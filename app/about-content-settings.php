<?php

namespace App;

/**
 * =========================================================================
 * FDS ABOUT CONTENT MANAGER (PT KARYA SOLUSI ANGKASA)
 * =========================================================================
 * Mengelola semua teks dan gambar section halaman Tentang Kami
 * (Hero, Statistik, Cerita Kami, Spektrum Teknologi, Kemitraan Klien,
 * Sertifikasi Mutu, dan CTA Workshop).
 */

// 1. DAFTARKAN MENU DI WP ADMIN
add_action('admin_menu', function () {
    add_menu_page(
        'Konten Tentang Kami',
        'Konten Tentang Kami',
        'manage_options',
        'fds-about-content',
        __NAMESPACE__ . '\\render_about_content_admin_page',
        'dashicons-building',
        26
    );
});

// Auto-sync / migrate 'Aktivitas Kami' & Contact content
add_action('init', function () {
    $current_title = get_option('fds_about_mitra_title');
    if (empty($current_title) || $current_title === 'Dipercaya oleh institusi negara, BUMN, dan korporasi terkemuka.' || $current_title === 'Kemitraan & Klien Strategis' || $current_title === 'Our Activity') {
        update_option('fds_about_mitra_badge', 'Aktivitas Kami');
        update_option('fds_about_mitra_title', 'Aktivitas Kami');
        update_option('fds_about_mitra_desc', 'Riset mandiri, inovasi manufaktur lokal, serta kolaborasi strategis bersama institusi nasional dan mitra internasional.');

        update_option('fds_about_mitra_item1_cat', 'Riset & Manufaktur');
        update_option('fds_about_mitra_item1_name', 'Produksi dan Manufaktur');
        update_option('fds_about_mitra_item1_desc', 'Proses produksi dan Riset Development seluruhnya dilakukan di Workshop Full Drone Solutions dengan Tenaga kerja yang diserap dari putra putri daerah pilihan asal Yogyakarta dan Sekitarnya.');

        update_option('fds_about_mitra_item2_cat', 'Program PRISMA 2024');
        update_option('fds_about_mitra_item2_name', 'Kolaborasi Bappenas - Pemerintah Australia');
        update_option('fds_about_mitra_item2_desc', 'Pada tahun 2024, Full Drone Solutions (FDS) dipercaya menjadi mitra strategis dalam program PRISMA untuk berkontribusi pada pengembangan sektor Teknologi Informasi dan Komunikasi, khususnya dalam mengenalkan dan mengimplementasikan teknologi Drone Sprayer kepada petani untuk meningkatkan efisiensi, produktivitas, serta meningkatkan kesejahteraan petani.');

        update_option('fds_about_mitra_item3_cat', 'Ketahanan Pangan & Inflasi');
        update_option('fds_about_mitra_item3_name', 'Kolaborasi Full Drone Solutions (FDS) dengan Bank Indonesia');
        update_option('fds_about_mitra_item3_desc', 'Kolaborasi strategis antara Full Drone Solutions (FDS) dengan Bank Indonesia (BI) menghadirkan inovasi berupa teknologi drone pertanian yang bertujuan memperkuat ketahanan pangan sekaligus mengurangi inflasi.');

        update_option('fds_about_mitra_item4_cat', 'Konservasi & Reboisasi');
        update_option('fds_about_mitra_item4_name', 'Kolaborasi Full Drone Solutions (FDS) – UGM - Mitra dari Swiss');
        update_option('fds_about_mitra_item4_desc', 'Full Drone Solutions (FDS) menjalin kolaborasi strategis bersama Universitas Gadjah Mada (UGM) dan mitra internasional dari Swiss dalam proyek pengembangan teknologi reboisasi dan penghijauan untuk kawasan mangrove dan hutan hujan tropis.');

        update_option('fds_about_mitra_item5_cat', '');
        update_option('fds_about_mitra_item5_name', '');
        update_option('fds_about_mitra_item5_desc', '');

        update_option('fds_about_mitra_item6_cat', '');
        update_option('fds_about_mitra_item6_name', '');
        update_option('fds_about_mitra_item6_desc', '');

        update_option('fds_about_mitra_item7_cat', '');
        update_option('fds_about_mitra_item7_name', '');
        update_option('fds_about_mitra_item7_desc', '');
    }

    // Sync Workshop Contact & Map Info
    $current_email = get_option('fds_about_info_email');
    if (empty($current_email) || $current_email === 'info@fulldronesolutions.com' || $current_email === 'sales@fulldronesolutions.co.id') {
        update_option('fds_about_info_alamat', 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281');
        update_option('fds_about_info_email', 'marketing@fulldronesolutions.com');
        update_option('fds_about_info_phone', '+62 8112 748 882');
        update_option('fds_about_info_maps', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid');
    }

    // Auto-clean raw HTML classes from text fields
    $cleanup_keys = [
        'fds_about_hero_title', 'fds_about_hero_desc',
        'fds_about_story_title', 'fds_about_story_p1', 'fds_about_story_p2', 'fds_about_story_p3', 'fds_about_story_p4',
        'fds_about_spektrum_title', 'fds_about_spektrum1_desc', 'fds_about_spektrum2_desc', 'fds_about_spektrum3_desc',
        'fds_about_mitra_title', 'fds_about_mitra_desc',
        'fds_about_certs_title', 'fds_about_cta_title', 'fds_about_cta_desc',
    ];
    foreach ($cleanup_keys as $k) {
        $val = get_option($k);
        if ($val && (strpos($val, 'class="') !== false || strpos($val, 'style="') !== false)) {
            $cleaned = preg_replace('/\s*(class|style)="[^"]*"/', '', $val);
            $cleaned = preg_replace('/<strong\s*>/', '<strong>', $cleaned);
            $cleaned = preg_replace('/<em\s*>/', '<em>', $cleaned);
            update_option($k, $cleaned);
        }
    }
});

// 2. HELPER GET ABOUT CONTENT
function fds_get_about_content() {
    $data = [
        // HERO
        'hero_sub'        => get_option('fds_about_hero_sub', 'PT Karya Solusi Angkasa (Full Drone Solutions) &middot; Pengalaman UAV Sejak 2012 &middot; Yogyakarta'),
        'hero_title'      => get_option('fds_about_hero_title', "Advanced UAV Engineering,\nManufacturing & AI Technology."),
        'hero_desc'       => get_option('fds_about_hero_desc', 'Berpengalaman di industri UAV sejak 2012 dan resmi berbadan hukum PT pada 2019. Kami merancang desain aerodinamis, struktur avionik in-house, rangka karbon lokal, serta analitik AI untuk kemandirian teknologi udara Indonesia.'),
        'hero_img'        => get_option('fds_about_hero_img', ''),

        // STATS
        'stat1_num'       => get_option('fds_about_stat1_num', '2012'),
        'stat1_lbl'       => get_option('fds_about_stat1_lbl', 'Pengalaman UAV (PT Sejak 2019)'),
        'stat2_num'       => get_option('fds_about_stat2_num', '60,74%'),
        'stat2_lbl'       => get_option('fds_about_stat2_lbl', 'Nilai TKDN + BMP Kemenperin'),
        'stat3_num'       => get_option('fds_about_stat3_num', 'ISO & SNI'),
        'stat3_lbl'       => get_option('fds_about_stat3_lbl', 'ISO 9001:2015 & SNI 9199:2023'),
        'stat4_num'       => get_option('fds_about_stat4_num', '100%'),
        'stat4_lbl'       => get_option('fds_about_stat4_lbl', 'Rekayasa & Software Lokal'),

        // CERITA KAMI
        'story_badge'     => get_option('fds_about_story_badge', 'Cerita Kami'),
        'story_title'     => get_option('fds_about_story_title', 'Rekayasa UAV mandiri untuk masa depan industri Indonesia.'),
        'story_img'       => get_option('fds_about_story_img', ''),
        'story_p1'        => get_option('fds_about_story_p1', 'Perjalanan kami dimulai pada tahun 2012 dari sebuah riset aeromodelling dan rekayasa wahana terbang tanpa awak. Didorong oleh komitmen kuat terhadap kemandirian teknologi nasional, tim kami resmi berbadan hukum pada tahun 2019 sebagai PT Karya Solusi Angkasa dengan merek dagang Full Drone Solutions (FDS).'),
        'story_p2'        => get_option('fds_about_story_p2', 'Berpusat di Sleman, Daerah Istimewa Yogyakarta, FDS mengintegrasikan fasilitas perancangan aerodinamika, pencetakan rangka karbon komposit lokal, perakitan avionik in-house, serta pengembangan perangkat lunak sistem kendali stasiun darat (GCS) berbahasa Indonesia.'),
        'story_p3'        => get_option('fds_about_story_p3', 'Kami adalah pionir produsen drone pertanian presisi di Indonesia dengan sertifikasi Standar Nasional Indonesia (SNI 9199:2023) dan mengantongi sertifikat Manajemen Mutu Internasional ISO 9001:2015.'),
        'story_p4'        => get_option('fds_about_story_p4', 'Melalui sertifikasi Tingkat Komponen Dalam Negeri (TKDN) dan Bobot Manfaat Perusahaan (BMP) dari Kementerian Perindustrian RI yang menembus 60,74%, FDS membuktikan bahwa inovasi kedirgantaraan berstandar global lahir dari tangan talenta terbaik bangsa.'),
        'story_cta_text'  => get_option('fds_about_story_cta_text', 'Lihat kemitraan strategis & portofolio klien'),
        'story_cta_url'   => get_option('fds_about_story_cta_url', '#aktivitas'),

        // SPEKTRUM TEKNOLOGI
        'spektrum_badge'  => get_option('fds_about_spektrum_badge', 'Spektrum Teknologi UAV'),
        'spektrum_title'  => get_option('fds_about_spektrum_title', 'Tiga arsitektur wahana udara untuk segala medan.'),
        
        'spektrum1_title' => get_option('fds_about_spektrum1_title', 'Rotary Wing (Multirotor)'),
        'spektrum1_desc'  => get_option('fds_about_spektrum1_desc', 'Kemampuan Vertical Takeoff and Landing (VTOL), kontrol posisi presisi tinggi, dan hovering super stabil untuk misi penyemprotan agrikultur serta inspeksi infrastruktur rapat.'),

        'spektrum2_title' => get_option('fds_about_spektrum2_title', 'Fixed Wing (Sayap Tetap)'),
        'spektrum2_desc'  => get_option('fds_about_spektrum2_desc', 'Dirancang untuk misi jarak jauh, daya tahan terbang tinggi (endurance), dan cakupan area pemetaan luas yang efisien dalam satu sorti penerbangan.'),

        'spektrum3_title' => get_option('fds_about_spektrum3_title', 'Hybrid VTOL (DELTAV)'),
        'spektrum3_desc'  => get_option('fds_about_spektrum3_desc', 'Menggabungkan fleksibilitas peluncuran vertikal tanpa landasan dengan kecepatan jelajah 15–22 m/s dan jangkauan 60 km untuk akuisisi geospasial presisi.'),

        // AKTIVITAS & KEMITRAAN
        'mitra_badge'     => get_option('fds_about_mitra_badge', 'Aktivitas Kami'),
        'mitra_title'     => get_option('fds_about_mitra_title', 'Aktivitas Kami'),
        'mitra_desc'      => get_option('fds_about_mitra_desc', 'Riset mandiri, inovasi manufaktur lokal, serta kolaborasi strategis bersama institusi nasional dan mitra internasional.'),

        'mitra_item1_cat' => get_option('fds_about_mitra_item1_cat', 'Riset & Manufaktur'),
        'mitra_item1_name'=> get_option('fds_about_mitra_item1_name', 'Produksi dan Manufaktur'),
        'mitra_item1_desc'=> get_option('fds_about_mitra_item1_desc', 'Proses produksi dan Riset Development seluruhnya dilakukan di Workshop Full Drone Solutions dengan Tenaga kerja yang diserap dari putra putri daerah pilihan asal Yogyakarta dan Sekitarnya.'),

        'mitra_item2_cat' => get_option('fds_about_mitra_item2_cat', 'Program PRISMA 2024'),
        'mitra_item2_name'=> get_option('fds_about_mitra_item2_name', 'Kolaborasi Bappenas - Pemerintah Australia'),
        'mitra_item2_desc'=> get_option('fds_about_mitra_item2_desc', 'Pada tahun 2024, Full Drone Solutions (FDS) dipercaya menjadi mitra strategis dalam program PRISMA untuk berkontribusi pada pengembangan sektor Teknologi Informasi dan Komunikasi, khususnya dalam mengenalkan dan mengimplementasikan teknologi Drone Sprayer kepada petani untuk meningkatkan efisiensi, produktivitas, serta meningkatkan kesejahteraan petani.'),

        'mitra_item3_cat' => get_option('fds_about_mitra_item3_cat', 'Ketahanan Pangan & Inflasi'),
        'mitra_item3_name'=> get_option('fds_about_mitra_item3_name', 'Kolaborasi Full Drone Solutions (FDS) dengan Bank Indonesia'),
        'mitra_item3_desc'=> get_option('fds_about_mitra_item3_desc', 'Kolaborasi strategis antara Full Drone Solutions (FDS) dengan Bank Indonesia (BI) menghadirkan inovasi berupa teknologi drone pertanian yang bertujuan memperkuat ketahanan pangan sekaligus mengurangi inflasi.'),

        'mitra_item4_cat' => get_option('fds_about_mitra_item4_cat', 'Konservasi & Reboisasi'),
        'mitra_item4_name'=> get_option('fds_about_mitra_item4_name', 'Kolaborasi Full Drone Solutions (FDS) – UGM - Mitra dari Swiss'),
        'mitra_item4_desc'=> get_option('fds_about_mitra_item4_desc', 'Full Drone Solutions (FDS) menjalin kolaborasi strategis bersama Universitas Gadjah Mada (UGM) dan mitra internasional dari Swiss dalam proyek pengembangan teknologi reboisasi dan penghijauan untuk kawasan mangrove dan hutan hujan tropis.'),

        'mitra_item5_cat' => get_option('fds_about_mitra_item5_cat', ''),
        'mitra_item5_name'=> get_option('fds_about_mitra_item5_name', ''),
        'mitra_item5_desc'=> get_option('fds_about_mitra_item5_desc', ''),

        'mitra_item6_cat' => get_option('fds_about_mitra_item6_cat', ''),
        'mitra_item6_name'=> get_option('fds_about_mitra_item6_name', ''),
        'mitra_item6_desc'=> get_option('fds_about_mitra_item6_desc', ''),

        'mitra_item7_cat' => get_option('fds_about_mitra_item7_cat', ''),
        'mitra_item7_name'=> get_option('fds_about_mitra_item7_name', ''),
        'mitra_item7_desc'=> get_option('fds_about_mitra_item7_desc', ''),

        // SERTIFIKASI & MUTU
        'certs_badge'     => get_option('fds_about_certs_badge', 'Sertifikasi & Standar Mutu'),
        'certs_title'     => get_option('fds_about_certs_title', 'Standar mutu global, sertifikasi resmi nasional.'),
        
        'cert1_badge'     => get_option('fds_about_cert1_badge', 'Kemenperin RI'),
        'cert1_val'       => get_option('fds_about_cert1_val', '60,74%'),
        'cert1_desc'      => get_option('fds_about_cert1_desc', 'Nilai TKDN + Bobot Manfaat Perusahaan (BMP) tertinggi di segmen drone industri buatan lokal.'),

        'cert2_badge'     => get_option('fds_about_cert2_badge', 'Standar Produk & Manajemen'),
        'cert2_val'       => get_option('fds_about_cert2_val', 'ISO & SNI'),
        'cert2_desc'      => get_option('fds_about_cert2_desc', 'Sertifikasi ISO 9001:2015 (Manajemen Mutu) dan SNI 9199:2023 (Standar Nasional Drone Pertanian).'),

        'cert3_badge'     => get_option('fds_about_cert3_badge', 'Jaminan Layanan'),
        'cert3_val'       => get_option('fds_about_cert3_val', '24/7'),
        'cert3_desc'      => get_option('fds_about_cert3_desc', 'Dukungan servis, suku cadang asli, dan sertifikasi pilot resmi di seluruh Indonesia.'),

        // CTA & WORKSHOP
        'cta_title'       => get_option('fds_about_cta_title', 'Siap bermitra dengan PT Karya Solusi Angkasa?'),
        'cta_desc'        => get_option('fds_about_cta_desc', 'Baik instansi pemerintah, BUMN, perkebunan agrikultur besar, atau mitra industri — tim engineering kami siap memberikan solusi terbaik.'),
        'cta_btn1_text'   => get_option('fds_about_cta_btn1_text', 'Mulai Konsultasi'),
        'cta_btn1_url'    => get_option('fds_about_cta_btn1_url', '#kontak'),
        'cta_btn2_text'   => get_option('fds_about_cta_btn2_text', 'Baca Studi Kasus ›'),
        'cta_btn2_url'    => get_option('fds_about_cta_btn2_url', '/blog'),

        'info_title'      => get_option('fds_about_info_title', 'Kantor Pusat & Workshop'),
        'info_entitas'    => get_option('fds_about_info_entitas', 'PT Karya Solusi Angkasa (Full Drone Solutions)'),
        'info_alamat'     => get_option('fds_about_info_alamat', 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281'),
        'info_email'      => get_option('fds_about_info_email', 'marketing@fulldronesolutions.com'),
        'info_phone'      => get_option('fds_about_info_phone', '+62 8112 748 882'),
        'info_layanan'    => get_option('fds_about_info_layanan', 'Konsultasi Proyek & Pengadaan Korporasi'),
        'info_maps'       => get_option('fds_about_info_maps', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid'),
    ];

    // Decode semua HTML entities berulang (&amp;amp; → &amp; → &, dll)
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

// 3. RENDER ADMIN PAGE
function render_about_content_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save changes
    $saved = false;
    if (isset($_POST['fds_about_nonce']) && wp_verify_nonce($_POST['fds_about_nonce'], 'fds_about_save')) {
        $keys = [
            // HERO
            'fds_about_hero_sub', 'fds_about_hero_title', 'fds_about_hero_desc', 'fds_about_hero_img',
            // STATS
            'fds_about_stat1_num', 'fds_about_stat1_lbl',
            'fds_about_stat2_num', 'fds_about_stat2_lbl',
            'fds_about_stat3_num', 'fds_about_stat3_lbl',
            'fds_about_stat4_num', 'fds_about_stat4_lbl',
            // CERITA KAMI
            'fds_about_story_badge', 'fds_about_story_title', 'fds_about_story_img',
            'fds_about_story_p1', 'fds_about_story_p2', 'fds_about_story_p3', 'fds_about_story_p4',
            'fds_about_story_cta_text', 'fds_about_story_cta_url',
            // SPEKTRUM
            'fds_about_spektrum_badge', 'fds_about_spektrum_title',
            'fds_about_spektrum1_title', 'fds_about_spektrum1_desc',
            'fds_about_spektrum2_title', 'fds_about_spektrum2_desc',
            'fds_about_spektrum3_title', 'fds_about_spektrum3_desc',
            // MITRA
            'fds_about_mitra_badge', 'fds_about_mitra_title', 'fds_about_mitra_desc',
            'fds_about_mitra_item1_cat', 'fds_about_mitra_item1_name', 'fds_about_mitra_item1_desc',
            'fds_about_mitra_item2_cat', 'fds_about_mitra_item2_name', 'fds_about_mitra_item2_desc',
            'fds_about_mitra_item3_cat', 'fds_about_mitra_item3_name', 'fds_about_mitra_item3_desc',
            'fds_about_mitra_item4_cat', 'fds_about_mitra_item4_name', 'fds_about_mitra_item4_desc',
            'fds_about_mitra_item5_cat', 'fds_about_mitra_item5_name', 'fds_about_mitra_item5_desc',
            'fds_about_mitra_item6_cat', 'fds_about_mitra_item6_name', 'fds_about_mitra_item6_desc',
            'fds_about_mitra_item7_cat', 'fds_about_mitra_item7_name', 'fds_about_mitra_item7_desc',
            // SERTIFIKASI
            'fds_about_certs_badge', 'fds_about_certs_title',
            'fds_about_cert1_badge', 'fds_about_cert1_val', 'fds_about_cert1_desc',
            'fds_about_cert2_badge', 'fds_about_cert2_val', 'fds_about_cert2_desc',
            'fds_about_cert3_badge', 'fds_about_cert3_val', 'fds_about_cert3_desc',
            // CTA & WORKSHOP
            'fds_about_cta_title', 'fds_about_cta_desc',
            'fds_about_cta_btn1_text', 'fds_about_cta_btn1_url',
            'fds_about_cta_btn2_text', 'fds_about_cta_btn2_url',
            'fds_about_info_title', 'fds_about_info_entitas', 'fds_about_info_alamat', 'fds_about_info_email', 'fds_about_info_layanan',
        ];

        foreach ($keys as $k) {
            if (isset($_POST[$k])) {
                // Allow HTML for certain fields
                if (in_array($k, ['fds_about_story_p1', 'fds_about_story_p2', 'fds_about_story_p3', 'fds_about_story_p4', 'fds_about_hero_sub', 'fds_about_hero_title', 'fds_about_story_title'])) {
                    update_option($k, wp_kses_post(wp_unslash($_POST[$k])));
                } else {
                    update_option($k, sanitize_textarea_field(wp_unslash($_POST[$k])));
                }
            }
        }

        // Sinkronisasi otomatis ke Kontak Global
        if (!empty($_POST['fds_about_info_entitas'])) {
            update_option('fds_global_company_name', sanitize_text_field($_POST['fds_about_info_entitas']));
            update_option('fds_footer_company_name', sanitize_text_field($_POST['fds_about_info_entitas']));
        }
        if (!empty($_POST['fds_about_info_alamat'])) {
            update_option('fds_global_address', sanitize_textarea_field($_POST['fds_about_info_alamat']));
            update_option('fds_footer_address', sanitize_textarea_field($_POST['fds_about_info_alamat']));
            update_option('fds_kontak_address', sanitize_textarea_field($_POST['fds_about_info_alamat']));
        }
        if (!empty($_POST['fds_about_info_email'])) {
            update_option('fds_global_email', sanitize_email($_POST['fds_about_info_email']));
            update_option('fds_footer_email', sanitize_email($_POST['fds_about_info_email']));
            update_option('fds_kontak_email', sanitize_email($_POST['fds_about_info_email']));
        }
        if (!empty($_POST['fds_about_info_phone'])) {
            update_option('fds_global_phone', sanitize_text_field($_POST['fds_about_info_phone']));
            update_option('fds_footer_phone', sanitize_text_field($_POST['fds_about_info_phone']));
            update_option('fds_kontak_phone', sanitize_text_field($_POST['fds_about_info_phone']));
        }
        if (!empty($_POST['fds_about_info_maps'])) {
            update_option('fds_global_maps_url', esc_url_raw($_POST['fds_about_info_maps']));
            update_option('fds_kontak_maps', esc_url_raw($_POST['fds_about_info_maps']));
        }

        $saved = true;
    }

    wp_enqueue_media();
    $data = fds_get_about_content();
    ?>
    <div class="wrap fds-about-admin-wrap">
      <h1 class="wp-heading-inline" style="font-weight: 700; color: #1d1d1f; margin-bottom: 8px;">
        ⚙️ Konten Halaman Tentang Kami
      </h1>
      <p style="color: #6e6e73; font-size: 14px; margin-top: 0; margin-bottom: 20px;">
        Sesuaikan seluruh judul, narasi, gambar, statistik, spektrum teknologi, daftar kemitraan, dan info workshop halaman Tentang Kami (FDS).
      </p>

      <?php if ($saved): ?>
        <div class="notice notice-success is-dismissible" style="border-left-color: #0066cc; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
          <p><strong>✅ Perubahan Konten Halaman Tentang Kami berhasil disimpan!</strong></p>
        </div>
      <?php endif; ?>

      <!-- STYLES -->
      <style>
        .fds-about-admin-wrap { max-width: 1200px; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .fds-tabs-nav { display: flex; gap: 8px; border-bottom: 1px solid #d2d2d7; margin-bottom: 24px; overflow-x: auto; padding-bottom: 4px; }
        .fds-tab-btn { background: none; border: none; padding: 10px 18px; font-size: 14px; font-weight: 600; color: #6e6e73; border-radius: 8px 8px 0 0; cursor: pointer; transition: all .15s; border-bottom: 2px solid transparent; }
        .fds-tab-btn:hover { color: #1d1d1f; background: #f5f5f7; }
        .fds-tab-btn.active { color: #0066cc; border-bottom: 2px solid #0066cc; background: #fff; }
        
        .fds-card { background: #fff; border: 1px solid #e5e5ea; border-radius: 12px; padding: 24px 28px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .fds-card h2 { margin-top: 0; font-size: 17px; font-weight: 700; color: #1d1d1f; border-bottom: 1px solid #f0f0f2; padding-bottom: 12px; margin-bottom: 18px; }
        .fds-card h3 { font-size: 14px; font-weight: 700; color: #1d1d1f; margin: 18px 0 10px; }
        
        .fds-field { margin-bottom: 18px; }
        .fds-field label { display: block; font-weight: 600; font-size: 13px; color: #1d1d1f; margin-bottom: 6px; }
        .fds-field input[type="text"], .fds-field input[type="url"], .fds-field textarea {
          width: 100%; border: 1px solid #d2d2d7; border-radius: 8px; padding: 9px 12px; font-size: 14px; color: #1d1d1f; box-sizing: border-box; transition: border-color .15s;
        }
        .fds-field input[type="text"]:focus, .fds-field input[type="url"]:focus, .fds-field textarea:focus {
          border-color: #0066cc; outline: none; box-shadow: 0 0 0 3px rgba(0,102,204,0.15);
        }
        .fds-field p.description { font-size: 12px; color: #86868b; margin-top: 4px; }
        .fds-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .fds-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .fds-grid-4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 16px; }
        
        .fds-img-preview-box { display: flex; align-items: center; gap: 16px; margin-top: 8px; }
        .fds-img-preview { width: 140px; height: 90px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e5ea; background: #f5f5f7; }
        
        .fds-submit-bar { position: sticky; bottom: 20px; background: #fff; border: 1px solid #d2d2d7; border-radius: 12px; padding: 14px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; z-index: 100; }
      </style>

      <form method="POST" action="">
        <?php wp_nonce_field('fds_about_save', 'fds_about_nonce'); ?>

        <!-- TAB NAVIGATION -->
        <div class="fds-tabs-nav">
          <button type="button" class="fds-tab-btn active" data-tab="tab-hero">1. Hero &amp; Statistik</button>
          <button type="button" class="fds-tab-btn" data-tab="tab-cerita">2. Cerita Kami &amp; Narasi</button>
          <button type="button" class="fds-tab-btn" data-tab="tab-spektrum">3. Spektrum Teknologi</button>
          <button type="button" class="fds-tab-btn" data-tab="tab-mitra">4. Kemitraan &amp; Klien</button>
          <button type="button" class="fds-tab-btn" data-tab="tab-sertifikasi">5. Sertifikasi &amp; Mutu</button>
          <button type="button" class="fds-tab-btn" data-tab="tab-cta">6. CTA &amp; Workshop</button>
        </div>

        <!-- ========================================================= -->
        <!-- TAB 1: HERO & STATISTIK -->
        <!-- ========================================================= -->
        <div class="fds-tab-pane" id="tab-hero">
          <div class="fds-card">
            <h2>🎯 Section Hero (Header Gelap)</h2>
            <div class="fds-field">
              <label>Sub-Headline / Tagline Atas</label>
              <input type="text" name="fds_about_hero_sub" value="<?php echo esc_attr($data['hero_sub']); ?>">
              <p class="description">Contoh: PT Karya Solusi Angkasa (Full Drone Solutions) &middot; Pengalaman UAV Sejak 2012 &middot; Yogyakarta</p>
            </div>

            <div class="fds-field">
              <label>Judul Utama (H1)</label>
              <textarea name="fds_about_hero_title" rows="2"><?php echo esc_textarea($data['hero_title']); ?></textarea>
              <p class="description">Gunakan enter untuk baris baru.</p>
            </div>

            <div class="fds-field">
              <label>Deskripsi Hero</label>
              <textarea name="fds_about_hero_desc" rows="3"><?php echo esc_textarea($data['hero_desc']); ?></textarea>
            </div>

            <div class="fds-field">
              <label>Gambar Hero (Workshop / Tim FDS)</label>
              <div class="fds-img-preview-box">
                <img id="preview_hero_img" class="fds-img-preview" src="<?php echo esc_url($data['hero_img'] ?: fds_img('tk_hero', 'https://picsum.photos/seed/fds-team-workshop-2026/1920/800')); ?>">
                <div>
                  <input type="hidden" name="fds_about_hero_img" id="input_hero_img" value="<?php echo esc_attr($data['hero_img']); ?>">
                  <button type="button" class="button fds-upload-btn" data-input="input_hero_img" data-preview="preview_hero_img">Pilih / Ganti Gambar</button>
                  <button type="button" class="button fds-clear-btn" data-input="input_hero_img" data-preview="preview_hero_img" data-default="<?php echo esc_url(fds_img('tk_hero', 'https://picsum.photos/seed/fds-team-workshop-2026/1920/800')); ?>" style="margin-left: 6px;">Reset</button>
                </div>
              </div>
            </div>
          </div>

          <div class="fds-card">
            <h2>📊 4 Angka Statistik Utama</h2>
            <div class="fds-grid-4">
              <div>
                <div class="fds-field">
                  <label>Stat 1 (Angka)</label>
                  <input type="text" name="fds_about_stat1_num" value="<?php echo esc_attr($data['stat1_num']); ?>">
                </div>
                <div class="fds-field">
                  <label>Stat 1 (Label)</label>
                  <input type="text" name="fds_about_stat1_lbl" value="<?php echo esc_attr($data['stat1_lbl']); ?>">
                </div>
              </div>

              <div>
                <div class="fds-field">
                  <label>Stat 2 (Angka)</label>
                  <input type="text" name="fds_about_stat2_num" value="<?php echo esc_attr($data['stat2_num']); ?>">
                </div>
                <div class="fds-field">
                  <label>Stat 2 (Label)</label>
                  <input type="text" name="fds_about_stat2_lbl" value="<?php echo esc_attr($data['stat2_lbl']); ?>">
                </div>
              </div>

              <div>
                <div class="fds-field">
                  <label>Stat 3 (Angka)</label>
                  <input type="text" name="fds_about_stat3_num" value="<?php echo esc_attr($data['stat3_num']); ?>">
                </div>
                <div class="fds-field">
                  <label>Stat 3 (Label)</label>
                  <input type="text" name="fds_about_stat3_lbl" value="<?php echo esc_attr($data['stat3_lbl']); ?>">
                </div>
              </div>

              <div>
                <div class="fds-field">
                  <label>Stat 4 (Angka)</label>
                  <input type="text" name="fds_about_stat4_num" value="<?php echo esc_attr($data['stat4_num']); ?>">
                </div>
                <div class="fds-field">
                  <label>Stat 4 (Label)</label>
                  <input type="text" name="fds_about_stat4_lbl" value="<?php echo esc_attr($data['stat4_lbl']); ?>">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ========================================================= -->
        <!-- TAB 2: CERITA KAMI & NARASI -->
        <!-- ========================================================= -->
        <div class="fds-tab-pane" id="tab-cerita" style="display:none;">
          <div class="fds-card">
            <h2>📖 Section Cerita Kami (Editorial Two-Column)</h2>
            <div class="fds-grid-2">
              <div class="fds-field">
                <label>Badge Kiri</label>
                <input type="text" name="fds_about_story_badge" value="<?php echo esc_attr($data['story_badge']); ?>">
              </div>
              <div class="fds-field">
                <label>Judul Kiri</label>
                <input type="text" name="fds_about_story_title" value="<?php echo esc_attr($data['story_title']); ?>">
              </div>
            </div>

            <div class="fds-field">
              <label>Gambar Cerita Kami (Workshop FDS)</label>
              <div class="fds-img-preview-box">
                <img id="preview_story_img" class="fds-img-preview" src="<?php echo esc_url($data['story_img'] ?: fds_img('tk_story', 'https://picsum.photos/seed/fds-origin-story/800/600')); ?>">
                <div>
                  <input type="hidden" name="fds_about_story_img" id="input_story_img" value="<?php echo esc_attr($data['story_img']); ?>">
                  <button type="button" class="button fds-upload-btn" data-input="input_story_img" data-preview="preview_story_img">Pilih / Ganti Gambar</button>
                  <button type="button" class="button fds-clear-btn" data-input="input_story_img" data-preview="preview_story_img" data-default="<?php echo esc_url(fds_img('tk_story', 'https://picsum.photos/seed/fds-origin-story/800/600')); ?>" style="margin-left: 6px;">Reset</button>
                </div>
              </div>
            </div>

            <h3>Paragraf Narasi (Kolom Kanan)</h3>
            <div class="fds-field">
              <label>Paragraf 1 (Awal Mula 2012)</label>
              <textarea name="fds_about_story_p1" rows="3"><?php echo esc_textarea($data['story_p1']); ?></textarea>
            </div>
            <div class="fds-field">
              <label>Paragraf 2 (In-House Engineering & Riset)</label>
              <textarea name="fds_about_story_p2" rows="3"><?php echo esc_textarea($data['story_p2']); ?></textarea>
            </div>
            <div class="fds-field">
              <label>Paragraf 3 (Mutu, ISO, SNI, TKDN)</label>
              <textarea name="fds_about_story_p3" rows="3"><?php echo esc_textarea($data['story_p3']); ?></textarea>
            </div>
            <div class="fds-field">
              <label>Paragraf 4 (Moto & 4 Alur Layanan)</label>
              <textarea name="fds_about_story_p4" rows="3"><?php echo esc_textarea($data['story_p4']); ?></textarea>
            </div>

            <div class="fds-grid-2">
              <div class="fds-field">
                <label>Teks Link Bawah</label>
                <input type="text" name="fds_about_story_cta_text" value="<?php echo esc_attr($data['story_cta_text']); ?>">
              </div>
              <div class="fds-field">
                <label>Target URL Link</label>
                <input type="text" name="fds_about_story_cta_url" value="<?php echo esc_attr($data['story_cta_url']); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- ========================================================= -->
        <!-- TAB 3: SPEKTRUM TEKNOLOGI -->
        <!-- ========================================================= -->
        <div class="fds-tab-pane" id="tab-spektrum" style="display:none;">
          <div class="fds-card">
            <h2>🛸 Spektrum Teknologi UAV (3 Arsitektur Wahana)</h2>
            <div class="fds-grid-2">
              <div class="fds-field">
                <label>Badge Section</label>
                <input type="text" name="fds_about_spektrum_badge" value="<?php echo esc_attr($data['spektrum_badge']); ?>">
              </div>
              <div class="fds-field">
                <label>Judul Section</label>
                <input type="text" name="fds_about_spektrum_title" value="<?php echo esc_attr($data['spektrum_title']); ?>">
              </div>
            </div>

            <div class="fds-grid-3" style="margin-top: 10px;">
              <!-- Spektrum 1 -->
              <div style="background: #f9f9fb; border: 1px solid #e5e5ea; border-radius: 8px; padding: 16px;">
                <div class="fds-field">
                  <label>Card 1 (Judul)</label>
                  <input type="text" name="fds_about_spektrum1_title" value="<?php echo esc_attr($data['spektrum1_title']); ?>">
                </div>
                <div class="fds-field">
                  <label>Card 1 (Deskripsi)</label>
                  <textarea name="fds_about_spektrum1_desc" rows="4"><?php echo esc_textarea($data['spektrum1_desc']); ?></textarea>
                </div>
              </div>

              <!-- Spektrum 2 -->
              <div style="background: #f9f9fb; border: 1px solid #e5e5ea; border-radius: 8px; padding: 16px;">
                <div class="fds-field">
                  <label>Card 2 (Judul)</label>
                  <input type="text" name="fds_about_spektrum2_title" value="<?php echo esc_attr($data['spektrum2_title']); ?>">
                </div>
                <div class="fds-field">
                  <label>Card 2 (Deskripsi)</label>
                  <textarea name="fds_about_spektrum2_desc" rows="4"><?php echo esc_textarea($data['spektrum2_desc']); ?></textarea>
                </div>
              </div>

              <!-- Spektrum 3 -->
              <div style="background: #f9f9fb; border: 1px solid #e5e5ea; border-radius: 8px; padding: 16px;">
                <div class="fds-field">
                  <label>Card 3 (Judul)</label>
                  <input type="text" name="fds_about_spektrum3_title" value="<?php echo esc_attr($data['spektrum3_title']); ?>">
                </div>
                <div class="fds-field">
                  <label>Card 3 (Deskripsi)</label>
                  <textarea name="fds_about_spektrum3_desc" rows="4"><?php echo esc_textarea($data['spektrum3_desc']); ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ========================================================= -->
        <!-- TAB 4: AKTIVITAS & KEMITRAAN STRATEGIS -->
        <!-- ========================================================= -->
        <div class="fds-tab-pane" id="tab-mitra" style="display:none;">
          <div class="fds-card">
            <h2>🤝 Aktivitas &amp; Kemitraan Strategis (Header)</h2>
            <div class="fds-grid-3">
              <div class="fds-field">
                <label>Badge</label>
                <input type="text" name="fds_about_mitra_badge" value="<?php echo esc_attr($data['mitra_badge']); ?>">
              </div>
              <div class="fds-field">
                <label>Judul</label>
                <input type="text" name="fds_about_mitra_title" value="<?php echo esc_attr($data['mitra_title']); ?>">
              </div>
              <div class="fds-field">
                <label>Deskripsi Pengantar</label>
                <textarea name="fds_about_mitra_desc" rows="2"><?php echo esc_textarea($data['mitra_desc']); ?></textarea>
              </div>
            </div>
          </div>

          <div class="fds-card">
            <h2>📋 Daftar Aktivitas &amp; Kolaborasi</h2>
            
            <?php for ($i = 1; $i <= 7; $i++): ?>
              <div style="background: #f9f9fb; border: 1px solid #e5e5ea; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                <div class="fds-grid-3">
                  <div class="fds-field">
                    <label>Kategori (Mitra <?php echo $i; ?>)</label>
                    <input type="text" name="fds_about_mitra_item<?php echo $i; ?>_cat" value="<?php echo esc_attr($data["mitra_item{$i}_cat"]); ?>">
                  </div>
                  <div class="fds-field">
                    <label>Nama Mitra / Institusi</label>
                    <input type="text" name="fds_about_mitra_item<?php echo $i; ?>_name" value="<?php echo esc_attr($data["mitra_item{$i}_name"]); ?>">
                  </div>
                  <div class="fds-field">
                    <label>Deskripsi Proyek / Kerjasama</label>
                    <textarea name="fds_about_mitra_item<?php echo $i; ?>_desc" rows="2"><?php echo esc_textarea($data["mitra_item{$i}_desc"]); ?></textarea>
                  </div>
                </div>
              </div>
            <?php endfor; ?>
          </div>
        </div>

        <!-- ========================================================= -->
        <!-- TAB 5: SERTIFIKASI & STANDAR MUTU -->
        <!-- ========================================================= -->
        <div class="fds-tab-pane" id="tab-sertifikasi" style="display:none;">
          <div class="fds-card">
            <h2>🏆 Sertifikasi &amp; Standar Mutu (Header &amp; 3 Bento Cards)</h2>
            <div class="fds-grid-2">
              <div class="fds-field">
                <label>Badge</label>
                <input type="text" name="fds_about_certs_badge" value="<?php echo esc_attr($data['certs_badge']); ?>">
              </div>
              <div class="fds-field">
                <label>Judul</label>
                <input type="text" name="fds_about_certs_title" value="<?php echo esc_attr($data['certs_title']); ?>">
              </div>
            </div>

            <div class="fds-grid-3" style="margin-top: 10px;">
              <!-- Card 1: TKDN -->
              <div style="background: #f0f7ff; border: 1px solid #cce5ff; border-radius: 8px; padding: 16px;">
                <div class="fds-field">
                  <label>Card 1 (Badge)</label>
                  <input type="text" name="fds_about_cert1_badge" value="<?php echo esc_attr($data['cert1_badge']); ?>">
                </div>
                <div class="fds-field">
                  <label>Card 1 (Nilai / Angka)</label>
                  <input type="text" name="fds_about_cert1_val" value="<?php echo esc_attr($data['cert1_val']); ?>">
                </div>
                <div class="fds-field">
                  <label>Card 1 (Deskripsi)</label>
                  <textarea name="fds_about_cert1_desc" rows="3"><?php echo esc_textarea($data['cert1_desc']); ?></textarea>
                </div>
              </div>

              <!-- Card 2: ISO & SNI -->
              <div style="background: #f9f9fb; border: 1px solid #e5e5ea; border-radius: 8px; padding: 16px;">
                <div class="fds-field">
                  <label>Card 2 (Badge)</label>
                  <input type="text" name="fds_about_cert2_badge" value="<?php echo esc_attr($data['cert2_badge']); ?>">
                </div>
                <div class="fds-field">
                  <label>Card 2 (Nilai / Label)</label>
                  <input type="text" name="fds_about_cert2_val" value="<?php echo esc_attr($data['cert2_val']); ?>">
                </div>
                <div class="fds-field">
                  <label>Card 2 (Deskripsi)</label>
                  <textarea name="fds_about_cert2_desc" rows="3"><?php echo esc_textarea($data['cert2_desc']); ?></textarea>
                </div>
              </div>

              <!-- Card 3: 24/7 -->
              <div style="background: #f9f9fb; border: 1px solid #e5e5ea; border-radius: 8px; padding: 16px;">
                <div class="fds-field">
                  <label>Card 3 (Badge)</label>
                  <input type="text" name="fds_about_cert3_badge" value="<?php echo esc_attr($data['cert3_badge']); ?>">
                </div>
                <div class="fds-field">
                  <label>Card 3 (Nilai / Label)</label>
                  <input type="text" name="fds_about_cert3_val" value="<?php echo esc_attr($data['cert3_val']); ?>">
                </div>
                <div class="fds-field">
                  <label>Card 3 (Deskripsi)</label>
                  <textarea name="fds_about_cert3_desc" rows="3"><?php echo esc_textarea($data['cert3_desc']); ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ========================================================= -->
        <!-- TAB 6: CTA & WORKSHOP -->
        <!-- ========================================================= -->
        <div class="fds-tab-pane" id="tab-cta" style="display:none;">
          <div class="fds-card">
            <h2>🚀 Section Call-to-Action (Bawah)</h2>
            <div class="fds-field">
              <label>Judul CTA</label>
              <input type="text" name="fds_about_cta_title" value="<?php echo esc_attr($data['cta_title']); ?>">
            </div>
            <div class="fds-field">
              <label>Deskripsi CTA</label>
              <textarea name="fds_about_cta_desc" rows="2"><?php echo esc_textarea($data['cta_desc']); ?></textarea>
            </div>

            <div class="fds-grid-2">
              <div>
                <div class="fds-field">
                  <label>Tombol 1 (Teks)</label>
                  <input type="text" name="fds_about_cta_btn1_text" value="<?php echo esc_attr($data['cta_btn1_text']); ?>">
                </div>
                <div class="fds-field">
                  <label>Tombol 1 (URL / Link)</label>
                  <input type="text" name="fds_about_cta_btn1_url" value="<?php echo esc_attr($data['cta_btn1_url']); ?>">
                </div>
              </div>

              <div>
                <div class="fds-field">
                  <label>Tombol 2 (Teks)</label>
                  <input type="text" name="fds_about_cta_btn2_text" value="<?php echo esc_attr($data['cta_btn2_text']); ?>">
                </div>
                <div class="fds-field">
                  <label>Tombol 2 (URL / Link)</label>
                  <input type="text" name="fds_about_cta_btn2_url" value="<?php echo esc_attr($data['cta_btn2_url']); ?>">
                </div>
              </div>
            </div>
          </div>

          <div class="fds-card">
            <h2>📍 Informasi Workshop &amp; Kontak Terpusat</h2>
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 14px 18px; margin-bottom: 18px;">
              <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                <div>
                  <strong style="color: #166534; font-size: 13px; display: block; margin-bottom: 2px;">📍 Terkelola Terpusat di Menu Kontak &amp; Sosmed</strong>
                  <span style="color: #15803d; font-size: 12px;">Entitas, Alamat Workshop, Email Resmi, Telepon, WhatsApp, dan Google Maps dikelola secara terpusat di menu <strong>Kontak &amp; Sosmed</strong>.</span>
                </div>
                <a href="admin.php?page=fds-footer-settings" class="button button-secondary" style="font-size: 12px; font-weight: 600; white-space: nowrap;">
                  ⚙️ Kelola Kontak &amp; Sosmed
                </a>
              </div>
            </div>
            <div class="fds-field">
              <label>Judul Box Direktori Workshop</label>
              <input type="text" name="fds_about_info_title" value="<?php echo esc_attr($data['info_title']); ?>">
            </div>
          </div>
        </div>

        <!-- STICKY SUBMIT BAR -->
        <div class="fds-submit-bar">
          <div style="font-size: 13px; color: #6e6e73;">
            Semua perubahan akan langsung diterapkan ke halaman <strong>Tentang Kami</strong>.
          </div>
          <div>
            <button type="submit" class="button button-primary button-large" style="background: #0066cc; border-color: #0066cc; font-weight: 600; padding: 0 24px; border-radius: 8px;">
              💾 Simpan Perubahan
            </button>
          </div>
        </div>

      </form>
    </div>

    <!-- TAB SWITCH SCRIPT & MEDIA UPLOADER -->
    <script>
      jQuery(document).ready(function($) {
        // Tab switcher
        $('.fds-tab-btn').on('click', function() {
          $('.fds-tab-btn').removeClass('active');
          $(this).addClass('active');
          $('.fds-tab-pane').hide();
          $('#' + $(this).data('tab')).show();
        });

        // Media Uploader
        $('.fds-upload-btn').on('click', function(e) {
          e.preventDefault();
          var btn = $(this);
          var inputId = btn.data('input');
          var previewId = btn.data('preview');

          var customUploader = wp.media({
            title: 'Pilih atau Unggah Gambar',
            button: { text: 'Gunakan Gambar Ini' },
            multiple: false
          });

          customUploader.on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            $('#' + inputId).val(attachment.url);
            $('#' + previewId).attr('src', attachment.url);
          });

          customUploader.open();
        });

        // Reset Button
        $('.fds-clear-btn').on('click', function(e) {
          e.preventDefault();
          var btn = $(this);
          var inputId = btn.data('input');
          var previewId = btn.data('preview');
          var defaultUrl = btn.data('default');

          $('#' + inputId).val('');
          $('#' + previewId).attr('src', defaultUrl);
        });
      });
    </script>
    <?php
}
