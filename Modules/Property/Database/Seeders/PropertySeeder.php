<?php

namespace Modules\Property\Database\Seeders;

use Modules\Property\Models\Feature;
use Modules\Property\Models\Property;
use App\Models\User;
use Database\Seeders\Support\DemoImages;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PropertySeeder extends Seeder
{
    private const PROPERTIES = 20;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $log = fn (string $level, string $message) => $this->command?->{$level}($message);

        $mainImages = DemoImages::pool('modern house exterior', 30, 'landscape', $log);
        $galleryImages = DemoImages::pool('luxury home interior', 40, 'landscape', $log);
        $featureIds = Feature::pluck('id');

        Property::factory()
            ->count(self::PROPERTIES)
            ->ownedBy(User::firstWhere('email', UserSeeder::ADMIN_EMAIL))
            ->state(fn () => ['image' => $mainImages ? Arr::random($mainImages) : DemoImages::placeholder()])
            ->create()
            ->each(function (Property $property) use ($galleryImages, $featureIds) {
                if ($galleryImages) {
                    $gallery = Arr::random($galleryImages, min(count($galleryImages), random_int(3, 5)));
                    $property->propertyImages()->createMany(
                        array_map(fn (string $image) => ['image' => $image], (array) $gallery)
                    );
                }

                if ($featureIds->isNotEmpty()) {
                    $property->features()->attach(
                        $featureIds->random(min($featureIds->count(), random_int(3, 6)))
                    );
                }
            });

        $this->seedDemoOwnerListings($mainImages);
    }

    private function seedDemoOwnerListings(array $mainImages): void
    {
        $owner = User::firstWhere('email', UserSeeder::DEMO_OWNER_EMAIL);
        if (!$owner) {
            return;
        }

        $listings = Property::factory()->ownedBy($owner)
            ->state(fn () => ['image' => $mainImages ? Arr::random($mainImages) : DemoImages::placeholder()]);

        $listings->create(['is_published' => true]);
        $listings->pending()->create();
        $listings->rejected('The cover photo is blurry. Please upload a clearer one.')->create();
    }
}
