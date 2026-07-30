<?php
declare(strict_types=1);

namespace KSF\Performance\Contracts;

use KSF\Performance\DTO\DrawdownResultDTO;

/**
 * Calculates max drawdown, recovery time, and drawdown series.
 */
interface DrawdownCalculatorInterface
{
    /**
     * @BABOK Related: FR-PM-003-001
     */
    public function calculate(string $portfolioId, string $startDate, string $endDate): DrawdownResultDTO;
}
