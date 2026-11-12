<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS KONTEN KATALOG & KATEGORI PRODUK MANAGER (/produk/ & /kategori-drone/*)
 * =========================================================================
 * Mengelola seluruh konten non-card yang editable pada:
 * - Hub Katalog Produk (/produk/)
 * - Halaman Arsip Kategori Drone (/kategori-drone/agrikultur/, pemetaan-gis, kargo, dll.)
 * - Teks UI Bersama, Sidebar Manufaktur Lokal, SEO Title/Desc & FAQPage Schema
 *
 * Catatan Arsitektur:
 * - Kartu unit drone (<x-drone-card>) mengambil 100% data & Hero image secara otomatis
 *   dari Detail Produk Drone (\App\fds_get_drone_detail_data).
 * - Seluruh konten editorial selain kartu tetap 100% editable dan non-destruktif.
 */

// =========================================================================
// 1. SANITIZATION & CACHE HELPERS
// =========================================================================

function fds_flush_produk_catalog_cache(): void {
    delete_transient('fds_llms_txt_cache_v4');
    delete_transient('fds_llms_txt_cache_v5');
    delete_transient('fds_llms_full_txt_cache_v5');
    delete_transient('fds_bandingkan_catalog_cache_v4');
    if (function_exists(__NAMESPACE__ . '\\fds_purge_ai_and_seo_caches')) {
        fds_purge_ai_and_seo_caches(false);
    } elseif (function_exists('has_action') && has_action('litespeed_purge_all')) {
        do_action('litespeed_purge_all');
    }
}

function fds_produk_clean_text($val, bool $allow_strong = false): string {
    $str = (string) $val;
    if (function_exists('fds_clean_string')) {
        $str = fds_clean_string($str);
    } else {
        $prev = '';
        $max  = 5;
        while ($prev !== $str && $max-- > 0) {
            $prev = $str;
            $str  = html_entity_decode(wp_specialchars_decode($str, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }
    if (function_exists('fds_scrub_legacy_text')) {
        $str = fds_scrub_legacy_text($str, true, true);
    }
    if ($allow_strong) {
        return trim(wp_kses($str, ['strong' => []]));
    }
    return trim(wp_strip_all_tags($str));
}

function fds_produk_sanitize_url($raw_url): string {
    $url = trim(wp_unslash((string) $raw_url));
    if ($url === '') {
        return '';
    }
    if ($url === '{cta_kontak_url}') {
        return '{cta_kontak_url}';
    }
    if (str_starts_with($url, '/') || str_starts_with($url, '#')) {
        return sanitize_text_field($url);
    }
    return esc_url_raw($url);
}

function fds_produk_normalize_href(string $url): string {
    $u = trim($url);
    if ($u === '') {
        return home_url('/produk/');
    }
    if (str_starts_with($u, 'http://') || str_starts_with($u, 'https://') || str_starts_with($u, '#')) {
        return $u;
    }
    return home_url($u);
}

// =========================================================================
// 2. DEFAULTS & GETTERS — HUB KATALOG (/produk/)
// =========================================================================

function fds_get_produk_catalog_defaults(): array {
    return [
        'hero_badge'         => 'Katalog Manufaktur UAV · PT Karya Solusi Angkasa',
        'hero_title'         => 'Armada Drone UAV Indonesia untuk Agrikultur, Pemetaan & Kargo.',
        'hero_desc'          => 'Seluruh unit dirancang dan dirakit di Sleman, Yogyakarta dengan dukungan software kendali FDS Station berbahasa Indonesia serta suku cadang asli siap pakai.',
        'hero_cta1_text'     => 'Minta Penawaran Resmi',
        'hero_cta1_url'      => '/kontak/?produk=armada-uav-fds#hubungi-form',
        'hero_cta2_text'     => 'Bandingkan Model Drone',
        'hero_cta2_url'      => '/bandingkan/',
        'nav_agri_label'     => 'Agrikultur — Seri FERTO',
        'nav_pemetaan_label' => 'Pemetaan & Inspeksi',
        'nav_kargo_label'    => 'Kargo Logistik',

        'highlights' => [
            [
                'title' => 'TKDN + BMP 60,74%',
                'desc'  => 'Sertifikat Kemenperin RI khusus untuk seluruh seri sprayer pertanian <strong>FERTO (5, 10, 15, 22, 30, 50)</strong>.',
            ],
            [
                'title' => 'SNI 9199:2023',
                'desc'  => 'Standar Nasional Indonesia alat semprot tanpa awak resmi pada model <strong>FERTO 15 (17 Liter)</strong> dan <strong>FERTO 22</strong>.',
            ],
            [
                'title' => 'ISO 9001:2015 & FDS Station',
                'desc'  => 'Sistem manajemen mutu perusahaan <strong>PT Karya Solusi Angkasa</strong> di workshop Sleman beserta software kendali misi FDS Station berbahasa Indonesia.',
            ],
        ],

        'cat_agri_badge'     => 'Kategori Agrikultur',
        'cat_agri_title'     => 'Drone Pertanian Presisi Seri FERTO (5L – 50L)',
        'cat_agri_desc'      => 'Seluruh lini FERTO telah bersertifikat TKDN + BMP 60,74% untuk pengadaan instansi dan korporasi, dengan standar SNI 9199:2023 pada model FERTO 15 (kapasitas 17 Liter) dan FERTO 22.',
        'cat_agri_link_text' => 'Halaman Kategori Agrikultur',

        'cat_pemetaan_badge'     => 'Kategori Pemetaan & GIS',
        'cat_pemetaan_title'     => 'Drone Pemetaan Fixed-Wing VTOL & Inspeksi Modular',
        'cat_pemetaan_desc'      => 'DELTAV VTOL (jangkauan hingga 60 km) untuk foto udara ortofoto & LiDAR area luas, serta MULTIPURPOSE (payload 5 kg) untuk kamera termal inframerah dan zoom optik 20x.',
        'cat_pemetaan_link_text' => 'Halaman Kategori Pemetaan & GIS',

        'cat_kargo_badge'     => 'Kategori Logistik Udara',
        'cat_kargo_title'     => 'Drone Kargo Otonom DELFRO',
        'cat_kargo_desc'      => 'Solusi pengiriman muatan kargo 3 – 10 kg (kompartemen 20 × 20 × 30 cm) secara otonom antartitik waypoint untuk logistik medis darurat maupun site terpencil.',
        'cat_kargo_link_text' => 'Halaman Kategori Kargo',

        'matrix_title'    => 'Ringkasan Spesifikasi Lini Wahana FDS',
        'matrix_desc'     => 'Bandingkan kapasitas muatan, durasi terbang, serta kesesuaian sertifikasi tiap lini produk sebelum pengadaan.',
        'matrix_cta_text' => 'Buka Alat Bandingkan Interaktif',
        'matrix_cta_url'  => '/bandingkan/',
        'matrix_columns'  => [
            'Model / Seri',
            'Fungsi Utama',
            'Payload / Kapasitas',
            'Durasi / Jangkauan',
            'Status Sertifikasi',
        ],
        'matrix_rows'     => [
            [
                'FERTO 5 & FERTO 10',
                'Sprayer lahan berbukit, hortikultura & kelompok tani',
                '5 Liter / 10 Liter',
                '10 – 15 menit (1 – 1,5 Ha/jam)',
                'TKDN + BMP 60,74%',
            ],
            [
                'FERTO 15 & FERTO 22',
                'Sprayer komersial & perkebunan sawit / tebu',
                '17 Liter (FERTO 15) / 22 Liter (FERTO 22)',
                '8 Ha/jam (FERTO 15) / 8,5 – 12 Ha/jam (FERTO 22)',
                'TKDN + BMP 60,74% | SNI 9199:2023',
            ],
            [
                'FERTO 30 & FERTO 50',
                'Agroindustri skala masif, sawit & Hutan Tanaman Industri',
                '30 Liter / 50 Liter',
                '15 – 22 Ha/jam',
                'TKDN + BMP 60,74%',
            ],
            [
                'DELTAV',
                'Pemetaan Fixed-Wing Hybrid VTOL topografi & LiDAR',
                '1 – 2 kg (RGB 24–42 MP / LiDAR)',
                '60 – 120 menit (hingga 60 km)',
                '—',
            ],
            [
                'MULTIPURPOSE',
                'Inspeksi SUTET 150kV, solar PV, migas & surveillance',
                '5 kg (Termal IR, 20x Zoom, LiDAR)',
                '15 – 30 menit',
                '—',
            ],
            [
                'DELFRO',
                'Logistik kargo otonom antartitik & pasokan darurat medis',
                '3 – 10 kg (20 × 20 × 30 cm, MTOW 15 kg)',
                '10 – 15 menit (Rute Waypoint)',
                '—',
            ],
        ],

        'faq_title'    => 'Pertanyaan Umum Katalog Drone',
        'faq_subtitle' => 'Ringkasan pertanyaan teknis dan kepatuhan pengadaan lini wahana PT Karya Solusi Angkasa.',
        'faqs'         => [
            [
                'q' => 'Model drone mana saja yang sudah mengantongi sertifikat TKDN 60,74%?',
                'a' => 'Sertifikat TKDN + BMP 60,74% dari Kemenperin RI berlaku untuk seluruh lini drone pertanian sprayer seri FERTO buatan PT Karya Solusi Angkasa, meliputi FERTO 5, FERTO 10, FERTO 15, FERTO 22, FERTO 30, dan FERTO 50. Pada tingkat korporasi, PT Karya Solusi Angkasa juga menerapkan sistem manajemen mutu ISO 9001:2015 dalam proses manufaktur dan pengujiannya.',
            ],
            [
                'q' => 'Model drone FDS mana yang sudah memenuhi standar SNI 9199:2023?',
                'a' => 'Sertifikasi Standar Nasional Indonesia SNI 9199:2023 saat ini berlaku secara resmi pada dua model sprayer utama, yaitu FERTO 15 dan FERTO 22.',
            ],
            [
                'q' => 'Berapa kapasitas tangki aktual pada drone pertanian FERTO 15?',
                'a' => 'FERTO 15 memiliki kapasitas tangki aktual sebesar 17 Liter (rentang kerja 15 – 17 Liter) dengan produktivitas semprot hingga 8 hektare per jam.',
            ],
            [
                'q' => 'Apa perbedaan kegunaan DELTAV, MULTIPURPOSE, dan DELFRO dibandingkan seri FERTO?',
                'a' => 'Seri FERTO berfokus pada penyemprotan cairan dan penyebaran granul pertanian. DELTAV merupakan wahana Fixed-Wing Hybrid VTOL dengan daya jelajah hingga 60 km untuk pemetaan fotogrametri/LiDAR; MULTIPURPOSE adalah multirotor modular payload 5 kg untuk inspeksi aset termal inframerah maupun 20x optical zoom; sedangkan DELFRO adalah wahana kargo logistik berkapasitas 3 – 10 kg.',
            ],
            [
                'q' => 'Apakah pengadaan unit drone FDS disertai pelatihan operator dan dukungan purna jual?',
                'a' => 'Ya. Setiap pengadaan dari PT Karya Solusi Angkasa mencakup pelatihan operasional FDS Station, uji terbang serah terima, serta dukungan suku cadang dan perawatan dari workshop resmi di Sleman, Daerah Istimewa Yogyakarta.',
            ],
        ],

        'cta_label'          => 'Konsultasi Pengadaan & Uji Terbang',
        'cta_title'          => 'Butuh Rekomendasi Spesifikasi Unit atau Dokumen e-Katalog?',
        'cta_desc'           => 'Tim engineering PT Karya Solusi Angkasa siap membantu menyusun kajian teknis armada, jadwal demo unit di workshop Sleman, maupun permintaan penawaran resmi.',
        'cta_primary_text'   => 'Minta Penawaran Unit',
        'cta_primary_url'    => '/kontak/?produk=armada-uav-fds#hubungi-form',
        'cta_secondary_text' => 'Chat WhatsApp Tim Sales',

        'seo_title'   => 'Katalog Produk Drone UAV Indonesia — Full Drone Solutions',
        'seo_desc'    => 'Katalog lengkap drone PT Karya Solusi Angkasa: sprayer FERTO (TKDN 60,74%), pemetaan VTOL DELTAV, kargo DELFRO, dan UAV modular MULTIPURPOSE.',
        'schema_name' => 'Katalog Produk Drone UAV Indonesia — Full Drone Solutions',
        'schema_desc' => 'Katalog resmi pesawat tanpa awak (UAV) PT Karya Solusi Angkasa: seri FERTO (TKDN 60,74%), DELTAV VTOL, MULTIPURPOSE, dan kargo DELFRO.',
    ];
}

function fds_get_produk_catalog_content(bool $fresh = false): array {
    static $memo = null;
    if (!$fresh && $memo !== null) {
        return $memo;
    }

    $defaults = fds_get_produk_catalog_defaults();
    $saved    = get_option('fds_produk_catalog_content_v1', []);
    if (!is_array($saved)) {
        $saved = [];
    }

    $merged = array_merge($defaults, $saved);

    $scalar_keys = [
        'hero_badge', 'hero_title', 'hero_desc', 'hero_cta1_text', 'hero_cta1_url',
        'hero_cta2_text', 'hero_cta2_url', 'nav_agri_label', 'nav_pemetaan_label', 'nav_kargo_label',
        'cat_agri_badge', 'cat_agri_title', 'cat_agri_desc', 'cat_agri_link_text',
        'cat_pemetaan_badge', 'cat_pemetaan_title', 'cat_pemetaan_desc', 'cat_pemetaan_link_text',
        'cat_kargo_badge', 'cat_kargo_title', 'cat_kargo_desc', 'cat_kargo_link_text',
        'matrix_title', 'matrix_desc', 'matrix_cta_text', 'matrix_cta_url',
        'faq_title', 'faq_subtitle',
        'cta_label', 'cta_title', 'cta_desc', 'cta_primary_text', 'cta_primary_url', 'cta_secondary_text',
        'seo_title', 'seo_desc', 'schema_name', 'schema_desc',
    ];
    foreach ($scalar_keys as $k) {
        if (str_ends_with($k, '_url')) {
            $merged[$k] = trim((string) ($merged[$k] ?? ''));
            if ($merged[$k] === '') {
                $merged[$k] = (string) $defaults[$k];
            }
        } else {
            $merged[$k] = fds_produk_clean_text($merged[$k] ?? '');
        }
    }

    // Clean Highlights
    $clean_hl = [];
    $raw_hl   = is_array($merged['highlights'] ?? null) ? $merged['highlights'] : $defaults['highlights'];
    foreach ($raw_hl as $hl) {
        if (!is_array($hl)) {
            continue;
        }
        $t = fds_produk_clean_text($hl['title'] ?? '');
        $d = fds_produk_clean_text($hl['desc'] ?? '', true);
        if ($t !== '' || $d !== '') {
            $clean_hl[] = ['title' => $t, 'desc' => $d];
        }
    }
    $merged['highlights'] = $clean_hl;

    // Clean Matrix Columns & Rows
    $raw_cols   = is_array($merged['matrix_columns'] ?? null) ? $merged['matrix_columns'] : $defaults['matrix_columns'];
    $clean_cols = [];
    foreach ($raw_cols as $col) {
        $c = fds_produk_clean_text($col);
        if ($c !== '') {
            $clean_cols[] = $c;
        }
    }
    $merged['matrix_columns'] = !empty($clean_cols) ? $clean_cols : $defaults['matrix_columns'];

    $col_count  = count($merged['matrix_columns']);
    $raw_rows   = is_array($merged['matrix_rows'] ?? null) ? $merged['matrix_rows'] : $defaults['matrix_rows'];
    $clean_rows = [];
    foreach ($raw_rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $cells = [];
        for ($i = 0; $i < $col_count; $i++) {
            $cells[] = fds_produk_clean_text($row[$i] ?? '');
        }
        if (implode('', $cells) !== '') {
            $clean_rows[] = $cells;
        }
    }
    $merged['matrix_rows'] = $clean_rows;

    // Clean FAQs
    $raw_faqs   = is_array($merged['faqs'] ?? null) ? $merged['faqs'] : $defaults['faqs'];
    $clean_faqs = [];
    foreach ($raw_faqs as $fq) {
        if (!is_array($fq)) {
            continue;
        }
        $q = fds_produk_clean_text($fq['q'] ?? '');
        $a = fds_produk_clean_text($fq['a'] ?? '');
        if ($q !== '' && $a !== '') {
            $clean_faqs[] = ['q' => $q, 'a' => $a];
        }
    }
    $merged['faqs'] = $clean_faqs;

    $memo = $merged;
    return $merged;
}

// =========================================================================
// 3. DEFAULTS & GETTERS — SHARED UI & PER-KATEGORI (/kategori-drone/*)
// =========================================================================

function fds_get_kategori_drone_ui_defaults(): array {
    return [
        'all_products_label' => 'Semua Produk',
        'hero_compare_text'  => 'Bandingkan Spesifikasi Drone',
        'hero_compare_url'   => '/bandingkan/',
        'empty_text_prefix'  => 'Belum ada unit drone terdaftar dalam kategori',
        'empty_link_text'    => 'Lihat seluruh katalog armada FDS',
        'sidebar_title'      => 'Dukungan Manufaktur Lokal',
        'sidebar_desc'       => 'Seluruh unit dirakit di workshop Sleman, D.I. Yogyakarta oleh PT Karya Solusi Angkasa yang menerapkan sistem manajemen mutu perusahaan ISO 9001:2015, dengan sertifikasi produk TKDN + BMP 60,74% untuk lini pertanian FERTO serta SNI 9199:2023 pada FERTO 15 dan FERTO 22.',
        'sidebar_links'      => [
            ['label' => 'Lihat Katalog Lengkap UAV FDS', 'url' => '/produk/'],
            ['label' => 'Bandingkan Spesifikasi Antar Model', 'url' => '/bandingkan/'],
            ['label' => 'Profil Pabrik & Sertifikasi FDS', 'url' => '/tentang-kami/'],
            ['label' => 'Ajukan Penawaran & Jadwal Demo', 'url' => '{cta_kontak_url}'],
        ],
        'faq_title_prefix'   => 'Pertanyaan Umum: Drone',
        'faq_subtitle'       => 'Informasi sertifikasi, pilihan kapasitas muatan, pelatihan operator, dan kesiapan pengadaan B2B maupun instansi.',
    ];
}

function fds_get_kategori_drone_ui_content(bool $fresh = false): array {
    static $memo = null;
    if (!$fresh && $memo !== null) {
        return $memo;
    }

    $defaults = fds_get_kategori_drone_ui_defaults();
    $saved    = get_option('fds_kat_drone_ui_v1', []);
    if (!is_array($saved)) {
        $saved = [];
    }

    $merged = array_merge($defaults, $saved);

    foreach ([
        'all_products_label', 'hero_compare_text', 'empty_text_prefix',
        'empty_link_text', 'sidebar_title', 'sidebar_desc',
        'faq_title_prefix', 'faq_subtitle',
    ] as $k) {
        $merged[$k] = fds_produk_clean_text($merged[$k] ?? '');
    }

    $merged['hero_compare_url'] = trim((string) ($merged['hero_compare_url'] ?? ''));
    if ($merged['hero_compare_url'] === '') {
        $merged['hero_compare_url'] = $defaults['hero_compare_url'];
    }

    $raw_links   = is_array($merged['sidebar_links'] ?? null) ? $merged['sidebar_links'] : $defaults['sidebar_links'];
    $clean_links = [];
    foreach ($raw_links as $lnk) {
        if (!is_array($lnk)) {
            continue;
        }
        $lbl = fds_produk_clean_text($lnk['label'] ?? '');
        $url = trim((string) ($lnk['url'] ?? ''));
        if ($lbl !== '' && $url !== '') {
            $clean_links[] = ['label' => $lbl, 'url' => $url];
        }
    }
    $merged['sidebar_links'] = $clean_links;

    $memo = $merged;
    return $merged;
}

function fds_get_kategori_drone_catalog_defaults(string $term_slug, string $term_name = '', string $term_desc = ''): array {
    $map = [
        'agrikultur' => [
            'headline'         => 'Drone Pertanian Presisi FERTO.',
            'subtitle'         => 'Lini drone sprayer pupuk cair dan penebar granul padat seri FERTO bersertifikat TKDN 60,74% (serta SNI 9199:2023 pada FERTO 15 & FERTO 22) untuk tanaman pangan dan perkebunan kelapa sawit.',
            'cta_kontak_label' => 'Konsultasi Pengadaan & TKDN',
            'cta_kontak_url'   => '/kontak/?layanan=kategori-agrikultur#hubungi-form',
            'guide_title'      => 'Standar Rekayasa Drone Agrikultur FDS untuk Perkebunan & Pangan Nasional',
            'paragraphs'       => [
                'Lini drone pertanian FERTO dikembangkan langsung oleh PT Karya Solusi Angkasa (Full Drone Solutions) di Sleman, Yogyakarta untuk menjawab kelangkaan tenaga kerja semprot dan pemborosan agrokimia di lahan sawah maupun perkebunan kelapa sawit, tebu, dan hortikultura.',
                'Setiap unit dari varian FERTO 5 (5 Liter) hingga FERTO 50 (50 Liter) dilengkapi sistem nozzle atomisasi presisi, radar kontur otomatis (Terrain Following), serta perangkat lunak Ground Control Station FDS Station berbahasa Indonesia yang memudahkan operator Gapoktan, koperasi, dan tim estate enterprise.',
                'Dengan sertifikasi Tingkat Komponen Dalam Negeri (TKDN + BMP) mencapai 60,74% untuk seluruh lini FERTO, standar nasional SNI 9199:2023 pada model FERTO 15 dan FERTO 22, serta ketersediaan suku cadang asli di Indonesia, pengadaan drone agrikultur FDS siap memenuhi ketentuan e-Katalog LKPP untuk instansi pemerintah, BUMN perkebunan, maupun swasta.',
            ],
            'faqs'             => [
                [
                    'q' => 'Model FERTO mana yang paling sesuai untuk lahan sawah terasering vs perkebunan sawit luas?',
                    'a' => 'Untuk petak sempit, hortikultura, dan lahan berbukit, FERTO 5 (5L) dan FERTO 10 (10L) memberikan manuver ringkas. Untuk kebun komersial skala menengah-besar serta perkebunan sawit dan tebu, tersedia FERTO 15 (kapasitas 17 Liter), FERTO 22 (22L), FERTO 30 (30L), hingga FERTO 50 (50L).',
                ],
                [
                    'q' => 'Apakah seluruh seri FERTO memenuhi TKDN 60,74% untuk pengadaan pemerintah dan BUMN?',
                    'a' => 'Ya. Seluruh model FERTO (5, 10, 15, 22, 30, dan 50) memiliki sertifikat TKDN + BMP 60,74%. Khusus pengadaan yang mensyaratkan SNI 9199:2023, tersedia FERTO 15 dan FERTO 22.',
                ],
                [
                    'q' => 'Perangkat lunak apa yang digunakan untuk mengoperasikan armada FERTO?',
                    'a' => 'Seluruh seri FERTO dikendalikan melalui Ground Control Station FDS Station berbahasa Indonesia lengkap dengan fitur perencanaan poligon lahan, Terrain Following, dan Fail-Safe Return to Home.',
                ],
                [
                    'q' => 'Apakah drone FERTO dapat digunakan untuk menyebar pupuk butir padat maupun benih?',
                    'a' => 'Ya. Platform sprayer komersial FERTO mendukung modul opsional Granule Spreader untuk penebaran pupuk butir dan benih padi selain fungsi penyemprotan larutan cair.',
                ],
            ],
            'seo_title' => 'Drone Agrikultur & Sprayer TKDN — Full Drone Solutions',
            'seo_desc'  => 'Katalog drone pertanian & sprayer presisi seri FERTO (5L–50L) berstandar TKDN 60,74% (SNI 9199:2023 pada FERTO 15 & 22) dan kendali FDS Station.',
        ],
        'pemetaan-gis' => [
            'headline'         => 'Drone Pemetaan & Survey LiDAR GIS.',
            'subtitle'         => 'Wahana Fixed-Wing VTOL DELTAV dan multirotor modular MULTIPURPOSE untuk foto udara ortofoto, topografi tambang, dan inspeksi utilitas.',
            'cta_kontak_label' => 'Konsultasi Pengadaan & Demo Unit',
            'cta_kontak_url'   => '/kontak/?layanan=kategori-pemetaan-gis#hubungi-form',
            'guide_title'      => 'Akuisisi Geospasial Akurasi Sub-Sentimeter & Konsultasi GIS Enterprise',
            'paragraphs'       => [
                'Sektor pertambangan, perkebunan HGU, kehutanan karbon, serta perencanaan tata ruang Kawasan Ekonomi Khusus (KEK) menuntut data spasial yang cepat dan akurat. DELTAV VTOL menghadirkan efisiensi terbang jelajah jarak jauh hingga 60 km dan durasi sampai 120 menit untuk memetakan ribuan hektar area tanpa membutuhkan landasan pacu.',
                'Untuk misi inspeksi infrastruktur dan akuisisi sensor khusus, platform MULTIPURPOSE mendukung payload modular kamera termal inframerah, zoom optik 20x, hingga sensor LiDAR ringan untuk penetrasi kanopi hutan dan inspeksi koridor jaringan listrik tegangan tinggi 150 kV.',
                'Seluruh wahana kompatibel dengan alur kerja pemrosesan ortomosaik, Digital Terrain Model (DTM), Digital Surface Model (DSM), kalkulasi volume stockpile cut & fill, hingga penyusunan WebGIS interaktif oleh tim Konsultan GIS PT Karya Solusi Angkasa.',
            ],
            'faqs'             => [
                [
                    'q' => 'Kapan sebaiknya memilih DELTAV VTOL dibandingkan drone multirotor pemetaan biasa?',
                    'a' => 'DELTAV VTOL tepat untuk area konsesi perkebunan, pit tambang, kehutanan, dan koridor infrastruktur ratusan hingga ribuan hektare per hari berkat jangkauan hingga 60 km, durasi 60–120 menit, dan kemampuan lepas landas vertikal tanpa landasan pacu.',
                ],
                [
                    'q' => 'Sensor apa saja yang dapat dipasang pada DELTAV dan MULTIPURPOSE?',
                    'a' => 'DELTAV mendukung muatan 1–2 kg berupa kamera metrik RGB 24–42 MP, sensor multispektral, atau LiDAR ringan. MULTIPURPOSE mendukung payload modular hingga 5 kg termasuk kamera termal inframerah, kamera optical zoom 20x, dan sensor LiDAR inspeksi.',
                ],
                [
                    'q' => 'Standar mutu apa yang diterapkan pada manufaktur DELTAV dan MULTIPURPOSE?',
                    'a' => 'DELTAV dan MULTIPURPOSE diproduksi dan dikalibrasi di workshop resmi PT Karya Solusi Angkasa yang menerapkan sistem manajemen mutu perusahaan bersertifikat ISO 9001:2015 untuk menjamin keandalan operasional industri.',
                ],
            ],
            'seo_title' => 'Drone Pemetaan & Survey LiDAR GIS — Full Drone Solutions',
            'seo_desc'  => 'Katalog drone pemetaan Fixed-Wing VTOL DELTAV dan UAV MULTIPURPOSE untuk foto udara, survey LiDAR, inspeksi aset 150kV, dan pemetaan GIS.',
        ],
        'kargo' => [
            'headline'         => 'Drone Kargo & Logistik Otonom.',
            'subtitle'         => 'Solusi pengiriman muatan cepat dan aman untuk kebutuhan logistik medis darurat, suku cadang industri, dan area kepulauan 3T.',
            'cta_kontak_label' => 'Konsultasi Pengadaan & Demo Unit',
            'cta_kontak_url'   => '/kontak/?layanan=kategori-kargo#hubungi-form',
            'guide_title'      => 'Distribusi Udara Otonom ke Wilayah Terisolir & Fasilitas Lepas Pantai',
            'paragraphs'       => [
                'Keterlambatan pengiriman sampel medis, obat darurat, dan suku cadang krusial akibat kendala geografis kini dapat diatasi dengan drone kargo otonom DELFRO buatan PT Karya Solusi Angkasa. Wahana ini mampu membawa kotak payload 3 hingga 10 kg melintasi medan pegunungan, perkebunan terpencil, dan kepulauan.',
                'Navigasi waypoint otonom yang dikendalikan melalui FDS Station memungkinkan pemantauan posisi real-time, pendaratan presisi, dan mekanisme pengamanan muatan yang handal di kondisi angin tropis.',
                'Didukung pelatihan pilot bersertifikat DKPPU CASR 107 melalui FDS Academy serta layanan perawatan purna jual lokal di Sleman Yogyakarta, pengoperasian armada logistik udara berjalan legal, aman, dan berkelanjutan.',
            ],
            'faqs'             => [
                [
                    'q' => 'Berapa kapasitas angkut maksimum dan ukuran kotak kargo DELFRO?',
                    'a' => 'DELFRO mampu mengangkut muatan efektif 3 hingga 10 kg dalam kompartemen berukuran 20 × 20 × 30 cm dengan bobot lepas landas maksimum (MTOW) 15 kg.',
                ],
                [
                    'q' => 'Apa contoh penggunaan paling efektif untuk drone logistik DELFRO?',
                    'a' => 'DELFRO digunakan untuk distribusi cepat sampel laboratorium, obat darurat kebencanaan, serta pengiriman suku cadang ringan antartitik site industri melewati sungai, perbukitan, atau medan terisolir.',
                ],
                [
                    'q' => 'Bagaimana keamanan penerbangan antar-titik pada DELFRO?',
                    'a' => 'DELFRO menjalankan rute waypoint otomatis yang dimonitor real-time melalui FDS Station Logistics Management dengan dukungan kendali manual cadangan dan prosedur Fail-Safe.',
                ],
            ],
            'seo_title' => 'Drone Kargo Logistik Otonom — Full Drone Solutions',
            'seo_desc'  => 'Platform drone kargo logistik DELFRO payload hingga 10 kg buatan Indonesia untuk distribusi medis darurat, suku cadang, & wilayah kepulauan.',
        ],
    ];

    if (isset($map[$term_slug])) {
        $entry = $map[$term_slug];
        if ($term_desc !== '') {
            $entry['subtitle_term'] = $term_desc;
        }
        return $entry;
    }

    $label = $term_name !== '' ? $term_name : ucwords(str_replace('-', ' ', $term_slug ?: 'Industri'));
    $desc_fallback = $term_desc !== ''
        ? $term_desc
        : 'Katalog resmi pesawat nirawak industrial produksi PT Karya Solusi Angkasa (sistem manajemen mutu perusahaan ISO 9001:2015).';

    return [
        'headline'         => 'Drone ' . $label . '.',
        'subtitle'         => $desc_fallback,
        'cta_kontak_label' => 'Konsultasi Pengadaan & Demo Unit',
        'cta_kontak_url'   => '/kontak/?layanan=' . rawurlencode('kategori-' . $term_slug) . '#hubungi-form',
        'guide_title'      => 'Keunggulan Wahana UAV Rekayasa PT Karya Solusi Angkasa',
        'paragraphs'       => [
            'Seluruh unit pesawat nirawak dalam kategori ' . $label . ' dirancang, dirakit, dan diuji terbang di fasilitas manufaktur Full Drone Solutions di Sleman, Daerah Istimewa Yogyakarta.',
            'Dengan dukungan suku cadang asli siap pakai, software kendali FDS Station berbahasa Indonesia, serta sistem manajemen mutu perusahaan ISO 9001:2015 di PT Karya Solusi Angkasa, solusi FDS siap melayani sektor agribisnis, pertambangan, geospasial, serta instansi BUMN.',
        ],
        'faqs'             => [
            [
                'q' => 'Bagaimana cara mengajukan konsultasi teknis atau jadwal demo terbang unit?',
                'a' => 'Hubungi tim engineering PT Karya Solusi Angkasa melalui halaman Kontak Resmi untuk menentukan model UAV paling tepat sesuai target operasi dan kondisi lapangan Anda.',
            ],
        ],
        'seo_title' => "Drone {$label} — Solusi UAV Industri | FDS",
        'seo_desc'  => "Katalog resmi drone {$label} buatan PT Karya Solusi Angkasa (Full Drone Solutions) untuk aplikasi industri.",
    ];
}

function fds_get_kategori_drone_catalog_content(string $term_slug, ?object $term = null, bool $fresh = false): array {
    static $memo = [];
    if (!$fresh && isset($memo[$term_slug])) {
        return $memo[$term_slug];
    }

    if (!$term && $term_slug !== '') {
        $found_term = get_term_by('slug', $term_slug, 'kategori_drone');
        if ($found_term && !is_wp_error($found_term)) {
            $term = $found_term;
        }
    }

    $term_name = ($term && !empty($term->name)) ? fds_produk_clean_text($term->name) : '';
    $term_desc = ($term && !empty($term->description)) ? fds_produk_clean_text($term->description) : '';
    $defaults  = fds_get_kategori_drone_catalog_defaults($term_slug, $term_name, $term_desc);

    $opt_key = 'fds_kat_drone_content_' . sanitize_key($term_slug) . '_v1';
    $saved   = ($term_slug !== '') ? get_option($opt_key, []) : [];
    if (!is_array($saved)) {
        $saved = [];
    }

    $merged = array_merge($defaults, $saved);

    // If admin did not explicitly set a custom subtitle override in Konten Katalog, honor WP taxonomy description when present
    $saved_sub = trim((string) ($saved['subtitle'] ?? ''));
    if ($saved_sub === '' && $term_desc !== '') {
        $merged['subtitle'] = $term_desc;
    }

    foreach (['headline', 'subtitle', 'cta_kontak_label', 'guide_title', 'seo_title', 'seo_desc'] as $k) {
        $merged[$k] = fds_produk_clean_text($merged[$k] ?? '');
    }

    $merged['cta_kontak_url'] = trim((string) ($merged['cta_kontak_url'] ?? ''));
    if ($merged['cta_kontak_url'] === '') {
        $merged['cta_kontak_url'] = (string) $defaults['cta_kontak_url'];
    }

    $raw_paras   = is_array($merged['paragraphs'] ?? null) ? $merged['paragraphs'] : $defaults['paragraphs'];
    $clean_paras = [];
    foreach ($raw_paras as $p) {
        $cp = fds_produk_clean_text($p);
        if ($cp !== '') {
            $clean_paras[] = $cp;
        }
    }
    $merged['paragraphs'] = $clean_paras;

    $raw_faqs   = is_array($merged['faqs'] ?? null) ? $merged['faqs'] : $defaults['faqs'];
    $clean_faqs = [];
    foreach ($raw_faqs as $fq) {
        if (!is_array($fq)) {
            continue;
        }
        $q = fds_produk_clean_text($fq['q'] ?? '');
        $a = fds_produk_clean_text($fq['a'] ?? '');
        if ($q !== '' && $a !== '') {
            $clean_faqs[] = ['q' => $q, 'a' => $a];
        }
    }
    $merged['faqs'] = $clean_faqs;

    $memo[$term_slug] = $merged;
    return $merged;
}

// =========================================================================
// 3B. DEFAULTS & GETTERS — DETAIL DRONE UI & BANDINGKAN
// =========================================================================

function fds_get_drone_detail_ui_defaults(): array {
    return [
        'hero_cta1_text'     => 'Minta Penawaran',
        'hero_cta2_text'     => 'Bandingkan model',
        'specs_eyebrow'      => 'Spesifikasi',
        'specs_compare_link' => 'Bandingkan spesifikasi',
        'specs_heading'      => 'Direkayasa untuk <br>performa nyata.',
        'specs_desc_fallback'=> 'Setiap spesifikasi {drone_name} divalidasi melalui ratusan jam uji lapangan di berbagai kondisi cuaca dan jenis lahan di Indonesia.',
        'specs_empty_text'   => 'Spesifikasi detail akan segera diperbarui.',
        'uc_eyebrow'         => 'Aplikasi & Use-Cases',
        'uc_heading_tpl'     => '{drone_name} cocok untuk Anda.',
        'uc_fallback_items'  => [
            'Kelompok tani & Gapoktan — Titik temu antara kapasitas operasional dan efisiensi investasi.',
            'Operasional kebun komersial — Aplikasi semprot atau akuisisi data udara lebih terukur sesuai SOP.',
            'Korporasi & pengadaan instansi — Dukungan pelatihan operator, garansi resmi, dan demonstrasi unit.',
            'Suku cadang asli terjamin — Ketersediaan komponen cepat dari workshop Sleman, Yogyakarta.',
        ],
        'guide_eyebrow'      => 'Panduan Teknis & Tanya Jawab',
        'cta_heading_tpl'    => 'Siap mengoperasikan {drone_name}?',
        'cta_desc'           => 'Konsultasikan kebutuhan misi Anda dengan tim teknis PT Karya Solusi Angkasa (FDS). Demo unit dan konsultasi teknis tersedia di Yogyakarta.',
        'cta_primary_text'   => 'Hubungi Tim Sales',
        'cta_secondary_text' => 'Baca studi kasus',
        'cta_secondary_url'  => '/blog/',
    ];
}

function fds_get_drone_detail_ui(bool $fresh = false): array {
    static $memo = null;
    if (!$fresh && $memo !== null) {
        return $memo;
    }

    $defaults = fds_get_drone_detail_ui_defaults();
    $saved    = get_option('fds_drone_detail_ui_v1', []);
    if (!is_array($saved)) {
        $saved = [];
    }
    $merged = array_merge($defaults, $saved);

    foreach ([
        'hero_cta1_text', 'hero_cta2_text', 'specs_eyebrow', 'specs_compare_link',
        'specs_desc_fallback', 'specs_empty_text', 'uc_eyebrow', 'uc_heading_tpl',
        'guide_eyebrow', 'cta_heading_tpl', 'cta_desc', 'cta_primary_text', 'cta_secondary_text',
    ] as $k) {
        $merged[$k] = fds_produk_clean_text($merged[$k] ?? '');
    }

    // Allow harmless <br> line break on specs_heading
    $raw_hd = (string) ($merged['specs_heading'] ?? $defaults['specs_heading']);
    if (function_exists('fds_clean_string')) {
        $raw_hd = fds_clean_string($raw_hd);
    }
    if (function_exists('fds_scrub_legacy_text')) {
        $raw_hd = fds_scrub_legacy_text($raw_hd, true, true);
    }
    $merged['specs_heading'] = trim(wp_kses($raw_hd, ['br' => []])) ?: $defaults['specs_heading'];

    $merged['cta_secondary_url'] = trim((string) ($merged['cta_secondary_url'] ?? ''));
    if ($merged['cta_secondary_url'] === '') {
        $merged['cta_secondary_url'] = $defaults['cta_secondary_url'];
    }

    $raw_uc = is_array($merged['uc_fallback_items'] ?? null) ? $merged['uc_fallback_items'] : $defaults['uc_fallback_items'];
    $clean_uc = [];
    foreach ($raw_uc as $item) {
        $ci = fds_produk_clean_text($item);
        if ($ci !== '') {
            $clean_uc[] = $ci;
        }
    }
    $merged['uc_fallback_items'] = !empty($clean_uc) ? $clean_uc : $defaults['uc_fallback_items'];

    $memo = $merged;
    return $merged;
}

function fds_get_bandingkan_defaults(): array {
    $default_guide_html = <<<'HTML'
<h3>1. Menentukan Kapasitas Lini Pertanian Seri FERTO (5L – 50L)</h3>
<p>Untuk kebutuhan penyemprotan pupuk cair, pestisida, maupun penebaran granul padat pada komoditas padi, tebu, jagung, dan perkebunan kelapa sawit, seri <a href="/kategori-drone/agrikultur/">Drone Agrikultur FERTO</a> menawarkan enam varian kapasitas tangki. Model ringkas seperti FERTO 5 dan FERTO 10 unggul untuk lahan berterasering, bukit, dan mobilitas kelompok tani, sedangkan FERTO 15 (17 Liter), FERTO 22, FERTO 30, hingga FERTO 50 dirancang untuk produktivitas estate korporasi puluhan hektar per hari dengan dukungan radar Terrain Following.</p>
<h3>2. Pemetaan Area Luas vs Inspeksi Multirotor</h3>
<p>Dalam pekerjaan survei topografi tambang, pemetaan kadastral HGU, serta <a href="/kategori-drone/pemetaan-gis/">Pemetaan &amp; Survey LiDAR GIS</a>, wahana Fixed-Wing Hybrid VTOL seperti DELTAV menjadi standar untuk cakupan ribuan hektar berbekal jangkauan jelajah hingga 60 km. Sementara itu, platform modular seperti MULTIPURPOSE paling efektif untuk inspeksi jaringan transmisi listrik 150 kV, panel surya, dan kawasan industri yang memerlukan hovering presisi serta sensor optik zoom maupun kamera termal inframerah.</p>
<h3>3. Distribusi Logistik &amp; Standar Sertifikasi Pengadaan</h3>
<p>Untuk misi pengantaran cepat ke pulau terluar atau lokasi terisolir, lini <a href="/kategori-drone/kargo/">Drone Kargo &amp; Logistik</a> DELFRO menghadirkan rute otonom via FDS Station berbahasa Indonesia. Fasilitas PT Karya Solusi Angkasa di Sleman, Yogyakarta menerapkan manajemen mutu ISO 9001:2015, dengan lini drone agrikultur seri FERTO (5L–50L) bersertifikat TKDN + BMP 60,74% serta FERTO 15 dan FERTO 22 memenuhi standar nasional SNI 9199:2023 untuk pengadaan instansi pemerintah, BUMN, dan e-Katalog LKPP. Pelajari rekam jejak manufaktur kami di halaman <a href="/tentang-kami/">Tentang Kami</a> atau jadwalkan demo terbang resmi melalui <a href="/kontak/#hubungi-form">Kontak Enterprise FDS</a>.</p>
HTML;

    return [
        'hero_label'          => 'Komparasi Model UAV',
        'hero_title'          => 'Bandingkan Model Drone.',
        'hero_desc'           => 'Pilih hingga 2 model UAV untuk membandingkan spesifikasi teknis, performa terbang, dan kecocokan operasional lapangan secara berdampingan.',
        'sticky_slot1'        => 'Pilih Drone 1',
        'sticky_slot2'        => 'Pilih Drone 2',
        'sticky_vs'           => 'vs',
        'slot_pick_title'     => 'Pilih Model Drone',
        'slot_pick_sub'       => 'Klik untuk memilih produk yang ingin dibandingkan',
        'slot_pick_btn'       => 'Pilih Model',
        'slot_change_btn'     => 'Ganti model',
        'slot_remove_btn'     => 'Hapus',
        'slot_offer_btn'      => 'Minta Penawaran',
        'slot_detail_btn'     => 'Detail Produk',
        'specs_badge'         => 'Spesifikasi',
        'specs_title'         => 'Direkayasa untuk performa nyata.',
        'specs_desc'          => 'Tabel perbandingan data teknis resmi dari lembar spesifikasi masing-masing unit drone FDS.',
        'table_empty_title'   => 'Belum Ada Model yang Dipilih',
        'table_empty_desc'    => 'Silakan pilih model drone pada slot di atas untuk menampilkan lembar perbandingan spesifikasi.',
        'uc_badge'            => 'Aplikasi & Use-Cases',
        'uc_title'            => 'Untuk Siapa Model Ini Diciptakan.',
        'uc_desc'             => 'Perbandingan profil pengguna, jenis lahan, dan skenario misi yang paling optimal.',
        'uc_empty_model1'     => 'Model Pertama Belum Dipilih',
        'uc_empty_model2'     => 'Model Kedua Belum Dipilih',
        'stats_empty_model1'  => 'Keunggulan Model 1',
        'stats_empty_model2'  => 'Keunggulan Model 2',
        'stats_empty_text'    => 'Belum ada model dipilih',
        'guide_badge'         => 'Panduan Pemilihan Armada UAV',
        'guide_title'         => 'Panduan Memilih Armada Drone & Pengadaan B2B (TKDN & SNI)',
        'guide_lead'          => 'Memilih pesawat nirawak industrial membutuhkan kecocokan presisi antara kapasitas payload, luasan lahan atau koridor survei, efisiensi waktu terbang, serta kepastian layanan purna jual dan sertifikasi pengadaan di Indonesia.',
        'guide_html'          => trim($default_guide_html),
        'cta_title'           => 'Siap menentukan pilihan armada Anda?',
        'cta_desc'            => 'Konsultasikan kebutuhan operasional dan uji terbang (demo unit) bersama tim teknis PT Karya Solusi Angkasa (FDS) di Yogyakarta.',
        'cta_primary_text'    => 'Hubungi Tim Sales FDS',
        'cta_primary_url'     => '/kontak/#hubungi-form',
        'cta_secondary_text'  => 'Baca studi kasus operasional',
        'cta_secondary_url'   => '/blog/',
        'modal_eyebrow'       => 'Katalog Produk',
        'modal_title'         => 'Pilih Model Drone',
        'modal_sub'           => 'Pilih produk drone yang ingin dibandingkan.',
        'modal_all_tab'       => 'Semua',
        'modal_empty_title'   => 'Tidak Ada Drone Lain dalam Kategori Ini',
        'modal_empty_desc'    => 'Semua model drone dalam kategori ini sudah dipilih atau belum ada model pembanding lain yang terdaftar.',
        'seo_title'           => 'Bandingkan Spesifikasi Drone — Komparasi Lini UAV FDS',
        'seo_desc'            => 'Bandingkan spesifikasi teknis drone sprayer FERTO (TKDN 60,74%), pemetaan VTOL DELTAV, MULTIPURPOSE, dan kargo DELFRO buatan PT Karya Solusi Angkasa.',
    ];
}

function fds_get_bandingkan_content(bool $fresh = false): array {
    static $memo = null;
    if (!$fresh && $memo !== null) {
        return $memo;
    }

    $defaults = fds_get_bandingkan_defaults();
    $saved    = get_option('fds_bandingkan_content_v1', []);
    if (!is_array($saved)) {
        $saved = [];
    }
    $merged = array_merge($defaults, $saved);

    $plain_keys = [
        'hero_label', 'hero_title', 'hero_desc',
        'sticky_slot1', 'sticky_slot2', 'sticky_vs',
        'slot_pick_title', 'slot_pick_sub', 'slot_pick_btn', 'slot_change_btn', 'slot_remove_btn', 'slot_offer_btn', 'slot_detail_btn',
        'specs_badge', 'specs_title', 'specs_desc', 'table_empty_title', 'table_empty_desc',
        'uc_badge', 'uc_title', 'uc_desc', 'uc_empty_model1', 'uc_empty_model2',
        'stats_empty_model1', 'stats_empty_model2', 'stats_empty_text',
        'guide_badge', 'guide_title', 'guide_lead',
        'cta_title', 'cta_desc', 'cta_primary_text', 'cta_secondary_text',
        'modal_eyebrow', 'modal_title', 'modal_sub', 'modal_all_tab', 'modal_empty_title', 'modal_empty_desc',
        'seo_title', 'seo_desc',
    ];
    foreach ($plain_keys as $k) {
        $merged[$k] = fds_produk_clean_text($merged[$k] ?? '');
    }

    foreach (['cta_primary_url', 'cta_secondary_url'] as $uk) {
        $merged[$uk] = trim((string) ($merged[$uk] ?? ''));
        if ($merged[$uk] === '') {
            $merged[$uk] = $defaults[$uk];
        }
    }

    $raw_guide = (string) ($merged['guide_html'] ?? $defaults['guide_html']);
    if (function_exists('fds_clean_string')) {
        $raw_guide = fds_clean_string($raw_guide);
    }
    if (function_exists('fds_scrub_legacy_text')) {
        $raw_guide = fds_scrub_legacy_text($raw_guide, true, true);
    }
    $merged['guide_html'] = trim(wp_kses_post($raw_guide)) ?: $defaults['guide_html'];

    $memo = $merged;
    return $merged;
}

// =========================================================================
// 4. WP ADMIN SUBMENU & PAGE EDITOR METABOX SHORTCUT (/produk/)
// =========================================================================

add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=drone',
        'Konten Katalog & Kategori Produk',
        'Konten Katalog',
        'manage_options',
        'fds-produk-content',
        __NAMESPACE__ . '\\render_produk_content_admin_page'
    );
}, 30);

add_action('add_meta_boxes_page', function ($post) {
    $slug = $post ? ($post->post_name ?? '') : '';
    if (in_array($slug, ['produk', 'bandingkan'], true)) {
        add_meta_box(
            'fds_produk_editor_shortcut',
            'Pengelolaan Konten Katalog & Komparasi FDS',
            __NAMESPACE__ . '\\render_produk_page_shortcut_metabox',
            'page',
            'side',
            'high',
            ['slug' => $slug]
        );
    }
});

function render_produk_page_shortcut_metabox($post = null, $box = []): void {
    $slug = is_array($box) && !empty($box['args']['slug']) ? (string) $box['args']['slug'] : 'produk';
    $tab  = ($slug === 'bandingkan') ? 'bandingkan' : 'hub';
    $url  = admin_url('edit.php?post_type=drone&page=fds-produk-content&tab=' . rawurlencode($tab));
    ?>
    <p style="margin:0 0 10px;font-size:12.5px;color:#334155;line-height:1.5;">
        Seluruh teks pengantar, header section, panduan pengadaan, FAQ, dan CTA halaman <strong>/<?php echo esc_html($slug); ?>/</strong> dikelola di <strong>Konten Katalog</strong> (terpisah dari data unit yang tersinkronisasi 1:1 dari <em>Produk Drone</em>).
    </p>
    <a href="<?php echo esc_url($url); ?>" class="button button-primary" style="background:#0f808a;border-color:#0f808a;width:100%;text-align:center;font-weight:600;">
        Buka Editor Konten &rarr;
    </a>
    <?php
}

// =========================================================================
// 5. ADMIN FORM PROCESSING (NON-DESTRUCTIVE + PER-TAB RESET)
// =========================================================================

function fds_get_produk_admin_tabs(): array {
    $tabs = [
        'hub' => [
            'kind'   => 'hub',
            'slug'   => 'produk',
            'label'  => 'Hub Katalog (/produk/)',
            'option' => 'fds_produk_catalog_content_v1',
            'url'    => home_url('/produk/'),
        ],
        'kat-agrikultur' => [
            'kind'   => 'kat',
            'slug'   => 'agrikultur',
            'label'  => 'Kategori: Agrikultur',
            'option' => 'fds_kat_drone_content_agrikultur_v1',
            'url'    => home_url('/kategori-drone/agrikultur/'),
        ],
        'kat-pemetaan-gis' => [
            'kind'   => 'kat',
            'slug'   => 'pemetaan-gis',
            'label'  => 'Kategori: Pemetaan & GIS',
            'option' => 'fds_kat_drone_content_pemetaan_gis_v1',
            'url'    => home_url('/kategori-drone/pemetaan-gis/'),
        ],
        'kat-kargo' => [
            'kind'   => 'kat',
            'slug'   => 'kargo',
            'label'  => 'Kategori: Kargo',
            'option' => 'fds_kat_drone_content_kargo_v1',
            'url'    => home_url('/kategori-drone/kargo/'),
        ],
    ];

    $terms = get_terms([
        'taxonomy'   => 'kategori_drone',
        'hide_empty' => false,
        'orderby'    => 'term_id',
        'order'      => 'ASC',
    ]);
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $t) {
            $ts = (string) $t->slug;
            if ($ts === '' || stripos($ts, 'rebo') !== false || isset($tabs['kat-' . $ts])) {
                continue;
            }
            $t_link = get_term_link($t);
            $tabs['kat-' . $ts] = [
                'kind'   => 'kat',
                'slug'   => $ts,
                'label'  => 'Kategori: ' . fds_produk_clean_text($t->name),
                'option' => 'fds_kat_drone_content_' . sanitize_key($ts) . '_v1',
                'url'    => (!is_wp_error($t_link) && is_string($t_link)) ? $t_link : home_url('/produk/'),
            ];
        }
    }

    $tabs['kat-ui'] = [
        'kind'   => 'kat-ui',
        'slug'   => 'kat-ui',
        'label'  => 'Teks Bersama & Sidebar Kategori',
        'option' => 'fds_kat_drone_ui_v1',
        'url'    => home_url('/kategori-drone/agrikultur/'),
    ];

    $tabs['detail-ui'] = [
        'kind'   => 'detail-ui',
        'slug'   => 'detail-ui',
        'label'  => 'Teks UI Detail Drone',
        'option' => 'fds_drone_detail_ui_v1',
        'url'    => home_url('/drone/ferto-15l/'),
    ];

    $tabs['bandingkan'] = [
        'kind'   => 'bandingkan',
        'slug'   => 'bandingkan',
        'label'  => 'Halaman Bandingkan (/bandingkan/)',
        'option' => 'fds_bandingkan_content_v1',
        'url'    => home_url('/bandingkan/'),
    ];

    return $tabs;
}

function fds_handle_produk_admin_post(string $active_tab, array $tabs): array {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return ['message' => '', 'error' => ''];
    }
    if (!current_user_can('manage_options')) {
        return ['message' => '', 'error' => 'Akses ditolak.'];
    }
    if (!isset($_POST['fds_produk_nonce']) || !check_admin_referer('fds_produk_content_nonce', 'fds_produk_nonce')) {
        return ['message' => '', 'error' => 'Token keamanan tidak valid. Muat ulang halaman dan coba lagi.'];
    }

    $post_tab = sanitize_key((string) wp_unslash($_POST['fds_produk_tab'] ?? $active_tab));
    if (!isset($tabs[$post_tab])) {
        return ['message' => '', 'error' => 'Tab tujuan tidak dikenal.'];
    }
    $meta = $tabs[$post_tab];

    if (isset($_POST['fds_produk_reset'])) {
        delete_option($meta['option']);
        fds_flush_produk_catalog_cache();
        return ['message' => 'Konten "' . $meta['label'] . '" telah dikembalikan ke default bawaan tema.', 'error' => ''];
    }

    if (!isset($_POST['fds_produk_save'])) {
        return ['message' => '', 'error' => ''];
    }

    $unslash_txt = static function ($key): string {
        return sanitize_text_field(wp_unslash((string) ($_POST[$key] ?? '')));
    };
    $unslash_area = static function ($key): string {
        return sanitize_textarea_field(wp_unslash((string) ($_POST[$key] ?? '')));
    };

    if ($meta['kind'] === 'hub') {
        $highlights = [];
        $hl_titles  = $_POST['hl_title'] ?? [];
        $hl_descs   = $_POST['hl_desc'] ?? [];
        if (is_array($hl_titles)) {
            foreach ($hl_titles as $idx => $t_raw) {
                $t = sanitize_text_field(wp_unslash((string) $t_raw));
                $d = wp_kses(wp_unslash((string) ($hl_descs[$idx] ?? '')), ['strong' => []]);
                if ($t !== '' || trim($d) !== '') {
                    $highlights[] = ['title' => $t, 'desc' => trim($d)];
                }
            }
        }

        $cols_raw = sanitize_text_field(wp_unslash((string) ($_POST['matrix_columns'] ?? '')));
        $cols     = array_values(array_filter(array_map('trim', explode('|', $cols_raw)), fn($v) => $v !== ''));
        $col_cnt  = max(1, count($cols));

        $rows      = [];
        $rows_raw  = sanitize_textarea_field(wp_unslash((string) ($_POST['matrix_rows'] ?? '')));
        $row_lines = preg_split('/\R/u', $rows_raw) ?: [];
        foreach ($row_lines as $rline) {
            $trimmed = trim($rline);
            if ($trimmed === '') {
                continue;
            }
            $cells = array_map('trim', explode('|', $trimmed));
            if (count($cells) < $col_cnt) {
                $cells = array_pad($cells, $col_cnt, '');
            } elseif (count($cells) > $col_cnt) {
                $cells = array_slice($cells, 0, $col_cnt);
            }
            $rows[] = $cells;
        }

        $faqs   = [];
        $faq_qs = $_POST['faq_q'] ?? [];
        $faq_as = $_POST['faq_a'] ?? [];
        if (is_array($faq_qs)) {
            foreach ($faq_qs as $idx => $q_raw) {
                $q = sanitize_text_field(wp_unslash((string) $q_raw));
                $a = sanitize_textarea_field(wp_unslash((string) ($faq_as[$idx] ?? '')));
                if ($q !== '' && $a !== '') {
                    $faqs[] = ['q' => $q, 'a' => $a];
                }
            }
        }

        $save_data = [
            'hero_badge'             => $unslash_txt('hero_badge'),
            'hero_title'             => $unslash_txt('hero_title'),
            'hero_desc'              => $unslash_area('hero_desc'),
            'hero_cta1_text'         => $unslash_txt('hero_cta1_text'),
            'hero_cta1_url'          => fds_produk_sanitize_url($_POST['hero_cta1_url'] ?? ''),
            'hero_cta2_text'         => $unslash_txt('hero_cta2_text'),
            'hero_cta2_url'          => fds_produk_sanitize_url($_POST['hero_cta2_url'] ?? ''),
            'nav_agri_label'         => $unslash_txt('nav_agri_label'),
            'nav_pemetaan_label'     => $unslash_txt('nav_pemetaan_label'),
            'nav_kargo_label'        => $unslash_txt('nav_kargo_label'),
            'highlights'             => $highlights,
            'cat_agri_badge'         => $unslash_txt('cat_agri_badge'),
            'cat_agri_title'         => $unslash_txt('cat_agri_title'),
            'cat_agri_desc'          => $unslash_area('cat_agri_desc'),
            'cat_agri_link_text'     => $unslash_txt('cat_agri_link_text'),
            'cat_pemetaan_badge'     => $unslash_txt('cat_pemetaan_badge'),
            'cat_pemetaan_title'     => $unslash_txt('cat_pemetaan_title'),
            'cat_pemetaan_desc'      => $unslash_area('cat_pemetaan_desc'),
            'cat_pemetaan_link_text' => $unslash_txt('cat_pemetaan_link_text'),
            'cat_kargo_badge'        => $unslash_txt('cat_kargo_badge'),
            'cat_kargo_title'        => $unslash_txt('cat_kargo_title'),
            'cat_kargo_desc'         => $unslash_area('cat_kargo_desc'),
            'cat_kargo_link_text'    => $unslash_txt('cat_kargo_link_text'),
            'matrix_title'           => $unslash_txt('matrix_title'),
            'matrix_desc'            => $unslash_area('matrix_desc'),
            'matrix_cta_text'        => $unslash_txt('matrix_cta_text'),
            'matrix_cta_url'         => fds_produk_sanitize_url($_POST['matrix_cta_url'] ?? ''),
            'matrix_columns'         => $cols,
            'matrix_rows'            => $rows,
            'faq_title'              => $unslash_txt('faq_title'),
            'faq_subtitle'           => $unslash_area('faq_subtitle'),
            'faqs'                   => $faqs,
            'cta_label'              => $unslash_txt('cta_label'),
            'cta_title'              => $unslash_txt('cta_title'),
            'cta_desc'               => $unslash_area('cta_desc'),
            'cta_primary_text'       => $unslash_txt('cta_primary_text'),
            'cta_primary_url'        => fds_produk_sanitize_url($_POST['cta_primary_url'] ?? ''),
            'cta_secondary_text'     => $unslash_txt('cta_secondary_text'),
            'seo_title'              => $unslash_txt('seo_title'),
            'seo_desc'               => $unslash_area('seo_desc'),
            'schema_name'            => $unslash_txt('schema_name'),
            'schema_desc'            => $unslash_area('schema_desc'),
        ];

        update_option($meta['option'], $save_data, false);
    } elseif ($meta['kind'] === 'kat') {
        $raw_paras = sanitize_textarea_field(wp_unslash((string) ($_POST['paragraphs'] ?? '')));
        $para_list = preg_split("/\R{2,}/u", $raw_paras) ?: [];
        if (count($para_list) <= 1 && str_contains($raw_paras, "\n")) {
            $para_list = preg_split('/\R/u', $raw_paras) ?: [];
        }
        $paragraphs = [];
        foreach ($para_list as $p) {
            $cp = trim(preg_replace('/\s+/', ' ', $p));
            if ($cp !== '') {
                $paragraphs[] = $cp;
            }
        }

        $faqs   = [];
        $faq_qs = $_POST['faq_q'] ?? [];
        $faq_as = $_POST['faq_a'] ?? [];
        if (is_array($faq_qs)) {
            foreach ($faq_qs as $idx => $q_raw) {
                $q = sanitize_text_field(wp_unslash((string) $q_raw));
                $a = sanitize_textarea_field(wp_unslash((string) ($faq_as[$idx] ?? '')));
                if ($q !== '' && $a !== '') {
                    $faqs[] = ['q' => $q, 'a' => $a];
                }
            }
        }

        $save_data = [
            'headline'         => $unslash_txt('headline'),
            'subtitle'         => $unslash_area('subtitle'),
            'cta_kontak_label' => $unslash_txt('cta_kontak_label'),
            'cta_kontak_url'   => fds_produk_sanitize_url($_POST['cta_kontak_url'] ?? ''),
            'guide_title'      => $unslash_txt('guide_title'),
            'paragraphs'       => $paragraphs,
            'faqs'             => $faqs,
            'seo_title'        => $unslash_txt('seo_title'),
            'seo_desc'         => $unslash_area('seo_desc'),
        ];

        update_option($meta['option'], $save_data, false);
    } elseif ($meta['kind'] === 'kat-ui') {
        $links  = [];
        $l_lbls = $_POST['sl_label'] ?? [];
        $l_urls = $_POST['sl_url'] ?? [];
        if (is_array($l_lbls)) {
            foreach ($l_lbls as $idx => $lbl_raw) {
                $lbl = sanitize_text_field(wp_unslash((string) $lbl_raw));
                $url = fds_produk_sanitize_url($l_urls[$idx] ?? '');
                if ($lbl !== '' && $url !== '') {
                    $links[] = ['label' => $lbl, 'url' => $url];
                }
            }
        }

        $save_data = [
            'all_products_label' => $unslash_txt('all_products_label'),
            'hero_compare_text'  => $unslash_txt('hero_compare_text'),
            'hero_compare_url'   => fds_produk_sanitize_url($_POST['hero_compare_url'] ?? ''),
            'empty_text_prefix'  => $unslash_txt('empty_text_prefix'),
            'empty_link_text'    => $unslash_txt('empty_link_text'),
            'sidebar_title'      => $unslash_txt('sidebar_title'),
            'sidebar_desc'       => $unslash_area('sidebar_desc'),
            'sidebar_links'      => $links,
            'faq_title_prefix'   => $unslash_txt('faq_title_prefix'),
            'faq_subtitle'       => $unslash_area('faq_subtitle'),
        ];

        update_option($meta['option'], $save_data, false);
    } elseif ($meta['kind'] === 'detail-ui') {
        $raw_uc = sanitize_textarea_field(wp_unslash((string) ($_POST['uc_fallback_items'] ?? '')));
        $uc_arr = array_values(array_filter(array_map('trim', preg_split('/\R/u', $raw_uc) ?: []), fn($v) => $v !== ''));
        $raw_hd = wp_kses(wp_unslash((string) ($_POST['specs_heading'] ?? '')), ['br' => []]);

        $save_data = [
            'hero_cta1_text'      => $unslash_txt('hero_cta1_text'),
            'hero_cta2_text'      => $unslash_txt('hero_cta2_text'),
            'specs_eyebrow'       => $unslash_txt('specs_eyebrow'),
            'specs_compare_link'  => $unslash_txt('specs_compare_link'),
            'specs_heading'       => trim($raw_hd),
            'specs_desc_fallback' => $unslash_area('specs_desc_fallback'),
            'specs_empty_text'    => $unslash_txt('specs_empty_text'),
            'uc_eyebrow'          => $unslash_txt('uc_eyebrow'),
            'uc_heading_tpl'      => $unslash_txt('uc_heading_tpl'),
            'uc_fallback_items'   => $uc_arr,
            'guide_eyebrow'       => $unslash_txt('guide_eyebrow'),
            'cta_heading_tpl'     => $unslash_txt('cta_heading_tpl'),
            'cta_desc'            => $unslash_area('cta_desc'),
            'cta_primary_text'    => $unslash_txt('cta_primary_text'),
            'cta_secondary_text'  => $unslash_txt('cta_secondary_text'),
            'cta_secondary_url'   => fds_produk_sanitize_url($_POST['cta_secondary_url'] ?? ''),
        ];

        update_option($meta['option'], $save_data, false);
    } elseif ($meta['kind'] === 'bandingkan') {
        $save_data = [
            'hero_label'          => $unslash_txt('hero_label'),
            'hero_title'          => $unslash_txt('hero_title'),
            'hero_desc'           => $unslash_area('hero_desc'),
            'sticky_slot1'        => $unslash_txt('sticky_slot1'),
            'sticky_slot2'        => $unslash_txt('sticky_slot2'),
            'sticky_vs'           => $unslash_txt('sticky_vs'),
            'slot_pick_title'     => $unslash_txt('slot_pick_title'),
            'slot_pick_sub'       => $unslash_txt('slot_pick_sub'),
            'slot_pick_btn'       => $unslash_txt('slot_pick_btn'),
            'slot_change_btn'     => $unslash_txt('slot_change_btn'),
            'slot_remove_btn'     => $unslash_txt('slot_remove_btn'),
            'slot_offer_btn'      => $unslash_txt('slot_offer_btn'),
            'slot_detail_btn'     => $unslash_txt('slot_detail_btn'),
            'specs_badge'         => $unslash_txt('specs_badge'),
            'specs_title'         => $unslash_txt('specs_title'),
            'specs_desc'          => $unslash_area('specs_desc'),
            'table_empty_title'   => $unslash_txt('table_empty_title'),
            'table_empty_desc'    => $unslash_area('table_empty_desc'),
            'uc_badge'            => $unslash_txt('uc_badge'),
            'uc_title'            => $unslash_txt('uc_title'),
            'uc_desc'             => $unslash_area('uc_desc'),
            'uc_empty_model1'     => $unslash_txt('uc_empty_model1'),
            'uc_empty_model2'     => $unslash_txt('uc_empty_model2'),
            'stats_empty_model1'  => $unslash_txt('stats_empty_model1'),
            'stats_empty_model2'  => $unslash_txt('stats_empty_model2'),
            'stats_empty_text'    => $unslash_txt('stats_empty_text'),
            'guide_badge'         => $unslash_txt('guide_badge'),
            'guide_title'         => $unslash_txt('guide_title'),
            'guide_lead'          => $unslash_area('guide_lead'),
            'guide_html'          => trim(wp_kses_post(wp_unslash((string) ($_POST['guide_html'] ?? '')))),
            'cta_title'           => $unslash_txt('cta_title'),
            'cta_desc'            => $unslash_area('cta_desc'),
            'cta_primary_text'    => $unslash_txt('cta_primary_text'),
            'cta_primary_url'     => fds_produk_sanitize_url($_POST['cta_primary_url'] ?? ''),
            'cta_secondary_text'  => $unslash_txt('cta_secondary_text'),
            'cta_secondary_url'   => fds_produk_sanitize_url($_POST['cta_secondary_url'] ?? ''),
            'modal_eyebrow'       => $unslash_txt('modal_eyebrow'),
            'modal_title'         => $unslash_txt('modal_title'),
            'modal_sub'           => $unslash_txt('modal_sub'),
            'modal_all_tab'       => $unslash_txt('modal_all_tab'),
            'modal_empty_title'   => $unslash_txt('modal_empty_title'),
            'modal_empty_desc'    => $unslash_area('modal_empty_desc'),
            'seo_title'           => $unslash_txt('seo_title'),
            'seo_desc'            => $unslash_area('seo_desc'),
        ];

        update_option($meta['option'], $save_data, false);
    }

    fds_flush_produk_catalog_cache();
    return ['message' => 'Perubahan "' . $meta['label'] . '" berhasil disimpan dan disinkronkan.', 'error' => ''];
}

// =========================================================================
// 6. ADMIN PAGE RENDERER
// =========================================================================

function render_produk_content_admin_page(): void {
    if (!current_user_can('manage_options')) {
        return;
    }

    $tabs       = fds_get_produk_admin_tabs();
    $active_tab = isset($_GET['tab']) ? sanitize_key((string) wp_unslash($_GET['tab'])) : 'hub';
    if (!isset($tabs[$active_tab])) {
        $active_tab = 'hub';
    }

    $notice = fds_handle_produk_admin_post($active_tab, $tabs);
    $meta   = $tabs[$active_tab];

    $has_override = get_option($meta['option'], null) !== null;
    if ($meta['kind'] === 'hub') {
        $data = fds_get_produk_catalog_content(true);
    } elseif ($meta['kind'] === 'kat') {
        $data = fds_get_kategori_drone_catalog_content($meta['slug'], null, true);
    } elseif ($meta['kind'] === 'detail-ui') {
        $data = fds_get_drone_detail_ui(true);
    } elseif ($meta['kind'] === 'bandingkan') {
        $data = fds_get_bandingkan_content(true);
    } else {
        $data = fds_get_kategori_drone_ui_content(true);
    }
    ?>
    <style>
        .fds-pk-wrap { max-width: 1120px; margin: 20px 20px 90px 0; }
        .fds-pk-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
        .fds-pk-tabs { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 18px; border-bottom: 1px solid #dcdcde; padding-bottom: 12px; }
        .fds-pk-tab { text-decoration: none; padding: 7px 13px; border-radius: 6px; font-size: 12.5px; font-weight: 600; color: #334155; background: #fff; border: 1px solid #cbd5e1; transition: all .15s; }
        .fds-pk-tab:hover { border-color: #0f808a; color: #0f808a; }
        .fds-pk-tab.is-active { background: #0f808a; border-color: #0f808a; color: #fff; }
        .fds-pk-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 18px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
        .fds-pk-card h3 { margin: 0 0 14px; font-size: 14.5px; font-weight: 700; color: #0f172a; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9; }
        .fds-pk-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 782px) { .fds-pk-grid2 { grid-template-columns: 1fr; } }
        .fds-pk-field { margin-bottom: 12px; }
        .fds-pk-field label { display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 4px; }
        .fds-pk-field input[type="text"], .fds-pk-field textarea { width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 5px; font-size: 13px; color: #1e293b; box-sizing: border-box; }
        .fds-pk-field input:focus, .fds-pk-field textarea:focus { border-color: #0f808a; outline: none; box-shadow: 0 0 0 2px rgba(15,128,138,.15); }
        .fds-pk-hint { font-size: 11px; color: #64748b; margin-top: 4px; display: block; line-height: 1.45; }
        .fds-pk-row { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-bottom: 10px; }
        .fds-pk-row-ctrl { display: flex; justify-content: flex-end; gap: 6px; margin-bottom: 8px; }
        .fds-pk-sticky { position: fixed; bottom: 0; left: 160px; right: 0; background: #fff; border-top: 1px solid #e2e8f0; padding: 12px 24px; display: flex; align-items: center; justify-content: space-between; z-index: 999; box-shadow: 0 -2px 10px rgba(0,0,0,0.05); }
        @media (max-width: 960px) { .fds-pk-sticky { left: 36px; } }
    </style>

    <div class="fds-pk-wrap">
        <div class="fds-pk-head">
            <div>
                <h1 style="margin:0;font-size:22px;font-weight:700;color:#0f172a;">Konten Katalog &amp; Kategori Produk Drone</h1>
                <p style="margin:4px 0 0;color:#475569;font-size:13px;">
                    Kartu unit produk otomatis mengambil 100% foto Hero &amp; spesifikasi dari <strong>Produk Drone</strong>. Panel ini mengatur semua narasi, matriks perbandingan, FAQ, dan CTA di halaman <code>/produk/</code> &amp; <code>/kategori-drone/*</code>.
                </p>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <a href="<?php echo esc_url($meta['url']); ?>" target="_blank" class="button">Lihat Halaman Live &nearr;</a>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=drone')); ?>" class="button">Kelola Produk Drone &rarr;</a>
            </div>
        </div>

        <?php if ($notice['message'] !== ''): ?>
            <div class="notice notice-success is-dismissible" style="margin:0 0 14px;"><p><?php echo esc_html($notice['message']); ?></p></div>
        <?php endif; ?>
        <?php if ($notice['error'] !== ''): ?>
            <div class="notice notice-error is-dismissible" style="margin:0 0 14px;"><p><?php echo esc_html($notice['error']); ?></p></div>
        <?php endif; ?>

        <nav class="fds-pk-tabs" aria-label="Tab Katalog Produk">
            <?php foreach ($tabs as $tkey => $tmeta): ?>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=drone&page=fds-produk-content&tab=' . rawurlencode($tkey))); ?>"
                   class="fds-pk-tab <?php echo $tkey === $active_tab ? 'is-active' : ''; ?>">
                    <?php echo esc_html($tmeta['label']); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <form method="post" action="">
            <?php wp_nonce_field('fds_produk_content_nonce', 'fds_produk_nonce'); ?>
            <input type="hidden" name="fds_produk_tab" value="<?php echo esc_attr($active_tab); ?>">

            <?php if ($meta['kind'] === 'hub'): ?>
                <!-- 1. HERO KATALOG -->
                <div class="fds-pk-card">
                    <h3>1. Hero Katalog (/produk/)</h3>
                    <div class="fds-pk-field">
                        <label>Badge Label Atas</label>
                        <input type="text" name="hero_badge" value="<?php echo esc_attr($data['hero_badge']); ?>">
                    </div>
                    <div class="fds-pk-field">
                        <label>Judul Utama (H1)</label>
                        <input type="text" name="hero_title" value="<?php echo esc_attr($data['hero_title']); ?>">
                    </div>
                    <div class="fds-pk-field">
                        <label>Deskripsi Pengantar Hero</label>
                        <textarea name="hero_desc" rows="2"><?php echo esc_textarea($data['hero_desc']); ?></textarea>
                    </div>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Teks Tombol CTA Utama</label>
                            <input type="text" name="hero_cta1_text" value="<?php echo esc_attr($data['hero_cta1_text']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>URL Tombol CTA Utama</label>
                            <input type="text" name="hero_cta1_url" value="<?php echo esc_attr($data['hero_cta1_url']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Teks Tombol CTA Sekunder</label>
                            <input type="text" name="hero_cta2_text" value="<?php echo esc_attr($data['hero_cta2_text']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>URL Tombol CTA Sekunder</label>
                            <input type="text" name="hero_cta2_url" value="<?php echo esc_attr($data['hero_cta2_url']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-grid2" style="grid-template-columns:repeat(3,1fr);">
                        <div class="fds-pk-field">
                            <label>Label Anchor Nav: Agrikultur</label>
                            <input type="text" name="nav_agri_label" value="<?php echo esc_attr($data['nav_agri_label']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Label Anchor Nav: Pemetaan</label>
                            <input type="text" name="nav_pemetaan_label" value="<?php echo esc_attr($data['nav_pemetaan_label']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Label Anchor Nav: Kargo</label>
                            <input type="text" name="nav_kargo_label" value="<?php echo esc_attr($data['nav_kargo_label']); ?>">
                        </div>
                    </div>
                </div>

                <!-- 2. STANDAR MUTU & LINGKUP SERTIFIKASI -->
                <div class="fds-pk-card">
                    <h3>2. Bar Highlight Standar Mutu &amp; Sertifikasi</h3>
                    <div id="pk-hl-list">
                        <?php foreach ((array) $data['highlights'] as $hl): ?>
                            <div class="fds-pk-row">
                                <div class="fds-pk-row-ctrl">
                                    <button type="button" class="button button-small" onclick="fdsPkMoveRow(this,-1)">&uarr;</button>
                                    <button type="button" class="button button-small" onclick="fdsPkMoveRow(this,1)">&darr;</button>
                                    <button type="button" class="button button-small button-link-delete" onclick="fdsPkRemoveRow(this)">Hapus</button>
                                </div>
                                <div class="fds-pk-field">
                                    <label>Judul Highlight</label>
                                    <input type="text" name="hl_title[]" value="<?php echo esc_attr($hl['title'] ?? ''); ?>">
                                </div>
                                <div class="fds-pk-field" style="margin-bottom:0;">
                                    <label>Penjelasan (Mendukung tag &lt;strong&gt;...&lt;/strong&gt;)</label>
                                    <textarea name="hl_desc[]" rows="2"><?php echo esc_textarea($hl['desc'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="button" onclick="fdsPkAddHighlight()">+ Tambah Highlight Sertifikasi</button>
                </div>

                <!-- 3. HEADER SECTION MASING-MASING KATEGORI -->
                <div class="fds-pk-card">
                    <h3>3. Header Section Kategori (Di Atas Grid Kartu Drone)</h3>
                    <?php
                    $cat_groups = [
                        'Agrikultur (Seri FERTO)'       => 'cat_agri',
                        'Pemetaan & Inspeksi (GIS)'     => 'cat_pemetaan',
                        'Kargo Logistik (DELFRO)'       => 'cat_kargo',
                    ];
                    foreach ($cat_groups as $gtitle => $prefix):
                    ?>
                        <div class="fds-pk-row">
                            <strong style="display:block;font-size:12.5px;color:#0f808a;margin-bottom:8px;"><?php echo esc_html($gtitle); ?></strong>
                            <div class="fds-pk-grid2">
                                <div class="fds-pk-field">
                                    <label>Label Eyebrow</label>
                                    <input type="text" name="<?php echo esc_attr($prefix . '_badge'); ?>" value="<?php echo esc_attr($data[$prefix . '_badge'] ?? ''); ?>">
                                </div>
                                <div class="fds-pk-field">
                                    <label>Teks Tautan Arsip Kategori</label>
                                    <input type="text" name="<?php echo esc_attr($prefix . '_link_text'); ?>" value="<?php echo esc_attr($data[$prefix . '_link_text'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="fds-pk-field">
                                <label>Judul Section (H2)</label>
                                <input type="text" name="<?php echo esc_attr($prefix . '_title'); ?>" value="<?php echo esc_attr($data[$prefix . '_title'] ?? ''); ?>">
                            </div>
                            <div class="fds-pk-field" style="margin-bottom:0;">
                                <label>Deskripsi Section</label>
                                <textarea name="<?php echo esc_attr($prefix . '_desc'); ?>" rows="2"><?php echo esc_textarea($data[$prefix . '_desc'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- 4. MATRIKS RINGKAS SPESIFIKASI -->
                <div class="fds-pk-card">
                    <h3>4. Header &amp; Kolom Matriks Ringkasan Spesifikasi (/produk/)</h3>
                    <div class="fds-pk-field">
                        <label>Judul Matriks</label>
                        <input type="text" name="matrix_title" value="<?php echo esc_attr($data['matrix_title']); ?>">
                    </div>
                    <div class="fds-pk-field">
                        <label>Subjudul Matriks</label>
                        <textarea name="matrix_desc" rows="2"><?php echo esc_textarea($data['matrix_desc']); ?></textarea>
                    </div>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Teks Tombol Bandingkan</label>
                            <input type="text" name="matrix_cta_text" value="<?php echo esc_attr($data['matrix_cta_text']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>URL Tombol Bandingkan</label>
                            <input type="text" name="matrix_cta_url" value="<?php echo esc_attr($data['matrix_cta_url']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Nama Kolom Tabel (pisahkan dengan tanda <code>|</code>)</label>
                        <input type="text" name="matrix_columns" value="<?php echo esc_attr(implode(' | ', (array) $data['matrix_columns'])); ?>">
                    </div>
                    <div class="fds-pk-field" style="margin-bottom:0;">
                        <label>Catatan Baris Matriks (Otomatis 1:1 dari Detail Produk Drone)</label>
                        <p class="fds-pk-hint" style="margin-top:0;font-size:12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:10px 12px;color:#334155;">
                            Isi tabel ringkasan di halaman <strong>/produk/</strong> otomatis disinkronkan 1:1 secara real-time per kategori (pilihan tab Agrikultur, Pemetaan &amp; GIS, dan Kargo) dari data masing-masing unit di menu <strong>Produk Drone</strong> (klik baris mana saja langsung mengarah ke halaman detail unit).
                        </p>
                        <input type="hidden" name="matrix_rows" value="<?php
                            $row_strs = array_map(fn($r) => implode(' | ', (array) $r), (array) $data['matrix_rows']);
                            echo esc_attr(implode("\n", $row_strs));
                        ?>">
                    </div>
                </div>

                <!-- 5. FAQ & CTA BAND -->
                <div class="fds-pk-card">
                    <h3>5. Tanya Jawab (FAQ &amp; FAQPage JSON-LD)</h3>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Judul Section FAQ</label>
                            <input type="text" name="faq_title" value="<?php echo esc_attr($data['faq_title']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Subjudul Section FAQ</label>
                            <input type="text" name="faq_subtitle" value="<?php echo esc_attr($data['faq_subtitle']); ?>">
                        </div>
                    </div>
                    <div id="pk-faq-list">
                        <?php foreach ((array) $data['faqs'] as $fq): ?>
                            <div class="fds-pk-row">
                                <div class="fds-pk-row-ctrl">
                                    <button type="button" class="button button-small" onclick="fdsPkMoveRow(this,-1)">&uarr;</button>
                                    <button type="button" class="button button-small" onclick="fdsPkMoveRow(this,1)">&darr;</button>
                                    <button type="button" class="button button-small button-link-delete" onclick="fdsPkRemoveRow(this)">Hapus</button>
                                </div>
                                <div class="fds-pk-field">
                                    <label>Pertanyaan</label>
                                    <input type="text" name="faq_q[]" value="<?php echo esc_attr($fq['q'] ?? ''); ?>">
                                </div>
                                <div class="fds-pk-field" style="margin-bottom:0;">
                                    <label>Jawaban</label>
                                    <textarea name="faq_a[]" rows="2"><?php echo esc_textarea($fq['a'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="button" onclick="fdsPkAddFaq()">+ Tambah Pertanyaan FAQ</button>
                </div>

                <div class="fds-pk-card">
                    <h3>6. CTA Penutup &amp; Metadata SEO (/produk/)</h3>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Eyebrow CTA Penutup</label>
                            <input type="text" name="cta_label" value="<?php echo esc_attr($data['cta_label']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Judul CTA Penutup</label>
                            <input type="text" name="cta_title" value="<?php echo esc_attr($data['cta_title']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Deskripsi CTA Penutup</label>
                        <textarea name="cta_desc" rows="2"><?php echo esc_textarea($data['cta_desc']); ?></textarea>
                    </div>
                    <div class="fds-pk-grid2" style="grid-template-columns:repeat(3,1fr);">
                        <div class="fds-pk-field">
                            <label>Tombol Primer</label>
                            <input type="text" name="cta_primary_text" value="<?php echo esc_attr($data['cta_primary_text']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>URL Tombol Primer</label>
                            <input type="text" name="cta_primary_url" value="<?php echo esc_attr($data['cta_primary_url']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Tombol WhatsApp Sekunder</label>
                            <input type="text" name="cta_secondary_text" value="<?php echo esc_attr($data['cta_secondary_text']); ?>">
                        </div>
                    </div>
                    <hr style="margin:14px 0;border:none;border-top:1px solid #f1f5f9;">
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>SEO Meta Title (Maks 60 Karakter)</label>
                            <input type="text" name="seo_title" value="<?php echo esc_attr($data['seo_title']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Schema CollectionPage Name</label>
                            <input type="text" name="schema_name" value="<?php echo esc_attr($data['schema_name']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>SEO Meta Description (Maks 158 Karakter)</label>
                            <textarea name="seo_desc" rows="2"><?php echo esc_textarea($data['seo_desc']); ?></textarea>
                        </div>
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>Schema CollectionPage Description</label>
                            <textarea name="schema_desc" rows="2"><?php echo esc_textarea($data['schema_desc']); ?></textarea>
                        </div>
                    </div>
                </div>

            <?php elseif ($meta['kind'] === 'kat'): ?>
                <!-- HALAMAN ARSIP KATEGORI DRONE -->
                <div class="fds-pk-card">
                    <h3>1. Hero Kategori (/kategori-drone/<?php echo esc_html($meta['slug']); ?>/)</h3>
                    <div class="fds-pk-field">
                        <label>Headline Utama (H1)</label>
                        <input type="text" name="headline" value="<?php echo esc_attr($data['headline']); ?>">
                    </div>
                    <div class="fds-pk-field">
                        <label>Subjudul Hero</label>
                        <textarea name="subtitle" rows="2"><?php echo esc_textarea($data['subtitle']); ?></textarea>
                        <span class="fds-pk-hint">Menampilkan subjudul kategori ini secara langsung dan menimpa ringkasan bawaan taksonomi apabila diisi.</span>
                    </div>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>Label Tombol CTA Konsultasi</label>
                            <input type="text" name="cta_kontak_label" value="<?php echo esc_attr($data['cta_kontak_label']); ?>">
                        </div>
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>URL Tombol CTA Konsultasi</label>
                            <input type="text" name="cta_kontak_url" value="<?php echo esc_attr($data['cta_kontak_url']); ?>">
                        </div>
                    </div>
                </div>

                <div class="fds-pk-card">
                    <h3>2. Panduan Teknis &amp; Narasi Kategori</h3>
                    <div class="fds-pk-field">
                        <label>Judul Panduan Teknis (H2)</label>
                        <input type="text" name="guide_title" value="<?php echo esc_attr($data['guide_title']); ?>">
                    </div>
                    <div class="fds-pk-field" style="margin-bottom:0;">
                        <label>Paragraf Panduan Teknis (Pisahkan setiap paragraf dengan 1 baris kosong)</label>
                        <textarea name="paragraphs" rows="8"><?php echo esc_textarea(implode("\n\n", (array) $data['paragraphs'])); ?></textarea>
                    </div>
                </div>

                <div class="fds-pk-card">
                    <h3>3. FAQ Kategori (SSR Accordion &amp; FAQPage Schema)</h3>
                    <div id="pk-faq-list">
                        <?php foreach ((array) $data['faqs'] as $fq): ?>
                            <div class="fds-pk-row">
                                <div class="fds-pk-row-ctrl">
                                    <button type="button" class="button button-small" onclick="fdsPkMoveRow(this,-1)">&uarr;</button>
                                    <button type="button" class="button button-small" onclick="fdsPkMoveRow(this,1)">&darr;</button>
                                    <button type="button" class="button button-small button-link-delete" onclick="fdsPkRemoveRow(this)">Hapus</button>
                                </div>
                                <div class="fds-pk-field">
                                    <label>Pertanyaan</label>
                                    <input type="text" name="faq_q[]" value="<?php echo esc_attr($fq['q'] ?? ''); ?>">
                                </div>
                                <div class="fds-pk-field" style="margin-bottom:0;">
                                    <label>Jawaban</label>
                                    <textarea name="faq_a[]" rows="2"><?php echo esc_textarea($fq['a'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="button" onclick="fdsPkAddFaq()">+ Tambah Pertanyaan FAQ</button>
                </div>

                <div class="fds-pk-card">
                    <h3>4. Metadata SEO Kategori</h3>
                    <div class="fds-pk-field">
                        <label>SEO Meta Title (Maks 60 Karakter)</label>
                        <input type="text" name="seo_title" value="<?php echo esc_attr($data['seo_title']); ?>">
                    </div>
                    <div class="fds-pk-field" style="margin-bottom:0;">
                        <label>SEO Meta Description (Maks 158 Karakter)</label>
                        <textarea name="seo_desc" rows="2"><?php echo esc_textarea($data['seo_desc']); ?></textarea>
                    </div>
                </div>

            <?php elseif ($meta['kind'] === 'kat-ui'): ?>
                <!-- TEKS BERSAMA & SIDEBAR SEMUA KATEGORI -->
                <div class="fds-pk-card">
                    <h3>1. Teks UI Bersama Halaman Kategori Drone</h3>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Label Pil "Semua Produk"</label>
                            <input type="text" name="all_products_label" value="<?php echo esc_attr($data['all_products_label']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Tombol Bandingkan Hero</label>
                            <input type="text" name="hero_compare_text" value="<?php echo esc_attr($data['hero_compare_text']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>URL Tombol Bandingkan Hero</label>
                            <input type="text" name="hero_compare_url" value="<?php echo esc_attr($data['hero_compare_url']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Awalan Judul FAQ (diikuti nama kategori)</label>
                            <input type="text" name="faq_title_prefix" value="<?php echo esc_attr($data['faq_title_prefix']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Subjudul FAQ Kategori</label>
                        <textarea name="faq_subtitle" rows="2"><?php echo esc_textarea($data['faq_subtitle']); ?></textarea>
                    </div>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>Awalan Pesan Kategori Kosong</label>
                            <input type="text" name="empty_text_prefix" value="<?php echo esc_attr($data['empty_text_prefix']); ?>">
                        </div>
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>Tautan Fallback Kategori Kosong</label>
                            <input type="text" name="empty_link_text" value="<?php echo esc_attr($data['empty_link_text']); ?>">
                        </div>
                    </div>
                </div>

                <div class="fds-pk-card">
                    <h3>2. Sidebar Dukungan Manufaktur Lokal</h3>
                    <div class="fds-pk-field">
                        <label>Judul Sidebar</label>
                        <input type="text" name="sidebar_title" value="<?php echo esc_attr($data['sidebar_title']); ?>">
                    </div>
                    <div class="fds-pk-field">
                        <label>Deskripsi Sidebar</label>
                        <textarea name="sidebar_desc" rows="3"><?php echo esc_textarea($data['sidebar_desc']); ?></textarea>
                    </div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#334155;margin-bottom:6px;">
                        Daftar Tautan Internal Sidebar (gunakan <code>{cta_kontak_url}</code> untuk URL kontak otomatis sesuai kategori)
                    </label>
                    <div id="pk-sl-list">
                        <?php foreach ((array) $data['sidebar_links'] as $sl): ?>
                            <div class="fds-pk-row">
                                <div class="fds-pk-row-ctrl">
                                    <button type="button" class="button button-small" onclick="fdsPkMoveRow(this,-1)">&uarr;</button>
                                    <button type="button" class="button button-small" onclick="fdsPkMoveRow(this,1)">&darr;</button>
                                    <button type="button" class="button button-small button-link-delete" onclick="fdsPkRemoveRow(this)">Hapus</button>
                                </div>
                                <div class="fds-pk-grid2">
                                    <input type="text" name="sl_label[]" value="<?php echo esc_attr($sl['label'] ?? ''); ?>" placeholder="Teks Tautan">
                                    <input type="text" name="sl_url[]" value="<?php echo esc_attr($sl['url'] ?? ''); ?>" placeholder="/produk/ atau {cta_kontak_url}">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="button" onclick="fdsPkAddSidebarLink()">+ Tambah Tautan Sidebar</button>
                </div>

            <?php elseif ($meta['kind'] === 'detail-ui'): ?>
                <!-- TEKS UI BERSAMA HALAMAN DETAIL DRONE -->
                <div class="fds-pk-card">
                    <h3>1. Tombol Aksi Hero &amp; Bagian Spesifikasi (/drone/*/)</h3>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Tombol CTA Utama Hero</label>
                            <input type="text" name="hero_cta1_text" value="<?php echo esc_attr($data['hero_cta1_text']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Tautan Bandingkan Hero</label>
                            <input type="text" name="hero_cta2_text" value="<?php echo esc_attr($data['hero_cta2_text']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Eyebrow Section Spesifikasi</label>
                            <input type="text" name="specs_eyebrow" value="<?php echo esc_attr($data['specs_eyebrow']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Tautan Bandingkan di Spesifikasi</label>
                            <input type="text" name="specs_compare_link" value="<?php echo esc_attr($data['specs_compare_link']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Heading Section Spesifikasi (mendukung tag <code>&lt;br&gt;</code>)</label>
                        <input type="text" name="specs_heading" value="<?php echo esc_attr($data['specs_heading']); ?>">
                    </div>
                    <div class="fds-pk-field">
                        <label>Deskripsi Cadangan Jika Deskripsi Drone Kosong (gunakan <code>{drone_name}</code>)</label>
                        <textarea name="specs_desc_fallback" rows="2"><?php echo esc_textarea($data['specs_desc_fallback']); ?></textarea>
                    </div>
                    <div class="fds-pk-field" style="margin-bottom:0;">
                        <label>Teks Jika Tabel Spesifikasi Belum Diisi</label>
                        <input type="text" name="specs_empty_text" value="<?php echo esc_attr($data['specs_empty_text']); ?>">
                    </div>
                </div>

                <div class="fds-pk-card">
                    <h3>2. Aplikasi Use-Cases, Panduan Teknis &amp; CTA Penutup Detail Drone</h3>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Eyebrow Aplikasi &amp; Use-Cases</label>
                            <input type="text" name="uc_eyebrow" value="<?php echo esc_attr($data['uc_eyebrow']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Format Heading Use-Cases (gunakan <code>{drone_name}</code>)</label>
                            <input type="text" name="uc_heading_tpl" value="<?php echo esc_attr($data['uc_heading_tpl']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Daftar Cadangan Use-Cases Jika Unit Baru Kosong (1 item per baris)</label>
                        <textarea name="uc_fallback_items" rows="4"><?php echo esc_textarea(implode("\n", (array) $data['uc_fallback_items'])); ?></textarea>
                    </div>
                    <div class="fds-pk-field">
                        <label>Eyebrow Panduan Teknis &amp; FAQ</label>
                        <input type="text" name="guide_eyebrow" value="<?php echo esc_attr($data['guide_eyebrow']); ?>">
                    </div>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Format Heading CTA Penutup (gunakan <code>{drone_name}</code>)</label>
                            <input type="text" name="cta_heading_tpl" value="<?php echo esc_attr($data['cta_heading_tpl']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Tombol Utama CTA Penutup</label>
                            <input type="text" name="cta_primary_text" value="<?php echo esc_attr($data['cta_primary_text']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Deskripsi CTA Penutup</label>
                        <textarea name="cta_desc" rows="2"><?php echo esc_textarea($data['cta_desc']); ?></textarea>
                    </div>
                    <div class="fds-pk-grid2" style="margin-bottom:0;">
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>Tautan Sekunder CTA Penutup</label>
                            <input type="text" name="cta_secondary_text" value="<?php echo esc_attr($data['cta_secondary_text']); ?>">
                        </div>
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>URL Tautan Sekunder</label>
                            <input type="text" name="cta_secondary_url" value="<?php echo esc_attr($data['cta_secondary_url']); ?>">
                        </div>
                    </div>
                </div>

            <?php elseif ($meta['kind'] === 'bandingkan'): ?>
                <!-- HALAMAN BANDINGKAN (/bandingkan/) -->
                <div class="fds-pk-card">
                    <h3>1. Hero &amp; Label Slot Perbandingan (/bandingkan/)</h3>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Eyebrow Hero</label>
                            <input type="text" name="hero_label" value="<?php echo esc_attr($data['hero_label']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Judul Hero (H1)</label>
                            <input type="text" name="hero_title" value="<?php echo esc_attr($data['hero_title']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Deskripsi Hero</label>
                        <textarea name="hero_desc" rows="2"><?php echo esc_textarea($data['hero_desc']); ?></textarea>
                    </div>
                    <div class="fds-pk-grid2" style="grid-template-columns:repeat(3,1fr);">
                        <div class="fds-pk-field">
                            <label>Sticky Bar Slot 1</label>
                            <input type="text" name="sticky_slot1" value="<?php echo esc_attr($data['sticky_slot1']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Label Tengah ("vs")</label>
                            <input type="text" name="sticky_vs" value="<?php echo esc_attr($data['sticky_vs']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Sticky Bar Slot 2</label>
                            <input type="text" name="sticky_slot2" value="<?php echo esc_attr($data['sticky_slot2']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-grid2" style="grid-template-columns:repeat(4,1fr);margin-bottom:0;">
                        <div class="fds-pk-field">
                            <label>Judul Placeholder Slot</label>
                            <input type="text" name="slot_pick_title" value="<?php echo esc_attr($data['slot_pick_title']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Tombol Placeholder</label>
                            <input type="text" name="slot_pick_btn" value="<?php echo esc_attr($data['slot_pick_btn']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Tautan Ganti / Hapus</label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                                <input type="text" name="slot_change_btn" value="<?php echo esc_attr($data['slot_change_btn']); ?>">
                                <input type="text" name="slot_remove_btn" value="<?php echo esc_attr($data['slot_remove_btn']); ?>">
                            </div>
                        </div>
                        <div class="fds-pk-field">
                            <label>Tombol Penawaran / Detail</label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                                <input type="text" name="slot_offer_btn" value="<?php echo esc_attr($data['slot_offer_btn']); ?>">
                                <input type="text" name="slot_detail_btn" value="<?php echo esc_attr($data['slot_detail_btn']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="fds-pk-field" style="margin:8px 0 0;">
                        <label>Subjudul Placeholder Slot</label>
                        <input type="text" name="slot_pick_sub" value="<?php echo esc_attr($data['slot_pick_sub']); ?>">
                    </div>
                </div>

                <div class="fds-pk-card">
                    <h3>2. Header Spesifikasi, Use-Cases &amp; Keunggulan</h3>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Eyebrow Spesifikasi</label>
                            <input type="text" name="specs_badge" value="<?php echo esc_attr($data['specs_badge']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Judul Spesifikasi (H2)</label>
                            <input type="text" name="specs_title" value="<?php echo esc_attr($data['specs_title']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Subjudul Spesifikasi</label>
                        <input type="text" name="specs_desc" value="<?php echo esc_attr($data['specs_desc']); ?>">
                    </div>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Judul Status Kosong Tabel</label>
                            <input type="text" name="table_empty_title" value="<?php echo esc_attr($data['table_empty_title']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Deskripsi Status Kosong Tabel</label>
                            <input type="text" name="table_empty_desc" value="<?php echo esc_attr($data['table_empty_desc']); ?>">
                        </div>
                    </div>
                    <hr style="margin:14px 0;border:none;border-top:1px solid #f1f5f9;">
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Eyebrow Use-Cases</label>
                            <input type="text" name="uc_badge" value="<?php echo esc_attr($data['uc_badge']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Judul Use-Cases (H2)</label>
                            <input type="text" name="uc_title" value="<?php echo esc_attr($data['uc_title']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Subjudul Use-Cases</label>
                        <input type="text" name="uc_desc" value="<?php echo esc_attr($data['uc_desc']); ?>">
                    </div>
                    <div class="fds-pk-grid2" style="grid-template-columns:repeat(3,1fr);">
                        <div class="fds-pk-field">
                            <label>Placeholder Use-Case Model 1 / 2</label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                                <input type="text" name="uc_empty_model1" value="<?php echo esc_attr($data['uc_empty_model1']); ?>">
                                <input type="text" name="uc_empty_model2" value="<?php echo esc_attr($data['uc_empty_model2']); ?>">
                            </div>
                        </div>
                        <div class="fds-pk-field">
                            <label>Placeholder Keunggulan Model 1 / 2</label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                                <input type="text" name="stats_empty_model1" value="<?php echo esc_attr($data['stats_empty_model1']); ?>">
                                <input type="text" name="stats_empty_model2" value="<?php echo esc_attr($data['stats_empty_model2']); ?>">
                            </div>
                        </div>
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>Teks Kosong Bar Keunggulan</label>
                            <input type="text" name="stats_empty_text" value="<?php echo esc_attr($data['stats_empty_text']); ?>">
                        </div>
                    </div>
                </div>

                <div class="fds-pk-card">
                    <h3>3. Panduan Pemilihan Armada, Modal Picker &amp; CTA Penutup</h3>
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Eyebrow Panduan</label>
                            <input type="text" name="guide_badge" value="<?php echo esc_attr($data['guide_badge']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Judul Panduan (H2)</label>
                            <input type="text" name="guide_title" value="<?php echo esc_attr($data['guide_title']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Paragraf Pembuka Panduan</label>
                        <textarea name="guide_lead" rows="2"><?php echo esc_textarea($data['guide_lead']); ?></textarea>
                    </div>
                    <div class="fds-pk-field">
                        <label>Isi Artikel Panduan (HTML aman: <code>&lt;h3&gt;</code>, <code>&lt;p&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;strong&gt;</code>)</label>
                        <textarea name="guide_html" rows="8"><?php echo esc_textarea($data['guide_html']); ?></textarea>
                    </div>
                    <hr style="margin:14px 0;border:none;border-top:1px solid #f1f5f9;">
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Modal Eyebrow / Tab Semua</label>
                            <div style="display:grid;grid-template-columns:2fr 1fr;gap:6px;">
                                <input type="text" name="modal_eyebrow" value="<?php echo esc_attr($data['modal_eyebrow']); ?>">
                                <input type="text" name="modal_all_tab" value="<?php echo esc_attr($data['modal_all_tab']); ?>">
                            </div>
                        </div>
                        <div class="fds-pk-field">
                            <label>Judul Modal Picker</label>
                            <input type="text" name="modal_title" value="<?php echo esc_attr($data['modal_title']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Subjudul Modal Picker</label>
                            <input type="text" name="modal_sub" value="<?php echo esc_attr($data['modal_sub']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Judul Modal Kosong</label>
                            <input type="text" name="modal_empty_title" value="<?php echo esc_attr($data['modal_empty_title']); ?>">
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Deskripsi Modal Kosong</label>
                        <textarea name="modal_empty_desc" rows="2"><?php echo esc_textarea($data['modal_empty_desc']); ?></textarea>
                    </div>
                    <hr style="margin:14px 0;border:none;border-top:1px solid #f1f5f9;">
                    <div class="fds-pk-grid2">
                        <div class="fds-pk-field">
                            <label>Judul CTA Penutup</label>
                            <input type="text" name="cta_title" value="<?php echo esc_attr($data['cta_title']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Teks CTA Primer</label>
                            <input type="text" name="cta_primary_text" value="<?php echo esc_attr($data['cta_primary_text']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>URL CTA Primer</label>
                            <input type="text" name="cta_primary_url" value="<?php echo esc_attr($data['cta_primary_url']); ?>">
                        </div>
                        <div class="fds-pk-field">
                            <label>Teks / URL CTA Sekunder</label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                                <input type="text" name="cta_secondary_text" value="<?php echo esc_attr($data['cta_secondary_text']); ?>">
                                <input type="text" name="cta_secondary_url" value="<?php echo esc_attr($data['cta_secondary_url']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="fds-pk-field">
                        <label>Deskripsi CTA Penutup</label>
                        <textarea name="cta_desc" rows="2"><?php echo esc_textarea($data['cta_desc']); ?></textarea>
                    </div>
                    <div class="fds-pk-grid2" style="margin-bottom:0;">
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>SEO Meta Title (Maks 60 Karakter)</label>
                            <input type="text" name="seo_title" value="<?php echo esc_attr($data['seo_title']); ?>">
                        </div>
                        <div class="fds-pk-field" style="margin-bottom:0;">
                            <label>SEO Meta Description (Maks 158 Karakter)</label>
                            <textarea name="seo_desc" rows="2"><?php echo esc_textarea($data['seo_desc']); ?></textarea>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="fds-pk-sticky">
                <div style="display:flex;align-items:center;gap:12px;">
                    <?php if ($has_override): ?>
                        <button type="submit" name="fds_produk_reset" class="button"
                                onclick="return confirm('Kembalikan tab ini ke konten default bawaan tema?');"
                                style="color:#dc2626;border-color:#fca5a5;">
                            Reset Tab Ini ke Default
                        </button>
                    <?php endif; ?>
                    <span style="font-size:12.5px;color:#64748b;">Tab aktif: <strong><?php echo esc_html($meta['label']); ?></strong></span>
                </div>
                <button type="submit" name="fds_produk_save" class="button button-primary button-large" style="background:#0f808a;border-color:#0f808a;font-weight:600;padding:4px 24px;">
                    Simpan Perubahan Tab Ini
                </button>
            </div>
        </form>
    </div>

    <script>
    function fdsPkRemoveRow(btn) {
        var row = btn.closest('.fds-pk-row');
        if (row) row.remove();
    }
    function fdsPkMoveRow(btn, dir) {
        var row = btn.closest('.fds-pk-row');
        if (!row) return;
        if (dir === -1 && row.previousElementSibling) {
            row.parentNode.insertBefore(row, row.previousElementSibling);
        } else if (dir === 1 && row.nextElementSibling) {
            row.parentNode.insertBefore(row.nextElementSibling, row);
        }
    }
    function fdsPkCtrlHtml() {
        return '<div class="fds-pk-row-ctrl">' +
            '<button type="button" class="button button-small" onclick="fdsPkMoveRow(this,-1)">&uarr;</button>' +
            '<button type="button" class="button button-small" onclick="fdsPkMoveRow(this,1)">&darr;</button>' +
            '<button type="button" class="button button-small button-link-delete" onclick="fdsPkRemoveRow(this)">Hapus</button>' +
            '</div>';
    }
    function fdsPkAddHighlight() {
        var el = document.getElementById('pk-hl-list');
        if (!el) return;
        var div = document.createElement('div');
        div.className = 'fds-pk-row';
        div.innerHTML = fdsPkCtrlHtml() +
            '<div class="fds-pk-field"><label>Judul Highlight</label><input type="text" name="hl_title[]" value=""></div>' +
            '<div class="fds-pk-field" style="margin-bottom:0;"><label>Penjelasan</label><textarea name="hl_desc[]" rows="2"></textarea></div>';
        el.appendChild(div);
    }
    function fdsPkAddFaq() {
        var el = document.getElementById('pk-faq-list');
        if (!el) return;
        var div = document.createElement('div');
        div.className = 'fds-pk-row';
        div.innerHTML = fdsPkCtrlHtml() +
            '<div class="fds-pk-field"><label>Pertanyaan</label><input type="text" name="faq_q[]" value=""></div>' +
            '<div class="fds-pk-field" style="margin-bottom:0;"><label>Jawaban</label><textarea name="faq_a[]" rows="2"></textarea></div>';
        el.appendChild(div);
    }
    function fdsPkAddSidebarLink() {
        var el = document.getElementById('pk-sl-list');
        if (!el) return;
        var div = document.createElement('div');
        div.className = 'fds-pk-row';
        div.innerHTML = fdsPkCtrlHtml() +
            '<div class="fds-pk-grid2">' +
                '<input type="text" name="sl_label[]" value="" placeholder="Teks Tautan">' +
                '<input type="text" name="sl_url[]" value="" placeholder="/produk/ atau {cta_kontak_url}">' +
            '</div>';
        el.appendChild(div);
    }
    </script>
    <?php
}

