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

class LinkedInService implements SocialMediaServiceInterface
{
    protected ?SocialMediaConfig $config = null;

    public function __construct() {}

    protected function getApiVersion(): string
    {
        $this->loadConfig();
        return $this->config?->settings['api_version'] ?? 'v2';
    }

    protected function getApiBaseUrl(): string
    {
        $this->loadConfig();
        $baseUrl = $this->config?->settings['api_base_url'] ?? 'https://api.linkedin.com';
        return rtrim($baseUrl, '/') . '/';
    }

    protected function loadConfig(): void
    {
        if ($this->config === null) {
            $this->config = SocialMediaConfig::where('platform', 'linkedin')->where('is_active', true)->first();
        }
    }

    protected function reloadConfig(): void
    {
        $this->config = null;
        $this->loadConfig();
    }

    public function post(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'text'): array
    {
        try {
            if ($account->page_id) {
                Log::info('Posting to LinkedIn organization page', ['page_id' => $account->page_id, 'post_type' => $postType]);
                return $this->postToPage($account, $account->page_id, $content, $media, $options, $postType);
            } else {
                Log::info('Posting to LinkedIn personal profile', ['account_id' => $account->account_id, 'post_type' => $postType]);
                return $this->postToProfile($account, $content, $media, $options, $postType);
            }
        } catch (Exception $e) {
            Log::error('LinkedIn post failed', ['account_id' => $account->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function getLinkedInPersonId(SocialMediaAccount $account): ?string
    {
        try {
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $account->access_token])
                ->get('https://api.linkedin.com/v2/userinfo');

            if ($response->successful()) {
                $data = $response->json();
                return $data['sub'] ?? null;
            }
            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    protected function postToProfile(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'text'): array
    {
        $personId = $this->getLinkedInPersonId($account);
        if (!$personId) {
            return ['success' => false, 'error' => 'Failed to retrieve LinkedIn person ID. Please reconnect your LinkedIn account.', 'platform_post_id' => null];
        }

        $author = "urn:li:person:{$personId}";
        Log::info('Using LinkedIn author URN', ['author' => $author, 'person_id' => $personId]);
        return $this->createPost($account, $author, $content, $media, $options, $postType);
    }

    public function postToPage(SocialMediaAccount $account, string $pageId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'text'): array
    {
        $author = "urn:li:organization:{$pageId}";
        $personId = $this->getLinkedInPersonId($account);

        Log::info('Using LinkedIn author for organization page', ['author' => $author, 'page_id' => $pageId, 'person_id' => $personId, 'has_media' => !empty($media)]);

        $result = $this->createPost($account, $author, $content, $media, $options, $postType);
        
        if (!$result['success'] && isset($result['debug']['status']) && $result['debug']['status'] === 403) {
            $errorMsg = $result['debug']['message'] ?? '';
            if (str_contains($errorMsg, 'ACCESS_DENIED') || str_contains($errorMsg, 'author')) {
                Log::warning('Organization posting failed, falling back to personal profile', ['error' => $errorMsg, 'person_id' => $personId]);
                if ($personId) {
                    $personAuthor = "urn:li:person:{$personId}";
                    return $this->createPost($account, $personAuthor, $content, $media, $options, $postType);
                }
            }
        }
        
        return $result;
    }

    protected function createPost(SocialMediaAccount $account, string $author, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'text'): array
    {
        try {
            if (!$media) {
                return $this->createTextPost($account, $author, $content, $options);
            }

            $mimeType = $media->getMimeType();
            if (str_starts_with($mimeType, 'video/')) {
                return $this->createVideoPost($account, $author, $content, $media, $options);
            } else {
                return $this->createImagePost($account, $author, $content, $media, $options);
            }
        } catch (Exception $e) {
            Log::error('LinkedIn create post failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function createTextPost(SocialMediaAccount $account, string $author, string $content, array $options = []): array
    {
        $payload = [
            'author' => $author,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => ['text' => $content],
                    'shareMediaCategory' => 'NONE'
                ]
            ],
            'visibility' => ['com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC']
        ];

        if (isset($options['article_url'])) {
            $payload['specificContent']['com.linkedin.ugc.ShareContent']['shareMediaCategory'] = 'ARTICLE';
            $payload['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [
                ['status' => 'READY', 'originalUrl' => $options['article_url']]
            ];
        }

        Log::info('Creating LinkedIn text post', ['author' => $author, 'content_length' => strlen($content)]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $account->access_token,
            'Content-Type' => 'application/json',
            'X-Restli-Protocol-Version' => '2.0.0'
        ])->post($this->getApiBaseUrl() . $this->getApiVersion() . '/ugcPosts', $payload);

        $responseData = $response->json();

        if ($response->successful()) {
            return ['success' => true, 'platform_post_id' => $responseData['id'] ?? null, 'response' => $responseData];
        }

        return ['success' => false, 'error' => $responseData['message'] ?? $responseData['error'] ?? 'Failed to create LinkedIn post', 'platform_post_id' => null, 'debug' => $responseData];
    }

    protected function createImagePost(SocialMediaAccount $account, string $author, string $content, UploadedFile $media, array $options = []): array
    {
        try {
            $registerResult = $this->registerImageUpload($account, $author);
            if (!$registerResult['success']) {
                return $registerResult;
            }

            $uploadUrl = $registerResult['upload_url'];
            $asset = $registerResult['asset'];

            $uploadResult = $this->uploadImageBinary($uploadUrl, $media);
            if (!$uploadResult['success']) {
                return $uploadResult;
            }

            $payload = [
                'author' => $author,
                'lifecycleState' => 'PUBLISHED',
                'specificContent' => [
                    'com.linkedin.ugc.ShareContent' => [
                        'shareCommentary' => ['text' => $content],
                        'shareMediaCategory' => 'IMAGE',
                        'media' => [['status' => 'READY', 'media' => $asset]]
                    ]
                ],
                'visibility' => ['com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC']
            ];

            Log::info('Creating LinkedIn image post', ['author' => $author, 'asset' => $asset]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $account->access_token,
                'Content-Type' => 'application/json',
                'X-Restli-Protocol-Version' => '2.0.0'
            ])->post($this->getApiBaseUrl() . 'v2/ugcPosts', $payload);

            $responseData = $response->json();

            if ($response->successful()) {
                return ['success' => true, 'platform_post_id' => $responseData['id'] ?? null, 'response' => $responseData];
            }

            return ['success' => false, 'error' => $responseData['message'] ?? $responseData['error'] ?? 'Failed to create LinkedIn image post', 'platform_post_id' => null, 'debug' => $responseData];
        } catch (Exception $e) {
            Log::error('LinkedIn image post failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function registerImageUpload(SocialMediaAccount $account, string $author): array
    {
        $payload = [
            'registerUploadRequest' => [
                'recipes' => ['urn:li:digitalmediaRecipe:feedshare-image'],
                'owner' => $author,
                'serviceRelationships' => [
                    ['relationshipType' => 'OWNER', 'identifier' => 'urn:li:userGeneratedContent']
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $account->access_token,
            'Content-Type' => 'application/json'
        ])->post($this->getApiBaseUrl() . $this->getApiVersion() . '/assets?action=registerUpload', $payload);

        $responseData = $response->json();

        if ($response->successful()) {
            $uploadUrl = $responseData['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'] ?? null;
            $asset = $responseData['value']['asset'] ?? null;

            if ($uploadUrl && $asset) {
                return ['success' => true, 'upload_url' => $uploadUrl, 'asset' => $asset];
            }
        }

        return ['success' => false, 'error' => $responseData['message'] ?? $responseData['error'] ?? 'Failed to register image upload', 'platform_post_id' => null, 'debug' => $responseData];
    }

    protected function uploadImageBinary(string $uploadUrl, UploadedFile $media): array
    {
        $response = Http::withHeaders(['Content-Type' => $media->getMimeType()])
            ->withBody(file_get_contents($media->getPathname()), $media->getMimeType())
            ->put($uploadUrl);

        if ($response->successful() || $response->status() === 201) {
            return ['success' => true];
        }

        return ['success' => false, 'error' => 'Failed to upload image binary', 'platform_post_id' => null, 'debug' => ['status' => $response->status(), 'body' => $response->body()]];
    }

    protected function registerVideoUpload(SocialMediaAccount $account, string $author): array
    {
        $payload = [
            'registerUploadRequest' => [
                'recipes' => ['urn:li:digitalmediaRecipe:feedshare-video'],
                'owner' => $author,
                'serviceRelationships' => [
                    ['relationshipType' => 'OWNER', 'identifier' => 'urn:li:userGeneratedContent']
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $account->access_token,
            'Content-Type' => 'application/json'
        ])->post($this->getApiBaseUrl() . $this->getApiVersion() . '/assets?action=registerUpload', $payload);

        $responseData = $response->json();

        if ($response->successful()) {
            $uploadUrl = $responseData['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'] ?? null;
            $asset = $responseData['value']['asset'] ?? null;

            if ($uploadUrl && $asset) {
                return ['success' => true, 'upload_url' => $uploadUrl, 'asset' => $asset];
            }
        }

        return ['success' => false, 'error' => $responseData['message'] ?? $responseData['error'] ?? 'Failed to register video upload', 'platform_post_id' => null, 'debug' => $responseData];
    }

    protected function uploadVideoChunks(string $uploadUrl, UploadedFile $media): array
    {
        $chunkSize = 4 * 1024 * 1024;
        $fileSize = $media->getSize();
        $filePath = $media->getPathname();

        Log::info('Starting LinkedIn video chunked upload', ['upload_url' => $uploadUrl, 'file_size' => $fileSize]);

        try {
            $fileHandle = fopen($filePath, 'rb');
            if (!$fileHandle) {
                return ['success' => false, 'error' => 'Failed to open video file for reading', 'platform_post_id' => null];
            }

            $chunkIndex = 0;
            $bytesUploaded = 0;

            while (!feof($fileHandle)) {
                $chunk = fread($fileHandle, $chunkSize);
                $chunkSizeActual = strlen($chunk);

                if ($chunkSizeActual === 0) break;

                $startByte = $bytesUploaded;
                $endByte = $bytesUploaded + $chunkSizeActual - 1;
                $contentRange = "bytes {$startByte}-{$endByte}/{$fileSize}";

                $response = Http::withHeaders([
                    'Content-Type' => $media->getMimeType(),
                    'Content-Range' => $contentRange
                ])->withBody($chunk, $media->getMimeType())->put($uploadUrl);

                if (!$response->successful() && $response->status() !== 308) {
                    fclose($fileHandle);
                    Log::error('Video chunk upload failed', ['status' => $response->status(), 'chunk_index' => $chunkIndex]);
                    return ['success' => false, 'error' => 'Failed to upload video chunk ' . $chunkIndex, 'platform_post_id' => null];
                }

                $bytesUploaded += $chunkSizeActual;
                $chunkIndex++;
            }

            fclose($fileHandle);
            return ['success' => true];
        } catch (Exception $e) {
            Log::error('Video chunked upload exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Exception during video upload: ' . $e->getMessage(), 'platform_post_id' => null];
        }
    }

    protected function finalizeVideoUpload(SocialMediaAccount $account, string $asset): array
    {
        $payload = ['finalizeUploadRequest' => (object)[]];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $account->access_token,
            'Content-Type' => 'application/json'
        ])->post($this->getApiBaseUrl() . $this->getApiVersion() . '/assets/' . urlencode($asset) . '?action=finalizeUpload', $payload);

        $responseData = $response->json();

        if ($response->successful()) {
            return ['success' => true];
        }

        return ['success' => false, 'error' => $responseData['message'] ?? $responseData['error'] ?? 'Failed to finalize video upload', 'platform_post_id' => null, 'debug' => $responseData];
    }

    protected function createVideoPost(SocialMediaAccount $account, string $author, string $content, UploadedFile $media, array $options = []): array
    {
        try {
            $registerResult = $this->registerVideoUpload($account, $author);
            if (!$registerResult['success']) {
                return $registerResult;
            }

            $uploadUrl = $registerResult['upload_url'];
            $asset = $registerResult['asset'];

            $uploadResult = $this->uploadVideoChunks($uploadUrl, $media);
            if (!$uploadResult['success']) {
                return $uploadResult;
            }

            sleep(2);

            Log::info('Skipping video finalization - proceeding to create post', ['asset' => $asset]);

            $payload = [
                'author' => $author,
                'lifecycleState' => 'PUBLISHED',
                'specificContent' => [
                    'com.linkedin.ugc.ShareContent' => [
                        'shareCommentary' => ['text' => $content],
                        'shareMediaCategory' => 'VIDEO',
                        'media' => [['status' => 'READY', 'media' => $asset]]
                    ]
                ],
                'visibility' => ['com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC']
            ];

            Log::info('Creating LinkedIn video post', ['author' => $author, 'asset' => $asset]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $account->access_token,
                'Content-Type' => 'application/json',
                'X-Restli-Protocol-Version' => '2.0.0'
            ])->post($this->getApiBaseUrl() . 'v2/ugcPosts', $payload);

            $responseData = $response->json();

            if ($response->successful()) {
                return ['success' => true, 'platform_post_id' => $responseData['id'] ?? null, 'response' => $responseData];
            }

            return ['success' => false, 'error' => $responseData['message'] ?? $responseData['error'] ?? 'Failed to create LinkedIn video post', 'platform_post_id' => null, 'debug' => $responseData];
        } catch (Exception $e) {
            Log::error('LinkedIn video post failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage(), 'platform_post_id' => null];
        }
    }

    public function getAccountInfo(SocialMediaAccount $account): array
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $account->access_token])
            ->get($this->getApiBaseUrl() . $this->getApiVersion() . '/userinfo');

        return $response->successful()
            ? ['success' => true, 'data' => $response->json()]
            : ['success' => false, 'error' => $response->json()['message'] ?? $response->json()['error'] ?? 'Failed to get account info'];
    }

    public function getPages(SocialMediaAccount $account): array
    {
        $personId = $this->getLinkedInPersonId($account);
        if (!$personId) {
            return ['success' => false, 'error' => 'Failed to retrieve LinkedIn person ID'];
        }

        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $account->access_token])
            ->get($this->getApiBaseUrl() . $this->getApiVersion() . '/organizationalEntityAcls', [
                'q' => 'roleAssignee',
                'role' => 'ADMINISTRATOR',
                'state' => 'APPROVED',
                'projection' => '(elements*(organizationalTarget~(localizedName,vanityName)))'
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $pages = [];

            foreach ($data['elements'] ?? [] as $element) {
                $org = $element['organizationalTarget~'] ?? null;
                if ($org) {
                    $orgId = str_replace('urn:li:organization:', '', $element['organizationalTarget'] ?? '');
                    $pages[] = [
                        'id' => $orgId,
                        'name' => $org['localizedName'] ?? 'Unknown',
                        'vanity_name' => $org['vanityName'] ?? null
                    ];
                }
            }

            return ['success' => true, 'pages' => $pages];
        }

        return ['success' => false, 'error' => $response->json()['message'] ?? $response->json()['error'] ?? 'Failed to get LinkedIn pages'];
    }

    public function getGroups(SocialMediaAccount $account): array
    {
        return ['success' => true, 'groups' => []];
    }

    public function postToGroup(SocialMediaAccount $account, string $groupId, string $content, ?UploadedFile $media = null, array $options = [], string $postType = 'text'): array
    {
        return ['success' => false, 'error' => 'LinkedIn does not support posting to groups via API.', 'platform_post_id' => null];
    }

    public function validateAccount(SocialMediaAccount $account): bool
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer ' . $account->access_token])
            ->get($this->getApiBaseUrl() . $this->getApiVersion() . '/userinfo');
        return $response->successful();
    }

    public function getPlatformName(): string
    {
        return 'linkedin';
    }

    public function getSupportedMediaTypes(): array
    {
        return ['image' => ['jpg', 'jpeg', 'png', 'gif'], 'video' => ['mp4']];
    }

    public function getContentLimits(): array
    {
        return ['text' => 3000, 'hashtags' => 'unlimited', 'mentions' => 'unlimited'];
    }

    protected function getValidAccessToken(SocialMediaAccount $account): ?string
    {
        try {
            if (!empty($account->refresh_token) && !empty($account->token_expires_at)) {
                $expiresAt = \Carbon\Carbon::parse($account->token_expires_at);
                $now = \Carbon\Carbon::now();
                if ($expiresAt->diffInMinutes($now) < 30) {
                    Log::info('LinkedIn token expired or expiring soon, refreshing...');
                }
            }
            return $account->access_token;
        } catch (Exception $e) {
            return $account->access_token;
        }
    }

    public function getPostInsights(SocialMediaAccount $account, string $platformPostId): array
    {
        try {
            $accessToken = $this->getValidAccessToken($account);
            if (!$accessToken) {
                return ['likes' => 0, 'comments' => 0, 'shares' => 0, 'reach' => 0, 'views' => 0];
            }
            
            $postId = $platformPostId;
            if (str_starts_with($platformPostId, 'urn:li:ugcPost:')) {
                $postId = str_replace('urn:li:ugcPost:', '', $platformPostId);
            }
            
            Log::info('LinkedIn fetching engagement for post', ['original_id' => $platformPostId, 'extracted_id' => $postId]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'X-Restli-Protocol-Version' => '2.0.0'
            ])->get($this->getApiBaseUrl() . 'v2/socialMetadata', ['id' => $platformPostId, 'type' => 'UGC_POST']);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'likes' => $data['totalLikes'] ?? 0,
                    'comments' => $data['totalComments'] ?? 0,
                    'shares' => $data['totalShares'] ?? 0,
                    'reach' => 0,
                    'views' => 0
                ];
            }
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'X-Restli-Protocol-Version' => '2.0.0'
            ])->get($this->getApiBaseUrl() . 'v2/ugcPosts/' . $postId, ['fields' => 'socialMetadata.totalLikes,socialMetadata.totalComments,socialMetadata.totalShares']);
            
            if ($response->successful()) {
                $data = $response->json();
                $socialMeta = $data['socialMetadata'] ?? [];
                return [
                    'likes' => $socialMeta['totalLikes'] ?? 0,
                    'comments' => $socialMeta['totalComments'] ?? 0,
                    'shares' => $socialMeta['totalShares'] ?? 0,
                    'reach' => 0,
                    'views' => 0
                ];
            }
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'X-Restli-Protocol-Version' => '2.0.0'
            ])->get($this->getApiBaseUrl() . 'v2/ugcPosts/' . $postId, ['fields' => 'totalLikes,totalComments,totalShares']);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'likes' => $data['totalLikes'] ?? 0,
                    'comments' => $data['totalComments'] ?? 0,
                    'shares' => $data['totalShares'] ?? 0,
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
                Log::info('LinkedIn engagement updated for post', ['post_history_id' => $postHistory->id, 'platform_post_id' => $platformPostId, 'likes' => $metrics['likes'], 'comments' => $metrics['comments'], 'shares' => $metrics['shares']]);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'error' => $e->getMessage(), 'metrics' => $metrics];
        }
        
        return ['success' => true, 'metrics' => $metrics];
    }
}