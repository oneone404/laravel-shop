<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('discount_keys', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã giảm giá
            $table->enum('discount_type', ['percentage', 'fixed_amount']); // Kiểu giảm giá
            $table->decimal('discount_value', 10, 2); // Giá trị giảm
            $table->enum('applicable_to', ['buy_key'])->default('buy_key'); // Áp dụng cho hành động nào
            $table->decimal('min_amount', 10, 2)->nullable(); // Số tiền tối thiểu để áp dụng
            $table->integer('max_discount')->default(1); // Số lần dùng tối đa
            $table->integer('used_count')->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_keys');
    }
};
