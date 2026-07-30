<?php
declare(strict_types=1);

namespace KSF\Performance\Tests\Unit\Services;

use KSF\Performance\Contracts\VolatilityCalculatorInterface;
use KSF\Performance\Contracts\ValuationInterface;
use KSF\Performance\DTO\VolatilityResultDTO;
use KSF\Performance\Exceptions\InsufficientDataException;
use KSF\Performance\Services\VolatilityCalculatorService;
use PHPUnit\Framework\TestCase;

class VolatilityCalculatorServiceTest extends TestCase
{
    private VolatilityCalculatorInterface $service;

    protected function setUp(): void
    {
        $repo = new class implements \KSF\Performance\Contracts\TransactionRepositoryInterface {
            /** @var array<string, ValuationInterface[]> */
            private array $byPortfolio = [];
            public function setValuations(string $pid, array $vals): void { $this->byPortfolio[$pid] = $vals; }
            public function findByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array { return []; }
            public function findValuationsByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array
            {
                $startS = $start->format('Y-m-d');
                $endS   = $end->format('Y-m-d');
                $out = [];
                foreach ($this->byPortfolio[$portfolioId] ?? [] as $v) {
                    $d = $v->getDate()->format('Y-m-d');
                    if ($d >= $startS && $d <= $endS) { $out[] = $v; }
                }
                return $out;
            }
        };
        $this->service = new VolatilityCalculatorService($repo);
    }

    private function valuations(string $pid, array $values): void
    {
        $vals = [];
        $date = new \DateTimeImmutable('2026-01-01');
        foreach ($values as $v) {
            $vals[] = new class($pid, $date, $v) implements ValuationInterface {
                public function __construct(private string $p, private \DateTimeInterface $d, private float $v) {}
                public function getPortfolioId(): string { return $this->p; }
                public function getDate(): \DateTimeInterface { return $this->d; }
                public function getTotalValue(): float { return $this->v; }
                public function getInboundTransfer(): float { return 0.0; }
                public function getOutboundTransfer(): float { return 0.0; }
                public function getCurrency(): string { return 'CAD'; }
            };
            $date = $date->modify('+1 day');
        }
        // Hacky access to repo — rebuild service with inline vals via reflection-free helper
        // Instead: just create a fresh repo-class in each test
    }

    private function makeServiceForValues(string $pid, array $values): VolatilityCalculatorInterface
    {
        $repo = new class($pid, $values) implements \KSF\Performance\Contracts\TransactionRepositoryInterface {
            public function __construct(private string $pid, private array $values) {}
            public function findByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array { return []; }
            public function findValuationsByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array
            {
                $startS = $start->format('Y-m-d');
                $endS   = $end->format('Y-m-d');
                $date = new \DateTimeImmutable('2026-01-01');
                $out = [];
                foreach ($this->values as $v) {
                    $d = $date->format('Y-m-d');
                    if ($d >= $startS && $d <= $endS) {
                        $out[] = new class($this->pid, clone $date, $v) implements ValuationInterface {
                            public function __construct(private string $p, private \DateTimeInterface $d, private float $v) {}
                            public function getPortfolioId(): string { return $this->p; }
                            public function getDate(): \DateTimeInterface { return $this->d; }
                            public function getTotalValue(): float { return $this->v; }
                            public function getInboundTransfer(): float { return 0.0; }
                            public function getOutboundTransfer(): float { return 0.0; }
                            public function getCurrency(): string { return 'CAD'; }
                        };
                    }
                    $date = $date->modify('+1 day');
                }
                return $out;
            }
        };
        return new VolatilityCalculatorService($repo);
    }

    /**
     * @BABOK Related: UT-PM-004-001-001
     */
    public function testZeroVolatilityForFlatLine(): void
    {
        $svc = $this->makeServiceForValues('P1', [1000, 1000, 1000]);
        $result = $svc->calculate('P1', '2026-01-01', '2026-01-03');
        $this->assertInstanceOf(VolatilityResultDTO::class, $result);
        $this->assertEqualsWithDelta(0.0, $result->getStdDeviation(), 0.001);
    }

    /**
     * @BABOK Related: UT-PM-004-001-002
     */
    public function testVolatilityPositiveForOscillatingValues(): void
    {
        $svc = $this->makeServiceForValues('P1', [1000, 1100, 900, 1200, 800]);
        $result = $svc->calculate('P1', '2026-01-01', '2026-01-05');
        $this->assertGreaterThan(0.0, $result->getStdDeviation());
        $this->assertGreaterThan(0.0, $result->getAnnualizedStdDeviation());
    }

    /**
     * @BABOK Related: UT-PM-004-002-001
     */
    public function testSemiDeviationLessOrEqual(): void
    {
        $svc = $this->makeServiceForValues('P1', [1000, 1100, 900, 1200, 800]);
        $result = $svc->calculate('P1', '2026-01-01', '2026-01-05');
        $this->assertLessThanOrEqual($result->getStdDeviation(), $result->getSemiDeviation());
    }

    /**
     * @BABOK Related: UT-PM-004-001-003
     */
    public function testThrowsInsufficientData(): void
    {
        $svc = $this->makeServiceForValues('P1', [1000]);
        $this->expectException(InsufficientDataException::class);
        $svc->calculate('P1', '2026-01-01', '2026-01-02');
    }
}
