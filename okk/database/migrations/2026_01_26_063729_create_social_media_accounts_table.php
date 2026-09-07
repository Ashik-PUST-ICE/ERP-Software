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
        Schema::create('social_media_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('platform'); // facebook, instagram, twitter, linkedin, etc.
            $table->string('account_id'); // platform-specific account identifier
            $table->text('access_token');
            $table->string('redirect_uri')->nullable();
            $table->string('page_id')->nullable(); // for Facebook pages
            $table->string('group_id')->nullable(); // for Facebook groups
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('permissions')->nullable(); // platform-specific permissions
            $table->timestamp('token_expires_at')->nullable();
            $table->json('settings')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'platform']);
            $table->index('platform');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_accounts');
    }
};