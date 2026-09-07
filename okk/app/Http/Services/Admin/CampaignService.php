<?php

namespace App\Http\Services\Admin;

use App\Models\Campaign;
use App\Models\Gallery;
use App\Models\Video;
use App\Models\SocialMediaAccount;
use App\Models\FileManager;
use App\Traits\ResponseTrait;
use App\Http\Services\SocialMedia\ScheduledPostService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CampaignService
{
    use ResponseTrait;

    protected ScheduledPostService $scheduledPostService;

    public function __construct(ScheduledPostService $scheduledPostService)
    {
        $this->scheduledPostService = $scheduledPostService;
    }

    public function getAllData(Request $request)
    {
        $query = Campaign::query()
            ->withCount(['scheduledPosts as pending_posts_count' => function ($q) {
                $q->where('status', 'pending');
            }])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return datatables($query)
            ->addColumn('campaign_name', function ($campaign) {
                $progress = $campaign->progress;
                $html = '<div class="campaign-info">';
                $html .= '<h4 class="campaign-title">' . e($campaign->name) . '</h4>';
                $html .= '<div class="campaign-progress">';
                $html .= '<div class="progress" role="progressbar" aria-label="Campaign Progress" aria-valuenow="' . $progress . '" aria-valuemin="0" aria-valuemax="100" style="height: 5px">';
                $html .= '<div class="progress-bar" style="width: ' . $progress . '%"></div>';
                $html .= '</div>';
                $html .= '<span class="campaign-value">' . $progress . '%</span>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('status', function ($campaign) {
                $status = $campaign->status ?? 'pending';
                $statusClass = 'pending';
                if ($status === 'posted') {
                    $statusClass = 'active';
                } elseif ($status === 'failed') {
                    $statusClass = 'failed';
                }
                $statusText = $status === 'posted' ? __('Posted') : ($status === 'failed' ? __('Failed') : __('Pending'));
                return '<span class="status ' . $statusClass . '">' . $statusText . '</span>';
            })
            ->addColumn('created_date', function ($campaign) {
                return $campaign->created_at ? $campaign->created_at->format('m/d/Y - h:i A') : '-';
            })
            ->addColumn('end_date', function ($campaign) {
                return $campaign->end_date ? $campaign->end_date->format('m/d/Y') : '-';
            })
            ->addColumn('platform', function ($campaign) {
                if ($campaign->platform && isset(SOCIAL_MEDIA_PLATFORMS[$campaign->platform])) {
                    return SOCIAL_MEDIA_PLATFORMS[$campaign->platform];
                }
                return $campaign->platform ?? '-';
            })
            ->addColumn('action', function ($campaign) {
                $editUrl = route('admin.campaign.edit', $campaign->id);
                $destroyUrl = route('admin.campaign.destroy', $campaign->id);
                // $publishUrl = route('admin.campaign.publish', $campaign->id);
                $token = csrf_token();
                $pendingCount = (int) ($campaign->pending_posts_count ?? 0);
                $hasPending = $pendingCount > 0;
                $isPosted = ($campaign->status ?? '') === 'posted';

                $html = '<div class="dropdown options-area">' .
                    '<a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' .
                    '<i class="fa-solid fa-ellipsis"></i></a>' .
                    '<ul class="dropdown-menu dropdown-menu-end">';
                if (!$isPosted) {
                    $html .= '<li><a class="dropdown-item" href="' . $editUrl . '">' . __('Edit') . '</a></li>';
                }
                // if ($hasPending) {
                //     $html .= '<li><button type="button" class="dropdown-item campaign-publish-btn" data-url="' . $publishUrl . '" data-campaign-name="' . e($campaign->name) . '">' . __('Publish') . ' (' . $pendingCount . ')</button></li>';
                // }
                $html .= '<li><form method="POST" action="' . $destroyUrl . '" class="d-inline campaign-delete-form">' .
                    '<input type="hidden" name="_token" value="' . $token . '">' .
                    '<input type="hidden" name="_method" value="DELETE">' .
                    '<button type="button" class="dropdown-item btn-link campaign-delete-btn" data-action="' . $destroyUrl . '">' . __('Delete') . '</button></form></li>' .
                    '</ul></div>';
                return $html;
            })
            ->rawColumns(['campaign_name', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $this->normalizeAccountIds($request);

        if ($request->platform) {
            $providerCheck = getProviderLimitCheckResult($request->platform);
            if (!$providerCheck['allowed']) {
                return $this->error([], $providerCheck['message']);
            }
        }

        try {
            DB::beginTransaction();

            $campaign = new Campaign();
            $campaign->name = $request->name;
            $campaign->start_date = $request->start_date;
            $campaign->end_date = $request->end_date;
            $campaign->scheduled_time = $request->scheduled_time ? \Carbon\Carbon::parse($request->scheduled_time, getAppTimeZone())->utc() : null;
            $campaign->description = $request->description;
            $campaign->platform = $request->platform;
            $campaign->account_ids = $request->account_ids ? implode(',', (array) $request->account_ids) : null;
            $campaign->content = $request->content;
            $campaign->post_type = $request->post_type ?? 'Feed';
            $campaign->status = in_array($request->status ?? '', ['pending', 'posted', 'failed']) ? $request->status : 'pending';
            $campaign->gallery_image_ids = $request->gallery_image_ids;
            $campaign->gallery_video_ids = $request->gallery_video_ids;
            $campaign->created_by = auth()->id();
            $campaign->tenant_id = auth()->user()->tenant_id;

            $campaign->save();

            $publishResults = null;
            $scheduleResults = null;
            if ($request->boolean('publish_now')) {
                $publishResults = $this->publishCampaignPosts($request, $campaign);
                if ($publishResults) {
                    $successCount = $publishResults['successful'] ?? 0;
                    $failedCount = $publishResults['failed'] ?? 0;
                    if ($successCount > 0) {
                        $campaign->status = 'posted';
                        $campaign->save();
                    } elseif ($failedCount > 0 && $successCount === 0) {
                        $campaign->status = 'failed';
                        $campaign->save();
                    }
                }
            } elseif ($request->filled('scheduled_time')) {
                $scheduleResults = $this->scheduleCampaignPosts($request, $campaign);
            } elseif ($request->has('publish_now') && !$request->boolean('publish_now')) {
                DB::rollBack();
                return $this->error([], __('Please set a scheduled date/time to save as scheduled posts.'));
            }

            DB::commit();

            if ($scheduleResults !== null) {
                $successCount = $scheduleResults['successful'] ?? 0;
                $failedCount = $scheduleResults['failed'] ?? 0;
                $errors = $scheduleResults['errors'] ?? [];
                if ($successCount > 0 && $failedCount === 0) {
                    return $this->success(['redirect_url' => route('admin.campaign.index')], __('Scheduled posts created successfully for :count account(s)', ['count' => $successCount]));
                }
                if ($successCount > 0 && $failedCount > 0) {
                    return $this->success(['redirect_url' => route('admin.campaign.index')], __('Scheduled for :count account(s). :failed failed.', ['count' => $successCount, 'failed' => $failedCount]) . ' ' . implode(', ', array_slice($errors, 0, 3)));
                }
                return $this->error(['redirect_url' => route('admin.campaign.index')], __('Failed to create scheduled posts:') . ' ' . implode(', ', array_slice($errors, 0, 3)));
            }

            if ($request->boolean('publish_now')) {
                if ($publishResults === null) {
                    return $this->success(['redirect_url' => route('admin.campaign.index')], __('Campaign created but no active accounts selected for publishing'));
                }
                $successCount = $publishResults['successful'] ?? 0;
                $failedCount = $publishResults['failed'] ?? 0;
                $errors = $publishResults['errors'] ?? [];
                if ($successCount > 0 && $failedCount === 0) {
                    return $this->success(['redirect_url' => route('admin.campaign.index')], __('Posts published successfully to :count account(s)', ['count' => $successCount]));
                }
                if ($successCount > 0 && $failedCount > 0) {
                    return $this->success(['redirect_url' => route('admin.campaign.index')], __('Posts published to :count account(s). :failed failed.', ['count' => $successCount, 'failed' => $failedCount]) . ' ' . implode(', ', array_slice($errors, 0, 3)));
                }
                return $this->error(['redirect_url' => route('admin.campaign.index')], __('Campaign created but failed to publish posts:') . ' ' . implode(', ', array_slice($errors, 0, 3)));
            }

            return $this->success(['redirect_url' => route('admin.campaign.index')], getMessage(CREATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Campaign store error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return $this->error([], $e->getMessage());
        }
    }

    protected function normalizeAccountIds(Request $request): void
    {
        if ($request->has('account_ids') && is_string($request->account_ids)) {
            $ids = array_filter(array_map('trim', explode(',', $request->account_ids)));
            $request->merge(['account_ids' => $ids]);
        }
    }

    protected function publishCampaignPosts(Request $request, Campaign $campaign): array
    {
        $results = ['successful' => 0, 'failed' => 0, 'errors' => []];

        $accountIds = $request->account_ids ? (array) $request->account_ids : [];
        if (empty($accountIds)) {
            return $results;
        }

        $postCheck = getPostLimitCheckResult();
        if (!$postCheck['allowed']) {
            $results['errors'][] = $postCheck['message'];
            return $results;
        }

        $accounts = SocialMediaAccount::whereIn('id', $accountIds)->where('is_active', true)->get();
        if ($accounts->isEmpty()) {
            return $results;
        }

        $mediaFile = $this->getCampaignMediaFile($request);
        $galleryImageIds = $request->gallery_image_ids ?? '';
        $galleryVideoIds = $request->gallery_video_ids ?? '';

        $servicePostType = strtolower($campaign->post_type ?? 'feed');
        if ($servicePostType === 'reels' || $servicePostType === 'shorts') {
            $servicePostType = 'reel';
        }

        foreach ($accounts as $account) {
            try {
                $result = $this->scheduledPostService->publishPostNow(
                    $account,
                    $campaign->content ?? '',
                    $mediaFile,
                    ['gallery_image_ids' => $galleryImageIds, 'gallery_video_ids' => $galleryVideoIds],
                    $servicePostType
                );
                if ($result['success']) {
                    $results['successful']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = $account->platform . ' (' . ($account->username ?? $account->account_id) . '): ' . ($result['error'] ?? 'Unknown error');
                    Log::warning('Failed to publish campaign post', ['account_id' => $account->id, 'campaign_id' => $campaign->id, 'error' => $result['error'] ?? 'Unknown error']);
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = $account->platform . ': ' . $e->getMessage();
                Log::error('Exception publishing campaign post', ['account_id' => $account->id, 'campaign_id' => $campaign->id, 'error' => $e->getMessage()]);
            }
        }

        return $results;
    }

    protected function scheduleCampaignPosts(Request $request, Campaign $campaign): array
    {
        $results = ['successful' => 0, 'failed' => 0, 'errors' => []];

        $accountIds = $request->account_ids ? (array) $request->account_ids : [];
        if (empty($accountIds)) {
            return $results;
        }

        $accounts = SocialMediaAccount::whereIn('id', $accountIds)->where('is_active', true)->get();
        if ($accounts->isEmpty()) {
            return $results;
        }

        $scheduledTime = \Carbon\Carbon::parse($request->scheduled_time, getAppTimeZone())->utc();

        $mediaFile = $this->getCampaignMediaFile($request);
        $galleryImageIds = $request->gallery_image_ids ?? '';
        $galleryVideoIds = $request->gallery_video_ids ?? '';
        $options = ['gallery_image_ids' => $galleryImageIds, 'gallery_video_ids' => $galleryVideoIds];

        $servicePostType = strtolower($campaign->post_type ?? 'feed');
        if ($servicePostType === 'reels' || $servicePostType === 'shorts') {
            $servicePostType = 'reel';
        }

        foreach ($accounts as $account) {
            try {
                $this->scheduledPostService->createScheduledPost(
                    $account,
                    $campaign->content ?? '',
                    $mediaFile,
                    $scheduledTime,
                    $options,
                    $servicePostType,
                    $campaign->id
                );
                $results['successful']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = $account->platform . ' (' . ($account->username ?? $account->account_id) . '): ' . $e->getMessage();
                Log::warning('Failed to schedule campaign post', ['account_id' => $account->id, 'campaign_id' => $campaign->id, 'error' => $e->getMessage()]);
            }
        }

        if ($results['successful'] > 0) {
            $campaign->update(['status' => 'pending']);
        }

        return $results;
    }

    public function publishCampaignPostsNow(Campaign $campaign): array
    {
        $results = ['successful' => 0, 'failed' => 0, 'errors' => []];

        $pendingPosts = $campaign->scheduledPosts()->pending()->get();
        if ($pendingPosts->isEmpty()) {
            return $results;
        }

        foreach ($pendingPosts as $post) {
            try {
                $result = $this->scheduledPostService->publishScheduledPostNow($post);
                if ($result['success']) {
                    $results['successful']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = $post->socialMediaAccount->platform . ': ' . ($result['error'] ?? 'Unknown error');
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = $post->socialMediaAccount->platform . ': ' . $e->getMessage();
                Log::warning('Failed to publish campaign scheduled post', ['post_id' => $post->id, 'campaign_id' => $campaign->id, 'error' => $e->getMessage()]);
            }
        }

        $campaign->refreshStatusFromScheduledPosts();
        return $results;
    }

    protected function getCampaignMediaFile(Request $request): ?\Illuminate\Http\UploadedFile
    {
        $galleryImageIds = $request->gallery_image_ids ?? '';
        $galleryVideoIds = $request->gallery_video_ids ?? '';

        if (!empty($galleryVideoIds)) {
            $videoIds = array_filter(explode(',', $galleryVideoIds));
            if (!empty($videoIds[0])) {
                $video = Video::find($videoIds[0]);
                if ($video) {
                    return $this->videoOrImageToUploadedFile($video->file_path, $video->file_name, $video->file_type ?? 'video/mp4');
                }
            }
        }
        if (!empty($galleryImageIds)) {
            $imageIds = array_filter(explode(',', $galleryImageIds));
            if (!empty($imageIds[0])) {
                $gallery = Gallery::find($imageIds[0]);
                if ($gallery) {
                    return $this->videoOrImageToUploadedFile($gallery->file_path, $gallery->file_name, $gallery->file_type ?? 'image/jpeg');
                }
            }
        }
        return null;
    }

    private function videoOrImageToUploadedFile(string $filePath, string $fileName, string $mimeType): ?\Illuminate\Http\UploadedFile
    {
        $fileRecord = FileManager::where('path', $filePath)->first();
        if ($fileRecord) {
            $disk = Storage::disk($fileRecord->storage_type);
            if ($disk->exists($fileRecord->path)) {
                if ($fileRecord->storage_type === 'public') {
                    $fullPath = storage_path('app/public/' . $fileRecord->path);
                } else {
                    $tempPath = sys_get_temp_dir() . '/' . uniqid() . '_' . $fileName;
                    file_put_contents($tempPath, $disk->get($fileRecord->path));
                    $fullPath = $tempPath;
                }
                if (file_exists($fullPath)) {
                    $tempPath = sys_get_temp_dir() . '/' . uniqid() . '_' . $fileName;
                    copy($fullPath, $tempPath);
                    return new \Illuminate\Http\UploadedFile($tempPath, $fileName, $mimeType, null, true);
                }
            }
        } else {
            $fullPath = storage_path('app/public/' . $filePath);
            if (file_exists($fullPath)) {
                $tempPath = sys_get_temp_dir() . '/' . uniqid() . '_' . $fileName;
                copy($fullPath, $tempPath);
                return new \Illuminate\Http\UploadedFile($tempPath, $fileName, $mimeType, null, true);
            }
        }
        return null;
    }

    public function update(Request $request, $id)
    {
        $this->normalizeAccountIds($request);

        if ($request->platform) {
            $providerCheck = getProviderLimitCheckResult($request->platform);
            if (!$providerCheck['allowed']) {
                return $this->error([], $providerCheck['message']);
            }
        }

        try {
            DB::beginTransaction();

            $campaign = Campaign::findOrFail($id);
            $campaign->name = $request->name;
            $campaign->start_date = $request->start_date;
            $campaign->end_date = $request->end_date;
            $campaign->scheduled_time = $request->scheduled_time ? \Carbon\Carbon::parse($request->scheduled_time, getAppTimeZone())->utc() : null;
            $campaign->description = $request->description;
            $campaign->platform = $request->platform;
            $campaign->account_ids = $request->account_ids ? implode(',', (array) $request->account_ids) : null;
            $campaign->content = $request->content;
            $campaign->post_type = $request->post_type ?? 'Feed';
            $campaign->status = in_array($request->status ?? '', ['pending', 'posted', 'failed']) ? $request->status : 'pending';
            $campaign->gallery_image_ids = $request->gallery_image_ids;
            $campaign->gallery_video_ids = $request->gallery_video_ids;
            $campaign->save();

            $publishResults = null;
            $scheduleResults = null;
            if ($request->boolean('publish_now')) {
                $publishResults = $this->publishCampaignPosts($request, $campaign);
                if ($publishResults && ($publishResults['successful'] ?? 0) > 0) {
                    $campaign->status = 'posted';
                    $campaign->save();
                }
            } elseif ($request->filled('scheduled_time') && $request->has('publish_now') && !$request->boolean('publish_now')) {
                $scheduleResults = $this->scheduleCampaignPosts($request, $campaign);
            } elseif ($request->has('publish_now') && !$request->boolean('publish_now')) {
                DB::rollBack();
                return $this->error([], __('Please set a scheduled date/time to save as scheduled posts.'));
            }

            DB::commit();

            if ($scheduleResults !== null) {
                $successCount = $scheduleResults['successful'] ?? 0;
                $failedCount = $scheduleResults['failed'] ?? 0;
                $errors = $scheduleResults['errors'] ?? [];
                if ($successCount > 0 && $failedCount === 0) {
                    return $this->success(['redirect_url' => route('admin.campaign.index')], __('Scheduled posts created successfully for :count account(s)', ['count' => $successCount]));
                }
                if ($successCount > 0 && $failedCount > 0) {
                    return $this->success(['redirect_url' => route('admin.campaign.index')], __('Scheduled for :count account(s). :failed failed.', ['count' => $successCount, 'failed' => $failedCount]) . ' ' . implode(', ', array_slice($errors, 0, 3)));
                }
                return $this->error(['redirect_url' => route('admin.campaign.index')], __('Failed to create scheduled posts:') . ' ' . implode(', ', array_slice($errors, 0, 3)));
            }

            if ($publishResults !== null) {
                $successCount = $publishResults['successful'] ?? 0;
                $failedCount = $publishResults['failed'] ?? 0;
                $errors = $publishResults['errors'] ?? [];
                if ($successCount > 0 && $failedCount === 0) {
                    return $this->success(['redirect_url' => route('admin.campaign.index')], __('Posts published successfully to :count account(s)', ['count' => $successCount]));
                }
                if ($successCount > 0 && $failedCount > 0) {
                    return $this->success(['redirect_url' => route('admin.campaign.index')], __('Posts published to :count account(s). :failed failed.', ['count' => $successCount, 'failed' => $failedCount]) . ' ' . implode(', ', array_slice($errors, 0, 3)));
                }
                return $this->error(['redirect_url' => route('admin.campaign.index')], __('Failed to publish posts:') . ' ' . implode(', ', array_slice($errors, 0, 3)));
            }

            return $this->success(['redirect_url' => route('admin.campaign.index')], getMessage(UPDATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function deleteById($id)
    {
        try {
            DB::beginTransaction();
            $campaign = Campaign::findOrFail($id);
            $campaign->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }
}