<?php
declare(strict_types=1);

namespace KSF\Performance\Services;

use KSF\Performance\Contracts\TWRCalculatorInterface;
use KSF\Performance\Contracts\TransactionRepositoryInterface;
use KSF\Performance\DTO\TWRResultDTO;
use KSF\Performance\Exceptions\InsufficientDataException;

class TWRCalculatorService implements TWRCalculatorInterface
{
    public function __construct(private TransactionRepositoryInterface $txRepo) {}

    /**
     * @BABOK Related: FR-PM-001-001
     *
     * @throws InsufficientDataException
     */
    public function calculate(string $portfolioId, string $startDate, string $endDate): TWRResultDTO
    {
        $start = new \DateTimeImmutable($startDate);
        $end   = new \DateTimeImmutable($endDate);

        $valuations = $this->txRepo->findValuationsByPortfolioAndRange($portfolioId, $start, $end);
        if (count($valuations) < 2) {
            throw new InsufficientDataException('Need at least 2 valuations to calculate TWR.');
        }

        $interval   = new \DateInterval('P1D');
        $period     = new \DatePeriod($start, $interval, $end->modify('+1 day'));

        $accumulated = 0.0;
        $prevTotal   = 0.0;
        $dates       = [];
        $dailyReturns = [];
        $cumulative   = [];

        foreach ($period as $dt) {
            $ds       = $dt->format('Y-m-d');
            $dates[]  = $ds;

            $val = $this->matchValuation($valuations, $dt);
            $total   = $val->getTotalValue();
            $inbound = $val->getInboundTransfer();
            $outbound = $val->getOutboundTransfer();

            if ($prevTotal === 0.0 || ($prevTotal + $inbound) === 0.0) {
                $delta = 0.0;
            } else {
                $delta = ($total + $outbound) / ($prevTotal + $inbound) - 1.0;
            }

            $accumulated = (($accumulated + 1.0) * ($delta + 1.0)) - 1.0;

            $dailyReturns[$ds] = (float) $delta;
            $cumulative[$ds]   = (float) $accumulated;

            $prevTotal = $total;
        }

        $days = (int) $start->diff($end)->days;
        $annualizedTWR = $days > 0 ? (pow(1.0 + $accumulated, 365.0 / $days) - 1.0) : 0.0;

        return new TWRResultDTO(
            (float) $accumulated,
            (float) $annualizedTWR,
            $days,
            $start,
            $end,
            $dailyReturns,
            $cumulative
        );
    }

    /** @return array<int, ValuationInterface> keyed by Y-m-d */
    private function matchValuation(array $valuations, \DateTimeInterface $dt): mixed
    {
        $day = $dt->format('Y-m-d');
        foreach ($valuations as $v) {
            if ($v->getDate()->format('Y-m-d') === $day) {
                return $v;
            }
        }
        // Fallback: return first valuation if no exact match
        return $valuations[0];
    }
}
