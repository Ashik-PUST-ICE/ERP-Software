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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamp('scheduled_time')->nullable();
            $table->longText('description')->nullable();
            $table->string('platform')->nullable();
            $table->text('account_ids')->nullable(); // Comma-separated account IDs
            $table->longText('content')->nullable();
            $table->string('post_type')->default('Feed');
            $table->text('gallery_image_ids')->nullable();
            $table->text('gallery_video_ids')->nullable();
            $table->string('status')->default('draft'); // draft, active, completed, cancelled
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
        Schema::dropIfExists('campaigns');
    }
};