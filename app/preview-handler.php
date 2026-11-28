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
        $multiline_keys = ['drone_specs_raw', 'drone_payload_specs_raw', 'drone_for', 'drone_desc'];
        $custom_keys = [
            'drone_badge', 'drone_tagline', 'drone_desc', 'drone_specs_raw',
            'drone_payload_specs_raw',
            'drone_card_m1_lbl', 'drone_card_m1_val', 'drone_card_m2_lbl', 'drone_card_m2_val',
            'drone_kategori',
            'drone_spec_kapasitas', 'drone_spec_durasi', 'drone_spec_baterai',
            'drone_spec_produktivitas', 'drone_spec_kecepatan', 'drone_spec_ketahanan',
            'drone_spec_otonomi', 'drone_spec_gcs', 'drone_spec_sertifikasi',
            'drone_for', 'drone_specs_img', 'drone_brosur_url', 'drone_video_url',
            'drone_uc1_t', 'drone_uc1_d', 'drone_uc2_t', 'drone_uc2_d',
            'drone_uc3_t', 'drone_uc3_d', 'drone_uc4_t', 'drone_uc4_d',
            'drone_stat1_num', 'drone_stat1_lbl',
            'drone_stat2_num', 'drone_stat2_lbl',
            'drone_stat3_num', 'drone_stat3_lbl',
            'drone_stat4_num', 'drone_stat4_lbl',
            'drone_gallery_customized',
            'drone_gallery_1_img', 'drone_gallery_1_cap',
            'drone_gallery_2_img', 'drone_gallery_2_cap',
            'drone_gallery_3_img', 'drone_gallery_3_cap',
            'drone_faq_customized',
            'drone_faq_1_q', 'drone_faq_1_a',
            'drone_faq_2_q', 'drone_faq_2_a',
            'drone_faq_3_q', 'drone_faq_3_a',
            'drone_faq_4_q', 'drone_faq_4_a',
            'drone_faq_5_q', 'drone_faq_5_a',
            '_thumbnail_id'
        ];

        foreach ($custom_keys as $key) {
            $raw_val = $_POST[$key] ?? ($_REQUEST[$key] ?? null);
            if ($raw_val !== null) {
                $clean_val = in_array($key, $multiline_keys, true)
                    ? sanitize_textarea_field(wp_unslash((string) $raw_val))
                    : sanitize_text_field(wp_unslash((string) $raw_val));
                update_post_meta($post_id, $key, $clean_val);
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
