<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicePayment extends Model
{
    use HasFactory;

    protected $table = 'garment_invoice_payments';
    protected $fillable = [
        'invoice_id', 'payment_date', 'amount', 'payment_method', 'reference', 'notes',
        'gateway', 'gateway_payment_id', 'gateway_transaction_id', 'gateway_status',
        'gateway_response', 'paid_at',
    ];
    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:4',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
