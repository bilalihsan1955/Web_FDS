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
    return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION');
}

/**
 * Get comprehensive SEO metadata for the current query/page
 */
function fds_get_seo_data() {
    global $post, $wp;

    $site_name   = get_bloginfo('name') ?: 'Full Drone Solutions';
    $site_desc   = get_bloginfo('description') ?: 'Produsen UAV Indonesia — Solusi Pertanian Presisi, Pemetaan GIS & Industri';
    $current_url = home_url(add_query_arg([], $wp->request));
    
    // Core high-intent keywords
    $primary_keywords = 'drone mapping, jasa pemetaan drone, jasa survey drone, jasa drone foto udara, jasa drone lidar, jasa pemetaan drone tambang, pemetaan GIS drone, drone pencarian korban bencana, drone pertanian, drone sprayer TKDN, drone FERTO, Fixed-Wing VTOL DELTAV, produsen UAV Indonesia, PT Karya Solusi Angkasa, FDS Station GCS';

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
            if (strpos($cat_lower, 'agri') !== false) {
                $keywords = "drone pertanian, drone sprayer {$d_title}, drone penyemprot pupuk, drone pertanian indonesia, produsen UAV Indonesia, TKDN, SNI 9199, {$payload}";
            } elseif (strpos($cat_lower, 'peta') !== false || strpos($cat_lower, 'gis') !== false) {
                $keywords = "drone mapping, jasa pemetaan drone, jasa survey drone, jasa drone foto udara, jasa drone lidar, jasa pemetaan drone tambang, pemetaan GIS drone, fixed wing VTOL, {$d_title}";
            } elseif (strpos($cat_lower, 'rebo') !== false || strpos($cat_lower, 'kargo') !== false) {
                $keywords = "drone pencarian korban bencana, drone SAR, drone reboisasi, drone kargo logistik, drone tanggap darurat, UAV Indonesia {$d_title}";
            } else {
                $keywords = "Drone {$d_title}, Drone {$kategori}, produsen UAV Indonesia, jasa survey drone, inspeksi drone, PT Karya Solusi Angkasa";
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
    // 5. BLOG / NEWSROOM
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
            'Drone Mapping & Fotogrametri Udara',
            'Jasa Pemetaan Drone',
            'Jasa Survey Drone LiDAR',
            'Jasa Drone Foto Udara',
            'Jasa Pemetaan Drone Tambang',
            'Pemetaan GIS Drone',
            'Drone Pertanian & Sprayer Presisi',
            'Sistem Kendali FDS STATION',
            'Drone Pencarian Korban Bencana & Misi SAR',
            'Produsen UAV Indonesia Resmi',
            'Sertifikasi TKDN & BMP hingga 60,74%',
            'Standar Nasional Indonesia SNI 9199:2023',
            'Sistem Manajemen Mutu ISO 9001:2015',
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
 */
add_filter('rank_math/frontend/title', function ($title) {
    if (is_singular('drone')) {
        $drone_id = get_the_ID();
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
 */
add_filter('rank_math/frontend/description', function ($description) {
    if (is_singular('drone')) {
        $drone_id = get_the_ID();
        $seo_desc = get_post_meta($drone_id, 'rank_math_description', true);
        if ($seo_desc) {
            return $seo_desc;
        }
    }
    return $description;
}, 20);

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
        'about-us',
        'aboutus',
        'profil',
        'profil-kami',
        'profil-perusahaan',
        'profile',
        'company-profile',
        'company',
        'tentang',
        'tentangkami',
        'page/about',
        'about.html',
        'about.php',
    ];

    if (in_array($path_lower, $legacy_about_slugs, true)) {
        wp_safe_redirect(home_url('/tentang-kami/'), 301);
        exit;
    }

    // 2. Legacy Compare Page 301 Redirects -> /bandingkan/
    $legacy_compare_slugs = [
        'compare',
        'compare-drone',
        'bandingkan-drone',
        'komparasi',
        'komparasi-drone',
        'perbandingan-drone',
    ];

    if (in_array($path_lower, $legacy_compare_slugs, true)) {
        wp_safe_redirect(home_url('/bandingkan/'), 301);
        exit;
    }

    // 3. Direct Root Slugs for Drones (e.g., /ferto-10l/ -> /drone/ferto-10l/)
    if (!empty($path_lower) && !in_array($path_lower, ['bandingkan', 'tentang-kami', 'blog', 'wp-admin', 'wp-login.php'], true)) {
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
        
        $rm_sitemap = $home . 'sitemap_index.xml';
        $wp_sitemap = $home . 'wp-sitemap.xml';
        $custom_sitemap = $home . 'sitemap.xml';

        $rules = [];
        $rules[] = "# ============================================================";
        $rules[] = "# FDS ENTERPRISE SEO & GEO DIRECTIVES (PT KARYA SOLUSI ANGKASA)";
        $rules[] = "# ============================================================";
        $rules[] = "Sitemap: {$rm_sitemap}";
        $rules[] = "Sitemap: {$wp_sitemap}";
        $rules[] = "Sitemap: {$custom_sitemap}";
        $rules[] = "";
        $rules[] = "# Standard Search Engine Bots";
        $rules[] = "User-agent: *";
        $rules[] = "Allow: /";
        $rules[] = "Disallow: /wp-admin/";
        $rules[] = "Disallow: /wp-includes/";
        $rules[] = "Disallow: /wp-content/plugins/";
        $rules[] = "Allow: /wp-content/uploads/";
        $rules[] = "";
        $rules[] = "# Tier Mythic AI Crawlers & LLM Indexers (GEO Optimization)";
        $rules[] = "User-agent: GPTBot";
        $rules[] = "Allow: /";
        $rules[] = "";
        $rules[] = "User-agent: OAI-SearchBot";
        $rules[] = "Allow: /";
        $rules[] = "";
        $rules[] = "User-agent: ClaudeBot";
        $rules[] = "Allow: /";
        $rules[] = "";
        $rules[] = "User-agent: Claude-SearchBot";
        $rules[] = "Allow: /";
        $rules[] = "";
        $rules[] = "User-agent: PerplexityBot";
        $rules[] = "Allow: /";
        $rules[] = "";
        $rules[] = "User-agent: Google-Extended";
        $rules[] = "Allow: /";
        $rules[] = "";
        $rules[] = "User-agent: Applebot-Extended";
        $rules[] = "Allow: /";
        $rules[] = "";
        $rules[] = "User-agent: Amazonbot";
        $rules[] = "Allow: /";
        $rules[] = "";
        $rules[] = "User-agent: CCBot";
        $rules[] = "Allow: /";

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
