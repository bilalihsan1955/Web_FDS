<?php

namespace App;

/**
 * =========================================================================
 * FDS GLOBAL CONTACT & SOCIAL MEDIA MANAGER (PT KARYA SOLUSI ANGKASA)
 * =========================================================================
 * Pusat kontrol terpadu untuk mengelola seluruh informasi kontak, alamat,
 * email, telepon, tautan Google Maps, serta akun sosial media (dengan toggle
 * aktif/nonaktif) dan footer di seluruh halaman website FDS.
 */

// 1. DAFTARKAN MENU TERPUSAT DI WP ADMIN
add_action('admin_menu', function () {
    add_menu_page(
        'Pengaturan Kontak & Sosial Media',
        'Kontak & Sosmed',
        'manage_options',
        'fds-footer-settings',
        __NAMESPACE__ . '\\render_global_contact_admin_page',
        'dashicons-share',
        28
    );
});

// 2. HELPER GLOBAL CONTACT & SOCIAL MEDIA (SINGLE SOURCE OF TRUTH)
function fds_get_global_contact() {
    $company_name = get_option('fds_global_company_name', get_option('fds_footer_company_name', 'PT Karya Solusi Angkasa (Full Drone Solutions)'));
    $address      = get_option('fds_global_address', get_option('fds_footer_address', get_option('fds_kontak_address', 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281')));
    $phone        = get_option('fds_global_phone', get_option('fds_footer_phone', get_option('fds_kontak_phone', '+62 8112 748 882')));
    $email        = get_option('fds_global_email', get_option('fds_footer_email', get_option('fds_kontak_email', 'marketing@fulldronesolutions.com')));
    $wa_link      = get_option('fds_global_wa_link', get_option('fds_kontak_wa_link', 'https://wa.me/628112748882'));
    $maps_url     = get_option('fds_global_maps_url', get_option('fds_kontak_maps', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid'));

    $data = [
        // KONTAK TERPUSAT
        'company_name' => $company_name,
        'address'      => $address,
        'phone'        => $phone,
        'email'        => $email,
        'wa_link'      => $wa_link,
        'maps_url'     => $maps_url,

        // SOSIAL MEDIA (URL & TOGGLE AKTIF)
        'instagram'        => get_option('fds_sosmed_instagram_url', get_option('fds_footer_instagram', 'https://instagram.com/fulldronesolutions')),
        'instagram_active' => (bool) get_option('fds_sosmed_instagram_active', 1),

        'youtube'          => get_option('fds_sosmed_youtube_url', get_option('fds_footer_youtube', 'https://youtube.com/@fulldronesolutions')),
        'youtube_active'   => (bool) get_option('fds_sosmed_youtube_active', 1),

        'linkedin'         => get_option('fds_sosmed_linkedin_url', get_option('fds_footer_linkedin', 'https://linkedin.com/company/fulldronesolutions')),
        'linkedin_active'  => (bool) get_option('fds_sosmed_linkedin_active', 1),

        'tiktok'           => get_option('fds_sosmed_tiktok_url', get_option('fds_footer_tiktok', 'https://tiktok.com/@fulldronesolutions')),
        'tiktok_active'    => (bool) get_option('fds_sosmed_tiktok_active', 1),

        'twitter'          => get_option('fds_sosmed_twitter_url', 'https://x.com/fulldronesolutions'),
        'twitter_active'   => (bool) get_option('fds_sosmed_twitter_active', 1),

        'whatsapp'         => get_option('fds_sosmed_whatsapp_url', get_option('fds_footer_whatsapp', 'https://wa.me/628112748882')),
        'whatsapp_active'  => (bool) get_option('fds_sosmed_whatsapp_active', 1),

        // FOOTER DISCLAIMER & LEGAL
        'disclaimer'   => get_option('fds_footer_disclaimer', 'PT Karya Solusi Angkasa (Full Drone Solutions) — Advanced UAV Engineering, Manufacturing & AI Technology. Sertifikasi ISO 9001:2015, SNI 9199:2023, serta Sertifikasi Nilai TKDN + BMP mencapai 60,74% diterbitkan resmi oleh Kementerian Perindustrian Republik Indonesia. Spesifikasi dapat disesuaikan dengan kebutuhan misi kustom.'),
        'copyright'    => get_option('fds_footer_copyright', 'Copyright © ' . date('Y') . ' PT Karya Solusi Angkasa (Full Drone Solutions). Hak cipta dilindungi.'),
        'privacy_url'  => get_option('fds_footer_privacy_url', '#'),
        'terms_url'    => get_option('fds_footer_terms_url', '#'),
    ];

    // Decode HTML entities berulang (&amp;amp; → &amp; → &, dll)
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

// Backward-compatible alias
function fds_get_footer_data() {
    return fds_get_global_contact();
}

// 3. TAMPILAN HALAMAN PENGATURAN WP ADMIN TERPADU
function render_global_contact_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $message = '';
    if (isset($_POST['fds_global_contact_save']) && check_admin_referer('fds_global_contact_nonce_action', 'fds_global_contact_nonce')) {
        // 1. Simpan Kontak Global
        $company_name = sanitize_text_field($_POST['fds_global_company_name'] ?? '');
        $address      = sanitize_textarea_field($_POST['fds_global_address'] ?? '');
        $phone        = sanitize_text_field($_POST['fds_global_phone'] ?? '');
        $email        = sanitize_email($_POST['fds_global_email'] ?? '');
        $wa_link      = esc_url_raw($_POST['fds_global_wa_link'] ?? '');
        $maps_url     = esc_url_raw($_POST['fds_global_maps_url'] ?? '');

        update_option('fds_global_company_name', $company_name);
        update_option('fds_global_address', $address);
        update_option('fds_global_phone', $phone);
        update_option('fds_global_email', $email);
        update_option('fds_global_wa_link', $wa_link);
        update_option('fds_global_maps_url', $maps_url);

        // Sinkronisasi otomatis ke opsi lama agar seluruh bagian sinkron
        update_option('fds_footer_company_name', $company_name);
        update_option('fds_footer_address', $address);
        update_option('fds_footer_phone', $phone);
        update_option('fds_footer_email', $email);
        update_option('fds_kontak_address', $address);
        update_option('fds_kontak_phone', $phone);
        update_option('fds_kontak_email', $email);
        update_option('fds_kontak_wa_link', $wa_link);
        update_option('fds_kontak_maps', $maps_url);
        update_option('fds_about_info_entitas', $company_name);
        update_option('fds_about_info_alamat', $address);
        update_option('fds_about_info_phone', $phone);
        update_option('fds_about_info_email', $email);
        update_option('fds_about_info_maps', $maps_url);

        // 2. Simpan Sosial Media & Status Aktif
        update_option('fds_sosmed_instagram_url', esc_url_raw($_POST['fds_sosmed_instagram_url'] ?? ''));
        update_option('fds_sosmed_instagram_active', isset($_POST['fds_sosmed_instagram_active']) ? 1 : 0);

        update_option('fds_sosmed_youtube_url', esc_url_raw($_POST['fds_sosmed_youtube_url'] ?? ''));
        update_option('fds_sosmed_youtube_active', isset($_POST['fds_sosmed_youtube_active']) ? 1 : 0);

        update_option('fds_sosmed_linkedin_url', esc_url_raw($_POST['fds_sosmed_linkedin_url'] ?? ''));
        update_option('fds_sosmed_linkedin_active', isset($_POST['fds_sosmed_linkedin_active']) ? 1 : 0);

        update_option('fds_sosmed_tiktok_url', esc_url_raw($_POST['fds_sosmed_tiktok_url'] ?? ''));
        update_option('fds_sosmed_tiktok_active', isset($_POST['fds_sosmed_tiktok_active']) ? 1 : 0);

        update_option('fds_sosmed_twitter_url', esc_url_raw($_POST['fds_sosmed_twitter_url'] ?? ''));
        update_option('fds_sosmed_twitter_active', isset($_POST['fds_sosmed_twitter_active']) ? 1 : 0);

        update_option('fds_sosmed_whatsapp_url', esc_url_raw($_POST['fds_sosmed_whatsapp_url'] ?? ''));
        update_option('fds_sosmed_whatsapp_active', isset($_POST['fds_sosmed_whatsapp_active']) ? 1 : 0);

        // 3. Simpan Footer Disclaimer & Legal
        update_option('fds_footer_disclaimer', sanitize_textarea_field($_POST['fds_footer_disclaimer'] ?? ''));
        update_option('fds_footer_copyright', sanitize_text_field($_POST['fds_footer_copyright'] ?? ''));
        update_option('fds_footer_privacy_url', esc_url_raw($_POST['fds_footer_privacy_url'] ?? '#'));
        update_option('fds_footer_terms_url', esc_url_raw($_POST['fds_footer_terms_url'] ?? '#'));

        $message = 'Pengaturan Kontak Global, Sosial Media &amp; Footer berhasil diperbarui dan disinkronkan ke seluruh halaman!';
    }

    $c = fds_get_global_contact();
    ?>
    <div class="wrap" style="max-width: 980px; margin-top: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

        <!-- HEADER CARD -->
        <div style="background: #fff; padding: 24px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 8px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #0066cc; display: flex; align-items: center; justify-content: center; color: #fff;">
                    <span class="dashicons dashicons-share" style="font-size: 24px; width: 24px; height: 24px;"></span>
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #1e293b;">Pusat Pengaturan Kontak, Sosmed &amp; Footer</h1>
                    <p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">Kelola informasi kontak dan sosial media di <strong>satu tempat saja</strong>. Perubahan di sini otomatis berlaku di Beranda, Tentang Kami, Footer, dan seluruh halaman.</p>
                </div>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                <span class="dashicons dashicons-yes-alt" style="color: #10b981;"></span>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <?php wp_nonce_field('fds_global_contact_nonce_action', 'fds_global_contact_nonce'); ?>

            <!-- CARD 1: INFORMASI KONTAK TERPUSAT (GLOBAL) -->
            <div style="background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                    <div>
                        <h2 style="font-size: 16px; font-weight: 600; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                            <span class="dashicons dashicons-location" style="color: #0066cc;"></span> Informasi Kontak Terpusat (Global)
                        </h2>
                        <p style="font-size: 13px; color: #64748b; margin: 4px 0 0;">Otomatis tersinkronisasi ke Beranda (#kontak), Tentang Kami (Workshop Section), dan Footer website.</p>
                    </div>
                    <span style="font-size: 11px; font-weight: 600; background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px;">Single Source of Truth</span>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Nama Entitas / Perusahaan Resmi</label>
                    <input type="text" name="fds_global_company_name" value="<?php echo esc_attr($c['company_name']); ?>" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Alamat Lengkap Workshop &amp; Kantor</label>
                    <textarea name="fds_global_address" rows="2" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 14px;"><?php echo esc_textarea($c['address']); ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 18px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Nomor Telepon / Call Center</label>
                        <input type="text" name="fds_global_phone" value="<?php echo esc_attr($c['phone']); ?>" placeholder="+62 8112 748 882" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Email Resmi Perusahaan</label>
                        <input type="email" name="fds_global_email" value="<?php echo esc_attr($c['email']); ?>" placeholder="marketing@fulldronesolutions.com" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 14px;">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Link WhatsApp Direct (misal: https://wa.me/628112748882)</label>
                    <input type="text" name="fds_global_wa_link" value="<?php echo esc_attr($c['wa_link']); ?>" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">URL Google Maps Embed (Iframe)</label>
                    <input type="text" name="fds_global_maps_url" value="<?php echo esc_attr($c['maps_url']); ?>" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 13px;">
                </div>
            </div>

            <!-- CARD 2: AKUN SOSIAL MEDIA (DENGAN TOGGLE AKTIF/NONAKTIF) -->
            <div style="background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 600; color: #0f172a; margin-top: 0; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                    <span class="dashicons dashicons-networking" style="color: #0066cc;"></span> Akun Sosial Media &amp; Kontrol Tampilan
                </h2>
                <p style="font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 20px;">Centang <strong>Aktif</strong> untuk menampilkan ikon sosial media di footer. Hapus centang untuk menyembunyikan tanpa menghapus tautan URL.</p>

                <div style="display: flex; flex-direction: column; gap: 16px;">

                    <!-- INSTAGRAM -->
                    <div style="display: grid; grid-template-columns: 140px 1fr 100px; gap: 14px; align-items: center; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="font-weight: 600; font-size: 13px; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                            📸 Instagram
                        </span>
                        <input type="url" name="fds_sosmed_instagram_url" value="<?php echo esc_attr($c['instagram']); ?>" placeholder="https://instagram.com/fulldronesolutions" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="fds_sosmed_instagram_active" value="1" <?php checked($c['instagram_active'], true); ?>>
                            Aktif
                        </label>
                    </div>

                    <!-- YOUTUBE -->
                    <div style="display: grid; grid-template-columns: 140px 1fr 100px; gap: 14px; align-items: center; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="font-weight: 600; font-size: 13px; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                            ▶️ YouTube
                        </span>
                        <input type="url" name="fds_sosmed_youtube_url" value="<?php echo esc_attr($c['youtube']); ?>" placeholder="https://youtube.com/@fulldronesolutions" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="fds_sosmed_youtube_active" value="1" <?php checked($c['youtube_active'], true); ?>>
                            Aktif
                        </label>
                    </div>

                    <!-- LINKEDIN -->
                    <div style="display: grid; grid-template-columns: 140px 1fr 100px; gap: 14px; align-items: center; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="font-weight: 600; font-size: 13px; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                            💼 LinkedIn
                        </span>
                        <input type="url" name="fds_sosmed_linkedin_url" value="<?php echo esc_attr($c['linkedin']); ?>" placeholder="https://linkedin.com/company/fulldronesolutions" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="fds_sosmed_linkedin_active" value="1" <?php checked($c['linkedin_active'], true); ?>>
                            Aktif
                        </label>
                    </div>

                    <!-- TIKTOK -->
                    <div style="display: grid; grid-template-columns: 140px 1fr 100px; gap: 14px; align-items: center; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="font-weight: 600; font-size: 13px; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                            🎵 TikTok
                        </span>
                        <input type="url" name="fds_sosmed_tiktok_url" value="<?php echo esc_attr($c['tiktok']); ?>" placeholder="https://tiktok.com/@fulldronesolutions" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="fds_sosmed_tiktok_active" value="1" <?php checked($c['tiktok_active'], true); ?>>
                            Aktif
                        </label>
                    </div>

                    <!-- TWITTER / X -->
                    <div style="display: grid; grid-template-columns: 140px 1fr 100px; gap: 14px; align-items: center; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="font-weight: 600; font-size: 13px; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                            𝕏 Twitter / X
                        </span>
                        <input type="url" name="fds_sosmed_twitter_url" value="<?php echo esc_attr($c['twitter']); ?>" placeholder="https://x.com/fulldronesolutions" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="fds_sosmed_twitter_active" value="1" <?php checked($c['twitter_active'], true); ?>>
                            Aktif
                        </label>
                    </div>

                    <!-- WHATSAPP DIRECT -->
                    <div style="display: grid; grid-template-columns: 140px 1fr 100px; gap: 14px; align-items: center; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="font-weight: 600; font-size: 13px; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                            💬 WhatsApp
                        </span>
                        <input type="text" name="fds_sosmed_whatsapp_url" value="<?php echo esc_attr($c['whatsapp']); ?>" placeholder="https://wa.me/628112748882" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 10px; font-size: 13px;">
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="fds_sosmed_whatsapp_active" value="1" <?php checked($c['whatsapp_active'], true); ?>>
                            Aktif
                        </label>
                    </div>

                </div>
            </div>

            <!-- CARD 3: DISCLAIMER & HAK CIPTA -->
            <div style="background: #fff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 600; color: #0f172a; margin-top: 0; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                    <span class="dashicons dashicons-media-document" style="color: #0066cc;"></span> Disclaimer, Legalitas &amp; Hak Cipta Footer
                </h2>
                <p style="font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 20px;">Teks sertifikasi mutu Kemenperin dan baris hak cipta di bagian terbawah footer.</p>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Teks Disclaimer Mutu &amp; Sertifikasi</label>
                    <textarea name="fds_footer_disclaimer" rows="3" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 13px;"><?php echo esc_textarea($c['disclaimer']); ?></textarea>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Teks Hak Cipta (Copyright)</label>
                    <input type="text" name="fds_footer_copyright" value="<?php echo esc_attr($c['copyright']); ?>" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 14px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">URL Kebijakan Privasi</label>
                        <input type="text" name="fds_footer_privacy_url" value="<?php echo esc_attr($c['privacy_url']); ?>" placeholder="#" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">URL Ketentuan Layanan</label>
                        <input type="text" name="fds_footer_terms_url" value="<?php echo esc_attr($c['terms_url']); ?>" placeholder="#" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; font-size: 14px;">
                    </div>
                </div>
            </div>

            <!-- SAVE BUTTON -->
            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" name="fds_global_contact_save" class="button button-primary button-large" style="background: #0066cc; border-color: #0066cc; font-weight: 600; padding: 6px 28px; border-radius: 8px; font-size: 14px;">
                    💾 Simpan Seluruh Pengaturan Kontak &amp; Sosmed
                </button>
            </div>

        </form>
    </div>
    <?php
}
