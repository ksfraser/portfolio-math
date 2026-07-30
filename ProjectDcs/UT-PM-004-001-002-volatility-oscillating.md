# UT-PM-004-001-002-volatility-oscillating

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify positive volatility for oscillating values.

## 2. Test Data
- Portfolio `P1`: [1000, 1100, 900, 1200, 800]

## 3. Expected Result
`getStdDeviation()` > 0, `getAnnualizedStdDeviation()` > 0

## 4. References
- **FR:** `FR-PM-004-001`
- **UC:** `UC-PM-004-calculate-volatility`
- **Implementation:** `VolatilityCalculatorServiceTest::testVolatilityPositiveForOscillatingValues()`
