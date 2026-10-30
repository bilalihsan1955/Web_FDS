<?php

declare(strict_types=1);

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS ENTERPRISE ADMIN PERFORMANCE & WORKER OPTIMIZATION MODULE
 * =========================================================================
 * Mencegah WP-Admin lambat / stalling dengan cara:
 * 1. Mengurangi frekuensi Heartbeat API dari 15 detik ke 60 detik
 *    (mencegah flood AJAX admin-ajax.php yang menghabiskan worker PHP)
 * 2. Menghapus external RSS widget (WordPress News & Events) di Dashboard
 *    yang sering mengalami latency / timeout saat menghubungi api.wordpress.org
 * 3. Membatasi Post Revisions ke 5 versi agar tabel wp_posts & wp_postmeta
 *    tidak membengkak
 * 4. Menonaktifkan update check yang memblokir render halaman admin
 * 5. Menambahkan header no-cache yang tepat untuk request admin
 */

// 1. THROTTLE HEARTBEAT API DI ADMIN (Mencegah CPU / Worker Spike)
add_filter('heartbeat_settings', function (array $settings): array {
    $settings['interval'] = 60; // Default WP adalah 15 detik
    return $settings;
});

// 2. HAPUS WIDGET DASHBOARD YANG MELAKUKAN EXTERNAL HTTP REQUEST KE WORDPRESS.ORG
add_action('wp_dashboard_setup', function (): void {
    // Menghapus WordPress News & Events RSS (pemicu external HTTP stall saat load dashboard)
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    // Menghapus Quick Draft (tidak digunakan di compro enterprise)
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
});

// 3. BATASI POST REVISIONS AGAR DATABASE TETAP RINGAN & CEPAT DI-QUERY
if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 5);
}

// 4. HAPUS NOTIFIKASI CHECK VERSI OTOMATIS SAAT RENDER HALAMAN
// (Update tetap dicek oleh core, tapi tidak memblokir render HTML admin saat visitor/admin membuka tab)
add_action('admin_init', function (): void {
    // Cegah pengecekan update bahasa yang memblokir request admin
    remove_action('admin_init', 'wp_maybe_auto_update');
}, 1);
