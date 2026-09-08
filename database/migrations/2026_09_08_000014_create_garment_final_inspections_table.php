<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_final_inspections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('inspection_lot', 80);
            $table->date('inspection_date');
            $table->unsignedInteger('lot_quantity')->default(0);
            $table->unsignedInteger('sample_quantity')->default(0);
            $table->string('aql_level', 30)->nullable();
            $table->unsignedInteger('defect_quantity')->default(0);
            $table->unsignedInteger('rejected_quantity')->default(0);
            $table->tinyInteger('result')->default(GARMENT_INSPECTION_RESULT_PENDING);
            $table->string('inspector_name', 120)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'inspection_date']);
            $table->index('result');
        });
    }

    public function down(): void { Schema::dropIfExists('garment_final_inspections'); }
};
