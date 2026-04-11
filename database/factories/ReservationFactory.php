<?php
namespace Database\Factories;

use App\Models\Barber;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'          => User::factory()->create(['role' => 'client'])->id,
            'service_id'       => Service::factory(),
            'barber_id'        => Barber::factory(),
            'reservation_date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'reservation_time' => $this->faker->randomElement(['09:00', '09:45', '10:30', '11:15', '12:00', '13:30', '14:15', '15:00']),
            'status'           => 'pending',
            'notes'            => null,
        ];
    }
}
