<footer class="bg-gradient-to-br from-slate-950 via-slate-900 to-blue-900 text-blue-100">
    <div class="max-w-7xl mx-auto px-6 py-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <div>
            <h3 class="text-sm font-semibold text-white">{{ __('app.footer.about_title') }}</h3>
            <p class="mt-3 text-sm leading-relaxed text-blue-100/90">
                {{ setting('footer.about', setting('footer_description', site_content('footer.about', __('app.footer.about_body')))) }}
            </p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white">{{ __('app.footer.contact_title') }}</h3>
            <ul class="mt-3 space-y-2 text-sm text-blue-100/90">
                <li>WhatsApp: {{ setting('footer.whatsapp', setting('social_whatsapp', site_content('footer.whatsapp', setting('footer_phone', '+62 812-3456-7890')))) }}</li>
                <li>Email: {{ setting('contact.email', setting('footer_email', site_content('contact.email', 'hello@manakerental.id'))) }}</li>
                <li>Instagram: {{ setting('footer.instagram', setting('social_instagram', site_content('footer.instagram', '@manakerental'))) }}</li>
                <li>YouTube: {{ setting('social_youtube', '@manakerental') }}</li>
                <li>TikTok: {{ setting('social_tiktok', '@manakerental') }}</li>
            </ul>
            <p class="mt-3 text-xs text-blue-200/75">{{ __('app.footer.contact_note') }}</p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white">{{ __('app.footer.address_title') }}</h3>
            <p class="mt-3 text-sm text-blue-100/90 leading-relaxed">{!! nl2br(e(setting('footer.address', setting('footer_address', site_content('footer.address', __('app.footer.address_body')))))) !!}</p>
            <p class="mt-3 text-xs text-blue-200/75">{{ __('app.footer.address_hours') }}</p>
        </div>
    </div>

    <div class="border-t border-blue-400/25">
        <div class="max-w-7xl mx-auto px-6 py-4 text-xs text-blue-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <span>&copy; {{ setting('footer_copyright', __('app.footer.copyright')) }}</span>
            <span>{{ setting('site_tagline', __('app.footer.tagline')) }}</span>
        </div>
    </div>
</footer>
