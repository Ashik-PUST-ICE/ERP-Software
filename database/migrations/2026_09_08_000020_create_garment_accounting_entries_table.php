<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('garment_accounting_entries', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('order_id')->nullable(); $table->string('entry_type', 10); $table->string('account_code', 40); $table->string('account_name', 120); $table->date('entry_date'); $table->decimal('debit', 14, 4)->default(0); $table->decimal('credit', 14, 4)->default(0); $table->string('reference', 100)->nullable(); $table->tinyInteger('status')->default(GARMENT_ACCOUNTING_ENTRY_DRAFT); $table->text('description')->nullable(); $table->timestamps(); $table->index(['entry_type','entry_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('garment_accounting_entries'); }
};
