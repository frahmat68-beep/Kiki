@php
    $footerAbout = setting('footer.about', setting('footer_description', site_content('footer.about', __('app.footer.about_body'))));
    $footerAddress = setting('footer.address', setting('footer_address', site_content('footer.address', __('app.footer.address_body'))));
    $footerWhatsapp = setting('footer.whatsapp', setting('social_whatsapp', site_content('footer.whatsapp', setting('footer_phone', '+62 812-3456-7890'))));
    $footerEmail = setting('contact.email', setting('footer_email', site_content('contact.email', 'hello@manakerental.id')));
    $footerInstagram = setting('footer.instagram', setting('social_instagram', site_content('footer.instagram', '@manakerental')));
    $footerMapEmbed = setting('contact_map_embed', site_content('contact.map_embed', setting('contact.map_embed', '')));
@endphp

<footer class="bg-gradient-to-br from-slate-950 via-slate-900 to-blue-900 text-blue-100">
    <div class="mx-auto grid max-w-7xl gap-8 px-6 py-12 lg:grid-cols-[1.2fr,0.9fr,1fr,1.2fr]">
        <div>
            <h3 class="text-sm font-semibold text-white">{{ __('app.footer.about_title') }}</h3>
            <p class="mt-3 text-sm leading-relaxed text-blue-100/90">{{ $footerAbout }}</p>
            <div class="mt-4 rounded-2xl border border-blue-300/25 bg-blue-950/35 p-3">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-blue-100">{{ setting('footer.rules_title', __('app.footer.rules_title')) }}</p>
                <a href="{{ route('rental.rules') }}" class="mt-2 inline-flex items-center gap-1 text-sm font-semibold italic text-blue-100 transition hover:text-white">
                    {{ setting('footer.rules_link', __('app.footer.rules_link')) }}
                    <span aria-hidden="true">→</span>
                </a>
                <p class="mt-2 text-xs leading-relaxed text-blue-100/85">{{ setting('footer.rules_note', __('app.footer.rules_note')) }}</p>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white">{{ __('app.footer.quick_links_title') }}</h3>
            <nav class="mt-3 flex flex-col gap-2 text-sm text-blue-100/90">
                <a href="{{ route('about') }}" class="rounded-lg px-2 py-1 transition hover:bg-blue-500/15 hover:text-white">{{ __('app.footer.quick_about') }}</a>
                <a href="{{ route('contact') }}" class="rounded-lg px-2 py-1 transition hover:bg-blue-500/15 hover:text-white">{{ __('app.footer.quick_contact') }}</a>
                <a href="{{ route('rental.rules') }}" class="rounded-lg px-2 py-1 transition hover:bg-blue-500/15 hover:text-white">{{ __('app.footer.quick_rules') }}</a>
                <a href="{{ route('catalog') }}" class="rounded-lg px-2 py-1 transition hover:bg-blue-500/15 hover:text-white">{{ __('app.footer.quick_catalog') }}</a>
            </nav>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white">{{ __('app.footer.contact_title') }}</h3>
            <div class="mt-3 space-y-2 text-sm text-blue-100/90">
                <p>WhatsApp: {{ $footerWhatsapp }}</p>
                <p>Email: {{ $footerEmail }}</p>
                <p>Instagram: {{ $footerInstagram }}</p>
            </div>
            <h3 class="mt-5 text-sm font-semibold text-white">{{ __('app.footer.address_title') }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-blue-100/90">{!! nl2br(e($footerAddress)) !!}</p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white">{{ __('app.footer.location_title') }}</h3>
            <div class="mt-3 overflow-hidden rounded-2xl border border-blue-300/30 bg-blue-950/35">
                @if (! empty($footerMapEmbed))
                    <div class="min-h-[180px] [&>iframe]:h-[220px] [&>iframe]:w-full [&>iframe]:border-0">
                        {!! $footerMapEmbed !!}
                    </div>
                @else
                    <div class="flex h-[220px] items-center justify-center px-4 text-center text-xs text-blue-100/85">
                        {{ __('app.footer.location_empty') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="border-t border-blue-400/25">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-6 py-4 text-xs text-blue-200/80 sm:flex-row sm:items-center sm:justify-between">
            <span>&copy; {{ setting('footer_copyright', __('app.footer.copyright')) }}</span>
            <span>{{ setting('site_tagline', __('app.footer.tagline')) }}</span>
        </div>
    </div>
</footer>
