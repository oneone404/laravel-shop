

<?php $__env->startSection('title', 'Nhập Code'); ?>

<?php $__env->startSection('content'); ?>

    <?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'HỆ THỐNG NHẬP CODE','description' => 'Nhập mã quà tặng của bạn để nhận phần thưởng hấp dẫn ngay lập tức.'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
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

    <div class="container mt-4">
        <div class="service__form">
            <h3 class="service__form-title"><i class="fas fa-gift mr-2"></i> NHẬP CODE HÀNG LOẠT</h3>

            <form id="gift-code-form">
                <div class="service__form-row">
                    <div class="service__form-group">
                        <label for="roleIds"><i class="fas fa-id-card"></i> DANH SÁCH ID GAME</label>
                        <textarea class="service__form-control glass-textarea" id="roleIds" name="roleIds"
                            placeholder="Mỗi ID một dòng&#10;Định dạng: xxxx-xxxx-xxxx" required></textarea>
                        <div class="helper-text">
                            <i class="fas fa-info-circle"></i> Định dạng: xxxx-xxxx-xxxx
                        </div>
                    </div>

                    <div class="service__form-group">
                        <label for="codes"><i class="fas fa-ticket-alt"></i> DANH SÁCH MÃ CODE</label>
                        <textarea class="service__form-control glass-textarea" id="codes" name="codes"
                            placeholder="Mỗi mã một dòng" required></textarea>
                        <div class="helper-text">
                            <i class="fas fa-info-circle"></i> Mỗi mã tối thiểu 6 ký tự
                        </div>
                    </div>
                </div>

                <div id="validation-error" class="service__alert service__alert--error" style="display: none;"></div>

                <div class="text-center mt-4">
                    <button type="submit" class="service__btn service__btn--primary" id="submitBtn"
                        style="min-width: 280px;">
                        <i class="fas fa-rocket mr-2"></i> BẮT ĐẦU XỬ LÝ
                    </button>
                </div>
            </form>

            <div id="bulk-results" class="bulk-results"></div>
        </div>
    </div>

    <script>
        document.getElementById('gift-code-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const roleIdsRaw = document.getElementById('roleIds').value.trim();
            const codesRaw = document.getElementById('codes').value.trim();
            const resultContainer = document.getElementById('bulk-results');
            const errorDiv = document.getElementById('validation-error');
            const submitBtn = document.getElementById('submitBtn');

            errorDiv.style.display = 'none';
            resultContainer.innerHTML = '';

            // 1. Parse and Validate
            const roleIds = roleIdsRaw.split('\n').map(s => s.trim()).filter(s => s !== '');
            const codes = codesRaw.split('\n').map(s => s.trim()).filter(s => s !== '');

            if (roleIds.length === 0 || codes.length === 0) {
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span>Vui lòng nhập ít nhất một ID và một mã code!</span>';
                errorDiv.style.display = 'flex';
                return;
            }

            // Game ID Regex: xxxx-xxxx-xxxx
            const idRegex = /^[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}$/;
            const invalidIds = roleIds.filter(id => !idRegex.test(id));
            if (invalidIds.length > 0) {
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> <span>ID sau không đúng định dạng (xxxx-xxxx-xxxx): <strong>${invalidIds[0]}</strong></span>`;
                errorDiv.style.display = 'flex';
                return;
            }

            const invalidCodes = codes.filter(c => c.length < 6);
            if (invalidCodes.length > 0) {
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> <span>Mã code phải từ 6 ký tự trở lên: <strong>${invalidCodes[0]}</strong></span>`;
                errorDiv.style.display = 'flex';
                return;
            }

            // 2. Start Processing
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> ĐANG XỬ LÝ...';

            // Create initial status list
            const queue = [];
            roleIds.forEach(id => {
                codes.forEach(code => {
                    const idSafe = id.replace(/-/g, '_'); // For element ID
                    const taskKey = `${idSafe}-${code}`.replace(/[^a-zA-Z0-9_]/g, '');
                    const resultId = `res-${taskKey}`;
                    const item = document.createElement('div');
                    item.className = 'result-item';
                    item.id = resultId;
                    item.innerHTML = `
                            <span class="result-id"><i class="fas fa-user-circle"></i> ${id}</span>
                            <span class="result-code"><i class="fas fa-barcode"></i> ${code}</span>
                            <span class="result-status status-pending"><i class="fas fa-clock"></i> Đang chờ...</span>
                        `;
                    resultContainer.appendChild(item);
                    queue.push({ id, code, resultId });
                });
            });

            // 3. Process each pair
            for (const task of queue) {
                const statusEl = document.querySelector(`#${task.resultId} .result-status`);
                statusEl.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Đang gửi...';

                try {
                    const response = await fetch('https://accone.vn/api/nap-zing/gift-code', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ roleId: task.id, code: task.code })
                    });
                    const data = await response.json();

                    if (data.status) {
                        statusEl.className = 'result-status status-success';
                        statusEl.innerHTML = '<i class="fas fa-check-circle"></i> THÀNH CÔNG';
                    } else {
                        statusEl.className = 'result-status status-error';
                        statusEl.innerHTML = `<i class="fas fa-times-circle"></i> ${data.message || 'THẤT BẠI'}`;
                    }
                } catch (error) {
                    statusEl.className = 'result-status status-error';
                    statusEl.innerHTML = '<i class="fas fa-exclamation-triangle"></i> LỖI KẾT NỐI';
                }

                // Add small delay
                await new Promise(r => setTimeout(r, 400));
            }

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-rocket mr-2"></i> BẮT ĐẦU XỬ LÝ';
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/apps/gift-code.blade.php ENDPATH**/ ?>