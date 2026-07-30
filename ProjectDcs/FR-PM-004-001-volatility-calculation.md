# FR-PM-004-001-volatility-calculation

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Requirement

Calculate volatility of portfolio returns over a reporting interval.

Inputs:
- Sorted valuation series

Outputs:
- Sample standard deviation of log-returns
- Annualized standard deviation (`× √252`)
- Semi-deviation (downside only)
- Annualized semi-deviation

Formula:
```text
diff_i = log(1 + return_i) - log(1 + mean_return)
stdDeviation = sqrt(sum(diff_i^2)/(n-1)) * sqrt(n)
```

## 2. Acceptance Criteria
1. Flat series → stdDeviation = 0
2. Oscillating series → stdDeviation > 0
3. Fewer than 2 valuations → `InsufficientDataException`

## 3. References
- Java reference: `name.abuchen.portfolio.math.Risk.Volatility`
- **Related BR:** `BR-PM-001-shared-performance-math.md`

## 4. Implementation
`src/KSF/Performance/Services/VolatilityCalculatorService::calculate()`
`@BABOK Related: FR-PM-004-001`

## 5. Test Coverage
`UT-PM-004-001-001`, `UT-PM-004-001-002`, `UT-PM-004-001-003`
