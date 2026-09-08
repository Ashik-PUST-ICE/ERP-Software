<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Style extends Model
{
    use HasFactory;

    protected $table = 'garment_styles';

    protected $fillable = [
        'style_code',
        'style_name',
        'product_type',
        'description',
        'season',
        'status',
        'notes',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(GarmentOrder::class);
    }
}
