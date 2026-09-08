<?php

/**
 * Theme filters.
 */

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Search & Archive Query Configuration:
 * - Search strictly across 'post' (Berita & Artikel FDS saja)
 * - 7 items per page
 * - Strict sanitization and max 80 char protection against ReDoS / XSS
 */
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search()) {
            $raw_s = $query->get('s');
            if (!empty($raw_s)) {
                $clean_s = sanitize_text_field(wp_unslash($raw_s));
                $clean_s = preg_replace('/[^\p{L}\p{N}\s\-_.]/u', '', $clean_s);
                $clean_s = trim(preg_replace('/\s+/', ' ', $clean_s));
                $clean_s = mb_substr($clean_s, 0, 80);
                $query->set('s', $clean_s);
            }
            $query->set('post_type', 'post');
            $query->set('posts_per_page', 7);
        } elseif ($query->is_home() || $query->is_archive()) {
            $query->set('posts_per_page', 7);
        }
    }
});

/**
 * Security filter for search queries: sanitize all queries across the application
 */
add_filter('get_search_query', function ($query) {
    return esc_html(wp_strip_all_tags($query));
});

/**
 * Enterprise Security Hardening for Comments:
 * 1. Anti-Bot Honeypot Trap (Reject automated spambots)
 * 2. Transient IP Rate Limiter (Max 5 comments / 5 min per IP)
 * 3. Strict XSS & HTML injection elimination
 * 4. Comment content buffer length protection (Max 2,000 characters)
 * 5. Disallow external URL link injection in author fields
 */
add_filter('preprocess_comment', function ($commentdata) {
    // 1. Anti-Bot Honeypot Trap
    if (!empty($_POST['fds_comment_hp'])) {
        wp_die(__('Aktivitas otomatis mencurigakan terdeteksi.', 'sage'), 'Akses Ditolak', ['response' => 403]);
    }

    // 2. IP Rate Limiting (Anti-Flood / Anti-DoS)
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $ip = sanitize_text_field($ip);

    if (!empty($ip) && !is_user_logged_in()) {
        $rate_key = 'fds_comment_rate_' . md5($ip);
        $attempts = (int) get_transient($rate_key);
        if ($attempts >= 5) {
            wp_die(
                __('Terlalu banyak komentar terkirim dalam waktu singkat. Silakan tunggu 5 menit lagi.', 'sage'),
                'Batas Pengiriman Tercapai',
                ['response' => 429]
            );
        }
        set_transient($rate_key, $attempts + 1, 5 * MINUTE_IN_SECONDS);
    }

    // 3. Sanitasi Ketat Nama Pengirim (Anti-XSS & Anti-Spam)
    if (!empty($commentdata['comment_author'])) {
        $clean_author = wp_strip_all_tags($commentdata['comment_author']);
        $clean_author = preg_replace('/[^\p{L}\p{N}\s.\'-]/u', '', $clean_author);
        $clean_author = trim(preg_replace('/\s+/', ' ', $clean_author));
        $commentdata['comment_author'] = mb_substr($clean_author, 0, 60);
    }

    // 4. Validasi Format Email
    if (!is_user_logged_in() && !empty($commentdata['comment_author_email'])) {
        $clean_email = sanitize_email($commentdata['comment_author_email']);
        if (!is_email($clean_email)) {
            wp_die(__('Format alamat email tidak valid.', 'sage'), 'Email Tidak Valid', ['response' => 400]);
        }
        $commentdata['comment_author_email'] = $clean_email;
    }

    // 5. Sanitasi & Pembatasan Panjang Isi Komentar (Max 2.000 karakter, bebas script/tag)
    if (!empty($commentdata['comment_content'])) {
        $clean_content = wp_strip_all_tags($commentdata['comment_content']);
        $clean_content = trim($clean_content);

        if (mb_strlen($clean_content) > 2000) {
            wp_die(__('Komentar terlalu panjang. Maksimal 2.000 karakter.', 'sage'), 'Karakter Melebihi Batas', ['response' => 400]);
        }

        if (empty($clean_content)) {
            wp_die(__('Silakan tulis komentar Anda.', 'sage'), 'Komentar Kosong', ['response' => 400]);
        }

        $commentdata['comment_content'] = $clean_content;
    }

    // 6. Matikan Author URL untuk mencegah backlink spam injection
    $commentdata['comment_author_url'] = '';

    return $commentdata;
});
