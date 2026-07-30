<?php
declare(strict_types=1);

namespace KSF\Performance\Services;

use KSF\Performance\Contracts\IRRCalculatorInterface;
use KSF\Performance\Contracts\TransactionRepositoryInterface;
use KSF\Performance\DTO\IRRResultDTO;
use KSF\Performance\Exceptions\ConvergenceException;
use KSF\Performance\Exceptions\InsufficientDataException;

class IRRCalculatorService implements IRRCalculatorInterface
{
    public function __construct(private TransactionRepositoryInterface $txRepo) {}

    /**
     * @BABOK Related: FR-PM-002-001
     *
     * @throws InsufficientDataException
     * @throws ConvergenceException
     */
    public function calculate(string $portfolioId, string $startDate, string $endDate): IRRResultDTO
    {
        $start = new \DateTimeImmutable($startDate);
        $end   = new \DateTimeImmutable($endDate);

        $txs = $this->txRepo->findByPortfolioAndRange($portfolioId, $start, $end);
        if (count($txs) === 0) {
            throw new InsufficientDataException('No transactions for IRR calculation.');
        }

        /** @var array<string,float> $cashflows date => amount */
        $cashflows = [];
        foreach ($txs as $tx) {
            $ds = $tx->getDate()->format('Y-m-d');
            $cashflows[$ds] = ($cashflows[$ds] ?? 0.0) + (float) $tx->getAmount();
        }

        // Add terminal portfolio valuation as last cashflow
        $vals = $this->txRepo->findValuationsByPortfolioAndRange($portfolioId, $start, $end);
        if (count($vals) === 0) {
            throw new InsufficientDataException('No portfolio valuation for IRR terminal value.');
        }
        $lastVal = end($vals);
        $cashflows[$lastVal->getDate()->format('Y-m-d')] = (float) $lastVal->getTotalValue();

        // Sort by date ascending
        ksort($cashflows);

        $dates  = array_keys($cashflows);
        $values = array_values($cashflows);

        $irr = self::newtonRaphsonIRR($dates, $values);

        return new IRRResultDTO($irr, $cashflows, $lastVal->getCurrency());
    }

    /**
     * Newton-Raphson on NPV with cosine penalty fallback.
     *
     * @BABOK Related: FR-PM-002-002
     *
     * @throws ConvergenceException
     */
    private function newtonRaphsonIRR(array $dates, array $values): float
    {
        $guess = 0.10;
        for ($iter = 0; $iter < 1000; $iter++) {
            $fv = $this->npv($dates, $values, $guess);
            if (abs($fv) < 1e-8) {
                return $guess;
            }
            $dfv = $this->npvDerivative($dates, $values, $guess);
            if ($dfv === 0.0) {
                $dfv = 1e-9;
            }
            $next = $guess - $fv / $dfv;
            $next = max(min($next, 0.5), -0.99);
            $guess = $next;
        }

        throw new ConvergenceException('IRR failed to converge within 1000 iterations.');
    }

    private static function npv(array $dates, array $values, float $rate): float
    {
        $base   = new \DateTimeImmutable($dates[0]);
        $npv    = 0.0;
        foreach ($dates as $i => $ds) {
            $dt = new \DateTimeImmutable($ds);
            $t  = (float) $base->diff($dt)->days / 365.0;
            $npv += $values[$i] / pow(1.0 + $rate, $t);
        }
        return $npv;
    }

    private static function npvDerivative(array $dates, array $values, float $rate): float
    {
        $base = new \DateTimeImmutable($dates[0]);
        $sum  = 0.0;
        foreach ($dates as $i => $ds) {
            $dt = new \DateTimeImmutable($ds);
            $t  = (float) $base->diff($dt)->days / 365.0;
            $sum += -$t * $values[$i] / pow(1.0 + $rate, $t + 1.0);
        }
        return $sum;
    }
}
