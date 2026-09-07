<?php

namespace App\Http\Services\SocialMedia;

use App\Models\SocialMediaAccount;
use App\Models\SocialMediaConfig;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YouTubeService implements SocialMediaServiceInterface
{
    protected string $apiBaseUrl = 'https://www.googleapis.com/youtube/v3/';
    protected string $uploadUrl = 'https://www.googleapis.com/upload/youtube/v3/videos';
    protected string $oauthUrl = 'https://oauth2.googleapis.com/token';

    public function post(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'video'): array
    {
        try {
            if (!$media) {
                return [
                    'success' => false,
                    'error' => 'Video media is required for YouTube posts',
                    'platform_post_id' => null
                ];
            }

            $mimeType = $media->getMimeType();
            if (!str_starts_with($mimeType, 'video/')) {
                return [
                    'success' => false,
                    'error' => 'Only video files are supported for YouTube posts',
                    'platform_post_id' => null
                ];
            }

            Log::info('YouTube post attempted', [
                'account_id' => $account->id,
                'content_length' => strlen($content),
                'has_media' => !is_null($media),
                'mime_type' => $mimeType
            ]);

            return $this->uploadVideo($account, $content, $media, $options);
        } catch (Exception $e) {
            Log::error('YouTube post failed', [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'platform_post_id' => null
            ];
        }
    }

    protected function uploadVideo(SocialMediaAccount $account, string $description, UploadedFile $media, array $options = []): array
    {
        try {
            $accessToken = $this->getValidAccessToken($account);

            if (!$accessToken) {
                return [
                    'success' => false,
                    'error' => 'YouTube access token not available. Please reconnect your YouTube account.',
                    'platform_post_id' => null
                ];
            }

            $title = $options['title'] ?? substr($description, 0, 100);
            $videoDescription = $description;

            if (($options['post_type'] ?? null) === 'reel' && !stripos($videoDescription, '#Shorts')) {
                $videoDescription .= "\n\n#Shorts";
            }

            $initResponse = $this->initializeUpload($accessToken, $title, $videoDescription, $media, $options);

            if (!$initResponse['success']) {
                return $initResponse;
            }

            $uploadUrl = $initResponse['upload_url'];
            $videoId = $initResponse['video_id'];

            return $this->uploadVideoBinary($accessToken, $uploadUrl, $media, $videoId);
        } catch (Exception $e) {
            Log::error('YouTube video upload failed', [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'platform_post_id' => null
            ];
        }
    }

    protected function initializeUpload(string $accessToken, string $title, string $description, UploadedFile $media, array $options = []): array
    {
        $fileSize = filesize($media->getPathname());

        $snippet = [
            'title' => $title,
            'description' => $description,
        ];

        if (!empty($options['tags'])) {
            $snippet['tags'] = is_array($options['tags']) ? $options['tags'] : explode(',', $options['tags']);
        }

        if (!empty($options['category_id'])) {
            $snippet['categoryId'] = (string) $options['category_id'];
        } else {
            $snippet['categoryId'] = '22';
        }

        $status = [
            'privacyStatus' => $options['privacy_status'] ?? 'public',
            'selfDeclaredMadeForKids' => $options['made_for_kids'] ?? false,
        ];

        Log::info('YouTube initializing upload', [
            'title' => $title,
            'file_size' => $fileSize,
            'privacy' => $status['privacyStatus']
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'X-Upload-Content-Length' => $fileSize,
            'X-Upload-Content-Type' => $media->getMimeType()
        ])->post($this->uploadUrl . '?part=snippet,status&uploadType=resumable', [
            'snippet' => $snippet,
            'status' => $status
        ]);

        Log::debug('YouTube upload initialization request', [
            'url' => $this->uploadUrl . '?part=snippet,status&uploadType=resumable',
            'title' => $title,
            'description_length' => strlen($description),
            'category_id' => $snippet['categoryId'] ?? 'not set',
            'privacy' => $status['privacyStatus'],
            'file_size' => $fileSize,
            'mime_type' => $media->getMimeType()
        ]);

        if ($response->successful()) {
            $uploadUrl = $response->header('Location');

            preg_match('/\/videos\/([^?]+)/', $uploadUrl, $matches);
            $videoId = $matches[1] ?? null;

            Log::info('YouTube upload initialized', [
                'upload_url' => $uploadUrl,
                'video_id' => $videoId
            ]);

            return [
                'success' => true,
                'upload_url' => $uploadUrl,
                'video_id' => $videoId
            ];
        }

        $errorData = $response->json();
        $errorMessage = $errorData['error']['message'] ?? 'Failed to initialize YouTube upload';

        $errorReason = $errorData['error']['errors'][0]['reason'] ?? null;

        if ($errorReason === 'quotaExceeded' || $errorReason === 'dailyLimitExceeded') {
            $errorMessage = 'YouTube API quota exceeded. Please try again tomorrow (quota resets at midnight Pacific Time) or request a quota increase from Google Cloud Console.';
        } elseif ($errorReason === 'uploadLimitExceeded') {
            $errorMessage = 'You have reached your daily YouTube upload limit. This is usually restricted for unverified channels or new accounts. Please verify your YouTube channel with a phone number to increase your limits, or wait 24 hours to try again.';
        } elseif ($errorReason === 'userRateLimitExceeded') {
            $errorMessage = 'Too many requests to YouTube in a short time. Please wait a few minutes and try again.';
        }

        Log::error('YouTube initialization failed', [
            'status' => $response->status(),
            'error' => $errorMessage,
            'error_reason' => $errorReason,
            'response' => $errorData
        ]);

        return [
            'success' => false,
            'error' => $errorMessage,
            'platform_post_id' => null
        ];
    }

    protected function uploadVideoBinary(string $accessToken, string $uploadUrl, UploadedFile $media, ?string $videoId = null): array
    {
        try {
            $fileContent = file_get_contents($media->getPathname());
            $fileSize = strlen($fileContent);
            $mimeType = $media->getMimeType();

            Log::info('YouTube uploading video binary', [
                'video_id' => $videoId ?? 'not yet assigned',
                'file_size' => $fileSize,
                'mime_type' => $mimeType
            ]);

            if ($fileSize <= 5 * 1024 * 1024) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Length' => $fileSize,
                    'Content-Type' => $mimeType
                ])->send('PUT', $uploadUrl, [
                    'body' => $fileContent
                ]);

                if ($response->successful()) {
                    $responseData = $response->json();
                    $finalVideoId = $responseData['id'] ?? $videoId;

                    Log::info('YouTube video uploaded successfully', [
                        'video_id' => $finalVideoId,
                        'url' => 'https://www.youtube.com/watch?v=' . $finalVideoId
                    ]);

                    return [
                        'success' => true,
                        'platform_post_id' => $finalVideoId,
                        'response' => [
                            'id' => $finalVideoId,
                            'url' => 'https://www.youtube.com/watch?v=' . $finalVideoId
                        ]
                    ];
                }
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Length' => $fileSize,
                'Content-Type' => $mimeType
            ])->send('PUT', $uploadUrl, [
                'body' => $fileContent
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $finalVideoId = $responseData['id'] ?? $videoId;

                Log::info('YouTube video uploaded successfully', [
                    'video_id' => $finalVideoId,
                    'url' => 'https://www.youtube.com/watch?v=' . $finalVideoId
                ]);

                return [
                    'success' => true,
                    'platform_post_id' => $finalVideoId,
                    'response' => [
                        'id' => $finalVideoId,
                        'url' => 'https://www.youtube.com/watch?v=' . $finalVideoId
                    ]
                ];
            }

            $errorData = $response->json();
            $errorMessage = $errorData['error']['message'] ?? 'Failed to upload video';

            Log::error('YouTube video upload failed', [
                'video_id' => $videoId,
                'status' => $response->status(),
                'error' => $errorMessage
            ]);

            return [
                'success' => false,
                'error' => $errorMessage,
                'platform_post_id' => null
            ];
        } catch (Exception $e) {
            Log::error('YouTube binary upload error', [
                'video_id' => $videoId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'platform_post_id' => null
            ];
        }
    }

    protected function getValidAccessToken(SocialMediaAccount $account): ?string
    {
        $accessToken = $account->access_token;

        if ($account->token_expires_at && now()->addMinutes(5)->isAfter($account->token_expires_at)) {
            $settings = $account->settings;

            if (is_string($settings)) {
                $settings = json_decode($settings, true);
            }

            $refreshToken = $settings['refresh_token'] ?? null;

            if (!$refreshToken) {
                $config = SocialMediaConfig::getPlatformConfig('youtube');
                $configSettings = $config->settings;
                if (is_string($configSettings)) {
                    $configSettings = json_decode($configSettings, true);
                }
                $refreshToken = $configSettings['refresh_token'] ?? null;
            }

            if ($refreshToken) {
                $newToken = $this->refreshAccessToken($account, $refreshToken);
                if ($newToken) {
                    $accessToken = $newToken;
                    $account->access_token = $newToken;
                    $account->save();
                    return $accessToken;
                }
            }

            Log::warning('YouTube token refresh failed, account needs to reconnect', [
                'account_id' => $account->id,
                'had_access_token' => !empty($account->access_token),
                'reason' => 'refresh_failed_no_valid_token_returned',
            ]);
            return null;
        }

        return $accessToken;
    }

    protected function refreshAccessToken(SocialMediaAccount $account, string $refreshToken): ?string
    {
        try {
            $config = SocialMediaConfig::getPlatformConfig('youtube');
            $clientId = $config->app_id ?? $account->settings['client_id'] ?? null;
            $clientSecret = $config->app_secret ?? $account->settings['client_secret'] ?? null;

            Log::info('YouTube refreshAccessToken attempt', [
                'account_id' => $account->id,
                'has_client_id' => !empty($clientId),
                'has_client_secret' => !empty($clientSecret),
                'has_config' => !empty($config),
            ]);

            $response = Http::post($this->oauthUrl, [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $account->access_token = $data['access_token'];
                if (!empty($data['expires_in'])) {
                    $account->token_expires_at = now()->addSeconds($data['expires_in']);
                }
                if (!empty($data['refresh_token'])) {
                    $settings = $account->settings ?? [];
                    $settings['refresh_token'] = $data['refresh_token'];
                    $account->settings = $settings;
                }
                $account->save();

                Log::info('YouTube token refreshed successfully', [
                    'account_id' => $account->id
                ]);

                return $data['access_token'];
            }

            $errorData = $response->json();
            Log::error('YouTube token refresh failed', [
                'status' => $response->status(),
                'response' => $errorData
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('YouTube token refresh error', [
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    public function postToPage(SocialMediaAccount $account, string $pageId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'video'): array
    {
        return $this->post($account, $content, $media, $options);
    }

    public function getAccountInfo(SocialMediaAccount $account): array
    {
        try {
            $accessToken = $this->getValidAccessToken($account);

            if (!$accessToken) {
                return [
                    'success' => false,
                    'error' => 'Access token not available'
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get($this->apiBaseUrl . 'channels', [
                'part' => 'snippet,statistics,contentDetails',
                'mine' => true
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $channel = $data['items'][0] ?? null;

                if ($channel) {
                    return [
                        'success' => true,
                        'data' => [
                            'id' => $channel['id'],
                            'title' => $channel['snippet']['title'],
                            'description' => $channel['snippet']['description'],
                            'subscriber_count' => $channel['statistics']['subscriberCount'] ?? 0,
                            'video_count' => $channel['statistics']['videoCount'] ?? 0,
                            'view_count' => $channel['statistics']['viewCount'] ?? 0,
                            'thumbnail' => $channel['snippet']['thumbnails']['default']['url'] ?? null
                        ]
                    ];
                }
            }

            return [
                'success' => false,
                'error' => 'Failed to get channel info'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function getPages(SocialMediaAccount $account): array
    {
        try {
            $accountInfo = $this->getAccountInfo($account);

            if ($accountInfo['success']) {
                return [
                    'success' => true,
                    'pages' => [[
                        'id' => $accountInfo['data']['id'],
                        'name' => $accountInfo['data']['title'],
                        'type' => 'channel'
                    ]]
                ];
            }

            return [
                'success' => true,
                'pages' => []
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function postToGroup(SocialMediaAccount $account, string $groupId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'feed'): array
    {
        return [
            'success' => false,
            'error' => 'YouTube does not support groups. Please use postToPage() instead.',
            'platform_post_id' => null
        ];
    }

    public function getGroups(SocialMediaAccount $account): array
    {
        return [
            'success' => false,
            'error' => 'YouTube does not support groups. Use getPages() to get your channels.',
            'groups' => []
        ];
    }

    public function validateAccount(SocialMediaAccount $account): bool
    {
        try {
            $accessToken = $this->getValidAccessToken($account);

            if (!$accessToken) {
                return false;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get('https://www.googleapis.com/oauth2/v2/userinfo');

            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getPlatformName(): string
    {
        return 'youtube';
    }

    public function getSupportedMediaTypes(): array
    {
        return [
            'video' => ['mp4', 'mov', 'avi', 'mkv', 'wmv', 'flv', 'webm']
        ];
    }

    public function getContentLimits(): array
    {
        return [
            'title' => 100,
            'description' => 5000,
            'video_size' => 128,
            'video_duration' => 12,
            'supported_formats' => ['mp4', 'mov', 'avi', 'mkv', 'wmv', 'flv', 'webm']
        ];
    }

    public function getPostInsights(SocialMediaAccount $account, string $platformPostId): array
    {
        try {
            $apiKey = $this->getApiKey($account);
            
            if (!$apiKey) {
                Log::warning('YouTube API key not found for engagement metrics');
                return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
            }
            
            $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                'part' => 'statistics',
                'id' => $platformPostId,
                'key' => $apiKey
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $items = $data['items'] ?? [];
                
                if (!empty($items)) {
                    $stats = $items[0]['statistics'] ?? [];
                    
                    return [
                        'likes' => (int) ($stats['likeCount'] ?? 0),
                        'comments' => (int) ($stats['commentCount'] ?? 0),
                        'shares' => 0,
                        'reach' => 0,
                        'views' => (int) ($stats['viewCount'] ?? 0)
                    ];
                }
            }
            
        } catch (Exception $e) {
            Log::error('Failed to fetch YouTube post insights', [
                'post_id' => $platformPostId,
                'error' => $e->getMessage()
            ]);
        }
        
        return [
            'likes' => 0,
            'comments' => 0,
            'shares' => 0,
            'reach' => 0,
            'views' => 0
        ];
    }

    protected function getApiKey(SocialMediaAccount $account): ?string
    {
        $config = SocialMediaConfig::where('platform', 'youtube')->where('is_active', true)->first();
        
        if ($config && !empty($config->settings['api_key'])) {
            return $config->settings['api_key'];
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
                Log::info('YouTube engagement updated for post', [
                    'post_history_id' => $postHistory->id,
                    'platform_post_id' => $platformPostId,
                    'likes' => $metrics['likes'],
                    'comments' => $metrics['comments'],
                    'shares' => $metrics['shares'],
                ]);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'error' => $e->getMessage(), 'metrics' => $metrics];
        }
        
        return [
            'success' => true,
            'metrics' => $metrics
        ];
    }
}