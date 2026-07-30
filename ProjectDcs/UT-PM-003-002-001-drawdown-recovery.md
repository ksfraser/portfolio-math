# UT-PM-003-002-001-drawdown-recovery

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify recovery days is non-negative for trough-after-peak series.

## 2. Test Data
- 1000 → 1100 (peak) → 800 (trough) → 1100 (recovered)

## 3. Expected Result
`getRecoveryDays()` ≥ 0, `getMaxDrawdown()` ≈ 0.2727

## 4. References
- **FR:** `FR-PM-003-002`
- **UC:** `UC-PM-003-calculate-drawdown`
- **Implementation:** `DrawdownCalculatorServiceTest::testRecoveryDaysCalculated()`
