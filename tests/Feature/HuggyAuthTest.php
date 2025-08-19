<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

use Tests\TestCase;

class HuggyAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_huggy_creates_user_and_returns_token()
    {
        Http::fake([
            'https://auth.huggy.app/oauth/access_token' => Http::response([
                'access_token' => 'fake-huggy-token',
                'token_type' => 'Bearer',
                'expires_in' => 2592000,
                'refresh_token' => 'fake-refresh-token',
            ], 200),
        ]);

        $response = $this->get('/oauth/huggy/callback?code=fake-code');

        $response->assertStatus(200);
        $response->assertSee('fake-huggy-token');
        $response->assertViewIs('auth.huggy-popup-close');
    }
}