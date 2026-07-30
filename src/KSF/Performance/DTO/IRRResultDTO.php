<?php
declare(strict_types=1);

namespace KSF\Performance\DTO;

/**
 * Immutable result of an IRR calculation.
 */
final class IRRResultDTO
{
    private float $irr;
    private array $cashflows;   // date => amount
    private string $currency;

    public function __construct(float $irr, array $cashflows, string $currency = 'CAD')
    {
        $this->irr       = $irr;
        $this->cashflows = $cashflows;
        $this->currency  = $currency;
    }

    public function getIRR(): float           { return $this->irr; }
    /** @return array<string,float> date(YYYY-MM-DD) => signed amount */
    public function getCashflows(): array     { return $this->cashflows; }
    public function getCurrency(): string     { return $this->currency; }
}
