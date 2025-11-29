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
        // Önce foreign key constraint'leri kaldır (index'i kullanıyorlar)
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);
        });
        
        // Session_id kolonunu ekle
        Schema::table('carts', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('user_id');
        });
        
        // Eski unique index'i sil
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'product_id']);
        });
        
        // Yeni unique constraint'leri oluştur
        Schema::table('carts', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id'], 'carts_user_product_unique');
            $table->unique(['session_id', 'product_id'], 'carts_session_product_unique');
        });
        
        // Foreign key constraint'leri yeniden ekle
        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Foreign key constraint'leri kaldır
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);
        });
        
        // Yeni unique index'leri sil
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique('carts_session_product_unique');
            $table->dropUnique('carts_user_product_unique');
        });
        
        // Eski unique constraint'i yeniden oluştur
        Schema::table('carts', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id']);
        });
        
        // Session_id kolonunu sil
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('session_id');
        });
        
        // Foreign key constraint'leri yeniden ekle
        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
