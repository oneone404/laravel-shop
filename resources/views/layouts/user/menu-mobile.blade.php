<style>
.menu-mobile-custom {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex;
    justify-content: center; /* Đổi từ space-between -> center */
    align-items: flex-end;
    padding: 6px 0;
    z-index: 1001; /* Cao hơn overlay menu */
    box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.1);
    border-top: 1px solid var(--border-color);
    font-family: 'Be Vietnam Pro', sans-serif;
    gap: 20px; /* Thêm khoảng cách đều giữa các cột */
    border-radius: 20px 20px 0 0;
}

.menu-col {
    text-align: center;
    font-size: 11px;
    color: var(--text-color);
    text-decoration: none;
    width: 90px; /* Giới hạn chiều rộng mỗi cột để không đẩy xa avatar */
}

.menu-col i {
    font-size: 20px;
    display: block;
    margin-bottom: 2px;
    color: var(--primary-color);
}

.menu-label {
    font-size: 11px;
    font-weight: bold;
}

.menu-value {
    font-size: 11px;
    margin-top: 1px;
}

.text-red {
    color: var(--danger);
    font-weight: bold;
}

.avatar-center {
    position: relative;
    top: -20px;
}

.menu-avatar-link {
    display: inline-block;
}

.menu-avatar {
    width: 50px;
    height: 50px;
    padding: 3px;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(14, 62, 218, 0.3);
    background: var(--primary-color);
    display: flex;
    justify-content: center;
    align-items: center;
}

.menu-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
}
.menu-text {
    font-size: 10px;
    font-weight: 700; /* Đậm hơn */
    color: var(--text-color); /* Màu đậm dễ đọc */
    font-family: 'Segoe UI', 'Roboto', 'Arial', sans-serif;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 3px;
}
/* Ẩn trên desktop */
@media (min-width: 768px) {
    .menu-mobile-custom {
        display: none;
    }
}
/* Cấu trúc Overlay Menu */
.mobile-overlay-menu {
    position: fixed;
    top: 0;
    left: -100%;
    width: 280px;
    height: 100%;
    background: var(--bg-card);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 4px 0 25px rgba(0, 0, 0, 0.2);
    transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 10002;
    padding: 24px 16px;
    overflow-y: auto;
    border-radius: 0 24px 24px 0;
    border-right: 1px solid var(--border-color);
}

.mobile-overlay-menu.active {
    left: 0;
}

/* Header của menu */
.mobile-overlay-menu__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 20px;
}

.mobile-overlay-menu__title {
    font-size: 18px;
    font-weight: bold;
    color: var(--dark);
    letter-spacing: 0.5px;
}

.mobile-overlay-menu__close {
    background: none;
    border: none;
    font-size: 22px;
    color: var(--text-light);
    cursor: pointer;
    transition: color 0.3s;
}

.mobile-overlay-menu__close:hover {
    color: var(--danger);
}

/* Căn chỉnh các nút khác */
.mobile-overlay-menu__link {
    display: flex;
    align-items: center;
    font-size: 14px;
    padding: 10px 12px;
    color: var(--text-color);
    background-color: var(--bg-light);
    border-radius: 10px;
    margin-bottom: 10px;
    transition: all 0.3s;
    position: relative;
    border: 1px solid var(--border-color);
}

.mobile-overlay-menu__link:hover {
    background-color: var(--border-color);
    transform: scale(1.02);
}

.mobile-overlay-menu__link i {
    margin-right: 10px;
    font-size: 18px;
    color: #777;
    transition: color 0.3s;
}

.mobile-overlay-menu__link:hover i {
    color: #333;
}
/* Nút đăng xuất */
.mobile-overlay-menu__button {
    width: calc(100% - 10px);  /* Điều chỉnh chiều rộng để căn đều */
    text-align: center;
    background-color: #fff;  /* Nền trắng */
    border: 2px solid #e53935;  /* Viền đỏ */
    color: #e53935;  /* Chữ đỏ */
    font-size: 13px;
    padding: 10px 16px;  /* Tăng padding để đẩy nội dung vào trong */
    margin-top: 16px;
    margin-left: 6px;  /* Căn trái để đẩy vào giữa */
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s, transform 0.3s;
    border-radius: 10px;
    font-weight: bold;
}

.mobile-overlay-menu__button:hover {
    background-color: #e53935;  /* Nền đỏ */
    color: #fff;  /* Chữ trắng */
    transform: scale(1.03);
}


/* Nền tối mờ */
.mobile-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 9999;
    display: none;
    transition: all 0.4s ease;
}

.mobile-overlay.active {
    display: block;
}
.username-uppercase {
    text-transform: uppercase;  /* In hoa */
    font-weight: bold;          /* Đậm hơn */
    color: var(--dark);                /* Màu chữ tối hơn */
    font-size: 14px;            /* Kích thước vừa phải */
}

</style>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<nav class="menu-mobile-custom">
    <!-- Cột Số Dư hoặc Login -->
    <div class="menu-col">
        @auth
            <a href="{{ route('profile.deposit-card') }}" class="menu-item">
                <i class="bx bx-wallet"></i>
                <div class="menu-text">{{ number_format(Auth::user()->balance) }} VNĐ</div>
            </a>
        @else
            <a href="{{ route('login') }}" class="menu-item">
                <i class="bx bx-wallet"></i>
                <div class="menu-text">BALANCE</div>
            </a>
        @endauth
    </div>

    <!-- Cột Avatar -->
    <div class="menu-col avatar-center">
        <a href="/" class="menu-avatar-link">
            <div class="menu-avatar">
                <img src="{{ config_get('site_favicon') }}" alt="ICON">
            </div>
        </a>
    </div>

    <!-- Cột Tài Khoản -->
    <div class="menu-col">
        @auth
        <a href="/profile" class="menu-item">
            <i class="bx bx-user"></i>
            <div class="menu-text">TÀI KHOẢN</div>
        </a>
        @else
        <a href="/login" class="menu-item">
            <i class="bx bx-user"></i>
            <div class="menu-text">ĐĂNG NHẬP</div>
        </a>
        @endauth
    </div>
</nav>
