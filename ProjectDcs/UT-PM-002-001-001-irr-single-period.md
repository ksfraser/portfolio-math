# UT-PM-002-001-001-irr-single-period

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify IRR of 10% for single-period investment returning 10% gain.

## 2. Test Data
- Transaction -1000 on 2026-01-01
- Terminal valuation +1100 on 2027-01-01

## 3. Expected Result
`getIRR()` ≈ 0.10 (±0.001)

## 4. References
- **FR:** `FR-PM-002-001`
- **UC:** `UC-PM-002-calculate-portfolio-irr`
- **Implementation:** `IRRCalculatorServiceTest::testSingleInvestmentPeriodReturnsExpectedIRR()`
