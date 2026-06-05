# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Stack

PHP application (no framework) served via Apache/Nginx. Dependencies managed via Composer:
- `stripe/stripe-php` ^7.100 — payment processing
- `ivan1986/dev-container` ^0.4.2 — dev environment helper

No build step. No test suite. Deployment is direct file serving.

## Configuration

Global constants and DB credentials live in `utils/defines.php` (included first in nearly every page). There is no `config.php` at root — configuration is inline in `defines.php`. The hardcoded `set_include_path` calls in class files point to `/var/www/instagrowth-V1/`, which is the production server path.

## Architecture

**Entry points** — Each page is a standalone `.php` file at root (e.g. `index.php`, `checkout.php`, `inscription-second.php`). Pages include shared layout partials and instantiate classes directly with no routing layer.

**Shared layout** — Every page includes:
- `utils/defines.php` — global constants, DB credentials, URLs, feature flags
- `utils/instagrowth-les-influenceurs-header-meta.php` — `<head>` tags
- `utils/instagrowth-les-influenceurs-header-menu.php` — nav (FR) or `-en.php` for EN
- `utils/instagrowth-les-influenceurs-footer.php` — footer

**Class layer** (`class/`) — Plain PHP classes, no namespace, no autoloader:
- `mysql.class.php` (`MMsql`) — mysqli connection wrapper
- `user.class.php` (`MUser`) — user account operations
- `instagrowth.class.php` (`MInstagrowth`) — core product logic (subscriptions, Instagram profile)
- `instagram.class.php` (`MInstagram`) — Instagram data ingestion
- `paiement.class.php` (`Paiement`) — subscription/pricing queries
- `mailgun.class.php` — transactional email via Mailgun
- `mailovh.class.php` — alternative mailer (OVH)
- `traduction.class.php` — FR/EN string lookup
- `buildmenu.class.php` (`Mbuildmenu`) — renders the backoffice sidebar menu
- `cleaninput.class.php` — input sanitization
- `cryptor.class.php` — encryption helpers
- `ticket.class.php`, `support.class.php` — support ticket system
- `invoice.class.php` — invoice generation
- `waf.class.php` — basic WAF/rate-limiting

**Backoffice** — Files prefixed `backoffice-` are the authenticated customer dashboard (stats, account settings, hashtags, follow-back tracking). Auth is session-based (`$_SESSION['name42']`). `backoffice-authentification.php` is the login page.

**Signup funnel** — Multi-step flow: `inscription-page.php` → `inscription-second.php` → `inscription-third.php` → `inscription-fourth.php` → `inscription-fifth.php`. Multiple dated variants exist (`-2019`, `-2021`, `-2024`) as historical snapshots — the canonical current files have no date suffix.

**Webservices** (`webservices/`) — Internal HTTP endpoints called by the Instagram data collection pipeline to insert metrics (impressions, reach, followers, gender, location) into the DB.

**Admin** (`admin/`) — Password reset flow and email validation.

**Blog** (`blog/`) — Static HTML blog posts, no PHP.

## Multilingual

FR is default. EN variants use the `-en` suffix on both pages (`index-en.php`) and partials (`header-menu-en.php`, `footer-en.php`). The `traduction.class.php` class handles runtime string switching.

## File naming conventions

- Dated files (e.g. `backoffice-user-general-2024-10-07.php`) are backups/snapshots — do not edit them.
- Files prefixed with `#` (e.g. `#checkout.php#`) are Emacs auto-save artifacts — ignore them.
- Files ending with `~` are editor backup files — ignore them.
- Active production files have no date suffix and no `~`.
