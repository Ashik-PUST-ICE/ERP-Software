<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_styles', function (Blueprint $table) {
            $table->id();
            $table->string('style_code', 50)->unique();
            $table->string('style_name', 150);
            $table->string('product_type', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('season', 80)->nullable();
            $table->tinyInteger('status')->default(STATUS_ACTIVE);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('garment_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('style_id')->after('buyer_id');
            $table->index('style_id');
        });

        Schema::table('garment_orders', function (Blueprint $table) {
            $table->dropColumn('style_code');
        });
    }

    public function down(): void
    {
        Schema::table('garment_orders', function (Blueprint $table) {
            $table->dropIndex(['style_id']);
            $table->dropColumn('style_id');
            $table->string('style_code', 50)->after('buyer_id');
        });

        Schema::dropIfExists('garment_styles');
    }
};
