@extends('layouts.app')

@section('title', __('ui.settings.title'))
@section('page_title', __('ui.settings.title'))

@section('content')
    <div class="space-y-5">
        <div class="flex items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold text-blue-700">{{ __('ui.settings.title') }}</h1>
            @if (session('status') === 'settings-updated')
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ __('ui.settings.saved') }}
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('settings.update') }}" class="grid gap-5 lg:grid-cols-2">
            @csrf

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">{{ __('ui.settings.section_language') }}</h2>

                <div class="mt-4 grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                    @foreach (['id' => __('ui.languages.id'), 'en' => __('ui.languages.en')] as $value => $label)
                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                            <input
                                type="radio"
                                name="locale"
                                value="{{ $value }}"
                                class="h-4 w-4 text-blue-600"
                                {{ $locale === $value ? 'checked' : '' }}
                            >
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
                @error('locale')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">{{ __('ui.settings.section_theme') }}</h2>

                <div class="mt-4 grid grid-cols-1 gap-2.5 sm:grid-cols-3">
                    @foreach (['system' => __('ui.settings.theme_system'), 'dark' => __('ui.settings.theme_dark'), 'light' => __('ui.settings.theme_light')] as $value => $label)
                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                            <input
                                type="radio"
                                name="theme"
                                value="{{ $value }}"
                                class="h-4 w-4 text-blue-600"
                                {{ $theme === $value ? 'checked' : '' }}
                            >
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
                @error('theme')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="lg:col-span-2">
                <button class="btn-primary inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-semibold">
                    {{ __('ui.settings.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.querySelector('form[action="{{ route('settings.update') }}"]');
            if (!form) {
                return;
            }

            form.addEventListener('submit', () => {
                const selectedTheme = form.querySelector('input[name="theme"]:checked')?.value;
                const selectedLocale = form.querySelector('input[name="locale"]:checked')?.value;

                if (selectedTheme && window.ManakePreferences?.rememberTheme) {
                    window.ManakePreferences.rememberTheme(selectedTheme);
                }

                if (selectedLocale && window.ManakePreferences?.rememberLocale) {
                    window.ManakePreferences.rememberLocale(selectedLocale);
                }
            });
        })();
    </script>
@endpush
