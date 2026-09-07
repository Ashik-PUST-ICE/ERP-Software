<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('user_package_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_hash')->nullable();
            $table->decimal('buy_amount', 10, 2)->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->longText('reasons')->nullable();
            $table->longText('admin_feedback')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_refunds');
    }
};