@extends('layouts.user.app')
@section('title', 'Thanh Toán Đơn Hàng #' . $order->order_code)
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

.status-banner.pending {
    background: #fffbeb;
    border: 1px solid #fef3c7;
}

.status-banner.expired {
    background: #fef2f2;
    border: 1px solid #fee2e2;
}

.status-banner.cancelled {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
}

.cancelled .status-icon-wrapper {
    background: #e2e8f0;
    color: #475569;
}

.status-icon-wrapper {
    width: 36px;
    height: 36px;
    aspect-ratio: 1 / 1;
    flex-shrink: 0;

    border-radius: 50%;   /* tròn hẳn */
    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 16px;      /* icon nhỏ lại */
}

.pending .status-icon-wrapper {
    background: #fef3c7;
    color: #d97706;
}

.expired .status-icon-wrapper {
    background: #fee2e2;
    color: #ef4444;
}

.paid .status-icon-wrapper {
    background: #dcfce7;
    color: #22c55e;
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

/* Countdown Visual Compact */
/* Container */
.countdown-box {
    margin-bottom: 20px;
    padding: 16px 24px;
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid #e5e7eb;
    border-left: 4px solid #475569;
    border-radius: 12px;
    box-shadow:
        0 1px 2px rgba(15, 23, 42, 0.04),
        0 0 0 1px rgba(255, 255, 255, 0.6) inset;
}

.timer-times {
    display: flex;
    justify-content: space-between;
    margin-bottom: 24px;
    padding: 0 4px;
}

.time-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.time-item.end {
    text-align: right;
}

.time-label {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.time-value {
    font-size: 14px;
    font-weight: 700;
    color: #475569;
    font-family: 'JetBrains Mono', monospace;
}

/* Layout */
.timer-display {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Segment */
.timer-segment {
    display: flex;
    align-items: baseline;
    gap: 6px;
}

/* Number */
.timer-num {
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    letter-spacing: -0.25px;
}

/* Label */
.timer-label {
    font-size: 12px;
    font-weight: 500;
    color: #64748b;
    margin: 0;
}

/* Colon */
.timer-dots {
    font-size: 17px;
    font-weight: 500;
    color: #94a3b8;
    margin: 0 14px;
}

/* QR Card */
.qr-wrapper {
    background: #ffffff;
    padding: 32px;
    border-radius: 24px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.02);
    text-align: center;
}

.qr-image-container {
    background: #f8fafc;
    padding: 20px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 20px;
    border: 1px solid #f1f5f9;
}

.qr-image-container img {
    max-width: 200px;
    width: 100%;
    mix-blend-mode: multiply;
}

.qr-instruction {
    font-size: 15px;
    font-weight: 700;
    color: #475569;
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

.payment-details-grid {
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

.detail-value.amount {
    color: #4f46e5;
    font-size: 15px;
}

.detail-value.memo {
    color: #ef4444;
    font-family: 'JetBrains Mono', monospace;
    font-size: 14px;
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

/* Alert Note */
.alert-note {
    margin-top: 32px;
    background: #fefce8;
    border: 1px solid #fef08a;
    padding: 20px;
    border-radius: 10px;
    display: flex;
    gap: 16px;
}

.alert-note i {
    color: #ca8a04;
    font-size: 20px;
}

.alert-note p {
    margin: 0;
    font-size: 14px;
    color: #854d0e;
    line-height: 1.6;
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
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
}

/* Footer Actions */
.payment-footer {
    margin-top: 48px;
    text-align: center;
}
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    background: #fff;
    color: #64748b;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.2s ease;
}

.btn-back:hover {
    border-color: #cbd5f5;
    color: #0f172a;
    background: #f8fafc;
}

/* Processing Animations */
.processing-overlay {
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.98);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px);
}

.processing-overlay.active {
    display: flex;
}

.processing-content {
    background: #fff;
    border-radius: 24px;
    padding: 40px;
    text-align: center;
    max-width: 380px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.processing-spinner {
    width: 56px;
    height: 56px;
    border: 4px solid #f1f5f9;
    border-top-color: #4f46e5;
    border-radius: 50%;
    animation: spin 1s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    margin: 0 auto 24px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.processing-text {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}

.processing-subtext {
    font-size: 14px;
    color: #64748b;
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
                $isExpired = $order->isExpired();
                $progress = 0;
                $step1Class = 'completed';
                $step2Class = '';
                $step3Class = '';

                if ($status === 'pending' && !$isExpired) {
                    $progress = 50;
                    $step2Class = 'active';
                } elseif ($status === 'paid') {
                    $progress = 100;
                    $step2Class = 'completed';
                    $step3Class = 'active';
                } elseif ($status === 'completed') {
                    $progress = 100;
                    $step2Class = 'completed';
                    $step3Class = 'completed';
                } elseif ($status === 'expired' || ($status === 'pending' && $isExpired) || $status === 'cancelled') {
                    $progress = 100;
                    $step2Class = 'failed';
                    $step3Class = 'failed';
                }
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
                    <div class="step-dot">
                        @if($step2Class === 'failed')
                            <i class="fas fa-times"></i>
                        @else
                            <i class="fas fa-credit-card"></i>
                        @endif
                    </div>
                    <div class="step-label">Thanh toán</div>
                </div>
                <div class="step-item {{ $step3Class }}">
                    <div class="step-dot">
                        @if($step3Class === 'failed')
                            <i class="fas fa-ban"></i>
                        @else
                            <i class="fas fa-check"></i>
                        @endif
                    </div>
                    <div class="step-label">Hoàn tất</div>
                </div>
            </div>
        </div>

        <div class="payment-body">
            @if($order->status === 'pending' && !$order->isExpired())
                <div class="modern-layout">
                    <div class="layout-left">
                        <div class="status-banner pending">
                            <div class="status-icon-wrapper">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                            <div class="status-info-text">
                                <h3>Đang Chờ Thanh Toán</h3>
                                <p>Đang Kiểm Tra Giao Dịch...</p>
                            </div>
                        </div>

                        <div class="timer-times">
                            <div class="time-item">
                                <span class="time-label">Bắt đầu</span>
                                <span class="time-value">{{ $order->created_at->format('H:i:s d/m') }}</span>
                            </div>
                            <div class="time-item end">
                                <span class="time-label">Hết hạn</span>
                                <span class="time-value" style="color: #ef4444;">{{ $order->expires_at->format('H:i:s d/m') }}</span>
                            </div>
                        </div>

                        <div class="countdown-box" id="countdownSection">
                            <div class="timer-display">
                                <div class="timer-segment">
                                    <div class="timer-num" id="countMinutes">--</div>
                                    <div class="timer-label">M</div>
                                </div>
                                <div class="timer-dots">:</div>
                                <div class="timer-segment">
                                    <div class="timer-num" id="countSeconds">--</div>
                                    <div class="timer-label">S</div>
                                </div>
                            </div>
                        </div>

                        <div class="qr-wrapper">
                            <div class="qr-image-container">
                                <img src="{{ $qrUrl }}" alt="QR Code">
                            </div>
                            <div class="qr-instruction">QUÉT MÃ QR ĐỂ THANH TOÁN</div>
                        </div>
                    </div>

                    <div class="layout-right">
                        <div class="info-title">Thông tin giao dịch</div>
                        <div class="payment-details-grid">
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Loại Sản Phẩm</span>
                                    <span class="detail-value">{{ $order->category->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            @if($order->order_type === \App\Models\DirectOrder::TYPE_ACCOUNT)
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Mã Tài Khoản</span>
                                    <span class="detail-value">#{{ $order->item_id }}</span>
                                </div>
                            </div>
                            @else
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Số Lượng</span>
                                    <span class="detail-value">{{ $order->quantity }} Tài Khoản</span>
                                </div>
                            </div>
                            @endif
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Ngân Hàng</span>
                                    <span class="detail-value">{{ $bankAccount->bank_name }}</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Số Tài Khoản</span>
                                    <span class="detail-value">{{ $bankAccount->account_number }}</span>
                                </div>
                                <button class="action-btn copy-btn" data-copy="{{ $bankAccount->account_number }}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Chủ Tài Khoản</span>
                                    <span class="detail-value">{{ $bankAccount->account_name }}</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Số Tiền</span>
                                    <span class="detail-value amount">{{ number_format($order->amount) }} VND</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-left">
                                    <span class="detail-label">Nội Dung Chuyển Khoản</span>
                                    <span class="detail-value memo">{{ $order->payment_content }}</span>
                                </div>
                                <button class="action-btn copy-btn" data-copy="{{ $order->payment_content }}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="alert-note">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p><strong>Lưu ý:</strong> Vui Lòng Chuyển Khoản <strong>Chính Xác Số Tiền</strong> Và <strong>Nội Dung</strong>.</p>
                        </div>
                    </div>
                </div>

            @elseif($order->status === 'expired' || ($order->status === 'pending' && $order->isExpired()))
                <div class="status-banner expired">
                    <div class="status-icon-wrapper">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="status-info-text">
                        <h3>Quá Hạn Thanh Toán</h3>
                        <p>Vui Lòng Tạo Đơn Hàng Mới Để Tiếp Tục.</p>
                    </div>
                </div>
                
                <div class="text-center" style="padding: 40px 0;">
                    <i class="fas fa-hourglass-end" style="font-size: 64px; color: #cbd5e1; margin-bottom: 24px; display: block;"></i>
                    <p style="color: #64748b; font-size: 16px; max-width: 400px; margin: 0 auto;">Đã Quá Thời Gian Thanh Toán, Nếu Bạn Đã Chuyển Tiền Thì Hãy Liên Hệ Admin Để Được Hỗ Trợ.</p>
                </div>

            @elseif($order->status === 'paid')
                <div class="status-banner paid">
                    <div class="status-icon-wrapper">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="status-info-text">
                        <h3>Thanh Toán Thành Công</h3>
                        <p>Hệ Thống Đang Xử Lý Đơn Hàng Của Bạn...</p>
                    </div>
                </div>

                <div class="text-center" style="padding: 60px 0;">
                    <div class="processing-spinner"></div>
                    <div class="processing-text">Đang Xử Lý Đơn Hàng...</div>
                    <div class="processing-subtext">Vui Lòng Đợi, Hệ Thống Đang Xử Lý Giao Dịch...</div>
                </div>
            @endif

            <div class="payment-footer">
                <a href="javascript:history.back()" class="btn-back">
                    <i class="fas fa-chevron-left"></i> Quay Lại
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Processing Overlay (shown when payment detected) --}}
<div class="processing-overlay" id="processingOverlay">
    <div class="processing-content">
        <div class="processing-spinner"></div>
        <div class="processing-text">Đang Xử Lý Thanh Toán...</div>
        <div class="processing-subtext">Vui Lòng Không Đóng Trang Này</div>
    </div>
</div>

@if($order->status === 'pending' && !$order->isExpired())
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown
    let remainingSeconds = {{ $remainingSeconds }};
    const minutesEl = document.getElementById('countMinutes');
    const secondsEl = document.getElementById('countSeconds');
    const countdownSection = document.getElementById('countdownSection');

    function updateCountdown() {
        if (remainingSeconds <= 0) {
            minutesEl.textContent = '00';
            secondsEl.textContent = '00';
            countdownSection.classList.add('expired');
            // Reload page to show expired state
            setTimeout(() => location.reload(), 2000);
            return;
        }

        const minutes = Math.floor(remainingSeconds / 60);
        const seconds = remainingSeconds % 60;

        minutesEl.textContent = minutes.toString().padStart(2, '0');
        secondsEl.textContent = seconds.toString().padStart(2, '0');

        remainingSeconds--;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);

    // Polling check status
    const orderCode = '{{ $order->order_code }}';
    const checkUrl = '{{ route("direct-payment.check", $order->order_code) }}';
    const processingOverlay = document.getElementById('processingOverlay');

    function checkStatus() {
        fetch(checkUrl)
            .then(r => r.json())
            .then(data => {
                if (data.status === 'paid' || data.status === 'completed') {
                    processingOverlay.classList.add('active');
                }
                if (data.status === 'completed' && data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
                if (data.status === 'expired') {
                    location.reload();
                }
            })
            .catch(() => {});
    }

    // Check every 5 seconds
    setInterval(checkStatus, 5000);

    // Copy buttons
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const text = this.dataset.copy;
            navigator.clipboard.writeText(text).then(() => {
                this.classList.add('copied');
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.classList.remove('copied');
                    this.innerHTML = '<i class="fas fa-copy"></i>';
                }, 2000);
            });
        });
    });
});
</script>
@endif

@endsection
