

<?php $__env->startSection('title', 'Nhập Code'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .container {
        padding: 0px;
    }
    .card {
        max-width: 500px;
        margin: 0 auto;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 10px;
    }
    .btn-primary {
        width: 100%;
    }
</style>

<?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'Nhập Code','description' => ''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
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
    <div class="card p-4">
        <h4 class="mb-4 text-center">Nhập Code Game</h4>

        <form id="gift-code-form">
            <div class="mb-3">
                <label for="roleId" class="form-label">ID Game:</label>
                <input type="text" class="form-control" id="roleId" name="roleId" placeholder="Nhập ID Game" required>
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Code:</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Nhập Mã Code" required>
            </div>

            <button type="submit" class="btn btn-primary">Nhập Code</button>
        </form>

        <div id="result" class="mt-4"></div>
    </div>
</div>

<script>
    document.getElementById('gift-code-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const roleId = document.getElementById('roleId').value.trim();
        const code = document.getElementById('code').value.trim();
        const resultDiv = document.getElementById('result');

        if (!roleId || !code) {
            resultDiv.innerHTML = `<div class="alert alert-warning">Vui Lòng Nhập Đầy Đủ Thông Tin</div>`;
            return;
        }

        resultDiv.innerHTML = `<div class="alert alert-info">...</div>`;

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
                        Success<br>
                        <strong>Nhập Thành Công</strong><br>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        Error<br>
                        <strong>Lỗi:</strong> Nhập Không Thành Công<br>
                    </div>
                `;
            }
        })
        .catch(error => {
            resultDiv.innerHTML = `<div class="alert alert-danger">⚠️ Lỗi kết nối: ${error}</div>`;
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/gift-code.blade.php ENDPATH**/ ?>