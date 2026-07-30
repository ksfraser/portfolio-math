# UT-PM-001-002-001-twr-with-transfer

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify TWR formula adjusts denominator for inbound transfers.

## 2. Test Data
- Portfolio `P1`: Day1 valuation 1000, Day2 valuation 1200 with inbound 100

## 3. Expected Result
`getTWR()` ≈ 0.090909 (±0.001)

## 4. References
- **FR:** `FR-PM-001-001`
- **UC:** `UC-PM-001-calculate-portfolio-twr`
- **Implementation:** `TWRCalculatorServiceTest::testTWRWithInboundTransfer()`
