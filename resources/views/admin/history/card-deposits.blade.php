@extends('layouts.admin.app')
@section('title', 'Lịch Sử Nạp Thẻ')
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
                                    <th>NHÀ MẠNG</th>
                                    <th>MỆNH GIÁ</th>
                                    <th>THỰC NHẬN</th>
                                    <th>MÃ THẺ</th>
                                    <th>SERIAL</th>
                                    <th>STATUS</th>
                                    <th>THỜI GIAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deposits as $key => $deposit)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $deposit->user_id) }}">
                                                {{ $deposit->user->username ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-uppercase">{{ $deposit->telco }}</span>
                                        </td>
                                        <td>{{ number_format($deposit->amount) }} đ</td>
                                        <td>{{ number_format($deposit->received_amount) }} đ</td>
                                        <td>
                                            <span
                                                class="text-monospace">{{ $deposit->pin }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-monospace">{{ $deposit->serial }}</span>
                                        </td>
                                        <td>
                                            @if ($deposit->status === 'success')
                                                <span class="badges bg-lightgreen">SUCCESS</span>
                                            @elseif ($deposit->status === 'processing')
                                                <span class="badges bg-lightyellow">PENDING</span>
                                            @elseif ($deposit->status === 'error')
                                                <span class="badges bg-lightred">CARD ERROR</span>
                                            @else
                                                <span class="badges bg-lightred">FAIL</span>
                                            @endif
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
