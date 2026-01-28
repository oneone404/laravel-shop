@extends('layouts.user.app')
@section('title', 'Lỗi - Key Free')
@section('content')

<style>
.keyfree-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 15px;
}

.keyfree-card {
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

.keyfree-header {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    padding: 28px 20px;
    text-align: center;
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

.keyfree-body {
    padding: 24px 20px;
    text-align: center;
}

.error-message {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: 16px;
    color: #b91c1c;
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

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
    padding: 12px 24px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.back-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: #fff;
}
</style>

<div class="keyfree-container">
    <div class="keyfree-card">
        <div class="keyfree-header">
            <div class="keyfree-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1>Đã Xảy Ra Lỗi!</h1>
            <p>Không thể lấy key miễn phí</p>
        </div>
        
        <div class="keyfree-body">
            <div class="error-message">
                <i class="fas fa-times-circle"></i>
                <span>{{ $error ?? 'Đã có lỗi xảy ra. Vui lòng thử lại sau!' }}</span>
            </div>
            
            <a href="{{ route('hacks.show', 1) }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Quay Lại Thử Lại
            </a>
        </div>
    </div>
</div>

@endsection
