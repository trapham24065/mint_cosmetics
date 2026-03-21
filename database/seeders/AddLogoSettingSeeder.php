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
                'label' => 'Logo trang web',
                'description' => 'Tải lên logo cho trang web của bạn (PNG, JPG, SVG).',
                'sort_order' => 0,
            ]
        );
    }

}
