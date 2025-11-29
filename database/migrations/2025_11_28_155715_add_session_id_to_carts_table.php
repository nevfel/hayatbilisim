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
        Schema::table('carts', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('user_id');
            $table->dropUnique(['user_id', 'product_id']);
        });
        
        // Unique constraint'i yeniden oluştur (user_id veya session_id ile)
        Schema::table('carts', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id'], 'carts_user_product_unique');
            $table->unique(['session_id', 'product_id'], 'carts_session_product_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique('carts_session_product_unique');
            $table->dropUnique('carts_user_product_unique');
            $table->unique(['user_id', 'product_id']);
            $table->dropColumn('session_id');
        });
    }
};
