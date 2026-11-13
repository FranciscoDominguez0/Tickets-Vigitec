<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\Priority;
use App\Models\Staff;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminHelpTopicsTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $administrador;

    protected Staff $agenteRegular;

    protected Department $departamento;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = Staff::firstOrCreate(
            ['username' => 'admin_test_helptopics'],
            [
                'email' => 'admin_test_helptopics@test.com',
                'firstname' => 'Admin',
                'lastname' => 'TemasAyuda',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->agenteRegular = Staff::firstOrCreate(
            ['username' => 'agente_regular_helptopics'],
            [
                'email' => 'agente_helptopics@test.com',
                'firstname' => 'Agente',
                'lastname' => 'TemasAyuda',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'agent',
                'isadmin' => 0,
                'is_active' => true,
            ]
        );

        $this->departamento = Department::firstOrCreate(
            ['empresa_id' => 1, 'name' => 'Soporte General'],
            ['description' => 'Departamento de soporte general', 'is_active' => true]
        );
    }

    public function test_administrador_puede_listar_temas_de_ayuda(): void
    {
        $tema = HelpTopic::firstOrCreate(
            ['empresa_id' => 1, 'name' => 'Consultas de Facturación Especial'],
            [
                'dept_id' => $this->departamento->id,
                'description' => 'Consultas relativas a facturas y cobros',
                'is_active' => true,
                'is_public' => true,
                'created' => now(),
            ]
        );

        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.helptopics'));

        $response->assertStatus(200);
        $response->assertSee('Temas de Ayuda');
        $response->assertSee('Consultas de Facturación Especial');
        $response->assertSee('Soporte General');
    }

    public function test_administrador_puede_filtrar_temas_de_ayuda(): void
    {
        $deptSecundario = Department::firstOrCreate(
            ['empresa_id' => 1, 'name' => 'Infraestructura TI'],
            ['description' => 'Departamento de TI', 'is_active' => true]
        );

        HelpTopic::create([
            'empresa_id' => 1,
            'name' => 'Falla de Conexión VPN',
            'dept_id' => $deptSecundario->id,
            'description' => 'Problemas con accesos remotos',
            'is_active' => 1,
            'is_public' => 0,
            'created' => now(),
        ]);

        HelpTopic::create([
            'empresa_id' => 1,
            'name' => 'Solicitud de Reembolso Directo',
            'dept_id' => $this->departamento->id,
            'description' => 'Devolución de dinero a clientes',
            'is_active' => 0,
            'is_public' => 1,
            'created' => now(),
        ]);

        // Filtro por texto
        $respuestaBusqueda = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.helptopics', ['q' => 'VPN']));
        $respuestaBusqueda->assertStatus(200);
        $respuestaBusqueda->assertSee('Falla de Conexión VPN');
        $respuestaBusqueda->assertDontSee('Solicitud de Reembolso Directo');

        // Filtro por departamento
        $respuestaDept = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.helptopics', ['dept_id' => $deptSecundario->id]));
        $respuestaDept->assertStatus(200);
        $respuestaDept->assertSee('Falla de Conexión VPN');

        // Filtro por inactivos
        $respuestaInactivos = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.helptopics', ['estado' => 'inactivos']));
        $respuestaInactivos->assertStatus(200);
        $respuestaInactivos->assertSee('Solicitud de Reembolso Directo');

        // Filtro por internos
        $respuestaInternos = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.helptopics', ['visibilidad' => 'internos']));
        $respuestaInternos->assertStatus(200);
        $respuestaInternos->assertSee('Falla de Conexión VPN');
    }

    public function test_administrador_puede_crear_un_nuevo_tema_de_ayuda(): void
    {
        $nombreUnico = 'Soporte para Módulos Fiscales '.uniqid();

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.helptopics.store'), [
                'name' => $nombreUnico,
                'dept_id' => $this->departamento->id,
                'description' => 'Asistencia con reportes contables e impositivos',
                'is_active' => 1,
                'is_public' => 1,
            ]);

        $response->assertRedirect(route('admin.helptopics'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('help_topics', [
            'empresa_id' => 1,
            'name' => $nombreUnico,
            'dept_id' => $this->departamento->id,
            'is_active' => 1,
            'is_public' => 1,
        ]);
    }

    public function test_validacion_evita_crear_tema_con_nombre_duplicado_en_misma_empresa(): void
    {
        $nombreTema = 'Instalación de Certificados SSL '.uniqid();

        HelpTopic::create([
            'empresa_id' => 1,
            'name' => $nombreTema,
            'dept_id' => $this->departamento->id,
            'is_active' => 1,
            'is_public' => 1,
            'created' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->from(route('admin.helptopics'))
            ->post(route('admin.helptopics.store'), [
                'name' => $nombreTema,
                'dept_id' => $this->departamento->id,
                'description' => 'Duplicado deliberado',
            ]);

        $response->assertRedirect(route('admin.helptopics'));
        $response->assertSessionHasErrors(['name']);
    }

    public function test_administrador_puede_actualizar_un_tema_de_ayuda(): void
    {
        $tema = HelpTopic::create([
            'empresa_id' => 1,
            'name' => 'Tema Original '.uniqid(),
            'dept_id' => $this->departamento->id,
            'description' => 'Descripción inicial',
            'is_active' => 1,
            'is_public' => 1,
            'created' => now(),
        ]);

        $nuevoNombre = 'Tema Actualizado '.uniqid();

        $response = $this->actingAs($this->administrador, 'staff')
            ->put(route('admin.helptopics.update', $tema->id), [
                'name' => $nuevoNombre,
                'dept_id' => $this->departamento->id,
                'description' => 'Descripción modificada con éxito',
                'is_active' => 0,
                'is_public' => 0,
            ]);

        $response->assertRedirect(route('admin.helptopics'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('help_topics', [
            'id' => $tema->id,
            'name' => $nuevoNombre,
            'description' => 'Descripción modificada con éxito',
            'is_active' => 0,
            'is_public' => 0,
        ]);
    }

    public function test_administrador_puede_alternar_estado_activo_inactivo(): void
    {
        $tema = HelpTopic::create([
            'empresa_id' => 1,
            'name' => 'Tema para Toggle '.uniqid(),
            'dept_id' => $this->departamento->id,
            'is_active' => 1,
            'is_public' => 1,
            'created' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.helptopics.toggle', $tema->id));

        $response->assertRedirect(route('admin.helptopics'));
        $response->assertSessionHas('success');
        $this->assertEquals(0, $tema->fresh()->is_active);

        $response2 = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.helptopics.toggle', $tema->id));

        $response2->assertRedirect(route('admin.helptopics'));
        $this->assertEquals(1, $tema->fresh()->is_active);
    }

    public function test_no_se_puede_eliminar_tema_de_ayuda_con_tickets_asociados(): void
    {
        $tema = HelpTopic::create([
            'empresa_id' => 1,
            'name' => 'Tema Con Tickets '.uniqid(),
            'dept_id' => $this->departamento->id,
            'is_active' => 1,
            'is_public' => 1,
            'created' => now(),
        ]);

        $usuario = User::firstOrCreate(
            ['email' => 'cliente_tema_test@test.com'],
            [
                'firstname' => 'Cliente',
                'lastname' => 'Test',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
            ]
        );

        $prioridad = Priority::firstOrCreate(['id' => 1], ['name' => 'Baja', 'id' => 1]);
        $estado = TicketStatus::firstOrCreate(['id' => 1], ['name' => 'Abierto', 'id' => 1]);

        Ticket::create([
            'ticket_number' => 'TCK-TEMA-'.rand(1000, 9999),
            'subject' => 'Ticket de prueba con tema de ayuda',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'topic_id' => $tema->id,
            'priority_id' => $prioridad->id,
            'status_id' => $estado->id,
            'empresa_id' => 1,
            'created' => now(),
            'updated' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.helptopics.destroy', $tema->id));

        $response->assertRedirect(route('admin.helptopics'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('help_topics', [
            'id' => $tema->id,
        ]);
    }

    public function test_se_puede_eliminar_tema_de_ayuda_sin_tickets_asociados(): void
    {
        $tema = HelpTopic::create([
            'empresa_id' => 1,
            'name' => 'Tema Sin Tickets '.uniqid(),
            'dept_id' => $this->departamento->id,
            'is_active' => 1,
            'is_public' => 1,
            'created' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.helptopics.destroy', $tema->id));

        $response->assertRedirect(route('admin.helptopics'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('help_topics', [
            'id' => $tema->id,
        ]);
    }

    public function test_administrador_puede_procesar_acciones_masivas(): void
    {
        $tema1 = HelpTopic::create([
            'empresa_id' => 1,
            'name' => 'Tema Masivo 1 '.uniqid(),
            'dept_id' => $this->departamento->id,
            'is_active' => 1,
            'is_public' => 1,
            'created' => now(),
        ]);

        $tema2 = HelpTopic::create([
            'empresa_id' => 1,
            'name' => 'Tema Masivo 2 '.uniqid(),
            'dept_id' => $this->departamento->id,
            'is_active' => 1,
            'is_public' => 1,
            'created' => now(),
        ]);

        $ids = [$tema1->id, $tema2->id];

        // 1. Deshabilitar masivo
        $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.helptopics.mass'), [
                'ids' => $ids,
                'accion' => 'deshabilitar',
            ])
            ->assertRedirect(route('admin.helptopics'))
            ->assertSessionHas('success');

        $this->assertEquals(0, $tema1->fresh()->is_active);
        $this->assertEquals(0, $tema2->fresh()->is_active);

        // 2. Habilitar masivo
        $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.helptopics.mass'), [
                'ids' => $ids,
                'accion' => 'habilitar',
            ])
            ->assertRedirect(route('admin.helptopics'))
            ->assertSessionHas('success');

        $this->assertEquals(1, $tema1->fresh()->is_active);
        $this->assertEquals(1, $tema2->fresh()->is_active);

        // 3. Hacer privado masivo
        $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.helptopics.mass'), [
                'ids' => $ids,
                'accion' => 'hacer_privado',
            ])
            ->assertRedirect(route('admin.helptopics'))
            ->assertSessionHas('success');

        $this->assertEquals(0, $tema1->fresh()->is_public);
        $this->assertEquals(0, $tema2->fresh()->is_public);

        // 4. Hacer público masivo
        $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.helptopics.mass'), [
                'ids' => $ids,
                'accion' => 'hacer_publico',
            ])
            ->assertRedirect(route('admin.helptopics'))
            ->assertSessionHas('success');

        $this->assertEquals(1, $tema1->fresh()->is_public);
        $this->assertEquals(1, $tema2->fresh()->is_public);

        // 5. Eliminar masivo
        $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.helptopics.mass'), [
                'ids' => $ids,
                'accion' => 'eliminar',
            ])
            ->assertRedirect(route('admin.helptopics'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('help_topics', ['id' => $tema1->id]);
        $this->assertDatabaseMissing('help_topics', ['id' => $tema2->id]);
    }

    public function test_agente_no_administrador_no_puede_acceder_a_temas_de_ayuda(): void
    {
        $response = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.helptopics'));

        $response->assertRedirect(route('agent.dashboard'));
        $response->assertSessionHas('error');
    }
}
