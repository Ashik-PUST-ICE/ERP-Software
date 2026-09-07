<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AutoPost\Admin\SocialMediaAccountRequest;
use App\Http\Services\SocialMedia\SocialMediaServiceManager;
use App\Models\SocialMediaAccount;
use App\Models\SocialMediaConfig;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialMediaController extends Controller
{
    use ResponseTrait;

    private SocialMediaServiceManager $serviceManager;

    public function __construct(SocialMediaServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function index(Request $request)
    {
        $data                    = $this->serviceManager->getIndexViewData($request);
        $data['activeSocialMedia'] = 'active';
        $data['showSocialMediaMenu'] = true;

        return view('auto_posts.admin.social_media.index', $data);
    }

    public function create()
    {
        $data['platforms']         = $this->serviceManager->getConfiguredPlatforms();
        $data['activeSocialMedia'] = 'active';
        $data['showSocialMediaMenu'] = true;

        return view('auto_posts.admin.social_media.create', $data);
    }

    public function store(SocialMediaAccountRequest $request)
    {
        try {
            $this->serviceManager->storeAccount($request->validated());

            if ($request->expectsJson() || $request->ajax()) {
                return $this->success([], getMessage(CREATED_SUCCESSFULLY));
            }

            return redirect()->route('admin.social.account.index')
                ->with('success', 'Social media account created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create social media account', ['error' => $e->getMessage()]);

            if ($request->expectsJson() || $request->ajax()) {
                return $this->error([], $e->getMessage());
            }

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(SocialMediaAccount $account)
    {
        $account->load(['user', 'scheduledPosts', 'postHistories']);

        $data['account']           = $account;
        $data['platformInfo']      = $this->serviceManager->getPlatformInfo($account->platform);
        $data['activeSocialMedia'] = 'active';
        $data['showSocialMediaMenu'] = true;

        return view('auto_posts.admin.social_media.show', $data);
    }

    public function edit(SocialMediaAccount $account)
    {
        $data['account']           = $account;
        $data['platforms']         = $this->serviceManager->getConfiguredPlatforms();
        $data['activeSocialMedia'] = 'active';
        $data['showSocialMediaMenu'] = true;

        return view('auto_posts.admin.social_media.edit', $data);
    }

    public function editData(SocialMediaAccount $account)
    {
        return response()->json($this->serviceManager->getEditAccountData($account));
    }

    public function update(SocialMediaAccountRequest $request, SocialMediaAccount $account)
    {
        try {
            $this->serviceManager->updateAccount($account, $request->validated());

            if ($request->expectsJson() || $request->ajax()) {
                return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
            }

            return redirect()->route('admin.social.account.index')
                ->with('success', 'Social media account updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update social media account', ['account_id' => $account->id, 'error' => $e->getMessage()]);

            if ($request->expectsJson() || $request->ajax()) {
                return $this->error([], $e->getMessage());
            }

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(SocialMediaAccount $account)
    {
        try {
            $this->serviceManager->deleteAccount($account);
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            Log::error('Failed to delete social media account', ['account_id' => $account->id, 'error' => $e->getMessage()]);
            return $this->error([], $e->getMessage());
        }
    }

    public function toggleStatus(SocialMediaAccount $account)
    {
        try {
            $this->serviceManager->toggleAccountStatus($account);
            return redirect()->route('admin.social.account.index')
                ->with('success', 'Account status updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to toggle account status', ['account_id' => $account->id, 'error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to update account status']);
        }
    }

    public function autoConnect()
    {
        try {
            $result = $this->serviceManager->runAutoConnect();
            return $this->success(
                ['connected' => $result['connected'], 'failed' => $result['failed']],
                "Auto connect completed. Connected: {$result['connected']}, Failed: {$result['failed']}"
            );
        } catch (\Exception $e) {
            Log::error('Auto connect error: ' . $e->getMessage());
            return $this->error([], 'Failed to auto connect accounts');
        }
    }

    public function connect(SocialMediaAccount $account)
    {
        try {
            if ($this->serviceManager->connectAccount($account)) {
                return redirect()->back()->with('success', 'Account connected successfully');
            }
            return redirect()->back()->withErrors(['error' => 'Failed to connect account - invalid credentials']);
        } catch (\Exception $e) {
            Log::error('Social media connect error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to connect account']);
        }
    }



    public function statistics(SocialMediaAccount $account)
    {
        return response()->json($this->serviceManager->getAccountStatistics($account));
    }

    public function redirectToFacebook()
    {
        try {
            if (!$this->serviceManager->getFacebookConfig()) {
                return redirect()->route('admin.social.account.index')
                    ->withErrors(['error' => 'Facebook configuration not found or inactive']);
            }

            $scopes = SocialMediaConfig::getDefaultPermissions('facebook');

            return Socialite::driver('facebook')
                ->scopes($scopes)
                ->redirect();
        } catch (\Exception $e) {
            Log::error('Facebook redirect error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to redirect to Facebook: ' . $e->getMessage()]);
        }
    }

    public function handleFacebookCallback()
    {
        try {
            $this->serviceManager->processFacebookCallback(Socialite::driver('facebook')->user());
            return redirect()->route('admin.social.account.index')
                ->with('success', 'Facebook account connected successfully');
        } catch (\Exception $e) {
            Log::error('Facebook callback error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to connect Facebook account: ' . $e->getMessage()]);
        }
    }

    public function redirectToInstagram()
    {
        try {
            $config = $this->serviceManager->getInstagramOAuthConfig();

            if (!$config) {
                return redirect()->route('admin.social.account.index')
                    ->withErrors(['error' => 'Facebook configuration not found. Configure Facebook first—Instagram uses the same app.']);
            }

            config([
                'services.facebook.client_id'     => $config->app_id,
                'services.facebook.client_secret'  => $config->app_secret,
                'services.facebook.redirect'       => route('admin.social.account.instagram.callback'),
            ]);

            $scopes = SocialMediaConfig::getDefaultPermissions('instagram');

            return Socialite::driver('facebook')
                ->scopes($scopes)
                ->redirect();
        } catch (\Exception $e) {
            Log::error('Instagram redirect error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to redirect to Instagram: ' . $e->getMessage()]);
        }
    }

    public function handleInstagramCallback()
    {
        try {
            $user      = Socialite::driver('facebook')->user();
            $connected = $this->serviceManager->processInstagramCallback($user->token, $user->expiresIn);

            if ($connected === 0) {
                return redirect()->route('admin.social.account.index')
                    ->withErrors(['error' => 'No Instagram Business account found. Link an Instagram Business account to your Facebook Page in Meta Business settings.']);
            }

            return redirect()->route('admin.social.account.index')
                ->with('success', 'Instagram account(s) connected successfully');
        } catch (\Exception $e) {
            Log::error('Instagram callback error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to connect Instagram account: ' . $e->getMessage()]);
        }
    }

    public function redirectToThreads()
    {
        try {
            $config = $this->serviceManager->getThreadsOAuthConfig();

            if (!$config) {
                return redirect()->route('admin.social.account.index')
                    ->withErrors(['error' => 'Facebook configuration not found. Configure Facebook first—Threads uses the same app.']);
            }

            config([
                'services.facebook.client_id'     => $config->app_id,
                'services.facebook.client_secret'  => $config->app_secret,
                'services.facebook.redirect'       => route('admin.social.account.threads.callback'),
            ]);

            $scopes = SocialMediaConfig::getDefaultPermissions('threads');

            return Socialite::driver('facebook')
                ->scopes($scopes)
                ->redirect();
        } catch (\Exception $e) {
            Log::error('Threads redirect error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to redirect to Threads: ' . $e->getMessage()]);
        }
    }

    public function handleThreadsCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $this->serviceManager->processThreadsCallback($user->token, $user->expiresIn);
            return redirect()->route('admin.social.account.index')
                ->with('success', 'Threads account connected successfully');
        } catch (\Exception $e) {
            Log::error('Threads callback error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to connect Threads account: ' . $e->getMessage()]);
        }
    }

    public function redirectToYouTube()
    {
        try {
            $config = $this->serviceManager->getYouTubeConfig();

            if (!$config) {
                return redirect()->route('admin.social.account.index')
                    ->withErrors(['error' => 'YouTube configuration not found or inactive.']);
            }

            config([
                'services.google.client_id'     => $config->app_id,
                'services.google.client_secret'  => $config->app_secret,
                'services.google.redirect'       => route('admin.social.account.youtube.callback'),
            ]);

            $scopes = SocialMediaConfig::getDefaultPermissions('youtube');

            return Socialite::driver('google')
                ->scopes($scopes)
                ->with([
                    'prompt'      => 'select_account',
                    'access_type' => 'offline',
                ])
                ->redirect();
        } catch (\Exception $e) {
            Log::error('YouTube redirect error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to redirect to YouTube: ' . $e->getMessage()]);
        }
    }

    public function handleYouTubeCallback()
    {
        try {
            $this->serviceManager->processYouTubeCallback(Socialite::driver('google')->user());
            return redirect()->route('admin.social.account.index')
                ->with('success', 'YouTube account connected successfully');
        } catch (\Exception $e) {
            Log::error('YouTube callback error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to connect YouTube: ' . $e->getMessage()]);
        }
    }

    public function redirectToLinkedin()
    {
        try {
            $setup = $this->serviceManager->getLinkedinOAuthSetup();
            return redirect($setup['url']);
        } catch (\Exception $e) {
            Log::error('LinkedIn redirect error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to redirect to LinkedIn: ' . $e->getMessage()]);
        }
    }

    public function handleLinkedinCallback(Request $request)
    {
        try {
            $this->serviceManager->processLinkedinCallback($request);
            return redirect()->route('admin.social.account.index')
                ->with('success', 'LinkedIn account connected successfully');
        } catch (\Exception $e) {
            Log::error('LinkedIn callback error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to connect LinkedIn: ' . $e->getMessage()]);
        }
    }

    public function redirectToTwitter()
    {
        try {
            $setup = $this->serviceManager->getTwitterOAuthSetup();
            return redirect($setup['url']);
        } catch (\Exception $e) {
            Log::error('Twitter redirect error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to redirect to Twitter: ' . $e->getMessage()]);
        }
    }

    public function handleTwitterCallback(Request $request)
    {
        try {
            $this->serviceManager->processTwitterCallback($request);
            return redirect()->route('admin.social.account.index')
                ->with('success', 'Twitter account connected successfully');
        } catch (\Exception $e) {
            Log::error('Twitter callback error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to connect Twitter: ' . $e->getMessage()]);
        }
    }

    public function redirectToTiktok()
    {
        try {
            $setup = $this->serviceManager->getTiktokOAuthSetup();
            return redirect($setup['url']);
        } catch (\Exception $e) {
            Log::error('TikTok redirect error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to redirect to TikTok: ' . $e->getMessage()]);
        }
    }

    public function handleTiktokCallback(Request $request)
    {
        try {
            $this->serviceManager->processTiktokCallback($request);
            return redirect()->route('admin.social.account.index')
                ->with('success', 'TikTok account connected successfully');
        } catch (\Exception $e) {
            Log::error('TikTok callback error: ' . $e->getMessage());
            return redirect()->route('admin.social.account.index')
                ->withErrors(['error' => 'Failed to connect TikTok: ' . $e->getMessage()]);
        }
    }
}