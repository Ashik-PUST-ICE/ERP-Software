<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_defect_rejections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('inline_qc_id')->nullable();
            $table->string('defect_type', 120);
            $table->string('section', 100);
            $table->unsignedInteger('defect_quantity')->default(0);
            $table->unsignedInteger('rejected_quantity')->default(0);
            $table->string('root_cause', 180)->nullable();
            $table->text('corrective_action')->nullable();
            $table->tinyInteger('status')->default(GARMENT_DEFECT_STATUS_OPEN);
            $table->date('reported_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'reported_date']);
            $table->index(['section', 'status']);
        });
    }

    public function down(): void { Schema::dropIfExists('garment_defect_rejections'); }
};
