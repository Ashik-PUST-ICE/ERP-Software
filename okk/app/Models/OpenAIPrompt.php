<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * OpenAIPrompt - Stores AI prompt templates (reusable prompts for content generation).
 * Uses same OpenAI model list as config('ai.openai_models').
 */
class OpenAIPrompt extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'open_ai_prompts';

    protected $fillable = [
        'name',
        'prompt',
        'model',
        'is_active',
        'tenant_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
