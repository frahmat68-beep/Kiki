<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestAccountFeatureAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_when_opening_cart(): void
    {
        $response = $this->get(route('cart'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Login dulu untuk akses fitur ini.');
    }
}
