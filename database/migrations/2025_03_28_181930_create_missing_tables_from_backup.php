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
        // Create game_hacks table directly with all fields
        if (!Schema::hasTable('game_hacks')) {
            Schema::create('game_hacks', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('version')->nullable();
                $table->text('description')->nullable();
                $table->string('logo')->nullable();
                $table->string('thumbnail')->nullable();
                $table->string('download_link')->nullable();
                $table->string('download_link_global')->nullable();
                $table->string('api_hack')->nullable();
                $table->string('api_type')->nullable();
                $table->string('solink')->nullable();
                $table->boolean('active')->default(true);
                $table->string('platform')->default('Windows');
                $table->string('size')->nullable();
                $table->json('images')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('key_purchase_history')) {
            Schema::create('key_purchase_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('game');
                $table->string('key_value');
                $table->integer('device_count')->default(1);
                $table->string('time_use')->nullable();
                $table->decimal('price', 15, 2)->default(0);
                $table->integer('reset_count')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('free_keys')) {
            Schema::create('free_keys', function (Blueprint $table) {
                $table->id();
                $table->string('token', 64)->unique();
                $table->string('key_value');
                $table->integer('hackviet_key_id')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('created_at_api')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->integer('duration_hours')->default(24);
                $table->boolean('is_vip')->default(false);
                $table->timestamps();

                $table->index('token');
                $table->index('ip_address');
                $table->index('expires_at');
            });
        }

        if (!Schema::hasTable('cardzing_deposit')) {
            Schema::create('cardzing_deposit', function (Blueprint $table) {
                $table->id();
                $table->string('cardSerial', 50);
                $table->string('cardPassword', 50);
                $table->enum('status', ['available', 'sold'])->default('available');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('game_key_purchases')) {
            Schema::create('game_key_purchases', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('game')->nullable();
                $table->string('key_duration')->nullable();
                $table->decimal('price', 15, 2)->nullable();
                $table->string('payment_method')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_purchase_history');
        Schema::dropIfExists('game_hacks');
        Schema::dropIfExists('free_keys');
        Schema::dropIfExists('cardzing_deposit');
        Schema::dropIfExists('game_key_purchases');
    }
};
