<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('garment_invoice_payments', function (Blueprint $table) {
            $table->string('gateway', 40)->nullable()->after('payment_method');
            $table->string('gateway_payment_id')->nullable()->after('gateway');
            $table->string('gateway_transaction_id')->nullable()->after('gateway_payment_id');
            $table->string('gateway_status', 30)->default('success')->after('gateway_transaction_id');
            $table->json('gateway_response')->nullable()->after('gateway_status');
            $table->timestamp('paid_at')->nullable()->after('gateway_response');
            $table->unique(['gateway', 'gateway_transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::table('garment_invoice_payments', function (Blueprint $table) {
            $table->dropUnique('garment_invoice_payments_gateway_gateway_transaction_id_unique');
            $table->dropColumn([
                'gateway',
                'gateway_payment_id',
                'gateway_transaction_id',
                'gateway_status',
                'gateway_response',
                'paid_at',
            ]);
        });
    }
};
