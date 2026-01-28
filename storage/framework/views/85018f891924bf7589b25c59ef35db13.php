

<?php $__env->startSection('title', 'Trung Tâm Công Cụ'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .tools-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .tool-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 25px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: relative;
            overflow: hidden;
        }

        .tool-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
        }

        .tool-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--glass-shadow);
            border-color: var(--primary-light);
        }

        .tool-card:hover::before {
            opacity: 0.05;
        }

        .tool-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            margin-bottom: 10px;
            box-shadow: 0 8px 15px rgba(14, 62, 218, 0.2);
            position: relative;
            z-index: 1;
        }

        .tool-info {
            position: relative;
            z-index: 1;
        }

        .tool-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 8px;
        }

        .tool-desc {
            font-size: 1.4rem;
            color: var(--text-light);
            line-height: 1.6;
            min-height: 45px;
        }

        .tool-action {
            margin-top: auto;
            position: relative;
            z-index: 1;
        }

        .btn-tool {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }

        .tool-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(14, 62, 218, 0.1);
            color: var(--primary-color);
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 1.1rem;
            font-weight: 700;
            z-index: 1;
        }

        .dark-mode .tool-tag {
            background: rgba(0, 212, 255, 0.1);
            color: #00d4ff;
        }

        .dark-mode .tool-name {
            color: #fff;
        }

        .tool-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 1.2rem;
            color: #10b981;
            font-weight: 600;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 8px #10b981;
        }
    </style>

    <?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => 'TRUNG TÂM CÔNG CỤ','description' => 'Khám phá các công cụ hỗ trợ trải nghiệm game tối ưu nhất cho game thủ.'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
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

    <div class="tools-container">
        <div class="tools-grid">
            <!-- Gift Code Tool -->
            <div class="tool-card">
                <span class="tool-tag">Mới</span>
                <div class="tool-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="tool-info">
                    <div class="tool-status">
                        <span class="status-dot"></span> Đang hoạt động
                    </div>
                    <h3 class="tool-name">Nhập Gift Code</h3>
                    <p class="tool-desc">Hệ thống nhập mã quà tặng Zing với tỷ lệ thành công cao, nhận quà ngay lập tức.</p>
                </div>
                <div class="tool-action">
                    <a href="<?php echo e(route('gift-code')); ?>" class="btn btn--primary btn-tool">
                        Sử Dụng Ngay <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Future Tools Placeholders -->
            <div class="tool-card" style="opacity: 0.7;">
                <div class="tool-icon" style="background: linear-gradient(135deg, #64748b, #94a3b8);">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="tool-info">
                    <div class="tool-status" style="color: #64748b;">
                        <i class="fas fa-clock"></i> Sắp ra mắt
                    </div>
                    <h3 class="tool-name">Check Scammer</h3>
                    <p class="tool-desc">Cơ sở dữ liệu kiểm tra người chơi có hành vi lừa đảo trong giao dịch.</p>
                </div>
                <div class="tool-action">
                    <button class="btn btn--outline btn-tool" disabled>Chưa ra mắt</button>
                </div>
            </div>

            <div class="tool-card" style="opacity: 0.7;">
                <div class="tool-icon" style="background: linear-gradient(135deg, #64748b, #94a3b8);">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="tool-info">
                    <div class="tool-status" style="color: #64748b;">
                        <i class="fas fa-clock"></i> Sắp ra mắt
                    </div>
                    <h3 class="tool-name">Tính Toán Sức Mạnh</h3>
                    <p class="tool-desc">Công cụ đo lường chỉ số nhân vật và dự đoán lực chiến trong game.</p>
                </div>
                <div class="tool-action">
                    <button class="btn btn--outline btn-tool" disabled>Chưa ra mắt</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/tools.blade.php ENDPATH**/ ?>