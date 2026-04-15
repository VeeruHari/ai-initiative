<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@reflex.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'email_verified_at' => now(),
                'role' => 'admin',
                'tenant_id' => null,
                'remember_token' => Str::random(10),
            ]
        );
    }
}
