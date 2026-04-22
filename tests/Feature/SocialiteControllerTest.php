<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialiteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_redirect_returns_to_login_with_clear_message_when_oauth_config_is_missing(): void
    {
        config([
            'services.google.client_id' => '',
            'services.google.client_secret' => '',
            'services.google.redirect' => '',
        ]);

        $response = $this->get(route('social.redirect', 'google'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Login Google belum aktif karena konfigurasi OAuth production belum lengkap.',
        ]);
    }
}
