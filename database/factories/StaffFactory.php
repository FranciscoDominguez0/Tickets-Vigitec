<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'empresa_id' => 1,
            'dept_id' => 1,
            'role' => 'admin',
            'is_active' => true,
        ];
    }
}
