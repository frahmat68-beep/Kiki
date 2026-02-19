@php
    $layout = auth()->check() ? 'layouts.app-dashboard' : 'layouts.app';
@endphp

@extends($layout)

@php
    $resolvedTitle = $title ?? (isset($title_key) ? __($title_key, $title_params ?? []) : __('ui.placeholders.default_title'));
    $resolvedMessage = $message ?? (isset($message_key) ? __($message_key, $message_params ?? []) : __('ui.placeholders.default_message'));
@endphp

@section('title', $resolvedTitle)
@section('page_title', $resolvedTitle)

@section('content')
    <section class="bg-slate-50 dark:bg-slate-950">
        <div class="max-w-4xl mx-auto px-6 py-16 text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">{{ __('ui.placeholders.kicker') }}</p>
            <h1 class="mt-4 text-2xl sm:text-3xl font-semibold text-slate-900 dark:text-white">{{ $resolvedTitle }}</h1>
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">
                {{ $resolvedMessage }}
            </p>
            <a
                href="/"
                class="mt-6 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:border-blue-200 hover:text-blue-600 transition dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-500/40"
            >
                {{ __('ui.placeholders.back_home') }}
            </a>
        </div>
    </section>
@endsection
