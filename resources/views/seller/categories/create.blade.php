@extends('layouts.seller.app')
@section('title', 'Thêm Danh Mục Game')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
        <div>
            <h2 class="text-2xl font-bold text-primary flex items-center gap-2">
                <img src="{{ asset('assets/img/icons/product.svg') }}" class="w-6 h-6" alt="icon">
                Thêm Danh Mục Game
            </h2>
        </div>
        <a href="{{ route('seller.categories.index') }}"
           class="mt-3 sm:mt-0 inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <img src="{{ asset('assets/img/icons/back.svg') }}"
                 class="w-4 h-4 sm:w-5 sm:h-5"
                 alt="back">Quay Lại
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
        <form action="{{ route('seller.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Loại Danh Mục <span class="text-red-500">*</span>
                </label>
                <select name="type" id="category_type"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('type') border-red-500 @enderror">
                    <option value="play" {{ old('type') == 'play' ? 'selected' : '' }}>Tài Khoản Play</option>
                    <option value="clone" {{ old('type') == 'clone' ? 'selected' : '' }}>Tài Khoản Clone</option>
                    <option value="random" {{ old('type') == 'random' ? 'selected' : '' }}>Tài Khoản Random</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tên danh mục --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tên Danh Mục <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="(VD: ACC 1XX)"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Trạng thái --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng Thái</label>
                <select name="active"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('active') border-red-500 @enderror">
                    <option value="1" {{ old('active') == 1 ? 'selected' : '' }}>🟢 ON</option>
                    <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>🔴 OFF</option>
                </select>
                @error('active')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if (auth()->user()->role === 'admin')
            {{-- Danh mục dùng chung (Dành cho Admin) --}}
            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <div class="relative inline-flex items-center">
                        <input type="checkbox" name="is_global" value="1" {{ old('is_global') ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-primary transition-colors">Danh Mục Dùng Chung (Tất cả Seller đều thấy)</span>
                </label>
                @error('is_global')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- Ảnh đại diện --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Ảnh Đại Diện <span class="text-red-500">*</span>
                </label>
                <div class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-primary transition relative">
                    <input type="file" name="thumbnail" id="thumbnail"
                        accept="image/jpeg,image/jpg,image/png,image/gif"
                        class="absolute inset-0 opacity-0 cursor-pointer"
                        onchange="previewImage(event)">

                    <div id="preview-container" class="flex flex-col items-center justify-center space-y-0">
                        <img id="preview-thumb" class="hidden w-40 rounded-lg border border-gray-200 aspect-video object-cover">
                        <div id="upload-placeholder" class="flex flex-col items-center space-y-2">
                            <img src="{{ asset('assets/img/icons/upload.svg') }}" class="w-10 opacity-70">
                        </div>
                    </div>
                </div>
                @error('thumbnail')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mô tả --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Mô Tả <span class="text-red-500">*</span>
                </label>
                <textarea name="description" rows="4" placeholder="VD: Clone Tiền Sao - Kim Cương..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nút hành động --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('seller.categories.index') }}"
                   class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                   <img src="{{ asset('assets/img/icons/back.svg') }}" class="w-5" alt="back">
                </a>

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
@endpush
@endsection
