@extends('layouts.admin.app')

@section('title', 'Lịch Sử Dịch Vụ')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người dùng</th>
                                <th>Dịch vụ</th>
                                <th>Gói dịch vụ</th>
                                <th>Máy chủ</th>
                                <th>Tài khoản game</th>
                                <th>Pass Word</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                                <th>Đăng Nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $service->id }}</td>
                                    <td>
                                            <a href="{{ route('admin.users.show', $service->user_id) }}">
                                                {{ $service->user->username ?? 'N/A' }}
                                            </a>
                                        </td>
                                    <td>{{ $service->gameService->name ?? 'N/A' }}</td>
                                    <td>{{ $service->servicePackage->name ?? 'N/A' }}</td>
                                    <td>{{ $service->server }}</td>
                                    <td>{{ $service->game_account }}</td>
                                    <td>{{ $service->game_password }}</td>
                                    <td>{{ number_format($service->price) }} đ</td>
                                    <td>
                                        <form action="{{ route('admin.services.updateStatus', $service->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-control" onchange="this.form.submit()">
                                                <option value="pending" {{ $service->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                <option value="processing" {{ $service->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                                <option value="completed" {{ $service->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                                <option value="cancelled" {{ $service->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>{{ $service->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $service->admin_note }}</td>
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
