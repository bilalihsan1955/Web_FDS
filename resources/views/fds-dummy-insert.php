<?php
/**
 * FDS Dummy Blog Posts Inserter
 * Akses sekali via browser: http://fds.local/wp-content/themes/fds-theme/fds-dummy-insert.php
 * Hapus file ini setelah dijalankan!
 */

// Load WordPress
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php';

// Hanya admin yang boleh menjalankan ini
if (!current_user_can('manage_options')) {
    wp_die('Akses ditolak. Login sebagai admin WP terlebih dahulu, kemudian akses halaman ini lagi.');
}

$dummy_posts = [
    [
        'title'   => 'FERTO 22L Berhasil Uji Lapangan di Lahan Tebu 500 Ha Jawa Timur',
        'excerpt' => 'Drone FERTO 22L sukses menyelesaikan misi penyemprotan seluas 500 hektare lahan tebu dalam waktu 4 hari operasional tanpa kendala teknis — menjadi rekor operasional terbesar untuk armada drone lokal Indonesia.',
        'content' => '<p>Tim lapangan Full Drone Solutions baru saja menyelesaikan proyek penyemprotan massal di perkebunan tebu seluas 500 hektare di Jawa Timur menggunakan armada 5 unit FERTO 22L. Proyek yang berlangsung selama 4 hari ini menjadi salah satu operasi drone agrikultur terbesar yang pernah dilakukan dengan perangkat produksi dalam negeri.</p><h2>Spesifikasi Operasional</h2><p>Setiap unit FERTO 22L mampu mengcover hingga 10 hektare per jam dalam kondisi angin normal. Dengan 5 unit beroperasi secara paralel menggunakan Ground Control App berbahasa Indonesia, koordinasi misi berjalan efisien tanpa hambatan komunikasi.</p><p>Hasilnya: penghematan biaya operasional hingga <strong>60% dibandingkan metode manual</strong>, dengan konsistensi distribusi cairan yang jauh lebih merata berkat sistem nozzle presisi FERTO.</p><h2>Teknologi di Balik Keberhasilan</h2><p>Ketahanan IP67 pada FERTO 22L juga terbukti saat hujan ringan terjadi di hari ketiga operasional. Semua unit tetap beroperasi tanpa gangguan.</p>',
        'category' => 'Studi Kasus',
        'days_ago' => 0,
    ],
    [
        'title'   => 'FDS Resmi Raih Sertifikasi TKDN 44,85% dari Kemenperin RI',
        'excerpt' => 'Full Drone Solutions secara resmi menerima sertifikasi TKDN sebesar 44,85% dari Kementerian Perindustrian RI untuk lini produk FERTO Series — salah satu drone dengan TKDN tertinggi di segmennya.',
        'content' => '<p>Full Drone Solutions (FDS) dengan bangga mengumumkan pencapaian sertifikasi resmi <strong>TKDN (Tingkat Komponen Dalam Negeri) sebesar 44,85%</strong> dari Kementerian Perindustrian Republik Indonesia untuk seluruh lini produk FERTO Series.</p><h2>Apa Artinya TKDN 44,85%?</h2><p>Nilai 44,85% berarti hampir separuh dari nilai produk FERTO dihasilkan dari dalam negeri — sebuah pencapaian signifikan di industri drone yang selama ini didominasi produk impor. Sertifikasi ini membuka akses bagi FDS untuk mengikuti tender pengadaan pemerintah yang mewajibkan produk ber-TKDN.</p><h2>Proses Sertifikasi</h2><p>Proses verifikasi dilakukan selama 6 bulan oleh auditor independen yang ditunjuk Kemenperin, mencakup audit rantai pasok, proses manufaktur di workshop Yogyakarta, hingga kandungan lokal pada komponen elektronik dan airframe.</p>',
        'category' => 'Berita Perusahaan',
        'days_ago' => 7,
    ],
    [
        'title'   => 'Kolaborasi FDS dan UGM: Riset Drone Pemetaan untuk Manajemen DAS',
        'excerpt' => 'FDS menandatangani MoU dengan Fakultas Geografi UGM untuk riset bersama penggunaan drone pemetaan dalam manajemen Daerah Aliran Sungai (DAS) di Indonesia.',
        'content' => '<p>Full Drone Solutions dan Fakultas Geografi Universitas Gadjah Mada (UGM) resmi menandatangani MoU untuk program riset bersama yang berfokus pada penggunaan teknologi drone pemetaan udara dalam manajemen Daerah Aliran Sungai (DAS).</p><h2>Fokus Riset</h2><p>Program riset yang berlangsung selama 2 tahun ini akan mengkaji efektivitas drone dalam menghasilkan data topografi presisi untuk mendukung pengelolaan sumber daya air di DAS-DAS prioritas Indonesia. Data akan diproses menggunakan pipeline GIS yang dikembangkan bersama tim software FDS.</p><h2>Manfaat bagi Industri</h2><p>Kolaborasi ini diharapkan menghasilkan SOP pemetaan drone untuk keperluan hidrologi yang dapat diadopsi secara nasional.</p>',
        'category' => 'Kemitraan',
        'days_ago' => 14,
    ],
    [
        'title'   => 'Ground Control App: Software Misi Drone Pertama Berbahasa Indonesia Penuh',
        'excerpt' => 'Ground Control App (GCA) dari FDS hadir sebagai satu-satunya software manajemen misi drone yang sepenuhnya berbahasa Indonesia — dari perencanaan rute, monitoring real-time, hingga laporan pasca-misi.',
        'content' => '<p>Dalam dunia drone industri, hambatan bahasa seringkali menjadi bottleneck yang diabaikan. Pilot lapangan yang terampil secara teknis kerap kesulitan memaksimalkan kemampuan drone karena antarmuka software berbahasa Inggris atau Mandarin.</p><h2>Lahir dari Kebutuhan Lapangan</h2><p>Ground Control App (GCA) dikembangkan oleh tim software FDS sebagai respons langsung dari feedback ratusan pilot lapangan. GCA menyediakan antarmuka penuh Bahasa Indonesia untuk seluruh alur kerja misi — perencanaan waypoint, pengaturan parameter semprot/pemetaan, monitoring real-time, hingga laporan operasional pasca-misi.</p><h2>Fitur Unggulan GCA</h2><p>Sistem perencanaan otomatis berbasis peta lahan, notifikasi suara berbahasa Indonesia untuk kondisi kritis, serta mode "Pilot Pemula" yang menyederhanakan antarmuka untuk operator baru.</p>',
        'category' => 'Produk & Teknologi',
        'days_ago' => 21,
    ],
    [
        'title'   => 'Program Sertifikasi Pilot Drone Korporasi FDS Resmi Dibuka',
        'excerpt' => 'FDS membuka program pelatihan dan sertifikasi pilot drone korporasi yang dirancang untuk tenaga lapangan perusahaan agrikultur, perkebunan, dan instansi pemerintah.',
        'content' => '<p>Full Drone Solutions resmi meluncurkan <strong>Program Sertifikasi Pilot Drone Korporasi FDS</strong> — program pelatihan komprehensif yang menghasilkan operator drone terampil dari internal organisasi klien.</p><h2>Kenapa Pilot Internal?</h2><p>Banyak perusahaan yang telah berinvestasi di armada drone namun bergantung pada operator pihak ketiga. Program sertifikasi FDS memungkinkan perusahaan membangun kapabilitas pilot internal yang kompeten dan bersertifikat.</p><h2>Kurikulum Program</h2><p>Program berlangsung 5 hari intensif mencakup: teori penerbangan, regulasi CASR Part 107 Indonesia, operasi misi agrikultur dan pemetaan, prosedur keselamatan, serta uji kompetensi lapangan bersertifikat. Seluruh materi dalam Bahasa Indonesia.</p>',
        'category' => 'Layanan',
        'days_ago' => 30,
    ],
    [
        'title'   => 'Inspeksi Jalur SUTT 150kV dengan Drone Termal FDS: Efisiensi Naik 300%',
        'excerpt' => 'Penggunaan drone inspeksi termal FDS untuk pemeriksaan SUTT 150kV menunjukkan peningkatan efisiensi inspeksi hingga 300% dibandingkan metode konvensional.',
        'content' => '<p>Inspeksi rutin infrastruktur kelistrikan adalah salah satu pekerjaan paling kritis sekaligus berbahaya di industri energi. Metode konvensional mengharuskan teknisi mendekati jalur tegangan tinggi secara langsung.</p><h2>Solusi: Drone Inspeksi Termal</h2><p>Menggunakan drone dengan kamera inframerah (IR) resolusi tinggi, tim inspeksi dapat mendeteksi anomali termal pada isolator, konektor, dan konduktor SUTT dari jarak aman tanpa perlu pemadaman jaringan.</p><p>Dalam proyek pilot bersama mitra PLN regional, tim FDS berhasil menginspeksi <strong>47 kilometer jalur SUTT 150kV dalam 3 hari kerja</strong> — yang secara konvensional membutuhkan 3 minggu.</p><h2>Hasil</h2><p>Sistem termal mengidentifikasi 12 titik anomali panas pada konektor dan 3 isolator yang menunjukkan degradasi — semua ditangani sebelum berkembang menjadi gangguan jaringan.</p>',
        'category' => 'Studi Kasus',
        'days_ago' => 45,
    ],
];

$categories = ['Studi Kasus', 'Berita Perusahaan', 'Kemitraan', 'Produk & Teknologi', 'Layanan'];
$created_cats = [];

foreach ($categories as $cat_name) {
    $existing = get_term_by('name', $cat_name, 'category');
    if ($existing) {
        $created_cats[$cat_name] = $existing->term_id;
    } else {
        $new_cat = wp_insert_term($cat_name, 'category');
        $created_cats[$cat_name] = is_array($new_cat) ? $new_cat['term_id'] : 0;
    }
}

$inserted = 0;
$skipped  = 0;
$errors   = 0;
$log      = [];

foreach ($dummy_posts as $data) {
    $existing = get_page_by_title($data['title'], OBJECT, 'post');
    if ($existing) {
        $log[] = ['status' => 'skip', 'title' => $data['title']];
        $skipped++;
        continue;
    }

    $post_id = wp_insert_post([
        'post_title'   => $data['title'],
        'post_excerpt' => $data['excerpt'],
        'post_content' => $data['content'],
        'post_status'  => 'publish',
        'post_type'    => 'post',
        'post_date'    => date('Y-m-d H:i:s', strtotime('-' . $data['days_ago'] . ' days')),
        'post_author'  => 1,
    ]);

    if (is_wp_error($post_id)) {
        $log[] = ['status' => 'error', 'title' => $data['title'], 'msg' => $post_id->get_error_message()];
        $errors++;
    } else {
        $cat_id = $created_cats[$data['category']] ?? 0;
        if ($cat_id) wp_set_post_categories($post_id, [$cat_id]);
        $log[] = ['status' => 'ok', 'title' => $data['title'], 'id' => $post_id, 'url' => get_permalink($post_id)];
        $inserted++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>FDS Dummy Posts Inserter</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Inter', sans-serif; max-width: 680px; margin: 60px auto; padding: 0 24px; background: #f5f5f7; color: #1d1d1f; }
  h1  { font-size: 28px; font-weight: 600; margin-bottom: 8px; }
  .card { background: white; border-radius: 16px; padding: 24px; margin: 16px 0; box-shadow: 0 2px 16px rgba(0,0,0,0.06); }
  .ok   { color: #1a7f37; }
  .skip { color: #9a6700; }
  .err  { color: #cf222e; }
  .summary { font-size: 18px; margin-bottom: 20px; }
  a   { color: #0066cc; text-decoration: none; font-weight: 600; }
  a:hover { text-decoration: underline; }
  .warning { background: #fff8e1; border-radius: 12px; padding: 14px 18px; font-size: 14px; color: #7a5c00; margin-top: 16px; }
</style>
</head>
<body>
  <h1>🚀 FDS Dummy Posts</h1>
  <div class="card summary">
    <p>✅ <strong><?= $inserted ?> artikel dibuat</strong> &nbsp; ⚠️ <?= $skipped ?> dilewati (sudah ada) &nbsp; ❌ <?= $errors ?> error</p>
    <p><a href="<?= home_url('/blog') ?>">→ Buka halaman Blog</a> &nbsp;&nbsp; <a href="<?= admin_url('edit.php') ?>">→ WP Admin Posts</a></p>
  </div>

  <div class="card">
    <?php foreach ($log as $item): ?>
      <?php if ($item['status'] === 'ok'): ?>
        <p class="ok">✅ <a href="<?= $item['url'] ?>" target="_blank"><?= esc_html($item['title']) ?></a> (ID: <?= $item['id'] ?>)</p>
      <?php elseif ($item['status'] === 'skip'): ?>
        <p class="skip">⚠️ Sudah ada: <?= esc_html($item['title']) ?></p>
      <?php else: ?>
        <p class="err">❌ Error: <?= esc_html($item['title']) ?> — <?= esc_html($item['msg']) ?></p>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <div class="warning">
    ⚠️ <strong>Hapus file ini setelah selesai!</strong><br>
    Lokasi: <code>/wp-content/themes/fds-theme/fds-dummy-insert.php</code>
  </div>
</body>
</html>
