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
 * Master List of Categorized Default Drone Keywords (Indonesia High-Intent B2B SEO)
 */
function fds_get_default_drone_keywords_grouped() {
    return [
        'pertanian' => [
            'drone pertanian',
            'jual drone pertanian',
            'harga drone pertanian',
            'drone sprayer indonesia',
            'drone penyemprot pupuk',
            'drone penyemprot pestisida',
            'drone sebar benih',
            'drone pertanian tkdn',
            'drone semprot sawit',
            'drone perkebunan kelapa sawit',
            'drone ferto 50l',
            'drone ferto 30l',
            'drone ferto 20l',
            'drone ferto 10l',
            'drone pemupukan presisi',
            'drone agrikultur indonesia',
            'jasa sewa drone sprayer',
            'perbandingan drone dji agras',
            'drone penyemprot tanaman pangan',
        ],
        'pemetaan' => [
            'drone mapping',
            'jual drone mapping',
            'jasa pemetaan drone',
            'jasa survey drone',
            'jasa drone foto udara',
            'jasa drone lidar',
            'jasa pemetaan drone tambang',
            'pemetaan GIS drone',
            'drone topografi',
            'fotogrametri udara ortomosaik',
            'drone fixed wing vtol indonesia',
            'uav vtol pemetaan',
            'drone deltav',
            'perhitungan volume stockpile tambang',
            'drone cut and fill',
            'survey koridor transmisi listrik dan sutet',
            'pemetaan batas kawasan perkebunan dan kehutanan',
            'drone inspeksi tambang nikel batubara',
        ],
        'industri_sar' => [
            'drone pencarian korban bencana',
            'drone misi SAR',
            'drone thermal flir inframerah',
            'drone inspeksi industri',
            'drone inspeksi sutet 150kv',
            'drone patroli karhutla',
            'drone reboisasi seedball otonom',
            'drone kargo logistik darurat medis',
            'drone payload besar indonesia',
            'drone tanggap darurat bencana',
            'drone pemadam kebakaran',
        ],
        'produsen_legalitas' => [
            'produsen UAV Indonesia',
            'pabrik drone di Indonesia',
            'manufaktur drone lokal Indonesia',
            'PT Karya Solusi Angkasa',
            'Full Drone Solutions',
            'drone TKDN 60,74%',
            'drone sertifikat SNI 9199:2023',
            'sistem manajemen mutu ISO 9001:2015',
            'pengadaan drone e-katalog LKPP',
            'pelatihan pilot drone resmi',
            'sertifikasi pilot drone FDS Academy',
            'service dan reparasi drone indonesia',
            'sparepart drone sprayer ready stock',
            'ground control station FDS STATION',
            'bengkel drone sleman yogyakarta',
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
    $description = 'Produsen UAV Indonesia resmi (PT Karya Solusi Angkasa). Menyediakan jasa pemetaan drone tambang, survey drone LiDAR, foto udara GIS, drone pertanian presisi TKDN 60,74% & SNI 9199:2023, dan drone pencarian korban bencana SAR.';
    $keywords    = $primary_keywords;
    $image_url   = '';
    $og_type     = 'website';
    $canonical   = trailingslashit($current_url);
    $breadcrumbs = [
        ['name' => 'Beranda', 'url' => home_url('/')]
    ];

    // 1. FRONT PAGE / HOMEPAGE
    if (is_front_page() || is_home()) {
        $title       = 'Full Drone Solutions — Produsen UAV Indonesia & Jasa Pemetaan Drone, LiDAR, Pertanian Presisi';
        $description = 'PT Karya Solusi Angkasa (FDS), produsen UAV Indonesia berstandar TKDN 60,74% dan SNI 9199:2023. Melayani jasa pemetaan drone tambang, survey drone LiDAR, foto udara GIS, drone pertanian presisi FERTO, serta drone pencarian korban bencana SAR.';
        $keywords    = $primary_keywords;
        $canonical   = trailingslashit(home_url('/'));
        $image_url   = fds_get_default_share_image();
    }
    // 2. SINGLE DRONE CPT PAGE
    elseif (is_singular('drone') || get_query_var('drone')) {
        $drone_post = get_post();
        if ($drone_post) {
            $slug        = $drone_post->post_name;
            $d_title     = get_the_title($drone_post->ID);
            $tagline     = get_post_meta($drone_post->ID, 'drone_tagline', true);
            $kategori    = get_post_meta($drone_post->ID, 'drone_kategori', true) ?: 'UAV Industri';
            $payload     = get_post_meta($drone_post->ID, 'drone_spec_kapasitas', true);
            $durasi      = get_post_meta($drone_post->ID, 'drone_spec_durasi', true);
            $feat_img    = get_the_post_thumbnail_url($drone_post->ID, 'full');

            $title       = "{$d_title} — Spesifikasi Drone {$kategori} TKDN & SNI Resmi | Full Drone Solutions";
            
            $desc_parts  = [];
            if ($tagline) $desc_parts[] = $tagline;
            if ($payload) $desc_parts[] = "Payload: {$payload}";
            if ($durasi)  $desc_parts[] = "Durasi Terbang: {$durasi}";
            $desc_parts[] = "Sertifikasi resmi TKDN 60,74% & SNI 9199:2023 buatan PT Karya Solusi Angkasa (FDS), produsen UAV Indonesia.";
            $description = implode('. ', $desc_parts);

            // Contextual keywords per category
            $cat_lower = strtolower($kategori);
            if (strpos($cat_lower, 'agri') !== false || strpos($slug, 'ferto') !== false) {
                $keywords = fds_get_primary_keywords_string("drone pertanian, drone sprayer {$d_title}, jual drone pertanian {$slug}, harga drone pertanian, drone penyemprot pupuk dan pestisida, drone sebar benih, drone pertanian indonesia, produsen UAV Indonesia, TKDN 60,74%, SNI 9199, {$payload}");
            } elseif (strpos($cat_lower, 'peta') !== false || strpos($cat_lower, 'gis') !== false || strpos($slug, 'deltav') !== false) {
                $keywords = fds_get_primary_keywords_string("drone mapping, jual drone mapping, jasa pemetaan drone, jasa survey drone, jasa drone foto udara, jasa drone lidar, jasa pemetaan drone tambang, pemetaan GIS drone, fixed wing VTOL, {$d_title}");
            } elseif (strpos($cat_lower, 'rebo') !== false || strpos($cat_lower, 'kargo') !== false) {
                $keywords = fds_get_primary_keywords_string("drone pencarian korban bencana, drone SAR, drone reboisasi, drone kargo logistik darurat, drone tanggap darurat, UAV Indonesia {$d_title}");
            } else {
                $keywords = fds_get_primary_keywords_string("Drone {$d_title}, Drone {$kategori}, produsen UAV Indonesia, jasa survey drone, inspeksi drone, PT Karya Solusi Angkasa");
            }

            $canonical   = trailingslashit(get_permalink($drone_post->ID));
            $image_url   = $feat_img ?: fds_get_default_share_image();
            $og_type     = 'product';

            $breadcrumbs[] = ['name' => 'Katalog Drone', 'url' => home_url('/#produk')];
            $breadcrumbs[] = ['name' => $d_title, 'url' => $canonical];
        }
    }
    // 3. BANDINGKAN DRONE PAGE
    elseif (is_page('bandingkan') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/bandingkan') !== false)) {
        $title       = 'Bandingkan Spesifikasi Drone Pertanian & Pemetaan FDS — Komparasi UAV TKDN Indonesia';
        $description = 'Bandingkan spesifikasi teknis lengkap drone pertanian FERTO (5L–50L), Fixed-Wing VTOL DELTAV untuk pemetaan GIS LiDAR, drone inspeksi, dan reboisasi buatan produsen UAV Indonesia PT Karya Solusi Angkasa.';
        $keywords    = 'drone pertanian, komparasi drone pertanian, spesifikasi drone pertanian TKDN, drone mapping, jasa pemetaan drone, produsen UAV Indonesia';
        $canonical   = trailingslashit(home_url('/bandingkan/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Bandingkan Drone', 'url' => $canonical];
    }
    // 4. TENTANG KAMI PAGE
    elseif (is_page('tentang-kami') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/tentang-kami') !== false)) {
        $title       = 'Tentang Kami — PT Karya Solusi Angkasa (Produsen UAV Indonesia Resmi Berstandar TKDN)';
        $description = 'Profil PT Karya Solusi Angkasa (Full Drone Solutions), manufaktur drone nasional berstandar TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015. Pusat riset, perakitan, dan pelatihan pilot drone di Sleman, Yogyakarta.';
        $keywords    = 'produsen UAV Indonesia, PT Karya Solusi Angkasa, pabrik drone Indonesia, produsen drone pertanian, jasa pemetaan drone, drone TKDN SNI Yogyakarta, FDS';
        $canonical   = trailingslashit(home_url('/tentang-kami/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Tentang Kami', 'url' => $canonical];
    }
    // 5. KONTAK KAMI PAGE
    elseif (is_page('kontak') || is_page('contact') || (isset($_SERVER['REQUEST_URI']) && preg_match('#/(kontak|contact)/?#i', $_SERVER['REQUEST_URI']))) {
        $title       = 'Kontak Kami — PT Karya Solusi Angkasa (Produsen UAV Indonesia Sleman Yogyakarta)';
        $description = 'Hubungi tim PT Karya Solusi Angkasa (Full Drone Solutions). Konsultasi pengadaan drone pertanian TKDN, mapping VTOL, jadwal uji terbang di Yogyakarta, dan layanan servis resmi.';
        $keywords    = 'kontak full drone solutions, kontak PT Karya Solusi Angkasa, alamat pabrik drone yogyakarta, hubungi produsen drone indonesia, pengadaan drone TKDN';
        $canonical   = trailingslashit(home_url('/kontak/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Kontak Kami', 'url' => $canonical];
    }
    // 6. BLOG / NEWSROOM
    elseif (is_home() || is_archive() || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/blog') !== false)) {
        $title       = 'Newsroom & Artikel Drone — Inovasi Drone Pertanian, Pemetaan LiDAR & Industri UAV';
        $description = 'Kumpulan berita, artikel teknologi, studi kasus drone mapping pertambangan, efisiensi drone pertanian presisi, regulasi UAV, dan operasional lapangan dari Full Drone Solutions.';
        $keywords    = 'artikel drone pertanian, berita drone mapping, studi kasus survey drone lidar, jasa pemetaan drone tambang, teknologi UAV indonesia, sertifikasi TKDN drone';
        $canonical   = trailingslashit(home_url('/blog/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Newsroom', 'url' => $canonical];
    }
    // 6. GENERIC / OTHER PAGES
    else {
        if (is_singular()) {
            $p_title     = get_the_title();
            $title       = "{$p_title} — Full Drone Solutions Indonesia";
            $p_excerpt   = get_the_excerpt() ?: wp_trim_words(get_the_content(), 25);
            if ($p_excerpt) {
                $description = wp_strip_all_tags($p_excerpt);
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
        'title'       => esc_attr(wp_strip_all_tags($title)),
        'description' => esc_attr(wp_strip_all_tags($description)),
        'keywords'    => esc_attr(wp_strip_all_tags($keywords)),
        'canonical'   => esc_url($canonical),
        'image'       => esc_url($image_url),
        'og_type'     => esc_attr($og_type),
        'site_name'   => esc_attr($site_name),
        'breadcrumbs' => $breadcrumbs,
    ];
}

/**
 * Get fallback sharing banner image
 */
function fds_get_default_share_image() {
    $nb = function_exists('App\fds_get_navbar_brand') ? fds_get_navbar_brand() : [];
    if (!empty($nb['logo_url'])) {
        return $nb['logo_url'];
    }
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_src = wp_get_attachment_image_src($custom_logo_id, 'full');
        if (!empty($logo_src[0])) return $logo_src[0];
    }
    return home_url('/wp-content/uploads/2026/08/logo-fds-academy-1.png');
}

/**
 * Render Head Meta Tags (Title, Description, Open Graph, Twitter Cards, Canonical)
 */
function fds_render_seo_meta_tags() {
    $has_plugin = fds_has_external_seo_plugin();

    // JIKA PLUGIN SEO EKSTERNAL (RANK MATH / YOAST / AIOSEO) AKTIF:
    // Sepenuhnya serahkan output Title, Description, Canonical, Robots, OpenGraph,
    // dan Twitter Cards ke plugin SEO tersebut agar hasil editan user di Rank Math
    // 100% dipatuhi dan tidak terjadi duplikasi tag di HTML source.
    if ($has_plugin) {
        return;
    }

    $seo = fds_get_seo_data();

    echo "\n    <!-- ============================================================ -->\n";
    echo "    <!-- FDS STANDALONE SEO ENGINE & OPEN GRAPH META TAGS           -->\n";
    echo "    <!-- ============================================================ -->\n";

    echo '    <title>' . esc_html($seo['title']) . "</title>\n";
    echo '    <meta name="description" content="' . esc_attr($seo['description']) . "\">\n";
    echo '    <meta name="keywords" content="' . esc_attr($seo['keywords']) . "\">\n";
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
 * Generate JSON-LD Structured Data (Schema.org)
 * Includes: Organization, LocalBusiness, WebSite, Breadcrumbs, Services, FAQPage, and Products.
 */
function fds_render_schema_jsonld() {
    $seo = fds_get_seo_data();
    $home_url = trailingslashit(home_url('/'));
    $logo_url = fds_get_default_share_image();

    // 1. Organization & LocalBusiness Schema
    $org_schema = [
        '@context'        => 'https://schema.org',
        '@type'           => ['Organization', 'LocalBusiness'],
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
        'telephone'       => '+62-821-3555-5347',
        'email'           => 'info@fulldronesolutions.com',
        'priceRange'      => '$$$$',
        'address'         => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'Jl. Magelang KM 14, Murangan VII, Triharjo',
            'addressLocality' => 'Sleman',
            'addressRegion'   => 'Daerah Istimewa Yogyakarta',
            'postalCode'      => '55514',
            'addressCountry'  => 'ID',
        ],
        'geo'             => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => -7.6975,
            'longitude' => 110.3548,
        ],
        'areaServed'      => [
            '@type' => 'Country',
            'name'  => 'Indonesia',
        ],
        'knowsAbout'      => [
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
            'Drone Reboisasi Hutan & Penyebaran Seedball Otonom',
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
            'name'            => 'Layanan Solusi Drone FDS',
            'itemListElement' => [
                [
                    '@type' => 'OfferCatalog',
                    'name'  => 'Jasa Pemetaan & Survey Drone',
                    'itemListElement' => [
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Pemetaan Drone & Drone Mapping']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Survey Drone LiDAR Akurasi Tinggi']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Pemetaan Drone Tambang (Volume Cut & Fill)']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Drone Foto Udara & Pemetaan GIS']],
                    ],
                ],
                [
                    '@type' => 'OfferCatalog',
                    'name'  => 'Solusi Drone Pertanian Presisi',
                    'itemListElement' => [
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Pengadaan Drone Pertanian Sprayer FERTO (5L–50L)']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Solusi Drone Sprayer & Granule Spreader TKDN 60,74%']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Jasa Penyemprotan & Pemupukan Presisi']],
                    ],
                ],
                [
                    '@type' => 'OfferCatalog',
                    'name'  => 'Misi Khusus & Tanggap Darurat',
                    'itemListElement' => [
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Drone Pencarian Korban Bencana (SAR Thermal)']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Drone Kargo Logistik Darurat']],
                        ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Drone Reboisasi & Sebar Benih Otonom']],
                    ],
                ],
            ],
        ],
        'contactPoint'    => [
            [
                '@type'             => 'ContactPoint',
                'telephone'         => '+62-821-3555-5347',
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

    // 4. Service Schemas for Target Keywords (High-value B2B intent)
    $services_schema = [
        [
            '@context'      => 'https://schema.org',
            '@type'         => 'Service',
            '@id'           => $home_url . '#service-drone-mapping',
            'name'          => 'Jasa Pemetaan Drone, Survey LiDAR & Tambang (Drone Mapping & GIS)',
            'serviceType'   => 'Drone Mapping, Aerial Survey, Mining Topography & GIS Mapping',
            'provider'      => ['@id' => $home_url . '#organization'],
            'areaServed'    => ['@type' => 'Country', 'name' => 'Indonesia'],
            'description'   => 'Layanan jasa survey drone LiDAR, pemetaan drone tambang untuk perhitungan volume cut and fill, ortomosaik foto udara sub-sentimeter, dan analisis GIS menggunakan armada UAV Fixed-Wing Hybrid VTOL DELTAV.',
            'offers'        => [
                '@type'         => 'Offer',
                'priceCurrency' => 'IDR',
                'price'         => '0',
                'availability'  => 'https://schema.org/InStock',
                'url'           => $home_url . '#kontak',
            ]
        ],
        [
            '@context'      => 'https://schema.org',
            '@type'         => 'Service',
            '@id'           => $home_url . '#service-drone-pertanian',
            'name'          => 'Solusi & Pengadaan Drone Pertanian Presisi TKDN 60,74%',
            'serviceType'   => 'Precision Agriculture Drone & Agricultural Sprayer Services',
            'provider'      => ['@id' => $home_url . '#organization'],
            'areaServed'    => ['@type' => 'Country', 'name' => 'Indonesia'],
            'description'   => 'Solusi drone pertanian sprayer dan spreader granule FDS FERTO (kapasitas 5L hingga 50L) bersertifikasi TKDN 60,74% & SNI resmi dengan keunggulan servis dan suku cadang lokal Indonesia.',
            'offers'        => [
                '@type'         => 'Offer',
                'priceCurrency' => 'IDR',
                'price'         => '0',
                'availability'  => 'https://schema.org/InStock',
                'url'           => $home_url . '#produk',
            ]
        ],
        [
            '@context'      => 'https://schema.org',
            '@type'         => 'Service',
            '@id'           => $home_url . '#service-drone-bencana',
            'name'          => 'Drone Pencarian Korban Bencana & Misi SAR (Thermal Surveillance)',
            'serviceType'   => 'Disaster Relief, Emergency SAR & Thermal Surveillance Drone',
            'provider'      => ['@id' => $home_url . '#organization'],
            'areaServed'    => ['@type' => 'Country', 'name' => 'Indonesia'],
            'description'   => 'Armada UAV pemantauan darurat berdaya jelajah tinggi dengan kamera termal inframerah dan transmisi video langsung untuk misi pencarian korban bencana (SAR), asesmen kerusakan pasca-bencana, dan mitigasi karhutla.',
            'offers'        => [
                '@type'         => 'Offer',
                'priceCurrency' => 'IDR',
                'price'         => '0',
                'availability'  => 'https://schema.org/InStock',
                'url'           => $home_url . '#kontak',
            ]
        ],
    ];

    // 5. FAQPage Schema (Targets People Also Ask & Featured Snippets on Google)
    $faq_schema = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        '@id'        => $home_url . '#faq',
        'mainEntity' => [
            [
                '@type'          => 'Question',
                'name'           => 'Apakah PT Karya Solusi Angkasa (FDS) merupakan produsen UAV resmi di Indonesia?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'Ya. PT Karya Solusi Angkasa (Full Drone Solutions) adalah produsen UAV terdaftar di Indonesia yang memproduksi drone pertanian, drone mapping VTOL, drone kargo, dan reboisasi dengan sertifikasi nilai TKDN + BMP mencapai 60,74%, Standar Nasional Indonesia SNI 9199:2023, dan ISO 9001:2015 berlokasi pabrik di Sleman, Yogyakarta.',
                ],
            ],
            [
                '@type'          => 'Question',
                'name'           => 'Apa saja keunggulan drone pertanian FDS FERTO untuk agrikultur Indonesia?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'Drone pertanian FDS seri FERTO (tersedia kapasitas 5L, 10L, 20L, 30L, hingga 50L) dirancang dengan sertifikasi TKDN 60,74% resmi untuk pengadaan instansi pemerintah/BUMN, ketersediaan suku cadang ready-stock lokal tanpa inden luar negeri, garansi pabrikan langsung di Indonesia, dan sistem kendali GCS berbahasa Indonesia (FDS STATION).',
                ],
            ],
            [
                '@type'          => 'Question',
                'name'           => 'Layanan apa saja yang disediakan dalam jasa pemetaan drone dan survey LiDAR FDS?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'FDS menyediakan layanan drone mapping lengkap: survey drone LiDAR akurasi tinggi, jasa pemetaan drone tambang untuk perhitungan volume cut and fill dan stockpile, pemetaan GIS koridor infrastruktur jalan dan kelistrikan, serta foto udara ortomosaik sub-sentimeter menggunakan Fixed-Wing Hybrid VTOL DELTAV dengan jangkauan jelajah hingga 60 km.',
                ],
            ],
            [
                '@type'          => 'Question',
                'name'           => 'Apakah drone FDS dapat digunakan untuk misi pencarian korban bencana dan SAR?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => 'Tentu. FDS memiliki drone khusus tanggap darurat yang dilengkapi sensor kamera termal inframerah (thermal imaging) dan kamera zoom optik tinggi untuk misi pencarian korban bencana alam (SAR), pemantauan banjir, serta patroli otonom pendeteksian titik api karhutla secara real-time.',
                ],
            ],
        ],
    ];

    $schemas = [$org_schema, $website_schema, $breadcrumb_schema];
    foreach ($services_schema as $srv) {
        $schemas[] = $srv;
    }
    $schemas[] = $faq_schema;

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
                'offers'              => [
                    '@type'           => 'Offer',
                    'url'             => $permalink,
                    'priceCurrency'   => 'IDR',
                    'price'           => '0',
                    'priceValidUntil' => '2028-12-31',
                    'availability'    => 'https://schema.org/InStock',
                    'seller'          => [
                        '@id' => $home_url . '#organization',
                    ],
                    'description'     => 'Konsultasi pengadaan resmi PT Karya Solusi Angkasa bersertifikat TKDN 60,74%.',
                ],
            ];

            $schemas[] = $product_schema;
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

/**
 * Filter Rank Math Title for Drone CPT and Custom Pages
 * Prioritizes user custom title from Rank Math snippet editor if set.
 */
add_filter('rank_math/frontend/title', function ($title) {
    if (is_singular('drone')) {
        $drone_id = get_the_ID();
        
        // JIKA USER SUDAH MENGISI / MENGEDIT JUDUL DI METABOX RANK MATH, GUNAKAN JUDUL USER!
        $custom_rm_title = get_post_meta($drone_id, 'rank_math_title', true);
        if (!empty($custom_rm_title)) {
            return $title;
        }

        // Fallback cerdas jika user belum mengisi judul custom di Rank Math:
        $d_title  = get_the_title($drone_id);
        $kategori = get_post_meta($drone_id, 'drone_kategori', true) ?: 'UAV Industri';
        
        $cat_lower = strtolower($kategori);
        if (strpos($cat_lower, 'agri') !== false) {
            return "{$d_title} — Drone Pertanian Presisi TKDN 60,74% & SNI Resmi | Full Drone Solutions";
        } elseif (strpos($cat_lower, 'peta') !== false) {
            return "{$d_title} — Drone Mapping & Jasa Survey Pemetaan LiDAR GIS | Full Drone Solutions";
        } elseif (strpos($cat_lower, 'kargo') !== false) {
            return "{$d_title} — Drone Kargo Logistik Otonom & Medis Darurat | Full Drone Solutions";
        } else {
            return "{$d_title} — Drone Reboisasi Hutan & SAR Kebencanaan TKDN | Full Drone Solutions";
        }
    }
    return $title;
}, 20);

/**
 * Filter Rank Math Meta Description
 * Prioritizes user custom description from Rank Math snippet editor if set.
 */
add_filter('rank_math/frontend/description', function ($description) {
    if (is_singular('drone')) {
        $drone_id = get_the_ID();
        
        // JIKA USER SUDAH MENGISI / MENGEDIT DESKRIPSI DI METABOX RANK MATH, GUNAKAN DESKRIPSI USER!
        $custom_rm_desc = get_post_meta($drone_id, 'rank_math_description', true);
        if (!empty($custom_rm_desc)) {
            return $description;
        }

        $seo_desc = get_post_meta($drone_id, 'rank_math_description', true);
        if ($seo_desc) {
            return $seo_desc;
        }
    }
    return $description;
}, 20);

/**
 * Bridge Custom Template Content into Rank Math Analyzer (Score 100/100)
 * Membantu Content Analyzer Rank Math membaca teks dari tema pada halaman Beranda & Tentang Kami.
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'])) {
        return;
    }
    global $post;
    if (!$post || $post->post_type !== 'page') {
        return;
    }

    $extra_text = '';
    $front_id   = get_option('page_on_front');

    if ($post->ID == $front_id || $post->post_name === 'beranda') {
        $hp = function_exists('App\fds_get_homepage_content') ? fds_get_homepage_content() : [];
        $extra_text = ($hp['hero_title'] ?? '') . ' ' 
                    . ($hp['hero_desc'] ?? '') . ' ' 
                    . ($hp['industri_title'] ?? '') . ' ' 
                    . ($hp['industri_desc'] ?? '') . ' ' 
                    . ($hp['kontak_title'] ?? '') . ' ' 
                    . ($hp['kontak_desc'] ?? '');
    } elseif ($post->post_name === 'tentang-kami') {
        $ab = function_exists('App\fds_get_about_content') ? fds_get_about_content() : [];
        $extra_text = ($ab['hero_title'] ?? '') . ' ' 
                    . ($ab['hero_desc'] ?? '') . ' ' 
                    . ($ab['visi_desc'] ?? '') . ' ' 
                    . ($ab['misi_desc'] ?? '') . ' ' 
                    . ($ab['lokasi_desc'] ?? '');
    }

    if (!empty(trim($extra_text))) {
        $clean_extra = esc_js(wp_strip_all_tags($extra_text));
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
 * Ensure CPT 'drone' is enabled in Rank Math XML Sitemaps
 */
add_filter('rank_math/sitemap/enable_cpt', function ($enabled, $post_type) {
    if ($post_type === 'drone') {
        return true;
    }
    return $enabled;
}, 10, 2);

add_filter('rank_math/sitemap/post_type_archive', function ($include, $post_type) {
    if ($post_type === 'drone') {
        return true;
    }
    return $include;
}, 10, 2);

// =========================================================================
// 8. 301 PERMANENT REDIRECT ENGINE (Fix Old About Page & Legacy Slugs in Google)
// =========================================================================

add_action('template_redirect', function () {
    global $wp;
    $request_uri = trim($_SERVER['REQUEST_URI'] ?? '', '/');
    $path_only   = trim(strtok($request_uri, '?'), '/');
    $path_lower  = strtolower($path_only);

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
    if (!empty($path_lower) && !in_array($path_lower, ['bandingkan', 'tentang-kami', 'kontak', 'blog', 'wp-admin', 'wp-login.php'], true)) {
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
        $rules[] = "Sitemap: {$home}wp-sitemap.xml";
        $rules[] = "Sitemap: {$home}sitemap.xml";
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
        return implode("\n", $rules) . "\n";
    }
    return $output;
}, 99, 2);

// =========================================================================
// 10. GENERATIVE ENGINE OPTIMIZATION (GEO): /llms.txt ENDPOINT
// =========================================================================

/**
 * Add rewrite rule for /llms.txt and /llms-full.txt
 */
add_action('init', function () {
    add_rewrite_rule('^llms\.txt$', 'index.php?fds_llms_txt=1', 'top');
    add_rewrite_rule('^llms-full\.txt$', 'index.php?fds_llms_txt=1', 'top');
    add_rewrite_rule('^sitemap\.xml$', 'index.php?fds_sitemap_xml=1', 'top');
});

add_filter('query_vars', function ($vars) {
    $vars[] = 'fds_llms_txt';
    $vars[] = 'fds_sitemap_xml';
    return $vars;
});

/**
 * Handle /llms.txt and /sitemap.xml rendering
 */
add_action('template_redirect', function () {
    // 1. LLMS.TXT ENDPOINT (GEO Standard 2024-2026)
    if (get_query_var('fds_llms_txt')) {
        header('Content-Type: text/plain; charset=utf-8');
        header('X-Robots-Tag: noindex, follow');

        $site_name = 'PT Karya Solusi Angkasa (Full Drone Solutions / FDS)';
        $home_url  = trailingslashit(home_url());

        $out = [];
        $out[] = "# {$site_name} — Knowledge Base for LLMs & AI Engines";
        $out[] = "> Manufaktur & Produsen UAV Indonesia resmi berstandar TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015.";
        $out[] = "";
        $out[] = "## Ringkasan Perusahaan";
        $out[] = "- Nama Resmi: PT Karya Solusi Angkasa (Full Drone Solutions)";
        $out[] = "- Lokasi Kantor & Pabrik: Jl. Magelang KM 14, Murangan VII, Triharjo, Sleman, D.I. Yogyakarta 55514";
        $out[] = "- Kontak: +62 821-3555-5347 | info@fulldronesolutions.com";
        $out[] = "- Website: {$home_url}";
        $out[] = "- Sertifikasi: TKDN + BMP 60,74% (Kemenperin RI), SNI 9199:2023 (BSN), ISO 9001:2015, DKPPU Kemenhub RI";
        $out[] = "";
        $out[] = "## Lini Produk Drone Resmi FDS";
        
        $drones = get_posts([
            'post_type'      => 'drone',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        ]);

        foreach ($drones as $d) {
            $t = get_the_title($d->ID);
            $url = get_permalink($d->ID);
            $tagline = get_post_meta($d->ID, 'drone_tagline', true);
            $payload = get_post_meta($d->ID, 'drone_spec_kapasitas', true);
            $durasi  = get_post_meta($d->ID, 'drone_spec_durasi', true);
            $baterai = get_post_meta($d->ID, 'drone_spec_baterai', true);
            $prod    = get_post_meta($d->ID, 'drone_spec_produktivitas', true);
            $gcs     = get_post_meta($d->ID, 'drone_spec_gcs', true);
            
            $out[] = "### {$t}";
            $out[] = "- URL: {$url}";
            if ($tagline) $out[] = "- Tagline: {$tagline}";
            if ($payload) $out[] = "- Payload: {$payload}";
            if ($durasi)  $out[] = "- Durasi Terbang: {$durasi}";
            if ($baterai) $out[] = "- Baterai: {$baterai}";
            if ($prod)    $out[] = "- Produktivitas: {$prod}";
            if ($gcs)     $out[] = "- GCS: {$gcs}";
            $out[] = "";
        }

        $out[] = "## Layanan & Jasa Profesional";
        $out[] = "1. Jasa Pemetaan Drone & Survey LiDAR: Fotogrametri topografi tambang, perhitungan volume stockpile cut & fill, koridor transmisi listrik, pemetaan GIS.";
        $out[] = "2. Pengadaan Drone Pertanian Sprayer & Granule (Seri FERTO 5L-50L): Solusi bersertifikasi TKDN 60,74% & SNI 9199:2023 dengan purna jual dan suku cadang lokal Sleman Yogyakarta.";
        $out[] = "3. Drone Reboisasi & Bencana SAR: Penyebaran seedball presisi hingga 20 kg dan pemantauan termal inframerah otonom.";
        $out[] = "4. Pelatihan Pilot & Sertifikasi (FDS Academy): Program sertifikasi pilot drone komersial.";
        $out[] = "## Kata Kunci & Topik Spesialisasi Drone";
        $out[] = "- Drone Pertanian & Agrikultur: Jual drone pertanian, harga drone pertanian, drone sprayer TKDN 60,74%, drone penyemprot pupuk dan pestisida, drone sebar benih padi, drone semprot kelapa sawit, drone FERTO 5L-50L, perbandingan drone DJI Agras.";
        $out[] = "- Pemetaan & Survey LiDAR: Drone mapping, jual drone mapping, jasa pemetaan drone, jasa survey drone LiDAR, pemetaan drone tambang volume cut and fill stockpile, ortofoto GIS, Fixed-Wing VTOL DELTAV.";
        $out[] = "- Industri & Misi Khusus: Drone inspeksi termal SUTET 150kV dan migas, drone patroli karhutla, drone pencarian korban bencana SAR kamera termal inframerah FLIR, drone reboisasi seedball, drone kargo logistik darurat medis.";
        $out[] = "- Legalitas & Manufaktur: Produsen UAV Indonesia resmi (PT Karya Solusi Angkasa), pabrik drone Sleman Yogyakarta, sertifikat TKDN 60,74%, SNI 9199:2023, ISO 9001:2015, pengadaan e-katalog LKPP, sertifikasi pilot FDS Academy.";
        $out[] = "";
        $out[] = "## Keunggulan Utama Produsen UAV Indonesia (FDS)";
        $out[] = "- Sertifikasi Legalitas: Nilai TKDN + BMP mencapai 60,74% (Prioritas LKPP / e-Katalog), SNI 9199:2023, ISO 9001:2015.";
        $out[] = "- Suku Cadang Terjamin: Ready-stock lokal di workshop Yogyakarta tanpa ketergantungan impor.";
        $out[] = "- Ground Control Station: FDS STATION berbahasa Indonesia terintegrasi penuh.";
        $out[] = "- Garansi & Servis: Diberikan langsung oleh teknisi pabrikan di Indonesia.";

        echo implode("\n", $out);
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

        // Drones
        $drones = get_posts([
            'post_type'      => 'drone',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);
        foreach ($drones as $d) {
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
