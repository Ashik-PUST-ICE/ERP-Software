<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AIChatConversation extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'user_id', 'title', 'last_message_at'];

    protected $casts = ['last_message_at' => 'datetime'];

    public function messages()
    {
        return $this->hasMany(AIChatMessage::class, 'conversation_id');
    }
}
