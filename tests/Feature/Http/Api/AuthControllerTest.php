<?php

namespace Tests\Feature\Http\Api;

use App\Models\User;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_register(): void
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => 'John Doe',
            'email' => 'john@email.com',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data',
            'message',
        ]);
    }

    public function test_login_invalid_credentials(): void
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => 'email@email.com',
            'password' => 'invalid-password',
        ]);

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            'message',
            'error',
        ]);
    }

    public function test_login_success(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'access_token',
                'token_type',
                'expires_at',
            ],
            'message',
        ]);
    }

    public function test_refresh(): void
    {
        $user = User::factory()->create();
        $loginResponse = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $accessToken = $loginResponse->json('data.access_token');

        $response = $this->post(route('auth.refresh'), [
            'Authorization' => "Bearer $accessToken",
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'access_token',
                'token_type',
                'expires_at',
            ],
            'message',
        ]);
    }

    public function test_logout(): void
    {
        $user = User::factory()->create();
        $loginResponse = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $accessToken = $loginResponse->json('data.access_token');

        $response = $this->post(route('auth.logout'), [
            'Authorization' => "Bearer $accessToken",
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data',
            'message',
        ]);
    }
}
