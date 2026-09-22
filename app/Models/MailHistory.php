<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_user_id',
        'host',
        'email',
        'subject',
        'message',
        'status',
        'user_id',
        'date',
        'error',
    ];

    protected $casts = [
        'date' => 'datetime',
        'status' => 'integer',
    ];
}
