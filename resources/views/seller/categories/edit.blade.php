@extends('layouts.seller.app')
@section('title', 'Chỉnh Sửa Danh Mục Game')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
        <div>
            <h2 class="text-2xl font-bold text-primary flex items-center gap-2">
                <img src="{{ asset('assets/img/icons/edit.svg') }}" class="w-6 h-6" alt="icon">
                Chỉnh Sửa Danh Mục Game
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
        <form action="{{ route('seller.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Loại Danh Mục <span class="text-red-500">*</span>
                </label>
                <select name="type" id="category_type"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('type') border-red-500 @enderror">
                    <option value="play" {{ old('type', $category->type) == 'play' ? 'selected' : '' }}>Tài Khoản Play</option>
                    <option value="clone" {{ old('type', $category->type) == 'clone' ? 'selected' : '' }}>Tài Khoản Clone</option>
                    <option value="random" {{ old('type', $category->type) == 'random' ? 'selected' : '' }}>Tài Khoản Random</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tên danh mục --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tên Danh Mục <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" readonly
                       class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 text-gray-500 cursor-not-allowed">
            </div>

            {{-- Ảnh đại diện --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh Đại Diện</label>
                <div class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-primary transition relative">
                    <input type="file" name="thumbnail" id="thumbnail"
                        accept="image/jpeg,image/jpg,image/png,image/gif"
                        class="absolute inset-0 opacity-0 cursor-pointer"
                        onchange="previewImage(event)">

                    <div id="preview-container" class="flex flex-col items-center justify-center space-y-3">
                        @if ($category->thumbnail)
                            <img id="preview-thumb" src="{{ asset($category->thumbnail) }}" class="w-40 rounded-lg border border-gray-200 aspect-video object-cover">
                        @else
                            <img id="preview-thumb" class="hidden w-40 rounded-lg border border-gray-200 aspect-video object-cover">
                        @endif
                        <div id="upload-placeholder" class="{{ $category->thumbnail ? 'hidden' : 'flex' }} flex-col items-center space-y-2">
                            <img src="{{ asset('assets/img/icons/upload.svg') }}" class="w-10 opacity-70">
                        </div>
                    </div>
                </div>
                @error('thumbnail')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Trạng thái --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng Thái</label>
                <select name="active"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('active') border-red-500 @enderror">
                    <option value="1" {{ old('active', $category->active) == 1 ? 'selected' : '' }}>🟢 ON</option>
                    <option value="0" {{ old('active', $category->active) == 0 ? 'selected' : '' }}>🔴 OFF</option>
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
                        <input type="checkbox" name="is_global" value="1" {{ old('is_global', $category->is_global) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-primary transition-colors">Public</span>
                </label>
                @error('is_global')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- Mô tả --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mô Tả</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary transition @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nút hành động --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('seller.categories.index') }}"
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
