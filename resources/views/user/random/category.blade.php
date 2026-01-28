
@extends('layouts.user.app')
@section('title', $category->name)
@section('content')
<style>
.container.no-padding {
    padding: 0;
}
/* Grid 2 cột mobile-first */
.account-grid-new {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    padding: 0;
}

/* Thẻ account */
.account-item {
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    overflow: hidden;
    transition: transform 0.2s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.account-item:hover {
    transform: translateY(-2px);
}

/* Ảnh account */
.account-image-wrapper {
    position: relative;
    width: 100%;
}

.account-image {
    width: 100%;
    display: block;
}

/* Mã số */
.account-code {
    position: absolute;
    top: 6px;
    left: 6px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 4px;
}

/* Badge thông tin */
.account-info-wrapper {
    padding: 10px 5px 5px 5px;
}

.account-badges {
    display: flex;
    flex-direction: column;
    gap: 4px;
    align-items: center;
    margin-top: 10px; /* Đẩy thấp xuống chút */
}

.account-badge {
    background: #f39200;
    color: #fff;
    padding: 2px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
    max-width: 80%;
    width: 80%;
    text-align: center;
    box-sizing: border-box;
}

@media (max-width: 400px) {
    .account-badge {
        font-size: 8px;
        padding: 1px 6px;
    }
}



/* Giá và nút */
.account-action-wrapper {
    padding: 5px 8px 10px 8px;
    text-align: center;
}

.account-price {
    font-weight: bold;
    font-size: 13px;
    margin-bottom: 6px;
}

.account-button {
    background-color: #ffffff;
    display: block;
    width: calc(100% - 4px);
    margin: 0 auto;
    border: 1.5px solid #0E3EDA;
    border-radius: 18px;
    padding: 5px 0;
    font-weight: 600;
    font-size: 12px;
    color: #0E3EDA;
    text-decoration: none;
    transition: 0.3s;
    margin-bottom: 6px;
}

.account-button:hover {
    background: #0E3EDA;
    color: #fff;
}
/* Responsive: 4 cột trên PC */
@media (min-width: 768px) {
    .account-grid-new {
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .account-badge {
        font-size: 11px;
        padding: 5px 12px;
    }

    .account-price {
        font-size: 14px;
    }

    .account-button {
        font-size: 13px;
        padding: 7px 0;
    }
}
.image-modal {
    display: none; 
    position: fixed; 
    z-index: 9999; 
    top: 0; left: 0; width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.9);
    justify-content: center; 
    align-items: center;
}

.image-modal.active {
    display: flex;
}

.image-modal-content {
    width: 100%;
    max-width: 600px;   /* Tuyệt đối không vượt quá 600px trên PC */
    max-height: 90vh;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.5);
    position: relative;
    object-fit: contain;
}
</style>
    <!-- Hero Section -->
    <x-hero-header title="{{ $category->name }}" description="" />

    <!-- Account List Section -->
    <section class="account-section">
    <div class="container no-padding">
        <div class="account-grid-new">
            @forelse($accounts as $account)
                <div class="account-item">
<div class="account-image-wrapper">
    <img src="{{ $account->thumbnail }}" alt="Account Preview" class="account-image" data-id="{{ $account->id }}">
    <div class="account-code">MS: {{ $account->id }}</div>
    <div class="eye-icon" 
        onclick="openImageModal('{{ $account->thumb }}')"
        style="
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.4);
            border-radius: 999px;
            padding: 1px 10px;
            cursor: pointer;
        "
    >
        <i class="fas fa-eye" style="color: white; font-size: 16px;"></i>
    </div>
</div>



                    <div class="account-info-wrapper">
                        <div class="account-badges">
                            <div class="account-badge">★ PLAY TOGETHER VNG</div>
                            <div class="account-badge">★ TRẮNG THÔNG TIN</div>
                        </div>
                    </div>

                    <div class="account-action-wrapper">
                        <div class="account-price">
                            {{ number_format($account->price, 0, '.', '.') }} VND
                        </div>
@auth
    <button 
        class="account-button" 
        data-id="{{ $account->id }}" 
        data-price="{{ $account->price }}"
        onclick="openQuickPurchaseModal(this)">
        MUA NGAY
    </button>
@else
    <a href="/login" class="account-button">MUA NGAY</a>
@endauth


                    </div>
                </div>
            @empty
                <div class="no-data">
                    <p class="no-data-text">Hiện Tại Không Còn Tài Khoản</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
<div id="imageModal" class="image-modal">
    <div class="image-modal-content">
        <img id="modalImage" src="" alt="Preview">
    </div>
</div>
<div id="quickPurchaseModal" class="modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(2px);">
    <div class="modal__content" style="background-color: #ffffff; margin: 10% auto; padding: 0; border: none; width: 100%; max-width: 400px; border-radius: 16px; box-shadow: 0 8px 16px rgba(0,0,0,0.3); overflow: hidden; animation: fadeIn 0.3s ease;">
        <div class="modal__header" style="background-color: #0E3EDA; color: white; padding: 20px; position: relative;">
            <h2 id="modalTitle" style="margin: 0; font-size: 18px; text-align: center; color: white;">
    XÁC NHẬN THANH TOÁN
</h2>
            <button onclick="closeQuickPurchaseModal()" style="position: absolute; top: 10px; right: 15px; background: transparent; border: none; font-size: 26px; color: white; cursor: pointer;">&times;</button>
        </div>

        <div class="modal__body" style="padding: 40px 20px; text-align: center; font-family: Arial, sans-serif;">
            <div id="totalPrice" style="font-size: 32px; font-weight: bold; color: #0E3EDA;">0 VND</div>

            <div id="loadingSpinner" style="display: none; margin-top: 20px;">
                <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #0E3EDA; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
            </div>

            <div id="balanceError" style="margin-top: 15px; color: #D8000C; background-color: #FFD2D2; border: 1px solid #D8000C; border-radius: 8px; padding: 12px 20px; font-weight: bold; font-size: 16px; display: none; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                Số Dư Không Đủ
            </div>

<div id="successMessage" style="
    margin-top: 15px; 
    color: #155724; 
    background-color: #d4edda; 
    border: 1px solid #28a745; 
    border-radius: 8px; 
    padding: 12px 20px; 
    font-weight: bold; 
    font-size: 16px; 
    display: none; 
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
">Thanh Toán Thành Công
</div>


        </div>

        <div class="modal__footer" style="padding: 20px; background-color: #f8f8f8; text-align: center;">
            <button id="purchaseButton" onclick="submitQuickPurchase()" style="background-color: #0E3EDA; color: white; font-size: 16px; padding: 14px 50px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; transition: background-color 0.3s ease;">
                <i class="fas fa-check-circle"></i>THANH TOÁN
            </button>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(-20px);} to { opacity: 1; transform: translateY(0); } }
@keyframes spin { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg); } }
</style>

<script>
let currentAccountId = null;
let currentPrice = 0;
let currentBalance = 0;

function openQuickPurchaseModal(button) {
    const modal = document.getElementById('quickPurchaseModal');
    const priceElement = document.getElementById('totalPrice');
    const balanceError = document.getElementById('balanceError');
    const successMessage = document.getElementById('successMessage');
    const spinner = document.getElementById('loadingSpinner');
    const purchaseBtn = document.getElementById('purchaseButton');

    // Lấy chính xác id và price từ button
    currentAccountId = parseInt(button.dataset.id);
    currentPrice = parseInt(button.dataset.price);
    document.getElementById('modalTitle').innerText = 'XÁC NHẬN THANH TOÁN #' + currentAccountId;
    priceElement.innerText = parseInt(currentPrice).toLocaleString('vi-VN') + ' VND';

    balanceError.style.display = 'none';
    successMessage.style.display = 'none';
    spinner.style.display = 'none';
    purchaseBtn.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i>THANH TOÁN';
    purchaseBtn.disabled = false;
    purchaseBtn.onclick = submitQuickPurchase;

    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeQuickPurchaseModal() {
    const modal = document.getElementById('quickPurchaseModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
function submitQuickPurchase() {
    const balanceError = document.getElementById('balanceError');
    const successMessage = document.getElementById('successMessage');
    const spinner = document.getElementById('loadingSpinner');
    const button = document.getElementById('purchaseButton');

    balanceError.style.display = 'none';
    successMessage.style.display = 'none';
    spinner.style.display = 'block';
    button.disabled = true;

    // Bước 1: Lấy balance realtime
    fetch('/user/balance')
        .then(response => response.json())
        .then(data => {
            spinner.style.display = 'none';
            
            const latestBalance = data.balance ?? 0;

            if (latestBalance < currentPrice) {
                balanceError.style.display = 'block';
                button.disabled = false;
            } else {
                // Bước 2: Thực hiện giao dịch thực tế
                processPurchase();
            }
        })
        .catch(error => {
            console.error('Lỗi khi kiểm tra số dư:', error);
            spinner.style.display = 'none';
            button.disabled = false;
        });
}

function processPurchase() {
    const spinner = document.getElementById('loadingSpinner');
    const successMessage = document.getElementById('successMessage');
    const button = document.getElementById('purchaseButton');

    spinner.style.display = 'block';
    fetch(`/random/account/${currentAccountId}/purchase`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        spinner.style.display = 'none';
        if (data.success) {
            successMessage.style.display = 'block';
            button.innerHTML = '<i class="fas fa-check-circle"></i> OK';
            button.disabled = false;
            button.onclick = redirectAfterSuccess;
        } else {
            console.error('Giao dịch thất bại:', data.message);
            alert('Lỗi khi mua acc: ' + data.message);
            button.disabled = false;
            button.innerText = 'THANH TOÁN';
        }
    })
    .catch(error => {
        console.error('Lỗi khi thực hiện thanh toán:', error);
        spinner.style.display = 'none';
        button.disabled = false;
        button.innerText = 'THANH TOÁN';
    });
}

function redirectAfterSuccess() {
    window.location.href = '/profile/purchased-accounts';
}
// Đóng modal khi click bên ngoài phần modal content
document.getElementById('quickPurchaseModal').addEventListener('click', function(e) {
    const content = document.querySelector('.modal__content');
    if (!content.contains(e.target)) {
        closeQuickPurchaseModal();
    }
});

document.querySelectorAll('.account-image').forEach(function(img) {
    img.addEventListener('click', function() {
        const modal = document.getElementById('imageModal');
        modal.style.display = "flex";
        document.getElementById('modalImage').src = this.src;
    });
});
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    modal.style.display = "flex";
    document.getElementById('modalImage').src = src;
}

// Xử lý click ra ngoài ảnh (click vào nền đen)
document.getElementById('imageModal').addEventListener('click', function(e) {
    // Nếu click không phải vào ảnh thì tắt modal
    if (e.target === this) {
        this.style.display = "none";
    }
});

</script>
@endsection
