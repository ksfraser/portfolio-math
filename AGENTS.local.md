<!-- Repo-specific appendix to the shared AGENTS.md. Generic conventions live in AGENTS_ARCH.md (hardlinked). -->

# AGENTS.local.md — ksfraser/portfolio-math
> Repo-specific overrides for `ksfraser/portfolio-math`. Core principles (SOLID, DRY, TDD) cannot be overridden.
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
## 3. SOLID / Architecture Rules
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
### DTOs
- All calculator methods return DTOs, never raw arrays
- DTOs are read-only after construction (immutable)
### Exceptions
- Base: `KSF\Performance\Exceptions\CalculationException`
- Specific: `InsufficientDataException`, `ConvergenceException`, `InvalidTransactionException`
---
## 4. Namespace
- Root: `KSF\Performance\`
- Sub: `KSF\Performance\{Contracts,DTO,Domain,Repositories,Services,Exceptions}`
---
## 5. ProjectDcs (BABOK Artifacts)
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
## 6. Appendix Files
- `AGENTS.local.md` — local overrides (not committed)
- `AGENTS_APPENDIX.md` — project-specific extensions (committed)
