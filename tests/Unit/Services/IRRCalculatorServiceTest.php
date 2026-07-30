<?php
declare(strict_types=1);

namespace KSF\Performance\Tests\Unit\Services;

use KSF\Performance\Contracts\IRRCalculatorInterface;
use KSF\Performance\Contracts\TransactionInterface;
use KSF\Performance\Contracts\ValuationInterface;
use KSF\Performance\DTO\IRRResultDTO;
use KSF\Performance\Exceptions\ConvergenceException;
use KSF\Performance\Exceptions\InsufficientDataException;
use KSF\Performance\Services\IRRCalculatorService;
use PHPUnit\Framework\TestCase;

class FakeTransaction implements TransactionInterface
{
    public function __construct(
        private string $id,
        private \DateTimeInterface $date,
        private float $amount,
        private string $type = 'buy',
        private string $currency = 'CAD',
        private ?string $securityId = null
    ) {}

    public function getId(): string                { return $this->id; }
    public function getDate(): \DateTimeInterface    { return $this->date; }
    public function getAmount(): float              { return $this->amount; }
    public function getType(): string               { return $this->type; }
    public function getCurrency(): string           { return $this->currency; }
    public function getSecurityId(): ?string        { return $this->securityId; }
}

class FakeValuation implements ValuationInterface
{
    public function __construct(
        private string $portfolioId,
        private \DateTimeInterface $date,
        private float $totalValue,
        private string $currency = 'CAD'
    ) {}

    public function getPortfolioId(): string       { return $this->portfolioId; }
    public function getDate(): \DateTimeInterface    { return $this->date; }
    public function getTotalValue(): float          { return $this->totalValue; }
    public function getInboundTransfer(): float     { return 0.0; }
    public function getOutboundTransfer(): float    { return 0.0; }
    public function getCurrency(): string           { return $this->currency; }
}

class InMemoryRepository implements TransactionRepositoryInterface
{
    /** @var array<string, TransactionInterface[]> */
    private array $txs = [];
    /** @var array<string, ValuationInterface[]> */
    private array $vals = [];

    public function setTransactions(string $pid, array $txs): void   { $this->txs[$pid] = $txs; }
    public function setValuations(string $pid, array $vals): void    { $this->vals[$pid] = $vals; }

    public function findByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $startS = $start->format('Y-m-d');
        $endS   = $end->format('Y-m-d');
        $out    = [];
        foreach ($this->txs[$portfolioId] ?? [] as $tx) {
            $d = $tx->getDate()->format('Y-m-d');
            if ($d >= $startS && $d <= $endS) {
                $out[] = $tx;
            }
        }
        return $out;
    }

    public function findValuationsByPortfolioAndRange(string $portfolioId, \DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $startS = $start->format('Y-m-d');
        $endS   = $end->format('Y-m-d');
        $out    = [];
        foreach ($this->vals[$portfolioId] ?? [] as $v) {
            $d = $v->getDate()->format('Y-m-d');
            if ($d >= $startS && $d <= $endS) {
                $out[] = $v;
            }
        }
        return $out;
    }
}

class IRRCalculatorServiceTest extends TestCase
{
    private IRRCalculatorInterface $service;
    private InMemoryRepository $repo;

    protected function setUp(): void
    {
        $this->repo   = new InMemoryRepository();
        $this->service = new IRRCalculatorService($this->repo);
    }

    /**
     * @BABOK Related: UT-PM-002-001-001
     */
    public function testSingleInvestmentPeriodReturnsExpectedIRR(): void
    {
        // Invest 1000 today, worth 1100 in one year → 10% IRR
        $pid = 'P1';
        $dt0 = new \DateTimeImmutable('2026-01-01');
        $dt1 = new \DateTimeImmutable('2027-01-01');

        $this->repo->setTransactions($pid, [
            new FakeTransaction('t1', $dt0, -1000.0)
        ]);
        $this->repo->setValuations($pid, [
            new FakeValuation($pid, $dt1, 1100.0)
        ]);

        $result = $this->service->calculate($pid, '2026-01-01', '2027-01-01');
        $this->assertInstanceOf(IRRResultDTO::class, $result);
        $this->assertEqualsWithDelta(0.10, $result->getIRR(), 0.001);
    }

    /**
     * @BABOK Related: UT-PM-002-001-002
     */
    public function testNegativeOutflowThenPositiveInflow(): void
    {
        // deposit 1000, withdraw 200, end value 900 → expect IRR ~ -10%
        $pid = 'P1';
        $dt0 = new \DateTimeImmutable('2026-01-01');
        $dt1 = new \DateTimeImmutable('2026-07-01');
        $dt2 = new \DateTimeImmutable('2027-01-01');

        $this->repo->setTransactions($pid, [
            new FakeTransaction('t1', $dt0, 1000.0, 'deposit'),
            new FakeTransaction('t2', $dt1, -200.0, 'withdrawal'),
        ]);
        $this->repo->setValuations($pid, [
            new FakeValuation($pid, $dt2, 900.0)
        ]);

        $result = $this->service->calculate($pid, '2026-01-01', '2027-01-01');
        $this->assertLessThan(0.0, $result->getIRR());
        $this->assertEqualsWithDelta(-0.10, $result->getIRR(), 0.02);
    }

    /**
     * @BABOK Related: UT-PM-002-001-003
     */
    public function testThrowsInsufficientDataWhenNoTransactions(): void
    {
        $this->expectException(InsufficientDataException::class);
        $this->service->calculate('P1', '2026-01-01', '2027-01-01');
    }
}
