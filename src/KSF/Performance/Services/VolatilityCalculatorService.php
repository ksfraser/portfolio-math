<?php
declare(strict_types=1);

namespace KSF\Performance\Services;

use KSF\Performance\Contracts\TransactionRepositoryInterface;
use KSF\Performance\Contracts\ValuationInterface;
use KSF\Performance\Contracts\VolatilityCalculatorInterface;
use KSF\Performance\DTO\VolatilityResultDTO;
use KSF\Performance\Exceptions\InsufficientDataException;

/**
 * @BABOK Related: FR-PM-004-001
 */
class VolatilityCalculatorService implements VolatilityCalculatorInterface
{
    public function __construct(private TransactionRepositoryInterface $txRepo) {}

    /**
     * @BABOK Related: FR-PM-004-001
     */
    public function calculate(string $portfolioId, string $startDate, string $endDate): VolatilityResultDTO
    {
        $start = new \DateTimeImmutable($startDate);
        $end   = new \DateTimeImmutable($endDate);

        $vals = $this->txRepo->findValuationsByPortfolioAndRange($portfolioId, $start, $end);
        $vals = array_values($vals);
        if (count($vals) < 2) {
            throw new InsufficientDataException('Need at least 2 valuations for volatility.');
        }

        usort($vals, static fn (ValuationInterface $a, ValuationInterface $b) => $a->getDate() <=> $b->getDate());

        $returns = [];
        for ($i = 1; $i < count($vals); $i++) {
            $prev = $vals[$i - 1]->getTotalValue();
            $curr = $vals[$i]->getTotalValue();
            if ($prev != 0.0) {
                $returns[] = ($curr / $prev) - 1.0;
            }
        }

        $n = count($returns);
        if ($n < 2) {
            return new VolatilityResultDTO(0.0, 0.0, 0.0, 0.0);
        }

        $mean = array_sum($returns) / $n;
        $sumSqStd = 0.0;
        $sumSqSemi = 0.0;
        $countStd = 0;
        $countSemi = 0;

        foreach ($returns as $r) {
            $logReturn = log(1.0 + $r);
            $diff = $logReturn - log(1.0 + $mean);
            $sq = $diff * $diff;
            $sumSqStd += $sq;
            $countStd++;

            if ($logReturn < log(1.0 + $mean)) {
                $sumSqSemi += $sq;
                $countSemi++;
            }
        }

        $stdDev = ($countStd > 1)
            ? sqrt($sumSqStd / ($countStd - 1)) * sqrt($countStd)
            : 0.0;
        $semiDev = ($countSemi > 1)
            ? sqrt($sumSqSemi / ($countSemi - 1)) * sqrt($countSemi)
            : 0.0;

        $annualizedStd = $stdDev * sqrt(252);
        $annualizedSemi = $semiDev * sqrt(252);

        return new VolatilityResultDTO(
            (float) $stdDev,
            (float) $semiDev,
            (float) $annualizedStd,
            (float) $annualizedSemi
        );
    }
}
