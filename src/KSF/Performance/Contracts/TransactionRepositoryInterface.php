<?php
declare(strict_types=1);

namespace KSF\Performance\Contracts;

/**
 * Abstraction for fetching transactions and valuations.
 *
 * Concrete adapters live in the app layer (stockmarket / FA / ksfii_app),
 * NOT in this package.
 */
interface TransactionRepositoryInterface
{
    /**
     * @return TransactionInterface[]
     */
    public function findByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array;

    /**
     * @return ValuationInterface[]
     */
    public function findValuationsByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array;
}
