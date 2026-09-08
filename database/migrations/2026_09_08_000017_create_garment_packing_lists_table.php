<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_packing_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->date('packing_date');
            $table->string('carton_number', 60);
            $table->string('color', 80)->nullable();
            $table->string('size', 40)->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('gross_weight', 12, 3)->default(0);
            $table->decimal('net_weight', 12, 3)->default(0);
            $table->decimal('carton_length', 10, 2)->nullable();
            $table->decimal('carton_width', 10, 2)->nullable();
            $table->decimal('carton_height', 10, 2)->nullable();
            $table->tinyInteger('status')->default(GARMENT_PACKING_STATUS_DRAFT);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'carton_number']);
            $table->index(['order_id', 'packing_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_packing_lists');
    }
};
