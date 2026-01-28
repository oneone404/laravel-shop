

<?php $__env->startSection('title', 'Danh Sách ID'); ?>

<?php $__env->startSection('content'); ?>
<style>
.table-container {
    max-width: 100%;
    margin: 0 auto;
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
    border: 1px solid #cbd5e1;
    overflow-x: auto;
}

.blue-table {
    width: max-content;
    min-width: 600px;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
}

.blue-table thead {
    background-color: #1d4ed8;
    color: #ffffff;
}

.blue-table th {
    text-align: left;
    padding: 8px 12px;
    font-weight: 600;
    border: 1px solid #cbd5e1;
}

.blue-table td {
    padding: 8px 12px;
    border: 1px solid #cbd5e1;
    color: #1e293b;
    word-break: break-word;
    white-space: normal;
    vertical-align: middle;
}

.check-col {
    width: 50px;
    text-align: center;
}

.blue-table td:not(:first-child):not(.check-col) {
    max-width: 180px;
}

.blue-table tbody tr:nth-child(even) {
    background-color: #f8fafc;
}

.blue-table tbody tr:hover {
    background-color: #e0f2fe;
}

.tab {
    cursor: pointer;
    padding: 6px 12px;
    font-weight: 600;
    color: #1e3a8a;
    border-bottom: 2px solid transparent;
}

.tab.active {
    color: #3b82f6;
    border-color: #3b82f6;
}

.filter-controls {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 20px;
    background: #f8fafc;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.grade-checkboxes {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
}

.grade-check, .type-check {
    display: none;
}

.grade-label {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
    user-select: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    opacity: 0.6;
}

.grade-check:checked + .grade-label, 
.type-check:checked + .grade-label {
    opacity: 1;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: rgba(0,0,0,0.2);
}

/* Colors for labels */
.label-g1 { background: #f2faf3ff; color: #14532D; border: 1px solid #cbd5e1; }
.label-g2 { background: #6EE7B7; color: #065F46; }
.label-g3 { background: #93C5FD; color: #1E3A8A; }
.label-g4 { background: #E9A8F2; color: #701A75; }
.label-g5 { background: linear-gradient(135deg, #F9A8D4, #FDE68A, #6EE7B7, #93C5FD, #E9A8F2); color: #1F2937; }

.label-fish { 
    background: #ffffff; 
    color: #166534; 
    border: 2px solid #bbf7d0; 
    font-size: 14px !important;
}
.type-check:checked + .label-fish {
    background: #dcfce7;
    border-color: #22c55e;
    color: #15803d;
}

.label-trash { 
    background: #ffffff; 
    color: #475569; 
    border: 2px solid #e2e8f0;
    font-size: 14px !important;
}
.type-check:checked + .label-trash {
    background: #f1f5f9;
    border-color: #64748b;
    color: #1e293b;
}

.filter-separator {
    height: 1px;
    background: linear-gradient(to right, transparent, #e2e8f0, transparent);
    margin: 10px 0;
    border: none;
}

/* ===== MODERN SWITCH ===== */
.switch-container {
    display: flex;
    gap: 30px;
    align-items: center;
    justify-content: center;
    padding: 2px 5px;
}

.switch-item {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.switch-text {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
}

.switch {
    position: relative;
    display: inline-block;
    width: 42px;
    height: 22px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

input:checked + .slider.fish { background-color: #22c55e; }
input:checked + .slider.trash { background-color: #22c55e; }

input:checked + .slider:before {
    transform: translateX(20px);
}

.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.copy-btn {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #fff;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.copy-btn i {
    font-size: 16px;
}

/* Modern Checkboxes */
input[type="checkbox"].row-checkbox, 
input[type="checkbox"]#selectAll {
    -webkit-appearance: none;
    appearance: none;
    background-color: #fff;
    margin: 0;
    width: 20px;
    height: 20px;
    border: 2px solid #cbd5e1;
    border-radius: 6px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    outline: none;
}

input[type="checkbox"].row-checkbox::before,
input[type="checkbox"]#selectAll::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transition: 0.2s transform cubic-bezier(0.4, 0, 0.2, 1);
    background-color: #fff;
    clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    display: block;
}

input[type="checkbox"].row-checkbox:checked,
input[type="checkbox"]#selectAll:checked {
    background-color: #1d4ed8;
    border-color: #1d4ed8;
    box-shadow: 0 2px 8px rgba(29, 78, 216, 0.3);
}

input[type="checkbox"].row-checkbox:checked::before,
input[type="checkbox"]#selectAll:checked::before {
    transform: scale(1);
}

input[type="checkbox"].row-checkbox:hover,
input[type="checkbox"]#selectAll:hover {
    border-color: #3b82f6;
}

.search-wrap {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
}

.search-wrap input {
    padding-left: 36px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
}

/* ===== MOBILE RESPONSIVE ===== */
@media (max-width: 768px) {
    .filter-controls {
        padding: 10px;
        gap: 8px;
    }

    .switch-container {
        gap: 20px;
        justify-content: center;
    }

    .grade-checkboxes {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 6px;
        margin-bottom: 5px !important;
    }
    
    .grade-label {
        padding: 8px 6px;
        font-size: 11px;
    }

    /* Đưa Cầu Vồng chiếm full 2 cột cho đẹp */
    .grade-label.label-g5 {
        grid-column: span 2;
    }

    .action-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .copy-btn {
        width: 100%;
        justify-content: center;
        padding: 10px;
    }

    .search-wrap {
        max-width: none;
    }

    .search-wrap input {
        height: 44px; /* Dễ bấm hơn trên mobile */
    }

    .table-container {
        padding: 10px;
        border-radius: 8px;
    }

    .blue-table {
        font-size: 13px;
    }
    
    .blue-table th, .blue-table td {
        padding: 6px 8px;
    }

    .update-time {
        text-align: center;
        width: 100%;
    }
}
</style>

<?php
function gradeColor($grade) {
    return match($grade) {
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

function gradeTextColor($grade) {
    return match($grade) {
        1 => '#14532D', // xanh đậm
        2 => '#065F46', // emerald đậm
        3 => '#1E3A8A', // xanh dương đậm
        4 => '#701A75', // tím đậm
        5 => '#1F2937', // xám đậm (dễ đọc trên gradient)
        default => '#1F2937',
    };
}
?>


<div class="container mt-4 table-container">
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
    // Gộp tất cả dữ liệu lại để lọc chung
    $allMaps = [];
    foreach($fishMap as $id => $items) {
        foreach($items as $item) { $allMaps[$id][] = array_merge($item, ['type' => 'fish']); }
    }
    foreach($trashMap as $id => $items) {
        foreach($items as $item) { $allMaps[$id][] = array_merge($item, ['type' => 'trash']); }
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
    <td class="id-cell" style="cursor: pointer; font-weight: bold; position: relative;" title="Click để copy ID">
        <?php echo e($id); ?>

    </td>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <td class="fish-cell" 
            data-grade="<?php echo e($item['grade']); ?>"
            data-type="<?php echo e($item['type']); ?>"
            style="
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
            const rowTypesStr = row.dataset.types;
            const rowTypes = rowTypesStr ? JSON.parse(rowTypesStr) : [];
            
            let rowHasMatchingFish = false;

            // Kiểm tra từng con cá trong dòng
            fishCells.forEach(cell => {
                const grade = parseInt(cell.dataset.grade);
                const type = cell.dataset.type;
                const name = cell.innerText.trim().toLowerCase();
                
                // Điều kiện 1: Phải khớp với checkbox grade đang chọn
                const matchGrade = activeGrades.includes(grade);
                
                // Điều kiện 2: Phải khớp với checkbox loại (cá/rác) đang chọn
                const matchType = activeTypes.includes(type);

                // Điều kiện 3: Phải khớp với từ khóa tìm kiếm (nếu có)
                const matchKeyword = keyword === '' || name.includes(keyword) || idText.includes(keyword);

                if (matchGrade && matchType && matchKeyword) {
                    cell.style.display = '';
                    rowHasMatchingFish = true;
                } else {
                    cell.style.display = 'none';
                }
            });

            // Ẩn/Hiện dòng dựa trên việc có con cá nào khớp không
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

    // Sao chép những ID đang hiển thị VÀ được tích chọn
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

    // Xử lý nút Chọn Tất Cả
    selectAll.addEventListener('change', () => {
        const isChecked = selectAll.checked;
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const cb = row.querySelector('.row-checkbox');
                if (cb) cb.checked = isChecked;
            }
        });
    });

    // Sao chép ID khi click vào cell ID
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
            // Toast thông báo (có thể dùng alert)
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

    filterRows(); // Chạy lọc mặc định
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/id-fish.blade.php ENDPATH**/ ?>