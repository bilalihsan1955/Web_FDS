# PRODUCT REQUIREMENTS DOCUMENT (PRD)

## Redesain Website Full Drone Solutions (FDS)

**STATUS: FINAL (REVISI STRUKTUR MULTI-HALAMAN)**

| | |
| --- | --- |
| **Nama Produk** | Redesain Website Company Profile Multi-Halaman Full Drone Solutions |
| **Versi Dokumen** | v0.2 |
| **Disusun oleh** | Bilal Al Ihsan (Pengembang) |
| **Untuk** | Tim Manajemen Full Drone Solutions (Klien) |
| **Tanggal** | 18 Agustus 2026 |
| **Dokumen Terkait** | Notulen Diskusi Revisi PRD - Evaluasi Konten Eksisting & Blog |

---

# 1. Ringkasan Produk (Overview)

Website Full Drone Solutions (FDS) saat ini sudah memiliki konten yang solid dan lengkap, mencakup berbagai produk (Drone Sprayer, Spreader, Pemetaan, Inspeksi), layanan purna jual, hingga kolaborasi dengan instansi besar (Bappenas, BI, UGM). Namun, antarmuka visual saat ini dinilai terlalu kaku dan terkesan generik, sehingga belum memancarkan citra sebagai produsen drone teknologi tinggi dengan sertifikasi TKDN tertinggi di Indonesia.

Proyek ini bertujuan untuk merombak total antarmuka (*frontend*) seluruh halaman website FDS menggunakan pendekatan desain *Clean Minimalist* tanpa mengubah inti konten yang sudah ada. Pengembangan akan dibangun secara monolitik di atas WordPress menggunakan Roots Sage dan Tailwind CSS. Ruang lingkup mencakup pembuatan sistem navigasi multi-halaman yang mulus, perombakan halaman produk (Seri FERTO), halaman layanan (Rental & Kursus), revitalisasi halaman Tentang Kami, serta optimalisasi halaman Blog sebagai pusat pembaruan informasi dan SEO.

# 2. Tujuan & Sasaran (Goals)

- Menyajikan seluruh katalog produk dan layanan eksisting FDS dalam balutan desain yang lebih profesional, modern, dan mudah dinavigasi.
- Menyoroti secara prominen *Unique Selling Proposition* (USP) perusahaan: Produk buatan Indonesia ber-TKDN (44,85%), Software Berbahasa Indonesia, dan Jasa *After-Sales* lokal.
- Menyediakan arsitektur sistem Blog yang rapi untuk mendukung visibilitas *search engine* dan publikasi artikel teknis/kegiatan kolaborasi.
- Mengubah struktur *codebase* menggunakan Roots Sage agar lebih ringan, memangkas waktu *loading*, dan memudahkan *maintenance* tanpa menggunakan *page builder* yang berat.

# 3. Pengguna & Peran (Users & Roles)

- **Pengunjung (Calon Klien & Mitra) :** Mengakses situs untuk mencari detail spesifikasi drone, membaca studi kasus/blog, mengeksplorasi layanan kursus/sewa, dan melakukan kontak.
- **Admin Konten FDS :** Memperbarui katalog produk, mempublikasikan artikel di blog, serta mengelola form pesan yang masuk dari pengunjung melalui *dashboard* WordPress.

# 4. Ruang Lingkup (Scope)

## 4.1 Termasuk (MVP)

- **Navigasi Global:** Header, *dropdown* menu multi-halaman, dan Footer.
- **Halaman Beranda:** Menampilkan ringkasan USP, kategori drone utama, dan logo kolaborator.
- **Halaman Layanan & Produk:** Sub-halaman untuk Drone Sprayer (Ferto 5, 10, 15, 22), Spreader, Pemetaan, Inspeksi, serta Pesewaan & Kursus FDS.
- **Halaman Tentang Kami:** Fokus pada pengalaman 10+ tahun, pabrik lokal, sertifikasi, dan kolaborasi strategis (Bappenas, BI, UGM, Swiss).
- **Halaman Blog:** Daftar artikel, kategori, fungsi pencarian, dan halaman baca artikel teroptimasi.
- **Halaman Kontak:** Formulir *inquiry*, tautan WhatsApp, dan informasi lokasi.

## 4.2 Di Luar Lingkup Awal / Fase Lanjutan

- Sistem portal manajemen klien (Client Area) untuk memantau status servis *after-sales* secara mandiri.
- Integrasi pembayaran otomatis (E-commerce langsung) untuk pesanan unit drone atau kursus.

# 5. Asumsi & Batasan (Assumptions & Constraints)

- Data produk yang digunakan merujuk pada unit yang sudah terdaftar (FDS Ferto 5, 10, 15, 22, dll).
- Infrastruktur *backend* tetap menggunakan WordPress untuk mempertahankan ekosistem Blog dan SEO yang sudah ada.
- *Styling* akan sepenuhnya menggunakan Tailwind CSS yang dikompilasi di dalam tema Roots Sage (Blade Templating).
- Klien bersedia menyiapkan ulang gambar produk beresolusi tinggi dengan *background* polos/transparan agar estetika *minimalist* dapat tercapai.

# 6. Kebutuhan Fungsional (Functional Requirements)

## 6.1 Pengunjung — Halaman Beranda (HOM)

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **HOM-1** | Sistem menampilkan *Hero Banner* dengan CTA utama dan visual drone pertanian berteknologi tinggi. | **Wajib** |
| **HOM-2** | Sistem menampilkan poin USP FDS: Sertifikat TKDN, Pengalaman 10 Tahun, Jaminan *After-Sales*, dan Melayani *Customized*. | **Wajib** |
| **HOM-3** | Sistem menampilkan daftar logo mitra kolaborasi strategis (Bappenas, Pemerintah Australia, Bank Indonesia, UGM). | **Wajib** |

## 6.2 Pengunjung — Katalog Produk & Layanan (PRD)

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PRD-1** | Sistem menyediakan halaman khusus untuk setiap kategori: Sprayer, Spreader, Pemetaan, dan Inspeksi. | **Wajib** |
| **PRD-2** | Sistem menampilkan katalog seri FDS FERTO (5L, 10L, 15L, 22L) lengkap dengan kapasitas tangki dan fungsi teknisnya. | **Wajib** |
| **PRD-3** | Sistem menampilkan informasi layanan terpisah untuk Penyewaan Drone dan Kursus FDS. | **Wajib** |
| **PRD-4** | Sistem menyajikan halaman detail produk dengan tata letak informasi yang bersih dan mudah dipindai (*scannable*). | **Wajib** |

## 6.3 Pengunjung — Sistem Blog (BLG)

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **BLG-1** | Sistem menampilkan halaman arsip (Daftar Blog) yang diurutkan dari artikel terbaru ke terlama dengan fitur *pagination*. | **Wajib** |
| **BLG-2** | Setiap kartu (*card*) artikel pada halaman arsip menampilkan gambar *thumbnail*, judul, tanggal terbit, dan cuplikan teks (*excerpt*). | **Wajib** |
| **BLG-3** | Sistem menampilkan halaman baca artikel (Single Post) yang rapi, mendukung elemen tipografi artikel (*heading, blockquote*, daftar), dan memiliki fitur bagikan ke media sosial. | **Wajib** |

## 6.4 Pengunjung — Kontak & Tentang Kami (KTK)

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **KTK-1** | Sistem menampilkan sejarah FDS, komitmen penggunaan Software Bahasa Indonesia, dan nilai TKDN pada halaman Tentang Kami. | **Wajib** |
| **KTK-2** | Sistem menyediakan formulir *inquiry* di halaman Contact yang langsung terhubung ke sistem pengiriman *email*. | **Wajib** |
| **KTK-3** | Sistem memuat integrasi tombol akses cepat WhatsApp untuk mempermudah komunikasi langsung dengan pihak *Sales*. | **Penting** |

# 7. Alur Pengguna Utama (Key User Flows)

## 7.1 Alur Eksplorasi Produk Lengkap

1. Pengunjung menavigasi menu *dropdown* "Layanan Drone" di Header.
2. Pengunjung memilih "Drone Sprayer".
3. Sistem memuat halaman katalog khusus Sprayer yang menampilkan seri FERTO 5, 10, 15, dan 22.
4. Pengunjung mengklik salah satu produk untuk membaca spesifikasi detail, lalu mengklik tombol WhatsApp atau form penawaran untuk berkonsultasi.

## 7.2 Alur Konsumsi Konten Blog & Berita

1. Pengunjung menavigasi menu "Blogs" di Navbar utama.
2. Sistem menampilkan halaman arsip *grid* berisi artikel terbaru terkait pameran (misal: MUNAS ASTTA 2025) atau studi kasus kolaborasi.
3. Pengunjung mengklik artikel, lalu sistem memuat halaman baca (*Single Post*) yang dioptimasi agar ramah di mata (*readable*).

# 8. Model Data (High-Level)

| **Entitas** | **Field Utama** | **Keterangan** |
| --- | --- | --- |
| **Post_Artikel** | id, judul, slug, konten, id_kategori, tanggal_terbit, status, meta_deskripsi | Data bawaan *post* WordPress untuk fungsionalitas Blog. |
| **Produk_Drone** | id, nama_produk, kategori_layanan, deskripsi, spesifikasi_teks, gambar_url | Katalog unit, dapat memanfaatkan *Custom Post Type* atau *Page* terstruktur. |
| **Pesan_Kontak** | id, nama_pengirim, email_pengirim, no_telepon, isi_pesan, tanggal | Data masukan dari form *Contact Us*. |

**Catatan:** field dalam [tanda kurung siku] merupakan bagian dari fitur usulan/Fase Lanjutan (Bab 11).

# 9. Kebutuhan Non-Fungsional (Non-Functional Requirements)

- **UI/UX (Clean Minimalist) :** Desain harus mengurangi *noise* visual yang berlebihan. Menggunakan banyak ruang kosong (*whitespace*), tipografi yang tajam, dan warna *background* yang menonjolkan perangkat *hardware* drone.
- **Sistem Templating :** Penggunaan Roots Sage untuk merender tampilan (*Blade*) guna mencegah HTML *bloat* yang sering terjadi pada tema standar WordPress.
- **SEO & Performa :** Dukungan penuh terhadap integrasi *plugin* SEO (untuk optimasi Blog). *Asset build* (CSS Tailwind & JS) diwajibkan melewati proses *minify* untuk mengamankan skor kecepatan web.

# 10. Integrasi Pihak Ketiga

| **Layanan** | **Fungsi** | **Catatan** |
| --- | --- | --- |
| **Plugin Formulir (Contact Form 7 / WPForms)** | Menangkap *leads* dari halaman Kontak dan meneruskan ke *email* FDS. | Tampilan formulir akan dioverride menggunakan *class* Tailwind. |
| **Plugin SEO (Yoast / RankMath)** | Manajemen OpenGraph dan *meta tags* untuk halaman Produk dan Blog. | - |
| **WhatsApp API** | Integrasi tautan klik-langsung ke nomor WhatsApp resmi FDS. | - |

# 11. Fitur Usulan / Fase Lanjutan

- **Sistem Filtering Katalog Produk.** Memungkinkan pengunjung untuk memfilter daftar produk secara dinamis (tanpa *reload*) berdasarkan kapasitas *payload* (5L, 10L, 15L, 22L) atau berdasarkan sektor industri.
- **Kalkulator Penghematan Agrikultur.** *Widget* interaktif di halaman Sprayer yang memungkinkan petani menghitung estimasi penghematan waktu/pupuk apabila menggunakan produk FDS dibandingkan metode manual.

# 12. Pertanyaan Terbuka / TBD

- **Migrasi Konten:** Apakah seluruh pos artikel blog yang ada di web lama (*fulldronesolutions.co.id*) perlu dipindahkan (*migrate*) satu per satu ke dalam sistem instalasi lokal yang baru, atau pembuatan konten blog akan dimulai dari nol (hanya memindahkan yang penting saja)?
- **Aset Gambar:** Apakah ada tautan *Google Drive* yang berisi *file* foto mentah/asli untuk tim teknis dan jajaran drone FDS agar bisa dipotong latar belakangnya (*cut-out*) secara rapi?

# 13. Glosarium

- **Seri FERTO :** Lini produk utama dari Full Drone Solutions (Ferto-5, Ferto-10, Ferto-15, Ferto-22).
- **Roots Sage :** Ekosistem dan *starter theme* WordPress yang memfasilitasi penggunaan Blade Templating dan *utility-first* CSS (Tailwind) untuk perancangan tema yang lebih tangguh dan rapi.
- **TKDN :** Tingkat Komponen Dalam Negeri; metrik persentase komponen buatan lokal. FDS menonjol dengan nilai tertinggi (44,85%).
- **Clean Minimalist :** Pendekatan desain antarmuka yang mengutamakan fungsi, kejernihan tipografi, dan menghindari dekorasi visual yang tidak perlu untuk menampilkan kesan elegan dan profesional.

---

*Dokumen ini merupakan draft sementara dan dapat berubah seiring pembahasan lebih lanjut dengan klien.*