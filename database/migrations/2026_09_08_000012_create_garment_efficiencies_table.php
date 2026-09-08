<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_efficiencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('machine_name', 100)->nullable();
            $table->string('line_name', 100)->nullable();
            $table->date('work_date');
            $table->unsignedInteger('working_minutes')->default(0);
            $table->unsignedInteger('target_output')->default(0);
            $table->unsignedInteger('actual_output')->default(0);
            $table->decimal('efficiency_percentage', 8, 4)->default(0);
            $table->tinyInteger('status')->default(GARMENT_EFFICIENCY_STATUS_RECORDED);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'work_date']);
            $table->index(['order_id', 'work_date']);
            $table->index('machine_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_efficiencies');
    }
};
