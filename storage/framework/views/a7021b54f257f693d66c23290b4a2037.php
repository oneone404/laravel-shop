

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

    <div class="gift-code-container">
        <div class="gift-card">
            <h4 class="text-center"><i class="fas fa-gift"></i> NHẬP MÃ QUÀ TẶNG</h4>

            <form id="gift-code-form">
                <div class="form-group">
                    <label for="roleId" class="form-label">ID Nhân Vật / ID Game:</label>
                    <input type="text" class="glass-input" id="roleId" name="roleId" placeholder="Ví dụ: 12345678" required>
                </div>

                <div class="form-group">
                    <label for="code" class="form-label">Mã Code (Gift Code):</label>
                    <input type="text" class="glass-input" id="code" name="code" placeholder="Nhập mã code của bạn"
                        required>
                </div>

                <button type="submit" class="btn btn--primary btn-submit">
                    XÁC NHẬN NHẬP CODE <i class="fas fa-check-circle ml-2"></i>
                </button>
            </form>

            <div id="result"></div>
        </div>
    </div>

    <script>
        document.getElementById('gift-code-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const roleId = document.getElementById('roleId').value.trim();
            const code = document.getElementById('code').value.trim();
            const resultDiv = document.getElementById('result');

            if (!roleId || !code) {
                resultDiv.innerHTML = `<div class="alert alert-danger">⚠️ Vui lòng nhập đầy đủ thông tin!</div>`;
                return;
            }

            resultDiv.innerHTML = `<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Đang xử lý, vui lòng đợi...</div>`;

            fetch('https://accone.vn/api/nap-zing/gift-code', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ roleId, code })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        resultDiv.innerHTML = `
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> <strong>Thành công!</strong> Code đã được kích hoạt cho ID ${roleId}.
                                </div>
                            `;
                        document.getElementById('gift-code-form').reset();
                    } else {
                        resultDiv.innerHTML = `
                                <div class="alert alert-danger">
                                    <i class="fas fa-times-circle"></i> <strong>Lỗi:</strong> ${data.message || 'Mã code không hợp lệ hoặc đã hết hạn.'}
                                </div>
                            `;
                    }
                })
                .catch(error => {
                    resultDiv.innerHTML = `<div class="alert alert-danger">⚠️ Lỗi hệ thống: Không thể kết nối đến máy chủ.</div>`;
                });
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/apps/gift-code.blade.php ENDPATH**/ ?>