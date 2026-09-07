<?php

namespace App\Http\Services\SocialMedia;

use App\Models\SocialMediaAccount;
use App\Models\SocialMediaConfig;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookService implements SocialMediaServiceInterface
{
    protected ?SocialMediaConfig $config = null;

    public function __construct() {}

    protected function getApiVersion(): string
    {
        $this->loadConfig();
        return $this->config && !empty($this->config->settings['api_version'])
            ? $this->config->settings['api_version']
            : 'v24.0';
    }

    protected function getGraphApiUrl(): string
    {
        $this->loadConfig();
        return $this->config && !empty($this->config->settings['graph_api_url'])
            ? rtrim($this->config->settings['graph_api_url'], '/')
            : 'https://graph.facebook.com';
    }

    protected function getApiBaseUrl(): string
    {
        return $this->getGraphApiUrl() . '/' . $this->getApiVersion() . '/';
    }

    protected function loadConfig()
    {
        if ($this->config === null) {
            $this->config = SocialMediaConfig::where('platform', 'facebook')->where('is_active', true)->first();
        }
    }

    public function reloadConfig()
    {
        $this->config = null;
        $this->loadConfig();
    }

    public function post(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        try {
            if ($account->page_id) {
                return $this->postToPage($account, $account->page_id, $content, $media, $options, $postType);
            } elseif ($account->group_id) {
                return $this->postToGroup($account, $account->group_id, $content, $media, $options, $postType);
            } else {
                return $this->postToProfile($account, $content, $media, $options, $postType);
            }
        } catch (Exception $e) {
            Log::error('Facebook post failed', ['account_id' => $account->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function getPageAccessToken(SocialMediaAccount $account, string $pageId): ?string
    {
        if (!empty($account->settings['pages']) && is_array($account->settings['pages'])) {
            foreach ($account->settings['pages'] as $page) {
                if ($page['id'] === $pageId && !empty($page['access_token'])) {
                    return $page['access_token'];
                }
            }
        }
        return $account->access_token;
    }

    public function postToPage(SocialMediaAccount $account, string $pageId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        $endpoint = match ($postType) {
            'video' => "{$pageId}/videos",
            'reel' => "{$pageId}/videos",
            default => "{$pageId}/feed"
        };

        if ($postType === 'reel') {
            $data['reel'] = true;
        }

        $data = ['access_token' => $this->getPageAccessToken($account, $pageId)];

        if ($postType === 'feed') {
            $data['message'] = $content;
        } elseif ($postType === 'video' || $postType === 'reel') {
            $data['description'] = $content;
        }

        $mimeType = $media ? $media->getMimeType() : null;
        $isVideo = $media && str_starts_with($mimeType, 'video/');

        if ($postType === 'feed' && $media) {
            if ($isVideo) {
                $endpoint = "{$pageId}/videos";
                $data['description'] = $content;
                $response = $this->makeVideoRequest($endpoint, $media, $data);
            } else {
                $mediaData = $this->uploadMedia($account, $media, $pageId);
                if (!$mediaData['success']) {
                    return ['success' => false, 'error' => 'Failed to upload media: ' . $mediaData['error'], 'platform_post_id' => null];
                }
                $data['attached_media'] = json_encode([['media_fbid' => $mediaData['media_id']]]);
                $response = Http::post($this->getApiBaseUrl() . $endpoint, $data);
            }
        } elseif (($postType === 'video' || $postType === 'reel') && !$media) {
            return ['success' => false, 'error' => 'Media file is required for video and reel posts', 'platform_post_id' => null];
        } elseif (($postType === 'video' || $postType === 'reel') && $media) {
            $response = $this->makeVideoRequest($endpoint, $media, $data);
        } else {
            $response = Http::post($this->getApiBaseUrl() . $endpoint, $data);
        }

        return $this->handleResponse($response);
    }

    protected function makeVideoRequest(string $endpoint, UploadedFile $media, array $data): \Illuminate\Http\Client\Response
    {
        return Http::attach('source', file_get_contents($media->getPathname()), $media->getClientOriginalName())
            ->post($this->getApiBaseUrl() . $endpoint, $data);
    }

    protected function handleResponse($response): array
    {
        $responseData = $response->json();
        if ($response->successful()) {
            return ['success' => true, 'platform_post_id' => $responseData['id'] ?? null, 'response' => $responseData];
        }
        $errorMessage = $responseData['error']['message'] ?? 'Unknown error';
        return ['success' => false, 'error' => $errorMessage, 'platform_post_id' => null];
    }

    public function postToGroup(SocialMediaAccount $account, string $groupId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        $endpoint = "{$groupId}/feed";
        $data = ['message' => $content, 'access_token' => $account->access_token];

        if ($media) {
            $mediaData = $this->uploadMedia($account, $media);
            if (!$mediaData['success']) {
                return ['success' => false, 'error' => 'Failed to upload media: ' . $mediaData['error'], 'platform_post_id' => null];
            }
            $data['attached_media[0]'] = ['media_fbid' => $mediaData['media_id']];
        }

        $response = Http::post($this->getApiBaseUrl() . $endpoint, $data);
        return $this->handleResponse($response);
    }

    protected function postToProfile(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        $endpoint = 'me/feed';
        $data = ['message' => $content, 'access_token' => $account->access_token];

        if ($media) {
            $mediaData = $this->uploadMedia($account, $media);
            if (!$mediaData['success']) {
                return ['success' => false, 'error' => 'Failed to upload media: ' . $mediaData['error'], 'platform_post_id' => null];
            }
            $data['attached_media[0]'] = ['media_fbid' => $mediaData['media_id']];
        }

        $response = Http::post($this->getApiBaseUrl() . $endpoint, $data);
        return $this->handleResponse($response);
    }

    protected function uploadMedia(SocialMediaAccount $account, UploadedFile $media, ?string $pageId = null): array
    {
        try {
            $mimeType = $media->getMimeType();
            
            if ($pageId) {
                $endpoint = str_starts_with($mimeType, 'video/') ? "{$pageId}/videos" : "{$pageId}/photos";
                $accessToken = $this->getPageAccessToken($account, $pageId);
            } else {
                $endpoint = str_starts_with($mimeType, 'video/') ? 'me/videos' : 'me/photos';
                $accessToken = $account->access_token;
            }

            $uploadResponse = Http::attach('source', file_get_contents($media->getPathname()), $media->getClientOriginalName())
                ->post($this->getApiBaseUrl() . $endpoint, ['access_token' => $accessToken, 'published' => false]);

            $uploadData = $uploadResponse->json();

            if ($uploadResponse->successful()) {
                return ['success' => true, 'media_id' => $uploadData['id']];
            }
            
            $errorMessage = $uploadData['error']['message'] ?? 'Media upload failed';
            return ['success' => false, 'error' => $errorMessage];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getAccountInfo(SocialMediaAccount $account): array
    {
        $response = Http::get($this->getApiBaseUrl() . 'me', ['access_token' => $account->access_token, 'fields' => 'id,name,email']);
        return $response->successful() 
            ? ['success' => true, 'data' => $response->json()]
            : ['success' => false, 'error' => $response->json()['error']['message'] ?? 'Failed to get account info'];
    }

    public function getPages(SocialMediaAccount $account): array
    {
        $response = Http::get($this->getApiBaseUrl() . 'me/accounts', ['access_token' => $account->access_token, 'fields' => 'id,name,access_token,category']);
        if ($response->successful()) {
            $data = $response->json();
            return ['success' => true, 'pages' => $data['data'] ?? []];
        }
        return ['success' => false, 'error' => $response->json()['error']['message'] ?? 'Failed to get pages'];
    }

    public function getGroups(SocialMediaAccount $account): array
    {
        $response = Http::get($this->getApiBaseUrl() . 'me/groups', ['access_token' => $account->access_token, 'fields' => 'id,name,privacy,member_count']);
        if ($response->successful()) {
            $data = $response->json();
            return ['success' => true, 'groups' => $data['data'] ?? []];
        }
        return ['success' => false, 'error' => $response->json()['error']['message'] ?? 'Failed to get groups'];
    }

    public function validateAccount(SocialMediaAccount $account): bool
    {
        return $this->validateAccountWithError($account)['valid'];
    }

    public function validateAccountWithError(SocialMediaAccount $account): array
    {
        $response = Http::get($this->getApiBaseUrl() . 'me', ['access_token' => $account->access_token]);
        if ($response->successful()) {
            return ['valid' => true, 'error' => null];
        }
        $errorData = $response->json();
        $errorMessage = $errorData['error']['message'] ?? 'Invalid access token or account credentials';
        return ['valid' => false, 'error' => $errorMessage];
    }

    public function getPlatformName(): string
    {
        return 'facebook';
    }

    public function getSupportedMediaTypes(): array
    {
        return ['image' => ['jpg', 'jpeg', 'png', 'gif'], 'video' => ['mp4', 'mov']];
    }

    public function getContentLimits(): array
    {
        return ['post' => 63206, 'image_caption' => 2200, 'video_description' => 2200];
    }

    public function getPostInsights(SocialMediaAccount $account, string $platformPostId, ?string $pageId = null, ?string $groupId = null): array
    {
        try {
            $accessToken = $pageId ? $this->getPageAccessToken($account, $pageId) : $account->access_token;
            return $this->getPostMetrics($account, $platformPostId, $pageId, $groupId);
        } catch (Exception $e) {
            return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
        }
    }

    protected function getPostMetrics(SocialMediaAccount $account, string $platformPostId, ?string $pageId = null, ?string $groupId = null): array
    {
        try {
            $accessToken = $pageId ? $this->getPageAccessToken($account, $pageId) : $account->access_token;
            
            if (empty($accessToken)) {
                return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
            }
            
            $fields = 'likes.summary(true),comments.summary(true),shares';
            $url = $this->getApiBaseUrl() . $platformPostId;
            $response = Http::get($url, ['access_token' => $accessToken, 'fields' => $fields]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $this->parseEngagementMetrics($data);
            }
            
            if ($response->status() === 400 && strpos($response->body(), 'nonexisting field') !== false) {
                return $this->getPageInsightsMetrics($account, $platformPostId, $accessToken);
            }
        } catch (Exception $e) {}
        
        return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
    }

    protected function parseEngagementMetrics(array $data): array
    {
        $likes = isset($data['likes']['summary']['total_count']) ? $data['likes']['summary']['total_count'] : (is_numeric($data['likes'] ?? null) ? $data['likes'] : 0);
        $comments = isset($data['comments']['summary']['total_count']) ? $data['comments']['summary']['total_count'] : (is_numeric($data['comments'] ?? null) ? $data['comments'] : 0);
        $shares = isset($data['shares']['count']) ? $data['shares']['count'] : 0;

        return ['likes' => $likes, 'comments' => $comments, 'shares' => $shares, 'reach' => 0, 'views' => 0];
    }

    public function updatePostEngagement(SocialMediaAccount $account, string $platformPostId, ?string $pageId = null, ?string $groupId = null): array
    {
        $metrics = $this->getPostInsights($account, $platformPostId, $pageId, $groupId);
        
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
    
    protected function getPageInsightsMetrics(SocialMediaAccount $account, string $platformPostId, string $accessToken): array
    {
        try {
            $pageId = null;
            $postId = $platformPostId;
            
            $settings = $account->settings;
            if ($settings && isset($settings['page_id'])) {
                $pageId = $settings['page_id'];
            }
            
            if (!$pageId && strpos($platformPostId, '_') !== false) {
                $parts = explode('_', $platformPostId);
                $pageId = $parts[0];
                $postId = $parts[1];
            }
            
            if (!$pageId) {
                $meResponse = Http::get($this->getApiBaseUrl() . 'me/accounts', ['access_token' => $accessToken, 'fields' => 'id,name']);
                if ($meResponse->successful()) {
                    $accountsData = $meResponse->json();
                    if (isset($accountsData['data']) && count($accountsData['data']) > 0) {
                        $pageId = $accountsData['data'][0]['id'];
                    }
                }
            }
            
            if (!$pageId) {
                return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
            }
            
            $pageAccessToken = $this->getPageAccessToken($account, $pageId);
            $tokenToUse = $pageAccessToken ?: $accessToken;
            $postUrl = $this->getApiBaseUrl() . $pageId . '_' . $postId;
            
            $postResponse = Http::get($postUrl, ['access_token' => $tokenToUse, 'fields' => 'likes.summary(true),comments.summary(true),shares']);
            
            if ($postResponse->successful()) {
                $postData = $postResponse->json();
                return $this->parseEngagementMetrics($postData);
            }
            
            $insightsUrl = $this->getApiBaseUrl() . $pageId . '/insights';
            $insightsResponse = Http::get($insightsUrl, ['access_token' => $tokenToUse, 'metric' => 'page_posts_impressions,page_posts_engaged_users']);
            
            if ($insightsResponse->successful()) {
                return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
            }
        } catch (Exception $e) {}
        
        return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
    }
    
    protected function parsePageInsights(array $insightsData): array
    {
        $metrics = ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
        
        if (!isset($insightsData['data']) || !is_array($insightsData['data'])) {
            return $metrics;
        }
        
        foreach ($insightsData['data'] as $insight) {
            $name = $insight['name'] ?? '';
            $value = $insight['values'][0]['value'] ?? 0;
            
            switch ($name) {
                case 'post_reactions':
                    $metrics['likes'] = is_array($value) ? array_sum($value) : (is_numeric($value) ? $value : 0);
                    break;
                case 'post_comments':
                    $metrics['comments'] = is_numeric($value) ? $value : 0;
                    break;
                case 'post_shares':
                    $metrics['shares'] = is_numeric($value) ? $value : 0;
                    break;
                case 'post_impressions':
                    $metrics['reach'] = is_numeric($value) ? $value : 0;
                    break;
                case 'post_impressions_unique':
                    if ($metrics['reach'] === 0) {
                        $metrics['reach'] = is_numeric($value) ? $value : 0;
                    }
                    break;
                case 'post_engaged_users':
                    $metrics['views'] = is_numeric($value) ? $value : 0;
                    break;
            }
        }
        
        return $metrics;
    }
}