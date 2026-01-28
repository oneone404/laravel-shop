<div class="profile-sidebar">
    <div class="sidebar-header">
    </div>
    <ul class="sidebar-menu">
        <?php if(config_get('payment.card.active', true)): ?>
            <li class="sidebar-item <?php echo e(request()->routeIs('profile.deposit-card') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('profile.deposit-card')); ?>" class="sidebar-link">
                    <i class="fa-solid fa-credit-card"></i> NẠP TIỀN THẺ CÀO
                </a>
            </li>
        <?php endif; ?>
        <li class="sidebar-item <?php echo e(request()->routeIs('profile.deposit-atm') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('profile.deposit-atm')); ?>" class="sidebar-link">
                <i class="fa-solid fa-money-bill-transfer"></i> NẠP TIỀN NGÂN HÀNG
            </a>
        </li>
    </ul>
</div>
<?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/layouts/user/sidebar.blade.php ENDPATH**/ ?>