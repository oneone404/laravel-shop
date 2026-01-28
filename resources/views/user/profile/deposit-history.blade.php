<table class="table table-striped">
    <thead>
        <tr>
            <th>Trạng thái</th>
            <th>Thời gian</th>
            <th>Nhà mạng</th>
            <th>Mệnh giá</th>
            <th>Thực nhận</th>
            <th>Mã thẻ</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($transactions as $transaction)
            <tr>
                <td>{!! display_status($transaction->status) !!}</td>
                <td>{{ $transaction->created_at }}</td>
                <td>{{ $transaction->telco }}</td>
                <td>{{ number_format($transaction->amount) }} VND</td>
                <td>{{ number_format($transaction->received_amount) }} VND</td>
                <td>{{ substr($transaction->pin, 0, 3) . '******' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Không có dữ liệu</td>
            </tr>
        @endforelse
    </tbody>
</table>
