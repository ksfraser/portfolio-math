# FR-PM-003-001-drawdown-calculation

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Requirement

Calculate maximum drawdown from a portfolio value series.

Inputs:
- Sorted valuation series

Outputs:
- Max drawdown fraction (0.0 = none, 0.3 = 30% decline)
- Peak date, trough date
- Drawdown series `YYYY-MM-DD => -fraction`

## 2. Acceptance Criteria
1. Flat series → 0 drawdown
2. 1000 → 1100 → 800 → maxDD = 0.2727
3. Drawdown series contains only negative values
4. Fewer than 2 valuations → `InsufficientDataException`

## 3. References
- Java reference: `name.abuchen.portfolio.math.Risk.Drawdown`
- **Related BR:** `BR-PM-001-shared-performance-math.md`

## 4. Implementation
`src/KSF/Performance/Services/DrawdownCalculatorService::calculate()`
`@BABOK Related: FR-PM-003-001`

## 5. Test Coverage
`UT-PM-003-001-001`, `UT-PM-003-001-002`, `UT-PM-003-001-003`
