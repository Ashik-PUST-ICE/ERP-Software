<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id');
            $table->string('style_code', 50);
            $table->string('order_number', 60)->unique();
            $table->text('product_description')->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 4)->default(0);
            $table->date('order_date');
            $table->date('delivery_date');
            $table->tinyInteger('status')->default(GARMENT_ORDER_STATUS_PENDING);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['buyer_id', 'status']);
            $table->index('delivery_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_orders');
    }
};
