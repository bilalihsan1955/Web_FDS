<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS HOMEPAGE CONTENT MANAGER (PT KARYA SOLUSI ANGKASA)
 * =========================================================================
 * Pusat kontrol terpadu untuk mengelola seluruh teks & section halaman depan:
 * 1. Hero Section & Hero Slider Images (dengan Media Uploader)
 * 2. Kemitraan & Logo Marquee
 * 3. Solusi Industri (Badge, Judul, Deskripsi & 4 Kartu Solusi)
 * 4. Keunggulan (Bento Grid 7 Kartu)
 * 5. Lini Produk Drone (Header & 4 Statistik)
 * 6. Layanan Enterprise (Header & 5 Layanan)
 * 7. Newsroom / Blog
 * 8. Formulir Kontak & Inquiry
 */

// 1. DAFTARKAN MENU DI WP ADMIN
add_action('admin_menu', function () {
    add_menu_page(
        'Konten Beranda',
        'Konten Beranda',
        'manage_options',
        'fds-homepage-content',
        __NAMESPACE__ . '\\render_homepage_content_admin_page',
        'dashicons-admin-page',
        25
    );
});

// 2. ENQUEUE WP MEDIA UPLOADER UNTUK GAMBAR SLIDER & SOLUSI
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_fds-homepage-content') {
        wp_enqueue_media();
    }
});

// 3. HELPER DATA KONTEN BERANDA
function fds_get_default_layanan_items() {
    return [
        [
            'title' => 'Jasa Foto Udara & Survey LiDAR Akurasi Tinggi',
            'desc'  => 'Akuisisi foto udara resolusi tinggi dan survey drone LiDAR untuk menghasilkan ortofoto, point cloud, kontur topografi, DTM, dan DSM detail.',
            'url'   => home_url('/layanan/jasa-foto-udara-lidar/'),
            'group' => 'Foto Udara & LiDAR',
        ],
        [
            'title' => 'Konsultan GIS & Pemodelan Spasial',
            'desc'  => 'Penyusunan sistem informasi geografis untuk analisis spasial, perencanaan tata ruang, pengembangan wilayah, dan dashboard pemantauan.',
            'url'   => home_url('/layanan/perencanaan/'),
            'group' => 'Konsultan GIS',
        ],
        [
            'title' => 'Pemetaan Kawasan Tambang, Kehutanan & Infrastruktur',
            'desc'  => 'Survei terintegrasi untuk pemantauan progres pit dan volume stockpile tambang, inventarisasi hutan dan karbon, serta inspeksi jaringan energi.',
            'url'   => home_url('/layanan/pertambangan/'),
            'group' => 'Pemetaan Kawasan',
        ],
        [
            'title' => 'Produsen Drone Pertanian & Sprayer FERTO (TKDN 60,74%)',
            'desc'  => 'Manufaktur resmi drone pertanian sprayer dan granule spreader pupuk kapasitas 5L–50L dengan sertifikasi TKDN + BMP 60,74% (seri FERTO) serta SNI 9199:2023 pada FERTO 15 & FERTO 22.',
            'url'   => home_url('/kategori-drone/agrikultur/'),
            'group' => 'Produsen UAV',
        ],
        [
            'title' => 'Produsen Drone Pemetaan, Logistik & Surveillance',
            'desc'  => 'Perakitan UAV Fixed-Wing VTOL DELTAV jangkauan 60 km untuk mapping luas, drone kargo logistik DELFRO, serta drone surveillance kamera optik dan termal inframerah untuk patroli dan inspeksi malam.',
            'url'   => home_url('/kategori-drone/pemetaan-gis/'),
            'group' => 'Produsen UAV',
        ],
        [
            'title' => 'Pelatihan, Bootcamp & Sertifikasi Pilot Drone',
            'desc'  => 'Program pelatihan operasional drone, bootcamp pemetaan dan aplikasi sektoral, serta persiapan sertifikasi operator UAV untuk tim internal perusahaan dan institusi.',
            'url'   => home_url('/layanan/pelatihan-sertifikasi/'),
            'group' => 'Pelatihan & Operasional',
        ],
    ];
}

function fds_resolve_legacy_layanan_item_url(string $title, string $raw_url): string {
    $trimmed = trim($raw_url);
    $is_legacy_anchor = (
        $trimmed === ''
        || str_starts_with($trimmed, '#')
        || (bool) preg_match('#/\#(solusi|layanan|produk|kontak)/?$#i', $trimmed)
    );
    if (!$is_legacy_anchor) {
        return $trimmed;
    }

    $lower = strtolower($title);
    if (str_contains($lower, 'lidar') || str_contains($lower, 'foto udara')) {
        return home_url('/layanan/jasa-foto-udara-lidar/');
    }
    if (str_contains($lower, 'multispectral') || str_contains($lower, 'hyperspectral')) {
        return home_url('/layanan/jasa-multispectral-hyperspectral/');
    }
    if (str_contains($lower, 'pelatihan') || str_contains($lower, 'sertifikasi') || str_contains($lower, 'bootcamp') || str_contains($lower, 'academy') || str_contains($lower, 'casr')) {
        return home_url('/layanan/pelatihan-sertifikasi/');
    }
    if (str_contains($lower, 'gis') || str_contains($lower, 'tata ruang') || str_contains($lower, 'perencanaan')) {
        return home_url('/layanan/perencanaan/');
    }
    if (str_contains($lower, 'tambang')) {
        return home_url('/layanan/pertambangan/');
    }
    if (str_contains($lower, 'kebun') || str_contains($lower, 'tani') || str_contains($lower, 'ferto')) {
        return home_url('/kategori-drone/agrikultur/');
    }
    if (str_contains($lower, 'deltav') || str_contains($lower, 'pemetaan') || str_contains($lower, 'surveillance')) {
        return home_url('/kategori-drone/pemetaan-gis/');
    }
    if (str_contains($lower, 'delfro') || str_contains($lower, 'kargo') || str_contains($lower, 'logistik')) {
        return home_url('/kategori-drone/kargo/');
    }
    return home_url('/layanan/');
}

function fds_get_layanan_items() {
    $saved = get_option('fds_layanan_items', null);
    if ($saved === null) {
        return fds_get_default_layanan_items();
    }

    $items_customized = (bool) get_option('fds_layanan_items_customized', false);
    $items = [];
    if (is_array($saved)) {
        foreach ($saved as $item) {
            $t = trim((string) ($item['title'] ?? ''));
            if (!empty($t)) {
                $decoded_title = function_exists('fds_clean_string') ? \fds_clean_string($t) : html_entity_decode(wp_specialchars_decode($t, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $decoded_desc  = function_exists('fds_clean_string') ? \fds_clean_string((string) ($item['desc'] ?? '')) : html_entity_decode(wp_specialchars_decode((string) ($item['desc'] ?? ''), ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $decoded_group = !empty($item['group']) ? (function_exists('fds_clean_string') ? \fds_clean_string((string) $item['group']) : (string) $item['group']) : 'Pelatihan & Operasional';
                $raw_url       = trim((string) ($item['url'] ?? ''));
                $resolved_url  = $items_customized
                    ? ($raw_url !== '' ? $raw_url : home_url('/layanan/'))
                    : fds_resolve_legacy_layanan_item_url($decoded_title, $raw_url);
                $items[] = [
                    'title' => $decoded_title,
                    'desc'  => $decoded_desc,
                    'url'   => $resolved_url,
                    'group' => $decoded_group,
                ];
            }
        }
    }
    return $items;
}

function fds_get_homepage_content(bool $fresh = false) {
    static $static_hp = null;
    if (!$fresh && $static_hp !== null) {
        return $static_hp;
    }

    if (!$fresh) {
        $cached = get_transient('fds_homepage_content_cache_v2');
        if ($cached !== false && is_array($cached)) {
            if (function_exists('fds_deep_decode')) {
                $cached = \fds_deep_decode($cached);
            }
            $static_hp = $cached;
            return $cached;
        }
    }

    $solusi_title_customized    = (bool) get_option('fds_solusi_title_customized', false);
    $homelayanan_cta_customized = (bool) get_option('fds_homelayanan_cta_customized', false);

    $solusi_title_saved = get_option('fds_solusi_title', 'Satu Platform UAV. Enam Sektor Kerja Strategis.');
    if (!$solusi_title_customized && $solusi_title_saved === 'Satu Platform UAV. 4 Sektor Kerja Strategis.') {
        $solusi_title_saved = 'Satu Platform UAV. Enam Sektor Kerja Strategis.';
    }

    $layanan_cta_url_saved = trim((string) get_option('fds_layanan_cta_url', ''));
    if (!$homelayanan_cta_customized && ($layanan_cta_url_saved === '' || $layanan_cta_url_saved === '#kontak' || $layanan_cta_url_saved === '#layanan' || $layanan_cta_url_saved === '#')) {
        $layanan_cta_url_saved = home_url('/layanan/');
    }

    $layanan_cta_text_saved = get_option('fds_layanan_cta_text', 'Lihat Semua Layanan');
    if (!$homelayanan_cta_customized && $layanan_cta_text_saved === 'Konsultasi Kebutuhan Lapangan' && $layanan_cta_url_saved === home_url('/layanan/')) {
        $layanan_cta_text_saved = 'Lihat Semua Layanan';
    }

    $data = [
        // HERO
        'hero_badge'        => get_option('fds_hero_badge', 'Produsen Drone Pertanian & UAV Indonesia'),
        'hero_title'        => get_option('fds_hero_title', "Solusi Drone Pertanian &\nIndustrial Indonesia."),
        'hero_desc'         => get_option('fds_hero_desc', 'Produsen UAV berstandar ISO 9001:2015 serta TKDN 60,74% (seri FERTO) dan SNI 9199:2023 (FERTO 15 & FERTO 22). Solusi drone pertanian sprayer, pemetaan LiDAR, dan inspeksi industri.'),
        'hero_cta1_text'    => get_option('fds_hero_cta1_text', 'Jelajahi Solusi Kami'),
        'hero_cta1_url'     => get_option('fds_hero_cta1_url', '#solusi'),
        'hero_cta2_text'    => get_option('fds_hero_cta2_text', 'Konsultasi Enterprise'),
        'hero_cta2_url'     => get_option('fds_hero_cta2_url', '#kontak'),

        // MITRA
        'mitra_heading'     => get_option('fds_mitra_heading', 'Dipercaya & Digunakan oleh Institusi Nasional dan Lembaga Riset'),

        // SOLUSI INDUSTRI
        'solusi_badge'      => get_option('fds_solusi_badge', 'Solusi Industri FDS'),
        'solusi_title'      => $solusi_title_saved,
        'solusi_desc'       => get_option('fds_solusi_desc', 'Dirancang khusus mengatasi kondisi medan terberat di Indonesia: dari area tambang terbuka, perkebunan, kehutanan, koridor energi, hingga tata ruang & tanggap bencana.'),

        // PRODUK DRONE
        'produk_badge'      => get_option('fds_produk_badge', 'Spesifikasi & Sertifikasi'),
        'produk_title'      => get_option('fds_produk_title', 'Standar Mutu Manufaktur Nasional.'),
        'produk_desc'       => get_option('fds_produk_desc', 'Seluruh wahana diproduksi di Sleman, Yogyakarta dengan kepatuhan regulasi penerbangan DKPPU dan sertifikasi resmi Kementerian Perindustrian RI.'),
        'produk_stat1_num'  => get_option('fds_produk_stat1_num', '60,74%'),
        'produk_stat1_lbl'  => get_option('fds_produk_stat1_lbl', 'TKDN + BMP Kemenperin RI (Seri FERTO)'),
        'produk_stat2_num'  => get_option('fds_produk_stat2_num', 'SNI'),
        'produk_stat2_lbl'  => get_option('fds_produk_stat2_lbl', 'SNI 9199:2023 (FERTO 15 & 22)'),
        'produk_stat3_num'  => get_option('fds_produk_stat3_num', '50 Liter'),
        'produk_stat3_lbl'  => get_option('fds_produk_stat3_lbl', 'Kapasitas Semprot Maksimal FERTO'),
        'produk_stat4_num'  => get_option('fds_produk_stat4_num', 'Nasional'),
        'produk_stat4_lbl'  => get_option('fds_produk_stat4_lbl', 'Dukungan Servis & Suku Cadang Resmi'),

        // KEUNGGULAN (BENTO GRID)
        'keunggulan_badge'       => get_option('fds_keunggulan_badge', 'Keunggulan Manufaktur FDS'),
        'keunggulan_title'       => get_option('fds_keunggulan_title', 'Mengapa Memilih Produsen Drone Lokal?'),
        
        'keunggulan_card1_badge' => get_option('fds_keunggulan_card1_badge', 'Riset & Perakitan Sleman'),
        'keunggulan_card1_title' => get_option('fds_keunggulan_card1_title', "Rangka Karbon Komposit &\nAvionik In-House."),
        'keunggulan_card1_desc'  => get_option('fds_keunggulan_card1_desc', 'Setiap unit dirakit dan diuji terbang langsung di fasilitas workshop kami di Sleman, Yogyakarta di bawah audit Sistem Manajemen Mutu ISO 9001:2015.'),
        'keunggulan_card1_img'   => get_option('fds_keunggulan_card1_img', get_option('fds_img_keunggulan', get_template_directory_uri() . '/public/images/site/workshop-deltav.webp')),

        'keunggulan_card2_badge'   => get_option('fds_keunggulan_card2_badge', 'Legalitas Pengadaan'),
        'keunggulan_card2_stat'    => get_option('fds_keunggulan_card2_stat', '60,74%'),
        'keunggulan_card2_substat' => get_option('fds_keunggulan_card2_substat', 'TKDN + BMP Seri FERTO Kemenperin RI'),
        'keunggulan_card2_desc'    => get_option('fds_keunggulan_card2_desc', 'Sertifikat resmi TKDN + BMP untuk seluruh model drone pertanian seri FERTO. Memenuhi prioritas belanja pemerintah di e-Katalog LKPP.'),
        'keunggulan_card2_img'     => get_option('fds_keunggulan_card2_img', get_template_directory_uri() . '/public/images/bangga-buatan-indonesia.svg'),

        'keunggulan_card3_badge' => get_option('fds_keunggulan_card3_badge', 'Software Kendali'),
        'keunggulan_card3_title' => get_option('fds_keunggulan_card3_title', "FDS Station GCS\nBahasa Indonesia."),
        'keunggulan_card3_desc'  => get_option('fds_keunggulan_card3_desc', 'Software Ground Control Station intuitif tanpa kendala bahasa. Rencana terbang waypoint otomatis dan fitur keselamatan fail-safe RTH.'),
        'keunggulan_card3_img'   => get_option('fds_keunggulan_card3_img', get_template_directory_uri() . '/public/images/site/engineer-ops.webp'),

        'keunggulan_card4_badge' => get_option('fds_keunggulan_card4_badge', 'Sertifikasi Produk'),
        'keunggulan_card4_stat'  => get_option('fds_keunggulan_card4_stat', 'ISO & SNI'),
        'keunggulan_card4_desc'  => get_option('fds_keunggulan_card4_desc', 'Manajemen mutu manufaktur ISO 9001:2015 serta standar keselamatan nasional SNI 9199:2023 pada FERTO 15 dan FERTO 22.'),
        'keunggulan_card4_img'   => get_option('fds_keunggulan_card4_img', get_template_directory_uri() . '/public/images/site/hero-2.webp'),

        'keunggulan_card5_badge' => get_option('fds_keunggulan_card5_badge', 'Layanan Purna Jual'),
        'keunggulan_card5_title' => get_option('fds_keunggulan_card5_title', 'Suku Cadang Ready Stock & Garansi Resmi.'),
        'keunggulan_card5_desc'  => get_option('fds_keunggulan_card5_desc', 'Semua komponen arm, motor propulsi, nosel, dan baterai tersedia langsung di workshop tanpa perlu menunggu impor berbulan-bulan.'),
        'keunggulan_card5_img'   => get_option('fds_keunggulan_card5_img', get_template_directory_uri() . '/public/images/site/workshop-deltav.webp'),

        'keunggulan_card6_badge' => get_option('fds_keunggulan_card6_badge', 'Akademi Pilot'),
        'keunggulan_card6_stat'  => get_option('fds_keunggulan_card6_stat', 'DKPPU'),
        'keunggulan_card6_desc'  => get_option('fds_keunggulan_card6_desc', 'Pelatihan operator drone industri dan pendampingan persiapan sertifikasi pilot sesuai regulasi CASR Part 107.'),
        'keunggulan_card6_img'   => get_option('fds_keunggulan_card6_img', get_template_directory_uri() . '/public/images/site/about-team.webp'),

        'keunggulan_card7_badge' => get_option('fds_keunggulan_card7_badge', 'Skalabilitas Armada'),
        'keunggulan_card7_title' => get_option('fds_keunggulan_card7_title', 'Satu ekosistem. Banyak solusi.'),
        'keunggulan_card7_desc'  => get_option('fds_keunggulan_card7_desc', 'Agrikultur, pemetaan topografi, inspeksi infrastruktur, kehutanan, dan pertambangan.'),
        'keunggulan_card7_chips' => get_option('fds_keunggulan_card7_chips', 'Agri, Mapping, Inspeksi, Tambang, Hutan, BUMN'),
        'keunggulan_card7_img'   => get_option('fds_keunggulan_card7_img', get_template_directory_uri() . '/public/images/drone-ecosystem-3d.jpg'),

        // LAYANAN ENTERPRISE
        'layanan_badge'          => get_option('fds_layanan_badge', 'Dukungan Operasional'),
        'layanan_title'          => get_option('fds_layanan_title', 'Layanan Lengkap untuk Kelancaran Operasi Lapangan.'),
        'layanan_desc'           => get_option('fds_layanan_desc', 'Kami tidak hanya menjual hardware. Kami mendampingi tim Anda mulai dari instalasi awal, sertifikasi pilot, hingga pemeliharaan armada secara berkala.'),
        'layanan_cta_text'       => $layanan_cta_text_saved,
        'layanan_cta_url'        => $layanan_cta_url_saved,

        // NEWSROOM
        'blog_badge'             => get_option('fds_blog_badge', 'Newsroom & Edukasi'),
        'blog_title'             => get_option('fds_blog_title', 'Studi Kasus & Informasi Teknologi UAV.'),
        'blog_cta_text'          => get_option('fds_blog_cta_text', 'Lihat Semua Artikel & Studi Kasus'),

        // KONTAK / INQUIRY
        'kontak_badge'           => get_option('fds_kontak_badge', 'Konsultasi & Penawaran'),
        'kontak_title'           => get_option('fds_kontak_title', "Jadwalkan Konsultasi Teknis\natau Demo Terbang."),
        'kontak_desc'            => get_option('fds_kontak_desc', 'Diskusikan kebutuhan armada drone, estimasi biaya operasional, atau jadwal uji terbang bersama tim engineer PT Karya Solusi Angkasa.'),
        'kontak_wa_text'         => get_option('fds_kontak_wa_text', 'Hubungi via WhatsApp'),
        'kontak_form_title'      => get_option('fds_kontak_form_title', 'Formulir Permintaan Penawaran / Jadwal Demo'),
        'kontak_form_desc'       => get_option('fds_kontak_form_desc', 'Silakan lengkapi formulir di bawah ini, tim representatif kami siap membantu kebutuhan Anda:'),
        'kontak_form_btn_text'   => get_option('fds_kontak_form_btn_text', 'Kirim Permintaan Konsultasi'),
        'kontak_form_note'       => get_option('fds_kontak_form_note', 'Tim kami merespons pada hari kerja Senin–Sabtu. Data perusahaan Anda terlindungi.'),
        'show_map_home'          => (bool) get_option('fds_show_map_home', 1),
    ];

    // Decode semua HTML entities berulang (&amp;amp; → &amp; → &, dll)
    // agar template Blade tidak pernah menerima entity ganda.
    $clean_data = array_map(function($v) {
        if (!is_string($v)) return $v;
        if (function_exists('fds_clean_string')) {
            return \fds_clean_string($v);
        }
        $prev = '';
        while ($prev !== $v) {
            $prev = $v;
            $v = wp_specialchars_decode($v, ENT_QUOTES);
            $v = html_entity_decode($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $v;
    }, $data);

    set_transient('fds_homepage_content_cache_v2', $clean_data, 12 * HOUR_IN_SECONDS);
    $static_hp = $clean_data;
    return $clean_data;
}

// 5. TAMPILAN HALAMAN WP ADMIN KONTEN BERANDA
function render_homepage_content_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $message = '';
    if (isset($_POST['fds_content_save']) && check_admin_referer('fds_content_nonce_action', 'fds_content_nonce')) {
        // 1. Simpan Fields Teks & Textarea Standar
        $fields_text = [
            'fds_hero_badge', 'fds_hero_cta1_text', 'fds_hero_cta1_url', 'fds_hero_cta2_text', 'fds_hero_cta2_url',
            'fds_mitra_heading',
            'fds_solusi_badge', 'fds_solusi_title', 'fds_solusi_cta_text', 'fds_solusi_cta_url',
            'fds_produk_badge', 'fds_produk_title', 'fds_produk_stat1_num', 'fds_produk_stat1_lbl', 'fds_produk_stat2_num', 'fds_produk_stat2_lbl', 'fds_produk_stat3_num', 'fds_produk_stat3_lbl', 'fds_produk_stat4_num', 'fds_produk_stat4_lbl',
            'fds_keunggulan_badge', 'fds_keunggulan_title', 'fds_keunggulan_card1_badge', 'fds_keunggulan_card1_img', 'fds_keunggulan_card2_badge', 'fds_keunggulan_card2_stat', 'fds_keunggulan_card2_substat', 'fds_keunggulan_card2_img', 'fds_keunggulan_card3_badge', 'fds_keunggulan_card3_img', 'fds_keunggulan_card4_badge', 'fds_keunggulan_card4_stat', 'fds_keunggulan_card4_img', 'fds_keunggulan_card5_badge', 'fds_keunggulan_card5_title', 'fds_keunggulan_card5_img', 'fds_keunggulan_card6_badge', 'fds_keunggulan_card6_stat', 'fds_keunggulan_card6_img', 'fds_keunggulan_card7_badge', 'fds_keunggulan_card7_title', 'fds_keunggulan_card7_chips', 'fds_keunggulan_card7_img',
            'fds_layanan_badge', 'fds_layanan_title', 'fds_layanan_cta_text', 'fds_layanan_cta_url',
            'fds_blog_badge', 'fds_blog_title', 'fds_blog_cta_text',
            'fds_kontak_badge', 'fds_kontak_wa_text', 'fds_kontak_form_title', 'fds_kontak_form_btn_text',
        ];

        $fields_textarea = [
            'fds_hero_title', 'fds_hero_desc',
            'fds_solusi_desc',
            'fds_produk_desc',
            'fds_keunggulan_card1_title', 'fds_keunggulan_card1_desc', 'fds_keunggulan_card2_desc', 'fds_keunggulan_card3_title', 'fds_keunggulan_card3_desc', 'fds_keunggulan_card4_desc', 'fds_keunggulan_card5_desc', 'fds_keunggulan_card6_desc', 'fds_keunggulan_card7_desc',
            'fds_layanan_desc',
            'fds_kontak_title', 'fds_kontak_desc', 'fds_kontak_form_desc', 'fds_kontak_form_note',
        ];

        foreach ($fields_text as $f) {
            update_option($f, sanitize_text_field(wp_unslash((string) ($_POST[$f] ?? ''))));
        }

        update_option('fds_solusi_title_customized', 1, 'no');
        update_option('fds_solusi_cta_customized', 1, 'no');
        update_option('fds_homelayanan_cta_customized', 1, 'no');

        if (!empty($_POST['fds_keunggulan_card1_img'])) {
            update_option('fds_img_keunggulan', esc_url_raw(wp_unslash((string) $_POST['fds_keunggulan_card1_img'])));
        }

        foreach ($fields_textarea as $f) {
            update_option($f, sanitize_textarea_field(wp_unslash((string) ($_POST[$f] ?? ''))));
        }

        // Simpan Tampilan Google Maps Beranda
        update_option('fds_show_map_home', isset($_POST['fds_show_map_home']) ? 1 : 0);

        // 2. Simpan Slide Hero
        if (isset($_POST['fds_slide_url']) && is_array($_POST['fds_slide_url'])) {
            $saved_slides = [];
            foreach ($_POST['fds_slide_url'] as $idx => $url) {
                $clean_url = esc_url_raw(wp_unslash((string) $url));
                if (!empty($clean_url)) {
                    $saved_slides[] = [
                        'url'   => $clean_url,
                        'title' => sanitize_text_field(wp_unslash((string) ($_POST['fds_slide_title'][$idx] ?? ''))),
                        'alt'   => sanitize_text_field(wp_unslash((string) ($_POST['fds_slide_alt'][$idx] ?? 'Full Drone Solutions Hero Slide'))),
                    ];
                }
            }
            update_option('fds_hero_slides', $saved_slides, 'no');
        }

        // 3. Simpan Kartu Solusi Industri
        if (isset($_POST['fds_solusi_resync']) && wp_unslash((string) $_POST['fds_solusi_resync']) === '1') {
            delete_option('fds_solusi_cards');
            update_option('fds_solusi_cards_customized', 0, 'no');
        } elseif (isset($_POST['fds_card_title']) && is_array($_POST['fds_card_title'])) {
            $existing_cards   = get_option('fds_solusi_cards', []);
            $existing_by_slug = [];
            if (is_array($existing_cards)) {
                foreach ($existing_cards as $ec) {
                    if (!empty($ec['slug'])) {
                        $existing_by_slug[(string) $ec['slug']] = $ec;
                    }
                }
            }
            $sectors_map   = function_exists(__NAMESPACE__ . '\\fds_get_layanan_sectors') ? fds_get_layanan_sectors() : [];
            $default_cards = function_exists(__NAMESPACE__ . '\\fds_get_default_solusi_cards') ? fds_get_default_solusi_cards() : [];

            $saved_cards = [];
            foreach ($_POST['fds_card_title'] as $idx => $title) {
                $clean_title = sanitize_text_field(wp_unslash((string) $title));
                $clean_desc  = sanitize_textarea_field(wp_unslash((string) ($_POST['fds_card_desc'][$idx] ?? '')));
                $clean_slug  = sanitize_title(wp_unslash((string) ($_POST['fds_card_slug'][$idx] ?? '')));
                $clean_num   = sanitize_text_field(wp_unslash((string) ($_POST['fds_card_num'][$idx] ?? '')));
                $clean_url   = sanitize_text_field(wp_unslash((string) ($_POST['fds_card_link_url'][$idx] ?? '')));

                if ($clean_slug === '' && $clean_url !== '' && preg_match('#/layanan/([a-z0-9-]+)/?#i', $clean_url, $sm)) {
                    if (isset($sectors_map[$sm[1]])) {
                        $clean_slug = $sm[1];
                    }
                }

                $preserved_tags = [];
                if ($clean_slug !== '' && !empty($existing_by_slug[$clean_slug]['tags'])) {
                    $preserved_tags = $existing_by_slug[$clean_slug]['tags'];
                } elseif ($clean_slug !== '' && !empty($sectors_map[$clean_slug]['card_tags'])) {
                    $preserved_tags = $sectors_map[$clean_slug]['card_tags'];
                }

                if ($clean_num === '' && $clean_slug !== '' && !empty($sectors_map[$clean_slug]['num'])) {
                    $clean_num = (string) $sectors_map[$clean_slug]['num'];
                }

                $saved_cards[] = [
                    'num'       => $clean_num,
                    'slug'      => $clean_slug,
                    'image'     => esc_url_raw(wp_unslash((string) ($_POST['fds_card_image'][$idx] ?? ''))),
                    'image_alt' => sanitize_text_field(wp_unslash((string) ($_POST['fds_card_image_alt'][$idx] ?? ''))),
                    'title'     => $clean_title,
                    'desc'      => $clean_desc,
                    'tags'      => $preserved_tags,
                    'tag'       => sanitize_text_field(wp_unslash((string) ($_POST['fds_card_tag'][$idx] ?? ''))),
                    'link_text' => sanitize_text_field(wp_unslash((string) ($_POST['fds_card_link_text'][$idx] ?? ''))),
                    'link_url'  => $clean_url,
                ];
            }

            $was_customized = (bool) get_option('fds_solusi_cards_customized', false);
            $norm_cmp = function ($cards_arr) {
                $clean = function ($s) {
                    return function_exists('fds_clean_string')
                        ? \fds_clean_string((string) $s)
                        : html_entity_decode(wp_specialchars_decode((string) $s, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                };
                $out = [];
                foreach ((array) $cards_arr as $item) {
                    $t = str_replace("\r\n", "\n", trim((string) ($item['title'] ?? '')));
                    $d = str_replace("\r\n", "\n", trim((string) ($item['desc'] ?? '')));
                    if ($t === '' && $d === '') {
                        continue;
                    }
                    $out[] = [
                        'image'     => trim((string) ($item['image'] ?? '')),
                        'image_alt' => str_replace("\r\n", "\n", trim($clean($item['image_alt'] ?? ''))),
                        'title'     => $clean($t),
                        'desc'      => $clean($d),
                        'tag'       => $clean(trim((string) ($item['tag'] ?? ''))),
                        'link_text' => $clean(trim((string) ($item['link_text'] ?? ''))),
                        'link_url'  => trim((string) ($item['link_url'] ?? '')),
                    ];
                }
                return $out;
            };

            if (!$was_customized && !empty($default_cards) && $norm_cmp($saved_cards) === $norm_cmp($default_cards)) {
                update_option('fds_solusi_cards_customized', 0, 'no');
            } else {
                update_option('fds_solusi_cards', $saved_cards, 'no');
                update_option('fds_solusi_cards_customized', 1, 'no');
            }
        }

        // 4. Simpan Item Layanan Enterprise Dinamis (Bisa Ditambah & Dikurangi)
        if (isset($_POST['fds_layanan_item_title']) && is_array($_POST['fds_layanan_item_title'])) {
            $saved_layanan = [];
            foreach ($_POST['fds_layanan_item_title'] as $idx => $title) {
                $t = sanitize_text_field(wp_unslash((string) $title));
                if (!empty($t)) {
                    $saved_layanan[] = [
                        'title' => $t,
                        'desc'  => sanitize_textarea_field(wp_unslash((string) ($_POST['fds_layanan_item_desc'][$idx] ?? ''))),
                        'url'   => sanitize_text_field(wp_unslash((string) ($_POST['fds_layanan_item_url'][$idx] ?? ''))),
                        'group' => sanitize_text_field(wp_unslash((string) ($_POST['fds_layanan_item_group'][$idx] ?? 'Pelatihan & Operasional'))),
                    ];
                }
            }
            update_option('fds_layanan_items', $saved_layanan, 'no');
            update_option('fds_layanan_items_customized', 1, 'no');
        }

        delete_transient('fds_homepage_content_cache');
        delete_transient('fds_homepage_content_cache_v2');
        if (function_exists(__NAMESPACE__ . '\\fds_flush_layanan_cache')) {
            fds_flush_layanan_cache();
        }
        $message = 'Semua perubahan konten Beranda berhasil disimpan!';
    }

    $c = fds_get_homepage_content(true);
    $solusi_admin = function_exists('App\\fds_get_solusi_data') ? fds_get_solusi_data() : [];
    $raw_slides = function_exists('App\\fds_get_hero_slides') ? fds_get_hero_slides() : (get_option('fds_hero_slides', []));
    if (empty($raw_slides)) {
        $site_img_base = get_template_directory_uri() . '/public/images/site';
        $raw_slides = [
            ['url' => $site_img_base . '/hero-1.webp', 'title' => 'Solusi Drone Industrial untuk Berbagai Sektor', 'alt' => 'Full Drone Solutions'],
            ['url' => $site_img_base . '/hero-2.webp', 'title' => 'Teknologi Presisi Pertanian & Perkebunan', 'alt' => 'Drone Pertanian'],
        ];
    }
    $slides = [];
    if (is_array($raw_slides)) {
        foreach ($raw_slides as $s_item) {
            $slides[] = [
                'url'   => $s_item['url'] ?? '',
                'title' => $s_item['title'] ?? '',
                'alt'   => $s_item['alt'] ?? 'Full Drone Solutions',
            ];
        }
    }

    $raw_cards = !empty($solusi_admin['cards']) ? $solusi_admin['cards'] : (get_option('fds_solusi_cards', []));
    if (empty($raw_cards) && function_exists('App\\fds_get_default_solusi_cards')) {
        $raw_cards = fds_get_default_solusi_cards();
    }
    $solusi_cards = [];
    if (is_array($raw_cards)) {
        foreach ($raw_cards as $c_item) {
            $tag_val = $c_item['tag'] ?? '';
            if (strpos($tag_val, 'Warning</b>') !== false || strpos($tag_val, 'Warning:') !== false) {
                $tag_val = '';
            }
            $solusi_cards[] = [
                'num'       => $c_item['num'] ?? '',
                'slug'      => $c_item['slug'] ?? '',
                'image'     => $c_item['image'] ?? '',
                'image_alt' => $c_item['image_alt'] ?? '',
                'title'     => $c_item['title'] ?? '',
                'desc'      => $c_item['desc'] ?? '',
                'tag'       => $tag_val,
                'link_text' => $c_item['link_text'] ?? 'Pelajari solusi sektor',
                'link_url'  => $c_item['link_url'] ?? home_url('/layanan/'),
            ];
        }
    }
    ?>
    <div class="wrap" style="max-width: 100%; margin-top: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; box-sizing: border-box;">
        <div style="background: #fff; padding: 24px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 8px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #0f808a; display: flex; align-items: center; justify-content: center; color: #fff;">
                    <span class="dashicons dashicons-admin-page" style="font-size: 24px; width: 24px; height: 24px;"></span>
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #1e293b;">Pusat Pengaturan Konten Beranda (Home)</h1>
                    <p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">Kelola semua teks, gambar slider, kartu solusi, keunggulan bento, produk, dan layanan di satu tempat.</p>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div style="background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; padding: 14px 18px; border-radius: 6px; margin: 20px 0 10px; font-size: 14px; font-weight: 500;">
                    ✓ <?php echo esc_html($message); ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="post" action="">
            <?php wp_nonce_field('fds_content_nonce_action', 'fds_content_nonce'); ?>

            <!-- NAVIGATION TABS -->
            <div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;" id="fds-content-tabs">
                <button type="button" class="tab-btn active" data-tab="tab-hero" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #0f808a; background: #0f808a; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer;">1. Hero &amp; Slider</button>
                <button type="button" class="tab-btn" data-tab="tab-mitra" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">2. Mitra</button>
                <button type="button" class="tab-btn" data-tab="tab-solusi" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">3. Solusi Industri</button>
                <button type="button" class="tab-btn" data-tab="tab-keunggulan" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">4. Keunggulan (Bento)</button>
                <button type="button" class="tab-btn" data-tab="tab-produk" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">5. Header Produk</button>
                <button type="button" class="tab-btn" data-tab="tab-layanan" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">6. Layanan Enterprise</button>
                <button type="button" class="tab-btn" data-tab="tab-blog" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">7. Newsroom</button>
                <button type="button" class="tab-btn" data-tab="tab-kontak" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">8. Kontak &amp; Form</button>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 1: HERO & SLIDER -->
            <!-- ========================================================= -->
            <div id="tab-hero" class="tab-content" style="background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    1. Hero Section &amp; Gambar Slider
                </h2>
                <div style="display: grid; gap: 16px; margin-bottom: 28px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge Atas Hero</label>
                        <input type="text" name="fds_hero_badge" value="<?php echo esc_attr($c['hero_badge']); ?>" style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama Hero (Gunakan Enter untuk baris baru)</label>
                        <textarea name="fds_hero_title" rows="2" style="width: 100%; font-size: 15px; font-weight: 600; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($c['hero_title']); ?></textarea>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Deskripsi Singkat Hero</label>
                        <textarea name="fds_hero_desc" rows="3" style="width: 100%; font-size: 13px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($c['hero_desc']); ?></textarea>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Tombol Utama (Teks)</label>
                            <input type="text" name="fds_hero_cta1_text" value="<?php echo esc_attr($c['hero_cta1_text']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Tombol Utama (Target URL)</label>
                            <input type="text" name="fds_hero_cta1_url" value="<?php echo esc_attr($c['hero_cta1_url']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Tombol Sekunder (Teks)</label>
                            <input type="text" name="fds_hero_cta2_text" value="<?php echo esc_attr($c['hero_cta2_text']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Tombol Sekunder (Target URL)</label>
                            <input type="text" name="fds_hero_cta2_url" value="<?php echo esc_attr($c['hero_cta2_url']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                    </div>
                </div>

                <!-- SLIDER IMAGES MANAGER -->
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 24px; margin-bottom: 12px; border-top: 1px solid #f1f5f9; pt-16;">
                    🖼️ Gambar Slider Hero Beranda (Rasio 16:9 / Full HD)
                </h3>
                <p style="font-size: 12px; color: #64748b; margin-top: 0; margin-bottom: 16px;">Unggah gambar latar slide hero. Gambar akan berganti otomatis secara halus.</p>

                <div id="slides-container" style="display: flex; flex-direction: column; gap: 14px;">
                    <?php foreach ($slides as $idx => $slide): ?>
                    <div class="slide-item" style="display: grid; grid-template-columns: 120px 1fr auto; gap: 16px; align-items: center; padding: 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <img src="<?php echo esc_url($slide['url']); ?>" class="slide-preview" style="width: 120px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; background: #e2e8f0;">
                        <div>
                            <input type="hidden" name="fds_slide_url[]" class="slide-url-input" value="<?php echo esc_attr($slide['url']); ?>">
                            <div style="margin-bottom: 6px;">
                                <input type="text" name="fds_slide_title[]" value="<?php echo esc_attr($slide['title']); ?>" placeholder="Judul / Keterangan Slide" style="width: 100%; font-size: 13px;">
                            </div>
                            <input type="text" name="fds_slide_alt[]" value="<?php echo esc_attr($slide['alt']); ?>" placeholder="Alt text gambar (SEO)" style="width: 100%; font-size: 12px;">
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            <button type="button" class="button btn-upload-slide" style="font-size: 12px;">Ganti Gambar</button>
                            <button type="button" class="button btn-remove-slide" style="font-size: 12px; color: #dc2626; border-color: #fca5a5;">Hapus</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 14px;">
                    <button type="button" id="btn-add-slide" class="button" style="font-size: 13px; font-weight: 600;">
                        ➕ Tambah Slide Gambar Baru
                    </button>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 2: MITRA -->
            <!-- ========================================================= -->
            <div id="tab-mitra" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    2. Section Mitra &amp; Logo Marquee
                </h2>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Heading Mitra Marquee</label>
                    <input type="text" name="fds_mitra_heading" value="<?php echo esc_attr($c['mitra_heading']); ?>" style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;">
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 3: SOLUSI INDUSTRI -->
            <!-- ========================================================= -->
            <div id="tab-solusi" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    3. Section Solusi Industri &amp; Kartu Layanan
                </h2>
                <input type="hidden" id="fds_solusi_resync" name="fds_solusi_resync" value="0">
                <div style="display: grid; gap: 16px; margin-bottom: 28px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge Sub-Heading</label>
                        <input type="text" name="fds_solusi_badge" value="<?php echo esc_attr($solusi_admin['badge'] ?? $c['solusi_badge']); ?>" style="width: 100%; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama Solusi</label>
                        <input type="text" name="fds_solusi_title" value="<?php echo esc_attr($solusi_admin['title'] ?? $c['solusi_title']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Deskripsi Solusi</label>
                        <textarea name="fds_solusi_desc" rows="2" style="width: 100%; font-size: 13px;"><?php echo esc_textarea($solusi_admin['desc'] ?? $c['solusi_desc']); ?></textarea>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Teks Tombol CTA Kanan Atas</label>
                            <input type="text" name="fds_solusi_cta_text" value="<?php echo esc_attr($solusi_admin['cta_text'] ?? 'Lihat semua layanan'); ?>" placeholder="Kosongkan untuk menyembunyikan tombol" style="width: 100%; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Target URL Tombol CTA</label>
                            <input type="text" name="fds_solusi_cta_url" value="<?php echo esc_attr($solusi_admin['cta_url'] ?? home_url('/layanan/')); ?>" placeholder="/layanan/" style="width: 100%; font-size: 13px;">
                        </div>
                    </div>
                </div>

                <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-top: 24px; margin-bottom: 16px; border-top: 1px solid #f1f5f9; padding-top: 18px;">
                    <div style="max-width: 680px;">
                        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">
                            🃏 Daftar Kartu Solusi Industri (Carousel Slide Horizontal Beranda)
                        </h3>
                        <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">Tampil di beranda dalam tata letak carousel slide horizontal. Default mengikuti 6 sektor utama di Hub Layanan (<code>/layanan/{sektor}/</code>). Setiap perubahan di sini langsung berlaku sebagai pengaturan khusus beranda.</p>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                        <button type="button" class="button" onclick="fdsSyncSolusiCardsFromSectors()" style="font-size: 12px; font-weight: 600;">
                            &#8635; Sinkronkan Ulang dari 6 Sektor
                        </button>
                        <button type="button" class="button button-primary" onclick="fdsAddSolusiCard()" style="font-size: 12px; font-weight: 600;">
                            + Tambah Kartu Solusi Baru
                        </button>
                    </div>
                </div>

                <div id="fds-solusi-cards-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <?php foreach ($solusi_cards as $idx => $card): ?>
                    <div class="fds-solusi-card-item" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; position: relative;">
                        <input type="hidden" name="fds_card_num[]" value="<?php echo esc_attr($card['num'] ?? ''); ?>">
                        <input type="hidden" name="fds_card_slug[]" value="<?php echo esc_attr($card['slug'] ?? ''); ?>">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                            <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: #0f808a;">Kartu Solusi #<span class="solusi-number"><?php echo ($idx + 1); ?></span></h4>
                            <div style="display: flex; gap: 6px;">
                                <button type="button" class="button button-small" onclick="fdsMoveSolusiCard(this, -1)" title="Pindah ke atas">▲</button>
                                <button type="button" class="button button-small" onclick="fdsMoveSolusiCard(this, 1)" title="Pindah ke bawah">▼</button>
                                <button type="button" class="button button-small button-link-delete" onclick="fdsDeleteSolusiCard(this)" style="color: #dc2626; margin-left: 6px;">🗑️ Hapus</button>
                            </div>
                        </div>
                        
                        <!-- GAMBAR SOLUSI -->
                        <div style="margin-bottom: 12px;">
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Gambar Kartu</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <img src="<?php echo esc_url(!empty($card['image']) ? $card['image'] : (get_template_directory_uri() . '/public/images/site/hero-1.webp')); ?>" class="card-preview-img" style="width: 100px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; background: #e2e8f0;">
                                <div style="flex: 1;">
                                    <input type="hidden" name="fds_card_image[]" class="card-image-input" value="<?php echo esc_attr($card['image'] ?? ''); ?>">
                                    <button type="button" class="button btn-upload-card-img" style="font-size: 11px; margin-bottom: 6px;">Pilih Gambar</button>
                                    <input type="text" name="fds_card_image_alt[]" value="<?php echo esc_attr($card['image_alt'] ?? ''); ?>" placeholder="Alt text gambar (SEO)" style="width: 100%; font-size: 11.5px;">
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Judul Kartu</label>
                            <input type="text" name="fds_card_title[]" value="<?php echo esc_attr($card['title'] ?? ''); ?>" style="width: 100%; font-size: 13px; font-weight: 600;" required placeholder="Contoh: Pertambangan">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Deskripsi Kartu</label>
                            <textarea name="fds_card_desc[]" rows="3" style="width: 100%; font-size: 12px;" placeholder="Tuliskan deskripsi ringkas solusi..."><?php echo esc_textarea($card['desc'] ?? ''); ?></textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Tag Internal / Catatan (Opsional — tidak tampil di kartu beranda)</label>
                                <input type="text" name="fds_card_tag[]" value="<?php echo esc_attr($card['tag'] ?? ''); ?>" placeholder="Pertambangan • LiDAR" style="width: 100%; font-size: 12px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Teks Link</label>
                                <input type="text" name="fds_card_link_text[]" value="<?php echo esc_attr($card['link_text'] ?? 'Pelajari solusi sektor'); ?>" style="width: 100%; font-size: 12px;">
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Target URL Link</label>
                            <input type="text" name="fds_card_link_url[]" value="<?php echo esc_attr($card['link_url'] ?? home_url('/layanan/')); ?>" placeholder="/layanan/sektor/" style="width: 100%; font-size: 12px;">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 16px;">
                    <button type="button" class="button button-secondary" onclick="fdsAddSolusiCard()" style="font-size: 12px; font-weight: 600;">
                        + Tambah Kartu Solusi Baru
                    </button>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 4: KEUNGGULAN BENTO GRID -->
            <!-- ========================================================= -->
            <div id="tab-keunggulan" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    4. Section Keunggulan Bento Grid
                </h2>
                <div style="display: grid; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge Sub-Heading</label>
                        <input type="text" name="fds_keunggulan_badge" value="<?php echo esc_attr($c['keunggulan_badge']); ?>" style="width: 100%; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama</label>
                        <input type="text" name="fds_keunggulan_title" value="<?php echo esc_attr($c['keunggulan_title']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                    </div>
                </div>

                <div style="display: grid; gap: 16px;">
                    <!-- Card 1 -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
                        <h4 style="margin: 0 0 10px; font-size: 14px; font-weight: 700; color: #0f808a;">Card 1: Manufaktur Lokal &amp; Desain Aerodinamis (Kartu Hero Besar Bento)</h4>
                        
                        <!-- GAMBAR BACKGROUND BENTO HERO -->
                        <div style="margin-bottom: 14px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px;">🖼️ Gambar Background Kartu Hero Bento (Pabrik / Workshop / Drone)</label>
                            <div style="display: flex; gap: 14px; align-items: center;">
                                <img id="bento-card1-preview" src="<?php echo esc_url($c['keunggulan_card1_img']); ?>" style="width: 140px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; background: #e2e8f0;">
                                <div>
                                    <input type="hidden" id="fds_keunggulan_card1_img" name="fds_keunggulan_card1_img" value="<?php echo esc_attr($c['keunggulan_card1_img']); ?>">
                                    <button type="button" id="btn-upload-bento-card1" class="button button-primary" style="font-size: 12px; background: #0f808a; border-color: #0f808a;">Pilih / Unggah Gambar</button>
                                    <button type="button" id="btn-remove-bento-card1" class="button" style="font-size: 12px; color: #dc2626; border-color: #fca5a5; margin-left: 6px;">Reset Default</button>
                                    <p style="margin: 4px 0 0; font-size: 11px; color: #64748b;">Rekomendasi rasio 16:9 / 1200×600 px. Foto akan tampil dengan efek gradasi gelap elegan.</p>
                                </div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 12px; margin-bottom: 8px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Badge</label>
                                <input type="text" name="fds_keunggulan_card1_badge" value="<?php echo esc_attr($c['keunggulan_card1_badge']); ?>" style="width: 100%;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Judul</label>
                                <input type="text" name="fds_keunggulan_card1_title" value="<?php echo esc_attr($c['keunggulan_card1_title']); ?>" style="width: 100%;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Deskripsi</label>
                            <textarea name="fds_keunggulan_card1_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card1_desc']); ?></textarea>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                        <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #0f808a;">Card 2: TKDN &amp; Bangga Buatan Indonesia (Kanan - Tinggi)</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 8px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Badge</label>
                                <input type="text" name="fds_keunggulan_card2_badge" value="<?php echo esc_attr($c['keunggulan_card2_badge']); ?>" style="width: 100%;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Nilai Stat</label>
                                <input type="text" name="fds_keunggulan_card2_stat" value="<?php echo esc_attr($c['keunggulan_card2_stat']); ?>" style="width: 100%;">
                            </div>
                        </div>
                        <div style="margin-bottom: 8px;">
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Sub-Label Bawah Nilai Stat</label>
                            <input type="text" name="fds_keunggulan_card2_substat" value="<?php echo esc_attr($c['keunggulan_card2_substat'] ?? 'Nilai TKDN + BMP Kemenperin RI'); ?>" style="width: 100%;">
                        </div>
                        <div style="margin-bottom: 8px;">
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Deskripsi Singkat</label>
                            <textarea name="fds_keunggulan_card2_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card2_desc']); ?></textarea>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">🖼️ Logo Bangga Buatan Indonesia / TKDN</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <img src="<?php echo esc_url($c['keunggulan_card2_img']); ?>" class="card-preview-img" style="width: 90px; height: 50px; object-fit: contain; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; padding: 4px;">
                                <div>
                                    <input type="hidden" name="fds_keunggulan_card2_img" class="card-image-input" value="<?php echo esc_attr($c['keunggulan_card2_img']); ?>">
                                    <button type="button" class="button btn-upload-card-img" style="font-size: 11px;">Pilih Logo</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Middle Row: Card 3 & Card 4 -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <!-- Card 3 -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                            <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #0f808a;">Card 3: Software GCS (Tengah Kiri)</h4>
                            <div style="margin-bottom: 8px;">
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Badge</label>
                                <input type="text" name="fds_keunggulan_card3_badge" value="<?php echo esc_attr($c['keunggulan_card3_badge']); ?>" style="width: 100%;">
                            </div>
                            <div style="margin-bottom: 8px;">
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Judul</label>
                                <input type="text" name="fds_keunggulan_card3_title" value="<?php echo esc_attr($c['keunggulan_card3_title']); ?>" style="width: 100%;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Deskripsi Singkat</label>
                                <textarea name="fds_keunggulan_card3_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card3_desc']); ?></textarea>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                            <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #0f808a;">Card 4: Standar ISO &amp; SNI (Tengah Kanan)</h4>
                            <div style="margin-bottom: 8px;">
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Judul Stat (ISO &amp; SNI)</label>
                                <input type="text" name="fds_keunggulan_card4_stat" value="<?php echo esc_attr($c['keunggulan_card4_stat']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Deskripsi Singkat</label>
                                <textarea name="fds_keunggulan_card4_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card4_desc']); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Row: Card Akademi Pilot & Card Ekosistem Multi-Sektor -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <!-- Card Akademi Pilot -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                            <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #0f808a;">Card 5: Akademi Pilot &amp; Operasional (Bawah Kiri)</h4>
                            <div style="margin-bottom: 8px;">
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Nilai Stat</label>
                                <input type="text" name="fds_keunggulan_card6_stat" value="<?php echo esc_attr($c['keunggulan_card6_stat']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                            </div>
                            <div style="margin-bottom: 8px;">
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Deskripsi Singkat</label>
                                <textarea name="fds_keunggulan_card6_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card6_desc']); ?></textarea>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">🖼️ Background Foto Tim / Operasional</label>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <img src="<?php echo esc_url($c['keunggulan_card6_img']); ?>" class="card-preview-img" style="width: 90px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; background: #e2e8f0;">
                                    <div>
                                        <input type="hidden" name="fds_keunggulan_card6_img" class="card-image-input" value="<?php echo esc_attr($c['keunggulan_card6_img']); ?>">
                                        <button type="button" class="button btn-upload-card-img" style="font-size: 11px;">Pilih Gambar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Satu Ekosistem -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                            <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #0f808a;">Card 6: Ekosistem Multi-Sektor (Bawah Kanan - Lebar)</h4>
                            <div style="margin-bottom: 8px;">
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Judul Utama</label>
                                <input type="text" name="fds_keunggulan_card7_title" value="<?php echo esc_attr($c['keunggulan_card7_title']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                            </div>
                            <div style="margin-bottom: 8px;">
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Deskripsi Singkat</label>
                                <textarea name="fds_keunggulan_card7_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card7_desc']); ?></textarea>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Label Chip Sektor (pisahkan dengan koma)</label>
                                <input type="text" name="fds_keunggulan_card7_chips" value="<?php echo esc_attr($c['keunggulan_card7_chips'] ?? 'Agri, Mapping, Inspeksi, Tambang, Hutan, BUMN'); ?>" style="width: 100%; font-size: 12px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 5: HEADER PRODUK & STATS -->
            <!-- ========================================================= -->
            <div id="tab-produk" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    5. Section Lini Produk Drone (Header &amp; Statistik)
                </h2>
                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge Sub-Heading</label>
                        <input type="text" name="fds_produk_badge" value="<?php echo esc_attr($c['produk_badge']); ?>" style="width: 100%; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama Section Produk</label>
                        <input type="text" name="fds_produk_title" value="<?php echo esc_attr($c['produk_title']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Deskripsi Produk</label>
                        <textarea name="fds_produk_desc" rows="2" style="width: 100%; font-size: 13px;"><?php echo esc_textarea($c['produk_desc']); ?></textarea>
                    </div>

                    <h3 style="font-size: 14px; font-weight: 700; margin: 12px 0 6px;">4 Baris Statistik USP di Bawah Produk:</h3>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                        <?php for($i = 1; $i <= 4; $i++): ?>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px;">
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Stat #<?php echo $i; ?> (Nilai)</label>
                            <input type="text" name="fds_produk_stat<?php echo $i; ?>_num" value="<?php echo esc_attr($c["produk_stat{$i}_num"]); ?>" style="width: 100%; font-size: 12px; font-weight: 700; margin-bottom: 4px;">
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Label</label>
                            <input type="text" name="fds_produk_stat<?php echo $i; ?>_lbl" value="<?php echo esc_attr($c["produk_stat{$i}_lbl"]); ?>" style="width: 100%; font-size: 11px;">
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 6: LAYANAN ENTERPRISE & DROPDOWN MENU -->
            <!-- ========================================================= -->
            <div id="tab-layanan" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">
                        6. Section Layanan Enterprise &amp; Dropdown Menu
                    </h2>
                    <span style="font-size: 12px; color: #0f808a; font-weight: 600; background: #e0f2fe; padding: 4px 10px; border-radius: 20px;">
                        ✨ Dinamis: Item Dapat Ditambah &amp; Dihapus
                    </span>
                </div>
                <div style="display: grid; gap: 16px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge</label>
                        <input type="text" name="fds_layanan_badge" value="<?php echo esc_attr($c['layanan_badge']); ?>" style="width: 100%; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama</label>
                        <input type="text" name="fds_layanan_title" value="<?php echo esc_attr($c['layanan_title']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Deskripsi Layanan</label>
                        <textarea name="fds_layanan_desc" rows="2" style="width: 100%; font-size: 13px;"><?php echo esc_textarea($c['layanan_desc']); ?></textarea>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Teks Tombol CTA</label>
                            <input type="text" name="fds_layanan_cta_text" value="<?php echo esc_attr($c['layanan_cta_text']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Target URL Tombol</label>
                            <input type="text" name="fds_layanan_cta_url" value="<?php echo esc_attr($c['layanan_cta_url']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0;">Daftar Item Layanan (Tampil di Beranda &amp; Mega Menu Dropdown):</h3>
                    <button type="button" class="button button-primary" onclick="fdsAddLayananItem()" style="font-size: 12px; font-weight: 600;">
                        + Tambah Layanan Baru
                    </button>
                </div>

                <div id="fds-layanan-repeater-container" style="display: grid; gap: 14px;">
                    <?php 
                    $current_layanan_items = fds_get_layanan_items();
                    foreach($current_layanan_items as $lIdx => $lItem): 
                    ?>
                    <div class="fds-layanan-row" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; position: relative;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-size: 12px; font-weight: 700; color: #0f808a;">Item Layanan #<span class="layanan-number"><?php echo ($lIdx + 1); ?></span></span>
                            <div style="display: flex; gap: 6px;">
                                <button type="button" class="button button-small" onclick="fdsMoveLayanan(this, -1)" title="Pindah ke atas">▲</button>
                                <button type="button" class="button button-small" onclick="fdsMoveLayanan(this, 1)" title="Pindah ke bawah">▼</button>
                                <button type="button" class="button button-small button-link-delete" onclick="fdsDeleteLayanan(this)" style="color: #dc2626; margin-left: 6px;">🗑️ Hapus</button>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; margin-bottom: 8px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Judul Layanan</label>
                                <input type="text" name="fds_layanan_item_title[]" value="<?php echo esc_attr($lItem['title']); ?>" style="width: 100%; font-weight: 600; font-size: 13px;" required placeholder="Contoh: Pemetaan Aerial & GIS">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Grup Menu Dropdown</label>
                                <select name="fds_layanan_item_group[]" style="width: 100%; font-size: 12px;">
                                    <option value="Pelatihan & Operasional" <?php selected($lItem['group'] ?? '', 'Pelatihan & Operasional'); ?>>Pelatihan &amp; Operasional</option>
                                    <option value="Survei & Inspeksi Teknis" <?php selected($lItem['group'] ?? '', 'Survei & Inspeksi Teknis'); ?>>Survei &amp; Inspeksi Teknis</option>
                                    <option value="Foto Udara & LiDAR" <?php selected($lItem['group'] ?? '', 'Foto Udara & LiDAR'); ?>>Foto Udara &amp; LiDAR</option>
                                    <option value="Konsultan GIS" <?php selected($lItem['group'] ?? '', 'Konsultan GIS'); ?>>Konsultan GIS</option>
                                    <option value="Pemetaan Kawasan" <?php selected($lItem['group'] ?? '', 'Pemetaan Kawasan'); ?>>Pemetaan Kawasan</option>
                                    <option value="Produsen UAV" <?php selected($lItem['group'] ?? '', 'Produsen UAV'); ?>>Produsen UAV</option>
                                    <option value="Lainnya" <?php selected($lItem['group'] ?? '', 'Lainnya'); ?>>Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Link Target (URL)</label>
                                <input type="text" name="fds_layanan_item_url[]" value="<?php echo esc_attr($lItem['url'] ?? ''); ?>" style="width: 100%; font-size: 12px;" placeholder="/layanan/ atau /kontak/">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Deskripsi Ringkas</label>
                            <textarea name="fds_layanan_item_desc[]" rows="2" style="width: 100%; font-size: 12px;" placeholder="Tuliskan deskripsi ringkas layanan..."><?php echo esc_textarea($lItem['desc'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 14px;">
                    <button type="button" class="button button-secondary" onclick="fdsAddLayananItem()" style="font-size: 12px; font-weight: 600;">
                        + Tambah Item Layanan Lainnya
                    </button>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 7: NEWSROOM -->
            <!-- ========================================================= -->
            <div id="tab-blog" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    7. Section Newsroom / Blog
                </h2>
                <div style="display: grid; gap: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge Sub-Heading</label>
                        <input type="text" name="fds_blog_badge" value="<?php echo esc_attr($c['blog_badge']); ?>" style="width: 100%; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama</label>
                        <input type="text" name="fds_blog_title" value="<?php echo esc_attr($c['blog_title']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Teks Tautan "Lihat semua artikel"</label>
                        <input type="text" name="fds_blog_cta_text" value="<?php echo esc_attr($c['blog_cta_text']); ?>" style="width: 100%; font-size: 13px;">
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- TAB 8: KONTAK & FORM -->
            <!-- ========================================================= -->
            <div id="tab-kontak" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    8. Section Kontak &amp; Enterprise Sales Form
                </h2>

                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                        <div>
                            <strong style="color: #166534; font-size: 13px; display: block; margin-bottom: 2px;">📍 Informasi Kontak &amp; Lokasi Terpusat</strong>
                            <span style="color: #15803d; font-size: 12px;">Alamat Workshop, Telepon/WA, Email Resmi, Link WhatsApp, dan Google Maps dikelola secara terpusat di menu <strong>Kontak &amp; Sosmed</strong>.</span>
                        </div>
                        <a href="admin.php?page=fds-footer-settings" class="button button-secondary" style="font-size: 12px; font-weight: 600; white-space: nowrap;">
                            ⚙️ Kelola Kontak &amp; Sosmed
                        </a>
                    </div>
                </div>

                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge</label>
                        <input type="text" name="fds_kontak_badge" value="<?php echo esc_attr($c['kontak_badge']); ?>" style="width: 100%; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama</label>
                        <textarea name="fds_kontak_title" rows="2" style="width: 100%; font-size: 14px; font-weight: 600;"><?php echo esc_textarea($c['kontak_title']); ?></textarea>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Deskripsi Kontak</label>
                        <textarea name="fds_kontak_desc" rows="2" style="width: 100%; font-size: 13px;"><?php echo esc_textarea($c['kontak_desc']); ?></textarea>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Teks Tombol WhatsApp</label>
                        <input type="text" name="fds_kontak_wa_text" value="<?php echo esc_attr($c['kontak_wa_text']); ?>" style="width: 100%; font-size: 13px;">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Form Inquiry</label>
                            <input type="text" name="fds_kontak_form_title" value="<?php echo esc_attr($c['kontak_form_title']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Teks Tombol Submit Form</label>
                            <input type="text" name="fds_kontak_form_btn_text" value="<?php echo esc_attr($c['kontak_form_btn_text']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Panduan / Deskripsi Form Inquiry</label>
                        <input type="text" name="fds_kontak_form_desc" value="<?php echo esc_attr($c['kontak_form_desc']); ?>" style="width: 100%; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Catatan Disclaimer Bawah Form</label>
                        <textarea name="fds_kontak_form_note" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['kontak_form_note']); ?></textarea>
                    </div>

                    <div style="padding: 16px 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-top: 10px;">
                        <span style="font-size: 12px; font-weight: 700; color: #0f808a; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">🗺️ Pengaturan Tampilan Google Maps</span>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #1e293b; cursor: pointer;">
                            <input type="checkbox" name="fds_show_map_home" value="1" <?php checked($c['show_map_home'], true); ?>>
                            Tampilkan Google Maps Interaktif di Bagian Bawah Halaman Beranda
                        </label>
                        <span style="display: block; font-size: 12px; color: #64748b; margin-top: 4px; margin-left: 24px;">URL peta diambil dari menu terpusat <strong>Kontak &amp; Sosmed</strong>.</span>
                    </div>
                </div>
            </div>

            <!-- TOMBOL SIMPAN GLOBAL -->
            <div style="position: sticky; bottom: 20px; background: #fff; padding: 16px 24px; border-radius: 10px; box-shadow: 0 -4px 20px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; z-index: 100;">
                <span style="font-size: 13px; color: #64748b;">Perubahan akan langsung tampil di halaman depan website.</span>
                <button type="submit" name="fds_content_save" class="button button-primary button-large" style="background: #0f808a; border-color: #0f808a; font-weight: 600; padding: 4px 24px;">
                    💾 Simpan Seluruh Konten Beranda
                </button>
            </div>

        </form>
    </div>

    <!-- JS TABS & MEDIA UPLOADER -->
    <script>
    jQuery(document).ready(function($) {
        // Tab switcher
        $('#fds-content-tabs .tab-btn').on('click', function() {
            var target = $(this).data('tab');
            $('#fds-content-tabs .tab-btn').css({
                'background': '#fff',
                'color': '#475569',
                'border-color': '#e2e8f0'
            }).removeClass('active');
            $(this).css({
                'background': '#0f808a',
                'color': '#fff',
                'border-color': '#0f808a'
            }).addClass('active');

            $('.tab-content').hide();
            $('#' + target).show();
        });

        // Media Uploader untuk Slide Hero
        $(document).on('click', '.btn-upload-slide', function(e) {
            e.preventDefault();
            var item = $(this).closest('.slide-item');
            var customUploader = wp.media({
                title: 'Pilih atau Unggah Gambar Slide Hero',
                button: { text: 'Gunakan Gambar Ini' },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();
                item.find('.slide-url-input').val(attachment.url);
                item.find('.slide-preview').attr('src', attachment.url);
            }).open();
        });

        // Hapus Slide
        $(document).on('click', '.btn-remove-slide', function() {
            if ($('#slides-container .slide-item').length > 1) {
                $(this).closest('.slide-item').remove();
            } else {
                alert('Minimal harus ada 1 slide gambar.');
            }
        });

        // Tambah Slide Baru
        $('#btn-add-slide').on('click', function() {
            var customUploader = wp.media({
                title: 'Pilih atau Unggah Gambar Slide Baru',
                button: { text: 'Tambahkan Slide' },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();
                var html = '<div class="slide-item" style="display: grid; grid-template-columns: 120px 1fr auto; gap: 16px; align-items: center; padding: 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">' +
                    '<img src="' + attachment.url + '" class="slide-preview" style="width: 120px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; background: #e2e8f0;">' +
                    '<div>' +
                        '<input type="hidden" name="fds_slide_url[]" class="slide-url-input" value="' + attachment.url + '">' +
                        '<div style="margin-bottom: 6px;"><input type="text" name="fds_slide_title[]" value="" placeholder="Judul / Keterangan Slide" style="width: 100%; font-size: 13px;"></div>' +
                        '<input type="text" name="fds_slide_alt[]" value="" placeholder="Alt text gambar (SEO)" style="width: 100%; font-size: 12px;">' +
                    '</div>' +
                    '<div style="display: flex; flex-direction: column; gap: 6px;">' +
                        '<button type="button" class="button btn-upload-slide" style="font-size: 12px;">Ganti Gambar</button>' +
                        '<button type="button" class="button btn-remove-slide" style="font-size: 12px; color: #dc2626; border-color: #fca5a5;">Hapus</button>' +
                    '</div>' +
                '</div>';
                $('#slides-container').append(html);
            }).open();
        });

        // Media Uploader untuk Kartu Solusi
        $(document).on('click', '.btn-upload-card-img', function(e) {
            e.preventDefault();
            var parent = $(this).closest('div');
            var imgBox = $(this).closest('div[style*="display: flex"]');
            var customUploader = wp.media({
                title: 'Pilih atau Unggah Gambar Kartu Solusi',
                button: { text: 'Gunakan Gambar Ini' },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();
                imgBox.find('.card-image-input').val(attachment.url);
                imgBox.find('.card-preview-img').attr('src', attachment.url);
            }).open();
        });

        // Media Uploader untuk Bento Card 1 (Pabrik & Workshop)
        $('#btn-upload-bento-card1').on('click', function(e) {
            e.preventDefault();
            var customUploader = wp.media({
                title: 'Pilih atau Unggah Gambar Background Bento Hero',
                button: { text: 'Gunakan Gambar Ini' },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();
                $('#fds_keunggulan_card1_img').val(attachment.url);
                $('#bento-card1-preview').attr('src', attachment.url);
            }).open();
        });

        $('#btn-remove-bento-card1').on('click', function(e) {
            e.preventDefault();
            var defaultImg = <?php echo wp_json_encode(get_template_directory_uri() . '/public/images/site/workshop-deltav.webp'); ?>;
            $('#fds_keunggulan_card1_img').val(defaultImg);
            $('#bento-card1-preview').attr('src', defaultImg);
        });

        // Dynamic Repeater untuk Layanan Enterprise
        window.fdsAddLayananItem = function() {
            var count = $('#fds-layanan-repeater-container .fds-layanan-row').length + 1;
            var html = '<div class="fds-layanan-row" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; position: relative;">' +
                '<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">' +
                    '<span style="font-size: 12px; font-weight: 700; color: #0f808a;">Item Layanan #<span class="layanan-number">' + count + '</span></span>' +
                    '<div style="display: flex; gap: 6px;">' +
                        '<button type="button" class="button button-small" onclick="fdsMoveLayanan(this, -1)" title="Pindah ke atas">▲</button>' +
                        '<button type="button" class="button button-small" onclick="fdsMoveLayanan(this, 1)" title="Pindah ke bawah">▼</button>' +
                        '<button type="button" class="button button-small button-link-delete" onclick="fdsDeleteLayanan(this)" style="color: #dc2626; margin-left: 6px;">🗑️ Hapus</button>' +
                    '</div>' +
                '</div>' +
                '<div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 12px; margin-bottom: 8px;">' +
                    '<div>' +
                        '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Judul Layanan</label>' +
                        '<input type="text" name="fds_layanan_item_title[]" value="" style="width: 100%; font-weight: 600; font-size: 13px;" required placeholder="Contoh: Pemetaan Aerial & GIS">' +
                    '</div>' +
                    '<div>' +
                        '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Grup Menu Dropdown</label>' +
                        '<select name="fds_layanan_item_group[]" style="width: 100%; font-size: 12px;">' +
                            '<option value="Pelatihan & Operasional">Pelatihan &amp; Operasional</option>' +
                            '<option value="Survei & Inspeksi Teknis">Survei &amp; Inspeksi Teknis</option>' +
                            '<option value="Foto Udara & LiDAR">Foto Udara &amp; LiDAR</option>' +
                            '<option value="Konsultan GIS">Konsultan GIS</option>' +
                            '<option value="Pemetaan Kawasan">Pemetaan Kawasan</option>' +
                            '<option value="Produsen UAV">Produsen UAV</option>' +
                            '<option value="Lainnya">Lainnya</option>' +
                        '</select>' +
                    '</div>' +
                    '<div>' +
                        '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Link Target (URL)</label>' +
                        '<input type="text" name="fds_layanan_item_url[]" value="/layanan/" style="width: 100%; font-size: 12px;" placeholder="/layanan/ atau /kontak/">' +
                    '</div>' +
                '</div>' +
                '<div>' +
                    '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Deskripsi Ringkas</label>' +
                    '<textarea name="fds_layanan_item_desc[]" rows="2" style="width: 100%; font-size: 12px;" placeholder="Tuliskan deskripsi ringkas layanan..."></textarea>' +
                '</div>' +
            '</div>';
            $('#fds-layanan-repeater-container').append(html);
            fdsRenumberLayanan();
        };

        window.fdsDeleteLayanan = function(btn) {
            if ($('#fds-layanan-repeater-container .fds-layanan-row').length <= 1) {
                alert('Minimal harus ada 1 item layanan.');
                return;
            }
            if (confirm('Yakin ingin menghapus item layanan ini?')) {
                $(btn).closest('.fds-layanan-row').remove();
                fdsRenumberLayanan();
            }
        };

        window.fdsMoveLayanan = function(btn, direction) {
            var row = $(btn).closest('.fds-layanan-row');
            if (direction === -1) {
                var prev = row.prev('.fds-layanan-row');
                if (prev.length) {
                    row.insertBefore(prev);
                    fdsRenumberLayanan();
                }
            } else if (direction === 1) {
                var next = row.next('.fds-layanan-row');
                if (next.length) {
                    row.insertAfter(next);
                    fdsRenumberLayanan();
                }
            }
        };

        window.fdsRenumberLayanan = function() {
            $('#fds-layanan-repeater-container .fds-layanan-row').each(function(idx) {
                $(this).find('.layanan-number').text(idx + 1);
            });
        };

        // Dynamic Repeater untuk Kartu Solusi Industri
        var fdsDefaultSolusiCards = <?php
            $sync_default_cards = function_exists(__NAMESPACE__ . '\\fds_get_default_solusi_cards') ? fds_get_default_solusi_cards() : [];
            echo wp_json_encode($sync_default_cards, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        ?>;

        function fdsEscHtml(str) {
            return String(str || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        window.fdsBuildSolusiCardHtml = function(card, count) {
            var img = card.image || <?php echo wp_json_encode(get_template_directory_uri() . '/public/images/site/hero-1.webp'); ?>;
            return '<div class="fds-solusi-card-item" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; position: relative;">' +
                '<input type="hidden" name="fds_card_num[]" value="' + fdsEscHtml(card.num || '') + '">' +
                '<input type="hidden" name="fds_card_slug[]" value="' + fdsEscHtml(card.slug || '') + '">' +
                '<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">' +
                    '<h4 style="margin: 0; font-size: 14px; font-weight: 700; color: #0f808a;">Kartu Solusi #<span class="solusi-number">' + count + '</span></h4>' +
                    '<div style="display: flex; gap: 6px;">' +
                        '<button type="button" class="button button-small" onclick="fdsMoveSolusiCard(this, -1)" title="Pindah ke atas">▲</button>' +
                        '<button type="button" class="button button-small" onclick="fdsMoveSolusiCard(this, 1)" title="Pindah ke bawah">▼</button>' +
                        '<button type="button" class="button button-small button-link-delete" onclick="fdsDeleteSolusiCard(this)" style="color: #dc2626; margin-left: 6px;">🗑️ Hapus</button>' +
                    '</div>' +
                '</div>' +
                '<div style="margin-bottom: 12px;">' +
                    '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Gambar Kartu</label>' +
                    '<div style="display: flex; gap: 10px; align-items: center;">' +
                        '<img src="' + fdsEscHtml(img) + '" class="card-preview-img" style="width: 100px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; background: #e2e8f0;">' +
                        '<div style="flex: 1;">' +
                            '<input type="hidden" name="fds_card_image[]" class="card-image-input" value="' + fdsEscHtml(img) + '">' +
                            '<button type="button" class="button btn-upload-card-img" style="font-size: 11px; margin-bottom: 6px;">Pilih Gambar</button>' +
                            '<input type="text" name="fds_card_image_alt[]" value="' + fdsEscHtml(card.image_alt || '') + '" placeholder="Alt text gambar (SEO)" style="width: 100%; font-size: 11.5px;">' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div style="margin-bottom: 10px;">' +
                    '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Judul Kartu</label>' +
                    '<input type="text" name="fds_card_title[]" value="' + fdsEscHtml(card.title || '') + '" style="width: 100%; font-size: 13px; font-weight: 600;" required placeholder="Contoh: Pertambangan">' +
                '</div>' +
                '<div style="margin-bottom: 10px;">' +
                    '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Deskripsi Kartu</label>' +
                    '<textarea name="fds_card_desc[]" rows="3" style="width: 100%; font-size: 12px;" placeholder="Tuliskan deskripsi ringkas solusi...">' + fdsEscHtml(card.desc || '') + '</textarea>' +
                '</div>' +
                '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">' +
                    '<div>' +
                        '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Tag Internal / Catatan (Opsional — tidak tampil di kartu beranda)</label>' +
                        '<input type="text" name="fds_card_tag[]" value="' + fdsEscHtml(card.tag || '') + '" placeholder="Pertambangan • LiDAR" style="width: 100%; font-size: 12px;">' +
                    '</div>' +
                    '<div>' +
                        '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Teks Link</label>' +
                        '<input type="text" name="fds_card_link_text[]" value="' + fdsEscHtml(card.link_text !== undefined ? card.link_text : 'Pelajari solusi sektor') + '" style="width: 100%; font-size: 12px;">' +
                    '</div>' +
                '</div>' +
                '<div>' +
                    '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Target URL Link</label>' +
                    '<input type="text" name="fds_card_link_url[]" value="' + fdsEscHtml(card.link_url || '/layanan/') + '" placeholder="/layanan/sektor/" style="width: 100%; font-size: 12px;">' +
                '</div>' +
            '</div>';
        };

        $('#fds-solusi-cards-container').on('input change', 'input, textarea', function() {
            $('#fds_solusi_resync').val('0');
        });

        window.fdsSyncSolusiCardsFromSectors = function() {
            if (!fdsDefaultSolusiCards || !fdsDefaultSolusiCards.length) return;
            if (!confirm('Muat ulang seluruh kartu solusi dari 6 Sektor Hub (/layanan/)? Klik Simpan di bagian bawah setelahnya untuk menerapkan.')) {
                return;
            }
            var container = $('#fds-solusi-cards-container');
            container.empty();
            $.each(fdsDefaultSolusiCards, function(idx, card) {
                container.append(fdsBuildSolusiCardHtml(card, idx + 1));
            });
            $('#fds_solusi_resync').val('1');
        };

        window.fdsAddSolusiCard = function() {
            var count = $('#fds-solusi-cards-container .fds-solusi-card-item').length + 1;
            var html = fdsBuildSolusiCardHtml({
                num: '',
                slug: '',
                image: <?php echo wp_json_encode(get_template_directory_uri() . '/public/images/site/hero-1.webp'); ?>,
                image_alt: '',
                title: 'Judul Solusi Baru',
                desc: '',
                tag: 'FDS UAV',
                link_text: 'Pelajari solusi sektor',
                link_url: '/layanan/'
            }, count);
            $('#fds-solusi-cards-container').append(html);
            $('#fds_solusi_resync').val('0');
            fdsRenumberSolusiCards();
        };

        window.fdsDeleteSolusiCard = function(btn) {
            if ($('#fds-solusi-cards-container .fds-solusi-card-item').length <= 1) {
                alert('Minimal harus ada 1 kartu solusi.');
                return;
            }
            if (confirm('Yakin ingin menghapus kartu solusi ini?')) {
                $(btn).closest('.fds-solusi-card-item').remove();
                $('#fds_solusi_resync').val('0');
                fdsRenumberSolusiCards();
            }
        };

        window.fdsMoveSolusiCard = function(btn, direction) {
            var row = $(btn).closest('.fds-solusi-card-item');
            if (direction === -1) {
                var prev = row.prev('.fds-solusi-card-item');
                if (prev.length) {
                    row.insertBefore(prev);
                    $('#fds_solusi_resync').val('0');
                    fdsRenumberSolusiCards();
                }
            } else if (direction === 1) {
                var next = row.next('.fds-solusi-card-item');
                if (next.length) {
                    row.insertAfter(next);
                    $('#fds_solusi_resync').val('0');
                    fdsRenumberSolusiCards();
                }
            }
        };

        window.fdsRenumberSolusiCards = function() {
            $('#fds-solusi-cards-container .fds-solusi-card-item').each(function(idx) {
                $(this).find('.solusi-number').text(idx + 1);
            });
        };
    });
    </script>
    <?php
}
