# ksfraser/portfolio-math

Shared portfolio performance calculation library for the KSF stack.

## What It Is

A Composer package implementing portfolio performance algorithms extracted from
[portfolio-performance/portfolio](https://github.com/portfolio-performance/portfolio).
All business logic lives here; app-layer wrappers (stockmarket, ksfii_app,
FrontAccounting) only map data to the interfaces and display results.

## Consuming Apps

| App | Role |
|-----|------|
| `ksf_stockmarket` | PHP/Apache bare-metal; computes TWR/IRR for holdings dashboard |
| `ksfii_app` | WealthSystem forecasting; consumes performance metrics for scenarios |
| `FrontAccounting` (`ksf-fa`) | Pulls GL transactions → feeds package → dashboard widget |

## Facilities

| Feature | Class |
|---------|-------|
| TWR (True Time-Weighted Return) | `KSF\Performance\Services\TWRCalculatorService` |
| IRR (Internal Rate of Return) | `KSF\Performance\Services\IRRCalculatorService` |
| Max Drawdown | `KSF\Performance\Services\DrawdownCalculatorService` |
| Volatility / Semi-deviation | `KSF\Performance\Services\VolatilityCalculatorService` |

## Composability

Apps write an adapter that implements `TransactionRepositoryInterface`:

```php
final class StockmarketRepository implements TransactionRepositoryInterface {
    public function findByPortfolioAndRange(string $id, DateTimeInterface $start, DateTimeInterface $end): array { ... }
    public function findValuationsByPortfolioAndRange(string $id, DateTimeInterface $start, DateTimeInterface $end): array { ... }
}
```

Then:

```php
$twr = new TWRCalculatorService(new StockmarketRepository());
$result = $twr->calculate('P1', '2026-01-01', '2027-01-01');
echo $result->getTWR();
```

## Installation

```bash
composer require ksfraser/portfolio-math
```

## Tests

```bash
vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/
```

Target: 16/16 pass.

## Notes

- Follow `ksf(report):` commit prefix for any changes to reporting/math features.
- New requirements are individual files in `ProjectDcs/`.
