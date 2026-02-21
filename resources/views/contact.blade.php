@extends('layouts.app')

@section('title', __('ui.contact.page_title'))

@section('content')
    <section class="bg-slate-50 dark:bg-slate-950">
        <div class="max-w-6xl mx-auto px-6 py-12">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900 dark:text-white">{{ __('ui.contact.title') }}</h1>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ __('ui.contact.subtitle') }}</p>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1fr,360px]">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('ui.contact.info_title') }}</h2>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 whitespace-pre-line">
                        {{ site_content('footer.address', setting('footer_address', '-')) }}
                    </p>
                    <div class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                        <p>{{ __('ui.contact.labels.email') }}: {{ site_content('contact.email', setting('footer_email', '-')) }}</p>
                        <p>{{ __('ui.contact.labels.phone') }}: {{ site_content('contact.phone', setting('footer_phone', '-')) }}</p>
                    </div>
                    <div class="mt-4 text-sm text-slate-600 dark:text-slate-300">
                        <p>{{ __('ui.contact.labels.instagram') }}: {{ site_content('footer.instagram', setting('social_instagram', '-')) }}</p>
                        <p>{{ __('ui.contact.labels.whatsapp') }}: {{ site_content('footer.whatsapp', setting('social_whatsapp', '-')) }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('ui.contact.map_title') }}</h2>
                    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
                        @if (setting('contact_map_embed'))
                            {!! setting('contact_map_embed') !!}
                        @else
                            <div class="flex h-48 items-center justify-center text-sm text-slate-500 dark:text-slate-400">
                                {{ __('ui.contact.map_empty') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
