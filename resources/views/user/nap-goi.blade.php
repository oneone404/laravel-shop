@extends('layouts.user.app')
@section('title', 'Nạp Gói Game')

@section('content')

<style>
/* Box hiển thị thông tin đăng nhập */
.login-info-box {
    border: 3px solid #FF9800;
    border-radius: 8px;
    padding: 10px;
    font-size: 1.2rem;
    color: #333;
    width: 100%;
    max-width: 400px;
    margin: 0 auto 10px auto;
    display: block;
}


.login-info-row {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    font-size: 1.3rem; /* tăng cỡ chữ */
    font-weight: bold;
    gap: 0;
    min-width: 0;
}

.info-left, .info-right {
    flex: 1;
    min-width: 0;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 6px 10px;
    font-size: inherit;
    border: 2px solid transparent;
    border-radius: 4px;
}

.info-left {
    color: #000;
    border-color: rgba(0, 0, 0, 0.2); /* viền đen mờ */
}

.info-right {
    color: #0E3EDA;
    border-color: rgba(14, 62, 218, 0.2); /* viền xanh mờ */
}

.divider {
    color: #FF9800;
    font-weight: bold;
    padding: 0 8px;
    flex: 0 0 auto;
}

.change-id-btn {
    display: block;
    margin: 0 auto;
    font-size: 1rem; /* 👈 nhỏ hơn so với 1.5rem */
    padding: 6px 12px;
    border: 1px solid rgba(0, 0, 0, 0.2); /* 👈 viền xám nhẹ */
    border-radius: 6px;
    background-color: #fff;
    color: #333;
    cursor: pointer;
    transition: all 0.2s ease;
}

.change-id-btn:hover {
    background-color: #f5f5f5;
    border-color: rgba(0, 0, 0, 0.3);
    color: #000;
}

.confirm-btn {
    display: block;
    margin: 0 auto;
    background-color: #0E3EDA;
    color: #fff;
    font-size: 1.2rem;
    font-weight: bold;
    padding: 10px 30px;
    border: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.confirm-btn:hover {
    background-color: #0b2f9c;
}
.orange-line {
    height: 4px;
    width: calc(100% - 40px); /* trừ 20px mỗi bên */
    max-width: calc(400px - 40px); /* nếu khung tối đa 400px thì trừ 40px */
    background-color: #FF9800;
    margin: 10px auto;
    border-radius: 2px;
}
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
    border: 2px solid #f39200;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase; /* 👈 in hoa */
    white-space: nowrap;
    width: 100%; /* 👈 full 2 bên */
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
.goi-filter-container {
    display: flex;
    flex-wrap: nowrap;
    margin-bottom: 10px;
    box-sizing: border-box;
}

.goi-filter-btn {
    background-color: #fff;
    border: 1px solid #ccc;
    color: #333;
    padding: 8px 14px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
    flex: 1;
    white-space: nowrap;
    margin-bottom: 10px;
}

/* ✅ Khoảng cách trái cho nút đầu */
.goi-filter-btn:first-child {
    margin-left: 10px;
}

/* ✅ Khoảng cách phải cho nút cuối */
.goi-filter-btn:last-child {
    margin-right: 10px;
}

/* Hover và active giống nhau */
.goi-filter-btn:hover,
.goi-filter-btn.active {
    background-color: #f5f5f5;
    border-color: #bbb;
    color: #000;
}

</style>

<div class="container" style="padding: 20px 0px 0 0px;">
    @if (!$loggedIn)

        @if ($errors->any())
            <div class="alert alert-danger text-center">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('nap-goi.login') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <input 
                    type="text" 
                    name="roleID" 
                    id="roleID" 
                    class="form-control text-center" 
                    placeholder="Nhập ID Game" 
                    required>
            </div>
            <button type="submit" class="confirm-btn">
                XÁC NHẬN
            </button>
        </form>

    @else
        <div class="login-info-box text-center">
            <div class="login-info-row">
                <div class="info-left">{{ $roleID }}</div>
                <div class="divider">|</div>
                <div class="info-right">{{ $roleName }}</div>
            </div>
        </div>
        
        <form action="{{ route('nap-goi.change') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning change-id-btn">ĐĂNG XUẤT</button>
        </form>
    @endif
</div>

<div class="orange-line"></div>

<div class="text-center mb-3">
    <button class="goi-filter-btn" data-filter="all">TẤT CẢ</button>
    <button class="goi-filter-btn" data-filter="hot_item">GÓI HOT</button>
    <!--<button class="goi-filter-btn" data-filter="goi_special">GÓI ĐẶC BIỆT</button>-->
    <!--<button class="goi-filter-btn" data-filter="goi_promotion">GÓI KHUYẾN MÃI</button>-->
</div>

<div class="container no-padding">
    <div class="account-section">
        <div class="account-grid-new">
            @foreach ($gois as $index => $goi)
                <div class="account-item" data-status="{{ $goi->status ?? 'none' }}">
                    <div class="account-image-wrapper">
                        <img src="{{ $goi->image }}" class="account-image" alt="{{ $goi->productName }}">
                    </div>
                    <div class="account-info-wrapper">
                        <div class="account-badges">
                            <div class="account-badge">{{ $goi->productName }}</div>
                        </div>
                    </div>
                    <div class="account-action-wrapper">
                        <div class="account-price">
                            {{ number_format($goi->price, 0, '.', '.') }} VND
                        </div>
                        <button class="account-button">MUA NGAY</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.goi-filter-btn');
    const items = document.querySelectorAll('.account-item');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');

            items.forEach(item => {
                const status = item.getAttribute('data-status');
                if (filter === 'all' || status === filter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });

            // Cập nhật nút active
            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });
});

</script>

@endsection
