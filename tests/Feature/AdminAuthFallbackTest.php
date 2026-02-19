<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_can_fallback_from_users_table_and_sync_admin_record(): void
    {
        User::factory()->create([
            'email' => 'role-admin@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('admin.login.store'), [
            'email' => 'role-admin@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated('admin');
        $this->assertDatabaseHas('admins', [
            'email' => 'role-admin@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_with_unverified_email_is_auto_verified_when_logging_in(): void
    {
        $admin = Admin::create([
            'name' => 'Admin Legacy',
            'email' => 'legacy-admin@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
            'email_verified_at' => null,
        ]);

        $response = $this->post(route('admin.login.store'), [
            'email' => 'legacy-admin@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated('admin');
        $this->assertNotNull($admin->fresh()->email_verified_at);
    }
}
