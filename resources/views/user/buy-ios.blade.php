@extends('layouts.user.app')
@section('title', 'Mua Key IOS')
@section('content')
<x-hero-header title="Mua Key IOS" description="" />
<div class="container">
    <!-- Hiển thị thông báo lỗi hoặc thành công -->
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@elseif (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        
        <!-- Thêm nút sao chép key -->
        <button class="copy-btn" data-clipboard-text="{{ session('key_value') }}">
            <i class="fas fa-copy"></i>
        </button>
    </div>
@endif
<!-- Form mua key -->
    <div class="service__form">
        <h3 class="service__form-title">THÔNG TIN ĐƠN HÀNG</h3>
        <form action="{{ route('buy-ios.purchase') }}" method="POST">
            @csrf

            <div class="service__form-row">
                <div class="service__form-group">
                    <label for="game"><i class="fas fa-gamepad"></i> Chọn Game</label>
                    <select name="game" class="service__form-control" id="game" required>
                        <option value="VNG">PLAY TOGETHER VNG</option>
                    </select>
                </div>

                <div class="service__form-group">
                    <label for="key_duration"><i class="fas fa-hourglass-end"></i> Hạn Sử Dụng</label>
                    <select name="package" class="service__form-control" id="package" required>
    <option value="1_month" data-price="300000">1 Tháng - 300K</option>
    <option value="2_month" data-price="500000">2 Tháng - 500K</option>
    <option value="vinhvien" data-price="1000000">Vĩnh Viễn - 1.000.000</option>
</select>

                </div>
            </div>

            <div class="form-group mb-4">
                <h3 class="text-center font-weight-bold">TỔNG TIỀN: <span style="color:red" id="total_price">0</span> VNĐ</h3>
            </div>
            <div class="service__form-actions">
                @if (Auth::check())
                    <button type="submit" class="service__btn service__btn--primary service__btn--block">
                        <i class="fas fa-check-circle"> </i> THANH TOÁN</button>
                @else
                    <a href="{{ route('login') }}"
                        class="service__btn service__btn--primary service__btn--block">
                        <i class="fas fa-sign-in-alt"> </i> ĐĂNG NHẬP
                    </a>
                @endif
            </div>
        </form>
    </div>
    <!-- Lịch sử mua key -->
    <div class="info-content">
        <div class="deposit-history">
            <div class="history-header">LỊCH SỬ MUA KEY</div>
            <div class="history-table-container">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>LINK TẢI</th>
                            <th>GAME</th>
                            <th>KEY VIP</th>
                            <th>THỜI GIAN</th>
                            <th>HẾT HẠN</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="6" class="text-center">Không có dữ liệu</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- Clipboard.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
<script>
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

        // Close alert buttons
        const alertCloseButtons = document.querySelectorAll('.service__alert-close');
        alertCloseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const alertBox = this.closest('.service__alert');
                if (alertBox) {
                    alertBox.remove();
                }
            });
        });

        // Price calculation logic
        const keyDurationSelect = document.getElementById('package');
        const totalPriceElement = document.getElementById('total_price');

        if (keyDurationSelect && totalPriceElement) {
            const updatePrice = () => {
                const selectedOption = keyDurationSelect.options[keyDurationSelect.selectedIndex];
                const price = selectedOption ? selectedOption.getAttribute('data-price') : 0;
                totalPriceElement.innerText = new Intl.NumberFormat().format(price);
            };

            keyDurationSelect.addEventListener('change', updatePrice);
            updatePrice(); // Set initial price
        }
    });
</script>
@endsection
