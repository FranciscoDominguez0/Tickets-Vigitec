<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Staff;

class AgentLoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_la_pagina_de_login_de_agentes_es_accesible()
    {
        $response = $this->get('/agent/login');

        $response->assertStatus(200);
        $response->assertSee('Acceso de Agentes');
    }

    public function test_el_agente_puede_iniciar_sesion_con_credenciales_correctas()
    {
        $agent = Staff::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/agent/login', [
            'username' => $agent->username,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($agent, 'staff');
        $response->assertRedirect('/agent/dashboard');
    }

    public function test_el_agente_no_puede_iniciar_sesion_con_credenciales_incorrectas()
    {
        $agent = Staff::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/agent/login', [
            'username' => $agent->username,
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest('staff');
        $response->assertSessionHasErrors('username');
    }

    public function test_el_agente_autenticado_es_redirigido_desde_el_login()
    {
        $agent = Staff::factory()->create();

        $response = $this->actingAs($agent, 'staff')->get('/agent/login');

        $response->assertRedirect('/agent/dashboard');
    }
}
