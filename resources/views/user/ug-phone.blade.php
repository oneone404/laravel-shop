@extends('layouts.user.app')

@section('title', 'Mua UgPhone')

@section('content')
<x-hero-header title="Mua UgPhone" description="" />
<div class="container py-3">
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if(count($items) > 0)
    <form method="POST" action="{{ route('ug-phone.purchase') }}">
        @csrf

        <!-- Dạng grid 2 cột kể cả mobile -->
        <div class="d-flex flex-wrap justify-content-center gap-3">
            @foreach($items as $item)
<label style="flex: 0 0 calc(50% - 1rem); max-width: calc(50% - 1rem);">
    <input type="radio" name="ug_phone_id" value="{{ $item->id }}"
        data-price="{{ $item->price }}"
        data-code="{{ $item->code }}"
        data-server="{{ $item->sever }}"
        data-hansudung="{{ $item->hansudung }}"
        data-cauhinh="{{ $item->cauhinh }}"
        style="display:none;">

<div class="card selectable-card shadow-sm border border-light p-3 d-flex">
    <!-- Icon góc trái, bo tròn -->
    <img src="{{ asset('images/ug.png') }}" alt="Icon" 
         class="device-icon me-3 mt-2" 
         style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">

    <!-- Nội dung chi tiết -->
    <div class="flex-grow-1 d-flex flex-column justify-content-center text-center">
        <div class="w-100">
            <div class="d-flex justify-content-between small py-1">
                <span><i class="fas fa-server me-1"></i><strong> Server:</strong></span>
                <span>{{ $item->sever }}</span>
            </div>
            <div class="d-flex justify-content-between small py-1">
                <span><i class="fas fa-clock me-1"></i><strong> Hạn Sử Dụng:</strong></span>
                <span>{{ $item->hansudung }}</span>
            </div>
            <div class="d-flex justify-content-between small py-1">
                <span><i class="fas fa-tag me-1"></i><strong> Giá:</strong></span>
                <span class="text-danger">{{ number_format($item->price) }} </span>VND
            </div>
            <div class="d-flex justify-content-between small py-1">
                <span><i class="fas fa-microchip me-1"></i><strong> Cấu Hình:</strong></span>
                <span>{{ $item->cauhinh }}</span>
            </div>
        </div>
    </div>
</div>

<br>

</label>
@endforeach

        </div>

        <!-- Tổng tiền -->
        <div class="text-center mt-4">
            <h5 class="fw-bold">TỔNG TIỀN: <span class="text-danger" id="totalPrice">0</span> VNĐ</h5>
        </div>
        <!-- Nút thanh toán -->
        <div class="service__form-actions mt-3 text-center">
            @if (Auth::check())
                <button type="submit" class="service__btn service__btn--primary service__btn--block">
                    <i class="fas fa-check-circle"></i> THANH TOÁN
                </button>
            @else
                <a href="{{ route('login') }}" class="service__btn service__btn--primary service__btn--block">
                    <i class="fas fa-sign-in-alt"></i> ĐĂNG NHẬP
                </a>
            @endif
        </div>
    </form>
    @else
        <p class="text-center text-muted mt-5">Hiện Tại Không Còn UgPhone</p>
    @endif
</div>
<!-- Lịch sử mua key -->
<div class="info-content">
    <div class="deposit-history">
        <div class="history-header">LỊCH SỬ MUA UGPHONE</div>
        <div class="history-table-container">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>GIÁ TIỀN</th>
                        <th>CODE REEDEM</th>
                        <th>HẠN SỬ DỤNG</th>
                        <th>THỜI GIAN</th>
                    </tr>
                </thead>
                <tbody>
    @if($histories->isEmpty())
        <tr>
            <td colspan="4" class="text-center text-muted">Không Có Dữ Liệu</td>
        </tr>
    @else
        @foreach($histories as $history)
            <tr>
                <td>{{ number_format($history->price) }} VND</td>
                <td>{{ $history->code }}</td> <!-- hoặc mã riêng nếu có -->
                <td>{{ $history->hansudung }}</td>
                <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
    @endif
</tbody>

            </table>
        </div>
    </div>
</div>
<style>
.history-table-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.history-table {
    white-space: nowrap;
    min-width: 700px; /* hoặc lớn hơn nếu bảng rộng hơn */
}

.history-table th,
.history-table td {
    white-space: nowrap;
}
.device-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.selectable-card {
    
    transition: all 0.3s ease;
    border-radius: 16px;
    background-color: #fff;
    cursor: pointer;
    border: 2px solid transparent;
    display: flex; /* mới thêm */
    align-items: start; /* mới thêm */
    gap: 1rem; /* tạo khoảng cách giữa icon và nội dung */
}

.selectable-card:hover {
    transform: scale(1.015);
    border-color: #007bff;
    box-shadow: 0 6px 16px rgba(0, 123, 255, 0.1);
}

input[type="radio"]:checked + .card {
    border-color: #0d6efd;
    box-shadow: 0 6px 18px rgba(13, 110, 253, 0.2);
}

@media (max-width: 576px) {
    .device-icon {
        width: 40px;
        height: 40px;
    }

    .selectable-card {
        flex-direction: row;
        padding: 1rem !important;
    }

    .selectable-card .fw-bold {
        font-size: 1rem;
    }

    .selectable-card .small {
        font-size: 1rem;
        line-height: 1.2;
        text-align: justify; 
    }

    .selectable-card .d-flex {
        gap: 0.25rem !important;
    }
}

</style>


<script>
    const radios = document.querySelectorAll('input[name="ug_phone_id"]');
    const totalPriceEl = document.getElementById('totalPrice');
    const deviceInfo = document.getElementById('deviceInfo');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            const price = parseInt(this.dataset.price);
            const code = this.dataset.code;
            const server = this.dataset.server;
            const hansudung = this.dataset.hansudung;
            const cauhinh = this.dataset.cauhinh;

            totalPriceEl.textContent = price.toLocaleString();
        });
    });
</script>
@endsection
