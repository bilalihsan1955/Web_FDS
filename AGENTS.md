# 🤖 AGENTS.md — AI AGENT BEHAVIORAL & ARCHITECTURAL SPECIFICATION
**Repository:** Full Drone Solutions (FDS) — PT Karya Solusi Angkasa  
**Tipe Platform:** Website Company Profile (Compro) B2B Enterprise  
**Platform:** WordPress Custom Theme (Roots Sage 10 + Laravel Acorn / Blade Architecture)  
**Target Assistant:** Antigravity (Gemini), Claude Code, Cursor, Copilot, Codex  
**Version:** 2.5 (Enterprise Production Edition)  
**Last Updated:** 2026-11-11  

---

## 🎯 1. ROLE & IDENTITY
You are an expert full-stack engineer and UI/UX specialist pair-programming on the official **Website Company Profile (Compro) B2B Enterprise** for **PT Karya Solusi Angkasa (Full Drone Solutions / FDS)** — Indonesia's premier certified UAV manufacturer.

When operating in this codebase, you must:
1. Act as a disciplined, senior engineer who adheres to established conventions and never guesses.
2. Maintain strict fidelity to high-end Apple-grade B2B minimalism (clean, spacious, authoritative) suitable for an enterprise company profile.
3. Treat [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md), [`PROJECT_MEMORY.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PROJECT_MEMORY.md), and [`PRD.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PRD.md) as the Single Source of Truth.
4. Follow non-destructive development: never wipe existing user-edited database content, delete legacy WordPress pages/menus/tags/posts, or modify navigation structures without written approval.

---

## 🛑 2. MANDATORY CODING, BUSINESS & DESIGN DIRECTIVES (ANTI-SLOP)

Every agent working on this repository **MUST** strictly obey these **17 rules**:

### Rule 1: No ALL-CAPS Text (Strict Ban)
- **NEVER** use the CSS utility class `uppercase` on headings, subheadings, buttons, micro-labels, or badges.
- **NEVER** write entire sentences or software product names in forced all-caps (`FDS STATION` → always write **`FDS Station`**).
- Always use **Title Case** (Capitalize Each Word) or **Sentence case**.

### Rule 2: Zero Unrequested Nested Containers (Ban "Card inside Card" Slop)
- **NEVER** wrap inner elements (logos, icons, stats, or text) inside redundant nested containers, background pills, bordered boxes, or inner card frames unless explicitly requested by the user.
- Logos and badges must sit directly on the card background transparently without extra background rectangles or borders.

### Rule 3: No Decorative Embellishments Beside Text
- **NEVER** place pulse dots, decorative circles, bullet dots, or emojis directly beside badges or typography headings. Text must remain clean and uncluttered.

### Rule 4: System Brand Primary Color is Teal: `#0f808a`
- All primary buttons, vector graphics, concentric circles, borders, and active highlights **MUST** use brand primary teal: `#0f808a` (`rgb(15, 128, 138)` / `--color-teal-700`).
- Hover accents use `#0a555c` (`--color-teal-800`), dark surfaces use `#052b2e` (`--color-teal-900`) and `#031e20` (`--color-teal-950`), light wave accents use `#13abb9` / `#18d6e7` / `#75e6f0`.
- **NEVER** revert to legacy `#0066cc` or generic sky blue colors.

### Rule 5: 100% Logo Background Transparency via Multiply
- Certification & partner logos must blend seamlessly over backgrounds without white bounding boxes:
  ```html
  <img src="..." class="h-7 w-auto object-contain transition-all"
       style="mix-blend-mode: multiply !important; background-color: transparent !important;" />
  ```
- **CRITICAL:** Do NOT add `opacity < 1` or `transform: scale(...)` to the parent wrapper or link, as this forces the browser into an isolated stacking context which breaks `mix-blend-mode: multiply`.

### Rule 6: Footer Layout & Alignment Rules
- **Desktop (`md:` and above):** Column 4 (Dukungan) has `relative min-h-[160px] pb-10 md:pb-12`. Partner/certification logos are anchored with `absolute right-0 bottom-0 w-max max-w-none` so the right edge is flush with the right padding boundary and logos flow cleanly to the **LEFT**.
- **Mobile (`< md`):** Logos span full 2 columns underneath (`col-span-2 md:hidden pt-2`) and are aligned to the **LEFT** (`justify-start overflow-x-auto no-scrollbar`).
- **Middle Row:** Company info on the left (`lg:col-span-8`), Social media icons on the right (`lg:col-span-4 justify-end`).

### Rule 7: Organic Wave Fill-Only Vectors on Every Dark Teal Section
- Every dark teal / primary gradient `<section>` across Homepage, Layanan Hub/Sektor/Kategori, Product Catalog/Category/Detail, Comparison, About, Contact, Blog, and Search **MUST** carry a subtle organic gradient wave background vector using solid/gradient `<path fill="url(#...)" />` elements isolated with `contain: layout paint` (`pointer-events-none`).
- **NEVER** use `stroke="..." fill="none"` or wireframe outlines on background vectors.

### Rule 8: Definition of "Teks Kecil" vs "Deskripsi"
- When the user asks to remove "teks kecil" (small text), it refers specifically to the **mini badge/pill label** above a heading.
- The **Descriptive explanation text** below a heading/stat MUST remain intact and editable in WP Admin.

### Rule 9: Consistent Typography Scale
- Section headings: `text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] leading-[1.1]`.
- Key statistics (e.g., `ISO & SNI`, `2019`, `60,74%`, `17 L`) must be large, bold, and authoritative.

### Rule 10: Non-Destructive Data Rule (Anti-Overwrite)
- Auto-seeder scripts ([`app/auto-setup.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/auto-setup.php), [`app/cpt-drones.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/cpt-drones.php), [`app/layanan-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/layanan-settings.php)) must ONLY seed data on initial clean install (guarded by `fds_theme_auto_setup_done_v1`, `fds_drones_seeded_final_v1`, `fds_layanan_pages_provisioned_v1`).
- When updating themes or running migrations ([`app/migrations-2026.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/migrations-2026.php)), **NEVER overwrite** existing user-edited database content with empty defaults, and NEVER delete legacy pages, posts, tags, menus, or users without explicit written user approval.

### Rule 11: Two-State Gated Brochure Download Flow
- State 1: Business email input field + "Kirim Email" button (download locked).
- State 2: Smooth AJAX transition into an active "Download Brosur PDF" button in `#0f808a`.
- Leads stored in WordPress options and exportable to CSV in WP Admin with anti-CSV formula injection protection.

### Rule 12: Single Drone Specs Offset Floating Image & Spacing Rule
- **Intentional Offset:** On [`single-drone.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/single-drone.blade.php), the large transparent product image in the specs section is intentionally designed to offset/float downwards (`lg:h-0 lg:relative` + `lg:absolute lg:top-0 lg:left-0 pointer-events-none z-30`).
- **Strictly Preserve Tight Section Spacing:** Section padding MUST remain compact (`pt-16 sm:pt-20 pb-10 sm:pb-12`). NEVER expand bottom padding or push `#unduh-brosur` down into a large empty gap.
- **Zero-Clipping Stacking Context:** The specs `<section>` uses `relative z-20 overflow-visible`, the left column & image wrapper use `overflow-visible z-30`, and `#unduh-brosur` uses `relative z-0`.

### Rule 13: Strict Business, Brand & Certification Guardrails (Zero Fabrication)
- **Entity Identity:** Public commercial brand = **Full Drone Solutions** (site title = `Full Drone Solutions`); legal entity name = **PT Karya Solusi Angkasa**; legal incorporation year = **2019**. Keep **"No.1"** claim where present.
- **Strictly Banned Claims:** **NEVER** output **`Best UAV`**, **`sejak 2012`** (or `2012` as founding stat), **`IP68` / `IP67` / `IP54`**, **`FLIR`** (use `kamera termal inframerah` / `sensor termal`), **`FDS STATION`** in all-caps (use `FDS Station`), unverified SLAs (**`48 jam`**, **`1×24 jam`**, **`24/7`**, **`±2.35%`**), `priceRange: "$$$$"`, or fabricated reviews / `aggregateRating`.
- **Certification Scope:**
  - **TKDN + BMP 60,74%** applies **ONLY** to the **FERTO** series (`5, 10, 15, 22, 30, 50`). Never claim TKDN on `DELTAV`, `DELFRO`, or `MULTIPURPOSE` unless officially confirmed.
  - **SNI 9199:2023** applies **ONLY** to **FERTO 15** and **FERTO 22**.
  - **FERTO 15** displays tank capacity **`17 Liter`** (`15 – 17 Liter`).
  - **9 Active Models / 3 Categories:** `Agrikultur` (`FERTO 5, 10, 15, 22, 30, 50`), `Pemetaan & GIS` (`DELTAV`, `MULTIPURPOSE`), and `Kargo` (`DELFRO`). `rebo` and `agrea` remain excluded/redirected.
- **Missing Data Policy:** Never display literal `[ISI: ...]` or `[KONFIRMASI: ...]` strings on public HTML. Record missing internal operational specs in `placeholders` arrays for WP Admin notes only.

### Rule 14: Server-Side Rendered (SSR) SEO & UI Parity Rules
- **SSR Accordions & Technical Guides:** All FAQ accordions (`<details>`/`<summary>`) and technical model guides (`fds_generate_drone_seo_content()`, 250–400w clean non-duplicated guide, NO bottom duplicate spec table) **MUST** exist in initial server-rendered HTML — NEVER injected at runtime via JS.
- **Unified Accordion Indicator (`⌄`):** Both drone FAQs (`.fds-drone-guide details.fds-faq-item summary::after`) and Layanan FAQs ([`service-faq.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/components/service-faq.blade.php)) MUST use the same downward chevron icon (`M19 9l-7 7-7-7` rotating 180deg on `[open]`) — NEVER `+` / `-` icons.
- **Unified Diagonal CTA Arrow (`↗`):** Interactive cards, Layanan links, card CTAs, and section CTA links use the consistent diagonal arrow icon (`<path d="M6 6h12v12"/><path d="M6 18 18 6"/>`) paired with subtle `translate(2.5px, -2.5px)` hover motion.
- **Clean Breadcrumbs Without Leading `/`:** [`breadcrumbs.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/components/breadcrumbs.blade.php) renders `Beranda / ...` cleanly with NO stray `/` prefix before `Beranda`.

### Rule 15: Homepage List vs Catalog Grid Layout & 1:1 Canonical Card Sync Rule
- **Homepage `#produk` (`front-page.blade.php`):** MUST keep the clean horizontal `.drone-row` editorial list format for consistency and scannability.
- **Catalog Hub (`/produk/`) & Taxonomy Archive (`/kategori-drone/*`):** MUST use uniform-height `<x-drone-card>` grids ([`drone-card.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/components/drone-card.blade.php)) with full-bleed `object-cover` media (4/3 aspect ratio, zero gray padding box), `.fds-stretched-link` primary click area, bottom-aligned comparison links (`Bandingkan →` / diagonal action), and uniform card footers.
- **1:1 Hero Image & Canonical Data Parity:** Catalog cards (`<x-drone-card>`), comparison picker/stage ([`page-bandingkan.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/page-bandingkan.blade.php)), and Detail Hero/Specs ([`single-drone.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/single-drone.blade.php)) MUST resolve media & specs from the same Single Source of Truth (`\App\fds_get_drone_hero_media()` and `\App\fds_get_drone_detail_data()` in [`app/cpt-drones.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/cpt-drones.php)), with automatic catalog cache purge on `drone` save. All **non-card** copy, highlights, comparison matrix, FAQs, sidebar, and SEO/Schema meta on `/produk/` and `/kategori-drone/*` MUST remain editable non-destructively via WP Admin **Produk Drone → Konten Katalog** ([`app/produk-content-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/produk-content-settings.php)).

### Rule 16: Strict Output & HTML Entity Sanitization (Zero Double-Escaping)
- **NEVER Double-Escape in Blade:** **BANNED:** `{{ esc_html(...) }}`, `{{ esc_attr(...) }}`, `{{ esc_url(...) }}`, `{{ wp_logout_url(...) }}`, or `{!! esc_html(...) !!}`.
- **Always Decode Before Blade Output:** Pass WP options, post titles, category names, and meta values through `fds_clean_string()` / `fds_deep_decode()` ([`app/helpers.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/helpers.php)) and scrub legacy banned strings via `fds_scrub_legacy_text()`, then render with standard `{{ $var }}` so Blade escapes once cleanly (`&amp;` never appears literally on screen).
- **URLs & Inline JS:** Use `{!! esc_url(...) !!}` for dynamic URLs (avoiding `&#038;` in query strings like Google Maps embeds, WhatsApp links, Unsplash params, and `/kontak/?layanan=...`), and use `{!! wp_json_encode(...) !!}` for PHP values passed into `<script>` (never `esc_js()`).
- **WordPress Search & Thumbnails:** Pass `get_search_query(false)` inside Blade `{{ ... }}` (since `get_search_query()` defaults to escaping `&` into `&amp;`), and pass clean plain text (NEVER `esc_attr(...)`) into `get_the_post_thumbnail(..., ['alt' => ...])`.

### Rule 17: Unified Inquiry Funnel & Security Hardening
- **Unified Lead Route:** Service CTAs, category CTAs, and sticky bars route commercial inquiries to `/kontak/#hubungi-form?layanan=...` ([`page-kontak.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/page-kontak.blade.php)), handled by [`app/inquiry-handler.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/inquiry-handler.php) with WP Nonce CSRF validation, honeypot field, optional Cloudflare Turnstile verification, GA4/GTM event telemetry (`generate_lead`, `click_whatsapp`, `click_quotation_cta`), and redirect to `/terima-kasih/` (`noindex`).
- **Zero Public Dev Exposure:** `.htaccess`, [`app/security.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/security.php), and [`app/migrations-2026.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/migrations-2026.php) ensure `.agents/`, `scratch/`, `.env`, `.sql`, `.log`, `.md`, and `.blade.php` sources are never accessible via HTTP on the active theme mirror, and `compro.fulldronesolutions.co.id` enforces `noindex, nofollow`.

---

## 📂 3. ARCHITECTURAL MAP & KEY FILES

```
fds-theme/
├── .htaccess                       # Block HTTP access to .agents, scratch, .md, .env, .sql, .log, views
├── functions.php                   # Roots Sage + Acorn bootstrap loading all 21 app/ modules
├── CHANGELOG.md                    # Chronological record of verified changes & releases
├── AGENTS.md                       # AI behavioral & architectural rules (v2.5)
├── GEMINI.md                       # Antigravity / Gemini quick reference directives
├── COMPRO_GUIDE.md                 # Master Compro Content Management Runbook (v2.5)
├── PROJECT_MEMORY.md               # Master Project Memory & Architecture Context
├── PRD.md                          # Product Requirements Document (PRD v2.5 Enterprise)
├── INIT.md                         # Initialization & Environment Setup Guide (v2.5)
├── SEO_KEYWORDS.md                 # Master B2B SEO Keyword & GEO Entity Matrix
├── app/
│   ├── setup.php                   # Theme setup, asset enqueuing, nav menus
│   ├── filters.php                 # Sage content filters & custom template hierarchy filters
│   ├── helpers.php                 # fds_clean_string, fds_deep_decode, fds_scrub_legacy_text, pagination, image sizing
│   ├── security.php                # OWASP headers, staging noindex guard, LiteSpeed AJAX no-cache
│   ├── admin-perf.php              # WP-Admin Heartbeat throttle, dashboard RSS cleanup, revision cap
│   ├── theme-settings.php          # WP Admin "Logo & Navbar" (logo, brand, favicon, mega menu, footer certs, tab titles)
│   ├── homepage-content-settings.php # WP Admin "Konten Beranda" (hero, mitra, solusi, bento, produk, layanan, newsroom, kontak)
│   ├── hero-slider.php             # Hero carousel slide defaults & renderer helpers
│   ├── solusi-settings.php         # 6 Sektor Industri homepage cards synced with Layanan sectors
│   ├── layanan-settings.php        # Layanan SSR data registry, cache flush, and /layanan/ page provisioner
│   ├── layanan-data-sectors.php    # Structured SSR copy, tables, deliverables, & FAQs for 6 Sektor Industri
│   ├── layanan-data-categories.php # Structured SSR copy, tables, deliverables, & FAQs for 3 Kategori Layanan
│   ├── layanan-admin.php           # WP Admin "Konten Layanan" non-destructive override editor & Reset button
│   ├── about-content-settings.php  # WP Admin "Konten Tentang Kami" (2019 PT history, spectrum, activities, certs, maps)
│   ├── cpt-drones.php              # CPT `drone` (9 active models, 3 categories), Hero/Card Single Source of Truth, SSR guide
│   ├── produk-content-settings.php # WP Admin "Konten Katalog" non-destructive editor for /produk/ & /kategori-drone/*
│   ├── cpt-mitra.php               # CPT `mitra` 1-click logo manager for homepage marquee
│   ├── brochure-settings.php       # Gated brochure download AJAX handler, lead table & CSV export
│   ├── footer-settings.php         # WP Admin "Kontak & Sosmed" (PT info, phone/WA, email, sosmed, maps, kontak copy)
│   ├── inquiry-handler.php         # CPT `fds_inquiry` ("Pesan Masuk"), AJAX lead submit, Turnstile, GA4/GTM config, CSV export
│   ├── preview-handler.php         # WordPress autosave custom-meta & thumbnail live preview interceptor
│   ├── seo.php                     # Rank Math @graph sync, ItemPage/Service/Organization JSON-LD, robots, sitemap, /llms.txt
│   ├── auto-setup.php              # Non-destructive first-install page & default options provisioner
│   └── migrations-2026.php         # Idempotent 2026-11 security, entity scrubbing & SEO sync migration
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php               # Master layout, Apple blur navbar, Mega Menu, Global Footer
│   │   ├── front-page.blade.php                # Homepage: Hero, Marquee, 6 Sektor, Bento, .drone-row List, Enterprise, Newsroom
│   │   ├── template-produk.blade.php           # /produk/ Catalog Hub using uniform <x-drone-card> grids
│   │   ├── taxonomy-kategori_drone.blade.php   # /kategori-drone/* archive using uniform <x-drone-card> grids
│   │   ├── single-drone.blade.php              # Drone detail: Specs floating image, Use Cases, Gated Brochure, SSR Technical Guide & FAQ
│   │   ├── page-bandingkan.blade.php           # Side-by-side drone comparison tool
│   │   ├── template-layanan-hub.blade.php      # /layanan/ Services Hub
│   │   ├── template-layanan-sektor.blade.php   # /layanan/{sektor}/ (6 Sektor Industri templates)
│   │   ├── template-layanan-kategori.blade.php # /layanan/{kategori}/ (3 Kategori Layanan templates)
│   │   ├── page-tentang-kami.blade.php         # /tentang-kami/ Company profile, 2019 PT narrative, Tech Spectrum, Maps
│   │   ├── page-kontak.blade.php               # /kontak/ Unified inquiry hub, prefill ?layanan=/?produk=, Google Maps
│   │   ├── page-terima-kasih.blade.php         # /terima-kasih/ Conversion confirmation page (noindex)
│   │   ├── index.blade.php                     # /blog/ & newsroom archive
│   │   ├── single.blade.php                    # Single blog post + social sharing
│   │   ├── search.blade.php                    # Search results template
│   │   ├── page.blade.php / 404.blade.php      # Standard page & 404 fallback with organic wave banner
│   │   └── components/                         # drone-card, service-card, service-faq, service-sticky-bar, breadcrumbs, cta-band, etc.
│   ├── css/app.css                 # Tailwind directives, accordion chevrons, stretched-link hover, wave performance isolation
│   └── js/app.js                   # Alpine.js / native scroll reveals, GA4 lead & click_quotation_cta / click_whatsapp tracking
└── public/
    ├── build/                      # Compiled Vite production assets (manifest, css, js)
    └── images/                     # Local optimized imagery (cutouts, drones, sectors, site)
```

---

## 🛠️ 4. STANDARD OPERATING PROCEDURES (SOP)

### How to Build & Deploy Updates Locally:
Whenever you edit Blade templates, PHP files, CSS/JS, or theme configuration:

```powershell
# 1. Ensure you are in the source theme root
cd "c:\Users\BILALIHSAN\Local Sites\fds\app\public\fds-theme"

# 2. Build frontend assets with Vite
npm run build

# 3. Clean stale compiled assets in active WordPress mirror before syncing
if (Test-Path "..\wp-content\themes\fds-theme\public\build") {
    Remove-Item "..\wp-content\themes\fds-theme\public\build\*" -Recurse -Force -ErrorAction SilentlyContinue
}

# 4. Synchronize PHP, Views, Config, Docs, and Build artifacts to active WordPress theme directory
Copy-Item -Path "functions.php",".htaccess","AGENTS.md","GEMINI.md","PRD.md","PROJECT_MEMORY.md","COMPRO_GUIDE.md","INIT.md","CHANGELOG.md" -Destination "..\wp-content\themes\fds-theme\" -Force
Copy-Item -Path "app\*" -Destination "..\wp-content\themes\fds-theme\app\" -Recurse -Force
Copy-Item -Path "resources\*" -Destination "..\wp-content\themes\fds-theme\resources\" -Recurse -Force
Copy-Item -Path "public\build\*" -Destination "..\wp-content\themes\fds-theme\public\build\" -Recurse -Force

# 5. Clear Acorn Blade view cache (CRITICAL so view changes take effect immediately!)
if (Test-Path "..\wp-content\cache\acorn\framework\views") {
    Remove-Item "..\wp-content\cache\acorn\framework\views\*" -Recurse -Force -ErrorAction SilentlyContinue
}
```
> [!IMPORTANT]
> Never copy `.agents/` or `scratch/` into `..\wp-content\themes\fds-theme\`. Both directories belong only in development workspace/repo and are blocked/cleaned from public theme directories by `.htaccess` and [`app/migrations-2026.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/migrations-2026.php).

---

## 🔍 5. SEO & RANK MATH INTERACTION PRINCIPLES

1. **Dual-Engine Architecture:**
   * When Rank Math is active, theme functions coordinate with Rank Math via `rank_math/json_ld` (`app/seo.php`) instead of emitting conflicting duplicate metadata.
   * Never inject duplicate `<title>` or `<meta name="description">` tags when Rank Math is handling them.
2. **Valid B2B Schema Without Merchant Snippet Errors:**
   * Single drone pages output `ItemPage` + `about: Thing` + `FAQPage` + `BreadcrumbList` — NEVER empty `Product`/`Offer` snippets or fake `aggregateRating`, and NEVER `priceRange: "$$$$"` on `Organization` / `LocalBusiness`.
3. **Rank Math Score Truth & SSR Guarantees:**
   * WP Admin's Rank Math score only checks Gutenberg editor text in admin; search bots evaluate full rendered HTML, 250–400w model field guides, `<details>` FAQ accordions, and JSON-LD `@graph` output at runtime.
4. **Clean GEO (`/llms.txt`):**
   * [`app/seo.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/seo.php) decodes HTML entities cleanly so `/llms.txt` and `/llms-full.txt` never expose raw `&amp;` entities.

---

## 🤝 6. COMMUNICATION STYLE
- Keep explanations concise, professional, and directly actionable.
- Link all mentioned files using Markdown file links (`[filename](file:///...)`).
- Before proposing any major architectural changes, consult [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md).
