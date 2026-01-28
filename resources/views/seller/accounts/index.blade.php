@extends('layouts.seller.app')
@section('title', 'Quản Lý Tài Khoản')

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
                Danh Sách Tài Khoản
            </h2>
            <p class="text-gray-500">Quản Lý Tài Khoản Game</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-3 sm:mt-0">
            {{-- Nút xoá nhiều --}}
            <button id="deleteSelectedBtn"
    class="hidden items-center gap-2 bg-gradient-to-r from-orange-400 to-red-500 text-white font-semibold px-4 py-2 rounded-lg hover:scale-[1.03] transition-all">
    <img src="{{ asset('assets/img/icons/delete3.svg') }}" class="w-4 h-4 invert" alt="delete">
    Xoá Đã Chọn
</button>
<button id="exportSelectedBtn"
    class="hidden items-center gap-2 bg-gradient-to-r from-green-400 to-emerald-500 text-white font-semibold px-4 py-2 rounded-lg hover:scale-[1.03] transition-all">
    <img src="{{ asset('assets/img/icons/download2.svg') }}" class="w-4 h-4 invert" alt="export">
    EXPORT CSV
</button>


            {{-- Nút thêm --}}
            <a href="{{ route('seller.accounts.create') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-secondary text-white font-medium px-4 py-2 rounded-lg hover:scale-105 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Thêm Tài Khoản
            </a>
        </div>
    </div>

    {{-- Table Tài Khoản Play/Clone --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="p-5 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <img src="{{ asset('assets/img/icons/sales1.svg') }}" class="w-5 h-5" alt="icon">
                Danh Sách Tài Khoản (Play/Clone)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
{{-- TABLE HEADER --}}
<thead class="bg-gradient-to-r from-green-50 to-orange-50 text-gray-700 uppercase text-xs font-semibold border-b">
    <tr>
        <th class="px-5 py-3 text-center">
            <input type="checkbox" id="selectAll" class="w-4 h-4 accent-primary cursor-pointer">
        </th>
        <th class="px-5 py-3 text-center">#</th>

        {{-- DANH MỤC - mở modal lọc --}}
<th class="px-5 py-3 text-left cursor-pointer hover:text-primary transition" onclick="openFilterModal('category')">
    <div class="inline-flex items-center gap-1">
        <span>Danh Mục</span>
        <img src="{{ asset('assets/img/icons/bottom.svg') }}" class="w-3.5 h-3.5 opacity-70 hover:opacity-100 transition" alt="filter">
    </div>
</th>


        <th class="px-5 py-3 text-left">Tài Khoản</th>
        <th class="px-5 py-3 text-center">Giá Tiền</th>

        {{-- TRẠNG THÁI - mở modal lọc --}}
        <th class="px-5 py-3 text-center cursor-pointer hover:text-primary transition" onclick="openFilterModal('status')">
    <div class="inline-flex items-center gap-1">
        <span>Trạng Thái</span>
        <img src="{{ asset('assets/img/icons/bottom.svg') }}" class="w-3.5 h-3.5 opacity-70 hover:opacity-100 transition" alt="filter">
    </div>
        </th>

        <th class="px-5 py-3 text-center">Hình Ảnh</th>
        <th class="px-5 py-3 text-center">Ngày Đăng</th>
        <th class="px-5 py-3 text-center">Thao Tác</th>
    </tr>
</thead>


                <tbody class="divide-y divide-gray-100">
                    @forelse ($accounts as $key => $account)
                        <tr class="hover:bg-gray-50 transition-all duration-150">
                            <td class="px-5 py-3 text-center">
                                <input type="checkbox" class="account-checkbox w-4 h-4 accent-primary cursor-pointer"
                                       value="{{ $account->id }}">
                            </td>
                            <td class="px-5 py-3 text-center text-gray-500 font-medium">{{ $key + 1 }}</td>
                            <td class="px-5 py-3 font-semibold text-gray-900">
                                <a href="{{ route('seller.categories.edit', $account->category->id) }}"
                                class="text-primary hover:underline">
                                    {{ $account->category->name }}
                                    @if ($account->category->is_global)
                                        <span class="text-xs font-medium text-gray-700">(Public)</span>
                                    @endif
                                </a>
                            </td>

                            <td class="px-5 py-3 font-medium text-gray-800">{{ $account->account_name }}</td>
                             <td class="px-5 py-3 text-center text-green-700 font-semibold">
                                {{ number_format($account->price) }} <span class="text-xs text-gray-500">VNĐ</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if ($account->status === 'available')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        AVAILABLE
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                        SOLD
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center">
                                    <div class="relative w-28 sm:w-32 aspect-video rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                        <img src="{{ asset($account->thumb) }}"
                                             alt="{{ $account->account_name }}"
                                             class="absolute inset-0 w-full h-full object-cover">
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3 text-center text-gray-500 text-xs">
                {{ $account->created_at->format('d/m/Y H:i') }}
            </td>


                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('seller.accounts.edit', $account->id) }}"
                                       class="hover:scale-110 transition">
                                        <img src="{{ asset('assets/img/icons/edit.svg') }}" class="w-4 h-4" alt="edit">
                                    </a>
                                    <button class="confirm-delete hover:scale-110 transition"
                                            data-id="{{ $account->id }}">
                                        <img src="{{ asset('assets/img/icons/delete.svg') }}" class="w-4 h-4" alt="delete">
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-gray-500 italic">Chưa Có Tài Khoản Nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
@if ($accounts->hasPages())
<div class="p-5 border-t bg-white rounded-b-xl flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-6">

    {{-- Text --}}
    <p class="text-xs sm:text-sm text-gray-500">
        Show
        <span class="font-semibold text-primary">{{ $accounts->firstItem() }}</span> -
        <span class="font-semibold text-primary">{{ $accounts->lastItem() }}</span> /
        Tổng <span class="font-semibold text-secondary">{{ $accounts->total() }}</span> Tài Khoản
    </p>

    {{-- Pagination Buttons --}}
    <div class="flex items-center space-x-1">
        {{-- Previous --}}
        @if ($accounts->onFirstPage())
            <span class="px-3 py-1.5 text-gray-400 bg-gray-100 rounded-md text-xs sm:text-sm cursor-not-allowed">
                <img src="{{ asset('assets/img/icons/chevron-left.svg') }}"
             class="w-5 h-5 opacity-50"
             alt=">">
            </span>
        @else
            <a href="{{ $accounts->previousPageUrl() }}"
               class="px-3 py-1.5 text-gray-700 bg-gray-50 hover:bg-primary hover:text-white rounded-md text-xs sm:text-sm font-medium transition">
                <img src="{{ asset('assets/img/icons/chevron-left.svg') }}"
             class="w-5 h-5 opacity-50"
             alt=">">
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($accounts->getUrlRange(max($accounts->currentPage() - 2, 1), min($accounts->currentPage() + 2, $accounts->lastPage())) as $page => $url)
            @if ($page == $accounts->currentPage())
                <span class="px-3 py-1.5 bg-primary text-white rounded-md text-xs sm:text-sm font-semibold">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}"
                   class="px-3 py-1.5 bg-gray-50 text-gray-700 hover:bg-secondary hover:text-white rounded-md text-xs sm:text-sm font-medium transition">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($accounts->hasMorePages())
            <a href="{{ $accounts->nextPageUrl() }}"
               class="px-3 py-1.5 text-gray-700 bg-gray-50 hover:bg-primary hover:text-white rounded-md text-xs sm:text-sm font-medium transition">
                <img src="{{ asset('assets/img/icons/chevron-right2.svg') }}"
             class="w-5 h-5 opacity-50"
             alt=">">
            </a>
        @else
            <span class="px-3 py-1.5 text-gray-400 bg-gray-100 rounded-md text-xs sm:text-sm cursor-not-allowed">
                <img src="{{ asset('assets/img/icons/chevron-right2.svg') }}"
             class="w-5 h-5 opacity-50"
             alt=">">
            </span>
        @endif
    </div>
</div>
@endif

    </div>
</div>

{{-- Modal Xoá --}}
<div id="deleteModal" class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-11/12 sm:w-96 animate-fadeIn">
        <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
            <img src="{{ asset('assets/img/icons/warning.svg') }}" class="w-5 h-5" alt="warning">
            Xác Nhận
        </h3>
        <p id="deleteMessage" class="text-gray-600 mb-6 text-sm leading-relaxed">
            Bạn Có Chắc Chắn Muốn Xoá Tài Khoản?
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Huỷ</button>
            <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Xoá</button>
        </div>
    </div>
</div>

{{-- Modal Filter --}}
<div id="filterModal" class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-11/12 sm:w-96 animate-fadeIn relative">
        <button onclick="closeFilterModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            ✕
        </button>

        <h3 id="filterTitle" class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <img src="{{ asset('assets/img/icons/filter.svg') }}" class="w-5 h-5" alt="filter">
            Bộ Lọc
        </h3>

        <form id="filterForm" method="GET" action="{{ route('seller.accounts.index') }}">
            {{-- Giữ nguyên query cũ để không mất khi chuyển trang --}}
            @if (request()->has('page'))
                <input type="hidden" name="page" value="{{ request('page') }}">
            @endif

<div id="filterCategory" class="hidden">
    <label class="block text-sm font-medium text-gray-600 mb-2">Chọn Nhiều Danh Mục</label>

    <div class="w-full border border-gray-200 rounded-xl p-3 bg-gray-50 space-y-2 max-h-64 overflow-y-auto">
        @foreach ($categories as $cat)
        <label class="flex items-center gap-2 cursor-pointer hover:bg-white rounded-lg px-2 py-1.5 transition">
            <input type="checkbox"
                   name="categories[]"
                   value="{{ $cat->id }}"
                   class="w-4 h-4 accent-primary rounded"
                   {{ is_array(request('categories')) && in_array($cat->id, request('categories')) ? 'checked' : '' }}>
            <span class="text-gray-700 text-sm font-medium">{{ $cat->name }}</span>
        </label>
        @endforeach
    </div>

    <p class="text-xs text-gray-400 mt-2 italic">* Có Thể Chọn Nhiều Danh Mục</p>
</div>


            <div id="filterStatus" class="hidden">
                <label class="block text-sm font-medium text-gray-600 mb-2">Chọn Trạng Thái</label>
                <select name="status" class="w-full border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">-- All --</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>AVAILABLE</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>SOLD</option>
                </select>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeFilterModal()" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm">Huỷ</button>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:scale-105 transition text-sm">Lọc</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
let deleteMode = 'single'; // single or multiple
let selectedId = null;
let selectedIds = [];

const checkboxes = document.querySelectorAll('.account-checkbox');
const selectAll = document.getElementById('selectAll');
const deleteBtn = document.getElementById('deleteSelectedBtn');

// chọn tất cả
selectAll.addEventListener('change', (e) => {
    checkboxes.forEach(cb => cb.checked = e.target.checked);
    toggleDeleteBtn();
});

// chọn từng checkbox
checkboxes.forEach(cb => cb.addEventListener('change', toggleDeleteBtn));

function toggleDeleteBtn() {
    selectedIds = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);
    if (selectedIds.length > 0) {
        deleteBtn.classList.remove('hidden');
        deleteBtn.classList.add('flex');
    } else {
        deleteBtn.classList.add('hidden');
        deleteBtn.classList.remove('flex');
    }
}

// xoá 1 acc
document.querySelectorAll('.confirm-delete').forEach(btn => {
    btn.addEventListener('click', () => {
        deleteMode = 'single';
        selectedId = btn.dataset.id;
        document.getElementById('deleteMessage').innerText = 'Bạn Có Chắc Chắn Xoá Tài Khoản?';
        document.getElementById('deleteModal').classList.remove('hidden');
    });
});

// xoá nhiều
deleteBtn.addEventListener('click', () => {
    deleteMode = 'multiple';
    document.getElementById('deleteMessage').innerText = `Bạn Có Chắc Chắn Xoá ${selectedIds.length} Tài Khoản Đã Chọn?`;
    document.getElementById('deleteModal').classList.remove('hidden');
});

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.getElementById('confirmDelete').addEventListener('click', () => {
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
            <input type="hidden" name="ids" id="deleteIdsInput">
        `;
        document.body.appendChild(form);
    }

    // Gán URL & dữ liệu cần xoá
    if (deleteMode === 'single') {
        form.action = `/seller/accounts/delete/${selectedId}`;
        form.querySelector('#deleteIdsInput').value = '';
    } else {
        form.action = `/seller/accounts/delete-multiple`;
        form.querySelector('#deleteIdsInput').value = JSON.stringify(selectedIds);
    }

    form.submit(); // 🔥 Gửi form — Controller redirect + flash
});
const exportBtn = document.getElementById('exportSelectedBtn');

// Bật/tắt nút export cùng nút xoá
function toggleDeleteBtn() {
    selectedIds = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);

    if (selectedIds.length > 0) {
        deleteBtn.classList.remove('hidden');
        deleteBtn.classList.add('flex');
        exportBtn.classList.remove('hidden');
        exportBtn.classList.add('flex');
    } else {
        deleteBtn.classList.add('hidden');
        deleteBtn.classList.remove('flex');
        exportBtn.classList.add('hidden');
        exportBtn.classList.remove('flex');
    }
}

// Xuất CSV
exportBtn.addEventListener('click', () => {
    if (selectedIds.length === 0) return;

    let form = document.getElementById('exportForm');
    if (!form) {
        form = document.createElement('form');
        form.id = 'exportForm';
        form.method = 'POST';
        form.action = "{{ route('seller.accounts.export') }}";
        form.style.display = 'none';
        form.innerHTML = `
            @csrf
            <input type="hidden" name="ids" id="exportIdsInput">
        `;
        document.body.appendChild(form);
    }

    form.querySelector('#exportIdsInput').value = JSON.stringify(selectedIds);
    form.submit();
});

</script>
<script>
function openFilterModal(type) {
    document.getElementById('filterModal').classList.remove('hidden');
    document.getElementById('filterCategory').classList.add('hidden');
    document.getElementById('filterStatus').classList.add('hidden');

    if (type === 'category') {
        document.getElementById('filterCategory').classList.remove('hidden');
        document.getElementById('filterTitle').innerHTML = `<img src="{{ asset('assets/img/icons/filter2.svg') }}" class="w-5 h-5"> Lọc Theo Danh Mục`;
    } else if (type === 'status') {
        document.getElementById('filterStatus').classList.remove('hidden');
        document.getElementById('filterTitle').innerHTML = `<img src="{{ asset('assets/img/icons/filter2.svg') }}" class="w-5 h-5"> Lọc Theo Trạng Thái`;
    }
}
function closeFilterModal() {
    document.getElementById('filterModal').classList.add('hidden');
}
</script>

<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
.animate-fadeIn { animation: fadeIn 0.25s ease-out; }
<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
.animate-fadeIn { animation: fadeIn 0.25s ease-out; }

#filterModal select {
  background-color: #f9fafb;
  transition: all 0.2s;
}
#filterModal select:hover {
  background-color: #fff;
  box-shadow: 0 0 0 2px rgba(59,130,246,0.15);
}
#filterModal button {
  transition: all 0.2s ease-in-out;
}
#filterModal button:hover {
  transform: scale(1.03);
}
</style>

</style>
@endpush
