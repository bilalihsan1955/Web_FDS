<?php
/**
 * FDS Setup Script — Auto-configure WordPress + Insert Dummy Posts
 * Akses via browser: http://fds.local/wp-content/themes/fds-theme/fds-setup.php
 * Hapus file ini setelah dijalankan!
 */

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php';

if (!current_user_can('manage_options')) {
    wp_die('Akses ditolak. Silakan login sebagai Admin WP terlebih dahulu, lalu buka URL ini kembali.');
}

$log = [];

// ================================================================
// STEP 1: Pastikan ada halaman "Beranda" (front page)
// ================================================================
$front_page = get_page_by_path('beranda');
if (!$front_page) {
    $front_page_id = wp_insert_post([
        'post_title'  => 'Beranda',
        'post_name'   => 'beranda',
        'post_status' => 'publish',
        'post_type'   => 'page',
        'post_content'=> '',
    ]);
    $log[] = ['ok', 'Halaman Beranda dibuat (ID: ' . $front_page_id . ')'];
} else {
    $front_page_id = $front_page->ID;
    $log[] = ['skip', 'Halaman Beranda sudah ada (ID: ' . $front_page_id . ')'];
}

// ================================================================
// STEP 2: Pastikan ada halaman "Blog" (posts page)
// ================================================================
$blog_page = get_page_by_path('blog');
if (!$blog_page) {
    $blog_page_id = wp_insert_post([
        'post_title'  => 'Blog',
        'post_name'   => 'blog',
        'post_status' => 'publish',
        'post_type'   => 'page',
        'post_content'=> '',
    ]);
    $log[] = ['ok', 'Halaman Blog dibuat (ID: ' . $blog_page_id . ')'];
} else {
    $blog_page_id = $blog_page->ID;
    $log[] = ['skip', 'Halaman Blog sudah ada (ID: ' . $blog_page_id . ')'];
}

// ================================================================
// STEP 3: Pastikan ada halaman "Tentang Kami"
// ================================================================
$about_page = get_page_by_path('tentang-kami');
if (!$about_page) {
    $about_id = wp_insert_post([
        'post_title'  => 'Tentang Kami',
        'post_name'   => 'tentang-kami',
        'post_status' => 'publish',
        'post_type'   => 'page',
        'post_content'=> '',
    ]);
    $log[] = ['ok', 'Halaman Tentang Kami dibuat (ID: ' . $about_id . ')'];
} else {
    $log[] = ['skip', 'Halaman Tentang Kami sudah ada (ID: ' . $about_page->ID . ')'];
}

// ================================================================
// STEP 4: Set Reading Settings
// ================================================================
update_option('show_on_front', 'page');
update_option('page_on_front', $front_page_id);
update_option('page_for_posts', $blog_page_id);
$log[] = ['ok', 'Reading Settings dikonfigurasi: Homepage = Beranda, Posts Page = Blog'];

// ================================================================
// STEP 5: Set Permalink Structure (pretty permalinks)
// ================================================================
$current_structure = get_option('permalink_structure');
if (empty($current_structure)) {
    update_option('permalink_structure', '/%postname%/');
    flush_rewrite_rules();
    $log[] = ['ok', 'Permalink structure diset ke /%postname%/'];
} else {
    flush_rewrite_rules();
    $log[] = ['skip', 'Permalink structure sudah ada: ' . $current_structure];
}

// ================================================================
// STEP 6: Insert dummy blog posts
// ================================================================
$dummy_posts = [
    [
        'title'    => 'FERTO 22L Berhasil Uji Lapangan di Lahan Tebu 500 Ha Jawa Timur',
        'excerpt'  => 'Drone FERTO 22L sukses menyelesaikan misi penyemprotan seluas 500 hektare lahan tebu dalam waktu 4 hari operasional tanpa kendala teknis.',
        'content'  => '<p>Tim lapangan Full Drone Solutions baru saja menyelesaikan proyek penyemprotan massal di perkebunan tebu seluas 500 hektare di Jawa Timur menggunakan armada 5 unit FERTO 22L. Proyek ini menjadi salah satu operasi drone agrikultur terbesar yang pernah dilakukan dengan perangkat produksi dalam negeri.</p><h2>Spesifikasi Operasional</h2><p>Setiap unit FERTO 22L mampu mengcover hingga 10 hektare per jam. Dengan 5 unit beroperasi paralel menggunakan Ground Control App berbahasa Indonesia, koordinasi misi berjalan efisien.</p><p>Hasil: penghematan biaya operasional hingga <strong>60% dibandingkan metode manual</strong> dengan konsistensi distribusi cairan yang jauh lebih merata.</p>',
        'category' => 'Studi Kasus',
        'days_ago' => 0,
    ],
    [
        'title'    => 'FDS Resmi Raih Sertifikasi TKDN 44,85% dari Kemenperin RI',
        'excerpt'  => 'Full Drone Solutions menerima sertifikasi TKDN sebesar 44,85% dari Kementerian Perindustrian RI untuk lini produk FERTO Series.',
        'content'  => '<p>Full Drone Solutions (FDS) dengan bangga mengumumkan sertifikasi resmi <strong>TKDN 44,85%</strong> dari Kementerian Perindustrian RI untuk seluruh lini produk FERTO Series.</p><h2>Apa Artinya?</h2><p>Nilai 44,85% berarti hampir separuh nilai produk FERTO dihasilkan dari dalam negeri — pencapaian signifikan di industri yang selama ini didominasi produk impor. Sertifikasi ini membuka akses ke tender pengadaan pemerintah yang mewajibkan produk ber-TKDN.</p>',
        'category' => 'Berita Perusahaan',
        'days_ago' => 7,
    ],
    [
        'title'    => 'Kolaborasi FDS dan UGM: Riset Drone Pemetaan untuk Manajemen DAS',
        'excerpt'  => 'FDS menandatangani MoU dengan Fakultas Geografi UGM untuk riset bersama drone pemetaan dalam manajemen Daerah Aliran Sungai di Indonesia.',
        'content'  => '<p>Full Drone Solutions dan Fakultas Geografi UGM resmi menandatangani MoU untuk program riset bersama penggunaan drone pemetaan dalam manajemen DAS.</p><h2>Fokus Riset</h2><p>Program 2 tahun ini akan mengkaji efektivitas drone dalam menghasilkan data topografi presisi untuk pengelolaan sumber daya air di DAS-DAS prioritas nasional. Data diproses menggunakan pipeline GIS yang dikembangkan tim software FDS.</p>',
        'category' => 'Kemitraan',
        'days_ago' => 14,
    ],
    [
        'title'    => 'Ground Control App: Software Misi Drone Pertama Berbahasa Indonesia Penuh',
        'excerpt'  => 'Ground Control App dari FDS hadir sebagai software manajemen misi drone yang sepenuhnya berbahasa Indonesia — dari perencanaan rute hingga laporan pasca-misi.',
        'content'  => '<p>Hambatan bahasa seringkali menjadi bottleneck yang diabaikan di industri drone. Ground Control App (GCA) dikembangkan sebagai respons dari feedback ratusan pilot lapangan.</p><h2>Fitur Unggulan</h2><p>Perencanaan waypoint otomatis, monitoring real-time, notifikasi suara berbahasa Indonesia untuk kondisi kritis, serta laporan operasional pasca-misi yang bisa langsung dikirim ke klien.</p>',
        'category' => 'Produk & Teknologi',
        'days_ago' => 21,
    ],
    [
        'title'    => 'Program Sertifikasi Pilot Drone Korporasi FDS Resmi Dibuka',
        'excerpt'  => 'FDS membuka program pelatihan dan sertifikasi pilot drone korporasi untuk tenaga lapangan perusahaan agrikultur, perkebunan, dan instansi pemerintah.',
        'content'  => '<p>FDS resmi meluncurkan Program Sertifikasi Pilot Drone Korporasi — pelatihan komprehensif yang menghasilkan operator drone terampil dari internal organisasi klien.</p><h2>Kurikulum 5 Hari</h2><p>Teori penerbangan, regulasi CASR Part 107 Indonesia, operasi misi agrikultur dan pemetaan, prosedur keselamatan, dan uji kompetensi lapangan bersertifikat. Seluruh materi dalam Bahasa Indonesia.</p>',
        'category' => 'Layanan',
        'days_ago' => 30,
    ],
    [
        'title'    => 'Inspeksi SUTT 150kV dengan Drone Termal FDS: Efisiensi Naik 300%',
        'excerpt'  => 'Penggunaan drone inspeksi termal FDS untuk SUTT 150kV menunjukkan peningkatan efisiensi hingga 300% dibandingkan metode konvensional.',
        'content'  => '<p>Dalam proyek pilot bersama mitra PLN regional, tim FDS berhasil menginspeksi <strong>47 km jalur SUTT 150kV dalam 3 hari kerja</strong> — yang secara konvensional membutuhkan 3 minggu dengan risiko keselamatan jauh lebih tinggi.</p><h2>Hasil</h2><p>Kamera IR mendeteksi 12 titik anomali panas dan 3 isolator degradasi — semua ditangani sebelum berkembang menjadi gangguan jaringan.</p>',
        'category' => 'Studi Kasus',
        'days_ago' => 45,
    ],
];

$cats = ['Studi Kasus', 'Berita Perusahaan', 'Kemitraan', 'Produk & Teknologi', 'Layanan'];
$cat_ids = [];
foreach ($cats as $c) {
    $t = get_term_by('name', $c, 'category');
    $cat_ids[$c] = $t ? $t->term_id : wp_insert_term($c, 'category')['term_id'] ?? 0;
}

$post_inserted = 0;
foreach ($dummy_posts as $p) {
    if (get_page_by_title($p['title'], OBJECT, 'post')) {
        $log[] = ['skip', 'Post sudah ada: ' . $p['title']];
        continue;
    }
    $id = wp_insert_post([
        'post_title'   => $p['title'],
        'post_excerpt' => $p['excerpt'],
        'post_content' => $p['content'],
        'post_status'  => 'publish',
        'post_type'    => 'post',
        'post_date'    => date('Y-m-d H:i:s', strtotime('-' . $p['days_ago'] . ' days')),
        'post_author'  => 1,
    ]);
    if (!is_wp_error($id)) {
        if ($cat_ids[$p['category']] ?? 0) wp_set_post_categories($id, [$cat_ids[$p['category']]]);
        $log[] = ['ok', 'Post dibuat: ' . $p['title']];
        $post_inserted++;
    }
}

if ($post_inserted > 0) {
    $log[] = ['ok', $post_inserted . ' dummy blog posts berhasil dibuat'];
}

?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>FDS Setup</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Inter', sans-serif; background: #f5f5f7; color: #1d1d1f; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
  .card { background: white; border-radius: 24px; padding: 40px; max-width: 620px; width: 100%; box-shadow: 0 8px 40px rgba(0,0,0,0.08); }
  h1 { font-size: 26px; font-weight: 700; margin-bottom: 6px; }
  .sub { font-size: 15px; color: #515154; margin-bottom: 28px; }
  .log-item { display: flex; align-items: flex-start; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f5f5f7; font-size: 14px; line-height: 1.4; }
  .log-item:last-child { border-bottom: none; }
  .icon-ok   { color: #1a7f37; flex-shrink: 0; font-size: 16px; }
  .icon-skip { color: #9a6700; flex-shrink: 0; font-size: 16px; }
  .actions { margin-top: 28px; padding-top: 24px; border-top: 1px solid #e8e8ed; display: flex; flex-wrap: wrap; gap: 12px; }
  .btn { display: inline-flex; align-items: center; gap-6px; background: #0066cc; color: white; font-size: 14px; font-weight: 600; padding: 10px 20px; border-radius: 100px; text-decoration: none; }
  .btn:hover { background: #0055b0; }
  .btn-ghost { background: #f5f5f7; color: #1d1d1f; }
  .btn-ghost:hover { background: #e8e8ed; }
  .warning { background: #fff3cd; border-radius: 12px; padding: 14px 18px; font-size: 13px; color: #664d03; margin-top: 20px; }
  code { background: #f5f5f7; padding: 2px 6px; border-radius: 4px; font-family: monospace; font-size: 12px; }
</style>
</head>
<body>
<div class="card">
  <h1>🚀 FDS Setup Selesai</h1>
  <p class="sub">Konfigurasi WordPress dan data dummy telah berhasil disiapkan.</p>

  <div>
    <?php foreach ($log as [$status, $msg]): ?>
    <div class="log-item">
      <span class="<?= $status === 'ok' ? 'icon-ok' : 'icon-skip' ?>">
        <?= $status === 'ok' ? '✅' : '⚠️' ?>
      </span>
      <span><?= esc_html($msg) ?></span>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="actions">
    <a href="<?= home_url('/blog') ?>" class="btn">→ Buka Halaman Blog</a>
    <a href="<?= home_url('/tentang-kami') ?>" class="btn btn-ghost">→ Tentang Kami</a>
    <a href="<?= home_url('/') ?>" class="btn btn-ghost">→ Beranda</a>
  </div>

  <div class="warning">
    ⚠️ <strong>Hapus file ini setelah selesai!</strong><br>
    File: <code>/wp-content/themes/fds-theme/fds-setup.php</code>
  </div>
</div>
</body>
</html>
