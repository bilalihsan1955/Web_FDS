@extends('layouts.app')

@section('content')
@php
  $slug    = get_post_field('post_name', get_the_ID());
  $drones  = [
    'ferto-5l' => [
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

  // Aliases for clean URL variations
  $drones['ferto-5']  = &$drones['ferto-5l'];
  $drones['ferto-10'] = &$drones['ferto-10l'];
  $drones['ferto-15'] = &$drones['ferto-15l'];
  $drones['ferto-22'] = &$drones['ferto-22l'];
  $drones['ferto-30'] = &$drones['ferto-30l'];
  $drones['ferto-50'] = &$drones['ferto-50l'];

  $drone = $drones[$slug] ?? null;
  $droneImgKey      = 'drone_' . str_replace('-', '_', str_replace('ferto-', '', $slug));
  $droneImgFallback = 'https://picsum.photos/seed/' . $slug . '-hero/1600/700';

  // --- DYNAMIC CPT RESOLUTION ---
  $cpt_drone = get_posts([
      'post_type'   => 'drone',
      'name'        => $slug,
      'numberposts' => 1,
      'post_status' => 'any'
  ]);
  
  $featuredImg = null;
  if (!empty($cpt_drone)) {
      $cpt_id = $cpt_drone[0]->ID;
      $featuredImg = get_the_post_thumbnail_url($cpt_id, 'full');
      
      if (!$drone) {
          $drone = [
              'name'      => $cpt_drone[0]->post_title,
              'kategori'  => get_post_meta($cpt_id, 'drone_kategori', true) ?: 'Drone',
              'badge'     => get_post_meta($cpt_id, 'drone_badge', true) ?: 'Enterprise',
              'tagline'   => get_post_meta($cpt_id, 'drone_tagline', true) ?: $cpt_drone[0]->post_excerpt,
              'color'     => '#0066cc',
              'specs'     => [],
              'desc'      => $cpt_drone[0]->post_content,
              'for'       => [],
          ];
      } else {
          if (!empty($cpt_drone[0]->post_title)) {
              $drone['name'] = $cpt_drone[0]->post_title;
          }
          if (!empty($cpt_drone[0]->post_content)) {
              $drone['desc'] = $cpt_drone[0]->post_content;
          }
          $terms = get_the_terms($cpt_id, 'kategori_drone');
          if (!empty($terms) && !is_wp_error($terms)) {
              $drone['kategori'] = $terms[0]->name;
          } else {
              $cpt_kategori = get_post_meta($cpt_id, 'drone_kategori', true);
              if ($cpt_kategori) $drone['kategori'] = $cpt_kategori;
          }
          
          $cpt_badge = get_post_meta($cpt_id, 'drone_badge', true);
          if ($cpt_badge) $drone['badge'] = $cpt_badge;
          
          $cpt_tagline = get_post_meta($cpt_id, 'drone_tagline', true);
          if ($cpt_tagline) $drone['tagline'] = $cpt_tagline;

          $cpt_specs_raw = get_post_meta($cpt_id, 'drone_specs_raw', true);
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

          $cpt_for = get_post_meta($cpt_id, 'drone_for', true);
          if ($cpt_for) {
              $parsed_for = array_filter(array_map('trim', explode("\n", $cpt_for)));
              if (!empty($parsed_for)) {
                  $drone['for'] = $parsed_for;
              }
          }

          $s1_n = get_post_meta($cpt_id, 'drone_stat1_num', true);
          if ($s1_n) $drone['stat1_num'] = $s1_n;
          $s1_l = get_post_meta($cpt_id, 'drone_stat1_lbl', true);
          if ($s1_l) $drone['stat1_lbl'] = $s1_l;

          $s2_n = get_post_meta($cpt_id, 'drone_stat2_num', true);
          if ($s2_n) $drone['stat2_num'] = $s2_n;
          $s2_l = get_post_meta($cpt_id, 'drone_stat2_lbl', true);
          if ($s2_l) $drone['stat2_lbl'] = $s2_l;

          $s3_n = get_post_meta($cpt_id, 'drone_stat3_num', true);
          if ($s3_n) $drone['stat3_num'] = $s3_n;
          $s3_l = get_post_meta($cpt_id, 'drone_stat3_lbl', true);
          if ($s3_l) $drone['stat3_lbl'] = $s3_l;

          $s4_n = get_post_meta($cpt_id, 'drone_stat4_num', true);
          if ($s4_n) $drone['stat4_num'] = $s4_n;
          $s4_l = get_post_meta($cpt_id, 'drone_stat4_lbl', true);
          if ($s4_l) $drone['stat4_lbl'] = $s4_l;
      }
  }
@endphp

@if(!$drone)
  <div class="pt-[52px] bg-[#f5f5f7] min-h-[70vh]">
    @php while(have_posts()): the_post(); @endphp
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-20">
        <h1 class="text-[40px] font-semibold text-[#1d1d1f] mb-8">{!! get_the_title() !!}</h1>
        <div class="prose text-[18px] text-[#515154] leading-[1.7]">{!! get_the_content() !!}</div>
      </div>
    @php endwhile; @endphp
  </div>

@else

  <div class="bg-white pt-[52px]">

    {{-- ── HERO — Dark split layout ────────────────────────────── --}}
    <section class="bg-[#1d1d1f] min-h-[90vh] flex flex-col">

      {{-- Top: text block --}}
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-16 w-full">
        {{-- Badge chips --}}
        <div class="flex flex-wrap items-start gap-3 mb-8">
          <span class="inline-flex items-center text-[12px] font-semibold text-white/40 tracking-wide border border-white/[0.12] rounded-full px-3.5 py-1">
            {!! wp_specialchars_decode($drone['kategori']) !!}
          </span>
          <span class="inline-flex items-center text-[12px] font-semibold text-[#6e9fd4] tracking-wide bg-[#0066cc]/15 rounded-full px-3.5 py-1">
            {!! wp_specialchars_decode($drone['badge']) !!}
          </span>
        </div>

        {{-- Nama produk --}}
        <h1 class="text-[72px] sm:text-[100px] lg:text-[128px] font-semibold tracking-[-0.05em] text-white leading-[0.9] mb-8">
          {!! wp_specialchars_decode($drone['name']) !!}
        </h1>

        <p class="text-[18px] sm:text-[20px] text-white/55 max-w-[580px] leading-[1.6] mb-10">
          {!! wp_specialchars_decode($drone['tagline']) !!}
        </p>

        {{-- CTAs --}}
        <div class="flex flex-wrap gap-4 pb-16">
          <a href="{{ home_url('/#kontak') }}"
             class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150">
            Minta Penawaran
          </a>
          <a href="{{ home_url('/#kontak') }}"
             class="inline-flex items-center text-white/60 text-[15px] font-medium hover:text-white transition-colors gap-1">
            Jadwalkan Demo &rsaquo;
          </a>
        </div>
      </div>

      {{-- Hero image — prioritaskan Featured Image, fallback ke Customizer --}}
      <div class="w-full overflow-hidden" style="max-height:620px;">
        @php
          $heroSrc = $featuredImg ?: get_the_post_thumbnail_url(get_the_ID(), 'full');
          $heroSrc = $heroSrc ?: fds_img($droneImgKey, $droneImgFallback);
        @endphp
        <img src="{{ $heroSrc }}"
             alt="{{ $drone['name'] }} &mdash; FDS"
             class="w-full object-cover object-center"
             style="max-height:620px;">
      </div>
    </section>

    {{-- ── SPECS ─────────────────────────────────────────────────── --}}
    <section class="bg-white pt-16 sm:pt-20 pb-16 sm:pb-20 border-t border-black/[0.06] relative z-10 overflow-visible">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 relative overflow-visible">
        @php
          $specs_img = (!empty($cpt_drone) && get_post_meta($cpt_id, 'drone_specs_img', true)) 
                       ? get_post_meta($cpt_id, 'drone_specs_img', true) 
                       : ($featuredImg ?: fds_img($droneImgKey, "https://picsum.photos/seed/{$slug}-specs/1400/1000"));
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-stretch relative overflow-visible">
          
          {{-- Left Column: Relative container where image is positioned absolute overlapping into black section below --}}
          <div class="lg:col-span-6 relative flex flex-col min-h-[320px]">
            <div>
              <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-3">Spesifikasi</p>
              <h2 class="text-[32px] sm:text-[40px] lg:text-[44px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-5">
                Direkayasa untuk<br>performa nyata.
              </h2>
              <p class="text-[15px] sm:text-[16px] text-[#515154] leading-relaxed max-w-xl lg:max-w-[480px] mb-6">
                {!! wp_specialchars_decode($drone['desc']) !!}
              </p>
            </div>

            {{-- Giant Transparent PNG Drone: Anchored cleanly right below description text --}}
            @if($specs_img)
            <div class="relative w-full h-0 select-none">
              <div class="mt-4 lg:mt-0 lg:absolute lg:top-2 xl:top-3 lg:-left-3 xl:-left-6 w-full lg:w-[125%] xl:w-[130%] max-w-[560px] lg:max-w-[660px] pointer-events-none z-20">
                <img src="{{ $specs_img }}" 
                     alt="{{ $drone['name'] }} Spesifikasi" 
                     class="w-full h-auto object-contain object-left select-none drop-shadow-none lg:drop-shadow-[0_20px_35px_rgba(255,255,255,0.22)]">
              </div>
            </div>
            @endif
          </div>

          {{-- Right Column: Specifications Table (More compact width) --}}
          <div class="lg:col-span-6 lg:pt-[44px] divide-y divide-black/[0.06] relative z-10">
            @foreach($drone['specs'] as [$label, $value])
            <div class="py-4 sm:py-5 first:pt-0 first:pb-5 grid grid-cols-2 gap-4 sm:gap-6 items-baseline">
              <p class="text-[13px] sm:text-[14px] font-medium text-[#86868b] leading-tight">{!! wp_specialchars_decode($label) !!}</p>
              <p class="text-[15px] sm:text-[16px] font-semibold text-[#1d1d1f] leading-tight">{!! wp_specialchars_decode($value) !!}</p>
            </div>
            @endforeach
          </div>

        </div>
      </div>
    </section>

    {{-- ── FOR WHOM ─────────────────────────────────────────────── --}}
    <section class="bg-[#1d1d1f] pt-24 sm:pt-32 pb-24 sm:pb-32 relative z-0">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <div>
            <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-4">Untuk Siapa</p>
            <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-white leading-[1.1]">
              {{ $drone['name'] }} cocok untuk Anda.
            </h2>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($drone['for'] as $usecase)
            <div class="bg-white/[0.06] border border-white/[0.08] rounded-2xl p-5 flex items-start gap-3">
              <div class="w-5 h-5 bg-[#0066cc]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3 h-3 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
              </div>
              <p class="text-[14px] font-medium text-white/80 leading-snug">{!! $usecase !!}</p>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>

    {{-- ── STATS BAR ────────────────────────────────────────────── --}}
    <section class="bg-white py-16 border-t border-black/[0.06]">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10 text-center">
          <div>
            <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">{{ $drone['stat1_num'] ?? 'SNI' }}</p>
            <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">{{ $drone['stat1_lbl'] ?? 'SNI 9199:2023' }}</p>
          </div>
          <div>
            <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">{{ $drone['stat2_num'] ?? '60,74%' }}</p>
            <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">{{ $drone['stat2_lbl'] ?? 'TKDN + BMP' }}</p>
          </div>
          <div>
            <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">{{ $drone['stat3_num'] ?? '100%' }}</p>
            <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">{{ $drone['stat3_lbl'] ?? 'FDS STATION GCS' }}</p>
          </div>
          <div>
            <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">{{ $drone['stat4_num'] ?? 'Garansi' }}</p>
            <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">{{ $drone['stat4_lbl'] ?? 'Purna Jual Resmi' }}</p>
          </div>
        </div>
      </div>
    </section>

    {{-- ── CTA ─────────────────────────────────────────────────── --}}
    <section class="bg-[#1d1d1f] py-24">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 text-center">
        <h2 class="text-[36px] sm:text-[52px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5">
          Siap mengoperasikan {{ $drone['name'] }}?
        </h2>
        <p class="text-[18px] text-white/60 max-w-[480px] mx-auto mb-8 leading-relaxed">
          Konsultasikan kebutuhan misi Anda dengan tim teknis PT Karya Solusi Angkasa (FDS). Demo unit dan konsultasi teknis tersedia di Yogyakarta.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
          <a href="{{ home_url('/#kontak') }}"
             class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150">
            Hubungi Tim Sales
          </a>
          <a href="{{ home_url('/blog') }}"
             class="inline-flex items-center text-white/70 text-[15px] font-medium hover:text-white transition-colors gap-1">
            Baca studi kasus &rsaquo;
          </a>
        </div>
      </div>
    </section>

  </div>

@endif
@endsection
