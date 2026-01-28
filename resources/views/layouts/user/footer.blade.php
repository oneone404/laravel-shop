<!-- Footer -->
<footer class="footer">
    <div class="footer__top">
        <div class="container">
            <div class="footer__grid">
                <!-- Column 1: Brand -->
                <div class="footer__col brand-col">
                    <a href="/" class="footer__logo">
                        {{-- Light mode logo --}}
                        <img class="logo-light" src="{{ config_get('site_logo') }}" alt="{{ config_get('site_name') }}">
                        {{-- Dark mode logo --}}
                        <img class="logo-dark" src="{{ config_get('site_logo_dark') ?: config_get('site_logo') }}"
                            alt="{{ config_get('site_name') }}">
                    </a>
                    <p class="footer__desc">
                        {{ config_get('site_description', 'Hệ thống cung cấp tài khoản và dịch vụ game hàng đầu Việt Nam. Uy tín - Nhanh chóng - An toàn.') }}
                    </p>
                    <div class="footer__socials">
                        <a href="https://facebook.com/" class="social-btn facebook" target="_blank"><i
                                class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://zalo.me/0967699321" class="social-btn zalo" target="_blank">Zalo</a>
                        <a href="https://youtube.com/" class="social-btn youtube" target="_blank"><i
                                class="fa-brands fa-youtube"></i></a>
                        <a href="https://tiktok.com/" class="social-btn tiktok" target="_blank"><i
                                class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="footer__col">
                    <h4 class="footer__title">Liên Kết Nhanh</h4>
                    <ul class="footer__links">
                        <li><a href="/">Trang Chủ</a></li>
                        <li><a href="{{ route('category.show-all') }}">Nick Game</a></li>
                        <li><a href="{{ route('service.show-all') }}">Dịch Vụ</a></li>
                        <li><a href="{{ route('profile.transaction-history') }}">Nạp Tiền</a></li>
                    </ul>
                </div>

                <!-- Column 3: Support -->
                <div class="footer__col">
                    <h4 class="footer__title">Chăm Sóc Khách Hàng</h4>
                    <ul class="footer__links">
                        <li><a href="#">Điều Khoản Sử Dụng</a></li>
                        <li><a href="#">Chính Sách Bảo Mật</a></li>
                        <li><a href="#">Hướng Dẫn Mua Hàng</a></li>
                        <li><a href="#">Khiếu Nại & Góp Ý</a></li>
                    </ul>
                </div>

                <!-- Column 4: Hotline -->
                <div class="footer__col contact-col">
                    <h4 class="footer__title">Hỗ Trợ 24/7</h4>
                    <div class="footer__contact-item">
                        <i class="fa-solid fa-headset"></i>
                        <div>
                            <span>Hotline:</span>
                            <strong>0967.699.xxx</strong>
                        </div>
                    </div>
                    <div class="footer__contact-item">
                        <i class="fa-solid fa-envelope"></i>
                        <div>
                            <span>Email:</span>
                            <strong>support@onedz.vn</strong>
                        </div>
                    </div>
                    <div class="footer__payment-methods">
                        <img src="{{ asset('assets/images/payment/momo.svg') }}" alt="Momo" class="payment-icon">
                        <img src="{{ asset('assets/images/payment/vnpay.svg') }}" alt="VNPay" class="payment-icon">
                        <img src="{{ asset('assets/images/payment/card.svg') }}" alt="Card" class="payment-icon">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer__bottom">
        <div class="container">
            <div class="footer__bottom-content">
                <p class="copyright">
                    &copy; {{ date('Y') }} <strong>{{ strtoupper(request()->getHost()) }}</strong>. All rights reserved.
                </p>
                <p class="designer">
                    Designed with <i class="fa-solid fa-heart" style="color: #ff4d4d;"></i> by Cyber-Lux
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Footer Specific Enhancements */
    .footer {
        margin-top: 100px;
    }

    /* Redefining styles in global.css to ensure consistency */
</style>
