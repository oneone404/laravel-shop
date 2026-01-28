@extends('layouts.user.app')
@section('title', 'CHI TIẾT TÀI KHOẢN #' . $account->id)
@section('content')
<style>
.modal{
    padding: 5px;
}
.modal__discount{
    margin-bottom: 10px;
}
.modal__discount-message {
    padding: 10px 10px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 500;
    display: none;
    transition: all 0.3s ease;
    text-align: center;
}

.modal__discount-message.success {
    background-color: #e6ffed;
    color: #2e7d32;
    border: 1px solid #81c784;
    display: block;
}

.modal__discount-message.error {
    background-color: #ffebee;
    color: #c62828;
    border: 1px solid #ef9a9a;
    display: block;
}

.container{
    padding: 0px;
}
.detail{
    padding: 0px;
}
.detail__purchased {
    background: linear-gradient(135deg, #fff0f0, #ffe5e5);
    border: 1.8px solid rgba(220, 38, 38, 0.25);
    border-radius: 14px;
    padding: 20px 15px;
    text-align: center;
    margin: 20px auto;
    max-width: 420px;
    box-shadow: 0 4px 14px rgba(255, 0, 0, 0.08);
    animation: fadeInUp 0.35s ease;
}

.detail__purchased-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #b91c1c;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.detail__purchased-title span {
    background: linear-gradient(90deg, #000000ff, #b91c1c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.detail__purchased-subtitle {
    margin-top: 6px;
    font-size: 0.95rem;
    color: #6b7280;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (min-width: 768px) {
    .detail__purchased {
        max-width: 520px;
        padding: 28px 20px;
        border-radius: 18px;
    }

    .detail__purchased-title {
        font-size: 1.8rem;
        letter-spacing: 1px;
    }

    .detail__purchased-subtitle {
        font-size: 1.05rem;
    }
}

.sl-navigation button {
    background: none !important;
    border: none;
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
}

.sl-navigation button:hover {
    color: #38bdf8;
    transform: scale(1.15);
}

.sl-image img {
    border-radius: 8px !important;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);
    transition: border-radius 0.3s ease;
}

.sl-overlay {
    background: rgba(0, 0, 0, 0.9) !important;
}

@media (min-width: 768px) {
    .sl-image img {
        border-radius: 16px !important;
    }
}

.detail__images-link {
    border-radius: 8px !important;
    object-fit: cover;
    transition: none !important;
    box-shadow: none !important;
}

.detail__images-link:hover {
    transform: none !important;
    box-shadow: none !important;
}

.detail__images-item {
    box-shadow: none !important;
    border: 1.8px solid rgba(100, 110, 140, 0.22);
    border-radius: 10px;
}

.detail__images-link:hover .detail__images-item {
    transform: none !important;
    box-shadow: none !important;
}

/* Price Section */
.modal__price-section {
    padding: 20px 15px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 12px;
    margin: 15px 0;
    border: 1.5px solid rgba(100, 116, 139, 0.15);
}

.modal__price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px dashed rgba(100, 116, 139, 0.2);
}

.modal__price-row:last-child {
    border-bottom: none;
}

.modal__price-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
}

.modal__price-value {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
}

.modal__discount-row {
    background: rgba(34, 197, 94, 0.08);
    border-radius: 6px;
    padding: 10px 12px;
    margin: 5px 0;
    border-bottom: none;
    animation: slideIn 0.3s ease;
}

.modal__discount-row .modal__price-label {
    color: #16a34a;
}

.modal__discount-value {
    color: #16a34a !important;
    font-size: 16px !important;
}

.modal__total-row {
    padding-top: 15px;
    margin-top: 8px;
    border-top: 2px solid rgba(14, 62, 218, 0.2) !important;
    border-bottom: none;
}

.modal__total-row .modal__price-label {
    font-size: 16px;
    color: #0E3EDA;
    font-weight: 700;
}

.modal__total-value {
    font-size: 20px !important;
    color: #0E3EDA !important;
    font-weight: 700 !important;
}

.modal__input {
    flex: 1;
    padding: 12px 15px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.modal__input:focus {
    outline: none;
    border-color: #0E3EDA;
    box-shadow: 0 0 0 3px rgba(14, 62, 218, 0.1);
}

.modal__btn--check {
    padding: 12px 25px;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal__btn--check:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(14, 62, 218, 0.3);
}

.modal__btn--wallet {
    width: 100%;
    padding: 10px;
    background: linear-gradient(135deg, #0E3EDA, #0a2fb0);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    text-decoration: none;
    margin-bottom: 10px;
}

.modal__btn--wallet:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(14, 62, 218, 0.4);
    color: white;
}

@media (min-width: 768px) {
    .modal__price-section {
        padding: 25px 20px;
    }

    .modal__price-label {
        font-size: 15px;
    }

    .modal__price-value {
        font-size: 16px;
    }

    .modal__discount-value {
        font-size: 17px !important;
    }

    .modal__total-row .modal__price-label {
        font-size: 17px;
    }

    .modal__total-value {
        font-size: 22px !important;
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.modal__footer_random {
    padding: 20px;
    background: #f8f8f8;
    text-align: center;
}
.detail__content {
    box-shadow: none !important;
    border: 2px solid #e2e8f0;
}

</style>

<x-hero-header title="TÀI KHOẢN #{{ $account->id }}" description="" />

<section class="detail">
    <div class="container">
        <div class="detail__content">
            <!-- Action Buttons -->
            <div class="detail__actions">
                @if ($account->status === 'available')
                    <button class="detail__btn detail__btn--primary" onclick="buyAccount({{ $account->id }})">
                        <i class="fas fa-shopping-cart"></i>MUA NGAY
                    </button>
                @else
                    <div class="detail__purchased">
                        <h2 class="detail__purchased-title">
                            TÀI KHOẢN <span>ĐÃ BÁN</span>
                        </h2>
                        <p class="detail__purchased-subtitle">Tài Khoản Đã Được Bán, Vui Lòng Chọn Tài Khoản Khác</p>
                    </div>
                @endif
            </div>

            <!-- Account Images -->
            <div class="detail__images">
                <h2 class="detail__images-title">HÌNH ẢNH CHI TIẾT <span class="text-danger">#{{ $account->id }}</span></h2>
                <div class="detail__images-list">
                    @foreach ($images as $image)
                        <a href="{{ $image }}" title="Xem Ảnh Lớn" class="detail__images-link">
                            <img src="{{ $image }}" class="detail__images-item">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Purchase Modal -->
<div id="purchaseModal" class="modal">
    <div class="modal__content">
        <div class="modal__header">
            <h2 class="modal__title">MUA TÀI KHOẢN #{{ $account->id }}</h2>
            <button class="modal__close" onclick="closePurchaseModal()">&times;</button>
        </div>

        <!-- Thông tin giá -->
        <div class="modal__price-section">
            <div class="modal__price-row">
                <span class="modal__price-label">Loại Tài Khoản</span>
                <span class="modal__price-value" id="originalPrice">{{ number_format($account->price, 0, '.', '.') }} VND</span>
            </div>
            <div class="modal__price-row modal__discount-row" id="discountRow" style="display: none;">
                <span class="modal__price-label">Giảm Giá</span>
                <span class="modal__price-value modal__discount-value" id="discountAmount">0 VND</span>
            </div>
            <div class="modal__price-row modal__total-row">
                <span class="modal__price-label">Tổng Thanh Toán</span>
                <span class="modal__price-value modal__total-value" id="totalPrice">{{ number_format($account->price, 0, '.', '.') }} VND</span>
            </div>
        </div>

        <!-- Mã giảm giá -->
        <div class="modal__discount">
            <div class="modal__footer">
                <input type="text" id="discount-code" class="modal__input" placeholder="Nhập Mã Giảm Giá (Nếu Có)">
                <button class="modal__btn modal__btn--check" onclick="checkDiscountCode('account')">Kiểm Tra</button>
            </div>
            <div id="discount-message" class="modal__discount-message"></div>
        </div>

        <div class="modal__body">
            <div id="loadingSpinner" style="display:none">
                <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #0E3EDA; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 20px auto;"></div>
            </div>
            <div id="purchaseError" class="modal__discount-message error" style="display:none; margin-bottom: 15px;"></div>
        </div>

        <!-- Nút xác nhận -->
        <div class="modal__footer_random" style="flex-direction: column; gap: 10px;">
            @auth
                <button class="modal__btn modal__btn--wallet" onclick="submitPurchase()" style="background: linear-gradient(135deg, #8B5CF6, #6366F1);">
                    <i class="fas fa-wallet"></i> THANH TOÁN BẰNG SỐ DƯ ({{ number_format(auth()->user()->balance) }} VND)
                </button>
            @endauth
            <button id="playDirectPayButton" class="modal__btn modal__btn--wallet" style="background: linear-gradient(135deg, #10b981, #059669);" onclick="submitPlayDirectPayment()">
                <i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY
            </button>
        </div>
    </div>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>

@push('scripts')
<script>
let originalPrice = {{ $account->price }};
let discountAmount = 0;
let discountCode = '';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize SimpleLightbox
    const lightbox = new SimpleLightbox('.detail__images-link', {
        captionPosition: 'bottom',
        captionsData: 'alt',
        closeText: '×',
        navText: [
            '<i class="fas fa-chevron-left"></i>',
            '<i class="fas fa-chevron-right"></i>'
        ],
        animationSpeed: 250,
        enableKeyboard: true,
        scaleImageToRatio: true,
        disableRightClick: true
    });

    // Close modal when clicking outside
    const modal = document.getElementById('purchaseModal');
    if (modal) {
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closePurchaseModal();
            }
        });
    }
});

function buyAccount(accountId) {
    const modal = document.getElementById('purchaseModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        resetPriceDisplay();
    }
}

function resetPriceDisplay() {
    discountAmount = 0;
    discountCode = '';

    document.getElementById('originalPrice').textContent = formatPrice(originalPrice);
    document.getElementById('totalPrice').textContent = formatPrice(originalPrice);
    document.getElementById('discountRow').style.display = 'none';
    document.getElementById('discount-code').value = '';

    const message = document.getElementById('discount-message');
    message.className = 'modal__discount-message';
    message.textContent = '';
}

function checkDiscountCode(context) {
    const code = document.getElementById('discount-code').value.trim();
    const message = document.getElementById('discount-message');

    if (!code) {
        message.className = 'modal__discount-message error';
        message.innerHTML = '<i class="fas fa-exclamation-circle"></i> Vui Lòng Nhập Mã Giảm Giá';
        return;
    }

    // Gọi API validate code
    fetch('{{ route("discount.validate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            code: code,
            context: context,
            item_id: {{ $account->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tính số tiền giảm
            if (data.discount_type === 'percentage') {
                discountAmount = Math.floor((originalPrice * data.discount_value) / 100);
            } else {
                discountAmount = data.discount_value;
            }

            // Đảm bảo không giảm quá giá gốc
            discountAmount = Math.min(discountAmount, originalPrice);
            discountCode = code;

            document.getElementById('discountAmount').textContent = '- ' + formatPrice(discountAmount);
            document.getElementById('discountRow').style.display = 'flex';

            const finalPrice = originalPrice - discountAmount;
            document.getElementById('totalPrice').textContent = formatPrice(finalPrice);

            message.className = 'modal__discount-message success';
            message.innerHTML = '<i class="fas fa-check-circle"></i> Thành Công! Giảm ' + formatPrice(discountAmount);
        } else {
            discountAmount = 0;
            discountCode = '';
            document.getElementById('discountRow').style.display = 'none';
            document.getElementById('totalPrice').textContent = formatPrice(originalPrice);

            message.className = 'modal__discount-message error';
            message.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (data.message || 'Mã Giảm Giá Không Hợp Lệ');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        message.className = 'modal__discount-message error';
        message.textContent = 'Lỗi! Vui Lòng Thử Lại Sau';
    });
}

function submitPurchase() {
    const accountId = {{ $account->id }};
    const spinner = document.getElementById('loadingSpinner');
    const errorBox = document.getElementById('purchaseError');

    errorBox.style.display = 'none';
    spinner.style.display = 'block';

    fetch('{{ route("account.purchase", ":id") }}'.replace(':id', accountId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            discount_code: discountCode
        })
    })
    .then(async r => {
        const data = await r.json().catch(() => null);
        if (!r.ok) {
            throw new Error(data?.message || 'Đã xảy ra lỗi trên hệ thống');
        }
        return data;
    })
    .then(data => {
        spinner.style.display = 'none';
        if (data && data.success && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            errorBox.textContent = (data && data.message) ? data.message : 'Bạn Đang Có Nhiều Đơn Hàng Chưa Thanh Toán';
            errorBox.style.display = 'block';
        }
    })
    .catch(error => {
        spinner.style.display = 'none';
        errorBox.textContent = error.message;
        errorBox.style.display = 'block';
    });
}

function closePurchaseModal() {
    const modal = document.getElementById('purchaseModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        resetPriceDisplay();
    }
}

// ========== Direct Payment for Play Account ==========
function submitPlayDirectPayment() {
    const accountId = {{ $account->id }};
    const directBtn = document.getElementById('playDirectPayButton');
    const spinner = document.getElementById('loadingSpinner');
    const errorBox = document.getElementById('purchaseError');
    
    errorBox.style.display = 'none';
    spinner.style.display = 'block';
    directBtn.disabled = true;
    directBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG TẠO ĐƠN...';

    if (!accountId) {
        spinner.style.display = 'none';
        errorBox.textContent = 'Lỗi: Không xác định được mã tài khoản.';
        errorBox.style.display = 'block';
        directBtn.disabled = false;
        directBtn.innerHTML = '<i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY';
        return;
    }

    fetch('{{ route("direct-payment.create-account", ":id") }}'.replace(':id', accountId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({})
    })
    .then(async r => {
        const data = await r.json().catch(() => null);
        if (!r.ok) {
            throw new Error(data?.message || 'Đã xảy ra lỗi trên hệ thống (Mã: ' + r.status + ')');
        }
        return data;
    })
    .then(data => {
        spinner.style.display = 'none';
        if (data && data.success && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            errorBox.textContent = (data && data.message) ? data.message : 'Bạn Đang Có Nhiều Đơn Hàng Chưa Thanh Toán';
            errorBox.style.display = 'block';
            directBtn.disabled = false;
            directBtn.innerHTML = '<i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY';
        }
    })
    .catch((err) => {
        spinner.style.display = 'none';
        errorBox.textContent = err.message;
        errorBox.style.display = 'block';
        directBtn.disabled = false;
        directBtn.innerHTML = '<i class="fas fa-qrcode"></i> QUÉT MÃ THANH TOÁN NGAY';
    });
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + ' VND';
}
</script>
@endpush
@endsection
