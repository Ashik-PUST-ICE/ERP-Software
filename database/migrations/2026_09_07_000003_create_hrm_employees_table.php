<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('joining_date');
            $table->foreignId('department_id')->constrained('hrm_departments')->onDelete('cascade');
            $table->foreignId('designation_id')->constrained('hrm_designations')->onDelete('cascade');
            $table->tinyInteger('employment_type')->default(EMPLOYMENT_TYPE_FULL_TIME);
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();
            $table->tinyInteger('status')->default(EMPLOYEE_STATUS_ACTIVE);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_employees');
    }
};
