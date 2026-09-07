<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\SocialMedia\ScheduledPostService;
use App\Models\ScheduledPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublishPostController extends Controller
{
    protected ScheduledPostService $scheduledPostService;

    public function __construct(ScheduledPostService $scheduledPostService)
    {
        $this->scheduledPostService = $scheduledPostService;
    }

    public function index()
    {
        $pendingPosts = ScheduledPost::pending()->count();
        $postedToday  = ScheduledPost::posted()->whereDate('posted_at', today())->count();
        $failedPosts  = ScheduledPost::failed()->count();

        return view('auto_posts.admin.publish_posts.index', [
            'pageTitle'          => 'Publish Posts',
            'activePosts'        => 'active',
            'activePublishPosts' => 'active',
            'showPostsMenu'      => true,
            'pendingPosts'       => $pendingPosts,
            'postedToday'        => $postedToday,
            'failedPosts'        => $failedPosts,
        ]);
    }

    public function publish(Request $request)
    {
        try {
            $results = $this->scheduledPostService->processAllPendingPosts();

            $message = sprintf(
                'Scheduled posts processed: %d total, %d successful, %d failed',
                $results['processed'],
                $results['successful'],
                $results['failed']
            );

            Log::info('Manual scheduled posts publish executed', $results);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $results
                ]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Manual scheduled posts publish failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to publish scheduled posts: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Failed to publish scheduled posts: ' . $e->getMessage()]);
        }
    }
}