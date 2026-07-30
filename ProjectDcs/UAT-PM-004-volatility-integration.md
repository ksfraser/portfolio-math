# UAT-PM-004-volatility-integration

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

End-to-end integration: annualized volatility matches expected risk profile.

## 2. Steps

| # | Action | Expected |
|---|--------|----------|
| 1 | Load 1-year daily valuations | Sorted series |
| 2 | Trigger `VolatilityCalculatorService` | Returns `VolatilityResultDTO` |
| 3 | Compare against Excel STDEV.S(log returns) × sqrt(252) | < 1% delta |

## 3. Acceptance Criteria
- [ ] UI volatility chart agrees with isolated library call
- [ ] Semi-deviation ≤ total std deviation

## 4. Traceability
- **BR:** `BR-PM-001-shared-performance-math.md`
- **FR:** `FR-PM-004-001-volatility-calculation.md`
- **UC:** `UC-PM-004-calculate-volatility`
- **UT:** `UT-PM-004-001-001` through `UT-PM-004-002-001`
