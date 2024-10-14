<?php

namespace Tests\Feature\Facades;

use App\Facades\AuthFacade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthFacadeTest extends TestCase
{
    protected function makeFacade()
    {
        return new AuthFacade;
    }

    public function test_get_access_token_successfully(): void
    {
        $facade = $this->makeFacade();
        $user = User::factory()->create();

        $token = $facade->getAccessToken([
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertNotNull($token);
    }

    public function test_get_access_token_failure(): void
    {
        $facade = $this->makeFacade();
        $user = User::factory()->create();

        $token = $facade->getAccessToken([
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertNull($token);
    }

    public function test_get_access_token_expiration_date(): void
    {
        Auth::factory()->setTTL(10);
        Carbon::setTestNow('2024-10-12 00:00:00');
        $facade = $this->makeFacade();

        $expirationDate = $facade->getAccessTokenExpirationDate();

        $this->assertNotNull($expirationDate);
        $this->assertEquals('2024-10-12 00:10:00', $expirationDate->format('Y-m-d H:i:s'));
    }

    public function test_register_user(): void
    {
        $facade = $this->makeFacade();
        $data = [
            'name' => 'John Doe',
            'email' => 'john@email.com',
            'password' => 'password',
        ];

        $user = $facade->register($data);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        $this->assertTrue($user->is($user));
        $this->assertTrue(password_verify($data['password'], $user->password));
    }

    public function test_logout_user(): void
    {
        $facade = $this->makeFacade();
        $user = User::factory()->create();
        Auth::login($user);

        $facade->logout();

        $this->assertNull(Auth::user());
    }

    public function test_refresh_access_token(): void
    {
        $facade = $this->makeFacade();
        $user = User::factory()->create();
        Auth::login($user);

        $token = $facade->refresh();

        $this->assertNotNull($token);
    }

    public function test_hash_password(): void
    {
        $facade = $this->makeFacade();
        $password = 'password';

        $hashedPassword = $facade->hashPassword($password);

        $this->assertTrue(password_verify($password, $hashedPassword));
    }

    public function test_build_access_token_response_object(): void
    {
        Auth::factory()->setTTL(10);
        Carbon::setTestNow('2024-10-12 00:00:00');
        $facade = $this->makeFacade();
        $accessToken = fake()->word();

        $response = $facade->buildAccessTokenResponseObject($accessToken);

        $this->assertEquals($accessToken, $response['access_token']);
        $this->assertEquals('bearer', $response['token_type']);
        $this->assertEquals('2024-10-12 00:10:00', Carbon::parse($response['expires_at'])->format('Y-m-d H:i:s'));
    }
}
