<?php
declare(strict_types=1);

namespace KSF\Performance\DTO;

/**
 * Immutable daily portfolio valuation (input to calculators).
 */
final class ValuationDTO
{
    public function __construct(
        private string $portfolioId,
        private \DateTimeInterface $date,
        private float $totalValue,
        private string $currency = 'CAD',
        private float $inboundTransfer = 0.0,
        private float $outboundTransfer = 0.0
    ) {}

    public function getPortfolioId(): string       { return $this->portfolioId; }
    public function getDate(): \DateTimeInterface   { return $this->date; }
    public function getTotalValue(): float         { return $this->totalValue; }
    public function getInboundTransfer(): float    { return $this->inboundTransfer; }
    public function getOutboundTransfer(): float   { return $this->outboundTransfer; }
    public function getCurrency(): string          { return $this->currency; }
}
