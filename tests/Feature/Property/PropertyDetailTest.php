<?php

namespace Tests\Feature\Property;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Property\Models\Property;
use Tests\TestCase;

class PropertyDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_a_listed_property_opens_with_only_listed_related_properties(): void
    {
        $property = Property::factory()->create(['is_published' => true]);
        $related = Property::factory()->create(['is_published' => true, 'property_type_id' => $property->property_type_id]);
        Property::factory()->create(['is_published' => false, 'property_type_id' => $property->property_type_id]);

        $this->get(route('listing.show', $property->slug))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Users/PropertyDetails')
                ->where('property.id', $property->id)
                ->has('relatedProperties.data', 1)
                ->where('relatedProperties.data.0.id', $related->id));
    }

    public function test_properties_that_are_not_listed_are_not_found(): void
    {
        $unpublished = Property::factory()->create(['is_published' => false]);
        $pending = Property::factory()->pending()->create();
        $ownerUnderReview = Property::factory()->ownedBy(User::factory()->underReview()->create())
            ->create(['is_published' => true]);

        foreach ([$unpublished, $pending, $ownerUnderReview] as $property) {
            $this->get(route('listing.show', $property->slug))->assertNotFound();
        }
    }
}
