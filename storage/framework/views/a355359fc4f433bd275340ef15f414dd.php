
<?php $__env->startSection('title', $category->name); ?>
<?php $__env->startSection('content'); ?>
<style>
.container.no-padding { padding: 0; }

/* Grid responsive */
.account-grid-new {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    padding: 0;
}

@media (min-width: 768px) {
    .account-grid-new { grid-template-columns: repeat(4, 1fr); gap: 12px; }
}

/* Account card */
.account-item {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    overflow: hidden;
    transition: transform 0.2s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.account-item:hover { transform: translateY(-2px); }

/* Image */
.account-image-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16/9;
    overflow: hidden;
    border-radius: 8px;
    cursor: zoom-in;
}

.account-image { width: 100%; height: 100%; object-fit: cover; }

.account-code {
    position: absolute;
    top: 6px;
    left: 6px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    font-size: 6px;
    padding: 2px 6px;
    border-radius: 4px;
}

.eye-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 999px;
    padding: 1px 10px;
    cursor: pointer;
}

/* Info section */
.account-info-wrapper { 
    padding: 5px 5px 5px; 
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.account-badges {
    display: flex;
    flex-direction: column;
    gap: 6px;
    align-items: flex-start; /* căn trái */
    margin-top: 10px;
    flex-grow: 1;
    justify-content: center; /* Căn giữa dọc badge */
}

.account-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 3px 6px;
    border-radius: 6px;
    font-size: 8px;
    font-weight: 600;
    width: 100%;
    color: #fff;
}

/* Badge 1 – Blue */
.account-badge:nth-child(1) {
    background: #3B82F6;
}

/* Badge 2 – Cyan */
.account-badge:nth-child(2) {
    background: #06B6D4;
}

/* Badge 3 – Purple */
.account-badge:nth-child(3) {
    background: #8B5CF6;
}

/* Badge 4 – Orange */
.account-badge:nth-child(4) {
    background: #F97316;
}

/* Badge 5 – Green */
.account-badge:nth-child(5) {
    background: #10B981;
}

/* Icon chung */
.account-badge i {
    font-size: 10px;
}


@media (min-width: 768px) {
    .account-badge { font-size: 10px; padding: 4px 8px; }
    .account-badge i {
    font-size: 12px;
}
}

/* Product Badge - y chang home */
.product-stats {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 6px;
  flex-wrap: nowrap;
  margin-bottom: 12px;
}

.divider {
  font-weight: 700;
  color: #aaa;
  font-size: 14px;
}

.product-badge {
  font-size: 0.75rem;
  font-weight: 800;
  padding: 3px 10px;
  border-radius: 8px;
  background: transparent;
  border: 1.2px solid rgba(14, 62, 218, 0.25);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-image: linear-gradient(90deg, #0E3EDA, #0eb5daff);
}

.badge-number-sold {
  -webkit-background-clip: initial !important;
  -webkit-text-fill-color: initial !important;
  color: #ff4444 !important;
  font-weight: 900;
}

.badge-number-available {
  -webkit-background-clip: initial !important;
  -webkit-text-fill-color: initial !important;
  color: rgba(34, 197, 94, 1) !important;
  font-weight: 900;
}

/* Responsive cho PC */
@media (min-width: 768px) {
  .product-badge {
    font-size: 0.85rem;
    padding: 3px 8px;
  }
  .divider {
    padding: 3px;
  }
  .product-stats {
    gap: 8px;
  }
}

/* Responsive cho mobile nhỏ */
@media (max-width: 380px) {
  .product-badge {
    font-size: 0.65rem;
    padding: 2px 6px;
  }
  .product-stats {
    gap: 4px;
  }
}

/* Action section */
.account-action-wrapper { padding: 5px 8px 10px; text-align: center; }

.account-price {
    font-weight: bold;
    font-size: 13px;
    margin-bottom: 6px;
}

.account-button {
    display: block;
    width: calc(100% - 4px);
    margin: 0 auto 6px;
    background: #fff;
    border: 1.5px solid #0E3EDA;
    border-radius: 18px;
    padding: 5px 0;
    font-weight: 600;
    font-size: 12px;
    color: #0E3EDA;
    text-decoration: none;
    transition: 0.3s;
}

.account-button:hover { background: #0E3EDA; color: #fff; }

@media (min-width: 768px) {
    .account-price { font-size: 14px; }
    .account-button { font-size: 13px; padding: 7px 0; }
}

/* Image Viewer */
.viewer {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
}

.viewer.active { display: block; }

.viewer__backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(2px);
}

.viewer__wrap {
    position: absolute;
    inset: 0;
    display: grid;
    grid-template-rows: auto 1fr;
}

.viewer__toolbar {
    display: flex;
    justify-content: space-between;
    padding: 8px 12px;
    background: rgba(17,17,17,0.7);
    border-bottom: 1px solid rgba(255,255,255,0.08);
    user-select: none;
}

.viewer__toolbar-right { display: flex; gap: 8px; }

.viewer__btn {
    background: rgba(255,255,255,0.12);
    color: #fff;
    border: none;
    padding: 6px 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.viewer__btn:hover { background: rgba(255,255,255,0.2); }
.viewer__btn:active { transform: scale(0.97); }
.viewer__btn-close { background: #dc3545; }
.viewer__btn-close:hover { background: #b02a37; }

.viewer__stage {
    position: relative;
    overflow: hidden;
    display: grid;
    place-items: center;
    padding: 16px;
}

#viewerImage {
    max-width: 90vw;
    max-height: 80vh;
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.45);
    transform: translate3d(var(--tx, 0), var(--ty, 0), 0) scale(var(--scale, 1));
    transition: transform 0.08s ease;
    cursor: grab;
}

#viewerImage.dragging { cursor: grabbing; }

/* Purchase Modal */

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    overflow-y: auto;
    padding: 5px;
}

/* Modal Badge Styles */
.modal-badge-container {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: center;
}

.modal-badge-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
}

@media (max-width: 480px) {
    .modal-badge-item {
        padding: 3px 6px;
        font-size: 8px;
        gap: 3px;
        border-radius: 4px;
    }
    .modal-badge-item i {
        font-size: 8px;
    }
}

.modal__body {
    padding: 5px 5px;
    text-align: center;
}

#totalPrice {
    font-size: 32px;
    font-weight: bold;
    color: #0E3EDA;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #0E3EDA;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

.message-box {
    border-radius: 6px;
    font-weight: bold;
    font-size: 16px;

    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

#balanceError, #randomBalanceError {
    color: #D8000C;
    background: #FFD2D2;
    border: 1px solid #D8000C;
}

#successMessage, #randomSuccessMessage {
    color: #155724;
    background: #d4edda;
    border: 1px solid #28a745;
}

.modal__footer_random {
    padding: 20px;
    background: #f8f8f8;
    text-align: center;
}


@keyframes spin { to { transform: rotate(360deg); } }

.modal__discount-message {
    padding: 10px 10px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 500;
    display: none;
    transition: all 0.3s ease;
    text-align: center;
}

/* Price Section */
.modal__price-section {
    padding: 20px 15px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 12px;
    margin: 15px 0;
    border: 1.5px solid rgba(100, 116, 139, 0.15);
}

.modal__price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px dashed rgba(100, 116, 139, 0.2);
}

.modal__price-row:last-child {
    border-bottom: none;
}

.modal__price-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
}

.modal__price-value {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
}

.modal__discount-row {
    background: rgba(34, 197, 94, 0.08);
    border-radius: 6px;
    padding: 10px 12px;
    margin: 5px 0;
    border-bottom: none;
    animation: slideIn 0.3s ease;
}

.modal__discount-row .modal__price-label {
    color: #16a34a;
}

.modal__discount-value {
    color: #16a34a !important;
    font-size: 16px !important;
}

.modal__total-row {
    padding-top: 15px;
    margin-top: 8px;
    border-top: 2px solid rgba(14, 62, 218, 0.2) !important;
    border-bottom: none;
}

.modal__total-row .modal__price-label {
    font-size: 16px;
    color: #0E3EDA;
    font-weight: 700;
}

.modal__total-value {
    font-size: 20px !important;
    color: #0E3EDA !important;
    font-weight: 800 !important;
}

.modal__input {
    flex: 1;
    padding: 12px 15px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.modal__input:focus {
    outline: none;
    border-color: #0E3EDA;
}

.modal__btn--check {
    padding: 12px 25px;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal__btn--wallet {
    width: 100%;
    padding: 10px;
    background: linear-gradient(135deg, #0E3EDA, #0a2fb0);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    text-decoration: none;
    margin-bottom: 10px;
}

@media (min-width: 768px) {
    .modal__price-section {
        padding: 25px 20px;
    }

    .modal__price-label {
        font-size: 15px;
    }

    .modal__price-value {
        font-size: 16px;
    }

    .modal__discount-value {
        font-size: 17px !important;
    }

    .modal__total-row .modal__price-label {
        font-size: 17px;
    }

    .modal__total-value {
        font-size: 22px !important;
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.sl-overlay {
    background: rgba(0,0,0,0.9) !important;
}
.sl-image img {
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);
    transition: border-radius 0.3s ease;
}
.sl-navigation button {
    background: none !important;
    border: none;
    color: white !important;
    font-size: 1.2rem;
}

.sl-close {
    color: white !important;
    font-size: 1.6rem !important;
}
.account-views {
    font-size: 10px;
    font-weight: 600;
    color: rgba(0, 0, 0, 0.65); /* Đen mờ 65% - đẹp nhất */
    margin: 0 auto 0px;
    width: max-content;
    display: flex;
    align-items: center;
    gap: 3px;

    text-shadow: 0 1px 1px rgba(255,255,255,0.4);
    /* bóng sáng nhẹ giúp chữ nổi hơn trên nền ảnh cam */
    transition: 0.25s ease;
}

.account-views i {
    font-size: 12px;
}

.account-views:hover {
    color: rgba(0, 0, 0, 0.85);
    transform: translateY(-1px);
}

</style>

<?php if($category->type === 'random'): ?>
<style>
/* Căn giữa toàn bộ nội dung cho tài khoản Random */
.account-grid-new {
    grid-template-columns: 1fr !important; /* Mobile 1 cột */
    max-width: 800px;
    margin: 0 auto;
    display: grid !important;
    gap: 20px;
}

@media (min-width: 768px) {
    .account-grid-new {
        grid-template-columns: repeat(3, 1fr) !important; /* PC 3 cột */
        max-width: 1200px;
    }
}

.account-info-wrapper {
    text-align: center;
}

.product-stats {
    justify-content: center !important;
}
</style>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal676d920e8bb32a4c96cd6e6c6ba00de0 = $attributes; } ?>
<?php $component = App\View\Components\HeroHeader::resolve(['title' => ''.e($category->name).'','description' => ''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
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

<section class="account-section">
    <div class="container no-padding">
        <?php if($category->type === 'random'): ?>
            
            <?php
                // Lọc các nhóm random còn available và có accounts_data (đã được cast thành array)
                $randomGroups = $accounts->where('status', 'available')->filter(function($acc) {
                    $data = $acc->accounts_data;
                    return is_array($data) && count($data) > 0;
                });
                $totalAvailable = $randomGroups->sum(function($group) {
                    return count($group->accounts_data ?? []);
                });
                $soldCount = $accounts->sum('sold_count');
            ?>

            <?php if($randomGroups->count() > 0): ?>
            <div class="account-grid-new">
                <?php $__currentLoopData = $randomGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $groupAccounts = $group->accounts_data ?? [];
                        $groupCount = count($groupAccounts);
                        $badges = $group->note ? explode("\n", trim($group->note)) : [];
                        $icons = ['fa-star', 'fa-check-circle', 'fa-fire', 'fa-bolt', 'fa-gem', 'fa-shield-alt'];
                    ?>
                    <div class="account-item">
                        <a href="<?php echo e($group->thumb ?? $category->thumbnail); ?>" class="account-image-wrapper sl-link">
                            <img src="<?php echo e($group->thumb ?? $category->thumbnail); ?>" alt="<?php echo e($category->name); ?>" class="account-image">
                            <div class="account-code" style="font-size: 10px; padding: 4px 10px;">RANDOM</div>
                        </a>
                        <div class="account-info-wrapper" style="padding: 15px;">
                            
                            <div class="product-stats">
                                <span class="product-badge">ĐÃ BÁN <span class="badge-number-sold"><?php echo e(($group->sold_count ?? 0) + 50); ?></span></span>
                                <span class="divider">|</span>
                                <span class="product-badge">CÒN LẠI <span class="badge-number-available"><?php echo e($groupCount); ?></span></span>
                            </div>

                            
                            <div class="account-badges">
                                <?php if(count($badges) > 0): ?>
                                    <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(trim($badge)): ?>
                                            <div class="account-badge">
                                                <i class="fas <?php echo e($icons[array_rand($icons)]); ?>"></i> <?php echo e(trim($badge)); ?>

                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="account-badge">
                                        <i class="fas fa-star"></i> ZING ID
                                    </div>
                                    <div class="account-badge">
                                        <i class="fas fa-check-circle"></i> TRẮNG THÔNG TIN
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="account-action-wrapper" style="padding: 15px;">
                            <div class="account-price" style="font-size: 18px;">
                                <?php echo e(number_format($group->price, 0, '.', '.')); ?> VND
                            </div>
                            <?php if(auth()->guard()->check()): ?>
                                <button class="account-button" style="font-size: 14px; padding: 10px 0;"
                                        data-category-id="<?php echo e($category->id); ?>"
                                        data-group-id="<?php echo e($group->id); ?>"
                                        data-category-name="<?php echo e($category->name); ?>"
                                        data-category-badge="<?php echo e($group->note); ?>"
                                        data-min-price="<?php echo e($group->price); ?>"
                                        data-max-price="<?php echo e($group->price); ?>"
                                        data-available-count="<?php echo e($groupCount); ?>"
                                        onclick="openRandomPurchaseModal(this)">
                                    <i class="fas fa-shopping-cart"></i> MUA TÀI KHOẢN
                                </button>
                            <?php else: ?>
                                <button class="account-button" style="font-size: 14px; padding: 10px 0;"
                                        data-category-id="<?php echo e($category->id); ?>"
                                        data-group-id="<?php echo e($group->id); ?>"
                                        data-category-name="<?php echo e($category->name); ?>"
                                        data-category-badge="<?php echo e($group->note); ?>"
                                        data-min-price="<?php echo e($group->price); ?>"
                                        data-max-price="<?php echo e($group->price); ?>"
                                        data-available-count="<?php echo e($groupCount); ?>"
                                        onclick="openRandomPurchaseModal(this)">
                                    <i class="fas fa-shopping-cart"></i> MUA TÀI KHOẢN
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
                <div class="no-data"><p class="no-data-text">Hiện Tại Không Còn Tài Khoản</p></div>
            <?php endif; ?>
        <?php else: ?>
            
            <div class="account-grid-new">
                <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="account-item">
                        <a href="<?php echo e($account->thumb); ?>?uid=<?php echo e($account->id); ?>"
                           class="account-image-wrapper sl-link"
                           onclick="increaseView(<?php echo e($account->id); ?>)">
                            <img src="<?php echo e($account->thumb); ?>" alt="Account Preview" class="account-image">
                            <div class="account-code">MS: <?php echo e($account->id); ?></div>
                        </a>
                        <div class="account-info-wrapper">
                            <div class="account-views">
                                <i class="fas fa-eye"></i>
                                <span class="view-count" id="view_<?php echo e($account->id); ?>">
                                    <?php echo e($account->views); ?>

                                </span>
                            </div>
                            <div class="account-badges">
                                <?php if($account->note): ?>
                                    <?php $__currentLoopData = explode("\n", trim($account->note)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $noteLine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(trim($noteLine)): ?>
                                           <?php
                                                $icons = ['fa-star', 'fa-check-circle', 'fa-fire', 'fa-bolt', 'fa-gem', 'fa-shield-alt'];
                                                $icon = $icons[array_rand($icons)];
                                            ?>

                                            <div class="account-badge">
                                                <i class="fas <?php echo e($icon); ?>"></i> <?php echo e(trim($noteLine)); ?>

                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <?php
                                        $defaultIcons = ['fa-star', 'fa-check', 'fa-fire', 'fa-bolt', 'fa-gem', 'fa-shield-alt'];
                                    ?>

                                    <div class="account-badge">
                                        <i class="fas <?php echo e($defaultIcons[array_rand($defaultIcons)]); ?>"></i> ZING ID
                                    </div>

                                    <div class="account-badge">
                                        <i class="fas <?php echo e($defaultIcons[array_rand($defaultIcons)]); ?>"></i> TRẮNG THÔNG TIN
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="account-action-wrapper">
                            <div class="account-price"><?php echo e(number_format($account->price, 0, '.', '.')); ?> VND</div>
                            <?php if($category->type === 'play'): ?>
                                <a href="<?php echo e(route('account.show', $account->id)); ?>" class="account-button">
                                    <i class="fas fa-info-circle"></i> XEM CHI TIẾT
                                </a>
                            <?php else: ?>
                                <?php if(auth()->guard()->check()): ?>
                                    <button class="account-button" data-id="<?php echo e($account->id); ?>" data-price="<?php echo e($account->price); ?>" onclick="openQuickPurchaseModal(this)">
                                        <i class="fas fa-shopping-cart"></i> MUA NGAY
                                    </button>
                                <?php else: ?>
                                    <button class="account-button" data-id="<?php echo e($account->id); ?>" data-price="<?php echo e($account->price); ?>" onclick="openQuickPurchaseModal(this)">
                                        <i class="fas fa-shopping-cart"></i> MUA NGAY
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="no-data"><p class="no-data-text">Hiện Tại Không Còn Tài Khoản</p></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Quick Purchase Modal -->
<div id="quickPurchaseModal" class="modal">
    <div class="modal__content">

        <div class="modal__header">
            <h2 class="modal__title">MUA TÀI KHOẢN #<span id="modalAccountId"></span></h2>
            <button class="modal__close" onclick="closeQuickPurchaseModal()">&times;</button>
        </div>

        <div class="modal__price-section">
            <div class="modal__price-row">
                <span class="modal__price-label">Loại Tài Khoản</span>
                <span class="modal__price-value"><?php echo e($category->name); ?></span>
            </div>
            <div class="modal__price-row modal__total-row">
                <span class="modal__price-label">Tổng Thanh Toán</span>
                <span class="modal__price-value modal__total-value" id="totalPrice">0 VND</span>
            </div>
        </div>

        <!-- Loading & Messages -->
        <div class="modal__body">
            <div id="loadingSpinner" style="display:none">
                <div class="spinner"></div>
            </div>

            <div id="balanceError" class="message-box" style="display:none;">
                Số Dư Không Đủ
            </div>

            <div id="successMessage" class="message-box" style="display:none;">
                Thanh Toán Thành Công
            </div>
        </div>

        <!-- Footer -->
        <div class="modal__footer_random" style="flex-direction: column; gap: 10px;">
            <?php if(auth()->guard()->check()): ?>
                <button id="purchaseButton" class="modal__btn modal__btn--wallet" style="background: linear-gradient(135deg, #8B5CF6, #6366F1);"
                        onclick="submitQuickPurchase()">
                    <i class="fas fa-wallet"></i> THANH TOÁN BẰNG SỐ DƯ (<?php echo e(number_format(auth()->user()->balance)); ?> VND)
                </button>
            <?php endif; ?>
            <button id="accountDirectPayButton" class="modal__btn modal__btn--wallet" style="background: linear-gradient(135deg, #10b981, #059669);"
                    onclick="submitAccountDirectPayment()">
                <i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    new SimpleLightbox('.sl-link', {
        caption: false,
        closeText: '×',
        navText: [
            '<i class="fas fa-chevron-left"></i>',
            '<i class="fas fa-chevron-right"></i>'
        ],
        animationSpeed: 250,
        scaleImageToRatio: true,
        enableKeyboard: true,
        disableRightClick: true
    });

    // Close Modals on click outside
    const quickModal = document.getElementById('quickPurchaseModal');
    if (quickModal) {
        quickModal.onclick = function(e) {
            if (e.target === quickModal) closeQuickPurchaseModal();
        };
    }

    const randomModal = document.getElementById('randomPurchaseModal');
    if (randomModal) {
        randomModal.onclick = function(e) {
            if (e.target === randomModal) closeRandomPurchaseModal();
        };
    }
});
</script>

<script>
// Purchase Modal
let currentAccountId = null, currentPrice = 0;

function openQuickPurchaseModal(btn) {
    currentAccountId = parseInt(btn.dataset.id);
    currentPrice = parseInt(btn.dataset.price);

    document.getElementById('modalAccountId').innerText = currentAccountId;
    document.getElementById('totalPrice').innerText = currentPrice.toLocaleString('vi-VN') + ' VND';

    document.getElementById('balanceError').style.display = 'none';
    document.getElementById('successMessage').style.display = 'none';
    document.getElementById('loadingSpinner').style.display = 'none';

    const purchaseBtn = document.getElementById('purchaseButton');
    if (purchaseBtn) {
        <?php if(auth()->guard()->check()): ?>
            purchaseBtn.innerHTML = '<i class="fas fa-wallet"></i> THANH TOÁN BẰNG SỐ DƯ (<?php echo e(number_format(auth()->user()->balance)); ?> VND)';
        <?php else: ?>
            purchaseBtn.innerHTML = '<i class="fas fa-wallet"></i> THANH TOÁN BẰNG SỐ DƯ';
        <?php endif; ?>
        purchaseBtn.disabled = false;
        purchaseBtn.onclick = submitQuickPurchase;
    }

    document.getElementById('quickPurchaseModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeQuickPurchaseModal() {
    document.getElementById('quickPurchaseModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function submitQuickPurchase() {
    const spinner = document.getElementById('loadingSpinner');
    const balanceError = document.getElementById('balanceError');
    const button = document.getElementById('purchaseButton');

    balanceError.style.display = 'none';
    spinner.style.display = 'block';
    button.disabled = true;

    fetch('/user/balance')
        .then(r => r.json())
        .then(data => {
            spinner.style.display = 'none';
            if ((data.balance ?? 0) < currentPrice) {
                balanceError.style.display = 'block';
                button.disabled = false;
            } else {
                processPurchase();
            }
        })
        .catch(() => {
            spinner.style.display = 'none';
            button.disabled = false;
        });
}

function processPurchase() {
    const spinner = document.getElementById('loadingSpinner');
    const success = document.getElementById('successMessage');
    const button = document.getElementById('purchaseButton');

    spinner.style.display = 'block';

    fetch(`/acc/${currentAccountId}/purchase`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(async r => {
        const data = await r.json().catch(() => null);
        if (!r.ok) {
            throw new Error(data?.message || 'Đã xảy ra lỗi trên hệ thống');
        }
        return data;
    })
    .then(data => {
        spinner.style.display = 'none';
        if (data && data.success) {
            success.style.display = 'block';
            button.innerHTML = '<i class="fas fa-eye"></i> XEM TÀI KHOẢN';
            button.disabled = false;
            button.onclick = () => window.location.href = '/profile/purchased-accounts';
        } else {
            const balanceError = document.getElementById('balanceError');
            balanceError.textContent = (data && data.message) ? data.message : 'Không thể thực hiện giao dịch';
            balanceError.style.display = 'block';
            button.disabled = false;
        }
    })
    .catch((err) => {
        spinner.style.display = 'none';
        const balanceError = document.getElementById('balanceError');
        balanceError.textContent = err.message;
        balanceError.style.display = 'block';
        button.disabled = false;
    });
}

// ========== Account Direct Payment ==========
function submitAccountDirectPayment() {
    const spinner = document.getElementById('loadingSpinner');
    const directBtn = document.getElementById('accountDirectPayButton');
    
    spinner.style.display = 'block';
    directBtn.disabled = true;
    directBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG TẠO ĐƠN...';

    if (!currentAccountId) {
        spinner.style.display = 'none';
        const balanceError = document.getElementById('balanceError');
        balanceError.textContent = 'Lỗi: Không xác định được mã tài khoản.';
        balanceError.style.display = 'block';
        directBtn.disabled = false;
        directBtn.innerHTML = '<i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY';
        return;
    }

    fetch('<?php echo e(route("direct-payment.create-account", ":id")); ?>'.replace(':id', currentAccountId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({})
    })
    .then(async r => {
        const data = await r.json().catch(() => null);
        if (!r.ok) {
            throw new Error(data?.message || 'Đã xảy ra lỗi trên hệ thống (Mã: ' + r.status + ')');
        }
        return data;
    })
    .then(data => {
        spinner.style.display = 'none';
        if (data && data.success && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            const balanceError = document.getElementById('balanceError');
            balanceError.textContent = (data && data.message) ? data.message : 'Bạn Đang Có Nhiều Đơn Hàng Chưa Thanh Toán';
            balanceError.style.display = 'block';
            directBtn.disabled = false;
            directBtn.innerHTML = '<i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY';
        }
    })
    .catch((err) => {
        spinner.style.display = 'none';
        const balanceError = document.getElementById('balanceError');
        balanceError.textContent = err.message;
        balanceError.style.display = 'block';
        directBtn.disabled = false;
        directBtn.innerHTML = '<i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY';
    });
}

function increaseView(accountId) {
    fetch(`/acc/${accountId}/view`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json"
        }
    });
}
function animateViews(el, start, end, duration = 600) {
    let startTime = null;

    function step(timestamp) {
        if (!startTime) startTime = timestamp;
        let progress = Math.min((timestamp - startTime) / duration, 1);
        let current = Math.floor(start + (end - start) * progress);

        el.innerText = current;

        if (progress < 1) {
            requestAnimationFrame(step);
        }
    }

    requestAnimationFrame(step);
}
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".view-count").forEach(el => {
        let finalValue = parseInt(el.innerText);
        el.innerText = 0;               // bắt đầu từ 0 cho đẹp
        animateViews(el, 0, finalValue, 550);   // 550ms = mượt nhất
    });
});

// Click outside to close (Attached in DOMContentLoaded below)

// ========== Random Purchase Modal ==========
let currentCategoryId = null;
let currentGroupId = null;
let randomUnitPrice = 0;
let randomAvailableCount = 0;

function openRandomPurchaseModal(btn) {
    currentCategoryId = parseInt(btn.dataset.categoryId);
    currentGroupId = btn.dataset.groupId ? parseInt(btn.dataset.groupId) : null;
    const categoryName = btn.dataset.categoryName || 'Random';
    const categoryBadge = btn.dataset.categoryBadge || '';
    randomUnitPrice = parseInt(btn.dataset.minPrice);
    randomAvailableCount = parseInt(btn.dataset.availableCount || 99);

    document.getElementById('randomModalCategoryName').innerText = categoryName;
    document.getElementById('randomUnitPrice').innerText = randomUnitPrice.toLocaleString('vi-VN') + ' VND';
    
    // Reset quantity
    document.getElementById('randomQuantityInput').value = 1;
    updateRandomTotalPrice();

    // Show available count if available
    const availableRow = document.getElementById('randomAvailableRow');
    if (randomAvailableCount > 0) {
        availableRow.style.display = 'flex';
        document.getElementById('randomAvailableCount').innerText = randomAvailableCount + ' Tài Khoản';
        document.getElementById('randomQuantityInput').max = randomAvailableCount;
    } else {
        availableRow.style.display = 'none';
    }



    document.getElementById('randomBalanceError').style.display = 'none';
    document.getElementById('randomSuccessMessage').style.display = 'none';
    document.getElementById('randomLoadingSpinner').style.display = 'none';
    document.getElementById('randomAccountResult').style.display = 'none';

    const purchaseBtn = document.getElementById('randomPurchaseButton');
    if (purchaseBtn) {
        <?php if(auth()->guard()->check()): ?>
            purchaseBtn.innerHTML = '<i class="fas fa-wallet"></i> THANH TOÁN BẰNG SỐ DƯ (<?php echo e(number_format(auth()->user()->balance)); ?> VND)';
        <?php else: ?>
            purchaseBtn.innerHTML = '<i class="fas fa-wallet"></i> THANH TOÁN BẰNG SỐ DƯ';
        <?php endif; ?>
        purchaseBtn.disabled = false;
        purchaseBtn.onclick = submitRandomPurchase;
    }

    document.getElementById('randomPurchaseModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function getRandomQuantity() {
    const input = document.getElementById('randomQuantityInput');
    return Math.max(1, Math.min(parseInt(input.value) || 1, randomAvailableCount || 50));
}

function changeRandomQty(delta) {
    const input = document.getElementById('randomQuantityInput');
    let val = parseInt(input.value) || 1;
    val = Math.max(1, Math.min(val + delta, randomAvailableCount || 50));
    input.value = val;
    updateRandomTotalPrice();
}

function updateRandomTotalPrice() {
    const quantity = getRandomQuantity();
    const total = randomUnitPrice * quantity;
    document.getElementById('randomTotalPrice').innerText = total.toLocaleString('vi-VN') + ' VND';
}

// Listen for input changes
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('randomQuantityInput');
    if (qtyInput) {
        qtyInput.addEventListener('input', updateRandomTotalPrice);
        qtyInput.addEventListener('change', updateRandomTotalPrice);
    }
    
    // Direct Payment button
    const directPayBtn = document.getElementById('randomDirectPayButton');
    if (directPayBtn) {
        directPayBtn.addEventListener('click', function() {
            submitDirectPayment('random');
        });
    }
});

function closeRandomPurchaseModal() {
    document.getElementById('randomPurchaseModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function submitRandomPurchase() {
    const spinner = document.getElementById('randomLoadingSpinner');
    const balanceError = document.getElementById('randomBalanceError');
    const button = document.getElementById('randomPurchaseButton');
    const quantity = getRandomQuantity();
    const totalPrice = randomUnitPrice * quantity;

    balanceError.style.display = 'none';
    spinner.style.display = 'block';
    button.disabled = true;

    // Check balance first
    fetch('/user/balance')
        .then(r => r.json())
        .then(data => {
            if ((data.balance ?? 0) < totalPrice) {
                spinner.style.display = 'none';
                balanceError.style.display = 'block';
                button.disabled = false;
            } else {
                processRandomPurchase(quantity);
            }
        })
        .catch(() => {
            spinner.style.display = 'none';
            button.disabled = false;
        });
}

function processRandomPurchase(quantity = 1) {
    const spinner = document.getElementById('randomLoadingSpinner');
    const success = document.getElementById('randomSuccessMessage');
    const button = document.getElementById('randomPurchaseButton');
    const resultDiv = document.getElementById('randomAccountResult');

    fetch('<?php echo e(route("category.random-purchase", ":id")); ?>'.replace(':id', currentCategoryId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            group_id: currentGroupId,
            quantity: quantity
        })
    })
    .then(async r => {
        const data = await r.json().catch(() => null);
        if (!r.ok) {
            throw new Error(data?.message || 'Đã xảy ra lỗi trên hệ thống');
        }
        return data;
    })
    .then(data => {
        spinner.style.display = 'none';
        if (data && data.success) {
            success.style.display = 'block';
            button.innerHTML = '<i class="fas fa-eye"></i> XEM TÀI KHOẢN';
            button.disabled = false;
            button.onclick = () => window.location.href = '/profile/purchased-accounts';
        } else {
            const balanceError = document.getElementById('randomBalanceError');
            balanceError.textContent = (data && data.message) ? data.message : 'Không thể thực hiện giao dịch';
            balanceError.style.display = 'block';
            button.disabled = false;
        }
    })
    .catch((err) => {
        spinner.style.display = 'none';
        const balanceError = document.getElementById('randomBalanceError');
        balanceError.textContent = err.message;
        balanceError.style.display = 'block';
        button.disabled = false;
    });
}

// ========== Direct Payment Functions ==========
function submitDirectPayment(type) {
    const spinner = document.getElementById('randomLoadingSpinner');
    const directBtn = document.getElementById('randomDirectPayButton');
    
    spinner.style.display = 'block';
    directBtn.disabled = true;
    directBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG TẠO ĐƠN...';

    const quantity = getRandomQuantity();
    
    if (!currentCategoryId) {
        spinner.style.display = 'none';
        const randomBalanceError = document.getElementById('randomBalanceError');
        randomBalanceError.textContent = 'Lỗi: Không xác định được danh mục.';
        randomBalanceError.style.display = 'block';
        directBtn.disabled = false;
        directBtn.innerHTML = '<i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY';
        return;
    }

    let url, body;
    if (type === 'random') {
        url = '<?php echo e(route("direct-payment.create-random", ":id")); ?>'.replace(':id', currentCategoryId);
        body = JSON.stringify({
            group_id: currentGroupId,
            quantity: quantity
        });
    } else {
        url = '<?php echo e(route("direct-payment.create-account", ":id")); ?>'.replace(':id', currentAccountId);
        body = JSON.stringify({});
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: body
    })
    .then(async r => {
        const data = await r.json().catch(() => null);
        if (!r.ok) {
            throw new Error(data?.message || 'Đã xảy ra lỗi trên hệ thống (Mã: ' + r.status + ')');
        }
        return data;
    })
    .then(data => {
        spinner.style.display = 'none';
        if (data && data.success && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            const randomBalanceError = document.getElementById('randomBalanceError');
            randomBalanceError.textContent = (data && data.message) ? data.message : 'Bạn Đang Có Nhiều Đơn Hàng Chưa Thanh Toán';
            randomBalanceError.style.display = 'block';
            directBtn.disabled = false;
            directBtn.innerHTML = '<i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY';
        }
    })
    .catch((err) => {
        spinner.style.display = 'none';
        const randomBalanceError = document.getElementById('randomBalanceError');
        randomBalanceError.textContent = err.message;
        randomBalanceError.style.display = 'block';
        directBtn.disabled = false;
        directBtn.innerHTML = '<i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY';
    });
}

// Click outside to close (Attached in DOMContentLoaded below)
</script>

<!-- Random Purchase Modal -->
<div id="randomPurchaseModal" class="modal">
    <div class="modal__content">
        <div class="modal__header">
            <h2 class="modal__title">MUA TÀI KHOẢN</h2>
            <button class="modal__close" onclick="closeRandomPurchaseModal()">&times;</button>
        </div>

        <div class="modal__price-section">
            <div class="modal__price-row">
                <span class="modal__price-label">Loại Tài Khoản</span>
                <span class="modal__price-value" id="randomModalCategoryName"></span>
            </div>
            <div class="modal__price-row">
                <span class="modal__price-label">Đơn Giá</span>
                <span class="modal__price-value" id="randomUnitPrice">0 VND</span>
            </div>
            <div class="modal__price-row">
                <span class="modal__price-label">Số Lượng</span>
                <span class="modal__price-value">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button type="button" class="qty-btn" onclick="changeRandomQty(-1)">-</button>
                        <input type="number" id="randomQuantityInput" value="1" min="1" max="50" 
                               style="width: 50px; text-align: center; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px; font-weight: 700;">
                        <button type="button" class="qty-btn" onclick="changeRandomQty(1)">+</button>
                    </div>
                </span>
            </div>
            <div class="modal__price-row" id="randomAvailableRow" style="display: none;">
                <span class="modal__price-label">Còn Lại</span>
                <span class="modal__price-value" id="randomAvailableCount" style="color: #16a34a;">0</span>
            </div>
            <div class="modal__price-row modal__total-row">
                <span class="modal__price-label">Tổng Thanh Toán</span>
                <span class="modal__price-value modal__total-value" id="randomTotalPrice">0 VND</span>
            </div>
        </div>

        <div class="modal__body">
            


            <div id="randomLoadingSpinner" style="display:none">
                <div class="spinner"></div>
            </div>

            <div id="randomBalanceError" class="message-box" style="display:none;">
                Số Dư Không Đủ
            </div>

            <div id="randomSuccessMessage" class="message-box" style="display:none;">
                Thanh Toán Thành Công
            </div>

            <div id="randomAccountResult" style="display:none;"></div>
        </div>

        <div class="modal__footer_random" style="flex-direction: column; gap: 10px;">
            <?php if(auth()->guard()->check()): ?>
                <button id="randomPurchaseButton" class="modal__btn modal__btn--wallet" style="background: linear-gradient(135deg, #8B5CF6, #6366F1);">
                    <i class="fas fa-wallet"></i> THANH TOÁN BẰNG SỐ DƯ (<?php echo e(number_format(auth()->user()->balance)); ?> VND)
                </button>
            <?php endif; ?>
            <button id="randomDirectPayButton" class="modal__btn modal__btn--wallet" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY
            </button>
        </div>
    </div>
</div>

<style>
.qty-btn {
    width: 32px;
    height: 32px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}
.qty-btn:hover {
    background: #667eea;
    color: #fff;
    border-color: #667eea;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/category/show.blade.php ENDPATH**/ ?>