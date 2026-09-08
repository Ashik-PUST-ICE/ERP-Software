<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;

    protected $table = 'garment_buyers';

    protected $fillable = [
        'buyer_code',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'website',
        'office_address',
        'shipping_address',
        'country',
        'currency',
        'payment_terms',
        'contract_terms',
        'status',
        'notes',
    ];

    public function orders()
    {
        return $this->hasMany(GarmentOrder::class);
    }
}
