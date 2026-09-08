<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name', 120);
            $table->string('address')->nullable();
            $table->tinyInteger('status')->default(STATUS_ACTIVE);
            $table->timestamps();
        });

        Schema::table('garment_materials', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->nullable()->after('warehouse')->constrained('garment_warehouses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('garment_materials', function (Blueprint $table) {
            $table->dropConstrainedForeignId('warehouse_id');
        });
        Schema::dropIfExists('garment_warehouses');
    }
};
