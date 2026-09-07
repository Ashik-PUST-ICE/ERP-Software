<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSeenUnseen extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'created_by',
        'is_seen',
        
    ];
}