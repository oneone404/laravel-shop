<?php
/**
 * Copyright (c) 2025 FPT University
 *
 * @author    Phạm Hoàng Tuấn
 * @email     phamhoangtuanqn@gmail.com
 * @facebook  fb.com/phamhoangtuanqn
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_accounts', function (Blueprint $table) {
            $table->id(); // ID tài khoản
            $table->foreignId('game_category_id')->constrained()->cascadeOnDelete(); // Liên kết danh mục
            $table->string('account_name'); // Tên tài khoản
            $table->string('password'); // Mật khẩu tài khoản
            $table->bigInteger('price')->unsigned(); // Giá tiền (>0)
            $table->enum('status', ['available', 'sold'])->default('available'); // Trạng thái
            $table->integer('server')->nullable(); // Máy chủ (1-13 hoặc tên)
            $table->enum('registration_type', ['virtual', 'real'])->nullable(); // Đăng ký (Ảo/Thật)
            $table->enum('planet', ['earth', 'namek', 'xayda'])->nullable(); // Hành tinh
            $table->boolean('earring')->default(false);
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null'); // Người mua (FK users)
            $table->text('note')->nullable(); // Ghi chú (Nếu có)
            $table->text('thumb')->nullable(); // List of images
            $table->text('images')->nullable(); // List of images
            $table->json('accounts_data')->nullable()->comment('JSON array of accounts for random type');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('views')->default(0);
            $table->integer('sold_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_accounts');
    }
};
