<div class="profile-sidebar">
    <div class="sidebar-header">
    </div>
    <ul class="sidebar-menu">
        @if (config_get('payment.card.active', true))
            <li class="sidebar-item {{ request()->routeIs('profile.deposit-card') ? 'active' : '' }}">
                <a href="{{ route('profile.deposit-card') }}" class="sidebar-link">
                    <i class="fa-solid fa-credit-card"></i> NẠP TIỀN THẺ CÀO
                </a>
            </li>
        @endif
        <li class="sidebar-item {{ request()->routeIs('profile.deposit-atm') ? 'active' : '' }}">
            <a href="{{ route('profile.deposit-atm') }}" class="sidebar-link">
                <i class="fa-solid fa-money-bill-transfer"></i> NẠP TIỀN NGÂN HÀNG
            </a>
        </li>
    </ul>
</div>
