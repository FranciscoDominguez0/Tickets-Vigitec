<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminNotificationsTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $administrador;

    protected Staff $agenteRegular;

    protected User $cliente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = Staff::firstOrCreate(
            ['username' => 'admin_test_notifs'],
            [
                'email' => 'admin_test_notifs@test.com',
                'firstname' => 'Admin',
                'lastname' => 'Notificaciones',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'admin',
                'isadmin' => 1,
                'is_active' => true,
            ]
        );

        $this->agenteRegular = Staff::firstOrCreate(
            ['username' => 'agente_regular_notifs'],
            [
                'email' => 'agente_notifs@test.com',
                'firstname' => 'Agente',
                'lastname' => 'Notificaciones',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'agent',
                'isadmin' => 0,
                'is_active' => true,
            ]
        );

        $this->cliente = User::firstOrCreate(
            ['email' => 'cliente_notif_test@test.com'],
            [
                'firstname' => 'Cliente',
                'lastname' => 'Notif',
                'name' => 'Cliente Notif',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
            ]
        );
    }

    public function test_administrador_puede_listar_notificaciones(): void
    {
        Notification::create([
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
            'message' => 'Notificación de prueba para el panel administrativo',
            'type' => 'general',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.notifications'));

        $response->assertStatus(200);
        $response->assertSee('Notificaciones y Alertas del Sistema');
        $response->assertSee('Notificación de prueba para el panel administrativo');
        $response->assertSee('Total Emitidas');
    }

    public function test_administrador_puede_filtrar_notificaciones_por_tipo_y_estado(): void
    {
        Notification::create([
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
            'message' => 'Alerta Crítica del Servidor '.uniqid(),
            'type' => 'alerta',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        Notification::create([
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
            'message' => 'Aviso de Mantenimiento Rutinario '.uniqid(),
            'type' => 'mantenimiento',
            'is_read' => 1,
            'created_at' => now(),
        ]);

        // Filtro por tipo alerta
        $respuestaAlerta = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.notifications', ['tipo' => 'alerta']));
        $respuestaAlerta->assertStatus(200);
        $respuestaAlerta->assertSee('Alerta Crítica del Servidor');
        $respuestaAlerta->assertDontSee('Aviso de Mantenimiento Rutinario');

        // Filtro por solo no leídas
        $respuestaNoLeidas = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.notifications', ['estado' => 'no_leidas']));
        $respuestaNoLeidas->assertStatus(200);
        $respuestaNoLeidas->assertSee('Alerta Crítica del Servidor');
    }

    public function test_administrador_puede_enviar_notificacion_a_todos_los_agentes(): void
    {
        $mensaje = 'Mensaje para todo el equipo '.uniqid();

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.notifications.send'), [
                'destinatario_tipo' => 'todos_agentes',
                'type' => 'alerta',
                'message' => $mensaje,
            ]);

        $response->assertRedirect(route('admin.notifications', ['audiencia' => 'personal']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('notifications', [
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
            'message' => $mensaje,
            'type' => 'alerta',
            'is_read' => 0,
        ]);
    }

    public function test_administrador_puede_enviar_notificacion_a_un_agente_especifico(): void
    {
        $mensaje = 'Mensaje exclusivo para agente '.uniqid();

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.notifications.send'), [
                'destinatario_tipo' => 'agente_especifico',
                'staff_id' => $this->agenteRegular->id,
                'type' => 'recordatorio',
                'message' => $mensaje,
            ]);

        $response->assertRedirect(route('admin.notifications', ['audiencia' => 'personal']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('notifications', [
            'empresa_id' => 1,
            'staff_id' => $this->agenteRegular->id,
            'message' => $mensaje,
            'type' => 'recordatorio',
        ]);
    }

    public function test_administrador_puede_enviar_notificacion_a_cliente_especifico(): void
    {
        $mensaje = 'Aviso de actualización para el cliente '.uniqid();

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.notifications.send'), [
                'destinatario_tipo' => 'cliente_especifico',
                'user_id' => $this->cliente->id,
                'type' => 'general',
                'message' => $mensaje,
            ]);

        $response->assertRedirect(route('admin.notifications', ['audiencia' => 'clientes']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_notifications', [
            'empresa_id' => 1,
            'user_id' => $this->cliente->id,
            'message' => $mensaje,
            'type' => 'general',
            'is_read' => 0,
        ]);
    }

    public function test_administrador_puede_marcar_notificacion_como_leida(): void
    {
        $notif = Notification::create([
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
            'message' => 'Notificación para marcar como leída '.uniqid(),
            'type' => 'general',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.notifications.read', $notif->id), [
                'audiencia' => 'personal',
            ]);

        $response->assertRedirect(route('admin.notifications', ['audiencia' => 'personal']));
        $response->assertSessionHas('success');

        $this->assertEquals(1, $notif->fresh()->is_read);
    }

    public function test_administrador_puede_marcar_todas_las_notificaciones_como_leidas(): void
    {
        Notification::create([
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
            'message' => 'Notificación 1 '.uniqid(),
            'type' => 'general',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        Notification::create([
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
            'message' => 'Notificación 2 '.uniqid(),
            'type' => 'general',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.notifications.read_all'), [
                'audiencia' => 'personal',
            ]);

        $response->assertRedirect(route('admin.notifications', ['audiencia' => 'personal']));
        $response->assertSessionHas('success');

        $pendientes = Notification::where('empresa_id', 1)->where('is_read', 0)->count();
        $this->assertEquals(0, $pendientes);
    }

    public function test_administrador_puede_eliminar_notificacion(): void
    {
        $notif = Notification::create([
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
            'message' => 'Notificación para eliminar '.uniqid(),
            'type' => 'general',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.notifications.destroy', $notif->id), [
                'audiencia' => 'personal',
            ]);

        $response->assertRedirect(route('admin.notifications', ['audiencia' => 'personal']));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('notifications', [
            'id' => $notif->id,
        ]);
    }

    public function test_administrador_puede_procesar_acciones_masivas_sobre_notificaciones(): void
    {
        $notif1 = Notification::create([
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
            'message' => 'Notif Masiva 1 '.uniqid(),
            'type' => 'general',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        $notif2 = Notification::create([
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
            'message' => 'Notif Masiva 2 '.uniqid(),
            'type' => 'general',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        $ids = [$notif1->id, $notif2->id];

        // 1. Marcar como leídas
        $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.notifications.mass'), [
                'ids' => $ids,
                'accion' => 'marcar_leido',
                'audiencia' => 'personal',
            ])
            ->assertRedirect(route('admin.notifications', ['audiencia' => 'personal']))
            ->assertSessionHas('success');

        $this->assertEquals(1, $notif1->fresh()->is_read);
        $this->assertEquals(1, $notif2->fresh()->is_read);

        // 2. Marcar como no leídas
        $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.notifications.mass'), [
                'ids' => $ids,
                'accion' => 'marcar_no_leido',
                'audiencia' => 'personal',
            ])
            ->assertRedirect(route('admin.notifications', ['audiencia' => 'personal']))
            ->assertSessionHas('success');

        $this->assertEquals(0, $notif1->fresh()->is_read);
        $this->assertEquals(0, $notif2->fresh()->is_read);

        // 3. Eliminar seleccionadas
        $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.notifications.mass'), [
                'ids' => $ids,
                'accion' => 'eliminar',
                'audiencia' => 'personal',
            ])
            ->assertRedirect(route('admin.notifications', ['audiencia' => 'personal']))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('notifications', ['id' => $notif1->id]);
        $this->assertDatabaseMissing('notifications', ['id' => $notif2->id]);
    }

    public function test_administrador_puede_guardar_destinatarios_de_alertas_globales(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.notifications.recipients'), [
                'staff_ids' => [$this->administrador->id, $this->agenteRegular->id],
            ]);

        $response->assertRedirect(route('admin.notifications', ['tab' => 'destinatarios']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('notification_recipients', [
            'empresa_id' => 1,
            'staff_id' => $this->administrador->id,
        ]);

        $this->assertDatabaseHas('notification_recipients', [
            'empresa_id' => 1,
            'staff_id' => $this->agenteRegular->id,
        ]);
    }

    public function test_agente_no_administrador_no_puede_acceder_a_notificaciones(): void
    {
        $response = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.notifications'));

        $response->assertRedirect(route('agent.dashboard'));
        $response->assertSessionHas('error');
    }
}
