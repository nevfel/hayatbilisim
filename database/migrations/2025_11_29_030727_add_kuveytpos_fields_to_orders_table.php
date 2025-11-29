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
        Schema::table('orders', function (Blueprint $table) {
            // KuveytPosOdeme sınıfının beklediği alanlar
            $table->string('billing_tax_number')->nullable()->after('billing_country');
            $table->enum('neden', ['bireysel', 'kurumsal'])->nullable()->after('billing_tax_number');
            $table->string('vergi_no')->nullable()->after('neden');
            $table->string('kimlik_no')->nullable()->after('vergi_no');
            $table->json('payment_extra')->nullable()->after('status');
            $table->text('payment_info')->nullable()->after('payment_extra');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'billing_tax_number',
                'neden',
                'vergi_no',
                'kimlik_no',
                'payment_extra',
                'payment_info',
            ]);
        });
    }
};
