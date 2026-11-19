<?php

namespace Database\Seeders;

use Modules\Property\Database\Seeders\ClassificationSeeder;
use Modules\Property\Database\Seeders\PropertyTypeSeeder;
use Modules\Location\Database\Seeders\LocationSeeder;
use Modules\Property\Database\Seeders\PropertySeeder;
use Modules\Property\Database\Seeders\FeatureSeeder;
use Modules\Content\Database\Seeders\ContentSeeder;

use Modules\Location\Models\Area;
use Modules\Location\Models\City;
use Modules\Property\Models\Feature;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyClassifications;
use Modules\Property\Models\PropertyType;
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
            ContentSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
