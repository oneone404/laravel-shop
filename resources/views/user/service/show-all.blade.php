@extends('layouts.user.app')
@section('title', 'Dịch Vụ')
@section('content')
    <x-hero-header title="DỊCH VỤ GAME" description="" />

    <section class="menu">
        <div class="container">
            <div class="product-grid">
                @if ($services->count() > 0)
                    @foreach ($boostingServices as $service)
                        @if ($service->active)
                            <a href="{{ route('service.show', ['slug' => $service->slug]) }}" class="product-card">
                                <img src="{{ $service->thumbnail }}" alt="{{ $service->name }}" class="product-image" />
                                <h2 class="product-name">{{ strtoupper($service->name) }}</h2>
                            <div class="product-stats">
                                <span class="status-label">Trạng Thái:</span>
                                <span class="status-ready">Sẵn Sàng</span>
                            </div>
                        <p class="product-action">THUÊ DỊCH VỤ</p>
                            </a>
                        @endif
                    @endforeach
                @else
                    <div class="no-results">
                        <div class="no-results__content">
                            <i class="fas fa-exclamation-circle no-results__icon"></i>
                            <h2 class="no-results__title">Không Tìm Thấy Dịch Vụ</h2>
                            <p class="no-results__message"></p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
