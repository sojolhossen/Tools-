<?php

declare(strict_types=1);

namespace App\Services\Tools;

final class TextMetricsService
{
    /**
     * Count words supporting international Unicode scripts including Bangla.
     */
    public function countWords(string $text): int
    {
        $trimmed = trim($text);
        if ($trimmed === '') {
            return 0;
        }

        // Match Unicode letters, combining marks (vowel signs \p{M}), and numbers across scripts
        if (preg_match_all('/[\p{L}\p{M}\p{N}_\'-]+/u', $trimmed, $matches)) {
            return count($matches[0]);
        }

        return 0;
    }

    /**
     * Count total characters.
     */
    public function countCharacters(string $text, bool $includeSpaces = true): int
    {
        if ($includeSpaces) {
            return mb_strlen($text, 'UTF-8');
        }

        $withoutSpaces = preg_replace('/\s+/u', '', $text) ?? '';
        return mb_strlen($withoutSpaces, 'UTF-8');
    }

    /**
     * Count sentences supporting standard terminators and Bangla dari (। / \x{0964}).
     */
    public function countSentences(string $text): int
    {
        $trimmed = trim($text);
        if ($trimmed === '') {
            return 0;
        }

        // Split by ., !, ?, or Bengali Dari (। - U+0964) followed by whitespace or end of string
        $sentences = preg_split('/[.!?\x{0964}]+(?:\s+|$)/u', $trimmed, -1, PREG_SPLIT_NO_EMPTY);
        if ($sentences === false) {
            return 0;
        }

        return count($sentences);
    }

    /**
     * Count paragraphs separated by newlines.
     */
    public function countParagraphs(string $text): int
    {
        $trimmed = trim($text);
        if ($trimmed === '') {
            return 0;
        }

        $paragraphs = preg_split('/\R+/u', $trimmed, -1, PREG_SPLIT_NO_EMPTY);
        if ($paragraphs === false) {
            return 0;
        }

        return count($paragraphs);
    }

    /**
     * Calculate estimated reading time in minutes and seconds (assumes 200 words per minute).
     *
     * @return array{minutes: int, seconds: int, formatted: string}
     */
    public function calculateReadingTime(int $words, int $wpm = 200): array
    {
        if ($words <= 0) {
            return ['minutes' => 0, 'seconds' => 0, 'formatted' => '0s'];
        }

        $totalSeconds = (int) ceil(($words / $wpm) * 60);
        $minutes = (int) floor($totalSeconds / 60);
        $seconds = $totalSeconds % 60;

        $formatted = $minutes > 0 ? "{$minutes}m {$seconds}s" : "{$seconds}s";

        return [
            'minutes' => $minutes,
            'seconds' => $seconds,
            'formatted' => $formatted,
        ];
    }

    /**
     * Calculate speaking time (assumes 130 words per minute).
     *
     * @return array{minutes: int, seconds: int, formatted: string}
     */
    public function calculateSpeakingTime(int $words, int $wpm = 130): array
    {
        if ($words <= 0) {
            return ['minutes' => 0, 'seconds' => 0, 'formatted' => '0s'];
        }

        $totalSeconds = (int) ceil(($words / $wpm) * 60);
        $minutes = (int) floor($totalSeconds / 60);
        $seconds = $totalSeconds % 60;

        $formatted = $minutes > 0 ? "{$minutes}m {$seconds}s" : "{$seconds}s";

        return [
            'minutes' => $minutes,
            'seconds' => $seconds,
            'formatted' => $formatted,
        ];
    }

    /**
     * Full metrics summary.
     *
     * @return array<string, mixed>
     */
    public function analyze(string $text): array
    {
        $words = $this->countWords($text);
        $charsWithSpaces = $this->countCharacters($text, true);
        $charsNoSpaces = $this->countCharacters($text, false);
        $sentences = $this->countSentences($text);
        $paragraphs = $this->countParagraphs($text);
        $readingTime = $this->calculateReadingTime($words);
        $speakingTime = $this->calculateSpeakingTime($words);

        return [
            'words' => $words,
            'characters' => $charsWithSpaces,
            'characters_no_spaces' => $charsNoSpaces,
            'sentences' => $sentences,
            'paragraphs' => $paragraphs,
            'reading_time' => $readingTime,
            'speaking_time' => $speakingTime,
        ];
    }
}
