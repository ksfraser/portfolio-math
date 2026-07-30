# UC-PM-002-calculate-portfolio-irr

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
1. `TransactionRepositoryInterface` implementation exists
2. Transactions and terminal valuation available

## 3. Main Success Scenario

| Step | Action |
|------|--------|
| 1 | Actor instantiates `IRRCalculatorService` with repository |
| 2 | Actor calls `calculate(portfolioId, startDate, endDate)` |
| 3 | Service fetches transactions and terminal valuation |
| 4 | Newton-Raphson solves NPV = 0 |
| 5 | Returns `IRRResultDTO` |

## 4. Alternate Flows
- 3a: No transactions → `InsufficientDataException`
- 4a: No convergence in 1000 iterations → `ConvergenceException`

## 5. Postconditions
- IRR value available; cashflows and currency returned

## 6. Exception Handling
| Exception | Action |
|-----------|--------|
| `InsufficientDataException` | Return N/A to UI |
| `ConvergenceException` | Log and return null to caller |

## 7. References
- **FR:** `FR-PM-002-001`, `FR-PM-002-002`
- **UT:** `UT-PM-002-001-001`, `UT-PM-002-001-002`, `UT-PM-002-001-003`
- **UAT:** `UAT-PM-002-irr-integration`
