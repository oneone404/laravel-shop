@extends('layouts.admin.app')
@section('title', 'Sửa Tài Khoản')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.accounts.update', $account->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @if ($account->status == 'sold' && $account->buyer_id)
                            <h4 class="text-danger">Người Mua <a
                                    href="{{ route('admin.users.show', $account->buyer_id) }}" target="_blank"
                                    class="text-bold">{{ $account->buyer_id }}</a></h4>
                        @endif
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>DANH MỤC GAME <span class="text-danger">*</span></label>
                                    <select name="game_category_id"
                                        class="select @error('game_category_id') is-invalid @enderror">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('game_category_id', $account->game_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('game_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>TÊN TÀI KHOẢN <span class="text-danger">*</span></label>
                                    <input type="text" name="account_name"
                                        value="{{ old('account_name', $account->account_name) }}"
                                        class="form-control @error('account_name') is-invalid @enderror">
                                    @error('account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>MẬT KHẨU <span class="text-danger">*</span></label>
                                    <input type="text" name="password" value="{{ old('password', $account->password) }}"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>GIÁ TIỀN <span class="text-danger">*</span></label>
                                    <input type="number" name="price" value="{{ old('price', $account->price) }}"
                                        class="form-control @error('price') is-invalid @enderror">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>TRẠNG THÁI</label>
                                    <select name="status" class="select @error('status') is-invalid @enderror">
                                        <option value="available"
                                            {{ old('status', $account->status) == 'available' ? 'selected' : '' }}>CÒN HÀNG
                                        </option>
                                        <option value="sold"
                                            {{ old('status', $account->status) == 'sold' ? 'selected' : '' }}>ĐÃ BÁN
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <input type="hidden" name="server" value="13">

                            <input type="hidden" name="registration_type" value="virtual">

                            <input type="hidden" name="planet" value="earth">

                            <input type="hidden" name="earring" value="1">

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="image-upload">
                                        <input type="file" name="thumb"
                                            class="form-control @error('thumb') is-invalid @enderror" accept="image/*"
                                            onchange="previewImage(this, 'preview-thumb')">
                                        @error('thumb')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="image-uploads">
                                            <img src="{{ asset('assets/img/icons/upload.svg') }}" alt="Upload Image"
                                                style="max-width: 200px; max-height: 200px;">
                                            <h4>Chọn Hình Ảnh</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="images[]" value="">

                            <div class="col-lg-12 text-center">
                                <img id="preview-thumb" src="{{ $account->thumb }}" alt="preview"
                                    class="mx-auto d-block mb-3 preview-thumb">
                                <div id="preview-images" class="d-flex flex-wrap justify-content-center gap-3 mb-3">
                                    @if ($account->images)
                                        @foreach (json_decode($account->images) as $image)
                                            <img src="{{ $image }}" alt="preview"
                                                style="max-width: 200px; max-height: 200px;">
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="note" value="">

                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-submit me-2">CẬP NHẬT</button>
                                <a href="{{ route('admin.accounts.index') }}" class="btn btn-cancel">HUỶ BỎ</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewMultipleImages(input, previewId) {
            var preview = document.getElementById(previewId);
            preview.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '200px';
                        img.style.maxHeight = '200px';
                        preview.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
@endpush
