@extends('layouts.app')

@section('content')

@php
  $term       = get_queried_object();
  $term_name  = ($term && !empty($term->name)) ? html_entity_decode(wp_specialchars_decode($term->name, ENT_QUOTES), ENT_QUOTES, 'UTF-8') : 'Katalog Drone';
  $term_slug  = ($term && !empty($term->slug)) ? (string) $term->slug : '';
  $term_desc  = ($term && !empty($term->description)) ? html_entity_decode(wp_specialchars_decode($term->description, ENT_QUOTES), ENT_QUOTES, 'UTF-8') : '';

  $decode_fn = function ($val) {
    $v = (string) $val;
    $prev = '';
    $max = 5;
    while ($prev !== $v && $max-- > 0) {
      $prev = $v;
      $v = html_entity_decode(wp_specialchars_decode($v, ENT_QUOTES), ENT_QUOTES, 'UTF-8');
    }
    return trim($v);
  };

  $category_intros = [
    'agrikultur' => [
      'headline' => 'Drone Pertanian Presisi FERTO.',
      'subtitle' => $term_desc ?: 'Lini drone sprayer pupuk cair dan penebar granul padat berstandar TKDN 60,74% & SNI 9199:2023 untuk tanaman pangan dan perkebunan kelapa sawit.',
      'guide_title' => 'Standar Rekayasa Drone Agrikultur FDS untuk Perkebunan & Pangan Nasional',
      'paragraphs' => [
        'Lini drone pertanian FERTO dikembangkan langsung oleh PT Karya Solusi Angkasa (Full Drone Solutions) di Sleman, Yogyakarta untuk menjawab kelangkaan tenaga kerja semprot dan pemborosan agrokimia di lahan sawah maupun perkebunan kelapa sawit, tebu, dan hortikultura.',
        'Setiap unit dari varian FERTO 5 (5 Liter) hingga FERTO 50 (50 Liter) dilengkapi sistem nozzle atomisasi presisi, radar kontur otomatis (Terrain Following), serta perangkat lunak Ground Control Station FDS STATION berbahasa Indonesia yang memudahkan operator Gapoktan, koperasi, dan tim estate enterprise.',
        'Dengan sertifikasi Tingkat Komponen Dalam Negeri (TKDN + BMP) mencapai 60,74%, Standar Nasional Indonesia SNI 9199:2023, serta jaminan ketersediaan suku cadang ready-stock di Indonesia, pengadaan drone agrikultur FDS memenuhi ketentuan e-Katalog LKPP untuk instansi pemerintah, BUMN perkebunan, maupun swasta.',
      ],
    ],
    'pemetaan-gis' => [
      'headline' => 'Drone Pemetaan & Survey LiDAR GIS.',
      'subtitle' => $term_desc ?: 'Wahana Fixed-Wing VTOL DELTAV dan multirotor modular MULTIPURPOSE untuk foto udara ortofoto, topografi tambang, dan inspeksi utilitas.',
      'guide_title' => 'Akuisisi Geospasial Akurasi Sub-Sentimeter & Konsultasi GIS Enterprise',
      'paragraphs' => [
        'Sektor pertambangan, perkebunan HGU, kehutanan karbon, serta perencanaan tata ruang Kawasan Ekonomi Khusus (KEK) menuntut data spasial yang cepat dan akurat. DELTAV VTOL menghadirkan efisiensi terbang jelajah jarak jauh hingga 60 km dan durasi sampai 120 menit untuk memetakan ribuan hektar area tanpa membutuhkan landasan pacu.',
        'Untuk misi inspeksi infrastruktur dan akuisisi sensor khusus, platform MULTIPURPOSE mendukung payload modular kamera termal inframerah FLIR, zoom optik 20x, hingga sensor LiDAR ringan untuk penetrasi kanopi hutan dan inspeksi koridor jaringan listrik tegangan tinggi 150 kV.',
        'Seluruh wahana kompatibel dengan alur kerja pemrosesan ortomosaik, Digital Terrain Model (DTM), Digital Surface Model (DSM), kalkulasi volume stockpile cut & fill, hingga penyusunan WebGIS interaktif oleh tim Konsultan GIS PT Karya Solusi Angkasa.',
      ],
    ],
    'kargo' => [
      'headline' => 'Drone Kargo & Logistik Otonom.',
      'subtitle' => $term_desc ?: 'Solusi pengiriman muatan cepat dan aman untuk kebutuhan logistik medis darurat, suku cadang industri, dan area kepulauan 3T.',
      'guide_title' => 'Distribusi Udara Otonom ke Wilayah Terisolir & Fasilitas Lepas Pantai',
      'paragraphs' => [
        'Keterlambatan pengiriman sampel medis, obat darurat, dan suku cadang krusial akibat kendala geografis kini dapat diatasi dengan drone kargo otonom DELFRO buatan PT Karya Solusi Angkasa. Wahana ini mampu membawa kotak payload 3 hingga 10 kg melintasi medan pegunungan, perkebunan terpencil, dan kepulauan.',
        'Navigasi waypoint otonom yang dikendalikan melalui FDS STATION memungkinkan pemantauan posisi real-time, pendaratan presisi, dan mekanisme pengamanan muatan yang handal di kondisi angin tropis.',
        'Didukung pelatihan pilot bersertifikat DKPPU CASR 107 melalui FDS Academy serta layanan perawatan purna jual lokal di Sleman Yogyakarta, pengoperasian armada logistik udara berjalan legal, aman, dan berkelanjutan.',
      ],
    ],
  ];

  $active_copy = $category_intros[$term_slug] ?? [
    'headline' => 'Drone ' . $term_name . '.',
    'subtitle' => $term_desc ?: 'Katalog resmi pesawat nirawak industrial buatan PT Karya Solusi Angkasa berstandar TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015.',
    'guide_title' => 'Keunggulan Wahana UAV Rekayasa PT Karya Solusi Angkasa',
    'paragraphs' => [
      'Seluruh unit pesawat nirawak dalam kategori ' . $term_name . ' dirancang, dirakit, dan diuji terbang di fasilitas manufaktur Full Drone Solutions di Sleman, Daerah Istimewa Yogyakarta.',
      'Dengan dukungan suku cadang asli ready-stock, software kendali FDS STATION berbahasa Indonesia, dan sertifikasi TKDN resmi Kemenperin RI, solusi FDS siap melayani sektor agribisnis, pertambangan, geospasial, serta instansi BUMN.',
    ],
  ];

  $all_cats = get_terms([
    'taxonomy'   => 'kategori_drone',
    'hide_empty' => true,
    'orderby'    => 'term_id',
    'order'      => 'ASC',
  ]);

  $drones_args = [
    'post_type'      => 'drone',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
  ];
  if ($term && !empty($term->term_id)) {
    $drones_args['tax_query'] = [[
      'taxonomy' => 'kategori_drone',
      'field'    => 'term_id',
      'terms'    => $term->term_id,
    ]];
  }
  $cat_drones = get_posts($drones_args);
  $cat_drones = array_values(array_filter($cat_drones, function ($d) {
    return stripos((string) $d->post_name, 'rebo') === false && stripos((string) $d->post_name, 'agrea') === false;
  }));
@endphp

{{-- 1. HERO KATEGORI DRONE (Brand Teal Palette) --}}
<section class="relative pt-32 sm:pt-40 pb-20 sm:pb-24 bg-gradient-to-br from-[#0f808a] via-[#0a555c] to-[#052b2e] overflow-hidden">
  <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
    <svg class="absolute inset-0 w-full h-full object-cover" viewBox="0 0 1440 500" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="cat-wave1" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#ffffff" stop-opacity="0.12" />
          <stop offset="100%" stop-color="#052b2e" stop-opacity="0.55" />
        </linearGradient>
        <linearGradient id="cat-wave2" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#75e6f0" stop-opacity="0.18" />
          <stop offset="100%" stop-color="#031e20" stop-opacity="0.75" />
        </linearGradient>
      </defs>
      <path d="M0,180 C340,220 720,340 1080,380 C1260,400 1380,390 1440,365 L1440,500 L0,500 Z" fill="url(#cat-wave1)" />
      <path d="M0,310 C320,330 680,410 1040,440 C1240,455 1360,450 1440,435 L1440,500 L0,500 Z" fill="url(#cat-wave2)" />
    </svg>
  </div>

  <div class="relative z-10 max-w-[1400px] mx-auto px-6 lg:px-12">
    <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-[13px] text-white/75 mb-6">
      <a href="{{ home_url('/') }}" class="hover:text-white transition-colors">Beranda</a>
      <span aria-hidden="true">/</span>
      <a href="{{ home_url('/#produk') }}" class="hover:text-white transition-colors">Katalog Drone</a>
      <span aria-hidden="true">/</span>
      <span class="text-white font-medium">{{ $term_name }}</span>
    </nav>

    <h1 class="text-[36px] sm:text-[48px] lg:text-[56px] font-semibold tracking-[-0.03em] text-white leading-[1.08] max-w-[820px]">
      {{ $active_copy['headline'] }}
    </h1>

    <p class="mt-5 text-[17px] sm:text-[19px] text-white/85 leading-relaxed max-w-[740px]">
      {{ $active_copy['subtitle'] }}
    </p>

    <div class="mt-8 flex flex-wrap items-center gap-4">
      <a href="{{ home_url('/bandingkan/') }}"
         class="inline-flex items-center gap-2 bg-white text-[#052b2e] hover:bg-[#f5f5f7] text-[14px] sm:text-[15px] font-semibold px-6 py-3 rounded-full transition-colors shadow-lg shadow-black/15">
        Bandingkan Spesifikasi Drone
      </a>
      <a href="{{ home_url('/kontak/') }}"
         class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white border border-white/25 text-[14px] sm:text-[15px] font-medium px-6 py-3 rounded-full transition-colors">
        Konsultasi Pengadaan &amp; TKDN
      </a>
    </div>
  </div>
</section>

{{-- 2. NAVIGASI KATEGORI & KATALOG UNIT --}}
<section class="bg-[#f5f5f7] py-16 sm:py-24">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    @if(!empty($all_cats) && !is_wp_error($all_cats))
    <div class="flex flex-wrap items-center gap-2.5 mb-12">
      @foreach($all_cats as $cat)
        @php
          if (stripos((string) $cat->slug, 'rebo') !== false) continue;
          $c_link = get_term_link($cat);
          $is_curr = ($term && isset($term->term_id) && (int) $term->term_id === (int) $cat->term_id);
        @endphp
        @if(!is_wp_error($c_link))
          <a href="{{ esc_url($c_link) }}"
             class="px-5 py-2.5 rounded-full text-[13.5px] font-semibold transition-all border {{ $is_curr ? 'bg-[#0f808a] text-white border-[#0f808a]' : 'bg-white text-[#1d1d1f] border-black/10 hover:border-[#0f808a] hover:text-[#0f808a]' }}">
            {{ $decode_fn($cat->name) }}
          </a>
        @endif
      @endforeach
    </div>
    @endif

    @if(!empty($cat_drones))
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($cat_drones as $drone)
          @php
            $d_id      = $drone->ID;
            $d_title   = $decode_fn(get_the_title($d_id));
            $d_url     = get_permalink($d_id);
            $d_slug    = $drone->post_name;
            $d_badge   = $decode_fn(get_post_meta($d_id, 'drone_badge', true) ?: $term_name);
            $d_tagline = $decode_fn(get_post_meta($d_id, 'drone_tagline', true) ?: get_post_meta($d_id, 'drone_desc', true));
            $d_cap     = $decode_fn(get_post_meta($d_id, 'drone_spec_kapasitas', true));
            $d_dur     = $decode_fn(get_post_meta($d_id, 'drone_spec_durasi', true));
            $d_prod    = $decode_fn(get_post_meta($d_id, 'drone_spec_produktivitas', true));
            $thumb_id  = get_post_thumbnail_id($d_id);
            $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '';
            $thumb_alt = $thumb_id ? trim((string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true)) : '';
            if ($thumb_alt === '') {
              $thumb_alt = $d_title . ' — Full Drone Solutions';
            }
            $spec_line = implode(' · ', array_filter([$d_cap, $d_dur, $d_prod]));
          @endphp
          <article class="bg-white rounded-3xl overflow-hidden border border-black/[0.06] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
            <div>
              <a href="{{ esc_url($d_url) }}" class="block aspect-[16/10] bg-[#f5f5f7] overflow-hidden">
                @if($thumb_url)
                  <img src="{{ esc_url($thumb_url) }}"
                       alt="{{ esc_attr($thumb_alt) }}"
                       width="800"
                       height="500"
                       loading="lazy"
                       decoding="async"
                       class="w-full h-full object-contain p-6 group-hover:scale-105 transition-transform duration-500">
                @else
                  <div class="w-full h-full flex items-center justify-center text-[#0f808a]/40 font-semibold text-[15px]">
                    {{ $d_title }}
                  </div>
                @endif
              </a>

              <div class="p-7 pb-4">
                <p class="text-[12px] font-semibold text-[#0f808a] mb-2">{{ $d_badge }}</p>
                <h2 class="text-[22px] font-semibold tracking-[-0.02em] text-[#1d1d1f] leading-snug mb-2.5 group-hover:text-[#0f808a] transition-colors">
                  <a href="{{ esc_url($d_url) }}">{{ $d_title }}</a>
                </h2>
                @if($d_tagline !== '')
                  <p class="text-[14.5px] text-[#515154] leading-relaxed mb-3">
                    {{ wp_trim_words($d_tagline, 24) }}
                  </p>
                @endif
                @if($spec_line !== '')
                  <p class="text-[13px] font-medium text-[#1d1d1f] pt-3 border-t border-black/[0.06]">
                    {{ $spec_line }}
                  </p>
                @endif
              </div>
            </div>

            <div class="px-7 pb-7 pt-2 flex items-center justify-between gap-4">
              <a href="{{ esc_url($d_url) }}" class="inline-flex items-center gap-1.5 text-[14px] font-semibold text-[#0f808a] hover:text-[#0a555c] leading-none group/cta">
                <span>Lihat Spesifikasi</span>
                <svg class="w-[18px] h-[18px] transition-transform duration-200 group-hover/cta:translate-x-0.5 group-hover/cta:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M6 6h12v12"/>
                  <path d="M6 18 18 6"/>
                </svg>
              </a>
              <a href="{{ esc_url(home_url('/bandingkan/?d1=' . $d_slug)) }}" class="text-[13px] font-medium text-[#6e6e73] hover:text-[#0f808a] transition-colors">
                Bandingkan
              </a>
            </div>
          </article>
        @endforeach
      </div>
    @else
      <div class="bg-white rounded-3xl p-12 text-center border border-black/[0.06]">
        <p class="text-[17px] text-[#515154] mb-4">Belum ada unit drone terdaftar dalam kategori {{ $term_name }}.</p>
        <a href="{{ home_url('/bandingkan/') }}" class="inline-flex items-center text-[14px] font-semibold text-[#0f808a] hover:underline">
          Lihat seluruh perbandingan armada FDS &rarr;
        </a>
      </div>
    @endif

  </div>
</section>

{{-- 3. PANDUAN TEKNIS & INTERNAL LINKS KATEGORI --}}
<section class="bg-white py-16 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
    <div class="lg:col-span-8 fds-drone-guide">
      <h2>{{ $active_copy['guide_title'] }}</h2>
      @foreach($active_copy['paragraphs'] as $para)
        <p>{{ $para }}</p>
      @endforeach
    </div>

    <div class="lg:col-span-4 space-y-6 border-t lg:border-t-0 lg:border-l border-black/[0.08] pt-8 lg:pt-0 lg:pl-10">
      <div>
        <h3 class="text-[18px] font-semibold text-[#1d1d1f] mb-2">Dukungan Manufaktur Lokal</h3>
        <p class="text-[14px] text-[#515154] leading-relaxed mb-4">
          Seluruh unit dirakit di workshop Sleman, D.I. Yogyakarta oleh PT Karya Solusi Angkasa dengan sertifikasi TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015.
        </p>
        <ul class="space-y-2.5 text-[14px] font-semibold text-[#0f808a]">
          <li><a href="{{ home_url('/bandingkan/') }}" class="hover:underline">Bandingkan Spesifikasi Antar Model &rarr;</a></li>
          <li><a href="{{ home_url('/tentang-kami/') }}" class="hover:underline">Profil Pabrik &amp; Sertifikasi FDS &rarr;</a></li>
          <li><a href="{{ home_url('/kontak/') }}" class="hover:underline">Ajukan Penawaran &amp; Jadwal Demo &rarr;</a></li>
        </ul>
      </div>
    </div>
  </div>
</section>

@endsection

