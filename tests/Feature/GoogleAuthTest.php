<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_creates_user_and_returns_token()
    {
        $googleUser = Mockery::mock(SocialiteUser::class);
        $googleUser->shouldReceive('getName')->andReturn('João Teste');
        $googleUser->shouldReceive('getEmail')->andReturn('joao@email.com');

        Socialite::shouldReceive('driver->stateless->user')->andReturn($googleUser);

        $response = $this->get('/api/auth/google/callback');

        $response->assertStatus(200);
        $response->assertSee('token');

        $this->assertDatabaseHas('users', [
            'email' => 'joao@email.com',
            'name' => 'João Teste'
        ]);
    }
}