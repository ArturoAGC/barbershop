<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiTokenAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_via_api_returns_token(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@barbershop.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@barbershop.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'role']]);
    }

    public function test_login_via_api_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@barbershop.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@barbershop.com',
            'password' => 'contraseña_incorrecta',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Credenciales invalidas']);
    }

    public function test_authenticated_user_can_access_protected_route(): void
    {
        $user = User::factory()->create([
            'email' => 'cliente@barbershop.com',
            'password' => Hash::make('password123'),
        ]);

        $login = $this->postJson('/api/login', [
            'email' => 'cliente@barbershop.com',
            'password' => 'password123',
        ]);

        $token = $login->json('token');

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJson(['email' => 'cliente@barbershop.com']);
    }

    public function test_unauthenticated_user_cannot_access_protected_route(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    public function test_logout_revokes_token(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@barbershop.com',
            'password' => Hash::make('password123'),
        ]);

        $login = $this->postJson('/api/login', [
            'email' => 'admin@barbershop.com',
            'password' => 'password123',
        ]);

        $token = $login->json('token');

        $logout = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout');

        $logout->assertStatus(200)
            ->assertJson(['message' => 'Sesion cerrada']);

        // Verificamos directamente en la base de datos que el token fue eliminado
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}