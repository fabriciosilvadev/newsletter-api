<?php

namespace Tests;

use App\Models\User;

trait HasAuthenticatedUser
{
    protected User $user;

    public function setupUser(?bool $isAdmin = false): void
    {
        $this->user = User::factory()->create([
            'is_admin' => $isAdmin,
        ]);
        $this->actingAs($this->user);
    }

    public function logout(): void
    {
        $this->user = null;
    }
}
