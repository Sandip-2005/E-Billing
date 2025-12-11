<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('user_signup')->onDelete('cascade');
                $table->foreignId('shop_id')->constrained('shop_profile')->onDelete('cascade');
                $table->foreignId('customer_id')->nullable()->constrained('add_customer')->onDelete('set null');
                $table->string('shop_phone')->nullable();
                $table->string('shop_gst')->nullable();
                $table->string('shop_address')->nullable();
                $table->date('bill_date');
                $table->decimal('sub_total', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('total', 10, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
