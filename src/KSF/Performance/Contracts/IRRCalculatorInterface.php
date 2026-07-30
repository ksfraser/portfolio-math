<?php
declare(strict_types=1);

namespace KSF\Performance\Contracts;

use KSF\Performance\DTO\IRRResultDTO;

/**
 * Calculates Internal Rate of Return (IRR / XIRR) from dated cash-flows
 * plus a terminal portfolio valuation.
 */
interface IRRCalculatorInterface
{
    /**
     * @BABOK Related: FR-PM-002-001
     */
    public function calculate(string $portfolioId, string $startDate, string $endDate): IRRResultDTO;
}
