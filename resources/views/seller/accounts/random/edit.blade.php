@extends('layouts.seller.app')
@section('title', 'Sửa Nhóm Random')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
        <div>
            <h2 class="text-2xl font-bold text-primary flex items-center gap-2">
                <img src="{{ asset('assets/img/icons/edit.svg') }}" class="w-5 invert" alt="save">
                Sửa Nhóm Random
            </h2>
        </div>
        <a href="{{ route('seller.accounts.random.index') }}"
           class="mt-3 sm:mt-0 inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <img src="{{ asset('assets/img/icons/back.svg') }}" class="w-4 h-4 sm:w-5 sm:h-5" alt="back"> Quay Lại
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
        <form action="{{ route('seller.accounts.random.update', $randomGroup->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Info Card --}}
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                <div class="flex items-center gap-4">
                    <div class="relative w-24 aspect-video rounded-lg overflow-hidden border border-gray-200">
                        <img src="{{ asset($randomGroup->thumb ?? 'assets/img/placeholder.png') }}"
                             alt="Thumbnail"
                             class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $randomGroup->category->name ?? 'N/A' }}</h3>
                        <p class="text-sm text-gray-500">
                            @php
                                $currentCount = count($randomGroup->accounts_data ?? []);
                            @endphp
                            Hiện có {{ $currentCount }} tài khoản
                        </p>
                    </div>
                </div>
            </div>

            {{-- Danh mục --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Danh Mục Random <span class="text-red-500">*</span>
                </label>
                <select name="game_category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition @error('game_category_id') border-red-500 @enderror">
                    @forelse ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('game_category_id', $randomGroup->game_category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                            @if ($category->is_global) (Public) @endif
                        </option>
                    @empty
                        <option disabled>Chưa có danh mục random nào</option>
                    @endforelse
                </select>
                @error('game_category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Giá tiền --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Giá Tiền (VNĐ) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="price" value="{{ old('price', $randomGroup->price) }}"
                       class="w-full sm:w-1/2 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Note (Badge) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Thông Tin Badge
                </label>
                <textarea name="note" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition @error('note') border-red-500 @enderror"
                          placeholder="Mỗi dòng 1 badge, ví dụ:&#10;ZING ID&#10;TRẮNG THÔNG TIN&#10;VIP">{{ old('note', $randomGroup->note) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">💡 Có thể để trống, mỗi dòng là 1 badge hiển thị</p>
                @error('note')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Danh sách tài khoản --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Danh Sách Tài Khoản <span class="text-red-500">*</span>
                </label>
                <textarea name="account_list" rows="10"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition font-mono text-sm @error('account_list') border-red-500 @enderror"
                          placeholder="acc1|pass1&#10;acc2|pass2&#10;acc3|pass3">{{ old('account_list', $accountList) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">💡 Mỗi dòng 1 tài khoản theo format: <code class="bg-gray-100 px-1 rounded">taikhoan|matkhau</code></p>
                @error('account_list')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ảnh đại diện --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Ảnh Đại Diện
                </label>
                <div class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-purple-500 transition relative">
                    <input type="file" name="thumb" id="thumb" accept="image/*"
                           class="absolute inset-0 opacity-0 cursor-pointer"
                           onchange="previewImage(event)">
                    <div id="preview-container" class="flex flex-col items-center justify-center space-y-3">
                        {{-- Preview ảnh mới --}}
                        <img id="preview-thumb" class="hidden w-40 rounded-lg border border-gray-200 aspect-video object-cover">
                        {{-- Ảnh cũ --}}
                        @if ($randomGroup->thumb)
                            <img id="current-thumb" src="{{ asset($randomGroup->thumb) }}" alt="Ảnh hiện tại"
                                 class="w-40 rounded-lg border border-gray-200 aspect-video object-cover">
                        @endif
                        <div id="upload-placeholder" class="{{ $randomGroup->thumb ? 'hidden' : 'flex' }} flex-col items-center space-y-2">
                            <img src="{{ asset('assets/img/icons/upload.svg') }}" class="w-10 opacity-70">
                            <p class="text-sm text-gray-500">Click để chọn ảnh mới</p>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">💡 Để trống nếu không muốn đổi ảnh</p>
                @error('thumb')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nút hành động --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('seller.accounts.random.index') }}"
                   class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                    <img src="{{ asset('assets/img/icons/back.svg') }}" class="w-5" alt="back">
                </a>
                <button type="submit"
                        class="px-6 py-2 text-white font-medium rounded-lg hover:scale-105 transition flex items-center gap-2"
                        style="background: linear-gradient(135deg, #8B5CF6, #EC4899);">
                    <img src="{{ asset('assets/img/icons/save.svg') }}" class="w-5 invert" alt="save">
                </button>
            </div>
        </form>
    </div>
</div>

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
@endpush
@endsection
