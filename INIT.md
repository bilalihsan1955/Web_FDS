# 🚀 INIT.md — PANDUAN INISIALISASI & SETUP LINGKUNGAN PENGEMBANGAN
## (Project Initialization, Environment Setup & Provisioning Guide)
**Website:** Full Drone Solutions (FDS) — PT Karya Solusi Angkasa (Website Company Profile & Lead Engine B2B Enterprise)  
**Platform:** WordPress Custom Theme (Roots Sage 10 + Laravel Acorn Blade + Tailwind CSS + Vite)  
**Versi:** 2.5 (Enterprise Production Edition)  
**Dokumen Terkait:** [`PRD.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PRD.md), [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md), [`AGENTS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/AGENTS.md), [`PROJECT_MEMORY.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PROJECT_MEMORY.md)  
**Terakhir Diperbarui:** 2026-11-11  

---

## 📋 1. PRASYARAT SISTEM (PREREQUISITES)

Sebelum memulai inisialisasi tema, pastikan lingkungan pengembangan lokal atau server hosting memenuhi spesifikasi berikut:

* **Sistem Operasi:** Windows 10/11, macOS, atau Ubuntu/Debian Linux
* **Web Server & Lingkungan Lokal:** Local by Flywheel (Sangat Direkomendasikan), Laragon, XAMPP, atau Nginx/Apache/LiteSpeed
* **Versi PHP:** PHP 8.1 atau PHP 8.2+ (ekstensi wajib: `curl`, `mbstring`, `xml`, `zip`, `gd`/`imagick`)
* **Database:** MySQL 5.7+ / MySQL 8.0+ / MariaDB 10.4+
* **Node.js & NPM:** Node.js v18.x / v20.x LTS + NPM v9+
* **Composer:** Composer v2.x (dependency manager untuk Roots Acorn & PHP autoloader)
* **WordPress Core:** WordPress 6.2 ke atas

---

## 🛠️ 2. LANGKAH-LANGKAH INISIALISASI LENGKAP (STEP-BY-STEP SETUP)

### Langkah 1: Penempatan Source vs Mirror Tema Aktif
```powershell
# Direktori source pengembangan (Git working tree):
c:\Users\BILALIHSAN\Local Sites\fds\app\public\fds-theme

# Direktori tema aktif WordPress (mirror produksi lokal):
c:\Users\BILALIHSAN\Local Sites\fds\app\public\wp-content\themes\fds-theme
```

### Langkah 2: Instalasi Dependensi Backend (Composer)
```powershell
cd "c:\Users\BILALIHSAN\Local Sites\fds\app\public\fds-theme"
composer install --no-dev --optimize-autoloader
```
> [!NOTE]
> Laravel Acorn menyediakan Blade Templating Engine dan dependency container di atas WordPress.

### Langkah 3: Instalasi Dependensi Frontend (NPM)
```powershell
npm install
```

### Langkah 4: Kompilasi Aset Frontend Produksi (Vite Build)
```powershell
# Kompilasi aset produksi (minified & manifest):
npm run build

# Atau mode development HMR:
npm run dev
```

### Langkah 5: Sinkronisasi Aman ke Tema Aktif WordPress
```powershell
# Hapus aset build hash lama di mirror agar tidak menumpuk
if (Test-Path "..\wp-content\themes\fds-theme\public\build") {
    Remove-Item "..\wp-content\themes\fds-theme\public\build\*" -Recurse -Force -ErrorAction SilentlyContinue
}

# Salin file inti, konfigurasi keamanan .htaccess, kode PHP, views Blade, dan hasil build
Copy-Item -Path "functions.php",".htaccess","AGENTS.md","GEMINI.md","PRD.md","PROJECT_MEMORY.md","COMPRO_GUIDE.md","INIT.md","CHANGELOG.md" -Destination "..\wp-content\themes\fds-theme\" -Force
Copy-Item -Path "app\*" -Destination "..\wp-content\themes\fds-theme\app\" -Recurse -Force
Copy-Item -Path "resources\*" -Destination "..\wp-content\themes\fds-theme\resources\" -Recurse -Force
Copy-Item -Path "public\build\*" -Destination "..\wp-content\themes\fds-theme\public\build\" -Recurse -Force
```
> [!IMPORTANT]
> Pastikan folder `.agents/` dan `scratch/` TIDAK ikut disalin ke direktori publik tema aktif WordPress (`wp-content/themes/fds-theme/`).

### Langkah 6: Aktivasi Tema & Auto-Provisioning Non-Destruktif
1. Login ke **WP-Admin Dashboard** (`http://fds.local/wp-admin`).
2. Masuk ke **Tampilan (Appearance) &rarr; Tema (Themes)** dan aktifkan **FDS Theme**.
3. Sistem auto-provisioning berjalan **secara non-destruktif** lewat tiga penggerak utama:
   * [`app/auto-setup.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/auto-setup.php) (`fds_theme_auto_setup_done_v1`): membuat halaman inti (`/beranda/`, `/tentang-kami/`, `/bandingkan/`, `/kontak/`, `/blog/`), menyetel permalinks `/%postname%/`, mengisi slider & kontak awal bila belum ada, dan memastikan folder cache Acorn siap ditulis.
   * [`app/layanan-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/layanan-settings.php) (`fds_layanan_pages_provisioned_v1`): memprovision Hub `/layanan/`, 6 halaman sektor (`/layanan/{sektor}/`), 3 halaman kategori layanan (`/layanan/{kategori}/`), serta halaman konfirmasi `/terima-kasih/`.
   * [`app/migrations-2026.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/migrations-2026.php): memvalidasi halaman `/produk/` & `/privacy-policy/`, menormalisasi identitas tanpa menimpa edit konten admin atau menghapus post/halaman lama, serta menyinkronkan panduan teknis dan FAQ 9 model drone aktif.

### Langkah 7: Inisialisasi Custom Post Type & Katalog 9 Unit Drone Aktif
1. [`app/cpt-drones.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/cpt-drones.php) mendaftarkan CPT `drone` (`has_archive => false`) serta **3 Kategori Resmi**:
   * **Agrikultur** (`FERTO 5`, `FERTO 10`, `FERTO 15` kapasitas `17 Liter`, `FERTO 22`, `FERTO 30`, `FERTO 50`)
   * **Pemetaan & GIS** (`DELTAV`, `MULTIPURPOSE`)
   * **Kargo** (`DELFRO`)
2. Auto-seeder (`fds_drones_seeded_final_v1`) hanya berjalan pada instalasi bersih, menyiapkan metadata teknis (termasuk kontrol **TKDN 60,74%** khusus FERTO & **SNI 9199:2023** khusus FERTO 15 & FERTO 22), 4 use cases, serta panduan teknis SSR 250–400 kata dan FAQ `<details>`.

---

## ⚙️ 3. KONFIGURASI SERVER, KEAMANAN & WORDPRESS OPTIMAL

Pastikan parameter berikut aktif pada `wp-config.php` bila diperlukan:
```php
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
define('WP_POST_REVISIONS', 5);
```

* **Permalinks Wajib:** **WP-Admin &rarr; Pengaturan &rarr; Permalinks** &rarr; pilih **Nama Tulisan (`/%postname%/`)** dan simpan.
* **Perlindungan `.htaccess`:** [`fds-theme/.htaccess`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/.htaccess) dan [`resources/views/.htaccess`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/resources/views/.htaccess) memblokir akses langsung ke folder `.agents`, `scratch`, `.env`, `.sql`, `.log`, `.md`, dan template `.blade.php`.
* **Proteksi Staging:** [`app/security.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/security.php) menyuntikkan header `X-Robots-Tag: noindex, nofollow` secara otomatis bila host adalah `compro.*`, `staging.*`, atau `*.local`.

---

## ✅ 4. CHECKLIST VERIFIKASI PASCA INISIALISASI

- [ ] **Navbar & Mega Menu:** Buka `/` &rarr; hover "Produk" (tampil 3 kategori aktif + highlight FDS Station GCS / TKDN seri FERTO / SNI FERTO 15 & 22) dan "Layanan" (`/layanan/` + 6 sektor + 3 kategori).
- [ ] **Beranda (`/`):** Hero carousel tampil dengan gradien gelap teal `#0f808a`, 6 kartu Solusi berpanah diagonal `↗`, Katalog `#produk` mempertahankan list horizontal `.drone-row`, dan seluruh `<section>` gelap memiliki gelombang SVG organik tanpa stroke.
- [ ] **Katalog Hub (`/produk/`) & Kategori (`/kategori-drone/*`):** Menampilkan grid kartu `<x-drone-card>` penuh *object-cover* tinggi seragam, link stretched, dan tombol Komparasi.
- [ ] **Detail Drone (`/drone/[slug]/`):** Foto spesifikasi melayang rapi (`z-30`) tanpa terpotong `#unduh-brosur`, form brosur 2-state bertransisi ke tombol PDF Teal `#0f808a`, serta Panduan Teknis & FAQ `<details>` chevron `⌄` ter-render langsung di HTML awal.
- [ ] **Halaman Bandingkan (`/bandingkan/`):** Komparasi lintas unit berfungsi akurat tanpa menampilkan data TKDN/SNI di luar ketentuan model.
- [ ] **Funnel Layanan (`/layanan/`, Sektor, Kategori):** Seluruh CTA mengarah ke `/kontak/?layanan=...#hubungi-form`, FAQ chevron `⌄` seragam, dan sticky WhatsApp bar tampil di mobile.
- [ ] **Kontak & Terima Kasih (`/kontak/` &rarr; `/terima-kasih/`):** Submit form inquiry menyimpan entri ke **Pesan Masuk**, memicu event GA4, dan redirect ke `/terima-kasih/` (`noindex`).
- [ ] **Tentang Kami (`/tentang-kami/`):** Hero perusahaan berbadan hukum PT sejak `2019`, 3 spektrum teknologi, aktivitas kemitraan, sertifikasi, dan peta Sleman ter-render bersih.
- [ ] **Sanitasi Text, SEO & `/llms.txt`:** Nol kemunculan `&amp;` / `&#038;` literal di HTML, blog, hasil pencarian, breadcrumb bebas `/` awalan, serta `/llms.txt` merender HTTP 200 dalam format Markdown bersih.
- [ ] **Footer Global:** Logo sertifikasi/partner kolom 4 terkunci pojok kanan bawah mengalir ke kiri dengan transparansi `mix-blend-mode: multiply`.

---

## 🧹 5. PEMBERSIHAN CACHE & TROUBLESHOOTING

```powershell
# 1. Bersihkan cache Blade Acorn
Remove-Item "..\wp-content\cache\acorn\framework\views\*" -Recurse -Force -ErrorAction SilentlyContinue

# 2. Rebuild aset Vite dan sinkronkan mirror bila perlu
npm run build
```
*(Bila `/llms.txt` perlu disegarkan manual setelah mengubah data CPT drone, buka `http://fds.local/?refresh_llms=1`)*

---

*Inisialisasi selesai. Website siap dikembangkan dan dikelola sesuai standar produksi B2B PT Karya Solusi Angkasa.*
