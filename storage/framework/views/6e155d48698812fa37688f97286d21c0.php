
<?php $__env->startSection('title', 'Thông Tin'); ?>
<?php $__env->startSection('content'); ?>
<style>
.container {
    padding: 0px;
}
</style>
    <section class="profile-section">
        <div class="container">
            <div class="profile-container">
                <div class="profile-header">
                    <h1 class="profile-title"><i class="fa-solid fa-user-circle me-2"></i> THÔNG TIN TÀI KHOẢN</h1>
                </div>
                            <div class="info-content">
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="fa-solid fa-id-card me-2"></i> ID Tài Khoản
                                    </div>
                                    <div class="info-value"><?php echo e($user->id); ?></div>
                                </div>

                               <div class="info-row">
                                    <div class="info-label">
                                        <i class="fas fa-trophy"></i> Hạng
                                    </div>
                                    <div class="info-value">
                                        <span><?php echo e($rank['name']); ?></span>
                                        <img src="<?php echo e(asset($rank['image'])); ?>" alt="<?php echo e($rank['name']); ?>" style="width: 25px; height: 25px; margin-left: 5px; display: inline-block; vertical-align: middle;">
                                        <a href="<?php echo e(route('discount.codes')); ?>" class="change-password-link">
                                            <i class="fas fa-gift"></i> QUÀ TẶNG
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="fa-solid fa-user me-2"></i> Tài Khoản
                                    </div>
                                    <div class="info-value"><?php echo e($user->username); ?></div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="fa-solid fa-key me-2"></i> Password
                                    </div>
                                    <div class="info-value">
                                        ********
                                        <a href="<?php echo e(route('profile.change-password')); ?>" class="change-password-link">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> ĐỔI MẬT KHẨU
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="fa-solid fa-wallet me-2"></i> Số Dư
                                    </div>
                                    <div class="info-value info-value--highlight" style = "color:green">
                                        <?php echo e(number_format($user->balance)); ?> VND
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="fa-solid fa-money-bill-trend-up me-2"></i> Tổng Nạp
                                    </div>
                                    <div class="info-value info-value--highlight" style = "color:green">
                                        <?php echo e(number_format($user->total_deposited)); ?> VND
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="fa-solid fa-calendar-check me-2"></i> Ngày Tạo
                                    </div>
                                    <div class="info-value">
                                        <?php echo e($user->created_at->format('H:i d/m/Y')); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/profile/profile.blade.php ENDPATH**/ ?>