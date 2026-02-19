# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

SIM League Toolkit (SLTK) is a WordPress plugin for managing sim racing leagues. It provides an admin dashboard built with React/TypeScript that communicates with a PHP REST API backend. Requires WordPress 6+ and PHP 8.3+.

## Build Commands

```bash
npm run build            # Production build (auto-generates enums first)
npm start                # Dev mode with file watching (auto-generates enums first)
npm start:hot            # HMR dev server on port 3000 (auto-generates enums first)
npm run generate:enums   # Generate TypeScript enums from PHP enums (runs automatically via pre-scripts)
```

Build output goes to `build/admin/`. No test framework is configured.

## Architecture

### PHP Backend (SLTK namespace)

**Autoloading**: Custom PSR-4-style autoloader in `Core/AutoLoader.php`. The `SLTK` namespace root maps to the plugin directory. Subdirectories are lowercase, class files are PascalCase (e.g., `SLTK\Domain\Championship` → `domain/Championship.php`).

**Domain Layer** (`Domain/`): Entities use the Aggregate Root pattern. Key abstractions in `Domain/Abstractions/`: `AggregateRoot`, `Saveable`, `Deletable`, `Listable`, `ProvidesPersistableArray`. Shared behavior via traits in `Domain/Traits/`.

**API Layer** (`Api/`): REST API at namespace `sltk/v1`. Controllers extend `ApiController` and compose behavior using traits (`Api/Traits/`): `HasGet`, `HasGetById`, `HasPost`, `HasPut`, `HasDelete`. Route resolution uses regex pattern matching in `ApiRegistrar::$routeMap`. Standardized responses via `ApiResponse`.

**Database Layer** (`Database/`): Each table has a `TableBuilder` class. `DatabaseBuilder::initialiseOrUpdate()` runs on plugin activation using WordPress `dbDelta()`. Repositories in `Database/Repositories/` handle persistence via `wpdb`. Table names defined in `TableNames.php`.

**Core Utilities** (`Core/`): `MenuManager`, `ScriptManager`, `StyleManager` handle WordPress integration. `DevServer` detects HMR dev server for asset loading. `Constants` defines date formats, permissions, and action names.

**Game Configs** (`Config/`): JSON files (`acc.json`, `ams2.json`, `iRacing.json`, `lmu.json`) define server settings per game. Seed data CSVs in `data/`.

### React/TypeScript Frontend (`src/admin/`)

**Entry point**: `src/admin/index.tsx` → `SimLeagueToolkitApp.tsx`. Renders into `#sltk-admin-root`.

**Component structure**: Feature modules in `components/` (championships, games, rules, scoringSets, servers, events, eventClasses, etc.). Each feature typically has `List.tsx`, `Card.tsx`, `Editor.tsx`, and `Selector.tsx`. Shared components in `components/shared/`.

**Data hooks** (`hooks/`): Each entity has a custom hook (e.g., `useChampionships`) that returns CRUD operations and state. Hooks use `ApiClient` for requests.

**API client** (`api/ApiClient.ts`): Wraps `@wordpress/api-fetch` with the `sltk/v1` namespace. Shows WordPress notices on errors automatically. Endpoint paths defined in `api/endpoints/`.

**Types** (`types/`): TypeScript interfaces mirror PHP domain entities. Base `Entity` interface with `id` field. Generated enums in `enums/` (from PHP enums via `scripts/generate-enums.php`).

**UI libraries**: PrimeReact components, PrimeFlex utilities, Tailwind CSS 4, FontAwesome icons.

**Navigation**: Hash-based routing with `useHashState()` hook. Responsive sidebar that collapses at 960px/782px breakpoints.

### Plugin Lifecycle

1. **Activation**: `SimLeagueToolkitPlugin::activate()` → runs database migrations → validates PHP enums
2. **Runtime**: `plugin_loaded` hook → registers menus, styles, scripts, API routes
3. **Request flow**: React UI → `ApiClient` → WP REST API → `ApiRegistrar` resolves controller → controller hydrates domain entity → repository persists via `wpdb`

## Key Conventions

- PHP namespace: `SLTK\*` with lowercase directory names
- API controllers use trait composition, not inheritance, for HTTP method support
- TypeScript enums are auto-generated from PHP — edit the PHP enum source, not the generated TS files
- All user-facing strings use WordPress i18n (`__()` in PHP, `__()` from `@wordpress/i18n` in TS), text domain: `sim-league-toolkit`
- Default entity ID is `-1` (defined in `Constants::DEFAULT_ID`)
- WordPress capabilities used: `manage_options`, `edit_user`