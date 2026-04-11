<?php
namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_is_running(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}