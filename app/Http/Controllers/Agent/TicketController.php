<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Department;
use App\Models\Priority;
use App\Models\User;

class TicketController extends Controller
{
    /**
     * Muestra la lista de tickets.
     */
    public function index()
    {
        return view('agent.tickets.index');
    }

    /**
     * Muestra el formulario para crear un nuevo ticket.
     */
    public function create()
    {
        $departments = Department::where('is_active', 1)->orderBy('name')->get();
        $priorities = Priority::orderBy('id')->get();
        
        // Obtenemos los primeros usuarios para fines de diseño de interfaz. 
        // En un entorno real se debe usar búsqueda asíncrona.
        $users = User::limit(10)->get(); 

        return view('agent.tickets.create', compact('departments', 'priorities', 'users'));
    }

    /**
     * Almacena un ticket recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'dept_id' => 'required|exists:departments,id',
            'priority_id' => 'required|exists:priorities,id',
            'body' => 'nullable|string',
            'walkin_phone' => 'nullable|string|max:20',
            'walkin_address' => 'nullable|string|max:255',
        ]);

        // Generación del número único de ticket
        $ticket = new Ticket();
        $ticket->ticket_number = 'TKT-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $ticket->user_id = $validated['user_id'];
        $ticket->subject = $validated['subject'];
        $ticket->dept_id = $validated['dept_id'];
        $ticket->priority_id = $validated['priority_id'];
        
        // Valores por defecto
        $ticket->empresa_id = auth('staff')->user()->empresa_id ?? 1;
        $ticket->status_id = 1; // Abierto
        $ticket->created = now();
        
        $ticket->save();

        if (!empty($validated['body'])) {
            // Primero se debe crear el hilo padre en la tabla threads
            $thread = $ticket->thread()->create([
                'created' => now(),
            ]);

            // Luego insertamos la respuesta inicial en thread_entries
            $thread->entries()->create([
                'empresa_id' => $ticket->empresa_id,
                'staff_id' => auth('staff')->id(),
                'body' => $validated['body'],
                'is_internal' => 0,
                'created' => now(),
            ]);
        }

        return redirect()->route('agent.tickets.index')->with('success', 'Ticket creado correctamente.');
    }

    /**
     * Muestra la vista detallada de un ticket específico.
     */
    public function show($id)
    {
        // Simulando datos para visualizar la interfaz
        $ticket = (object)[
            'id' => $id,
            'ticket_number' => 'TCK-100' . $id,
            'subject' => 'Problema con la conexión a internet',
            'status_id' => 1,
            'user' => (object)['firstname' => 'Juan', 'lastname' => 'Pérez'],
            'status' => (object)['name' => 'Abierto'],
            'priority' => (object)['name' => 'Alta'],
            'department' => (object)['name' => 'Soporte Técnico'],
            'staff' => (object)['firstname' => 'Agente', 'lastname' => 'Admin']
        ];
        return view('agent.ticket.show', compact('ticket'));
    }
}
