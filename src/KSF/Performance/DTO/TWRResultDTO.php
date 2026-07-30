<?php
declare(strict_types=1);

namespace KSF\Performance\DTO;

/**
 * Immutable result of a TWR calculation.
 */
final class TWRResultDTO
{
    private float $twr;
    private float $annualizedTWR;
    private int $days;
    private \DateTimeInterface $startDate;
    private \DateTimeInterface $endDate;
    private array $dailyReturns;     // date => float
    private array $cumulative;       // date => float

    public function __construct(
        float $twr,
        float $annualizedTWR,
        int $days,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
        array $dailyReturns = [],
        array $cumulative = []
    ) {
        $this->twr              = $twr;
        $this->annualizedTWR    = $annualizedTWR;
        $this->days             = $days;
        $this->startDate        = $startDate;
        $this->endDate          = $endDate;
        $this->dailyReturns     = $dailyReturns;
        $this->cumulative       = $cumulative;
    }

    public function getTWR(): float               { return $this->twr; }
    public function getAnnualizedTWR(): float     { return $this->annualizedTWR; }
    public function getDays(): int                { return $this->days; }
    public function getStartDate(): \DateTimeInterface { return $this->startDate; }
    public function getEndDate(): \DateTimeInterface   { return $this->endDate; }
    /** @return array<string,float> date(YYYY-MM-DD) => daily return */
    public function getDailyReturns(): array      { return $this->dailyReturns; }
    /** @return array<string,float> date(YYYY-MM-DD) => accumulated return */
    public function getCumulative(): array        { return $this->cumulative; }
}
