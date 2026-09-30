<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS DRONE BROCHURE & GATED LEAD MANAGEMENT SYSTEM
 * =========================================================================
 * Theme: FDS Theme (PT Karya Solusi Angkasa)
 * 
 * Features:
 * 1. WP Admin Settings under "Produk Drone -> Pengaturan Brosur"
 * 2. Section customization (Badge, Title, Description, Button, Privacy note, Fallback PDF)
 * 3. Media Uploader support for PDF files
 * 4. Gated Email Lead Capture via secure AJAX
 * 5. Lead logging in database & CSV export
 */

// 1. SUBMENU PADA MENU PRODUK DRONE
add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=drone',
        'Pengaturan Brosur & Lead Unduhan',
        'Pengaturan Brosur',
        'manage_options',
        'fds-brochure-settings',
        __NAMESPACE__ . '\\render_brochure_settings_admin_page'
    );
});

// 2. ENQUEUE SCRIPTS UNTUK ADMIN BROCHURE SETTINGS
add_action('admin_enqueue_scripts', function ($hook) {
    if (strpos($hook, 'fds-brochure-settings') !== false) {
        wp_enqueue_media();
    }
});

// 3. RENDER ADMIN PAGE
function render_brochure_settings_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $saved = false;
    if (isset($_POST['fds_save_brochure_settings']) && check_admin_referer('fds_brochure_settings_nonce')) {
        update_option('fds_brochure_enable', isset($_POST['fds_brochure_enable']) ? '1' : '0');
        update_option('fds_brochure_badge', sanitize_text_field($_POST['fds_brochure_badge'] ?? ''));
        update_option('fds_brochure_title', sanitize_text_field($_POST['fds_brochure_title'] ?? ''));
        update_option('fds_brochure_desc', sanitize_textarea_field($_POST['fds_brochure_desc'] ?? ''));
        update_option('fds_brochure_desc_ready', sanitize_textarea_field($_POST['fds_brochure_desc_ready'] ?? ''));
        update_option('fds_brochure_placeholder', sanitize_text_field($_POST['fds_brochure_placeholder'] ?? ''));
        update_option('fds_brochure_button_text', sanitize_text_field($_POST['fds_brochure_button_text'] ?? ''));
        update_option('fds_brochure_layout', sanitize_text_field($_POST['fds_brochure_layout'] ?? 'horizontal'));
        update_option('fds_brochure_privacy_note', sanitize_text_field($_POST['fds_brochure_privacy_note'] ?? ''));
        update_option('fds_brochure_default_pdf', esc_url_raw($_POST['fds_brochure_default_pdf'] ?? ''));
        update_option('fds_brochure_admin_email', sanitize_email($_POST['fds_brochure_admin_email'] ?? ''));
        $saved = true;
    }

    // Default values (Sleek, Minimalist, No Clutter)
    $enable       = get_option('fds_brochure_enable', '1');
    $layout       = get_option('fds_brochure_layout', 'horizontal');
    $title        = get_option('fds_brochure_title', 'Brosur Spesifikasi {drone_name}');
    $desc         = get_option('fds_brochure_desc', 'Masukkan email Anda untuk mengunduh dokumen spesifikasi teknis resmi (PDF).');
    $desc_ready   = get_option('fds_brochure_desc_ready', 'Dokumen spesifikasi teknis resmi (PDF) telah siap untuk diunduh.');
    $placeholder  = get_option('fds_brochure_placeholder', 'Masukkan email Anda...');
    $button_text  = get_option('fds_brochure_button_text', 'Download Brosur (PDF)');
    $default_pdf  = get_option('fds_brochure_default_pdf', '');
    $admin_email  = get_option('fds_brochure_admin_email', get_option('admin_email'));

    // Fetch latest leads
    $leads = get_posts([
        'post_type'      => 'fds_inquiry',
        'post_status'    => 'publish',
        'meta_key'       => '_fds_inquiry_type',
        'meta_value'     => 'brochure_download',
        'posts_per_page' => 20,
    ]);
    ?>
    <div class="wrap" style="max-width: 1100px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-top: 10px;">
            <div>
                <h1 style="font-size: 22px; font-weight: 700; color: #1e293b; margin: 0 0 4px 0;">📄 Pengaturan Brosur &amp; Lead Unduhan Drone</h1>
                <p style="color: #64748b; font-size: 13px; margin: 0;">Kelola teks section unduh brosur di halaman produk drone serta rekap data email prospek yang masuk.</p>
            </div>
            <?php if (!empty($leads)): ?>
            <a href="<?php echo esc_url(admin_url('admin-post.php?action=fds_export_brochure_leads&_wpnonce=' . wp_create_nonce('fds_export_leads_nonce'))); ?>" class="button button-secondary" style="display: flex; align-items: center; gap: 6px; font-weight: 600;">
                <span class="dashicons dashicons-download" style="margin-top: 2px;"></span> Ekspor Data Lead (CSV / Excel)
            </a>
            <?php endif; ?>
        </div>

        <?php if ($saved): ?>
        <div class="notice notice-success is-dismissible" style="border-radius: 6px; margin-bottom: 20px;">
            <p><strong>Pengaturan Berhasil Disimpan!</strong> Perubahan teks dan file brosur telah diterapkan ke seluruh halaman produk drone.</p>
        </div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field('fds_brochure_settings_nonce'); ?>

            <!-- CARD 1: PENGATURAN SECTION & DESAIN KONTEN -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9; margin-bottom: 20px;">
                    <h2 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <span style="background: #e0f2fe; color: #0284c7; width: 26px; height: 26px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px;">⚙️</span>
                        1. Tampilan &amp; Teks Section Unduh Brosur
                    </h2>
                    <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600; font-size: 13px; color: #1e293b; cursor: pointer;">
                        <input type="checkbox" name="fds_brochure_enable" value="1" <?php checked($enable, '1'); ?>>
                        Aktifkan Section Unduh Brosur di Halaman Drone
                    </label>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Judul Section</label>
                    <input type="text" name="fds_brochure_title" value="<?php echo esc_attr($title); ?>" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                    <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Gunakan kode <code>{drone_name}</code> agar otomatis digantikan dengan nama drone yang sedang dibuka (contoh: FERTO 10).</span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Deskripsi (Sebelum Input Email)</label>
                        <input type="text" name="fds_brochure_desc" value="<?php echo esc_attr($desc); ?>" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                        <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Tampil saat form input email masih aktif.</span>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Deskripsi (Setelah Siap Download)</label>
                        <input type="text" name="fds_brochure_desc_ready" value="<?php echo esc_attr($desc_ready); ?>" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                        <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Tampil otomatis saat tombol download biru terbuka.</span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Placeholder Input Email</label>
                        <input type="text" name="fds_brochure_placeholder" value="<?php echo esc_attr($placeholder); ?>" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">Teks Tombol Download Aktif</label>
                        <input type="text" name="fds_brochure_button_text" value="<?php echo esc_attr($button_text); ?>" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 8px;">Pilihan Varian Tata Letak (Layout)</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <label style="border: 2px solid <?php echo $layout === 'horizontal' ? '#0284c7' : '#e2e8f0'; ?>; background: <?php echo $layout === 'horizontal' ? '#f0f9ff' : '#ffffff'; ?>; border-radius: 8px; padding: 14px; cursor: pointer; display: flex; gap: 12px; align-items: flex-start; transition: all 0.2s;">
                            <input type="radio" name="fds_brochure_layout" value="horizontal" <?php checked($layout, 'horizontal'); ?> style="margin-top: 3px;">
                            <div>
                                <div style="font-weight: 700; font-size: 13px; color: #0f172a; margin-bottom: 2px;">Varian 1: Horizontal / Split (Kiri - Kanan)</div>
                                <div style="font-size: 11px; color: #64748b; line-height: 1.4;">Judul di kiri, form email &amp; tombol download di kanan. Ringkas &amp; modern.</div>
                            </div>
                        </label>
                        <label style="border: 2px solid <?php echo $layout === 'centered' ? '#0284c7' : '#e2e8f0'; ?>; background: <?php echo $layout === 'centered' ? '#f0f9ff' : '#ffffff'; ?>; border-radius: 8px; padding: 14px; cursor: pointer; display: flex; gap: 12px; align-items: flex-start; transition: all 0.2s;">
                            <input type="radio" name="fds_brochure_layout" value="centered" <?php checked($layout, 'centered'); ?> style="margin-top: 3px;">
                            <div>
                                <div style="font-weight: 700; font-size: 13px; color: #0f172a; margin-bottom: 2px;">Varian 2: Centered / Rata Tengah</div>
                                <div style="font-size: 11px; color: #64748b; line-height: 1.4;">Judul, subjudul, form input, dan tombol tersusun simetris di tengah halaman.</div>
                            </div>
                        </label>
                    </div>
                </div>



                <div style="border-top: 1px solid #f1f5f9; padding-top: 16px; margin-top: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px;">File PDF Brosur Cadangan / Global Fallback</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" id="fds_brochure_default_pdf" name="fds_brochure_default_pdf" value="<?php echo esc_attr($default_pdf); ?>" placeholder="https://..." style="flex: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                        <button type="button" id="fds_upload_default_pdf_btn" class="button" style="font-weight: 600;">📁 Pilih / Unggah PDF</button>
                        <button type="button" id="fds_remove_default_pdf_btn" class="button" style="color: #dc2626;">Hapus</button>
                    </div>
                    <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">File ini akan digunakan jika produk drone tertentu belum memiliki upload file brosur spesifik.</span>
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <input type="submit" name="fds_save_brochure_settings" value="Simpan Perubahan Pengaturan" class="button button-primary button-large" style="font-weight: 600; padding: 6px 24px; height: auto; font-size: 14px;">
            </div>
        </form>

        <!-- CARD 2: REKAP LEADS UNDUHAN TERAKHIR -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9; margin-bottom: 16px;">
                <h2 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <span style="background: #dcfce7; color: #15803d; width: 26px; height: 26px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px;">📥</span>
                    2. Riwayat Email Pengunduh Brosur (Leads)
                </h2>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=fds_inquiry')); ?>" class="button button-secondary button-small" style="font-size: 12px;">Lihat Semua di Menu Pesan Masuk &rarr;</a>
            </div>

            <?php if (!empty($leads)): ?>
            <table class="wp-list-table widefat fixed striped table-view-list" style="border-radius: 6px; overflow: hidden; border: 1px solid #e2e8f0;">
                <thead>
                    <tr>
                        <th style="font-weight: 600; width: 180px;">Waktu Unduh</th>
                        <th style="font-weight: 600;">Email Pengunjung</th>
                        <th style="font-weight: 600;">Model Drone</th>
                        <th style="font-weight: 600; width: 120px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $l): 
                        $email = get_post_meta($l->ID, '_fds_inquiry_email', true) ?: '-';
                        $drone_name = get_post_meta($l->ID, '_fds_inquiry_drone', true) ?: '-';
                    ?>
                    <tr>
                        <td style="color: #64748b; font-size: 12px;"><?php echo esc_html(get_the_date('d M Y, H:i', $l->ID)); ?></td>
                        <td><strong style="color: #1e293b;"><?php echo esc_html($email); ?></strong></td>
                        <td><span style="background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; color: #0f172a;"><?php echo esc_html($drone_name); ?></span></td>
                        <td><span style="color: #16a34a; font-size: 12px; font-weight: 600;">✓ Berhasil Diunduh</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div style="text-align: center; padding: 30px; color: #94a3b8; font-size: 13px;">
                Belum ada data unduhan brosur. Saat pengunjung memasukkan email di halaman drone, data akan otomatis tercatat di sini.
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#fds_upload_default_pdf_btn').on('click', function(e) {
            e.preventDefault();
            var pdfUploader = wp.media({
                title: 'Pilih atau Unggah File Brosur PDF',
                button: { text: 'Gunakan File PDF Ini' },
                library: { type: 'application/pdf' },
                multiple: false
            }).on('select', function() {
                var attachment = pdfUploader.state().get('selection').first().toJSON();
                $('#fds_brochure_default_pdf').val(attachment.url);
            }).open();
        });

        $('#fds_remove_default_pdf_btn').on('click', function(e) {
            e.preventDefault();
            $('#fds_brochure_default_pdf').val('');
        });
    });
    </script>
    <?php
}

// 4. AJAX HANDLER: FORM SUBMISSION UNTUK UNDUH BROSUR
add_action('wp_ajax_fds_download_brochure', __NAMESPACE__ . '\\handle_brochure_download_ajax');
add_action('wp_ajax_nopriv_fds_download_brochure', __NAMESPACE__ . '\\handle_brochure_download_ajax');

function handle_brochure_download_ajax() {
    check_ajax_referer('fds_brochure_download_nonce', 'nonce');

    $email      = sanitize_email($_POST['email'] ?? '');
    $drone_id   = intval($_POST['drone_id'] ?? 0);
    $drone_name = sanitize_text_field($_POST['drone_name'] ?? 'Drone FDS');

    if (!is_email($email)) {
        wp_send_json_error([
            'message' => 'Silakan masukkan alamat email yang valid.',
        ]);
    }

    // Ambil URL PDF spesifik drone
    $pdf_url = '';
    if ($drone_id > 0) {
        $pdf_url = get_post_meta($drone_id, 'drone_brosur_url', true);
    }
    if (empty($pdf_url) || $pdf_url === '#') {
        $pdf_url = get_option('fds_brochure_default_pdf', '');
    }

    // Cek apakah lead dengan email dan drone ini sudah pernah ada di database
    $existing = get_posts([
        'post_type'      => 'fds_inquiry',
        'post_status'    => 'publish',
        'meta_query'     => [
            [
                'key'   => '_fds_inquiry_email',
                'value' => $email,
            ],
            [
                'key'   => '_fds_inquiry_drone',
                'value' => $drone_name,
            ],
        ],
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ]);

    // Simpan ke CPT fds_inquiry hanya jika belum pernah tercatat sebelumnya
    if (empty($existing)) {
        $inquiry_id = wp_insert_post([
            'post_title'   => '[Unduh Brosur] ' . $drone_name . ' - ' . $email,
            'post_type'    => 'fds_inquiry',
            'post_status'  => 'publish',
            'post_content' => 'Pengunjung mengunduh brosur teknis PDF untuk produk ' . $drone_name,
        ]);

        if ($inquiry_id && !is_wp_error($inquiry_id)) {
            update_post_meta($inquiry_id, '_fds_inquiry_type', 'brochure_download');
            update_post_meta($inquiry_id, '_fds_inquiry_email', $email);
            update_post_meta($inquiry_id, '_fds_inquiry_drone', $drone_name);
            update_post_meta($inquiry_id, '_fds_inquiry_status', 'unread');
            update_post_meta($inquiry_id, '_fds_inquiry_ip', sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''));
            update_post_meta($inquiry_id, '_fds_inquiry_message', 'Unduh Brosur PDF: ' . $drone_name);
        }
    }

    wp_send_json_success([
        'message'      => 'Email berhasil diverifikasi.',
        'download_url' => !empty($pdf_url) ? esc_url($pdf_url) : '',
    ]);
}

// 5. EXPORT CSV HANDLER
add_action('admin_post_fds_export_brochure_leads', function () {
    if (!current_user_can('manage_options') || !check_admin_referer('fds_export_leads_nonce')) {
        wp_die('Akses ditolak.');
    }

    $leads = get_posts([
        'post_type'      => 'fds_inquiry',
        'post_status'    => 'publish',
        'meta_key'       => '_fds_inquiry_type',
        'meta_value'     => 'brochure_download',
        'posts_per_page' => -1,
    ]);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=fds_leads_brosur_drone_' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');
    // Add UTF-8 BOM
    fputs($output, "\xEF\xBB\xBF");

    fputcsv($output, ['No', 'Tanggal & Waktu', 'Email', 'Model Drone', 'IP Address']);

    $no = 1;
    foreach ($leads as $l) {
        $email      = get_post_meta($l->ID, '_fds_inquiry_email', true) ?: '-';
        $drone_name = get_post_meta($l->ID, '_fds_inquiry_drone', true) ?: '-';
        $ip         = get_post_meta($l->ID, '_fds_inquiry_ip', true) ?: '-';
        $date       = get_the_date('Y-m-d H:i:s', $l->ID);

        fputcsv($output, [$no++, $date, $email, $drone_name, $ip]);
    }

    fclose($output);
    exit;
});
