@extends('layouts.admin.app')
@section('title', $title)
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Chỉnh sửa gói dịch vụ</h4>
                    <h6>Cập nhật thông tin gói dịch vụ</h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Dịch vụ <span class="text-danger">*</span></label>
                                    <select name="game_service_id"
                                        class="select @error('game_service_id') is-invalid @enderror">
                                        <option value="">Chọn dịch vụ</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                {{ old('game_service_id', $package->game_service_id) == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }}
                                                ({{ $service->type == 'gold' ? 'Bán vàng' : ($service->type == 'gem' ? 'Bán ngọc' : 'Cày thuê') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('game_service_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên gói dịch vụ <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $package->name) }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Ví dụ: Gói 1000 vàng, Gói 100 ngọc...">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Giá (VND) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" value="{{ old('price', $package->price) }}"
                                        class="form-control @error('price') is-invalid @enderror"
                                        placeholder="Ví dụ: 100000">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12" id="estimated-time-group">
                                <div class="form-group">
                                    <label>Thời gian ước tính (phút)</label>
                                    <input type="number" name="estimated_time"
                                        value="{{ old('estimated_time', $package->estimated_time) }}"
                                        class="form-control @error('estimated_time') is-invalid @enderror"
                                        placeholder="Ví dụ: 60">
                                    @error('estimated_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái <span class="text-danger">*</span></label>
                                    <select name="active" class="select @error('active') is-invalid @enderror">
                                        <option value="1"
                                            {{ old('active', $package->active) == '1' ? 'selected' : '' }}>Hiển thị
                                        </option>
                                        <option value="0"
                                            {{ old('active', $package->active) == '0' ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                    @error('active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả gói dịch vụ</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                                        placeholder="Mô tả chi tiết về gói dịch vụ">{{ old('description', $package->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Ảnh đại diện</label>
                                    <div class="image-upload">
                                        <input type="file" name="thumbnail" id="thumbInput"
                                            class="form-control @error('thumbnail') is-invalid @enderror"
                                            accept="image/*"
                                            onchange="previewImage(this, 'preview-thumb', 'thumbTitle')">
                                        @error('thumbnail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <div class="image-uploads" id="thumbBox">
                                            <img src="{{ asset('assets/img/icons/upload.svg') }}" alt="Upload Image" style="max-width: 200px; max-height: 200px;">
                                            <h4>Kéo thả hoặc chọn ảnh để tải lên</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <x-preview-image title="" :image="asset('storage/' . $package->thumbnail)" />
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-submit me-2">Cập nhật</button>
                                <a href="{{ route('admin.packages.service', $package->game_service_id) }}"
                                    class="btn btn-cancel">Hủy bỏ</a>
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
    const preview = document.getElementById(previewId);
    const file = input.files && input.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            const title = preview.nextElementSibling;
            if (title && title.tagName.toLowerCase() === 'h4') {
                title.textContent = 'Ảnh xem trước';
            }
            // Bỏ chọn “xóa ảnh” nếu có
            const removeCk = document.getElementById('removeThumbCheck');
            if (removeCk) removeCk.checked = false;
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "{{ asset('assets/img/icons/upload.svg') }}";
        const title = preview.nextElementSibling;
        if (title && title.tagName.toLowerCase() === 'h4') {
            title.textContent = 'Kéo thả hoặc chọn ảnh để tải lên';
        }
    }
}

// Nếu tick “xóa ảnh hiện tại” → ẩn preview
document.addEventListener('DOMContentLoaded', function() {
  const removeCk = document.getElementById('removeThumbCheck');
  const preview  = document.getElementById('preview-thumb');
  const title    = preview ? preview.nextElementSibling : null;
  if (removeCk) {
    removeCk.addEventListener('change', function() {
      if (this.checked) {
        preview.style.display = 'none';
        if (title && title.tagName.toLowerCase() === 'h4') {
          title.textContent = 'Ảnh sẽ bị xóa khi lưu';
        }
      } else {
        preview.style.display = 'block';
        if (title && title.tagName.toLowerCase() === 'h4') {
          title.textContent = 'Ảnh hiện tại';
        }
      }
    });
  }
});
</script>
@endpush
