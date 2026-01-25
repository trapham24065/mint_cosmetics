<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{

    public function index(): View
    {
        $settings = Setting::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('group');

        return view('admin.management.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'contact_email' => 'nullable|email|max:255',
            'site_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        if ($request->hasFile('site_logo')) {
            $logoSetting = Setting::where('key', 'site_logo')->first();

            if ($logoSetting && $logoSetting->value && Storage::disk('public')->exists($logoSetting->value)) {
                Storage::disk('public')->delete($logoSetting->value);
            }

            $path = $request->file('site_logo')->store('settings', 'public');

            Setting::updateOrCreate(
                ['key' => 'site_logo'],
                [
                    'value' => $path,
                    'group' => 'general',
                    'type'  => 'image',
                    'label' => 'Website Logo',
                ]
            );
        }

        $data = $request->except(['_token', 'site_logo']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('all_settings');

        return back()->with('success', 'Settings updated successfully.');
    }

}
