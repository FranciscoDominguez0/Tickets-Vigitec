<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActualizarPersonalRequest;
use App\Http\Requests\Admin\CrearPersonalRequest;
use App\Http\Requests\Admin\ProcesarPersonalMasivoRequest;
use App\Http\Requests\Admin\RestablecerClavePersonalRequest;
use App\Models\Department;
use App\Models\Role;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PersonalController extends Controller
{
    /**
     * Muestra el listado de agentes del personal con filtros y estadísticas.
     * Ruta: /admin/staff
     */
    public function agentes(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $busqueda = trim((string) $solicitud->input('q', ''));
        $filtroDept = (int) $solicitud->input('dept_id', 0);
        $filtroRol = (string) $solicitud->input('role', 'todos');
        $filtroEstado = (string) $solicitud->input('estado', 'todos');

        $consulta = Staff::with(['department', 'departamentosAdicionales'])
            ->where('empresa_id', $empresaId);

        if (! empty($busqueda)) {
            $consulta->where(function ($sub) use ($busqueda) {
                $sub->where('firstname', 'like', "%{$busqueda}%")
                    ->orWhere('lastname', 'like', "%{$busqueda}%")
                    ->orWhere('username', 'like', "%{$busqueda}%")
                    ->orWhere('email', 'like', "%{$busqueda}%");
            });
        }

        if ($filtroDept > 0) {
            $consulta->where(function ($sub) use ($filtroDept) {
                $sub->where('dept_id', $filtroDept)
                    ->orWhereHas('departamentosAdicionales', function ($q) use ($filtroDept) {
                        $q->where('departments.id', $filtroDept);
                    });
            });
        }

        if ($filtroRol !== 'todos') {
            $consulta->where('role', $filtroRol);
        }

        if ($filtroEstado === 'activos') {
            $consulta->where('is_active', 1);
        } elseif ($filtroEstado === 'inactivos') {
            $consulta->where('is_active', 0);
        }

        $agentes = $consulta->orderBy('firstname', 'asc')->get();

        foreach ($agentes as $agente) {
            $agente->total_tickets = $agente->totalTickets();
        }

        $todosLosAgentes = Staff::where('empresa_id', $empresaId)->get();

        $estadisticas = [
            'total' => $todosLosAgentes->count(),
            'activos' => $todosLosAgentes->where('is_active', true)->count(),
            'inactivos' => $todosLosAgentes->where('is_active', false)->count(),
            'administradores' => $todosLosAgentes->filter(fn ($a) => $a->esAdmin())->count(),
        ];

        $departamentos = Department::where('empresa_id', $empresaId)
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();

        $roles = Role::where('empresa_id', $empresaId)
            ->where('is_enabled', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.staff.index', compact(
            'agentes',
            'departamentos',
            'roles',
            'estadisticas',
            'busqueda',
            'filtroDept',
            'filtroRol',
            'filtroEstado'
        ));
    }

    /**
     * Registra un nuevo agente en el sistema.
     */
    public function guardar(CrearPersonalRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        $agente = Staff::create([
            'empresa_id' => $empresaId,
            'firstname' => trim((string) $solicitud->input('firstname')),
            'lastname' => trim((string) $solicitud->input('lastname')),
            'username' => trim((string) $solicitud->input('username')),
            'email' => strtolower(trim((string) $solicitud->input('email'))),
            'password' => Hash::make((string) $solicitud->input('password')),
            'dept_id' => $solicitud->filled('dept_id') ? (int) $solicitud->input('dept_id') : null,
            'role' => (string) $solicitud->input('role', 'agent'),
            'is_active' => $solicitud->boolean('is_active', true) ? 1 : 0,
            'signature' => $solicitud->filled('signature') ? trim((string) $solicitud->input('signature')) : null,
            'created' => now(),
            'updated' => now(),
        ]);

        if ($solicitud->has('departamentos_adicionales')) {
            $agente->departamentosAdicionales()->sync($solicitud->input('departamentos_adicionales', []));
        }

        return redirect()->route('admin.staff')
            ->with('success', "Agente '{$agente->nombreCompleto()}' creado exitosamente.");
    }

    /**
     * Actualiza la información de un agente existente.
     */
    public function actualizar(ActualizarPersonalRequest $solicitud, int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $agente = Staff::where('empresa_id', $empresaId)->findOrFail($id);

        $usuarioAutenticadoId = (int) auth('staff')->id();

        // Evitar que el administrador en sesión se desactive a sí mismo
        if ($agente->id === $usuarioAutenticadoId && ! $solicitud->boolean('is_active')) {
            return redirect()->route('admin.staff')
                ->with('error', 'No puedes desactivar tu propia cuenta mientras estés en sesión.');
        }

        // Evitar que el administrador en sesión se degrade de rol a sí mismo
        $nuevoRol = (string) $solicitud->input('role', 'agent');
        if ($agente->id === $usuarioAutenticadoId && ! in_array($nuevoRol, ['admin', 'superadmin'], true)) {
            return redirect()->route('admin.staff')
                ->with('error', 'No puedes quitarte el rol de administrador a ti mismo.');
        }

        $agente->update([
            'firstname' => trim((string) $solicitud->input('firstname')),
            'lastname' => trim((string) $solicitud->input('lastname')),
            'username' => trim((string) $solicitud->input('username')),
            'email' => strtolower(trim((string) $solicitud->input('email'))),
            'dept_id' => $solicitud->filled('dept_id') ? (int) $solicitud->input('dept_id') : null,
            'role' => $nuevoRol,
            'is_active' => $solicitud->boolean('is_active', false) ? 1 : 0,
            'signature' => $solicitud->filled('signature') ? trim((string) $solicitud->input('signature')) : null,
            'updated' => now(),
        ]);

        $departamentosAdicionales = (array) $solicitud->input('departamentos_adicionales', []);
        $agente->departamentosAdicionales()->sync($departamentosAdicionales);

        return redirect()->route('admin.staff')
            ->with('success', "Agente '{$agente->nombreCompleto()}' actualizado correctamente.");
    }

    /**
     * Restablece la contraseña de un agente.
     */
    public function restablecerClave(RestablecerClavePersonalRequest $solicitud, int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $agente = Staff::where('empresa_id', $empresaId)->findOrFail($id);

        $agente->update([
            'password' => Hash::make((string) $solicitud->input('password')),
            'updated' => now(),
        ]);

        return redirect()->route('admin.staff')
            ->with('success', "La contraseña de '{$agente->nombreCompleto()}' ha sido restablecida exitosamente.");
    }

    /**
     * Alterna rápidamente el estado activo/inactivo de un agente.
     */
    public function alternarEstado(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $agente = Staff::where('empresa_id', $empresaId)->findOrFail($id);

        if ($agente->id === (int) auth('staff')->id()) {
            return redirect()->route('admin.staff')
                ->with('error', 'No puedes desactivar tu propia cuenta mientras estés en sesión.');
        }

        $nuevoEstado = ! $agente->is_active;
        $agente->update([
            'is_active' => $nuevoEstado ? 1 : 0,
            'updated' => now(),
        ]);

        $mensaje = $nuevoEstado
            ? "El agente '{$agente->nombreCompleto()}' ha sido habilitado."
            : "El agente '{$agente->nombreCompleto()}' ha sido deshabilitado.";

        return redirect()->route('admin.staff')->with('success', $mensaje);
    }

    /**
     * Elimina un agente del sistema si no tiene tickets asociados.
     */
    public function eliminar(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $agente = Staff::where('empresa_id', $empresaId)->findOrFail($id);

        if ($agente->id === (int) auth('staff')->id()) {
            return redirect()->route('admin.staff')
                ->with('error', 'No puedes eliminar tu propia cuenta mientras estés en sesión.');
        }

        if ($agente->estaEnUso()) {
            return redirect()->route('admin.staff')
                ->with('error', "No se puede eliminar al agente '{$agente->nombreCompleto()}' porque tiene tickets asignados o históricos en el sistema. Puedes desactivar su cuenta en su lugar.");
        }

        $nombre = $agente->nombreCompleto();
        $agente->departamentosAdicionales()->detach();
        $agente->delete();

        return redirect()->route('admin.staff')
            ->with('success', "Agente '{$nombre}' eliminado correctamente.");
    }

    /**
     * Procesa acciones masivas sobre el personal seleccionado.
     */
    public function procesarMasivo(ProcesarPersonalMasivoRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $usuarioAutenticadoId = (int) auth('staff')->id();

        $ids = array_values(array_unique(array_filter(array_map('intval', (array) $solicitud->input('ids', [])))));
        $accion = (string) $solicitud->input('accion');

        // Filtrar al usuario en sesión para proteger la cuenta activa
        $ids = array_diff($ids, [$usuarioAutenticadoId]);

        if (empty($ids)) {
            return redirect()->route('admin.staff')
                ->with('error', 'No se puede aplicar la acción sobre las cuentas seleccionadas (tu propia cuenta está protegida).');
        }

        if ($accion === 'activar') {
            Staff::where('empresa_id', $empresaId)->whereIn('id', $ids)->update([
                'is_active' => 1,
                'updated' => now(),
            ]);

            return redirect()->route('admin.staff')
                ->with('success', 'Agentes habilitados correctamente.');
        }

        if ($accion === 'desactivar') {
            Staff::where('empresa_id', $empresaId)->whereIn('id', $ids)->update([
                'is_active' => 0,
                'updated' => now(),
            ]);

            return redirect()->route('admin.staff')
                ->with('success', 'Agentes deshabilitados correctamente.');
        }

        if ($accion === 'eliminar') {
            $agentes = Staff::where('empresa_id', $empresaId)->whereIn('id', $ids)->get();
            $agentesEnUso = [];
            $idsParaEliminar = [];

            foreach ($agentes as $agente) {
                if ($agente->estaEnUso()) {
                    $agentesEnUso[] = $agente->nombreCompleto();
                } else {
                    $idsParaEliminar[] = $agente->id;
                }
            }

            if (! empty($idsParaEliminar)) {
                foreach ($agentes->whereIn('id', $idsParaEliminar) as $agenteEliminar) {
                    $agenteEliminar->departamentosAdicionales()->detach();
                }
                Staff::where('empresa_id', $empresaId)->whereIn('id', $idsParaEliminar)->delete();
            }

            if (! empty($agentesEnUso)) {
                $mensajeAviso = 'Se eliminaron '.count($idsParaEliminar).' agentes. Los siguientes agentes no se pudieron eliminar porque tienen tickets asociados: '.implode(', ', $agentesEnUso).'. Puedes desactivarlos.';

                return redirect()->route('admin.staff')->with('warning', $mensajeAviso);
            }

            return redirect()->route('admin.staff')
                ->with('success', 'Agentes eliminados correctamente.');
        }

        return redirect()->route('admin.staff')->with('error', 'Acción masiva no reconocida.');
    }
}
