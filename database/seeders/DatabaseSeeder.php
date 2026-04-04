<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Barber;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@barbershop.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Crear cliente de prueba
        User::create([
            'name' => 'Cliente Prueba',
            'email' => 'cliente@barbershop.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
        ]);

        // Crear servicios
        Service::insert([
            ['name' => 'Corte clásico', 'description' => 'Corte tradicional con tijera y máquina', 'price' => 80, 'duration_minutes' => 30, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Corte + Barba', 'description' => 'Corte completo más arreglo de barba', 'price' => 130, 'duration_minutes' => 45, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Arreglo de barba', 'description' => 'Perfilado y arreglo de barba con navaja', 'price' => 60, 'duration_minutes' => 20, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tinte', 'description' => 'Coloración completa del cabello', 'price' => 200, 'duration_minutes' => 90, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Crear estilistas
        Barber::insert([
            ['name' => 'Carlos Méndez', 'specialty' => 'Cortes modernos', 'bio' => '5 años de experiencia en cortes urbanos', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Diego Ramírez', 'specialty' => 'Barbas y estilos clásicos', 'bio' => 'Especialista en estilos vintage y barbas', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Luis Torres', 'specialty' => 'Coloración y tintes', 'bio' => 'Experto en coloración y tratamientos', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}