<?php

/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 9/9/2025
 * @time 9:52 PM
 */

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get / set the specified setting value.
     *
     * @param  string  $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        $settings = Cache::rememberForever('all_settings', function () {
            return Setting::all()->pluck('value', 'key')->all();
        });

        return $settings[$key] ?? $default;
    }
}
