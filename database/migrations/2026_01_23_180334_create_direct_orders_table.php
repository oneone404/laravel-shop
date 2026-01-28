<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('direct_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 32)->unique()->comment('Mã đơn hàng unique');
            
            // User info (nullable for guest)
            $table->unsignedBigInteger('user_id')->nullable()->comment('User ID nếu đã đăng nhập');
            $table->string('guest_session', 100)->nullable()->comment('Session ID cho guest');
            $table->string('guest_ip', 45)->nullable()->comment('IP cho tracking');
            
            // Order info
            $table->enum('order_type', ['account', 'random_account'])->comment('Loại đơn hàng');
            $table->unsignedBigInteger('category_id')->comment('Category ID');
            $table->unsignedBigInteger('item_id')->nullable()->comment('Account ID nếu mua cụ thể');
            $table->unsignedBigInteger('group_id')->nullable()->comment('Nhóm random ID');
            $table->unsignedInteger('quantity')->default(1)->comment('Số lượng acc muốn mua (random)');
            
            // Payment info
            $table->decimal('amount', 15, 0)->comment('Tổng tiền thanh toán');
            $table->string('payment_content', 50)->comment('Nội dung CK: DONHANG + order_code');
            
            // Status
            $table->enum('status', ['pending', 'paid', 'completed', 'expired', 'cancelled'])
                  ->default('pending')
                  ->comment('Trạng thái đơn hàng');
            
            // Result
            $table->json('account_data')->nullable()->comment('Thông tin tài khoản sau khi mua');
            $table->string('bank_transaction_id', 100)->nullable()->comment('Mã giao dịch ngân hàng');
            
            // Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->comment('Thời điểm hết hạn');
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('order_code');
            $table->index('payment_content');
            $table->index(['status', 'expires_at']);
            $table->index('user_id');
            $table->index('guest_session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direct_orders');
    }
};
