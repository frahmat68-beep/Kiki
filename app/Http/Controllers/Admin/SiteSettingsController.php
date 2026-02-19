<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    public function edit()
    {
        $keys = array_merge($this->keys(), array_keys($this->typographyMap()));
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
            'typography_heading_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'typography_subheading_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'typography_body_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'typography_heading_weight' => ['nullable', 'in:600,700,800,900'],
            'typography_body_weight' => ['nullable', 'in:400,500,600'],
            'typography_heading_style' => ['nullable', 'in:normal,italic'],
            'typography_body_style' => ['nullable', 'in:normal,italic'],
            'typography_heading_scale' => ['nullable', 'in:sm,md,lg'],
            'typography_body_scale' => ['nullable', 'in:sm,md,lg'],
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

        foreach ($this->typographyMap() as $settingKey => $requestKey) {
            SiteSetting::updateOrCreate(
                ['key' => $settingKey],
                [
                    'value' => $data[$requestKey] ?? null,
                    'type' => 'text',
                    'group' => 'typography',
                    'updated_by_admin_id' => auth('admin')->id(),
                ]
            );

            site_setting_forget($settingKey);
        }

        admin_audit('site_settings.update', 'site_settings', null, [
            'keys' => array_merge($this->keys(), array_keys($this->typographyMap())),
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

    private function typographyMap(): array
    {
        return [
            'typography.heading_color' => 'typography_heading_color',
            'typography.subheading_color' => 'typography_subheading_color',
            'typography.body_color' => 'typography_body_color',
            'typography.heading_weight' => 'typography_heading_weight',
            'typography.body_weight' => 'typography_body_weight',
            'typography.heading_style' => 'typography_heading_style',
            'typography.body_style' => 'typography_body_style',
            'typography.heading_scale' => 'typography_heading_scale',
            'typography.body_scale' => 'typography_body_scale',
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
