<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'garment_suppliers';

    protected $fillable = [
        'supplier_code', 'company_name', 'contact_person', 'email', 'phone',
        'category', 'address', 'payment_terms', 'status', 'notes',
    ];
}
