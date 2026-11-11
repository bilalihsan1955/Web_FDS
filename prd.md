# 📑 PRD.md — PRODUCT REQUIREMENTS DOCUMENT
## Pengembangan Website Company Profile (Compro) & Lead Engine B2B Enterprise Full Drone Solutions (FDS)

**STATUS: PRODUCTION (VERSI MULTI-HALAMAN COMPRO + FUNNEL LAYANAN B2B ENTERPRISE)**  
**Versi Dokumen:** v2.5 (Enterprise Production Edition)  
**Tipe Platform:** Website Company Profile (Compro) & Lead Engine B2B Enterprise  
**Entitas:** PT Karya Solusi Angkasa (`Full Drone Solutions`)  
**Disusun oleh:** Bilal Al Ihsan (Pengembang Utama & UI/UX Specialist)  
**Untuk:** Tim Manajemen & Stakeholder PT Karya Solusi Angkasa  
**Tanggal Rilis:** 2026-11-11  
**Dokumen Terkait:** [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md), [`PROJECT_MEMORY.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PROJECT_MEMORY.md), [`AGENTS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/AGENTS.md), [`INIT.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/INIT.md), [`SEO_KEYWORDS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/SEO_KEYWORDS.md)  

---

# 1. Ringkasan Eksekutif (Executive Summary)

Website **Full Drone Solutions (FDS)** — berbadan hukum resmi **PT Karya Solusi Angkasa** (berbadan hukum PT sejak 2019) yang berlokasi di Sleman, D.I. Yogyakarta — merupakan platform digital resmi manufaktur Pesawat Udara Nirawak (UAV) dan penyedia layanan pemetaan geospasial terkemuka di Indonesia.

Platform ini dirancang sebagai **Website Company Profile & Lead Engine B2B Enterprise Modern** di atas Roots Sage 10 + Laravel Acorn Blade + Tailwind CSS + Vite untuk:
1. Menampilkan kepatuhan legalitas resmi nasional secara akurat:
   * **TKDN + BMP mencapai 60,74%** (Kementerian Perindustrian RI) berlaku khusus pada seluruh lini drone pertanian seri **FERTO** (`5, 10, 15, 22, 30, 50`).
   * **SNI 9199:2023** berlaku khusus untuk model **FERTO 15** (kapasitas kerja **17 Liter**) dan **FERTO 22**.
   * **ISO 9001:2015** Sistem Manajemen Mutu dan kepatuhan regulasi operator **DKPPU CASR Part 107**.
2. Menyajikan katalog terstruktur berisi **9 model drone aktif** dalam **3 kategori utama** (`Agrikultur`, `Pemetaan & GIS`, dan `Kargo`), sekaligus funnel **Hub Layanan (`/layanan/`)**, **6 halaman sektor industri**, dan **3 halaman kategori layanan spesialis**.
3. Mengonversi pengunjung korporasi, BUMN, dan instansi menjadi prospek terukur melalui **Formulir Konsultasi Terpadu (`/kontak/#hubungi-form`)** beserta auto-prefill `?layanan=` / `?produk=`, **Gated Download Brosur PDF**, **Mobile Sticky WhatsApp CTA**, dan **pelacakan telemetri GA4/GTM**.
4. Menjaga keterbacaan penuh oleh bot mesin pencari dan AI melalui **Server-Side Rendered (SSR)** HTML mentah (termasuk panduan teknis dan FAQ accordion `<details>`), **Schema JSON-LD `@graph`** yang valid tanpa field fiktif, dan `/llms.txt`.

---

# 2. Tujuan & Sasaran Bisnis (Business Goals & Metrics)

* **Otoritas Manufaktur Nasional:** Mengukuhkan PT Karya Solusi Angkasa (Full Drone Solutions) sebagai produsen UAV nasional No.1 di Indonesia dengan fasilitas riset dan perakitan di Sleman, ketersediaan suku cadang lokal siap pakai, layanan servis terjadwal, dan Ground Control Station berbahasa Indonesia (**FDS Station**).
* **Akurasi Klaim & Kepatuhan Regulasi:** Memisahkan klaim TKDN + BMP 60,74% khusus seri FERTO, SNI 9199:2023 khusus FERTO 15 & FERTO 22, kapasitas FERTO 15 `17 Liter`, serta membersihkan klaim historis atau sertifikasi teknis yang belum terverifikasi.
* **Konversi Lead B2B Terintegrasi:** Mengarahkan intent komersial dari setiap halaman layanan dan produk ke funnel terpusat `/kontak/#hubungi-form`, merekam CRM WP-Admin (**Pesan Masuk** `fds_inquiry`), dan mengarahkan konversi berhasil ke `/terima-kasih/` (`noindex`).
* **Kecepatan, SSR-First & AI Search (GEO):** Zero page-builder bloat, Scroll native compositor, semua konten teknis dan FAQ tersedia langsung dalam initial HTML untuk perayap web dan model AI via Schema JSON-LD dan `/llms.txt`.

---

# 3. Profil Pengguna & Peran (User Personas & Roles)

1. **Pejabat Pengadaan / BUMN / Lembaga Negara:** Membutuhkan kepastian ruang lingkup TKDN 60,74% (lini FERTO), SNI 9199:2023 (FERTO 15 & FERTO 22), ISO 9001:2015, dan dokumen spesifikasi PDF untuk verifikasi e-Katalog LKPP maupun lelang resmi.
2. **Manajer Operasional Tambang, Perkebunan, Kehutanan & Energi:** Membutuhkan data teknis riil wahana (payload, durasi terbang, baterai, sensor LiDAR/Multispektral/Termal inframerah), tabel perbandingan metode, daftar luaran deliverable (`LAS/LAZ`, `GeoTIFF`, `SHP`, `DWG`), dan estimasi lingkup layanan sektor.
3. **Pilot UAV & Tim Teknis Riset:** Memerhatikan integrasi **FDS Station** GCS, pelatihan/bootcamp, dan pendampingan sertifikasi pilot DKPPU CASR Part 107.
4. **Admin Konten & Tim Marketing FDS:** Mengelola konten Beranda, Layanan Hub/Sektor/Kategori, Katalog Drone, Tentang Kami, Kontak, Leads Brosur, serta Pesan Masuk & Konfigurasi Tracking (`GA4`, `GTM`, `Turnstile`, `Webhook`) langsung di WP-Admin.

---

# 4. Cakupan Sistem & Fitur Website Compro (Core Features)

## 4.1 Navigasi Global, Mega Menu & Identitas Warna (NAV)
* **NAV-1 (Apple-Style Blur Header):** Header sticky `backdrop-blur`, logo/brand dinamis (`Full Drone Solutions`), tombol CTA kanan atas, dan highlight menu aktif.
* **NAV-2 (Mega Menu Produk):** Dropdown 3 tab kategori aktif (`Agrikultur`, `Pemetaan & GIS`, `Kargo`), 3 kartu highlight otoritas (**FDS Station GCS**, **TKDN + BMP 60,74%** seri FERTO, **SNI 9199:2023** FERTO 15 & 22), tautan ke hub `/produk/`, dan tautan `/bandingkan/`.
* **NAV-3 (Mega Menu Layanan):** Dropdown terhubung dengan hub `/layanan/`, 6 sektor industri, serta 3 layanan spesialis (`Foto Udara & LiDAR`, `Multispectral & Hyperspectral`, `Pelatihan & Sertifikasi`).
* **NAV-4 (Sistem Warna Brand Teal `#0f808a`):** Seluruh aksen utama, tombol, focus ring, highlight, dan gelombang vektor latar gelap menggunakan palet teal terkalibrasi (`#0f808a`, `#0a555c`, `#052b2e`, `#13abb9`, `#18d6e7`, `#75e6f0`).

## 4.2 Halaman Beranda Compro (`/`)
* **HOM-1 (Full-Bleed Cinematic Hero):** Slider layar penuh dengan overlay gradien teal gelap, judul H1 dinamis, deskripsi bebas klaim usang, dan dua tombol CTA.
* **HOM-2 (Marquee Mitra Strategis):** Deretan logo mitra instansi nasional dan riset (`CPT mitra`).
* **HOM-3 (Solusi 6 Sektor Industri):** 6 kartu interaktif (`.fds-service-card--interactive` dengan panah diagonal `↗`, tag sekunder interaktif `.fds-card-tag-link`) selaras Single Source of Truth `app/layanan-data-sectors.php`: `Pertambangan`, `Perkebunan dan Pertanian`, `Kehutanan dan Lingkungan`, `Energi dan Infrastruktur`, `Perencanaan`, serta `Bencana dan Lingkungan`.
* **HOM-4 (Bento Keunggulan Manufaktur):** Grid asimetris pilar manufaktur Sleman (Rangka Karbon & ISO 9001:2015, TKDN 60,74% seri FERTO, FDS Station GCS Bahasa Indonesia, Standar Mutu & SNI, Suku Cadang Lokal Siap Pakai, Pelatihan Pilot DKPPU, Skalabilitas Armada).
* **HOM-5 (Katalog List Drone di Beranda `#produk`):** Tetap mempertahankan layout baris daftar horizontal (`.drone-row`) dengan filter kategori interaktif (`Agrikultur`, `Pemetaan & GIS`, `Kargo`), menjaga konsistensi editorial Beranda sambil mengarahkan penelusuran kartu penuh ke `/produk/`.
* **HOM-6 (Dukungan Layanan Enterprise & Newsroom):** Daftar baris layanan lapangan (1 klik penuh per baris menuju `/layanan/*`) dengan gelombang vektor latar teal dan daftar artikel Newsroom terbaru.
* **HOM-7 (Hub Inquiry Beranda `#kontak`):** Formulir penawaran terintegrasi dengan opsi layanan otomatis, validasi CSRF nonce, honeypot, Turnstile opsional, dan pengalihan `/terima-kasih/`.

## 4.3 Katalog Hub (`/produk/`), Taksonomi (`/kategori-drone/*`) & Detail Drone (CPT `drone`)
* **DRN-1 (9 Model Aktif & 3 Kategori):** Model aktif `FERTO 5`, `FERTO 10`, `FERTO 15` (`17 Liter`), `FERTO 22`, `FERTO 30`, `FERTO 50`, `DELTAV`, `MULTIPURPOSE`, dan `DELFRO` dalam kategori `Agrikultur`, `Pemetaan & GIS`, dan `Kargo` (model/rute lama seperti `/rebo` dan `/ferto-20l` diarahkan aman via 301).
* **DRN-2 (Standarisasi Kartu Katalog Grid `<x-drone-card>` & Paritas Hero Detail 1:1):** Pada `/produk/` dan `/kategori-drone/*`, kartu memiliki tinggi seragam, gambar penuh *full-bleed* `object-cover` rasio `4/3` tanpa padding abu-abu, area klik utama stretched-link (`.fds-stretched-link`), badge kategori/sertifikasi akurat, dan tombol terpisah untuk komparasi. Foto utama serta spesifikasi kartu tersinkronisasi **1:1** secara otomatis dengan section Hero halaman detail unit (`\App\fds_get_drone_hero_media()` & `\App\fds_get_drone_detail_data()`), sementara seluruh konten editorial di luar kartu (Hero katalog, highlight sertifikasi, judul/deskripsi section kategori, tabel matriks spesifikasi, panduan kategori, sidebar, FAQ, CTA penutup, serta SEO meta & Schema `CollectionPage`/`FAQPage`) tetap **100% editable** non-destruktif melalui **Produk Drone &rarr; Konten Katalog** ([`app/produk-content-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/produk-content-settings.php)).
* **DRN-3 (Halaman Detail `single-drone.blade.php`):** Ringkasan Hero + metrik utama -> Tabel Spesifikasi & gambar offset melayang (`z-20`/`z-30`) -> **Sistem Gated Download Brosur PDF 2-State** (State 1 input email -> AJAX -> State 2 tombol aktif `#0f808a`) -> 4 Skenario Operasional -> **Panduan Teknis Ringkas (250–400 kata)** + **FAQ Accordion `<details>` SSR** dengan ikon panah chevron seragam (tanpa pengulangan tabel spesifikasi ganda di bawah).

## 4.4 Funnel Layanan B2B (`/layanan/`, Sektor & Kategori Spesialis)
* **LYN-1 (Hub Layanan `/layanan/`):** Merangkum 6 sektor industri, 3 layanan teknis, alur kerja survei, format luaran data spasial, daftar periksa KAK pengadaan, dan FAQ SSR (`<x-service-faq>`).
* **LYN-2 (6 Halaman Sektor `/layanan/{sektor}/` & 3 Halaman Kategori `/layanan/{kategori}/`):** Menggunakan `template-layanan-sektor.blade.php` & `template-layanan-kategori.blade.php` berisi: Hero -> Ringkasan Tantangan & Solusi -> Tabel Perbandingan Metode SSR -> Daftar Luaran Deliverable & Perangkat Lunak -> Panduan Faktor Penentu Biaya & KAK -> FAQ Accordion SSR (`⌄`) -> CTA konversi terpusat menuju `/kontak/?layanan=...#hubungi-form`.
* **LYN-3 (Mobile Sticky Bar):** Komponen `service-sticky-bar.blade.php` menampilkan tombol cepat penawaran (`/kontak/#hubungi-form`) dan WhatsApp di layar mobile tanpa menutupi konten utama.
* **LYN-4 (WP Admin Editable Hub `Konten Layanan`):** Seluruh tab dikelola non-destruktif per halaman di WP-Admin (`app/layanan-admin.php`) dengan tombol reset terisolasi per tab dan flush cache otomatis.

## 4.5 Halaman Komparasi (`/bandingkan/`), Profil (`/tentang-kami/`) & Kontak (`/kontak/`)
* **CMP-1 (`/bandingkan/`):** Komparasi spesifikasi teknis lintas model drone FDS berdampingan (*side-by-side*), mencakup payload, durasi terbang, baterai, produktivitas, FDS Station GCS, dan ruang lingkup TKDN/SNI.
* **ABT-1 (`/tentang-kami/`):** Profil **PT Karya Solusi Angkasa** (berbadan hukum **2019**), statistik kunci besar (`2019`, `60,74%`, `ISO & SNI`, `100%`), spektrum 3 arsitektur wahana (Rotary Wing, Fixed Wing, Hybrid VTOL DELTAV), aktivitas kemitraan (Bappenas, PRISMA, Bank Indonesia, UGM-Swiss), galeri dokumentasi, sertifikasi, dan Peta Workshop Sleman.
* **CNT-1 (`/kontak/` & `/terima-kasih/`):** Hub kontak dua kolom (info PT Sleman, jam kerja Senin–Sabtu, WhatsApp + formulir penawaran dengan auto-prefill parameter `?layanan=` / `?produk=`), Google Maps workshop, dan halaman `/terima-kasih/` berstatus `noindex`.

## 4.6 Global Footer & Paritas Desain
* **FTR-1 (Grid 4 Kolom & Penempatan Logo Partner):** Logo partner/sertifikasi dikunci di pojok kanan bawah kolom 4 pada desktop (`absolute right-0 bottom-0`) mengalir ke kiri, dan rata kiri di mobile.
* **FTR-2 (Transparansi Multiply):** Logo footer memakai `mix-blend-mode: multiply !important; background-color: transparent !important;` tanpa parent opacity/transform.
* **FTR-3 (Wave Background Vector & Ikon Konsisten):** Setiap `<section>` berlatar teal gelap memakai SVG gelombang organik *fill-only* (nol `stroke`), seluruh tautan/kartu interaktif memakai panah diagonal (`↗`), breadcrumb bebas garis miring awalan `/`, dan seluruh teks wajib Title Case / Sentence case (zero CSS `uppercase`).

---

# 5. Kebutuhan Non-Fungsional & Keamanan

* **Keamanan Web & Form (`app/security.php`, `app/inquiry-handler.php`, `.htaccess`):**
  * Proteksi WP Nonces (`check_ajax_referer`), kolom honeypot anti-bot, dukungan Cloudflare Turnstile, persetujuan privasi UU PDP, serta anti-CSV Formula Injection pada ekspor CSV Leads & Brosur.
  * Header keamanan HTTP (`X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy`, `COOP`, `Permissions-Policy`, `CSP`, `HSTS` saat HTTPS).
  * Penonaktifan cache LiteSpeed untuk endpoint AJAX dinamis (`fds_submit_inquiry`, `fds_download_brochure`), penghapusan jejak `?ver=` WP, serta aturan `.htaccess` yang memblokir akses langsung HTTP ke `.agents/`, `scratch/`, file `.md`, `.env`, `.sql`, `.log`, dan sumber `.blade.php`.
  * Guard `noindex, nofollow` otomatis pada host staging (`compro.*`, `staging.*`, `.local`).
* **Sanitasi Output & Anti-Double-Escaping:** Seluruh template Blade mendecode entity DB melalui `fds_clean_string()` / `fds_deep_decode()` + `fds_scrub_legacy_text()` agar `&amp;` / `&#038;` tidak pernah muncul secara harfiah, memakai `{!! esc_url(...) !!}` untuk URL berparameter query, `{!! wp_json_encode(...) !!}` untuk literal JS, serta `get_search_query(false)` untuk pencarian.
* **Performa Frontend:** Penghentian hijacking scroll `Lenis` demi scrolling native compositor browser, sizing gambar responsif `fds_optimized_img_url()`, isolasi kontainer SVG `contain: layout paint`, dan view cache terkompilasi Laravel Acorn.
* **Non-Destructive Rule:** Auto-seeder dan skrip migrasi `app/migrations-2026.php` dilarang menimpa kustomisasi database admin maupun menghapus halaman/menu/tag lama tanpa persetujuan tertulis.

---

# 6. Integrasi SEO, Schema JSON-LD & GEO

* **Dual-Engine SEO (`app/seo.php`):** Koordinasi penuh dengan **Rank Math SEO** (`rank_math/json_ld`) dan perlindungan kanonis / bahasa `id-ID` (`inLanguage`).
* **Valid B2B Schema `@graph`:** Output terstruktur untuk `Organization`/`Corporation`/`LocalBusiness` (dengan `legalName: PT Karya Solusi Angkasa`, bebas `priceRange: "$$$$"`), `WebSite`, `CollectionPage`/`ItemList` (`/produk/`, `/layanan/`, `/kategori-drone/*`), `Service`, `FAQPage`, `ContactPage`, `BlogPosting`, `BreadcrumbList`, serta `ItemPage` + `about: Thing` pada `single-drone` (tanpa snippet `Product`/`Offer`/ulasan fiktif).
* **Crawl Hygiene & Sitemap:** Arsip `/drone/` dinonaktifkan, rute legacy dialihkan 301 ke URL aktif, halaman utilitas/terima-kasih dilabeli `noindex`, kategori kosong dikeluarkan dari sitemap, dan `/llms.txt` + `/llms-full.txt` tersaji bersih (Markdown terdekode bebas `&amp;`) bersama izin bot AI pada `robots.txt`.

---

# 7. Penerimaan & Kriteria Keberhasilan (Acceptance Criteria)

1. Seluruh halaman inti (`/`, `/produk/`, `/kategori-drone/*`, `/drone/*`, `/layanan/`, `/layanan/{sektor}/`, `/layanan/{kategori}/`, `/tentang-kami/`, `/bandingkan/`, `/kontak/`, `/terima-kasih/`, `/blog/`, pencarian, dan `/llms.txt`) tampil dengan HTTP 200 tanpa PHP error dan tanpa teks `&amp;` / entity mentah.
2. Seluruh `<section>` teal gelap memiliki latar SVG gelombang organik tanpa stroke; seluruh akordeon memakai ikon chevron seragam (`⌄`); tautan interaktif memakai panah diagonal (`↗`); Beranda `#produk` mempertahankan list `.drone-row`; dan halaman `/produk/` memakai grid `<x-drone-card>` seragam.
3. Pengiriman formulir `/kontak/#hubungi-form` merekam entri `fds_inquiry`, memicu event telemetri GA4 (`generate_lead`), dan mengalihkan ke `/terima-kasih/`; unduh brosur 2-state merekam email leads dan membuka PDF.
4. Nol kemunculan klaim terlarang, warna usang `#0066cc`, maupun teks CSS ALL-CAPS `uppercase` di HTML ter-render.
5. Panduan pengelolaan dan aturan teknis terdokumentasi akurat di [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md) dan [`AGENTS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/AGENTS.md).

---

*Dokumen ini merupakan spesifikasi resmi kebutuhan produk Website Company Profile & Lead Engine Full Drone Solutions (FDS) — PT Karya Solusi Angkasa.*
