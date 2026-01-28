

<?php $__env->startSection('title', 'Danh Sách ID Vùng Câu'); ?>

<?php $__env->startSection('content'); ?>

    <?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'Danh Sách ID','description' => ''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
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

    <div class="container mt-4 table-container">
        <input type="text" id="searchInput" class="search-input" placeholder="Tìm Kiếm ID Hoặc Tên Vùng Câu">

        <table class="blue-table">
            <thead>
                <tr>
                    <th style="width: 100px;">ID</th>
                    <th>Vùng Câu</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $fakeIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fake): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="pretty-id"><?php echo e($fake['id'] ?? '...'); ?></td>
                        <td class="pretty-name"><?php echo e($fake['name']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.blue-table tbody tr');

            searchInput.addEventListener('input', () => {
                const keyword = searchInput.value.trim().toLowerCase();

                rows.forEach(row => {
                    const idCell = row.querySelector('td.pretty-id');
                    const nameCell = row.querySelector('td.pretty-name');
                    const idText = idCell.textContent.trim().toLowerCase();
                    const nameText = nameCell.textContent.trim().toLowerCase();

                    if (idText.includes(keyword) || nameText.includes(keyword)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/apps/fake-id.blade.php ENDPATH**/ ?>