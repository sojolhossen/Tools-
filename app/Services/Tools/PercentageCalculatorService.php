<?php

declare(strict_types=1);

namespace App\Services\Tools;

final class PercentageCalculatorService
{
    /**
     * Calculate X% of Y.
     * Formula: (X / 100) * Y
     */
    public function percentageOf(float $percentage, float $total): float
    {
        return ($percentage / 100) * $total;
    }

    /**
     * Calculate percentage change from initial to final value.
     * Formula: ((final - initial) / initial) * 100
     *
     * @return array{
     *     percentage_change: float,
     *     difference: float,
     *     direction: 'increase' | 'decrease' | 'none',
     *     error: ?string
     * }
     */
    public function percentageChange(float $initial, float $final): array
    {
        if ($initial == 0.0) {
            return [
                'percentage_change' => 0.0,
                'difference' => $final - $initial,
                'direction' => 'none',
                'error' => 'Cannot calculate percentage change from zero (division by zero).',
            ];
        }

        $difference = $final - $initial;
        $change = ($difference / abs($initial)) * 100;

        $direction = 'none';
        if ($change > 0) {
            $direction = 'increase';
        } elseif ($change < 0) {
            $direction = 'decrease';
        }

        return [
            'percentage_change' => round(abs($change), 4),
            'difference' => round($difference, 4),
            'direction' => $direction,
            'error' => null,
        ];
    }

    /**
     * Calculate discount amount and final price.
     *
     * @return array{
     *     original_price: float,
     *     discount_percent: float,
     *     discount_amount: float,
     *     final_price: float,
     *     savings_percent: float
     * }
     */
    public function discountAndFinalPrice(float $originalPrice, float $discountPercent): array
    {
        $discountAmount = ($discountPercent / 100) * $originalPrice;
        $finalPrice = max(0.0, $originalPrice - $discountAmount);

        return [
            'original_price' => round($originalPrice, 2),
            'discount_percent' => round($discountPercent, 2),
            'discount_amount' => round($discountAmount, 2),
            'final_price' => round($finalPrice, 2),
            'savings_percent' => round($discountPercent, 2),
        ];
    }
}
