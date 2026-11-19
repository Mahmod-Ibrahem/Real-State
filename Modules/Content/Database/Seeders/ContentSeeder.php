<?php

namespace Modules\Content\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contentsType = [
            [
                'name' => 'Home Video',
                'type' => 'home_video',
                'content' => 'https://www.youtube.com/watch?v=5Tt9mYh5KbM'
            ],
            [
                'name' => 'About Video',
                'type' => 'about_video',
                'content' => 'https://www.youtube.com/watch?v=5Tt9mYh5KbM'
            ]
        ];

        foreach ($contentsType as $content) {
            \Modules\Content\Models\BasicContent::create($content);
        }
    }
}
