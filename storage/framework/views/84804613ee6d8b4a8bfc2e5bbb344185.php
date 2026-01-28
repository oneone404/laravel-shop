<?php $__env->startSection('title', $title); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => ''.e($title).'','description' => ''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('hero-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\HeroHeader::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0)): ?>
<?php $attributes = $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0; ?>
<?php unset($__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0)): ?>
<?php $component = $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0; ?>
<?php unset($__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0); ?>
<?php endif; ?>


    <div class="service">
        <div class="container">
            <!-- Thông báo lỗi và thành công -->
            <?php if(session('error')): ?>
                <div class="service__alert service__alert--error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo e(session('error')); ?></span>
                    <button type="button" class="service__alert-close">&times;</button>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="service__alert service__alert--success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo e(session('success')); ?></span>
                    <button type="button" class="service__alert-close">&times;</button>
                </div>
            <?php endif; ?>



            <!-- Form đặt dịch vụ -->
            <div class="service__form">
                <?php if($service->type == 'leveling'): ?>
                <div class="alert-banner">
                    Yêu Cầu Có Gói Bán Nhanh
                </div>
            <?php endif; ?>
                <h3 class="service__form-title">THÔNG TIN ĐƠN HÀNG</h3>

                <?php if($service->type == 'leveling'): ?>
<form action="<?php echo e(route('service.order', $service->slug)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="service_id" value="<?php echo e($service->id); ?>">
    <input type="hidden" name="giftcode" value="<?php echo e(old('giftcode')); ?>">

    <div class="service__form-row">
        <div class="service__form-group">
  <label><i class="fas fa-cogs"></i> Chọn Gói Dịch Vụ</label>

  
  <input type="hidden" id="package_id" name="package_id" value="<?php echo e(old('package_id')); ?>">


<div class="svc-picked svc-picked--thumb" id="svcPickedBox">
  <img id="svcPickedThumb" class="svc-picked-thumb" src="<?php echo e($service->thumbnail); ?>" alt="Gói Đã Chọn">
  <div class="svc-picked-info">
    <div class="svc-picked-name" id="svcPickedName">Chọn Gói Dịch Vụ</div>
    <div class="svc-picked-price">
      <span id="svcPickedPrice">0</span> <span class="svc-currency">VNĐ</span>
    </div>
    <div class="svc-picked-meta" id="svcPickedMeta">...</div>
  </div>
  <button type="button" id="svcOpenPkgModal" class="svc-pkg-trigger">
    <i class="fas fa-images"></i> Chọn Gói
  </button>
</div>

  <?php $__errorArgs = ['package_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="service__form-error"><?php echo e($message); ?></div>
  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>


        <div class="service__form-group">
            <label for="server"><i class="fas fa-server"></i> Máy Chủ</label>
            <select id="server" name="server" class="service__form-control" required>
                <option value="PTVNG">VNG</option>
                <option value="PTGLOBAL">GLOBAL</option>
            </select>
            <?php $__errorArgs = ['server'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="service__form-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <div class="service__form-row">
        <div class="service__form-group">
            <label for="game_account"><i class="fas fa-user"></i> Nhập Tài Khoản</label>
            <input type="text" id="game_account" name="game_account" class="service__form-control"
                value="<?php echo e(old('game_account')); ?>" placeholder="Nhập Tài Khoản" required>
            <?php $__errorArgs = ['game_account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="service__form-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="service__form-group">
            <label for="game_password"><i class="fas fa-lock"></i> Nhập Password</label>
            <input type="text" id="game_password" name="game_password" class="service__form-control"
                value="<?php echo e(old('game_password')); ?>" placeholder="Nhập Password" required>
            <?php $__errorArgs = ['game_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="service__form-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <div class="service__form-row">
        <div class="service__form-group">
            <label for="login_game"><i class="fas fa-server"></i> Cổng Đăng Nhập</label>
            <select id="login_game" name="login_game" class="service__form-control" required>
                <option value="ZINGID">Zing ID</option>
                <option value="FACEBOOK">Facebook</option>
                <option value="GOOGLE">Google</option>
                <option value="PLAYNOW">Chơi Ngay</option>
                <option value="MAIL">Gmail</option>
            </select>
            <?php $__errorArgs = ['server'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="service__form-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div id="discount-feedback" class="service__discount-feedback" style="display: none;"></div>
<div class="svc-order-bar svc-order-bar--inline">
  <div class="svc-order-info">
    <div class="svc-mini-row">
      <span class="svc-chip">Gói:</span>
      <span id="svcDesktopPkgName" class="svc-pkg-name">Chưa Chọn</span>
    </div>
    <div class="svc-mini-row svc-amount-wrap">
      <span class="svc-amount-value"><span id="amount">0</span> <span class="svc-currency">VNĐ</span></span>
    </div>
  </div>

  <?php if(Auth::check()): ?>
    <button type="submit" class="svc-btn">
      <i class="fas fa-check-circle"></i> THANH TOÁN
    </button>
  <?php else: ?>
    <a href="<?php echo e(route('login')); ?>" class="svc-btn svc-btn--ghost">
      <i class="fas fa-ban"></i> ĐĂNG NHẬP
    </a>
  <?php endif; ?>
</div>

</form>

<?php elseif($service->type == 'pay-game'): ?>
    <form action="<?php echo e(route('service.order', $service->slug)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="service_id" value="<?php echo e($service->id); ?>">

    <div class="service__form-row">
        <div class="service__form-group">
  <label><i class="fas fa-cogs"></i> Chọn Gói Dịch Vụ</label>

  
  <input type="hidden" id="package_id" name="package_id" value="<?php echo e(old('package_id')); ?>">


<div class="svc-picked svc-picked--thumb" id="svcPickedBox">
  <img id="svcPickedThumb" class="svc-picked-thumb" src="<?php echo e($service->thumbnail); ?>" alt="Gói Đã Chọn">
  <div class="svc-picked-info">
    <div class="svc-picked-name" id="svcPickedName">Chọn Gói Dịch Vụ</div>
    <div class="svc-picked-price">
      <span id="svcPickedPrice">0</span> <span class="svc-currency">VNĐ</span>
    </div>
    <div class="svc-picked-meta" id="svcPickedMeta">...</div>
  </div>
  <button type="button" id="svcOpenPkgModal" class="svc-pkg-trigger">
    <i class="fas fa-images"></i> Chọn Gói
  </button>
</div>

  <?php $__errorArgs = ['package_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="service__form-error"><?php echo e($message); ?></div>
  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

        <div class="service__form-group">
            <label for="server"><i class="fas fa-server"></i> Máy Chủ</label>
            <select id="server" name="server" class="service__form-control" required>
                <option value="PTVNG">VNG</option>
            </select>
            <?php $__errorArgs = ['server'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="service__form-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="service__form-row">
        <div class="service__form-group">
            <label for="id_account"><i class="fas fa-user"></i> ID Tài Khoản</label>
            <input type="text" id="id_account" name="id_account" class="service__form-control"
                value="<?php echo e(old('id_account')); ?>" placeholder="xxxx-xxxx-xxxx" required>
            <?php $__errorArgs = ['id_account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="service__form-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="service__form-group">
            <label for="account_name"><i class="fas fa-user-check"></i> Check Tên Game</label>

            <div class="input-wrap">
                <input type="text" id="account_name" name="account_name" class="service__form-control"
                    placeholder="Vui Lòng Nhập ID Ở Trên" readonly>
                <!-- Spinner nằm trong cùng form-group, chồng lên mép phải input -->
                <span id="account_name_spinner" class="spinner" hidden>
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </div>
        </div>

    </div>

    <input type="hidden" name="giftcode" value="<?php echo e(old('giftcode')); ?>">

    <div id="discount-feedback" class="service__discount-feedback" style="display: none;"></div>
<div class="svc-order-bar svc-order-bar--inline">
  <div class="svc-order-info">
    <div class="svc-mini-row">
      <span class="svc-chip">Gói:</span>
      <span id="svcDesktopPkgName" class="svc-pkg-name">Chưa Chọn</span>
    </div>
    <div class="svc-mini-row svc-amount-wrap">
      <span class="svc-amount-value"><span id="amount">0</span> <span class="svc-currency">VNĐ</span></span>
    </div>
  </div>

  <?php if(Auth::check()): ?>
    <button type="submit" class="svc-btn">
      <i class="fas fa-check-circle"></i> THANH TOÁN
    </button>
  <?php else: ?>
    <a href="<?php echo e(route('login')); ?>" class="svc-btn svc-btn--ghost">
      <i class="fas fa-ban"></i> ĐĂNG NHẬP
    </a>
  <?php endif; ?>
</div>

</form>
<?php endif; ?>
<div id="svcPkgBackdrop" class="svc-pkg-backdrop" aria-hidden="true">
  <div class="svc-pkg-modal" role="dialog" aria-modal="true" aria-labelledby="svcPkgTitle">
    <div class="svc-pkg-head">
      <div id="svcPkgTitle" class="svc-pkg-title">Chọn Gói Dịch Vụ</div>
      <button type="button" id="svcPkgClose" class="svc-pkg-close" aria-label="Đóng">×</button>
    </div>
    <div class="svc-pkg-body">
      <div class="svc-pkg-search">
        <input type="text" id="svcPkgSearch" placeholder="Tìm Theo Tên Gói...">
      </div>

      <div id="svcPkgGrid" class="svc-pkg-grid">
<?php $__currentLoopData = $service->packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
$thumb = $package->thumbnail
    ? asset('storage/' . $package->thumbnail)
    : ($package->image ?? asset('images/package-default.png'));
$priceRaw = $service->type === 'pay-game'
    ? ($package->price_after_discount ?? $package->price)
    : $package->price;
?>

<div class="svc-pkg-card"
     data-id="<?php echo e($package->id); ?>"
     data-index="<?php echo e($loop->iteration); ?>"
     data-name="<?php echo e($package->name); ?>"
     data-price="<?php echo e($priceRaw); ?>">
  <img class="svc-pkg-thumb"
       src="<?php echo e($thumb); ?>"
       alt="<?php echo e($package->name); ?>"
       loading="lazy" decoding="async" fetchpriority="low"
       width="320" height="200">
  <div class="svc-pkg-content">
    <div class="svc-pkg-name"><?php echo e($package->name); ?></div>
    <div class="svc-pkg-price"><?php echo e(number_format($priceRaw, 0, ',', ',')); ?> VNĐ</div>
    
    <div class="svc-pkg-meta"><span>#<?php echo e($loop->iteration); ?></span></div>
    <button type="button" class="svc-pkg-pick">Chọn Gói Này</button>
  </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      </div>
    </div>
  </div>
</div>

<div id="svcMobileSticky" class="svc-mobile-sticky" aria-hidden="true">
  <div class="svc-mobile-inner">
    <div class="svc-mobile-info">
      <div class="svc-mini-row">
        <span class="svc-chip">Gói:</span>
        <span id="svcMobilePkgName" class="svc-mobile-pkg-name">Chưa Chọn</span>
      </div>
      <div class="svc-mini-row svc-mobile-amount">
        <span id="svcMobileAmount" class="svc-amount-value">0</span>
        <span class="svc-currency">VNĐ</span>
      </div>
    </div>

    
    <?php if(Auth::check()): ?>
      <button id="svcMobileSubmit" type="button" class="svc-mobile-btn">
        <i class="fas fa-check-circle"></i> <span>THANH TOÁN</span>
      </button>
    <?php else: ?>
      <a href="<?php echo e(route('login')); ?>" id="svcMobileLogin" class="svc-mobile-btn svc-mobile-btn--ghost">
        <i class="fas fa-ban"></i> <span>ĐĂNG NHẬP</span>
      </a>
    <?php endif; ?>
  </div>
</div>


            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const isMobile = () => window.matchMedia('(max-width: 768px)').matches;

  const forms      = document.querySelectorAll('.service__form form');
  const activeForm = forms[0] || null;
  const pageWrap   = document.querySelector('.service .container') || document.body;

  // pay-game name check
  const idAccountInput   = document.getElementById('id_account');
  const accountNameInput = document.getElementById('account_name');
  const spinnerIcon      = document.getElementById('account_name_spinner');

  // package (không còn <select>)
  const packageIdInput   = document.getElementById('package_id'); // hidden input name="package_id"
  const pickedNameEl     = document.getElementById('svcPickedName');
  const pickedPriceEl    = document.getElementById('svcPickedPrice');

  // amount & summaries
  const amountSpan       = document.getElementById('amount');
  const discountFeedback = document.getElementById('discount-feedback');
  const desktopPkgName   = document.getElementById('svcDesktopPkgName');

  // sticky mobile
  const sticky           = document.getElementById('svcMobileSticky');
  const mobileAmount     = document.getElementById('svcMobileAmount');
  const mobileSubmitBtn  = document.getElementById('svcMobileSubmit'); // chỉ có khi đã login
  const mobilePkgName    = document.getElementById('svcMobilePkgName');

  // modal
  const pkgBackdrop      = document.getElementById('svcPkgBackdrop');
  const openPkgModalBtn  = document.getElementById('svcOpenPkgModal');
  const closePkgModalBtn = document.getElementById('svcPkgClose');
  const pkgGrid          = document.getElementById('svcPkgGrid');
  const pkgSearchInput   = document.getElementById('svcPkgSearch');

  /* ============ submit UX lock ============ */
  if (forms.length) {
    forms.forEach(form => {
      form.addEventListener('submit', function () {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
          submitButton.disabled = true;
          submitButton.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Đang Xử Lý...`;
        }
        if (mobileSubmitBtn) {
          mobileSubmitBtn.disabled = true;
          mobileSubmitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Đang Xử Lý...`;
        }
      });
    });
  }

  /* ============ pay-game: lookup role name ============ */
  let typingTimeout;
  function showNameSpinner(){
    if (spinnerIcon) spinnerIcon.hidden = false;
    if (accountNameInput){
      accountNameInput.classList.add('has-spinner');
      accountNameInput.value = '';
      accountNameInput.style.color = '';
      accountNameInput.style.fontWeight = '';
    }
  }
  function hideNameSpinner(){
    if (spinnerIcon) spinnerIcon.hidden = true;
    if (accountNameInput) accountNameInput.classList.remove('has-spinner');
  }
  if (idAccountInput && accountNameInput){
    idAccountInput.addEventListener('input', function(){
      clearTimeout(typingTimeout);
      const id = idAccountInput.value.trim();
      if (!id){
        hideNameSpinner();
        accountNameInput.value = '';
        accountNameInput.style.color = '';
        accountNameInput.style.fontWeight = '';
        return;
      }
      showNameSpinner();
      typingTimeout = setTimeout(() => {
        fetch('<?php echo e(route("service.getRoleName")); ?>', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
          body: JSON.stringify({ id_account: id })
        })
        .then(r => r.json())
        .then(data => {
          hideNameSpinner();
          if (data.success){
            accountNameInput.value = data.role_name;
            accountNameInput.style.color = '#0E3EDA';
            accountNameInput.style.fontWeight = 'bold';
          } else {
            accountNameInput.value = 'ID Tài Khoản Không Tồn Tại';
            accountNameInput.style.color = 'red';
            accountNameInput.style.fontWeight = 'bold';
          }
        })
        .catch(() => {
          hideNameSpinner();
          accountNameInput.value = 'Lỗi';
          accountNameInput.style.color = 'red';
          accountNameInput.style.fontWeight = 'bold';
        });
      }, 500);
    });
  }

  /* ============ set package (replace <select>) ============ */
  function formatVND(n){ return (n||0).toLocaleString('vi-VN'); }

  function setPackage({ id, name, price, thumb, metaId }){
    if (packageIdInput) packageIdInput.value = id || '';

    if (pickedNameEl)  pickedNameEl.textContent  = name || 'Chưa Chọn';
    if (pickedPriceEl) pickedPriceEl.textContent = formatVND(price || 0);

    // ảnh & meta
    const pickedThumbEl = document.getElementById('svcPickedThumb');
    const pickedMetaEl  = document.getElementById('svcPickedMeta');
    if (pickedThumbEl && thumb) pickedThumbEl.src = thumb;
    if (pickedMetaEl) pickedMetaEl.textContent = metaId ? `#${metaId}` : '...';

    // đồng bộ tổng tiền & “bạn đang chọn”
    if (amountSpan)     amountSpan.textContent = formatVND(price || 0);
    if (desktopPkgName) desktopPkgName.textContent = name || 'Chưa Chọn';
    if (mobilePkgName)  mobilePkgName.textContent  = name || 'Chưa Chọn';

    if (discountFeedback) discountFeedback.style.display = 'none';
    syncAmountToMobile();
    updateStickyVisibility();
  }

  // init từ old('package_id') nếu có
    (function initOldPackage(){
        if (!packageIdInput || !pkgGrid) return;

        const oldId = packageIdInput.value;
        if (!oldId) return;

        const card  = pkgGrid.querySelector(`.svc-pkg-card[data-id="${oldId}"]`);

        if (!card) {
            // Không tìm thấy card (VD: gói bị xoá), reset meta
            setPackage({ id: '', name: 'Chưa Chọn', price: 0, thumb: null, metaId: null });
            return;
        }

        const name   = card.getAttribute('data-name')  || 'Chưa Chọn';
        const price  = parseInt(card.getAttribute('data-price') || '0', 10) || 0;
        const index  = card.getAttribute('data-index');            // ✅ LẤY THỨ TỰ GÓI
        const imgEl  = card.querySelector('.svc-pkg-thumb');
        const thumb  = imgEl ? imgEl.src : null;

        // ✅ metaId dùng index (thứ tự hiển thị), không dùng ID
        setPackage({ id: oldId, name, price, thumb, metaId: index });
    })();

  /* ============ sticky mobile ============ */
  function syncAmountToMobile(){
    if (amountSpan && mobileAmount){
      mobileAmount.textContent = amountSpan.textContent || '0';
    }
    requestAnimationFrame(applyBottomPadding);
  }
  function applyBottomPadding(){
    if (!isMobile() || !sticky){
      pageWrap.style.paddingBottom = '';
      return;
    }
    const isActive = sticky.classList.contains('svc-active');
    pageWrap.style.paddingBottom = isActive
      ? (sticky.getBoundingClientRect().height +
         (parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 64) +
         16) + 'px'
      : '';
  }
  function updateStickyVisibility(){
    if (!sticky) return;
    const hasPkg = packageIdInput && packageIdInput.value;
    const shouldShow = isMobile() && !!hasPkg;
    sticky.classList.toggle('svc-active', shouldShow);
    applyBottomPadding();
  }

  if (amountSpan){
    const obs = new MutationObserver(syncAmountToMobile);
    obs.observe(amountSpan, { childList:true, subtree:true, characterData:true });
    syncAmountToMobile();
  }

  // submit từ sticky (khi đã login)
  if (mobileSubmitBtn && activeForm){
    mobileSubmitBtn.addEventListener('click', function(){
      const realSubmit = activeForm.querySelector('button[type="submit"]');
      if (realSubmit && !realSubmit.disabled){
        realSubmit.disabled = true;
        realSubmit.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Đang Xử Lý...`;
        mobileSubmitBtn.disabled = true;
        mobileSubmitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Đang Xử Lý...`;
        activeForm.submit();
      }
    });
  }

  /* ============ modal chọn gói ============ */
  function openPkgModal(){
    if (!pkgBackdrop) return;
    pkgBackdrop.classList.add('svc-active');
    document.body.style.overflow = 'hidden';
    if (pkgSearchInput){ pkgSearchInput.value = ''; filterCards(''); }
  }
  function closePkgModal(){
    if (!pkgBackdrop) return;
    pkgBackdrop.classList.remove('svc-active');
    document.body.style.overflow = '';
  }
  if (openPkgModalBtn) openPkgModalBtn.addEventListener('click', openPkgModal);
  if (closePkgModalBtn) closePkgModalBtn.addEventListener('click', closePkgModal);
  if (pkgBackdrop){
    pkgBackdrop.addEventListener('click', (e)=>{ if (e.target === pkgBackdrop) closePkgModal(); });
  }
  document.addEventListener('keydown', (e)=>{ if (e.key === 'Escape') closePkgModal(); });

  // Chọn gói bằng card (event delegation)
  if (pkgGrid){
    pkgGrid.addEventListener('click', function(e){
      const card = e.target.closest('.svc-pkg-card');
      if (!card) return;
      const id    = card.getAttribute('data-id');
      const name  = card.getAttribute('data-name')  || 'Chưa Chọn';
      const price = parseInt(card.getAttribute('data-price') || '0', 10) || 0;
      const index  = card.getAttribute('data-index'); // ✅ lấy thứ tự
      const imgEl = card.querySelector('.svc-pkg-thumb');
      const thumb = imgEl ? imgEl.src : null;
      setPackage({ id, name, price, thumb, metaId: index });
      closePkgModal();
    });
  }

  // Tìm theo tên
  function filterCards(keyword){
    const kw = (keyword || '').toLowerCase();
    const cards = pkgGrid ? pkgGrid.querySelectorAll('.svc-pkg-card') : [];
    cards.forEach(card=>{
      const name = (card.getAttribute('data-name') || '').toLowerCase();
      card.style.display = name.includes(kw) ? '' : 'none';
    });
  }
  if (pkgSearchInput){
    pkgSearchInput.addEventListener('input', function(){ filterCards(this.value); });
  }

  // init view
  if (packageIdInput && !packageIdInput.value){
    updateStickyVisibility();
  }
  window.addEventListener('resize', updateStickyVisibility);
  window.addEventListener('load', () => { updateStickyVisibility(); applyBottomPadding(); });
});

/* ============ pre-decode ảnh cho lần mở modal đầu ============ */
(function queuePredecode() {
  const grid = document.getElementById('svcPkgGrid');
  if (!grid) { setTimeout(queuePredecode, 200); return; }

  const predecodeThumbs = () => {
    grid.querySelectorAll('img.svc-pkg-thumb').forEach(img => {
      if (img.decode) img.decode().catch(() => {});
    });
  };
  if ('requestIdleCallback' in window) {
    requestIdleCallback(predecodeThumbs, { timeout: 1500 });
  } else {
    setTimeout(predecodeThumbs, 600);
  }
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/service/show.blade.php ENDPATH**/ ?>