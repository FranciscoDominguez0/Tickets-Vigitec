<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'id' => 1,
                'name' => 'Soporte Técnico',
                'description' => 'Atención a incidentes y soporte general de TI',
                'empresa_id' => 1,
                'is_active' => 1,
                'requires_report' => false,
            ],
            [
                'id' => 2,
                'name' => 'Sistemas e Infraestructura',
                'description' => 'Servidores, redes, bases de datos y conectividad',
                'empresa_id' => 1,
                'is_active' => 1,
                'requires_report' => false,
            ],
            [
                'id' => 3,
                'name' => 'Mantenimiento en Sitio',
                'description' => 'Visitas técnicas y mantenimiento preventivo/correctivo',
                'empresa_id' => 1,
                'is_active' => 1,
                'requires_report' => true,
            ],
            [
                'id' => 4,
                'name' => 'Facturación y Cobranzas',
                'description' => 'Consultas de pagos, estados de cuenta y facturas',
                'empresa_id' => 1,
                'is_active' => 1,
                'requires_report' => false,
            ],
            [
                'id' => 5,
                'name' => 'Ventas y Comercial',
                'description' => 'Atención comercial, cotizaciones y nuevos servicios',
                'empresa_id' => 1,
                'is_active' => 1,
                'requires_report' => false,
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['id' => $dept['id']], $dept);
        }
    }
}
