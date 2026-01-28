<!DOCTYPE html>
<html lang="en">
<!-- Head -->
<?php echo $__env->make('layouts.user.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<body>
    <!-- CSRF Token Meta for JS usage -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php echo $__env->make('layouts.user.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Main -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('layouts.user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('layouts.user.menu-mobile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- jQuery -->
    <script src="<?php echo e(asset('assets/js/jquery-3.6.0.min.js')); ?>"></script>

    <!-- Lightbox script -->
    <script src="<?php echo e(asset('assets/libs/simplelightbox.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/image-lightbox.js')); ?>"></script>

    <!-- Mobile Menu script -->
    <script src="<?php echo e(asset('assets/js/mobile-menu.js')); ?>"></script>

    <!-- Core scripts -->
    <script src="<?php echo e(asset('assets/js/discount-code.js?id=87d12f')); ?>"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/menu.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/alert-notice.js')); ?>"></script>

    <!-- Add before closing body tag -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
<?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/layouts/user/app.blade.php ENDPATH**/ ?>