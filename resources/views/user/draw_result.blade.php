@extends('layouts.user.app')

@section('title', 'Mở Quà May Mắn')

@section('content')
<style>
    .reward-card {
        background: linear-gradient(to bottom right, #ffffff, #f0f4f8);
        border: 2px solid #d1d5db;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-radius: 16px;
        padding: 2rem;
        max-width: 330px;
        margin: 0 auto;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .reward-card:hover {
        transform: translateY(-5px);
    }

    .btn-copy {
        background-color: #3b82f6;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        margin-top: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-copy:hover {
        background-color: #2563eb;
    }

    .copy-success {
        color: green;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
    .key-highlight {
    background-color: #fff9c4; /* vàng nhạt */
    border: 2px solid #fbc02d; /* vàng đậm */
    box-shadow: 0 0 10px rgba(251, 192, 45, 0.7);
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.25rem;
    color: #b28704; /* vàng nâu */
    max-width: 250px;
    margin: 0 auto;
    word-break: break-all;
    user-select: all; /* dễ copy */
    cursor: pointer;
    transition: box-shadow 0.3s ease;
}
.key-highlight:hover {
    box-shadow: 0 0 15px rgba(251, 192, 45, 1);
}

</style>

<div style="margin-top: 60px;" class="flex flex-col items-center justify-center">

    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">🎁 KẾT QUẢ MỞ QUÀ 🎁</h2>



    <div class="reward-card">
        @if(Str::startsWith($key, 'ONE_'))
            <p class="text-green-600 font-semibold text-xl">🎉 Chúc Mừng! Trúng Key 1 Ngày</p>
            <p id="rewardText" class="key-highlight">{{ $key }}</p>

            <button class="btn-copy" onclick="copyReward()">Sao Chép</button>
            <div id="copyMessage" class="copy-success" style="display: none;">Đã Sao Chép!</div>
        @else
            <p class="text-red-500 font-semibold text-lg">Chúc Bạn May Mắn Lần Sau 😢</p>
        @endif
    </div>
</div>

<script>
    function copyReward() {
        const rewardText = document.getElementById("rewardText").innerText;
        navigator.clipboard.writeText(rewardText).then(() => {
            document.getElementById("copyMessage").style.display = 'block';
            setTimeout(() => {
                document.getElementById("copyMessage").style.display = 'none';
            }, 2000);
        });
    }
</script>
@endsection
