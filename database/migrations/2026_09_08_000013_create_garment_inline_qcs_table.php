<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_inline_qcs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('inspection_point', 120);
            $table->date('inspection_date');
            $table->unsignedInteger('checked_quantity')->default(0);
            $table->unsignedInteger('passed_quantity')->default(0);
            $table->unsignedInteger('defect_quantity')->default(0);
            $table->string('inspector_name', 120)->nullable();
            $table->tinyInteger('status')->default(GARMENT_QC_STATUS_PENDING);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'inspection_date']);
            $table->index('status');
        });
    }

    public function down(): void { Schema::dropIfExists('garment_inline_qcs'); }
};
