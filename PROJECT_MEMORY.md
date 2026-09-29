# 🧠 PROJECT MEMORY & ARCHITECTURE MASTER REFERENCE
**Website:** Full Drone Solutions (FDS) — PT Karya Solusi Angkasa  
**Platform:** WordPress Custom Theme (Roots Sage / Acorn Blade Architecture)  
**Terakhir Diperbarui:** 2026-09-29  

---

## 📌 1. Profil & Identitas Bisnis (Brand Context)
* **Perusahaan:** PT Karya Solusi Angkasa (Full Drone Solutions / FDS)
* **Status:** Produsen & Manufaktur Pesawat Udara Nirawak (UAV) Resmi Nasional Indonesia
* **Lokasi Kantor & Fasilitas Produksi:** Sleman, Daerah Istimewa Yogyakarta 55514
* **Legalitas & Sertifikasi Resmi:**
  * **TKDN + BMP:** Nilai kandungan dalam negeri mencapai **60,74%** (Kementerian Perindustrian RI)
  * **SNI 9199:2023:** Standar Nasional Indonesia untuk Pesawat Udara Nirawak (UAV) Pertanian
  * **ISO 9001:2015:** Sistem Manajemen Mutu Manufaktur
  * **Regulasi Penerbangan:** Kelaikudaraan DKPPU Kementerian Perhubungan RI & Sertifikasi Pilot FDS Academy
* **Nilai Keunggulan Utama (Value Proposition):**
  1. Legal untuk tender pemerintah, BUMN, & e-Katalog LKPP (Prioritas belanja produk ber-TKDN).
  2. Suku cadang *ready-stock* lokal di Yogyakarta tanpa perlu menunggu inden impor luar negeri.
  3. Sistem Ground Control Station buatan sendiri berbahasa Indonesia (**FDS STATION GCS**).
  4. Garansi dan tim teknisi langsung dari pabrikan di Indonesia.

---

## 📁 2. Peta File & Struktur Arsitektur Tema

### A. Core Engine & Backend Logic (`app/`)
| File | Fungsi & Tanggung Jawab Utama |
| :--- | :--- |
| `app/cpt-drones.php` | **Custom Post Type Drone & Taksonomi:** Mendaftarkan CPT `drone`, 4 Kategori Resmi (`Agrikultur`, `Pemetaan & GIS`, `Kargo`, `Reboisasi`), Form Input Spesifikasi Khusus di WP Admin, Auto-Seeder 10 model drone, dan **Auto-Generator Artikel SEO 800–1200+ kata** ke `post_content` serta sinkronisasi metadata Rank Math. |
| `app/seo.php` | **Enterprise Technical & Structured Data SEO:** Render Meta Tags, OpenGraph, Twitter Cards, Schema.org JSON-LD lengkap (`Organization`, `LocalBusiness`, `WebSite`, `Breadcrumbs`, `Service`, `FAQPage`, `Product`), **301 Permanent Redirects** (`/about`, `/about-us`, `/profil` -> `/tentang-kami/`), **AI Bot Directives** (`robots.txt`), **GEO Endpoint** (`/llms.txt`), dan Standalone XML Sitemap. |
| `app/theme-settings.php` | **Pengaturan Logo & Navbar:** Menu WP Admin khusus untuk kelola logo, brand text, favicon, tinggi logo, shortcut admin bar, dan title browser tab dinamis. |
| `app/homepage-content-settings.php` | **Pengaturan Konten Beranda:** Mengelola Hero Slider, Stats Bar, Solusi/Layanan (repeater dropdown & section), Fitur Inovasi, Testimoni, Newsroom, dan Form Kontak. |
| `app/about-content-settings.php` | **Pengaturan Konten Tentang Kami:** Hero Profil, Visi-Misi, Sejarah/Milestone Perusahaan, Tim Manajemen & Engineer, Fasilitas Manufaktur Sleman, dan Nilai TKDN. |
| `app/footer-settings.php` | **Pengaturan Footer & Kontak Global:** Alamat workshop Sleman, link WhatsApp, Email, telepon, Google Maps embed, jam operasional, link sosial media, dan copyright. |
| `app/auto-setup.php` | **Auto-Provisioning & Migration Engine:** Otomatisasi pembuatan halaman inti (`/beranda`, `/tentang-kami`, `/bandingkan`, `/blog`), permalinks, auto-migration DB, dan sinkronisasi SEO. |

---

### B. Template Tampilan Frontend (`resources/views/`)
| File Blade | Tampilan & Fitur Frontend |
| :--- | :--- |
| `resources/views/layouts/app.blade.php` | Layout utama, Apple-style blur navbar, **Unified Mega Menu Drawer (Produk & Layanan)**, dynamic active menu underline, dan Global Footer. |
| `resources/views/front-page.blade.php` | Halaman Beranda lengkap: **Full-Bleed Background Hero Section** (gambar latar layar penuh sinematik dengan multi-layer gradient overlay & slider interaktif), Stats Bar, Katalog Filterable Drone, Solusi Industri, Video / Fasilitas, Keunggulan Manufaktur, Testimoni, Blog, & Kontak Form. |
| `resources/views/single-drone.blade.php` | Halaman Detail Produk Drone: Galeri foto, badge TKDN & SNI, highlight statistik, tabel spesifikasi interaktif, 4 skenario penggunaan (use cases), dan FAQ. |
| `resources/views/page-bandingkan.blade.php` | Alat Komparasi Spesifikasi Teknis: Membandingkan 2 atau lebih model drone FDS secara berdampingan (*side-by-side*). |
| `resources/views/page-tentang-kami.blade.php` | Halaman Profil Perusahaan PT Karya Solusi Angkasa: Sejarah, Visi Misi, Sertifikasi TKDN/SNI/ISO, Tim Inti, dan Fasilitas Riset di Sleman. |

---

## 🔍 3. Panduan Lokasi Edit di WP Admin (Where to Edit What)

```
WP-Admin Dashboard
│
├── ✈️ Produk Drone (CPT)
│   ├── Semua Drone ────────────── Edit Nama Drone, Foto, Spesifikasi, Tagline, Use Cases
│   ├── Tambah Drone Baru ──────── Form input spesifikasi lengkap tanpa editor artikel
│   └── Kategori Drone ─────────── 4 Kategori (Agrikultur, Pemetaan & GIS, Kargo, Reboisasi)
│
├── 🎨 Logo & Navbar
│   ├── Pengaturan Brand ───────── Upload Logo, Atur Tinggi Logo, Brand Text, Favicon
│   ├── Tombol CTA Navbar ──────── Teks & URL Tombol CTA Kanan Atas Navbar
│   ├── Mega Menu Dropdown Produk  Judul Box Kanan, 3 Kartu Highlight (FDS STATION, TKDN, SNI), Link Komparasi
│   └── Mega Menu Dropdown Layanan Judul Box Kanan, 2 Kartu Highlight (Pilot Bersertifikat, Workshop Sleman), Link CTA
│
├── 🏠 Konten Beranda (Anti-Slop B2B Copy)
│   ├── Tab 1: Hero Carousel ───── Slider Banner Beranda (TKDN 60,74%, Headline Sleman, Deskripsi, Tombol CTA)
│   ├── Tab 2: Mitra & Lembaga ─── Judul Marquee Kemitraan (Institusi Nasional & Riset)
│   ├── Tab 3: Solusi Industri ─── 4 Kartu Sektor Kerja Strategis (Sawit/Padi, Tambang LiDAR, Inspeksi 150kV, SAR/Reboisasi)
│   ├── Tab 4: Bento Keunggulan ── 7 Kartu Keunggulan Manufaktur Lokal (Rangka Karbon, TKDN, GCS, SNI, Spare Parts 48 Jam, FDS Academy, Fleet Management)
│   ├── Tab 5: Lini Produk & Mutu ─ Header Katalog Drone & 4 Angka Statistik Mutu Nasional
│   ├── Tab 6: Layanan Enterprise ─ Repeater Layanan Lapangan (Otomatis tampil di Mega Menu Layanan)
│   ├── Tab 7: Newsroom / Blog ─── Judul & Ajakan Edukasi / Studi Kasus
│   └── Tab 8: Kontak & Form ───── Teks ajakan form penawaran harga & jadwal demo terbang
│
├── 🏢 Konten Tentang Kami
│   ├── Tab 1: Hero & Pengantar ── Advanced UAV Engineering, Pengalaman Sejak 2012
│   ├── Tab 2: Cerita & Milestone  Riset Aeromodelling Sleman hingga Berbadan Hukum PT
│   ├── Tab 3: Spektrum Teknologi  3 Arsitektur Wahana (Rotary Wing, Fixed Wing, Hybrid VTOL DELTAV)
│   ├── Tab 4: Aktivitas & Mitra ── Kolaborasi PRISMA Bappenas, Bank Indonesia, UGM-Swiss
│   ├── Tab 5: Sertifikasi Mutu ── TKDN 60,74%, ISO 9001:2015, SNI 9199:2023
│   └── Tab 6: Lokasi & Workshop ─ Kantor Pusat Depok Sleman, Kontak WhatsApp, Google Maps Embed
│
└── 📌 Kontak & Sosmed (Global Footer)
    ├── Informasi Perusahaan ───── Alamat Sleman, No. WhatsApp, Email Marketing
    ├── Lokasi & Google Maps ───── Embed iframe Google Maps & Toggle Tampil
    ├── Media Sosial ───────────── Link & Toggle Aktif Instagram, LinkedIn, YouTube, TikTok, X, WhatsApp
    └── Hak Cipta & Legal ──────── Teks Disclaimer Sertifikasi Kemenperin & Copyright
```

---

## 🚀 4. Strategi SEO & GEO (Warrior, Legend & Mythic Framework)

### A. Target Kata Kunci Utama (High-Intent B2B Keywords):
1. **Pemetaan & GIS:** `drone mapping`, `jasa pemetaan drone`, `jasa survey drone lidar`, `jasa drone foto udara`, `jasa pemetaan drone tambang`, `pemetaan GIS drone`, `fixed wing vtol indonesia`.
2. **Pertanian Presisi:** `drone pertanian`, `drone sprayer TKDN`, `drone pertanian indonesia`, `drone penyemprot pupuk`, `produsen drone pertanian`.
3. **Misi Khusus & Kemanusiaan:** `drone pencarian korban bencana`, `drone SAR termal`, `drone reboisasi seedball`, `drone kargo logistik`.
4. **Manufaktur & Brand Otoritas:** `produsen UAV Indonesia`, `PT Karya Solusi Angkasa`, `Full Drone Solutions`, `drone TKDN 60%`, `SNI 9199:2023`.

### B. Kebijakan Brand Kompetitor (Clean & Ethical SEO):
* **Aturan:** Judul SEO (`<title>`) dan deskripsi pencarian Google (`<meta name="description">`) **TIDAK menyebutkan nama brand kompetitor secara langsung**.
* **Fokus:** Mengedepankan kekuatan legalitas manufaktur dalam negeri (TKDN 60,74%, SNI 9199:2023, suku cadang ready-stock lokal di Sleman, garansi pabrik, GCS Bahasa Indonesia).

### C. Pemahaman Terhadap Skor Rank Math di WP Admin:
* Skor di WP-Admin (misal 6/100 atau 80/100) adalah **kalkulator pengetikan teks lokal di browser** yang mengecek apakah user mengetik artikel di kotak blog.
* Googlebot **TIDAK PERNAH** melihat angka skor di WP-Admin. Google menilai halaman dari **kode HTML rendered dan Schema JSON-LD di frontend**, yang di mana sistem FDS sudah menginjeksi 800–1200+ kata artikel teknis, tabel spesifikasi, dan microdata lengkap.

---

## 🛠️ 5. Alur Build & Deployment Lokal / Hosting
Jika melakukan perubahan kode pada template Blade, PHP, atau CSS:
```bash
# 1. Masuk ke direktori tema
cd "c:\Users\BILALIHSAN\Local Sites\fds\app\public\fds-theme"

# 2. Build aset frontend (Tailwind/Vite)
npm run build

# 3. Sinkronisasikan ke tema aktif WordPress
Copy-Item -Path "app\*" -Destination "..\wp-content\themes\fds-theme\app\" -Recurse -Force
Copy-Item -Path "public\build\*" -Destination "..\wp-content\themes\fds-theme\public\build\" -Recurse -Force
Copy-Item -Path "resources\views\*" -Destination "..\wp-content\themes\fds-theme\resources\views\" -Recurse -Force

# 4. Hapus cache Blade Acorn (jika tampilan tidak langsung berubah)
Remove-Item -Path "..\..\cache\acorn\framework\views\*" -Recurse -Force
```

---

## 🛑 6. Strict AI Behavioral & UI Architecture Rules (Anti-Slop)
1. **Dilarang "Container di dalam Container" (Ban Nested Container Slop):**
   - Logo, ikon, badge, teks tidak boleh dibungkus dalam container/kotak tambahan, pill bertingkat, atau frame bersarang di dalam kartu kecuali diminta user secara eksplisit.
   - Logo harus diletakkan transparan secara langsung di atas background kartu.
2. **Dilarang Memberi Titik/Hiasan di Samping Teks:**
   - Tidak boleh menambahkan pulsing dots, decorative bullets, atau emoji di samping teks badge/heading.
3. **Patuhi Referensi Wireframe Tanpa Halusinasi:**
   - Terapkan struktur grid/masonry sesuai referensi yang diberikan user secara presisi tanpa menambah elemen buatan sendiri yang tidak diperintahkan.
4. **Konsistensi Skala Tipografi:**
   - Semua judul section wajib seragam menggunakan `text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] leading-[1.1]`.
