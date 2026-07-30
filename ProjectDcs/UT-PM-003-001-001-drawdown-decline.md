# UT-PM-003-001-001-drawdown-decline

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify max drawdown is calculated correctly for declining series after peak.

## 2. Test Data
- 1000 → 1100 → 800

## 3. Expected Result
`getMaxDrawdown()` ≈ 0.2727 (±0.001)

## 4. References
- **FR:** `FR-PM-003-001`
- **UC:** `UC-PM-003-calculate-drawdown`
- **Implementation:** `DrawdownCalculatorServiceTest::testMaxDrawdownWithDecline()`
