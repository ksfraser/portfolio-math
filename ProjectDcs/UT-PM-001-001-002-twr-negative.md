# UT-PM-001-001-002-twr-negative

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify TWRCalculatorService returns negative TWR when portfolio declines.

## 2. Test Data
- Portfolio `P1`: valuation 1000 on 2026-01-01, 800 on 2026-01-02

## 3. Expected Result
`getTWR()` ≈ -0.2000 (±0.001)

## 4. References
- **FR:** `FR-PM-001-001`
- **UC:** `UC-PM-001-calculate-portfolio-twr`
- **Implementation:** `TWRCalculatorServiceTest::testNegativeTWR()`
