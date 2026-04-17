/**
 * Megamenu Interactions
 * Handle desktop and mobile megamenu behaviors
 */

// Desktop Megamenu uses pure CSS hover - no JavaScript needed
// Mobile menu uses Bootstrap collapse and aside-menu - no custom JS needed

// Close aside menu when clicking on actual category/navigation links
document.addEventListener('DOMContentLoaded', function () {
    const asideMenu = document.getElementById('AsideOffcanvasMenu');
    if (!asideMenu) return;

    // Only close when clicking on real links (not toggles)
    const asideMenuNav = document.getElementById('offcanvasNav');
    if (!asideMenuNav) return;

    // Handle collapse toggles in capture phase to avoid theme JS conflicts.
    asideMenuNav.addEventListener('click', function (event) {
        const toggleLink = event.target.closest('a[data-bs-toggle="collapse"]');
        if (!toggleLink) return;

        event.preventDefault();
        event.stopPropagation();

        const selector = toggleLink.getAttribute('data-bs-target') || toggleLink.getAttribute('href');
        if (!selector || selector === '#') return;

        const target = document.querySelector(selector);
        if (!target) return;

        bootstrap.Collapse.getOrCreateInstance(target, { toggle: false }).toggle();
    }, true);

    asideMenuNav.addEventListener('click', function (event) {
        // Check if clicked element is a link
        let link = event.target.closest('a');
        if (!link) return;

        // Don't close if it's a collapse toggle (has data-bs-toggle)
        if (link.hasAttribute('data-bs-toggle')) return;

        // Theme offcanvas toggles use href="#" and nested <ul>
        if (link.getAttribute('href') === '#' || link.nextElementSibling?.tagName === 'UL') return;

        // Don't close if href is javascript:void(0)
        const href = link.getAttribute('href') || '';
        if (href.includes('javascript:')) return;

        // Close offcanvas for real navigation links
        if (asideMenu.classList.contains('show')) {
            const offcanvasInstance = bootstrap.Offcanvas.getInstance(asideMenu);
            if (offcanvasInstance) {
                offcanvasInstance.hide();
            }
        }
    });
});
