<?php

namespace App\Console\Commands;

use App\Models\PostHistory;
use App\Models\SocialMediaAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchPostEngagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:fetch-engagement {--days=7 : Number of days to fetch engagement for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch engagement metrics (likes, comments, shares) for all published posts';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        
        $this->info("Fetching engagement for posts from last {$days} days...");
        
        // Get all successful posts within the date range
        $posts = PostHistory::where('success', true)
            ->where('posted_at', '>=', now()->subDays($days))
            ->whereNotNull('platform_post_id')
            ->get();
        
        if ($posts->isEmpty()) {
            $this->info('No posts found to fetch engagement for.');
            return Command::SUCCESS;
        }
        
        $this->info("Found {$posts->count()} posts to process.");
        
        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($posts as $post) {
            try {
                $account = SocialMediaAccount::find($post->social_media_account_id);
                
                Log::info('Processing post for engagement', [
                    'post_history_id' => $post->id,
                    'account_id' => $post->social_media_account_id,
                    'account_found' => $account !== null,
                    'account_access_token_exists' => $account ? !empty($account->access_token) : false,
                    'platform_post_id' => $post->platform_post_id
                ]);
                
                if (!$account || !$account->isActive()) {
                    $bar->advance();
                    continue;
                }
                
                // Get the service for this platform
                $serviceClass = $this->getServiceClass($account->platform);
                
                if (!$serviceClass || !class_exists($serviceClass)) {
                    $bar->advance();
                    continue;
                }
                
                $service = new $serviceClass();
                
                if (!method_exists($service, 'updatePostEngagement')) {
                    $bar->advance();
                    continue;
                }
                
                // Fetch and update engagement
                $result = $service->updatePostEngagement($account, $post->platform_post_id);
                
                if ($result['success']) {
                    $successCount++;
                    Log::info('Engagement fetched successfully', [
                        'post_history_id' => $post->id,
                        'platform' => $account->platform,
                        'platform_post_id' => $post->platform_post_id,
                        'metrics' => $result['metrics'] ?? []
                    ]);
                } else {
                    $errorCount++;
                    Log::warning('Failed to fetch engagement', [
                        'post_history_id' => $post->id,
                        'error' => $result['error'] ?? 'Unknown error'
                    ]);
                }
                
            } catch (\Exception $e) {
                $errorCount++;
                Log::error('Error fetching engagement for post', [
                    'post_history_id' => $post->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("Completed! Success: {$successCount}, Errors: {$errorCount}");
        
        return Command::SUCCESS;
    }
    
    /**
     * Get the service class for a platform
     */
    protected function getServiceClass(string $platform): ?string
    {
        $services = [
            'facebook' => \App\Http\Services\SocialMedia\FacebookService::class,
            'instagram' => \App\Http\Services\SocialMedia\InstagramService::class,
            'twitter' => \App\Http\Services\SocialMedia\TwitterService::class,
            'linkedin' => \App\Http\Services\SocialMedia\LinkedinService::class,
            'tiktok' => \App\Http\Services\SocialMedia\TikTokService::class,
            'youtube' => \App\Http\Services\SocialMedia\YouTubeService::class,
            'threads' => \App\Http\Services\SocialMedia\ThreadsService::class,
        ];
        
        return $services[strtolower($platform)] ?? null;
    }
}