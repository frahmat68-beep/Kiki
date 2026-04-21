<?php

namespace App\Http\Controllers;

use App\Services\Ai\NvidiaAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChatbotController extends Controller
{
    protected NvidiaAiService $aiService;

    public function __construct(NvidiaAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle the chat message request.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->input('message');
        
        // Get or initialize chat history from session
        $history = Session::get('chatbot_history', []);
        
        // Add user message to history
        $history[] = ['role' => 'user', 'content' => $userMessage];
        
        // Keep history manageable (last 10 messages)
        if (count($history) > 11) { // 10 + 1 (system prompt handled in service)
            $history = array_slice($history, -10);
        }

        // Get AI response
        $aiResponse = $this->aiService->chat($history);
        
        // Add AI response to history
        $history[] = ['role' => 'assistant', 'content' => $aiResponse];
        
        // Save back to session
        Session::put('chatbot_history', $history);

        return response()->json([
            'message' => $aiResponse,
        ]);
    }

    /**
     * Reset the chat history.
     */
    public function reset()
    {
        Session::forget('chatbot_history');
        return response()->json(['status' => 'success']);
    }
}
