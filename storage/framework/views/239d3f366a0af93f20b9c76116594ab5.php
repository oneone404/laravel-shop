
<?php $__env->startSection('title', 'Trang Chủ'); ?>
<?php $__env->startSection('content'); ?>

  <div class="hero-wrapper">
    <div class="hero-left">
      <div class="banner-container">
        <div class="banner-border">
          <div class="banner-frame">
            <img src="<?php echo e(config_get('site_banner')); ?>" alt="<?php echo e(config_get('site_description')); ?>" class="banner-photo">

            <!-- Professional Overlays -->
            <!-- Live System Badge -->
            <div class="banner-badge system-live-badge">
              <span class="live-pulse"></span>
              <span class="live-text"><?php echo e(rand(450, 950)); ?> Online</span>
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
                  <span class="stat-label"><?php echo e(number_format($totalTransactions + 200000)); ?> Giao Dịch</span>
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
                <?php $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <span class="marquee-item">
                    <i class="fas fa-check-circle text-success" style="font-size: 0.9em;"></i>
                    <span class="user-highlight">ID <?php echo e($transaction->user->id ?? '?'); ?></span>
                    <strong><?php echo e($transaction->type == 'deposit' ? 'Nạp Tiền' : 'Mua Sản Phẩm'); ?></strong>
                    <span class="time-ago">(<?php echo e($transaction->created_at->diffForHumans()); ?>)</span>
                    <span class="separator">|</span>
                  </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(count($recentTransactions) == 0): ?>
                  <span class="marquee-item">Chào Mừng Bạn Đến Với Shop Acc ACCONE.VN!</span>
                <?php endif; ?>
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
            <?php if(isset($pendingOrdersCount) && $pendingOrdersCount > 0): ?>
              <span class="notice-badge"><?php echo e($pendingOrdersCount); ?></span>
            <?php endif; ?>
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
    
    <?php if($categories_play->count()): ?>
      <section class="menu">
        <div class="container no-padding">
          <header class="menu__header">
            <h2 class="menu__header__title">TÀI KHOẢN PLAY 1</h2>
          </header>
          <div class="product-grid">
            <?php $__currentLoopData = $categories_play; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php echo $__env->make('user.partials.category-card', ['category' => $category], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    
    <?php if($categories_clone->count()): ?>
      <section class="menu">
        <div class="container no-padding">
          <header class="menu__header">
            <h2 class="menu__header__title">TÀI KHOẢN PLAY 2</h2>
          </header>
          <div class="product-grid">
            <?php $__currentLoopData = $categories_clone; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php echo $__env->make('user.partials.category-card', ['category' => $category], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    
    <?php if($categories_random->count()): ?>
      <section class="menu">
        <div class="container no-padding">
          <header class="menu__header">
            <h2 class="menu__header__title">TÀI KHOẢN PLAY 3</h2>
          </header>
          <div class="product-grid">
            <?php $__currentLoopData = $categories_random; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php echo $__env->make('user.partials.category-card', ['category' => $category], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      </section>
    <?php endif; ?>
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
          <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($service->active): ?>
              <a href="<?php echo e(route('service.show', ['slug' => $service->slug])); ?>" class="product-card">
                <div class="product-image-wrapper image-wrapper">
                  <img src="<?php echo e(asset('images/loader.svg')); ?>" alt="Loading..." class="image-loader">
                  <img src="<?php echo e($service->thumbnail); ?>" alt="<?php echo e($service->name); ?>" class="product-image" loading="lazy"
                    decoding="async" />
                </div>
                <h2 class="product-name"><?php echo e($service->name); ?></h2>
                <div class="product-stats">
                  <span class="status-label">Trạng Thái:</span>
                  <span class="status-ready">Sẵn Sàng</span>
                </div>

                <?php if(config('app.use_image_button')): ?>
                  <div class="product-action-img">
                    <img src="<?php echo e(asset('assets/img/button/buttonshowall.png')); ?>" alt="XEM CHI TIẾT"
                      class="product-action-image">
                  </div>
                <?php else: ?>
                  <p class="product-action">XEM CHI TIẾT</p>
                <?php endif; ?>
              </a>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </section>
  </div>
  <?php if(config_get('welcome_modal', false)): ?>
    <div id="welcomeModal" class="welcome-modal-overlay" style="display: none; padding: 50px 10px;">
      <div class="welcome-modal" style="max-width: 600px; margin: auto;">
        <div class="welcome-modal__header">
          <h3 class="welcome-modal__title">Thông báo</h3>
          <button class="welcome-modal__close">&times;</button>
        </div>
        <div class="welcome-modal__body">
          <img src="<?php echo e(config_get('site_logo')); ?>" alt="<?php echo e(config_get('site_description')); ?>" class="welcome-modal__icon">
          <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="welcome-modal__feature-item"
              style="display: flex; align-items: center; padding-bottom: 5px; margin-bottom: 5px; font-size: 14px;">
              <div class="welcome-modal__feature-icon icon-box <?php echo e($notification->thumbnail ? 'icon-image' : 'icon-fa'); ?>">
                <?php if($notification->thumbnail): ?>
                  <img src="<?php echo e(asset($notification->thumbnail)); ?>" alt="icon">
                <?php else: ?>
                  <i class="fas <?php echo e($notification->class_favicon); ?>"></i>
                <?php endif; ?>
              </div>
              <div class="welcome-modal__feature-text" style="flex: 1;">
                <?php echo $notification->content; ?>

              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="welcome-modal__footer">
          <button class="welcome-modal__btn" id="welcomeModalBtn">
            <i class="fas fa-check-circle"></i> OK
          </button>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/home.blade.php ENDPATH**/ ?>