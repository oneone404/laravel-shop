@extends('layouts.user.app')

@section('title', 'Đăng Nhập')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
@endpush
@section('content')
<style>
.containerv2{
    padding: 0px;
}
</style>
    <section class="register-section">
        <div class="containerv2">
            <div class="register-container">
                <div class="register-header">
                    <h1 class="register-title">Đăng Nhập</h1>
                </div>
                @if (session('error'))
                    <div class="service__alert service__alert--error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" class="service__alert-close">&times;</button>
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}" class="register-form">
                    @csrf

                    <div class="form-group">
                        <label for="username" class="form-label">Tên Tài Khoản</label>
                        <input id="username" type="text"
                            class="form-input @error('username') is-invalid @enderror"
                            name="username" value="{{ old('username') }}" required autofocus
                            placeholder="Nhập Username...">
                        @error('username')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password"
                            class="form-input @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password"
                            placeholder="Nhập Password...">
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <input type="hidden" name="remember" value="on">
                    <button type="submit" class="register-btn">
                        Đăng Nhập
                    </button>
                    <div class="login-link">
                        Chưa Có Tài Khoản? <a href="{{ route('register') }}">Đăng Ký Nhanh</a>
                    </div>
                    @if (config_get('login_social.google.active', false) || config_get('login_social.facebook.active', false))
                        <div class="social-login">
                            <p class="social-login-text">Hoặc</p>
                            <div class="social-login-buttons">
                                @if (config_get('login_social.google.active', false))
                                    <a href="{{ route('auth.google') }}" class="google-login-btn">
                                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google icon" style="width: 20px; margin-right: 10px;">
                                        <span>Đăng Nhập Bằng Google</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection
