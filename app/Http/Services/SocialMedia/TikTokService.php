<?php

namespace App\Http\Services\SocialMedia;

use App\Models\SocialMediaAccount;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TikTokService implements SocialMediaServiceInterface
{
    protected string $apiVersion = 'v2';
    protected string $apiBaseUrl = 'https://open.tiktokapis.com/';

    public function __construct()
    {
        $this->updateBaseUrlFromConfig();
    }

    protected function updateBaseUrlFromConfig(): void
    {
        $this->apiBaseUrl = 'https://open.tiktokapis.com/';
    }

    public function post(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        try {
            $this->updateBaseUrlFromConfig();
            Log::info('Posting to TikTok', ['account_id' => $account->id, 'post_type' => $postType, 'has_media' => !is_null($media)]);
            return $this->postVideo($account, $content, $media, $options);
        } catch (Exception $e) {
            Log::error('TikTok post failed', ['account_id' => $account->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function postVideo(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = []): array
    {
        if (!$media) {
            return ['success' => false, 'error' => 'Video media is required for TikTok posts', 'platform_post_id' => null];
        }

        try {
            $initResult = $this->initializeVideoUpload($account, $media);
            if (!$initResult['success']) {
                return $initResult;
            }

            $uploadResult = $this->uploadVideoToUrl($media, $initResult['upload_url']);
            if (!$uploadResult['success']) {
                return $uploadResult;
            }

            return $this->publishVideo($account, $content, $initResult['publish_id'], $options);
        } catch (Exception $e) {
            Log::error('TikTok video post failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function initializeVideoUpload(SocialMediaAccount $account, UploadedFile $media): array
    {
        try {
            $endpoint = 'v2/post/publish/video/init/';

            $payload = [
                'post_info' => [
                    'title' => 'Video Upload',
                    'privacy_level' => 'SELF_ONLY',
                    'disable_duet' => false,
                    'disable_comment' => false,
                    'disable_stitch' => false,
                    'video_cover_timestamp_ms' => 1000
                ],
                'source_info' => [
                    'source' => 'FILE_UPLOAD',
                    'video_size' => $media->getSize(),
                    'chunk_size' => $media->getSize(),
                    'total_chunk_count' => 1
                ]
            ];

            $fullUrl = $this->apiBaseUrl . $endpoint;
            $maskedToken = substr($account->access_token, 0, 4) . '...' . substr($account->access_token, -4);

            Log::info('Initializing TikTok video upload', ['url' => $fullUrl, 'token_preview' => $maskedToken]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . trim($account->access_token),
                'Content-Type' => 'application/json; charset=UTF-8'
            ])->post($fullUrl, $payload);

            $responseData = $response->json();
            Log::info('TikTok upload initialization response', ['status' => $response->status(), 'response' => $responseData]);

            if ($response->successful() && isset($responseData['data'])) {
                return [
                    'success' => true,
                    'upload_url' => $responseData['data']['upload_url'] ?? null,
                    'publish_id' => $responseData['data']['publish_id'] ?? null
                ];
            } else {
                $errorMessage = $responseData['error']['message'] ?? 'Upload initialization failed';
                Log::error('TikTok upload initialization failed', ['error' => $errorMessage, 'response' => $responseData]);
                return ['success' => false, 'error' => $errorMessage];
            }
        } catch (Exception $e) {
            Log::error('TikTok upload initialization exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function uploadVideoToUrl(UploadedFile $media, string $uploadUrl): array
    {
        try {
            $maxSize = 4 * 1024 * 1024 * 1024;
            if ($media->getSize() > $maxSize) {
                return ['success' => false, 'error' => 'Video file size exceeds TikTok limit (4GB)'];
            }

            $allowedTypes = ['video/mp4', 'video/quicktime', 'video/webm'];
            if (!in_array($media->getMimeType(), $allowedTypes)) {
                return ['success' => false, 'error' => 'Unsupported video format. TikTok accepts MP4, MOV, WEBM'];
            }

            Log::info('Uploading video to TikTok URL', ['file_size' => $media->getSize(), 'mime_type' => $media->getMimeType()]);

            $fileSize = $media->getSize();
            $response = Http::withHeaders([
                'Content-Type' => 'video/mp4',
                'Content-Length' => $fileSize,
                'Content-Range' => 'bytes 0-' . ($fileSize - 1) . '/' . $fileSize,
            ])->withBody(file_get_contents($media->getPathname()), 'video/mp4')->put($uploadUrl);

            Log::info('TikTok video upload to URL response', ['status' => $response->status()]);

            if ($response->successful() || $response->status() === 201) {
                return ['success' => true];
            } else {
                Log::error('TikTok video upload to URL failed', ['status' => $response->status(), 'body' => $response->body()]);
                return ['success' => false, 'error' => 'Video upload to URL failed'];
            }
        } catch (Exception $e) {
            Log::error('TikTok video upload to URL exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function publishVideo(SocialMediaAccount $account, string $content, string $publishId, array $options = []): array
    {
        try {
            $endpoint = 'v2/post/publish/status/fetch/';
            $payload = ['publish_id' => $publishId];

            Log::info('Checking TikTok video publish status', ['endpoint' => $this->apiBaseUrl . $endpoint, 'publish_id' => $publishId]);

            $maxAttempts = 30;
            $attempt = 0;
            
            while ($attempt < $maxAttempts) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $account->access_token,
                    'Content-Type' => 'application/json; charset=UTF-8'
                ])->post($this->apiBaseUrl . $endpoint, $payload);

                $responseData = $response->json();
                Log::info('TikTok publish status check', ['attempt' => $attempt, 'status' => $response->status(), 'response' => $responseData]);

                if ($response->successful() && isset($responseData['data'])) {
                    $status = $responseData['data']['status'] ?? '';
                    
                    if ($status === 'PUBLISH_COMPLETE') {
                        $postId = $responseData['data']['publicaly_available_post_id'] ?? $publishId;
                        $videoUrl = "https://www.tiktok.com/@" . ($account->username ?? 'user') . "/video/" . $postId;
                        
                        Log::info('TikTok video published successfully!', ['post_id' => $postId, 'video_url' => $videoUrl]);
                        return ['success' => true, 'platform_post_id' => $postId, 'response' => $responseData, 'video_url' => $videoUrl];
                    } elseif ($status === 'FAILED') {
                        return ['success' => false, 'error' => $responseData['data']['fail_reason'] ?? 'Video publishing failed', 'platform_post_id' => null];
                    }
                    
                    sleep(2);
                    $attempt++;
                } else {
                    break;
                }
            }

            return ['success' => true, 'platform_post_id' => $publishId, 'response' => ['status' => 'PROCESSING']];
        } catch (Exception $e) {
            Log::error('TikTok video publish exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    public function getAccountInfo(SocialMediaAccount $account): array
    {
        try {
            $endpoint = 'v2/user/info/';
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $account->access_token,
                'Content-Type' => 'application/json; charset=UTF-8'
            ])->get($this->apiBaseUrl . $endpoint, ['fields' => 'open_id,union_id,avatar_url,display_name,bio_description,is_verified,follower_count,following_count,likes_count']);

            $responseData = $response->json();

            return $response->successful()
                ? ['success' => true, 'data' => $responseData['data'] ?? $responseData]
                : ['success' => false, 'error' => $responseData['error']['message'] ?? 'Failed to get account info'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getPages(SocialMediaAccount $account): array
    {
        return ['success' => true, 'pages' => []];
    }

    public function getGroups(SocialMediaAccount $account): array
    {
        return ['success' => true, 'groups' => []];
    }

    public function validateAccount(SocialMediaAccount $account): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $account->access_token,
            'Content-Type' => 'application/json; charset=UTF-8'
        ])->get($this->apiBaseUrl . 'v2/user/info/');

        return $response->successful();
    }

    public function getPlatformName(): string
    {
        return 'tiktok';
    }

    public function getSupportedMediaTypes(): array
    {
        return ['video' => ['mp4', 'mov', 'webm']];
    }

    public function getContentLimits(): array
    {
        return ['caption' => 2200, 'hashtags' => 100, 'video_duration' => 600, 'video_size' => 4 * 1024 * 1024 * 1024];
    }

    protected function postToProfile(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = []): array
    {
        return $this->postVideo($account, $content, $media, $options);
    }

    public function postToPage(SocialMediaAccount $account, string $pageId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        return ['success' => false, 'error' => 'TikTok does not support posting to pages.', 'platform_post_id' => null];
    }

    public function postToGroup(SocialMediaAccount $account, string $groupId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        return ['success' => false, 'error' => 'TikTok does not support posting to groups.', 'platform_post_id' => null];
    }

    public function getPostInsights(SocialMediaAccount $account, string $platformPostId): array
    {
        try {
            $accessToken = $this->getAccessToken($account);
            
            if (!$accessToken) {
                Log::warning('TikTok access token not found for engagement metrics');
                return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
            }
            
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $accessToken])
                ->get('https://open.tiktokapis.com/v2/video/list/', ['fields' => 'like_count,comment_count,share_count,view_count']);
            
            if ($response->successful()) {
                $data = $response->json();
                $videos = $data['data']['videos'] ?? [];
                
                if (!empty($videos)) {
                    $video = $videos[0];
                    return [
                        'likes' => $video['like_count'] ?? 0,
                        'comments' => $video['comment_count'] ?? 0,
                        'shares' => $video['share_count'] ?? 0,
                        'reach' => 0,
                        'views' => $video['view_count'] ?? 0
                    ];
                }
            }
        } catch (Exception $e) {
            Log::error('Failed to fetch TikTok post insights', ['post_id' => $platformPostId, 'error' => $e->getMessage()]);
        }
        
        return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
    }

    protected function getAccessToken(SocialMediaAccount $account): ?string
    {
        return $account->access_token;
    }

    public function updatePostEngagement(SocialMediaAccount $account, string $platformPostId): array
    {
        $metrics = $this->getPostInsights($account, $platformPostId);
        
        try {
            DB::beginTransaction();
            
            $postHistory = \App\Models\PostHistory::where('social_media_account_id', $account->id)
                ->where('platform_post_id', $platformPostId)
                ->first();
            
            if ($postHistory) {
                $postHistory->updateEngagement($metrics);
                Log::info('TikTok engagement updated for post', ['post_history_id' => $postHistory->id, 'platform_post_id' => $platformPostId, 'likes' => $metrics['likes'], 'comments' => $metrics['comments'], 'shares' => $metrics['shares']]);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'error' => $e->getMessage(), 'metrics' => $metrics];
        }
        
        return ['success' => true, 'metrics' => $metrics];
    }
}