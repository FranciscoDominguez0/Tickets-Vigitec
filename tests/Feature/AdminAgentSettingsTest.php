<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Department;
use App\Models\Staff;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAgentSettingsTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $administrador;

    protected Staff $agenteRegular;

    protected Department $departamento;

    protected function setUp(): void
    {
        parent::setUp();

        $this->departamento = Department::firstOrCreate(
            ['name' => 'Soporte Agentes Pruebas'],
            [
                'ispublic' => 1,
                'email_id' => 1,
            ]
        );

        $this->administrador = Staff::firstOrCreate(
            ['username' => 'admin_config_agents_test'],
            [
                'email' => 'admin_config_agents_test@test.com',
                'firstname' => 'Admin',
                'lastname' => 'ConfigAgentes',
                'password' => Hash::make('password123'),
                'dept_id' => $this->departamento->id,
                'empresa_id' => 1,
                'role' => 'admin',
                'isadmin' => 1,
                'is_active' => true,
            ]
        );

        $this->agenteRegular = Staff::firstOrCreate(
            ['username' => 'agente_config_agents_test'],
            [
                'email' => 'agente_config_agents_test@test.com',
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

    public function test_administrador_puede_ver_vista_de_configuracion_de_agentes(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.settings.agents'));

        $response->assertStatus(200);
        $response->assertSee('Configuración de Agentes y Personal');
        $response->assertSee('Formato de Nombre de Agentes');
        $response->assertSee('Reglas y Expiración de Contraseñas');
        $response->assertSee('Protección Contra Accesos No Autorizados y Sesiones');
    }

    public function test_administrador_puede_actualizar_configuracion_de_agentes(): void
    {
        $datos = [
            'staff_name_format' => 'last_first',
            'hide_staff_name' => '1',
            'agent_avatar_source' => 'gravatar',
            'disable_agent_collaborators' => '1',
            'staff_password_expiration_days' => 90,
            'staff_allow_pw_reset' => '1',
            'staff_pw_reset_window' => 45,
            'staff_password_min_length' => 10,
            'staff_password_require_symbols' => '1',
            'staff_session_timeout' => 60,
            'staff_max_login_attempts' => 3,
            'staff_lockout_duration' => 15,
            'staff_bind_agent_ip' => '1',
            'active_tab' => 'passwords',
        ];

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.settings.agents.store'), $datos);

        $response->assertRedirect(route('admin.settings.agents', ['tab' => 'passwords']));
        $response->assertSessionHas('success');

        $empresaId = (int) $this->administrador->empresa_id;

        $this->assertEquals('last_first', AppSetting::obtener('agents.name_format', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('agents.hide_staff_name', null, $empresaId));
        $this->assertEquals('gravatar', AppSetting::obtener('agents.avatar_source', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('agents.disable_agent_collaborators', null, $empresaId));
        $this->assertEquals('90', AppSetting::obtener('agents.password_expiration_days', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('agents.allow_pw_reset', null, $empresaId));
        $this->assertEquals('45', AppSetting::obtener('agents.pw_reset_window', null, $empresaId));
        $this->assertEquals('10', AppSetting::obtener('agents.password_min_length', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('agents.password_require_symbols', null, $empresaId));
        $this->assertEquals('60', AppSetting::obtener('agents.session_timeout', null, $empresaId));
        $this->assertEquals('3', AppSetting::obtener('agents.max_login_attempts', null, $empresaId));
        $this->assertEquals('15', AppSetting::obtener('agents.lockout_duration', null, $empresaId));
        $this->assertEquals('1', AppSetting::obtener('agents.bind_agent_ip', null, $empresaId));
    }

    public function test_validacion_falla_si_formato_o_avatar_son_invalidos(): void
    {
        $datos = [
            'staff_name_format' => 'invalido',
            'agent_avatar_source' => 'desconocido',
            'staff_password_expiration_days' => 0,
            'staff_pw_reset_window' => 30,
            'staff_password_min_length' => 8,
            'staff_session_timeout' => 60,
            'staff_max_login_attempts' => 5,
            'staff_lockout_duration' => 30,
        ];

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.settings.agents.store'), $datos);

        $response->assertSessionHasErrors([
            'staff_name_format',
            'agent_avatar_source',
        ]);
    }

    public function test_validacion_falla_si_limites_estan_fuera_de_rango(): void
    {
        $datos = [
            'staff_name_format' => 'first_last',
            'agent_avatar_source' => 'initials',
            'staff_password_expiration_days' => 500, // Max 365
            'staff_pw_reset_window' => 2, // Min 5
            'staff_password_min_length' => 3, // Min 6
            'staff_session_timeout' => 2000, // Max 1440
            'staff_max_login_attempts' => 0, // Min 1
            'staff_lockout_duration' => 0, // Min 1
        ];

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.settings.agents.store'), $datos);

        $response->assertSessionHasErrors([
            'staff_password_expiration_days',
            'staff_pw_reset_window',
            'staff_password_min_length',
            'staff_session_timeout',
            'staff_max_login_attempts',
            'staff_lockout_duration',
        ]);
    }

    public function test_agente_sin_permiso_de_administrador_es_redireccionado(): void
    {
        $response = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.settings.agents'));

        $response->assertRedirect(route('agent.dashboard'));
        $response->assertSessionHas('error');
    }
}
