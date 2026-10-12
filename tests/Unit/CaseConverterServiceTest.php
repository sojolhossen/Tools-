<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Tools\CaseConverterService;
use PHPUnit\Framework\TestCase;

final class CaseConverterServiceTest extends TestCase
{
    private CaseConverterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CaseConverterService();
    }

    public function test_it_converts_to_uppercase(): void
    {
        $this->assertSame('HELLO WORLD', $this->service->toUppercase('hello world'));
    }

    public function test_it_converts_to_lowercase(): void
    {
        $this->assertSame('hello world', $this->service->toLowercase('HELLO WORLD'));
    }

    public function test_it_converts_to_title_case(): void
    {
        $this->assertSame('The Quick Brown Fox', $this->service->toTitleCase('the quick brown fox'));
    }

    public function test_it_converts_to_sentence_case(): void
    {
        $input = 'first sentence. second sentence? third sentence!';
        $expected = 'First sentence. Second sentence? Third sentence!';
        $this->assertSame($expected, $this->service->toSentenceCase($input));
    }

    public function test_it_converts_to_camel_case(): void
    {
        $this->assertSame('helloWorldTest', $this->service->toCamelCase('hello-world_test'));
    }

    public function test_it_converts_to_snake_case(): void
    {
        $this->assertSame('hello_world_test', $this->service->toSnakeCase('Hello World-Test'));
    }

    public function test_it_converts_to_kebab_case(): void
    {
        $this->assertSame('hello-world-test', $this->service->toKebabCase('Hello World_Test'));
    }

    public function test_it_preserves_unicameral_scripts_like_bangla_without_corruption(): void
    {
        $bangla = 'বাংলা ভাষা ও সাহিত্য';
        $this->assertSame($bangla, $this->service->toUppercase($bangla));
        $this->assertSame($bangla, $this->service->toLowercase($bangla));
    }
}
