<?php

namespace Database\Seeders;

use App\Models\FeatureType;
use Illuminate\Database\Seeder;

/**
 * Seeds Feature type → Feature with real amenities (English + Arabic names).
 * The property page lists a property's features grouped under these types.
 */
class FeatureSeeder extends Seeder
{
    private const FEATURE_TYPES = [
        ['en' => 'Interior', 'ar' => 'المساحات الداخلية', 'features' => [
            ['en' => 'Air conditioning', 'ar' => 'تكييف هواء'],
            ['en' => 'Central heating', 'ar' => 'تدفئة مركزية'],
            ['en' => 'Built-in wardrobes', 'ar' => 'خزائن حائط'],
            ['en' => 'Fitted kitchen', 'ar' => 'مطبخ مجهز'],
            ['en' => 'Maid\'s room', 'ar' => 'غرفة خادمة'],
        ]],
        ['en' => 'Outdoor', 'ar' => 'المساحات الخارجية', 'features' => [
            ['en' => 'Private garden', 'ar' => 'حديقة خاصة'],
            ['en' => 'Swimming pool', 'ar' => 'حمام سباحة'],
            ['en' => 'Balcony', 'ar' => 'شرفة'],
            ['en' => 'Roof terrace', 'ar' => 'تراس على السطح'],
            ['en' => 'Sea view', 'ar' => 'إطلالة على البحر'],
        ]],
        ['en' => 'Building', 'ar' => 'المبنى', 'features' => [
            ['en' => 'Elevator', 'ar' => 'مصعد'],
            ['en' => 'Covered parking', 'ar' => 'موقف سيارات مغطى'],
            ['en' => 'Gym', 'ar' => 'صالة رياضية'],
            ['en' => 'Backup generator', 'ar' => 'مولد كهرباء احتياطي'],
        ]],
        ['en' => 'Security', 'ar' => 'الأمان', 'features' => [
            ['en' => '24/7 security', 'ar' => 'أمن على مدار الساعة'],
            ['en' => 'CCTV', 'ar' => 'كاميرات مراقبة'],
            ['en' => 'Gated community', 'ar' => 'مجمع مسوّر'],
            ['en' => 'Intercom', 'ar' => 'إنتركم'],
        ]],
    ];

    public function run(): void
    {
        foreach (self::FEATURE_TYPES as $typeData) {
            $featureType = FeatureType::create(['name' => self::names($typeData)]);

            foreach ($typeData['features'] as $featureData) {
                $featureType->features()->create(['name' => self::names($featureData)]);
            }
        }
    }

    private static function names(array $data): array
    {
        return ['en' => $data['en'], 'ar' => $data['ar']];
    }
}
