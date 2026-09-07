<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 12, 2)->default(0.00);
            $table->decimal('old_monthly_price', 12, 2)->default(0.00);
            $table->decimal('yearly_price', 12, 2)->default(0.00);
            $table->decimal('old_yearly_price', 12, 2)->default(0.00);
            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_monthly_plan_id')->nullable();
            $table->string('stripe_yearly_plan_id')->nullable();
            $table->string('paypal_product_id')->nullable();
            $table->string('paypal_monthly_plan_id')->nullable();
            $table->string('paypal_yearly_plan_id')->nullable();
            $table->tinyInteger('ai_enabled')->default(0);
            $table->text('provider_limit')->nullable();
            $table->text('features')->nullable();
            $table->integer('post_limit')->default(0);
            $table->tinyInteger('status')->default(0)->comment('1 = active, 0 = inactive');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};