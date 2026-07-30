# FR-PM-001-001-twr-calculation

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Requirement

Calculate True Time-Weighted Return (TWR) for a portfolio over a reporting interval.

Inputs:
- Daily portfolio valuation series (total value, inbound/outbound transfers)
- Start date, end date

Outputs:
- TWR (total period return, geometric chain)
- Daily delta returns array `YYYY-MM-DD => float`
- Daily accumulated return array `YYYY-MM-DD => float`

Formula:
```text
delta[i] = (total[i] + outbound[i]) / (prevTotal + inbound[i]) - 1
accumulated[i] = ((accumulated[i-1] + 1) * (delta[i] + 1)) - 1
```

## 2. Acceptance Criteria
1. Given 2 valuations with no transfers, TWR = (end/start) - 1
2. Given 2 valuations with inbound transfer, TWR adjusts denominator
3. Given 1 valuation, throws `InsufficientDataException`
4. Returns `TWRResultDTO` with `getTWR()`, `getAnnualizedTWR()`, `getDays()`

## 3. References
- **UML:** `ProjectDcs/UML.md` — TWR sequence diagram
- **Related BR:** `BR-PM-001-shared-performance-math.md`

## 4. Implementation
`src/KSF/Performance/Services/TWRCalculatorService::calculate()`
`@BABOK Related: FR-PM-001-001`

## 5. Test Coverage
`UT-PM-001-001-001`, `UT-PM-001-001-002`, `UT-PM-001-001-003`, `UT-PM-001-002-001`, `UT-PM-001-002-002`
