# ♊ GEMINI.md — ANTIGRAVITY & GEMINI SYSTEM DIRECTIVES
**Website:** Full Drone Solutions (FDS) — PT Karya Solusi Angkasa (Website Company Profile B2B Enterprise)  
**Last Updated:** 2026-11-12 (`v2.5.1`)  
**Master Instructions:** Refer to [`AGENTS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/AGENTS.md), [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md), [`PROJECT_MEMORY.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PROJECT_MEMORY.md), and [`PRD.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PRD.md).

---

## ⚡ QUICK RULES FOR GEMINI & ANTIGRAVITY
1. **Never use ALL-CAPS (`uppercase`)** on UI elements; always write `FDS Station` (never `FDS STATION`).
2. **Never create nested container slop** ("card inside card", unnecessary pill wrappers).
3. **Never place decorative dots/pulses** beside typography.
4. **Brand Color is strictly `#0f808a`** (Teal Palette: primary `#0f808a`, hover `#0a555c`, dark `#052b2e` / `#031e20`). Never use `#0066cc`.
5. **Logo transparency** must use `mix-blend-mode: multiply !important; background-color: transparent !important;` without parent opacity or transform wrappers.
6. **Footer Layout:** Column 4 (Dukungan) has partner logos locked bottom-right flowing left; middle row has PT info on left (`col-span-8`) and Social Media on right (`col-span-4`).
7. **Business & Compliance Guardrails:** Brand = `Full Drone Solutions`, Legal = `PT Karya Solusi Angkasa` (founded `2019`). Keep `No.1`; ban `Best UAV`, `sejak 2012`, `IP68`/`IP67`/`IP54`, `FLIR`, unverified SLAs (`48 jam`, `1×24 jam`, `24/7`, `±2.35%`), and `priceRange: "$$$$"`. **TKDN 60,74% = FERTO series only**; **SNI 9199:2023 = FERTO 15 & FERTO 22 only**; **ISO 9001:2015 = corporate/manufacturing QMS (PT Karya Solusi Angkasa) only — NEVER attach ISO 9001:2015 to individual drone unit specs/cards/rows**; **FERTO 15 = `17 Liter`**.
8. **UI Parity & Wave Vectors:** Every dark teal `<section>` needs fill-only organic gradient wave SVGs (zero `stroke`). Interactive cards/CTAs use diagonal arrow (`↗`), accordions use downward chevron (`⌄`, no `+`/`-`), and breadcrumbs never start with leading `/`.
9. **Homepage vs Catalog Drone Layout:** Keep `.drone-row` horizontal list on Homepage `#produk`; keep uniform `<x-drone-card>` grids (`object-cover`) on `/produk/` and `/kategori-drone/*`.
10. **SSR & Zero Double-Escaping:** Keep FAQ `<details>` and specs SSR in initial HTML. Decode dynamic text with `fds_clean_string()` + `fds_scrub_legacy_text()`; never double-escape `{{ esc_html/esc_attr/esc_url }}`; use `{!! esc_url(...) !!}` for dynamic query URLs and `{!! wp_json_encode(...) !!}` for JS values.
11. **Follow SOP & Non-Destructive Rules:** Clean stale `public\build\*`, sync `functions.php` + `app\*` + `resources\*` + `public\build\*` (never `.agents/` or `scratch/`), and clear `..\wp-content\cache\acorn\framework\views\*`. Never overwrite user DB edits.
