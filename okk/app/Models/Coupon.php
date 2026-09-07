<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name',
        'code',
        'discount_type',
        'amount',
        'start_date',
        'end_date',
        'minimum_spend',
        'usage_limit_per_coupon',
        'usage_limit_per_customer',
        'used_count',
        'status',
        'tenant_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'minimum_spend' => 'decimal:2',
        'usage_limit_per_coupon' => 'integer',
        'usage_limit_per_customer' => 'integer',
        'used_count' => 'integer',
        'status' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Check if the coupon is valid for use.
     */
    public function isValid()
    {
        $now = now();
        return $this->status &&
               $now->between($this->start_date, $this->end_date) &&
               (!$this->usage_limit_per_coupon || $this->used_count < $this->usage_limit_per_coupon);
    }

    /**
     * Check if the coupon can be used by a specific user.
     */
    public function canBeUsedByUser($userId)
    {
        // For simplicity, assuming a json field for user usages, but since we don't have it, just check global limit
        // In a real app, you'd have a pivot table or json field for per-user usage
        return $this->isValid() &&
               (!$this->usage_limit_per_customer || true); // Placeholder, need per-user tracking
    }

    /**
     * Apply the coupon discount to an amount.
     */
    public function applyDiscount($amount)
    {
        if (!$this->isValid()) {
            return $amount;
        }

        if ($this->minimum_spend && $amount < $this->minimum_spend) {
            return $amount;
        }

        if ($this->discount_type === 'fixed') {
            return max(0, $amount - $this->amount);
        } elseif ($this->discount_type === 'percentage') {
            $discount = $amount * ($this->amount / 100);
            return max(0, $amount - $discount);
        }

        return $amount;
    }

    /**
     * Increment usage count when coupon is used.
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
