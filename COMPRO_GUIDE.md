# 📘 COMPRO_GUIDE.md — PANDUAN LENGKAP PENGELOLAAN WEBSITE COMPANY PROFILE FDS
## (Operational Runbook & Content Management Guide for PT Karya Solusi Angkasa)
**Tipe Platform:** Website Company Profile (Compro) B2B Enterprise  
**Teknologi:** WordPress Custom Theme (Roots Sage 10 + Laravel Acorn Blade)  
**Versi:** 2.4 (Enterprise Production Edition)  
**Status:** Dokumen Resmi Pengelolaan Konten Website Compro  
**Terakhir Diperbarui:** 2026-10-13  

---

## 📌 1. TUJUAN & RUANG LINGKUP DOKUMEN

Dokumen ini adalah **Panduan Operasional & Pengelolaan Konten (Manual Book / Runbook)** khusus untuk Website Company Profile resmi **PT Karya Solusi Angkasa (Full Drone Solutions / FDS)**.

### Perbedaan Dokumen Ini dengan `PRD.md`:
* **`PRD.md` (Product Requirements Document):** Menjelaskan **APA & MENGAPA** (*What & Why*). Berisi spesifikasi kebutuhan sistem, analisis pasar B2B, target audiens (BUMN/pemerintah/perkebunan), standar sertifikasi TKDN 60,74%, dan kriteria penerimaan desain web compro.
* **`COMPRO_GUIDE.md` (Dokumen Ini):** Menjelaskan **BAGAIMANA CARA MENGELOLA** (*How to Manage*). Berisi panduan langkah demi langkah bagi tim pengelola/admin web FDS untuk mengubah konten teks, gambar hero, katalog drone, form brosur, dan footer di WP-Admin, serta pemetaan teknis file kode Blade yang menampilkannya.

---

## 🗺️ 2. MATRIKS PENGELOLAAN KONTEN WEBSITE COMPRO

Tabel ini memetakan setiap bagian tampilan di website company profile dengan lokasi pengaturannya di WP-Admin dan file template pengolahnya:

| Bagian Halaman Compro | Menu Pengaturan di WP-Admin | Key Database (`wp_options` / Post Meta) | File Backend Pengolah | File Tampilan Frontend (Blade) |
| :--- | :--- | :--- | :--- | :--- |
| **Brand, Logo & Favicon** | Pengaturan FDS &rarr; Logo & Navbar | `fds_site_logo`, `fds_navbar_brand_name`, `fds_site_favicon` | `app/theme-settings.php` | `resources/views/layouts/app.blade.php` |
| **Unified Mega Menu** | Pengaturan FDS &rarr; Logo & Navbar | `fds_menu_products`, `fds_menu_services` | `app/theme-settings.php` | `resources/views/layouts/app.blade.php` |
| **Hero Carousel Beranda** | Pengaturan FDS &rarr; Konten Beranda | `fds_hero_slides` (JSON Array) | `app/homepage-content-settings.php` | `resources/views/front-page.blade.php` |
| **Slider Mitra Strategis** | Pengaturan FDS &rarr; Logo & Navbar | `fds_partners_logos` | `app/theme-settings.php` | `resources/views/front-page.blade.php` |
| **Solusi 4 Sektor Industri** | Pengaturan FDS &rarr; Konten Beranda | `fds_industry_sectors` | `app/homepage-content-settings.php` | `resources/views/front-page.blade.php` |
| **Bento 7 Keunggulan** | Pengaturan FDS &rarr; Konten Beranda | `fds_bento_features` | `app/homepage-content-settings.php` | `resources/views/front-page.blade.php` |
| **Katalog 10 Model Drone** | Menu Drones (CPT) di Sidebar | Custom Post Type `drone` + `_fds_drone_specs` | `app/cpt-drones.php` | `resources/views/front-page.blade.php` & `single-drone.blade.php` |
| **Download Brosur Leads** | Pengaturan FDS &rarr; Leads Brosur | `fds_brochure_leads` | `app/brochure-settings.php` | `resources/views/single-drone.blade.php` |
| **Alat Komparasi Drone** | Halaman Statis `/bandingkan/` | Mengambil data live CPT `drone` via REST API | `app/cpt-drones.php` | `resources/views/page-bandingkan.blade.php` |
| **Profil & Sejarah 2012** | Pengaturan FDS &rarr; Tentang Kami | `fds_about_history`, `fds_about_vision`, `fds_about_spectrum` | `app/about-content-settings.php` | `resources/views/page-tentang-kami.blade.php` |
| **Footer & Kontak Kantor** | Pengaturan FDS &rarr; Pengaturan Footer | `fds_footer_address`, `fds_footer_phone`, `fds_footer_email`, `fds_social_links` | `app/footer-settings.php` | `resources/views/layouts/app.blade.php` |
| **Logo Partner Footer** | Pengaturan FDS &rarr; Logo & Navbar | `fds_footer_cert_logos`, `fds_footer_logo_height` | `app/theme-settings.php` | `resources/views/layouts/app.blade.php` |

---

## 📖 3. PANDUAN PENGELOLAAN SETIAP MODUL WEBSITE COMPRO

### 3.1 Header & Unified Mega Menu (Navigasi Compro)
1. **Logo Perusahaan:**
   * Masuk ke **Pengaturan FDS &rarr; Logo & Navbar**.
   * Upload logo FDS berformat PNG transparan atau SVG.
   * Atur tinggi logo (default: `32px` untuk mobile, `40px` untuk desktop).
2. **Unified Mega Menu (Produk Drone & Layanan):**
   * Tab Kategori: Mengelompokkan drone menjadi Agrikultur, Pemetaan, Kargo, dan Reboisasi.
   * Kartu Highlight Otoritas: Menampilkan FDS STATION GCS, Sertifikasi TKDN 60,74%, dan SNI 9199:2023.
   * Tautan Langsung: Mengarahkan calon klien ke katalog spesifik atau alat komparasi (`/bandingkan/`).

### 3.2 Halaman Depan Compro (`/`)
1. **Hero Banner Sinematik:**
   * Di **Pengaturan FDS &rarr; Konten Beranda**, admin dapat menambah/mengedit slide latar belakang.
   * Setiap slide memuat: Gambar latar drone beresolusi tinggi, judul H1 persuasif, paragraf deskripsi, dan dua tombol CTA (Konsultasi & Jelajahi Unit).
2. **Sektor Industri & Bento Keunggulan:**
   * Menampilkan pembuktian teknologi FDS di sektor Perkebunan Sawit, Pertambangan Mineral, Inspeksi Listrik PLN, dan Reboisasi BNPB/Kehutanan.
   * Bento 7 pilar keunggulan FDS (Rangka Karbon Asli Indonesia, Layanan Servis 48 Jam, Pusat Pelatihan Pilot FDS Academy).

### 3.3 Katalog Unit Drone (Custom Post Type `drone`)
1. **Menambah / Mengedit Unit Drone:**
   * Buka menu **Drones &rarr; Tambah Baru** di sidebar WP-Admin.
   * Isi Judul Model Drone (contoh: *FERTO 22 - Heavy Lift Agricultural Drone*).
   * Pilih Kategori di panel kanan (Agrikultur, Pemetaan & GIS, Kargo, atau Reboisasi).
2. **Mengisi Spesifikasi Teknis di Metabox FDS:**
   * *Kapasitas Muatan (Payload):* e.g., `22 Liter` atau `10 kg`.
   * *Durasi Terbang (Flight Time):* e.g., `20-25 Menit`.
   * *Kapasitas Baterai:* e.g., `22.000 mAh LiPo Smart Battery`.
   * *Produktivitas Kerja:* e.g., `8,5 Hektar/Jam`.
   * *Ground Control Station:* `FDS STATION (Bahasa Indonesia)`.
   * *Status Sertifikasi:* `TKDN 60,74%` & `SNI 9199:2023`.
   * *Upload File Brosur PDF:* Masukkan tautan/upload file brosur PDF resmi untuk diunduh pengunjung.
3. **Skenario Operasional (Use Cases):**
   * Masukkan 4 skenario pemanfaatan riil unit di lapangan untuk meyakinkan calon pembeli korporat.

### 3.4 Sistem Gated Download Brosur & Manajemen Leads
* **Alur Pengunjung:** Calon pembeli memasukkan alamat email instansi/perusahaan &rarr; Sistem memvalidasi via AJAX &rarr; Tombol berubah menjadi "Download Brosur PDF" aktif berwarna `#0066cc`.
* **Melihat & Mengekspor Data Leads:**
  1. Masuk ke **Pengaturan FDS &rarr; Leads Brosur**.
  2. Tabel menampilkan: Tanggal, Nama Drone yang Diminati, Alamat Email Pengunjung, dan Alamat IP.
  3. Klik tombol **"Ekspor Data ke CSV"** untuk mengunduh rekap calon pembeli dalam format Excel/Spreadsheet.

### 3.5 Halaman Tentang Kami (`/tentang-kami/`)
* **Pengaturan Konten:** Di **Pengaturan FDS &rarr; Tentang Kami**.
* **Elemen:** Sejarah riset mandiri sejak 2012, peresmian PT pada 2019, visi kemandirian teknologi dirgantara nasional, fasilitas riset Sleman, dan peta workshop Google Maps.

### 3.6 Footer Global & Penataan Logo Sertifikasi
* **Informasi Kontak:** Di **Pengaturan FDS &rarr; Pengaturan Footer** (Alamat kantor Sleman, nomor WhatsApp sales, email inquiry, jam operasional).
* **Posisi Logo Sertifikasi / Partner:**
  * Terkunci di kolom ke-4 (Dukungan) pada pojok kanan bawah, mepet batas padding kanan.
  * Menggunakan `mix-blend-mode: multiply` agar transparan 100% tanpa kotak putih di atas latar belakang abu-abu terang `#f5f5f7`.

---

## 🔍 4. PANDUAN SEO COMPRO & INTEGRASI RANK MATH

1. **Dual-Engine Architecture:**
   * Jika plugin **Rank Math SEO** aktif, sistem tema otomatis mengalah 100% ke Rank Math agar tidak terjadi duplikasi tag metadata di kode HTML.
2. **Optimalisasi Konten Compro di WP-Admin:**
   * Pada setiap halaman atau pos drone, gunakan panel Rank Math di sisi kanan layar untuk mengatur:
     - *Title Tag* (Contoh: `Jual Drone Pertanian TKDN 60,74% - FDS FERTO 22`)
     - *Meta Description* (Ringkasan menarik 140–160 karakter untuk hasil pencarian Google)
     - *Focus Keyword* (Kata kunci target utama)
3. **Structured Data JSON-LD:**
   * Tema otomatis menghasilkan skema schema.org: `Organization`, `LocalBusiness`, `FAQPage`, dan `Product` agar Google mengenali FDS sebagai entitas bisnis manufaktur resmi.
4. **Kesiapan Mesin Pencari AI (GEO):**
   * Website menyediakan berkas ringkasan `/llms.txt` agar asisten AI seperti ChatGPT, Perplexity, dan Claude dapat menyajikan profil FDS secara akurat saat menjawab pertanyaan pengguna.

---

## 🛠️ 5. SOP PEMELIHARAAN & BUILD CODE

Setiap kali tim teknis melakukan pembaruan pada template Blade atau style Tailwind:
```powershell
# 1. Masuk ke folder tema
cd "c:\Users\BILALIHSAN\Local Sites\fds\app\public\fds-theme"

# 2. Kompilasi aset frontend
npm run build

# 3. Sinkronisasi ke tema aktif WordPress
Copy-Item -Path "app\*" -Destination "..\wp-content\themes\fds-theme\app\" -Recurse -Force
Copy-Item -Path "resources\*" -Destination "..\wp-content\themes\fds-theme\resources\" -Recurse -Force
Copy-Item -Path "public\build\*" -Destination "..\wp-content\themes\fds-theme\public\build\" -Recurse -Force

# 4. Hapus cache view Blade Acorn
Remove-Item "..\wp-content\cache\acorn\framework\views\*" -Recurse -Force -ErrorAction SilentlyContinue
```

---

*Dokumen ini merupakan panduan resmi pengelolaan konten website company profile PT Karya Solusi Angkasa (Full Drone Solutions).*
