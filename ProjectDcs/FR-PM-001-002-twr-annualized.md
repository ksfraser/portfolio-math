# FR-PM-001-002-twr-annualized

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Requirement

Compute the annualized TWR when the reporting period is not exactly 365 days.

Formula:
```text
annualizedTWR = pow(1 + accumulatedTWR, 365.0 / days) - 1
```

## 2. Acceptance Criteria
1. 1-day TWR of 10% → annualized ≈ pow(1.10, 365) - 1
2. 30-day TWR of 5% → annualized ≈ pow(1.05, 365/30) - 1
3. If days = 0, annualizedTWR = 0

## 3. References
- **Related FR:** `FR-PM-001-001`

## 4. Implementation
`TWRResultDTO::getAnnualizedTWR()` field set by `TWRCalculatorService`
`@BABOK Related: FR-PM-001-002`

## 5. Test Coverage
`UT-PM-001-002-001` (covered via `testAnnualizedTWROverMultipleDays`)
