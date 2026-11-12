<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS LAYANAN ARCHITECTURE & SINGLE SOURCE OF TRUTH DATA REGISTRY
 * =========================================================================
 * Sumber data terpusat untuk:
 * 1. Hub Layanan (/layanan/) -> target keyword: jasa drone mapping indonesia
 * 2. 6 Halaman Sektor Industri (/layanan/{sektor}/) via app/layanan-data-sectors.php
 * 3. 3 Halaman Kategori Layanan (/layanan/{kategori}/) via app/layanan-data-categories.php
 * 4. Sinkronisasi Kartu Section "Solusi Kami" & "Layanan Enterprise" di Beranda
 * 5. Dropdown Navbar, Tautan Footer & Provisioning Halaman /terima-kasih/
 *
 * Seluruh teks sektor dan hub bersumber dari spesifikasi Halaman_Layanan.md.
 * Data operasional yang belum tersedia dicatat terstruktur pada array 'placeholders'
 * dan TIDAK pernah dicetak sebagai literal [ISI: ...] pada HTML publik.
 */

require_once __DIR__ . '/layanan-data-sectors.php';
require_once __DIR__ . '/layanan-data-categories.php';

/**
 * Sanitasi URL / path relatif / fragment (#anchor) untuk form Konten Layanan.
 */
function fds_sanitize_service_url(string $raw): string {
    $trimmed = trim(wp_unslash($raw));
    if ($trimmed === '') {
        return '';
    }
    if (str_starts_with($trimmed, '#') || str_starts_with($trimmed, '/')) {
        return sanitize_text_field($trimmed);
    }
    return esc_url_raw($trimmed, ['http', 'https', 'mailto', 'tel']);
}

/**
 * Menggabungkan array default dengan array override secara aman:
 * - List/repeater keys yang diedit di WP Admin menggantikan list default sepenuhnya
 *   agar operasi tambah, ubah urutan, atau hapus item berfungsi sempurna.
 * - Associative section containers digabung per key.
 */
function fds_merge_layanan_data(array $default, array $override): array {
    static $list_keys = [
        'intro_paragraphs',
        'closing_paragraphs',
        'paragraphs',
        'pain_points',
        'solutions',
        'products_tech',
        'sublinks',
        'deliverable_groups',
        'items',
        'workflow_steps',
        'steps',
        'columns',
        'rows',
        'formats',
        'tools',
        'factors',
        'kak_checklist',
        'bullets',
        'related_capabilities',
        'reading_links',
        'faqs',
        'card_tags',
        'capabilities',
        'outputs',
        'sector_links',
        'placeholders',
    ];

    $result = $default;
    foreach ($override as $key => $val) {
        if (in_array((string) $key, $list_keys, true)) {
            $result[$key] = is_array($val) ? $val : [];
            continue;
        }
        if (isset($result[$key]) && is_array($result[$key]) && is_array($val)) {
            $result[$key] = fds_merge_layanan_data($result[$key], $val);
            continue;
        }
        $result[$key] = $val;
    }

    return $result;
}

/**
 * Menghapus seluruh cache memori statis, transient menu/beranda/llms.txt,
 * dan memicu purge cache halaman setelah penyimpanan admin.
 */
function fds_flush_layanan_cache(): void {
    static $is_flushing = false;
    if ($is_flushing) {
        return;
    }
    $is_flushing = true;

    delete_transient('fds_homepage_content_cache');
    delete_transient('fds_homepage_content_cache_v2');
    delete_transient('fds_homepage_content_cache_v3');
    delete_transient('fds_newsroom_ui_cache_v1');
    delete_transient('fds_about_content_cache');
    delete_transient('fds_about_content_cache_v2');
    delete_transient('fds_about_content_cache_v3');
    delete_transient('fds_global_contact_cache_v4');
    delete_transient('fds_navbar_brand_cache_v2');
    delete_transient('fds_navbar_brand_cache_v3');
    delete_transient('fds_navbar_brand_cache_v4');
    delete_transient('fds_mega_menu_data_v2');
    delete_transient('fds_mega_menu_data_v3');
    delete_transient('fds_bandingkan_catalog_cache_v2');
    delete_transient('fds_bandingkan_catalog_cache_v3');
    delete_transient('fds_bandingkan_catalog_cache_v4');
    delete_transient('fds_llms_txt_cache_v3');
    delete_transient('fds_llms_txt_cache_v4');
    delete_transient('fds_llms_txt_cache_v5');
    delete_transient('fds_llms_full_txt_cache_v5');

    if (function_exists(__NAMESPACE__ . '\\fds_get_layanan_sectors')) {
        fds_get_layanan_sectors(true);
    }
    if (function_exists(__NAMESPACE__ . '\\fds_get_layanan_categories')) {
        fds_get_layanan_categories(true);
    }
    if (function_exists(__NAMESPACE__ . '\\fds_get_layanan_hub_data')) {
        fds_get_layanan_hub_data(true);
    }
    if (function_exists(__NAMESPACE__ . '\\fds_get_layanan_ui_texts')) {
        fds_get_layanan_ui_texts(true);
    }
    if (function_exists(__NAMESPACE__ . '\\fds_get_homepage_content')) {
        fds_get_homepage_content(true);
    }
    if (function_exists(__NAMESPACE__ . '\\fds_get_navbar_brand')) {
        fds_get_navbar_brand(true);
    }
    if (function_exists(__NAMESPACE__ . '\\fds_purge_ai_and_seo_caches')) {
        fds_purge_ai_and_seo_caches(true);
    } elseif (function_exists('has_action') && has_action('litespeed_purge_all')) {
        do_action('litespeed_purge_all');
    }

    $is_flushing = false;
}

/**
 * Data bawaan halaman Hub /layanan/
 */
function fds_get_default_layanan_hub_data(): array {
    return [
        'slug'        => 'layanan',
        'url'         => home_url('/layanan/'),
        'eyebrow'     => 'Solusi Layanan UAV & Geospasial FDS',
        'h1'          => 'UAV Technology. Intelligent Data. Real Industrial Solutions.',
        'subtitle'    => 'Jasa drone mapping Indonesia dan solusi UAV terintegrasi untuk pengumpulan data geospasial, survei LiDAR, pencitraan spektral, dan inspeksi aset industri.',
        'hero_summary' => 'Solusi layanan UAV terintegrasi untuk pengumpulan data geospasial, pemetaan topografi, survei LiDAR, analisis multispectral, inspeksi aset, hingga pelatihan tim operasional industri di seluruh Indonesia.',
        'intro_paragraphs' => [
            'Full Drone Solutions (PT Karya Solusi Angkasa) menghadirkan solusi teknologi Unmanned Aerial Vehicle (UAV) yang mengintegrasikan platform drone, sensor pemetaan, sistem inspeksi, dan pengolahan data berbasis AI.',
            'Kami membantu perusahaan tambang, perkebunan, kehutanan, energi, kontraktor konstruksi, serta instansi pemerintah memperoleh data udara yang lebih cepat, akurat, dan terukur untuk mendukung operasional, pemantauan aset, pengambilan keputusan, serta pengembangan bisnis.',
            'Mulai dari jasa drone mapping fotogrametri, drone LiDAR survey, multispectral dan hyperspectral imaging, aerial inspection visual/termal, pengembangan UAV khusus, hingga pelatihan dan bootcamp operator, setiap pekerjaan disesuaikan langsung dengan karakteristik proyek dan standar teknis organisasi Anda.',
        ],
        'cta_primary_text'   => 'Minta Penawaran Proyek',
        'cta_primary_url'    => home_url('/kontak/#hubungi-form'),
        'cta_secondary_text' => 'Jelajahi 6 Sektor Industri',
        'cta_secondary_url'  => '#solusi-industri',
        'challenge_section' => [
            'eyebrow'  => 'Mengapa Organisasi Industri Beralih ke Akuisisi UAV Terpadu?',
            'title'    => 'Menjawab Kebutuhan Data Geospasial Luas Tanpa Membebani Operasi Lapangan',
            'paragraphs' => [
                'Dalam pengelolaan konsesi pertambangan, perkebunan kelapa sawit ribuan hektar, koridor jaringan energi sepanjang ratusan kilometer, hingga perencanaan Kawasan Ekonomi Khusus (KEK), tantangan utamanya jarang terletak pada kurangnya data laporan—melainkan kesenjangan antara kondisi riil di lapangan yang berubah cepat dan pembaruan peta dasar yang terlambat.',
                'Metode pengukuran manual dari darat tetap penting untuk pemasangan patok kontrol geodesi dan Bench Mark fisik, tetapi memakan waktu berminggu-minggu bila harus memindai seluruh cakupan wilayah secara berkala. Layanan UAV terpadu dari Full Drone Solutions menutup celah ini dengan akuisisi udara beresolusi tinggi, kontrol titik tanah terstandar, dan pengolahan data yang langsung selaras dengan alur kerja tim engineering, agronomi, maupun GIS perusahaan.',
            ],
            'pain_points' => [
                [
                    'title' => 'Fragmentasi Vendor Wahana, Akuisisi & Pengolahan GIS',
                    'desc'  => 'Banyak proyek tersendat karena penyedia jasa terbang berbeda dengan tim pengolah data dan produsen perangkat keras, sehingga tanggung jawab mutu tidak terpusat.',
                ],
                [
                    'title' => 'Ketidakcocokan Sensor terhadap Tutupan Lahan',
                    'desc'  => 'Mengandalkan kamera RGB biasa pada kawasan berkanopi lebat atau memakai survei manual di lereng curam mengakibatkan revisi desain mahal dan paparan risiko K3.',
                ],
                [
                    'title' => 'Kebutuhan Skalabilitas dari Jasa Proyek ke Armada Mandiri',
                    'desc'  => 'Perusahaan yang beralih dari jasa survei berkala ke kepemilikan armada internal sering kesulitan mencari mitra manufaktur bersertifikat sistem manajemen mutu ISO 9001:2015 (serta TKDN untuk lini pertanian FERTO) sekaligus pelatihannya.',
                ],
            ],
        ],
        'why_fds' => [
            'title' => 'Mengapa Memilih Layanan UAV & Pemetaan FDS',
            'desc'  => 'Pendekatan terpadu dari rekayasa platform wahana dan pemilihan sensor hingga integrasi data spasial ke alur kerja operasional perusahaan Anda.',
            'items' => [
                [
                    'title' => 'Integrasi Platform, Sensor & Analitik AI',
                    'desc'  => 'Menghubungkan wahana UAV produksi sendiri (seperti Fixed-Wing VTOL DELTAV, MULTIPURPOSE, DELFRO, dan seri FERTO), sensor pemetaan atau inspeksi, serta pengolahan data spasial dalam satu koordinasi teknik.',
                ],
                [
                    'title' => 'Data Cepat, Terkalibrasi & Siap Audit',
                    'desc'  => 'Membantu tim teknis, surveyor, agronomi, lingkungan, dan manajemen memperoleh gambaran kondisi aktual lapangan berbasis kontrol GCP/ICP sebagai dasar evaluasi dan pelaporan resmi.',
                ],
                [
                    'title' => 'Kustomisasi Metode Sesuai Sektor Industri',
                    'desc'  => 'Merekomendasikan teknologi paling efisien—baik foto udara fotogrametri, survei LiDAR penembus kanopi, pencitraan multispectral/hyperspectral, maupun inspeksi visual/termal sesuai karakter site.',
                ],
                [
                    'title' => 'Produsen UAV Nasional ISO 9001:2015 & TKDN (Seri FERTO)',
                    'desc'  => 'Didukung fasilitas riset, perakitan, dan persediaan suku cadang di Sleman, Yogyakarta dengan standar ISO 9001:2015, TKDN 60,74% untuk lini FERTO, SNI 9199:2023 pada FERTO 15 & FERTO 22, serta kesiapan pengadaan e-Katalog LKPP.',
                ],
            ],
        ],
        'method_comparison' => [
            'eyebrow'  => 'Matriks Teknologi Sensor UAV',
            'title'    => 'Memilih Metode Pemetaan & Inspeksi Udara yang Tepat untuk Proyek Anda',
            'subtitle' => 'Setiap tantangan industri memerlukan kombinasi wahana dan sensor yang berbeda. Ringkasan ini membantu tim Anda menyusun kerangka acuan kerja sebelum meminta proposal.',
            'columns'  => ['Aspek Evaluasi', 'Foto Udara Fotogrametri (RGB)', 'UAV Drone LiDAR (3D Laser)', 'Multispectral & Hyperspectral', 'Inspeksi Visual Zoom & Termal'],
            'rows'     => [
                [
                    'Tujuan Pengumpulan Data',
                    'Menghasilkan peta situasi ortofoto resolusi tinggi dan pemodelan 3D area terbuka bersih.',
                    'Merekam jutaan titik koordinat 3D untuk menembus celah kanopi pohon dan memetakan permukaan tanah asli (DTM).',
                    'Mengukur reflektansi spektral tajuk daun untuk menilai kesehatan tanaman dan variasi stres vegetasi.',
                    'Mendokumentasikan kondisi visual detail aset tinggi/bertegangan dan mendeteksi anomali suhu permukaan (hotspot).',
                ],
                [
                    'Kondisi Lahan Ideal',
                    'Lahan bukaan tambang bersih, area konstruksi sipil, kawasan industri, dan perkebunan muda.',
                    'Hutan alam, perkebunan kelapa sawit rapat, koridor SUTET/pipa bervegetasi, dan lereng tebing terjal.',
                    'Blok perkebunan kelapa sawit/tebu/pangan, lahan reklamasi, hutan konservasi, dan ekosistem mangrove.',
                    'Menara transmisi listrik, panel PLTS solar farm, struktur jembatan, flare industri, serta misi bantuan SAR.',
                ],
                [
                    'Output Teknis Utama',
                    'Ortofoto GeoTIFF/ECW, DSM, model 3D visual, serta estimasi cut & fill di permukaan terbuka.',
                    'Point cloud terklasifikasi (.las/.laz), Bare Earth DTM, DSM, garis kontur CAD (.dwg/.dxf), dan profil koridor.',
                    'Peta indeks vegetasi (seperti NDVI), zonasi vigor tanaman, identifikasi empty spot, dan tren vegetasi berkala.',
                    'Dokumentasi foto/video zoom tinggi, citra radiometrik termal, dan peta titik temuan visual yang perlu tindak lanjut.',
                ],
                [
                    'Halaman Layanan Terkait',
                    'Sektor Perencanaan, Pertambangan, serta Kategori Foto Udara & LiDAR.',
                    'Kategori Jasa Foto Udara & LiDAR, Sektor Pertambangan, dan Sektor Kehutanan.',
                    'Kategori Jasa Foto Multispectral & Hyperspectral serta Sektor Perkebunan & Pertanian.',
                    'Sektor Energi & Infrastruktur serta Sektor Bencana & Lingkungan.',
                ],
            ],
        ],
        'workflow' => [
            'title' => 'Alur Kerja Layanan Lapangan hingga Dashboard Operasional',
            'desc'  => 'Tahapan kolaborasi terstruktur yang memastikan keselarasan antara kondisi aktual di lapangan, protokol geodetik, dan format data penerima di sisi tim Anda.',
            'steps' => [
                [
                    'step'  => '01',
                    'title' => 'Konsultasi, Review Area & Assessment Kebutuhan',
                    'desc'  => 'Evaluasi batas wilayah kerja dan koordinat proyek, tutupan vegetasi, skala atau interval kontur yang dibutuhkan, akses perizinan, dan penentuan metode akuisisi paling tepat guna.',
                ],
                [
                    'step'  => '02',
                    'title' => 'Perencanaan Misi Terbang, Izin Ruang Udara & K3',
                    'desc'  => 'Penyusunan rancangan koridor terbang, konfigurasi stasiun dasar GNSS, pemenuhan prosedur K3/HSE site (seperti induksi KTT tambang), serta koordinasi perizinan ruang udara.',
                ],
                [
                    'step'  => '03',
                    'title' => 'Akuisisi Data Udara & Pengukuran Titik Kontrol (GCP/ICP)',
                    'desc'  => 'Pengumpulan data di lokasi memakai platform UAV dan sensor terkalibrasi (didukung sistem kendali FDS Station) bersamaan dengan pengukuran titik kontrol dan titik uji independen di tanah.',
                ],
                [
                    'step'  => '04',
                    'title' => 'Pengolahan Data, Klasifikasi & Pemeriksaan Kualitas',
                    'desc'  => 'Pemrosesan ortofoto, lintasan PPK, penyaringan dan klasifikasi point cloud (ASPRS), pemodelan DTM/DSM/kontur, penghitungan volume maupun indeks vegetasi, serta uji akurasi ICP.',
                ],
                [
                    'step'  => '05',
                    'title' => 'Serah Terima Deliverables, GIS & Opsi Dashboard Periodik',
                    'desc'  => 'Penyerahan paket peta siap buka ke CAD/GIS/mine planning, laporan verifikasi mutu, basis data spasial historis, hingga pendampingan integrasi maupun pelatihan tim internal klien.',
                ],
            ],
        ],
        'cost_framework' => [
            'eyebrow'  => 'Panduan Persiapan Anggaran & Pengadaan B2B',
            'title'    => 'Bagaimana Estimasi Penawaran Layanan UAV FDS Disusun?',
            'intro'    => 'Karena setiap proyek industri memiliki karakter lapangan, kondisi tutupan lahan, dan standar pelaporan yang khas, FDS menyusun penawaran biaya berdasarkan evaluasi batas area kerja serta KAK resmi perusahaan Anda:',
            'factors'  => [
                [
                    'name' => 'Jenis Sensor & Kedalaman Analisis Data',
                    'desc' => 'Kebutuhan akuisisi foto udara fotogrametri berbeda struktur sumber dayanya dibanding survei Drone LiDAR, pencitraan hyperspectral, maupun inspeksi aset jarak dekat.',
                ],
                [
                    'name' => 'Luasan Area, Geometri Blok/Koridor & Tutupan Lahan',
                    'desc' => 'Total hektar blok kompak, panjang rute koridor memanjang, kerapatan vegetasi, serta kontur medan memengaruhi jumlah hari akuisisi dan kapasitas komputasi pengolahan.',
                ],
                [
                    'name' => 'Lokasi Site, Logistik Mobilisasi & Persyaratan HSE',
                    'desc' => 'Jarak mobilisasi tim dari Sleman/hub terdekat ke luar Pulau Jawa, transportasi lokal 4WD atau perahu, serta durasi induksi K3 kawasan tambang/energi.',
                ],
                [
                    'name' => 'Skema Kerja Sama (Proyek Tunggal vs Kontrak Berkala vs Beli Armada)',
                    'desc' => 'Kontrak monitoring bulanan/kuartalan memberi efisiensi mobilisasi, sementara organisasi dengan operasi harian tinggi dapat mengevaluasi opsi pembelian unit UAV FDS + pelatihan tim.',
                ],
            ],
            'kak_checklist' => [
                'Informasi lokasi proyek (kota/kabupaten dan provinsi) serta perkiraan luas area atau panjang koridor.',
                'Pilihan sektor proyek dan jenis layanan utama (Foto Udara, LiDAR, Multispectral, Inspeksi Aset, atau Pelatihan).',
                'Target output data (Ortofoto, DTM/DSM, Point Cloud LAS, kontur DWG interval tertentu, volume stockpile, atau NDVI) serta datum koordinat.',
                'Perkiraan jadwal pelaksanaan lapangan dan skema pengadaan yang direncanakan (jasa proyek atau pengadaan unit + pelatihan).',
            ],
        ],
        'eeat_proof' => [
            'eyebrow'  => 'Keandalan Produsen UAV Nasional',
            'title'    => 'Dari Manufaktur Wahana Yogyakarta Hingga Operasi Lapangan Seluruh Indonesia',
            'intro'    => 'PT Karya Solusi Angkasa (Full Drone Solutions / FDS) mengoperasikan fasilitas manufaktur pesawat nirawak di Sleman, Daerah Istimewa Yogyakarta dengan sistem manajemen mutu ISO 9001:2015, sertifikasi TKDN + BMP 60,74% pada lini pertanian FERTO, serta SNI 9199:2023 pada FERTO 15 dan FERTO 22.',
            'bullets'  => [
                'Mandiri secara perangkat keras & lunak: lini wahana produksi lokal meliputi seri pertanian FERTO (5L–50L), Fixed-Wing VTOL DELTAV (daya jelajah 60 km), drone logistik DELFRO, dan platform inspeksi MULTIPURPOSE berbasis GCS berbahasa Indonesia FDS Station.',
                'Kesiapan administrasi B2B, BUMN, dan pengadaan pemerintah melalui kelengkapan legalitas perusahaan serta registrasi produk pada ekosistem pengadaan nasional e-Katalog LKPP.',
                'Transparansi teknis di setiap kontrak: seluruh parameter penerbangan, pengikatan jaring kontrol tanah GCP/ICP, serta batasan fisik sensor disampaikan terbuka sesuai KAK/TOR yang disepakati.',
            ],
            'portfolio_note' => 'Dokumentasi proyek dan portofolio resmi tiap sektor disiapkan secara terstruktur sesuai izin publikasi masing-masing mitra, lengkap dengan rincian metode survei, parameter kontrol mutu, dan format deliverable.',
        ],
        'reading_links' => [
            ['label' => 'Layanan Spesialis: Jasa Pemetaan & Survey LiDAR Indonesia', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')],
            ['label' => 'Solusi Pertambangan: Jasa Survey LiDAR Tambang & Volume Stockpile', 'url' => home_url('/layanan/pertambangan/')],
            ['label' => 'Bandingkan Lini Armada UAV Produksi FDS (FERTO, DELTAV, DELFRO, MULTIPURPOSE)', 'url' => home_url('/bandingkan/')],
            ['label' => 'Profil PT Karya Solusi Angkasa & Sertifikasi ISO/TKDN', 'url' => home_url('/tentang-kami/')],
            ['label' => 'Sertifikasi TKDN Manufaktur UAV FDS Kemenperin RI', 'slug' => 'fds-resmi-raih-sertifikasi-tkdn-4485-dari-kemenperin-ri'],
        ],
        'faqs' => [
            [
                'q' => 'Layanan UAV dan pemetaan apa saja yang disediakan oleh Full Drone Solutions (FDS)?',
                'a' => 'FDS menyediakan layanan drone mapping fotogrametri, LiDAR survey 3D, multispectral dan hyperspectral imaging, aerial inspection visual dan termal, pengembangan UAV khusus, serta program pelatihan, bootcamp, dan persiapan sertifikasi operator untuk enam sektor industri utama.',
            ],
            [
                'q' => 'Sektor industri apa saja yang dilayani oleh solusi UAV FDS?',
                'a' => 'Solusi Industri FDS mencakup enam sektor utama: Pertambangan, Perkebunan dan Pertanian, Kehutanan dan Lingkungan, Energi dan Infrastruktur, Perencanaan Wilayah dan Konstruksi, serta Bencana dan Lingkungan.',
            ],
            [
                'q' => 'Apakah FDS hanya menyediakan jasa akuisisi foto udara, atau juga pengolahan data hingga GIS?',
                'a' => 'Selain pengumpulan data udara menggunakan UAV dan sensor pemetaan, tim kami menangani seluruh alur pengolahan hingga produk siap pakai—meliputi high-resolution orthophoto, DTM/DSM/DEM, point cloud terklasifikasi (.las/.laz), peta kontur CAD (.dwg/.dxf), analisis volume dan vegetasi NDVI, hingga penyusunan basis data GIS.',
            ],
            [
                'q' => 'Bagaimana cara menentukan metode pemetaan antara foto udara fotogrametri, LiDAR, dan multispectral?',
                'a' => 'Pemilihan metode ditentukan melalui tahap assessment awal berdasarkan kondisi tutupan vegetasi, luasan dan geometri area, topografi, serta tujuan akhir proyek—misalnya foto udara RGB untuk lahan terbuka, LiDAR untuk menembus celah kanopi hutan atau lereng tambang curam, dan multispectral untuk analisis kesehatan tanaman.',
            ],
            [
                'q' => 'Apakah FDS melayani pekerjaan survei di luar Pulau Jawa dan kawasan terpencil?',
                'a' => 'Ya. Tim operasional dan geospasial Full Drone Solutions melayani mobilisasi survei proyek di berbagai wilayah kepulauan Indonesia—meliputi Sumatra, Kalimantan, Sulawesi, Jawa, Bali, Nusa Tenggara, hingga Indonesia Timur—dengan perencanaan logistik dan standar K3/HSE lokasi.',
            ],
            [
                'q' => 'Bagaimana prosedur permintaan penawaran proyek, apakah bisa sekaligus konsultasi pengadaan unit drone?',
                'a' => 'Anda dapat mengirimkan informasi lokasi proyek (kota/provinsi), perkiraan luasan area, dan kebutuhan output melalui halaman Kontak resmi FDS atau melalui WhatsApp resmi FDS. Tim kami akan menjadwalkan diskusi teknis baik untuk skema jasa survei maupun pengadaan armada UAV mandiri beserta pelatihan.',
            ],
        ],
        'quote_defaults' => [
            'eyebrow'      => 'Konsultasi & Request for Proposal (RFP)',
            'title'        => 'Ajukan Kebutuhan Pemetaan, Survei LiDAR, Inspeksi, atau Pelatihan Tim Anda',
            'subtitle'     => 'Pilih layanan atau sektor proyek pada formulir ini dan jelaskan lokasi serta ringkasan kebutuhan agar tim engineering FDS menyusun evaluasi awal.',
            'wa_prefill'   => 'Halo tim Enterprise FDS, kami ingin berkonsultasi mengenai layanan UAV dan pemetaan udara untuk proyek perusahaan kami.',
            'placeholder'  => 'Jelaskan ruang lingkup kebutuhan (sektor proyek, metode foto udara/LiDAR/multispectral/inspeksi, estimasi luas area, atau kebutuhan pelatihan tim)...',
        ],
        'seo' => [
            'meta_title' => 'Jasa Drone Mapping Indonesia & Solusi UAV | FDS',
            'meta_desc'  => 'Jasa drone mapping Indonesia, survey LiDAR, multispectral, inspeksi aset, dan pelatihan pilot UAV dari produsen TKDN PT Karya Solusi Angkasa (FDS).',
        ],
        'placeholders' => [
            'Konfirmasi portofolio proyek resmi terverifikasi per sektor yang mendapat izin klien untuk dipublikasikan di halaman Hub maupun masing-masing sektor.',
            'Kisaran tarif patokan atau skema minimum charge resmi FDS per layanan apabila manajemen memutuskan untuk menampilkan angka indikatif publik.',
            'SLA resmi waktu penyusunan proposal teknis serta estimasi rata-rata waktu olah data per 100 ha apabila perusahaan ingin menetapkannya sebagai komitmen tertulis.',
        ],
    ];
}

/**
 * Mengambil data Hub Layanan (/layanan/) setelah menggabungkan override WP Admin
 * secara non-destruktif (option: fds_layanan_hub_overrides_v1).
 */
function fds_get_layanan_hub_data(bool $fresh = false): array {
    static $memo = null;
    if (!$fresh && $memo !== null) {
        return $memo;
    }

    $default  = fds_get_default_layanan_hub_data();
    $override = get_option('fds_layanan_hub_overrides_v1', null);
    $memo     = (is_array($override) && !empty($override))
        ? fds_merge_layanan_data($default, $override)
        : $default;

    $cta_url = trim((string) ($memo['cta_primary_url'] ?? ''));
    if ($cta_url === '' || $cta_url === '#form-penawaran') {
        $memo['cta_primary_url'] = home_url('/kontak/#hubungi-form');
    }

    if (function_exists('fds_deep_decode')) {
        $memo = \fds_deep_decode($memo);
    }

    return $memo;
}

/**
 * Label antarmuka bersama untuk seluruh template Layanan, Mega Menu Layanan,
 * Footer kolom Layanan, Form Penawaran, Sticky Bar, dan halaman /terima-kasih/.
 */
function fds_get_default_layanan_ui_texts(): array {
    return [
        // Bersama & Hero
        'cta_wa_text'                    => 'Konsultasi via WhatsApp',
        'trust_bar'                      => 'Manufaktur UAV Nasional · ISO 9001:2015 · TKDN 60,74% (Seri FERTO)',
        'sticky_cta_label'               => 'Minta Penawaran',

        // Mega Menu & Footer Kolom Layanan
        'menu_industries_heading'        => 'Solusi Industri (6 Sektor)',
        'menu_hub_link_text'             => 'Hub Layanan',
        'menu_categories_heading'        => 'Layanan Spesialis & Pelatihan',
        'menu_all_services_text'         => 'Lihat Semua Layanan',
        'footer_layanan_heading'         => 'Layanan',
        'footer_hub_link_text'           => 'Semua Layanan UAV (Hub)',

        // Template Hub (/layanan/)
        'hub_intro_eyebrow'              => 'Tentang Layanan Terpadu FDS',
        'hub_intro_heading'              => 'Ekosistem Layanan Pemetaan, Sensor & UAV Nasional',
        'hub_sectors_eyebrow'            => 'Solusi Industri FDS',
        'hub_sectors_heading'            => 'Enam Sektor Industri Strategis',
        'hub_sectors_desc'               => 'Pilih sektor operasional Anda untuk mempelajari cakupan survei udara, pemodelan geospasial, inspeksi aset, dan output teknis lengkap.',
        'hub_sector_card_cta'            => 'Pelajari solusi sektor',
        'hub_categories_eyebrow'         => 'Layanan Lintas Industri',
        'hub_categories_heading'         => 'Foto Udara, LiDAR, Sensor Spektral & Pelatihan Tim',
        'hub_categories_desc'            => 'Kapabilitas akuisisi data geospasial dan peningkatan kapasitas operator yang dapat disesuaikan lintas sektor.',
        'hub_category_card_cta'          => 'Lihat detail layanan',
        'hub_why_eyebrow'                => 'Keunggulan Terpadu',
        'hub_workflow_eyebrow'           => 'Metodologi Kerja',
        'hub_cost_cta_text'              => 'Kirim rincian lokasi & KAK untuk estimasi resmi',
        'hub_reading_heading'            => 'Tautan Teknis & Armada Terkait',
        'hub_faq_title'                  => 'Pertanyaan Umum Layanan FDS',
        'hub_faq_subtitle'               => 'Informasi awal terkait ruang lingkup proyek, pemilihan metode survei udara, integrasi data spasial GIS, dan prosedur konsultasi.',

        // Template Sektor (/layanan/{sektor}/)
        'sektor_cta_primary_prefix'      => 'Minta Penawaran ',
        'sektor_cta_secondary_text'      => 'Lihat Solusi & Output Data',
        'sektor_solutions_eyebrow_prefix'=> 'Ruang Lingkup Sektor ',
        'sektor_solutions_heading'       => 'Solusi dan Layanan',
        'sektor_products_heading'        => 'Produk dan Teknologi Pendukung',
        'sektor_outputs_heading'         => 'Output Pekerjaan',
        'sektor_closing_heading'         => 'Dukungan Lintas Tim Operasional',
        'sektor_deliverables_eyebrow'    => 'Detail Teknis & Deliverables',
        'sektor_deliverables_heading'    => 'Cakupan Konsultasi, Analisis & Output Data',
        'sektor_deliverables_sub_prefix' => 'Rincian aktivitas konsultasi, metode akuisisi, pengolahan spasial, hingga dashboard pemantauan pada sektor ',
        'sektor_workflow_eyebrow'        => 'Standar Pelaksanaan',
        'sektor_workflow_heading_prefix' => 'Alur Kerja Survei & Pengendalian Mutu Sektor ',
        'sektor_formats_heading'         => 'Format File Penyerahan',
        'sektor_tools_heading'           => 'Perangkat Lunak Penerima',
        'sektor_cost_cta_text'           => 'Kirim lokasi & kebutuhan proyek untuk estimasi resmi',
        'sektor_reading_heading'         => 'Referensi Armada & Artikel Teknis',
        'sektor_related_heading'         => 'Layanan & Sektor Terkait',
        'sektor_related_subtitle'        => 'Telusuri metode sensor dan solusi lintas industri lainnya dari Full Drone Solutions.',
        'sektor_related_all_link'        => 'Semua Layanan FDS',
        'sektor_related_card_cta'        => 'Jelajahi layanan terkait',
        'sektor_faq_prefix'              => 'Pertanyaan Umum: ',
        'sektor_faq_subtitle'            => 'Jawaban ringkas terkait metodologi, deliverables, integrasi software, dan tahapan kerja lapangan.',

        // Template Kategori (/layanan/{kategori}/)
        'kat_cta_primary_prefix'         => 'Minta Penawaran ',
        'kat_cta_secondary_text'         => 'Pelajari Ruang Lingkup',
        'kat_intro_heading_prefix'       => 'Tentang ',
        'kat_cap_eyebrow'                => 'Cakupan Teknis',
        'kat_cap_heading'                => 'Kapabilitas & Ruang Lingkup Layanan',
        'kat_workflow_eyebrow'           => 'Standar Pelaksanaan',
        'kat_workflow_heading_prefix'    => 'Alur Kerja & Kontrol Kualitas ',
        'kat_formats_heading'            => 'Format Output & Modul',
        'kat_tools_heading'              => 'Perangkat Lunak & Platform Pendukung',
        'kat_outputs_heading'            => 'Output & Deliverables Utama',
        'kat_sectors_heading'            => 'Penerapan di Sektor Industri',
        'kat_sector_card_cta'            => 'Lihat sektor',
        'kat_cost_cta_text'              => 'Kirim rincian kebutuhan untuk estimasi penawaran',
        'kat_reading_heading'            => 'Referensi Teknis & Armada Terkait',
        'kat_faq_prefix'                 => 'Pertanyaan Umum: ',
        'kat_faq_subtitle'               => 'Penjelasan teknis, format file keluaran, dan informasi yang dapat Anda konsultasikan bersama tim FDS.',

        // Form Penawaran Bersama (Quotation Section)
        'quote_kak_heading'              => 'Informasi yang Membantu Evaluasi Awal:',
        'quote_wa_btn'                   => 'Diskusi via WhatsApp',
        'quote_phone_label'              => 'Telepon Langsung',
        'quote_email_label'              => 'Email Resmi',
        'quote_trust_footer'             => 'PT Karya Solusi Angkasa (FDS) · Manufaktur UAV Sleman, Yogyakarta · ISO 9001:2015',
        'quote_form_heading'             => 'Formulir Permintaan Penawaran & Konsultasi Proyek',
        'quote_form_subtitle'            => 'Lengkapi rincian berikut agar proposal teknis disusun sesuai kondisi lapangan Anda. Bidang bertanda * wajib diisi.',
        'quote_form_submit_text'         => 'Kirim Permintaan Penawaran',
        'quote_form_privacy_note'        => 'Seluruh informasi proyek dijaga kerahasiaannya sesuai kebijakan privasi FDS.',

        // Halaman Konfirmasi (/terima-kasih/)
        'ty_badge'                       => 'Permintaan Penawaran Tercatat di Sistem FDS',
        'ty_h1'                          => 'Terima Kasih, Permintaan Konsultasi Anda Telah Kami Terima',
        'ty_subtitle'                    => 'Tim Full Drone Solutions (PT Karya Solusi Angkasa) akan meninjau fokus layanan, detail lokasi proyek, serta rincian kebutuhan yang Anda kirimkan untuk menyiapkan diskusi teknis dan estimasi penawaran yang relevan.',
        'ty_ref_label'                   => 'Nomor Referensi Inquiry',
        'ty_summary_heading'             => 'Ringkasan Pengajuan Proyek',
        'ty_lbl_service'                 => 'Fokus Layanan / Sektor',
        'ty_lbl_company'                 => 'Perusahaan / Instansi',
        'ty_lbl_location'                => 'Lokasi Proyek',
        'ty_lbl_area'                    => 'Perkiraan Luas / Target Jadwal',
        'ty_steps_heading'               => 'Tahapan Peninjauan Berikutnya oleh Tim FDS',
        'ty_step_prefix'                 => 'Tahap',
        'ty_step1_title'                 => 'Verifikasi Data & Lokasi Proyek',
        'ty_step1_desc'                  => 'Tim kami memastikan kelengkapan lokasi wilayah kerja serta memeriksa kejelasan cakupan kebutuhan atau format output data yang Anda butuhkan.',
        'ty_step2_title'                 => 'Evaluasi Metode & Kesiapan Lapangan',
        'ty_step2_desc'                  => 'Tim geospasial mengevaluasi karakteristik tutupan lahan, pilihan metode akuisisi (fotogrametri, LiDAR, atau multispectral), kebutuhan titik kontrol, dan izin ruang udara.',
        'ty_step3_title'                 => 'Koordinasi Diskusi Teknis & Proposal',
        'ty_step3_desc'                  => 'Tim enterprise akan menghubungi email atau WhatsApp bisnis Anda untuk klarifikasi ruang lingkup akhir sebelum penerbitan dokumen proposal resmi.',
        'ty_extra_heading'               => 'Perlu Mengirimkan Dokumen Tambahan atau Koordinasi Mendesak?',
        'ty_extra_desc'                  => 'Anda dapat langsung melampirkan dokumen KAK, poligon lokasi (.SHP/.GeoJSON), atau spesifikasi teknis tambahan melalui email {email} atau terhubung ke tim kami melalui WhatsApp ({phone}).',
        'ty_btn_wa'                      => 'Lanjut via WhatsApp',
        'ty_btn_back'                    => 'Kembali ke Layanan',
        'ty_btn_compare'                 => 'Bandingkan Armada UAV',
    ];
}

function fds_get_layanan_ui_texts(bool $fresh = false): array {
    static $memo = null;
    if (!$fresh && $memo !== null) {
        return $memo;
    }

    $default  = fds_get_default_layanan_ui_texts();
    $override = get_option('fds_layanan_ui_overrides_v1', null);
    if (is_array($override) && !empty($override)) {
        $merged = array_merge($default, $override);
    } else {
        $merged = $default;
    }

    // Pastikan label CTA tidak menyimpan karakter panah horizontal/siku lama dari DB
    // karena template Blade merender ikon SVG panah serong sistem secara konsisten.
    $arrow_keys = [
        'menu_hub_link_text',
        'menu_all_services_text',
        'hub_cost_cta_text',
        'sektor_cost_cta_text',
        'sektor_related_all_link',
        'kat_cost_cta_text',
    ];
    foreach ($arrow_keys as $ak) {
        if (isset($merged[$ak]) && is_string($merged[$ak])) {
            $decoded = function_exists('fds_clean_string') ? \fds_clean_string($merged[$ak]) : html_entity_decode($merged[$ak], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $merged[$ak] = trim((string) preg_replace('/(?:\s*(?:→|↗|›|»|->|&rarr;|&rsaquo;|&raquo;|&gt;|>))+$/u', '', $decoded));
        }
    }

    if (function_exists('fds_deep_decode')) {
        $merged = \fds_deep_decode($merged);
    }

    $memo = $merged;
    return $memo;
}

/**
 * Helper: Resolve URL untuk artikel blog/halaman opsional berdasarkan slug.
 * Mengembalikan URL penuh HANYA jika status post/page sudah 'publish', selain itu ''.
 */
function fds_resolve_optional_post_url(string $slug): string {
    $clean_slug = trim($slug, '/ ');
    if ($clean_slug === '') {
        return '';
    }
    $post = get_page_by_path($clean_slug, OBJECT, ['post', 'page']);
    if ($post && $post->post_status === 'publish') {
        $permalink = get_permalink($post->ID);
        return is_string($permalink) ? $permalink : '';
    }
    return '';
}

/**
 * Helper: Daftar opsi dropdown layanan standar untuk form quotation B2B.
 */
function fds_get_layanan_service_options(): array {
    $options = [
        'layanan' => 'Konsultasi Umum Solusi UAV & Pemetaan (Hub Layanan)',
    ];

    foreach (fds_get_layanan_categories() as $slug => $cat) {
        $options[$slug] = (string) ($cat['short_title'] ?? ucfirst(str_replace('-', ' ', $slug)));
    }

    foreach (fds_get_layanan_sectors() as $slug => $sec) {
        $options[$slug] = 'Sektor ' . (string) ($sec['card_title'] ?? ucfirst(str_replace('-', ' ', $slug)));
    }

    $options['pengadaan-armada-uav'] = 'Pengadaan Armada Drone FDS (Seri FERTO TKDN / VTOL DELTAV / MULTIPURPOSE)';

    return $options;
}

/**
 * Helper: Kembalikan daftar lengkap seluruh halaman Layanan (untuk sitemap, menu, & verifikasi).
 */
function fds_get_all_layanan_paths(): array {
    $paths = ['layanan'];
    foreach (array_keys(fds_get_layanan_sectors()) as $slug) {
        $paths[] = 'layanan/' . $slug;
    }
    foreach (array_keys(fds_get_layanan_categories()) as $slug) {
        $paths[] = 'layanan/' . $slug;
    }
    return $paths;
}

/**
 * Deteksi apakah request saat ini berada pada Hub Layanan, salah satu Sektor, atau salah satu Kategori.
 * Mengembalikan array ['kind' => 'hub'|'sektor'|'kategori', 'slug' => ..., 'data' => ...] atau null.
 */
function fds_get_current_layanan_context(): ?array {
    $sectors    = fds_get_layanan_sectors();
    $categories = fds_get_layanan_categories();

    if (is_page('layanan')) {
        return [
            'kind' => 'hub',
            'slug' => 'layanan',
            'data' => fds_get_layanan_hub_data(),
        ];
    }

    foreach ($sectors as $slug => $data) {
        if (is_page('layanan/' . $slug) || (is_page($slug) && fds_is_child_of_layanan())) {
            return [
                'kind' => 'sektor',
                'slug' => $slug,
                'data' => $data,
            ];
        }
    }

    foreach ($categories as $slug => $data) {
        if (is_page('layanan/' . $slug) || (is_page($slug) && fds_is_child_of_layanan())) {
            return [
                'kind' => 'kategori',
                'slug' => $slug,
                'data' => $data,
            ];
        }
    }

    // Fallback berbasis template file jika slug halaman disesuaikan di WP Admin
    $tpl = get_page_template_slug(get_queried_object_id());
    $post_slug = get_post_field('post_name', get_queried_object_id());
    if ($tpl === 'template-layanan-hub.blade.php') {
        return ['kind' => 'hub', 'slug' => 'layanan', 'data' => fds_get_layanan_hub_data()];
    }
    if ($tpl === 'template-layanan-sektor.blade.php' && isset($sectors[$post_slug])) {
        return ['kind' => 'sektor', 'slug' => $post_slug, 'data' => $sectors[$post_slug]];
    }
    if ($tpl === 'template-layanan-kategori.blade.php' && isset($categories[$post_slug])) {
        return ['kind' => 'kategori', 'slug' => $post_slug, 'data' => $categories[$post_slug]];
    }

    return null;
}

function fds_is_child_of_layanan(): bool {
    $qid = get_queried_object_id();
    if (!$qid) {
        return false;
    }
    $parent_id = (int) wp_get_post_parent_id($qid);
    if ($parent_id <= 0) {
        return false;
    }
    return get_post_field('post_name', $parent_id) === 'layanan';
}

/**
 * Provisioning Non-Destruktif Halaman /layanan/, 6 Sektor, dan 3 Kategori di WP Pages.
 * Dilindungi transient mutex lock agar request paralel tidak menciptakan halaman duplikat.
 */
function fds_ensure_layanan_pages(bool $force_check = false): void {
    if (!$force_check && get_option('fds_layanan_pages_seeded_v1')) {
        return;
    }

    if (get_transient('fds_layanan_seeding_lock')) {
        return;
    }
    set_transient('fds_layanan_seeding_lock', 1, 60);

    // 1. Hub /layanan/
    $hub_page = get_page_by_path('layanan');
    if (!$hub_page) {
        $hub_id = wp_insert_post([
            'post_title'   => 'Layanan',
            'post_name'    => 'layanan',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
            'menu_order'   => 10,
        ]);
        if ($hub_id && !is_wp_error($hub_id)) {
            update_post_meta($hub_id, '_wp_page_template', 'template-layanan-hub.blade.php');
        }
    } else {
        $hub_id = (int) $hub_page->ID;
        $existing_tpl = get_post_meta($hub_id, '_wp_page_template', true);
        if (empty($existing_tpl) || $existing_tpl === 'default') {
            update_post_meta($hub_id, '_wp_page_template', 'template-layanan-hub.blade.php');
        }
    }

    if (empty($hub_id) || is_wp_error($hub_id)) {
        delete_transient('fds_layanan_seeding_lock');
        return;
    }

    $created_any = false;
    $order       = 1;

    // 2. 6 Halaman Sektor (/layanan/{sektor}/)
    foreach (fds_get_layanan_sectors() as $slug => $sector) {
        $existing = get_page_by_path('layanan/' . $slug);
        if (!$existing) {
            $pid = wp_insert_post([
                'post_title'   => $sector['card_title'],
                'post_name'    => $slug,
                'post_parent'  => $hub_id,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
                'menu_order'   => $order,
            ]);
            if ($pid && !is_wp_error($pid)) {
                update_post_meta($pid, '_wp_page_template', 'template-layanan-sektor.blade.php');
                $created_any = true;
            }
        } else {
            $existing_tpl = get_post_meta($existing->ID, '_wp_page_template', true);
            if (empty($existing_tpl) || $existing_tpl === 'default') {
                update_post_meta($existing->ID, '_wp_page_template', 'template-layanan-sektor.blade.php');
            }
        }
        $order++;
    }

    // 3. 3 Halaman Kategori Layanan (/layanan/{kategori}/)
    foreach (fds_get_layanan_categories() as $slug => $cat) {
        $existing = get_page_by_path('layanan/' . $slug);
        if (!$existing) {
            $pid = wp_insert_post([
                'post_title'   => $cat['short_title'],
                'post_name'    => $slug,
                'post_parent'  => $hub_id,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
                'menu_order'   => $order,
            ]);
            if ($pid && !is_wp_error($pid)) {
                update_post_meta($pid, '_wp_page_template', 'template-layanan-kategori.blade.php');
                $created_any = true;
            }
        } else {
            $existing_tpl = get_post_meta($existing->ID, '_wp_page_template', true);
            if (empty($existing_tpl) || $existing_tpl === 'default') {
                update_post_meta($existing->ID, '_wp_page_template', 'template-layanan-kategori.blade.php');
            }
        }
        $order++;
    }

    if ($created_any || !get_option('fds_layanan_pages_seeded_v1')) {
        flush_rewrite_rules(false);
        update_option('fds_layanan_pages_seeded_v1', 1, false);
    }

    delete_transient('fds_layanan_seeding_lock');
}

/**
 * Provisioning Non-Destruktif Halaman Konfirmasi Lead /terima-kasih/
 * Dilindungi transient mutex lock + option guard; di-noindex oleh app/seo.php.
 */
function fds_ensure_thank_you_page(bool $force_check = false): void {
    if (!$force_check && get_option('fds_page_terima_kasih_seeded_v1')) {
        return;
    }

    if (get_transient('fds_ty_seeding_lock')) {
        return;
    }
    set_transient('fds_ty_seeding_lock', 1, 60);

    $ty_page = get_page_by_path('terima-kasih');
    $created = false;

    if (!$ty_page) {
        $pid = wp_insert_post([
            'post_title'   => 'Terima Kasih',
            'post_name'    => 'terima-kasih',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
            'menu_order'   => 99,
        ]);
        if ($pid && !is_wp_error($pid)) {
            update_post_meta($pid, '_wp_page_template', 'page-terima-kasih.blade.php');
            $created = true;
        }
    } else {
        $existing_tpl = get_post_meta($ty_page->ID, '_wp_page_template', true);
        if (empty($existing_tpl) || $existing_tpl === 'default') {
            update_post_meta($ty_page->ID, '_wp_page_template', 'page-terima-kasih.blade.php');
        }
    }

    if ($created || !get_option('fds_page_terima_kasih_seeded_v1')) {
        if ($created) {
            flush_rewrite_rules(false);
        }
        update_option('fds_page_terima_kasih_seeded_v1', 1, false);
    }

    delete_transient('fds_ty_seeding_lock');
}

add_action('after_switch_theme', function () {
    fds_ensure_layanan_pages(true);
    fds_ensure_thank_you_page(true);
});

add_action('init', function () {
    if (!get_option('fds_layanan_pages_seeded_v1')) {
        fds_ensure_layanan_pages(true);
    }
    if (!get_option('fds_page_terima_kasih_seeded_v1')) {
        fds_ensure_thank_you_page(true);
    }
}, 25);

/**
 * Pengaman routing template Sage 10 jika halaman layanan atau /terima-kasih/
 * belum memiliki _wp_page_template atau berada pada fallback slug standar.
 */
add_filter('page_template', function ($template) {
    if (is_page('terima-kasih')) {
        $ty_located = locate_template(['resources/views/page-terima-kasih.blade.php']);
        if ($ty_located) {
            return $ty_located;
        }
    }

    $ctx = fds_get_current_layanan_context();
    if (!$ctx) {
        return $template;
    }

    $map = [
        'hub'      => 'resources/views/template-layanan-hub.blade.php',
        'sektor'   => 'resources/views/template-layanan-sektor.blade.php',
        'kategori' => 'resources/views/template-layanan-kategori.blade.php',
    ];
    $rel = $map[$ctx['kind']] ?? '';
    if ($rel !== '') {
        $located = locate_template([$rel]);
        if ($located) {
            return $located;
        }
    }

    return $template;
}, 1);
