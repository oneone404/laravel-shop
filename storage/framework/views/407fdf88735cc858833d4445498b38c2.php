

<?php $__env->startSection('title', 'Tài Khoản Đã Mua'); ?>

<?php $__env->startSection('content'); ?>
<style>
        .history-table-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.history-table {
    white-space: nowrap;
    min-width: auto; /* hoặc lớn hơn nếu bảng rộng hơn */
}

.history-table th,
.history-table td {
    white-space: nowrap;
}

.container-v2 {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 16px;
}

.containerv2 {
    padding: 0px; /* cho mobile vẫn gọn */
}

/* Khi màn hình lớn hơn 768px (tablet trở lên) */
@media (min-width: 768px) {
    .containerv2 {
        padding: 0 23%; /* cho PC, rộng rãi hơn */
    }
}
    
.history-header {
    border-bottom: 2px solid var(--accent-color);
    margin: 0 20px;
    text-align: center;
}
</style>
    <section class="profile-section">
        <div class="containerv2">
<div class="history-header">LỊCH SỬ TÀI KHOẢN</div>
                            <div class="info-content">
                                <?php if(session('error')): ?>
                                    <div class="alert alert-danger">
                                        <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo e(session('error')); ?>

                                    </div>
                                <?php endif; ?>

                                <?php if(session('success')): ?>
                                    <div class="alert alert-success">
                                        <i class="fa-solid fa-circle-check me-2"></i> <?php echo e(session('success')); ?>

                                    </div>
                                <?php endif; ?>

                                <div class="transaction-history">
                                    <div class="history-table-container">
                                        <table class="history-table">
                                            <thead>
                                                <tr>
                                                    <th>TÀI KHOẢN</th>
                                                    <th>MẬT KHẨU</th>
                                                    <th>SỐ TIỀN</th>
                                                    <th>THỜI GIAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        
                                                        
                                                       <td class="text-bold"><?php echo e($transaction->account_name); ?>

    <button class="copy-btn" data-clipboard-text="<?php echo e($transaction->account_name); ?>">
        <i class="fas fa-copy"></i>
    </button>
</td>
<td class="text-bold"><?php echo e($transaction->password); ?>

    <button class="copy-btn" data-clipboard-text="<?php echo e($transaction->password); ?>">
        <i class="fas fa-copy"></i>
    </button>
</td>

                                                        <td class="amount text-danger">
                                                            - <?php echo e(number_format($transaction->price)); ?> VND</td>
                                                        <td><?php echo e($transaction->updated_at->format('H:i d/m/Y')); ?></td>
                                                    </tr>
                                                    
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="7" class="no-data">Không có dữ liệu</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="pagination">
                                        <?php echo e($transactions->links()); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="containerv2">
                    <div class="detail__info">
                    <div class="detail__info-row">
                        <div class="detail__info-item">
                            <span class="detail__info-label">★ CÁCH 1: ĐỔI MẬT KHẨU ZING ID</span>
                            <span class="detail__info-value">1. Vào Trang <a href = "https://id.zing.vn" target = "_blank" style = "color:red">id.zing.vn</a> Đăng Nhập Tài Khoản</span>
                            <span class="detail__info-value">2. Nhập Thông Tin Vào Nếu Trang Đó Yêu Cầu</span>
                            <span class="detail__info-value">3. Nhập Xong Vào Mục ĐỔI MẬT KHẨU Để Đổi</span>
                            <span class="detail__info-label">★ CÁCH 2: ĐỔI MẬT KHẨU ZING ID GIỮ TRẮNG THÔNG TIN</span>
                            <span class="detail__info-value">1. Xem Video <a href = "https://youtu.be/EsrtHzjH_TY?si=F3loV0-HJjH7-yHG" target = "_blank" style = "color:red">Tại Đây</a></span>
                            <!-- <span class="detail__info-label">★ CÁCH ĐĂNG NHẬP BẰNG CHƠI NGAY</span>
                            <span class="detail__info-value">1. VÀO MÀN HÌNH ĐĂNG NHẬP</span>
                            <span class="detail__info-value">2. CHỌN PHƯƠNG THỨC KHÁC</span>
                            <span class="detail__info-value">3. CHỌN KHÔI PHỤC CHƠI NGAY</span>
                            <span class="detail__info-value">4. ĐĂNG NHẬP TÀI KHOẢN ĐÃ MUA VÀO</span>
                            <span class="detail__info-label">★  CÁCH ĐỔI MẬT KHẨU CHƠI NGAY</span>
                            <span class="detail__info-value">1. TẠO TÀI KHOẢN GMAIL TƯƠNG ỨNG VỚI TÀI KHOẢN ĐÃ MUA</span>
                            <span class="detail__info-value">2. CHỌN PHƯƠNG THỨC KHÁC</span>
                            <span class="detail__info-value">3. CHỌN KHÔI PHỤC CHƠI NGAY</span>
                            <span class="detail__info-value">4. CHỌN QUÊN MẬT KHẨU</span>
                            <span class="detail__info-value">5. NHẬP GMAIL ĐÃ TẠO VÀ ĐỔI MẬT KHẨU</span> -->
                    </div>
                </div>
                </div>
            </div>
    </section>
<!-- Clipboard.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

<script>
    // Initialize clipboard.js
    var clipboard = new ClipboardJS('.copy-btn');

    clipboard.on('success', function(e) {
        var btn = e.trigger; // Nút bấm copy
        var icon = btn.querySelector('i'); // Lấy thẻ <i> bên trong

        // Đổi sang dấu tick
        icon.classList.remove('fa-copy');
        icon.classList.add('fa-check');

        // Sau 2 giây đổi lại icon copy
        setTimeout(function() {
            icon.classList.remove('fa-check');
            icon.classList.add('fa-copy');
        }, 2000);

        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        alert('Sao chép thất bại! Vui lòng thử lại.');
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/profile/purchased-accounts.blade.php ENDPATH**/ ?>