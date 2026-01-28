@extends('layouts.user.app')

@section('title', 'Trung Tâm Công Cụ')

@section('content')
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/css/apps.css') }}">
    @endpush

    <x-hero-header title="TRUNG TÂM CÔNG CỤ"
        description="Khám phá các công cụ hỗ trợ trải nghiệm game tối ưu nhất cho game thủ." />

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
                    <a href="{{ route('apps.gift-code') }}" class="btn btn--primary btn-tool">
                        Sử Dụng Ngay <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Fish ID Tool -->
            <div class="tool-card">
                <div class="tool-icon" style="background: linear-gradient(135deg, #0ea5e9, #38bdf8);">
                    <i class="fas fa-fish"></i>
                </div>
                <div class="tool-info">
                    <div class="tool-status">
                        <span class="status-dot"></span> Đang hoạt động
                    </div>
                    <h3 class="tool-name">Danh Sách ID Cá</h3>
                    <p class="tool-desc">Tra cứu nhanh mã ID của các loại cá và vật phẩm rác trong game để setup Auto.</p>
                </div>
                <div class="tool-action">
                    <a href="{{ route('apps.fish-id') }}" class="btn btn--primary btn-tool">
                        Tra Cứu ID <i class="fas fa-search-plus"></i>
                    </a>
                </div>
            </div>

            <!-- Region ID Tool -->
            <div class="tool-card">
                <div class="tool-icon" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="tool-info">
                    <div class="tool-status">
                        <span class="status-dot"></span> Đang hoạt động
                    </div>
                    <h3 class="tool-name">ID Vùng Câu (Region)</h3>
                    <p class="tool-desc">Danh sách mã vùng câu hỗ trợ tính năng chuyển vùng (Global/VN) và Fake Region.</p>
                </div>
                <div class="tool-action">
                    <a href="{{ route('apps.fake-id') }}" class="btn btn--primary btn-tool">
                        Xem Danh Sách <i class="fas fa-list-ul"></i>
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
        </div>
    </div>
@endsection