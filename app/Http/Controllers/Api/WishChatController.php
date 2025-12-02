<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WishChatController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        try {
            $prompt = 'You are Santa Claus.
            Speak kindly and joyfully as santa would.
            You may only answer questions related to christmas and wishes and the holiday seasons.
            Keep every response under 30 words.
            If asked about anything not related to christmas or wishes, respond with \"Ho Ho Ho! Let\'s talk about Christmas wishes!\".'
            .$data['prompt'];

            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
                ->generateContent($prompt);

            return response()->json([
                'success' => true,
                'response' => $result->text(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error generating content: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to generate content.',
            ], 500);
        }
    }
}
