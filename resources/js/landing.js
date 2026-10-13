const landing = document.querySelector('.landing-page');

if (landing) {
    const menuToggle = document.getElementById('lp-menu-toggle');
    const navigation = document.getElementById('lp-navigation');

    if (menuToggle && navigation) {
        landing.dataset.menuReady = '';
        menuToggle.hidden = false;

        const setMenuOpen = (open) => {
            navigation.classList.toggle('is-open', open);
            menuToggle.setAttribute('aria-expanded', String(open));
            menuToggle.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
        };

        menuToggle.addEventListener('click', () => {
            setMenuOpen(menuToggle.getAttribute('aria-expanded') !== 'true');
        });

        navigation.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => setMenuOpen(false));
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && menuToggle.getAttribute('aria-expanded') === 'true') {
                setMenuOpen(false);
                menuToggle.focus();
            }
        });

        window.matchMedia('(min-width: 901px)').addEventListener('change', (event) => {
            if (event.matches) setMenuOpen(false);
        });
    }
}
