<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('garment_materials')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained('garment_warehouses')->nullOnDelete();
            $table->string('movement_type', 30);
            $table->decimal('quantity', 14, 4);
            $table->decimal('balance_after', 14, 4);
            $table->string('reference_type', 60)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['material_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_stock_movements');
    }
};
