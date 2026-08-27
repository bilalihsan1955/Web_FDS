<?php

namespace App;

/**
 * =========================================================================
 * FDS NAVBAR & LOGO MANAGER (PT KARYA SOLUSI ANGKASA)
 * =========================================================================
 * Mengelola logo, teks brand, favicon, dan pengaturan visual navbar secara
 * dinamis melalui WP Admin. Menghilangkan ketergantungan pada Appearance -> Customize.
 */

// 1. DAFTARKAN MENU PENGATURAN LOGO & NAVBAR DI WP ADMIN
add_action('admin_menu', function () {
    add_menu_page(
        'Pengaturan Logo & Navbar',
        'Logo & Navbar',
        'manage_options',
        'fds-navbar-settings',
        __NAMESPACE__ . '\\render_navbar_settings_admin_page',
        'dashicons-art',
        27
    );
});

// 2. HAPUS APPEARANCE -> CUSTOMIZE DARI ADMIN MENU & ADMIN BAR KARENA SUDAH TERSEDIA DI MENU KHUSUS
add_action('admin_menu', function () {
    remove_submenu_page('themes.php', 'customize.php');
    if (isset($_SERVER['REQUEST_URI'])) {
        remove_submenu_page('themes.php', 'customize.php?return=' . urlencode(wp_unslash($_SERVER['REQUEST_URI'])));
    }

    global $submenu;
    if (isset($submenu['themes.php'])) {
        foreach ($submenu['themes.php'] as $key => $item) {
            if (isset($item[2]) && strpos($item[2], 'customize') !== false) {
                unset($submenu['themes.php'][$key]);
            }
            if (isset($item[1]) && $item[1] === 'customize') {
                unset($submenu['themes.php'][$key]);
            }
        }
    }
}, 9999);

add_action('admin_head', function () {
    echo '<style>#menu-appearance a[href*="customize.php"], #wp-admin-bar-customize { display: none !important; }</style>';
});

add_action('admin_bar_menu', function ($wp_admin_bar) {
    $wp_admin_bar->remove_node('customize');
}, 9999);

// Jika admin membuka customize.php secara langsung, arahkan ke Konten Beranda
add_action('load-customize.php', function () {
    wp_safe_redirect(admin_url('admin.php?page=fds-homepage-content'));
    exit;
});

// 3. ENQUEUE WP MEDIA UPLOADER
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_fds-navbar-settings') {
        wp_enqueue_media();
    }
});

// 4. HELPER UNTUK MENGAMBIL DATA LOGO & BRAND NAVBAR DI FRONTEND
function fds_get_navbar_brand() {
    $custom_logo_id = get_theme_mod('custom_logo');
    $custom_logo_url = '';
    if ($custom_logo_id) {
        $img_src = wp_get_attachment_image_src($custom_logo_id, 'full');
        if (!empty($img_src[0])) {
            $custom_logo_url = $img_src[0];
        }
    }

    $saved_logo_url = get_option('fds_navbar_logo_url', '');
    $logo_url = !empty($saved_logo_url) ? $saved_logo_url : $custom_logo_url;

    $saved_brand_text = get_option('fds_navbar_brand_text', null);
    if ($saved_brand_text === null) {
        $brand_text = get_bloginfo('name') ?: 'Full Drone Solutions';
    } else {
        $brand_text = $saved_brand_text;
    }

    $display_mode = get_option('fds_navbar_display_mode', 'both');
    $logo_height = (int) get_option('fds_navbar_logo_height', 28);
    if ($logo_height < 16 || $logo_height > 60) {
        $logo_height = 28;
    }

    $favicon_url = get_option('fds_site_favicon_url', '');
    if (empty($favicon_url)) {
        $site_icon_id = get_option('site_icon');
        if ($site_icon_id) {
            $favicon_url = wp_get_attachment_image_url($site_icon_id, 'full') ?: '';
        }
    }

    return [
        'has_logo'     => !empty($logo_url),
        'logo_url'     => $logo_url,
        'brand_text'   => $brand_text,
        'display_mode' => $display_mode,
        'logo_height'  => $logo_height,
        'favicon_url'  => $favicon_url,
    ];
}

// 5. TAMPILAN HALAMAN PENGATURAN WP ADMIN
function render_navbar_settings_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $message = '';
    if (isset($_POST['fds_navbar_save']) && check_admin_referer('fds_navbar_nonce_action', 'fds_navbar_nonce')) {
        $logo_url     = esc_url_raw($_POST['fds_navbar_logo_url'] ?? '');
        $brand_text   = sanitize_text_field($_POST['fds_navbar_brand_text'] ?? '');
        $display_mode = sanitize_text_field($_POST['fds_navbar_display_mode'] ?? 'both');
        $logo_height  = max(16, min(60, (int) ($_POST['fds_navbar_logo_height'] ?? 28)));
        $favicon_url  = esc_url_raw($_POST['fds_site_favicon_url'] ?? '');

        update_option('fds_navbar_logo_url', $logo_url);
        update_option('fds_navbar_brand_text', $brand_text);
        update_option('fds_navbar_display_mode', $display_mode);
        update_option('fds_navbar_logo_height', $logo_height);
        update_option('fds_site_favicon_url', $favicon_url);

        // Jika favicon diisi, cari attachment ID untuk sinkronisasi ke core site_icon
        if (!empty($favicon_url)) {
            $attachment_id = attachment_url_to_postid($favicon_url);
            if ($attachment_id) {
                update_option('site_icon', $attachment_id);
            }
        }

        $message = 'Pengaturan Logo, Navbar &amp; Favicon berhasil disimpan!';
    }

    $brand_data = fds_get_navbar_brand();
    ?>
    <div class="wrap" style="max-width: 900px; margin-top: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
        <div style="background: #fff; padding: 24px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 8px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #0066cc; display: flex; align-items: center; justify-content: center; color: #fff;">
                    <span class="dashicons dashicons-art" style="font-size: 24px; width: 24px; height: 24px;"></span>
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #1e293b;">Pengaturan Logo, Brand &amp; Favicon Navbar</h1>
                    <p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">Kelola logo gambar, favicon tab browser, dan teks brand yang tampil di pojok kiri atas header website.</p>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div style="background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; padding: 14px 18px; border-radius: 6px; margin: 20px 0 10px; font-size: 14px; font-weight: 500;">
                    ✓ <?php echo $message; ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="post" action="">
            <?php wp_nonce_field('fds_navbar_nonce_action', 'fds_navbar_nonce'); ?>

            <div style="background: #fff; padding: 30px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 28px;">

                <!-- 1. LIVE PREVIEW NAVBAR -->
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
                        Live Preview Navbar
                    </label>
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 24px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                        <div id="fds-preview-brand-container" style="display: flex; align-items: center; gap: 10px;">
                            <img id="fds-preview-logo-img" 
                                 src="<?php echo esc_url($brand_data['logo_url']); ?>" 
                                 alt="Logo Preview" 
                                 style="height: <?php echo esc_attr($brand_data['logo_height']); ?>px; width: auto; object-fit: contain; <?php echo empty($brand_data['logo_url']) ? 'display:none;' : ''; ?>">
                            
                            <svg id="fds-preview-fallback-icon" class="w-5 h-5 text-[#1d1d1f]" style="width: 20px; height: 20px; color: #1d1d1f; <?php echo !empty($brand_data['logo_url']) ? 'display:none;' : ''; ?>" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2L3 6l7 4 7-4-7-4zM3 14l7 4 7-4M3 10l7 4 7-4"/>
                            </svg>
                            
                            <span id="fds-preview-brand-text" style="font-size: 15px; font-weight: 600; color: #1d1d1f; letter-spacing: -0.01em;">
                                <?php echo esc_html($brand_data['brand_text']); ?>
                            </span>
                        </div>

                        <!-- Dummy Navigation Elements -->
                        <div style="display: flex; align-items: center; gap: 20px; color: #64748b; font-size: 13px; font-weight: 500;">
                            <span>Beranda</span>
                            <span>Produk</span>
                            <span>Layanan</span>
                            <span>Tentang Kami</span>
                            <span>Blog</span>
                        </div>
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 0;">

                <!-- 2. UPLOAD LOGO GAMBAR -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                        Logo Gambar Navbar (PNG, SVG, JPG, WebP)
                    </label>
                    <p style="margin: 0 0 12px; color: #64748b; font-size: 13px;">Unggah file logo perusahaan Anda. Format transparan (.PNG atau .SVG) sangat direkomendasikan.</p>

                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                        <input type="text" 
                               id="fds_navbar_logo_url" 
                               name="fds_navbar_logo_url" 
                               value="<?php echo esc_url($brand_data['logo_url']); ?>" 
                               class="regular-text" 
                               style="flex: 1; font-size: 13px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                               placeholder="https://domain.com/wp-content/uploads/.../logo.png">
                        
                        <button type="button" id="fds_upload_logo_btn" class="button button-primary" style="padding: 6px 16px; font-size: 13px; font-weight: 600; background: #0066cc; border-color: #0066cc;">
                            Pilih / Unggah Logo
                        </button>
                        
                        <button type="button" id="fds_remove_logo_btn" class="button" style="padding: 6px 14px; font-size: 13px; color: #dc2626; border-color: #fecaca;">
                            Hapus Logo
                        </button>
                    </div>
                </div>

                <!-- 3. UPLOAD FAVICON (SITE ICON) -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                        Favicon / Site Icon (Ikon Tab Browser)
                    </label>
                    <p style="margin: 0 0 12px; color: #64748b; font-size: 13px;">Ikon kecil yang tampil di tab browser pengunjung (Rekomendasi: PNG 512×512 piksel).</p>

                    <div style="display: flex; align-items: center; gap: 16px;">
                        <?php if (!empty($brand_data['favicon_url'])): ?>
                            <img id="fds_favicon_preview" src="<?php echo esc_url($brand_data['favicon_url']); ?>" style="width: 36px; height: 36px; object-fit: contain; border-radius: 6px; border: 1px solid #cbd5e1; background: #f8fafc; padding: 2px;">
                        <?php else: ?>
                            <div id="fds_favicon_preview" style="width: 36px; height: 36px; border-radius: 6px; border: 1px solid #cbd5e1; background: #f8fafc; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #94a3b8;">Icon</div>
                        <?php endif; ?>

                        <input type="text" 
                               id="fds_site_favicon_url" 
                               name="fds_site_favicon_url" 
                               value="<?php echo esc_url($brand_data['favicon_url']); ?>" 
                               style="flex: 1; font-size: 13px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                               placeholder="https://domain.com/.../favicon.png">

                        <button type="button" id="fds_upload_favicon_btn" class="button" style="padding: 6px 14px; font-size: 13px; font-weight: 600;">
                            Pilih Favicon
                        </button>
                    </div>
                </div>

                <!-- 4. TEKS BRAND / NAMA PERUSAHAAN -->
                <div>
                    <label for="fds_navbar_brand_text" style="display: block; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                        Teks Brand / Nama Perusahaan di Navbar
                    </label>
                    <p style="margin: 0 0 12px; color: #64748b; font-size: 13px;">Teks yang tampil di samping logo gambar.</p>
                    
                    <input type="text" 
                           id="fds_navbar_brand_text" 
                           name="fds_navbar_brand_text" 
                           value="<?php echo esc_attr($brand_data['brand_text']); ?>" 
                           style="width: 100%; max-width: 500px; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                           placeholder="Contoh: Full Drone Solutions">
                </div>

                <!-- 5. MODE TAMPILAN -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
                        Mode Tampilan Navbar
                    </label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; max-width: 600px;">
                        <label style="display: flex; align-items: center; gap: 8px; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #f8fafc;">
                            <input type="radio" name="fds_navbar_display_mode" value="both" <?php checked($brand_data['display_mode'], 'both'); ?>>
                            <span style="font-size: 13px; font-weight: 600; color: #1e293b;">Logo + Teks Brand</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #f8fafc;">
                            <input type="radio" name="fds_navbar_display_mode" value="logo_only" <?php checked($brand_data['display_mode'], 'logo_only'); ?>>
                            <span style="font-size: 13px; font-weight: 600; color: #1e293b;">Hanya Logo Gambar</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #f8fafc;">
                            <input type="radio" name="fds_navbar_display_mode" value="text_only" <?php checked($brand_data['display_mode'], 'text_only'); ?>>
                            <span style="font-size: 13px; font-weight: 600; color: #1e293b;">Hanya Teks Brand</span>
                        </label>
                    </div>
                </div>

                <!-- 6. TINGGI LOGO (PX) -->
                <div>
                    <label for="fds_navbar_logo_height" style="display: block; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                        Tinggi Logo Navbar (Pixel)
                    </label>
                    <p style="margin: 0 0 12px; color: #64748b; font-size: 13px;">Sesuaikan tinggi logo gambar agar pas di dalam navbar (Direkomendasikan: 24 – 34 px).</p>
                    
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="number" 
                               id="fds_navbar_logo_height" 
                               name="fds_navbar_logo_height" 
                               value="<?php echo esc_attr($brand_data['logo_height']); ?>" 
                               min="16" 
                               max="60" 
                               style="width: 90px; font-size: 14px; padding: 6px 10px; border-radius: 6px; border: 1px solid #cbd5e1;">
                        <span style="color: #64748b; font-size: 13px; font-weight: 500;">px</span>
                    </div>
                </div>

                <!-- TOMBOL SIMPAN -->
                <div style="padding-top: 10px; border-top: 1px solid #f1f5f9;">
                    <button type="submit" name="fds_navbar_save" class="button button-primary button-large" style="background: #0066cc; border-color: #0066cc; font-size: 14px; font-weight: 600; padding: 8px 24px; border-radius: 6px; height: auto;">
                        💾 Simpan Perubahan Logo, Navbar &amp; Favicon
                    </button>
                </div>

            </div>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Live Preview Updater
        function updateLivePreview() {
            let logoUrl = $('#fds_navbar_logo_url').val().trim();
            let brandText = $('#fds_navbar_brand_text').val().trim();
            let displayMode = $('input[name="fds_navbar_display_mode"]:checked').val();
            let logoHeight = parseInt($('#fds_navbar_logo_height').val()) || 28;

            let previewImg = $('#fds-preview-logo-img');
            let previewFallback = $('#fds-preview-fallback-icon');
            let previewText = $('#fds-preview-brand-text');

            if (displayMode === 'text_only') {
                previewImg.hide();
                previewFallback.hide();
                previewText.show().text(brandText || 'Full Drone Solutions');
            } else if (displayMode === 'logo_only') {
                previewText.hide();
                if (logoUrl) {
                    previewImg.attr('src', logoUrl).css('height', logoHeight + 'px').show();
                    previewFallback.hide();
                } else {
                    previewImg.hide();
                    previewFallback.show();
                }
            } else {
                // both
                if (logoUrl) {
                    previewImg.attr('src', logoUrl).css('height', logoHeight + 'px').show();
                    previewFallback.hide();
                } else {
                    previewImg.hide();
                    previewFallback.show();
                }
                previewText.show().text(brandText || 'Full Drone Solutions');
            }
        }

        // Trigger updates on input change
        $('#fds_navbar_brand_text, #fds_navbar_logo_height').on('input', updateLivePreview);
        $('input[name="fds_navbar_display_mode"]').on('change', updateLivePreview);

        // WP Media Uploader for Logo
        $('#fds_upload_logo_btn').on('click', function(e) {
            e.preventDefault();
            var customUploader = wp.media({
                title: 'Pilih atau Unggah Logo Navbar',
                button: { text: 'Gunakan Logo Ini' },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();
                $('#fds_navbar_logo_url').val(attachment.url);
                updateLivePreview();
            }).open();
        });

        // WP Media Uploader for Favicon
        $('#fds_upload_favicon_btn').on('click', function(e) {
            e.preventDefault();
            var customUploader = wp.media({
                title: 'Pilih atau Unggah Favicon / Site Icon',
                button: { text: 'Gunakan Favicon Ini' },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();
                $('#fds_site_favicon_url').val(attachment.url);
                $('#fds_favicon_preview').replaceWith('<img id="fds_favicon_preview" src="' + attachment.url + '" style="width: 36px; height: 36px; object-fit: contain; border-radius: 6px; border: 1px solid #cbd5e1; background: #f8fafc; padding: 2px;">');
            }).open();
        });

        // Remove Logo Button
        $('#fds_remove_logo_btn').on('click', function(e) {
            e.preventDefault();
            $('#fds_navbar_logo_url').val('');
            updateLivePreview();
        });
    });
    </script>
    <?php
}
