<?php

declare(strict_types=1);

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS ENTERPRISE SECURITY HARDENING MODULE (OWASP 2021-2026 & BURP SHIELD)
 * =========================================================================
 * Comprehensive security controls covering:
 * - OWASP Web Top 10 (2021) & API Security Top 10 (2023)
 * - HTTP Security Headers (X-Frame, HSTS, Nosniff, Referrer, Permissions)
 * - Information Leakage Mitigation (WP Generator, REST User Enumeration)
 * - Anti-Spoofing IP Resolution & Anti-CSV Formula Injection
 * - LiteSpeed Cache Dynamic AJAX No-Cache Protection
 */

// 1. INJEKSI HTTP SECURITY HEADERS TERPUSAT
add_action('send_headers', function (): void {
    if (headers_sent()) {
        return;
    }

    // Clickjacking Defense
    header('X-Frame-Options: SAMEORIGIN');

    // MIME-Type Sniffing Defense
    header('X-Content-Type-Options: nosniff');

    // Referrer Information Minimization
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Permissions & Hardware Access Policy
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

    // HSTS (HTTP Strict Transport Security) jika berjalan di HTTPS
    if (is_ssl()) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
});

// 2. PROTEKSI DYNAMIC AJAX AGAR TIDAK DI-CACHE OLEH LITESPEED / REVERSE PROXY
add_action('admin_init', function (): void {
    if (wp_doing_ajax()) {
        $action = sanitize_text_field($_REQUEST['action'] ?? '');
        $protected_actions = ['fds_submit_inquiry', 'fds_download_brochure'];

        if (in_array($action, $protected_actions, true)) {
            nocache_headers();
            if (!headers_sent()) {
                header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
                header('Pragma: no-cache');
                header('Expires: 0');
                header('X-LiteSpeed-Cache-Control: no-cache');
            }
        }
    }
});

// 3. HAPUS JEJAK VERSI WORDPRESS & GENERATOR (OWASP A05:2021)
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

// Hapus parameter versi ?ver= dari skrip & style publik untuk mencegah passive reconnaissance
add_filter('style_loader_src', __NAMESPACE__ . '\\fds_remove_wp_version_strings', 9999);
add_filter('script_loader_src', __NAMESPACE__ . '\\fds_remove_wp_version_strings', 9999);

function fds_remove_wp_version_strings(string $src): string {
    if (strpos($src, 'ver=' . get_bloginfo('version')) !== false) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

// 4. BLOKIR REST API USER ENUMERATION UNTUK NON-ADMIN / TAMU (OWASP API8:2023)
// Mencegah penyerang mendeteksi daftar username admin via /wp-json/wp/v2/users
add_filter('rest_endpoints', function (array $endpoints): array {
    if (!current_user_can('manage_options')) {
        if (isset($endpoints['/wp/v2/users'])) {
            unset($endpoints['/wp/v2/users']);
        }
        if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        }
    }
    return $endpoints;
});

// Blokir penjelajahan author scan via query string (?author=1)
add_action('template_redirect', function (): void {
    if (!is_admin() && is_author()) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
});

// 5. HELPER ANTI-SPOOFING IP RESOLUTION (BURP SUITE SHIELD)
// Mengutamakan REMOTE_ADDR agar attacker tidak bisa bypass rate limit via header X-Forwarded-For palsu
function fds_get_client_ip(): string {
    // Gunakan REMOTE_ADDR sebagai nilai paling terpercaya dari socket webserver
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';

    // Jika berjalan di balik Cloudflare terverifikasi
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    $clean_ip = filter_var($ip, FILTER_VALIDATE_IP);
    return $clean_ip !== false ? $clean_ip : '127.0.0.1';
}

// 6. HELPER ANTI-CSV FORMULA INJECTION (OWASP A03/A08:2021)
// Prepend tanda kutip ' jika cell teks diawali karakter formula spreadsheet
function fds_sanitize_csv_cell(mixed $value): string {
    if (!is_scalar($value) && $value !== null) {
        return '';
    }

    $str = (string) $value;
    $str = wp_strip_all_tags($str);
    $str = trim($str);

    // Deteksi awalan formula berbahaya (=, +, -, @, \t, \r)
    if ($str !== '' && in_array($str[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
        return "'" . $str;
    }

    return $str;
}

// 7. HELPER PERLINDUNGAN PATH TRAVERSAL
function fds_safe_filename(string $filename): string {
    $clean = sanitize_file_name($filename);
    $clean = str_replace(['../', '..\\', '%00'], '', $clean);
    return $clean;
}
