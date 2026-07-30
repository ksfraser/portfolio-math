# UT-PM-004-001-001-volatility-flat

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify zero volatility for constant portfolio value.

## 2. Test Data
- Portfolio `P1`: [1000, 1000, 1000]

## 3. Expected Result
`getStdDeviation()` ≈ 0

## 4. References
- **FR:** `FR-PM-004-001`
- **UC:** `UC-PM-004-calculate-volatility`
- **Implementation:** `VolatilityCalculatorServiceTest::testZeroVolatilityForFlatLine()`
