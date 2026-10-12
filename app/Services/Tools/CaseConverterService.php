<?php

declare(strict_types=1);

namespace App\Services\Tools;

final class CaseConverterService
{
    /**
     * Convert to UPPERCASE using multi-byte UTF-8 string functions.
     */
    public function toUppercase(string $text): string
    {
        return mb_strtoupper($text, 'UTF-8');
    }

    /**
     * Convert to lowercase using multi-byte UTF-8 string functions.
     */
    public function toLowercase(string $text): string
    {
        return mb_strtolower($text, 'UTF-8');
    }

    /**
     * Convert to Title Case.
     */
    public function toTitleCase(string $text): string
    {
        return mb_convert_case($text, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Convert to Sentence case.
     * Capitalizes first character of each sentence after standard punctuation (. ! ? \x{0964}).
     */
    public function toSentenceCase(string $text): string
    {
        if (trim($text) === '') {
            return '';
        }

        // Lowercase everything first
        $lower = mb_strtolower($text, 'UTF-8');

        // Capitalize the first letter of each sentence
        $pattern = '/(^|[.!?\x{0964}]\s+)([\p{Ll}])/u';
        $result = preg_replace_callback($pattern, function (array $matches): string {
            return $matches[1] . mb_strtoupper($matches[2], 'UTF-8');
        }, $lower);

        return $result ?? $lower;
    }

    /**
     * Convert to camelCase.
     */
    public function toCamelCase(string $text): string
    {
        $words = preg_split('/[_\-\s]+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        if ($words === false || count($words) === 0) {
            return '';
        }

        $first = mb_strtolower(array_shift($words), 'UTF-8');
        $rest = array_map(function (string $word): string {
            return mb_convert_case($word, MB_CASE_TITLE, 'UTF-8');
        }, $words);

        return $first . implode('', $rest);
    }

    /**
     * Convert to snake_case.
     */
    public function toSnakeCase(string $text): string
    {
        $cleaned = preg_replace('/[_\-\s]+/u', '_', trim($text));
        if ($cleaned === null) {
            return '';
        }
        return mb_strtolower(trim($cleaned, '_'), 'UTF-8');
    }

    /**
     * Convert to kebab-case.
     */
    public function toKebabCase(string $text): string
    {
        $cleaned = preg_replace('/[_\-\s]+/u', '-', trim($text));
        if ($cleaned === null) {
            return '';
        }
        return mb_strtolower(trim($cleaned, '-'), 'UTF-8');
    }
}
