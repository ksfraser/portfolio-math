# UAT-PM-001-twr-integration

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

End-to-end integration acceptance: TWR displayed in stockmarket `DashboardController::overview()` matches the shared library value.

## 2. Scope
- stockmarket app on `192.168.1.102`
- Portfolio: KEG.UN, AAC holdings

## 3. Steps

| # | Action | Expected |
|---|--------|----------|
| 1 | Load portfolio history for AAC 2-year window | 12–24 daily valuations available |
| 2 | Trigger `TWRCalculatorService` via wrapper | Returns `TWRResultDTO` |
| 3 | Compare to manual Excel calculation | Difference < 0.05% |

## 4. Acceptance Criteria
- [ ] TWR fetched from stockmarket UI = TWR from isolated PHP call to library
- [ ] Annualized TWR displayed in UI with correct formatting
- [ ] No PHP notices/warnings in error_log during calculation

## 5. Traceability
- **BR:** `BR-PM-001-shared-performance-math.md`
- **FR:** `FR-PM-001-001-twr-calculation.md`
- **UC:** `UC-PM-001-calculate-portfolio-twr.md`
- **UT:** `UT-PM-001-001-001` through `UT-PM-001-002-002`
