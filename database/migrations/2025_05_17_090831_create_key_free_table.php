<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
Schema::create('key_free', function (Blueprint $table) {
    $table->id();
    $table->string('key_value');
    $table->string('game');
    $table->integer('time_use');
    $table->timestamps();  // Thêm dòng này để tạo 2 cột created_at và updated_at
});

    }

    public function down(): void
    {
        Schema::dropIfExists('key_free');
    }
};
