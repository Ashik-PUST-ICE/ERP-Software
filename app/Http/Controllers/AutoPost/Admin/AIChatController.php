<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\AIChatProviderService;
use App\Http\Services\SubscriptionService;
use App\Models\AIChatConversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIChatController extends Controller
{
    public function __construct(private readonly SubscriptionService $subscriptionService)
    {
    }

    public function history(Request $request): JsonResponse
    {
        $conversation = $this->conversation($request);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'messages' => $conversation->messages()->oldest()->get(['id', 'role', 'content', 'created_at']),
        ]);
    }

    public function send(Request $request, AIChatProviderService $service): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:6000'],
            'conversation_id' => ['nullable', 'integer'],
        ]);

        if (!$service->isConfigured()) {
            return response()->json(['success' => false, 'message' => __('Please configure the selected AI provider first in Settings → AI Settings.')], 503);
        }

        if ((int) getOption('openai_ai_status', 1) !== 1) {
            return response()->json(['success' => false, 'message' => __('AI assistant is currently disabled by administrator.')], 403);
        }

        $currentPackage = $this->subscriptionService->getCurrentPlan($request->user()->id);
        if (!$currentPackage || !$currentPackage->packageable || !$currentPackage->packageable->ai_enabled) {
            return response()->json(['success' => false, 'message' => __('Your current plan does not include AI features. Please upgrade your package.')], 403);
        }

        $conversation = $this->conversation($request, $validated['conversation_id'] ?? null);
        $recentMessages = $conversation->messages()->latest()->limit(12)->get()->reverse()->values();
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are the ERP admin AI assistant. Be concise, friendly, and practical. '
                    . 'Answer in the same language as the admin (Bangla or English). '
                    . 'You may explain how to use this ERP, but do not claim to have changed data or completed an action. '
                    . 'Do not expose API keys, passwords, private employee data, or internal system secrets. '
                    . 'If a question needs live business data, clearly say that this assistant needs the relevant read-only data integration.',
            ],
        ];
        foreach ($recentMessages as $chatMessage) {
            $messages[] = ['role' => $chatMessage->role, 'content' => $chatMessage->content];
        }
        $messages[] = ['role' => 'user', 'content' => $validated['message']];

        $result = $service->generateChat($messages);
        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => __('AI could not respond right now. Please try again.')], 422);
        }

        $conversation->messages()->create(['role' => 'user', 'content' => $validated['message']]);
        $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $result['text'],
            'model' => $service->model(),
        ]);
        $conversation->forceFill(['last_message_at' => now()])->save();

        return response()->json(['success' => true, 'message' => $result['text'], 'conversation_id' => $conversation->id]);
    }

    private function conversation(Request $request, ?int $id = null): AIChatConversation
    {
        $user = $request->user();
        $query = AIChatConversation::where('user_id', $user->id);

        if ($id) {
            $conversation = $query->find($id);
            if ($conversation) {
                return $conversation;
            }
        }

        return AIChatConversation::create([
            'user_id' => $user->id,
            'tenant_id' => $user->tenant_id,
            'title' => __('AI Assistant'),
            'last_message_at' => now(),
        ]);
    }
}
