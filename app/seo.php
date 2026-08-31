<?php

namespace App;

/**
 * FDS SEO & Schema.org Structured Data Engine
 * Enterprise-grade Technical & On-Page SEO for Full Drone Solutions (PT Karya Solusi Angkasa)
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if external SEO plugin is managing meta tags
 */
function fds_has_external_seo_plugin() {
    return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION');
}

/**
 * Get comprehensive SEO metadata for the current query/page
 */
function fds_get_seo_data() {
    global $post, $wp;

    $site_name   = get_bloginfo('name') ?: 'Full Drone Solutions';
    $site_desc   = get_bloginfo('description') ?: 'Solusi UAV Agrikultur, Pemetaan, dan Industri Indonesia — PT Karya Solusi Angkasa';
    $current_url = home_url(add_query_arg([], $wp->request));
    
    // Default fallback values
    $title       = $site_name . ' — ' . $site_desc;
    $description = 'Full Drone Solutions (PT Karya Solusi Angkasa) menghadirkan platform UAV pertanian FERTO 5L-50L, Fixed-Wing VTOL DELTAV, drone inspeksi termal, kargo, dan reboisasi bersertifikasi TKDN 60,74% & SNI 9199:2023 di Indonesia.';
    $keywords    = 'Drone Pertanian Indonesia, Drone Sprayer TKDN, Drone FERTO, Fixed-Wing VTOL DELTAV, Drone Inspeksi 150kV, Drone Kargo DELFRO, Drone Reboisasi REBO, PT Karya Solusi Angkasa, FDS Station GCS';
    $image_url   = '';
    $og_type     = 'website';
    $canonical   = trailingslashit($current_url);
    $breadcrumbs = [
        ['name' => 'Beranda', 'url' => home_url('/')]
    ];

    // 1. FRONT PAGE / HOMEPAGE
    if (is_front_page() || is_home()) {
        $title       = 'Full Drone Solutions — Produsen & Solusi UAV Agrikultur, Pemetaan & Industri Indonesia';
        $description = 'Full Drone Solutions (PT Karya Solusi Angkasa) memproduksi drone pertanian FERTO 5L–50L, VTOL Hybrid DELTAV, inspeksi termal, kargo & reboisasi berstandar TKDN 60,74% & SNI resmi di Indonesia.';
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

            $title       = "{$d_title} — Spesifikasi & Fitur Drone {$kategori} | Full Drone Solutions";
            
            $desc_parts  = [];
            if ($tagline) $desc_parts[] = $tagline;
            if ($payload) $desc_parts[] = "Payload: {$payload}";
            if ($durasi)  $desc_parts[] = "Durasi Terbang: {$durasi}";
            $desc_parts[] = "Sertifikasi TKDN & SNI resmi PT Karya Solusi Angkasa (FDS).";
            $description = implode('. ', $desc_parts);

            $keywords    = "Drone {$d_title}, Spesifikasi {$d_title}, Drone {$kategori}, UAV Indonesia, {$payload}, {$durasi}, FDS PT Karya Solusi Angkasa";
            $canonical   = trailingslashit(get_permalink($drone_post->ID));
            $image_url   = $feat_img ?: fds_get_default_share_image();
            $og_type     = 'product';

            $breadcrumbs[] = ['name' => 'Katalog Drone', 'url' => home_url('/#katalog')];
            $breadcrumbs[] = ['name' => $d_title, 'url' => $canonical];
        }
    }
    // 3. BANDINGKAN DRONE PAGE
    elseif (is_page('bandingkan') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/bandingkan') !== false)) {
        $title       = 'Bandingkan Spesifikasi Drone UAV FDS — Komparasi Model Pertanian, VTOL & Industri';
        $description = 'Bandingkan spesifikasi teknis lengkap drone agrikultur FERTO (5L–50L), Fixed-Wing VTOL DELTAV, drone inspeksi, kargo, dan reboisasi buatan PT Karya Solusi Angkasa.';
        $keywords    = 'Bandingkan Drone Pertanian, Komparasi Drone FDS, Spesifikasi FERTO 50L vs FERTO 30L, Drone VTOL DELTAV, Perbandingan UAV Indonesia';
        $canonical   = trailingslashit(home_url('/bandingkan/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Bandingkan Drone', 'url' => $canonical];
    }
    // 4. TENTANG KAMI PAGE
    elseif (is_page('tentang-kami') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/tentang-kami') !== false)) {
        $title       = 'Tentang Kami — PT Karya Solusi Angkasa (Full Drone Solutions Indonesia)';
        $description = 'Mengenal PT Karya Solusi Angkasa (Full Drone Solutions), manufaktur drone nasional berstandar TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015 berpusat di Sleman, Yogyakarta.';
        $keywords    = 'Tentang Full Drone Solutions, PT Karya Solusi Angkasa, Pabrik Drone Indonesia, Produsen UAV Yogyakarta, Drone TKDN SNI';
        $canonical   = trailingslashit(home_url('/tentang-kami/'));
        $image_url   = fds_get_default_share_image();

        $breadcrumbs[] = ['name' => 'Tentang Kami', 'url' => $canonical];
    }
    // 5. GENERIC / OTHER PAGES
    else {
        if (is_singular()) {
            $p_title     = get_the_title();
            $title       = "{$p_title} — Full Drone Solutions";
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
    $seo = fds_get_seo_data();
    $has_plugin = fds_has_external_seo_plugin();

    echo "\n    <!-- ============================================================ -->\n";
    echo "    <!-- FDS SEO ENGINE & OPEN GRAPH META TAGS                      -->\n";
    echo "    <!-- ============================================================ -->\n";

    if (!$has_plugin) {
        echo '    <title>' . esc_html($seo['title']) . "</title>\n";
        echo '    <meta name="description" content="' . esc_attr($seo['description']) . "\">\n";
        echo '    <meta name="keywords" content="' . esc_attr($seo['keywords']) . "\">\n";
        echo '    <link rel="canonical" href="' . esc_url($seo['canonical']) . "\">\n";
        echo "    <meta name=\"robots\" content=\"index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1\">\n";
    }

    // Open Graph Tags
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

    // Twitter Card Tags
    echo "    <meta name=\"twitter:card\" content=\"summary_large_image\">\n";
    echo '    <meta name="twitter:title" content="' . esc_attr($seo['title']) . "\">\n";
    echo '    <meta name="twitter:description" content="' . esc_attr($seo['description']) . "\">\n";
    if (!empty($seo['image'])) {
        echo '    <meta name="twitter:image" content="' . esc_url($seo['image']) . "\">\n";
    }
}

/**
 * Generate JSON-LD Structured Data (Schema.org)
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
        'description'     => 'Produsen dan penyedia solusi UAV (Unmanned Aerial Vehicle) terkemuka di Indonesia untuk sektor agrikultur, pemetaan GIS, inspeksi infrastruktur, logistik kargo, dan reboisasi hutan.',
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
            'Drone Pertanian & Sprayer',
            'Drone Spreader Granule',
            'UAV Fixed-Wing Hybrid VTOL',
            'Survei Topografi & Pemetaan GIS',
            'Inspeksi Termal Transmisi Listrik 150kV',
            'Drone Kargo Logistik',
            'Restorasi Hutan & Seedball Dispensing',
            'Sertifikasi TKDN & BMP hingga 60,74%',
            'Standar Nasional Indonesia SNI 9199:2023',
            'Sistem Manajemen Mutu ISO 9001:2015',
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

    // 2. WebSite Schema
    $website_schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        '@id'             => $home_url . '#website',
        'url'             => $home_url,
        'name'            => 'Full Drone Solutions',
        'description'     => 'Solusi UAV Pertanian, Pemetaan, dan Industri Indonesia',
        'publisher'       => [
            '@id' => $home_url . '#organization',
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

    $schemas = [$org_schema, $website_schema, $breadcrumb_schema];

    // 4. Product Schema (Only on Single Drone CPT Page)
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
                    'description'     => 'Konsultasi pengadaan dan demo unit resmi PT Karya Solusi Angkasa.',
                ],
            ];

            $schemas[] = $product_schema;
        }
    }

    echo "\n    <!-- SCHEMA.ORG STRUCTURED DATA (JSON-LD) -->\n";
    foreach ($schemas as $s) {
        echo '    <script type="application/ld+json">' . "\n";
        echo json_encode($s, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
        echo "    </script>\n";
    }
}
