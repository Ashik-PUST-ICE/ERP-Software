<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('garment_incentives', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('order_id')->nullable(); $table->string('employee_name', 160); $table->date('production_date'); $table->string('operation', 120); $table->unsignedInteger('production_quantity'); $table->decimal('piece_rate', 12, 4); $table->decimal('incentive_amount', 14, 4)->default(0); $table->tinyInteger('status')->default(GARMENT_INCENTIVE_STATUS_DRAFT); $table->text('notes')->nullable(); $table->timestamps(); $table->index(['production_date','status']);
        });
    }
    public function down(): void { Schema::dropIfExists('garment_incentives'); }
};
