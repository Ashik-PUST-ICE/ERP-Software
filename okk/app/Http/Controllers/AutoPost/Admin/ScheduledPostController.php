<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AutoPost\Admin\ScheduledPostRequest;
use App\Http\Services\SocialMedia\ScheduledPostService;
use App\Http\Services\SubscriptionService;
use App\Models\ScheduledPost;
use App\Models\SocialMediaAccount;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Video;
use App\Models\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ScheduledPostController extends Controller
{
    protected ScheduledPostService $scheduledPostService;

    protected SubscriptionService $subscriptionService;

    public function __construct(ScheduledPostService $scheduledPostService, SubscriptionService $subscriptionService)
    {
        $this->scheduledPostService = $scheduledPostService;
        $this->subscriptionService = $subscriptionService;
    }

    public function index()
    {
        $tenantId = auth()->user()->tenant_id ?? null;

        return view('auto_posts.admin.scheduled_posts.index', [
            'users' => User::select('id', 'name', 'email')
                ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                ->get(),
            'accounts' => SocialMediaAccount::select('id', 'platform', 'username')->get(),
            'platforms' => SocialMediaAccount::distinct('platform')->pluck('platform')->toArray(),
            'activePosts' => 'active',
            'showPostsMenu' => 'active',
            'activeScheduledPosts' => 'active'
        ]);
    }

    public function datatable(Request $request)
    {
        return $this->scheduledPostService->getAllData($request);
    }

    public function create()
    {
        $tenantId = auth()->user()->tenant_id ?? null;

        $allowedProviders = getUserAllowedProviders();
        $postLimitInfo = getPostLimitCheckResult();

        return view('auto_posts.admin.scheduled_posts.create', [
            'galleries'        => Gallery::latest()->get(),
            'videos'           => Video::latest()->get(),
            'users'            => User::select('id', 'name', 'email')
                ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                ->get(),
            'accounts'         => SocialMediaAccount::where('is_active', true)->get(),
            'platforms'        => SocialMediaAccount::where('is_active', true)->distinct()->pluck('platform'),
            'activePosts'      => 'active',
            'showPostsMenu'    => 'active',
            'activeCreatePost' => 'active',
            'templateListUrl'  => route('admin.template.list'),
            'hashtagsIndexUrl' => route('admin.hashtag.index'),
            'platformKeyToName' => collect(SOCIAL_MEDIA_PLATFORMS)
                ->map(fn($name) => strtolower($name))
                ->toArray(),
            'ai_enabled'       => $this->isAiEnabledForUser(),
            'ai_upgrade_message' => __('Your current plan does not include AI features. Please upgrade your package.'),
            'allowedProviders'  => $allowedProviders,
            'postLimitInfo'     => $postLimitInfo,
        ]);
    }

    private function isAiEnabledForUser(): bool
    {
        $currentPackage = $this->subscriptionService->getCurrentPlan(auth()->id());

        return $currentPackage
            && $currentPackage->packageable
            && $currentPackage->packageable->ai_enabled;
    }

    public function store(ScheduledPostRequest $request)
    {
        $result = $this->scheduledPostService->store($request);

        if ($result['success']) {
            return redirect()->route('admin.all-posts.index')->with('success', $result['message']);
        }

        return redirect()->back()->withErrors(['error' => $result['error']]);
    }

    public function show(ScheduledPost $post)
    {
        $post->load(['socialMediaAccount', 'user']);

        return view('auto_posts.admin.scheduled_posts.show', [
            'post' => $post
        ]);
    }

    public function edit(ScheduledPost $post)
    {
        $post->load(['socialMediaAccount', 'user']);
        $tenantId = auth()->user()->tenant_id ?? null;

        $allowedProviders = getUserAllowedProviders();
        $postLimitInfo = getPostLimitCheckResult();

        return view('auto_posts.admin.scheduled_posts.edit', [
            'post'             => $post,
            'galleries'        => Gallery::latest()->get(),
            'videos'           => Video::latest()->get(),
            'users'            => User::select('id', 'name', 'email')
                ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                ->get(),
            'accounts'         => SocialMediaAccount::where('is_active', true)->get(),
            'activePosts'      => 'active',
            'showPostsMenu'    => 'active',
            'activeCreatePost' => 'active',
            'templateListUrl'  => route('admin.template.list'),
            'hashtagsIndexUrl' => route('admin.hashtag.index'),
            'platformKeyToName' => collect(SOCIAL_MEDIA_PLATFORMS)
                ->map(fn($name) => strtolower($name))
                ->toArray(),
            'postConfig' => [
                'postType'        => [
                    'feed'  => 'Feed',
                    'reel'  => 'Reels',
                    'video' => 'Reels',
                    'story' => 'Story',
                ][$post->post_type ?? 'feed'] ?? 'Feed',
                'platform'        => strtolower($post->socialMediaAccount->platform ?? ''),
                'scheduledTime'   => $post->scheduled_time ? formatDateToCustomer($post->scheduled_time, 'Y-m-d H:i', 'UTC', getAppTimeZone()) : '',
                'galleryImageIds' => $post->post_metadata['gallery_image_ids'] ?? '',
                'galleryVideoIds' => $post->post_metadata['gallery_video_ids'] ?? '',
            ],
            'ai_enabled'       => $this->isAiEnabledForUser(),
            'ai_upgrade_message' => __('Your current plan does not include AI features. Please upgrade your package.'),
            'allowedProviders'  => $allowedProviders,
            'postLimitInfo'     => $postLimitInfo,
        ]);
    }

    public function update(ScheduledPostRequest $request, ScheduledPost $post)
    {
        $result = $this->scheduledPostService->update($request, $post);

        if ($result['success']) {
            return redirect()->route('admin.all-posts.index')->with('success', $result['message']);
        }

        return redirect()->back()->withErrors(['error' => $result['error']]);
    }

    public function destroy(ScheduledPost $post)
    {
        try {
            $post->postHistories()->delete();
            
            if ($post->media_url) {
                $file = FileManager::where('path', $post->media_url)->first();
                if ($file) {
                    $file->removeFile();
                    $file->delete();
                } else {
                    Storage::delete($post->media_url);
                }
            }

            $post->delete();

            if (request()->expectsJson()) {
                return response()->json(['status' => true, 'message' => 'Scheduled post deleted successfully']);
            }

            return redirect()->route('admin.all-posts.index')
                ->with('success', 'Scheduled post deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete scheduled post', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json(['status' => false, 'message' => 'Failed to delete scheduled post: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Failed to delete scheduled post: ' . $e->getMessage()]);
        }
    }

    public function cancel(ScheduledPost $post)
    {
        try {
            if ($this->scheduledPostService->cancelScheduledPost($post)) {
                if (request()->expectsJson()) {
                    return response()->json(['status' => true, 'message' => 'Scheduled post cancelled successfully']);
                }

                return redirect()->route('admin.all-posts.index')
                    ->with('success', 'Scheduled post cancelled successfully');
            } else {
                if (request()->expectsJson()) {
                    return response()->json(['status' => false, 'message' => 'Cannot cancel this post'], 400);
                }

                return redirect()->back()->withErrors(['error' => 'Cannot cancel this post']);
            }
        } catch (\Exception $e) {
            Log::error('Failed to cancel scheduled post', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json(['status' => false, 'message' => 'Failed to cancel scheduled post'], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Failed to cancel scheduled post']);
        }
    }

    public function statistics()
    {
        $stats = [
            'total_scheduled' => ScheduledPost::count(),
            'pending' => ScheduledPost::where('status', 'pending')->count(),
            'posted' => ScheduledPost::where('status', 'posted')->count(),
            'failed' => ScheduledPost::where('status', 'failed')->count(),
            'cancelled' => ScheduledPost::where('status', 'cancelled')->count(),
            'due_now' => ScheduledPost::due()->count()
        ];

        return response()->json($stats);
    }

    public function calendar()
    {
        $allowedProviders = getUserAllowedProviders();

        return view('auto_posts.admin.scheduled_posts.calendar', [
            'accounts'       => SocialMediaAccount::where('is_active', true)->get(),
            'activeCalendar' => 'active',
            'allowedProviders' => $allowedProviders,
            'calendarConfig' => [
                'appTimeZone'        => getAppTimeZone(),
                'eventsUrl'          => route('admin.calendar.events'),
                'updateDateUrl'      => url('/') . '/autopost/admin/calendar/posts/:id/update-date',
                'publishNowUrl'      => url('/') . '/autopost/admin/calendar/posts/:id/publish-now',
                'editPostUrl'        => route('admin.all-posts.index') . '/:id/edit',
                'editCampaignUrl'    => route('admin.campaign.edit', ':id'),
                'publishCampaignUrl' => route('admin.campaign.publish', ['id' => ':id']),
                'deletePostUrl'      => route('admin.all-posts.index') . '/:id',
                'deleteCampaignUrl'  => route('admin.campaign.destroy', ':id'),
                'csrfToken'          => csrf_token(),
                'defaultAvatar'      => asset('assets/images/profile-image-1.png'),
                'baseUrl'            => url('/'),
            ],
        ]);
    }

    public function calendarEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $platforms = $request->input('platforms', []);
        $accountIds = $request->input('account_ids', []);
        
        $query = ScheduledPost::with(['socialMediaAccount'])
            ->whereBetween('scheduled_time', [$start, $end]);

        if (!empty($platforms)) {
            $platformNames = [];
            foreach ($platforms as $platformLower) {
                foreach (SOCIAL_MEDIA_PLATFORMS as $platformName) {
                    if (strtolower($platformName) === strtolower($platformLower)) {
                        $platformNames[] = $platformName;
                        break;
                    }
                }
            }
            
            if (!empty($platformNames)) {
                $query->whereHas('socialMediaAccount', function($q) use ($platformNames) {
                    $q->whereIn('platform', $platformNames);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if (!empty($accountIds)) {
            $query->whereIn('social_media_account_id', $accountIds);
        }

        $posts = $query->get();

        $events = $posts->map(function ($post) {
            $account = $post->socialMediaAccount;
            if (!$account) {
                return null;
            }
            
            $platformIcon = $this->getPlatformIcon($account->platform ?? '');
            $content = $post->content ? strip_tags($post->content) : 'No content';
            $title = strlen($content) > 50 ? substr($content, 0, 50) . '...' : $content;
            
            $accountAvatar = asset('assets/images/profile-image-1.png');
            if ($account->avatar) {
                if (str_starts_with($account->avatar, 'http')) {
                    $accountAvatar = $account->avatar;
                } else {
                    $accountAvatar = asset('storage/' . $account->avatar);
                }
            }
            
            $startIso = $post->scheduled_time->utc()->format('Y-m-d\TH:i:s\Z');
            $displayTime = formatDateToCustomer($post->scheduled_time, 'H:i', 'UTC', getAppTimeZone());
            return [
                'id'           => 'post_' . $post->id,
                'title'        => $title,
                'start'        => $startIso,
                'editable'     => $post->status === 'pending',
                'allDay'       => false,
                'time'         => $displayTime,
                'status'       => $post->status,
                'platform'     => $account->platform ?? '',
                'account_name' => $account->username ?? $account->account_id ?? '',
                'account_avatar' => $accountAvatar,
                'platform_icon'  => $platformIcon,
                'post_type'    => $post->post_type,
                'media_url'    => $post->media_url ? asset('storage/' . $post->media_url) : null,
                'type'         => 'scheduled_post',
                'scheduled_at_iso' => $startIso,
            ];
        })->filter();

        $campaignQuery = \App\Models\Campaign::whereNotNull('scheduled_time')
            ->whereBetween('scheduled_time', [$start, $end]);

        if (!empty($platforms)) {
            $platformNames = [];
            foreach ($platforms as $platformLower) {
                foreach (SOCIAL_MEDIA_PLATFORMS as $platformName) {
                    if (strtolower($platformName) === strtolower($platformLower)) {
                        $platformNames[] = $platformName;
                        break;
                    }
                }
            }
            if (!empty($platformNames)) {
                $campaignQuery->whereIn('platform', $platformNames);
            } else {
                $campaignQuery->whereRaw('1 = 0');
            }
        }

        if (!empty($accountIds)) {
            $campaignQuery->where(function($q) use ($accountIds) {
                foreach ($accountIds as $accountId) {
                    $q->orWhereRaw("FIND_IN_SET(?, account_ids)", [$accountId]);
                }
            });
        }

        $campaigns = $campaignQuery->get();

        $campaignEvents = $campaigns->map(function ($campaign) {
            $platformIcon = $this->getPlatformIcon($campaign->platform ?? '');
            $content = $campaign->content ? strip_tags($campaign->content) : $campaign->name;
            $title = strlen($content) > 50 ? substr($content, 0, 50) . '...' : $content;
            
            $accountAvatar = asset('assets/images/profile-image-1.png');
            if ($campaign->account_ids) {
                $accountIds = explode(',', $campaign->account_ids);
                if (!empty($accountIds[0])) {
                    $account = \App\Models\SocialMediaAccount::find($accountIds[0]);
                    if ($account && $account->avatar) {
                        if (str_starts_with($account->avatar, 'http')) {
                            $accountAvatar = $account->avatar;
                        } else {
                            $accountAvatar = asset('storage/' . $account->avatar);
                        }
                    }
                }
            }
            
            $startIso = $campaign->scheduled_time->utc()->format('Y-m-d\TH:i:s\Z');
            $displayTime = formatDateToCustomer($campaign->scheduled_time, 'H:i', 'UTC', getAppTimeZone());
            return [
                'id' => 'campaign_' . $campaign->id,
                'title' => $title,
                'start' => $startIso,
                'allDay' => false,
                'time' => $displayTime,
                'status' => $campaign->status,
                'platform' => $campaign->platform ?? '',
                'campaign_name' => $campaign->name,
                'account_name' => 'Campaign',
                'account_avatar' => $accountAvatar,
                'platform_icon' => $platformIcon,
                'post_type' => $campaign->post_type ?? 'Feed',
                'media_url' => null,
                'type' => 'campaign',
                'scheduled_at_iso' => $startIso,
            ];
        });

        $eventsCollection = collect($events->values()->toArray());
        $campaignEventsCollection = collect($campaignEvents->values()->toArray());
        $allEvents = $eventsCollection->merge($campaignEventsCollection);

        return response()->json($allEvents->values());
    }

    public function updateDate(Request $request, ScheduledPost $post)
    {
        try {
            $scheduledTimeInput = $request->input('scheduled_time');
            
            Log::info('Update date request', [
                'post_id' => $post->id,
                'scheduled_time' => $scheduledTimeInput,
                'raw_input' => $request->all()
            ]);

            if (empty($scheduledTimeInput)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Scheduled time is required'
                ], 422);
            }

            $appTz = getAppTimeZone();
            $scheduledTime = null;
            try {
                $parsed = \Carbon\Carbon::parse($scheduledTimeInput, $appTz)->utc();
                $scheduledTime = $parsed;
            } catch (\Exception $e) {
            }
            if (!$scheduledTime) {
                $scheduledTime = \DateTime::createFromFormat('Y-m-d H:i:s', $scheduledTimeInput);
                if ($scheduledTime) {
                    $scheduledTime = \Carbon\Carbon::parse($scheduledTime->format('Y-m-d H:i:s'), $appTz)->utc();
                }
            }
            if (!$scheduledTime) {
                try {
                    $scheduledTime = \Carbon\Carbon::parse($scheduledTimeInput, $appTz)->utc();
                } catch (\Exception $e) {
                }
            }
            if (!$scheduledTime) {
                $scheduledTime = \DateTime::createFromFormat('Y-m-d H:i', $scheduledTimeInput);
                if ($scheduledTime) {
                    $scheduledTime = \Carbon\Carbon::parse($scheduledTime->format('Y-m-d H:i'), $appTz)->utc();
                }
            }
            if (!$scheduledTime) {
                $dateOnly = \DateTime::createFromFormat('Y-m-d', $scheduledTimeInput);
                if ($dateOnly) {
                    $existingTime = $post->scheduled_time;
                    if ($existingTime) {
                        $scheduledTime = \Carbon\Carbon::parse($dateOnly->format('Y-m-d') . ' ' . $existingTime->format('H:i:s'), $appTz)->utc();
                    } else {
                        $scheduledTime = \Carbon\Carbon::parse($dateOnly->format('Y-m-d') . ' 00:00:00', $appTz)->utc();
                    }
                }
            }
            if (!$scheduledTime) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid date format. Received: ' . $scheduledTimeInput . '. Supported formats: Y-m-d H:i:s, ISO 8601, Y-m-d H:i, or Y-m-d'
                ], 400);
            }

            $post->update([
                'scheduled_time' => $scheduledTime
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Scheduled date updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating scheduled post date', [
                'post_id' => $post->id,
                'errors' => $e->errors(),
                'scheduled_time' => $request->scheduled_time
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to update scheduled post date', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
                'scheduled_time' => $request->scheduled_time,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to update scheduled date: ' . $e->getMessage()
            ], 400);
        }
    }

    public function publishNow(ScheduledPost $post)
    {
        $result = $this->scheduledPostService->publishScheduledPostNow($post);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    private function getPlatformIcon($platform)
    {
        $icons = [
            'Facebook' => 'fa-brands fa-facebook-f',
            'LinkedIn' => 'fa-brands fa-linkedin-in',
            'YouTube' => 'fa-brands fa-youtube',
            'TikTok' => 'fa-brands fa-tiktok',
            'Twitter' => 'fa-brands fa-x-twitter',
            'Instagram' => 'fa-brands fa-instagram',
            'Threads' => 'fa-brands fa-threads',
        ];

        return $icons[$platform] ?? 'fa-solid fa-globe';
    }
}