<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class ClientLoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_la_pagina_de_login_es_accesible()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Inicia Sesión');
    }

    public function test_el_cliente_puede_iniciar_sesion_con_credenciales_correctas()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_el_cliente_no_puede_iniciar_sesion_con_credenciales_incorrectas()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_el_cliente_autenticado_es_redirigido_desde_el_login()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/dashboard'); // or home, check route('login') redirects
    }
}
