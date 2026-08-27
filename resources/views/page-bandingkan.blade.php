@extends('layouts.app')

@section('content')
@php
  // 1. DATA MASTER DRONE RESMI FDS (100% IDENTIK DENGAN SINGLE-DRONE.BLADE.PHP)
  $drones = [
    'ferto-5l' => [
      'slug'      => 'ferto-5l',
      'name'      => 'FERTO 5',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Kompak & Lincah',
      'tagline'   => 'Drone Pertanian FERTO 5 — Platform UAV Agrikultur modular dengan mobilitas tinggi.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Kapasitas Tangki', '5 Liter'],
        ['Durasi Terbang', '10 – 15 menit'],
        ['Sistem Daya (Baterai)', '8.000 mAh'],
        ['Produktivitas Semprot', '1 Ha / jam'],
        ['Kecepatan Jelajah', '2 – 6 m/s'],
        ['Sistem Otonomi & Navigasi', 'Otonom & Manual, Terrain Following, Fail-Safe'],
        ['Ground Control Station', 'FDS STATION (Bahasa Indonesia)'],
        ['Sertifikasi & Standar', 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015'],
      ],
      'desc'      => 'FERTO 5 didesain sebagai platform multirotor modular dengan mobilitas tinggi untuk menjangkau area berbukit, terasering, dan lahan perkebunan dengan kontur ekstrem. Dilengkapi fitur terrain-following otomatis dan sistem kendali FDS STATION, drone ini menjamin presisi penyemprotan pupuk cair maupun pestisida secara merata dengan produktivitas 1 Ha per jam.',
      'for'       => [
        'Lahan berbukit & terasering — Bobot ringan dan dimensi ringkas mempermudah manuver di area sempit.',
        'Petani hortikultura & kebun — Efisiensi bahan kimia >50% dengan penyemprotan droplet presisi.',
        'Penyedia jasa semprot mandiri — Mobilitas tinggi mudah dibawa dengan sepeda motor ke pelosok sawah.',
        'Dukungan purna jual resmi — Jaringan servis dan suku cadang asli lokal FDS siap pakai.'
      ],
      'stat1_num' => 'SNI', 'stat1_lbl' => 'SNI 9199:2023',
      'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
      'stat3_num' => '100%', 'stat3_lbl' => 'FDS STATION GCS',
      'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
    ],
    'ferto-10l' => [
      'slug'      => 'ferto-10l',
      'name'      => 'FERTO 10',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Terlaris',
      'tagline'   => 'Drone Pertanian FERTO 10 — Pilihan terbaik kelompok tani dengan produktivitas andal.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Kapasitas Tangki', '10 Liter'],
        ['Durasi Terbang', '12 – 15 menit'],
        ['Sistem Daya (Baterai)', '16.000 mAh'],
        ['Produktivitas Semprot', '1 – 1,5 Ha / jam'],
        ['Kecepatan Jelajah', '2 – 6 m/s'],
        ['Sistem Otonomi & Navigasi', 'Otonom & Manual, Terrain Following, Fail-Safe'],
        ['Ground Control Station', 'FDS STATION (Bahasa Indonesia)'],
        ['Sertifikasi & Standar', 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015'],
      ],
      'desc'      => 'FERTO 10 adalah varian terlaris FDS yang menawarkan keseimbangan optimal antara kapasitas muatan 10 liter, ketahanan baterai 16.000 mAh, dan produktivitas 1 - 1,5 Ha/jam. Menggunakan rangka karbon komposit buatan dalam negeri berstandar SNI 9199:2023, drone ini menjadi tulang punggung modernisasi pertanian di berbagai wilayah Indonesia.',
      'for'       => [
        'Kelompok tani & Gapoktan — Titik temu terbaik antara kapasitas operasional dan efisiensi investasi.',
        'Koperasi pertanian — Mengurangi beban biaya tenaga kerja semprot manual hingga 60%.',
        'Program ketahanan pangan Bank Indonesia & Bappenas — Terbukti andal di berbagai proyek percontohan nasional.',
        'Suku cadang asli terjamin — Ketersediaan komponen cepat dari workshop Yogyakarta.'
      ],
      'stat1_num' => 'SNI', 'stat1_lbl' => 'SNI 9199:2023',
      'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
      'stat3_num' => '100%', 'stat3_lbl' => 'FDS STATION GCS',
      'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
    ],
    'ferto-15l' => [
      'slug'      => 'ferto-15l',
      'name'      => 'FERTO 15',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Profesional',
      'tagline'   => 'Drone Pertanian FERTO 15 — Kapasitas 17 Liter dengan produktivitas tinggi 8 Ha/jam.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Kapasitas Tangki', '17 Liter (15 – 17 Liter)'],
        ['Durasi Terbang', '15 – 25 menit'],
        ['Sistem Daya (Baterai)', '16.000 mAh'],
        ['Produktivitas Semprot', '8 Ha / jam'],
        ['Kecepatan Jelajah', '2 – 6 m/s'],
        ['Sistem Otonomi & Navigasi', 'Otonom & Manual, Terrain Following, Fail-Safe'],
        ['Ground Control Station', 'FDS STATION (Bahasa Indonesia)'],
        ['Sertifikasi & Standar', 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015'],
      ],
      'desc'      => 'FERTO 15 menghadirkan kapasitas tangki 17 Liter dengan efisiensi tinggi, mampu menyelesaikan penyemprotan hingga 8 hektare per jam. Dilengkapi sistem propulsi berdaya tahan 15-25 menit dan radar terrain-following presisi, drone ini sangat cocok untuk operasional komersial menengah ke atas pada komoditas tebu, jagung, dan hortikultura luas.',
      'for'       => [
        'Perkebunan tebu & jagung skala komersial — Menyemprot cepat 8 Ha/jam dengan cakupan merata.',
        'Kontraktor jasa perlindungan tanaman — Durasi terbang hingga 25 menit untuk ritme kerja lapangan yang padat.',
        'Dual mode Sprayer & Spreader — Kompatibel dengan tangki granule spreader untuk penyebaran pupuk butir.',
        'Dukungan teknis pilot bersertifikat — Layanan pendampingan dan pelatihan pilot resmi FDS.'
      ],
      'stat1_num' => 'SNI', 'stat1_lbl' => 'SNI 9199:2023',
      'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
      'stat3_num' => '8 Ha/j', 'stat3_lbl' => 'Produktivitas Semprot',
      'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
    ],
    'ferto-22l' => [
      'slug'      => 'ferto-22l',
      'name'      => 'FERTO 22',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Enterprise',
      'tagline'   => 'Drone Pertanian FERTO 22 — Kapasitas enterprise 22L untuk perkebunan skala besar.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Kapasitas Tangki', '22 Liter'],
        ['Durasi Terbang', '20 – 25 menit'],
        ['Sistem Daya (Baterai)', '22.000 mAh'],
        ['Produktivitas Semprot', '8,5 Ha / jam'],
        ['Kecepatan Jelajah', '5,24 m/s'],
        ['Sistem Otonomi & Navigasi', 'Semi-to-Fully Autonomous, Terrain Following, Fail-Safe'],
        ['Ground Control Station', 'FDS STATION (Bahasa Indonesia)'],
        ['Sertifikasi & Standar', 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015'],
      ],
      'desc'      => 'FERTO 22 adalah varian enterprise andalan FDS untuk industri perkebunan sawit, tebu, dan tanaman industri berskala ribuan hektare. Ditenagai baterai 22.000 mAh dengan kecepatan jelajah 5,24 m/s, drone ini mampu menuntaskan 8,5 hektare per jam secara otonom dan terintegrasi penuh ke dalam sistem FDS STATION.',
      'for'       => [
        'Perkebunan sawit & tanaman industri — Mengatasi tantangan lahan luas dengan kecepatan semprot tinggi.',
        'BUMN Perkebunan & Korporasi Agrikultur — Memenuhi syarat pengadaan pemerintah dengan TKDN+BMP resmi.',
        'Manajemen armada perkebunan — Terintegrasi dengan analitik kesehatan tanaman berbasis multispektral & NDVI.',
        'Purna jual resmi & garansi lokal — Layanan servis dan suku cadang asli tanpa ketergantungan impor.'
      ],
      'stat1_num' => 'SNI', 'stat1_lbl' => 'SNI 9199:2023',
      'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
      'stat3_num' => '8,5 Ha/j', 'stat3_lbl' => 'Produktivitas Semprot',
      'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
    ],
    'ferto-30l' => [
      'slug'      => 'ferto-30l',
      'name'      => 'FERTO 30',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Heavy Duty',
      'tagline'   => 'Drone Pertanian FERTO 30 — Kapasitas muat masif 30L dengan produktivitas 15 Ha/jam.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Kapasitas Tangki', '30 Liter'],
        ['Durasi Terbang', '20 – 30 menit'],
        ['Sistem Daya (Baterai)', '28.000 mAh'],
        ['Produktivitas Semprot', '15 Ha / jam'],
        ['Kecepatan Jelajah', '5,24 m/s'],
        ['Sistem Otonomi & Navigasi', 'Semi-to-Fully Autonomous, Terrain Following, Fail-Safe'],
        ['Ground Control Station', 'FDS STATION (Bahasa Indonesia)'],
        ['Sertifikasi & Standar', 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015'],
      ],
      'desc'      => 'FERTO 30 menghadirkan lompatan kapasitas tangki 30 Liter yang dirancang untuk kebutuhan agribisnis skala masif. Dengan sistem baterai high-capacity 28.000 mAh dan daya jangkau penerbangan hingga 30 menit, drone ini mampu menghasilkan produktivitas 15 Ha per jam, memangkas waktu kerja dan biaya operasional secara drastis.',
      'for'       => [
        'Mega perkebunan sawit & tebu — Produktivitas 15 Ha/jam mempercepat target penyemprotan harian.',
        'Aplikasi pupuk & pestisida volume tinggi — Tangki 30L meminimalkan frekuensi pendaratan untuk isi ulang.',
        'Pengendalian hama serentak — Menuntaskan ratusan hektare lahan dalam waktu singkat sebelum hama menyebar.',
        'Konstruksi karbon komposit kokoh — Tahan cuaca ekstrem dengan rangka material terbaik.'
      ],
      'stat1_num' => 'SNI', 'stat1_lbl' => 'SNI 9199:2023',
      'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
      'stat3_num' => '15 Ha/j', 'stat3_lbl' => 'Produktivitas Semprot',
      'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
    ],
    'ferto-50l' => [
      'slug'      => 'ferto-50l',
      'name'      => 'FERTO 50',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Ultra Capacity',
      'tagline'   => 'Drone Pertanian FERTO 50 — Kapasitas puncak 50L untuk produktivitas agrikultur tanpa tanding.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Kapasitas Tangki', '50 Liter'],
        ['Durasi Terbang', '20 – 30 menit'],
        ['Sistem Daya (Baterai)', '28.000 mAh'],
        ['Produktivitas Semprot', '15 Ha / jam'],
        ['Kecepatan Jelajah', '6 m/s'],
        ['Sistem Otonomi & Navigasi', 'Semi-to-Fully Autonomous, Terrain Following, Fail-Safe'],
        ['Ground Control Station', 'FDS STATION (Bahasa Indonesia)'],
        ['Sertifikasi & Standar', 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015'],
      ],
      'desc'      => 'FERTO 50 adalah platform UAV agrikultur dengan muatan tertinggi di lini FDS. Membawa tangki berkapasitas 50 Liter dengan sistem propulsi bertenaga raksasa dan kecepatan jelajah hingga 6 m/s, drone ini diciptakan untuk menjawab tantangan operasional perkebunan agrikultur terbesar di Indonesia dengan efisiensi maksimal.',
      'for'       => [
        'Perkebunan konglomerasi & agroindustri raksasa — Menangani area ribuan hektare dengan armada minimal.',
        'Penyebaran pupuk & pestisida intensif — Muatan 50L memaksimalkan efisiensi setiap sorti penerbangan.',
        'Misi otomatis skala besar — Perencanaan rute cerdas dan pemantauan real-time via FDS STATION.',
        'Dukungan logistik & purna jual komprehensif — Paket perawatan berkala dan penyediaan suku cadang resmi.'
      ],
      'stat1_num' => 'SNI', 'stat1_lbl' => 'SNI 9199:2023',
      'stat2_num' => '60,74%', 'stat2_lbl' => 'TKDN + BMP',
      'stat3_num' => '50 Liter', 'stat3_lbl' => 'Kapasitas Tangki',
      'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
    ],
    'deltav' => [
      'slug'      => 'deltav',
      'name'      => 'DELTAV',
      'kategori'  => 'Pemetaan & GIS',
      'badge'     => 'Hybrid VTOL',
      'tagline'   => 'Platform UAV Pemetaan Fixed-Wing VTOL Hybrid — Jangkauan 60 km untuk akuisisi geospasial area luas.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Bentang Sayap (Wingspan)', '2.000 mm'],
        ['Konfigurasi Motor', '4 Rotor VTOL + 1 Rotor Jelajah (Cruise)'],
        ['Berat Lepas Landas (MTOW)', '10 kg (10.000 g)'],
        ['Kapasitas Payload', '1 – 2 kg (Kamera RGB, LiDAR, Multispektral)'],
        ['Durasi Terbang', '60 – 120 menit'],
        ['Kecepatan Jelajah', '15 – 22 m/s'],
        ['Jangkauan Misi (Range)', 'Hingga 60 km'],
        ['Material Rangka', 'Komposit Hibrida (Carbon Composite)'],
        ['Sistem Kendali & Misi', 'Semi-to-Fully Autonomous, FDS STATION GCS'],
      ],
      'desc'      => 'DELTAV adalah pesawat UAV fixed-wing berteknologi Hybrid VTOL (Vertical Takeoff and Landing) yang menggabungkan kemudahan lepas landas tegak lurus tanpa landasan pacu dengan kecepatan jelajah serta efisiensi aerodinamis pesawat sayap tetap. Dengan jangkauan hingga 60 km dan durasi terbang 60-120 menit, DELTAV adalah solusi terbaik untuk survei topografi, ortofoto beresolusi tinggi, pemetaan kehutanan, dan akuisisi data geospasial area luas dalam sekali terbang.',
      'for'       => [
        'Survei topografi & konstruksi — Menghemat waktu 70-80% untuk pemodelan 3D, ortomozaik, dan perhitungan cut & fill.',
        'Kehutanan & lingkungan — Pemetaan daerah aliran sungai (DAS), tutupan kanopi, dan progres reklamasi tambang 80% lebih cepat.',
        'Pertambangan & kuari — Pemetaan kontur presisi dan pemantauan batas konsesi tambang.',
        'Perencanaan tata ruang & GIS nasional — Akurasi data geospasial sub-sentimeter siap integrasi CAD & BIM.'
      ],
      'stat1_num' => '2.000mm', 'stat1_lbl' => 'Bentang Sayap',
      'stat2_num' => '60 km', 'stat2_lbl' => 'Jangkauan Misi',
      'stat3_num' => '120 min', 'stat3_lbl' => 'Durasi Terbang Maks',
      'stat4_num' => 'VTOL', 'stat4_lbl' => 'Lepas Landas Vertikal',
    ],
    'multipurpose' => [
      'slug'      => 'multipurpose',
      'name'      => 'MULTIPURPOSE',
      'kategori'  => 'Pemetaan & Inspeksi',
      'badge'     => 'Modular UAV',
      'tagline'   => 'Platform UAV Modular Serbaguna — Integrasi payload termal, optical zoom, & sensor inspeksi.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Kapasitas Payload', '5 kg'],
        ['Durasi Terbang', '15 – 30 menit'],
        ['Sistem Daya (Baterai)', '8.000 mAh'],
        ['Sensor Kompatibel', 'Kamera Termal IR, 20x Optical Zoom, LiDAR, Multispektral'],
        ['Mode Penerbangan', 'Manual, Task Following, Semi-to-Fully Autonomous'],
        ['Sistem Proteksi', 'Konstruksi tahan cuaca & sistem fail-safe mandiri'],
        ['Software Pengendali', 'FDS STATION Real-Time Monitoring & AI Analytics'],
        ['Aplikasi Utama', 'Inspeksi Jaringan Listrik 150kV, Solar PV, Pipa Migas, & Struktur'],
      ],
      'desc'      => 'MULTIPURPOSE dirancang sebagai platform UAV modular yang fleksibel untuk berbagai misi kustom. Mampu mengangkut payload hingga 5 kg dengan integrasi berbagai sensor canggih seperti kamera termal inframerah, optik zoom 20x, hingga sensor LiDAR. Sangat andal untuk inspeksi aset kritikal seperti jaringan transmisi listrik 150 kV, ladang panel surya, tangki minyak & gas, serta infrastruktur jembatan dan gedung tinggi tanpa risiko keselamatan kerja.',
      'for'       => [
        'Inspeksi transmisi listrik 150 kV — 5x lebih cepat tanpa perlu pemadaman listrik dan tanpa bekerja di ketinggian.',
        'Inspeksi ladang energi surya (Solar PV) — Deteksi dini hotspot dan sel rusak berbasis AI untuk mencegah kehilangan energi.',
        'Inspeksi migas & cerobong suar (Flare Stacks) — Deteksi kebocoran dan korosi tanpa mematikan operasi kilang.',
        'Inspeksi struktur jembatan & konstruksi — Pemeriksaan keretakan mikro struktur beton dan baja.'
      ],
      'stat1_num' => '5 kg', 'stat1_lbl' => 'Kapasitas Payload',
      'stat2_num' => '30 min', 'stat2_lbl' => 'Durasi Terbang Maks',
      'stat3_num' => 'Termal/AI', 'stat3_lbl' => 'Sensor Kompatibel',
      'stat4_num' => '150 kV', 'stat4_lbl' => 'Inspeksi Aset Kritikal',
    ],
    'delfro' => [
      'slug'      => 'delfro',
      'name'      => 'DELFRO',
      'kategori'  => 'Cargo & Logistik',
      'badge'     => 'Logistics UAV',
      'tagline'   => 'Platform UAV Kargo Logistik Ringan — Distribusi logistik cepat dan aman ke area sulit dijangkau.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Kapasitas Payload', '3 – 10 kg'],
        ['Berat Lepas Landas (MTOW)', '15 kg'],
        ['Dimensi Kotak Payload', '20 x 20 x 30 cm'],
        ['Kecepatan Jelajah / Maks', '2 – 6 m/s'],
        ['Waktu Terbang', '10 – 15 menit'],
        ['Ukuran Propeller', '18-inch High-Efficiency Carbon Propeller'],
        ['Mode Pengoperasian', 'Otonom (Waypoint Cargo Route) & Manual Fail-Safe'],
        ['Ground Control Software', 'FDS STATION Logistics Management'],
      ],
      'desc'      => 'DELFRO adalah drone kargo otonom yang dikembangkan khusus untuk distribusi logistik ringan yang cepat, efisien, dan aman. Dengan kapasitas angkut 3 hingga 10 kg dan kompartemen kargo berukuran 20 x 20 x 30 cm, DELFRO menjadi solusi mutakhir untuk pengiriman sampel medis, pasokan darurat kebencanaan, suku cadang penting, dan logistik ekspres ke wilayah kepulauan atau daerah terisolir yang sulit dijangkau transportasi darat.',
      'for'       => [
        'Logistik medis & darurat bencana — Pengiriman cepat obat-obatan, kantong darah, dan sampel laboratorium ke lokasi terpencil.',
        'Pengiriman suku cadang industri — Menghubungkan offshore platform atau site tambang dengan warehouse secara kilat.',
        'Ekspedisi & kurir last-mile — Alternatif logistik ramah lingkungan untuk melintasi sungai, bukit, atau selat.',
        'Manajemen rute otomatis — Pemantauan status kargo dan rute penerbangan real-time via FDS STATION.'
      ],
      'stat1_num' => '10 kg', 'stat1_lbl' => 'Kapasitas Payload',
      'stat2_num' => '15 kg', 'stat2_lbl' => 'MTOW Maksimum',
      'stat3_num' => '18"', 'stat3_lbl' => 'Carbon Propeller',
      'stat4_num' => 'Auto', 'stat4_lbl' => 'Waypoint Route',
    ],
    'rebo' => [
      'slug'      => 'rebo',
      'name'      => 'REBO',
      'kategori'  => 'Reboisasi & Konservasi',
      'badge'     => 'Heavy-Duty Seedball',
      'tagline'   => 'Platform UAV Reboisasi & Restorasi Hutan — Penyebaran biji seedball presisi tinggi secara otonom.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Kapasitas Payload Biji', '20 kg'],
        ['Durasi Terbang', '15 – 20 menit'],
        ['Sistem Daya (Baterai)', '22.000 mAh'],
        ['Mode Misi', 'Otonom Penuh (Auto Seedball Dispensing Grid)'],
        ['Sistem Dispenser', 'Penyebar seedball otomatis terkalibrasi'],
        ['Rangka & Proteksi', 'Komposit Karbon Tahan Cuaca Ekstrem'],
        ['Software Perencanaan Misi', 'FDS STATION Reforestation Mission Planning'],
        ['Kolaborasi Riset', 'Didukung riset bersama UGM & Mitra Swiss'],
      ],
      'desc'      => 'REBO adalah UAV heavy-duty khusus yang dirancang untuk mendukung misi reboisasi hutan, restorasi lahan kritis, dan reklamasi area pasca-tambang. Mampu mengangkut hingga 20 kg seedball (biji tanaman berkapsul nutrisi) dalam satu kali sorti, REBO menabur biji secara presisi mengikuti pola koordinat otonom pada lereng curam atau hutan lebat yang mustahil diakses penanam manual.',
      'for'       => [
        'Restorasi hutan lindung & lereng curam — Menghijaukan kembali tebing dan medan berbahaya tanpa membahayakan petugas.',
        'Reklamasi lahan bekas tambang — Mempercepat pemulihan vegetasi lahan tambang sesuai regulasi lingkungan.',
        'Konservasi daerah aliran sungai (DAS) — Penyebaran bibit pohon penyangga air secara masif dan terstruktur.',
        'Riset kehutanan berkelanjutan — Dikembangkan berdasarkan riset lapangan kolaboratif UGM dan institusi internasional.'
      ],
      'stat1_num' => '20 kg', 'stat1_lbl' => 'Payload Seedball',
      'stat2_num' => '22.000mAh', 'stat2_lbl' => 'Baterai Daya Tinggi',
      'stat3_num' => 'Otonom', 'stat3_lbl' => 'Dispenser Presisi',
      'stat4_num' => 'Riset', 'stat4_lbl' => 'UGM & Swiss',
    ],
  ];

  // 2. ENRICH WITH WORDPRESS CPT 'drone' DATABASE
  $db_drones = get_posts([
    'post_type'      => 'drone',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'ID',
    'order'          => 'ASC',
  ]);

  $catalog = $drones;

  foreach ($db_drones as $dp) {
    $slug    = $dp->post_name;
    $post_id = $dp->ID;

    $c_terms = get_the_terms($post_id, 'kategori_drone');
    $cat_name = (!empty($c_terms) && !is_wp_error($c_terms)) ? $c_terms[0]->name : (get_post_meta($post_id, 'drone_kategori', true) ?: 'Agrikultur');

    $thumb = get_the_post_thumbnail_url($post_id, 'full');
    $specs_img_db = get_post_meta($post_id, 'drone_specs_img', true);

    $droneImgKey      = 'drone_' . str_replace('-', '_', str_replace('ferto-', '', $slug));
    $droneImgFallback = 'https://picsum.photos/seed/' . $slug . '-hero/1600/700';

    $item = $catalog[$slug] ?? [
      'slug'      => $slug,
      'name'      => get_the_title($post_id),
      'kategori'  => $cat_name,
      'badge'     => get_post_meta($post_id, 'drone_badge', true) ?: 'Produk UAV',
      'tagline'   => get_post_meta($post_id, 'drone_tagline', true) ?: get_the_excerpt($post_id),
      'color'     => '#0066cc',
      'specs'     => [],
      'desc'      => get_post_meta($post_id, 'drone_desc', true) ?: get_the_content(null, false, $post_id),
      'for'       => [],
      'stat1_num' => get_post_meta($post_id, 'drone_stat1_num', true) ?: 'SNI',
      'stat1_lbl' => get_post_meta($post_id, 'drone_stat1_lbl', true) ?: 'SNI 9199:2023',
      'stat2_num' => get_post_meta($post_id, 'drone_stat2_num', true) ?: '60,74%',
      'stat2_lbl' => get_post_meta($post_id, 'drone_stat2_lbl', true) ?: 'TKDN + BMP',
      'stat3_num' => get_post_meta($post_id, 'drone_stat3_num', true) ?: '100%',
      'stat3_lbl' => get_post_meta($post_id, 'drone_stat3_lbl', true) ?: 'FDS STATION GCS',
      'stat4_num' => get_post_meta($post_id, 'drone_stat4_num', true) ?: 'Garansi',
      'stat4_lbl' => get_post_meta($post_id, 'drone_stat4_lbl', true) ?: 'Purna Jual Resmi',
    ];

    $item['name'] = get_the_title($post_id) ?: $item['name'];
    $item['url']  = get_permalink($post_id);
    $item['hero_img']  = $thumb ?: fds_img($droneImgKey, $droneImgFallback);
    $item['specs_img'] = $specs_img_db ?: ($thumb ?: fds_img($droneImgKey, "https://picsum.photos/seed/{$slug}-specs/1400/1000"));

    $c_badge = get_post_meta($post_id, 'drone_badge', true);
    if ($c_badge) $item['badge'] = $c_badge;
    
    $c_tagline = get_post_meta($post_id, 'drone_tagline', true);
    if ($c_tagline) $item['tagline'] = $c_tagline;

    $c_desc = get_post_meta($post_id, 'drone_desc', true);
    if ($c_desc) $item['desc'] = $c_desc;

    // Ambil Specs
    $c_specs_raw = get_post_meta($post_id, 'drone_specs_raw', true);
    if ($c_specs_raw) {
      $parsed_specs = [];
      $lines = explode("\n", $c_specs_raw);
      foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        $parts = explode(':', $line, 2);
        if (count($parts) === 2) {
          $parsed_specs[] = [trim($parts[0]), trim($parts[1])];
        }
      }
      if (!empty($parsed_specs)) {
        $item['specs'] = $parsed_specs;
      }
    }

    if (empty($item['specs'])) {
      $spec_fields = [
        'Kapasitas Tangki / Payload' => get_post_meta($post_id, 'drone_spec_kapasitas', true) ?: get_post_meta($post_id, 'drone_kapasitas', true),
        'Durasi Terbang'             => get_post_meta($post_id, 'drone_spec_durasi', true),
        'Sistem Daya (Baterai)'      => get_post_meta($post_id, 'drone_spec_baterai', true) ?: get_post_meta($post_id, 'drone_baterai', true),
        'Produktivitas / Jangkauan'  => get_post_meta($post_id, 'drone_spec_produktivitas', true) ?: get_post_meta($post_id, 'drone_cakupan', true),
        'Kecepatan Jelajah'          => get_post_meta($post_id, 'drone_spec_kecepatan', true),
        'Ketahanan Lingkungan'       => get_post_meta($post_id, 'drone_spec_ketahanan', true) ?: 'IP65',
        'Sistem Otonomi & Navigasi'  => get_post_meta($post_id, 'drone_spec_otonomi', true),
        'Ground Control Station'     => get_post_meta($post_id, 'drone_spec_gcs', true) ?: 'FDS STATION (Bahasa Indonesia)',
        'Sertifikasi & Standar'      => get_post_meta($post_id, 'drone_spec_sertifikasi', true) ?: 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015',
      ];
      $custom_specs = [];
      foreach ($spec_fields as $lbl => $val) {
        if ($val) $custom_specs[] = [$lbl, $val];
      }
      if (!empty($custom_specs)) {
        $item['specs'] = $custom_specs;
      }
    }

    $c_for = get_post_meta($post_id, 'drone_for', true);
    if ($c_for) {
      $parsed_for = array_filter(array_map('trim', explode("\n", $c_for)));
      if (!empty($parsed_for)) {
        $item['for'] = $parsed_for;
      }
    }

    if (empty($item['for'])) {
      $uc_list = [];
      for ($i = 1; $i <= 4; $i++) {
        $t = get_post_meta($post_id, "drone_uc{$i}_t", true);
        $d = get_post_meta($post_id, "drone_uc{$i}_d", true);
        if ($t) $uc_list[] = $d ? "$t — $d" : $t;
      }
      if (!empty($uc_list)) {
        $item['for'] = $uc_list;
      }
    }

    $s1_n = get_post_meta($post_id, 'drone_stat1_num', true);
    if ($s1_n) $item['stat1_num'] = $s1_n;
    $s1_l = get_post_meta($post_id, 'drone_stat1_lbl', true);
    if ($s1_l) $item['stat1_lbl'] = $s1_l;

    $s2_n = get_post_meta($post_id, 'drone_stat2_num', true);
    if ($s2_n) $item['stat2_num'] = $s2_n;
    $s2_l = get_post_meta($post_id, 'drone_stat2_lbl', true);
    if ($s2_l) $item['stat2_lbl'] = $s2_l;

    $s3_n = get_post_meta($post_id, 'drone_stat3_num', true);
    if ($s3_n) $item['stat3_num'] = $s3_n;
    $s3_l = get_post_meta($post_id, 'drone_stat3_lbl', true);
    if ($s3_l) $item['stat3_lbl'] = $s3_l;

    $s4_n = get_post_meta($post_id, 'drone_stat4_num', true);
    if ($s4_n) $item['stat4_num'] = $s4_n;
    $s4_l = get_post_meta($post_id, 'drone_stat4_lbl', true);
    if ($s4_l) $item['stat4_lbl'] = $s4_l;

    $catalog[$slug] = $item;
  }

  // 3. PARAMETER URL
  $req_d1 = isset($_GET['d1']) && isset($catalog[$_GET['d1']]) ? $_GET['d1'] : '';
  $req_d2 = isset($_GET['d2']) && isset($catalog[$_GET['d2']]) ? $_GET['d2'] : '';

  // Prevent identical drone comparison
  if (!empty($req_d1) && !empty($req_d2) && $req_d1 === $req_d2) {
    $req_d2 = '';
  }

  $json_catalog = json_encode($catalog);
@endphp

<div id="compare-page" class="w-full bg-[#f5f5f7] font-sans selection:bg-[#0066cc]/20">

  {{-- ── 1. HERO SECTION (100% KONSISTEN DENGAN BERANDA & HALAMAN LAINNYA) ── --}}
  <section id="compare-hero" class="pt-[52px] bg-[#f5f5f7] overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-16 sm:pt-20 pb-8 sm:pb-12 text-center">

      <p class="inline-block text-[13px] font-semibold text-[#0066cc] mb-4 tracking-wide">
        Komparasi Model UAV
      </p>

      <h1 class="text-[44px] sm:text-[58px] lg:text-[72px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.05] max-w-[820px] mx-auto">
        Bandingkan Model Drone.
      </h1>

      <p class="mt-5 text-[18px] sm:text-[20px] text-[#515154] font-normal max-w-[600px] mx-auto leading-[1.55]">
        Pilih hingga 2 model UAV untuk membandingkan spesifikasi teknis, performa terbang, dan kecocokan operasional lapangan secara berdampingan.
      </p>

    </div>
  </section>

  {{-- ── 2. COMPACT STICKY BAR (MUNCUL OTOMATIS SAAT SCROLL DI TABEL SPEK) ──── --}}
  <div id="compare-sticky-bar" class="sticky top-[52px] z-40 bg-white/95 backdrop-blur-2xl border-b border-black/[0.08] shadow-[0_4px_24px_rgba(0,0,0,0.03)] py-3 transition-all duration-300 opacity-0 pointer-events-none -translate-y-2">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      <div class="grid grid-cols-2 gap-6 sm:gap-12 lg:gap-16">
        
        {{-- SLOT 1 COMPACT --}}
        <div id="sticky-slot-1" class="flex items-center justify-center gap-3"></div>

        {{-- SLOT 2 COMPACT --}}
        <div id="sticky-slot-2" class="flex items-center justify-center gap-3 border-l border-black/[0.06]"></div>

      </div>
    </div>
  </div>

  {{-- ── 3. VISUAL PRODUCT CARDS / EMPTY (+) PLACEHOLDERS (WHITE) ── --}}
  <section id="product-cards-section" class="bg-white pt-8 pb-16 sm:pb-20 border-b border-black/[0.06]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      <div class="grid grid-cols-2 gap-6 sm:gap-12 lg:gap-16 items-stretch">
        
        {{-- SLOT 1 CONTAINER (Card or Plus Placeholder) --}}
        <div id="slot-1-card-container" class="flex flex-col items-center text-center"></div>

        {{-- SLOT 2 CONTAINER (Card or Plus Placeholder) --}}
        <div id="slot-2-card-container" class="flex flex-col items-center text-center"></div>

      </div>
    </div>
  </section>

  {{-- ── 4. SECTION SPESIFIKASI TEKNIS (WHITE — 100% DATA RESMI) ── --}}
  <section class="bg-white py-20 border-b border-black/[0.06]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      
      {{-- Section Header --}}
      <div class="text-center max-w-2xl mx-auto mb-14">
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-2">Spesifikasi</p>
        <h2 class="text-[32px] sm:text-[44px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-3">
          Direkayasa untuk performa nyata.
        </h2>
        <p class="text-[15px] text-[#515154] leading-relaxed">
          Tabel perbandingan data teknis resmi dari lembar spesifikasi masing-masing unit drone FDS.
        </p>
      </div>

      {{-- Direct Specs Comparison Table --}}
      <div id="specs-compare-table-container" class="divide-y divide-black/[0.06] border-t border-b border-black/[0.06]"></div>

    </div>
  </section>

  {{-- ── 5. SECTION SASARAN PENGGUNA (DARK BG-[#1d1d1f]) ──────── --}}
  <section id="section-usecases" class="bg-[#1d1d1f] py-24 border-b border-white/[0.08]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      
      <div class="text-center max-w-2xl mx-auto mb-14">
        <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-2">Aplikasi &amp; Use-Cases</p>
        <h2 class="text-[32px] sm:text-[44px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-3">
          Untuk Siapa Model Ini Diciptakan.
        </h2>
        <p class="text-[15px] text-white/60 leading-relaxed">
          Perbandingan profil pengguna, jenis lahan, dan skenario misi yang paling optimal.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
        {{-- D1 FOR --}}
        <div id="d1-for-box">
          <h3 id="d1-for-title" class="text-[18px] font-bold text-white mb-4 pb-3 border-b border-white/10 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-[#0066cc]"></span>
            <span>Model 1</span>
          </h3>
          <div id="d1-for-list" class="grid grid-cols-1 gap-3.5"></div>
        </div>

        {{-- D2 FOR --}}
        <div id="d2-for-box">
          <h3 id="d2-for-title" class="text-[18px] font-bold text-white mb-4 pb-3 border-b border-white/10 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-[#0066cc]"></span>
            <span>Model 2</span>
          </h3>
          <div id="d2-for-list" class="grid grid-cols-1 gap-3.5"></div>
        </div>
      </div>

    </div>
  </section>

  {{-- ── 6. SECTION STATS BAR (WHITE) ──────────────────────────── --}}
  <section id="section-stats" class="bg-white py-16 border-b border-black/[0.06]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 divide-y md:divide-y-0 md:divide-x divide-black/[0.06]">
        
        {{-- D1 STATS --}}
        <div class="pt-6 md:pt-0">
          <p id="d1-stats-header" class="text-[12px] font-bold text-[#86868b] uppercase tracking-wider mb-6 text-center">
            Highlights Model 1
          </p>
          <div id="d1-stats-row" class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center"></div>
        </div>

        {{-- D2 STATS --}}
        <div class="pt-8 md:pt-0 md:pl-12">
          <p id="d2-stats-header" class="text-[12px] font-bold text-[#86868b] uppercase tracking-wider mb-6 text-center">
            Highlights Model 2
          </p>
          <div id="d2-stats-row" class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center"></div>
        </div>

      </div>
    </div>
  </section>

  {{-- ── 7. CLOSING CTA (DARK BG-[#1d1d1f]) ────────────────────── --}}
  <section class="bg-[#1d1d1f] py-24 text-center">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      <h2 class="text-[36px] sm:text-[50px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-4">
        Siap menentukan pilihan armada Anda?
      </h2>
      <p class="text-[17px] text-white/60 max-w-[520px] mx-auto mb-8 leading-relaxed">
        Konsultasikan kebutuhan operasional dan uji terbang (demo unit) bersama tim teknis PT Karya Solusi Angkasa (FDS) di Yogyakarta.
      </p>
      <div class="flex flex-wrap gap-4 justify-center">
        <a href="{{ home_url('/#kontak') }}"
           class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[15px] font-semibold px-8 py-4 rounded-full transition-all duration-150 shadow-lg">
          Hubungi Tim Sales FDS
        </a>
        <a href="{{ home_url('/blog') }}"
           class="inline-flex items-center text-white/70 text-[15px] font-medium hover:text-white transition-colors gap-1 px-4 py-4">
          Baca studi kasus operasional &rsaquo;
        </a>
      </div>
    </div>
  </section>

  {{-- ── 8. CUSTOM APPLE-STYLE DRONE PICKER MODAL ── --}}
  <div id="drone-picker-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-md hidden items-center justify-center p-4 sm:p-6 transition-opacity duration-200">
    <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-hidden shadow-2xl border border-black/10 flex flex-col transform transition-all duration-300 scale-95 opacity-0" id="drone-picker-dialog">
      
      {{-- Modal Header --}}
      <div class="p-6 pb-4 border-b border-black/[0.08] flex items-center justify-between bg-[#fbfbfd]">
        <div>
          <p class="text-[12px] font-bold text-[#0066cc] uppercase tracking-wider mb-0.5">Katalog Produk</p>
          <h3 class="text-[20px] sm:text-[24px] font-bold text-[#1d1d1f] tracking-tight">
            Pilih Model Drone (<span id="modal-slot-title">Sisi Kiri</span>)
          </h3>
        </div>
        <button type="button" onclick="closePickerModal()" class="w-9 h-9 rounded-full bg-black/5 hover:bg-black/10 flex items-center justify-center text-[#515154] hover:text-[#1d1d1f] transition-colors" aria-label="Tutup">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      {{-- Modal Filter Tabs --}}
      <div class="px-6 py-3 border-b border-black/[0.06] bg-white flex flex-wrap gap-1.5">
        <button type="button" onclick="filterModalCategory('all')" class="modal-cat-tab active px-3 py-1 rounded-full text-[12px] font-semibold bg-[#1d1d1f] text-white">Semua</button>
        <button type="button" onclick="filterModalCategory('agrikultur')" class="modal-cat-tab px-3 py-1 rounded-full text-[12px] font-semibold bg-[#f5f5f7] text-[#515154] hover:text-[#1d1d1f]">Agrikultur (FERTO)</button>
        <button type="button" onclick="filterModalCategory('pemetaan-gis')" class="modal-cat-tab px-3 py-1 rounded-full text-[12px] font-semibold bg-[#f5f5f7] text-[#515154] hover:text-[#1d1d1f]">Pemetaan (DELTAV)</button>
        <button type="button" onclick="filterModalCategory('cargo-logistik')" class="modal-cat-tab px-3 py-1 rounded-full text-[12px] font-semibold bg-[#f5f5f7] text-[#515154] hover:text-[#1d1d1f]">Kargo (DELFRO)</button>
        <button type="button" onclick="filterModalCategory('reboisasi-konservasi')" class="modal-cat-tab px-3 py-1 rounded-full text-[12px] font-semibold bg-[#f5f5f7] text-[#515154] hover:text-[#1d1d1f]">Reboisasi (REBO)</button>
      </div>

      {{-- Modal Body: Drone Cards Grid --}}
      <div id="modal-drones-grid" class="p-6 overflow-y-auto max-h-[60vh] grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($catalog as $slugKey => $dItem)
        <div class="modal-drone-card group border border-black/[0.08] hover:border-[#0066cc] rounded-2xl p-4 bg-white hover:bg-[#fbfbfd] transition-all cursor-pointer flex items-center gap-4 relative"
             data-slug="{{ $slugKey }}"
             data-cat="{{ sanitize_title($dItem['kategori']) }}"
             onclick="selectDroneForActiveSlot('{{ $slugKey }}')">
          <div class="w-20 h-20 bg-[#f5f5f7] rounded-xl flex items-center justify-center p-2 flex-shrink-0">
            <img src="{{ $dItem['specs_img'] ?: $dItem['hero_img'] }}" alt="{{ $dItem['name'] }}" class="max-w-full max-h-full object-contain">
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-1.5 mb-1">
              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#0066cc]/10 text-[#0066cc]">
                {{ $dItem['badge'] }}
              </span>
              <span class="already-selected-badge hidden text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-700"></span>
            </div>
            <h4 class="text-[16px] font-bold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors leading-tight mb-1 truncate">
              {!! esc_html(wp_specialchars_decode($dItem['name'], ENT_QUOTES)) !!}
            </h4>
            <p class="text-[12px] text-[#86868b] truncate">
              {{ $dItem['kategori'] }}
            </p>
          </div>
          <div class="w-8 h-8 rounded-full bg-[#f5f5f7] group-hover:bg-[#0066cc] group-hover:text-white flex items-center justify-center text-[#86868b] transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
          </div>
        </div>
        @endforeach
      </div>

    </div>
  </div>

</div>

{{-- ── 9. JAVASCRIPT COMPARISON ENGINE WITH CUSTOM APPLE MODAL ── --}}
<script>
(function() {
  const CATALOG = {!! $json_catalog !!};
  let activePickingSlot = 1;
  let d1Key = "{{ $req_d1 }}";
  let d2Key = "{{ $req_d2 }}";

  window.openPickerModal = function(slot) {
    activePickingSlot = slot;
    const titleEl = document.getElementById('modal-slot-title');
    if (titleEl) titleEl.textContent = `Sisi ${slot === 1 ? 'Kiri' : 'Kanan'}`;

    const otherSlug = (slot === 1) ? d2Key : d1Key;

    // Update modal cards: disable/mark the drone selected in the other slot
    document.querySelectorAll('.modal-drone-card').forEach(card => {
      const cardSlug = card.getAttribute('data-slug');
      const alreadyBadge = card.querySelector('.already-selected-badge');
      
      if (otherSlug && cardSlug === otherSlug) {
        card.classList.add('opacity-40', 'pointer-events-none', 'bg-[#f5f5f7]');
        card.classList.remove('hover:border-[#0066cc]', 'cursor-pointer');
        if (alreadyBadge) {
          alreadyBadge.textContent = `Sedang Dipilih (${slot === 1 ? 'Sisi Kanan' : 'Sisi Kiri'})`;
          alreadyBadge.classList.remove('hidden');
        }
      } else {
        card.classList.remove('opacity-40', 'pointer-events-none', 'bg-[#f5f5f7]');
        card.classList.add('hover:border-[#0066cc]', 'cursor-pointer');
        if (alreadyBadge) {
          alreadyBadge.classList.add('hidden');
        }
      }
    });

    const modal = document.getElementById('drone-picker-modal');
    const dialog = document.getElementById('drone-picker-dialog');
    if (modal && dialog) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      setTimeout(() => {
        dialog.classList.remove('scale-95', 'opacity-0');
        dialog.classList.add('scale-100', 'opacity-100');
      }, 10);
    }
  };

  window.closePickerModal = function() {
    const modal = document.getElementById('drone-picker-modal');
    const dialog = document.getElementById('drone-picker-dialog');
    if (modal && dialog) {
      dialog.classList.remove('scale-100', 'opacity-100');
      dialog.classList.add('scale-95', 'opacity-0');
      setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }, 200);
    }
  };

  window.filterModalCategory = function(catSlug) {
    document.querySelectorAll('.modal-cat-tab').forEach(b => {
      b.classList.remove('bg-[#1d1d1f]', 'text-white');
      b.classList.add('bg-[#f5f5f7]', 'text-[#515154]');
    });
    event.currentTarget.classList.remove('bg-[#f5f5f7]', 'text-[#515154]');
    event.currentTarget.classList.add('bg-[#1d1d1f]', 'text-white');

    document.querySelectorAll('.modal-drone-card').forEach(card => {
      const cardCat = card.getAttribute('data-cat') || '';
      if (catSlug === 'all' || cardCat.includes(catSlug) || catSlug.includes(cardCat)) {
        card.classList.remove('hidden');
      } else {
        card.classList.add('hidden');
      }
    });
  };

  window.selectDroneForActiveSlot = function(slug) {
    if (!CATALOG[slug]) return;

    if (activePickingSlot === 1 && slug === d2Key) {
      alert('Model ini sudah dipilih pada sisi kanan. Silakan pilih model drone yang berbeda.');
      return;
    }
    if (activePickingSlot === 2 && slug === d1Key) {
      alert('Model ini sudah dipilih pada sisi kiri. Silakan pilih model drone yang berbeda.');
      return;
    }

    if (activePickingSlot === 1) {
      d1Key = slug;
    } else {
      d2Key = slug;
    }

    const url = new URL(window.location.href);
    if (d1Key) url.searchParams.set('d1', d1Key); else url.searchParams.delete('d1');
    if (d2Key) url.searchParams.set('d2', d2Key); else url.searchParams.delete('d2');
    window.history.replaceState({}, '', url);

    closePickerModal();
    renderAll();
  };

  window.clearSlot = function(slot) {
    if (slot === 1) d1Key = '';
    if (slot === 2) d2Key = '';

    const url = new URL(window.location.href);
    if (d1Key) url.searchParams.set('d1', d1Key); else url.searchParams.delete('d1');
    if (d2Key) url.searchParams.set('d2', d2Key); else url.searchParams.delete('d2');
    window.history.replaceState({}, '', url);

    renderAll();
  };

  function renderAll() {
    const d1 = CATALOG[d1Key] || null;
    const d2 = CATALOG[d2Key] || null;

    // 1. UPDATE COMPACT STICKY BAR
    renderStickyBar(d1, d2);

    // 2. RENDER MAIN PRODUCT CARDS / PLUS PLACEHOLDERS
    renderSlotCard('slot-1-card-container', 1, d1);
    renderSlotCard('slot-2-card-container', 2, d2);

    // 3. RENDER SPECS TABLE
    renderSpecsTable(d1, d2);

    // 4. RENDER USE-CASES
    renderUseCases(d1, d2);

    // 5. RENDER STATS BARS
    renderStatsQuad(d1, d2);
  }

  function renderStickyBar(d1, d2) {
    const s1 = document.getElementById('sticky-slot-1');
    const s2 = document.getElementById('sticky-slot-2');

    if (s1) {
      if (d1) {
        s1.innerHTML = `
          <img src="${escapeHtml(d1.specs_img || d1.hero_img || '')}" alt="${escapeHtml(d1.name)}" class="w-8 h-8 object-contain">
          <span class="text-[14px] font-bold text-[#1d1d1f] truncate">${escapeHtml(d1.name)}</span>
          <button type="button" onclick="openPickerModal(1)" class="text-[12px] font-semibold text-[#0066cc] hover:underline">Ganti</button>
        `;
      } else {
        s1.innerHTML = `
          <button type="button" onclick="openPickerModal(1)" class="text-[13px] font-semibold text-[#0066cc] hover:underline flex items-center gap-1">
            <span>+ Pilih Model Pertama</span>
          </button>
        `;
      }
    }

    if (s2) {
      if (d2) {
        s2.innerHTML = `
          <img src="${escapeHtml(d2.specs_img || d2.hero_img || '')}" alt="${escapeHtml(d2.name)}" class="w-8 h-8 object-contain">
          <span class="text-[14px] font-bold text-[#1d1d1f] truncate">${escapeHtml(d2.name)}</span>
          <button type="button" onclick="openPickerModal(2)" class="text-[12px] font-semibold text-[#0066cc] hover:underline">Ganti</button>
        `;
      } else {
        s2.innerHTML = `
          <button type="button" onclick="openPickerModal(2)" class="text-[13px] font-semibold text-[#0066cc] hover:underline flex items-center gap-1">
            <span>+ Pilih Model Kedua</span>
          </button>
        `;
      }
    }
  }

  function renderSlotCard(containerId, slotNum, drone) {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (!drone) {
      // APPLE-STYLE PLUS PLACEHOLDER
      container.innerHTML = `
        <button type="button" onclick="openPickerModal(${slotNum})"
                class="w-full min-h-[380px] rounded-3xl border-2 border-dashed border-black/15 hover:border-[#0066cc] bg-[#f5f5f7]/60 hover:bg-white transition-all duration-200 flex flex-col items-center justify-center p-8 text-center group cursor-pointer shadow-sm">
          <div class="w-16 h-16 rounded-full bg-white group-hover:bg-[#0066cc] text-[#1d1d1f] group-hover:text-white border border-black/10 group-hover:border-[#0066cc] flex items-center justify-center transition-all duration-200 shadow-sm mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
          </div>
          <span class="text-[18px] font-bold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors mb-1.5">
            Pilih Model ${slotNum === 1 ? 'Pertama' : 'Kedua'}
          </span>
          <span class="text-[13px] text-[#86868b] max-w-xs leading-relaxed">
            Klik di sini untuk memilih produk drone FDS yang ingin Anda bandingkan.
          </span>
        </button>
      `;
    } else {
      // POPULATED APPLE PRODUCT CARD (SEAMLESS INTEGRATED PICKER)
      container.innerHTML = `
        <div class="w-full flex flex-col items-center text-center">
          <div class="w-full h-52 sm:h-72 flex items-center justify-center mb-6 relative">
            <img src="${escapeHtml(drone.specs_img || drone.hero_img || '')}" 
                 alt="${escapeHtml(drone.name)}" 
                 class="max-w-full max-h-full object-contain select-none drop-shadow-xl transition-all duration-300">
          </div>
          
          <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-1.5">
            ${escapeHtml(drone.badge || '')}
          </p>

          <h2 class="text-[32px] sm:text-[44px] font-bold tracking-[-0.03em] text-[#1d1d1f] mb-1 leading-tight">
            ${escapeHtml(drone.name)}
          </h2>

          <div class="mb-4 flex items-center gap-3">
            <button type="button" onclick="openPickerModal(${slotNum})" class="inline-flex items-center gap-1 text-[13px] font-semibold text-[#0066cc] hover:underline">
              <span>Ganti model</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <span class="text-black/20">|</span>
            <button type="button" onclick="clearSlot(${slotNum})" class="text-[13px] text-[#86868b] hover:text-red-600 transition-colors">
              Hapus
            </button>
          </div>

          <p class="text-[14px] sm:text-[15px] text-[#515154] max-w-md leading-relaxed mb-6 min-h-[44px]">
            ${escapeHtml(drone.tagline || '')}
          </p>

          <div class="flex flex-wrap gap-2.5 justify-center">
            <a href="{{ home_url('/#kontak') }}" class="inline-flex items-center bg-[#0066cc] hover:bg-[#0055b0] text-white text-[13px] sm:text-[14px] font-semibold px-5 py-2.5 rounded-full transition-all">
              Minta Penawaran
            </a>
            <a href="${escapeHtml(drone.url || '#')}" class="inline-flex items-center bg-[#f5f5f7] hover:bg-[#ebebed] text-[#1d1d1f] text-[13px] sm:text-[14px] font-semibold px-4 py-2.5 rounded-full transition-all">
              Detail Produk
            </a>
          </div>
        </div>
      `;
    }
  }

  function renderSpecsTable(d1, d2) {
    const container = document.getElementById('specs-compare-table-container');
    if (!container) return;

    if (!d1 && !d2) {
      container.innerHTML = `
        <div class="py-16 text-center text-[#86868b]">
          <div class="w-12 h-12 rounded-full bg-[#f5f5f7] flex items-center justify-center mx-auto mb-3 text-[#86868b]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          </div>
          <p class="text-[16px] font-semibold text-[#1d1d1f] mb-1">Belum Ada Model yang Dipilih</p>
          <p class="text-[13px]">Silakan pilih model drone pada slot di atas untuk menampilkan lembar perbandingan spesifikasi.</p>
        </div>
      `;
      return;
    }

    const specsMap1 = {};
    if (d1 && d1.specs) {
      d1.specs.forEach(item => {
        if (Array.isArray(item) && item.length === 2) specsMap1[item[0].trim()] = item[1];
      });
    }

    const specsMap2 = {};
    if (d2 && d2.specs) {
      d2.specs.forEach(item => {
        if (Array.isArray(item) && item.length === 2) specsMap2[item[0].trim()] = item[1];
      });
    }

    const standardKeys = [
      'Kapasitas Tangki',
      'Kapasitas Tangki / Payload',
      'Kapasitas Payload',
      'Bentang Sayap (Wingspan)',
      'Konfigurasi Motor',
      'Berat Lepas Landas (MTOW)',
      'Durasi Terbang',
      'Waktu Terbang',
      'Sistem Daya (Baterai)',
      'Produktivitas Semprot',
      'Produktivitas / Jangkauan',
      'Kecepatan Jelajah',
      'Kecepatan Jelajah / Maks',
      'Jangkauan Misi (Range)',
      'Sensor Kompatibel',
      'Mode Penerbangan',
      'Mode Misi',
      'Sistem Otonomi & Navigasi',
      'Sistem Kendali & Misi',
      'Ground Control Station',
      'Ground Control Software',
      'Software Pengendali',
      'Sertifikasi & Standar',
      'Material Rangka',
      'Rangka & Proteksi',
      'Ukuran Propeller',
      'Sistem Dispenser',
      'Kolaborasi Riset',
      'Aplikasi Utama'
    ];

    const allKeysSet = new Set();
    standardKeys.forEach(k => {
      if (specsMap1[k] !== undefined || specsMap2[k] !== undefined) allKeysSet.add(k);
    });
    Object.keys(specsMap1).forEach(k => allKeysSet.add(k));
    Object.keys(specsMap2).forEach(k => allKeysSet.add(k));

    let html = '';
    allKeysSet.forEach(specTitle => {
      const val1 = d1 ? (specsMap1[specTitle] || '—') : `<button type="button" onclick="openPickerModal(1)" class="text-[#0066cc] text-[13px] font-semibold hover:underline">+ Pilih Drone</button>`;
      const val2 = d2 ? (specsMap2[specTitle] || '—') : `<button type="button" onclick="openPickerModal(2)" class="text-[#0066cc] text-[13px] font-semibold hover:underline">+ Pilih Drone</button>`;

      html += `
        <div class="py-6 transition-colors hover:bg-black/[0.01]">
          <div class="text-center mb-3">
            <span class="text-[12px] font-semibold text-[#515154] tracking-wide bg-[#f5f5f7] px-3.5 py-1 rounded-full">
              ${escapeHtml(specTitle)}
            </span>
          </div>
          <div class="grid grid-cols-2 gap-6 sm:gap-12 lg:gap-16 items-center">
            <div class="text-center text-[15px] sm:text-[16px] font-medium text-[#1d1d1f] leading-relaxed px-2">
              ${val1}
            </div>
            <div class="text-center text-[15px] sm:text-[16px] font-medium text-[#1d1d1f] leading-relaxed px-2 border-l border-black/[0.06]">
              ${val2}
            </div>
          </div>
        </div>
      `;
    });

    container.innerHTML = html;
  }

  function renderUseCases(d1, d2) {
    const d1Title = document.getElementById('d1-for-title');
    const d1List = document.getElementById('d1-for-list');
    if (d1) {
      if (d1Title) d1Title.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-[#0066cc]"></span> <span>${escapeHtml(d1.name)}</span>`;
      if (d1List) {
        d1List.innerHTML = (d1.for || []).map(uc => `
          <div class="bg-white/[0.06] border border-white/[0.08] rounded-2xl p-5 flex items-start gap-3">
            <div class="w-5 h-5 bg-[#0066cc]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg class="w-3 h-3 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-[14px] font-medium text-white/80 leading-snug">${escapeHtml(uc)}</p>
          </div>
        `).join('') || '<p class="text-[14px] text-white/50">Lembar spesifikasi use-case lengkap tersedia di rincian produk.</p>';
      }
    } else {
      if (d1Title) d1Title.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-white/20"></span> <span>Model Pertama Belum Dipilih</span>`;
      if (d1List) d1List.innerHTML = `<div class="bg-white/[0.03] border border-dashed border-white/10 rounded-2xl p-6 text-center text-[13px] text-white/40"><button type="button" onclick="openPickerModal(1)" class="text-[#6e9fd4] hover:underline font-semibold">+ Pilih Drone Pertama</button></div>`;
    }

    const d2Title = document.getElementById('d2-for-title');
    const d2List = document.getElementById('d2-for-list');
    if (d2) {
      if (d2Title) d2Title.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-[#0066cc]"></span> <span>${escapeHtml(d2.name)}</span>`;
      if (d2List) {
        d2List.innerHTML = (d2.for || []).map(uc => `
          <div class="bg-white/[0.06] border border-white/[0.08] rounded-2xl p-5 flex items-start gap-3">
            <div class="w-5 h-5 bg-[#0066cc]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg class="w-3 h-3 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-[14px] font-medium text-white/80 leading-snug">${escapeHtml(uc)}</p>
          </div>
        `).join('') || '<p class="text-[14px] text-white/50">Lembar spesifikasi use-case lengkap tersedia di rincian produk.</p>';
      }
    } else {
      if (d2Title) d2Title.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-white/20"></span> <span>Model Kedua Belum Dipilih</span>`;
      if (d2List) d2List.innerHTML = `<div class="bg-white/[0.03] border border-dashed border-white/10 rounded-2xl p-6 text-center text-[13px] text-white/40"><button type="button" onclick="openPickerModal(2)" class="text-[#6e9fd4] hover:underline font-semibold">+ Pilih Drone Kedua</button></div>`;
    }
  }

  function renderStatsQuad(d1, d2) {
    const d1Header = document.getElementById('d1-stats-header');
    const d1Row = document.getElementById('d1-stats-row');
    if (d1) {
      if (d1Header) d1Header.textContent = `Highlights: ${d1.name}`;
      if (d1Row) {
        d1Row.innerHTML = `
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d1.stat1_num || 'SNI')}</p>
            <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mt-1">${escapeHtml(d1.stat1_lbl || 'SNI 9199:2023')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d1.stat2_num || '60,74%')}</p>
            <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mt-1">${escapeHtml(d1.stat2_lbl || 'TKDN + BMP')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d1.stat3_num || '100%')}</p>
            <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mt-1">${escapeHtml(d1.stat3_lbl || 'FDS STATION')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d1.stat4_num || 'Garansi')}</p>
            <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mt-1">${escapeHtml(d1.stat4_lbl || 'Purna Jual Resmi')}</p>
          </div>
        `;
      }
    } else {
      if (d1Header) d1Header.textContent = 'Highlights Model 1';
      if (d1Row) d1Row.innerHTML = '<div class="col-span-4 text-center text-[13px] text-[#86868b] py-4">Belum ada model dipilih</div>';
    }

    const d2Header = document.getElementById('d2-stats-header');
    const d2Row = document.getElementById('d2-stats-row');
    if (d2) {
      if (d2Header) d2Header.textContent = `Highlights: ${d2.name}`;
      if (d2Row) {
        d2Row.innerHTML = `
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d2.stat1_num || 'SNI')}</p>
            <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mt-1">${escapeHtml(d2.stat1_lbl || 'SNI 9199:2023')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d2.stat2_num || '60,74%')}</p>
            <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mt-1">${escapeHtml(d2.stat2_lbl || 'TKDN + BMP')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d2.stat3_num || '100%')}</p>
            <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mt-1">${escapeHtml(d2.stat3_lbl || 'FDS STATION')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d2.stat4_num || 'Garansi')}</p>
            <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mt-1">${escapeHtml(d2.stat4_lbl || 'Purna Jual Resmi')}</p>
          </div>
        `;
      }
    } else {
      if (d2Header) d2Header.textContent = 'Highlights Model 2';
      if (d2Row) d2Row.innerHTML = '<div class="col-span-4 text-center text-[13px] text-[#86868b] py-4">Belum ada model dipilih</div>';
    }
  }

  function escapeHtml(str) {
    if (!str) return '';
    return str.toString()
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  // Close modal when clicking outside
  document.addEventListener('click', function(e) {
    const modal = document.getElementById('drone-picker-modal');
    if (e.target === modal) {
      closePickerModal();
    }
  });

  // Scroll listener for compact sticky bar
  window.addEventListener('scroll', function() {
    const stickyBar = document.getElementById('compare-sticky-bar');
    if (!stickyBar) return;
    const cardsSection = document.getElementById('product-cards-section');
    if (cardsSection) {
      const rect = cardsSection.getBoundingClientRect();
      if (rect.bottom < 100) {
        stickyBar.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-2');
        stickyBar.classList.add('opacity-100', 'translate-y-0');
      } else {
        stickyBar.classList.add('opacity-0', 'pointer-events-none', '-translate-y-2');
        stickyBar.classList.remove('opacity-100', 'translate-y-0');
      }
    }
  });

  document.addEventListener('DOMContentLoaded', renderAll);
})();
</script>
@endsection
