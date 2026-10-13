<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PageView;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnalyticsTracker
{
    /**
     * Record a pageview in the database.
     */
    public function record(Request $request, Response $response): void
    {
        // Only track successful GET responses for HTML pages
        if ($request->method() !== 'GET' || $response->getStatusCode() !== 200) {
            return;
        }

        // Ignore AJAX, JSON, and prefetch requests
        if ($request->ajax() || $request->wantsJson() || $request->header('Sec-Purpose') === 'prefetch') {
            return;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');
        if ($contentType !== '' && !str_contains(strtolower($contentType), 'text/html')) {
            return;
        }

        // Ignore authenticated admin users to avoid polluting traffic metrics with internal activity
        if ($request->user() && method_exists($request->user(), 'isAdmin') && $request->user()->isAdmin()) {
            return;
        }

        $path = trim($request->path(), '/');

        // Ignore admin panel, internal health checks, API endpoints, and live assets
        if (
            str_starts_with($path, 'admin') ||
            str_starts_with($path, 'up') ||
            str_starts_with($path, 'livewire') ||
            str_starts_with($path, '_') ||
            $path === 'robots.txt' ||
            $path === 'sitemap.xml' ||
            $path === 'favicon.ico' ||
            str_ends_with($path, '.png') ||
            str_ends_with($path, '.svg') ||
            str_ends_with($path, '.json') ||
            str_ends_with($path, '.webmanifest')
        ) {
            return;
        }

        // Anonymized daily visitor hash (GDPR compliant, zero raw IP storage)
        // Extract genuine visitor IP behind Cloudflare and reverse proxies
        $ip = $request->header('CF-Connecting-IP')
            ?: ($request->header('X-Forwarded-For')
                ?: ($request->header('X-Real-IP') ?: ($request->ip() ?? '127.0.0.1')));

        if (is_string($ip) && str_contains($ip, ',')) {
            $ip = trim(explode(',', $ip)[0]);
        }
        $ip = is_string($ip) && trim($ip) !== '' ? trim($ip) : '127.0.0.1';

        $userAgent = $request->userAgent() ?? '';
        $salt = (string) config('app.key', 'ToolZenoSalt') . '-' . date('Y-m-d');
        $sessionHash = hash('sha256', $ip . '|' . $userAgent . '|' . $salt);

        // Detect device, browser, OS, and bots
        $isBot = $this->detectBot($userAgent);
        $deviceType = $this->detectDevice($userAgent);
        $browser = $this->detectBrowser($userAgent);
        $os = $this->detectOs($userAgent);

        // Referrer intelligence
        $rawReferrer = $request->headers->get('referer');
        [$referrerHost, $referrerSource] = $this->parseReferrer($rawReferrer, $request->getHost());

        // Contextual routing params
        $route = $request->route();
        $routeName = $route ? $route->getName() : null;
        $toolSlug = $route ? $route->parameter('slug') : null;
        $category = $route ? $route->parameter('category') : null;
        $locale = $route ? $route->parameter('locale', app()->getLocale()) : app()->getLocale();

        // If toolSlug is not in route params, check if path matches a tool route
        if (!$toolSlug && preg_match('#tools/([a-z0-9\-]+)#', $path, $matches)) {
            $toolSlug = $matches[1];
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('page_views')) {
                PageView::create([
                    'session_hash' => $sessionHash,
                    'url' => substr($request->fullUrl(), 0, 255),
                    'path' => substr($path, 0, 255),
                    'route_name' => $routeName ? substr($routeName, 0, 100) : null,
                    'tool_slug' => $toolSlug ? substr($toolSlug, 0, 100) : null,
                    'category' => $category ? substr($category, 0, 50) : null,
                    'locale' => substr((string) $locale, 0, 10),
                    'referrer' => $rawReferrer ? substr($rawReferrer, 0, 255) : null,
                    'referrer_host' => $referrerHost ? substr($referrerHost, 0, 100) : null,
                    'referrer_source' => substr($referrerSource, 0, 50),
                    'device_type' => $deviceType,
                    'browser' => $browser,
                    'os' => $os,
                    'is_bot' => $isBot,
                    'viewed_date' => now()->toDateString(),
                    'created_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Silently fail if DB write encounters any issue to never interrupt user experience
            if (!app()->runningUnitTests()) {
                report($e);
            }
        }
    }

    private function detectBot(string $ua): bool
    {
        if (trim($ua) === '') {
            return true;
        }

        $botPattern = '/bot|crawl|spider|slurp|facebookexternalhit|whatsapp|telegram|preview|curl|wget|python|postman|headless|lighthouse|pagespeed|uptime|pingdom|monitor|check_http|nagios|datadog|semrush|ahrefs|dotbot|petalbot|yandex|baidu|bingbot|duckduck|screaming frog|claudebot|gptbot|chatgpt|anthropic|applebot|amazonbot|ccbot|bytespider|phantomjs|selenium|playwright|puppeteer|httpclient|guzzle|okhttp|java\/|go-http-client|aiohttp|axios|node-fetch|scanner|censys|shodan/i';

        return (bool) preg_match($botPattern, $ua);
    }

    private function detectDevice(string $ua): string
    {
        if (preg_match('/ipad|tablet|playbook|silk/i', $ua)) {
            return 'tablet';
        }
        if (preg_match('/mobile|iphone|ipod|android|blackberry|opera mini|iemobile/i', $ua)) {
            return 'mobile';
        }
        return 'desktop';
    }

    private function detectOs(string $ua): string
    {
        $uaLower = strtolower($ua);
        if (str_contains($uaLower, 'windows')) return 'Windows';
        if (str_contains($uaLower, 'macintosh') || str_contains($uaLower, 'mac os x')) return 'macOS';
        if (str_contains($uaLower, 'iphone') || str_contains($uaLower, 'ipad') || str_contains($uaLower, 'ipod')) return 'iOS';
        if (str_contains($uaLower, 'android')) return 'Android';
        if (str_contains($uaLower, 'linux')) return 'Linux';
        if (str_contains($uaLower, 'cros')) return 'ChromeOS';
        return 'Other';
    }

    private function detectBrowser(string $ua): string
    {
        $uaLower = strtolower($ua);
        if (str_contains($uaLower, 'edg/')) return 'Edge';
        if (str_contains($uaLower, 'opr/') || str_contains($uaLower, 'opera')) return 'Opera';
        if (str_contains($uaLower, 'samsungbrowser')) return 'Samsung Internet';
        if (str_contains($uaLower, 'chrome/') && !str_contains($uaLower, 'edg/')) return 'Chrome';
        if (str_contains($uaLower, 'firefox/')) return 'Firefox';
        if (str_contains($uaLower, 'safari/') && !str_contains($uaLower, 'chrome/')) return 'Safari';
        return 'Other';
    }

    /**
     * @return array{0: string|null, 1: string}
     */
    private function parseReferrer(?string $referrer, string $currentHost): array
    {
        if (empty($referrer)) {
            return [null, 'Direct / Bookmark'];
        }

        $parsed = parse_url($referrer);
        $host = $parsed['host'] ?? null;

        if (!$host || strtolower($host) === strtolower($currentHost)) {
            return [null, 'Direct / Internal'];
        }

        $hostLower = strtolower($host);

        if (str_contains($hostLower, 'google.')) return [$host, 'Google Search'];
        if (str_contains($hostLower, 'bing.')) return [$host, 'Bing Search'];
        if (str_contains($hostLower, 'yahoo.')) return [$host, 'Yahoo'];
        if (str_contains($hostLower, 'duckduckgo.')) return [$host, 'DuckDuckGo'];
        if (str_contains($hostLower, 'facebook.') || str_contains($hostLower, 'fb.com')) return [$host, 'Facebook'];
        if (str_contains($hostLower, 'twitter.') || str_contains($hostLower, 't.co') || str_contains($hostLower, 'x.com')) return [$host, 'Twitter / X'];
        if (str_contains($hostLower, 'instagram.')) return [$host, 'Instagram'];
        if (str_contains($hostLower, 'linkedin.')) return [$host, 'LinkedIn'];
        if (str_contains($hostLower, 'reddit.')) return [$host, 'Reddit'];
        if (str_contains($hostLower, 'youtube.')) return [$host, 'YouTube'];
        if (str_contains($hostLower, 'github.')) return [$host, 'GitHub'];

        return [$host, 'Referral (' . $host . ')'];
    }
}
