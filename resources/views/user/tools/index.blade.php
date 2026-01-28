@extends('layouts.user.app')

@section('title', 'Trung Tâm Công Cụ')

@section('content')
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
                    <a href="{{ route('tools.gift-code') }}" class="btn btn--primary btn-tool">
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
                    <a href="{{ route('tools.fish-id') }}" class="btn btn--primary btn-tool">
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
                    <a href="{{ route('tools.fake-id') }}" class="btn btn--primary btn-tool">
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