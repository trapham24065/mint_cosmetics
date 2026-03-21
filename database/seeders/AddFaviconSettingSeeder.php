<?php
/**
 * @project mint_cosmetics
 * @author M397
 * @email m397.dev@gmail.com
 * @date 3/11/2026
 * @time 5:47 PM
 */

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class AddFaviconSettingSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'site_favicon'],
            [
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'label' => 'Biểu tượng Favicon',
                'description' => 'Tải lên logo Favicon cho trang web của bạn (PNG, JPG, SVG).',
                'sort_order' => 0,
            ]
        );
    }

}
