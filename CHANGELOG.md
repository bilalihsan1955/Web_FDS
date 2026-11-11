# 📜 CHANGELOG.md — RIWAYAT PERUBAHAN & RILIS TEMA FDS
**Repository:** Full Drone Solutions (FDS) — PT Karya Solusi Angkasa  
**Format:** Reverse-chronological verified codebase changelog  

---

## [v2.5] — 2026-11-11

### Single Source of Truth Katalog Produk, Paritas Gambar Hero 1:1 & Panel Konten Katalog
- **Sinkronisasi 1:1 Detail Drone & Kartu Katalog (`app/cpt-drones.php`, `single-drone.blade.php`, `drone-card.blade.php`, `page-bandingkan.blade.php`):**
  - Menambahkan helper kanonik `\App\fds_get_drone_hero_media()` dan `\App\fds_get_drone_detail_data()` di [`app/cpt-drones.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/cpt-drones.php) sehingga [`single-drone.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/single-drone.blade.php) (Hero + Spek) dan kartu [`components/drone-card.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/components/drone-card.blade.php) di `/produk/` dan `/kategori-drone/*` selalu menampilkan gambar Hero, spesifikasi, badge TKDN/SNI, dan tagline yang identik secara otomatis.
  - Memperbarui [`page-bandingkan.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/page-bandingkan.blade.php) agar memakai data kanonik tersebut sekaligus menghapus cek transient usang dan menjaga cutout PNG transparan di panggung komparasi.
  - Menambahkan `\App\fds_purge_drone_catalog_caches()` untuk menghapus cache Mega Menu, komparasi, `/llms.txt`, dan LiteSpeed secara otomatis setiap unit `drone` atau taksonomi `kategori_drone` diperbarui.
- **Panel Editable Non-Destruktif `Konten Katalog` (`app/produk-content-settings.php`):**
  - Menambahkan [`app/produk-content-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/produk-content-settings.php) (terdaftar di [`functions.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/functions.php) dan shortcut Admin Bar [`app/theme-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/theme-settings.php)) dengan menu WP Admin **Produk Drone &rarr; Konten Katalog**, metabox pintasan khusus pada halaman `/produk/` (mencegah bentrok metabox di [`app/layanan-admin.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/layanan-admin.php)), dan tombol Reset terisolasi per tab.
  - Menyambungkan seluruh konten non-card pada [`template-produk.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/template-produk.blade.php) dan [`taxonomy-kategori_drone.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/taxonomy-kategori_drone.blade.php) ke opsi editable tersebut, tersinkronisasi 1:1 dengan SEO Meta & Schema `CollectionPage`/`FAQPage` di [`app/seo.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/seo.php).

### UI Parity, Wave Vectors & Entity Sanitization Sweep
- **Blade & PHP Output Sanitization (Zero Double-Escaping):**
  - Menambahkan helper terpusat [`app/helpers.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/helpers.php) (`fds_clean_string`, `fds_deep_decode`, `fds_scrub_legacy_text`) serta pembersihan `&amp;` / `&quot;` / `&#038;` di seluruh template Blade ([`layouts/app.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/layouts/app.blade.php), [`front-page.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/front-page.blade.php), `template-layanan-*.blade.php`, [`page-kontak.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/page-kontak.blade.php), [`index.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/index.blade.php), [`search.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/search.blade.php), [`single.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/single.blade.php), [`page.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/page.blade.php), [`partials/comments.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/partials/comments.blade.php), [`forms/search.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/forms/search.blade.php)).
  - Memperbaiki encoding `&amp;` dan prioritas hook `init` pada generator `/llms.txt` & `/llms-full.txt` di [`app/seo.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/seo.php) (`v4.2`) agar permalink `/drone/{slug}/` terselesaikan akurat bersama decoding entitas & pengurutan model logis [`fds_sort_drone_posts()`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/helpers.php).
  - Mengganti `{{ esc_url(...) }}` dan `esc_js(...)` menjadi `{!! esc_url(...) !!}` dan `{!! wp_json_encode(...) !!}`; memakai `get_search_query(false)` dan menghapus `esc_attr()` berlebih pada `'alt'` `get_the_post_thumbnail()`.
- **UI Consistency & Brand Guardrails:**
  - Memastikan seluruh **21** `<section>` teal gelap memiliki latar gelombang SVG organik *fill-only* (tanpa garis `stroke`).
  - Menyatukan ikon akordeon `<details>` pada [`single-drone.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/single-drone.blade.php) dan [`components/service-faq.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/components/service-faq.blade.php) ke chevron ke bawah seragam ([`resources/css/app.css`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/css/app.css)).
  - Menghapus tanda `/` berlebih di awal [`components/breadcrumbs.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/components/breadcrumbs.blade.php) serta menyeragamkan ikon panah diagonal (`↗`) di kartu dan tautan interaktif.
  - Mempertahankan format daftar horizontal `.drone-row` di Beranda `#produk` sekaligus menjaga grid kartu `<x-drone-card>` *full-bleed* `object-cover` seragam di `/produk/` dan `/kategori-drone/*`.
- **Documentation Sync:** Pembaruan menyeluruh [`AGENTS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/AGENTS.md), [`GEMINI.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/GEMINI.md), [`anti-hallucination-and-strict-guidelines.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/.agents/rules/anti-hallucination-and-strict-guidelines.md), [`PRD.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PRD.md), [`PROJECT_MEMORY.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PROJECT_MEMORY.md), [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md), [`INIT.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/INIT.md), dan [`SEO_KEYWORDS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/SEO_KEYWORDS.md) agar sejalan dengan palet `#0f808a`, tahun PT `2019`, aturan TKDN/SNI, 9 model aktif, funnel Layanan, dan SOP mirror.

---

## [v2.4.3] — 2026-11-10 (`b3f7f9f`, `1e1aaad`, `fa06af4`)

### B2B Layanan Funnel, Lead Engine, Performance & Schema Hardening
- **Layanan Funnel & Admin CRM (`app/layanan-*.php`, `app/inquiry-handler.php`):**
  - Membangun Hub `/layanan/`, 6 halaman sektor industri (`pertambangan`, `perkebunan-pertanian`, `kehutanan-lingkungan`, `energi-infrastruktur`, `perencanaan`, `bencana-lingkungan`), 3 halaman kategori spesialis (`jasa-foto-udara-lidar`, `jasa-multispectral-hyperspectral`, `pelatihan-sertifikasi`), serta panel WP-Admin non-destruktif **Konten Layanan**.
  - Mengarahkan seluruh CTA konversi ke `/kontak/#hubungi-form?layanan=...` + sticky WhatsApp bar mobile, menyimpan lead ke CPT `fds_inquiry` (**Pesan Masuk**) dengan honeypot, Cloudflare Turnstile, ekspor CSV aman, telemetri GA4/GTM (`generate_lead`, `click_quotation_cta`, `click_whatsapp`), dan pengalihan `/terima-kasih/` (`noindex`).
- **SSR SEO, Schema JSON-LD & Blade Fixes (`app/seo.php`, `app/cpt-drones.php`):**
  - Mengganti schema single drone dari snippet `Product`/`Offer` kosong menjadi `ItemPage` + `about: Thing` + `FAQPage` + `BreadcrumbList`; menyelaraskan `@graph` Rank Math; mengganti `@php(...)` satu baris ke blok `@php ... @endphp` demi kompatibilitas server produksi.
  - Menyederhanakan panduan teknis drone menjadi 250–400 kata non-duplikatif + FAQ `<details>` SSR tanpa tabel ganda bawah.
- **Scroll & Asset Performance (`resources/js/app.js`, `resources/css/app.css`):**
  - Menghapus wheel-hijacking `Lenis`, memakai native compositor scrolling, mengoptimalkan observer reveal dan sizing gambar thumbnail.

---

## [v2.4.2] — 2026-11-06 – 2026-11-09 (`57e893c`, `62d34d0`, `5babc9e`, `f98d54f`, `0167509`)

### Crawl Hygiene, Security Guardrails & Claim Compliance
- Mematikan arsip `/drone/`, menambahkan 301 redirect aman dari `/rebo` & `/ferto-20l` ke target aktif, mengeluarkan halaman utilitas & kategori kosong dari sitemap, serta membersihkan klaim `priceRange: "$$$$"`, `Best UAV`, `sejak 2012`, `IP68/67/54`, dan ruang lingkup TKDN/SNI per model.
- Menambahkan perlindungan keamanan [`app/security.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/security.php), [`app/admin-perf.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/admin-perf.php), aturan blokir [`.htaccess`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/.htaccess), serta modul sanitasi non-destruktif [`app/migrations-2026.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/migrations-2026.php).

---

## [v2.4.1] — 2026-11-04 – 2026-11-05 (`66c9068`, `e2670d0`, `504e022`, `46f0d6e`, `cf787f4`)

### Core SEO & GEO Architecture
- Mengimplementasikan [`SEO_KEYWORDS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/SEO_KEYWORDS.md), generator fisik & endpoint `/llms.txt`, helper gambar responsif `fds_optimized_img_url()`, serta kanonik trailing slash konsisten.
