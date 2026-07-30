# UC-PM-004-calculate-volatility

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
| 1 | Actor instantiates `VolatilityCalculatorService` with repository |
| 2 | Actor calls `calculate(portfolioId, startDate, endDate)` |
| 3 | Service computes log-return std dev and semi-deviation |
| 4 | Returns `VolatilityResultDTO` |
| 5 | Actor reads `getStdDeviation()`, `getAnnualizedStdDeviation()`, `getSemiDeviation()` |

## 4. Alternate Flows
- 3a: Fewer than 2 valuations → `InsufficientDataException`

## 5. References
- **FR:** `FR-PM-004-001`, `FR-PM-004-002`
- **UT:** `UT-PM-004-001-001`, `UT-PM-004-001-002`, `UT-PM-004-001-003`, `UT-PM-004-002-001`
