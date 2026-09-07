<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'scheduled_time',
        'description',
        'platform',
        'account_ids',
        'content',
        'post_type',
        'gallery_image_ids',
        'gallery_video_ids',
        'status',
        'created_by',
        'tenant_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'scheduled_time' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scheduledPosts(): HasMany
    {
        return $this->hasMany(ScheduledPost::class);
    }

    /**
     * Refresh campaign status from its scheduled posts: pending, posted, or failed.
     */
    public function refreshStatusFromScheduledPosts(): void
    {
        $posts = $this->scheduledPosts()->get();
        if ($posts->isEmpty()) {
            return;
        }
        $hasPending = $posts->contains('status', 'pending');
        $hasFailed = $posts->contains('status', 'failed');
        $allPosted = $posts->every('status', 'posted');
        if ($hasFailed) {
            $this->update(['status' => 'failed']);
        } elseif ($allPosted) {
            $this->update(['status' => 'posted']);
        } elseif ($hasPending) {
            $this->update(['status' => 'pending']);
        }
    }

    /**
     * Get count of pending scheduled posts for this campaign.
     */
    public function getPendingScheduledPostsCountAttribute(): int
    {
        return $this->scheduledPosts()->pending()->count();
    }

    /**
     * Get progress percentage based on dates
     */
    public function getProgressAttribute()
    {
        $now = now();
        $start = $this->start_date;
        $end = $this->end_date;

        if ($now < $start) {
            return 0;
        }

        if ($now > $end) {
            return 100;
        }

        $totalDays = $start->diffInDays($end);
        $elapsedDays = $start->diffInDays($now);

        return $totalDays > 0 ? round(($elapsedDays / $totalDays) * 100) : 0;
    }
}
