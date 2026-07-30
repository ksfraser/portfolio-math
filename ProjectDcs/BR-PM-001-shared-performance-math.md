# BR-PM-001-shared-performance-math

**Module:** Portfolio Math (`PM`)
**Version:** 1.0.0
**Date:** 2026-07-29
**Status:** Draft

---

## 1. Purpose

The KSF stack requires portfolio performance calculations in three applications:

- **stockmarket** — PHP/Apache bare-metal app
- **ksfii_app / WealthSystem** — PHP FA wrapper
- **FrontAccounting** (`ksf-fa`) — rootless Podman container

Each app currently has no performance-math layer. Duplicating the algorithms across apps violates DRY.

## 2. Business Goals

| ID | Goal |
|----|------|
| BG-PM-001 | Single source of truth for TWR, IRR, drawdown, volatility |
| BG-PM-002 | Consistent numbers across stockmarket, ksfii_app, FA |
| BG-PM-003 | Thin app-layer wrappers; all business logic in shared library |

## 3. Scope

In scope: TWR, IRR, drawdown, volatility, asset allocation drift.
Out of scope: PDF import, broker APIs, trade execution.
