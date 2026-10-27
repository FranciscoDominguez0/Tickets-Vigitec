<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class UserController extends Controller
{
    public function __construct(
        protected UserService $servicioUsuario
    ) {}

    /**
     * Muestra el directorio de usuarios/clientes con filtros y estadísticas.
     */
    public function listar(Request $solicitud): View
    {
        $busqueda = $solicitud->input('q');
        $estado = $solicitud->input('estado');

        $usuarios = $this->servicioUsuario->obtenerDirectorio($busqueda, $estado);
        $estadisticas = $this->servicioUsuario->obtenerEstadisticas();

        return view('agent.users.index', compact('usuarios', 'estadisticas', 'busqueda', 'estado'));
    }

    /**
     * Muestra el formulario para registrar un nuevo usuario.
     */
    public function crear(): View
    {
        return view('agent.users.create');
    }

    /**
     * Almacena un nuevo usuario en el sistema.
     */
    public function guardar(StoreUserRequest $solicitud): RedirectResponse
    {
        $usuario = $this->servicioUsuario->registrarUsuario($solicitud->validated());

        return redirect()
            ->route('agent.users.show', $usuario->id)
            ->with('exito', 'Usuario registrado correctamente.');
    }

    /**
     * Muestra la información detallada y tickets del usuario.
     */
    public function mostrar(int $id): View
    {
        $usuario = $this->servicioUsuario->obtenerPorId($id);

        return view('agent.users.show', compact('usuario'));
    }

    /**
     * Muestra el formulario para editar un usuario.
     */
    public function editar(int $id): View
    {
        $usuario = $this->servicioUsuario->obtenerPorId($id);

        return view('agent.users.edit', compact('usuario'));
    }

    /**
     * Actualiza la información del usuario.
     */
    public function actualizar(UpdateUserRequest $solicitud, int $id): RedirectResponse
    {
        $this->servicioUsuario->actualizarUsuario($id, $solicitud->validated());

        return redirect()
            ->route('agent.users.show', $id)
            ->with('exito', 'Usuario actualizado correctamente.');
    }

    /**
     * Cambia el estado de activación del usuario.
     */
    public function cambiarEstado(int $id): RedirectResponse
    {
        $usuario = $this->servicioUsuario->conmutarEstado($id);
        $mensaje = $usuario->status == 1 ? 'Usuario activado con éxito.' : 'Usuario desactivado con éxito.';

        return back()->with('exito', $mensaje);
    }

    /**
     * Elimina el usuario si no posee historial de tickets.
     */
    public function eliminar(int $id): RedirectResponse
    {
        try {
            $this->servicioUsuario->eliminarUsuario($id);

            return redirect()
                ->route('agent.users.directory')
                ->with('exito', 'Usuario eliminado con éxito.');
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
