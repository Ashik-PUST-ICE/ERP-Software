<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_costings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique();
            $table->decimal('fabric_cost', 12, 4)->default(0);
            $table->decimal('trims_cost', 12, 4)->default(0);
            $table->decimal('accessories_cost', 12, 4)->default(0);
            $table->decimal('cm_cost', 12, 4)->default(0);
            $table->decimal('washing_cost', 12, 4)->default(0);
            $table->decimal('printing_cost', 12, 4)->default(0);
            $table->decimal('embroidery_cost', 12, 4)->default(0);
            $table->decimal('overhead_cost', 12, 4)->default(0);
            $table->decimal('other_cost', 12, 4)->default(0);
            $table->decimal('total_cost', 12, 4)->default(0);
            $table->decimal('fob_price', 12, 4)->default(0);
            $table->decimal('profit_amount', 12, 4)->default(0);
            $table->decimal('profit_margin', 8, 4)->default(0);
            $table->tinyInteger('status')->default(GARMENT_COSTING_STATUS_DRAFT);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_costings');
    }
};
