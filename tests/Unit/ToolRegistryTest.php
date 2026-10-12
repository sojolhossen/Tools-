<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\Tools\ToolRegistry;
use Tests\TestCase;

final class ToolRegistryTest extends TestCase
{
    private ToolRegistry $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new ToolRegistry();
    }

    public function test_it_loads_all_tools(): void
    {
        $tools = $this->registry->all();
        $this->assertCount(8, $tools);
    }

    public function test_it_finds_tool_by_slug(): void
    {
        $tool = $this->registry->find('word-counter');
        $this->assertNotNull($tool);
        $this->assertSame('word-counter', $tool->slug);
        $this->assertSame('text', $tool->category);
        $this->assertTrue($tool->isActive());
        $this->assertTrue($tool->supportsLocale('en'));
        $this->assertTrue($tool->supportsLocale('bn'));
    }

    public function test_it_returns_null_for_unknown_slug(): void
    {
        $tool = $this->registry->find('non-existent-tool');
        $this->assertNull($tool);
    }

    public function test_it_filters_by_category(): void
    {
        $textTools = $this->registry->getByCategory('text');
        $this->assertCount(2, $textTools); // word-counter, case-converter

        $imageTools = $this->registry->getByCategory('image');
        $this->assertCount(2, $imageTools); // image-compressor, image-resizer

        $pdfTools = $this->registry->getByCategory('pdf');
        $this->assertCount(1, $pdfTools); // image-to-pdf
    }

    public function test_it_returns_related_tools(): void
    {
        $related = $this->registry->getRelated('word-counter', 3);
        $this->assertLessThanOrEqual(3, $related->count());
        $this->assertFalse($related->has('word-counter'));
    }

    public function test_it_searches_tools(): void
    {
        $results = $this->registry->search('word');
        $this->assertTrue($results->has('word-counter'));

        $resultsPdf = $this->registry->search('pdf');
        $this->assertTrue($resultsPdf->has('image-to-pdf'));
    }
}
