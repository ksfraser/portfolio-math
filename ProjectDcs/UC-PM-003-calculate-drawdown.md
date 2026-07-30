# UC-PM-003-calculate-drawdown

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Primary Actor
- stockmarket app
- ksfii_app / WealthSystem

## 2. Preconditions
1. Valuation series loaded and sorted by date

## 3. Main Success Scenario

| Step | Action |
|------|--------|
| 1 | Actor instantiates `DrawdownCalculatorService` with repository |
| 2 | Actor calls `calculate(portfolioId, startDate, endDate)` |
| 3 | Service walks series, tracks peak/trough |
| 4 | Returns `DrawdownResultDTO` |
| 5 | Actor reads `getMaxDrawdown()`, `getDrawdownSeries()` |

## 4. Alternate Flows
- 3a: Fewer than 2 valuations → `InsufficientDataException`

## 5. References
- **FR:** `FR-PM-003-001`, `FR-PM-003-002`
- **UT:** `UT-PM-003-001-001`, `UT-PM-003-001-002`, `UT-PM-003-001-003`, `UT-PM-003-002-001`
