@extends('layouts.user.app')

@section('title', 'Bảo Trì Chức Năng')

@section('content')
<style>
    body {
        background-color: #f8f9fa;
    }

    .maintenance-wrapper {
        max-width: 550px;
        margin: 100px auto;
        padding: 40px;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        font-family: 'Segoe UI', sans-serif;
        position: relative;
        overflow: hidden;
    }

    .gear-icon {
        font-size: 64px;
        color: #f39c12;
        animation: spin 3s linear infinite;
        display: inline-block;
        margin-bottom: 20px;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    .maintenance-wrapper h1 {
        font-size: 32px;
        color: #343a40;
        margin-bottom: 16px;
    }

    .maintenance-wrapper p {
        font-size: 16px;
        color: #6c757d;
        margin-bottom: 12px;
        line-height: 1.6;
    }

    .maintenance-wrapper a {
        display: inline-block;
        padding: 10px 24px;
        background-color: #f39c12;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        margin-top: 20px;
        transition: background-color 0.3s ease;
    }

    .maintenance-wrapper a:hover {
        background-color: #e67e22;
    }

    @media (max-width: 480px) {
        .maintenance-wrapper {
            margin: 40px 20px;
            padding: 30px 20px;
        }

        .gear-icon {
            font-size: 48px;
        }

        .maintenance-wrapper h1 {
            font-size: 28px;
        }
    }
</style>

<div class="maintenance-wrapper">
    <div class="gear-icon">⚙️</div>
    <h1>Đang Bảo Trì</h1>
    <p>Vui Lòng Quay Lại Sau</p>
    <a href="{{ url('/') }}">Quay lại Trang Chủ</a>
</div>
@endsection
