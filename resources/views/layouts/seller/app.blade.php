<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#28c76f',
            secondary: '#ff9f43',
          }
        }
      }
    };
  </script>
  <script>
    // Toggle sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden');
      document.body.classList.toggle('overflow-hidden');
    }

    // Close sidebar
    function closeSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  </script>

  <style>
    /* Cho phép nhấn nút menu khi mở sidebar */
    #sidebar-overlay {
      pointer-events: auto;
    }

    /* Khi mở menu thì overlay không che phần navbar */
    @media (max-width: 767px) {
      #sidebar-overlay {
        top: 64px; /* cao bằng header */
      }
    }

    /* Ẩn dòng header trong sidebar khi ở PC */
    @media (min-width: 768px) {
      .sidebar-header-mobile {
        display: none !important;
      }
    }
  </style>
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

  <!-- Navbar -->
  <header class="bg-white p-4 flex justify-between items-center sticky top-0 z-30">
    <div class="flex items-center gap-3">
      <button onclick="toggleSidebar()" class="md:hidden text-gray-600 text-2xl relative z-40">
        <img src="{{ asset('assets/img/icons/list-menu.svg') }}" class="w-6 h-6" alt="menu">
      </button>
<h1 class="text-lg md:text-2xl font-extrabold text-primary ml-2 md:ml-4 tracking-wide">
  SELLER PANEL
</h1>

    </div>

    <div class="flex items-center gap-3">
      <div class="text-right hidden sm:block">
        <p class="font-semibold text-gray-800">{{ Auth::user()->username ?? 'Seller' }}</p>
        <p class="text-sm text-gray-500">Seller</p>
      </div>
      <img src="{{ config_get('site_favicon') }}" class="w-10 h-10 rounded-full border-2 border-primary" alt="avatar">
    </div>
  </header>

  <!-- Container -->
  <div class="flex flex-1 relative">

    <!-- Overlay (chỉ che phần nội dung, không che navbar) -->
    <div id="sidebar-overlay"
         class="fixed left-0 right-0 bottom-0 bg-black bg-opacity-40 hidden z-20 md:hidden"
         onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
           class="fixed top-0 left-0 md:sticky md:top-[64px] z-30 bg-white w-64 h-screen md:h-[calc(100vh-64px)] border-r transform -translate-x-full md:translate-x-0
                  transition-transform duration-200 ease-in-out md:shadow-none">

      <!-- Header trong sidebar (mobile only) -->
      <div class="sidebar-header-mobile flex items-center justify-between p-4 border-b bg-white sticky top-0 z-10">
        <div class="flex items-center gap-3">
          <img src="{{ asset('assets/img/icons/list-menu.svg') }}" class="w-6 h-6 text-gray-600 opacity-80" alt="menu">
<h2 class="text-lg md:text-2xl font-extrabold text-primary ml-2 md:ml-4 tracking-wide">
  SELLER PANEL
</h2>

        </div>
        <button onclick="closeSidebar()" class="text-gray-600 hover:text-red-500 text-xl">
         <img src="{{ asset('assets/img/icons/close2.svg') }}"
                     class="w-5 h-5" alt="close">
        </button>
      </div>

      <!-- Menu điều hướng -->
      <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-64px)]">

        {{-- Dashboard --}}
        <a href="{{ route('seller.dashboard') }}"
           class="flex items-center justify-between px-4 py-2 rounded-lg font-medium text-xs transition
                  {{ request()->routeIs('seller.dashboard')
                      ? 'bg-primary text-white'
                      : 'text-gray-700 hover:bg-primary hover:text-white' }}">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/img/icons/dashboard.svg') }}"
                     class="w-4 h-4 sm:w-5 sm:h-5" alt="dashboard">
                <span class="tracking-wide">BẢNG ĐIỀU KHIỂN</span>
            </div>
            <img src="{{ asset('assets/img/icons/chevron-right.svg') }}"
                 class="w-3 h-3 opacity-60" alt=">">
        </a>

        {{-- Danh mục tài khoản --}}
        <a href="{{ route('seller.categories.index') }}"
           class="flex items-center justify-between px-4 py-2 rounded-lg font-medium text-xs transition
                  {{ request()->routeIs('seller.categories.*')
                      ? 'bg-primary text-white'
                      : 'text-gray-700 hover:bg-primary hover:text-white' }}">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/img/icons/product.svg') }}"
                     class="w-4 h-4 sm:w-5 sm:h-5" alt="product">
                <span class="tracking-wide">DANH MỤC TÀI KHOẢN</span>
            </div>
            <img src="{{ asset('assets/img/icons/chevron-right.svg') }}"
                 class="w-3 h-3 opacity-60" alt=">">
        </a>

        {{-- Quản lý tài khoản - Dropdown --}}
        <div class="space-y-1">
            {{-- Header --}}
            <div class="flex items-center justify-between px-4 py-2 rounded-lg font-medium text-xs text-gray-500 uppercase tracking-wider">
                <span>Quản Lý Tài Khoản</span>
            </div>

            {{-- Tài khoản Play --}}
            <a href="{{ route('seller.accounts.play.index') }}"
               class="flex items-center justify-between px-4 py-2 rounded-lg font-medium text-xs transition ml-2
                      {{ request()->routeIs('seller.accounts.play.*')
                          ? 'bg-blue-500 text-white'
                          : 'text-gray-700 hover:bg-blue-500 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="tracking-wide">TÀI KHOẢN PLAY</span>
                </div>
                <img src="{{ asset('assets/img/icons/chevron-right.svg') }}" class="w-3 h-3 opacity-60" alt=">">
            </a>

            {{-- Tài khoản Clone --}}
            <a href="{{ route('seller.accounts.clone.index') }}"
               class="flex items-center justify-between px-4 py-2 rounded-lg font-medium text-xs transition ml-2
                      {{ request()->routeIs('seller.accounts.clone.*')
                          ? 'bg-purple-500 text-white'
                          : 'text-gray-700 hover:bg-purple-500 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span class="tracking-wide">TÀI KHOẢN CLONE</span>
                </div>
                <img src="{{ asset('assets/img/icons/chevron-right.svg') }}" class="w-3 h-3 opacity-60" alt=">">
            </a>

            {{-- Tài khoản Random --}}
            <a href="{{ route('seller.accounts.random.index') }}"
               class="flex items-center justify-between px-4 py-2 rounded-lg font-medium text-xs transition ml-2
                      {{ request()->routeIs('seller.accounts.random.*')
                          ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white'
                          : 'text-gray-700 hover:bg-gradient-to-r hover:from-purple-500 hover:to-pink-500 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span class="tracking-wide">TÀI KHOẢN RANDOM</span>
                </div>
                <img src="{{ asset('assets/img/icons/chevron-right.svg') }}" class="w-3 h-3 opacity-60" alt=">">
            </a>
        </div>

        {{-- Lịch sử giao dịch --}}
        <a href="{{ route('seller.history.accounts') }}"
           class="flex items-center justify-between px-4 py-2 rounded-lg font-medium text-xs transition
                  {{ request()->routeIs('seller.history.*')
                      ? 'bg-primary text-white'
                      : 'text-gray-700 hover:bg-primary hover:text-white' }}">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/img/icons/time.svg') }}"
                     class="w-4 h-4 sm:w-5 sm:h-5" alt="history">
                <span class="tracking-wide">LỊCH SỬ GIAO DỊCH</span>
            </div>
            <img src="{{ asset('assets/img/icons/chevron-right.svg') }}"
                 class="w-3 h-3 opacity-60" alt=">">
        </a>

        {{-- Cài đặt hệ thống --}}
        <a href="#"
           class="flex items-center justify-between px-4 py-2 rounded-lg font-medium text-xs transition
                  {{ request()->routeIs('seller.settings.*')
                      ? 'bg-primary text-white'
                      : 'text-gray-700 hover:bg-primary hover:text-white' }}">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/img/icons/settings.svg') }}"
                     class="w-4 h-4 sm:w-5 sm:h-5" alt="settings">
                <span class="tracking-wide">CÀI ĐẶT HỆ THỐNG</span>
            </div>
            <img src="{{ asset('assets/img/icons/chevron-right.svg') }}"
                 class="w-3 h-3 opacity-60" alt=">">
        </a>

      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-5 md:p-8 overflow-y-auto">
      @yield('content')
    </main>

  </div>

  @stack('scripts')
</body>
</html>
