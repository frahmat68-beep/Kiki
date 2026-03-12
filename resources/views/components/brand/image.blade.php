@props([
    'light' => 'manake-logo-blue.png',
    'dark' => 'manake-logo-white.png',
    'alt' => 'Manake',
    'imgClass' => '',
    'swapInDark' => true,
])

@php
    $lightUrl = site_asset($light);
    $darkUrl = $dark ? site_asset($dark) : $lightUrl;
@endphp

<span {{ $attributes->class(['manake-themed-asset', 'manake-themed-asset--swap' => $swapInDark]) }}>
    <img
        src="{{ $lightUrl }}"
        alt="{{ $alt }}"
        class="manake-themed-asset__light {{ $imgClass }}"
    >
    @if ($swapInDark)
        <img
            src="{{ $darkUrl }}"
            alt=""
            aria-hidden="true"
            class="manake-themed-asset__dark {{ $imgClass }}"
        >
    @endif
</span>
