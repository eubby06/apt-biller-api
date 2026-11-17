<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class AdminAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_admin_account_is_created_with_a_hashed_password(): void
    {
        $user = User::where('email', 'admin@gmail.com')->firstOrFail();

        $this->assertSame('admin', $user->name);
        $this->assertTrue(Hash::check(config('bootstrap_admin.password'), $user->password));
        $this->assertNotSame(config('bootstrap_admin.password'), $user->password);
    }

    public function test_the_admin_can_log_in_using_the_email(): void
    {
        $this->postJson('/api/login', [
            'email' => 'admin@gmail.com',
            'password' => config('bootstrap_admin.password'),
        ])->assertOk()
            ->assertJsonPath('user.email', 'admin@gmail.com')
            ->assertJsonMissingPath('user.password')
            ->assertJsonStructure(['token']);
    }

    public function test_invalid_password_is_rejected(): void
    {
        $this->postJson('/api/login', [
            'email' => 'admin@gmail.com',
            'password' => 'incorrect',
        ])->assertUnauthorized();
    }

    public function test_the_migration_fails_if_the_initial_password_is_missing(): void
    {
        config(['bootstrap_admin.password' => null]);
        $migration = require database_path('migrations/2026_11_13_071431_insert_admin_user.php');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Set BOOTSTRAP_ADMIN_PASSWORD before running the admin user migration.');

        $migration->up();
    }
}
