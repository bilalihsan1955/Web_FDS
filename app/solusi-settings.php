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
            'title'     => 'Drone Pertanian Presisi & Analisis NDVI',
            'desc'      => 'Solusi drone pertanian sprayer & spreader pupuk seri FERTO 5L–50L berstandar resmi TKDN 60,74% dan SNI 9199:2023. Menghemat bahan kimia >50% dengan radar terrain-following dan pemantauan kesehatan tanaman NDVI 10x lebih cepat.',
            'tag'       => 'FERTO 5L – 50L (TKDN)',
            'link_text' => 'Lihat Seri FERTO',
            'link_url'  => '#produk',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Jasa Pemetaan Drone, Survey LiDAR & Tambang',
            'desc'      => 'Layanan drone mapping dan survey drone LiDAR untuk pemetaan GIS dan foto udara area luas dengan Fixed-Wing Hybrid VTOL DELTAV (jangkauan 60 km). Menghasilkan ortomozaik sub-sentimeter, model 3D DSM/DTM, dan kalkulasi volume cut & fill tambang (akurasi ±2.35%).',
            'tag'       => 'DELTAV (VTOL 60 km)',
            'link_text' => 'Konsultasi Pemetaan',
            'link_url'  => '#kontak',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Inspeksi Industri & Infrastruktur 150kV',
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
            'title'     => 'Drone Pencarian Korban Bencana & Misi SAR',
            'desc'      => 'Patroli otonom dan tanggap darurat bencana dengan sensor kamera termal inframerah serta transmisi video real-time untuk pencarian korban bencana (SAR), pemantauan banjir, dan deteksi dini titik api karhutla.',
            'tag'       => 'SAR & SURVEILLANCE',
            'link_text' => 'Pelajari Solusi',
            'link_url'  => '#kontak',
        ],
    ];
}

// Auto-sync anti-slop cards to database (Run once)
add_action('init', function () {
    if (!get_option('fds_solusi_cards_antislop_v1')) {
        $cards = get_option('fds_solusi_cards', null);
        if ($cards === null || (is_array($cards) && (count($cards) < 5 || (isset($cards[0]['title']) && strpos($cards[0]['title'], 'Drone Pertanian') !== false)))) {
            update_option('fds_solusi_cards', fds_get_default_solusi_cards());
        }
        update_option('fds_solusi_cards_antislop_v1', 1);
    }
});

// 2. HELPER DATA FRONTEND
function fds_get_solusi_data() {
    $section_badge = get_option('fds_solusi_badge', 'Solusi Berdasarkan Industri');
    $section_title = get_option('fds_solusi_title', 'Satu Platform UAV. 4 Sektor Kerja Strategis.');
    $section_desc  = get_option('fds_solusi_desc', 'Dirancang khusus mengatasi kondisi medan terberat di Indonesia: dari perkebunan sawit berbukit, tambang terbuka, hingga area bencana terisolasi.');
    
    $saved_cards = get_option('fds_solusi_cards', null);
    if ($saved_cards === null) {
        $cards = fds_get_default_solusi_cards();
    } else {
        $cards = is_array($saved_cards) ? $saved_cards : [];
    }

    $normalized_cards = [];
    if (is_array($cards)) {
        foreach ($cards as $c) {
            $card_title = trim($c['title'] ?? '');
            $card_desc  = trim($c['desc'] ?? '');
            if ($card_title === '' && $card_desc === '') {
                continue; // Skip kartu yang sengaja dikosongkan oleh admin
            }

            $tag = $c['tag'] ?? '';
            if (strpos($tag, 'Warning</b>') !== false) {
                $tag = '';
            }
            $normalized_cards[] = [
                'image'     => $c['image'] ?? '',
                'title'     => $card_title,
                'desc'      => $card_desc,
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
        'badge' => $fds_deep_decode($section_badge),
        'title' => $fds_deep_decode($section_title),
        'desc'  => $fds_deep_decode($section_desc),
        'cards' => $normalized_cards,
    ];
}
