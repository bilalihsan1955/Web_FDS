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
 * Set posts per page for blog, category, and search archives to 7 (1 featured + 6 grid).
 */
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && ($query->is_home() || $query->is_archive() || $query->is_search())) {
        $query->set('posts_per_page', 7);
    }
});

/**
 * Register clean & pretty rewrite rules for search URLs (/search/{query}/)
 */
add_action('init', function () {
    add_rewrite_rule(
        '^search/([^/]+)/page/([0-9]+)/?$',
        'index.php?s=$matches[1]&paged=$matches[2]',
        'top'
    );
    add_rewrite_rule(
        '^search/([^/]+)/?$',
        'index.php?s=$matches[1]',
        'top'
    );
});

/**
 * Clean & Secure Search URL Redirection:
 * Converts /?s=keyword to /search/keyword/ with strict sanitization against XSS and query flooding.
 */
add_action('template_redirect', function () {
    if (is_search() && !empty($_GET['s']) && !is_admin()) {
        $raw_query = wp_unslash($_GET['s']);
        // Sanitize string and strip control/dangerous characters
        $clean_query = sanitize_text_field($raw_query);
        $clean_query = preg_replace('/[^\p{L}\p{N}\s\-_.]/u', '', $clean_query);
        $clean_query = trim(preg_replace('/\s+/', ' ', $clean_query));
        // Max query length protection against ReDoS / buffer abuse
        $clean_query = mb_substr($clean_query, 0, 80);

        if (empty($clean_query)) {
            $blog_url = get_permalink(get_option('page_for_posts')) ?: home_url('/blog');
            wp_safe_redirect(esc_url($blog_url));
            exit;
        }

        // Redirect cleanly to /search/keyword/
        wp_safe_redirect(home_url('/search/' . rawurlencode($clean_query) . '/'));
        exit;
    }
});

/**
 * Security filter for search queries: sanitize all queries across the application
 */
add_filter('get_search_query', function ($query) {
    return esc_html(wp_strip_all_tags($query));
});
