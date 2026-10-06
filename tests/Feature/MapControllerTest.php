<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Staff;

class MapControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $staff;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup base data
        DB::table('empresas')->insert(['id' => 1, 'nombre' => 'Test', 'estado' => 'activa']);
        $this->staff = Staff::factory()->create(['empresa_id' => 1]);
    }

    public function test_el_indice_del_mapa_es_accesible()
    {
        $response = $this->actingAs($this->staff, 'staff')->get(route('agent.map'));
        $response->assertStatus(200);
        $response->assertViewIs('agent.map.index');
    }

    public function test_el_agente_puede_actualizar_su_ubicacion()
    {
        $response = $this->actingAs($this->staff, 'staff')->postJson(route('agent.location.update'), [
            'lat' => 8.98,
            'lng' => -79.52,
        ]);
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('staff_locations', [
            'staff_id' => $this->staff->id,
            'lat' => 8.98,
            'lng' => -79.52,
        ]);
    }

    public function test_ubicaciones_retorna_agentes_activos()
    {
        DB::table('ticket_status')->insert([
            ['id' => 4, 'name' => 'En proceso', 'color' => '#ffffff'],
        ]);
        
        // Disable foreign key checks for manual insertion to avoid dealing with missing departments/users
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('departments')->insert(['id' => 1, 'name' => 'IT', 'empresa_id' => 1]);
        $user = \App\Models\User::factory()->create(['empresa_id' => 1]);

        DB::table('tickets')->insert([
            'id' => 1,
            'ticket_number' => 'T-001',
            'staff_id' => $this->staff->id,
            'status_id' => 4,
            'empresa_id' => 1,
            'subject' => 'Test',
            'user_id' => $user->id,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('staff_locations')->insert([
            'staff_id' => $this->staff->id,
            'lat' => 10.0,
            'lng' => -80.0,
        ]);

        $response = $this->actingAs($this->staff, 'staff')->getJson(route('agent.map.locations'));
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['ok', 'locations']);
        $locations = $response->json('locations');
        $this->assertCount(1, $locations);
        $this->assertEquals($this->staff->id, $locations[0]['staff_id']);
        $this->assertEquals(10.0, $locations[0]['lat']);
    }
}
