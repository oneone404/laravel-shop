@extends('layouts.user.app')
@section('title', 'Lỗi Thanh Toán')
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
    color: #ef4444;
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

.payment-body {
    padding: 40px 24px;
    text-align: center;
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
    background: #fef2f2;
    border: 1px solid #fee2e2;
    text-align: left;
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

/* Action Buttons */
.footer-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
    justify-content: center;
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

.error-illustration {
    font-size: 64px;
    color: #cbd5e1;
    margin-bottom: 24px;
}
</style>

<div class="payment-container">
    <div class="payment-card">
        <div class="payment-header">
            <div class="payment-header-top">
                <h1><i class="fas fa-exclamation-circle"></i> Thông Báo</h1>
                <div class="order-code-pill">ERROR</div>
            </div>
        </div>

        <div class="payment-body">
            <div class="status-banner">
                <div class="status-icon-wrapper">
                    <i class="fas fa-times"></i>
                </div>
                <div class="status-info-text">
                    <h3>Không Thể Thực Hiện</h3>
                    <p>Đã Xảy Ra Lỗi Trong Quá Trình Xử Lý.</p>
                </div>
            </div>

            <div class="error-illustration">
                <i class="fas fa-unlink"></i>
            </div>

            <p style="color: #64748b; font-size: 15px; margin-bottom: 32px; line-height: 1.6;">
                {{ $error ?? 'Yêu Cầu Của Bạn Không Thể Hoàn Thành Vào Lúc Này. Vui Lòng Kiểm Tra Lại Đơn Hàng Hoặc Liên Hệ Admin.' }}
            </p>

            <div class="footer-actions">
                <a href="javascript:history.back()" class="btn-secondary-pill">
                    <i class="fas fa-chevron-left"></i> Quay Lại
                </a>
                <a href="/" class="btn-secondary-pill">
                    <i class="fas fa-home"></i> Về Trang Chủ
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
