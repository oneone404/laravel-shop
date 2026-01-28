@extends('layouts.seller.app')
@section('title', 'Quản Lý Danh Mục Game')

@section('content')
<div class="space-y-8">
{{-- Flash messages --}}
@if (session('success'))
    <div id="alert-success"
         class="flex items-center justify-between bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 animate-fadeIn">
        <div class="flex items-center gap-2">
            <img src="{{ asset('assets/img/icons/check.svg') }}" class="w-5 h-5" alt="success">
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
            ✕
        </button>
    </div>
@endif

@if (session('error'))
    <div id="alert-error"
         class="flex items-center justify-between bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 animate-fadeIn">
        <div class="flex items-center gap-2">
            <img src="{{ asset('assets/img/icons/error.svg') }}" class="w-5 h-5" alt="error">
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
            ✕
        </button>
    </div>
@endif
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-primary flex items-center gap-2">
                Danh Mục Game
            </h2>
            <p class="text-gray-500">Quản Lý Danh Mục Game</p>
        </div>

        <a href="{{ route('seller.categories.create') }}"
           class="mt-3 sm:mt-0 inline-flex items-center gap-2 bg-gradient-to-r from-primary to-secondary text-white font-medium px-4 py-2 rounded-lg hover:scale-105 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
            </svg>
            Thêm Danh Mục
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="p-5 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <img src="{{ asset('assets/img/icons/product.svg') }}" class="w-5 h-5" alt="icon">
                Danh Sách Danh Mục
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-gradient-to-r from-green-50 to-orange-50 text-gray-700 uppercase text-xs font-semibold border-b">
                    <tr>
                        <th class="px-5 py-3 text-center">#</th>
                        <th class="px-5 py-3 text-center">Loại Danh Mục</th>
                        <th class="px-5 py-3 text-left">Tên danh mục</th>
                        <th class="px-5 py-3 text-center">Ảnh</th>
                        <th class="px-5 py-3 text-center">Trạng thái</th>
                        <th class="px-5 py-3 text-center">Ngày tạo</th>
                        <th class="px-5 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $key => $category)
                        <tr class="hover:bg-gray-50 transition-all duration-150">
                            <td class="px-5 py-3 text-center text-gray-500 font-medium">{{ $key + 1 }}</td>
                            <td class="px-5 py-3 text-center">
                                @if ($category->type === 'play')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                        Tài Khoản Play
                                    </span>
                                @elseif ($category->type === 'clone')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                        Tài Khoản Clone
                                    </span>
                                @elseif ($category->type === 'random')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                        Tài Khoản Random
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                                        Khác
                                    </span>
                                @endif
                            </td>

                           <td class="px-5 py-3 font-semibold text-gray-900 truncate max-w-[200px]">
                                {{ $category->name }}
                                @if ($category->is_global)
                                    <span class="text-gray-700 text-xs font-medium">(Public)</span>
                                @endif
                            </td>

                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center">
                                    <div class="relative w-32 sm:w-40 aspect-video rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                        <img src="{{ asset($category->thumbnail) }}"
                                            alt="{{ $category->name }}"
                                            class="absolute inset-0 w-full h-full object-cover">
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if ($category->active)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        ON
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                        OFF
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center text-gray-500">{{ $category->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a class="me-3" href="{{ route('seller.categories.edit', $category->id) }}">
                                        <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                                    </a>
                                    <a class="me-3 confirm-delete" href="javascript:void(0);" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-id="{{ $category->id }}">
                                        <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="img">
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500 italic">Chưa Có Danh Mục</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Xoá --}}
<div id="deleteModal" class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-11/12 sm:w-96 animate-fadeIn">
        <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-red-500"></i> Xác Nhận
        </h3>
        <p class="text-gray-600 mb-6 text-sm leading-relaxed">
            Bạn Có Chắn Chắn Muốn Xoá Danh Mục Này?
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Huỷ</button>
            <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Xoá</button>
        </div>
    </div>
</div>
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    let selectedId = null;

    // Gán ID khi bấm nút xoá
    document.querySelectorAll('.confirm-delete').forEach(btn => {
        btn.addEventListener('click', () => {
            selectedId = btn.dataset.id;
            document.getElementById('deleteModal').classList.remove('hidden');
        });
    });

    // Đóng modal
    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Khi nhấn "Xoá" trong modal
    document.getElementById('confirmDelete').addEventListener('click', () => {
        if (!selectedId) return;
        closeModal();

        // Nếu chưa có form ẩn thì tạo
        let form = document.getElementById('deleteForm');
        if (!form) {
            form = document.createElement('form');
            form.id = 'deleteForm';
            form.method = 'POST';
            form.style.display = 'none';
            form.innerHTML = `
                @csrf
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
        }

        form.action = `/seller/categories/delete/${selectedId}`;
        form.submit(); // 🔥 Gửi form -> Controller redirect + flash message
    });
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn { animation: fadeIn 0.25s ease-out; }
</style>
@endpush
