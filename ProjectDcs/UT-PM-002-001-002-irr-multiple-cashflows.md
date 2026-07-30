# UT-PM-002-001-002-irr-multiple-cashflows

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify IRR converges with two investments before terminal realization.

## 2. Test Data
- Transaction -1000 on 2026-01-01
- Transaction -500 on 2026-07-01
- Terminal valuation +2000 on 2027-01-01

## 3. Expected Result
`getIRR()` ≈ 0.4062 (±0.001)

## 4. References
- **FR:** `FR-PM-002-001`, `FR-PM-002-002`
- **UC:** `UC-PM-002-calculate-portfolio-irr`
- **Implementation:** `IRRCalculatorServiceTest::testTwoInvestmentsThenRealization()`
