@extends('layouts.user.app')
@section('title', 'Xác Nhận Nhận Key')
@section('content')

<style>
.confirm-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 15px;
}

.confirm-card {
    width: 100%;
    max-width: 500px;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    animation: cardSlideUp 0.5s ease-out;
    border: 1px solid #e2e8f0;
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

.confirm-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 28px 20px;
    text-align: center;
}

.confirm-icon {
    width: 70px;
    height: 70px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    backdrop-filter: blur(10px);
    animation: iconPulse 2s ease-in-out infinite;
}

@keyframes iconPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.confirm-icon i {
    font-size: 32px;
    color: #fff;
}

.confirm-header h1 {
    color: #fff;
    font-size: 22px;
    font-weight: 700;
    margin: 0 0 6px;
}

.confirm-header p {
    color: rgba(255,255,255,0.85);
    font-size: 13px;
    margin: 0;
}

.confirm-body {
    padding: 28px 24px;
    text-align: center;
}

.info-text {
    color: #64748b;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 24px;
}

.info-text strong {
    color: #1e293b;
}

.turnstile-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 24px;
    min-height: 65px;
}

.btn-activate {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 16px 24px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-activate:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
}

.btn-activate:active {
    transform: translateY(0);
}

.btn-activate:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-activate i {
    font-size: 18px;
}

.error-alert {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: 12px 16px;
    color: #b91c1c;
    font-size: 14px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.error-alert i {
    font-size: 18px;
    flex-shrink: 0;
}

.security-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
    font-size: 12px;
    margin-top: 16px;
}

.security-badge i {
    color: #10b981;
}

@media (max-width: 480px) {
    .confirm-container {
        padding: 20px 12px;
    }
    
    .confirm-header {
        padding: 22px 16px;
    }
    
    .confirm-body {
        padding: 24px 16px;
    }
    
    .confirm-header h1 {
        font-size: 18px;
    }
}
</style>

<div class="confirm-container">
    <div class="confirm-card">
        <div class="confirm-header">
            <div class="confirm-icon">
                @if($tooFast ?? false)
                    <i class="fas fa-exclamation-triangle"></i>
                @else
                    <i class="fas fa-key"></i>
                @endif
            </div>
            @if($tooFast ?? false)
                <h1>PHÁT HIỆN BẤT THƯỜNG</h1>
                <code>Có Lỗi Xảy Ra Trong Phiên Làm Việc</code>
            @else
                <h1>VƯỢT LINK THÀNH CÔNG</h1>
                <code>Bước Cuối Cùng Để Nhận Key Miễn Phí</code>
            @endif
        </div>
        
        <div class="confirm-body">
            @if($tooFast ?? false)
                {{-- Lỗi: Vượt quá nhanh --}}
                <div style="background: #1e1e1e; border-radius: 10px; padding: 20px; margin-bottom: 20px; border: 1px solid #dc2626;">
                    <code style="color: #ef4444; font-size: 14px; font-family: 'Courier New', monospace; display: block; text-align: center; letter-spacing: 1px;">
                        BẠN CẦN VƯỢT LINK ĐỂ LẤY KEY
                    </code>
                </div>
                
                <a href="{{ route('hacks.show', 1) }}" class="btn-activate" style="text-decoration: none; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                    <i class="fas fa-arrow-left"></i>
                    THỬ LẠI
                </a>
            @else
                @if(session('error'))
                    <div class="error-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                
                <p class="info-text">
                    Vui Lòng Chờ Vài Giây Để Kiểm Tra.
                    <strong>Key</strong> Sẽ Được <strong>Hiển Thị</strong> Sau Khi Xác Minh Thành Công.
                </p>
                
                <form method="POST" action="{{ route('keyfree.activate', ['token' => $token]) }}" id="activateForm">
                    @csrf
                    
                    {{-- Cloudflare Turnstile Widget --}}
                    <div class="turnstile-wrapper">
                        @if($turnstileSiteKey)
                            <div class="cf-turnstile" 
                                 data-sitekey="{{ $turnstileSiteKey }}"
                                 data-callback="onTurnstileSuccess"
                                 data-theme="light">
                            </div>
                        @else
                            <p style="color: #f59e0b; font-size: 13px;">
                                <i class="fas fa-exclamation-triangle"></i>
                                Turnstile chưa được cấu hình
                            </p>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn-activate" id="btnActivate" disabled>
                        <i class="fas fa-hourglass-half"></i>
                        Đang Chờ Xác Minh...
                    </button>
                </form>
                
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    Được Bảo Vệ Bởi Cloudflare
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Cloudflare Turnstile Script --}}
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

<script>
function onTurnstileSuccess(token) {
    // Auto submit form khi Turnstile hoàn thành
    var btn = document.getElementById('btnActivate');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang Hiển Thị Key...';
    
    // Submit form ngay
    document.getElementById('activateForm').submit();
}
</script>

@endsection
