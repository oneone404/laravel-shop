@extends('layouts.admin.app')
@section('title', $title)
@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>{{ isset($notification) ? 'Chỉnh sửa thông báo' : 'Thêm thông báo mới' }}</h4>
        <h6>{{ isset($notification) ? 'Cập nhật thông tin thông báo' : 'Tạo icon bằng FA hoặc upload ảnh nếu không dùng FA' }}</h6>
      </div>
    </div>

    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
    @endif

    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}</div>
    @endif

    <div class="card">
      <div class="card-body">
        <!-- ✅ BỔ SUNG enctype CHO UPLOAD ẢNH -->
        <form
          action="{{ isset($notification) ? route('admin.settings.notifications.update', $notification->id) : route('admin.settings.notifications.store') }}"
          method="POST"
          enctype="multipart/form-data"
        >
          @csrf
          @if(isset($notification)) @method('PUT') @endif

          <div class="row">
            <!-- FA ICON INPUT -->
            <div class="col-lg-6 col-sm-6 col-12">
              <div class="form-group">
                <label>Biểu tượng FontAwesome (hoặc bỏ trống để dùng ảnh)</label>
                <input
                  type="text"
                  name="class_favicon"
                  id="faInput"
                  class="form-control"
                  value="{{ old('class_favicon', $notification->class_favicon ?? '') }}"
                  placeholder="bell, shield-alt, user-circle..."
                >
              </div>
            </div>

            <!-- ICON PREVIEW -->
            <div class="col-lg-6 col-sm-6 col-12">
              <div class="form-group">
                <label>Xem trước biểu tượng</label>
                <div class="icon-preview" style="padding:20px;border:1px solid #eee;border-radius:8px;text-align:center;background:#f9f9f9;">
                  <!-- ✅ Preview icon: render FA nếu có, nếu không render ẢNH cũ (nếu tồn tại) -->
                  @if (old('class_favicon', $notification->class_favicon ?? ''))
                    <i id="iconPreview" class="fas {{ old('class_favicon', $notification->class_favicon ?? '') }}" style="font-size:2.5rem;"></i>
                  @elseif ($notification->thumbnail ?? false)
                    <img id="iconPreviewImg" src="{{ asset($notification->thumbnail) }}" style="width:56px;height:56px;border-radius:8px;">
                  @else
                    <i id="iconPreview" class="fas fa-image" style="font-size:2.5rem; opacity:0.4;"></i>
                  @endif

                  <div style="margin-top:8px;"><code id="iconText">{{ old('class_favicon', $notification->class_favicon ?? 'image upload') }}</code></div>
                </div>
              </div>
            </div>

            <!-- TEXT CONTENT -->
            <div class="col-lg-12 col-12">
              <div class="form-group">
                <label>Nội dung thông báo <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control" rows="3">{{ old('content', $notification->content ?? '') }}</textarea>
              </div>
            </div>

            <!-- IMAGE UPLOAD WRAPPER -->
            <div class="col-lg-6 col-sm-6 col-12">
              <div class="form-group">
                <label>Hoặc upload ảnh biểu tượng (nếu không dùng FA)</label>
                <input
                  type="file"
                  name="thumb"
                  id="imgInput"
                  class="form-control"
                  accept="image/*"
                >
                @if ($notification->thumbnail ?? false)
                  <small>Ảnh icon hiện tại sẽ được giữ nếu bạn không upload ảnh mới</small>
                @endif
              </div>
            </div>

            <!-- SUBMIT -->
            <div class="col-lg-12">
              <button type="submit" class="btn btn-submit me-2">{{ isset($notification) ? 'Cập nhật' : 'Thêm thông báo' }}</button>
              <a href="{{ route('admin.settings.notifications') }}" class="btn btn-cancel">Hủy bỏ</a>
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
document.addEventListener('DOMContentLoaded', () => {
  const faInput  = document.getElementById('faInput');
  const imgInput = document.getElementById('imgInput');
  const iconEl   = document.getElementById('iconPreview');
  const textEl   = document.getElementById('iconText');
  const imgEl    = document.getElementById('iconPreviewImg');

  // Khi chọn ảnh mới → bỏ FA input
  imgInput?.addEventListener('change', () => {
    if (imgEl) imgEl.remove(); // xoá ảnh cũ khỏi preview nếu upload mới
    if (!iconEl) return;
    if (imgInput.files.length > 0) {
      faInput.value = '';
      const file = imgInput.files[0];
      const url  = URL.createObjectURL(file);
      frameUpdatePreview(url);
      textEl.textContent = 'image upload';
    }
  });

  // Khi gõ FA → clear ảnh preview
   // ✅ Không thể đặt required cứng → chỉ kiểm tra 1 TRONG 2 ở server
  faInput?.addEventListener('input', () => {
    if (imgEl) imgEl.remove(); // xoá ảnh nếu đang preview
    let c = faInput.value.trim();
    if (c && !c.startsWith('fa-')) c = 'fa-' + c;
    iconEl?.setAttribute('class', 'fas ' + (c || 'fa-image'));
    textEl.textContent = c || '';
  });

  // Hàm apply ảnh preview
  function frameUpdatePreview(url) {
    if (iconEl) {
      iconEl.style.display = 'none';
    }

    if (imgEl) {
      imgEl.style.display = 'block';
      imgEl.src = url;
    } else {
      const newImg = document.createElement('img');
      newImg.id = "iconPreviewImg";
      newImg.src = url;
      newImg.style.cssText = "width:56px;height:56px;border-radius:8px;margin:auto;";
      document.querySelector('.icon-preview').prepend(newImg);
    }
  }
});
</script>
@endpush
