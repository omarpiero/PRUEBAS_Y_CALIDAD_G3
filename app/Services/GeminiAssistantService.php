<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiAssistantService
{
    public function reply(string $message, array $history = []): string
    {
        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');

        if (! $apiKey) {
            throw new \RuntimeException('Gemini API key is not configured.');
        }

        $response = Http::acceptJson()
            ->asJson()
            ->timeout(30)
            ->retry(2, 350)
            ->withOptions([
                'verify' => $this->tlsVerifyOption(),
            ])
            ->withHeaders([
                'x-goog-api-key' => $apiKey,
            ])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                'systemInstruction' => [
                    'parts' => [
                        ['text' => $this->systemPrompt()],
                    ],
                ],
                'contents' => [
                    ...$this->formatHistory($history),
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $message],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.55,
                    'topP' => 0.9,
                    'maxOutputTokens' => 650,
                ],
            ]);

        if (! $response->successful()) {
            Log::warning('Gemini request failed.', [
                'status' => $response->status(),
                'reason' => Str::limit($response->body(), 500),
            ]);

            $response->throw();
        }

        return trim((string) data_get(
            $response->json(),
            'candidates.0.content.parts.0.text',
            'No pude generar una respuesta en este momento.'
        ));
    }

    private function systemPrompt(): string
    {
        return implode("\n", [
            "Eres el asistente virtual de JM y JS Alimentos, empresa de Huancayo, Peru.",
            "Responde siempre en espanol, con tono profesional, amable, breve y util.",
            "Ayudas a visitantes y alumnos con cursos, precios, duracion, inscripcion, pagos, contacto y temas de calidad e inocuidad alimentaria.",
            "No inventes precios, fechas, descuentos ni cursos. Si la informacion no esta en el contexto, ofrece orientar y sugiere contactar a la empresa.",
            "No pidas datos sensibles innecesarios. Si el usuario quiere comprar o inscribirse, guialo a la pagina de cursos o checkout.",
            "",
            "Contexto disponible de la plataforma:",
            $this->courseContext(),
        ]);
    }

    private function courseContext(): string
    {
        try {
            $courses = Course::query()
                ->with('category:id,name')
                ->published()
                ->orderByDesc('is_featured')
                ->orderBy('name')
                ->limit(10)
                ->get(['id', 'category_id', 'name', 'slug', 'short_description', 'level', 'price', 'sale_price', 'sale_start', 'sale_end', 'duration_weeks']);
        } catch (\Throwable $e) {
            Log::notice('Could not load course context for Gemini assistant.', [
                'message' => $e->getMessage(),
            ]);

            return "- Catalogo no disponible desde base de datos en este momento.";
        }

        if ($courses->isEmpty()) {
            return "- Todavia no hay cursos publicados en el catalogo.";
        }

        return $courses
            ->map(function (Course $course): string {
                $description = $course->short_description
                    ? ' Descripcion: ' . Str::limit(strip_tags($course->short_description), 180)
                    : '';

                return sprintf(
                    '- %s | Categoria: %s | Nivel: %s | Duracion: %s semanas | Precio actual: S/ %.2f | URL: /cursos/%s.%s',
                    $course->name,
                    $course->category?->name ?? 'General',
                    $course->level,
                    $course->duration_weeks,
                    $course->effective_price,
                    $course->slug,
                    $description
                );
            })
            ->implode("\n");
    }

    private function formatHistory(array $history): array
    {
        return collect($history)
            ->take(-8)
            ->map(function (array $item): ?array {
                $role = $item['role'] ?? null;
                $text = trim(strip_tags((string) ($item['text'] ?? '')));

                if ($text === '') {
                    return null;
                }

                return [
                    'role' => $role === 'user' ? 'user' : 'model',
                    'parts' => [
                        ['text' => Str::limit($text, 1000, '')],
                    ],
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function tlsVerifyOption(): bool|string
    {
        $caBundle = config('services.gemini.ca_bundle');

        if (is_string($caBundle) && trim($caBundle) !== '') {
            $path = $this->resolvePath($caBundle);

            if (is_file($path)) {
                return $path;
            }
        }

        return (bool) config('services.gemini.verify_ssl', true);
    }

    private function resolvePath(string $path): string
    {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);

        if (preg_match('/^[A-Za-z]:\\\\/', $path) || str_starts_with($path, DIRECTORY_SEPARATOR)) {
            return $path;
        }

        return base_path($path);
    }
}
