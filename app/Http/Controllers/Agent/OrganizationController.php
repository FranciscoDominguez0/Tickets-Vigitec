<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\StoreOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function __construct(
        protected OrganizationService $servicioOrganizacion
    ) {}

    /**
     * Muestra el directorio de organizaciones con métricas y búsqueda.
     */
    public function listar(Request $solicitud): View
    {
        $busqueda = $solicitud->input('q');
        $organizaciones = $this->servicioOrganizacion->obtenerListado($busqueda);
        $estadisticas = $this->servicioOrganizacion->obtenerEstadisticas();

        return view('agent.users.orgs', compact('organizaciones', 'estadisticas', 'busqueda'));
    }

    /**
     * Registra una nueva organización.
     */
    public function guardar(StoreOrganizationRequest $solicitud): RedirectResponse|JsonResponse
    {
        $organizacion = $this->servicioOrganizacion->registrarOrganizacion($solicitud->validated());

        if ($solicitud->wantsJson() || $solicitud->ajax()) {
            return response()->json([
                'exito' => true,
                'mensaje' => 'Organización creada exitosamente.',
                'organizacion' => $organizacion,
            ], 201);
        }

        return redirect()
            ->route('agent.organizations.show', $organizacion->id)
            ->with('exito', 'Organización creada exitosamente.');
    }

    /**
     * Muestra la vista detallada de una organización con pestañas de miembros y tickets.
     */
    public function mostrar(Request $solicitud, int $id): View
    {
        $organizacion = $this->servicioOrganizacion->obtenerPorId($id);
        $pestanaActiva = $solicitud->input('t', 'usuarios');

        $usuarios = $this->servicioOrganizacion->obtenerUsuarios($organizacion);
        $tickets = $this->servicioOrganizacion->obtenerTickets($organizacion);

        // Candidatos de usuarios para vincular en el modal
        $usuariosCandidatos = User::whereNull('organization_id')
            ->orWhere('company', '!=', $organizacion->name)
            ->orderBy('firstname')
            ->take(50)
            ->get();

        return view('agent.users.orgs_show', compact(
            'organizacion',
            'pestanaActiva',
            'usuarios',
            'tickets',
            'usuariosCandidatos'
        ));
    }

    /**
     * Actualiza la información de la organización.
     */
    public function actualizar(UpdateOrganizationRequest $solicitud, int $id): RedirectResponse
    {
        $this->servicioOrganizacion->actualizarOrganizacion($id, $solicitud->validated());

        return redirect()
            ->route('agent.organizations.show', $id)
            ->with('exito', 'Organización actualizada exitosamente.');
    }

    /**
     * Elimina una organización del sistema.
     */
    public function eliminar(int $id): RedirectResponse
    {
        $this->servicioOrganizacion->eliminarOrganizacion($id);

        return redirect()
            ->route('agent.users.orgs')
            ->with('exito', 'Organización eliminada exitosamente.');
    }

    /**
     * Vincula a un usuario existente a la organización.
     */
    public function vincularUsuario(Request $solicitud, int $id): RedirectResponse|JsonResponse
    {
        $solicitud->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ], [
            'user_id.required' => 'Debes seleccionar un usuario para vincular.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
        ]);

        $this->servicioOrganizacion->agregarMiembro($id, (int) $solicitud->input('user_id'));

        if ($solicitud->wantsJson() || $solicitud->ajax()) {
            return response()->json([
                'exito' => true,
                'mensaje' => 'Usuario vinculado correctamente.',
            ]);
        }

        return redirect()
            ->route('agent.organizations.show', ['id' => $id, 't' => 'usuarios'])
            ->with('exito', 'Usuario vinculado a la organización con éxito.');
    }

    /**
     * Remueve la vinculación de un usuario con la organización.
     */
    public function desvincularUsuario(int $id, int $usuarioId): RedirectResponse
    {
        $this->servicioOrganizacion->removerMiembro($id, $usuarioId);

        return redirect()
            ->route('agent.organizations.show', ['id' => $id, 't' => 'usuarios'])
            ->with('exito', 'Usuario desvinculado de la organización con éxito.');
    }
}
