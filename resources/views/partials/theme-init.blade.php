@php
    $initialThemePreference = $themePreference ?? request()->attributes->get('theme_preference', 'light');
@endphp
<script>
    window.tailwind = window.tailwind || {};
    window.tailwind.config = {
        darkMode: 'class',
    };

    (() => {
        const allowed = ['system', 'dark', 'light'];
        let preference = @json($initialThemePreference);

        if (!allowed.includes(preference)) {
            preference = 'light';
        }

        try {
            const localTheme = localStorage.getItem('manake.theme');
            if (allowed.includes(localTheme)) {
                preference = localTheme;
            }
        } catch (error) {
            // Ignore localStorage access errors.
        }

        try {
            const cookieTheme = decodeURIComponent(
                (document.cookie.split('; ').find((row) => row.startsWith('theme=')) || '').split('=')[1] || ''
            );
            if (allowed.includes(cookieTheme)) {
                preference = cookieTheme;
            }
        } catch (error) {
            // Ignore cookie parsing errors.
        }

        const resolveTheme = (themePreference) => {
            const canMatchMedia = typeof window.matchMedia === 'function';
            if (themePreference !== 'system') {
                return themePreference;
            }

            if (!canMatchMedia) {
                return 'light';
            }

            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        };

        const syncThemeAssets = (resolvedTheme) => {
            const activeTheme = resolvedTheme || (document.documentElement.dataset.themeResolved || 'light');
            document.querySelectorAll('link[data-theme-favicon]').forEach((faviconLink) => {
                const lightHref = faviconLink.getAttribute('data-light') || faviconLink.getAttribute('href');
                const darkHref = faviconLink.getAttribute('data-dark') || lightHref;
                faviconLink.setAttribute('href', activeTheme === 'dark' ? darkHref : lightHref);
            });
        };

        const applyTheme = (themePreference) => {
            const resolvedTheme = resolveTheme(themePreference);
            const root = document.documentElement;
            root.classList.toggle('dark', resolvedTheme === 'dark');
            root.dataset.theme = 'manake-brand';
            root.dataset.themePreference = themePreference;
            root.dataset.themeResolved = resolvedTheme;
            syncThemeAssets(resolvedTheme);
        };

        applyTheme(preference);

        try {
            localStorage.setItem('manake.theme', preference);
        } catch (error) {
            // Ignore localStorage access errors.
        }

        window.ManakeTheme = {
            allowed,
            getPreference: () => document.documentElement.dataset.themePreference || preference,
            applyTheme,
            resolveTheme,
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => syncThemeAssets());
        } else {
            syncThemeAssets();
        }

        if (typeof window.matchMedia === 'function') {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            const onSystemThemeChange = () => {
                const currentPreference = document.documentElement.dataset.themePreference || preference;
                if (currentPreference === 'system') {
                    applyTheme('system');
                }
            };

            if (typeof mediaQuery.addEventListener === 'function') {
                mediaQuery.addEventListener('change', onSystemThemeChange);
            } else if (typeof mediaQuery.addListener === 'function') {
                mediaQuery.addListener(onSystemThemeChange);
            }
        }
    })();
</script>
<style>
    :root {
        --bg: #edf2fb;
        --bg-soft: #f7f9ff;
        --surface: #ffffff;
        --surface-2: #f3f7ff;
        --surface-3: #ecf2fd;
        --border: #d6e2f3;
        --text: #111b35;
        --text-muted: #667895;
        --text-soft: #8a98b0;
        --primary: #2f5ff5;
        --primary-strong: #2248ca;
        --primary-soft: #e8efff;
        --primary-ring: rgba(47, 95, 245, 0.24);
        --success: #0f9b6d;
        --warning: #d97706;
        --danger: #dc2626;
        --radius-lg: 14px;
        --radius-xl: 18px;
        --radius-2xl: 24px;
        --shadow-soft: 0 20px 40px rgba(8, 24, 64, 0.09), 0 5px 14px rgba(8, 24, 64, 0.06);
        --shadow-xs: 0 10px 24px rgba(8, 24, 64, 0.08);
        --gradient-brand: linear-gradient(135deg, #061338 0%, #264bbf 54%, #10295f 100%);
    }

    html[data-theme-resolved='light'] {
        color-scheme: light;
    }

    html[data-theme-resolved='dark'] {
        color-scheme: dark;
        --bg: #070f24;
        --bg-soft: #0b1733;
        --surface: #101f3e;
        --surface-2: #152a50;
        --surface-3: #1a345f;
        --border: #29426c;
        --text: #e7eefb;
        --text-muted: #a5b7d8;
        --text-soft: #7d96be;
        --primary: #4f7dff;
        --primary-strong: #3a64de;
        --primary-soft: rgba(79, 125, 255, 0.14);
        --primary-ring: rgba(79, 125, 255, 0.32);
        --success: #34d399;
        --warning: #fbbf24;
        --danger: #fb7185;
        --shadow-soft: 0 20px 42px rgba(2, 8, 23, 0.48), 0 8px 20px rgba(2, 8, 23, 0.3);
        --shadow-xs: 0 12px 26px rgba(2, 8, 23, 0.36);
        --gradient-brand: linear-gradient(135deg, #030818 0%, #173485 54%, #0c1f4e 100%);
    }

    html {
        background: var(--bg);
    }

    body,
    body.bg-slate-100,
    body.bg-slate-50 {
        color: var(--text);
        background:
            radial-gradient(1000px 420px at 108% -18%, color-mix(in oklab, var(--primary) 16%, transparent), transparent 64%),
            radial-gradient(860px 360px at -18% -14%, color-mix(in oklab, var(--primary) 9%, transparent), transparent 66%),
            var(--bg);
    }

    main {
        min-height: calc(100vh - 210px);
    }

    * {
        transition: background-color 140ms ease, border-color 140ms ease, color 140ms ease, box-shadow 140ms ease;
    }

    @keyframes manake-fade-up {
        0% {
            opacity: 0;
            transform: translateY(8px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card,
    .manake-panel {
        animation: manake-fade-up 320ms ease both;
    }

    .card,
    .manake-panel,
    .rounded-3xl.border,
    .rounded-2xl.border,
    .rounded-xl.border,
    .rounded-lg.border {
        border-color: var(--border) !important;
        background: var(--surface) !important;
        color: var(--text);
        box-shadow: var(--shadow-xs);
    }

    .shadow-sm,
    .shadow,
    .shadow-md,
    .shadow-lg {
        box-shadow: var(--shadow-soft) !important;
    }

    .input,
    input:not([type='checkbox']):not([type='radio']):not([type='range']):not([type='hidden']):not([type='color']),
    select,
    textarea {
        border-color: var(--border) !important;
        background: color-mix(in oklab, var(--surface-2) 74%, var(--surface)) !important;
        color: var(--text);
        border-radius: var(--radius-lg);
        transition: border-color 150ms ease, box-shadow 150ms ease, background-color 150ms ease;
    }

    input[type='file'] {
        padding: 8px !important;
    }

    input[type='file']::file-selector-button {
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface);
        color: var(--text);
        font-size: 12px;
        font-weight: 600;
        padding: 7px 10px;
        margin-right: 10px;
        cursor: pointer;
    }

    .input::placeholder,
    input::placeholder,
    textarea::placeholder {
        color: var(--text-soft);
    }

    .input:focus,
    input:focus,
    select:focus,
    textarea:focus {
        border-color: color-mix(in oklab, var(--primary) 65%, var(--border)) !important;
        box-shadow: 0 0 0 3px var(--primary-ring) !important;
        outline: none;
    }

    .btn-primary {
        border: 1px solid transparent;
        background: var(--primary);
        color: #fff;
        box-shadow: 0 10px 22px color-mix(in oklab, var(--primary) 28%, transparent);
        transition: background-color 150ms ease, transform 120ms ease, box-shadow 150ms ease;
    }

    .btn-primary:hover {
        background: var(--primary-strong);
        box-shadow: 0 14px 26px color-mix(in oklab, var(--primary) 34%, transparent);
    }

    .btn-secondary {
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text);
        transition: border-color 150ms ease, color 150ms ease, background-color 150ms ease;
    }

    .btn-secondary:hover {
        border-color: color-mix(in oklab, var(--primary) 35%, var(--border));
        background: var(--surface-2);
        color: var(--primary);
    }

    button.bg-blue-600,
    a.bg-blue-600 {
        background: var(--primary) !important;
        color: #fff !important;
        border-color: transparent !important;
        box-shadow: 0 8px 20px color-mix(in oklab, var(--primary) 28%, transparent);
    }

    button.hover\:bg-blue-700:hover,
    a.hover\:bg-blue-700:hover {
        background: var(--primary-strong) !important;
    }

    button.border-slate-200,
    a.border-slate-200,
    button.border-gray-200,
    a.border-gray-200 {
        border-color: var(--border) !important;
        color: var(--text-muted) !important;
    }

    button.border-slate-200:hover,
    a.border-slate-200:hover,
    button.border-gray-200:hover,
    a.border-gray-200:hover {
        border-color: color-mix(in oklab, var(--primary) 30%, var(--border)) !important;
        color: var(--primary) !important;
        background: color-mix(in oklab, var(--surface-2) 80%, var(--surface)) !important;
    }

    .bg-emerald-50 { background-color: color-mix(in oklab, var(--success) 13%, var(--surface)) !important; }
    .border-emerald-100,
    .border-emerald-200 { border-color: color-mix(in oklab, var(--success) 30%, var(--border)) !important; }
    .text-emerald-700 { color: color-mix(in oklab, var(--success) 84%, var(--text)) !important; }

    .bg-amber-50 { background-color: color-mix(in oklab, var(--warning) 15%, var(--surface)) !important; }
    .border-amber-100,
    .border-amber-200 { border-color: color-mix(in oklab, var(--warning) 33%, var(--border)) !important; }
    .text-amber-700 { color: color-mix(in oklab, var(--warning) 85%, var(--text)) !important; }

    .bg-rose-50,
    .bg-red-50 { background-color: color-mix(in oklab, var(--danger) 12%, var(--surface)) !important; }
    .border-rose-100,
    .border-rose-200,
    .border-red-200 { border-color: color-mix(in oklab, var(--danger) 30%, var(--border)) !important; }
    .text-rose-700,
    .text-red-600 { color: color-mix(in oklab, var(--danger) 88%, var(--text)) !important; }

    .bg-white,
    .bg-white\/95,
    .bg-white\/90,
    .bg-white\/80 {
        background-color: var(--surface) !important;
    }

    .bg-slate-50,
    .bg-slate-50\/90 {
        background-color: var(--surface-2) !important;
    }

    .bg-slate-900\/40,
    .bg-slate-900\/55 {
        background-color: rgba(6, 15, 35, 0.52) !important;
    }

    .bg-slate-100 {
        background-color: var(--bg) !important;
    }

    .border-slate-200,
    .border-slate-300,
    .border-gray-200,
    .border-gray-300,
    .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
    .divide-slate-200 > :not([hidden]) ~ :not([hidden]) {
        border-color: var(--border) !important;
    }

    .text-slate-900,
    .text-slate-800,
    .text-slate-700,
    .text-gray-900,
    .text-gray-800,
    .text-gray-700 {
        color: var(--text) !important;
    }

    .text-slate-600,
    .text-slate-500,
    .text-slate-400,
    .text-gray-600,
    .text-gray-500,
    .text-gray-400 {
        color: var(--text-muted) !important;
    }

    .text-slate-300 {
        color: color-mix(in oklab, var(--text-muted) 75%, #fff) !important;
    }

    .text-blue-600,
    .text-blue-700 {
        color: var(--primary) !important;
    }

    .bg-blue-50,
    .bg-blue-100 {
        background-color: var(--primary-soft) !important;
    }

    .border-blue-200,
    .border-blue-100 {
        border-color: color-mix(in oklab, var(--primary) 28%, var(--border)) !important;
    }

    .from-blue-600,
    .from-blue-500,
    .from-slate-950 {
        --tw-gradient-from: #0a1c58 var(--tw-gradient-from-position) !important;
        --tw-gradient-to: rgb(10 28 88 / 0) var(--tw-gradient-to-position) !important;
        --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
    }

    .to-blue-500,
    .to-blue-600,
    .to-blue-700,
    .to-slate-900,
    .via-blue-900 {
        --tw-gradient-to: #274bbf var(--tw-gradient-to-position) !important;
    }

    .bg-gradient-to-br {
        background-image: var(--gradient-brand) !important;
    }

    table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    table thead th {
        background: color-mix(in oklab, var(--surface-2) 90%, var(--surface));
        color: var(--text-muted);
        font-size: 11px;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-weight: 700;
        border-bottom: 1px solid var(--border);
        padding: 12px 14px;
    }

    table tbody td {
        border-bottom: 1px solid color-mix(in oklab, var(--border) 88%, transparent);
        padding: 14px;
    }

    table tbody tr:nth-child(even) {
        background: color-mix(in oklab, var(--surface-2) 45%, var(--surface));
    }

    table tbody tr {
        transition: background-color 140ms ease, transform 140ms ease;
    }

    table tbody tr:hover {
        background: color-mix(in oklab, var(--surface-2) 72%, var(--surface));
        transform: translateY(-1px);
    }

    table thead th:first-child {
        border-top-left-radius: 12px;
    }

    table thead th:last-child {
        border-top-right-radius: 12px;
    }

    header.sticky {
        background: color-mix(in oklab, var(--surface) 90%, transparent) !important;
        border-bottom-color: var(--border) !important;
        box-shadow: 0 8px 22px rgba(12, 30, 72, 0.05);
        backdrop-filter: blur(12px);
    }

    .group\/sidebar {
        background: color-mix(in oklab, var(--surface) 92%, transparent) !important;
        border-right-color: var(--border) !important;
        box-shadow: 12px 0 28px rgba(12, 30, 72, 0.08);
        backdrop-filter: blur(12px);
    }

    .group\/sidebar::before {
        content: none !important;
        display: none !important;
    }

    .group\/sidebar a {
        border: 1px solid transparent;
    }

    .group\/sidebar a.bg-blue-600 {
        background: var(--primary) !important;
        color: #fff !important;
        border-color: color-mix(in oklab, var(--primary) 65%, var(--border)) !important;
        box-shadow: 0 9px 20px color-mix(in oklab, var(--primary) 35%, transparent);
    }

    .group\/sidebar a:hover {
        background: color-mix(in oklab, var(--surface-2) 86%, var(--surface)) !important;
        border-color: color-mix(in oklab, var(--primary) 22%, var(--border)) !important;
    }

    .badge-status {
        border: 1px solid var(--border);
        background: var(--surface-2);
        color: var(--text-muted);
    }

    .badge-status-success {
        border-color: color-mix(in oklab, var(--success) 35%, var(--border));
        background: color-mix(in oklab, var(--success) 20%, var(--surface));
        color: color-mix(in oklab, var(--success) 80%, var(--text));
    }

    .badge-status-warning {
        border-color: color-mix(in oklab, var(--warning) 35%, var(--border));
        background: color-mix(in oklab, var(--warning) 18%, var(--surface));
        color: color-mix(in oklab, var(--warning) 80%, var(--text));
    }

    .badge-status-danger {
        border-color: color-mix(in oklab, var(--danger) 35%, var(--border));
        background: color-mix(in oklab, var(--danger) 16%, var(--surface));
        color: color-mix(in oklab, var(--danger) 80%, var(--text));
    }

    a:focus-visible,
    button:focus-visible,
    input:focus-visible,
    select:focus-visible,
    textarea:focus-visible {
        outline: 2px solid color-mix(in oklab, var(--primary) 60%, transparent);
        outline-offset: 2px;
    }

    footer {
        border-top: 1px solid color-mix(in oklab, var(--primary) 18%, var(--border));
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
    }

    .footer-rules-card {
        background: linear-gradient(145deg, #0a1c58 0%, #274bbf 100%) !important;
        border-color: rgba(147, 197, 253, 0.5) !important;
        color: #eff6ff !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08), 0 10px 24px rgba(5, 20, 62, 0.18);
    }

    .footer-rules-kicker {
        color: #bfdbfe !important;
    }

    .footer-rules-link {
        color: #ffffff !important;
    }

    .footer-rules-note {
        color: #dbeafe !important;
    }

    .footer-rules-card:hover {
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 12px 28px rgba(5, 20, 62, 0.24);
    }

    html[data-theme-resolved='dark'] .footer-rules-card {
        background: linear-gradient(145deg, #0a1431 0%, #1d3f9b 100%) !important;
        border-color: rgba(96, 165, 250, 0.45) !important;
    }

    @media (max-width: 1024px) {
        .group\/sidebar {
            background: var(--surface) !important;
            box-shadow: 10px 0 26px rgba(2, 10, 34, 0.2);
        }

        .group\/sidebar::before {
            opacity: 0;
        }

        table thead th {
            padding: 10px 10px;
        }

        table tbody td {
            padding: 10px;
        }
    }

    @media (max-width: 768px) {
        main {
            min-height: calc(100vh - 150px);
        }

        .card,
        .manake-panel,
        .rounded-3xl.border,
        .rounded-2xl.border {
            border-radius: 16px !important;
        }

        .rounded-xl.border,
        .rounded-lg.border {
            border-radius: 12px !important;
        }

        .text-4xl {
            font-size: 2rem !important;
            line-height: 1.2 !important;
        }

        .text-3xl {
            font-size: 1.75rem !important;
            line-height: 1.22 !important;
        }

        .text-2xl {
            font-size: 1.5rem !important;
            line-height: 1.25 !important;
        }
    }

    @media (max-width: 640px) {
        .p-6 {
            padding: 1rem !important;
        }

        .px-6 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .py-6 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        * {
            transition: none !important;
            animation: none !important;
        }
    }
</style>
