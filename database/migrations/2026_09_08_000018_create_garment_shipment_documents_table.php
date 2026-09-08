<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_shipment_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('document_type', 40);
            $table->string('document_number', 80);
            $table->date('document_date');
            $table->string('shipper', 160)->nullable();
            $table->string('consignee', 160)->nullable();
            $table->string('port_of_loading', 120)->nullable();
            $table->string('port_of_discharge', 120)->nullable();
            $table->string('carrier', 120)->nullable();
            $table->date('shipment_date')->nullable();
            $table->tinyInteger('status')->default(GARMENT_SHIPMENT_STATUS_DRAFT);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'document_type', 'document_number']);
            $table->index(['order_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_shipment_documents');
    }
};
