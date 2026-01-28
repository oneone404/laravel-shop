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
.notice-content {
  text-align: left; /* Căn chữ sang trái */
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Phông chữ dễ đọc */
  font-size: 16px; /* Cỡ chữ vừa phải */
  line-height: 1.6; /* Giãn dòng cho dễ đọc */
  color: #333; /* Màu chữ dịu mắt */
}
.mb-icon {
  width: 28px;
  height: 28px;
  border-radius: 6px; /* Bo tròn góc vừa phải */
  margin-right: 8px;
  vertical-align: middle;
  object-fit: cover;  /* Giữ ảnh không méo */
  border: 1px solid #ddd; /* Tùy chọn: thêm viền nhẹ nếu cần */
}
.container {
    padding: 0px;
}
</style>
<section class="profile-section">
        <div class="container">
            <div class="profiler-container">
                <div class="profile-header">
                    <h1 class="profile-title"><i class="fa-solid fa-money-bill-transfer"></i> NẠP TIỀN NGÂN HÀNG</h1>
                </div>
                <div class="profile-content">
                    @include('layouts.user.sidebar')
                            <div class="info-content">
                                <!-- Hướng Dẫn -->
                                <div class="deposit-notice">
                                    <div class="notice-header" style = "text-align: left">HƯỚNG DẪN NẠP TIỀN</div>
                                    <div class="notice-content">
                                        <p>1. QUÉT MÃ QR BÊN DƯỚI</p>
                                        <p>2. GHI ĐÚNG NỘI DUNG</p> 
                                        <p>3. TỰ ĐỘNG CỘNG SAU 5S</p> 
                                        <p style = "color:red">Lưu ý: Nạp Tối Thiểu 10.000 VND</p>
                                    </div>
                                    <!--<div class="notice-warning">CHÚ Ý: PHẢI ĐÚNG CÚ PHÁP NỘI DUNG CHUYỂN KHOẢN</div>-->
                                </div>

                                <div class="bank-accounts-container">
                                    <div class="bank-accounts-list">
                                        @if (count($bankAccounts) > 0)
                                            @foreach ($bankAccounts as $account)
                                                <div class="bank-account-item">
                                                    <div class="bank-account-info">
                                                        <div class="bank-details">
                                                            <h3 class="bank-name">
                                                              <img src="{{ asset('images/acbank.png') }}" alt="MBBank" class="mb-icon">
                                                              {{ $account->bank_name }}
                                                            </h3>
                                                            <div class="branch">
                                                                <span class="label">Tên TK</span>
                                                                <span class="value">{{ $account->account_name }}</span>
                                                            </div>
                                                            <div class="account-number">
                                                                <span class="label">Số TK</span>
                                                                <span class="value">{{ $account->account_number }}</span>
                                                                <button class="copy-btn"
                                                                    data-clipboard-text="{{ $account->account_number }}">
                                                                    <i class="far fa-copy"></i>
                                                                </button>
                                                            </div>
                                                            <div class="note">
                                                                <span class="label">Nội Dung</span>
                                                                <span
                                                                    style = "color:#008000">{{ $account->prefix . Auth::user()->id }}</span>
                                                                <button class="copy-btn"
                                                                data-clipboard-text="{{ $account->prefix . Auth::user()->id }}">
                                                                <i class="far fa-copy"></i>
                                                            </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bank-qr-code">
                                                        <img src="https://qr.sepay.vn/img?bank={{ $account->bank_name }}&acc={{ $account->account_number }}&template=&amount=&des={{ $account->prefix . Auth::user()->id }}"
                                                            alt="QR Code">

                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="no-bank-accounts">
                                                <p class="text-danger text-bold">Hiện tại không có tài khoản ngân hàng nào
                                                    được cấu
                                                    hình</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="container">
<div class="deposit-history">
                                    <!--<button id="check-history">XÁC NHẬN ĐÃ CHUYỂN TIỀN</button>-->
                                    <div class="history-header">LỊCH SỬ NẠP TIỀN</div>
                                    <div class="history-table-container">
                                        <table class="history-table">
                                            <thead>
                                                <tr>
                                                    <th>Số tiền</th>
                                                    <th>Ngân hàng</th>
                                                    <th>Nội dung</th>
                                                    <th>Thời gian</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($transactions) && count($transactions) > 0)
                                                    @foreach ($transactions as $transaction)
                                                        <tr>
                                                            <td class="text-success">
                                                                + {{ number_format($transaction->amount) }} VND</td>
                                                            <td>{{ $transaction->bank }}</td>
                                                            <td>{{ $transaction->content }}</td>
                                                            <td>{{ $transaction->created_at }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4" class="no-data">Không có dữ liệu</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    @if (isset($transactions) && $transactions->hasPages())
                                        <div class="pagination">
                                            {{ $transactions->links() }}
                                        </div>
                                    @endif
                                </div>
                                 </div>
</section>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var clipboard = new ClipboardJS('.copy-btn');

            clipboard.on('success', function(e) {
                const originalText = e.trigger.innerHTML;
                e.trigger.innerHTML = '<i class="fas fa-check"></i>';

                setTimeout(function() {
                    e.trigger.innerHTML = originalText;
                }, 2000);

                e.clearSelection();
            });

            const checkHistoryBtn = document.getElementById('check-history');
            const originalText = checkHistoryBtn.innerHTML;

            checkHistoryBtn.addEventListener('click', function() {
                checkHistoryBtn.innerHTML = 'LOADING... <span class="loading-spinner"></span>';
                checkHistoryBtn.disabled = true;

                fetch('/api/check-history')
                    .then(response => {
                        console.log('Request sent to API successfully');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        location.reload();
                    });
            });
        });
    </script>
@endpush

<style>
    #check-history {
        background-image: linear-gradient(135deg, #6e93f7, #a78bfa);
        color: #fff;
        padding: 10px 18px;
        border: none;
        border-radius: 24px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    #check-history:hover {
        background-image: linear-gradient(135deg, #a78bfa, #6e93f7);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    #check-history:active {
        transform: translateY(1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    #check-history:disabled {
        background-color: #bdc3c7;
        cursor: not-allowed;
    }

    .loading-spinner {
        width: 14px;
        height: 14px;
        border: 2px solid #fff;
        border-top: 2px solid #6e93f7;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>


@endsection
