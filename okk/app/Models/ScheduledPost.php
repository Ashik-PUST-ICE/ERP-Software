<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduledPost extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'user_id',
        'social_media_account_id',
        'campaign_id',
        'content',
        'post_type',
        'media_url',
        'media_type',
        'scheduled_time',
        'status',
        'platform_post_id',
        'error_message',
        'retry_count',
        'posted_at',
        'post_metadata',
        'tenant_id',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'posted_at' => 'datetime',
        'post_metadata' => 'array',
        'retry_count' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function socialMediaAccount(): BelongsTo
    {
        return $this->belongsTo(SocialMediaAccount::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function postHistories(): HasMany
    {
        return $this->hasMany(PostHistory::class, 'scheduled_post_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDue($query)
    {
        return $query->where('scheduled_time', '<=', now())->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    // Helper methods
    public function isDue(): bool
    {
        return $this->scheduled_time <= now() && $this->status === 'pending';
    }

    public function canRetry(): bool
    {
        return $this->status === 'failed' && $this->retry_count < 3;
    }

    public function markAsPosted(string $platformPostId = null, array $metadata = []): void
    {
        $this->update([
            'status' => 'posted',
            'platform_post_id' => $platformPostId,
            'posted_at' => now(),
            'post_metadata' => $metadata,
            'error_message' => null,
        ]);
        if ($this->campaign_id) {
            $this->campaign?->refreshStatusFromScheduledPosts();
        }
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'retry_count' => $this->retry_count + 1,
        ]);
        if ($this->campaign_id) {
            $this->campaign?->refreshStatusFromScheduledPosts();
        }
    }

    public function getStatusLabel(): string
    {
        $labels = [
            'pending' => 'Scheduled',
            'posted' => 'Posted',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusClass(): string
    {
        $classes = [
            'pending' => 'warning',
            'posted' => 'success',
            'failed' => 'danger',
            'cancelled' => 'secondary',
        ];

        return $classes[$this->status] ?? 'info';
    }
}
