<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrawHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('draw_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('description'); // Phần thưởng
            $table->date('daily'); // Ngày bốc, giới hạn 1 lần/ngày
            $table->string('ip_address')->nullable();       // IP người dùng
            $table->string('device_id')->nullable();        // Device ID (frontend gửi lên)
            $table->text('user_agent')->nullable();         // Thông tin trình duyệt/thết bị
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Unique để đảm bảo user chỉ được bốc 1 lần/ngày
            $table->unique(['user_id', 'daily']);

            // Nếu bạn muốn tránh dùng cùng device_id hoặc ip_address bốc cùng ngày,
            // bạn có thể thêm index thay vì unique vì device_id/ip có thể null
            $table->index(['device_id', 'daily']);
            $table->index(['ip_address', 'daily']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('draw_histories');
    }
}
