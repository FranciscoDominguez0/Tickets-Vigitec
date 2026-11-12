<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActualizarDepartamentoRequest;
use App\Http\Requests\Admin\CrearDepartamentoRequest;
use App\Http\Requests\Admin\ProcesarDepartamentosMasivoRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartamentoController extends Controller
{
    /**
     * Muestra el listado de departamentos con sus estadísticas.
     * Ruta: /admin/departments
     */
    public function listar(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        $departamentos = Department::with('cuentaCorreo')
            ->where('empresa_id', $empresaId)
            ->orderBy('name', 'asc')
            ->get();

        $conteoActivos = 0;
        $conteoInactivos = 0;

        foreach ($departamentos as $departamento) {
            $departamento->total_agentes = $departamento->totalAgentes();
            $departamento->total_tickets = $departamento->totalTickets();

            if ($departamento->is_active) {
                $conteoActivos++;
            } else {
                $conteoInactivos++;
            }
        }

        $totalDepartamentos = $departamentos->count();

        return view('admin.departments.index', compact(
            'departamentos',
            'conteoActivos',
            'conteoInactivos',
            'totalDepartamentos'
        ));
    }

    /**
     * Crea un nuevo departamento en el sistema.
     */
    public function guardar(CrearDepartamentoRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        Department::create([
            'empresa_id' => $empresaId,
            'name' => trim((string) $solicitud->input('name')),
            'description' => $solicitud->filled('description') ? trim((string) $solicitud->input('description')) : null,
            'is_active' => $solicitud->boolean('is_active', true),
            'requires_report' => $solicitud->boolean('requires_report', false),
            'created' => now(),
        ]);

        return redirect()
            ->route('admin.departments')
            ->with('exito', 'Departamento creado correctamente.');
    }

    /**
     * Actualiza la información de un departamento existente.
     */
    public function actualizar(ActualizarDepartamentoRequest $solicitud, int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        $departamento = Department::where('empresa_id', $empresaId)->findOrFail($id);

        $departamento->update([
            'name' => trim((string) $solicitud->input('name')),
            'description' => $solicitud->filled('description') ? trim((string) $solicitud->input('description')) : null,
            'is_active' => $solicitud->boolean('is_active', false),
            'requires_report' => $solicitud->boolean('requires_report', false),
        ]);

        return redirect()
            ->route('admin.departments')
            ->with('exito', 'Departamento actualizado correctamente.');
    }

    /**
     * Procesa acciones masivas sobre los departamentos seleccionados (habilitar, deshabilitar, eliminar).
     */
    public function procesarMasivo(ProcesarDepartamentosMasivoRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $accion = (string) ($solicitud->input('accion') ?: $solicitud->input('a'));
        $ids = array_values(array_unique(array_filter(array_map('intval', (array) $solicitud->input('ids', [])), fn ($v) => $v > 0)));

        if (empty($ids)) {
            return redirect()
                ->route('admin.departments')
                ->with('error', 'Debe seleccionar al menos un departamento.');
        }

        if ($accion === 'habilitar' || $accion === 'enable') {
            Department::where('empresa_id', $empresaId)
                ->whereIn('id', $ids)
                ->update(['is_active' => true]);

            return redirect()
                ->route('admin.departments')
                ->with('exito', 'Departamentos habilitados correctamente.');
        }

        if ($accion === 'deshabilitar' || $accion === 'disable') {
            Department::where('empresa_id', $empresaId)
                ->whereIn('id', $ids)
                ->update(['is_active' => false]);

            return redirect()
                ->route('admin.departments')
                ->with('exito', 'Departamentos deshabilitados correctamente.');
        }

        if ($accion === 'eliminar' || $accion === 'delete') {
            $departamentos = Department::where('empresa_id', $empresaId)
                ->whereIn('id', $ids)
                ->get();

            $departamentosEnUso = [];
            $idsParaEliminar = [];

            foreach ($departamentos as $dept) {
                if ($dept->estaEnUso()) {
                    $departamentosEnUso[] = $dept->name;
                } else {
                    $idsParaEliminar[] = $dept->id;
                }
            }

            if (! empty($departamentosEnUso) && empty($idsParaEliminar)) {
                return redirect()
                    ->route('admin.departments')
                    ->with('error', 'No se pueden eliminar los departamentos seleccionados porque tienen agentes, tickets o temas asociados ('.implode(', ', $departamentosEnUso).').');
            }

            if (! empty($idsParaEliminar)) {
                Department::where('empresa_id', $empresaId)
                    ->whereIn('id', $idsParaEliminar)
                    ->delete();
            }

            if (! empty($departamentosEnUso)) {
                return redirect()
                    ->route('admin.departments')
                    ->with('alerta', 'Se eliminaron los departamentos no utilizados. Los siguientes departamentos no se pudieron eliminar por tener registros vinculados: '.implode(', ', $departamentosEnUso));
            }

            return redirect()
                ->route('admin.departments')
                ->with('exito', 'Departamentos eliminados correctamente.');
        }

        return redirect()
            ->route('admin.departments')
            ->with('error', 'Acción no reconocida.');
    }
}
