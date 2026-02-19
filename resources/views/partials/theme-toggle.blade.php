<script>
    (() => {
        const allowedThemes = ['system', 'dark', 'light'];
        const allowedLocales = ['id', 'en'];

        const rememberTheme = (theme) => {
            if (!allowedThemes.includes(theme)) {
                return;
            }

            try {
                localStorage.setItem('manake.theme', theme);
            } catch (error) {
                // Ignore localStorage errors.
            }

            if (window.ManakeTheme && typeof window.ManakeTheme.applyTheme === 'function') {
                window.ManakeTheme.applyTheme(theme);
            }
        };

        const rememberLocale = (locale) => {
            if (!allowedLocales.includes(locale)) {
                return;
            }

            try {
                localStorage.setItem('manake.locale', locale);
            } catch (error) {
                // Ignore localStorage errors.
            }
        };

        document.addEventListener('click', (event) => {
            const themeLink = event.target.closest('a[data-theme-option]');
            if (themeLink) {
                rememberTheme(themeLink.dataset.themeOption || '');
                return;
            }

            const localeLink = event.target.closest('a[data-locale-option]');
            if (localeLink) {
                rememberLocale(localeLink.dataset.localeOption || '');
            }
        });

        window.ManakePreferences = {
            rememberTheme,
            rememberLocale,
        };
    })();
</script>
