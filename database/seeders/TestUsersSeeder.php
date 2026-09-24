<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear un Usuario (Cliente) de prueba
        \App\Models\User::firstOrCreate(
            ['email' => 'cliente@test.com'],
            [
                'firstname' => 'Juan',
                'lastname' => 'Cliente',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'empresa_id' => 1, // Empresa default
                'status' => 1,
            ]
        );

        // 2. Crear un Administrador/Agente de prueba
        \App\Models\Staff::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@test.com',
                'firstname' => 'Carlos',
                'lastname' => 'Administrador',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'empresa_id' => 1, // Empresa default
                'dept_id' => 1,    // Departamento default
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
