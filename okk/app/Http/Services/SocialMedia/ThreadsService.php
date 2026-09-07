<?php

namespace App\Http\Services\SocialMedia;

use App\Models\SocialMediaAccount;
use App\Models\SocialMediaConfig;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ThreadsService implements SocialMediaServiceInterface
{
    protected string $apiVersion = 'v1.0';
    protected string $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = "https://graph.threads.net/{$this->apiVersion}/";
    }

    public function post(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'threads'): array
    {
        try {
            Log::info('Posting to Threads', ['account_id' => $account->id, 'content_length' => strlen($content), 'has_media' => !is_null($media), 'post_type' => $postType]);

            $accessToken = !empty($account->access_token) ? $account->access_token : null;

            if (!$accessToken) {
                return ['success' => false, 'error' => 'Threads access token not found in account configuration.', 'platform_post_id' => null];
            }

            return $this->postToThreads($account, $content, $media, $options, $accessToken);
        } catch (Exception $e) {
            Log::error('Threads post failed', ['account_id' => $account->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function postToThreads(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $accessToken): array
    {
        $mediaUrl = null;
        if ($media) {
            $mediaUrl = $this->getPublicMediaUrl($media);
            if (!$mediaUrl) {
                return ['success' => false, 'error' => 'Failed to upload media to public URL.', 'platform_post_id' => null];
            }
        }

        $threadsUserId = $account->account_id;
        $endpoint = "{$threadsUserId}/threads";

        $data = ['access_token' => $accessToken];

        if (!empty($content)) {
            $data['text'] = $content;
        }

        if ($mediaUrl) {
            $mimeType = $media->getMimeType();
            if (str_starts_with($mimeType, 'video/')) {
                $data['video_url'] = $mediaUrl;
                $data['media_type'] = 'VIDEO';
            } else {
                $data['image_url'] = $mediaUrl;
                $data['media_type'] = 'IMAGE';
            }
        } else {
            $data['media_type'] = 'TEXT';
        }

        Log::info('Creating Threads media container', ['endpoint' => $this->apiBaseUrl . $endpoint, 'has_media' => !empty($mediaUrl), 'media_type' => $data['media_type'], 'text_length' => strlen($data['text'] ?? '')]);

        $response = Http::post($this->apiBaseUrl . $endpoint, $data);
        $responseData = $response->json();

        Log::info('Threads container creation response', ['status' => $response->status(), 'response' => $responseData]);

        if (!$response->successful()) {
            $errorMessage = $responseData['error']['message'] ?? 'Failed to create Threads container';

            if (isset($responseData['error']['error_subcode']) && $responseData['error']['error_subcode'] == 2207076) {
                $errorMessage = 'Threads cannot access the media URL. Make sure Cloudinary is configured or ngrok is running.';
            }

            Log::error('Threads API error', ['account_id' => $account->id, 'status' => $response->status(), 'error' => $errorMessage, 'response' => $responseData]);
            return ['success' => false, 'error' => 'Threads API Error: ' . $errorMessage, 'platform_post_id' => null];
        }

        $containerId = $responseData['id'];
        Log::info('Threads container created', ['container_id' => $containerId]);

        if ($mediaUrl) {
            $isReady = $this->waitForContainerReady($containerId, $accessToken);
            if (!$isReady) {
                return ['success' => false, 'error' => 'Media container not ready after 60 seconds. The video may be too large or processing failed.', 'platform_post_id' => null];
            }
        }

        return $this->publishThreadsContainer($account, $containerId, $accessToken);
    }

    protected function waitForContainerReady(string $containerId, string $accessToken, int $maxWait = 60): bool
    {
        $endpoint = "{$containerId}";
        $waited = 0;
        $checkInterval = 5;

        Log::info('Waiting for container to be ready', ['container_id' => $containerId]);

        while ($waited < $maxWait) {
            $response = Http::get($this->apiBaseUrl . $endpoint, ['fields' => 'id,status,error_message', 'access_token' => $accessToken]);

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['status'] ?? 'UNKNOWN';

                Log::info('Container status check', ['container_id' => $containerId, 'status' => $status, 'waited' => "{$waited}s"]);

                if ($status === 'FINISHED') {
                    Log::info('Container is ready!', ['container_id' => $containerId]);
                    return true;
                }

                if ($status === 'ERROR') {
                    Log::error('Container processing error', ['container_id' => $containerId, 'error' => $data['error_message'] ?? 'Unknown error']);
                    return false;
                }

                Log::info("Container still processing, waiting {$checkInterval}s more...", ['status' => $status, 'waited' => $waited]);
            } else {
                Log::warning('Failed to check container status', ['status' => $response->status(), 'response' => $response->json()]);
            }

            sleep($checkInterval);
            $waited += $checkInterval;
        }

        Log::warning('Container status check timeout', ['container_id' => $containerId, 'waited' => "{$waited}s"]);
        return false;
    }

    protected function publishThreadsContainer(SocialMediaAccount $account, string $containerId, string $accessToken): array
    {
        $threadsUserId = $account->account_id;
        $endpoint = "{$threadsUserId}/threads_publish";

        $payload = ['creation_id' => $containerId, 'access_token' => $accessToken];

        Log::info('Publishing Threads container', ['endpoint' => $this->apiBaseUrl . $endpoint, 'container_id' => $containerId, 'threads_user_id' => $threadsUserId]);

        $response = Http::post($this->apiBaseUrl . $endpoint, $payload);
        $responseData = $response->json();

        Log::info('Threads publish response', ['status' => $response->status(), 'response' => $responseData]);

        if ($response->successful()) {
            Log::info('Threads post successful', ['account_id' => $account->id, 'post_id' => $responseData['id'] ?? null]);
            return ['success' => true, 'platform_post_id' => $responseData['id'] ?? null, 'response' => $responseData];
        }

        $errorMessage = $responseData['error']['message'] ?? 'Failed to publish Threads container';
        $errorCode = $responseData['error']['code'] ?? null;
        $errorSubcode = $responseData['error']['error_subcode'] ?? null;

        Log::error('Threads publish failed', ['error_code' => $errorCode, 'error_subcode' => $errorSubcode, 'error_message' => $errorMessage, 'response' => $responseData]);
        return ['success' => false, 'error' => $errorMessage, 'platform_post_id' => null];
    }

    protected function getPublicMediaUrl(UploadedFile $media): ?string
    {
        try {
            if (env('CLOUDINARY_CLOUD_NAME') && env('CLOUDINARY_API_KEY') && env('CLOUDINARY_API_SECRET')) {
                return $this->uploadToCloudinary($media);
            }

            $path = $media->store('threads-media', 'public');
            $url = URL::to('/storage/' . $path);

            if (!str_starts_with($url, 'http')) {
                $url = URL::to($url);
            }

            $url = str_replace('//autopost.test/autopost.test/', '//autopost.test/', $url);

            if (!str_starts_with($url, 'https://')) {
                $url = str_replace('http://', 'https://', $url);
            }

            $parsedHost = parse_url($url, PHP_URL_HOST);
            $isLocal = str_contains($url, 'localhost') || str_contains($url, '127.0.0.1') || str_ends_with($parsedHost, '.test');

            if ($isLocal && !str_contains($url, 'ngrok')) {
                Log::warning('Local URL detected - Threads may not be able to access it', ['url' => $url, 'suggestion' => 'Add Cloudinary credentials to .env or use ngrok']);
            }

            Log::info('Media uploaded to public URL', ['path' => $path, 'url' => $url]);
            return $url;
        } catch (Exception $e) {
            Log::error('Failed to upload media to public URL', ['error' => $e->getMessage()]);
            return null;
        }
    }

    protected function uploadToCloudinary(UploadedFile $media): ?string
    {
        try {
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $apiKey = env('CLOUDINARY_API_KEY');
            $apiSecret = env('CLOUDINARY_API_SECRET');

            $timestamp = time();
            $mimeType = $media->getMimeType();
            $isVideo = str_starts_with($mimeType, 'video/');
            $resourceType = $isVideo ? 'video' : 'image';
            $folder = $isVideo ? 'threads_reels' : 'threads_feed';
            $publicId = $folder . '/' . uniqid();

            $paramsToSign = ['public_id' => $publicId, 'timestamp' => $timestamp];
            ksort($paramsToSign);

            $signatureParts = [];
            foreach ($paramsToSign as $key => $value) {
                $signatureParts[] = "{$key}={$value}";
            }
            $signatureString = implode('&', $signatureParts) . $apiSecret;
            $signature = sha1($signatureString);

            Log::info('Uploading to Cloudinary', ['cloud_name' => $cloudName, 'public_id' => $publicId, 'resource_type' => $resourceType]);

            $response = Http::attach('file', file_get_contents($media->getPathname()), $media->getClientOriginalName())
                ->post("https://api.cloudinary.com/v1_1/{$cloudName}/{$resourceType}/upload", [
                    'api_key' => $apiKey,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                    'public_id' => $publicId
                ]);

            if (!$response->successful()) {
                Log::error('Cloudinary upload failed', ['status' => $response->status(), 'response' => $response->json()]);
                return null;
            }

            $result = $response->json();
            Log::info('Cloudinary upload successful', ['url' => $result['secure_url'], 'resource_type' => $resourceType]);
            return $result['secure_url'];
        } catch (Exception $e) {
            Log::error('Cloudinary upload error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function postToPage(SocialMediaAccount $account, string $pageId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'threads'): array
    {
        return $this->post($account, $content, $media, $options);
    }

    public function postToGroup(SocialMediaAccount $account, string $groupId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'threads'): array
    {
        return $this->post($account, $content, $media, $options);
    }

    public function getAccountInfo(SocialMediaAccount $account): array
    {
        try {
            $accessToken = $account->access_token;
            $threadsUserId = $account->account_id;

            $response = Http::get($this->apiBaseUrl . $threadsUserId, [
                'fields' => 'id,username,threads_profile_picture_url,threads_biography',
                'access_token' => $accessToken
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => [
                        'id' => $data['id'] ?? $account->account_id,
                        'username' => $data['username'] ?? $account->username,
                        'profile_picture' => $data['threads_profile_picture_url'] ?? null,
                        'biography' => $data['threads_biography'] ?? null,
                        'platform' => 'threads',
                        'is_active' => $account->is_active
                    ]
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to fetch account info',
                'data' => ['id' => $account->account_id, 'username' => $account->username, 'platform' => 'threads', 'is_active' => $account->is_active]
            ];
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
        return !empty($account->access_token) && $account->is_active && !empty($account->account_id);
    }

    public function getPlatformName(): string
    {
        return 'threads';
    }

    public function getSupportedMediaTypes(): array
    {
        return ['image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'], 'video' => ['mp4', 'mov', 'avi', 'webm']];
    }

    public function getContentLimits(): array
    {
        return ['text' => 500, 'image' => 10 * 1024 * 1024, 'video' => 100 * 1024 * 1024];
    }

    public function getPostInsights(SocialMediaAccount $account, string $platformPostId): array
    {
        try {
            $accessToken = $account->access_token;
            $fields = 'like_count,comment_count,share_count,replies_count';
            $response = Http::get('https://graph.threads.net/' . $platformPostId, ['access_token' => $accessToken, 'fields' => $fields]);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'likes' => $data['like_count'] ?? 0,
                    'comments' => $data['comment_count'] ?? 0,
                    'shares' => $data['share_count'] ?? 0,
                    'reach' => 0,
                    'views' => $data['replies_count'] ?? 0
                ];
            }
        } catch (Exception $e) {
            Log::error('Failed to fetch Threads post insights', ['post_id' => $platformPostId, 'error' => $e->getMessage()]);
        }
        
        return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
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
                Log::info('Threads engagement updated for post', ['post_history_id' => $postHistory->id, 'platform_post_id' => $platformPostId, 'likes' => $metrics['likes'], 'comments' => $metrics['comments'], 'shares' => $metrics['shares']]);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'error' => $e->getMessage(), 'metrics' => $metrics];
        }
        
        return ['success' => true, 'metrics' => $metrics];
    }
}
