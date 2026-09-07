<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class PostHistory extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'user_id',
        'social_media_account_id',
        'scheduled_post_id',
        'content',
        'media_url',
        'platform',
        'platform_post_id',
        'post_type',
        'posted_at',
        'response_data',
        'success',
        'error_message',
        'post_metadata',
        'likes_count',
        'comments_count',
        'shares_count',
        'reach_count',
        'views_count',
        'engagement_fetched_at',
        'tenant_id',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
        'response_data' => 'array',
        'post_metadata' => 'array',
        'success' => 'boolean',
        'engagement_fetched_at' => 'datetime',
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

    public function scheduledPost(): BelongsTo
    {
        return $this->belongsTo(ScheduledPost::class);
    }

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    public function scopePlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('posted_at', '>=', now()->subDays($days));
    }

    // Helper methods
    /**
     * Update engagement metrics with logging
     */
    public function updateEngagement(array $metrics): bool
    {
        $oldLikes = $this->likes_count ?? 0;
        $oldComments = $this->comments_count ?? 0;
        $oldShares = $this->shares_count ?? 0;
        $oldViews = $this->views_count ?? 0;

        $this->likes_count = $metrics['likes'] ?? $oldLikes;
        $this->comments_count = $metrics['comments'] ?? $oldComments;
        $this->shares_count = $metrics['shares'] ?? $oldShares;
        $this->views_count = $metrics['views'] ?? $oldViews;
        $this->reach_count = $metrics['reach'] ?? ($this->reach_count ?? 0);
        $this->engagement_fetched_at = now();

        $saved = $this->save();

        if ($saved) {
            Log::info('Post engagement updated', [
                'post_history_id' => $this->id,
                'platform' => $this->platform,
                'platform_post_id' => $this->platform_post_id,
                'old_likes' => $oldLikes,
                'new_likes' => $this->likes_count,
                'old_comments' => $oldComments,
                'new_comments' => $this->comments_count,
                'old_shares' => $oldShares,
                'new_shares' => $this->shares_count,
                'old_views' => $oldViews,
                'new_views' => $this->views_count,
            ]);
        }

        return $saved;
    }

    public function getPostTypeLabel(): string
    {
        $labels = [
            'scheduled' => 'Scheduled Post',
            'manual' => 'Manual Post',
            'failed_retry' => 'Failed Retry',
        ];

        return $labels[$this->post_type] ?? ucfirst($this->post_type);
    }

    public function getPlatformDisplayName(): string
    {
        return ucfirst($this->platform);
    }

    public function getPostUrl(): ?string
    {
        if (!$this->platform_post_id) {
            return null;
        }

        switch ($this->platform) {
            case 'facebook':
                return "https://facebook.com/{$this->platform_post_id}";
            case 'instagram':
                return "https://instagram.com/p/{$this->platform_post_id}";
            case 'twitter':
                return "https://twitter.com/post/{$this->platform_post_id}";
            default:
                return null;
        }
    }

    public function getSuccessClass(): string
    {
        return $this->success ? 'success' : 'danger';
    }

    public function getSuccessLabel(): string
    {
        return $this->success ? 'Success' : 'Failed';
    }

    // Engagement Metrics Methods
    public function getTotalEngagement(): int
    {
        return ($this->likes_count ?? 0) + ($this->comments_count ?? 0) + ($this->shares_count ?? 0);
    }

    public function getEngagementRate(): float
    {
        if (($this->reach_count ?? 0) > 0) {
            return round(($this->getTotalEngagement() / $this->reach_count) * 100, 2);
        }
        return 0.0;
    }

    public function hasEngagementData(): bool
    {
        return $this->likes_count > 0 || $this->comments_count > 0 || $this->shares_count > 0;
    }

    public function isEngagementStale(): bool
    {
        if (!$this->engagement_fetched_at) {
            return true;
        }
        // Consider data stale after 1 hour
        return $this->engagement_fetched_at->diffInHours(now()) > 1;
    }

    public function updateEngagementMetrics(array $metrics): void
    {
        $this->likes_count = $metrics['likes'] ?? $this->likes_count;
        $this->comments_count = $metrics['comments'] ?? $this->comments_count;
        $this->shares_count = $metrics['shares'] ?? $this->shares_count;
        $this->reach_count = $metrics['reach'] ?? $this->reach_count;
        $this->views_count = $metrics['views'] ?? $this->views_count;
        $this->engagement_fetched_at = now();
        $this->save();
    }
}