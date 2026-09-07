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
        Schema::table('post_histories', function (Blueprint $table) {
            $table->integer('likes_count')->default(0)->after('post_metadata');
            $table->integer('comments_count')->default(0)->after('likes_count');
            $table->integer('shares_count')->default(0)->after('comments_count');
            $table->integer('reach_count')->default(0)->after('shares_count');
            $table->integer('views_count')->default(0)->after('reach_count');
            $table->timestamp('engagement_fetched_at')->nullable()->after('views_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_histories', function (Blueprint $table) {
            $table->dropColumn([
                'likes_count',
                'comments_count',
                'shares_count',
                'reach_count',
                'views_count',
                'engagement_fetched_at',
            ]);
        });
    }
};