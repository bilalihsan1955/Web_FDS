<?php

/**
 * Custom Post Type: Mitra (Kemitraan & Klien Strategis)
 * Theme: FDS Theme (PT Karya Solusi Angkasa)
 *
 * Fitur:
 * 1. Form Super Simpel: Khusus Upload Logo Mitra (1-Click Media Uploader).
 * 2. Auto-set Featured Image & Auto-fill Title dari nama file gambar.
 * 3. Pratinjau Logo Instan di form dan tabel daftar admin.
 */

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

// 1. Nonaktifkan Block/Gutenberg Editor untuk Mitra (Gunakan Form Simpel)
add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) {
    if ($post_type === 'mitra') {
        return false;
    }
    return $use_block_editor;
}, 10, 2);

// 2. Registrasi CPT 'mitra'
add_action('init', function () {
    $labels = [
        'name'               => 'Mitra',
        'singular_name'      => 'Mitra',
        'menu_name'          => 'Mitra',
        'add_new'            => 'Tambah Mitra Baru',
        'add_new_item'       => 'Tambah Logo Mitra Baru',
        'edit_item'          => 'Edit Logo Mitra',
        'new_item'           => 'Mitra Baru',
        'view_item'          => 'Lihat Mitra',
        'search_items'       => 'Cari Mitra',
        'not_found'          => 'Belum ada logo mitra yang diunggah',
        'not_found_in_trash' => 'Tidak ada mitra di tempat sampah',
    ];

    $args = [
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => true,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-networking',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => ['title', 'thumbnail'],
        'rewrite'             => false,
        'query_var'           => false,
        'show_in_rest'        => false,
    ];

    register_post_type('mitra', $args);
});

// 3. Ubah placeholder judul Mitra & Enqueue WP Media
add_filter('enter_title_here', function ($title, $post) {
    if ($post && $post->post_type === 'mitra') {
        return 'Masukkan Nama Mitra / Institusi (Contoh: PT Petrosida Gresik)';
    }
    return $title;
}, 10, 2);

add_action('admin_enqueue_scripts', function ($hook) {
    global $post_type;
    if ($post_type === 'mitra') {
        wp_enqueue_media();
    }
});

// 4. Custom Metabox 1-Click Upload Logo Mitra
add_action('add_meta_boxes', function () {
    // Hapus metabox postimagediv default agar tidak membingungkan
    remove_meta_box('postimagediv', 'mitra', 'side');

    add_meta_box(
        'fds_mitra_simple_panel',
        '🏢 Unggah Logo Mitra / Klien FDS',
        'App\render_mitra_simple_metabox',
        'mitra',
        'normal',
        'high'
    );
});

function render_mitra_simple_metabox($post) {
    wp_nonce_field('fds_mitra_save_nonce', 'fds_mitra_nonce');

    $thumb_id = get_post_thumbnail_id($post->ID);
    $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : '';
    ?>
    <style>
        #title-prompt-text.screen-reader-text {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }
    </style>
    <div style="padding: 16px 8px;">
        <p style="font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 16px;">
            Cukup unggah logo mitra/klien (format PNG transparan atau SVG dianjurkan). Logo ini akan otomatis tampil di baris logo kemitraan di Beranda.
        </p>

        <!-- Hidden input untuk _thumbnail_id -->
        <input type="hidden" id="_thumbnail_id" name="_thumbnail_id" value="<?php echo esc_attr($thumb_id ? $thumb_id : '-1'); ?>">

        <div style="display: flex; gap: 24px; align-items: center; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 24px;">
            
            <!-- Box Preview Logo -->
            <div id="mitra-logo-preview-box" style="width: 220px; height: 120px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); flex-shrink: 0;">
                <?php if ($thumb_url) : ?>
                    <img id="mitra-logo-img" src="<?php echo esc_url($thumb_url); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                <?php else : ?>
                    <div id="mitra-logo-placeholder" style="text-align: center; color: #94a3b8; font-size: 12px;">
                        <span style="font-size: 28px; display: block; margin-bottom: 4px;">🖼️</span>
                        Belum ada logo
                    </div>
                    <img id="mitra-logo-img" src="" style="max-width: 100%; max-height: 100%; object-fit: contain; display: none;">
                <?php endif; ?>
            </div>

            <!-- Tombol Aksi -->
            <div style="flex: 1;">
                <div style="margin-bottom: 12px;">
                    <button type="button" id="btn-choose-mitra-logo" class="button button-primary button-hero" style="font-size: 14px; height: 42px; line-height: 40px; padding: 0 20px; display: inline-flex; align-items: center; gap: 8px;">
                        📷 Pilih / Unggah Logo Mitra
                    </button>
                    <button type="button" id="btn-remove-mitra-logo" class="button button-link-delete" style="margin-left: 12px; font-size: 13px; color: #dc2626; <?php echo !$thumb_url ? 'display: none;' : ''; ?>">
                        Hapus Logo
                    </button>
                </div>
                <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.5;">
                    💡 <em>Tips: Saat Anda memilih gambar dari Media Library, judul mitra di atas akan otomatis terisi mengikuti nama logo jika masih kosong.</em>
                </p>
            </div>

        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;

        // Hilangkan placeholder teks yang menumpuk jika input judul sudah terisi
        function checkTitlePlaceholder() {
            var val = $('#title').val();
            if (val && val.trim().length > 0) {
                $('#title-prompt-text').addClass('screen-reader-text');
            }
        }
        checkTitlePlaceholder();
        $(document).on('input keyup change focus blur', '#title', checkTitlePlaceholder);

        $('#btn-choose-mitra-logo').on('click', function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media({
                title: 'Pilih atau Unggah Logo Mitra',
                button: { text: 'Gunakan Logo Ini' },
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                
                // Simpan Thumbnail ID
                $('#_thumbnail_id').val(attachment.id);
                
                // Update Preview
                $('#mitra-logo-placeholder').hide();
                $('#mitra-logo-img').attr('src', attachment.url).show();
                $('#btn-remove-mitra-logo').show();

                // Auto-fill Judul jika kosong & hilangkan teks placeholder Add title
                var titleInput = $('#title');
                if (titleInput.length && !titleInput.val().trim()) {
                    var cleanTitle = attachment.title || attachment.filename.replace(/\.[^/.]+$/, "");
                    cleanTitle = cleanTitle.replace(/[-_]/g, ' ');
                    titleInput.val(cleanTitle);
                    $('#title-prompt-text').addClass('screen-reader-text');
                    titleInput.trigger('input').trigger('change').focus();
                }
            });

            mediaUploader.open();
        });

        $('#btn-remove-mitra-logo').on('click', function(e) {
            e.preventDefault();
            $('#_thumbnail_id').val('-1');
            $('#mitra-logo-img').attr('src', '').hide();
            $('#mitra-logo-placeholder').show();
            $(this).hide();
        });
    });
    </script>
    <?php
}

// 5. Kolom Khusus di Daftar Tabel Mitra (edit.php?post_type=mitra)
add_filter('manage_mitra_posts_columns', function ($columns) {
    $new = [];
    if (isset($columns['cb'])) {
        $new['cb'] = $columns['cb'];
    }
    $new['mitra_logo']  = 'Logo Mitra';
    $new['title']       = 'Nama Mitra / Institusi';
    if (isset($columns['date'])) {
        $new['date'] = $columns['date'];
    }
    return $new;
});

add_action('manage_mitra_posts_custom_column', function ($column, $post_id) {
    if ($column === 'mitra_logo') {
        $thumb_url = get_the_post_thumbnail_url($post_id, 'medium');
        if ($thumb_url) {
            echo '<div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:6px;padding:4px 8px;display:inline-flex;align-items:center;justify-content:center;height:44px;min-width:70px;">';
            echo '<img src="' . esc_url($thumb_url) . '" style="max-height:36px;max-width:120px;object-fit:contain;">';
            echo '</div>';
        } else {
            echo '<span style="color:#94a3b8;font-size:11px;font-style:italic;">Belum ada logo</span>';
        }
    }
}, 10, 2);
