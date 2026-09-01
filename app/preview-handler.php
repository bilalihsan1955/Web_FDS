<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS LIVE PREVIEW & AUTOSAVE HANDLER
 * =========================================================================
 * Memastikan data yang diedit (termasuk gambar unggulan, spesifikasi teknis,
 * tagline, use-cases, dan custom fields) langsung tampil di halaman Preview
 * (Pratinjau) tanpa harus menekan tombol Publish/Update terlebih dahulu.
 */

// 1. Simpan seluruh custom fields dan thumbnail ke revisi autosave saat tombol Pratinjau diklik
add_action('save_post', function ($post_id) {
    if (wp_is_post_autosave($post_id)) {
        $parent_id = wp_is_post_autosave($post_id);
        if (!current_user_can('edit_post', $parent_id)) {
            return;
        }
        
        // List semua custom field yang relevan untuk drone dan post
        $custom_keys = [
            'drone_badge', 'drone_tagline', 'drone_desc', 'drone_specs_raw',
            'drone_kategori',
            'drone_spec_kapasitas', 'drone_spec_durasi', 'drone_spec_baterai',
            'drone_spec_produktivitas', 'drone_spec_kecepatan', 'drone_spec_ketahanan',
            'drone_spec_otonomi', 'drone_spec_gcs', 'drone_spec_sertifikasi',
            'drone_for', 'drone_specs_img',
            'drone_stat1_num', 'drone_stat1_lbl',
            'drone_stat2_num', 'drone_stat2_lbl',
            'drone_stat3_num', 'drone_stat3_lbl',
            'drone_stat4_num', 'drone_stat4_lbl',
            '_thumbnail_id'
        ];

        foreach ($custom_keys as $key) {
            if (isset($_POST[$key])) {
                update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
            } elseif (isset($_REQUEST[$key])) {
                update_post_meta($post_id, $key, sanitize_text_field($_REQUEST[$key]));
            }
        }
    }
}, 10, 1);

// 2. Intercept pembacaan post meta saat is_preview() aktif agar membaca dari revisi autosave
add_filter('get_post_metadata', function ($value, $object_id, $meta_key, $single) {
    static $in_filter = false;
    if ($in_filter) return $value;

    if (is_preview() && !is_admin()) {
        $queried_id = get_the_ID();
        if ($object_id == $queried_id) {
            $autosave = wp_get_post_autosave($queried_id);
            if ($autosave && $autosave->ID != $object_id) {
                $in_filter = true;
                $preview_val = get_post_meta($autosave->ID, $meta_key, $single);
                $in_filter = false;
                
                if (!empty($preview_val)) {
                    return $preview_val;
                }
            }
        }
    }
    return $value;
}, 10, 4);

// 3. Intercept thumbnail ID saat is_preview() aktif
add_filter('post_thumbnail_id', function ($thumbnail_id, $post) {
    if (is_preview() && !is_admin()) {
        $post_obj = get_post($post);
        if ($post_obj) {
            $autosave = wp_get_post_autosave($post_obj->ID);
            if ($autosave) {
                $rev_thumb = get_post_meta($autosave->ID, '_thumbnail_id', true);
                if (!empty($rev_thumb)) {
                    return $rev_thumb;
                }
            }
        }
    }
    return $thumbnail_id;
}, 10, 2);

// 4. Pastikan browser tidak meng-cache halaman preview
add_action('template_redirect', function () {
    if (is_preview()) {
        nocache_headers();
    }
});
