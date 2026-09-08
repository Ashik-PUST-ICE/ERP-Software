<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_buyers', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_code', 30)->unique();
            $table->string('company_name', 150);
            $table->string('contact_person', 120)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('website', 150)->nullable();
            $table->text('office_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('payment_terms', 150)->nullable();
            $table->text('contract_terms')->nullable();
            $table->tinyInteger('status')->default(STATUS_ACTIVE);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_buyers');
    }
};
