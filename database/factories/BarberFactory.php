<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BarberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'      => $this->faker->name(),
            'specialty' => $this->faker->randomElement(['Cortes modernos', 'Barbas', 'Coloración', 'Clásicos']),
            'bio'       => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}