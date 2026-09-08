<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('garment_grns', function (Blueprint $table) {
            $table->foreignId('purchase_order_id')->nullable()->after('material_id')->constrained('garment_purchase_orders')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('garment_grns', function (Blueprint $table) {
            $table->dropConstrainedForeignId('purchase_order_id');
        });
    }
};
