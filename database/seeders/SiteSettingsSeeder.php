<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'site_name', 'group' => 'brand', 'type' => 'text'],
            ['key' => 'site_tagline', 'group' => 'brand', 'type' => 'text'],
            ['key' => 'meta_title', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'meta_description', 'group' => 'seo', 'type' => 'textarea'],
            ['key' => 'brand.name', 'group' => 'branding', 'type' => 'text'],
            ['key' => 'brand.tagline', 'group' => 'branding', 'type' => 'text'],
            ['key' => 'brand.logo_path', 'group' => 'branding', 'type' => 'image'],
            ['key' => 'brand.favicon_path', 'group' => 'branding', 'type' => 'image'],
            ['key' => 'seo.meta_title', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'seo.meta_description', 'group' => 'seo', 'type' => 'textarea'],
            ['key' => 'site.maintenance_enabled', 'group' => 'website', 'type' => 'boolean', 'value' => '0'],

            ['key' => 'hero_title', 'group' => 'home', 'type' => 'text'],
            ['key' => 'hero_subtitle', 'group' => 'home', 'type' => 'textarea'],
            ['key' => 'hero_cta_text', 'group' => 'home', 'type' => 'text'],
            ['key' => 'home.hero_title', 'group' => 'home', 'type' => 'text'],
            ['key' => 'home.hero_subtitle', 'group' => 'home', 'type' => 'textarea'],
            ['key' => 'home.hero_image_path', 'group' => 'home', 'type' => 'image'],
            ['key' => 'home.hero_image_path_alt', 'group' => 'home', 'type' => 'text'],
            ['key' => 'home.overview_headline', 'group' => 'home', 'type' => 'text'],

            ['key' => 'footer_description', 'group' => 'footer', 'type' => 'textarea'],
            ['key' => 'footer.about', 'group' => 'footer', 'type' => 'textarea'],
            ['key' => 'footer_address', 'group' => 'footer', 'type' => 'textarea'],
            ['key' => 'footer.address', 'group' => 'footer', 'type' => 'textarea'],
            ['key' => 'footer_email', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_phone', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_copyright', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer.whatsapp', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer.instagram', 'group' => 'footer', 'type' => 'text'],

            ['key' => 'contact.email', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'contact.phone', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'contact.whatsapp', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'contact.map_embed', 'group' => 'contact', 'type' => 'textarea'],
            ['key' => 'contact_map_embed', 'group' => 'contact', 'type' => 'textarea'],
            ['key' => 'contact_form_receiver_email', 'group' => 'contact', 'type' => 'text'],

            ['key' => 'social_instagram', 'group' => 'social', 'type' => 'text'],
            ['key' => 'social_whatsapp', 'group' => 'social', 'type' => 'text'],
            ['key' => 'social_youtube', 'group' => 'social', 'type' => 'text'],
            ['key' => 'social_tiktok', 'group' => 'social', 'type' => 'text'],
            ['key' => 'social.instagram', 'group' => 'social', 'type' => 'text'],
            ['key' => 'social.tiktok', 'group' => 'social', 'type' => 'text'],
        ];

        foreach ($defaults as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'] ?? null,
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                    'updated_by_admin_id' => null,
                ]
            );
        }
    }
}
