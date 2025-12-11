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
        Schema::create('add_customer', function (Blueprint $table) {
            $table->id('id');
            $table->string('customer_name',40);
            $table->string('email',40)->nullable();
            $table->string('phone_number',30);
            $table->string('address',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_customer');
    }
};
