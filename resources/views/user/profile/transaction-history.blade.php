@extends('layouts.user.app')

@section('title', 'Biến Động Số Dư')

@section('content')
<style>
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
.containerv2 {
    padding: 0px; /* cho mobile vẫn gọn */
}

/* Khi màn hình lớn hơn 768px (tablet trở lên) */
@media (min-width: 768px) {
    .containerv2 {
        padding: 0 23%; /* cho PC, rộng rãi hơn */
    }
}
    
.history-header {
    border-bottom: 2px solid var(--accent-color);
    margin: 0 20px;
    text-align: center;
}
</style>
    <section class="profile-section">
        <div class="containerv2">
                <div class="history-header">BIẾN ĐỘNG SỐ DƯ</div>

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

                                <div class="transaction-history">
                                    <div class="history-table-container">
                                        <table class="history-table">
                                            <thead>
                                                <tr>
                                                    <th>THỜI GIAN</th>
                                                    <th>MÔ TẢ</th>
                                                    <th>SỐ DƯ TRƯỚC</th>
                                                    <th>SỐ DƯ SAU</th>
                                                    <th>SỐ TIỀN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($transactions as $transaction)
                                                    <tr>
                                                        <td>{{ $transaction->created_at->format('H:i d/m/Y') }}</td>

                                                        <td>{{ $transaction->description }}</td>
                                                        <td>{{ number_format($transaction->balance_before) }} VND</td>
                                                        <td>{{ number_format($transaction->balance_after) }} VND</td>
                                                        <td class="amount {{ $transaction->type === 'deposit' ? 'text-success' : 'text-danger' }}">
                                                            {{ $transaction->type === 'deposit' ? '+' : '-' }}
                                                            {{ number_format($transaction->amount) }} VND
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="no-data">Không Có Dữ Liệu</td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
