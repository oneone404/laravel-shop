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
        // database/migrations/xxxx_xx_xx_create_ug_phone_table.php
        Schema::create('ug_phone', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('sever');
            $table->string('hansudung');
            $table->integer('price');
            $table->string('cauhinh');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ug_phone');
    }
};
