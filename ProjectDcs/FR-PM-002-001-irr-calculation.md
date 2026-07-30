# FR-PM-002-001-irr-calculation

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Requirement

Calculate Internal Rate of Return (IRR) for a portfolio over a reporting interval.

Inputs:
- Portfolio transactions (buy, sell, deposit, withdrawal) with dates
- Terminal portfolio valuation at end date

Outputs:
- IRR as decimal (e.g. 0.12 = 12%)
- Cashflow array `YYYY-MM-DD => signed amount`
- Currency code

## 2. Acceptance Criteria
1. Single investment: -1000 at t0, +1100 at t1 → IRR ≈ 0.10
2. No transactions → throws `InsufficientDataException`
3. Terminal valuation required; missing → throws `InsufficientDataException`

## 3. References
- **UML:** `ProjectDcs/UML.md` — IRR sequence diagram
- **Related BR:** `BR-PM-001-shared-performance-math.md`

## 4. Implementation
`src/KSF/Performance/Services/IRRCalculatorService::calculate()`
`@BABOK Related: FR-PM-002-001`

## 5. Test Coverage
`UT-PM-002-001-001`, `UT-PM-002-001-002`, `UT-PM-002-001-003`
