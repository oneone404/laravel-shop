/**
 * Mobile Menu Functionality
 * Handles opening/closing of the mobile menu and overlay menu
 */

document.addEventListener('DOMContentLoaded', function() {
    document.body.classList.add('has-mobile-menu');

    const menuToggle = document.querySelector('.menu-toggle');
    const mobileMenu = document.querySelector('.menu-mobile');
    const overlayMenu = document.querySelector('.mobile-overlay-menu');
    const overlayMenuClose = document.querySelector('.mobile-overlay-menu__close');
    const overlay = document.querySelector('.mobile-overlay');

    // Show overlay menu
    if (menuToggle && overlayMenu && overlay) {
        menuToggle.addEventListener('click', function() {
            overlayMenu.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close overlay menu (close button)
    if (overlayMenuClose && overlayMenu && overlay) {
        overlayMenuClose.addEventListener('click', function() {
            overlayMenu.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Close overlay menu (click overlay)
    if (overlay && overlayMenu) {
        overlay.addEventListener('click', function() {
            overlayMenu.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Highlight active menu link
    const currentPath = window.location.pathname;
    const mobileLinks = document.querySelectorAll('.menu-mobile__link');
    mobileLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && (currentPath === href || (href !== '/' && currentPath.startsWith(href)))) {
            link.classList.add('active');
        }
    });

    // Scroll behavior
    let lastScrollTop = 0;
    if (mobileMenu) {
        window.addEventListener('scroll', function() {
            if (window.innerWidth <= 768) {
                const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

                if (currentScroll > lastScrollTop && currentScroll > 150) {
                    mobileMenu.style.transform = 'translateY(100%)';
                } else {
                    mobileMenu.style.transform = 'translateY(0)';
                }

                lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
            }
        }, { passive: true });
    }
});
