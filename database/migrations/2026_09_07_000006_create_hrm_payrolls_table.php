<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_month'); // e.g. "2026-09"
            $table->foreignId('employee_id')->constrained('hrm_employees')->onDelete('cascade');
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);
            $table->tinyInteger('payment_status')->default(PAYMENT_STATUS_PENDING);
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['payroll_month', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_payrolls');
    }
};
