

<?php $__env->startSection('title', 'Danh Sách ID'); ?><?php $__env->startSection('content'); ?><?php $__env->startPush('css'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/apps.css')); ?>">
    <?php $__env->stopPush(); ?>

    <?php
        function gradeColor($grade)
        {
            return match ((int) $grade) {
                // Thường
                1 => '#f2faf3ff',   // xanh lá nhạt (giống nền cá thường)

                // Hiếm
                2 => '#6EE7B7',   // xanh ngọc (emerald)

                // Cực hiếm
                3 => '#93C5FD',   // xanh dương dịu

                // VIP
                4 => '#E9A8F2',   // tím hồng (rất giống game)

                // VVIP
                5 => 'linear-gradient(135deg,
                                    #F9A8D4,
                                    #FDE68A,
                                    #6EE7B7,
                                    #93C5FD,
                                    #E9A8F2
                                )',

                default => '#E5E7EB',
            };
        }

        function gradeTextColor($grade)
        {
            return match ((int) $grade) {
                1 => '#14532D', // xanh đậm
                2 => '#065F46', // emerald đậm
                3 => '#1E3A8A', // xanh dương đậm
                4 => '#701A75', // tím đậm
                5 => '#1F2937', // xám đậm (dễ đọc trên gradient)
                default => '#1F2937',
            };
        }
    ?>

    <div class="container mt-4 table-fish-container">
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

        <div class="filter-controls">
            <div class="switch-container">
                <label class="switch-item">
                    <div class="switch">
                        <input type="checkbox" id="type_fish" class="type-check" value="fish" checked>
                        <span class="slider fish"></span>
                    </div>
                    <span class="switch-text">Cá</span>
                </label>

                <label class="switch-item">
                    <div class="switch">
                        <input type="checkbox" id="type_trash" class="type-check" value="trash">
                        <span class="slider trash"></span>
                    </div>
                    <span class="switch-text">Rác</span>
                </label>
            </div>

            <hr class="filter-separator">

            <div class="grade-checkboxes">
                <input type="checkbox" id="g1" class="grade-check" value="1" checked>
                <label for="g1" class="grade-label label-g1">Trắng</label>

                <input type="checkbox" id="g2" class="grade-check" value="2" checked>
                <label for="g2" class="grade-label label-g2">Xanh Lá</label>

                <input type="checkbox" id="g3" class="grade-check" value="3" checked>
                <label for="g3" class="grade-label label-g3">Xanh Dương</label>

                <input type="checkbox" id="g4" class="grade-check" value="4" checked>
                <label for="g4" class="grade-label label-g4">Tím (VIP)</label>

                <input type="checkbox" id="g5" class="grade-check" value="5" checked>
                <label for="g5" class="grade-label label-g5">Cầu Vồng (VVIP)</label>
            </div>

            <div class="action-bar">
                <button type="button" class="copy-btn" id="copyAllIds">
                    <i class="fas fa-copy"></i> Sao Chép ID Đã Lọc
                </button>

                <div class="search-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Tìm kiếm ID hoặc tên cá..." class="form-control">
                </div>

                <?php if($lastUpdated): ?>
                    <div class="update-time" style="font-size: 12px; color: #94A3B8; font-weight: 600;">
                        CẬP NHẬT: <?php echo e(\Carbon\Carbon::parse($lastUpdated)->format('H:i d/m/Y')); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>

        <table class="blue-table">
            <thead>
                <tr>
                    <th class="check-col">
                        <input type="checkbox" id="selectAll" checked title="Chọn tất cả đang hiển thị">
                    </th>
                    <th style="width: 80px;">ID</th>
                    <?php for($i = 1; $i <= $maxTypes; $i++): ?>
                        <th>Loại <?php echo e($i); ?></th>
                    <?php endfor; ?>
                </tr>
            </thead>

            <tbody id="mainTable">
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
                    <?php
                        $rowTypes = array_unique(array_column($items, 'type'));
                    ?>
                    <tr class="fish-row" data-types="<?php echo e(json_encode($rowTypes)); ?>">
                        <td class="check-col">
                            <input type="checkbox" class="row-checkbox" checked>
                        </td>
                        <td class="id-cell" style="cursor: pointer; font-weight: bold; position: relative;"
                            title="Click để copy ID">
                            <?php echo e($id); ?>

                        </td>
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td class="fish-cell" data-grade="<?php echo e($item['grade']); ?>" data-type="<?php echo e($item['type']); ?>" style="
                                                    background: <?php echo e(gradeColor($item['grade'])); ?>;
                                                    color: <?php echo e(gradeTextColor($item['grade'])); ?>;
                                                    font-weight: <?php echo e($item['grade'] >= 4 ? 'bold' : 'normal'); ?>;
                                                ">
                                <?php echo e($item['name']); ?>

                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php for($i = count($items); $i < $maxTypes; $i++): ?>
                            <td class="empty-cell"></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('searchInput');
            const selectAll = document.getElementById('selectAll');
            const gradeChecks = document.querySelectorAll('.grade-check');
            const typeChecks = document.querySelectorAll('.type-check');
            const copyBtn = document.getElementById('copyAllIds');
            const mainTable = document.getElementById('mainTable');
            const rows = mainTable.querySelectorAll('.fish-row');

            function filterRows() {
                const keyword = input.value.trim().toLowerCase();
                const activeGrades = Array.from(gradeChecks)
                    .filter(c => c.checked)
                    .map(c => parseInt(c.value));

                const activeTypes = Array.from(typeChecks)
                    .filter(c => c.checked)
                    .map(c => c.value);

                rows.forEach(row => {
                    const idCell = row.querySelector('.id-cell');
                    const fishCells = row.querySelectorAll('.fish-cell');
                    const emptyCells = row.querySelectorAll('.empty-cell');
                    const idText = idCell.innerText.trim().toLowerCase();

                    let rowHasMatchingFish = false;

                    fishCells.forEach(cell => {
                        const grade = parseInt(cell.dataset.grade);
                        const type = cell.dataset.type;
                        const name = cell.innerText.trim().toLowerCase();

                        const matchGrade = activeGrades.includes(grade);
                        const matchType = activeTypes.includes(type);
                        const matchKeyword = keyword === '' || name.includes(keyword) || idText.includes(keyword);

                        if (matchGrade && matchType && matchKeyword) {
                            cell.style.display = '';
                            rowHasMatchingFish = true;
                        } else {
                            cell.style.display = 'none';
                        }
                    });

                    if (rowHasMatchingFish) {
                        row.style.display = '';
                        emptyCells.forEach(c => c.style.display = 'none');
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            input.addEventListener('input', filterRows);
            gradeChecks.forEach(check => check.addEventListener('change', filterRows));
            typeChecks.forEach(check => check.addEventListener('change', filterRows));

            copyBtn.addEventListener('click', () => {
                const visibleCheckedIds = [];
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const checkbox = row.querySelector('.row-checkbox');
                        if (checkbox && checkbox.checked) {
                            const id = row.querySelector('.id-cell').innerText.trim();
                            visibleCheckedIds.push(id);
                        }
                    }
                });

                if (visibleCheckedIds.length === 0) {
                    alert('Vui lòng chọn ít nhất một ID để copy!');
                    return;
                }

                const textToCopy = visibleCheckedIds.join(',');
                copyToClipboard(textToCopy, `Đã Sao Chép ${visibleCheckedIds.length} ID`);
            });

            selectAll.addEventListener('change', () => {
                const isChecked = selectAll.checked;
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cb = row.querySelector('.row-checkbox');
                        if (cb) cb.checked = isChecked;
                    }
                });
            });

            document.querySelectorAll('.id-cell').forEach(cell => {
                cell.addEventListener('click', () => {
                    const id = cell.innerText.trim();
                    copyToClipboard(id, `Đã copy ID: ${id}`);
                });
            });

            function copyToClipboard(text, successMsg) {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    const alertDiv = document.createElement('div');
                    alertDiv.style = 'position:fixed; bottom:20px; left:50%; transform:translateX(-50%); background:rgba(0,0,0,0.8); color:#fff; padding:10px 20px; border-radius:30px; z-index:9999; font-weight:bold; font-size:14px;';
                    alertDiv.innerText = successMsg;
                    document.body.appendChild(alertDiv);
                    setTimeout(() => alertDiv.remove(), 2000);
                } catch (err) {
                    console.error('Không thể copy', err);
                }
                document.body.removeChild(textArea);
            }

            filterRows();
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/apps/fish-id.blade.php ENDPATH**/ ?>