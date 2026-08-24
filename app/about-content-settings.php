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

// 2. HELPER GET ABOUT CONTENT
function fds_get_about_content() {
    return [
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
        'story_p1'        => get_option('fds_about_story_p1', '<strong class="text-[#1d1d1f] font-semibold">PT Karya Solusi Angkasa</strong> (dikenal sebagai <strong class="text-[#1d1d1f] font-semibold">Full Drone Solutions / FDS</strong>) mengawali perjalanannya dari dedikasi mendalam terhadap rekayasa sistem pesawat tanpa awak (<em>Unmanned Aerial Vehicle</em>) sejak tahun 2012 di Yogyakarta, hingga resmi berbadan hukum perseroan terbatas pada tahun 2019.'),
        'story_p2'        => get_option('fds_about_story_p2', 'Dengan fokus pada <strong class="text-[#1d1d1f] font-semibold">Advanced UAV Engineering, Manufacturing, &amp; AI Technology</strong>, FDS tidak sekadar merakit atau mengimpor komponen jadi. Kami melakukan riset desain aerodinamis, pengembangan struktur mekanis, perancangan avionik <em>in-house</em>, pencetakan komposit karbon lokal, dan integrasi <em>payload</em> kustom untuk menghasilkan drone yang tangguh di iklim tropis Indonesia.'),
        'story_p3'        => get_option('fds_about_story_p3', 'Komitmen mutu kami dibuktikan melalui kepemilikan sertifikasi manajemen mutu internasional <strong class="text-[#1d1d1f] font-semibold">ISO 9001:2015</strong>, sertifikasi produk <strong class="text-[#1d1d1f] font-semibold">SNI 9199:2023</strong>, serta pencapaian <strong class="text-[#1d1d1f] font-semibold">Nilai TKDN + Bobot Manfaat Perusahaan (BMP) mencapai 60,74%</strong> dari Kementerian Perindustrian Republik Indonesia.'),
        'story_p4'        => get_option('fds_about_story_p4', 'Dengan moto <em class="text-[#1d1d1f] font-semibold">"Powerful Service. Giving Value"</em>, kami mengoperasikan alur kerja layanan end-to-end yang terstruktur: mulai dari <strong>1. Consultation</strong>, <strong>2. Requirement &amp; Spec Formulation</strong>, <strong>3. In-House Development</strong>, hingga <strong>4. Delivery &amp; Certified Pilot Training</strong>.'),
        'story_cta_text'  => get_option('fds_about_story_cta_text', 'Lihat kemitraan strategis & portofolio klien'),
        'story_cta_url'   => get_option('fds_about_story_cta_url', '#mitra'),

        // SPEKTRUM TEKNOLOGI
        'spektrum_badge'  => get_option('fds_about_spektrum_badge', 'Spektrum Teknologi UAV'),
        'spektrum_title'  => get_option('fds_about_spektrum_title', 'Tiga arsitektur wahana udara untuk segala medan.'),
        
        'spektrum1_title' => get_option('fds_about_spektrum1_title', 'Rotary Wing (Multirotor)'),
        'spektrum1_desc'  => get_option('fds_about_spektrum1_desc', 'Kemampuan Vertical Takeoff and Landing (VTOL), kontrol posisi presisi tinggi, dan hovering super stabil. Digunakan pada seri FERTO (5–50L), DELFRO kargo, dan REBO reboisasi.'),

        'spektrum2_title' => get_option('fds_about_spektrum2_title', 'Fixed Wing (Sayap Tetap)'),
        'spektrum2_desc'  => get_option('fds_about_spektrum2_desc', 'Dirancang untuk misi jarak jauh, daya tahan terbang tinggi (endurance), dan cakupan area pemetaan luas yang efisien dalam satu sorti penerbangan.'),

        'spektrum3_title' => get_option('fds_about_spektrum3_title', 'Hybrid VTOL (DELTAV)'),
        'spektrum3_desc'  => get_option('fds_about_spektrum3_desc', 'Menggabungkan fleksibilitas peluncuran vertikal tanpa landasan dengan kecepatan jelajah 15–22 m/s dan jangkauan 60 km untuk akuisisi geospasial presisi.'),

        // KEMITRAAN & KLIEN STRATEGIS
        'mitra_badge'     => get_option('fds_about_mitra_badge', 'Kemitraan & Klien Strategis'),
        'mitra_title'     => get_option('fds_about_mitra_title', 'Dipercaya oleh institusi negara, BUMN, dan korporasi terkemuka.'),
        'mitra_desc'      => get_option('fds_about_mitra_desc', 'FDS secara konsisten menjadi mitra strategis dalam program ketahanan pangan nasional, riset geospasial, dan otomatisasi industri skala besar.'),

        'mitra_item1_cat' => get_option('fds_about_mitra_item1_cat', 'Program Riset & Pemerintah'),
        'mitra_item1_name'=> get_option('fds_about_mitra_item1_name', 'Bappenas & Australia DFAT'),
        'mitra_item1_desc'=> get_option('fds_about_mitra_item1_desc', 'Kolaborasi teknologi pertanian presisi dan ketahanan pangan nasional melalui Program PRISMA.'),

        'mitra_item2_cat' => get_option('fds_about_mitra_item2_cat', 'Moneter & Pangan'),
        'mitra_item2_name'=> get_option('fds_about_mitra_item2_name', 'Bank Indonesia'),
        'mitra_item2_desc'=> get_option('fds_about_mitra_item2_desc', 'Penyediaan ekosistem drone agrikultur terpadu untuk penguatan klaster ketahanan pangan daerah.'),

        'mitra_item3_cat' => get_option('fds_about_mitra_item3_cat', 'Riset Akademis & Konservasi'),
        'mitra_item3_name'=> get_option('fds_about_mitra_item3_name', 'UGM & Mitra Riset Swiss'),
        'mitra_item3_desc'=> get_option('fds_about_mitra_item3_desc', 'Riset bersama teknologi reboisasi benih udara (seedball) dan pemetaan geospasial berkelanjutan.'),

        'mitra_item4_cat' => get_option('fds_about_mitra_item4_cat', 'Agroindustri & Pupuk'),
        'mitra_item4_name'=> get_option('fds_about_mitra_item4_name', 'Pupuk Indonesia, Petrokimia Kayaku & Petrosida'),
        'mitra_item4_desc'=> get_option('fds_about_mitra_item4_desc', 'Uji efektivitas penyemprotan pupuk cair dan pestisida presisi di berbagai sentra pertanian.'),

        'mitra_item5_cat' => get_option('fds_about_mitra_item5_cat', 'Pertambangan & Energi'),
        'mitra_item5_name'=> get_option('fds_about_mitra_item5_name', 'Pertamina, PLN, PAMA & MHU Coal'),
        'mitra_item5_desc'=> get_option('fds_about_mitra_item5_desc', 'Inspeksi termal jaringan transmisi 150 kV, solar farm, cerobong migas, dan volumetri stockpile tambang.'),

        'mitra_item6_cat' => get_option('fds_about_mitra_item6_cat', 'BUMN & Keuangan'),
        'mitra_item6_name'=> get_option('fds_about_mitra_item6_name', 'SUCOFINDO, Bank BRI, BNI & Perhutani'),
        'mitra_item6_desc'=> get_option('fds_about_mitra_item6_desc', 'Verifikasi data geospasial, pemetaan tutupan hutan, dan kemitraan pembiayaan modernisasi agritech.'),

        'mitra_item7_cat' => get_option('fds_about_mitra_item7_cat', 'Logistik & Kehutanan'),
        'mitra_item7_name'=> get_option('fds_about_mitra_item7_name', 'Sinarmas Forestry, RAPP, KAI, J&T & BRIN'),
        'mitra_item7_desc'=> get_option('fds_about_mitra_item7_desc', 'Survei kanopi hutan, inspeksi jalur rel kereta api, riset UAV BRIN, dan pengujian logistik otonom.'),

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
        'info_alamat'     => get_option('fds_about_info_alamat', 'DI Yogyakarta, Indonesia'),
        'info_email'      => get_option('fds_about_info_email', 'info@fulldronesolutions.com'),
        'info_layanan'    => get_option('fds_about_info_layanan', 'Konsultasi Proyek & Pengadaan Korporasi'),
    ];
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
        <!-- TAB 4: KEMITRAAN & KLIEN STRATEGIS -->
        <!-- ========================================================= -->
        <div class="fds-tab-pane" id="tab-mitra" style="display:none;">
          <div class="fds-card">
            <h2>🤝 Kemitraan &amp; Klien Strategis (Header)</h2>
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
            <h2>📋 Daftar 7 Kemitraan Strategis</h2>
            
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
            <h2>📍 Box Info Workshop &amp; Kantor</h2>
            <div class="fds-field">
              <label>Judul Box</label>
              <input type="text" name="fds_about_info_title" value="<?php echo esc_attr($data['info_title']); ?>">
            </div>
            <div class="fds-grid-2">
              <div class="fds-field">
                <label>Entitas Perusahaan</label>
                <input type="text" name="fds_about_info_entitas" value="<?php echo esc_attr($data['info_entitas']); ?>">
              </div>
              <div class="fds-field">
                <label>Alamat Workshop</label>
                <input type="text" name="fds_about_info_alamat" value="<?php echo esc_attr($data['info_alamat']); ?>">
              </div>
            </div>
            <div class="fds-grid-2">
              <div class="fds-field">
                <label>Email Resmi</label>
                <input type="text" name="fds_about_info_email" value="<?php echo esc_attr($data['info_email']); ?>">
              </div>
              <div class="fds-field">
                <label>Layanan Cepat</label>
                <input type="text" name="fds_about_info_layanan" value="<?php echo esc_attr($data['info_layanan']); ?>">
              </div>
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
