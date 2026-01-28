@extends('layouts.admin.app')
@section('title', $title)
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a class="btn btn-searchset">
                                    <img src="{{ asset('assets/img/icons/search-white.svg') }}" alt="img">
                                </a>
                                <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                    <label>
                                        <input type="search" class="form-control form-control-sm"
                                            placeholder="Tìm Kiếm">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>USER</th>
                                    <th>LOẠI GIAO DỊCH</th>
                                    <th>SỐ TIỀN</th>
                                    <th>SỐ DƯ TRƯỚC</th>
                                    <th>SỐ DƯ SAU</th>
                                    <th>MÔ TẢ</th>
                                    <th>THỜI GIAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $key => $transaction)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $transaction->user_id) }}">
                                                {{ $transaction->user->username ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>
                                            {!! display_status_transactions_admin($transaction->type) !!}

                                        </td>
                                        <td>
                                            @if ($transaction->amount > 0)
                                                <span class="text-success">+{{ number_format($transaction->amount) }}
                                                    đ</span>
                                            @else
                                                <span class="text-danger">{{ number_format($transaction->amount) }} đ</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($transaction->balance_before) }} đ</td>
                                        <td>{{ number_format($transaction->balance_after) }} đ</td>
                                        <td>{{ $transaction->description }}</td>

                                        <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- <div class="pagination-area mt-3">
                        {{ $transactions->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
