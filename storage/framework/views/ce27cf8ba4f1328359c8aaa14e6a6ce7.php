<?php $__env->startSection('title', 'Tài Khoản'); ?>
<?php $__env->startSection('content'); ?>

<style>
/* ==== Lưới sản phẩm ==== */
.product-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 6px;
}
.container { padding: 0; }

/* ==== Card sản phẩm ==== */
.product-card {
  background: #fff;
  border: 2px solid rgba(90, 105, 150, 0.2);
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(14, 62, 218, 0.05);
  overflow: hidden;
  text-align: center;
  transition: all 0.25s ease;
}
.product-card:hover {
  border-color: #0eb5daff;
  box-shadow: 0 6px 16px rgba(14, 62, 218, 0.15);
  transform: translateY(-3px);
}

/* ==== Ảnh sản phẩm + loader ==== */
.image-wrapper {
  position: relative;
  width: 100%;
  aspect-ratio: 16 / 9;
  border-radius: 8px;
  overflow: hidden;
}
.image-wrapper img {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: opacity .3s ease, transform .3s ease;
}
.image-wrapper .image-loader {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  max-width: 100px;
  height: auto;
  opacity: 1;
  transition: opacity .25s ease;
}
.image-wrapper img.product-image {
  opacity: 0;
  transform: scale(1.05);
}
.image-wrapper img.product-image.loaded {
  opacity: 1;
  transform: scale(1);
}
.image-wrapper .image-loader.hide {
  opacity: 0;
  pointer-events: none;
}

/* ==== Tên, badge, action ==== */
.product-name {
  font-size: 14px;
  font-weight: 600;
  margin-top: 10px;
}

.product-stats {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 6px;
  margin: 10px 0;
}

.product-badge {
  font-size: 0.75rem;
  font-weight: 800;
  padding: 3px 10px;
  border-radius: 8px;
  border: 1.2px solid rgba(14, 62, 218, 0.25);
  background: transparent;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-image: linear-gradient(90deg, #0E3EDA, #0eb5daff);
}
.badge-number-sold {
  -webkit-background-clip: initial !important;
  -webkit-text-fill-color: initial !important;
  color: #ff4444 !important; /* đỏ thật sự */
  font-weight: 900;
}
.badge-number-available {
  -webkit-background-clip: initial !important;
  -webkit-text-fill-color: initial !important;
      color:  rgba(34, 197, 94, 1) !important; /* đỏ thật sự */
  font-weight: 900;
}
.product-action {
  border: 1.5px solid #0E3EDA;
  color: #0E3EDA;
  border-radius: 18px;
  padding: 5px 0;
  font-weight: 600;
  font-size: 11px;
  margin: 2px auto 15px;
  display: block;
  width: calc(100% - 32px);
  transition: 0.3s;
}
.product-action:hover {
  background: #0E3EDA;
  color: #fff;
}

.product-action-image {
  width: 180px;
  height: auto;
  display: block;
  margin: 10px auto;
  transition: transform 0.2s ease;
}
.product-action-image:active { transform: scale(0.96); }

/* ==== Responsive ==== */
@media (min-width: 768px) {
  .product-grid { grid-template-columns: repeat(4, 1fr); gap: 12px; }
  .product-name { font-size: 16px; }
  .product-action { font-size: 12px; padding: 7px 24px; }
  .product-badge { font-size: 0.85rem; padding: 3px 8px; }
}
.menu{
  margin-bottom: 0px;
}

/* Pending Order Banner */
.pending-banner {
    background: #ffffff;
    padding: 14px 20px;
    border-radius: 14px;
    margin: 15px auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #0E3EDA;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
    animation: heroFadeUp 0.6s ease-out;
    position: relative;
    overflow: hidden;
}

/* Subtle background element */
.pending-banner::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(14, 62, 218, 0.03));
    pointer-events: none;
}

.pending-banner__info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.pending-banner__info i {
    color: #0E3EDA;
    font-size: 18px;
    background: rgba(14, 62, 218, 0.06);
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    flex-shrink: 0; /* Ngăn chặn bóp méo icon */
}

.pending-banner__info span {
    font-size: 14px;
    color: #1e293b;
    font-weight: 600;
}

.pending-banner__info strong {
    color: #0E3EDA;
    font-weight: 800;
}

.pending-banner__btn {
    background: #0E3EDA;
    color: white;
    padding: 8px 18px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pending-banner__btn:hover {
    background: #0f172a;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(15, 23, 42, 0.2);
    color: white;
}

@media (max-width: 480px) {
    .pending-banner {
        padding: 12px 16px;
        flex-direction: row;
        gap: 10px;
    }
    .pending-banner__info span {
        font-size: 11px;
        line-height: 1.3;
    }
    .pending-banner__info i {
        width: 30px;
        height: 30px;
        font-size: 14px;
    }
    .pending-banner__btn {
        padding: 6px 10px;
        font-size: 9px;
    }
}

.pending-container {
    max-width: 1216px;
    margin: 0 auto;
    padding: 0 15px;
}

.pending-row {
    margin-bottom: 10px;
}

.pending-row:last-child {
    margin-bottom: 0;
}
</style>

<?php if(isset($pendingOrders) && $pendingOrders->count() > 0): ?>
    <div class="pending-container">
        <?php $__currentLoopData = $pendingOrders->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="pending-row">
                <div class="pending-banner">
                    <div class="pending-banner__info">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Bạn Đang Có Đơn Hàng <strong>#<?php echo e($order->order_code); ?></strong> Chưa Thanh Toán</span>
                    </div>
                    <a href="<?php echo e(route('direct-payment.show', $order->order_code)); ?>" class="pending-banner__btn">
                        THANH TOÁN NGAY
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'TÀI KHOẢN PLAY 1','description' => ''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('hero-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\HeroHeader::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0)): ?>
<?php $attributes = $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0; ?>
<?php unset($__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0)): ?>
<?php $component = $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0; ?>
<?php unset($__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0); ?>
<?php endif; ?>
<section class="menu">
    <div class="container">

        <div class="product-grid">

            
            <?php if($categories_play->count()): ?>

                <?php $__currentLoopData = $categories_play; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('user.partials.category-card', ['category' => $category], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        </div>

    </div>
</section>

<?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'TÀI KHOẢN PLAY 2','description' => ''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('hero-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\HeroHeader::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0)): ?>
<?php $attributes = $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0; ?>
<?php unset($__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0)): ?>
<?php $component = $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0; ?>
<?php unset($__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0); ?>
<?php endif; ?>
<section class="menu">
    <div class="container">

        <div class="product-grid">

            
            <?php if($categories_clone->count()): ?>

                <?php $__currentLoopData = $categories_clone; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('user.partials.category-card', ['category' => $category], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        </div>

    </div>
</section>


<?php if($categories_random->count()): ?>
<?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'TÀI KHOẢN PLAY 3','description' => ''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('hero-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\HeroHeader::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0)): ?>
<?php $attributes = $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0; ?>
<?php unset($__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0)): ?>
<?php $component = $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0; ?>
<?php unset($__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0); ?>
<?php endif; ?>
<section class="menu">
    <div class="container">

        <div class="product-grid">
            <?php $__currentLoopData = $categories_random; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('user.partials.category-card', ['category' => $category], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
</section>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".image-wrapper").forEach(wrapper => {
    const img = wrapper.querySelector(".product-image");
    const loader = wrapper.querySelector(".image-loader");

    const showImage = () => {
      img.classList.add("loaded");
      loader?.classList.add("hide");
      setTimeout(() => loader?.remove(), 300);
    };

    if (img.complete) showImage();
    else {
      img.addEventListener("load", showImage);
      img.addEventListener("error", () => loader?.remove());
    }
  });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/category/show-all.blade.php ENDPATH**/ ?>