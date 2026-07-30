# UC-PM-001-calculate-portfolio-twr

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Primary Actor

- stockmarket app
- ksfii_app / WealthSystem
- FrontAccounting module

## 2. Preconditions
1. `TransactionRepositoryInterface` implementation exists in app layer
2. Portfolio valuations loaded for requested date range

## 3. Main Success Scenario

| Step | Action |
|------|--------|
| 1 | Actor instantiates `TWRCalculatorService` with repository |
| 2 | Actor calls `calculate(portfolioId, startDate, endDate)` |
| 3 | Service fetches valuations from repository |
| 4 | Service computes daily delta and accumulated returns |
| 5 | Service returns `TWRResultDTO` |
| 6 | Actor reads `getTWR()`, `getAnnualizedTWR()`, `getCumulative()` |

## 4. Alternate Flows
- 3a: Fewer than 2 valuations → throws `InsufficientDataException`
- 3b: No valuations found → throws `InsufficientDataException`

## 5. Postconditions
- Portfolio performance series ready for display or storage
- No side effects on repository

## 6. Exception Handling
| Exception | Action |
|-----------|--------|
| `InsufficientDataException` | Log and return zero/empty series to caller |

## 7. References
- **FR:** `FR-PM-001-001`, `FR-PM-001-002`
- **UT:** `UT-PM-001-001-001`, `UT-PM-001-001-002`, `UT-PM-001-001-003`, `UT-PM-001-002-001`
- **UAT:** `UAT-PM-001-twr-integration`
