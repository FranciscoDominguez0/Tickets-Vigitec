<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            [
                'id' => 1,
                'name' => 'Baja',
                'level' => 1,
                'color' => '#3b82f6',
            ],
            [
                'id' => 2,
                'name' => 'Normal',
                'level' => 2,
                'color' => '#10b981',
            ],
            [
                'id' => 3,
                'name' => 'Alta',
                'level' => 3,
                'color' => '#f59e0b',
            ],
            [
                'id' => 4,
                'name' => 'Urgente',
                'level' => 4,
                'color' => '#ef4444',
            ],
        ];

        foreach ($priorities as $priority) {
            Priority::updateOrCreate(['id' => $priority['id']], $priority);
        }
    }
}
