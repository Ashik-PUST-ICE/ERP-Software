<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_shipment_tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_document_id')->constrained('garment_shipment_documents')->cascadeOnDelete();
            $table->string('location', 150)->nullable();
            $table->string('status', 60);
            $table->dateTime('event_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['shipment_document_id', 'event_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_shipment_tracking_events');
    }
};
