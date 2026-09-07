<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Pest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    /**
     * Handles chat messages from the widget. The API key lives only in .env -
     * the browser never sees it. Builds the system prompt from live DB content
     * so the bot always matches whatever is on the site.
     */
    public function respond(Request $request)
    {
        $validated = $request->validate([
            'messages' => 'required|array',
        ]);

        $plans = Plan::all()->map(fn ($p) => "{$p->name}: R{$p->price}/{$p->billing_cycle} - {$p->description}")->implode("\n");
        $pests = Pest::pluck('name')->implode(', ');

        $systemPrompt = "You are the on-site assistant for SP Pest Control. Answer only using this "
            . "information, in a concise, professional tone.\n\nPESTS TREATED: {$pests}\n\nPLANS:\n{$plans}\n\n"
            . "If the visitor wants to book, ask for name, phone, and pest/service, then let them know "
            . "a technician will follow up.";

        // Gemini's API doesn't have a distinct "system" role like Anthropic's —
        // instead the system prompt goes in a top-level systemInstruction block,
        // and the conversation itself is "contents", with each message's role
        // being "user" or "model" (not "assistant" like most other APIs) and
        // the text nested under parts: [{ text: "..." }] instead of a flat string.
        $contents = collect($validated['messages'])->map(fn ($m) => [
            'role' => $m['role'] === 'assistant' ? 'model' : 'user',
            'parts' => [['text' => $m['content']]],
        ])->all();

        $model = config('services.gemini.model', 'gemini-2.0-flash');

        $response = Http::withHeaders([
            'content-type' => 'application/json',
        ])->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . config('services.gemini.key'),
            [
                'systemInstruction' => [
                    'parts' => [['text' => $systemPrompt]],
                ],
                'contents' => $contents,
            ]
        );

        $reply = $response->json('candidates.0.content.parts.0.text');

        if (! $reply) {
            \Log::warning('Gemini chatbot request failed', $response->json() ?? ['status' => $response->status()]);
        }

        return response()->json([
            'reply' => $reply ?: "I couldn't process that - please use the contact form.",
        ]);
    }
}
