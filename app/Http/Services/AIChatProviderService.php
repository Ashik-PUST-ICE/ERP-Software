<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIChatProviderService
{
    public function provider(): string
    {
        $provider = (string) getOption('ai_provider', config('ai.default_provider', 'openai'));
        return array_key_exists($provider, config('ai.providers', [])) ? $provider : 'openai';
    }

    public function model(): string
    {
        $provider = $this->provider();
        $settings = config("ai.providers.{$provider}", []);
        return (string) getOption('ai_model', getOption($provider . '_model', $settings['default_model'] ?? ''));
    }

    public function label(): string
    {
        return (string) config('ai.providers.' . $this->provider() . '.label', ucfirst($this->provider()));
    }

    public function isConfigured(): bool
    {
        $option = config('ai.providers.' . $this->provider() . '.api_key_option');
        return $option && filled(trim((string) getOption($option, '')));
    }

    public function generateChat(array $messages, ?int $maxTokens = null): array
    {
        if (! $this->isConfigured()) {
            return ['success' => false, 'error' => $this->label() . ' API key is not configured.'];
        }

        return match ($this->provider()) {
            'gemini' => $this->generateGemini($messages, $maxTokens),
            'anthropic' => $this->generateAnthropic($messages, $maxTokens),
            default => $this->generateOpenAICompatible($messages, $maxTokens),
        };
    }

    private function generateOpenAICompatible(array $messages, ?int $maxTokens): array
    {
        $provider = $this->provider();
        $config = config("ai.providers.{$provider}");
        $key = getOption($config['api_key_option'], '');
        try {
            $response = Http::withToken($key)->timeout(90)->post($config['base_url'] . '/chat/completions', [
                'model' => $this->model(),
                'messages' => $messages,
                'max_tokens' => $maxTokens ?? (int) getOption('openai_max_tokens', 1000),
                'temperature' => (float) getOption('openai_temperature', 0.7),
            ]);
            if (! $response->successful()) {
                return $this->failure($response->json('error.message') ?: $response->body());
            }
            return ['success' => true, 'text' => trim((string) $response->json('choices.0.message.content', ''))];
        } catch (\Throwable $exception) {
            Log::error('AI compatible provider request failed', ['provider' => $provider, 'error' => $exception->getMessage()]);
            return $this->failure('The AI provider could not be reached.');
        }
    }

    private function generateGemini(array $messages, ?int $maxTokens): array
    {
        $config = config('ai.providers.gemini');
        $contents = array_map(fn (array $message): array => [
            'role' => $message['role'] === 'assistant' ? 'model' : 'user',
            'parts' => [['text' => $message['content']]],
        ], array_filter($messages, fn (array $message): bool => $message['role'] !== 'system'));
        $system = collect($messages)->firstWhere('role', 'system')['content'] ?? null;
        try {
            $payload = ['contents' => array_values($contents), 'generationConfig' => ['maxOutputTokens' => $maxTokens ?? 1000, 'temperature' => (float) getOption('openai_temperature', 0.7)]];
            if ($system) $payload['systemInstruction'] = ['parts' => [['text' => $system]]];
            $response = Http::timeout(90)->post($config['base_url'] . '/models/' . $this->model() . ':generateContent?key=' . urlencode((string) getOption('gemini_api_key', '')), $payload);
            if (! $response->successful()) return $this->failure($response->json('error.message') ?: $response->body());
            return ['success' => true, 'text' => trim((string) $response->json('candidates.0.content.parts.0.text', ''))];
        } catch (\Throwable $exception) {
            Log::error('Gemini chatbot request failed', ['error' => $exception->getMessage()]);
            return $this->failure('Gemini could not be reached.');
        }
    }

    private function generateAnthropic(array $messages, ?int $maxTokens): array
    {
        $config = config('ai.providers.anthropic');
        $system = collect($messages)->firstWhere('role', 'system')['content'] ?? null;
        $messages = array_values(array_filter($messages, fn (array $message): bool => $message['role'] !== 'system'));
        try {
            $request = Http::withHeaders(['x-api-key' => getOption('anthropic_api_key', ''), 'anthropic-version' => '2023-06-01'])->timeout(90);
            $payload = ['model' => $this->model(), 'max_tokens' => $maxTokens ?? 1000, 'messages' => $messages];
            if ($system) $payload['system'] = $system;
            $response = $request->post($config['base_url'] . '/messages', $payload);
            if (! $response->successful()) return $this->failure($response->json('error.message') ?: $response->body());
            return ['success' => true, 'text' => trim((string) $response->json('content.0.text', ''))];
        } catch (\Throwable $exception) {
            Log::error('Anthropic chatbot request failed', ['error' => $exception->getMessage()]);
            return $this->failure('Anthropic could not be reached.');
        }
    }

    private function failure(string $message): array
    {
        return ['success' => false, 'error' => $message ?: 'AI request failed.'];
    }
}
