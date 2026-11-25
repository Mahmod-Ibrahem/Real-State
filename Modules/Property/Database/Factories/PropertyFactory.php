<?php

namespace Modules\Property\Database\Factories;

use Modules\Property\Models\Property;
use Modules\Property\Enums\ListingPurpose;
use Modules\Property\Enums\PropertyStatus;
use Modules\Property\Enums\ReviewStatus;
use App\Models\User;
use Modules\Location\Models\Area;
use Modules\Property\Models\PropertyClassifications;
use Modules\Property\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Property\Models\Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pick one area and take its city and country from it, so the location is always consistent.
        $area = Area::with('city')->inRandomOrder()->first() ?? Area::factory()->create()->load('city');

        $bedrooms = $this->faker->numberBetween(1, 6);
        $purpose = $this->faker->randomElement(ListingPurpose::cases());
        $name = sprintf(
            '%s %d-Bedroom %s',
            $this->faker->randomElement(['Modern', 'Elegant', 'Spacious', 'Cozy', 'Luxury', 'Sunny', 'Charming', 'Contemporary']),
            $bedrooms,
            $this->faker->randomElement(['House', 'Apartment', 'Villa', 'Townhouse', 'Penthouse', 'Duplex'])
        );

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::lower(Str::random(5)),
            'description' => implode("\n\n", $this->faker->paragraphs(3)),
            'address' => $this->faker->streetAddress(),
            'image' => asset('assets/images/property/1.jpg'),
            'price' => $this->faker->numberBetween(50, 2000) * 1000,
            'bedrooms' => $bedrooms,
            'bathrooms' => $this->faker->numberBetween(1, max(1, $bedrooms - 1)),
            'garages' => $this->faker->numberBetween(0, 3),
            'year_built' => $this->faker->numberBetween(1990, (int) date('Y')),
            'sqft' => $this->faker->numberBetween(600, 5000),
            'floor' => (string) $this->faker->numberBetween(0, 20),
            'google_map' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3455.284334901057!2d31.18099511981128!3d29.999991010346022!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x145846765e2334ff%3A0x29022126e13689c5!2z2KfZhNmH2LHZhdiMINmF2K3Yp9mB2LjYqSDYp9mE2KzZitiy2Kk!5e0!3m2!1sar!2seg!4v1744709563856!5m2!1sar!2seg" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'is_published' => $this->faker->boolean(80),

            // Get real existing foreign key values or create fallback
            'property_type_id' => PropertyType::inRandomOrder()->value('id') ?? PropertyType::factory()->create()->id,
            'property_classification_id' => PropertyClassifications::inRandomOrder()->value('id') ?? PropertyClassifications::factory()->create()->id,
            'area_id' => $area->id,
            'city_id' => $area->city_id,
            'country_id' => $area->city->country_id,
            'purpose' => $purpose,
            // ~25% of demo listings are already closed, so the Sold/Rented ribbons show up.
            'status' => $this->faker->boolean(75) ? PropertyStatus::Available : PropertyStatus::closedFor($purpose),
            'owner_id' => fn () => User::query()->orderBy('id')->value('id') ?? User::factory()->create()->id,
            'review_status' => ReviewStatus::Approved,
        ];
    }

    public function ownedBy(User $owner): static
    {
        return $this->state(['owner_id' => $owner->id]);
    }

    public function withGallery(int $photos = Property::MIN_GALLERY_IMAGES): static
    {
        return $this->afterCreating(fn (Property $property) => $property->propertyImages()->createMany(
            array_fill(0, $photos, ['image' => asset('assets/images/property/1.jpg')])
        ));
    }

    public function pending(): static
    {
        return $this->state(['review_status' => ReviewStatus::Pending, 'is_published' => false]);
    }

    public function rejected(string $reason): static
    {
        return $this->state([
            'review_status' => ReviewStatus::Rejected,
            'rejection_reason' => $reason,
            'is_published' => false,
        ]);
    }
}
