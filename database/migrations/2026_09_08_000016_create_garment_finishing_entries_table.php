<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_finishing_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->date('finishing_date');
            $table->unsignedInteger('received_quantity')->default(0);
            $table->unsignedInteger('passed_quantity')->default(0);
            $table->unsignedInteger('rework_quantity')->default(0);
            $table->unsignedInteger('rejected_quantity')->default(0);
            $table->tinyInteger('status')->default(GARMENT_FINISHING_STATUS_PENDING);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'finishing_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_finishing_entries');
    }
};
