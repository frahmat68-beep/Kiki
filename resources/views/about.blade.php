@extends('layouts.app')

@section('title', __('app.footer.quick_about'))

@php
    $aboutText = setting('footer.about', setting('footer_description', site_content('footer.about', __('app.footer.about_body'))));
    $contactWhatsapp = setting('footer.whatsapp', setting('social_whatsapp', site_content('footer.whatsapp', setting('footer_phone', '+62 812-3456-7890'))));
    $contactEmail = setting('contact.email', setting('footer_email', site_content('contact.email', 'hello@manakerental.id')));
@endphp

@section('content')
    <section class="bg-slate-50">
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
            <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">{{ __('app.footer.quick_about') }}</p>
                <h1 class="mt-3 text-2xl font-semibold text-slate-900 sm:text-3xl">{{ setting('brand.name', 'Manake') }}</h1>
                <p class="mt-3 max-w-3xl text-sm leading-relaxed text-slate-600">{{ $aboutText }}</p>
            </header>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">{{ __('Yang Perlu Diisi di CMS') }}</h2>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li>{{ __('1. Profil singkat bisnis: fokus layanan dan area cakupan.') }}</li>
                        <li>{{ __('2. Kontak aktif: WhatsApp, email, dan jam operasional.') }}</li>
                        <li>{{ __('3. Alamat lengkap + embed maps agar titik lokasi jelas.') }}</li>
                        <li>{{ __('4. Aturan sewa yang wajib dibaca sebelum checkout.') }}</li>
                        <li>{{ __('5. Call-to-action utama: cara booking, pembayaran, dan pengembalian.') }}</li>
                    </ul>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">{{ __('Kontak Cepat') }}</h2>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <p><span class="font-semibold text-slate-900">WhatsApp:</span> {{ $contactWhatsapp }}</p>
                        <p><span class="font-semibold text-slate-900">Email:</span> {{ $contactEmail }}</p>
                    </div>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <a href="{{ route('contact') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-700">
                            {{ __('app.footer.quick_contact') }}
                        </a>
                        <a href="{{ route('catalog') }}" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                            {{ __('app.footer.quick_catalog') }}
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endsection
