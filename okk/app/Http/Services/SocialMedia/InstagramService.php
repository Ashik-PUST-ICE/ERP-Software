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

class InstagramService implements SocialMediaServiceInterface
{
    protected ?SocialMediaConfig $config = null;

    public function __construct() {}

    protected function getApiVersion(): string
    {
        $this->loadConfig();
        return $this->config?->settings['api_version'] ?? 'v23.0';
    }

    protected function getGraphApiUrl(): string
    {
        $this->loadConfig();
        return $this->config?->settings['graph_api_url'] ?? 'https://graph.instagram.com';
    }

    protected function getApiBaseUrl(): string
    {
        return rtrim($this->getGraphApiUrl(), '/') . '/' . $this->getApiVersion() . '/';
    }

    protected function loadConfig(): void
    {
        if ($this->config === null) {
            $this->config = SocialMediaConfig::where('platform', 'instagram')->where('is_active', true)->first();
        }
    }

    protected function reloadConfig(): void
    {
        $this->config = null;
        $this->loadConfig();
    }

    public function post(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        try {
            if ($account->page_id) {
                Log::info('Posting to Instagram page', ['page_id' => $account->page_id, 'post_type' => $postType]);
                return $this->postToPage($account, $account->page_id, $content, $media, $options, $postType);
            } elseif ($account->group_id) {
                Log::info('Posting to Instagram group', ['group_id' => $account->group_id, 'post_type' => $postType]);
                return $this->postToGroup($account, $account->group_id, $content, $media, $options, $postType);
            } else {
                Log::info('Posting to Instagram user profile', ['post_type' => $postType]);
                return $this->postToProfile($account, $content, $media, $options, $postType);
            }
        } catch (Exception $e) {
            Log::error('Instagram post failed', ['account_id' => $account->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function postFeed(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = []): array
    {
        if (!$media) {
            return ['success' => false, 'error' => 'Media is required for Instagram feed posts', 'platform_post_id' => null];
        }

        $mimeType = $media->getMimeType();
        if (str_starts_with($mimeType, 'video/')) {
            return ['success' => false, 'error' => 'Instagram feed posts only accept images, not videos. Use reels for video content.', 'platform_post_id' => null];
        }

        try {
            $mediaUrl = $this->getPublicMediaUrl($media);
            if (!$mediaUrl) {
                return ['success' => false, 'error' => 'Failed to upload media to public URL. Instagram requires media to be accessible via a public HTTPS URL.', 'platform_post_id' => null];
            }

            $containerResult = $this->createMediaContainer($account, $mediaUrl, $content, $options);
            if (!$containerResult['success']) {
                return $containerResult;
            }

            return $this->publishContainer($account, $containerResult['container_id']);
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function postReel(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = []): array
    {
        if (!$media) {
            return ['success' => false, 'error' => 'Video media is required for Instagram reels', 'platform_post_id' => null];
        }

        try {
            $mediaUrl = $this->getPublicMediaUrl($media);
            if (!$mediaUrl) {
                return ['success' => false, 'error' => 'Failed to upload media. Make sure ngrok is running or use Cloudinary.', 'platform_post_id' => null];
            }

            $igUserId = $account->account_id;
            $endpoint = "{$igUserId}/media";

            $data = [
                'media_type' => 'REELS',
                'video_url' => $mediaUrl,
                'access_token' => $account->access_token
            ];

            if (!empty($content)) {
                $data['caption'] = $content;
            }

            if (isset($options['share_to_feed'])) {
                $data['share_to_feed'] = $options['share_to_feed'];
            }

            $response = Http::post($this->getApiBaseUrl() . $endpoint, $data);
            $responseData = $response->json();

            if (!$response->successful()) {
                $errorMessage = $responseData['error']['message'] ?? 'Failed to create reel container';
                if (isset($responseData['error']['error_subcode']) && $responseData['error']['error_subcode'] == 2207076) {
                    $errorMessage = 'Instagram cannot access the video URL. Make sure ngrok is running and APP_URL is set correctly.';
                }
                return ['success' => false, 'error' => $errorMessage, 'platform_post_id' => null];
            }

            $containerId = $responseData['id'];
            sleep(5);

            return $this->publishContainer($account, $containerId);
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function postStory(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = []): array
    {
        if (!$media) {
            return ['success' => false, 'error' => 'Media is required for Instagram stories', 'platform_post_id' => null];
        }

        try {
            $mediaUrl = $this->getPublicMediaUrl($media);
            if (!$mediaUrl) {
                return ['success' => false, 'error' => 'Failed to upload media to public URL.', 'platform_post_id' => null];
            }

            $igUserId = $account->account_id;
            $endpoint = "{$igUserId}/media";

            $mimeType = $media->getMimeType();
            $isVideo = str_starts_with($mimeType, 'video/');

            $payload = ['media_type' => 'STORIES'];

            if (!empty($content)) {
                $payload['caption'] = $content;
            }

            if ($isVideo) {
                $payload['video_url'] = $mediaUrl;
            } else {
                $payload['image_url'] = $mediaUrl;
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $account->access_token
            ])->post($this->getApiBaseUrl() . $endpoint, $payload);

            $responseData = $response->json();

            if (!$response->successful()) {
                return ['success' => false, 'error' => $responseData['error']['message'] ?? 'Failed to create story container', 'platform_post_id' => null];
            }

            $containerId = $responseData['id'];
            sleep(3);

            return $this->publishContainer($account, $containerId);
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function createMediaContainer(SocialMediaAccount $account, string $mediaUrl, string $caption, array $options = []): array
    {
        $igUserId = $account->account_id;
        $endpoint = "{$igUserId}/media";

        $payload = ['image_url' => $mediaUrl];

        if (!empty($caption)) {
            $payload['caption'] = $caption;
        }

        if (isset($options['location_id'])) {
            $payload['location_id'] = $options['location_id'];
        }

        if (isset($options['user_tags'])) {
            $payload['user_tags'] = $options['user_tags'];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $account->access_token
        ])->post($this->getApiBaseUrl() . $endpoint, $payload);

        $responseData = $response->json();

        if ($response->successful()) {
            return ['success' => true, 'container_id' => $responseData['id']];
        }

        return ['success' => false, 'error' => $responseData['error']['message'] ?? 'Failed to create media container', 'platform_post_id' => null];
    }

    protected function publishContainer(SocialMediaAccount $account, string $containerId): array
    {
        $igUserId = $account->account_id;
        $endpoint = "{$igUserId}/media_publish";

        $payload = ['creation_id' => $containerId];

        $maxRetries = 6;
        $retryDelay = 10;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $account->access_token
            ])->post($this->getApiBaseUrl() . $endpoint, $payload);

            $responseData = $response->json();

            if ($response->successful()) {
                return ['success' => true, 'platform_post_id' => $responseData['id'], 'response' => $responseData];
            }

            if (isset($responseData['error']['code']) && $responseData['error']['code'] == 9007) {
                if ($attempt < $maxRetries) {
                    sleep($retryDelay);
                    $retryDelay *= 2;
                    continue;
                } else {
                    return ['success' => false, 'error' => 'Media is not ready after multiple attempts.', 'platform_post_id' => null];
                }
            }

            if (isset($responseData['error']['code']) && $responseData['error']['code'] == 100 && isset($responseData['error']['error_subcode']) && $responseData['error']['error_subcode'] == 2207076) {
                return ['success' => false, 'error' => 'Instagram cannot access media from this URL. Use ngrok or Cloudinary.', 'platform_post_id' => null];
            }

            return ['success' => false, 'error' => $responseData['error']['message'] ?? 'Failed to publish container', 'platform_post_id' => null];
        }

        return ['success' => false, 'error' => 'Failed to publish container after multiple attempts', 'platform_post_id' => null];
    }

    protected function getPublicMediaUrl(UploadedFile $media): ?string
    {
        try {
            if (env('CLOUDINARY_CLOUD_NAME') && env('CLOUDINARY_API_KEY') && env('CLOUDINARY_API_SECRET')) {
                return $this->uploadToCloudinary($media);
            }

            $path = $media->store('instagram-media', 'public');
            $url = Storage::disk('public')->url($path);

            if (!str_starts_with($url, 'http')) {
                $url = URL::to($url);
            }

            $url = str_replace('//autopost.test/autopost.test/', '//autopost.test/', $url);

            if (!str_starts_with($url, 'https://')) {
                $url = str_replace('http://', 'https://', $url);
            }

            return $url;
        } catch (Exception $e) {
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
            $folder = $isVideo ? 'instagram_reels' : 'instagram_feed';
            $publicId = $folder . '/' . uniqid();

            $paramsToSign = ['public_id' => $publicId, 'timestamp' => $timestamp];
            ksort($paramsToSign);
            
            $signatureParts = [];
            foreach ($paramsToSign as $key => $value) {
                $signatureParts[] = "{$key}={$value}";
            }
            $signatureString = implode('&', $signatureParts) . $apiSecret;
            $signature = sha1($signatureString);

            $response = Http::attach('file', file_get_contents($media->getPathname()), $media->getClientOriginalName())
                ->post("https://api.cloudinary.com/v1_1/{$cloudName}/{$resourceType}/upload", [
                    'api_key' => $apiKey,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                    'public_id' => $publicId
                ]);

            if (!$response->successful()) {
                return null;
            }

            $result = $response->json();
            return $result['secure_url'];
        } catch (Exception $e) {
            return null;
        }
    }

    public function getAccountInfo(SocialMediaAccount $account): array
    {
        if (!$account->account_id) {
            return ['success' => false, 'error' => 'Instagram Business Account ID is required'];
        }

        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $account->access_token])
            ->get($this->getApiBaseUrl() . $account->account_id, ['fields' => 'id,username,name,profile_picture_url,followers_count,follows_count,media_count']);

        return $response->successful()
            ? ['success' => true, 'data' => $response->json()]
            : ['success' => false, 'error' => $response->json()['error']['message'] ?? 'Failed to get account info'];
    }

    public function getInstagramAccounts(SocialMediaAccount $account): array
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $account->access_token])
            ->get($this->getApiBaseUrl() . 'me/accounts', ['fields' => 'id,name,instagram_business_account']);

        if (!$response->successful()) {
            return ['success' => false, 'error' => $response->json()['error']['message'] ?? 'Failed to get Instagram accounts'];
        }

        $data = $response->json();
        $instagramAccounts = [];

        foreach ($data['data'] ?? [] as $page) {
            if (isset($page['instagram_business_account'])) {
                $instagramAccounts[] = [
                    'page_id' => $page['id'],
                    'page_name' => $page['name'],
                    'instagram_account_id' => $page['instagram_business_account']['id']
                ];
            }
        }

        return ['success' => true, 'accounts' => $instagramAccounts];
    }

    public function validateAccount(SocialMediaAccount $account): bool
    {
        if (!$account->account_id) {
            return false;
        }

        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $account->access_token])
            ->get($this->getApiBaseUrl() . $account->account_id, ['fields' => 'id,username']);

        return $response->successful();
    }

    protected function getInstagramConfig(): ?array
    {
        $config = SocialMediaConfig::where('platform', 'instagram')->where('is_active', true)->first();
        if (!$config) {
            return null;
        }

        $settings = $config->settings;
        if (is_string($settings)) {
            $settings = json_decode($settings, true);
        }

        if (!isset($settings['access_token'])) {
            return null;
        }

        return $settings;
    }

    public function getPlatformName(): string
    {
        return 'instagram';
    }

    public function getSupportedMediaTypes(): array
    {
        return ['image' => ['jpg', 'jpeg', 'png'], 'video' => ['mp4', 'mov']];
    }

    protected function postToProfile(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        if (!$account->account_id) {
            return ['success' => false, 'error' => 'Instagram Business Account ID is required', 'platform_post_id' => null];
        }

        if (!$account->access_token) {
            return ['success' => false, 'error' => 'Instagram account access token not found', 'platform_post_id' => null];
        }

        Log::info('Posting to Instagram profile', ['ig_account_id' => $account->account_id, 'has_media' => !is_null($media), 'post_type' => $postType]);

        return match ($postType) {
            'reel', 'video' => $this->postReel($account, $content, $media, $options),
            'story' => $this->postStory($account, $content, $media, $options),
            default => $media && str_starts_with($media->getMimeType(), 'video/') 
                ? $this->postReel($account, $content, $media, $options)
                : $this->postFeed($account, $content, $media, $options)
        };
    }

    public function postToPage(SocialMediaAccount $account, string $pageId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        return ['success' => false, 'error' => 'Instagram does not support posting to pages.', 'platform_post_id' => null];
    }

    public function postToGroup(SocialMediaAccount $account, string $groupId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        return ['success' => false, 'error' => 'Instagram does not support posting to groups.', 'platform_post_id' => null];
    }

    public function getPages(SocialMediaAccount $account): array
    {
        return $this->getInstagramAccounts($account);
    }

    public function getGroups(SocialMediaAccount $account): array
    {
        return ['success' => true, 'groups' => []];
    }

    public function getContentLimits(): array
    {
        return ['caption' => 2200, 'hashtags' => 30, 'mentions' => 20];
    }

    public function getPostInsights(SocialMediaAccount $account, string $platformPostId): array
    {
        try {
            $accessToken = $account->access_token;
            $fields = 'like_count,comments_count';
            $url = $this->getGraphApiUrl() . '/' . $platformPostId;
            
            $response = Http::get($url, ['access_token' => $accessToken, 'fields' => $fields]);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'likes' => $data['like_count'] ?? 0,
                    'comments' => $data['comments_count'] ?? 0,
                    'shares' => 0,
                    'reach' => 0,
                    'views' => 0
                ];
            }
            
            $basicResponse = Http::get($url, ['access_token' => $accessToken]);
            if ($basicResponse->successful()) {
                $basicData = $basicResponse->json();
                return [
                    'likes' => $basicData['like_count'] ?? 0,
                    'comments' => $basicData['comments_count'] ?? 0,
                    'shares' => 0,
                    'reach' => 0,
                    'views' => 0
                ];
            }
        } catch (Exception $e) {}
        
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
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'error' => $e->getMessage(), 'metrics' => $metrics];
        }
        
        return ['success' => true, 'metrics' => $metrics];
    }
}