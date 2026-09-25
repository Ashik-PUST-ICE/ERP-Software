<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AISettingController extends Controller
{
    use ResponseTrait;

    public function openAISetting()
    {
        return view('auto_posts.super_admin.setting.ai_settings.open-ai-settings', [
            'title' => __('AI Chatbot Settings'),
            'showManageApplicationSetting' => 'show',
            'activeApplicationSetting' => 'active',
            'subAISettingActiveClass' => 'active',
            'providers' => config('ai.providers', []),
        ]);
    }

    public function updateAISettings(Request $request)
    {
        $providers = config('ai.providers', []);
        $provider = $request->input('ai_provider');
        abort_unless(array_key_exists($provider, $providers), 422, 'Invalid AI provider.');
        $models = $providers[$provider]['models'] ?? [];

        $data = $request->validate([
            'openai_ai_status' => ['required', 'in:0,1'],
            'ai_provider' => ['required', 'string', 'in:' . implode(',', array_keys($providers))],
            'ai_model' => ['required_if:openai_ai_status,1', 'string', 'in:' . implode(',', $models)],
            'provider_api_key' => ['nullable', 'string', 'max:500'],
            'openai_temperature' => ['nullable', 'numeric', 'min:0', 'max:2'],
            'openai_max_tokens' => ['required_if:openai_ai_status,1', 'integer', 'min:100', 'max:4096'],
            'openai_default_language' => ['nullable', 'string', 'max:20'],
        ]);

        $keyOption = $providers[$provider]['api_key_option'];
        $apiKey = trim((string) ($data['provider_api_key'] ?? ''));
        if ((int) $data['openai_ai_status'] === 1 && $apiKey === '' && ! filled(getOption($keyOption, ''))) {
            return response()->json(['status' => false, 'message' => __('API Key is required for the selected provider.')], 422);
        }

        $values = [
            'openai_ai_status' => $data['openai_ai_status'],
            'ai_provider' => $provider,
            'ai_model' => $data['ai_model'],
            $keyOption => $apiKey !== '' ? $apiKey : getOption($keyOption, ''),
            'openai_temperature' => $data['openai_temperature'] ?? '',
            'openai_max_tokens' => $data['openai_max_tokens'] ?? '',
            'openai_default_language' => $data['openai_default_language'] ?? '',
        ];
        $values[$provider . '_model'] = $data['ai_model'];
        if ($provider === 'openai') $values['openai_model'] = $data['ai_model'];

        foreach ($values as $key => $value) {
            Setting::updateOrCreate(['option_key' => $key], ['option_value' => $value]);
        }

        Log::info('AI chatbot settings updated', ['provider' => $provider, 'model' => $data['ai_model']]);
        return $this->success([], __('AI chatbot settings updated successfully.'));
    }
}
