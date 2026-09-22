<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AIGeneratedContent extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'user_id', 'content_type', 'prompt', 'generated_text',
        'generated_media_path', 'model', 'is_saved',
    ];

    protected $casts = ['is_saved' => 'boolean'];
}
