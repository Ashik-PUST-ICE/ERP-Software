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
        Schema::create('post_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('social_media_account_id')->constrained();
            $table->foreignId('scheduled_post_id')->nullable()->constrained();
            $table->text('content');
            $table->string('media_url')->nullable();
            $table->string('platform');
            $table->string('platform_post_id')->nullable();
            $table->string('post_type'); // scheduled, manual, failed_retry
            $table->timestamp('posted_at');
            $table->json('response_data')->nullable(); // platform API response
            $table->boolean('success')->default(true);
            $table->text('error_message')->nullable();
            $table->json('post_metadata')->nullable(); // platform-specific metadata
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'platform']);
            $table->index('posted_at');
            $table->index('platform');
            $table->index('success');
            $table->index('scheduled_post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_histories');
    }
};
