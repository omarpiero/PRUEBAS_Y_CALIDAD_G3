<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiAssistantService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ChatController extends Controller
{
    public function handleChat(Request $request, GeminiAssistantService $assistant)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['sometimes', 'array', 'max:12'],
            'history.*.role' => ['required_with:history', 'in:user,bot'],
            'history.*.text' => ['required_with:history', 'string', 'max:1000'],
        ]);

        $message = trim(strip_tags($validated['message']));
        $history = $validated['history'] ?? [];

        try {
            return response()->json([
                'reply' => $assistant->reply($message, $history),
                'status' => 'ok',
                'configured' => true,
            ]);
        } catch (RuntimeException $e) {
            if ($e->getMessage() !== 'Gemini API key is not configured.') {
                throw $e;
            }

            Log::warning('Gemini chat requested without API key configured.');

            return response()->json([
                'reply' => 'El asistente IA aun no esta configurado. Revisa GEMINI_API_KEY en el archivo .env.',
                'status' => 'not_configured',
                'configured' => false,
            ], 503);
        } catch (RequestException $e) {
            $status = $e->response?->status();
            [$reply, $code] = match ($status) {
                401, 403 => ['El asistente no pudo autenticar la clave de Gemini. Revisa GEMINI_API_KEY en el archivo .env.', 'auth_error'],
                429 => ['Gemini esta recibiendo demasiadas solicitudes. Intenta nuevamente en unos minutos.', 'rate_limited'],
                default => ['Hubo un error al conectar con Google Gemini. Por favor, intenta de nuevo.', 'provider_error'],
            };

            return response()->json([
                'reply' => $reply,
                'status' => $code,
                'configured' => true,
                'upstream_status' => $status,
            ], 503);
        } catch (\Throwable $e) {
            Log::error('Unexpected Gemini chat error.', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'reply' => 'Ocurrio un error inesperado al procesar tu solicitud.',
                'status' => 'error',
                'configured' => (bool) config('services.gemini.key'),
            ], 500);
        }
    }
}
