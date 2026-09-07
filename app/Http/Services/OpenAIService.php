<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected string $apiKey;
    protected string $model;
    protected float $temperature;
    protected int $maxTokens;
    protected string $baseUrl = 'https://api.openai.com/v1';

    public function __construct(?string $apiKey = null, ?string $model = null, ?float $temperature = null, ?int $maxTokens = null)
    {
        $this->apiKey = $apiKey ?? getOption('openai_api_key', '');
        $this->model = $model ?? getOption('openai_model', config('ai.openai_default_model', 'gpt-4o-mini'));
        $this->temperature = $temperature ?? (float) getOption('openai_temperature', config('ai.openai_default_temperature', 0.7));
        $this->maxTokens = $maxTokens ?? (int) getOption('openai_max_tokens', config('ai.openai_default_max_tokens', 1000));
    }

    public function isConfigured(): bool
    {
        return !empty(trim($this->apiKey));
    }

    /**
     * Generate image from text prompt (OpenAI Images API).
     *
     * @return array{success: bool, b64_json?: string, error?: string}
     */
    public function generateImage(string $prompt, ?string $imageModel = null): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => __('OpenAI API key is not configured.')];
        }

        $model = $imageModel ?? getOption('openai_image_model', config('ai.openai_default_image_model', 'gpt-image-1-mini'));
        $models = config('ai.openai_image_models', ['gpt-image-1.5', 'gpt-image-1-mini', 'gpt-image-1']);
        if (!in_array($model, $models, true)) {
            $model = $models[0] ?? 'gpt-image-1-mini';
        }

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(120)
                ->post("{$this->baseUrl}/images/generations", [
                    'model' => $model,
                    'prompt' => $prompt,
                    'n' => 1,
                ]);

            if (!$response->successful()) {
                $body = $response->json();
                $message = $body['error']['message'] ?? $response->body();
                // Billing/quota = OpenAI account limit, not application bug
                Log::warning('OpenAI Images API error (check account billing at platform.openai.com)', ['status' => $response->status(), 'body' => $body]);
                return ['success' => false, 'error' => $message];
            }

            $data = $response->json();
            $b64 = $data['data'][0]['b64_json'] ?? null;
            if (empty($b64)) {
                return ['success' => false, 'error' => __('No image data in response.')];
            }
            return ['success' => true, 'b64_json' => $b64];
        } catch (\Exception $e) {
            Log::error('OpenAI image generation failed', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Create video generation job, poll until complete, return raw MP4 content.
     *
     * @return array{success: bool, content?: string, error?: string} content is raw binary (base64 for transport)
     */
    public function generateVideo(string $prompt, ?string $videoModel = null, int $pollTimeoutSeconds = 600, int $pollIntervalSeconds = 10): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => __('OpenAI API key is not configured.')];
        }

        $model = $videoModel ?? getOption('openai_video_model', config('ai.openai_default_video_model', 'sora-2'));
        $models = config('ai.openai_video_models', ['sora-2', 'sora-2-pro']);
        if (!in_array($model, $models, true)) {
            $model = $models[0] ?? 'sora-2';
        }

        try {
            // Increase timeout for video generation - it can take a while
            $createResponse = Http::withToken($this->apiKey)
                ->timeout(300)  // 5 minutes for initial request
                ->connectTimeout(60)
                ->asMultipart()
                ->post("{$this->baseUrl}/videos", [
                    ['name' => 'model', 'contents' => $model],
                    ['name' => 'prompt', 'contents' => $prompt],
                ]);

            if (!$createResponse->successful()) {
                $body = $createResponse->json();
                $message = $body['error']['message'] ?? $createResponse->body();
                
                // Check for moderation error
                if (stripos($message, 'moderation') !== false || stripos($message, 'blocked') !== false) {
                    $message = 'Your request was blocked by OpenAI\'s moderation system. Please try a different, more appropriate prompt.';
                }
                
                Log::warning('OpenAI Videos API error (check account billing at platform.openai.com)', ['status' => $createResponse->status(), 'body' => $body]);
                return ['success' => false, 'error' => $message];
            }

            $job = $createResponse->json();
            $videoId = $job['id'] ?? null;
            if (empty($videoId)) {
                return ['success' => false, 'error' => __('No video job ID in response.')];
            }

            $deadline = time() + $pollTimeoutSeconds;
            while (time() < $deadline) {
                $statusResponse = Http::withToken($this->apiKey)
                    ->timeout(60)  // Increased from 30
                    ->connectTimeout(30)
                    ->get("{$this->baseUrl}/videos/{$videoId}");

                if (!$statusResponse->successful()) {
                    // Check for timeout error
                    if ($statusResponse->serverError() || $statusResponse->failed()) {
                        Log::warning('OpenAI video status check failed, retrying...', ['status' => $statusResponse->status()]);
                        sleep($pollIntervalSeconds * 2);  // Wait longer on error
                        continue;
                    }
                    return ['success' => false, 'error' => __('Failed to get video status.')];
                }
                $statusData = $statusResponse->json();
                $status = $statusData['status'] ?? '';

                if ($status === 'failed') {
                    $err = $statusData['error']['message'] ?? __('Video generation failed.');
                    return ['success' => false, 'error' => $err];
                }
                if ($status === 'completed') {
                    $contentResponse = Http::withToken($this->apiKey)
                        ->timeout(120)
                        ->get("{$this->baseUrl}/videos/{$videoId}/content");
                    if (!$contentResponse->successful()) {
                        return ['success' => false, 'error' => __('Failed to download video.')];
                    }
                    return ['success' => true, 'content' => $contentResponse->body()];
                }

                sleep($pollIntervalSeconds);
            }

            return ['success' => false, 'error' => __('Video generation timed out.')];
        } catch (\Exception $e) {
            Log::error('OpenAI video generation failed', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate text completion using OpenAI Chat API.
     *
     * @param string $prompt User prompt
     * @param int|null $maxTokens Override max tokens (uses setting if null)
     * @return array{success: bool, text?: string, error?: string}
     */
    public function generateContent(string $prompt, ?int $maxTokens = null): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => __('OpenAI API key is not configured. Please set it in Super Admin → Settings → AI Settings.')];
        }

        $maxTokens = $maxTokens ?? $this->maxTokens;

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => $maxTokens,
                    'temperature' => $this->temperature,
                ]);

            if (!$response->successful()) {
                $body = $response->json();
                $message = $body['error']['message'] ?? $response->body();
                // 429/400 with quota or billing = OpenAI account limit, not application bug
                $maskedKey = substr($this->apiKey, 0, 7) . '...' . substr($this->apiKey, -4);
                Log::error('OpenAI API failure debug', [
                    'status' => $response->status(),
                    'key_used' => $maskedKey,
                    'error_message' => $message,
                    'full_body' => $body
                ]);
                Log::warning('OpenAI API error (check account quota/billing at platform.openai.com)', ['status' => $response->status(), 'body' => $body]);
                return ['success' => false, 'error' => $message];
            }

            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '';

            return ['success' => true, 'text' => trim($text)];
        } catch (\Exception $e) {
            Log::error('OpenAI request failed', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}