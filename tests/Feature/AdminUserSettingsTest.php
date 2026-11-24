<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Department;
use App\Models\Staff;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserSettingsTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $administrador;

    protected Staff $agenteRegular;

    protected Department $departamento;

    protected function setUp(): void
    {
        parent::setUp();

        $this->departamento = Department::firstOrCreate(
            ['name' => 'Soporte Usuarios Pruebas'],
            [
                'ispublic' => 1,
                'email_id' => 1,
            ]
        );

        $this->administrador = Staff::firstOrCreate(
            ['username' => 'admin_config_users_test'],
            [
                'email' => 'admin_config_users_test@test.com',
                'firstname' => 'Admin',
                'lastname' => 'ConfigUsuarios',
                'password' => Hash::make('password123'),
                'dept_id' => $this->departamento->id,
                'empresa_id' => 1,
                'role' => 'admin',
                'isadmin' => 1,
                'is_active' => true,
            ]
        );

        $this->agenteRegular = Staff::firstOrCreate(
            ['username' => 'agente_config_users_test'],
            [
                'email' => 'agente_config_users_test@test.com',
                'firstname' => 'Agente',
                'lastname' => 'Regular',
                'password' => Hash::make('password123'),
                'dept_id' => $this->departamento->id,
                'empresa_id' => 1,
                'role' => 'agent',
                'isadmin' => 0,
                'is_active' => true,
            ]
        );
    }

    public function test_administrador_puede_ver_vista_de_configuracion_de_usuarios(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.settings.users'));

        $response->assertStatus(200);
        $response->assertSee('Configuración de Usuarios y Clientes');
        $response->assertSee('Modo de Registro de Usuarios');
        $response->assertSee('Sesiones y Límites de Tickets');
        $response->assertSee('Organizaciones y Colaboración');
        $response->assertSee('Avisos del Portal');
    }

    public function test_administrador_puede_actualizar_configuracion_de_usuarios(): void
    {
        $datos = [
            'client_registration' => 'private',
            'client_registration_required' => '1',
            'client_verify_email' => '1',
            'client_allow_pw_reset' => '1',
            'client_session_timeout' => 90,
            'client_max_open_tickets' => 8,
            'auto_assign_organization' => '1',
            'org_members_see_all_tickets' => '1',
            'auto_add_collaborators' => '1',
            'max_collaborators_per_ticket' => 12,
            'client_welcome_msg' => 'Bienvenido al portal exclusivo de soporte de nuestra empresa.',
            'client_guest_ticket_notice' => 'Nota importante: asegúrate de contar con autorización previa.',
            'active_tab' => 'general',
        ];

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.settings.users.store'), $datos);

        $response->assertRedirect(route('admin.settings.users', ['tab' => 'general']));
        $response->assertSessionHas('success');

        $empresaId = (int) $this->administrador->empresa_id;

        $this->assertEquals('private', AppSetting::obtener('users.client_registration', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('users.client_registration_required', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('users.client_verify_email', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('users.client_allow_pw_reset', null, $empresaId));
        $this->assertEquals('90', AppSetting::obtener('users.client_session_timeout', null, $empresaId));
        $this->assertEquals('8', AppSetting::obtener('users.client_max_open_tickets', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('users.auto_assign_organization', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('users.org_members_see_all_tickets', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('users.auto_add_collaborators', null, $empresaId));
        $this->assertEquals('12', AppSetting::obtener('users.max_collaborators_per_ticket', null, $empresaId));
        $this->assertEquals('Bienvenido al portal exclusivo de soporte de nuestra empresa.', AppSetting::obtener('users.client_welcome_msg', null, $empresaId));
        $this->assertEquals('Nota importante: asegúrate de contar con autorización previa.', AppSetting::obtener('users.client_guest_ticket_notice', null, $empresaId));
    }

    public function test_validacion_falla_si_modo_de_registro_es_invalido(): void
    {
        $datos = [
            'client_registration' => 'modo_invalido_xyz',
            'client_session_timeout' => 60,
            'client_max_open_tickets' => 0,
            'max_collaborators_per_ticket' => 5,
        ];

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.settings.users.store'), $datos);

        $response->assertSessionHasErrors(['client_registration']);
    }

    public function test_validacion_falla_si_limites_estan_fuera_de_rango(): void
    {
        $datos = [
            'client_registration' => 'public',
            'client_session_timeout' => 2000, // Max 1440
            'client_max_open_tickets' => -1, // Min 0
            'max_collaborators_per_ticket' => 100, // Max 50
        ];

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.settings.users.store'), $datos);

        $response->assertSessionHasErrors([
            'client_session_timeout',
            'client_max_open_tickets',
            'max_collaborators_per_ticket',
        ]);
    }

    public function test_agente_sin_permisos_de_administrador_es_redireccionado(): void
    {
        $response = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.settings.users'));

        $response->assertRedirect(route('agent.dashboard'));
        $response->assertSessionHas('error');
    }
}
