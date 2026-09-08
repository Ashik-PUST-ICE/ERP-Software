<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('garment_production_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('line_name', 100);
            $table->date('attendance_date');
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('production_quantity')->default(0);
            $table->decimal('working_hours', 6, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'attendance_date']);
            $table->index(['line_name', 'attendance_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('garment_production_attendances'); }
};
