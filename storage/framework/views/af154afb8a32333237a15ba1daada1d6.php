

<?php $__env->startSection('title', 'Mua Key Game'); ?>

<?php $__env->startSection('content'); ?>

<?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'Mua Key VIP','description' => ''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
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

<div class="container">
    <?php if(session('error')): ?>
        <div class="service__alert service__alert--error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo e(session('error')); ?></span>
            <button type="button" class="service__alert-close">&times;</button>
        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="service__alert service__alert--success">
            <i class="fas fa-check-circle"></i>
            <span><?php echo e(session('success')); ?></span>
            <button type="button" class="service__alert-close">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Form Mua Key -->
    <div class="service__form mb-5">
        <h3 class="service__form-title">THÔNG TIN ĐƠN HÀNG</h3>
        <form action="<?php echo e(route('gamekey.create')); ?>" method="POST" id="orderForm">
            <?php echo csrf_field(); ?>
            
            <div class="service__form-group">
                <label for="chonGame"><i class="fas fa-gamepad"></i> CHỌN GAME</label>
                <div class="select-wrapper" id="customSelect">
                    <div class="selected" data-value="com.vng.playtogether">
                        <img src="<?php echo e(asset('images/vng.png')); ?>" alt=""> PLAY TOGETHER VNG
                        <span class="arrow"></span>
                    </div>
                    <div class="options">
                        <div class="option" data-value="com.vng.playtogether">
                            <img src="<?php echo e(asset('images/vng.png')); ?>" alt=""> PLAY TOGETHER VNG
                        </div>
                        <div class="option" data-value="com.haegin.playtogether">
                            <img src="<?php echo e(asset('images/global.png')); ?>" alt=""> PLAY TOGETHER GLOBAL
                        </div>
                    </div>
                    <input type="hidden" name="chonGame" id="chonGame" value="com.vng.playtogether">
                </div>
            </div>

            <div class="service__form-group">
                <label><i class="fas fa-hourglass-end"></i> HẠN SỬ DỤNG <img src="<?php echo e(asset('assets/img/icons/vip-pass.gif')); ?>" style="width:22px; height:22px; vertical-align:middle; margin-left:6px;" alt="VIP"></label>
                <div class="select-wrapper" id="timeSelectWrap">
                    <div class="selected" data-value="D">
                        1 Ngày - 15.000 VND
                        <span class="arrow"></span>
                    </div>
                    <div class="options">
                        <div class="option" data-value="D">1 Ngày - 15.000 VND</div>
                        <div class="option" data-value="W">1 Tuần - 70.000 VND</div>
                        <div class="option" data-value="2W">2 Tuần - 100.000 VND</div>
                        <div class="option" data-value="3W">3 Tuần - 130.000 VND</div>
                        <div class="option" data-value="M">1 Tháng - 170.000 VND</div>
                        <div class="option" data-value="F">Vĩnh Viễn - 3.000.000 VND</div>
                    </div>
                    <input type="hidden" name="time_type" id="time_type" value="D">
                     <div style="font-size: 12px; color: #94a3b8; font-style: italic; margin-top: 4px; opacity: 0.7;">
                    💡Key Vip Sẽ Dùng Đủ Thời Gian Đã Mua
                </div>
                </div>
            </div>

            <div class="service__form-group">
                <label for="somay"><i class="fas fa-desktop"></i> SỐ MÁY SỬ DỤNG</label>
                <input type="number" name="somay" id="somay" class="service__form-control" min="1" value="1" required>
                <div style="font-size: 12px; color: #94a3b8; font-style: italic; margin-top: 4px; opacity: 0.7;">
                    💡Từ Máy Thứ 2 Giảm 50% Giá Key
                </div>
            </div>

            <div class="service__form-group">
                <label for="discount_code"><i class="fa-solid fa-gift"></i> MÃ GIẢM GIÁ</label>
                <input type="text" name="discount_code" id="discount_code" class="service__form-control" placeholder="Nhập Mã Nếu Có" value="<?php echo e(old('discount_code')); ?>">
                <div id="discountMessage" class="discount-message"></div>
            </div>

            <div class="text-center mt-3 mb-3">
                <h3 class="font-weight-bold mb-2">TỔNG TIỀN: <span class="text-danger" id="totalPrice">0</span> VND</h3>
                <a href="/hacks" class="d-inline-block" style="font-size: 12px; text-decoration: none; color: #94a3b8; opacity: 0.7; font-style: italic;">
                    <i class="fas fa-download"></i> Click Để Tải Hack
                </a>
            </div>

            <div class="text-center">
                <?php if(Auth::check()): ?>
                    <button type="submit" class="service__btn service__btn--primary" style="min-width: 300px;">
                        <i class="fas fa-shopping-cart mr-2"></i> THANH TOÁN
                    </button>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="service__btn service__btn--primary" style="min-width: 300px;">ĐĂNG NHẬP ĐỂ MUA</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Lịch sử mua key -->
    <div>
        <div class="history-header">LỊCH SỬ MUA KEY </div>
        <div class="history-table-container">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>GAME PLAY</th>
                        <th>KEY</th>
                        <th>THIẾT BỊ</th>
                        <th>HẠN SỬ DỤNG</th>
                        <th>GIÁ TIỀN</th>
                        <th>NGÀY MUA</th>
                        <th>NGÀY HẾT HẠN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr data-purchase-id="<?php echo e($purchase->id); ?>" data-reset-count="<?php echo e($purchase->reset_count ?? 0); ?>">
                        <td>
                            <div class="game-cell">
                                <?php if($purchase->game === 'com.vng.playtogether'): ?>
                                    <img src="<?php echo e(asset('images/vng.png')); ?>" class="game-icon-img" title="VNG">
                                <?php elseif($purchase->game === 'com.haegin.playtogether'): ?>
                                    <img src="<?php echo e(asset('images/global.png')); ?>" class="game-icon-img" title="Global">
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e($purchase->game); ?></span>
                                <?php endif; ?>
                                
                                <button class="btn-reset-device reset-trigger" 
                                        data-key="<?php echo e($purchase->key_value); ?>" 
                                        title="Reset tất cả thiết bị (Xóa session cũ)">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="key-cell">
                                <span><?php echo e(substr($purchase->key_value, 0, 5) . '...'); ?></span>
                                <button class="copy-btn" data-clipboard-text="<?php echo e($purchase->key_value); ?>" title="Copy đầy đủ key">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="device-loading-container" data-key="<?php echo e($purchase->key_value); ?>">
                                <span class="device-badge">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark"><?php echo e($purchase->time_use); ?></span>
                        </td>
                        <td>
                            <span class="price-badge"><?php echo e(number_format($purchase->price)); ?></span>
                        </td>
                        <td>
                            <div class="time-badge"><?php echo e($purchase->created_at->format('H:i:s - d/m/Y')); ?></div>
                        </td>
                        <td>
                            <div class="expiry-loading-container" data-key="<?php echo e($purchase->key_value); ?>">
                                <span class="device-badge">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Chưa có giao dịch nào</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            <?php if($purchases instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                <?php echo e($purchases->links()); ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Custom Modal -->
<div class="custom-modal-overlay" id="customModal">
    <div class="custom-modal">
        <div class="custom-modal-header">
            <h3 class="custom-modal-title" id="modalTitle">Modal Title</h3>
            <button class="custom-modal-close" onclick="closeCustomModal()">&times;</button>
        </div>
        <div class="custom-modal-body" id="modalBody">
            <!-- Content will be injected here -->
        </div>
        <div class="custom-modal-footer" id="modalFooter">
            <!-- Buttons will be injected here -->
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // ---------------- CLIPBOARD ----------------
    new ClipboardJS('.copy-btn').on('success', function(e) {
        const btn = e.trigger;
        const icon = btn.querySelector('i');
        icon.className = 'fas fa-check text-success';
        setTimeout(() => icon.className = 'fas fa-copy', 2000);
        e.clearSelection();
    });

    // ---------------- PRICING LOGIC ----------------
    const priceList = {'D': 15000, 'W': 70000, '2W': 100000, '3W': 130000, 'M': 170000, 'F': 3000000};
    const timeInp = document.getElementById('time_type');
    const somayInp = document.getElementById('somay');
    const discInp = document.getElementById('discount_code');
    const totalDisp = document.getElementById('totalPrice');
    const discMsg = document.getElementById('discountMessage');

    function formatNum(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); }

    function updatePrice() {
        const pPerKey = priceList[timeInp.value] || 0;
        const count = parseInt(somayInp.value) || 1;
        const total = pPerKey + ((count - 1) * (pPerKey / 2));
        
        const code = discInp.value.trim();
        if(!code) {
            totalDisp.innerText = formatNum(Math.round(total));
            discMsg.style.display = 'none';
            return;
        }

        discMsg.className = 'discount-message loading';
        discMsg.innerText = 'Đang kiểm tra mã...';
        discMsg.style.display = 'block';

        fetch('<?php echo e(route('discount.check')); ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'},
            body: JSON.stringify({ code: code, total: total })
        })
        .then(r => r.json())
        .then(data => {
            discMsg.innerText = data.message;
            if(data.success) {
                discMsg.className = 'discount-message success';
                totalDisp.innerText = formatNum(Math.round(total - (data.discount || 0)));
            } else {
                discMsg.className = 'discount-message error';
                totalDisp.innerText = formatNum(Math.round(total));
            }
        });
    }

    timeInp.addEventListener('change', updatePrice);
    somayInp.addEventListener('input', updatePrice);
    discInp.addEventListener('input', updatePrice);
    updatePrice();

    // ---------------- SELECT UI ----------------
    function initSelect(id) {
        const w = document.getElementById(id);
        const s = w.querySelector('.selected');
        const inp = w.querySelector('input');
        s.onclick = (e) => {
            e.stopPropagation();
            w.classList.toggle('open');
        };
        w.querySelectorAll('.option').forEach(o => {
            o.onclick = () => {
                s.innerHTML = o.innerHTML + '<span class="arrow"></span>';
                inp.value = o.dataset.value;
                inp.dispatchEvent(new Event('change'));
                w.classList.remove('open');
            };
        });
        // Close when click outside
        document.addEventListener('click', (e) => {
            if (!w.contains(e.target)) {
                w.classList.remove('open');
            }
        });
    }
    initSelect('customSelect');
    initSelect('timeSelectWrap');

    // ---------------- DEVICE INFO LOADING ----------------
    const loadDeviceForKeys = () => {
        document.querySelectorAll('.device-loading-container').forEach(cont => {
            const key = cont.dataset.key;
            const row = cont.closest('tr');
            const expiryCont = row.querySelector('.expiry-loading-container');
            const resetBtn = row.querySelector('.reset-trigger');
            
            fetch('<?php echo e(route('user.ajax.get-key-details')); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'},
                body: JSON.stringify({ key_value: key })
            })
            .then(r => r.json())
            .then(res => {
                if(res.success) {
                    const d = res.data;
                    const count = d.devices ? d.devices.length : 0;
                    const limit = d.device_limit || 1;
                    const hv_id = d.id;
                    
                    // Device count - show green if key is activated (has expires_at) OR has devices
                    const isActivated = d.expires_at && d.expires_at !== null;
                    let html = `<span class="device-badge ${isActivated || count > 0 ? 'active' : ''}">
                        ${count}/${limit}
                    </span>`;
                    
                    if(count > 0) {
                        html += `<button class="btn-view-devices" data-hv-id="${hv_id}" data-key="${key}" title="Danh Sách Devices">Xem</button>`;
                    }
                    
                    cont.innerHTML = html;
                    cont.dataset.hvId = hv_id;

                    // Show reset button only if key is activated AND has devices
                    if (d.expires_at && d.expires_at !== null && count > 0 && resetBtn) {
                        resetBtn.classList.add('show');
                        resetBtn.dataset.hvId = hv_id;
                        // Store purchase_id from row data attribute
                        const purchaseId = row.dataset.purchaseId;
                        if (purchaseId) {
                            resetBtn.dataset.purchaseId = purchaseId;
                        }
                    }

                    // Expiry Date calculation
                    let expiryDate;
                    if (d.expires_at && d.expires_at !== null) {
                        // Key đã kích hoạt - dùng expires_at
                        expiryDate = new Date(d.expires_at);
                    } else {
                        // Key chưa kích hoạt - tính từ created_at + duration
                        expiryDate = new Date(d.created_at);
                        const durationValue = d.duration_value || 1;
                        const durationType = d.duration_type || 'day';
                        
                        if (durationType === 'day') {
                            expiryDate.setDate(expiryDate.getDate() + durationValue);
                        } else if (durationType === 'month') {
                            expiryDate.setMonth(expiryDate.getMonth() + durationValue);
                        } else if (durationType === 'year') {
                            expiryDate.setFullYear(expiryDate.getFullYear() + durationValue);
                        }
                    }

                    // Format: HH:MM:SS - DD/MM/YYYY
                    const hours = String(expiryDate.getHours()).padStart(2, '0');
                    const minutes = String(expiryDate.getMinutes()).padStart(2, '0');
                    const seconds = String(expiryDate.getSeconds()).padStart(2, '0');
                    const day = String(expiryDate.getDate()).padStart(2, '0');
                    const month = String(expiryDate.getMonth() + 1).padStart(2, '0');
                    const year = expiryDate.getFullYear();
                    
                    const formattedExpiry = `${hours}:${minutes}:${seconds} - ${day}/${month}/${year}`;
                    
                    if (expiryCont) {
                        expiryCont.innerHTML = `<span class="time-badge">${formattedExpiry}</span>`;
                    }
                    
                } else {
                    cont.innerHTML = '<span class="text-danger" style="font-size:11px">SQL/DB Key</span>';
                    if (expiryCont) {
                        expiryCont.innerHTML = '<span class="text-muted" style="font-size:11px">N/A</span>';
                    }
                }
            });
        });
    };
    loadDeviceForKeys();

    // ---------------- CUSTOM MODAL HELPERS ----------------
    window.openCustomModal = function(title, bodyHTML, footerButtons = []) {
        document.getElementById('modalTitle').innerHTML = title;
        document.getElementById('modalBody').innerHTML = bodyHTML;
        
        const footer = document.getElementById('modalFooter');
        footer.innerHTML = '';
        footerButtons.forEach(btn => {
            const button = document.createElement('button');
            button.className = `modal-btn ${btn.class || 'modal-btn-secondary'}`;
            button.innerHTML = btn.text;
            button.onclick = btn.onclick;
            footer.appendChild(button);
        });
        
        document.getElementById('customModal').classList.add('active');
    };
    
    window.closeCustomModal = function() {
        document.getElementById('customModal').classList.remove('active');
    };
    
    // Close modal when clicking overlay
    document.getElementById('customModal').addEventListener('click', (e) => {
        if (e.target.id === 'customModal') {
            closeCustomModal();
        }
    });

    // Helper function to format datetime
    function formatDateTime(dateStr) {
        if (!dateStr || dateStr === 'N/A') return 'N/A';
        const date = new Date(dateStr);
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${hours}:${minutes}:${seconds} - ${day}/${month}/${year}`;
    }

    // ---------------- VIEW DEVICES MODAL ----------------
    document.addEventListener('click', e => {
        // Check if click is on button or icon inside
        const viewBtn = e.target.closest('.btn-view-devices');
        if(viewBtn) {
            const key = viewBtn.dataset.key;
            const hvId = viewBtn.dataset.hvId;
            
            openCustomModal('Danh Sách Devices', '<div style="text-align:center;padding:20px;"><i class="fas fa-spinner fa-spin fa-2x"></i></div>', [
                {text: 'Đóng', class: 'modal-btn-secondary', onclick: closeCustomModal}
            ]);
            
            fetch('<?php echo e(route('user.ajax.get-key-details')); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'},
                body: JSON.stringify({ key_value: key })
            })
            .then(r => r.json())
            .then(res => {
                if(res.success && res.data.devices && res.data.devices.length > 0) {
                    let listHtml = '<div style="max-height:400px; overflow-y:auto;">';
                    res.data.devices.forEach((d, i) => {
                        const lastLogin = formatDateTime(d.last_login_at);
                        listHtml += `
                            <div style="padding:16px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; ${i === res.data.devices.length - 1 ? 'border-bottom:none;' : ''}">
                                <div style="flex:1;">
                                    <div style="font-size:13px; color:#64748b; line-height:1.8;">
                                        <div><strong style="color:#1e293b;">ID:</strong> ${d.device_id}</div>
                                        <div><strong style="color:#1e293b;">Lần Login Cuối:</strong> ${lastLogin}</div>
                                    </div>
                                </div>
                                <button class="modal-btn modal-btn-danger" style="padding:8px 12px; font-size:13px;" 
                                    onclick="deleteDevice('${hvId}', '${d.device_id}', '${key}')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </div>
                        `;
                    });
                    listHtml += '</div>';
                    
                    openCustomModal(`<i class="fas fa-key"></i> Key: ${key}`, listHtml, [
                        {text: 'Đóng', class: 'modal-btn-primary', onclick: closeCustomModal}
                    ]);
                } else {
                    openCustomModal('<i class="fas fa-info-circle"></i> Không có thiết bị', '<div style="text-align:center; padding:20px; color:#64748b;">Key này chưa được kích hoạt trên thiết bị nào</div>', [
                        {text: 'Đóng', class: 'modal-btn-secondary', onclick: closeCustomModal}
                    ]);
                }
            });
        }

        // RESET TRIGGER
        if(e.target.closest('.reset-trigger')) {
            const btn = e.target.closest('.reset-trigger');
            const key = btn.dataset.key;
            const hvId = btn.dataset.hvId;
            const purchaseId = btn.dataset.purchaseId;
            const row = btn.closest('tr');
            const resetCount = parseInt(row.dataset.resetCount || 0);

            if(!hvId || !purchaseId) {
                openCustomModal('<i class="fas fa-exclamation-triangle"></i> Lỗi', '<div style="text-align:center; padding:20px;">Key này không hỗ trợ reset qua API (Key DB)</div>', [
                    {text: 'Đóng', class: 'modal-btn-secondary', onclick: closeCustomModal}
                ]);
                return;
            }

            // Check reset fee
            const resetFee = resetCount >= 1 ? 5000 : 0;
            const feeText = resetFee > 0 
                ? `<div style="background:#fff3cd; padding:12px; border-radius:8px; margin-top:12px; border:1px solid #ffc107;">
                      <i class="fas fa-info-circle" style="color:#856404;"></i>
                      <strong style="color:#856404;"> Phí reset key: ${resetFee.toLocaleString()} VND</strong>
                   </div>`
                : `<div style="background:#dcfce7; padding:12px; border-radius:8px; margin-top:12px; border:1px solid #86efac;">
                      <i class="fas fa-gift" style="color:#166534;"></i>
                      <strong style="color:#166534;"> Miễn phí reset lần đầu!</strong>
                   </div>`;

            openCustomModal(
                '<i class="fas fa-exclamation-triangle" style="color:#ff0000ff;"></i> XÁC NHẬN ?', 
                `<div style="padding:20px; text-align:center;">
                    <div style="font-size:15px; color:#64748b; line-height:1.6;">
                        Toàn bộ thiết bị đang liên kết với key<br/>
                        <strong style="color:#1e293b;">${key}</strong><br/>
                        sẽ bị gỡ bỏ và key có thể được sử dụng lại.
                    </div>
                    ${feeText}
                </div>`,
                [
                    {text: 'Hủy', class: 'modal-btn-secondary', onclick: closeCustomModal},
                    {text: '<i class="fas fa-sync-alt"></i> Reset', class: 'modal-btn-danger', onclick: () => {
                        openCustomModal('Đang xử lý...', '<div style="text-align:center;padding:40px;"><i class="fas fa-spinner fa-spin fa-3x" style="color:#667eea;"></i></div>', []);
                        
                        fetch('<?php echo e(route('user.ajax.reset-devices')); ?>', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'},
                            body: JSON.stringify({ hackviet_id: hvId, purchase_id: purchaseId })
                        })
                        .then(r => r.json())
                        .then(res => {
                            if(res.success) {
                                openCustomModal('<i class="fas fa-check-circle" style="color:#10b981;"></i> Thành công', 
                                    `<div style="text-align:center; padding:30px;">
                                        <i class="fas fa-check-circle" style="font-size:60px; color:#10b981; margin-bottom:16px;"></i>
                                        <div style="color:#166534; font-size:16px;">${res.message}</div>
                                    </div>`, [
                                    {text: 'Đóng', class: 'modal-btn-primary', onclick: () => {
                                        closeCustomModal();
                                        location.reload(); // Reload to update reset_count
                                    }}
                                ]);
                            } else {
                                openCustomModal('<i class="fas fa-times-circle" style="color:#ef4444;"></i> Thất bại', 
                                    `<div style="text-align:center; padding:30px;">
                                        <i class="fas fa-times-circle" style="font-size:60px; color:#ef4444; margin-bottom:16px;"></i>
                                        <div style="color:#991b1b; font-size:15px;">${res.error || 'Lỗi không xác định'}</div>
                                    </div>`, [
                                    {text: 'Đóng', class: 'modal-btn-secondary', onclick: closeCustomModal}
                                ]);
                            }
                        });
                    }}
                ]
            );
        }
    });

    // Global function for delete device
    window.deleteDevice = function(hvId, deviceId, key) {
        openCustomModal(
            '<i class="fas fa-exclamation-triangle" style="color:#ef4444;"></i> Xác nhận xóa thiết bị?',
            `<div style="text-align:center; padding:20px;">
                <div style="font-size:14px; color:#64748b;">
                    Bạn có chắc muốn xóa thiết bị<br/>
                    <strong style="color:#1e293b;">${deviceId}</strong><br/>
                    khỏi key <strong>${key}</strong>?
                </div>
            </div>`,
            [
                {text: 'Hủy', class: 'modal-btn-secondary', onclick: closeCustomModal},
                {text: '<i class="fas fa-trash"></i> Xóa ngay', class: 'modal-btn-danger', onclick: () => {
                    openCustomModal('Đang xử lý...', '<div style="text-align:center;padding:40px;"><i class="fas fa-spinner fa-spin fa-3x" style="color:#667eea;"></i></div>', []);
                    
                    fetch('<?php echo e(route('user.ajax.delete-device')); ?>', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'},
                        body: JSON.stringify({ hackviet_id: hvId, device_id: deviceId })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if(res.success) {
                            openCustomModal('<i class="fas fa-check-circle" style="color:#10b981;"></i> Thành công', 
                                `<div style="text-align:center; padding:30px;">
                                    <i class="fas fa-check-circle" style="font-size:60px; color:#10b981; margin-bottom:16px;"></i>
                                    <div style="color:#166534;">${res.message}</div>
                                </div>`, [
                                {text: 'Đóng', class: 'modal-btn-primary', onclick: () => {
                                    closeCustomModal();
                                    loadDeviceForKeys(); // Reload device count
                                }}
                            ]);
                        } else {
                            openCustomModal('<i class="fas fa-times-circle" style="color:#ef4444;"></i> Lỗi', 
                                `<div style="text-align:center; padding:20px; color:#991b1b;">${res.error || 'Không thể xóa thiết bị'}</div>`, [
                                {text: 'Đóng', class: 'modal-btn-secondary', onclick: closeCustomModal}
                            ]);
                        }
                    });
                }}
            ]
        );
    };
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/buy-key.blade.php ENDPATH**/ ?>