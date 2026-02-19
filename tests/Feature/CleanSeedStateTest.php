<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CleanSeedStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_fresh_seed_starts_with_clean_catalog_data(): void
    {
        $this->seed();

        $this->assertDatabaseHas('admins', [
            'email' => 'frahmat68@gmail.com',
            'role' => 'super_admin',
        ]);

        $this->assertDatabaseCount('categories', 0);
        $this->assertDatabaseCount('equipments', 0);
        $this->assertDatabaseCount('orders', 0);

        $this->assertDatabaseHas('site_settings', ['key' => 'home.hero_title']);
        $this->assertDatabaseHas('site_settings', ['key' => 'footer.about']);
    }
}

