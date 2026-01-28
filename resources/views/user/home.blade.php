@extends('layouts.user.app')
@section('title', 'Trang Chủ')
@section('content')

  <div class="hero-wrapper">
    <div class="hero-left">
      <div class="banner-container">
        <div class="banner-border">
          <div class="banner-frame">
            <img src="{{ config_get('site_banner') }}" alt="{{ config_get('site_description') }}" class="banner-photo">

            <!-- Professional Overlays -->
            <!-- Live System Badge -->
            <div class="banner-badge system-live-badge">
              <span class="live-pulse"></span>
              <span class="live-text">{{ rand(450, 950) }} Online</span>
            </div>

            <!-- Premium Stats Grid -->
            <div class="banner-stats-grid">
              <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-bolt"></i></div>
                <div class="stat-text">
                  <span class="stat-value">Tự Động</span>
                  <span class="stat-label">100% Hệ Thống</span>
                </div>
              </div>
              <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="stat-text">
                  <span class="stat-value">Uy Tín</span>
                  <span class="stat-label">{{ number_format($totalTransactions + 200000) }} Giao Dịch</span>
                </div>
              </div>
              <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-tag"></i></div>
                <div class="stat-text">
                  <span class="stat-value">Giá Rẻ</span>
                  <span class="stat-label">Ưu Đãi Mỗi Ngày</span>
                </div>
              </div>
            </div>

            <div class="banner-content">
              <h1 class="banner-title">ACCONE.VN</h1>
              <p class="banner-subtitle">Hệ Thống Shop Acc & Dịch Vụ Game Tự Động 24/7</p>
            </div>
          </div>
        </div>

        <!-- Simplified Announcement Bar -->
        <div class="banner-announcement">
          <div class="announcement-wrapper">
            <i class="fas fa-bullhorn"></i>
            <div class="announcement-marquee">
              <div class="marquee-content">
                @foreach($recentTransactions as $transaction)
                  <span class="marquee-item">
                    <i class="fas fa-check-circle text-success" style="font-size: 0.9em;"></i>
                    <span class="user-highlight">ID {{ $transaction->user->id ?? '?' }}</span>
                    <strong>{{ $transaction->type == 'deposit' ? 'Nạp Tiền' : 'Mua Sản Phẩm' }}</strong>
                    <span class="time-ago">({{ $transaction->created_at->diffForHumans() }})</span>
                    <span class="separator">|</span>
                  </span>
                @endforeach
                @if(count($recentTransactions) == 0)
                  <span class="marquee-item">Chào Mừng Bạn Đến Với Shop Acc ACCONE.VN!</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="hero-right">
      <section class="menu special-menu">
        <div class="mobile-menu-title">
          <span>MENU NHANH</span>
        </div>
        <div class="transaction__list">
          <!-- Item 1: Tải Game -->
          <a href="/hacks/1" class="transaction__item">
            <div class="item-icon-wrapper">
              <i class="fa-solid fa-cloud-arrow-down"></i>
            </div>
            <div class="item-info">
              <span class="item-title">TẢI GAME</span>
              <span class="item-sub">Bản mới nhất</span>
            </div>
          </a>

          <!-- Item 2: Mua Key -->
          <a href="/muakey" class="transaction__item">
            <div class="item-icon-wrapper">
              <i class="fa-solid fa-key"></i>
            </div>
            <div class="item-info">
              <span class="item-title">MUA KEY</span>
              <span class="item-sub">Active Tool</span>
            </div>
          </a>

          <!-- Item 3: Nạp Tiền -->
          <a href="#" id="heroBtnDeposit" class="transaction__item">
            <div class="item-icon-wrapper">
              <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="item-info">
              <span class="item-title">NẠP TIỀN</span>
              <span class="item-sub">Auto Bank</span>
            </div>
          </a>

          <!-- Item 4: Tài Khoản -->
          <a href="/show" class="transaction__item">
            @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
              <span class="notice-badge">{{ $pendingOrdersCount }}</span>
            @endif
            <div class="item-icon-wrapper">
              <i class="fa-solid fa-user-gear"></i>
            </div>
            <div class="item-info">
              <span class="item-title">TÀI KHOẢN</span>
              <span class="item-sub">Kho Acc VIP</span>
            </div>
          </a>
        </div>
      </section>
    </div>
  </div>
  <!-- Main Tab Navigation -->
  <div class="main-tabs-container">
    <div class="tab-scroller">
      <button class="main-tab-btn active" data-target="accounts-tab">
        <i class="fas fa-user-circle"></i> TÀI KHOẢN
      </button>
      <button class="main-tab-btn" data-target="services-tab">
        <i class="fas fa-tags"></i> DỊCH VỤ
      </button>
    </div>
  </div>

  <!-- Accounts Tab Content -->
  <div id="accounts-tab" class="main-tab-pane active">
    {{-- PLAY --}}
    @if ($categories_play->count())
      <section class="menu">
        <div class="container no-padding">
          <header class="menu__header">
            <h2 class="menu__header__title">TÀI KHOẢN PLAY 1</h2>
          </header>
          <div class="product-grid">
            @foreach ($categories_play as $category)
              @include('user.partials.category-card', ['category' => $category])
            @endforeach
          </div>
        </div>
      </section>
    @endif

    {{-- CLONE --}}
    @if ($categories_clone->count())
      <section class="menu">
        <div class="container no-padding">
          <header class="menu__header">
            <h2 class="menu__header__title">TÀI KHOẢN PLAY 2</h2>
          </header>
          <div class="product-grid">
            @foreach ($categories_clone as $category)
              @include('user.partials.category-card', ['category' => $category])
            @endforeach
          </div>
        </div>
      </section>
    @endif

    {{-- RANDOM --}}
    @if ($categories_random->count())
      <section class="menu">
        <div class="container no-padding">
          <header class="menu__header">
            <h2 class="menu__header__title">TÀI KHOẢN PLAY 3</h2>
          </header>
          <div class="product-grid">
            @foreach ($categories_random as $category)
              @include('user.partials.category-card', ['category' => $category])
            @endforeach
          </div>
        </div>
      </section>
    @endif
  </div>

  <!-- Services Tab Content -->
  <div id="services-tab" class="main-tab-pane">
    <!-- Menu mục dịch vụ game -->
    <section class="menu">
      <div class="container no-padding">
        <header class="menu__header">
          <h2 class="menu__header__title">DỊCH VỤ GAME</h2>
        </header>
        <div class="product-grid">
          @foreach ($services as $service)
            @if ($service->active)
              <a href="{{ route('service.show', ['slug' => $service->slug]) }}" class="product-card">
                <div class="product-image-wrapper image-wrapper">
                  <img src="{{ asset('images/loader.svg') }}" alt="Loading..." class="image-loader">
                  <img src="{{ $service->thumbnail }}" alt="{{ $service->name }}" class="product-image" loading="lazy"
                    decoding="async" />
                </div>
                <h2 class="product-name">{{ $service->name }}</h2>
                <div class="product-stats">
                  <span class="status-label">Trạng Thái:</span>
                  <span class="status-ready">Sẵn Sàng</span>
                </div>

                @if (config('app.use_image_button'))
                  <div class="product-action-img">
                    <img src="{{ asset('assets/img/button/buttonshowall.png') }}" alt="XEM CHI TIẾT"
                      class="product-action-image">
                  </div>
                @else
                  <p class="product-action">XEM CHI TIẾT</p>
                @endif
              </a>
            @endif
          @endforeach
        </div>
      </div>
    </section>
  </div>
  @if (config_get('welcome_modal', false))
    <div id="welcomeModal" class="welcome-modal-overlay" style="display: none; padding: 50px 10px;">
      <div class="welcome-modal" style="max-width: 600px; margin: auto;">
        <div class="welcome-modal__header">
          <h3 class="welcome-modal__title">Thông báo</h3>
          <button class="welcome-modal__close">&times;</button>
        </div>
        <div class="welcome-modal__body">
          <img src="{{ config_get('site_logo') }}" alt="{{ config_get('site_description') }}" class="welcome-modal__icon">
          @foreach ($notifications as $notification)
            <div class="welcome-modal__feature-item"
              style="display: flex; align-items: center; padding-bottom: 5px; margin-bottom: 5px; font-size: 14px;">
              <div class="welcome-modal__feature-icon icon-box {{ $notification->thumbnail ? 'icon-image' : 'icon-fa' }}">
                @if($notification->thumbnail)
                  <img src="{{ asset($notification->thumbnail) }}" alt="icon">
                @else
                  <i class="fas {{ $notification->class_favicon }}"></i>
                @endif
              </div>
              <div class="welcome-modal__feature-text" style="flex: 1;">
                {!! $notification->content !!}
              </div>
            </div>
          @endforeach
        </div>
        <div class="welcome-modal__footer">
          <button class="welcome-modal__btn" id="welcomeModalBtn">
            <i class="fas fa-check-circle"></i> OK
          </button>
        </div>
      </div>
    </div>
  @endif
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('welcomeModal');
      const btnOK = document.getElementById('welcomeModalBtn');
      const btnClose = document.querySelector('.welcome-modal__close');

      function closeModal() {
        if (!modal) return;
        modal.style.opacity = '0';
        setTimeout(() => {
          modal.style.display = 'none';
          document.body.style.overflow = '';
        }, 250);
      }

      btnOK?.addEventListener('click', closeModal);
      btnClose?.addEventListener('click', closeModal);

      // bấm ngoài overlay cũng tắt
      modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
      });

      // ESC cũng tắt
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
      });

      // hiện modal nhẹ fade in
      setTimeout(() => {
        if (!modal) return;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        modal.style.opacity = '1';
      }, 350);
    });

    document.addEventListener("DOMContentLoaded", () => {
      document.querySelectorAll(".image-wrapper").forEach(wrapper => {
        const img = wrapper.querySelector(".product-image");
        const loader = wrapper.querySelector(".image-loader");

        const showImage = () => {
          img.classList.add("loaded");
          loader?.classList.add("hide");
          setTimeout(() => loader?.remove(), 300);
        };

        if (img.complete) showImage();
        else {
          img.addEventListener("load", showImage);
          img.addEventListener("error", () => loader?.remove());
        }
      });
    });

    // Main Tab Switching Logic
    document.addEventListener('DOMContentLoaded', () => {
      const tabBtns = document.querySelectorAll('.main-tab-btn');
      const tabPanes = document.querySelectorAll('.main-tab-pane');

      tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          const target = btn.getAttribute('data-target');

          // Update Buttons
          tabBtns.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');

          // Update Panes
          tabPanes.forEach(pane => {
            pane.classList.remove('active');
            if (pane.id === target) {
              pane.classList.add('active');
            }
          });

          // Scroll to top of categories smoothly if needed
          // window.scrollTo({ top: btn.parentElement.offsetTop - 100, behavior: 'smooth' });
        });
      });
    });
  </script>
  <script>
    function updateMiniTime() {
      const now = new Date();
      const formatted =
        now.toLocaleDateString('vi-VN') + ' ' +
        now.toLocaleTimeString('vi-VN');

      document.getElementById('miniTime').textContent = " - " + formatted;
    }

    // Gọi 1 lần khi load
    updateMiniTime();

    // Cập nhật mỗi giây
    setInterval(updateMiniTime, 1000);
  </script>

@endpush
