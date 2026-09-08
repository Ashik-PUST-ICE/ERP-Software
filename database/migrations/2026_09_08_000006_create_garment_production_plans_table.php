<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_production_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('line_name', 100);
            $table->unsignedInteger('planned_quantity');
            $table->unsignedInteger('daily_target');
            $table->unsignedInteger('capacity_per_day')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status')->default(GARMENT_PLAN_STATUS_DRAFT);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_production_plans');
    }
};
