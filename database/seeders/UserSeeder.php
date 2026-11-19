<?php

namespace Database\Seeders;

use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=User::factory()->create([
            'name' => 'Admin User',
            'email' => 'test@example.com',
        ]);
        $user->assignRole('Super Admin');


    }
}
