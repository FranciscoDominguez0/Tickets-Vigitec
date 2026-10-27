<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Organization;
use App\Models\Priority;
use App\Models\Staff;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AgentOrganizationTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $agente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->agente = Staff::firstOrCreate(
            ['username' => 'test_agent_orgs'],
            [
                'email' => 'test_agent_orgs@test.com',
                'firstname' => 'Agente',
                'lastname' => 'Organizaciones',
                'password' => bcrypt('password123'),
                'empresa_id' => 1,
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        TicketStatus::firstOrCreate(
            ['id' => 1],
            ['name' => 'Abierto', 'color' => '#3498db', 'icon' => 'fa-folder-open', 'order_by' => 1]
        );
    }

    public function test_agente_puede_ver_listado_de_organizaciones(): void
    {
        $org = Organization::create([
            'name' => 'Empresa Test Alpha S.A.',
            'empresa_id' => 1,
            'address' => 'Via España, Panamá',
            'phone' => '+507 260-1122',
        ]);

        $response = $this->actingAs($this->agente, 'staff')
            ->get(route('agent.users.orgs'));

        $response->assertStatus(200);
        $response->assertSee('Organizaciones');
        $response->assertSee('Empresa Test Alpha S.A.');
        $response->assertSee('+507 260-1122');
        $response->assertSee('data-bs-target="#modalCrearOrganizacion"', false);
    }

    public function test_agente_puede_buscar_organizaciones_por_nombre(): void
    {
        Organization::create([
            'name' => 'Organización Buscada Exclusiva',
            'empresa_id' => 1,
        ]);

        $response = $this->actingAs($this->agente, 'staff')
            ->get(route('agent.users.orgs', ['q' => 'Buscada Exclusiva']));

        $response->assertStatus(200);
        $response->assertSee('Organización Buscada Exclusiva');
    }

    public function test_agente_puede_registrar_una_nueva_organizacion(): void
    {
        $datos = [
            'name' => 'Vigitec Soluciones Corporativas',
            'phone' => '+507 390-4455',
            'phone_ext' => '201',
            'website' => 'https://vigitec.com',
            'address' => 'Costa del Este, Financial Park',
            'notes' => 'Cliente corporativo preferencial',
            'plain_text_emails' => '1',
        ];

        $response = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.organizations.store'), $datos);

        $response->assertRedirect();

        $this->assertDatabaseHas('organizations', [
            'name' => 'Vigitec Soluciones Corporativas',
            'phone' => '+507 390-4455',
            'website' => 'https://vigitec.com',
        ]);
    }

    public function test_agente_puede_ver_detalle_de_una_organizacion_con_usuarios_y_tickets(): void
    {
        $org = Organization::create([
            'name' => 'Banco Nacional Aliado',
            'empresa_id' => 1,
            'address' => 'Calle 50',
            'phone' => '+507 500-1000',
        ]);

        $usuario = User::factory()->create([
            'firstname' => 'Roberto',
            'lastname' => 'Durán',
            'company' => $org->name,
            'organization_id' => $org->id,
        ]);

        $depto = Department::firstOrCreate(
            ['name' => 'Soporte Técnico'],
            ['empresa_id' => 1, 'is_active' => true]
        );
        $prioridad = Priority::firstOrCreate(
            ['name' => 'Normal'],
            ['level' => 2, 'color' => '#3498db']
        );

        $ticket = Ticket::create([
            'ticket_number' => '100099',
            'user_id' => $usuario->id,
            'dept_id' => $depto->id,
            'priority_id' => $prioridad->id,
            'status_id' => 1,
            'subject' => 'Problema con acceso a red corporativa',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        // Ver pestaña de usuarios
        $responseUsuarios = $this->actingAs($this->agente, 'staff')
            ->get(route('agent.organizations.show', ['id' => $org->id, 't' => 'usuarios']));

        $responseUsuarios->assertStatus(200);
        $responseUsuarios->assertSee('Banco Nacional Aliado');
        $responseUsuarios->assertSee('Roberto Durán');

        // Ver pestaña de tickets
        $responseTickets = $this->actingAs($this->agente, 'staff')
            ->get(route('agent.organizations.show', ['id' => $org->id, 't' => 'tickets']));

        $responseTickets->assertStatus(200);
        $responseTickets->assertSee('Problema con acceso a red corporativa');
    }

    public function test_agente_puede_actualizar_una_organizacion(): void
    {
        $org = Organization::create([
            'name' => 'Nombre Viejo S.A.',
            'empresa_id' => 1,
            'phone' => '1111-1111',
        ]);

        $datosActualizados = [
            'name' => 'Nombre Renovado Global',
            'phone' => '2222-2222',
            'address' => 'Nueva Dirección 123',
        ];

        $response = $this->actingAs($this->agente, 'staff')
            ->put(route('agent.organizations.update', $org->id), $datosActualizados);

        $response->assertRedirect(route('agent.organizations.show', $org->id));

        $this->assertDatabaseHas('organizations', [
            'id' => $org->id,
            'name' => 'Nombre Renovado Global',
            'phone' => '2222-2222',
        ]);
    }

    public function test_agente_puede_vincular_y_desvincular_usuario_a_organizacion(): void
    {
        $org = Organization::create([
            'name' => 'Logística del Istmo',
            'empresa_id' => 1,
        ]);

        $usuario = User::factory()->create([
            'firstname' => 'Marta',
            'lastname' => 'Linares',
        ]);

        // Vincular
        $responseVincular = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.organizations.members.store', $org->id), [
                'user_id' => $usuario->id,
            ]);

        $responseVincular->assertRedirect();
        $this->assertDatabaseHas('user_organizations', [
            'organization_id' => $org->id,
            'user_id' => $usuario->id,
        ]);

        // Desvincular
        $responseDesvincular = $this->actingAs($this->agente, 'staff')
            ->delete(route('agent.organizations.members.destroy', ['id' => $org->id, 'userId' => $usuario->id]));

        $responseDesvincular->assertRedirect();
        $this->assertDatabaseMissing('user_organizations', [
            'organization_id' => $org->id,
            'user_id' => $usuario->id,
        ]);
    }

    public function test_agente_puede_eliminar_una_organizacion(): void
    {
        $org = Organization::create([
            'name' => 'Organización Para Eliminar',
            'empresa_id' => 1,
        ]);

        $response = $this->actingAs($this->agente, 'staff')
            ->delete(route('agent.organizations.destroy', $org->id));

        $response->assertRedirect(route('agent.users.orgs'));
        $this->assertDatabaseMissing('organizations', [
            'id' => $org->id,
        ]);
    }

    public function test_validacion_de_nombre_requerido_y_unico(): void
    {
        Organization::create([
            'name' => 'Duplicado S.A.',
            'empresa_id' => 1,
        ]);

        // Intento con nombre duplicado
        $response = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.organizations.store'), [
                'name' => 'Duplicado S.A.',
            ]);

        $response->assertSessionHasErrors('name');
    }
}
