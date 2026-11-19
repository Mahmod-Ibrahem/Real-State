<?php

namespace Modules\Property\Database\Seeders;

use Modules\Property\Models\PropertyClassifications;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classifications = ['Affordable', 'Luxury', 'Moderate'];
        foreach ($classifications as $classification) {
            PropertyClassifications::factory()->create([
                'name' => $classification,
            ]);
        }
    }
}
