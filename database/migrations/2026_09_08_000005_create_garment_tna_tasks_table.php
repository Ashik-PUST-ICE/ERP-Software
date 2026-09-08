<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_tna_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('task_name', 150);
            $table->string('task_type', 100)->nullable();
            $table->date('planned_date');
            $table->date('actual_date')->nullable();
            $table->tinyInteger('status')->default(GARMENT_TNA_STATUS_PENDING);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'planned_date']);
            $table->index('employee_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_tna_tasks');
    }
};
