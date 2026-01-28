<?php if(session('error')): ?>
    <div class="service__alert service__alert--error">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <span><?php echo e(session('error')); ?></span>
        </div>
        <button type="button" class="service__alert-close">&times;</button>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/components/alert-error.blade.php ENDPATH**/ ?>