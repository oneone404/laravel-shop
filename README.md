# Laravel Shop - Game Account & Services

[![Laravel Version](https://img.shields.io/badge/Laravel-v10+-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-v8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

Dự án website bán tài khoản game và dịch vụ với giao diện hiện đại phong cách **Gen Z & Glassmorphism**.

## ✨ Tính năng nổi bật
- **Giao diện đỉnh cao**: Thiết kế theo xu hướng Glassmorphism (kính mờ) hiện đại, hỗ trợ tối ưu cả Dark mode và Light mode.
- **Header thông minh**: Điều hướng mượt mà, tích hợp số dư và thông tin người dùng trực quan.
- **Hệ thống Tab Luxury**: Chuyển đổi giữa Tài khoản và Dịch vụ với phong cách Pill-shaped cao cấp.
- **Đăng nhập mạng xã hội**: Hỗ trợ Google và Facebook Login bảo mật.
- **Quản lý nạp tiền**: Tích hợp modal nạp tiền chuyên nghiệp ngay trên Header.
- **Bảo mật**: Hệ thống bảo mật thông tin nhạy cảm qua `.env` và Secret Scanning.

## 🛠 Yêu cầu hệ thống
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL >= 5.7

## 🚀 Hướng dẫn cài đặt (Môi trường mới)

### 1. Clone dự án
```bash
git clone https://github.com/oneone404/laravel-shop.git
cd laravel-shop
```

### 2. Cài đặt các gói phụ thuộc
Cài đặt PHP dependencies:
```bash
composer install
```
Cài đặt Frontend dependencies:
```bash
npm install
npm run build # hoặc npm run dev để phát triển
```

### 3. Cấu hình môi trường (.env)
Sao chép file mẫu và cấu hình:
```bash
cp .env.example .env
```
Mở file `.env` và cập nhật thông tin Database:
```env
DB_DATABASE=ten_database_cua_ban
DB_USERNAME=root
DB_PASSWORD=
```
Cấu hình Google/Facebook Login (nếu cần):
```env
GOOGLE_CLIENT_ID=your_id
GOOGLE_CLIENT_SECRET=your_secret
```

### 4. Khởi tạo Application Key
```bash
php artisan key:generate
```

### 5. Cấu hình Database & Seed dữ liệu
Tạo migration và nạp dữ liệu mẫu:
```bash
php artisan migrate
php artisan db:seed --class=ConfigSeeder
```

### 6. Tạo đường dẫn Storage
```bash
php artisan storage:link
```

### 7. Chạy dự án
```bash
php artisan serve
```
Truy cập: [http://localhost:8000](http://localhost:8000)

## 📌 Lưu ý khi phát triển
- Luôn kiểm tra file `.env` trước khi chạy để đảm bảo `APP_KEY` đã được tạo.
- Các file CSS quan trọng nằm tại: `public/assets/css/global.css` và `home.css`.
- Tránh ghi đè các mã Secret trực tiếp vào Code, hãy luôn sử dụng hàm `env()`.

## 🤝 Liên hệ
- **Tác giả**: oneone404
- **Repository**: [laravel-shop](https://github.com/oneone404/laravel-shop)
