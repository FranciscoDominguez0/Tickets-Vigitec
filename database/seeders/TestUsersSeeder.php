<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear un Usuario (Cliente) de prueba
        User::firstOrCreate(
            ['email' => 'cliente@test.com'],
            [
                'firstname' => 'Juan',
                'lastname' => 'Cliente',
                'password' => Hash::make('password123'),
                'empresa_id' => 1, // Empresa default
                'status' => 1,
            ]
        );

        // 2. Crear un Administrador/Agente de prueba
        Staff::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@test.com',
                'firstname' => 'Carlos',
                'lastname' => 'Administrador',
                'password' => Hash::make('password123'),
                'empresa_id' => 1, // Empresa default
                'dept_id' => 1,    // Departamento default
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
