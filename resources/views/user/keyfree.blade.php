@extends('layouts.user.app')
@section('title', 'Nhận Key Miễn Phí')
@section('content')

<style>
/* ===== Main Container ===== */
.keyfree-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 15px;
}

/* ===== Card ===== */
.keyfree-card {
    width: 100%;
    max-width: 600px;
    background: var(--bg-card);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 16px;
    overflow: hidden;
    animation: cardSlideUp 0.5s ease-out;
    border: 1px solid var(--border-color);
}

@keyframes cardSlideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== Header ===== */
.keyfree-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 28px 20px;
    text-align: center;
    position: relative;
}

.keyfree-icon {
    width: 70px;
    height: 70px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    backdrop-filter: blur(10px);
    animation: iconBounce 1s ease-in-out;
}

@keyframes iconBounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.keyfree-icon i {
    font-size: 32px;
    color: #fff;
}

.keyfree-header h1 {
    color: #fff;
    font-size: 22px;
    font-weight: 700;
    margin: 0 0 6px;
}

.keyfree-header p {
    color: rgba(255,255,255,0.85);
    font-size: 13px;
    margin: 0;
}

/* ===== Body ===== */
.keyfree-body {
    padding: 24px 20px;
    text-align: center;
}

/* ===== Key Display ===== */
.key-display {
    background: var(--bg-light);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.key-label {
    font-size: 11px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 600;
    margin-bottom: 10px;
}

.key-value-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--bg-card);
    border-radius: 8px;
    border: 1px solid var(--border-color);
    padding: 10px 12px;
    flex: 1;
}

.key-value {
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: clamp(14px, 4vw, 18px);
    font-weight: 700;
    color: var(--text-color);
    word-break: break-all;
    line-height: 1.4;
    flex: 1;
}

.btn-copy-inline {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.btn-copy-inline:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-copy-inline.copied {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

/* ===== Key Countdown Row (2 columns on desktop) ===== */
.key-countdown-row {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 18px;
}

/* ===== Countdown Timer ===== */
.countdown-wrap {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 12px;
    padding: 16px;
    color: #fff;
    flex: 1;
}

.countdown-wrap.expired {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.countdown-label {
    font-size: 12px;
    font-weight: 600;
    opacity: 0.9;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.countdown-timer {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.countdown-item {
    text-align: center;
}

.countdown-value {
    font-size: 28px;
    font-weight: 700;
    line-height: 1;
    font-family: 'JetBrains Mono', monospace;
}

.countdown-unit {
    font-size: 10px;
    font-weight: 600;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 4px;
}

.countdown-separator {
    font-size: 24px;
    font-weight: 700;
    opacity: 0.6;
    align-self: center;
}

/* ===== Time Info ===== */
.time-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 18px;
}

.time-item {
    background: var(--bg-light);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 12px;
    text-align: center;
}

.time-item-label {
    font-size: 10px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    margin-bottom: 4px;
}

.time-item-value {
    font-size: 13px;
    color: var(--text-color);
    font-weight: 600;
}

/* ===== Copy Button ===== */
.btn-copy {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 14px;
}

.btn-copy:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
}

.btn-copy:active {
    transform: translateY(0);
}

.btn-copy.copied {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.btn-copy i {
    font-size: 16px;
}

/* ===== Info Box ===== */
.info-box {
    background: #fef3c7;
    border: 1px solid #fcd34d;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-box i {
    color: #d97706;
    font-size: 18px;
    flex-shrink: 0;
}

.info-box p {
    margin: 0;
    font-size: 13px;
    color: #92400e;
    text-align: left;
    line-height: 1.4;
}

/* ===== Back Link ===== */
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: color 0.2s ease;
    padding: 8px 16px;
    border-radius: 8px;
    background: #f1f5f9;
}

.back-link:hover {
    color: #667eea;
    background: #e0e7ff;
}

/* ===== Error State ===== */
.keyfree-header.error {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.keyfree-header.expired-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.error-message {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 10px;
    padding: 16px;
    color: #ef4444;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
}

.error-message i {
    font-size: 20px;
    flex-shrink: 0;
}

/* ===== Toast Notification ===== */
.toast-notify {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    background: #1e293b;
    color: #fff;
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    z-index: 9999;
    opacity: 0;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
}

.toast-notify.show {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
}

.toast-notify.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

/* ===== VIP Badge ===== */
.vip-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #78350f;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 10px;
}

.vip-badge i {
    font-size: 12px;
}

/* ===== Desktop Styles (min-width: 576px) ===== */
@media (min-width: 576px) {
    .keyfree-container {
        padding: 40px 20px;
    }
    
    .keyfree-header {
        padding: 32px 30px;
    }
    
    .keyfree-icon {
        width: 80px;
        height: 80px;
    }
    
    .keyfree-icon i {
        font-size: 36px;
    }
    
    .keyfree-header h1 {
        font-size: 26px;
    }
    
    .keyfree-body {
        padding: 28px 30px;
    }
    
    /* 2 columns layout for countdown + key */
    .key-countdown-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }
    
    .countdown-wrap {
        margin-bottom: 0;
    }
    
    .key-display {
        margin-bottom: 0;
    }
    
    /* Time info: 4 columns on desktop */
    .time-info {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .countdown-value {
        font-size: 32px;
    }
    
    .key-value {
        font-size: 16px;
    }
    
    /* Device section horizontal */
    .device-item {
        flex-direction: row;
        align-items: center;
    }
}

/* ===== Mobile Styles (max-width: 480px) ===== */
@media (max-width: 480px) {
    .keyfree-container {
        padding: 20px 12px;
    }
    
    .keyfree-card {
        border-radius: 14px;
    }
    
    .keyfree-header {
        padding: 22px 16px;
    }
    
    .keyfree-icon {
        width: 60px;
        height: 60px;
    }
    
    .keyfree-icon i {
        font-size: 26px;
    }
    
    .keyfree-header h1 {
        font-size: 18px;
    }
    
    .keyfree-body {
        padding: 20px 16px;
    }
    
    .key-value {
        font-size: 13px;
    }
    
    .countdown-value {
        font-size: 22px;
    }
    
    .time-info {
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
}

/* ===== Device Section ===== */
.device-section {
    margin-bottom: 18px;
}

.device-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.device-title {
    font-size: 12px;
    font-weight: 700;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.device-count {
    background: var(--primary-color);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 20px;
}

.device-count.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.device-count.full {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.device-list {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
}

.device-item {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.device-item:last-child {
    border-bottom: none;
}

.device-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    flex-shrink: 0;
}

.device-info {
    flex: 1;
    min-width: 0;
    text-align: left;
}

.device-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 4px;
    word-break: break-all;
}

.device-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 11px;
    color: #64748b;
}

.device-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
}

.device-meta-item i {
    font-size: 10px;
    opacity: 0.7;
}

.no-device {
    padding: 20px;
    text-align: center;
    color: #64748b;
    font-size: 13px;
}

.no-device i {
    font-size: 24px;
    margin-bottom: 8px;
    opacity: 0.5;
    display: block;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
}
</style>

<div class="keyfree-container">
    <div class="keyfree-card">
        @php
            // Hỗ trợ cả 2 cách truyền data (cũ: $freeKey, mới: $session + $key)
            $keyValue = $key ?? ($freeKey->key_value ?? null);
            $expiresAtValue = $expires_at ?? ($freeKey->expires_at ?? null);
            $createdAtValue = $session->activated_at ?? ($freeKey->created_at_api ?? ($freeKey->created_at ?? null));
            $isExpired = $expiresAtValue && $expiresAtValue->isPast();
        @endphp
        
        @if($keyValue)
            @if($isExpired)
                {{-- Expired State --}}
                <div class="keyfree-header expired-header">
                    <div class="keyfree-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h1>⏰ Key Đã Hết Hạn!</h1>
                    <p>Key miễn phí này đã hết thời gian sử dụng</p>
                </div>
                
                <div class="keyfree-body">
                    <div class="countdown-wrap expired">
                        <div class="countdown-label">Đã hết hạn</div>
                        <div class="countdown-timer">
                            <div class="countdown-item">
                                <div class="countdown-value">00</div>
                                <div class="countdown-unit">Giờ</div>
                            </div>
                            <span class="countdown-separator">:</span>
                            <div class="countdown-item">
                                <div class="countdown-value">00</div>
                                <div class="countdown-unit">Phút</div>
                            </div>
                            <span class="countdown-separator">:</span>
                            <div class="countdown-item">
                                <div class="countdown-value">00</div>
                                <div class="countdown-unit">Giây</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <p>Bạn có thể lấy key miễn phí mới bằng cách quay lại trang hack.</p>
                    </div>
                    
                    <a href="{{ route('hacks.show', 1) }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Lấy Key Mới
                    </a>
                </div>
            @else
                <div class="keyfree-body">
                    {{-- Row: Countdown + Key Display --}}
                    <div class="key-countdown-row">
                        {{-- Countdown Timer --}}
                        <div class="countdown-wrap" id="countdownWrap">
                            <h1 style="color: white">THÀNH CÔNG</h1>
                            <div class="countdown-label">Thời Gian Còn Lại</div>
                            <div class="countdown-timer" id="countdownTimer">
                                <div class="countdown-item">
                                    <div class="countdown-value" id="countHours">--</div>
                                    <div class="countdown-unit">Giờ</div>
                                </div>
                                <span class="countdown-separator">:</span>
                                <div class="countdown-item">
                                    <div class="countdown-value" id="countMinutes">--</div>
                                    <div class="countdown-unit">Phút</div>
                                </div>
                                <span class="countdown-separator">:</span>
                                <div class="countdown-item">
                                    <div class="countdown-value" id="countSeconds">--</div>
                                    <div class="countdown-unit">Giây</div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Key Display với Copy Button --}}
                        <div class="key-display">
                            <div class="key-label">🔑 Key Của Bạn</div>
                            <div class="key-value-wrap">
                                <div class="key-value" id="keyValue">{{ $keyValue }}</div>
                                <button type="button" class="btn-copy-inline" id="btnCopy" onclick="copyKey()" title="Sao chép key">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Time Info --}}
                    <div class="time-info">
                        <div class="time-item">
                            <div class="time-item-label">📅 Ngày Tạo</div>
                            <div class="time-item-value">
                                {{ $createdAtValue ? $createdAtValue->format('d/m/Y H:i:s') : 'N/A' }}
                            </div>
                        </div>
                        <div class="time-item">
                            <div class="time-item-label">⏰ Hết Hạn</div>
                            <div class="time-item-value">
                                {{ $expiresAtValue ? $expiresAtValue->format('d/m/Y H:i:s') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                    
                    {{-- Device Section --}}
                    @if(isset($devices) && is_array($devices))
                    <div class="device-section">
                        <div class="device-header">
                            <div class="device-title">
                                <i class="fas fa-mobile-alt"></i>
                                Thiết Bị Đã Dùng
                            </div>
                            @php
                                $deviceCount = count($devices);
                                $deviceLimit = $deviceLimit ?? 1;
                                $countClass = '';
                                if ($deviceCount >= $deviceLimit) {
                                    $countClass = 'full';
                                } elseif ($deviceCount > 0) {
                                    $countClass = 'warning';
                                }
                            @endphp
                            <span class="device-count {{ $countClass }}">{{ $deviceCount }}/{{ $deviceLimit }}</span>
                        </div>
                        
                        <div class="device-list">
                            @if($deviceCount > 0)
                                @foreach($devices as $device)
                                    <div class="device-item">
                                        <div class="device-icon">
                                            <i class="fas fa-mobile-alt"></i>
                                        </div>
                                        <div class="device-info">
                                            <div class="device-name">
                                                @php
                                                    $deviceName = $device['name'] 
                                                        ?? $device['device_name'] 
                                                        ?? $device['model'] 
                                                        ?? $device['brand'] 
                                                        ?? null;
                                                    
                                                    if (!$deviceName && !empty($device['device_id'])) {
                                                        $deviceName = 'Device ' . \Illuminate\Support\Str::limit($device['device_id'], 8);
                                                    }
                                                    
                                                    $deviceName = $deviceName ?: 'Thiết bị không xác định';
                                                @endphp
                                                {{ $deviceName }}
                                            </div>
                                            <div class="device-meta">
                                                @if(!empty($device['device_id']))
                                                    <span class="device-meta-item">
                                                        <i class="fas fa-fingerprint"></i>
                                                        {{ \Illuminate\Support\Str::limit($device['device_id'], 16) }}
                                                    </span>
                                                @endif
                                                @if(!empty($device['created_at']))
                                                    <span class="device-meta-item">
                                                        <i class="fas fa-clock"></i>
                                                        {{ \Carbon\Carbon::parse($device['created_at'])->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="status-badge active">
                                            <i class="fas fa-check-circle"></i>
                                            Active
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="no-device">
                                    <i class="fas fa-mobile-alt"></i>
                                    Chưa Có Dữ Liệu
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    {{-- Info Box --}}
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <p>Hạn Sử Dụng Key Free Đến 23:59:59 Cùng Ngày</p>
                    </div>
                    
                    <a href="{{ route('hacks.show', 1) }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Quay Lại
                    </a>
                </div>
            @endif
        @else
            {{-- Error State --}}
            <div class="keyfree-header error">
                <div class="keyfree-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1>Có Lỗi Xảy Ra!</h1>
                <p>Không thể nhận key lúc này</p>
            </div>
            
            <div class="keyfree-body">
                <div class="error-message">
                    <i class="fas fa-times-circle"></i>
                    <span>{{ $error ?? 'Không tìm thấy key! Vui lòng thử lại.' }}</span>
                </div>
                
                <a href="{{ route('hacks.show', 1) }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Thử Lại
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-notify" id="toastNotify">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage">Đã sao chép!</span>
</div>

@if($keyValue && !$isExpired)
<script>
// Countdown timer - sử dụng timestamp để chính xác
(function() {
    // expires_at đã được parse về timezone của server
    const expiresAtTimestamp = {{ $expiresAtValue ? $expiresAtValue->timestamp * 1000 : 0 }};
    
    const hoursEl = document.getElementById('countHours');
    const minutesEl = document.getElementById('countMinutes');
    const secondsEl = document.getElementById('countSeconds');
    const wrapEl = document.getElementById('countdownWrap');
    
    function updateCountdown() {
        const now = Date.now();
        let countdown = Math.floor((expiresAtTimestamp - now) / 1000);
        
        if (countdown <= 0) {
            hoursEl.textContent = '00';
            minutesEl.textContent = '00';
            secondsEl.textContent = '00';
            wrapEl.classList.add('expired');
            return;
        }
        
        const hours = Math.floor(countdown / 3600);
        const minutes = Math.floor((countdown % 3600) / 60);
        const seconds = countdown % 60;
        
        hoursEl.textContent = hours.toString().padStart(2, '0');
        minutesEl.textContent = minutes.toString().padStart(2, '0');
        secondsEl.textContent = seconds.toString().padStart(2, '0');
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
})();

// Copy key to clipboard
function copyKey() {
    const keyValue = document.getElementById('keyValue').textContent;
    const btn = document.getElementById('btnCopy');
    
    navigator.clipboard.writeText(keyValue).then(() => {
        showCopySuccess();
    }).catch(() => {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = keyValue;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showCopySuccess();
    });
    
    function showCopySuccess() {
        btn.classList.add('copied');
        btn.querySelector('i').className = 'fas fa-check';
        
        // Show toast
        showToast('Đã sao chép key vào clipboard!');
        
        // Reset after 2s
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.querySelector('i').className = 'fas fa-copy';
        }, 2000);
    }
}

// Show toast notification
function showToast(message) {
    const toast = document.getElementById('toastNotify');
    const toastMsg = document.getElementById('toastMessage');
    
    toastMsg.textContent = message;
    toast.classList.add('show', 'success');
    
    setTimeout(() => {
        toast.classList.remove('show', 'success');
    }, 2500);
}
</script>
@endif

@endsection
