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

// 1. HELPER DEFAULT CARDS (Single Source of Truth dari 6 Sektor Layanan)
function fds_get_default_solusi_cards() {
    if (function_exists(__NAMESPACE__ . '\\fds_get_layanan_sectors')) {
        $cards = [];
        foreach (fds_get_layanan_sectors() as $slug => $sector) {
            $cards[] = [
                'num'       => $sector['num'] ?? '',
                'slug'      => $slug,
                'image'     => $sector['image'] ?? '',
                'image_alt' => $sector['image_alt'] ?? ($sector['card_title'] ?? ''),
                'title'     => $sector['card_title'] ?? '',
                'desc'      => $sector['card_desc'] ?? '',
                'tags'      => $sector['card_tags'] ?? [],
                'tag'       => $sector['home_tag'] ?? ($sector['card_title'] ?? ''),
                'link_text' => 'Pelajari solusi sektor',
                'link_url'  => $sector['url'] ?? home_url('/layanan/' . $slug . '/'),
            ];
        }
        if (!empty($cards)) {
            return $cards;
        }
    }

    $theme_img = get_template_directory_uri() . '/public/images/sectors';

    return [
        [
            'num'       => '01',
            'slug'      => 'pertambangan',
            'image'     => $theme_img . '/pertambangan.webp',
            'image_alt' => 'Pemetaan area pertambangan dan survei topografi tambang menggunakan drone UAV FDS',
            'title'     => 'Pertambangan',
            'desc'      => 'Solusi drone mapping, survei topografi, pengukuran volume, dan monitoring progres tambang untuk mendukung produktivitas serta efisiensi operasional.',
            'tags'      => [['label' => 'Mining Survey', 'url' => ''], ['label' => 'Stockpile', 'url' => ''], ['label' => 'LiDAR', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')]],
            'tag'       => 'Pertambangan • LiDAR',
            'link_text' => 'Pelajari solusi sektor',
            'link_url'  => home_url('/layanan/pertambangan/'),
        ],
        [
            'num'       => '02',
            'slug'      => 'perkebunan-pertanian',
            'image'     => $theme_img . '/perkebunan-pertanian.webp',
            'image_alt' => 'Penerapan drone pertanian presisi, penyemprotan sprayer, dan pemantauan blok perkebunan oleh FDS',
            'title'     => 'Perkebunan dan Pertanian',
            'desc'      => 'Teknologi drone monitoring, multispectral imaging, dan smart spraying untuk meningkatkan produktivitas serta efisiensi pengelolaan lahan.',
            'tags'      => [['label' => 'Precision Agriculture', 'url' => ''], ['label' => 'NDVI', 'url' => home_url('/layanan/jasa-multispectral-hyperspectral/')]],
            'tag'       => 'Smart Plantation • Sprayer',
            'link_text' => 'Pelajari solusi sektor',
            'link_url'  => home_url('/layanan/perkebunan-pertanian/'),
        ],
        [
            'num'       => '03',
            'slug'      => 'kehutanan-lingkungan',
            'image'     => $theme_img . '/kehutanan-lingkungan.webp',
            'image_alt' => 'Survei drone kehutanan, penilaian stok karbon, dan pemantauan lingkungan hutan di Indonesia',
            'title'     => 'Kehutanan dan Lingkungan',
            'desc'      => 'Pemetaan hutan, estimasi karbon, pemantauan keanekaragaman hayati, serta analisis dampak lingkungan menggunakan drone dan sensor geospasial.',
            'tags'      => [['label' => 'Forestry', 'url' => ''], ['label' => 'Carbon Stock', 'url' => ''], ['label' => 'LiDAR', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')]],
            'tag'       => 'Forestry • Carbon & ESG',
            'link_text' => 'Pelajari solusi sektor',
            'link_url'  => home_url('/layanan/kehutanan-lingkungan/'),
        ],
        [
            'num'       => '04',
            'slug'      => 'energi-infrastruktur',
            'image'     => $theme_img . '/energi-infrastruktur.webp',
            'image_alt' => 'Inspeksi udara infrastruktur jaringan energi, panel surya, dan pemetaan koridor menggunakan drone FDS',
            'title'     => 'Energi dan Infrastruktur',
            'desc'      => 'Layanan inspeksi udara dan pemetaan drone untuk jaringan listrik, pembangkit energi, jalur pipa, serta berbagai aset infrastruktur.',
            'tags'      => [['label' => 'Asset Inspection', 'url' => ''], ['label' => 'Thermal Inspection', 'url' => '']],
            'tag'       => 'Inspeksi Aset • Koridor',
            'link_text' => 'Pelajari solusi sektor',
            'link_url'  => home_url('/layanan/energi-infrastruktur/'),
        ],
        [
            'num'       => '05',
            'slug'      => 'perencanaan',
            'image'     => $theme_img . '/perencanaan.webp',
            'image_alt' => 'Pemodelan geospasial dan survei topografi drone untuk perencanaan wilayah dan konstruksi',
            'title'     => 'Perencanaan',
            'desc'      => 'Penyediaan data geospasial resolusi tinggi untuk mendukung perencanaan tata ruang, pengembangan wilayah, konstruksi, dan masterplan.',
            'tags'      => [['label' => 'Urban Planning', 'url' => ''], ['label' => 'Geospatial Mapping', 'url' => home_url('/layanan/jasa-foto-udara-lidar/')]],
            'tag'       => 'Tata Ruang • Masterplan',
            'link_text' => 'Pelajari solusi sektor',
            'link_url'  => home_url('/layanan/perencanaan/'),
        ],
        [
            'num'       => '06',
            'slug'      => 'bencana-lingkungan',
            'image'     => $theme_img . '/bencana-lingkungan.webp',
            'image_alt' => 'Survei udara drone dan pemetaan area terdampak bencana serta evaluasi risiko lingkungan oleh FDS',
            'title'     => 'Bencana dan Lingkungan',
            'desc'      => 'Pemetaan area terdampak bencana, monitoring perubahan lahan, pemantauan garis pantai, serta evaluasi risiko lingkungan berbasis drone.',
            'tags'      => [['label' => 'Disaster Mapping', 'url' => ''], ['label' => 'Monitoring Lingkungan', 'url' => '']],
            'tag'       => 'Mitigasi • Tanggap Darurat',
            'link_text' => 'Pelajari solusi sektor',
            'link_url'  => home_url('/layanan/bencana-lingkungan/'),
        ],
    ];
}

// 2. HELPER DATA FRONTEND
function fds_get_solusi_data() {
    $title_customized = (bool) get_option('fds_solusi_title_customized', false);
    $cta_customized   = (bool) get_option('fds_solusi_cta_customized', false);
    $cards_customized = (bool) get_option('fds_solusi_cards_customized', false);

    $section_badge = get_option('fds_solusi_badge', 'Solusi Industri FDS');
    $section_title = get_option('fds_solusi_title', 'Satu Platform UAV. Enam Sektor Kerja Strategis.');
    if (!$title_customized && $section_title === 'Satu Platform UAV. 4 Sektor Kerja Strategis.') {
        $section_title = 'Satu Platform UAV. Enam Sektor Kerja Strategis.';
    }
    $section_desc  = get_option('fds_solusi_desc', 'Integrasi platform drone, sensor pemetaan dan inspeksi, serta pengolahan data geospasial untuk mendukung keputusan operasional lintas sektor.');

    if ($cta_customized) {
        $section_cta_text = (string) get_option('fds_solusi_cta_text', 'Lihat semua layanan');
        $section_cta_url  = (string) get_option('fds_solusi_cta_url', home_url('/layanan/'));
    } else {
        $raw_cta_text     = get_option('fds_solusi_cta_text', null);
        $raw_cta_url      = trim((string) get_option('fds_solusi_cta_url', ''));
        $section_cta_text = ($raw_cta_text !== null && trim((string) $raw_cta_text) !== '') ? (string) $raw_cta_text : 'Lihat semua layanan';
        $section_cta_url  = ($raw_cta_url !== '' && $raw_cta_url !== '#') ? $raw_cta_url : home_url('/layanan/');
    }

    $saved_cards  = get_option('fds_solusi_cards', null);
    $sectors_map  = function_exists(__NAMESPACE__ . '\\fds_get_layanan_sectors') ? fds_get_layanan_sectors() : [];

    // Deteksi apakah opsi DB masih berisi kartu legacy (#produk / #kontak / #solusi) sebelum admin menyimpan kustomisasi
    if ($cards_customized && is_array($saved_cards)) {
        $use_unified_sectors = false;
    } else {
        $use_unified_sectors = ($saved_cards === null || !is_array($saved_cards) || empty($saved_cards));
        if (!$use_unified_sectors) {
            $has_modern_layanan_link = false;
            $all_legacy_anchors      = true;
            foreach ($saved_cards as $sc) {
                $u = trim((string) ($sc['link_url'] ?? ''));
                if (stripos($u, '/layanan/') !== false) {
                    $has_modern_layanan_link = true;
                }
                if ($u !== '' && !str_starts_with($u, '#') && !preg_match('#/\#(produk|kontak|solusi|layanan)$#i', $u)) {
                    $all_legacy_anchors = false;
                }
            }
            if (!$has_modern_layanan_link && $all_legacy_anchors) {
                $use_unified_sectors = true;
            }
        }
    }

    if ($use_unified_sectors) {
        $cards = fds_get_default_solusi_cards();
    } else {
        $cards = $saved_cards;
    }

    $normalized_cards = [];
    if (is_array($cards)) {
        foreach ($cards as $idx => $c) {
            $card_title = trim((string) ($c['title'] ?? ''));
            $card_desc  = trim((string) ($c['desc'] ?? ''));
            if ($card_title === '' && $card_desc === '') {
                continue; // Skip kartu yang sengaja dikosongkan oleh admin
            }

            $tag = $c['tag'] ?? '';
            if (is_string($tag) && strpos($tag, 'Warning</b>') !== false) {
                $tag = '';
            }

            $link_url = trim((string) ($c['link_url'] ?? ''));
            if (!$cards_customized && ($link_url === '' || $link_url === '#kontak')) {
                $link_url = home_url('/layanan/');
            }

            // Sinkronkan tags & num dari sektor apabila URL mengarah ke /layanan/{slug}/ atau slug tersimpan
            $slug = (string) ($c['slug'] ?? '');
            if ($slug === '' && $link_url !== '' && preg_match('#/layanan/([a-z0-9-]+)/?#i', $link_url, $sm)) {
                if (isset($sectors_map[$sm[1]])) {
                    $slug = $sm[1];
                }
            }
            $matched_sector = ($slug !== '' && isset($sectors_map[$slug])) ? $sectors_map[$slug] : null;

            $num = array_key_exists('num', $c)
                ? (string) $c['num']
                : ($matched_sector['num'] ?? str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT));
            $tags = !empty($c['tags']) && is_array($c['tags'])
                ? $c['tags']
                : ($matched_sector['card_tags'] ?? ($tag !== '' ? [['label' => $tag, 'url' => '']] : []));

            $raw_alt   = trim((string) ($c['image_alt'] ?? ''));
            $image_alt = $raw_alt !== '' ? $raw_alt : ($matched_sector['image_alt'] ?? $card_title);

            $raw_link_text = trim((string) ($c['link_text'] ?? ''));
            $link_text     = $raw_link_text !== '' ? $raw_link_text : ($cards_customized ? '' : 'Pelajari solusi sektor');

            $normalized_cards[] = [
                'num'       => $num,
                'slug'      => $slug,
                'image'     => (string) ($c['image'] ?? ($matched_sector['image'] ?? '')),
                'image_alt' => $image_alt,
                'title'     => $card_title,
                'desc'      => $card_desc,
                'tags'      => $tags,
                'tag'       => (string) $tag,
                'link_text' => $link_text,
                'link_url'  => $link_url,
            ];
        }
    }

    // Decode HTML entities berulang (&amp;amp; → &amp; → &, dll)
    $fds_deep_decode = function ($str) {
        if (!is_string($str)) return $str;
        if (function_exists('fds_clean_string')) {
            return \fds_clean_string($str);
        }
        $prev = '';
        while ($prev !== $str) {
            $prev = $str;
            $str = wp_specialchars_decode($str, ENT_QUOTES);
            $str = html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $str;
    };

    foreach ($normalized_cards as &$nc) {
        foreach ($nc as $key => &$val) {
            if (is_string($val)) {
                $val = $fds_deep_decode($val);
            } elseif ($key === 'tags' && is_array($val)) {
                foreach ($val as &$t_item) {
                    if (is_array($t_item) && isset($t_item['label'])) {
                        $t_item['label'] = $fds_deep_decode((string) $t_item['label']);
                    } elseif (is_string($t_item)) {
                        $t_item = $fds_deep_decode($t_item);
                    }
                }
                unset($t_item);
            }
        }
        unset($val);
    }
    unset($nc);

    return [
        'badge'    => $fds_deep_decode($section_badge),
        'title'    => $fds_deep_decode($section_title),
        'desc'     => $fds_deep_decode($section_desc),
        'cta_text' => $fds_deep_decode($section_cta_text),
        'cta_url'  => $fds_deep_decode($section_cta_url),
        'cards'    => $normalized_cards,
    ];
}
