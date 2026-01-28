@extends('layouts.seller.app')
@section('title', 'Quản Lý Tài Khoản Random')

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
                Tài Khoản Random
            </h2>
            <p class="text-gray-500">Quản Lý Tài Khoản Random</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-3 sm:mt-0">
            {{-- Nút thêm --}}
            <a href="{{ route('seller.accounts.random.create') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-medium px-4 py-2 rounded-lg hover:scale-105 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Thêm Nhóm Random
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="p-5 border-b flex items-center justify-between bg-gradient-to-r from-purple-50 to-pink-50">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <img src="{{ asset('assets/img/icons/random.svg') }}" class="w-5 h-5" alt="icon">
                Danh Sách Nhóm Random
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-gradient-to-r from-purple-50 to-pink-50 text-gray-700 uppercase text-xs font-semibold border-b">
                    <tr>
                        <th class="px-5 py-3 text-center">#</th>
                        <th class="px-5 py-3 text-center">Ảnh</th>
                        <th class="px-5 py-3 text-left">Danh Mục</th>
                        <th class="px-5 py-3 text-center">Số Lượng</th>
                        <th class="px-5 py-3 text-center">Đã Bán</th>
                        <th class="px-5 py-3 text-center">Giá Tiền</th>
                        <th class="px-5 py-3 text-center">Trạng Thái</th>
                        <th class="px-5 py-3 text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($randomGroups as $key => $group)
                        @php
                            $accountsData = $group->accounts_data ?? [];
                            $accountCount = count($accountsData);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-all duration-150">
                            <td class="px-5 py-3 text-center text-gray-500 font-medium">{{ $randomGroups->firstItem() + $key }}</td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center">
                                    <div class="relative w-20 aspect-video rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                        <img src="{{ asset($group->thumb ?? 'assets/img/placeholder.png') }}"
                                             alt="Thumbnail"
                                             class="absolute inset-0 w-full h-full object-cover">
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 font-semibold text-gray-900">
                                {{ $group->category->name ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $sellerId = auth()->id();
                                    $myAccounts = [];
                                    foreach($accountsData as $item) {
                                        $ownerId = is_array($item) ? ($item['sid'] ?? $group->created_by) : $group->created_by;
                                        if($ownerId == $sellerId) {
                                            $myAccounts[] = $item;
                                        }
                                    }
                                @endphp
                                <button onclick="openAccountsModal({{ $group->id }}, '{{ addslashes($group->category->name ?? 'N/A') }}', {{ json_encode($myAccounts) }})"
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full hover:bg-purple-200 transition cursor-pointer"
                                        title="Click để xem các tài khoản của bạn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ count($myAccounts) }} / {{ $accountCount }}
                                </button>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                    {{ $group->sold_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center text-green-700 font-semibold">
                                {{ number_format($group->price) }} <span class="text-xs text-gray-500">VNĐ</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if ($group->status === 'available' && $accountCount > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                        CÒN HÀNG
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                        HẾT HÀNG
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('seller.accounts.random.edit', $group->id) }}"
                                       class="hover:scale-110 transition" title="Sửa">
                                        <img src="{{ asset('assets/img/icons/edit.svg') }}" class="w-4 h-4" alt="edit">
                                    </a>
                                    <form action="{{ route('seller.accounts.random.destroy', $group->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Bạn chắc chắn muốn xóa nhóm random này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="hover:scale-110 transition" title="Xóa">
                                            <img src="{{ asset('assets/img/icons/delete.svg') }}" class="w-4 h-4" alt="delete">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 italic">Chưa có nhóm random nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($randomGroups->hasPages())
        <div class="p-5 border-t bg-white rounded-b-xl flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-6">
            <p class="text-xs sm:text-sm text-gray-500">
                Hiển thị
                <span class="font-semibold text-primary">{{ $randomGroups->firstItem() }}</span> -
                <span class="font-semibold text-primary">{{ $randomGroups->lastItem() }}</span> /
                Tổng <span class="font-semibold text-secondary">{{ $randomGroups->total() }}</span> nhóm
            </p>

            <div class="flex items-center space-x-1">
                {{ $randomGroups->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal Xem Danh Sách Tài Khoản --}}
<div id="accountsModal" class="fixed inset-0 hidden bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[80vh] flex flex-col animate-fadeIn">
        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b bg-gradient-to-r from-purple-50 to-pink-50 rounded-t-2xl">
            <h3 class="font-bold text-gray-800 flex items-center gap-2 text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span id="modalCategoryName">Danh sách tài khoản</span>
            </h3>
            <button onclick="closeAccountsModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">×</button>
        </div>

        {{-- Body - Scrollable --}}
        <div class="flex-1 overflow-y-auto p-5">
            <div class="mb-4 flex items-center justify-between">
                <span class="text-sm text-gray-500">Tổng Số <span id="modalAccountCount" class="font-bold text-purple-600">0</span> Tài Khoản</span>
                <button onclick="copyAllAccounts()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-100 text-purple-700 text-xs font-semibold rounded-lg hover:bg-purple-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Copy All
                </button>
            </div>

            <div id="accountsList" class="space-y-2">
                {{-- Accounts will be rendered here --}}
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-4 border-t bg-gray-50 rounded-b-2xl">
            <button onclick="closeAccountsModal()" class="w-full px-4 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                Đóng
            </button>
        </div>
    </div>
</div>

@push('scripts')
<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
.animate-fadeIn { animation: fadeIn 0.25s ease-out; }
</style>
<script>
let currentAccountsData = [];

function openAccountsModal(groupId, categoryName, accounts) {
    currentAccountsData = accounts || [];
    document.getElementById('modalCategoryName').innerText = categoryName;
    document.getElementById('modalAccountCount').innerText = currentAccountsData.length;

    const listContainer = document.getElementById('accountsList');
    listContainer.innerHTML = '';

    if (currentAccountsData.length === 0) {
        listContainer.innerHTML = '<p class="text-center text-gray-400 italic py-8">Bạn chưa nạp tài khoản nào vào nhóm này</p>';
    } else {
        currentAccountsData.forEach((acc, index) => {
            let username = '';
            let password = '';
            let rawString = '';

            if (typeof acc === 'object') {
                username = acc.u || '';
                password = acc.p || '';
                rawString = username + '|' + password;
            } else {
                const parts = acc.split('|');
                username = parts[0] || '';
                password = parts[1] || '';
                rawString = acc;
            }

            const div = document.createElement('div');
            div.className = 'flex items-center justify-between bg-gray-50 rounded-lg p-3 border border-gray-100 hover:bg-gray-100 transition';
            div.innerHTML = `
                <div class="flex-1 min-w-0">
                    <div class="text-xs text-gray-400 mb-1">#${index + 1}</div>
                    <div class="font-mono text-sm text-gray-800 truncate">${escapeHtml(username)}</div>
                    <div class="font-mono text-xs text-gray-500 truncate">${escapeHtml(password)}</div>
                </div>
                <button onclick="copyAccount('${escapeHtml(rawString).replace(/'/g, "\\'")}')" class="ml-3 p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition" title="Copy">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </button>
            `;
            listContainer.appendChild(div);
        });
    }

    document.getElementById('accountsModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAccountsModal() {
    document.getElementById('accountsModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function copyAccount(account) {
    navigator.clipboard.writeText(account).then(() => {
        // Show brief feedback
        const btn = event.currentTarget;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
        setTimeout(() => { btn.innerHTML = originalHTML; }, 1000);
    });
}

function copyAllAccounts() {
    if (currentAccountsData.length === 0) return;
    const text = currentAccountsData.map(acc => {
        if (typeof acc === 'object') return (acc.u || '') + '|' + (acc.p || '');
        return acc;
    }).join('\n');
    navigator.clipboard.writeText(text).then(() => {
        alert('Đã copy ' + currentAccountsData.length + ' tài khoản của bạn!');
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal when clicking outside
document.getElementById('accountsModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeAccountsModal();
});
</script>
@endpush
@endsection
