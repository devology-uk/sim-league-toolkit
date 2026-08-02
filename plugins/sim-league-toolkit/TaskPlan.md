# SIM League Toolkit — Task Plan

Living plan doc, updated as work progresses. For architecture reference see `CLAUDE.md`. For the
full theme feature inventory and reasoning behind decisions below, see project memory (Claude Code
maintains this across sessions — ask it to recall if more detail is needed than fits here).

## Purpose

SLTK re-implements the admin tooling from the `acc-league-tools` theme as a standalone WordPress
plugin, so any league admin can add sim-racing-league features to an existing site without needing
to adopt a whole custom theme or edit code. Three-part scope:

1. **Admin SPA** — React/TS dashboard for creating/managing championships, standalone events,
   entrants, classes, scoring, rules, servers. *In progress.*
2. **Gutenberg blocks** — front-end blocks so admins can build public-facing pages (standings,
   entrant lists, schedules, etc.) in any theme. *Not started.*
3. **Prebuilt SLTK theme** — turnkey site built on the plugin, for admins who want the old theme's
   out-of-the-box experience. *Not started.*

## Current priority

Reach feature parity with `acc-league-tools` so it can be *replaced*, rather than diverting effort
into adding a third game (Assetto Corsa Evo) to the theme again — avoids doing the ACE work twice.

## Sequencing plan

1. ✅ **Wait-list support for event entrants** — per-class and championship/event-wide entrant caps,
   auto-waitlisting on creation, promotion on cancellation, status shown in entrant UI, plus the
   editable per-class Max Entrants field on the class-assignment screen (the piece that made it
   actually usable). Done and confirmed working by Mike 2026-08-02.
2. ⏭ **Manual result entry** *(next up)* — game-agnostic core for admins to enter session results by
   hand. Deliberately built before any import facility, so a brand-new game can be supported quickly
   via manual entry alone.
3. **Per-game result import** — parsing/import per game, built on manual entry. The theme's biggest
   complexity area (3 separate bespoke parsers for ACC/AMS2 old/AMS2 new) — needs a real
   `ResultParser`-per-`GameKey` abstraction here, not the theme's string-branching approach.
4. **Member CSV import** — bulk-onboard an existing league's roster. Note: per-user SLTK fields
   (Steam/PSN/Xbox ID, nationality, race number) already exist via `Core/UserProfileExtension.php` on
   WordPress's native Edit User screen — the gap is specifically bulk creation/import, since "Add New
   User" doesn't surface those fields and there's no one-at-a-time-avoiding path today.
5. **Demo/test data seed-and-clean tool** — quickly populate realistic test data and clean it up
   again, mirroring the theme's `demo-admin-page.php`. Convenience for Mike and for admins evaluating
   the plugin, not automated test infrastructure.

## Parity gap analysis (vs. `acc-league-tools` theme)

Full inventory done 2026-08-01 — snapshot, re-verify specifics against the theme if acting on this
later.

- Theme has ~90 admin page/tab files; most are mechanical CRUD that maps cleanly onto SLTK's
  existing Domain/Repository/API pattern — low risk to port.
- Theme is a **full public-facing site** (~30 page templates: standings, driver profiles, teams,
  registration flows, notifications, time trials, trophies, sponsors, pre-season plan voting). SLTK
  is currently admin-SPA-only — the Gutenberg block layer needs to eventually cover all of this, a
  much bigger scope than "a few blocks."
- **Five effort-heavy areas**, none exist in SLTK yet:
  1. Result import/parsing (see sequencing item 3 above)
  2. Car/track ID reconciliation per game (hand-maintained alias tables in the theme)
  3. Server config file generation (game-specific server config ZIP download)
  4. Standings/scoring calculation engine
  5. Championship pre-season plan/voting subsystem (sizeable, arguably deferrable)
- Smaller missing subsystems: teams (formation/invite/request), race-number pre-allocation,
  in-app notifications, time trials, trophies, sponsors, automatic podium-penalty/BoP carry-forward
  (theme hard-gates this to ACC only — may not generalize).
- **Game-coupling anti-pattern to avoid**: the theme has no game abstraction — `gameType` is a plain
  string, checked via scattered `if ($gameType === 'AMS2')` branches; AMS2 support was added by
  copy-pasting classes/tables (e.g. fully duplicated `Server`/`Ams2Server` models). SLTK's
  `Config/*.json` + `GameConfigProvider` + `Domain\Game` is a better foundation (data-driven server
  settings/session schemas) but currently only covers settings, not result parsing or game-specific
  scoring rules — those will need a real strategy/provider interface, not more JSON, to hold up once
  ACE (or any future sim) is added.

## Recent notable fixes (2026-08-01 – 2026-08-02)

- Fixed 41 mutation hooks across the frontend that discarded the `invalidateQueries()` promise
  (`.then(() => {})` instead of `await`), causing stale data to briefly reappear if a just-saved item
  was reopened quickly.
- Fixed a loading-state bug in `ChampionshipEntrants`/`StandaloneEventEntrants` that showed "you must
  assign a class first" while the classes query was still loading, not just when genuinely empty.
- Fixed a `carClasses` vs `car-classes` route naming mismatch causing 404s when editing a custom
  event class.
- Added the missing frontend UI for per-class `maxEntrants` (backend already had it, no UI ever
  called it) — see sequencing item 1 above.
- Found (and locally neutralized) a pre-existing global CSS bug: `.p-button { margin-top: 1rem
  !important; }` in `src/admin/index.scss` applies to every button in the admin app, unscoped. Left
  in place since other layouts may depend on it, but worth properly scoping if it causes trouble
  elsewhere — a misaligned button with no obvious cause is probably this.
