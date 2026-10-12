<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Str;

final class SafeContentRenderer
{
    /**
     * Allowed HTML tags for rendered CMS content.
     */
    private const ALLOWED_TAGS = '<p><br><strong><em><b><i><u><h1><h2><h3><h4><h5><h6><ul><ol><li><a><blockquote><code><pre><hr><table><thead><tbody><tr><th><td>';

    /**
     * Parse markdown to HTML safely with strict tag and attribute sanitization.
     */
    public static function render(?string $markdown): string
    {
        if (empty($markdown)) {
            return '';
        }

        // Convert markdown to HTML allowing HTML tags so we can sanitize them explicitly
        $html = Str::markdown($markdown, [
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);

        // Strip scripts, styles, iframes, and objects completely with their contents
        $html = preg_replace('/<(script|style|iframe|object|embed)\b[^>]*>.*?<\/\1>/is', '', $html);
        $html = preg_replace('/<script\b[^>]*>/is', '', $html);

        // Strip disallowed tags while keeping allowed formatting tags
        $sanitized = strip_tags($html, self::ALLOWED_TAGS);

        // Disallow dangerous attributes like javascript: or on* event listeners
        $sanitized = preg_replace('/javascript:/i', '', $sanitized);
        $sanitized = preg_replace('/\s*on\w+\s*=\s*(["\']).*?\1/i', '', $sanitized);
        $sanitized = preg_replace('/\s*on\w+\s*=\s*[^ >]+/i', '', $sanitized);

        return $sanitized;
    }
}
