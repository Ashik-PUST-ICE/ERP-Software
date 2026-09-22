<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('ai_generated_contents', 'is_saved')) {
            Schema::table('ai_generated_contents', function (Blueprint $table) {
                $table->boolean('is_saved')->default(false)->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ai_generated_contents', 'is_saved')) {
            Schema::table('ai_generated_contents', function (Blueprint $table) {
                $table->dropColumn('is_saved');
            });
        }
    }
};
