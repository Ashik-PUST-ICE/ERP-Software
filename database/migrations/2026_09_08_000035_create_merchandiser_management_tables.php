<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_order_merchandisers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('garment_orders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->unique(['order_id', 'user_id']);
        });
        Schema::create('garment_merchandiser_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('garment_orders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 160);
            $table->date('due_date')->nullable();
            $table->tinyInteger('priority')->default(2);
            $table->tinyInteger('status')->default(STATUS_PENDING);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        Schema::create('garment_buyer_communications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('garment_orders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('channel', 30);
            $table->dateTime('communicated_at');
            $table->text('subject')->nullable();
            $table->text('notes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_buyer_communications');
        Schema::dropIfExists('garment_merchandiser_tasks');
        Schema::dropIfExists('garment_order_merchandisers');
    }
};
