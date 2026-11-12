<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS LAYANAN CATEGORIES REGISTRY (3 KATEGORI LAYANAN LINTAS SEKTOR)
 * =========================================================================
 * Single Source of Truth untuk:
 * 1. /layanan/jasa-foto-udara-lidar/ (Target keyword: jasa pemetaan lidar)
 * 2. /layanan/jasa-multispectral-hyperspectral/ (Target keyword: jasa drone multispectral)
 * 3. /layanan/pelatihan-sertifikasi/ (Target keyword: pelatihan pilot drone sertifikasi)
 *
 * Aturan ketahanan data:
 * - Zero [ISI: ...] literal pada output HTML publik; data perusahaan yang masih
 *   perlu konfirmasi dicatat terstruktur pada key 'placeholders'.
 * - Zero fabrikasi harga, model sensor LiDAR fiktif, klaim RMSE palsu, atau SLA fiktif.
 */
function fds_get_default_layanan_categories(): array {
    return [
        // =====================================================================
        // KATEGORI 01: JASA FOTO UDARA DAN LIDAR
        // =====================================================================
        'jasa-foto-udara-lidar' => [
            'slug'         => 'jasa-foto-udara-lidar',
            'url'          => home_url('/layanan/jasa-foto-udara-lidar/'),
            'short_title'  => 'Jasa Foto Udara dan LiDAR',
            'h1'           => 'Jasa Pemetaan LiDAR & Survey Foto Udara Presisi Indonesia',
            'subtitle'     => 'Akuisisi citra ortofoto resolusi tinggi dan pemetaan 3D laser scanning berstandar geodetik lintas sektor industri.',
            'hero_summary' => 'Layanan jasa pemetaan LiDAR dan fotogrametri drone untuk menghasilkan ortomosaik, Bare Earth Digital Terrain Model (DTM), Digital Surface Model (DSM), point cloud terklasifikasi LAS/LAZ, dan peta kontur detail.',
            'intro_paragraphs' => [
                'Full Drone Solutions (PT Karya Solusi Angkasa) menghadirkan layanan jasa pemetaan LiDAR dan jasa survey foto udara fotogrametri menggunakan pesawat nirawak (UAV) untuk kebutuhan data topografi presisi tinggi di seluruh Indonesia. Solusi ini dirancang untuk menjawab keterbatasan pengukuran terestris manual di medan luas serta mengatasi kendala foto udara biasa saat berhadapan dengan tutupan kanopi hutan, perkebunan rapat, atau koridor infrastruktur panjang.',
                'Berbeda dari sensor optik RGB yang hanya merekam puncak permukaan daun atau atap bangunan, teknologi LiDAR (Light Detection and Ranging) memancarkan ribuan pulsa sinar laser aktif per detik. Kemampuan multi-return memungkinkan pulsa laser menyusup melalui celah kecil tajuk vegetasi hingga menyentuh permukaan tanah asli (bare earth), menghasilkan jutaan hingga miliaran titik koordinat 3D (point cloud) yang diikat secara ketat ke jaringan kontrol geodesi nasional.',
                'Dalam praktiknya, proyek tidak selalu menuntut teknologi paling mahal secara membabi buta. Tim geospasial FDS mengevaluasi karakteristik area Anda terlebih dahulu—menentukan kapan foto udara fotogrametri sudah optimal untuk area terbuka, kapan jasa survey LiDAR drone menjadi wajib di lahan bervegetasi, atau kapan kombinasi keduanya perlu dijalankan sekaligus pada satu kampanye mobilisasi.',
            ],
            'challenge_section' => [
                'eyebrow'  => 'Prinsip Kerja & Batasan Teknis LiDAR',
                'title'    => 'Bagaimana Laser Scanning Menembus Kanopi & Apa Batasannya secara Fisik?',
                'paragraphs' => [
                    'Setiap tembakan sinar laser dari sensor LiDAR di udara dihitung jaraknya berdasarkan waktu tempuh pantulan (time-of-flight) lalu diselaraskan dengan posisi satelit GNSS RTK/PPK dan sudut orientasi IMU (Inertial Measurement Unit). Pantulan pertama (first return) merekam pucuk pepohonan atau atap struktur, pantulan menengah menangkap cabang atau jaringan kabel, sedangkan pantulan terakhir (last return) merekam permukaan tanah asli untuk membentuk Bare Earth DTM.',
                    'Penting dipahami secara teknis dan jujur: sinar laser LiDAR bekerja secara optik di atas permukaan tanah. Teknologi ini mampu menembus celah kanopi pepohonan lebat (bahkan pada konfigurasi khusus bathy-LiDAR mampu memindai dasar perairan dangkal yang jernih), namun LiDAR tidak dapat menembus lapisan tanah padat, beton, atau batuan di bawah permukaan. Untuk memetakan pipa atau kabel tertanam di bawah tanah, tetap dibutuhkan metode geofisika terpisah seperti Ground Penetrating Radar (GPR).',
                ],
                'pain_points' => [
                    [
                        'title' => 'Bias Elevasi pada Foto Udara di Lahan Berhutan & Sawit Tua',
                        'desc'  => 'Menggunakan model elevasi dari foto udara RGB pada lahan berkanopi menyebabkan hitungan volume cut and fill maupun rancangan parit drainase meleset setinggi tajuk pepohonan.',
                    ],
                    [
                        'title' => 'Lambatnya Pembukaan Rintisan pada Survei Terestris Darat',
                        'desc'  => 'Pengukuran Total Station atau GNSS rover di lereng berhutan lebat atau rawa dalam memakan waktu berminggu-minggu dan menuntut pembukaan jalur tebas rintisan.',
                    ],
                    [
                        'title' => 'Risiko Ketidakcocokan Metode terhadap Budget & KAK',
                        'desc'  => 'Tanpa penilaian awal antara fotogrametri RGB, Drone LiDAR, Backpack SLAM, dan alat ukur darat, anggaran pengadaan berisiko membengkak atau data gagal lolos uji verifikasi.',
                    ],
                ],
            ],
            'capabilities' => [
                [
                    'name' => 'Drone LiDAR Survey (3D Laser Scanning)',
                    'desc' => 'Pemindaian laser aktif berbasis UAV untuk menembus celah kanopi vegetasi, merekam point cloud kerapatan tinggi, struktur tajuk, kabel transmisi, dan membangun Digital Terrain Model (DTM) permukaan tanah murni di medan curam maupun berhutan.',
                ],
                [
                    'name' => 'Drone Photogrammetry & High-Resolution Orthophoto',
                    'desc' => 'Akuisisi citra udara metrik bertampalan untuk menghasilkan peta ortofoto ortomosaik bebas distorsi, peta situasi detail, model visual 3D, serta dokumentasi perubahan fisik lahan terbuka secara efisien.',
                ],
                [
                    'name' => 'Kontrol Geodetik (GCP/ICP) & QA/QC Standar ASPRS',
                    'desc' => 'Pengikatan titik kontrol tanah (GCP) terhadap jaringan referensi resmi maupun Bench Mark lokasi proyek, koreksi lintasan PPK, strip adjustment, dan verifikasi uji akurasi independen memakai Independent Check Point (ICP).',
                ],
                [
                    'name' => 'Ekstraksi Kontur, Earthwork Volume & Integrasi GIS',
                    'desc' => 'Pengolahan lanjutan point cloud terklasifikasi (.las/.laz) menjadi peta garis kontur siap CAD (.dwg/.dxf), analisis volume cut and fill serta stockpile, pemodelan koridor infrastruktur, dan basis data spasial GIS.',
                ],
            ],
            'method_comparison' => [
                'eyebrow'  => 'Panduan Pemilihan Teknologi Akuisisi',
                'title'    => 'Perbandingan Drone LiDAR, Fotogrametri RGB, Backpack SLAM & Survei Terestris',
                'subtitle' => 'Setiap metode pemetaan memiliki kekuatan dan batas fisik masing-masing. Matriks berikut membantu tim engineering menentukan pendekatan paling tepat guna untuk lokasi proyek Anda.',
                'columns'  => ['Aspek Teknis & Medan', 'Drone LiDAR Survey (3D Laser)', 'Fotogrametri Udara (Kamera RGB)', 'Backpack / Handheld SLAM LiDAR', 'Survei Terestris (Total Station / GNSS RTK)'],
                'rows'     => [
                    [
                        'Penetrasi Kanopi Hutan & Semak Rapat',
                        'Sangat efektif: pulsa laser menembus celah dedaunan hingga menyentuh permukaan tanah asli (Bare Earth).',
                        'Terbatas: hanya merekam permukaan atas daun (DSM), elevasi tanah di bawah kanopi rapat tidak terbaca.',
                        'Efektif di bawah kanopi sangat rapat tanpa sinyal langit, namun terbatas oleh jalur jalan kaki operator.',
                        'Akurat di titik ukur, tetapi sangat lambat dan memerlukan tebas rintisan jalur pandang di hutan.',
                    ],
                    [
                        'Kecepatan Cakupan & Efisiensi Area Luas',
                        'Tinggi: mampu menyapu ratusan hingga ribuan hektar konsesi, pit tambang, atau koridor infrastruktur dalam satu kampanye.',
                        'Sangat tinggi & paling ekonomis untuk lahan terbuka bersih (sawah, tanah gundul, kavling siap bangun).',
                        'Sedang hingga kecil: mengikuti kecepatan jalan kaki manusia sehingga biaya harian membengkak di lahan luas.',
                        'Terbatas: ideal untuk blok sempit beberapa hektar atau pengamatan titik referensi setempat.',
                    ],
                    [
                        'Kinerja di Bayangan Curam, Kabel & Struktur Kompleks',
                        'Laser aktif tidak bergantung pencahayaan matahari sehingga andal membaca dinding tebing dalam maupun kawat transmisi.',
                        'Kualitas pencocokan piksel menurun di area berbayangan gelap, air tenang, atau objek kabel tipis.',
                        'Sangat unggul di dalam terowongan, bawah tanah tambang, gorong-gorong, atau interior bangunan pabrik.',
                        'Bergantung garis pandang optik langsung dan sangat berisiko bila operator mendekati bibir tebing labil.',
                    ],
                    [
                        'Produk Data Utama yang Dihasilkan',
                        'Point Cloud terklasifikasi (.las/.laz), Bare Earth DTM, DSM, serta garis kontur topografi detail.',
                        'Ortofoto RGB beresolusi tinggi, 3D textured mesh, DSM, serta DTM khusus pada zona terbuka.',
                        'Point cloud jarak dekat di lorong, interior ruang, atau koridor sempit bawah tajuk tertutup total.',
                        'Koordinat titik ikat BM, GCP, ICP, patok kadastral legal, serta stake-out bangunan di lapangan.',
                    ],
                ],
            ],
            'workflow_steps' => [
                [
                    'step'  => '01',
                    'title' => 'Evaluasi Area Survei & Pemilihan Metode Terbaik',
                    'desc'  => 'Tim mengevaluasi lokasi kabupaten/provinsi serta koordinat wilayah kerja, kondisi tutupan vegetasi, relief elevasi, ruang udara, dan menentukan apakah proyek memerlukan Drone LiDAR, Fotogrametri, atau kombinasi keduanya.',
                ],
                [
                    'step'  => '02',
                    'title' => 'Pengikatan Jaring Kontrol Tanah (BM, GCP & ICP)',
                    'desc'  => 'Pemasangan stasiun Base GNSS dan pengukuran Ground Control Point (GCP) serta Independent Check Point (ICP) yang mengacu pada titik referensi lokal atau Jaring Kontrol Geodesi Nasional.',
                ],
                [
                    'step'  => '03',
                    'title' => 'Akuisisi Udara Terkendali Menggunakan Armada UAV',
                    'desc'  => 'Penerbangan misi otonom menggunakan wahana seperti Fixed-Wing VTOL DELTAV untuk koridor/area luas atau platform multirotor MULTIPURPOSE pada area berbukit rapat, dipantau lewat FDS Station.',
                ],
                [
                    'step'  => '04',
                    'title' => 'Trajectory PPK, Boresight & Strip Adjustment',
                    'desc'  => 'Penghitungan lintasan Post-Processed Kinematic (PPK), sinkronisasi IMU, koreksi boresight, dan penyelarasan antarlajur terbang (strip adjustment) untuk menghilangkan deviasi antar lintasan.',
                ],
                [
                    'step'  => '05',
                    'title' => 'Klasifikasi ASPRS, Verifikasi ICP & Serah Terima File',
                    'desc'  => 'Penyaringan noise, klasifikasi titik Ground/Vegetasi/Bangunan, ekstraksi DTM/DSM/Kontur/Ortofoto, pengujian selisih koordinat terhadap ICP, dan penyerahan paket data beserta laporan kualitas.',
                ],
            ],
            'software_integrations' => [
                'eyebrow'  => 'Kompatibilitas File & Alur Kerja Engineering',
                'title'    => 'Format Data Standar Industri Siap Impor ke Software Teknis Anda',
                'subtitle' => 'Setiap pekerjaan pemetaan udara dan survei LiDAR diserahkan dalam format terbuka tanpa penguncian vendor, siap dibuka tim engineering, arsitek, maupun analis GIS.',
                'formats'  => [
                    'Point Cloud Terklasifikasi (.LAS / .LAZ sesuai skema klasifikasi ASPRS)',
                    'Digital Terrain Model (DTM), DSM & DEM (.TIF GeoTIFF / .ASC / Grid)',
                    'Peta Garis Kontur, Situasi & Potongan Memanjang/Melintang (.DWG / .DXF CAD)',
                    'Citra Ortofoto Udara Tergeoreferensi (.TIF GeoTIFF / .ECW)',
                    'Layer Vektor GIS (.SHP ESRI Shapefile / .GeoJSON) & Laporan Uji GCP/ICP (.PDF / .XLSX)',
                ],
                'tools'    => ['AutoCAD Civil 3D', 'Global Mapper', 'QGIS & ArcGIS', 'Minescape / Surpac', 'HEC-RAS / Hidrologi', 'MicroStation / CAD'],
            ],
            'outputs' => [
                'Raw & Classified Point Cloud (.las/.laz) dengan pemisahan kelas tanah (Ground), vegetasi, bangunan, dan infrastruktur',
                'Digital Terrain Model (DTM) Bare Earth, Digital Surface Model (DSM), dan Digital Elevation Model (DEM)',
                'High-Resolution Orthophoto tergeoreferensi dengan skala dan koordinat sesuai acuan proyek',
                'Peta garis kontur elevasi dan peta situasi siap buka di perangkat lunak CAD (.dwg/.dxf) dan GIS (.shp/.geojson)',
                'Peta koridor infrastruktur, profil jaringan transmisi, batas pit/blok, atau masterplan kawasan pengembangan',
                'Perhitungan volume earthwork (cut and fill), stockpile reconciliation, atau analisis struktur kanopi hutan',
                'Laporan metodologi teknis, dokumentasi Survey Control Point (GCP/ICP), dan evaluasi pemeriksaan kualitas data',
            ],
            'cost_framework' => [
                'eyebrow'  => 'Struktur Anggaran & Penyusunan RAB',
                'title'    => 'Faktor Penentu Biaya Jasa Pemetaan LiDAR & Foto Udara',
                'intro'    => 'Biaya survei geospasial sangat bergantung pada karakteristik medan dan kompleksitas deliverables di dalam KAK/TOR proyek. Memahami lima komponen utama berikut mencegah pembengkakan anggaran di tengah pelaksanaan:',
                'factors'  => [
                    [
                        'name' => 'Luas Wilayah & Geometri Poligon (Blok Kompak vs Koridor)',
                        'desc' => 'Blok kompak ratusan hektar menekan biaya rata-rata per hektar, sedangkan koridor memanjang (jalan, pipa, SUTET) menuntut perpindahan base station dan titik lepas landas lebih sering.',
                    ],
                    [
                        'name' => 'Tutupan Kanopi, Kondisi Lahan & Kebutuhan Sensor',
                        'desc' => 'Hutan primer rapat membutuhkan kecepatan terbang lebih pelan dan sidelap lebih rapat pada LiDAR dibandingkan pemetaan ortofoto RGB di lahan konstruksi terbuka.',
                    ],
                    [
                        'name' => 'Akses Lokasi, Mobilisasi & Kondisi Tanah untuk GCP/ICP',
                        'desc' => 'Jarak mobilisasi dari hub operasional di Yogyakarta, sewa transportasi medan khusus 4WD/perahu, serta sulitnya pemasangan titik kontrol di gambut atau lembah curam.',
                    ],
                    [
                        'name' => 'Persyaratan Izin Terbang, K3/HSE & Kedalaman Output',
                        'desc' => 'Perbedaan biaya antara paket standar (Point Cloud + DTM + Kontur) dengan analisis tambahan seperti volume cut-and-fill, hidrologi detail, interval kontur sangat rapat, atau sertifikasi K3 site khusus.',
                    ],
                ],
                'kak_checklist' => [
                    'Informasi lokasi survei (kota/kabupaten, provinsi, atau koordinat area) serta perkiraan luasan.',
                    'Pilihan output yang diminta (Point Cloud LAS, DTM, DSM, garis kontur interval berapa meter, dan apakah perlu Ortofoto RGB).',
                    'Sistem koordinat dan datum acuan perusahaan (misal UTM WGS84 atau grid proyek lokal) serta ketersediaan titik BM.',
                    'Lokasi administrasi (Kabupaten/Provinsi), status izin kawasan/ruang udara, dan target waktu penyelesaian pekerjaan.',
                ],
            ],
            'eeat_proof' => [
                'eyebrow'  => 'Manufaktur UAV Lokal Terintegrasi',
                'title'    => 'Mengapa Bekerja Sama dengan Tim Geospasial & Manufaktur FDS?',
                'intro'    => 'Full Drone Solutions (PT Karya Solusi Angkasa) adalah produsen pesawat nirawak nasional di Sleman, Yogyakarta dengan sistem manajemen mutu ISO 9001:2015, sertifikasi TKDN + BMP 60,74% untuk lini pertanian FERTO, serta SNI 9199:2023 pada FERTO 15 & FERTO 22. Penguasaan rekayasa wahana sendiri memberikan jaminan kesiapan armada saat mobilisasi ke seluruh Indonesia.',
                'bullets'  => [
                    'Didukung lini wahana in-house seperti Fixed-Wing Hybrid VTOL DELTAV (daya jelajah 60 km) untuk survei koridor dan konsesi luas, serta wahana multirotor modular MULTIPURPOSE untuk area kompleks dan berbukit rapat.',
                    'Sistem Ground Control Station FDS Station berbahasa Indonesia dan protokol inspeksi teknis memastikan keandalan akuisisi di medan lapangan terpencil tanpa kendala pasokan suku cadang berkepanjangan.',
                    'Opsi kerja sama B2B fleksibel: mulai dari kontrak jasa pemetaan satu proyek, kontrak pemantauan berkala, hingga evaluasi investasi pengadaan armada UAV mandiri dan pelatihan tim survei perusahaan.',
                ],
                'portfolio_note' => 'Setiap proyek jasa foto udara dan LiDAR kami dokumentasikan secara terstruktur mencakup luasan aktual, parameter terbang, jumlah titik ikat GCP/ICP, laporan evaluasi ketelitian koordinat, dan format file terverifikasi.',
            ],
            'sector_links' => ['pertambangan', 'kehutanan-lingkungan', 'energi-infrastruktur', 'perencanaan', 'bencana-lingkungan'],
            'reading_links' => [
                ['label' => 'Platform Fixed-Wing VTOL DELTAV (Jelajah 60 km)', 'url' => home_url('/drone/deltav/')],
                ['label' => 'Platform UAV MULTIPURPOSE untuk Pemetaan & Inspeksi', 'url' => home_url('/drone/multipurpose/')],
                ['label' => 'Layanan Spesifik Sektor: Jasa Survey LiDAR Tambang', 'url' => home_url('/layanan/pertambangan/')],
                ['label' => 'Komponen Biaya Survei LiDAR di Indonesia (Artikel)', 'slug' => 'biaya-survei-lidar-indonesia'],
                ['label' => 'Panduan Teknis LiDAR untuk Tambang: Manfaat & Metode', 'slug' => 'lidar-untuk-tambang-manfaat-metode'],
            ],
            'faqs' => [
                [
                    'q' => 'Apa perbedaan paling mendasar antara jasa foto udara fotogrametri dan jasa pemetaan LiDAR?',
                    'a' => 'Foto udara fotogrametri mengolah ribuan foto kamera RGB bertampalan dan ideal menghasilkan ortofoto visual di lahan terbuka bersih, namun tidak dapat menembus tajuk pohon. Jasa pemetaan LiDAR menggunakan pulsa sinar laser aktif yang mampu menyusup lewat celah daun untuk memetakan permukaan tanah murni (Bare Earth DTM) dan kontur akurat di bawah hutan maupun perkebunan.',
                ],
                [
                    'q' => 'Apakah sinar laser LiDAR dapat memetakan utilitas pipa atau kabel di bawah tanah?',
                    'a' => 'Tidak. Sinar laser LiDAR bersifat optik di atas permukaan—teknologi ini menembus celah kanopi pepohonan dan (pada konfigurasi khusus perairan) menembus air jernih dangkal, namun tidak menembus tanah padat atau lapisan beton. Pemetaan utilitas bawah tanah memerlukan metode geofisika terpisah seperti Ground Penetrating Radar (GPR).',
                ],
                [
                    'q' => 'Data apa saja yang perlu saya siapkan untuk mengajukan permintaan penawaran harga?',
                    'a' => 'Siapkan informasi lokasi kabupaten/provinsi atau koordinat area proyek, estimasi luas atau panjang koridor, target interval kontur atau kebutuhan output (misalnya LAS, DTM, DSM, Ortofoto), serta target jadwal kerja untuk dikirim melalui halaman Kontak atau WhatsApp resmi FDS.',
                ],
                [
                    'q' => 'Kapan proyek lebih efisien memakai Drone LiDAR dibandingkan Backpack SLAM LiDAR?',
                    'a' => 'Untuk konsesi seluas puluhan hingga ribuan hektar, pit tambang terbuka, koridor transmisi, atau kawasan hutan/perkebunan, UAV Drone LiDAR jauh lebih cepat dan hemat total man-days dibanding operator berjalan kaki menggendong Backpack LiDAR. Backpack LiDAR justru dipakai sebagai pelengkap lokal di terowongan, ruang tertutup pabrik, atau lorong rapat tanpa sinyal langit sama sekali.',
                ],
                [
                    'q' => 'Apakah titik kontrol tanah (GCP dan ICP) tetap wajib dipasang jika memakai drone RTK/PPK?',
                    'a' => 'Untuk pemetaan teknik yang menjadi dasar perhitungan anggaran konstruksi atau rekonsiliasi volume, pengikatan GCP dan uji ICP di tanah tetap sangat direkomendasikan agar seluruh koordinat selaras tepat ke datum dan Bench Mark (BM) resmi lokasi proyek sekaligus memvalidasi mutu elevasi secara objektif.',
                ],
                [
                    'q' => 'Format file apa saja yang diserahkan kepada tim teknis atau konsultan kami?',
                    'a' => 'Hasil survei diserahkan dalam format standar terbuka berupa point cloud terklasifikasi (.las/.laz), Digital Terrain Model dan Digital Surface Model (GeoTIFF), garis kontur (.dwg/.dxf CAD dan .shp/.geojson GIS), ortofoto tergeoreferensi, serta laporan verifikasi titik kontrol GCP/ICP.',
                ],
                [
                    'q' => 'Apakah Full Drone Solutions melayani mobilisasi survei ke luar Pulau Jawa?',
                    'a' => 'Ya. Tim operasional kami melayani survei di seluruh wilayah Indonesia termasuk Kalimantan, Sumatra, Sulawesi, Nusa Tenggara, hingga Papua, serta dapat memberikan opsi pengadaan unit UAV dan pelatihan bagi perusahaan yang membutuhkan operasi jangka panjang.',
                ],
            ],
            'quote_defaults' => [
                'eyebrow'      => 'Permintaan Penawaran Jasa LiDAR & Foto Udara',
                'title'        => 'Konsultasikan Kebutuhan Proyek untuk Rekomendasi Metode & Estimasi RAB',
                'subtitle'     => 'Sampaikan lokasi proyek, perkiraan luasan area, kondisi tutupan vegetasi, dan target output agar tim geospasial FDS menyusun evaluasi awal.',
                'wa_prefill'   => 'Halo tim Geospasial FDS, kami ingin berkonsultasi mengenai jasa pemetaan LiDAR / foto udara drone.',
                'placeholder'  => 'Jelaskan lokasi (kota/provinsi), perkiraan luas hektar atau km koridor, kondisi vegetasi (terbuka/hutan/sawit), dan format output yang diinginkan (.las, DTM, kontur DWG, ortofoto)...',
            ],
            'seo' => [
                'meta_title'   => 'Jasa Pemetaan LiDAR & Survey Foto Udara | FDS',
                'meta_desc'    => 'Jasa pemetaan LiDAR dan foto udara FDS menghasilkan point cloud LAS, bare earth DTM, DSM, ortofoto, dan peta kontur presisi untuk proyek industri.',
                'service_type' => 'Aerial Photogrammetry, Drone Mapping and Drone LiDAR Survey',
            ],
            'placeholders' => [
                'Model/seri sensor LiDAR & GNSS Geodetik spesifik (pulse rate, jangkauan laser, jumlah return, dan spesifikasi akurasi pabrikan) setelah unit resmi ditetapkan manajemen.',
                'Rentang tarif indikatif per hektar atau kebijakan minimum proyek resmi apabila manajemen membuka patokan biaya di website.',
                'Minimal 2 studi kasus proyek foto udara & LiDAR resmi yang telah selesai beserta izin publikasi lokasi, luas ha, nilai uji RMSE ICP, dan dokumentasi visual asli.',
                'Ketentuan estimasi durasi rata-rata pengerjaan lapangan serta pemrosesan per skala area (mis. per blok 100 ha atau per koridor km).',
            ],
        ],

        // =====================================================================
        // KATEGORI 02: JASA FOTO MULTISPECTRAL DAN HYPERSPECTRAL
        // =====================================================================
        'jasa-multispectral-hyperspectral' => [
            'slug'         => 'jasa-multispectral-hyperspectral',
            'url'          => home_url('/layanan/jasa-multispectral-hyperspectral/'),
            'short_title'  => 'Jasa Foto Multispectral dan Hyperspectral',
            'h1'           => 'Jasa Drone Multispectral & Hyperspectral Analisis Vegetasi',
            'subtitle'     => 'Pemetaan indeks kesehatan tanaman presisi, identifikasi variasi blok, dan asesmen ekologi kehutanan berbasis data spektral.',
            'hero_summary' => 'Layanan jasa drone multispectral dan hyperspectral terintegrasi sensor FigSpec FS50/FS60 untuk memproduksi peta indeks vegetasi (seperti NDVI), deteksi stres blok kebun, serta inventarisasi ekologi hutan.',
            'intro_paragraphs' => [
                'Mata manusia dan kamera RGB standar hanya menangkap cahaya tampak pada pita merah, hijau, dan biru. Ketika pohon kelapa sawit, tanaman tebu, padi, atau tegakan hutan mulai mengalami stres kekurangan hara, defisiensi air, atau gangguan kesehatan tanaman, gejala visual sering baru muncul setelah pertumbuhan blok menurun jauh. Jasa drone multispectral dan hyperspectral dari Full Drone Solutions menjembatani kesenjangan pemantauan tersebut lewat pengukuran reflektansi spektral terkalibrasi.',
                'Dengan mengintegrasikan wahana UAV industri bersama sensor multispectral/hyperspectral seperti lini FigSpec FS50 / FS60 dan sistem kendali FDS Ground Control Station yang tercantum dalam katalog teknologi kami, data reflektansi daun direkam serentak di atas ratusan hingga ribuan hektar konsesi secara sistematis.',
                'Hasil pemetaan tidak berhenti pada gambar berwarna semata, melainkan diolah tim agronomi dan GIS menjadi peta indeks vegetasi seperti NDVI, identifikasi area kurang seragam maupun empty spot, validasi populasi tegakan, hingga kolaborasi pemodelan stok karbon bila dipadukan bersama data survei LiDAR dan plot sampel lapangan.',
            ],
            'challenge_section' => [
                'eyebrow'  => 'Mengapa Kamera RGB Biasa Tidak Cukup di Sektor Agronomi & Ekologi?',
                'title'    => 'Mengukur Respons Reflektansi Tajuk Sebelum Penurunan Produktivitas Terjadi',
                'paragraphs' => [
                    'Jaringan klorofil daun yang sehat menyerap kuat spektrum merah (Red) dan memantulkan sangat tinggi spektrum inframerah-dekat (Near-Infrared / NIR). Ketika tanaman mengalami tekanan nutrisi, genangan air berlebih, atau gangguan biologis, pantulan cahaya pada band NIR dan Red-Edge bergeser lebih cepat dibandingkan perubahan warna daun yang bisa diamati secara kasatmata.',
                    'Melalui jasa foto udara multispectral serta hyperspectral resolusi tinggi, perusahaan perkebunan maupun pengelola kawasan hutan memperoleh pemetaan variasi kondisi vegetasi di tingkat blok secara objektif. Informasi ini membantu manajemen memprioritaskan kunjungan verifikasi agronomi lapangan dan mendukung perencanaan aplikasi input presisi seperti penyemprotan terarah menggunakan agricultural drone.',
                ],
                'pain_points' => [
                    [
                        'title' => 'Tinjauan Lapangan Manual Sulit Menjangkau Blok Luas Merata',
                        'desc'  => 'Inspeksi sampling barisan tanaman dengan berjalan kaki hanya memeriksa fraksi kecil area sehingga anomali di tengah blok kerap terlewat.',
                    ],
                    [
                        'title' => 'Aplikasi Pupuk & Perlakuan Lahan Bersifat Merata Tanpa Zonasi',
                        'desc'  => 'Tanpa peta variasi vigor tanaman antarblok, pemupukan dan perawatan dilakukan seragam padahal kebutuhan aktual di tiap sub-blok berbeda.',
                    ],
                    [
                        'title' => 'Kurangnya Baseline Historis Kesehatan Ekosistem Hutan & Reklamasi',
                        'desc'  => 'Evaluasi keberhasilan revegetasi tambang maupun kondisi hutan memerlukan perbandingan indeks spektral multi-periode yang terstandar.',
                    ],
                ],
            ],
            'capabilities' => [
                [
                    'name' => 'Crop Health Monitoring & Pemetaan Indeks NDVI',
                    'desc' => 'Akuisisi citra multispectral untuk memantau kesehatan tanaman, menyusun peta indeks vegetasi seperti NDVI serta indeks spektral relevan lainnya, dan menandai variasi kondisi tajuk per blok.',
                ],
                [
                    'name' => 'Plantation Performance, Crop Spacing & Empty Spot Detection',
                    'desc' => 'Integrasi citra multispectral dan foto udara resolusi tinggi guna mengidentifikasi area kosong (empty spot), area pertumbuhan tidak seragam, serta memvalidasi estimasi populasi dan sebaran tanaman.',
                ],
                [
                    'name' => 'Forest Biomass, Carbon & Ecological Health Assessment',
                    'desc' => 'Penyusunan metode survei UAV multispectral maupun hyperspectral yang dapat dikombinasikan dengan data LiDAR serta pengukuran plot lapangan untuk pemantauan degradasi hutan, pemetaan mangrove, dan kajian stok karbon.',
                ],
                [
                    'name' => 'Historical Vegetation Database & Sinkronisasi Tindakan Presisi',
                    'desc' => 'Pengolahan perubahan indeks vegetasi seri waktu ke dalam plantation atau environmental monitoring dashboard serta penautan data ke sistem perencanaan pemeliharaan tanaman.',
                ],
            ],
            'method_comparison' => [
                'eyebrow'  => 'Perbandingan Sensor Udara untuk Vegetasi',
                'title'    => 'Memilih Kamera RGB, Multispectral, Hyperspectral, atau UAV LiDAR',
                'subtitle' => 'Setiap sensor membaca sifat fisik berbeda dari tanaman. Gunakan panduan berikut untuk memilih teknologi survei yang sesuai dengan tujuan riset atau operasional Anda.',
                'columns'  => ['Aspek Analisis', 'Jasa Drone Multispectral', 'Jasa Drone Hyperspectral', 'Foto Udara Kamera RGB', 'UAV / Drone LiDAR'],
                'rows'     => [
                    [
                        'Jumlah Pita Spektrum yang Direkam',
                        'Beberapa pita diskrit terkalibrasi (umumnya mencakup tampak + Red-Edge + NIR sesuai konfigurasi sensor).',
                        'Puluhan hingga ratusan pita spektral sempit yang berurutan secara kontinu untuk analisis reflektansi rinci.',
                        '3 pita warna tampak standar (Merah, Hijau, Biru) tanpa pita spektral inframerah-dekat terkalibrasi.',
                        'Pulsa laser tunggal inframerah untuk mengukur jarak dan koordinat bentuk 3D, bukan spektrum warna biokimia.',
                    ],
                    [
                        'Fokus Informasi yang Diperoleh',
                        'Pemetaan indeks vegetasi (seperti NDVI), variasi vigor kanopi, empty spot, dan pemantauan blok berkala.',
                        'Identifikasi karakteristik spektral sangat spesifik untuk riset botani lanjutan, mineral/vegetasi detail, dan asesmen khusus.',
                        'Citra visual jelas batas lahan, sensus visual pohon, jalan blok, drainase permukaan, dan dokumentasi progres.',
                        'Tinggi fisik aktual tajuk pohon, kepadatan struktur vertikal tajuk, volume biomassa geometris, serta kontur tanah dasar.',
                    ],
                    [
                        'Skala Cakupan Harian & Pengolahan Data',
                        'Cepat & efisien untuk ratusan hektar blok perkebunan operasional dengan alur olah ortomosaik spektral terstandar.',
                        'Ukuran file sangat besar dan menuntut pemrosesan radiometrik serta komputasi khusus per area target studi.',
                        'Paling ringkas dari sisi akuisisi dan volume data untuk pemetaan batas dan ortofoto visual.',
                        'Efektif untuk area berhutan yang sekaligus butuh topografi tanah asli serta struktur tinggi pohon 3D.',
                    ],
                    [
                        'Aplikasi Industri Paling Sesuai',
                        'Perkebunan kelapa sawit, tebu, padi pangan, hortikultura, reklamasi pascatambang, dan pemantauan kesehatan blok.',
                        'Proyek riset R&D agronomi, identifikasi variasi stres spesifik, kajian kehutanan ilmiah, dan analisis lingkungan khusus.',
                        'Peta kerja estate, pemetaan infrastruktur jalan kebun, dan inventarisasi visual umum.',
                        'Estimasi tinggi tegakan hutan/biomassa (bersama multispectral) dan pembuatan peta saluran drainase di bawah tajuk.',
                    ],
                ],
            ],
            'workflow_steps' => [
                [
                    'step'  => '01',
                    'title' => 'Identifikasi Komoditas, Target Indeks & Cakupan Blok',
                    'desc'  => 'Diskusi mengenai jenis tanaman/vegetasi, umur tanam, tujuan pemantauan (kesehatan blok, empty spot, atau ekologi hutan), serta peninjauan peta dan luasan blok kebun.',
                ],
                [
                    'step'  => '02',
                    'title' => 'Perencanaan Misi Spektral & Persyaratan Kalibrasi Lapangan',
                    'desc'  => 'Pengaturan ketinggian terbang, pertampalan citra (overlap/sidelap), jendela pencahayaan matahari optimal, serta prosedur kalibrasi radiometrik lapangan menggunakan sensor cahaya / panel referensi.',
                ],
                [
                    'step'  => '03',
                    'title' => 'Akuisisi Udara Terintegrasi Sensor FigSpec & FDS GCS',
                    'desc'  => 'Pengumpulan data multispectral atau hyperspectral di atas blok target melalui wahana UAV FDS dan sistem kendali FDS Ground Control Station, disertai pencatatan titik ikat spasial.',
                ],
                [
                    'step'  => '04',
                    'title' => 'Kalibrasi Radiometrik, Ortomosaik & Kalkulasi Indeks Vegetasi',
                    'desc'  => 'Koreksi reflektan, penyusunan ortomosaik multispectral/hyperspectral, serta kalkulasi peta indeks vegetasi (seperti NDVI) dan klasifikasi zonasi variasi kanopi.',
                ],
                [
                    'step'  => '05',
                    'title' => 'Validasi Spasial, Laporan Agronomi/GIS & Tindak Lanjut Lapangan',
                    'desc'  => 'Penyerahan raster indeks, peta zonasi blok, dan lapisan vektor GIS untuk menandai prioritas pengecekan di lapangan maupun dukungan penyemprotan presisi menggunakan drone FERTO.',
                ],
            ],
            'software_integrations' => [
                'eyebrow'  => 'Integrasi GIS Perkebunan & Agronomi',
                'title'    => 'Output Peta Indeks & Layer Vektor Siap Pakai Tim Estate & Ekologi',
                'subtitle' => 'Seluruh peta spektral diserahkan dalam format geospasial standar agar tim GIS internal dan manajer kebun dapat melakukan overlay langsung di atas batas blok perusahaan.',
                'formats'  => [
                    'Multispectral / Hyperspectral Reflectance Orthomosaic terkalibrasi (.TIF GeoTIFF multiband)',
                    'Peta Indeks Vegetasi Terklasifikasi, mis. NDVI (.TIF GeoTIFF & layout peta .PDF)',
                    'Poligon Zonasi Variasi Vegetasi & Titik Indikasi Empty Spot (.SHP / .GeoJSON)',
                    'Integrasi Data Spektral dengan Point Cloud / Plot Lapangan (untuk kajian biomassa & karbon)',
                    'Historical Vegetation Time-Series Database untuk perbandingan antarperiode pemantauan',
                ],
                'tools'    => ['QGIS & ArcGIS', 'Pix4Dfields / Agisoft Metashape', 'Google Earth Pro', 'ENVI / Analitik Spektral', 'FDS Ground Control Station'],
            ],
            'outputs' => [
                'Citra ortomosaik multispectral / hyperspectral tergeoreferensi dan terkalibrasi',
                'Peta indeks vegetasi (NDVI dan indeks spektral relevan sesuai ruang lingkup proyek)',
                'Peta klasifikasi variasi kondisi pertumbuhan dan area indikasi pertumbuhan tanaman tidak seragam',
                'Identifikasi area kosong (empty spot) serta dukungan validasi estimasi populasi tanaman per blok',
                'Integrasi data spektral dengan data survei LiDAR dan plot lapangan untuk pemantauan degradasi hutan maupun estimasi biomassa',
                'Historical vegetation database dan visualisasi dashboard monitoring perkembangan blok antarwaktu',
            ],
            'cost_framework' => [
                'eyebrow'  => 'Variabel Perencanaan Biaya Survei Spektral',
                'title'    => 'Faktor Penentu Anggaran Pemetaan Multispectral & Hyperspectral',
                'intro'    => 'Besaran investasi survei spektral disesuaikan berdasarkan metode sensor, luasan blok, dan frekuensi pemantauan yang disepakati dengan tim kebun atau riset Anda:',
                'factors'  => [
                    [
                        'name' => 'Pilihan Sensor (Multispectral Operasional vs Hyperspectral Riset)',
                        'desc' => 'Pengolahan ratusan pita kontinu hyperspectral memerlukan waktu akuisisi, kalibrasi, serta kapasitas pemrosesan jauh lebih besar dibanding paket multispectral indeks NDVI.',
                    ],
                    [
                        'name' => 'Luas Area Blok & Jarak Sebaran Lokasi Divisi/Estate',
                        'desc' => 'Blok konsesi yang menyatu menurunkan biaya mobilisasi antarblok dibanding kebun atau plot sampel kehutanan yang tersebar berjauhan.',
                    ],
                    [
                        'name' => 'Frekuensi Pemantauan (Baseline Tunggal vs Siklus Berkala)',
                        'desc' => 'Pemantauan multi-waktu (sebelum pemupukan vs pascaperlakuan) mendukung pembuatan basis data historis vegetasi per kuartal atau per siklus tanam.',
                    ],
                    [
                        'name' => 'Kebutuhan Integrasi Lintas Sensor & Pelatihan Unit Mandiri',
                        'desc' => 'Apakah pekerjaan digabungkan dengan survei foto RGB/LiDAR, verifikasi plot lapangan, atau skema penyediaan armada pemetaan & drone sprayer seri FERTO untuk operasional kebun mandiri.',
                    ],
                ],
                'kak_checklist' => [
                    'Jenis komoditas atau ekosistem target (kelapa sawit, tebu, padi, hortikultura, hutan alam, atau mangrove).',
                    'Lokasi kabupaten/provinsi blok/divisi yang ingin dipetakan serta estimasi total hektar.',
                    'Tujuan analisis utama (peta NDVI & kesehatan blok, deteksi empty spot, riset hyperspectral, atau estimasi biomassa).',
                    'Preferensi kerja sama: jasa survei oleh tim FDS atau pengadaan perangkat sensor & pelatihan tim estate internal.',
                ],
            ],
            'eeat_proof' => [
                'eyebrow'  => 'Ekosistem Agrikultur & Geospasial Terintegrasi',
                'title'    => 'Dari Pemantauan Kesehatan Tanaman hingga Solusi Aplikasi Lapangan FDS',
                'intro'    => 'Berbeda dari penyedia jasa pemetaan tunggal, Full Drone Solutions (PT Karya Solusi Angkasa) menggabungkan kapabilitas manufaktur perusahaan bersertifikat manajemen mutu ISO 9001:2015 serta produk berstandar TKDN 60,74% pada seri FERTO dan SNI 9199:2023 pada FERTO 15 & FERTO 22 mulai dari pemantauan hingga perangkat aplikasi presisi.',
                'bullets'  => [
                    'Katalog teknologi kami mencakup integrasi sensor spektral FigSpec FS50 / FS60 bersama platform pemetaan Fixed-Wing VTOL DELTAV dan sistem kendali berbahasa Indonesia FDS Ground Control Station.',
                    'Temuan zona kebutuhan perlakuan dari peta indeks multispectral dapat langsung dihubungkan ke rencana aplikasi penyemprotan atau penyebaran presisi memakai lini drone pertanian FERTO (5L, 10L, 15L hingga seri komersial lainnya).',
                    'Tim engineering kami berkolaborasi dengan tim estate, agronomi, dan riset ekologi guna memastikan luaran data spektral diterjemahkan menjadi informasi yang realistis di lapangan (ground truthing tetap penting untuk konfirmasi penyebab spesifik).',
                ],
                'portfolio_note' => 'Dokumentasi pemetaan multispectral dan hyperspectral memuat identitas komoditas/ekosistem, luasan blok, waktu jendela pencahayaan akuisisi, daftar peta indeks yang dihasilkan, serta catatan pengecekan silang lapangan.',
            ],
            'sector_links' => ['perkebunan-pertanian', 'kehutanan-lingkungan', 'bencana-lingkungan'],
            'reading_links' => [
                ['label' => 'Solusi Sektor: Perkebunan, Sawit & Pertanian Presisi', 'url' => home_url('/layanan/perkebunan-pertanian/')],
                ['label' => 'Solusi Sektor: Kehutanan, Biomassa & Karbon Hutan', 'url' => home_url('/layanan/kehutanan-lingkungan/')],
                ['label' => 'Lini Drone Sprayer Pertanian FERTO (5L, 10L, 15L–50L)', 'url' => home_url('/kategori-drone/agrikultur/')],
                ['label' => 'Fixed-Wing VTOL DELTAV untuk Akuisisi Lahan Luas', 'url' => home_url('/drone/deltav/')],
                ['label' => 'Uji Lapangan Drone Pertanian FDS di Lahan Tebu 500 Ha', 'slug' => 'ferto-22l-berhasil-uji-lapangan-di-lahan-tebu-500-ha-jawa-timur'],
            ],
            'faqs' => [
                [
                    'q' => 'Apa perbedaan utama jasa drone multispectral, hyperspectral, dan kamera foto udara RGB?',
                    'a' => 'Kamera RGB hanya merekam tiga warna cahaya tampak (merah, hijau, biru). Multispectral merekam beberapa band cahaya terkalibrasi termasuk spektrum inframerah-dekat (NIR/Red-Edge) untuk menghitung indeks vegetasi seperti NDVI, sedangkan hyperspectral merekam puluhan hingga ratusan band spektral sempit kontinu untuk studi reflektansi spesifik tingkat lanjut.',
                ],
                [
                    'q' => 'Apakah peta indeks NDVI otomatis memberi tahu jenis hama atau nama penyakit tanaman?',
                    'a' => 'Peta NDVI dan indeks vegetasi sangat efektif menandai lokasi blok atau zona tanaman yang mengalami penurunan vigor/stres lebih cepat dibanding mata manusia. Namun, identifikasi penyebab pastinya—apakah karena kekurangan hara tertentu, defisiensi drainase, atau serangan organisme pengganggu—tetap memerlukan verifikasi langsung di titik temuan oleh tim agronomi lapangan.',
                ],
                [
                    'q' => 'Teknologi sensor apa yang dirujuk dalam layanan spektral Full Drone Solutions?',
                    'a' => 'Dalam arsitektur solusi perkebunan dan pertanian FDS, pemetaan spektral merujuk pada penggunaan sensor seri FigSpec FS50 / FS60 yang diintegrasikan pada platform wahana UAV dan sistem kendali FDS Ground Control Station sesuai kebutuhan proyek.',
                ],
                [
                    'q' => 'Apakah hasil pemetaan multispectral bisa dipadukan dengan LiDAR untuk sektor kehutanan?',
                    'a' => 'Ya. Untuk asesmen kehutanan, degradasi hutan, maupun studi stok karbon, citra multispectral atau hyperspectral dipadukan bersama data struktur kanopi 3D dari UAV LiDAR serta hasil pengukuran plot lapangan guna memperkuat model spasial di dalam GIS.',
                ],
                [
                    'q' => 'Bisakah perusahaan perkebunan membeli unit drone beserta sensor dan pelatihan tim internalnya?',
                    'a' => 'Tentu. Selain jasa survei proyek, FDS melayani pengadaan unit UAV pemetaan, sensor spektral, drone sprayer/spreader seri FERTO untuk tindak lanjut pemeliharaan tanaman, beserta program pelatihan tim operasional kebun.',
                ],
                [
                    'q' => 'Informasi apa yang dibutuhkan saat kami meminta penawaran survei multispectral?',
                    'a' => 'Silakan sampaikan lokasi provinsi/kabupaten atau koordinat area, jenis tanaman/komoditas, perkiraan luas hektar, serta tujuan analisis (misalnya monitoring NDVI, deteksi empty spot, atau riset hyperspectral) melalui halaman Kontak atau WhatsApp resmi FDS.',
                ],
            ],
            'quote_defaults' => [
                'eyebrow'      => 'Konsultasi Survei Multispectral & Hyperspectral',
                'title'        => 'Rencanakan Pemetaan Kesehatan Tanaman atau Ekologi Blok Anda',
                'subtitle'     => 'Kirimkan jenis komoditas, perkiraan luasan hektar, serta tujuan analisis spektral (multispectral NDVI / hyperspectral) untuk mendapatkan rancangan teknis dari tim FDS.',
                'wa_prefill'   => 'Halo tim FDS, kami ingin berkonsultasi mengenai layanan jasa drone multispectral / hyperspectral.',
                'placeholder'  => 'Jelaskan jenis tanaman/ekosistem (mis. kelapa sawit, tebu, padi, mangrove/hutan), luas area hektar, frekuensi pantau, dan apakah digabung dengan LiDAR atau sprayer...',
            ],
            'seo' => [
                'meta_title'   => 'Jasa Drone Multispectral, Hyperspectral & NDVI | FDS',
                'meta_desc'    => 'Layanan jasa drone multispectral dan hyperspectral FDS berbasis FigSpec FS50/FS60 untuk peta indeks vegetasi NDVI, kesehatan sawit, dan ekologi hutan.',
                'service_type' => 'Multispectral and Hyperspectral Aerial Imaging Service',
            ],
            'placeholders' => [
                'Spesifikasi rentang panjang gelombang (nanometer), jumlah band spektral aktif, dan resolusi GSD standar konfigurasi sensor FigSpec FS50 / FS60 milik FDS.',
                'Daftar lengkap indeks vegetasi terkalibrasi yang dijamin dalam paket standar (mis. NDVI, NDRE, GNDVI, OSAVI) serta SOP panel kalibrasi radiometrik lapangan.',
                'Kebijakan luasan hektar minimal, kisaran tarif per hektar/paket, dan studi kasus perkebunan/kehutanan terverifikasi yang diizinkan klien untuk dipublikasikan.',
            ],
        ],

        // =====================================================================
        // KATEGORI 03: PELATIHAN, BOOTCAMP, DAN SERTIFIKASI
        // =====================================================================
        'pelatihan-sertifikasi' => [
            'slug'         => 'pelatihan-sertifikasi',
            'url'          => home_url('/layanan/pelatihan-sertifikasi/'),
            'short_title'  => 'Pelatihan, Bootcamp, dan Sertifikasi',
            'h1'           => 'Pelatihan Pilot Drone, Bootcamp & Pendampingan Sertifikasi UAV',
            'subtitle'     => 'Peningkatan kompetensi pilot, operator survei pemetaan, tim agrikultur presisi, dan unit inspeksi perusahaan.',
            'hero_summary' => 'Program pelatihan pengoperasian UAV industri, bootcamp aplikasi pemetaan maupun pertanian presisi, serta pembinaan persiapan sertifikasi pilot beracuan regulasi DKPPU CASR Part 107 bersama tim engineer FDS.',
            'intro_paragraphs' => [
                'Keberhasilan implementasi teknologi Unmanned Aerial Vehicle (UAV) di perusahaan tambang, perkebunan, kehutanan, infrastruktur, maupun instansi pemerintah sangat ditentukan oleh kesiapan sumber daya manusia yang menerbangkan wahana dan mengolah datanya. Investasi perangkat keras bernilai tinggi tidak akan optimal bila tim internal belum menguasai prosedur keselamatan ruang udara, manajemen baterai, rencana misi otonom, dan standar pengolahan output lapangan.',
                'Full Drone Solutions (PT Karya Solusi Angkasa) menyelenggarakan kategori layanan Pelatihan, Bootcamp, dan Sertifikasi yang dirancang khusus untuk kebutuhan operasional korporasi, BUMN, instansi pemerintah, dan operator profesional. Materi disusun langsung oleh tim manufaktur dan praktisi lapangan yang merancang serta menguji wahana UAV buatan dalam negeri.',
                'Peserta tidak sekadar belajar menerbangkan unit secara manual, melainkan dibekali pemahaman menyeluruh mulai dari pengenalan sistem avionik, perencanaan jalur terbang otomatis di FDS Ground Control Station, mitigasi kondisi darurat, hingga alur kerja aplikasi sesuai sektor industri masing-masing.',
            ],
            'challenge_section' => [
                'eyebrow'  => 'Mengapa Perusahaan Membutuhkan Program Pelatihan Terstruktur?',
                'title'    => 'Menjembatani Kesenjangan antara Kemampuan Terbang Dasar dan Operasi Industri',
                'paragraphs' => [
                    'Menerbangkan drone hobi untuk dokumentasi singkat sangat berbeda dengan mengoperasikan drone pertanian sprayer muatan puluhan liter di perkebunan sawit, menjalankan misi Fixed-Wing VTOL melintasi lembah tambang, atau melakukan inspeksi visual dekat menara tegangan tinggi. Setiap misi industri memiliki profil risiko K3, regulasi ketinggian serta jarak pandang, dan standar pemeriksaan pra-terbang (pre-flight inspection) yang ketat.',
                    'Selain aspek terbang, banyak organisasi mengalami kendala di hilir: pilot mampu mengudara, tetapi foto yang diambil kurang bertampalan sehingga gagal diproses menjadi peta ortofoto, atau dosis penyemprotan tidak merata karena pengaturan kecepatan dan ketinggian belum tepat. Program terstruktur kami menyatukan pemahaman operasional wahana, regulasi keselamatan udara Indonesia (mengacu pada ketentuan Direktorat Kelaikudaraan dan Pengoperasian Pesawat Udara / DKPPU CASR Part 107), serta praktik aplikasi sektoral.',
                ],
                'pain_points' => [
                    [
                        'title' => 'Tingginya Risiko Insiden Teknis oleh Operator Belum Terlatih',
                        'desc'  => 'Kurangnya pemahaman cek kalibrasi sensor, manajemen baterai LiPo/Smart Battery, dan prosedur failsafe sering memicu kerusakan unit maupun gangguan operasi site.',
                    ],
                    [
                        'title' => 'Ketidakpatuhan terhadap Aturan Ruang Udara & K3 Perusahaan',
                        'desc'  => 'Tim operasional memerlukan bekal pemahaman batas zona udara, tata cara registrasi wahana dan remote pilot sesuai regulasi penerbangan sipil Indonesia.',
                    ],
                    [
                        'title' => 'Data Akuisisi Gagal Memenuhi Standar Tim Engineering/Agronomi',
                        'desc'  => 'Tanpa pengetahuan perencanaan misi (overlap, sidelap, GSD, kecepatan semprot), hasil kerja lapangan sering harus diulang dan membuang biaya mobilisasi.',
                    ],
                ],
            ],
            'capabilities' => [
                [
                    'name' => 'Pelatihan Operasional Drone Industri & FDS GCS',
                    'desc' => 'Pengenalan arsitektur wahana UAV (multirotor, agricultural drone, hingga platform Fixed-Wing VTOL), pengoperasian perangkat lunak kendali FDS Ground Control Station, pre-flight checklist, teknik manuver aman, serta penanganan kondisi darurat.',
                ],
                [
                    'name' => 'Bootcamp Aplikasi Sektoral (Pemetaan & Pertanian Presisi)',
                    'desc' => 'Pelatihan praktik intensif terfokus pada skenario lapangan perusahaan—mencakup perencanaan misi drone mapping, fotogrametri area luas, inspeksi visual aset, hingga kalibrasi alur penyemprotan dan penyebaran menggunakan lini drone FERTO.',
                ],
                [
                    'name' => 'Persiapan & Pembinaan Sertifikasi Pilot (Acuan Regulasi DKPPU CASR 107)',
                    'desc' => 'Pembinaan materi teori keselamatan penerbangan sipil, klasifikasi ruang udara, meteorologi dasar, asesmen risiko penerbangan, serta evaluasi keterampilan terbang praktik sebagai bekal persiapan sertifikasi pilot remote.',
                ],
                [
                    'name' => 'Pelatihan Alur Pengolahan Data Spasial & Integrasi GIS',
                    'desc' => 'Pengenalan alur pemrosesan data mentah hasil akuisisi udara menuju citra ortofoto, peta blok, model elevasi permukaan (DTM/DSM), hingga standar kualitas penyerahan data ke sistem GIS perusahaan.',
                ],
            ],
            'method_comparison' => [
                'eyebrow'  => 'Pilihan Skema Penyelenggaraan',
                'title'    => 'Perbandingan Skema Workshop di Sleman, On-Site Perusahaan & Pendampingan Unit',
                'subtitle' => 'Format kelas disesuaikan dengan jumlah peserta, jenis wahana yang dioperasikan, serta kesiapan lapangan uji coba organisasi Anda.',
                'columns'  => ['Aspek Pelaksanaan', 'In-House Workshop di Fasilitas FDS (Sleman)', 'Custom On-Site Training (Lokasi Perusahaan)', 'Pendampingan Teknis Pasca-Pengadaan Armada'],
                'rows'     => [
                    [
                        'Lokasi & Fasilitas Uji Terbang',
                        'Diselenggarakan di kantor/workshop dan area uji terbang PT Karya Solusi Angkasa, Sleman, Yogyakarta.',
                        'Tim instruktur FDS datang langsung ke lokasi kebun, area tambang, atau lapangan perusahaan klien di Indonesia.',
                        'Terintegrasi dalam paket serah terima pengadaan unit UAV buatan FDS (FERTO, DELTAV, DELFRO, MULTIPURPOSE).',
                    ],
                    [
                        'Fokus Materi & Praktik',
                        'Teori ruang udara, simulasi kendali FDS GCS, pengenalan mekanik manufaktur, dan praktik lapangan dengan armada latihan.',
                        'Menyesuaikan karakteristik medan riil site perusahaan (kontur berbukit, blok sawit, kawasan industri, atau standar K3/HSE internal).',
                        'Memastikan tim operator internal klien siap mengoperasikan, merawat berkala, dan mengkalibrasi unit baru secara mandiri.',
                    ],
                    [
                        'Kecocokan Peserta',
                        'Kandidat pilot baru, koordinator operasi proyek, dan tim kecil perusahaan yang ingin mengakses fasilitas pabrik sekaligus.',
                        'Korporasi, BUMN, dinas pemerintah daerah, atau perkebunan dengan kelompok peserta operator internal yang siap ditempatkan di site.',
                        'Organisasi pengguna unit UAV FDS (diproduksi oleh PT Karya Solusi Angkasa yang menerapkan sistem manajemen mutu ISO 9001:2015; dengan sertifikasi produk TKDN 60,74% pada seri FERTO serta SNI 9199:2023 pada FERTO 15 & 22) yang membutuhkan transfer keahlian langsung kepada operator.',
                    ],
                ],
            ],
            'workflow_steps' => [
                [
                    'step'  => '01',
                    'title' => 'Asesmen Kompetensi Awal & Penentuan Jalur Program',
                    'desc'  => 'Pemetaan latar belakang peserta (pemula vs surveyor/agronom berpengalaman), jenis wahana target (sprayer, multirotor inspeksi, atau VTOL pemetaan), dan jumlah peserta.',
                ],
                [
                    'step'  => '02',
                    'title' => 'Penyesuaian Silabus Teori, Regulasi & Skenario Lapangan',
                    'desc'  => 'Penyusunan jadwal gabungan kelas teori keselamatan ruang udara, prosedur operasional standar, perencanaan misi FDS GCS, serta sesi praktik lapangan sesuai kebutuhan organisasi.',
                ],
                [
                    'step'  => '03',
                    'title' => 'Kelas Teori Keselamatan, Avionik & Simulasi Misi',
                    'desc'  => 'Pembekalan prinsip aerodinamika UAV, manajemen baterai, aturan penerbangan sipil CASR Part 107, pemeriksaan pra-terbang, dan perancangan jalur misi di software stasiun darat.',
                ],
                [
                    'step'  => '04',
                    'title' => 'Praktik Terbang Lapangan & Manajemen Kondisi Darurat',
                    'desc'  => 'Latihan lepas landas, misi otomatis otonom, pengambilan alih kendali manual dalam skenario abnormal, serta perawatan pasca-terbang di bawah pengawasan instruktur.',
                ],
                [
                    'step'  => '05',
                    'title' => 'Evaluasi Praktik, Ulasan Alur Data & Dokumen Kelulusan Internal',
                    'desc'  => 'Penilaian kecakapan operasional peserta dan evaluasi alur pengolahan hasil tangkapan data, disertai dokumen evaluasi dan saran kesiapan pengoperasian di lingkungan kerja.',
                ],
            ],
            'software_integrations' => [
                'eyebrow'  => 'Ekosistem Pembelajaran Teknis',
                'title'    => 'Perangkat Lunak, Regulasi & Wahana yang Dipelajari Selama Program',
                'subtitle' => 'Kurikulum difokuskan pada keterampilan praktis lintas perangkat lunak kendali wahana dan referensi keselamatan resmi di Indonesia.',
                'formats'  => [
                    'Sistem Kendali Misi Berbahasa Indonesia: FDS Station / FDS Ground Control Station',
                    'Modul Regulasi Penerbangan Sipil Tanpa Awak: Acuan DKPPU Kemenhub RI (CASR Part 107 / PM 37 & SIDOPI-GO)',
                    'Checklist Pre-Flight, Post-Flight, Maintenance Logbook & Prosedur Keselamatan Baterai',
                    'Alur Kerja Fotogrametri & GIS (persiapan overlap, penggunaan GCP, hingga verifikasi ortofoto)',
                ],
                'tools'    => ['FDS Station GCS', 'Seri Drone Pertanian FERTO', 'VTOL Mapping DELTAV', 'UAV MULTIPURPOSE', 'Portal SIDOPI-GO DKPPU', 'QGIS / Fotogrametri Dasar'],
            ],
            'outputs' => [
                'Modul dan materi pembelajaran operasional UAV serta prosedur keselamatan penerbangan industri',
                'Pengalaman praktik terstruktur pengoperasian wahana dan perencanaan misi otomatis menggunakan FDS Ground Control Station',
                'Pemahaman standar pre-flight inspection, manajemen baterai, pemeliharaan rutin, dan penanganan kondisi darurat',
                'Pembinaan pemahaman regulasi ruang udara dan kesiapan alur administrasi kompetensi pilot mengacu ketentuan DKPPU CASR 107',
                'Pendampingan praktik alur akuisisi data pemetaan maupun penyemprotan presisi sesuai sektor perusahaan peserta',
                'Rekomendasi jadwal pembinaan dan proposal kurikulum yang disesuaikan untuk tim operasional internal korporasi atau instansi',
            ],
            'cost_framework' => [
                'eyebrow'  => 'Variabel Penyusunan Penawaran Pelatihan',
                'title'    => 'Faktor Penentu Biaya Pelatihan, Bootcamp & Sertifikasi Korporasi',
                'intro'    => 'Program pelatihan disusun sesuai profil kebutuhan organisasi. Komponen berikut menjadi dasar penghitungan proposal pelatihan B2B dan instansi:',
                'factors'  => [
                    [
                        'name' => 'Jumlah Peserta & Rasio Pendampingan Instruktur',
                        'desc' => 'Pelatihan kelas kecil berfokus praktik intensif per pilot memiliki dinamika kebutuhan armada dan instruktur yang berbeda dibanding kelas pengenalan grup besar.',
                    ],
                    [
                        'name' => 'Jenis Wahana & Kompleksitas Aplikasi (Multirotor, Sprayer, atau VTOL)',
                        'desc' => 'Pelatihan pengoperasian drone pemetaan jarak jauh Fixed-Wing VTOL atau paket pemrosesan peta GIS membutuhkan struktur modul lebih mendalam daripada pengoperasian dasar.',
                    ],
                    [
                        'name' => 'Lokasi Pelaksanaan (Workshop Sleman vs On-Site Perusahaan)',
                        'desc' => 'Kelas di fasilitas Sleman memanfaatkan lahan uji terbang workshop FDS, sementara kelas on-site di lokasi site klien mencakup akomodasi serta pengiriman tim pengajar dan peralatan demo.',
                    ],
                    [
                        'name' => 'Durasi Program & Status Pengadaan Unit',
                        'desc' => 'Kebutuhan kelas reguler/bootcamp mandiri versus program transfer keahlian yang dibundel langsung bersama pembelian armada UAV resmi FDS.',
                    ],
                ],
                'kak_checklist' => [
                    'Jumlah perkiraan peserta serta peran kerja mereka (operator lapangan, tim survey, agronomi, atau inspeksi aset).',
                    'Fokus aplikasi pelatihan: pengoperasian drone pemetaan/GIS, drone pertanian sprayer/spreader, inspeksi aset, atau persiapan sertifikasi pilot.',
                    'Preferensi lokasi: mengikuti kelas di fasilitas Sleman, Yogyakarta atau pelatihan in-house (on-site) di lokasi perusahaan Anda.',
                    'Apakah peserta sudah memiliki unit drone sendiri/berencana mengadakan armada FDS, atau membutuhkan penyediaan unit praktik selama sesi kelas.',
                ],
            ],
            'eeat_proof' => [
                'eyebrow'  => 'Belajar Langsung dari Produsen UAV Nasional',
                'title'    => 'Mengapa Pelatihan di Full Drone Solutions Berbeda dari Kursus Umum?',
                'intro'    => 'Berpusat di Sleman, Yogyakarta, PT Karya Solusi Angkasa (FDS) memiliki sertifikasi sistem manajemen mutu ISO 9001:2015, TKDN + BMP 60,74% untuk lini pertanian FERTO, serta SNI 9199:2023 pada FERTO 15 dan FERTO 22. Peserta belajar langsung dari ekosistem rekayasa perancang wahana.',
                'bullets'  => [
                    'Pemahaman mekanik & avionik mendalam: peserta dilatih mengenali gejala anomali sistem kendali, perawatan baterai industrial, dan langkah troubleshooting dasar di lapangan agar siap saat bertugas di lokasi terpencil.',
                    'Software kendali FDS Station berbahasa Indonesia memudahkan operator berbagai tingkat keahlian dalam memahami indikator telemetri, batas zona aman, dan parameter misi.',
                    'Mendukung kebutuhan korporasi swasta maupun pengadaan instansi pemerintah/BUMN melalui skema resmi yang selaras dengan kebutuhan SDM operator di lapangan.',
                ],
                'portfolio_note' => 'Proposal pelatihan atau bootcamp disusun resmi memuat tujuan kompetensi, jadwal teori dan praktik harian, persyaratan keselamatan peserta, serta peralatan yang disiapkan selama sesi berlangsung.',
            ],
            'sector_links' => ['perkebunan-pertanian', 'pertambangan', 'energi-infrastruktur', 'perencanaan'],
            'reading_links' => [
                ['label' => 'Software Kendali Misi FDS Station Berbahasa Indonesia', 'slug' => 'ground-control-app-software-misi-drone-sepenuhnya-berbahasa-indonesia'],
                ['label' => 'Profil Fasilitas Manufaktur & Riset PT Karya Solusi Angkasa', 'url' => home_url('/tentang-kami/')],
                ['label' => 'Sertifikasi TKDN & Standar Kualitas Manufaktur FDS', 'slug' => 'fds-resmi-raih-sertifikasi-tkdn-4485-dari-kemenperin-ri'],
                ['label' => 'Layanan Jasa Foto Udara dan Pemetaan LiDAR', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')],
                ['label' => 'Lini Katalog Produk Drone Industrial FDS (FERTO, DELTAV, DELFRO, MULTIPURPOSE)', 'url' => home_url('/bandingkan/')],
            ],
            'faqs' => [
                [
                    'q' => 'Siapa saja yang direkomendasikan mengikuti program Pelatihan, Bootcamp, dan Sertifikasi FDS?',
                    'a' => 'Program ini dirancang untuk calon operator drone perusahaan, tim surveyor pertambangan dan tata ruang, divisi agronomi perkebunan, tim inspeksi aset energi/konstruksi, maupun instansi pemerintah dan BUMN yang berencana membangun atau menstandarkan tim operasional UAV internal.',
                ],
                [
                    'q' => 'Apa perbedaan antara Pelatihan Operasional Dasar, Bootcamp Aplikasi Sektoral, dan Persiapan Sertifikasi?',
                    'a' => 'Pelatihan operasional dasar berfokus pada penguasaan aman kendali wahana, cek pra-terbang, dan software stasiun darat. Bootcamp aplikasi berfokus lebih intensif ke alur kerja industri spesifik seperti pemetaan topografi atau pertanian presisi. Sementara program persiapan sertifikasi berfokus pada pembinaan teori regulasi penerbangan sipil (acuan CASR Part 107) dan evaluasi kesiapan uji praktik.',
                ],
                [
                    'q' => 'Apakah pelatihan bisa diselenggarakan secara in-house di lokasi perusahaan (on-site training)?',
                    'a' => 'Bisa. Selain penyelenggaraan kelas di workshop dan lokasi uji terbang kami di Sleman, Yogyakarta, tim instruktur FDS melayani skema custom on-site training langsung di area perusahaan, perkebunan, atau site operasional klien di berbagai wilayah Indonesia.',
                ],
                [
                    'q' => 'Apakah peserta harus sudah memiliki drone sendiri untuk mengikuti pelatihan?',
                    'a' => 'Tidak wajib. Anda dapat mengajukan kebutuhan pelatihan menggunakan armada latihan FDS atau menjadwalkan pelatihan khusus memakai tipe unit yang dioperasikan oleh perusahaan Anda (baik seri FERTO, DELTAV, MULTIPURPOSE, maupun konfigurasi sejenis).',
                ],
                [
                    'q' => 'Apakah regulasi resmi DKPPU CASR Part 107 ikut dibahas dalam materi?',
                    'a' => 'Ya. Pembekalan kepatuhan ruang udara, pembagian kelas airspace, prosedur izin penerbangan sipil di Indonesia, serta asas keselamatan operasional sesuai acuan regulasi Direktorat Kelaikudaraan dan Pengoperasian Pesawat Udara (DKPPU) menjadi bagian integral modul keselamatan kami.',
                ],
                [
                    'q' => 'Bagaimana cara mendapatkan informasi silabus lengkap, jadwal batch, dan penawaran biaya pelatihan?',
                    'a' => 'Anda dapat mengirimkan perkiraan jumlah peserta, fokus materi (pemetaan, agrikultur, inspeksi, atau sertifikasi), serta preferensi lokasi melalui halaman Kontak atau WhatsApp resmi FDS agar tim FDS mengirimkan rancangan proposal resmi.',
                ],
            ],
            'quote_defaults' => [
                'eyebrow'      => 'Permintaan Proposal Pelatihan & Sertifikasi',
                'title'        => 'Rencanakan Program Pelatihan Tim UAV Perusahaan atau Instansi Anda',
                'subtitle'     => 'Kirimkan perkiraan jumlah peserta, fokus aplikasi industri, dan preferensi lokasi (Sleman vs On-Site) untuk menerima draf silabus dan estimasi biaya.',
                'wa_prefill'   => 'Halo tim FDS, kami ingin berkonsultasi mengenai program Pelatihan, Bootcamp, dan Sertifikasi Pilot Drone.',
                'placeholder'  => 'Tuliskan perkiraan jumlah peserta, kebutuhan fokus (pengoperasian dasar, pemetaan/GIS, drone pertanian sprayer, atau persiapan sertifikasi), dan rencana lokasi pelatihan...',
            ],
            'seo' => [
                'meta_title'   => 'Pelatihan Pilot Drone, Bootcamp & Sertifikasi | FDS',
                'meta_desc'    => 'Program pelatihan pilot drone, bootcamp pemetaan dan agrikultur presisi, serta persiapan sertifikasi operator UAV industri DKPPU CASR 107 bersama FDS.',
                'service_type' => 'Drone Pilot Training, UAV Bootcamp and Certification Service',
            ],
            'placeholders' => [
                'Nama resmi merek program pelatihan/akademi serta status mitra lembaga uji kompetensi atau penyelenggaraan sertifikasi resmi (bila terdapat kerja sama lembaga eksternal/LSP/DKPPU).',
                'Durasi hari resmi per jalur program (Pelatihan Dasar vs Bootcamp Pemetaan/Pertanian vs Sertifikasi) dan kuota minimum jam terbang praktik.',
                'Informasi tarif pelatihan reguler per peserta vs paket korporasi on-site, jadwal batch publik aktif, serta persyaratan administratif peserta.',
                'Daftar jenis dokumen/sertifikat kelulusan resmi yang diterbitkan setelah peserta menyelesaikan evaluasi akhir.',
            ],
        ],
    ];
}

/**
 * Mengambil data 3 kategori layanan setelah menggabungkan override WP Admin
 * secara non-destruktif (option: fds_layanan_cat_overrides_{slug}).
 */
function fds_get_layanan_categories(bool $fresh = false): array {
    static $memo = null;
    if (!$fresh && $memo !== null) {
        return $memo;
    }

    $defaults = fds_get_default_layanan_categories();
    $merged   = [];

    foreach ($defaults as $slug => $cat_default) {
        $override = get_option('fds_layanan_cat_overrides_' . $slug, null);
        if (is_array($override) && !empty($override) && function_exists(__NAMESPACE__ . '\\fds_merge_layanan_data')) {
            $merged[$slug] = fds_merge_layanan_data($cat_default, $override);
        } else {
            $merged[$slug] = $cat_default;
        }
    }

    if (function_exists('fds_deep_decode')) {
        $merged = \fds_deep_decode($merged);
    }

    $memo = $merged;
    return $memo;
}


