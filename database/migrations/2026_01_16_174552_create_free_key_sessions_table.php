<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('free_key_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('session_code', 32)->unique()->nullable();
            $table->string('short_url')->nullable();
            $table->string('client_id')->nullable();
            $table->string('ip_address', 45);
            $table->string('status', 20)->default('pending'); // pending, activated, expired
            $table->string('key_value')->nullable(); // Chỉ có sau khi activate
            $table->unsignedBigInteger('hackviet_key_id')->nullable();
            $table->foreignId('game_hack_id')->nullable()->constrained('game_hacks')->onDelete('cascade');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();

            $table->index(['token', 'status']);
            $table->index(['ip_address', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('free_key_sessions');
    }
};
