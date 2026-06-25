<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_endpoint_calls_gemini_with_course_context_and_history(): void
    {
        config([
            'services.gemini.key' => 'test-gemini-key',
            'services.gemini.model' => 'gemini-test-model',
        ]);

        $category = Category::create([
            'name' => 'Calidad Alimentaria',
            'slug' => 'calidad-alimentaria',
        ]);

        Course::create([
            'category_id' => $category->id,
            'name' => 'BPM en Alimentos',
            'slug' => 'bpm-alimentos',
            'short_description' => 'Curso practico de buenas practicas de manufactura.',
            'level' => 'basico',
            'status' => 'publicado',
            'price' => 350,
            'duration_weeks' => 8,
        ]);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Tenemos BPM en Alimentos disponible.'],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $response = $this->postJson('/api/chat', [
            'message' => 'Que cursos ofrecen?',
            'history' => [
                ['role' => 'bot', 'text' => 'Hola, soy el asistente.'],
                ['role' => 'user', 'text' => 'Necesito informacion.'],
            ],
        ]);

        $response->assertOk()
            ->assertJson([
                'reply' => 'Tenemos BPM en Alimentos disponible.',
                'status' => 'ok',
                'configured' => true,
            ]);

        Http::assertSent(function (Request $request) {
            $payload = $request->data();
            $systemText = data_get($payload, 'systemInstruction.parts.0.text', '');

            return $request->hasHeader('x-goog-api-key', 'test-gemini-key')
                && str_contains((string) $request->url(), 'models/gemini-test-model:generateContent')
                && str_contains($systemText, 'BPM en Alimentos')
                && data_get($payload, 'contents.0.role') === 'model'
                && data_get($payload, 'contents.1.role') === 'user'
                && data_get($payload, 'contents.2.parts.0.text') === 'Que cursos ofrecen?';
        });
    }

    public function test_chat_endpoint_reports_gemini_not_configured(): void
    {
        config([
            'services.gemini.key' => null,
        ]);

        Http::fake();

        $response = $this->postJson('/api/chat', [
            'message' => 'Hola',
        ]);

        $response->assertStatus(503)
            ->assertJson([
                'status' => 'not_configured',
                'configured' => false,
            ]);

        Http::assertNothingSent();
    }
}
