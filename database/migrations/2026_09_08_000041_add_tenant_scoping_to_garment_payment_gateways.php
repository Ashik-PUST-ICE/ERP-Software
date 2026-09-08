<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tenant columns and tenant-aware unique indexes are created with the
        // gateway tables migration.
    }

    public function down(): void
    {
        // Kept as a no-op for compatibility with databases created by
        // earlier versions of the gateway table migration.
    }
};
