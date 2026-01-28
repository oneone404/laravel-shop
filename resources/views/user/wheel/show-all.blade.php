@extends('layouts.user.app')
@section('title', 'Mua Key Game')

@section('content')
    <div class="container">
        <h1>Mua Key Game</h1>

        <!-- Hiển thị thông báo lỗi hoặc thành công -->
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @elseif (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('buy-key.process') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="game">Chọn Game</label>
                <select name="game" class="form-control" required>
                    <option value="">Chọn game</option>
                    @foreach ($games as $game)
                        <option value="{{ $game }}">{{ $game }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="key_duration">Chọn Thời Gian Key</label>
                <select name="key_duration" class="form-control" required>
                    <option value="">Chọn thời gian</option>
                    @foreach ($options as $key => $price)
                        <option value="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }} - {{ $price }} VNĐ</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="payment_method">Phương Thức Thanh Toán</label>
                <select name="payment_method" class="form-control" required>
                    <option value="cash">Tiền mặt</option>
                    <option value="credit">Thẻ tín dụng</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Thanh Toán</button>
        </form>
    </div>
@endsection
