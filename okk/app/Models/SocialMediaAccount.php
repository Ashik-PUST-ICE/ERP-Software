<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class SocialMediaAccount extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'user_id',
        'platform',
        'account_id',
        'access_token',
        'redirect_uri',
        'page_id',
        'group_id',
        'username',
        'email',
        'description',
        'is_active',
        'permissions',
        'token_expires_at',
        'settings',
        'tenant_id',
    ];

    protected $casts = [
        'permissions' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'token_expires_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scheduledPosts(): HasMany
    {
        return $this->hasMany(ScheduledPost::class);
    }

    public function postHistories(): HasMany
    {
        return $this->hasMany(PostHistory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->is_active && (!$this->token_expires_at || $this->token_expires_at->isFuture());
    }

    public function getInactiveReason(): string
    {
        if (!$this->is_active) {
            return 'Account is inactive';
        }

        if ($this->token_expires_at && $this->token_expires_at->isPast()) {
            return 'Access token has expired. Please reconnect your account.';
        }

        return 'Account is not active';
    }

    public function getPlatformDisplayName(): string
    {
        return ucfirst($this->platform);
    }

    public function isPageConnected(): bool
    {
        return !empty($this->page_id);
    }

    public function isGroupConnected(): bool
    {
        return !empty($this->group_id);
    }

        public function isTokenExpired(): bool
    {
        return $this->token_expires_at && $this->token_expires_at->isPast();
    }

    /**
     * Get engagement statistics from post histories
     */
    public function getEngagementStats(): array
    {
        $stats = $this->postHistories()
            ->where('success', true)
            ->selectRaw('
                SUM(COALESCE(likes_count, 0)) as total_likes,
                SUM(COALESCE(comments_count, 0)) as total_comments,
                SUM(COALESCE(shares_count, 0)) as total_shares,
                SUM(COALESCE(views_count, 0)) as total_views,
                COUNT(*) as total_posts
            ')
            ->first();

        $result = [
            'likes' => (int) ($stats->total_likes ?? 0),
            'comments' => (int) ($stats->total_comments ?? 0),
            'shares' => (int) ($stats->total_shares ?? 0),
            'views' => (int) ($stats->total_views ?? 0),
            'posts' => (int) ($stats->total_posts ?? 0)
        ];

        Log::info('getEngagementStats calculated for account', [
            'account_id' => $this->id,
            'platform' => $this->platform,
            'stats' => $result
        ]);

        return $result;
    }
}