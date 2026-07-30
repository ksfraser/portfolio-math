# UT-PM-001-001-003-twr-insufficient

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify `InsufficientDataException` when fewer than 2 valuations exist.

## 2. Test Data
- Portfolio `P1`: single valuation 1000 on 2026-01-01

## 3. Expected Result
Throws `InsufficientDataException`

## 4. References
- **FR:** `FR-PM-001-001`
- **UC:** `UC-PM-001-calculate-portfolio-twr`
- **Implementation:** `TWRCalculatorServiceTest::testThrowsInsufficientDataForSingleValuation()`
