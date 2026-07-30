<?php
declare(strict_types=1);

namespace KSF\Performance\Tests\Unit\Services;

use KSF\Performance\Contracts\DrawdownCalculatorInterface;
use KSF\Performance\Contracts\ValuationInterface;
use KSF\Performance\DTO\DrawdownResultDTO;
use KSF\Performance\Exceptions\InsufficientDataException;
use KSF\Performance\Services\DrawdownCalculatorService;
use PHPUnit\Framework\TestCase;

class InMemoryRepo implements \KSF\Performance\Contracts\TransactionRepositoryInterface
{
    /** @var array<string, ValuationInterface[]> */
    private array $byPortfolio = [];

    public function setValuations(string $pid, array $vals): void { $this->byPortfolio[$pid] = $vals; }

    public function findByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array
    {
        return [];
    }

    public function findValuationsByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $startS = $start->format('Y-m-d');
        $endS   = $end->format('Y-m-d');
        $out    = [];
        foreach ($this->byPortfolio[$portfolioId] ?? [] as $v) {
            $d = $v->getDate()->format('Y-m-d');
            if ($d >= $startS && $d <= $endS) {
                $out[] = $v;
            }
        }
        return $out;
    }
}

class FakeValuationDrawdown implements ValuationInterface
{
    public function __construct(
        private string $portfolioId,
        private \DateTimeInterface $date,
        private float $totalValue
    ) {}

    public function getPortfolioId(): string     { return $this->portfolioId; }
    public function getDate(): \DateTimeInterface  { return $this->date; }
    public function getTotalValue(): float        { return $this->totalValue; }
    public function getInboundTransfer(): float   { return 0.0; }
    public function getOutboundTransfer(): float  { return 0.0; }
    public function getCurrency(): string         { return 'CAD'; }
}

class DrawdownCalculatorServiceTest extends TestCase
{
    private DrawdownCalculatorInterface $service;
    private InMemoryRepo $repo;

    protected function setUp(): void
    {
        $this->repo   = new InMemoryRepo();
        $this->service = new DrawdownCalculatorService($this->repo);
    }

    /**
     * @BABOK Related: UT-PM-003-001-001
     */
    public function testMaxDrawdownWithDecline(): void
    {
        $pid = 'P1';
        $vals = [
            new FakeValuationDrawdown($pid, new \DateTimeImmutable('2026-01-01'), 1000.0),
            new FakeValuationDrawdown($pid, new \DateTimeImmutable('2026-01-02'), 1100.0), // peak
            new FakeValuationDrawdown($pid, new \DateTimeImmutable('2026-01-03'), 800.0),
        ];
        $this->repo->setValuations($pid, $vals);

        $result = $this->service->calculate($pid, '2026-01-01', '2026-01-03');
        $this->assertInstanceOf(DrawdownResultDTO::class, $result);
        // maxDD = (1100 - 800) / 1100 = 0.2727
        $this->assertEqualsWithDelta(0.2727, $result->getMaxDrawdown(), 0.001);
    }

    /**
     * @BABOK Related: UT-PM-003-001-002
     */
    public function testNoDeclineReturnsZeroDrawdown(): void
    {
        $pid = 'P1';
        $vals = [
            new FakeValuationDrawdown($pid, new \DateTimeImmutable('2026-01-01'), 1000.0),
            new FakeValuationDrawdown($pid, new \DateTimeImmutable('2026-01-02'), 1200.0),
        ];
        $this->repo->setValuations($pid, $vals);

        $result = $this->service->calculate($pid, '2026-01-01', '2026-01-02');
        $this->assertEqualsWithDelta(0.0, $result->getMaxDrawdown(), 0.001);
    }

    /**
     * @BABOK Related: UT-PM-003-002-001
     */
    public function testRecoveryDaysCalculated(): void
    {
        $pid = 'P1';
        $vals = [
            new FakeValuationDrawdown($pid, new \DateTimeImmutable('2026-01-01'), 1000.0),
            new FakeValuationDrawdown($pid, new \DateTimeImmutable('2026-01-02'), 1100.0), // peak
            new FakeValuationDrawdown($pid, new \DateTimeImmutable('2026-01-03'), 800.0),  // trough
            new FakeValuationDrawdown($pid, new \DateTimeImmutable('2026-01-04'), 1100.0), // recovered
        ];
        $this->repo->setValuations($pid, $vals);

        $result = $this->service->calculate($pid, '2026-01-01', '2026-01-04');
        $this->assertEqualsWithDelta(0.2727, $result->getMaxDrawdown(), 0.001);
        $this->assertGreaterThanOrEqual(0, $result->getRecoveryDays());
    }

    /**
     * @BABOK Related: UT-PM-003-001-003
     */
    public function testThrowsInsufficientData(): void
    {
        $this->expectException(InsufficientDataException::class);
        $this->repo->setValuations('P1', [
            new FakeValuationDrawdown('P1', new \DateTimeImmutable('2026-01-01'), 1000.0)
        ]);
        $this->service->calculate('P1', '2026-01-01', '2026-01-02');
    }
}
