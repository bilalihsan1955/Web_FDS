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
