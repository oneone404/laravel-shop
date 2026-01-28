<?php if($paginator->hasPages()): ?>
    <nav>
        <div class="pagination">
            
            <?php if($paginator->onFirstPage()): ?>
                <a href="#" class="pagination-item" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                    <i class="fa-solid fa-angle-left"></i>
                </a>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="pagination-item" rel="prev"
                    aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                    <i class="fa-solid fa-angle-left"></i>
                </a>
            <?php endif; ?>

            
            <?php
                $window = 2; // Show 2 numbers on each side of current page
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $start = max(1, $current - $window);
                $end = min($last, $current + $window);
            ?>

            <?php if($start > 1): ?>
                <a href="<?php echo e($paginator->url(1)); ?>" class="pagination-item">1</a>
                <?php if($start > 2): ?>
                    <a href="#" class="pagination-item disabled" aria-disabled="true">...</a>
                <?php endif; ?>
            <?php endif; ?>

            <?php for($i = $start; $i <= $end; $i++): ?>
                <?php if($i == $current): ?>
                    <a href="#" class="pagination-item active" aria-current="page"><?php echo e($i); ?></a>
                <?php else: ?>
                    <a href="<?php echo e($paginator->url($i)); ?>" class="pagination-item"><?php echo e($i); ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if($end < $last): ?>
                <?php if($end < $last - 1): ?>
                    <a href="#" class="pagination-item disabled" aria-disabled="true">...</a>
                <?php endif; ?>
                <a href="<?php echo e($paginator->url($last)); ?>" class="pagination-item"><?php echo e($last); ?></a>
            <?php endif; ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="pagination-item next" rel="next"
                    aria-label="<?php echo app('translator')->get('pagination.next'); ?>">
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            <?php else: ?>
                <a href="#" class="pagination-item next" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </nav>
<?php endif; ?>
<?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/vendor/pagination/default.blade.php ENDPATH**/ ?>