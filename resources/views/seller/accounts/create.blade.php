@extends('layouts.seller.app')
@section('title', 'Thêm Tài Khoản Game')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
        <div>
            <h2 class="text-2xl font-bold text-primary flex items-center gap-2">
                <img src="{{ asset('assets/img/icons/sales1.svg') }}" class="w-6 h-6" alt="icon">
                Thêm Tài Khoản Game
            </h2>
        </div>
        <a href="{{ route('seller.accounts.index') }}"
           class="mt-3 sm:mt-0 inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <img src="{{ asset('assets/img/icons/back.svg') }}" class="w-4 h-4 sm:w-5 sm:h-5" alt="back"> Quay Lại
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
        <form action="{{ route('seller.accounts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
{{-- Danh mục --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Danh Mục Game <span class="text-red-500">*</span>
    </label>

    <select name="game_category_id" id="categorySelect"
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('game_category_id') border-red-500 @enderror">
        @forelse ($categories as $category)
            <option value="{{ $category->id }}"
                data-type="{{ $category->type }}"
                data-price="{{ $category->price }}"
                {{ old('game_category_id', $account->game_category_id ?? null) == $category->id ? 'selected' : '' }}>
                {{ $category->name }} ({{ number_format($category->price) }} VND)
                @if ($category->is_global)
                    (Public)
                @endif
            </option>
        @empty
            <option disabled>Bạn chưa có danh mục nào</option>
        @endforelse
    </select>
    <input type="hidden" name="is_random_category" id="isRandomCategory" value="0">

    @error('game_category_id')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>


            {{-- Toggle thêm danh sách (ẩn khi random) --}}
            <div id="toggleMultiAccountArea" class="flex items-center gap-2">
                <input id="multiAccountToggle" type="checkbox" class="rounded text-primary focus:ring-primary">
                <label for="multiAccountToggle" class="text-sm text-gray-700 font-medium">Thêm Danh Sách Tài Khoản</label>
            </div>

            {{-- Nhập 1 tài khoản --}}
            <div id="singleAccountArea" class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tài Khoản <span class="text-red-500">*</span></label>
                    <input type="text" name="account_name" value="{{ old('account_name') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('account_name') border-red-500 @enderror">
                    @error('account_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="text" name="password" value="{{ old('password') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nhập danh sách --}}
            <div id="multiAccountArea" style="display:none;">
                <label class="block text-sm font-medium text-gray-700 mb-2">Danh Sách Tài Khoản</label>
                <textarea name="account_list" rows="5"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition"
                          placeholder="acc1|pass1&#10;acc2|pass2">{{ old('account_list') }}</textarea>
            </div>

            {{-- Giá tiền --}}
            <div id="priceArea">
                <label class="block text-sm font-medium text-gray-700 mb-2">Giá Tiền (VNĐ) <span class="text-red-500">*</span></label>
                <input type="number" name="price" id="account_price" value="{{ old('price', 50000) }}"
                       class="w-full sm:w-1/2 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('price') border-red-500 @enderror">
                <p id="priceNote" class="text-xs text-gray-500 mt-1 hidden italic">💡 Tài Khoản Random Sẽ Có Giá Cố Định, Không Thể Chỉnh Sửa</p>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Note (Badge thông tin) --}}
            <div id="noteArea">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Thông Tin Badge
                </label>
                <textarea name="note" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('note') border-red-500 @enderror"
                          placeholder="Mặc Định Để Trống Sẽ Là: &#10;ZING ID&#10;TRẮNG THÔNG TIN&#10;">{{ old('note') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">💡 Có Thể Để Trống Hoặc Ghi Mỗi Dòng 1 Badge</p>
                @error('note')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ảnh minh họa (ẩn khi random) --}}
            <div id="thumbUploadArea">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh Đại Diện <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-primary transition relative">
                    <input type="file" name="thumb" id="thumb" accept="image/*"
                           class="absolute inset-0 opacity-0 cursor-pointer"
                           onchange="previewImage(event)">
                    <div id="preview-container" class="flex flex-col items-center justify-center space-y-0">
                        <img id="preview-thumb" class="hidden w-40 rounded-lg border border-gray-200 aspect-video object-cover">
                        <div id="upload-placeholder" class="flex flex-col items-center space-y-2">
                            <img src="{{ asset('assets/img/icons/upload.svg') }}" class="w-10 opacity-70">
                        </div>
                    </div>
                </div>
                @error('thumb')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ảnh chi tiết (nhiều ảnh) - ẩn khi random --}}
<div id="imagesUploadArea">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Ảnh Preview (Chọn Nhiều Ảnh) <span class="text-red-500">*</span>
    </label>
    <div class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-primary transition relative">
        <input type="file" name="images[]" id="images" multiple accept="image/*"
               class="absolute inset-0 opacity-0 cursor-pointer"
               onchange="previewMultiImages(event)">
<div id="multi-preview-container" class="flex flex-wrap gap-3 justify-center">
    <p id="multi-upload-placeholder">
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
<input type="hidden" name="status" value="available">

            {{-- Nút hành động --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('seller.accounts.index') }}"
                   class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition"> <img src="{{ asset('assets/img/icons/back.svg') }}" class="w-5" alt="save"></a>
                <button type="submit"
                        class="px-5 py-2 bg-primary text-white font-medium rounded-lg hover:scale-105 transition">
                    <img src="{{ asset('assets/img/icons/save.svg') }}" class="w-5" alt="save">
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script toggle + preview --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('multiAccountToggle');
        const multiArea = document.getElementById('multiAccountArea');
        const singleArea = document.getElementById('singleAccountArea');
        const categorySelect = document.getElementById('categorySelect');
        const isRandomInput = document.getElementById('isRandomCategory');
        const toggleMultiAccountArea = document.getElementById('toggleMultiAccountArea');
        const thumbUploadArea = document.getElementById('thumbUploadArea');
        const imagesUploadArea = document.getElementById('imagesUploadArea');
        const accountPriceInput = document.getElementById('account_price');
        const priceNote = document.getElementById('priceNote');
        const noteArea = document.getElementById('noteArea');

        // Toggle single/multi account input
        toggle.addEventListener('change', function () {
            if (this.checked) {
                multiArea.style.display = 'block';
                singleArea.style.display = 'none';
            } else {
                multiArea.style.display = 'none';
                singleArea.style.display = 'grid';
            }
        });

        // Detect category type change
        function handleCategoryChange() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const categoryType = selectedOption ? selectedOption.dataset.type : '';
            const isRandom = categoryType === 'random';

            isRandomInput.value = isRandom ? '1' : '0';

            if (isRandom) {
                // Random category: hide single account, show multi, hide images
                toggleMultiAccountArea.style.display = 'none';
                singleArea.style.display = 'none';
                multiArea.style.display = 'block';
                toggle.checked = true;
                thumbUploadArea.style.display = 'none';
                imagesUploadArea.style.display = 'none';
                
                // Price treatment for random
                const price = selectedOption.dataset.price || 0;
                accountPriceInput.value = price;
                accountPriceInput.readOnly = true;
                accountPriceInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                priceNote.classList.remove('hidden');

                // Note treatment for random (badges taken from category desc)
                noteArea.classList.add('hidden');
            } else {
                // Normal category: show toggle, show images
                toggleMultiAccountArea.style.display = 'flex';
                thumbUploadArea.style.display = 'block';
                imagesUploadArea.style.display = 'block';
                
                // Normal price
                accountPriceInput.readOnly = false;
                accountPriceInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                priceNote.classList.add('hidden');

                // Normal note
                noteArea.classList.remove('hidden');

                // Restore toggle state
                if (toggle.checked) {
                    multiArea.style.display = 'block';
                    singleArea.style.display = 'none';
                } else {
                    multiArea.style.display = 'none';
                    singleArea.style.display = 'grid';
                }
            }
        }

        categorySelect.addEventListener('change', handleCategoryChange);
        // Run on page load
        handleCategoryChange();
    });

    function previewImage(event) {
        const file = event.target.files[0];
        const img = document.getElementById('preview-thumb');
        const placeholder = document.getElementById('upload-placeholder');
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
<script>
let selectedFiles = [];

function previewMultiImages(event) {
    const newFiles = Array.from(event.target.files);
    const container = document.getElementById('multi-preview-container');

    // Gộp ảnh cũ + mới (tránh trùng)
    newFiles.forEach(file => {
        if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
            selectedFiles.push(file);
        }
    });

    // Hiển thị preview
    renderPreview(container);

    // reset input để có thể chọn lại cùng file
    event.target.value = '';
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    const container = document.getElementById('multi-preview-container');
    renderPreview(container);
}

function renderPreview(container) {
    const placeholder = document.getElementById('multi-upload-placeholder');

    // ❗ Xoá chỉ các item preview, KHÔNG xoá placeholder
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

// ⚙️ Gửi toàn bộ selectedFiles khi submit form
document.querySelector('form').addEventListener('submit', function (e) {
    const input = document.getElementById('images');
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(f => dataTransfer.items.add(f));
    input.files = dataTransfer.files;
});
</script>

@endpush
@endsection
