<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS LAYANAN SECTORS REGISTRY (6 SEKTOR INDUSTRI STRATEGIS)
 * =========================================================================
 * Single Source of Truth untuk 6 halaman sektor (/layanan/{sektor}/),
 * kartu Hub Layanan, Beranda "#solusi", Navbar, dan Footer.
 *
 * Aturan ketahanan data:
 * - Seluruh butir solusi, produk/teknologi, output pekerjaan, dan deliverable_groups
 *   dari Halaman_Layanan.md dipertahankan 100%.
 * - Zero [ISI: ...] literal pada output HTML publik; data perusahaan yang masih
 *   perlu konfirmasi dicatat terstruktur pada key 'placeholders'.
 * - Zero fabrikasi harga, model sensor LiDAR fiktif, klaim RMSE palsu, atau SLA fiktif.
 */
function fds_get_default_layanan_sectors(): array {
    $theme_uri = get_template_directory_uri();
    return [
        // =====================================================================
        // SEKTOR 01: PERTAMBANGAN
        // =====================================================================
        'pertambangan' => [
            'num'        => '01',
            'slug'       => 'pertambangan',
            'url'        => home_url('/layanan/pertambangan/'),
            'card_title' => 'Pertambangan',
            'card_desc'  => 'Solusi drone mapping, survei topografi, pengukuran volume, dan monitoring progres tambang untuk mendukung produktivitas serta efisiensi operasional.',
            'card_tags'  => [
                ['label' => 'Mining Survey', 'url' => ''],
                ['label' => 'Stockpile', 'url' => ''],
                ['label' => 'LiDAR', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')],
                ['label' => 'Mine Monitoring', 'url' => ''],
                ['label' => 'Eksplorasi', 'url' => ''],
                ['label' => 'Perencanaan Tambang', 'url' => home_url('/layanan/perencanaan/')],
            ],
            'home_tag'   => 'Pertambangan • LiDAR',
            'image'      => $theme_uri . '/public/images/sectors/pertambangan.webp',
            'image_alt'  => 'Pemetaan area pertambangan terbuka dan survei topografi tambang menggunakan drone UAV FDS',

            'h1'           => 'Jasa Survey LiDAR Tambang & Pemetaan Topografi UAV',
            'subtitle'     => 'Smart Mining Survey & Geospatial Intelligence',
            'hero_summary' => 'Solusi drone mapping, survei LiDAR tambang, pengukuran volume stockpile, dan pemantauan progres pit berbasis data geospasial.',
            'intro_paragraphs' => [
                'Optimalkan operasional pertambangan melalui teknologi drone mapping dan survei geospasial. FDS menyediakan solusi pengumpulan data udara untuk membantu perusahaan tambang memantau kondisi site, mengevaluasi progres produksi, dan mendukung perencanaan operasional.',
                'Teknologi UAV memungkinkan pengumpulan data topografi dan visual area tambang secara berkala, sehingga perusahaan dapat memperoleh gambaran kondisi aktual sebagai dasar evaluasi dan pengambilan keputusan.',
            ],
            'challenge_section' => [
                'eyebrow'  => 'Konteks Operasional Pertambangan',
                'title'    => 'Mengapa Data Topografi Tambang Sering Menghambat Rekonsiliasi Produksi?',
                'paragraphs' => [
                    'Pada operasi tambang terbuka seperti batubara, nikel laterit, bauksit, emas, maupun kuari bahan galian, geometri pit dan disposal berubah setiap hari mengikuti ritme alat muat-angkut. Ketika pemetaan topografi masih mengandalkan pengukuran titik-per-titik di darat untuk keseluruhan bukaan luas, data kerap selesai diolah setelah kondisi lapangan bergeser.',
                    'Di sisi lain, banyak titik kritis justru berada di area berisiko tinggi seperti kaki tebing highwall, lereng disposal gembur, maupun area pre-stripping yang masih tertutup semak dan pepohonan lebat. Di lokasi berkanopi rapat seperti ini, pemetaan foto udara konvensional hanya menangkap elevasi pucuk vegetasi, sehingga berpotensi memicu bias hitungan volume overburden dan perencanaan drainase awal.',
                ],
                'pain_points' => [
                    [
                        'title' => 'Paparan Keselamatan Surveyor di Zona Aktif',
                        'desc'  => 'Menugaskan tim darat mendaki gundukan stockpile atau mendekati bibir bench untuk mengambil prisma menambah risiko kecelakaan kerja di tengah mobilitas dump truck dan alat berat.',
                    ],
                    [
                        'title' => 'Discrepancy Volume EOM antara Kontraktor dan Owner',
                        'desc'  => 'Kerapatan titik ukur manual yang renggang atau model permukaan yang masih tercampur pantulan alat berat sering menjadi sumber perdebatan dalam berita acara volume bulanan.',
                    ],
                    [
                        'title' => 'Distorsi Elevasi pada Lahan Pre-Stripping & Reklamasi',
                        'desc'  => 'Tanpa pemisahan titik permukaan tanah asli (Bare Earth DTM) melalui teknologi laser scanning atau kontrol lapangan memadai, estimasi kupasan awal dan evaluasi revegetasi menjadi kurang presisi.',
                    ],
                ],
            ],
            'solutions' => [
                [
                    'name'       => 'Mine Mapping & Topographic Survey',
                    'desc'       => 'Pemetaan topografi pit, disposal, jalan tambang, dan area operasional.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Stockpile Volume Measurement',
                    'desc'       => 'Pengukuran volume timbunan dan material untuk kebutuhan rekonsiliasi.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Pit & Progress Monitoring',
                    'desc'       => 'Monitoring perubahan area penggalian dan progres tambang secara berkala.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Drone LiDAR Survey',
                    'desc'       => 'Pemetaan tiga dimensi menggunakan teknologi laser scanning.',
                    'link_url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                    'link_label' => 'Detail Jasa Foto Udara & LiDAR',
                ],
                [
                    'name'       => 'Hauling Road Mapping',
                    'desc'       => 'Pemetaan jalan angkut, drainase, dan infrastruktur pendukung.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Mine Infrastructure Inspection',
                    'desc'       => 'Dokumentasi dan inspeksi visual fasilitas tambang.',
                    'link_url'   => home_url('/layanan/energi-infrastruktur/'),
                    'link_label' => 'Lihat Sektor Energi & Infrastruktur',
                ],
                [
                    'name'       => 'Reclamation Monitoring',
                    'desc'       => 'Pemantauan perkembangan area reklamasi.',
                    'link_url'   => home_url('/layanan/kehutanan-lingkungan/'),
                    'link_label' => 'Lihat Monitoring Vegetasi & Kehutanan',
                ],
            ],
            'method_comparison' => [
                'eyebrow'  => 'Matriks Pemilihan Metode Akuisisi',
                'title'    => 'Kapan Menggunakan Drone LiDAR, Fotogrametri UAV, atau Survei Terestris di Tambang?',
                'subtitle' => 'Pemilihan metode survei tambang tidak selalu tentang teknologi termahal, melainkan kesesuaian sensor dengan tutupan lahan dan tingkat bahaya lokasi.',
                'columns'  => ['Aspek Evaluasi Site', 'Drone LiDAR Survey', 'Fotogrametri Udara (RGB)', 'Total Station / GNSS RTK Darat'],
                'rows'     => [
                    [
                        'Area Pre-Stripping & Reklamasi Bervegetasi',
                        'Pilihan utama: pulsa laser menembus celah semak dan kanopi untuk membangun Bare Earth DTM tanpa tebas rintisan.',
                        'Kurang sesuai: foto udara hanya merekam permukaan atas daun (DSM), bukan tanah asli di bawah vegetasi.',
                        'Akurat secara titik, namun sangat lambat dan mahal untuk cakupan puluhan hingga ratusan hektar berhutan.',
                    ],
                    [
                        'Pemetaan Pit Dalam & Lereng Highwall Curam',
                        'Andal di zona gelap atau berbayangan tebing curam sekaligus menjaga operator di area aman.',
                        'Cukup baik di pit terbuka terang, tetapi kualitas pencocokan titik foto dapat menurun di bayangan tebing dalam.',
                        'Berisiko tinggi bila personel harus berdiri memegang prisma di dekat tebing curam atau lantai galian aktif.',
                    ],
                    [
                        'Stockpile Volume Measurement & EOM Aktif',
                        'Merekam bentuk timbunan kompleks secara cepat di antara jadwal operasi, termasuk material gelap atau berdebu ringan.',
                        'Efisien untuk stockpile terbuka bersih dengan tekstur seragam terang dan pencahayaan stabil.',
                        'Memadai untuk timbunan kecil tunggal, tetapi menghambat lalu lintas wheel loader pada stockyard besar.',
                    ],
                    [
                        'Pemasangan Bench Mark (BM) & Patok Batas IUP',
                        'Tidak menggantikan pemasangan patok fisik dan pengamatan titik ikat di tanah.',
                        'Tetap memerlukan kontrol GCP/ICP terestris agar tidak terjadi distorsi datum.',
                        'Wajib digunakan sebagai jaring kontrol referensi utama dan kalibrasi sistem koordinat mine grid lokal.',
                    ],
                ],
            ],
            'closing_paragraphs' => [
                'FDS mendukung perusahaan pertambangan dalam mengoptimalkan kegiatan survei, perencanaan, monitoring produksi, pengelolaan material, dan pemantauan lingkungan melalui teknologi UAV dan geospatial intelligence.',
                'Kami membantu perusahaan mengubah data spasial menjadi informasi yang dapat digunakan oleh tim mine planning, survey, produksi, engineering, K3, lingkungan, dan manajemen.',
            ],
            'deliverable_groups' => [
                [
                    'title' => 'Konsultasi dan Perencanaan Survei Tambang',
                    'items' => [
                        'Mining site assessment dan identifikasi kebutuhan pemetaan.',
                        'Evaluasi sistem survei dan monitoring tambang yang sedang berjalan.',
                        'Analisis kebutuhan data untuk mine planning dan production monitoring.',
                        'Penyusunan strategi drone survey berdasarkan luas dan karakteristik site.',
                        'Penyusunan standar akuisisi data dan quality control.',
                        'Penyusunan program monitoring tambang harian, mingguan, atau bulanan.',
                        'Analisis kebutuhan integrasi data drone dengan sistem mine planning.',
                    ],
                ],
                [
                    'title' => 'Data Dasar Topografi dan Model Permukaan',
                    'items' => [
                        'Digital Terrain Model (DTM), Digital Surface Model (DSM), Digital Elevation Model (DEM).',
                        'Contour and elevation map, 3D terrain model, High-resolution orthophoto.',
                        'Point cloud dan klasifikasi permukaan.',
                        'Peta batas pit, bench, ramp, crest, dan toe sesuai data yang tersedia.',
                        'Peta situasi fasilitas dan infrastruktur tambang.',
                        'Survey control point dan laporan pemeriksaan kualitas data.',
                    ],
                ],
                [
                    'title' => 'Analisis Progres Penggalian, Volume & Infrastruktur Tambang',
                    'items' => [
                        'Analisis progres penggalian berdasarkan blok atau area kerja.',
                        'Perbandingan topografi aktual terhadap desain tambang.',
                        'Analisis perubahan elevasi dan permukaan.',
                        'Volume cut and fill.',
                        'Analisis perubahan volume disposal.',
                        'Monitoring perkembangan area penimbunan.',
                        'Production progress dashboard.',
                        'Laporan deviasi kondisi aktual terhadap rencana.',
                        'Dokumentasi visual perkembangan site secara periodik.',
                        'Stockpile volume calculation, boundary mapping.',
                        '3D stockpile model.',
                        'Material volume reconciliation.',
                        'Perbandingan volume antarperiode.',
                        'Monitoring perubahan bentuk dan elevasi stockpile.',
                        'Stockpile inventory report.',
                        'Analisis geometrik dan lebar jalan berdasarkan data yang tersedia.',
                        'Pemetaan fasilitas dan infrastruktur tambang.',
                    ],
                ],
                [
                    'title' => 'Inspeksi Fasilitas, Reklamasi dan Monitoring Lingkungan',
                    'items' => [
                        'Identifikasi visual area yang membutuhkan inspeksi lanjutan.',
                        'Peta area reklamasi.',
                        'Land cover classification.',
                        'Vegetation cover assessment.',
                        'Monitoring perubahan tutupan lahan.',
                        'Dokumentasi perkembangan revegetasi.',
                        'Identifikasi area dengan tutupan vegetasi rendah.',
                        'Monitoring kolam sedimen dan drainase.',
                        'Pemetaan area bukaan tambang.',
                        'Environmental monitoring dashboard.',
                    ],
                ],
                [
                    'title' => 'Dashboard, Database Spasial dan Integrasi Mine Planning',
                    'items' => [
                        'Integrasi data drone dengan GIS dan software mine planning.',
                        'Historical mining spatial database.',
                        'Monthly mining monitoring dashboard.',
                        'Visualisasi 2D dan 3D kondisi site.',
                        'Production and stockpile monitoring dashboard.',
                        'Sistem dokumentasi perubahan site.',
                        'Analisis tren progres tambang.',
                        'Rekomendasi peningkatan workflow survei dan pengelolaan data.',
                    ],
                ],
            ],
            'workflow_steps' => [
                [
                    'step'  => '01',
                    'title' => 'Mining Site Assessment & Penyelarasan Mine Grid',
                    'desc'  => 'Evaluasi batas poligon IUP, zona pit aktif, disposal, tutupan vegetasi, serta parameter transformasi antara koordinat nasional (UTM WGS84) dan mine grid lokal tambang.',
                ],
                [
                    'step'  => '02',
                    'title' => 'Koordinasi K3/HSE Site & Penjadwalan Ruang Udara',
                    'desc'  => 'Penyelarasan rencana terbang dengan Kepala Teknik Tambang (KTT), tim HSE, jadwal peledakan (blasting), dan prosedur induksi keselamatan operasi pertambangan.',
                ],
                [
                    'step'  => '03',
                    'title' => 'Pengikatan BM/GCP & Akuisisi Data UAV Terkontrol',
                    'desc'  => 'Pengukuran titik ikat Bench Mark dan Independent Check Point (ICP) di darat bersamaan dengan misi terbang fotogrametri atau Drone LiDAR yang dikonfigurasi sesuai elevasi lembah pit.',
                ],
                [
                    'step'  => '04',
                    'title' => 'Pembersihan Objek Bergerak & Klasifikasi Permukaan',
                    'desc'  => 'Penyaringan pantulan alat berat aktif (dump truck, excavator), konveyor, serta vegetasi sehingga model DTM dan hitungan volume hanya mencerminkan material aktual.',
                ],
                [
                    'step'  => '05',
                    'title' => 'Validasi Kontrol Kualitas & Integrasi Mine Planning',
                    'desc'  => 'Verifikasi selisih elevasi terhadap ICP, penyerahan file topografi dan rekonsiliasi volume, hingga penyusunan historical mining spatial database untuk monitoring periodik.',
                ],
            ],
            'software_integrations' => [
                'eyebrow'  => 'Kompatibilitas Alur Kerja Tambang',
                'title'    => 'Integrasi Langsung ke Software Mine Planning & GIS Internal',
                'subtitle' => 'Setiap deliverables dirancang agar tim mine survey, geoteknik, dan mine plan dapat mengolah data lanjutan tanpa konversi rumit.',
                'formats'  => [
                    'Point Cloud Terklasifikasi (.LAS / .LAZ sesuai standar klasifikasi ASPRS)',
                    'Digital Terrain Model (DTM), DSM & DEM (GeoTIFF / ASCII Grid)',
                    'Garis Kontur, Pit Shell, Crest, Toe & Peta Situasi (.DWG / .DXF CAD & .SHP / GeoJSON)',
                    'Citra Ortofoto Resolusi Tinggi (.TIFF / .ECW tergeoreferensi)',
                    'Laporan Rekonsiliasi Volume Stockpile & Cut and Fill (.PDF & spreadsheet terstruktur)',
                ],
                'tools'    => ['AutoCAD Civil 3D', 'Minescape', 'Surpac', 'Vulcan', 'Minex', 'Global Mapper', 'ArcGIS & QGIS'],
            ],
            'cost_framework' => [
                'eyebrow'  => 'Perencanaan Anggaran & Pengadaan',
                'title'    => 'Variabel Pembentuk Biaya Survei Drone & LiDAR Tambang',
                'intro'    => 'Anggaran proyek pemetaan tambang disusun berdasarkan kondisi riil lapangan setelah peninjauan batas IUP dan spesifikasi teknis. Memahami variabel berikut membantu tim pengadaan membandingkan penawaran secara setara.',
                'factors'  => [
                    [
                        'name' => 'Luas Area & Proporsi Tutupan Vegetasi',
                        'desc' => 'Blok pit terbuka murni dan area pre-stripping berhutan rapat memerlukan parameter jalur terbang serta beban klasifikasi point cloud yang berbeda.',
                    ],
                    [
                        'name' => 'Frekuensi Survei (Proyek Tunggal vs Kontrak Berkala)',
                        'desc' => 'Program monitoring progres tambang bulanan (End of Month) atau mingguan memungkinkan optimasi mobilisasi dan pemanfaatan titik Bench Mark permanen.',
                    ],
                    [
                        'name' => 'Lokasi IUP, Akses Logistik & Persyaratan HSE',
                        'desc' => 'Jarak mobilisasi tim dari workshop, transportasi akses lokasi terpencil, serta durasi induksi KTT/commissioning peralatan site turut diperhitungkan.',
                    ],
                    [
                        'name' => 'Kedalaman Deliverables & Analisis Volume',
                        'desc' => 'Perbedaan lingkup antara paket dasar topografi (DTM, DSM, kontur) dengan analisis deviasi desain, laporan rekonsiliasi stockpile, hingga dashboard periodik.',
                    ],
                ],
                'kak_checklist' => [
                    'Informasi lokasi proyek (kota/kabupaten dan provinsi) atau koordinat batas wilayah kerja.',
                    'Pembagian sub-area target (active pit, waste dump/disposal, stockpile, hauling road, atau lahan reklamasi).',
                    'Sistem koordinat acuan perusahaan (UTM WGS84 atau parameter mine grid lokal beserta ketersediaan titik BM).',
                    'Kebutuhan interval kontur, metode survei yang diinginkan (fotogrametri vs LiDAR), serta target jadwal pelaporan.',
                ],
            ],
            'eeat_proof' => [
                'eyebrow'  => 'Otoritas Rekayasa & Standar Mutu',
                'title'    => 'Didukung Manufaktur UAV Dalam Negeri & Protokol Audit Data',
                'intro'    => 'Berbeda dari pelaksana lapangan yang hanya bergantung pada pasokan unit pihak ketiga, Full Drone Solutions (PT Karya Solusi Angkasa) mengelola rekayasa armada UAV di Sleman, Yogyakarta dengan sistem manajemen mutu ISO 9001:2015 serta sertifikasi TKDN 60,74% pada lini pertanian FERTO dan SNI 9199:2023 pada FERTO 15 & FERTO 22.',
                'bullets'  => [
                    'Dukungan armada wahana seperti Fixed-Wing VTOL DELTAV untuk koridor dan area tambang luas maupun platform multirotor modular untuk area pit bertingkat, lengkap dengan sistem kendali FDS Station berbahasa Indonesia.',
                    'Fleksibilitas kerja sama B2B: mulai dari jasa survei terjadwal hingga opsi evaluasi pengadaan armada mandiri beserta pelatihan tim surveyor internal tambang.',
                    'Setiap hasil pemetaan disertai dokumentasi pemeriksaan kualitas data berbasis titik kontrol lapangan (GCP/ICP) sebelum diserahkan kepada tim mine survey klien.',
                ],
                'portfolio_note' => 'Dokumentasi teknis proyek tambang disusun bersama tim klien dengan merinci luasan IUP, metode survei (fotogrametri/LiDAR), sistem koordinat mine grid, verifikasi kontrol titik uji, serta format file yang terintegrasi dengan software tambang perusahaan.',
            ],
            'related_capabilities' => [
                [
                    'title' => 'Jasa Foto Udara dan LiDAR',
                    'desc'  => 'Pelajari detail metodologi laser scanning dan fotogrametri untuk penetrasi vegetasi dan pemodelan permukaan tambang.',
                    'url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                ],
                [
                    'title' => 'Perencanaan Wilayah & Konstruksi',
                    'desc'  => 'Analisis volume cut and fill, pemetaan akses jalan, dan model permukaan 3D untuk persiapan infrastruktur kawasan.',
                    'url'   => home_url('/layanan/perencanaan/'),
                ],
                [
                    'title' => 'Kehutanan & Lingkungan',
                    'desc'  => 'Evaluasi revegetasi lanjutan, tutupan vegetasi, dan baseline lingkungan untuk area reklamasi pascatambang.',
                    'url'   => home_url('/layanan/kehutanan-lingkungan/'),
                ],
            ],
            'reading_links' => [
                ['label' => 'Spesifikasi Fixed-Wing VTOL DELTAV untuk Pemetaan Luas', 'url' => home_url('/drone/deltav/')],
                ['label' => 'Platform Multirotor MULTIPURPOSE untuk Inspeksi & Pit Detail', 'url' => home_url('/drone/multipurpose/')],
                ['label' => 'Software Ground Control System FDS Station Berbahasa Indonesia', 'slug' => 'ground-control-app-software-misi-drone-sepenuhnya-berbahasa-indonesia'],
                ['label' => 'Panduan Teknis LiDAR untuk Tambang (Artikel Pilar)', 'slug' => 'lidar-untuk-tambang-manfaat-metode'],
                ['label' => 'Faktor Pembentuk Biaya Survei LiDAR di Indonesia', 'slug' => 'biaya-survei-lidar-indonesia'],
            ],
            'faqs' => [
                [
                    'q' => 'Apa manfaat utama jasa pemetaan dan survei LiDAR drone pada operasional tambang?',
                    'a' => 'Teknologi UAV memungkinkan pengambilan data topografi pit, disposal, hauling road, dan stockpile secara berkala dalam waktu lebih singkat tanpa menugaskan surveyor berjalan langsung di zona lereng rawan atau lalu lintas alat berat.',
                ],
                [
                    'q' => 'Bagaimana membedakan kebutuhan Drone LiDAR Survey vs fotogrametri udara di lokasi tambang?',
                    'a' => 'Fotogrametri udara efektif untuk pit terbuka bersih, jalan tambang terang, dan stockpile tanpa tutupan semak. Drone LiDAR diprioritaskan pada area pre-stripping yang masih berhutan, lahan reklamasi bervegetasi, serta dasar pit curam dengan bayangan tebing dalam.',
                ],
                [
                    'q' => 'Output data apa yang dihasilkan dari layanan pemetaan pertambangan FDS?',
                    'a' => 'Deliverables meliputi Digital Terrain Model (DTM), Digital Surface Model (DSM), Digital Elevation Model (DEM), peta kontur dan elevasi, high-resolution orthophoto, point cloud terklasifikasi (.las/.laz), peta batas pit/bench/ramp/crest/toe, serta laporan rekonsiliasi volume stockpile dan cut and fill.',
                ],
                [
                    'q' => 'Apakah pantulan excavator dan dump truck aktif di dalam pit memengaruhi hasil perhitungan volume?',
                    'a' => 'Selama proses pengolahan point cloud dan klasifikasi permukaan, pantulan dari objek non-tanah seperti alat berat, konveyor, bangunan sementara, maupun pepohonan disaring keluar dari kelas titik tanah (Ground) agar tidak menggelembungkan hitungan volume.',
                ],
                [
                    'q' => 'Apakah data hasil drone mapping dan LiDAR bisa mengikuti koordinat mine grid lokal tambang?',
                    'a' => 'Ya. Data dapat diikatkan ke titik Bench Mark (BM) resmi site menggunakan sistem koordinat proyek perusahaan maupun UTM, dengan laporan verifikasi titik kontrol serta kompatibilitas menuju AutoCAD Civil 3D, Minescape, Surpac, Vulcan, dan platform GIS.',
                ],
                [
                    'q' => 'Bagaimana prosedur pengajuan survei lokasi tambang atau penjadwalan monitoring berkala?',
                    'a' => 'Proses dimulai dengan penyampaian lokasi IUP/site, perkiraan luas area target, dan kebutuhan output melalui halaman Kontak atau WhatsApp resmi FDS. Tim kami akan melakukan mining site assessment guna menyusun rekomendasi metode, rencana K3, dan estimasi kerja.',
                ],
            ],
            'quote_defaults' => [
                'eyebrow'      => 'Permintaan Penawaran Pertambangan',
                'title'        => 'Jadwalkan Mining Site Assessment & Konsultasi Survei IUP',
                'subtitle'     => 'Kirimkan lokasi site, perkiraan luas pit/disposal/stockpile, serta target output untuk mendapatkan evaluasi metode survei dan rancangan proposal teknis.',
                'wa_prefill'   => 'Halo tim Enterprise FDS, kami ingin berkonsultasi mengenai jasa pemetaan / survei LiDAR tambang.',
                'placeholder'  => 'Jelaskan target area (pit, disposal, stockpile, hauling road, atau pre-stripping berhutan), kebutuhan interval kontur/volume, dan sistem koordinat proyek...',
            ],
            'seo' => [
                'meta_title'   => 'Jasa Survey LiDAR Tambang & Pemetaan Pit | FDS',
                'meta_desc'    => 'Layanan pemetaan drone tambang, survey LiDAR pit, pengukuran volume stockpile, cut and fill, hauling road, dan monitoring reklamasi dari FDS.',
                'service_type' => 'Mining Drone Mapping, Stockpile Measurement, and Mine LiDAR Survey',
            ],
            'placeholders' => [
                'Model dan spesifikasi seri sensor LiDAR & GNSS Geodetik spesifik yang akan dioperasikan oleh tim survei tambang FDS.',
                'Format file ekspor tambahan spesifik atau template laporan EOM khusus apabila diminta per kontrak klien tambang.',
                'Estimasi waktu mobilisasi tim ke luar Pulau Jawa dan persyaratan commissioning K3/HSE spesifik site klien.',
                'Studi kasus proyek pertambangan terverifikasi beserta izin publikasi nama komoditas/lokasi, foto lapangan asli, dan nilai RMSE hasil uji ICP.',
            ],
        ],

        // =====================================================================
        // SEKTOR 02: PERKEBUNAN DAN PERTANIAN
        // =====================================================================
        'perkebunan-pertanian' => [
            'num'        => '02',
            'slug'       => 'perkebunan-pertanian',
            'url'        => home_url('/layanan/perkebunan-pertanian/'),
            'card_title' => 'Perkebunan dan Pertanian',
            'card_desc'  => 'Teknologi drone untuk penyemprotan presisi, pemantauan kesehatan tanaman, pemetaan lahan, dan pengelolaan perkebunan berbasis data.',
            'card_tags'  => [
                ['label' => 'Precision Agriculture', 'url' => ''],
                ['label' => 'Spraying', 'url' => ''],
                ['label' => 'Multispectral', 'url' => home_url('/layanan/jasa-multispectral-hyperspectral/')],
                ['label' => 'Plantation Mapping', 'url' => ''],
            ],
            'home_tag'   => 'Agrikultur • Multispectral',
            'image'      => $theme_uri . '/public/images/sectors/perkebunan-pertanian.webp',
            'image_alt'  => 'Pemetaan kesehatan tanaman dan penyemprotan perkebunan presisi dengan drone UAV FDS',

            'h1'           => 'Drone Pemetaan Kebun Sawit & Pertanian Presisi FDS',
            'subtitle'     => 'Precision Agriculture & Smart Plantation Management',
            'hero_summary' => 'Solusi penyemprotan presisi, pemetaan blok perkebunan, pemantauan kesehatan tanaman multispectral, serta pengelolaan lahan berbasis data.',
            'intro_paragraphs' => [
                'Tingkatkan efisiensi pengelolaan lahan pertanian dan perkebunan melalui teknologi UAV. FDS menghadirkan solusi drone untuk aplikasi pertanian presisi, penyemprotan tanaman, pemetaan lahan, dan monitoring kondisi vegetasi.',
                'Dengan mengintegrasikan drone, sensor multispectral, dan analisis data, perusahaan perkebunan dan pelaku agribisnis dapat memperoleh informasi kondisi lahan dan tanaman secara lebih sistematis.',
            ],
            'challenge_section' => [
                'eyebrow'  => 'Tantangan Agronomi & Manajemen Blok',
                'title'    => 'Mengapa Pengelolaan Kebun Skala Besar Membutuhkan Data Spasial & Aplikasi Presisi?',
                'paragraphs' => [
                    'Pada hamparan perkebunan kelapa sawit, tebu, padi pangan, hingga hortikultura seluas ratusan hingga ribuan hektar, tim estate sering menghadapi keterlambatan informasi saat menilai keseragaman pertumbuhan blok, area kosong (empty spot), kondisi jalan produksi, dan efektivitas saluran drainase di lahan datar maupun gambut.',
                    'Selain pemetaan, aplikasi pupuk cair dan perlindungan tanaman secara manual kerap terkendala keterbatasan tenaga semprot, ketidakseragaman dosis antarblok, dan pemborosan cairan agrokimia. Integrasi antara pemetaan blok udara, analisis vegetasi multispectral, dan armada agricultural spraying drone memberi landasan data sebelum tindakan lapangan diambil.',
                ],
                'pain_points' => [
                    [
                        'title' => 'Stres Tanaman Terlambat Terdeteksi Secara Visual',
                        'desc'  => 'Gejala kekurangan nutrisi atau tekanan lingkungan sering baru tampak dengan mata telanjang setelah memengaruhi bagian luas tajuk dan produktivitas blok.',
                    ],
                    [
                        'title' => 'Ketidaksesuaian Data Batas Blok & Jumlah Tegakan',
                        'desc'  => 'Peta kerja lawas dan sensus manual acap kali berbeda dengan realitas lapangan akibat replanting, erosi parit, atau area tanam yang tidak produktif.',
                    ],
                    [
                        'title' => 'Ketergantungan Tenaga Kerja Penyemprotan Manual',
                        'desc'  => 'Penyemprotan konvensional di lahan berair, berbukit, atau tanaman tinggi membutuhkan waktu kerja panjang dengan keseragaman cakupan semprot yang sulit diaudit.',
                    ],
                ],
            ],
            'solutions' => [
                [
                    'name'       => 'Agricultural Drone Spraying',
                    'desc'       => 'Penyemprotan pupuk cair dan pestisida menggunakan drone.',
                    'link_url'   => home_url('/kategori-drone/agrikultur/'),
                    'link_label' => 'Lihat Katalog Drone Agrikultur',
                ],
                [
                    'name'       => 'Plantation Mapping',
                    'desc'       => 'Pemetaan batas lahan, blok perkebunan, dan akses operasional.',
                    'link_url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                    'link_label' => 'Detail Jasa Foto Udara',
                ],
                [
                    'name'       => 'Crop Health Monitoring',
                    'desc'       => 'Pemantauan kondisi tanaman menggunakan citra udara dan multispectral.',
                    'link_url'   => home_url('/layanan/jasa-multispectral-hyperspectral/'),
                    'link_label' => 'Pelajari Jasa Multispectral',
                ],
                [
                    'name'       => 'Multispectral Analysis',
                    'desc'       => 'Analisis indeks vegetasi seperti NDVI dan indeks lain yang sesuai dengan kebutuhan.',
                    'link_url'   => home_url('/layanan/jasa-multispectral-hyperspectral/'),
                    'link_label' => 'Detail Multispectral & Hyperspectral',
                ],
                [
                    'name'       => 'Plantation Inventory',
                    'desc'       => 'Pendataan dan pemantauan area tanaman.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Land & Irrigation Mapping',
                    'desc'       => 'Pemetaan lahan dan jaringan irigasi.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Agricultural UAV Customization',
                    'desc'       => 'Pengembangan dan konfigurasi drone sesuai kebutuhan aplikasi.',
                    'link_url'   => home_url('/kontak/#hubungi-form'),
                    'link_label' => 'Konsultasi Kustomisasi UAV',
                ],
            ],
            'products_tech' => [
                [
                    'name' => 'Ferto Agricultural Spraying Drone.',
                    'url'  => home_url('/kategori-drone/agrikultur/'),
                ],
                [
                    'name' => 'Ferto 5, Ferto 10, dan Ferto 15.',
                    'url'  => '',
                    'sublinks' => [
                        ['label' => 'Ferto 5', 'url' => home_url('/drone/ferto-5l/')],
                        ['label' => 'Ferto 10', 'url' => home_url('/drone/ferto-10l/')],
                        ['label' => 'Ferto 15', 'url' => home_url('/drone/ferto-15l/')],
                    ],
                ],
                [
                    'name' => 'FigSpec FS50 / FS60 Multispectral & Hyperspectral Sensor.',
                    'url'  => home_url('/layanan/jasa-multispectral-hyperspectral/'),
                ],
                [
                    'name' => 'Fixed-Wing VTOL DELTAV Mapping UAV.',
                    'url'  => '',
                    'sublinks' => [
                        ['label' => 'Deltav', 'url' => home_url('/drone/deltav/')],
                    ],
                    'note' => 'Tersedia pula opsi kapasitas Ferto seri komersial lain (22L–50L) sesuai kebutuhan luasan perkebunan.',
                ],
                [
                    'name' => 'FDS Ground Control Station dan sistem pendukung operasi.',
                    'url'  => '',
                ],
            ],
            'work_outputs' => 'Peta perkebunan, peta blok, ortofoto, peta indeks vegetasi, laporan kondisi tanaman, dokumentasi penyemprotan, dan data spasial pertanian.',
            'method_comparison' => [
                'eyebrow'  => 'Matriks Pemilihan Pendekatan UAV Perkebunan',
                'title'    => 'Memadukan Pemetaan Udara, Multispectral, LiDAR & Drone Spraying Secara Tepat',
                'subtitle' => 'Setiap fase pengelolaan kebun membutuhkan sensor dan wahana berbeda agar anggaran operasional tidak terbuang pada data yang kurang relevan.',
                'columns'  => ['Kebutuhan Lapangan', 'Pemetaan RGB & Blok', 'Multispectral / Hyperspectral', 'Ferto Agricultural Spraying Drone'],
                'rows'     => [
                    [
                        'Inventarisasi Blok, Akses Jalan & Ortofoto',
                        'Optimal untuk pemetaan batas HGU, jalan panen, jembatan, fasilitas estate, dan sensus pokok dari citra tajam.',
                        'Digunakan sebagai lapisan pelengkap ketika dibutuhkan indeks reflektansi vegetasi per blok.',
                        'Tidak dirancang untuk menghasilkan peta ortofoto skala besar.',
                    ],
                    [
                        'Deteksi Kesehatan Tanaman & Indeks NDVI',
                        'Terbatas pada indikasi warna daun tampak sehingga gejala stres dini kurang terukur secara spektral.',
                        'Metode utama untuk memetakan variasi vigor vegetasi, zona kurang subur, dan anomali pertumbuhan blok.',
                        'Memanfaatkan hasil peta zona stres untuk mengarahkan aplikasi perlakuan secara lebih terfokus.',
                    ],
                    [
                        'Topografi Tanah di Bawah Tajuk Sawit Tua Rapat',
                        'Pada kanopi menutup total, foto RGB merekam elevasi pelepah atas, bukan permukaan tanah/parit.',
                        'Fokus pada reflektansi tajuk, bukan penetrasi elevasi dasar tanah (gunakan Jasa LiDAR untuk DTM drainase rapat).',
                        'Mengandalkan terrain-following radar saat terbang di atas tajuk bervariasi.',
                    ],
                    [
                        'Eksekusi Penyemprotan & Pemeliharaan Blok',
                        'Menyediakan peta batas poligon kerja dan zona halangan sebelum misi semprot disusun.',
                        'Menentukan prioritas blok yang membutuhkan tindak lanjut pemeliharaan.',
                        'Menjalankan aplikasi penyemprotan pupuk cair maupun pestisida berbasis jalur otonom.',
                    ],
                ],
            ],
            'closing_paragraphs' => [
                'FDS membantu perusahaan perkebunan dan pertanian meningkatkan efisiensi pengelolaan lahan melalui teknologi agricultural drone, precision spraying, multispectral imaging, dan analisis geospasial.',
                'Kami mengembangkan solusi mulai dari pemetaan lahan, evaluasi kondisi tanaman, pengelolaan operasional penyemprotan, hingga monitoring produktivitas berbasis data.',
            ],
            'deliverable_groups' => [
                [
                    'title' => 'Konsultasi dan Perencanaan Pertanian Presisi',
                    'items' => [
                        'Evaluasi kebutuhan pemetaan dan monitoring perkebunan.',
                        'Penyusunan strategi precision agriculture.',
                        'Rekomendasi platform drone dan sensor.',
                        'Perencanaan program penyemprotan dan monitoring tanaman.',
                        'Penyusunan workflow pengumpulan dan pengelolaan data perkebunan.',
                    ],
                ],
                [
                    'title' => 'Peta Dasar Lahan, Blok Perkebunan dan Infrastruktur',
                    'items' => [
                        'Plantation base map.',
                        'Peta batas dan luas lahan.',
                        'Plantation block mapping.',
                        'Peta akses jalan dan infrastruktur perkebunan.',
                        'Peta topografi dan elevasi lahan.',
                        'Peta jaringan irigasi dan drainase.',
                        'Peta penggunaan lahan.',
                        'Peta lokasi tanaman berdasarkan data inventarisasi.',
                        'GIS database perkebunan.',
                    ],
                ],
                [
                    'title' => 'Monitoring Kesehatan Tanaman, Multispectral & Inventarisasi',
                    'items' => [
                        'Crop health monitoring.',
                        'Multispectral imagery.',
                        'NDVI dan vegetation index mapping.',
                        'Analisis variasi kondisi vegetasi.',
                        'Identifikasi area dengan indikasi pertumbuhan tanaman tidak seragam.',
                        'Estimasi jumlah tanaman dari citra atau data spasial dengan validasi lapangan.',
                        'Identifikasi area kosong atau tidak produktif.',
                        'Pemetaan fasilitas perkebunan.',
                        'Pemetaan jalan operasional dan jaringan pendukung.',
                        'Plantation inventory database.',
                    ],
                ],
                [
                    'title' => 'Integrasi GIS, Dashboard Performa & Pengembangan AI',
                    'items' => [
                        'Integrasi data drone dengan GIS perkebunan.',
                        'Historical vegetation database.',
                        'Crop health monitoring dashboard.',
                        'Plantation block performance dashboard.',
                        'Analisis perubahan vegetasi antarperiode.',
                        'Pengembangan sistem monitoring berbasis AI sesuai kebutuhan dan ketersediaan data.',
                    ],
                ],
            ],
            'workflow_steps' => [
                [
                    'step'  => '01',
                    'title' => 'Evaluasi Komoditas, Poligon Blok & Tujuan Agronomi',
                    'desc'  => 'Peninjauan jenis tanaman (sawit, tebu, pangan, atau hortikultura), luasan divisi/afdeling, batas blok kerja, serta tujuan pemetaan vs penyemprotan.',
                ],
                [
                    'step'  => '02',
                    'title' => 'Pemilihan Wahana & Kalibrasi Sensor Operasional',
                    'desc'  => 'Penetapan konfigurasi Fixed-Wing VTOL DELTAV Mapping UAV, sensor FigSpec FS50/FS60, maupun unit sprayer seri Ferto sesuai karakter tajuk dan topografi kebun.',
                ],
                [
                    'step'  => '03',
                    'title' => 'Akuisisi Udara & Operasi Penyemprotan Terdokumentasi',
                    'desc'  => 'Pengumpulan citra ortofoto/multispectral atau pelaksanaan penyemprotan presisi dengan catatan jalur terbang, luas cakupan, dan pemeriksaan kualitas data di FDS Ground Control Station.',
                ],
                [
                    'step'  => '04',
                    'title' => 'Pengolahan GIS, Pemetaan Indeks & Validasi Lapangan',
                    'desc'  => 'Penyusunan plantation base map, peta NDVI, deteksi area kosong, estimasi jumlah tanaman yang divalidasi sampel lapangan, dan integrasi database kebun.',
                ],
            ],
            'software_integrations' => [
                'eyebrow'  => 'Format Output & Database Estate',
                'title'    => 'Data Spasial Siap Pakai oleh Tim GIS, Agronomi & Manajemen Estate',
                'subtitle' => 'Seluruh hasil diserahkan dalam format spasial standar yang mudah digabungkan dengan arsip afdeling perusahaan.',
                'formats'  => [
                    'Plantation Base Map & Ortofoto Blok (GeoTIFF tergeoreferensi)',
                    'Peta Batas Lahan, Blok, Jaringan Irigasi & Jalan Produksi (.SHP / GeoJSON / .DWG)',
                    'Peta Indeks Vegetasi (NDVI & indeks spektral sesuai kebutuhan proyek)',
                    'Basis Data Inventarisasi Tanaman & Titik Area Kosong (Tabular .XLSX/.CSV + Layer Spasial)',
                    'Laporan Kondisi Tanaman, Dokumentasi Penyemprotan & Visualisasi Dashboard GIS',
                ],
                'tools'    => ['QGIS', 'ArcGIS', 'FDS Ground Control Station', 'Global Mapper', 'AutoCAD / GIS Enterprise'],
            ],
            'cost_framework' => [
                'eyebrow'  => 'Struktur Anggaran Kebun & Pertanian',
                'title'    => 'Faktor Penentu Biaya Layanan Drone Perkebunan & Pertanian',
                'intro'    => 'Kebutuhan layanan di sektor agribisnis dapat berupa jasa survei pemetaan periodik, operasi penyemprotan kontrak, maupun pengadaan unit armada mandiri + pelatihan operator estate.',
                'factors'  => [
                    [
                        'name' => 'Jenis Pekerjaan (Mapping vs Multispectral vs Spraying)',
                        'desc' => 'Jasa foto blok RGB, analisis multispectral, dan operasional penyemprotan seri Ferto memiliki kebutuhan logistik lapangan, baterai, dan durasi misi berbeda.',
                    ],
                    [
                        'name' => 'Total Luasan Blok & Sebaran Lokasi Afdeling',
                        'desc' => 'Blok kebun yang saling berdekatan meningkatkan efisiensi mobilisasi tim dibanding lahan terpencar antar-distrik atau kepulauan.',
                    ],
                    [
                        'name' => 'Topografi Kebun & Kerapatan Tajuk Tanaman',
                        'desc' => 'Lahan gambut/rawa datar berbeda perencanaan terbangnya dengan kebun perbukitan terasering yang memerlukan pengaturan terrain following.',
                    ],
                    [
                        'name' => 'Kedalaman Analisis AI, Sensus Tanaman & Integrasi Dashboard',
                        'desc' => 'Output dasar ortofoto dan peta blok berbeda alokasi pengerjaannya dibanding analisis populasi tanaman tervalidasi serta penyusunan historical vegetation database.',
                    ],
                ],
                'kak_checklist' => [
                    'Jenis komoditas dan umur rata-rata tanaman di blok target.',
                    'Lokasi kabupaten/provinsi kebun/afdeling serta perkiraan total hektar.',
                    'Layanan yang diprioritaskan (Plantation Mapping, Multispectral Crop Health, Drone Spraying, atau pengadaan unit Ferto + Training).',
                    'Target jadwal musim tanam/pemupukan atau periode pelaporan manajemen.',
                ],
            ],
            'eeat_proof' => [
                'eyebrow'  => 'Otoritas Teknologi Agribisnis Nasional',
                'title'    => 'Produsen Drone Pertanian Seri FERTO Bersertifikasi TKDN 60,74% & SNI',
                'intro'    => 'PT Karya Solusi Angkasa (FDS) memproduksi lini drone sprayer pertanian seri FERTO dengan TKDN + BMP 60,74% serta standar SNI 9199:2023 pada FERTO 15 dan FERTO 22, terdaftar pada ekosistem pengadaan pemerintah e-Katalog LKPP.',
                'bullets'  => [
                    'Selain layanan jasa pemetaan dan pemantauan kebun, FDS merancang langsung wahana seri Ferto (5L hingga 50L) serta menyediakan dukungan suku cadang dan pelatihan operator melalui kategori Pelatihan & Bootcamp.',
                    'Pengalaman implementasi lapangan mencakup uji aplikasi di lahan tebu komersial serta dukungan kendali berbahasa Indonesia melalui FDS Ground Control Station.',
                    'Untuk kebun dengan rencana operasi jangka panjang, tim FDS siap mendampingi perbandingan objektif antara kontrak jasa lapangan vs pembentukan unit operator internal.',
                ],
                'portfolio_note' => 'Dokumentasi evaluasi kebun disusun berdasarkan batas blok terverifikasi, catatan kondisi cuaca lapangan, sampel validasi darat untuk sensus tegakan, serta laporan komparatif antarperiode tanam.',
            ],
            'related_capabilities' => [
                [
                    'title' => 'Jasa Foto Multispectral & Hyperspectral',
                    'desc'  => 'Akuisisi citra spektral untuk analisis indeks vegetasi NDVI serta deteksi variasi kesehatan tanaman pada lahan luas.',
                    'url'   => home_url('/layanan/jasa-multispectral-hyperspectral/'),
                ],
                [
                    'title' => 'Kehutanan & Lingkungan',
                    'desc'  => 'Pemetaan tutupan vegetasi rapat, inventarisasi struktur tegakan, dan pemantauan area rehabilitasi.',
                    'url'   => home_url('/layanan/kehutanan-lingkungan/'),
                ],
                [
                    'title' => 'Pelatihan, Bootcamp & Sertifikasi',
                    'desc'  => 'Peningkatan kapasitas tim operator di lapangan untuk pengoperasian drone penyemprotan dan pemetaan.',
                    'url'   => home_url('/layanan/pelatihan-sertifikasi/'),
                ],
            ],
            'reading_links' => [
                ['label' => 'Katalog Lengkap Drone Agrikultur Seri FERTO (5L–50L)', 'url' => home_url('/kategori-drone/agrikultur/')],
                ['label' => 'Bandingkan Spesifikasi Ferto 5, Ferto 10, Ferto 15 hingga 50L', 'url' => home_url('/bandingkan/')],
                ['label' => 'Uji Lapangan FERTO 22L di Lahan Tebu 500 Ha Jawa Timur', 'slug' => 'ferto-22l-berhasil-uji-lapangan-di-lahan-tebu-500-ha-jawa-timur'],
                ['label' => 'Sertifikasi TKDN & Standar Nasional Produksi UAV FDS', 'slug' => 'fds-resmi-raih-sertifikasi-tkdn-4485-dari-kemenperin-ri'],
            ],
            'faqs' => [
                [
                    'q' => 'Layanan apa saja yang tercakup dalam sektor Perkebunan dan Pertanian FDS?',
                    'a' => 'Layanan mencakup Agricultural Drone Spraying (pupuk cair dan pestisida), Plantation Mapping, Crop Health Monitoring, Multispectral Analysis (seperti NDVI), Plantation Inventory, Land & Irrigation Mapping, serta Agricultural UAV Customization.',
                ],
                [
                    'q' => 'Platform drone dan sensor apa yang digunakan pada solusi pertanian dan perkebunan FDS?',
                    'a' => 'Teknologi yang dirujuk mencakup Ferto Agricultural Spraying Drone (seri FERTO 5, FERTO 10, FERTO 15 hingga kapasitas besar 50L), FigSpec FS50 / FS60 Multispectral & Hyperspectral Sensor, Fixed-Wing VTOL DELTAV Mapping UAV, serta FDS Ground Control Station.',
                ],
                [
                    'q' => 'Apa saja hasil akhir (output pekerjaan) yang diterima tim perkebunan?',
                    'a' => 'Output pekerjaan meliputi peta perkebunan, peta blok, ortofoto, peta indeks vegetasi, laporan kondisi tanaman, dokumentasi penyemprotan, dan data spasial pertanian termasuk plantation inventory database dan dashboard pemantauan.',
                ],
                [
                    'q' => 'Apakah drone dapat membantu mengidentifikasi blok tanaman yang pertumbuhannya tidak seragam?',
                    'a' => 'Ya. Melalui citra multispectral, pemetaan NDVI, dan analisis variasi kondisi vegetasi, tim kami membantu memetakan area kosong atau indikasi pertumbuhan tanaman tidak seragam beserta estimasi jumlah tanaman dengan validasi lapangan.',
                ],
                [
                    'q' => 'Kapan kebun sawit atau tanaman tahunan perlu menggunakan Jasa LiDAR dibanding foto udara biasa?',
                    'a' => 'Jika tujuan utama adalah membuat peta kontur tanah dan merancang sistem drainase parit di bawah kanopi sawit dewasa yang tajuknya saling menutup, survei LiDAR diperlukan agar pulsa laser dapat menembus sela daun hingga permukaan tanah.',
                ],
                [
                    'q' => 'Bisakah perusahaan perkebunan membeli unit drone sekaligus melatih tim operator internal?',
                    'a' => 'Ya. Selain layanan survei dan aplikasi berbasis proyek, PT Karya Solusi Angkasa menyediakan pengadaan unit drone pertanian seri FERTO bersertifikat TKDN 60,74% (serta SNI 9199:2023 pada FERTO 15 & FERTO 22) disertai pelatihan operasional melalui program Pelatihan, Bootcamp, dan Sertifikasi.',
                ],
            ],
            'quote_defaults' => [
                'eyebrow'      => 'Konsultasi Perkebunan & Pertanian Presisi',
                'title'        => 'Ajukan Evaluasi Lahan, Pemetaan Blok, atau Solusi Spraying',
                'subtitle'     => 'Sampaikan jenis komoditas, perkiraan luasan afdeling/blok, serta kebutuhan antara layanan survei multispectral/mapping atau pengadaan unit Ferto.',
                'wa_prefill'   => 'Halo tim FDS, kami ingin berkonsultasi mengenai solusi drone perkebunan dan pertanian presisi.',
                'placeholder'  => 'Tuliskan komoditas (kelapa sawit, tebu, padi, dsb.), luas area target, serta pilihan layanan (pemetaan blok/NDVI, penyemprotan, atau pengadaan armada + training)...',
            ],
            'seo' => [
                'meta_title'   => 'Drone Pemetaan Kebun Sawit & Pertanian Presisi | FDS',
                'meta_desc'    => 'Solusi drone pemetaan kebun sawit, penyemprotan presisi Ferto, analisis vegetasi NDVI multispectral, dan inventarisasi blok agribisnis dari FDS.',
                'service_type' => 'Precision Agriculture Drone Spraying and Plantation Mapping',
            ],
            'placeholders' => [
                'Konfirmasi spesifikasi rentang panjang gelombang aktif resmi untuk sensor FigSpec FS50/FS60 yang dioperasikan tim.',
                'Ketentuan luas minimum pengerjaan jasa penyemprotan/pemetaan per kontrak layanan dan paket harga B2B apabila dipublikasikan.',
                'Portofolio resmi klien perkebunan/estate sawit beserta izin publikasi studi kasus.',
            ],
        ],

        // =====================================================================
        // SEKTOR 03: KEHUTANAN DAN LINGKUNGAN
        // =====================================================================
        'kehutanan-lingkungan' => [
            'num'        => '03',
            'slug'       => 'kehutanan-lingkungan',
            'url'        => home_url('/layanan/kehutanan-lingkungan/'),
            'card_title' => 'Kehutanan dan Lingkungan',
            'card_desc'  => 'Solusi pemetaan vegetasi, inventarisasi hutan, estimasi biomassa, pemantauan karbon, serta monitoring rehabilitasi dan restorasi ekosistem.',
            'card_tags'  => [
                ['label' => 'Forest Inventory', 'url' => ''],
                ['label' => 'LiDAR', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')],
                ['label' => 'Carbon Assessment', 'url' => ''],
                ['label' => 'Environmental Monitoring', 'url' => ''],
            ],
            'home_tag'   => 'Kehutanan • LiDAR & Karbon',
            'image'      => $theme_uri . '/public/images/sectors/kehutanan-lingkungan.webp',
            'image_alt'  => 'Pemetaan kanopi hutan, estimasi biomassa, dan inventarisasi vegetasi menggunakan drone LiDAR FDS',

            'h1'           => 'Jasa LiDAR Kehutanan, Inventarisasi Hutan & Stok Karbon',
            'subtitle'     => 'Forest Intelligence, Carbon Assessment & Environmental Monitoring',
            'hero_summary' => 'Solusi drone LiDAR, pencitraan multispectral, inventarisasi hutan, estimasi biomassa dan stok karbon, serta pemantauan rehabilitasi ekosistem.',
            'intro_paragraphs' => [
                'Dukung pengelolaan hutan berkelanjutan melalui teknologi drone LiDAR, multispectral imaging, dan analisis geospasial. FDS menyediakan solusi pemetaan dan monitoring vegetasi untuk mendukung inventarisasi hutan, pengelolaan sumber daya alam, serta evaluasi program rehabilitasi dan restorasi lingkungan.',
                'Integrasi teknologi UAV dengan analisis data memungkinkan pengumpulan informasi struktur vegetasi dan kondisi permukaan yang dapat digunakan sebagai dasar pemantauan serta pengambilan keputusan.',
            ],
            'challenge_section' => [
                'eyebrow'  => 'Konteks Kehutanan & Ekosistem Pesisir',
                'title'    => 'Mengatasi Keterbatasan Plot Sampel Manual & Citra Optik pada Areal Hutan Lebat',
                'paragraphs' => [
                    'Pengelolaan konsesi PBPH, rehabilitasi DAS, restorasi ekosistem, maupun kawasan mangrove pesisir di Indonesia mencakup hamparan dengan akses darat terbatas dan tajuk vegetasi bertingkat. Mengandalkan survei transek plot manual semata hanya menjangkau porsi sangat kecil dari total konsesi, sehingga rentan menimbulkan ketidakpastian saat ekstrapolasi struktur tegakan.',
                    'Sementara itu, citra satelit resolusi menengah dan kamera udara RGB konvensional tidak dapat mengukur profil tinggi tajuk serta permukaan tanah murni di balik dedaunan rapat. Penggabungan akuisisi UAV LiDAR dan sensor multispectral yang dikalibrasi dengan plot lapangan memberi gambaran tiga dimensi yang jauh lebih terukur untuk baseline lingkungan hingga pelaporan karbon.',
                ],
                'pain_points' => [
                    [
                        'title' => 'Kesulitan Mengukur Struktur Tinggi & Kerapatan Kanopi Skala Luas',
                        'desc'  => 'Estimasi Above Ground Biomass (AGB) membutuhkan informasi ketinggian kanopi yang akurat terhadap elevasi permukaan tanah sebenarnya, bukan sekadar peta tutup tajuk 2D.',
                    ],
                    [
                        'title' => 'Akses Sulit pada Hutan Rawa, Gambut & Kawasan Mangrove',
                        'desc'  => 'Survei darat pada ekosistem basah dan berumpun rapat membutuhkan waktu lapangan panjang serta logistik tinggi dengan cakupan area harian terbatas.',
                    ],
                    [
                        'title' => 'Audit Progres Reboisasi & Pemantauan Degradasi Antarperiode',
                        'desc'  => 'Pemilik konsesi dan lembaga konservasi membutuhkan catatan historis yang konsisten untuk membuktikan perkembangan penanaman maupun mendeteksi gangguan hutan secara dini.',
                    ],
                ],
            ],
            'solutions' => [
                [
                    'name'       => 'Forest Inventory Mapping',
                    'desc'       => 'Pemetaan dan inventarisasi struktur vegetasi hutan.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Forest LiDAR Survey',
                    'desc'       => 'Pengumpulan data tiga dimensi untuk analisis struktur kanopi dan permukaan tanah.',
                    'link_url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                    'link_label' => 'Detail Jasa Foto Udara & LiDAR',
                ],
                [
                    'name'       => 'Forest Biomass Estimation',
                    'desc'       => 'Dukungan estimasi biomassa menggunakan data spasial dan parameter lapangan.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Forest Carbon Assessment',
                    'desc'       => 'Dukungan pemetaan dan analisis stok karbon berbasis data LiDAR, multispectral, dan validasi lapangan.',
                    'link_url'   => home_url('/layanan/jasa-multispectral-hyperspectral/'),
                    'link_label' => 'Detail Multispectral & Hyperspectral',
                ],
                [
                    'name'       => 'Vegetation Mapping',
                    'desc'       => 'Pemetaan tutupan dan kondisi vegetasi.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Reforestation Monitoring',
                    'desc'       => 'Monitoring perkembangan rehabilitasi dan reforestasi.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Mangrove Mapping',
                    'desc'       => 'Pemetaan ekosistem mangrove dan pemantauan perubahan kawasan pesisir.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Environmental Monitoring',
                    'desc'       => 'Dokumentasi perubahan kondisi lingkungan.',
                    'link_url'   => home_url('/layanan/bencana-lingkungan/'),
                    'link_label' => 'Lihat Sektor Bencana & Lingkungan',
                ],
            ],
            'method_comparison' => [
                'eyebrow'  => 'Matriks Sensor & Metode Kehutanan',
                'title'    => 'Peran UAV LiDAR, Multispectral & Plot Lapangan dalam Analisis Hutan dan Karbon',
                'subtitle' => 'Estimasi biomassa dan stok karbon yang kredibel tidak berdiri pada satu sumber data tunggal, melainkan integrasi terkalibrasi antara sensor udara dan plot pengukuran darat.',
                'columns'  => ['Parameter Analisis', 'UAV LiDAR Survey', 'Multispectral & Hyperspectral UAV', 'Plot Pengukuran Lapangan (Alometri)'],
                'rows'     => [
                    [
                        'Ekstraksi Permukaan Tanah (DTM) & Tinggi Pohon (CHM)',
                        'Sangat efektif menembus celah kanopi guna memisahkan pucuk tajuk dari permukaan lantai hutan asli.',
                        'Hanya merekam puncak kanopi dan sifat spektral daun; tidak menghasilkan DTM tanah bawah tajuk.',
                        'Pengukuran klinometer/hagameter manual lambat dan terbatas pada sampel luasan kecil.',
                    ],
                    [
                        'Identifikasi Vigor & Variasi Spesies/Vegetasi',
                        'Merekam geometri 3D, kepadatan daun, dan bentuk tajuk, namun tanpa pita spektral warna mendalam.',
                        'Metode utama untuk memantau indeks kesehatan vegetasi, stres tegakan muda, dan variasi spektral tajuk.',
                        'Validasi jenis spesies secara taksonomi langsung oleh rimbawan di dalam plot sampel.',
                    ],
                    [
                        'Pemodelan Biomassa (AGB) & Stok Karbon',
                        'Menyediakan metrik tinggi dan struktur 3D untuk ekstrapolasi spasial ke luar plot sampel.',
                        'Memperkuat stratifikasi kelas kerapatan dan kondisi kesehatan tutupan lahan di area luas.',
                        'Wajib ada sebagai dasar pengukuran DBH batang dan persamaan alometri untuk mengkalibrasi model spasial.',
                    ],
                    [
                        'Monitoring Rehabilitasi DAS & Mangrove Pesisir',
                        'Ideal untuk evaluasi pertumbuhan tegakan vertikal dan hidrodinamika saluran pasang surut.',
                        'Efektif memantau persentase keberhasilan tumbuh anakan dan dinamika garis pantai.',
                        'Digunakan sebagai ground truth berkala pada plot pemantauan permanen.',
                    ],
                ],
            ],
            'closing_paragraphs' => [
                'Full Drone Solutions mendukung pengelola hutan, konsultan lingkungan, dan program restorasi untuk mengubah data penginderaan jauh menjadi peta inventarisasi, baseline ekologi, serta sistem monitoring spasial jangka panjang.',
                'Seluruh metodologi estimasi biomassa dan stok karbon dirancang terpadu antara akuisisi udara dan validasi plot lapangan agar sesuai kebutuhan audit internal maupun tata kelola lingkungan perusahaan.',
            ],
            'deliverable_groups' => [
                [
                    'title' => 'Konsultasi dan Penyusunan Metode Survei Kehutanan',
                    'items' => [
                        'Evaluasi kebutuhan inventarisasi hutan.',
                        'Penyusunan metode survei UAV LiDAR dan multispectral dan hyperspectral.',
                        'Penyusunan metode integrasi data udara dan pengukuran lapangan.',
                        'Evaluasi kebutuhan monitoring karbon dan biomassa.',
                        'Penyusunan rencana monitoring rehabilitasi hutan.',
                    ],
                ],
                [
                    'title' => 'Inventarisasi Tegakan, Struktur Kanopi & Data Dasar',
                    'items' => [
                        'Forest base map dan boundary mapping.',
                        'Canopy density mapping.',
                        'Vegetation structure analysis.',
                        'Pemetaan distribusi dan estimasi tinggi pohon.',
                        'Forest inventory database.',
                        'Forest biomass estimation.',
                    ],
                ],
                [
                    'title' => 'Analisis Biomassa, Karbon, Mangrove dan Rehabilitasi',
                    'items' => [
                        'Above Ground Biomass (AGB) estimation.',
                        'Above Ground Carbon Stock estimation.',
                        'Forest carbon spatial mapping.',
                        'Biomass distribution map.',
                        'Analisis struktur kanopi untuk pemodelan biomassa.',
                        'Integrasi data LiDAR, multispectral, dan plot lapangan.',
                        'Pengembangan model estimasi biomassa.',
                        'Monitoring perubahan biomassa dan stok karbon antarperiode.',
                        'Forest degradation monitoring.',
                        'Forest disturbance mapping.',
                        'Habitat structure documentation.',
                        'Mangrove ecosystem mapping.',
                        'Coastal vegetation monitoring.',
                        'Analisis perubahan tutupan vegetasi.',
                        'Pemetaan area yang memerlukan inspeksi lapangan.',
                        'Restoration area mapping.',
                        'Monitoring perubahan tutupan vegetasi.',
                        'Analisis perubahan kondisi area rehabilitasi.',
                        'Dokumentasi perkembangan penanaman.',
                    ],
                ],
                [
                    'title' => 'Baseline Lingkungan, Database Karbon & Dashboard GIS',
                    'items' => [
                        'Environmental baseline mapping.',
                        'Land cover change analysis.',
                        'Environmental monitoring dashboard.',
                        'Carbon spatial database.',
                        'Pengembangan carbon monitoring system.',
                        'Analisis perubahan tutupan lahan.',
                        'Visualisasi data lingkungan berbasis GIS.',
                    ],
                ],
            ],
            'workflow_steps' => [
                [
                    'step'  => '01',
                    'title' => 'Review Konsesi, Stratifikasi Tutupan & Desain Sampling',
                    'desc'  => 'Peninjauan batas areal kerja, tipe hutan (hutan tanaman, hutan alam, gambut, atau mangrove), dan penyelarasan kebutuhan jumlah plot sampel lapangan.',
                ],
                [
                    'step'  => '02',
                    'title' => 'Akuisisi UAV LiDAR & Multispectral Terintegrasi',
                    'desc'  => 'Pelaksanaan misi terbang pada zona prioritas dengan pengaturan ketinggian dan kerapatan jalur terbang sesuai karakteristik tajuk.',
                ],
                [
                    'step'  => '03',
                    'title' => 'Pengukuran Plot Lapangan & Kalibrasi Geodetik',
                    'desc'  => 'Koordinasi penyelarasan koordinat titik plot ukur DBH dan kontrol lapangan ke dalam sistem referensi spasial konsesi.',
                ],
                [
                    'step'  => '04',
                    'title' => 'Ekstraksi Canopy Height Model, Pemodelan AGB & Database GIS',
                    'desc'  => 'Klasifikasi point cloud, pembentukan DTM/CHM, regresi spasial biomassa/stok karbon terkalibrasi, serta penyusunan peta perubahan tutupan lahan.',
                ],
            ],
            'software_integrations' => [
                'eyebrow'  => 'Integrasi Data Kehutanan & Lingkungan',
                'title'    => 'Output Spasial Terbuka untuk Tim Perencanaan Hutan & Karbon',
                'subtitle' => 'Hasil pemetaan terstruktur agar mudah diaudit bersama data inventarisasi hutan dan sistem pelaporan spasial institusi.',
                'formats'  => [
                    'Point Cloud Struktur Tajuk & Ground (.LAS / .LAZ)',
                    'Digital Terrain Model (DTM), DSM & Canopy Height Model (CHM GeoTIFF)',
                    'Peta Stratifikasi Tutupan Lahan, Mangrove & Area Rehabilitasi (.SHP / .GeoJSON)',
                    'Peta Sebaran Biomassa (AGB) & Estimasi Stok Karbon beserta Laporan Metodologi (.PDF / .XLSX)',
                    'Carbon Spatial Database & Environmental Monitoring Dashboard berbasis GIS',
                ],
                'tools'    => ['QGIS', 'ArcGIS Pro', 'Global Mapper', 'R / Python Geospatial Analysis', 'WebGIS Enterprise'],
            ],
            'cost_framework' => [
                'eyebrow'  => 'Struktur Perencanaan Survei Hutan',
                'title'    => 'Faktor Penentu Biaya Jasa LiDAR Kehutanan & Pemetaan Karbon',
                'intro'    => 'Pendekatan survei kehutanan umumnya memakai metode blok prioritas atau koridor sampel untuk menjaga efisiensi anggaran pada konsesi sangat luas.',
                'factors'  => [
                    [
                        'name' => 'Luasan Target Terbang (Full Wall-to-Wall vs Sampling Koridor)',
                        'desc' => 'Konsesi puluhan ribu hektar sering memakai pendekatan strip/sampling LiDAR dipadukan citra luas untuk mengoptimalkan biaya.',
                    ],
                    [
                        'name' => 'Kerapatan Kanopi & Kombinasi Sensor',
                        'desc' => 'Penggunaan LiDAR tunggal berbeda kebutuhannya dengan kombinasi serentak LiDAR dan sensor multispectral/hyperspectral.',
                    ],
                    [
                        'name' => 'Aksesibilitas Kawasan & Logistik Ekosistem Rawa/Pesisir',
                        'desc' => 'Akomodasi pos hutan terpencil, akses sungai/perahu di kawasan mangrove atau gambut, dan izin masuk kawasan konservasi memengaruhi mobilisasi.',
                    ],
                    [
                        'name' => 'Lingkup Pemodelan Biomassa & Plot Validasi Lapangan',
                        'desc' => 'Apakah tim klien menyediakan data plot ukur alometri internal atau membutuhkan pendampingan pengumpulan dan pemodelan penuh bersama tim ahli.',
                    ],
                ],
                'kak_checklist' => [
                    'Lokasi kabupaten/provinsi areal konsesi, blok RKL/RKT, atau DAS target beserta estimasi luasan.',
                    'Tipe ekosistem dominan (hutan produksi, hutan alam, lahan gambut, kawasan mangrove, atau area rehabilitasi pascatambang).',
                    'Tujuan utama data (inventarisasi tegakan, baseline estimasi karbon, atau monitoring keberhasilan penanaman).',
                    'Ketersediaan data plot pengukuran lapangan eksisting yang perlu diintegrasikan.',
                ],
            ],
            'eeat_proof' => [
                'eyebrow'  => 'Kapabilitas Riset & Rekayasa UAV Nasional',
                'title'    => 'Berpengalaman Mendukung Riset Manajemen DAS & Ekosistem Berkelanjutan',
                'intro'    => 'PT Karya Solusi Angkasa (FDS) memiliki rekam jejak kolaborasi riset pemetaan udara untuk manajemen Daerah Aliran Sungai (DAS) serta manufaktur UAV Fixed-Wing VTOL DELTAV yang mampu menjangkau bentangan hutan luas tanpa memerlukan landasan pacu konvensional.',
                'bullets'  => [
                    'Integrasi sensor multispectral dan hyperspectral FigSpec FS50/FS60 dengan platform UAV memberi fleksibilitas analisis ekologi dan kerapatan vegetasi.',
                    'Transparansi ilmiah: FDS secara eksplisit menyatakan bahwa pemodelan stok karbon dan AGB dari udara memerlukan integrasi dan validasi terhadap pengukuran plot lapangan (ground plot) — tanpa mengklaim angka stok karbon secara sepihak tanpa data kalibrasi.',
                ],
                'portfolio_note' => 'Dokumentasi proyek kehutanan dan rehabilitasi disusun berdasarkan stratifikasi tajuk, lokasi transek kontrol darat, serta riwayat monitoring antarperiode guna keperluan pelaporan tata kelola lingkungan.',
            ],
            'related_capabilities' => [
                [
                    'title' => 'Jasa Foto Udara dan LiDAR',
                    'desc'  => 'Penetrasi kanopi vegetasi rapat untuk mengekstrak elevasi permukaan tanah murni (DTM) dan struktur tegakan.',
                    'url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                ],
                [
                    'title' => 'Jasa Foto Multispectral & Hyperspectral',
                    'desc'  => 'Pencitraan spektral detail untuk pemantauan degradasi hutan, pemetaan stres vegetasi, dan kawasan rehabilitasi.',
                    'url'   => home_url('/layanan/jasa-multispectral-hyperspectral/'),
                ],
                [
                    'title' => 'Perkebunan dan Pertanian',
                    'desc'  => 'Pemetaan batas area produksi, pemantauan kesehatan tanaman, dan inventarisasi tegakan berbasis GIS.',
                    'url'   => home_url('/layanan/perkebunan-pertanian/'),
                ],
            ],
            'reading_links' => [
                ['label' => 'Kolaborasi FDS dan UGM: Riset Drone Pemetaan untuk Manajemen DAS', 'slug' => 'kolaborasi-fds-dan-ugm-riset-drone-pemetaan-untuk-manajemen-das'],
                ['label' => 'Jasa Foto Udara & LiDAR Lintas Sektor', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')],
                ['label' => 'Spesifikasi Drone Pemetaan Fixed-Wing VTOL DELTAV', 'url' => home_url('/drone/deltav/')],
                ['label' => 'Memahami Struktur Biaya Survei LiDAR di Indonesia', 'slug' => 'biaya-survei-lidar-indonesia'],
            ],
            'faqs' => [
                [
                    'q' => 'Bagaimana teknologi drone LiDAR dan multispectral bekerja untuk pemetaan kehutanan?',
                    'a' => 'Drone LiDAR mengumpulkan data tiga dimensi struktur kanopi sekaligus permukaan tanah murni di balik celah tajuk (Forest LiDAR Survey), sedangkan multispectral menangkap kondisi reflektansi vegetasi untuk memantau tutupan, degradasi, maupun area rehabilitasi.',
                ],
                [
                    'q' => 'Apakah estimasi biomassa dan stok karbon dilakukan sepenuhnya dari udara tanpa cek lapangan?',
                    'a' => 'Tidak. Dalam layanan Forest Biomass Estimation dan Forest Carbon Assessment FDS, data LiDAR dan multispectral selalu diintegrasikan dengan data parameter dan plot pengukuran lapangan (alometri) untuk memvalidasi pemodelan biomassa di atas permukaan tanah (AGB).',
                ],
                [
                    'q' => 'Apakah FDS melayani pemetaan ekosistem mangrove dan kawasan rehabilitasi pesisir?',
                    'a' => 'Ya. Ruang lingkup layanan mencakup Mangrove Mapping, Coastal Vegetation Monitoring, Restoration Area Mapping, dan dokumentasi perkembangan penanaman pada program reforestasi maupun pemulihan DAS.',
                ],
                [
                    'q' => 'Kapan konsesi hutan sebaiknya menggunakan Fixed-Wing VTOL dibanding multirotor untuk pemetaan?',
                    'a' => 'Pada blok hutan atau kawasan pesisir yang luas serta minim ruang terbuka untuk lepas landas datar, platform Fixed-Wing VTOL seperti DELTAV memungkinkan jangkauan terbang jauh lebih panjang sambil tetap mendarat vertikal di celah terbuka kecil.',
                ],
                [
                    'q' => 'Apa saja deliverables yang diserahkan untuk kebutuhan inventarisasi hutan dan karbon?',
                    'a' => 'Output mencakup Forest Base Map, Canopy Density Map, estimasi distribusi tinggi pohon, Forest Biomass & Carbon Distribution Map, Forest Inventory Database, Carbon Spatial Database, hingga Environmental Monitoring Dashboard.',
                ],
                [
                    'q' => 'Bagaimana memulai diskusi penyusunan metode survei untuk areal hutan atau program restorasi kami?',
                    'a' => 'Kirimkan informasi lokasi dan perkiraan luas kawasan, jenis ekosistem target, serta ketersediaan data plot darat melalui halaman Kontak atau WhatsApp resmi FDS agar tim FDS dapat menyusun rekomendasi metode dan rancangan kebutuhan kerja.',
                ],
            ],
            'quote_defaults' => [
                'eyebrow'      => 'Konsultasi Survei Kehutanan & Karbon',
                'title'        => 'Diskusikan Strategi Pemetaan Hutan, Mangrove & Monitoring Karbon',
                'subtitle'     => 'Sampaikan lokasi kawasan dan tujuan inventarisasi untuk menyusun rancangan integrasi LiDAR, multispectral, dan validasi plot.',
                'wa_prefill'   => 'Halo tim FDS, kami ingin berkonsultasi mengenai layanan pemetaan kehutanan / LiDAR hutan / monitoring karbon.',
                'placeholder'  => 'Jelaskan jenis tutupan lahan (hutan produksi, mangrove, gambut, atau area rehabilitasi/reklamasi), luas area target, serta kebutuhan analisis AGB/karbon...',
            ],
            'seo' => [
                'meta_title'   => 'Jasa LiDAR Kehutanan, Biomassa & Karbon Hutan | FDS',
                'meta_desc'    => 'Layanan drone LiDAR kehutanan, inventarisasi tegakan, estimasi biomassa karbon, pemetaan mangrove, dan monitoring rehabilitasi ekosistem dari FDS.',
                'service_type' => 'Forestry LiDAR Mapping, Biomass Estimation and Carbon Stock Assessment',
            ],
            'placeholders' => [
                'Metodologi alometrik baku atau standar protokol sertifikasi karbon spesifik (seperti SRN PPI / Verra / standar klien) bila tim menginginkan pencantuman referensi spesifik.',
                'Contoh proyek inventarisasi hutan, mangrove, atau rehabilitasi DAS terverifikasi yang dapat dipublikasikan beserta data plot validasinya.',
                'Tarif indikatif atau skema biaya untuk pemetaan sampling strip vs cakupan penuh areal konsesi.',
            ],
        ],

        // =====================================================================
        // SEKTOR 04: ENERGI DAN INFRASTRUKTUR
        // =====================================================================
        'energi-infrastruktur' => [
            'num'        => '04',
            'slug'       => 'energi-infrastruktur',
            'url'        => home_url('/layanan/energi-infrastruktur/'),
            'card_title' => 'Energi dan Infrastruktur',
            'card_desc'  => 'Teknologi UAV untuk inspeksi aset, pemetaan koridor, pemantauan fasilitas industri, serta pengumpulan data infrastruktur secara efisien.',
            'card_tags'  => [
                ['label' => 'Asset Inspection', 'url' => ''],
                ['label' => 'Thermal Imaging', 'url' => ''],
                ['label' => 'LiDAR', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')],
                ['label' => 'Infrastructure Mapping', 'url' => ''],
            ],
            'home_tag'   => 'Energi • Inspeksi Termal',
            'image'      => $theme_uri . '/public/images/sectors/energi-infrastruktur.webp',
            'image_alt'  => 'Inspeksi visual dan termal fasilitas pembangkit surya dan koridor infrastruktur menggunakan drone FDS',

            'h1'           => 'Jasa Inspeksi Drone Infrastruktur, Termal & LiDAR Koridor',
            'subtitle'     => 'Intelligent Asset Inspection & Infrastructure Mapping',
            'hero_summary' => 'Solusi UAV inspeksi visual jaringan transmisi, inspeksi termal pembangkit tenaga surya, LiDAR koridor utilitas, dan monitoring konstruksi.',
            'intro_paragraphs' => [
                'Tingkatkan efektivitas inspeksi dan pemantauan infrastruktur melalui teknologi UAV. FDS menyediakan solusi pemetaan dan inspeksi udara untuk sektor energi, utilitas, konstruksi, dan fasilitas industri.',
                'Drone dapat membantu mengumpulkan data visual dan spasial pada area yang luas atau sulit dijangkau, sehingga mendukung proses inspeksi, dokumentasi kondisi aset, dan perencanaan pemeliharaan.',
            ],
            'challenge_section' => [
                'eyebrow'  => 'Konteks Operasional Aset Kritis & Utilitas',
                'title'    => 'Menekan Risiko Keselamatan & Blind Spot Inspeksi pada Koridor Energi Panjang',
                'paragraphs' => [
                    'Aset energi dan utilitas — mulai dari jaringan transmisi tegangan tinggi (SUTT/SUTET), pembangkit listrik tenaga surya (solar farm), jalur pipa, hingga pembangunan jalan tol, jembatan, dan kawasan industri — terbentang di atas medan beragam dan struktur tinggi yang berisiko bila diperiksa seluruhnya dengan pemanjatan manual atau penghentian operasional berkala.',
                    'Pada jaringan transmisi, pertumbuhan vegetasi di sekitar Right-of-Way (ROW) sering baru diketahui saat jarak bebas terhadap konduktor sudah tipis. Sementara pada pembangkit surya berskala besar, anomali termal seperti hotspot modul PV atau sambungan inverter kerap luput dari inspeksi visual kasat mata sebelum berdampak pada performa pembangkit.',
                ],
                'pain_points' => [
                    [
                        'title' => 'Patroli Manual Darat Lambat pada Jalur Koridor Panjang',
                        'desc'  => 'Pemeriksaan koridor menembus lembah, sungai, dan semak secara berjalan kaki membutuhkan waktu panjang untuk memetakan ancaman encroachment vegetasi.',
                    ],
                    [
                        'title' => 'Ketidakmampuan Mendeteksi Anomali Suhu Tanpa Sensor Termal',
                        'desc'  => 'Kerusakan sel surya, sambungan kabel longgar, atau komponen isolator panas berlebih tidak selalu terlihat dari permukaan RGB biasa.',
                    ],
                    [
                        'title' => 'Kesulitan Membandingkan Progres Konstruksi Antarperiode Secara Spasial',
                        'desc'  => 'Foto dokumentasi proyek yang tidak tergeoreferensi sulit dipakai menghitung volume timbunan/galian (earthwork) dan dokumentasi as-built aktual.',
                    ],
                ],
            ],
            'solutions' => [
                [
                    'name'       => 'Power Line Inspection',
                    'desc'       => 'Dokumentasi dan inspeksi visual jaringan transmisi serta distribusi listrik.',
                    'link_url'   => home_url('/drone/multipurpose/'),
                    'link_label' => 'Lihat Platform Inspeksi UAV',
                ],
                [
                    'name'       => 'Thermal Inspection',
                    'desc'       => 'Pengumpulan citra termal untuk mendukung pemeriksaan kondisi aset yang sesuai.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Solar Farm Inspection',
                    'desc'       => 'Monitoring visual dan termal fasilitas pembangkit listrik tenaga surya.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Infrastructure Mapping',
                    'desc'       => 'Pemetaan fasilitas industri, jalan, jembatan, dan kawasan infrastruktur.',
                    'link_url'   => home_url('/layanan/perencanaan/'),
                    'link_label' => 'Lihat Sektor Perencanaan & Tata Ruang',
                ],
                [
                    'name'       => 'LiDAR Corridor Mapping',
                    'desc'       => 'Pemetaan koridor infrastruktur menggunakan teknologi LiDAR.',
                    'link_url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                    'link_label' => 'Detail Jasa Foto Udara & LiDAR',
                ],
                [
                    'name'       => 'Construction Progress Monitoring',
                    'desc'       => 'Dokumentasi perkembangan konstruksi.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Industrial Asset Inspection',
                    'desc'       => 'Inspeksi visual menggunakan kamera zoom dan sensor khusus.',
                    'link_url'   => home_url('/drone/multipurpose/'),
                    'link_label' => 'Pelajari UAV MULTIPURPOSE',
                ],
            ],
            'method_comparison' => [
                'eyebrow'  => 'Matriks Teknologi Inspeksi & Pemetaan Aset',
                'title'    => 'Memilih Metode Udara untuk Jalur Transmisi, Solar Farm & Proyek Konstruksi',
                'subtitle' => 'Setiap target infrastruktur memadukan kombinasi sensor kamera zoom, sensor termal, atau laser scanning agar tidak ada tahapan inspeksi yang tumpang tindih.',
                'columns'  => ['Skenario Infrastruktur', 'Kamera Zoom Visual & Termal UAV', 'LiDAR Corridor & Mapping UAV', 'Inspeksi Manual / Pemanjatan Konvensional'],
                'rows'     => [
                    [
                        'Jalur Transmisi & Encroachment Vegetasi (ROW)',
                        'Optimal memeriksa kondisi fisik isolator, kawat konduktor, menara, dan titik sambungan berpotensi panas.',
                        'Metode utama memetakan jarak tiga dimensi antara kanopi vegetasi koridor terhadap aset jaringan serta elevasi tanah.',
                        'Sulit mengukur jarak vertikal vegetasi-konduktor secara kontinu pada lintasan panjang bervegetasi.',
                    ],
                    [
                        'Pembangkit Listrik Tenaga Surya (Solar Farm)',
                        'Menggabungkan ortofoto visual & pemetaan anomali termal antarblok panel sebagai bahan verifikasi teknisi listrik.',
                        'Berperan pada fase pra-konstruksi lahan dan pemetaan topografi serta saluran drainase area PLTS.',
                        'Pemeriksaan handheld per baris memakan waktu jauh lebih lama untuk kapasitas multi-megawatt.',
                    ],
                    [
                        'Jalan, Jembatan & Kawasan Industri Aktif',
                        'Membantu dokumentasi visual struktur tinggi yang dapat dijangkau garis pandang sensor tanpa perancah.',
                        'Menghasilkan DSM/DTM koridor, volume pekerjaan earthwork (cut and fill), serta dokumentasi as-built spasial.',
                        'Dibutuhkan saat pengujian fisik non-destruktif yang menuntut kontak alat langsung di struktur beton/baja.',
                    ],
                ],
            ],
            'closing_paragraphs' => [
                'Melalui kombinasi wahana UAV stabil, sensor kamera zoom, citra termal, dan pemetaan LiDAR koridor, Full Drone Solutions membantu tim O&M energi, kontraktor EPC, serta pengelola kawasan industri mendokumentasikan kesehatan aset secara berkala.',
                'Data hasil survei dirangkai menjadi catatan kondisi historis yang mempermudah prioritisasi pemeliharaan preventif dan pelaporan kemajuan proyek konstruksi.',
            ],
            'deliverable_groups' => [
                [
                    'title' => 'Perencanaan dan Prioritisasi Inspeksi Aset',
                    'items' => [
                        'Infrastructure asset assessment.',
                        'Asset mapping strategy.',
                        'Penyusunan prioritas aset untuk inspeksi.',
                    ],
                ],
                [
                    'title' => 'Pemetaan Koridor Transmisi, Tower dan Vegetasi',
                    'items' => [
                        'Power transmission corridor mapping.',
                        'Pemetaan jalur transmisi dan distribusi.',
                        'Peta lokasi tower dan fasilitas pendukung.',
                        'Pemetaan akses dan kondisi lingkungan koridor.',
                        'Vegetation encroachment mapping.',
                        'Digital surface model koridor.',
                        'Peta jarak vegetasi terhadap aset berdasarkan metode dan data yang sesuai.',
                    ],
                ],
                [
                    'title' => 'Inspeksi Visual, Termal dan Fasilitas Pembangkit Surya',
                    'items' => [
                        'Visual asset inspection.',
                        'Tower and structure documentation.',
                        'Inspeksi visual komponen aset yang dapat dijangkau sensor.',
                        'Thermal imaging inspection.',
                        'Anomali termal untuk pemeriksaan lebih lanjut.',
                        'Dokumentasi kondisi aset antarperiode.',
                        'Solar panel visual inspection.',
                        'Thermal anomaly mapping.',
                        'Pemetaan akses dan topografi area.',
                        'Monitoring perubahan kondisi fasilitas.',
                        'Solar farm inspection report.',
                    ],
                ],
                [
                    'title' => 'Survei Konstruksi, Earthwork dan Dokumentasi As-Built',
                    'items' => [
                        'Construction site mapping.',
                        'Progress documentation.',
                        'Construction progress comparison.',
                        'Earthwork volume calculation.',
                        'Cut and fill analysis.',
                        'Pemetaan jalan, jembatan, dan fasilitas.',
                        'Pemetaan koridor infrastruktur.',
                        'As-built documentation berbasis survei udara.',
                    ],
                ],
                [
                    'title' => 'Sistem Informasi Spasial dan Dashboard Aset Infrastruktur',
                    'items' => [
                        'Construction progress dashboard.',
                        'Asset spatial database.',
                        'Infrastructure GIS dashboard.',
                        'Historical inspection database.',
                        'Asset condition visualization.',
                        'Geospatial documentation system.',
                        'Monitoring aset berdasarkan periode.',
                    ],
                ],
            ],
            'workflow_steps' => [
                [
                    'step'  => '01',
                    'title' => 'Asset Assessment & Koordinasi Izin Objek Vital',
                    'desc'  => 'Identifikasi jenis aset, panjang koridor atau luas fasilitas, batas keselamatan area bertegangan, dan kepatuhan regulasi izin terbang sekitar objek vital nasional.',
                ],
                [
                    'step'  => '02',
                    'title' => 'Akuisisi Visual, Termal, atau Laser Scanning Koridor',
                    'desc'  => 'Penerbangan terencana dengan profil jarak aman aset serta waktu terbang optimal sesuai kebutuhan pembacaan termal atau ketelitian geometri koridor.',
                ],
                [
                    'step'  => '03',
                    'title' => 'Klasifikasi Koridor, Anomali & Perhitungan Earthwork',
                    'desc'  => 'Pemisahan point cloud koridor, penandaan titik anomali termal dan visual beserta koordinatnya, atau perhitungan volume timbunan/galian proyek konstruksi.',
                ],
                [
                    'step'  => '04',
                    'title' => 'Pelaporan Terstruktur & Historical Inspection Database',
                    'desc'  => 'Serah terima peta koridor, solar farm inspection report, perbandingan progres konstruksi antarperiode, hingga visualisasi GIS dashboard aset.',
                ],
            ],
            'software_integrations' => [
                'eyebrow'  => 'Integrasi Dokumentasi Aset & EPC',
                'title'    => 'Kompatibel dengan Alur Kerja Tim O&M Energi dan Konstruksi Sipil',
                'subtitle' => 'Setiap foto inspeksi, titik anomali termal, dan peta koridor tergeoreferensi agar mudah dilacak langsung ke lokasi menara, blok PV, atau STA proyek.',
                'formats'  => [
                    'Point Cloud & Model Koridor Transmisi (.LAS / .LAZ & DSM GeoTIFF)',
                    'Peta Lokasi Tower, Akses Koridor & Vegetation Encroachment (.SHP / .GeoJSON / .DWG)',
                    'Citra Ortofoto Proyek Konstruksi & Model Permukaan Earthwork (Cut & Fill)',
                    'Laporan Anomali Termal Solar Farm / Struktur Industri Terkoordinat (.PDF + Tag Lokasi)',
                    'As-Built Documentation & Historical Inspection Database terintegrasi GIS',
                ],
                'tools'    => ['AutoCAD Civil 3D', 'ArcGIS', 'QGIS', 'Global Mapper', 'Software Pengolahan Termal & O&M Enterprise'],
            ],
            'cost_framework' => [
                'eyebrow'  => 'Perencanaan Biaya Inspeksi & Pemetaan Aset',
                'title'    => 'Faktor Penentu Biaya Layanan Inspeksi Infrastruktur & Koridor',
                'intro'    => 'Struktur biaya pada proyek infrastruktur dipengaruhi oleh bentuk aset (area kompak seperti kawasan industri/PLTS vs koridor linear panjang) dan kombinasi payload sensor.',
                'factors'  => [
                    [
                        'name' => 'Jenis Aset & Panjang Koridor / Kapasitas Fasilitas',
                        'desc' => 'Survei koridor jalan/transmisi sepanjang puluhan kilometer membutuhkan perpindahan titik peluncuran serta Base GNSS berulang dibanding site PLTS kompak.',
                    ],
                    [
                        'name' => 'Kombinasi Sensor (Zoom Optik vs Termal vs LiDAR)',
                        'desc' => 'Inspeksi menara jarak dekat dengan kamera zoom/termal memerlukan pola terbang berbeda dibanding akuisisi koridor dengan sensor LiDAR.',
                    ],
                    [
                        'name' => 'Prosedur Izin Terbang & Kepatuhan HSE Objek Vital',
                        'desc' => 'Pengamanan area utilitas aktif, jadwal jendela operasi pembangkit, serta pemeriksaan K3 sebelum akuisisi memengaruhi jadwal hari kerja tim.',
                    ],
                    [
                        'name' => 'Frekuensi Monitoring & Integrasi Dashboard',
                        'desc' => 'Pemantauan progres konstruksi periodik atau inspeksi rutin antarsemester memberi efisiensi desain kerja berulang.',
                    ],
                ],
                'kak_checklist' => [
                    'Lokasi jalur koridor atau fasilitas (kota/provinsi), panjang kilometer/hektar, atau jumlah unit menara/kapasitas PLTS.',
                    'Tujuan inspeksi (vegetation encroachment ROW, inspeksi visual menara, thermal anomaly solar farm, atau cut & fill konstruksi).',
                    'Ketentuan HSE site, izin lingkungan objek vital, atau kebutuhan koordinasi dengan tim operasi listrik/konstruksi.',
                    'Format laporan inspeksi atau CAD/GIS yang diharapkan oleh tim internal.',
                ],
            ],
            'eeat_proof' => [
                'eyebrow'  => 'Kesiapan Rekayasa & Operasi Lapangan',
                'title'    => 'Dukungan Wahana UAV Multipurpose & Pengawasan Mutu Indonesia',
                'intro'    => 'Sebagai produsen UAV dengan sistem manajemen mutu ISO 9001:2015, FDS merancang platform seperti MULTIPURPOSE serta Fixed-Wing VTOL DELTAV untuk menjangkau skenario inspeksi industri maupun koridor panjang di Indonesia.',
                'bullets'  => [
                    'Laporan anomali termal dan inspeksi visual disusun secara objektif sebagai alat bantu penapisan kondisi (screening) untuk pemeriksaan teknis lanjutan oleh engineer utilitas bersertifikat.',
                    'Seluruh dokumentasi koridor maupun as-built konstruksi dipadukan dengan kerangka kendali geodetik agar posisi objek dan infrastruktur sesuai koordinat desain.',
                ],
                'portfolio_note' => 'Dokumentasi proyek aset energi dan infrastruktur meliputi peta jalur/fasilitas, indeks koordinat temuan inspeksi atau area encroachment, serta rekam jejak pembanding antarperiode pemeliharaan.',
            ],
            'related_capabilities' => [
                [
                    'title' => 'Jasa Foto Udara dan LiDAR',
                    'desc'  => 'Akuisisi koridor jaringan transmisi, Digital Surface Model, serta pemetaan area infrastruktur skala besar.',
                    'url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                ],
                [
                    'title' => 'Perencanaan Wilayah & Kawasan',
                    'desc'  => 'Dukungan survei topografi, analisis cut and fill, dan dokumentasi as-built untuk pengembangan proyek konstruksi.',
                    'url'   => home_url('/layanan/perencanaan/'),
                ],
                [
                    'title' => 'Sektor Pertambangan',
                    'desc'  => 'Pemetaan hauling road, drainase, serta inspeksi fasilitas operasional industri tambang.',
                    'url'   => home_url('/layanan/pertambangan/'),
                ],
            ],
            'reading_links' => [
                ['label' => 'Platform UAV MULTIPURPOSE untuk Inspeksi Optik & Termal', 'url' => home_url('/drone/multipurpose/')],
                ['label' => 'Fixed-Wing VTOL DELTAV untuk Pemetaan Koridor Panjang', 'url' => home_url('/drone/deltav/')],
                ['label' => 'Jasa Foto Udara & Pemetaan LiDAR Koridor', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')],
                ['label' => 'Komparasi Komponen Biaya Survei LiDAR di Indonesia', 'slug' => 'biaya-survei-lidar-indonesia'],
            ],
            'faqs' => [
                [
                    'q' => 'Jenis infrastruktur energi apa saja yang dapat diinspeksi menggunakan drone FDS?',
                    'a' => 'Layanan mencakup inspeksi visual dan termal pada jaringan transmisi serta distribusi listrik (power line), fasilitas pembangkit listrik tenaga surya (solar farm), jalan, jembatan, lokasi konstruksi, serta fasilitas industri.',
                ],
                [
                    'q' => 'Apa hasil dari layanan LiDAR Corridor Mapping pada jaringan transmisi listrik?',
                    'a' => 'Output mencakup power transmission corridor mapping, peta lokasi tower dan fasilitas pendukung, digital surface model koridor, vegetation encroachment mapping, serta peta jarak vegetasi terhadap aset berdasarkan metode dan data yang sesuai.',
                ],
                [
                    'q' => 'Bagaimana sensor termal membantu pemeriksaan fasilitas PLTS dan utilitas?',
                    'a' => 'Thermal imaging inspection menghasilkan pemetaan anomali termal dan dokumentasi kondisi panel surya maupun aset energi antarperiode guna mendukung identifikasi komponen yang memerlukan pemeriksaan lanjutan.',
                ],
                [
                    'q' => 'Apakah inspeksi visual dan termal menggunakan drone memerlukan pemadaman total sistem listrik?',
                    'a' => 'Pada banyak kasus pemeriksaan eksternal non-kontak dari jarak aman terbang sesuai regulasi keselamatan site, akuisisi visual dan termal dapat dijadwalkan tanpa penghentian panjang, selama koordinasi keselamatan dan izin area utilitas dipenuhi.',
                ],
                [
                    'q' => 'Apakah progres proyek konstruksi infrastruktur dapat dipantau secara berkala?',
                    'a' => 'Ya. FDS menyediakan construction progress comparison, perhitungan earthwork volume (cut and fill analysis), as-built documentation berbasis survei udara, serta construction progress dashboard.',
                ],
                [
                    'q' => 'Bagaimana cara mengajukan permintaan penawaran untuk inspeksi jalur transmisi atau PLTS?',
                    'a' => 'Sampaikan lokasi aset, perkiraan panjang koridor atau kapasitas PLTS, dan jenis output inspeksi yang dibutuhkan melalui halaman Kontak atau WhatsApp resmi FDS untuk evaluasi teknis awal.',
                ],
            ],
            'quote_defaults' => [
                'eyebrow'      => 'Permintaan Penawaran Energi & Infrastruktur',
                'title'        => 'Konsultasikan Inspeksi Aset, Termal PLTS, atau LiDAR Koridor',
                'subtitle'     => 'Kirimkan lokasi proyek, perkiraan panjang koridor atau kapasitas fasilitas, beserta cakupan inspeksi untuk menyusun metode kerja dan estimasi.',
                'wa_prefill'   => 'Halo tim FDS, kami ingin berkonsultasi mengenai jasa inspeksi aset / termal / pemetaan koridor infrastruktur.',
                'placeholder'  => 'Sebutkan jenis aset (transmisi SUTT/SUTET, PLTS/solar farm, jalan/jembatan, atau kawasan industri), panjang koridor/kapasitas, dan jenis inspeksi...',
            ],
            'seo' => [
                'meta_title'   => 'Jasa Inspeksi Drone Infrastruktur, Termal & LiDAR | FDS',
                'meta_desc'    => 'Jasa inspeksi drone jaringan listrik, termal solar farm, LiDAR koridor utilitas, pemetaan fasilitas industri, dan monitoring konstruksi dari FDS.',
                'service_type' => 'Infrastructure Drone Mapping, Power Line & Thermal Asset Inspection',
            ],
            'placeholders' => [
                'Model spesifik kamera zoom optik dan kamera termal radiometrik yang dioperasikan tim inspeksi infrastruktur FDS.',
                'Kualifikasi khusus keselamatan kelistrikan atau protokol jarak terbang minimum menara tegangan tinggi yang diterapkan tim FDS.',
                'Studi kasus proyek inspeksi jaringan transmisi, PLTS, atau pemantauan konstruksi terverifikasi untuk publikasi.',
            ],
        ],

        // =====================================================================
        // SEKTOR 05: PERENCANAAN
        // =====================================================================
        'perencanaan' => [
            'num'        => '05',
            'slug'       => 'perencanaan',
            'url'        => home_url('/layanan/perencanaan/'),
            'card_title' => 'Perencanaan',
            'card_desc'  => 'Data spasial dan pemetaan udara untuk mendukung perencanaan wilayah, pengembangan kawasan, konstruksi, tata ruang, dan studi kelayakan proyek.',
            'card_tags'  => [
                ['label' => 'Topographic Survey', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')],
                ['label' => 'GIS', 'url' => ''],
                ['label' => 'Orthophoto', 'url' => ''],
                ['label' => '3D Mapping', 'url' => ''],
            ],
            'home_tag'   => 'Perencanaan • Topografi & GIS',
            'image'      => $theme_uri . '/public/images/sectors/perencanaan.webp',
            'image_alt'  => 'Survei topografi dan pemetaan udara 3D untuk perencanaan kawasan dan tata ruang wilayah FDS',

            'h1'           => 'Jasa Survei Topografi Drone, Peta Kontur & Data Spasial',
            'subtitle'     => 'Geospatial Intelligence for Better Planning',
            'hero_summary' => 'Layanan survei topografi, pemetaan udara ortofoto, pemodelan 3D terrain, analisis cut and fill, serta integrasi data GIS untuk pengembangan kawasan.',
            'intro_paragraphs' => [
                'Data spasial yang akurat merupakan dasar penting dalam perencanaan dan pengembangan suatu kawasan. FDS menyediakan layanan survei udara dan pemetaan geospasial untuk mendukung perencanaan wilayah, tata ruang, konstruksi, pengembangan lahan, dan proyek infrastruktur.',
                'Melalui teknologi drone photogrammetry, LiDAR, dan pengolahan data geospasial, FDS membantu menghasilkan informasi permukaan bumi yang dapat digunakan sebagai bahan analisis teknis dan perencanaan.',
            ],
            'challenge_section' => [
                'eyebrow'  => 'Konteks Tata Ruang, KEK & Pengembangan Lahan',
                'title'    => 'Mencegah Revisi Masterplan dan Salah Hitung Cut & Fill Sejak Tahap Studi Kelayakan',
                'paragraphs' => [
                    'Pada pengembangan kawasan industri, Kawasan Ekonomi Khusus (KEK), perumahan skala kota, maupun penataan wilayah RTRW/RDTR, peta dasar yang usang sering tidak lagi mencerminkan penggunaan lahan terkini, saluran drainase alami, maupun beda tinggi aktual di lapangan.',
                    'Kesalahan pada model elevasi awal berdampak berantai terhadap rancangan kemiringan jalan, kapasitas saluran pematusan air hujan, hingga anggaran pematangan lahan (cut and fill). Karena itu, pemilihan metode antara drone photogrammetry dan LiDAR di awal studi sangat menentukan kualitas dasar rekayasa arsitek dan konsultan sipil.',
                ],
                'pain_points' => [
                    [
                        'title' => 'Citra Satelit Umum Tidak Cukup Detail untuk Skala Teknik',
                        'desc'  => 'Resolusi dan tanggal perekaman citra gratis tidak mampu menggambarkan kondisi mikro-topografi, parit sempit, maupun perkembangan kawasan terbangun mutakhir.',
                    ],
                    [
                        'title' => 'Kesalahan Memilih Metode pada Lahan Campuran Semak & Terbuka',
                        'desc'  => 'Memaksakan foto udara di area bersemak lebat dapat memicu estimasi timbunan atau galian yang meleset ketika ekskavator mulai pembersihan lahan.',
                    ],
                    [
                        'title' => 'Data Terpecah Antar Divisi Desain CAD dan GIS',
                        'desc'  => 'File gambar teknis sering terpisah dari basis data spasial historis sehingga evaluasi perubahan tata guna lahan antarperiode berjalan lambat.',
                    ],
                ],
            ],
            'solutions' => [
                [
                    'name'       => 'Topographic Survey',
                    'desc'       => 'Survei dan pemetaan topografi.',
                    'link_url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                    'link_label' => 'Detail Foto Udara & LiDAR',
                ],
                [
                    'name'       => 'Aerial Mapping',
                    'desc'       => 'Pemetaan udara menggunakan drone.',
                    'link_url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                    'link_label' => 'Pelajari Jasa Foto Udara',
                ],
                [
                    'name'       => 'Land Development Mapping',
                    'desc'       => 'Pemetaan lahan untuk pengembangan kawasan.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => '3D Terrain Modeling',
                    'desc'       => 'Pembuatan model permukaan tiga dimensi.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'GIS & Spatial Data',
                    'desc'       => 'Pengolahan dan integrasi data spasial.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Cut and Fill Analysis',
                    'desc'       => 'Analisis volume pekerjaan tanah.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Construction Monitoring',
                    'desc'       => 'Pemantauan perkembangan pekerjaan konstruksi.',
                    'link_url'   => home_url('/layanan/energi-infrastruktur/'),
                    'link_label' => 'Lihat Sektor Energi & Infrastruktur',
                ],
                [
                    'name'       => 'Urban & Regional Mapping',
                    'desc'       => 'Pemetaan untuk kebutuhan perencanaan kawasan.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
            ],
            'method_comparison' => [
                'eyebrow'  => 'Panduan Pemilihan Metode Perencanaan',
                'title'    => 'Drone Photogrammetry vs UAV LiDAR vs Terestris dalam Proyek Tata Ruang & Sipil',
                'subtitle' => 'Setiap skala perencanaan — dari pra-kelayakan hingga as-built — memiliki kombinasi metode paling efisien.',
                'columns'  => ['Kebutuhan Proyek', 'Drone Photogrammetry (RGB)', 'Drone LiDAR Survey', 'Survei Terestris (Total Station / GNSS)'],
                'rows'     => [
                    [
                        'Kawasan Lahan Terbuka / Pertanian Datar Bersih',
                        'Pilihan sangat efisien: menghasilkan ortofoto visual beresolusi tinggi, peta batas bangunan, DSM, serta kontur area terbuka.',
                        'Tetap akurat namun relatif lebih padat kebutuhan pengolahan bila tidak ada tutupan kanopi penghalang.',
                        'Efektif untuk pengukuran titik ikat batas bidang tanah hukum (kadastral) dan stake out titik bangunan.',
                    ],
                    [
                        'Calon Lahan Berbukit dengan Semak & Kebun Campuran',
                        'Hanya merekam puncak tajuk pohon sehingga perlu koreksi manual atau rintisan untuk area tertutup.',
                        'Metode utama untuk mendapatkan peta kontur tanah asli (Bare Earth DTM) dan jalur drainase alami di balik vegetasi.',
                        'Membutuhkan waktu survei darat sangat panjang bila bentangan mencapai puluhan hingga ratusan hektar.',
                    ],
                    [
                        'Pemetaan Kawasan Terbangun / Urban & As-Built',
                        'Menghasilkan foto tegak tajam (orthophoto), model 3D bangunan, dan inventarisasi aset permukaan jalan/atap.',
                        'Membantu ekstraksi profil permukaan jalan, pepohonan kota, dan saluran di bawah naungan rindang.',
                        'Wajib mendampingi untuk detail posisi patok legal, utilitas tertutup, serta verifikasi elevasi struktur presisi.',
                    ],
                ],
            ],
            'closing_paragraphs' => [
                'Full Drone Solutions mendampingi konsultan perencana, developer kawasan, kontraktor sipil, dan instansi pemerintah daerah dalam menyiapkan baseline spasial yang utuh dari fase studi kelayakan hingga dokumentasi as-built.',
                'Data diserahkan dalam format siap pakai agar terhubung mulus antara tim arsitek/engineering CAD dengan pengelola basis data GIS kawasan.',
            ],
            'deliverable_groups' => [
                [
                    'title' => 'Konsultasi Teknis dan Strategi Survei Perencanaan',
                    'items' => [
                        'Evaluasi kebutuhan survei topografi.',
                        'Penyusunan strategi pengumpulan data spasial.',
                        'Penentuan cakupan dan skala pemetaan.',
                        'Rekomendasi metode photogrammetry dan LiDAR.',
                        'Penyusunan kebutuhan data dasar untuk perencanaan.',
                        'Penyusunan rencana kerja survei dan pengolahan.',
                    ],
                ],
                [
                    'title' => 'Data Topografi, Kontur dan Estimasi Pekerjaan Tanah',
                    'items' => [
                        'Digital Elevation Model.',
                        'Contour map.',
                        'Peta batas dan kondisi lahan.',
                        'Pemetaan fitur topografi dan objek permukaan yang teridentifikasi.',
                        'Analisis kebutuhan pekerjaan tanah.',
                        'Cut and fill volume estimation.',
                    ],
                ],
                [
                    'title' => 'Pemetaan Kawasan, Tata Ruang dan Desain Rekayasa',
                    'items' => [
                        'Urban area mapping.',
                        'Drainage and waterway mapping.',
                        'Pemetaan kawasan terbangun.',
                        'Spatial change detection.',
                        'Pemetaan kawasan pengembangan.',
                        'GIS thematic maps.',
                        'Spatial planning database.',
                        'Construction site baseline.',
                        'Road and corridor mapping.',
                        'Surface model for engineering design support.',
                        'Construction planning base map.',
                        'As-built survey documentation sesuai lingkup pekerjaan.',
                    ],
                ],
                [
                    'title' => 'Pengembangan Database dan Visualisasi GIS',
                    'items' => [
                        'GIS database development.',
                        'Interactive GIS map.',
                        '2D and 3D spatial visualization.',
                        'Spatial analysis report.',
                        'Historical geospatial database.',
                    ],
                ],
            ],
            'workflow_steps' => [
                [
                    'step'  => '01',
                    'title' => 'Penetapan Skala Peta, Cakupan & Datum Referensi',
                    'desc'  => 'Evaluasi kebutuhan tahap proyek (pra-studi kelayakan, DED masterplan, pematangan lahan, atau as-built) serta standar skala pemetaan yang dibutuhkan.',
                ],
                [
                    'step'  => '02',
                    'title' => 'Rekomendasi Metode & Pemasangan Kontrol Tanah',
                    'desc'  => 'Penentuan apakah cukup dengan photogrammetry, perlu Drone LiDAR untuk lahan bervegetasi, atau kombinasi dengan kontrol GCP/ICP terestris.',
                ],
                [
                    'step'  => '03',
                    'title' => 'Akuisisi Udara & Pemrosesan Topografi',
                    'desc'  => 'Pengumpulan foto udara/data laser sesuai overlapping dan terrain following, dilanjutkan pembuatan ortofoto, DEM/DTM, serta klasifikasi objek permukaan.',
                ],
                [
                    'step'  => '04',
                    'title' => 'Ekstraksi Kontur, Cut & Fill, dan Basis Data GIS',
                    'desc'  => 'Penyusunan peta kontur CAD, perhitungan estimasi pekerjaan tanah, peta tematik tata ruang, hingga interactive GIS map dan historical geospatial database.',
                ],
            ],
            'software_integrations' => [
                'eyebrow'  => 'Kompatibilitas CAD, BIM & Sistem GIS',
                'title'    => 'Output Terintegrasi untuk Konsultan Perencana, Sipil & Pengelola Kawasan',
                'subtitle' => 'Setiap layer gambar dan model permukaan dikemas sesuai konvensi perangkat lunak desain teknis maupun platform tata ruang.',
                'formats'  => [
                    'High-Resolution Orthophoto & Digital Elevation Model (GeoTIFF / .ECW)',
                    'Peta Kontur, Drainase, Jalan & As-Built Survey (.DWG / .DXF siap buka di AutoCAD Civil 3D)',
                    'Layer Tematik Tata Ruang, Penggunaan Lahan & Batas Kawasan (.SHP / .GeoJSON)',
                    'Surface Model untuk Desain Rekayasa & Estimasi Cut and Fill Volume (.PDF + Tabular + Model 3D)',
                    'GIS Database Development & Interactive GIS Visualization',
                ],
                'tools'    => ['AutoCAD Civil 3D', 'QGIS', 'ArcGIS', 'InfraWorks / Lingkungan BIM', 'Global Mapper'],
            ],
            'cost_framework' => [
                'eyebrow'  => 'Perencanaan Anggaran Topografi & Tata Ruang',
                'title'    => 'Variabel Penentu Biaya Jasa Survei Topografi Drone & GIS',
                'intro'    => 'Estimasi biaya proyek perencanaan sangat dipengaruhi oleh skala peta, tutupan lahan, dan cakupan pengolahan peta tematik GIS.',
                'factors'  => [
                    [
                        'name' => 'Skala Pemetaan & Interval Kontur yang Diminta',
                        'desc' => 'Kebutuhan pra-desain makro berbeda kepadatan jalur terbang dan kerapatan titik kontrolnya dibanding kebutuhan DED atau as-built engineering.',
                    ],
                    [
                        'name' => 'Pilihan Metode (Fotogrametri RGB vs UAV LiDAR)',
                        'desc' => 'Lahan terbuka bersih jauh lebih ekonomis diselesaikan dengan fotogrametri, sedangkan lahan berkanopi lebat memerlukan paket LiDAR agar elevasi tanah tetap valid.',
                    ],
                    [
                        'name' => 'Kepadatan Area Terbangun & Perizinan Kawasan Perkotaan',
                        'desc' => 'Survei kawasan urban atau dekat fasilitas publik menuntut koordinasi perizinan ruang udara dan manajemen keselamatan penerbangan lebih ketat.',
                    ],
                    [
                        'name' => 'Lingkup Analisis Lanjutan & Pembuatan Interactive GIS',
                        'desc' => 'Penyusunan spatial analysis report, perhitungan volume earthwork, dan database GIS interaktif dihitung sesuai kedalaman permintaan TOR.',
                    ],
                ],
                'kak_checklist' => [
                    'Lokasi kawasan perencanaan (kota/kabupaten dan provinsi) beserta perkiraan luas kawasan.',
                    'Kondisi tutupan lahan dominan (tanah terbuka, perkebunan/semak, atau kawasan urban terbangun).',
                    'Target penggunaan peta (studi kelayakan, DED masterplan, analisis drainase/cut-and-fill, atau tata ruang).',
                    'Format file desain yang dibutuhkan (DWG/DXF, GeoTIFF, SHP, atau WebGIS) beserta referensi datum/koordinat.',
                ],
            ],
            'eeat_proof' => [
                'eyebrow'  => 'Kapabilitas Geospasial & Rekayasa Nasional',
                'title'    => 'Menggabungkan Keahlian Pemetaan UAV & Pengembangan Sistem GIS',
                'intro'    => 'Full Drone Solutions (PT Karya Solusi Angkasa) mendukung sektor perencanaan tata ruang dan konstruksi melalui kesatuan tim manufaktur UAV berstandar ISO 9001:2015 serta spesialis pengolahan data geospasial.',
                'bullets'  => [
                    'Jaringan kontrol lapangan tetap menjadi prasyarat pada proyek desain rekayasa agar model permukaan (DTM/DEM) selaras dengan acuan elevasi konsultan sipil maupun Jaring Kontrol Geodesi Nasional.',
                    'Pendekatan jujur dalam konsultasi awal: tim kami membantu mengevaluasi apakah lahan target cukup dengan foto udara fotogrametri atau memang wajib memakai LiDAR sebelum kontrak disepakati.',
                ],
                'portfolio_note' => 'Setiap proyek perencanaan didokumentasikan bersama peta batas area, metodologi kontrol tanah, laporan verifikasi mutu elevasi, serta arsip layer CAD/GIS siap integrasi.',
            ],
            'related_capabilities' => [
                [
                    'title' => 'Jasa Foto Udara dan LiDAR',
                    'desc'  => 'Pemilihan metode photogrammetry vs LiDAR sesuai kondisi lahan terbuka maupun bervegetasi untuk ketelitian desain teknik.',
                    'url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                ],
                [
                    'title' => 'Energi & Infrastruktur',
                    'desc'  => 'Layanan lanjutan monitoring konstruksi, pemetaan jalur koridor utilitas, dan dokumentasi aset terpadu.',
                    'url'   => home_url('/layanan/energi-infrastruktur/'),
                ],
                [
                    'title' => 'Bencana dan Lingkungan',
                    'desc'  => 'Pemetaan wilayah rawan, saluran drainase, dan daerah aliran sungai sebagai masukan tata ruang kawasan.',
                    'url'   => home_url('/layanan/bencana-lingkungan/'),
                ],
            ],
            'reading_links' => [
                ['label' => 'Detail Jasa Foto Udara Fotogrametri & Pemetaan LiDAR', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')],
                ['label' => 'Riset Drone Pemetaan untuk Manajemen DAS (FDS & UGM)', 'slug' => 'kolaborasi-fds-dan-ugm-riset-drone-pemetaan-untuk-manajemen-das'],
                ['label' => 'Spesifikasi Fixed-Wing VTOL DELTAV untuk Kawasan Luas', 'url' => home_url('/drone/deltav/')],
                ['label' => 'Panduan Faktor Biaya Survei LiDAR di Indonesia', 'slug' => 'biaya-survei-lidar-indonesia'],
            ],
            'faqs' => [
                [
                    'q' => 'Kapan proyek perencanaan wilayah sebaiknya menggunakan drone photogrammetry vs LiDAR?',
                    'a' => 'Pada tahap konsultasi awal, FDS melakukan evaluasi cakupan, skala pemetaan, dan kondisi tutupan lahan guna merekomendasikan metode photogrammetry (optimal di lahan terbuka bersih) atau LiDAR (wajib bila lahan masih berkanopi lebat) agar surface model memenuhi kebutuhan desain engineering.',
                ],
                [
                    'q' => 'Data topografi apa saja yang disiapkan untuk studi kelayakan dan konstruksi kawasan?',
                    'a' => 'Kami menghasilkan Digital Elevation Model (DEM/DTM), contour map, peta batas dan kondisi lahan, pemetaan drainase serta saluran air, hingga estimasi volume pekerjaan tanah (cut and fill analysis).',
                ],
                [
                    'q' => 'Apakah layanan Perencanaan mencakup pembuatan database dan peta interaktif GIS?',
                    'a' => 'Ya. Deliverables mencakup GIS thematic maps, spatial planning database, GIS database development, interactive GIS map, visualisasi spasial 2D dan 3D, serta spatial analysis report.',
                ],
                [
                    'q' => 'Dapatkah tim FDS melakukan as-built survey untuk dokumentasi pekerjaan lapangan?',
                    'a' => 'Ya, kami menyediakan as-built survey documentation sesuai lingkup pekerjaan serta historical geospatial database untuk mendokumentasikan perubahan spasial kawasan antarperiode.',
                ],
                [
                    'q' => 'Apakah hasil peta kontur bisa langsung dibuka di AutoCAD Civil 3D dan software GIS?',
                    'a' => 'Seluruh garis kontur, peta situasi, ortofoto, dan model elevasi diserahkan dalam format standar terbuka seperti .dwg/.dxf, GeoTIFF, serta .shp/.geojson agar langsung kompatibel dengan AutoCAD Civil 3D, QGIS, maupun ArcGIS.',
                ],
                [
                    'q' => 'Apa dokumen awal yang dibutuhkan agar FDS bisa menghitung estimasi survei topografi kawasan?',
                    'a' => 'Sampaikan informasi lokasi proyek (kota/kabupaten dan koordinat area), kondisi lahan saat ini, perkiraan luas hektar, serta skala/tujuan perencanaan (studi kelayakan, DED, tata ruang, atau konstruksi) melalui halaman Kontak atau WhatsApp resmi kami.',
                ],
            ],
            'quote_defaults' => [
                'eyebrow'      => 'Konsultasi Survei Topografi & Tata Ruang',
                'title'        => 'Ajukan Evaluasi Survei Kawasan, Kontur & Data GIS Proyek Anda',
                'subtitle'     => 'Sampaikan lokasi proyek, perkiraan luas kawasan, serta target format desain untuk mendapatkan rekomendasi metode terbaik dan penawaran resmi.',
                'wa_prefill'   => 'Halo tim FDS, kami ingin berkonsultasi mengenai jasa survei topografi drone, peta kontur, atau data spasial GIS.',
                'placeholder'  => 'Jelaskan jenis proyek (KEK/kawasan industri, pengembangan properti, RTRW/RDTR, atau as-built konstruksi), kondisi tutupan lahan, dan kebutuhan format CAD/GIS...',
            ],
            'seo' => [
                'meta_title'   => 'Jasa Survei Topografi Drone, Kontur & GIS Wilayah | FDS',
                'meta_desc'    => 'Layanan jasa survei topografi drone, foto udara, pemodelan 3D terrain, cut and fill, dan integrasi data GIS untuk perencanaan wilayah dari FDS.',
                'service_type' => 'Topographic Drone Survey, Urban Regional Mapping & Spatial GIS Planning',
            ],
            'placeholders' => [
                'Standar skala peta spesifik (mis. 1:500, 1:1.000, atau 1:5.000) dan contoh proyek tata ruang/KEK terverifikasi milik FDS jika diizinkan tampil publik.',
                'Contoh paket atau fitur spesifik modul Interactive GIS / WebGIS apabila tim memiliki demo platform publik.',
            ],
        ],

        // =====================================================================
        // SEKTOR 06: BENCANA DAN LINGKUNGAN
        // =====================================================================
        'bencana-lingkungan' => [
            'num'        => '06',
            'slug'       => 'bencana-lingkungan',
            'url'        => home_url('/layanan/bencana-lingkungan/'),
            'card_title' => 'Bencana dan Lingkungan',
            'card_desc'  => 'Solusi pemetaan cepat untuk penilaian dampak bencana, monitoring banjir dan longsor, pencarian lokasi, serta pemantauan perubahan lingkungan.',
            'card_tags'  => [
                ['label' => 'Flood Mapping', 'url' => ''],
                ['label' => 'Landslide Mapping', 'url' => ''],
                ['label' => 'Disaster Rapid Assessment', 'url' => ''],
                ['label' => 'Search & Rescue Support', 'url' => ''],
            ],
            'home_tag'   => 'Kebencanaan • SAR & Lingkungan',
            'image'      => $theme_uri . '/public/images/sectors/bencana-lingkungan.webp',
            'image_alt'  => 'Pemetaan udara cepat area terdampak banjir, longsor, dan kondisi lingkungan menggunakan drone UAV FDS',

            'h1'           => 'Jasa Pemetaan Drone Bencana, Banjir, Longsor & SAR',
            'subtitle'     => 'Disaster & Environmental Solutions — Rapid Aerial Assessment & Disaster Intelligence',
            'hero_summary' => 'Solusi UAV pemetaan cepat area banjir dan longsor, penilaian dampak bencana, dukungan visual/termal Search & Rescue, serta pemantauan perubahan lingkungan.',
            'intro_paragraphs' => [
                'Dalam situasi bencana, ketersediaan informasi kondisi lapangan menjadi faktor penting untuk mendukung respons dan penanganan. FDS menyediakan solusi UAV untuk pemetaan cepat, dokumentasi dampak bencana, dan pengumpulan data visual pada area terdampak.',
                'Teknologi drone memungkinkan pengambilan informasi dari udara pada area yang sulit dijangkau, sehingga dapat membantu tim tanggap darurat, pemerintah, dan lembaga terkait dalam memperoleh gambaran kondisi lapangan.',
            ],
            'challenge_section' => [
                'eyebrow'  => 'Konteks Tanggap Darurat & Mitigasi Risiko',
                'title'    => 'Mempercepat Gambaran Lapangan saat Akses Darat Terputus & Cuaca Mendung',
                'paragraphs' => [
                    'Saat terjadi banjir bandang, tanah longsor, maupun perubahan garis sungai di wilayah rawan Indonesia, akses jalan kerap tertutup material atau genangan air tinggi, sementara citra satelit optik sering terhalang tutupan awan tebal tepat pada jam-jam kritis penanganan.',
                    'Selain fase tanggap darurat dan dukungan pencarian korban, pemangku kepentingan kebencanaan juga menghadapi tantangan lanjutan di tahap rehabilitasi dan mitigasi: menghitung estimasi volume material longsor, mengevaluasi kapasitas saluran pengendali banjir, serta memetakan area permukiman yang paling membutuhkan penataan ulang.',
                ],
                'pain_points' => [
                    [
                        'title' => 'Keterlambatan Informasi Akses Jalan & Infrastruktur Kritis',
                        'desc'  => 'Tim logistik dan evakuasi membutuhkan konfirmasi cepat mengenai jembatan, tanggul, atau ruas jalan yang masih bisa dilalui dibanding jalur yang terputus.',
                    ],
                    [
                        'title' => 'Bahaya Longsor Susulan bagi Personel Pemetaan Darat',
                        'desc'  => 'Memasuki mahkota atau kaki lereng longsor secara langsung untuk mengukur perubahan geometri sangat berisiko saat tanah masih labil.',
                    ],
                    [
                        'title' => 'Kebutuhan Baseline Pemulihan Pascabencana & Daerah Aliran Sungai',
                        'desc'  => 'Tanpa dokumentasi udara berkala, evaluasi efektivitas tanggul, normalisasi saluran air, dan rehabilitasi ekosistem pascabencana sulit dipantau secara terukur.',
                    ],
                ],
            ],
            'solutions' => [
                [
                    'name'       => 'Flood Mapping',
                    'desc'       => 'Pemetaan area terdampak banjir dan dokumentasi perubahan kondisi wilayah.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Landslide Mapping',
                    'desc'       => 'Pemetaan area longsor dan dokumentasi perubahan topografi.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Disaster Rapid Assessment',
                    'desc'       => 'Pengumpulan data udara untuk penilaian awal dampak bencana.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Post-Disaster Mapping',
                    'desc'       => 'Pemetaan kondisi wilayah setelah kejadian bencana.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
                [
                    'name'       => 'Search & Rescue Support',
                    'desc'       => 'Dukungan pencarian melalui kamera zoom, thermal, dan sistem pencarian khusus.',
                    'link_url'   => home_url('/drone/multipurpose/'),
                    'link_label' => 'Lihat UAV MULTIPURPOSE SAR',
                ],
                [
                    'name'       => 'Environmental Change Monitoring',
                    'desc'       => 'Pemantauan perubahan tutupan lahan dan kondisi lingkungan.',
                    'link_url'   => home_url('/layanan/kehutanan-lingkungan/'),
                    'link_label' => 'Lihat Sektor Kehutanan & Lingkungan',
                ],
                [
                    'name'       => 'Emergency Aerial Documentation',
                    'desc'       => 'Dokumentasi udara untuk mendukung koordinasi penanganan.',
                    'link_url'   => '',
                    'link_label' => '',
                ],
            ],
            'method_comparison' => [
                'eyebrow'  => 'Matriks Dukungan UAV vs Metode Lain dalam Kebencanaan',
                'title'    => 'Peran Rapid Mapping Drone, Sensor Termal & Pemodelan LiDAR di Siklus Bencana',
                'subtitle' => 'Setiap fase — pra-bencana, tanggap darurat, dan pemulihan — membutuhkan kapabilitas sensor dan wahana yang tepat guna.',
                'columns'  => ['Fase Penanganan', 'Pemetaan Cepat & Visual Zoom UAV', 'Sensor Termal & Search Support', 'UAV LiDAR & Hidrologi (Pra/Pasca)'],
                'rows'     => [
                    [
                        'Pra-Bencana & Kajian Wilayah Rawan',
                        'Pemetaan baseline kawasan permukiman rentan, kondisi jalan evakuasi, dan dokumentasi visual saluran.',
                        'Pemantauan area panas atau titik rawan kebakaran lahan di perimeter terbatas.',
                        'Pemodelan topografi aliran air, profil sungai, tanggul, serta lereng curam bervegetasi.',
                    ],
                    [
                        'Tanggap Darurat (Banjir, Longsor, SAR)',
                        'Terbang di bawah awan untuk memetakan sebaran genangan, jembatan putus, dan akses logistik terdampak.',
                        'Membantu pencarian area terbuka atau kondisi minim cahaya menggunakan kamera zoom dan termal.',
                        'Fokus operasional bergeser ke misi cepat visual/termal karena prioritas kecepatan koordinasi lapangan.',
                    ],
                    [
                        'Pascabencana, Estimasi Volume & Rehabilitasi',
                        'Menghasilkan ortofoto pascakejadian untuk inventarisasi visual kerusakan bangunan serta infrastruktur.',
                        'Dokumentasi lanjutan apabila masih ada titik panas atau pemantauan area pemulihan khusus.',
                        'Menghitung estimasi volume material longsor, perubahan alur sungai, dan rencana rekonstruksi drainase.',
                    ],
                ],
            ],
            'closing_paragraphs' => [
                'Full Drone Solutions menyediakan dukungan pemetaan udara maupun penguatan kapasitas armada UAV bagi instansi kebencanaan, pemerintah daerah, pengelola kawasan strategis, dan tim tanggap darurat perusahaan.',
                'Dengan kombinasi platform penginderaan jarak jauh dan pengolahan data spasial terstruktur, informasi visual maupun model permukaan dapat segera diterjemahkan menjadi peta koordinasi penanganan hingga monitoring rehabilitasi wilayah.',
            ],
            'deliverable_groups' => [
                [
                    'title' => 'Assessment Awal, Pemetaan Rawan dan Penilaian Dampak Bencana',
                    'items' => [
                        'Disaster mapping requirement assessment.',
                        'Pemetaan wilayah rawan.',
                        'Pemetaan akses dan infrastruktur kritis.',
                        'Baseline geospatial database.',
                        'Peta area terdampak.',
                        'Peta kerusakan bangunan dan infrastruktur yang teridentifikasi secara visual.',
                        'Pemetaan akses jalan yang terdampak.',
                        'Dokumentasi visual wilayah terdampak.',
                    ],
                ],
                [
                    'title' => 'Flood & Hydrological Mapping',
                    'items' => [
                        'Flood & Hydrological Mapping sebagai kerangka pemetaan wilayah perairan terdampak.',
                        'Peta sebaran genangan.',
                        'Pemetaan sungai dan saluran.',
                        'Pemetaan tanggul dan infrastruktur pengendali air.',
                        'Pemetaan area yang membutuhkan inspeksi drainase.',
                        'Dokumentasi perubahan kondisi sungai dan daerah aliran.',
                    ],
                ],
                [
                    'title' => 'Pemetaan Longsor dan Perubahan Geometri Lahan',
                    'items' => [
                        'Landslide area mapping.',
                        'Estimasi volume material longsor berdasarkan model permukaan.',
                        'Monitoring perubahan geometri area terdampak.',
                    ],
                ],
                [
                    'title' => 'Dukungan Search & Rescue (SAR) Berbasis Udara',
                    'items' => [
                        'Aerial visual Search & Rescue.',
                        'Thermal-assisted Search & Rescue.',
                        'Area Search & Rescue mapping.',
                    ],
                ],
                [
                    'title' => 'Pemantauan Dampak Ekosistem dan Rehabilitasi Wilayah',
                    'items' => [
                        'Dokumentasi dampak bencana pada ekosistem.',
                        'Monitoring rehabilitasi wilayah terdampak.',
                    ],
                ],
            ],
            'workflow_steps' => [
                [
                    'step'  => '01',
                    'title' => 'Disaster Requirement Assessment & Koordinasi Lapangan',
                    'desc'  => 'Penentuan zona prioritas terdampak, jalur akses aman peluncuran wahana, kondisi cuaca setempat, serta koordinasi ruang udara bersama pihak berwenang di lokasi.',
                ],
                [
                    'step'  => '02',
                    'title' => 'Akuisisi Udara Cepat (Visual, Termal, atau Pemetaan Permukaan)',
                    'desc'  => 'Pengumpulan data jalur genangan banjir, lereng longsor, koridor jalan putus, maupun pemindaian sektor Search & Rescue sesuai kebutuhan tim darat.',
                ],
                [
                    'step'  => '03',
                    'title' => 'Pemrosesan Ortofoto Cepat, Analisis Genangan & Volume Longsor',
                    'desc'  => 'Penyusunan peta area terdampak, penandaan visual kerusakan bangunan dan jembatan, serta perbandingan model permukaan bila tersedia data baseline pra-kejadian.',
                ],
                [
                    'step'  => '04',
                    'title' => 'Penyerahan Peta Koordinasi & Pemantauan Rehabilitasi',
                    'desc'  => 'Distribusi peta sebaran dan file GIS ke pos komando atau tim perencana pemulihan, dilanjutkan pemantauan berkala selama masa rehabilitasi wilayah.',
                ],
            ],
            'software_integrations' => [
                'eyebrow'  => 'Format Data Tanggap Darurat & Rehabilitasi',
                'title'    => 'Peta Spasial Siap Distribusi untuk Pos Komando & Tim Pemulihan',
                'subtitle' => 'File keluaran dibuat agar mudah dibuka pada sistem GIS instansi maupun perangkat pemetaan lapangan.',
                'formats'  => [
                    'Peta Area Terdampak, Sebaran Genangan & Akses Jalan Terputus (GeoTIFF / .PDF Siap Cetak)',
                    'Layer Batas Genangan, Alur Sungai, Tanggul & Titik Infrastruktur Kritis (.SHP / .GeoJSON)',
                    'Model Permukaan 3D & Estimasi Volume Material Longsor (.TIF / .DWG sesuai cakupan)',
                    'Dokumentasi Visual & Termal Area Search & Rescue Terkoordinat',
                    'Baseline Geospatial Database untuk Pemantauan Rehabilitasi Wilayah & Ekosistem',
                ],
                'tools'    => ['QGIS', 'ArcGIS', 'FDS Ground Control Station', 'Global Mapper', 'Perangkat Koordinasi Lapangan GIS'],
            ],
            'cost_framework' => [
                'eyebrow'  => 'Skema Kerja Sama Kesiapsiagaan & Penanganan',
                'title'    => 'Faktor Penentu Kebutuhan Sumber Daya Pemetaan Kebencanaan',
                'intro'    => 'Kebutuhan layanan kebencanaan terbagi antara proyek pemetaan mitigasi/baseline kawasan rawan yang terencana dan dukungan operasi tanggap darurat atau pengadaan unit UAV siaga bagi instansi.',
                'factors'  => [
                    [
                        'name' => 'Tujuan Proyek (Mitigasi Baseline vs Pascabencana vs Armada Siaga)',
                        'desc' => 'Pemetaan kajian risiko kawasan DAS berbeda cakupan dan jadwalnya dibanding pengiriman tim pemetaan cepat pascakejadian.',
                    ],
                    [
                        'name' => 'Luas Wilayah Terdampak & Akses Transportasi Darurat',
                        'desc' => 'Keterputusan jalur darat, ketersediaan pasokan listrik/pengisian baterai di pos lapangan, dan jarak lokasi memengaruhi desain mobilisasi.',
                    ],
                    [
                        'name' => 'Jenis Payload (Pemetaan Ortofoto vs Termal SAR vs LiDAR Hidrologi)',
                        'desc' => 'Pemilihan wahana dan sensor disesuaikan pada apakah prioritasnya adalah pencitraan cepat, dukungan SAR termal, atau pemodelan elevasi longsor/tanggul.',
                    ],
                    [
                        'name' => 'Kebutuhan Pelatihan Tim Internal Instansi',
                        'desc' => 'Banyak instansi daerah dan korporasi memilih memiliki armada sendiri seperti MULTIPURPOSE disertai pelatihan operator agar siap siaga sewaktu-waktu.',
                    ],
                ],
                'kak_checklist' => [
                    'Koordinat atau nama wilayah kajian/terdampak (kecamatan, kabupaten/kota, dan provinsi).',
                    'Jenis kejadian atau fokus kajian (pemetaan banjir/DAS, kajian longsor, dukungan SAR, atau baseline mitigasi).',
                    'Kebutuhan layanan: penugasan tim pemetaan FDS vs pengadaan unit UAV siaga + pelatihan tim daerah.',
                    'Pihak penanggung jawab koordinasi izin area atau pos komando di lapangan.',
                ],
            ],
            'eeat_proof' => [
                'eyebrow'  => 'Kesiapan Teknologi Dalam Negeri',
                'title'    => 'Wahana MULTIPURPOSE, Pengalaman Riset DAS & Kepatuhan e-Katalog',
                'intro'    => 'Sebagai produsen UAV nasional di Sleman, Yogyakarta, PT Karya Solusi Angkasa (FDS) merancang lini platform termasuk UAV MULTIPURPOSE serta Fixed-Wing VTOL DELTAV untuk skenario pemetaan udara luas maupun pengamatan visual dan termal di medan sulit.',
                'bullets'  => [
                    'Kolaborasi riset manajemen Daerah Aliran Sungai (DAS) memperkuat pemahaman tim kami terhadap pemetaan hidrologi, tanggul, dan dinamika alur sungai.',
                    'Sistem manajemen mutu ISO 9001:2015 mendukung kesiapan mekanisme pengadaan resmi instansi pemerintah pusat/daerah maupun BUMN.',
                    'Transparansi teknis: dukungan Search & Rescue berbasis drone berfungsi sebagai alat bantu pengamatan udara bagi tim SAR berwenang di lapangan.',
                ],
                'portfolio_note' => 'Dokumentasi pemetaan kebencanaan dan lingkungan mencatat batas wilayah terdampak, waktu akuisisi lapangan, titik infrastruktur akses kritis, serta pembaruan kondisi selama program rehabilitasi berjalan.',
            ],
            'related_capabilities' => [
                [
                    'title' => 'Jasa Foto Udara dan LiDAR',
                    'desc'  => 'Pemodelan permukaan topografi dan daerah aliran sungai guna memetakan potensi genangan maupun area rawan.',
                    'url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
                ],
                [
                    'title' => 'Kehutanan & Lingkungan',
                    'desc'  => 'Monitoring perubahan tutupan lahan jangka panjang, restorasi ekosistem, dan pemetaan vegetasi.',
                    'url'   => home_url('/layanan/kehutanan-lingkungan/'),
                ],
                [
                    'title' => 'Perencanaan Wilayah',
                    'desc'  => 'Pemetaan drainase, saluran, dan rencana penataan ulang infrastruktur kawasan pascabencana.',
                    'url'   => home_url('/layanan/perencanaan/'),
                ],
            ],
            'reading_links' => [
                ['label' => 'Platform UAV MULTIPURPOSE (Optik Zoom & Termal untuk SAR)', 'url' => home_url('/drone/multipurpose/')],
                ['label' => 'Kolaborasi Riset FDS & UGM: Drone Pemetaan Manajemen DAS', 'slug' => 'kolaborasi-fds-dan-ugm-riset-drone-pemetaan-untuk-manajemen-das'],
                ['label' => 'Platform Drone Logistik DELFRO untuk Distribusi Wilayah Terisolir', 'url' => home_url('/kategori-drone/kargo/')],
                ['label' => 'Layanan Pelatihan & Sertifikasi Pilot Drone Korporasi/Instansi', 'url' => home_url('/layanan/pelatihan-sertifikasi/')],
            ],
            'faqs' => [
                [
                    'q' => 'Bagaimana teknologi drone membantu pada tahap tanggap darurat dan pascabencana?',
                    'a' => 'UAV membantu pengumpulan data udara secara cepat pada lokasi yang sulit dijangkau untuk menghasilkan peta area terdampak, pemetaan akses jalan dan infrastruktur kritis, serta dokumentasi kerusakan bangunan yang teridentifikasi secara visual.',
                ],
                [
                    'q' => 'Apa cakupan pemetaan untuk bencana banjir dan hidrologi (Flood & Hydrological Mapping)?',
                    'a' => 'Cakupan meliputi peta sebaran genangan, pemetaan sungai dan saluran, tanggul dan infrastruktur pengendali air, area yang membutuhkan inspeksi drainase, serta dokumentasi perubahan daerah aliran.',
                ],
                [
                    'q' => 'Bagaimana dukungan UAV untuk penanganan tanah longsor?',
                    'a' => 'FDS menyediakan landslide area mapping, estimasi volume material longsor berdasarkan model permukaan, serta monitoring perubahan geometri area terdampak antarwaktu tanpa menempatkan petugas ukur di atas lereng labil.',
                ],
                [
                    'q' => 'Apakah tersedia dukungan Search & Rescue dengan sensor kamera termal?',
                    'a' => 'Ya. Dukungan pencarian mencakup aerial visual Search & Rescue menggunakan kamera zoom, thermal-assisted Search & Rescue, serta area Search & Rescue mapping untuk membantu koordinasi tim lapangan.',
                ],
                [
                    'q' => 'Bisakah pemerintah daerah atau instansi kebencanaan membeli armada UAV siaga beserta pelatihan tim?',
                    'a' => 'Bisa. FDS (perusahaan bersertifikat sistem manajemen mutu ISO 9001:2015) menyediakan pengadaan unit UAV buatan dalam negeri seperti seri MULTIPURPOSE, DELTAV, dan DELFRO disertai program pelatihan dan bootcamp bagi tim operasional instansi.',
                ],
                [
                    'q' => 'Bagaimana cara mengajukan permintaan pemetaan baseline mitigasi maupun pemulihan pascabencana?',
                    'a' => 'Anda dapat mengirimkan informasi lokasi wilayah (koordinat/kabupaten/provinsi) atau deskripsi kebutuhan kajian hidrologi, longsor, dan lingkungan melalui halaman Kontak atau WhatsApp resmi PT Karya Solusi Angkasa.',
                ],
            ],
            'quote_defaults' => [
                'eyebrow'      => 'Konsultasi Kebencanaan & Lingkungan',
                'title'        => 'Ajukan Pemetaan Mitigasi, Rapid Assessment, atau Pengadaan UAV Siaga',
                'subtitle'     => 'Sampaikan lokasi wilayah kajian/terdampak, kebutuhan pemetaan hidrologi/longsor/SAR, atau rencana pengadaan unit untuk instansi Anda.',
                'wa_prefill'   => 'Halo tim FDS, kami ingin berkonsultasi mengenai pemetaan kebencanaan / lingkungan / solusi UAV SAR.',
                'placeholder'  => 'Jelaskan fokus kebutuhan (pemetaan banjir/DAS, kajian longsor, dukungan visual/termal SAR, monitoring pascabencana, atau pengadaan unit siaga + pelatihan)...',
            ],
            'seo' => [
                'meta_title'   => 'Jasa Pemetaan Drone Bencana, Banjir, Longsor & SAR | FDS',
                'meta_desc'    => 'Pemetaan cepat banjir, longsor, penilaian dampak bencana, dukungan search and rescue visual termal, dan monitoring pemulihan lingkungan oleh FDS.',
                'service_type' => 'Disaster Rapid Assessment, Flood & Landslide Drone Mapping, and SAR Support',
            ],
            'placeholders' => [
                'Konfirmasi tag/keyword resmi pilihan tim internal untuk Kartu 06 di Hub Layanan apabila berbeda dari 4 tag saat ini.',
                'Protokol respon cepat khusus atau nomor hotline siaga tanggap darurat apabila manajemen menyediakan jalur komunikasi terpisah.',
                'Studi kasus pemetaan banjir, longsor, atau kajian DAS/lingkungan yang diizinkan instansi mitra untuk dipublikasikan.',
            ],
        ],
    ];
}

/**
 * Mengambil seluruh data 6 sektor layanan setelah menggabungkan override WP Admin
 * secara non-destruktif (option: fds_layanan_sector_overrides_{slug}).
 */
function fds_get_layanan_sectors(bool $fresh = false): array {
    static $memo = null;
    if (!$fresh && $memo !== null) {
        return $memo;
    }

    $defaults = fds_get_default_layanan_sectors();
    $merged   = [];

    foreach ($defaults as $slug => $sec_default) {
        $override = get_option('fds_layanan_sector_overrides_' . $slug, null);
        if (is_array($override) && !empty($override) && function_exists(__NAMESPACE__ . '\\fds_merge_layanan_data')) {
            $merged_sec = fds_merge_layanan_data($sec_default, $override);
        } else {
            $merged_sec = $sec_default;
        }

        if (!empty($merged_sec['solutions']) && is_array($merged_sec['solutions'])) {
            foreach ($merged_sec['solutions'] as &$sol_item) {
                if (is_array($sol_item) && trim((string) ($sol_item['link_url'] ?? '')) === '#form-penawaran') {
                    $sol_item['link_url'] = home_url('/kontak/#hubungi-form');
                }
            }
            unset($sol_item);
        }

        $merged[$slug] = $merged_sec;
    }

    if (function_exists('fds_deep_decode')) {
        $merged = \fds_deep_decode($merged);
    }

    $memo = $merged;
    return $memo;
}


