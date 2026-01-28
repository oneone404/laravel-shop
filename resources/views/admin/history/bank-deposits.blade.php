@extends('layouts.admin.app')
@section('title', $title)
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Lịch sử nạp tiền qua ngân hàng</h4>
                    <h6>Xem tất cả lịch sử nạp tiền qua ngân hàng của người dùng</h6>
                </div>
            </div>

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
                                            placeholder="Tìm kiếm...">
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
                                    <th>MÃ GIAO DỊCH</th>
                                    <th>USER</th>
                                    <th>Số tiền</th>
                                    <th>NỘI DUNG</th>
                                    <th>STATUS</th>
                                    <th>THỜI GIAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deposits as $key => $deposit)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $deposit->transaction_id }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $deposit->user_id) }}">
                                                {{ $deposit->user->username ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>{{ number_format($deposit->amount) }} VND</td>
                                        <td>{{ $deposit->content }}</td>
                                        <td>
                                            <span class="badges bg-lightgreen">SUCCESS</span>
                                        </td>
                                        <td>{{ $deposit->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- <div class="pagination-area mt-3">
                        {{ $deposits->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
