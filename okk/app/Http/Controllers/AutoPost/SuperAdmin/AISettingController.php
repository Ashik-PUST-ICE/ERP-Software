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
        $data['title'] = __('AI Settings');
        $data['showManageApplicationSetting'] = 'show';
        $data['activeApplicationSetting'] = 'active';
        $data['subAISettingActiveClass'] = 'active';
        return view('auto_posts.super_admin.setting.ai_settings.open-ai-settings')->with($data);
    }

    public function updateAISettings(Request $request)
    {
        $openaiModels = config('ai.openai_models', ['gpt-4o-mini', 'gpt-4o', 'gpt-4-turbo', 'gpt-3.5-turbo']);
        $allowedAiModel = array_map(fn ($m) => 'openai__' . $m, $openaiModels);

        $request->validate([
            'openai_ai_status'        => 'required|in:0,1',
            'ai_model'                => 'required_if:openai_ai_status,1|nullable|string|in:' . implode(',', $allowedAiModel),
            'openai_api_key'          => 'nullable|string|max:500',
            'openai_temperature'      => 'nullable|numeric|min:0|max:2',
            'openai_max_tokens'       => 'required_if:openai_ai_status,1|integer|min:100|max:4096',
            'openai_default_language' => 'nullable|string|max:20',
        ]);

        $aiModel = $request->input('ai_model', '');
        $openaiModel = config('ai.openai_default_model', 'gpt-4o-mini');
        if (str_contains($aiModel, '__')) {
            [, $modelId] = explode('__', $aiModel, 2);
            if (in_array($modelId, $openaiModels, true)) {
                $openaiModel = $modelId;
            }
        }

        if ((int) $request->input('openai_ai_status', 0) === 1) {
            if (empty(trim($request->input('openai_api_key', '')))) {
                return response()->json(['status' => false, 'message' => __('API Key is required. Enter your OpenAI key.')], 422);
            }
        }

        $keys = ['openai_api_key', 'openai_model', 'openai_temperature', 'openai_max_tokens', 'openai_ai_status', 'openai_default_language'];
        $values = [
            'openai_api_key'          => $request->input('openai_api_key', ''),
            'openai_model'             => $openaiModel,
            'openai_temperature'      => $request->input('openai_temperature', ''),
            'openai_max_tokens'       => $request->input('openai_max_tokens', ''),
            'openai_ai_status'        => $request->input('openai_ai_status', ''),
            'openai_default_language' => $request->input('openai_default_language', ''),
        ];
        foreach ($keys as $key) {
            $option = Setting::firstOrCreate(['option_key' => $key]);
            $option->option_value = $values[$key] ?? '';
            $option->save();
        }

        Log::info('AI Settings Updated', [
            'keys' => $keys,
            'values_snippet' => array_merge($values, ['openai_api_key' => substr($values['openai_api_key'], 0, 8) . '...'])
        ]);

        return $this->success([], __('Updated successfully.'));
    }
}
