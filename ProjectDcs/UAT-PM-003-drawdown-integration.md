# UAT-PM-003-drawdown-integration

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

End-to-end integration: max drawdown shown on stockmarket dashboard matches library.

## 2. Steps

| # | Action | Expected |
|---|--------|----------|
| 1 | Load 2-year daily valuations for KEG.UN+RSP portfolio | Sorted date series |
| 2 | Trigger `DrawdownCalculatorService` | Returns `DrawdownResultDTO` |
| 3 | Compare peak/peak date vs. chart | Matches visual peak |

## 3. Acceptance Criteria
- [ ] Max drawdown displayed in UI matches library result
- [ ] Recovery days displayed when trough → peak exists

## 4. Traceability
- **BR:** `BR-PM-001-shared-performance-math.md`
- **FR:** `FR-PM-003-001-drawdown-calculation.md`
- **UC:** `UC-PM-003-calculate-drawdown`
- **UT:** `UT-PM-003-001-001` through `UT-PM-003-002-001`
