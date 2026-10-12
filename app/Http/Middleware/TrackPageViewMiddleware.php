<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\AnalyticsTracker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageViewMiddleware
{
    public function __construct(
        protected AnalyticsTracker $tracker
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     * This ensures zero latency for visitors!
     */
    public function terminate(Request $request, Response $response): void
    {
        $this->tracker->record($request, $response);
    }
}
