

<?php $__env->startSection('title', 'Hạng Thành Viên'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ====== TABLE ====== */
.container { padding: 0px; }
.history-table { white-space: nowrap; min-width: auto; }

/* ====== DISCOUNT MODAL ====== */
.discount-modal {
    display: none;
    position: fixed;
    z-index: 3000;
    inset: 0;
    justify-content: center;
    align-items: center;
    padding: 10px;
}
.discount-modal.show {
    display: flex;
}
.discount-overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.35);
    backdrop-filter: blur(6px);
    top: 0;
    left: 0;
}
.discount-body {
    position: relative;
    z-index: 10;
    width: 95%;
    max-width: 500px;
}
.discount-content {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    animation: discountFadeIn 0.3s ease;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
}
.discount-content h3 {
    margin-bottom: 15px;
    font-weight: 700;
    text-align: center;
    font-size: 20px;
}

/* ====== TABLE STYLE ====== */
.discount-content table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 8px;
    overflow: hidden;
    font-size: 14px;
}

.discount-content thead {
    background: linear-gradient(135deg, #0A2A90, #0E3EDA);
    color: #fff;
}

.discount-content thead th {
    padding: 10px;
    font-weight: 600;
}

.discount-content tbody tr {
    border-bottom: 1px solid #e0e0e0;
}

.discount-content tbody td {
    padding: 10px;
    vertical-align: middle;
}

.discount-content tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.discount-content tbody tr:hover {
    background-color: #f1f5ff;
}

/* ====== BUTTON ====== */
.discount-btn {
    width: 100%;
    height: 45px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 10px;
    margin-top: 12px;
    background: linear-gradient(135deg, #0A2A90, #0E3EDA);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}
.discount-btn:hover {
    background: linear-gradient(135deg, #0E3EDA, #1C54D6);
}

/* ====== ANIMATION ====== */
@keyframes discountFadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
/* Chỉ áp dụng cho bảng trong discount modal */
#discountRewardModal table th,
#discountRewardModal table td {
    text-align: left; /* Hoặc right tùy ý */
    vertical-align: middle;
}

.change-id-btn {
    display: block;
    margin: 0 auto;
    font-size: 1rem; /* 👈 nhỏ hơn so với 1.5rem */
    padding: 6px 12px;
    border: 1px solid rgba(0, 0, 0, 0.2); /* 👈 viền xám nhẹ */
    border-radius: 6px;
    background-color: #fff;
    color: #333;
    cursor: pointer;
    transition: all 0.2s ease;
}

.change-id-btn:hover {
    background-color: #f5f5f5;
    border-color: rgba(0, 0, 0, 0.3);
    color: #000;
}
@keyframes discountFadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<div class="container mt-4">
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
    
<div class="login-info-box text-center">
    <?php
        // Map rank -> màu gradient mới
        $rankColors = [
            'Thành Viên Mới'        => 'linear-gradient(135deg, #5c6f7b, #7b8d97, #9aaab3)', // xám xanh hiện đại
            'Thành Viên Bạc'        => 'linear-gradient(135deg, #cfd9df, #e2ebf0, #f8f9fa)', // bạc sáng sang trọng
            'Thành Viên Vàng'       => 'linear-gradient(135deg, #ffcc33, #ffb300, #ff9100)', // vàng cam rực rỡ
            'Thành Viên Bạch Kim'   => 'linear-gradient(135deg, #dfe9f3, #f0f0f0, #cfd9df)', // trắng bạc tinh tế
            'Thành Viên Kim Cương'  => 'linear-gradient(135deg, #00f2fe, #4facfe, #007adf)', // xanh lam ngọc lấp lánh
            'Thành Viên Huyền Thoại'=> 'linear-gradient(135deg, #ff4b1f, #ff9068, #ff4b1f)', // đỏ cam huyền ảo
        ];
    
        // Nếu rank không có trong danh sách thì dùng màu tím hồng neon
        $gradient = $rankColors[$rank['name']] ?? 'linear-gradient(135deg, #8e2de2, #4a00e0, #ff00cc)';
    ?>
    
    <div class="member-rank" style="background: <?php echo e($gradient); ?>;">
        <strong><?php echo e($rank['name']); ?></strong>
        <img src="<?php echo e(asset($rank['image'])); ?>" alt="<?php echo e($rank['name']); ?>" style="height: 24px;">
    </div>
</div>
<div class="login-info-box text-center">
<style>
.rank-progress-container {
    text-align: center;
    margin: 20px auto;
    max-width: 800px;
}

.rank-icons-wrapper {
    position: relative;
    margin-bottom: 8px;
}

.rank-line {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 6px;
    background: #ddd;
    z-index: 1;
    border-radius: 3px;
}

.rank-line-fill {
    position: absolute;
    top: 50%;
    left: 0;
    height: 6px;
    background: linear-gradient(90deg, #ff9800, #ffb74d);
    z-index: 2;
    border-radius: 3px;
}

.rank-icons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 3;
}

.rank-icons img {
    width: 40px;
    height: 40px;
    background: white;
    padding: 4px;
    border-radius: 50%;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
}
.rank-tooltip {
    position: absolute;
    background: #fff;
    color: #333;
    padding: 6px 10px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 6px;
    max-width: calc(100vw - 20px); /* 👈 Không cho rộng hơn màn hình */
    word-wrap: break-word; /* 👈 Cho phép xuống dòng */
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transform: translate(-50%, -120%) scale(0.95);
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.25s ease, transform 0.25s ease;
    z-index: 9999;
}

.rank-tooltip.show {
    opacity: 1;
    transform: translate(-50%, -120%) scale(1);
}
.rank-progress-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 15px 0;
}

.rank-step {
    text-align: center;
    flex: 0 0 auto;
}

.rank-step img {
    width: 30px;
    height: 30px;
    display: block;
    margin: 0 auto 4px;
}

.rank-bar-line {
    flex: 1;
    height: 8px;
    background: #ddd;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.rank-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #ff9800, #ffb74d);
}

.rank-missing {
    text-align: center;
    font-size: 14px;
    margin-top: 5px;
    color: #555;
}

</style>
<?php
    $ranks = [
        ['name' => 'Thành Viên Mới', 'image' => 'images/rank/dong.png', 'min' => 0],
        ['name' => 'Thành Viên Bạc', 'image' => 'images/rank/bac.png', 'min' => 100000],
        ['name' => 'Thành Viên Vàng', 'image' => 'images/rank/vang.png', 'min' => 300000],
        ['name' => 'Thành Viên Bạch Kim', 'image' => 'images/rank/bachkim.png', 'min' => 1000000],
        ['name' => 'Thành Viên Kim Cương', 'image' => 'images/rank/kimcuong.png', 'min' => 2000000],
        ['name' => 'Thành Viên Huyền Thoại', 'image' => 'images/rank/huyenthoai.png', 'min' => 5000000],
    ];

    $totalDeposited = $totalDeposited ?? 0;

    // Rank hiện tại
    $currentRankIndex = 0;
    foreach ($ranks as $i => $rank) {
        if ($totalDeposited >= $rank['min']) {
            $currentRankIndex = $i;
        }
    }

    $currentRank = $ranks[$currentRankIndex];
    $nextRank = $ranks[min($currentRankIndex + 1, count($ranks) - 1)];

    // % tiến trình giữa rank hiện tại và tiếp theo
    $currentMin = $currentRank['min'];
    $nextMin = $nextRank['min'];
    if ($nextMin > $currentMin) {
        $progress = (($totalDeposited - $currentMin) / ($nextMin - $currentMin)) * 100;
    } else {
        $progress = 100;
    }

    // Tính % cho thanh nối icon
    $progressLine = ($currentRankIndex / (count($ranks) - 1)) * 100;
    if ($progress < 100 && $currentRankIndex < count($ranks) - 1) {
        $progressLine += ($progress / (count($ranks) - 1));
    }
?>

<div class="rank-progress-container">
    <div class="rank-icons-wrapper">
        
        <div class="rank-line"></div>
        <div class="rank-line-fill" style="width: <?php echo e($progressLine); ?>%;"></div>
        <div class="rank-icons">
            <?php $__currentLoopData = $ranks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <img src="<?php echo e(asset($rank['image'])); ?>" title="<?php echo e($rank['name']); ?>">
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div id="rankTooltip" class="rank-tooltip"></div>

    <div class="rank-bar">
        <div class="rank-bar-fill" style="width: <?php echo e($progress); ?>%; background: linear-gradient(90deg, #ff9800, #ffb74d);"></div>
    </div>

    
    <div class="rank-progress-bar">
        <div class="rank-step">
            <img src="<?php echo e(asset($currentRank['image'])); ?>" alt="<?php echo e($currentRank['name']); ?>">
            <strong><?php echo e($currentRank['name']); ?></strong>
        </div>
    
        <div class="rank-bar-line">
            <div class="rank-bar-fill" style="width: <?php echo e($progress); ?>%;"></div>
        </div>
    
        <div class="rank-step">
            <img src="<?php echo e(asset($nextRank['image'])); ?>" alt="<?php echo e($nextRank['name']); ?>">
            <strong><?php echo e($nextRank['name']); ?></strong>
        </div>
    </div>
    
    
    <?php if($progress < 100): ?>
        <div class="rank-missing">
            Còn Thiếu <strong><?php echo e(number_format($nextRank['min'] - $totalDeposited)); ?> VND</strong>
        </div>
    <?php else: ?>
        <div class="rank-missing">
            <strong>Hạng Thành Viên Tối Đa</strong>
        </div>
    <?php endif; ?>
    
    </div>

<?php if(trim($rank['name']) === 'Thành Viên Mới' || $codesPerMonth == 0): ?>
    <div class="mt-2 text-muted">
        Hiện Tại Chưa Có Phần Thưởng Cho Thành Viên Mới
    </div>
<?php else: ?>
    <?php if($alreadyClaimed): ?>
        <div class="mt-2 text-success fw-bold">
            Bạn Đã Nhận Phần Thưởng Tháng Này
        </div>
    <?php else: ?>
        <div class="mt-2 text-primary">
            Có <strong><?php echo e($codesPerMonth); ?></strong> Mã Giảm Giá Chưa Nhận
        </div>
        <form action="<?php echo e(route('discount.claim')); ?>" method="POST" class="mt-2">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-warning change-id-btn">Nhận Thưởng</button>
        </form>
    <?php endif; ?>
<?php endif; ?>

<button class="btn btn-warning change-id-btn" onclick="openDiscountModal()">Xem Phần Thưởng</button>

</div>

<style>
.login-info-box {
    border: 3px solid #FF9800;
    border-radius: 8px;
    padding: 10px;
    font-size: 1.2rem;
    color: #333;
    width: 100%;
    max-width: 400px;
    margin: 0 auto 10px auto;
    display: block;
}

.member-rank {
    display: inline-flex;
    align-items: center;
    padding: 8px 20px;
    border-radius: 20px;
    color: #fff;
    font-weight: bold;
    font-size: 13px;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    position: relative;
    overflow: hidden;
    gap: 5px;
}
.member-rank strong {
    margin-right: 0; /* bỏ margin cũ */
}
.member-rank img {
    width: 25px;
    height: 25px;
}

.member-rank::before {
    content: "";
    position: absolute;
    top: 0;
    left: -75%;
    width: 50%;
    height: 100%;
    background: linear-gradient(
        120deg,
        rgba(255,255,255,0.3) 0%,
        rgba(255,255,255,0.6) 50%,
        rgba(255,255,255,0.3) 100%
    );
    transform: skewX(-25deg);
    animation: shine 3s infinite;
}

@keyframes shine {
    0% { left: -75%; }
    100% { left: 125%; }
}
.rank-cell {
    display: inline-flex;       /* Dùng flex để canh ngang */
    align-items: center;        /* Căn giữa theo chiều dọc */
    gap: 5px;                   /* Khoảng cách giữa text và ảnh */
}

.rank-cell img {
    width: 20px;
    height: 20px;
}

</style>


    
    <div class="deposit-history">
        <div class="history-header">DANH SÁCH PHẦN THƯỞNG</div>
        <div class="history-table-container">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>CODE</th>
                        <th>GIẢM GIÁ</th>
                        <th>HẠN SỬ DỤNG</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td>
                                <?php echo e(substr($code->code, 0, 3) . '****' . substr($code->code, -3)); ?>

                                <button class="copy-btn" data-clipboard-text="<?php echo e($code->code); ?>">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </td>
                            <td><?php echo e(number_format($code->discount_value)); ?> VND</td>
                            <td><?php echo e($expiryDate); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="no-data">Không Có Dữ Liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="discount-modal" id="discountRewardModal">
  <div class="discount-overlay"></div>
  <div class="discount-body">
    <div class="discount-content">
      <h3>Phần Thưởng</h3>
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>Thành Viên</th>
                  <th>Mã / Tháng</th>
              </tr>
          </thead>
          <?php
    $ranks = [
        ['name' => 'Mới', 'image' => 'dong.png', 'codes' => 0],
        ['name' => 'Bạc', 'image' => 'bac.png', 'codes' => 1],
        ['name' => 'Vàng', 'image' => 'vang.png', 'codes' => 3],
        ['name' => 'Bạch Kim', 'image' => 'bachkim.png', 'codes' => 5],
        ['name' => 'Kim Cương', 'image' => 'kimcuong.png', 'codes' => 10],
        ['name' => 'Huyền Thoại', 'image' => 'huyenthoai.png', 'codes' => 20],
    ];
?>

<tbody>
    <?php $__currentLoopData = $ranks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rankItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td>
                <span class="rank-cell">
                    <?php echo e($rankItem['name']); ?>

                    <img src="<?php echo e(asset('images/rank/' . $rankItem['image'])); ?>" alt="<?php echo e($rankItem['name']); ?>">
                </span>
            </td>
            <td><strong><?php echo e($rankItem['codes']); ?></strong></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>

      </table>
      <button class="discount-btn" onclick="closeDiscountModal()">Đóng</button>
    </div>
  </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const copyButtons = document.querySelectorAll('.copy-btn');
    if (copyButtons.length > 0) {
        const clipboard = new ClipboardJS('.copy-btn');
        clipboard.on('success', function (e) {
            const icon = e.trigger.querySelector('i');
            icon.classList.remove('fa-copy');
            icon.classList.add('fa-check', 'text-success');
            setTimeout(() => {
                icon.classList.remove('fa-check', 'text-success');
                icon.classList.add('fa-copy');
            }, 1500);
            e.clearSelection();
        });
        clipboard.on('error', function () {
            alert('Lỗi Copy');
        });
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tooltip = document.getElementById("rankTooltip");
    const rankImages = document.querySelectorAll(".rank-icons img");

    rankImages.forEach(img => {
        img.addEventListener("click", function () {
            const name = img.getAttribute("title") || "Không rõ";
            tooltip.textContent = name;

            const rect = img.getBoundingClientRect();
            const scrollTop = window.scrollY || document.documentElement.scrollTop;
            const scrollLeft = window.scrollX || document.documentElement.scrollLeft;

            let leftPos = rect.left + rect.width / 2 + scrollLeft;
            let topPos = rect.top + scrollTop;

            // Tạm đặt tooltip
            tooltip.style.left = leftPos + "px";
            tooltip.style.top = topPos + "px";
            tooltip.classList.add("show");

            // Đo lại kích thước tooltip
            const tooltipRect = tooltip.getBoundingClientRect();

            // Nếu vượt phải → đẩy sang trái
            if (tooltipRect.right > window.innerWidth - 5) {
                leftPos -= (tooltipRect.right - (window.innerWidth - 5)) + 5;
            }

            // Nếu vượt trái → đẩy sang phải
            if (tooltipRect.left < 5) {
                leftPos += (5 - tooltipRect.left) + 5;
            }

            // Cập nhật lại vị trí an toàn
            tooltip.style.left = leftPos + "px";
            tooltip.style.top = topPos + "px";

            // Tự ẩn tooltip
            clearTimeout(window.rankTooltipTimeout);
            window.rankTooltipTimeout = setTimeout(() => {
                tooltip.classList.remove("show");
            }, 1800);
        });
    });
});
</script>


<script>
function openDiscountModal() {
    document.getElementById('discountRewardModal').classList.add('show');
}
function closeDiscountModal() {
    document.getElementById('discountRewardModal').classList.remove('show');
}
document.querySelectorAll('#discountRewardModal .discount-overlay').forEach(el => {
    el.addEventListener('click', closeDiscountModal);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/discount_codes.blade.php ENDPATH**/ ?>