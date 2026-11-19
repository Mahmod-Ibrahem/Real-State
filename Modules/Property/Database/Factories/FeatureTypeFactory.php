<?php

namespace Modules\Property\Database\Factories;

use Modules\Property\Models\FeatureType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Property\Models\FeatureType>
 */
class FeatureTypeFactory extends Factory
{
    protected $model = FeatureType::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->word()),
        ];
    }
}
