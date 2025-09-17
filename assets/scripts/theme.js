(function () {
    const THEME_KEY = 'site-theme'; // 'dark' or 'light'
    const LIGHT_CLASS = 'theme-light';
    const btn = document.getElementById('themeToggle');

    function getSavedTheme() {
        try { return localStorage.getItem(THEME_KEY); } catch (e) { return null; }
    }
    function saveTheme(theme) {
        try { localStorage.setItem(THEME_KEY, theme); } catch (e) {}
    }
    function prefersLight() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches;
    }

    function applyTheme(theme) {
        const html = document.documentElement;
        if (theme === 'light') {
            html.classList.add(LIGHT_CLASS);
            if (btn) { btn.textContent = 'Тёмная'; btn.setAttribute('aria-pressed', 'true'); }
        } else {
            html.classList.remove(LIGHT_CLASS);
            if (btn) { btn.textContent = 'Светлая'; btn.setAttribute('aria-pressed', 'false'); }
        }
    }

    function resolveInitialTheme() {
        const saved = getSavedTheme();
        if (saved === 'dark' || saved === 'light') return saved;
        return prefersLight() ? 'light' : 'dark'; // fallback dark
    }

    function toggleTheme() {
        const current = document.documentElement.classList.contains(LIGHT_CLASS) ? 'light' : 'dark';
        const next = current === 'light' ? 'dark' : 'light';
        applyTheme(next);
        saveTheme(next);
    }

    (function init() {
        const initial = resolveInitialTheme();
        applyTheme(initial);

        if (btn) {
            btn.addEventListener('click', toggleTheme);
        }

        // Если нет сохранённого выбора, реагируем на системные изменения
        if (!getSavedTheme() && window.matchMedia) {
            const mq = window.matchMedia('(prefers-color-scheme: light)');
            const listener = (e) => applyTheme(e.matches ? 'light' : 'dark');
            if (typeof mq.addEventListener === 'function') {
                mq.addEventListener('change', listener);
            } else if (typeof mq.addListener === 'function') {
                mq.addListener(listener);
            }
        }
    })();
})();