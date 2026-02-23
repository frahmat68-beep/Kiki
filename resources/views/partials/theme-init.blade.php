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
{!! file_get_contents(resource_path('css/theme.css')) !!}
</style>
