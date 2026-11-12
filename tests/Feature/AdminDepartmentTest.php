<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Staff;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDepartmentTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $administrador;

    protected Staff $agenteRegular;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = Staff::firstOrCreate(
            ['username' => 'admin_test_depts'],
            [
                'email' => 'admin_test_depts@test.com',
                'firstname' => 'Admin',
                'lastname' => 'Departamentos',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->agenteRegular = Staff::firstOrCreate(
            ['username' => 'agente_regular_depts'],
            [
                'email' => 'agente_depts@test.com',
                'firstname' => 'Agente',
                'lastname' => 'Departamentos',
                'password' => Hash::make('password123'),
                'empresa_id' => 1,
                'role' => 'agent',
                'isadmin' => 0,
                'is_active' => true,
            ]
        );
    }

    public function test_administrador_puede_listar_departamentos(): void
    {
        Department::firstOrCreate(
            ['empresa_id' => 1, 'name' => 'Soporte Técnico Especializado'],
            ['description' => 'Área de soporte avanzado', 'is_active' => true]
        );

        $response = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.departments'));

        $response->assertStatus(200);
        $response->assertSee('Departamentos');
        $response->assertSee('Soporte Técnico Especializado');
    }

    public function test_administrador_puede_crear_un_nuevo_departamento(): void
    {
        $nombreDept = 'Investigación y Desarrollo '.uniqid();

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.departments.store'), [
                'name' => $nombreDept,
                'description' => 'Departamento de innovación y producto',
                'is_active' => '1',
                'requires_report' => '1',
            ]);

        $response->assertRedirect(route('admin.departments'));
        $response->assertSessionHas('exito');

        $this->assertDatabaseHas('departments', [
            'empresa_id' => 1,
            'name' => $nombreDept,
            'is_active' => 1,
            'requires_report' => 1,
        ]);
    }

    public function test_validacion_evita_crear_departamento_duplicado(): void
    {
        $nombre = 'Ventas '.uniqid();
        Department::create([
            'empresa_id' => 1,
            'name' => $nombre,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.departments.store'), [
                'name' => $nombre,
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_administrador_puede_actualizar_un_departamento(): void
    {
        $dept = Department::create([
            'empresa_id' => 1,
            'name' => 'Operaciones '.uniqid(),
            'description' => 'Original',
            'is_active' => true,
        ]);

        $nuevoNombre = 'Operaciones Logísticas '.uniqid();

        $response = $this->actingAs($this->administrador, 'staff')
            ->put(route('admin.departments.update', ['id' => $dept->id]), [
                'id' => $dept->id,
                'name' => $nuevoNombre,
                'description' => 'Actualizado con éxito',
                'is_active' => '1',
                'requires_report' => '1',
            ]);

        $response->assertRedirect(route('admin.departments'));
        $response->assertSessionHas('exito');

        $dept->refresh();
        $this->assertEquals($nuevoNombre, $dept->name);
        $this->assertEquals('Actualizado con éxito', $dept->description);
        $this->assertTrue($dept->requires_report);
    }

    public function test_procesamiento_masivo_puede_deshabilitar_y_habilitar_departamentos(): void
    {
        $dept = Department::create([
            'empresa_id' => 1,
            'name' => 'Mantenimiento '.uniqid(),
            'is_active' => true,
        ]);

        // Deshabilitar
        $responseDisable = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.departments.mass'), [
                'ids' => [$dept->id],
                'accion' => 'deshabilitar',
            ]);

        $responseDisable->assertRedirect(route('admin.departments'));
        $dept->refresh();
        $this->assertFalse($dept->is_active);

        // Habilitar
        $responseEnable = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.departments.mass'), [
                'ids' => [$dept->id],
                'accion' => 'habilitar',
            ]);

        $responseEnable->assertRedirect(route('admin.departments'));
        $dept->refresh();
        $this->assertTrue($dept->is_active);
    }

    public function test_no_permite_eliminar_departamento_con_agentes_asignados(): void
    {
        $dept = Department::create([
            'empresa_id' => 1,
            'name' => 'Atención al Cliente '.uniqid(),
            'is_active' => true,
        ]);

        // Asignar agente al departamento
        DB::table('staff_departments')->insert([
            'dept_id' => $dept->id,
            'staff_id' => $this->administrador->id,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.departments.mass'), [
                'ids' => [$dept->id],
                'accion' => 'eliminar',
            ]);

        $response->assertRedirect(route('admin.departments'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('departments', [
            'id' => $dept->id,
        ]);
    }

    public function test_no_permite_eliminar_departamento_con_tickets_asignados(): void
    {
        $dept = Department::create([
            'empresa_id' => 1,
            'name' => 'Garantías '.uniqid(),
            'is_active' => true,
        ]);

        $cliente = \App\Models\User::firstOrCreate(
            ['email' => 'cliente_dept_test@test.com'],
            ['firstname' => 'Cliente', 'lastname' => 'Test', 'password' => Hash::make('password123'), 'empresa_id' => 1]
        );

        $prioridad = \App\Models\Priority::firstOrCreate(['id' => 1], ['name' => 'Baja', 'id' => 1]);
        $estado = \App\Models\TicketStatus::firstOrCreate(['id' => 1], ['name' => 'Abierto', 'id' => 1]);

        // Crear ticket asignado
        Ticket::create([
            'empresa_id' => 1,
            'ticket_number' => 'TCK-'.rand(10000, 99999),
            'subject' => 'Ticket de prueba para departamento',
            'dept_id' => $dept->id,
            'status_id' => $estado->id,
            'user_id' => $cliente->id,
            'staff_id' => $this->administrador->id,
            'priority_id' => $prioridad->id,
            'created' => now(),
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.departments.mass'), [
                'ids' => [$dept->id],
                'accion' => 'eliminar',
            ]);

        $response->assertRedirect(route('admin.departments'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('departments', [
            'id' => $dept->id,
        ]);
    }

    public function test_elimina_departamento_sin_asignaciones(): void
    {
        $dept = Department::create([
            'empresa_id' => 1,
            'name' => 'Temporal Para Borrar '.uniqid(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.departments.mass'), [
                'ids' => [$dept->id],
                'accion' => 'eliminar',
            ]);

        $response->assertRedirect(route('admin.departments'));
        $response->assertSessionHas('exito');

        $this->assertDatabaseMissing('departments', [
            'id' => $dept->id,
        ]);
    }

    public function test_agente_sin_permiso_admin_es_redireccionado(): void
    {
        $response = $this->actingAs($this->agenteRegular, 'staff')
            ->get(route('admin.departments'));

        $response->assertRedirect(route('agent.dashboard'));
        $response->assertSessionHas('error');
    }
}
