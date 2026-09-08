<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_warehouse_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('garment_materials')->cascadeOnDelete();
            $table->foreignId('from_warehouse_id')->constrained('garment_warehouses')->restrictOnDelete();
            $table->foreignId('to_warehouse_id')->constrained('garment_warehouses')->restrictOnDelete();
            $table->decimal('quantity', 14, 4);
            $table->date('transfer_date');
            $table->tinyInteger('status')->default(STATUS_PENDING);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('garment_warehouse_transfers'); }
};
