@extends('layouts.seller.app')
@section('title', 'Chỉnh Sửa Tài Khoản Game')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
        <div>
            <h2 class="text-2xl font-bold text-primary flex items-center gap-2">
                <img src="{{ asset('assets/img/icons/edit.svg') }}" class="w-6 h-6" alt="icon">
                Chỉnh Sửa Tài Khoản Game
            </h2>
        </div>
        <a href="{{ route('seller.accounts.index') }}"
           class="mt-3 sm:mt-0 inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <img src="{{ asset('assets/img/icons/back.svg') }}" class="w-4 h-4 sm:w-5 sm:h-5" alt="back"> Quay Lại
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
        <form action="{{ route('seller.accounts.update', $account->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

{{-- Danh mục --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Danh Mục Game <span class="text-red-500">*</span>
    </label>

    <select name="game_category_id"
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('game_category_id') border-red-500 @enderror">
        @forelse ($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('game_category_id', $account->game_category_id ?? null) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}{{ $category->is_global ? ' (Public)' : '' }}
            </option>
        @empty
            <option disabled>Chưa có danh mục nào khả dụng</option>
        @endforelse
    </select>

    @error('game_category_id')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>


            {{-- Tài khoản & Mật khẩu --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên Tài Khoản <span class="text-red-500">*</span></label>
                    <input type="text" name="account_name" value="{{ old('account_name', $account->account_name) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="text" name="password" value="{{ old('password', $account->password) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition">
                </div>
            </div>

            {{-- Giá tiền & Trạng thái --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Giá Tiền (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $account->price) }}"
                           {{ $account->category->type === 'random' ? 'readonly' : '' }}
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition {{ $account->category->type === 'random' ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}">
                    @if($account->category->type === 'random')
                        <p class="text-xs text-gray-500 mt-1 italic">💡 Hệ thống lấy giá từ danh mục Random.</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng Thái</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition">
                        <option value="available" {{ old('status', $account->status) == 'available' ? 'selected' : '' }}>AVAILABLE</option>
                        <option value="sold" {{ old('status', $account->status) == 'sold' ? 'selected' : '' }}>SOLD</option>
                    </select>
                </div>
            </div>

            {{-- Note (Badge thông tin) --}}
            @if($account->category->type !== 'random')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Thông Tin Badge
                </label>
                <textarea name="note" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('note') border-red-500 @enderror"
                          placeholder="Mặc Định Để Trống Sẽ Là: &#10;ZING ID&#10;TRẮNG THÔNG TIN&#10;">{{ old('note', $account->note) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">💡 Có Thể Để Trống Hoặc Ghi Mỗi Dòng 1 Badge</p>
                @error('note')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- Ảnh minh họa --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh Đại Diện</label>
                <div class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-primary transition relative">
                    <input type="file" name="thumb" id="thumb" accept="image/*"
                           class="absolute inset-0 opacity-0 cursor-pointer"
                           onchange="previewImage(event)">
                    <div id="preview-container" class="flex flex-col items-center justify-center space-y-0">
                        {{-- Preview ảnh mới --}}
                        <img id="preview-thumb" class="hidden w-40 rounded-lg border border-gray-200 aspect-video object-cover">
                        {{-- Ảnh cũ --}}
                        @if ($account->thumb)
                            <img id="current-thumb" src="{{ asset($account->thumb) }}" alt="Ảnh hiện tại"
                                 class="w-40 rounded-lg border border-gray-200 aspect-video object-cover">
                        @endif
                        <div id="upload-placeholder" class="flex flex-col items-center space-y-2">
                        </div>
                    </div>
                </div>
            </div>

{{-- Ảnh chi tiết (nhiều ảnh) --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Ảnh Acc Game (Có Thể Chọn Nhiều Ảnh)
    </label>
    <div class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-primary transition relative">
        <input type="file" name="images[]" id="images" multiple accept="image/*"
               class="absolute inset-0 opacity-0 cursor-pointer"
               onchange="previewMultiImages(event)">
        <div id="multi-preview-container" class="flex flex-wrap gap-3 justify-center">
            {{-- Ảnh cũ --}}
            @if ($account->images)
                @php
                    $images = $account->images ?? [];
                @endphp
                @foreach ($images as $index => $img)
                    <div class="relative inline-block old-image">
                        <img src="{{ asset($img) }}" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                        <button type="button"
                                onclick="removeOldImage({{ $index }})"
                                class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                            &times;
                        </button>
                        <input type="hidden" name="keep_images[]" value="{{ $img }}">
                    </div>
                @endforeach
            @endif

            {{-- Placeholder upload --}}
            <p id="multi-upload-placeholder" class="{{ isset($images) && count($images) ? 'hidden' : '' }}">
                <img src="{{ asset('assets/img/icons/upload.svg') }}" class="w-10 opacity-70">
            </p>
        </div>
    </div>
    @error('images.*')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<input type="hidden" name="server" value="13">
<input type="hidden" name="registration_type" value="virtual">
<input type="hidden" name="planet" value="earth">
<input type="hidden" name="earring" value="1">

            {{-- Nút hành động --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('seller.accounts.index') }}"
                   class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition"><img src="{{ asset('assets/img/icons/back.svg') }}" class="w-5" alt="save"></a>
                <button type="submit"
                        class="px-5 py-2 bg-primary text-white font-medium rounded-lg hover:scale-105 transition">
                    <img src="{{ asset('assets/img/icons/save.svg') }}" class="w-5" alt="save">
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script preview ảnh --}}
@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const img = document.getElementById('preview-thumb');
        const current = document.getElementById('current-thumb');
        const placeholder = document.getElementById('upload-placeholder');
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
                if (current) current.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
<script>
let selectedFiles = [];
let removedOldIndexes = [];

function previewMultiImages(event) {
    const newFiles = Array.from(event.target.files);
    const container = document.getElementById('multi-preview-container');
    const placeholder = document.getElementById('multi-upload-placeholder');

    newFiles.forEach(file => {
        if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
            selectedFiles.push(file);
        }
    });

    renderPreview(container, placeholder);
    event.target.value = '';
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    const container = document.getElementById('multi-preview-container');
    const placeholder = document.getElementById('multi-upload-placeholder');
    renderPreview(container, placeholder);
}

// 🗑️ Xóa ảnh cũ
function removeOldImage(index) {
    const oldDivs = document.querySelectorAll('.old-image');
    if (oldDivs[index]) {
        oldDivs[index].remove();
    }
}

// 🧩 Hiển thị preview ảnh mới
function renderPreview(container, placeholder) {
    container.querySelectorAll('.multi-preview-item').forEach(el => el.remove());

    if (selectedFiles.length === 0) {
        if (placeholder) placeholder.classList.remove('hidden');
        return;
    } else {
        if (placeholder) placeholder.classList.add('hidden');
    }

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative inline-block multi-preview-item';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-20 h-20 object-cover rounded-lg border border-gray-200';

            const removeBtn = document.createElement('button');
            removeBtn.innerHTML = '&times;';
            removeBtn.type = 'button';
            removeBtn.className =
                'absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs';
            removeBtn.onclick = () => removeImage(index);

            wrapper.appendChild(img);
            wrapper.appendChild(removeBtn);
            container.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });
}

// ✅ Khi submit form → gộp lại ảnh mới
document.querySelector('form').addEventListener('submit', function (e) {
    const input = document.getElementById('images');
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(f => dataTransfer.items.add(f));
    input.files = dataTransfer.files;
});
</script>

@endpush
@endsection
