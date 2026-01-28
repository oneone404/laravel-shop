@extends('layouts.admin.app')
@section('title', 'Sửa Người Dùng')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Sửa Người Dùng</h4>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>TÊN TÀI KHOẢN</label>
                                    <input type="text" name="username" value="{{ $user->username }}" class="form-control"
                                        readonly>
                                </div>
                            </div>
<!--<div class="form-group">-->
<!--    <label for="password">Mật khẩu mới (nếu muốn đổi)</label>-->
<!--    <input type="password" name="password" class="form-control">-->
<!--</div>-->

<!--<div class="form-group">-->
<!--    <label for="password_confirmation">Xác nhận mật khẩu mới</label>-->
<!--    <input type="password" name="password_confirmation" class="form-control">-->
<!--</div>-->

                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>EMAIL</label>
                                    <input type="email" name="email" value="{{ $user->email }}"
                                        class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>CẤP BẬC</label>
                                    <select name="role" class="select @error('role') is-invalid @enderror">
                                        <option value="member" {{ $user->role == 'member' ? 'selected' : '' }}>NGƯỜI DÙNG
                                        </option>
                                        <option value="seller" {{ $user->role == 'seller' ? 'selected' : '' }}>NGƯỜI BÁN HÀNG
                                        </option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>QUẢN TRỊ VIÊN
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>SỐ DƯ</label>
                                    <input type="number" name="balance" value="{{ $user->balance }}"
                                        class="form-control @error('balance') is-invalid @enderror">
                                    @error('balance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
<div class="col-lg-4 col-sm-6 col-12">
    <div class="form-group">
        <label>TỔNG NẠP</label>
        <input type="number" value="{{ $user->total_deposited }}" class="form-control" disabled>
    </div>
</div>

                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>TRẠNG THÁI</label>
                                    <select name="banned" class="select @error('banned') is-invalid @enderror">
                                        <option value="0" {{ $user->banned == 0 ? 'selected' : '' }}>HOẠT ĐỘNG
                                        </option>
                                        <option value="1" {{ $user->banned == 1 ? 'selected' : '' }}>BỊ KHOÁ</option>
                                    </select>
                                    @error('banned')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-submit me-2">CẬP NHẬT</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-cancel">HUỶ BỎ</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">

                            <div class="search-input">
                                <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg') }}"
                                        alt="img"></a>
                            </div>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>LOẠI GD</th>
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
                                            {!! display_status_transactions_admin($transaction->type) !!}

                                        </td>
                                        <td
                                            class="{{ $transaction->type == 'deposit' || $transaction->type == 'refund' ? 'text-success' : 'text-danger' }}">
                                            {{ ($transaction->type == 'deposit' || $transaction->type == 'refund' ? '+' : '-') . number_format($transaction->amount) }} VND
                                        </td>
                                        <td>{{ number_format($transaction->balance_before) }} VND</td>
                                        <td>{{ number_format($transaction->balance_after) }} VND</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
