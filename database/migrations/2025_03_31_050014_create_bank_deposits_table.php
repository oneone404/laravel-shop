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
        Schema::create('bank_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // Mã giao dịch
            $table->string('transaction_hash', 64)->nullable()->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết với users
            $table->string('account_number'); // Số tài khoản ngân hàng
            $table->integer('amount'); // Số tiền giao dịch
            $table->string('content'); // Nội dung chuyển tiền
            $table->enum('bank', [
                'VPBank',
                'TPBank',
                'VietinBank',
                'ACB',
                'BIDV',
                'MBBank',
                'OCB',
                'KienLongBank',
                'MSB'
            ]); // Loại ngân hàng
            $table->unsignedInteger('bank_session')->default(1);
            $table->timestamps(); // Timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_deposits');
    }
};
