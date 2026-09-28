<?php

namespace App\Http\Controllers;

use App\Models\Pest;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Handles chat messages from the widget. The API key lives only in .env,
     * the browser never sees it. The system prompt is built from live database
     * content so the bot always matches whatever is on the site.
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

        // Gemini uses the roles "user" and "model" (not "assistant"), puts the system prompt
        // in a top-level systemInstruction block, and nests each text under parts.
        $contents = collect(array_slice($validated['messages'], -20))->map(fn ($m) => [
            'role' => ($m['role'] ?? 'user') === 'assistant' ? 'model' : 'user',
            'parts' => [['text' => (string) ($m['content'] ?? '')]],
        ])->values()->all();

        // A conversation must start with a user message. The widget's greeting is a model message.
        while ($contents && $contents[0]['role'] === 'model') {
            array_shift($contents);
        }

        $key = (string) config('services.gemini.key');
        $primary = config('services.gemini.model', 'gemini-flash-latest');
        $models = array_values(array_unique(array_filter([
            $primary,
            'gemini-flash-latest',
            'gemini-flash-lite-latest',
            'gemini-3.1-flash-lite',
        ])));

        $payload = [
            'systemInstruction' => ['parts' => [['text' => $systemPrompt]]],
            'contents' => $contents,
        ];

        // Try each model in turn, but never keep the visitor waiting more than about 22 seconds.
        $reply = null;
        $deadline = microtime(true) + 22;

        foreach ($models as $model) {
            $left = $deadline - microtime(true);
            if ($left < 4) {
                break;
            }

            try {
                $response = Http::withHeaders(['x-goog-api-key' => $key])
                    ->timeout((int) min(10, $left))
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", $payload);
            } catch (\Throwable $e) {
                Log::warning('Gemini chatbot connection problem', [
                    'model' => $model,
                    'error' => str_replace($key, '[key]', $e->getMessage()),
                ]);
                continue;
            }

            $reply = $response->json('candidates.0.content.parts.0.text');
            if ($reply) {
                break;
            }

            Log::warning('Gemini chatbot request failed', [
                'model' => $model,
                'status' => $response->status(),
                'error' => $response->json('error.message'),
            ]);
        }

        return response()->json([
            'reply' => $reply ?: "Sorry, I'm having trouble answering right now. Please try again in a moment, use the contact form, or call us on 011 394 1191.",
        ]);
    }
}
