<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enterprise Inquiry, Quotation & Lead Telemetry Management System
 * Theme: FDS Theme (PT Karya Solusi Angkasa)
 *
 * Features:
 * 1. CPT 'fds_inquiry' ("Pesan Masuk") dengan kolom layanan/lokasi/UTM, badge unread, dan aksi cepat.
 * 2. Meta Box detail proyek (Lokasi, Luas, Timeline, File AOI terproteksi, Referrer/UTM, Persetujuan UU PDP).
 * 3. Secure AJAX Submission (Backward-compatible Beranda/Kontak + Form Penawaran Layanan + Upload AOI KML/KMZ/GeoJSON/PDF).
 * 4. Submenu Admin "Tracking & Konfigurasi Lead" (GA4, GTM, Google Ads, Meta Pixel, Penerima Email, Auto-Reply, Webhook, Turnstile).
 * 5. Ekspor CSV penuh berpelindung Anti-CSV Formula Injection + unduhan AOI privat khusus admin.
 */

// =========================================================================
// 0. TRACKING & LEAD CONFIGURATION HELPER
// =========================================================================

function fds_get_tracking_config(): array {
    return [
        'ga4_id'                => trim((string) get_option('fds_ga4_id', '')),
        'gtm_id'                => trim((string) get_option('fds_gtm_id', '')),
        'gads_id'               => trim((string) get_option('fds_gads_id', '')),
        'gads_label'            => trim((string) get_option('fds_gads_label', '')),
        'meta_pixel_id'         => trim((string) get_option('fds_meta_pixel_id', '')),
        'lead_recipients'       => trim((string) get_option('fds_lead_recipients', '')),
        'lead_autoreply_enable' => (string) get_option('fds_lead_autoreply_enable', '1'),
        'lead_webhook_url'      => trim((string) get_option('fds_lead_webhook_url', '')),
        'turnstile_site_key'    => trim((string) get_option('fds_turnstile_site_key', '')),
        'turnstile_secret_key'  => trim((string) get_option('fds_turnstile_secret_key', '')),
    ];
}

// =========================================================================
// 1. REGISTER CPT 'fds_inquiry' ("Pesan Masuk") & SUBMENU KONFIGURASI
// =========================================================================

add_action('init', function () {
    $labels = [
        'name'               => 'Pesan Masuk',
        'singular_name'      => 'Pesan Masuk',
        'menu_name'          => 'Pesan Masuk',
        'name_admin_bar'     => 'Pesan Masuk',
        'add_new'            => 'Tambah Manual',
        'add_new_item'       => 'Tambah Pesan Masuk Baru',
        'new_item'           => 'Pesan Baru',
        'edit_item'          => 'Detail Pesan Masuk',
        'view_item'          => 'Lihat Pesan',
        'all_items'          => 'Semua Pesan Masuk',
        'search_items'       => 'Cari Pesan...',
        'not_found'          => 'Belum ada pesan masuk.',
        'not_found_in_trash' => 'Tidak ada pesan di tempat sampah.',
    ];

    register_post_type('fds_inquiry', [
        'labels'          => $labels,
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => true,
        'menu_position'   => 26,
        'menu_icon'       => 'dashicons-email-alt',
        'supports'        => ['title'],
        'capability_type' => 'post',
        'map_meta_cap'    => true,
        'hierarchical'    => false,
    ]);
});

add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=fds_inquiry',
        'Tracking & Konfigurasi Lead',
        'Tracking & Konfigurasi Lead',
        'manage_options',
        'fds-lead-tracking-settings',
        __NAMESPACE__ . '\\fds_render_lead_tracking_settings_page'
    );
});

// Tambahkan badge counter unread ke menu Pesan Masuk secara native WordPress
add_action('admin_menu', function () {
    global $menu;
    if (!is_admin() || !is_array($menu)) {
        return;
    }

    $unread_count = get_transient('fds_unread_inquiry_count');
    if ($unread_count === false) {
        $unread_query = new \WP_Query([
            'post_type'      => 'fds_inquiry',
            'post_status'    => 'publish',
            'meta_key'       => '_fds_inquiry_status',
            'meta_value'     => 'unread',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => false,
        ]);
        $unread_count = (int) $unread_query->found_posts;
        set_transient('fds_unread_inquiry_count', $unread_count, 5 * MINUTE_IN_SECONDS);
    }

    if ($unread_count > 0) {
        foreach ($menu as $key => $item) {
            if (isset($item[2]) && $item[2] === 'edit.php?post_type=fds_inquiry') {
                $menu[$key][0] .= sprintf(
                    ' <span class="update-plugins count-%d" style="background-color:#0f808a;"><span class="plugin-count">%d</span></span>',
                    $unread_count,
                    $unread_count
                );
                break;
            }
        }
    }
}, 999);

// Nonaktifkan Gutenberg untuk fds_inquiry
add_filter('use_block_editor_for_post_type', function ($use_block, $post_type) {
    if ($post_type === 'fds_inquiry') {
        return false;
    }
    return $use_block;
}, 10, 2);

// Tombol Ekspor CSV di layar daftar CPT 'fds_inquiry'
add_action('manage_posts_extra_tablenav', function ($which) {
    if ($which !== 'top' || !current_user_can('manage_options')) {
        return;
    }
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->post_type !== 'fds_inquiry') {
        return;
    }
    $export_url = admin_url('admin-post.php?action=fds_export_inquiries&_wpnonce=' . wp_create_nonce('fds_export_inquiries_nonce'));
    echo '<div class="alignleft actions" style="margin-left:8px;">';
    echo '<a href="' . esc_url($export_url) . '" class="button button-secondary" style="display:inline-flex;align-items:center;gap:5px;border-color:#0f808a;color:#0a555c;font-weight:600;">';
    echo '<span class="dashicons dashicons-download" style="font-size:16px;width:16px;height:16px;margin-top:2px;"></span>';
    echo 'Ekspor Semua Lead (CSV / Excel)';
    echo '</a>';
    echo '</div>';
});

// =========================================================================
// 1b. RENDER HALAMAN PENGATURAN TRACKING & KONFIGURASI LEAD
// =========================================================================

function fds_render_lead_tracking_settings_page(): void {
    if (!current_user_can('manage_options')) {
        return;
    }

    $saved = false;
    if (isset($_POST['fds_save_lead_tracking_settings']) && check_admin_referer('fds_lead_tracking_settings_nonce')) {
        $ga4_id     = preg_replace('/[^A-Za-z0-9\-_]/', '', sanitize_text_field(wp_unslash($_POST['fds_ga4_id'] ?? '')));
        $gtm_id     = preg_replace('/[^A-Za-z0-9\-_]/', '', sanitize_text_field(wp_unslash($_POST['fds_gtm_id'] ?? '')));
        $gads_id    = preg_replace('/[^A-Za-z0-9\-_]/', '', sanitize_text_field(wp_unslash($_POST['fds_gads_id'] ?? '')));
        $gads_label = preg_replace('/[^A-Za-z0-9\-_]/', '', sanitize_text_field(wp_unslash($_POST['fds_gads_label'] ?? '')));
        $meta_id    = preg_replace('/[^0-9]/', '', sanitize_text_field(wp_unslash($_POST['fds_meta_pixel_id'] ?? '')));

        $raw_recipients = sanitize_text_field(wp_unslash($_POST['fds_lead_recipients'] ?? ''));
        $valid_emails   = [];
        foreach (explode(',', $raw_recipients) as $chunk) {
            $em = sanitize_email(trim($chunk));
            if ($em !== '' && is_email($em)) {
                $valid_emails[] = $em;
            }
        }

        $autoreply = isset($_POST['fds_lead_autoreply_enable']) ? '1' : '0';

        $raw_webhook = esc_url_raw(trim((string) wp_unslash($_POST['fds_lead_webhook_url'] ?? '')), ['https']);
        if ($raw_webhook !== '' && stripos($raw_webhook, 'https://') !== 0) {
            $raw_webhook = '';
        }

        $turnstile_site   = sanitize_text_field(trim((string) wp_unslash($_POST['fds_turnstile_site_key'] ?? '')));
        $turnstile_secret = sanitize_text_field(trim((string) wp_unslash($_POST['fds_turnstile_secret_key'] ?? '')));

        update_option('fds_ga4_id', $ga4_id);
        update_option('fds_gtm_id', $gtm_id);
        update_option('fds_gads_id', $gads_id);
        update_option('fds_gads_label', $gads_label);
        update_option('fds_meta_pixel_id', $meta_id);
        update_option('fds_lead_recipients', implode(', ', array_unique($valid_emails)));
        update_option('fds_lead_autoreply_enable', $autoreply);
        update_option('fds_lead_webhook_url', $raw_webhook);
        update_option('fds_turnstile_site_key', $turnstile_site);
        update_option('fds_turnstile_secret_key', $turnstile_secret);
        $saved = true;
    }

    $cfg = fds_get_tracking_config();
    ?>
    <div class="wrap" style="max-width: 980px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        <h1 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Tracking Analytics, Proteksi Form &amp; Konfigurasi Lead</h1>
        <p style="color: #475569; font-size: 13.5px; margin-top: 0;">Kelola ID pengukuran konversi (GA4, Google Tag Manager, Google Ads, Meta Pixel), penerima notifikasi email tim sales, integrasi webhook CRM, serta perlindungan anti-spam Cloudflare Turnstile.</p>

        <?php if ($saved): ?>
        <div class="notice notice-success is-dismissible" style="margin: 16px 0;">
            <p><strong>Pengaturan berhasil disimpan.</strong> Perubahan langsung aktif pada formulir publik dan script pelacakan tema.</p>
        </div>
        <?php endif; ?>

        <form method="post" style="margin-top: 18px;">
            <?php wp_nonce_field('fds_lead_tracking_settings_nonce'); ?>

            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px 24px; margin-bottom: 20px;">
                <h2 style="margin-top: 0; font-size: 16px; color: #0f808a;">1. Kode Pelacakan Konversi &amp; Telemetry Event</h2>
                <p style="color: #64748b; font-size: 13px; margin-top: 4px;">Skrip hanya dimuat di halaman publik apabila ID diisi. Event <code>generate_lead</code>, <code>click_whatsapp</code>, <code>click_phone</code>, <code>click_email</code>, dan <code>download_brochure</code> dipancarkan otomatis ke <code>dataLayer</code> &amp; <code>gtag</code>.</p>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="fds_gtm_id">Google Tag Manager ID</label></th>
                        <td>
                            <input type="text" id="fds_gtm_id" name="fds_gtm_id" value="<?php echo esc_attr($cfg['gtm_id']); ?>" class="regular-text" placeholder="GTM-XXXXXXX">
                            <p class="description">Direkomendasikan jika GA4 dan Ads dikelola lewat Google Tag Manager.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fds_ga4_id">Google Analytics 4 Measurement ID</label></th>
                        <td>
                            <input type="text" id="fds_ga4_id" name="fds_ga4_id" value="<?php echo esc_attr($cfg['ga4_id']); ?>" class="regular-text" placeholder="G-XXXXXXXXXX">
                            <p class="description">Dipakai langsung apabila tidak dipasang melalui GTM.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fds_gads_id">Google Ads Conversion ID &amp; Label</label></th>
                        <td>
                            <input type="text" id="fds_gads_id" name="fds_gads_id" value="<?php echo esc_attr($cfg['gads_id']); ?>" class="regular-text" placeholder="AW-XXXXXXXXX" style="width: 190px;">
                            <input type="text" id="fds_gads_label" name="fds_gads_label" value="<?php echo esc_attr($cfg['gads_label']); ?>" class="regular-text" placeholder="Conversion Label" style="width: 240px;">
                            <p class="description">Otomatis memicu event konversi Google Ads saat submit form berhasil (<code>generate_lead</code>).</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fds_meta_pixel_id">Meta / Facebook Pixel ID</label></th>
                        <td>
                            <input type="text" id="fds_meta_pixel_id" name="fds_meta_pixel_id" value="<?php echo esc_attr($cfg['meta_pixel_id']); ?>" class="regular-text" placeholder="123456789012345">
                        </td>
                    </tr>
                </table>
            </div>

            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px 24px; margin-bottom: 20px;">
                <h2 style="margin-top: 0; font-size: 16px; color: #0f808a;">2. Routing Notifikasi Email, Auto-Reply &amp; Webhook CRM</h2>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="fds_lead_recipients">Email Penerima Notifikasi Tim Sales</label></th>
                        <td>
                            <input type="text" id="fds_lead_recipients" name="fds_lead_recipients" value="<?php echo esc_attr($cfg['lead_recipients']); ?>" class="large-text" placeholder="<?php echo esc_attr(get_option('admin_email')); ?>">
                            <p class="description">Pisahkan beberapa alamat email dengan koma (misal: <code>sales@fulldronesolutions.com, marketing@fulldronesolutions.com</code>). Jika kosong, notifikasi dikirim ke Admin Email WordPress (<code><?php echo esc_html(get_option('admin_email')); ?></code>).</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Auto-Reply Email Konfirmasi ke Prospek</th>
                        <td>
                            <label>
                                <input type="checkbox" name="fds_lead_autoreply_enable" value="1" <?php checked($cfg['lead_autoreply_enable'], '1'); ?>>
                                Kirim email konfirmasi otomatis berisi nomor referensi &amp; ringkasan proyek ke pengirim form
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fds_lead_webhook_url">Webhook URL Integrasi CRM / Spreadsheet (HTTPS)</label></th>
                        <td>
                            <input type="url" id="fds_lead_webhook_url" name="fds_lead_webhook_url" value="<?php echo esc_attr($cfg['lead_webhook_url']); ?>" class="large-text" placeholder="https://hooks.zapier.com/hooks/catch/...">
                            <p class="description">Opsional. Menerima payload JSON non-blocking setiap kali lead B2B baru masuk (hanya URL HTTPS valid).</p>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px 24px; margin-bottom: 20px;">
                <h2 style="margin-top: 0; font-size: 16px; color: #0f808a;">3. Perlindungan Anti-Spam Cloudflare Turnstile (Opsional)</h2>
                <p style="color: #64748b; font-size: 13px; margin-top: 4px;">Jika Site Key dan Secret Key kosong, formulir tetap terlindungi dengan WordPress Nonce, Honeypot field, serta rate-limit IP. Saat kedua kunci diisi, widget Cloudflare Turnstile tampil dan diverifikasi server-side.</p>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="fds_turnstile_site_key">Turnstile Site Key</label></th>
                        <td><input type="text" id="fds_turnstile_site_key" name="fds_turnstile_site_key" value="<?php echo esc_attr($cfg['turnstile_site_key']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fds_turnstile_secret_key">Turnstile Secret Key</label></th>
                        <td><input type="password" id="fds_turnstile_secret_key" name="fds_turnstile_secret_key" value="<?php echo esc_attr($cfg['turnstile_secret_key']); ?>" class="regular-text" autocomplete="new-password"></td>
                    </tr>
                </table>
            </div>

            <p class="submit">
                <button type="submit" name="fds_save_lead_tracking_settings" class="button button-primary button-large" style="background: #0f808a; border-color: #0a555c;">Simpan Pengaturan Tracking &amp; Lead</button>
            </p>
        </form>
    </div>
    <?php
}

// =========================================================================
// 2. CUSTOM ADMIN COLUMNS UNTUK PESAN MASUK
// =========================================================================

add_filter('manage_fds_inquiry_posts_columns', function ($columns) {
    return [
        'cb'              => '<input type="checkbox" />',
        'status_badge'    => 'Status',
        'title'           => 'Pengirim & Ref',
        'inquiry_service' => 'Layanan & Lokasi Proyek',
        'inquiry_company' => 'Perusahaan / Instansi',
        'inquiry_contact' => 'Kontak (WA / Email)',
        'inquiry_source'  => 'Sumber / UTM',
        'inquiry_actions' => 'Aksi Cepat',
        'date'            => 'Waktu Masuk',
    ];
});

add_action('manage_fds_inquiry_posts_custom_column', function ($column, $post_id) {
    $ref      = get_post_meta($post_id, '_fds_inquiry_ref', true);
    $company  = get_post_meta($post_id, '_fds_inquiry_company', true) ?: '-';
    $email    = get_post_meta($post_id, '_fds_inquiry_email', true) ?: '-';
    $phone    = get_post_meta($post_id, '_fds_inquiry_phone', true) ?: '-';
    $service  = get_post_meta($post_id, '_fds_inquiry_service_label', true) ?: get_post_meta($post_id, '_fds_inquiry_drone', true) ?: 'Konsultasi Umum';
    $location = get_post_meta($post_id, '_fds_inquiry_project_location', true);
    $area     = get_post_meta($post_id, '_fds_inquiry_project_area', true);
    $aoi_name = get_post_meta($post_id, '_fds_inquiry_aoi_name', true);
    $utm_src  = get_post_meta($post_id, '_fds_inquiry_utm_source', true);
    $utm_camp = get_post_meta($post_id, '_fds_inquiry_utm_campaign', true);
    $src_page = get_post_meta($post_id, '_fds_inquiry_source_page', true);
    $status   = get_post_meta($post_id, '_fds_inquiry_status', true) ?: 'unread';

    switch ($column) {
        case 'status_badge':
            if ($status === 'replied') {
                echo '<span style="display:inline-block;padding:3px 8px;background:#dcfce7;color:#15803d;border-radius:12px;font-size:11px;font-weight:700;">Sudah Dihubungi</span>';
            } elseif ($status === 'in_progress') {
                echo '<span style="display:inline-block;padding:3px 8px;background:#fef9c3;color:#854d0e;border-radius:12px;font-size:11px;font-weight:700;">Dalam Proses</span>';
            } else {
                echo '<span style="display:inline-block;padding:3px 8px;background:#dbeafe;color:#1d4ed8;border-radius:12px;font-size:11px;font-weight:700;">Baru</span>';
            }
            if ($ref) {
                echo '<div style="margin-top:4px;font-size:11px;color:#0f808a;font-family:monospace;font-weight:600;">' . esc_html($ref) . '</div>';
            }
            break;

        case 'inquiry_service':
            echo '<div style="font-size:12.5px;line-height:1.45;">';
            echo '<strong style="color:#0f172a;">' . esc_html($service) . '</strong>';
            if ($location || $area) {
                $loc_txt = implode(' &middot; ', array_filter([$location, $area]));
                echo '<div style="color:#475569;font-size:12px;margin-top:2px;">' . esc_html(wp_specialchars_decode($loc_txt, ENT_QUOTES)) . '</div>';
            }
            if ($aoi_name) {
                echo '<div style="color:#0a555c;font-size:11.5px;margin-top:2px;font-weight:600;">Lampiran AOI: ' . esc_html($aoi_name) . '</div>';
            }
            echo '</div>';
            break;

        case 'inquiry_company':
            echo '<strong>' . esc_html($company) . '</strong>';
            break;

        case 'inquiry_contact':
            echo '<div style="font-size:12px;line-height:1.4;">';
            if ($phone !== '-') {
                echo '<div><a href="https://wa.me/' . preg_replace('/[^0-9]/', '', $phone) . '" target="_blank" style="color:#059669;font-weight:600;">WA: ' . esc_html($phone) . '</a></div>';
            }
            if ($email !== '-') {
                echo '<div><a href="mailto:' . esc_attr($email) . '" style="color:#2563eb;">' . esc_html($email) . '</a></div>';
            }
            echo '</div>';
            break;

        case 'inquiry_source':
            echo '<div style="font-size:11.5px;color:#475569;line-height:1.45;">';
            if ($utm_src) {
                echo '<div><strong>UTM:</strong> ' . esc_html($utm_src . ($utm_camp ? ' / ' . $utm_camp : '')) . '</div>';
            }
            if ($src_page) {
                $path = wp_parse_url($src_page, PHP_URL_PATH) ?: $src_page;
                echo '<div title="' . esc_attr($src_page) . '"><strong>Halaman:</strong> ' . esc_html($path) . '</div>';
            } elseif (!$utm_src) {
                echo '<span>Langsung / Organik</span>';
            }
            echo '</div>';
            break;

        case 'inquiry_actions':
            echo '<div style="display:flex;gap:6px;align-items:center;">';
            if ($phone !== '-') {
                $clean_phone = preg_replace('/[^0-9]/', '', $phone);
                $wa_note     = rawurlencode('Halo, kami dari tim Full Drone Solutions menindaklanjuti inquiry' . ($ref ? ' (' . $ref . ')' : '') . ' terkait ' . $service . '.');
                echo '<a href="https://wa.me/' . $clean_phone . '?text=' . $wa_note . '" target="_blank" class="button button-small" style="background:#25D366;color:#fff;border-color:#25D366;font-weight:600;" title="Balas via WhatsApp">WA</a>';
            }
            if ($email !== '-') {
                echo '<a href="mailto:' . esc_attr($email) . '" class="button button-small" style="font-weight:600;" title="Balas via Email">Email</a>';
            }
            echo '</div>';
            break;
    }
}, 10, 2);

// =========================================================================
// 3. META BOX DETAIL PESAN MASUK & KEPERLUAN PROYEK B2B
// =========================================================================

add_action('add_meta_boxes', function () {
    add_meta_box(
        'fds_inquiry_details_box',
        'Detail Informasi Pesan Masuk, Proyek & Atribusi Lead',
        function ($post) {
            wp_nonce_field('fds_save_inquiry_meta', 'fds_inquiry_meta_nonce');

            $ref         = get_post_meta($post->ID, '_fds_inquiry_ref', true) ?: '-';
            $full_name   = get_post_meta($post->ID, '_fds_inquiry_full_name', true);
            $first_name  = get_post_meta($post->ID, '_fds_inquiry_first_name', true);
            $last_name   = get_post_meta($post->ID, '_fds_inquiry_last_name', true);
            $display_name = trim((string) ($full_name ?: trim($first_name . ' ' . $last_name))) ?: '-';
            $company     = get_post_meta($post->ID, '_fds_inquiry_company', true);
            $email       = get_post_meta($post->ID, '_fds_inquiry_email', true);
            $phone       = get_post_meta($post->ID, '_fds_inquiry_phone', true);
            $message     = get_post_meta($post->ID, '_fds_inquiry_message', true);
            $status      = get_post_meta($post->ID, '_fds_inquiry_status', true) ?: 'unread';
            $ip          = get_post_meta($post->ID, '_fds_inquiry_ip', true) ?: '-';
            $date        = get_the_date('d F Y, H:i WIB', $post->ID);

            $service_label = get_post_meta($post->ID, '_fds_inquiry_service_label', true) ?: get_post_meta($post->ID, '_fds_inquiry_drone', true) ?: '-';
            $service_slug  = get_post_meta($post->ID, '_fds_inquiry_service_slug', true) ?: '-';
            $location      = get_post_meta($post->ID, '_fds_inquiry_project_location', true) ?: '-';
            $area          = get_post_meta($post->ID, '_fds_inquiry_project_area', true) ?: '-';
            $timeline      = get_post_meta($post->ID, '_fds_inquiry_project_timeline', true) ?: '-';
            $pdp_consent   = get_post_meta($post->ID, '_fds_inquiry_pdp_consent', true);
            $pdp_time      = get_post_meta($post->ID, '_fds_inquiry_pdp_consent_at', true);

            $aoi_name    = get_post_meta($post->ID, '_fds_inquiry_aoi_name', true);
            $aoi_relpath = get_post_meta($post->ID, '_fds_inquiry_aoi_relpath', true);
            $aoi_size    = (int) get_post_meta($post->ID, '_fds_inquiry_aoi_size', true);

            $src_page  = get_post_meta($post->ID, '_fds_inquiry_source_page', true) ?: '-';
            $src_title = get_post_meta($post->ID, '_fds_inquiry_source_title', true) ?: '-';
            $referrer  = get_post_meta($post->ID, '_fds_inquiry_referrer', true) ?: '-';
            $utm_src   = get_post_meta($post->ID, '_fds_inquiry_utm_source', true) ?: '-';
            $utm_med   = get_post_meta($post->ID, '_fds_inquiry_utm_medium', true) ?: '-';
            $utm_camp  = get_post_meta($post->ID, '_fds_inquiry_utm_campaign', true) ?: '-';
            $utm_term  = get_post_meta($post->ID, '_fds_inquiry_utm_term', true) ?: '-';
            $utm_cont  = get_post_meta($post->ID, '_fds_inquiry_utm_content', true) ?: '-';
            $gclid     = get_post_meta($post->ID, '_fds_inquiry_gclid', true) ?: '-';
            $fbclid    = get_post_meta($post->ID, '_fds_inquiry_fbclid', true) ?: '-';
            ?>
            <style>
                .fds-inq-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                .fds-inq-table th { width: 220px; text-align: left; padding: 11px 12px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #475569; vertical-align: top; }
                .fds-inq-table td { padding: 11px 12px; border-bottom: 1px solid #e2e8f0; font-size: 13.5px; color: #1e293b; }
                .fds-btn-wa { display: inline-flex; align-items: center; gap: 6px; background: #25D366; color: #fff; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; font-size: 12.5px; }
                .fds-btn-wa:hover { background: #1eb851; color: #fff; }
                .fds-btn-email { display: inline-flex; align-items: center; gap: 6px; background: #0f808a; color: #fff; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; font-size: 12.5px; margin-left: 8px; }
                .fds-btn-email:hover { background: #0a555c; color: #fff; }
            </style>

            <table class="fds-inq-table">
                <tr>
                    <th>Nomor Referensi</th>
                    <td><strong style="font-family:monospace;font-size:14px;color:#0f808a;"><?php echo esc_html($ref); ?></strong></td>
                </tr>
                <tr>
                    <th>Status Tindak Lanjut</th>
                    <td>
                        <select name="fds_inquiry_status" style="font-size: 13px; font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                            <option value="unread" <?php selected($status, 'unread'); ?>>Baru / Belum Dibaca</option>
                            <option value="in_progress" <?php selected($status, 'in_progress'); ?>>Dalam Proses Komunikasi</option>
                            <option value="replied" <?php selected($status, 'replied'); ?>>Selesai / Sudah Dihubungi</option>
                        </select>
                        <span style="font-size: 12px; color: #64748b; margin-left: 10px;">Perbarui status dan klik tombol "Update" di sisi kanan.</span>
                    </td>
                </tr>
                <tr>
                    <th>Waktu Diterima</th>
                    <td><strong><?php echo esc_html($date); ?></strong> <span style="font-size: 12px; color: #64748b;">(IP: <?php echo esc_html($ip); ?>)</span></td>
                </tr>
                <tr>
                    <th>Nama Lengkap</th>
                    <td><strong><?php echo esc_html($display_name); ?></strong></td>
                </tr>
                <tr>
                    <th>Perusahaan / Instansi</th>
                    <td><strong><?php echo esc_html($company ?: '-'); ?></strong></td>
                </tr>
                <tr>
                    <th>Nomor WhatsApp / Telepon</th>
                    <td>
                        <span style="font-size: 15px; font-weight: 600;"><?php echo esc_html($phone ?: '-'); ?></span>
                        <?php if (!empty($phone) && $phone !== '-'): ?>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $phone); ?>" target="_blank" class="fds-btn-wa" style="margin-left: 14px;">
                                Hubungi via WhatsApp
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Email Bisnis</th>
                    <td>
                        <span style="font-size: 14px; font-weight: 600;"><?php echo esc_html($email ?: '-'); ?></span>
                        <?php if (!empty($email) && $email !== '-'): ?>
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="fds-btn-email">
                                Kirim Email Balasan
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Layanan / Fokus Solusi</th>
                    <td><strong><?php echo esc_html($service_label); ?></strong> <span style="color:#64748b;font-size:12px;">(<?php echo esc_html($service_slug); ?>)</span></td>
                </tr>
                <tr>
                    <th>Lokasi Proyek (Kab / Prov)</th>
                    <td><?php echo esc_html($location); ?></td>
                </tr>
                <tr>
                    <th>Perkiraan Luas / Koridor</th>
                    <td><?php echo esc_html($area); ?></td>
                </tr>
                <tr>
                    <th>Estimasi Jadwal Proyek</th>
                    <td><?php echo esc_html($timeline); ?></td>
                </tr>
                <tr>
                    <th>Lampiran Batas Area (AOI)</th>
                    <td>
                        <?php if ($aoi_name && $aoi_relpath): ?>
                            <?php
                            $dl_url = admin_url('admin-post.php?action=fds_download_aoi&post_id=' . (int) $post->ID . '&_wpnonce=' . wp_create_nonce('fds_download_aoi_' . (int) $post->ID));
                            $kb     = $aoi_size > 0 ? round($aoi_size / 1024, 1) . ' KB' : '';
                            ?>
                            <strong><?php echo esc_html($aoi_name); ?></strong>
                            <?php if ($kb): ?><span style="color:#64748b;">(<?php echo esc_html($kb); ?>)</span><?php endif; ?>
                            <a href="<?php echo esc_url($dl_url); ?>" class="button button-small" style="margin-left: 10px; border-color: #0f808a; color: #0a555c; font-weight: 600;">Unduh File AOI Privat</a>
                        <?php else: ?>
                            <span style="color:#64748b;">Tidak ada file AOI yang dilampirkan.</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Detail Pesan / Kebutuhan</th>
                    <td>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 18px; font-size: 14px; line-height: 1.6; color: #0f172a; white-space: pre-wrap;">
                            <?php echo esc_html($message ?: '-'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Persetujuan Privasi (UU PDP)</th>
                    <td>
                        <?php if ($pdp_consent === '1'): ?>
                            <span style="color:#15803d;font-weight:600;">Disetujui pengirim</span>
                            <?php if ($pdp_time): ?>
                                <span style="color:#64748b;font-size:12px;">pada <?php echo esc_html($pdp_time); ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color:#64748b;">Formulir standar (tanpa parameter UU PDP khusus)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Atribusi Halaman &amp; UTM</th>
                    <td style="font-size:12.5px;line-height:1.6;color:#475569;">
                        <div><strong>Halaman Sumber:</strong> <?php echo esc_html($src_title . ' (' . $src_page . ')'); ?></div>
                        <div><strong>Referrer:</strong> <?php echo esc_html($referrer); ?></div>
                        <div><strong>UTM:</strong> source=<code><?php echo esc_html($utm_src); ?></code> | medium=<code><?php echo esc_html($utm_med); ?></code> | campaign=<code><?php echo esc_html($utm_camp); ?></code> | term=<code><?php echo esc_html($utm_term); ?></code> | content=<code><?php echo esc_html($utm_cont); ?></code></div>
                        <div><strong>Click ID:</strong> GCLID=<code><?php echo esc_html($gclid); ?></code> | FBCLID=<code><?php echo esc_html($fbclid); ?></code></div>
                    </td>
                </tr>
            </table>
            <?php
        },
        'fds_inquiry',
        'normal',
        'high'
    );
});

// Simpan perubahan status dari admin edit screen
add_action('save_post_fds_inquiry', function ($post_id) {
    if (!isset($_POST['fds_inquiry_meta_nonce']) || !wp_verify_nonce($_POST['fds_inquiry_meta_nonce'], 'fds_save_inquiry_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['fds_inquiry_status'])) {
        $allowed = ['unread', 'in_progress', 'replied'];
        $new_status = sanitize_text_field($_POST['fds_inquiry_status']);
        if (in_array($new_status, $allowed, true)) {
            update_post_meta($post_id, '_fds_inquiry_status', $new_status);
            delete_transient('fds_unread_inquiry_count');
        }
    }
});

// Invalidate cache saat inquiry dihapus/dipindahkan ke trash
add_action('wp_trash_post', function ($post_id) {
    if (get_post_type($post_id) === 'fds_inquiry') {
        delete_transient('fds_unread_inquiry_count');
    }
});
add_action('untrash_post', function ($post_id) {
    if (get_post_type($post_id) === 'fds_inquiry') {
        delete_transient('fds_unread_inquiry_count');
    }
});
add_action('delete_post', function ($post_id) {
    if (get_post_type($post_id) === 'fds_inquiry') {
        delete_transient('fds_unread_inquiry_count');
    }
});

// =========================================================================
// 3b. SECURE ADMIN-ONLY AOI FILE DOWNLOAD & FULL CSV EXPORT HANDLERS
// =========================================================================

add_action('admin_post_fds_download_aoi', function () {
    $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;
    if (!$post_id || !current_user_can('manage_options') || !check_admin_referer('fds_download_aoi_' . $post_id)) {
        wp_die('Akses ditolak.', 'Forbidden', ['response' => 403]);
    }

    $relpath   = (string) get_post_meta($post_id, '_fds_inquiry_aoi_relpath', true);
    $orig_name = (string) get_post_meta($post_id, '_fds_inquiry_aoi_name', true) ?: basename($relpath);

    if ($relpath === '' || strpos($relpath, '..') !== false) {
        wp_die('File AOI tidak ditemukan.', 'Not Found', ['response' => 404]);
    }

    $upload_dir = wp_upload_dir();
    $base_dir   = realpath(trailingslashit($upload_dir['basedir']) . 'fds-aoi');
    $full_path  = realpath(trailingslashit($upload_dir['basedir']) . 'fds-aoi/' . ltrim($relpath, '/\\'));

    if (!$base_dir || !$full_path || strpos($full_path, $base_dir) !== 0 || !is_file($full_path)) {
        wp_die('Berkas AOI tidak ditemukan pada penyimpanan aman.', 'Not Found', ['response' => 404]);
    }

    $download_name = function_exists(__NAMESPACE__ . '\\fds_safe_filename')
        ? fds_safe_filename($orig_name)
        : sanitize_file_name($orig_name);

    nocache_headers();
    header('X-Content-Type-Options: nosniff');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $download_name . '"');
    header('Content-Length: ' . filesize($full_path));
    readfile($full_path);
    exit;
});

add_action('admin_post_fds_export_inquiries', function () {
    if (!current_user_can('manage_options') || !check_admin_referer('fds_export_inquiries_nonce')) {
        wp_die('Akses ditolak.', 'Forbidden', ['response' => 403]);
    }

    $posts = get_posts([
        'post_type'      => 'fds_inquiry',
        'post_status'    => ['publish', 'private'],
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    nocache_headers();
    header('X-Content-Type-Options: nosniff');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=fds_leads_inquiry_' . gmdate('Y-m-d') . '.csv');

    $out = fopen('php://output', 'w');
    fputs($out, "\xEF\xBB\xBF");

    fputcsv($out, [
        'No',
        'Ref ID',
        'Waktu Masuk',
        'Status',
        'Tipe Lead',
        'Nama Lengkap',
        'Perusahaan / Instansi',
        'Email Bisnis',
        'Telepon / WhatsApp',
        'Layanan / Produk',
        'Lokasi Proyek',
        'Luas / Koridor',
        'Timeline',
        'File AOI',
        'Halaman Asal',
        'UTM Source',
        'UTM Medium',
        'UTM Campaign',
        'UTM Term',
        'UTM Content',
        'GCLID',
        'FBCLID',
        'Referrer',
        'PDP Consent',
        'IP Address',
        'Pesan / Kebutuhan',
    ]);

    $sanitize_csv = function_exists(__NAMESPACE__ . '\\fds_sanitize_csv_cell')
        ? __NAMESPACE__ . '\\fds_sanitize_csv_cell'
        : function ($v) {
            return (string) $v;
        };

    $no = 1;
    foreach ($posts as $p) {
        $full_name  = get_post_meta($p->ID, '_fds_inquiry_full_name', true);
        $first_name = get_post_meta($p->ID, '_fds_inquiry_first_name', true);
        $last_name  = get_post_meta($p->ID, '_fds_inquiry_last_name', true);
        $disp_name  = trim((string) ($full_name ?: trim($first_name . ' ' . $last_name))) ?: '-';
        $type_raw   = get_post_meta($p->ID, '_fds_inquiry_type', true) ?: 'quotation_inquiry';

        $row = [
            $no++,
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_ref', true) ?: '-'),
            $sanitize_csv(get_the_date('Y-m-d H:i:s', $p->ID)),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_status', true) ?: 'unread'),
            $sanitize_csv($type_raw),
            $sanitize_csv($disp_name),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_company', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_email', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_phone', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_service_label', true) ?: get_post_meta($p->ID, '_fds_inquiry_drone', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_project_location', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_project_area', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_project_timeline', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_aoi_name', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_source_page', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_utm_source', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_utm_medium', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_utm_campaign', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_utm_term', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_utm_content', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_gclid', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_fbclid', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_referrer', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_pdp_consent', true) === '1' ? 'Ya' : 'Tidak'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_ip', true) ?: '-'),
            $sanitize_csv(get_post_meta($p->ID, '_fds_inquiry_message', true) ?: '-'),
        ];

        fputcsv($out, $row);
    }

    fclose($out);
    exit;
});

// =========================================================================
// 4. SECURE AOI FILE UPLOAD HELPER (.kml, .kmz, .geojson, .pdf <= 5MB)
// =========================================================================

function fds_store_uploaded_aoi_file(array $file): array {
    $err_code = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($err_code === UPLOAD_ERR_NO_FILE) {
        return ['ok' => true, 'has_file' => false];
    }

    if ($err_code !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'message' => 'Gagal mengunggah file batas area (AOI). Pastikan ukuran maksimal 5 MB.'];
    }

    $max_bytes = 5 * 1024 * 1024;
    $size      = (int) ($file['size'] ?? 0);
    if ($size <= 0 || $size > $max_bytes) {
        return ['ok' => false, 'message' => 'Ukuran file batas area (AOI) maksimal 5 MB.'];
    }

    $tmp_name  = (string) ($file['tmp_name'] ?? '');
    $orig_name = (string) ($file['name'] ?? '');
    if ($tmp_name === '' || !is_file($tmp_name)) {
        return ['ok' => false, 'message' => 'Berkas sementara upload AOI tidak valid.'];
    }

    $ext          = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
    $allowed_exts = ['kml', 'kmz', 'geojson', 'pdf'];
    if (!in_array($ext, $allowed_exts, true)) {
        return ['ok' => false, 'message' => 'Format file AOI harus berupa .kml, .kmz, .geojson, atau .pdf.'];
    }

    // Validasi signature isi file terhadap ekstensi
    $raw_content = @file_get_contents($tmp_name, false, null, 0, $max_bytes + 1);
    if ($raw_content === false || $raw_content === '') {
        return ['ok' => false, 'message' => 'File batas area (AOI) kosong atau tidak dapat dibaca.'];
    }

    if ($ext === 'pdf') {
        if (strpos($raw_content, '%PDF-') !== 0) {
            return ['ok' => false, 'message' => 'Isi file PDF tidak valid.'];
        }
    } elseif ($ext === 'kmz') {
        if (substr($raw_content, 0, 2) !== 'PK') {
            return ['ok' => false, 'message' => 'Isi file KMZ terkompresi tidak valid.'];
        }
    } elseif ($ext === 'kml') {
        if (stripos($raw_content, '<kml') === false || stripos($raw_content, '<?php') !== false || stripos($raw_content, '<script') !== false) {
            return ['ok' => false, 'message' => 'Format XML KML tidak valid atau mengandung elemen terlarang.'];
        }
    } elseif ($ext === 'geojson') {
        $clean_json = preg_replace('/^\xEF\xBB\xBF/', '', trim($raw_content));
        $decoded    = json_decode((string) $clean_json, true);
        if (!is_array($decoded) || (empty($decoded['type']) && empty($decoded['features']) && empty($decoded['geometry']))) {
            return ['ok' => false, 'message' => 'Struktur JSON pada file GeoJSON tidak valid.'];
        }
    }

    $upload_dir  = wp_upload_dir();
    $root_aoi    = trailingslashit($upload_dir['basedir']) . 'fds-aoi';
    $subdir      = gmdate('Y/m');
    $target_dir  = trailingslashit($root_aoi) . $subdir;

    if (!wp_mkdir_p($target_dir)) {
        return ['ok' => false, 'message' => 'Direktori penyimpanan AOI tidak tersedia di server.'];
    }

    // Amankan direktori privat dari akses web langsung maupun eksekusi script
    $htaccess_file = trailingslashit($root_aoi) . '.htaccess';
    if (!file_exists($htaccess_file)) {
        $rules = "<IfModule mod_authz_core.c>\n  Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n  Order allow,deny\n  Deny from all\n</IfModule>\n<FilesMatch \"\\.(php|phtml|phar|pl|py|cgi|sh)$\">\n  Require all denied\n</FilesMatch>\n";
        @file_put_contents($htaccess_file, $rules);
    }
    $index_file = trailingslashit($root_aoi) . 'index.php';
    if (!file_exists($index_file)) {
        @file_put_contents($index_file, "<?php\n// Silence is golden.\n");
    }

    $safe_orig = function_exists(__NAMESPACE__ . '\\fds_safe_filename')
        ? fds_safe_filename($orig_name)
        : sanitize_file_name($orig_name);
    $stem      = pathinfo($safe_orig, PATHINFO_FILENAME) ?: 'aoi';
    $stem      = substr(preg_replace('/[^a-zA-Z0-9_-]/', '-', $stem), 0, 50);
    try {
        $rand = bin2hex(random_bytes(6));
    } catch (\Throwable $e) {
        $rand = substr(md5((string) wp_rand() . microtime()), 0, 12);
    }

    $stored_filename = wp_unique_filename($target_dir, $rand . '-' . $stem . '.' . $ext);
    $dest_path       = trailingslashit($target_dir) . $stored_filename;

    $moved = false;
    if (is_uploaded_file($tmp_name)) {
        $moved = @move_uploaded_file($tmp_name, $dest_path);
    }
    if (!$moved) {
        $moved = @file_put_contents($dest_path, $raw_content) !== false;
    }

    if (!$moved) {
        return ['ok' => false, 'message' => 'Gagal menyimpan berkas AOI di server.'];
    }

    return [
        'ok'       => true,
        'has_file' => true,
        'name'     => $safe_orig,
        'relpath'  => $subdir . '/' . $stored_filename,
        'size'     => $size,
    ];
}

// =========================================================================
// 5. AJAX SUBMISSION ENDPOINT (Beranda, Kontak & Form Penawaran Layanan)
// =========================================================================

add_action('wp_ajax_fds_submit_inquiry', __NAMESPACE__ . '\\fds_handle_inquiry_submission');
add_action('wp_ajax_nopriv_fds_submit_inquiry', __NAMESPACE__ . '\\fds_handle_inquiry_submission');

function fds_handle_inquiry_submission() {
    // 0. Anti-bot honeypot check
    if (!empty($_POST['website_url_hp'])) {
        wp_send_json_success(['message' => 'Pesan Anda telah berhasil terkirim.']);
    }

    // 1. Verifikasi Nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fds_inquiry_nonce')) {
        wp_send_json_error(['message' => 'Sesi kedaluwarsa. Silakan muat ulang halaman dan coba lagi.'], 403);
    }

    // Rate Limiting per IP (Maks 10 pengiriman per 10 menit untuk mencegah spam flooding)
    $ip = function_exists(__NAMESPACE__ . '\\fds_get_client_ip')
        ? fds_get_client_ip()
        : sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');

    $rate_key = 'fds_inq_' . md5($ip);
    $attempts = (int) get_transient($rate_key);
    if ($attempts >= 10) {
        wp_send_json_error(['message' => 'Terlalu banyak permintaan pengiriman dalam waktu singkat. Silakan tunggu beberapa saat lagi.'], 429);
    }
    set_transient($rate_key, $attempts + 1, 10 * MINUTE_IN_SECONDS);

    // 1b. Verifikasi Kondisional Cloudflare Turnstile (hanya jika Site Key & Secret Key diisi admin)
    $tracking_cfg = fds_get_tracking_config();
    if ($tracking_cfg['turnstile_site_key'] !== '' && $tracking_cfg['turnstile_secret_key'] !== '') {
        $ts_token = sanitize_text_field(wp_unslash($_POST['cf-turnstile-response'] ?? ''));
        if ($ts_token === '') {
            wp_send_json_error(['message' => 'Silakan selesaikan verifikasi keamanan Cloudflare Turnstile sebelum mengirim formulir.'], 400);
        }
        $verify_resp = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'timeout' => 5,
            'body'    => [
                'secret'   => $tracking_cfg['turnstile_secret_key'],
                'response' => $ts_token,
                'remoteip' => $ip,
            ],
        ]);
        if (is_wp_error($verify_resp)) {
            wp_send_json_error(['message' => 'Verifikasi keamanan sementara tidak dapat dihubungi. Silakan coba beberapa saat lagi.'], 503);
        }
        $verify_body = json_decode((string) wp_remote_retrieve_body($verify_resp), true);
        if (empty($verify_body['success'])) {
            wp_send_json_error(['message' => 'Verifikasi anti-bot gagal. Silakan muat ulang verifikasi dan kirim kembali.'], 403);
        }
    }

    // 2. Sanitasi Nama (Mendukung full_name dari Form Penawaran Layanan ATAU first_name + last_name dari Beranda/Kontak)
    $raw_full_input = isset($_POST['full_name']) ? trim((string) wp_unslash($_POST['full_name'])) : '';
    $raw_first      = isset($_POST['first_name']) ? (string) wp_unslash($_POST['first_name']) : '';
    $raw_last       = isset($_POST['last_name']) ? (string) wp_unslash($_POST['last_name']) : '';

    $using_full_name_mode = ($raw_full_input !== '' || (!isset($_POST['first_name']) && !isset($_POST['last_name'])));

    $clean_name_str = function (string $s, int $max): string {
        $cleaned = preg_replace('/[^\p{L}\p{N}\s.\'-]/u', '', wp_strip_all_tags($s));
        $cleaned = preg_replace('/\s+/', ' ', (string) $cleaned);
        return mb_substr(trim((string) $cleaned), 0, $max);
    };

    if ($using_full_name_mode) {
        $full_name = $clean_name_str($raw_full_input, 120);
        if ($full_name === '') {
            wp_send_json_error(['message' => 'Silakan isi nama lengkap Anda dengan benar.'], 400);
        }
        $name_parts = preg_split('/\s+/', $full_name, 2);
        $first_name = $name_parts[0] ?? $full_name;
        $last_name  = $name_parts[1] ?? '';
    } else {
        $first_name = $clean_name_str($raw_first, 60);
        $last_name  = $clean_name_str($raw_last, 60);
        if (empty($first_name)) {
            wp_send_json_error(['message' => 'Silakan isi nama depan Anda dengan benar.'], 400);
        }
        if (empty($last_name)) {
            wp_send_json_error(['message' => 'Silakan isi nama belakang Anda dengan benar.'], 400);
        }
        $full_name = trim($first_name . ' ' . $last_name);
    }

    // 3. Sanitasi Field Kontak & Proyek B2B
    $raw_comp  = isset($_POST['company']) ? wp_unslash($_POST['company']) : '';
    $raw_email = isset($_POST['email']) ? wp_unslash($_POST['email']) : '';
    $raw_phone = isset($_POST['phone']) ? wp_unslash($_POST['phone']) : '';
    $raw_msg   = isset($_POST['message']) ? wp_unslash($_POST['message']) : '';

    $company = mb_substr(trim((string) preg_replace('/\s+/', ' ', wp_strip_all_tags($raw_comp))), 0, 120);
    $email   = sanitize_email($raw_email);
    $phone   = mb_substr(trim((string) preg_replace('/[^\d+\-\s().]/', '', wp_strip_all_tags($raw_phone))), 0, 30);

    if (mb_strlen($raw_msg) > 3000) {
        wp_send_json_error(['message' => 'Pesan terlalu panjang. Maksimal 3.000 karakter.'], 400);
    }
    $message = mb_substr(trim(wp_strip_all_tags($raw_msg)), 0, 3000);

    if (empty($company)) {
        wp_send_json_error(['message' => 'Silakan isi nama perusahaan atau instansi Anda.'], 400);
    }
    if (empty($email) || !is_email($email)) {
        wp_send_json_error(['message' => 'Format alamat email tidak valid.'], 400);
    }
    if (empty($phone) || strlen(preg_replace('/[^0-9]/', '', $phone)) < 6) {
        wp_send_json_error(['message' => 'Silakan isi nomor telepon / WhatsApp yang valid (minimal 6 digit angka).'], 400);
    }
    if (empty($message)) {
        wp_send_json_error(['message' => 'Silakan tuliskan deskripsi kebutuhan atau rincian area proyek Anda.'], 400);
    }

    // UU PDP Consent validation bila diminta oleh formulir penawaran
    $pdp_required = !empty($_POST['pdp_consent_required']);
    $pdp_checked  = !empty($_POST['pdp_consent']);
    if ($pdp_required && !$pdp_checked) {
        wp_send_json_error(['message' => 'Silakan centang persetujuan pemrosesan data sesuai kebijakan privasi sebelum mengirim permintaan.'], 400);
    }

    // Field Proyek Tambahan (opsional / konteks B2B)
    $service_slug     = sanitize_key(wp_unslash($_POST['service_slug'] ?? ''));
    $service_label    = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['service_label'] ?? '')), 0, 140));
    if ($service_label === '' && $service_slug !== '' && function_exists(__NAMESPACE__ . '\\fds_get_layanan_service_options')) {
        $srv_opts = fds_get_layanan_service_options();
        if (isset($srv_opts[$service_slug])) {
            $service_label = $srv_opts[$service_slug];
        }
    }
    $project_location = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['project_location'] ?? '')), 0, 140));
    $project_area     = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['project_area'] ?? '')), 0, 100));
    $project_timeline = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['project_timeline'] ?? '')), 0, 100));

    // Atribusi & UTM
    $source_page  = esc_url_raw(mb_substr(trim((string) wp_unslash($_POST['source_page'] ?? '')), 0, 500));
    $source_title = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['source_title'] ?? '')), 0, 160));
    $referrer     = esc_url_raw(mb_substr(trim((string) wp_unslash($_POST['referrer'] ?? '')), 0, 500));
    $utm_source   = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['utm_source'] ?? '')), 0, 100));
    $utm_medium   = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['utm_medium'] ?? '')), 0, 100));
    $utm_campaign = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['utm_campaign'] ?? '')), 0, 120));
    $utm_term     = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['utm_term'] ?? '')), 0, 120));
    $utm_content  = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['utm_content'] ?? '')), 0, 120));
    $gclid        = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['gclid'] ?? '')), 0, 140));
    $fbclid       = sanitize_text_field(mb_substr(trim((string) wp_unslash($_POST['fbclid'] ?? '')), 0, 140));

    // 4. Upload AOI File opsional (.kml, .kmz, .geojson, .pdf <= 5MB)
    $aoi_result = ['ok' => true, 'has_file' => false];
    if (!empty($_FILES['aoi_file']) && is_array($_FILES['aoi_file'])) {
        $aoi_result = fds_store_uploaded_aoi_file($_FILES['aoi_file']);
        if (empty($aoi_result['ok'])) {
            wp_send_json_error(['message' => $aoi_result['message'] ?? 'Berkas AOI tidak valid.'], 400);
        }
    }

    // 5. Susun Judul Post & Simpan ke CPT 'fds_inquiry'
    $post_title = $service_label !== ''
        ? "[{$service_label}] {$full_name} - {$company}"
        : "{$full_name} - {$company}";

    $meta_input = [
        '_fds_inquiry_type'             => 'quotation_inquiry',
        '_fds_inquiry_full_name'        => $full_name,
        '_fds_inquiry_first_name'       => $first_name,
        '_fds_inquiry_last_name'        => $last_name,
        '_fds_inquiry_company'          => $company,
        '_fds_inquiry_email'            => $email,
        '_fds_inquiry_phone'            => $phone,
        '_fds_inquiry_message'          => $message,
        '_fds_inquiry_status'           => 'unread',
        '_fds_inquiry_ip'               => $ip,
        '_fds_inquiry_service_slug'     => $service_slug,
        '_fds_inquiry_service_label'    => $service_label,
        '_fds_inquiry_project_location' => $project_location,
        '_fds_inquiry_project_area'     => $project_area,
        '_fds_inquiry_project_timeline' => $project_timeline,
        '_fds_inquiry_pdp_consent'      => $pdp_checked ? '1' : '0',
        '_fds_inquiry_pdp_consent_at'   => $pdp_checked ? current_time('mysql') : '',
        '_fds_inquiry_source_page'      => $source_page,
        '_fds_inquiry_source_title'     => $source_title,
        '_fds_inquiry_referrer'         => $referrer,
        '_fds_inquiry_utm_source'       => $utm_source,
        '_fds_inquiry_utm_medium'       => $utm_medium,
        '_fds_inquiry_utm_campaign'     => $utm_campaign,
        '_fds_inquiry_utm_term'         => $utm_term,
        '_fds_inquiry_utm_content'      => $utm_content,
        '_fds_inquiry_gclid'            => $gclid,
        '_fds_inquiry_fbclid'           => $fbclid,
    ];

    if (!empty($aoi_result['has_file'])) {
        $meta_input['_fds_inquiry_aoi_name']    = (string) $aoi_result['name'];
        $meta_input['_fds_inquiry_aoi_relpath'] = (string) $aoi_result['relpath'];
        $meta_input['_fds_inquiry_aoi_size']    = (int) $aoi_result['size'];
    }

    $post_id = wp_insert_post([
        'post_type'    => 'fds_inquiry',
        'post_title'   => $post_title,
        'post_content' => $message,
        'post_status'  => 'publish',
        'meta_input'   => $meta_input,
    ]);

    if (is_wp_error($post_id)) {
        wp_send_json_error(['message' => 'Gagal menyimpan permintaan Anda. Silakan coba lagi.'], 500);
    }

    $lead_ref = 'FDS-' . wp_date('Ymd') . '-' . str_pad((string) $post_id, 4, '0', STR_PAD_LEFT);
    update_post_meta($post_id, '_fds_inquiry_ref', $lead_ref);

    delete_transient('fds_unread_inquiry_count');

    // 6. Notifikasi Email ke Tim Sales / Admin FDS
    $recipients = [];
    if ($tracking_cfg['lead_recipients'] !== '') {
        foreach (explode(',', $tracking_cfg['lead_recipients']) as $r_em) {
            $clean_em = sanitize_email(trim($r_em));
            if ($clean_em !== '' && is_email($clean_em)) {
                $recipients[] = $clean_em;
            }
        }
    }
    if (empty($recipients)) {
        $fallback_admin = sanitize_email((string) get_option('admin_email'));
        if ($fallback_admin !== '') {
            $recipients[] = $fallback_admin;
        }
    }

    if (!empty($recipients)) {
        $subject = "[{$lead_ref}] " . ($service_label !== '' ? $service_label : 'Inquiry Baru') . " - {$company}";
        $body    = "Permintaan penawaran / konsultasi baru telah diterima dari website Full Drone Solutions:\n\n"
                 . "Nomor Referensi : {$lead_ref}\n"
                 . "Layanan / Fokus : " . ($service_label !== '' ? $service_label : 'Konsultasi Umum') . "\n"
                 . "Nama Lengkap    : {$full_name}\n"
                 . "Perusahaan      : {$company}\n"
                 . "WhatsApp / Telp : {$phone}\n"
                 . "Email Bisnis    : {$email}\n"
                 . "Lokasi Proyek   : " . ($project_location ?: '-') . "\n"
                 . "Perkiraan Luas  : " . ($project_area ?: '-') . "\n"
                 . "Estimasi Jadwal : " . ($project_timeline ?: '-') . "\n"
                 . "Lampiran AOI    : " . (!empty($aoi_result['has_file']) ? $aoi_result['name'] : 'Tidak ada') . "\n\n"
                 . "Rincian Kebutuhan:\n{$message}\n\n"
                 . "--- Data Sumber & UTM ---\n"
                 . "Halaman Asal : " . ($source_page ?: '-') . "\n"
                 . "UTM Campaign : " . ($utm_source ? "{$utm_source} / {$utm_medium} / {$utm_campaign}" : 'Organik / Langsung') . "\n\n"
                 . "Kelola & unduh AOI di WP Admin: " . admin_url('post.php?post=' . $post_id . '&action=edit');

        @wp_mail($recipients, $subject, $body);
    }

    // 7. Auto-Reply Konfirmasi ke Email Prospek (tanpa klaim SLA palsu)
    if ($tracking_cfg['lead_autoreply_enable'] === '1' && is_email($email)) {
        $gc           = function_exists(__NAMESPACE__ . '\\fds_get_global_contact') ? fds_get_global_contact() : [];
        $fds_phone    = $gc['phone'] ?? '+62 8112 748 882';
        $fds_email    = $gc['email'] ?? 'marketing@fulldronesolutions.com';
        $user_subject = "Konfirmasi Permintaan Konsultasi FDS [{$lead_ref}]";
        $user_body    = "Halo {$full_name},\n\n"
                      . "Terima kasih telah menghubungi PT Karya Solusi Angkasa (Full Drone Solutions / FDS).\n"
                      . "Permintaan informasi dan penawaran Anda telah kami catat dengan rincian berikut:\n\n"
                      . "Nomor Referensi : {$lead_ref}\n"
                      . "Perusahaan      : {$company}\n"
                      . "Fokus Layanan   : " . ($service_label !== '' ? $service_label : 'Solusi UAV & Pemetaan FDS') . "\n"
                      . ($project_location ? "Lokasi Proyek   : {$project_location}\n" : '')
                      . ($project_area ? "Perkiraan Luas  : {$project_area}\n" : '')
                      . "\nTim teknis dan engineering kami sedang meninjau informasi kebutuhan Anda untuk menyiapkan rekomendasi metode atau langkah diskusi lanjutan.\n\n"
                      . "Apabila Anda perlu menambahkan batas area tambahan (.kml/.shp) atau berdiskusi lebih cepat, silakan balas email ini dengan menyertakan nomor referensi {$lead_ref}, atau hubungi kami melalui:\n"
                      . "- Telepon / WhatsApp: {$fds_phone}\n"
                      . "- Email Resmi       : {$fds_email}\n"
                      . "- Kantor & Workshop : Jl. Lukito Harjo No.10, Sleman, D.I. Yogyakarta 55285\n\n"
                      . "Hormat kami,\n"
                      . "Tim Enterprise & Geospasial — PT Karya Solusi Angkasa (Full Drone Solutions)";

        @wp_mail($email, $user_subject, $user_body);
    }

    // 8. Opsional Non-Blocking Webhook (CRM / Spreadsheet)
    if ($tracking_cfg['lead_webhook_url'] !== '' && stripos($tracking_cfg['lead_webhook_url'], 'https://') === 0) {
        $webhook_payload = [
            'event'            => 'fds_lead_created',
            'lead_ref'         => $lead_ref,
            'post_id'          => (int) $post_id,
            'created_at'       => current_time('c'),
            'full_name'        => $full_name,
            'company'          => $company,
            'email'            => $email,
            'phone'            => $phone,
            'service_slug'     => $service_slug,
            'service_label'    => $service_label,
            'project_location' => $project_location,
            'project_area'     => $project_area,
            'project_timeline' => $project_timeline,
            'has_aoi_file'     => !empty($aoi_result['has_file']),
            'aoi_file_name'    => $aoi_result['name'] ?? '',
            'source_page'      => $source_page,
            'utm_source'       => $utm_source,
            'utm_medium'       => $utm_medium,
            'utm_campaign'     => $utm_campaign,
        ];
        @wp_remote_post($tracking_cfg['lead_webhook_url'], [
            'blocking' => false,
            'timeout'  => 3,
            'headers'  => ['Content-Type' => 'application/json; charset=utf-8'],
            'body'     => wp_json_encode($webhook_payload),
        ]);
    }

    $redirect_url = !empty($_POST['redirect_on_success'])
        ? home_url('/terima-kasih/?ref=' . rawurlencode($lead_ref))
        : '';

    wp_send_json_success([
        'message'       => "Permintaan penawaran Anda telah tercatat dengan nomor referensi {$lead_ref}. Tim Full Drone Solutions akan segera meninjau kebutuhan proyek Anda.",
        'lead_ref'      => $lead_ref,
        'service_label' => $service_label !== '' ? $service_label : 'Konsultasi Umum',
        'redirect_url'  => $redirect_url,
    ]);
}
