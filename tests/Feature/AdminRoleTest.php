<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Staff;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRoleTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $administrador;

    protected Staff $agenteRegular;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = Staff::firstOrCreate(
            ['username' => 'admin_test_roles'],
            [
                'email' => 'admin_test_roles@test.com',
                'firstname' => 'Admin',
                'lastname' => 'Roles',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->agenteRegular = Staff::firstOrCreate(
            ['username' => 'agente_regular_roles'],
            [
                'email' => 'agente_roles@test.com',
                'firstname' => 'Agente',
                'lastname' => 'Roles',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'agent',
                'isadmin' => 0,
                'is_active' => true,
            ]
        );
    }

    public function test_administrador_puede_listar_roles(): void
    {
        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.roles'));

        $response->assertStatus(200);
        $response->assertSee('Roles');
        $response->assertSee('Gestión de roles y permisos');
        $response->assertSee('admin');
        $response->assertSee('agent');
    }

    public function test_administrador_puede_crear_un_nuevo_rol(): void
    {
        $nombreRol = 'Auditor de Calidad';

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.roles.store'), [
                'name' => $nombreRol,
            ]);

        $response->assertRedirect(route('admin.roles'));
        $response->assertSessionHas('exito');

        $this->assertDatabaseHas('roles', [
            'empresa_id' => 1,
            'name' => $nombreRol,
            'is_enabled' => 1,
        ]);
    }

    public function test_validacion_evita_crear_rol_duplicado_en_misma_empresa(): void
    {
        Role::firstOrCreate([
            'empresa_id' => 1,
            'name' => 'Rol Repetido',
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.roles.store'), [
                'name' => 'Rol Repetido',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_procesamiento_masivo_puede_deshabilitar_y_habilitar_roles(): void
    {
        $rol = Role::firstOrCreate([
            'empresa_id' => 1,
            'name' => 'Rol Para Deshabilitar',
            'is_enabled' => true,
        ]);

        // Deshabilitar
        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.roles.mass'), [
                'accion' => 'deshabilitar',
                'ids' => [$rol->id],
            ]);

        $response->assertRedirect(route('admin.roles'));
        $response->assertSessionHas('exito');
        $this->assertFalse((bool) $rol->fresh()->is_enabled);

        // Habilitar
        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.roles.mass'), [
                'accion' => 'habilitar',
                'ids' => [$rol->id],
            ]);

        $response->assertRedirect(route('admin.roles'));
        $response->assertSessionHas('exito');
        $this->assertTrue((bool) $rol->fresh()->is_enabled);
    }

    public function test_no_se_puede_eliminar_un_rol_asignado_a_un_agente(): void
    {
        // El rol 'admin' está en uso por el administrador de prueba
        $rolAdmin = Role::firstOrCreate([
            'empresa_id' => 1,
            'name' => 'admin',
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.roles.mass'), [
                'accion' => 'eliminar',
                'ids' => [$rolAdmin->id],
            ]);

        $response->assertRedirect(route('admin.roles'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('roles', ['id' => $rolAdmin->id]);
    }

    public function test_se_puede_eliminar_un_rol_sin_agentes_asignados(): void
    {
        $rolSinAgentes = Role::firstOrCreate([
            'empresa_id' => 1,
            'name' => 'Rol Sin Personal',
            'is_enabled' => true,
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.roles.mass'), [
                'accion' => 'eliminar',
                'ids' => [$rolSinAgentes->id],
            ]);

        $response->assertRedirect(route('admin.roles'));
        $response->assertSessionHas('exito');
        $this->assertDatabaseMissing('roles', ['id' => $rolSinAgentes->id]);
    }

    public function test_administrador_puede_ver_y_guardar_permisos_de_un_rol(): void
    {
        $rol = Role::firstOrCreate([
            'empresa_id' => 1,
            'name' => 'supervisor_test',
        ]);

        // Ver matriz de permisos
        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.roles.permissions', ['nombre' => $rol->name]));

        $response->assertStatus(200);
        $response->assertSee('Permisos del Rol');
        $response->assertSee('supervisor_test');
        $response->assertSee('ticket.create');

        // Guardar permisos
        $responsePost = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.roles.permissions.store', ['nombre' => $rol->name]), [
                'perms' => ['ticket.create', 'ticket.reply'],
            ]);

        $responsePost->assertRedirect(route('admin.roles.permissions', ['nombre' => $rol->name]));
        $responsePost->assertSessionHas('exito');

        $this->assertDatabaseHas('role_permissions', [
            'empresa_id' => 1,
            'role_name' => $rol->name,
            'perm_key' => 'ticket.create',
            'is_enabled' => 1,
        ]);

        // Restablecer permisos
        $responseReset = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.roles.permissions.store', ['nombre' => $rol->name]), [
                'do' => 'reset',
            ]);

        $responseReset->assertRedirect(route('admin.roles.permissions', ['nombre' => $rol->name]));
        $responseReset->assertSessionHas('exito');

        $this->assertDatabaseMissing('role_permissions', [
            'empresa_id' => 1,
            'role_name' => $rol->name,
        ]);
    }

    public function test_agente_no_admin_es_redireccionado_al_intentar_ver_roles(): void
    {
        $response = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.roles'));

        $response->assertRedirect(route('agent.dashboard'));
        $response->assertSessionHas('error');
    }
}
