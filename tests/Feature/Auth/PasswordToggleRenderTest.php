<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordToggleRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_toggle_rendered_on_login_and_register_pages(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('data-password-toggle', false);

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('data-password-toggle', false);
    }

    public function test_password_toggle_rendered_on_admin_login_page(): void
    {
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('data-password-toggle', false);
    }

    public function test_password_toggle_rendered_on_password_reset_and_confirm_pages(): void
    {
        $this->get(route('password.reset', ['token' => 'dummy-token', 'email' => 'user@example.com']))
            ->assertOk()
            ->assertSee('data-password-toggle', false);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('password.confirm'))
            ->assertOk()
            ->assertSee('data-password-toggle', false);
    }
}
