<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    protected $table = 'transactions';

    protected $fillable = [
        'site_id',
        'app_id',
        'user_id',
        'payment_id',
        'reference_id',
        'type',
        'tnxId',
        'amount',
        'purpose',
        'payment_time',
        'payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
