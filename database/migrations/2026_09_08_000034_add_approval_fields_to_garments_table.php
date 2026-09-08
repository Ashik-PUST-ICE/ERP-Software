<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('garment_purchase_orders', function (Blueprint $table) {
            $table->tinyInteger('approval_status')->default(STATUS_PENDING)->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
        Schema::table('garment_shipment_documents', function (Blueprint $table) {
            $table->tinyInteger('approval_status')->default(STATUS_PENDING)->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('garment_purchase_orders', function (Blueprint $table) { $table->dropColumn(['approval_status', 'approved_by', 'approved_at']); });
        Schema::table('garment_shipment_documents', function (Blueprint $table) { $table->dropColumn(['approval_status', 'approved_by', 'approved_at']); });
    }
};
