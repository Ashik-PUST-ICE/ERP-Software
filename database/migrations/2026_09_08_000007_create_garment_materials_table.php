<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_materials', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 50)->unique();
            $table->string('item_name', 150);
            $table->string('category', 30);
            $table->string('unit', 30);
            $table->decimal('opening_stock', 14, 4)->default(0);
            $table->decimal('current_stock', 14, 4)->default(0);
            $table->decimal('reorder_level', 14, 4)->default(0);
            $table->string('warehouse', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->tinyInteger('status')->default(STATUS_ACTIVE);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['category', 'status']);
            $table->index('current_stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_materials');
    }
};
