<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
    }

    public function generateProductDescription(array $productData): ?array
    {
        try {
            $prompt = "Genera una descripción profesional de producto en formato estructurado.\n\n";
            $prompt .= "Nombre: {$productData['name']}\n";
            if (!empty($productData['brand'])) {
                $prompt .= "Marca: {$productData['brand']}\n";
            }
            if (!empty($productData['type'])) {
                $prompt .= "Tipo: {$productData['type']}\n";
            }
            if (!empty($productData['current'])) {
                $prompt .= "Descripción actual: {$productData['current']}\n";
            }

            $prompt .= "\nResponde EXACTAMENTE en este formato (sin preambuló):\n";
            $prompt .= "DESCRIPCIÓN CORTA: [máx 100 caracteres, descripción breve]\n";
            $prompt .= "DESCRIPCIÓN LARGA: [descripción completa, máx 500 caracteres]";

            $response = Http::withToken("Bearer {$this->apiKey}")
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 300,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                return $this->parseDescription($content);
            }

            Log::error("OpenAI request failed: {$response->status()} {$response->body()}");
            return null;
        } catch (\Exception $e) {
            Log::error("OpenAI error: {$e->getMessage()}");
            return null;
        }
    }

    private function parseDescription(string $content): ?array
    {
        $result = [
            'short_description' => null,
            'description' => null,
        ];

        if (preg_match('/DESCRIPCIÓN\s+CORTA:\s*(.+?)(?:\n|DESCRIPCIÓN\s+LARGA:)/i', $content, $matches)) {
            $result['short_description'] = trim($matches[1]);
        }

        if (preg_match('/DESCRIPCIÓN\s+LARGA:\s*(.+?)$/i', $content, $matches)) {
            $result['description'] = trim($matches[1]);
        }

        return ($result['short_description'] || $result['description']) ? $result : null;
    }
}
