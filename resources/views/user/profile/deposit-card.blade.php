@extends('layouts.user.app')

@section('title', 'Nạp Tiền')

@section('content')
<style>
.profiler-container {
  background-color: #fff;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  overflow: hidden;
  transition: all 0.3s ease;
}

    .history-table-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.history-table {
    white-space: nowrap;
    min-width: auto; /* hoặc lớn hơn nếu bảng rộng hơn */
}

.history-table th,
.history-table td {
    white-space: nowrap;
}

.status-badge {
    display: inline-block;
    padding: 2px 8px;             /* Giảm padding để nút nhỏ hơn */
    border-radius: 12px;          /* Có thể giảm bo tròn nếu muốn gọn */
    font-size: 0.9rem;            /* Giảm kích cỡ chữ */
    font-weight: 500;             /* Có thể giảm trọng số font nếu muốn thanh mảnh hơn */
    text-transform: uppercase;
    text-align: center;
    vertical-align: middle;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}



.status-badge.success {
    background-color: #28a745;
    color: white;
}

.status-badge.error {
    background-color: #dc3545;
    color: white;
}

.status-badge.processing {
    background-color: #ffc107;
    color: #212529;
}

.status-badge.success-smg {
    background-color: #17a2b8; /* xanh biển */
    color: white;
    
}
.container {
    padding: 0px;
}
</style>
<section class="profile-section">
        <div class="container">
            <div class="profiler-container">
                <div class="profile-header">
                    <h1 class="profile-title"><i class="fa-solid fa-credit-card me-2"></i> NẠP TIỀN THẺ CÀO</h1>
                </div>

                <div class="profile-content">
                    @include('layouts.user.sidebar')
                            <div class="info-content">
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                                    </div>
                                @endif

                                <form action="{{ route('profile.deposit-card') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="telco" class="form-label">
                                            <i class="fa-solid fa-building me-2"></i> LOẠI THẺ
                                        </label>
                                        <select class="form-control @error('telco') is-invalid @enderror" id="telco" name="telco" required>
                                        </select>
                                        @error('telco')
                                            <div class="invalid-feedback">
                                                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="amount" class="form-label">
                                            <i class="fa-solid fa-money-bill me-2"></i> MỆNH GIÁ
                                        </label>
                                        <select class="form-control @error('amount') is-invalid @enderror" id="amount"
                                            name="amount" required>
                                            <option value="10000" {{ old('amount', '10000') == '10000' ? 'selected' : '' }}>
                                                10.000 VND
                                            </option>
                                            <option value="20000" {{ old('amount') == '20000' ? 'selected' : '' }}>
                                                20.000 VND
                                            </option>
                                            <option value="30000" {{ old('amount') == '30000' ? 'selected' : '' }}>
                                                30.000 VND
                                            </option>
                                            <option value="50000" {{ old('amount') == '50000' ? 'selected' : '' }}>
                                                50.000 VND
                                            </option>
                                            <option value="100000" {{ old('amount') == '100000' ? 'selected' : '' }}>
                                                100.000 VND
                                            </option>
                                            <option value="200000" {{ old('amount') == '200000' ? 'selected' : '' }}>
                                                200.000 VND
                                            </option>
                                            <option value="500000" {{ old('amount') == '500000' ? 'selected' : '' }}>
                                                500.000 VND
                                            </option>
                                            <option value="1000000" {{ old('amount') == '1000000' ? 'selected' : '' }}>
                                                1.000.000 VND
                                            </option>
                                        </select>
                                        @error('amount')
                                            <div class="invalid-feedback">
                                                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                        <div class="mt-2 text-success fw-bold" id="received-amount">
                                            Thực Nhận: 0 VND
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="serial" class="form-label">
                                            <i class="fa-solid fa-barcode me-2"></i> SERI
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('serial') is-invalid @enderror"
                                               id="serial" 
                                               name="serial" 
                                               value="{{ old('serial') }}" 
                                               placeholder="Nhập Số Seri" 
                                               required>
                                        @error('serial')
                                            <div class="invalid-feedback">
                                                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="pin" class="form-label">
                                            <i class="fa-solid fa-key me-2"></i> MÃ THẺ
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('pin') is-invalid @enderror"
                                               id="pin" 
                                               name="pin" 
                                               value="{{ old('pin') }}" 
                                               placeholder="Nhập Mã Thẻ" 
                                               required>
                                        @error('pin')
                                            <div class="invalid-feedback">
                                                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-check me-2"></i> Nạp Tiền
                                        </button>
                                    </div>
                                </form>

                                <div class="deposit-notice">
                                    <div class="notice-warning" style = "text-align: left">LƯU Ý: SAI MỆNH GIÁ MẤT THẺ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    <div class="container">
<div class="deposit-history">
                                    <div class="history-header">LỊCH SỬ NẠP THẺ</div>
                                    <div class="history-table-container">
                                        <table class="history-table">
                                            <thead>
                                                <tr>
                                                    <th>TRẠNG THÁI</th>
                                                    <th>Nhà mạng</th>
                                                    <th>Mệnh giá</th>
                                                    <th>Thực nhận</th>
                                                    <th>Mã thẻ</th>
                                                    <th>Thời gian</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($transactions as $transaction)
                                                    <tr>
                                                        <td>{!! display_status($transaction->status) !!}</td>
                                                        <td>{{ $transaction->telco }}</td>
                                                        <td>{{ number_format($transaction->amount) }} VND</td>
                                                        <td>{{ number_format($transaction->received_amount) }} VND</td>
                                                        <td>{{ substr($transaction->pin, 0, 3) . '...'}}</td>
                                                        <td>{{ $transaction->created_at }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="no-data">Không có dữ liệu</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="pagination">
                                        {{ $transactions->links() }}
                                    </div>
                                </div>
                                </div>
                                </section>
@push('scripts')
<script>
const discounts = {
    VIETTEL: 15,
    VINAPHONE: 20,
    MOBIFONE: 20,
    GARENA: 10,
    ZING: 10
};

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action="{{ route('profile.deposit-card') }}"]');
    const submitButton = form.querySelector('button[type="submit"]');
    const telcoSelect = document.getElementById('telco');
    const amountSelect = document.getElementById('amount');
    const receivedAmountDiv = document.getElementById('received-amount');

    const oldTelco = "{{ old('telco', 'VIETTEL') }}";

    // 🧩 Render telco <option> kèm %
    telcoSelect.innerHTML = '';
    Object.entries(discounts).forEach(([telco, percent]) => {
        const option = document.createElement('option');
        option.value = telco;
        option.textContent = `${telco} (Phí ${percent}%)`;
        if (telco === oldTelco) option.selected = true;
        telcoSelect.appendChild(option);
    });

    // 🧩 Hàm tính & hiển thị Thực Nhận
    const updateReceived = () => {
        const telco = telcoSelect.value;
        const amount = parseInt(amountSelect.value) || 0;
        const discount = discounts[telco] || 0;

        const received = amount - Math.floor(amount * discount / 100);
        receivedAmountDiv.textContent = `Thực Nhận: ${received.toLocaleString('vi-VN')} VND`;
    };

    // Khởi tạo
    updateReceived();

    // Sự kiện thay đổi
    telcoSelect.addEventListener('change', updateReceived);
    amountSelect.addEventListener('change', updateReceived);

    // 🧩 Xử lý submit
    form.addEventListener('submit', () => {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> ĐANG XỬ LÝ...';
    });
});
</script>
@endpush

@endsection
