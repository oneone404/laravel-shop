

<?php $__env->startSection('title', 'Danh Sách ID Vùng Câu'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .tools-sub-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 30px;
            box-shadow: var(--glass-shadow);
        }

        .search-wrapper {
            position: relative;
            margin-bottom: 30px;
        }

        .search-wrapper i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.6rem;
        }

        .glass-search {
            width: 100%;
            padding: 15px 20px 15px 50px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            color: var(--text-color);
            font-size: 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .dark-mode .glass-search {
            background: rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        .glass-search:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(14, 62, 218, 0.1);
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th {
            text-align: left;
            padding: 15px 20px;
            color: var(--text-light);
            font-size: 1.3rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--border-color);
        }

        .custom-table td {
            padding: 15px 20px;
            color: var(--text-color);
            font-size: 1.5rem;
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s ease;
        }

        .dark-mode .custom-table td {
            color: #e2e8f0;
        }

        .id-badge {
            display: inline-block;
            padding: 4px 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: #fff;
            border-radius: 8px;
            font-weight: 800;
            font-size: 1.3rem;
            box-shadow: 0 4px 10px rgba(14, 62, 218, 0.2);
        }

        .region-name {
            font-weight: 700;
            color: var(--primary-color);
        }

        .dark-mode .region-name {
            color: #4df2ff;
        }

        .custom-table tr:hover td {
            background: rgba(14, 62, 218, 0.03);
        }

        .dark-mode .custom-table tr:hover td {
            background: rgba(255, 255, 255, 0.03);
        }
    </style>

    <?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'ID VÙNG CÂU','description' => 'Tra cứu mã ID vùng câu để sử dụng các tính năng hỗ trợ trong game.'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
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

    <div class="tools-sub-container">
        <div class="glass-card">
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="glass-search" placeholder="Tìm kiếm ID hoặc tên vùng câu...">
            </div>

            <div style="overflow-x: auto;">
                <table class="custom-table" id="idTable">
                    <thead>
                        <tr>
                            <th style="width: 120px;">ID</th>
                            <th>Tên Vùng Câu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $fakeIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fake): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <span class="id-badge"><?php echo e($fake['id'] ?? '...'); ?></span>
                                </td>
                                <td class="region-name"><?php echo e($fake['name']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function () {
            const keyword = this.value.toLowerCase();
            const rows = document.querySelectorAll('#idTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(keyword) ? '' : 'none';
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/tools/fake-id.blade.php ENDPATH**/ ?>