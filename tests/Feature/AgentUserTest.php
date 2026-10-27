<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Priority;
use App\Models\Staff;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AgentUserTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $agente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->agente = Staff::firstOrCreate(
            ['username' => 'test_agent_user'],
            [
                'email' => 'test_agent_user@test.com',
                'firstname' => 'Agente',
                'lastname' => 'Prueba',
                'password' => bcrypt('password123'),
                'empresa_id' => 1,
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }

    public function test_agente_puede_ver_directorio_de_usuarios(): void
    {
        $cliente = User::factory()->create([
            'firstname' => 'Cliente',
            'lastname' => 'Directorio',
            'email' => 'directorio@test.com',
        ]);

        $response = $this->actingAs($this->agente, 'staff')
            ->get(route('agent.users.directory'));

        $response->assertStatus(200);
        $response->assertSee('Directorio de Usuarios');
        $response->assertSee('Cliente Directorio');
        $response->assertSee('directorio@test.com');
    }

    public function test_agente_puede_buscar_usuarios(): void
    {
        User::factory()->create([
            'firstname' => 'Carlos',
            'lastname' => 'Buscado',
            'email' => 'carlos.buscado@test.com',
        ]);

        $response = $this->actingAs($this->agente, 'staff')
            ->get(route('agent.users.directory', ['q' => 'carlos.buscado']));

        $response->assertStatus(200);
        $response->assertSee('Carlos Buscado');
    }

    public function test_agente_puede_ver_formulario_de_creacion_de_usuario(): void
    {
        $response = $this->actingAs($this->agente, 'staff')
            ->get(route('agent.users.create'));

        $response->assertStatus(200);
        $response->assertSee('Registrar Nuevo Usuario');
    }

    public function test_agente_puede_registrar_un_usuario_exitosamente(): void
    {
        $datos = [
            'firstname' => 'Nuevo',
            'lastname' => 'Cliente',
            'email' => 'nuevocliente@test.com',
            'phone' => '+507 6555-1234',
            'company' => 'Empresa Test',
            'address' => 'Ciudad de Panamá',
            'password' => 'secreto123',
            'status' => 1,
        ];

        $response = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.users.store'), $datos);

        $this->assertDatabaseHas('users', [
            'email' => 'nuevocliente@test.com',
            'firstname' => 'Nuevo',
            'lastname' => 'Cliente',
        ]);

        $usuario = User::where('email', 'nuevocliente@test.com')->first();
        $response->assertRedirect(route('agent.users.show', $usuario->id));
    }

    public function test_agente_puede_ver_perfil_y_tickets_del_usuario(): void
    {
        $cliente = User::factory()->create([
            'firstname' => 'Cliente',
            'lastname' => 'ConTicket',
            'email' => 'conticket@test.com',
        ]);

        $departamento = Department::firstOrCreate(['id' => 1], ['name' => 'Soporte']);
        $prioridad = Priority::firstOrCreate(['id' => 1], ['name' => 'Baja']);
        $estado = TicketStatus::firstOrCreate(['id' => 1], ['name' => 'Abierto']);

        Ticket::create([
            'ticket_number' => '100999',
            'user_id' => $cliente->id,
            'dept_id' => $departamento->id,
            'priority_id' => $prioridad->id,
            'status_id' => $estado->id,
            'subject' => 'Problema con conexión de red',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $response = $this->actingAs($this->agente, 'staff')
            ->get(route('agent.users.show', $cliente->id));

        $response->assertStatus(200);
        $response->assertSee('Cliente ConTicket');
        $response->assertSee('Problema con conexión de red');
    }

    public function test_agente_puede_actualizar_datos_del_usuario(): void
    {
        $cliente = User::factory()->create([
            'firstname' => 'Original',
            'lastname' => 'Usuario',
            'email' => 'original@test.com',
        ]);

        $datosActualizados = [
            'firstname' => 'NombreModificado',
            'lastname' => 'ApellidoModificado',
            'email' => 'modificado@test.com',
            'phone' => '+507 6999-8888',
            'company' => 'Nueva Empresa',
            'status' => 1,
        ];

        $response = $this->actingAs($this->agente, 'staff')
            ->put(route('agent.users.update', $cliente->id), $datosActualizados);

        $response->assertRedirect(route('agent.users.show', $cliente->id));
        $this->assertDatabaseHas('users', [
            'id' => $cliente->id,
            'firstname' => 'NombreModificado',
            'email' => 'modificado@test.com',
        ]);
    }

    public function test_agente_puede_alternar_estado_del_usuario(): void
    {
        $cliente = User::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.users.toggle_status', $cliente->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $cliente->id,
            'status' => 'inactive',
        ]);
    }

    public function test_agente_puede_eliminar_usuario_sin_tickets(): void
    {
        $cliente = User::factory()->create();

        $response = $this->actingAs($this->agente, 'staff')
            ->delete(route('agent.users.destroy', $cliente->id));

        $response->assertRedirect(route('agent.users.directory'));
        $this->assertDatabaseMissing('users', ['id' => $cliente->id]);
    }

    public function test_agente_no_puede_eliminar_usuario_con_tickets(): void
    {
        $cliente = User::factory()->create();

        $departamento = Department::firstOrCreate(['id' => 1], ['name' => 'Soporte']);
        $prioridad = Priority::firstOrCreate(['id' => 1], ['name' => 'Baja']);
        $estado = TicketStatus::firstOrCreate(['id' => 1], ['name' => 'Abierto']);

        Ticket::create([
            'ticket_number' => '100888',
            'user_id' => $cliente->id,
            'dept_id' => $departamento->id,
            'priority_id' => $prioridad->id,
            'status_id' => $estado->id,
            'subject' => 'Ticket de prueba no eliminable',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $response = $this->actingAs($this->agente, 'staff')
            ->delete(route('agent.users.destroy', $cliente->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $cliente->id]);
    }
}
