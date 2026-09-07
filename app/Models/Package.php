<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'monthly_price',
        'old_monthly_price',
        'yearly_price',
        'old_yearly_price',
        'stripe_product_id',
        'stripe_monthly_plan_id',
        'stripe_yearly_plan_id',
        'paypal_product_id',
        'paypal_monthly_plan_id',
        'paypal_yearly_plan_id',
        'ai_enabled',
        'provider_limit',
        'features',
        'post_limit',
        'status',
        
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'old_monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'old_yearly_price' => 'decimal:2',
        'ai_enabled' => 'boolean',
        'provider_limit' => 'array',
        'features' => 'array',
        'post_limit' => 'integer',
        'status' => 'boolean',
    ];


    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
    public function userPackage()
    {
        return $this->morphMany(UserPackage::class, 'packageable');
    }
}