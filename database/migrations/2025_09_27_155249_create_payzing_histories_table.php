<?php

// database/migrations/2025_09_27_000000_create_payzing_histories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payzing_histories', function (Blueprint $t) {
            $t->id();

            // Ai nạp & nạp gói gì
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('service_id')->constrained('game_services');
            $t->foreignId('package_id')->constrained('service_packages');

            // Tài khoản/game
            $t->string('role_id', 64);                         // ID game (role)
            $t->string('server', 64)->nullable();

            // NCC & đơn mua
            $t->string('provider', 50)->default('cardws');     // ví dụ: cardws/tenmien
            $t->string('request_id', 64)->index();             // để redownload/đối soát
            $t->string('service_code', 50)->nullable();        // mã sản phẩm bên NCC (vd: ZING)
            $t->unsignedInteger('value')->nullable();          // mệnh giá
            $t->unsignedInteger('qty')->default(1);

            // Trạng thái
            $t->string('status', 20)->index();                 // pending|success|error

            // Thẻ nhận được
            $t->string('card_serial', 64)->nullable();
            $t->text('card_pin_enc')->nullable();              // PIN đã mã hoá

            // Trạng thái phía NCC
            $t->integer('provider_status')->nullable();        // theo bảng mã lỗi NCC
            $t->string('provider_message', 255)->nullable();

            // Mô tả chi tiết (human-readable)
            $t->text('description')->nullable();

            // Lưu thêm raw payload (tuỳ chọn)
            $t->json('meta')->nullable();

            $t->timestamps();

            $t->index(['role_id','service_id','package_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('payzing_histories');
    }
};
