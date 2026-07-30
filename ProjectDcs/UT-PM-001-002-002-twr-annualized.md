# UT-PM-001-002-002-twr-annualized

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify annualized TWR is positive for multi-day period with growth.

## 2. Test Data
- Portfolio `P1`: 11 valuations growing at ~1%/day

## 3. Expected Result
`getTWR()` > 0, `getAnnualizedTWR()` > 0, `getDays()` = 10

## 4. References
- **FR:** `FR-PM-001-001`, `FR-PM-001-002`
- **UC:** `UC-PM-001-calculate-portfolio-twr`
- **Implementation:** `TWRCalculatorServiceTest::testAnnualizedTWROverMultipleDays()`
