<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_cuttings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('material_id')->nullable();
            $table->string('marker_number', 60)->nullable();
            $table->decimal('fabric_consumption', 14, 4)->default(0);
            $table->decimal('marker_efficiency', 8, 4)->default(0);
            $table->unsignedInteger('planned_cut_quantity')->default(0);
            $table->unsignedInteger('cut_quantity')->default(0);
            $table->unsignedInteger('panel_quantity')->default(0);
            $table->tinyInteger('status')->default(GARMENT_CUTTING_STATUS_PLANNED);
            $table->date('cutting_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
            $table->index('material_id');
            $table->index('cutting_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_cuttings');
    }
};
