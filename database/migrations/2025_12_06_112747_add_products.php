<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // If products belong to users
            $table->unsignedBigInteger('shop_id'); // If products belong to shops
            $table->string('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user_signup')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shop_profile')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};