# UAT-PM-002-irr-integration

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

End-to-end integration acceptance: IRR for AAC/KEG.UN matches manual calculation.

## 2. Steps

| # | Action | Expected |
|---|--------|----------|
| 1 | Load transactions from stockmarket `portfolio_transactions` | Dates and signed amounts read correctly |
| 2 | Fetch terminal valuation from stockmarket `portfolio` table | Value matches last market close |
| 3 | Trigger `IRRCalculatorService` via wrapper | Returns `IRRResultDTO` with converging IRR |

## 3. Acceptance Criteria
- [ ] IRR from stockmarket UI = IRR from isolated PHP call within rounding
- [ ] Cashflow series matches transaction history count
- [ ] No `ConvergenceException` for standard 1–3 year windows

## 4. Traceability
- **BR:** `BR-PM-001-shared-performance-math.md`
- **FR:** `FR-PM-002-001-irr-calculation.md`
- **UC:** `UC-PM-002-calculate-portfolio-irr.md`
- **UT:** `UT-PM-002-001-001` through `UT-PM-002-001-003`
