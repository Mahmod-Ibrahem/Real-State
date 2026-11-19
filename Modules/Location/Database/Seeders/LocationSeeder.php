<?php

namespace Modules\Location\Database\Seeders;

use Modules\Location\Models\Country;
use Illuminate\Database\Seeder;

/**
 * Seeds the Country → City → Area tree with real places (English + Arabic names).
 */
class LocationSeeder extends Seeder
{
    private const LOCATIONS = [
        ['en' => 'Egypt', 'ar' => 'مصر', 'cities' => [
            ['en' => 'Cairo', 'ar' => 'القاهرة', 'areas' => [
                ['en' => 'New Cairo', 'ar' => 'القاهرة الجديدة'],
                ['en' => 'Maadi', 'ar' => 'المعادي'],
                ['en' => 'Zamalek', 'ar' => 'الزمالك'],
                ['en' => 'Heliopolis', 'ar' => 'مصر الجديدة'],
                ['en' => 'Nasr City', 'ar' => 'مدينة نصر'],
            ]],
            ['en' => 'Giza', 'ar' => 'الجيزة', 'areas' => [
                ['en' => 'Sheikh Zayed', 'ar' => 'الشيخ زايد'],
                ['en' => '6th of October', 'ar' => 'السادس من أكتوبر'],
                ['en' => 'Dokki', 'ar' => 'الدقي'],
                ['en' => 'Mohandessin', 'ar' => 'المهندسين'],
            ]],
            ['en' => 'Alexandria', 'ar' => 'الإسكندرية', 'areas' => [
                ['en' => 'Smouha', 'ar' => 'سموحة'],
                ['en' => 'Sidi Gaber', 'ar' => 'سيدي جابر'],
                ['en' => 'Stanley', 'ar' => 'ستانلي'],
                ['en' => 'Miami', 'ar' => 'ميامي'],
            ]],
        ]],
        ['en' => 'United Arab Emirates', 'ar' => 'الإمارات العربية المتحدة', 'cities' => [
            ['en' => 'Dubai', 'ar' => 'دبي', 'areas' => [
                ['en' => 'Dubai Marina', 'ar' => 'دبي مارينا'],
                ['en' => 'Downtown Dubai', 'ar' => 'وسط مدينة دبي'],
                ['en' => 'Jumeirah', 'ar' => 'جميرا'],
            ]],
            ['en' => 'Abu Dhabi', 'ar' => 'أبوظبي', 'areas' => [
                ['en' => 'Al Reem Island', 'ar' => 'جزيرة الريم'],
                ['en' => 'Khalifa City', 'ar' => 'مدينة خليفة'],
            ]],
        ]],
    ];

    public function run(): void
    {
        foreach (self::LOCATIONS as $countryData) {
            $country = Country::create(['name' => self::names($countryData)]);

            foreach ($countryData['cities'] as $cityData) {
                $city = $country->cities()->create(['name' => self::names($cityData)]);

                foreach ($cityData['areas'] as $areaData) {
                    $city->areas()->create(['name' => self::names($areaData)]);
                }
            }
        }
    }

    private static function names(array $data): array
    {
        return ['en' => $data['en'], 'ar' => $data['ar']];
    }
}
