<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\OpenAIService;
use App\Http\Services\SubscriptionService;
use App\Models\AIGeneratedContent;
use App\Models\OpenAIPrompt;
use App\Models\Gallery; // Added for gallery integration
use App\Models\Video;   // Added for video integration
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Added for logging
use Illuminate\View\View;

class AIContentGenerationController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct()
    {
        $this->subscriptionService = new SubscriptionService();
    }

    public function generateContentPage(): View
    {
        return view('auto_posts.admin.ai.generate', [
            'activeAIContent'       => 'active',
            'showAIContentMenu'     => 'show',
            'activeGenerateContent' => 'active',
        ]);
    }

    public function generateContent(Request $request): JsonResponse
    {
        $request->validate([
            'prompt'            => 'required|string|max:10000',
            'content_type'      => 'nullable|string|in:text,image,video',
            'tone'              => 'nullable|string|max:50',
            'language'           => 'nullable|string|max:10',
            'max_tokens'         => 'nullable|integer|min:100|max:4096',
            'prompt_template_id' => 'nullable|integer|exists:open_ai_prompts,id',
        ]);

        if (getOption('openai_ai_status', 1) != 1) {
            return response()->json([
                'success' => false,
                'message' => __('AI content generation is currently disabled by administrator.'),
            ], 403);
        }

        $currentPackage = $this->subscriptionService->getCurrentPlan(auth()->id());
        if (!$currentPackage || !$currentPackage->packageable || !$currentPackage->packageable->ai_enabled) {
            return response()->json([
                'success' => false,
                'message' => __('Your current plan does not include AI features. Please upgrade your package.'),
            ], 403);
        }

        $contentType = $request->input('content_type', 'text');
        $prompt = $request->input('prompt');
        $tone = $request->input('tone');
        $language = $request->input('language') ?: getOption('openai_default_language');
        $maxTokens = $request->input('max_tokens');
        $templateId = $request->input('prompt_template_id');

        if ($templateId && $contentType === 'text') {
            $template = OpenAIPrompt::find($templateId);
            if ($template) {
                $userInput = $prompt;
                $prompt = str_replace(['{user_input}', '{input}'], $userInput, $template->prompt_template);
            }
        }

        $instructions = [];
        if ($contentType === 'text') {
            if ($tone && isset(config('ai.tones')[$tone])) {
                $instructions[] = 'Tone: ' . config('ai.tones')[$tone];
            }
            if ($language) {
                $languageList = languageIsoCode();
                if (isset($languageList[$language])) {
                    $instructions[] = 'Write in: ' . $languageList[$language];
                }
            }
            if (!empty($instructions)) {
                $prompt = implode('. ', $instructions) . "\n\n" . $prompt;
            }
        }

        $openaiService = new OpenAIService();
        if ($contentType === 'image' || $contentType === 'video') {
            if (!$openaiService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => __('OpenAI API key is required for image/video. Set it in Settings → AI Settings.'),
                ], 400);
            }
            if ($contentType === 'image') {
                return $this->generateImageContent($request, $openaiService, $prompt);
            }
            return $this->generateVideoContent($request, $openaiService, $prompt);
        }

        if (!$openaiService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => __('OpenAI API key is not configured. Please ask Super Admin to set it in Settings → AI Settings.'),
            ], 400);
        }

        if ($request->has('debug_ai')) {
            dd([
                'api_key' => getOption('openai_api_key'),
                'model' => getOption('openai_model', config('ai.openai_default_model', 'gpt-4o-mini')),
                'configured' => $openaiService->isConfigured(),
                'prompt' => $request->prompt,
                'max_tokens' => $request->max_tokens,
                'full_config' => config('ai')
            ]);
        }

        // --- DEBUG LOGGING START ---
        $debugKey = getOption('openai_api_key');
        $debugModel = getOption('openai_model', config('ai.openai_default_model', 'gpt-4o-mini'));
        \Illuminate\Support\Facades\Log::info('AI Generation Debug Trace', [
            'user_id' => auth()->id(),
            'key_snippet' => substr($debugKey, 0, 8) . '...' . substr($debugKey, -4),
            'model' => $debugModel,
            'prompt' => $prompt,
            'max_tokens' => $maxTokens
        ]);
        // --- DEBUG LOGGING END ---

        $result = $openaiService->generateContent($prompt, $maxTokens);
        $model = getOption('openai_model', config('ai.openai_default_model', 'gpt-4o-mini'));

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $this->formatAIErrorMessage($result['error'] ?? __('Generation failed.')),
            ], 422);
        }

        $record = AIGeneratedContent::create([
            'user_id'        => auth()->id(),
            'prompt'         => $request->input('prompt'),
            'content_type'   => 'text',
            'generated_text' => $result['text'],
            'model'          => $model,
            'tenant_id'      => auth()->user()->tenant_id,
        ]);

        return response()->json([
            'success'      => true,
            'text'         => $result['text'],
            'content_type' => 'text',
            'id'           => $record->id,
        ]);
    }

    /**
     * User-friendly message for API errors. Quota/billing = OpenAI account limit, not a code bug.
     */
    protected function formatAIErrorMessage(string $apiError): string
    {
        $lower = strtolower($apiError);
        $isBillingOrQuota = str_contains($lower, 'billing') || str_contains($lower, 'quota') || str_contains($lower, 'usage limit')
            || str_contains($lower, 'quota exceeded') || str_contains($lower, 'insufficient_quota') || str_contains($lower, 'billing_limit');
        if ($isBillingOrQuota) {
            $hint = __('This is an OpenAI account limit: add payment method or credits at platform.openai.com → Billing.');
            return __('Your OpenAI quota or billing limit has been reached.') . ' ' . $hint;
        }
        if (preg_match('/retry\s+in\s+([\d.]+)\s*s/i', $apiError, $m)) {
            $sec = (int) round((float) $m[1]);
            return __('Quota or rate limit reached. Retry in about :sec seconds.', ['sec' => $sec]) . ' ' . __('Check platform.openai.com for usage and billing.');
        }
        return $apiError;
    }

    protected function generateImageContent(Request $request, OpenAIService $service, string $prompt): JsonResponse
    {
        $result = $service->generateImage($prompt);
        if (!$result['success']) {
            $errorMsg = $result['error'] ?? __('Image generation failed.');
            
            // Check for moderation error
            if (stripos($errorMsg, 'moderation') !== false || stripos($errorMsg, 'blocked') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your request was blocked by OpenAI\'s moderation system. Please try a different, more appropriate prompt.',
                ], 422);
            }
            
            return response()->json([
                'success' => false,
                'message' => $this->formatAIErrorMessage($errorMsg),
            ], 422);
        }

        $b64 = $result['b64_json'] ?? null;
        if (empty($b64)) {
            return response()->json(['success' => false, 'message' => __('No image data received.')], 422);
        }

        $dir = 'ai-generated';
        $filename = 'img_' . uniqid() . '_' . time() . '.png';
        $path = $dir . '/' . $filename;
        $binary = base64_decode($b64, true);
        if ($binary === false) {
            return response()->json(['success' => false, 'message' => __('Invalid image data.')], 422);
        }

        Storage::disk('public')->put($path, $binary);
        $model = getOption('openai_image_model', config('ai.openai_default_image_model', 'gpt-image-1-mini'));
        $record = AIGeneratedContent::create([
            'user_id'             => auth()->id(),
            'prompt'              => $request->input('prompt'),
            'content_type'        => 'image',
            'generated_text'      => null,
            'generated_media_path' => $path,
            'model'               => $model,
            'tenant_id'           => auth()->user()->tenant_id,
        ]);

        // Also save to Gallery for easy selection and persistence in posts
        $gallery = Gallery::create([
            'user_id'        => auth()->id(),
            'title'          => __('AI Generated: :prompt', ['prompt' => substr($request->input('prompt'), 0, 50)]),
            'platform'       => 'ai',
            'file_name'      => $filename,
            'file_path'      => $path,
            'file_type'      => 'image/png',
            'file_extension' => 'png',
            'file_size'      => strlen($binary),
            'tenant_id'      => auth()->user()->tenant_id,
        ]);

        $mediaUrl = asset('storage/' . $path);
        return response()->json([
            'success'      => true,
            'text'         => '',
            'content_type' => 'image',
            'media_url'    => $mediaUrl,
            'id'           => $record->id,
            'gallery_id'   => $gallery->id, // Added for create-post integration
        ]);
    }

    protected function generateVideoContent(Request $request, OpenAIService $service, string $prompt): JsonResponse
    {
        // Set a longer timeout for video generation (10 minutes max)
        set_time_limit(600); 
        $result = $service->generateVideo($prompt);
        
        if (!$result['success']) {
            // Check for moderation error
            $errorMsg = $result['error'] ?? __('Video generation failed.');
            if (stripos($errorMsg, 'moderation') !== false || stripos($errorMsg, 'blocked') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your request was blocked by OpenAI\'s moderation system. Please try a different, more appropriate prompt that doesn\'t contain harmful, violent, or inappropriate content.',
                ], 422);
            }
            return response()->json([
                'success' => false,
                'message' => $this->formatAIErrorMessage($errorMsg),
            ], 422);
        }

        $content = $result['content'] ?? null;
        if (empty($content)) {
            return response()->json(['success' => false, 'message' => __('No video data received.')], 422);
        }

        $dir = 'ai-generated';
        $filename = 'vid_' . uniqid() . '_' . time() . '.mp4';
        $path = $dir . '/' . $filename;
        Storage::disk('public')->put($path, $content);

        $model = getOption('openai_video_model', config('ai.openai_default_video_model', 'sora-2'));
        $record = AIGeneratedContent::create([
            'user_id'             => auth()->id(),
            'prompt'              => $request->input('prompt'),
            'content_type'        => 'video',
            'generated_text'      => null,
            'generated_media_path' => $path,
            'model'               => $model,
            'tenant_id'           => auth()->user()->tenant_id,
        ]);

        // Also save to Video for easy selection and persistence in posts
        $video = Video::create([
            'user_id'        => auth()->id(),
            'title'          => __('AI Generated: :prompt', ['prompt' => substr($request->input('prompt'), 0, 50)]),
            'platform'       => 'ai', // Fix: Added platform
            'file_name'      => $filename,
            'file_path'      => $path,
            'file_type'      => 'video/mp4',
            'file_extension' => 'mp4',
            'file_size'      => strlen($content),
            'status'         => 1,
            'tenant_id'      => auth()->user()->tenant_id,
        ]);

        $mediaUrl = asset('storage/' . $path);
        return response()->json([
            'success'      => true,
            'text'         => '',
            'content_type' => 'video',
            'media_url'    => $mediaUrl,
            'id'           => $record->id,
            'video_id'     => $video->id, // Added for create-post integration
        ]);
    }

    public function generateContentList(): View
    {
        return view('auto_posts.admin.ai.generated-list', [
            'items'              => [],
            'activeAIContent'    => 'active',
            'showAIContentMenu'  => 'show',
            'activeGeneratedList' => 'active',
        ]);
    }

    public function datatable(Request $request)
    {
        $query = AIGeneratedContent::where('user_id', auth()->id())
            ->where('is_saved', 1)
            ->latest();

        return datatables($query)
            ->addColumn('DT_RowIndex', function ($item) {
                return '';
            })
            ->addColumn('prompt', function ($item) {
                return '<span class="d-block text-truncate" style="max-width: 380px;" title="' . e($item->prompt) . '">' . e(Str::limit($item->prompt, 80)) . '</span>';
            })
            ->addColumn('content_type', function ($item) {
                $type = $item->content_type ?? 'text';
                $badge = $type === 'image' ? 'info' : ($type === 'video' ? 'secondary' : 'light');
                return '<span class="badge bg-' . $badge . '">' . e(ucfirst($type)) . '</span>';
            })
            ->addColumn('generated_text', function ($item) {
                $type = $item->content_type ?? 'text';
                if ($type === 'image') {
                    return $item->generated_media_path ? '<a href="' . asset('storage/' . $item->generated_media_path) . '" target="_blank">' . __('Image') . '</a>' : '—';
                }
                if ($type === 'video') {
                    return $item->generated_media_path ? '<a href="' . asset('storage/' . $item->generated_media_path) . '" target="_blank">' . __('Video') . '</a>' : '—';
                }
                return '<span class="d-block text-truncate" style="max-width: 380px;" title="' . e($item->generated_text ?? '') . '">' . e(Str::limit($item->generated_text ?? '', 80)) . '</span>';
            })
            ->addColumn('model', function ($item) {
                return $item->model ?? '—';
            })
            ->addColumn('created_at', function ($item) {
                return $item->created_at->format('M d, Y H:i');
            })
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.ai.generated-content.view', $item->id) . '" class="primary-btn btn-sm">View</a>';
            })
            ->rawColumns(['prompt', 'content_type', 'generated_text', 'action'])
            ->make(true);
    }

    public function toggleSave(Request $request, int $id): JsonResponse
    {
        try {
            $item = AIGeneratedContent::where('user_id', auth()->id())->findOrFail($id);
            $item->is_saved = $request->input('is_saved', 1);
            $item->save();

            return response()->json([
                'success' => true,
                'message' => $item->is_saved ? __('Content saved successfully.') : __('Removed from saved list.'),
                'is_saved' => $item->is_saved
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to update status.')
            ], 500);
        }
    }

    public function generateContentView(int $id): View|JsonResponse
    {
        $item = AIGeneratedContent::where('user_id', auth()->id())->findOrFail($id);

        return view('auto_posts.admin.ai.generated-view', [
            'item'             => $item,
            'activeAIContent'  => 'active',
            'showAIContentMenu' => 'show',
        ]);
    }
}