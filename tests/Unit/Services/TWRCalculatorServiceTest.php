<?php
declare(strict_types=1);

namespace KSF\Performance\Tests\Unit\Services;

use KSF\Performance\Contracts\TransactionRepositoryInterface;
use KSF\Performance\Contracts\TWRCalculatorInterface;
use KSF\Performance\Contracts\ValuationInterface;
use KSF\Performance\DTO\TWRResultDTO;
use KSF\Performance\Exceptions\InsufficientDataException;
use KSF\Performance\Services\TWRCalculatorService;
use PHPUnit\Framework\TestCase;

/**
 * @BABOK Related: UT-PM-001-001-001
 *
 * In-memory fake repository for isolated unit testing.
 */
class InMemoryValuationRepository implements TransactionRepositoryInterface, ValuationInterface
{
    /** @var array<string, array<string, ValuationInterface>> */
    private array $byPortfolio = [];
    private string $currentPortfolio = '';

    public function setValuations(string $portfolioId, array $valuations): void
    {
        $this->byPortfolio[$portfolioId] = $valuations;
        $this->currentPortfolio = $portfolioId;
    }

    public function getPortfolioId(): string { return $this->currentPortfolio; }
    public function getDate(): \DateTimeInterface { return new \DateTimeImmutable(); }
    public function getTotalValue(): float { return 0.0; }
    public function getInboundTransfer(): float { return 0.0; }
    public function getOutboundTransfer(): float { return 0.0; }
    public function getCurrency(): string { return 'CAD'; }

    public function findByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array
    {
        return [];
    }

    public function findValuationsByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $startS = $start->format('Y-m-d');
        $endS   = $end->format('Y-m-d');
        $out    = [];
        foreach (($this->byPortfolio[$portfolioId] ?? []) as $v) {
            $d = $v->getDate()->format('Y-m-d');
            if ($d >= $startS && $d <= $endS) {
                $out[] = $v;
            }
        }
        return $out;
    }
}

class FakeValuation implements ValuationInterface
{
    public function __construct(
        private string $portfolioId,
        private \DateTimeInterface $date,
        private float $totalValue,
        private float $inbound = 0.0,
        private float $outbound = 0.0,
        private string $currency = 'CAD'
    ) {}

    public function getPortfolioId(): string       { return $this->portfolioId; }
    public function getDate(): \DateTimeInterface    { return $this->date; }
    public function getTotalValue(): float          { return $this->totalValue; }
    public function getInboundTransfer(): float     { return $this->inbound; }
    public function getOutboundTransfer(): float    { return $this->outbound; }
    public function getCurrency(): string           { return $this->currency; }
}

class TWRCalculatorServiceTest extends TestCase
{
    private TWRCalculatorInterface $service;
    private InMemoryValuationRepository $repo;

    protected function setUp(): void
    {
        $this->repo   = new InMemoryValuationRepository();
        $this->service = new TWRCalculatorService($this->repo);
    }

    /**
     * @BABOK Related: UT-PM-001-001-001
     */
    public function testPositiveTWRWithTwoValuations(): void
    {
        $pid = 'P1';
        $v1  = new FakeValuation($pid, new \DateTimeImmutable('2026-01-01'), 1000.0, 0.0, 0.0);
        $v2  = new FakeValuation($pid, new \DateTimeImmutable('2026-01-02'), 1100.0, 0.0, 0.0);
        $this->repo->setValuations($pid, [$v1, $v2]);

        $result = $this->service->calculate($pid, '2026-01-01', '2026-01-02');
        $this->assertInstanceOf(TWRResultDTO::class, $result);
        $this->assertEqualsWithDelta(0.10, $result->getTWR(), 0.001);
        $this->assertEqualsWithDelta(355.0 / 365.0 - 1.0, $result->getAnnualizedTWR(), 0.01, '1-day TWR annualized ≈ 355x');
        $this->assertSame(1, $result->getDays());
    }

    /**
     * @BABOK Related: UT-PM-001-001-002
     */
    public function testNegativeTWR(): void
    {
        $pid = 'P1';
        $v1  = new FakeValuation($pid, new \DateTimeImmutable('2026-01-01'), 1000.0);
        $v2  = new FakeValuation($pid, new \DateTimeImmutable('2026-01-02'), 800.0);
        $this->repo->setValuations($pid, [$v1, $v2]);

        $result = $this->service->calculate($pid, '2026-01-01', '2026-01-02');
        $this->assertEqualsWithDelta(-0.20, $result->getTWR(), 0.001);
    }

    /**
     * @BABOK Related: UT-PM-001-001-003
     */
    public function testThrowsInsufficientDataForSingleValuation(): void
    {
        $pid = 'P1';
        $v1  = new FakeValuation($pid, new \DateTimeImmutable('2026-01-01'), 1000.0);
        $this->repo->setValuations($pid, [$v1]);

        $this->expectException(InsufficientDataException::class);
        $this->service->calculate($pid, '2026-01-01', '2026-01-03');
    }

    /**
     * @BABOK Related: UT-PM-001-002-001
     */
    public function testTWRWithInboundTransfer(): void
    {
        $pid = 'P1';
        $v1  = new FakeValuation($pid, new \DateTimeImmutable('2026-01-01'), 1000.0, 0.0, 0.0);
        $v2  = new FakeValuation($pid, new \DateTimeImmutable('2026-01-02'), 1200.0, 100.0, 0.0);
        $this->repo->setValuations($pid, [$v1, $v2]);

        $result = $this->service->calculate($pid, '2026-01-01', '2026-01-02');
        // (1200 - 100) / (1000 + 0) - 1 = 1.1 - 1 = 0.10
        $this->assertEqualsWithDelta(0.10, $result->getTWR(), 0.001);
    }

    /**
     * @BABOK Related: UT-PM-001-002-002
     */
    public function testAnnualizedTWROverMultipleDays(): void
    {
        $pid = 'P1';
        $vals = [];
        $value = 1000.0;
        for ($i = 0; $i <= 10; $i++) {
            $vals[] = new FakeValuation($pid, (new \DateTimeImmutable('2026-01-01'))->modify("+{$i} days"), $value);
            $value *= 1.01; // +1% per day
        }
        $this->repo->setValuations($pid, $vals);

        $result = $this->service->calculate($pid, '2026-01-01', '2026-01-11');
        $this->assertEquals(10, $result->getDays());
        // ~10 days × 1% ≈ 10.46% total, annualized should be > 0
        $this->assertGreaterThan(0.0, $result->getTWR());
        $this->assertGreaterThan(0.0, $result->getAnnualizedTWR());
    }
}
