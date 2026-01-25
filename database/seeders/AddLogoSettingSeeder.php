<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddLogoSettingSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'site_logo'],
            [
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'label' => 'Website Logo',
                'description' => 'Upload logo for your website (PNG, JPG, SVG).',
                'sort_order' => 0,
            ]
        );
    }

}
