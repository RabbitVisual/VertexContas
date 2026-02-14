/**
 * Dark Mode - Flowbite/Tailwind v4 Best Practices
 * Centraliza a lógica de tema usando localStorage 'color-theme'
 * @see https://flowbite.com/docs/customize/dark-mode/
 */
(function () {
    const STORAGE_KEY = 'color-theme';

    function applyTheme(isDark) {
        document.documentElement.classList.toggle('dark', isDark);
        localStorage.setItem(STORAGE_KEY, isDark ? 'dark' : 'light');
    }

    function getPreferredTheme() {
        if (localStorage.getItem(STORAGE_KEY)) {
            return localStorage.getItem(STORAGE_KEY) === 'dark';
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    window.Theme = {
        set: function (mode) {
            applyTheme(mode === 'dark');
        },
        toggle: function () {
            applyTheme(!document.documentElement.classList.contains('dark'));
        },
        isDark: function () {
            return document.documentElement.classList.contains('dark');
        },
    };

    // Respeita preferência do sistema quando não há escolha salva
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
            if (!('color-theme' in localStorage)) {
                applyTheme(e.matches);
            }
        });
    }
})();
