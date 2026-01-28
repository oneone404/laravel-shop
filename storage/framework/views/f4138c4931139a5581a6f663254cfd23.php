

<?php $__env->startSection('title', 'Danh Sách ID Cá'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .tools-sub-container {
            max-width: 1248px;
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

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            background: rgba(14, 62, 218, 0.03);
            padding: 24px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
        }

        .switch-group {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: center;
        }

        .glass-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.3s ease;
            user-select: none;
        }

        .glass-toggle:hover {
            background: rgba(14, 62, 218, 0.05);
        }

        .glass-toggle input {
            display: none;
        }

        .glass-toggle input:checked+span {
            color: var(--primary-color);
            font-weight: 800;
        }

        .glass-toggle input:checked+span i {
            transform: scale(1.2);
        }

        .grade-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .grade-pill {
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 100px;
            font-size: 1.2rem;
            font-weight: 700;
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0.5;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .grade-pill input {
            display: none;
        }

        .grade-pill:has(input:checked) {
            opacity: 1;
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .g1 {
            background: #e2e8f0;
            color: #475569;
        }

        .g2 {
            background: #dcfce7;
            color: #166534;
        }

        .g3 {
            background: #dbeafe;
            color: #1e40af;
        }

        .g4 {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .g5 {
            background: linear-gradient(135deg, #f9a8d4, #fde68a, #93c5fd);
            color: #1e293b;
        }

        .action-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-top: 20px;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            background: rgba(0, 0, 0, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-color);
            font-weight: 600;
        }

        .dark-mode .search-box input {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .fish-table {
            width: 100%;
            min-width: 800px;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .fish-table th {
            text-align: left;
            padding: 15px;
            color: var(--text-light);
            font-size: 1.2rem;
            background: rgba(0, 0, 0, 0.02);
            border-bottom: 2px solid var(--border-color);
        }

        .fish-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .id-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-weight: 800;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(14, 62, 218, 0.2);
        }

        .fish-name-cell {
            border-radius: 10px;
            padding: 8px 12px !important;
            font-weight: 700;
            font-size: 1.3rem;
            display: inline-block;
            margin: 2px;
        }

        @media (max-width: 768px) {
            .action-row {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                max-width: none;
            }
        }
    </style>

    <?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'KHO DỮ LIỆU ID CÁ','description' => 'Danh sách ID cá được cập nhật liên tục từ máy chủ.'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
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
            <div class="filter-grid">
                <div class="switch-group">
                    <label class="glass-toggle">
                        <input type="checkbox" id="type_fish" class="type-check" value="fish" checked>
                        <span><i class="fas fa-fish"></i> Cá</span>
                    </label>
                    <label class="glass-toggle">
                        <input type="checkbox" id="type_trash" class="type-check" value="trash">
                        <span><i class="fas fa-trash-alt"></i> Rác</span>
                    </label>
                </div>

                <div class="grade-filters">
                    <label class="grade-pill g1">
                        <input type="checkbox" class="grade-check" value="1" checked> Trắng
                    </label>
                    <label class="grade-pill g2">
                        <input type="checkbox" class="grade-check" value="2" checked> Xanh Lá
                    </label>
                    <label class="grade-pill g3">
                        <input type="checkbox" class="grade-check" value="3" checked> Xanh Dương
                    </label>
                    <label class="grade-pill g4">
                        <input type="checkbox" class="grade-check" value="4" checked> Tím (VIP)
                    </label>
                    <label class="grade-pill g5">
                        <input type="checkbox" class="grade-check" value="5" checked> Cầu Vồng (VVIP)
                    </label>
                </div>
            </div>

            <div class="action-row">
                <button class="btn btn--primary" id="copyAllIds" style="padding: 12px 24px;">
                    <i class="fas fa-copy"></i> Sao chép ID đã lọc
                </button>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Tìm kiếm cá hoặc ID...">
                </div>
                <?php if($lastUpdated): ?>
                    <div style="font-size: 1.1rem; color: var(--text-light); font-weight: 700;">
                        <i class="fas fa-sync"></i> <?php echo e(\Carbon\Carbon::parse($lastUpdated)->format('H:i d/m/Y')); ?>

                    </div>
                <?php endif; ?>
            </div>

            <div style="overflow-x: auto;">
                <table class="fish-table" id="mainTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;"><input type="checkbox" id="selectAll" checked></th>
                            <th style="width: 100px;">ID</th>
                            <th>Danh Sách Cá / Vật Phẩm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $allMaps = [];
                            foreach ($fishMap as $id => $items) {
                                foreach ($items as $item) {
                                    $allMaps[$id][] = array_merge($item, ['type' => 'fish']);
                                }
                            }
                            foreach ($trashMap as $id => $items) {
                                foreach ($items as $item) {
                                    $allMaps[$id][] = array_merge($item, ['type' => 'trash']);
                                }
                            }
                            ksort($allMaps);
                        ?>

                        <?php $__currentLoopData = $allMaps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $rowTypes = array_unique(array_column($items, 'type')); ?>
                            <tr class="fish-row" data-types="<?php echo e(json_encode($rowTypes)); ?>">
                                <td><input type="checkbox" class="row-checkbox" checked></td>
                                <td><button class="id-btn copy-id" data-id="<?php echo e($id); ?>"><?php echo e($id); ?></button></td>
                                <td>
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $colorClass = match ((int) $item['grade']) { 1 => 'g1', 2 => 'g2', 3 => 'g3', 4 => 'g4', 5 => 'g5', default => 'g1'};
                                        ?>
                                        <span class="fish-name-cell <?php echo e($colorClass); ?> fish-item" data-grade="<?php echo e($item['grade']); ?>"
                                            data-type="<?php echo e($item['type']); ?>">
                                            <?php echo e($item['name']); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const gradeChecks = document.querySelectorAll('.grade-check');
            const typeChecks = document.querySelectorAll('.type-check');
            const rows = document.querySelectorAll('.fish-row');

            function filter() {
                const keyword = searchInput.value.toLowerCase();
                const activeGrades = Array.from(gradeChecks).filter(c => c.checked).map(c => c.value);
                const activeTypes = Array.from(typeChecks).filter(c => c.checked).map(c => c.value);

                rows.forEach(row => {
                    let hasMatch = false;
                    const items = row.querySelectorAll('.fish-item');
                    const rowId = row.querySelector('.copy-id').dataset.id;

                    items.forEach(item => {
                        const grade = item.dataset.grade;
                        const type = item.dataset.type;
                        const name = item.textContent.toLowerCase();

                        const matchGrade = activeGrades.includes(grade);
                        const matchType = activeTypes.includes(type);
                        const matchSearch = !keyword || name.includes(keyword) || rowId.includes(keyword);

                        if (matchGrade && matchType && matchSearch) {
                            item.style.display = 'inline-block';
                            hasMatch = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    row.style.display = hasMatch ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filter);
            gradeChecks.forEach(c => c.addEventListener('change', filter));
            typeChecks.forEach(c => c.addEventListener('change', filter));

            // Copy handlers
            document.querySelectorAll('.copy-id').forEach(btn => {
                btn.addEventListener('click', () => {
                    navigator.clipboard.writeText(btn.dataset.id);
                    showToast(`Đã copy ID: ${btn.dataset.id}`);
                });
            });

            document.getElementById('copyAllIds').addEventListener('click', () => {
                const ids = [];
                document.querySelectorAll('.fish-row').forEach(row => {
                    if (row.style.display !== 'none' && row.querySelector('.row-checkbox').checked) {
                        ids.push(row.querySelector('.copy-id').dataset.id);
                    }
                });
                if (ids.length > 0) {
                    navigator.clipboard.writeText(ids.join(','));
                    showToast(`Đã copy ${ids.length} ID`);
                }
            });

            document.getElementById('selectAll').addEventListener('change', e => {
                document.querySelectorAll('.fish-row').forEach(row => {
                    if (row.style.display !== 'none') {
                        row.querySelector('.row-checkbox').checked = e.target.checked;
                    }
                });
            });

            function showToast(msg) {
                const toast = document.createElement('div');
                toast.style = 'position:fixed; bottom:30px; left:50%; transform:translateX(-50%); background:rgba(14,62,218,0.9); color:#fff; padding:12px 24px; border-radius:100px; z-index:10000; font-weight:800; backdrop-filter:blur(10px); box-shadow:0 10px 30px rgba(0,0,0,0.2);';
                toast.innerHTML = `<i class="fas fa-check-circle mr-2"></i> ${msg}`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            }

            filter();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/tools/fish-id.blade.php ENDPATH**/ ?>