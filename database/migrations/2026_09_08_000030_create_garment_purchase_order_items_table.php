<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('garment_purchase_orders')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('garment_materials')->restrictOnDelete();
            $table->decimal('quantity', 14, 4);
            $table->decimal('unit_rate', 14, 4);
            $table->decimal('line_total', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_purchase_order_items');
    }
};
