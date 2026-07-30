# UT-PM-002-001-003-irr-insufficient

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify `InsufficientDataException` when no transactions exist.

## 2. Test Data
- Portfolio `P1` with no transactions or valuations

## 3. Expected Result
Throws `InsufficientDataException`

## 4. References
- **FR:** `FR-PM-002-001`
- **UC:** `UC-PM-002-calculate-portfolio-irr`
- **Implementation:** `IRRCalculatorServiceTest::testThrowsInsufficientDataWhenNoTransactions()`
