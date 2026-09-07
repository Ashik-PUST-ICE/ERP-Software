<?php

namespace App\Http\Services\SocialMedia;

use App\Models\SocialMediaAccount;
use App\Models\SocialMediaConfig;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SocialMediaServiceManager
{
    use ResponseTrait;

    protected array $services = [];

    public function registerService(string $platform, SocialMediaServiceInterface $service): void
    {
        $this->services[$platform] = $service;
    }

    public function getService(string $platform): ?SocialMediaServiceInterface
    {
        return $this->services[$platform] ?? null;
    }

    public function getSupportedPlatforms(): array
    {
        return array_keys($this->services);
    }

    public function getPlatformInfo(string $platform): array
    {
        $service = $this->getService($platform);

        if (!$service) {
            return [
                'name'           => $platform,
                'supported'      => false,
                'media_types'    => [],
                'content_limits' => [],
            ];
        }

        return [
            'name'           => $service->getPlatformName(),
            'supported'      => true,
            'media_types'    => $service->getSupportedMediaTypes(),
            'content_limits' => $service->getContentLimits(),
        ];
    }

    public function post(SocialMediaAccount $account, string $content, ?UploadedFile $media = null, array $options = []): array
    {
        $service = $this->getService($account->platform);

        if (!$service) {
            return ['success' => false, 'error' => "Service not available for platform: {$account->platform}", 'platform_post_id' => null];
        }

        return $service->post($account, $content, $media, $options);
    }

    public function postToPage(SocialMediaAccount $account, string $pageId, string $content, ?UploadedFile $media = null, array $options = []): array
    {
        $service = $this->getService($account->platform);

        if (!$service) {
            return ['success' => false, 'error' => "Service not available for platform: {$account->platform}", 'platform_post_id' => null];
        }

        return $service->postToPage($account, $pageId, $content, $media, $options);
    }

    public function postToGroup(SocialMediaAccount $account, string $groupId, string $content, ?UploadedFile $media = null, array $options = []): array
    {
        $service = $this->getService($account->platform);

        if (!$service) {
            return ['success' => false, 'error' => "Service not available for platform: {$account->platform}", 'platform_post_id' => null];
        }

        return $service->postToGroup($account, $groupId, $content, $media, $options);
    }

    public function getAccountInfo(SocialMediaAccount $account): array
    {
        $service = $this->getService($account->platform);

        if (!$service) {
            return ['success' => false, 'error' => "Service not available for platform: {$account->platform}"];
        }

        return $service->getAccountInfo($account);
    }

    public function getPages(SocialMediaAccount $account): array
    {
        $service = $this->getService($account->platform);

        if (!$service) {
            return ['success' => false, 'error' => "Service not available for platform: {$account->platform}"];
        }

        return $service->getPages($account);
    }

    public function getGroups(SocialMediaAccount $account): array
    {
        $service = $this->getService($account->platform);

        if (!$service) {
            return ['success' => false, 'error' => "Service not available for platform: {$account->platform}"];
        }

        return $service->getGroups($account);
    }

    public function validateAccount(SocialMediaAccount $account): bool
    {
        $service = $this->getService($account->platform);

        if (!$service) {
            return false;
        }

        return $service->validateAccount($account);
    }

    public function getConfiguredPlatforms(): array
    {
        return SocialMediaConfig::where('is_active', true)->distinct()->pluck('platform')->toArray();
    }

    public function getIndexViewData(Request $request): array
    {
        $query = SocialMediaAccount::with('user');

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $accounts = $query->withCount('scheduledPosts')->latest()->paginate(20);

        foreach ($accounts as $account) {
            $account->engagement = $account->getEngagementStats();
            \Illuminate\Support\Facades\Log::info('Engagement stats loaded for account', [
                'account_id' => $account->id,
                'platform' => $account->platform,
                'likes' => $account->engagement['likes'],
                'comments' => $account->engagement['comments'],
                'shares' => $account->engagement['shares'],
            ]);
        }

        $tenantId = auth()->user()->tenant_id ?? null;

        return [
            'accounts'  => $accounts,
            'platforms' => $this->getConfiguredPlatforms(),
            'users'     => User::select('id', 'name', 'email')
                ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                ->get(),
        ];
    }

    public function storeAccount(array $data): void
    {
        $configured = $this->getConfiguredPlatforms();

        if (!in_array($data['platform'], $configured)) {
            throw new \Exception('Platform not configured');
        }

        if ($data['platform'] === 'twitter' && !empty($data['bearer_token'])) {
            $data['settings'] = array_merge($data['settings'] ?? [], ['bearer_token' => $data['bearer_token']]);
            unset($data['bearer_token']);
        }

        if ($data['platform'] === 'youtube' && !empty($data['refresh_token'])) {
            $data['settings'] = array_merge($data['settings'] ?? [], ['refresh_token' => $data['refresh_token']]);
            unset($data['refresh_token']);
        }

        $data['is_active'] = true;

        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        if ($data['platform'] === 'facebook' && empty($data['page_id']) && !empty($data['account_id'])) {
            $data['page_id'] = $data['account_id'];
        }

        if (empty($data['username'])) {
            unset($data['username']);
        }

        if (empty($data['tenant_id']) && auth()->check()) {
            $data['tenant_id'] = auth()->user()->tenant_id;
        }

        SocialMediaAccount::create($data);
    }

    public function updateAccount(SocialMediaAccount $account, array $data): void
    {
        if (!$this->getService($data['platform'])) {
            throw new \Exception('Platform not supported');
        }

        if ($data['platform'] === 'twitter' && isset($data['access_token_secret'])) {
            $data['permissions'] = array_merge($data['permissions'] ?? $account->permissions ?? [], [
                'access_token_secret' => $data['access_token_secret'],
            ]);
            unset($data['access_token_secret']);
        }

        if ($data['platform'] === 'youtube' && isset($data['refresh_token'])) {
            $data['settings'] = array_merge($account->settings ?? [], $data['settings'] ?? [], [
                'refresh_token' => $data['refresh_token'],
            ]);
            unset($data['refresh_token']);
        }

        $account->update($data);
    }

    public function deleteAccount(SocialMediaAccount $account): void
    {
        $account->postHistories()->delete();
        $account->scheduledPosts()->delete();
        $account->delete();
    }

    public function toggleAccountStatus(SocialMediaAccount $account): void
    {
        $account->update(['is_active' => !$account->is_active]);
    }

    public function getEditAccountData(SocialMediaAccount $account): array
    {
        $data = [
            'id'                 => $account->id,
            'platform'           => $account->platform,
            'account_id'         => $account->account_id,
            'username'           => $account->username ?? '',
            'email'              => $account->email ?? '',
            'access_token'       => $account->access_token ?? '',
            'is_active'          => $account->is_active,
            'page_id'            => $account->page_id ?? '',
            'group_id'           => $account->group_id ?? '',
        ];

        if ($account->platform === 'twitter') {
            $data['access_token_secret'] = $account->permissions['access_token_secret'] ?? '';
            $data['bearer_token']        = $account->settings['bearer_token'] ?? '';
        }

        if ($account->platform === 'youtube') {
            $data['refresh_token'] = $account->settings['refresh_token'] ?? '';
        }

        return $data;
    }

    public function runAutoConnect(): array
    {
        $accounts  = SocialMediaAccount::where('is_active', true)->get();
        $connected = 0;
        $failed    = 0;

        foreach ($accounts as $account) {
            try {
                $service = $this->getService($account->platform);

                if (!$service) {
                    $failed++;
                    continue;
                }

                if ($service->validateAccount($account)) {
                    $this->refreshAccountInfo($account, $service);
                    $connected++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error('Auto connect failed for account ' . $account->id . ': ' . $e->getMessage());
            }
        }

        return ['connected' => $connected, 'failed' => $failed];
    }

    public function connectAccount(SocialMediaAccount $account): bool
    {
        $service = $this->getService($account->platform);

        if (!$service || !$service->validateAccount($account)) {
            return false;
        }

        $this->refreshAccountInfo($account, $service);

        return true;
    }

    public function getAccountStatistics(SocialMediaAccount $account): array
    {
        return [
            'total_posts'     => $account->postHistories()->count(),
            'successful_posts' => $account->postHistories()->where('success', true)->count(),
            'failed_posts'    => $account->postHistories()->where('success', false)->count(),
            'scheduled_posts' => $account->scheduledPosts()->where('status', 'pending')->count(),
            'recent_posts'    => $account->postHistories()->latest()->take(10)->get(),
        ];
    }

    public function getFacebookConfig(): ?SocialMediaConfig
    {
        return SocialMediaConfig::where('platform', 'facebook')->where('is_active', true)->first();
    }

    public function processFacebookCallback($socialiteUser): void
    {
        $accessToken    = $socialiteUser->token;
        $expiresIn      = $socialiteUser->expiresIn;
        $tokenExpiresAt = $expiresIn ? now()->addSeconds($expiresIn) : now()->addDays(60);

        $account = SocialMediaAccount::updateOrCreate(
            [
                'platform'   => 'facebook',
                'account_id' => $socialiteUser->id,
                'tenant_id'  => auth()->user()->tenant_id,
            ],
            [
                'user_id'          => auth()->id(),
                'access_token'     => $accessToken,
                'username'         => $socialiteUser->name,
                'email'            => $socialiteUser->email,
                'token_expires_at' => $tokenExpiresAt,
                'is_active'        => true,
                'tenant_id'        => auth()->user()->tenant_id,
            ]
        );
        $account->refresh();

        $service = $this->getService('facebook');
        if ($service) {
            $pages  = $service->getPages($account);
            $groups = $service->getGroups($account);

            if ($pages['success']) {
                $account->page_id = implode(',', array_column($pages['pages'], 'id'));
                $account->settings = array_merge($account->settings ?? [], ['pages' => $pages['pages']]);
            }

            if ($groups['success']) {
                $account->group_id = implode(',', array_column($groups['groups'], 'id'));
                $account->settings = array_merge($account->settings ?? [], ['groups' => $groups['groups']]);
            }

            $account->save();
        }

        Log::info('Facebook OAuth account created/updated', ['account_id' => $account->id]);
    }

    public function getInstagramOAuthConfig(): ?SocialMediaConfig
    {
        return SocialMediaConfig::where('platform', 'facebook')->where('is_active', true)->first();
    }

    public function processInstagramCallback(string $accessToken, $expiresIn): int
    {
        $tokenExpiresAt = $expiresIn ? now()->addSeconds($expiresIn) : now()->addDays(60);
        $apiVersion     = 'v18.0';

        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$apiVersion}/me/accounts", [
                'fields' => 'id,name,instagram_business_account,access_token',
            ]);

        if (!$response->successful()) {
            Log::error('Instagram callback: failed to fetch pages', ['body' => $response->json()]);
            throw new \Exception('Failed to fetch Instagram accounts. Ensure your Facebook Page is linked to an Instagram Business account.');
        }

        $pages     = $response->json()['data'] ?? [];
        $connected = 0;

        foreach ($pages as $page) {
            $igAccount = $page['instagram_business_account'] ?? null;
            if (!$igAccount) {
                continue;
            }

            $igId            = $igAccount['id'];
            $pageAccessToken = $page['access_token'] ?? $accessToken;

            $igInfo   = Http::withToken($pageAccessToken)
                ->get("https://graph.facebook.com/{$apiVersion}/{$igId}", ['fields' => 'username,name'])
                ->json();
            $username = $igInfo['username'] ?? $igInfo['name'] ?? 'instagram_' . $igId;

            $account = SocialMediaAccount::updateOrCreate(
                [
                    'platform'   => 'instagram',
                    'account_id' => $igId,
                    'tenant_id'  => auth()->user()->tenant_id,
                ],
                [
                    'user_id'          => auth()->id(),
                    'access_token'     => $pageAccessToken,
                    'page_id'          => $page['id'],
                    'username'         => $username,
                    'token_expires_at' => $tokenExpiresAt,
                    'is_active'        => true,
                    'tenant_id'        => auth()->user()->tenant_id,
                ]
            );
            $account->refresh();
            $connected++;

            Log::info('Instagram OAuth account created/updated', ['account_id' => $account->id, 'instagram_id' => $igId]);
        }

        return $connected;
    }

    public function getThreadsOAuthConfig(): ?SocialMediaConfig
    {
        return SocialMediaConfig::where('platform', 'facebook')->where('is_active', true)->first();
    }

    public function processThreadsCallback(string $accessToken, $expiresIn): void
    {
        $tokenExpiresAt = $expiresIn ? now()->addSeconds($expiresIn) : now()->addDays(60);

        $response = Http::get('https://graph.threads.net/v1.0/me', [
            'fields'       => 'id,username',
            'access_token' => $accessToken,
        ]);

        if (!$response->successful()) {
            Log::error('Threads callback: failed to fetch user', ['body' => $response->json()]);
            throw new \Exception('Failed to fetch Threads account. Ensure your Meta app has Threads API product added.');
        }

        $data      = $response->json();
        $threadsId = $data['id'] ?? null;
        $username  = $data['username'] ?? 'threads_' . ($threadsId ?? 'user');

        if (!$threadsId) {
            throw new \Exception('Could not get Threads user ID. Ensure your Instagram account is linked to Threads.');
        }

        $account = SocialMediaAccount::updateOrCreate(
            [
                'platform'   => 'threads',
                'account_id' => $threadsId,
                'tenant_id'  => auth()->user()->tenant_id,
            ],
            [
                'user_id'          => auth()->id(),
                'access_token'     => $accessToken,
                'username'         => $username,
                'token_expires_at' => $tokenExpiresAt,
                'is_active'        => true,
                'tenant_id'        => auth()->user()->tenant_id,
            ]
        );
        $account->refresh();

        Log::info('Threads OAuth account created/updated', ['account_id' => $account->id, 'threads_id' => $threadsId]);
    }

    public function getYouTubeConfig(): ?SocialMediaConfig
    {
        return SocialMediaConfig::where('platform', 'youtube')->where('is_active', true)->first();
    }

    public function processYouTubeCallback($googleUser): void
    {
        $accessToken    = $googleUser->token;
        $refreshToken   = $googleUser->refreshToken;
        $expiresIn      = $googleUser->expiresIn ?? 3600;
        $tokenExpiresAt = now()->addSeconds($expiresIn);

        $channelsResponse = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/youtube/v3/channels', ['part' => 'snippet', 'mine' => 'true']);

        if (!$channelsResponse->successful() || empty($channelsResponse->json()['items'])) {
            throw new \Exception('Failed to fetch YouTube channel.');
        }

        $channel      = $channelsResponse->json()['items'][0];
        $channelId    = $channel['id'];
        $channelTitle = $channel['snippet']['title'] ?? 'YouTube';

        $accountData = [
            'user_id'         => auth()->id(),
            'access_token'    => $accessToken,
            'username'        => $channelTitle,
            'token_expires_at' => $tokenExpiresAt,
            'is_active'       => true,
        ];

        if ($refreshToken) {
            $accountData['settings'] = ['refresh_token' => $refreshToken];
        }

        $accountData['tenant_id'] = auth()->user()->tenant_id;
        SocialMediaAccount::updateOrCreate(
            [
                'platform'   => 'youtube',
                'account_id' => $channelId,
                'tenant_id'  => auth()->user()->tenant_id,
            ],
            $accountData
        );

        Log::info('YouTube OAuth account created/updated', ['channel_id' => $channelId]);
    }

    public function getLinkedinOAuthSetup(): array
    {
        $config = SocialMediaConfig::where('platform', 'linkedin')->where('is_active', true)->first();

        if (!$config) {
            throw new \Exception('LinkedIn configuration not found or inactive.');
        }

        $redirectUri = $config->redirect_uri ?: url('autopost/admin/social/account/list/linkedin/callback');
        $state       = Str::random(40);
        Session::put('oauth_state_linkedin', $state);

        $url = 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query([
            'response_type' => 'code',
            'client_id'     => $config->app_id,
            'redirect_uri'  => $redirectUri,
            'scope'         => 'openid profile email w_member_social',
            'state'         => $state,
        ]);

        return ['url' => $url];
    }

    public function processLinkedinCallback(Request $request): void
    {
        if ($request->get('state') !== Session::get('oauth_state_linkedin')) {
            throw new \Exception('Invalid state parameter.');
        }
        Session::forget('oauth_state_linkedin');

        $config = SocialMediaConfig::where('platform', 'linkedin')->where('is_active', true)->first();
        if (!$config) {
            throw new \Exception('LinkedIn configuration not found.');
        }

        $redirectUri   = $config->redirect_uri ?: url('autopost/admin/social/account/list/linkedin/callback');
        $tokenResponse = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type'    => 'authorization_code',
            'code'          => $request->get('code'),
            'client_id'     => $config->app_id,
            'client_secret' => $config->app_secret,
            'redirect_uri'  => $redirectUri,
        ]);

        if (!$tokenResponse->successful()) {
            throw new \Exception('Failed to get LinkedIn access token.');
        }

        $tokenData      = $tokenResponse->json();
        $accessToken    = $tokenData['access_token'];
        $expiresIn      = $tokenData['expires_in'] ?? 5184000;
        $tokenExpiresAt = now()->addSeconds($expiresIn);

        $userResponse = Http::withToken($accessToken)->get('https://api.linkedin.com/v2/userinfo');
        if (!$userResponse->successful()) {
            throw new \Exception('Failed to fetch LinkedIn profile.');
        }

        $userData = $userResponse->json();
        $personId = $userData['sub'] ?? null;
        $username = $userData['name'] ?? $userData['preferred_username'] ?? 'linkedin_user';

        if (!$personId) {
            throw new \Exception('Could not get LinkedIn user ID.');
        }

        SocialMediaAccount::updateOrCreate(
            [
                'platform'   => 'linkedin',
                'account_id' => $personId,
                'tenant_id'  => auth()->user()->tenant_id,
            ],
            [
                'user_id'          => auth()->id(),
                'access_token'     => $accessToken,
                'username'         => $username,
                'email'            => $userData['email'] ?? null,
                'token_expires_at' => $tokenExpiresAt,
                'is_active'        => true,
                'tenant_id'        => auth()->user()->tenant_id,
            ]
        );

        Log::info('LinkedIn OAuth account created/updated', ['person_id' => $personId]);
    }

    public function getTwitterOAuthSetup(): array
    {
        $config = SocialMediaConfig::where('platform', 'twitter')->where('is_active', true)->first();

        if (!$config) {
            throw new \Exception('Twitter configuration not found or inactive.');
        }

        $codeVerifier   = Str::random(128);
        $codeChallenge  = strtr(rtrim(base64_encode(hash('sha256', $codeVerifier, true)), '='), '+/', '-_');
        $state          = Str::random(40);
        $redirectUri    = $config->redirect_uri ?: url('autopost/admin/social/account/list/twitter/callback');

        Session::put('oauth_pkce_twitter', ['verifier' => $codeVerifier, 'state' => $state]);

        $url = 'https://twitter.com/i/oauth2/authorize?' . http_build_query([
            'response_type'         => 'code',
            'client_id'             => $config->app_id,
            'redirect_uri'          => $redirectUri,
            'scope'                 => 'tweet.read users.read offline.access',
            'state'                 => $state,
            'code_challenge'        => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        return ['url' => $url];
    }

    public function processTwitterCallback(Request $request): void
    {
        $pkce = Session::get('oauth_pkce_twitter');

        if (!$pkce || $request->get('state') !== $pkce['state']) {
            throw new \Exception('Invalid state.');
        }
        Session::forget('oauth_pkce_twitter');

        $config = SocialMediaConfig::where('platform', 'twitter')->where('is_active', true)->first();
        if (!$config) {
            throw new \Exception('Twitter configuration not found.');
        }

        $redirectUri   = $config->redirect_uri ?: url('autopost/admin/social/account/list/twitter/callback');
        $tokenResponse = Http::withBasicAuth($config->app_id, $config->app_secret)
            ->asForm()
            ->post('https://api.twitter.com/2/oauth2/token', [
                'grant_type'    => 'authorization_code',
                'code'          => $request->get('code'),
                'redirect_uri'  => $redirectUri,
                'code_verifier' => $pkce['verifier'],
            ]);

        if (!$tokenResponse->successful()) {
            throw new \Exception('Failed to get Twitter access token.');
        }

        $tokenData    = $tokenResponse->json();
        $accessToken  = $tokenData['access_token'];
        $refreshToken = $tokenData['refresh_token'] ?? null;

        $userResponse = Http::withToken($accessToken)
            ->get('https://api.twitter.com/2/users/me', ['user.fields' => 'username,name']);

        if (!$userResponse->successful()) {
            throw new \Exception('Failed to fetch Twitter profile.');
        }

        $userData = $userResponse->json()['data'] ?? [];
        $userId   = $userData['id'] ?? null;
        $username = '@' . ($userData['username'] ?? $userData['name'] ?? 'twitter_user');

        if (!$userId) {
            throw new \Exception('Could not get Twitter user ID.');
        }

        $accountData = [
            'user_id'      => auth()->id(),
            'access_token' => $accessToken,
            'username'     => $username,
            'is_active'    => true,
        ];

        if ($refreshToken) {
            $accountData['settings'] = ['refresh_token' => $refreshToken];
        }

        $accountData['tenant_id'] = auth()->user()->tenant_id;
        SocialMediaAccount::updateOrCreate(
            [
                'platform'   => 'twitter',
                'account_id' => $userId,
                'tenant_id'  => auth()->user()->tenant_id,
            ],
            $accountData
        );

        Log::info('Twitter OAuth account created/updated', ['user_id' => $userId]);
    }

    public function getTiktokOAuthSetup(): array
    {
        $config = SocialMediaConfig::where('platform', 'tiktok')->where('is_active', true)->first();

        if (!$config) {
            throw new \Exception('TikTok configuration not found or inactive.');
        }

        $redirectUri = $config->redirect_uri ?: url('autopost/admin/social/account/list/tiktok/callback');
        $state       = Str::random(40);
        Session::put('oauth_state_tiktok', $state);

        $url = 'https://www.tiktok.com/auth/authorize/?' . http_build_query([
            'client_key'    => $config->app_id,
            'scope'         => 'user.info.basic,video.upload,video.publish',
            'response_type' => 'code',
            'redirect_uri'  => $redirectUri,
            'state'         => $state,
        ]);

        return ['url' => $url, 'config' => $config, 'redirect_uri' => $redirectUri];
    }

    public function processTiktokCallback(Request $request): void
    {
        if ($request->get('state') !== Session::get('oauth_state_tiktok')) {
            throw new \Exception('Invalid state parameter.');
        }
        Session::forget('oauth_state_tiktok');

        $config = SocialMediaConfig::where('platform', 'tiktok')->where('is_active', true)->first();
        if (!$config) {
            throw new \Exception('TikTok configuration not found.');
        }

        $redirectUri   = $config->redirect_uri ?: url('autopost/admin/social/account/list/tiktok/callback');
        $tokenResponse = Http::asForm()->post('https://open.tiktokapis.com/v2/oauth/token/', [
            'client_key'    => $config->app_id,
            'client_secret' => $config->app_secret,
            'code'          => $request->get('code'),
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $redirectUri,
        ]);

        if (!$tokenResponse->successful()) {
            throw new \Exception('Failed to get TikTok access token.');
        }

        $tokenData    = $tokenResponse->json();
        $accessToken  = $tokenData['data']['access_token'] ?? null;
        $expiresIn    = $tokenData['data']['expires_in'] ?? 86400;
        $openId       = $tokenData['data']['open_id'] ?? null;
        $refreshToken = $tokenData['data']['refresh_token'] ?? null;

        if (!$accessToken || !$openId) {
            throw new \Exception('Invalid TikTok token response.');
        }

        $userResponse = Http::withToken($accessToken)
            ->get('https://open.tiktokapis.com/v2/user/info/', [
                'fields' => 'open_id,union_id,avatar_url,display_name',
            ]);

        $username = 'tiktok_' . $openId;
        if ($userResponse->successful()) {
            $userData = $userResponse->json()['data']['user'] ?? [];
            $username = $userData['display_name'] ?? $username;
        }

        $accountData = [
            'user_id'         => auth()->id(),
            'access_token'    => $accessToken,
            'username'        => $username,
            'token_expires_at' => now()->addSeconds($expiresIn),
            'is_active'       => true,
        ];

        if ($refreshToken) {
            $accountData['settings'] = ['refresh_token' => $refreshToken];
        }

        $accountData['tenant_id'] = auth()->user()->tenant_id;
        SocialMediaAccount::updateOrCreate(
            [
                'platform'   => 'tiktok',
                'account_id' => $openId,
                'tenant_id'  => auth()->user()->tenant_id,
            ],
            $accountData
        );

        Log::info('TikTok OAuth account created/updated', ['open_id' => $openId]);
    }

    private function refreshAccountInfo(SocialMediaAccount $account, SocialMediaServiceInterface $service): void
    {
        $accountInfo = $service->getAccountInfo($account);
        $pages       = $service->getPages($account);
        $groups      = $service->getGroups($account);

        $account->update([
            'username' => $accountInfo['username'] ?? $account->username,
            'email'    => $accountInfo['email']    ?? $account->email,
            'page_id'  => ($pages['success']  ?? false) ? implode(',', array_column($pages['pages']   ?? [], 'id')) : $account->page_id,
            'group_id' => ($groups['success'] ?? false) ? implode(',', array_column($groups['groups'] ?? [], 'id')) : $account->group_id,
        ]);
    }
}