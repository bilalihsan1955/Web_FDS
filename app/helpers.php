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

if (!function_exists('fds_optimized_img_url')) {
    /**
     * Cerdas mengonversi URL foto mentah berukuran besar (kamera/AI) menjadi ukuran web teroptimasi (large/medium_large)
     * jika tersedia di WordPress Media Library, memangkas payload hingga 85-95%.
     */
    function fds_optimized_img_url($url, $size = 'large') {
        if (empty($url) || !is_string($url)) {
            return $url;
        }

        // Hanya proses media lokal uploads WordPress
        if (strpos($url, '/wp-content/uploads/') === false) {
            return $url;
        }

        static $cache = [];
        $cache_key = md5($url . '_' . $size);
        if (isset($cache[$cache_key])) {
            return $cache[$cache_key];
        }

        // Cek jika URL sudah merupakan versi resized (-1024x..., -768x..., dsb)
        if (preg_match('/-\d+x\d+\.(jpe?g|png|webp|avif)$/i', $url)) {
            $cache[$cache_key] = $url;
            return $url;
        }

        // Resolusi attachment ID
        $attachment_id = attachment_url_to_postid($url);
        if ($attachment_id) {
            $src = wp_get_attachment_image_url($attachment_id, $size);
            if ($src) {
                $cache[$cache_key] = $src;
                return $src;
            }
        }

        $cache[$cache_key] = $url;
        return $url;
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

if (!function_exists('fds_scrub_legacy_text')) {
    /**
     * Sanitasi defensif output SSR dari klaim lama yang tidak terverifikasi.
     */
    function fds_scrub_legacy_text($text, bool $allowTkdn = true, bool $allowSni = true) {
        if (!is_string($text) || $text === '') {
            return $text;
        }

        $text = str_ireplace('FDS STATION', 'FDS Station', $text);
        $text = preg_replace('/\bBest\s+UAV\b/i', 'UAV Industri', $text);
        $text = preg_replace('/\bsejak\s*(?:tahun\s*)?2012\b/i', 'dengan rekayasa lokal Indonesia', $text);
        $text = preg_replace('/\b(?:kamera\s+termal\s+(?:inframerah\s+)?FLIR|FLIR)\b/i', 'kamera termal inframerah', $text);
        $text = preg_replace('/\s*[|·,;\/-]?\s*\b(?:Tahan\s+(?:Air|Debu)(?:\s*(?:&|dan)\s*Debu)?\s*)?IP\s?(?:54|67|68)\b(?:\s*[^\n|]*)?/i', '', $text);
        $text = str_ireplace(['/#produk', '/#kontak'], ['/produk/', '/kontak/#hubungi-form'], $text);

        if (!$allowSni) {
            $text = preg_replace('/\s*[|·,;\/&]?\s*\bSNI\s*9199:2023\b\s*/i', ' ', $text);
        }
        if (!$allowTkdn) {
            $text = preg_replace('/\s*[|·,;\/&]?\s*\b(?:sertifikat\s+|bersertifikat\s+)?TKDN(?:\s*\+\s*BMP)?(?:\s*60[,.]74%)?\b\s*/i', ' ', $text);
        }

        $text = preg_replace('/^[|\s·\/,;]+|[|\s·\/,;]+$/u', '', $text);
        $text = preg_replace('/\s{2,}/', ' ', (string) $text);

        return trim((string) $text);
    }
}

if (!function_exists('fds_sort_drone_posts')) {
    /**
     * Mengurutkan post CPT drone secara logis (FERTO 5 -> 10 -> 15 -> 22 -> 30 -> 50 -> DELTAV -> MULTIPURPOSE -> DELFRO).
     */
    function fds_sort_drone_posts(array $posts): array {
        $priority = [
            'ferto-5l'     => 10,
            'ferto-5'      => 10,
            'ferto-10l'    => 20,
            'ferto-10'     => 20,
            'ferto-15l'    => 30,
            'ferto-15'     => 30,
            'ferto-22l'    => 40,
            'ferto-22'     => 40,
            'ferto-30l'    => 50,
            'ferto-30'     => 50,
            'ferto-50l'    => 60,
            'ferto-50'     => 60,
            'deltav'       => 70,
            'multipurpose' => 80,
            'delfro'       => 90,
        ];

        usort($posts, function ($a, $b) use ($priority) {
            $sa = is_object($a) ? strtolower((string) $a->post_name) : '';
            $sb = is_object($b) ? strtolower((string) $b->post_name) : '';
            $wa = $priority[$sa] ?? 500;
            $wb = $priority[$sb] ?? 500;
            if ($wa === $wb) {
                return strcmp($sa, $sb);
            }
            return $wa <=> $wb;
        });

        return array_values($posts);
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


