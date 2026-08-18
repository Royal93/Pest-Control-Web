<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;

// Called by the chatbot widget in the browser. The Claude API key stays server-side.
Route::post('/chatbot', [ChatbotController::class, 'respond'])->name('api.chatbot');
