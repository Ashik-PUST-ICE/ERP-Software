<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketAssignee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'assigned_to',
        'assigned_by',
        'is_active',
        
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}