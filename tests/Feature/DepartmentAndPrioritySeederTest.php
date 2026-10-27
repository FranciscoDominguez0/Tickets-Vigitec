<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Priority;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\PrioritySeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DepartmentAndPrioritySeederTest extends TestCase
{
    use DatabaseTransactions;

    public function test_seeder_de_departamentos_inserta_los_departamentos_correctos(): void
    {
        $this->seed(DepartmentSeeder::class);

        $this->assertDatabaseHas('departments', [
            'id' => 1,
            'name' => 'Soporte Técnico',
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('departments', [
            'id' => 2,
            'name' => 'Sistemas e Infraestructura',
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('departments', [
            'id' => 3,
            'name' => 'Mantenimiento en Sitio',
            'requires_report' => 1,
        ]);

        $this->assertGreaterThanOrEqual(5, Department::count());
    }

    public function test_seeder_de_prioridades_inserta_las_prioridades_y_colores_correctos(): void
    {
        $this->seed(PrioritySeeder::class);

        $this->assertDatabaseHas('priorities', [
            'id' => 1,
            'name' => 'Baja',
            'level' => 1,
            'color' => '#3b82f6',
        ]);

        $this->assertDatabaseHas('priorities', [
            'id' => 2,
            'name' => 'Normal',
            'level' => 2,
            'color' => '#10b981',
        ]);

        $this->assertDatabaseHas('priorities', [
            'id' => 3,
            'name' => 'Alta',
            'level' => 3,
            'color' => '#f59e0b',
        ]);

        $this->assertDatabaseHas('priorities', [
            'id' => 4,
            'name' => 'Urgente',
            'level' => 4,
            'color' => '#ef4444',
        ]);

        $this->assertGreaterThanOrEqual(4, Priority::count());
    }
}
