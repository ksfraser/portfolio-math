# UT-PM-004-002-001-semi-deviation-order

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify semi-deviation ≤ total standard deviation.

## 2. Test Data
- Portfolio `P1`: [1000, 1100, 900, 1200, 800]

## 3. Expected Result
`getSemiDeviation()` ≤ `getStdDeviation()`

## 4. References
- **FR:** `FR-PM-004-002`
- **UC:** `UC-PM-004-calculate-volatility`
- **Implementation:** `VolatilityCalculatorServiceTest::testSemiDeviationLessOrEqual()`
