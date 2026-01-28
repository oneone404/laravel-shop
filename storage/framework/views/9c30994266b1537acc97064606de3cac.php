<?php $__env->startSection('title', 'Dịch Vụ'); ?>
<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'DỊCH VỤ GAME','description' => ''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
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
                <?php if($services->count() > 0): ?>
                    <?php $__currentLoopData = $boostingServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($service->active): ?>
                            <a href="<?php echo e(route('service.show', ['slug' => $service->slug])); ?>" class="product-card">
                                <img src="<?php echo e($service->thumbnail); ?>" alt="<?php echo e($service->name); ?>" class="product-image" />
                                <h2 class="product-name"><?php echo e(strtoupper($service->name)); ?></h2>
                            <div class="product-stats">
                                <span class="status-label">Trạng Thái:</span>
                                <span class="status-ready">Sẵn Sàng</span>
                            </div>
                        <p class="product-action">THUÊ DỊCH VỤ</p>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="no-results">
                        <div class="no-results__content">
                            <i class="fas fa-exclamation-circle no-results__icon"></i>
                            <h2 class="no-results__title">Không Tìm Thấy Dịch Vụ</h2>
                            <p class="no-results__message"></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/service/show-all.blade.php ENDPATH**/ ?>