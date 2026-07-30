# FR-PM-004-002-semi-deviation

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Requirement

Compute downside semi-deviation using only log-returns below the mean.

Outputs:
- `semiDeviation` and `annualizedSemiDeviation` in `VolatilityResultDTO`

## 2. Acceptance Criteria
1. Semi-deviation ≤ total standard deviation
2. Semi-deviation = 0 when no log-return is below mean

## 3. References
- Java reference: `name.abuchen.portfolio.math.Risk.Volatility`
- **Related FR:** `FR-PM-004-001`

## 4. Implementation
`VolatilityCalculatorService` filters `logReturn < mean` before summing squares
`@BABOK Related: FR-PM-004-002`

## 5. Test Coverage
`UT-PM-004-002-001`
