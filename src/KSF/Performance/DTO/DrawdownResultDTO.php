<?php
declare(strict_types=1);

namespace KSF\Performance\DTO;

/**
 * Immutable result of a drawdown analysis.
 */
final class DrawdownResultDTO
{
    private float $maxDrawdown;
    private \DateTimeInterface $peakDate;
    private \DateTimeInterface $troughDate;
    private int $recoveryDays;
    private array $drawdownSeries; // date => float (negative %)

    public function __construct(
        float $maxDrawdown,
        \DateTimeInterface $peakDate,
        \DateTimeInterface $troughDate,
        int $recoveryDays,
        array $drawdownSeries = []
    ) {
        $this->maxDrawdown    = $maxDrawdown;
        $this->peakDate       = $peakDate;
        $this->troughDate     = $troughDate;
        $this->recoveryDays   = $recoveryDays;
        $this->drawdownSeries = $drawdownSeries;
    }

    public function getMaxDrawdown(): float                  { return $this->maxDrawdown; }
    public function getPeakDate(): \DateTimeInterface        { return $this->peakDate; }
    public function getTroughDate(): \DateTimeInterface      { return $this->troughDate; }
    public function getRecoveryDays(): int                   { return $this->recoveryDays; }
    /** @return array<string,float> date(YYYY-MM-DD) => drawdown fraction (negative) */
    public function getDrawdownSeries(): array               { return $this->drawdownSeries; }
}
