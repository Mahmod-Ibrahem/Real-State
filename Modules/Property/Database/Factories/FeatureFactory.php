<?php

namespace Modules\Property\Database\Factories;

use Modules\Property\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Property\Models\Feature>
 */
class FeatureFactory extends Factory
{
    protected $model = Feature::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'feature_type_id' => \Modules\Property\Models\FeatureType::factory(),
            'name' => $this->faker->unique()->word(),
        ];
    }
}
