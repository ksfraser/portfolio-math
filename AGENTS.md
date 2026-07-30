# AGENTS.md — ksfraser/portfolio-math

> **Project-specific overrides for `ksfraser/portfolio-math`.**
> The canonical master guidelines live at `~/AGENTS.md`. This file adds or
> overrides rules specific to this library. Core principles (SOLID, DRY, TDD)
> cannot be overridden.

---

## 1. Target Platform

- **PHP**: `>=7.4`, tested on 7.4, 8.0, 8.1, 8.2
- **No framework dependency** — pure library, framework-agnostic
- **No external runtime dependencies** — only PHPUnit for tests

---

## 2. Purpose

Shared portfolio performance math library for the KSF stack:
- **stockmarket** (`ksf_stockmarket`) — thin wrapper against portfolio_transactions table
- **FrontAccounting** (`ksf-fa`) — thin wrapper against FA GL transactions
- **ksfii_app / WealthSystem** — thin wrapper against FA forecasting ledger

Implements: TWR, IRR, drawdown, volatility, asset allocation drift.

---

## 3. Naming Conventions

### PHP
- **Interfaces**: `InterfaceNameInterface` (e.g., `TransactionRepositoryInterface`)
- **Services**: `ServiceNameService` (e.g., `TWRCalculatorService`)
- **Value Objects**: PascalCase, immutable (e.g., `Transaction`, `PerformanceResult`)
- **DTOs**: PascalCase + `DTO` suffix (e.g., `TWRResultDTO`)
- **Exceptions**: `ExceptionNameException` (e.g., `InsufficientDataException`)
- **Repositories**: PascalCase + `Repository` suffix (e.g., `TransactionRepository`)

### Namespace
- Root: `KSF\Performance\`
- Sub: `KSF\Performance\{Contracts,DTO,Domain,Repositories,Services,Exceptions}`

### Files
- Match class name exactly (PSR-4): `TWRCalculatorService.php`
- One class per file

---

## 4. SOLID / Architecture Rules

### Dependency Inversion
All dependencies flow toward contracts. Services receive repositories via constructor injection:

```php
class TWRCalculatorService implements TWRCalculatorServiceInterface
{
    public function __construct(
        TransactionRepositoryInterface $txRepo,
        ValuationRepositoryInterface $valRepo
    ) { }
}
```

### Single Responsibility
- **TWRCalculatorService** = TWR math only
- **IRRCalculatorService** = IRR math only
- **DrawdownCalculatorService** = drawdown math only
- **VolatilityCalculatorService** = volatility math only
- **AssetAllocationService** = weight/drift math only

Repository interfaces define data contracts; concrete adapters live in the app layer
(stockmarket/FA/ksfii_app), **not** in this package.

### DRY
- Shared number-formatting, date-helper, and currency-rounding logic lives in
  `KSF\Performance\Support\*`
- No copy-paste between calculators

### DTOs
- All calculator methods return DTOs, never raw arrays
- DTOs are read-only after construction (immutable)

### Exceptions
- Base: `KSF\Performance\Exceptions\CalculationException`
- Specific: `InsufficientDataException`, `ConvergenceException`, `InvalidTransactionException`

---

## 5. Coding Standards

```php
<?php
declare(strict_types=1);

namespace KSF\Performance\Services;

use KSF\Performance\Contracts\TransactionRepositoryInterface;
use KSF\Performance\DTO\TWRResultDTO;
use KSF\Performance\Exceptions\InsufficientDataException;

/**
 * Calculates True Time-Weighted Return (TWR) for a portfolio.
 *
 * @BABOK Related: FR-PM-001
 * @UML Note: Class diagram in ProjectDcs/UML.md
 *
 * @since 1.0.0
 */
class TWRCalculatorService implements TWRCalculatorServiceInterface
{
    public function __construct(
        private TransactionRepositoryInterface $txRepo,
        private ValuationRepositoryInterface $valRepo
    ) {}

    /**
     * Calculate TWR for a date range.
     *
     * @param string $portfolioId Portfolio identifier
     * @param string $startDate   YYYY-MM-DD
     * @param string $endDate     YYYY-MM-DD
     * @return TWRResultDTO
     * @throws InsufficientDataException If fewer than 2 valuations exist
     *
     * @since 1.0.0
     */
    public function calculate(string $portfolioId, string $startDate, string $endDate): TWRResultDTO
    {
        // ...
    }
}
```

**Required PHPDoc**: `@param`, `@return`, `@throws`, `@since`
**Optional tags**: `@see`, `@link`, `@deprecated`, `@UML`, `@BABOK`

---

## 6. Testing Standards

### TDD Workflow
1. Write failing test first (RED)
2. Minimal code to pass (GREEN)
3. Refactor while keeping tests green (REFACTOR)

### Coverage
- Target: 100% line coverage
- No skipped tests

### Test Structure
```
tests/
├── Unit/
│   ├── Services/
│   │   ├── TWRCalculatorServiceTest.php
│   │   ├── IRRCalculatorServiceTest.php
│   │   └── ...
│   ├── DTO/
│   └── Support/
└── bootstrap.php
```

```php
namespace KSF\Performance\Tests\Unit\Services;

class TWRCalculatorServiceTest extends \PHPUnit\Framework\TestCase
{
    private TWRCalculatorServiceInterface $service;

    protected function setUp(): void
    {
        $this->service = new TWRCalculatorService(
            new InMemoryTransactionRepository(),
            new InMemoryValuationRepository()
        );
    }

    public function testCalculateReturnsPositiveTWR(): void
    {
        // Arrange
        // Act
        // Assert
    }
}
```

---

## 7. Git & Version Control

### Branch Naming
- `main` — production-ready
- `feature/*` — new features
- `fix/*` — bug fixes
- `refactor/*` — refactoring

### Commit Messages
```
type(scope): description

feat(twr): add TWR calculation service
fix(irr): handle negative cash flows in Newton-Raphson
docs(requirements): add FR-PM-001
```

### .gitignore
```
/vendor/
/composer.lock
/.phpunit.cache/
/.idea/
/.vscode/
.phpunit.result.cache
```

### SemVer Tags
```bash
git tag -a v1.0.0 -m "Initial release: TWR, IRR, drawdown, volatility"
git push origin v1.0.0
```

---

## 8. Composer Publishing

- Repo: `git@github.com:ksfraser/portfolio-math.git`
- Packagist: `ksfraser/portfolio-math`
- Private Packagist or public — per deployment choice
- `minimum-stability: stable`, `prefer-stable: true`

---

## 9. ProjectDcs (BABOK Artifacts)

All requirements are **individual files**, not bundled markdown documents.

### Naming Convention

| Type | Pattern | Example |
|------|---------|---------|
| Business Requirement | `BR-PM-001-<short-name>.md` | `BR-PM-001-shared-performance-math.md` |
| Functional Requirement | `FR-PM-001-001-<short-name>.md` | `FR-PM-001-001-twr-calculation.md` |
| Use Case | `UC-PM-001-<short-name>.md` | `UC-PM-001-calculate-portfolio-twr.md` |
| Unit Test | `UT-PM-001-001-001-<short-name>.md` | `UT-PM-001-001-001-twr-positive.md` |
| UAT Case | `UAT-PM-001-<short-name>.md` | `UAT-PM-001-twr-reporting.md` |

- `PM` = fixed module code for **P**ortfolio **M**ath
- FR adds a sub-sequence `001`, UT adds `001` again for method sequencing
- Files live directly under `ProjectDcs/`; subdirs allowed for grouping by calculator if needed

### Traceability

Each code class/method and test must reference the FR it satisfies in
PHPDoc and at the top of the test file:

```php
/** @BABOK Related: FR-PM-001-001 */
```

```php
/** @BABOK Related: UT-PM-001-001-001 */
```

The RTM is auto-generated from these references during release prep; do not
maintain a separate hand-written RTM.

### Directory Layout

```
ProjectDcs/
├── BR-PM-001-shared-performance-math.md
├── FR-PM-001-001-twr-calculation.md
├── FR-PM-001-002-twr-annualized.md
├── FR-PM-002-001-irr-calculation.md
├── FR-PM-003-001-drawdown-calculation.md
├── FR-PM-003-002-drawdown-recovery-duration.md
├── FR-PM-004-001-volatility-calculation.md
├── FR-PM-004-002-semi-deviation.md
├── FR-PM-005-001-asset-allocation-drift.md
├── UC-PM-001-twr-reporting.md
├── UC-PM-002-irr-reporting.md
├── UT-PM-001-001-001-twr-positive.md
├── UT-PM-001-001-002-twr-negative.md
├── UT-PM-002-001-001-irr-single-cashflow.md
├── UT-PM-003-001-001-drawdown-no-peak.md
├── UT-PM-003-001-002-drawdown-with-peak.md
├── UT-PM-005-001-001-allocation-on-target.md
├── UAT-PM-001-twr-integration.md
├── UAT-PM-002-irr-integration.md
├── Architecture.md            # layer diagram, dependency flow
├── Test Plan.md               # strategy, fixtures, coverage targets
└── UML.md                     # class/sequence diagrams
```

---

## 10. Appendix Files

- `AGENTS.local.md` — local overrides (not committed)
- `AGENTS_APPENDIX.md` — project-specific extensions (committed)

Core principles (SOLID, DRY, TDD) cannot be overridden.
