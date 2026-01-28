

<?php $__env->startSection('title', 'Danh Sách ID Vùng Câu'); ?>

<?php $__env->startSection('content'); ?>
<style>
.table-container {
    max-width: 960px;
    margin: 0 auto;
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
    overflow: hidden;
    border: 1px solid #cbd5e1;
}

.table-title {
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    margin: 20px 0;
    color: #1e3a8a;
}

.blue-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 15px;
}

.blue-table thead {
    background-color: #1d4ed8;
    color: #ffffff;
}

.blue-table th {
    text-align: left;
    padding: 12px 16px;
    font-weight: 600;
    letter-spacing: 0.3px;
    border: 1px solid #cbd5e1;
}

.blue-table td {
    padding: 12px 16px;
    border: 1px solid #cbd5e1;
    color: #1e293b;
}

.blue-table tbody tr:nth-child(even) {
    background-color: #f8fafc;
}

.blue-table tbody tr:hover {
    background-color: #e0f2fe;
}

.pretty-id {
    font-weight: 600;
    color: #2563eb;
}

.pretty-name {
    color: #334155;
}

.blue-table thead tr:first-child th:first-child {
    border-top-left-radius: 8px;
}
.blue-table thead tr:first-child th:last-child {
    border-top-right-radius: 8px;
}
.blue-table tbody tr:last-child td:first-child {
    border-bottom-left-radius: 8px;
}
.blue-table tbody tr:last-child td:last-child {
    border-bottom-right-radius: 8px;
}

.search-input {
    margin-bottom: 15px;
    width: 100%;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    padding: 8px 12px;
}
</style>

<div class="container mt-4 table-container">
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