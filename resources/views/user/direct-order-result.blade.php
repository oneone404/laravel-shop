@extends('layouts.user.app')
@section('title', 'Đơn Hàng #' . $order->order_code)
@section('content')

<style>
.payment-container {
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 15px;
    background-color: #f8fafc;
    background-image: 
        radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.03) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.03) 0px, transparent 50%);
}

.payment-card {
    width: 100%;
    max-width: 480px;
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
}

@media (min-width: 992px) {
    .payment-card {
        max-width: 1000px;
    }
}

.payment-header {
    background: #ffffff;
    padding: 25px 25px 20px;
    border-bottom: 1px solid #f1f5f9;
}

.payment-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.payment-header h1 {
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.payment-header h1 i {
    color: #4f46e5;
    font-size: 20px;
}

.order-code-pill {
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    background: #f1f5f9;
    padding: 8px 16px;
    border-radius: 12px;
    font-family: 'JetBrains Mono', monospace;
}

.payment-steps {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    position: relative;
    padding-bottom: 10px;
}

.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex: 1;
    position: relative;
    z-index: 2;
}

.step-dot {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    color: #94a3b8;
    border: 3px solid #fff;
    box-shadow: 0 0 0 1px #e2e8f0;
    transition: all 0.3s;
}

.step-label {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.step-item.active .step-dot {
    background: #4f46e5;
    color: #fff;
    box-shadow: 0 0 0 1px #4f46e5;
}

.step-item.active .step-label {
    color: #4f46e5;
}

.step-line {
    position: absolute;
    top: 16px;
    left: 0;
    right: 0;
    height: 2px;
    background: #f1f5f9;
    z-index: 1;
}

.step-line-progress {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: #4f46e5;
    transition: width 0.3s ease;
}

.step-item.failed .step-dot {
    background: #fef2f2;
    color: #ef4444;
    box-shadow: 0 0 0 1px #ef4444;
}

.step-item.failed .step-label {
    color: #ef4444;
}

.step-item.completed .step-dot {
    background: #4f46e5;
    color: #fff;
    box-shadow: 0 0 0 1px #4f46e5;
}

.step-item.completed .step-label {
    color: #4f46e5;
}

.payment-body {
    padding: 20px 24px;
}

/* Status Banner */
.status-banner {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 32px;
    transition: all 0.3s ease;
}

.status-banner.success {
    background: #f0fdf4;
    border: 1px solid #dcfce7;
}

.status-banner.error {
    background: #fef2f2;
    border: 1px solid #fee2e2;
}

.status-icon-wrapper {
    width: 36px;
    height: 36px;
    aspect-ratio: 1 / 1;
    flex-shrink: 0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.success .status-icon-wrapper {
    background: #dcfce7;
    color: #16a34a;
}

.error .status-icon-wrapper {
    background: #fee2e2;
    color: #ef4444;
}

.status-info-text h3 {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.status-info-text p {
    font-size: 14px;
    margin: 4px 0 0;
    color: #64748b;
}

@media (max-width: 480px) {
    .status-info-text h3 {
        font-size: 14px;
        font-weight: 700;
    }

    .status-info-text p {
        font-size: 12px;
        margin-top: 2px;
    }
}

/* Info Tiles */
.info-title {
    font-size: 13px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 24px;
    text-align: center;
}

.details-grid {
    display: grid;
    gap: 10px;
}

.detail-item {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    padding: 12px 16px;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.detail-item:hover {
    background: #fff;
    border-color: #4f46e5;
    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.05);
}

.detail-left {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 11px;
    color: #64748b;
    font-weight: 700;
    margin-bottom: 2px;
}

.detail-value {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
}

.detail-value.success {
    color: #16a34a;
}

.detail-value.highlight {
    color: #4f46e5;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    font-size: 14px;
}

.action-btn:hover {
    background: #4f46e5;
    color: #fff;
    border-color: #4f46e5;
}

/* Account List styling */
.account-lists {
    margin-top: 32px;
}

.account-card.hidden-account {
    display: none;
}

.show-all-wrapper {
    text-align: center;
    margin-top: 16px;
}

.btn-show-all {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #475569;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-show-all:hover {
    background: #e2e8f0;
    color: #0f172a;
}

.account-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    margin-bottom: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.account-card:hover {
    border-color: #4f46e5;
    box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.08);
}

.account-card-header {
    background: #f8fafc;
    padding: 10px 16px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.account-badge {
    font-size: 11px;
    font-weight: 700;
    color: #4f46e5;
    text-transform: uppercase;
}

.account-card-body {
    padding: 12px 16px;
}

/* Alert Note */
.alert-note {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    padding: 16px;
    border-radius: 10px;
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

.alert-note i {
    color: #ef4444;
    font-size: 18px;
    margin-top: 2px;
}

.alert-note p {
    margin: 0;
    font-size: 13px;
    color: #991b1b;
    line-height: 1.5;
    font-weight: 500;
}

/* Layout Wrapper */
.modern-layout {
    display: flex;
    flex-direction: column;
}

@media (min-width: 992px) {
    .modern-layout {
        flex-direction: row;
        gap: 60px;
    }

    .layout-left {
        flex: 0 0 420px;
    }

    .layout-right {
        flex: 1;
        padding-left: 60px;
        border-left: 2px solid #f1f5f9;
    }
}

/* Action Buttons */
.footer-actions {
    margin-top: 40px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

@media (min-width: 992px) {
    .footer-actions {
        flex-direction: row;
        justify-content: center;
    }
}

.btn-primary-pill {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 32px;
    background: #4f46e5;
    color: #fff;
    border: none;
    border-radius: 14px;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-primary-pill:hover {
    background: #4338ca;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    color: #fff;
}

.btn-secondary-pill {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 32px;
    background: #fff;
    color: #64748b;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-secondary-pill:hover {
    border-color: #cbd5f5;
    color: #0f172a;
    background: #f8fafc;
}

/* Toast Notification */
.toast-notify {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    background: #1e293b;
    color: #fff;
    padding: 12px 24px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    z-index: 9999;
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.toast-notify.show {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
}

.toast-notify.success {
    background: #10b981;
}

</style>

<div class="payment-container">
    <div class="payment-card">
        <div class="payment-header">
            <div class="payment-header-top">
                <h1><i class="fas fa-shield-alt"></i> Đơn Hàng</h1>
                <div class="order-code-pill">ID: {{ $order->order_code }}</div>
            </div>

            @php
                $status = $order->status;
                $progress = 100;
                $step1Class = 'completed';
                $step2Class = 'completed';
                $step3Class = ($status === 'completed') ? 'completed' : 'failed';
            @endphp

            <div class="payment-steps">
                <div class="step-line">
                    <div class="step-line-progress" style="width: {{ $progress }}%;"></div>
                </div>
                <div class="step-item {{ $step1Class }}">
                    <div class="step-dot"><i class="fas fa-shopping-cart"></i></div>
                    <div class="step-label">Tạo Đơn</div>
                </div>
                <div class="step-item {{ $step2Class }}">
                    <div class="step-dot"><i class="fas fa-credit-card"></i></div>
                    <div class="step-label">Thanh toán</div>
                </div>
                <div class="step-item {{ $step3Class }}">
                    <div class="step-dot">
                        @if($status === 'completed')
                            <i class="fas fa-check"></i>
                        @else
                            <i class="fas fa-ban"></i>
                        @endif
                    </div>
                    <div class="step-label">Hoàn tất</div>
                </div>
            </div>
        </div>

        <div class="payment-body">
            @if($order->status === 'completed' && !empty($order->account_data))
                <div class="modern-layout">
                    <div class="layout-left">
                        <div class="status-banner success">
                            <div class="status-icon-wrapper">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="status-info-text">
                                <h3>Giao Dịch Thành Công</h3>
                                <p>Cảm Ơn Bạn Đã Sử Dụng Dịch Vụ</p>
                            </div>
                        </div>

                        <div class="info-title">Thông Tin Đơn Hàng</div>
                        <div class="details-grid">
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Mã Đơn Hàng</span>
                                    <span class="detail-value">{{ $order->order_code }}</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Tổng Thanh Toán</span>
                                    <span class="detail-value success">{{ number_format($order->amount) }} VND</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Số Lượng</span>
                                    <span class="detail-value">{{ count($order->account_data) }} Tài Khoản</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Hoàn Thành Lúc</span>
                                    <span class="detail-value">{{ $order->completed_at->format('d/m/Y H:i:s') }}</span>
                                </div>
                            </div>
                        </div>

                        @if($isGuest)
                            <div class="alert-note">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p><strong>Quan Trọng:</strong> Bạn chưa đăng nhập, vui lòng lưu lại tài khoản hoặc tải file TXT. Hệ thống không lưu lịch sử cho khách.</p>
                            </div>
                        @endif
                    </div>

                    <div class="layout-right">
                        <div class="info-title">Thông Tin Tài Khoản</div>
                        <div class="account-lists">
                            @foreach($order->account_data as $index => $account)
                                <div class="account-card {{ $index >= 3 ? 'hidden-account' : '' }}">
                                    <div class="account-card-header">
                                        <span class="account-badge">Tài Khoản #{{ $index + 1 }}</span>
                                    </div>
                                    <div class="account-card-body">
                                        <div class="details-grid">
                                            <div class="detail-item">
                                                <div class="detail-left">
                                                    <span class="detail-label">Username</span>
                                                    <span class="detail-value">{{ $account['account_name'] ?? $account['u'] ?? 'N/A' }}</span>
                                                </div>
                                                <button class="action-btn copy-btn" data-copy="{{ $account['account_name'] ?? $account['u'] ?? '' }}">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-left">
                                                    <span class="detail-label">Password</span>
                                                    <span class="detail-value">{{ $account['password'] ?? $account['p'] ?? 'N/A' }}</span>
                                                </div>
                                                <button class="action-btn copy-btn" data-copy="{{ $account['password'] ?? $account['p'] ?? '' }}">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if(count($order->account_data) > 3)
                                <div class="show-all-wrapper">
                                    <button class="btn-show-all" id="btnShowAll">
                                        <i class="fas fa-chevron-down"></i> View All ({{ count($order->account_data) }})
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="footer-actions">
                    <a href="{{ route('direct-payment.download', $order->order_code) }}" class="btn-primary-pill">
                        <i class="fas fa-download"></i> Tải File TXT
                    </a>
                    @if($isGuest)
                        <a href="{{ route('login') }}" class="btn-secondary-pill">
                            <i class="fas fa-user"></i> Đăng Nhập Ngay
                        </a>
                    @else
                        <a href="{{ route('profile.purchased-accounts') }}" class="btn-secondary-pill">
                            <i class="fas fa-history"></i> Lịch Sử Mua Hàng
                        </a>
                    @endif
                    <a href="/" class="btn-secondary-pill">
                        <i class="fas fa-home"></i> Trang Chủ
                    </a>
                </div>

            @else
                {{-- Error/Pending State --}}
                <div class="status-banner error">
                    <div class="status-icon-wrapper">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="status-info-text">
                        <h3>Đơn Hàng Chưa Hoàn Thành</h3>
                        <p>Đã Có Lỗi Xảy Ra Trong Quá Trình Giao Dịch.</p>
                    </div>
                </div>

                <div class="details-grid" style="max-width: 400px; margin: 32px auto;">
                    <div class="detail-item">
                        <div class="detail-left">
                            <span class="detail-label">Trạng Thái</span>
                            <span class="detail-value">
                                @switch($order->status)
                                    @case('pending') Đang Chờ Thanh Toán @break
                                    @case('paid') Đang Xử Lý @break
                                    @case('expired') Quá Hạn @break
                                    @case('cancelled') Đã Huỷ @break
                                    @default {{ $order->status }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                <div class="footer-actions">
                    @if($order->status === 'pending' && !$order->isExpired())
                        <a href="{{ route('direct-payment.show', $order->order_code) }}" class="btn-primary-pill">
                            <i class="fas fa-qrcode"></i> Thanh Toán Ngay
                        </a>
                    @endif
                    <a href="/" class="btn-secondary-pill">
                        <i class="fas fa-home"></i> Về Trang Chủ
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div class="toast-notify" id="toastNotify">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage">Đã Sao Chép</span>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy buttons
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const text = this.dataset.copy;
            if (!text) return;
            
            navigator.clipboard.writeText(text).then(() => {
                const icon = this.querySelector('i');
                const originalClass = icon.className;
                
                icon.className = 'fas fa-check';
                this.style.color = '#10b981';
                this.style.borderColor = '#10b981';
                
                showToast('Đã Sao Chép');
                
                setTimeout(() => {
                    icon.className = originalClass;
                    this.style.color = '';
                    this.style.borderColor = '';
                }, 2000);
            });
        });
    });

    // Show all accounts
    const btnShowAll = document.getElementById('btnShowAll');
    if (btnShowAll) {
        btnShowAll.addEventListener('click', function() {
            document.querySelectorAll('.account-card.hidden-account').forEach(card => {
                card.classList.remove('hidden-account');
            });
            this.parentElement.remove(); // Hide the show all button wrapper
        });
    }

    function showToast(message) {
        const toast = document.getElementById('toastNotify');
        const messageEl = document.getElementById('toastMessage');
        
        messageEl.textContent = message;
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 2500);
    }
});
</script>

@endsection
