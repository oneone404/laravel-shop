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
        Schema::create('key_vips', function (Blueprint $table) {
            $table->id();
            $table->string('game');
            $table->string('key_value');
            $table->integer('time_use');
            $table->integer('price');
            $table->integer('device_limit')->default(1);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_vips');
    }
};
