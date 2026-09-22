<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class OpenAIPrompt extends Model
{
    use BelongsToTenant;

    protected $table = 'open_ai_prompts';

    protected $fillable = ['tenant_id', 'name', 'prompt_template', 'model', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
