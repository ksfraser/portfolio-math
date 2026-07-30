<?php
declare(strict_types=1);

namespace KSF\Performance\Contracts;

/**
 * Read-only view of a single portfolio transaction.
 */
interface TransactionInterface
{
    public function getId(): string;
    public function getDate(): \DateTimeInterface;
    public function getAmount(): float;          // positive = inflow, negative = outflow
    public function getType(): string;           // buy|sell|deposit|withdrawal|fee|dividend|interest|tax
    public function getCurrency(): string;
    public function getSecurityId(): ?string;
}
