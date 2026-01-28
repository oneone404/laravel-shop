<!DOCTYPE html>
<html lang="en">
<!-- Head -->
@include('layouts.user.head')

<body>
    <!-- CSRF Token Meta for JS usage -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.user.header')
    <!-- Main -->
    <main>
        @yield('content')
    </main>

    @include('layouts.user.footer')
    @include('layouts.user.menu-mobile')

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

    <!-- Lightbox script -->
    <script src="{{ asset('assets/libs/simplelightbox.min.js') }}"></script>
    <script src="{{ asset('assets/js/image-lightbox.js') }}"></script>

    <!-- Mobile Menu script -->
    <script src="{{ asset('assets/js/mobile-menu.js') }}"></script>

    <!-- Core scripts -->
    <script src="{{ asset('assets/js/discount-code.js?id=87d12f') }}"></script>

    @stack('scripts')
    <script src="{{ asset('assets/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/alert-notice.js') }}"></script>

    <!-- Add before closing body tag -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
