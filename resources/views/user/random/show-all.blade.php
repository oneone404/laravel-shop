@extends('layouts.user.app')
@section('title', 'Random')
@section('content')
<style>
.product-action {
    border: 1.5px solid #0E3EDA;
    color: #0E3EDA;
    border-radius: 18px;
    padding: 5px 0; /* padding trái phải để tính bằng width 100% */
    font-weight: 600;
    font-size: 11px;
    margin-top: 2px;
    margin-bottom: 8px;
    display: block;
    width: calc(100% - 32px); /* full chiều ngang, chừa 2px mỗi bên */
    margin-left: auto;
    margin-right: auto;
    transition: 0.3s;
}

.product-action:hover {
    background: #0E3EDA;
    color: #fff;
}
.product-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 6px;
}
    .container {
    padding: 0;
}
/* Bước 3: card bo nhẹ góc, nhỏ gọn */
.product-card {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
    transition: transform 0.2s ease;
    padding-bottom: 8px;
}

.product-card:hover {
    transform: translateY(-2px);
}

.product-image-wrapper {
    width: 100%;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: auto;
    display: block;
}

.product-name {
    font-size: 15px;
    font-weight: 600;
    margin: 8px 4px 4px 4px;
    min-height: 32px;
}
/* Badge nằm ngang lại */
.product-stats {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
    flex-wrap: nowrap;
}

.product-badge {
    background: #0E3EDA;
    color: #fff;
    border-radius: 12px;
    padding: 3px 9px;
    font-size: 0.8rem;
    white-space: nowrap;
    font-weight: 700;
}
/* Responsive cho PC: 4 cột khi màn hình >= 768px */
@media (min-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 12px; /* Cho PC giãn ra chút cho đẹp */
    }

    .product-name {
        font-size: 16px;
        min-height: 32px;
    }

    .product-action {
        font-size: 12px;
        padding: 7px 24px;
    }
}
</style>
    <x-hero-header title="DANH MỤC RANDOM" description="" />

    <section class="menu">
        <div class="container">
            <div class="product-grid">
                @if ($categories->count() > 0)
                    @foreach ($categories as $category)
                        <a href="{{ route('random.index', ['slug' => $category->slug]) }}" class="product-card">
                            <img src="{{ $category->thumbnail }}" alt="{{ $category->name }}" class="product-image" />
                            <h2 class="product-name">{{ strtoupper($category->name) }}</h2>
                            <div class="product-stats">
                        <span class="product-badge">ĐÃ BÁN {{ number_format($category->soldCount)}}</span>
                        <span class="product-badge">CÒN LẠI {{ number_format($category->availableAccount) }}</span>
                    </div>
                            <p class="product-action">XEM CHI TIẾT</p>
                        </a>
                    @endforeach
                @else
                    <div class="no-results">
                        <div class="no-results__content">
                            <i class="fas fa-exclamation-circle no-results__icon"></i>
                            <h2 class="no-results__title">Không tìm thấy danh mục!</h2>
                            <p class="no-results__message">Hiện tại không có danh mục random nào.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
