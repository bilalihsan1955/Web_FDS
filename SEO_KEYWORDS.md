# 🎯 SEO_KEYWORDS.md — MASTER KEYWORD REPOSITORY & GEO MATRIX
**Perusahaan:** PT Karya Solusi Angkasa (`Full Drone Solutions` / `FDS`)  
**Tipe Platform:** Website Company Profile (Compro) B2B Enterprise  
**Domain Utama:** `https://fulldronesolutions.co.id/`  
**Single Source of Truth:** Terintegrasi dengan [`app/seo.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/seo.php), [`app/layanan-data-sectors.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/layanan-data-sectors.php), dan [`app/layanan-data-categories.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/layanan-data-categories.php)  
**Versi:** 2.7 (Enterprise Production Edition — 4 Core Pillars, Funnel Layanan & Compliance Alignment)  
**Terakhir Diperbarui:** 2026-11-11  

---

## 🧭 4 PILAR STRATEGIS UTAMA (CORE FOCUS PILLARS)

Website FDS memprioritaskan 4 pilar kata kunci B2B bernilai tinggi berikut agar mendominasi pencarian Google serta mudah dikutip oleh model AI (`ChatGPT`, `Perplexity`, `Claude`, `Google Gemini`):

```
┌────────────────────────────────────────────────────────────────────────┐
│                   PT KARYA SOLUSI ANGKASA (FDS)                        │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
     ┌──────────────────────────────┼──────────────────────────────┐
     │                              │                              │
┌────┴────────────────────────┐ ┌───┴────────────────────────┐ ┌───┴────────────────────────┐ ┌───┴────────────────────────┐
│           PILAR 1           │ │           PILAR 2          │ │           PILAR 3          │ │           PILAR 4          │
│   PRODUSEN DRONE PERTANIAN, │ │      JASA FOTO UDARA       │ │      KONSULTAN GIS         │ │    PEMETAAN KAWASAN KEK,   │
│   PEMETAAN, LOGISTIK DAN    │ │         DAN LIDAR          │ │  (SISTEM INFORMASI         │ │   TAMBANG, KEHUTANAN DAN   │
│        SURVEILLANCE         │ │   (SURVEY SUB-SENTIMETER)  │ │        GEOGRAFIS)          │ │          INDUSTRI          │
│ (TKDN 60,74% SERI FERTO)    │ │  (FOTOGRAMETRI & DTM/DSM)  │ │ (ANALISIS SPASIAL & WEBGIS)│ │ (VOLUME STOCKPILE & KORIDOR)│
└─────────────────────────────┘ └────────────────────────────┘ └────────────────────────────┘ └────────────────────────────┘
```

### Matriks 4 Pilar Utama FDS:

| Pilar | Fokus Layanan / Produk | Kata Kunci Prioritas (Primary Keywords) | Target Audiens & Search Intent | Target URL Aktif |
|---|---|---|---|---|
| **Pilar 1** | **Produsen Drone Pertanian, Pemetaan, Logistik & Surveillance** | `produsen drone pertanian`, `produsen drone pemetaan`, `produsen drone logistik`, `produsen drone surveillance`, `pabrik drone indonesia`, `manufaktur uav tkdn`, `drone sprayer ferto`, `drone vtol deltav` | B2B, Dinas Pertanian, BUMN Pangan, Perkebunan Sawit, Instansi Pemerintah (Commercial / Transactional) | [`/produk/`](/produk/) & [`/drone/*`](/drone/ferto-15l/) |
| **Pilar 2** | **Jasa Foto Udara dan LiDAR** | `jasa foto udara`, `jasa drone foto udara`, `foto udara resolusi tinggi`, `jasa lidar drone`, `jasa survey lidar`, `survey lidar indonesia`, `point cloud lidar dtm dsm`, `ortofoto ortomosaik` | Konsultan Teknik, Kontraktor EPC, Surveyor Topografi, Pengembang Infrastruktur (Commercial Investigation) | [`/layanan/jasa-foto-udara-lidar/`](/layanan/jasa-foto-udara-lidar/) & [`/#solusi`](/#solusi) |
| **Pilar 3** | **Konsultan GIS (Sistem Informasi Geografis)** | `konsultan gis`, `konsultan pemetaan gis`, `jasa konsultan sig`, `analisis spasial gis`, `webgis enterprise`, `pembuatan peta tata ruang gis`, `kadastral dan batas wilayah`, `pemodelan topografi 3d` | Pemerintah Daerah (Bappeda/ATR-BPN), Perusahaan Perkebunan HGU, Pengelola Aset Spasial (Commercial / Informational) | [`/layanan/`](/layanan/) & [`/layanan/perencanaan/`](/layanan/perencanaan/) |
| **Pilar 4** | **Pemetaan Kawasan KEK, Tambang, Kehutanan & Industri** | `pemetaan kawasan kek`, `pemetaan kawasan ekonomi khusus`, `jasa pemetaan drone tambang`, `survey drone tambang batubara nikel`, `perhitungan volume stockpile cut and fill`, `pemetaan kawasan kehutanan`, `pemetaan kawasan industri` | Direktur Operasional Tambang, Pengelola Kawasan KEK, Konsesi Hutan Tanaman Industri, Pengelola Kawasan Industri (High-Intent B2B Enterprise) | [`/layanan/pertambangan/`](/layanan/pertambangan/), [`/layanan/kehutanan-lingkungan/`](/layanan/kehutanan-lingkungan/), [`/layanan/energi-infrastruktur/`](/layanan/energi-infrastruktur/) |

---

## 🤖 ARSITEKTUR PENYERAPAN DATA OLEH AI (GEO - GENERATIVE ENGINE OPTIMIZATION)

### 1. Endpoint Standar Resmi `/llms.txt` & `/llms-full.txt`
* Mengimplementasikan protokol `llmstxt.org` tanpa beban script/styling dan bebas entitas HTML (`&amp;` didekode bersih sebelum output).
* Memuat ringkasan entitas resmi PT Karya Solusi Angkasa (berbadan hukum PT sejak 2019), ruang lingkup akurat **TKDN + BMP 60,74%** khusus seri FERTO dan **SNI 9199:2023** khusus FERTO 15 & FERTO 22, 9 model drone aktif, daftar luaran spasial, Q&A AI, dan tautan hub `/produk/` serta `/layanan/`.

### 2. Semantic Triples (Schema.org JSON-LD Terpadu)
* `Organization` / `Corporation` / `LocalBusiness` (`legalName: PT Karya Solusi Angkasa`, bebas `priceRange: "$$$$"`) $\rightarrow$ `hasOfferCatalog` $\rightarrow$ 4 Pilar Layanan Utama.
* Halaman detail drone memancarkan `ItemPage` + `about: Thing` + `FAQPage` + `BreadcrumbList` agar bebas error Merchant/Product/Review snippet Search Console.
* Halaman layanan dan katalog menggunakan `Service`, `CollectionPage`, `ItemList`, `FAQPage`, dan `ContactPage`.

### 3. Izin Khusus AI Crawler di `robots.txt`
* Memberikan akses crawl kepada bot resmi (`GPTBot`, `OAI-SearchBot`, `ClaudeBot`, `PerplexityBot`, `Google-Extended`, `Applebot`, `CCBot`, `cohere-ai`) dan menjaga `noindex` khusus pada host staging (`compro.fulldronesolutions.co.id`) serta halaman konversi `/terima-kasih/`.

---

## 📌 DAFTAR ISI
1. [4 Pilar Strategis Utama](#-4-pilar-strategis-utama-core-focus-pillars)
2. [Arsitektur Penyerapan Data oleh AI (GEO)](#-arsitektur-penyerapan-data-oleh-ai-geo---generative-engine-optimization)
3. [Arsitektur Distribusi Kata Kunci](#-1-arsitektur-distribusi-kata-kunci)
4. [Cluster 1: Root & Head Keywords](#-cluster-1-root--head-keywords-34-keywords)
5. [Cluster 2: Drone Pertanian & Smart Agriculture](#-cluster-2-drone-pertanian--smart-agriculture-31-keywords)
6. [Cluster 3: Drone Mapping, Foto Udara & LiDAR](#-cluster-3-drone-mapping-pemetaan--survey-lidar-23-keywords)
7. [Cluster 4: Drone Industri, Misi SAR, Kargo & Kehutanan](#-cluster-4-drone-industri-misi-sar-kargo--kehutanan-19-keywords)
8. [Cluster 5: Manufaktur Drone Indonesia & Legalitas TKDN](#-cluster-5-manufaktur-drone-indonesia-legalitas-tkdn--pengadaan-b2bbumn-18-keywords)
9. [Kata Kunci Kontekstual Per Halaman URL](#-7-kata-kunci-kontekstual-per-halaman-url)
10. [Google Knowledge Graph Entities](#-8-google-knowledge-graph-entities-schemaorg-knowsabout-27-entitas)
11. [Matriks Komparasi](#-9-matriks-komparasi-posisi-pasar)

---

## 🏗️ 1. ARSITEKTUR DISTRIBUSI KATA KUNCI

Seluruh kata kunci dalam dokumen ini disalurkan ke 5 lapisan teknis:
1. **`<title>` Tag Utama:** Kata kunci prioritas dengan nama resmi **Full Drone Solutions** / **PT Karya Solusi Angkasa**.
2. **`<meta name="description">`:** Copywriting B2B terverifikasi dengan kapasitas aktual (`17 Liter` untuk FERTO 15), TKDN seri FERTO, SNI FERTO 15 & 22, dan fasilitas Sleman, Yogyakarta.
3. **`<meta name="keywords">` & Entity Tagging:** Diolah melalui [`app/seo.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/seo.php) dengan aturan ketat bebas klaim usang.
4. **Schema.org JSON-LD (`@graph`):** `Organization`, `Corporation`, `LocalBusiness`, `CollectionPage`, `ItemList`, `ItemPage`, `Service`, `BlogPosting`, `FAQPage`, dan `ContactPage`.
5. **Konten SSR HTML Awal:** Heading `<h1>`/`<h2>`, tabel spesifikasi, tabel perbandingan metode layanan, panduan teknis 250–400 kata, serta FAQ akordeon `<details>`.

---

## 🚀 CLUSTER 1: ROOT & HEAD KEYWORDS (34 KEYWORDS)

| No | Kata Kunci (Keyword) | Search Intent | Target URL |
|---|---|---|---|
| 1 | `drone` | Head Term / Commercial | `/` (Beranda) |
| 2 | `drone indonesia` | High Intent Commercial | `/` (Beranda) |
| 3 | `uav indonesia` | B2B Commercial | `/` & `/tentang-kami/` |
| 4 | `drone uav` | Commercial / Technical | `/` (Beranda) |
| 5 | `pesawat tanpa awak` | Informational / Government | `/` & `/tentang-kami/` |
| 6 | `pesawat nirawak` | Formal / LKPP | `/tentang-kami/` |
| 7 | `produsen drone` | B2B / Manufacturing | `/` & `/tentang-kami/` |
| 8 | `produsen drone indonesia` | B2B / RFP | `/` (Beranda) |
| 9 | `pabrik drone` | B2B / Supplier | `/tentang-kami/` |
| 10 | `pabrik drone indonesia` | B2B / Supplier | `/tentang-kami/` |
| 11 | `manufaktur drone indonesia` | Industry / APBN | `/tentang-kami/` |
| 12 | `jual drone` | Transactional | `/produk/` & `/bandingkan/` |
| 13 | `jual drone indonesia` | Transactional B2B | `/produk/` & `/kontak/` |
| 14 | `harga drone` | Commercial Investigation | `/bandingkan/` & `/kontak/` |
| 15 | `beli drone` | Transactional | `/kontak/` |
| 16 | `katalog drone indonesia` | Commercial | `/produk/` |
| 17 | `distributor drone indonesia` | B2B Partnership | `/kontak/` |
| 18 | `perusahaan drone` | Corporate Entity | `/tentang-kami/` |
| 19 | `perusahaan drone indonesia` | Corporate Entity | `/tentang-kami/` |
| 20 | `pt karya solusi angkasa` | Branded Official | `/` & `/tentang-kami/` |
| 21 | `full drone solutions` | Branded Primary | `/` (Beranda) |
| 22 | `fds drone` | Branded Short | `/` (Beranda) |
| 23 | `drone tkdn` | Government Procurement | `/produk/` & `/kategori-drone/agrikultur/` |
| 24 | `drone ber-tkdn` | Government Procurement | `/` & `/produk/` |
| 25 | `drone sni` | Quality Assurance | `/drone/ferto-15l/` & `/drone/ferto-22l/` |
| 26 | `drone industrial` | Enterprise Solution | `/produk/` |
| 27 | `drone enterprise indonesia` | B2B Corporate | `/` & `/kontak/` |
| 28 | `drone komersial` | Commercial | `/produk/` |
| 29 | `drone b2b` | B2B Procurement | `/kontak/` |
| 30 | `drone profesional` | Professional Services | `/layanan/` |
| 31 | `teknologi drone indonesia` | Tech & Innovation | `/blog/` |
| 32 | `rekayasa drone indonesia` | R&D Engineering | `/tentang-kami/` |
| 33 | `pusat perakitan drone yogyakarta` | Local Authority | `/tentang-kami/` |
| 34 | `drone sleman yogyakarta` | Geo Local SEO | `/kontak/` |

---

## 🌾 CLUSTER 2: DRONE PERTANIAN & SMART AGRICULTURE (31 KEYWORDS)
> *Catatan Kepatuhan:* Nilai **TKDN + BMP 60,74%** mencakup seluruh lini **FERTO (5L, 10L, 15L/17L, 22L, 30L, 50L)**; **SNI 9199:2023** khusus **FERTO 15** (`17 Liter`) dan **FERTO 22**. Rute lama `/drone/ferto-20l/` dialihkan 301 ke `/drone/ferto-15l/`.

| No | Kata Kunci (Keyword) | Search Intent | Target URL |
|---|---|---|---|
| 1 | `drone pertanian` | Primary Commercial | `/kategori-drone/agrikultur/` & `/drone/ferto-15l/` |
| 2 | `jual drone pertanian` | Transactional B2B | `/produk/` & `/kontak/` |
| 3 | `harga drone pertanian` | Commercial / Pricing | `/bandingkan/` & `/kontak/` |
| 4 | `drone pertanian indonesia` | National Authority | `/` (Beranda) |
| 5 | `alat semprot pertanian otomatis` | Problem-Solving Query | `/drone/ferto-10l/` |
| 6 | `alat penyiram tanaman modern` | Alternative Query | `/drone/ferto-5l/` |
| 7 | `drone sprayer indonesia` | Equipment Specific | `/drone/ferto-15l/` |
| 8 | `drone sprayer pertanian` | Equipment Specific | `/drone/ferto-30l/` |
| 9 | `drone penyemprot pupuk cair` | Functional Target | `/drone/ferto-15l/` |
| 10 | `drone penyemprot pestisida` | Functional Target | `/drone/ferto-22l/` |
| 11 | `drone tebar pupuk granul dan benih` | Dual-function Feature | `/drone/ferto-50l/` |
| 12 | `drone sebar benih padi` | Agronomy Solution | `/drone/ferto-22l/` |
| 13 | `drone pertanian tkdn` | LKPP / Tender Kementan | `/kategori-drone/agrikultur/` & `/drone/ferto-15l/` |
| 14 | `drone semprot sawit tebu padi` | Crop Specific | `/drone/ferto-50l/` |
| 15 | `drone perkebunan kelapa sawit` | Plantation Enterprise | `/layanan/perkebunan-pertanian/` & `/drone/ferto-50l/` |
| 16 | `smart agriculture drone indonesia` | High-Tech Agriculture | `/layanan/jasa-multispectral-hyperspectral/` |
| 17 | `drone ferto 50l` | Branded Product Model | `/drone/ferto-50l/` |
| 18 | `drone ferto 30l` | Branded Product Model | `/drone/ferto-30l/` |
| 19 | `drone ferto 22l` | Branded Product Model | `/drone/ferto-22l/` |
| 20 | `drone ferto 15l` | Branded Product Model | `/drone/ferto-15l/` |
| 21 | `drone ferto 10l` | Branded Product Model | `/drone/ferto-10l/` |
| 22 | `drone ferto 5l` | Branded Product Model | `/drone/ferto-5l/` |
| 23 | `drone pertanian 50 liter` | Capacity Query | `/drone/ferto-50l/` |
| 24 | `drone pertanian 30 liter` | Capacity Query | `/drone/ferto-30l/` |
| 25 | `drone pertanian 22 liter` | Capacity Query | `/drone/ferto-22l/` |
| 26 | `drone pertanian 17 liter` | Capacity Query | `/drone/ferto-15l/` |
| 27 | `drone pemupukan presisi` | Agrotechnology | `/layanan/perkebunan-pertanian/` |
| 28 | `drone agrikultur indonesia` | Industry Query | `/kategori-drone/agrikultur/` |
| 29 | `drone multispektral ndvi kesehatan tanaman` | Precision Analytics | `/layanan/jasa-multispectral-hyperspectral/` & `/drone/deltav/` |
| 30 | `jasa sewa drone sprayer pertanian` | Operational Service | `/layanan/perkebunan-pertanian/` & `/kontak/` |
| 31 | `komparasi drone pertanian ber-tkdn` | Procurement Comparison | `/bandingkan/` |

---

## 🗺️ CLUSTER 3: DRONE MAPPING, PEMETAAN & SURVEY LIDAR (23 KEYWORDS)

| No | Kata Kunci (Keyword) | Search Intent | Target URL |
|---|---|---|---|
| 1 | `drone mapping` | Commercial Service | `/layanan/jasa-foto-udara-lidar/` & `/drone/deltav/` |
| 2 | `jual drone mapping` | Transactional Hardware | `/drone/deltav/` |
| 3 | `jasa pemetaan drone` | High-Value B2B Service | `/layanan/` & `/kontak/` |
| 4 | `jasa pemetaan drone rtk ppk` | High Precision Technical | `/layanan/jasa-foto-udara-lidar/` |
| 5 | `jasa survey drone` | Technical Survey | `/layanan/` |
| 6 | `jasa drone foto udara` | Photography / GIS | `/layanan/jasa-foto-udara-lidar/` |
| 7 | `jasa drone lidar` | High-Value LiDAR Service | `/layanan/jasa-foto-udara-lidar/` |
| 8 | `jasa survey lidar indonesia` | Geodesy / Topography | `/layanan/jasa-foto-udara-lidar/` |
| 9 | `jasa pemetaan drone tambang` | Mining B2B | `/layanan/pertambangan/` |
| 10 | `pemetaan GIS drone` | Geospatial GIS | `/layanan/perencanaan/` & `/blog/` |
| 11 | `drone topografi tambang` | Mining Topography | `/layanan/pertambangan/` & `/drone/deltav/` |
| 12 | `fotogrametri udara ortomosaik dsm dtm` | Elevation Model Data | `/layanan/jasa-foto-udara-lidar/` |
| 13 | `drone fixed wing vtol indonesia` | Airframe Specification | `/drone/deltav/` |
| 14 | `long endurance hybrid vtol uav` | Technical Specification | `/drone/deltav/` |
| 15 | `uav vtol pemetaan jarak jauh` | Long Range Mission | `/drone/deltav/` |
| 16 | `drone deltav` | Branded Product Model | `/drone/deltav/` |
| 17 | `perhitungan volume stockpile tambang` | Volume Calculation Cut/Fill | `/layanan/pertambangan/` |
| 18 | `drone cut and fill tambang batubara nikel` | Mining Operations | `/layanan/pertambangan/` |
| 19 | `survey koridor transmisi listrik dan pipa migas` | Infrastructure Corridor | `/layanan/energi-infrastruktur/` |
| 20 | `pemetaan batas kawasan perkebunan dan kehutanan hgu` | HGU Boundary Mapping | `/layanan/kehutanan-lingkungan/` |
| 21 | `jasa pemetaan drone dan survey topografi` | Survey Services | `/layanan/perencanaan/` & `/kontak/` |
| 22 | `drone inspeksi tambang nikel batubara` | Mining Inspection | `/layanan/pertambangan/` |
| 23 | `pemetaan ortofoto resolusi tinggi` | Mapping Quality | `/layanan/jasa-foto-udara-lidar/` |

---

## 🚨 CLUSTER 4: DRONE INDUSTRI, MISI SAR, KARGO & KEHUTANAN (19 KEYWORDS)
> *Catatan Arsitektur:* URL lama `/drone/rebo/` tidak lagi aktif dan diarahkan 301 ke `/produk/`. Kata kunci terkait kehutanan dan rehabilitasi diarahkan ke `/layanan/kehutanan-lingkungan/` serta `/produk/`.

| No | Kata Kunci (Keyword) | Search Intent | Target URL |
|---|---|---|---|
| 1 | `drone pencarian korban bencana` | SAR / Emergency | `/layanan/bencana-lingkungan/` & `/kontak/` |
| 2 | `drone logistik untuk kebencanaan` | Disaster Relief Cargo | `/drone/delfro/` |
| 3 | `drone mitigasi bencana` | Disaster Prevention | `/layanan/bencana-lingkungan/` |
| 4 | `drone misi SAR` | Search & Rescue Operations | `/layanan/bencana-lingkungan/` |
| 5 | `drone sensor termal inframerah` | Thermal Sensor Mission | `/drone/multipurpose/` & `/layanan/energi-infrastruktur/` |
| 6 | `drone inspeksi industri` | Plant & Asset Inspection | `/drone/multipurpose/` & `/layanan/energi-infrastruktur/` |
| 7 | `drone inspeksi sutet 150kv dan pltu` | Energy Infrastructure | `/layanan/energi-infrastruktur/` |
| 8 | `drone inspeksi migas dan energi` | Oil & Gas Inspection | `/layanan/energi-infrastruktur/` |
| 9 | `drone patroli karhutla dan keamanan` | Forest Fire & Security | `/layanan/kehutanan-lingkungan/` & `/drone/deltav/` |
| 10 | `drone surveillance pemantauan jarak jauh` | Long-Range Surveillance | `/drone/deltav/` & `/drone/multipurpose/` |
| 11 | `pemetaan restorasi hutan dan lahan kritis` | Environmental Restoration | `/layanan/kehutanan-lingkungan/` |
| 12 | `monitoring karbon dan inventarisasi hutan` | Carbon Stock & ESG | `/layanan/kehutanan-lingkungan/` |
| 13 | `drone kargo logistik darurat medis` | Emergency Medical Cargo | `/drone/delfro/` |
| 14 | `drone pengangkut barang daerah 3t` | Frontier Logistics | `/drone/delfro/` |
| 15 | `drone kargo delfro kapasitas besar` | Heavy-Lift Cargo Model | `/drone/delfro/` |
| 16 | `drone payload besar indonesia` | Heavy-Payload Query | `/kategori-drone/kargo/` & `/drone/delfro/` |
| 17 | `drone tanggap darurat bencana` | Emergency Response | `/layanan/bencana-lingkungan/` |
| 18 | `pemetaan perubahan lahan dan banjir` | Environmental Monitoring | `/layanan/bencana-lingkungan/` |
| 19 | `drone multipurpose uav` | Versatile Industrial Platform | `/drone/multipurpose/` |

---

## 📜 CLUSTER 5: MANUFAKTUR DRONE INDONESIA, LEGALITAS TKDN & PENGADAAN B2B/BUMN (18 KEYWORDS)

| No | Kata Kunci (Keyword) | Search Intent | Target URL |
|---|---|---|---|
| 1 | `produsen UAV Indonesia` | Core Corporate Authority | `/` & `/tentang-kami/` |
| 2 | `manufaktur drone indonesia` | Domestic Manufacturer | `/tentang-kami/` |
| 3 | `drone buatan indonesia` | Bangga Buatan Indonesia | `/` & `/tentang-kami/` |
| 4 | `pabrik drone di Indonesia` | Factory Facility | `/tentang-kami/` |
| 5 | `manufaktur drone lokal Indonesia` | Local Supply Chain | `/tentang-kami/` |
| 6 | `solusi drone enterprise indonesia` | B2B Solutions | `/` & `/layanan/` |
| 7 | `drone TKDN 60,74%` | Government Compliance | `/kategori-drone/agrikultur/` & `/drone/ferto-15l/` |
| 8 | `drone sertifikat SNI 9199:2023` | National Product Standard | `/drone/ferto-15l/` & `/drone/ferto-22l/` |
| 9 | `sistem manajemen mutu ISO 9001:2015` | Corporate Governance | `/tentang-kami/` |
| 10 | `pengadaan drone e-katalog LKPP pemerintah BUMN` | Procurement Target | `/kontak/` |
| 11 | `pelatihan pilot drone resmi` | Certified Drone Training | `/layanan/pelatihan-sertifikasi/` |
| 12 | `sertifikasi pilot drone DKPPU CASR 107 FDS Academy` | Regulatory Compliance | `/layanan/pelatihan-sertifikasi/` |
| 13 | `service dan reparasi drone indonesia` | Local Maintenance | `/kontak/` |
| 14 | `pusat servis dan suku cadang drone sprayer ready stock` | Spare Parts Availability | `/kontak/` & `/tentang-kami/` |
| 15 | `ground control station FDS Station bahasa indonesia` | Software In-House | `/` & `/tentang-kami/` |
| 16 | `manufaktur uav sleman yogyakarta` | Geographic Center | `/tentang-kami/` |
| 17 | `bengkel perakitan drone indonesia` | Workshop & Assembly | `/tentang-kami/` |
| 18 | `prioritas belanja apbn apbd drone lokal` | Government Budget Priority | `/kategori-drone/agrikultur/` & `/blog/` |

---

## 🎯 7. KATA KUNCI KONTEKSTUAL PER HALAMAN URL

### A. Halaman Beranda (`https://fulldronesolutions.co.id/`)
* **Target Keywords:** `drone`, `drone indonesia`, `produsen drone pertanian`, `drone sprayer tkdn`, `jasa pemetaan drone tambang`, `uav indonesia`.

### B. Hub Katalog (`/produk/`) & Kategori (`/kategori-drone/*`)
* **Target Keywords:** `katalog drone indonesia`, `drone pertanian ferto`, `drone pemetaan deltav`, `drone kargo delfro`, `drone inspeksi multipurpose`.

### C. Halaman Seri FERTO (`https://fulldronesolutions.co.id/drone/ferto-*/`)
* **Model Aktif:** `FERTO 5L`, `FERTO 10L`, `FERTO 15L` (`17 Liter` + `SNI 9199:2023`), `FERTO 22L` (`SNI 9199:2023`), `FERTO 30L`, `FERTO 50L` — seluruh seri ber-**TKDN + BMP 60,74%**.
* **Target Keywords:** `drone sprayer FERTO`, `drone pertanian kapasitas [X] liter`, `drone penyemprot pupuk sawit`, `drone sebar benih granul`, `suku cadang drone ready stock`.

### D. Halaman DELTAV, MULTIPURPOSE & DELFRO
* **`/drone/deltav/`:** `drone fixed wing vtol`, `drone pemetaan jarak jauh`, `drone survey lidar tambang`, `fotogrametri udara ortomosaik`.
* **`/drone/multipurpose/`:** `drone modular inspeksi aset`, `drone kamera termal inframerah`, `inspeksi transmisi 150kv`.
* **`/drone/delfro/`:** `drone kargo logistik darurat medis`, `drone payload 3-10 kg`, `pengiriman area sulit akses`.

### E. Hub & Detail Layanan (`/layanan/`, Sektor & Kategori Spesialis)
* **Hub `/layanan/`:** `jasa drone mapping indonesia`, `konsultan gis`, `survey lidar & multispectral`.
* **6 Sektor:** `pertambangan`, `perkebunan-pertanian`, `kehutanan-lingkungan`, `energi-infrastruktur`, `perencanaan`, `bencana-lingkungan`.
* **3 Kategori Spesialis:** `jasa-foto-udara-lidar`, `jasa-multispectral-hyperspectral`, `pelatihan-sertifikasi`.

### F. Bandingkan, Tentang Kami, Kontak & Newsroom
* **`/bandingkan/`:** `komparasi drone pertanian`, `spesifikasi teknis drone ferto vs deltav`, `perbandingan drone tkdn indonesia`.
* **`/tentang-kami/`:** `PT Karya Solusi Angkasa`, `pabrik perakitan drone sleman yogyakarta`, `berbadan hukum PT sejak 2019`, `standar mutu iso 9001:2015`.
* **`/kontak/`:** `hubungi produsen drone indonesia`, `jadwal demo terbang drone sleman`, `permintaan penawaran rfp uav`.
* **`/blog/` & Single Posts:** `{Judul Postingan} — Inovasi Drone Indonesia | Full Drone Solutions`.

---

## 🌐 8. GOOGLE KNOWLEDGE GRAPH ENTITIES (Schema.org `knowsAbout` — 27 Entitas)

1. `Drone`
2. `Drone Indonesia`
3. `Pesawat Udara Tanpa Awak (UAV)`
4. `Produsen Drone Indonesia`
5. `Pabrik Drone & Manufaktur UAV Indonesia`
6. `Jual Drone & Pengadaan Drone Pemerintah BUMN`
7. `Drone Pertanian & Sprayer Presisi FERTO TKDN 60,74%`
8. `Pengadaan Drone Pertanian E-Katalog LKPP`
9. `Drone Penyemprot Pupuk & Pestisida Tanaman Pangan & Sawit`
10. `Drone Sebar Benih & Pemupukan Kelapa Sawit`
11. `Jual Drone Pertanian & Servis Suku Cadang Resmi`
12. `Drone Mapping & Fotogrametri Udara Resolusi Tinggi`
13. `Jasa Pemetaan Drone & Survey Drone LiDAR`
14. `Jasa Pemetaan Drone Tambang (Volume Cut & Fill / Stockpile)`
15. `Jasa Drone Foto Udara & Pemetaan GIS Topografi`
16. `UAV Fixed-Wing Hybrid VTOL DELTAV Jelajah Panjang`
17. `Drone Pencarian Korban Bencana & Misi SAR Sensor Termal Inframerah`
18. `Pemetaan Kehutanan, Estimasi Karbon & Restorasi Lingkungan`
19. `Drone Kargo Logistik Medis Darurat DELFRO`
20. `Inspeksi Termal Inframerah Infrastruktur Energi, SUTET 150kV & Migas`
21. `Sistem Kendali Wahana FDS Station (Bahasa Indonesia)`
22. `Produsen UAV Indonesia Resmi (PT Karya Solusi Angkasa)`
23. `Sertifikasi TKDN & BMP 60,74% Kemenperin RI (Seri FERTO)`
24. `Standar Nasional Indonesia SNI 9199:2023 (FERTO 15 & FERTO 22)`
25. `Sistem Manajemen Mutu ISO 9001:2015`
26. `Pelatihan & Sertifikasi Pilot Drone DKPPU CASR Part 107`
27. `Pusat Riset, Perakitan & Servis Suku Cadang Sleman Yogyakarta`

---

## 📊 9. MATRIKS KOMPARASI POSISI PASAR

| Parameter SEO | Distributor / Unit Impor Umum | PT Karya Solusi Angkasa (FDS) |
|---|---|---|
| **Legalitas Belanja APBN/APBD** | Unit impor umumnya tanpa TKDN nasional atau TKDN rendah. | **TKDN + BMP 60,74% (Seri FERTO) & SNI 9199:2023 (FERTO 15 & 22)** — siap e-Katalog LKPP. |
| **Suku Cadang & Servis** | Inden suku cadang impor lintas negara. | **Suku cadang lokal siap pakai & servis terjadwal di workshop Sleman, Yogyakarta.** |
| **Software Ground Control Station** | Berbahasa asing dan bergantung ekosistem luar. | **FDS Station in-house berbahasa Indonesia** dengan alur perencanaan waypoint lokal. |
| **Target Keyword Penetrasi** | Fokus jual-beli merk impor semata. | **Kombinasi manufaktur UAV TKDN + layanan jasa pemetaan LiDAR, GIS, & multispektral.** |
| **Integrasi GEO / AI Indexing** | HTML standar tanpa struktur entitas B2B lengkap. | **`/llms.txt` bersih, JSON-LD `@graph` tervalidasi, & panduan teknis + FAQ SSR HTML.** |

---

> **Catatan Pengembang:** Dokumen ini selaras dengan implementasi pada [`app/seo.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/seo.php) dan [`app/layanan-settings.php`](file:///c:/Users/BILALIHSAN/Local%20Sites/fds/app/public/fds-theme/app/layanan-settings.php). Setiap pembaruan kata kunci atau URL layanan wajib disinkronkan di sini.
