<?php

namespace Database\Factories;

use App\Models\PropertyClassifications;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyClassificationsFactory extends Factory
{
    protected $model = PropertyClassifications::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
