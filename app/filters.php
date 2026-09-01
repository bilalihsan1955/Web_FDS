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
