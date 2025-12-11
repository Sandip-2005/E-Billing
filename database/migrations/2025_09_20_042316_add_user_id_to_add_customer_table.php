<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('add_customer', function (Blueprint $table) {
            if (!Schema::hasColumn('add_customer', 'user_id')) {
            $table->foreignId('user_id')
                  ->after('id')
                  ->constrained('user_signup')
                  ->onDelete('cascade');
        }
        });
    }

    public function down(): void
    {
        Schema::table('add_customer', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
