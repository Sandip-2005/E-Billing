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
        Schema::create('shop_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_signup')->onDelete('cascade');
            $table->string('shop_name');
            $table->string('shop_contact');
            $table->string('gst_number');
            $table->text('shop_address');
            $table->string('shop_logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_profile');
    }
};
