<?php
declare(strict_types=1);

namespace KSF\Performance\Contracts;

use KSF\Performance\DTO\TWRResultDTO;

/**
 * Calculates True Time-Weighted Return (TWR) / Dietz-style geometric chain.
 */
interface TWRCalculatorInterface
{
    /**
     * @BABOK Related: FR-PM-001-001
     */
    public function calculate(string $portfolioId, string $startDate, string $endDate): TWRResultDTO;
}
