const shell = document.querySelector('.panel-shell');

if (shell) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('mobile-menu-button');
    const overlay = document.getElementById('sidebar-overlay');
    const mobileViewport = window.matchMedia('(max-width: 1023px)');

    shell.dataset.panelReady = '';
    toggle.hidden = false;

    const setDrawerOpen = (open, restoreFocus = false) => {
        const visible = open && mobileViewport.matches;
        shell.classList.toggle('panel-drawer-open', visible);
        toggle.setAttribute('aria-expanded', String(visible));
        toggle.setAttribute('aria-label', visible ? 'Tutup menu navigasi' : 'Buka menu navigasi');
        overlay.hidden = !visible;
        sidebar.inert = mobileViewport.matches && !visible;
        sidebar.setAttribute('aria-hidden', String(mobileViewport.matches && !visible));
        if (visible) sidebar.querySelector('a')?.focus();
        else if (restoreFocus) toggle.focus();
    };

    setDrawerOpen(false);
    toggle.addEventListener('click', () => setDrawerOpen(toggle.getAttribute('aria-expanded') !== 'true', true));
    overlay.addEventListener('click', () => setDrawerOpen(false, true));
    mobileViewport.addEventListener('change', () => setDrawerOpen(false));

    document.addEventListener('keydown', (event) => {
        if (!shell.classList.contains('panel-drawer-open')) return;
        if (event.key === 'Escape') {
            setDrawerOpen(false, true);
            return;
        }
        if (event.key === 'Tab') {
            const controls = [...sidebar.querySelectorAll('a, button, summary')]
                .filter((element) => element.getClientRects().length > 0);
            const first = controls[0];
            const last = controls.at(-1);
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last?.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first?.focus();
            }
        }
    });

    document.querySelectorAll('.panel-nav a').forEach((link) => {
        if (new URL(link.href).pathname === window.location.pathname) {
            link.setAttribute('aria-current', 'page');
        }
        if (link.getAttribute('aria-current') === 'page') {
            const group = link.closest('details');
            if (group) group.open = true;
        }
    });
}
