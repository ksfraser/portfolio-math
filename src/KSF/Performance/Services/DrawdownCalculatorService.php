<?php
declare(strict_types=1);

namespace KSF\Performance\Services;

use KSF\Performance\Contracts\DrawdownCalculatorInterface;
use KSF\Performance\Contracts\TransactionRepositoryInterface;
use KSF\Performance\Contracts\ValuationInterface;
use KSF\Performance\DTO\DrawdownResultDTO;
use KSF\Performance\Exceptions\InsufficientDataException;

/**
 * @BABOK Related: FR-PM-003-001
 */
class DrawdownCalculatorService implements DrawdownCalculatorInterface
{
    public function __construct(private TransactionRepositoryInterface $txRepo) {}

    /**
     * @BABOK Related: FR-PM-003-001
     */
    public function calculate(string $portfolioId, string $startDate, string $endDate): DrawdownResultDTO
    {
        $start = new \DateTimeImmutable($startDate);
        $end   = new \DateTimeImmutable($endDate);

        $vals = $this->txRepo->findValuationsByPortfolioAndRange($portfolioId, $start, $end);
        $vals = array_values($vals); // reindex
        if (count($vals) < 2) {
            throw new InsufficientDataException('Need at least 2 valuations for drawdown analysis.');
        }

        usort($vals, static fn (ValuationInterface $a, ValuationInterface $b) => $a->getDate() <=> $b->getDate());

        $peak      = $vals[0]->getTotalValue();
        $peakDate  = $vals[0]->getDate();
        $troughDate = $peakDate;
        $maxDD     = 0.0;
        $series    = [];

        foreach ($vals as $v) {
            $value = $v->getTotalValue();
            $ds    = $v->getDate()->format('Y-m-d');

            if ($value >= $peak) {
                $peak     = $value;
                $peakDate = $v->getDate();
            } else {
                $dd = ($peak - $value) / $peak;
                $series[$ds] = -$dd;
                if ($dd > $maxDD) {
                    $maxDD     = $dd;
                    $troughDate = $v->getDate();
                }
            }
        }

        $recoveryDays = (int) $peakDate->diff($troughDate)->days;

        return new DrawdownResultDTO(
            (float) $maxDD,
            $peakDate,
            $troughDate,
            $recoveryDays,
            $series
        );
    }
}
