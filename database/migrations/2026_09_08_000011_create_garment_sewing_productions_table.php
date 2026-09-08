<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_sewing_productions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('line_name', 100);
            $table->date('production_date');
            $table->unsignedInteger('daily_target')->default(0);
            $table->unsignedInteger('hourly_target')->default(0);
            $table->unsignedInteger('hourly_output')->default(0);
            $table->unsignedInteger('total_output')->default(0);
            $table->unsignedInteger('wip_quantity')->default(0);
            $table->tinyInteger('status')->default(GARMENT_SEWING_STATUS_RUNNING);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'production_date']);
            $table->index(['line_name', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_sewing_productions');
    }
};
