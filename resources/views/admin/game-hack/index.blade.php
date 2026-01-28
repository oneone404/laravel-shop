@extends('layouts.admin.app')
@section('title', 'Quản Lý Game')
@section('content')
<style>
.game-logo {
    width: 80px;
    height: 80px;
    object-fit: cover;   /* Cắt ảnh cho vừa khung mà không méo */
    border-radius: 8px;  /* Bo nhẹ góc nếu muốn */
}
</style>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Danh Sách Game</h4>
                </div>
                <div class="page-btn">
                    <a href="{{ route('admin.game-hack.create') }}" class="btn btn-added">
                        <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img">Thêm Game Mới
                    </a>
                </div>
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
                                <a class="btn btn-searchset">
                                    <img src="{{ asset('assets/img/icons/search-white.svg') }}" alt="img">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>ID</th>
                                    <th>TÊN GAME</th>
                                    <th>IMAGE</th>
                                    <th>API</th>
                                    <th>PACKAGE NAME</th>
                                    <th>TRẠNG THÁI</th>
                                    <th>NGÀY TẠO</th>
                                    <th>THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($games as $key => $game)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="text-bolds">{{ $game->name }}</td>
                                        <td>
                                            <img src="{{ $game->thumbnail }}" alt="{{ $game->name }}"
                                                class="img-thumbnail game-logo">
                                        </td>
                                        <td>{{ $game->api_hack }}</td>
                                        <td>{{ $game->api_type }}</td>
                                        <td>
                                            @if($game->active)
                                                <span class="badges bg-lightgreen">ON</span>
                                            @else
                                                <span class="badges bg-lightred">OFF</span>
                                            @endif
                                        </td>
                                        <td>{{ $game->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a class="me-3" href="{{ route('admin.game-hack.edit', $game->id) }}">
                                                <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                                            </a>
                                            <a class="me-3 confirm-delete" href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-id="{{ $game->id }}">
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
        message="Bạn có chắc chắn muốn xóa game hack này không? Dữ liệu sẽ bị xoá khỏi hệ thống!" />

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let gameId;

            $('.confirm-delete').on('click', function() {
                gameId = $(this).data('id');
            });

            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '/admin/game-hack/' + gameId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Đã xóa game hack thành công',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // 👇 Reload lại đúng trang hiện tại, không nhảy sang index.html
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: response.message || 'Có lỗi xảy ra khi xóa game hack',
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#deleteModal').modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi xóa game hack',
                        });
                    }
                });
            });
        });
    </script>
@endpush
