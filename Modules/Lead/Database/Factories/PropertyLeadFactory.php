<?php

namespace Modules\Lead\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Lead\Enums\LeadSource;
use Modules\Lead\Enums\LeadStatus;
use Modules\Lead\Models\PropertyLead;
use Modules\Property\Models\Property;

/** @extends Factory<PropertyLead> */
class PropertyLeadFactory extends Factory
{
    protected $model = PropertyLead::class;

    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'owner_id' => fn (array $attributes) => Property::find($attributes['property_id'])->owner_id,
            'full_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => '+2010' . $this->faker->numerify('########'),
            'message' => $this->faker->sentence(12),
            'source' => LeadSource::Form,
            'status' => LeadStatus::New,
        ];
    }

    public function forProperty(Property $property): static
    {
        return $this->state(['property_id' => $property->id, 'owner_id' => $property->owner_id]);
    }

    public function withStatus(LeadStatus $status): static
    {
        return $this->state(['status' => $status]);
    }
}
