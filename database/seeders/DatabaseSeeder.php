<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\City;
use App\Models\Feature;
use App\Models\Property;
use App\Models\PropertyClassifications;
use App\Models\PropertyType;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

        $this->call([
            LocationSeeder::class,
            PropertyTypeSeeder::class,
            ClassificationSeeder::class,
            FeatureSeeder::class,
            PropertySeeder::class,
            contentSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
