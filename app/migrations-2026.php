<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * =========================================================================
 * FDS ENTERPRISE MIGRATION & SANITIZATION ENGINE (2026-11)
 * =========================================================================
 * Idempotent, non-destructive migration script that:
 * 1. Removes exposed dev directories (.agents/, scratch/) from active theme dir
 * 2. Normalizes blogname & tagline (keeps "Full Drone Solutions" as brand name)
 * 3. Provisions /produk/ catalog page and ensures /privacy-policy/ is published & indexable
 * 4. Clears any accidental staging canonicals (compro.fulldronesolutions.co.id)
 * 5. Scrubs banned legacy tokens & misattributed claims from saved fds_* options
 * 6. Synchronizes the 9 CPT drone posts (meta + unique technical guide + 1:1 FAQs)
 * 7. Flushes rewrite rules, transients, /llms.txt, and LiteSpeed cache
 *
 * Strictly does NOT bulk-touch blog post_modified dates, and does NOT delete
 * legacy Elementor/WooCommerce pages, duplicate posts, tags, nav menus, or users.
 */

if (!function_exists(__NAMESPACE__ . '\\fds_recursive_rmdir')) {
    function fds_recursive_rmdir(string $dir): void {
        if (!is_dir($dir)) {
            return;
        }
        $items = @scandir($dir);
        if (!is_array($items)) {
            return;
        }
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path) && !is_link($path)) {
                fds_recursive_rmdir($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}

if (!function_exists(__NAMESPACE__ . '\\fds_migration_scrub_string')) {
    function fds_migration_scrub_string(string $val, ?bool &$changed = false): string {
        $orig = $val;
        if ($val === '') {
            return $val;
        }

        // Exact phrase replacements (preserve meaning without inventing numbers)
        $replacements = [
            '+62 821-3555-5347'              => '+62 8112 748 882',
            '62 821-3555-5347'               => '62 8112 748 882',
            '0821-3555-5347'                 => '0811-2748-882',
            '821-3555-5347'                  => '8112 748 882',
            'wa.me/6282135555347'            => 'wa.me/628112748882',
            'FDS STATION'                    => 'FDS Station',
            'Best UAV'                       => 'UAV Industri',
            'Best Layanan'                   => 'Layanan UAV',
            '±2.35%'                         => 'berbasis RTK/PPK & GCP terkalibrasi',
            '± 2.35%'                        => 'berbasis RTK/PPK & GCP terkalibrasi',
            '2.35%'                          => 'terkalibrasi RTK/PPK & GCP',
            'kamera termal FLIR'             => 'kamera termal inframerah',
            'Kamera Termal FLIR'             => 'Kamera Termal Inframerah',
            'FLIR'                           => 'kamera termal inframerah',
            'sejak tahun 2012'               => 'secara berkelanjutan di Indonesia',
            'Sejak tahun 2012'               => 'Secara berkelanjutan di Indonesia',
            'sejak 2012'                     => 'dengan rekayasa lokal Indonesia',
            'Sejak 2012'                     => 'Dengan rekayasa lokal Indonesia',
            'Sejak Tahun 2012'               => 'Dengan Rekayasa Lokal Indonesia',
            '(2012)'                         => '',
            '1×24 jam kerja'                 => 'pada jam kerja',
            '1x24 jam kerja'                 => 'pada jam kerja',
            '1×24 jam'                       => 'pada jam kerja',
            '1x24 jam'                       => 'pada jam kerja',
            'Maksimal 48 Jam'                => 'Terjadwal Sesuai Wilayah',
            'maksimal 48 jam'                => 'terjadwal sesuai wilayah',
            '48 Jam'                         => 'Terjadwal',
            '48 jam'                         => 'terjadwal',
            '24/7'                           => 'jam operasional kerja',
            '24 Jam'                         => 'jam kerja',
            '24 jam'                         => 'jam kerja',
            'Avero'                          => 'FDS Station',
            '.kml, .kmz, '                   => '.SHP, .GeoJSON, ',
            '.kml/.kmz'                      => '.SHP/.GeoJSON',
            'KML/KMZ'                        => 'ESRI Shapefile (.shp), GeoJSON, AutoCAD DWG/DXF',
            'ferto 20l'                      => 'ferto 15l',
            'FERTO 20L'                      => 'FERTO 15L',
            '/#produk'                       => '/produk/',
            '/#kontak'                       => '/kontak/#hubungi-form',
            'compro.fulldronesolutions.co.id'=> 'fulldronesolutions.co.id',
        ];

        $val = str_replace(array_keys($replacements), array_values($replacements), $val);

        // Remove lingering IP54/IP67/IP68 unverified ratings
        $val = (string) preg_replace('/\bIP\s?(54|67|68)\b(\s*\/\s*)?/i', '', $val);
        // Remove standalone legacy Avero / 2012 references in branding copy
        $val = (string) preg_replace('/\bTahun\s+2012\b/i', 'Awal Riset Nasional', $val);

        // Clean double spaces if replacements stripped parentheses
        $val = (string) preg_replace('/\s{2,}/', ' ', $val);

        if ($val !== $orig) {
            $changed = true;
        }
        return trim($val) === '' && $orig !== '' ? $orig : $val;
    }
}

if (!function_exists(__NAMESPACE__ . '\\fds_migration_scrub_recursive')) {
    function fds_migration_scrub_recursive($value, ?bool &$changed = false) {
        if (is_string($value)) {
            return fds_migration_scrub_string($value, $changed);
        }
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = fds_migration_scrub_recursive($v, $changed);
            }
        }
        return $value;
    }
}

if (!function_exists(__NAMESPACE__ . '\\fds_migration_replace_stale_urls')) {
    function fds_migration_replace_stale_urls($value, string $theme_uri, ?bool &$changed = false) {
        if (is_string($value)) {
            $orig = $value;
            if (stripos($value, 'unsplash.com') !== false) {
                $value = $theme_uri . '/public/images/site/hero-1.webp';
            } elseif (stripos($value, 'perjalanan-kami') !== false) {
                $value = $theme_uri . '/public/images/site/about-team.webp';
            } elseif (stripos($value, 'DELFRO.png') !== false) {
                $value = $theme_uri . '/public/images/cutouts/delfro.png';
            }
            if ($value !== $orig) {
                $changed = true;
            }
            return $value;
        }
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = fds_migration_replace_stale_urls($v, $theme_uri, $changed);
            }
        }
        return $value;
    }
}

if (!function_exists(__NAMESPACE__ . '\\fds_run_migration_2026_11')) {
    function fds_run_migration_2026_11(): void {
        if (get_transient('fds_migration_2026_lock')) {
            return;
        }
        set_transient('fds_migration_2026_lock', 1, 120);

        $theme_dir = wp_normalize_path(get_template_directory());
        $theme_uri = untrailingslashit(get_template_directory_uri());

        // 1. FASE 0 Security: Ensure .agents/, .claude/, scratch/, and legacy setup scripts never linger inside active wp-content/themes/*
        if (strpos($theme_dir, '/wp-content/themes/') !== false) {
            fds_recursive_rmdir($theme_dir . '/.agents');
            fds_recursive_rmdir($theme_dir . '/.claude');
            fds_recursive_rmdir($theme_dir . '/scratch');
            @unlink($theme_dir . '/.env');
            @unlink($theme_dir . '/fds-dummy-insert.php');
            @unlink($theme_dir . '/fds-setup.php');
        }

        // 2. Site Title & Tagline (Decision B1: Brand utama "Full Drone Solutions")
        if (get_option('blogname') !== 'Full Drone Solutions') {
            update_option('blogname', 'Full Drone Solutions');
        }
        $blogdesc = (string) get_option('blogdescription', '');
        if ($blogdesc === '' || stripos($blogdesc, 'Just another') !== false || stripos($blogdesc, 'Best UAV') !== false || preg_match('/\b2012\b/', $blogdesc)) {
            update_option('blogdescription', 'Produsen Drone & UAV No.1 Indonesia — Pertanian Presisi, Pemetaan & Industri');
        }

        // 3. Provision /produk/ Catalog Page (non-destructive)
        $produk_page = get_page_by_path('produk');
        if (!$produk_page) {
            $produk_candidates = get_posts([
                'name'           => 'produk',
                'post_type'      => 'page',
                'post_status'    => 'any',
                'posts_per_page' => 1,
            ]);
            if (!empty($produk_candidates)) {
                $produk_page = $produk_candidates[0];
            }
        }

        if (!$produk_page) {
            $produk_id = wp_insert_post([
                'post_title'   => 'Produk Drone FDS',
                'post_name'    => 'produk',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
                'menu_order'   => 2,
            ]);
            if ($produk_id && !is_wp_error($produk_id)) {
                update_post_meta($produk_id, '_wp_page_template', 'template-produk.blade.php');
                update_post_meta($produk_id, 'rank_math_robots', ['index']);
            }
        } else {
            if ($produk_page->post_status !== 'publish') {
                wp_update_post([
                    'ID'          => $produk_page->ID,
                    'post_status' => 'publish',
                ]);
            }
            update_post_meta($produk_page->ID, '_wp_page_template', 'template-produk.blade.php');
        }

        // 4. Ensure /privacy-policy/ is published, has clean UU PDP copy, and is indexable
        $privacy_page = get_page_by_path('privacy-policy');
        if (!$privacy_page) {
            $pp_candidates = get_posts([
                'name'           => 'privacy-policy',
                'post_type'      => 'page',
                'post_status'    => 'any',
                'posts_per_page' => 1,
            ]);
            if (!empty($pp_candidates)) {
                $privacy_page = $pp_candidates[0];
            }
        }

        $clean_privacy_body = <<<'HTML'
<h2>Kebijakan Privasi &amp; Pelindungan Data Pribadi</h2>
<p>Dokumen ini menjelaskan bagaimana <strong>PT Karya Solusi Angkasa (Full Drone Solutions / FDS)</strong> mengumpulkan, menggunakan, menyimpan, dan melindungi data pribadi Anda saat mengakses situs resmi <strong>fulldronesolutions.co.id</strong> sesuai Undang-Undang Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi (UU PDP).</p>

<h3>1. Data yang Kami Kumpulkan</h3>
<ul>
  <li><strong>Data Formulir Konsultasi &amp; Penawaran (B2B):</strong> Nama lengkap, perusahaan atau instansi, alamat email bisnis, nomor telepon/WhatsApp, fokus layanan/produk, lokasi proyek, dan deskripsi kebutuhan.</li>
  <li><strong>Data Unduhan Brosur Teknis:</strong> Alamat email bisnis yang Anda kirimkan saat mengakses dokumen spesifikasi produk.</li>
  <li><strong>Data Teknis Kunjungan:</strong> Informasi peramban, halaman rujukan, parameter kampanye (UTM), serta cookie fungsional untuk menjaga keamanan dan keandalan situs.</li>
</ul>

<h3>2. Tujuan Pemrosesan Data</h3>
<p>Data digunakan secara terbatas untuk menindaklanjuti permintaan penawaran teknis, menjadwalkan konsultasi atau demonstrasi armada UAV, mengirimkan dokumen brosur resmi, serta meningkatkan mutu layanan situs. Kami tidak pernah menjual atau menyewakan data kontak Anda kepada pihak ketiga.</p>

<h3>3. Penyimpanan, Keamanan &amp; Hak Pemilik Data</h3>
<p>Data disimpan pada infrastruktur server terproteksi dengan pembatasan akses internal yang ketat. Sesuai UU PDP, Anda berhak meminta informasi, pembaruan, koreksi, maupun penghapusan data pribadi Anda kapan saja dengan menghubungi kami melalui email <a href="mailto:marketing@fulldronesolutions.com">marketing@fulldronesolutions.com</a> atau telepon <a href="tel:+628112748882">+62 8112 748 882</a>.</p>
HTML;

        if (!$privacy_page) {
            $pp_id = wp_insert_post([
                'post_title'   => 'Kebijakan Privasi',
                'post_name'    => 'privacy-policy',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => $clean_privacy_body,
            ]);
            if ($pp_id && !is_wp_error($pp_id)) {
                update_post_meta($pp_id, 'rank_math_robots', ['index']);
                update_option('wp_page_for_privacy_policy', (int) $pp_id);
            }
        } else {
            $update_pp = [];
            if ($privacy_page->post_status !== 'publish') {
                $update_pp['post_status'] = 'publish';
            }
            $pp_raw   = (string) $privacy_page->post_content;
            $pp_words = str_word_count(wp_strip_all_tags($pp_raw));
            if ($pp_words < 60 || stripos($pp_raw, 'Suggested text') !== false || stripos($pp_raw, '.kml') !== false || stripos($pp_raw, '.kmz') !== false || stripos($pp_raw, 'sejak 2012') !== false) {
                $update_pp['post_content'] = $clean_privacy_body;
            }
            if (!empty($update_pp)) {
                $update_pp['ID'] = $privacy_page->ID;
                wp_update_post($update_pp);
            }
            $rm_robots = get_post_meta($privacy_page->ID, 'rank_math_robots', true);
            if (is_array($rm_robots) && in_array('noindex', $rm_robots, true)) {
                update_post_meta($privacy_page->ID, 'rank_math_robots', ['index']);
            }
            $pp_canon = (string) get_post_meta($privacy_page->ID, 'rank_math_canonical_url', true);
            if ($pp_canon !== '' && stripos($pp_canon, 'compro.fulldronesolutions') !== false) {
                delete_post_meta($privacy_page->ID, 'rank_math_canonical_url');
            }
            if (!get_option('wp_page_for_privacy_policy')) {
                update_option('wp_page_for_privacy_policy', (int) $privacy_page->ID);
            }
        }

        // 5. Clear any accidental staging canonicals (compro.fulldronesolutions.co.id) across public post types
        global $wpdb;
        $staging_canons = $wpdb->get_results(
            "SELECT post_id, meta_key FROM {$wpdb->postmeta} WHERE meta_key IN ('rank_math_canonical_url', '_yoast_wpseo_canonical') AND meta_value LIKE '%compro.fulldronesolutions%'"
        );
        if (!empty($staging_canons)) {
            foreach ($staging_canons as $sc) {
                delete_post_meta((int) $sc->post_id, (string) $sc->meta_key);
            }
        }

        // 6. Non-destructive scrub of stale fds_* DB options (only modifies entries containing banned tokens)
        $option_rows = $wpdb->get_results(
            "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'fds_%' AND option_name NOT IN ('fds_turnstile_site_key', 'fds_turnstile_secret_key', 'fds_lead_webhook_url', 'fds_ga4_id', 'fds_gtm_id', 'fds_gads_id', 'fds_gads_label', 'fds_meta_pixel_id', 'fds_migration_2026_11_v2')"
        );

        $stale_pattern = '/(Best UAV|Best Layanan|sejak\s*(?:tahun\s*)?2012|\b2012\b|821-3555-5347|82135555347|unsplash\.com|perjalanan-kami|DELFRO\.png|FDS STATION|IP\s?(?:54|67|68)|\bAvero\b|\.kml\b|\.kmz\b|ferto[\s-]?20l|2\.35%|48\s*jam|1[×x]24\s*jam|24\/7|24\s*jam|\/#produk|\/#kontak|\bFLIR\b|compro\.fulldronesolutions)/i';

        if (!empty($option_rows)) {
            foreach ($option_rows as $orow) {
                $opt_name = (string) $orow->option_name;
                $raw_val  = get_option($opt_name);
                if ($raw_val === false || $raw_val === null) {
                    continue;
                }

                // Special address/phone/about normalization ONLY if holding legacy strings
                if (in_array($opt_name, ['fds_global_address', 'fds_footer_address', 'fds_kontak_address', 'fds_contact_address', 'fds_about_info_alamat'], true)) {
                    $s = trim((string) $raw_val);
                    if ($s !== '' && stripos($s, 'Lukito Harjo') === false && (stripos($s, 'Tajem') !== false || stripos($s, 'Sleman') !== false || stripos($s, 'Yogyakarta') !== false)) {
                        update_option($opt_name, 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285');
                        continue;
                    }
                }

                // Ensure disabled/unverified social profiles stay inactive if URL empty
                if (in_array($opt_name, ['fds_sosmed_twitter_active', 'fds_sosmed_linkedin_active'], true)) {
                    $url_key = str_replace('_active', '_url', $opt_name);
                    $u_val   = trim((string) get_option($url_key, ''));
                    if ($u_val === '' || $u_val === '#') {
                        update_option($opt_name, '0');
                    }
                    continue;
                }

                // Targeted fixes for stale SNI/TKDN/2012 defaults in saved options
                if ($opt_name === 'fds_hero_badge' && preg_match('/SNI/i', (string) $raw_val) && stripos((string) $raw_val, 'FERTO') === false) {
                    update_option($opt_name, 'Manufaktur UAV Terkemuka di Indonesia');
                    continue;
                }
                if ($opt_name === 'fds_about_stat1_num' && trim((string) $raw_val) === '2012') {
                    update_option($opt_name, 'Lokal');
                    continue;
                }
                if ($opt_name === 'fds_about_stat1_lbl' && preg_match('/2012|Berdiri/i', (string) $raw_val)) {
                    update_option($opt_name, 'Desain & Perakitan Indonesia');
                    continue;
                }
                if ($opt_name === 'fds_about_stat3_num' && stripos((string) $raw_val, 'SNI') !== false && stripos((string) $raw_val, 'ISO') === false) {
                    update_option($opt_name, 'ISO & SNI');
                    continue;
                }
                if ($opt_name === 'fds_about_stat3_lbl' && stripos((string) $raw_val, 'SNI') !== false && stripos((string) $raw_val, 'FERTO 15') === false) {
                    update_option($opt_name, 'ISO 9001:2015 & SNI (FERTO 15 & 22)');
                    continue;
                }
                if ($opt_name === 'fds_about_cert2_val' && stripos((string) $raw_val, 'SNI') !== false && stripos((string) $raw_val, 'ISO') === false) {
                    update_option($opt_name, 'ISO & SNI');
                    continue;
                }
                if ($opt_name === 'fds_about_cert2_desc' && stripos((string) $raw_val, 'SNI') !== false && stripos((string) $raw_val, 'FERTO 15') === false) {
                    update_option($opt_name, 'Sertifikasi ISO 9001:2015 (Manajemen Mutu) dan SNI 9199:2023 pada FERTO 15 & FERTO 22.');
                    continue;
                }
                if ($opt_name === 'fds_menu_prod_item2_desc' && stripos((string) $raw_val, 'FERTO') === false) {
                    update_option($opt_name, 'Berlaku untuk seluruh seri FERTO');
                    continue;
                }
                if ($opt_name === 'fds_menu_prod_item3_desc' && stripos((string) $raw_val, 'FERTO 15') === false) {
                    update_option($opt_name, 'Teruji resmi pada FERTO 15 & FERTO 22');
                    continue;
                }
                if ($opt_name === 'fds_about_gallery_items' && is_string($raw_val) && stripos($raw_val, 'unsplash.com') !== false) {
                    delete_option($opt_name);
                    continue;
                }
                if ($opt_name === 'fds_hero_slides' && is_array($raw_val)) {
                    $encoded_hs = wp_json_encode($raw_val);
                    if ($encoded_hs && stripos($encoded_hs, 'unsplash.com') !== false) {
                        $site_uri = $theme_uri . '/public/images/site';
                        foreach ($raw_val as $idx => &$sl) {
                            if (isset($sl['img']) && stripos((string) $sl['img'], 'unsplash.com') !== false) {
                                $img_idx   = ($idx % 3) + 1;
                                $sl['img'] = "{$site_uri}/hero-{$img_idx}.webp";
                            }
                        }
                        unset($sl);
                    }
                }

                $serialized_check = is_string($raw_val) ? $raw_val : wp_json_encode($raw_val);
                if (!is_string($serialized_check) || !preg_match($stale_pattern, $serialized_check)) {
                    continue;
                }

                $changed = false;
                $updated = fds_migration_replace_stale_urls($raw_val, $theme_uri, $changed);
                $updated = fds_migration_scrub_recursive($updated, $changed);
                if ($changed) {
                    update_option($opt_name, $updated);
                }
            }
        }

        // 7. Synchronize ONLY CPT 'drone' posts (meta cleanup + model-specific field guide & synced FAQs)
        $drone_posts = get_posts([
            'post_type'      => 'drone',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        if (!empty($drone_posts)) {
            foreach ($drone_posts as $d) {
                $slug_lower = strtolower((string) $d->post_name);
                if (strpos($slug_lower, 'rebo') !== false || strpos($slug_lower, 'agrea') !== false) {
                    continue;
                }

                $model_key = function_exists(__NAMESPACE__ . '\\fds_get_drone_model_key')
                    ? fds_get_drone_model_key($d->ID)
                    : $slug_lower;
                $is_ferto  = (strpos($model_key, 'ferto-') === 0);
                $has_sni   = in_array($model_key, ['ferto-15l', 'ferto-22l'], true);

                // Normalize FERTO 15 capacity display ("17 Liter") per Decision A4
                if ($model_key === 'ferto-15l') {
                    $cap = (string) get_post_meta($d->ID, 'drone_spec_kapasitas', true);
                    if ($cap === '' || stripos($cap, '17') === false) {
                        update_post_meta($d->ID, 'drone_spec_kapasitas', '17 Liter (15 – 17 Liter)');
                    }
                }

                // Clean cutout & featured image meta from Unsplash / old DELFRO.png
                $cutout = (string) get_post_meta($d->ID, 'drone_img_cutout_url', true);
                if ($cutout === '' || stripos($cutout, 'unsplash.com') !== false || stripos($cutout, 'DELFRO.png') !== false) {
                    $local_cutout_rel = "/public/images/cutouts/{$model_key}.png";
                    if (file_exists(get_template_directory() . $local_cutout_rel)) {
                        update_post_meta($d->ID, 'drone_img_cutout_url', $theme_uri . $local_cutout_rel);
                    }
                }
                $feat_img = (string) get_post_meta($d->ID, 'drone_featured_img_url', true);
                if ($feat_img === '' || stripos($feat_img, 'unsplash.com') !== false || stripos($feat_img, 'DELFRO.png') !== false) {
                    $local_feat_rel = "/public/images/drones/{$model_key}.webp";
                    if (file_exists(get_template_directory() . $local_feat_rel)) {
                        update_post_meta($d->ID, 'drone_featured_img_url', $theme_uri . $local_feat_rel);
                    }
                }

                // Scrub unverified IP ratings & stale FDS STATION from drone specs
                foreach (['drone_spec_ketahanan', 'drone_spec_angin', 'drone_spec_gcs', 'drone_spec_sertifikasi', 'drone_tagline', 'drone_highlight'] as $mk) {
                    $mv = (string) get_post_meta($d->ID, $mk, true);
                    if ($mv === '') {
                        continue;
                    }
                    $new_mv = str_replace('FDS STATION', 'FDS Station', $mv);
                    $new_mv = (string) preg_replace('/\bIP\s?(54|67|68)\b(\s*\/\s*)?/i', '', $new_mv);
                    $new_mv = trim((string) preg_replace('/\s{2,}/', ' ', $new_mv), " \t\n\r\0\x0B/|");
                    if ($mk === 'drone_spec_ketahanan' && $new_mv === '') {
                        $new_mv = 'Rangka Karbon Komposit Modular';
                    }
                    if ($new_mv !== $mv) {
                        update_post_meta($d->ID, $mk, $new_mv);
                    }
                }

                // Enforce Decision A2 & A3 on drone_stat1/stat2 & drone_spec_sertifikasi
                $stat1_num = (string) get_post_meta($d->ID, 'drone_stat1_num', true);
                $stat1_lbl = (string) get_post_meta($d->ID, 'drone_stat1_lbl', true);
                $stat2_num = (string) get_post_meta($d->ID, 'drone_stat2_num', true);
                $stat2_lbl = (string) get_post_meta($d->ID, 'drone_stat2_lbl', true);
                $spec_cert = (string) get_post_meta($d->ID, 'drone_spec_sertifikasi', true);

                if (!$has_sni && (stripos($stat1_num, 'SNI') !== false || stripos($stat1_lbl, 'SNI') !== false)) {
                    $cap_map = [
                        'ferto-5l'     => ['5 L', 'Kapasitas Tangki'],
                        'ferto-10l'    => ['10 L', 'Kapasitas Tangki'],
                        'ferto-30l'    => ['30 L', 'Kapasitas Tangki'],
                        'ferto-50l'    => ['50 L', 'Kapasitas Tangki'],
                        'deltav'       => ['2.000mm', 'Bentang Sayap'],
                        'delfro'       => ['10 kg', 'Payload Maksimum'],
                        'multipurpose' => ['5 kg', 'Payload Maksimum'],
                    ];
                    if (isset($cap_map[$model_key])) {
                        update_post_meta($d->ID, 'drone_stat1_num', $cap_map[$model_key][0]);
                        update_post_meta($d->ID, 'drone_stat1_lbl', $cap_map[$model_key][1]);
                    }
                }

                if (!$is_ferto) {
                    if (stripos($stat2_num, '60,74') !== false || stripos($stat2_lbl, 'TKDN') !== false) {
                        $stat2_map = [
                            'deltav'       => ['60 km', 'Daya Jelajah'],
                            'delfro'       => ['Otonom', 'Navigasi Waypoint'],
                            'multipurpose' => ['30 min', 'Durasi Terbang Maks'],
                        ];
                        if (isset($stat2_map[$model_key])) {
                            update_post_meta($d->ID, 'drone_stat2_num', $stat2_map[$model_key][0]);
                            update_post_meta($d->ID, 'drone_stat2_lbl', $stat2_map[$model_key][1]);
                        }
                    }
                    update_post_meta($d->ID, 'drone_spec_sertifikasi', 'ISO 9001:2015');
                } elseif (!$has_sni && stripos($spec_cert, 'SNI') !== false) {
                    update_post_meta($d->ID, 'drone_spec_sertifikasi', 'TKDN + BMP 60,74% (Kemenperin RI) | ISO 9001:2015');
                }

                // Decide if Rank Math title/desc need refresh because of stale or inaccurate claims
                $rm_t = (string) get_post_meta($d->ID, 'rank_math_title', true);
                $rm_d = (string) get_post_meta($d->ID, 'rank_math_description', true);
                $rm_k = (string) get_post_meta($d->ID, 'rank_math_focus_keyword', true);

                $need_meta = ($rm_t === '' || $rm_d === '' || $rm_k === '');
                $check_rm  = $rm_t . ' ' . $rm_d;
                if (preg_match('/(Best UAV|sejak 2012|IP\s?(?:54|67|68)|FDS STATION|FLIR)/i', $check_rm)) {
                    $need_meta = true;
                }
                if (!$has_sni && stripos($check_rm, 'SNI') !== false) {
                    $need_meta = true;
                }
                if (!$is_ferto && stripos($check_rm, 'TKDN') !== false) {
                    $need_meta = true;
                }
                if ($model_key === 'ferto-15l' && stripos($rm_d, '17 Liter') === false) {
                    $need_meta = true;
                }

                if (function_exists(__NAMESPACE__ . '\\fds_sync_drone_rank_math_seo')) {
                    fds_sync_drone_rank_math_seo((int) $d->ID, true, $need_meta);
                }
            }
        }

        // 8. Invalidate caches, rebuild /llms.txt, flush rewrites & LiteSpeed
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%rank_math_sitemap%' OR option_name LIKE '%rm_sm_%'");

        $transients = [
            'fds_mega_menu_data',
            'fds_mega_menu_data_v2',
            'fds_bandingkan_catalog_cache',
            'fds_bandingkan_catalog_cache_v2',
            'fds_homepage_content_cache',
            'fds_navbar_brand_cache_v3',
            'fds_global_contact_cache_v2',
            'fds_llms_txt_cache_v2',
            'fds_llms_txt_cache_v3',
            'fds_llms_txt_cache_v4',
        ];
        foreach ($transients as $tk) {
            delete_transient($tk);
        }

        if (function_exists(__NAMESPACE__ . '\\fds_flush_layanan_cache')) {
            fds_flush_layanan_cache();
        }
        if (function_exists(__NAMESPACE__ . '\\fds_build_llms_txt_content')) {
            fds_build_llms_txt_content();
            update_option('fds_llms_version_disk', '4.0');
        }

        flush_rewrite_rules(false);

        if (has_action('litespeed_purge_all')) {
            do_action('litespeed_purge_all');
        }

        update_option('fds_migration_2026_11_v2', 1, false);
        delete_transient('fds_migration_2026_lock');
    }
}

add_action('init', function () {
    $force_manual = is_admin()
        && current_user_can('manage_options')
        && isset($_GET['fds_run_migration_2026'])
        && (string) $_GET['fds_run_migration_2026'] === '1';

    if (!get_option('fds_migration_2026_11_v2') || $force_manual) {
        fds_run_migration_2026_11();
    }
}, 40);

/**
 * Fallback safety routing so /produk/ always resolves to template-produk.blade.php
 */
add_filter('page_template', function ($template) {
    if (is_page('produk')) {
        $located = locate_template(['resources/views/template-produk.blade.php', 'resources/views/page-produk.blade.php']);
        if ($located) {
            return $located;
        }
    }
    return $template;
}, 1);
