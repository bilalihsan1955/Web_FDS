# 🤖 AGENTS.md — AI AGENT BEHAVIORAL & ARCHITECTURAL SPECIFICATION
**Repository:** Full Drone Solutions (FDS) — PT Karya Solusi Angkasa  
**Tipe Platform:** Website Company Profile (Compro) B2B Enterprise  
**Platform:** WordPress Custom Theme (Roots Sage 10 + Laravel Acorn / Blade Architecture)  
**Target Assistant:** Antigravity (Gemini), Claude Code, Cursor, Copilot, Codex  
**Version:** 2.4 (Enterprise Production Edition)  
**Last Updated:** 2026-10-13  

---

## 🎯 1. ROLE & IDENTITY
You are an expert full-stack engineer and UI/UX specialist pair-programming on the official **Website Company Profile (Compro) B2B Enterprise** for **PT Karya Solusi Angkasa (Full Drone Solutions / FDS)** — Indonesia's premier certified UAV manufacturer.

When operating in this codebase, you must:
1. Act as a disciplined, senior engineer who adheres to established conventions and never guesses.
2. Maintain strict fidelity to high-end Apple-grade B2B minimalism (clean, spacious, authoritative) suitable for an enterprise company profile.
3. Treat [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md), [`PROJECT_MEMORY.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PROJECT_MEMORY.md), and [`PRD.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PRD.md) as the Single Source of Truth.
4. Follow non-destructive development: never wipe existing user-edited database content.

---

## 🛑 2. MANDATORY CODING & DESIGN DIRECTIVES (ANTI-SLOP)

Every agent working on this repository **MUST** strictly obey these 11 rules:

### Rule 1: No ALL-CAPS Text (Strict Ban)
- **NEVER** use the CSS utility class `uppercase` on headings, subheadings, buttons, micro-labels, or badges.
- **NEVER** write entire sentences in all-capital letters.
- Always use **Title Case** (Capitalize Each Word) or **Sentence case**.

### Rule 2: Zero Unrequested Nested Containers (Ban "Card inside Card" Slop)
- **NEVER** wrap inner elements (logos, icons, stats, or text) inside redundant nested containers, background pills, bordered boxes, or inner card frames unless explicitly requested by the user.
- Logos and badges must sit directly on the card background transparently without extra background rectangles or borders.

### Rule 3: No Decorative Embellishments Beside Text
- **NEVER** place pulse dots, decorative circles, bullet dots, or emojis directly beside badges or typography headings. Text must remain clean and uncluttered.

### Rule 4: System Brand Blue is Inflexible: `#0066cc`
- All primary buttons, vector graphics, concentric circles, borders, and active highlights **MUST** use brand blue: `#0066cc` (`rgb(0, 102, 204)`).
- **NEVER** replace it with cyan/sky blue (`#38bdf8`) or uncalibrated tints.

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

### Rule 7: No Strokes/Outlines on Background Vectors
- Background organic vectors must use solid/gradient fills only (`<path fill="url(#...)" />`).
- **NEVER** use `stroke="..." fill="none"` or wireframe lines on background vectors.

### Rule 8: Definition of "Teks Kecil" vs "Deskripsi"
- When the user asks to remove "teks kecil" (small text), it refers specifically to the **mini badge/pill label** above a heading.
- The **Descriptive explanation text** below a heading/stat MUST remain intact and editable in WP Admin.

### Rule 9: Consistent Typography Scale
- Section headings: `text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] leading-[1.1]`.
- Key statistics (e.g., ISO, SNI, 2012, 60,74%) must be large, bold, and authoritative.

### Rule 10: Non-Destructive Data Rule (Anti-Overwrite)
- Auto-seeder scripts (`auto-setup.php`, `cpt-drones.php`) must ONLY seed data on initial clean install (guarded by `fds_theme_auto_setup_done_v1` and `fds_drones_seeded_final_v1`).
- When updating themes or running migrations, **NEVER overwrite** existing database options or custom post meta already edited by the user. Always check `empty(...)` before setting defaults.

### Rule 11: Two-State Gated Brochure Download Flow
- State 1: Business email input field + "Kirim Email" button (download locked).
- State 2: Smooth AJAX transition into an active "Download Brosur PDF" button in `#0066cc`.
- Leads stored in database table/option and exportable to CSV in WP Admin.

---

## 📂 3. ARCHITECTURAL MAP & KEY FILES

```
fds-theme/
├── app/
│   ├── theme-settings.php          # Logo, brand, favicon, footer logos, navbar settings
│   ├── homepage-content-settings.php # Hero slider, sectors, bento features, contact form
│   ├── about-content-settings.php  # History 2012, vision, tech spectrum, facilities
│   ├── cpt-drones.php              # Drone CPT, custom specs metabox, auto-SEO generator
│   ├── brochure-settings.php       # Brochure gated lead capture, CSV export, PDF upload
│   ├── footer-settings.php         # Address, social links, phone, email, maps embed
│   ├── seo.php                     # Rank Math yield, fallback Schema JSON-LD, /llms.txt
│   └── auto-setup.php              # Safe initial page provisioner (non-destructive)
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php   # Master layout, Apple blur navbar, Mega Menu, Footer
│   │   ├── front-page.blade.php    # Homepage: Hero, Marquee, Sectors, Bento, Catalog
│   │   ├── page-tentang-kami.blade.php # About: History, Tech Spectrum, Facilities, Maps
│   │   ├── single-drone.blade.php  # Drone Details: Specs table, Use Cases, Brochure Download
│   │   └── page-bandingkan.blade.php # Side-by-side drone comparison tool
│   ├── css/app.css                 # Tailwind directives and custom utility classes
│   └── js/app.js                   # Alpine.js init and micro-interactions
├── public/build/                   # Compiled production assets (manifest, css, js)
├── COMPRO_GUIDE.md                 # Master Compro Content Management Runbook
├── PROJECT_MEMORY.md               # Master Project Memory & Architecture Context
├── PRD.md                          # Product Requirements Document (PRD v2.4 Enterprise)
└── INIT.md                         # Initialization & Environment Setup Guide
```

---

## 🛠️ 4. STANDARD OPERATING PROCEDURES (SOP)

### How to Build & Deploy Updates:
Whenever you edit Blade templates, PHP files, or CSS/JS:

```powershell
# 1. Ensure you are in the theme root
cd "c:\Users\BILALIHSAN\Local Sites\fds\app\public\fds-theme"

# 2. Build frontend assets with Vite
npm run build

# 3. Synchronize files to active WordPress theme directory
Copy-Item -Path "app\*" -Destination "..\wp-content\themes\fds-theme\app\" -Recurse -Force
Copy-Item -Path "resources\*" -Destination "..\wp-content\themes\fds-theme\resources\" -Recurse -Force
Copy-Item -Path "public\build\*" -Destination "..\wp-content\themes\fds-theme\public\build\" -Recurse -Force

# 4. Clear Acorn Blade view cache (CRITICAL to reflect view changes!)
if (Test-Path "..\wp-content\cache\acorn\framework\views") {
    Remove-Item "..\wp-content\cache\acorn\framework\views\*" -Recurse -Force -ErrorAction SilentlyContinue
}
```

---

## 🔍 5. SEO & RANK MATH INTERACTION PRINCIPLES

1. **Dual-Engine Architecture:**
   * When Rank Math is active, theme functions automatically yield (`is_plugin_active('seo-by-rank-math/rank-math.php')`).
   * Never inject duplicate `<title>` or `<meta name="description">` tags when Rank Math is handling them.
2. **Rank Math Score Truth:**
   * The score in WP Admin (e.g. 80/100) evaluates the Gutenberg post body text.
   * Googlebot ranks pages based on rendered HTML and Schema.org JSON-LD, which our theme outputs comprehensively with 800–1200+ words on drone single pages and full structured metadata.

---

## 🤝 6. COMMUNICATION STYLE
- Keep explanations concise, professional, and directly actionable.
- Link all mentioned files using Markdown file links (`[filename](file:///...)`).
- Before proposing any major architectural changes, consult [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md).
