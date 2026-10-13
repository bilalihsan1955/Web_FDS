# 📑 PRD.md — PRODUCT REQUIREMENTS DOCUMENT
## Redesain & Pengembangan Website Company Profile (Compro) Enterprise Full Drone Solutions (FDS)

**STATUS: FINAL / PRODUCTION (VERSI MULTI-HALAMAN COMPRO B2B ENTERPRISE)**  
**Versi Dokumen:** v2.4 (Enterprise Production Edition)  
**Tipe Platform:** Website Company Profile (Compro) B2B Enterprise  
**Entitas:** PT Karya Solusi Angkasa (Full Drone Solutions)  
**Disusun oleh:** Bilal Al Ihsan (Pengembang Utama & UI/UX Specialist)  
**Untuk:** Tim Manajemen & Stakeholder PT Karya Solusi Angkasa  
**Tanggal Rilis:** 2026-10-13  
**Dokumen Terkait:** [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md), [`PROJECT_MEMORY.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/PROJECT_MEMORY.md), [`AGENTS.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/AGENTS.md), [`INIT.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/INIT.md)  

---

# 1. Ringkasan Eksekutif (Executive Summary)

Website **Full Drone Solutions (FDS)** — berbadan hukum resmi **PT Karya Solusi Angkasa** yang berlokasi di Sleman, D.I. Yogyakarta — merupakan representasi digital manufaktur Pesawat Udara Nirawak (UAV) terkemuka di Indonesia.

Website ini dirancang dan dibangun sebagai **Website Company Profile (Compro) B2B Enterprise Modern Berstandar Industri Tinggi**. Platform ini dirancang untuk:
1. Menampilkan keunggulan legalitas resmi nasional: **TKDN + BMP mencapai 60,74%** (Kementerian Perindustrian RI) dan standar **SNI 9199:2023** untuk kebutuhan pengadaan barang pemerintah, BUMN, dan e-Katalog LKPP.
2. Menyajikan etalase katalog 10 model drone nasional yang mencakup 4 sektor utama: Agrikultur, Pemetaan & GIS, Kargo Logistik, dan Reboisasi Khusus.
3. Mengonversi pengunjung korporat menjadi prospek bisnis (*leads*) melalui **Sistem Gated Download Brosur PDF** dan form penawaran interaktif.
4. Memberikan pengalaman navigasi kelas dunia menggunakan arsitektur **Clean Apple-Grade B2B Minimalist** di atas Roots Sage 10 + Laravel Acorn Blade tanpa beban *page-builder* yang lambat.

---

# 2. Tujuan & Sasaran Bisnis (Business Goals & Metrics)

* **Otoritas Manufaktur Nasional:** Mengukuhkan posisi FDS sebagai produsen UAV mandiri asli Indonesia dengan fasilitas riset dan perakitan langsung di Sleman, suku cadang *ready-stock* 48 jam, dan GCS berbahasa Indonesia (**FDS STATION**).
* **Konversi B2B & Lead Generation:** Menyediakan alur *gated download* spesifikasi teknis untuk menangkap data calon pembeli (email instansi/korporasi) secara otomatis.
* **Kecepatan & Performa Tinggi:** Memastikan kecepatan render halaman di bawah 1,2 detik dengan pemangkasan aset CSS/JS menggunakan Vite dan Tailwind CSS.
* **Visibilitas Search Engine & AI Search:** Mencapai posisi puncak pencarian Google untuk kata kunci B2B berintensitas tinggi (*drone mapping, drone pertanian TKDN, produsen UAV Indonesia*) serta keterbacaan oleh bot AI (ChatGPT, Claude, Perplexity) melalui Schema JSON-LD dan `/llms.txt`.

---

# 3. Profil Pengguna & Peran (User Personas & Roles)

1. **Pejabat Pengadaan / BUMN / Lembaga Negara:** Membutuhkan kepastian sertifikasi TKDN 60,74%, SNI, legalitas DKPPU Kemenhub, dan dokumen spesifikasi resmi untuk proses lelang/tender LKPP.
2. **Manajer Operasional Perkebunan, Tambang, & Kehutanan:** Membutuhkan data teknis riil: kapasitas angkut (*payload*), daya tahan baterai (*flight endurance*), produktivitas hektar per jam, dan kemudahan servis lokal.
3. **Pilot UAV & Akademisi Riset:** Memerhatikan integrasi software FDS STATION GCS, lisensi FDS Academy, dan kolaborasi riset.
4. **Admin Konten & Tim Marketing FDS:** Mengelola harga, katalog drone, hero banner beranda, halaman profil, mengunduh rekap email leads dari form brosur, serta menyinkronkan SEO Rank Math.

---

# 4. Cakupan Sistem & Fitur Website Compro (Core Features)

## 4.1 Navigasi Global & Unified Mega Menu (NAV)
* **NAV-1 (Apple-Style Blur Header):** Header sticky dengan efek kaca blur (*backdrop-blur-md*), logo dinamis, dan tombol CTA kanan atas.
* **NAV-2 (Mega Menu Produk):** Menu dropdown interaktif yang mengorganisir katalog berdasarkan 4 tab kategori (`Agrikultur`, `Pemetaan & GIS`, `Kargo`, `Reboisasi`) serta 3 kartu highlight otoritas (FDS STATION, TKDN 60,74%, SNI 9199:2023) dan tombol komparasi drone.
* **NAV-3 (Mega Menu Layanan):** Menu dropdown layanan industri (Survey LiDAR, Pemetaan GIS, Jasa Semprot Hama, FDS Academy) dengan highlight workshop Sleman.

## 4.2 Halaman Beranda Compro (HOM)
* **HOM-1 (Full-Bleed Cinematic Hero):** Slider latar belakang layar penuh dengan transisi elegan, badge *"Teknologi UAV Indonesia"*, H1 headline dinamis, deskripsi, dan tombol CTA ganda.
* **HOM-2 (Marquee Mitra Strategis):** Slider logo bergerak mulus yang menampilkan mitra kolaborasi (Bappenas, Pemerintah Australia PRISMA, Bank Indonesia, UGM, Swiss).
* **HOM-3 (Solusi Sektor Industri):** 4 kartu interaktif sektor kerja: Perkebunan Sawit/Padi, Pertambangan Mineral LiDAR, Inspeksi Energi 150kV, Misi Tanggap Darurat & Reboisasi.
* **HOM-4 (Bento 7 Keunggulan Manufaktur):** Struktur grid asimetris yang menampilkan 7 pilar keunggulan FDS (Rangka Karbon, TKDN 60,74%, GCS Bahasa Indonesia, SNI 9199, Spare Parts 48 Jam, FDS Academy, Fleet Telemetri).
* **HOM-5 (Katalog Filterable Drone):** Tab filter dinamis tanpa reload halaman untuk menelusuri unit drone unggulan.
* **HOM-6 (Newsroom / Blog Terkini):** Menampilkan artikel berita, rilis teknologi, dan studi kasus terbaru.
* **HOM-7 (Formulir Penawaran & Konsultasi):** Form pemesanan unit dan permohonan demo terbang.

## 4.3 Katalog & Halaman Detail Drone (CPT `drone`)
* **DRN-1 (Custom Post Type & 4 Kategori):** Struktur data dinamis untuk 10 model drone nasional.
* **DRN-2 (Tabel Spesifikasi Teknis Interaktif):** Menampilkan data kapasitas muatan, durasi terbang, baterai, produktivitas per jam, GCS, TKDN, SNI, dan garansi secara rapi dan terstruktur.
* **DRN-3 (Skenario Operasional / Use Cases):** 4 skenario pemanfaatan riil per unit drone dengan penjelasan detail.
* **DRN-4 (Sistem Gated Download Brosur 2-State):**
  - State 1: Input email bisnis + tombol "Kirim Email" (akses PDF terkunci).
  - Validasi AJAX: Validasi email tanpa reload halaman dan rekam leads ke database.
  - State 2: Bertransisi otomatis menjadi tombol "Download Brosur PDF" aktif berwarna biru `#0066cc`.
  - Akses Langsung: Mengunduh file PDF brosur resmi drone tersebut.
* **DRN-5 (Auto-SEO Technical Content Engine):** Sistem otomatis menyuntikkan artikel komprehensif 800–1200+ kata berstandar Rank Math ke `post_content` saat drone disimpan jika artikel masih kosong.

## 4.4 Halaman Alat Komparasi Spesifikasi (`/bandingkan/`)
* **CMP-1 (Side-by-Side Comparison):** Memungkinkan pengunjung membandingkan 2 atau lebih model drone secara berdampingan.
* **CMP-2 (Parameter Matriks Lengkap):** Membandingkan muatan, durasi terbang, produktivitas, standar TKDN, dan skenario pemanfaatan.

## 4.5 Halaman Profil Perusahaan (`/tentang-kami/`)
* **ABT-1 (Hero Sejarah & Narasi 2012):** Perjalanan dari riset aeromodelling mandiri di Sleman hingga berdiri resmi sebagai PT Karya Solusi Angkasa.
* **ABT-2 (Visi & Misi Strategis):** Komitmen kedaulatan teknologi kedirgantaraan nirawak Indonesia.
* **ABT-3 (Spektrum 3 Arsitektur Wahana):** Rotary-Wing Multirotor, Fixed-Wing Long Endurance, dan Hybrid VTOL DELTAV.
* **ABT-4 (Fasilitas Riset & Manufaktur):** Dokumentasi workshop Sleman, mesin perakitan, dan tim insinyur lokal.
* **ABT-5 (Peta Workshop):** Embed Google Maps interaktif kantor pusat Sleman.

## 4.6 Global Footer & Kontak (FTR)
* **FTR-1 (Grid 4 Kolom Navigasi):** Produk Drone, Layanan, Perusahaan, dan Dukungan.
* **FTR-2 (Penempatan Logo Partner/Sertifikasi):** Terkunci di pojok kanan bawah kolom 4 (Dukungan) mepet batas padding kanan, mengalir rapi ke kiri.
* **FTR-3 (Transparansi Logo Multiply):** Efek transparansi mutlak tanpa kotak putih menggunakan `mix-blend-mode: multiply !important;`.
* **FTR-4 (Baris Tengah):** Informasi resmi PT di sisi kiri (`col-span-8`) dan tombol sosial media resmi di sisi kanan (`col-span-4 justify-end`).
* **FTR-5 (Bottom Bar):** Copyright & Legal Notice sertifikasi Kemenperin.

---

# 5. Kebutuhan Non-Fungsional (Non-Functional Requirements)

* **Keamanan & Validasi Input:** Seluruh form AJAX (Brosur, Kontak) diverifikasi menggunakan WordPress Nonces (`check_ajax_referer`), sanitasi email (`sanitize_email`), dan sanitasi teks (`sanitize_text_field`).
* **Arsitektur Tanpa Page-Builder:** Zero dependency pada Elementor, WPBakery, atau Divi. Kode bersih murni Laravel Blade + Tailwind CSS.
* **Performa Cache Blade:** Pemanfaatan caching view terkompilasi oleh Laravel Acorn untuk memangkas *database overhead*.
* **Aksesibilitas & Responsive:** Mendukung 100% responsivitas di Mobile (< 640px), Tablet (768px - 1024px), dan Layar Lebar Desktop (1280px - 1920px).
* **Anti-Destructive Data Rule:** Pembaruan tema tidak boleh menimpa data yang telah dikonfigurasi admin di WP Admin.

---

# 6. Integrasi SEO, Rank Math, & Generative Engine Optimization (GEO)

* **Dual-Engine SEO Architecture:** Tema mendukung fallback mandiri dan otomatis mengalah 100% jika plugin Rank Math SEO aktif, mencegah duplikasi metadata.
* **Rank Math Full Field Support:** Mendukung Title, Description, Focus Keywords, dan Social Share Image secara *native*.
* **Schema.org JSON-LD:** Structured data otomatis untuk `Organization`, `LocalBusiness`, `WebSite`, `Breadcrumbs`, `Service`, `FAQPage`, dan `Product`.
* **Dukungan AI Crawler:** Konfigurasi `robots.txt` khusus bot AI resmi serta penyediaan ringkasan knowledge base bersih di `/llms.txt`.

---

# 7. Penerimaan & Kriteria Keberhasilan (Acceptance Criteria)

1. Semua halaman inti (`/`, `/tentang-kami/`, `/bandingkan/`, `/blog/`, `/drone/*`) dapat diakses tanpa broken layout atau error 500.
2. Form unduh brosur berhasil menangkap email dan memicu download PDF brosur tanpa reload halaman.
3. Seluruh logo sertifikasi/partner tampil transparan di footer tanpa kotak putih atau glitch hover.
4. Tidak ada teks menggunakan gaya ALL-CAPS (`uppercase`), melainkan menggunakan Title Case atau Sentence case elegan.
5. Panduan pengelolaan konten operasional terdokumentasi lengkap di [`COMPRO_GUIDE.md`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/COMPRO_GUIDE.md).

---

*Dokumen ini merupakan spesifikasi resmi kebutuhan produk Website Company Profile Full Drone Solutions (FDS) PT Karya Solusi Angkasa.*
