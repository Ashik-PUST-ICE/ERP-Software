<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIChatMessage extends Model
{
    protected $fillable = ['conversation_id', 'role', 'content', 'model'];

    public function conversation()
    {
        return $this->belongsTo(AIChatConversation::class, 'conversation_id');
    }
}
