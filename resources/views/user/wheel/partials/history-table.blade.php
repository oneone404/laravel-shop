<section class="deposit-history" id="history-section">
            <h2 class="history-header">LỊCH SỬ VÒNG QUAY</h2>
            @if (count($history) > 0)
                <div class="history-table-container">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Tổng Tiền</th>
                                <th>Phần Thưởng</th>    
                                <th>Số LƯỢT</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $index => $item)
                                <tr class="{{ $index >= 5 ? 'history-hidden' : '' }}" style="{{ $index >= 5 ? 'display:none;' : '' }}">
                                    <td>{{ number_format($item->total_cost) }} VNĐ</td>
                                    <td class="history-item-reward">
                                        @if(\Illuminate\Support\Str::startsWith($item->description, 'ONE'))
                                            {{ substr($item->description, 0, 10) . '***' }}
                                            <button class="copy-btn" data-clipboard-text="{{ $item->description }}">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        @else
                                            {{ $item->description }}
                                        @endif
                                    </td>
                                    <td>{{ $item->spin_count }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-history">
                    <p>Chưa Có Dữ Liệu</p>
                </div>
            @endif
        </section>
