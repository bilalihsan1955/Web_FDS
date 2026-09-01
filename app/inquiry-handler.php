<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enterprise Inquiry & Lead Management System
 * Theme: FDS Theme (PT Karya Solusi Angkasa)
 * 
 * Features:
 * 1. CPT 'fds_inquiry' ("Pesan Masuk") with custom columns, unread counter badge, and quick reply actions.
 * 2. Custom Meta Boxes for Viewing & Managing Inquiry Status.
 * 3. Secure AJAX Form Submission with Nonce verification, sanitization, and automated storage.
 */

// =========================================================================
// 1. REGISTER CPT 'fds_inquiry' ("Pesan Masuk")
// =========================================================================

add_action('init', function () {
    // Hitung pesan unread untuk badge counter di menu
    $unread_count = 0;
    if (is_admin()) {
        $unread_query = new \WP_Query([
            'post_type'      => 'fds_inquiry',
            'post_status'    => 'publish',
            'meta_key'       => '_fds_inquiry_status',
            'meta_value'     => 'unread',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ]);
        $unread_count = $unread_query->found_posts;
    }

    $menu_title = 'Pesan Masuk';
    if ($unread_count > 0) {
        $menu_title .= sprintf(' <span class="awaiting-mod count-%d" style="background:#2563eb;color:#fff;border-radius:10px;padding:2px 7px;font-size:10px;font-weight:700;"><span class="pending-count">%d</span></span>', $unread_count, $unread_count);
    }

    $labels = [
        'name'               => 'Pesan Masuk',
        'singular_name'      => 'Pesan Masuk',
        'menu_name'          => $menu_title,
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
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 26,
        'menu_icon'          => 'dashicons-email-alt',
        'supports'           => ['title'],
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
        'hierarchical'       => false,
    ]);
});

// Nonaktifkan Gutenberg untuk fds_inquiry
add_filter('use_block_editor_for_post_type', function ($use_block, $post_type) {
    if ($post_type === 'fds_inquiry') {
        return false;
    }
    return $use_block;
}, 10, 2);

// =========================================================================
// 2. CUSTOM ADMIN COLUMNS UNTUK PESAN MASUK
// =========================================================================

add_filter('manage_fds_inquiry_posts_columns', function ($columns) {
    return [
        'cb'              => '<input type="checkbox" />',
        'status_badge'    => 'Status',
        'title'           => 'Nama Pengirim',
        'inquiry_company' => 'Perusahaan / Instansi',
        'inquiry_contact' => 'Kontak (WA / Email)',
        'inquiry_message' => 'Pesan / Kebutuhan',
        'inquiry_actions' => 'Aksi Cepat',
        'date'            => 'Waktu Masuk',
    ];
});

add_action('manage_fds_inquiry_posts_custom_column', function ($column, $post_id) {
    $first_name = get_post_meta($post_id, '_fds_inquiry_first_name', true) ?: '-';
    $last_name  = get_post_meta($post_id, '_fds_inquiry_last_name', true) ?: '';
    $company    = get_post_meta($post_id, '_fds_inquiry_company', true) ?: '-';
    $email      = get_post_meta($post_id, '_fds_inquiry_email', true) ?: '-';
    $phone      = get_post_meta($post_id, '_fds_inquiry_phone', true) ?: '-';
    $message    = get_post_meta($post_id, '_fds_inquiry_message', true) ?: '-';
    $status     = get_post_meta($post_id, '_fds_inquiry_status', true) ?: 'unread';

    switch ($column) {
        case 'status_badge':
            if ($status === 'replied') {
                echo '<span style="display:inline-block;padding:3px 8px;background:#dcfce7;color:#15803d;border-radius:12px;font-size:11px;font-weight:700;">Sudah Dihubungi</span>';
            } elseif ($status === 'in_progress') {
                echo '<span style="display:inline-block;padding:3px 8px;background:#fef9c3;color:#854d0e;border-radius:12px;font-size:11px;font-weight:700;">Dalam Proses</span>';
            } else {
                echo '<span style="display:inline-block;padding:3px 8px;background:#dbeafe;color:#1d4ed8;border-radius:12px;font-size:11px;font-weight:700;">Baru</span>';
            }
            break;

        case 'inquiry_company':
            echo '<strong>' . esc_html($company) . '</strong>';
            break;

        case 'inquiry_contact':
            echo '<div style="font-size:12px;line-height:1.4;">';
            if ($phone !== '-') {
                echo '<div>📱 <a href="https://wa.me/' . preg_replace('/[^0-9]/', '', $phone) . '" target="_blank" style="color:#059669;font-weight:600;">' . esc_html($phone) . '</a></div>';
            }
            if ($email !== '-') {
                echo '<div>✉️ <a href="mailto:' . esc_attr($email) . '" style="color:#2563eb;">' . esc_html($email) . '</a></div>';
            }
            echo '</div>';
            break;

        case 'inquiry_message':
            $excerpt = wp_trim_words($message, 12, '...');
            echo '<span style="color:#475569;font-size:13px;" title="' . esc_attr($message) . '">' . esc_html($excerpt) . '</span>';
            break;

        case 'inquiry_actions':
            echo '<div style="display:flex;gap:6px;align-items:center;">';
            if ($phone !== '-') {
                $clean_phone = preg_replace('/[^0-9]/', '', $phone);
                echo '<a href="https://wa.me/' . $clean_phone . '" target="_blank" class="button button-small" style="background:#25D366;color:#fff;border-color:#25D366;font-weight:600;" title="Balas via WhatsApp">WA</a>';
            }
            if ($email !== '-') {
                echo '<a href="mailto:' . esc_attr($email) . '" class="button button-small" style="font-weight:600;" title="Balas via Email">Email</a>';
            }
            echo '</div>';
            break;
    }
}, 10, 2);

// =========================================================================
// 3. META BOX DETAIL PESAN MASUK
// =========================================================================

add_action('add_meta_boxes', function () {
    add_meta_box(
        'fds_inquiry_details_box',
        'Detail Informasi Pesan Masuk & Lead',
        function ($post) {
            wp_nonce_field('fds_save_inquiry_meta', 'fds_inquiry_meta_nonce');

            $first_name = get_post_meta($post->ID, '_fds_inquiry_first_name', true);
            $last_name  = get_post_meta($post->ID, '_fds_inquiry_last_name', true);
            $company    = get_post_meta($post->ID, '_fds_inquiry_company', true);
            $email      = get_post_meta($post->ID, '_fds_inquiry_email', true);
            $phone      = get_post_meta($post->ID, '_fds_inquiry_phone', true);
            $message    = get_post_meta($post->ID, '_fds_inquiry_message', true);
            $status     = get_post_meta($post->ID, '_fds_inquiry_status', true) ?: 'unread';
            $ip         = get_post_meta($post->ID, '_fds_inquiry_ip', true) ?: '-';
            $date       = get_the_date('d F Y, H:i WIB', $post->ID);
            ?>
            <style>
                .fds-inq-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                .fds-inq-table th { width: 200px; text-align: left; padding: 12px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-size: 13px; color: #475569; }
                .fds-inq-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 14px; color: #1e293b; }
                .fds-btn-wa { display: inline-flex; align-items: center; gap: 6px; background: #25D366; color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 13px; }
                .fds-btn-wa:hover { background: #1eb851; color: #fff; }
                .fds-btn-email { display: inline-flex; align-items: center; gap: 6px; background: #0284c7; color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 13px; margin-left: 8px; }
                .fds-btn-email:hover { background: #0369a1; color: #fff; }
            </style>

            <table class="fds-inq-table">
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
                    <td><strong><?php echo esc_html(trim($first_name . ' ' . $last_name) ?: '-'); ?></strong></td>
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
                    <th>Detail Pesan / Kebutuhan</th>
                    <td>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 18px; font-size: 14px; line-height: 1.6; color: #0f172a; white-space: pre-wrap;">
                            <?php echo esc_html($message ?: '-'); ?>
                        </div>
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
        }
    }
});

// =========================================================================
// 4. AJAX SUBMISSION ENDPOINT (Frontend Form -> WP Database)
// =========================================================================

add_action('wp_ajax_fds_submit_inquiry', __NAMESPACE__ . '\\fds_handle_inquiry_submission');
add_action('wp_ajax_nopriv_fds_submit_inquiry', __NAMESPACE__ . '\\fds_handle_inquiry_submission');

function fds_handle_inquiry_submission() {
    // 1. Verifikasi Nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'fds_inquiry_nonce')) {
        wp_send_json_error(['message' => 'Sesi kedaluwarsa. Silakan muat ulang halaman dan coba lagi.'], 403);
    }

    // 2. Sanitasi Data Masukan
    $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
    $last_name  = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
    $company    = isset($_POST['company']) ? sanitize_text_field(wp_unslash($_POST['company'])) : '';
    $email      = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $phone      = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $message    = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';

    // 3. Validasi Semua Field Wajib Diisi
    if (empty($first_name)) {
        wp_send_json_error(['message' => 'Silakan isi nama depan Anda.'], 400);
    }

    if (empty($last_name)) {
        wp_send_json_error(['message' => 'Silakan isi nama belakang Anda.'], 400);
    }

    if (empty($company)) {
        wp_send_json_error(['message' => 'Silakan isi nama perusahaan atau instansi Anda.'], 400);
    }

    if (empty($email)) {
        wp_send_json_error(['message' => 'Silakan isi alamat email bisnis Anda.'], 400);
    }

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Format alamat email tidak valid.'], 400);
    }

    if (empty($phone)) {
        wp_send_json_error(['message' => 'Silakan isi nomor telepon / WhatsApp Anda.'], 400);
    }

    if (empty($message)) {
        wp_send_json_error(['message' => 'Silakan tuliskan deskripsi kebutuhan atau pertanyaan Anda.'], 400);
    }

    // 4. Susun Judul Post
    $full_name = trim($first_name . ' ' . $last_name);
    $post_title = $full_name;
    if (!empty($company)) {
        $post_title .= ' - ' . $company;
    } else {
        $post_title .= ' (' . ($phone ?: $email) . ')';
    }

    // 5. Dapatkan IP Address Pengirim
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $ip = sanitize_text_field($ip);

    // 6. Simpan ke Custom Post Type 'fds_inquiry'
    $post_id = wp_insert_post([
        'post_type'    => 'fds_inquiry',
        'post_title'   => $post_title,
        'post_content' => $message,
        'post_status'  => 'publish',
        'meta_input'   => [
            '_fds_inquiry_first_name' => $first_name,
            '_fds_inquiry_last_name'  => $last_name,
            '_fds_inquiry_company'    => $company,
            '_fds_inquiry_email'      => $email,
            '_fds_inquiry_phone'      => $phone,
            '_fds_inquiry_message'    => $message,
            '_fds_inquiry_status'     => 'unread',
            '_fds_inquiry_ip'         => $ip,
        ],
    ]);

    if (is_wp_error($post_id)) {
        wp_send_json_error(['message' => 'Gagal menyimpan pesan. Silakan coba lagi.'], 500);
    }

    // 7. Opsional: Notifikasi Email ke Admin FDS
    $admin_email = get_option('admin_email');
    if ($admin_email) {
        $subject = 'Inquiry Baru: ' . $post_title;
        $body = "Pesan inquiry baru telah diterima dari website Full Drone Solutions:\n\n"
              . "Nama: {$full_name}\n"
              . "Perusahaan: " . ($company ?: '-') . "\n"
              . "WhatsApp/Telepon: " . ($phone ?: '-') . "\n"
              . "Email: " . ($email ?: '-') . "\n\n"
              . "Pesan Kebutuhan:\n{$message}\n\n"
              . "Lihat & kelola pesan di WP Admin: " . admin_url('post.php?post=' . $post_id . '&action=edit');
        
        @wp_mail($admin_email, $subject, $body);
    }

    wp_send_json_success([
        'message' => 'Pesan Anda telah berhasil terkirim dan tersimpan. Tim Enterprise FDS akan segera merespons dalam 1×24 jam kerja.',
    ]);
}
