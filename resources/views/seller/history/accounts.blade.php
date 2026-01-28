@extends('layouts.seller.app')
@section('title', 'Lịch Sử Bán Tài Khoản')

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
                Lịch Sử Tài Khoản
            </h2>
            <p class="text-gray-500">Danh Sách Tài Khoản Đã Được Bán</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="p-5 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <img src="{{ asset('assets/img/icons/history.svg') }}" class="w-5 h-5" alt="icon">
                Danh Sách Tài Khoản Đã Bán
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-gradient-to-r from-green-50 to-orange-50 text-gray-700 uppercase text-xs font-semibold border-b">
                    <tr>
                        <th class="px-5 py-3 text-center">#</th>
                        <th class="px-5 py-3 text-left">Danh Mục</th>
                        <th class="px-5 py-3 text-left">Tài Khoản</th>
                        <th class="px-5 py-3 text-center">Giá Bán</th>
                        <th class="px-5 py-3 text-center">Ảnh</th>
                                                <th class="px-5 py-3 text-center">Ngày Bán</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($accounts as $key => $acc)
                        <tr class="hover:bg-gray-50 transition-all duration-150">
                            <td class="px-5 py-3 text-center text-gray-500 font-medium">
                                {{ $accounts->firstItem() + $key }}
                            </td>
                            <td class="px-5 py-3 font-semibold text-gray-900">
                                {{ $acc->category->name ?? 'Không rõ' }}
                            </td>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $acc->account_name }}</td>
                            <td class="px-5 py-3 text-center text-green-700 font-semibold">
                                {{ number_format($acc->price) }} <span class="text-xs text-gray-500">VNĐ</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex justify-center">
                                    <div class="relative w-28 sm:w-32 aspect-video rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                        <img src="{{ asset($acc->thumb) }}"
                                             alt="{{ $acc->account_name }}"
                                             class="absolute inset-0 w-full h-full object-cover">
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-center text-gray-500">
                                {{ $acc->updated_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 italic">
                                Chưa Có Tài Khoản Được Bán
                            </td>
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
@endsection

@push('styles')
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn { animation: fadeIn 0.25s ease-out; }
</style>
@endpush
