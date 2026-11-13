<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Priority;
use App\Models\Staff;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminStaffTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $administrador;

    protected Staff $agenteRegular;

    protected Department $departamento;

    protected function setUp(): void
    {
        parent::setUp();

        $this->departamento = Department::firstOrCreate(
            ['name' => 'Soporte Técnico Pruebas'],
            [
                'ispublic' => 1,
                'email_id' => 1,
            ]
        );

        $this->administrador = Staff::firstOrCreate(
            ['username' => 'admin_personal_test'],
            [
                'email' => 'admin_personal_test@test.com',
                'firstname' => 'Admin',
                'lastname' => 'Personal',
                'password' => Hash::make('password123'),
                'dept_id' => $this->departamento->id,
                'empresa_id' => 1,
                'role' => 'admin',
                'isadmin' => 1,
                'is_active' => true,
            ]
        );

        $this->agenteRegular = Staff::firstOrCreate(
            ['username' => 'agente_personal_test'],
            [
                'email' => 'agente_personal_test@test.com',
                'firstname' => 'Agente',
                'lastname' => 'Pruebas',
                'password' => Hash::make('password123'),
                'dept_id' => $this->departamento->id,
                'empresa_id' => 1,
                'role' => 'agent',
                'isadmin' => 0,
                'is_active' => true,
            ]
        );
    }

    public function test_administrador_puede_ver_listado_de_personal(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.staff'));

        $response->assertStatus(200);
        $response->assertSee('Directorio de Personal y Agentes');
        $response->assertSee($this->agenteRegular->firstname);
        $response->assertSee($this->administrador->firstname);
    }

    public function test_administrador_puede_filtrar_personal_por_busqueda_y_rol(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.staff', ['busqueda' => 'agente_personal_test', 'role' => 'agent']));

        $response->assertStatus(200);
        $response->assertSee('agente_personal_test');
    }

    public function test_administrador_puede_crear_un_nuevo_agente(): void
    {
        $nuevoUsername = 'nuevo_agente_'.uniqid();
        $nuevoEmail = $nuevoUsername.'@empresa.com';

        $datos = [
            'firstname' => 'Carlos',
            'lastname' => 'Pérez',
            'username' => $nuevoUsername,
            'email' => $nuevoEmail,
            'phone' => '1234567890',
            'password' => 'SecretoSeguro123!',
            'password_confirmation' => 'SecretoSeguro123!',
            'dept_id' => $this->departamento->id,
            'role' => 'agent',
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.staff.store'), $datos);

        $response->assertRedirect(route('admin.staff'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('staff', [
            'username' => $nuevoUsername,
            'email' => $nuevoEmail,
            'firstname' => 'Carlos',
            'lastname' => 'Pérez',
            'role' => 'agent',
            'is_active' => 1,
        ]);
    }

    public function test_creacion_de_agente_valida_campos_requeridos_y_unicidad(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.staff.store'), [
                'username' => $this->agenteRegular->username, // Duplicado
                'email' => $this->agenteRegular->email, // Duplicado
                'password' => 'corta',
                'password_confirmation' => 'diferente',
            ]);

        $response->assertSessionHasErrors(['firstname', 'lastname', 'username', 'email', 'password', 'role']);
    }

    public function test_administrador_puede_actualizar_datos_de_un_agente(): void
    {
        $agente = Staff::create([
            'firstname' => 'Temporal',
            'lastname' => 'Prueba',
            'username' => 'temporal_'.uniqid(),
            'email' => 'temporal_'.uniqid().'@test.com',
            'password' => Hash::make('password123'),
            'dept_id' => $this->departamento->id,
            'role' => 'agent',
            'isadmin' => 0,
            'is_active' => true,
        ]);

        $datosActualizados = [
            'firstname' => 'Modificado',
            'lastname' => 'Actualizado',
            'username' => $agente->username,
            'email' => 'modificado_'.uniqid().'@test.com',
            'phone' => '9876543210',
            'dept_id' => $this->departamento->id,
            'role' => 'supervisor',
            'is_active' => 0,
        ];

        $response = $this->actingAs($this->administrador, 'staff')
            ->put(route('admin.staff.update', $agente->id), $datosActualizados);

        $response->assertRedirect(route('admin.staff'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('staff', [
            'id' => $agente->id,
            'firstname' => 'Modificado',
            'lastname' => 'Actualizado',
            'role' => 'supervisor',
            'is_active' => 0,
        ]);
    }

    public function test_administrador_no_puede_desactivarse_ni_quitarse_rol_admin_a_si_mismo(): void
    {
        $datos = [
            'firstname' => $this->administrador->firstname,
            'lastname' => $this->administrador->lastname,
            'username' => $this->administrador->username,
            'email' => $this->administrador->email,
            'dept_id' => $this->administrador->dept_id,
            'role' => 'agent', // Intento de auto-degradación
            'is_active' => 0, // Intento de auto-desactivación
        ];

        $response = $this->actingAs($this->administrador, 'staff')
            ->put(route('admin.staff.update', $this->administrador->id), $datos);

        $response->assertRedirect(route('admin.staff'));
        $response->assertSessionHas('error');

        $this->administrador->refresh();
        $this->assertEquals('admin', $this->administrador->role);
        $this->assertTrue((bool) $this->administrador->is_active);
    }

    public function test_administrador_puede_restablecer_clave_de_un_agente(): void
    {
        $agente = Staff::create([
            'firstname' => 'Clave',
            'lastname' => 'Prueba',
            'username' => 'clave_'.uniqid(),
            'email' => 'clave_'.uniqid().'@test.com',
            'password' => Hash::make('claveVieja123!'),
            'dept_id' => $this->departamento->id,
            'role' => 'agent',
            'is_active' => true,
        ]);

        $nuevaClave = 'ClaveNuevaTotalmenteSegura2026!';

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.staff.reset_password', $agente->id), [
                'password' => $nuevaClave,
                'password_confirmation' => $nuevaClave,
            ]);

        $response->assertRedirect(route('admin.staff'));
        $response->assertSessionHas('success');

        $agente->refresh();
        $this->assertTrue(Hash::check($nuevaClave, $agente->password));
    }

    public function test_administrador_puede_alternar_estado_activo_inactivo(): void
    {
        $agente = Staff::create([
            'firstname' => 'Toggle',
            'lastname' => 'Prueba',
            'username' => 'toggle_'.uniqid(),
            'email' => 'toggle_'.uniqid().'@test.com',
            'password' => Hash::make('password123'),
            'dept_id' => $this->departamento->id,
            'role' => 'agent',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.staff.toggle', $agente->id));

        $response->assertRedirect(route('admin.staff'));
        $agente->refresh();
        $this->assertFalse((bool) $agente->is_active);

        // Volver a activar
        $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.staff.toggle', $agente->id));

        $agente->refresh();
        $this->assertTrue((bool) $agente->is_active);
    }

    public function test_administrador_no_puede_alternar_su_propio_estado(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.staff.toggle', $this->administrador->id));

        $response->assertRedirect(route('admin.staff'));
        $response->assertSessionHas('error');
        $this->administrador->refresh();
        $this->assertTrue((bool) $this->administrador->is_active);
    }

    public function test_no_se_puede_eliminar_agente_con_tickets_asociados(): void
    {
        $agente = Staff::create([
            'firstname' => 'ConTickets',
            'lastname' => 'Prueba',
            'username' => 'tickets_'.uniqid(),
            'email' => 'tickets_'.uniqid().'@test.com',
            'password' => Hash::make('password123'),
            'dept_id' => $this->departamento->id,
            'role' => 'agent',
            'is_active' => true,
        ]);

        $cliente = User::firstOrCreate(
            ['email' => 'cliente_staff_test@test.com'],
            ['firstname' => 'Cliente', 'lastname' => 'Test', 'password' => Hash::make('password123'), 'empresa_id' => 1]
        );

        $prioridad = Priority::firstOrCreate(['id' => 1], ['name' => 'Baja', 'id' => 1]);
        $estado = TicketStatus::firstOrCreate(['id' => 1], ['name' => 'Abierto', 'id' => 1]);

        Ticket::create([
            'empresa_id' => 1,
            'ticket_number' => 'TCK-'.rand(100000, 999999),
            'subject' => 'Ticket de prueba',
            'user_id' => $cliente->id,
            'dept_id' => $this->departamento->id,
            'staff_id' => $agente->id,
            'status_id' => $estado->id,
            'priority_id' => $prioridad->id,
            'created' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.staff.destroy', $agente->id));

        $response->assertRedirect(route('admin.staff'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('staff', ['id' => $agente->id]);
    }

    public function test_se_puede_eliminar_agente_sin_tickets(): void
    {
        $agente = Staff::create([
            'firstname' => 'SinTickets',
            'lastname' => 'Prueba',
            'username' => 'sintickets_'.uniqid(),
            'email' => 'sintickets_'.uniqid().'@test.com',
            'password' => Hash::make('password123'),
            'dept_id' => $this->departamento->id,
            'role' => 'agent',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.staff.destroy', $agente->id));

        $response->assertRedirect(route('admin.staff'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('staff', ['id' => $agente->id]);
    }

    public function test_administrador_no_puede_eliminarse_a_si_mismo(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.staff.destroy', $this->administrador->id));

        $response->assertRedirect(route('admin.staff'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('staff', ['id' => $this->administrador->id]);
    }

    public function test_administrador_puede_procesar_acciones_masivas_protegiendo_su_cuenta(): void
    {
        $agente1 = Staff::create([
            'firstname' => 'M1',
            'lastname' => 'Prueba',
            'username' => 'm1_'.uniqid(),
            'email' => 'm1_'.uniqid().'@test.com',
            'password' => Hash::make('password123'),
            'dept_id' => $this->departamento->id,
            'role' => 'agent',
            'is_active' => true,
        ]);

        $agente2 = Staff::create([
            'firstname' => 'M2',
            'lastname' => 'Prueba',
            'username' => 'm2_'.uniqid(),
            'email' => 'm2_'.uniqid().'@test.com',
            'password' => Hash::make('password123'),
            'dept_id' => $this->departamento->id,
            'role' => 'agent',
            'is_active' => true,
        ]);

        // Desactivar masivamente incluyendo al administrador en los ids
        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.staff.mass'), [
                'accion' => 'desactivar',
                'ids' => [$agente1->id, $agente2->id, $this->administrador->id],
            ]);

        $response->assertRedirect(route('admin.staff'));
        $response->assertSessionHas('success');

        $agente1->refresh();
        $agente2->refresh();
        $this->administrador->refresh();

        $this->assertFalse((bool) $agente1->is_active);
        $this->assertFalse((bool) $agente2->is_active);
        $this->assertTrue((bool) $this->administrador->is_active); // Su propia cuenta queda protegida
    }

    public function test_agente_no_administrador_no_puede_acceder_a_gestion_de_personal(): void
    {
        $response = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.staff'));

        $response->assertRedirect(route('agent.dashboard'));
        $response->assertSessionHas('error');
    }
}
