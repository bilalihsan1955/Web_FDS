@extends('layouts.app')

@section('content')
@php
  $post_id = get_the_ID();
  $preview_id = $post_id;
  if (is_preview()) {
      $autosave = wp_get_post_autosave($post_id);
      if ($autosave) {
          $preview_id = $autosave->ID;
      }
  }
  $slug = get_post_field('post_name', $post_id);

  $drones  = [
    'ferto-5l' => [
      'name'      => 'FERTO 5',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Kompak & Lincah',
      'tagline'   => 'Drone Pertanian FERTO 5 — Platform UAV Agrikultur modular dengan mobilitas tinggi.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Payload', '5 Liter'],
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
      'name'      => 'FERTO 10',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Terlaris',
      'tagline'   => 'Drone Pertanian FERTO 10 — Pilihan terbaik kelompok tani dengan produktivitas andal.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Payload', '10 Liter'],
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
      'name'      => 'FERTO 15',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Profesional',
      'tagline'   => 'Drone Pertanian FERTO 15 — Kapasitas 17 Liter dengan produktivitas tinggi 8 Ha/jam.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Payload', '17 Liter (15 – 17 Liter)'],
        ['Durasi Terbang', '15 – 25 menit'],
        ['Sistem Daya (Baterai)', '16.000 mAh'],
        ['Produktivitas Semprot', '8 Ha / jam'],
        ['Kecepatan Jelajah', '2 – 6 m/s'],
        ['Sistem Otonomi & Navigasi', 'Otonom & Manual, Terrain Following, Fail-Safe'],
        ['Ground Control Station', 'FDS STATION (Bahasa Indonesia)'],
        ['Sertifikasi & Standar', 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015'],
      ],
      'desc'      => 'FERTO 15 menghadirkan payload 17 Liter dengan efisiensi tinggi, mampu menyelesaikan penyemprotan hingga 8 hektare per jam. Dilengkapi sistem propulsi berdaya tahan 15-25 menit dan radar terrain-following presisi, drone ini sangat cocok untuk operasional komersial menengah ke atas pada komoditas tebu, jagung, dan hortikultura luas.',
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
      'name'      => 'FERTO 22',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Enterprise',
      'tagline'   => 'Drone Pertanian FERTO 22 — Kapasitas enterprise 22L untuk perkebunan skala besar.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Payload', '22 Liter'],
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
      'name'      => 'FERTO 30',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Heavy Duty',
      'tagline'   => 'Drone Pertanian FERTO 30 — Kapasitas muat masif 30L dengan produktivitas 15 Ha/jam.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Payload', '30 Liter'],
        ['Durasi Terbang', '20 – 30 menit'],
        ['Sistem Daya (Baterai)', '28.000 mAh'],
        ['Produktivitas Semprot', '15 Ha / jam'],
        ['Kecepatan Jelajah', '5,24 m/s'],
        ['Sistem Otonomi & Navigasi', 'Semi-to-Fully Autonomous, Terrain Following, Fail-Safe'],
        ['Ground Control Station', 'FDS STATION (Bahasa Indonesia)'],
        ['Sertifikasi & Standar', 'TKDN + BMP hingga 60,74% | SNI 9199:2023 | ISO 9001:2015'],
      ],
      'desc'      => 'FERTO 30 menghadirkan lompatan payload 30 Liter yang dirancang untuk kebutuhan agribisnis skala masif. Dengan sistem baterai high-capacity 28.000 mAh dan daya jangkau penerbangan hingga 30 menit, drone ini mampu menghasilkan produktivitas 15 Ha per jam, memangkas waktu kerja dan biaya operasional secara drastis.',
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
      'name'      => 'FERTO 50',
      'kategori'  => 'Agrikultur',
      'badge'     => 'Ultra Capacity',
      'tagline'   => 'Drone Pertanian FERTO 50 — Kapasitas puncak 50L untuk produktivitas agrikultur tanpa tanding.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Payload', '50 Liter'],
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
      'stat3_num' => '50 Liter', 'stat3_lbl' => 'Payload Maksimum',
      'stat4_num' => 'Garansi', 'stat4_lbl' => 'Purna Jual Resmi',
    ],
    'deltav' => [
      'name'      => 'DELTAV',
      'kategori'  => 'Pemetaan & GIS',
      'badge'     => 'Hybrid VTOL',
      'tagline'   => 'Platform UAV Pemetaan Fixed-Wing VTOL Hybrid — Jangkauan 60 km untuk akuisisi geospasial area luas.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Bentang Sayap (Wingspan)', '2.000 mm'],
        ['Konfigurasi Motor', '4 Rotor VTOL + 1 Rotor Jelajah (Cruise)'],
        ['Berat Lepas Landas (MTOW)', '10 kg (10.000 g)'],
        ['Payload', '1 – 2 kg (Kamera RGB, LiDAR, Multispektral)'],
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
      'name'      => 'MULTIPURPOSE',
      'kategori'  => 'Pemetaan & Inspeksi',
      'badge'     => 'Modular UAV',
      'tagline'   => 'Platform UAV Modular Serbaguna — Integrasi payload termal, optical zoom, & sensor inspeksi.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Payload', '5 kg (Sensor Termal, LiDAR, Optical Zoom)'],
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
      'color'     => '#0066cc',
      'specs'     => [
        ['Payload', '3 – 10 kg (Kotak Logistik)'],
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
      'stat1_num' => '10 kg', 'stat1_lbl' => 'Payload Maksimum',
      'stat2_num' => '15 kg', 'stat2_lbl' => 'MTOW Maksimum',
      'stat3_num' => '18"', 'stat3_lbl' => 'Carbon Propeller',
      'stat4_num' => 'Auto', 'stat4_lbl' => 'Waypoint Route',
    ],
    'rebo' => [
      'name'      => 'REBO',
      'kategori'  => 'Reboisasi & Konservasi',
      'badge'     => 'Heavy-Duty Seedball',
      'tagline'   => 'Platform UAV Reboisasi & Restorasi Hutan — Penyebaran biji seedball presisi tinggi secara otonom.',
      'color'     => '#0066cc',
      'specs'     => [
        ['Payload', '20 kg (Dispenser Seedball)'],
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

  // Aliases for clean URL variations
  $drones['ferto-5']  = &$drones['ferto-5l'];
  $drones['ferto-10'] = &$drones['ferto-10l'];
  $drones['ferto-15'] = &$drones['ferto-15l'];
  $drones['ferto-22'] = &$drones['ferto-22l'];
  $drones['ferto-30'] = &$drones['ferto-30l'];
  $drones['ferto-50'] = &$drones['ferto-50l'];

  $drone = $drones[$slug] ?? null;
  $droneImgKey      = 'drone_' . str_replace('-', '_', str_replace('ferto-', '', $slug));
  $droneImgFallback = 'https://images.unsplash.com/photo-1527011046414-4781f1f94f8c?auto=format&fit=crop&w=1600&q=80';

  // --- DYNAMIC POST META RESOLUTION (FOR BOTH EXISTING & NEW DRONES) ---
  $get_meta = function($key) use ($preview_id, $post_id) {
      $val = get_post_meta($preview_id, $key, true);
      if ($val === '' || $val === false || $val === null) {
          $val = get_post_meta($post_id, $key, true);
      }
      return $val;
  };

  $meta_has = function($key) use ($preview_id, $post_id) {
      return metadata_exists('post', $preview_id, $key) || metadata_exists('post', $post_id, $key);
  };

  $featuredImg = get_the_post_thumbnail_url($preview_id, 'full') ?: get_the_post_thumbnail_url($post_id, 'full');
  
  if (!$drone) {
      $drone = [
          'name'      => get_the_title($preview_id) ?: get_the_title($post_id),
          'kategori'  => $get_meta('drone_kategori') ?: 'Agrikultur',
          'badge'     => $get_meta('drone_badge'),
          'tagline'   => $get_meta('drone_tagline') ?: get_the_excerpt($preview_id),
          'color'     => '#0066cc',
          'specs'     => [],
          'desc'      => $get_meta('drone_desc') ?: get_the_content(null, false, $preview_id),
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
      // Jika metadata ada di database (bahkan jika disengaja dikosongkan), timpa nilai default katalog
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

  // Override / Enrich with DB / Preview meta if present
  $cpt_title = get_the_title($preview_id) ?: get_the_title($post_id);
  if (!empty($cpt_title)) {
      $drone['name'] = $cpt_title;
  }

  $cpt_desc = $get_meta('drone_desc');
  if ($meta_has('drone_desc')) {
      $drone['desc'] = $cpt_desc;
  } elseif (empty($drone['desc'])) {
      $drone['desc'] = get_the_content(null, false, $preview_id) ?: get_the_content(null, false, $post_id);
  }

  $terms = get_the_terms($preview_id, 'kategori_drone') ?: get_the_terms($post_id, 'kategori_drone');
  if (!empty($terms) && !is_wp_error($terms)) {
      $drone['kategori'] = $terms[0]->name;
  } else {
      $cpt_kategori = $get_meta('drone_kategori');
      if ($cpt_kategori) $drone['kategori'] = $cpt_kategori;
  }
  
  $cpt_badge = $get_meta('drone_badge');
  if ($meta_has('drone_badge')) $drone['badge'] = $cpt_badge;
  
  $cpt_tagline = $get_meta('drone_tagline');
  if ($meta_has('drone_tagline')) $drone['tagline'] = $cpt_tagline;

  $cpt_specs_raw = $get_meta('drone_specs_raw');
  if ($cpt_specs_raw) {
      $parsed_specs = [];
      $lines = explode("\n", $cpt_specs_raw);
      foreach ($lines as $line) {
          $line = trim($line);
          if (empty($line)) continue;
          $parts = explode(':', $line, 2);
          if (count($parts) === 2) {
              $parsed_specs[] = [trim($parts[0]), trim($parts[1])];
          }
      }
      if (!empty($parsed_specs)) {
          $drone['specs'] = $parsed_specs;
      }
  }

  // Fallback to structured spec fields if specs empty
  if (empty($drone['specs'])) {
      $spec_fields = [
          'Payload'                    => $get_meta('drone_spec_kapasitas') ?: $get_meta('drone_kapasitas'),
          'Durasi Terbang'             => $get_meta('drone_spec_durasi'),
          'Sistem Daya (Baterai)'      => $get_meta('drone_spec_baterai') ?: $get_meta('drone_baterai'),
          'Produktivitas / Jangkauan'  => $get_meta('drone_spec_produktivitas') ?: $get_meta('drone_cakupan'),
          'Kecepatan Jelajah'          => $get_meta('drone_spec_kecepatan'),
          'Ketahanan Lingkungan'       => $get_meta('drone_spec_ketahanan'),
          'Sistem Otonomi & Navigasi'  => $get_meta('drone_spec_otonomi'),
          'Ground Control Station'     => $get_meta('drone_spec_gcs'),
          'Sertifikasi & Standar'      => $get_meta('drone_spec_sertifikasi'),
      ];
      $custom_specs = [];
      foreach ($spec_fields as $lbl => $val) {
          if ($val) $custom_specs[] = [$lbl, $val];
      }
      if (!empty($custom_specs)) {
          $drone['specs'] = $custom_specs;
      }
  }

  $cpt_for = $get_meta('drone_for');
  if ($cpt_for) {
      $parsed_for = array_filter(array_map('trim', explode("\n", $cpt_for)));
      if (!empty($parsed_for)) {
          $drone['for'] = $parsed_for;
      }
  }

  // Fallback to structured use-case fields if for empty
  if (empty($drone['for'])) {
      $uc_list = [];
      for ($i = 1; $i <= 4; $i++) {
          $t = $get_meta("drone_uc{$i}_t");
          $d = $get_meta("drone_uc{$i}_d");
          if ($t) $uc_list[] = $d ? "$t — $d" : $t;
      }
      if (!empty($uc_list)) {
          $drone['for'] = $uc_list;
      }
  }

  if ($meta_has('drone_stat1_num')) $drone['stat1_num'] = $get_meta('drone_stat1_num');
  if ($meta_has('drone_stat1_lbl')) $drone['stat1_lbl'] = $get_meta('drone_stat1_lbl');
  if ($meta_has('drone_stat2_num')) $drone['stat2_num'] = $get_meta('drone_stat2_num');
  if ($meta_has('drone_stat2_lbl')) $drone['stat2_lbl'] = $get_meta('drone_stat2_lbl');
  if ($meta_has('drone_stat3_num')) $drone['stat3_num'] = $get_meta('drone_stat3_num');
  if ($meta_has('drone_stat3_lbl')) $drone['stat3_lbl'] = $get_meta('drone_stat3_lbl');
  if ($meta_has('drone_stat4_num')) $drone['stat4_num'] = $get_meta('drone_stat4_num');
  if ($meta_has('drone_stat4_lbl')) $drone['stat4_lbl'] = $get_meta('drone_stat4_lbl');

  $fds_deep_decode = function($val) use (&$fds_deep_decode) {
      if (is_array($val)) {
          return array_map($fds_deep_decode, $val);
      }
      if (!is_string($val)) return $val;
      $prev = '';
      while ($prev !== $val) {
          $prev = $val;
          $val = wp_specialchars_decode($val, ENT_QUOTES);
      }
      return $val;
  };
  if ($drone) {
      $drone = $fds_deep_decode($drone);

      // Normalisasi semua label spesifikasi agar selalu menjadi 'Payload'
      if (!empty($drone['specs']) && is_array($drone['specs'])) {
          foreach ($drone['specs'] as &$spItem) {
              if (is_array($spItem) && count($spItem) >= 2) {
                  $spLabel = trim((string)$spItem[0]);
                  if (
                      $spLabel === 'Kapasitas Tangki' || 
                      $spLabel === 'Kapasitas Tangki / Payload' || 
                      $spLabel === 'Kapasitas Payload' || 
                      $spLabel === 'Kapasitas Payload Biji' || 
                      stripos($spLabel, 'kapasitas tangki') === 0 || 
                      stripos($spLabel, 'kapasitas payload') === 0
                  ) {
                      $spItem[0] = 'Payload';
                  }
              }
          }
          unset($spItem);
      }

      // Normalisasi label statistik bawah jika ada yang bertuliskan Kapasitas Tangki / Kapasitas Payload
      for ($sIdx = 1; $sIdx <= 4; $sIdx++) {
          $stKey = "stat{$sIdx}_lbl";
          if (!empty($drone[$stKey])) {
              $stVal = trim((string)$drone[$stKey]);
              if (
                  $stVal === 'Kapasitas Tangki' || 
                  $stVal === 'Kapasitas Tangki / Payload' || 
                  $stVal === 'Kapasitas Payload' || 
                  stripos($stVal, 'kapasitas tangki') === 0 || 
                  stripos($stVal, 'kapasitas payload') === 0
              ) {
                  $drone[$stKey] = 'Payload';
              }
          }
      }
  }
@endphp

@if(!$drone)
  <div class="pt-[52px] bg-[#f5f5f7] min-h-[70vh]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-20">
      <h1 class="text-[40px] font-semibold text-[#1d1d1f] mb-8">{!! get_the_title() !!}</h1>
      <div class="prose text-[18px] text-[#515154] leading-[1.7]">{!! get_the_content() !!}</div>
    </div>
  </div>

@else

  <div class="bg-white pt-[52px]">

    {{-- ── HERO — Dark split layout (ORIGINAL HERO STRUCTURE WITH ENHANCED BACKGROUND VECTOR) ─── --}}
    <section class="relative bg-gradient-to-br from-[#1c1f26] via-[#13151b] to-[#0a0c10] flex flex-col justify-between overflow-hidden">

      {{-- Vector Background: Lekukan Gelombang Organik Fluida dengan Gradasi ke Gelap di Sisi Kanan --}}
      <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <svg class="absolute inset-0 w-full h-full object-cover" viewBox="0 0 1440 700" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
            {{-- Lapisan 1 (Terluar) — Biru di sisi kurva, bergradasi ke gelap di sisi kanan --}}
            <linearGradient id="hero-curve-fill1" x1="0%" y1="20%" x2="100%" y2="80%">
              <stop offset="0%" stop-color="#0071e3" stop-opacity="0.16" />
              <stop offset="45%" stop-color="#004080" stop-opacity="0.08" />
              <stop offset="100%" stop-color="#0a0c10" stop-opacity="0.95" />
            </linearGradient>

            {{-- Lapisan 2 (Sedang-Luar) — Bergradasi ke gelap di sisi kanan --}}
            <linearGradient id="hero-curve-fill2" x1="0%" y1="20%" x2="100%" y2="80%">
              <stop offset="0%" stop-color="#0077ed" stop-opacity="0.25" />
              <stop offset="50%" stop-color="#0052a3" stop-opacity="0.12" />
              <stop offset="100%" stop-color="#0a0c10" stop-opacity="0.85" />
            </linearGradient>

            {{-- Lapisan 3 (Tengah) — Bergradasi ke gelap di sisi kanan --}}
            <linearGradient id="hero-curve-fill3" x1="0%" y1="20%" x2="100%" y2="80%">
              <stop offset="0%" stop-color="#1a85ff" stop-opacity="0.36" />
              <stop offset="55%" stop-color="#0066cc" stop-opacity="0.18" />
              <stop offset="100%" stop-color="#0a0c10" stop-opacity="0.75" />
            </linearGradient>

            {{-- Lapisan 4 (Inti) — Biru Vibrant di awal, bergradasi ke gelap di sisi kanan --}}
            <linearGradient id="hero-curve-fill4" x1="0%" y1="20%" x2="100%" y2="80%">
              <stop offset="0%" stop-color="#2997ff" stop-opacity="0.52" />
              <stop offset="55%" stop-color="#0071e3" stop-opacity="0.26" />
              <stop offset="100%" stop-color="#0a0c10" stop-opacity="0.65" />
            </linearGradient>
          </defs>

          <!-- Lapisan Lekukan Vektor Organik: Digeser Lebih ke Kanan, Kiri Biru Cerah, Kanan Bergradasi ke Gelap -->
          <path d="M380,0 C560,110 1160,160 1060,360 C980,520 900,620 800,700 L1440,700 L1440,0 Z" fill="url(#hero-curve-fill1)" />
          <path d="M600,0 C760,100 1260,150 1180,330 C1100,490 1020,600 940,700 L1440,700 L1440,0 Z" fill="url(#hero-curve-fill2)" />
          <path d="M820,0 C960,90 1340,140 1280,300 C1220,450 1160,570 1080,700 L1440,700 L1440,0 Z" fill="url(#hero-curve-fill3)" />
          <path d="M1040,0 C1160,80 1420,130 1360,270 C1300,410 1240,540 1200,700 L1440,700 L1440,0 Z" fill="url(#hero-curve-fill4)" />
        </svg>
      </div>

      {{-- Top: text block (STRUKTUR ASLI TIDAK DIUBAH) --}}
      <div class="relative z-10 max-w-[1400px] mx-auto px-6 lg:px-12 pt-16 sm:pt-20 w-full">
        {{-- Badge chips --}}
        <div class="flex flex-wrap items-start gap-3 mb-8">
          <span class="inline-flex items-center text-[12px] font-semibold text-white/70 tracking-wide border border-white/[0.15] bg-white/[0.04] rounded-full px-3.5 py-1 backdrop-blur-sm">
            {!! wp_specialchars_decode($drone['kategori'] ?? 'Agrikultur') !!}
          </span>
          @if(!empty($drone['badge']))
          <span class="inline-flex items-center text-[12px] font-semibold text-[#60a5fa] tracking-wide bg-[#0066cc]/20 border border-blue-400/30 rounded-full px-3.5 py-1 backdrop-blur-sm">
            {!! wp_specialchars_decode($drone['badge']) !!}
          </span>
          @endif
        </div>

        {{-- Nama produk --}}
        <h1 class="text-[72px] sm:text-[100px] lg:text-[128px] font-semibold tracking-[-0.05em] text-white leading-[0.9] mb-8 drop-shadow-sm">
          {!! wp_specialchars_decode($drone['name']) !!}
        </h1>

        @if(!empty($drone['tagline']))
        <p class="text-[18px] sm:text-[20px] text-white/75 max-w-[580px] leading-[1.6] mb-10">
          {!! wp_specialchars_decode($drone['tagline']) !!}
        </p>
        @endif

        {{-- CTAs --}}
        <div class="flex flex-wrap items-center gap-4 pb-8 sm:pb-10">
          <a href="{{ home_url('/#kontak') }}"
             class="inline-flex items-center bg-[#0066cc] hover:bg-[#0052a3] active:scale-[0.97] text-white text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150 shadow-md shadow-[#0066cc]/30">
            Minta Penawaran
          </a>
          <a href="{{ home_url('/bandingkan?d1=' . $slug) }}"
             class="inline-flex items-center text-white/80 hover:text-white text-[15px] font-medium transition-colors gap-1.5 px-3 py-3">
            Bandingkan model &rsaquo;
          </a>
        </div>
      </div>

      {{-- Hero image (STRUKTUR ASLI TIDAK DIUBAH) --}}
      <div class="relative z-10 w-full mt-auto overflow-hidden flex items-end justify-center leading-none max-h-[240px] sm:max-h-[260px] lg:max-h-[260px] xl:max-h-[400px] 2xl:max-h-[680px]">
        @php
          $heroSrc = $featuredImg ?: get_the_post_thumbnail_url($post_id, 'full');
          $heroSrc = $heroSrc ?: fds_img($droneImgKey, $droneImgFallback);
        @endphp
        <img src="{{ $heroSrc }}"
             alt="{!! esc_attr(wp_specialchars_decode($drone['name'], ENT_QUOTES)) !!} — Drone Pertanian & Industri PT Karya Solusi Angkasa FDS"
             loading="eager"
             fetchpriority="high"
             class="w-full h-auto object-cover object-center block max-h-[240px] sm:max-h-[260px] lg:max-h-[260px] xl:max-h-[400px] 2xl:max-h-[680px]">
      </div>

    </section>

    {{-- ── SPECS ─────────────────────────────────────────────────── --}}
    @if(!empty($drone['specs']) || !empty($drone['desc']))
    <section class="bg-white pt-16 sm:pt-20 pb-10 sm:pb-12 border-t border-black/[0.06] relative z-10 overflow-visible">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 relative overflow-visible">
        @php
          $specs_img_meta = $get_meta('drone_specs_img');
          $specs_img = $specs_img_meta ?: ($featuredImg ?: fds_img($droneImgKey, "https://images.unsplash.com/photo-1508614589041-895b88991e3e?auto=format&fit=crop&w=1400&q=80"));
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start relative overflow-visible">
          
          {{-- Left Column: Header, Desc, & Big Transparent Product Image (Col 6) --}}
          <div class="lg:col-span-6 flex flex-col relative overflow-visible">
            <div>
              <div class="flex items-center justify-between mb-3">
                <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide">Spesifikasi</p>
                <a href="{{ home_url('/bandingkan?d1=' . $slug) }}" class="text-[13px] font-semibold text-[#0066cc] hover:underline flex items-center gap-1">
                  Bandingkan spesifikasi &rsaquo;
                </a>
              </div>
              <h2 class="text-[32px] sm:text-[40px] lg:text-[44px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-5">
                Direkayasa untuk<br>performa nyata.
              </h2>
              <p class="text-[15px] sm:text-[16px] text-[#515154] leading-relaxed max-w-xl mb-4">
                {!! wp_specialchars_decode($drone['desc'] ?: "Setiap spesifikasi {$drone['name']} divalidasi melalui ratusan jam uji lapangan di berbagai kondisi cuaca dan jenis lahan di Indonesia.") !!}
              </p>
            </div>

            {{-- Big Transparent Specs Drone Image: Ditengah di mobile/tablet, selaras di desktop --}}
            @if($specs_img)
            <div class="mt-4 sm:mt-6 w-full select-none lg:h-0 lg:relative flex justify-center lg:block">
              <div class="relative lg:absolute lg:top-0 lg:left-0 lg:-ml-2 xl:-ml-4 w-full max-w-[540px] lg:max-w-[620px] pointer-events-none z-20 flex justify-center lg:block mx-auto lg:mx-0">
                <img src="{{ $specs_img }}" 
                     alt="Spesifikasi Teknis {!! esc_attr(wp_specialchars_decode($drone['name'], ENT_QUOTES)) !!} — Full Drone Solutions" 
                     loading="lazy"
                     decoding="async"
                     class="w-full h-auto object-contain object-center lg:object-left select-none drop-shadow-[0_20px_45px_rgba(255,255,255,0.35)] lg:drop-shadow-[0_30px_60px_rgba(255,255,255,0.42)]">
              </div>
            </div>
            @endif
          </div>

          {{-- Right Column: Specifications Table (Col 6) --}}
          <div class="lg:col-span-6 divide-y divide-black/[0.06] pt-2 lg:pt-8 relative z-10">
            @if(!empty($drone['specs']))
              @foreach($drone['specs'] as [$label, $value])
              @php
                $lblClean = trim(wp_specialchars_decode((string)$label));
                if (
                  $lblClean === 'Kapasitas Tangki' || 
                  $lblClean === 'Kapasitas Tangki / Payload' || 
                  $lblClean === 'Kapasitas Payload' || 
                  $lblClean === 'Kapasitas Payload Biji' || 
                  stripos($lblClean, 'kapasitas tangki') === 0 || 
                  stripos($lblClean, 'kapasitas payload') === 0
                ) {
                  $lblClean = 'Payload';
                }
              @endphp
              <div class="py-4 sm:py-5 first:pt-0 grid grid-cols-2 gap-4 sm:gap-6 items-baseline">
                <p class="text-[13px] sm:text-[14px] font-medium text-[#86868b] leading-tight">{!! $lblClean !!}</p>
                <p class="text-[15px] sm:text-[16px] font-semibold text-[#1d1d1f] leading-tight">{!! wp_specialchars_decode($value) !!}</p>
              </div>
              @endforeach
            @else
              <div class="py-4 text-[#86868b] text-[14px]">Spesifikasi detail akan segera diperbarui.</div>
            @endif
          </div>

        </div>
      </div>
    </section>
    @endif

    {{-- ── DOWNLOAD BROCHURE SECTION (CLEAN & MINIMALIST) ─────────────── --}}
    @php
      $brochure_enable        = get_option('fds_brochure_enable', '1');
      $brochure_layout        = get_option('fds_brochure_layout', 'horizontal');
      $raw_title              = get_option('fds_brochure_title', 'Brosur Spesifikasi {drone_name}');
      $brochure_title         = str_replace('{drone_name}', wp_specialchars_decode($drone['name']), $raw_title);
      $brochure_desc_initial  = get_option('fds_brochure_desc', 'Masukkan email Anda untuk mengunduh brosur spesifikasi teknis resmi (PDF).');
      $brochure_desc_ready    = get_option('fds_brochure_desc_ready', 'Dokumen spesifikasi teknis resmi (PDF) telah siap untuk diunduh.');
      $brochure_ph            = get_option('fds_brochure_placeholder', 'Masukkan email Anda...');
      $brochure_btn           = get_option('fds_brochure_button_text', 'Download Brosur (PDF)');
      $brochure_file_url      = get_post_meta($post_id, 'drone_brosur_url', true) ?: get_option('fds_brochure_default_pdf', '');
    @endphp

    @if($brochure_enable === '1')
    <section id="unduh-brosur" class="bg-white py-16 sm:py-20 lg:py-24 border-t border-black/[0.08] relative z-10">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
        
        @if($brochure_layout === 'centered')
        {{-- VARIAN 2: CENTERED / RATA TENGAH (PROPORTIONAL & PROMINENT) --}}
        <div class="max-w-3xl mx-auto text-center flex flex-col items-center">
          
          {{-- Heading & Subtitle Centered --}}
          <h3 class="text-[30px] sm:text-[38px] lg:text-[44px] font-bold text-[#1d1d1f] tracking-tight leading-[1.15]">
            {!! esc_html($brochure_title) !!}
          </h3>
          <p class="fds-brochure-desc-text text-[16px] sm:text-[18px] lg:text-[19px] text-[#6e6e73] mt-3 sm:mt-4 leading-relaxed max-w-xl mx-auto transition-all duration-200"
             data-desc-initial="{!! esc_attr(wp_specialchars_decode($brochure_desc_initial)) !!}"
             data-desc-ready="{!! esc_attr(wp_specialchars_decode($brochure_desc_ready)) !!}">
            {!! esc_html(wp_specialchars_decode($brochure_desc_initial)) !!}
          </p>

          {{-- Action Area Centered --}}
          <div class="w-full mt-8 sm:mt-10 flex flex-col items-center justify-center">
            
            {{-- STATE 1: FORM INPUT EMAIL --}}
            <form id="fds-brochure-form" class="w-full max-w-xl flex flex-col sm:flex-row items-center justify-center gap-3.5">
              <input type="hidden" name="action" value="fds_download_brochure">
              <input type="hidden" name="nonce" value="{{ wp_create_nonce('fds_brochure_download_nonce') }}">
              <input type="hidden" name="drone_id" value="{{ $post_id }}">
              <input type="hidden" name="drone_name" value="{{ esc_attr(wp_specialchars_decode($drone['name'])) }}">

              <div class="relative w-full sm:w-[340px]">
                <input type="email" 
                       id="brochure_email" 
                       name="email" 
                       required 
                       placeholder="{{ esc_attr($brochure_ph) }}" 
                       class="w-full h-[54px] sm:h-[58px] px-6 bg-[#f5f5f7] border border-black/[0.1] rounded-full text-[15px] sm:text-[16px] text-[#1d1d1f] placeholder:text-[#86868b] focus:outline-none focus:bg-white focus:border-[#0066cc] focus:ring-2 focus:ring-[#0066cc]/20 transition-all text-center sm:text-left">
              </div>

              <button type="submit" 
                      id="fds-brochure-submit-btn" 
                      class="w-full sm:w-auto h-[54px] sm:h-[58px] px-8 sm:px-10 bg-[#1d1d1f] hover:bg-black active:scale-[0.98] text-white text-[15px] sm:text-[16px] font-semibold rounded-full flex items-center justify-center gap-2.5 transition-all duration-150 cursor-pointer whitespace-nowrap">
                <span>Kirim Email</span>
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
              </button>
            </form>

            {{-- STATE 2: TOMBOL DOWNLOAD AKTIF --}}
            <div id="fds-brochure-download-ready" class="hidden items-center justify-center">
              <a id="fds-brochure-download-link" 
                 href="{{ esc_url($brochure_file_url ?: '#') }}" 
                 target="_blank" 
                 rel="noopener noreferrer"
                 class="inline-flex items-center justify-center gap-3 bg-[#0066cc] hover:bg-[#0055b3] active:scale-[0.98] text-white h-[54px] sm:h-[58px] px-10 sm:px-12 rounded-full font-semibold text-[15px] sm:text-[16px] transition-all duration-150 whitespace-nowrap cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>{!! esc_html(wp_specialchars_decode($brochure_btn)) !!}</span>
              </a>
            </div>

            {{-- Status & Error Feedback Only --}}
            <div id="fds-brochure-msg" class="hidden text-[13px] mt-3 px-2 text-center"></div>
          </div>

        </div>

        @else
        {{-- VARIAN 1: HORIZONTAL / SPLIT (KIRI-KANAN) --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 lg:gap-12">
          
          {{-- Left: Big Heading & Subtitle --}}
          <div class="max-w-2xl">
            <h3 class="text-[28px] sm:text-[36px] lg:text-[40px] font-bold text-[#1d1d1f] tracking-tight leading-[1.18]">
              {!! esc_html($brochure_title) !!}
            </h3>
            <p class="fds-brochure-desc-text text-[16px] sm:text-[18px] text-[#6e6e73] mt-2.5 sm:mt-3 leading-relaxed transition-all duration-200"
               data-desc-initial="{!! esc_attr(wp_specialchars_decode($brochure_desc_initial)) !!}"
               data-desc-ready="{!! esc_attr(wp_specialchars_decode($brochure_desc_ready)) !!}">
              {!! esc_html(wp_specialchars_decode($brochure_desc_initial)) !!}
            </p>
          </div>

          {{-- Right: Big Action Area --}}
          <div class="w-full lg:w-auto">
            
            {{-- STATE 1: FORM INPUT EMAIL (SEBELUM KIRIM EMAIL) --}}
            <form id="fds-brochure-form" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5">
              <input type="hidden" name="action" value="fds_download_brochure">
              <input type="hidden" name="nonce" value="{{ wp_create_nonce('fds_brochure_download_nonce') }}">
              <input type="hidden" name="drone_id" value="{{ $post_id }}">
              <input type="hidden" name="drone_name" value="{{ esc_attr(wp_specialchars_decode($drone['name'])) }}">

              <div class="relative w-full sm:w-[320px] lg:w-[340px]">
                <input type="email" 
                       id="brochure_email" 
                       name="email" 
                       required 
                       placeholder="{{ esc_attr($brochure_ph) }}" 
                       class="w-full h-[54px] sm:h-[58px] px-6 bg-[#f5f5f7] border border-black/[0.1] rounded-full text-[15px] sm:text-[16px] text-[#1d1d1f] placeholder:text-[#86868b] focus:outline-none focus:bg-white focus:border-[#0066cc] focus:ring-2 focus:ring-[#0066cc]/20 transition-all">
              </div>

              <button type="submit" 
                      id="fds-brochure-submit-btn" 
                      class="h-[54px] sm:h-[58px] px-8 sm:px-10 bg-[#1d1d1f] hover:bg-black active:scale-[0.98] text-white text-[15px] sm:text-[16px] font-semibold rounded-full flex items-center justify-center gap-2.5 transition-all duration-150 cursor-pointer whitespace-nowrap">
                <span>Kirim Email</span>
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
              </button>
            </form>

            {{-- STATE 2: TOMBOL DOWNLOAD AKTIF (BERSIH TANPA CLUTTER) --}}
            <div id="fds-brochure-download-ready" class="hidden items-center">
              <a id="fds-brochure-download-link" 
                 href="{{ esc_url($brochure_file_url ?: '#') }}" 
                 target="_blank" 
                 rel="noopener noreferrer"
                 class="inline-flex items-center justify-center gap-3 bg-[#0066cc] hover:bg-[#0055b3] active:scale-[0.98] text-white h-[54px] sm:h-[58px] px-10 sm:px-12 rounded-full font-semibold text-[15px] sm:text-[16px] transition-all duration-150 whitespace-nowrap cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>{!! esc_html(wp_specialchars_decode($brochure_btn)) !!}</span>
              </a>
            </div>

            {{-- Status & Error Feedback Only --}}
            <div id="fds-brochure-msg" class="hidden text-[13px] mt-2.5 px-2 text-center sm:text-left"></div>
          </div>

        </div>
        @endif
      </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('fds-brochure-form');
      const readyBox = document.getElementById('fds-brochure-download-ready');
      const downloadLink = document.getElementById('fds-brochure-download-link');
      const submitBtn = document.getElementById('fds-brochure-submit-btn');
      const msgBox = document.getElementById('fds-brochure-msg');
      const descTexts = document.querySelectorAll('.fds-brochure-desc-text');
      const initialPdf = '{{ esc_js($brochure_file_url) }}';

      if (!form || !readyBox) return;

      function updateDescToReady() {
        descTexts.forEach(el => {
          if (el.dataset.descReady) {
            el.textContent = el.dataset.descReady;
          }
        });
      }

      // 1. CEK STATE CLIENT LOCAL STORAGE (Jika user sudah pernah input email, langsung buka tombol & update deskripsi)
      const isUnlocked = localStorage.getItem('fds_brochure_unlocked') === '1';
      if (isUnlocked) {
        form.classList.add('hidden');
        readyBox.classList.remove('hidden');
        readyBox.classList.add('flex');
        updateDescToReady();
        if (initialPdf && downloadLink) {
          downloadLink.href = initialPdf;
        }
      }

      // 2. FORM SUBMIT HANDLER
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const emailInput = document.getElementById('brochure_email');
        const emailVal = emailInput ? emailInput.value.trim() : '';

        if (!emailVal || (emailInput && !emailInput.validity.valid)) {
          if (msgBox) {
            msgBox.textContent = 'Silakan masukkan alamat email yang valid.';
            msgBox.className = 'text-[13px] mt-2.5 font-medium text-rose-600 block text-center sm:text-left';
            msgBox.classList.remove('hidden');
          }
          return;
        }

        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Memproses...</span>
          `;
        }
        if (msgBox) msgBox.classList.add('hidden');

        const formData = new FormData(form);

        fetch('{{ admin_url('admin-ajax.php') }}', {
          method: 'POST',
          body: formData,
        })
        .then(res => res.json())
        .then(data => {
          // Simpan status unlock ke localStorage user
          localStorage.setItem('fds_brochure_email', emailVal);
          localStorage.setItem('fds_brochure_unlocked', '1');

          if (data && data.success && data.data && data.data.download_url) {
            if (downloadLink) downloadLink.href = data.data.download_url;
          } else if (initialPdf && downloadLink) {
            downloadLink.href = initialPdf;
          }

          // Langsung ganti tampilan ke tombol download biru & update kalimat deskripsi
          form.classList.add('hidden');
          readyBox.classList.remove('hidden');
          readyBox.classList.add('flex');
          updateDescToReady();
        })
        .catch(() => {
          // Fallback jika offline/error: tetap unlock tombol
          localStorage.setItem('fds_brochure_email', emailVal);
          localStorage.setItem('fds_brochure_unlocked', '1');
          form.classList.add('hidden');
          readyBox.classList.remove('hidden');
          readyBox.classList.add('flex');
          updateDescToReady();
        })
        .finally(() => {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
              <span>Kirim Email</span>
              <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
              </svg>
            `;
          }
        });
      });
    });
    </script>
    @endif

    {{-- ── FOR WHOM (SOFTWARE KENDALI ORGANIC WAVE BACKGROUND) ─────── --}}
    @if(!empty($drone['for']))
    <section class="relative bg-gradient-to-br from-[#1c1f26] via-[#12141a] to-[#0a0c10] pt-24 sm:pt-32 pb-24 sm:pb-32 overflow-hidden border-t border-white/[0.06] z-10">
      
      {{-- Vector Background: Pure Organic Wave Fills (Persis Proporsi Card Software Kendali) --}}
      <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <svg class="w-full h-full" viewBox="0 0 1000 400" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
          <defs>
            <linearGradient id="forwhom-wave-fill1" x1="0%" y1="100%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#2563eb" stop-opacity="0.48" />
              <stop offset="60%" stop-color="#1d4ed8" stop-opacity="0.22" />
              <stop offset="100%" stop-color="#0f172a" stop-opacity="0.0" />
            </linearGradient>
            <linearGradient id="forwhom-wave-fill2" x1="0%" y1="100%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.38" />
              <stop offset="70%" stop-color="#2563eb" stop-opacity="0.16" />
              <stop offset="100%" stop-color="#0f172a" stop-opacity="0.0" />
            </linearGradient>
            <linearGradient id="forwhom-wave-fill3" x1="100%" y1="100%" x2="0%" y2="0%">
              <stop offset="0%" stop-color="#60a5fa" stop-opacity="0.42" />
              <stop offset="50%" stop-color="#2563eb" stop-opacity="0.22" />
              <stop offset="100%" stop-color="#0f172a" stop-opacity="0.0" />
            </linearGradient>
          </defs>

          <!-- Pure Fluid Wave Fills: Sedikit di kiri bawah, mengalir elegan naik ke kanan -->
          <path d="M0,340 C280,320 470,220 780,150 C900,120 960,100 1000,80 L1000,400 L0,400 Z" fill="url(#forwhom-wave-fill1)" />
          <path d="M0,380 C360,370 580,280 860,190 C930,160 970,140 1000,120 L1000,400 L0,400 Z" fill="url(#forwhom-wave-fill2)" />
          <path d="M440,400 C590,340 780,300 1000,220 L1000,400 Z" fill="url(#forwhom-wave-fill3)" />
        </svg>
      </div>

      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
          <div>
            <span class="text-[12px] font-semibold text-[#60a5fa] tracking-wide mb-3 block">Untuk Siapa</span>
            <h2 class="text-[36px] sm:text-[46px] lg:text-[48px] font-semibold tracking-[-0.03em] text-white leading-[1.12]">
              {!! esc_html(wp_specialchars_decode($drone['name'], ENT_QUOTES)) !!} cocok untuk Anda.
            </h2>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($drone['for'] as $usecase)
            <div class="bg-white/[0.06] hover:bg-white/[0.1] border border-white/[0.1] hover:border-blue-400/30 rounded-2xl p-5 flex items-start gap-3.5 backdrop-blur-md transition-all duration-200 shadow-sm">
              <div class="w-5 h-5 bg-[#2563eb]/25 text-[#60a5fa] rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 border border-blue-400/30">
                <svg class="w-3 h-3 text-[#60a5fa]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
              </div>
              <p class="text-[14px] font-medium text-white/90 leading-snug">{!! wp_specialchars_decode($usecase, ENT_QUOTES) !!}</p>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>
    @endif

    {{-- ── STATS BAR ────────────────────────────────────────────── --}}
    @php
      $stats_list = [];
      for ($si = 1; $si <= 4; $si++) {
          $sNum = trim((string)($drone["stat{$si}_num"] ?? ''));
          $sLbl = trim((string)($drone["stat{$si}_lbl"] ?? ''));
          if ($sNum !== '' || $sLbl !== '') {
              $stats_list[] = [
                  'num' => $sNum,
                  'lbl' => $sLbl,
              ];
          }
      }
      $total_stats = count($stats_list);
    @endphp

    @if($total_stats > 0)
    <section class="bg-white py-16 border-t border-black/[0.06]">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-2 {{ $total_stats === 1 ? 'md:grid-cols-1 max-w-sm mx-auto' : ($total_stats === 2 ? 'md:grid-cols-2 max-w-2xl mx-auto' : ($total_stats === 3 ? 'md:grid-cols-3 max-w-4xl mx-auto' : 'md:grid-cols-4')) }} gap-10 text-center">
          @foreach($stats_list as $st)
          <div>
            @if(!empty($st['num']))
            <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">{!! esc_html(wp_specialchars_decode($st['num'], ENT_QUOTES)) !!}</p>
            @endif
            @if(!empty($st['lbl']))
            <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">{!! esc_html(wp_specialchars_decode($st['lbl'], ENT_QUOTES)) !!}</p>
            @endif
          </div>
          @endforeach
        </div>
      </div>
    </section>
    @endif

    {{-- ── CTA (EKOSISTEM VECTOR BACKGROUND) ─────────────────────── --}}
    <section class="relative bg-[#0c1018] py-24 sm:py-32 lg:py-36 overflow-hidden border-t border-white/[0.06]">
      
      {{-- Organic Wave Geometry Vector Background (Matching Ekosistem Bento Card) --}}
      <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <svg class="w-full h-full" viewBox="0 0 398 96" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
          <g>
            <rect width="398" height="96" fill="url(#paint0_drone_cta_eko)"/>
            <path d="M-18.2893 -6.32697C-136.964 10.9814 -24.6036 35.4983 -46.9639 64.4209C-70.1751 94.444 4.76287 155.377 4.76287 155.377L406.77 160.338L455.686 19.5816C455.686 19.5816 486.61 10.9501 346.048 50.0886C205.485 89.2271 100.385 -23.6353 -18.2893 -6.32697Z" fill="url(#paint1_drone_cta_eko)"/>
            <path d="M-87.4837 43.4736C-65.1233 14.551 -71.703 -9.63438 27.815 2.49305C127.333 14.6205 180.184 94.5513 338.738 73.6039C402.333 65.2021 433.365 58.4269 447.725 53.3251L453.437 41.8199C453.437 41.8199 469.167 45.7069 447.725 53.3251L389.303 171L-53.1488 161.803C-53.1488 161.803 -110.695 73.4967 -87.4837 43.4736Z" fill="url(#paint2_drone_cta_eko)"/>
            <path d="M52.5537 76.3607C17.9455 66.8521 -56.751 53.9736 -56.751 53.9736L-46.402 162.355L187.493 166.765C187.493 166.765 114.012 93.2462 52.5537 76.3607Z" fill="url(#paint3_drone_cta_eko)"/>
          </g>
          <defs>
            <linearGradient id="paint0_drone_cta_eko" x1="199" y1="0" x2="199" y2="96" gradientUnits="userSpaceOnUse">
              <stop stop-color="#1b2434"/>
              <stop offset="0.915" stop-color="#0c121e"/>
            </linearGradient>
            <linearGradient id="paint1_drone_cta_eko" x1="192.438" y1="-8.14648" x2="192.438" y2="160.338" gradientUnits="userSpaceOnUse">
              <stop stop-color="#2563eb" stop-opacity="0.40"/>
              <stop offset="0.915" stop-color="#1d4ed8" stop-opacity="0.15"/>
            </linearGradient>
            <linearGradient id="paint2_drone_cta_eko" x1="183" y1="-0.710449" x2="183" y2="171" gradientUnits="userSpaceOnUse">
              <stop stop-color="#3b82f6" stop-opacity="0.35"/>
              <stop offset="0.49" stop-color="#1d4ed8" stop-opacity="0.22"/>
              <stop offset="0.9999" stop-color="#080c14"/>
            </linearGradient>
            <linearGradient id="paint3_drone_cta_eko" x1="65.3711" y1="53.9736" x2="65.3711" y2="166.765" gradientUnits="userSpaceOnUse">
              <stop stop-color="#60a5fa" stop-opacity="0.40"/>
              <stop offset="0.49" stop-color="#2563eb" stop-opacity="0.25"/>
              <stop offset="0.9999" stop-color="#060910"/>
            </linearGradient>
          </defs>
        </svg>
      </div>

      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 text-center relative z-10">
        <h2 class="text-[36px] sm:text-[48px] lg:text-[54px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5 drop-shadow-sm">
          Siap mengoperasikan {!! esc_html(wp_specialchars_decode($drone['name'], ENT_QUOTES)) !!}?
        </h2>
        <p class="text-[16px] sm:text-[18px] lg:text-[19px] text-white/80 max-w-[540px] mx-auto mb-9 leading-relaxed">
          Konsultasikan kebutuhan misi Anda dengan tim teknis PT Karya Solusi Angkasa (FDS). Demo unit dan konsultasi teknis tersedia di Yogyakarta.
        </p>
        <div class="flex flex-wrap gap-4 items-center justify-center">
          <a href="{{ home_url('/#kontak') }}"
             class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.98] text-[#0c121e] text-[16px] font-semibold px-8 py-4 rounded-full transition-all duration-150 shadow-lg shadow-black/20">
            Hubungi Tim Sales
          </a>
          <a href="{{ home_url('/blog') }}"
             class="inline-flex items-center text-white/80 hover:text-white text-[16px] font-medium transition-colors gap-1 px-4 py-3">
            Baca studi kasus &rsaquo;
          </a>
        </div>
      </div>
    </section>

  </div>

@endif
@endsection
