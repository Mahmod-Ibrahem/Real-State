<?php

namespace Modules\Property\Database\Seeders;

use Modules\Property\Models\Feature;
use Modules\Property\Models\Property;
use Modules\Property\Services\PropertyPaymentPlanService;
use App\Models\User;
use Database\Seeders\Support\DemoImages;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PropertySeeder extends Seeder
{
    private const PROPERTIES = 20;
    private const MAX_GALLERY_IMAGES = 8;
    private const DEVELOPER_PLANS = [
        ['down_payment_percent' => 0, 'installment_years' => 5, 'installment_frequency' => 'quarterly'],
        ['down_payment_percent' => 10, 'installment_years' => 6, 'installment_frequency' => 'monthly'],
        ['down_payment_percent' => 20, 'installment_years' => 8, 'installment_frequency' => 'quarterly'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(PropertyPaymentPlanService $paymentPlans): void
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
            ->each(function (Property $property) use ($galleryImages, $featureIds, $paymentPlans) {
                $this->addGallery($property, $galleryImages);

                if ($featureIds->isNotEmpty()) {
                    $property->features()->attach(
                        $featureIds->random(min($featureIds->count(), random_int(3, 6)))
                    );
                }

                if (random_int(0, 1) === 1) {
                    $paymentPlans->sync($property, array_slice(self::DEVELOPER_PLANS, 0, random_int(1, count(self::DEVELOPER_PLANS))));
                }
            });

        $this->seedDemoOwnerListings($mainImages, $galleryImages);
    }

    private function seedDemoOwnerListings(array $mainImages, array $galleryImages): void
    {
        $owner = User::firstWhere('email', UserSeeder::DEMO_OWNER_EMAIL);
        if (!$owner) {
            return;
        }

        $listings = Property::factory()->ownedBy($owner)
            ->state(fn () => ['image' => $mainImages ? Arr::random($mainImages) : DemoImages::placeholder()]);

        collect([
            $listings->create(['is_published' => true]),
            $listings->pending()->create(),
            $listings->rejected('The cover photo is blurry. Please upload a clearer one.')->create(),
        ])->each(fn (Property $property) => $this->addGallery($property, $galleryImages));
    }

    private function addGallery(Property $property, array $galleryImages): void
    {
        $count = random_int(Property::MIN_GALLERY_IMAGES, self::MAX_GALLERY_IMAGES);
        $pool = Arr::shuffle($galleryImages ?: [DemoImages::placeholder()]);

        $property->propertyImages()->createMany(
            array_map(fn (int $i) => ['image' => $pool[$i % count($pool)]], range(0, $count - 1))
        );
    }
}
