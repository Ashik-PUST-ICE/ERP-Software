<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AutoPost\Admin\SocialMediaConfigRequest;
use App\Http\Services\Admin\SocialMediaConfigService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialMediaConfigController extends Controller
{
    use ResponseTrait;

    private SocialMediaConfigService $configService;

    public function __construct(SocialMediaConfigService $configService)
    {
        $this->configService = $configService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->configService->configList($request->all());
        }

        $data['configs']                 = $this->configService->getAllConfigs()->load('socialMediaAccounts');
        $data['stats']                   = $this->configService->getConfigStats();
        $data['platforms']               = $this->configService->getAvailablePlatforms();
        $data['title']                   = __('Social Media Configurations');
        $data['activeSocialMediaConfigs'] = 'active';
        $data['showSocialMediaMenu']      = true;

        return view('auto_posts.admin.social_media.configs.index', $data);
    }

    public function create()
    {
        $data['platforms']               = $this->configService->getAvailablePlatforms();
        $data['activeSocialMediaConfigs'] = 'active';
        $data['showSocialMediaMenu']      = true;

        return view('auto_posts.admin.social_media.configs.create', $data);
    }

    public function store(SocialMediaConfigRequest $request)
    {
        try {
            if (!isProviderAllowedByPlan($request->platform)) {
                $checkResult = getProviderLimitCheckResult($request->platform);
                return redirect()->back()->withInput()
                    ->with('error', $checkResult['message']);
            }

            $this->configService->store($request->validated());

            return redirect()
                ->route('admin.platform.index')
                ->with('success', getMessage(CREATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            Log::error('Failed to create social media config: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', getErrorMessage($e, $e->getMessage()));
        }
    }

    public function show($id)
    {
        $config = $this->configService->getConfigByPlatform($id);

        if (!$config) {
            return redirect()->route('admin.platform.index')->with('error', getMessage(CONFIG_NOT_FOUND));
        }

        $data['config']                  = $config;
        $data['validationErrors']         = $this->configService->validateConfig($config);
        $data['platforms']               = $this->configService->getAvailablePlatforms();
        $data['activeSocialMediaConfigs'] = 'active';
        $data['showSocialMediaMenu']      = true;

        return view('auto_posts.admin.social_media.configs.show', $data);
    }

    public function edit($id)
    {
        $config = $this->configService->getConfigByPlatform($id);

        if (!$config) {
            return redirect()->route('admin.platform.index')->with('error', getMessage(CONFIG_NOT_FOUND));
        }

        $data['config']                  = $config;
        $data['platforms']               = $this->configService->getAvailablePlatforms();
        $data['activeSocialMediaConfigs'] = 'active';
        $data['showSocialMediaMenu']      = true;

        return view('auto_posts.admin.social_media.configs.edit', $data);
    }

    public function update(SocialMediaConfigRequest $request, $id)
    {
        $config = $this->configService->getConfigByPlatform($id);

        if (!$config) {
            return redirect()->route('admin.platform.index')->with('error', getMessage(CONFIG_NOT_FOUND));
        }

        if (!isProviderAllowedByPlan($config->platform)) {
            $checkResult = getProviderLimitCheckResult($config->platform);
            return redirect()->back()->withInput()
                ->with('error', $checkResult['message']);
        }

        try {
            $this->configService->update($request->validated(), $config);

            return redirect()
                ->route('admin.platform.index')
                ->with('success', getMessage(UPDATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            Log::error('Failed to update social media config: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', getErrorMessage($e, $e->getMessage()));
        }
    }

    public function toggleStatus($id)
    {
        $config = $this->configService->getConfigByPlatform($id);

        if (!$config) {
            return redirect()->route('admin.platform.index')->with('error', getMessage(CONFIG_NOT_FOUND));
        }

        if (!$config->is_active && !isProviderAllowedByPlan($config->platform)) {
            $checkResult = getProviderLimitCheckResult($config->platform);
            return redirect()->route('admin.platform.index')->with('error', $checkResult['message']);
        }

        try {
            $config = $this->configService->toggleStatus($config);
            $status = $config->is_active ? 'activated' : 'deactivated';

            return redirect()
                ->route('admin.platform.index')
                ->with('success', getMessage(UPDATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            Log::error('Failed to toggle config status: ' . $e->getMessage());
            return redirect()->route('admin.platform.index')
                ->with('error', getErrorMessage($e, SOMETHING_WENT_WRONG));
        }
    }

    public function destroy($id)
    {
        $config = $this->configService->getConfigByPlatform($id);

        if (!$config) {
            return redirect()->route('admin.platform.index')->with('error', getMessage(CONFIG_NOT_FOUND));
        }

        try {
            $this->configService->deleteConfig($config);

            return redirect()
                ->route('admin.platform.index')
                ->with('success', getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            Log::error('Failed to delete social media config: ' . $e->getMessage());
            return redirect()->route('admin.platform.index')
                ->with('error', getErrorMessage($e, SOMETHING_WENT_WRONG));
        }
    }
}