@extends('layouts.user.app')
@section('title', 'Vòng Quay May Mắn')
@section('content')
    <div class="container">
        {{-- Notification Area --}}
        {{-- This div is for JavaScript-driven notifications (e.g., AJAX responses) --}}
        <div id="page-notification" class="page-notification" style="display: none;">
            <span id="page-notification-message"></span>
            <button id="page-notification-close" class="page-notification-close">&times;</button>
        </div>

        {{-- This block is for Laravel session flash messages --}}
        @if (session('notification_message'))
            <div class="page-notification {{ session('notification_type') ?: 'info' }}" style="display: flex;">
                <span>{{ session('notification_message') }}</span>
                {{-- Inline JS for simplicity, or you can add a dedicated event listener --}}
                <button onclick="this.parentElement.style.display='none';" class="page-notification-close">&times;</button>
            </div>
        @endif
        {{-- End Notification Area --}}

        <div class="lucky-wheel-container">
            <div class="wheel-page">
                <div class="wheel-info">
                    @if(Auth::check())
                    <div id="lucky-status" class="lucky-progress-container" aria-label="Tỉ lệ may mắn">
                        <div class="lucky-progress-bar" style="width: {{ $lucky }}%;"></div>
                        <span class="lucky-progress-text">{{ $lucky }}%</span>
                    </div>
                    @endif
                    <div class="wheel-price">
                        <span>{{ number_format($wheel->price_per_spin) }} VNĐ</span> / LƯỢT QUAY
                    </div>
                    @auth
                    <div id="free-spins-info" class="wheel-price" style="margin-bottom: 20px;">LƯỢT QUAY MIỄN PHÍ CÒN LẠI <strong style="color:yellow">{{ $freeSpinsLeft }}</strong></div>
                    @endauth
                </div>
                <div class="wheel-container">
                    <img src="{{ $wheel->wheel_image }}" alt="Vòng quay" class="wheel-image">
                    <img src="{{ asset('images/needle.png') }}" alt="QUAY" class="needle-image" id="spin-btn">
                </div>
            </div>
        </div>
<style>
    /* === START: UPDATED CSS FOR TOAST NOTIFICATIONS === */
.page-notification {
    position: fixed;     /* Để nổi trên các nội dung khác */
    top: 25vh;           /* CÁCH TRÊN XUỐNG 1/4 CHIỀU CAO MÀN HÌNH */
    right: 20px;         /* Khoảng cách từ phải màn hình */
    z-index: 1055;       /* Đảm bảo nổi trên hầu hết các element khác */
    max-width: 350px;    /* Chiều rộng tối đa cho toast */
    width: auto;         /* Tự động điều chỉnh chiều rộng theo nội dung */
    padding: 15px 20px;
    margin-bottom: 10px; /* Khoảng cách nếu có nhiều toast (cho tương lai) */
    border: 1px solid transparent;
    border-radius: 8px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 0.95rem;  /* Kích thước chữ nhỏ hơn một chút cho toast */
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15); /* Bóng đổ rõ hơn cho element nổi */
    /* background-color: #fff; */ /* Bỏ màu nền mặc định ở đây để các class con tự định nghĩa */
    /* color: #333; */ /* Bỏ màu chữ mặc định ở đây */
}

.page-notification.success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.page-notification.error {
    background-color:rgb(158, 29, 14); /* MÀU ĐỎ ĐẬM (Pomegranate) */
    color: #ffffff;             /* Chữ màu trắng để tương phản */
    border-color: #a93226;      /* Border đậm hơn một chút */
}

.page-notification.info {
    color: #004085;
    background-color: #cce5ff;
    border-color: #b8daff;
}

.page-notification-close {
    background: transparent;
    border: none;
    font-size: 1.6rem;
    font-weight: bold;
    color: inherit; /* Lấy màu từ class cha (.success, .error, .info) */
    cursor: pointer;
    padding: 0 0 0 15px;
    line-height: 1;
    opacity: 0.7; /* Làm cho nút X bớt chói */
    transition: opacity 0.2s ease-in-out;
}
.page-notification-close:hover {
    opacity: 1;
}
/* === END: UPDATED CSS FOR TOAST NOTIFICATIONS === */

    .wheel-controls {
        display: flex;
        justify-content: center;  /* Căn giữa theo chiều ngang */
        align-items: center;      /* Căn giữa theo chiều cao nếu cần */
        gap: 12px;                /* Khoảng cách giữa các phần tử */
        margin: 20px auto;        /* Để block nằm giữa trang */
    }
    .wheel-spin-btn {
        background: linear-gradient(145deg,rgb(255, 0, 0),rgb(48, 42, 48)); /* Gradient cam-vàng */
        color: #fff;
        padding: 14px 32px;
        font-size: 1.5rem;
        font-weight: bold;
        border: none;
        border-radius: 999px;
        box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4), inset 0 -4px 6px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .wheel-spin-btn::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.4) 0%, transparent 70%);
        animation: spin-glow 3s linear infinite;
        z-index: 0;
    }

    .wheel-spin-btn span {
        position: relative;
        z-index: 1;
    }

    .wheel-spin-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 24px rgba(255, 165, 0, 0.6);
    }

    .wheel-spin-btn:active {
        transform: scale(0.95);
        box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    @keyframes spin-glow {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .wheel-price {
        display: block;
        background: rgba(255, 255, 255, 0.1);
        padding: 10px 25px;
        border-radius: var(--border-radius-pill);
        margin-top: 30px;
        font-size: 1.5rem;
        font-weight: 550;
        text-align: center;
    }
    .lucky-progress-container {
        position: relative;
        display: block;              /* Đảm bảo xuống hàng */
        width: 100%;
        max-width: 400px;
        height: 30px;
        background-color: #e0e0e0;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
        margin: 20px auto;            /* Căn giữa và tạo khoảng cách top/bottom */
    }

    .lucky-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #FFD700, #FF8C00); /* Màu vàng sang */
        border-radius: 15px 0 0 15px;
        transition: width 0.5s ease-in-out;
    }

    .lucky-progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: bold;
        color: #333;
        user-select: none;
        pointer-events: none;
    }

    .wheel-container {
        position: relative;
        max-width: 90vw;
        aspect-ratio: 1 / 1;
        margin: auto;
    }

    .wheel-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 5s cubic-bezier(0.2, 0.8, 0.3, 1);
    }

    .needle-image {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -65%);
        width: 25%; /* tuỳ chỉnh kích thước kim */
        height: auto;
        user-select: none;
        z-index: 10;
        opacity: 0.6;
        transition: opacity 0.3s ease, transform 0.3s ease;
        cursor: pointer;
    }

    .needle-image:hover,
    .needle-image:active {
        opacity: 1;
        transform: translate(-50%, -65%) scale(1.1);
    }

    .history-table {
        white-space: nowrap;
        min-width: 700px; /* hoặc lớn hơn nếu bảng rộng hơn */
    }

    .history-table th,
    .history-table td {
        white-space: nowrap;
    }
    .detail__info {
        background: #f9f9f9;
        padding: 15px 20px;
        border-radius: 8px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        max-width: 400px;
    }

    .detail__info-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }

    .detail__info-item {
        margin-bottom: 12px;
        font-size: 14px;
        line-height: 1.4;
    }

    .detail__info-item strong {
        color: #e67e22; /* Màu cam nổi bật cho dấu ★ */
    }

    .detail__free-spins-list {
        list-style: disc inside;
        margin-top: 6px;
        margin-left: 16px;
        color: #555;
        font-size: 13px;
    }
    .history-table {
    width: 100%; /* Bảng chiếm toàn bộ chiều rộng container của nó */
    table-layout: fixed;
}
</style>
<style>
    .swal-btn-confirm {
        background-color: #3498db; /* xanh dương tươi */
        color: white;
        font-size: 15px;
        font-weight: 600;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        margin: 0 8px;
        cursor: pointer;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.2s ease;
    }

    .swal-btn-confirm:hover {
        background-color: #2980b9; /* xanh dương đậm hơn khi hover */
    }

    .swal-btn-cancel {
        background-color: #7f8c8d; /* xám đậm */
        color: white;
        font-size: 15px;
        font-weight: 600;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        margin: 0 8px;
        cursor: pointer;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.2s ease;
    }

    .swal-btn-cancel:hover {
        background-color: #636e72; /* hover xám tối hơn */
    }
</style>
        <section class="deposit-history" id="history-section">
            <h2 class="history-header">LỊCH SỬ VÒNG QUAY</h2>
            @if (count($history) > 0)
                <div class="history-table-container">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th style="width: 100px; text-align: center;">THAO TÁC</th>
                                <th>Phần Thưởng</th>    
                                <th>Số LƯỢT</th>
                                <th>TỔNG TIỀN</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $index => $item)
                                @if (!Str::startsWith($item->description, 'ONE'))
                                    @continue
                                @endif
                                <tr class="{{ $index >= 5 ? 'history-hidden' : '' }}" style="{{ $index >= 5 ? 'display:none;' : '' }}">
                                    <td>
                                        <!-- Form reset key -->
                                        <form action="{{ route('user.reset-key') }}" method="POST" class="reset-form" data-key="{{ $item->description }}" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="key_value" value="{{ $item->description }}">
                                            <button type="submit" class="reset-btn" style="background: none; border: none; padding: 4px; cursor: pointer; color:#3399FF;" title="Reset Key">
                                                <i class="fas fa-rotate fa-lg text-warning"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        {{ substr($item->description, 0, 10) . '***' }}
                                        <button class="copy-btn" data-clipboard-text="{{ $item->description }}">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                    <td>{{ $item->spin_count }}</td>
                                    <td>{{ number_format($item->total_cost) }} VNĐ</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-history">
                    <p>Chưa Có Dữ Liệu</p>
                </div>
            @endif
        </section>

        @if(Auth::check())
        <div class="total-deposit-progress" style="max-width: 500px; margin: 20px auto; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
            <h3 style="text-align: center; color: #222; font-weight: 700; margin-bottom: 15px; font-size: 18px;">
                Tổng Nạp <span style="color: #1565c0;">{{ number_format($totalDeposited) }} VND</span>
            </h3>
            @php
                $levels = [0, 50000, 150000, 300000, 500000, 1000000];
                $labels = ['0K', '50K', '150K', '300K', '500K', '1M'];
                function calcPercent($levels, $total) {
                    for ($i = 0; $i < count($levels) - 1; $i++) {
                        if ($total >= $levels[$i] && $total <= $levels[$i+1]) {
                            $range = $levels[$i+1] - $levels[$i];
                            $progressInRange = $total - $levels[$i];
                            $segmentPercent = 100 / (count($levels) - 1);
                            return $i * $segmentPercent + ($progressInRange / $range) * $segmentPercent;
                        }
                    }
                    return 100;
                }
                $percent = calcPercent($levels, $totalDeposited);
            @endphp
            <div style="position: relative; background: #bbdefb; border-radius: 10px; height: 18px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                <div style="
                    background: #1565c0;
                    height: 100%;
                    width: {{ $percent }}%;
                    border-radius: 10px 0 0 10px;
                    transition: width 0.7s ease-in-out;
                    box-shadow: 0 0 10px #0d47a1aa;
                    position: relative;
                ">
                    <span style="
                        position: absolute;
                        right: 8px;
                        top: 50%;
                        transform: translateY(-50%);
                        font-weight: 700;
                        color: #e3f2fd;
                        font-size: 12px;
                        text-shadow: 0 0 3px rgba(0,0,0,0.2);
                        user-select: none;
                    ">{{ round($percent, 1) }}%</span>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 12px; user-select: none; font-size: 11px; color: #1565c0;">
                @foreach ($levels as $key => $level)
                    @php
                        $isActive = $totalDeposited >= $level;
                    @endphp
                    <div style="position: relative; text-align: center; flex: 1;">
                        <div style="
                            width: 14px;
                            height: 14px;
                            margin: 0 auto 4px;
                            border-radius: 50%;
                            background: {{ $isActive ? '#1565c0' : '#90caf9' }};
                            box-shadow: {{ $isActive ? '0 0 6px #0d47a1cc' : 'none' }};
                            transition: background 0.3s;
                            cursor: default;
                        " title="Mốc nạp: {{ number_format($level) }} VND"></div>
                        <small style="color: {{ $isActive ? '#0d47a1' : '#64b5f6' }}; font-weight: 600;">
                            {{ $labels[$key] }}
                        </small>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <section class="rules-section">
            <h2 class="history-header">QUY ĐỊNH TRÚNG THƯỞNG</h2>
            <div class="detail__info">
                <ul class="detail__info-list">
                    <li class="detail__info-item">
                        <strong>★ TĂNG 10% MAY MẮN</strong>
                        <ul class="detail__free-spins-list">
                            <li>Nếu Đủ 100% Sẽ Nâng Cơ Hội Trúng Key Lên 100% (100% Sẽ Quay Trúng Key VIP)</li>
                        </ul>
                    </li>
                    <li class="detail__info-item">
                        <strong>★ PHẦN THƯỞNG KEY VIP</strong>
                        <ul class="detail__free-spins-list">
                            <li>KEY 1 NGÀY</li>
                            <li>KEY 1 TUẦN</li>
                            <li>KEY 1 THÁNG</li>
                        </ul>
                    </li>
                    <li class="detail__info-item">
                        <strong>★ LƯỢT QUAY MIỄN PHÍ</strong>
                        <ul class="detail__free-spins-list">
                            <li>TỔNG NẠP <strong style="color:black">50K - 1 LƯỢT / NGÀY</strong></li>
                            <li>TỔNG NẠP <strong style="color:black">150K - 2 LƯỢT / NGÀY</strong></li>
                            <li>TỔNG NẠP <strong style="color:black">300K - 3 LƯỢT / NGÀY</strong></li>
                            <li>TỔNG NẠP <strong style="color:black">500K - 5 LƯỢT / NGÀY</strong></li>
                            <li>TỔNG NẠP <strong style="color:black">1M - 10 LƯỢT / NGÀY</strong></li>
                            <li><strong style="color:black">Mỗi Ngày Có Thể Vào Quay Miễn Phí</strong></li>
                            <li><strong style="color:red">Lưu ý: </strong><strong>Lưu Key Lại Khi Trúng Thưởng</strong></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </section>

        <div class="result-modal" id="result-modal">
            <div class="modal-content">
                <button class="modal-close" id="modal-close"><i class="fas fa-times"></i></button>
                <div class="result-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <h3 class="result-title">CHÚC MỪNG!</h3>
                <p class="result-desc">Bạn Đã Trúng Phần Thưởng</p>
                <div class="result-reward" id="result-reward"></div>
                <button class="btn btn--primary" id="continue-btn">OK</button>
            </div>
        </div>
        {{-- Thêm modal này vào cuối @section('content') hoặc gần modal kết quả --}}
<div class="result-modal" id="general-message-modal">
    <div class="modal-content">
        <button class="modal-close" id="general-message-modal-close"><i class="fas fa-times"></i></button>
        <div class="result-icon" id="general-message-modal-icon">
            <i class="fas fa-info-circle"></i> </div>
        <h3 class="result-title" id="general-message-modal-title">THÔNG BÁO</h3>
        <p class="result-reward" id="general-message-modal-text"></p>
        <button class="btn btn--primary" id="general-message-modal-continue-btn">OK</button>
    </div>
</div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 🔸 Hiển thị SweetAlert khi có session success hoặc error
    @if (session('success'))
        Swal.fire({
            title: 'THÀNH CÔNG',
            html: `<div style="font-size: 15px; padding: 10px;">{!! session('success') !!}</div>`,
            icon: 'success',
            confirmButtonText: 'OK',
            reverseButtons: true,
            width: '420px',
            padding: '2em',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'swal-btn-confirm',
                title: 'fw-bold',
                htmlContainer: 'text-center'
            }
        });
    @elseif (session('error'))
        Swal.fire({
            title: 'THẤT BẠI',
            html: `<div style="font-size: 15px; padding: 10px;">{!! session('error') !!}</div>`,
            icon: 'error',
            confirmButtonText: 'OK',
            reverseButtons: true,
            width: '420px',
            padding: '2em',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'swal-btn-confirm',
                title: 'fw-bold',
                htmlContainer: 'text-center'
            }
        });
    @endif

    // 🔸 Gắn sự kiện submit vào form reset
    document.querySelectorAll('.reset-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const key = this.getAttribute('data-key');
            const currentForm = this;

            Swal.fire({
                title: 'XÁC NHẬN',
                html: `
                    <div style="font-size: 15px; padding: 10px;">
                        LÀM MỚI VỀ <strong>0</strong> THIẾT BỊ SỬ DỤNG?<br>
                        <span style="color: #555; font-size: 17px;">Phí <strong>5.000 VND</strong><br>( Miễn Phí Lần Đầu )</span>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ĐỒNG Ý',
                cancelButtonText: 'HUỶ',
                reverseButtons: true,
                width: '420px',
                padding: '2em',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'swal-btn-confirm',
                    cancelButton: 'swal-btn-cancel',
                    title: 'fw-bold',
                    htmlContainer: 'text-center'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentForm.submit();
                }
            });
        });
    });

});

    document.addEventListener('DOMContentLoaded', function() {
        // Clipboard.js init
        const copyButtons = document.querySelectorAll('.copy-btn');
        if (copyButtons.length > 0) {
            const clipboard = new ClipboardJS('.copy-btn');
            clipboard.on('success', function(e) {
                const originalText = e.trigger.innerHTML;
                e.trigger.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(function() {
                    e.trigger.innerHTML = originalText;
                }, 2000);
                e.clearSelection();
            });
        }

        // Spin the wheel
        const prizeConfig = @json($config);
        let isSpinning = false;
        const spinBtn = document.getElementById('spin-btn');
        const wheelElement = document.querySelector('.wheel-image');
        const spinCount = 1; // Giữ nguyên vì bạn đang lấy giá trị này từ input/logic khác nếu cần quay nhiều
        const totalItems = 8; // Hoặc {{ count($config) }} nếu $config luôn đủ 8 item trên vòng quay
        const arcAngle = 360 / totalItems;

        // Elements cho General Message Modal MỚI
        const generalMessageModal = document.getElementById('general-message-modal');
        const generalMessageModalTitle = document.getElementById('general-message-modal-title');
        const generalMessageModalText = document.getElementById('general-message-modal-text');
        const generalMessageModalIcon = document.getElementById('general-message-modal-icon').querySelector('i');
        const generalMessageModalCloseBtn = document.getElementById('general-message-modal-close');
        const generalMessageModalContinueBtn = document.getElementById('general-message-modal-continue-btn');

        // Elements cho Result Modal (trúng thưởng)
        const resultModal = document.getElementById('result-modal');
        const resultRewardText = document.getElementById('result-reward');
        const resultModalClose = document.getElementById('modal-close');
        const resultContinueBtn = document.getElementById('continue-btn');

        // Hàm hiển thị General Message Modal MỚI
        function showGeneralMessage(title, message, iconClass = 'fas fa-info-circle') {
            if (generalMessageModalTitle) generalMessageModalTitle.textContent = title;
            if (generalMessageModalText) generalMessageModalText.textContent = message;
            if (generalMessageModalIcon) generalMessageModalIcon.className = iconClass; // ví dụ: 'fas fa-exclamation-triangle' cho lỗi

            if (generalMessageModal) generalMessageModal.classList.add('active');
        }

        // Hàm đóng General Message Modal MỚI
        function closeGeneralMessageModal() {
            if (generalMessageModal) generalMessageModal.classList.remove('active');
        }

        // Event listeners cho General Message Modal MỚI
        if (generalMessageModalCloseBtn) generalMessageModalCloseBtn.addEventListener('click', closeGeneralMessageModal);
        if (generalMessageModalContinueBtn) generalMessageModalContinueBtn.addEventListener('click', closeGeneralMessageModal);


        spinBtn.addEventListener('click', spinWheel);
        function spinWheel() {
            if (isSpinning) return;
            isSpinning = true;
            spinBtn.disabled = true;

            const spinCountValue = spinCount;

            fetch('{{ route('lucky.spin', $wheel->slug) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    spin_count: spinCountValue
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    // Sử dụng modal mới để hiển thị lỗi/thông báo từ controller
                    showGeneralMessage('Thông Báo', data.message, data.iconClass || 'fas fa-exclamation-triangle');
                    isSpinning = false;
                    spinBtn.disabled = false;
                    return;
                }

                const reward = data.rewards[0];
                const selectedIndex = reward.index;

                const padding = 5;
                const randomOffset = padding + Math.random() * (arcAngle - 2 * padding);
                const startOffset = 20;
                const stopAngle = -(selectedIndex * arcAngle + randomOffset) + startOffset;
                const extraRotations = 10;
                const totalRotation = stopAngle - (360 * extraRotations);

                wheelElement.style.transform = `rotate(${totalRotation}deg)`;

                setTimeout(() => {
                    const resultMessage = prizeConfig[selectedIndex].content;
                    showResult(resultMessage); // Hiển thị modal trúng thưởng
                    isSpinning = false;
                    spinBtn.disabled = false;

                    if (data.lucky !== undefined) {
                        const luckyStatus = document.getElementById('lucky-status');
                        if (luckyStatus) {
                            const bar = luckyStatus.querySelector('.lucky-progress-bar');
                            const text = luckyStatus.querySelector('.lucky-progress-text');
                            const luckyPercent = Math.min(Math.max(data.lucky, 0), 100);
                            if (bar) bar.style.width = `${luckyPercent}%`;
                            if (text) text.textContent = `${luckyPercent}%`;
                        }
                    }

                    if (data.free_spins_left !== undefined) {
                        const freeSpinsInfo = document.getElementById('free-spins-info');
                        if (freeSpinsInfo) {
                            freeSpinsInfo.innerHTML = `LƯỢT QUAY MIỄN PHÍ CÒN LẠI <strong style="color:yellow">${data.free_spins_left}</strong>`;
                        }
                    }

                    if (data.new_balance !== undefined) {
                        const balanceElement = document.querySelector('.user-balance'); // Cần có element này trong layout của bạn
                        if (balanceElement) {
                            balanceElement.textContent = new Intl.NumberFormat('vi-VN').format(data.new_balance);
                        }
                    }

                    setTimeout(() => {
                        wheelElement.style.transition = 'none';
                        wheelElement.style.transform = 'rotate(0deg)';
                        setTimeout(() => {
                            wheelElement.style.transition = 'transform 5s cubic-bezier(0.2, 0.8, 0.3, 1)';
                        }, 50);
                    }, 1000);

                    fetch('{{ route("lucky.history", $wheel->slug) }}')
                        .then(response => response.text())
                        .then(html => {
                            const historySection = document.getElementById('history-section');
                            if (historySection) {
                                const newHistoryTableContainer = new DOMParser().parseFromString(html, "text/html").querySelector('.history-table-container');
                                const oldHistoryTableContainer = historySection.querySelector('.history-table-container');
                                const noHistoryDiv = historySection.querySelector('.no-history');

                                if (newHistoryTableContainer) {
                                   if(oldHistoryTableContainer) {
                                       oldHistoryTableContainer.innerHTML = newHistoryTableContainer.innerHTML;
                                   } else if(noHistoryDiv) {
                                       noHistoryDiv.replaceWith(newHistoryTableContainer);
                                   } else {
                                       historySection.appendChild(newHistoryTableContainer);
                                   }
                                } else if (noHistoryDiv && !newHistoryTableContainer) {
                                    // Giữ nguyên "Chưa có dữ liệu" nếu API trả về không có bảng mới
                                } else if (oldHistoryTableContainer && !newHistoryTableContainer) {
                                    // Nếu có bảng cũ mà không có bảng mới (ví dụ API lỗi và trả về HTML rỗng)
                                    // thì tạo lại div "Chưa có dữ liệu"
                                    const newNoHistory = document.createElement('div');
                                    newNoHistory.className = 'no-history';
                                    newNoHistory.innerHTML = '<p>Chưa Có Dữ Liệu</p>';
                                    oldHistoryTableContainer.replaceWith(newNoHistory);
                                }
                                // Re-init clipboard cho các nút copy mới nếu có trong lịch sử
                                const newCopyButtons = historySection.querySelectorAll('.copy-btn');
                                if (newCopyButtons.length > 0) {
                                    new ClipboardJS('.copy-btn').on('success', function(e) {
                                        const originalText = e.trigger.innerHTML;
                                        e.trigger.innerHTML = '<i class="fas fa-check"></i>';
                                        setTimeout(function() {
                                            e.trigger.innerHTML = originalText;
                                        }, 2000);
                                        e.clearSelection();
                                    });
                                }
                            }
                        })
                        .catch(() => {
                            console.error('Không thể tải lịch sử mới');
                        });
                }, 5000);
            })
            .catch(error => {
                // console.error('Error:', error);
                showGeneralMessage('Lỗi Hệ Thống', 'Vui Lòng Thử Lại', 'fas fa-server');
                isSpinning = false;
                spinBtn.disabled = false;
            });
        }

        // Hàm hiển thị Result Modal (trúng thưởng)
        function showResult(prize) {
            if (resultRewardText) resultRewardText.textContent = prize;
            if (resultModal) resultModal.classList.add('active');
        }

        // Hàm đóng Result Modal (trúng thưởng)
        function closeResultModal() {
            if (resultModal) resultModal.classList.remove('active');
        }

        // Event listeners cho Result Modal (trúng thưởng)
        if (resultModalClose) resultModalClose.addEventListener('click', closeResultModal);
        if (resultContinueBtn) resultContinueBtn.addEventListener('click', closeResultModal);
    });
</script>
@endpush
