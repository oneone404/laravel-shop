<a href="<?php echo e(route('category.index', ['slug' => $category->slug])); ?>" class="product-card">
  <div class="product-image-wrapper image-wrapper">
    <img src="<?php echo e(asset('images/loader.svg')); ?>" alt="Loading..." class="image-loader">
    <img src="<?php echo e($category->thumbnail); ?>" alt="<?php echo e($category->name); ?>" class="product-image" loading="lazy" decoding="async" />
  </div>

  <h2 class="product-name"><?php echo e($category->name); ?></h2>

  <div class="product-stats">
    <span class="product-badge">
      ĐÃ BÁN <span class="badge-number-sold"><?php echo e(number_format($category->soldCount + 50)); ?></span>
    </span>
    <span class="divider">|</span>
    <span class="product-badge">
      CÒN LẠI <span class="badge-number-available"><?php echo e(number_format($category->availableAccount)); ?></span>
    </span>
  </div>

                <?php if(config('app.use_image_button')): ?>
                    <div class="product-action-img">
                        <img src="<?php echo e(asset('assets/img/button/buttonshowall.png')); ?>"
                            alt="XEM CHI TIẾT"
                            class="product-action-image">
                    </div>
                <?php else: ?>
                    <p class="product-action">XEM CHI TIẾT</p>
                <?php endif; ?>
</a>
<?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/partials/category-card.blade.php ENDPATH**/ ?>