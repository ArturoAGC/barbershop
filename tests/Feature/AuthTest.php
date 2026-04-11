<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads_correctly(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_register_page_loads_correctly(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_client_can_register(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Cliente Test',
            'email'                 => 'test@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/client/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
            'role'  => 'client',
        ]);
    }

    public function test_client_can_login(): void
    {
        $user = User::factory()->create([
            'email'    => 'cliente@test.com',
            'password' => bcrypt('password123'),
            'role'     => 'client',
        ]);

        $response = $this->post('/login', [
            'email'    => 'cliente@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/client/dashboard');
    }

    public function test_admin_is_redirected_to_admin_dashboard_after_login(): void
    {
        $admin = User::factory()->create([
            'email'    => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_unauthenticated_user_cannot_access_client_dashboard(): void
    {
        $response = $this->get('/client/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_client_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }
}