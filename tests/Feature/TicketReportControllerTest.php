<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class TicketReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $staff;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('empresas')->insert(['id' => 1, 'nombre' => 'Test', 'estado' => 'activa']);
        $this->staff = Staff::factory()->create(['empresa_id' => 1]);
        
        DB::table('ticket_status')->insert([
            ['id' => 3, 'name' => 'Cerrado', 'color' => '#000000'],
        ]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('departments')->insert(['id' => 1, 'name' => 'IT', 'empresa_id' => 1]);
        $user = \App\Models\User::factory()->create(['empresa_id' => 1]);

        DB::table('tickets')->insert([
            'id' => 1,
            'ticket_number' => 'T-001',
            'staff_id' => $this->staff->id,
            'status_id' => 3,
            'empresa_id' => 1,
            'subject' => 'Test Report',
            'closed' => now(),
            'user_id' => $user->id,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function test_el_agente_puede_ver_la_lista_de_facturacion()
    {
        $response = $this->actingAs($this->staff, 'staff')->get(route('agent.tickets.reports'));
        $response->assertStatus(200);
        $response->assertViewIs('agent.tickets.billing');
        $response->assertSee('Test Report');
    }

    public function test_el_agente_puede_ver_la_hoja_de_reporte()
    {
        $response = $this->actingAs($this->staff, 'staff')->get(route('agent.tickets.report_sheet', 1));
        $response->assertStatus(200);
        $response->assertViewIs('agent.tickets.report_sheet');
    }

    public function test_el_agente_puede_guardar_un_nuevo_reporte()
    {
        $response = $this->actingAs($this->staff, 'staff')->post(route('agent.tickets.report.store', 1), [
            'report_type' => 'cotizacion',
            'observations' => 'Initial notes',
            'item_description' => ['Task 1', 'Task 2'],
            'item_price' => ['50.00', '100.50'],
        ]);

        $response->assertRedirect(route('agent.tickets.report_sheet', 1));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ticket_reports', [
            'ticket_id' => 1,
            'billing_status' => 'cotizacion',
            'final_price' => 150.50,
            'observations' => 'Initial notes',
            'created_by' => $this->staff->id,
        ]);

        $this->assertDatabaseHas('ticket_report_items', [
            'description' => 'Task 1',
            'price' => 50.00,
        ]);
        
        $this->assertDatabaseHas('ticket_report_items', [
            'description' => 'Task 2',
            'price' => 100.50,
        ]);
    }

    public function test_el_agente_puede_editar_un_reporte_existente()
    {
        // First create a report manually
        $this->actingAs($this->staff, 'staff')->post(route('agent.tickets.report.store', 1), [
            'report_type' => 'pending',
            'observations' => 'Old notes',
            'item_description' => ['Task Old'],
            'item_price' => ['10.00'],
        ]);

        // Then edit it
        $response = $this->actingAs($this->staff, 'staff')->post(route('agent.tickets.report.store', 1), [
            'report_type' => 'visita_tecnica',
            'observations' => 'Updated notes',
            'item_description' => ['Task New'],
            'item_price' => ['99.99'],
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('ticket_reports', [
            'ticket_id' => 1,
            'billing_status' => 'visita_tecnica',
            'final_price' => 99.99,
            'observations' => 'Updated notes',
        ]);

        $this->assertDatabaseMissing('ticket_report_items', [
            'description' => 'Task Old',
        ]);

        $this->assertDatabaseHas('ticket_report_items', [
            'description' => 'Task New',
            'price' => 99.99,
        ]);
    }
}
