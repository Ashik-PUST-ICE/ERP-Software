<?php

namespace App\Http\Services\SocialMedia;

use App\Models\SocialMediaAccount;
use App\Models\SocialMediaConfig;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwitterService implements SocialMediaServiceInterface
{
    protected string $apiBaseUrl = 'https://api.twitter.com/2/';

    public function post(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        try {
            Log::info('Twitter post attempted', ['account_id' => $account->id, 'content_length' => strlen($content), 'has_media' => !is_null($media)]);

            $config = SocialMediaConfig::getPlatformConfig('twitter');

            if (!$config) {
                return ['success' => false, 'error' => 'Twitter platform not configured. Please add Twitter configuration in Social Media Config.', 'platform_post_id' => null];
            }

            $consumerKey = $config->app_id;
            $consumerSecret = $config->app_secret;
            $accessToken = $account->access_token;
            $accessTokenSecret = $account->permissions['access_token_secret'] ?? null;

            if (!$consumerKey || !$consumerSecret || !$accessToken || !$accessTokenSecret) {
                $missing = [];
                if (!$consumerKey) $missing[] = 'App ID';
                if (!$consumerSecret) $missing[] = 'App Secret';
                if (!$accessToken) $missing[] = 'Access Token';
                if (!$accessTokenSecret) $missing[] = 'Access Token Secret';

                return ['success' => false, 'error' => 'Twitter OAuth credentials incomplete. Missing: ' . implode(', ', $missing) . '. Please check Social Media Config and account setup.', 'platform_post_id' => null];
            }

            $oauthHeaders = $this->generateOAuthHeaders('POST', $this->apiBaseUrl . 'tweets', $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

            $headers = ['Authorization' => $oauthHeaders, 'Content-Type' => 'application/json'];

            $postData = ['text' => $content];

            if ($media) {
                $mediaId = $this->uploadMedia($account, $media, $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
                if ($mediaId) {
                    $postData['media'] = ['media_ids' => [$mediaId]];
                }
            }

            $response = Http::withHeaders($headers)->timeout(30)->post($this->apiBaseUrl . 'tweets', $postData);

            if ($response->successful()) {
                $tweetData = $response->json();
                Log::info('Twitter post successful', ['account_id' => $account->id, 'tweet_id' => $tweetData['data']['id'] ?? null]);
                return ['success' => true, 'platform_post_id' => $tweetData['data']['id'] ?? null, 'response' => $tweetData];
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['detail'] ?? $errorData['title'] ?? 'Unknown Twitter API error';
                Log::error('Twitter API error', ['account_id' => $account->id, 'status' => $response->status(), 'error' => $errorMessage, 'response' => $errorData]);
                return ['success' => false, 'error' => 'Twitter API Error: ' . $errorMessage, 'platform_post_id' => null];
            }
        } catch (Exception $e) {
            Log::error('Twitter post failed', ['account_id' => $account->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function generateOAuthHeaders(string $method, string $url, string $consumerKey, string $consumerSecret, string $accessToken, string $accessTokenSecret): string
    {
        $oauthParams = [
            'oauth_consumer_key' => $consumerKey,
            'oauth_nonce' => md5(uniqid(rand(), true)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => $accessToken,
            'oauth_version' => '1.0'
        ];

        $signatureBaseString = strtoupper($method) . '&' . rawurlencode($url) . '&' . rawurlencode(http_build_query($oauthParams, '', '&', PHP_QUERY_RFC3986));
        $signingKey = rawurlencode($consumerSecret) . '&' . rawurlencode($accessTokenSecret);
        $oauthParams['oauth_signature'] = base64_encode(hash_hmac('sha1', $signatureBaseString, $signingKey, true));

        $header = 'OAuth ';
        $headerParts = [];
        foreach ($oauthParams as $key => $value) {
            $headerParts[] = rawurlencode($key) . '="' . rawurlencode($value) . '"';
        }
        $header .= implode(', ', $headerParts);

        return $header;
    }

    protected function uploadMedia(SocialMediaAccount $account, UploadedFile $media, string $consumerKey, string $consumerSecret, string $accessToken, string $accessTokenSecret): ?string
    {
        try {
            $mediaContent = base64_encode(file_get_contents($media->getRealPath()));
            $uploadUrl = 'https://upload.twitter.com/1.1/media/upload.json';

            $oauthHeaders = $this->generateOAuthHeaders('POST', $uploadUrl, $consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

            $uploadResponse = Http::withHeaders([
                'Authorization' => $oauthHeaders,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post($uploadUrl, ['media_data' => $mediaContent]);

            if ($uploadResponse->successful()) {
                $uploadData = $uploadResponse->json();
                return $uploadData['media_id_string'] ?? null;
            }

            Log::warning('Twitter media upload failed', ['account_id' => $account->id, 'response' => $uploadResponse->body()]);
            return null;
        } catch (Exception $e) {
            Log::error('Twitter media upload error', ['account_id' => $account->id, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function postToPage(SocialMediaAccount $account, string $pageId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        return $this->post($account, $content, $media, $options);
    }

    public function postToGroup(SocialMediaAccount $account, string $groupId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        return $this->post($account, $content, $media, $options);
    }

    public function getAccountInfo(SocialMediaAccount $account): array
    {
        try {
            return [
                'success' => true,
                'data' => [
                    'id' => $account->account_id,
                    'username' => $account->username,
                    'name' => $account->username,
                    'followers_count' => 0,
                    'verified' => false
                ]
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
        return !empty($account->access_token) && $account->is_active;
    }

    public function getPlatformName(): string
    {
        return 'twitter';
    }

    public function getSupportedMediaTypes(): array
    {
        return ['image' => ['jpg', 'jpeg', 'png', 'gif'], 'video' => ['mp4', 'mov', 'avi']];
    }

    public function getContentLimits(): array
    {
        return ['post' => 280, 'image' => 5, 'video' => 512];
    }

    public function getPostInsights(SocialMediaAccount $account, string $platformPostId): array
    {
        try {
            $bearerToken = $this->getBearerToken($account);
            
            if (!$bearerToken) {
                Log::warning('Twitter bearer token not found for engagement metrics');
                return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
            }
            
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $bearerToken])
                ->get('https://api.twitter.com/2/tweets/' . $platformPostId, ['tweet.fields' => 'public_metrics,non_public_metrics']);
            
            if ($response->successful()) {
                $data = $response->json();
                $metrics = $data['data']['public_metrics'] ?? [];
                
                return [
                    'likes' => $metrics['like_count'] ?? 0,
                    'comments' => $metrics['reply_count'] ?? 0,
                    'shares' => $metrics['retweet_count'] ?? 0,
                    'reach' => $metrics['impression_count'] ?? 0,
                    'views' => $metrics['impression_count'] ?? 0
                ];
            }
        } catch (Exception $e) {
            Log::error('Failed to fetch Twitter post insights', ['post_id' => $platformPostId, 'error' => $e->getMessage()]);
        }
        
        return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
    }

    protected function getBearerToken(SocialMediaAccount $account): ?string
    {
        $config = SocialMediaConfig::where('platform', 'twitter')->where('is_active', true)->first();
        
        if ($config && !empty($config->settings['bearer_token'])) {
            return $config->settings['bearer_token'];
        }
        
        return null;
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
                Log::info('Twitter engagement updated for post', ['post_history_id' => $postHistory->id, 'platform_post_id' => $platformPostId, 'likes' => $metrics['likes'], 'comments' => $metrics['comments'], 'shares' => $metrics['shares']]);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'error' => $e->getMessage(), 'metrics' => $metrics];
        }
        
        return ['success' => true, 'metrics' => $metrics];
    }
}