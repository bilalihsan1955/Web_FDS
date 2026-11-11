<?php

namespace App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Post Type & Taxonomy System: Drone & Kategori Drone
 * Theme: FDS Theme (PT Karya Solusi Angkasa)
 *
 * Fitur:
 * 1. CPT 'drone' ("Produk Drone") — Menggunakan Form Textfield Murni (Bukan Editor Artikel / Blog)
 * 2. Taksonomi 'kategori_drone' ("Kategori Drone") dengan Kategori Resmi:
 *    - Agrikultur
 *    - Pemetaan & GIS
 *    - Kargo
 * 3. Tampilan Edit WP Admin Premium: Full Textfield Form (Bebas dari Editor Post Berita)
 * 4. Auto-Seeder & Sinkronisasi Data compro.md
 */

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
        'has_archive'         => false,
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

    // 2.3 Auto-insert Kategori Resmi FDS sekali saja jika belum tersimpan
    if (!get_option('fds_default_drone_cats_v2')) {
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
        update_option('fds_default_drone_cats_v2', 1, false);
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



// Auto-route / redirect root slug /{slug} or legacy page to drone single if slug matches a drone post
add_action('template_redirect', function () {
    global $wp;
    $slug = trim($wp->request, '/');
    if ($slug === 'bandingkan-drone' || $slug === 'compare') {
        wp_safe_redirect(home_url('/bandingkan/'), 301);
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

    // Ambil data tersimpan (Hormati nilai kosong jika admin mengosongkannya)
    $kategori           = get_post_meta($post->ID, 'drone_kategori', true);
    if ($kategori === '' && !metadata_exists('post', $post->ID, 'drone_kategori')) {
        $kategori = 'Agrikultur';
    }
    $badge              = get_post_meta($post->ID, 'drone_badge', true);
    $tagline            = get_post_meta($post->ID, 'drone_tagline', true);
    $desc               = get_post_meta($post->ID, 'drone_desc', true);
    if ($desc === '' && !metadata_exists('post', $post->ID, 'drone_desc')) {
        $desc = $post->post_content;
    }
    $brosur_url         = get_post_meta($post->ID, 'drone_brosur_url', true);
    $video_url          = get_post_meta($post->ID, 'drone_video_url', true);
    $specs_img_url      = get_post_meta($post->ID, 'drone_specs_img', true);

    // 4 Key Stats Bar (Tanpa fallback paksa agar bisa dikosongkan sepenuhnya)
    $stat1_num          = get_post_meta($post->ID, 'drone_stat1_num', true);
    $stat1_lbl          = get_post_meta($post->ID, 'drone_stat1_lbl', true);
    $stat2_num          = get_post_meta($post->ID, 'drone_stat2_num', true);
    $stat2_lbl          = get_post_meta($post->ID, 'drone_stat2_lbl', true);
    $stat3_num          = get_post_meta($post->ID, 'drone_stat3_num', true);
    $stat3_lbl          = get_post_meta($post->ID, 'drone_stat3_lbl', true);
    $stat4_num          = get_post_meta($post->ID, 'drone_stat4_num', true);
    $stat4_lbl          = get_post_meta($post->ID, 'drone_stat4_lbl', true);

    // Spesifikasi Teknis Terstruktur (Textfields)
    $spec_kapasitas     = get_post_meta($post->ID, 'drone_spec_kapasitas', true);
    $spec_durasi        = get_post_meta($post->ID, 'drone_spec_durasi', true);
    $spec_baterai       = get_post_meta($post->ID, 'drone_spec_baterai', true);
    $spec_produktivitas = get_post_meta($post->ID, 'drone_spec_produktivitas', true);
    $spec_kecepatan     = get_post_meta($post->ID, 'drone_spec_kecepatan', true);
    $spec_ketahanan     = get_post_meta($post->ID, 'drone_spec_ketahanan', true);
    $spec_otonomi       = get_post_meta($post->ID, 'drone_spec_otonomi', true);
    $spec_gcs           = get_post_meta($post->ID, 'drone_spec_gcs', true);
    $spec_sertifikasi   = get_post_meta($post->ID, 'drone_spec_sertifikasi', true);

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
        .fds-section-title { font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 16px 0; padding-bottom: 8px; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 8px; letter-spacing: 0.03em; }
        
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
            border-color: #0f808a;
            outline: none;
            box-shadow: 0 0 0 3px rgba(15,128,138,0.15);
        }
        .fds-field .fds-subtext { font-size: 11px; color: #64748b; margin-top: 4px; display: block; }
        
        .fds-stat-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; }
        .fds-stat-card label { font-size: 11px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px; }
        .fds-stat-card input { width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px; margin-bottom: 6px; box-sizing: border-box; }
        
        .fds-uc-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; margin-bottom: 12px; }
        .fds-uc-card h5 { margin: 0 0 8px 0; font-size: 12px; font-weight: 700; color: #0f808a; }
    </style>

    <!-- 1. INFORMASI UTAMA & RINGKASAN PRODUK -->
    <div class="fds-section-card">
        <div class="fds-section-title">📌 1. Informasi Utama Produk Drone</div>
        <p style="font-size: 12px; color: #64748b; margin: 0 0 16px 0; line-height: 1.55;">
            <em>Tip: Untuk memilih kategori drone (Agrikultur, Pemetaan &amp; GIS, Kargo), centang checklist pada kotak <strong>"Kategori Drone"</strong> di bilah kanan (sidebar).<br>
            🖼️ <strong>Sinkronisasi Foto Utama:</strong> Gambar yang Anda pasang pada kotak <strong>"Featured Image / Gambar Unggulan"</strong> di bilah kanan akan tampil otomatis sebagai <strong>Hero Halaman Detail Drone</strong> sekaligus <strong>Foto Kartu Katalog Drone</strong> (<code>/produk/</code> &amp; <code>/kategori-drone/*</code>).</em>
        </p>

        <div class="fds-field">
            <label for="drone_badge">Badge / Label Tipe</label>
            <input type="text" id="drone_badge" name="drone_badge" value="<?php echo esc_attr($badge); ?>" placeholder="Contoh: Terlaris / Enterprise / Hybrid VTOL / Modular UAV">
            <span class="fds-subtext">Label badge ringkas berwarna teal di atas judul drone.</span>
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
                <label for="drone_brosur_url">📄 File Brosur PDF Spesifikasi (Opsional)</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="text" id="drone_brosur_url" name="drone_brosur_url" value="<?php echo esc_attr($brosur_url); ?>" placeholder="https://... (URL File PDF)">
                    <button type="button" id="fds_upload_brosur_btn" class="button" style="white-space: nowrap; font-weight: 600;">📁 Upload PDF</button>
                    <button type="button" id="fds_remove_brosur_btn" class="button" style="color: #dc2626;">Hapus</button>
                </div>
                <span class="fds-subtext">Pilih atau unggah dokumen PDF spesifikasi resmi drone ini. Pengunjung akan mengunduh file ini setelah memasukkan email.</span>
            </div>
            <div class="fds-field">
                <label for="drone_video_url">Tautan Video Demo YouTube (Opsional)</label>
                <input type="text" id="drone_video_url" name="drone_video_url" value="<?php echo esc_attr($video_url); ?>" placeholder="https://youtube.com/watch?v=...">
                <span class="fds-subtext">Tautan video demonstrasi penerbangan atau uji coba lapangan.</span>
            </div>
        </div>
    </div>

    <!-- 2. HIGHLIGHT 4 KARTU STATS -->
    <div class="fds-section-card">
        <div class="fds-section-title">📊 2. Highlight 4 Kartu Statistik (Stats Bar)</div>
        <p style="font-size:12px;color:#64748b;margin-bottom:14px;">4 Kartu angka besar yang tampil di bar statistik atas halaman detail produk:</p>
        
        <div class="fds-row-4">
            <div class="fds-stat-card">
                <label>Kartu 1 (Kapasitas / Standar Produk)</label>
                <input type="text" name="drone_stat1_num" value="<?php echo esc_attr($stat1_num); ?>" placeholder="Contoh: 10 L atau SNI (khusus FERTO 15/22)">
                <input type="text" name="drone_stat1_lbl" value="<?php echo esc_attr($stat1_lbl); ?>" placeholder="Contoh: Kapasitas Tangki atau SNI 9199:2023">
            </div>
            <div class="fds-stat-card">
                <label>Kartu 2 (TKDN / Spesifikasi Utama)</label>
                <input type="text" name="drone_stat2_num" value="<?php echo esc_attr($stat2_num); ?>" placeholder="Contoh: 60,74% (Seri FERTO) atau 60 km">
                <input type="text" name="drone_stat2_lbl" value="<?php echo esc_attr($stat2_lbl); ?>" placeholder="Contoh: TKDN + BMP atau Jangkauan Misi">
            </div>
            <div class="fds-stat-card">
                <label>Kartu 3 (Produktivitas / Durasi)</label>
                <input type="text" name="drone_stat3_num" value="<?php echo esc_attr($stat3_num); ?>" placeholder="Contoh: 8 Ha/j atau 120 min">
                <input type="text" name="drone_stat3_lbl" value="<?php echo esc_attr($stat3_lbl); ?>" placeholder="Contoh: Produktivitas Semprot">
            </div>
            <div class="fds-stat-card">
                <label>Kartu 4 (Garansi / Keunggulan)</label>
                <input type="text" name="drone_stat4_num" value="<?php echo esc_attr($stat4_num); ?>" placeholder="Contoh: Garansi">
                <input type="text" name="drone_stat4_lbl" value="<?php echo esc_attr($stat4_lbl); ?>" placeholder="Contoh: Purna Jual Resmi">
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
                <label>Payload (Kapasitas Muatan / Tangki / Sensor)</label>
                <input type="text" name="drone_spec_kapasitas" value="<?php echo esc_attr($spec_kapasitas); ?>" placeholder="Contoh: 10 Liter / 1 – 2 kg (LiDAR & RGB) / Muatan 10 kg">
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
                <input type="text" name="drone_spec_gcs" value="<?php echo esc_attr($spec_gcs); ?>" placeholder="Contoh: FDS Station (Bahasa Indonesia)">
            </div>
        </div>

        <div class="fds-field">
            <label>Sertifikasi & Standar Resmi</label>
            <input type="text" name="drone_spec_sertifikasi" value="<?php echo esc_attr($spec_sertifikasi); ?>" placeholder="Contoh: TKDN + BMP 60,74% (Seri FERTO) | SNI 9199:2023 (FERTO 15 & 22) | ISO 9001:2015">
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
                <input type="text" name="drone_uc2_d" value="<?php echo esc_attr($uc2_d); ?>" placeholder="Keterangan (Contoh: Aplikasi droplet seragam dengan navigasi jalur otomatis FDS Station.)">
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
        // Media Uploader untuk File PDF Brosur
        $('#fds_upload_brosur_btn').on('click', function(e) {
            e.preventDefault();
            var brosurUploader = wp.media({
                title: 'Pilih atau Unggah File PDF Brosur Drone',
                button: { text: 'Gunakan File PDF Ini' },
                library: { type: 'application/pdf' },
                multiple: false
            }).on('select', function() {
                var attachment = brosurUploader.state().get('selection').first().toJSON();
                $('#drone_brosur_url').val(attachment.url);
            }).open();
        });

        $('#fds_remove_brosur_btn').on('click', function(e) {
            e.preventDefault();
            $('#drone_brosur_url').val('');
        });

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

function fds_save_post_drone_handler($post_id) {
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
        update_post_meta($post_id, 'drone_specs_img', sanitize_text_field(wp_unslash((string) $_POST['drone_specs_img'])));
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
            update_post_meta($post_id, $f, sanitize_text_field(wp_unslash((string) $_POST[$f])));
        }
    }

    if (isset($_POST['drone_desc'])) {
        $clean_desc = sanitize_textarea_field(wp_unslash((string) $_POST['drone_desc']));
        update_post_meta($post_id, 'drone_desc', $clean_desc);
    }

    // Reconstruct string tabel spesifikasi & usecase otomatis
    $sp_kap  = sanitize_text_field(wp_unslash((string) ($_POST['drone_spec_kapasitas'] ?? '')));
    $sp_dur  = sanitize_text_field(wp_unslash((string) ($_POST['drone_spec_durasi'] ?? '')));
    $sp_bat  = sanitize_text_field(wp_unslash((string) ($_POST['drone_spec_baterai'] ?? '')));
    $sp_prod = sanitize_text_field(wp_unslash((string) ($_POST['drone_spec_produktivitas'] ?? '')));
    $sp_kec  = sanitize_text_field(wp_unslash((string) ($_POST['drone_spec_kecepatan'] ?? '')));
    $sp_ket  = sanitize_text_field(wp_unslash((string) ($_POST['drone_spec_ketahanan'] ?? '')));
    $sp_oto  = sanitize_text_field(wp_unslash((string) ($_POST['drone_spec_otonomi'] ?? '')));
    $sp_gcs  = sanitize_text_field(wp_unslash((string) ($_POST['drone_spec_gcs'] ?? '')));
    $sp_sert = sanitize_text_field(wp_unslash((string) ($_POST['drone_spec_sertifikasi'] ?? '')));

    $specs_arr = [];
    if ($sp_kap)  $specs_arr[] = "Payload: $sp_kap";
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
        $t = sanitize_text_field(wp_unslash((string) ($_POST["drone_uc{$i}_t"] ?? '')));
        $d = sanitize_text_field(wp_unslash((string) ($_POST["drone_uc{$i}_d"] ?? '')));
        if ($t) {
            $uc_arr[] = $d ? "$t — $d" : $t;
        }
    }
    update_post_meta($post_id, 'drone_for', implode("\n", $uc_arr));

    // Sinkronisasi kategori dari taksonomi checklist sidebar ke post meta 'drone_kategori'
    $terms = wp_get_post_terms($post_id, 'kategori_drone');
    if (!empty($terms) && !is_wp_error($terms)) {
        $clean_term_name = function_exists('fds_clean_string') ? \fds_clean_string((string) $terms[0]->name) : (string) $terms[0]->name;
        update_post_meta($post_id, 'drone_kategori', sanitize_text_field($clean_term_name));
    }

    // Auto-generate SEO metadata when empty (never overwrite custom content)
    fds_sync_drone_rank_math_seo($post_id, false);

    // Invalidate Mega Menu Nav Transient Cache & Bandingkan Catalog Cache
    fds_purge_drone_catalog_caches();

    $is_saving = false;
}
add_action('save_post_drone', __NAMESPACE__ . '\\fds_save_post_drone_handler', 10);

function fds_purge_drone_catalog_caches(): void {
    delete_transient('fds_mega_menu_data');
    delete_transient('fds_mega_menu_data_v2');
    delete_transient('fds_mega_menu_data_v3');
    delete_transient('fds_bandingkan_catalog_cache');
    delete_transient('fds_bandingkan_catalog_cache_v2');
    delete_transient('fds_bandingkan_catalog_cache_v3');
    delete_transient('fds_llms_txt_cache_v4');
    if (function_exists('has_action') && has_action('litespeed_purge_all')) {
        do_action('litespeed_purge_all');
    }
}

// Invalidate Mega Menu & Bandingkan cache saat drone dihapus atau di-restore
add_action('wp_trash_post', function ($post_id) {
    if (get_post_type($post_id) === 'drone') {
        fds_purge_drone_catalog_caches();
    }
});
add_action('untrash_post', function ($post_id) {
    if (get_post_type($post_id) === 'drone') {
        fds_purge_drone_catalog_caches();
    }
});
add_action('delete_post', function ($post_id) {
    if (get_post_type($post_id) === 'drone') {
        fds_purge_drone_catalog_caches();
    }
});
add_action('saved_kategori_drone', __NAMESPACE__ . '\\fds_purge_drone_catalog_caches');
add_action('delete_kategori_drone', __NAMESPACE__ . '\\fds_purge_drone_catalog_caches');

/**
 * Identify normalized model key from post ID/slug/title
 */
function fds_get_drone_model_key($post_id): string {
    $slug  = strtolower((string) get_post_field('post_name', $post_id));
    $title = strtolower((string) get_the_title($post_id));
    $hay   = $slug . ' ' . $title;

    if (preg_match('/ferto[\s\-_]*50/', $hay)) return 'ferto-50l';
    if (preg_match('/ferto[\s\-_]*30/', $hay)) return 'ferto-30l';
    if (preg_match('/ferto[\s\-_]*22/', $hay)) return 'ferto-22l';
    if (preg_match('/ferto[\s\-_]*(15|20)/', $hay)) return 'ferto-15l';
    if (preg_match('/ferto[\s\-_]*10/', $hay)) return 'ferto-10l';
    if (preg_match('/ferto[\s\-_]*5\b/', $hay) || strpos($slug, 'ferto-5') !== false) return 'ferto-5l';
    if (strpos($hay, 'deltav') !== false || strpos($hay, 'vtol') !== false) return 'deltav';
    if (strpos($hay, 'delfro') !== false || strpos($hay, 'kargo') !== false) return 'delfro';
    if (strpos($hay, 'multi') !== false) return 'multipurpose';

    return $slug ?: 'custom';
}

/**
 * Unique 250-400 Word Model Field Guide & Model-Specific FAQs (100% synced with JSON-LD FAQPage)
 */
function fds_get_drone_model_guide_and_faqs($post_id): array {
    $decode = function ($val) {
        return function_exists(__NAMESPACE__ . '\\fds_decode_text')
            ? fds_decode_text((string) $val)
            : trim(html_entity_decode(wp_specialchars_decode((string) $val, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    };

    $key         = fds_get_drone_model_key($post_id);
    $title       = $decode(get_the_title($post_id));
    $model_base  = trim((string) preg_replace('/\s*\(.*?\)/', '', $title)) ?: $title;
    $kategori    = $decode((string) (get_post_meta($post_id, 'drone_kategori', true) ?: 'UAV Industri'));
    $desc        = $decode((string) get_post_meta($post_id, 'drone_desc', true));
    $kapasitas   = $decode((string) get_post_meta($post_id, 'drone_spec_kapasitas', true));
    $durasi      = $decode((string) get_post_meta($post_id, 'drone_spec_durasi', true));
    $baterai     = $decode((string) get_post_meta($post_id, 'drone_spec_baterai', true));
    $produktiv   = $decode((string) get_post_meta($post_id, 'drone_spec_produktivitas', true));
    $kecepatan   = $decode((string) get_post_meta($post_id, 'drone_spec_kecepatan', true));
    $otonomi     = $decode((string) get_post_meta($post_id, 'drone_spec_otonomi', true));
    $gcs         = $decode((string) (get_post_meta($post_id, 'drone_spec_gcs', true) ?: 'FDS Station (Bahasa Indonesia)'));
    $gcs         = str_ireplace('FDS STATION', 'FDS Station', $gcs);

    $terms   = get_the_terms($post_id, 'kategori_drone');
    $cat_url = (!empty($terms) && !is_wp_error($terms)) ? get_term_link($terms[0]) : home_url('/produk/');
    if (is_wp_error($cat_url)) {
        $cat_url = home_url('/produk/');
    }

    $produk_url  = home_url('/produk/');
    $compare_url = home_url('/bandingkan/');
    $layanan_url = home_url('/layanan/perkebunan-pertanian/');
    $contact_url = home_url('/kontak/#hubungi-form');

    $guides = [
        'ferto-5l' => [
            'lead' => '<strong>FERTO 5</strong> merupakan drone sprayer berkapasitas tangki <strong>5 Liter</strong> yang dikembangkan oleh <strong>PT Karya Solusi Angkasa (Full Drone Solutions)</strong> khusus untuk kondisi lahan sempit, berbukit, dan terasering. Bobotnya yang ringkas memudahkan operator membawa armada ke lokasi sulit akses tanpa kendaraan berat, dengan dukungan sertifikasi <strong>TKDN 60,74%</strong>.',
            'sections' => [
                [
                    'h2' => 'Karakteristik Medan & Keunggulan Manuver Lahan Miring',
                    'p'  => 'Banyak lahan hortikultura, sayuran dataran tinggi, serta petak sawah terasering tidak efisien dijangkau drone berukuran masif. Melalui kombinasi baterai 8.000 mAh, kecepatan jelajah terukur 2–6 m/s, serta sensor Terrain Following, FERTO 5 menjaga ketinggian semprot konsisten di atas kontur tanah yang berubah-ubah.',
                ],
                [
                    'h2' => 'Alur Misi Otomatis Melalui FDS Station',
                    'p'  => 'Operator cukup memetakan batas petak di aplikasi FDS Station berbahasa Indonesia, menetapkan dosis liter per hektare, lalu menjalankan penyemprotan otonom dengan produktivitas sekitar 1 Ha per jam. Jika kapasitas baterai menipis sebelum petak selesai, wahana menyimpan titik koordinat terakhir secara presisi untuk melanjutkan misi setelah pengisian ulang.',
                ],
                [
                    'h2' => 'Kecocokan untuk Kelompok Tani Pemula & Usaha Jasa Mandiri',
                    'p'  => 'Bagi penyedia jasa semprot mandiri maupun Gapoktan yang baru mengadopsi pertanian presisi, FERTO 5 menawarkan kurva belajar yang singkat, kebutuhan baterai pendukung lebih hemat, dan dukungan purna jual suku cadang langsung dari workshop FDS.',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'Apakah FERTO 5 sudah memenuhi sertifikasi TKDN resmi?',
                    'a' => 'Ya. FERTO 5 merupakan bagian dari seri FERTO buatan PT Karya Solusi Angkasa dengan sertifikasi TKDN + BMP mencapai 60,74%.',
                ],
                [
                    'q' => 'Berapa kapasitas baterai dan luas semprot per jam FERTO 5?',
                    'a' => 'FERTO 5 menggunakan baterai 8.000 mAh dengan durasi terbang 10–15 menit per sortie dan produktivitas rata-rata 1 hektare per jam.',
                ],
                [
                    'q' => 'Mengapa FERTO 5 cocok untuk lahan terasering atau dataran tinggi?',
                    'a' => 'Dimensi rangka kompak memudahkan transportasi ke petak berbukit, sementara fitur Terrain Following membantu menjaga jarak nosel terhadap tajuk tanaman miring.',
                ],
                [
                    'q' => 'Apakah pembelian FERTO 5 termasuk pelatihan pengoperasian?',
                    'a' => 'Setiap pengadaan unit didukung program pelatihan pengoperasian FDS Station, kalibrasi nosel, prosedur keselamatan terbang, dan pendampingan teknis resmi.',
                ],
            ],
        ],
        'ferto-10l' => [
            'lead' => '<strong>FERTO 10</strong> hadir dengan kapasitas tangki <strong>10 Liter</strong> yang menyeimbangkan bobot terbang lincah dan efisiensi cakupan sawah maupun kebun muda. Diproduksi di Indonesia dengan nilai <strong>TKDN 60,74%</strong>, model ini dirancang sebagai armada utama Gapoktan, koperasi tani, dan kontraktor penyemprotan rutin.',
            'sections' => [
                [
                    'h2' => 'Keseimbangan Investasi & Produktivitas Lapangan',
                    'p'  => 'Dengan suplai daya baterai 16.000 mAh untuk durasi terbang 12–15 menit serta produktivitas 1 hingga 1,5 Ha per jam, FERTO 10 memangkas waktu aplikasi pestisida cair maupun pupuk daun dibandingkan metode gendong manual. Droplet teratomisasi secara seragam sehingga pemakaian larutan kerja lebih terkontrol.',
                ],
                [
                    'h2' => 'Operasional Harian dengan Kendali FDS Station',
                    'p'  => 'Perangkat lunak FDS Station menyajikan antarmuka berbahasa Indonesia yang jelas untuk mengatur kecepatan terbang 2–6 m/s, pola jalur bolak-balik, serta fitur fail-safe Return to Home. Catatan riwayat luas lahan membantu pengurus koperasi maupun manajer kebun mengaudit progres harian.',
                ],
                [
                    'h2' => 'Dukungan Suku Cadang Domestik & Perawatan Terprediksi',
                    'p'  => 'Seluruh arm karbon, nosel, dan modul pompa dapat dirawat menggunakan stok komponen resmi dari fasilitas produksi PT Karya Solusi Angkasa di Sleman, sehingga jadwal musim tanam tidak terhambat waktu tunggu suku cadang.',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'Apa status sertifikasi TKDN pada FERTO 10?',
                    'a' => 'FERTO 10 termasuk lini sprayer seri FERTO yang telah mengantongi sertifikat TKDN + BMP 60,74% dari Kementerian Perindustrian RI.',
                ],
                [
                    'q' => 'Berapa rata-rata produktivitas kerja FERTO 10 di sawah irigasi?',
                    'a' => 'Dalam operasi standar dengan baterai 16.000 mAh, FERTO 10 mencapai produktivitas 1 hingga 1,5 hektare per jam tergantung kontur petak dan jarak titik isi ulang.',
                ],
                [
                    'q' => 'Apakah FERTO 10 mendukung pengisian ulang rute otomatis saat cairan habis?',
                    'a' => 'Ya. Melalui sistem waypoint otonom FDS Station, drone dapat menandai titik terakhir saat tangki kosong untuk melanjutkan penyemprotan tepat dari koordinat tersebut.',
                ],
                [
                    'q' => 'Di mana layanan servis dan konsultasi suku cadang FERTO 10 tersedia?',
                    'a' => 'Dukungan teknis dan ketersediaan suku cadang asli dilayani langsung oleh tim engineering PT Karya Solusi Angkasa di Sleman, Yogyakarta maupun jadwal kunjungan tim lapangan.',
                ],
            ],
        ],
        'ferto-15l' => [
            'lead' => '<strong>FERTO 15</strong> menawarkan kapasitas tangki <strong>17 Liter (15 – 17 Liter)</strong> dengan produktivitas semprot hingga <strong>8 Ha per jam</strong> untuk kebun komersial, hortikultura intensif, tebu, dan jagung. Unit ini mengantongi sertifikasi ganda <strong>TKDN 60,74%</strong> dan standar nasional <strong>SNI 9199:2023</strong>.',
            'sections' => [
                [
                    'h2' => 'Performa Komersial 17 Liter & Fleksibilitas Aplikasi',
                    'p'  => 'Kombinasi kapasitas kerja 17 Liter dan baterai 16.000 mAh memungkinkan waktu terbang 15–25 menit per sorti. Dibandingkan kelas di bawahnya, frekuensi pendaratan untuk isi ulang berkurang drastis sehingga kontraktor perlindungan tanaman dapat menuntaskan blok puluhan hektare dalam satu jendela cuaca pagi.',
                ],
                [
                    'h2' => 'Opsi Integrasi Tangki Granule Spreader',
                    'p'  => 'Selain modul semprot cairan bertekanan, platform FERTO 15 mendukung konfigurasi spreader granular untuk aplikasi pupuk butir dan benih pada lahan basah maupun kebun berteras.',
                ],
                [
                    'h2' => 'Kesiapan Pengadaan Instansi & Standar SNI 9199:2023',
                    'p'  => 'Kepemilikan sertifikat SNI 9199:2023 khusus alat semprot tanpa awak pertanian serta nilai TKDN 60,74% menjadikan FERTO 15 memenuhi persyaratan verifikasi pengadaan Dinas Pertanian, proyek ketahanan pangan, maupun BUMN perkebunan melalui skema e-Katalog LKPP.',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'Mengapa kapasitas tangki FERTO 15 tercatat 17 Liter?',
                    'a' => 'Meski mengusung nama model FERTO 15, ruang tampung aktual tangki operasionalnya mencapai 17 Liter (rentang kerja 15 – 17 Liter) guna mengoptimalkan efisiensi per sorti.',
                ],
                [
                    'q' => 'Apakah FERTO 15 sudah bersertifikat SNI 9199:2023 dan TKDN?',
                    'a' => 'Ya. FERTO 15 merupakan model resmi FDS yang telah memenuhi standar SNI 9199:2023 sekaligus memiliki nilai TKDN + BMP 60,74%.',
                ],
                [
                    'q' => 'Apakah FERTO 15 dapat dipakai untuk menebar pupuk butir padat?',
                    'a' => 'Bisa. Platform FERTO 15 kompatibel dengan modul dual-mode Sprayer untuk cairan dan Granule Spreader untuk pemupukan butir maupun benih.',
                ],
                [
                    'q' => 'Berapa produktivitas penyemprotan harian FERTO 15?',
                    'a' => 'Dengan jendela terbang 15–25 menit dan kecepatan 2–6 m/s, produktivitas kerja mencapai hingga 8 hektare per jam tergantung bentuk blok dan logistik pengisian.',
                ],
            ],
        ],
        'ferto-22l' => [
            'lead' => '<strong>FERTO 22</strong> dirancang untuk operasional perkebunan komersial skala besar seperti kelapa sawit dan tebu yang menuntut daya angkut tangki <strong>22 Liter</strong>, kecepatan jelajah <strong>5,24 m/s</strong>, serta legalitas penuh bersertifikat <strong>TKDN 60,74%</strong> dan <strong>SNI 9199:2023</strong>.',
            'sections' => [
                [
                    'h2' => 'Daya Dorong Stabil untuk Kanopi Sawit & Tanaman Tinggi',
                    'p'  => 'Menggunakan baterai berkapasitas 22.000 mAh untuk durasi terbang 20–25 menit, FERTO 22 menghasilkan produktivitas kerja 8,5 hingga 12 Ha per jam. Aliran udara rotor membantu penetrasi droplet halus menembus tajuk pelepah kelapa sawit maupun rumpun tebu tinggi secara lebih merata dibandingkan penyemprotan manual.',
                ],
                [
                    'h2' => 'Integrasi Analitik Lahan & Pengadaan BUMN Perkebunan',
                    'p'  => 'Melalui perangkat lunak FDS Station berbahasa Indonesia, manajemen kebun dapat mengekspor log penerbangan, dosis aplikasi, serta menggabungkan perencanaan rute bersama peta indeks kesehatan tanaman NDVI. Status sertifikasi TKDN + BMP 60,74% dan SNI 9199:2023 mempermudah proses tender resmi pengadaan alat mesin pertanian.',
                ],
                [
                    'h2' => 'Keandalan Operasi Lapangan Berkelanjutan',
                    'p'  => 'Konstruksi rangka komposit memudahkan perawatan rutin di workshop afdeling kebun tanpa menunggu pengiriman komponen lintas negara.',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'Apakah FERTO 22 memiliki sertifikat SNI 9199:2023 dan TKDN resmi?',
                    'a' => 'Ya. Bersama FERTO 15, FERTO 22 telah memperoleh sertifikasi SNI 9199:2023 serta sertifikat TKDN + BMP sebesar 60,74%.',
                ],
                [
                    'q' => 'Berapa produktivitas semprot dan durasi terbang FERTO 22?',
                    'a' => 'Dengan tangki 22 Liter dan baterai 22.000 mAh, FERTO 22 terbang selama 20–25 menit per baterai dan mencatat produktivitas 8,5 hingga 12 Ha/jam.',
                ],
                [
                    'q' => 'Apakah jalur terbang FERTO 22 dapat diselaraskan dengan peta kesehatan tanaman NDVI?',
                    'a' => 'Bisa. FDS Station mendukung perencanaan rute berbasis poligon blok maupun kebutuhan dosis presisi hasil survei multispektral NDVI.',
                ],
                [
                    'q' => 'Bagaimana kesiapan purna jual untuk operasional perkebunan di luar Pulau Jawa?',
                    'a' => 'PT Karya Solusi Angkasa melayani pengiriman suku cadang siap pakai dari Yogyakarta, garansi manufaktur resmi, serta program sertifikasi pilot bagi operator kebun.',
                ],
            ],
        ],
        'ferto-30l' => [
            'lead' => '<strong>FERTO 30</strong> menaikkan skala kapasitas tangki menjadi <strong>30 Liter</strong> dengan produktivitas kerja hingga <strong>15 Ha per jam</strong> dan nilai <strong>TKDN 60,74%</strong>. Model ini dibangun untuk agroindustri besar serta Hutan Tanaman Industri (HTI) yang membutuhkan kecepatan cakupan blok tinggi.',
            'sections' => [
                [
                    'h2' => 'Efisiensi Rotasi Isi Ulang pada Blok Ratusan Hektare',
                    'p'  => 'Ditenagai paket baterai 28.000 mAh dengan ketahanan terbang 20–30 menit pada kecepatan jelajah 5,24 m/s, tangki 30 Liter memangkas total pergantian baterai dan pengisian cairan sepanjang hari kerja. Hal ini penting ketika musim hujan mempersempit waktu penyemprotan bebas embun.',
                ],
                [
                    'h2' => 'Konfigurasi Dual-Mode Cairan dan Granular HTI',
                    'p'  => 'Platform FERTO 30 mendukung pengaplikasian herbisida/insektisida cair maupun penebaran pupuk butir atau benih secara terukur pada lahan perkebunan maupun program penanaman kembali area kehutanan.',
                ],
                [
                    'h2' => 'Kontrol Armada Terpusat lewat FDS Station',
                    'p'  => 'Navigasi semi hingga otonom penuh lengkap dengan Radar Terrain Following membantu pilot menjaga jarak terbang aman ketika melintasi areal bergelombang di luar jaringan jalan utama kebun.',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'Apakah FERTO 30 sudah bersertifikat TKDN?',
                    'a' => 'Ya. FERTO 30 merupakan bagian dari seri produksi FERTO FDS yang telah memiliki sertifikasi TKDN + BMP 60,74%.',
                ],
                [
                    'q' => 'Berapa jangkauan kerja per jam FERTO 30 di perkebunan sawit atau HTI?',
                    'a' => 'FERTO 30 mampu menjangkau sekitar 15 hektare per jam dengan dukungan tangki 30 Liter dan baterai berkapasitas 28.000 mAh.',
                ],
                [
                    'q' => 'Apakah tangki FERTO 30 dapat digunakan untuk penebaran pupuk padat?',
                    'a' => 'Dapat. Tersedia konfigurasi dual-mode agar wahana dapat beralih antara penyemprotan larutan cair dan sebar granular padat.',
                ],
                [
                    'q' => 'Berapa lama pelatihan yang dibutuhkan operator untuk armada 30 Liter?',
                    'a' => 'Program pelatihan terstruktur FDS membekali operator dengan SOP muatan berat, keselamatan baterai, serta perencanaan blok pada FDS Station sebelum operasi mandiri.',
                ],
            ],
        ],
        'ferto-50l' => [
            'lead' => '<strong>FERTO 50</strong> menempati kelas kapasitas tertinggi di lini sprayer FDS dengan tangki <strong>50 Liter</strong> dan baterai berkapasitas tinggi untuk operasi agroindustri berskala ribuan hektare. Sebagai bagian dari seri FERTO buatan lokal, platform ini mengantongi sertifikasi <strong>TKDN 60,74%</strong>.',
            'sections' => [
                [
                    'h2' => 'Kapasitas 50 Liter untuk Produktivitas Puncak Kebun Industri',
                    'p'  => 'Dengan kecepatan jelajah hingga 6 m/s dan ketahanan terbang 20–30 menit, muatan 50 Liter memungkinkan satu tim lapangan menuntaskan cakupan 15 hingga 22 Ha per jam tergantung lebar jalur dan volume aplikasi. Frekuensi berhenti di titik mixing tank berkurang signifikan.',
                ],
                [
                    'h2' => 'Stabilitas Struktur & Pengendalian Dosis Berbasis Radar',
                    'p'  => 'Rangka karbon komposit berukuran besar diperkuat sistem kendali FDS Station untuk memonitor debit pompa, ketinggian radar, serta alarm baterai secara real-time saat membawa beban cairan maupun material granular penuh.',
                ],
                [
                    'h2' => 'Optimasi Total Biaya Kepemilikan bagi Korporasi',
                    'p'  => 'Operasi dengan unit berkapasitas besar menekan kebutuhan jumlah personel pilot serta kendaraan logistik air di lapangan dibandingkan pengoperasian banyak unit berkapasitas kecil sekaligus.',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'Berapa kapasitas tangki dan kecepatan operasi FERTO 50?',
                    'a' => 'FERTO 50 membawa tangki 50 Liter dengan kecepatan jelajah hingga 6 m/s dan waktu terbang 20–30 menit per sorti.',
                ],
                [
                    'q' => 'Apakah FERTO 50 memenuhi syarat TKDN untuk pengadaan korporasi maupun BUMN?',
                    'a' => 'Ya. Seluruh seri FERTO produksi PT Karya Solusi Angkasa bersertifikat TKDN + BMP 60,74% dari Kemenperin RI.',
                ],
                [
                    'q' => 'Sektor apa saja yang paling tepat memakai FERTO 50?',
                    'a' => 'FERTO 50 dirancang untuk konglomerasi perkebunan sawit, tebu skala masif, Hutan Tanaman Industri, dan program penyebaran pupuk granular area luas.',
                ],
                [
                    'q' => 'Bagaimana dukungan teknis dan ketersediaan suku cadang FERTO 50?',
                    'a' => 'Seluruh garansi manufaktur, jadwal inspeksi berkala, dan komponen pengganti disediakan langsung oleh workshop PT Karya Solusi Angkasa di Sleman.',
                ],
            ],
        ],
        'deltav' => [
            'lead' => '<strong>DELTAV</strong> merupakan wahana pemetaan udara <strong>Fixed-Wing Hybrid VTOL</strong> berbentang sayap <strong>2.000 mm</strong> yang menggabungkan lepas landas vertikal tanpa landasan pacu dengan efisiensi jelajah pesawat sayap tetap sejauh <strong>60 km</strong> dan durasi terbang <strong>60–120 menit</strong>.',
            'sections' => [
                [
                    'h2' => 'Akuisisi Koridor & Blok Luas Tanpa Runway Khusus',
                    'p'  => 'Di area tambang terbuka, koridor hutan, maupun kawasan pesisir yang tidak memiliki jalur pendaratan datar, empat rotor VTOL menerbangkan DELTAV secara vertikal sebelum motor pusher mengambil alih pada kecepatan jelajah 15–22 m/s. Satu sortie sanggup mencakup area pemetaan jauh lebih luas dibandingkan multirotor konvensional.',
                ],
                [
                    'h2' => 'Fleksibilitas Integrasi Sensor Pemetaan 1 – 2 kg',
                    'p'  => 'Kompartemen payload berkapasitas 1–2 kg siap mengakomodasi kamera RGB metrik beresolusi tinggi, sensor multispektral untuk analisis vegetasi kebun, maupun unit LiDAR ringan sesuai kebutuhan proyek survei topografi dan pemodelan DTM/DSM.',
                ],
                [
                    'h2' => 'Keunggulan Operasional untuk Konsultan GIS & Proyek Pertambangan',
                    'p'  => 'Melalui FDS Station berbahasa Indonesia, pilot menentukan batas blok terbang, persentase overlap citra, serta titik pendaratan darurat (Return to Launch) guna menghasilkan data mentah fotogrametri siap olah menjadi ortofoto dan peta kontur.',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'Apa perbedaan utama DELTAV dibandingkan drone multirotor untuk pemetaan?',
                    'a' => 'DELTAV mampu terbang jauh lebih lama (60–120 menit) dengan jangkauan hingga 60 km berkat daya angkat aerodinamis sayap tetap, namun tetap lepas landas dan mendarat vertikal (VTOL) di ruang sempit.',
                ],
                [
                    'q' => 'Sensor apa saja yang dapat dipasang pada kompartemen payload DELTAV?',
                    'a' => 'Payload 1–2 kg pada DELTAV mendukung kamera fotogrametri RGB 24–42 MP, sensor multispektral untuk perkebunan, maupun perangkat LiDAR ringan.',
                ],
                [
                    'q' => 'Berapa bentang sayap dan kecepatan jelajah DELTAV?',
                    'a' => 'DELTAV memiliki bentang sayap 2.000 mm dengan kecepatan jelajah misi 15–22 m/s di bawah pengendalian otonom FDS Station.',
                ],
                [
                    'q' => 'Apakah FDS juga menyediakan jasa survei menggunakan DELTAV selain pembelian unit?',
                    'a' => 'Ya. Selain pengadaan unit, tim geospasial FDS menyediakan layanan jasa foto udara, survei LiDAR, dan pengolahan peta GIS untuk pertambangan, kehutanan, serta tata ruang.',
                ],
            ],
        ],
        'multipurpose' => [
            'lead' => '<strong>MULTIPURPOSE</strong> dirancang sebagai platform UAV multirotor modular dengan kapasitas angkut hingga <strong>5 kg</strong> dan durasi terbang <strong>15–30 menit</strong>, memungkinkan pertukaran cepat antara kamera termal inframerah, optical zoom jarak jauh, dan sensor LiDAR inspeksi.',
            'sections' => [
                [
                    'h2' => 'Inspeksi Aset Kritis Energi & Industri Tanpa Shutdown',
                    'p'  => 'Dalam pemeriksaan jaringan transmisi listrik 150 kV, panel surya (Solar PV), flare stack kilang migas, maupun struktur jembatan, inspeksi konvensional sering menuntut pemadaman sementara serta pemasangan perancah berisiko tinggi. MULTIPURPOSE membawa kamera termal inframerah dan zoom optik menjaga jarak aman medan magnetik sambil merekam anomali panas.',
                ],
                [
                    'h2' => 'Arsitektur Payload Modular untuk Beragam Misi Khusus',
                    'p'  => 'Sistem mounting terbuka memungkinkan instansi riset, tim SAR kebencanaan, maupun operator infrastruktur mengganti modul payload 5 kg sesuai target operasi harian—mulai dari patroli malam, pemetaan koridor utilitas jarak dekat, hingga pengamatan visual detail sambungan tower.',
                ],
                [
                    'h2' => 'Kontrol Presisi & Fail-Safe di FDS Station',
                    'p'  => 'Didukung baterai 8.000 mAh dan rentang kecepatan terbang terkontrol 2–6 m/s, pilot dapat memanfaatkan mode manual untuk manuver dekat struktur maupun Task Following otonom berdasar koordinat aset.',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'Sensor apa saja yang kompatibel dengan payload 5 kg pada drone MULTIPURPOSE?',
                    'a' => 'MULTIPURPOSE dapat dikonfigurasi dengan kamera termal inframerah, kamera optical zoom 20x, sensor LiDAR ringan, serta perangkat sensor inspeksi atau pencahayaan taktis kustom.',
                ],
                [
                    'q' => 'Apakah drone MULTIPURPOSE aman digunakan untuk inspeksi SUTET 150 kV?',
                    'a' => 'Ya. Penggunaan zoom optik dan kamera termal memungkinkan operator melakukan pendataan hotspot dan kondisi isolator dari jarak aman tanpa memutus aliran listrik atau memanjat menara.',
                ],
                [
                    'q' => 'Berapa durasi terbang rata-rata untuk satu misi inspeksi?',
                    'a' => 'Durasi terbang berkisar 15–30 menit per misi bergantung pada total bobot payload sensor dan kondisi angin di area inspeksi.',
                ],
                [
                    'q' => 'Apakah konfigurasi payload bisa disesuaikan dengan kebutuhan instansi kami?',
                    'a' => 'Bisa. Tim engineering PT Karya Solusi Angkasa melayani konsultasi kustomisasi payload dan integrasi sensor melalui halaman kontak resmi.',
                ],
            ],
        ],
        'delfro' => [
            'lead' => '<strong>DELFRO</strong> merupakan wahana UAV kargo logistik otonom dengan daya angkut <strong>3 hingga 10 kg</strong> pada kompartemen kotak terintegrasi berukuran <strong>20 × 20 × 30 cm</strong> dan bobot lepas landas maksimum (MTOW) <strong>15 kg</strong>, dibangun untuk pengiriman cepat menembus batas geografis.',
            'sections' => [
                [
                    'h2' => 'Solusi Rantai Pasok Medis & Wilayah Sulit Akses 3T',
                    'p'  => 'Distribusi sampel laboratorium medis, vaksin, perlengkapan P3K darurat bencana, maupun suku cadang kecil sering terhambat jalur darat terputus, sungai lebar, atau kontur perbukitan. DELFRO menerbangkan muatan langsung di udara pada kecepatan jelajah 2–6 m/s menggunakan propeller karbon 18 inci untuk efisiensi angkut stabil.',
                ],
                [
                    'h2' => 'Penerbangan Waypoint Terjadwal & Manajemen Rute',
                    'p'  => 'Dengan durasi penerbangan 10–15 menit antar titik pendaratan terkoordinat, operator cukup menentukan rute aman pada perangkat lunak FDS Station Logistics Management. Selama pengiriman berlangsung, sistem telemetri real-time mencatat posisi wahana serta mengaktifkan Return to Home fail-safe bila terjadi gangguan sinyal.',
                ],
                [
                    'h2' => 'Integrasi Logistik Lapangan Tambang & Offshore Terdekat',
                    'p'  => 'Selain logistik kemanusiaan, DELFRO mempercepat perpindahan komponen ukur maupun perlengkapan ringan antara gudang utama site tambang menuju pos pengawasan terpencil tanpa mengandalkan kendaraan lapangan bermuatan besar.',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'Berapa berat muatan dan ukuran kompartemen kargo DELFRO?',
                    'a' => 'DELFRO mengangkut muatan efektif 3 hingga 10 kg di dalam kompartemen berukuran 20 × 20 × 30 cm, dengan berat lepas landas maksimum (MTOW) 15 kg.',
                ],
                [
                    'q' => 'Jenis muatan apa yang ideal dikirim menggunakan DELFRO?',
                    'a' => 'DELFRO ideal untuk sampel medis, obat-obatan darurat bencana, perlengkapan komunikasi lapangan, dan suku cadang industri berukuran ringkas di area sulit dijangkau kendaraan darat.',
                ],
                [
                    'q' => 'Apakah DELFRO dapat terbang otomatis antara dua lokasi tetap?',
                    'a' => 'Ya. DELFRO menjalankan penerbangan rute waypoint otomatis menggunakan FDS Station dengan opsi kendali manual dan prosedur Fail-Safe.',
                ],
                [
                    'q' => 'Apakah kompartemen kargo dapat disesuaikan untuk wadah khusus (misalnya coolbox medis)?',
                    'a' => 'Dimensi kotak payload dapat didiskusikan dengan tim engineering FDS apabila instansi membutuhkan mekanisme pengunci atau pelindung termal khusus.',
                ],
            ],
        ],
    ];

    if (isset($guides[$key])) {
        return $guides[$key];
    }

    // Dynamic fallback untuk model kustom tambahan
    $dynamic_specs = array_filter([
        $kapasitas !== '' ? "kapasitas payload {$kapasitas}" : '',
        $durasi !== '' ? "durasi terbang {$durasi}" : '',
        $baterai !== '' ? "sistem daya {$baterai}" : '',
        $produktiv !== '' ? "produktivitas/jangkauan {$produktiv}" : '',
    ]);
    $spec_line = !empty($dynamic_specs) ? implode(', ', $dynamic_specs) : 'spesifikasi operasional industrial';

    return [
        'lead' => "<strong>{$model_base}</strong> merupakan bagian dari lini pesawat udara tanpa awak (UAV) kategori <a href=\"" . esc_url($cat_url) . "\">" . esc_html($kategori) . "</a> buatan <strong>PT Karya Solusi Angkasa (Full Drone Solutions)</strong> di Sleman, Yogyakarta dengan {$spec_line}.",
        'sections' => [
            [
                'h2' => "Kesiapan Operasional & Kendali FDS Station pada {$model_base}",
                'p'  => ($desc !== '' ? $desc . ' ' : '') . "Mengusung sistem navigasi {$otonomi} pada platform kendali {$gcs}, {$model_base} dirancang untuk membantu tim lapangan menyelesaikan misi secara terukur dan aman.",
            ],
            [
                'h2' => 'Konsultasi Pengadaan & Layanan Terpadu FDS',
                'p'  => "Seluruh lini wahana di <a href=\"" . esc_url($produk_url) . "\">Katalog Produk Drone FDS</a> dapat dibandingkan spesifikasinya di halaman <a href=\"" . esc_url($compare_url) . "\">Bandingkan Model Drone</a> atau dikonsultasikan bersama tim engineering melalui <a href=\"" . esc_url($contact_url) . "\">Kontak Resmi FDS</a> dan unit <a href=\"" . esc_url($layanan_url) . "\">Layanan Lapangan FDS</a>.",
            ],
        ],
        'faqs' => [
            [
                'q' => "Apa fungsi utama wahana {$model_base} dari Full Drone Solutions?",
                'a' => "{$model_base} dikembangkan untuk mendukung misi kategori {$kategori} dengan dukungan navigasi {$otonomi} serta kendali {$gcs}.",
            ],
            [
                'q' => "Bagaimana dukungan purna jual dan pelatihan operator untuk {$model_base}?",
                'a' => 'Setiap pengadaan didukung garansi resmi manufaktur dari workshop PT Karya Solusi Angkasa di Sleman serta pelatihan operasional operator sebelum serah terima lapangan.',
            ],
        ],
    ];
}

/**
 * Generate Clean, Non-Duplicated 250-400w Model Field Guide + Visible FAQ HTML for Drone CPT
 * Per Final Decisions A2-A6: NO bottom duplicate specs table, NO certification section, NO unverified IP/SNI/TKDN.
 */
function fds_generate_drone_seo_content($post_id) {
    $decode = function ($val) {
        return function_exists(__NAMESPACE__ . '\\fds_decode_text')
            ? fds_decode_text((string) $val)
            : trim(html_entity_decode(wp_specialchars_decode((string) $val, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    };

    $title      = $decode(get_the_title($post_id));
    $model_base = trim((string) preg_replace('/\s*\(.*?\)/', '', $title)) ?: $title;
    $guide      = fds_get_drone_model_guide_and_faqs($post_id);

    $compare_url = home_url('/bandingkan/');
    $produk_url  = home_url('/produk/');
    $contact_url = home_url('/kontak/#hubungi-form');

    $html   = [];
    $html[] = '<div class="fds-seo-article">';
    $html[] = '<p class="lead">' . wp_kses_post($guide['lead']) . '</p>';

    foreach ($guide['sections'] as $sec) {
        $html[] = '<h2>' . esc_html($sec['h2']) . '</h2>';
        $html[] = '<p>' . wp_kses_post($sec['p']) . '</p>';
    }

    $html[] = '<p>Tinjau perbandingan spesifikasi lintas lini di <a href="' . esc_url($compare_url) . '">Bandingkan Spesifikasi Drone</a>, telusuri <a href="' . esc_url($produk_url) . '">Katalog Lengkap UAV FDS</a>, atau jadwalkan demo teknis di halaman <a href="' . esc_url($contact_url) . '">Hubungi Tim Engineering</a>.</p>';

    if (!empty($guide['faqs'])) {
        $html[] = '<h2>Pertanyaan Teknis Seputar ' . esc_html($model_base) . '</h2>';
        $html[] = '<div class="fds-seo-faq-group">';
        foreach ($guide['faqs'] as $idx => $faq) {
            $open_attr = $idx === 0 ? ' open' : '';
            $html[]    = '<details class="fds-faq-item"' . $open_attr . '>';
            $html[]    = '<summary>' . esc_html($faq['q']) . '</summary>';
            $html[]    = '<div class="faq-answer"><p>' . esc_html($faq['a']) . '</p></div>';
            $html[]    = '</details>';
        }
        $html[] = '</div>';
    }

    $html[] = '</div>';

    return implode("\n", $html);
}

/**
 * Synchronize Rank Math SEO Meta Keys & Generate Clean Field Guide post_content for Drone CPT
 */
function fds_sync_drone_rank_math_seo($post_id, bool $force_content = false, bool $force_meta = false) {
    if (!$post_id || get_post_type($post_id) !== 'drone') {
        return;
    }

    $decode = function ($val) {
        return function_exists(__NAMESPACE__ . '\\fds_decode_text')
            ? fds_decode_text((string) $val)
            : trim(html_entity_decode(wp_specialchars_decode((string) $val, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    };

    $key        = fds_get_drone_model_key($post_id);
    $title      = $decode(get_the_title($post_id));
    $model_base = trim((string) preg_replace('/\s*\(.*?\)/', '', $title)) ?: $title;
    $kategori   = $decode(get_post_meta($post_id, 'drone_kategori', true) ?: 'UAV Industri');
    $payload    = $decode(get_post_meta($post_id, 'drone_spec_kapasitas', true));
    $durasi     = $decode(get_post_meta($post_id, 'drone_spec_durasi', true));
    $permalink  = get_permalink($post_id);

    // 1. Update post_content hanya bila kosong atau eksplisit diminta oleh migrasi
    $current_content = get_post_field('post_content', $post_id);
    if ($force_content || empty(trim((string) $current_content))) {
        $rich_content = fds_generate_drone_seo_content($post_id);

        remove_action('save_post_drone', __NAMESPACE__ . '\\fds_save_post_drone_handler', 10);
        wp_update_post([
            'ID'           => $post_id,
            'post_content' => $rich_content,
        ]);
        add_action('save_post_drone', __NAMESPACE__ . '\\fds_save_post_drone_handler', 10);
    }

    // 2. Scope Title, Description, Focus Keywords akurat per model (TKDN hanya seri FERTO; SNI 9199:2023 hanya FERTO 15 & 22)
    $cat_lower    = strtolower($kategori);
    $model_lower  = strtolower($model_base);
    $is_ferto     = (strpos($key, 'ferto-') === 0);
    $has_sni      = in_array($key, ['ferto-15l', 'ferto-22l'], true);
    $specs_parts  = array_filter([
        $payload !== '' ? "kapasitas {$payload}" : '',
        $durasi !== '' ? "durasi {$durasi}" : '',
    ]);
    $specs_phrase = !empty($specs_parts) ? implode(', ', $specs_parts) : 'performa operasional tinggi';

    if ($is_ferto || strpos($cat_lower, 'agri') !== false) {
        if ($has_sni) {
            $focus_kw  = "{$model_lower}, drone pertanian {$model_lower}, jual drone pertanian, drone sprayer tkdn sni, produsen uav indonesia";
            $raw_title = "{$model_base} — Drone Pertanian TKDN & SNI | FDS";
            $raw_desc  = "Drone pertanian {$model_base} buatan PT Karya Solusi Angkasa (FDS) dengan {$specs_phrase}, sertifikat TKDN 60,74%, SNI 9199:2023, dan FDS Station.";
        } elseif ($is_ferto) {
            $focus_kw  = "{$model_lower}, drone pertanian {$model_lower}, jual drone pertanian, drone sprayer tkdn, produsen uav indonesia";
            $raw_title = "{$model_base} — Drone Pertanian Presisi TKDN | FDS";
            $raw_desc  = "Drone pertanian {$model_base} buatan PT Karya Solusi Angkasa (FDS) dengan {$specs_phrase}, sertifikasi TKDN 60,74%, dan kendali FDS Station.";
        } else {
            $focus_kw  = "{$model_lower}, drone pertanian {$model_lower}, jual drone pertanian, produsen uav indonesia";
            $raw_title = "{$model_base} — Drone Pertanian Presisi | FDS";
            $raw_desc  = "Drone pertanian {$model_base} buatan PT Karya Solusi Angkasa (FDS) dengan {$specs_phrase} dan kendali FDS Station Bahasa Indonesia.";
        }
    } elseif ($key === 'deltav' || strpos($cat_lower, 'peta') !== false) {
        $focus_kw  = "{$model_lower}, drone mapping {$model_lower}, fixed wing vtol indonesia, jasa survey drone lidar, produsen uav indonesia";
        $raw_title = "{$model_base} — Drone Mapping Fixed-Wing VTOL | FDS";
        $raw_desc  = "Drone mapping Fixed-Wing VTOL {$model_base} ({$specs_phrase}) buatan PT Karya Solusi Angkasa untuk ortofoto area luas, survei LiDAR, dan pemetaan GIS.";
    } elseif ($key === 'delfro' || strpos($cat_lower, 'kargo') !== false) {
        $focus_kw  = "{$model_lower}, drone kargo {$model_lower}, drone logistik medis, drone payload besar, produsen uav indonesia";
        $raw_title = "{$model_base} — Drone Kargo Logistik Otonom | FDS";
        $raw_desc  = "Drone kargo logistik {$model_base} ({$specs_phrase}) buatan PT Karya Solusi Angkasa untuk distribusi medis darurat dan suku cadang ke wilayah sulit akses.";
    } else {
        $focus_kw  = "{$model_lower}, drone inspeksi {$model_lower}, drone thermal inframerah, drone surveillance industri, produsen uav indonesia";
        $raw_title = "{$model_base} — Drone Inspeksi & Surveillance Modular | FDS";
        $raw_desc  = "Drone inspeksi modular {$model_base} ({$specs_phrase}) buatan PT Karya Solusi Angkasa. Mendukung sensor termal inframerah, optical zoom & LiDAR.";
    }

    $seo_title = function_exists(__NAMESPACE__ . '\\fds_clamp_meta_title') ? fds_clamp_meta_title($raw_title, 60) : $raw_title;
    $seo_desc  = function_exists(__NAMESPACE__ . '\\fds_clamp_meta_desc') ? fds_clamp_meta_desc($raw_desc, 158) : $raw_desc;

    // 3. Populate Rank Math Specific Post Meta Fields
    if ($force_meta || !get_post_meta($post_id, 'rank_math_focus_keyword', true)) {
        update_post_meta($post_id, 'rank_math_focus_keyword', $focus_kw);
    }
    if ($force_meta || !get_post_meta($post_id, 'rank_math_title', true)) {
        update_post_meta($post_id, 'rank_math_title', $seo_title);
    }
    if ($force_meta || !get_post_meta($post_id, 'rank_math_description', true)) {
        update_post_meta($post_id, 'rank_math_description', $seo_desc);
    }
    if ($force_meta || !get_post_meta($post_id, 'rank_math_canonical_url', true)) {
        update_post_meta($post_id, 'rank_math_canonical_url', $permalink);
    }
    if ($force_meta || !get_post_meta($post_id, 'rank_math_facebook_title', true)) {
        update_post_meta($post_id, 'rank_math_facebook_title', $seo_title);
    }
    if ($force_meta || !get_post_meta($post_id, 'rank_math_facebook_description', true)) {
        update_post_meta($post_id, 'rank_math_facebook_description', $seo_desc);
    }
    if ($force_meta || !get_post_meta($post_id, 'rank_math_twitter_title', true)) {
        update_post_meta($post_id, 'rank_math_twitter_title', $seo_title);
    }
    if ($force_meta || !get_post_meta($post_id, 'rank_math_twitter_description', true)) {
        update_post_meta($post_id, 'rank_math_twitter_description', $seo_desc);
    }
    update_post_meta($post_id, 'rank_math_pillar_content', 'on');
    update_post_meta($post_id, 'rank_math_robots', ['index']);
    update_post_meta($post_id, 'rank_math_advanced_robots', [
        'max-snippet'       => '-1',
        'max-video-preview' => '-1',
        'max-image-preview' => 'large',
    ]);

    // Nonaktifkan rich snippet Product kosong (B2B tanpa harga publik/ulasan) agar tidak memicu error Merchant/Product Snippets di GSC
    $existing_snippet = get_post_meta($post_id, 'rank_math_rich_snippet', true);
    if ($existing_snippet === '' || $existing_snippet === 'product') {
        update_post_meta($post_id, 'rank_math_rich_snippet', 'off');
    }
    delete_post_meta($post_id, 'rank_math_snippet_product_name');
    delete_post_meta($post_id, 'rank_math_snippet_product_desc');
    delete_post_meta($post_id, 'rank_math_snippet_product_currency');
    delete_post_meta($post_id, 'rank_math_snippet_product_instock');

    // Yoast SEO Fallback Fields
    if ($force_meta || !get_post_meta($post_id, '_yoast_wpseo_focuskw', true)) {
        update_post_meta($post_id, '_yoast_wpseo_focuskw', explode(',', $focus_kw)[0]);
    }
    if ($force_meta || !get_post_meta($post_id, '_yoast_wpseo_title', true)) {
        update_post_meta($post_id, '_yoast_wpseo_title', $seo_title);
    }
    if ($force_meta || !get_post_meta($post_id, '_yoast_wpseo_metadesc', true)) {
        update_post_meta($post_id, '_yoast_wpseo_metadesc', $seo_desc);
    }
    if ($force_meta || !get_post_meta($post_id, '_yoast_wpseo_canonical', true)) {
        update_post_meta($post_id, '_yoast_wpseo_canonical', $permalink);
    }
}

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
            'content'   => 'FERTO 5 didesain sebagai platform multirotor modular dengan mobilitas tinggi untuk menjangkau area berbukit, terasering, dan lahan perkebunan dengan kontur ekstrem. Dilengkapi fitur terrain-following otomatis dan sistem kendali FDS Station, drone ini menjamin presisi penyemprotan pupuk cair maupun pestisida secara merata dengan produktivitas 1 Ha per jam.',
            'stat1_n'   => '5 L', 'stat1_l' => 'Kapasitas Tangki',
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
            'sp_gcs'    => 'FDS Station (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP 60,74% | ISO 9001:2015',
            'uc1_t'     => 'Lahan berbukit & terasering', 'uc1_d' => 'Bobot ringan dan dimensi ringkas mempermudah manuver di area sempit.',
            'uc2_t'     => 'Petani hortikultura & kebun', 'uc2_d' => 'Aplikasi cairan merata dengan pengurangan pemborosan larutan kerja.',
            'uc3_t'     => 'Penyedia jasa semprot mandiri', 'uc3_d' => 'Mobilitas tinggi mudah dibawa ke petak sawah perbukitan.',
            'uc4_t'     => 'Dukungan purna jual resmi', 'uc4_d' => 'Jaringan servis dan suku cadang asli lokal FDS siap pakai.',
        ],
        'ferto-10l' => [
            'title'     => 'FERTO 10 (10 Liter)',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Terlaris',
            'tagline'   => 'Drone Pertanian FERTO 10 — Pilihan andal kelompok tani dengan produktivitas seimbang.',
            'content'   => 'FERTO 10 adalah varian andalan FDS yang menawarkan keseimbangan optimal antara kapasitas muatan 10 liter, ketahanan baterai 16.000 mAh, dan produktivitas 1 - 1,5 Ha/jam. Menggunakan rangka karbon komposit buatan dalam negeri bersertifikat TKDN 60,74%, drone ini mendukung modernisasi penyemprotan pertanian di berbagai wilayah Indonesia.',
            'stat1_n'   => '10 L', 'stat1_l' => 'Kapasitas Tangki',
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
            'sp_gcs'    => 'FDS Station (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP 60,74% | ISO 9001:2015',
            'uc1_t'     => 'Kelompok tani & Gapoktan', 'uc1_d' => 'Titik temu seimbang antara kapasitas operasional dan efisiensi investasi.',
            'uc2_t'     => 'Koperasi pertanian', 'uc2_d' => 'Membantu mengatasi keterbatasan tenaga kerja semprot manual di musim tanam.',
            'uc3_t'     => 'Program ketahanan pangan', 'uc3_d' => 'Mendukung penyemprotan tanaman pangan terukur secara otonom.',
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
            'stat4_n'   => '17 L', 'stat4_l' => 'Kapasitas Tangki',
            'sp_kap'    => '17 Liter (15 – 17 Liter)',
            'sp_dur'    => '15 – 25 menit',
            'sp_bat'    => '16.000 mAh',
            'sp_prod'   => '8 Ha / jam',
            'sp_kec'    => '2 – 6 m/s',
            'sp_ket'    => '',
            'sp_oto'    => 'Otonom & Manual, Terrain Following, Fail-Safe',
            'sp_gcs'    => 'FDS Station (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP 60,74% | SNI 9199:2023 | ISO 9001:2015',
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
            'content'   => 'FERTO 22 adalah varian enterprise andalan FDS untuk industri perkebunan sawit, tebu, dan tanaman industri berskala ribuan hektare. Ditenagai baterai 22.000 mAh dengan kecepatan jelajah 5,24 m/s, drone ini mampu menuntaskan 8,5 hektare per jam secara otonom dan terintegrasi penuh ke dalam sistem FDS Station.',
            'stat1_n'   => 'SNI', 'stat1_l' => 'SNI 9199:2023',
            'stat2_n'   => '60,74%', 'stat2_l' => 'TKDN + BMP',
            'stat3_n'   => '8,5 Ha/j', 'stat3_l' => 'Produktivitas Semprot',
            'stat4_n'   => '22 L', 'stat4_l' => 'Kapasitas Tangki',
            'sp_kap'    => '22 Liter',
            'sp_dur'    => '20 – 25 menit',
            'sp_bat'    => '22.000 mAh',
            'sp_prod'   => '8,5 Ha / jam',
            'sp_kec'    => '5,24 m/s',
            'sp_ket'    => '',
            'sp_oto'    => 'Semi-to-Fully Autonomous, Terrain Following, Fail-Safe',
            'sp_gcs'    => 'FDS Station (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP 60,74% | SNI 9199:2023 | ISO 9001:2015',
            'uc1_t'     => 'Perkebunan sawit & tanaman industri', 'uc1_d' => 'Mengatasi tantangan lahan luas dengan kecepatan semprot tinggi.',
            'uc2_t'     => 'BUMN Perkebunan & Korporasi Agrikultur', 'uc2_d' => 'Memenuhi syarat pengadaan pemerintah dengan TKDN+BMP & SNI 9199:2023.',
            'uc3_t'     => 'Manajemen armada perkebunan', 'uc3_d' => 'Terintegrasi dengan analitik kesehatan tanaman berbasis NDVI.',
            'uc4_t'     => 'Purna jual resmi & garansi lokal', 'uc4_d' => 'Layanan servis dan suku cadang asli tanpa ketergantungan impor.',
        ],
        'ferto-30l' => [
            'title'     => 'FERTO 30 (30 Liter)',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Heavy Duty',
            'tagline'   => 'Drone Pertanian FERTO 30 — Kapasitas muat masif 30L dengan produktivitas 15 Ha/jam.',
            'content'   => 'FERTO 30 menghadirkan lompatan kapasitas tangki 30 Liter yang dirancang untuk kebutuhan agribisnis skala masif. Dengan sistem baterai high-capacity 28.000 mAh dan daya jangkau penerbangan hingga 30 menit, drone ini mampu menghasilkan produktivitas 15 Ha per jam, memangkas waktu kerja dan biaya operasional secara drastis.',
            'stat1_n'   => '30 L', 'stat1_l' => 'Kapasitas Tangki',
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
            'sp_gcs'    => 'FDS Station (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP 60,74% | ISO 9001:2015',
            'uc1_t'     => 'Mega perkebunan sawit & tebu', 'uc1_d' => 'Produktivitas 15 Ha/jam mempercepat target penyemprotan harian.',
            'uc2_t'     => 'Aplikasi pupuk & pestisida volume tinggi', 'uc2_d' => 'Tangki 30L meminimalkan frekuensi pendaratan untuk isi ulang.',
            'uc3_t'     => 'Pengendalian hama serentak', 'uc3_d' => 'Menuntaskan ratusan hektare lahan dalam waktu singkat sebelum hama menyebar.',
            'uc4_t'     => 'Konstruksi karbon komposit kokoh', 'uc4_d' => 'Dirancang untuk kerja berat lapangan dengan komponen siap pakai lokal.',
        ],
        'ferto-50l' => [
            'title'     => 'FERTO 50 (50 Liter)',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Ultra Capacity',
            'tagline'   => 'Drone Pertanian FERTO 50 — Kapasitas puncak 50L untuk produktivitas agrikultur tinggi.',
            'content'   => 'FERTO 50 adalah platform UAV agrikultur dengan muatan tertinggi di lini FDS. Membawa tangki berkapasitas 50 Liter dengan sistem propulsi bertenaga tinggi dan kecepatan jelajah hingga 6 m/s, drone ini diciptakan untuk menjawab tantangan operasional perkebunan agrikultur besar di Indonesia dengan efisiensi maksimal.',
            'stat1_n'   => '50 L', 'stat1_l' => 'Payload Maksimum',
            'stat2_n'   => '60,74%', 'stat2_l' => 'TKDN + BMP',
            'stat3_n'   => '15 Ha/j', 'stat3_l' => 'Produktivitas Semprot',
            'stat4_n'   => 'Garansi', 'stat4_l' => 'Purna Jual Resmi',
            'sp_kap'    => '50 Liter',
            'sp_dur'    => '20 – 30 menit',
            'sp_bat'    => '28.000 mAh',
            'sp_prod'   => '15 Ha / jam',
            'sp_kec'    => '6 m/s',
            'sp_ket'    => '',
            'sp_oto'    => 'Semi-to-Fully Autonomous, Terrain Following, Fail-Safe',
            'sp_gcs'    => 'FDS Station (Bahasa Indonesia)',
            'sp_sert'   => 'TKDN + BMP 60,74% | ISO 9001:2015',
            'uc1_t'     => 'Perkebunan konglomerasi & agroindustri', 'uc1_d' => 'Menangani area ribuan hektare dengan armada efisien.',
            'uc2_t'     => 'Penyebaran pupuk & pestisida intensif', 'uc2_d' => 'Muatan 50L memaksimalkan efisiensi setiap sorti penerbangan.',
            'uc3_t'     => 'Misi otomatis skala besar', 'uc3_d' => 'Perencanaan rute cerdas dan pemantauan real-time via FDS Station.',
            'uc4_t'     => 'Dukungan logistik & purna jual komprehensif', 'uc4_d' => 'Paket perawatan berkala dan penyediaan suku cadang resmi.',
        ],
        'deltav' => [
            'title'     => 'DELTAV (Fixed-Wing VTOL)',
            'kategori'  => 'Pemetaan & GIS',
            'badge'     => 'Hybrid VTOL',
            'tagline'   => 'Platform UAV Pemetaan Fixed-Wing VTOL Hybrid — Jangkauan 60 km untuk akuisisi geospasial area luas.',
            'content'   => 'DELTAV adalah pesawat UAV fixed-wing berteknologi Hybrid VTOL (Vertical Takeoff and Landing) yang menggabungkan kemudahan lepas landas tegak lurus tanpa landasan pacu dengan kecepatan jelajah serta efisiensi aerodinamis pesawat sayap tetap. Dengan jangkauan hingga 60 km dan durasi terbang 60-120 menit, DELTAV adalah solusi tepat untuk survei topografi, ortofoto beresolusi tinggi, pemetaan kehutanan, dan akuisisi data geospasial area luas.',
            'stat1_n'   => '2.000mm', 'stat1_l' => 'Bentang Sayap',
            'stat2_n'   => '60 km', 'stat2_l' => 'Jangkauan Misi',
            'stat3_n'   => '120 min', 'stat3_l' => 'Durasi Terbang Maks',
            'stat4_n'   => 'VTOL', 'stat4_l' => 'Lepas Landas Vertikal',
            'sp_kap'    => 'Bentang Sayap: 2.000 mm | Payload: 1 – 2 kg',
            'sp_dur'    => '60 – 120 menit',
            'sp_bat'    => 'LiPo High Density Cruise Battery',
            'sp_prod'   => 'Jangkauan Misi: Hingga 60 km',
            'sp_kec'    => '15 – 22 m/s',
            'sp_ket'    => 'Rangka Komposit Karbon Hibrida',
            'sp_oto'    => 'Semi-to-Fully Autonomous, Waypoint Mission, Return to Launch',
            'sp_gcs'    => 'FDS Station GCS (Bahasa Indonesia)',
            'sp_sert'   => 'ISO 9001:2015',
            'uc1_t'     => 'Survei topografi & konstruksi', 'uc1_d' => 'Mempercepat pemodelan 3D, ortomozaik, dan perhitungan cut & fill secara signifikan.',
            'uc2_t'     => 'Kehutanan & lingkungan', 'uc2_d' => 'Pemetaan DAS, tutupan kanopi hutan, dan progres reklamasi tambang.',
            'uc3_t'     => 'Pertambangan & kuari', 'uc3_d' => 'Pemetaan kontur presisi dan pemantauan batas konsesi tambang.',
            'uc4_t'     => 'Perencanaan tata ruang & GIS nasional', 'uc4_d' => 'Akurasi data geospasial sub-sentimeter siap integrasi CAD & BIM.',
        ],
        'multipurpose' => [
            'title'     => 'MULTIPURPOSE (UAV Kustom & Inspeksi)',
            'kategori'  => 'Pemetaan & GIS',
            'badge'     => 'Modular UAV',
            'tagline'   => 'Platform UAV Modular Serbaguna — Integrasi payload termal, optical zoom, & sensor inspeksi.',
            'content'   => 'MULTIPURPOSE dirancang sebagai platform UAV modular yang fleksibel untuk berbagai misi kustom. Mampu mengangkut payload hingga 5 kg dengan integrasi berbagai sensor seperti kamera termal inframerah, optik zoom 20x, hingga sensor LiDAR. Sangat andal untuk inspeksi aset kritikal seperti jaringan transmisi listrik 150 kV, ladang panel surya, tangki minyak & gas, serta struktur jembatan dan gedung tinggi.',
            'stat1_n'   => '5 kg', 'stat1_l' => 'Payload Maksimum',
            'stat2_n'   => '30 min', 'stat2_l' => 'Durasi Terbang Maks',
            'stat3_n'   => 'Termal/AI', 'stat3_l' => 'Sensor Kompatibel',
            'stat4_n'   => '150 kV', 'stat4_l' => 'Inspeksi Aset Kritikal',
            'sp_kap'    => '5 kg (Sensor Termal, LiDAR, Optical Zoom)',
            'sp_dur'    => '15 – 30 menit',
            'sp_bat'    => '8.000 mAh',
            'sp_prod'   => 'Inspeksi Jalur Transmisi & Area Industri',
            'sp_kec'    => '2 – 6 m/s',
            'sp_ket'    => 'Konstruksi Modular & Fail-Safe Mandiri',
            'sp_oto'    => 'Manual, Task Following, Semi-to-Fully Autonomous',
            'sp_gcs'    => 'FDS Station Real-Time Monitoring & AI Analytics',
            'sp_sert'   => 'ISO 9001:2015',
            'uc1_t'     => 'Inspeksi transmisi listrik 150 kV', 'uc1_d' => 'Aman tanpa perlu pemadaman listrik dan bebas risiko bekerja di ketinggian.',
            'uc2_t'     => 'Inspeksi ladang energi surya (Solar PV)', 'uc2_d' => 'Deteksi dini hotspot dan sel rusak untuk cegah kehilangan energi.',
            'uc3_t'     => 'Inspeksi migas & cerobong suar (Flare)', 'uc3_d' => 'Deteksi kebocoran dan anomali termal tanpa mematikan kilang.',
            'uc4_t'     => 'Inspeksi struktur jembatan & infrastruktur', 'uc4_d' => 'Pemeriksaan visual mendetail struktur beton dan baja.',
        ],
        'delfro' => [
            'title'     => 'DELFRO (Drone Kargo & Logistik)',
            'kategori'  => 'Kargo',
            'badge'     => 'Logistics UAV',
            'tagline'   => 'Platform UAV Kargo Logistik Ringan — Distribusi logistik cepat dan aman ke area sulit dijangkau.',
            'content'   => 'DELFRO adalah drone kargo otonom yang dikembangkan khusus untuk distribusi logistik ringan yang cepat, efisien, dan aman. Dengan kapasitas angkut 3 hingga 10 kg dan kompartemen kargo berukuran 20 x 20 x 30 cm, DELFRO menjadi solusi praktis untuk pengiriman sampel medis, pasokan darurat kebencanaan, suku cadang penting, dan logistik ekspres ke wilayah kepulauan atau daerah terisolir yang sulit dijangkau transportasi darat.',
            'stat1_n'   => '10 kg', 'stat1_l' => 'Payload Maksimum',
            'stat2_n'   => '15 kg', 'stat2_l' => 'MTOW Maksimum',
            'stat3_n'   => '18"', 'stat3_l' => 'Carbon Propeller',
            'stat4_n'   => 'Auto', 'stat4_l' => 'Waypoint Route',
            'sp_kap'    => '3 – 10 kg (Kotak Logistik 20 x 20 x 30 cm)',
            'sp_dur'    => '10 – 15 menit',
            'sp_bat'    => 'LiPo High Density Cargo Battery',
            'sp_prod'   => 'Pengiriman Logistik Antar Titik Waypoint',
            'sp_kec'    => '2 – 6 m/s',
            'sp_ket'    => 'MTOW: 15 kg | Propeller Karbon 18-inch',
            'sp_oto'    => 'Otonom (Waypoint Cargo Route) & Manual Fail-Safe',
            'sp_gcs'    => 'FDS Station Logistics Management',
            'sp_sert'   => 'ISO 9001:2015',
            'uc1_t'     => 'Logistik medis & darurat bencana', 'uc1_d' => 'Pengiriman cepat obat-obatan, darah, dan sampel medis ke lokasi terpencil.',
            'uc2_t'     => 'Pengiriman suku cadang industri', 'uc2_d' => 'Menghubungkan site terpencil dengan warehouse secara cepat.',
            'uc3_t'     => 'Ekspedisi & kurir last-mile', 'uc3_d' => 'Alternatif logistik untuk melintasi sungai, bukit, atau selat.',
            'uc4_t'     => 'Manajemen rute otomatis', 'uc4_d' => 'Pemantauan status kargo dan rute penerbangan real-time via FDS Station.',
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
                'post_excerpt' => $data['tagline'],
                'post_status'  => 'publish',
            ]);

            if ($post_id && !is_wp_error($post_id)) {
                // 1. Assign Taxonomy Term 'kategori_drone'
                wp_set_object_terms($post_id, $data['kategori'], 'kategori_drone', false);

                // 2. Set All Post Meta for newly seeded post
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
                }
                update_post_meta($post_id, 'drone_spec_otonomi', $data['sp_oto']);
                update_post_meta($post_id, 'drone_spec_gcs', $data['sp_gcs']);
                update_post_meta($post_id, 'drone_spec_sertifikasi', $data['sp_sert']);

                // Structured Raw Specs string
                $raw_specs = "Payload: {$data['sp_kap']}\n"
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

                // Auto-generate rich SEO article & Rank Math metadata for newly seeded post
                fds_sync_drone_rank_math_seo($post_id);
            }
        }
    }
    update_option('fds_drones_seeded_final_v1', true);
});

/**
 * Master Registry of Default FDS Drone Catalog
 * Single Source of Truth for Fallback / Canonical Specs
 * Used by single-drone.blade.php, page-bandingkan.blade.php, and API endpoints.
 */
if (!function_exists('\App\fds_get_default_drone_catalog')) {
    function fds_get_default_drone_catalog() {
    static $catalog = null;
    if ($catalog !== null) {
        return $catalog;
    }

    $catalog = [
        'ferto-5l' => [
            'name'      => 'FERTO 5',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Kompak & Lincah',
            'tagline'   => 'Drone Pertanian FERTO 5 — Platform UAV Agrikultur modular dengan mobilitas tinggi.',
            'color'     => '#0f808a',
            'specs'     => [
                ['Payload', '5 Liter'],
                ['Durasi Terbang', '10 – 15 menit'],
                ['Sistem Daya (Baterai)', '8.000 mAh'],
                ['Produktivitas Semprot', '1 Ha / jam'],
                ['Kecepatan Jelajah', '2 – 6 m/s'],
                ['Sistem Otonomi & Navigasi', 'Otonom & Manual, Terrain Following, Fail-Safe'],
                ['Ground Control Station', 'FDS Station (Bahasa Indonesia)'],
                ['Sertifikasi & Standar', 'TKDN + BMP 60,74% | ISO 9001:2015'],
            ],
            'desc'      => 'FERTO 5 didesain sebagai platform multirotor modular dengan mobilitas tinggi untuk menjangkau area berbukit, terasering, dan lahan perkebunan dengan kontur ekstrem. Dilengkapi fitur terrain-following otomatis dan sistem kendali FDS Station, drone ini menjamin presisi penyemprotan pupuk cair maupun pestisida secara merata dengan produktivitas 1 Ha per jam.',
            'for'       => [
                'Lahan berbukit & terasering — Bobot ringan dan dimensi ringkas mempermudah manuver di area sempit.',
                'Petani hortikultura & kebun — Aplikasi larutan kerja seragam dengan penyemprotan droplet presisi.',
                'Penyedia jasa semprot mandiri — Mobilitas tinggi mudah dibawa ke area perbukitan.',
                'Dukungan purna jual resmi — Jaringan servis dan suku cadang asli lokal FDS siap pakai.',
            ],
            'stat1_num' => '5 L', 'stat1_lbl' => 'Kapasitas Tangki',
            'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
            'stat3_num' => '1 Ha/j', 'stat3_lbl' => 'Produktivitas Semprot',
            'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
        ],
        'ferto-10l' => [
            'name'      => 'FERTO 10',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Terlaris',
            'tagline'   => 'Drone Pertanian FERTO 10 — Pilihan andal kelompok tani dengan produktivitas seimbang.',
            'color'     => '#0f808a',
            'specs'     => [
                ['Payload', '10 Liter'],
                ['Durasi Terbang', '12 – 15 menit'],
                ['Sistem Daya (Baterai)', '16.000 mAh'],
                ['Produktivitas Semprot', '1 – 1,5 Ha / jam'],
                ['Kecepatan Jelajah', '2 – 6 m/s'],
                ['Sistem Otonomi & Navigasi', 'Otonom & Manual, Terrain Following, Fail-Safe'],
                ['Ground Control Station', 'FDS Station (Bahasa Indonesia)'],
                ['Sertifikasi & Standar', 'TKDN + BMP 60,74% | ISO 9001:2015'],
            ],
            'desc'      => 'FERTO 10 adalah varian andalan FDS yang menawarkan keseimbangan optimal antara kapasitas muatan 10 liter, ketahanan baterai 16.000 mAh, dan produktivitas 1 - 1,5 Ha/jam. Menggunakan rangka karbon komposit buatan dalam negeri bersertifikat TKDN 60,74%, drone ini mendukung modernisasi pertanian di berbagai wilayah Indonesia.',
            'for'       => [
                'Kelompok tani & Gapoktan — Titik temu seimbang antara kapasitas operasional dan efisiensi investasi.',
                'Koperasi pertanian — Mengurangi ketergantungan tenaga kerja semprot manual di musim tanam.',
                'Program ketahanan pangan — Dirancang untuk dukungan penyemprotan presisi komoditas pangan.',
                'Suku cadang asli terjamin — Ketersediaan komponen cepat dari workshop Yogyakarta.',
            ],
            'stat1_num' => '10 L', 'stat1_lbl' => 'Kapasitas Tangki',
            'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
            'stat3_num' => '1,5 Ha/j', 'stat3_lbl' => 'Produktivitas Semprot',
            'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
        ],
        'ferto-15l' => [
            'name'      => 'FERTO 15',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Komersial',
            'tagline'   => 'Drone Pertanian FERTO 15 — Kapasitas 17 Liter dengan produktivitas 8 Ha/jam untuk komersial.',
            'color'     => '#0f808a',
            'specs'     => [
                ['Payload', '17 Liter (15 – 17 Liter)'],
                ['Durasi Terbang', '15 – 22 menit'],
                ['Sistem Daya (Baterai)', '16.000 mAh'],
                ['Produktivitas Semprot', '8 Ha / jam'],
                ['Kecepatan Jelajah', '2 – 6 m/s'],
                ['Sistem Otonomi & Navigasi', 'Otonom & Manual, Terrain Following, Fail-Safe'],
                ['Ground Control Station', 'FDS Station (Bahasa Indonesia)'],
                ['Sertifikasi & Standar', 'TKDN + BMP 60,74% | SNI 9199:2023 | ISO 9001:2015'],
            ],
            'desc'      => 'FERTO 15 dirancang untuk ritme kerja lapangan yang padat dengan kapasitas tangki fleksibel 17 Liter dan produktivitas semprot hingga 8 hektare per jam. Mampu menuntaskan penyemprotan pupuk cair maupun pestisida pada lahan perkebunan luas dalam durasi terbang 15 hingga 22 menit per sortie baterai.',
            'for'       => [
                'Perkebunan komersial & BUMN — Efisiensi kerja skala besar dengan kapasitas tangki 17 Liter.',
                'Kontraktor jasa aplikasi presisi — Durasi terbang hingga 22 menit untuk ritme kerja lapangan padat.',
                'Dinas Pertanian & Tanaman Pangan — Standar TKDN 60,74% dan SNI 9199:2023 untuk e-Katalog LKPP.',
                'Dukungan teknis pilot bersertifikat — Layanan pendampingan dan pelatihan pilot resmi FDS.',
            ],
            'stat1_num' => 'SNI', 'stat1_lbl' => 'SNI 9199:2023',
            'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
            'stat3_num' => '8 Ha/j', 'stat3_lbl' => 'Produktivitas Semprot',
            'stat4_num' => '17 L', 'stat4_lbl' => 'Kapasitas Tangki',
        ],
        'ferto-22l' => [
            'name'      => 'FERTO 22',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Enterprise',
            'tagline'   => 'Drone Pertanian FERTO 22 — Kapasitas muat besar untuk perkebunan kelapa sawit & tebu.',
            'color'     => '#0f808a',
            'specs'     => [
                ['Payload', '22 Liter'],
                ['Durasi Terbang', '15 – 25 menit'],
                ['Sistem Daya (Baterai)', '22.000 mAh'],
                ['Produktivitas Semprot', '10 – 12 Ha / jam'],
                ['Kecepatan Jelajah', '2 – 7 m/s'],
                ['Sistem Otonomi & Navigasi', 'Otonom & Manual, Terrain Following, Fail-Safe'],
                ['Ground Control Station', 'FDS Station (Bahasa Indonesia)'],
                ['Sertifikasi & Standar', 'TKDN + BMP 60,74% | SNI 9199:2023 | ISO 9001:2015'],
            ],
            'desc'      => 'FERTO 22 adalah varian heavy-duty yang didesain untuk operasional perkebunan skala korporasi seperti kelapa sawit, karet, dan tebu. Didukung baterai berdensitas energi tinggi 22.000 mAh dan pompa bertekanan dengan nozzle atomisasi sentrifugal, drone ini menghasilkan droplet halus merata hingga ke kanopi tanaman tinggi.',
            'for'       => [
                'Perkebunan kelapa sawit & industri — Mampu menyemprot kanopi tinggi kelapa sawit secara merata.',
                'Agroindustri tebu & jagung — Cakupan luas 10-12 Ha/jam mempercepat siklus perlindungan tanaman.',
                'Korporasi agribisnis terintegrasi — Terintegrasi dengan analitik telemetri FDS Station.',
                'Garansi & suku cadang workshop FDS — Jaminan ketersediaan komponen tanpa inden impor.',
            ],
            'stat1_num' => 'SNI', 'stat1_lbl' => 'SNI 9199:2023',
            'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
            'stat3_num' => '12 Ha/j', 'stat3_lbl' => 'Produktivitas Semprot',
            'stat4_num' => '22 L', 'stat4_lbl' => 'Kapasitas Muatan',
        ],
        'ferto-30l' => [
            'name'      => 'FERTO 30',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Heavy Duty',
            'tagline'   => 'Drone Pertanian FERTO 30 — Produktivitas 15 Ha/jam untuk perkebunan berskala luas.',
            'color'     => '#0f808a',
            'specs'     => [
                ['Payload', '30 Liter'],
                ['Durasi Terbang', '15 – 25 menit'],
                ['Sistem Daya (Baterai)', '28.000 mAh'],
                ['Produktivitas Semprot', '15 Ha / jam'],
                ['Kecepatan Jelajah', '2 – 7 m/s'],
                ['Sistem Otonomi & Navigasi', 'Otonom & Manual, Terrain Following, Fail-Safe'],
                ['Ground Control Station', 'FDS Station (Bahasa Indonesia)'],
                ['Sertifikasi & Standar', 'TKDN + BMP 60,74% | ISO 9001:2015'],
            ],
            'desc'      => 'FERTO 30 hadir untuk memenuhi kebutuhan efisiensi pada perkebunan besar dan proyek penghijauan hutan. Mengusung motor bertenaga tinggi dan tangki 30 liter berbahan komposit kokoh, drone ini mampu mencakup hingga 15 hektare per jam dengan kemampuan dual-mode penyemprotan cairan dan penebaran pupuk granular.',
            'for'       => [
                'Perkebunan kelapa sawit & hutan tanaman industri (HTI) — Mengurangi rotasi isi ulang dengan muatan 30L.',
                'Penyebaran pupuk granular & benih padi — Dual-mode semprot cair dan sebar butiran padat.',
                'Perusahaan perkebunan berskala luas — Integrasi armada drone dengan perencanaan blok terpusat.',
                'Dukungan logistik & purna jual komprehensif — Paket perawatan berkala dan penyediaan suku cadang resmi.',
            ],
            'stat1_num' => '30 L', 'stat1_lbl' => 'Muatan Heavy Duty',
            'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
            'stat3_num' => '15 Ha/j', 'stat3_lbl' => 'Produktivitas Semprot',
            'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
        ],
        'ferto-50l' => [
            'name'      => 'FERTO 50',
            'kategori'  => 'Agrikultur',
            'badge'     => 'Kapasitas Puncak',
            'tagline'   => 'Drone Pertanian FERTO 50 — Muatan maksimum 50 liter untuk efisiensi agroindustri besar.',
            'color'     => '#0f808a',
            'specs'     => [
                ['Payload', '50 Liter'],
                ['Durasi Terbang', '15 – 25 menit'],
                ['Sistem Daya (Baterai)', '30.000 mAh High Capacity'],
                ['Produktivitas Semprot', '18 – 22 Ha / jam'],
                ['Kecepatan Jelajah', '2 – 8 m/s'],
                ['Sistem Otonomi & Navigasi', 'Otonom & Manual, Terrain Following, Fail-Safe'],
                ['Ground Control Station', 'FDS Station (Bahasa Indonesia)'],
                ['Sertifikasi & Standar', 'TKDN + BMP 60,74% | ISO 9001:2015'],
            ],
            'desc'      => 'FERTO 50 adalah varian berdaya angkut tertinggi dalam lini drone pertanian FDS dengan kapasitas tangki hingga 50 liter. Dirancang untuk agroindustri terintegrasi dan perkebunan ratusan hingga ribuan hektare yang membutuhkan produktivitas tinggi dalam jendela operasional harian.',
            'for'       => [
                'Agroindustri skala besar — Produktivitas tinggi menuntaskan blok ribuan hektare secara efisien.',
                'Penyebaran urea & pupuk organik padat — Kapasitas sebar butiran hingga 50 kg per sortie.',
                'Mitra korporasi perkebunan nasional — Standardisasi armada semprot modern bersertifikat TKDN 60,74%.',
                'Layanan purna jual terpadu — Dukungan suku cadang dan inspeksi berkala workshop Sleman.',
            ],
            'stat1_num' => '50 L', 'stat1_lbl' => 'Kapasitas Maksimum',
            'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
            'stat3_num' => '22 Ha/j', 'stat3_lbl' => 'Produktivitas Maks',
            'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
        ],
        'deltav' => [
            'name'      => 'DELTAV',
            'kategori'  => 'Pemetaan & GIS',
            'badge'     => 'Hybrid VTOL',
            'tagline'   => 'Fixed-Wing VTOL UAV — Jangkauan 60 km untuk pemetaan topografi, koridor, & survei udara luas.',
            'color'     => '#0f808a',
            'specs'     => [
                ['Bentang Sayap (Wingspan)', '2.000 mm'],
                ['Konfigurasi Motor', 'VTOL Quad-Rotor + Pusher Fixed-Wing'],
                ['Berat Lepas Landas (MTOW)', '8 – 12 kg'],
                ['Durasi Terbang', '60 – 120 menit'],
                ['Jangkauan Misi (Range)', 'Hingga 60 km'],
                ['Sensor Kompatibel', 'Kamera RGB 24-42 MP, LiDAR Ringan, Multispektral'],
                ['Sistem Otonomi & Navigasi', 'Full Autonomous Waypoint Flight, Auto-Return to Launch (RTL)'],
                ['Software Perencanaan Misi', 'FDS Station Pemetaan & Telemetri Real-Time'],
            ],
            'desc'      => 'DELTAV adalah pesawat UAV fixed-wing berteknologi Hybrid VTOL (Vertical Takeoff and Landing) yang menggabungkan kemudahan lepas landas tegak lurus tanpa landasan pacu dengan kecepatan jelajah serta efisiensi aerodinamis pesawat sayap tetap. Dengan jangkauan hingga 60 km dan durasi terbang 60-120 menit, DELTAV tepat untuk survei topografi, ortofoto beresolusi tinggi, pemetaan kehutanan, dan akuisisi data geospasial area luas.',
            'for'       => [
                'Survei topografi & konstruksi — Mempercepat pemodelan 3D, ortomozaik, dan perhitungan cut & fill secara signifikan.',
                'Kehutanan & lingkungan — Pemetaan daerah aliran sungai (DAS), tutupan kanopi, dan progres reklamasi tambang.',
                'Pertambangan & kuari — Pemetaan kontur presisi dan pemantauan batas konsesi tambang.',
                'Perencanaan tata ruang & GIS nasional — Akurasi data geospasial sub-sentimeter siap integrasi CAD & BIM.',
            ],
            'stat1_num' => '2.000mm', 'stat1_lbl' => 'Bentang Sayap',
            'stat2_num' => '60 km', 'stat2_lbl' => 'Jangkauan Misi',
            'stat3_num' => '120 min', 'stat3_lbl' => 'Durasi Terbang Maks',
            'stat4_num' => 'VTOL', 'stat4_lbl' => 'Lepas Landas Vertikal',
        ],
        'multipurpose' => [
            'name'      => 'MULTIPURPOSE',
            'kategori'  => 'Pemetaan & Inspeksi',
            'badge'     => 'Modular UAV',
            'tagline'   => 'Platform UAV Modular Serbaguna — Integrasi payload termal, optical zoom, & sensor inspeksi.',
            'color'     => '#0f808a',
            'specs'     => [
                ['Payload', '5 kg (Sensor Termal, LiDAR, Optical Zoom)'],
                ['Durasi Terbang', '15 – 30 menit'],
                ['Sistem Daya (Baterai)', '8.000 mAh'],
                ['Sensor Kompatibel', 'Kamera Termal IR, 20x Optical Zoom, LiDAR, Multispektral'],
                ['Mode Penerbangan', 'Manual, Task Following, Semi-to-Fully Autonomous'],
                ['Sistem Proteksi', 'Konstruksi modular kokoh & sistem fail-safe mandiri'],
                ['Software Pengendali', 'FDS Station Real-Time Monitoring & AI Analytics'],
                ['Aplikasi Utama', 'Inspeksi Jaringan Listrik 150kV, Solar PV, Pipa Migas, & Struktur'],
            ],
            'desc'      => 'MULTIPURPOSE dirancang sebagai platform UAV modular yang fleksibel untuk berbagai misi kustom. Mampu mengangkut payload hingga 5 kg dengan integrasi berbagai sensor seperti kamera termal inframerah, optik zoom 20x, hingga sensor LiDAR. Andal untuk inspeksi aset kritikal seperti jaringan transmisi listrik 150 kV, ladang panel surya, tangki minyak & gas, serta struktur jembatan.',
            'for'       => [
                'Inspeksi transmisi listrik 150 kV — Aman tanpa pemadaman listrik dan mengurangi risiko kerja di ketinggian.',
                'Inspeksi ladang energi surya (Solar PV) — Deteksi dini hotspot dan sel rusak sebelum berdampak pada output energi.',
                'Inspeksi migas & cerobong suar (Flare Stacks) — Deteksi kebocoran dan anomali termal tanpa mematikan kilang.',
                'Inspeksi struktur jembatan & konstruksi — Pemeriksaan visual mendetail komponen beton dan baja.',
            ],
            'stat1_num' => '5 kg', 'stat1_lbl' => 'Payload Maksimum',
            'stat2_num' => '30 min', 'stat2_lbl' => 'Durasi Terbang Maks',
            'stat3_num' => 'Termal/AI', 'stat3_lbl' => 'Sensor Kompatibel',
            'stat4_num' => '150 kV', 'stat4_lbl' => 'Inspeksi Aset Kritikal',
        ],
        'delfro' => [
            'name'      => 'DELFRO',
            'kategori'  => 'Cargo & Logistik',
            'badge'     => 'Logistics UAV',
            'tagline'   => 'Platform UAV Kargo Logistik Ringan — Distribusi logistik cepat dan aman ke area sulit dijangkau.',
            'color'     => '#0f808a',
            'specs'     => [
                ['Payload', '3 – 10 kg (Kotak Logistik)'],
                ['Berat Lepas Landas (MTOW)', '15 kg'],
                ['Dimensi Kotak Payload', '20 x 20 x 30 cm'],
                ['Kecepatan Jelajah / Maks', '2 – 6 m/s'],
                ['Waktu Terbang', '10 – 15 menit'],
                ['Ukuran Propeller', '18-inch High-Efficiency Carbon Propeller'],
                ['Mode Pengoperasian', 'Otonom (Waypoint Cargo Route) & Manual Fail-Safe'],
                ['Ground Control Software', 'FDS Station Logistics Management'],
            ],
            'desc'      => 'DELFRO adalah drone kargo otonom yang dikembangkan khusus untuk distribusi logistik ringan yang cepat dan terukur. Dengan kapasitas angkut 3 hingga 10 kg dan kompartemen kargo berukuran 20 x 20 x 30 cm, DELFRO menjadi solusi praktis untuk pengiriman sampel medis, pasokan darurat kebencanaan, suku cadang penting, dan logistik ke wilayah sulit akses darat.',
            'for'       => [
                'Logistik medis & darurat bencana — Pengiriman cepat obat-obatan, kantong darah, dan sampel laboratorium ke lokasi terpencil.',
                'Pengiriman suku cadang industri — Menghubungkan titik tambang atau lokasi lapangan terpisah dengan gudang secara cepat.',
                'Ekspedisi & kurir last-mile — Alternatif pengangkutan lintas sungai, bukit, atau selat.',
                'Manajemen rute otomatis — Pemantauan status kargo dan rute penerbangan real-time via FDS Station.',
            ],
            'stat1_num' => '10 kg', 'stat1_lbl' => 'Payload Maksimum',
            'stat2_num' => '15 kg', 'stat2_lbl' => 'MTOW Maksimum',
            'stat3_num' => '18"', 'stat3_lbl' => 'Carbon Propeller',
            'stat4_num' => 'Auto', 'stat4_lbl' => 'Waypoint Route',
        ],
    ];

    // Aliases for clean URL variations
    $catalog['ferto-5']  = &$catalog['ferto-5l'];
    $catalog['ferto-10'] = &$catalog['ferto-10l'];
    $catalog['ferto-15'] = &$catalog['ferto-15l'];
    $catalog['ferto-22'] = &$catalog['ferto-22l'];
    $catalog['ferto-30'] = &$catalog['ferto-30l'];
    $catalog['ferto-50'] = &$catalog['ferto-50l'];

    return $catalog;
    }
}

/**
 * Canonical Hero Media Resolver for Drone CPT
 * Guarantees 1:1 parity between single-drone Hero section & catalog <x-drone-card>.
 */
if (!function_exists('\App\fds_get_drone_hero_media')) {
    function fds_get_drone_hero_media(int $post_id, int $source_id = 0): array {
        $preview_id = ($source_id > 0) ? $source_id : $post_id;
        $slug       = strtolower((string) get_post_field('post_name', $post_id));
        $modelKey   = fds_get_drone_model_key($post_id);
        $droneImgKey = 'drone_' . str_replace('-', '_', str_replace('ferto-', '', $slug));

        $themeUri = get_template_directory_uri();
        $themeDir = get_template_directory();

        $photoRel           = "/public/images/drones/{$modelKey}.webp";
        $localPhotoFallback = file_exists($themeDir . $photoRel)
            ? $themeUri . $photoRel
            : $themeUri . '/public/images/drones/ferto-15l.webp';

        $cutoutRel           = "/public/images/cutouts/{$modelKey}.png";
        $localCutoutFallback = file_exists($themeDir . $cutoutRel)
            ? $themeUri . $cutoutRel
            : $localPhotoFallback;

        $thumb_id    = (int) (get_post_thumbnail_id($preview_id) ?: get_post_thumbnail_id($post_id));
        $featuredImg = get_the_post_thumbnail_url($preview_id, 'full')
            ?: get_the_post_thumbnail_url($post_id, 'full')
            ?: get_the_post_thumbnail_url($preview_id, 'large')
            ?: get_the_post_thumbnail_url($post_id, 'large');

        $is_banned = function ($url): bool {
            if (!is_string($url) || trim($url) === '') {
                return true;
            }
            return stripos($url, 'unsplash.com') !== false || stripos($url, 'DELFRO.png') !== false;
        };

        $heroSrc    = '';
        $isFeatured = false;
        if (!$is_banned($featuredImg)) {
            $heroSrc    = (string) $featuredImg;
            $isFeatured = true;
        } else {
            $heroCandidate = function_exists('fds_img') ? (string) fds_img($droneImgKey, '') : '';
            if (!$is_banned($heroCandidate)) {
                $heroSrc = $heroCandidate;
            } else {
                $heroSrc = $localPhotoFallback;
            }
        }

        $srcset = ($isFeatured && $thumb_id > 0)
            ? (string) (wp_get_attachment_image_srcset($thumb_id, 'large') ?: '')
            : '';

        return [
            'url'            => $heroSrc,
            'thumb_id'       => $thumb_id,
            'srcset'         => $srcset,
            'local_fallback' => $localPhotoFallback,
            'cutout_fallback'=> $localCutoutFallback,
            'model_key'      => $modelKey,
            'drone_img_key'  => $droneImgKey,
            'is_featured'    => $isFeatured,
        ];
    }
}

/**
 * Single Canonical Drone Detail Resolver
 * Consumed identically by single-drone.blade.php (Detail Page) and <x-drone-card> (Catalog Grid)
 * so catalog cards inherit 100% of Detail Hero image, category, badge, tagline, & technical specs.
 */
if (!function_exists('\App\fds_get_drone_detail_data')) {
    function fds_get_drone_detail_data(int $post_id, int $source_id = 0): array {
        static $memo = [];

        $preview_id = ($source_id > 0) ? $source_id : $post_id;
        $can_cache  = (!is_preview() && !is_admin() && $preview_id === $post_id);
        $cache_key  = $post_id . ':' . $preview_id;
        if ($can_cache && isset($memo[$cache_key])) {
            return $memo[$cache_key];
        }

        $post_obj = get_post($post_id);
        if (!$post_obj) {
            return [];
        }

        $slug      = (string) get_post_field('post_name', $post_id);
        $modelKey  = fds_get_drone_model_key($post_id);
        $isFerto   = str_starts_with($modelKey, 'ferto-');
        $hasSni    = in_array($modelKey, ['ferto-15l', 'ferto-22l'], true);
        $permalink = (string) get_permalink($post_id);
        $heroMedia = fds_get_drone_hero_media($post_id, $preview_id);

        $drones = fds_get_default_drone_catalog();
        $drone  = $drones[$slug] ?? ($drones[$modelKey] ?? null);

        $get_meta = static function (string $key) use ($preview_id, $post_id) {
            $val = get_post_meta($preview_id, $key, true);
            if ($val === '' || $val === false || $val === null) {
                $val = get_post_meta($post_id, $key, true);
            }
            return $val;
        };

        $meta_has = static function (string $key) use ($preview_id, $post_id): bool {
            return metadata_exists('post', $preview_id, $key) || metadata_exists('post', $post_id, $key);
        };

        if (!$drone) {
            $drone = [
                'name'      => get_the_title($preview_id) ?: get_the_title($post_id),
                'kategori'  => $get_meta('drone_kategori') ?: 'Agrikultur',
                'badge'     => $get_meta('drone_badge'),
                'tagline'   => $get_meta('drone_tagline') ?: get_the_excerpt($preview_id),
                'color'     => '#0f808a',
                'specs'     => [],
                'desc'      => $get_meta('drone_desc') ?: get_post_field('post_content', $preview_id),
                'for'       => [],
                'stat1_num' => $get_meta('drone_stat1_num'),
                'stat1_lbl' => $get_meta('drone_stat1_lbl'),
                'stat2_num' => $get_meta('drone_stat2_num'),
                'stat2_lbl' => $get_meta('drone_stat2_lbl'),
                'stat3_num' => $get_meta('drone_stat3_num'),
                'stat3_lbl' => $get_meta('drone_stat3_lbl'),
                'stat4_num' => $get_meta('drone_stat4_num'),
                'stat4_lbl' => $get_meta('drone_stat4_lbl'),
            ];
        } else {
            if ($meta_has('drone_stat1_num')) $drone['stat1_num'] = $get_meta('drone_stat1_num');
            if ($meta_has('drone_stat1_lbl')) $drone['stat1_lbl'] = $get_meta('drone_stat1_lbl');
            if ($meta_has('drone_stat2_num')) $drone['stat2_num'] = $get_meta('drone_stat2_num');
            if ($meta_has('drone_stat2_lbl')) $drone['stat2_lbl'] = $get_meta('drone_stat2_lbl');
            if ($meta_has('drone_stat3_num')) $drone['stat3_num'] = $get_meta('drone_stat3_num');
            if ($meta_has('drone_stat3_lbl')) $drone['stat3_lbl'] = $get_meta('drone_stat3_lbl');
            if ($meta_has('drone_stat4_num')) $drone['stat4_num'] = $get_meta('drone_stat4_num');
            if ($meta_has('drone_stat4_lbl')) $drone['stat4_lbl'] = $get_meta('drone_stat4_lbl');
            if ($meta_has('drone_badge'))     $drone['badge']     = $get_meta('drone_badge');
            if ($meta_has('drone_tagline'))   $drone['tagline']   = $get_meta('drone_tagline');
            if ($meta_has('drone_desc'))      $drone['desc']      = $get_meta('drone_desc');
        }

        $cpt_title = get_the_title($preview_id) ?: get_the_title($post_id);
        if (!empty($cpt_title)) {
            $drone['name'] = $cpt_title;
        }

        $cpt_desc = $get_meta('drone_desc');
        if ($meta_has('drone_desc')) {
            $drone['desc'] = $cpt_desc;
        } elseif (empty($drone['desc'])) {
            $drone['desc'] = get_post_field('post_content', $preview_id) ?: get_post_field('post_content', $post_id);
        }

        $terms    = get_the_terms($preview_id, 'kategori_drone') ?: get_the_terms($post_id, 'kategori_drone');
        $cat_url  = home_url('/produk/');
        $cat_slug = sanitize_title((string) ($drone['kategori'] ?? 'agrikultur'));
        if (!empty($terms) && !is_wp_error($terms)) {
            $drone['kategori'] = $terms[0]->name;
            $cat_slug          = (string) $terms[0]->slug;
            $term_link         = get_term_link($terms[0]);
            if (!is_wp_error($term_link) && is_string($term_link)) {
                $cat_url = $term_link;
            }
        } else {
            $cpt_kategori = $get_meta('drone_kategori');
            if ($cpt_kategori) {
                $drone['kategori'] = $cpt_kategori;
                $cat_slug          = sanitize_title((string) $cpt_kategori);
                $term_by_slug      = get_term_by('slug', $cat_slug, 'kategori_drone');
                if ($term_by_slug && !is_wp_error($term_by_slug)) {
                    $tl = get_term_link($term_by_slug);
                    if (!is_wp_error($tl) && is_string($tl)) {
                        $cat_url = $tl;
                    }
                }
            }
        }

        if ($meta_has('drone_badge')) {
            $drone['badge'] = $get_meta('drone_badge');
        }
        if ($meta_has('drone_tagline')) {
            $drone['tagline'] = $get_meta('drone_tagline');
        }

        $cpt_specs_raw = $get_meta('drone_specs_raw');
        if ($cpt_specs_raw) {
            $parsed_specs = [];
            $lines        = explode("\n", (string) $cpt_specs_raw);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '') continue;
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $parsed_specs[] = [trim($parts[0]), trim($parts[1])];
                }
            }
            if (!empty($parsed_specs)) {
                $drone['specs'] = $parsed_specs;
            }
        }

        if (empty($drone['specs'])) {
            $spec_fields = [
                'Payload'                   => $get_meta('drone_spec_kapasitas') ?: $get_meta('drone_kapasitas'),
                'Durasi Terbang'            => $get_meta('drone_spec_durasi'),
                'Sistem Daya (Baterai)'     => $get_meta('drone_spec_baterai') ?: $get_meta('drone_baterai'),
                'Produktivitas / Jangkauan' => $get_meta('drone_spec_produktivitas') ?: $get_meta('drone_cakupan'),
                'Kecepatan Jelajah'         => $get_meta('drone_spec_kecepatan'),
                'Ketahanan Lingkungan'      => $get_meta('drone_spec_ketahanan'),
                'Sistem Otonomi & Navigasi' => $get_meta('drone_spec_otonomi'),
                'Ground Control Station'    => $get_meta('drone_spec_gcs'),
                'Sertifikasi & Standar'     => $get_meta('drone_spec_sertifikasi'),
            ];
            $custom_specs = [];
            foreach ($spec_fields as $lbl => $val) {
                if ($val) {
                    $custom_specs[] = [$lbl, $val];
                }
            }
            if (!empty($custom_specs)) {
                $drone['specs'] = $custom_specs;
            }
        }

        $cpt_for = $get_meta('drone_for');
        if ($cpt_for) {
            $parsed_for = array_filter(array_map('trim', explode("\n", (string) $cpt_for)));
            if (!empty($parsed_for)) {
                $drone['for'] = $parsed_for;
            }
        }

        if (empty($drone['for'])) {
            $uc_list = [];
            for ($i = 1; $i <= 4; $i++) {
                $t = $get_meta("drone_uc{$i}_t");
                $d = $get_meta("drone_uc{$i}_d");
                if ($t) {
                    $uc_list[] = $d ? "$t — $d" : $t;
                }
            }
            if (!empty($uc_list)) {
                $drone['for'] = $uc_list;
            }
        }

        // Decode & Scrub All Text Outputs Consistently
        $decode_deep = static function ($val) use (&$decode_deep) {
            if (function_exists('fds_deep_decode')) {
                return fds_deep_decode($val);
            }
            if (is_array($val)) {
                return array_map($decode_deep, $val);
            }
            if (!is_string($val)) {
                return $val;
            }
            $prev = '';
            while ($prev !== $val) {
                $prev = $val;
                $val  = wp_specialchars_decode($val, ENT_QUOTES);
                $val  = html_entity_decode($val, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
            return $val;
        };

        $drone = $decode_deep($drone);

        $scrub_fn = static function ($txt) use ($isFerto, $hasSni): string {
            $s = (string) $txt;
            return function_exists('fds_scrub_legacy_text')
                ? fds_scrub_legacy_text($s, $isFerto, $hasSni)
                : $s;
        };

        $drone['name']     = $scrub_fn($drone['name'] ?? '');
        $drone['kategori'] = $scrub_fn($drone['kategori'] ?? 'Agrikultur');
        $drone['badge']    = $scrub_fn($drone['badge'] ?? '');
        $drone['tagline']  = $scrub_fn($drone['tagline'] ?? '');
        $drone['desc']     = $scrub_fn($drone['desc'] ?? '');
        if (!empty($drone['for']) && is_array($drone['for'])) {
            $drone['for'] = array_values(array_filter(array_map($scrub_fn, $drone['for'])));
        }

        if (!empty($drone['specs']) && is_array($drone['specs'])) {
            $clean_specs = [];
            foreach ($drone['specs'] as $spItem) {
                if (!is_array($spItem) || count($spItem) < 2) {
                    continue;
                }
                $spLabel = trim((string) $spItem[0]);
                if (
                    $spLabel === 'Kapasitas Tangki' ||
                    $spLabel === 'Kapasitas Tangki / Payload' ||
                    $spLabel === 'Kapasitas Payload' ||
                    $spLabel === 'Kapasitas Payload Biji' ||
                    stripos($spLabel, 'kapasitas tangki') === 0 ||
                    stripos($spLabel, 'kapasitas payload') === 0
                ) {
                    $spLabel = 'Payload';
                }

                $spVal = $scrub_fn($spItem[1]);
                if ($spLabel === 'Payload' && $modelKey === 'ferto-15l' && stripos($spVal, '17') === false) {
                    $spVal = '17 Liter (15 – 17 Liter)';
                }
                if (stripos($spLabel, 'Sertifikasi') !== false && $spVal === '') {
                    if ($hasSni) {
                        $spVal = 'TKDN + BMP 60,74% | SNI 9199:2023 | ISO 9001:2015';
                    } elseif ($isFerto) {
                        $spVal = 'TKDN + BMP 60,74% | ISO 9001:2015';
                    } else {
                        $spVal = 'ISO 9001:2015';
                    }
                }

                if ($spVal !== '') {
                    $clean_specs[] = [$spLabel, $spVal];
                }
            }
            $drone['specs'] = $clean_specs;
        }

        for ($sIdx = 1; $sIdx <= 4; $sIdx++) {
            $stNumKey = "stat{$sIdx}_num";
            $stLblKey = "stat{$sIdx}_lbl";
            if (!empty($drone[$stLblKey])) {
                $stVal = trim((string) $drone[$stLblKey]);
                if (
                    $stVal === 'Kapasitas Tangki' ||
                    $stVal === 'Kapasitas Tangki / Payload' ||
                    $stVal === 'Kapasitas Payload' ||
                    stripos($stVal, 'kapasitas tangki') === 0 ||
                    stripos($stVal, 'kapasitas payload') === 0
                ) {
                    $drone[$stLblKey] = 'Payload';
                }
            }
            $drone[$stNumKey] = $scrub_fn($drone[$stNumKey] ?? '');
            $drone[$stLblKey] = $scrub_fn($drone[$stLblKey] ?? '');
            if ($modelKey === 'ferto-15l' && stripos((string) $drone[$stLblKey], 'Payload') !== false && stripos((string) $drone[$stNumKey], '15') !== false) {
                $drone[$stNumKey] = '17 L';
            }
        }

        // Derive Catalog Card Summary strictly from Resolved Detail Specs/Meta
        $spec_map = [];
        if (!empty($drone['specs']) && is_array($drone['specs'])) {
            foreach ($drone['specs'] as $spItem) {
                $spec_map[strtolower(trim((string) $spItem[0]))] = trim((string) $spItem[1]);
            }
        }

        $card_payload = (string) ($spec_map['payload'] ?? '');
        if ($card_payload === '') {
            $card_payload = $scrub_fn($decode_deep((string) ($get_meta('drone_spec_kapasitas') ?: $get_meta('drone_kapasitas'))));
        }
        if ($card_payload === '' && $modelKey === 'deltav') {
            $wingspan     = $spec_map['bentang sayap (wingspan)'] ?? '2.000 mm';
            $card_payload = "1 – 2 kg ({$wingspan})";
        }
        if ($modelKey === 'ferto-15l' && ($card_payload === '' || stripos($card_payload, '17') === false)) {
            $card_payload = '17 Liter (15 – 17 Liter)';
        } elseif ($modelKey === 'delfro' && ($card_payload === '' || stripos($card_payload, '20 kg') !== false)) {
            $card_payload = '3 – 10 kg (20 × 20 × 30 cm)';
        } elseif ($modelKey === 'multipurpose' && ($card_payload === '' || stripos($card_payload, '30x') !== false)) {
            $card_payload = '5 kg (Modular)';
        }
        if ($card_payload === '' && !empty($drone['stat1_num']) && stripos((string) $drone['stat1_num'], 'SNI') === false) {
            $card_payload = (string) $drone['stat1_num'];
        }
        if ($card_payload === '') {
            $card_payload = 'Modular';
        }

        $card_durasi = (string) ($spec_map['durasi terbang'] ?? ($spec_map['waktu terbang'] ?? ''));
        if ($card_durasi === '') {
            $card_durasi = $scrub_fn($decode_deep((string) $get_meta('drone_spec_durasi')));
        }
        if ($modelKey === 'delfro' && $card_durasi === '') {
            $card_durasi = '10 – 15 menit';
        } elseif ($modelKey === 'deltav' && $card_durasi === '') {
            $card_durasi = '60 – 120 menit';
        } elseif ($modelKey === 'multipurpose' && ($card_durasi === '' || stripos($card_durasi, '35') !== false)) {
            $card_durasi = '15 – 30 menit';
        }

        $card_range = (string) (
            $spec_map['produktivitas / jangkauan']
            ?? ($spec_map['produktivitas semprot']
            ?? ($spec_map['jangkauan misi (range)'] ?? ''))
        );
        if ($card_range === '') {
            $card_range = $scrub_fn($decode_deep((string) ($get_meta('drone_spec_produktivitas') ?: $get_meta('drone_spec_jarak'))));
        }

        if ($card_durasi !== '') {
            $card_metric_label = 'Durasi Terbang';
            $card_metric_value = $card_durasi;
        } elseif ($card_range !== '') {
            $card_metric_label = 'Jangkauan';
            $card_metric_value = $card_range;
        } else {
            $card_metric_label = 'Jangkauan';
            $card_metric_value = 'Otonom GCS';
        }

        $specs_img_meta = (string) $get_meta('drone_specs_img');
        $specs_img      = ($specs_img_meta !== '' && stripos($specs_img_meta, 'unsplash.com') === false && stripos($specs_img_meta, 'DELFRO.png') === false)
            ? $specs_img_meta
            : $heroMedia['cutout_fallback'];

        $desc_plain   = trim((string) wp_strip_all_tags((string) ($drone['desc'] ?? '')));
        $card_tagline = ($drone['tagline'] !== '')
            ? (string) $drone['tagline']
            : ($desc_plain !== '' ? wp_trim_words($desc_plain, 16, '…') : 'Platform UAV manufaktur PT Karya Solusi Angkasa (FDS) Sleman, Yogyakarta.');

        $result = array_merge($drone, [
            'id'                => $post_id,
            'preview_id'        => $preview_id,
            'slug'              => $slug,
            'model_key'         => $modelKey,
            'permalink'         => $permalink,
            'is_ferto'          => $isFerto,
            'has_sni'           => $hasSni,
            'cat_slug'          => $cat_slug,
            'cat_url'           => $cat_url,
            'hero_media'        => $heroMedia,
            'hero_url'          => $heroMedia['url'],
            'hero_srcset'       => $heroMedia['srcset'],
            'specs_img'         => $specs_img,
            'card_payload'      => $card_payload,
            'card_metric_label' => $card_metric_label,
            'card_metric_value' => $card_metric_value,
            'card_tagline'      => $card_tagline,
        ]);

        if ($can_cache) {
            $memo[$cache_key] = $result;
        }

        return $result;
    }
}

