<?php

namespace Modules\Content\Database\Seeders;

use Modules\Content\Models\Broker;
use Database\Seeders\Support\DemoImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrokerSeeder extends Seeder
{
    private const JOB_TITLES = [
        'Senior Real Estate Agent',
        'Property Consultant',
        'Sales Manager',
        'Leasing Specialist',
        'Investment Advisor',
        'Real Estate Broker',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $log = fn (string $level, string $message) => $this->command?->{$level}($message);
        $portraits = DemoImages::pool('professional business portrait', count(self::JOB_TITLES), 'portrait', $log);

        foreach (self::JOB_TITLES as $index => $jobTitle) {
            $name = fake()->name();
            $handle = Str::slug($name, '');

            Broker::create([
                'name' => $name,
                'job_title' => $jobTitle,
                'image' => $portraits[$index] ?? DemoImages::placeholder(),
                'facebook' => "https://facebook.com/{$handle}",
                'twitter' => "https://twitter.com/{$handle}",
                'instagram' => "https://instagram.com/{$handle}",
            ]);
        }
    }
}
