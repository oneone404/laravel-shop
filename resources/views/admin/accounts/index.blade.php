@extends('layouts.admin.app')
@section('title', 'Quản Lý Tài Khoản')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                
            </div>
            @if (session('success'))
                <x-alert-admin type="success" :message="session('success')" />
            @endif

            @if (session('error'))
                <x-alert-admin type="danger" :message="session('error')" />
            @endif
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
                                    <th>DANH MỤC</th>
                                    <th>TÀI KHOẢN</th>
                                    <th>GIÁ TIỀN</th>
                                    <th>TRẠNG THÁI</th>
                                    <!--<th>Máy chủ</th>-->
                                    <!--<th>Loại đăng ký</th>-->
                                    <!--<th>Hành tinh</th>-->
                                    <th>HÌNH ẢNH</th>
                                    <th>THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $key => $account)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a
                                                href="{{ route('admin.categories.edit', ['category' => $account->category->id]) }}">{{ $account->category->name }}</a>
                                        </td>
                                        <td class="text-bolds">{{ $account->account_name }}</td>
                                        <td>{{ number_format($account->price) }} VNĐ</td>
                                        <td>
                                            <span
                                                class="badges {{ $account->status === 'available' ? 'bg-lightgreen' : 'bg-lightred' }}">
                                                {{ $account->status === 'available' ? 'CHƯA BÁN' : 'ĐÃ BÁN' }}
                                            </span>
                                        </td>
                                        <td>
                                            <img src="{{ asset($account->thumb) }}" alt="{{ $account->account_name }}"
                                                class="img-thumbnail" style="max-width: 100px;">
                                        </td>
                                        <td>
                                            <a class="me-3" href="{{ route('admin.accounts.edit', $account->id) }}">
                                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                                            </a>
                                            <a class="me-3 confirm-delete" href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-id="{{ $account->id }}">
                                                <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="img">
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal-confirm-delete
        message="Bạn có chắc chắn muốn xóa tài khoản game này không? Tất cả dữ liệu có liên quan đến nó sẽ
                    biến mất khỏi hệ thống!" />
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let id;

            // Lưu ID dịch vụ khi click nút xóa
            $('.confirm-delete').on('click', function() {
                id = $(this).data('id');
            });

            // Xử lý sự kiện click nút xác nhận xóa
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '/admin/accounts/delete/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        if (response.success) {
                            // Hiển thị thông báo thành công
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Đã xóa thành công',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // Reload trang
                                window.location.reload();
                            });
                        } else {
                            // Hiển thị thông báo lỗi
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: response.message ||
                                    'Có lỗi xảy ra khi xóa',
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#deleteModal').modal('hide');
                        // Hiển thị thông báo lỗi
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: xhr.responseJSON?.message ||
                                'Có lỗi xảy ra khi xóa',
                        });
                    }
                });
            });
        });
    </script>
@endpush
