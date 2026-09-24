<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ClientForgotPasswordTest extends TestCase
{
    use DatabaseTransactions;

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

        $response->assertSessionHas('status', 'Si el correo existe en nuestro sistema, te hemos enviado un enlace para restablecer tu contraseña.');
    }

    public function test_falla_si_el_correo_es_invalido()
    {
        $response = $this->post('/forgot-password', [
            'email' => 'no-es-un-correo',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
