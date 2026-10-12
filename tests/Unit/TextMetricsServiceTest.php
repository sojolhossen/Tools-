<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Tools\TextMetricsService;
use PHPUnit\Framework\TestCase;

final class TextMetricsServiceTest extends TestCase
{
    private TextMetricsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TextMetricsService();
    }

    public function test_it_counts_words_in_english(): void
    {
        $text = 'The quick brown fox jumps over the lazy dog.';
        $this->assertSame(9, $this->service->countWords($text));
    }

    public function test_it_counts_words_in_bangla(): void
    {
        $text = 'আমাদের সোনার বাংলাদেশ একটি সুন্দর দেশ।';
        $this->assertSame(6, $this->service->countWords($text));
    }

    public function test_it_handles_empty_and_whitespace_input(): void
    {
        $this->assertSame(0, $this->service->countWords(''));
        $this->assertSame(0, $this->service->countWords("   \n\t   "));
        $this->assertSame(0, $this->service->countCharacters('', true));
        $this->assertSame(0, $this->service->countCharacters('   ', false));
        $this->assertSame(0, $this->service->countSentences(''));
        $this->assertSame(0, $this->service->countParagraphs(''));
    }

    public function test_it_counts_characters_with_and_without_spaces(): void
    {
        $text = 'Hello World';
        $this->assertSame(11, $this->service->countCharacters($text, true));
        $this->assertSame(10, $this->service->countCharacters($text, false));

        $bangla = 'বাংলা ভাষা';
        $this->assertSame(10, $this->service->countCharacters($bangla, true));
        $this->assertSame(9, $this->service->countCharacters($bangla, false));
    }

    public function test_it_counts_sentences_with_english_and_bangla_punctuation(): void
    {
        $english = 'First sentence! Is this second? Yes, third.';
        $this->assertSame(3, $this->service->countSentences($english));

        $bangla = 'এটি প্রথম বাক্য। এটি কি দ্বিতীয় বাক্য? হ্যাঁ, এটি তৃতীয় বাক্য!';
        $this->assertSame(3, $this->service->countSentences($bangla));
    }

    public function test_it_counts_paragraphs(): void
    {
        $text = "Paragraph 1\n\nParagraph 2\n\n\nParagraph 3";
        $this->assertSame(3, $this->service->countParagraphs($text));
    }

    public function test_it_calculates_reading_and_speaking_times(): void
    {
        $reading = $this->service->calculateReadingTime(400); // 400 words at 200 wpm = 2 mins
        $this->assertSame(2, $reading['minutes']);
        $this->assertSame(0, $reading['seconds']);
        $this->assertSame('2m 0s', $reading['formatted']);

        $speaking = $this->service->calculateSpeakingTime(130); // 130 words at 130 wpm = 1 min
        $this->assertSame(1, $speaking['minutes']);
        $this->assertSame(0, $speaking['seconds']);

        $empty = $this->service->calculateReadingTime(0);
        $this->assertSame('0s', $empty['formatted']);
    }
}
