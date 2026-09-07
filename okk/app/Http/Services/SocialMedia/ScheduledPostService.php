<?php

namespace App\Http\Services\SocialMedia;

use App\Models\Gallery;
use App\Models\PostHistory;
use App\Models\ScheduledPost;
use App\Models\SocialMediaAccount;
use App\Models\Video;
use App\Models\FileManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Traits\ResponseTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ScheduledPostService
{
    use ResponseTrait;

    protected SocialMediaServiceManager $serviceManager;

    public function __construct(SocialMediaServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function store(Request $request): array
    {
        try {
            $data = $request->validated();

            $accountIds = $request->input('account_ids', []);
            if (empty($accountIds) && $request->filled('social_media_account_id')) {
                $accountIds = [$request->input('social_media_account_id')];
            }
            if (empty($accountIds)) {
                return ['success' => false, 'error' => 'Please select at least one social media account'];
            }

            $accounts = SocialMediaAccount::whereIn('id', $accountIds)->get();
            if ($accounts->isEmpty()) {
                return ['success' => false, 'error' => 'Invalid social media account(s)'];
            }

            $galleryImageIds = (string) ($request->input('gallery_image_ids') ?? '');
            $galleryVideoIds = (string) ($request->input('gallery_video_ids') ?? '');
            $mediaFile = $this->prepareMediaFile($request, $galleryImageIds, $galleryVideoIds);

            $originalPostType = strtolower($data['post_type'] ?? 'feed');
            $servicePostType = $this->normalizeServicePostType($originalPostType);
            $dbPostType = $this->normalizeDbPostType($originalPostType, $galleryVideoIds, $mediaFile);

            $options = array_merge($data['options'] ?? [], [
                'gallery_image_ids' => $galleryImageIds,
                'gallery_video_ids' => $galleryVideoIds,
            ]);

            $successCount = 0;
            $errorMessages = [];

            $publishNow = $request->boolean('publish_now');

            if (!$publishNow && !$request->filled('scheduled_time')) {
                return ['success' => false, 'error' => 'Please set a scheduled date/time to save to the calendar.'];
            }

            $scheduledTime = null;
            if (!$publishNow && $request->filled('scheduled_time')) {
                $appTz = getAppTimeZone();
                $scheduledTime = Carbon::parse($data['scheduled_time'], $appTz)->utc();
            }

            foreach ($accounts as $account) {
                try {
                    if ($publishNow) {
                        $providerCheck = getProviderLimitCheckResult($account->platform);
                        if (!$providerCheck['allowed']) {
                            $errorMessages[] = $account->platform . ': ' . $providerCheck['message'];
                            continue;
                        }

                        $postCheck = getPostLimitCheckResult();
                        if (!$postCheck['allowed']) {
                            $errorMessages[] = $account->platform . ': ' . $postCheck['message'];
                            continue;
                        }

                        $result = $this->publishPostNow($account, $data['content'] ?? '', $mediaFile, $options, $servicePostType);
                        $result['success'] ? $successCount++ : $errorMessages[] = $account->platform . ': ' . ($result['error'] ?? 'Failed to publish');
                    } else {
                        $this->createScheduledPost($account, $data['content'] ?? '', $mediaFile, $scheduledTime, $options, $dbPostType);
                        $successCount++;
                    }
                } catch (Exception $e) {
                    $errorMessages[] = $account->platform . ': ' . $e->getMessage();
                    Log::error('Failed to process post for account', ['account_id' => $account->id, 'error' => $e->getMessage()]);
                }
            }

            if ($successCount > 0) {
                $message = $request->boolean('publish_now')
                    ? "Post published successfully to {$successCount} account(s)"
                    : "Scheduled post created successfully for {$successCount} account(s)";
                if (!empty($errorMessages)) {
                    $message .= '. Errors: ' . implode(', ', $errorMessages);
                }
                return ['success' => true, 'message' => $message];
            }

            return ['success' => false, 'error' => 'Failed to create posts: ' . implode(', ', $errorMessages)];
        } catch (Exception $e) {
            Log::error('Failed to create scheduled post', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Failed to create scheduled post: ' . $e->getMessage()];
        }
    }

    public function update(Request $request, ScheduledPost $post): array
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $galleryImageIds = (string) ($request->input('gallery_image_ids') ?? '');
            $galleryVideoIds = (string) ($request->input('gallery_video_ids') ?? '');

            $mediaPath = $post->media_url;
            $mediaType = $post->media_type;

            $newMediaFile = $this->prepareMediaFile($request, $galleryImageIds, $galleryVideoIds);
            if ($newMediaFile) {
                if ($post->media_url) {
                    $existingFile = FileManager::where('path', $post->media_url)->first();
                    if ($existingFile) {
                        $existingFile->removeFile();
                        $existingFile->delete();
                    } else {
                        Storage::delete($post->media_url);
                    }
                }

                $fileManager = new FileManager();
                $uploaded = $fileManager->upload('social-media-media', $newMediaFile);

                if ($uploaded) {
                    $mediaPath = $uploaded->path;
                    $mediaType = $uploaded->file_type;
                }
            }

            $originalPostType = strtolower($data['post_type'] ?? 'feed');
            $dbPostType = $this->normalizeDbPostType($originalPostType, $galleryVideoIds, $newMediaFile);

            $scheduledTime = $post->scheduled_time;
            if ($request->filled('scheduled_time')) {
                $appTz = getAppTimeZone();
                $scheduledTime = Carbon::parse($request->input('scheduled_time'), $appTz)->utc();
            }

            $post->update([
                'content'       => $data['content'] ?? '',
                'post_type'     => $dbPostType,
                'media_url'     => $mediaPath,
                'media_type'    => $mediaType,
                'scheduled_time' => $scheduledTime,
                'post_metadata' => array_merge($post->post_metadata ?? [], [
                    'gallery_image_ids' => $galleryImageIds,
                    'gallery_video_ids' => $galleryVideoIds,
                ], $data['options'] ?? [])
            ]);

            DB::commit();

            return ['success' => true, 'message' => 'Scheduled post updated successfully'];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update scheduled post', ['post_id' => $post->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Failed to update scheduled post: ' . $e->getMessage()];
        }
    }

    private function prepareMediaFile(Request $request, ?string $galleryImageIds, ?string $galleryVideoIds): ?UploadedFile
    {
        if ($request->hasFile('media')) {
            return $request->file('media');
        }

        if ($request->hasFile('direct_media')) {
            $directMedia = $request->file('direct_media');
            if (is_array($directMedia) && !empty($directMedia[0])) {
                return $directMedia[0];
            }
        }

        if (!empty($galleryVideoIds)) {
            $videoIds = array_filter(explode(',', $galleryVideoIds));
            if (!empty($videoIds[0])) {
                if (str_starts_with($videoIds[0], 'uploaded:')) {
                    $path = substr($videoIds[0], 9);
                    $fullPath = storage_path('app/public/' . $path);
                    if (file_exists($fullPath)) {
                        $fileManager = FileManager::where('path', $path)->first();
                        $mimeType = $fileManager ? $fileManager->file_type : (mime_content_type($fullPath) ?: 'video/mp4');
                        $fileName = $fileManager ? $fileManager->file_name : basename($path);
                        return $this->createUploadedFileFromModel($path, $fileName, $mimeType);
                    }
                } else {
                    $video = Video::find($videoIds[0]);
                    if ($video) {
                        return $this->createUploadedFileFromModel($video->file_path, $video->file_name, $video->file_type ?? 'video/mp4');
                    }
                }
            }
        }

        if (!empty($galleryImageIds)) {
            $imageIds = array_filter(explode(',', $galleryImageIds));
            if (!empty($imageIds[0])) {
                if (str_starts_with($imageIds[0], 'uploaded:')) {
                    $path = substr($imageIds[0], 9);
                    $fullPath = storage_path('app/public/' . $path);
                    if (file_exists($fullPath)) {
                        $fileManager = FileManager::where('path', $path)->first();
                        $mimeType = $fileManager ? $fileManager->file_type : (mime_content_type($fullPath) ?: 'image/jpeg');
                        $fileName = $fileManager ? $fileManager->file_name : basename($path);
                        return $this->createUploadedFileFromModel($path, $fileName, $mimeType);
                    }
                } else {
                    $gallery = Gallery::find($imageIds[0]);
                    if ($gallery) {
                        return $this->createUploadedFileFromModel($gallery->file_path, $gallery->file_name, $gallery->file_type ?? 'image/jpeg');
                    }
                }
            }
        }

        return null;
    }

    private function createUploadedFileFromModel(string $filePath, string $fileName, string $mimeType): ?UploadedFile
    {
        $fileRecord = FileManager::where('path', $filePath)->first();

        if ($fileRecord) {
            $disk = Storage::disk($fileRecord->storage_type);
            if (!$disk->exists($fileRecord->path)) {
                return null;
            }

            $tempPath = sys_get_temp_dir() . '/' . uniqid() . '_' . $fileName;
            file_put_contents($tempPath, $disk->get($fileRecord->path));

            return new UploadedFile($tempPath, $fileName, $mimeType, null, true);
        }

        $fullPath = storage_path('app/public/' . $filePath);
        if (!file_exists($fullPath)) {
            return null;
        }

        $tempPath = sys_get_temp_dir() . '/' . uniqid() . '_' . $fileName;
        copy($fullPath, $tempPath);

        return new UploadedFile($tempPath, $fileName, $mimeType, null, true);
    }

    private function normalizeDbPostType(string $originalPostType, ?string $galleryVideoIds, ?UploadedFile $mediaFile): string
    {
        if (in_array($originalPostType, ['reel', 'reels', 'shorts'])) {
            return 'reel';
        }
        if ($originalPostType === 'video') {
            return 'video';
        }
        if ($originalPostType === 'story') {
            return 'story';
        }
        return 'feed';
    }

    private function normalizeServicePostType(string $originalPostType): string
    {
        if (in_array($originalPostType, ['reels', 'shorts'])) {
            return 'reel';
        }
        return $originalPostType;
    }

    public function getAllData($request)
    {
        $query = ScheduledPost::with(['socialMediaAccount', 'user']);

        if ($request->search) {
             $query->where('content', 'like', '%' . $request->search . '%')
                   ->orWhereHas('user', function($q) use ($request) {
                       $q->where('name', 'like', '%' . $request->search . '%');
                   })
                   ->orWhereHas('socialMediaAccount', function($q) use ($request) {
                       $q->where('platform', 'like', '%' . $request->search . '%')
                         ->orWhere('username', 'like', '%' . $request->search . '%');
                   });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('platform')) {
            $query->whereHas('socialMediaAccount', function ($q) use ($request) {
                $q->where('platform', $request->platform);
            });
        }

        $query->orderBy('id', 'desc');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('platform', function ($post) {
                $icon = '';
                switch($post->socialMediaAccount->platform) {
                    case 'twitter': $icon = '<i class="fa-brands fa-x-twitter"></i>'; break;
                    case 'facebook': $icon = '<i class="fa-brands fa-facebook-f"></i>'; break;
                    case 'instagram': $icon = '<i class="fa-brands fa-instagram"></i>'; break;
                    case 'linkedin': $icon = '<i class="fa-brands fa-linkedin-in"></i>'; break;
                    case 'youtube': $icon = '<i class="fa-brands fa-youtube"></i>'; break;
                    case 'tiktok': $icon = '<i class="fa-brands fa-tiktok"></i>'; break;
                    case 'threads': $icon = '<i class="fa-brands fa-threads"></i>'; break;
                    default: $icon = '<i class="fa-solid fa-share-nodes"></i>';
                }
                return '<div class="platform-area">
                            <span class="icon">' . $icon . '</span>
                            ' . ucfirst($post->socialMediaAccount->platform) . '
                        </div>';
            })
            ->addColumn('account_name', function ($post) {
                $accountName = $post->socialMediaAccount->username ?: $post->socialMediaAccount->account_id;
                return '<div class="account-info">' . e($accountName) . '</div>';
            })
            ->addColumn('user', function ($post) {
                $name = e($post->user->name ?? '');
                return '<div class="account-info">' . $name . '</div>';
            })
            ->addColumn('scheduled_time', function ($post) {
                if (!$post->scheduled_time) {
                    return '-';
                }
                return formatDateToCustomer($post->scheduled_time, 'd/m/Y - h:i A', 'UTC', getAppTimeZone());
            })
            ->addColumn('created_at', function ($post) {
                return $post->created_at->diffForHumans();
            })
            ->addColumn('post_type', function ($post) {
                $postType = $post->post_type ?? 'feed';
                return '<span class="status active">' . __(ucfirst($postType)) . '</span>';
            })
            ->addColumn('status', function ($post) {
                $statusClass = 'pending';
                if($post->status == 'posted') $statusClass = 'active';
                elseif($post->status == 'failed') $statusClass = 'failed';
                elseif($post->status == 'cancelled') $statusClass = 'failed';
                
                return '<span class="status ' . $statusClass . '">' . ucfirst($post->status) . '</span>';
            })
            ->addColumn('action', function ($post) {
                $actions = '<div class="dropdown options-area">
                                <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">';
                
                if($post->status === 'pending') {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="location.href=\'' . route('admin.all-posts.edit', $post->id) . '\'">' . __('Edit') . '</a></li>';
                }
                
                $actions .= '<li><a class="dropdown-item delete-item" href="#" data-route="' . route('admin.all-posts.destroy', $post->id) . '">' . __('Delete') . '</a></li>
                                </ul>
                            </div>';
                return $actions;
            })
            ->rawColumns(['platform', 'account_name', 'user', 'status', 'post_type', 'action'])
            ->make(true);
    }

    public function createScheduledPost(
        SocialMediaAccount $account,
        ?string $content = null,
        ?UploadedFile $media = null,
        ?\DateTime $scheduledTime = null,
        array $options = [],
        string $postType = 'feed',
        ?int $campaignId = null
    ): ScheduledPost {
        if (!$account->isActive()) {
            throw new Exception($account->getInactiveReason());
        }

        $content = $content ?? '';

        $platformInfo = $this->serviceManager->getPlatformInfo($account->platform);
        if (!$platformInfo['supported']) {
            throw new Exception("Platform {$account->platform} is not supported");
        }

        if (!empty($content)) {
            $limitKey = (in_array($account->platform, ['instagram', 'threads'])) ? 'caption' : 'post';
            $limit = $platformInfo['content_limits'][$limitKey] ?? $platformInfo['content_limits']['caption'] ?? 10000;
            
            if (strlen($content) > $limit) {
                throw new Exception("Content exceeds platform limit of {$limit} characters");
            }
        }

        $mediaData = null;
        if ($media) {
            $mediaData = $this->validateAndPrepareMedia($account, $media, $platformInfo);
        }

        $dbPostType = 'feed';
        if ($postType === 'reel' || $postType === 'reels' || $postType === 'shorts') {
            $dbPostType = 'reel';
        } elseif ($postType === 'story') {
            $dbPostType = 'story';
        } elseif ($postType === 'video') {
            $hasVideo = ($mediaData && $mediaData['type'] === 'video') || 
                       (isset($options['gallery_video_ids']) && !empty($options['gallery_video_ids']));
            $dbPostType = $hasVideo ? 'video' : 'feed';
        } elseif ($postType === 'feed') {
            $dbPostType = 'feed';
        }

        return DB::transaction(function () use ($account, $content, $dbPostType, $mediaData, $scheduledTime, $options, $campaignId) {
            return ScheduledPost::create([
                'user_id' => $account->user_id,
                'social_media_account_id' => $account->id,
                'campaign_id' => $campaignId,
                'content' => $content,
                'post_type' => $dbPostType,
                'media_url' => $mediaData['url'] ?? null,
                'media_type' => $mediaData['type'] ?? null,
                'scheduled_time' => $scheduledTime ?? now(),
                'status' => 'pending',
                'post_metadata' => $options,
                'tenant_id' => $account->tenant_id,
            ]);
        });
    }

    public function publishScheduledPostNow(ScheduledPost $post): array
    {
        if ($post->status !== 'pending') {
            return ['success' => false, 'error' => 'Only pending posts can be published. Current status: ' . $post->status];
        }

        try {
            $result = $this->executeScheduledPost($post);

            if ($result['success']) {
                $post->markAsPosted($result['platform_post_id'] ?? null, $result['response'] ?? []);
                return ['success' => true, 'message' => 'Post published successfully'];
            }

            $post->markAsFailed($result['error']);
            return ['success' => false, 'error' => $result['error']];
        } catch (Exception $e) {
            Log::error('Failed to publish scheduled post from calendar', [
                'post_id' => $post->id,
                'error'   => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function processAllPendingPosts(): array
    {
        $duePosts = ScheduledPost::pending()->get();

        return $this->processPostCollection($duePosts);
    }

    public function processDuePosts(): array
    {
        return $this->processPostCollection(ScheduledPost::due()->get());
    }

    private function processPostCollection(\Illuminate\Support\Collection $posts): array
    {
        $results = [
            'processed' => 0,
            'successful' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($posts as $post) {
            $results['processed']++;

            try {
                $result = $this->executeScheduledPost($post);

                if ($result['success']) {
                    $results['successful']++;
                    $post->markAsPosted($result['platform_post_id'] ?? null, $result['response'] ?? []);
                } else {
                    $results['failed']++;
                    $post->markAsFailed($result['error']);

                    Log::error('Scheduled post failed', [
                        'post_id'    => $post->id,
                        'account_id' => $post->social_media_account_id,
                        'error'      => $result['error'],
                    ]);
                }
            } catch (Exception $e) {
                $results['failed']++;
                $results['errors'][] = $e->getMessage();
                $post->markAsFailed($e->getMessage());

                Log::error('Scheduled post execution error', [
                    'post_id' => $post->id,
                    'error'   => $e->getMessage(),
                    'trace'   => $e->getTraceAsString(),
                ]);
            }
        }

        return $results;
    }

    protected function executeScheduledPost(ScheduledPost $post): array
    {
        $account = $post->socialMediaAccount;

        if (!$account || !$account->isActive()) {
            return [
                'success' => false,
                'error' => 'Account is not active or has expired'
            ];
        }

        $providerCheck = getProviderLimitCheckResult($account->platform, $post->user_id);
        if (!$providerCheck['allowed']) {
            return [
                'success' => false,
                'error' => $providerCheck['message']
            ];
        }

        $postCheck = getPostLimitCheckResult($post->user_id);
        if (!$postCheck['allowed']) {
            return [
                'success' => false,
                'error' => $postCheck['message']
            ];
        }

        $media = null;
        if ($post->media_url) {
            $fileName = basename($post->media_url);
            $mimeType = $post->media_type ?? 'application/octet-stream';
            $media = $this->createUploadedFileFromModel($post->media_url, $fileName, $mimeType);

            if (!$media) {
                Log::warning('Scheduled post media file not found on disk', [
                    'post_id'   => $post->id,
                    'media_url' => $post->media_url,
                ]);
            }
        }

        $service = $this->serviceManager->getService($account->platform);

        if (!$service) {
            return [
                'success' => false,
                'error' => "Service not available for platform: {$account->platform}"
            ];
        }

        try {
            $result = $service->post($account, $post->content, $media, $post->post_metadata, $post->post_type ?? 'feed');

            $this->createPostHistory($post, $result, 'scheduled');

            return $result;
        } catch (Exception $e) {
            Log::error('Social media posting failed', [
                'platform' => $account->platform,
                'account_id' => $account->id,
                'post_id' => $post->id,
                'error' => $e->getMessage(),
                'token_length' => strlen($account->access_token ?? ''),
                'has_account_id' => !empty($account->account_id)
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function createPostHistory(ScheduledPost $post, array $result, string $historyType = 'scheduled'): void
    {
        $postHistory = PostHistory::create([
            'user_id'                 => $post->user_id,
            'social_media_account_id' => $post->social_media_account_id,
            'scheduled_post_id'       => $post->id,
            'content'                 => $post->content,
            'media_url'               => $post->media_url,
            'platform'                => $post->socialMediaAccount->platform,
            'platform_post_id'        => $result['platform_post_id'] ?? null,
            'post_type'               => $historyType,
            'posted_at'               => now(),
            'response_data'           => $result['response'] ?? null,
            'success'                 => $result['success'],
            'error_message'           => $result['success'] ? null : ($result['error'] ?? 'Unknown error'),
            'post_metadata'           => $post->post_metadata,
            'tenant_id'               => $post->tenant_id,
        ]);

        if ($result['success'] && !empty($result['platform_post_id'])) {
            try {
                $service = $this->serviceManager->getService($post->socialMediaAccount->platform);
                if ($service && method_exists($service, 'updatePostEngagement')) {
                    $service->updatePostEngagement($post->socialMediaAccount, $result['platform_post_id']);
                    Log::info('Engagement fetched after post creation', [
                        'post_history_id' => $postHistory->id,
                        'platform' => $post->socialMediaAccount->platform,
                        'platform_post_id' => $result['platform_post_id']
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to fetch engagement after post', [
                    'post_history_id' => $postHistory->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    protected function validateAndPrepareMedia(SocialMediaAccount $account, UploadedFile $media, array $platformInfo): array
    {
        $mimeType = $media->getMimeType();
        $extension = $media->getClientOriginalExtension();

        $supportedTypes = $platformInfo['media_types'];
        $mediaType = null;

        if (str_starts_with($mimeType, 'image/')) {
            $mediaType = 'image';
            if (!in_array($extension, $supportedTypes['image'])) {
                throw new Exception("Image format {$extension} is not supported for {$account->platform}");
            }
        } elseif (str_starts_with($mimeType, 'video/')) {
            $mediaType = 'video';
            if (!in_array($extension, $supportedTypes['video'])) {
                throw new Exception("Video format {$extension} is not supported for {$account->platform}");
            }
        } else {
            throw new Exception("Media type {$mimeType} is not supported for {$account->platform}");
        }

        $maxSize = $mediaType === 'image' ? 8 * 1024 * 1024 : 100 * 1024 * 1024;

        if ($media->getSize() > $maxSize) {
            throw new Exception("File size exceeds maximum allowed size of " . ($maxSize / 1024 / 1024) . "MB");
        }

        $fileManager = new FileManager();
        $uploaded = $fileManager->upload('social-media-media', $media);

        if (!$uploaded) {
            throw new Exception('Failed to upload media file');
        }

        return [
            'url' => $uploaded->path,
            'type' => $mediaType
        ];
    }

    public function retryFailedPost(ScheduledPost $post): array
    {
        if ($post->status !== 'failed') {
            return [
                'success' => false,
                'error' => 'Only failed posts can be retried'
            ];
        }

        if (!$post->canRetry()) {
            return [
                'success' => false,
                'error' => 'Maximum retry attempts reached'
            ];
        }

        try {
            $result = $this->executeScheduledPost($post);

            if ($result['success']) {
                $post->markAsPosted($result['platform_post_id'], $result['response'] ?? []);
                return [
                    'success' => true,
                    'message' => 'Post retried successfully'
                ];
            } else {
                $post->markAsFailed($result['error']);
                return [
                    'success' => false,
                    'error' => $result['error']
                ];
            }
        } catch (Exception $e) {
            $post->markAsFailed($e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function getUserScheduledPosts(int $userId, array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = ScheduledPost::where('user_id', $userId);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['platform'])) {
            $query->whereHas('socialMediaAccount', function ($q) use ($filters) {
                $q->where('platform', $filters['platform']);
            });
        }

        if (isset($filters['date_from'])) {
            $query->where('scheduled_time', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('scheduled_time', '<=', $filters['date_to']);
        }

        return $query->with(['socialMediaAccount'])->latest()->get();
    }

    public function publishPostNow(
        SocialMediaAccount $account,
        ?string $content = null,
        ?UploadedFile $media = null,
        array $options = [],
        string $postType = 'feed'
    ): array {
        Log::info('publishPostNow called', [
            'account_id' => $account->id,
            'platform' => $account->platform,
            'post_type' => $postType,
            'has_media' => $media !== null,
            'media_type' => $media ? $media->getMimeType() : null,
            'options' => $options
        ]);
        
        if (!$account->isActive()) {
            return [
                'success' => false,
                'error' => $account->getInactiveReason()
            ];
        }

        $content = $content ?? '';

        $platformInfo = $this->serviceManager->getPlatformInfo($account->platform);
        if (!$platformInfo['supported']) {
            return [
                'success' => false,
                'error' => "Platform {$account->platform} is not supported"
            ];
        }

        if (!empty($content)) {
            $limitKey = (in_array($account->platform, ['instagram', 'threads'])) ? 'caption' : 'post';
            $limit = $platformInfo['content_limits'][$limitKey] ?? $platformInfo['content_limits']['caption'] ?? 10000;
            
            if (strlen($content) > $limit) {
                return [
                    'success' => false,
                    'error' => "Content exceeds platform limit of {$limit} characters"
                ];
            }
        }

        $mediaData = null;
        if ($media) {
            Log::info('Validating and preparing media', [
                'filename' => $media->getClientOriginalName(),
                'size' => $media->getSize(),
                'mime_type' => $media->getMimeType()
            ]);
            
            try {
                $mediaData = $this->validateAndPrepareMedia($account, $media, $platformInfo);
                Log::info('Media preparation successful', ['media_data' => $mediaData]);
            } catch (Exception $e) {
                Log::error('Media preparation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                return [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        $service = $this->serviceManager->getService($account->platform);
        if (!$service) {
            return [
                'success' => false,
                'error' => "Service not available for platform: {$account->platform}"
            ];
        }

        try {
            Log::info('Executing social media post', [
                'platform' => $account->platform,
                'service_class' => get_class($service),
                'account_id' => $account->id,
                'has_media' => $media !== null,
                'options' => $options
            ]);
            
            $result = $service->post($account, $content, $media, $options, $postType);
            
            Log::info('Post execution result', [
                'success' => $result['success'],
                'platform_post_id' => $result['platform_post_id'] ?? null,
                'error' => $result['error'] ?? null
            ]);

            $dbPostType = 'feed';
            if ($postType === 'reel' || $postType === 'reels' || $postType === 'shorts') {
                $dbPostType = 'reel';
            } elseif ($postType === 'story') {
                $dbPostType = 'story';
            } elseif ($postType === 'video') {
                $hasVideo = ($mediaData && $mediaData['type'] === 'video') || 
                           (isset($options['gallery_video_ids']) && !empty($options['gallery_video_ids']));
                $dbPostType = $hasVideo ? 'video' : 'feed';
            } elseif ($postType === 'feed') {
                $dbPostType = 'feed';
            }

            $post = null;
            DB::transaction(function () use (&$post, $account, $content, $dbPostType, $mediaData, $options, $result) {
                $post = ScheduledPost::create([
                    'user_id' => $account->user_id,
                    'social_media_account_id' => $account->id,
                    'content' => $content,
                    'post_type' => $dbPostType,
                    'media_url' => $mediaData['url'] ?? null,
                    'media_type' => $mediaData['type'] ?? null,
                    'scheduled_time' => now(),
                    'status' => $result['success'] ? 'posted' : 'failed',
                    'platform_post_id' => $result['platform_post_id'] ?? null,
                    'posted_at' => $result['success'] ? now() : null,
                    'error_message' => $result['success'] ? null : $result['error'],
                    'post_metadata' => $options,
                    'tenant_id' => $account->tenant_id,
                ]);

                $this->createPostHistory($post, $result, 'manual');
            });

            return [
                'success' => $result['success'],
                'post' => $post,
                'platform_post_id' => $result['platform_post_id'] ?? null,
                'error' => $result['success'] ? null : $result['error']
            ];
        } catch (Exception $e) {
            $dbPostType = 'feed';
            if ($postType === 'reel' || $postType === 'reels' || $postType === 'shorts') {
                $dbPostType = 'reel';
            } elseif ($postType === 'video' || $postType === 'story') {
                $hasVideo = ($mediaData && $mediaData['type'] === 'video') || 
                           (isset($options['gallery_video_ids']) && !empty($options['gallery_video_ids']));
                $dbPostType = $hasVideo ? 'video' : 'feed';
            }

            $post = DB::transaction(function () use ($account, $content, $dbPostType, $mediaData, $options, $e) {
                return ScheduledPost::create([
                    'user_id' => $account->user_id,
                    'social_media_account_id' => $account->id,
                    'content' => $content,
                    'post_type' => $dbPostType,
                    'media_url' => $mediaData['url'] ?? null,
                    'media_type' => $mediaData['type'] ?? null,
                    'scheduled_time' => now(),
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'post_metadata' => $options,
                    'tenant_id' => $account->tenant_id,
                ]);
            });

            return [
                'success' => false,
                'post' => $post,
                'error' => $e->getMessage()
            ];
        }
    }

    public function cancelScheduledPost(ScheduledPost $post): bool
    {
        if ($post->status !== 'pending') {
            return false;
        }

        $post->update(['status' => 'cancelled']);
        return true;
    }
}