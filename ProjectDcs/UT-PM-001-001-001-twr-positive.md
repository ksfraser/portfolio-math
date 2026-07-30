# UT-PM-001-001-001-twr-positive

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify TWRCalculatorService returns positive TWR when end value exceeds start value.

## 2. Test Data
- Portfolio `P1`: valuation 1000 on 2026-01-01, 1100 on 2026-01-02

## 3. Expected Result
`getTWR()` ≈ 0.1000 (±0.001)

## 4. References
- **FR:** `FR-PM-001-001`
- **UC:** `UC-PM-001-calculate-portfolio-twr`
- **Implementation:** `TWRCalculatorServiceTest::testPositiveTWRWithTwoValuations()`
