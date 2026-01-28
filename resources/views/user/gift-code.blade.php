@extends('layouts.user.app')

@section('title', 'Nhập Code')

@section('content')

<style>
    .container {
        padding: 0px;
    }
    .card {
        max-width: 500px;
        margin: 0 auto;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 10px;
    }
    .btn-primary {
        width: 100%;
    }
</style>

<x-hero-header title="Nhập Code" description="" />

<div class="container mt-4">
    <div class="card p-4">
        <h4 class="mb-4 text-center">Nhập Code Game</h4>

        <form id="gift-code-form">
            <div class="mb-3">
                <label for="roleId" class="form-label">ID Game:</label>
                <input type="text" class="form-control" id="roleId" name="roleId" placeholder="Nhập ID Game" required>
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Code:</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Nhập Mã Code" required>
            </div>

            <button type="submit" class="btn btn-primary">Nhập Code</button>
        </form>

        <div id="result" class="mt-4"></div>
    </div>
</div>

<script>
    document.getElementById('gift-code-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const roleId = document.getElementById('roleId').value.trim();
        const code = document.getElementById('code').value.trim();
        const resultDiv = document.getElementById('result');

        if (!roleId || !code) {
            resultDiv.innerHTML = `<div class="alert alert-warning">Vui Lòng Nhập Đầy Đủ Thông Tin</div>`;
            return;
        }

        resultDiv.innerHTML = `<div class="alert alert-info">...</div>`;

        fetch('https://accone.vn/api/nap-zing/gift-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ roleId, code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        Success<br>
                        <strong>Nhập Thành Công</strong><br>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        Error<br>
                        <strong>Lỗi:</strong> Nhập Không Thành Công<br>
                    </div>
                `;
            }
        })
        .catch(error => {
            resultDiv.innerHTML = `<div class="alert alert-danger">⚠️ Lỗi kết nối: ${error}</div>`;
        });
    });
</script>

@endsection
