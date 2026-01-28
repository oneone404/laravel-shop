@extends('layouts.admin.app')
@section('title', 'Thêm Tài Khoản')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.accounts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>DANH MỤC GAME <span class="text-danger">*</span></label>
                                    <select name="game_category_id" class="select @error('game_category_id') is-invalid @enderror">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('game_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('game_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Toggle thêm danh sách --}}
                            <div class="col-lg-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="multiAccountToggle">
                                    <label class="form-check-label" for="multiAccountToggle">Thêm Danh Sách</label>
                                </div>
                            </div>

                            {{-- Nhập 1 tài khoản --}}
                            <div id="singleAccountArea" class="row">
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>TÀI KHOẢN <span class="text-danger">*</span></label>
                                        <input type="text" name="account_name" value="{{ old('account_name') }}"
                                            class="form-control @error('account_name') is-invalid @enderror">
                                        @error('account_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>MẬT KHẨU <span class="text-danger">*</span></label>
                                        <input type="text" name="password" value="{{ old('password') }}"
                                            class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Nhập danh sách tài khoản --}}
                            <div id="multiAccountArea" class="col-lg-12" style="display: none;">
                                <div class="form-group">
                                    <label>Danh Sách Tài Khoản (1 dòng: tài khoản|mật khẩu)</label>
                                    <textarea name="account_list" class="form-control" rows="6"
                                        placeholder="acc1|pass1&#10;acc2|pass2">{{ old('account_list') }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>GIÁ TIỀN <span class="text-danger">*</span></label>
                                    <input type="number" name="price" value="{{ old('price', 50000) }}"
                                        class="form-control @error('price') is-invalid @enderror">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <input type="hidden" name="status" value="available">
                            <input type="hidden" name="server" value="13">
                            <input type="hidden" name="registration_type" value="virtual">
                            <input type="hidden" name="planet" value="{{ old('planet', 'earth') }}">
                            <input type="hidden" name="earring" value="{{ old('earring', 1) }}">

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

                            <x-preview-image />

                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-submit me-2">THÊM TÀI KHOẢN</button>
                                <a href="{{ route('admin.accounts.index') }}" class="btn btn-cancel">HUỶ BỎ</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toggle = document.getElementById('multiAccountToggle');
                const multiArea = document.getElementById('multiAccountArea');
                const singleArea = document.getElementById('singleAccountArea');

                toggle.addEventListener('change', function () {
                    if (this.checked) {
                        multiArea.style.display = 'block';
                        singleArea.style.display = 'none';
                    } else {
                        multiArea.style.display = 'none';
                        singleArea.style.display = 'flex';
                    }
                });
            });
        </script>
    @endpush
@endsection
