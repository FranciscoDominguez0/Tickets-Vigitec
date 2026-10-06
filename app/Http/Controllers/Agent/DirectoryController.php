<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Staff;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    public function index(Request $request)
    {
        $departamentoId = $request->input('did');
        $busqueda = $request->input('q');

        // Construir la consulta
        $consulta = Staff::with('department')
            ->withCount(['tickets as total_asignados', 'tickets as abiertos' => function ($query) {
                $query->whereIn('status_id', [1]); 
            }]);

        if ($departamentoId) {
            $consulta->where('dept_id', $departamentoId);
        }

        if ($busqueda) {
            $consulta->where(function ($q) use ($busqueda) {
                $q->where('firstname', 'like', '%' . $busqueda . '%')
                  ->orWhere('lastname', 'like', '%' . $busqueda . '%')
                  ->orWhere('username', 'like', '%' . $busqueda . '%')
                  ->orWhere('email', 'like', '%' . $busqueda . '%');
            });
        }

        $totalAgentes = $consulta->count();
        $agentes = $consulta->paginate(15);
        $departamentos = Department::all();

        return view('agent.directory.index', compact('agentes', 'departamentos', 'departamentoId', 'busqueda', 'totalAgentes'));
    }
}
