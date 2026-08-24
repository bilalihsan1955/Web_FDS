<?php

namespace App;

/**
 * =========================================================================
 * FDS HOMEPAGE CONTENT MANAGER (PT KARYA SOLUSI ANGKASA)
 * =========================================================================
 * Mengelola semua teks section halaman depan (Hero, Mitra, Produk Header,
 * Keunggulan Bento Grid, Layanan Enterprise, Newsroom, dan Kontak).
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

// Auto-sync homepage contact details
add_action('init', function () {
    $current_phone = get_option('fds_kontak_phone');
    if (empty($current_phone) || $current_phone === '+62 812-3456-7890') {
        update_option('fds_kontak_phone', '+62 8112 748 882');
        update_option('fds_kontak_email', 'marketing@fulldronesolutions.com');
        update_option('fds_kontak_address', 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281');
        update_option('fds_kontak_wa_link', 'https://wa.me/628112748882');
        update_option('fds_kontak_wa_text', 'Chat via WhatsApp');
        update_option('fds_kontak_maps', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid');
    }

    // Clean wa_text if it has phone number
    $wa_text = get_option('fds_kontak_wa_text');
    if ($wa_text === 'Chat via WhatsApp (+62 8112 748 882)') {
        update_option('fds_kontak_wa_text', 'Chat via WhatsApp');
    }
});

// 2. HELPER GET HOMEPAGE CONTENT
function fds_get_homepage_content() {
    return [
        // HERO
        'hero_badge'        => get_option('fds_hero_badge', 'TKDN 44,85% · Produksi Indonesia'),
        'hero_title'        => get_option('fds_hero_title', "Solusi Drone Industrial\nuntuk Setiap Sektor."),
        'hero_desc'         => get_option('fds_hero_desc', 'Dari pemetaan topografi hingga inspeksi infrastruktur—Full Drone Solutions menghadirkan teknologi udara berstandar industri, diproduksi lokal.'),
        'hero_cta1_text'    => get_option('fds_hero_cta1_text', 'Jelajahi Solusi Kami'),
        'hero_cta1_url'     => get_option('fds_hero_cta1_url', '#solusi'),
        'hero_cta2_text'    => get_option('fds_hero_cta2_text', 'Konsultasi Enterprise'),
        'hero_cta2_url'     => get_option('fds_hero_cta2_url', '#kontak'),

        // MITRA
        'mitra_heading'     => get_option('fds_mitra_heading', 'Dipercaya oleh Lembaga Nasional & Internasional'),

        // PRODUK HEADER & STATS
        'produk_badge'      => get_option('fds_produk_badge', 'Lini Produk Drone'),
        'produk_title'      => get_option('fds_produk_title', 'Teknologi UAV Rekayasa Indonesia.'),
        'produk_desc'       => get_option('fds_produk_desc', 'TKDN + BMP hingga 60,74%, SNI 9199:2023, software FDS STATION Bahasa Indonesia, dan garansi purna jual resmi.'),
        'produk_stat1_num'  => get_option('fds_produk_stat1_num', '60,74%'),
        'produk_stat1_lbl'  => get_option('fds_produk_stat1_lbl', 'Nilai TKDN + BMP'),
        'produk_stat2_num'  => get_option('fds_produk_stat2_num', 'ISO & SNI'),
        'produk_stat2_lbl'  => get_option('fds_produk_stat2_lbl', 'ISO 9001 & SNI 9199:2023'),
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

        'layanan_item1_title'    => get_option('fds_layanan_item1_title', 'Pemetaan Aerial & GIS'),
        'layanan_item1_desc'     => get_option('fds_layanan_item1_desc', 'Peta topografi resolusi tinggi dengan akurasi sub-sentimeter untuk perencanaan lahan, kehutanan, dan infrastruktur.'),

        'layanan_item2_title'    => get_option('fds_layanan_item2_title', 'Inspeksi Industri & Infrastruktur'),
        'layanan_item2_desc'     => get_option('fds_layanan_item2_desc', 'Pemeriksaan visual dan termal berbasis UAV untuk pemantauan fasilitas energi, kelistrikan, migas, dan infrastruktur kritis secara cepat dan aman tanpa menghentikan operasional.'),

        'layanan_item3_title'    => get_option('fds_layanan_item3_title', 'Sewa Armada Drone'),
        'layanan_item3_desc'     => get_option('fds_layanan_item3_desc', 'Armada FERTO siap pakai untuk proyek jangka pendek, pilot project, atau kebutuhan peak season tanpa investasi unit penuh.'),

        'layanan_item4_title'    => get_option('fds_layanan_item4_title', 'Pelatihan & Sertifikasi Pilot'),
        'layanan_item4_desc'     => get_option('fds_layanan_item4_desc', 'Program pelatihan pilot drone bersertifikat resmi untuk tim lapangan Anda. Kurikulum mencakup misi agrikultur, pemetaan, dan inspeksi.'),

        'layanan_item5_title'    => get_option('fds_layanan_item5_title', 'After-Sales & Maintenance'),
        'layanan_item5_desc'     => get_option('fds_layanan_item5_desc', 'Layanan purna jual lokal dengan stok suku cadang, teknisi bersertifikat, dan garansi resmi di seluruh Indonesia.'),

        // NEWSROOM
        'blog_badge'             => get_option('fds_blog_badge', 'Newsroom'),
        'blog_title'             => get_option('fds_blog_title', 'Berita & Pembaruan Terkini.'),
        'blog_cta_text'          => get_option('fds_blog_cta_text', 'Lihat semua artikel'),

        // KONTAK
        'kontak_badge'           => get_option('fds_kontak_badge', 'Enterprise Sales'),
        'kontak_title'           => get_option('fds_kontak_title', "Hubungi tim\nEnterprise FDS."),
        'kontak_desc'            => get_option('fds_kontak_desc', 'Dari konsultasi teknis, fleet management, hingga program sertifikasi — kami siap mendampingi operasional drone Anda.'),
        'kontak_phone'           => get_option('fds_kontak_phone', '+62 8112 748 882'),
        'kontak_email'           => get_option('fds_kontak_email', 'marketing@fulldronesolutions.com'),
        'kontak_address'         => get_option('fds_kontak_address', 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281'),
        'kontak_wa_link'         => get_option('fds_kontak_wa_link', 'https://wa.me/628112748882'),
        'kontak_wa_text'         => get_option('fds_kontak_wa_text', 'Chat via WhatsApp'),
        'kontak_maps'            => get_option('fds_kontak_maps', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid'),
        'kontak_form_title'      => get_option('fds_kontak_form_title', 'Kirim pesan inquiry'),
        'kontak_form_btn_text'   => get_option('fds_kontak_form_btn_text', 'Kirim Pesan'),
        'kontak_form_note'       => get_option('fds_kontak_form_note', "Kami merespons dalam 1×24 jam kerja.\nData Anda tidak akan dibagikan ke pihak ketiga."),
    ];
}

// 3. TAMPILAN HALAMAN WP ADMIN KONTEN BERANDA
function render_homepage_content_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $message = '';
    if (isset($_POST['fds_content_save']) && check_admin_referer('fds_content_nonce_action', 'fds_content_nonce')) {
        $fields_text = [
            'fds_hero_badge', 'fds_hero_cta1_text', 'fds_hero_cta1_url', 'fds_hero_cta2_text', 'fds_hero_cta2_url',
            'fds_mitra_heading',
            'fds_produk_badge', 'fds_produk_title', 'fds_produk_stat1_num', 'fds_produk_stat1_lbl', 'fds_produk_stat2_num', 'fds_produk_stat2_lbl', 'fds_produk_stat3_num', 'fds_produk_stat3_lbl', 'fds_produk_stat4_num', 'fds_produk_stat4_lbl',
            'fds_keunggulan_badge', 'fds_keunggulan_title', 'fds_keunggulan_card1_badge', 'fds_keunggulan_card2_badge', 'fds_keunggulan_card2_stat', 'fds_keunggulan_card3_badge', 'fds_keunggulan_card4_badge', 'fds_keunggulan_card4_stat', 'fds_keunggulan_card5_badge', 'fds_keunggulan_card5_title', 'fds_keunggulan_card6_badge', 'fds_keunggulan_card6_stat', 'fds_keunggulan_card7_badge', 'fds_keunggulan_card7_title',
            'fds_layanan_badge', 'fds_layanan_title', 'fds_layanan_cta_text', 'fds_layanan_cta_url', 'fds_layanan_item1_title', 'fds_layanan_item2_title', 'fds_layanan_item3_title', 'fds_layanan_item4_title', 'fds_layanan_item5_title',
            'fds_blog_badge', 'fds_blog_title', 'fds_blog_cta_text',
            'fds_kontak_badge', 'fds_kontak_phone', 'fds_kontak_email', 'fds_kontak_address', 'fds_kontak_wa_link', 'fds_kontak_wa_text', 'fds_kontak_form_title', 'fds_kontak_form_btn_text',
        ];

        $fields_textarea = [
            'fds_hero_title', 'fds_hero_desc',
            'fds_produk_desc',
            'fds_keunggulan_card1_title', 'fds_keunggulan_card1_desc', 'fds_keunggulan_card2_desc', 'fds_keunggulan_card3_title', 'fds_keunggulan_card3_desc', 'fds_keunggulan_card4_desc', 'fds_keunggulan_card5_desc', 'fds_keunggulan_card6_desc', 'fds_keunggulan_card7_desc',
            'fds_layanan_desc', 'fds_layanan_item1_desc', 'fds_layanan_item2_desc', 'fds_layanan_item3_desc', 'fds_layanan_item4_desc', 'fds_layanan_item5_desc',
            'fds_kontak_title', 'fds_kontak_desc', 'fds_kontak_form_note',
        ];

        foreach ($fields_text as $f) {
            update_option($f, sanitize_text_field($_POST[$f] ?? ''));
        }

        foreach ($fields_textarea as $f) {
            update_option($f, sanitize_textarea_field($_POST[$f] ?? ''));
        }

        // Sinkronisasi otomatis ke Kontak Global
        if (!empty($_POST['fds_kontak_phone'])) {
            update_option('fds_global_phone', sanitize_text_field($_POST['fds_kontak_phone']));
            update_option('fds_footer_phone', sanitize_text_field($_POST['fds_kontak_phone']));
            update_option('fds_about_info_phone', sanitize_text_field($_POST['fds_kontak_phone']));
        }
        if (!empty($_POST['fds_kontak_email'])) {
            update_option('fds_global_email', sanitize_email($_POST['fds_kontak_email']));
            update_option('fds_footer_email', sanitize_email($_POST['fds_kontak_email']));
            update_option('fds_about_info_email', sanitize_email($_POST['fds_kontak_email']));
        }
        if (!empty($_POST['fds_kontak_address'])) {
            update_option('fds_global_address', sanitize_textarea_field($_POST['fds_kontak_address']));
            update_option('fds_footer_address', sanitize_textarea_field($_POST['fds_kontak_address']));
            update_option('fds_about_info_alamat', sanitize_textarea_field($_POST['fds_kontak_address']));
        }
        if (!empty($_POST['fds_kontak_wa_link'])) {
            update_option('fds_global_wa_link', esc_url_raw($_POST['fds_kontak_wa_link']));
            update_option('fds_footer_whatsapp', esc_url_raw($_POST['fds_kontak_wa_link']));
        }

        $message = 'Konten Beranda berhasil diperbarui dan disinkronkan!';
    }

    $c = fds_get_homepage_content();
    ?>
    <div class="wrap" style="max-width: 1050px; margin-top: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
        <div style="background: #fff; padding: 24px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 8px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #0066cc; display: flex; align-items: center; justify-content: center; color: #fff;">
                    <span class="dashicons dashicons-admin-page" style="font-size: 24px; width: 24px; height: 24px;"></span>
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #1e293b;">Pengaturan Teks &amp; Header Section Beranda</h1>
                    <p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">Kelola semua teks, judul, sub-heading, deskripsi, dan tombol untuk setiap section di halaman depan website.</p>
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
                <button type="button" class="tab-btn active" data-tab="tab-hero" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #0066cc; background: #0066cc; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer;">1. Hero Section</button>
                <button type="button" class="tab-btn" data-tab="tab-mitra" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">2. Mitra</button>
                <button type="button" class="tab-btn" data-tab="tab-produk" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">3. Header Produk</button>
                <button type="button" class="tab-btn" data-tab="tab-keunggulan" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">4. Keunggulan (Bento)</button>
                <button type="button" class="tab-btn" data-tab="tab-layanan" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">5. Layanan Enterprise</button>
                <button type="button" class="tab-btn" data-tab="tab-blog" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">6. Newsroom</button>
                <button type="button" class="tab-btn" data-tab="tab-kontak" style="padding: 10px 18px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; color: #475569; font-size: 13px; font-weight: 600; cursor: pointer;">7. Kontak &amp; Sales</button>
            </div>

            <!-- TAB 1: HERO -->
            <div id="tab-hero" class="tab-content" style="background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    1. Hero Section (Atas Halaman Beranda)
                </h2>
                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge Atas Hero</label>
                        <input type="text" name="fds_hero_badge" value="<?php echo esc_attr($c['hero_badge']); ?>" style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama Hero (Gunakan Enter untuk baris baru)</label>
                        <textarea name="fds_hero_title" rows="2" style="width: 100%; font-size: 15px; font-weight: 600; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($c['hero_title']); ?></textarea>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Deskripsi / Sub-Judul Hero</label>
                        <textarea name="fds_hero_desc" rows="3" style="width: 100%; font-size: 13px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;"><?php echo esc_textarea($c['hero_desc']); ?></textarea>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Tombol Utama 1 (Biru)</label>
                            <input type="text" name="fds_hero_cta1_text" value="<?php echo esc_attr($c['hero_cta1_text']); ?>" style="width: 100%; font-size: 13px; margin-bottom: 6px;" placeholder="Teks Tombol">
                            <input type="text" name="fds_hero_cta1_url" value="<?php echo esc_attr($c['hero_cta1_url']); ?>" style="width: 100%; font-size: 12px;" placeholder="URL Tujuan (misal: #solusi)">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Tombol Sekunder 2 (Tautan)</label>
                            <input type="text" name="fds_hero_cta2_text" value="<?php echo esc_attr($c['hero_cta2_text']); ?>" style="width: 100%; font-size: 13px; margin-bottom: 6px;" placeholder="Teks Tombol">
                            <input type="text" name="fds_hero_cta2_url" value="<?php echo esc_attr($c['hero_cta2_url']); ?>" style="width: 100%; font-size: 12px;" placeholder="URL Tujuan (misal: #kontak)">
                        </div>
                    </div>
                    <p style="margin: 8px 0 0; font-size: 12px; color: #64748b;"><em>*Untuk upload gambar carousel hero, gunakan menu terpisah "Hero Slider".</em></p>
                </div>
            </div>

            <!-- TAB 2: MITRA -->
            <div id="tab-mitra" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    2. Section Mitra &amp; Lembaga
                </h2>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Header Marquee Mitra</label>
                    <input type="text" name="fds_mitra_heading" value="<?php echo esc_attr($c['mitra_heading']); ?>" style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;">
                </div>
            </div>

            <!-- TAB 3: PRODUK HEADER & STATS -->
            <div id="tab-produk" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    3. Header Section Lini Produk Drone &amp; Statistik
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
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Deskripsi Pengantar</label>
                        <textarea name="fds_produk_desc" rows="2" style="width: 100%; font-size: 13px;"><?php echo esc_textarea($c['produk_desc']); ?></textarea>
                    </div>

                    <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 10px 0 4px;">4 Metrik Statistik di Bawah Daftar Produk</h3>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                        <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <input type="text" name="fds_produk_stat1_num" value="<?php echo esc_attr($c['produk_stat1_num']); ?>" style="width: 100%; font-weight: 700; margin-bottom: 4px;">
                            <input type="text" name="fds_produk_stat1_lbl" value="<?php echo esc_attr($c['produk_stat1_lbl']); ?>" style="width: 100%; font-size: 11px;">
                        </div>
                        <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <input type="text" name="fds_produk_stat2_num" value="<?php echo esc_attr($c['produk_stat2_num']); ?>" style="width: 100%; font-weight: 700; margin-bottom: 4px;">
                            <input type="text" name="fds_produk_stat2_lbl" value="<?php echo esc_attr($c['produk_stat2_lbl']); ?>" style="width: 100%; font-size: 11px;">
                        </div>
                        <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <input type="text" name="fds_produk_stat3_num" value="<?php echo esc_attr($c['produk_stat3_num']); ?>" style="width: 100%; font-weight: 700; margin-bottom: 4px;">
                            <input type="text" name="fds_produk_stat3_lbl" value="<?php echo esc_attr($c['produk_stat3_lbl']); ?>" style="width: 100%; font-size: 11px;">
                        </div>
                        <div style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <input type="text" name="fds_produk_stat4_num" value="<?php echo esc_attr($c['produk_stat4_num']); ?>" style="width: 100%; font-weight: 700; margin-bottom: 4px;">
                            <input type="text" name="fds_produk_stat4_lbl" value="<?php echo esc_attr($c['produk_stat4_lbl']); ?>" style="width: 100%; font-size: 11px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: KEUNGGULAN (BENTO) -->
            <div id="tab-keunggulan" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    4. Section Mengapa FDS (Bento Grid)
                </h2>
                <div style="display: grid; gap: 18px;">
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge Header</label>
                            <input type="text" name="fds_keunggulan_badge" value="<?php echo esc_attr($c['keunggulan_badge']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama Header</label>
                            <input type="text" name="fds_keunggulan_title" value="<?php echo esc_attr($c['keunggulan_title']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                        </div>
                    </div>

                    <!-- Bento Card 1 -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 8px;">
                        <strong>Bento 1: Rekayasa &amp; Manufaktur In-House</strong>
                        <div style="display: grid; gap: 8px; margin-top: 8px;">
                            <input type="text" name="fds_keunggulan_card1_badge" value="<?php echo esc_attr($c['keunggulan_card1_badge']); ?>" style="width: 100%; font-size: 12px;" placeholder="Badge">
                            <textarea name="fds_keunggulan_card1_title" rows="2" style="width: 100%; font-size: 13px; font-weight: 600;" placeholder="Judul"><?php echo esc_textarea($c['keunggulan_card1_title']); ?></textarea>
                            <textarea name="fds_keunggulan_card1_desc" rows="2" style="width: 100%; font-size: 12px;" placeholder="Deskripsi"><?php echo esc_textarea($c['keunggulan_card1_desc']); ?></textarea>
                        </div>
                    </div>

                    <!-- Bento Card 2 -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 8px;">
                        <strong>Bento 2: Sertifikasi TKDN + BMP</strong>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 8px;">
                            <input type="text" name="fds_keunggulan_card2_badge" value="<?php echo esc_attr($c['keunggulan_card2_badge']); ?>" style="width: 100%; font-size: 12px;">
                            <input type="text" name="fds_keunggulan_card2_stat" value="<?php echo esc_attr($c['keunggulan_card2_stat']); ?>" style="width: 100%; font-size: 13px; font-weight: 700;">
                        </div>
                        <input type="text" name="fds_keunggulan_card2_desc" value="<?php echo esc_attr($c['keunggulan_card2_desc']); ?>" style="width: 100%; font-size: 12px; margin-top: 8px;">
                    </div>

                    <!-- Bento Card 3 & 4 -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 8px;">
                            <strong>Bento 3: Software FDS STATION GCS</strong>
                            <div style="display: grid; gap: 6px; margin-top: 8px;">
                                <input type="text" name="fds_keunggulan_card3_badge" value="<?php echo esc_attr($c['keunggulan_card3_badge']); ?>" style="width: 100%; font-size: 12px;">
                                <textarea name="fds_keunggulan_card3_title" rows="2" style="width: 100%; font-size: 13px; font-weight: 600;"><?php echo esc_textarea($c['keunggulan_card3_title']); ?></textarea>
                                <textarea name="fds_keunggulan_card3_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card3_desc']); ?></textarea>
                            </div>
                        </div>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 8px;">
                            <strong>Bento 4: Standar &amp; Mutu ISO/SNI</strong>
                            <div style="display: grid; gap: 6px; margin-top: 8px;">
                                <input type="text" name="fds_keunggulan_card4_badge" value="<?php echo esc_attr($c['keunggulan_card4_badge']); ?>" style="width: 100%; font-size: 12px;">
                                <input type="text" name="fds_keunggulan_card4_stat" value="<?php echo esc_attr($c['keunggulan_card4_stat']); ?>" style="width: 100%; font-size: 13px; font-weight: 700;">
                                <textarea name="fds_keunggulan_card4_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card4_desc']); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bento Card 5, 6, 7 -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 8px;">
                            <strong>Bento 5: Purna Jual &amp; Suku Cadang</strong>
                            <div style="display: grid; gap: 6px; margin-top: 8px;">
                                <input type="text" name="fds_keunggulan_card5_badge" value="<?php echo esc_attr($c['keunggulan_card5_badge']); ?>" style="width: 100%; font-size: 12px;">
                                <input type="text" name="fds_keunggulan_card5_title" value="<?php echo esc_attr($c['keunggulan_card5_title']); ?>" style="width: 100%; font-size: 13px; font-weight: 600;">
                                <textarea name="fds_keunggulan_card5_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card5_desc']); ?></textarea>
                            </div>
                        </div>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 8px;">
                            <strong>Bento 6: Pengalaman Industri (2012)</strong>
                            <div style="display: grid; gap: 6px; margin-top: 8px;">
                                <input type="text" name="fds_keunggulan_card6_badge" value="<?php echo esc_attr($c['keunggulan_card6_badge']); ?>" style="width: 100%; font-size: 12px;">
                                <input type="text" name="fds_keunggulan_card6_stat" value="<?php echo esc_attr($c['keunggulan_card6_stat']); ?>" style="width: 100%; font-size: 13px; font-weight: 700;">
                                <textarea name="fds_keunggulan_card6_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c['keunggulan_card6_desc']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 5: LAYANAN ENTERPRISE -->
            <div id="tab-layanan" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    5. Section Layanan Enterprise
                </h2>
                <div style="display: grid; gap: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Badge</label>
                            <input type="text" name="fds_layanan_badge" value="<?php echo esc_attr($c['layanan_badge']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Utama Layanan</label>
                            <input type="text" name="fds_layanan_title" value="<?php echo esc_attr($c['layanan_title']); ?>" style="width: 100%; font-size: 14px; font-weight: 600;">
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Deskripsi Pengantar Layanan</label>
                        <textarea name="fds_layanan_desc" rows="2" style="width: 100%; font-size: 13px;"><?php echo esc_textarea($c['layanan_desc']); ?></textarea>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Teks Tombol CTA</label>
                            <input type="text" name="fds_layanan_cta_text" value="<?php echo esc_attr($c['layanan_cta_text']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">URL Tombol CTA</label>
                            <input type="text" name="fds_layanan_cta_url" value="<?php echo esc_attr($c['layanan_cta_url']); ?>" style="width: 100%; font-size: 13px;">
                        </div>
                    </div>

                    <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 12px 0 4px;">Daftar 5 Layanan Utama</h3>
                    <?php for($i=1; $i<=5; $i++): ?>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px;">
                        <input type="text" name="fds_layanan_item<?php echo $i; ?>_title" value="<?php echo esc_attr($c["layanan_item{$i}_title"]); ?>" style="width: 100%; font-weight: 600; font-size: 13px; margin-bottom: 6px;">
                        <textarea name="fds_layanan_item<?php echo $i; ?>_desc" rows="2" style="width: 100%; font-size: 12px;"><?php echo esc_textarea($c["layanan_item{$i}_desc"]); ?></textarea>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- TAB 6: NEWSROOM -->
            <div id="tab-blog" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    6. Section Newsroom / Blog
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

            <!-- TAB 7: KONTAK -->
            <div id="tab-kontak" class="tab-content" style="display: none; background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    7. Section Kontak &amp; Enterprise Sales
                </h2>

                <!-- NOTIFIKASI KONTAK TERPUSAT -->
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
                <button type="submit" name="fds_content_save" class="button button-primary button-large" style="background: #0066cc; border-color: #0066cc; font-size: 15px; font-weight: 600; padding: 8px 30px; border-radius: 6px; height: auto;">
                    Simpan Semua Konten Beranda
                </button>
            </div>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('.tab-btn').on('click', function() {
            $('.tab-btn').css({'background': '#fff', 'color': '#475569', 'border-color': '#e2e8f0'});
            $(this).css({'background': '#0066cc', 'color': '#fff', 'border-color': '#0066cc'});
            
            let targetTab = $(this).data('tab');
            $('.tab-content').hide();
            $('#' + targetTab).fadeIn(150);
        });
    });
    </script>
    <?php
}
