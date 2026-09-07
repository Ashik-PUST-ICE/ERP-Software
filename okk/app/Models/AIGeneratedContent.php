<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * AIGeneratedContent (GenerateContent) - Stores generated AI content.
 * Model used for generation comes from config('ai.openai_models') via settings.
 */
class AIGeneratedContent extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'ai_generated_contents';

    protected $fillable = [
        'user_id',
        'prompt',
        'content_type',
        'generated_text',
        'generated_media_path',
        'model',
        'is_saved',
        'tenant_id',
    ];

    protected $casts = [
        'content_type' => 'string',
        'is_saved' => 'boolean',
    ];

    public function getMediaUrlAttribute(): ?string
    {
        if (empty($this->generated_media_path)) {
            return null;
        }
        return asset('storage/' . $this->generated_media_path);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
