<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDbExplorerTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(string $role = 'super_admin'): Admin
    {
        return Admin::create([
            'name' => ucfirst($role) . ' User',
            'email' => $role . '@example.com',
            'password' => Hash::make('password'),
            'role' => $role,
            'email_verified_at' => now(),
        ]);
    }

    public function test_super_admin_can_open_db_explorer_pages(): void
    {
        $admin = $this->createAdmin('super_admin');

        $category = Category::create([
            'name' => 'DB Explorer Category',
            'slug' => 'db-explorer-category',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.db.index'))
            ->assertOk()
            ->assertSee('categories');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.db.table', 'categories'))
            ->assertOk()
            ->assertSee((string) $category->id)
            ->assertSee($category->name);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.db.table', ['table' => 'categories', 'column' => 'id', 'q' => (string) $category->id]))
            ->assertOk()
            ->assertSee($category->name);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.db.show', ['table' => 'categories', 'record' => $category->id]))
            ->assertOk()
            ->assertSee($category->name);
    }

    public function test_non_super_admin_cannot_access_db_explorer(): void
    {
        $admin = $this->createAdmin('admin');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.db.index'))
            ->assertForbidden();
    }
}
