<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Banlist;
use App\Models\Department;
use App\Models\EmailAccount;
use App\Models\HelpTopic;
use App\Models\Priority;
use App\Models\Sequence;
use App\Models\Staff;
use App\Models\TicketStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminEmailAndSequenceTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $administrador;

    protected Staff $agenteRegular;

    protected Department $departamento;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = Staff::firstOrCreate(
            ['username' => 'admin_test_emails_seq'],
            [
                'email' => 'admin_emails_seq@test.com',
                'firstname' => 'Admin',
                'lastname' => 'Pruebas',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->agenteRegular = Staff::firstOrCreate(
            ['username' => 'agente_regular_emails_seq'],
            [
                'email' => 'agente_emails_seq@test.com',
                'firstname' => 'Agente',
                'lastname' => 'Pruebas',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'agent',
                'isadmin' => 0,
                'is_active' => true,
            ]
        );

        $this->departamento = Department::firstOrCreate(
            ['empresa_id' => 1, 'name' => 'Atención General'],
            ['description' => 'Área de soporte general', 'is_active' => true]
        );
    }

    /* =========================================================================
     * Pruebas de Cuentas de Correo (/admin/email)
     * ========================================================================= */

    public function test_administrador_puede_listar_cuentas_de_correo(): void
    {
        $cuenta = EmailAccount::create([
            'empresa_id' => 1,
            'email' => 'soporte_'.uniqid().'@test.com',
            'name' => 'Soporte Técnico',
            'priority' => 'normal',
            'dept_id' => $this->departamento->id,
            'is_default' => 1,
            'smtp_host' => 'smtp.test.com',
            'smtp_port' => 587,
            'smtp_secure' => 'tls',
            'smtp_user' => 'user@test.com',
            'smtp_pass' => 'secret123',
            'created' => now(),
            'updated' => now(),
        ]);

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.email'));

        $respuesta->assertStatus(200);
        $respuesta->assertSee('Cuentas de Correo');
        $respuesta->assertSee($cuenta->email);
    }

    public function test_administrador_puede_crear_cuenta_de_correo(): void
    {
        $correoNuevo = 'notificaciones_'.uniqid().'@empresa.com';

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.email.store'), [
                'email' => $correoNuevo,
                'name' => 'Notificaciones Automáticas',
                'priority' => 'Alta',
                'dept_id' => $this->departamento->id,
                'is_default' => '1',
                'smtp_host' => 'mail.empresa.com',
                'smtp_port' => 465,
                'smtp_secure' => 'ssl',
                'smtp_user' => 'notificaciones@empresa.com',
                'smtp_pass' => 'claveSegura123',
            ]);

        $respuesta->assertRedirect(route('admin.email'));
        $respuesta->assertSessionHas('success');

        $this->assertDatabaseHas('email_accounts', [
            'empresa_id' => 1,
            'email' => $correoNuevo,
            'name' => 'Notificaciones Automáticas',
            'is_default' => 1,
        ]);
    }

    public function test_administrador_puede_actualizar_cuenta_de_correo(): void
    {
        $cuenta = EmailAccount::create([
            'empresa_id' => 1,
            'email' => 'ventas_'.uniqid().'@test.com',
            'name' => 'Ventas Inicial',
            'priority' => 'Baja',
            'dept_id' => $this->departamento->id,
            'is_default' => 0,
            'smtp_host' => 'smtp.test.com',
            'smtp_port' => 587,
            'smtp_secure' => 'tls',
            'smtp_user' => 'user@test.com',
            'smtp_pass' => 'pass123',
            'created' => now(),
            'updated' => now(),
        ]);

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->put(route('admin.email.update', $cuenta->id), [
                'email' => 'ventas_actualizada_'.uniqid().'@test.com',
                'name' => 'Ventas y Facturación',
                'priority' => 'Urgente',
                'dept_id' => $this->departamento->id,
                'is_default' => '0',
                'smtp_host' => 'smtp2.test.com',
                'smtp_port' => 25,
                'smtp_secure' => 'none',
                'smtp_user' => 'user2@test.com',
            ]);

        $respuesta->assertRedirect(route('admin.email'));
        $respuesta->assertSessionHas('success');

        $this->assertDatabaseHas('email_accounts', [
            'id' => $cuenta->id,
            'name' => 'Ventas y Facturación',
            'priority' => 'Urgente',
        ]);
    }

    public function test_administrador_puede_marcar_cuenta_como_predeterminada(): void
    {
        $cuenta1 = EmailAccount::create([
            'empresa_id' => 1,
            'email' => 'cuenta1_'.uniqid().'@test.com',
            'name' => 'Cuenta Uno',
            'priority' => 'normal',
            'dept_id' => $this->departamento->id,
            'is_default' => 1,
            'created' => now(),
            'updated' => now(),
        ]);

        $cuenta2 = EmailAccount::create([
            'empresa_id' => 1,
            'email' => 'cuenta2_'.uniqid().'@test.com',
            'name' => 'Cuenta Dos',
            'priority' => 'normal',
            'dept_id' => $this->departamento->id,
            'is_default' => 0,
            'created' => now(),
            'updated' => now(),
        ]);

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.email.default', $cuenta2->id));

        $respuesta->assertRedirect(route('admin.email'));
        $respuesta->assertSessionHas('success');

        $this->assertDatabaseHas('email_accounts', [
            'id' => $cuenta2->id,
            'is_default' => 1,
        ]);

        $this->assertDatabaseHas('email_accounts', [
            'id' => $cuenta1->id,
            'is_default' => 0,
        ]);
    }

    public function test_administrador_puede_eliminar_cuenta_de_correo(): void
    {
        $cuenta = EmailAccount::create([
            'empresa_id' => 1,
            'email' => 'borrar_'.uniqid().'@test.com',
            'name' => 'Cuenta Para Borrar',
            'priority' => 'normal',
            'dept_id' => $this->departamento->id,
            'is_default' => 0,
            'created' => now(),
            'updated' => now(),
        ]);

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.email.destroy', $cuenta->id));

        $respuesta->assertRedirect(route('admin.email'));
        $respuesta->assertSessionHas('success');

        $this->assertDatabaseMissing('email_accounts', [
            'id' => $cuenta->id,
        ]);
    }

    /* =========================================================================
     * Pruebas de Lista de Prohibidos (Banlist - /admin/banlist)
     * ========================================================================= */

    public function test_administrador_puede_listar_banlist(): void
    {
        $correoProhibido = 'spam_'.uniqid().'@spammer.com';

        Banlist::create([
            'empresa_id' => 1,
            'email' => $correoProhibido,
            'domain' => null,
            'notes' => 'Spam recurrente',
            'is_active' => 1,
            'created' => now(),
            'updated' => now(),
        ]);

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.banlist'));

        $respuesta->assertStatus(200);
        $respuesta->assertSee('Lista de Prohibidos');
        $respuesta->assertSee($correoProhibido);
    }

    public function test_administrador_puede_agregar_correo_a_banlist(): void
    {
        $correoBloquear = 'malicioso_'.uniqid().'@atacante.com';

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.banlist.store'), [
                'type' => 'email',
                'value' => $correoBloquear,
                'notes' => 'Bloqueado por seguridad',
            ]);

        $respuesta->assertRedirect(route('admin.banlist'));
        $respuesta->assertSessionHas('success');

        $this->assertDatabaseHas('banlist', [
            'empresa_id' => 1,
            'email' => $correoBloquear,
            'domain' => null,
            'is_active' => 1,
        ]);
    }

    public function test_administrador_puede_agregar_dominio_a_banlist(): void
    {
        $dominioBloquear = 'dominio-malicioso-'.uniqid().'.com';

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.banlist.store'), [
                'type' => 'domain',
                'value' => '@'.$dominioBloquear,
                'notes' => 'Bloqueo por dominio completo',
            ]);

        $respuesta->assertRedirect(route('admin.banlist'));
        $respuesta->assertSessionHas('success');

        $this->assertDatabaseHas('banlist', [
            'empresa_id' => 1,
            'domain' => $dominioBloquear,
            'email' => null,
            'is_active' => 1,
        ]);
    }

    public function test_administrador_puede_alternar_estado_y_eliminar_banlist(): void
    {
        $bloqueo = Banlist::create([
            'empresa_id' => 1,
            'email' => 'temporal_'.uniqid().'@test.com',
            'domain' => null,
            'notes' => 'Prueba de alternancia',
            'is_active' => 1,
            'created' => now(),
            'updated' => now(),
        ]);

        // Alternar a inactivo
        $respuestaAlternar = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.banlist.toggle', $bloqueo->id));

        $respuestaAlternar->assertRedirect(route('admin.banlist'));
        $this->assertDatabaseHas('banlist', [
            'id' => $bloqueo->id,
            'is_active' => 0,
        ]);

        // Eliminar
        $respuestaEliminar = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.banlist.destroy', $bloqueo->id));

        $respuestaEliminar->assertRedirect(route('admin.banlist'));
        $this->assertDatabaseMissing('banlist', [
            'id' => $bloqueo->id,
        ]);
    }

    /* =========================================================================
     * Pruebas de Diagnóstico y Envío de Correo (/admin/emailtest)
     * ========================================================================= */

    public function test_administrador_puede_ver_pantalla_de_diagnostico_y_enviar_prueba(): void
    {
        Mail::fake();

        $cuenta = EmailAccount::create([
            'empresa_id' => 1,
            'email' => 'diag_'.uniqid().'@test.com',
            'name' => 'Diagnostico Host',
            'priority' => 'normal',
            'dept_id' => $this->departamento->id,
            'is_default' => 1,
            'smtp_host' => 'smtp.test.com',
            'smtp_port' => 587,
            'smtp_secure' => 'tls',
            'smtp_user' => 'user@test.com',
            'smtp_pass' => 'pass123',
            'created' => now(),
            'updated' => now(),
        ]);

        $respuestaVer = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.emailtest'));

        $respuestaVer->assertStatus(200);
        $respuestaVer->assertSee('Diagnóstico y Prueba de Correo');

        $respuestaEnviar = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.emailtest.send'), [
                'from_id' => $cuenta->id,
                'to' => 'destinatario_prueba@ejemplo.com',
                'subject' => 'Asunto de Prueba SMTP',
                'body' => 'Contenido de comprobación del servicio de correo.',
            ]);

        $respuestaEnviar->assertRedirect(route('admin.emailtest'));
        $respuestaEnviar->assertSessionHas('success');
    }

    /* =========================================================================
     * Pruebas de Configuración de Solicitudes y Números de Tickets (/admin/settings/tickets)
     * ========================================================================= */

    public function test_administrador_puede_ver_y_actualizar_configuracion_de_solicitudes(): void
    {
        $estado = TicketStatus::firstOrCreate(['id' => 1], ['name' => 'Abierto', 'id' => 1]);
        $prioridad = Priority::firstOrCreate(['id' => 1], ['name' => 'Baja', 'id' => 1]);
        $tema = HelpTopic::firstOrCreate(
            ['empresa_id' => 1, 'name' => 'Consultas Generales'],
            ['description' => 'Tema general', 'is_active' => true]
        );
        $secuencia = Sequence::firstOrCreate(
            ['empresa_id' => 1, 'name' => 'Secuencia Base'],
            ['next' => 1000, 'increment' => 1, 'padding' => 6]
        );

        $respuestaVer = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.settings.tickets'));

        $respuestaVer->assertStatus(200);
        $respuestaVer->assertSee('Configuración de Solicitudes');
        $respuestaVer->assertSee('Numeración de Tickets');

        $nuevoFormato = 'TICK-######';

        $respuestaGuardar = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.settings.tickets.store'), [
                'ticket_number_format' => $nuevoFormato,
                'ticket_sequence_id' => (string) $secuencia->id,
                'default_ticket_status_id' => (string) $estado->id,
                'default_priority_id' => (string) $prioridad->id,
                'default_help_topic' => (string) $tema->id,
                'max_open_tickets' => '15',
                'block_new_if_signature_pending' => '1',
                'ticket_max_file_mb' => '25',
                'ticket_max_uploads' => '8',
            ]);

        $respuestaGuardar->assertRedirect(route('admin.settings.tickets'));
        $respuestaGuardar->assertSessionHas('success');

        $this->assertEquals($nuevoFormato, AppSetting::obtener('tickets.ticket_number_format'));
        $this->assertEquals((string) $secuencia->id, AppSetting::obtener('tickets.ticket_sequence_id'));
        $this->assertEquals('15', AppSetting::obtener('tickets.max_open_tickets'));
        $this->assertEquals('1', AppSetting::obtener('tickets.block_new_if_signature_pending'));
        $this->assertEquals('25', AppSetting::obtener('tickets.ticket_max_file_mb'));
        $this->assertEquals('8', AppSetting::obtener('tickets.ticket_max_uploads'));
    }

    /* =========================================================================
     * Pruebas de Gestión de Secuencias Numéricas (/admin/sequences)
     * ========================================================================= */

    public function test_administrador_puede_listar_crear_y_actualizar_secuencias(): void
    {
        $respuestaVer = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.sequences'));

        $respuestaVer->assertStatus(200);
        $respuestaVer->assertSee('Secuencias Numéricas');

        $nombreSecuencia = 'Secuencia Corporativa '.uniqid();

        // Crear
        $respuestaCrear = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.sequences.store'), [
                'name' => $nombreSecuencia,
                'next' => 5000,
                'increment' => 1,
                'padding' => 7,
            ]);

        $respuestaCrear->assertSessionHas('success');

        $secuencia = Sequence::where('name', $nombreSecuencia)->first();
        $this->assertNotNull($secuencia);
        $this->assertEquals(5000, $secuencia->next);
        $this->assertEquals(7, $secuencia->padding);

        // Actualizar
        $respuestaActualizar = $this->actingAs($this->administrador, 'staff')
            ->put(route('admin.sequences.update', $secuencia->id), [
                'name' => $nombreSecuencia.' Modificada',
                'next' => 6000,
                'increment' => 2,
                'padding' => 8,
            ]);

        $respuestaActualizar->assertSessionHas('success');

        $this->assertDatabaseHas('sequences', [
            'id' => $secuencia->id,
            'name' => $nombreSecuencia.' Modificada',
            'next' => 6000,
            'increment' => 2,
            'padding' => 8,
        ]);
    }

    public function test_no_permite_eliminar_secuencia_activa_en_configuracion(): void
    {
        $secuenciaActiva = Sequence::create([
            'empresa_id' => 1,
            'name' => 'Secuencia Activa Protegida '.uniqid(),
            'next' => 1,
            'increment' => 1,
            'padding' => 6,
            'created' => now(),
            'updated' => now(),
        ]);

        AppSetting::establecer('tickets.ticket_sequence_id', (string) $secuenciaActiva->id, 1);

        $respuestaEliminar = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.sequences.destroy', $secuenciaActiva->id));

        $respuestaEliminar->assertSessionHas('error');
        $this->assertDatabaseHas('sequences', [
            'id' => $secuenciaActiva->id,
        ]);
    }

    public function test_permite_eliminar_secuencia_no_activa(): void
    {
        $secuenciaInactiva = Sequence::create([
            'empresa_id' => 1,
            'name' => 'Secuencia Para Eliminar '.uniqid(),
            'next' => 1,
            'increment' => 1,
            'padding' => 6,
            'created' => now(),
            'updated' => now(),
        ]);

        // Asignamos una secuencia distinta en la configuración
        AppSetting::establecer('tickets.ticket_sequence_id', '999999', 1);

        $respuestaEliminar = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.sequences.destroy', $secuenciaInactiva->id));

        $respuestaEliminar->assertSessionHas('success');
        $this->assertDatabaseMissing('sequences', [
            'id' => $secuenciaInactiva->id,
        ]);
    }

    /* =========================================================================
     * Pruebas de Autorización (Staff sin rol admin es bloqueado)
     * ========================================================================= */

    public function test_agente_sin_permisos_admin_no_puede_acceder_a_modulos_de_correo_o_secuencias(): void
    {
        $respuestaEmail = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.email'));
        $respuestaEmail->assertRedirect(route('agent.dashboard'));
        $respuestaEmail->assertSessionHas('error');

        $respuestaBanlist = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.banlist'));
        $respuestaBanlist->assertRedirect(route('agent.dashboard'));
        $respuestaBanlist->assertSessionHas('error');

        $respuestaTicketsSettings = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.settings.tickets'));
        $respuestaTicketsSettings->assertRedirect(route('agent.dashboard'));
        $respuestaTicketsSettings->assertSessionHas('error');

        $respuestaSequences = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.sequences'));
        $respuestaSequences->assertRedirect(route('agent.dashboard'));
        $respuestaSequences->assertSessionHas('error');
    }
}
