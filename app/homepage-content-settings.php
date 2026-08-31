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

// 3. AUTO-CLEANUP & INITIALIZATION (Run once)
add_action('init', function () {
    if (get_option('fds_cleanup_hp_done_v1')) {
        return;
    }

    // Clean raw HTML Tailwind classes from options
    $cleanup_keys = [
        'fds_hero_title', 'fds_hero_desc',
        'fds_solusi_title', 'fds_solusi_desc',
        'fds_keunggulan_title', 'fds_keunggulan_card1_desc',
        'fds_produk_desc', 'fds_layanan_desc',
    ];
    foreach ($cleanup_keys as $k) {
        $val = get_option($k);
        if ($val && strpos($val, 'class="') !== false) {
            $cleaned = preg_replace('/\s*(class|style)="[^"]*"/', '', $val);
            $cleaned = preg_replace('/<strong\s*>/', '<strong>', $cleaned);
            $cleaned = preg_replace('/<em\s*>/', '<em>', $cleaned);
            update_option($k, $cleaned);
        }
    }

    // Clean fds_solusi_cards if corrupted with Warning
    $raw_cards = get_option('fds_solusi_cards');
    if (is_array($raw_cards)) {
        $modified = false;
        foreach ($raw_cards as $k => $c) {
            foreach (['tag', 'title', 'desc', 'image', 'link_text', 'link_url'] as $field) {
                if (isset($c[$field]) && (strpos($c[$field], 'Warning</b>') !== false || strpos($c[$field], 'Warning:') !== false)) {
                    $raw_cards[$k][$field] = '';
                    $modified = true;
                }
            }
        }
        if ($modified) {
            update_option('fds_solusi_cards', $raw_cards);
        }
    }

    update_option('fds_cleanup_hp_done_v1', 1);
});

// 4. HELPER DATA KONTEN BERANDA
function fds_get_default_layanan_items() {
    return [
        [
            'title' => 'Pemetaan Aerial & GIS',
            'desc'  => 'Peta topografi resolusi tinggi dengan akurasi sub-sentimeter untuk perencanaan lahan, kehutanan, dan infrastruktur.',
            'url'   => home_url('/#layanan'),
            'group' => 'Survei & Inspeksi Teknis',
        ],
        [
            'title' => 'Inspeksi Industri & Infrastruktur',
            'desc'  => 'Pemeriksaan visual dan termal berbasis UAV untuk pemantauan fasilitas energi, kelistrikan, migas, dan infrastruktur kritis secara cepat dan aman tanpa menghentikan operasional.',
            'url'   => home_url('/#layanan'),
            'group' => 'Survei & Inspeksi Teknis',
        ],
        [
            'title' => 'Sewa Armada Drone',
            'desc'  => 'Armada FERTO siap pakai untuk proyek jangka pendek, pilot project, atau kebutuhan peak season tanpa investasi unit penuh.',
            'url'   => home_url('/#kontak'),
            'group' => 'Pelatihan & Operasional',
        ],
        [
            'title' => 'Pelatihan & Sertifikasi Pilot',
            'desc'  => 'Program pelatihan pilot drone bersertifikat resmi untuk tim lapangan Anda. Kurikulum mencakup misi agrikultur, pemetaan, dan inspeksi.',
            'url'   => home_url('/#layanan'),
            'group' => 'Pelatihan & Operasional',
        ],
        [
            'title' => 'After-Sales & Maintenance',
            'desc'  => 'Layanan purna jual lokal dengan stok suku cadang, teknisi bersertifikat, dan garansi resmi di seluruh Indonesia.',
            'url'   => home_url('/#kontak'),
            'group' => 'Pelatihan & Operasional',
        ],
    ];
}

function fds_get_layanan_items() {
    $saved = get_option('fds_layanan_items', null);
    if ($saved === null || !is_array($saved) || empty($saved)) {
        // Cek migrasi dari format legacy
        $legacy = [];
        for ($i = 1; $i <= 5; $i++) {
            $t = get_option("fds_layanan_item{$i}_title", '');
            $d = get_option("fds_layanan_item{$i}_desc", '');
            if (!empty($t)) {
                $grp = ($i <= 2) ? 'Survei & Inspeksi Teknis' : 'Pelatihan & Operasional';
                $url = ($i === 3 || $i === 5) ? home_url('/#kontak') : home_url('/#layanan');
                $legacy[] = [
                    'title' => $t,
                    'desc'  => $d,
                    'url'   => $url,
                    'group' => $grp,
                ];
            }
        }
        if (!empty($legacy)) {
            return $legacy;
        }
        return fds_get_default_layanan_items();
    }

    $items = [];
    foreach ($saved as $item) {
        if (!empty($item['title'])) {
            $items[] = [
                'title' => wp_specialchars_decode($item['title'], ENT_QUOTES),
                'desc'  => wp_specialchars_decode($item['desc'] ?? '', ENT_QUOTES),
                'url'   => !empty($item['url']) ? $item['url'] : home_url('/#layanan'),
                'group' => !empty($item['group']) ? $item['group'] : 'Pelatihan & Operasional',
            ];
        }
    }
    return !empty($items) ? $items : fds_get_default_layanan_items();
}

function fds_get_homepage_content() {
    $data = [
        // HERO
        'hero_badge'        => get_option('fds_hero_badge', 'Teknologi UAV Indonesia'),
        'hero_title'        => get_option('fds_hero_title', "Solusi Drone Industrial\nuntuk Setiap Sektor."),
        'hero_desc'         => get_option('fds_hero_desc', 'Teknologi udara berstandar industri, diproduksi lokal oleh PT Karya Solusi Angkasa (FDS) di Yogyakarta.'),
        'hero_cta1_text'    => get_option('fds_hero_cta1_text', 'Jelajahi Solusi Kami'),
        'hero_cta1_url'     => get_option('fds_hero_cta1_url', '#solusi'),
        'hero_cta2_text'    => get_option('fds_hero_cta2_text', 'Konsultasi Enterprise'),
        'hero_cta2_url'     => get_option('fds_hero_cta2_url', '#kontak'),

        // MITRA
        'mitra_heading'     => get_option('fds_mitra_heading', 'Dipercaya &amp; Digunakan Oleh Berbagai Institusi Terkemuka'),

        // SOLUSI INDUSTRI
        'solusi_badge'      => get_option('fds_solusi_badge', 'Solusi Industri FDS'),
        'solusi_title'      => get_option('fds_solusi_title', 'Satu platform. Berbagai industri strategis.'),
        'solusi_desc'       => get_option('fds_solusi_desc', 'Solusi rekayasa UAV terintegrasi hardware, software FDS STATION, sensor AI, dan layanan operasional bersertifikasi untuk efisiensi maksimal di lapangan.'),

        // PRODUK DRONE
        'produk_badge'      => get_option('fds_produk_badge', 'Lini Produk UAV'),
        'produk_title'      => get_option('fds_produk_title', 'Ekosistem Drone FDS.'),
        'produk_desc'       => get_option('fds_produk_desc', 'Rangkaian platform UAV bersertifikasi SNI &amp; TKDN untuk kebutuhan agrikultur, pemetaan, inspeksi, dan misi berat.'),
        'produk_stat1_num'  => get_option('fds_produk_stat1_num', 'SNI'),
        'produk_stat1_lbl'  => get_option('fds_produk_stat1_lbl', 'SNI 9199:2023 Resmi'),
        'produk_stat2_num'  => get_option('fds_produk_stat2_num', '60,74%'),
        'produk_stat2_lbl'  => get_option('fds_produk_stat2_lbl', 'TKDN + BMP Kemenperin'),
        'produk_stat3_num'  => get_option('fds_produk_stat3_num', '100%'),
        'produk_stat3_lbl'  => get_option('fds_produk_stat3_lbl', 'FDS Station GCS'),
        'produk_stat4_num'  => get_option('fds_produk_stat4_num', '2012'),
        'produk_stat4_lbl'  => get_option('fds_produk_stat4_lbl', 'Pengalaman Industri UAV'),

        // KEUNGGULAN (BENTO GRID)
        'keunggulan_badge'       => get_option('fds_keunggulan_badge', 'Mengapa FDS'),
        'keunggulan_title'       => get_option('fds_keunggulan_title', 'Keunggulan yang tidak bisa dikompromikan.'),
        
        'keunggulan_card1_badge' => get_option('fds_keunggulan_card1_badge', 'Rekayasa & Manufaktur'),
        'keunggulan_card1_title' => get_option('fds_keunggulan_card1_title', "Desain Aerodinamis &\nAvionik In-House."),
        'keunggulan_card1_desc'  => get_option('fds_keunggulan_card1_desc', 'Rangka karbon komposit lokal, avionik in-house, dan integrasi payload kustom di workshop PT Karya Solusi Angkasa (FDS).'),
        'keunggulan_card1_img'   => get_option('fds_keunggulan_card1_img', get_option('fds_img_keunggulan', 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=1200&q=80')),

        'keunggulan_card2_badge' => get_option('fds_keunggulan_card2_badge', 'Sertifikasi TKDN + BMP'),
        'keunggulan_card2_stat'  => get_option('fds_keunggulan_card2_stat', '60,74%'),
        'keunggulan_card2_desc'  => get_option('fds_keunggulan_card2_desc', 'Nilai TKDN + Bobot Manfaat Perusahaan resmi Kementerian Perindustrian RI.'),

        'keunggulan_card3_badge' => get_option('fds_keunggulan_card3_badge', 'Software'),
        'keunggulan_card3_title' => get_option('fds_keunggulan_card3_title', "FDS STATION\nGround Control GCS"),
        'keunggulan_card3_desc'  => get_option('fds_keunggulan_card3_desc', 'Perencanaan misi otomatis dan pemantauan real-time berbahasa Indonesia.'),

        'keunggulan_card4_badge' => get_option('fds_keunggulan_card4_badge', 'Standar & Mutu'),
        'keunggulan_card4_stat'  => get_option('fds_keunggulan_card4_stat', 'ISO & SNI'),
        'keunggulan_card4_desc'  => get_option('fds_keunggulan_card4_desc', 'Tersertifikasi ISO 9001:2015 dan Standar Nasional Indonesia SNI 9199:2023.'),

        'keunggulan_card5_badge' => get_option('fds_keunggulan_card5_badge', 'After-Sales'),
        'keunggulan_card5_title' => get_option('fds_keunggulan_card5_title', 'Purna Jual & Suku Cadang'),
        'keunggulan_card5_desc'  => get_option('fds_keunggulan_card5_desc', 'Pelatihan pilot berlisensi, servis berkala, dan spare parts siap kirim dari Yogyakarta.'),

        'keunggulan_card6_badge' => get_option('fds_keunggulan_card6_badge', 'Pengalaman Industri'),
        'keunggulan_card6_stat'  => get_option('fds_keunggulan_card6_stat', '2012'),
        'keunggulan_card6_desc'  => get_option('fds_keunggulan_card6_desc', 'Berpengalaman di industri UAV sejak 2012, resmi berbadan hukum PT sejak 2019.'),

        'keunggulan_card7_badge' => get_option('fds_keunggulan_card7_badge', 'Cakupan Industri'),
        'keunggulan_card7_title' => get_option('fds_keunggulan_card7_title', 'Satu ekosistem. Banyak solusi.'),
        'keunggulan_card7_desc'  => get_option('fds_keunggulan_card7_desc', 'Agrikultur, pemetaan topografi, inspeksi infrastruktur, kehutanan, dan pertambangan.'),

        // LAYANAN ENTERPRISE
        'layanan_badge'          => get_option('fds_layanan_badge', 'Layanan'),
        'layanan_title'          => get_option('fds_layanan_title', 'Lebih dari sekadar hardware.'),
        'layanan_desc'           => get_option('fds_layanan_desc', 'Kami menyediakan layanan operasional lengkap untuk memastikan investasi drone Anda memberikan hasil maksimal.'),
        'layanan_cta_text'       => get_option('fds_layanan_cta_text', 'Diskusi Kebutuhan Anda'),
        'layanan_cta_url'        => get_option('fds_layanan_cta_url', '#kontak'),

        // NEWSROOM
        'blog_badge'             => get_option('fds_blog_badge', 'Newsroom'),
        'blog_title'             => get_option('fds_blog_title', 'Berita & Pembaruan Terkini.'),
        'blog_cta_text'          => get_option('fds_blog_cta_text', 'Lihat semua artikel'),

        // KONTAK / INQUIRY
        'kontak_badge'           => get_option('fds_kontak_badge', 'Enterprise Sales'),
        'kontak_title'           => get_option('fds_kontak_title', "Hubungi tim\nEnterprise FDS."),
        'kontak_desc'            => get_option('fds_kontak_desc', 'Dari konsultasi teknis, fleet management, hingga program sertifikasi — kami siap mendampingi operasional drone Anda.'),
        'kontak_wa_text'         => get_option('fds_kontak_wa_text', 'Chat via WhatsApp'),
        'kontak_form_title'      => get_option('fds_kontak_form_title', 'Kirim pesan inquiry'),
        'kontak_form_btn_text'   => get_option('fds_kontak_form_btn_text', 'Kirim Pesan'),
        'kontak_form_note'       => get_option('fds_kontak_form_note', "Kami merespons dalam 1×24 jam kerja.\nData Anda tidak akan dibagikan ke pihak ketiga."),
    ];

    // Decode semua HTML entities berulang (&amp;amp; → &amp; → &, dll)
    // agar template Blade tidak pernah menerima entity ganda.
    return array_map(function($v) {
        if (!is_string($v)) return $v;
        $prev = '';
        while ($prev !== $v) {
            $prev = $v;
            $v = wp_specialchars_decode($v, ENT_QUOTES);
        }
        return $v;
    }, $data);
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
            'fds_solusi_badge', 'fds_solusi_title',
            'fds_produk_badge', 'fds_produk_title', 'fds_produk_stat1_num', 'fds_produk_stat1_lbl', 'fds_produk_stat2_num', 'fds_produk_stat2_lbl', 'fds_produk_stat3_num', 'fds_produk_stat3_lbl', 'fds_produk_stat4_num', 'fds_produk_stat4_lbl',
            'fds_keunggulan_badge', 'fds_keunggulan_title', 'fds_keunggulan_card1_badge', 'fds_keunggulan_card1_img', 'fds_keunggulan_card2_badge', 'fds_keunggulan_card2_stat', 'fds_keunggulan_card3_badge', 'fds_keunggulan_card4_badge', 'fds_keunggulan_card4_stat', 'fds_keunggulan_card5_badge', 'fds_keunggulan_card5_title', 'fds_keunggulan_card6_badge', 'fds_keunggulan_card6_stat', 'fds_keunggulan_card7_badge', 'fds_keunggulan_card7_title',
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
            'fds_kontak_title', 'fds_kontak_desc', 'fds_kontak_form_note',
        ];

        foreach ($fields_text as $f) {
            update_option($f, sanitize_text_field($_POST[$f] ?? ''));
        }

        if (!empty($_POST['fds_keunggulan_card1_img'])) {
            update_option('fds_img_keunggulan', esc_url_raw($_POST['fds_keunggulan_card1_img']));
        }

        foreach ($fields_textarea as $f) {
            update_option($f, sanitize_textarea_field($_POST[$f] ?? ''));
        }

        // 2. Simpan Slide Hero
        if (isset($_POST['fds_slide_url']) && is_array($_POST['fds_slide_url'])) {
            $saved_slides = [];
            foreach ($_POST['fds_slide_url'] as $idx => $url) {
                $clean_url = esc_url_raw($url);
                if (!empty($clean_url)) {
                    $saved_slides[] = [
                        'url'   => $clean_url,
                        'title' => sanitize_text_field($_POST['fds_slide_title'][$idx] ?? ''),
                        'alt'   => sanitize_text_field($_POST['fds_slide_alt'][$idx] ?? 'Full Drone Solutions Hero Slide'),
                    ];
                }
            }
            update_option('fds_hero_slides', $saved_slides);
        }

        // 3. Simpan Kartu Solusi Industri
        if (isset($_POST['fds_card_title']) && is_array($_POST['fds_card_title'])) {
            $saved_cards = [];
            foreach ($_POST['fds_card_title'] as $idx => $title) {
                $saved_cards[] = [
                    'image'     => esc_url_raw($_POST['fds_card_image'][$idx] ?? ''),
                    'title'     => sanitize_text_field($title),
                    'desc'      => sanitize_textarea_field($_POST['fds_card_desc'][$idx] ?? ''),
                    'tag'       => sanitize_text_field($_POST['fds_card_tag'][$idx] ?? ''),
                    'link_text' => sanitize_text_field($_POST['fds_card_link_text'][$idx] ?? 'Pelajari Selengkapnya'),
                    'link_url'  => sanitize_text_field($_POST['fds_card_link_url'][$idx] ?? '#kontak'),
                ];
            }
            update_option('fds_solusi_cards', $saved_cards);
        }

        // 4. Simpan Item Layanan Enterprise Dinamis (Bisa Ditambah & Dikurangi)
        if (isset($_POST['fds_layanan_item_title']) && is_array($_POST['fds_layanan_item_title'])) {
            $saved_layanan = [];
            foreach ($_POST['fds_layanan_item_title'] as $idx => $title) {
                $t = sanitize_text_field($title);
                if (!empty($t)) {
                    $saved_layanan[] = [
                        'title' => $t,
                        'desc'  => sanitize_textarea_field($_POST['fds_layanan_item_desc'][$idx] ?? ''),
                        'url'   => sanitize_text_field($_POST['fds_layanan_item_url'][$idx] ?? ''),
                        'group' => sanitize_text_field($_POST['fds_layanan_item_group'][$idx] ?? 'Pelatihan & Operasional'),
                    ];
                }
            }
            update_option('fds_layanan_items', $saved_layanan);
        }

        $message = 'Semua perubahan konten Beranda berhasil disimpan!';
    }

    $c = fds_get_homepage_content();
    $raw_slides = function_exists('App\fds_get_hero_slides') ? fds_get_hero_slides() : (get_option('fds_hero_slides', []));
    if (empty($raw_slides)) {
        $raw_slides = [
            ['url' => 'https://images.unsplash.com/photo-1527011046414-4781f1f94f8c?auto=format&fit=crop&w=1920&q=80', 'title' => 'Solusi Drone Industrial untuk Berbagai Sektor', 'alt' => 'Full Drone Solutions'],
            ['url' => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=1920&q=80', 'title' => 'Teknologi Presisi Pertanian & Perkebunan', 'alt' => 'Drone Pertanian'],
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

    $raw_cards = function_exists('App\fds_get_solusi_data') ? fds_get_solusi_data()['cards'] : (get_option('fds_solusi_cards', []));
    if (empty($raw_cards) && function_exists('App\fds_get_default_solusi_cards')) {
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
                'image'     => $c_item['image'] ?? '',
                'title'     => $c_item['title'] ?? '',
                'desc'      => $c_item['desc'] ?? '',
                'tag'       => $tag_val,
                'link_text' => $c_item['link_text'] ?? 'Pelajari Selengkapnya',
                'link_url'  => $c_item['link_url'] ?? '#kontak',
            ];
        }
    }
    ?>
    <div class="wrap" style="max-width: 100%; margin-top: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; box-sizing: border-box;">
        <div style="background: #fff; padding: 24px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 8px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #0066cc; display: flex; align-items: center; justify-content: center; color: #fff;">
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
                <button type="button" class="tab-btn active" data-tab="tab-hero" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #0066cc; background: #0066cc; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer;">1. Hero &amp; Slider</button>
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
                <div style="display: grid; gap: 16px; margin-bottom: 28px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge Sub-Heading</label>
                        <input type="text" name="fds_solusi_badge" value="<?php echo esc_attr(get_option('fds_solusi_badge', 'Solusi Industri FDS')); ?>" style="width: 100%; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama Solusi</label>
                        <input type="text" name="fds_solusi_title" value="<?php echo esc_attr(get_option('fds_solusi_title', 'Satu platform. Berbagai industri strategis.')); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Deskripsi Solusi</label>
                        <textarea name="fds_solusi_desc" rows="2" style="width: 100%; font-size: 13px;"><?php echo esc_textarea(get_option('fds_solusi_desc', 'Solusi rekayasa UAV terintegrasi hardware, software FDS STATION, sensor AI, dan layanan operasional bersertifikasi untuk efisiensi maksimal di lapangan.')); ?></textarea>
                    </div>
                </div>

                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 20px; margin-bottom: 16px; border-top: 1px solid #f1f5f9; pt-16;">
                    🃏 4 Kartu Solusi Industri
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <?php foreach ($solusi_cards as $idx => $card): ?>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px;">
                        <h4 style="margin: 0 0 12px; font-size: 14px; font-weight: 700; color: #0066cc;">Kartu Solusi #<?php echo ($idx + 1); ?></h4>
                        
                        <!-- GAMBAR SOLUSI -->
                        <div style="margin-bottom: 12px;">
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Gambar Kartu</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <img src="<?php echo esc_url($card['image'] ?? ''); ?>" class="card-preview-img" style="width: 100px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; background: #e2e8f0;">
                                <div>
                                    <input type="hidden" name="fds_card_image[]" class="card-image-input" value="<?php echo esc_attr($card['image'] ?? ''); ?>">
                                    <button type="button" class="button btn-upload-card-img" style="font-size: 11px;">Pilih Gambar</button>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Judul Kartu</label>
                            <input type="text" name="fds_card_title[]" value="<?php echo esc_attr($card['title'] ?? ''); ?>" style="width: 100%; font-size: 13px; font-weight: 600;">
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Deskripsi Kartu</label>
                            <textarea name="fds_card_desc[]" rows="3" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($card['desc'] ?? ''); ?></textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Tag Produk / Spek</label>
                                <input type="text" name="fds_card_tag[]" value="<?php echo esc_attr($card['tag'] ?? ''); ?>" placeholder="FERTO 5L – 50L" style="width: 100%; font-size: 12px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Teks Link</label>
                                <input type="text" name="fds_card_link_text[]" value="<?php echo esc_attr($card['link_text'] ?? 'Pelajari Selengkapnya'); ?>" style="width: 100%; font-size: 12px;">
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Target URL Link</label>
                            <input type="text" name="fds_card_link_url[]" value="<?php echo esc_attr($card['link_url'] ?? '#kontak'); ?>" placeholder="#kontak" style="width: 100%; font-size: 12px;">
                        </div>
                    </div>
                    <?php endforeach; ?>
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
                        <h4 style="margin: 0 0 10px; font-size: 14px; font-weight: 700; color: #0066cc;">Card 1: Manufaktur Lokal &amp; Desain Aerodinamis (Kartu Hero Besar Bento)</h4>
                        
                        <!-- GAMBAR BACKGROUND BENTO HERO -->
                        <div style="margin-bottom: 14px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px;">🖼️ Gambar Background Kartu Hero Bento (Pabrik / Workshop / Drone)</label>
                            <div style="display: flex; gap: 14px; align-items: center;">
                                <img id="bento-card1-preview" src="<?php echo esc_url($c['keunggulan_card1_img']); ?>" style="width: 140px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; background: #e2e8f0;">
                                <div>
                                    <input type="hidden" id="fds_keunggulan_card1_img" name="fds_keunggulan_card1_img" value="<?php echo esc_attr($c['keunggulan_card1_img']); ?>">
                                    <button type="button" id="btn-upload-bento-card1" class="button button-primary" style="font-size: 12px; background: #0066cc; border-color: #0066cc;">Pilih / Unggah Gambar</button>
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
                        <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700;">Card 2: TKDN &amp; BMP</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 8px;">
                            <input type="text" name="fds_keunggulan_card2_badge" value="<?php echo esc_attr($c['keunggulan_card2_badge']); ?>" placeholder="Badge">
                            <input type="text" name="fds_keunggulan_card2_stat" value="<?php echo esc_attr($c['keunggulan_card2_stat']); ?>" placeholder="Nilai Stat (60,74%)">
                        </div>
                        <textarea name="fds_keunggulan_card2_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card2_desc']); ?></textarea>
                    </div>

                    <!-- Card 3 -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                        <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700;">Card 3: Software GCS</h4>
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 12px; margin-bottom: 8px;">
                            <input type="text" name="fds_keunggulan_card3_badge" value="<?php echo esc_attr($c['keunggulan_card3_badge']); ?>" placeholder="Badge">
                            <input type="text" name="fds_keunggulan_card3_title" value="<?php echo esc_attr($c['keunggulan_card3_title']); ?>" placeholder="Judul">
                        </div>
                        <textarea name="fds_keunggulan_card3_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card3_desc']); ?></textarea>
                    </div>

                    <!-- Card 4, 5, 6, 7 -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                            <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700;">Card 4: Standar ISO &amp; SNI</h4>
                            <input type="text" name="fds_keunggulan_card4_stat" value="<?php echo esc_attr($c['keunggulan_card4_stat']); ?>" style="width: 100%; margin-bottom: 6px;">
                            <textarea name="fds_keunggulan_card4_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card4_desc']); ?></textarea>
                        </div>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                            <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700;">Card 5: Purna Jual &amp; Suku Cadang</h4>
                            <input type="text" name="fds_keunggulan_card5_title" value="<?php echo esc_attr($c['keunggulan_card5_title']); ?>" style="width: 100%; margin-bottom: 6px;">
                            <textarea name="fds_keunggulan_card5_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card5_desc']); ?></textarea>
                        </div>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                            <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700;">Card 6: Pengalaman Industri (2012)</h4>
                            <input type="text" name="fds_keunggulan_card6_stat" value="<?php echo esc_attr($c['keunggulan_card6_stat']); ?>" style="width: 100%; margin-bottom: 6px;">
                            <textarea name="fds_keunggulan_card6_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card6_desc']); ?></textarea>
                        </div>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
                            <h4 style="margin: 0 0 8px; font-size: 13px; font-weight: 700;">Card 7: Ekosistem Multi-Sektor</h4>
                            <input type="text" name="fds_keunggulan_card7_title" value="<?php echo esc_attr($c['keunggulan_card7_title']); ?>" style="width: 100%; margin-bottom: 6px;">
                            <textarea name="fds_keunggulan_card7_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card7_desc']); ?></textarea>
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
                    <span style="font-size: 12px; color: #0066cc; font-weight: 600; background: #e0f2fe; padding: 4px 10px; border-radius: 20px;">
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
                            <span style="font-size: 12px; font-weight: 700; color: #0066cc;">Item Layanan #<span class="layanan-number"><?php echo ($lIdx + 1); ?></span></span>
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
                                    <option value="Lainnya" <?php selected($lItem['group'] ?? '', 'Lainnya'); ?>>Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Link Target (URL)</label>
                                <input type="text" name="fds_layanan_item_url[]" value="<?php echo esc_attr($lItem['url'] ?? ''); ?>" style="width: 100%; font-size: 12px;" placeholder="#layanan atau #kontak">
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
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Catatan Disclaimer Bawah Form</label>
                        <textarea name="fds_kontak_form_note" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['kontak_form_note']); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- TOMBOL SIMPAN GLOBAL -->
            <div style="position: sticky; bottom: 20px; background: #fff; padding: 16px 24px; border-radius: 10px; box-shadow: 0 -4px 20px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; z-index: 100;">
                <span style="font-size: 13px; color: #64748b;">Perubahan akan langsung tampil di halaman depan website.</span>
                <button type="submit" name="fds_content_save" class="button button-primary button-large" style="background: #0066cc; border-color: #0066cc; font-weight: 600; padding: 4px 24px;">
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
                'background': '#0066cc',
                'color': '#fff',
                'border-color': '#0066cc'
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
            var defaultImg = 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=1200&q=80';
            $('#fds_keunggulan_card1_img').val(defaultImg);
            $('#bento-card1-preview').attr('src', defaultImg);
        });

        // Dynamic Repeater untuk Layanan Enterprise
        window.fdsAddLayananItem = function() {
            var count = $('#fds-layanan-repeater-container .fds-layanan-row').length + 1;
            var html = '<div class="fds-layanan-row" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; position: relative;">' +
                '<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">' +
                    '<span style="font-size: 12px; font-weight: 700; color: #0066cc;">Item Layanan #<span class="layanan-number">' + count + '</span></span>' +
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
                            '<option value="Lainnya">Lainnya</option>' +
                        '</select>' +
                    '</div>' +
                    '<div>' +
                        '<label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 2px;">Link Target (URL)</label>' +
                        '<input type="text" name="fds_layanan_item_url[]" value="#layanan" style="width: 100%; font-size: 12px;" placeholder="#layanan atau #kontak">' +
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
    });
    </script>
    <?php
}
