<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'packageable_id',
        'packageable_type',
        'payment_id',
        'start_date',
        'end_date',
        'status',
        'subscription_type',
        'post_limit',
        
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function packageable()
    {
        return $this->morphTo();
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}