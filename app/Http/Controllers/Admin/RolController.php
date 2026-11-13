<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CrearRolRequest;
use App\Http\Requests\Admin\ProcesarRolesMasivoRequest;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RolController extends Controller
{
    /**
     * Muestra el listado de roles con sus estadísticas de agentes.
     * Ruta: /admin/roles
     */
    public function listar(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        // Si la empresa no tiene roles registrados, sembrar los roles iniciales por defecto
        if (Role::where('empresa_id', $empresaId)->count() === 0) {
            $rolesIniciales = ['admin', 'supervisor', 'agent'];
            foreach ($rolesIniciales as $rol) {
                Role::create([
                    'empresa_id' => $empresaId,
                    'name' => $rol,
                    'is_enabled' => true,
                ]);
            }
        }

        $roles = Role::where('empresa_id', $empresaId)
            ->orderBy('name', 'asc')
            ->get();

        // Enriquecer cada rol con totales de agentes
        foreach ($roles as $rol) {
            $rol->total_agentes = $rol->totalAgentes();
            $rol->total_activos = $rol->totalAgentesActivos();
        }

        $rolesTotal = $roles->count();
        $agentesTotal = Staff::where('empresa_id', $empresaId)->count();

        return view('admin.roles.index', compact('roles', 'rolesTotal', 'agentesTotal'));
    }

    /**
     * Crea un nuevo rol en el sistema.
     */
    public function guardar(CrearRolRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $nombreRol = trim((string) $solicitud->input('name'));

        Role::create([
            'empresa_id' => $empresaId,
            'name' => $nombreRol,
            'is_enabled' => true,
        ]);

        return redirect()
            ->route('admin.roles')
            ->with('exito', 'Rol creado correctamente.');
    }

    /**
     * Procesa acciones masivas sobre los roles seleccionados (habilitar, deshabilitar, eliminar).
     */
    public function procesarMasivo(ProcesarRolesMasivoRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $accion = (string) ($solicitud->input('accion') ?: $solicitud->input('a'));
        $ids = array_values(array_unique(array_filter(array_map('intval', (array) $solicitud->input('ids', [])), fn ($v) => $v > 0)));

        if (empty($ids)) {
            return redirect()
                ->route('admin.roles')
                ->with('error', 'Debe seleccionar al menos un rol.');
        }

        if ($accion === 'habilitar' || $accion === 'enable') {
            Role::where('empresa_id', $empresaId)
                ->whereIn('id', $ids)
                ->update(['is_enabled' => true]);

            return redirect()
                ->route('admin.roles')
                ->with('exito', 'Roles habilitados correctamente.');
        }

        if ($accion === 'deshabilitar' || $accion === 'disable') {
            Role::where('empresa_id', $empresaId)
                ->whereIn('id', $ids)
                ->update(['is_enabled' => false]);

            return redirect()
                ->route('admin.roles')
                ->with('exito', 'Roles deshabilitados correctamente.');
        }

        if ($accion === 'eliminar' || $accion === 'delete') {
            $rolesParaEliminar = Role::where('empresa_id', $empresaId)
                ->whereIn('id', $ids)
                ->get();

            $enUso = false;
            foreach ($rolesParaEliminar as $rol) {
                if ($rol->estaEnUso()) {
                    $enUso = true;
                    break;
                }
            }

            if ($enUso) {
                return redirect()
                    ->route('admin.roles')
                    ->with('error', 'No se pueden eliminar roles que están en uso por agentes.');
            }

            $nombresRoles = $rolesParaEliminar->pluck('name')->toArray();

            Role::where('empresa_id', $empresaId)
                ->whereIn('id', $ids)
                ->delete();

            RolePermission::where('empresa_id', $empresaId)
                ->whereIn('role_name', $nombresRoles)
                ->delete();

            return redirect()
                ->route('admin.roles')
                ->with('exito', 'Roles eliminados correctamente.');
        }

        return redirect()
            ->route('admin.roles')
            ->with('error', 'Acción no reconocida.');
    }

    /**
     * Muestra la matriz de permisos para un rol específico.
     * Ruta: /admin/roles/{nombre}/permissions
     */
    public function mostrarPermisos(string $nombre): View|RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $rol = Role::where('empresa_id', $empresaId)->where('name', $nombre)->first();

        if (! $rol) {
            return redirect()
                ->route('admin.roles')
                ->with('error', 'Rol no encontrado.');
        }

        $gruposPermisos = self::obtenerGruposPermisos();
        $permisosAsignados = RolePermission::where('empresa_id', $empresaId)
            ->where('role_name', $rol->name)
            ->where('is_enabled', true)
            ->pluck('perm_key')
            ->flip()
            ->toArray();

        return view('admin.roles.permissions', compact('rol', 'gruposPermisos', 'permisosAsignados'));
    }

    /**
     * Guarda los permisos actualizados de un rol.
     */
    public function guardarPermisos(Request $solicitud, string $nombre): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $rol = Role::where('empresa_id', $empresaId)->where('name', $nombre)->first();

        if (! $rol) {
            return redirect()
                ->route('admin.roles')
                ->with('error', 'Rol no encontrado.');
        }

        $accion = (string) $solicitud->input('do', 'save');

        if ($accion === 'reset') {
            RolePermission::where('empresa_id', $empresaId)
                ->where('role_name', $rol->name)
                ->delete();

            return redirect()
                ->route('admin.roles.permissions', ['nombre' => $rol->name])
                ->with('exito', 'Permisos restablecidos.');
        }

        // Eliminar permisos previos del rol y guardar la selección
        RolePermission::where('empresa_id', $empresaId)
            ->where('role_name', $rol->name)
            ->delete();

        $clavesSeleccionadas = (array) $solicitud->input('perms', []);
        $grupos = self::obtenerGruposPermisos();
        $clavesValidas = [];
        foreach ($grupos as $grupo) {
            foreach ($grupo as $clave => $meta) {
                $clavesValidas[$clave] = true;
            }
        }

        $registros = [];
        foreach ($clavesSeleccionadas as $clave) {
            $clave = trim((string) $clave);
            if ($clave !== '' && isset($clavesValidas[$clave])) {
                $registros[] = [
                    'empresa_id' => $empresaId,
                    'role_name' => $rol->name,
                    'perm_key' => $clave,
                    'is_enabled' => true,
                ];
            }
        }

        if (! empty($registros)) {
            RolePermission::insert($registros);
        }

        return redirect()
            ->route('admin.roles.permissions', ['nombre' => $rol->name])
            ->with('exito', 'Permisos actualizados correctamente.');
    }

    /**
     * Retorna el catálogo estructurado de permisos agrupados.
     *
     * @return array<string, array<string, array<string, string>>>
     */
    public static function obtenerGruposPermisos(): array
    {
        return [
            'Tickets' => [
                'ticket.assign' => ['titulo' => 'Asignar', 'descripcion' => 'Asignar tickets a moderadores o equipos'],
                'ticket.close' => ['titulo' => 'Cerrar', 'descripcion' => 'Cerrar tickets'],
                'ticket.create' => ['titulo' => 'Crear', 'descripcion' => 'Abrir tickets en nombre de los usuarios'],
                'ticket.delete' => ['titulo' => 'Eliminar', 'descripcion' => 'Eliminar tickets'],
                'ticket.edit' => ['titulo' => 'Editar', 'descripcion' => 'Modificar tickets'],
                'ticket.edit_thread' => ['titulo' => 'Editar Asunto', 'descripcion' => 'Editar los elementos del hilo de otros moderadores'],
                'ticket.link' => ['titulo' => 'Vincular', 'descripcion' => 'Posibilidad de vincular tickets entre sí'],
                'ticket.markanswered' => ['titulo' => 'Marcar como contestados', 'descripcion' => 'Posibilidad de marcar un ticket como respondido o no respondido'],
                'ticket.merge' => ['titulo' => 'Fusionar', 'descripcion' => 'Posibilidad de fusionar tickets'],
                'ticket.reply' => ['titulo' => 'Publicar Respuesta', 'descripcion' => 'Publicar una respuesta al ticket'],
                'ticket.refer' => ['titulo' => 'Referido', 'descripcion' => 'Habilidad para administrar tickets referidos'],
                'ticket.post' => ['titulo' => 'Publicado', 'descripcion' => 'Habilidad para realizar una asignación de ticket'],
                'ticket.transfer' => ['titulo' => 'Transferir', 'descripcion' => 'Transferir tickets entre departamentos'],
                'ticket.view_all' => ['titulo' => 'Ver todo', 'descripcion' => 'Habilidad para ver todos los tickets (si está desactivado, el agente solo verá los asignados a él)'],
                'ticket.reports' => ['titulo' => 'Reportes de tickets', 'descripcion' => 'Habilidad para acceder a la página de reportes de tickets'],
            ],
            'Tareas' => [
                'task.create' => ['titulo' => 'Crear', 'descripcion' => 'Crear tareas'],
                'task.edit' => ['titulo' => 'Editar', 'descripcion' => 'Editar tareas'],
                'task.close' => ['titulo' => 'Cerrar', 'descripcion' => 'Cerrar tareas'],
                'task.assign' => ['titulo' => 'Asignar', 'descripcion' => 'Asignar tareas'],
                'task.delete' => ['titulo' => 'Eliminar', 'descripcion' => 'Eliminar tareas'],
            ],
            'Directorio, Mapa y Estadísticas' => [
                'user.view' => ['titulo' => 'Ver usuarios', 'descripcion' => 'Habilidad para ver el directorio de usuarios'],
                'user.manage' => ['titulo' => 'Gestionar usuarios', 'descripcion' => 'Habilidad para crear, editar, eliminar y realizar acciones en usuarios'],
                'org.view' => ['titulo' => 'Ver organizaciones', 'descripcion' => 'Habilidad para ver el listado y detalles de organizaciones'],
                'org.manage' => ['titulo' => 'Gestionar organizaciones', 'descripcion' => 'Habilidad para crear, editar y eliminar organizaciones'],
                'org.reports' => ['titulo' => 'Informes a jefes', 'descripcion' => 'Crear y enviar informes a jefes de organización en su panel'],
                'agent.directory' => ['titulo' => 'Ver directorio de agentes', 'descripcion' => 'Habilidad para acceder al directorio del agente'],
                'agent.map' => ['titulo' => 'Ver mapa de agentes', 'descripcion' => 'Habilidad para acceder al mapa de agentes en tiempo real'],
                'stats.view' => ['titulo' => 'Ver estadísticas', 'descripcion' => 'Habilidad para visualizar el panel de estadísticas.'],
            ],
            'Administración' => [
                'admin.access' => ['titulo' => 'Acceso al Panel de Administración', 'descripcion' => 'Habilidad para acceder a la configuración, agentes, roles y configuraciones de correo electrónico.'],
            ],
            'Cotizaciones' => [
                'quote.view' => ['titulo' => 'Acceso a Cotizaciones', 'descripcion' => 'Habilidad para ver y gestionar cotizaciones.'],
            ],
            'Requisiciones e Inventario' => [
                'requisitions.view' => ['titulo' => 'Acceder a Requisiciones', 'descripcion' => 'Habilidad para ver y solicitar requisiciones de inventario.'],
                'requisitions.manage' => ['titulo' => 'Entregar Requisiciones', 'descripcion' => 'Habilidad para marcar requisiciones como entregadas.'],
                'requisitions.delete' => ['titulo' => 'Eliminar Requisiciones', 'descripcion' => 'Habilidad para eliminar requisiciones de inventario.'],
            ],
        ];
    }
}
