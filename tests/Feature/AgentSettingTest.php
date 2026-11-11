<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Staff;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AgentSettingTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $administrador;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = Staff::firstOrCreate(
            ['username' => 'admin_test_settings'],
            [
                'email' => 'admin_test_settings@test.com',
                'firstname' => 'Admin',
                'lastname' => 'Configuracion',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }

    public function test_administrador_puede_ver_configuracion_perfil_empresa(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('agent.settings.pages', ['t' => 'pages']));

        $response->assertStatus(200);
        $response->assertSee('Perfil de la empresa');
        $response->assertSee('Información básica');
        $response->assertSee('Logos');
        $response->assertSee('Fondo del login');
    }

    public function test_sidebar_de_administrador_renderiza_secciones_separadas(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('agent.settings.pages', ['t' => 'pages']));

        $response->assertStatus(200);
        $response->assertSee('PANEL ADMIN');
        $response->assertSee('Configuración');
        $response->assertSee('Perfil de la empresa');
        $response->assertSee('Facturación');
        $response->assertSee('Solicitudes');
        $response->assertSee('Tareas');
        $response->assertSee('Panel de Control');
        $response->assertSee('Notificaciones');
        $response->assertSee('Temas');
        $response->assertSee('Tickets Borrados');
        $response->assertSee('Correos Electrónicos');
        $response->assertSee('Lista de prohibidos');
        $response->assertSee('Diagnóstico');
        $response->assertSee('Agentes');
        $response->assertSee('Roles');
        $response->assertSee('Departamentos');
        $response->assertSee('Salir');
        $response->assertSee('Volver a Agentes');
    }

    public function test_administrador_puede_actualizar_informacion_basica(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('agent.settings.pages.store'), [
                'company_name' => 'Empresa Test Pro',
                'company_website' => 'https://empresatest.com',
                'company_phone' => '+507 8888-9999',
                'company_address' => 'Ciudad de Panamá, Calle 50',
                'company_logo_mode' => 'default',
                'login_bg_mode' => 'default',
                'active_tab' => 'basic',
            ]);

        $response->assertRedirect(route('agent.settings.pages', ['t' => 'pages', 'tab' => 'basic']));
        $response->assertSessionHas('exito');

        $this->assertEquals('Empresa Test Pro', AppSetting::obtener('company.name', '', 1));
        $this->assertEquals('https://empresatest.com', AppSetting::obtener('company.website', '', 1));
        $this->assertEquals('+507 8888-9999', AppSetting::obtener('company.phone', '', 1));
    }

    public function test_administrador_puede_subir_logo_personalizado(): void
    {
        Storage::fake('public');

        $archivoLogo = UploadedFile::fake()->image('logo_empresa.png', 400, 200);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('agent.settings.pages.store'), [
                'company_name' => 'Empresa Test',
                'company_logo_mode' => 'custom',
                'company_logo' => $archivoLogo,
                'login_bg_mode' => 'default',
                'active_tab' => 'logos',
            ]);

        $response->assertRedirect(route('agent.settings.pages', ['t' => 'pages', 'tab' => 'logos']));
        $response->assertSessionHas('exito');

        $rutaGuardada = AppSetting::obtener('company.logo', '', 1);
        $this->assertNotEmpty($rutaGuardada);
        $this->assertEquals('custom', AppSetting::obtener('company.logo_mode', '', 1));
        Storage::disk('public')->assertExists($rutaGuardada);
    }
}
