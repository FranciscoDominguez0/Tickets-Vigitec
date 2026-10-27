<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            [
                'id' => 1,
                'name' => 'Abierto',
                'color' => '#3498db',
                'icon' => 'fa-folder-open',
                'order_by' => 1,
            ],
            [
                'id' => 2,
                'name' => 'En Camino',
                'color' => '#e67e22',
                'icon' => 'fa-truck',
                'order_by' => 2,
            ],
            [
                'id' => 3,
                'name' => 'En Proceso',
                'color' => '#f1c40f',
                'icon' => 'fa-cogs',
                'order_by' => 3,
            ],
            [
                'id' => 4,
                'name' => 'Resuelto',
                'color' => '#27ae60',
                'icon' => 'fa-check-circle',
                'order_by' => 4,
            ],
            [
                'id' => 5,
                'name' => 'Cerrado',
                'color' => '#95a5a6',
                'icon' => 'fa-times-circle',
                'order_by' => 5,
            ],
            [
                'id' => 6,
                'name' => 'Retenido',
                'color' => '#e74c3c',
                'icon' => 'fa-pause-circle',
                'order_by' => 6,
            ],
            [
                'id' => 7,
                'name' => 'En espera o/c',
                'color' => '#8e44ad',
                'icon' => 'fa-clock',
                'order_by' => 7,
            ],
        ];

        foreach ($estados as $estado) {
            TicketStatus::updateOrCreate(
                ['id' => $estado['id']],
                $estado
            );
        }
    }
}
