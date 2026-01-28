document.addEventListener('DOMContentLoaded', function() {
    // Lấy các phần tử
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const overlay = document.querySelector('.overlay');
    const closeButton = document.querySelector('.mobile-menu__close');

    // Hàm toggle menu
    function toggleMenu() {
        if (!mobileMenu || !overlay) return; // tránh lỗi nếu thiếu
        mobileMenu.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.classList.toggle('no-scroll');
    }

    // Gắn sự kiện chỉ khi phần tử tồn tại
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleMenu);
    }

    if (closeButton) {
        closeButton.addEventListener('click', toggleMenu);
    }

    if (overlay) {
        overlay.addEventListener('click', toggleMenu);
    }

    // Đóng menu khi nhấn ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('active')) {
            toggleMenu();
        }
    });
});
