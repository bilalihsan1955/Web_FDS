# 🧠 PROJECT MEMORY & ARCHITECTURE MASTER REFERENCE
**Website:** Full Drone Solutions (FDS) — PT Karya Solusi Angkasa (Website Company Profile & Lead Engine B2B Enterprise)  
**Platform:** WordPress Custom Theme (Roots Sage 10 / Laravel Acorn Blade + Tailwind CSS + Vite)  
**Dokumen Operasional Utama:** [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md) & [`PRD.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PRD.md)  
**Terakhir Diperbarui:** 2026-11-12  

---

## 📌 1. Profil & Identitas Bisnis (Brand Context)
* **Perusahaan (`legalName`):** PT Karya Solusi Angkasa (berbadan hukum PT sejak **2019**)
* **Brand Publik & Site Title:** Full Drone Solutions (FDS)
* **Status:** Produsen & Manufaktur Pesawat Udara Nirawak (UAV) Resmi Nasional Indonesia + Konsultan Layanan Pemetaan, LiDAR, Multispektral & GIS
* **Lokasi Kantor & Fasilitas Produksi:** Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285
* **Kontak Resmi:** `+62 8112 748 882` / `https://wa.me/628112748882` | Email: `marketing@fulldronesolutions.com`
* **Legalitas & Sertifikasi Resmi (Dengan Ruang Lingkup Ketat):**
  * **TKDN + BMP 60,74% (Kemenperin RI):** Berlaku **KHUSUS** untuk seluruh model lini drone pertanian seri **FERTO** (`5, 10, 15, 22, 30, 50`). Model non-FERTO (`DELTAV`, `DELFRO`, `MULTIPURPOSE`) dilarang mencantumkan TKDN sebelum ada konfirmasi resmi.
  * **SNI 9199:2023:** Berlaku **KHUSUS** untuk **FERTO 15** (tampil dengan kapasitas **`17 Liter`**) dan **FERTO 22**.
  * **ISO 9001:2015:** Sertifikasi **Sistem Manajemen Mutu Perusahaan / Manufaktur PT Karya Solusi Angkasa** (bukan sertifikasi unit/model drone individual — dilarang melekat pada baris spesifikasi per model maupun matriks sertifikasi per unit).
  * **Regulasi Penerbangan:** Pendampingan sertifikasi pilot beracuan DKPPU **CASR Part 107** / SIDOPI-GO.
* **Nilai Keunggulan Utama (Value Proposition):**
  1. Kesiapan pengadaan instansi pemerintah, BUMN, dan e-Katalog LKPP untuk seri FERTO ber-TKDN 60,74% dan SNI 9199:2023.
  2. Suku cadang *ready-stock* lokal di workshop Sleman beserta layanan servis terjadwal langsung dari pabrikan.
  3. Software Ground Control Station buatan sendiri berbahasa Indonesia (**FDS Station** — selalu ditulis Title Case).
  4. Dukungan operasional end-to-end melalui unit layanan survei LiDAR, fotogrametri, multispektral/hyperspectral, dan pelatihan pilot.

---

## 📁 2. Peta File & Struktur Arsitektur Tema

### A. Core Engine, Funnel, Security & Backend Logic (`app/` — 24 Modul via `functions.php`)
| File | Fungsi & Tanggung Jawab Utama |
| :--- | :--- |
| `app/setup.php` & `app/filters.php` | Inisialisasi tema Sage, enqueuing aset Vite, navigasi menu, dan filter template kustom. |
| `app/helpers.php` | Helper sanitasi entity & teks (`fds_clean_string`, `fds_deep_decode`, `fds_scrub_legacy_text`), pengurutan logis model (`fds_sort_drone_posts`), responsif gambar WP (`fds_optimized_img_url`), dan paginasi blog (`fds_posts_pagination`). |
| `app/security.php` | HTTP Security Headers (OWASP), no-cache AJAX dinamis (`fds_submit_inquiry`, `fds_download_brochure`), anti-enumerasi/penghapusan `?ver=` WP, serta guard `noindex, nofollow` otomatis pada host staging/lokal (`compro.*`, `staging.*`, `.local`). |
| `app/admin-perf.php` | Optimasi backend WP-Admin: throttle Heartbeat API ke 60 detik, hapus RSS widget eksternal, dan batasi revisi post ke 5. |
| `app/theme-settings.php` | Menu WP-Admin **Logo & Navbar**: upload logo, tinggi logo, brand text, favicon, CTA navbar, highlight card Mega Menu Produk & Layanan, logo partner footer, dan custom title tab browser. |
| `app/homepage-content-settings.php` | Menu WP-Admin **Konten Beranda**: 9 tab pengelola Hero Carousel, Mitra, Solusi Industri, Bento Keunggulan, Header/Filter Produk, Layanan Enterprise, Newsroom, Kontak/Form Beranda, serta **Tab Newsroom, Search & 404**. |
| `app/hero-slider.php` & `app/solusi-settings.php` | Pengelola slide hero default & 6 kartu Solusi Industri beranda (terhubung Single Source of Truth dengan 6 sektor layanan). |
| `app/layanan-settings.php` | Registry SSR Hub `/layanan/`, 6 Sektor, 3 Kategori, UI bersama + `/terima-kasih/`, flush cache layanan, dan non-destructive page provisioner (`fds_layanan_pages_provisioned_v1`). |
| `app/layanan-data-sectors.php` | Data SSR lengkap (narasi, tabel perbandingan metode, format luaran, faktor biaya, checklist KAK, FAQ) untuk 6 sektor: `pertambangan`, `perkebunan-pertanian`, `kehutanan-lingkungan`, `energi-infrastruktur`, `perencanaan`, `bencana-lingkungan`. |
| `app/layanan-data-categories.php` | Data SSR lengkap untuk 3 kategori layanan spesialis: `jasa-foto-udara-lidar`, `jasa-multispectral-hyperspectral`, dan `pelatihan-sertifikasi`. |
| `app/layanan-admin.php` | Menu WP-Admin **Konten Layanan** dengan tab terisolasi per halaman, catatan placeholder internal admin, tombol simpan non-destruktif, reset per tab, serta metabox pintasan di WP Pages. |
| `app/about-content-settings.php` | Menu WP-Admin **Konten Tentang Kami** (7 tab): Hero & Statistik (`2019`, `60,74%`, `ISO & SNI`, `100%`), Cerita Kami, 3 Spektrum Teknologi, Aktivitas & Mitra, Galeri, Sertifikasi, CTA/Workshop & Label Info. |
| `app/cpt-drones.php` | Mendaftarkan CPT `drone` (`has_archive => false`) dan taksonomi `kategori_drone` (`agrikultur`, `pemetaan-gis`, `kargo`), metabox spesifikasi khusus, helper Single Source of Truth Hero, Card & **Matriks 9 Baris (`/produk/`)** (`fds_get_drone_hero_media()`, `fds_get_drone_detail_data()`), purge cache katalog otomatis (`fds_purge_drone_catalog_caches()`), auto-seeder 9 model aktif, dan generator panduan teknis SSR 250–400 kata + `<details>` FAQ (tanpa duplikasi tabel spesifikasi). |
| `app/produk-content-settings.php` | Submenu **Produk Drone &rarr; Konten Katalog**: pengelolaan non-destruktif seluruh teks non-card di `/produk/`, `/kategori-drone/*`, Detail UI & **Tab Bandingkan** (Hero, Matrix header, Panduan, Sidebar, FAQ & SEO/Schema) dengan tombol Reset terisolasi per tab dan metabox pintasan `/produk/` & `/bandingkan/`. |
| `app/cpt-mitra.php` | CPT `mitra` untuk pengelolaan logo kemitraan marquee beranda (1-click media upload). |
| `app/brochure-settings.php` | Submenu **Produk Drone &rarr; Pengaturan Brosur**: kustomisasi UI unduh brosur 2-state, teks modal PDF fallback global, AJAX email capture (`fds_download_brochure`), log leads, dan ekspor CSV aman. |
| `app/footer-settings.php` | Menu WP-Admin **Kontak & Sosmed**: informasi PT, nomor WA/telepon, email, Google Maps, jam kerja, toggle tautan media sosial, copyright/legal, label kolom footer, serta copy halaman `/kontak/`. |
| `app/inquiry-handler.php` | Mendaftarkan CPT `fds_inquiry` (**Pesan Masuk**) dengan unread counter badge, meta box proyek/UTM/UU PDP, AJAX submission (`fds_submit_inquiry` + honeypot + Turnstile + redirect `/terima-kasih/`), submenu **Tracking & Konfigurasi Lead** (GA4/GTM/Ads/Pixel/Webhook), dan ekspor CSV anti-formula injection. |
| `app/preview-handler.php` | Intercept autosave custom meta & thumbnail CPT saat mode WP Preview (`is_preview()`) aktif. |
| `app/seo.php` | Koordinasi Rank Math `@graph`, Schema JSON-LD (`Organization`, `WebSite`, `CollectionPage`, `ItemList`, `ItemPage` + `about: Thing`, `Service`, `FAQPage`, `ContactPage`, `BlogPosting`, `BreadcrumbList`), 301 redirect legacy (`/rebo`, `/ferto-20l`, `/about`, dsb.), `noindex` halaman utilitas, `robots.txt` AI bot rules, diferensiasi `/llms.txt` (indeks ringkas) vs `/llms-full.txt` (korpus penuh), sitemap lengkap bersih, serta purge otomatis terpusat (`fds_purge_ai_and_seo_caches()`). |
| `app/auto-setup.php` | Auto-provisioning awal satu kali (`fds_theme_auto_setup_done_v1`) untuk halaman inti (`/beranda`, `/tentang-kami`, `/bandingkan`, `/kontak`, `/blog`), permalinks, slider, dan kontak default. |
| `app/migrations-2026.php` | Skrip migrasi & sanitasi idempoten 2026-11: membersihkan `.agents/`/`scratch/` jika ada di tema aktif, mempublikasikan `/produk/` & `/privacy-policy/`, membersihkan token/klaim terlarang, ungkapan negatif di opsi/meta (`fds_diplomatic_cert_scrub_v1`), serta memisahkan `ISO 9001:2015` (korporasi) dari metadata sertifikasi unit drone (`fds_unit_iso_cleanup_v1`) tanpa menimpa edit admin atau menghapus halaman/menu/tag lama. |

### B. Template Tampilan Frontend (`resources/views/`)
| File / Komponen Blade | Tampilan & Fitur Frontend |
| :--- | :--- |
| `layouts/app.blade.php` | Layout global, navbar sticky Apple blur, Unified Mega Menu 3 kategori + Layanan, Footer 4 kolom + logo partner multiply, GA4/GTM event telemetry. |
| `front-page.blade.php` | Beranda: Full-bleed Hero Carousel, Marquee Mitra, 6 kartu Solusi Industri (`↗`), Bento Keunggulan, **Daftar Baris `.drone-row` Filterable 3 Kategori**, Layanan Enterprise (1 baris 1 klik penuh), Newsroom, Hub Kontak. |
| `template-produk.blade.php` | Hub Katalog `/produk/`: Hero gelombang teal, filter 3 kategori (`Agrikultur`, `Pemetaan & GIS`, `Kargo`), **Grid `<x-drone-card>`** seragam penuh *object-cover*, **Matriks Ringkasan Live Per Kategori (Tab `.drone-tab` identik Beranda tanpa angka hitungan, Klik Baris Langsung ke Detail Unit, & kolom `Status Sertifikasi` dinamis pada kategori bersertifikat produk seperti Agrikultur)** + CTA Band gelombang teal. |
| `taxonomy-kategori_drone.blade.php` | Arsip `/kategori-drone/{slug}/` menggunakan Grid `<x-drone-card>` seragam dengan panah diagonal `↗`. |
| `single-drone.blade.php` | Detail Drone: Hero + 4 stat card, Tabel Spesifikasi + gambar offset melayang (`z-30`), Unduh Brosur PDF 2-State (`#0f808a`), 4 Use Cases, **Panduan Teknis 250–400w + FAQ Accordion `<details>` chevron `⌄`**, CTA Band gelombang teal. |
| `page-bandingkan.blade.php` | Alat Komparasi spesifikasi **9 model live CPT** secara berdampingan (*side-by-side*) dengan narasi & panduan pemilihan editable dari `Konten Katalog`. |
| `template-layanan-hub.blade.php` | Hub `/layanan/`: 6 kartu Sektor, 3 kartu Kategori Layanan Spesialis, alur survei, format luaran, KAK, dan FAQ `<x-service-faq>`. |
| `template-layanan-sektor.blade.php` & `template-layanan-kategori.blade.php` | Template SSR detail 6 sektor industri & 3 kategori layanan lengkap dengan tabel metode, deliverable, panduan biaya, `<x-service-faq>` (ikon chevron `⌄`), CTA terarah ke `/kontak/#hubungi-form`, dan `service-sticky-bar.blade.php`. |
| `page-tentang-kami.blade.php` | Profil PT Karya Solusi Angkasa (`2019`), 3 Spektrum Teknologi, Aktivitas Kemitraan, Galeri, Sertifikasi, Peta Workshop Sleman. |
| `page-kontak.blade.php` & `page-terima-kasih.blade.php` | Hub Kontak terpusat (auto-prefill parameter `?layanan=` / `?produk=`), Google Maps, dan konfirmasi konversi `/terima-kasih/` (`noindex`). |
| `index.blade.php`, `single.blade.php`, `search.blade.php`, `page.blade.php`, `404.blade.php` | Arsip Newsroom, detail artikel, hasil pencarian (`get_search_query(false)`), halaman standar, dan 404 dengan gelombang vektor teal. |
| `components/*` | `drone-card`, `service-card`, `service-faq`, `service-sticky-bar`, `breadcrumbs` (tanpa awalan `/`), `inquiry-hub`, `modal-brochure`, `google-map-card`, `page-hero`, `cta-band`, `section-header`, `badge`, `button`. |

---

## 🔍 3. Panduan Lokasi Edit di WP Admin (Where to Edit What)

```
WP-Admin Dashboard
│
├── ✈️ Produk Drone (CPT `drone`)
│   ├── Semua Drone / Tambah Baru ─────── Edit 9 model aktif, Featured Image (Hero + Card 1:1), spesifikasi, use cases, PDF
│   ├── Kategori Drone ────────────────── 3 Kategori Aktif (Agrikultur, Pemetaan & GIS, Kargo)
│   ├── Konten Katalog ────────────────── Edit non-card copy /produk/ & /kategori-drone/* (Hero, Matrix, Panduan, FAQ, SEO)
│   └── Pengaturan Brosur ─────────────── Teks 2-state brosur, fallback PDF, log leads & Ekspor CSV
│
├── 🤝 Mitra (CPT `mitra`) ────────────── Upload 1-click logo kemitraan marquee Beranda
│
├── 📩 Pesan Masuk (CPT `fds_inquiry`)
│   ├── Semua Pesan Masuk ─────────────── Lead proyek masuk (layanan, lokasi, UTM, badge unread) & Ekspor CSV
│   └── Tracking & Konfigurasi Lead ───── GA4, GTM, Google Ads, Meta Pixel, email penerima, Turnstile, webhook
│
├── 🎨 Logo & Navbar ──────────────────── Logo, brand text, favicon, CTA navbar, highlight Mega Menu, logo footer, title tab
├── 🏠 Konten Beranda ─────────────────── 8 Tab: Hero & Slider, Mitra, Solusi, Bento, Produk, Layanan, Newsroom, Kontak
├── 🌐 Konten Layanan ─────────────────── Hub Layanan, 6 Sektor, 3 Kategori Spesialis, Teks UI Bersama & /terima-kasih/
├── 🏢 Konten Tentang Kami ────────────── 7 Tab: Hero (2019), Cerita, Spektrum, Aktivitas, Galeri, Sertifikasi, CTA/Workshop
└── 📌 Kontak & Sosmed ────────────────── Alamat Sleman, WA/Telp, Email, Google Maps, Sosmed, Legal, Teks /kontak/
```

---

## 🛠️ 4. Alur Build & Deployment Lokal
Setiap melakukan perubahan kode pada template Blade, PHP, CSS, atau JS:
```powershell
# 1. Masuk ke direktori source tema
cd "c:\Users\BILALIHSAN\Local Sites\fds\app\public\fds-theme"

# 2. Build aset frontend produksi (Tailwind / Vite)
npm run build

# 3. Bersihkan aset build lama di mirror aktif agar tidak menumpuk
if (Test-Path "..\wp-content\themes\fds-theme\public\build") {
    Remove-Item "..\wp-content\themes\fds-theme\public\build\*" -Recurse -Force -ErrorAction SilentlyContinue
}

# 4. Sinkronisasikan seluruh kode & docs ke tema aktif WordPress
Copy-Item -Path "functions.php",".htaccess","AGENTS.md","GEMINI.md","PRD.md","PROJECT_MEMORY.md","COMPRO_GUIDE.md","INIT.md","CHANGELOG.md" -Destination "..\wp-content\themes\fds-theme\" -Force
Copy-Item -Path "app\*" -Destination "..\wp-content\themes\fds-theme\app\" -Recurse -Force
Copy-Item -Path "resources\*" -Destination "..\wp-content\themes\fds-theme\resources\" -Recurse -Force
Copy-Item -Path "public\build\*" -Destination "..\wp-content\themes\fds-theme\public\build\" -Recurse -Force

# 5. Hapus cache Blade Acorn (WAJIB agar perubahan Blade langsung tampil)
if (Test-Path "..\wp-content\cache\acorn\framework\views") {
    Remove-Item "..\wp-content\cache\acorn\framework\views\*" -Recurse -Force -ErrorAction SilentlyContinue
}
```
> *Catatan Keamanan Mirror:* Jangan menyalin folder `.agents/` maupun `scratch/` ke `..\wp-content\themes\fds-theme\`.

---

## 🛑 5. Strict AI Behavioral & UI Architecture Rules (Ringkasan Kepatuhan 2026-11)
Seluruh daftar aturan wajib tersedia rinci di [`AGENTS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/AGENTS.md) dan [`anti-hallucination-and-strict-guidelines.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/.agents/rules/anti-hallucination-and-strict-guidelines.md). Poin krusial:
1. **Warna Brand Teal `#0f808a`:** Jangan pernah memakai `#0066cc` pada tombol, link aktif, fokus, maupun ilustrasi.
2. **Bebas ALL-CAPS (`uppercase`):** Gunakan Title Case atau Sentence case (`FDS Station`, badge, heading, CTA).
3. **Nol Nested Container / Decorative Dots:** Tanpa card-inside-card dan tanpa bullet/dot dekoratif di samping judul/badge.
4. **Logo Footer & `mix-blend-mode: multiply`:** Terkunci di pojok kanan bawah kolom 4 desktop mengalir ke kiri; rata kiri di mobile; tanpa wrapper `opacity < 1` atau `transform`.
5. **Wave SVG Organik Tanpa Stroke di Setiap Section Teal Gelap:** Setiap section berlatar teal gelap/utama di semua halaman wajib memiliki vektor gelombang fluida gradien *fill-only* (`stroke` dilarang).
6. **Paritas Ikon & Breadcrumb:**
   * Seluruh kartu/tautan interaktif memakai panah diagonal (`↗`).
   * Seluruh akordeon (Detail Drone `.fds-faq-item` dan Layanan `<x-service-faq>`) seragam memakai ikon chevron ke bawah (`⌄`) yang berputar 180°, BUKAN `+`/`-`.
   * [`breadcrumbs.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/components/breadcrumbs.blade.php) dilarang menampilkan `/` di depan `Beranda`.
7. **Beranda `.drone-row` vs Katalog Grid `<x-drone-card>`:** Beranda `#produk` mempertahankan tampilan list horizontal; `/produk/` dan `/kategori-drone/*` memakai grid kartu *full-bleed* `object-cover` seragam.
8. **SSR-First & Anti-Double-Escaping:** Konten panduan & FAQ `<details>` wajib di HTML awal (SSR). Gunakan `fds_clean_string()` + `fds_scrub_legacy_text()`, hindari `{{ esc_html/esc_attr/esc_url }}`, gunakan `{!! esc_url(...) !!}` untuk URL query, `{!! wp_json_encode(...) !!}` untuk script JS, `get_search_query(false)` untuk output pencarian, serta jangan bungkus argumen `'alt'` pada `get_the_post_thumbnail()` dengan `esc_attr()`.
9. **Non-Destructive & Anti-Overwrite:** Dilarang menghapus halaman/post/tag/menu lama tanpa persetujuan tertulis atau menimpa opsi yang sudah disesuaikan admin WP.
