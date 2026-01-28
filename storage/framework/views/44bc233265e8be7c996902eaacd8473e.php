<!DOCTYPE html>
<html lang="en">
<?php echo $__env->make('layouts.admin.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<body>
    

    <div class="main-wrapper">
        <?php echo $__env->make('layouts.admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('layouts.admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <?php echo $__env->make('layouts.admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>

</html>
<?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/layouts/admin/app.blade.php ENDPATH**/ ?>