# 🚀 INIT.md — PANDUAN INISIALISASI & SETUP LINGKUNGAN PENGEMBANGAN
## (Project Initialization, Environment Setup & Provisioning Guide)
**Website:** Full Drone Solutions (FDS) — PT Karya Solusi Angkasa (Website Company Profile B2B Enterprise)  
**Platform:** WordPress Custom Theme (Roots Sage 10 + Laravel Acorn Blade)  
**Versi:** 2.4 (Enterprise Production Edition)  
**Dokumen Terkait:** [`PRD.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PRD.md), [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md), [`AGENTS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/AGENTS.md)  
**Terakhir Diperbarui:** 2026-10-13  

---

## 📋 1. PRASYARAT SISTEM (PREREQUISITES)

Sebelum memulai inisialisasi tema, pastikan lingkungan pengembangan lokal atau server hosting Anda telah memenuhi spesifikasi berikut:

* **Sistem Operasi:** Windows 10/11, macOS, atau Ubuntu/Debian Linux
* **Web Server & Lingkungan Lokal:** Local by Flywheel (Sangat Direkomendasikan), XAMPP, Laragon, atau Nginx/Apache
* **Versi PHP:** PHP 8.1 atau PHP 8.2+ (Wajib ekstensi: `curl`, `mbstring`, `xml`, `zip`, `gd`/`imagick`)
* **Versi MySQL / MariaDB:** MySQL 5.7+ atau MySQL 8.0+ / MariaDB 10.4+
* **Node.js & Package Manager:** Node.js v18.x atau v20.x LTS + NPM v9+
* **Composer:** Composer v2.x (Dependency manager untuk ekosistem PHP & Roots Acorn)
* **WordPress Core:** WordPress 6.2 ke atas (Fresh Install atau Existing Site)

---

## 🛠️ 2. LANGKAH-LANGKAH INISIALISASI LENGKAP (STEP-BY-STEP SETUP)

### Langkah 1: Kloning / Penempatan Repositori
Tempatkan folder tema ini di dalam direktori instalasi WordPress Anda:
```bash
# Direktori pengembangan tema:
c:\Users\BILALIHSAN\Local Sites\fds\app\public\fds-theme

# Direktori tema aktif WordPress:
c:\Users\BILALIHSAN\Local Sites\fds\app\public\wp-content\themes\fds-theme
```

---

### Langkah 2: Instalasi Dependensi Backend (Composer)
Masuk ke folder tema dan jalankan Composer untuk memasang Roots Acorn dan dependensi PHP lainnya:
```bash
cd "c:\Users\BILALIHSAN\Local Sites\fds\app\public\fds-theme"
composer install --no-dev --optimize-autoloader
```
> [!NOTE]
> Laravel Acorn menyediakan container dependency injection dan Blade Templating Engine di atas WordPress.

---

### Langkah 3: Instalasi Dependensi Frontend (NPM)
Pasang seluruh paket frontend yang dibutuhkan (Tailwind CSS, Vite, PostCSS, Autoprefixer, Alpine.js):
```bash
npm install
```

---

### Langkah 4: Kompilasi Aset Frontend Produksi (Vite Build)
Kompilasikan seluruh style CSS Tailwind dan bundle JavaScript:
```bash
# Untuk kompilasi aset produksi (Minified & Optimized):
npm run build

# Atau untuk mode pengembangan real-time (Hot Reload):
npm run dev
```
Setelah build selesai, folder `public/build/` akan berisi `manifest.json`, file CSS yang di-minify, dan file JS bundle.

---

### Langkah 5: Sinkronisasi ke Folder Tema Aktif WordPress
Salin aset yang telah dikompilasi serta file kode terbaru ke direktori `wp-content/themes/fds-theme/`:
```powershell
# Jalankan di PowerShell:
Copy-Item -Path "app\*" -Destination "..\wp-content\themes\fds-theme\app\" -Recurse -Force
Copy-Item -Path "resources\*" -Destination "..\wp-content\themes\fds-theme\resources\" -Recurse -Force
Copy-Item -Path "public\build\*" -Destination "..\wp-content\themes\fds-theme\public\build\" -Recurse -Force
```

---

### Langkah 6: Aktivasi Tema & Auto-Provisioning Otomatis
1. Buka browser dan login ke **WP-Admin Dashboard** (`http://fds.local/wp-admin`).
2. Masuk ke menu **Tampilan (Appearance) &rarr; Tema (Themes)**.
3. Klik **Aktifkan (Activate)** pada tema **FDS Theme**.
4. **Auto-Provisioning Engine (`app/auto-setup.php`):**
   * Tema secara otomatis membuat halaman-halaman inti jika belum ada:
     - `/beranda/` (ditetapkan sebagai Halaman Depan Statis)
     - `/tentang-kami/`
     - `/bandingkan/` (alat komparasi drone)
     - `/blog/` (ditetapkan sebagai Halaman Pos Artikel)
   * Mengatur struktur Permalinks menjadi `/%postname%/` (Nama Tulisan).
   * Menyiapkan opsi default profil perusahaan (Alamat Sleman, WhatsApp, Nilai TKDN 60,74%).
   * Ditandai dengan flag `fds_theme_auto_setup_done_v1` sehingga aman dan tidak akan menimpa data di masa mendatang.

---

### Langkah 7: Inisialisasi Custom Post Type & Auto-Seeder 10 Unit Drone
1. Saat tema pertama kali aktif, file `app/cpt-drones.php` akan mendaftarkan Custom Post Type `drone` dan taksonomi 4 kategori resmi:
   - **Agrikultur**
   - **Pemetaan & GIS**
   - **Kargo**
   - **Reboisasi**
2. Sistem secara otomatis menyuntikkan data awal 10 model drone nasional lengkap dengan:
   - Spesifikasi teknis (Kapasitas, Durasi, Baterai, GCS FDS Station, TKDN, SNI)
   - 4 skenario pemanfaatan operasional (Use Cases)
   - Artikel komprehensif 800–1200+ kata untuk visibilitas SEO & Rank Math.
3. Ditandai dengan flag `fds_drones_seeded_final_v1` sehingga seeder tidak akan pernah duplikat.

---

## ⚙️ 3. KONFIGURASI SERVER & WORDPRESS OPTIMAL

Untuk kinerja optimal, pastikan parameter berikut aktif di konfigurasi `php.ini` atau `wp-config.php`:

```php
// Tambahkan di wp-config.php jika diperlukan:
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
define('WP_POST_REVISIONS', 5);
```

### Konfigurasi Permalinks (Wajib):
Masuk ke **WP-Admin &rarr; Pengaturan &rarr; Permalinks**, pilih **Nama Tulisan (Post Name)** (`/%postname%/`), lalu klik **Simpan Perubahan**.

---

## ✅ 4. CHECKLIST VERIFIKASI PASCA INISIALISASI (VERIFICATION CHECKLIST)

Gunakan checklist ini untuk memastikan seluruh sistem berjalan normal:

- [ ] **Navbar & Mega Menu:** Buka Beranda (`/`). Arahkan kursor ke menu "Produk Drone" & "Layanan". Unified Mega Menu drawer terbuka mulus dengan kartu highlight.
- [ ] **Hero Carousel:** Slider latar belakang layar penuh berganti secara mulus dengan tombol navigasi aktif.
- [ ] **Katalog Drone:** Tab filter kategori di beranda berfungsi menyaring unit drone tanpa reload halaman.
- [ ] **Detail Drone (`/drone/[slug]/`):** Tabel spesifikasi teknis tampil rapi, badge TKDN & SNI muncul, dan form download brosur aktif.
- [ ] **Gated Download Brosur:** Masukkan alamat email pengetesan di form brosur. Form bertransisi menjadi tombol "Download Brosur PDF" aktif berwarna biru `#0066cc`.
- [ ] **Halaman Bandingkan (`/bandingkan/`):** Pilih 2 drone untuk dikomparasikan. Tabel perbandingan spesifikasi berdampingan tampil akurat.
- [ ] **Halaman Tentang Kami (`/tentang-kami/`):** Hero sejarah 2012, 3 spektrum teknologi wahana, dan Google Maps Sleman ter-render.
- [ ] **Footer Global:** Kolom 4 (Dukungan) memiliki deretan logo partner di pojok kanan bawah yang transparan tanpa kotak putih.
- [ ] **Rank Math SEO:** Buka salah satu halaman di WP-Admin. Panel Rank Math siap digunakan untuk custom Title, Description, dan Focus Keywords.

---

## 🧹 5. PEMBERSIHAN CACHE & TROUBLESHOOTING AWAL

Jika terjadi kendala di mana tampilan tidak langsung terbarui setelah inisialisasi:
```powershell
# Bersihkan cache view Blade Acorn:
Remove-Item "..\wp-content\cache\acorn\framework\views\*" -Recurse -Force

# Re-build aset:
npm run build
```

---

*Inisialisasi selesai. Website siap dikembangkan dan dikelola lebih lanjut.*
