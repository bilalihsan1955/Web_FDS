# 📘 COMPRO_GUIDE.md — PANDUAN LENGKAP PENGELOLAAN WEBSITE COMPANY PROFILE FDS
## (Operational Runbook & Content Management Guide for PT Karya Solusi Angkasa)
**Tipe Platform:** Website Company Profile (Compro) & Lead Engine B2B Enterprise  
**Teknologi:** WordPress Custom Theme (Roots Sage 10 + Laravel Acorn Blade + Tailwind CSS + Vite)  
**Versi:** 2.5 (Enterprise Production Edition)  
**Status:** Dokumen Resmi Pengelolaan Konten Website Compro  
**Terakhir Diperbarui:** 2026-11-11  

---

## 📌 1. TUJUAN & RUANG LINGKUP DOKUMEN

Dokumen ini adalah **Panduan Operasional & Pengelolaan Konten (Manual Book / Runbook)** khusus untuk website resmi **PT Karya Solusi Angkasa (`Full Drone Solutions / FDS`)**.

### Perbedaan Dokumen Ini dengan `PRD.md`:
* **`PRD.md` (Product Requirements Document):** Menjelaskan **APA & MENGAPA** (*What & Why*). Berisi arsitektur fitur, kepatuhan klaim sertifikasi, funnel layanan B2B, skema SEO, dan kriteria penerimaan.
* **`COMPRO_GUIDE.md` (Dokumen Ini):** Menjelaskan **BAGAIMANA CARA MENGELOLA** (*How to Manage*). Berisi lokasi menu WP-Admin aktual, batas klaim legal yang wajib dijaga editor, pengelolaan lead masuk, dan prosedur build teknis.

---

## 🗺️ 2. MATRIKS PENGELOLAAN KONTEN WEBSITE COMPRO

| Bagian Halaman Website | Menu Pengaturan di WP-Admin | Sumber Data (`wp_options` / CPT) | File Backend Pengolah | Template Frontend (Blade) |
| :--- | :--- | :--- | :--- | :--- |
| **Brand, Logo, Favicon & Tab Title** | **Logo & Navbar** | `fds_navbar_logo_url`, `fds_navbar_brand_text`, `fds_site_favicon_url`, `fds_*_tab_title` | [`app/theme-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/theme-settings.php) | [`layouts/app.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/layouts/app.blade.php) |
| **Unified Mega Menu & Logo Footer** | **Logo & Navbar** | `fds_menu_prod_*`, `fds_menu_layanan_*`, `fds_footer_cert_logos` | [`app/theme-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/theme-settings.php) | [`layouts/app.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/layouts/app.blade.php) |
| **Hero, Solusi, Bento, Newsroom & Form Beranda** | **Konten Beranda** (8 Tab) | `fds_hero_slides`, `fds_hero_*`, `fds_solusi_*`, `fds_keunggulan_*`, `fds_layanan_*`, `fds_blog_*`, `fds_kontak_*` | [`app/homepage-content-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/homepage-content-settings.php) & [`app/solusi-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/solusi-settings.php) | [`front-page.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/front-page.blade.php) |
| **Logo Mitra Marquee Beranda** | **Mitra** (`CPT mitra`) | Custom Post Type `mitra` (Title + Featured Image) | [`app/cpt-mitra.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/cpt-mitra.php) | [`front-page.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/front-page.blade.php) |
| **Katalog 9 Model Drone & Spesifikasi** | **Produk Drone** (`CPT drone`) | CPT `drone` + Taksonomi `kategori_drone` (`Agrikultur`, `Pemetaan & GIS`, `Kargo`) + post meta `drone_*` | [`app/cpt-drones.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/cpt-drones.php) | [`front-page.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/front-page.blade.php), [`template-produk.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/template-produk.blade.php), [`taxonomy-kategori_drone.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/taxonomy-kategori_drone.blade.php), [`single-drone.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/single-drone.blade.php) |
| **Narasi, Matriks, FAQ & SEO Katalog (`/produk/` & Kategori)** | **Produk Drone &rarr; Konten Katalog** | `fds_produk_catalog_content_v1`, `fds_kat_drone_content_*_v1`, `fds_kat_drone_ui_v1` | [`app/produk-content-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/produk-content-settings.php) | [`template-produk.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/template-produk.blade.php) & [`taxonomy-kategori_drone.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/taxonomy-kategori_drone.blade.php) |
| **Gated Brosur & Log Leads Email** | **Produk Drone &rarr; Pengaturan Brosur** | `fds_brochure_*`, log leads unduhan brosur | [`app/brochure-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/brochure-settings.php) | [`single-drone.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/single-drone.blade.php) |
| **Alat Komparasi Drone (`/bandingkan/`)** | Halaman `/bandingkan/` | Membaca data live CPT `drone` + katalog master terkalibrasi | [`app/cpt-drones.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/cpt-drones.php) | [`page-bandingkan.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/page-bandingkan.blade.php) |
| **Hub, 6 Sektor & 3 Kategori Layanan (`/layanan/`)** | **Konten Layanan** (11 Tab) | `fds_layanan_hub_overrides_v1`, `fds_layanan_sector_*`, `fds_layanan_cat_*`, `fds_layanan_ui_overrides_v1` | [`app/layanan-admin.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/layanan-admin.php) & [`app/layanan-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/layanan-settings.php) | [`template-layanan-hub.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/template-layanan-hub.blade.php), [`template-layanan-sektor.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/template-layanan-sektor.blade.php), [`template-layanan-kategori.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/template-layanan-kategori.blade.php), [`page-terima-kasih.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/page-terima-kasih.blade.php) |
| **Profil & Sejarah PT (`/tentang-kami/`)** | **Konten Tentang Kami** (7 Tab) | `fds_about_*`, `fds_about_activity_items`, `fds_about_gallery_items` | [`app/about-content-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/about-content-settings.php) | [`page-tentang-kami.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/page-tentang-kami.blade.php) |
| **Kontak PT, Sosmed, Maps & `/kontak/`** | **Kontak & Sosmed** | `fds_global_*`, `fds_sosmed_*`, `fds_page_kontak_*`, `fds_footer_*` | [`app/footer-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/footer-settings.php) | [`page-kontak.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/page-kontak.blade.php) & [`layouts/app.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/layouts/app.blade.php) |
| **CRM Pesan Masuk & Tracking GA4/GTM** | **Pesan Masuk** (`CPT fds_inquiry`) & **Tracking & Konfigurasi Lead** | CPT `fds_inquiry` + `fds_ga4_id`, `fds_gtm_id`, `fds_lead_*`, `fds_turnstile_*` | [`app/inquiry-handler.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/inquiry-handler.php) | [`page-kontak.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/page-kontak.blade.php), [`front-page.blade.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/front-page.blade.php) |

---

## 📖 3. PANDUAN PENGELOLAAN SETIAP MODUL

### 3.1 Aturan Wajib Penulisan Konten & Kepatuhan Klaim (Editorial Guardrails)
Sebelum mengedit teks apa pun di WP-Admin, editor wajib mematuhi batasan berikut:
1. **Identitas Resmi:** Gunakan merek **Full Drone Solutions (FDS)** dan badan hukum **PT Karya Solusi Angkasa** (berbadan hukum PT tahun **2019**). Jangan memakai tahun pendirian lama yang belum terverifikasi.
2. **Batas Klaim TKDN & SNI:**
   * **TKDN + BMP 60,74% (Kemenperin RI):** HANYA untuk seluruh model **seri FERTO** (`FERTO 5, 10, 15, 22, 30, 50`). Dilarang memasang klaim TKDN pada `DELTAV`, `DELFRO`, maupun `MULTIPURPOSE` kecuali sudah terbit sertifikat resmi.
   * **SNI 9199:2023:** HANYA untuk **FERTO 15** dan **FERTO 22**.
   * **FERTO 15:** Tetap cantumkan kapasitas tangki operasional **`17 Liter`** (`15 – 17 Liter`).
3. **Klaim yang Dilarang:** Jangan menuliskan **Best UAV**, kode IP yang belum teruji resmi, nama merek sensor pihak ketiga tertentu (tulis **`kamera termal inframerah`**), janji SLA servis/balasan yang tidak terverifikasi, maupun penulisan **`FDS STATION`** huruf besar semua (wajib **`FDS Station`**).

### 3.2 Header, Unified Mega Menu & Footer
1. **Logo & Navbar:** Atur logo PNG transparan/SVG, brand text, tinggi logo (`34px` default desktop), teks CTA kanan atas, serta 3 kartu highlight Mega Menu Produk dan 2 kartu highlight Layanan.
2. **Logo Sertifikasi/Partner di Footer:** Diatur dari panel **Logo & Navbar**; render otomatis di pojok kanan bawah kolom 4 desktop mengalir ke kiri (rata kiri di mobile) dengan blending `mix-blend-mode: multiply` agar bebas kotak putih.

### 3.3 Beranda (`/`) & Katalog Produk (`/produk/` & `/kategori-drone/*`)
1. **Beranda (`/`):** Kelola 8 tab di menu **Konten Beranda**: Hero & Slider, Mitra, Solusi Industri (6 sektor terpadu), Keunggulan Bento, Header Produk, Layanan Enterprise, Newsroom, serta Kontak & Form. Bagian `#produk` di Beranda menampilkan daftar baris `.drone-row` yang ringkas.
2. **Katalog Hub (`/produk/`) & Arsip Kategori (`/kategori-drone/*`):** Menampilkan seluruh **9 model drone aktif** dalam tampilan grid kartu `<x-drone-card>` berseragam penuh *object-cover*. Seluruh **foto Hero, badge TKDN/SNI, kategori, tagline, dan metrik utama pada kartu tersinkronisasi 1:1 secara otomatis dari data Detail Unit Drone** ([`app/cpt-drones.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/cpt-drones.php)).
3. **Edit Konten Non-Card Katalog (`Produk Drone` &rarr; `Konten Katalog`):** Kelola seluruh teks Hero `/produk/`, bar highlight sertifikasi, header kategori, tabel ringkas spesifikasi, panduan kategori, sidebar dukungan lokal, FAQ SSR, CTA penutup, serta SEO Meta Title/Description & Schema `CollectionPage`/`FAQPage` secara non-destruktif dengan tombol Reset terisolasi per tab ([`app/produk-content-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/produk-content-settings.php)).

### 3.4 Pengelolaan Unit Drone & Unduh Brosur PDF
1. **Edit Unit (`Produk Drone`):** Kelola melalui **Produk Drone &rarr; Semua Drone** (judul, **Featured Image / Gambar Unggulan yang menjadi gambar Hero Detail sekaligus foto kartu katalog**, foto spesifikasi transparan offset melayang, 4 kartu statistik atas, 8 parameter teknis, 4 skenario penggunaan, dan file PDF brosur). Penyimpanan unit otomatis menghapus cache katalog & Mega Menu (`fds_purge_drone_catalog_caches()`).
2. **Panduan Teknis & FAQ SSR:** Biarkan kotak editor utama kosong jika ingin sistem menggunakan generator panduan teknis 250–400 kata + FAQ akordeon chevron terstandarisasi secara otomatis dari metadata resmi tanpa duplikasi tabel.
3. **Gated Download Brosur PDF (`Produk Drone &rarr; Pengaturan Brosur`):** Pengunjung mengisi email bisnis -> AJAX mencatat lead & memvalidasi -> tombol berubah menjadi **"Download Brosur PDF"** aktif berwarna Teal **`#0f808a`**. Tim marketing dapat melihat log dan mengklik **"Ekspor Data ke CSV"** (aman dari formula Excel).

### 3.5 Hub & Halaman Layanan (`/layanan/` + 6 Sektor + 3 Kategori)
1. Buka menu **Konten Layanan** di sidebar kiri WP-Admin (atau klik tombol pintasan *"Buka Editor Konten Layanan"* di sidebar halaman WordPress terkait).
2. Setiap tab (`Hub Layanan`, `01. Pertambangan` s.d. `06. Bencana dan Lingkungan`, 3 kategori spesialis, serta `UI Bersama & Terima Kasih`) menyimpan override secara terisolasi tanpa mengubah tab lain dan memiliki tombol **"Reset Halaman Ini ke Default"**.
3. Catatan internal tim (`placeholders`) hanya muncul pada kotak kuning di WP-Admin dan tidak pernah dicetak di HTML publik. Seluruh CTA layanan mengarahkan prospek ke `/kontak/#hubungi-form?layanan=...`.

### 3.6 Halaman Tentang Kami (`/tentang-kami/`), Kontak (`/kontak/`) & Pesan Masuk
1. **Tentang Kami:** Dikelola penuh dari menu **Konten Tentang Kami** (7 tab). Editor standar WordPress Pages pada halaman ini hanya dipakai untuk metadata SEO Rank Math agar layout Apple minimalist tetap konsisten.
2. **Kontak & Maps:** Dikelola dari menu **Kontak & Sosmed** (informasi PT, telepon/WA, email, jam kerja Senin–Sabtu, link Google Maps, tautan sosmed, dan label form `/kontak/`).
3. **Pesan Masuk (`CPT fds_inquiry`) & Tracking:** Setiap pengiriman formulir penawaran di Beranda maupun `/kontak/` masuk ke **Pesan Masuk** lengkap dengan kategori layanan, lokasi, luas/kebutuhan proyek, parameter UTM, bukti persetujuan UU PDP, serta tombol **Ekspor Semua Lead (CSV / Excel)**. Konfigurasi GA4 (`G-...`), GTM, Cloudflare Turnstile, email penerima, dan webhook diatur di **Pesan Masuk &rarr; Tracking & Konfigurasi Lead**.

---

## 🔍 4. PANDUAN SEO, RANK MATH & AI SEARCH (`/llms.txt`)

1. **Dual-Engine & `@graph` Terpadu:** Saat Rank Math aktif, [`app/seo.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/seo.php) menyelaraskan schema JSON-LD agar tidak terjadi metadata ganda.
2. **Schema B2B Bebas Error Search Console:** Halaman single drone memancarkan `ItemPage` (`about: Thing`) + `FAQPage` + `BreadcrumbList` tanpa `Product`/`Offer`/rating fiktif. `Organization`/`LocalBusiness` menggunakan `legalName: PT Karya Solusi Angkasa` tanpa field usang `priceRange: "$$$$"`.
3. **Skor Rank Math di Admin vs Frontend:** Skor editor WP-Admin hanya menilai teks di editor klasik/Gutenberg; Googlebot & perayap membaca HTML SSR penuh (tabel spesifikasi, panduan teknis, tabel metode layanan, dan akordeon `<details>`).
4. **AI Search Readiness (`/llms.txt`):** Berkas Markdown bersih di `/llms.txt` dan `/llms-full.txt` tersaji tanpa entitas `&amp;` mentah, disegarkan ke disk secara otomatis, dan didukung izin bot AI di `robots.txt`.

---

## 🛠️ 5. SOP PEMELIHARAAN & BUILD CODE

Setiap kali tim teknis melakukan pembaruan pada template Blade, file PHP, CSS, atau JavaScript:
```powershell
# 1. Masuk ke folder source tema
cd "c:\Users\BILALIHSAN\Local Sites\fds\app\public\fds-theme"

# 2. Kompilasi aset frontend
npm run build

# 3. Bersihkan aset build lama di mirror aktif agar hash lama tidak menumpuk
if (Test-Path "..\wp-content\themes\fds-theme\public\build") {
    Remove-Item "..\wp-content\themes\fds-theme\public\build\*" -Recurse -Force -ErrorAction SilentlyContinue
}

# 4. Sinkronisasi ke tema aktif WordPress
Copy-Item -Path "functions.php",".htaccess","AGENTS.md","GEMINI.md","PRD.md","PROJECT_MEMORY.md","COMPRO_GUIDE.md","INIT.md","CHANGELOG.md" -Destination "..\wp-content\themes\fds-theme\" -Force
Copy-Item -Path "app\*" -Destination "..\wp-content\themes\fds-theme\app\" -Recurse -Force
Copy-Item -Path "resources\*" -Destination "..\wp-content\themes\fds-theme\resources\" -Recurse -Force
Copy-Item -Path "public\build\*" -Destination "..\wp-content\themes\fds-theme\public\build\" -Recurse -Force

# 5. Hapus cache view Blade Acorn
if (Test-Path "..\wp-content\cache\acorn\framework\views") {
    Remove-Item "..\wp-content\cache\acorn\framework\views\*" -Recurse -Force -ErrorAction SilentlyContinue
}
```

---

*Dokumen ini merupakan panduan resmi pengelolaan konten website company profile PT Karya Solusi Angkasa (`Full Drone Solutions`).*
