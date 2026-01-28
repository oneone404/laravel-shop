@extends('layouts.admin.app')
@section('title', 'Thêm Game')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Thêm Game</h4>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.game-hack.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- Tên Game --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên Game <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Logo --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Logo (URL)</label>
                                    <input type="text" name="logo" value="{{ old('logo') }}"
                                        class="form-control @error('logo') is-invalid @enderror" placeholder="https://.../logo.png">
                                    <div class="mt-2">
                                        <label>Hoặc Tải lên Logo trực tiếp</label>
                                        <input type="file" name="logo_file" class="form-control @error('logo_file') is-invalid @enderror">
                                        @error('logo_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Thumbnail (mới) --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Thumbnail (URL) — dùng cho danh sách</label>
                                    <input type="text" name="thumbnail" value="{{ old('thumbnail') }}"
                                        class="form-control @error('thumbnail') is-invalid @enderror" placeholder="https://.../thumb.jpg">
                                    <div class="mt-2">
                                        <label>Hoặc Tải lên Thumbnail trực tiếp</label>
                                        <input type="file" name="thumbnail_file" class="form-control @error('thumbnail_file') is-invalid @enderror">
                                        @error('thumbnail_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Ảnh chi tiết (mới) --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Ảnh chi tiết (mỗi dòng 1 URL) — dùng cho trang chi tiết</label>
                                    <textarea name="images" rows="6"
                                        class="form-control @error('images') is-invalid @enderror"
                                        placeholder="https://.../1.jpg&#10;https://.../2.jpg">{{ old('images') }}</textarea>
                                    <div class="mt-2">
                                        <label>Hoặc Tải lên các ảnh chi tiết trực tiếp</label>
                                        <input type="file" name="image_files[]" multiple class="form-control @error('image_files.*') is-invalid @enderror">
                                        @error('image_files.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Link Tải --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Link Tải</label>
                                    <input type="text" name="download_link" value="{{ old('download_link') }}"
                                        class="form-control @error('download_link') is-invalid @enderror" placeholder="https://...">
                                    @error('download_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- API --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>API</label>
                                    <input type="text" name="api_hack" value="{{ old('api_hack') }}"
                                        class="form-control @error('api_hack') is-invalid @enderror">
                                    @error('api_hack') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- PACKAGE NAME --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>PACKAGE NAME</label>
                                    <input type="text" name="api_type" value="{{ old('api_type') }}"
                                        class="form-control @error('api_type') is-invalid @enderror" placeholder="com.example.game">
                                    @error('api_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Số Link --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Số Link</label>
                                    <input type="number" name="solink" value="{{ old('solink') }}"
                                        class="form-control @error('solink') is-invalid @enderror" min="0">
                                    @error('solink') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Trạng Thái --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng Thái</label>
                                    <select name="active" class="form-control @error('active') is-invalid @enderror">
                                        <option value="1" {{ old('active', 1) == 1 ? 'selected' : '' }}>ON</option>
                                        <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>OFF</option>
                                    </select>
                                    @error('active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Nền tảng (mới) --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Nền tảng</label>
                                    <input type="text" name="platform" value="{{ old('platform', 'Windows') }}"
                                        class="form-control @error('platform') is-invalid @enderror" placeholder="Windows / Android / iOS ...">
                                    @error('platform') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Kích thước (mới) --}}
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Kích thước</label>
                                    <input type="text" name="size" value="{{ old('size') }}"
                                        class="form-control @error('size') is-invalid @enderror" placeholder="VD: 150 MB">
                                    @error('size') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Mô Tả --}}
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô Tả</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-submit me-2">TẠO MỚI</button>
                                <a href="{{ route('admin.game-hack.index') }}" class="btn btn-cancel">HUỶ BỎ</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
