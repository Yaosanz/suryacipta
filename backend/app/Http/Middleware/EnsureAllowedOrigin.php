<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAllowedOrigin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('security.require_origin_header')) {
            return $next($request);
        }

        $origin = $this->normalizeOrigin($request->headers->get('Origin'));
        $allowedOrigins = array_map(
            fn (string $allowed): ?string => $this->normalizeOrigin($allowed),
            config('cors.allowed_origins', []),
        );

        if ($origin === null || ! in_array($origin, $allowedOrigins, true)) {
            return new JsonResponse([
                'message' => 'Origin permintaan tidak diizinkan.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    private function normalizeOrigin(?string $origin): ?string
    {
        if ($origin === null || $origin === '') {
            return null;
        }

        $parts = parse_url($origin);

        if (! is_array($parts)
            || ! isset($parts['scheme'], $parts['host'])
            || ! in_array(strtolower($parts['scheme']), ['http', 'https'], true)
            || isset($parts['path'], $parts['query'], $parts['fragment'], $parts['user'], $parts['pass'])) {
            return null;
        }

        $port = isset($parts['port']) ? ':'.$parts['port'] : '';

        return strtolower($parts['scheme']).'://'.strtolower($parts['host']).$port;
    }
}
