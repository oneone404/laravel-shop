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
        Schema::create('goi_napshop', function (Blueprint $table) {
            $table->id();
            $table->string('product_ID')->unique();
            $table->string('productName');
            $table->string('image');
            $table->decimal('price', 12, 2);
            $table->boolean('active')->default(true);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goi_napshop');
    }
};
