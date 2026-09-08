<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->string('title');
            $table->string('slug');
            $table->boolean('status')->default(false);
            $table->unsignedTinyInteger('mode')->nullable();
            $table->string('url')->nullable();
            $table->text('key')->nullable();
            $table->text('secret')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('garment_payment_gateway_currencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->foreignId('gateway_id')->constrained('garment_payment_gateways')->cascadeOnDelete();
            $table->string('currency', 8);
            $table->decimal('conversion_rate', 14, 6)->default(1);
            $table->timestamps();
            $table->unique(['tenant_id', 'gateway_id', 'currency']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_payment_gateway_currencies');
        Schema::dropIfExists('garment_payment_gateways');
    }
};
