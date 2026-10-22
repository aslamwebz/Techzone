# Changelog

All notable changes to the **Techzone** project are documented in this file.

## [2.0.0] - 2026-10-22

### Security & Hardening
- Enforced PHP `~8.4.27 || ^8.5.12` constraint and platform lock to resolve PHP RCE vulnerabilities (CVE-2026-109618 / CVE-2026-109619).
- Clean security advisory profile confirmed via `composer audit` (0 vulnerabilities).
- Implemented transactional checkout with row-level pessimistic locking (`lockForUpdate()`) to verify inventory before order completion.
- Secured mass-assignment vectors in `User` and `ProfileController` to prevent privilege escalation.
- Added rate limiting on sensitive routes: login (`throttle:5,1`), checkout (`throttle:10,1`), and cart (`throttle:60,1`).
- Implemented authorization gates and model policies (`ProductPolicy`, `CategoryPolicy`, `UserPolicy`, `OrderPolicy`).
- Replaced inline validation with dedicated Form Requests across all administrative and customer forms.
- Configured Google Authenticator Two-Factor Authentication with dedicated verification flows.

### Major Upgrades
- Upgraded Laravel framework from `12.x` to `13.38.0`.
- Upgraded Filament admin panel from `3.x` to `5.10.3`.
- Upgraded Livewire from `3.x` to `4.4.8` and Livewire Volt to `1.11.2`.
- Migrated design system to Tailwind CSS `v4.3.3` with `@tailwindcss/postcss` and Vite `6.3.5`.
- Upgraded Pragmarx Google2FA Laravel to `v3.0.1`, Owen-It Auditing to `v14.0.6`, Sentry to `v4.29.0`, Spatie Honeypot to `v4.7.3`.
- Replaced abandoned `hardevine/shoppingcart` package with an in-house session-backed `Cart` and `CartItem` service.
- Removed deprecated `doctrine/dbal` dependency.

### Performance & Quality
- Resolved N+1 query patterns across catalog, product details, new arrivals, and search.
- Corrected database column mismatch (`sale_price` instead of non-existent `compare_at_price`).
- Added Order and OrderItem Eloquent models, migrations, and administrative views.
- Expanded automated test coverage from 2 skeleton tests to 67 unit and feature tests (197 assertions).
- Automated CI pipeline via GitHub Actions on PHP 8.4 with automated Pint, audit, build, and test steps.
- Configured automated Dependabot dependency updates across Composer, NPM, and GitHub Actions.

## [1.0.0] - 2025-05-24
- Initial release with Laravel e-commerce storefront, admin panel, and authentication.
