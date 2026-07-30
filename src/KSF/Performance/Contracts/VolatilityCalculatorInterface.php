<?php
declare(strict_types=1);

namespace KSF\Performance\Contracts;

use KSF\Performance\DTO\VolatilityResultDTO;

/**
 * Calculates volatility and semi-deviation from return series.
 */
interface VolatilityCalculatorInterface
{
    /**
     * @BABOK Related: FR-PM-004-001
     */
    public function calculate(string $portfolioId, string $startDate, string $endDate): VolatilityResultDTO;
}
