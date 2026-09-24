<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pantalla_de_recuperar_contrasena_se_muestra()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Recuperar contraseña');
    }

    public function test_se_puede_solicitar_un_enlace_de_recuperacion()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHas('status', 'Le hemos enviado por correo electrónico el enlace para restablecer su contraseña.');
    }

    public function test_falla_si_el_correo_es_invalido()
    {
        $response = $this->post('/forgot-password', [
            'email' => 'no-es-un-correo',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
