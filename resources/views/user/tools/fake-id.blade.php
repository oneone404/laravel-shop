@extends('layouts.user.app')

@section('title', 'Danh Sách ID Vùng Câu')

@section('content')
<style>
.table-container {
    max-width: 960px;
    margin: 0 auto;
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
    overflow: hidden;
    border: 1px solid #cbd5e1;
}

.table-title {
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    margin: 20px 0;
    color: #1e3a8a;
}

.blue-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 15px;
}

.blue-table thead {
    background-color: #1d4ed8;
    color: #ffffff;
}

.blue-table th {
    text-align: left;
    padding: 12px 16px;
    font-weight: 600;
    letter-spacing: 0.3px;
    border: 1px solid #cbd5e1;
}

.blue-table td {
    padding: 12px 16px;
    border: 1px solid #cbd5e1;
    color: #1e293b;
}

.blue-table tbody tr:nth-child(even) {
    background-color: #f8fafc;
}

.blue-table tbody tr:hover {
    background-color: #e0f2fe;
}

.pretty-id {
    font-weight: 600;
    color: #2563eb;
}

.pretty-name {
    color: #334155;
}

.blue-table thead tr:first-child th:first-child {
    border-top-left-radius: 8px;
}
.blue-table thead tr:first-child th:last-child {
    border-top-right-radius: 8px;
}
.blue-table tbody tr:last-child td:first-child {
    border-bottom-left-radius: 8px;
}
.blue-table tbody tr:last-child td:last-child {
    border-bottom-right-radius: 8px;
}

.search-input {
    margin-bottom: 15px;
    width: 100%;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    padding: 8px 12px;
}
</style>

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