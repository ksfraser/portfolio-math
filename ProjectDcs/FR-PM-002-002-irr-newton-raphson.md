# FR-PM-002-002-irr-newton-raphson

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Requirement

Implement Newton-Raphson root-finding for IRR with bounded convergence.

Algorithm:
- Start at r = 10%
- Iterate: next = current - NPV/derivative(NPV)
- Clamp to (-99%, 50%) on each iteration
- Max 1000 iterations; tol = 1e-8

## 2. Acceptance Criteria
1. Converges for standard cashflow series within 100 iterations
2. Throws `ConvergenceException` after 1000 failed iterations
3. Protected against derivative = 0 (clamp denominator)

## 3. References
- Java reference: `name.abuchen.portfolio.math.IRR` in ksfraser/portfolio fork
- **Related FR:** `FR-PM-002-001`

## 4. Implementation
`IRRCalculatorService::newtonRaphsonIRR()`, `IRRCalculatorService::npv()`, `IRRCalculatorService::npvDerivative()`
`@BABOK Related: FR-PM-002-002`

## 5. Test Coverage
`UT-PM-002-001-001` exercises Newton solver implicitly
