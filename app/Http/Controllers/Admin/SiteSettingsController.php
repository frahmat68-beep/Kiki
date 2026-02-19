<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    public function edit()
    {
        $keys = $this->keys();
        $settings = SiteSetting::whereIn('key', $keys)->pluck('value', 'key')->toArray();

        return view('admin.settings.edit', [
            'settings' => $settings,
            'activePage' => 'settings',
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['nullable', 'string', 'max:150'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:500'],
            'hero_cta_text' => ['nullable', 'string', 'max:80'],
            'footer_description' => ['nullable', 'string', 'max:800'],
            'footer_address' => ['nullable', 'string', 'max:500'],
            'footer_email' => ['nullable', 'email', 'max:150'],
            'footer_phone' => ['nullable', 'string', 'max:50'],
            'footer_copyright' => ['nullable', 'string', 'max:150'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_whatsapp' => ['nullable', 'string', 'max:255'],
            'social_youtube' => ['nullable', 'string', 'max:255'],
            'social_tiktok' => ['nullable', 'string', 'max:255'],
            'contact_map_embed' => ['nullable', 'string', 'max:5000'],
            'contact_form_receiver_email' => ['nullable', 'email', 'max:150'],
        ]);

        foreach ($this->keys() as $key) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data[$key] ?? null,
                    'type' => in_array($key, ['meta_description', 'hero_subtitle', 'footer_description', 'footer_address', 'contact_map_embed'], true) ? 'textarea' : 'text',
                    'group' => $this->groupForKey($key),
                    'updated_by_admin_id' => auth('admin')->id(),
                ]
            );

            site_setting_forget($key);
        }

        admin_audit('site_settings.update', 'site_settings', null, [
            'keys' => $this->keys(),
        ], auth('admin')->id());

        return back()->with('success', __('ui.admin.settings_saved'));
    }

    private function keys(): array
    {
        return [
            'site_name',
            'site_tagline',
            'meta_title',
            'meta_description',
            'hero_title',
            'hero_subtitle',
            'hero_cta_text',
            'footer_description',
            'footer_address',
            'footer_email',
            'footer_phone',
            'footer_copyright',
            'social_instagram',
            'social_whatsapp',
            'social_youtube',
            'social_tiktok',
            'contact_map_embed',
            'contact_form_receiver_email',
        ];
    }

    private function groupForKey(string $key): string
    {
        return match ($key) {
            'site_name', 'site_tagline' => 'brand',
            'meta_title', 'meta_description' => 'seo',
            'hero_title', 'hero_subtitle', 'hero_cta_text' => 'home',
            'footer_description', 'footer_address', 'footer_email', 'footer_phone', 'footer_copyright' => 'footer',
            'social_instagram', 'social_whatsapp', 'social_youtube', 'social_tiktok' => 'social',
            default => 'contact',
        };
    }
}
