<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\CampaignService;
use App\Http\Services\SocialMedia\ScheduledPostService;
use App\Http\Services\SubscriptionService;
use App\Models\Campaign;
use App\Models\Gallery;
use App\Models\SocialMediaAccount;
use App\Models\Video;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    protected CampaignService $campaignService;

    protected SubscriptionService $subscriptionService;

    public function __construct()
    {
        $this->campaignService = new CampaignService(app(\App\Http\Services\SocialMedia\ScheduledPostService::class));
        $this->subscriptionService = new SubscriptionService();
    }

    public function index()
    {
        return view('auto_posts.admin.campaign.index', [
            'activeCampaigns' => 'active',
            'showCampaignsMenu' => 'show',
            'activeCampaignList' => 'active'
        ]);
    }

    public function datatable(Request $request)
    {
        return $this->campaignService->getAllData($request);
    }

    public function create()
    {
        $accounts = SocialMediaAccount::where('is_active', true)->get();
        $galleries = Gallery::latest()->get();
        $videos = Video::latest()->get();
        
        $allowedProviders = getUserAllowedProviders();
        $postLimitInfo = getPostLimitCheckResult();

        return view('auto_posts.admin.campaign.create', [
            'accounts' => $accounts,
            'galleries' => $galleries,
            'videos' => $videos,
            'activeCampaigns' => 'active',
            'showCampaignsMenu' => 'show',
            'activeCampaignCreate' => 'active',
            'ai_enabled' => $this->isAiEnabledForUser(),
            'ai_upgrade_message' => __('Your current plan does not include AI features. Please upgrade your package.'),
            'allowedProviders' => $allowedProviders,
            'postLimitInfo' => $postLimitInfo,
        ]);
    }

    private function isAiEnabledForUser(): bool
    {
        $currentPackage = $this->subscriptionService->getCurrentPlan(auth()->id());

        return $currentPackage
            && $currentPackage->packageable
            && $currentPackage->packageable->ai_enabled;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'platform' => 'nullable|string',
            'account_ids' => 'nullable',
            'content' => 'nullable|string',
            'post_type' => 'nullable|string',
            'status' => 'nullable|in:pending,posted,failed',
            'scheduled_time' => 'nullable|date',
        ]);

        $response = $this->campaignService->store($request);
        $data = $response->getData(true);

        if (!empty($data['status'])) {
            return redirect()->to($data['data']['redirect_url'] ?? route('admin.campaign.index'))->with('success', $data['message']);
        }
        return redirect()->back()->withInput()->with('error', $data['message'] ?? __('Something went wrong'));
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        if (($campaign->status ?? '') === 'posted') {
            return redirect()->route('admin.campaign.index')
                ->with('error', __('Posted campaigns cannot be edited.'));
        }
        $accounts = SocialMediaAccount::where('is_active', true)->get();
        $galleries = Gallery::latest()->get();
        $videos = Video::latest()->get();
        
        // Prepare existing media for JavaScript initialization
        $existingMedia = [
            'images' => [],
            'videos' => []
        ];
        
        if ($campaign->gallery_image_ids) {
            $imageIds = array_filter(explode(',', $campaign->gallery_image_ids));
            foreach ($imageIds as $imageId) {
                $gallery = Gallery::find($imageId);
                if ($gallery) {
                    $existingMedia['images'][] = [
                        'id' => $gallery->id,
                        'url' => asset('storage/' . $gallery->file_path)
                    ];
                }
            }
        }
        
        if ($campaign->gallery_video_ids) {
            $videoIds = array_filter(explode(',', $campaign->gallery_video_ids));
            foreach ($videoIds as $videoId) {
                $video = Video::find($videoId);
                if ($video) {
                    $existingMedia['videos'][] = [
                        'id' => $video->id,
                        'url' => asset('storage/' . $video->file_path)
                    ];
                }
            }
        }
        
        $allowedProviders = getUserAllowedProviders();
        $postLimitInfo = getPostLimitCheckResult();

        return view('auto_posts.admin.campaign.edit', [
            'campaign' => $campaign,
            'accounts' => $accounts,
            'galleries' => $galleries,
            'videos' => $videos,
            'existingMedia' => $existingMedia,
            'activeCampaigns' => 'active',
            'showCampaignsMenu' => 'show',
            'activeCampaignList' => 'active',
            'ai_enabled' => $this->isAiEnabledForUser(),
            'ai_upgrade_message' => __('Your current plan does not include AI features. Please upgrade your package.'),
            'allowedProviders' => $allowedProviders,
            'postLimitInfo' => $postLimitInfo,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'platform' => 'nullable|string',
            'account_ids' => 'nullable',
            'content' => 'nullable|string',
            'post_type' => 'nullable|string',
            'status' => 'nullable|in:pending,posted,failed',
            'scheduled_time' => 'nullable|date',
            'publish_now' => 'nullable|in:0,1',
        ]);

        $campaign = Campaign::findOrFail($id);
        if (($campaign->status ?? '') === 'posted') {
            return redirect()->route('admin.campaign.index')
                ->with('error', __('Posted campaigns cannot be edited.'));
        }

        $response = $this->campaignService->update($request, $id);
        $data = $response->getData(true);

        if (!empty($data['status'])) {
            return redirect()->to($data['data']['redirect_url'] ?? route('admin.campaign.index'))->with('success', $data['message']);
        }
        return redirect()->back()->withInput()->with('error', $data['message'] ?? __('Something went wrong'));
    }

    public function destroy($id)
    {
        $response = $this->campaignService->deleteById($id);
        $data = $response->getData(true);

        if (!empty($data['status'])) {
            return redirect()->route('admin.campaign.index')->with('success', $data['message']);
        }
        return redirect()->route('admin.campaign.index')->with('error', $data['message'] ?? __('Something went wrong'));
    }

    /**
     * Publish all pending scheduled posts for this campaign (from campaign list).
     * Cron also publishes due scheduled posts via PublishPostController / processAllPendingPosts.
     */
    public function publish($id)
    {
        $campaign = Campaign::findOrFail($id);
        $results = $this->campaignService->publishCampaignPostsNow($campaign);

        $successCount = $results['successful'] ?? 0;
        $failedCount = $results['failed'] ?? 0;
        $errors = $results['errors'] ?? [];

        if ($successCount > 0 && $failedCount === 0) {
            $message = __('Posts published successfully to :count account(s)', ['count' => $successCount]);
            if (request()->expectsJson()) {
                return response()->json(['status' => true, 'message' => $message]);
            }
            return redirect()->route('admin.campaign.index')->with('success', $message);
        }
        if ($successCount > 0 && $failedCount > 0) {
            $message = __('Published to :count account(s). :failed failed.', ['count' => $successCount, 'failed' => $failedCount]) . ' ' . implode(', ', array_slice($errors, 0, 3));
            if (request()->expectsJson()) {
                return response()->json(['status' => true, 'message' => $message]);
            }
            return redirect()->route('admin.campaign.index')->with('success', $message);
        }
        $message = $failedCount > 0 ? __('Failed to publish: ') . implode(', ', array_slice($errors, 0, 3)) : __('No pending posts to publish for this campaign.');
        if (request()->expectsJson()) {
            return response()->json(['status' => false, 'message' => $message], 422);
        }
        return redirect()->route('admin.campaign.index')->with('error', $message);
    }

    /**
     * Return campaigns as JSON for "Select Campaign" on post create
     */
    public function list(Request $request)
    {
        $campaigns = Campaign::whereIn('status', ['pending', 'posted'])
            ->get(['id', 'name', 'content', 'post_type', 'platform', 'account_ids', 'gallery_image_ids', 'gallery_video_ids']);

        $list = $campaigns->map(function ($c) {
            $media = [];
            if ($c->gallery_image_ids) {
                foreach (array_filter(explode(',', $c->gallery_image_ids)) as $id) {
                    $g = Gallery::find($id);
                    if ($g) {
                        $media[] = ['type' => 'image', 'id' => (int) $id, 'url' => asset('storage/' . $g->file_path)];
                    }
                }
            }
            if ($c->gallery_video_ids) {
                foreach (array_filter(explode(',', $c->gallery_video_ids)) as $id) {
                    $v = Video::find($id);
                    if ($v) {
                        $media[] = ['type' => 'video', 'id' => (int) $id, 'url' => asset('storage/' . $v->file_path)];
                    }
                }
            }
            
            $platformLabel = '—';
            if ($c->platform !== null && isset(SOCIAL_MEDIA_PLATFORMS[$c->platform])) {
                $platformLabel = SOCIAL_MEDIA_PLATFORMS[$c->platform];
            } elseif ($c->platform !== null) {
                $platformLabel = $c->platform;
            }
            
            return [
                'id' => $c->id,
                'name' => $c->name,
                'content' => $c->content ?? '',
                'post_type' => $c->post_type ?? 'Feed',
                'platform' => $c->platform,
                'platform_label' => $platformLabel,
                'account_ids' => $c->account_ids,
                'gallery_image_ids' => $c->gallery_image_ids,
                'gallery_video_ids' => $c->gallery_video_ids,
                'media' => $media,
            ];
        });

        return response()->json(['campaigns' => $list]);
    }
}