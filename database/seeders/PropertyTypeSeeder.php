<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Database\Seeders\Support\DemoImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Property type name => Pexels search query.
     */
    private const TYPES = [
        'House' => 'house exterior',
        'Apartment' => 'apartment building',
        'Villa' => 'luxury villa',
        'Office' => 'modern office',
        'Studio' => 'studio apartment',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $log = fn (string $level, string $message) => $this->command?->{$level}($message);

        foreach (self::TYPES as $name => $query) {
            $images = DemoImages::pool($query, 1, 'landscape', $log);

            PropertyType::create([
                'slug' => Str::slug($name),
                'name' => $name,
                'image' => $images[0] ?? DemoImages::placeholder(),
            ]);
        }
    }
}
