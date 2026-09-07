<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'order_id',
        'ticket_id',
        'ticket_title',
        'ticket_description',
        'last_reply_id',
        'last_reply_by',
        'last_reply_time',
        'status',
        'priority',
        'file_id',
        'created_by',
        
    ];

    public function assignee()
    {
        return $this->hasMany(TicketAssignee::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'order_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function userPackage()
    {
        return $this->belongsTo(UserPackage::class, 'order_id');
    }
}