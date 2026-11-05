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
            'title'     => 'Produsen Drone Pertanian FERTO (TKDN 60,74%)',
            'desc'      => 'Solusi drone pertanian sprayer & granule spreader pupuk seri FERTO 5L–50L berstandar resmi TKDN 60,74% dan SNI 9199:2023. Menghemat bahan kimia >50% dengan radar terrain-following dan pemantauan kesehatan tanaman NDVI 10x lebih cepat.',
            'tag'       => 'FERTO 5L – 50L (TKDN)',
            'link_text' => 'Lihat Seri FERTO',
            'link_url'  => '#produk',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Jasa Foto Udara & Survey LiDAR Akurasi Tinggi',
            'desc'      => 'Layanan akuisisi foto udara resolusi tinggi (GSD 1-3 cm) dan survey drone LiDAR dengan penetrasi kanopi vegetasi menggunakan UAV Fixed-Wing Hybrid VTOL DELTAV (jangkauan 60 km). Menghasilkan ortomosaik tegak, point cloud rapat, dan Digital Terrain Model (DTM/DSM).',
            'tag'       => 'Foto Udara & LiDAR',
            'link_text' => 'Konsultasi Foto Udara & LiDAR',
            'link_url'  => '#kontak',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Jasa Konsultan GIS & Pemodelan Spasial 3D',
            'desc'      => 'Konsultan Sistem Informasi Geografis profesional untuk analisis spasial hidrologi DAS, tata ruang wilayah RTRW/RDTR, pemetaan batas kadastral HGU perkebunan & kehutanan, serta perancangan dashboard WebGIS enterprise interaktif terintegrasi.',
            'tag'       => 'Konsultan GIS',
            'link_text' => 'Konsultasi Layanan GIS',
            'link_url'  => '#kontak',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Pemetaan Kawasan KEK, Tambang, Kehutanan & Industri',
            'desc'      => 'Survei terintegrasi untuk kawasan strategis: masterplan Kawasan Ekonomi Khusus (KEK), perhitungan volume stockpile serta cut and fill tambang (akurasi ±2.35%), inventarisasi biomassa dan karbon hutan, serta inspeksi utilitas kawasan industri.',
            'tag'       => 'KEK, Tambang & Industri',
            'link_text' => 'Pelajari Pemetaan Kawasan',
            'link_url'  => '#kontak',
        ],
        [
            'image'     => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
            'title'     => 'Drone Logistik Kargo DELFRO & Surveillance Termal',
            'desc'      => 'Distribusi kargo logistik cepat 10–20 kg ke area terisolir dengan DELFRO. Serta misi surveillance malam hari, patroli keamanan objek vital, mitigasi karhutla, dan tanggap darurat bencana (SAR) dengan sensor termal inframerah FLIR.',
            'tag'       => 'Logistik & Surveillance',
            'link_text' => 'Pelajari Solusi UAV',
            'link_url'  => '#kontak',
        ],
    ];
}

// 2. HELPER DATA FRONTEND
function fds_get_solusi_data() {
    $section_badge = get_option('fds_solusi_badge', 'Solusi Industri FDS');
    $section_title = get_option('fds_solusi_title', 'Satu platform. Berbagai industri strategis.');
    $section_desc  = get_option('fds_solusi_desc', 'Solusi rekayasa UAV terintegrasi hardware, software FDS STATION, sensor AI, dan layanan operasional bersertifikasi untuk efisiensi maksimal di lapangan.');
    
    $saved_cards = get_option('fds_solusi_cards', null);
    if ($saved_cards === null || !is_array($saved_cards) || empty($saved_cards)) {
        $cards = fds_get_default_solusi_cards();
    } else {
        $cards = $saved_cards;
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
