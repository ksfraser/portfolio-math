# UT-PM-003-001-002-drawdown-no-decline

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify zero drawdown when series never declines.

## 2. Test Data
- 1000 → 1200

## 3. Expected Result
`getMaxDrawdown()` ≈ 0.0

## 4. References
- **FR:** `FR-PM-003-001`
- **UC:** `UC-PM-003-calculate-drawdown`
- **Implementation:** `DrawdownCalculatorServiceTest::testNoDeclineReturnsZeroDrawdown()`
