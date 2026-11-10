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
    $prev = '';
    $max  = 5;
    while ($prev !== $value && $max-- > 0) {
        $prev  = $value;
        $value = html_entity_decode(wp_specialchars_decode($value, ENT_QUOTES), ENT_QUOTES, 'UTF-8');
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
            'drone ferto 20l',
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
            'drone multipurpose thermal flir',
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
 * Get comprehensive SEO metadata for the current query/page
 */
function fds_get_seo_data() {
    global $post, $wp;

    $site_name   = get_bloginfo('name') ?: 'Full Drone Solutions';
    $site_desc   = get_bloginfo('description') ?: 'Produsen UAV Indonesia — Solusi Pertanian Presisi, Pemetaan GIS & Industri';
    $current_url = home_url(add_query_arg([], $wp->request));
    
    // Core high-intent keywords (Default enriched drone keywords + custom admin keywords)
    $primary_keywords = fds_get_primary_keywords_string();

    // Default fallback values
    $title       = $site_name . ' — ' . $site_desc;
    $description = 'PT Karya Solusi Angkasa (FDS), produsen drone pertanian, pemetaan VTOL, logistik & surveillance (TKDN 60,74%). Melayani jasa foto udara, survey LiDAR, konsultan GIS, dan pemetaan kawasan KEK, tambang, kehutanan, serta industri.';
    $keywords    = $primary_keywords;
    $image_url   = '';
    $og_type     = 'website';
    $canonical   = trailingslashit($current_url);
    $breadcrumbs = [
        ['name' => 'Beranda', 'url' => home_url('/')]
    ];

    // 1. FRONT PAGE / HOMEPAGE (Fokus 4 Pilar: Produsen Drone, Foto Udara & LiDAR, Konsultan GIS, Pemetaan Kawasan KEK/Tambang)
    if (is_front_page()) {
        $title       = 'Produsen Drone Pertanian & UAV TKDN — Full Drone Solutions';
        $description = 'PT Karya Solusi Angkasa (FDS), produsen drone & UAV Indonesia TKDN. Solusi drone sprayer FERTO, survey LiDAR, pemetaan GIS, dan pelatihan pilot resmi.';
        $keywords    = $primary_keywords;
        $canonical   = trailingslashit(home_url('/'));
        $image_url   = fds_get_default_share_image();
    }
    // 2. SINGLE DRONE CPT PAGE
    elseif (is_singular('drone') || get_query_var('drone')) {
        $drone_post = get_post();
        if ($drone_post) {
            $slug        = $drone_post->post_name;
            $d_title     = fds_decode_text(get_the_title($drone_post->ID));
            $model_base  = trim((string) preg_replace('/\s*\(.*?\)/', '', $d_title)) ?: $d_title;
            $tagline     = fds_decode_text((string) get_post_meta($drone_post->ID, 'drone_tagline', true));
            $kategori    = fds_decode_text((string) (get_post_meta($drone_post->ID, 'drone_kategori', true) ?: 'UAV Industri'));
            $payload     = fds_decode_text((string) get_post_meta($drone_post->ID, 'drone_spec_kapasitas', true));
            $durasi      = fds_decode_text((string) get_post_meta($drone_post->ID, 'drone_spec_durasi', true));
            $feat_img    = get_the_post_thumbnail_url($drone_post->ID, 'full');

            $title       = fds_clamp_meta_title("{$model_base} — Spesifikasi Drone {$kategori} TKDN & SNI | FDS", 60);

            $desc_parts  = [];
            if ($tagline !== '') $desc_parts[] = $tagline;
            if ($payload !== '') $desc_parts[] = "Payload: {$payload}";
            if ($durasi !== '')  $desc_parts[] = "Durasi Terbang: {$durasi}";
            $desc_parts[] = 'Sertifikasi resmi TKDN 60,74% & SNI 9199:2023 buatan PT Karya Solusi Angkasa (FDS), produsen UAV Indonesia.';
            $description = fds_clamp_meta_desc(implode('. ', $desc_parts), 158);

            // Contextual keywords per category
            $cat_lower = strtolower($kategori);
            if (strpos($cat_lower, 'agri') !== false || strpos($slug, 'ferto') !== false) {
                $keywords = fds_get_primary_keywords_string("drone pertanian, drone sprayer {$model_base}, jual drone pertanian {$slug}, drone penyemprot pupuk dan pestisida, drone sebar benih, drone pertanian indonesia, produsen UAV Indonesia, TKDN 60,74%, SNI 9199, {$payload}");
            } elseif (strpos($cat_lower, 'peta') !== false || strpos($cat_lower, 'gis') !== false || strpos($slug, 'deltav') !== false) {
                $keywords = fds_get_primary_keywords_string("drone mapping, jual drone mapping, jasa pemetaan drone, jasa survey drone, jasa drone foto udara, jasa drone lidar, jasa pemetaan drone tambang, pemetaan GIS drone, fixed wing VTOL, {$model_base}");
            } elseif (strpos($cat_lower, 'kargo') !== false) {
                $keywords = fds_get_primary_keywords_string("drone kargo logistik darurat, drone medis, drone payload besar, drone tanggap darurat, UAV Indonesia {$model_base}");
            } else {
                $keywords = fds_get_primary_keywords_string("Drone {$model_base}, Drone {$kategori}, produsen UAV Indonesia, jasa survey drone, inspeksi drone, PT Karya Solusi Angkasa");
            }

            $canonical   = trailingslashit(get_permalink($drone_post->ID));
            $image_url   = $feat_img ?: fds_get_default_share_image();
            $og_type     = 'product';

            $breadcrumbs[] = ['name' => 'Katalog Drone', 'url' => home_url('/#produk')];
            $breadcrumbs[] = ['name' => $d_title, 'url' => $canonical];
        }
    }
    // 3. KATEGORI DRONE TAXONOMY ARCHIVE
    elseif (is_tax('kategori_drone')) {
        $term      = get_queried_object();
        $term_name = ($term && !empty($term->name)) ? fds_decode_text($term->name) : 'Industri';
        $term_slug = ($term && !empty($term->slug)) ? $term->slug : '';
        $term_link = $term ? get_term_link($term) : home_url('/#produk');
        $canonical = !is_wp_error($term_link) ? trailingslashit($term_link) : trailingslashit(home_url('/#produk'));
        $image_url = fds_get_default_share_image();

        if ($term_slug === 'agrikultur') {
            $title       = 'Drone Agrikultur & Sprayer TKDN — Full Drone Solutions';
            $description = 'Katalog drone pertanian & sprayer presisi seri FERTO (5L–50L) buatan Indonesia berstandar TKDN 60,74%, SNI 9199:2023 & FDS STATION.';
        } elseif ($term_slug === 'pemetaan-gis') {
            $title       = 'Drone Pemetaan & Survey LiDAR GIS — Full Drone Solutions';
            $description = 'Katalog drone pemetaan Fixed-Wing VTOL DELTAV dan UAV MULTIPURPOSE untuk foto udara, survey LiDAR, inspeksi aset 150kV, dan pemetaan GIS.';
        } elseif ($term_slug === 'kargo') {
            $title       = 'Drone Kargo Logistik Otonom TKDN — Full Drone Solutions';
            $description = 'Platform drone kargo logistik DELFRO payload hingga 10 kg buatan Indonesia untuk distribusi medis darurat, suku cadang, & wilayah kepulauan.';
        } else {
            $title       = fds_clamp_meta_title("Drone {$term_name} — Solusi UAV TKDN & SNI | FDS", 60);
            $description = fds_clamp_meta_desc("Katalog resmi drone {$term_name} berstandar TKDN 60,74% & SNI 9199:2023 buatan PT Karya Solusi Angkasa (Full Drone Solutions).", 158);
        }

        $breadcrumbs[] = ['name' => 'Katalog Drone', 'url' => home_url('/#produk')];
        $breadcrumbs[] = ['name' => $term_name, 'url' => $canonical];
    }
    // 4. BANDINGKAN DRONE PAGE
    elseif (is_page('bandingkan')) {
        $title       = 'Bandingkan Spesifikasi Drone — Komparasi Lini UAV FDS';
        $description = 'Bandingkan spesifikasi teknis drone sprayer FERTO, drone pemetaan VTOL DELTAV, dan kargo DELFRO berstandar TKDN & SNI buatan PT Karya Solusi Angkasa.';
        $keywords    = 'drone pertanian, komparasi drone pertanian, spesifikasi drone pertanian TKDN, drone mapping, jasa pemetaan drone, produsen UAV Indonesia';
        $canonical   = trailingslashit(home_url('/bandingkan/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Bandingkan Drone', 'url' => $canonical];
    }
    // 5. TENTANG KAMI PAGE
    elseif (is_page('tentang-kami')) {
        $title       = 'Tentang Kami — PT Karya Solusi Angkasa (Produsen UAV)';
        $description = 'Profil PT Karya Solusi Angkasa (FDS), produsen drone & UAV Indonesia sejak 2012. Fasilitas riset, perakitan lokal, dan sertifikasi TKDN resmi di Sleman.';
        $keywords    = 'produsen UAV Indonesia, PT Karya Solusi Angkasa, pabrik drone Indonesia, produsen drone pertanian, jasa pemetaan drone, drone TKDN SNI Yogyakarta, FDS';
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
                if (!empty($slide['img']) && stripos((string) $slide['img'], 'logo-fds-academy') === false) {
                    return (string) $slide['img'];
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
    echo "    <meta name=\"robots\" content=\"index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1\">\n";

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
 * Generate JSON-LD Structured Data (Schema.org) ONLY when external SEO plugin (Rank Math/Yoast) is inactive.
 * When Rank Math is active, schema enrichment runs via `rank_math/json_ld` single graph filter to prevent collision.
 */
function fds_render_schema_jsonld() {
    if (fds_has_external_seo_plugin()) {
        return;
    }

    $seo = fds_get_seo_data();
    $home_url = trailingslashit(home_url('/'));
    $logo_url = fds_get_default_share_image();

    // Ambil data kontak & alamat resmi dinamis dari Theme Settings
    $contact  = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
    $phone    = !empty($contact['phone']) ? $contact['phone'] : '+62 8112 748 882';
    $email    = !empty($contact['email']) ? $contact['email'] : 'marketing@fulldronesolutions.com';
    $raw_addr = !empty($contact['address']) ? wp_strip_all_tags($contact['address']) : 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285';
    $postal   = '55285';
    if (preg_match('/\b(\d{5})\b/', $raw_addr, $pm)) {
        $postal = $pm[1];
    }

    // 1. Organization & LocalBusiness Schema (B2B UAV Manufacturer - Bebas Price Slop)
    $org_schema = [
        '@context'        => 'https://schema.org',
        '@type'           => ['Organization', 'Corporation', 'LocalBusiness'],
        '@id'             => $home_url . '#organization',
        'name'            => 'PT Karya Solusi Angkasa',
        'alternateName'   => ['Full Drone Solutions', 'FDS UAV Indonesia', 'FDS'],
        'legalName'       => 'PT Karya Solusi Angkasa',
        'url'             => $home_url,
        'logo'            => [
            '@type'      => 'ImageObject',
            '@id'        => $home_url . '#logo',
            'url'        => $logo_url,
            'caption'    => 'Full Drone Solutions Logo',
        ],
        'image'           => $logo_url,
        'description'     => 'Produsen UAV Indonesia terkemuka (PT Karya Solusi Angkasa) penyedia jasa pemetaan drone tambang, survey drone LiDAR, drone pertanian sprayer TKDN 60,74%, inspeksi termal, dan drone pencarian korban bencana.',
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
            'Jual Drone & Pengadaan Drone Pemerintah BUMN',
            'Drone Pertanian & Sprayer Presisi TKDN 60,74%',
            'Pengadaan Drone Pertanian E-Katalog LKPP',
            'Drone Penyemprot Pupuk & Pestisida Tanaman Pangan & Sawit',
            'Drone Sebar Benih & Pemupukan Kelapa Sawit',
            'Jual Drone Pertanian & Servis Suku Cadang Resmi',
            'Drone Mapping & Fotogrametri Udara Sub-Sentimeter',
            'Jasa Pemetaan Drone & Survey Drone LiDAR Akurasi Tinggi',
            'Jasa Pemetaan Drone Tambang (Volume Cut & Fill / Stockpile)',
            'Jasa Drone Foto Udara & Pemetaan GIS Topografi',
            'UAV Fixed-Wing Hybrid VTOL DELTAV Jelajah Panjang',
            'Drone Pencarian Korban Bencana & Misi SAR (Thermal FLIR)',
            'Drone Kargo Logistik Medis Darurat',
            'Inspeksi Termal Infrastruktur Energi, SUTET 150kV & Migas',
            'Sistem Kendali Wahana FDS STATION (Bahasa Indonesia)',
            'Produsen UAV Indonesia Resmi (PT Karya Solusi Angkasa)',
            'Sertifikasi TKDN & BMP hingga 60,74% Kemenperin RI',
            'Standar Nasional Indonesia SNI 9199:2023',
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
                    'name'  => 'Pilar 1: Produsen Drone Pertanian, Pemetaan, Logistik & Surveillance (TKDN 60,74%)',
                    'itemListElement' => [
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Produsen Drone Pertanian Sprayer & Granule FERTO (5L–50L)']],
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

    // 4. Service Schemas for 4 Target Core Focus Pillars (High-value B2B Intent)
    $services_schema = [
        // Pilar 1: Produsen Drone Pertanian, Pemetaan, Logistik & Surveillance
        [
            '@context'      => 'https://schema.org',
            '@type'         => 'Service',
            '@id'           => $home_url . '#service-produsen-drone',
            'name'          => 'Produsen Drone Pertanian, Pemetaan, Logistik & Surveillance (TKDN 60,74%)',
            'serviceType'   => 'UAV Manufacturing, Agricultural Drone Sprayer, VTOL Mapping Drone, Logistics & Surveillance Drone',
            'provider'      => ['@id' => $home_url . '#organization'],
            'areaServed'    => ['@type' => 'Country', 'name' => 'Indonesia'],
            'description'   => 'Manufaktur resmi pesawat tanpa awak (UAV) Indonesia dengan sertifikasi TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015. Memproduksi drone pertanian sprayer FERTO (5L–50L), Fixed-Wing VTOL DELTAV jangkauan 60 km, drone kargo DELFRO, dan drone surveillance kamera termal FLIR.',
            'offers'        => [
                '@type'         => 'Offer',
                'priceCurrency' => 'IDR',
                'availability'  => 'https://schema.org/InStock',
                'url'           => $home_url . '#produk',
            ]
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
            'offers'        => [
                '@type'         => 'Offer',
                'priceCurrency' => 'IDR',
                'availability'  => 'https://schema.org/InStock',
                'url'           => $home_url . '#solusi',
            ]
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
            'offers'        => [
                '@type'         => 'Offer',
                'priceCurrency' => 'IDR',
                'availability'  => 'https://schema.org/InStock',
                'url'           => $home_url . '#layanan',
            ]
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
            'description'   => 'Layanan pemetaan udara dan survei geospasial khusus: masterplan Kawasan Ekonomi Khusus (KEK), perhitungan volume stockpile serta cut and fill tambang (akurasi ±2.35%), inventarisasi biomassa dan karbon kawasan kehutanan, serta survei koridor utilitas kawasan industri.',
            'offers'        => [
                '@type'         => 'Offer',
                'priceCurrency' => 'IDR',
                'availability'  => 'https://schema.org/InStock',
                'url'           => $home_url . '#kontak',
            ]
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
                'name'           => 'Apakah PT Karya Solusi Angkasa (FDS) merupakan produsen drone pertanian, pemetaan, logistik, dan surveillance berstandar TKDN di Indonesia?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'Ya. PT Karya Solusi Angkasa (Full Drone Solutions / FDS) adalah produsen dan manufaktur UAV resmi buatan Indonesia sejak 2012 yang memproduksi drone pertanian presisi (seri FERTO 5L hingga 50L), drone pemetaan Fixed-Wing VTOL DELTAV jarak 60 km, drone kargo logistik DELFRO, dan drone surveillance thermal MULTIPURPOSE dengan sertifikasi resmi TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015 di Sleman, Yogyakarta.',
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
                    'text'  => 'FDS menyediakan survei terintegrasi untuk kawasan strategis: pemetaan masterplan Kawasan Ekonomi Khusus (KEK), survei pit tambang batubara/nikel untuk perhitungan volume stockpile dan cut & fill dengan akurasi tinggi (±2.35%), inventarisasi tutupan pohon dan stok karbon kawasan kehutanan, serta survei topografi lahan dan inspeksi koridor utilitas kawasan industri.',
                ],
            ],
        ],
    ];

    $schemas = [$org_schema, $website_schema, $breadcrumb_schema];
    foreach ($services_schema as $srv) {
        $schemas[] = $srv;
    }
    $schemas[] = $faq_schema;

    // 5b. ItemList Product Catalog Schema on Homepage & /bandingkan/ (Surfaces Product Lineup in Google Search)
    if (is_front_page() || is_page('bandingkan') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/bandingkan') !== false)) {
        $catalog_drones = get_posts([
            'post_type'      => 'drone',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'ID',
            'order'          => 'ASC',
        ]);
        if (!empty($catalog_drones)) {
            $item_elements = [];
            $item_pos = 1;
            foreach ($catalog_drones as $cd) {
                $cd_title   = get_the_title($cd->ID);
                $cd_url     = get_permalink($cd->ID);
                $cd_tagline = get_post_meta($cd->ID, 'drone_tagline', true);
                $cd_payload = get_post_meta($cd->ID, 'drone_spec_kapasitas', true);
                $item_elements[] = [
                    '@type'       => 'ListItem',
                    'position'    => $item_pos++,
                    'name'        => $cd_title . ($cd_payload ? " ({$cd_payload})" : ''),
                    'description' => wp_strip_all_tags($cd_tagline ?: "Drone UAV {$cd_title} buatan Indonesia berstandar TKDN 60,74% & SNI 9199:2023."),
                    'url'         => $cd_url,
                ];
            }
            $schemas[] = [
                '@context'        => 'https://schema.org',
                '@type'           => 'ItemList',
                '@id'             => $home_url . '#drone-catalog-list',
                'name'            => 'Katalog Produk Drone Pertanian, Pemetaan LiDAR, Kargo & Inspeksi FDS (TKDN 60,74%)',
                'itemListElement' => $item_elements,
            ];
        }
    }

    // 6. Product Schema (Only on Single Drone CPT Page)
    if (is_singular('drone') || get_query_var('drone')) {
        $drone_post = get_post();
        if ($drone_post) {
            $d_title    = get_the_title($drone_post->ID);
            $d_desc     = get_post_meta($drone_post->ID, 'drone_desc', true) ?: $drone_post->post_content;
            $d_tagline  = get_post_meta($drone_post->ID, 'drone_tagline', true);
            $kategori   = get_post_meta($drone_post->ID, 'drone_kategori', true) ?: 'UAV Industri';
            $feat_img   = get_the_post_thumbnail_url($drone_post->ID, 'full') ?: $logo_url;
            $permalink  = get_permalink($drone_post->ID);

            $payload    = get_post_meta($drone_post->ID, 'drone_spec_kapasitas', true);
            $durasi     = get_post_meta($drone_post->ID, 'drone_spec_durasi', true);
            $baterai    = get_post_meta($drone_post->ID, 'drone_spec_baterai', true);
            $kecepatan  = get_post_meta($drone_post->ID, 'drone_spec_kecepatan', true);
            $gcs        = get_post_meta($drone_post->ID, 'drone_spec_gcs', true) ?: 'FDS STATION (Bahasa Indonesia)';
            $sertifikasi= get_post_meta($drone_post->ID, 'drone_spec_sertifikasi', true) ?: 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015';

            $additional_properties = [];
            if ($payload)     $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Payload', 'value' => $payload];
            if ($durasi)      $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Durasi Terbang', 'value' => $durasi];
            if ($baterai)     $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Sistem Daya Baterai', 'value' => $baterai];
            if ($kecepatan)   $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Kecepatan Jelajah', 'value' => $kecepatan];
            if ($gcs)         $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Ground Control Station', 'value' => $gcs];
            if ($sertifikasi) $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Sertifikasi & Standar Mutu', 'value' => $sertifikasi];

            $product_schema = [
                '@context'            => 'https://schema.org',
                '@type'               => 'Product',
                '@id'                 => $permalink . '#product',
                'name'                => $d_title,
                'image'               => [$feat_img],
                'description'         => wp_strip_all_tags($d_desc ?: $d_tagline),
                'category'            => "UAV / Drone {$kategori}",
                'brand'               => [
                    '@type' => 'Brand',
                    'name'  => 'Full Drone Solutions',
                ],
                'manufacturer'        => [
                    '@id' => $home_url . '#organization',
                ],
                'additionalProperty'  => $additional_properties,
            ];

            $schemas[] = $product_schema;
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

// Harmonisasikan Schema Rank Math dalam satu Unified @graph (bebas duplikasi, priceRange, & Offer tanpa harga)
add_filter('rank_math/json_ld', function ($data, $jsonld) {
    if (!is_array($data)) {
        return $data;
    }

    $home_url       = trailingslashit(home_url('/'));
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

    // Hapus node Article/BlogPosting/NewsArticle atau Product nyasar pada halaman non-artikel / non-produk
    $is_non_article_landing = is_front_page() || is_page(['bandingkan', 'kontak', 'contact']) || is_tax('kategori_drone');
    if ($is_non_article_landing) {
        foreach ($data as $key => $node) {
            if (!is_array($node)) {
                continue;
            }
            $node_type = $node['@type'] ?? '';
            $types     = is_array($node_type) ? $node_type : [$node_type];
            if (array_intersect($types, ['Article', 'BlogPosting', 'NewsArticle', 'Product'])) {
                unset($data[$key]);
            }
        }
    }

    $text_keys = ['name', 'headline', 'description', 'caption', 'alternateName', 'legalName'];

    $sanitize_node = function (&$node) use (&$sanitize_node, $official_addr, $official_phone, $official_email, $postal, $text_keys) {
        if (!is_array($node)) {
            return;
        }

        // Hapus priceRange agar SERP tidak pernah menampilkan baris biaya "Price: $$$$"
        if (array_key_exists('priceRange', $node)) {
            unset($node['priceRange']);
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
            $node['name']          = 'PT Karya Solusi Angkasa';
            $node['alternateName'] = ['Full Drone Solutions', 'FDS UAV Indonesia', 'FDS'];
            $node['legalName']     = 'PT Karya Solusi Angkasa';
            $node['telephone']     = $official_phone;
            $node['email']         = $official_email;
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
                    'Produsen Drone Pertanian Sprayer FERTO (TKDN 60,74% & SNI 9199:2023)',
                    'Jasa Foto Udara Resolusi Tinggi & Survey Drone LiDAR',
                    'Konsultan GIS & Pengembangan WebGIS Enterprise',
                    'Pemetaan Kawasan KEK, Tambang, Kehutanan & Kawasan Industri',
                    'UAV Fixed-Wing Hybrid VTOL DELTAV & Drone Kargo DELFRO',
                ];
            }
            if (isset($node['logo']['caption']) && is_string($node['logo']['caption']) && stripos($node['logo']['caption'], 'supplier') !== false) {
                $node['logo']['caption'] = 'Full Drone Solutions — PT Karya Solusi Angkasa';
            }
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

    // 1. ItemList Katalog Drone pada Beranda, /bandingkan/, dan /kategori-drone/*
    if (is_front_page() || is_page('bandingkan') || is_tax('kategori_drone')) {
        $q_args = [
            'post_type'      => 'drone',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        ];
        $list_name = 'Katalog Produk Drone Pertanian, Pemetaan LiDAR, Kargo & Inspeksi FDS (TKDN 60,74%)';
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
                    'description' => $cd_tagline !== '' ? $cd_tagline : "Drone UAV {$cd_title} buatan Indonesia berstandar TKDN 60,74% & SNI 9199:2023.",
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
        $data['fdsContactPage'] = [
            '@type'       => 'ContactPage',
            '@id'         => home_url('/kontak/#contactpage'),
            'url'         => home_url('/kontak/'),
            'name'        => 'Kontak PT Karya Solusi Angkasa (Full Drone Solutions)',
            'description' => 'Halaman kontak resmi PT Karya Solusi Angkasa. Alamat workshop Sleman Yogyakarta, telepon/WhatsApp, dan konsultasi pengadaan drone TKDN.',
            'mainEntity'  => [
                '@id' => $home_url . '#organization',
            ],
        ];
    }

    // 3. Single Drone: Perkaya Product Schema + Emit FAQPage KHUSUS pada halaman detail produk (selaras konten terlihat)
    if (is_singular('drone')) {
        $drone_post = get_post();
        if ($drone_post) {
            $d_id        = $drone_post->ID;
            $d_title     = fds_decode_text(get_the_title($d_id));
            $d_desc      = fds_decode_text((string) (get_post_meta($d_id, 'drone_desc', true) ?: get_post_meta($d_id, 'drone_tagline', true)));
            $kategori    = fds_decode_text((string) (get_post_meta($d_id, 'drone_kategori', true) ?: 'UAV Industri'));
            $feat_img    = get_the_post_thumbnail_url($d_id, 'full') ?: fds_get_default_share_image();
            $permalink   = trailingslashit(get_permalink($d_id));

            $payload     = fds_decode_text((string) get_post_meta($d_id, 'drone_spec_kapasitas', true));
            $durasi      = fds_decode_text((string) get_post_meta($d_id, 'drone_spec_durasi', true));
            $baterai     = fds_decode_text((string) get_post_meta($d_id, 'drone_spec_baterai', true));
            $kecepatan   = fds_decode_text((string) get_post_meta($d_id, 'drone_spec_kecepatan', true));
            $gcs         = fds_decode_text((string) (get_post_meta($d_id, 'drone_spec_gcs', true) ?: 'FDS STATION (Bahasa Indonesia)'));
            $sertifikasi = fds_decode_text((string) (get_post_meta($d_id, 'drone_spec_sertifikasi', true) ?: 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015'));

            $additional_properties = [];
            if ($payload)     $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Payload', 'value' => $payload];
            if ($durasi)      $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Durasi Terbang', 'value' => $durasi];
            if ($baterai)     $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Sistem Daya Baterai', 'value' => $baterai];
            if ($kecepatan)   $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Kecepatan Jelajah', 'value' => $kecepatan];
            if ($gcs)         $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Ground Control Station', 'value' => $gcs];
            if ($sertifikasi) $additional_properties[] = ['@type' => 'PropertyValue', 'name' => 'Sertifikasi & Standar Mutu', 'value' => $sertifikasi];

            $found_product = false;
            foreach ($data as $k => &$n) {
                if (!is_array($n)) {
                    continue;
                }
                $nt = $n['@type'] ?? '';
                $ts = is_array($nt) ? $nt : [$nt];
                if (in_array('Product', $ts, true)) {
                    $found_product           = true;
                    $n['name']               = $d_title;
                    $n['category']           = "UAV / Drone {$kategori}";
                    $n['brand']              = ['@type' => 'Brand', 'name' => 'Full Drone Solutions'];
                    $n['manufacturer']       = ['@id' => $home_url . '#organization'];
                    if (!empty($additional_properties)) {
                        $n['additionalProperty'] = $additional_properties;
                    }
                    unset($n['offers']);
                }
            }
            unset($n);

            if (!$found_product) {
                $data['fdsDroneProduct'] = [
                    '@type'              => 'Product',
                    '@id'                => $permalink . '#product',
                    'name'               => $d_title,
                    'image'              => $feat_img ? [$feat_img] : [],
                    'description'        => $d_desc,
                    'category'           => "UAV / Drone {$kategori}",
                    'brand'              => ['@type' => 'Brand', 'name' => 'Full Drone Solutions'],
                    'manufacturer'       => ['@id' => $home_url . '#organization'],
                    'additionalProperty' => $additional_properties,
                ];
            }

            $data['fdsDroneFaq'] = [
                '@type'      => 'FAQPage',
                '@id'        => $permalink . '#faq',
                'mainEntity' => [
                    [
                        '@type'          => 'Question',
                        'name'           => "Apakah {$d_title} sudah memiliki sertifikasi TKDN resmi untuk pengadaan pemerintah?",
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text'  => "Ya. Seluruh lini drone yang diproduksi oleh PT Karya Solusi Angkasa, termasuk {$d_title}, telah terverifikasi memiliki nilai Bobot Manfaat Perusahaan (BMP) dan Tingkat Komponen Dalam Negeri (TKDN) hingga 60,74%, memenuhi kriteria pengadaan e-Katalog LKPP dan instansi BUMN.",
                        ],
                    ],
                    [
                        '@type'          => 'Question',
                        'name'           => 'Berapa lama garansi dan bagaimana ketersediaan suku cadang resminya?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text'  => 'FDS memberikan garansi resmi manufaktur untuk rangka dan sistem elektronik, didukung ketersediaan suku cadang asli ready-stock langsung dari pabrik kami di Sleman, Yogyakarta tanpa perlu menunggu impor.',
                        ],
                    ],
                    [
                        '@type'          => 'Question',
                        'name'           => 'Apakah operator pemula mendapatkan pelatihan sebelum menerbangkan unit?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text'  => 'Ya, setiap pembelian drone disertai dengan paket pelatihan pilot intensif mencakup prosedur keselamatan terbang, kalibrasi sensor, pengoperasian software FDS STATION, dan penanganan darurat hingga operator dinyatakan kompeten.',
                        ],
                    ],
                ],
            ];
        }
    }

    return $data;
}, 99, 2);

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
        return 'Produsen Drone Pertanian & UAV TKDN — Full Drone Solutions';
    }

    if (is_tax('kategori_drone')) {
        $term      = get_queried_object();
        $term_name = ($term && !empty($term->name)) ? fds_decode_text($term->name) : 'Industri';
        $term_slug = ($term && !empty($term->slug)) ? $term->slug : '';
        if ($term_slug === 'agrikultur') {
            return 'Drone Agrikultur & Sprayer TKDN — Full Drone Solutions';
        }
        if ($term_slug === 'pemetaan-gis') {
            return 'Drone Pemetaan & Survey LiDAR GIS — Full Drone Solutions';
        }
        if ($term_slug === 'kargo') {
            return 'Drone Kargo Logistik Otonom TKDN — Full Drone Solutions';
        }
        return fds_clamp_meta_title("Drone {$term_name} — Solusi UAV TKDN & SNI | FDS", 60);
    }

    if (is_page('tentang-kami')) {
        return 'Tentang Kami — PT Karya Solusi Angkasa (Produsen UAV)';
    }

    if (is_page(['kontak', 'contact'])) {
        return 'Hubungi Kami — Konsultasi Resmi Full Drone Solutions';
    }

    if (is_page('bandingkan')) {
        return 'Bandingkan Spesifikasi Drone — Komparasi Lini UAV FDS';
    }

    if (is_home()) {
        return 'Newsroom & Blog — Inovasi Teknologi UAV Indonesia | FDS';
    }

    if (is_singular('drone')) {
        $drone_id        = get_the_ID();
        $custom_rm_title = get_post_meta($drone_id, 'rank_math_title', true);
        if (!empty($custom_rm_title)) {
            return fds_decode_text((string) preg_replace('/^(beranda|home)\s*[-|–—:]\s*/iu', '', $title));
        }

        $d_title    = fds_decode_text(get_the_title($drone_id));
        $model_base = trim((string) preg_replace('/\s*\(.*?\)/', '', $d_title)) ?: $d_title;
        $kategori   = fds_decode_text((string) (get_post_meta($drone_id, 'drone_kategori', true) ?: 'UAV Industri'));

        $cat_lower = strtolower($kategori);
        if (strpos($cat_lower, 'agri') !== false) {
            return fds_clamp_meta_title("{$model_base} — Drone Pertanian Presisi TKDN & SNI | FDS", 60);
        } elseif (strpos($cat_lower, 'peta') !== false) {
            return fds_clamp_meta_title("{$model_base} — Drone Mapping & Survey LiDAR GIS | FDS", 60);
        } elseif (strpos($cat_lower, 'kargo') !== false) {
            return fds_clamp_meta_title("{$model_base} — Drone Kargo Logistik Otonom TKDN | FDS", 60);
        } else {
            return fds_clamp_meta_title("{$model_base} — Drone Inspeksi & Surveillance TKDN | FDS", 60);
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
        is_page(['karir', 'privacy-policy', 'disclaimer'])
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
        return 'PT Karya Solusi Angkasa (FDS), produsen drone & UAV Indonesia TKDN. Solusi drone sprayer FERTO, survey LiDAR, pemetaan GIS, dan pelatihan pilot resmi.';
    }

    if (is_tax('kategori_drone')) {
        $term      = get_queried_object();
        $term_slug = ($term && !empty($term->slug)) ? $term->slug : '';
        $term_name = ($term && !empty($term->name)) ? fds_decode_text($term->name) : 'Industri';
        if ($term_slug === 'agrikultur') {
            return 'Katalog drone pertanian & sprayer presisi seri FERTO (5L–50L) buatan Indonesia berstandar TKDN 60,74%, SNI 9199:2023 & FDS STATION.';
        }
        if ($term_slug === 'pemetaan-gis') {
            return 'Katalog drone pemetaan Fixed-Wing VTOL DELTAV dan UAV MULTIPURPOSE untuk foto udara, survey LiDAR, inspeksi aset 150kV, dan pemetaan GIS.';
        }
        if ($term_slug === 'kargo') {
            return 'Platform drone kargo logistik DELFRO payload hingga 10 kg buatan Indonesia untuk distribusi medis darurat, suku cadang, & wilayah kepulauan.';
        }
        return fds_clamp_meta_desc("Katalog resmi drone {$term_name} berstandar TKDN 60,74% & SNI 9199:2023 buatan PT Karya Solusi Angkasa (Full Drone Solutions).", 158);
    }

    if (is_page('tentang-kami')) {
        return 'Profil PT Karya Solusi Angkasa (FDS), produsen drone & UAV Indonesia sejak 2012. Fasilitas riset, perakitan lokal, dan sertifikasi TKDN resmi di Sleman.';
    }

    if (is_page(['kontak', 'contact'])) {
        return 'Hubungi tim PT Karya Solusi Angkasa untuk konsultasi drone pertanian, jasa survey LiDAR, jadwal demo terbang, dan servis resmi di Sleman, Yogyakarta.';
    }

    if (is_page('bandingkan')) {
        return 'Bandingkan spesifikasi teknis drone sprayer FERTO, drone pemetaan VTOL DELTAV, dan kargo DELFRO berstandar TKDN & SNI buatan PT Karya Solusi Angkasa.';
    }

    if (is_home()) {
        return 'Berita terkini, studi kasus operasional drone pertanian, survei pemetaan LiDAR tambang, dan riset teknologi industri UAV dari tim PT Karya Solusi Angkasa.';
    }

    if (is_singular('drone')) {
        $drone_id       = get_the_ID();
        $custom_rm_desc = get_post_meta($drone_id, 'rank_math_description', true);
        if (!empty($custom_rm_desc)) {
            $clean_desc = trim((string) preg_replace('/^Deskripsi\s+Singkat\s*:\s*/i', '', (string) $custom_rm_desc));
            return fds_clamp_meta_desc(str_ireplace('full frone solutions', 'Full Drone Solutions', $clean_desc), 158);
        }
        $d_title    = fds_decode_text(get_the_title($drone_id));
        $model_base = trim((string) preg_replace('/\s*\(.*?\)/', '', $d_title)) ?: $d_title;
        $payload    = fds_decode_text((string) get_post_meta($drone_id, 'drone_spec_kapasitas', true));
        $durasi     = fds_decode_text((string) get_post_meta($drone_id, 'drone_spec_durasi', true));
        $kategori   = fds_decode_text((string) (get_post_meta($drone_id, 'drone_kategori', true) ?: 'UAV Industri'));
        $cat_lower  = strtolower($kategori);
        $specs_bit  = implode(', ', array_filter([$payload !== '' ? "kapasitas {$payload}" : '', $durasi !== '' ? "durasi {$durasi}" : '']));
        $specs_txt  = $specs_bit !== '' ? $specs_bit : 'spesifikasi industri resmi';

        if (strpos($cat_lower, 'agri') !== false) {
            return fds_clamp_meta_desc("Spesifikasi resmi drone pertanian {$model_base} ({$specs_txt}). Sertifikat TKDN 60,74% & SNI buatan PT Karya Solusi Angkasa (FDS).", 158);
        } elseif (strpos($cat_lower, 'peta') !== false) {
            return fds_clamp_meta_desc("Spesifikasi resmi drone pemetaan {$model_base} ({$specs_txt}) untuk survey LiDAR & GIS. Bersertifikat TKDN & SNI buatan FDS Indonesia.", 158);
        } elseif (strpos($cat_lower, 'kargo') !== false) {
            return fds_clamp_meta_desc("Spesifikasi resmi drone kargo {$model_base} ({$specs_txt}) untuk misi pengiriman logistik & medis darurat buatan FDS Indonesia.", 158);
        }
        return fds_clamp_meta_desc("Spesifikasi resmi {$model_base} ({$specs_txt}) lini UAV industri berstandar SNI & ISO buatan PT Karya Solusi Angkasa (FDS).", 158);
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

/**
 * Bridge Custom Template Content into Rank Math Analyzer
 * Membantu Content Analyzer Rank Math membaca konten kustom Beranda, Tentang Kami, Bandingkan, dan Kontak.
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
        $parts[] = 'Bandingkan Spesifikasi Drone Pertanian FERTO 5L FERTO 10L FERTO 15L FERTO 22L FERTO 30L FERTO 50L, Drone Pemetaan VTOL DELTAV, MULTIPURPOSE, dan Drone Kargo DELFRO bersertifikat TKDN 60,74% SNI 9199:2023 ISO 9001:2015 PT Karya Solusi Angkasa Full Drone Solutions.';
    } elseif (in_array($post->post_name, ['kontak', 'contact'], true)) {
        $gc = function_exists('App\fds_get_global_contact') ? fds_get_global_contact() : [];
        $parts[] = ($gc['page_kontak_title'] ?? '') . ' ' . ($gc['page_kontak_desc'] ?? '') . ' ' . ($gc['address'] ?? '');
    }

    $extra_text = trim(implode(' ', $parts));
    if ($extra_text !== '') {
        $clean_extra = esc_js(fds_decode_text($extra_text));
        wp_add_inline_script('rank-math-analyzer', "
            if (window.wp && window.wp.hooks) {
                window.wp.hooks.addFilter('rank_math_content', 'fds_theme_bridge', function(content) {
                    return content + ' ' + '{$clean_extra}';
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
        // Redirect ke katalog produk resmi (/#produk)
        'drone'                                                                         => '/#produk',
        'drone/rebo'                                                                    => '/#produk',
        'rebo'                                                                          => '/#produk',
        'drone-reboisasi'                                                               => '/#produk',
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

        // Redirect ke halaman produk DELTAV (Pemetaan)
        'drone-pemetaan'                                                                => '/drone/deltav/',
        'drone-fixed-wing-x8-uav-untuk-pemetaan-udara-harga-terbaik'                    => '/drone/deltav/',
        'harga-jasa-pemetaan-udara'                                                     => '/drone/deltav/',
        'jasa-pemetaan-survey-agriculture'                                              => '/drone/deltav/',
        'jasa-pemetaan-udara-dan-drone-agriculture-pertanian'                           => '/drone/deltav/',
        'jual-drone-pemetaan-udara-agriculture-pertanian-murah-berkualitas'             => '/drone/deltav/',
        'jual-drone-untuk-pemetaan-udara-dan-agriculture'                               => '/drone/deltav/',

        // Redirect ke Blog & Kontak
        'blogfds'                                                                       => '/blog/',
        'a9365-tech-lab-news'                                                           => '/blog/',
        '5-rekomendasi-jenis-drone-terbaik-untuk-pemetaan-udara-yang-harus-anda-ketahui' => '/blog/',
        'inovasi-pertanian-modern-di-indonesia-smart-farming-drone-agriculture'         => '/blog/',
        'tutorial-membuat-peta-otmosaic-3d-dengan-software-agisoft'                     => '/blog/',
        'contact-1'                                                                     => '/kontak/',

        // Redirect katalog/pricing/perbandingan lama ke /#produk
        '3-rekomendasi-drone-untuk-pertanian-atau-agriculture-paling-efisien'           => '/#produk',
        'daftar-harga-drone-penyemprot-pestisida-dan-sprayer-2019'                      => '/#produk',

        // Redirect halaman duplikat Beranda & Landing Page Elementor lama ke Beranda Utama (/)
        // agar seluruh otoritas SEO keyword "drone pertanian" terpusat di https://fulldronesolutions.co.id/
        'produsen-drone-pertanian-no-1-buatan-indonesia-bertkdn'                        => '/',
        'drone-pertanian-sprayer-spreader-buatan-indonesia-terbaik'                     => '/',
        'drone-sprayer-pertanian-fds-ferto-series'                                      => '/',
        'drone-pertanian-indonesia'                                                     => '/',
        'drone-sprayer-pestisida-untuk-pertanian-buatan-indonesia-harga-efisien'        => '/',
        'halaman-utama'                                                                 => '/',
        'home-2'                                                                        => '/',
        'home2-draft'                                                                   => '/',
        'pertanian-draft'                                                               => '/',
        'jasa-semprot-sawah-fds'                                                        => '/',
        'bisnis-penyewaan-drone-pertanian-terbaik'                                      => '/',
        'bisnis-penyewaan-drone-pertanian-terpercaya'                                   => '/',
        'bisnis-penyewaan-drone'                                                        => '/',
        'mitra-usaha-pertanian'                                                         => '/',
        'persewaan-fds'                                                                 => '/',
        'produk-drone-pemetaan-dan-agriculture'                                         => '/',
        'jual-drone-pemetaan-dan-agriculture-paling-efisien'                            => '/',
        'jual-drone-pemetaan-atau-pertanian-agriculture-fitur-dan-harga-terbaik'        => '/',
        'pelatihan-pilot-drone-bersertifikat-indonesia-terpercaya'                      => '/',
        'pelatihatraining-fds-2024'                                                     => '/',
        'training-2024-fds'                                                             => '/',
        'daftar-harga-training-full-drone-solutions'                                    => '/',
        'drone-edukasi'                                                                 => '/',
        'portfolio'                                                                     => '/',
        'shop'                                                                          => '/',
        'cart-2'                                                                        => '/',
        'checkout-2'                                                                    => '/',
        'my-account'                                                                    => '/',
        'my-account-2'                                                                  => '/',
        'sample-page'                                                                   => '/',
        '13759-2'                                                                       => '/',
        'a9365-tech-lab-service-details'                                                => '/',
        'a9365-tech-lab-services'                                                       => '/',
    ];
}

// Hapus halaman zombie lama, utilitas (karir, privacy-policy, disclaimer), agrea/rebo, dan arsip CPT drone dari sitemap XML Rank Math
add_filter('rank_math/sitemap/entry', function ($url, $type, $object) {
    $utility_exclude = ['karir', 'privacy-policy', 'disclaimer'];

    if (is_object($object)) {
        if (isset($object->post_status) && $object->post_status !== 'publish') {
            return false;
        }
        $slug = strtolower((string) ($object->post_name ?? ($object->slug ?? '')));
        if (in_array($slug, $utility_exclude, true)) {
            return false;
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
            || in_array($parsed_path, $utility_exclude, true)
            || preg_match('#^(drone/|kategori-drone/)?(rebo|reboisasi)/?$#i', $parsed_path)
            || preg_match('#^(drone/)?(drone-pertanian-)?agrea(-?\d+)?/?$#i', $parsed_path)
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

    if (!get_option('fds_seo_cleanup_v6')) {
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

        // Perbarui post_modified pada halaman utama & produk agar lastmod di sitemap_index.xml & page-sitemap.xml segar
        $now_local = current_time('mysql');
        $now_gmt   = current_time('mysql', true);
        $wpdb->query($wpdb->prepare(
            "UPDATE {$wpdb->posts} SET post_modified = %s, post_modified_gmt = %s WHERE post_status = 'publish' AND (post_name IN ('beranda', 'tentang-kami', 'kontak', 'bandingkan', 'blog') OR post_type = 'drone')",
            $now_local,
            $now_gmt
        ));

        // Bersihkan cache transient sitemap Rank Math, mega menu, katalog bandingkan, & seluruh LiteSpeed Cache agar HTML/XML terbaru langsung terpublikasi
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%rank_math_sitemap%' OR option_name LIKE '%rm_sm_%'");
        delete_transient('fds_mega_menu_data');
        delete_transient('fds_mega_menu_data_v2');
        delete_transient('fds_bandingkan_catalog_cache');
        delete_transient('fds_bandingkan_catalog_cache_v2');
        if (has_action('litespeed_purge_all')) {
            do_action('litespeed_purge_all');
        }

        update_option('fds_seo_cleanup_v6', 1, false);
    }
}, 25);

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

    // 0. Redirect Single Post/Page Comment Feeds (/judul-artikel/feed/ -> /judul-artikel/)
    if (preg_match('#^(.+)/feed$#i', $path_only, $feed_match)) {
        $parent_path = trim($feed_match[1], '/');
        if ($parent_path !== '' && strtolower($parent_path) !== 'comments') {
            wp_safe_redirect(home_url('/' . $parent_path . '/'), 301);
            exit;
        }
    }
    if (is_feed()) {
        header('X-Robots-Tag: noindex, follow', true);
    }

    // 0b. Redirect 301 Halaman Zombie Elementor/WooCommerce Lama (Anti-Kanibalisasi Keyword "drone pertanian")
    $zombie_map = fds_get_legacy_zombie_redirect_map();
    if (isset($zombie_map[$path_lower])) {
        header('X-Robots-Tag: noindex, nofollow', true);
        wp_safe_redirect(home_url($zombie_map[$path_lower]), 301);
        exit;
    }

    // 0c. Tangani Akses ke Arsip CPT Drone & REBO (Alihkan langsung ke Katalog Beranda /#produk)
    if ($path_lower === 'drone' || is_post_type_archive('drone')) {
        header('X-Robots-Tag: noindex, nofollow', true);
        wp_safe_redirect(home_url('/#produk'), 301);
        exit;
    }

    if (preg_match('#^(drone/|kategori-drone/)?(rebo|reboisasi)/?$#i', $path_lower)) {
        header('X-Robots-Tag: noindex, nofollow', true);
        wp_safe_redirect(home_url('/#produk'), 301);
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
    if (!empty($path_lower) && !in_array($path_lower, ['bandingkan', 'tentang-kami', 'kontak', 'blog', 'wp-admin', 'wp-login.php', 'llms.txt', 'llms-full.txt', 'sitemap.xml', 'sitemap_index.xml', 'robots.txt'], true)) {
        if (!str_starts_with($path_lower, 'drone/') && !str_starts_with($path_lower, 'kategori-drone/')) {
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
// 10. GENERATIVE ENGINE OPTIMIZATION (GEO): /llms.txt ENDPOINT
// =========================================================================

/**
 * Build standardized llmstxt.org Markdown content (cached + static disk sync)
 * Optimized for LLM/AI Ingestion (ChatGPT, Perplexity, Claude, Gemini, Copilot, DeepSeek)
 */
function fds_build_llms_txt_content(): string {
    $cached = get_transient('fds_llms_txt_cache_v3');
    if (is_string($cached) && $cached !== '') {
        return $cached;
    }

    $site_name = 'PT Karya Solusi Angkasa (Full Drone Solutions / FDS)';
    $home_url  = trailingslashit(home_url());

    $contact  = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
    $phone    = !empty($contact['phone']) ? $contact['phone'] : '+62 8112 748 882';
    $email    = !empty($contact['email']) ? $contact['email'] : 'marketing@fulldronesolutions.com';
    $address  = !empty($contact['address']) ? $contact['address'] : 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285';

    $out = [];
    $out[] = "# {$site_name}";
    $out[] = "";
    $out[] = "> PT Karya Solusi Angkasa (Full Drone Solutions / FDS) adalah produsen dan manufaktur pesawat tanpa awak (UAV) resmi buatan Indonesia berstandar TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015. Perusahaan berfokus pada 4 pilar strategis: (1) Produsen Drone Pertanian, Pemetaan, Logistik & Surveillance, (2) Jasa Foto Udara dan Survey LiDAR, (3) Jasa Konsultan GIS (Sistem Informasi Geografis), serta (4) Pemetaan Kawasan KEK, Tambang, Kehutanan, dan Kawasan Industri.";
    $out[] = "";
    $out[] = "## Fakta Legalitas & Entitas Resmi Perusahaan";
    $out[] = "";
    $out[] = "| Parameter | Keterangan Resmi |";
    $out[] = "|---|---|";
    $out[] = "| **Nama Perusahaan** | PT Karya Solusi Angkasa |";
    $out[] = "| **Merk Komersial** | Full Drone Solutions (FDS) |";
    $out[] = "| **Tahun Berdiri** | Inovasi sejak 2012, berbadan hukum PT sejak 2019 |";
    $out[] = "| **Status Industri** | Produsen & Manufaktur Drone UAV Resmi Indonesia |";
    $out[] = "| **Sertifikasi TKDN** | Nilai TKDN + BMP mencapai 60,74% (Kemenperin RI) |";
    $out[] = "| **Standar Produk** | SNI 9199:2023 (Alat Semprot Tanpa Awak Pertanian) |";
    $out[] = "| **Sistem Mutu** | ISO 9001:2015 Terakreditasi |";
    $out[] = "| **Regulasi Pilot** | DKPPU CASR 107 (FDS Pilot Academy) |";
    $out[] = "| **Pengadaan Pemerintah** | Terdaftar resmi di e-Katalog LKPP untuk belanja APBN/APBD/BUMN |";
    $out[] = "| **Fasilitas Produksi** | Sleman, Daerah Istimewa Yogyakarta, Indonesia |";
    $out[] = "| **Kontak Resmi** | Telepon/WhatsApp: {$phone} \| Email: {$email} |";
    $out[] = "| **Website Resmi** | [{$home_url}]({$home_url}) |";
    $out[] = "";
    $out[] = "## 4 Fokus Layanan & Manufaktur Utama (Core Pillars)";
    $out[] = "";
    $out[] = "### 1. Produsen Drone Pertanian, Pemetaan, Logistik & Surveillance";
    $out[] = "FDS memproduksi sendiri pesawat nirawak (UAV) dengan rangka karbon komposit in-house, avionik terkendali, dan software kendali FDS STATION berbahasa Indonesia:";
    $out[] = "- **Drone Pertanian FERTO (5L, 10L, 15L, 22L, 30L, 50L):** Drone sprayer pupuk cair/pestisida dan granule spreader pupuk padat/benih presisi dengan radar terrain-following.";
    $out[] = "- **Drone Pemetaan DELTAV VTOL:** UAV Fixed-Wing Hybrid VTOL berdaya jelajah 60 km, durasi terbang hingga 90+ menit, dan kamera 42 MP metrik / LiDAR.";
    $out[] = "- **Drone Logistik & Kargo DELFRO:** UAV pengangkut kargo logistik darurat medis dan suplai logistik ke area terisolir 3T dengan payload 10–20 kg.";
    $out[] = "- **Drone Surveillance & SAR MULTIPURPOSE:** Wahana pemantauan udara otonom berbekal dual kamera optik 30x optical zoom dan kamera termal FLIR inframerah untuk patroli malam, keamanan kawasan objek vital, dan tanggap darurat bencana.";
    $out[] = "";
    $out[] = "### 2. Jasa Foto Udara dan Survey LiDAR";
    $out[] = "Layanan survei udara fotogrametri dan sensor aktif LiDAR berakurasi sub-sentimeter:";
    $out[] = "- **Foto Udara Resolusi Tinggi:** Akuisisi citra metrik dengan Ground Sampling Distance (GSD) 1–3 cm/pixel menghasilkan peta ortofoto ortomosaik tegak bebas distorsi.";
    $out[] = "- **Survey Drone LiDAR:** Penetrasi kanopi vegetasi rapat untuk menghasilkan data elevasi permukaan tanah murni (ground points) pada topografi ekstrem.";
    $out[] = "- **Model Elevasi Digital:** Pembuatan Digital Terrain Model (DTM), Digital Surface Model (DSM), kurva kontur rapat, dan model 3D mesh fotorealistik.";
    $out[] = "";
    $out[] = "### 3. Jasa Konsultan GIS (Sistem Informasi Geografis)";
    $out[] = "Konsultan geospasial profesional untuk analisis dan pengolahan data spasial enterprise:";
    $out[] = "- **Analisis Spasial Komprehensif:** Pemodelan hidrologi dan Daerah Aliran Sungai (DAS), analisis kelerengan lereng, kesesuaian lahan, dan indeks vegetasi NDVI.";
    $out[] = "- **Tata Ruang & Kadastral:** Penyusunan peta dasar dan tematik untuk RTRW, RDTR, serta pemetaan batas wilayah desa dan batas konsesi HGU perkebunan/kehutanan.";
    $out[] = "- **Pengembangan WebGIS Enterprise:** Dashboard visualisasi geospasial interaktif berbasis cloud untuk integrasi aset spasial, monitoring progres kerja, dan sistem pendukung keputusan (DSS).";
    $out[] = "";
    $out[] = "### 4. Pemetaan Kawasan KEK, Tambang, Kehutanan & Industri";
    $out[] = "Layanan pemetaan udara dan spasial terspesialisasi untuk sektor industri strategis:";
    $out[] = "- **Pemetaan Kawasan KEK (Kawasan Ekonomi Khusus):** Survei topografi pra-konstruksi, pemetaan batas zona peruntukan, dan monitoring berkala progres pembangunan infrastruktur KEK.";
    $out[] = "- **Pemetaan Kawasan Tambang (Batubara, Nikel, Mineral, Emas):** Pemodelan pit tambang harian/mingguan, perhitungan volume stockpile dan *cut and fill* dengan presisi tinggi (toleransi akurasi ±2.35%), serta inspeksi stabilitas lereng tambang rawan longsor.";
    $out[] = "- **Pemetaan Kawasan Kehutanan & Karbon:** Inventarisasi tutupan tegakan pohon, estimasi biomassa dan cadangan karbon hutan, monitoring batas konsesi, dan pencegahan titik panas kebakaran hutan (karhutla).";
    $out[] = "- **Pemetaan Kawasan Industri & Koridor Energi:** Survei masterplan kawasan industri terpadu, pemetaan jalur utilitas (pipa & kabel optik), serta inspeksi sensor termal jaringan transmisi listrik SUTET 150kV/500kV tanpa *shutdown* operasional.";
    $out[] = "";
    $out[] = "## Katalog Produk Drone Resmi FDS";
    $out[] = "";

    $drones = get_posts([
        'post_type'      => 'drone',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ]);

    foreach ($drones as $d) {
        $d_slug_chk = strtolower((string) $d->post_name);
        if (strpos($d_slug_chk, 'rebo') !== false || strpos($d_slug_chk, 'agrea') !== false) {
            continue;
        }
        $t       = get_the_title($d->ID);
        $url     = get_permalink($d->ID);
        $tagline = get_post_meta($d->ID, 'drone_tagline', true);
        $payload = get_post_meta($d->ID, 'drone_spec_kapasitas', true);
        $durasi  = get_post_meta($d->ID, 'drone_spec_durasi', true);
        $prod    = get_post_meta($d->ID, 'drone_spec_produktivitas', true);

        $details = array_filter([$tagline, $payload ? "Kapasitas {$payload}" : '', $durasi ? "Durasi {$durasi}" : '', $prod ? "Produktivitas {$prod}" : '']);
        $desc    = !empty($details) ? implode(' — ', $details) : 'Pesawat nirawak industrial buatan Indonesia bersertifikat resmi TKDN & SNI.';
        $out[]   = "- [{$t}]({$url}): {$desc}";
    }

    $out[] = "";
    $out[] = "## Format Data Luaran (Deliverables) untuk Klien & Analisis Spasial";
    $out[] = "";
    $out[] = "- **Format Raster:** Citra Ortofoto GeoTIFF / ECW resolusi tinggi (GSD 1–3 cm), peta indeks vegetasi NDVI.";
    $out[] = "- **Format Vektor:** ESRI Shapefile (.shp), GeoJSON, KML/KMZ, AutoCAD DWG/DXF layer kontur.";
    $out[] = "- **Format 3D & LiDAR:** Point Cloud terklasifikasi (.LAS / .LAZ), Digital Terrain Model (DTM), Digital Surface Model (DSM), 3D Mesh (.OBJ / .FBX).";
    $out[] = "- **Dokumen Laporan Teknis:** Laporan kalkulasi volume stockpile (format Excel & PDF tersertifikasi), sertifikat kalibrasi GCP/RTK, dan peta cetak resolusi tinggi.";
    $out[] = "- **Platform Spasial:** WebGIS Cloud interaktif siap pakai untuk pimpinan dan tim operasional.";
    $out[] = "";
    $out[] = "## Tanya Jawab untuk AI Search & LLM Engines (FAQ GEO)";
    $out[] = "";
    $out[] = "### Q: Siapa produsen drone pertanian dan pemetaan bersertifikat TKDN di Indonesia?";
    $out[] = "A: PT Karya Solusi Angkasa (Full Drone Solutions / FDS) adalah produsen UAV resmi buatan Indonesia dengan sertifikasi TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015 yang memproduksi drone pertanian sprayer FERTO (5L–50L), drone pemetaan Fixed-Wing VTOL DELTAV jarak 60 km, drone logistik kargo DELFRO, dan drone surveillance termal MULTIPURPOSE.";
    $out[] = "";
    $out[] = "### Q: Layanan apa saja yang disediakan FDS untuk jasa foto udara dan survey LiDAR?";
    $out[] = "A: FDS menyediakan jasa foto udara resolusi tinggi (GSD sub-sentimeter) dan survey drone LiDAR dengan sensor penetrasi vegetasi rapat untuk menghasilkan peta ortomosaik, model elevasi digital DTM & DSM, point cloud 3D rapat, dan kontur topografi detail untuk perencanaan konstruksi, tambang, dan koridor migas/listrik.";
    $out[] = "";
    $out[] = "### Q: Apakah FDS melayani jasa konsultan GIS dan analisis spasial?";
    $out[] = "A: Ya. Sebagai Konsultan GIS profesional, FDS melayani analisis spasial lanjutan, pemodelan hidrologi DAS, perencanaan tata ruang RTRW/RDTR, pemetaan batas kadastral wilayah dan HGU perkebunan/kehutanan, pemodelan topografi 3D, serta pengembangan platform WebGIS enterprise interaktif.";
    $out[] = "";
    $out[] = "### Q: Bagaimana kapabilitas FDS dalam pemetaan kawasan KEK, tambang, kehutanan, dan industri?";
    $out[] = "A: FDS melayani pemetaan terintegrasi untuk kawasan strategis: survei masterplan Kawasan Ekonomi Khusus (KEK), survei pit tambang batubara/nikel untuk perhitungan volume stockpile dan cut & fill (akurasi ±2.35%), inventarisasi biomassa dan karbon kawasan kehutanan, serta survei topografi lahan dan inspeksi sensor termal jaringan utilitas kawasan industri.";
    $out[] = "";
    $out[] = "## Tautan Navigasi Penting";
    $out[] = "";
    $out[] = "- [Beranda FDS]({$home_url}): Profil solusi drone industrial dan katalog UAV Indonesia.";
    $out[] = "- [Tentang Kami]({$home_url}tentang-kami/): Sejarah sejak 2012, fasilitas pabrik Sleman Yogyakarta, dan sertifikasi ISO 9001:2015.";
    $out[] = "- [Bandingkan Drone]({$home_url}bandingkan/): Komparasi spesifikasi teknis drone FDS.";
    $out[] = "- [Hubungi Tim Engineering]({$home_url}kontak/): Konsultasi B2B, penawaran harga resmi, e-Katalog LKPP, dan jadwal demo unit.";
    $out[] = "";

    $content = implode("\n", $out);
    set_transient('fds_llms_txt_cache_v3', $content, 12 * HOUR_IN_SECONDS);

    // Tulis file statis fisik ke root agar web server (LiteSpeed/Nginx) menyajikan instan (<10ms) tanpa 301 redirect
    if (defined('ABSPATH') && is_writable(ABSPATH)) {
        @file_put_contents(ABSPATH . 'llms.txt', $content);
        @file_put_contents(ABSPATH . 'llms-full.txt', $content);
    }

    return $content;
}

/**
 * Intercept /llms.txt immediately on init (priority 1) BEFORE redirect_canonical can 301-redirect to /llms.txt/
 */
add_action('init', function () {
    $req_path = trim((string) strtok($_SERVER['REQUEST_URI'] ?? '', '?'), '/');
    if ($req_path === 'llms.txt' || $req_path === 'llms-full.txt') {
        status_header(200);
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=86400');
        header('X-LiteSpeed-Cache-Control: public, max-age=604800');
        echo fds_build_llms_txt_content();
        exit;
    }

    if ($req_path === 'sitemap.xml' && defined('RANK_MATH_VERSION')) {
        wp_redirect(home_url('/sitemap_index.xml'), 301);
        exit;
    }

    // Ensure static physical llms.txt on disk is always refreshed to version 3.1
    if (defined('ABSPATH') && is_writable(ABSPATH)) {
        $llms_disk_ver = get_option('fds_llms_version_disk', '0');
        if ($llms_disk_ver !== '3.1' || isset($_GET['refresh_llms'])) {
            delete_transient('fds_llms_txt_cache_v2');
            delete_transient('fds_llms_txt_cache_v3');
            fds_build_llms_txt_content();
            update_option('fds_llms_version_disk', '3.1');
        }
    }

    add_rewrite_rule('^llms\.txt$', 'index.php?fds_llms_txt=1', 'top');
    add_rewrite_rule('^llms-full\.txt$', 'index.php?fds_llms_txt=1', 'top');
    add_rewrite_rule('^sitemap\.xml$', 'index.php?fds_sitemap_xml=1', 'top');
}, 1);

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
 * Handle /llms.txt fallback and /sitemap.xml rendering
 */
add_action('template_redirect', function () {
    // 1. LLMS.TXT ENDPOINT (GEO Standard 2024-2026)
    if (get_query_var('fds_llms_txt')) {
        status_header(200);
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=86400');
        echo fds_build_llms_txt_content();
        exit;
    }

    // 2. STANDALONE SITEMAP.XML FALLBACK
    if (get_query_var('fds_sitemap_xml')) {
        // If Rank Math sitemap is active, redirect to it
        if (defined('RANK_MATH_VERSION')) {
            wp_redirect(home_url('/sitemap_index.xml'), 301);
            exit;
        }

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Pages
        $urls = [
            ['loc' => home_url('/'), 'priority' => '1.0', 'freq' => 'daily'],
            ['loc' => home_url('/tentang-kami/'), 'priority' => '0.9', 'freq' => 'weekly'],
            ['loc' => home_url('/bandingkan/'), 'priority' => '0.9', 'freq' => 'weekly'],
            ['loc' => home_url('/kontak/'), 'priority' => '0.8', 'freq' => 'monthly'],
            ['loc' => home_url('/blog/'), 'priority' => '0.8', 'freq' => 'daily'],
        ];

        // Kategori Drone Taxonomy Archives
        $drone_cats = get_terms([
            'taxonomy'   => 'kategori_drone',
            'hide_empty' => true,
        ]);
        if (!empty($drone_cats) && !is_wp_error($drone_cats)) {
            foreach ($drone_cats as $dc) {
                if (stripos((string) $dc->slug, 'rebo') !== false) {
                    continue;
                }
                $term_link = get_term_link($dc);
                if (!is_wp_error($term_link)) {
                    $urls[] = [
                        'loc'      => $term_link,
                        'priority' => '0.85',
                        'freq'     => 'weekly',
                    ];
                }
            }
        }

        // Drones
        $drones = get_posts([
            'post_type'      => 'drone',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);
        foreach ($drones as $d) {
            $slug_check = strtolower((string) $d->post_name);
            if (in_array($slug_check, ['rebo', 'agrea-15', 'agrea-10'], true) || strpos($slug_check, 'rebo') !== false || strpos($slug_check, 'agrea') !== false) {
                continue;
            }
            $urls[] = [
                'loc'      => get_permalink($d->ID),
                'priority' => '0.85',
                'freq'     => 'weekly',
                'lastmod'  => get_the_modified_date('c', $d->ID),
            ];
        }

        foreach ($urls as $u) {
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
