<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Staff;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AgentProfileTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $agente;

    protected function setUp(): void
    {
        parent::setUp();

        $departamento = Department::firstOrCreate(
            ['name' => 'Soporte Técnico'],
            [
                'description' => 'Departamento de prueba',
                'empresa_id' => 1,
                'is_active' => 1,
            ]
        );

        $this->agente = Staff::firstOrCreate(
            ['username' => 'test_agent_profile'],
            [
                'email' => 'test_agent_profile@test.com',
                'firstname' => 'Agente',
                'lastname' => 'Perfil',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'admin',
                'is_active' => true,
                'dept_id' => $departamento->id,
            ]
        );
    }

    public function test_agente_puede_ver_su_perfil(): void
    {
        $response = $this->actingAs($this->agente, 'staff')
            ->get(route('agent.profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Mi Perfil');
        $response->assertSee($this->agente->firstname);
        $response->assertSee($this->agente->email);
        $response->assertSee($this->agente->username);
    }

    public function test_agente_puede_actualizar_su_informacion_basica(): void
    {
        $response = $this->actingAs($this->agente, 'staff')
            ->put(route('agent.profile.update'), [
                'firstname' => 'Carlos',
                'lastname' => 'Modificado',
                'email' => 'carlos.modificado@test.com',
                'dark_mode' => 1,
            ]);

        $response->assertRedirect(route('agent.profile.index'));
        $response->assertSessionHas('exito');

        $this->agente->refresh();
        $this->assertEquals('Carlos', $this->agente->firstname);
        $this->assertEquals('Modificado', $this->agente->lastname);
        $this->assertEquals('carlos.modificado@test.com', $this->agente->email);
        $this->assertTrue((bool) $this->agente->dark_mode);
    }

    public function test_agente_puede_cambiar_su_contrasena(): void
    {
        $response = $this->actingAs($this->agente, 'staff')
            ->put(route('agent.profile.password'), [
                'current_password' => 'password123',
                'password' => 'nuevaClaveSegura99',
                'password_confirmation' => 'nuevaClaveSegura99',
            ]);

        $response->assertRedirect(route('agent.profile.index'));
        $response->assertSessionHas('exito');

        $this->agente->refresh();
        $this->assertTrue(Hash::check('nuevaClaveSegura99', $this->agente->password));
    }

    public function test_falla_cambio_de_contrasena_si_actual_es_incorrecta(): void
    {
        $response = $this->actingAs($this->agente, 'staff')
            ->put(route('agent.profile.password'), [
                'current_password' => 'claveInvalidaErronea',
                'password' => 'otraClaveNueva123',
                'password_confirmation' => 'otraClaveNueva123',
            ]);

        $response->assertSessionHasErrors(['current_password'], errorBag: 'password');
    }
}
