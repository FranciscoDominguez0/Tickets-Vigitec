<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActualizarSecuenciaRequest;
use App\Http\Requests\Admin\GuardarSecuenciaRequest;
use App\Models\AppSetting;
use App\Models\Sequence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecuenciaController extends Controller
{
    /**
     * Muestra el listado de secuencias numéricas registradas.
     * Ruta: /admin/sequences o /admin/settings/tickets/sequences
     */
    public function listar(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        $secuencias = Sequence::where('empresa_id', $empresaId)
            ->orderBy('id', 'asc')
            ->get();

        $secuenciaActivaId = (string) AppSetting::obtener('tickets.ticket_sequence_id', '0');

        return view('admin.settings.sequences', compact('secuencias', 'secuenciaActivaId'));
    }

    /**
     * Registra una nueva secuencia numérica.
     */
    public function guardar(GuardarSecuenciaRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        Sequence::create([
            'empresa_id' => $empresaId,
            'name' => trim((string) $solicitud->validated('name')),
            'next' => (int) $solicitud->validated('next'),
            'increment' => (int) $solicitud->validated('increment'),
            'padding' => (int) $solicitud->validated('padding'),
            'created' => now(),
            'updated' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Secuencia numérica creada exitosamente.');
    }

    /**
     * Actualiza una secuencia numérica existente.
     */
    public function actualizar(ActualizarSecuenciaRequest $solicitud, int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $secuencia = Sequence::where('empresa_id', $empresaId)->findOrFail($id);

        $secuencia->update([
            'name' => trim((string) $solicitud->validated('name')),
            'next' => (int) $solicitud->validated('next'),
            'increment' => (int) $solicitud->validated('increment'),
            'padding' => (int) $solicitud->validated('padding'),
            'updated' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Secuencia numérica actualizada exitosamente.');
    }

    /**
     * Elimina una secuencia numérica si no está en uso activo en la configuración.
     */
    public function eliminar(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $secuencia = Sequence::where('empresa_id', $empresaId)->findOrFail($id);

        $secuenciaActivaId = (string) AppSetting::obtener('tickets.ticket_sequence_id', '0');
        if ($secuenciaActivaId === (string) $secuencia->id) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar la secuencia "'.$secuencia->name.'" porque está configurada como la secuencia activa para la numeración de tickets.');
        }

        $secuencia->delete();

        return redirect()->back()
            ->with('success', 'Secuencia numérica eliminada correctamente.');
    }
}
