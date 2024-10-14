<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'is_admin' => true,
        ]);
        User::factory()->create([
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => Hash::make(fake()->password()),
            'is_admin' => false,
        ]);
    }
}
