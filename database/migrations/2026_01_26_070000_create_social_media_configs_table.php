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
        Schema::create('social_media_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('platform');
            $table->string('app_id');
            $table->string('app_secret');
            $table->string('redirect_uri')->nullable();
            $table->longText('bearer_token')->nullable();
            $table->longText('permissions')->nullable();
            $table->longText('settings')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_configs');
    }
};