<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('garment_suppliers')->cascadeOnDelete();
            $table->string('po_number', 50)->unique();
            $table->date('order_date');
            $table->date('expected_date')->nullable();
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->tinyInteger('status')->default(STATUS_PENDING);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_purchase_orders');
    }
};
