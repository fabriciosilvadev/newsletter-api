<?php

namespace App\Facades;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthFacade
{
    private int $accessTokenTTL;

    public function __construct()
    {
        $this->accessTokenTTL = Auth::factory()->getTTL();
    }

    public function getAccessToken(array $credentials): ?array
    {
        $token = Auth::attempt($credentials);
        if (! $token) {
            return null;
        }

        return $this->buildAccessTokenResponseObject($token);
    }

    public function getAccessTokenExpirationDate(): Carbon
    {
        return now()->addMinutes($this->accessTokenTTL);
    }

    public function register(array $data): User
    {
        $data['password'] = $this->hashPassword($data['password']);

        return User::create($data);
    }

    public function logout(): void
    {
        $token = Auth::getToken();
        Auth::invalidate($token);
        Auth::logout();
    }

    public function refresh(): array
    {
        $token = Auth::refresh();

        return $this->buildAccessTokenResponseObject($token);
    }

    public function hashPassword(string $password): string
    {
        return Hash::make($password);
    }

    public function buildAccessTokenResponseObject(string $accessToken): array
    {
        $expiresAt = $this->getAccessTokenExpirationDate();

        return [
            'access_token' => $accessToken,
            'token_type' => 'bearer',
            'expires_at' => $expiresAt->toISOString(),
        ];
    }
}
