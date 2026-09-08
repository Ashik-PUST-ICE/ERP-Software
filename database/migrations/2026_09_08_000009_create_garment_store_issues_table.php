<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_store_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('issue_number', 60)->unique();
            $table->string('section', 100);
            $table->string('line_name', 100)->nullable();
            $table->date('issue_date');
            $table->decimal('issued_quantity', 14, 4);
            $table->decimal('returned_quantity', 14, 4)->default(0);
            $table->decimal('net_quantity', 14, 4)->default(0);
            $table->tinyInteger('status')->default(GARMENT_ISSUE_STATUS_ISSUED);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['material_id', 'issue_date']);
            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_store_issues');
    }
};
