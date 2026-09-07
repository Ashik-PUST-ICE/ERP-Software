<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaConfig extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'social_media_configs';

    protected $fillable = [
        'user_id',
        'platform',
        'app_id',
        'app_secret',
        'bearer_token',
        'redirect_uri',
        'permissions',
        'is_active',
        'settings',
        'tenant_id',
    ];

    protected $casts = [
        'permissions' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the platform display name
     */
    public function getPlatformNameAttribute()
    {
        return ucfirst($this->platform);
    }

    /**
     * Get the default permissions for each platform
     */
    public static function getDefaultPermissions($platform)
    {
        // Centralized default scopes used across the app (seeders, OAuth, forms)
        $permissions = [
            'facebook' => [
                // Basic profile + email
                'public_profile',
                'email',
                // Page & engagement permissions
                'pages_show_list',
                'pages_manage_posts',
                'pages_read_engagement',
                'pages_manage_engagement',
            ],
            'instagram' => [
    'public_profile',
    'pages_show_list',
    'pages_read_engagement',
    'pages_manage_posts',
    'instagram_basic',
    'instagram_content_publish',
    'business_management',
],
            'twitter' => [
                'tweet.read',
                'tweet.write',
                'users.read',
                'follows.read',
                'follows.write',
            ],
            'linkedin' => [
                'openid',
                'profile',
                'w_member_social',
                'email',
            ],
            'youtube' => [
                'https://www.googleapis.com/auth/youtube.upload',
                'https://www.googleapis.com/auth/youtube.readonly',
                'https://www.googleapis.com/auth/youtube.force-ssl',
                'openid',
                'profile',
                'email',
            ],
            'tiktok' => [
                'user.info.basic',
                'video.upload',
                'video.publish',
            ],
            'threads' => [
                'threads_basic',
                'threads_content_publish',
            ],
        ];

        return $permissions[$platform] ?? [];
    }

    /**
     * Get the configuration for a specific platform (legacy helper)
     */
    public static function getPlatformConfig($platform)
    {
        return self::where('platform', $platform)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Check if a platform is configured and active (legacy helper)
     */
    public static function isPlatformActive($platform)
    {
        return self::where('platform', $platform)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get the social media accounts for this configuration
     */
    public function socialMediaAccounts()
    {
        return $this->hasMany(SocialMediaAccount::class, 'platform', 'platform')
            ->when($this->user_id ?? null, function ($q) {
                $q->where('user_id', $this->user_id);
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}