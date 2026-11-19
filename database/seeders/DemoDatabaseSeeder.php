<?php

namespace Database\Seeders;

use Modules\Property\Database\Seeders\ClassificationSeeder;
use Modules\Property\Database\Seeders\PropertyTypeSeeder;
use Modules\Location\Database\Seeders\LocationSeeder;
use Modules\Property\Database\Seeders\PropertySeeder;
use Modules\Property\Database\Seeders\FeatureSeeder;
use Modules\Content\Database\Seeders\ContentSeeder;
use Modules\Content\Database\Seeders\BrokerSeeder;
use Modules\Content\Database\Seeders\FaqSeeder;

use Illuminate\Database\Seeder;

/**
 * Full demo dataset with real Pexels photos.
 *
 * php artisan migrate:fresh --seed --seeder=DemoDatabaseSeeder
 *
 * Images are downloaded once into storage/app/demo-images and reused afterwards
 * (delete that folder to fetch new ones). Requires PEXELS_API_KEY in .env for the first run.
 */
class DemoDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data.
     */
    public function run(): void
    {
        $this->call([
            LocationSeeder::class,
            PropertyTypeSeeder::class,
            ClassificationSeeder::class,
            FeatureSeeder::class,
            PropertySeeder::class,
            BrokerSeeder::class,
            ContentSeeder::class,
            FaqSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
