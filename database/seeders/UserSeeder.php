<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\OwnerRegistrationService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public const ADMIN_EMAIL = 'test@example.com';
    public const DEMO_OWNER_EMAIL = 'owner@example.com';

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => self::ADMIN_EMAIL,
        ])->assignRole(PermissionSeeder::SUPER_ADMIN);

        User::factory()->create([
            'name' => 'Demo Owner',
            'email' => self::DEMO_OWNER_EMAIL,
        ])->assignRole(OwnerRegistrationService::ROLE);
    }
}
