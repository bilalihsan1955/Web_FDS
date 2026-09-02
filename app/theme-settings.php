<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

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

// 2. DISABLE WORDPRESS TWEMOJI CONVERSION & PREVENT EMOJIS FROM BLOWING UP
add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
});

add_action('admin_head', function () {
    echo '<style>
        #menu-appearance a[href*="customize.php"], #wp-admin-bar-customize { display: none !important; }
        img.wp-smiley, img.emoji {
            display: inline !important;
            border: none !important;
            box-shadow: none !important;
            height: 1em !important;
            width: 1em !important;
            max-height: 1em !important;
            max-width: 1em !important;
            margin: 0 0.07em !important;
            vertical-align: -0.1em !important;
            background: none !important;
            padding: 0 !important;
        }
    </style>';
});

add_action('wp_head', function () {
    echo '<style>
        img.wp-smiley, img.emoji {
            display: inline !important;
            border: none !important;
            box-shadow: none !important;
            height: 1em !important;
            width: 1em !important;
            max-height: 1em !important;
            max-width: 1em !important;
            margin: 0 0.07em !important;
            vertical-align: -0.1em !important;
            background: none !important;
            padding: 0 !important;
        }
    </style>';
}, 1);

add_action('admin_bar_menu', function ($wp_admin_bar) {
    $wp_admin_bar->remove_node('customize');

    if (!current_user_can('manage_options')) {
        return;
    }

    // Shortcut langsung di Admin Bar untuk mengedit konten beranda tanpa tersesat
    $wp_admin_bar->add_node([
        'id'    => 'fds-quick-settings',
        'title' => '<span class="ab-icon dashicons dashicons-admin-generic" style="top:2px;"></span> Pengaturan FDS',
        'href'  => admin_url('admin.php?page=fds-homepage-content'),
    ]);

    $wp_admin_bar->add_node([
        'id'     => 'fds-edit-home',
        'parent' => 'fds-quick-settings',
        'title'  => '🏠 Konten Beranda (Home)',
        'href'   => admin_url('admin.php?page=fds-homepage-content'),
    ]);

    $wp_admin_bar->add_node([
        'id'     => 'fds-edit-about',
        'parent' => 'fds-quick-settings',
        'title'  => '🏢 Konten Tentang Kami',
        'href'   => admin_url('admin.php?page=fds-about-content'),
    ]);

    $wp_admin_bar->add_node([
        'id'     => 'fds-edit-navbar',
        'parent' => 'fds-quick-settings',
        'title'  => '🎨 Logo & Navbar',
        'href'   => admin_url('admin.php?page=fds-navbar-settings'),
    ]);

    $wp_admin_bar->add_node([
        'id'     => 'fds-edit-footer',
        'parent' => 'fds-quick-settings',
        'title'  => '📌 Footer & Kontak',
        'href'   => admin_url('admin.php?page=fds-footer-settings'),
    ]);
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
    $logo_height = (int) get_option('fds_navbar_logo_height', 34);
    if ($logo_height < 16 || $logo_height > 60) {
        $logo_height = 34;
    }

    $favicon_url = get_option('fds_site_favicon_url', '');
    if (empty($favicon_url)) {
        $site_icon_id = get_option('site_icon');
        if ($site_icon_id) {
            $favicon_url = wp_get_attachment_image_url($site_icon_id, 'full') ?: '';
        }
    }

    $drone_icon_url = get_option('fds_navbar_drone_icon_url', '');

    return [
        'has_logo'       => !empty($logo_url),
        'logo_url'       => $logo_url,
        'brand_text'     => $brand_text,
        'display_mode'   => $display_mode,
        'logo_height'    => $logo_height,
        'favicon_url'    => $favicon_url,
        'drone_icon_url' => $drone_icon_url,
    ];
}

// 4a. HELPER STANDALONE ICON DRONE (NAVBAR, SECTION DRONE, TENTANG KAMI, DLL)
function fds_get_drone_icon() {
    return get_option('fds_navbar_drone_icon_url', '');
}

function fds_get_navbar_drone_icon() {
    return fds_get_drone_icon();
}

// 4b. OTOMATIS TAMPILKAN ICON TAB BROWSER / FAVICON DI HEAD
add_action('wp_head', function () {
    $brand = fds_get_navbar_brand();
    if (!empty($brand['favicon_url'])) {
        $f_url = esc_url($brand['favicon_url']);
        echo '<link rel="icon" type="image/png" href="' . $f_url . '">' . "\n";
        echo '<link rel="shortcut icon" href="' . $f_url . '">' . "\n";
        echo '<link rel="apple-touch-icon" href="' . $f_url . '">' . "\n";
    }
}, 1);

add_action('admin_head', function () {
    $brand = fds_get_navbar_brand();
    if (!empty($brand['favicon_url'])) {
        echo '<link rel="icon" type="image/png" href="' . esc_url($brand['favicon_url']) . '">' . "\n";
    }
});

// 4c. PENGATURAN NAMA TAB BROWSER & DOCUMENT TITLE SETIAP HALAMAN
add_filter('document_title_parts', function ($title_parts) {
    $brand_text = get_option('fds_navbar_brand_text', null);
    if (empty($brand_text) || $brand_text === 'FDS') {
        $brand_text = 'Full Drone Solutions';
    }

    // 1. HALAMAN UTAMA / BERANDA (HOME)
    if (is_front_page()) {
        $custom_home_title = get_option('fds_home_tab_title', '');
        if (!empty($custom_home_title)) {
            return ['title' => $custom_home_title];
        }
        $site_tagline = get_option('fds_site_tagline', '');
        if (empty($site_tagline)) {
            $site_tagline = get_bloginfo('description') ?: 'Solusi Drone Industri, Agrikultur & Pemetaan Indonesia';
        }
        return [
            'title'   => $brand_text,
            'tagline' => $site_tagline,
        ];
    }

    // 2. HALAMAN TENTANG KAMI
    if (is_page('tentang-kami') || is_page('about') || is_page('about-us')) {
        $custom_page_title = get_post_meta(get_the_ID(), '_fds_custom_tab_title', true);
        if (!empty($custom_page_title)) {
            return ['title' => $custom_page_title];
        }
        $about_title = get_option('fds_about_tab_title', '');
        if (!empty($about_title)) {
            return ['title' => $about_title];
        }
        return [
            'title' => 'Tentang Kami',
            'site'  => $brand_text,
        ];
    }

    // 3. HALAMAN PERBANDINGAN MODEL DRONE
    if (is_page('bandingkan')) {
        $custom_page_title = get_post_meta(get_the_ID(), '_fds_custom_tab_title', true);
        if (!empty($custom_page_title)) {
            return ['title' => $custom_page_title];
        }
        $compare_title = get_option('fds_compare_tab_title', '');
        if (!empty($compare_title)) {
            return ['title' => $compare_title];
        }
        return [
            'title' => 'Bandingkan Model UAV & Spesifikasi Teknis',
            'site'  => $brand_text,
        ];
    }

    // 4. BLOG / ARTIKEL
    if (is_home() || is_page('blog')) {
        $custom_page_title = is_page('blog') ? get_post_meta(get_the_ID(), '_fds_custom_tab_title', true) : '';
        if (!empty($custom_page_title)) {
            return ['title' => $custom_page_title];
        }
        $blog_title = get_option('fds_blog_tab_title', '');
        if (!empty($blog_title)) {
            return ['title' => $blog_title];
        }
        return [
            'title' => 'Blog & Artikel Edukasi Drone',
            'site'  => $brand_text,
        ];
    }

    // 5. DETAIL DRONE (CPT DRONE)
    if (is_singular('drone')) {
        $post_id = get_the_ID();
        $custom_drone_title = get_post_meta($post_id, '_fds_custom_tab_title', true);
        if (!empty($custom_drone_title)) {
            return ['title' => $custom_drone_title];
        }

        $drone_name = get_the_title($post_id);
        $badge = get_post_meta($post_id, 'drone_badge', true);
        $badge_str = !empty($badge) ? ' (' . $badge . ')' : '';
        $custom_suffix = get_option('fds_drone_tab_suffix', '');
        if (!empty($custom_suffix)) {
            return ['title' => $drone_name . $badge_str . ' ' . $custom_suffix];
        }
        return [
            'title' => $drone_name . $badge_str,
            'site'  => $brand_text,
        ];
    }

    // 6. HALAMAN TUNGGAL LAINNYA (SINGLE POST / PAGE)
    if (is_singular()) {
        $post_id = get_the_ID();
        $custom_tab_title = get_post_meta($post_id, '_fds_custom_tab_title', true);
        if (!empty($custom_tab_title)) {
            return ['title' => $custom_tab_title];
        }

        return [
            'title' => get_the_title($post_id),
            'site'  => $brand_text,
        ];
    }

    // 7. ARSIP KATEGORI DRONE
    if (is_tax('kategori_drone')) {
        $term = get_queried_object();
        return [
            'title' => 'Kategori Drone ' . ($term ? $term->name : ''),
            'site'  => $brand_text,
        ];
    }

    if (is_404()) {
        return [
            'title' => 'Halaman Tidak Ditemukan (404)',
            'site'  => $brand_text,
        ];
    }

    if (isset($title_parts['site'])) {
        $title_parts['site'] = $brand_text;
    }

    return $title_parts;
}, 30);

add_filter('document_title_separator', function ($sep) {
    return get_option('fds_tab_title_separator', '–');
});

// 5. TAMPILAN HALAMAN PENGATURAN WP ADMIN
function render_navbar_settings_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $message = '';
    if (isset($_POST['fds_navbar_save']) && check_admin_referer('fds_navbar_nonce_action', 'fds_navbar_nonce')) {
        $logo_url          = esc_url_raw($_POST['fds_navbar_logo_url'] ?? '');
        $brand_text        = sanitize_text_field($_POST['fds_navbar_brand_text'] ?? '');
        $display_mode      = sanitize_text_field($_POST['fds_navbar_display_mode'] ?? 'both');
        $logo_height       = max(16, min(60, (int) ($_POST['fds_navbar_logo_height'] ?? 28)));
        $favicon_url       = esc_url_raw($_POST['fds_site_favicon_url'] ?? '');
        $drone_icon_url    = esc_url_raw($_POST['fds_navbar_drone_icon_url'] ?? '');
        $home_tab_title    = sanitize_text_field($_POST['fds_home_tab_title'] ?? '');
        $about_tab_title   = sanitize_text_field($_POST['fds_about_tab_title'] ?? '');
        $compare_tab_title = sanitize_text_field($_POST['fds_compare_tab_title'] ?? '');
        $blog_tab_title    = sanitize_text_field($_POST['fds_blog_tab_title'] ?? '');
        $drone_tab_suffix  = sanitize_text_field($_POST['fds_drone_tab_suffix'] ?? '');
        $site_tagline      = sanitize_text_field($_POST['fds_site_tagline'] ?? '');
        $title_separator   = sanitize_text_field($_POST['fds_tab_title_separator'] ?? '–');

        update_option('fds_navbar_logo_url', $logo_url);
        update_option('fds_navbar_brand_text', $brand_text);
        update_option('fds_navbar_display_mode', $display_mode);
        update_option('fds_navbar_logo_height', $logo_height);
        update_option('fds_site_favicon_url', $favicon_url);
        update_option('fds_navbar_drone_icon_url', $drone_icon_url);
        update_option('fds_home_tab_title', $home_tab_title);
        update_option('fds_about_tab_title', $about_tab_title);
        update_option('fds_compare_tab_title', $compare_tab_title);
        update_option('fds_blog_tab_title', $blog_tab_title);
        update_option('fds_drone_tab_suffix', $drone_tab_suffix);
        update_option('fds_site_tagline', $site_tagline);
        update_option('fds_tab_title_separator', $title_separator);

        // Sinkronkan ke Core WordPress blogname & blogdescription
        if (!empty($brand_text)) {
            update_option('blogname', $brand_text);
        }
        if (!empty($site_tagline)) {
            update_option('blogdescription', $site_tagline);
        }

        // Jika favicon diisi, cari attachment ID untuk sinkronisasi ke core site_icon
        if (!empty($favicon_url)) {
            $attachment_id = attachment_url_to_postid($favicon_url);
            if ($attachment_id) {
                update_option('site_icon', $attachment_id);
            }
        } else {
            delete_option('site_icon');
        }

        $message = 'Pengaturan Logo, Navbar, Icon Tab &amp; Nama Tab Semua Halaman berhasil disimpan!';
    }

    $brand_data        = fds_get_navbar_brand();
    $home_tab_title    = get_option('fds_home_tab_title', '');
    $about_tab_title   = get_option('fds_about_tab_title', '');
    $compare_tab_title = get_option('fds_compare_tab_title', '');
    $blog_tab_title    = get_option('fds_blog_tab_title', '');
    $drone_tab_suffix  = get_option('fds_drone_tab_suffix', '');
    $site_tagline      = get_option('fds_site_tagline', get_bloginfo('description') ?: 'Solusi Drone Industri, Agrikultur & Pemetaan Indonesia');
    $title_separator   = get_option('fds_tab_title_separator', '–');
    ?>
    <div class="wrap" style="max-width: 100%; margin-top: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; box-sizing: border-box;">
        <div style="background: #fff; padding: 24px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 8px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #0066cc; display: flex; align-items: center; justify-content: center; color: #fff;">
                    <span class="dashicons dashicons-art" style="font-size: 24px; width: 24px; height: 24px;"></span>
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #1e293b;">Pengaturan Logo, Navbar &amp; Nama Tab Browser</h1>
                    <p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">Kelola logo gambar, ikon tab (favicon), teks brand, dan kustomisasi judul tab browser untuk Beranda, Tentang Kami, Perbandingan, Blog, dan Detail Drone.</p>
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
                        <div id="fds-preview-brand-container" style="display: flex; align-items: center; gap: 10px; max-height: 48px;">
                            <img id="fds-preview-logo-img" 
                                 src="<?php echo esc_url($brand_data['logo_url']); ?>" 
                                 alt="Logo Preview" 
                                 style="height: <?php echo (int) $brand_data['logo_height']; ?>px !important; max-height: <?php echo (int) $brand_data['logo_height']; ?>px !important; width: auto !important; max-width: 220px !important; object-fit: contain !important; <?php echo empty($brand_data['logo_url']) ? 'display:none;' : 'display:block;'; ?>">
                            
                            <img id="fds-preview-drone-icon-img" 
                                 src="<?php echo esc_url($brand_data['drone_icon_url']); ?>" 
                                 alt="Drone Icon Preview" 
                                 style="height: 24px !important; width: 24px !important; max-height: 24px !important; max-width: 24px !important; object-fit: contain !important; flex-shrink: 0; <?php echo (!empty($brand_data['drone_icon_url']) && empty($brand_data['logo_url'])) ? 'display:block;' : 'display:none;'; ?>">

                            <svg id="fds-preview-fallback-icon" class="w-5 h-5 text-[#1d1d1f]" style="width: 20px; height: 20px; color: #1d1d1f; <?php echo (!empty($brand_data['logo_url']) || !empty($brand_data['drone_icon_url'])) ? 'display:none;' : ''; ?>" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2L3 6l7 4 7-4-7-4zM3 14l7 4 7-4M3 10l7 4 7-4"/>
                            </svg>
                            
                            <span id="fds-preview-brand-text" style="font-size: 15px; font-weight: 600; color: #1d1d1f; letter-spacing: -0.01em;">
                                <?php echo esc_html($brand_data['brand_text']); ?>
                            </span>
                        </div>

                        <!-- Dummy Navigation Elements -->
                        <div style="display: flex; align-items: center; gap: 20px; color: #64748b; font-size: 13px; font-weight: 500;">
                            <span style="color: #0066cc; font-weight: 600;">Beranda</span>
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
                        <div id="fds_favicon_wrapper" style="width: 42px; height: 42px; min-width: 42px; max-width: 42px; max-height: 42px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 3px; box-sizing: border-box; flex-shrink: 0;">
                            <?php if (!empty($brand_data['favicon_url'])): ?>
                                <img id="fds_favicon_preview" src="<?php echo esc_url($brand_data['favicon_url']); ?>" style="width: 100% !important; height: 100% !important; max-width: 36px !important; max-height: 36px !important; object-fit: contain !important; display: block !important;">
                            <?php else: ?>
                                <div id="fds_favicon_preview" style="font-size: 10px; color: #94a3b8; font-weight: 600;">Icon</div>
                            <?php endif; ?>
                        </div>

                        <input type="text" 
                               id="fds_site_favicon_url" 
                               name="fds_site_favicon_url" 
                               value="<?php echo esc_url($brand_data['favicon_url']); ?>" 
                               style="flex: 1; font-size: 13px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                               placeholder="https://domain.com/.../favicon.png">

                        <button type="button" id="fds_upload_favicon_btn" class="button" style="padding: 6px 14px; font-size: 13px; font-weight: 600;">
                            Pilih / Unggah Icon Tab
                        </button>
                        
                        <button type="button" id="fds_remove_favicon_btn" class="button" style="padding: 6px 14px; font-size: 13px; color: #dc2626; border-color: #fecaca;">
                            Hapus
                        </button>
                    </div>
                </div>

                <!-- 3b. UPLOAD DRONE ICON (1 UNTUK SEMUA) -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                        <span class="dashicons dashicons-airplane" style="color: #0066cc; margin-right: 4px; vertical-align: text-bottom;"></span> Icon Drone Website (1 untuk Semua: Section Drone, Tentang Kami &amp; Navbar)
                    </label>
                    <p style="margin: 0 0 12px; color: #64748b; font-size: 13px;">
                        Unggah 1 icon drone untuk menggantikan icon drone bawaan (segitiga/pesawat biru) secara seragam di seluruh website, termasuk di <strong>Section Drone Beranda</strong>, <strong>Halaman Tentang Kami</strong>, dan <strong>Navbar</strong>. Rekomendasi: format <strong>SVG</strong> atau <strong>PNG transparan</strong> (ukuran 32×32 atau 64×64 piksel).
                    </p>

                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div id="fds_drone_icon_wrapper" style="width: 42px; height: 42px; min-width: 42px; max-width: 42px; max-height: 42px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 4px; box-sizing: border-box; flex-shrink: 0;">
                            <?php if (!empty($brand_data['drone_icon_url'])): ?>
                                <img id="fds_drone_icon_preview" src="<?php echo esc_url($brand_data['drone_icon_url']); ?>" style="width: 100% !important; height: 100% !important; max-width: 34px !important; max-height: 34px !important; object-fit: contain !important; display: block !important;">
                            <?php else: ?>
                                <div id="fds_drone_icon_preview">
                                    <svg style="width: 22px; height: 22px; color: #0066cc; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <input type="text" 
                               id="fds_navbar_drone_icon_url" 
                               name="fds_navbar_drone_icon_url" 
                               value="<?php echo esc_url($brand_data['drone_icon_url']); ?>" 
                               style="flex: 1; font-size: 13px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                               placeholder="https://domain.com/.../drone-icon.svg">

                        <button type="button" id="fds_upload_drone_icon_btn" class="button" style="padding: 6px 14px; font-size: 13px; font-weight: 600;">
                            Pilih / Unggah Icon Drone
                        </button>
                        
                        <button type="button" id="fds_remove_drone_icon_btn" class="button" style="padding: 6px 14px; font-size: 13px; color: #dc2626; border-color: #fecaca;">
                            Hapus / Reset
                        </button>
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 0;">

                <!-- 4. PENGATURAN NAMA TAB BROWSER (TITLE TAG UNTUK SEMUA HALAMAN) -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 22px 26px;">
                    <h2 style="margin: 0 0 4px; font-size: 16px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-admin-site-alt3" style="color: #0066cc;"></span> Pengaturan Nama Tab Browser (Title Tag)
                    </h2>
                    <p style="margin: 0 0 20px; color: #64748b; font-size: 13px;">Kustomisasi teks judul yang tampil pada tab browser saat pengunjung membuka halaman-halaman utama website Anda.</p>

                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        
                        <!-- 1. Nama Tab Halaman Beranda -->
                        <div>
                            <label for="fds_home_tab_title" style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                                <span class="dashicons dashicons-admin-home" style="color: #64748b; vertical-align: text-bottom; margin-right: 2px;"></span> Nama Tab Halaman Beranda / Home:
                            </label>
                            <input type="text" 
                                   id="fds_home_tab_title" 
                                   name="fds_home_tab_title" 
                                   value="<?php echo esc_attr($home_tab_title); ?>" 
                                   style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                                   placeholder="Contoh: Full Drone Solutions – Solusi Drone Industri, Agrikultur &amp; Pemetaan Indonesia">
                            <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">
                                Jika kosong, otomatis menggunakan <code>[Nama Brand] – [Tagline]</code>.
                            </p>
                        </div>

                        <!-- 2. Nama Tab Halaman Tentang Kami -->
                        <div>
                            <label for="fds_about_tab_title" style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                                <span class="dashicons dashicons-building" style="color: #64748b; vertical-align: text-bottom; margin-right: 2px;"></span> Nama Tab Halaman Tentang Kami:
                            </label>
                            <input type="text" 
                                   id="fds_about_tab_title" 
                                   name="fds_about_tab_title" 
                                   value="<?php echo esc_attr($about_tab_title); ?>" 
                                   style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                                   placeholder="Contoh: Tentang Kami – PT Karya Solusi Angkasa (Full Drone Solutions)">
                            <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">
                                Jika kosong, otomatis menggunakan <code>Tentang Kami – [Nama Brand]</code>.
                            </p>
                        </div>

                        <!-- 3. Nama Tab Halaman Perbandingan -->
                        <div>
                            <label for="fds_compare_tab_title" style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                                <span class="dashicons dashicons-leftright" style="color: #64748b; vertical-align: text-bottom; margin-right: 2px;"></span> Nama Tab Halaman Perbandingan Drone (/bandingkan):
                            </label>
                            <input type="text" 
                                   id="fds_compare_tab_title" 
                                   name="fds_compare_tab_title" 
                                   value="<?php echo esc_attr($compare_tab_title); ?>" 
                                   style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                                   placeholder="Contoh: Bandingkan Model UAV &amp; Spesifikasi Teknis – Full Drone Solutions">
                            <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">
                                Jika kosong, otomatis menggunakan <code>Bandingkan Model UAV &amp; Spesifikasi Teknis – [Nama Brand]</code>.
                            </p>
                        </div>

                        <!-- 4. Nama Tab Halaman Blog -->
                        <div>
                            <label for="fds_blog_tab_title" style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                                <span class="dashicons dashicons-welcome-write-blog" style="color: #64748b; vertical-align: text-bottom; margin-right: 2px;"></span> Nama Tab Halaman Blog / Berita (/blog):
                            </label>
                            <input type="text" 
                                   id="fds_blog_tab_title" 
                                   name="fds_blog_tab_title" 
                                   value="<?php echo esc_attr($blog_tab_title); ?>" 
                                   style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                                   placeholder="Contoh: Blog &amp; Berita Edukasi Drone – Full Drone Solutions">
                            <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">
                                Jika kosong, otomatis menggunakan <code>Blog &amp; Berita Terkini – [Nama Brand]</code>.
                            </p>
                        </div>

                        <!-- 5. Akhiran Tab Detail Produk Drone -->
                        <div>
                            <label for="fds_drone_tab_suffix" style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                                <span class="dashicons dashicons-airplane" style="color: #64748b; vertical-align: text-bottom; margin-right: 2px;"></span> Akhiran / Format Judul Tab Detail Produk Drone (Opsional):
                            </label>
                            <input type="text" 
                                   id="fds_drone_tab_suffix" 
                                   name="fds_drone_tab_suffix" 
                                   value="<?php echo esc_attr($drone_tab_suffix); ?>" 
                                   style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                                   placeholder="Contoh: – Drone Industri &amp; Pertanian Indonesia | Full Drone Solutions">
                            <p style="margin: 4px 0 0; color: #64748b; font-size: 12px;">
                                Jika kosong, otomatis menggunakan <code>[Nama Drone] ([Badge]) – [Nama Brand]</code>.
                            </p>
                        </div>

                        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 4px 0;">

                        <!-- Tagline Website -->
                        <div>
                            <label for="fds_site_tagline" style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                                Tagline / Slogan Perusahaan:
                            </label>
                            <input type="text" 
                                   id="fds_site_tagline" 
                                   name="fds_site_tagline" 
                                   value="<?php echo esc_attr($site_tagline); ?>" 
                                   style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
                                   placeholder="Contoh: Solusi Drone Industri, Agrikultur &amp; Pemetaan Indonesia">
                        </div>

                        <!-- Simbol Pemisah Tab -->
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                                Simbol Pemisah Judul Tab:
                            </label>
                            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                                <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                    <input type="radio" name="fds_tab_title_separator" value="–" <?php checked($title_separator, '–'); ?>>
                                    <span>– (En-dash)</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                    <input type="radio" name="fds_tab_title_separator" value="|" <?php checked($title_separator, '|'); ?>>
                                    <span>| (Pipe)</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                    <input type="radio" name="fds_tab_title_separator" value="•" <?php checked($title_separator, '•'); ?>>
                                    <span>• (Bullet)</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                    <input type="radio" name="fds_tab_title_separator" value="-" <?php checked($title_separator, '-'); ?>>
                                    <span>- (Hyphen)</span>
                                </label>
                            </div>
                        </div>

                        <div style="background: #eff6ff; border-left: 3px solid #3b82f6; padding: 10px 14px; border-radius: 4px; font-size: 12px; color: #1e40af;">
                            💡 <strong>Kustomisasi Spesifik Tiap Halaman:</strong> Anda juga tetap dapat mengedit nama tab khusus untuk masing-masing Halaman/Post/Drone secara individual langsung di layar edit halaman (kotak <em>"Pengaturan Nama Tab Browser (SEO Title)"</em> di bawah editor).
                        </div>

                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 0;">

                <!-- 5. TEKS BRAND / NAMA PERUSAHAAN -->
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

                <!-- 6. MODE TAMPILAN -->
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

                <!-- 7. TINGGI LOGO (PX) -->
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
                        💾 Simpan Perubahan Logo, Navbar &amp; Nama Tab
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
            let droneIconUrl = $('#fds_navbar_drone_icon_url').val().trim();
            let brandText = $('#fds_navbar_brand_text').val().trim();
            let displayMode = $('input[name="fds_navbar_display_mode"]:checked').val();
            let logoHeight = parseInt($('#fds_navbar_logo_height').val()) || 28;

            let previewImg = $('#fds-preview-logo-img');
            let previewDroneIcon = $('#fds-preview-drone-icon-img');
            let previewFallback = $('#fds-preview-fallback-icon');
            let previewText = $('#fds-preview-brand-text');

            if (displayMode === 'text_only') {
                previewImg.hide();
                previewDroneIcon.hide();
                previewFallback.hide();
                previewText.show().text(brandText || 'Full Drone Solutions');
            } else if (displayMode === 'logo_only') {
                previewText.hide();
                if (logoUrl) {
                    previewImg.attr('src', logoUrl).css({
                        'height': logoHeight + 'px',
                        'max-height': logoHeight + 'px',
                        'width': 'auto',
                        'max-width': '220px',
                        'object-fit': 'contain',
                        'display': 'block'
                    }).show();
                    previewDroneIcon.hide();
                    previewFallback.hide();
                } else if (droneIconUrl) {
                    previewImg.hide();
                    previewDroneIcon.attr('src', droneIconUrl).show();
                    previewFallback.hide();
                } else {
                    previewImg.hide();
                    previewDroneIcon.hide();
                    previewFallback.show();
                }
            } else {
                // both
                if (logoUrl) {
                    previewImg.attr('src', logoUrl).css({
                        'height': logoHeight + 'px',
                        'max-height': logoHeight + 'px',
                        'width': 'auto',
                        'max-width': '220px',
                        'object-fit': 'contain',
                        'display': 'block'
                    }).show();
                    previewDroneIcon.hide();
                    previewFallback.hide();
                } else if (droneIconUrl) {
                    previewImg.hide();
                    previewDroneIcon.attr('src', droneIconUrl).show();
                    previewFallback.hide();
                } else {
                    previewImg.hide();
                    previewDroneIcon.hide();
                    previewFallback.show();
                }
                previewText.show().text(brandText || 'Full Drone Solutions');
            }
        }

        // Trigger updates on input change
        $('#fds_navbar_brand_text, #fds_navbar_logo_height, #fds_navbar_drone_icon_url').on('input', updateLivePreview);
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

        // WP Media Uploader for Favicon / Icon Tab
        $('#fds_upload_favicon_btn').on('click', function(e) {
            e.preventDefault();
            var customUploader = wp.media({
                title: 'Pilih atau Unggah Icon Tab Browser (Favicon)',
                button: { text: 'Gunakan Icon Tab Ini' },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();
                $('#fds_site_favicon_url').val(attachment.url);
                $('#fds_favicon_wrapper').html('<img id="fds_favicon_preview" src="' + attachment.url + '" style="width: 100% !important; height: 100% !important; max-width: 36px !important; max-height: 36px !important; object-fit: contain !important; display: block !important;">');
            }).open();
        });

        // WP Media Uploader for Drone Icon Navbar
        $('#fds_upload_drone_icon_btn').on('click', function(e) {
            e.preventDefault();
            var customUploader = wp.media({
                title: 'Pilih atau Unggah Icon Drone Navbar',
                button: { text: 'Gunakan Icon Drone Ini' },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();
                $('#fds_navbar_drone_icon_url').val(attachment.url);
                $('#fds_drone_icon_wrapper').html('<img id="fds_drone_icon_preview" src="' + attachment.url + '" style="width: 100% !important; height: 100% !important; max-width: 34px !important; max-height: 34px !important; object-fit: contain !important; display: block !important;">');
                updateLivePreview();
            }).open();
        });

        // Remove Logo Button
        $('#fds_remove_logo_btn').on('click', function(e) {
            e.preventDefault();
            $('#fds_navbar_logo_url').val('');
            updateLivePreview();
        });

        // Remove Favicon Button
        $('#fds_remove_favicon_btn').on('click', function(e) {
            e.preventDefault();
            $('#fds_site_favicon_url').val('');
            $('#fds_favicon_wrapper').html('<div id="fds_favicon_preview" style="font-size: 10px; color: #94a3b8; font-weight: 600;">Icon</div>');
        });

        // Remove Drone Icon Button
        $('#fds_remove_drone_icon_btn').on('click', function(e) {
            e.preventDefault();
            $('#fds_navbar_drone_icon_url').val('');
            $('#fds_drone_icon_wrapper').html('<div id="fds_drone_icon_preview"><svg style="width: 22px; height: 22px; color: #0066cc; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg></div>');
            updateLivePreview();
        });
    });
    </script>
    <?php
}

// 6. METABOX DI SETIAP HALAMAN / POST / DRONE UNTUK CUSTOM TAB TITLE
add_action('add_meta_boxes', function () {
    $screens = ['page', 'post', 'drone'];
    foreach ($screens as $screen) {
        add_meta_box(
            'fds_tab_title_meta_box',
            'Pengaturan Nama Tab Browser (SEO Title)',
            __NAMESPACE__ . '\\render_tab_title_meta_box',
            $screen,
            'normal',
            'high'
        );
    }
});

function render_tab_title_meta_box($post) {
    wp_nonce_field('fds_tab_title_meta_nonce_action', 'fds_tab_title_meta_nonce');
    $current_custom_title = get_post_meta($post->ID, '_fds_custom_tab_title', true);
    $brand_text = get_option('fds_navbar_brand_text', null) ?: (get_bloginfo('name') ?: 'Full Drone Solutions');
    $default_preview = get_the_title($post->ID) . ' – ' . $brand_text;
    ?>
    <div style="padding: 10px 0;">
        <label for="fds_custom_tab_title" style="display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
            Kustomisasi Nama Tab Browser (Opsional):
        </label>
        <input type="text" 
               id="fds_custom_tab_title" 
               name="fds_custom_tab_title" 
               value="<?php echo esc_attr($current_custom_title); ?>" 
               style="width: 100%; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1;" 
               placeholder="Contoh: <?php echo esc_attr($default_preview); ?>">
        <p style="margin: 6px 0 0; color: #64748b; font-size: 12px;">
            Kosongkan jika ingin menggunakan format otomatis default: <code>[Judul Halaman] – <?php echo esc_html($brand_text); ?></code>.
        </p>
    </div>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['fds_tab_title_meta_nonce']) || !check_admin_referer('fds_tab_title_meta_nonce_action', 'fds_tab_title_meta_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['fds_custom_tab_title'])) {
        $custom_title = sanitize_text_field($_POST['fds_custom_tab_title']);
        if (!empty($custom_title)) {
            update_post_meta($post_id, '_fds_custom_tab_title', $custom_title);
        } else {
            delete_post_meta($post_id, '_fds_custom_tab_title');
        }
    }
});
