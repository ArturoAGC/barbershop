<?php
namespace Tests\Unit;

use App\Models\Service;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public function test_service_has_correct_fillable_fields(): void
    {
        $service = new Service();
        $fillable = $service->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('price', $fillable);
        $this->assertContains('duration_minutes', $fillable);
        $this->assertContains('is_active', $fillable);
    }

    public function test_service_instance_can_be_created(): void
    {
        $service = new Service([
            'name'             => 'Corte clásico',
            'price'            => 80,
            'duration_minutes' => 30,
            'is_active'        => true,
        ]);

        $this->assertEquals('Corte clásico', $service->name);
        $this->assertEquals(80, $service->price);
        $this->assertEquals(30, $service->duration_minutes);
    }
}