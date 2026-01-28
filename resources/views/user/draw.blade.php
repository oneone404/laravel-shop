@extends('layouts.user.app')

@section('title', 'Mở Quà May Mắn')

@section('content')
<style>
    .gift-box {
        width: 120px;
        height: 160px;
        perspective: 800px;
        cursor: pointer;
        margin: 1rem;
        position: relative;
    }

    .box-inner {
        width: 100%;
        height: 100%;
        position: relative;
        transition: transform 1s;
        transform-style: preserve-3d;
    }

    .box-inner.open {
        transform: rotateX(180deg);
        cursor: default;
    }

    .box-front, .box-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgb(0 0 0 / 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        user-select: none;
    }

    .box-front {
        background: linear-gradient(45deg, #ff6f61, #d84315);
        color: white;
        box-shadow: 0 8px 15px rgba(255, 111, 97, 0.6);
    }

    .box-back {
        background: #f9fafb;
        color: #333;
        transform: rotateX(180deg);
        padding: 1rem;
        box-sizing: border-box;
        text-align: center;
        font-size: 0.9rem;
    }

.gifts-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 0;
}

.gift-box {
    width: 120px;
    height: 160px;
    perspective: 800px;
    cursor: pointer;
    margin: 1rem;
    position: relative;
}

/* Mobile: luôn luôn 2 box trên 1 hàng */
@media (max-width: 600px) {
    .gift-box {
        flex: 1 1 calc(50% - 40px);
        max-width: calc(50% - 40px);
        height: auto; /* Giữ tỉ lệ tự co, nếu muốn giữ nguyên có thể bỏ dòng này */
        aspect-ratio: 3/4; /* Giữ đúng tỉ lệ 120x160 khi co nhỏ */
    }
}

    #result-message {
        margin-top: 2rem;
        font-size: 1.2rem;
        text-align: center;
        font-weight: 600;
        color: #2d3748;
    }

    /* Loading spinner */
    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #d84315;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        animation: spin 1s linear infinite;
        display: inline-block;
        vertical-align: middle;
    }

    @keyframes spin {
        0% { transform: rotate(0deg);}
        100% { transform: rotate(360deg);}
    }
    .detail__info {
        background: #f9f9f9;
        padding: 15px 20px;
        border-radius: 8px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        max-width: 400px;
    }

    .detail__info-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }

    .detail__info-item {
        margin-bottom: 12px;
        font-size: 14px;
        line-height: 1.4;
    }

    .detail__info-item strong {
        color: #e67e22; /* Màu cam nổi bật cho dấu ★ */
    }

    .detail__free-spins-list {
        list-style: disc inside;
        margin-top: 6px;
        margin-left: 16px;
        color: #555;
        font-size: 13px;
    }
.custom-select-wrapper {
    width: 220px;
    margin: 2rem auto;
    position: relative;
    font-family: 'Segoe UI', sans-serif;
}

.custom-select-selected {
    border: 2px solid #0E3EDA;
    padding: 10px 12px;
    border-radius: 8px;
    background-color: #eef3ff;
    color: #0E3EDA;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    font-size: 12px;
}

.custom-select-selected:hover {
    background-color: #dce8ff;
    border-color: #1f4aff;
}

.custom-select-options {
    position: absolute;
    width: 100%;
    top: calc(100% + 4px);
    left: 0;
    background: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    list-style: none;
    padding: 4px 0;
    margin: 0;
    display: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    animation: dropdownSlide 0.25s ease-out;
    overflow: hidden;
    z-index: 100;
}

@keyframes dropdownSlide {
    0% {
        opacity: 0;
        transform: translateY(-8px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.custom-select-options li {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    padding: 10px 12px;
    white-space: nowrap;         /* Ngăn chữ xuống hàng */
    width: 100%;                 /* Chiếm full hàng */
    box-sizing: border-box;
    transition: background-color 0.2s ease;
    font-size: 12px;
}

.custom-select-options li:hover {
    background-color: #e6f0ff;
}

.game-icon {
    width: 24px;
    height: 24px;
    border-radius: 35%;
    object-fit: cover;
    border: 1px solid #ccc;
}

</style>

<div class="custom-select-wrapper">
  <div class="custom-select-selected" id="selected-game">
    <img src="/images/vng.png" alt="" class="game-icon"> PLAY TOGETHER VNG
  </div>
  <ul class="custom-select-options" id="game-options">
    <li data-value="com.vng.playtogether">
      <img src="/images/vng.png" class="game-icon"> PLAY TOGETHER VNG
    </li>
    <li data-value="com.haegin.playtogether">
      <img src="/images/global.png" class="game-icon"> PLAY TOGETHER GLOBAL
    </li>
  </ul>
</div>

<!-- Dùng input này trong fetch -->
<input type="hidden" id="game-select" value="com.vng.playtogether">

<script>
const selected = document.getElementById('selected-game');
const options = document.getElementById('game-options');
const hiddenInput = document.getElementById('game-select');

// Toggle dropdown
selected.addEventListener('click', () => {
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
});

// Handle selection
options.querySelectorAll('li').forEach(item => {
    item.addEventListener('click', () => {
        selected.innerHTML = item.innerHTML;
        hiddenInput.value = item.dataset.value;
        options.style.display = 'none';
    });
});

// Auto close when click outside
document.addEventListener('click', function(e) {
    if (!document.querySelector('.custom-select-wrapper').contains(e.target)) {
        options.style.display = 'none';
    }
});
</script>
 
<div class="container mx-auto px-4">

    <div class="gifts-container">
        @for ($i = 0; $i < 4; $i++)
        <div class="gift-box" data-index="{{ $i }}">
            <div class="box-inner">
                <div class="box-front">
                    Mở Quà
                </div>
                <div class="box-back">
                    <!-- Kết quả sẽ hiện ở đây -->
                    Đang Chờ...
                </div>
            </div>
        </div>
        @endfor
    </div>
    
    <div id="result-message"></div>
    <section class="rules-section">
            <h2 class="history-header">QUY ĐỊNH TRÚNG THƯỞNG</h2>
            <div class="detail__info">
                <ul class="detail__info-list">
                    <li class="detail__info-item">
                        <strong>★ ĐIỀU KIỆN MIỄN PHÍ MỞ QUÀ</strong>
                        <ul class="detail__free-spins-list">
                            <li>TỔNG TIỀN BẠN ĐÃ NẠP TRÊN SHOP PHẢI TỪ 10K TRỞ LÊN</li>
                            <li>XEM TỔNG NẠP <a href="/profile" style="color:blue">TẠI ĐÂY</a></li>
                        </ul>
                    </li>
                    <li class="detail__info-item">
                        <strong>★ PHẦN THƯỞNG</strong>
                        <ul class="detail__free-spins-list">
                            <li>KEY VIP 1 NGÀY</li>
                            <li>KEY VIP 1 TUẦN</li>
                            <li>KEY VIP 1 THÁNG</li>
                        </ul>
                    </li>
                    <li class="detail__info-item">
                        <strong>★ MỞ QUÀ MIỄN PHÍ</strong>
                        <ul class="detail__free-spins-list">
                            <li>1 LƯỢT MỞ QUÀ MỖI NGÀY</strong></li>
                            <li><strong style="color:black">Mỗi Ngày Có Thể Vào Mở Quà Miễn Phí</strong></li>
                            <li><strong style="color:red">Lưu ý: </strong><strong>Lưu Key Lại Khi Trúng Thưởng</strong></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </section>
</div>

<script>
    document.querySelectorAll('.gift-box').forEach(box => {
        box.addEventListener('click', function() {
            if (this.querySelector('.box-inner').classList.contains('open')) {
                // Đã mở rồi, không làm gì nữa
                return;
            }

            const index = this.getAttribute('data-index');
            const boxInner = this.querySelector('.box-inner');
            const boxBack = this.querySelector('.box-back');
            const resultMessage = document.getElementById('result-message');

            // Mở hộp quà (animation)
            boxInner.classList.add('open');

            // Hiện loading trong mặt sau
            boxBack.innerHTML = '<div class="spinner"></div>';
            let deviceId = localStorage.getItem('device_id');
            if (!deviceId) {
                deviceId = crypto.randomUUID();  // tạo UUID mới (hỗ trợ từ các trình duyệt hiện đại)
                localStorage.setItem('device_id', deviceId);
            }

            // Gửi AJAX lên server
            const game = document.getElementById('game-select').value;

            fetch("{{ route('draw.spin') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Device-ID': deviceId,
                },
                body: JSON.stringify({ game: game })
            })
            .then(response => {
    if (!response.ok) {
        if (response.status === 429) {
            throw new Error('Bạn Chỉ Được Mở Quà 1 Lần Trong Ngày');
        }
        if (response.status === 403) {
            throw new Error('Cần Đăng Nhập Để Bốc Thăm');
        }
        if (response.status === 402) {
            throw new Error('Tổng Nạp Trên 10K Mới Có Thể Mở Quà');
        }
        throw new Error('Lỗi Máy Chủ');
    }

    return response.json();
})
.then(data => {
    if(data.link) {
        boxBack.innerHTML = `
            <a href="${data.link}" target="_blank" class="text-red-600 font-bold underline">
                🎉 Nhận Quà Tại Đây
            </a>
        `;
        resultMessage.textContent = '';
    } else if(data.error) {
        boxBack.textContent = 'Lỗi: ' + data.error;
    } else {
        boxBack.textContent = 'Vui Lòng Thử Lại!';
    }
})
.catch((error) => {
    boxBack.textContent = error.message || 'Lỗi';
});

        });
    });
</script>
@endsection
