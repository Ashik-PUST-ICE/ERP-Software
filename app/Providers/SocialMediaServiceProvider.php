<?php

namespace App\Providers;

use App\Http\Services\SocialMedia\FacebookService;
use App\Http\Services\SocialMedia\InstagramService;
use App\Http\Services\SocialMedia\SocialMediaServiceManager;
use App\Http\Services\SocialMedia\ThreadsService;
use App\Http\Services\SocialMedia\TwitterService;
use App\Http\Services\SocialMedia\LinkedinService;
use App\Http\Services\SocialMedia\YouTubeService;
use App\Http\Services\SocialMedia\TikTokService;
use Illuminate\Support\ServiceProvider;

class SocialMediaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the SocialMediaServiceManager as a singleton
        $this->app->singleton(SocialMediaServiceManager::class, function ($app) {
            $serviceManager = new SocialMediaServiceManager();

            // Register Facebook service
            $serviceManager->registerService('facebook', new FacebookService());

            // Register Twitter service
            $serviceManager->registerService('twitter', new TwitterService());

            // Register Instagram service
            $serviceManager->registerService('instagram', new InstagramService());

            // Register Threads service
            $serviceManager->registerService('threads', new ThreadsService());

            // Register LinkedIn service
            $serviceManager->registerService('linkedin', new LinkedinService());

            // Register YouTube service
            $serviceManager->registerService('youtube', new YouTubeService());

            // Register TikTok service
            $serviceManager->registerService('tiktok', new TikTokService());

            return $serviceManager;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
