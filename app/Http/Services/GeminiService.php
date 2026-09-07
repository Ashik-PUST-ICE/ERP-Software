<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $model;
    protected float $temperature;
    protected int $maxTokens;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';

    public function __construct(?string $apiKey = null, ?string $model = null, ?float $temperature = null, ?int $maxTokens = null)
    {
        $this->apiKey = $apiKey ?? getOption('gemini_api_key', '');
        $this->model = $model ?? getOption('gemini_model', config('ai.gemini_default_model', 'gemini-1.5-flash'));
        $this->temperature = $temperature ?? (float) getOption('openai_temperature', config('ai.openai_default_temperature', 0.7));
        $this->maxTokens = $maxTokens ?? (int) getOption('openai_max_tokens', config('ai.openai_default_max_tokens', 1000));
    }

    public function isConfigured(): bool
    {
        return !empty(trim($this->apiKey));
    }

    /**
     * Generate text using Google Gemini API.
     *
     * @return array{success: bool, text?: string, error?: string}
     */
    public function generateContent(string $prompt, ?int $maxTokens = null): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => __('Gemini API key is not configured. Please set it in Super Admin → Settings → AI Settings.')];
        }

        $maxTokens = $maxTokens ?? $this->maxTokens;
        $models = config('ai.gemini_models', ['gemini-1.5-flash']);
        if (!in_array($this->model, $models, true)) {
            $this->model = $models[0] ?? 'gemini-1.5-flash';
        }

        try {
            $url = "{$this->baseUrl}/models/{$this->model}:generateContent?key=" . urlencode($this->apiKey);
            $response = Http::timeout(60)
                ->post($url, [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => $maxTokens,
                        'temperature'     => $this->temperature,
                    ],
                ]);

            if (!$response->successful()) {
                $body = $response->json();
                $message = $body['error']['message'] ?? $response->body();
                Log::warning('Gemini API error', ['status' => $response->status(), 'body' => $body]);
                return ['success' => false, 'error' => $message];
            }

            $data = $response->json();
            $candidates = $data['candidates'] ?? [];
            if (empty($candidates)) {
                $reason = $data['promptFeedback']['blockReason'] ?? 'empty_response';
                return ['success' => false, 'error' => __('Response blocked or empty. Reason: :reason.', ['reason' => $reason])];
            }
            $parts = $candidates[0]['content']['parts'] ?? null;
            if (empty($parts)) {
                $reason = $candidates[0]['finishReason'] ?? 'no_content';
                return ['success' => false, 'error' => __('Response blocked or empty. Reason: :reason.', ['reason' => $reason])];
            }
            $text = $parts[0]['text'] ?? '';
            return ['success' => true, 'text' => trim($text)];
        } catch (\Exception $e) {
            Log::error('Gemini request failed', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
