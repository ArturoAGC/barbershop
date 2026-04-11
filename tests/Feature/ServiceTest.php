<?php
namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function client(): User
    {
        return User::factory()->create(['role' => 'client']);
    }

    public function test_admin_can_view_services(): void
    {
        $response = $this->actingAs($this->admin())->get('/admin/services');
        $response->assertStatus(200);
    }

    public function test_client_cannot_view_admin_services(): void
    {
        $response = $this->actingAs($this->client())->get('/admin/services');
        $response->assertStatus(403);
    }

    public function test_admin_can_create_service(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/services', [
            'name'             => 'Corte de prueba',
            'description'      => 'Descripcion de prueba',
            'price'            => 100,
            'duration_minutes' => 30,
        ]);

        $response->assertRedirect('/admin/services');
        $this->assertDatabaseHas('services', [
            'name'  => 'Corte de prueba',
            'price' => 100,
        ]);
    }

    public function test_admin_can_update_service(): void
    {
        $service = Service::factory()->create();

        $response = $this->actingAs($this->admin())->put("/admin/services/{$service->id}", [
            'name'             => 'Nombre actualizado',
            'price'            => 150,
            'duration_minutes' => 45,
        ]);

        $response->assertRedirect('/admin/services');
        $this->assertDatabaseHas('services', [
            'id'    => $service->id,
            'name'  => 'Nombre actualizado',
            'price' => 150,
        ]);
    }

    public function test_admin_can_delete_service(): void
    {
        $service = Service::factory()->create();

        $response = $this->actingAs($this->admin())->delete("/admin/services/{$service->id}");

        $response->assertRedirect('/admin/services');
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_service_name_is_required(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/services', [
            'name'             => '',
            'price'            => 100,
            'duration_minutes' => 30,
        ]);

        $response->assertSessionHasErrors('name');
    }
}