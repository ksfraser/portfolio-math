# FR-PM-003-002-drawdown-recovery-duration

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Requirement

Return number of days between peak and trough for the max drawdown interval.

Output:
- `recoveryDays` in `DrawdownResultDTO`

## 2. Acceptance Criteria
1. Peak and trough on consecutive days → recoveryDays = 1
2. Same-day peak/trough → recoveryDays = 0

## 3. References
- **Related FR:** `FR-PM-003-001`

## 4. Implementation
`DrawdownCalculatorService` computes `recoveryDays` via `DateTime::diff()`
`@BABOK Related: FR-PM-003-002`

## 5. Test Coverage
`UT-PM-003-002-001`
