<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and apply production-grade HTTP security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Clickjacking Defense
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Legacy XSS filter for older user-agents
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer Privacy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Hardware Feature Restriction
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content Security Policy (Compatible with Alpine.js, GA4, AdSense, and Adsterra)
        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://pagead2.googlesyndication.com https://adservice.google.com https://*.profitableratecpmnetwork.com https://*.highperformanceformat.com https://*.topcreativeformat.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: blob: https: https://www.google-analytics.com https://pagead2.googlesyndication.com https://googleads.g.doubleclick.net",
            "connect-src 'self' https: https://www.google-analytics.com https://analytics.google.com https://region1.google-analytics.com https://pagead2.googlesyndication.com",
            "frame-src 'self' https: https://googleads.g.doubleclick.net https://tpc.googlesyndication.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ];
        $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));

        // Strict search engine exclusion for administrative and diagnostic endpoints
        if ($request->is('admin*') || $request->is('up')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive');
        }

        // HSTS when serving over HTTPS
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
