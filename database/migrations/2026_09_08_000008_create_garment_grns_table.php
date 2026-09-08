<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_grns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->string('grn_number', 60)->unique();
            $table->string('supplier_name', 150);
            $table->string('purchase_reference', 100)->nullable();
            $table->date('received_date');
            $table->decimal('ordered_quantity', 14, 4)->default(0);
            $table->decimal('received_quantity', 14, 4);
            $table->decimal('rejected_quantity', 14, 4)->default(0);
            $table->decimal('accepted_quantity', 14, 4)->default(0);
            $table->decimal('unit_cost', 14, 4)->default(0);
            $table->tinyInteger('status')->default(GARMENT_GRN_STATUS_RECEIVED);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['material_id', 'received_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_grns');
    }
};
