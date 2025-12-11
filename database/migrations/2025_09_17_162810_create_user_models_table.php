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
        Schema::create('user_signup', function (Blueprint $table) {
            $table->id('id');
            $table->string('name',40);
            $table->string('username',40);
            $table->string('email',40);
            $table->string('phone',30);
            $table->string('pancard',30);
            $table->string('gstin',40)->nullable();
            $table->string('address',30);
            $table->string('ps',30);
            $table->string('district',30);
            $table->string('state',30);
            $table->string('pincode',20);
            $table->string('password',100);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_models');
    }
};
