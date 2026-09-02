<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS SOLUSI INDUSTRI HELPERS & DATA PROVIDER
 * =========================================================================
 * Menyediakan data default dan getter data kartu solusi industri beranda.
 * Pengaturan UI terpadu di panel "Konten Beranda" (Tab 3: Solusi Industri).
 */

// 1. HELPER DEFAULT CARDS
function fds_get_default_solusi_cards() {
    return [
        [
            'image'     => 'https://images.unsplash.com/photo-1527011046414-4781f1f94f8c?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Penyemprotan & Analisis NDVI',
            'desc'      => 'Penyemprotan >50% lebih efisien bahan kimia dengan radar terrain-following kontur tanah untuk seri FERTO 5L–50L. Pemantauan kesehatan tanaman 10x lebih cepat (30–40 Ha/jam) dengan kamera multispektral NDVI.',
            'tag'       => 'FERTO 5L – 50L',
            'link_text' => 'Lihat Seri FERTO',
            'link_url'  => '#produk',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Pemetaan Udara & Topografi 3D',
            'desc'      => 'Menghemat waktu survei 70–80% untuk area luas dengan Fixed-Wing Hybrid VTOL DELTAV (jangkauan 60 km). Menghasilkan ortomozaik sub-sentimeter, model 3D DSM/DTM, kalkulasi volume cut & fill (akurasi ±2.35%), dan data siap CAD/BIM.',
            'tag'       => 'DELTAV (60 km)',
            'link_text' => 'Konsultasi Pemetaan',
            'link_url'  => '#kontak',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Inspeksi Industri & Infrastruktur',
            'desc'      => 'Inspeksi aset secara efisien dan aman tanpa shutdown operasional (zero downtime), serta bebas risiko bekerja di ketinggian. Didukung sensor optik high-zoom, kamera termal inframerah, dan analitik AI untuk deteksi dini anomali serta pemeliharaan preventif.',
            'tag'       => 'MULTIPURPOSE UAV',
            'link_text' => 'Konsultasi Solusi Inspeksi',
            'link_url'  => '#kontak',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Distribusi Kargo & Sebar Biji (Seedball)',
            'desc'      => 'Distribusi kargo logistik cepat 3–10 kg ke area terisolir dengan DELFRO. Serta misi penaburan benih seedball otonom berkapasitas 20 kg dengan REBO untuk restorasi hutan dan reklamasi tambang 80% lebih cepat dibanding survei darat.',
            'tag'       => 'DELFRO & REBO',
            'link_text' => 'Pelajari Produk',
            'link_url'  => '#produk',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Pemantauan Karhutla & Tanggap Bencana',
            'desc'      => 'Patroli otonom jangkauan jauh dengan sensor termal inframerah dan transmisi video real-time untuk deteksi dini titik api karhutla, monitoring banjir, serta asesmen cepat pasca-bencana alam.',
            'tag'       => 'SURVEILLANCE UAV',
            'link_text' => 'Pelajari Solusi',
            'link_url'  => '#kontak',
        ],
    ];
}

// Auto-sync 5th card to database if fewer than 5 cards exist
add_action('init', function () {
    $cards = get_option('fds_solusi_cards', null);
    if ($cards !== null && is_array($cards) && count($cards) < 5) {
        $cards[] = [
            'image'     => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Pemantauan Karhutla & Tanggap Bencana',
            'desc'      => 'Patroli otonom jangkauan jauh dengan sensor termal inframerah dan transmisi video real-time untuk deteksi dini titik api karhutla, monitoring banjir, serta asesmen cepat pasca-bencana alam.',
            'tag'       => 'SURVEILLANCE UAV',
            'link_text' => 'Pelajari Solusi',
            'link_url'  => '#kontak',
        ];
        update_option('fds_solusi_cards', $cards);
    }
});

// 2. HELPER DATA FRONTEND
function fds_get_solusi_data() {
    $badge = get_option('fds_solusi_badge', 'Solusi Industri FDS');
    $title = get_option('fds_solusi_title', 'Satu platform. Berbagai industri strategis.');
    $desc  = get_option('fds_solusi_desc', 'Solusi rekayasa UAV terintegrasi hardware, software FDS STATION, sensor AI, dan layanan operasional bersertifikasi untuk efisiensi maksimal di lapangan.');
    
    $saved_cards = get_option('fds_solusi_cards', null);
    if ($saved_cards === null || !is_array($saved_cards) || empty($saved_cards)) {
        $cards = fds_get_default_solusi_cards();
    } else {
        $cards = $saved_cards;
    }

    $normalized_cards = [];
    if (is_array($cards)) {
        foreach ($cards as $c) {
            $tag = $c['tag'] ?? '';
            if (strpos($tag, 'Warning</b>') !== false) {
                $tag = '';
            }
            $normalized_cards[] = [
                'image'     => $c['image'] ?? '',
                'title'     => $c['title'] ?? '',
                'desc'      => $c['desc'] ?? '',
                'tag'       => $tag,
                'link_text' => $c['link_text'] ?? 'Pelajari Selengkapnya',
                'link_url'  => $c['link_url'] ?? '#kontak',
            ];
        }
    }

    // Decode HTML entities berulang (&amp;amp; → &amp; → &, dll)
    $fds_deep_decode = function($str) {
        if (!is_string($str)) return $str;
        $prev = '';
        while ($prev !== $str) {
            $prev = $str;
            $str = wp_specialchars_decode($str, ENT_QUOTES);
        }
        return $str;
    };

    foreach ($normalized_cards as &$nc) {
        foreach ($nc as $key => &$val) {
            $val = $fds_deep_decode($val);
        }
        unset($val);
    }
    unset($nc);

    return [
        'badge' => $fds_deep_decode($badge),
        'title' => $fds_deep_decode($title),
        'desc'  => $fds_deep_decode($desc),
        'cards' => $normalized_cards,
    ];
}
