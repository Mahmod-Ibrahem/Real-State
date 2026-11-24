<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Location\Database\Seeders\LocationSeeder;
use Modules\Property\Database\Seeders\ClassificationSeeder;
use Modules\Property\Database\Seeders\FeatureSeeder;
use Modules\Property\Database\Seeders\PropertySeeder;
use Modules\Property\Database\Seeders\PropertyTypeSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            LocationSeeder::class,
            PropertyTypeSeeder::class,
            ClassificationSeeder::class,
            FeatureSeeder::class,
            PropertySeeder::class,
        ]);
    }
}
