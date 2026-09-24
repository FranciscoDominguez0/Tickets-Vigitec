<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pantalla_de_registro_se_muestra()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Crear cuenta');
        $response->assertSee('Nombre');
    }

    public function test_un_nuevo_usuario_se_puede_registrar()
    {
        $response = $this->post('/register', [
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'phone' => '12345678',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticatedAs(User::where('email', 'test@example.com')->first(), 'web');
        $response->assertRedirect(route('dashboard'));
    }

    public function test_el_registro_falla_si_falta_informacion()
    {
        $response = $this->post('/register', [
            'firstname' => 'Test',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors(['lastname', 'password']);
    }
}
