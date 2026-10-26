<?php

/**
 * Global Theme Helper Functions (Non-namespaced)
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('fds_img')) {
    function fds_img($key, $default = '') {
        if (function_exists('App\fds_img')) {
            return \App\fds_img($key, $default);
        }
        $val = get_option("fds_img_{$key}", '');
        return !empty($val) ? $val : $default;
    }
}

if (!function_exists('fds_posts_pagination')) {
    /**
     * Render modern numbered circular pagination for WordPress queries
     */
    function fds_posts_pagination($query = null) {
        global $wp_query;
        $q = $query ?: $wp_query;
        $total_pages = (int) $q->max_num_pages;
        $current_page = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));

        if ($total_pages <= 1) {
            return '';
        }

        $html = '<nav aria-label="Navigasi Halaman" class="flex items-center justify-center gap-2 sm:gap-2.5 flex-wrap my-2">';

        // Prev Button
        if ($current_page > 1) {
            $prev_url = esc_url(get_pagenum_link($current_page - 1));
            $html .= '<a href="' . $prev_url . '" aria-label="Halaman Sebelumnya" class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white hover:bg-[#f5f5f7] hover:border-[#0f808a]/40 text-[#1d1d1f] hover:text-[#0f808a] border border-black/[0.08] shadow-sm flex items-center justify-center transition-all duration-150 active:scale-95">
                <svg class="w-4 h-4 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>';
        } else {
            $html .= '<span aria-disabled="true" class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white text-[#94a3b8] border border-black/[0.06] shadow-sm flex items-center justify-center opacity-40 cursor-not-allowed select-none">
                <svg class="w-4 h-4 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </span>';
        }

        // Page Numbers
        $range = 2;
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == 1 || $i == $total_pages || ($i >= $current_page - $range && $i <= $current_page + $range)) {
                if ($i == $current_page) {
                    $html .= '<span aria-current="page" class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#0f808a] text-white font-semibold text-[13.5px] flex items-center justify-center shadow-md select-none">' . $i . '</span>';
                } else {
                    $page_url = esc_url(get_pagenum_link($i));
                    $html .= '<a href="' . $page_url . '" class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white hover:bg-[#f5f5f7] hover:border-[#0f808a]/40 text-[#1d1d1f] hover:text-[#0f808a] font-medium text-[13.5px] border border-black/[0.08] shadow-sm flex items-center justify-center transition-all duration-150 active:scale-95">' . $i . '</a>';
                }
            } elseif ($i == 2 && $current_page - $range > 2) {
                $html .= '<span class="w-6 sm:w-8 h-10 text-[#86868b] font-medium text-[14px] flex items-center justify-center select-none">&hellip;</span>';
            } elseif ($i == $total_pages - 1 && $current_page + $range < $total_pages - 1) {
                $html .= '<span class="w-6 sm:w-8 h-10 text-[#86868b] font-medium text-[14px] flex items-center justify-center select-none">&hellip;</span>';
            }
        }

        // Next Button
        if ($current_page < $total_pages) {
            $next_url = esc_url(get_pagenum_link($current_page + 1));
            $html .= '<a href="' . $next_url . '" aria-label="Halaman Berikutnya" class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white hover:bg-[#f5f5f7] hover:border-[#0f808a]/40 text-[#1d1d1f] hover:text-[#0f808a] border border-black/[0.08] shadow-sm flex items-center justify-center transition-all duration-150 active:scale-95">
                <svg class="w-4 h-4 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>';
        } else {
            $html .= '<span aria-disabled="true" class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white text-[#94a3b8] border border-black/[0.06] shadow-sm flex items-center justify-center opacity-40 cursor-not-allowed select-none">
                <svg class="w-4 h-4 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>';
        }

        $html .= '</nav>';
        return $html;
    }
}

if (!function_exists('fds_clean_string')) {
    /**
     * Clean and safely decode HTML entities from dynamic strings
     */
    function fds_clean_string($val) {
        if (!is_string($val)) return $val;
        $prev = '';
        while ($prev !== $val) {
            $prev = $val;
            $val = wp_specialchars_decode($val, ENT_QUOTES);
            $val = html_entity_decode($val, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $val;
    }
}

if (!function_exists('fds_deep_decode')) {
    /**
     * Recursively clean and decode HTML entities from arrays or strings
     */
    function fds_deep_decode($val) {
        if (is_array($val)) {
            return array_map('fds_deep_decode', $val);
        }
        return fds_clean_string($val);
    }
}

if (!function_exists('fds_get_default_drone_catalog')) {
    /**
     * Global accessor for default drone catalog specs
     */
    function fds_get_default_drone_catalog() {
        if (function_exists('\App\fds_get_default_drone_catalog')) {
            return \App\fds_get_default_drone_catalog();
        }
        return [];
    }
}

if (!function_exists('fds_aos')) {
    /**
     * Generate HTML attributes for AOS animation
     *
     * @param string $type fade-up, fade-down, fade-left, fade-right, zoom-in, etc.
     * @param int $delay delay in milliseconds
     * @param int|null $offset trigger offset in pixels
     * @param int|null $duration animation duration in milliseconds
     * @return string
     */
    function fds_aos($type = 'fade-up', $delay = 0, $offset = null, $duration = null) {
        $attrs = 'data-aos="' . esc_attr($type) . '"';
        if ($delay > 0) {
            $attrs .= ' data-aos-delay="' . intval($delay) . '"';
        }
        if ($offset !== null) {
            $attrs .= ' data-aos-offset="' . intval($offset) . '"';
        }
        if ($duration !== null) {
            $attrs .= ' data-aos-duration="' . intval($duration) . '"';
        }
        return $attrs;
    }
}

if (!function_exists('fds_stagger')) {
    /**
     * Generate staggered AOS attributes for looped items
     *
     * @param int $index current loop index (0-based)
     * @param int $step step interval in milliseconds (default 100)
     * @param int $base base delay in milliseconds (default 0)
     * @param string $type animation type (default fade-up)
     * @param int $modulo optional modulo for row-based staggering (e.g. 3 for 3 columns)
     * @return string
     */
    function fds_stagger($index = 0, $step = 100, $base = 0, $type = 'fade-up', $modulo = 0) {
        $idx = intval($index);
        $st = intval($step);
        $bs = intval($base);
        $mod = intval($modulo);

        $delay = ($mod > 0) ? (($idx % $mod) * $st + $bs) : ($idx * $st + $bs);
        return fds_aos($type, $delay);
    }
}


