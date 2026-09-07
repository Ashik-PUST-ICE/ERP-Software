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
        Schema::create('scheduled_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('social_media_account_id');
            $table->text('content');
            $table->string('post_type')->default('feed');
            $table->string('media_url')->nullable();
            $table->string('media_type')->nullable(); // ivideo, carousel
            $table->timestamp('scheduled_time');
            $table->string('status')->default('pending'); // 
            $table->string('platform_post_id')->nullable(); // 
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamp('posted_at')->nullable();
            $table->json('post_metadata')->nullable(); // platform-specific metadata
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index('scheduled_time');
            $table->index('status');
            $table->index('social_media_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_posts');
    }
};