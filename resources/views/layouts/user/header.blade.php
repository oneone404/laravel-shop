<!-- Navbar -->
<nav class="navbar">
    <div class="navbar__container">
        <a href="/" class="navbar__logo">
            {{-- Light mode logo - hidden in dark mode --}}
            <img class="logo-light" src="{{ config_get('site_logo') }}" alt="{{ config_get('site_name') }}">
            {{-- Dark mode logo - hidden in light mode --}}
            <img class="logo-dark" src="{{ config_get('site_logo_dark') ?: config_get('site_logo') }}"
                alt="{{ config_get('site_name') }}">
        </a>

        <!-- Desktop Menu -->
        <div class="navbar__menu">
            <a href="/" class="menu__item {{ request()->is('/') ? 'active' : '' }}"><i class="fas fa-home"></i> TRANG
                CHỦ</a>

            <!-- Danh Mục Dropdown -->
            <div class="menu__dropdown">
                <a href="#" class="menu__item menu__item--dropdown">
                    <i class="fas fa-th-large"></i> DANH MỤC <i class="fas fa-chevron-down"></i>
                </a>
                <div class="dropdown__content">
                    <a href="#" class="dropdown__item">
                        <i class="fas fa-user-circle"></i> Tài Khoản Play 1
                    </a>
                    <a href="#" class="dropdown__item">
                        <i class="fas fa-random"></i> Tài Khoản Play 2
                    </a>
                    <a href="#" class="dropdown__item">
                        <i class="fas fa-concierge-bell"></i> Tài Khoản Play 3
                    </a>
                    <a href="#" class="dropdown__item">
                        <i class="fas fa-gamepad"></i> Dịch Vụ Game
                    </a>
                </div>
            </div>

            <!-- Lịch Sử Dropdown -->
            <div class="menu__dropdown">
                <a href="#" class="menu__item menu__item--dropdown">
                    <i class="fas fa-history"></i> LỊCH SỬ <i class="fas fa-chevron-down"></i>
                </a>
                <div class="dropdown__content">
                    <a href="#" class="dropdown__item">
                        <i class="fas fa-shopping-bag"></i> Lịch Sử Mua Tài Khoản
                    </a>
                    <a href="#" class="dropdown__item">
                        <i class="fas fa-dice"></i> Lịch Sử Mua Random
                    </a>
                    <a href="#" class="dropdown__item">
                        <i class="fas fa-key"></i> Lịch Sử Mua Key
                    </a>
                    <a href="#" class="dropdown__item">
                        <i class="fas fa-cogs"></i> Lịch Sử Dịch Vụ
                    </a>
                    <a href="#" class="dropdown__item">
                        <i class="fas fa-chart-line"></i> Biến Động Số Dư
                    </a>
                </div>
            </div>

            <a href="#" class="menu__item"><i class="fas fa-newspaper"></i> BÀI VIẾT</a>

            @if (Auth::check() && Auth()->user()->role === 'admin')
                <a href="{{ route('admin.index') }}" target="_blank" class="menu__item"><i class="fas fa-user-shield"></i>
                    ADMIN</a>
            @endif

            <button class="btn btn--icon theme-toggle-btn">
                <span class="theme-icon-light"><i class="fas fa-moon"></i></span>
                <span class="theme-icon-dark"><i class="fas fa-sun"></i></span>
            </button>

            @guest
                <a href="{{ route('login') }}" class="btn btn--outline"><i class="fa-solid fa-user"></i> Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn btn--outline"><i class="fa-solid fa-key"></i> Đăng ký</a>
            @else
                <div class="navbar__user-info">
                    <div class="user-info-text">
                        <span class="user-greeting">Xin chào,
                            <a href="{{ route('profile.index') }}"
                                class="nav-username">{{ \Illuminate\Support\Str::limit(Auth::user()->username, 10) }}</a>
                        </span>
                        <span class="balance-amount">{{ number_format(Auth::user()->balance) }} VND</span>
                    </div>

                    <button id="pcBtnDeposit" class="btn btn-deposit">
                        <i class="fa-solid fa-plus-circle"></i> NẠP TIỀN
                    </button>

                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn--icon logout-btn">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            @endguest

        </div>

        <!-- Mobile Actions -->
        <div class="navbar__actions">
            <button class="btn btn--icon theme-toggle-btn">
                <span class="theme-icon-light"><i class="fas fa-moon"></i></span>
                <span class="theme-icon-dark"><i class="fas fa-sun"></i></span>
            </button>

            @auth
                <button id="btnDeposit" class="btn btn-deposit" style="padding: 8px 12px;">
                    <i class="fa-solid fa-wallet"></i>
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn--icon">
                    <i class="fas fa-sign-in-alt"></i>
                </a>
            @endauth

            <button class="btn btn--icon menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</nav>

<!-- Modals & Dynamic Content -->
@include('user.partials.deposit-modals')

<div class="mobile-dropdown glass" id="mobileDropdown">
    <div class="dropdown-group">
        <div class="dropdown-title">Tài khoản</div>
        @auth
            <a href="{{ route('profile.index') }}"><i class="fas fa-user-circle"></i> ID:
                <strong>{{ Auth::user()->id }}</strong></a>
            <a href="{{ route('profile.index') }}"><i class="fas fa-wallet"></i> Số Dư: <strong
                    style="color: var(--second-color);">{{ number_format(Auth::user()->balance) }}đ</strong></a>
            <a href="{{ route('discount.codes') }}" style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-crown"></i>
                <strong>{{ $rank['name'] }}</strong>
                <img src="{{ asset($rank['image']) }}" alt="{{ $rank['name'] }}" style="width: 20px; height: 20px;">
            </a>
            @if (Auth()->user()->role === 'seller' || Auth()->user()->role === 'admin')
                <a href="{{ route('seller.dashboard') }}" target="_blank"><i class="fas fa-shield-alt"></i> Bảng Điều Khiển</a>
            @endif
        @else
            <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> ĐĂNG NHẬP</a>
            <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> ĐĂNG KÝ</a>
        @endauth
    </div>

    <div class="dropdown-group">
        <div class="dropdown-title">DANH MỤC</div>
        <a href="#"><i class="fas fa-user-circle"></i> Tài Khoản</a>
        <a href="#"><i class="fas fa-random"></i> Play 123</a>
        <a href="#"><i class="fas fa-concierge-bell"></i> Dịch Vụ</a>
        <a href="#"><i class="fas fa-gamepad"></i> Game</a>
    </div>

    <div class="dropdown-group">
        <div class="dropdown-title">LỊCH SỬ</div>
        <a href="#"><i class="fas fa-shopping-bag"></i> Lịch Sử Mua Tài Khoản</a>
        <a href="#"><i class="fas fa-dice"></i> Lịch Sử Mua Random</a>
        <a href="#"><i class="fas fa-key"></i> Lịch Sử Mua Key</a>
        <a href="#"><i class="fas fa-cogs"></i> Lịch Sử Dịch Vụ</a>
        <a href="#"><i class="fas fa-chart-line"></i> Biến Động Số Dư</a>
    </div>

    <div class="dropdown-group">
        <a href="#"><i class="fas fa-newspaper"></i> BÀI VIẾT</a>
    </div>

    @auth
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 10px;">
            @csrf
            <button type="submit" class="btn btn--danger-outline" style="width: 100%;">ĐĂNG XUẤT</button>
        </form>
    @endauth
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navbar = document.querySelector('.navbar');
        const menuToggle = document.querySelector('.menu-toggle');
        const dropdown = document.getElementById('mobileDropdown');
        const themeBtns = document.querySelectorAll('.theme-toggle-btn');

        // Theme Toggle - CSS handles icon and logo switching automatically
        themeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const isDark = document.documentElement.classList.toggle('dark-mode');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        });

        // Mobile Menu
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target) && !menuToggle.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    });
</script>
