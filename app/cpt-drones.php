<?php

/**
 * Custom Post Type & Taxonomy System: Drone & Kategori Drone
 * Theme: FDS Theme (PT Karya Solusi Angkasa)
 *
 * Fitur:
 * 1. CPT 'drone' ("Produk Drone") — Menggunakan Form Textfield Murni (Bukan Editor Artikel / Blog)
 * 2. Taksonomi 'kategori_drone' ("Kategori Drone") dengan 4 Kategori Resmi:
 *    - Agrikultur
 *    - Pemetaan & GIS
 *    - Kargo
 *    - Reboisasi
 * 3. Tampilan Edit WP Admin Premium: Full Textfield Form (Bebas dari Editor Post Berita)
 * 4. Auto-Seeder & Sinkronisasi Data compro.md
 */

namespace App;

// =========================================================================
// 1. NONAKTIFKAN BLOCK/GUTENBERG EDITOR UNTUK DRONE (Gunakan Form Textfield Murni)
// =========================================================================

add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) {
    if ($post_type === 'drone') {
        return false; // Matikan editor artikel agar menjadi form input murni
    }
    return $use_block_editor;
}, 10, 2);

// =========================================================================
// 2. REGISTRASI TAKSONOMI & CUSTOM POST TYPE
// =========================================================================

add_action('init', function () {
    // 2.1 Registrasi Taksonomi Resmi 'kategori_drone' (Tampil di bawah menu Produk Drone)
    $tax_labels = [
        'name'              => 'Kategori Drone',
        'singular_name'     => 'Kategori Drone',
        'search_items'      => 'Cari Kategori Drone',
        'all_items'         => 'Semua Kategori Drone',
        'parent_item'       => 'Kategori Induk',
        'parent_item_colon' => 'Kategori Induk:',
        'edit_item'         => 'Edit Kategori Drone',
        'update_item'       => 'Perbarui Kategori Drone',
        'add_new_item'      => 'Tambah Kategori Drone Baru',
        'new_item_name'     => 'Nama Kategori Drone Baru',
        'menu_name'         => 'Kategori Drone',
    ];

    register_taxonomy('kategori_drone', ['drone'], [
        'hierarchical'      => true,
        'labels'            => $tax_labels,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_nav_menus' => true,
        'show_in_rest'      => false,
        'rewrite'           => ['slug' => 'kategori-drone', 'with_front' => false],
    ]);

    // 2.2 Registrasi Custom Post Type 'drone' (HANYA title & thumbnail, tanpa editor artikel)
    $cpt_labels = [
        'name'               => 'Produk Drone',
        'singular_name'      => 'Drone',
        'menu_name'          => 'Produk Drone',
        'add_new'            => 'Tambah Drone Baru',
        'add_new_item'       => 'Tambah Drone Baru',
        'edit_item'          => 'Edit Spesifikasi Drone',
        'new_item'           => 'Drone Baru',
        'view_item'          => 'Lihat Drone di Website',
        'search_items'       => 'Cari Drone',
        'not_found'          => 'Tidak ada data drone ditemukan',
        'not_found_in_trash' => 'Tidak ada drone di tempat sampah',
    ];

    $cpt_args = [
        'labels'              => $cpt_labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-airplane',
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'supports'            => ['title', 'thumbnail'], // Hanya judul dan foto produk
        'taxonomies'          => ['kategori_drone'],
        'rewrite'             => ['slug' => 'drone', 'with_front' => false],
        'show_in_rest'        => false,
    ];

    register_post_type('drone', $cpt_args);

    // 2.3 Auto-insert 4 Kategori Resmi FDS jika belum ada di database
    $default_categories = [
        'agrikultur'   => [
            'name' => 'Agrikultur',
            'desc' => 'Pertanian Presisi FERTO 5L – 50L',
        ],
        'pemetaan-gis' => [
            'name' => 'Pemetaan & GIS',
            'desc' => 'Survei Geospasial & Inspeksi Aset',
        ],
        'kargo'        => [
            'name' => 'Kargo',
            'desc' => 'Distribusi Logistik Cepat 10 kg',
        ],
        'reboisasi'    => [
            'name' => 'Reboisasi',
            'desc' => 'Restorasi Hutan & Penabur Biji',
        ],
    ];

    foreach ($default_categories as $slug => $cdata) {
        $term = get_term_by('slug', $slug, 'kategori_drone');
        if (!$term) {
            wp_insert_term($cdata['name'], 'kategori_drone', [
                'slug'        => $slug,
                'description' => $cdata['desc'],
            ]);
        } else {
            wp_update_term($term->term_id, 'kategori_drone', [
                'name'        => $cdata['name'],
                'description' => $cdata['desc'],
            ]);
        }
    }
});

// Unregister taxonomy category, post_tag, dan kategori-drone lama dari object drone
add_action('init', function () {
    unregister_taxonomy_for_object_type('category', 'drone');
    unregister_taxonomy_for_object_type('post_tag', 'drone');
    unregister_taxonomy_for_object_type('kategori-drone', 'drone');
}, 99);

// Hapus menu 'Kategori' bawaan dari menu Produk Drone dan Pos, SISAKAN 'Kategori Drone'
add_action('admin_menu', function () {
    global $submenu;

    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
    remove_submenu_page('edit.php?post_type=drone', 'edit-tags.php?taxonomy=category&amp;post_type=drone');
    remove_submenu_page('edit.php?post_type=drone', 'edit-tags.php?taxonomy=category&post_type=drone');
    remove_submenu_page('edit.php?post_type=drone', 'edit-tags.php?taxonomy=category');

    if (isset($submenu['edit.php?post_type=drone'])) {
        foreach ($submenu['edit.php?post_type=drone'] as $k => $item) {
            if ($item[0] === 'Kategori' || (isset($item[2]) && strpos($item[2], 'taxonomy=category') !== false)) {
                unset($submenu['edit.php?post_type=drone'][$k]);
            }
        }
    }
}, 9999);

// Ubah placeholder judul saat input Drone
add_filter('enter_title_here', function ($title, $post) {
    if ($post->post_type === 'drone') {
        return 'Masukkan Nama / Model Drone (Contoh: FERTO 10)';
    }
    return $title;
}, 10, 2);

// Auto-insert page 'bandingkan' if not exists
add_action('init', function () {
    if (!get_page_by_path('bandingkan')) {
        wp_insert_post([
            'post_title'     => 'Bandingkan Model Drone',
            'post_name'      => 'bandingkan',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        ]);
    }
});

// Auto-route / redirect root slug /{slug} or legacy page to drone single if slug matches a drone post
add_action('template_redirect', function () {
    global $wp;
    $slug = trim($wp->request, '/');
    if ($slug === 'bandingkan-drone' || $slug === 'compare') {
        wp_safe_redirect(home_url('/bandingkan'), 301);
        exit;
    }
    if (!empty($slug) && $slug !== 'bandingkan') {
        $drone_posts = get_posts([
            'post_type'      => 'drone',
            'name'           => $slug,
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        ]);
        if (!empty($drone_posts) && (is_404() || is_page())) {
            wp_safe_redirect(get_permalink($drone_posts[0]->ID), 301);
            exit;
        }
    }
});

// =========================================================================
// 3. KOLOM ADMIN CUSTOM DI DAFTAR PRODUK DRONE
// =========================================================================

add_filter('manage_drone_posts_columns', function ($columns) {
    $new = [];
    if (isset($columns['cb'])) {
        $new['cb'] = $columns['cb'];
    }
    $new['drone_thumb']    = 'Foto';
    $new['title']          = 'Nama Drone';
    $new['drone_cat_tax']  = 'Kategori Drone';
    $new['drone_badge']    = 'Badge / Tipe';
    $new['drone_tagline']  = 'Tagline Ringkas';
    $new['drone_stats']    = 'Highlight Stats';
    if (isset($columns['date'])) {
        $new['date'] = $columns['date'];
    }
    return $new;
});

add_action('manage_drone_posts_custom_column', function ($column, $post_id) {
    switch ($column) {
        case 'drone_thumb':
            $thumb = get_the_post_thumbnail_url($post_id, [60, 60]);
            if ($thumb) {
                echo '<img src="' . esc_url($thumb) . '" style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #cbd5e1;">';
            } else {
                echo '<div style="width:48px;height:48px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:9px;border:1px dashed #cbd5e1;">No Foto</div>';
            }
            break;

        case 'drone_cat_tax':
            $terms = get_the_terms($post_id, 'kategori_drone');
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $t) {
                    $badge_colors = [
                        'Agrikultur'     => ['bg' => '#e0f2fe', 'color' => '#0369a1'],
                        'Pemetaan & GIS' => ['bg' => '#dcfce7', 'color' => '#15803d'],
                        'Kargo'          => ['bg' => '#fef3c7', 'color' => '#b45309'],
                        'Reboisasi'      => ['bg' => '#ecfdf5', 'color' => '#047857'],
                    ];
                    $c = $badge_colors[$t->name] ?? ['bg' => '#f3f4f6', 'color' => '#374151'];
                    echo '<span style="display:inline-block;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:' . esc_attr($c['bg']) . ';color:' . esc_attr($c['color']) . ';margin-right:4px;">' . esc_html($t->name) . '</span>';
                }
            } else {
                $meta_cat = get_post_meta($post_id, 'drone_kategori', true);
                if ($meta_cat) {
                    echo '<span style="display:inline-block;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#e0f2fe;color:#0369a1;">' . esc_html($meta_cat) . '</span>';
                } else {
                    echo '<span style="color:#aaa;font-size:12px;">-</span>';
                }
            }
            break;

        case 'drone_badge':
            $badge = get_post_meta($post_id, 'drone_badge', true);
            if ($badge) {
                echo '<span style="background:#f1f5f9;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;color:#334155;">' . esc_html($badge) . '</span>';
            } else {
                echo '<span style="color:#aaa;">-</span>';
            }
            break;

        case 'drone_tagline':
            $tagline = get_post_meta($post_id, 'drone_tagline', true);
            echo '<span style="color:#475569;font-size:12px;line-height:1.3;display:block;">' . esc_html(wp_trim_words($tagline, 8)) . '</span>';
            break;

        case 'drone_stats':
            $s1 = get_post_meta($post_id, 'drone_stat1_num', true);
            $s2 = get_post_meta($post_id, 'drone_stat2_num', true);
            $s3 = get_post_meta($post_id, 'drone_stat3_num', true);
            echo '<div style="font-size:11px;color:#64748b;line-height:1.4;">';
            if ($s1) echo '<strong>' . esc_html($s1) . '</strong> &bull; ';
            if ($s2) echo '<strong>' . esc_html($s2) . '</strong> &bull; ';
            if ($s3) echo '<strong>' . esc_html($s3) . '</strong>';
            echo '</div>';
            break;
    }
}, 10, 2);

// =========================================================================
// 4. FORM TEXTFIELD MURNI DRONE (FDS DRONE DATA FORM)
// =========================================================================

// Enqueue WordPress Media Uploader untuk halaman Tambah / Edit Drone
add_action('admin_enqueue_scripts', function ($hook) {
    global $post_type;
    if ($post_type === 'drone') {
        wp_enqueue_media();
    }
});

add_action('add_meta_boxes', function () {
    add_meta_box(
        'fds_drone_fields_panel',
        '✈️ Form Data & Spesifikasi Produk Drone (FDS Form Panel)',
        'App\render_drone_pure_form_metabox',
        'drone',
        'normal',
        'high'
    );
});

function render_drone_pure_form_metabox($post) {
    wp_nonce_field('fds_drone_form_save', 'fds_drone_form_nonce');

    // Ambil data tersimpan
    $kategori           = get_post_meta($post->ID, 'drone_kategori', true) ?: 'Agrikultur';
    $badge              = get_post_meta($post->ID, 'drone_badge', true);
    $tagline            = get_post_meta($post->ID, 'drone_tagline', true);
    $desc               = get_post_meta($post->ID, 'drone_desc', true) ?: $post->post_content;
    $brosur_url         = get_post_meta($post->ID, 'drone_brosur_url', true);
    $video_url          = get_post_meta($post->ID, 'drone_video_url', true);
    $specs_img_url      = get_post_meta($post->ID, 'drone_specs_img', true);

    // 4 Key Stats Bar
    $stat1_num          = get_post_meta($post->ID, 'drone_stat1_num', true) ?: 'SNI';
    $stat1_lbl          = get_post_meta($post->ID, 'drone_stat1_lbl', true) ?: 'SNI 9199:2023';
    $stat2_num          = get_post_meta($post->ID, 'drone_stat2_num', true) ?: '60,74%';
    $stat2_lbl          = get_post_meta($post->ID, 'drone_stat2_lbl', true) ?: 'TKDN + BMP Resmi';
    $stat3_num          = get_post_meta($post->ID, 'drone_stat3_num', true);
    $stat3_lbl          = get_post_meta($post->ID, 'drone_stat3_lbl', true);
    $stat4_num          = get_post_meta($post->ID, 'drone_stat4_num', true) ?: 'Garansi';
    $stat4_lbl          = get_post_meta($post->ID, 'drone_stat4_lbl', true) ?: 'Purna Jual Resmi';

    // Spesifikasi Teknis Terstruktur (Textfields)
    $spec_kapasitas     = get_post_meta($post->ID, 'drone_spec_kapasitas', true);
    $spec_durasi        = get_post_meta($post->ID, 'drone_spec_durasi', true);
    $spec_baterai       = get_post_meta($post->ID, 'drone_spec_baterai', true);
    $spec_produktivitas = get_post_meta($post->ID, 'drone_spec_produktivitas', true);
    $spec_kecepatan     = get_post_meta($post->ID, 'drone_spec_kecepatan', true);
    $spec_ketahanan     = get_post_meta($post->ID, 'drone_spec_ketahanan', true);
    $spec_otonomi       = get_post_meta($post->ID, 'drone_spec_otonomi', true);
    $spec_gcs           = get_post_meta($post->ID, 'drone_spec_gcs', true) ?: 'FDS STATION (Bahasa Indonesia)';
    $spec_sertifikasi   = get_post_meta($post->ID, 'drone_spec_sertifikasi', true) ?: 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015';

    // 4 Kasus Penggunaan (Use Cases)
    $uc1_t              = get_post_meta($post->ID, 'drone_uc1_t', true);
    $uc1_d              = get_post_meta($post->ID, 'drone_uc1_d', true);
    $uc2_t              = get_post_meta($post->ID, 'drone_uc2_t', true);
    $uc2_d              = get_post_meta($post->ID, 'drone_uc2_d', true);
    $uc3_t              = get_post_meta($post->ID, 'drone_uc3_t', true);
    $uc3_d              = get_post_meta($post->ID, 'drone_uc3_d', true);
    $uc4_t              = get_post_meta($post->ID, 'drone_uc4_t', true);
    $uc4_d              = get_post_meta($post->ID, 'drone_uc4_d', true);
    ?>

    <style>
        .fds-section-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .fds-section-title { font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 16px 0; padding-bottom: 8px; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.03em; }
        
        .fds-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 14px; }
        .fds-row-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 14px; }
        
        .fds-field { margin-bottom: 14px; }
        .fds-field label { display: block; font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 6px; }
        .fds-field input[type="text"], .fds-field input[type="url"], .fds-field select, .fds-field textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 13px;
            color: #1e293b;
            background: #ffffff;
            box-sizing: border-box;
        }
        .fds-field input:focus, .fds-field select:focus, .fds-field textarea:focus {
            border-color: #0066cc;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,102,204,0.15);
        }
        .fds-field .fds-subtext { font-size: 11px; color: #64748b; margin-top: 4px; display: block; }
        
        .fds-stat-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; }
        .fds-stat-card label { font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px; }
        .fds-stat-card input { width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px; margin-bottom: 6px; box-sizing: border-box; }
        
        .fds-uc-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; margin-bottom: 12px; }
        .fds-uc-card h5 { margin: 0 0 8px 0; font-size: 12px; font-weight: 700; color: #0066cc; }
    </style>

    <!-- 1. INFORMASI UTAMA & RINGKASAN PRODUK -->
    <div class="fds-section-card">
        <div class="fds-section-title">📌 1. Informasi Utama Produk Drone</div>
        <p style="font-size: 12px; color: #64748b; margin: 0 0 16px 0;">
            <em>Tip: Untuk memilih kategori drone (Agrikultur, Kargo, Pemetaan &amp; GIS, Reboisasi), cukup centang checklist pada kotak <strong>"Kategori Drone"</strong> di bilah kanan (sidebar).</em>
        </p>

        <div class="fds-field">
            <label for="drone_badge">Badge / Label Tipe</label>
            <input type="text" id="drone_badge" name="drone_badge" value="<?php echo esc_attr($badge); ?>" placeholder="Contoh: Terlaris / Enterprise / Hybrid VTOL / Modular UAV">
            <span class="fds-subtext">Label badge kecil berwarna biru di atas judul drone.</span>
        </div>

        <div class="fds-field">
            <label for="drone_tagline">Tagline / Subtitle Resmi</label>
            <input type="text" id="drone_tagline" name="drone_tagline" value="<?php echo esc_attr($tagline); ?>" placeholder="Contoh: Drone Pertanian FERTO 10 — Pilihan terbaik kelompok tani dengan produktivitas andal.">
            <span class="fds-subtext">Kalimat headline ringkas yang tampil di header halaman detail dan kartu beranda.</span>
        </div>

        <div class="fds-field">
            <label for="drone_desc">Deskripsi Lengkap Produk Drone</label>
            <textarea id="drone_desc" name="drone_desc" rows="4" placeholder="Tuliskan penjelasan lengkap mengenai performa, keunggulan, material rangka, dan kemampuan operasional drone..."><?php echo esc_textarea($desc); ?></textarea>
            <span class="fds-subtext">Paragraf pengantar resmi produk pada halaman spesifikasi detail.</span>
        </div>

        <div class="fds-row-2">
            <div class="fds-field">
                <label for="drone_brosur_url">Tautan Unduh Brosur PDF (Opsional)</label>
                <input type="text" id="drone_brosur_url" name="drone_brosur_url" value="<?php echo esc_attr($brosur_url); ?>" placeholder="https://... atau #kontak">
            </div>
            <div class="fds-field">
                <label for="drone_video_url">Tautan Video Demo YouTube (Opsional)</label>
                <input type="text" id="drone_video_url" name="drone_video_url" value="<?php echo esc_attr($video_url); ?>" placeholder="https://youtube.com/watch?v=...">
            </div>
        </div>
    </div>

    <!-- 2. HIGHLIGHT 4 KARTU STATS -->
    <div class="fds-section-card">
        <div class="fds-section-title">📊 2. Highlight 4 Kartu Statistik (Stats Bar)</div>
        <p style="font-size:12px;color:#64748b;margin-bottom:14px;">4 Kartu angka besar yang tampil di bar statistik atas halaman detail produk:</p>
        
        <div class="fds-row-4">
            <div class="fds-stat-card">
                <label>Kartu 1 (Sertifikasi SNI)</label>
                <input type="text" name="drone_stat1_num" value="<?php echo esc_attr($stat1_num); ?>" placeholder="Angka: SNI">
                <input type="text" name="drone_stat1_lbl" value="<?php echo esc_attr($stat1_lbl); ?>" placeholder="Label: SNI 9199:2023">
            </div>
            <div class="fds-stat-card">
                <label>Kartu 2 (TKDN / Sertifikasi)</label>
                <input type="text" name="drone_stat2_num" value="<?php echo esc_attr($stat2_num); ?>" placeholder="Angka: 60,74%">
                <input type="text" name="drone_stat2_lbl" value="<?php echo esc_attr($stat2_lbl); ?>" placeholder="Label: TKDN + BMP">
            </div>
            <div class="fds-stat-card">
                <label>Kartu 3 (Produktivitas / Jangkauan)</label>
                <input type="text" name="drone_stat3_num" value="<?php echo esc_attr($stat3_num); ?>" placeholder="Angka: 8 Ha/j atau 60 km">
                <input type="text" name="drone_stat3_lbl" value="<?php echo esc_attr($stat3_lbl); ?>" placeholder="Label: Produktivitas Semprot">
            </div>
            <div class="fds-stat-card">
                <label>Kartu 4 (Garansi / Servis)</label>
                <input type="text" name="drone_stat4_num" value="<?php echo esc_attr($stat4_num); ?>" placeholder="Angka: Garansi">
                <input type="text" name="drone_stat4_lbl" value="<?php echo esc_attr($stat4_lbl); ?>" placeholder="Label: Purna Jual Resmi">
            </div>
        </div>
    </div>

    <!-- 3. SPESIFIKASI TEKNIS DETAIL (TEXTFIELDS) -->
    <div class="fds-section-card">
        <div class="fds-section-title">⚙️ 3. Spesifikasi Teknis Terstruktur (Tabel Specs)</div>
        
        <!-- Foto Spesifikasi Drone (PNG Transparan / Spek) -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 18px;">
            <label style="font-size: 13px; font-weight: 700; color: #0f172a; display: block; margin-bottom: 4px;">
                📐 Foto Spesifikasi Produk (PNG Transparan / Detail Spek)
            </label>
            <span class="fds-subtext" style="margin-bottom: 12px;">Foto drone (format PNG transparan dianjurkan) yang tampil di samping kiri tabel spesifikasi teknis.</span>
            
            <div style="display: flex; gap: 16px; align-items: center;">
                <div style="width: 140px; height: 100px; border-radius: 6px; overflow: hidden; background: #ffffff; display: flex; align-items: center; justify-content: center; border: 1px solid #cbd5e1; flex-shrink: 0;">
                    <img id="fds_specs_img_preview" src="<?php echo esc_url($specs_img_url ?: 'https://placehold.co/400x300/f8fafc/94a3b8?text=Foto+Spesifikasi+(PNG)'); ?>" style="max-width: 90%; max-height: 90%; object-fit: contain;">
                </div>
                <div style="flex: 1;">
                    <input type="hidden" id="fds_specs_img_url" name="drone_specs_img" value="<?php echo esc_attr($specs_img_url); ?>">
                    <div style="display: flex; gap: 8px; margin-bottom: 6px;">
                        <button type="button" id="fds_upload_specs_btn" class="button button-secondary" style="font-size: 12px; font-weight: 600; height: 32px;">
                            📷 Pilih / Upload Foto Spek
                        </button>
                        <button type="button" id="fds_remove_specs_btn" class="button button-link" style="color: #dc2626; font-size: 12px;">
                            Hapus Gambar
                        </button>
                    </div>
                    <span class="fds-subtext">Jika dikosongkan, halaman akan otomatis menggunakan Featured Image atau ilustrasi standar drone.</span>
                </div>
            </div>
        </div>

        <div class="fds-row-2">
            <div class="fds-field">
                <label>Kapasitas Muatan / Tangki</label>
                <input type="text" name="drone_spec_kapasitas" value="<?php echo esc_attr($spec_kapasitas); ?>" placeholder="Contoh: 10 Liter / Bentang Sayap 2.000 mm / Muatan 10 kg">
            </div>
            <div class="fds-field">
                <label>Durasi Terbang (Endurance)</label>
                <input type="text" name="drone_spec_durasi" value="<?php echo esc_attr($spec_durasi); ?>" placeholder="Contoh: 12 – 15 menit / 60 – 120 menit">
            </div>
        </div>

        <div class="fds-row-2">
            <div class="fds-field">
                <label>Sistem Daya (Baterai)</label>
                <input type="text" name="drone_spec_baterai" value="<?php echo esc_attr($spec_baterai); ?>" placeholder="Contoh: 16.000 mAh / 22.000 mAh High Density">
            </div>
            <div class="fds-field">
                <label>Produktivitas Semprot / Jangkauan Misi</label>
                <input type="text" name="drone_spec_produktivitas" value="<?php echo esc_attr($spec_produktivitas); ?>" placeholder="Contoh: 1 – 1,5 Ha / jam atau Jangkauan 60 km">
            </div>
        </div>

        <div class="fds-row-2">
            <div class="fds-field">
                <label>Kecepatan Jelajah (Cruise Speed)</label>
                <input type="text" name="drone_spec_kecepatan" value="<?php echo esc_attr($spec_kecepatan); ?>" placeholder="Contoh: 2 – 6 m/s atau 15 – 22 m/s">
            </div>
            <div class="fds-field">
                <label>Ketahanan Lingkungan / Cuaca (opsional)</label>
                <input type="text" name="drone_spec_ketahanan" value="<?php echo esc_attr($spec_ketahanan); ?>" placeholder="Opsional, kosongkan jika tidak ada">
            </div>
        </div>

        <div class="fds-row-2">
            <div class="fds-field">
                <label>Sistem Otonomi & Navigasi</label>
                <input type="text" name="drone_spec_otonomi" value="<?php echo esc_attr($spec_otonomi); ?>" placeholder="Contoh: Semi-to-Fully Autonomous, Terrain Following, Fail-Safe">
            </div>
            <div class="fds-field">
                <label>Ground Control Station (GCS)</label>
                <input type="text" name="drone_spec_gcs" value="<?php echo esc_attr($spec_gcs); ?>" placeholder="Contoh: FDS STATION (Bahasa Indonesia)">
            </div>
        </div>

        <div class="fds-field">
            <label>Sertifikasi & Standar Resmi</label>
            <input type="text" name="drone_spec_sertifikasi" value="<?php echo esc_attr($spec_sertifikasi); ?>" placeholder="Contoh: TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015">
        </div>
    </div>

    <!-- 4. KASUS PENGGUNAAN & TARGET PENGGUNA -->
    <div class="fds-section-card">
        <div class="fds-section-title">🎯 4. Target Pengguna & Kasus Penggunaan (Use Cases)</div>
        
        <div class="fds-uc-card">
            <h5>Target 1</h5>
            <div class="fds-field" style="margin-bottom:6px;">
                <input type="text" name="drone_uc1_t" value="<?php echo esc_attr($uc1_t); ?>" placeholder="Judul Sasaran (Contoh: Kelompok tani & Gapoktan)">
            </div>
            <div class="fds-field" style="margin-bottom:0;">
                <input type="text" name="drone_uc1_d" value="<?php echo esc_attr($uc1_d); ?>" placeholder="Keterangan (Contoh: Titik temu terbaik antara kapasitas operasional dan efisiensi investasi.)">
            </div>
        </div>

        <div class="fds-uc-card">
            <h5>Target 2</h5>
            <div class="fds-field" style="margin-bottom:6px;">
                <input type="text" name="drone_uc2_t" value="<?php echo esc_attr($uc2_t); ?>" placeholder="Judul Sasaran (Contoh: Perkebunan komersial & BUMN)">
            </div>
            <div class="fds-field" style="margin-bottom:0;">
                <input type="text" name="drone_uc2_d" value="<?php echo esc_attr($uc2_d); ?>" placeholder="Keterangan (Contoh: Mengurangi beban biaya tenaga kerja semprot manual hingga 60%.)">
            </div>
        </div>

        <div class="fds-uc-card">
            <h5>Target 3</h5>
            <div class="fds-field" style="margin-bottom:6px;">
                <input type="text" name="drone_uc3_t" value="<?php echo esc_attr($uc3_t); ?>" placeholder="Judul Sasaran (Contoh: Kontraktor jasa aplikasi presisi)">
            </div>
            <div class="fds-field" style="margin-bottom:0;">
                <input type="text" name="drone_uc3_d" value="<?php echo esc_attr($uc3_d); ?>" placeholder="Keterangan (Contoh: Durasi terbang hingga 25 menit untuk ritme kerja lapangan padat.)">
            </div>
        </div>

        <div class="fds-uc-card">
            <h5>Target 4</h5>
            <div class="fds-field" style="margin-bottom:6px;">
                <input type="text" name="drone_uc4_t" value="<?php echo esc_attr($uc4_t); ?>" placeholder="Judul Sasaran (Contoh: Dukungan purna jual resmi)">
            </div>
            <div class="fds-field" style="margin-bottom:0;">
                <input type="text" name="drone_uc4_d" value="<?php echo esc_attr($uc4_d); ?>" placeholder="Keterangan (Contoh: Ketersediaan suku cadang asli dari workshop Yogyakarta.)">
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Media Uploader untuk Foto Spesifikasi Drone (PNG)
        $('#fds_upload_specs_btn').on('click', function(e) {
            e.preventDefault();
            var specsUploader = wp.media({
                title: 'Pilih atau Unggah Foto Spesifikasi Drone (PNG Transparan)',
                button: { text: 'Gunakan Foto Ini' },
                multiple: false
            }).on('select', function() {
                var attachment = specsUploader.state().get('selection').first().toJSON();
                $('#fds_specs_img_url').val(attachment.url);
                $('#fds_specs_img_preview').attr('src', attachment.url);
            }).open();
        });

        $('#fds_remove_specs_btn').on('click', function(e) {
            e.preventDefault();
            $('#fds_specs_img_url').val('');
            $('#fds_specs_img_preview').attr('src', 'https://placehold.co/400x300/f8fafc/94a3b8?text=Foto+Spesifikasi+(PNG)');
        });
    });
    </script>
    <?php
}

// =========================================================================
// 5. SIMPAN DATA TEXTFIELD DI WP ADMIN
// =========================================================================

add_action('save_post_drone', function ($post_id) {
    static $is_saving = false;
    if ($is_saving) return;

    if (!isset($_POST['fds_drone_form_nonce']) || !wp_verify_nonce($_POST['fds_drone_form_nonce'], 'fds_drone_form_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $is_saving = true;

    // Simpan Foto Spesifikasi Drone
    if (isset($_POST['drone_specs_img'])) {
        update_post_meta($post_id, 'drone_specs_img', sanitize_text_field($_POST['drone_specs_img']));
    }

    $text_fields = [
        'drone_kategori', 'drone_badge', 'drone_tagline', 'drone_brosur_url', 'drone_video_url',
        'drone_stat1_num', 'drone_stat1_lbl',
        'drone_stat2_num', 'drone_stat2_lbl',
        'drone_stat3_num', 'drone_stat3_lbl',
        'drone_stat4_num', 'drone_stat4_lbl',
        'drone_spec_kapasitas', 'drone_spec_durasi', 'drone_spec_baterai',
        'drone_spec_produktivitas', 'drone_spec_kecepatan', 'drone_spec_ketahanan',
        'drone_spec_otonomi', 'drone_spec_gcs', 'drone_spec_sertifikasi',
        'drone_uc1_t', 'drone_uc1_d',
        'drone_uc2_t', 'drone_uc2_d',
        'drone_uc3_t', 'drone_uc3_d',
        'drone_uc4_t', 'drone_uc4_d',
    ];

    foreach ($text_fields as $f) {
        if (isset($_POST[$f])) {
            update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
        }
    }

    if (isset($_POST['drone_desc'])) {
        $clean_desc = sanitize_textarea_field($_POST['drone_desc']);
        update_post_meta($post_id, 'drone_desc', $clean_desc);
        
        // Simpan juga ke post_content (menggunakan static $is_saving agar tidak terjadi infinite recursion)
        $current_post = get_post($post_id);
        if ($current_post && $current_post->post_content !== $clean_desc) {
            wp_update_post([
                'ID'           => $post_id,
                'post_content' => $clean_desc,
            ]);
        }
    }

    // Reconstruct string tabel spesifikasi & usecase otomatis
    $sp_kap  = sanitize_text_field($_POST['drone_spec_kapasitas'] ?? '');
    $sp_dur  = sanitize_text_field($_POST['drone_spec_durasi'] ?? '');
    $sp_bat  = sanitize_text_field($_POST['drone_spec_baterai'] ?? '');
    $sp_prod = sanitize_text_field($_POST['drone_spec_produktivitas'] ?? '');
    $sp_kec  = sanitize_text_field($_POST['drone_spec_kecepatan'] ?? '');
    $sp_ket  = sanitize_text_field($_POST['drone_spec_ketahanan'] ?? '');
    $sp_oto  = sanitize_text_field($_POST['drone_spec_otonomi'] ?? '');
    $sp_gcs  = sanitize_text_field($_POST['drone_spec_gcs'] ?? '');
    $sp_sert = sanitize_text_field($_POST['drone_spec_sertifikasi'] ?? '');

    $specs_arr = [];
    if ($sp_kap)  $specs_arr[] = "Kapasitas Tangki / Payload: $sp_kap";
    if ($sp_dur)  $specs_arr[] = "Durasi Terbang: $sp_dur";
    if ($sp_bat)  $specs_arr[] = "Sistem Daya (Baterai): $sp_bat";
    if ($sp_prod) $specs_arr[] = "Produktivitas / Jangkauan: $sp_prod";
    if ($sp_kec)  $specs_arr[] = "Kecepatan Jelajah: $sp_kec";
    if ($sp_ket)  $specs_arr[] = "Ketahanan Lingkungan: $sp_ket";
    if ($sp_oto)  $specs_arr[] = "Sistem Otonomi & Navigasi: $sp_oto";
    if ($sp_gcs)  $specs_arr[] = "Ground Control Station: $sp_gcs";
    if ($sp_sert) $specs_arr[] = "Sertifikasi & Standar: $sp_sert";

    update_post_meta($post_id, 'drone_specs_raw', implode("\n", $specs_arr));

    $uc_arr = [];
    for ($i = 1; $i <= 4; $i++) {
        $t = sanitize_text_field($_POST["drone_uc{$i}_t"] ?? '');
        $d = sanitize_text_field($_POST["drone_uc{$i}_d"] ?? '');
        if ($t) {
            $uc_arr[] = $d ? "$t — $d" : $t;
        }
    }
    update_post_meta($post_id, 'drone_for', implode("\n", $uc_arr));

    // Sinkronisasi kategori dari taksonomi checklist sidebar ke post meta 'drone_kategori'
    $terms = wp_get_post_terms($post_id, 'kategori_drone');
    if (!empty($terms) && !is_wp_error($terms)) {
        update_post_meta($post_id, 'drone_kategori', $terms[0]->name);
    }

    $is_saving = false;
});

// =========================================================================
// 6. AUTO-SEEDER DATA COMPRO.MD (One-time Initializer)
// =========================================================================

add_action('init', function () {
    if (get_option('fds_drones_seeded_final_v1')) {
        return;
    }
    $existing = get_posts(['post_type' => 'drone', 'numberposts' => -1, 'post_status' => 'any']);

    $seeds = [
        'ferto-5l' => [
            'title'     => 'FERTO 5 (5 Liter)',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Kompak & Lincah',
            'tagline'   => 'Drone Pertanian FERTO 5 — Platform UAV Agrikultur modular dengan mobilitas tinggi.',
            'content'   => 'FERTO 5 didesain sebagai platform multirotor modular dengan mobilitas tinggi untuk menjangkau area berbukit, terasering, dan lahan perkebunan dengan kontur ekstrem. Dilengkapi fitur terrain-following otomatis dan sistem kendali FDS STATION, drone ini menjamin presisi penyemprotan pupuk cair maupun pestisida secara merata dengan produktivitas 1 Ha per jam.',
            'stat1_n'   => 'SNI', 'stat1_l' => 'SNI 9199:2023',
            'stat2_n'   => '60,74%', 'stat2_l' => 'TKDN + BMP',
            'stat3_n'   => '1 Ha/j', 'stat3_l' => 'Produktivitas Semprot',
            'stat4_n'   => 'Garansi', 'stat4_l' => 'Purna Jual Resmi',
            'sp_kap'    => '5 Liter',
            'sp_dur'    => '10 – 15 menit',
            'sp_bat'    => '8.000 mAh',
            'sp_prod'   => '1 Ha / jam',
            'sp_kec'    => '2 – 6 m/s',
            'sp_ket'    => '',
            'sp_oto'    => 'Otonom & Manual, Terrain Following, Fail-Safe',
            'sp_gcs'    => 'FDS STATION (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015',
            'uc1_t'     => 'Lahan berbukit & terasering', 'uc1_d' => 'Bobot ringan dan dimensi ringkas mempermudah manuver di area sempit.',
            'uc2_t'     => 'Petani hortikultura & kebun', 'uc2_d' => 'Efisiensi bahan kimia >50% dengan penyemprotan droplet presisi.',
            'uc3_t'     => 'Penyedia jasa semprot mandiri', 'uc3_d' => 'Mobilitas tinggi mudah dibawa dengan sepeda motor ke pelosok sawah.',
            'uc4_t'     => 'Dukungan purna jual resmi', 'uc4_d' => 'Jaringan servis dan suku cadang asli lokal FDS siap pakai.',
        ],
        'ferto-10l' => [
            'title'     => 'FERTO 10 (10 Liter)',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Terlaris',
            'tagline'   => 'Drone Pertanian FERTO 10 — Pilihan terbaik kelompok tani dengan produktivitas andal.',
            'content'   => 'FERTO 10 adalah varian terlaris FDS yang menawarkan keseimbangan optimal antara kapasitas muatan 10 liter, ketahanan baterai 16.000 mAh, dan produktivitas 1 - 1,5 Ha/jam. Menggunakan rangka karbon komposit buatan dalam negeri berstandar SNI 9199:2023, drone ini menjadi tulang punggung modernisasi pertanian di berbagai wilayah Indonesia.',
            'stat1_n'   => 'SNI', 'stat1_l' => 'SNI 9199:2023',
            'stat2_n'   => '60,74%', 'stat2_l' => 'TKDN + BMP',
            'stat3_n'   => '1,5 Ha/j', 'stat3_l' => 'Produktivitas Semprot',
            'stat4_n'   => 'Garansi', 'stat4_l' => 'Purna Jual Resmi',
            'sp_kap'    => '10 Liter',
            'sp_dur'    => '12 – 15 menit',
            'sp_bat'    => '16.000 mAh',
            'sp_prod'   => '1 – 1,5 Ha / jam',
            'sp_kec'    => '2 – 6 m/s',
            'sp_ket'    => '',
            'sp_oto'    => 'Otonom & Manual, Terrain Following, Fail-Safe',
            'sp_gcs'    => 'FDS STATION (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015',
            'uc1_t'     => 'Kelompok tani & Gapoktan', 'uc1_d' => 'Titik temu terbaik antara kapasitas operasional dan efisiensi investasi.',
            'uc2_t'     => 'Koperasi pertanian', 'uc2_d' => 'Mengurangi beban biaya tenaga kerja semprot manual hingga 60%.',
            'uc3_t'     => 'Program ketahanan pangan Bappenas & BI', 'uc3_d' => 'Terbukti andal di berbagai proyek percontohan nasional.',
            'uc4_t'     => 'Suku cadang asli terjamin', 'uc4_d' => 'Ketersediaan komponen cepat dari workshop Yogyakarta.',
        ],
        'ferto-15l' => [
            'title'     => 'FERTO 15 (17 Liter)',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Profesional',
            'tagline'   => 'Drone Pertanian FERTO 15 — Kapasitas 17 Liter dengan produktivitas tinggi 8 Ha/jam.',
            'content'   => 'FERTO 15 menghadirkan kapasitas tangki 17 Liter dengan efisiensi tinggi, mampu menyelesaikan penyemprotan hingga 8 hektare per jam. Dilengkapi sistem propulsi berdaya tahan 15-25 menit dan radar terrain-following presisi, drone ini sangat cocok untuk operasional komersial menengah ke atas pada komoditas tebu, jagung, dan hortikultura luas.',
            'stat1_n'   => 'SNI', 'stat1_l' => 'SNI 9199:2023',
            'stat2_n'   => '60,74%', 'stat2_l' => 'TKDN + BMP',
            'stat3_n'   => '8 Ha/j', 'stat3_l' => 'Produktivitas Semprot',
            'stat4_n'   => 'Garansi', 'stat4_l' => 'Purna Jual Resmi',
            'sp_kap'    => '17 Liter (15 – 17 Liter)',
            'sp_dur'    => '15 – 25 menit',
            'sp_bat'    => '16.000 mAh',
            'sp_prod'   => '8 Ha / jam',
            'sp_kec'    => '2 – 6 m/s',
            'sp_ket'    => '',
            'sp_oto'    => 'Otonom & Manual, Terrain Following, Fail-Safe',
            'sp_gcs'    => 'FDS STATION (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015',
            'uc1_t'     => 'Perkebunan tebu & jagung komersial', 'uc1_d' => 'Menyemprot cepat 8 Ha/jam dengan cakupan droplet merata.',
            'uc2_t'     => 'Kontraktor jasa perlindungan tanaman', 'uc2_d' => 'Durasi terbang hingga 25 menit untuk ritme kerja lapangan padat.',
            'uc3_t'     => 'Dual mode Sprayer & Granule Spreader', 'uc3_d' => 'Kompatibel dengan tangki granule spreader untuk pemupukan butir.',
            'uc4_t'     => 'Dukungan teknis pilot bersertifikat', 'uc4_d' => 'Layanan pendampingan dan pelatihan pilot resmi FDS.',
        ],
        'ferto-22l' => [
            'title'     => 'FERTO 22 (22 Liter)',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Enterprise',
            'tagline'   => 'Drone Pertanian FERTO 22 — Kapasitas enterprise 22L untuk perkebunan skala besar.',
            'content'   => 'FERTO 22 adalah varian enterprise andalan FDS untuk industri perkebunan sawit, tebu, dan tanaman industri berskala ribuan hektare. Ditenagai baterai 22.000 mAh dengan kecepatan jelajah 5,24 m/s, drone ini mampu menuntaskan 8,5 hektare per jam secara otonom dan terintegrasi penuh ke dalam sistem FDS STATION.',
            'stat1_n'   => 'SNI', 'stat1_l' => 'SNI 9199:2023',
            'stat2_n'   => '60,74%', 'stat2_l' => 'TKDN + BMP',
            'stat3_n'   => '8,5 Ha/j', 'stat3_l' => 'Produktivitas Semprot',
            'stat4_n'   => 'Garansi', 'stat4_l' => 'Purna Jual Resmi',
            'sp_kap'    => '22 Liter',
            'sp_dur'    => '20 – 25 menit',
            'sp_bat'    => '22.000 mAh',
            'sp_prod'   => '8,5 Ha / jam',
            'sp_kec'    => '5,24 m/s',
            'sp_ket'    => '',
            'sp_oto'    => 'Semi-to-Fully Autonomous, Terrain Following, Fail-Safe',
            'sp_gcs'    => 'FDS STATION (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015',
            'uc1_t'     => 'Perkebunan sawit & tanaman industri', 'uc1_d' => 'Mengatasi tantangan lahan luas dengan kecepatan semprot tinggi.',
            'uc2_t'     => 'BUMN Perkebunan & Korporasi Agrikultur', 'uc2_d' => 'Memenuhi syarat pengadaan pemerintah dengan TKDN+BMP resmi.',
            'uc3_t'     => 'Manajemen armada perkebunan', 'uc3_d' => 'Terintegrasi dengan analitik kesehatan tanaman berbasis NDVI.',
            'uc4_t'     => 'Purna jual resmi & garansi lokal', 'uc4_d' => 'Layanan servis dan suku cadang asli tanpa ketergantungan impor.',
        ],
        'ferto-30l' => [
            'title'     => 'FERTO 30 (30 Liter)',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Heavy Duty',
            'tagline'   => 'Drone Pertanian FERTO 30 — Kapasitas muat masif 30L dengan produktivitas 15 Ha/jam.',
            'content'   => 'FERTO 30 menghadirkan lompatan kapasitas tangki 30 Liter yang dirancang untuk kebutuhan agribisnis skala masif. Dengan sistem baterai high-capacity 28.000 mAh dan daya jangkau penerbangan hingga 30 menit, drone ini mampu menghasilkan produktivitas 15 Ha per jam, memangkas waktu kerja dan biaya operasional secara drastis.',
            'stat1_n'   => 'SNI', 'stat1_l' => 'SNI 9199:2023',
            'stat2_n'   => '60,74%', 'stat2_l' => 'TKDN + BMP',
            'stat3_n'   => '15 Ha/j', 'stat3_l' => 'Produktivitas Semprot',
            'stat4_n'   => 'Garansi', 'stat4_l' => 'Purna Jual Resmi',
            'sp_kap'    => '30 Liter',
            'sp_dur'    => '20 – 30 menit',
            'sp_bat'    => '28.000 mAh',
            'sp_prod'   => '15 Ha / jam',
            'sp_kec'    => '5,24 m/s',
            'sp_ket'    => '',
            'sp_oto'    => 'Semi-to-Fully Autonomous, Terrain Following, Fail-Safe',
            'sp_gcs'    => 'FDS STATION (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015',
            'uc1_t'     => 'Mega perkebunan sawit & tebu', 'uc1_d' => 'Produktivitas 15 Ha/jam mempercepat target penyemprotan harian.',
            'uc2_t'     => 'Aplikasi pupuk & pestisida volume tinggi', 'uc2_d' => 'Tangki 30L meminimalkan frekuensi pendaratan untuk isi ulang.',
            'uc3_t'     => 'Pengendalian hama serentak', 'uc3_d' => 'Menuntaskan ratusan hektare lahan dalam waktu singkat sebelum hama menyebar.',
            'uc4_t'     => 'Konstruksi karbon komposit kokoh', 'uc4_d' => 'Tahan cuaca ekstrem dengan rangka material terbaik.',
        ],
        'ferto-50l' => [
            'title'     => 'FERTO 50 (50 Liter)',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Ultra Capacity',
            'tagline'   => 'Drone Pertanian FERTO 50 — Kapasitas puncak 50L untuk produktivitas agrikultur tanpa tanding.',
            'content'   => 'FERTO 50 adalah platform UAV agrikultur dengan muatan tertinggi di lini FDS. Membawa tangki berkapasitas 50 Liter dengan sistem propulsi bertenaga raksasa dan kecepatan jelajah hingga 6 m/s, drone ini diciptakan untuk menjawab tantangan operasional perkebunan agrikultur terbesar di Indonesia dengan efisiensi maksimal.',
            'stat1_n'   => 'SNI', 'stat1_l' => 'SNI 9199:2023',
            'stat2_n'   => '60,74%', 'stat2_l' => 'TKDN + BMP',
            'stat3_n'   => '50 Liter', 'stat3_l' => 'Kapasitas Tangki',
            'stat4_n'   => 'Garansi', 'stat4_l' => 'Purna Jual Resmi',
            'sp_kap'    => '50 Liter',
            'sp_dur'    => '20 – 30 menit',
            'sp_bat'    => '28.000 mAh',
            'sp_prod'   => '15 Ha / jam',
            'sp_kec'    => '6 m/s',
            'sp_ket'    => '',
            'sp_oto'    => 'Semi-to-Fully Autonomous, Terrain Following, Fail-Safe',
            'sp_gcs'    => 'FDS STATION (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015',
            'uc1_t'     => 'Perkebunan konglomerasi & agroindustri', 'uc1_d' => 'Menangani area ribuan hektare dengan armada minimal.',
            'uc2_t'     => 'Penyebaran pupuk & pestisida intensif', 'uc2_d' => 'Muatan 50L memaksimalkan efisiensi setiap sorti penerbangan.',
            'uc3_t'     => 'Misi otomatis skala besar', 'uc3_d' => 'Perencanaan rute cerdas dan pemantauan real-time via FDS STATION.',
            'uc4_t'     => 'Dukungan logistik & purna jual komprehensif', 'uc4_d' => 'Paket perawatan berkala dan penyediaan suku cadang resmi.',
        ],
        'deltav' => [
            'title'     => 'DELTAV (Fixed-Wing VTOL)',
            'kategori'  => 'Pemetaan & GIS',
            'badge'     => 'Hybrid VTOL',
            'tagline'   => 'Platform UAV Pemetaan Fixed-Wing VTOL Hybrid — Jangkauan 60 km untuk akuisisi geospasial area luas.',
            'content'   => 'DELTAV adalah pesawat UAV fixed-wing berteknologi Hybrid VTOL (Vertical Takeoff and Landing) yang menggabungkan kemudahan lepas landas tegak lurus tanpa landasan pacu dengan kecepatan jelajah serta efisiensi aerodinamis pesawat sayap tetap. Dengan jangkauan hingga 60 km dan durasi terbang 60-120 menit, DELTAV adalah solusi terbaik untuk survei topografi, ortofoto beresolusi tinggi, pemetaan kehutanan, dan akuisisi data geospasial area luas dalam sekali terbang.',
            'stat1_n'   => '2.000mm', 'stat1_l' => 'Bentang Sayap',
            'stat2_n'   => '60 km', 'stat2_l' => 'Jangkauan Misi',
            'stat3_n'   => '120 min', 'stat3_l' => 'Durasi Terbang Maks',
            'stat4_n'   => 'VTOL', 'stat4_l' => 'Lepas Landas Vertikal',
            'sp_kap'    => 'Bentang Sayap: 2.000 mm | Payload: 1 – 2 kg',
            'sp_dur'    => '60 – 120 menit',
            'sp_bat'    => 'LiPo High Density Cruise Battery',
            'sp_prod'   => 'Jangkauan Misi: Hingga 60 km',
            'sp_kec'    => '15 – 22 m/s',
            'sp_ket'    => 'Rangka Komposit Karbon Hibrida Tahan Angin',
            'sp_oto'    => 'Semi-to-Fully Autonomous, Waypoint Mission, Return to Launch',
            'sp_gcs'    => 'FDS STATION GCS (Bahasa Indonesia)',
            'sp_sert'   => 'SNI 9199:2023 | ISO 9001:2015 | TKDN Resmi',
            'uc1_t'     => 'Survei topografi & konstruksi', 'uc1_d' => 'Menghemat waktu 70-80% untuk pemodelan 3D, ortomozaik, dan perhitungan cut & fill.',
            'uc2_t'     => 'Kehutanan & lingkungan', 'uc2_d' => 'Pemetaan DAS, tutupan kanopi hutan, dan progres reklamasi tambang.',
            'uc3_t'     => 'Pertambangan & kuari', 'uc3_d' => 'Pemetaan kontur presisi dan pemantauan batas konsesi tambang.',
            'uc4_t'     => 'Perencanaan tata ruang & GIS nasional', 'uc4_d' => 'Akurasi data geospasial sub-sentimeter siap integrasi CAD & BIM.',
        ],
        'multipurpose' => [
            'title'     => 'MULTIPURPOSE (UAV Kustom & Inspeksi)',
            'kategori'  => 'Pemetaan & GIS',
            'badge'     => 'Modular UAV',
            'tagline'   => 'Platform UAV Modular Serbaguna — Integrasi payload termal, optical zoom, & sensor inspeksi.',
            'content'   => 'MULTIPURPOSE dirancang sebagai platform UAV modular yang fleksibel untuk berbagai misi kustom. Mampu mengangkut payload hingga 5 kg dengan integrasi berbagai sensor canggih seperti kamera termal inframerah, optik zoom 20x, hingga sensor LiDAR. Sangat andal untuk inspeksi aset kritikal seperti jaringan transmisi listrik 150 kV, ladang panel surya, tangki minyak & gas, serta infrastruktur jembatan dan gedung tinggi tanpa risiko keselamatan kerja.',
            'stat1_n'   => '5 kg', 'stat1_l' => 'Kapasitas Payload',
            'stat2_n'   => '30 min', 'stat2_l' => 'Durasi Terbang Maks',
            'stat3_n'   => 'Termal/AI', 'stat3_l' => 'Sensor Kompatibel',
            'stat4_n'   => '150 kV', 'stat4_l' => 'Inspeksi Aset Kritikal',
            'sp_kap'    => 'Payload Kapasitas: 5 kg',
            'sp_dur'    => '15 – 30 menit',
            'sp_bat'    => '8.000 mAh',
            'sp_prod'   => 'Inspeksi Jalur Transmisi & Area Industri',
            'sp_kec'    => '2 – 6 m/s',
            'sp_ket'    => 'Konstruksi Tahan Cuaca & Fail-Safe Mandiri',
            'sp_oto'    => 'Manual, Task Following, Semi-to-Fully Autonomous',
            'sp_gcs'    => 'FDS STATION Real-Time Monitoring & AI Analytics',
            'sp_sert'   => 'SNI 9199:2023 | ISO 9001:2015',
            'uc1_t'     => 'Inspeksi transmisi listrik 150 kV', 'uc1_d' => 'Aman tanpa perlu pemadaman listrik dan bebas risiko bekerja di ketinggian.',
            'uc2_t'     => 'Inspeksi ladang energi surya (Solar PV)', 'uc2_d' => 'Deteksi dini hotspot dan sel rusak berbasis AI untuk cegah kehilangan energi.',
            'uc3_t'     => 'Inspeksi migas & cerobong suar (Flare)', 'uc3_d' => 'Deteksi kebocoran dan korosi tanpa mematikan operasi kilang.',
            'uc4_t'     => 'Inspeksi struktur jembatan & infrastruktur', 'uc4_d' => 'Pemeriksaan keretakan mikro struktur beton dan baja.',
        ],
        'delfro' => [
            'title'     => 'DELFRO (Drone Kargo & Logistik)',
            'kategori'  => 'Kargo',
            'badge'     => 'Logistics UAV',
            'tagline'   => 'Platform UAV Kargo Logistik Ringan — Distribusi logistik cepat dan aman ke area sulit dijangkau.',
            'content'   => 'DELFRO adalah drone kargo otonom yang dikembangkan khusus untuk distribusi logistik ringan yang cepat, efisien, dan aman. Dengan kapasitas angkut 3 hingga 10 kg dan kompartemen kargo berukuran 20 x 20 x 30 cm, DELFRO menjadi solusi mutakhir untuk pengiriman sampel medis, pasokan darurat kebencanaan, suku cadang penting, dan logistik ekspres ke wilayah kepulauan atau daerah terisolir yang sulit dijangkau transportasi darat.',
            'stat1_n'   => '10 kg', 'stat1_l' => 'Kapasitas Payload',
            'stat2_n'   => '15 kg', 'stat2_l' => 'MTOW Maksimum',
            'stat3_n'   => '18"', 'stat3_l' => 'Carbon Propeller',
            'stat4_n'   => 'Auto', 'stat4_l' => 'Waypoint Route',
            'sp_kap'    => 'Payload: 3 – 10 kg | Kotak: 20 x 20 x 30 cm',
            'sp_dur'    => '10 – 15 menit',
            'sp_bat'    => 'LiPo High Density Cargo Battery',
            'sp_prod'   => 'Pengiriman Logistik Antar Titik Waypoint',
            'sp_kec'    => '2 – 6 m/s',
            'sp_ket'    => 'MTOW: 15 kg | Propeller Karbon 18-inch',
            'sp_oto'    => 'Otonom (Waypoint Cargo Route) & Manual Fail-Safe',
            'sp_gcs'    => 'FDS STATION Logistics Management',
            'sp_sert'   => 'SNI 9199:2023 | ISO 9001:2015',
            'uc1_t'     => 'Logistik medis & darurat bencana', 'uc1_d' => 'Pengiriman cepat obat-obatan, darah, dan sampel medis ke lokasi terpencil.',
            'uc2_t'     => 'Pengiriman suku cadang industri', 'uc2_d' => 'Menghubungkan offshore platform atau site tambang dengan warehouse secara kilat.',
            'uc3_t'     => 'Ekspedisi & kurir last-mile', 'uc3_d' => 'Alternatif logistik ramah lingkungan untuk melintasi sungai, bukit, atau selat.',
            'uc4_t'     => 'Manajemen rute otomatis', 'uc4_d' => 'Pemantauan status kargo dan rute penerbangan real-time via FDS STATION.',
        ],
        'rebo' => [
            'title'     => 'REBO (Drone Reboisasi Hutan)',
            'kategori'  => 'Reboisasi',
            'badge'     => 'Heavy-Duty Seedball',
            'tagline'   => 'Platform UAV Reboisasi & Restorasi Hutan — Penyebaran biji seedball presisi tinggi secara otonom.',
            'content'   => 'REBO adalah UAV heavy-duty khusus yang dirancang untuk mendukung misi reboisasi hutan, restorasi lahan kritis, dan reklamasi area pasca-tambang. Mampu mengangkut hingga 20 kg seedball (biji tanaman berkapsul nutrisi) dalam satu kali sorti, REBO menabur biji secara presisi mengikuti pola koordinat otonom pada lereng curam atau hutan lebat yang mustahil diakses penanam manual.',
            'stat1_n'   => '20 kg', 'stat1_l' => 'Payload Seedball',
            'stat2_n'   => '22.000mAh', 'stat2_l' => 'Baterai Daya Tinggi',
            'stat3_n'   => 'Otonom', 'stat3_l' => 'Dispenser Presisi',
            'stat4_n'   => 'Riset', 'stat4_l' => 'UGM & Mitra Swiss',
            'sp_kap'    => 'Kapasitas Payload Biji: 20 kg',
            'sp_dur'    => '15 – 20 menit',
            'sp_bat'    => '22.000 mAh Heavy-Duty Battery',
            'sp_prod'   => 'Auto Seedball Dispensing Grid',
            'sp_kec'    => 'Kecepatan Sesuai Pola Sebar Presisi',
            'sp_ket'    => 'Komposit Karbon Tahan Cuaca Ekstrem',
            'sp_oto'    => 'Otonom Penuh (Auto Seedball Dispensing Grid)',
            'sp_gcs'    => 'FDS STATION Reforestation Mission Planning',
            'sp_sert'   => 'Didukung Riset Bersama UGM & Mitra Swiss',
            'uc1_t'     => 'Restorasi hutan lindung & tebing curam', 'uc1_d' => 'Menghijaukan kembali medan berbahaya tanpa membahayakan petugas.',
            'uc2_t'     => 'Reklamasi lahan bekas tambang', 'uc2_d' => 'Mempercepat pemulihan vegetasi lahan tambang sesuai regulasi lingkungan.',
            'uc3_t'     => 'Konservasi daerah aliran sungai (DAS)', 'uc3_d' => 'Penyebaran bibit pohon penyangga air secara masif dan terstruktur.',
            'uc4_t'     => 'Riset kehutanan berkelanjutan', 'uc4_d' => 'Dikembangkan berdasarkan riset lapangan kolaboratif UGM dan institusi global.',
        ],
    ];

    $existing_slugs = [];
    foreach ($existing as $p) {
        $existing_slugs[$p->post_name] = $p->ID;
    }

    foreach ($seeds as $slug => $data) {
        $post_id = $existing_slugs[$slug] ?? null;

        if (!$post_id) {
            $post_id = wp_insert_post([
                'post_type'    => 'drone',
                'post_name'    => $slug,
                'post_title'   => $data['title'],
                'post_content' => $data['content'],
                'post_status'  => 'publish',
            ]);
        }

        if ($post_id && !is_wp_error($post_id)) {
            // 1. Assign Taxonomy Term 'kategori_drone'
            wp_set_object_terms($post_id, $data['kategori'], 'kategori_drone', false);

            // 2. Set/Update All Post Meta
            update_post_meta($post_id, 'drone_kategori', $data['kategori']);
            update_post_meta($post_id, 'drone_badge', $data['badge']);
            update_post_meta($post_id, 'drone_tagline', $data['tagline']);
            update_post_meta($post_id, 'drone_desc', $data['content']);

            update_post_meta($post_id, 'drone_stat1_num', $data['stat1_n']);
            update_post_meta($post_id, 'drone_stat1_lbl', $data['stat1_l']);
            update_post_meta($post_id, 'drone_stat2_num', $data['stat2_n']);
            update_post_meta($post_id, 'drone_stat2_lbl', $data['stat2_l']);
            update_post_meta($post_id, 'drone_stat3_num', $data['stat3_n']);
            update_post_meta($post_id, 'drone_stat3_lbl', $data['stat3_l']);
            update_post_meta($post_id, 'drone_stat4_num', $data['stat4_n']);
            update_post_meta($post_id, 'drone_stat4_lbl', $data['stat4_l']);

            update_post_meta($post_id, 'drone_spec_kapasitas', $data['sp_kap']);
            update_post_meta($post_id, 'drone_spec_durasi', $data['sp_dur']);
            update_post_meta($post_id, 'drone_spec_baterai', $data['sp_bat']);
            update_post_meta($post_id, 'drone_spec_produktivitas', $data['sp_prod']);
            update_post_meta($post_id, 'drone_spec_kecepatan', $data['sp_kec']);
            if (!empty($data['sp_ket'])) {
                update_post_meta($post_id, 'drone_spec_ketahanan', $data['sp_ket']);
            } else {
                delete_post_meta($post_id, 'drone_spec_ketahanan');
            }
            update_post_meta($post_id, 'drone_spec_otonomi', $data['sp_oto']);
            update_post_meta($post_id, 'drone_spec_gcs', $data['sp_gcs']);
            update_post_meta($post_id, 'drone_spec_sertifikasi', $data['sp_sert']);

            // Structured Raw Specs string
            $raw_specs = "Kapasitas Tangki / Payload: {$data['sp_kap']}\n"
                       . "Durasi Terbang: {$data['sp_dur']}\n"
                       . "Sistem Daya (Baterai): {$data['sp_bat']}\n"
                       . "Produktivitas / Jangkauan: {$data['sp_prod']}\n"
                       . "Kecepatan Jelajah: {$data['sp_kec']}\n"
                       . (!empty($data['sp_ket']) ? "Ketahanan Lingkungan: {$data['sp_ket']}\n" : "")
                       . "Sistem Otonomi & Navigasi: {$data['sp_oto']}\n"
                       . "Ground Control Station: {$data['sp_gcs']}\n"
                       . "Sertifikasi & Standar: {$data['sp_sert']}";
            update_post_meta($post_id, 'drone_specs_raw', trim($raw_specs));

            update_post_meta($post_id, 'drone_uc1_t', $data['uc1_t']);
            update_post_meta($post_id, 'drone_uc1_d', $data['uc1_d']);
            update_post_meta($post_id, 'drone_uc2_t', $data['uc2_t']);
            update_post_meta($post_id, 'drone_uc2_d', $data['uc2_d']);
            update_post_meta($post_id, 'drone_uc3_t', $data['uc3_t']);
            update_post_meta($post_id, 'drone_uc3_d', $data['uc3_d']);
            update_post_meta($post_id, 'drone_uc4_t', $data['uc4_t']);
            update_post_meta($post_id, 'drone_uc4_d', $data['uc4_d']);

            $raw_for = "{$data['uc1_t']} — {$data['uc1_d']}\n"
                     . "{$data['uc2_t']} — {$data['uc2_d']}\n"
                     . "{$data['uc3_t']} — {$data['uc3_d']}\n"
                     . "{$data['uc4_t']} — {$data['uc4_d']}";
            update_post_meta($post_id, 'drone_for', $raw_for);
        }
    }
    update_option('fds_drones_seeded_final_v1', true);
});
