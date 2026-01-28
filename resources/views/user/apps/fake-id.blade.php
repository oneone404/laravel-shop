@extends('layouts.user.app')

@section('title', 'Danh Sách ID Vùng Câu')

@section('content')
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/css/apps.css') }}">
    @endpush

    <div class="container mt-4 table-container">
        <x-hero-header title="Danh Sách ID" description="" />

        <input type="text" id="searchInput" class="search-input" placeholder="Tìm Kiếm ID Hoặc Tên Vùng Câu">

        <table class="blue-table">
            <thead>
                <tr>
                    <th style="width: 100px;">ID</th>
                    <th>Vùng Câu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fakeIds as $fake)
                    <tr>
                        <td class="pretty-id">{{ $fake['id'] ?? '...' }}</td>
                        <td class="pretty-name">{{ $fake['name'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.blue-table tbody tr');

            searchInput.addEventListener('input', () => {
                const keyword = searchInput.value.trim().toLowerCase();

                rows.forEach(row => {
                    const idCell = row.querySelector('td.pretty-id');
                    const nameCell = row.querySelector('td.pretty-name');
                    const idText = idCell.textContent.trim().toLowerCase();
                    const nameText = nameCell.textContent.trim().toLowerCase();

                    if (idText.includes(keyword) || nameText.includes(keyword)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>

@endsection