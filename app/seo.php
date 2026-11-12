<?php

namespace App;

/**
 * FDS SEO & Schema.org Structured Data Engine
 * Enterprise-grade Technical, On-Page & Rich Snippets SEO
 * PT Karya Solusi Angkasa (Full Drone Solutions)
 *
 * Target Keywords:
 * - drone mapping
 * - jasa pemetaan drone
 * - jasa survey drone
 * - jasa drone foto udara
 * - jasa drone lidar
 * - jasa pemetaan drone tambang
 * - pemetaan GIS drone
 * - drone pencarian korban bencana
 * - drone pertanian
 * - drone dji agras (DJI Agras T40, T50, T25, T20P)
 * - produsen UAV Indonesia
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if external SEO plugin is managing meta tags
 */
function fds_has_external_seo_plugin() {
    return defined('RANK_MATH_VERSION')
        || class_exists('RankMath')
        || defined('WPSEO_VERSION')
        || class_exists('WPSEO_Options')
        || defined('AIOSEO_VERSION');
}

/**
 * Deep-decode nested HTML entities (&amp;amp; / &#038; -> &) cleanly
 */
function fds_decode_text($value): string {
    if (!is_string($value) || $value === '') {
        return '';
    }
    if (function_exists('fds_clean_string')) {
        $value = \fds_clean_string($value);
    } else {
        $prev = '';
        $max  = 5;
        while ($prev !== $value && $max-- > 0) {
            $prev  = $value;
            $value = html_entity_decode(wp_specialchars_decode($value, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }
    return trim((string) preg_replace('/\s+/u', ' ', wp_strip_all_tags($value)));
}

/**
 * Word-safe UTF-8 title clamp (<= $max chars, prioritizes model/lead keyword at char 0)
 */
function fds_clamp_meta_title(string $title, int $max = 60): string {
    $clean = fds_decode_text($title);
    if (mb_strlen($clean, 'UTF-8') <= $max) {
        return $clean;
    }
    $short_brand = str_replace(
        [' | Full Drone Solutions Indonesia', ' — Full Drone Solutions Indonesia', ' | Full Drone Solutions', ' — Full Drone Solutions'],
        [' | FDS', ' | FDS', ' | FDS', ' | FDS'],
        $clean
    );
    if (mb_strlen($short_brand, 'UTF-8') <= $max) {
        return $short_brand;
    }
    $has_suffix = str_ends_with($short_brand, ' | FDS');
    $core       = $has_suffix ? substr($short_brand, 0, -6) : $short_brand;
    $limit      = $has_suffix ? ($max - 6) : $max;
    $cut        = mb_substr($core, 0, $limit, 'UTF-8');
    $last_space = mb_strrpos($cut, ' ', 0, 'UTF-8');
    if ($last_space !== false && $last_space > (int) ($limit * 0.5)) {
        $cut = mb_substr($cut, 0, $last_space, 'UTF-8');
    }
    $cut = rtrim($cut, " \t\n\r\0\x0B-|—–:,");
    return $has_suffix ? ($cut . ' | FDS') : $cut;
}

/**
 * Word-safe UTF-8 meta description clamp (<= $max chars)
 */
function fds_clamp_meta_desc(string $desc, int $max = 158): string {
    $clean = fds_decode_text($desc);
    if (mb_strlen($clean, 'UTF-8') <= $max) {
        return $clean;
    }
    $cut = mb_substr($clean, 0, $max - 1, 'UTF-8');
    $last_space = mb_strrpos($cut, ' ', 0, 'UTF-8');
    if ($last_space !== false && $last_space > 90) {
        $cut = mb_substr($cut, 0, $last_space, 'UTF-8');
    }
    return rtrim($cut, " \t\n\r\0\x0B.,;:-") . '…';
}

/**
 * Master List of Categorized Default Drone Keywords (Indonesia High-Intent B2B SEO)
 * Benchmarked against: Halo Robotics, TechnoGIS, Tera Drone, Frogs Indonesia, Beta-UAS, Skytech, DJI Enterprise/Agriculture, ANX UAV
 */
function fds_get_default_drone_keywords_grouped() {
    return [
        // PILAR 1: PRODUSEN DRONE PERTANIAN, PEMETAAN, LOGISTIK & SURVEILLANCE (MANUFAKTUR UAV)
        'pilar_produsen_drone' => [
            'produsen drone pertanian',
            'produsen drone pemetaan',
            'produsen drone logistik',
            'produsen drone surveillance',
            'produsen UAV Indonesia',
            'manufaktur drone indonesia',
            'pabrik drone indonesia',
            'pabrik drone di Indonesia',
            'manufaktur drone lokal Indonesia',
            'drone buatan indonesia',
            'drone tkdn 60,74%',
            'drone sni 9199:2023',
            'pt karya solusi angkasa',
            'full drone solutions',
            'drone sprayer ferto',
            'drone ferto 50l',
            'drone ferto 30l',
            'drone ferto 22l',
            'drone ferto 15l',
            'drone ferto 10l',
            'drone ferto 5l',
            'drone pertanian 50 liter',
            'drone semprot pupuk cair',
            'drone sebar benih padi dan pupuk granul',
            'drone perkebunan kelapa sawit',
            'drone fixed wing vtol indonesia',
            'drone vtol deltav',
            'uav vtol pemetaan jarak jauh',
            'drone kargo logistik',
            'drone kargo delfro kapasitas besar',
            'drone logistik darurat medis',
            'drone payload besar indonesia',
            'drone surveillance pemantauan jarak jauh',
            'drone patroli karhutla dan keamanan',
            'drone multipurpose thermal inframerah',
            'alat semprot pertanian otomatis',
            'pengadaan drone e-katalog LKPP pemerintah BUMN',
            'pusat perakitan drone yogyakarta',
            'ground control station fds station bahasa indonesia',
        ],

        // PILAR 2: JASA FOTO UDARA DAN LIDAR
        'pilar_foto_udara_lidar' => [
            'jasa foto udara',
            'jasa drone foto udara',
            'foto udara resolusi tinggi',
            'foto udara ortofoto ortomosaik',
            'jasa lidar drone',
            'jasa survey lidar',
            'jasa survey lidar indonesia',
            'jasa drone lidar',
            'pemetaan lidar uav',
            'survey lidar akurasi sub-sentimeter',
            'point cloud lidar dtm dsm',
            'fotogrametri udara digital elevation model',
            'survey koridor lidar transmisi migas sutet',
            'pemetaan topografi akurasi tinggi',
            'jasa drone mapping rtk ppk',
            'jasa survey drone udara',
            'sewa drone pemetaan dan survey lidar',
        ],

        // PILAR 3: KONSULTAN GIS (SISTEM INFORMASI GEOGRAFIS)
        'pilar_konsultan_gis' => [
            'konsultan gis',
            'konsultan gis indonesia',
            'konsultan pemetaan gis',
            'jasa konsultan sig',
            'analisis spasial gis',
            'pemodelan topografi 3d gis',
            'webgis enterprise indonesia',
            'pembuatan peta tata ruang gis',
            'konsultan geospasial indonesia',
            'pemetaan kadastral dan batas wilayah',
            'pemetaan batas kawasan perkebunan dan kehutanan hgu',
            'integrasi data geospasial gis rtrw rdtr',
            'analisis hidrologi das geospasial',
            'digitalisasi aset lahan peta gis',
            'jasa pengolahan citra drone gis',
        ],

        // PILAR 4: PEMETAAN KAWASAN KEK, TAMBANG, KEHUTANAN & INDUSTRI
        'pilar_pemetaan_kawasan' => [
            'pemetaan kawasan kek',
            'pemetaan kawasan ekonomi khusus',
            'masterplan kawasan ekonomi khusus drone',
            'jasa pemetaan drone tambang',
            'survey drone tambang batubara nikel',
            'drone cut and fill tambang batubara nikel',
            'perhitungan volume stockpile tambang',
            'drone topografi tambang',
            'drone inspeksi tambang nikel batubara emas',
            'pemetaan kawasan kehutanan',
            'drone pemetaan kehutanan dan karbon',
            'drone mitigasi karhutla kehutanan',
            'inventarisasi tutupan lahan kehutanan',
            'pemetaan kawasan industri',
            'inspeksi kawasan industri 150kv',
            'pemetaan koridor utilitas kawasan industri',
            'survei topografi lahan kawasan industri terpadu',
            'pemetaan drone perkebunan kelapa sawit hgu',
        ],

        // GENERAL HEAD & BRAND ENTITIES
        'head_drone' => [
            'drone',
            'drone indonesia',
            'uav indonesia',
            'drone uav',
            'pesawat tanpa awak',
            'pesawat nirawak',
            'produsen drone',
            'produsen drone indonesia',
            'pabrik drone',
            'manufaktur drone indonesia',
            'jual drone indonesia',
            'harga drone',
            'perusahaan drone indonesia',
            'drone industrial b2b',
            'pelatihan pilot drone resmi DKPPU CASR 107',
            'pusat servis dan suku cadang drone indonesia',
        ],
    ];
}

/**
 * Get unified string of all keywords (Default + Custom from WP Admin)
 */
function fds_get_primary_keywords_string($context_keywords = '') {
    $grouped = fds_get_default_drone_keywords_grouped();
    $list = [];
    foreach ($grouped as $items) {
        $list = array_merge($list, $items);
    }

    if (!empty($context_keywords)) {
        if (is_array($context_keywords)) {
            $list = array_merge($context_keywords, $list);
        } else {
            $ctx_arr = array_map('trim', explode(',', $context_keywords));
            $list = array_merge($ctx_arr, $list);
        }
    }

    // Ambil kata kunci tambahan dari WP Admin (User Custom Keywords)
    $custom = get_option('fds_custom_keywords', '');
    if (!empty($custom)) {
        $custom_arr = array_map('trim', explode(',', $custom));
        $list = array_merge($list, $custom_arr);
    }

    $clean = [];
    foreach ($list as $item) {
        $item = trim($item);
        if (!empty($item) && !in_array(strtolower($item), array_map('strtolower', $clean), true)) {
            $clean[] = $item;
        }
    }

    return implode(', ', $clean);
}

/**
 * Official brand logo URL (prioritizes square/brand logo over landscape share banners)
 */
function fds_get_official_logo_url(): string {
    $nb = function_exists(__NAMESPACE__ . '\\fds_get_navbar_brand') ? fds_get_navbar_brand() : [];
    if (!empty($nb['logo_url']) && stripos((string) $nb['logo_url'], 'logo-fds-academy') === false) {
        return (string) $nb['logo_url'];
    }
    if (file_exists(get_template_directory() . '/public/images/fds-logo-512.png')) {
        return get_template_directory_uri() . '/public/images/fds-logo-512.png';
    }
    if (file_exists(get_template_directory() . '/public/images/fds-logo.png')) {
        return get_template_directory_uri() . '/public/images/fds-logo.png';
    }
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_src = wp_get_attachment_image_src($custom_logo_id, 'full');
        if (!empty($logo_src[0])) {
            return (string) $logo_src[0];
        }
    }
    return '';
}

/**
 * Verified social profile URLs for Organization.sameAs (only active & valid https URLs)
 */
function fds_get_verified_social_urls(): array {
    $urls = [];
    if (function_exists(__NAMESPACE__ . '\\fds_get_global_contact')) {
        $gc = fds_get_global_contact();
        $map = [
            'ig' => ['active' => 'instagram_active', 'url' => 'instagram'],
            'yt' => ['active' => 'youtube_active',   'url' => 'youtube'],
            'tt' => ['active' => 'tiktok_active',    'url' => 'tiktok'],
            'tw' => ['active' => 'twitter_active',   'url' => 'twitter'],
            'li' => ['active' => 'linkedin_active',  'url' => 'linkedin'],
        ];
        foreach ($map as $cfg) {
            $is_on = !empty($gc[$cfg['active']]);
            $u     = trim((string) ($gc[$cfg['url']] ?? ''));
            if ($is_on && filter_var($u, FILTER_VALIDATE_URL) && strpos($u, 'http') === 0) {
                $urls[] = esc_url_raw($u);
            }
        }
    }
    return array_values(array_unique($urls));
}

/**
 * Shared OpeningHoursSpecification matching /kontak/ operational schedule
 */
function fds_get_opening_hours_schema(): array {
    return [
        [
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens'     => '08:30',
            'closes'    => '17:00',
        ],
        [
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Saturday'],
            'opens'     => '08:30',
            'closes'    => '13:00',
        ],
    ];
}

/**
 * Get comprehensive SEO metadata for the current query/page
 */
function fds_get_seo_data() {
    global $post, $wp;

    $site_name   = get_bloginfo('name') ?: 'Full Drone Solutions';
    $site_desc   = get_bloginfo('description') ?: 'Produsen UAV No.1 Indonesia — Solusi Pertanian Presisi, Pemetaan GIS & Industri';
    $current_url = home_url(add_query_arg([], $wp->request));

    // Core high-intent keywords (Default enriched drone keywords + custom admin keywords)
    $primary_keywords = fds_get_primary_keywords_string();

    // Default fallback values
    $title       = $site_name . ' — ' . $site_desc;
    $description = 'PT Karya Solusi Angkasa (Full Drone Solutions), produsen drone pertanian seri FERTO (TKDN 60,74%), pemetaan VTOL DELTAV, kargo DELFRO, jasa foto udara LiDAR, dan konsultan GIS Indonesia.';
    $keywords    = $primary_keywords;
    $image_url   = '';
    $og_type     = 'website';
    $canonical   = trailingslashit($current_url);
    $breadcrumbs = [
        ['name' => 'Beranda', 'url' => home_url('/')]
    ];

    // 1. FRONT PAGE / HOMEPAGE
    if (is_front_page()) {
        $title       = 'Full Drone Solutions — Produsen Drone & UAV No.1 Indonesia';
        $description = 'PT Karya Solusi Angkasa (Full Drone Solutions), produsen UAV Indonesia No.1: seri FERTO TKDN 60,74%, pemetaan VTOL DELTAV, jasa LiDAR & GIS.';
        $keywords    = $primary_keywords;
        $canonical   = trailingslashit(home_url('/'));
        $image_url   = fds_get_default_share_image();
    }
    // 2. SINGLE DRONE CPT PAGE
    elseif (is_singular('drone') || get_query_var('drone')) {
        $drone_post = get_post();
        if ($drone_post) {
            $slug        = $drone_post->post_name;
            $model_key   = function_exists(__NAMESPACE__ . '\\fds_get_drone_model_key') ? fds_get_drone_model_key($drone_post->ID) : strtolower((string) $slug);
            $is_ferto    = (strpos($model_key, 'ferto-') === 0 || strpos(strtolower((string) $slug), 'ferto') !== false);
            $has_sni     = in_array($model_key, ['ferto-15l', 'ferto-22l'], true);

            $detail      = function_exists(__NAMESPACE__ . '\\fds_get_drone_detail_data') ? fds_get_drone_detail_data($drone_post->ID) : [];
            $d_title     = fds_decode_text((string) ($detail['name'] ?? get_the_title($drone_post->ID)));
            $model_base  = trim((string) preg_replace('/\s*\(.*?\)/', '', $d_title)) ?: $d_title;
            $tagline     = fds_decode_text((string) ($detail['tagline'] ?? get_post_meta($drone_post->ID, 'drone_tagline', true)));
            $kategori    = fds_decode_text((string) ($detail['kategori'] ?? (get_post_meta($drone_post->ID, 'drone_kategori', true) ?: 'UAV Industri')));
            $payload     = fds_decode_text((string) ($detail['card_payload'] ?? get_post_meta($drone_post->ID, 'drone_spec_kapasitas', true)));
            $durasi      = fds_decode_text((string) ($detail['card_metric_value'] ?? get_post_meta($drone_post->ID, 'drone_spec_durasi', true)));
            if (function_exists('fds_scrub_legacy_text')) {
                $d_title  = \fds_scrub_legacy_text($d_title, $is_ferto, $has_sni);
                $tagline  = \fds_scrub_legacy_text($tagline, $is_ferto, $has_sni);
                $kategori = \fds_scrub_legacy_text($kategori, $is_ferto, $has_sni);
                $payload  = \fds_scrub_legacy_text($payload, $is_ferto, $has_sni);
                $durasi   = \fds_scrub_legacy_text($durasi, $is_ferto, $has_sni);
            }
            $feat_img    = !empty($detail['hero_url']) ? (string) $detail['hero_url'] : get_the_post_thumbnail_url($drone_post->ID, 'full');

            $cat_lower   = strtolower($kategori);
            if ($is_ferto || strpos($cat_lower, 'agri') !== false) {
                $title = fds_clamp_meta_title($has_sni ? "{$model_base} — Drone Pertanian TKDN & SNI | FDS" : "{$model_base} — Drone Pertanian Presisi TKDN | FDS", 60);
            } elseif ($model_key === 'deltav' || strpos($cat_lower, 'peta') !== false) {
                $title = fds_clamp_meta_title("{$model_base} — Drone Mapping Fixed-Wing VTOL | FDS", 60);
            } elseif ($model_key === 'delfro' || strpos($cat_lower, 'kargo') !== false) {
                $title = fds_clamp_meta_title("{$model_base} — Drone Kargo Logistik Otonom | FDS", 60);
            } else {
                $title = fds_clamp_meta_title("{$model_base} — Drone Inspeksi & Surveillance Modular | FDS", 60);
            }

            $desc_parts  = [];
            if ($tagline !== '') $desc_parts[] = $tagline;
            if ($payload !== '') $desc_parts[] = "Payload: {$payload}";
            if ($durasi !== '')  $desc_parts[] = "Durasi Terbang: {$durasi}";
            if ($is_ferto && $has_sni) {
                $desc_parts[] = 'Sertifikasi TKDN 60,74% & SNI 9199:2023 buatan PT Karya Solusi Angkasa (FDS).';
            } elseif ($is_ferto) {
                $desc_parts[] = 'Sertifikasi TKDN 60,74% buatan PT Karya Solusi Angkasa (FDS).';
            } else {
                $desc_parts[] = 'Buatan PT Karya Solusi Angkasa (Full Drone Solutions), produsen UAV Indonesia.';
            }
            $description = fds_clamp_meta_desc(implode('. ', $desc_parts), 158);

            // Contextual keywords per category (accurate certifications)
            if ($is_ferto || strpos($cat_lower, 'agri') !== false) {
                $cert_kw  = $has_sni ? 'TKDN 60,74%, SNI 9199:2023' : ($is_ferto ? 'TKDN 60,74%' : '');
                $keywords = fds_get_primary_keywords_string("drone pertanian, drone sprayer {$model_base}, jual drone pertanian {$slug}, drone penyemprot pupuk dan pestisida, drone pertanian indonesia, produsen UAV Indonesia, {$cert_kw}, {$payload}");
            } elseif (strpos($cat_lower, 'peta') !== false || strpos($cat_lower, 'gis') !== false || $model_key === 'deltav') {
                $keywords = fds_get_primary_keywords_string("drone mapping, jual drone mapping, jasa pemetaan drone, jasa survey drone, jasa drone foto udara, jasa drone lidar, jasa pemetaan drone tambang, pemetaan GIS drone, fixed wing VTOL, {$model_base}");
            } elseif (strpos($cat_lower, 'kargo') !== false || $model_key === 'delfro') {
                $keywords = fds_get_primary_keywords_string("drone kargo logistik darurat, drone medis, drone payload besar, drone tanggap darurat, UAV Indonesia {$model_base}");
            } else {
                $keywords = fds_get_primary_keywords_string("Drone {$model_base}, Drone {$kategori}, produsen UAV Indonesia, jasa survey drone, inspeksi drone termal, PT Karya Solusi Angkasa");
            }

            $canonical   = trailingslashit(get_permalink($drone_post->ID));
            $image_url   = $feat_img ?: fds_get_default_share_image();
            $og_type     = 'product';

            $breadcrumbs[] = ['name' => 'Produk Drone', 'url' => home_url('/produk/')];
            $breadcrumbs[] = ['name' => $d_title, 'url' => $canonical];
        }
    }
    // 3. KATEGORI DRONE TAXONOMY ARCHIVE
    elseif (is_tax('kategori_drone')) {
        $term      = get_queried_object();
        $term_name = ($term && !empty($term->name)) ? fds_decode_text($term->name) : 'Industri';
        $term_slug = ($term && !empty($term->slug)) ? $term->slug : '';
        $term_link = $term ? get_term_link($term) : home_url('/produk/');
        $canonical = !is_wp_error($term_link) ? trailingslashit($term_link) : trailingslashit(home_url('/produk/'));
        $image_url = fds_get_default_share_image();

        if ($term_slug === 'agrikultur') {
            $title       = 'Drone Agrikultur & Sprayer TKDN — Full Drone Solutions';
            $description = 'Katalog drone pertanian & sprayer presisi seri FERTO (5L–50L) berstandar TKDN 60,74% (SNI 9199:2023 pada FERTO 15 & 22) dan kendali FDS Station.';
        } elseif ($term_slug === 'pemetaan-gis') {
            $title       = 'Drone Pemetaan & Survey LiDAR GIS — Full Drone Solutions';
            $description = 'Katalog drone pemetaan Fixed-Wing VTOL DELTAV dan UAV MULTIPURPOSE untuk foto udara, survey LiDAR, inspeksi aset 150kV, dan pemetaan GIS.';
        } elseif ($term_slug === 'kargo') {
            $title       = 'Drone Kargo Logistik Otonom — Full Drone Solutions';
            $description = 'Platform drone kargo logistik DELFRO payload hingga 10 kg buatan Indonesia untuk distribusi medis darurat, suku cadang, dan wilayah kepulauan.';
        } else {
            $title       = fds_clamp_meta_title("Drone {$term_name} — Solusi UAV Industri | FDS", 60);
            $description = fds_clamp_meta_desc("Katalog resmi drone {$term_name} buatan PT Karya Solusi Angkasa (Full Drone Solutions) untuk operasional industri presisi Indonesia.", 158);
        }

        if (function_exists(__NAMESPACE__ . '\\fds_get_kategori_drone_catalog_content')) {
            $kat_content = fds_get_kategori_drone_catalog_content($term_slug, $term);
            if (!empty($kat_content['seo_title'])) {
                $title = fds_clamp_meta_title((string) $kat_content['seo_title'], 60);
            }
            if (!empty($kat_content['seo_desc'])) {
                $description = fds_clamp_meta_desc((string) $kat_content['seo_desc'], 158);
            }
        }

        $breadcrumbs[] = ['name' => 'Produk Drone', 'url' => home_url('/produk/')];
        $breadcrumbs[] = ['name' => $term_name, 'url' => $canonical];
    }
    // 3b. HALAMAN KATALOG PRODUK (/produk/)
    elseif (is_page('produk')) {
        $pk_content  = function_exists(__NAMESPACE__ . '\\fds_get_produk_catalog_content') ? fds_get_produk_catalog_content() : [];
        $title       = !empty($pk_content['seo_title'])
            ? fds_clamp_meta_title((string) $pk_content['seo_title'], 60)
            : 'Katalog Produk Drone UAV Indonesia — Full Drone Solutions';
        $description = !empty($pk_content['seo_desc'])
            ? fds_clamp_meta_desc((string) $pk_content['seo_desc'], 158)
            : 'Katalog lengkap drone PT Karya Solusi Angkasa: sprayer FERTO (TKDN 60,74%), pemetaan VTOL DELTAV, kargo DELFRO, dan UAV modular MULTIPURPOSE.';
        $keywords    = $primary_keywords;
        $canonical   = trailingslashit(home_url('/produk/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Produk Drone', 'url' => $canonical];
    }
    // 4. BANDINGKAN DRONE PAGE
    elseif (is_page('bandingkan')) {
        $bk_content  = function_exists(__NAMESPACE__ . '\\fds_get_bandingkan_content') ? fds_get_bandingkan_content() : [];
        $title       = !empty($bk_content['seo_title'])
            ? fds_clamp_meta_title((string) $bk_content['seo_title'], 60)
            : 'Bandingkan Spesifikasi Drone — Komparasi Lini UAV FDS';
        $description = !empty($bk_content['seo_desc'])
            ? fds_clamp_meta_desc((string) $bk_content['seo_desc'], 158)
            : 'Bandingkan spesifikasi teknis drone sprayer FERTO (TKDN 60,74%), pemetaan VTOL DELTAV, MULTIPURPOSE, dan kargo DELFRO buatan PT Karya Solusi Angkasa.';
        $keywords    = 'drone pertanian, komparasi drone pertanian, spesifikasi drone pertanian TKDN, drone mapping, jasa pemetaan drone, produsen UAV Indonesia';
        $canonical   = trailingslashit(home_url('/bandingkan/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Bandingkan Drone', 'url' => $canonical];
    }
    // 5. TENTANG KAMI PAGE
    elseif (is_page('tentang-kami')) {
        $title       = 'Tentang Kami — PT Karya Solusi Angkasa (Full Drone Solutions)';
        $description = 'Profil PT Karya Solusi Angkasa (Full Drone Solutions), produsen drone & UAV Indonesia di Sleman, Yogyakarta dengan sistem manajemen mutu ISO 9001:2015.';
        $keywords    = 'produsen UAV Indonesia, PT Karya Solusi Angkasa, pabrik drone Indonesia, produsen drone pertanian, jasa pemetaan drone, drone Sleman Yogyakarta, FDS';
        $canonical   = trailingslashit(home_url('/tentang-kami/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Tentang Kami', 'url' => $canonical];
    }
    // 6. KONTAK KAMI PAGE
    elseif (is_page(['kontak', 'contact'])) {
        $title       = 'Hubungi Kami — Konsultasi Resmi Full Drone Solutions';
        $description = 'Hubungi tim PT Karya Solusi Angkasa untuk konsultasi drone pertanian, jasa survey LiDAR, jadwal demo terbang, dan servis resmi di Sleman, Yogyakarta.';
        $keywords    = 'kontak full drone solutions, kontak PT Karya Solusi Angkasa, alamat pabrik drone yogyakarta, hubungi produsen drone indonesia, pengadaan drone TKDN';
        $canonical   = trailingslashit(home_url('/kontak/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Kontak Kami', 'url' => $canonical];
    }
    // 7. BLOG / NEWSROOM
    elseif (is_home() || is_category() || is_tag() || is_archive()) {
        $title       = 'Newsroom & Blog — Inovasi Teknologi UAV Indonesia | FDS';
        $description = 'Berita terkini, studi kasus operasional drone pertanian, survei pemetaan LiDAR tambang, dan riset teknologi industri UAV dari tim PT Karya Solusi Angkasa.';
        $keywords    = 'artikel drone pertanian, berita drone mapping, studi kasus survey drone lidar, jasa pemetaan drone tambang, teknologi UAV indonesia, sertifikasi TKDN drone';
        $canonical   = is_home() ? trailingslashit(home_url('/blog/')) : trailingslashit($current_url);
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Newsroom', 'url' => home_url('/blog/')];
    }
    // 7b. HALAMAN HUB, SEKTOR & KATEGORI LAYANAN
    elseif (function_exists(__NAMESPACE__ . '\\fds_get_current_layanan_context') && ($layanan_ctx = fds_get_current_layanan_context()) !== null) {
        $ld          = $layanan_ctx['data'] ?? [];
        $seo_meta    = $ld['seo'] ?? [];
        $title       = fds_clamp_meta_title((string) ($seo_meta['meta_title'] ?? ($ld['h1'] ?? 'Layanan UAV FDS')), 60);
        $description = fds_clamp_meta_desc((string) ($seo_meta['meta_desc'] ?? ($ld['hero_summary'] ?? '')), 155);
        $canonical   = trailingslashit((string) ($ld['url'] ?? home_url('/layanan/')));
        $og_type     = 'website';
        $breadcrumbs[] = ['name' => 'Layanan', 'url' => home_url('/layanan/')];
        if (($layanan_ctx['kind'] ?? '') === 'sektor') {
            $breadcrumbs[] = ['name' => (string) ($ld['card_title'] ?? get_the_title()), 'url' => $canonical];
        } elseif (($layanan_ctx['kind'] ?? '') === 'kategori') {
            $breadcrumbs[] = ['name' => (string) ($ld['short_title'] ?? get_the_title()), 'url' => $canonical];
        }
        $image_url = !empty($ld['image']) ? $ld['image'] : fds_get_default_share_image();
    }
    // 8. GENERIC / OTHER PAGES
    else {
        if (is_singular()) {
            $p_title     = fds_decode_text(get_the_title());
            $title       = fds_clamp_meta_title("{$p_title} — Full Drone Solutions", 60);
            $p_excerpt   = get_the_excerpt() ?: wp_trim_words(get_the_content(), 25);
            if ($p_excerpt) {
                $description = fds_clamp_meta_desc($p_excerpt, 158);
            }
            $feat_img    = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if ($feat_img) $image_url = $feat_img;
            $canonical   = trailingslashit(get_permalink());
            $breadcrumbs[] = ['name' => $p_title, 'url' => $canonical];
        }
    }

    if (empty($image_url)) {
        $image_url = fds_get_default_share_image();
    }

    return [
        'title'       => fds_decode_text($title),
        'description' => fds_decode_text($description),
        'keywords'    => fds_decode_text($keywords),
        'canonical'   => esc_url($canonical),
        'image'       => esc_url($image_url),
        'og_type'     => esc_attr($og_type),
        'site_name'   => fds_decode_text($site_name),
        'breadcrumbs' => $breadcrumbs,
    ];
}

/**
 * Get fallback sharing banner image (prioritizes wide landscape hero visuals over tiny logos)
 */
function fds_get_default_share_image() {
    $rm_titles = get_option('rank-math-options-titles', []);
    if (is_array($rm_titles) && !empty($rm_titles['open_graph_image'])) {
        $og_default = (string) $rm_titles['open_graph_image'];
        if (stripos($og_default, 'logo-fds-academy') === false) {
            return $og_default;
        }
    }

    if (function_exists('\App\fds_get_hero_slides')) {
        $slides = \App\fds_get_hero_slides();
        if (is_array($slides)) {
            foreach ($slides as $slide) {
                $slide_img = (string) ($slide['url'] ?? ($slide['img'] ?? ''));
                if ($slide_img !== '' && stripos($slide_img, 'logo-fds-academy') === false) {
                    return $slide_img;
                }
            }
        }
    }

    $theme_hero = get_template_directory() . '/public/images/drone-ecosystem-3d.jpg';
    if (file_exists($theme_hero)) {
        return get_template_directory_uri() . '/public/images/drone-ecosystem-3d.jpg';
    }

    $nb = function_exists('App\fds_get_navbar_brand') ? fds_get_navbar_brand() : [];
    if (!empty($nb['logo_url'])) {
        return $nb['logo_url'];
    }
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_src = wp_get_attachment_image_src($custom_logo_id, 'full');
        if (!empty($logo_src[0])) return $logo_src[0];
    }
    return '';
}

/**
 * Cegah duplikasi <title> dan <meta name="robots"> bawaan WP Core saat FDS Standalone SEO aktif
 */
add_action('template_redirect', function () {
    if (!fds_has_external_seo_plugin()) {
        remove_action('wp_head', '_wp_render_title_tag', 1);
        remove_action('wp_head', 'wp_robots', 1);
    }
}, 5);

/**
 * Render Head Meta Tags (Title, Description, Open Graph, Twitter Cards, Canonical)
 */
function fds_render_seo_meta_tags() {
    if (fds_has_external_seo_plugin()) {
        return;
    }

    $seo = fds_get_seo_data();

    echo "\n    <!-- ============================================================ -->\n";
    echo "    <!-- FDS STANDALONE SEO ENGINE & OPEN GRAPH META TAGS           -->\n";
    echo "    <!-- ============================================================ -->\n";

    echo '    <title>' . esc_html($seo['title']) . "</title>\n";
    echo '    <meta name="description" content="' . esc_attr($seo['description']) . "\">\n";
    echo '    <link rel="canonical" href="' . esc_url($seo['canonical']) . "\">\n";
    if ('0' === (string) get_option('blog_public')) {
        echo "    <meta name=\"robots\" content=\"noindex, nofollow\">\n";
    } elseif (
        is_page(['karir', 'disclaimer', 'terima-kasih'])
        || is_date()
        || is_author()
        || is_tag()
        || is_attachment()
        || is_paged()
        || is_category('uncategorized')
    ) {
        echo "    <meta name=\"robots\" content=\"noindex, follow\">\n";
    } else {
        echo "    <meta name=\"robots\" content=\"index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1\">\n";
    }

    // Open Graph Tags (Hanya dirender jika tidak ada plugin SEO eksternal)
    echo '    <meta property="og:locale" content="id_ID">' . "\n";
    echo '    <meta property="og:type" content="' . esc_attr($seo['og_type']) . "\">\n";
    echo '    <meta property="og:title" content="' . esc_attr($seo['title']) . "\">\n";
    echo '    <meta property="og:description" content="' . esc_attr($seo['description']) . "\">\n";
    echo '    <meta property="og:url" content="' . esc_url($seo['canonical']) . "\">\n";
    echo '    <meta property="og:site_name" content="' . esc_attr($seo['site_name']) . "\">\n";
    if (!empty($seo['image'])) {
        echo '    <meta property="og:image" content="' . esc_url($seo['image']) . "\">\n";
        echo "    <meta property=\"og:image:width\" content=\"1200\">\n";
        echo "    <meta property=\"og:image:height\" content=\"630\">\n";
    }

    // Twitter Card Tags (Hanya dirender jika tidak ada plugin SEO eksternal)
    echo "    <meta name=\"twitter:card\" content=\"summary_large_image\">\n";
    echo '    <meta name="twitter:title" content="' . esc_attr($seo['title']) . "\">\n";
    echo '    <meta name="twitter:description" content="' . esc_attr($seo['description']) . "\">\n";
    if (!empty($seo['image'])) {
        echo '    <meta name="twitter:image" content="' . esc_url($seo['image']) . "\">\n";
    }
}

/**
 * Bangun node Schema.org bersih (CollectionPage / Service / ItemList / FAQPage) untuk halaman Layanan.
 * BEBAS Product, Offer tanpa harga, AggregateRating, dan priceRange.
 */
function fds_build_layanan_schema_nodes(array $ctx, bool $with_context = false): array {
    $home_url  = trailingslashit(home_url('/'));
    $kind      = $ctx['kind'] ?? '';
    $ld        = $ctx['data'] ?? [];
    $canonical = trailingslashit((string) ($ld['url'] ?? home_url('/layanan/')));
    $seo_meta  = $ld['seo'] ?? [];
    $nodes     = [];

    $ctx_prefix = $with_context ? ['@context' => 'https://schema.org'] : [];

    if ($kind === 'hub') {
        $sectors    = function_exists(__NAMESPACE__ . '\\fds_get_layanan_sectors') ? fds_get_layanan_sectors() : [];
        $categories = function_exists(__NAMESPACE__ . '\\fds_get_layanan_categories') ? fds_get_layanan_categories() : [];
        $list_items = [];
        $pos        = 1;

        foreach ($sectors as $sec) {
            $list_items[] = [
                '@type'       => 'ListItem',
                'position'    => $pos++,
                'name'        => (string) ($sec['card_title'] ?? ''),
                'description' => (string) ($sec['card_desc'] ?? ''),
                'url'         => trailingslashit((string) ($sec['url'] ?? '')),
            ];
        }
        foreach ($categories as $cat) {
            $list_items[] = [
                '@type'       => 'ListItem',
                'position'    => $pos++,
                'name'        => (string) ($cat['short_title'] ?? ''),
                'description' => (string) ($cat['hero_summary'] ?? ''),
                'url'         => trailingslashit((string) ($cat['url'] ?? '')),
            ];
        }

        $nodes['fdsLayananHub'] = array_merge($ctx_prefix, [
            '@type'       => 'CollectionPage',
            '@id'         => $canonical . '#collectionpage',
            'url'         => $canonical,
            'name'        => fds_decode_text((string) ($ld['h1'] ?? 'Layanan Full Drone Solutions')),
            'description' => fds_decode_text((string) ($seo_meta['meta_desc'] ?? ($ld['subtitle'] ?? ''))),
            'inLanguage'  => 'id-ID',
            'isPartOf'    => ['@id' => $home_url . '#website'],
            'about'       => ['@id' => $home_url . '#organization'],
            'mainEntity'  => ['@id' => $canonical . '#services-list'],
        ]);

        if (!empty($list_items)) {
            $nodes['fdsLayananList'] = array_merge($ctx_prefix, [
                '@type'           => 'ItemList',
                '@id'             => $canonical . '#services-list',
                'name'            => 'Daftar Solusi Industri & Layanan UAV Full Drone Solutions',
                'itemListElement' => $list_items,
            ]);
        }
    } elseif ($kind === 'sektor' || $kind === 'kategori') {
        $service_name = fds_decode_text((string) ($ld['h1'] ?? ($ld['card_title'] ?? ($ld['short_title'] ?? 'Layanan UAV FDS'))));
        $service_desc = fds_decode_text((string) ($ld['hero_summary'] ?? ($seo_meta['meta_desc'] ?? '')));
        $service_type = fds_decode_text((string) ($seo_meta['service_type'] ?? 'UAV Industrial Survey and Mapping Service'));

        $nodes['fdsLayananService'] = array_merge($ctx_prefix, [
            '@type'            => 'Service',
            '@id'              => $canonical . '#service',
            'name'             => $service_name,
            'serviceType'      => $service_type,
            'url'              => $canonical,
            'description'      => $service_desc,
            'provider'         => ['@id' => $home_url . '#organization'],
            'areaServed'       => ['@type' => 'Country', 'name' => 'Indonesia'],
            'mainEntityOfPage' => $with_context
                ? ['@type' => 'WebPage', '@id' => $canonical]
                : ['@id' => $canonical . '#webpage'],
        ]);
    }

    if (!empty($ld['faqs']) && is_array($ld['faqs'])) {
        $faq_entities = [];
        foreach ($ld['faqs'] as $faq) {
            $q = fds_decode_text((string) ($faq['q'] ?? ''));
            $a = trim((string) ($faq['a'] ?? ''));
            // Lewati FAQ yang seluruh jawabannya masih berupa placeholder [ISI: ...]
            if ($q === '' || $a === '' || str_starts_with($a, '[ISI:')) {
                continue;
            }
            // Bersihkan catatan placeholder inline [ISI: ...] di akhir jawaban untuk output JSON-LD yang rapi
            $clean_a = trim((string) preg_replace('/\s*\[ISI:[^\]]+\]\.?/i', '', $a));
            $clean_a = fds_decode_text($clean_a);
            if ($clean_a === '') {
                continue;
            }
            $faq_entities[] = [
                '@type'          => 'Question',
                'name'           => $q,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $clean_a,
                ],
            ];
        }

        if (!empty($faq_entities)) {
            $nodes['fdsLayananFaq'] = array_merge($ctx_prefix, [
                '@type'      => 'FAQPage',
                '@id'        => $canonical . '#faq',
                'mainEntity' => $faq_entities,
            ]);
        }
    }

    return $nodes;
}

/**
 * Shared SSR & Schema FAQ dataset for /produk/ catalog hub (1:1 parity).
 */
function fds_get_produk_faqs(): array {
    if (function_exists(__NAMESPACE__ . '\\fds_get_produk_catalog_content')) {
        $pk = fds_get_produk_catalog_content();
        if (isset($pk['faqs']) && is_array($pk['faqs'])) {
            return $pk['faqs'];
        }
    }

    return [
        [
            'q' => 'Model drone mana saja yang sudah mengantongi sertifikat TKDN 60,74%?',
            'a' => 'Sertifikat TKDN + BMP 60,74% dari Kemenperin RI berlaku untuk seluruh lini drone pertanian sprayer seri FERTO buatan PT Karya Solusi Angkasa, meliputi FERTO 5, FERTO 10, FERTO 15, FERTO 22, FERTO 30, dan FERTO 50. Pada tingkat korporasi, PT Karya Solusi Angkasa juga menerapkan sistem manajemen mutu ISO 9001:2015 dalam proses manufaktur dan pengujiannya.',
        ],
        [
            'q' => 'Model drone FDS mana yang sudah memenuhi standar SNI 9199:2023?',
            'a' => 'Sertifikasi Standar Nasional Indonesia SNI 9199:2023 saat ini berlaku secara resmi pada dua model sprayer utama, yaitu FERTO 15 dan FERTO 22.',
        ],
        [
            'q' => 'Berapa kapasitas tangki aktual pada drone pertanian FERTO 15?',
            'a' => 'FERTO 15 memiliki kapasitas tangki aktual sebesar 17 Liter (rentang kerja 15 – 17 Liter) dengan produktivitas semprot hingga 8 hektare per jam.',
        ],
        [
            'q' => 'Apa perbedaan kegunaan DELTAV, MULTIPURPOSE, dan DELFRO dibandingkan seri FERTO?',
            'a' => 'Seri FERTO berfokus pada penyemprotan cairan dan penyebaran granul pertanian. DELTAV merupakan wahana Fixed-Wing Hybrid VTOL dengan daya jelajah hingga 60 km untuk pemetaan fotogrametri/LiDAR; MULTIPURPOSE adalah multirotor modular payload 5 kg untuk inspeksi aset termal inframerah maupun 20x optical zoom; sedangkan DELFRO adalah wahana kargo logistik berkapasitas 3 – 10 kg.',
        ],
        [
            'q' => 'Apakah pengadaan unit drone FDS disertai pelatihan operator dan dukungan purna jual?',
            'a' => 'Ya. Setiap pengadaan dari PT Karya Solusi Angkasa mencakup pelatihan operasional FDS Station, uji terbang serah terima, serta dukungan suku cadang dan perawatan dari workshop resmi di Sleman, Daerah Istimewa Yogyakarta.',
        ],
    ];
}

/**
 * Shared SSR & Schema FAQ dataset for /kategori-drone/{slug}/ taxonomy archives (1:1 parity).
 */
function fds_get_kategori_drone_faqs(string $term_slug): array {
    if (function_exists(__NAMESPACE__ . '\\fds_get_kategori_drone_catalog_content')) {
        $kc = fds_get_kategori_drone_catalog_content($term_slug);
        if (isset($kc['faqs']) && is_array($kc['faqs'])) {
            return $kc['faqs'];
        }
    }

    $map = [
        'agrikultur' => [
            [
                'q' => 'Model FERTO mana yang paling sesuai untuk lahan sawah terasering vs perkebunan sawit luas?',
                'a' => 'Untuk petak sempit, hortikultura, dan lahan berbukit, FERTO 5 (5L) dan FERTO 10 (10L) memberikan manuver ringkas. Untuk kebun komersial skala menengah-besar serta perkebunan sawit dan tebu, tersedia FERTO 15 (kapasitas 17 Liter), FERTO 22 (22L), FERTO 30 (30L), hingga FERTO 50 (50L).',
            ],
            [
                'q' => 'Apakah seluruh seri FERTO memenuhi TKDN 60,74% untuk pengadaan pemerintah dan BUMN?',
                'a' => 'Ya. Seluruh model FERTO (5, 10, 15, 22, 30, dan 50) memiliki sertifikat TKDN + BMP 60,74%. Khusus pengadaan yang mensyaratkan SNI 9199:2023, tersedia FERTO 15 dan FERTO 22.',
            ],
            [
                'q' => 'Perangkat lunak apa yang digunakan untuk mengoperasikan armada FERTO?',
                'a' => 'Seluruh seri FERTO dikendalikan melalui Ground Control Station FDS Station berbahasa Indonesia lengkap dengan fitur perencanaan poligon lahan, Terrain Following, dan Fail-Safe Return to Home.',
            ],
            [
                'q' => 'Apakah drone FERTO dapat digunakan untuk menyebar pupuk butir padat maupun benih?',
                'a' => 'Ya. Platform sprayer komersial FERTO mendukung modul opsional Granule Spreader untuk penebaran pupuk butir dan benih padi selain fungsi penyemprotan larutan cair.',
            ],
        ],
        'pemetaan-gis' => [
            [
                'q' => 'Kapan sebaiknya memilih DELTAV VTOL dibandingkan drone multirotor pemetaan biasa?',
                'a' => 'DELTAV VTOL tepat untuk area konsesi perkebunan, pit tambang, kehutanan, dan koridor infrastruktur ratusan hingga ribuan hektare per hari berkat jangkauan hingga 60 km, durasi 60–120 menit, dan kemampuan lepas landas vertikal tanpa landasan pacu.',
            ],
            [
                'q' => 'Sensor apa saja yang dapat dipasang pada DELTAV dan MULTIPURPOSE?',
                'a' => 'DELTAV mendukung muatan 1–2 kg berupa kamera metrik RGB 24–42 MP, sensor multispektral, atau LiDAR ringan. MULTIPURPOSE mendukung payload modular hingga 5 kg termasuk kamera termal inframerah, kamera optical zoom 20x, dan sensor LiDAR inspeksi.',
            ],
            [
                'q' => 'Standar mutu apa yang diterapkan pada manufaktur DELTAV dan MULTIPURPOSE?',
                'a' => 'DELTAV dan MULTIPURPOSE diproduksi dan dikalibrasi di workshop resmi PT Karya Solusi Angkasa di bawah sistem manajemen mutu bersertifikat ISO 9001:2015 untuk menjamin keandalan operasional industri.',
            ],
        ],
        'kargo' => [
            [
                'q' => 'Berapa kapasitas angkut maksimum dan ukuran kotak kargo DELFRO?',
                'a' => 'DELFRO mampu mengangkut muatan efektif 3 hingga 10 kg dalam kompartemen berukuran 20 × 20 × 30 cm dengan bobot lepas landas maksimum (MTOW) 15 kg.',
            ],
            [
                'q' => 'Apa contoh penggunaan paling efektif untuk drone logistik DELFRO?',
                'a' => 'DELFRO digunakan untuk distribusi cepat sampel laboratorium, obat darurat kebencanaan, serta pengiriman suku cadang ringan antartitik site industri melewati sungai, perbukitan, atau medan terisolir.',
            ],
            [
                'q' => 'Bagaimana keamanan penerbangan antar-titik pada DELFRO?',
                'a' => 'DELFRO menjalankan rute waypoint otomatis yang dimonitor real-time melalui FDS Station Logistics Management dengan dukungan kendali manual cadangan dan prosedur Fail-Safe.',
            ],
        ],
    ];

    if (isset($map[$term_slug])) {
        return $map[$term_slug];
    }

    return [
        [
            'q' => 'Bagaimana cara mengajukan konsultasi teknis atau jadwal demo terbang unit?',
            'a' => 'Hubungi tim engineering PT Karya Solusi Angkasa melalui halaman Kontak Resmi untuk menentukan model UAV paling tepat sesuai target operasi dan kondisi lapangan Anda.',
        ],
    ];
}

/**
 * Generate JSON-LD Structured Data (Schema.org) ONLY when external SEO plugin (Rank Math/Yoast) is inactive.
 * When Rank Math is active, schema enrichment runs via `rank_math/json_ld` single graph filter to prevent collision.
 */
function fds_render_schema_jsonld() {
    if (fds_has_external_seo_plugin()) {
        return;
    }

    $seo          = fds_get_seo_data();
    $home_url     = trailingslashit(home_url('/'));
    $share_image  = fds_get_default_share_image();
    $logo_url     = fds_get_official_logo_url() ?: $share_image;
    $same_as      = fds_get_verified_social_urls();

    // Ambil data kontak & alamat resmi dinamis dari Theme Settings
    $contact  = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
    $phone    = !empty($contact['phone']) ? fds_decode_text($contact['phone']) : '+62 8112 748 882';
    $email    = !empty($contact['email']) ? fds_decode_text($contact['email']) : 'marketing@fulldronesolutions.com';
    $raw_addr = !empty($contact['address']) ? fds_decode_text(wp_strip_all_tags($contact['address'])) : 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285';
    $postal   = '55285';
    if (preg_match('/\b(\d{5})\b/', $raw_addr, $pm)) {
        $postal = $pm[1];
    }

    // 1. Organization & LocalBusiness Schema (B2B UAV Manufacturer - Bebas Price Slop)
    $org_schema = [
        '@context'        => 'https://schema.org',
        '@type'           => ['Organization', 'Corporation', 'LocalBusiness'],
        '@id'             => $home_url . '#organization',
        'name'            => 'Full Drone Solutions',
        'alternateName'   => ['PT Karya Solusi Angkasa', 'FDS UAV Indonesia', 'FDS'],
        'legalName'       => 'PT Karya Solusi Angkasa',
        'url'             => $home_url,
        'logo'            => [
            '@type'      => 'ImageObject',
            '@id'        => $home_url . '#logo',
            'url'        => $logo_url,
            'caption'    => 'Full Drone Solutions — PT Karya Solusi Angkasa',
        ],
        'image'           => $share_image ?: $logo_url,
        'description'     => 'PT Karya Solusi Angkasa (Full Drone Solutions), produsen UAV Indonesia No.1: seri drone sprayer FERTO (TKDN 60,74%), pemetaan VTOL DELTAV, kargo DELFRO, jasa foto udara LiDAR & konsultan GIS.',
        'telephone'       => $phone,
        'email'           => $email,
        'address'         => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $raw_addr,
            'addressLocality' => 'Sleman',
            'addressRegion'   => 'Daerah Istimewa Yogyakarta',
            'postalCode'      => $postal,
            'addressCountry'  => 'ID',
        ],
        'geo'             => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => -7.7332,
            'longitude' => 110.3558,
        ],
        'openingHoursSpecification' => fds_get_opening_hours_schema(),
        'areaServed'      => [
            '@type' => 'Country',
            'name'  => 'Indonesia',
        ],
        'knowsAbout'      => [
            'Drone',
            'Drone Indonesia',
            'Pesawat Udara Tanpa Awak (UAV)',
            'Produsen Drone Indonesia',
            'Pabrik Drone & Manufaktur UAV Indonesia',
            'Pengadaan Drone Pemerintah & BUMN',
            'Drone Pertanian & Sprayer Presisi FERTO (TKDN 60,74%)',
            'Pengadaan Drone Pertanian e-Katalog LKPP',
            'Drone Penyemprot Pupuk & Pestisida Tanaman Pangan & Sawit',
            'Drone Sebar Benih & Pemupukan Kelapa Sawit',
            'Jual Drone Pertanian & Servis Suku Cadang Resmi',
            'Drone Mapping & Fotogrametri Udara Sub-Sentimeter',
            'Jasa Pemetaan Drone & Survey Drone LiDAR Akurasi Tinggi',
            'Jasa Pemetaan Drone Tambang (Volume Cut & Fill / Stockpile)',
            'Jasa Drone Foto Udara & Pemetaan GIS Topografi',
            'UAV Fixed-Wing Hybrid VTOL DELTAV Jelajah Panjang',
            'Drone Pencarian Korban Bencana & Misi SAR (Kamera Termal Inframerah)',
            'Drone Kargo Logistik Medis Darurat DELFRO',
            'Inspeksi Termal Infrastruktur Energi, SUTET 150kV & Migas',
            'Sistem Kendali Wahana FDS Station (Bahasa Indonesia)',
            'Produsen UAV Indonesia Resmi (PT Karya Solusi Angkasa)',
            'Sertifikasi TKDN & BMP 60,74% Seri FERTO Kemenperin RI',
            'Standar Nasional Indonesia SNI 9199:2023 (FERTO 15 & FERTO 22)',
            'Sistem Manajemen Mutu ISO 9001:2015',
            'Pelatihan & Sertifikasi Pilot Drone Komersial FDS Academy',
            'Pusat Riset, Perakitan & Servis Suku Cadang Sleman Yogyakarta',
        ],
        'hasOfferCatalog' => [
            '@type'           => 'OfferCatalog',
            'name'            => 'Layanan 4 Fokus Utama FDS (Produsen Drone, Foto Udara LiDAR, Konsultan GIS & Pemetaan Kawasan)',
            'itemListElement' => [
                [
                    '@type' => 'OfferCatalog',
                    'name'  => 'Pilar 1: Produsen Drone Pertanian (FERTO TKDN 60,74%), Pemetaan VTOL, Logistik & Surveillance',
                    'itemListElement' => [
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Produsen Drone Pertanian Sprayer & Granule FERTO (5L–50L) Bersertifikat TKDN 60,74%']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Produsen Drone Pemetaan Fixed-Wing VTOL DELTAV']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Produsen Drone Logistik & Kargo DELFRO']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Produsen Drone Surveillance & Thermal MULTIPURPOSE']],
                    ],
                ],
                [
                    '@type' => 'OfferCatalog',
                    'name'  => 'Pilar 2: Jasa Foto Udara dan Survey LiDAR',
                    'itemListElement' => [
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Foto Udara Resolusi Tinggi (Ortofoto Ortomosaik Sub-Sentimeter)']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Survey Drone LiDAR & Point Cloud DTM/DSM']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Fotogrametri Udara & Pemodelan Topografi Kontur']],
                    ],
                ],
                [
                    '@type' => 'OfferCatalog',
                    'name'  => 'Pilar 3: Jasa Konsultan GIS (Sistem Informasi Geografis)',
                    'itemListElement' => [
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Konsultan GIS & Pemetaan Geospasial Spasial']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Analisis Spasial Tata Ruang RTRW/RDTR & Hidrologi DAS']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Pengembangan WebGIS Enterprise & Dashboard Interaktif']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Pemetaan Kadastral & Batas Lahan HGU Perkebunan/Kehutanan']],
                    ],
                ],
                [
                    '@type' => 'OfferCatalog',
                    'name'  => 'Pilar 4: Pemetaan Kawasan KEK, Tambang, Kehutanan & Industri',
                    'itemListElement' => [
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Pemetaan Kawasan KEK (Kawasan Ekonomi Khusus)']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Pemetaan Drone Tambang (Volume Cut & Fill / Stockpile)']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Pemetaan Kawasan Kehutanan & Karbon Hutan']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Pemetaan Kawasan Industri & Inspeksi Transmisi 150kV']],
                    ],
                ],
            ],
        ],
        'contactPoint'    => [
            [
                '@type'             => 'ContactPoint',
                'telephone'         => $phone,
                'email'             => $email,
                'contactType'       => 'customer service',
                'areaServed'        => 'ID',
                'availableLanguage' => ['Indonesian', 'English'],
            ]
        ]
    ];
    if (!empty($same_as)) {
        $org_schema['sameAs'] = $same_as;
    }

    // 2. WebSite Schema with SearchAction
    $website_schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        '@id'             => $home_url . '#website',
        'url'             => $home_url,
        'name'            => 'Full Drone Solutions',
        'alternateName'   => 'FDS Indonesia',
        'description'     => 'Produsen UAV Indonesia — Solusi Drone Pertanian, Jasa Pemetaan LiDAR & Industri',
        'publisher'       => [
            '@id' => $home_url . '#organization',
        ],
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => $home_url . '?s={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
        'inLanguage'      => 'id-ID',
    ];

    // 3. BreadcrumbList Schema
    $breadcrumb_items = [];
    $pos = 1;
    foreach ($seo['breadcrumbs'] as $bc) {
        $breadcrumb_items[] = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'name'     => $bc['name'],
            'item'     => $bc['url'],
        ];
    }
    $breadcrumb_schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $breadcrumb_items,
    ];

    // 4. Service Schemas for 4 Target Core Focus Pillars (High-value B2B Intent — bebas empty Offer/priceRange)
    $services_schema = [
        // Pilar 1: Produsen Drone Pertanian, Pemetaan, Logistik & Surveillance
        [
            '@context'      => 'https://schema.org',
            '@type'         => 'Service',
            '@id'           => $home_url . '#service-produsen-drone',
            'name'          => 'Produsen Drone Pertanian (FERTO TKDN 60,74%), Pemetaan VTOL, Logistik & Surveillance',
            'serviceType'   => 'UAV Manufacturing, Agricultural Drone Sprayer, VTOL Mapping Drone, Logistics & Surveillance Drone',
            'provider'      => ['@id' => $home_url . '#organization'],
            'areaServed'    => ['@type' => 'Country', 'name' => 'Indonesia'],
            'description'   => 'Manufaktur pesawat tanpa awak (UAV) Indonesia dengan sistem mutu ISO 9001:2015: drone pertanian sprayer FERTO (5L–50L) bersertifikat TKDN 60,74% (SNI 9199:2023 pada FERTO 15 & 22), Fixed-Wing VTOL DELTAV jangkauan 60 km, drone kargo DELFRO, dan drone surveillance termal MULTIPURPOSE.',
        ],
        // Pilar 2: Jasa Foto Udara dan LiDAR
        [
            '@context'      => 'https://schema.org',
            '@type'         => 'Service',
            '@id'           => $home_url . '#service-foto-udara-lidar',
            'name'          => 'Jasa Foto Udara dan Survey LiDAR Akurasi Tinggi',
            'serviceType'   => 'Aerial Photography, UAV LiDAR Survey, Orthophoto Mapping & Sub-centimeter Topography',
            'provider'      => ['@id' => $home_url . '#organization'],
            'areaServed'    => ['@type' => 'Country', 'name' => 'Indonesia'],
            'description'   => 'Layanan jasa foto udara resolusi tinggi (GSD 1-3 cm) dan survey drone LiDAR dengan sensor penetrasi kanopi vegetasi rapat untuk akuisisi kontur akurat, Digital Terrain Model (DTM), Digital Surface Model (DSM), dan ortofoto ortomosaik tanpa distorsi.',
        ],
        // Pilar 3: Konsultan GIS
        [
            '@context'      => 'https://schema.org',
            '@type'         => 'Service',
            '@id'           => $home_url . '#service-konsultan-gis',
            'name'          => 'Jasa Konsultan GIS & Pemodelan Geospasial Spasial',
            'serviceType'   => 'GIS Consultant, Spatial Data Analysis, WebGIS Enterprise & Cadastral Boundary Survey',
            'provider'      => ['@id' => $home_url . '#organization'],
            'areaServed'    => ['@type' => 'Country', 'name' => 'Indonesia'],
            'description'   => 'Konsultan Sistem Informasi Geografis (SIG / GIS) profesional untuk tata guna lahan RTRW/RDTR, analisis hidrologi DAS, pemetaan batas kadastral HGU perkebunan & kehutanan, pemodelan 3D topografi spasial, dan implementasi WebGIS enterprise.',
        ],
        // Pilar 4: Pemetaan Kawasan KEK, Tambang, Kehutanan & Industri
        [
            '@context'      => 'https://schema.org',
            '@type'         => 'Service',
            '@id'           => $home_url . '#service-pemetaan-kawasan',
            'name'          => 'Jasa Pemetaan Kawasan KEK, Tambang, Kehutanan & Industri',
            'serviceType'   => 'Special Economic Zone Mapping, Mining Volume Cut and Fill, Forestry Carbon Inventory & Industrial Estate Drone Survey',
            'provider'      => ['@id' => $home_url . '#organization'],
            'areaServed'    => ['@type' => 'Country', 'name' => 'Indonesia'],
            'description'   => 'Layanan pemetaan udara dan survei geospasial khusus: masterplan Kawasan Ekonomi Khusus (KEK), perhitungan volume stockpile serta cut and fill tambang berbasis RTK/PPK & GCP terkalibrasi, inventarisasi biomassa kawasan kehutanan, serta survei koridor utilitas kawasan industri.',
        ],
    ];

    // 5. FAQPage Schema (Targets People Also Ask, Featured Snippets & AI Search Extraction)
    $faq_schema = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        '@id'        => $home_url . '#faq',
        'mainEntity' => [
            [
                '@type'          => 'Question',
                'name'           => 'Model drone apa saja yang diproduksi oleh PT Karya Solusi Angkasa (Full Drone Solutions) dan bagaimana status TKDN-nya?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'PT Karya Solusi Angkasa (Full Drone Solutions) memproduksi drone pertanian sprayer seri FERTO (5L, 10L, 15L/17L, 22L, 30L, 50L) yang bersertifikat TKDN 60,74% (dengan SNI 9199:2023 pada model FERTO 15 dan FERTO 22), drone pemetaan Fixed-Wing VTOL DELTAV, wahana modular MULTIPURPOSE, serta drone kargo logistik DELFRO dengan standar manajemen mutu ISO 9001:2015 di Sleman, Yogyakarta.',
                ],
            ],
            [
                '@type'          => 'Question',
                'name'           => 'Layanan apa saja yang disediakan FDS untuk jasa foto udara dan survey LiDAR?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'FDS menyediakan jasa foto udara resolusi tinggi (GSD hingga sub-sentimeter) dan survey drone LiDAR dengan sensor penetrasi vegetasi untuk menghasilkan peta ortofoto ortomosaik, model elevasi digital DTM & DSM, point cloud 3D rapat, dan kontur topografi detail untuk perencanaan rekayasa konstruksi, infrastruktur, dan koridor migas/SUTET.',
                ],
            ],
            [
                '@type'          => 'Question',
                'name'           => 'Apa cakupan layanan FDS sebagai Konsultan GIS (Sistem Informasi Geografis)?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'Sebagai Konsultan GIS profesional, FDS melayani analisis spasial lanjutan, pemodelan hidrologi dan daerah aliran sungai (DAS), perencanaan tata ruang RTRW/RDTR, pemetaan batas kadastral wilayah dan HGU perkebunan/kehutanan, pemodelan topografi 3D, serta pengembangan platform WebGIS enterprise interaktif untuk visualisasi dan manajemen data geospasial terintegrasi.',
                ],
            ],
            [
                '@type'          => 'Question',
                'name'           => 'Bagaimana solusi pemetaan kawasan KEK, tambang, kehutanan, dan industri yang ditawarkan oleh FDS?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'FDS menyediakan survei terintegrasi untuk kawasan strategis: pemetaan masterplan Kawasan Ekonomi Khusus (KEK), survei pit tambang batubara/nikel untuk perhitungan volume stockpile dan cut & fill berbasis RTK/PPK dan GCP terkalibrasi, inventarisasi tutupan pohon kawasan kehutanan, serta survei topografi lahan dan inspeksi koridor utilitas kawasan industri.',
                ],
            ],
        ],
    ];

    $schemas     = [$org_schema, $website_schema, $breadcrumb_schema];
    $layanan_ctx = function_exists(__NAMESPACE__ . '\\fds_get_current_layanan_context')
        ? fds_get_current_layanan_context()
        : null;

    if ($layanan_ctx !== null) {
        foreach (fds_build_layanan_schema_nodes($layanan_ctx, true) as $layanan_node) {
            $schemas[] = $layanan_node;
        }
    } elseif (is_front_page()) {
        foreach ($services_schema as $srv) {
            $schemas[] = $srv;
        }
        $schemas[] = $faq_schema;
    }

    // 5b. ItemList Product Catalog Schema on Homepage, /produk/, & /bandingkan/
    if (is_front_page() || is_page(['produk', 'bandingkan']) || (isset($_SERVER['REQUEST_URI']) && preg_match('#/(produk|bandingkan)/?#i', $_SERVER['REQUEST_URI']))) {
        $catalog_drones = get_posts([
            'post_type'      => 'drone',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        ]);
        if (!empty($catalog_drones)) {
            if (function_exists('fds_sort_drone_posts')) {
                $catalog_drones = \fds_sort_drone_posts($catalog_drones);
            }
            $item_elements = [];
            $item_pos = 1;
            foreach ($catalog_drones as $cd) {
                $cd_slug = strtolower((string) $cd->post_name);
                if (strpos($cd_slug, 'rebo') !== false || strpos($cd_slug, 'agrea') !== false) {
                    continue;
                }
                $cd_detail  = function_exists(__NAMESPACE__ . '\\fds_get_drone_detail_data') ? fds_get_drone_detail_data($cd->ID) : [];
                $cd_ferto   = !empty($cd_detail['is_ferto']) || strpos($cd_slug, 'ferto') === 0;
                $cd_sni     = !empty($cd_detail['has_sni']) || in_array($cd_slug, ['ferto-15l', 'ferto-22l', 'ferto-15', 'ferto-22'], true);
                $cd_title   = fds_decode_text((string) ($cd_detail['name'] ?? get_the_title($cd->ID)));
                $cd_url     = (string) ($cd_detail['permalink'] ?? get_permalink($cd->ID));
                $cd_tagline = fds_decode_text((string) ($cd_detail['tagline'] ?? get_post_meta($cd->ID, 'drone_tagline', true)));
                $cd_payload = fds_decode_text((string) ($cd_detail['card_payload'] ?? get_post_meta($cd->ID, 'drone_spec_kapasitas', true)));
                if (function_exists('fds_scrub_legacy_text')) {
                    $cd_title   = \fds_scrub_legacy_text($cd_title, $cd_ferto, $cd_sni);
                    $cd_tagline = \fds_scrub_legacy_text($cd_tagline, $cd_ferto, $cd_sni);
                    $cd_payload = \fds_scrub_legacy_text($cd_payload, $cd_ferto, $cd_sni);
                }
                $item_elements[] = [
                    '@type'       => 'ListItem',
                    'position'    => $item_pos++,
                    'name'        => $cd_title . ($cd_payload ? " ({$cd_payload})" : ''),
                    'description' => $cd_tagline ?: "Platform UAV {$cd_title} produksi PT Karya Solusi Angkasa (Full Drone Solutions).",
                    'url'         => $cd_url,
                ];
            }
            if (!empty($item_elements)) {
                $schemas[] = [
                    '@context'        => 'https://schema.org',
                    '@type'           => 'ItemList',
                    '@id'             => $home_url . '#drone-catalog-list',
                    'name'            => 'Katalog Produk Drone Pertanian (FERTO TKDN), Pemetaan VTOL, Kargo dan Inspeksi FDS',
                    'itemListElement' => $item_elements,
                ];
            }
        }

        if (is_page('produk') || (isset($_SERVER['REQUEST_URI']) && preg_match('#^/produk/?$#i', (string) strtok((string) $_SERVER['REQUEST_URI'], '?')))) {
            $produk_url  = home_url('/produk/');
            $pk_content  = function_exists(__NAMESPACE__ . '\\fds_get_produk_catalog_content') ? fds_get_produk_catalog_content() : [];
            $schema_name = !empty($pk_content['schema_name'])
                ? (string) $pk_content['schema_name']
                : 'Katalog Produk Drone UAV Indonesia — Full Drone Solutions';
            $schema_desc = !empty($pk_content['schema_desc'])
                ? (string) $pk_content['schema_desc']
                : 'Katalog resmi pesawat tanpa awak (UAV) PT Karya Solusi Angkasa: seri FERTO (TKDN 60,74%), DELTAV VTOL, MULTIPURPOSE, dan kargo DELFRO.';
            $produk_faqs = [];
            foreach (fds_get_produk_faqs() as $pfq) {
                $produk_faqs[] = [
                    '@type'          => 'Question',
                    'name'           => $pfq['q'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $pfq['a']],
                ];
            }
            $schemas[] = [
                '@context'    => 'https://schema.org',
                '@type'       => 'CollectionPage',
                '@id'         => $produk_url . '#collectionpage',
                'url'         => $produk_url,
                'name'        => $schema_name,
                'description' => $schema_desc,
                'inLanguage'  => 'id-ID',
                'isPartOf'    => ['@id' => $home_url . '#website'],
                'about'       => ['@id' => $home_url . '#organization'],
                'mainEntity'  => ['@id' => $home_url . '#drone-catalog-list'],
            ];
            if (!empty($produk_faqs)) {
                $schemas[] = [
                    '@context'   => 'https://schema.org',
                    '@type'      => 'FAQPage',
                    '@id'        => $produk_url . '#faq',
                    'mainEntity' => $produk_faqs,
                ];
            }
        }
    }

    if (is_tax('kategori_drone')) {
        $term        = get_queried_object();
        $term_slug   = ($term && !empty($term->slug)) ? (string) $term->slug : '';
        $term_name   = ($term && !empty($term->name)) ? fds_decode_text($term->name) : 'Industri';
        $term_url    = ($term && !is_wp_error($tl = get_term_link($term))) ? trailingslashit($tl) : $home_url;
        $kat_content = function_exists(__NAMESPACE__ . '\\fds_get_kategori_drone_catalog_content')
            ? fds_get_kategori_drone_catalog_content($term_slug, $term)
            : [];
        $schemas[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'CollectionPage',
            '@id'         => $term_url . '#collectionpage',
            'url'         => $term_url,
            'name'        => !empty($kat_content['seo_title']) ? (string) $kat_content['seo_title'] : ($seo['title'] ?: "Drone {$term_name} — Full Drone Solutions"),
            'description' => !empty($kat_content['seo_desc']) ? (string) $kat_content['seo_desc'] : ($seo['description'] ?: (string) ($kat_content['subtitle'] ?? '')),
            'inLanguage'  => 'id-ID',
            'isPartOf'    => ['@id' => $home_url . '#website'],
            'about'       => ['@id' => $home_url . '#organization'],
        ];
        $cat_faqs  = [];
        foreach (fds_get_kategori_drone_faqs($term_slug) as $cfq) {
            $cat_faqs[] = [
                '@type'          => 'Question',
                'name'           => $cfq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $cfq['a']],
            ];
        }
        if (!empty($cat_faqs)) {
            $schemas[] = [
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                '@id'        => $term_url . '#faq',
                'mainEntity' => $cat_faqs,
            ];
        }
    }

    // 6. ItemPage Schema + Optional Synced FAQPage on Single Drone CPT Page
    if (is_singular('drone') || get_query_var('drone')) {
        $drone_post = get_post();
        if ($drone_post) {
            $d_id       = $drone_post->ID;
            $d_detail   = function_exists(__NAMESPACE__ . '\\fds_get_drone_detail_data') ? fds_get_drone_detail_data($d_id) : [];
            $d_slug     = strtolower((string) ($d_detail['slug'] ?? $drone_post->post_name));
            $d_ferto    = !empty($d_detail['is_ferto']) || strpos($d_slug, 'ferto') === 0;
            $d_sni      = !empty($d_detail['has_sni']) || in_array($d_slug, ['ferto-15l', 'ferto-22l', 'ferto-15', 'ferto-22'], true);
            $d_title    = fds_decode_text((string) ($d_detail['name'] ?? get_the_title($d_id)));
            $d_desc     = fds_decode_text((string) ($d_detail['desc'] ?? (get_post_meta($d_id, 'drone_desc', true) ?: $drone_post->post_content)));
            $d_tagline  = fds_decode_text((string) ($d_detail['tagline'] ?? get_post_meta($d_id, 'drone_tagline', true)));
            $feat_img   = (!empty($d_detail['hero_url']) ? (string) $d_detail['hero_url'] : get_the_post_thumbnail_url($d_id, 'full')) ?: $share_image;
            $permalink  = trailingslashit((string) ($d_detail['permalink'] ?? get_permalink($d_id)));
            $desc_clean = $d_desc ?: $d_tagline;
            if (function_exists('fds_scrub_legacy_text')) {
                $d_title    = \fds_scrub_legacy_text($d_title, $d_ferto, $d_sni);
                $desc_clean = \fds_scrub_legacy_text($desc_clean, $d_ferto, $d_sni);
            }

            $schemas[] = [
                '@context'    => 'https://schema.org',
                '@type'       => 'ItemPage',
                '@id'         => $permalink . '#webpage',
                'url'         => $permalink,
                'name'        => $seo['title'] ?: $d_title,
                'description' => $desc_clean,
                'inLanguage'  => 'id-ID',
                'isPartOf'    => ['@id' => $home_url . '#website'],
                'about'       => [
                    '@type'       => 'Thing',
                    '@id'         => $permalink . '#unit',
                    'name'        => $d_title,
                    'description' => $desc_clean,
                    'image'       => $feat_img,
                    'url'         => $permalink,
                ],
            ];

            $raw_body = trim((string) get_post_field('post_content', $d_id));
            if (($raw_body === '' || stripos($raw_body, 'fds-faq-item') !== false || stripos($raw_body, 'Pertanyaan') !== false) && function_exists(__NAMESPACE__ . '\\fds_get_drone_model_guide_and_faqs')) {
                $guide_data = fds_get_drone_model_guide_and_faqs($d_id);
                if (!empty($guide_data['faqs'])) {
                    $faq_items = [];
                    foreach ($guide_data['faqs'] as $fq) {
                        $faq_items[] = [
                            '@type'          => 'Question',
                            'name'           => fds_decode_text($fq['q']),
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text'  => fds_decode_text($fq['a']),
                            ],
                        ];
                    }
                    $schemas[] = [
                        '@context'   => 'https://schema.org',
                        '@type'      => 'FAQPage',
                        '@id'        => $permalink . '#faq',
                        'mainEntity' => $faq_items,
                    ];
                }
            }
        }
    }

    // 6b. Article / BlogPosting Schema for Single Posts
    if (is_singular('post')) {
        $post_obj = get_post();
        if ($post_obj) {
            $p_feat = get_the_post_thumbnail_url($post_obj->ID, 'full') ?: $logo_url;
            $schemas[] = [
                '@context'         => 'https://schema.org',
                '@type'            => 'BlogPosting',
                '@id'              => get_permalink($post_obj->ID) . '#article',
                'headline'         => get_the_title($post_obj->ID),
                'description'      => wp_strip_all_tags(get_the_excerpt($post_obj->ID) ?: wp_trim_words($post_obj->post_content, 30)),
                'image'            => [$p_feat],
                'datePublished'    => get_the_date('c', $post_obj->ID),
                'dateModified'     => get_the_modified_date('c', $post_obj->ID),
                'author'           => [
                    '@type' => 'Organization',
                    'name'  => 'PT Karya Solusi Angkasa (Full Drone Solutions)',
                    'url'   => $home_url,
                ],
                'publisher'        => [
                    '@id' => $home_url . '#organization',
                ],
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id'   => get_permalink($post_obj->ID),
                ],
            ];
        }
    }

    // 7. ContactPage Schema for /kontak
    if (is_page('kontak') || is_page('contact') || (isset($_SERVER['REQUEST_URI']) && preg_match('#/(kontak|contact)/?#i', $_SERVER['REQUEST_URI']))) {
        $schemas[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'ContactPage',
            '@id'         => home_url('/kontak/#contactpage'),
            'url'         => home_url('/kontak/'),
            'name'        => 'Kontak PT Karya Solusi Angkasa (Full Drone Solutions)',
            'description' => 'Halaman kontak resmi PT Karya Solusi Angkasa. Alamat workshop Sleman Yogyakarta, nomor telepon/WhatsApp, dan formulir permohonan penawaran harga.',
            'mainEntity'  => [
                '@id' => $home_url . '#organization',
            ],
        ];
    }

    echo "\n    <!-- SCHEMA.ORG STRUCTURED DATA (JSON-LD) -->\n";
    foreach ($schemas as $s) {
        echo '    <script type="application/ld+json">' . "\n";
        echo wp_json_encode($s, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . "\n";
        echo "    </script>\n";
    }
}

// =========================================================================
// 7. RANK MATH SEO PLUGIN HOOKS & COMPATIBILITY LAYER
// =========================================================================

// Pastikan OpenGraph Locale di Rank Math selalu id_ID untuk target pasar Google Indonesia
add_filter('rank_math/opengraph/facebook/og_locale', function () {
    return 'id_ID';
}, 20);

// Pastikan HTML Language Attributes selalu id-ID untuk Googlebot & GEO AI lokal
add_filter('language_attributes', function () {
    return 'lang="id-ID"';
}, 99);

add_filter('locale', function ($locale) {
    if (!is_admin()) {
        return 'id_ID';
    }
    return $locale;
}, 99);

    // Pastikan entitas global (Organization/LocalBusiness, WebSite, WebPage) selalu ikut pada katalog & halaman drone
add_filter('rank_math/schema/add_global_entities', function ($can_add) {
    if (is_singular('drone') || is_front_page() || is_tax('kategori_drone') || is_page('produk')) {
        return true;
    }
    return $can_add;
}, 20);

// Harmonisasikan Schema Rank Math dalam satu Unified @graph (bebas duplikasi, priceRange, Product kosong & Offer tanpa harga)
add_filter('rank_math/json_ld', function ($data, $jsonld) {
    if (!is_array($data)) {
        return $data;
    }

    $home_url       = trailingslashit(home_url('/'));
    $official_logo  = fds_get_official_logo_url();
    $same_as        = fds_get_verified_social_urls();
    $opening_hours  = fds_get_opening_hours_schema();
    $contact        = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
    $official_addr  = !empty($contact['address'])
        ? fds_decode_text($contact['address'])
        : 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285';
    $official_phone = !empty($contact['phone']) ? fds_decode_text($contact['phone']) : '+62 8112 748 882';
    $official_email = !empty($contact['email']) ? fds_decode_text($contact['email']) : 'marketing@fulldronesolutions.com';
    $postal         = '55285';
    if (preg_match('/\b(\d{5})\b/', $official_addr, $pm)) {
        $postal = $pm[1];
    }

    $has_valid_offer_or_review = function (array $node): bool {
        if (isset($node['offers']) && is_array($node['offers'])) {
            $offers = $node['offers'];
            if (
                (isset($offers['price']) && is_numeric($offers['price']))
                || isset($offers['priceSpecification'])
                || (isset($offers[0]) && is_array($offers[0]) && ((isset($offers[0]['price']) && is_numeric($offers[0]['price'])) || isset($offers[0]['priceSpecification'])))
            ) {
                return true;
            }
        }
        if (!empty($node['aggregateRating']) && is_array($node['aggregateRating']) && (!empty($node['aggregateRating']['ratingCount']) || !empty($node['aggregateRating']['reviewCount']))) {
            return true;
        }
        if (!empty($node['review']) && is_array($node['review'])) {
            return true;
        }
        return false;
    };

    // Hapus node Article/BlogPosting/NewsArticle, Product, ProductGroup, Offer, AggregateRating, Review nyasar / tanpa harga/ulasan
    $layanan_ctx = function_exists(__NAMESPACE__ . '\\fds_get_current_layanan_context')
        ? fds_get_current_layanan_context()
        : null;
    $is_non_article_landing = is_front_page()
        || is_page(['produk', 'bandingkan', 'kontak', 'contact'])
        || is_tax('kategori_drone')
        || ($layanan_ctx !== null);
    if ($is_non_article_landing || is_singular('drone')) {
        foreach ($data as $key => $node) {
            if (!is_array($node)) {
                continue;
            }
            $node_type = $node['@type'] ?? '';
            $types     = is_array($node_type) ? $node_type : [$node_type];
            if ($is_non_article_landing && array_intersect($types, ['Article', 'BlogPosting', 'NewsArticle', 'Product', 'ProductGroup', 'Offer', 'AggregateRating', 'Review'])) {
                unset($data[$key]);
                continue;
            }
            if (is_singular('drone') && array_intersect($types, ['Product', 'ProductGroup', 'Offer', 'AggregateRating', 'Review'])) {
                if (!$has_valid_offer_or_review($node)) {
                    unset($data[$key]);
                }
            }
        }
    }

    $text_keys = ['name', 'headline', 'description', 'caption', 'alternateName', 'legalName', 'value', 'text'];

    $sanitize_node = function (&$node) use (&$sanitize_node, $official_addr, $official_phone, $official_email, $postal, $text_keys, $is_non_article_landing, $official_logo, $same_as, $opening_hours) {
        if (!is_array($node)) {
            return;
        }

        // Hapus priceRange agar SERP tidak pernah menampilkan baris biaya "Price: $$$$"
        if (array_key_exists('priceRange', $node)) {
            unset($node['priceRange']);
        }

        if ($is_non_article_landing || is_singular('drone')) {
            $cur_type  = $node['@type'] ?? '';
            $cur_types = is_array($cur_type) ? $cur_type : [$cur_type];
            if (array_intersect($cur_types, ['WebPage', 'ItemPage', 'CollectionPage', 'Service'])) {
                unset($node['offers'], $node['aggregateRating'], $node['review']);
            }
        }

        if (isset($node['inLanguage']) && in_array($node['inLanguage'], ['en-US', 'en_US', 'en'], true)) {
            $node['inLanguage'] = 'id-ID';
        }

        // Decode HTML entity ganda pada kolom teks Schema
        foreach ($text_keys as $tk) {
            if (isset($node[$tk])) {
                if (is_string($node[$tk])) {
                    $node[$tk] = fds_decode_text($node[$tk]);
                } elseif (is_array($node[$tk])) {
                    foreach ($node[$tk] as $ak => $av) {
                        if (is_string($av)) {
                            $node[$tk][$ak] = fds_decode_text($av);
                        }
                    }
                }
            }
        }

        if (isset($node['knowsAbout']) && is_array($node['knowsAbout'])) {
            foreach ($node['knowsAbout'] as $kk => $kv) {
                if (is_string($kv)) {
                    $node['knowsAbout'][$kk] = fds_decode_text($kv);
                }
            }
        }

        if (isset($node['caption']) && is_string($node['caption']) && stripos($node['caption'], 'supplier') !== false) {
            $node['caption'] = 'Full Drone Solutions — PT Karya Solusi Angkasa';
        }

        $node_type = $node['@type'] ?? '';
        $types     = is_array($node_type) ? $node_type : [$node_type];

        // Bersihkan Offer tanpa harga numerik agar lolos Google Rich Results Test
        if (in_array('Product', $types, true) && isset($node['offers'])) {
            $offers      = $node['offers'];
            $has_numeric = false;
            if (is_array($offers)) {
                if (isset($offers['price']) && is_numeric($offers['price'])) {
                    $has_numeric = true;
                } elseif (isset($offers['priceSpecification'])) {
                    $has_numeric = true;
                } elseif (isset($offers[0]) && is_array($offers[0]) && ((isset($offers[0]['price']) && is_numeric($offers[0]['price'])) || isset($offers[0]['priceSpecification']))) {
                    $has_numeric = true;
                }
            }
            if (!$has_numeric) {
                unset($node['offers']);
            }
        }

        if (in_array('WebSite', $types, true)) {
            $node['name']          = 'Full Drone Solutions';
            $node['alternateName'] = 'PT Karya Solusi Angkasa (FDS)';
        }

        if (in_array('Organization', $types, true) || in_array('LocalBusiness', $types, true) || in_array('Corporation', $types, true)) {
            $node['name']          = 'Full Drone Solutions';
            $node['alternateName'] = ['PT Karya Solusi Angkasa', 'FDS UAV Indonesia', 'FDS'];
            $node['legalName']     = 'PT Karya Solusi Angkasa';
            $node['telephone']     = $official_phone;
            $node['email']         = $official_email;
            $node['openingHoursSpecification'] = $opening_hours;
            if (!empty($same_as)) {
                $node['sameAs'] = $same_as;
            } else {
                unset($node['sameAs']);
            }
            if ($official_logo !== '') {
                if (isset($node['logo']) && is_array($node['logo'])) {
                    $node['logo']['url'] = $official_logo;
                } elseif (isset($node['logo']) && is_string($node['logo'])) {
                    $node['logo'] = $official_logo;
                }
            }
            if (!isset($node['geo'])) {
                $node['geo'] = [
                    '@type'     => 'GeoCoordinates',
                    'latitude'  => -7.7332,
                    'longitude' => 110.3558,
                ];
            }
            if (!isset($node['contactPoint'])) {
                $node['contactPoint'] = [
                    '@type'             => 'ContactPoint',
                    'telephone'         => $official_phone,
                    'email'             => $official_email,
                    'contactType'       => 'customer service',
                    'areaServed'        => 'ID',
                    'availableLanguage' => ['Indonesian', 'English'],
                ];
            }
            if (!isset($node['knowsAbout'])) {
                $node['knowsAbout'] = [
                    'Produsen Drone Pertanian Sprayer FERTO (TKDN 60,74% & SNI 9199:2023 pada FERTO 15 & 22)',
                    'Jasa Foto Udara Resolusi Tinggi dan Survey Drone LiDAR',
                    'Konsultan GIS dan Pengembangan WebGIS Enterprise',
                    'Pemetaan Kawasan KEK, Tambang, Kehutanan dan Kawasan Industri',
                    'UAV Fixed-Wing Hybrid VTOL DELTAV, MULTIPURPOSE & Drone Kargo DELFRO',
                ];
            }
            if (isset($node['logo']['caption']) && is_string($node['logo']['caption']) && stripos($node['logo']['caption'], 'supplier') !== false) {
                $node['logo']['caption'] = 'Full Drone Solutions — PT Karya Solusi Angkasa';
            }
        }

        if (in_array('ImageObject', $types, true) && isset($node['@id']) && str_ends_with((string) $node['@id'], '#logo') && $official_logo !== '') {
            $node['url']     = $official_logo;
            $node['caption'] = 'Full Drone Solutions — PT Karya Solusi Angkasa';
        }

        if (
            in_array('PostalAddress', $types, true)
            || (isset($node['streetAddress']) && (stripos((string) $node['streetAddress'], 'Griya Perwita') !== false || stripos((string) $node['streetAddress'], 'Condongcatur') !== false))
        ) {
            $node['streetAddress']   = $official_addr;
            $node['addressLocality'] = 'Sleman';
            $node['addressRegion']   = 'Daerah Istimewa Yogyakarta';
            $node['postalCode']      = $postal;
            $node['addressCountry']  = 'ID';
        }

        if (in_array('BreadcrumbList', $types, true) && !empty($node['itemListElement']) && is_array($node['itemListElement'])) {
            foreach ($node['itemListElement'] as $b_idx => &$bc_item) {
                if (!is_array($bc_item)) {
                    continue;
                }
                if (isset($bc_item['name']) && is_string($bc_item['name'])) {
                    $bc_name = fds_decode_text($bc_item['name']);
                    if (strcasecmp($bc_name, 'Home') === 0) {
                        $bc_name = 'Beranda';
                    }
                    $bc_item['name'] = $bc_name;
                }
                if (isset($bc_item['item']) && is_array($bc_item['item']) && isset($bc_item['item']['name']) && strcasecmp(trim((string) $bc_item['item']['name']), 'Home') === 0) {
                    $bc_item['item']['name'] = 'Beranda';
                }
                if ($b_idx === 0 && isset($bc_item['item']) && is_string($bc_item['item'])) {
                    $bc_item['item'] = trailingslashit($bc_item['item']);
                }
            }
            unset($bc_item);
        }

        foreach ($node as &$child) {
            if (is_array($child)) {
                $sanitize_node($child);
            }
        }
    };

    $sanitize_node($data);

    // 1. ItemList Katalog Drone pada Beranda, /produk/, /bandingkan/, dan /kategori-drone/*
    if (is_front_page() || is_page(['produk', 'bandingkan']) || is_tax('kategori_drone')) {
        $q_args = [
            'post_type'      => 'drone',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        ];
        $list_name = 'Katalog Produk Drone Pertanian (FERTO TKDN), Pemetaan VTOL, Kargo dan Inspeksi FDS';
        $list_id   = $home_url . '#drone-catalog-list';

        if (is_tax('kategori_drone')) {
            $term = get_queried_object();
            if ($term && !empty($term->term_id)) {
                $q_args['tax_query'] = [[
                    'taxonomy' => 'kategori_drone',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ]];
                $t_link    = get_term_link($term);
                $list_name = 'Katalog Drone Kategori ' . fds_decode_text((string) $term->name) . ' — Full Drone Solutions';
                $list_id   = (!is_wp_error($t_link) ? trailingslashit($t_link) : $home_url) . '#drone-catalog-list';
            }
        }

        $catalog_drones = get_posts($q_args);
        if (!empty($catalog_drones)) {
            $item_elements = [];
            $item_pos      = 1;
            foreach ($catalog_drones as $cd) {
                $cd_slug = strtolower((string) $cd->post_name);
                if (strpos($cd_slug, 'rebo') !== false || strpos($cd_slug, 'agrea') !== false) {
                    continue;
                }
                $cd_title   = fds_decode_text(get_the_title($cd->ID));
                $cd_tagline = fds_decode_text((string) get_post_meta($cd->ID, 'drone_tagline', true));
                $cd_payload = fds_decode_text((string) get_post_meta($cd->ID, 'drone_spec_kapasitas', true));
                $item_elements[] = [
                    '@type'       => 'ListItem',
                    'position'    => $item_pos++,
                    'name'        => $cd_title . ($cd_payload !== '' ? " ({$cd_payload})" : ''),
                    'description' => $cd_tagline !== '' ? $cd_tagline : "Platform UAV {$cd_title} buatan PT Karya Solusi Angkasa (Full Drone Solutions).",
                    'url'         => get_permalink($cd->ID),
                ];
            }
            if (!empty($item_elements)) {
                $data['fdsCatalogList'] = [
                    '@type'           => 'ItemList',
                    '@id'             => $list_id,
                    'name'            => $list_name,
                    'itemListElement' => $item_elements,
                ];
            }
        }
    }

    // 2. ContactPage pada /kontak/
    if (is_page(['kontak', 'contact'])) {
        $found_contact_page = false;
        foreach ($data as $ck => &$cn) {
            if (!is_array($cn)) {
                continue;
            }
            $ct = $cn['@type'] ?? '';
            $cs = is_array($ct) ? $ct : [$ct];
            if (in_array('ContactPage', $cs, true)) {
                $found_contact_page = true;
                $cn['name']         = 'Kontak PT Karya Solusi Angkasa (Full Drone Solutions)';
                $cn['description']  = 'Halaman kontak resmi PT Karya Solusi Angkasa. Alamat workshop Sleman Yogyakarta, telepon/WhatsApp, dan konsultasi pengadaan drone.';
                $cn['inLanguage']   = 'id-ID';
                $cn['mainEntity']   = ['@id' => $home_url . '#organization'];
            }
        }
        unset($cn);

        if (!$found_contact_page) {
            $data['fdsContactPage'] = [
                '@type'       => 'ContactPage',
                '@id'         => home_url('/kontak/#contactpage'),
                'url'         => home_url('/kontak/'),
                'name'        => 'Kontak PT Karya Solusi Angkasa (Full Drone Solutions)',
                'description' => 'Halaman kontak resmi PT Karya Solusi Angkasa. Alamat workshop Sleman Yogyakarta, telepon/WhatsApp, dan konsultasi pengadaan drone.',
                'inLanguage'  => 'id-ID',
                'mainEntity'  => [
                    '@id' => $home_url . '#organization',
                ],
            ];
        }
    }

    // 3. Single Drone: Pastikan ItemPage rapi, Breadcrumb melewati /produk/, & Emit FAQPage KHUSUS sesuai konten terlihat
    if (is_singular('drone')) {
        $drone_post = get_post();
        if ($drone_post) {
            $d_id       = $drone_post->ID;
            $d_detail   = function_exists(__NAMESPACE__ . '\\fds_get_drone_detail_data') ? fds_get_drone_detail_data($d_id) : [];
            $d_slug     = strtolower((string) ($d_detail['slug'] ?? $drone_post->post_name));
            $d_ferto    = !empty($d_detail['is_ferto']) || strpos($d_slug, 'ferto') === 0;
            $d_sni      = !empty($d_detail['has_sni']) || in_array($d_slug, ['ferto-15l', 'ferto-22l', 'ferto-15', 'ferto-22'], true);
            $d_title    = fds_decode_text((string) ($d_detail['name'] ?? get_the_title($d_id)));
            $d_desc     = fds_decode_text((string) ($d_detail['desc'] ?? (get_post_meta($d_id, 'drone_desc', true) ?: get_post_meta($d_id, 'drone_tagline', true))));
            if ($d_desc === '') {
                $d_desc = fds_decode_text((string) ($d_detail['tagline'] ?? ''));
            }
            $kategori   = fds_decode_text((string) ($d_detail['kategori'] ?? (get_post_meta($d_id, 'drone_kategori', true) ?: 'UAV Industri')));
            if (function_exists('fds_scrub_legacy_text')) {
                $d_title  = \fds_scrub_legacy_text($d_title, $d_ferto, $d_sni);
                $d_desc   = \fds_scrub_legacy_text($d_desc, $d_ferto, $d_sni);
                $kategori = \fds_scrub_legacy_text($kategori, $d_ferto, $d_sni);
            }
            $feat_img   = (!empty($d_detail['hero_url']) ? (string) $d_detail['hero_url'] : get_the_post_thumbnail_url($d_id, 'full')) ?: fds_get_default_share_image();
            $permalink  = trailingslashit((string) ($d_detail['permalink'] ?? get_permalink($d_id)));

            $found_webpage = false;
            foreach ($data as $k => &$n) {
                if (!is_array($n)) {
                    continue;
                }
                $nt = $n['@type'] ?? '';
                $ts = is_array($nt) ? $nt : [$nt];
                if (in_array('BreadcrumbList', $ts, true)) {
                    $n['itemListElement'] = [
                        ['@type' => 'ListItem', 'position' => 1, 'item' => ['@id' => $home_url, 'name' => 'Beranda']],
                        ['@type' => 'ListItem', 'position' => 2, 'item' => ['@id' => home_url('/produk/'), 'name' => 'Produk Drone']],
                        ['@type' => 'ListItem', 'position' => 3, 'item' => ['@id' => $permalink, 'name' => $d_title]],
                    ];
                }
                if (in_array('WebPage', $ts, true) || in_array('ItemPage', $ts, true)) {
                    $found_webpage    = true;
                    $n['@type']       = 'ItemPage';
                    $n['@id']         = $n['@id'] ?? ($permalink . '#webpage');
                    $n['url']         = $permalink;
                    $n['name']        = $d_title . ' — ' . $kategori . ' | Full Drone Solutions';
                    $n['description'] = $d_desc;
                    $n['inLanguage']  = 'id-ID';
                    $n['about']       = [
                        '@type'       => 'Thing',
                        '@id'         => $permalink . '#unit',
                        'name'        => $d_title,
                        'description' => $d_desc,
                        'image'       => $feat_img,
                        'url'         => $permalink,
                    ];
                }
            }
            unset($n);

            if (!$found_webpage) {
                $data['WebPage'] = [
                    '@type'       => 'ItemPage',
                    '@id'         => $permalink . '#webpage',
                    'url'         => $permalink,
                    'name'        => $d_title . ' — ' . $kategori . ' | Full Drone Solutions',
                    'description' => $d_desc,
                    'inLanguage'  => 'id-ID',
                    'isPartOf'    => ['@id' => $home_url . '#website'],
                    'about'       => [
                        '@type'       => 'Thing',
                        '@id'         => $permalink . '#unit',
                        'name'        => $d_title,
                        'description' => $d_desc,
                        'image'       => $feat_img,
                        'url'         => $permalink,
                    ],
                ];
            }

            $raw_body        = trim((string) get_post_field('post_content', $d_id));
            $has_visible_faq = ($raw_body === '')
                || (stripos($raw_body, 'fds-faq-item') !== false)
                || (stripos($raw_body, 'Pertanyaan') !== false);

            if ($has_visible_faq && function_exists(__NAMESPACE__ . '\\fds_get_drone_model_guide_and_faqs')) {
                $guide_data = fds_get_drone_model_guide_and_faqs($d_id);
                if (!empty($guide_data['faqs'])) {
                    $faq_items = [];
                    foreach ($guide_data['faqs'] as $fq) {
                        $faq_items[] = [
                            '@type'          => 'Question',
                            'name'           => fds_decode_text($fq['q']),
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text'  => fds_decode_text($fq['a']),
                            ],
                        ];
                    }
                    $data['fdsDroneFaq'] = [
                        '@type'      => 'FAQPage',
                        '@id'        => $permalink . '#faq',
                        'isPartOf'   => ['@id' => $permalink . '#webpage'],
                        'mainEntity' => $faq_items,
                    ];
                }
            }
        }
    }

    if (is_page('produk')) {
        $produk_url  = home_url('/produk/');
        $pk_content  = function_exists(__NAMESPACE__ . '\\fds_get_produk_catalog_content') ? fds_get_produk_catalog_content() : [];
        $schema_name = !empty($pk_content['schema_name'])
            ? (string) $pk_content['schema_name']
            : 'Katalog Produk Drone UAV Indonesia — Full Drone Solutions';
        $schema_desc = !empty($pk_content['schema_desc'])
            ? (string) $pk_content['schema_desc']
            : 'Katalog resmi pesawat tanpa awak (UAV) PT Karya Solusi Angkasa: seri FERTO (TKDN 60,74%), DELTAV VTOL, MULTIPURPOSE, dan kargo DELFRO.';
        $produk_faqs = [];
        foreach (fds_get_produk_faqs() as $pfq) {
            $produk_faqs[] = [
                '@type'          => 'Question',
                'name'           => $pfq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $pfq['a']],
            ];
        }
        $found_produk_page = false;
        foreach ($data as $pk => &$pn) {
            if (!is_array($pn)) {
                continue;
            }
            $pt = $pn['@type'] ?? '';
            $ps = is_array($pt) ? $pt : [$pt];
            if (in_array('WebPage', $ps, true) || in_array('CollectionPage', $ps, true)) {
                $found_produk_page = true;
                $pn['@type']       = 'CollectionPage';
                $pn['name']        = $schema_name;
                $pn['description'] = $schema_desc;
                $pn['inLanguage']  = 'id-ID';
                $pn['mainEntity']  = ['@id' => $home_url . '#drone-catalog-list'];
            }
        }
        unset($pn);
        if (!$found_produk_page) {
            $data['fdsProdukHub'] = [
                '@type'       => 'CollectionPage',
                '@id'         => $produk_url . '#collectionpage',
                'url'         => $produk_url,
                'name'        => $schema_name,
                'description' => $schema_desc,
                'inLanguage'  => 'id-ID',
                'isPartOf'    => ['@id' => $home_url . '#website'],
                'about'       => ['@id' => $home_url . '#organization'],
                'mainEntity'  => ['@id' => $home_url . '#drone-catalog-list'],
            ];
        }
        if (!empty($produk_faqs)) {
            $data['fdsProdukFaq'] = [
                '@type'      => 'FAQPage',
                '@id'        => $produk_url . '#faq',
                'mainEntity' => $produk_faqs,
            ];
        }
    }

    if (is_tax('kategori_drone')) {
        $term        = get_queried_object();
        $term_name   = ($term && !empty($term->name)) ? fds_decode_text($term->name) : 'Industri';
        $term_slug   = ($term && !empty($term->slug)) ? (string) $term->slug : '';
        $term_link   = ($term && !is_wp_error($t_url = get_term_link($term))) ? trailingslashit($t_url) : $home_url;
        $kat_content = function_exists(__NAMESPACE__ . '\\fds_get_kategori_drone_catalog_content')
            ? fds_get_kategori_drone_catalog_content($term_slug, $term)
            : [];
        foreach ($data as &$n) {
            if (!is_array($n)) {
                continue;
            }
            $nt = $n['@type'] ?? '';
            $ts = is_array($nt) ? $nt : [$nt];
            if (in_array('BreadcrumbList', $ts, true)) {
                $n['itemListElement'] = [
                    ['@type' => 'ListItem', 'position' => 1, 'item' => ['@id' => $home_url, 'name' => 'Beranda']],
                    ['@type' => 'ListItem', 'position' => 2, 'item' => ['@id' => home_url('/produk/'), 'name' => 'Produk Drone']],
                    ['@type' => 'ListItem', 'position' => 3, 'item' => ['@id' => $term_link, 'name' => $term_name]],
                ];
            }
            if (in_array('CollectionPage', $ts, true) || in_array('WebPage', $ts, true)) {
                if (!empty($kat_content['seo_title'])) {
                    $n['name'] = fds_decode_text((string) $kat_content['seo_title']);
                }
                if (!empty($kat_content['seo_desc'])) {
                    $n['description'] = fds_decode_text((string) $kat_content['seo_desc']);
                }
                $n['inLanguage'] = 'id-ID';
            }
        }
        unset($n);

        $cat_faqs = [];
        foreach (fds_get_kategori_drone_faqs($term_slug) as $cfq) {
            $cat_faqs[] = [
                '@type'          => 'Question',
                'name'           => $cfq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $cfq['a']],
            ];
        }
        if (!empty($cat_faqs)) {
            $data['fdsKategoriDroneFaq'] = [
                '@type'      => 'FAQPage',
                '@id'        => $term_link . '#faq',
                'mainEntity' => $cat_faqs,
            ];
        }
    }

    // 4. Halaman Hub, Sektor Industri & Kategori Layanan (/layanan/*): Service / CollectionPage / ItemList / FAQPage bersih
    if ($layanan_ctx !== null && function_exists(__NAMESPACE__ . '\\fds_build_layanan_schema_nodes')) {
        $layanan_nodes = fds_build_layanan_schema_nodes($layanan_ctx, false);
        foreach ($layanan_nodes as $ln_key => $ln_node) {
            $data[$ln_key] = $ln_node;
        }

        $canonical_layanan = trailingslashit((string) ($layanan_ctx['data']['url'] ?? home_url('/layanan/')));
        $crumb_label = ($layanan_ctx['kind'] ?? '') === 'sektor'
            ? (string) ($layanan_ctx['data']['card_title'] ?? get_the_title())
            : (($layanan_ctx['kind'] ?? '') === 'kategori' ? (string) ($layanan_ctx['data']['short_title'] ?? get_the_title()) : '');

        $has_rm_webpage = false;
        foreach ($data as $k => &$n) {
            if (!is_array($n)) {
                continue;
            }
            $nt = $n['@type'] ?? '';
            $ts = is_array($nt) ? $nt : [$nt];
            if (in_array('BreadcrumbList', $ts, true)) {
                $bc_items = [
                    [
                        '@type'    => 'ListItem',
                        'position' => 1,
                        'item'     => ['@id' => $home_url, 'name' => 'Beranda'],
                    ],
                    [
                        '@type'    => 'ListItem',
                        'position' => 2,
                        'item'     => ['@id' => home_url('/layanan/'), 'name' => 'Layanan'],
                    ],
                ];
                if ($crumb_label !== '') {
                    $bc_items[] = [
                        '@type'    => 'ListItem',
                        'position' => 3,
                        'item'     => ['@id' => $canonical_layanan, 'name' => fds_decode_text($crumb_label)],
                    ];
                }
                $n['itemListElement'] = $bc_items;
            }
            if (($layanan_ctx['kind'] ?? '') === 'hub' && (in_array('WebPage', $ts, true) || in_array('CollectionPage', $ts, true)) && $k !== 'fdsLayananHub') {
                $has_rm_webpage   = true;
                $n['@type']       = 'CollectionPage';
                $n['name']        = fds_decode_text((string) ($layanan_ctx['data']['h1'] ?? 'Layanan Full Drone Solutions'));
                $n['description'] = fds_decode_text((string) ($layanan_ctx['data']['seo']['meta_desc'] ?? ($layanan_ctx['data']['subtitle'] ?? '')));
                $n['inLanguage']  = 'id-ID';
                $n['mainEntity']  = ['@id' => $canonical_layanan . '#services-list'];
            }
        }
        unset($n);

        if ($has_rm_webpage && isset($data['fdsLayananHub'])) {
            unset($data['fdsLayananHub']);
        }
    }

    return $data;
}, 999, 2);

// Pastikan OpenGraph & Twitter image tidak pernah memakai thumbnail kecil legacy academy
add_filter('rank_math/opengraph/facebook/image', function ($img) {
    if (empty($img) || stripos((string) $img, 'logo-fds-academy') !== false) {
        return fds_get_default_share_image();
    }
    return $img;
}, 99);

add_filter('rank_math/opengraph/twitter/image', function ($img) {
    if (empty($img) || stripos((string) $img, 'logo-fds-academy') !== false) {
        return fds_get_default_share_image();
    }
    return $img;
}, 99);

/**
 * Filter Rank Math Title for Drone CPT, Homepage, Taxonomy, and Custom Pages
 * Prioritizes user custom title from Rank Math snippet editor if set,
 * strips redundant "Beranda - " / "Home - " prefixes, and clamps fallbacks <= 60 chars.
 */
add_filter('rank_math/frontend/title', function ($title) {
    $title = fds_decode_text((string) preg_replace('/^(beranda|home)\s*[-|–—:]\s*/iu', '', (string) $title));

    if (is_front_page()) {
        return 'Full Drone Solutions — Produsen Drone & UAV No.1 Indonesia';
    }

    if (is_tax('kategori_drone')) {
        $term      = get_queried_object();
        $term_name = ($term && !empty($term->name)) ? fds_decode_text($term->name) : 'Industri';
        $term_slug = ($term && !empty($term->slug)) ? $term->slug : '';
        if (function_exists(__NAMESPACE__ . '\\fds_get_kategori_drone_catalog_content')) {
            $kat_content = fds_get_kategori_drone_catalog_content($term_slug, $term);
            if (!empty($kat_content['seo_title'])) {
                return fds_clamp_meta_title((string) $kat_content['seo_title'], 60);
            }
        }
        if ($term_slug === 'agrikultur') {
            return 'Drone Agrikultur & Sprayer TKDN — Full Drone Solutions';
        }
        if ($term_slug === 'pemetaan-gis') {
            return 'Drone Pemetaan & Survey LiDAR GIS — Full Drone Solutions';
        }
        if ($term_slug === 'kargo') {
            return 'Drone Kargo Logistik Otonom — Full Drone Solutions';
        }
        return fds_clamp_meta_title("Drone {$term_name} — Solusi UAV Industri | FDS", 60);
    }

    if (is_page('produk')) {
        if (function_exists(__NAMESPACE__ . '\\fds_get_produk_catalog_content')) {
            $pk_content = fds_get_produk_catalog_content();
            if (!empty($pk_content['seo_title'])) {
                return fds_clamp_meta_title((string) $pk_content['seo_title'], 60);
            }
        }
        return 'Katalog Produk Drone UAV Indonesia — Full Drone Solutions';
    }

    if (is_page('tentang-kami')) {
        return 'Tentang Kami — PT Karya Solusi Angkasa (Full Drone Solutions)';
    }

    if (is_page(['kontak', 'contact'])) {
        return 'Hubungi Kami — Konsultasi Resmi Full Drone Solutions';
    }

    if (is_page('bandingkan')) {
        if (function_exists(__NAMESPACE__ . '\\fds_get_bandingkan_content')) {
            $bk_content = fds_get_bandingkan_content();
            if (!empty($bk_content['seo_title'])) {
                return fds_clamp_meta_title((string) $bk_content['seo_title'], 60);
            }
        }
        return 'Bandingkan Spesifikasi Drone — Komparasi Lini UAV FDS';
    }

    if (is_home()) {
        return 'Newsroom & Blog — Inovasi Teknologi UAV Indonesia | FDS';
    }

    if (function_exists(__NAMESPACE__ . '\\fds_get_current_layanan_context') && ($layanan_ctx = fds_get_current_layanan_context()) !== null) {
        $post_id         = get_queried_object_id();
        $custom_rm_title = $post_id ? get_post_meta($post_id, 'rank_math_title', true) : '';
        if (!empty($custom_rm_title)) {
            return fds_decode_text((string) preg_replace('/^(beranda|home)\s*[-|–—:]\s*/iu', '', $title));
        }
        $seo_meta = $layanan_ctx['data']['seo'] ?? [];
        return fds_clamp_meta_title((string) ($seo_meta['meta_title'] ?? ($layanan_ctx['data']['h1'] ?? 'Layanan UAV FDS')), 60);
    }

    if (is_singular('drone')) {
        $drone_id        = get_the_ID();
        $custom_rm_title = get_post_meta($drone_id, 'rank_math_title', true);
        if (!empty($custom_rm_title)) {
            return fds_decode_text((string) preg_replace('/^(beranda|home)\s*[-|–—:]\s*/iu', '', $title));
        }

        $d_title    = fds_decode_text(get_the_title($drone_id));
        $model_base = trim((string) preg_replace('/\s*\(.*?\)/', '', $d_title)) ?: $d_title;
        $model_key  = function_exists(__NAMESPACE__ . '\\fds_get_drone_model_key') ? fds_get_drone_model_key($drone_id) : strtolower((string) get_post_field('post_name', $drone_id));
        $is_ferto   = (strpos($model_key, 'ferto-') === 0);
        $has_sni    = in_array($model_key, ['ferto-15l', 'ferto-22l'], true);
        $kategori   = fds_decode_text((string) (get_post_meta($drone_id, 'drone_kategori', true) ?: 'UAV Industri'));

        $cat_lower = strtolower($kategori);
        if ($is_ferto || strpos($cat_lower, 'agri') !== false) {
            return fds_clamp_meta_title($has_sni ? "{$model_base} — Drone Pertanian TKDN & SNI | FDS" : "{$model_base} — Drone Pertanian Presisi TKDN | FDS", 60);
        } elseif ($model_key === 'deltav' || strpos($cat_lower, 'peta') !== false) {
            return fds_clamp_meta_title("{$model_base} — Drone Mapping Fixed-Wing VTOL | FDS", 60);
        } elseif ($model_key === 'delfro' || strpos($cat_lower, 'kargo') !== false) {
            return fds_clamp_meta_title("{$model_base} — Drone Kargo Logistik Otonom | FDS", 60);
        } else {
            return fds_clamp_meta_title("{$model_base} — Drone Inspeksi & Surveillance Modular | FDS", 60);
        }
    }

    if (is_singular('post')) {
        $post_id         = get_the_ID();
        $custom_rm_title = get_post_meta($post_id, 'rank_math_title', true);
        if (!empty($custom_rm_title)) {
            return fds_decode_text((string) preg_replace('/^(beranda|home)\s*[-|–—:]\s*/iu', '', $title));
        }
        $p_title = fds_decode_text(get_the_title($post_id));
        return fds_clamp_meta_title("{$p_title} — Inovasi Drone Indonesia | FDS", 60);
    }

    return $title;
}, 99);

/**
 * Kendalikan robots meta secara presisi:
 * - Pertahankan noindex, follow untuk halaman utilitas dan arsip tipis (date, author, tag, paged, uncategorized, attachment)
 * - Hormati pengaturan manual noindex di metabox Rank Math bila disetel user
 * - Indeks halaman publik strategis secara penuh
 */
add_filter('rank_math/frontend/robots', function ($robots) {
    if (is_admin() || is_search() || is_404() || is_feed()) {
        return $robots;
    }

    if (
        is_page(['karir', 'disclaimer', 'terima-kasih'])
        || is_singular(['ot_header_builders', 'ot_footer_builders', 'product', 'download', 'e-landing-page', 'elementor_library'])
        || is_date()
        || is_author()
        || is_tag()
        || is_attachment()
        || is_paged()
        || is_category('uncategorized')
    ) {
        $robots['index']  = 'noindex';
        $robots['follow'] = 'follow';
        unset($robots['max-snippet'], $robots['max-image-preview'], $robots['max-video-preview']);
        return $robots;
    }

    if ((isset($robots['index']) && $robots['index'] === 'noindex') || in_array('noindex', $robots, true)) {
        unset($robots['max-snippet'], $robots['max-image-preview'], $robots['max-video-preview']);
        return $robots;
    }

    if (is_singular()) {
        $rm_robots = get_post_meta(get_queried_object_id(), 'rank_math_robots', true);
        if (is_array($rm_robots) && in_array('noindex', $rm_robots, true)) {
            $robots['index']  = 'noindex';
            $robots['follow'] = in_array('nofollow', $rm_robots, true) ? 'nofollow' : 'follow';
            return $robots;
        }
    } elseif (is_category() || is_tax()) {
        $rm_robots = get_term_meta(get_queried_object_id(), 'rank_math_robots', true);
        if (is_array($rm_robots) && in_array('noindex', $rm_robots, true)) {
            $robots['index']  = 'noindex';
            $robots['follow'] = in_array('nofollow', $rm_robots, true) ? 'nofollow' : 'follow';
            return $robots;
        }
    }

    $robots['index'] = 'index';
    unset($robots['noindex']);
    $robots['follow'] = 'follow';
    unset($robots['nofollow']);
    $robots['max-snippet']       = 'max-snippet:-1';
    $robots['max-image-preview'] = 'max-image-preview:large';
    $robots['max-video-preview'] = 'max-video-preview:-1';

    return $robots;
}, 99);

/**
 * Filter Rank Math Meta Description
 * Menjamin sub-deskripsi Google SERP rapi, terdekode bersih, dan <= 158 karakter.
 */
add_filter('rank_math/frontend/description', function ($description) {
    if (is_front_page()) {
        return 'PT Karya Solusi Angkasa (Full Drone Solutions), produsen UAV Indonesia No.1: seri FERTO TKDN 60,74%, pemetaan VTOL DELTAV, jasa LiDAR & GIS.';
    }

    if (is_tax('kategori_drone')) {
        $term      = get_queried_object();
        $term_slug = ($term && !empty($term->slug)) ? $term->slug : '';
        $term_name = ($term && !empty($term->name)) ? fds_decode_text($term->name) : 'Industri';
        if (function_exists(__NAMESPACE__ . '\\fds_get_kategori_drone_catalog_content')) {
            $kat_content = fds_get_kategori_drone_catalog_content($term_slug, $term);
            if (!empty($kat_content['seo_desc'])) {
                return fds_clamp_meta_desc((string) $kat_content['seo_desc'], 158);
            }
        }
        if ($term_slug === 'agrikultur') {
            return 'Katalog drone pertanian & sprayer presisi seri FERTO (5L–50L) berstandar TKDN 60,74% (SNI 9199:2023 pada FERTO 15 & 22) dan kendali FDS Station.';
        }
        if ($term_slug === 'pemetaan-gis') {
            return 'Katalog drone pemetaan Fixed-Wing VTOL DELTAV dan UAV MULTIPURPOSE untuk foto udara, survey LiDAR, inspeksi aset 150kV, dan pemetaan GIS.';
        }
        if ($term_slug === 'kargo') {
            return 'Platform drone kargo logistik DELFRO payload hingga 10 kg buatan Indonesia untuk distribusi medis darurat, suku cadang, & wilayah kepulauan.';
        }
        return fds_clamp_meta_desc("Katalog resmi drone {$term_name} buatan PT Karya Solusi Angkasa (Full Drone Solutions) untuk aplikasi industri.", 158);
    }

    if (is_page('produk')) {
        if (function_exists(__NAMESPACE__ . '\\fds_get_produk_catalog_content')) {
            $pk_content = fds_get_produk_catalog_content();
            if (!empty($pk_content['seo_desc'])) {
                return fds_clamp_meta_desc((string) $pk_content['seo_desc'], 158);
            }
        }
        return 'Katalog lengkap drone PT Karya Solusi Angkasa: sprayer FERTO (TKDN 60,74%), pemetaan VTOL DELTAV, kargo DELFRO, dan UAV modular MULTIPURPOSE.';
    }

    if (is_page('tentang-kami')) {
        return 'Profil PT Karya Solusi Angkasa (Full Drone Solutions), produsen drone & UAV Indonesia di Sleman, Yogyakarta dengan sistem manajemen mutu ISO 9001:2015.';
    }

    if (is_page(['kontak', 'contact'])) {
        return 'Hubungi tim PT Karya Solusi Angkasa untuk konsultasi drone pertanian, jasa survey LiDAR, jadwal demo terbang, dan servis resmi di Sleman, Yogyakarta.';
    }

    if (is_page('bandingkan')) {
        if (function_exists(__NAMESPACE__ . '\\fds_get_bandingkan_content')) {
            $bk_content = fds_get_bandingkan_content();
            if (!empty($bk_content['seo_desc'])) {
                return fds_clamp_meta_desc((string) $bk_content['seo_desc'], 158);
            }
        }
        return 'Bandingkan spesifikasi teknis drone sprayer FERTO (TKDN 60,74%), pemetaan VTOL DELTAV, MULTIPURPOSE, dan kargo DELFRO buatan PT Karya Solusi Angkasa.';
    }

    if (is_home()) {
        return 'Berita terkini, studi kasus operasional drone pertanian, survei pemetaan LiDAR tambang, dan riset teknologi industri UAV dari tim PT Karya Solusi Angkasa.';
    }

    if (function_exists(__NAMESPACE__ . '\\fds_get_current_layanan_context') && ($layanan_ctx = fds_get_current_layanan_context()) !== null) {
        $post_id        = get_queried_object_id();
        $custom_rm_desc = $post_id ? get_post_meta($post_id, 'rank_math_description', true) : '';
        if (!empty($custom_rm_desc)) {
            $clean_desc = trim((string) preg_replace('/^Deskripsi\s+Singkat\s*:\s*/i', '', (string) $custom_rm_desc));
            return fds_clamp_meta_desc($clean_desc, 155);
        }
        $seo_meta = $layanan_ctx['data']['seo'] ?? [];
        return fds_clamp_meta_desc((string) ($seo_meta['meta_desc'] ?? ($layanan_ctx['data']['hero_summary'] ?? '')), 155);
    }

    if (is_singular('drone')) {
        $drone_id       = get_the_ID();
        $d_detail       = function_exists(__NAMESPACE__ . '\\fds_get_drone_detail_data') ? fds_get_drone_detail_data($drone_id) : [];
        $model_key      = (string) ($d_detail['model_key'] ?? (function_exists(__NAMESPACE__ . '\\fds_get_drone_model_key') ? fds_get_drone_model_key($drone_id) : strtolower((string) get_post_field('post_name', $drone_id))));
        $is_ferto       = !empty($d_detail['is_ferto']) || (strpos($model_key, 'ferto-') === 0);
        $has_sni        = !empty($d_detail['has_sni']) || in_array($model_key, ['ferto-15l', 'ferto-22l'], true);
        $custom_rm_desc = get_post_meta($drone_id, 'rank_math_description', true);
        if (!empty($custom_rm_desc)) {
            $clean_desc = trim((string) preg_replace('/^Deskripsi\s+Singkat\s*:\s*/i', '', (string) $custom_rm_desc));
            $clean_desc = str_ireplace('full frone solutions', 'Full Drone Solutions', $clean_desc);
            if (function_exists('fds_scrub_legacy_text')) {
                $clean_desc = \fds_scrub_legacy_text($clean_desc, $is_ferto, $has_sni);
            }
            return fds_clamp_meta_desc($clean_desc, 158);
        }
        $d_title    = fds_decode_text((string) ($d_detail['name'] ?? get_the_title($drone_id)));
        $model_base = trim((string) preg_replace('/\s*\(.*?\)/', '', $d_title)) ?: $d_title;
        $payload    = fds_decode_text((string) ($d_detail['card_payload'] ?? get_post_meta($drone_id, 'drone_spec_kapasitas', true)));
        $durasi     = fds_decode_text((string) ($d_detail['card_metric_value'] ?? get_post_meta($drone_id, 'drone_spec_durasi', true)));
        $kategori   = fds_decode_text((string) ($d_detail['kategori'] ?? (get_post_meta($drone_id, 'drone_kategori', true) ?: 'UAV Industri')));
        if (function_exists('fds_scrub_legacy_text')) {
            $payload  = \fds_scrub_legacy_text($payload, $is_ferto, $has_sni);
            $durasi   = \fds_scrub_legacy_text($durasi, $is_ferto, $has_sni);
            $kategori = \fds_scrub_legacy_text($kategori, $is_ferto, $has_sni);
        }
        $cat_lower  = strtolower($kategori);
        $specs_bit  = implode(', ', array_filter([$payload !== '' ? "kapasitas {$payload}" : '', $durasi !== '' ? "durasi {$durasi}" : '']));
        $specs_txt  = $specs_bit !== '' ? $specs_bit : 'spesifikasi industri resmi';

        if ($is_ferto || strpos($cat_lower, 'agri') !== false) {
            if ($has_sni) {
                return fds_clamp_meta_desc("Spesifikasi drone pertanian {$model_base} ({$specs_txt}). Sertifikat TKDN 60,74% & SNI 9199:2023 buatan PT Karya Solusi Angkasa.", 158);
            }
            return fds_clamp_meta_desc("Spesifikasi drone pertanian {$model_base} ({$specs_txt}). Sertifikat TKDN 60,74% & kendali FDS Station buatan PT Karya Solusi Angkasa.", 158);
        } elseif ($model_key === 'deltav' || strpos($cat_lower, 'peta') !== false) {
            return fds_clamp_meta_desc("Spesifikasi drone pemetaan Fixed-Wing VTOL {$model_base} ({$specs_txt}) untuk survei LiDAR & GIS buatan Full Drone Solutions.", 158);
        } elseif ($model_key === 'delfro' || strpos($cat_lower, 'kargo') !== false) {
            return fds_clamp_meta_desc("Spesifikasi drone kargo {$model_base} ({$specs_txt}) untuk pengiriman logistik & medis darurat buatan Full Drone Solutions.", 158);
        }
        return fds_clamp_meta_desc("Spesifikasi {$model_base} ({$specs_txt}) platform UAV modular dengan dukungan kamera termal & LiDAR buatan Full Drone Solutions.", 158);
    }

    if (is_singular('post')) {
        $post_id        = get_the_ID();
        $custom_rm_desc = get_post_meta($post_id, 'rank_math_description', true);
        if (!empty($custom_rm_desc)) {
            $clean_desc = trim((string) preg_replace('/^Deskripsi\s+Singkat\s*:\s*/i', '', (string) $custom_rm_desc));
            return fds_clamp_meta_desc(str_ireplace('full frone solutions', 'Full Drone Solutions', $clean_desc), 158);
        }
        $post_obj = get_post($post_id);
        if ($post_obj) {
            $excerpt = get_the_excerpt($post_obj) ?: wp_trim_words($post_obj->post_content, 25);
            if (!empty($excerpt)) {
                return fds_clamp_meta_desc($excerpt, 158);
            }
        }
    }

    if (!empty($description)) {
        $description = trim((string) preg_replace('/^Deskripsi\s+Singkat\s*:\s*/i', '', (string) $description));
        $description = fds_decode_text(str_ireplace('full frone solutions', 'Full Drone Solutions', $description));
    }

    if (empty($description)) {
        $seo = fds_get_seo_data();
        if (!empty($seo['description'])) {
            return fds_decode_text($seo['description']);
        }
    }

    return $description;
}, 99);

/**
 * Pastikan OpenGraph & Twitter Card memakai Title, Description & Site Name yang seragam.
 */
add_filter('rank_math/opengraph/facebook/og_title', function ($og_title) {
    return apply_filters('rank_math/frontend/title', $og_title);
}, 99);

add_filter('rank_math/opengraph/facebook/title', function ($og_title) {
    return apply_filters('rank_math/frontend/title', $og_title);
}, 99);

add_filter('rank_math/opengraph/facebook/og_description', function ($og_desc) {
    return apply_filters('rank_math/frontend/description', $og_desc);
}, 99);

add_filter('rank_math/opengraph/facebook/description', function ($og_desc) {
    return apply_filters('rank_math/frontend/description', $og_desc);
}, 99);

add_filter('rank_math/opengraph/facebook/og_site_name', function () {
    return 'Full Drone Solutions';
}, 99);

add_filter('rank_math/opengraph/twitter/twitter_title', function ($tw_title) {
    return apply_filters('rank_math/frontend/title', $tw_title);
}, 99);

add_filter('rank_math/opengraph/twitter/title', function ($tw_title) {
    return apply_filters('rank_math/frontend/title', $tw_title);
}, 99);

add_filter('rank_math/opengraph/twitter/twitter_description', function ($tw_desc) {
    return apply_filters('rank_math/frontend/description', $tw_desc);
}, 99);

add_filter('rank_math/opengraph/twitter/description', function ($tw_desc) {
    return apply_filters('rank_math/frontend/description', $tw_desc);
}, 99);

add_filter('rank_math/frontend/canonical', function ($canonical) {
    if (function_exists(__NAMESPACE__ . '\\fds_get_current_layanan_context') && ($layanan_ctx = fds_get_current_layanan_context()) !== null) {
        $post_id      = get_queried_object_id();
        $custom_canon = $post_id ? get_post_meta($post_id, 'rank_math_canonical', true) : '';
        if (empty($custom_canon) && !empty($layanan_ctx['data']['url'])) {
            return trailingslashit((string) $layanan_ctx['data']['url']);
        }
    }
    return $canonical;
}, 99);

/**
 * Bridge Custom Template Content into Rank Math Analyzer
 * Membantu Content Analyzer Rank Math membaca konten kustom Beranda, Tentang Kami, Bandingkan, Kontak, dan Layanan.
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) {
        return;
    }
    global $post;
    if (!$post || $post->post_type !== 'page') {
        return;
    }

    $parts    = [];
    $front_id = (int) get_option('page_on_front');

    if ((int) $post->ID === $front_id || $post->post_name === 'beranda') {
        $hp = function_exists('App\fds_get_homepage_content') ? fds_get_homepage_content() : [];
        foreach ([
            'hero_title', 'hero_desc', 'solusi_title', 'solusi_desc',
            'produk_title', 'produk_desc', 'keunggulan_title',
            'keunggulan_card1_title', 'keunggulan_card1_desc',
            'keunggulan_card2_desc', 'keunggulan_card3_title', 'keunggulan_card3_desc',
            'keunggulan_card4_desc', 'keunggulan_card5_title', 'keunggulan_card5_desc',
            'keunggulan_card6_desc', 'keunggulan_card7_title', 'keunggulan_card7_desc',
            'layanan_title', 'layanan_desc', 'kontak_title', 'kontak_desc',
        ] as $k) {
            if (!empty($hp[$k])) {
                $parts[] = $hp[$k];
            }
        }
        if (function_exists('App\fds_get_solusi_data')) {
            $sol = fds_get_solusi_data();
            foreach (($sol['cards'] ?? []) as $c) {
                $parts[] = ($c['title'] ?? '') . ' ' . ($c['desc'] ?? '');
            }
        }
        if (function_exists('App\fds_get_layanan_items')) {
            foreach (fds_get_layanan_items() as $l) {
                $parts[] = ($l['title'] ?? '') . ' ' . ($l['desc'] ?? '');
            }
        }
    } elseif ($post->post_name === 'tentang-kami') {
        $ab = function_exists('App\fds_get_about_content') ? fds_get_about_content() : [];
        foreach ([
            'hero_title', 'hero_desc', 'story_title', 'story_p1', 'story_p2', 'story_p3', 'story_p4',
            'spektrum_title', 'spektrum1_title', 'spektrum1_desc', 'spektrum2_title', 'spektrum2_desc', 'spektrum3_title', 'spektrum3_desc',
            'mitra_title', 'mitra_desc', 'mitra_item1_name', 'mitra_item1_desc', 'mitra_item2_name', 'mitra_item2_desc',
            'mitra_item3_name', 'mitra_item3_desc', 'mitra_item4_name', 'mitra_item4_desc',
            'certs_title', 'cert1_desc', 'cert2_desc', 'cert3_desc', 'cta_title', 'cta_desc', 'info_alamat',
        ] as $k) {
            if (!empty($ab[$k])) {
                $parts[] = $ab[$k];
            }
        }
    } elseif ($post->post_name === 'bandingkan') {
        $bk = function_exists('App\fds_get_bandingkan_content') ? fds_get_bandingkan_content() : [];
        if (!empty($bk)) {
            $parts[] = ($bk['hero_title'] ?? '') . ' ' . ($bk['hero_desc'] ?? '') . ' ' . ($bk['specs_title'] ?? '') . ' ' . ($bk['specs_desc'] ?? '') . ' ' . ($bk['uc_title'] ?? '') . ' ' . ($bk['uc_desc'] ?? '') . ' ' . ($bk['guide_title'] ?? '') . ' ' . ($bk['guide_lead'] ?? '') . ' ' . wp_strip_all_tags((string) ($bk['guide_html'] ?? ''));
        } else {
            $parts[] = 'Bandingkan Spesifikasi Drone Pertanian FERTO 5L FERTO 10L FERTO 15L FERTO 22L FERTO 30L FERTO 50L (TKDN 60,74% serta SNI 9199:2023 pada FERTO 15 & 22), Drone Pemetaan VTOL DELTAV, MULTIPURPOSE, dan Drone Kargo DELFRO produksi PT Karya Solusi Angkasa (Full Drone Solutions) berstandar sistem manajemen mutu perusahaan ISO 9001:2015.';
        }
    } elseif (in_array($post->post_name, ['kontak', 'contact'], true)) {
        $gc = function_exists('App\fds_get_global_contact') ? fds_get_global_contact() : [];
        $parts[] = ($gc['page_kontak_title'] ?? '') . ' ' . ($gc['page_kontak_desc'] ?? '') . ' ' . ($gc['address'] ?? '');
    } elseif ($post->post_name === 'layanan' && function_exists('App\fds_get_layanan_hub_data')) {
        $hub = fds_get_layanan_hub_data();
        $parts[] = ($hub['h1'] ?? '') . ' ' . ($hub['subtitle'] ?? '') . ' ' . ($hub['hero_summary'] ?? '');
        foreach (($hub['intro_paragraphs'] ?? []) as $p) {
            $parts[] = $p;
        }
        if (!empty($hub['challenge_section'])) {
            $parts[] = ($hub['challenge_section']['title'] ?? '') . ' ' . implode(' ', $hub['challenge_section']['paragraphs'] ?? []);
            foreach (($hub['challenge_section']['pain_points'] ?? []) as $pp) {
                $parts[] = ($pp['title'] ?? '') . ' ' . ($pp['desc'] ?? '');
            }
        }
        foreach (($hub['why_fds']['items'] ?? []) as $wi) {
            $parts[] = ($wi['title'] ?? '') . ' ' . ($wi['desc'] ?? '');
        }
        if (!empty($hub['method_comparison']['rows'])) {
            $parts[] = ($hub['method_comparison']['title'] ?? '') . ' ' . ($hub['method_comparison']['subtitle'] ?? '');
            foreach ($hub['method_comparison']['rows'] as $row) {
                $parts[] = implode(' ', $row);
            }
        }
        foreach (($hub['workflow']['steps'] ?? []) as $st) {
            $parts[] = ($st['title'] ?? '') . ' ' . ($st['desc'] ?? '');
        }
        if (!empty($hub['cost_framework'])) {
            $parts[] = ($hub['cost_framework']['title'] ?? '') . ' ' . ($hub['cost_framework']['intro'] ?? '');
            foreach (($hub['cost_framework']['factors'] ?? []) as $cf) {
                $parts[] = ($cf['name'] ?? '') . ' ' . ($cf['desc'] ?? '');
            }
            $parts[] = implode(' ', $hub['cost_framework']['kak_checklist'] ?? []);
        }
        if (!empty($hub['eeat_proof'])) {
            $parts[] = ($hub['eeat_proof']['title'] ?? '') . ' ' . ($hub['eeat_proof']['intro'] ?? '') . ' ' . implode(' ', $hub['eeat_proof']['bullets'] ?? []);
        }
        foreach (($hub['faqs'] ?? []) as $fq) {
            $parts[] = ($fq['q'] ?? '') . ' ' . ($fq['a'] ?? '');
        }
        if (function_exists('App\fds_get_layanan_sectors')) {
            foreach (fds_get_layanan_sectors() as $sec) {
                $parts[] = ($sec['card_title'] ?? '') . ' ' . ($sec['card_desc'] ?? '');
            }
        }
        if (function_exists('App\fds_get_layanan_categories')) {
            foreach (fds_get_layanan_categories() as $cat) {
                $parts[] = ($cat['short_title'] ?? '') . ' ' . ($cat['hero_summary'] ?? '');
            }
        }
    } elseif (function_exists('App\fds_get_layanan_sectors') && isset(fds_get_layanan_sectors()[$post->post_name])) {
        $sec = fds_get_layanan_sectors()[$post->post_name];
        $parts[] = ($sec['h1'] ?? '') . ' ' . ($sec['subtitle'] ?? '') . ' ' . ($sec['hero_summary'] ?? '');
        foreach (array_merge($sec['intro_paragraphs'] ?? [], $sec['closing_paragraphs'] ?? []) as $p) {
            $parts[] = $p;
        }
        if (!empty($sec['challenge_section'])) {
            $parts[] = ($sec['challenge_section']['title'] ?? '') . ' ' . implode(' ', $sec['challenge_section']['paragraphs'] ?? []);
            foreach (($sec['challenge_section']['pain_points'] ?? []) as $pp) {
                $parts[] = ($pp['title'] ?? '') . ' ' . ($pp['desc'] ?? '');
            }
        }
        foreach (($sec['solutions'] ?? []) as $sol_item) {
            $parts[] = ($sol_item['name'] ?? '') . ' ' . ($sol_item['desc'] ?? '');
        }
        if (!empty($sec['method_comparison']['rows'])) {
            $parts[] = ($sec['method_comparison']['title'] ?? '') . ' ' . ($sec['method_comparison']['subtitle'] ?? '');
            foreach ($sec['method_comparison']['rows'] as $row) {
                $parts[] = implode(' ', $row);
            }
        }
        foreach (($sec['deliverable_groups'] ?? []) as $grp) {
            $parts[] = ($grp['title'] ?? '') . ' ' . implode(' ', $grp['items'] ?? []);
        }
        foreach (($sec['workflow_steps'] ?? []) as $st) {
            $parts[] = ($st['title'] ?? '') . ' ' . ($st['desc'] ?? '');
        }
        if (!empty($sec['software_integrations'])) {
            $parts[] = ($sec['software_integrations']['title'] ?? '') . ' ' . implode(' ', $sec['software_integrations']['formats'] ?? []) . ' ' . implode(' ', $sec['software_integrations']['tools'] ?? []);
        }
        if (!empty($sec['cost_framework'])) {
            $parts[] = ($sec['cost_framework']['title'] ?? '') . ' ' . ($sec['cost_framework']['intro'] ?? '');
            foreach (($sec['cost_framework']['factors'] ?? []) as $cf) {
                $parts[] = ($cf['name'] ?? '') . ' ' . ($cf['desc'] ?? '');
            }
            $parts[] = implode(' ', $sec['cost_framework']['kak_checklist'] ?? []);
        }
        if (!empty($sec['eeat_proof'])) {
            $parts[] = ($sec['eeat_proof']['title'] ?? '') . ' ' . ($sec['eeat_proof']['intro'] ?? '') . ' ' . implode(' ', $sec['eeat_proof']['bullets'] ?? []);
        }
        foreach (($sec['faqs'] ?? []) as $fq) {
            $parts[] = ($fq['q'] ?? '') . ' ' . ($fq['a'] ?? '');
        }
    } elseif (function_exists('App\fds_get_layanan_categories') && isset(fds_get_layanan_categories()[$post->post_name])) {
        $cat = fds_get_layanan_categories()[$post->post_name];
        $parts[] = ($cat['h1'] ?? '') . ' ' . ($cat['subtitle'] ?? '') . ' ' . ($cat['hero_summary'] ?? '');
        foreach (($cat['intro_paragraphs'] ?? []) as $p) {
            $parts[] = $p;
        }
        if (!empty($cat['challenge_section'])) {
            $parts[] = ($cat['challenge_section']['title'] ?? '') . ' ' . implode(' ', $cat['challenge_section']['paragraphs'] ?? []);
            foreach (($cat['challenge_section']['pain_points'] ?? []) as $pp) {
                $parts[] = ($pp['title'] ?? '') . ' ' . ($pp['desc'] ?? '');
            }
        }
        foreach (($cat['capabilities'] ?? []) as $cap) {
            $parts[] = ($cap['name'] ?? '') . ' ' . ($cap['desc'] ?? '');
        }
        if (!empty($cat['method_comparison']['rows'])) {
            $parts[] = ($cat['method_comparison']['title'] ?? '') . ' ' . ($cat['method_comparison']['subtitle'] ?? '');
            foreach ($cat['method_comparison']['rows'] as $row) {
                $parts[] = implode(' ', $row);
            }
        }
        foreach (($cat['outputs'] ?? []) as $out_item) {
            $parts[] = $out_item;
        }
        foreach (($cat['workflow_steps'] ?? []) as $st) {
            $parts[] = ($st['title'] ?? '') . ' ' . ($st['desc'] ?? '');
        }
        if (!empty($cat['software_integrations'])) {
            $parts[] = ($cat['software_integrations']['title'] ?? '') . ' ' . implode(' ', $cat['software_integrations']['formats'] ?? []) . ' ' . implode(' ', $cat['software_integrations']['tools'] ?? []);
        }
        if (!empty($cat['cost_framework'])) {
            $parts[] = ($cat['cost_framework']['title'] ?? '') . ' ' . ($cat['cost_framework']['intro'] ?? '');
            foreach (($cat['cost_framework']['factors'] ?? []) as $cf) {
                $parts[] = ($cf['name'] ?? '') . ' ' . ($cf['desc'] ?? '');
            }
            $parts[] = implode(' ', $cat['cost_framework']['kak_checklist'] ?? []);
        }
        if (!empty($cat['eeat_proof'])) {
            $parts[] = ($cat['eeat_proof']['title'] ?? '') . ' ' . ($cat['eeat_proof']['intro'] ?? '') . ' ' . implode(' ', $cat['eeat_proof']['bullets'] ?? []);
        }
        foreach (($cat['faqs'] ?? []) as $fq) {
            $parts[] = ($fq['q'] ?? '') . ' ' . ($fq['a'] ?? '');
        }
    }

    $extra_text = trim(implode(' ', $parts));
    if ($extra_text !== '') {
        $clean_extra_json = wp_json_encode(fds_decode_text($extra_text), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        wp_add_inline_script('rank-math-analyzer', "
            if (window.wp && window.wp.hooks) {
                window.wp.hooks.addFilter('rank_math_content', 'fds_theme_bridge', function(content) {
                    return content + ' ' + {$clean_extra_json};
                });
            }
        ", 'after');
    }
});

/**
 * Ensure CPT 'drone' is enabled in Rank Math XML Sitemaps & clean legacy zombie pages from page-sitemap.xml
 */
add_action('init', function () {
    if (!get_option('fds_rm_drone_sitemap_enabled_v1')) {
        $rm_sitemap = get_option('rank-math-options-sitemap');
        if (is_array($rm_sitemap)) {
            $rm_sitemap['pt_drone_sitemap'] = 'on';
            $rm_sitemap['tax_kategori_drone_sitemap'] = 'on';
            update_option('rank-math-options-sitemap', $rm_sitemap);
            update_option('fds_rm_drone_sitemap_enabled_v1', 1, false);
        }
    }
}, 30);

add_filter('rank_math/sitemap/enable_cpt', function ($enabled, $post_type) {
    if ($post_type === 'drone') {
        return true;
    }
    return $enabled;
}, 10, 2);

add_filter('rank_math/sitemap/post_type_archive', function ($include, $post_type) {
    if ($post_type === 'drone') {
        return false;
    }
    return $include;
}, 10, 2);

/**
 * Daftar halaman Elementor/WooCommerce lama (2017–2025) yang menyebabkan Kanibalisasi Keyword "drone pertanian"
 * dan dialihkan (301 Redirect) ke halaman resmi baru.
 */
function fds_get_legacy_zombie_redirect_map(): array {
    return [
        // Redirect ke katalog produk resmi (/produk/)
        'drone'                                                                         => '/produk/',
        'drone/rebo'                                                                    => '/produk/',
        'rebo'                                                                          => '/produk/',
        'drone-reboisasi'                                                               => '/produk/',
        'kategori-drone/pertanian'                                                      => '/kategori-drone/agrikultur/',

        // Redirect ke halaman produk FERTO (5L, 10L, 15L, 22L)
        'drone-pertanian-ferto-5'                                                       => '/drone/ferto-5l/',
        'ferto-draft'                                                                   => '/drone/ferto-5l/',
        'drone-pertanian-ferto-10-terbaik-2023'                                         => '/drone/ferto-10l/',
        'drone-pertanian-agrea-15'                                                      => '/drone/ferto-15l/',
        'drone-pertanian-agrea-10'                                                      => '/drone/ferto-10l/',
        'drone/agrea-15'                                                                => '/drone/ferto-15l/',
        'drone/agrea-10'                                                                => '/drone/ferto-10l/',
        'agrea-15'                                                                      => '/drone/ferto-15l/',
        'agrea-10'                                                                      => '/drone/ferto-10l/',
        'alat-semprot-pertanian-modern-cairan-pestisida-dan-pupuk-ag15'                 => '/drone/ferto-15l/',
        'drone-pertanian-15-liter-sprayer-pestisida-pupuk-a415-agriculture-harga-efisien' => '/drone/ferto-15l/',
        'drone/ferto-20l'                                                               => '/drone/ferto-15l/',
        'drone/ferto-20'                                                                => '/drone/ferto-15l/',
        'ferto-20l'                                                                     => '/drone/ferto-15l/',
        'ferto-20'                                                                      => '/drone/ferto-15l/',

        // Redirect ke halaman produk DELTAV & layanan pemetaan resmi (/layanan/*)
        'drone-pemetaan'                                                                => '/drone/deltav/',
        'drone-fixed-wing-x8-uav-untuk-pemetaan-udara-harga-terbaik'                    => '/drone/deltav/',
        'harga-jasa-pemetaan-udara'                                                     => '/layanan/jasa-foto-udara-lidar/',
        'jasa-pemetaan-survey-agriculture'                                              => '/layanan/perkebunan-pertanian/',
        'jasa-pemetaan-udara-dan-drone-agriculture-pertanian'                           => '/layanan/perkebunan-pertanian/',
        'jual-drone-pemetaan-udara-agriculture-pertanian-murah-berkualitas'             => '/drone/deltav/',
        'jual-drone-untuk-pemetaan-udara-dan-agriculture'                               => '/drone/deltav/',

        // Redirect ke Blog & Kontak
        'blogfds'                                                                       => '/blog/',
        'a9365-tech-lab-news'                                                           => '/blog/',
        '5-rekomendasi-jenis-drone-terbaik-untuk-pemetaan-udara-yang-harus-anda-ketahui' => '/blog/',
        'inovasi-pertanian-modern-di-indonesia-smart-farming-drone-agriculture'         => '/blog/',
        'tutorial-membuat-peta-otmosaic-3d-dengan-software-agisoft'                     => '/blog/',
        'contact-1'                                                                     => '/kontak/',

        // Redirect katalog/pricing/perbandingan lama ke /produk/
        '3-rekomendasi-drone-untuk-pertanian-atau-agriculture-paling-efisien'           => '/produk/',
        'daftar-harga-drone-penyemprot-pestisida-dan-sprayer-2019'                      => '/produk/',

        // Redirect halaman duplikat Beranda & Landing Page Elementor lama ke Beranda Utama (/)
        // atau ke halaman Layanan spesifik (/layanan/*) bila intensi pencariannya adalah jasa/pelatihan
        'produsen-drone-pertanian-no-1-buatan-indonesia-bertkdn'                        => '/',
        'drone-pertanian-sprayer-spreader-buatan-indonesia-terbaik'                     => '/',
        'drone-sprayer-pertanian-fds-ferto-series'                                      => '/',
        'drone-pertanian-indonesia'                                                     => '/',
        'drone-sprayer-pestisida-untuk-pertanian-buatan-indonesia-harga-efisien'        => '/',
        'halaman-utama'                                                                 => '/',
        'home-2'                                                                        => '/',
        'home2-draft'                                                                   => '/',
        'pertanian-draft'                                                               => '/',
        'jasa-semprot-sawah-fds'                                                        => '/layanan/perkebunan-pertanian/',
        'bisnis-penyewaan-drone-pertanian-terbaik'                                      => '/',
        'bisnis-penyewaan-drone-pertanian-terpercaya'                                   => '/',
        'bisnis-penyewaan-drone'                                                        => '/',
        'mitra-usaha-pertanian'                                                         => '/',
        'persewaan-fds'                                                                 => '/',
        'produk-drone-pemetaan-dan-agriculture'                                         => '/',
        'jual-drone-pemetaan-dan-agriculture-paling-efisien'                            => '/',
        'jual-drone-pemetaan-atau-pertanian-agriculture-fitur-dan-harga-terbaik'        => '/',
        'pelatihan'                                                                     => '/layanan/pelatihan-sertifikasi/',
        'pelatihan-pilot-drone-bersertifikat-indonesia-terpercaya'                      => '/layanan/pelatihan-sertifikasi/',
        'pelatihatraining-fds-2024'                                                     => '/layanan/pelatihan-sertifikasi/',
        'training-2024-fds'                                                             => '/layanan/pelatihan-sertifikasi/',
        'daftar-harga-training-full-drone-solutions'                                    => '/layanan/pelatihan-sertifikasi/',
        'drone-edukasi'                                                                 => '/',
        'portfolio'                                                                     => '/',
        'shop'                                                                          => '/',
        'cart-2'                                                                        => '/',
        'checkout-2'                                                                    => '/',
        'my-account'                                                                    => '/',
        'my-account-2'                                                                  => '/',
        'sample-page'                                                                   => '/',
        '13759-2'                                                                       => '/',
        'a9365-tech-lab-service-details'                                                => '/layanan/',
        'a9365-tech-lab-services'                                                       => '/layanan/',
    ];
}

// Hapus halaman zombie lama, utilitas (karir, disclaimer, terima-kasih), CPT internal/privat, uncategorized, term kosong, agrea/rebo, dan arsip CPT drone dari sitemap XML Rank Math
add_filter('rank_math/sitemap/entry', function ($url, $type, $object) {
    $utility_exclude      = ['karir', 'disclaimer', 'terima-kasih'];
    $internal_post_types  = [
        'fds_inquiry',
        'mitra',
        'attachment',
        'revision',
        'nav_menu_item',
        'wp_navigation',
        'ot_header_builders',
        'ot_footer_builders',
        'product',
        'download',
        'e-landing-page',
        'elementor_library',
    ];

    if (is_object($object)) {
        if (isset($object->post_status) && $object->post_status !== 'publish') {
            return false;
        }
        if (isset($object->post_type) && in_array($object->post_type, $internal_post_types, true)) {
            return false;
        }
        if ($type === 'post' && !empty($object->ID)) {
            $rm_robots = get_post_meta((int) $object->ID, 'rank_math_robots', true);
            if (is_array($rm_robots) && in_array('noindex', $rm_robots, true)) {
                return false;
            }
        }
        $slug = strtolower((string) ($object->post_name ?? ($object->slug ?? '')));
        if (in_array($slug, $utility_exclude, true)) {
            return false;
        }
        if ($type === 'term') {
            $taxonomy = (string) ($object->taxonomy ?? '');
            if ($taxonomy === 'category' && ($slug === 'uncategorized' || ((int) ($object->count ?? 0)) <= 0)) {
                return false;
            }
            if ($taxonomy === 'kategori_drone' && ((int) ($object->count ?? 0)) <= 0) {
                return false;
            }
            if (!empty($object->term_id)) {
                $rm_term_robots = get_term_meta((int) $object->term_id, 'rank_math_robots', true);
                if (is_array($rm_term_robots) && in_array('noindex', $rm_term_robots, true)) {
                    return false;
                }
            }
        }
        if ($slug === 'rebo' || $slug === 'reboisasi' || strpos($slug, 'agrea') !== false || strpos($slug, 'ferto-20') !== false) {
            return false;
        }
        if (isset($object->post_type) && $object->post_type !== 'post' && strpos($slug, 'rebo') !== false) {
            return false;
        }
        $zombie_map = fds_get_legacy_zombie_redirect_map();
        if (isset($zombie_map[$slug])) {
            return false;
        }
    }

    $check_url = is_array($url) ? ($url['loc'] ?? '') : (is_string($url) ? $url : '');
    if ($check_url !== '') {
        $parsed_path = strtolower(trim(wp_parse_url($check_url, PHP_URL_PATH) ?? '', '/'));
        if (
            $parsed_path === 'drone'
            || $parsed_path === 'category/uncategorized'
            || in_array($parsed_path, $utility_exclude, true)
            || preg_match('#^(drone/|kategori-drone/)?(rebo|reboisasi)/?$#i', $parsed_path)
            || preg_match('#^(drone/)?(drone-pertanian-)?agrea(-?\d+)?/?$#i', $parsed_path)
            || preg_match('#^(drone/)?ferto-20l?/?$#i', $parsed_path)
        ) {
            return false;
        }
        $zombie_map = fds_get_legacy_zombie_redirect_map();
        if (isset($zombie_map[$parsed_path])) {
            return false;
        }
    }
    return $url;
}, 10, 3);

// Kecualikan halaman utilitas (/terima-kasih/, /karir/, /disclaimer/) & zombie dari WP Core Sitemap (/wp-sitemap.xml)
add_filter('wp_sitemaps_posts_query_args', function ($args, $post_type) {
    if ($post_type !== 'page') {
        return $args;
    }
    $exclude_slugs = ['karir', 'disclaimer', 'terima-kasih'];
    $exclude_ids   = [];
    foreach ($exclude_slugs as $slug) {
        $p = get_page_by_path($slug);
        if ($p && !empty($p->ID)) {
            $exclude_ids[] = (int) $p->ID;
        }
    }
    if (!empty($exclude_ids)) {
        $existing_not_in  = isset($args['post__not_in']) && is_array($args['post__not_in']) ? $args['post__not_in'] : [];
        $args['post__not_in'] = array_values(array_unique(array_merge($existing_not_in, $exclude_ids)));
    }
    return $args;
}, 10, 2);

// Pastikan wp_robots core ikut menghormati noindex pada /terima-kasih/, /karir/, /disclaimer/ bila Rank Math tidak aktif
add_filter('wp_robots', function ($robots) {
    if (is_page(['karir', 'disclaimer', 'terima-kasih'])) {
        $robots['noindex'] = true;
        $robots['follow']  = true;
        unset($robots['index'], $robots['max-image-preview']);
    }
    return $robots;
}, 99);

/**
 * Pembersihan Otomatis Konten Warisan Lawas (2019-2023):
 * 1. Menghapus / me-rename halaman "Drone Pertanian Agrea 15" (ID 13821) dan "Agrea 10" (ID 15297) yang dibuat pada 2020-2023
 *    agar kata kunci kompetitor (Agrea / Agras) bersih 100% dari database.
 * 2. Memindahkan artikel DJI impor lawas tahun 2019 ke Trash.
 * 3. Mengarsipkan dan menghapus sisa data REBO dari sitemap.
 */
add_action('init', function () {
    if (!get_option('fds_clean_agrea_dji_legacy_posts_v2')) {
        // 1. Rename & Trash Agrea 15 (Page 13821, dibuat 29 Des 2020)
        if ($p13821 = get_post(13821)) {
            wp_update_post([
                'ID'          => 13821,
                'post_title'  => 'FERTO 15 — Drone Pertanian TKDN Resmi FDS',
                'post_status' => 'trash',
            ]);
        }

        // 2. Rename & Trash Agrea 10 (Page 15297, dibuat 1 Agu 2023)
        if ($p15297 = get_post(15297)) {
            wp_update_post([
                'ID'          => 15297,
                'post_title'  => 'FERTO 10 — Drone Pertanian TKDN Resmi FDS',
                'post_status' => 'trash',
            ]);
        }

        // 3. Trash artikel DJI impor lawas tahun 2019 (ID 2618, 3127, 2970, 1638)
        foreach ([2618, 3127, 2970, 1638] as $dji_id) {
            if (get_post($dji_id)) {
                wp_update_post([
                    'ID'          => $dji_id,
                    'post_status' => 'trash',
                ]);
            }
        }

        update_option('fds_clean_agrea_dji_legacy_posts_v2', 1, false);
    }

    if (!get_option('fds_clean_rebo_trash_v2')) {
        $rebo_posts = get_posts([
            'name'        => 'rebo',
            'post_type'   => 'any',
            'post_status' => 'any',
        ]);
        foreach ($rebo_posts as $rp) {
            wp_update_post([
                'ID'          => $rp->ID,
                'post_name'   => 'rebo-archived-' . $rp->ID,
                'post_status' => 'trash',
            ]);
        }
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%rank_math_sitemap%' OR option_name LIKE '%rm_sm_%'");
        update_option('fds_clean_rebo_trash_v2', 1, false);
    }

    if (!get_option('fds_seo_cleanup_v7')) {
        if (!get_option('fds_layanan_pages_seeded_v1') && function_exists(__NAMESPACE__ . '\\fds_ensure_layanan_pages')) {
            fds_ensure_layanan_pages(true);
        }

        global $wpdb;

        // Trash artikel pricing/kompetitor lawas 2019-2021 (ID 1797 Daftar Harga 2019, ID 35 Jual Murah 2021)
        foreach ([1797, 35] as $legacy_post_id) {
            if (get_post($legacy_post_id)) {
                wp_update_post([
                    'ID'          => $legacy_post_id,
                    'post_status' => 'trash',
                ]);
            }
        }

        // Pastikan seluruh post/page/drone dengan slug mengandung "agrea" atau "rebo" bersih ke trash & rename slug
        $agrea_like = '%' . $wpdb->esc_like('agrea') . '%';
        $legacy_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type IN ('post', 'page', 'drone') AND post_status != 'trash' AND (LOWER(post_name) LIKE %s OR LOWER(post_name) = 'rebo')",
            $agrea_like
        ));
        if (!empty($legacy_ids)) {
            foreach ($legacy_ids as $lid) {
                $p_obj = get_post((int) $lid);
                if ($p_obj) {
                    wp_update_post([
                        'ID'          => $p_obj->ID,
                        'post_name'   => sanitize_title($p_obj->post_name) . '-archived-' . $p_obj->ID,
                        'post_status' => 'trash',
                    ]);
                }
            }
        }

        // Bersihkan cache transient sitemap Rank Math, mega menu, homepage content, katalog bandingkan, & LiteSpeed Cache
        // (post_modified tidak diubah secara massal agar sinyal kesegaran konten tetap akurat)
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%rank_math_sitemap%' OR option_name LIKE '%rm_sm_%'");
        delete_transient('fds_mega_menu_data');
        delete_transient('fds_mega_menu_data_v2');
        delete_transient('fds_bandingkan_catalog_cache');
        delete_transient('fds_bandingkan_catalog_cache_v2');
        delete_transient('fds_homepage_content_cache');
        delete_transient('fds_navbar_brand_cache_v3');
        delete_transient('fds_llms_txt_cache_v3');
        if (has_action('litespeed_purge_all')) {
            do_action('litespeed_purge_all');
        }

        update_option('fds_seo_cleanup_v6', 1, false);
        update_option('fds_seo_cleanup_v7', 1, false);
    }
}, 35);

// =========================================================================
// 8. 301 PERMANENT REDIRECT ENGINE & FEED CLEANUP (Fix Legacy Slugs & /feed/ Crawl Bloat)
// =========================================================================

// Hilangkan tag <link rel="alternate" ... Comments Feed href=".../feed/"> di <head>
// agar Googlebot tidak meng-crawl ribuan URL /nama-artikel/feed/ sebagai halaman terpisah.
add_action('init', function () {
    remove_action('wp_head', 'feed_links_extra', 3);
}, 20);

add_action('template_redirect', function () {
    global $wp;
    $request_uri = trim($_SERVER['REQUEST_URI'] ?? '', '/');
    $path_only   = trim(strtok($request_uri, '?'), '/');
    $path_lower  = strtolower($path_only);

    // 0. Matikan seluruh endpoint /feed/ (comment, post, taxonomy) dengan 410 Gone + X-Robots-Tag untuk menghentikan crawl trap
    if (is_feed() || preg_match('#^(?:.+/)?feed$#i', $path_only)) {
        status_header(410);
        header('X-Robots-Tag: noindex, nofollow', true);
        header('Content-Type: text/plain; charset=utf-8', true);
        echo '410 Gone — Feed telah dinonaktifkan.';
        exit;
    }

    // 0b. Redirect 301 Halaman Zombie Elementor/WooCommerce Lama (Anti-Kanibalisasi Keyword "drone pertanian")
    $zombie_map = fds_get_legacy_zombie_redirect_map();
    if (isset($zombie_map[$path_lower])) {
        header('X-Robots-Tag: noindex, nofollow', true);
        wp_safe_redirect(home_url($zombie_map[$path_lower]), 301);
        exit;
    }

    // 0c. Tangani Akses ke Arsip CPT Drone & REBO (Alihkan ke Katalog Produk /produk/)
    if ($path_lower === 'drone' || is_post_type_archive('drone')) {
        header('X-Robots-Tag: noindex, nofollow', true);
        wp_safe_redirect(home_url('/produk/'), 301);
        exit;
    }

    if (preg_match('#^(drone/|kategori-drone/)?(rebo|reboisasi)/?$#i', $path_lower)) {
        header('X-Robots-Tag: noindex, nofollow', true);
        wp_safe_redirect(home_url('/produk/'), 301);
        exit;
    }

    // 0c-2. Tangani Wildcard URL Legacy Agrea (mis. /drone-pertanian-agrea-15/, /drone/agrea-15/, /agrea-10/)
    if (preg_match('#^(drone/)?(drone-pertanian-)?agrea(-?(\d+))?/?$#i', $path_lower, $ag_m)) {
        header('X-Robots-Tag: noindex, nofollow', true);
        $target_ferto = (!empty($ag_m[4]) && $ag_m[4] === '10') ? '/drone/ferto-10l/' : '/drone/ferto-15l/';
        wp_safe_redirect(home_url($target_ferto), 301);
        exit;
    }

    // 0d. Tangani Typo Slug Drone Non-Eksisten (seperti /drone/ferto-20l/ -> /drone/ferto-15l/)
    if (preg_match('#^(drone/)?ferto-20l?/?$#i', $path_lower)) {
        header('X-Robots-Tag: noindex, nofollow', true);
        wp_safe_redirect(home_url('/drone/ferto-15l/'), 301);
        exit;
    }

    // 1. Legacy About Page 301 Redirects -> /tentang-kami/
    $legacy_about_slugs = [
        'about',
        'about-2',
        'about-3',
        'about-us',
        'about-us-2',
        'aboutus',
        'profil',
        'profil-2',
        'profil-kami',
        'profil-perusahaan',
        'profil-perusahaan-2',
        'profile',
        'profile-2',
        'company-profile',
        'company',
        'company-2',
        'tentang',
        'tentang-2',
        'tentangkami',
        'tentang-kami-2',
        'page/about',
        'page/about-2',
        'about.html',
        'about.php',
    ];

    if ($path_lower !== 'tentang-kami' && (in_array($path_lower, $legacy_about_slugs, true) || preg_match('/^(about|about-us|tentang|tentang-kami|profil|profile)(-\d+)?$/i', $path_lower))) {
        wp_safe_redirect(home_url('/tentang-kami/'), 301);
        exit;
    }

    // 2. Legacy Compare Page 301 Redirects -> /bandingkan/
    $legacy_compare_slugs = [
        'compare',
        'compare-2',
        'compare-drone',
        'bandingkan-2',
        'bandingkan-drone',
        'komparasi',
        'komparasi-2',
        'komparasi-drone',
        'perbandingan-drone',
    ];

    if ($path_lower !== 'bandingkan' && (in_array($path_lower, $legacy_compare_slugs, true) || preg_match('/^(compare|compare-drone|bandingkan|komparasi)(-\d+)?$/i', $path_lower))) {
        wp_safe_redirect(home_url('/bandingkan/'), 301);
        exit;
    }

    // 3. Legacy Contact Page 301 Redirects -> /kontak/
    $legacy_contact_slugs = [
        'contact',
        'contact-2',
        'contact-3',
        'contact-us',
        'contact-us-2',
        'contactus',
        'hubungi',
        'hubungi-2',
        'hubungi-kami',
        'hubungi-kami-2',
        'hubungikami',
        'kontak-2',
        'kontak-kami',
        'kontakkami',
        'page/contact',
        'page/contact-2',
        'page/kontak',
        'contact.html',
        'contact.php',
    ];

    if ($path_lower !== 'kontak' && (in_array($path_lower, $legacy_contact_slugs, true) || preg_match('/^(contact|contact-us|hubungi|hubungi-kami|kontak|kontak-kami)(-\d+)?$/i', $path_lower))) {
        wp_safe_redirect(home_url('/kontak/'), 301);
        exit;
    }

    // 4. Direct Root Slugs for Drones (e.g., /ferto-10l/ -> /drone/ferto-10l/)
    if (!empty($path_lower) && !in_array($path_lower, ['produk', 'bandingkan', 'tentang-kami', 'kontak', 'blog', 'layanan', 'terima-kasih', 'karir', 'disclaimer', 'syarat-ketentuan', 'privacy-policy', 'wp-admin', 'wp-login.php', 'llms.txt', 'llms-full.txt', 'sitemap.xml', 'sitemap_index.xml', 'robots.txt'], true)) {
        if (!str_starts_with($path_lower, 'drone/') && !str_starts_with($path_lower, 'kategori-drone/') && !str_starts_with($path_lower, 'layanan/')) {
            $drone_posts = get_posts([
                'post_type'      => 'drone',
                'name'           => $path_lower,
                'posts_per_page' => 1,
                'post_status'    => 'publish',
            ]);
            if (!empty($drone_posts) && (is_404() || is_page())) {
                wp_safe_redirect(get_permalink($drone_posts[0]->ID), 301);
                exit;
            }
        }
    }
}, 1);

// =========================================================================
// 9. AI SEARCH & GEO DIRECTIVES (TIER MYTHIC PLAYBOOK): ROBOTS.TXT
// =========================================================================

/**
 * Configure robots.txt to explicitly allow top AI crawlers and declare XML sitemaps
 */
add_filter('robots_txt', function ($output, $public) {
    if ('1' === (string) $public) {
        $home = trailingslashit(home_url());
        $rules = [];
        $rules[] = "# ============================================================";
        $rules[] = "# FDS ENTERPRISE SEO & GEO DIRECTIVES (PT KARYA SOLUSI ANGKASA)";
        $rules[] = "# ============================================================";
        $rules[] = "Sitemap: {$home}sitemap_index.xml";
        $rules[] = "";
        $rules[] = "# Standard Search Engine Bots";
        $rules[] = "User-agent: *";
        $rules[] = "Allow: /";
        $rules[] = "Disallow: /wp-admin/";
        $rules[] = "Allow: /wp-admin/admin-ajax.php";
        $rules[] = "";
        $rules[] = "# Tier Mythic AI Crawlers & LLM Indexers";
        $rules[] = "User-agent: GPTBot\nAllow: /";
        $rules[] = "User-agent: OAI-SearchBot\nAllow: /";
        $rules[] = "User-agent: ClaudeBot\nAllow: /";
        $rules[] = "User-agent: PerplexityBot\nAllow: /";
        $rules[] = "User-agent: Google-Extended\nAllow: /";
        $rules[] = "User-agent: Applebot\nAllow: /";
        $rules[] = "User-agent: Applebot-Extended\nAllow: /";
        $rules[] = "User-agent: CCBot\nAllow: /";
        $rules[] = "User-agent: cohere-ai\nAllow: /";
        $rules[] = "";
        $rules[] = "# LLM & GEO Directives";
        $rules[] = "# llms.txt: {$home}llms.txt";
        $rules[] = "# llms-full.txt: {$home}llms-full.txt";
        return implode("\n", $rules) . "\n";
    }
    return $output;
}, 99, 2);

// =========================================================================
// 10. GENERATIVE ENGINE OPTIMIZATION (GEO): /llms.txt & /llms-full.txt
// =========================================================================

/**
 * Build standardized llmstxt.org Markdown content (differentiated summary vs full corpus + static disk sync)
 * - $full = false (/llms.txt): Concise executive index (~4-6 KB) with clear pointer to /llms-full.txt
 * - $full = true (/llms-full.txt): Complete knowledge corpus (about, 9 full UAV specs, 6 sectors, 3 categories, matrices, blog posts & FAQs)
 */
function fds_build_llms_txt_content(bool $full = false): string {
    $cache_key = $full ? 'fds_llms_full_txt_cache_v5' : 'fds_llms_txt_cache_v5';
    $cached    = get_transient($cache_key);
    if (is_string($cached) && $cached !== '') {
        return $cached;
    }

    $clean_llms = static function ($v, bool $allow_tkdn = true, bool $allow_sni = true, bool $allow_iso = true): string {
        if (!is_string($v) && !is_numeric($v)) {
            return '';
        }
        $str = (string) $v;
        if (function_exists('fds_clean_string')) {
            $str = \fds_clean_string($str);
        } else {
            $prev = '';
            $max  = 5;
            while ($prev !== $str && $max-- > 0) {
                $prev = $str;
                $str  = html_entity_decode(wp_specialchars_decode($str, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }
        $str = wp_strip_all_tags($str);
        if (function_exists('fds_scrub_legacy_text')) {
            $str = \fds_scrub_legacy_text($str, $allow_tkdn, $allow_sni, $allow_iso);
        }
        return trim((string) preg_replace('/\s+/u', ' ', $str));
    };

    $escape_md_cell = static function (string $cell): string {
        return str_replace('|', '\\|', $cell);
    };

    $site_name = 'PT Karya Solusi Angkasa (Full Drone Solutions / FDS)';
    $home_url  = trailingslashit(home_url());

    $contact = function_exists(__NAMESPACE__ . '\\fds_get_global_contact') ? fds_get_global_contact() : [];
    $phone   = $clean_llms($contact['phone'] ?? '+62 8112 748 882') ?: '+62 8112 748 882';
    $email   = $clean_llms($contact['email'] ?? 'marketing@fulldronesolutions.com') ?: 'marketing@fulldronesolutions.com';
    $address = $clean_llms($contact['address'] ?? 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285')
        ?: 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285';

    // Collect 9 active CPT 'drone' units cleanly from Single Source of Truth
    $drones_raw = get_posts([
        'post_type'      => 'drone',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ]);
    if (function_exists('fds_sort_drone_posts') && is_array($drones_raw)) {
        $drones_raw = \fds_sort_drone_posts($drones_raw);
    }

    $drone_records = [];
    foreach ($drones_raw as $d) {
        $d_slug_chk = strtolower((string) $d->post_name);
        if (strpos($d_slug_chk, 'rebo') !== false || strpos($d_slug_chk, 'agrea') !== false || strpos($d_slug_chk, 'ferto-20') !== false) {
            continue;
        }
        $rm_robots = get_post_meta($d->ID, 'rank_math_robots', true);
        if (is_array($rm_robots) && in_array('noindex', $rm_robots, true)) {
            continue;
        }
        $detail = function_exists(__NAMESPACE__ . '\\fds_get_drone_detail_data') ? fds_get_drone_detail_data((int) $d->ID) : [];
        $url    = !empty($detail['permalink']) ? (string) $detail['permalink'] : (string) get_permalink($d->ID);
        if ((!$url || strpos($url, '?p=') !== false) && !empty($d->post_name)) {
            $url = home_url('/drone/' . $d->post_name . '/');
        }
        $model_key = function_exists(__NAMESPACE__ . '\\fds_get_drone_model_key') ? fds_get_drone_model_key($d->ID) : $d_slug_chk;
        $is_ferto  = isset($detail['is_ferto']) ? (bool) $detail['is_ferto'] : (strpos($model_key, 'ferto-') === 0);
        $has_sni   = isset($detail['has_sni']) ? (bool) $detail['has_sni'] : in_array($model_key, ['ferto-15l', 'ferto-22l'], true);

        $name        = $clean_llms($detail['name'] ?? get_the_title($d->ID), $is_ferto, $has_sni);
        $kategori    = $clean_llms($detail['kategori'] ?? (get_post_meta($d->ID, 'drone_kategori', true) ?: 'UAV Industri'), $is_ferto, $has_sni);
        $tagline     = $clean_llms($detail['tagline'] ?? get_post_meta($d->ID, 'drone_tagline', true), $is_ferto, $has_sni);
        $desc        = $clean_llms($detail['desc'] ?? get_post_meta($d->ID, 'drone_desc', true), $is_ferto, $has_sni);
        $payload     = $clean_llms($detail['card_payload'] ?? get_post_meta($d->ID, 'drone_spec_kapasitas', true), $is_ferto, $has_sni);
        $durasi      = $clean_llms($detail['matrix_duration'] ?? ($detail['card_metric_value'] ?? get_post_meta($d->ID, 'drone_spec_durasi', true)), $is_ferto, $has_sni);
        $matrix_cert = $clean_llms($detail['matrix_cert'] ?? '', $is_ferto, $has_sni, false);
        if ($matrix_cert === '') {
            $matrix_cert = $has_sni
                ? 'TKDN + BMP 60,74% | SNI 9199:2023'
                : ($is_ferto ? 'TKDN + BMP 60,74%' : '');
        }

        $stats = [];
        for ($si = 1; $si <= 4; $si++) {
            $s_num = $clean_llms($detail["stat{$si}_num"] ?? '', $is_ferto, $has_sni);
            $s_lbl = $clean_llms($detail["stat{$si}_lbl"] ?? '', $is_ferto, $has_sni);
            if ($s_num !== '' && $s_lbl !== '') {
                $stats[] = "{$s_lbl}: {$s_num}";
            }
        }

        $specs = [];
        if (!empty($detail['specs']) && is_array($detail['specs'])) {
            foreach ($detail['specs'] as $sp) {
                if (is_array($sp) && count($sp) >= 2) {
                    $sl = $clean_llms($sp[0], $is_ferto, $has_sni);
                    $is_cert_sp = (stripos($sl, 'Sertifikasi') !== false);
                    $sv = $clean_llms($sp[1], $is_ferto, $has_sni, !$is_cert_sp);
                    if ($sl !== '' && $sv !== '') {
                        $specs[] = [$sl, $sv];
                    }
                }
            }
        }

        $uses = [];
        if (!empty($detail['for']) && is_array($detail['for'])) {
            foreach ($detail['for'] as $uc) {
                $cuc = $clean_llms($uc, $is_ferto, $has_sni);
                if ($cuc !== '') {
                    $uses[] = $cuc;
                }
            }
        }

        $drone_records[] = [
            'name'        => $name,
            'url'         => $url,
            'kategori'    => $kategori,
            'tagline'     => $tagline,
            'desc'        => $desc,
            'payload'     => $payload,
            'durasi'      => $durasi,
            'cert'        => $matrix_cert,
            'stats'       => $stats,
            'specs'       => $specs,
            'uses'        => $uses,
            'is_ferto'    => $is_ferto,
            'has_sni'     => $has_sni,
        ];
    }

    $sectors    = function_exists(__NAMESPACE__ . '\\fds_get_layanan_sectors') ? fds_get_layanan_sectors() : [];
    $categories = function_exists(__NAMESPACE__ . '\\fds_get_layanan_categories') ? fds_get_layanan_categories() : [];
    $hub        = function_exists(__NAMESPACE__ . '\\fds_get_layanan_hub_data') ? fds_get_layanan_hub_data() : [];
    $about      = function_exists(__NAMESPACE__ . '\\fds_get_about_content') ? fds_get_about_content() : [];
    $produk_ui  = function_exists(__NAMESPACE__ . '\\fds_get_produk_catalog_content') ? fds_get_produk_catalog_content() : [];

    $out = [];

    if (!$full) {
        // =================================================================
        // STANDARD /llms.txt (RINGKAS, TERSTRUKTUR, INDEX EKSEKUTIF ~4-6 KB)
        // =================================================================
        $out[] = "# {$site_name}";
        $out[] = "";
        $out[] = "> PT Karya Solusi Angkasa (Full Drone Solutions / FDS) adalah produsen pesawat tanpa awak (UAV) resmi berbadan hukum PT di Indonesia sejak 2019, bersertifikat sistem mutu ISO 9001:2015, TKDN + BMP 60,74% untuk lini drone pertanian seri FERTO, serta SNI 9199:2023 pada FERTO 15 dan FERTO 22. FDS beroperasi di Sleman, D.I. Yogyakarta dengan fokus manufaktur UAV, jasa foto udara & survey LiDAR, konsultasi GIS enterprise, serta pemetaan kawasan strategis.";
        $out[] = "";
        $out[] = "## Korpus Teknis Lengkap & Peta Situs";
        $out[] = "- [llms-full.txt]({$home_url}llms-full.txt): Dokumentasi teknis lengkap meliputi profil PT Karya Solusi Angkasa, tabel spesifikasi penuh 9 model UAV, rincian 6 sektor industri & 3 kategori layanan, format deliverables geospasial, FAQ lengkap, dan daftar artikel Newsroom.";
        $out[] = "- [XML Sitemap Index]({$home_url}sitemap_index.xml): Indeks peta situs resmi yang diperbarui otomatis.";
        $out[] = "";
        $out[] = "## Fakta Entitas & Kepatuhan Resmi";
        $out[] = "- **Entitas Legal:** PT Karya Solusi Angkasa (berdiri sejak 2019), merek komersial Full Drone Solutions (FDS).";
        $out[] = "- **Sertifikasi TKDN:** Nilai TKDN + BMP 60,74% (Kemenperin RI) berlaku pada seluruh seri drone pertanian FERTO (FERTO 5, FERTO 10, FERTO 15, FERTO 22, FERTO 30, FERTO 50).";
        $out[] = "- **Standar Produk Nasional:** SNI 9199:2023 berlaku resmi pada FERTO 15 (kapasitas tangki 17 Liter) dan FERTO 22.";
        $out[] = "- **Sistem Mutu & Kendali:** Akreditasi sistem manajemen mutu perusahaan ISO 9001:2015 (PT Karya Solusi Angkasa), pelatihan pilot regulasi DKPPU CASR Part 107, serta Ground Control Station (GCS) berbahasa Indonesia FDS Station.";
        $out[] = "- **Workshop & Pengujian:** " . $address;
        $out[] = "- **Kontak Resmi:** Telepon/WhatsApp {$phone} | Email {$email}";
        $out[] = "";
        $out[] = "## 4 Pilar Layanan & Manufaktur";
        $out[] = "1. **Manufaktur Drone Nasional:** Drone pertanian sprayer & spreader seri FERTO (5L–50L), Fixed-Wing Hybrid VTOL DELTAV (jelajah hingga 60 km), kargo logistik DELFRO (3–10 kg), dan platform inspeksi modular MULTIPURPOSE (payload 5 kg).";
        $out[] = "2. **Jasa Foto Udara & Survey LiDAR:** Akuisisi ortofoto resolusi tinggi, pemindaian laser 3D penembus kanopi (Bare Earth DTM, DSM, Point Cloud .LAS/.LAZ), dan kontrol geodetik GCP/ICP.";
        $out[] = "3. **Jasa Konsultan GIS Enterprise:** Analisis spasial daerah aliran sungai (DAS), tata ruang RTRW/RDTR, batas konsesi HGU, NDVI, dan pengembangan dashboard WebGIS interaktif.";
        $out[] = "4. **Pemetaan Kawasan Strategis:** Survei pit pertambangan & volume stockpile, pemetaan Kawasan Ekonomi Khusus (KEK), kawasan kehutanan, koridor energi 150 kV, serta kawasan industri terpadu.";
        $out[] = "";
        $out[] = "## Katalog Armada Drone Resmi (" . count($drone_records) . " Model Aktif)";
        foreach ($drone_records as $dr) {
            $summary_bits = array_filter([
                $dr['kategori'],
                $dr['payload'] !== '' ? "Payload {$dr['payload']}" : '',
                $dr['durasi'] !== '' ? "Durasi {$dr['durasi']}" : '',
                $dr['cert'],
            ]);
            $out[] = "- [{$dr['name']}]({$dr['url']}): " . implode(' · ', $summary_bits) . ".";
        }
        $out[] = "";
        $out[] = "## Solusi Industri & Layanan Spesialis";
        $out[] = "- [Hub Layanan UAV & Geospasial]({$home_url}layanan/): Gambaran menyeluruh jasa drone mapping, survey LiDAR, multispectral, inspeksi aset, dan pelatihan operator.";
        foreach ($sectors as $sec) {
            $st = $clean_llms($sec['card_title'] ?? '');
            $sd = $clean_llms($sec['card_desc'] ?? '');
            $su = (string) ($sec['url'] ?? '');
            if ($st !== '' && $su !== '') {
                $out[] = "- [Sektor {$st}]({$su}): {$sd}";
            }
        }
        foreach ($categories as $cat) {
            $ct = $clean_llms($cat['short_title'] ?? '');
            $cd = $clean_llms($cat['hero_summary'] ?? '');
            $cu = (string) ($cat['url'] ?? '');
            if ($ct !== '' && $cu !== '') {
                $out[] = "- [{$ct}]({$cu}): {$cd}";
            }
        }
        $out[] = "";
        $out[] = "## Halaman Strategis Perusahaan";
        $out[] = "- [Beranda Full Drone Solutions]({$home_url}): Profil utama solusi UAV enterprise dan manufaktur nasional.";
        $out[] = "- [Katalog Produk Drone]({$home_url}produk/): Katalog lengkap wahana FDS, perbandingan sertifikasi, dan brosur teknis.";
        $out[] = "- [Bandingkan Spesifikasi Drone]({$home_url}bandingkan/): Alat komparasi interaktif antar model armada FDS.";
        $out[] = "- [Tentang PT Karya Solusi Angkasa]({$home_url}tentang-kami/): Sejarah perusahaan sejak 2019, spektrum teknologi UAV, dan kemitraan strategis.";
        $out[] = "- [Newsroom & Studi Kasus]({$home_url}blog/): Publikasi berita resmi, artikel teknis UAV, dan laporan implementasi lapangan.";
        $out[] = "- [Hubungi Tim Engineering]({$home_url}kontak/): Konsultasi pengadaan B2B/BUMN/e-Katalog LKPP, permintaan RFP, dan jadwal demo unit.";
        $out[] = "";
    } else {
        // =================================================================
        // FULL /llms-full.txt (KORPUS PENGETAHUAN LENGKAP UNTUK AI & RAG)
        // =================================================================
        $out[] = "# {$site_name} — Dokumentasi & Korpus Teknis Lengkap";
        $out[] = "";
        $out[] = "> PT Karya Solusi Angkasa (merek komersial: Full Drone Solutions / FDS) adalah perusahaan manufaktur dan penyedia layanan teknologi pesawat nirawak (UAV) nasional di Sleman, Daerah Istimewa Yogyakarta, berbadan hukum PT resmi sejak 2019. FDS menerapkan sistem manajemen mutu ISO 9001:2015, sertifikasi TKDN + BMP 60,74% Kemenperin RI untuk seluruh lini drone pertanian sprayer seri FERTO, serta standar SNI 9199:2023 pada FERTO 15 dan FERTO 22.";
        $out[] = "";
        $out[] = "## 1. Profil Perusahaan, Sejarah & Legalitas Pengadaan";
        $out[] = "";
        $out[] = "| Parameter | Keterangan Resmi |";
        $out[] = "|---|---|";
        $out[] = "| **Entitas Hukum** | PT Karya Solusi Angkasa |";
        $out[] = "| **Merek Komersial** | Full Drone Solutions (FDS) |";
        $out[] = "| **Tahun Pendirian PT** | 2019 (Yogyakarta) |";
        $out[] = "| **Sistem Manajemen Mutu Perusahaan** | ISO 9001:2015 (PT Karya Solusi Angkasa) |";
        $out[] = "| **Sertifikasi TKDN + BMP** | 60,74% (Kemenperin RI) — berlaku khusus seri FERTO (5, 10, 15, 22, 30, 50) |";
        $out[] = "| **Standar Produk Nasional** | SNI 9199:2023 — berlaku resmi pada model FERTO 15 (17 Liter) dan FERTO 22 |";
        $out[] = "| **Software Kendali GCS** | FDS Station (Ground Control Station berbahasa Indonesia) |";
        $out[] = "| **Sertifikasi Pilot** | Pelatihan & pendampingan regulasi DKPPU CASR Part 107 |";
        $out[] = "| **Kesiapan Pengadaan** | Dukungan B2B korporasi, BUMN, dan pengadaan pemerintah / e-Katalog LKPP |";
        $out[] = "| **Workshop & Pusat Riset** | " . $escape_md_cell($address) . " |";
        $out[] = "| **Kontak Resmi** | " . $escape_md_cell("Telepon/WhatsApp: {$phone} | Email: {$email}") . " |";
        $out[] = "| **Website Resmi** | [{$home_url}]({$home_url}) |";
        $out[] = "";

        $about_narratives = [];
        foreach (['story_p1', 'story_p2', 'story_p3', 'story_p4'] as $ak) {
            if (!empty($about[$ak])) {
                $about_narratives[] = $clean_llms($about[$ak]);
            }
        }
        if (!empty($about_narratives)) {
            $out[] = "### Latar Belakang Rekayasa & Arsitektur Wahana";
            foreach ($about_narratives as $an) {
                $out[] = $an;
                $out[] = "";
            }
        }
        $out[] = "- **Arsitektur Wahana FDS:** (1) *Rotary Wing / Multirotor* untuk aplikasi penyemprotan pertanian presisi, kargo waypoint, dan inspeksi jarak dekat; (2) *Fixed Wing* dan (3) *Hybrid VTOL (DELTAV)* untuk efisiensi lepas landas vertikal tanpa landasan dengan daya jelajah area luas hingga 60 km.";
        $out[] = "- **Jejak Kemitraan Strategis:** Produksi & R&D mandiri oleh talenta kedirgantaraan Yogyakarta, mitra program PRISMA (Bappenas - Pemerintah Australia) untuk adopsi drone sprayer pertanian, kolaborasi ketahanan pangan bersama Bank Indonesia, serta riset konservasi & reboisasi bersama Universitas Gadjah Mada (UGM) dan mitra Swiss.";
        $out[] = "";

        $out[] = "## 2. Katalog & Spesifikasi Lengkap " . count($drone_records) . " Model Drone Resmi FDS";
        $out[] = "";
        foreach ($drone_records as $dr) {
            $out[] = "### {$dr['name']} ({$dr['kategori']})";
            $out[] = "- **URL Resmi:** {$dr['url']}";
            if ($dr['tagline'] !== '') {
                $out[] = "- **Ringkasan Singkat:** {$dr['tagline']}";
            }
            if ($dr['desc'] !== '') {
                $out[] = "- **Deskripsi Teknis:** {$dr['desc']}";
            }
            if (!empty($dr['stats'])) {
                $out[] = "- **Statistik Utama:** " . implode(' | ', $dr['stats']);
            }
            if ($dr['cert'] !== '') {
                $out[] = "- **Sertifikasi Produk Terverifikasi:** {$dr['cert']}";
            }
            if (!empty($dr['specs'])) {
                $out[] = "- **Lembar Spesifikasi Teknis:**";
                foreach ($dr['specs'] as $sp_row) {
                    $out[] = "  - {$sp_row[0]}: {$sp_row[1]}";
                }
            }
            if (!empty($dr['uses'])) {
                $out[] = "- **Aplikasi & Use-Cases:**";
                foreach ($dr['uses'] as $u_item) {
                    $out[] = "  - {$u_item}";
                }
            }
            $out[] = "";
        }

        $out[] = "## 3. Ekosistem Layanan UAV, Survei LiDAR, GIS & Pelatihan";
        $out[] = "";
        if (!empty($hub['intro_paragraphs']) && is_array($hub['intro_paragraphs'])) {
            foreach ($hub['intro_paragraphs'] as $hp) {
                $chp = $clean_llms($hp);
                if ($chp !== '') {
                    $out[] = $chp;
                    $out[] = "";
                }
            }
        }
        if (!empty($hub['why_fds']['items']) && is_array($hub['why_fds']['items'])) {
            $out[] = "### Keunggulan Terpadu Layanan FDS";
            foreach ($hub['why_fds']['items'] as $wi) {
                $wt = $clean_llms($wi['title'] ?? '');
                $wd = $clean_llms($wi['desc'] ?? '');
                if ($wt !== '') {
                    $out[] = "- **{$wt}:** {$wd}";
                }
            }
            $out[] = "";
        }
        if (!empty($hub['method_comparison']['columns']) && !empty($hub['method_comparison']['rows'])) {
            $cols = array_map(fn($c) => $escape_md_cell($clean_llms($c)), $hub['method_comparison']['columns']);
            $out[] = "### Matriks Pemilihan Metode Akuisisi Udara";
            $out[] = "| " . implode(" | ", $cols) . " |";
            $out[] = "|" . implode("|", array_fill(0, count($cols), "---")) . "|";
            foreach ($hub['method_comparison']['rows'] as $row) {
                if (is_array($row) && count($row) === count($cols)) {
                    $cells = array_map(fn($c) => $escape_md_cell($clean_llms($c)), $row);
                    $out[] = "| " . implode(" | ", $cells) . " |";
                }
            }
            $out[] = "";
        }

        if (!empty($sectors)) {
            $out[] = "### Rincian 6 Sektor Industri Layanan FDS";
            $out[] = "";
            foreach ($sectors as $sec) {
                $st   = $clean_llms($sec['card_title'] ?? '');
                $sh1  = $clean_llms($sec['h1'] ?? '');
                $ssum = $clean_llms($sec['hero_summary'] ?? '');
                $surl = (string) ($sec['url'] ?? '');
                $out[] = "#### Sektor {$st}" . ($sh1 !== '' ? " — {$sh1}" : '');
                if ($surl !== '') {
                    $out[] = "- **URL:** {$surl}";
                }
                if ($ssum !== '') {
                    $out[] = "- **Ringkasan:** {$ssum}";
                }
                if (!empty($sec['solutions']) && is_array($sec['solutions'])) {
                    $sol_txt = [];
                    foreach ($sec['solutions'] as $sol) {
                        $sn = $clean_llms($sol['name'] ?? '');
                        $sd = $clean_llms($sol['desc'] ?? '');
                        if ($sn !== '') {
                            $sol_txt[] = $sd !== '' ? "{$sn} ({$sd})" : $sn;
                        }
                    }
                    if (!empty($sol_txt)) {
                        $out[] = "- **Solusi & Layanan:** " . implode('; ', $sol_txt) . '.';
                    }
                }
                if (!empty($sec['outputs']) && is_array($sec['outputs'])) {
                    $out_txt = array_filter(array_map($clean_llms, $sec['outputs']));
                    if (!empty($out_txt)) {
                        $out[] = "- **Output Pekerjaan:** " . implode('; ', $out_txt) . '.';
                    }
                }
                if (!empty($sec['faqs']) && is_array($sec['faqs'])) {
                    foreach (array_slice($sec['faqs'], 0, 2) as $fq) {
                        $qq = $clean_llms($fq['q'] ?? '');
                        $qa = $clean_llms($fq['a'] ?? '');
                        if ($qq !== '' && $qa !== '') {
                            $out[] = "- **Tanya:** {$qq} **Jawab:** {$qa}";
                        }
                    }
                }
                $out[] = "";
            }
        }

        if (!empty($categories)) {
            $out[] = "### Rincian 3 Kategori Layanan Lintas Sektor";
            $out[] = "";
            foreach ($categories as $cat) {
                $ct   = $clean_llms($cat['short_title'] ?? '');
                $ch1  = $clean_llms($cat['h1'] ?? '');
                $csum = $clean_llms($cat['hero_summary'] ?? '');
                $curl = (string) ($cat['url'] ?? '');
                $out[] = "#### {$ct}" . ($ch1 !== '' ? " — {$ch1}" : '');
                if ($curl !== '') {
                    $out[] = "- **URL:** {$curl}";
                }
                if ($csum !== '') {
                    $out[] = "- **Ringkasan:** {$csum}";
                }
                if (!empty($cat['capabilities']) && is_array($cat['capabilities'])) {
                    $cap_txt = [];
                    foreach ($cat['capabilities'] as $cp) {
                        $cn = $clean_llms($cp['name'] ?? '');
                        $cd = $clean_llms($cp['desc'] ?? '');
                        if ($cn !== '') {
                            $cap_txt[] = $cd !== '' ? "{$cn}: {$cd}" : $cn;
                        }
                    }
                    if (!empty($cap_txt)) {
                        $out[] = "- **Kapabilitas:** " . implode(' | ', $cap_txt);
                    }
                }
                if (!empty($cat['outputs']) && is_array($cat['outputs'])) {
                    $out_txt = array_filter(array_map($clean_llms, $cat['outputs']));
                    if (!empty($out_txt)) {
                        $out[] = "- **Deliverables Utama:** " . implode('; ', $out_txt) . '.';
                    }
                }
                if (!empty($cat['faqs']) && is_array($cat['faqs'])) {
                    foreach (array_slice($cat['faqs'], 0, 2) as $fq) {
                        $qq = $clean_llms($fq['q'] ?? '');
                        $qa = $clean_llms($fq['a'] ?? '');
                        if ($qq !== '' && $qa !== '') {
                            $out[] = "- **Tanya:** {$qq} **Jawab:** {$qa}";
                        }
                    }
                }
                $out[] = "";
            }
        }

        $out[] = "## 4. Format Data Luaran (Deliverables) Geospasial";
        $out[] = "";
        $out[] = "- **Format Raster:** Citra Ortofoto GeoTIFF / ECW resolusi tinggi, peta indeks kesehatan tanaman NDVI.";
        $out[] = "- **Format Vektor & CAD:** ESRI Shapefile (.shp), GeoJSON, AutoCAD DWG/DXF layer kontur.";
        $out[] = "- **Format 3D & LiDAR:** Point Cloud terklasifikasi standar ASPRS (.LAS / .LAZ), Digital Terrain Model (Bare Earth DTM), Digital Surface Model (DSM), 3D Mesh (.OBJ / .FBX).";
        $out[] = "- **Dokumen Teknis:** Laporan kalkulasi volume stockpile & cut-and-fill (Excel/PDF), laporan akurasi kontrol tanah GCP/ICP & lintasan PPK, serta peta cetak.";
        $out[] = "- **Platform Spasial:** WebGIS Cloud Enterprise interaktif untuk monitoring progres dan integrasi aset.";
        $out[] = "";

        $out[] = "## 5. Tanya Jawab GEO & Teknis Pengadaan";
        $out[] = "";
        $geo_faqs = [
            [
                'q' => 'Siapa produsen drone pertanian dan pemetaan bersertifikat di Indonesia?',
                'a' => 'PT Karya Solusi Angkasa (Full Drone Solutions / FDS) adalah produsen UAV resmi di Indonesia bersertifikat ISO 9001:2015 yang memproduksi seri drone pertanian sprayer FERTO (5L–50L) dengan nilai TKDN + BMP 60,74% serta SNI 9199:2023 pada FERTO 15 dan FERTO 22, sekaligus mengembangkan Fixed-Wing VTOL DELTAV, kargo DELFRO, dan platform inspeksi MULTIPURPOSE.',
            ],
            [
                'q' => 'Apa peran PT Karya Solusi Angkasa dalam jasa foto udara, survey LiDAR, dan konsultan GIS?',
                'a' => 'FDS menangani layanan ujung ke ujung: perencanaan misi terbang, pengikatan titik kontrol geodesi GCP/ICP, akuisisi foto udara fotogrametri dan LiDAR penembus kanopi, pemrosesan ortofoto/DTM/DSM/LAS/kontur, hingga analisis hidrologi DAS, batas HGU, dan pembangunan dashboard WebGIS enterprise.',
            ],
        ];
        if (!empty($produk_ui['faqs']) && is_array($produk_ui['faqs'])) {
            foreach ($produk_ui['faqs'] as $pfq) {
                $pq = $clean_llms($pfq['q'] ?? '');
                $pa = $clean_llms($pfq['a'] ?? '');
                if ($pq !== '' && $pa !== '') {
                    $geo_faqs[] = ['q' => $pq, 'a' => $pa];
                }
            }
        }
        foreach ($geo_faqs as $gf) {
            $out[] = "### Q: {$gf['q']}";
            $out[] = "A: {$gf['a']}";
            $out[] = "";
        }

        // Newsroom & Studi Kasus (up to 25 recent published articles, clean & non-zombie)
        $zombie_map   = function_exists(__NAMESPACE__ . '\\fds_get_legacy_zombie_redirect_map') ? fds_get_legacy_zombie_redirect_map() : [];
        $blog_posts   = get_posts([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 25,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        $blog_lines = [];
        foreach ($blog_posts as $bp) {
            $bp_slug = strtolower((string) $bp->post_name);
            if (
                $bp_slug === ''
                || isset($zombie_map[$bp_slug])
                || strpos($bp_slug, 'rebo') !== false
                || strpos($bp_slug, 'agrea') !== false
                || strpos($bp_slug, 'ferto-20') !== false
            ) {
                continue;
            }
            $rm_robots = get_post_meta($bp->ID, 'rank_math_robots', true);
            if (is_array($rm_robots) && in_array('noindex', $rm_robots, true)) {
                continue;
            }
            $bp_title = $clean_llms(get_the_title($bp->ID));
            $bp_url   = (string) get_permalink($bp->ID);
            if ($bp_title === '' || $bp_url === '') {
                continue;
            }
            $raw_excerpt = (string) ($bp->post_excerpt ?: $bp->post_content);
            $bp_excerpt  = $clean_llms(wp_trim_words(wp_strip_all_tags($raw_excerpt), 28, '…'));
            $bp_date     = get_the_date('Y-m-d', $bp->ID);
            $blog_lines[] = "- [{$bp_title}]({$bp_url}) ({$bp_date})" . ($bp_excerpt !== '' ? ": {$bp_excerpt}" : '');
        }
        if (!empty($blog_lines)) {
            $out[] = "## 6. Newsroom, Studi Kasus & Artikel Teknis Terkini";
            $out[] = "";
            foreach ($blog_lines as $bl) {
                $out[] = $bl;
            }
            $out[] = "";
        }

        $out[] = "## 7. Direktori Tautan Resmi";
        $out[] = "";
        $out[] = "- [Beranda FDS]({$home_url}): Profil perusahaan dan ekosistem UAV Indonesia.";
        $out[] = "- [Katalog Produk Drone]({$home_url}produk/): Indeks seri FERTO, DELTAV, MULTIPURPOSE, dan DELFRO.";
        $out[] = "- [Kategori Agrikultur]({$home_url}kategori-drone/agrikultur/): Seri sprayer & spreader FERTO 5L–50L.";
        $out[] = "- [Kategori Pemetaan & GIS]({$home_url}kategori-drone/pemetaan-gis/): Fixed-Wing VTOL DELTAV dan MULTIPURPOSE.";
        $out[] = "- [Kategori Kargo Logistik]({$home_url}kategori-drone/kargo/): Drone kargo otonom DELFRO.";
        $out[] = "- [Bandingkan Spesifikasi Drone]({$home_url}bandingkan/): Alat komparasi model UAV.";
        $out[] = "- [Hub Layanan UAV]({$home_url}layanan/): Pusat layanan pemetaan, LiDAR, GIS, inspeksi, dan pelatihan.";
        $out[] = "- [Tentang Kami]({$home_url}tentang-kami/): Sejarah PT Karya Solusi Angkasa sejak 2019 dan fasilitas Yogyakarta.";
        $out[] = "- [Newsroom & Blog]({$home_url}blog/): Artikel dan studi kasus implementasi drone.";
        $out[] = "- [Hubungi Tim Engineering]({$home_url}kontak/): Formulir RFP, penawaran pengadaan B2B/BUMN, dan jadwal demo.";
        $out[] = "- [Kebijakan Privasi]({$home_url}privacy-policy/): Kebijakan pelindungan data sesuai UU PDP.";
        $out[] = "- [llms.txt (Ringkasan Indeks)]({$home_url}llms.txt): Versi ringkas direktori untuk konsumsi LLM cepat.";
        $out[] = "- [XML Sitemap]({$home_url}sitemap_index.xml): Peta situs mesin pencari.";
        $out[] = "";
    }

    $content = implode("\n", $out);
    set_transient($cache_key, $content, 12 * HOUR_IN_SECONDS);

    // Tulis file statis terpisah ke root agar web server (LiteSpeed/Nginx) menyajikan instan tanpa 301 redirect
    if (defined('ABSPATH') && is_writable(ABSPATH)) {
        $filename = $full ? 'llms-full.txt' : 'llms.txt';
        @file_put_contents(ABSPATH . $filename, $content);
    }

    return $content;
}

/**
 * Daftar option WP yang memengaruhi konten publik, SEO, atau dokumen AI (/llms.txt & sitemap).
 * Mengabaikan transient, lock, seed marker, dan log lead agar tidak pernah memicu rebuild tak berujung.
 */
function fds_is_purge_relevant_option(string $option_name): bool {
    if ($option_name === '' || str_starts_with($option_name, '_transient') || str_starts_with($option_name, '_site_transient')) {
        return false;
    }

    $core_watched = [
        'blogname',
        'blogdescription',
        'page_on_front',
        'page_for_posts',
        'show_on_front',
        'permalink_structure',
        'site_icon',
    ];
    if (in_array($option_name, $core_watched, true)) {
        return true;
    }

    if (!str_starts_with($option_name, 'fds_')) {
        return false;
    }

    $ignored_fragments = [
        '_cache',
        '_lock',
        '_seeded',
        '_version',
        'migration',
        'cleanup',
        'diplomatic',
        'clean_',
        'fds_rm_',
        'fds_llms_',
        'fds_auto_setup',
        'fds_theme_auto_setup',
        'fds_default_drone_cats',
        'fds_drones_seeded',
        'fds_brochure_leads',
        'fds_inquiries',
    ];
    foreach ($ignored_fragments as $frag) {
        if (stripos($option_name, $frag) !== false) {
            return false;
        }
    }

    return true;
}

/**
 * Mesin Pembersih Cache Terpusat (Transients, Static Memo Getters, Rank Math Sitemap, Disk llms.txt & llms-full.txt, LiteSpeed)
 *
 * @param bool $purge_litespeed Jalankan do_action('litespeed_purge_all') bila aktif
 * @param bool $immediate       True untuk langsung membangun ulang sitemap & disk llms; false untuk invalidsi memori/transient cepat
 */
function fds_purge_ai_and_seo_caches(bool $purge_litespeed = true, bool $immediate = true): void {
    static $is_purging = false;
    if ($is_purging) {
        return;
    }
    $is_purging = true;

    try {
        $transients = [
            'fds_llms_txt_cache_v2',
            'fds_llms_txt_cache_v3',
            'fds_llms_txt_cache_v4',
            'fds_llms_txt_cache_v5',
            'fds_llms_full_txt_cache_v5',
            'fds_mega_menu_data',
            'fds_mega_menu_data_v2',
            'fds_mega_menu_data_v3',
            'fds_bandingkan_catalog_cache',
            'fds_bandingkan_catalog_cache_v2',
            'fds_bandingkan_catalog_cache_v3',
            'fds_bandingkan_catalog_cache_v4',
            'fds_homepage_content_cache',
            'fds_homepage_content_cache_v2',
            'fds_homepage_content_cache_v3',
            'fds_newsroom_ui_cache_v1',
            'fds_navbar_brand_cache_v2',
            'fds_navbar_brand_cache_v3',
            'fds_navbar_brand_cache_v4',
            'fds_global_contact_cache_v2',
            'fds_global_contact_cache_v3',
            'fds_global_contact_cache_v4',
            'fds_about_content_cache',
            'fds_about_content_cache_v2',
            'fds_about_content_cache_v3',
            'fds_about_gallery_cache',
        ];
        foreach ($transients as $tk) {
            delete_transient($tk);
        }

        // Refresh memoized runtime getters cleanly (avoid calling fds_flush_layanan_cache to prevent loops)
        if (function_exists(__NAMESPACE__ . '\\fds_get_layanan_sectors')) {
            fds_get_layanan_sectors(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_layanan_categories')) {
            fds_get_layanan_categories(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_layanan_hub_data')) {
            fds_get_layanan_hub_data(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_layanan_ui_texts')) {
            fds_get_layanan_ui_texts(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_homepage_content')) {
            fds_get_homepage_content(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_newsroom_ui_content')) {
            fds_get_newsroom_ui_content(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_navbar_brand')) {
            fds_get_navbar_brand(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_global_contact')) {
            fds_get_global_contact(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_about_content')) {
            fds_get_about_content(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_produk_catalog_content')) {
            fds_get_produk_catalog_content(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_kategori_drone_ui_content')) {
            fds_get_kategori_drone_ui_content(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_kategori_drone_catalog_content')) {
            foreach (['agrikultur', 'pemetaan-gis', 'kargo'] as $kt_slug) {
                fds_get_kategori_drone_catalog_content($kt_slug, null, true);
            }
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_drone_detail_ui')) {
            fds_get_drone_detail_ui(true);
        }
        if (function_exists(__NAMESPACE__ . '\\fds_get_bandingkan_content')) {
            fds_get_bandingkan_content(true);
        }

        if ($immediate) {
            global $wpdb;
            if (isset($wpdb) && is_object($wpdb)) {
                $rm_like_1 = '%' . $wpdb->esc_like('rank_math_sitemap') . '%';
                $rm_like_2 = '%' . $wpdb->esc_like('rm_sm_') . '%';
                $wpdb->query($wpdb->prepare(
                    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
                    $rm_like_1,
                    $rm_like_2
                ));
            }
            if (class_exists('\RankMath\Sitemap\Cache') && method_exists('\RankMath\Sitemap\Cache', 'invalidate_storage')) {
                try {
                    \RankMath\Sitemap\Cache::invalidate_storage();
                } catch (\Throwable $e) {
                    // Fail silently if Rank Math cache class expects request context
                }
            }

            // Rebuild & sync both /llms.txt & /llms-full.txt cleanly on disk + transient
            fds_build_llms_txt_content(false);
            fds_build_llms_txt_content(true);
            if (get_option('fds_llms_version_disk') !== '5.0') {
                update_option('fds_llms_version_disk', '5.0');
            }

            if ($purge_litespeed && function_exists('has_action') && has_action('litespeed_purge_all')) {
                do_action('litespeed_purge_all');
            }

            $GLOBALS['fds_ai_seo_purge_dirty'] = false;
        }
    } finally {
        $is_purging = false;
    }
}

/**
 * Debounce lifecycle hook purges into a single shutdown pass so multi-option forms
 * and bulk post saves trigger at most one disk + sitemap + LiteSpeed rebuild per request.
 */
function fds_schedule_ai_seo_cache_purge(bool $purge_litespeed = true): void {
    $GLOBALS['fds_ai_seo_purge_dirty'] = true;
    if ($purge_litespeed) {
        $GLOBALS['fds_ai_seo_purge_ls'] = true;
    }

    // Immediate in-memory & transient flush
    fds_purge_ai_and_seo_caches(false, false);

    if (empty($GLOBALS['fds_ai_seo_purge_shutdown_registered'])) {
        $GLOBALS['fds_ai_seo_purge_shutdown_registered'] = true;
        add_action('shutdown', function () {
            if (!empty($GLOBALS['fds_ai_seo_purge_dirty'])) {
                $do_ls = !empty($GLOBALS['fds_ai_seo_purge_ls']);
                fds_purge_ai_and_seo_caches($do_ls, true);
            }
        }, 99);
    }
}

// Otomatis sinkronkan cache AI (/llms.txt & /llms-full.txt), Sitemap & Page Cache saat konten diterbitkan/diubah
foreach (['post', 'page', 'drone', 'mitra'] as $fds_watched_pt) {
    add_action("save_post_{$fds_watched_pt}", function ($post_id, $post = null) {
        if (get_transient('fds_migration_2026_lock') || wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }
        $p = $post ?: get_post($post_id);
        if (!$p || $p->post_status === 'auto-draft') {
            return;
        }
        fds_schedule_ai_seo_cache_purge(true);
    }, 20, 2);
}

add_action('transition_post_status', function ($new_status, $old_status, $post) {
    if (get_transient('fds_migration_2026_lock') || !$post || $new_status === $old_status) {
        return;
    }
    if (!in_array($post->post_type, ['post', 'page', 'drone', 'mitra'], true)) {
        return;
    }
    if ($new_status === 'publish' || $old_status === 'publish') {
        fds_schedule_ai_seo_cache_purge(true);
    }
}, 20, 3);

add_action('trashed_post', function ($post_id) {
    if (!get_transient('fds_migration_2026_lock') && in_array(get_post_type($post_id), ['post', 'page', 'drone', 'mitra'], true)) {
        fds_schedule_ai_seo_cache_purge(true);
    }
}, 20);

add_action('untrashed_post', function ($post_id) {
    if (!get_transient('fds_migration_2026_lock') && in_array(get_post_type($post_id), ['post', 'page', 'drone', 'mitra'], true)) {
        fds_schedule_ai_seo_cache_purge(true);
    }
}, 20);

add_action('deleted_post', function ($post_id, $post = null) {
    $pt = ($post && isset($post->post_type)) ? $post->post_type : get_post_type($post_id);
    if (!get_transient('fds_migration_2026_lock') && in_array($pt, ['post', 'page', 'drone', 'mitra'], true)) {
        fds_schedule_ai_seo_cache_purge(true);
    }
}, 20, 2);

add_action('created_term', function ($term_id, $tt_id, $taxonomy) {
    if (!get_transient('fds_migration_2026_lock') && in_array($taxonomy, ['category', 'post_tag', 'kategori_drone'], true)) {
        fds_schedule_ai_seo_cache_purge(true);
    }
}, 20, 3);

add_action('edited_term', function ($term_id, $tt_id, $taxonomy) {
    if (!get_transient('fds_migration_2026_lock') && in_array($taxonomy, ['category', 'post_tag', 'kategori_drone'], true)) {
        fds_schedule_ai_seo_cache_purge(true);
    }
}, 20, 3);

add_action('delete_term', function ($term_id, $tt_id, $taxonomy) {
    if (!get_transient('fds_migration_2026_lock') && in_array($taxonomy, ['category', 'post_tag', 'kategori_drone'], true)) {
        fds_schedule_ai_seo_cache_purge(true);
    }
}, 20, 3);

add_action('updated_option', function ($option_name) {
    if (!get_transient('fds_migration_2026_lock') && fds_is_purge_relevant_option((string) $option_name)) {
        fds_schedule_ai_seo_cache_purge(true);
    }
}, 20, 1);

add_action('added_option', function ($option_name) {
    if (!get_transient('fds_migration_2026_lock') && fds_is_purge_relevant_option((string) $option_name)) {
        fds_schedule_ai_seo_cache_purge(true);
    }
}, 20, 1);

add_action('deleted_option', function ($option_name) {
    if (!get_transient('fds_migration_2026_lock') && fds_is_purge_relevant_option((string) $option_name)) {
        fds_schedule_ai_seo_cache_purge(true);
    }
}, 20, 1);

/**
 * Intercept /llms.txt & /llms-full.txt immediately on init (priority 25 — after CPTs/rewrites register,
 * before redirect_canonical can 301-redirect)
 */
add_action('init', function () {
    $req_path = trim((string) strtok($_SERVER['REQUEST_URI'] ?? '', '?'), '/');
    if ($req_path === 'llms.txt' || $req_path === 'llms-full.txt') {
        $is_full = ($req_path === 'llms-full.txt');
        status_header(200);
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=86400');
        header('X-LiteSpeed-Cache-Control: public, max-age=604800');
        echo fds_build_llms_txt_content($is_full);
        exit;
    }

    if ($req_path === 'sitemap.xml' && defined('RANK_MATH_VERSION')) {
        wp_redirect(home_url('/sitemap_index.xml'), 301);
        exit;
    }

    // Ensure static physical llms.txt & llms-full.txt on disk are always refreshed to version 5.0
    if (defined('ABSPATH') && is_writable(ABSPATH)) {
        $llms_disk_ver = get_option('fds_llms_version_disk', '0');
        if ($llms_disk_ver !== '5.0' || isset($_GET['refresh_llms'])) {
            delete_transient('fds_llms_txt_cache_v2');
            delete_transient('fds_llms_txt_cache_v3');
            delete_transient('fds_llms_txt_cache_v4');
            delete_transient('fds_llms_txt_cache_v5');
            delete_transient('fds_llms_full_txt_cache_v5');
            fds_build_llms_txt_content(false);
            fds_build_llms_txt_content(true);
            update_option('fds_llms_version_disk', '5.0');
        }
    }

    add_rewrite_rule('^llms\.txt$', 'index.php?fds_llms_txt=summary', 'top');
    add_rewrite_rule('^llms-full\.txt$', 'index.php?fds_llms_txt=full', 'top');
    add_rewrite_rule('^sitemap\.xml$', 'index.php?fds_sitemap_xml=1', 'top');
}, 25);

add_filter('redirect_canonical', function ($redirect_url, $requested_url) {
    if (preg_match('#/(llms(-full)?\.txt|sitemap\.xml)/?$#i', $requested_url)) {
        return false;
    }
    return $redirect_url;
}, 1, 2);

add_filter('query_vars', function ($vars) {
    $vars[] = 'fds_llms_txt';
    $vars[] = 'fds_sitemap_xml';
    return $vars;
});

/**
 * Handle /llms.txt, /llms-full.txt fallback and complete standalone /sitemap.xml rendering
 */
add_action('template_redirect', function () {
    // 1. LLMS.TXT & LLMS-FULL.TXT ENDPOINTS (GEO Standard 2024-2026)
    $llms_mode = (string) get_query_var('fds_llms_txt', '');
    if ($llms_mode !== '') {
        $req_only = trim((string) strtok($_SERVER['REQUEST_URI'] ?? '', '?'), '/');
        $is_full  = ($llms_mode === 'full' || $req_only === 'llms-full.txt');
        status_header(200);
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=86400');
        echo fds_build_llms_txt_content($is_full);
        exit;
    }

    // 2. STANDALONE SITEMAP.XML FALLBACK (Complete deduplicated XML when Rank Math is inactive)
    if (get_query_var('fds_sitemap_xml')) {
        if (defined('RANK_MATH_VERSION')) {
            wp_redirect(home_url('/sitemap_index.xml'), 301);
            exit;
        }

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $url_map    = [];
        $zombie_map = function_exists(__NAMESPACE__ . '\\fds_get_legacy_zombie_redirect_map')
            ? fds_get_legacy_zombie_redirect_map()
            : [];
        $utility_exclude = ['karir', 'disclaimer', 'terima-kasih', 'sample-page'];

        $add_url = static function (string $loc, string $priority = '0.7', string $freq = 'weekly', string $lastmod = '') use (&$url_map) {
            $clean_loc = trailingslashit(trim($loc));
            if ($clean_loc === '' || $clean_loc === '/') {
                return;
            }
            $key = strtolower(untrailingslashit($clean_loc));
            if (!isset($url_map[$key])) {
                $url_map[$key] = [
                    'loc'      => $clean_loc,
                    'priority' => $priority,
                    'freq'     => $freq,
                    'lastmod'  => $lastmod,
                ];
            } elseif ($lastmod !== '' && $url_map[$key]['lastmod'] === '') {
                $url_map[$key]['lastmod'] = $lastmod;
            }
        };

        $is_post_noindexed = static function (int $post_id): bool {
            $rm = get_post_meta($post_id, 'rank_math_robots', true);
            return is_array($rm) && in_array('noindex', $rm, true);
        };

        // 2a. Strategic Root & Static Pages (preferring WP modified date when backed by a page)
        $static_routes = [
            '/'               => ['priority' => '1.0', 'freq' => 'daily', 'path' => ''],
            '/produk/'        => ['priority' => '0.95', 'freq' => 'weekly', 'path' => 'produk'],
            '/tentang-kami/'  => ['priority' => '0.9', 'freq' => 'weekly', 'path' => 'tentang-kami'],
            '/bandingkan/'    => ['priority' => '0.9', 'freq' => 'weekly', 'path' => 'bandingkan'],
            '/kontak/'        => ['priority' => '0.8', 'freq' => 'monthly', 'path' => 'kontak'],
            '/blog/'          => ['priority' => '0.8', 'freq' => 'daily', 'path' => 'blog'],
            '/privacy-policy/'=> ['priority' => '0.4', 'freq' => 'yearly', 'path' => 'privacy-policy'],
        ];
        foreach ($static_routes as $route => $cfg) {
            $lastmod = '';
            if ($cfg['path'] === '') {
                $fid = (int) get_option('page_on_front', 0);
                if ($fid > 0) {
                    $lastmod = (string) get_the_modified_date('c', $fid);
                }
            } else {
                $pg = get_page_by_path($cfg['path']);
                if ($pg && $pg->post_status === 'publish') {
                    if ($is_post_noindexed((int) $pg->ID)) {
                        continue;
                    }
                    $lastmod = (string) get_the_modified_date('c', $pg->ID);
                }
            }
            $add_url(home_url($route), $cfg['priority'], $cfg['freq'], $lastmod);
        }

        // 2b. Hub, 6 Sektor Industri & 3 Kategori Layanan
        if (function_exists(__NAMESPACE__ . '\\fds_get_all_layanan_paths')) {
            foreach (fds_get_all_layanan_paths() as $lp) {
                $pg      = get_page_by_path($lp);
                $lastmod = ($pg && $pg->post_status === 'publish') ? (string) get_the_modified_date('c', $pg->ID) : '';
                if ($pg && $is_post_noindexed((int) $pg->ID)) {
                    continue;
                }
                $add_url(
                    home_url('/' . trailingslashit($lp)),
                    $lp === 'layanan' ? '0.95' : '0.90',
                    'weekly',
                    $lastmod
                );
            }
        }

        // 2c. Published Custom WP Pages (non-utility, non-zombie, non-noindex)
        $all_pages = get_posts([
            'post_type'      => 'page',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);
        foreach ($all_pages as $pg) {
            $p_slug = strtolower((string) $pg->post_name);
            if (
                $p_slug === ''
                || in_array($p_slug, $utility_exclude, true)
                || isset($zombie_map[$p_slug])
                || strpos($p_slug, 'rebo') !== false
                || strpos($p_slug, 'agrea') !== false
                || strpos($p_slug, 'ferto-20') !== false
                || $is_post_noindexed((int) $pg->ID)
            ) {
                continue;
            }
            $pl = get_permalink($pg->ID);
            if (is_string($pl) && $pl !== '') {
                $add_url($pl, '0.75', 'weekly', (string) get_the_modified_date('c', $pg->ID));
            }
        }

        // 2d. Kategori Drone Taxonomy Archives (active terms only)
        $drone_cats = get_terms([
            'taxonomy'   => 'kategori_drone',
            'hide_empty' => true,
        ]);
        if (!empty($drone_cats) && !is_wp_error($drone_cats)) {
            foreach ($drone_cats as $dc) {
                $dc_slug = strtolower((string) $dc->slug);
                if (strpos($dc_slug, 'rebo') !== false || strpos($dc_slug, 'agrea') !== false) {
                    continue;
                }
                $rm_term = get_term_meta((int) $dc->term_id, 'rank_math_robots', true);
                if (is_array($rm_term) && in_array('noindex', $rm_term, true)) {
                    continue;
                }
                $term_link = get_term_link($dc);
                if (!is_wp_error($term_link) && is_string($term_link)) {
                    $add_url($term_link, '0.85', 'weekly');
                }
            }
        }

        // 2e. Active CPT Drone Models (sorted)
        $drones = get_posts([
            'post_type'      => 'drone',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);
        if (function_exists('fds_sort_drone_posts') && is_array($drones)) {
            $drones = \fds_sort_drone_posts($drones);
        }
        foreach ($drones as $d) {
            $slug_check = strtolower((string) $d->post_name);
            if (
                in_array($slug_check, ['rebo', 'agrea-15', 'agrea-10'], true)
                || strpos($slug_check, 'rebo') !== false
                || strpos($slug_check, 'agrea') !== false
                || strpos($slug_check, 'ferto-20') !== false
                || $is_post_noindexed((int) $d->ID)
            ) {
                continue;
            }
            $dl = get_permalink($d->ID);
            if (is_string($dl) && $dl !== '') {
                $add_url($dl, '0.85', 'weekly', (string) get_the_modified_date('c', $d->ID));
            }
        }

        // 2f. Published Newsroom / Blog Posts
        $posts = get_posts([
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        foreach ($posts as $bp) {
            $bp_slug = strtolower((string) $bp->post_name);
            if (
                $bp_slug === ''
                || isset($zombie_map[$bp_slug])
                || strpos($bp_slug, 'rebo') !== false
                || strpos($bp_slug, 'agrea') !== false
                || strpos($bp_slug, 'ferto-20') !== false
                || $is_post_noindexed((int) $bp->ID)
            ) {
                continue;
            }
            $bl = get_permalink($bp->ID);
            if (is_string($bl) && $bl !== '') {
                $add_url($bl, '0.75', 'weekly', (string) get_the_modified_date('c', $bp->ID));
            }
        }

        // 2g. Non-empty Blog Categories (excluding uncategorized & noindex)
        $post_cats = get_terms([
            'taxonomy'   => 'category',
            'hide_empty' => true,
        ]);
        if (!empty($post_cats) && !is_wp_error($post_cats)) {
            foreach ($post_cats as $pc) {
                $pc_slug = strtolower((string) $pc->slug);
                if ($pc_slug === 'uncategorized' || strpos($pc_slug, 'rebo') !== false || strpos($pc_slug, 'agrea') !== false) {
                    continue;
                }
                $rm_term = get_term_meta((int) $pc->term_id, 'rank_math_robots', true);
                if (is_array($rm_term) && in_array('noindex', $rm_term, true)) {
                    continue;
                }
                $cat_link = get_term_link($pc);
                if (!is_wp_error($cat_link) && is_string($cat_link)) {
                    $add_url($cat_link, '0.60', 'weekly');
                }
            }
        }

        foreach ($url_map as $u) {
            echo "  <url>\n";
            echo "    <loc>" . esc_url($u['loc']) . "</loc>\n";
            if (!empty($u['lastmod'])) {
                echo "    <lastmod>" . esc_html($u['lastmod']) . "</lastmod>\n";
            }
            echo "    <changefreq>" . esc_html($u['freq']) . "</changefreq>\n";
            echo "    <priority>" . esc_html($u['priority']) . "</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>' . "\n";
        exit;
    }
});
