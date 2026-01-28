@extends('layouts.admin.app')
@section('title', 'Lịch Sử Mua Tài Khoản')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Lịch Sử Mua Tài Khoản</h4>
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
                                    <th>Người Mua</th>
                                    <th>Tài Khoản</th>
                                    <th>Danh Mục</th>
                                    <th>Giá Tiền</th>
                                    <th>Thời Gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $key => $account)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $account->buyer_id) }}">
                                                {{ $account->buyer->username ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.accounts.edit', $account->id) }}">
                                                {{ $account->account_name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($account->category)
                                                {{ $account->category->name }}
                                            @else
                                                <span class="text-danger">Không có</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($account->price) }} VND</td>
                                        <td>{{ $account->updated_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- <div class="pagination-area mt-3">
                        {{ $accounts->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
