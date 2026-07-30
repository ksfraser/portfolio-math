<?php
declare(strict_types=1);

namespace KSF\Performance\DTO;

/**
 * Immutable result of a volatility analysis.
 */
final class VolatilityResultDTO
{
    private float $stdDeviation;        // sample std dev of log-returns
    private float $semiDeviation;       // downside-only semideviation
    private float $annualizedStdDeviation;
    private float $annualizedSemiDeviation;
    private int    $tradingDays;

    public function __construct(
        float $stdDeviation,
        float $semiDeviation,
        float $annualizedStdDeviation,
        float $annualizedSemiDeviation,
        int $tradingDays = 252
    ) {
        $this->stdDeviation           = $stdDeviation;
        $this->semiDeviation          = $semiDeviation;
        $this->annualizedStdDeviation = $annualizedStdDeviation;
        $this->annualizedSemiDeviation = $annualizedSemiDeviation;
        $this->tradingDays            = $tradingDays;
    }

    public function getStdDeviation(): float           { return $this->stdDeviation; }
    public function getSemiDeviation(): float          { return $this->semiDeviation; }
    public function getAnnualizedStdDeviation(): float { return $this->annualizedStdDeviation; }
    public function getAnnualizedSemiDeviation(): float { return $this->annualizedSemiDeviation; }
    public function getTradingDays(): int              { return $this->tradingDays; }
}
