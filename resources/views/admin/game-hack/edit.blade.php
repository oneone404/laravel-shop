@extends('layouts.admin.app')
@section('title', 'Chỉnh Sửa Game')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Chỉnh Sửa Game</h4>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.game-hack.update', $gameHack->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">

        {{-- Tên Game --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Tên Game <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $gameHack->name) }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Phiên bản --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Phiên Bản</label>
                <input type="text" name="version" value="{{ old('version', $gameHack->version) }}"
                       class="form-control @error('version') is-invalid @enderror"
                       placeholder="VD: 1.0.0 hoặc V2.5">
                @error('version') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Logo --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Logo (URL)</label>
                <input type="text" name="logo" value="{{ old('logo', $gameHack->logo) }}"
                       class="form-control @error('logo') is-invalid @enderror">
                <div class="mt-2">
                    <label>Hoặc Tải lên Logo mới trực tiếp</label>
                    <input type="file" name="logo_file" class="form-control @error('logo_file') is-invalid @enderror">
                    @error('logo_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                @if($gameHack->logo)
                    <div class="mt-2">
                        <img src="{{ $gameHack->logo }}" alt="logo preview" style="max-height: 50px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                @endif
                @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Thumbnail (mới) --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Thumbnail (URL – dùng cho danh sách)</label>
                <input type="text" name="thumbnail" value="{{ old('thumbnail', $gameHack->thumbnail) }}"
                       class="form-control @error('thumbnail') is-invalid @enderror">
                <div class="mt-2">
                    <label>Hoặc Tải lên Thumbnail mới trực tiếp</label>
                    <input type="file" name="thumbnail_file" class="form-control @error('thumbnail_file') is-invalid @enderror">
                    @error('thumbnail_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if($gameHack->thumbnail)
                    <small class="text-muted">Xem thử: </small>
                    <div style="margin-top:6px">
                        <img src="{{ $gameHack->thumbnail }}" alt="thumb" style="max-width:180px;border-radius:8px">
                    </div>
                @endif
            </div>
        </div>

        {{-- Images (mới) --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Ảnh chi tiết (mỗi dòng 1 URL) — dùng cho trang chi tiết</label>
                <textarea name="images" id="images_textarea" rows="6"
                    class="form-control @error('images') is-invalid @enderror"
                    placeholder="https://cdn.site/img1.jpg&#10;https://cdn.site/img2.jpg">{{ old('images', is_array($gameHack->images) ? implode("\n", $gameHack->images) : '') }}</textarea>
                <div class="mt-2">
                    <label>Tải lên ảnh chi tiết mới (sẽ được thêm vào danh sách trên sau khi lưu)</label>
                    <input type="file" name="image_files[]" multiple class="form-control @error('image_files.*') is-invalid @enderror">
                    @error('image_files.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if(!empty($gameHack->images) && is_array($gameHack->images))
                    <small class="text-muted d-block mt-2">Đang có {{ count($gameHack->images) }} ảnh.</small>
                @endif
            </div>
        </div>

        {{-- Link Tải VNG --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Link Tải VNG</label>
                <input type="text" name="download_link" value="{{ old('download_link', $gameHack->download_link) }}"
                       class="form-control @error('download_link') is-invalid @enderror">
                @error('download_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Link Tải Global --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Link Tải Global <small class="text-muted">(chỉ dành cho Play Together)</small></label>
                <input type="text" name="download_link_global" value="{{ old('download_link_global', $gameHack->download_link_global) }}"
                       class="form-control @error('download_link_global') is-invalid @enderror"
                       placeholder="Để trống nếu không có bản Global">
                @error('download_link_global') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- API --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>API</label>
                <input type="text" name="api_hack" value="{{ old('api_hack', $gameHack->api_hack) }}"
                       class="form-control @error('api_hack') is-invalid @enderror">
                @error('api_hack') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- PACKAGE NAME --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>PACKAGE NAME</label>
                <input type="text" name="api_type" value="{{ old('api_type', $gameHack->api_type) }}"
                       class="form-control @error('api_type') is-invalid @enderror">
                @error('api_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Số Link --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Số Link</label>
                <input type="number" name="solink" value="{{ old('solink', $gameHack->solink) }}"
                       class="form-control @error('solink') is-invalid @enderror">
                @error('solink') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Trạng Thái --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Trạng Thái</label>
                <select name="active" class="form-control @error('active') is-invalid @enderror">
                    <option value="1" {{ old('active', $gameHack->active) == 1 ? 'selected' : '' }}>ON</option>
                    <option value="0" {{ old('active', $gameHack->active) == 0 ? 'selected' : '' }}>OFF</option>
                </select>
                @error('active') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Platform (mới) --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Nền tảng</label>
                <input type="text" name="platform" value="{{ old('platform', $gameHack->platform) }}"
                       class="form-control @error('platform') is-invalid @enderror"
                       placeholder="Windows / Android / iOS ...">
                @error('platform') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Size (mới) --}}
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Kích thước</label>
                <input type="text" name="size" value="{{ old('size', $gameHack->size) }}"
                       class="form-control @error('size') is-invalid @enderror"
                       placeholder="VD: 150 MB">
                @error('size') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Mô Tả --}}
        <div class="col-lg-12">
            <div class="form-group">
                <label>Mô Tả</label>
                <textarea name="description" rows="4"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $gameHack->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="col-lg-12">
            <button type="submit" class="btn btn-submit me-2">CẬP NHẬT</button>
            <a href="{{ route('admin.game-hack.index') }}" class="btn btn-cancel">HUỶ BỎ</a>
        </div>
    </div>
</form>

                </div>
            </div>

        </div>
    </div>
@endsection
