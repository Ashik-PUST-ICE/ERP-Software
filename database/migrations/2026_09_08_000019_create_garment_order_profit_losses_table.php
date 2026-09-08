<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_order_profit_losses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique();
            $table->decimal('sales_revenue', 14, 4)->default(0);
            $table->decimal('material_cost', 14, 4)->default(0);
            $table->decimal('production_cost', 14, 4)->default(0);
            $table->decimal('salary_cost', 14, 4)->default(0);
            $table->decimal('overhead_cost', 14, 4)->default(0);
            $table->decimal('other_cost', 14, 4)->default(0);
            $table->decimal('total_cost', 14, 4)->default(0);
            $table->decimal('profit_amount', 14, 4)->default(0);
            $table->decimal('profit_margin', 8, 4)->default(0);
            $table->tinyInteger('status')->default(GARMENT_PROFIT_LOSS_STATUS_DRAFT);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_order_profit_losses');
    }
};
