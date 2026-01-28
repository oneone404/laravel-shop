
<?php $__env->startSection('title', $hack->name); ?>
<?php $__env->startSection('content'); ?>

<!-- Popup Bảo Trì -->
<div id="maintModal" class="maint-backdrop" hidden tabindex="-1">
  <div class="maint-modal" role="dialog" aria-modal="true" aria-labelledby="maintTitle">
    <div class="maint-icon">
      <i class="fas fa-tools"></i>
    </div>
    <div class="maint-content">
      <h3 id="maintTitle">Hệ Thống Đang Bảo Trì</h3>
      <p id="maintMsg">Bản Hack Đang Trong Quá Trình Bảo Trì Sửa Lỗi, Xin Cảm Ơn !</p>
    </div>
    <div class="maint-actions">
      <button type="button" class="maint-ok">OK, Tôi Đã Hiểu</button>
    </div>
  </div>
</div>


<!-- Modal Nhận Key Premium Design -->
<div id="getkeyModal" class="noti-backdrop" hidden tabindex="-1">
  <div class="getkey-modal">
    <div class="getkey-header">
      <div class="getkey-icon-wrapper">
        <div class="getkey-icon-glow"></div>
        <div class="getkey-icon">
          <i class="fas fa-key"></i>
        </div>
      </div>
      <div class="getkey-title">Nhận Key Free</div>
      <p class="getkey-desc">Đã Sẵn Sàng Để Tiép Tục</p>
    </div>
    <div class="getkey-body">
      <ul class="getkey-checklist">
        <li class="getkey-check-item">
          <i class="fas fa-check-circle"></i>
          <span>Chỉ Cần Làm 1 Nhiệm Vụ Để Nhận</span>
        </li>
        <li class="getkey-check-item">
          <i class="fas fa-check-circle"></i>
          <span>Có Thể Lấy Key Free Mỗi Ngày</span>
        </li>
        <li class="getkey-check-item">
          <i class="fas fa-check-circle"></i>
          <span>Sử Dụng Đến 23:59:59 - <?php echo e(date('d/m/Y')); ?></span>
        </li>
      </ul>
      <div class="getkey-actions">
        <a href="#" id="linkGetKey" target="_blank" class="btn-start-getkey">
          <span>BẮT ĐẦU NHẬN KEY</span>
          <i class="fas fa-arrow-right"></i>
        </a>
        <button type="button" class="btn-close-getkey" id="closeGetKey">
          Đóng
        </button>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="show-wrap">
    <!-- LEFT: main content -->
    <div class="card">
      <div class="hack-head">
        <?php if($hack->logo): ?>
          <img src="<?php echo e($hack->logo); ?>" alt="<?php echo e($hack->name); ?> Logo" class="hack-logo">
        <?php endif; ?>
        <div class="hack-title"><?php echo e($hack->name); ?><span class="status-dot <?php echo e($hack->active ? 'active' : 'inactive'); ?>" title="<?php echo e($hack->active ? 'Hoạt Động' : 'Bảo Trì'); ?>"></span></div>
      </div>

      
      <?php
        $gallery = is_array($hack->images ?? null) && count($hack->images) ? $hack->images : [ $hack->thumbnail ?? $hack->logo ];
        $gallery = array_values(array_filter($gallery ?? [], fn($u) => !empty($u)));
        $imageCount = count($gallery);
      ?>

      
      <?php if($imageCount > 0): ?>
        <a href="<?php echo e($gallery[0]); ?>" class="hack-gallery-preview sl-gallery">
          <img src="<?php echo e($gallery[0]); ?>" alt="<?php echo e($hack->name); ?>">
        </a>
        
        <?php $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if($index > 0): ?>
            <a href="<?php echo e($img); ?>" class="hack-gallery-hidden sl-gallery"></a>
          <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>

      <div class="hack-body">
        <?php
          $descLines = !empty($hack->description) ? array_filter(explode("\n", str_replace("\r", "", $hack->description)), fn($l) => trim($l) !== "") : [];
          $notiCount = count($descLines);
        ?>


<div id="wrap">
<a id="btnDownload"
   href="<?php echo e(route('download.hack', $hack)); ?>"
   class="btn-slide"
   data-active="<?php echo e((int) ($hack->active ?? 0)); ?>"
   data-maint-msg="Bản Hack Đang Trong Quá Trình Bảo Trì Sửa Lỗi, Xin Cảm Ơn !"
   rel="nofollow">
  <span class="circle"><img src="<?php echo e(asset('images/vng.png')); ?>" alt="VNG" class="btn-icon-img"></span>
  <span class="title">Tải VNG</span>
  <span class="title-hover">Download</span>
</a>

<?php if(!empty($hack->download_link_global)): ?>
  <a id="btnDownloadGlobal"
     href="<?php echo e(route('download.hack.global', $hack)); ?>"
     class="btn-slide4"
     data-active="<?php echo e((int) ($hack->active ?? 0)); ?>"
     data-maint-msg="Bản Hack Đang Trong Quá Trình Bảo Trì Sửa Lỗi, Xin Cảm Ơn !"
     rel="nofollow">
    <span class="circle4"><img src="<?php echo e(asset('images/global.png')); ?>" alt="Global" class="btn-icon-img"></span>
    <span class="title4">Tải Global</span>
    <span class="title-hover4">Download</span>
  </a>
<?php endif; ?>

  <a href="<?php echo e(route('gamekey.form')); ?>" class="btn-slide3">
    <span class="circle3"><i class="fas fa-crown"></i></span>
    <span class="title3">Mua Key</span>
    <span class="title-hover3">Đặc Quyền</span>
  </a>

  <button type="button"
     id="btnKeyFree"
     class="btn-slide2"
     data-api-url="<?php echo e(route('hacks.free-key', $hack)); ?>"
     data-active="<?php echo e((int) ($hack->active ?? 0)); ?>"
     data-maint-msg="Bản Hack Đang Trong Quá Trình Bảo Trì Sửa Lỗi, Xin Cảm Ơn !">
    <span class="circle2"><i class="fas fa-key"></i></span>
    <span class="title2">Key Free</span>
    <span class="title-hover2">Miễn Phí</span>
  </button>
</div>

      </div>
    </div>

    <!-- CENTER: Notification directly shown -->
    <div class="card noti-static-card">
      <div class="hack-head">
        <div class="hack-title"><i class="fas fa-bell"></i> Thông Báo Mới</div>
      </div>
      <div class="noti-static-body">
        <?php if(!empty($hack->description)): ?>
          <?php
            $lines = array_filter(explode("\n", str_replace("\r", "", $hack->description)), fn($l) => trim($l) !== "");
            $updateTime = optional($hack->updated_at)->format('H:i - d/m/Y') ?? date('H:i - d/m/Y');
          ?>
          <?php $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
              $parts = explode('|', $line, 2);
              $hasCustomTime = count($parts) > 1;
              $displayTime = $hasCustomTime ? trim($parts[0]) : $updateTime;
              $displayContent = $hasCustomTime ? trim($parts[1]) : trim($line);
            ?>
            <div class="noti-item">
              <div class="noti-time">
                <i class="far fa-clock"></i> <?php echo e($displayTime); ?>

              </div>
              <div class="noti-content"><?php echo $displayContent; ?></div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
          <div style="text-align: center; color: #666; padding: 20px;">Không Có Dữ Liệu</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- RIGHT: info panel -->
    <aside class="card info-card-sidebar">
      <div class="hack-head">
        <div class="hack-title"><i class="fas fa-info-circle"></i> Thông Tin Khác</div>
      </div>
      <div class="info-wrap">
        <ul class="info-list">
            <li class="info-item"><span>Phiên Bản</span><span><?php echo e($hack->version ?? $hack->name); ?></span></li>
            <li class="info-item"><span>Hệ Điều Hành</span><span><?php echo e($hack->platform ?? 'Android'); ?></span></li>
            <li class="info-item"><span>Kích Thước</span><span><?php echo e($hack->size ?? '...'); ?></span></li>
            <li class="info-item"><span>Nâng Cấp Lần Cuối</span><span><?php echo e(optional($hack->updated_at)->format('d/m/Y H:i:s') ?? '—'); ?></span></li>
        </ul>
      </div>
    </aside>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    new SimpleLightbox('.sl-gallery', {
        caption: false,
        closeText: '×',
        navText: [
            '<i class="fas fa-chevron-left"></i>',
            '<i class="fas fa-chevron-right"></i>'
        ],
        animationSpeed: 250,
        scaleImageToRatio: true,
        enableKeyboard: true,
        disableRightClick: true
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const btnDownload = document.getElementById('btnDownload');
  const btnKeyFree = document.getElementById('btnKeyFree');
  const modal = document.getElementById('maintModal');
  if(!modal) return;

  // Luôn bảo đảm popup đang ẩn khi load
  modal.hidden = true;

  const closeBtn = modal.querySelector('.maint-close');
  const okBtn    = modal.querySelector('.maint-ok');
  const msgEl    = modal.querySelector('#maintMsg');

  function openModal(text){
    if(text && msgEl) msgEl.textContent = text;
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    // focus backdrop để Esc hoạt động ngay
    modal.focus({preventScroll:true});
  }
  function closeModal(){
    modal.hidden = true;
    document.body.style.overflow = '';
  }

  // Xử lý cho nút Download VNG
  if(btnDownload){
    const activeDownload = ['1','true',1,true].includes(btnDownload.dataset.active);
    btnDownload.addEventListener('click', function(e){
      if(!activeDownload){
        e.preventDefault();
        openModal(btnDownload.dataset.maintMsg || 'Hiện bản hack đang bảo trì. Vui lòng thử lại sau.');
      }
    });
  }

  // Xử lý cho nút Download Global
  const btnDownloadGlobal = document.getElementById('btnDownloadGlobal');
  if(btnDownloadGlobal){
    const activeGlobal = ['1','true',1,true].includes(btnDownloadGlobal.dataset.active);
    btnDownloadGlobal.addEventListener('click', function(e){
      if(!activeGlobal){
        e.preventDefault();
        openModal(btnDownloadGlobal.dataset.maintMsg || 'Hiện bản hack đang bảo trì. Vui lòng thử lại sau.');
      }
    });
  }

    // Xử lý cho nút Key Free (AJAX)
    if(btnKeyFree){
      const activeKeyFree = ['1','true',1,true].includes(btnKeyFree.dataset.active);
      const apiUrl = btnKeyFree.dataset.apiUrl;
      let isLoading = false;
      
      const showGetKeyModal = (url) => {
        const getkeyModal = document.getElementById('getkeyModal');
        const linkGetKey = document.getElementById('linkGetKey');
        if(getkeyModal && linkGetKey) {
          linkGetKey.href = url;
          getkeyModal.hidden = false;
          document.body.style.overflow = 'hidden';
          
          linkGetKey.onclick = () => {
            getkeyModal.hidden = true;
            document.body.style.overflow = '';
          };
        }
      };

      btnKeyFree.addEventListener('click', async function(e){
        e.preventDefault();
        
        if(!activeKeyFree){
          openModal(btnKeyFree.dataset.maintMsg || 'Hiện bản hack đang bảo trì. Vui lòng thử lại sau.');
          return;
        }

        if(isLoading) return;
        isLoading = true;

        const originalTitle = btnKeyFree.querySelector('.title2');
        const originalHover = btnKeyFree.querySelector('.title-hover2');
        const origText = originalTitle ? originalTitle.textContent : '';
        const origHoverText = originalHover ? originalHover.textContent : '';
        
        if(originalTitle) originalTitle.textContent = 'Checking...';
        if(originalHover) originalHover.textContent = 'Checking...';
        btnKeyFree.disabled = true;
        btnKeyFree.style.opacity = '0.7';

        try {
          const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
            },
          });

          const data = await response.json();

          if(originalTitle) originalTitle.textContent = origText;
          if(originalHover) originalHover.textContent = origHoverText;
          btnKeyFree.disabled = false;
          btnKeyFree.style.opacity = '1';
          isLoading = false;

          if(data.success && data.short_url){
            showGetKeyModal(data.short_url);
          } else {
            openModal(data.error || 'Có lỗi xảy ra, vui lòng thử lại!');
          }
        } catch(err) {
          console.error('Free Key Error:', err);
          openModal('Lỗi kết nối, vui lòng thử lại!');
          if(originalTitle) originalTitle.textContent = origText;
          if(originalHover) originalHover.textContent = origHoverText;
          btnKeyFree.disabled = false;
          btnKeyFree.style.opacity = '1';
          isLoading = false;
        }
      });

    // Sự kiện đóng modal nhận key
    const closeGetKey = document.getElementById('closeGetKey');
    const getkeyModal = document.getElementById('getkeyModal');
    if(closeGetKey && getkeyModal) {
      closeGetKey.onclick = () => {
        getkeyModal.hidden = true;
        document.body.style.overflow = '';
      };
      getkeyModal.onclick = (e) => {
        if(e.target === getkeyModal) {
          getkeyModal.hidden = true;
          document.body.style.overflow = '';
        }
      };
    }
  }

  // Đóng popup: X, Đã hiểu, click ra nền, phím Esc
  closeBtn && closeBtn.addEventListener('click', closeModal);
  okBtn && okBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', (e)=>{ if(e.target === modal) closeModal(); });
  window.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && !modal.hidden) closeModal(); });

});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/hacks/show.blade.php ENDPATH**/ ?>