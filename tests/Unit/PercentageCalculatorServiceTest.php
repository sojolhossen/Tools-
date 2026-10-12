<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Tools\PercentageCalculatorService;
use PHPUnit\Framework\TestCase;

final class PercentageCalculatorServiceTest extends TestCase
{
    private PercentageCalculatorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PercentageCalculatorService();
    }

    public function test_it_calculates_percentage_of_a_number(): void
    {
        $this->assertEquals(30.0, $this->service->percentageOf(15.0, 200.0));
        $this->assertEquals(0.0, $this->service->percentageOf(0.0, 200.0));
        $this->assertEquals(0.0, $this->service->percentageOf(50.0, 0.0));
    }

    public function test_it_calculates_percentage_increase(): void
    {
        $result = $this->service->percentageChange(50.0, 75.0);
        $this->assertSame(50.0, $result['percentage_change']);
        $this->assertSame(25.0, $result['difference']);
        $this->assertSame('increase', $result['direction']);
        $this->assertNull($result['error']);
    }

    public function test_it_calculates_percentage_decrease(): void
    {
        $result = $this->service->percentageChange(100.0, 75.0);
        $this->assertSame(25.0, $result['percentage_change']);
        $this->assertSame(-25.0, $result['difference']);
        $this->assertSame('decrease', $result['direction']);
        $this->assertNull($result['error']);
    }

    public function test_it_handles_division_by_zero_safely(): void
    {
        $result = $this->service->percentageChange(0.0, 50.0);
        $this->assertSame(0.0, $result['percentage_change']);
        $this->assertSame('none', $result['direction']);
        $this->assertNotNull($result['error']);
    }

    public function test_it_calculates_discount_and_final_price(): void
    {
        $result = $this->service->discountAndFinalPrice(1000.0, 20.0);
        $this->assertSame(1000.0, $result['original_price']);
        $this->assertSame(20.0, $result['discount_percent']);
        $this->assertSame(200.0, $result['discount_amount']);
        $this->assertSame(800.0, $result['final_price']);
    }
}
