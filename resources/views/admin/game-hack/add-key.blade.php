@extends('layouts.admin.app')
@section('title', 'Thêm Key cho Game')
@section('content')
<style>
.settings-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    color: white;
    margin-bottom: 20px;
}
.settings-card .card-header {
    background: transparent;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}
.settings-card .card-header h4 {
    color: white;
    margin: 0;
}
.mode-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 14px;
}
.mode-badge.api {
    background: #10b981;
    color: white;
}
.mode-badge.db {
    background: #f59e0b;
    color: white;
}
.toggle-btn {
    cursor: pointer;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}
.toggle-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.add-key-section {
    transition: all 0.3s ease;
    overflow: hidden;
}
.add-key-section.hidden {
    max-height: 0;
    opacity: 0;
    padding: 0 !important;
    margin: 0 !important;
}
.add-key-section.visible {
    max-height: 2000px;
    opacity: 1;
}
</style>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Quản Lý Key Game</h4>
            </div>
        </div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Settings Card -->
<div class="card settings-card">
    <div class="card-header">
        <h4><i class="fas fa-cog"></i> Cài Đặt Bán Key</h4>
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-2"><strong>Chế độ hiện tại:</strong></p>
                <span class="mode-badge {{ strtolower($keyMode ?? 'db') }}" id="currentModeBadge">
                    @if(strtolower($keyMode ?? 'db') === 'api')
                        <i class="fas fa-cloud"></i> API MODE (HackViet)
                    @else
                        <i class="fas fa-database"></i> DATABASE MODE
                    @endif
                </span>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <button type="button" class="btn btn-warning mb-2" id="toggleModeBtn">
                    <i class="fas fa-sync-alt"></i> Chuyển Sang 
                    <span id="nextMode">{{ strtolower($keyMode ?? 'db') === 'api' ? 'DB' : 'API' }}</span>
                </button>
                <br>
                <a href="{{ route('admin.game-hack.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Quay Lại
                </a>
            </div>
        </div>
        
        @if(strtolower($keyMode ?? 'db') === 'api')
        <hr style="border-color: rgba(255,255,255,0.3)">
        <div class="row">
            <div class="col-12">
                <p class="mb-1"><i class="fas fa-info-circle"></i> <strong>API Mode:</strong> Key được tạo tự động qua HackViet API</p>
                <ul class="mb-0" style="opacity: 0.9;">
                    <li>Không cần nhập key thủ công</li>
                    <li>Key được tạo ngay khi user mua</li>
                    <li>Tự động re-login nếu session hết hạn</li>
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Số Lượng Key Còn Lại (chỉ hiện khi DB mode) -->
@if(strtolower($keyMode ?? 'db') === 'db')
<div class="card mt-4">
    <div class="card-header"><h4>Số Lượng Key Còn Lại (Database)</h4></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Game</th>
                        <th>1 Ngày</th>
                        <th>7 Ngày</th>
                        <th>14 Ngày</th>
                        <th>21 Ngày</th>
                        <th>30 Ngày</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $game)
                        @php
                            $summary = $keysSummary[$game->name] ?? collect();
                        @endphp
                        <tr>
                            <td>{{ $game->name }}</td>
                            <td>{{ $summary->firstWhere('time_use', 1)->total ?? 0 }}</td>
                            <td>{{ $summary->firstWhere('time_use', 7)->total ?? 0 }}</td>
                            <td>{{ $summary->firstWhere('time_use', 14)->total ?? 0 }}</td>
                            <td>{{ $summary->firstWhere('time_use', 21)->total ?? 0 }}</td>
                            <td>{{ $summary->firstWhere('time_use', 30)->total ?? 0 }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td><strong>ALL</strong></td>
                        <td>{{ $keysSummary->sum(fn($g) => $g->firstWhere('time_use', 1)->total ?? 0) }}</td>
                        <td>{{ $keysSummary->sum(fn($g) => $g->firstWhere('time_use', 7)->total ?? 0) }}</td>
                        <td>{{ $keysSummary->sum(fn($g) => $g->firstWhere('time_use', 14)->total ?? 0) }}</td>
                        <td>{{ $keysSummary->sum(fn($g) => $g->firstWhere('time_use', 21)->total ?? 0) }}</td>
                        <td>{{ $keysSummary->sum(fn($g) => $g->firstWhere('time_use', 30)->total ?? 0) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Toggle Button -->
<div class="text-center my-4">
    <button type="button" class="toggle-btn btn btn-primary" id="toggleAddKey">
        <i class="fas fa-plus"></i> Thêm Key Vào Database
    </button>
</div>

<!-- Add Key Form (ẩn mặc định) -->
<div class="card add-key-section hidden" id="addKeySection">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Nhập Key Mới</h4>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="closeAddKey">
            <i class="fas fa-times"></i> Đóng
        </button>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.game-hack.store-key') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Chọn Game</label>
                <select name="game_type" class="form-control">
                    <option value="all">ALL</option>
                    @foreach($games as $game)
                        <option value="{{ $game->name }}">{{ $game->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Nhập Key (1 Dòng 1 Key)</label>
                <textarea name="key_values" class="form-control" rows="5" required placeholder="KEY1&#10;KEY2&#10;KEY3"></textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Thời Gian (Ngày)</label>
                        <select name="time_use" class="form-control" required>
                            <option value="1">1 Ngày</option>
                            <option value="7">7 Ngày (1 Tuần)</option>
                            <option value="14">14 Ngày (2 Tuần)</option>
                            <option value="21">21 Ngày (3 Tuần)</option>
                            <option value="30">30 Ngày (1 Tháng)</option>
                            <option value="3650">Vĩnh Viễn</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Giới Hạn Thiết Bị</label>
                        <input type="number" name="device_limit" class="form-control" value="1" min="1" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Giá Tiền (VNĐ)</label>
                        <input type="number" name="price" class="form-control" value="15000" min="0" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="fas fa-save"></i> Lưu Key Vào Database
            </button>
        </form>
    </div>
</div>
@else
<!-- API Configuration (chỉ hiện khi API mode) -->
<div class="card mt-4">
    <div class="card-header">
        <h4><i class="fas fa-key"></i> Cấu Hình Tài Khoản API (HackViet & XLink)</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.game-hack.update-api-configs') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary"><i class="fas fa-cloud"></i> HackViet API</h5>
                    <div class="form-group mb-3">
                        <label>Email Đăng Nhập</label>
                        <input type="email" name="HACKVIET_EMAIL" class="form-control" value="{{ $apiConfigs['HACKVIET_EMAIL'] }}" placeholder="Email tài khoản HackViet">
                    </div>
                    <div class="form-group mb-3">
                        <label>Mật Khẩu</label>
                        <div class="input-group">
                            <input type="password" name="HACKVIET_PASSWORD" class="form-control" value="{{ $apiConfigs['HACKVIET_PASSWORD'] }}" id="hackvietPassword">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('hackvietPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Base URL</label>
                        <input type="url" name="HACKVIET_BASE_URL" class="form-control" value="{{ $apiConfigs['HACKVIET_BASE_URL'] }}" placeholder="https://hackviet.io">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label>Shop Slug</label>
                                <input type="text" name="HACKVIET_SHOP_SLUG" class="form-control" value="{{ $apiConfigs['HACKVIET_SHOP_SLUG'] }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label>Game Slug</label>
                                <input type="text" name="HACKVIET_GAME_SLUG" class="form-control" value="{{ $apiConfigs['HACKVIET_GAME_SLUG'] }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3 text-info"><i class="fas fa-link"></i> XLink API (Quản lý Download)</h5>
                    <div class="form-group mb-3">
                        <label>API URL</label>
                        <input type="url" name="XLINK_API_URL" class="form-control" value="{{ $apiConfigs['XLINK_API_URL'] }}" placeholder="https://xlink.co/api">
                    </div>
                    <div class="form-group mb-3">
                        <label>API Token (XLINK_API_TOKEN)</label>
                        <input type="text" name="XLINK_API_TOKEN" class="form-control" value="{{ $apiConfigs['XLINK_API_TOKEN'] }}" placeholder="Token từ xlink.co">
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <p class="mb-0 small"><i class="fas fa-info-circle"></i> <strong>Lưu ý:</strong> Cấu hình này được sử dụng để tạo key tự động và quản lý link tải game. Hãy đảm bảo thông tin chính xác để tránh gián đoạn dịch vụ.</p>
                    </div>
                </div>
            </div>
            
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save"></i> Lưu Cấu Hình API
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(id) {
    const el = document.getElementById(id);
    if (el.type === "password") {
        el.type = "text";
    } else {
        el.type = "password";
    }
}
</script>
@endif

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleAddKey');
    const closeBtn = document.getElementById('closeAddKey');
    const addKeySection = document.getElementById('addKeySection');

    if (toggleBtn && addKeySection) {
        toggleBtn.addEventListener('click', function() {
            addKeySection.classList.remove('hidden');
            addKeySection.classList.add('visible');
            toggleBtn.style.display = 'none';
        });
    }

    if (closeBtn && addKeySection && toggleBtn) {
        closeBtn.addEventListener('click', function() {
            addKeySection.classList.remove('visible');
            addKeySection.classList.add('hidden');
            toggleBtn.style.display = 'inline-block';
        });
    }

    // Toggle KEY_MODE
    const toggleModeBtn = document.getElementById('toggleModeBtn');
    if (toggleModeBtn) {
        toggleModeBtn.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn chuyển đổi chế độ KEY?')) {
                toggleModeBtn.disabled = true;
                toggleModeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                
                fetch('{{ route("admin.game-hack.toggle-key-mode") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        alert(res.message);
                        location.reload(); // Reload page to update UI
                    } else {
                        alert('Lỗi: ' + (res.message || 'Không thể chuyển đổi'));
                        toggleModeBtn.disabled = false;
                        toggleModeBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Thử Lại';
                    }
                })
                .catch(err => {
                    alert('Lỗi kết nối: ' + err);
                    toggleModeBtn.disabled = false;
                    toggleModeBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Thử Lại';
                });
            }
        });
    }
});
</script>
@endsection
