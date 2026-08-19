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

        $response = Http::withHeaders([
            'x-api-key' => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => config('services.anthropic.model', 'claude-sonnet-4-6'),
            'max_tokens' => 1000,
            'system' => $systemPrompt,
            'messages' => $validated['messages'],
        ]);

        $textBlocks = collect($response->json('content', []))
            ->where('type', 'text')
            ->pluck('text')
            ->implode("\n");

        return response()->json([
            'reply' => $textBlocks ?: "I couldn't process that - please use the contact form.",
        ]);
    }
}
