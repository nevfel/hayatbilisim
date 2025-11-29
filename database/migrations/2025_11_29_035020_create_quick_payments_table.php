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
        Schema::create('quick_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->boolean('payment_ok')->default(false);

            // Müşteri bilgileri
            $table->string('gon_email');
            $table->string('gon_adsoyad');
            $table->string('gon_phone')->nullable();

            // Ödeme bilgileri
            $table->text('payment_info')->nullable();
            $table->json('payment_extra')->nullable();
            $table->datetime('payment_date')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            // İndeksler
            $table->index('payment_number');
            $table->index('status');
            $table->index('payment_ok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_payments');
    }
};
