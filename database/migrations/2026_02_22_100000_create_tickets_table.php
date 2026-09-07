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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->string('order_id')->nullable();
            $table->string('ticket_id')->nullable();
            $table->string('ticket_title')->nullable();
            $table->longText('ticket_description');
            $table->unsignedBigInteger('last_reply_id')->nullable();
            $table->unsignedBigInteger('last_reply_by')->nullable();
            $table->timestamp('last_reply_time')->nullable();
            $table->integer('status')->default(0);
            $table->integer('priority')->default(0);
            $table->text('file_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};