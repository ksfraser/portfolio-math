<?php
declare(strict_types=1);

namespace KSF\Performance\Contracts;

/**
 * Portfolio valuation at a point in time.
 */
interface ValuationInterface
{
    public function getPortfolioId(): string;
    public function getDate(): \DateTimeInterface;
    public function getTotalValue(): float;       // in reporting currency
    public function getInboundTransfer(): float;  // deposits that day
    public function getOutboundTransfer(): float; // withdrawals that day
    public function getCurrency(): string;
}
