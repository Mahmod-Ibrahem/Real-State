<?php

namespace Modules\Location\Database\Factories;

use Modules\Location\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Location\Models\Area>
 */
class AreaFactory extends Factory
{
    protected $model = Area::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->streetName(),
            'city_id' => \Modules\Location\Models\City::factory(),
        ];
    }
}
