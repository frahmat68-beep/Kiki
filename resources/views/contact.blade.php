@extends('layouts.app')

@section('title', setting('meta_title', 'Contact | Manake'))

@section('content')
    <section class="bg-slate-50 dark:bg-slate-950">
        <div class="max-w-6xl mx-auto px-6 py-12">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">{{ __('ui.nav.contact') ?? 'Contact' }}</p>
                    <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900 dark:text-white">Kontak Manake</h1>
                    <p class="text-sm text-slate-600 dark:text-slate-300">Hubungi kami untuk pemesanan dan kolaborasi.</p>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1fr,360px]">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Alamat & Info</h2>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 whitespace-pre-line">
                        {{ site_content('footer.address', setting('footer_address', '-')) }}
                    </p>
                    <div class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                        <p>Email: {{ site_content('contact.email', setting('footer_email', '-')) }}</p>
                        <p>Phone: {{ site_content('contact.phone', setting('footer_phone', '-')) }}</p>
                    </div>
                    <div class="mt-4 text-sm text-slate-600 dark:text-slate-300">
                        <p>Instagram: {{ site_content('footer.instagram', setting('social_instagram', '-')) }}</p>
                        <p>WhatsApp: {{ site_content('footer.whatsapp', setting('social_whatsapp', '-')) }}</p>
                        <p>YouTube: {{ setting('social_youtube', '-') }}</p>
                        <p>TikTok: {{ setting('social_tiktok', '-') }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Peta Lokasi</h2>
                    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
                        @if (setting('contact_map_embed'))
                            {!! setting('contact_map_embed') !!}
                        @else
                            <div class="flex h-48 items-center justify-center text-sm text-slate-500 dark:text-slate-400">
                                Map embed belum diatur.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
