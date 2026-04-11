<?php
namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_is_admin_returns_true_when_role_is_admin(): void
    {
        $user = new User();
        $user->role = 'admin';
        $this->assertTrue($user->isAdmin());
    }

    public function test_is_admin_returns_false_when_role_is_client(): void
    {
        $user = new User();
        $user->role = 'client';
        $this->assertFalse($user->isAdmin());
    }
}