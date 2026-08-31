<?php

namespace App;

/**
 * =========================================================================
 * FDS THEME AUTO-PROVISIONING & AUTO-SEEDER ENGINE
 * =========================================================================
 * Otomatis mengonfigurasi halaman inti (Beranda, Tentang Kami, Bandingkan, Blog),
 * direktori cache Blade, permalink, CPT Drone (10 model), dan data default
 * begitu tema diaktifkan di WordPress hosting baru.
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Ensure Acorn Blade view cache directory exists and is writable
 */
function fds_ensure_view_cache_dir() {
    $cache_paths = [
        WP_CONTENT_DIR . '/cache/acorn/framework/views',
        WP_CONTENT_DIR . '/cache/acorn/framework/cache',
    ];
    foreach ($cache_paths as $p) {
        if (!is_dir($p)) {
            wp_mkdir_p($p);
        }
    }
}


/**
 * Run full auto-setup on theme activation or on initial load if not yet initialized
 */
function fds_run_theme_auto_provision() {
    fds_ensure_view_cache_dir();

    // Pastikan permalink ramah SEO (/%postname%/)
    $current_permalink = get_option('permalink_structure');
    if (empty($current_permalink) || $current_permalink === '') {
        update_option('permalink_structure', '/%postname%/');
    }

    // 1. BUAT HALAMAN BERANDA (Front Page)
    $front_page = get_page_by_path('beranda');
    if (!$front_page) {
        $front_id = wp_insert_post([
            'post_title'   => 'Beranda',
            'post_name'    => 'beranda',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ]);
    } else {
        $front_id = $front_page->ID;
    }

    if ($front_id && !is_wp_error($front_id)) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $front_id);
    }

    // 2. BUAT HALAMAN BLOG (Posts Page)
    $blog_page = get_page_by_path('blog');
    if (!$blog_page) {
        $blog_id = wp_insert_post([
            'post_title'   => 'Blog & Berita',
            'post_name'    => 'blog',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ]);
    } else {
        $blog_id = $blog_page->ID;
    }

    if ($blog_id && !is_wp_error($blog_id)) {
        update_option('page_for_posts', $blog_id);
    }

    // 3. BUAT HALAMAN TENTANG KAMI
    $about_page = get_page_by_path('tentang-kami');
    if (!$about_page) {
        wp_insert_post([
            'post_title'   => 'Tentang Kami',
            'post_name'    => 'tentang-kami',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ]);
    }

    // 4. BUAT HALAMAN BANDINGKAN
    $bandingkan_page = get_page_by_path('bandingkan');
    if (!$bandingkan_page) {
        wp_insert_post([
            'post_title'   => 'Bandingkan Spesifikasi Drone',
            'post_name'    => 'bandingkan',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ]);
    }

    // 5. SEED DEFAULT MITRA LOGOS JIKA KOSONG
    $mitra_count = wp_count_posts('mitra');
    if (empty($mitra_count->publish) || $mitra_count->publish == 0) {
        $default_mitras = [
            'Bappenas RI', 'Pemerintah Australia (PRISMA)', 'Bank Indonesia',
            'Universitas Gadjah Mada (UGM)', 'Kementerian Pertanian', 'PT Perkebunan Nusantara (PTPN)',
            'Mitra Konservasi Swiss', 'Pupuk Indonesia'
        ];
        foreach ($default_mitras as $m_name) {
            $existing_m = get_page_by_path(sanitize_title($m_name), OBJECT, 'mitra');
            if (!$existing_m) {
                wp_insert_post([
                    'post_title'   => $m_name,
                    'post_name'    => sanitize_title($m_name),
                    'post_status'  => 'publish',
                    'post_type'    => 'mitra',
                ]);
            }
        }
    }

    // 6. SEED DEFAULT HERO SLIDER JIKA BELUM ADA
    if (!get_option('fds_hero_slides')) {
        $default_hero_slides = [
            [
                'badge'      => 'Platform UAV Pertanian Presisi',
                'title'      => 'FERTO Series — Efisiensi Semprot & Sebar Maksimal.',
                'desc'       => 'Drone pertanian berstandar SNI 9199:2023 dengan kapasitas tangki 5L hingga 50L. Produktivitas tinggi hingga 15 Ha/jam dan efisiensi bahan kimia >50%.',
                'img'        => 'https://images.unsplash.com/photo-1527011046414-4781f1f94f8c?auto=format&fit=crop&w=1920&q=80',
                'btn1_text'  => 'Lihat Katalog Drone',
                'btn1_url'   => '#katalog',
                'btn2_text'  => 'Konsultasi Tim FDS',
                'btn2_url'   => '#kontak',
            ],
            [
                'badge'      => 'Platform Pemetaan & Inspeksi',
                'title'      => 'DELTAV VTOL & MULTIPURPOSE UAV.',
                'desc'       => 'Pesawat sayap tetap VTOL jangkauan 60 km untuk pemetaan ortofoto skala luas, dan drone modular dengan sensor termal untuk inspeksi transmisi 150 kV.',
                'img'        => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=1920&q=80',
                'btn1_text'  => 'Pelajari DELTAV',
                'btn1_url'   => home_url('/drone/deltav/'),
                'btn2_text'  => 'Bandingkan Model',
                'btn2_url'   => home_url('/bandingkan/'),
            ],
            [
                'badge'      => 'Kemandirian Teknologi Nasional',
                'title'      => 'Inovasi Kedirgantaraan Berstandar TKDN & SNI Resmi.',
                'desc'       => 'Pencapaian nilai TKDN + BMP Kemenperin hingga 60,74%, sertifikasi ISO 9001:2015, dan SNI 9199:2023 dengan workshop riset & manufaktur lokal di Yogyakarta.',
                'img'        => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=1920&q=80',
                'btn1_text'  => 'Tentang FDS',
                'btn1_url'   => home_url('/tentang-kami/'),
                'btn2_text'  => 'Aktivitas & Kemitraan',
                'btn2_url'   => home_url('/tentang-kami/#aktivitas'),
            ],
        ];
        update_option('fds_hero_slides', $default_hero_slides);
    }

    // 7. SEED DEFAULT CONTACT & ADDRESS
    if (!get_option('fds_contact_address')) {
        update_option('fds_contact_company_name', 'PT Karya Solusi Angkasa (Full Drone Solutions)');
        update_option('fds_contact_address', 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281');
        update_option('fds_contact_email', 'marketing@fulldronesolutions.com');
        update_option('fds_contact_phone', '+62 8112 748 882');
        update_option('fds_contact_wa_link', 'https://wa.me/628112748882');
        update_option('fds_contact_maps_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid');
    }

    // Flush rewrite rules
    flush_rewrite_rules(false);
    update_option('fds_theme_auto_setup_done_v1', 1);
}

// Hook ke theme activation & init
add_action('after_switch_theme', __NAMESPACE__ . '\\fds_run_theme_auto_provision');

add_action('init', function () {
    if (!get_option('fds_theme_auto_setup_done_v1')) {
        fds_run_theme_auto_provision();
    }
}, 20);
