# UT-PM-004-001-003-volatility-insufficient

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

Verify `InsufficientDataException` when fewer than 2 valuations.

## 2. Test Data
- Portfolio `P1`: single valuation 1000

## 3. Expected Result
Throws `InsufficientDataException`

## 4. References
- **FR:** `FR-PM-004-001`
- **UC:** `UC-PM-004-calculate-volatility`
- **Implementation:** `VolatilityCalculatorServiceTest::testThrowsInsufficientData()`
