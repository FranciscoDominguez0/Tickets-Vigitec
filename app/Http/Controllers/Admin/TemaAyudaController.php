<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActualizarTemaAyudaRequest;
use App\Http\Requests\Admin\GuardarTemaAyudaRequest;
use App\Http\Requests\Admin\ProcesarTemasAyudaMasivoRequest;
use App\Models\Department;
use App\Models\HelpTopic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemaAyudaController extends Controller
{
    /**
     * Muestra el listado de temas de ayuda con filtros y métricas.
     * Ruta: /admin/helptopics
     */
    public function listar(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $busqueda = trim((string) $solicitud->input('q', ''));
        $filtroDept = (int) $solicitud->input('dept_id', 0);
        $filtroEstado = (string) $solicitud->input('estado', 'todos');
        $filtroVisibilidad = (string) $solicitud->input('visibilidad', 'todos');

        $consulta = HelpTopic::with('departamento')
            ->where('empresa_id', $empresaId);

        if (! empty($busqueda)) {
            $consulta->where(function ($sub) use ($busqueda) {
                $sub->where('name', 'like', "%{$busqueda}%")
                    ->orWhere('description', 'like', "%{$busqueda}%");
            });
        }

        if ($filtroDept > 0) {
            $consulta->where('dept_id', $filtroDept);
        }

        if ($filtroEstado === 'activos') {
            $consulta->where('is_active', 1);
        } elseif ($filtroEstado === 'inactivos') {
            $consulta->where('is_active', 0);
        }

        if ($filtroVisibilidad === 'publicos') {
            $consulta->where('is_public', 1);
        } elseif ($filtroVisibilidad === 'internos') {
            $consulta->where('is_public', 0);
        }

        $temas = $consulta->orderBy('name', 'asc')->get();

        foreach ($temas as $tema) {
            $tema->total_tickets = $tema->totalTickets();
        }

        $departamentos = Department::where('empresa_id', $empresaId)
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();

        $todosLosTemas = HelpTopic::where('empresa_id', $empresaId)->get();

        $estadisticas = [
            'total' => $todosLosTemas->count(),
            'activos' => $todosLosTemas->where('is_active', true)->count(),
            'inactivos' => $todosLosTemas->where('is_active', false)->count(),
            'publicos' => $todosLosTemas->where('is_public', true)->count(),
            'internos' => $todosLosTemas->where('is_public', false)->count(),
        ];

        return view('admin.helptopics.index', compact(
            'temas',
            'departamentos',
            'estadisticas',
            'busqueda',
            'filtroDept',
            'filtroEstado',
            'filtroVisibilidad'
        ));
    }

    /**
     * Registra un nuevo tema de ayuda.
     */
    public function guardar(GuardarTemaAyudaRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        HelpTopic::create([
            'empresa_id' => $empresaId,
            'name' => trim((string) $solicitud->input('name')),
            'dept_id' => (int) $solicitud->input('dept_id'),
            'description' => $solicitud->filled('description') ? trim((string) $solicitud->input('description')) : null,
            'is_active' => $solicitud->boolean('is_active', true) ? 1 : 0,
            'is_public' => $solicitud->boolean('is_public', true) ? 1 : 0,
            'created' => now(),
        ]);

        return redirect()->route('admin.helptopics')
            ->with('success', 'Tema de ayuda creado exitosamente.');
    }

    /**
     * Actualiza un tema de ayuda existente.
     */
    public function actualizar(ActualizarTemaAyudaRequest $solicitud, int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $tema = HelpTopic::where('empresa_id', $empresaId)->findOrFail($id);

        $tema->update([
            'name' => trim((string) $solicitud->input('name')),
            'dept_id' => (int) $solicitud->input('dept_id'),
            'description' => $solicitud->filled('description') ? trim((string) $solicitud->input('description')) : null,
            'is_active' => $solicitud->boolean('is_active', false) ? 1 : 0,
            'is_public' => $solicitud->boolean('is_public', false) ? 1 : 0,
        ]);

        return redirect()->route('admin.helptopics')
            ->with('success', 'Tema de ayuda actualizado correctamente.');
    }

    /**
     * Alterna rápidamente el estado activo/inactivo de un tema de ayuda.
     */
    public function alternarEstado(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $tema = HelpTopic::where('empresa_id', $empresaId)->findOrFail($id);

        $nuevoEstado = ! $tema->is_active;
        $tema->update(['is_active' => $nuevoEstado ? 1 : 0]);

        $mensaje = $nuevoEstado
            ? "El tema '{$tema->name}' ha sido habilitado."
            : "El tema '{$tema->name}' ha sido deshabilitado.";

        return redirect()->route('admin.helptopics')->with('success', $mensaje);
    }

    /**
     * Elimina un tema de ayuda si no tiene tickets asociados.
     */
    public function eliminar(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $tema = HelpTopic::where('empresa_id', $empresaId)->findOrFail($id);

        if ($tema->estaEnUso()) {
            return redirect()->route('admin.helptopics')
                ->with('error', "No se puede eliminar el tema '{$tema->name}' porque tiene tickets asociados en el sistema. Puedes desactivarlo en su lugar.");
        }

        $nombre = $tema->name;
        $tema->delete();

        return redirect()->route('admin.helptopics')
            ->with('success', "Tema de ayuda '{$nombre}' eliminado correctamente.");
    }

    /**
     * Procesa acciones masivas sobre los temas seleccionados.
     */
    public function procesarMasivo(ProcesarTemasAyudaMasivoRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $ids = array_values(array_unique(array_filter(array_map('intval', (array) $solicitud->input('ids', [])))));
        $accion = (string) $solicitud->input('accion');

        if (empty($ids)) {
            return redirect()->route('admin.helptopics')
                ->with('error', 'Debes seleccionar al menos un tema de ayuda.');
        }

        if (in_array($accion, ['habilitar', 'enable'], true)) {
            HelpTopic::where('empresa_id', $empresaId)->whereIn('id', $ids)->update(['is_active' => 1]);

            return redirect()->route('admin.helptopics')
                ->with('success', 'Temas de ayuda habilitados correctamente.');
        }

        if (in_array($accion, ['deshabilitar', 'disable'], true)) {
            HelpTopic::where('empresa_id', $empresaId)->whereIn('id', $ids)->update(['is_active' => 0]);

            return redirect()->route('admin.helptopics')
                ->with('success', 'Temas de ayuda deshabilitados correctamente.');
        }

        if ($accion === 'hacer_publico') {
            HelpTopic::where('empresa_id', $empresaId)->whereIn('id', $ids)->update(['is_public' => 1]);

            return redirect()->route('admin.helptopics')
                ->with('success', 'Temas configurados como visibles para el portal de clientes.');
        }

        if ($accion === 'hacer_privado') {
            HelpTopic::where('empresa_id', $empresaId)->whereIn('id', $ids)->update(['is_public' => 0]);

            return redirect()->route('admin.helptopics')
                ->with('success', 'Temas configurados como uso interno para agentes.');
        }

        if (in_array($accion, ['eliminar', 'delete'], true)) {
            $temas = HelpTopic::where('empresa_id', $empresaId)->whereIn('id', $ids)->get();
            $temasEnUso = [];
            $idsParaEliminar = [];

            foreach ($temas as $tema) {
                if ($tema->estaEnUso()) {
                    $temasEnUso[] = $tema->name;
                } else {
                    $idsParaEliminar[] = $tema->id;
                }
            }

            if (! empty($idsParaEliminar)) {
                HelpTopic::where('empresa_id', $empresaId)->whereIn('id', $idsParaEliminar)->delete();
            }

            if (! empty($temasEnUso)) {
                $mensajeAviso = 'Se eliminaron '.count($idsParaEliminar).' temas. No se pudieron eliminar los siguientes temas porque tienen tickets asociados: '.implode(', ', $temasEnUso).'.';

                return redirect()->route('admin.helptopics')->with('warning', $mensajeAviso);
            }

            return redirect()->route('admin.helptopics')
                ->with('success', 'Temas de ayuda eliminados correctamente.');
        }

        return redirect()->route('admin.helptopics')->with('error', 'Acción no reconocida.');
    }
}
