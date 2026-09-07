<?php

namespace App\Console\Commands;

use App\Http\Services\SocialMedia\ScheduledPostService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduled-posts:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled posts that are due';

    protected ScheduledPostService $scheduledPostService;

    public function __construct(ScheduledPostService $scheduledPostService)
    {
        parent::__construct();
        $this->scheduledPostService = $scheduledPostService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing due scheduled posts...');

        $results = $this->scheduledPostService->processDuePosts();

        $this->info("Processed: {$results['processed']} posts");
        $this->info("Successful: {$results['successful']} posts");
        $this->info("Failed: {$results['failed']} posts");

        if (!empty($results['errors'])) {
            $this->error('Errors encountered:');
            foreach ($results['errors'] as $error) {
                $this->error("- {$error}");
            }
        }

        Log::info('Scheduled posts processed', $results);

        return Command::SUCCESS;
    }
}
