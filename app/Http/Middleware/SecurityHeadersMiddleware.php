<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add standard security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        
        if ($request->secure() || config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $isProduction = config('app.env') === 'production';

        $scriptSources = ["'self'"];
        $styleSources = ["'self'", 'https://fonts.googleapis.com'];
        $connectSources = ["'self'"];

        if (! $isProduction) {
            $scriptSources[] = "'unsafe-inline'";
            $styleSources[] = "'unsafe-inline'";
            $scriptSources[] = 'http://localhost:*';
            $scriptSources[] = 'http://127.0.0.1:*';
            $connectSources[] = 'ws://localhost:*';
            $connectSources[] = 'ws://127.0.0.1:*';
            $connectSources[] = 'http://localhost:*';
            $connectSources[] = 'http://127.0.0.1:*';
        }

        $response->headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            'script-src ' . implode(' ', $scriptSources),
            'style-src ' . implode(' ', $styleSources),
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: http: https:",
            "media-src 'self' data: blob:",
            "frame-src 'self' https://www.youtube.com https://player.vimeo.com",
            'connect-src ' . implode(' ', $connectSources),
            "object-src 'none'",
            "base-uri 'self'",
            "frame-ancestors 'self'",
        ]));

        return $response;
    }
}
