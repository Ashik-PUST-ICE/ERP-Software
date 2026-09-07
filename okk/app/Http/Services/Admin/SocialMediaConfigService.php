<?php

namespace App\Http\Services\Admin;

use App\Models\SocialMediaAccount;
use App\Models\SocialMediaConfig;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocialMediaConfigService
{
    use ResponseTrait;

    private function getTenantId()
    {
        $user = auth()->user();
        return $user ? $user->tenant_id : null;
    }

    private function getUserId()
    {
        return auth()->id();
    }

    public function configList(array $params)
    {
        $tenantId = $this->getTenantId();
        $userId   = $this->getUserId();

        $query = SocialMediaConfig::with('socialMediaAccounts')
            ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->orderBy('platform', 'asc');

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('platform', function ($row) {
                return '<span class="text-capitalize">' . e($row->platform) . '</span>';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active 
                    ? '<span class="badge bg-success">' . __('Active') . '</span>'
                    : '<span class="badge bg-danger">' . __('Inactive') . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->diffForHumans();
            })
            ->rawColumns(['platform', 'is_active'])
            ->make(true);
    }

    public function getAllConfigs()
    {
        $tenantId = $this->getTenantId();
        $userId   = $this->getUserId();

        return SocialMediaConfig::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->orderBy('platform', 'asc')
            ->get();
    }

    public function getActiveConfigs()
    {
        $tenantId = $this->getTenantId();
        $userId   = $this->getUserId();

        return SocialMediaConfig::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->where('is_active', true)
            ->orderBy('platform', 'asc')
            ->get();
    }

    public function getConfigByPlatform($platform)
    {
        $tenantId = $this->getTenantId();
        $userId   = $this->getUserId();

        return SocialMediaConfig::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->where('platform', $platform)
            ->first();
    }

    

    public function getConfigStats(): array
    {
        $tenantId = $this->getTenantId();
        $userId   = $this->getUserId();

        $configQuery = SocialMediaConfig::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
            ->when($userId, fn($q) => $q->where('user_id', $userId));

        $accountQuery = SocialMediaAccount::when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
            ->when($userId, fn($q) => $q->where('user_id', $userId));
        
        return [
            'total_configs'   => $configQuery->count(),
            'active_configs'  => $configQuery->where('is_active', true)->count(),
            'total_accounts'  => $accountQuery->count(),
            'active_accounts' => $accountQuery->where('is_active', true)->count(),
            'platforms'       => $this->getAvailablePlatforms(),
        ];
    }

    public function getAvailablePlatforms(): array
    {
        return [
            'facebook'  => [
                'name'        => 'Facebook',
                'icon'        => 'fab fa-facebook',
                'color'       => '#1877F2',
                'description' => 'Connect Facebook pages and groups',
                'permissions' => SocialMediaConfig::getDefaultPermissions('facebook'),
            ],
            'twitter'   => [
                'name'        => 'Twitter/X',
                'icon'        => 'fab fa-twitter',
                'color'       => '#1DA1F2',
                'description' => 'Connect Twitter/X accounts',
                'permissions' => SocialMediaConfig::getDefaultPermissions('twitter'),
            ],
            'instagram' => [
                'name'        => 'Instagram',
                'icon'        => 'fab fa-instagram',
                'color'       => '#E4405F',
                'description' => 'Connect Instagram business accounts',
                'permissions' => SocialMediaConfig::getDefaultPermissions('instagram'),
            ],
            'linkedin'  => [
                'name'        => 'LinkedIn',
                'icon'        => 'fab fa-linkedin',
                'color'       => '#0077B5',
                'description' => 'Connect LinkedIn pages and profiles',
                'permissions' => SocialMediaConfig::getDefaultPermissions('linkedin'),
            ],
            'youtube'   => [
                'name'        => 'YouTube',
                'icon'        => 'fab fa-youtube',
                'color'       => '#FF0000',
                'description' => 'Connect YouTube channels and upload videos',
                'permissions' => SocialMediaConfig::getDefaultPermissions('youtube'),
            ],
            'tiktok'    => [
                'name'        => 'TikTok',
                'icon'        => 'fab fa-tiktok',
                'color'       => '#000000',
                'description' => 'Connect TikTok accounts and upload videos',
                'permissions' => SocialMediaConfig::getDefaultPermissions('tiktok'),
            ],
            'threads'   => [
                'name'        => 'Threads',
                'icon'        => 'fab fa-threads',
                'color'       => '#000000',
                'description' => 'Connect Threads accounts (uses Instagram API)',
                'permissions' => SocialMediaConfig::getDefaultPermissions('threads'),
            ],
        ];
    }

    public function getPlatformConfigWithDefaults($platform): array
    {
        $config = $this->getConfigByPlatform($platform);

        if (!$config) {
            return [
                'platform'     => $platform,
                'app_id'       => '',
                'app_secret'   => '',
                'redirect_uri' => url('/autopost/admin/social-media/callback'),
                'permissions'  => SocialMediaConfig::getDefaultPermissions($platform),
                'settings'     => [],
                'is_active'    => false,
            ];
        }

        return $config->toArray();
    }

    public function validateConfig(SocialMediaConfig $config): array
    {
        $errors = [];

        if (empty($config->app_id)) {
            $errors[] = 'App ID is required';
        }
        if (empty($config->app_secret)) {
            $errors[] = 'App Secret is required';
        }
        if (empty($config->redirect_uri)) {
            $errors[] = 'Redirect URI is required';
        } elseif (!filter_var($config->redirect_uri, FILTER_VALIDATE_URL)) {
            $errors[] = 'Invalid Redirect URI format';
        }
        if (empty($config->permissions)) {
            $errors[] = 'At least one permission is required';
        }

        return $errors;
    }

    public function toggleStatus(SocialMediaConfig $config): SocialMediaConfig
    {
        $config->is_active = !$config->is_active;
        $config->save();

        if (!$config->is_active) {
            SocialMediaAccount::where('platform', $config->platform)
                ->when($config->tenant_id, fn($q) => $q->where('tenant_id', $config->tenant_id))
                ->when($config->user_id, fn($q) => $q->where('user_id', $config->user_id))
                ->update(['is_active' => false]);
        }

        return $config;
    }

    public function deleteConfig(SocialMediaConfig $config): void
    {
        SocialMediaAccount::where('platform', $config->platform)
            ->when($config->tenant_id, fn($q) => $q->where('tenant_id', $config->tenant_id))
            ->when($config->user_id, fn($q) => $q->where('user_id', $config->user_id))
            ->delete();
        $config->delete();
    }

    public function store(array $data): SocialMediaConfig
    {
        $data = $this->prepareStoreData($data);
        // Add tenant_id if not already set
        if (empty($data['tenant_id'])) {
            $data['tenant_id'] = $this->getTenantId();
        }
        // Bind config to current user as well
        $data['user_id'] = $this->getUserId();
        return SocialMediaConfig::create($data);
    }

    public function update(array $data, SocialMediaConfig $config): SocialMediaConfig
    {
        $data = $this->prepareUpdateData($data, $config);
        $config->update($data);
        return $config;
    }

    private function prepareStoreData(array $data): array
    {
        $platform = $data['platform'];

        if (empty($data['redirect_uri'])) {
            $data['redirect_uri'] = in_array($platform, ['facebook', 'instagram'])
                ? ''
                : url('/autopost/admin/social-media/callback');
        }

        if ($platform === 'twitter' && !empty($data['access_token_secret'])) {
            $data['permissions'] = array_merge($data['permissions'] ?? [], [
                'access_token_secret' => $data['access_token_secret'],
            ]);
        }
        unset($data['access_token_secret']);

        if (in_array($platform, ['facebook', 'instagram', 'linkedin'])) {
            $settings = [];
            if (!empty($data['settings']['access_token'])) {
                $settings['access_token'] = $data['settings']['access_token'];
            }
            if ($platform === 'facebook') {
                $settings['api_version']   = $data['settings']['api_version']   ?? 'v24.0';
                $settings['graph_api_url'] = $data['settings']['graph_api_url'] ?? 'https://graph.facebook.com';
            }
            $data['settings'] = $settings;
        }

        if ($platform === 'facebook') {
            $data['permissions'] = is_array($data['permissions'] ?? null) ? $data['permissions'] : [];
        }

        if ($platform === 'youtube' && !empty($data['settings'])) {
            $s = [];
            foreach (['access_token', 'refresh_token', 'api_key'] as $key) {
                if (!empty($data['settings'][$key])) {
                    $s[$key] = $data['settings'][$key];
                }
            }
            $data['settings'] = $s;
        }

        if ($platform === 'tiktok' && !empty($data['settings']['access_token'])) {
            $data['settings'] = ['access_token' => trim($data['settings']['access_token'])];
        }

        return $data;
    }

    private function prepareUpdateData(array $data, SocialMediaConfig $config): array
    {
        $platform         = $data['platform'];
        $existingSettings = $config->settings ?? [];

        if ($platform === 'twitter') {
            if (empty($data['redirect_uri'])) {
                $data['redirect_uri'] = $config->redirect_uri ?? '';
            }

            $secretValue = trim($data['access_token_secret'] ?? '');
            if ($secretValue !== '') {
                $data['permissions'] = array_merge($data['permissions'] ?? [], [
                    'access_token_secret' => $secretValue,
                ]);
            }
            unset($data['access_token_secret']);

            $newSettings = $data['settings'] ?? [];
            $final = [];

            $bearerToken = trim($newSettings['bearer_token'] ?? '');
            if ($bearerToken !== '') $final['bearer_token'] = $bearerToken;

            $accessToken = trim($newSettings['access_token'] ?? '');
            if ($accessToken !== '') $final['access_token'] = $accessToken;

            $data['settings'] = $final;
        }

        if (in_array($platform, ['facebook', 'instagram', 'linkedin'])) {
            if (in_array($platform, ['facebook', 'instagram']) && empty($data['redirect_uri'])) {
                $data['redirect_uri'] = $config->redirect_uri ?? '';
            }

            $newSettings = $data['settings'] ?? [];
            unset($newSettings['access_token_existing']);

            $accessToken = trim($newSettings['access_token'] ?? '');
            $final = [];
            if ($accessToken !== '') $final['access_token'] = $accessToken;

            $defaults = [
                'facebook'  => ['api_version' => 'v24.0',  'graph_api_url' => 'https://graph.facebook.com'],
                'instagram' => ['api_version' => 'v23.0',  'graph_api_url' => 'https://graph.instagram.com'],
                'linkedin'  => ['api_version' => 'v2',     'api_base_url'  => 'https://api.linkedin.com'],
            ];

            foreach ($defaults[$platform] as $key => $default) {
                $final[$key] = !empty($newSettings[$key])
                    ? $newSettings[$key]
                    : ($existingSettings[$key] ?? $default);
            }

            $data['settings'] = $final;
        }

        if ($platform === 'facebook') {
            $data['permissions'] = is_array($data['permissions'] ?? null) ? $data['permissions'] : [];
        }

        if ($platform === 'youtube') {
            $newSettings = $data['settings'] ?? [];
            $final = [];

            // Process each setting key
            foreach (['access_token', 'refresh_token', 'api_key'] as $key) {
                unset($newSettings[$key . '_existing']);
                
                // Check if key exists in submitted form data
                if (array_key_exists($key, $newSettings)) {
                    // User explicitly set a value - use it (even if empty to allow clearing)
                    $final[$key] = $newSettings[$key];
                } elseif (isset($existingSettings[$key]) && $existingSettings[$key] !== '') {
                    // No new value provided, keep existing if it exists
                    $final[$key] = $existingSettings[$key];
                }
            }
            $data['settings'] = $final;
        }

        if ($platform === 'tiktok') {
            $newSettings = $data['settings'] ?? [];
            unset($newSettings['access_token_existing']);
            $accessToken = trim($newSettings['access_token'] ?? '');
            $final = $existingSettings;
            if ($accessToken !== '') {
                $final['access_token'] = $accessToken;
            }
            $data['settings'] = $final;
        }

        return $data;
    }
}