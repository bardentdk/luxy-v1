<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GroqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroqController extends Controller
{
    public function __construct(private readonly GroqService $groqService)
    {}

    public function generate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'field'             => ['required', 'string'],
            'title'             => ['required', 'string', 'max:255'],
            'category'          => ['nullable', 'string'],
            'duration'          => ['nullable', 'string'],
            'level'             => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
        ]);

        if (! $this->groqService->isConfigured()) {
            return response()->json([
                'error' => 'Clé API Groq non configurée. Ajoutez GROQ_API_KEY dans votre .env',
            ], 503);
        }

        if (! in_array($data['field'], $this->groqService->supportedFields())) {
            return response()->json([
                'error' => 'Champ "' . $data['field'] . '" non supporté.',
            ], 422);
        }

        try {
            $content = $this->groqService->generate($data['field'], [
                'title'             => $data['title'],
                'category'          => $data['category']          ?? '',
                'duration'          => $data['duration']          ?? '',
                'level'             => $data['level']             ?? '',
                'short_description' => $data['short_description'] ?? '',
            ]);

            return response()->json(['content' => $content]);

        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 502);
        }
    }
}