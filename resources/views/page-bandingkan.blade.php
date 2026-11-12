@extends('layouts.app')

@section('content')
@php
  $bk = function_exists('\App\fds_get_bandingkan_content')
      ? \App\fds_get_bandingkan_content()
      : [];

  $href_bk = static function ($u): string {
      return function_exists('\App\fds_produk_normalize_href')
          ? \App\fds_produk_normalize_href((string) $u)
          : home_url((string) $u);
  };

  // 1. CANONICAL CATALOG 1:1 DARI WORDPRESS CPT 'drone' VIA SINGLE SOURCE OF TRUTH (\App\fds_get_drone_detail_data)
  $cached_catalog = get_transient('fds_bandingkan_catalog_cache_v4');
  $cache_is_stale = false;
  if ($cached_catalog !== false && is_array($cached_catalog)) {
    $cache_blob = serialize($cached_catalog);
    if (
      strpos($cache_blob, 'FDS STATION') !== false ||
      stripos($cache_blob, 'FLIR') !== false ||
      stripos($cache_blob, 'sejak 2012') !== false ||
      stripos($cache_blob, 'Tanpa klaim') !== false
    ) {
      $cache_is_stale = true;
    }
  }

  if ($cached_catalog !== false && is_array($cached_catalog) && !$cache_is_stale && !empty($cached_catalog)) {
    $catalog = function_exists('fds_deep_decode') ? fds_deep_decode($cached_catalog) : $cached_catalog;
  } else {
    $db_drones = get_posts([
      'post_type'      => 'drone',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'orderby'        => 'menu_order title',
      'order'          => 'ASC',
    ]);

    if (function_exists('fds_sort_drone_posts') && !empty($db_drones)) {
      $db_drones = fds_sort_drone_posts($db_drones);
    }

    $catalog = [];

    foreach ($db_drones as $dp) {
      $slug = strtolower((string) $dp->post_name);
      if (
        $slug === '' ||
        strpos($slug, 'rebo') !== false ||
        strpos($slug, 'agrea') !== false ||
        strpos($slug, 'ferto-20') !== false
      ) {
        continue;
      }

      if (function_exists('\App\fds_get_drone_detail_data')) {
        $detail = \App\fds_get_drone_detail_data((int) $dp->ID);
        if (empty($detail)) {
          continue;
        }
        $cat_slug = (string) ($detail['cat_slug'] ?? sanitize_title((string) ($detail['kategori'] ?? 'agrikultur')));
        if (strpos($cat_slug, 'rebo') !== false) {
          continue;
        }
        $catalog[$slug] = [
          'slug'      => (string) ($detail['slug'] ?? $slug),
          'name'      => (string) ($detail['name'] ?? get_the_title($dp->ID)),
          'kategori'  => (string) ($detail['kategori'] ?? 'Agrikultur'),
          'cat_slug'  => $cat_slug,
          'badge'     => (string) ($detail['badge'] ?? ''),
          'tagline'   => (string) ($detail['tagline'] ?? ''),
          'color'     => '#0f808a',
          'specs'     => (array) ($detail['specs'] ?? []),
          'desc'      => (string) ($detail['desc'] ?? ''),
          'for'       => (array) ($detail['for'] ?? []),
          'stat1_num' => (string) ($detail['stat1_num'] ?? ''),
          'stat1_lbl' => (string) ($detail['stat1_lbl'] ?? ''),
          'stat2_num' => (string) ($detail['stat2_num'] ?? ''),
          'stat2_lbl' => (string) ($detail['stat2_lbl'] ?? ''),
          'stat3_num' => (string) ($detail['stat3_num'] ?? ''),
          'stat3_lbl' => (string) ($detail['stat3_lbl'] ?? ''),
          'stat4_num' => (string) ($detail['stat4_num'] ?? ''),
          'stat4_lbl' => (string) ($detail['stat4_lbl'] ?? ''),
          'url'       => (string) ($detail['permalink'] ?? get_permalink($dp->ID)),
          'hero_img'  => (string) ($detail['hero_url'] ?? ''),
          'specs_img' => (string) ($detail['specs_img'] ?? ($detail['hero_url'] ?? '')),
        ];
      }
    }

    // Fallback aman hanya bila query CPT drone kosong
    if (empty($catalog)) {
      $raw_catalog = function_exists('fds_get_default_drone_catalog') ? fds_get_default_drone_catalog() : \App\fds_get_default_drone_catalog();
      $cutoutBaseUri = get_template_directory_uri() . '/public/images/cutouts';
      $photoBaseUri  = get_template_directory_uri() . '/public/images/drones';
      foreach ($raw_catalog as $s => $item) {
        if (in_array($s, ['ferto-5', 'ferto-10', 'ferto-15', 'ferto-22', 'ferto-30', 'ferto-50'], true)) continue;
        if (stripos((string) $s, 'rebo') !== false || stripos((string) $s, 'agrea') !== false) continue;
        $item['slug']      = $s;
        $item['cat_slug']  = sanitize_title($item['kategori'] ?? 'agrikultur');
        $item['url']       = home_url('/drone/' . $s . '/');
        $item['hero_img']  = $photoBaseUri . '/' . $s . '.webp';
        $item['specs_img'] = $cutoutBaseUri . '/' . $s . '.png';
        $catalog[$s]       = $item;
      }
    }

    if (function_exists('fds_deep_decode')) {
      $catalog = fds_deep_decode($catalog);
    }
    set_transient('fds_bandingkan_catalog_cache_v4', $catalog, 12 * HOUR_IN_SECONDS);
  }

  // 2. RESOLVE QUERY PARAMS DENGAN DUKUNGAN ALIAS SLUG (misal ferto-15 -> ferto-15l)
  $resolve_slug = static function (string $raw) use ($catalog): string {
      $s = strtolower(trim($raw));
      if ($s === '') return '';
      if (isset($catalog[$s])) return $s;
      if (isset($catalog[$s . 'l'])) return $s . 'l';
      $stripped = rtrim($s, 'l');
      if ($stripped !== '' && isset($catalog[$stripped])) return $stripped;
      return '';
  };

  $raw_d1 = isset($_GET['d1']) ? sanitize_text_field(wp_unslash((string) $_GET['d1'])) : '';
  $raw_d2 = isset($_GET['d2']) ? sanitize_text_field(wp_unslash((string) $_GET['d2'])) : '';
  $req_d1 = $resolve_slug($raw_d1);
  $req_d2 = $resolve_slug($raw_d2);

  // Pastikan drone tidak identik dan berasal dari kategori yang sama
  if (!empty($req_d1) && !empty($req_d2)) {
    if ($req_d1 === $req_d2) {
      $req_d2 = '';
    } else {
      $c1 = strtolower($catalog[$req_d1]['cat_slug'] ?? $catalog[$req_d1]['kategori']);
      $c2 = strtolower($catalog[$req_d2]['cat_slug'] ?? $catalog[$req_d2]['kategori']);
      if ($c1 !== $c2) {
        $req_d2 = '';
      }
    }
  }

  // 3. KATEGORI DRONE RESMI DARI WP ADMIN (TAKSONOMI 'kategori_drone')
  $terms_db = get_terms([
    'taxonomy'   => 'kategori_drone',
    'hide_empty' => false,
    'orderby'    => 'term_id',
    'order'      => 'ASC',
  ]);
  $modal_categories = [];
  if (!empty($terms_db) && !is_wp_error($terms_db)) {
    foreach ($terms_db as $t) {
      if (stripos((string) $t->slug, 'rebo') !== false) {
        continue;
      }
      $modal_categories[$t->slug] = $t->name;
    }
  }
  if (empty($modal_categories)) {
    $modal_categories = [
      'agrikultur'   => 'Agrikultur',
      'pemetaan-gis' => 'Pemetaan & GIS',
      'kargo'        => 'Kargo',
    ];
  }

  // 4. DEEP DECODE & DEFENSIVE GUARDRAIL SCRUBBING VIA GLOBAL HELPER
  $catalog = fds_deep_decode($catalog);
  if (function_exists('fds_scrub_legacy_text')) {
    foreach ($catalog as $cSlug => $cItem) {
      $sLower  = strtolower((string) $cSlug);
      $isFerto = (strpos($sLower, 'ferto') === 0);
      $hasSni  = in_array($sLower, ['ferto-15l', 'ferto-22l', 'ferto-15', 'ferto-22'], true);

      foreach (['name', 'badge', 'tagline', 'desc', 'stat1_num', 'stat1_lbl', 'stat2_num', 'stat2_lbl', 'stat3_num', 'stat3_lbl', 'stat4_num', 'stat4_lbl'] as $fKey) {
        if (isset($cItem[$fKey]) && is_string($cItem[$fKey]) && $cItem[$fKey] !== '') {
          $cItem[$fKey] = fds_scrub_legacy_text($cItem[$fKey], $isFerto, $hasSni);
        }
      }

      if (!empty($cItem['specs']) && is_array($cItem['specs'])) {
        $cleanSpecs = [];
        foreach ($cItem['specs'] as $spPair) {
          if (!is_array($spPair) || count($spPair) < 2) continue;
          $spLbl    = fds_scrub_legacy_text((string) $spPair[0], $isFerto, $hasSni);
          $isCertSp = stripos($spLbl, 'sertifikasi') !== false;
          $spVal    = fds_scrub_legacy_text((string) $spPair[1], $isFerto, $hasSni, !$isCertSp);
          if (in_array($sLower, ['ferto-15l', 'ferto-15'], true) && (stripos($spLbl, 'payload') !== false || stripos($spLbl, 'kapasitas') !== false)) {
            if (stripos($spVal, '17') === false) {
              $spVal = '17 Liter (15 – 17 Liter)';
            }
          }
          if ($spVal === '') {
            if ($isCertSp) {
              if ($hasSni) {
                $spVal = 'TKDN + BMP 60,74% | SNI 9199:2023';
              } elseif ($isFerto) {
                $spVal = 'TKDN + BMP 60,74%';
              } else {
                continue;
              }
            } else {
              continue;
            }
          }
          $cleanSpecs[] = [$spLbl, $spVal];
        }
        $cItem['specs'] = $cleanSpecs;
      }

      if (!empty($cItem['for']) && is_array($cItem['for'])) {
        $cleanFor = [];
        foreach ($cItem['for'] as $forTxt) {
          $scrubbedFor = fds_scrub_legacy_text((string) $forTxt, $isFerto, $hasSni);
          if ($scrubbedFor !== '') {
            $cleanFor[] = $scrubbedFor;
          }
        }
        $cItem['for'] = $cleanFor;
      }

      $catalog[$cSlug] = $cItem;
    }
  }
  $modal_categories = fds_deep_decode($modal_categories);
  $json_catalog     = wp_json_encode($catalog);

  $bk_ui = [
    'sticky_slot1'       => (string) ($bk['sticky_slot1'] ?? 'Pilih Drone 1'),
    'sticky_slot2'       => (string) ($bk['sticky_slot2'] ?? 'Pilih Drone 2'),
    'slot_pick_title'    => (string) ($bk['slot_pick_title'] ?? 'Pilih Model Drone'),
    'slot_pick_sub'      => (string) ($bk['slot_pick_sub'] ?? 'Klik untuk memilih produk yang ingin dibandingkan'),
    'slot_pick_btn'      => (string) ($bk['slot_pick_btn'] ?? 'Pilih Model'),
    'slot_change_btn'    => (string) ($bk['slot_change_btn'] ?? 'Ganti model'),
    'slot_remove_btn'    => (string) ($bk['slot_remove_btn'] ?? 'Hapus'),
    'slot_offer_btn'     => (string) ($bk['slot_offer_btn'] ?? 'Minta Penawaran'),
    'slot_detail_btn'    => (string) ($bk['slot_detail_btn'] ?? 'Detail Produk'),
    'table_empty_title'  => (string) ($bk['table_empty_title'] ?? 'Belum Ada Model yang Dipilih'),
    'table_empty_desc'   => (string) ($bk['table_empty_desc'] ?? 'Silakan pilih model drone pada slot di atas untuk menampilkan lembar perbandingan spesifikasi.'),
    'uc_empty_model1'    => (string) ($bk['uc_empty_model1'] ?? 'Model Pertama Belum Dipilih'),
    'uc_empty_model2'    => (string) ($bk['uc_empty_model2'] ?? 'Model Kedua Belum Dipilih'),
    'stats_empty_model1' => (string) ($bk['stats_empty_model1'] ?? 'Keunggulan Model 1'),
    'stats_empty_model2' => (string) ($bk['stats_empty_model2'] ?? 'Keunggulan Model 2'),
    'stats_empty_text'   => (string) ($bk['stats_empty_text'] ?? 'Belum ada model dipilih'),
    'modal_sub'          => (string) ($bk['modal_sub'] ?? 'Pilih produk drone yang ingin dibandingkan.'),
    'modal_empty_title'  => (string) ($bk['modal_empty_title'] ?? 'Tidak Ada Drone Lain dalam Kategori Ini'),
    'modal_empty_desc'   => (string) ($bk['modal_empty_desc'] ?? 'Semua model drone dalam kategori ini sudah dipilih atau belum ada model pembanding lain yang terdaftar.'),
  ];
@endphp

<div id="compare-page" class="w-full bg-[#f5f5f7] font-sans">

  {{-- ── 1. HERO SECTION VIA GLOBAL COMPONENT ── --}}
  <x-page-hero
    :label="$bk['hero_label'] ?? 'Komparasi Model UAV'"
    :title="$bk['hero_title'] ?? 'Bandingkan Model Drone.'"
    :description="$bk['hero_desc'] ?? 'Pilih hingga 2 model UAV untuk membandingkan spesifikasi teknis, performa terbang, dan kecocokan operasional lapangan secara berdampingan.'"
  />

  {{-- STICKY MOBILE & COMPACT COMPARISON BAR --}}
  <div id="sticky-compare-bar" class="fixed top-[52px] inset-x-0 z-40 bg-white/95 backdrop-blur-md border-b border-black/[0.08] shadow-sm transform -translate-y-full transition-transform duration-300 pointer-events-none">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-12 py-2 flex items-center justify-between pointer-events-auto">
      <div id="sticky-slot-1" class="flex-1 flex items-center gap-2 justify-center text-center">
        <span class="text-[12px] sm:text-[13px] font-semibold text-[#1d1d1f] truncate">{{ $bk['sticky_slot1'] ?? 'Pilih Drone 1' }}</span>
      </div>
      <div class="px-3 text-[11px] font-bold text-[#86868b] tracking-wider">{{ $bk['sticky_vs'] ?? 'vs' }}</div>
      <div id="sticky-slot-2" class="flex-1 flex items-center gap-2 justify-center text-center">
        <span class="text-[12px] sm:text-[13px] font-semibold text-[#1d1d1f] truncate">{{ $bk['sticky_slot2'] ?? 'Pilih Drone 2' }}</span>
      </div>
    </div>
  </div>

  {{-- ── 2. VISUAL PRODUCT CARDS / EMPTY (+) PLACEHOLDERS (WARNA ABU-ABU #f5f5f7) ── --}}
  <section id="product-cards-section" class="bg-[#f5f5f7] pt-8 pb-16 sm:pb-24">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      <x-reveal-group :step="120" class="relative grid grid-cols-2 items-stretch">
        
        {{-- SLOT 1 CONTAINER (Card or Plus Placeholder) --}}
        <div id="slot-1-card-container" class="flex flex-col items-center text-center px-3 sm:px-8 lg:px-12"></div>

        {{-- SLOT 2 CONTAINER (Card or Plus Placeholder) --}}
        <div id="slot-2-card-container" class="flex flex-col items-center text-center px-3 sm:px-8 lg:px-12"></div>

        {{-- CENTER VERTICAL DIVIDER --}}
        <div class="hidden md:block absolute inset-y-0 left-1/2 w-px bg-black/[0.08] -translate-x-1/2 pointer-events-none"></div>

      </x-reveal-group>
    </div>
  </section>

  {{-- ── 4. SECTION SPESIFIKASI TEKNIS (WHITE — 100% DATA RESMI) ── --}}
  <section class="bg-white py-20">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      
      {{-- Section Header --}}
      <x-reveal class="text-center max-w-2xl mx-auto mb-14">
        <p class="text-[13px] font-semibold text-[#0f808a] tracking-wide mb-2">{{ $bk['specs_badge'] ?? 'Spesifikasi' }}</p>
        <h2 class="text-[32px] sm:text-[44px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-3">
          {{ $bk['specs_title'] ?? 'Direkayasa untuk performa nyata.' }}
        </h2>
        <p class="text-[15px] text-[#515154] leading-relaxed">
          {{ $bk['specs_desc'] ?? 'Tabel perbandingan data teknis resmi dari lembar spesifikasi masing-masing unit drone FDS.' }}
        </p>
      </x-reveal>

      {{-- Direct Specs Comparison Table --}}
      <x-reveal :delay="120" id="specs-compare-table-container" class="divide-y divide-black/[0.06] border-t border-b border-black/[0.06]"></x-reveal>

    </div>
  </section>

  {{-- ── 5. SECTION SASARAN PENGGUNA (AERODYNAMIC FLOW STRATA VECTOR BACKGROUND) ──────── --}}
  <section id="section-usecases" class="relative bg-gradient-to-br from-[#13abb9] via-[#0f808a] to-[#0a555c] py-24 overflow-hidden">

    {{-- Vector Background: Aerodynamic Flow Strata Lines (Identik dengan Hero Section Halaman Tentang Kami) --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
      <svg class="w-full h-full" viewBox="0 0 1440 600" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
        <defs>
          {{-- Base Canvas Gradient --}}
          <linearGradient id="cmp_flow_base" x1="720" y1="0" x2="720" y2="600" gradientUnits="userSpaceOnUse">
            <stop stop-color="#13abb9"/>
            <stop offset="0.65" stop-color="#0f808a"/>
            <stop offset="1" stop-color="#0a555c"/>
          </linearGradient>

          {{-- Wave Gradients --}}
          <linearGradient id="cmp_flow_grad1" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#0a555c" stop-opacity="0.22"/>
            <stop offset="50%" stop-color="#0a555c" stop-opacity="0.10"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
          </linearGradient>

          <linearGradient id="cmp_flow_grad2" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#0f808a" stop-opacity="0.28"/>
            <stop offset="50%" stop-color="#0a555c" stop-opacity="0.14"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
          </linearGradient>

          <linearGradient id="cmp_flow_grad3" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#46deec" stop-opacity="0.34"/>
            <stop offset="50%" stop-color="#0a555c" stop-opacity="0.16"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
          </linearGradient>

          <linearGradient id="cmp_flow_grad4" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#13abb9" stop-opacity="0.40"/>
            <stop offset="50%" stop-color="#0f808a" stop-opacity="0.18"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
          </linearGradient>

          <linearGradient id="cmp_flow_grad5" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#18d6e7" stop-opacity="0.46"/>
            <stop offset="50%" stop-color="#46deec" stop-opacity="0.22"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
          </linearGradient>

          {{-- Bottom Dark Fade to seamlessly blend into section below --}}
          <linearGradient id="cmp_bottom_fade" x1="0" y1="320" x2="0" y2="600" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#0a555c" stop-opacity="0"/>
            <stop offset="50%" stop-color="#0a555c" stop-opacity="0.6"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="1"/>
          </linearGradient>
        </defs>

        <g>
          <!-- Base Canvas -->
          <rect width="1440" height="600" fill="url(#cmp_flow_base)"/>

          <!-- 1. Wave Layer 1 (U-Cradle: Kiri -50px, Tengah 360px, Kanan -50px) -->
          <path d="M-50,-50 C250,40 500,360 720,360 C940,360 1190,40 1490,-50 L1490,700 L-50,700 Z" fill="url(#cmp_flow_grad1)"/>

          <!-- 2. Wave Layer 2 (Samping +135px, Tengah +50px) -->
          <path d="M-50,85 C250,160 500,410 720,410 C940,410 1190,160 1490,85 L1490,700 L-50,700 Z" fill="url(#cmp_flow_grad2)"/>

          <!-- 3. Wave Layer 3 (Samping +135px, Tengah +50px) -->
          <path d="M-50,220 C250,280 500,460 720,460 C940,460 1190,280 1490,220 L1490,700 L-50,700 Z" fill="url(#cmp_flow_grad3)"/>

          <!-- 4. Wave Layer 4 (Samping +135px, Tengah +55px) -->
          <path d="M-50,355 C250,400 500,515 720,515 C940,515 1190,400 1490,355 L1490,700 L-50,700 Z" fill="url(#cmp_flow_grad4)"/>

          <!-- 5. Wave Layer 5 (Samping +135px, Tengah +55px, Inti Depan) -->
          <path d="M-50,490 C250,510 500,570 720,570 C940,570 1190,510 1490,490 L1490,700 L-50,700 Z" fill="url(#cmp_flow_grad5)"/>

          <!-- Bottom Blend Gradient Overlay -->
          <rect y="320" width="1440" height="280" fill="url(#cmp_bottom_fade)"/>
        </g>
      </svg>
    </div>

    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 relative z-10">
      
      <x-reveal class="text-center max-w-2xl mx-auto mb-14">
        <p class="text-[13px] font-semibold text-white/80 tracking-wide mb-2">{{ $bk['uc_badge'] ?? 'Aplikasi & Use-Cases' }}</p>
        <h2 class="text-[32px] sm:text-[44px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-3">
          {{ $bk['uc_title'] ?? 'Untuk Siapa Model Ini Diciptakan.' }}
        </h2>
        <p class="text-[15px] text-white/80 leading-relaxed">
          {{ $bk['uc_desc'] ?? 'Perbandingan profil pengguna, jenis lahan, dan skenario misi yang paling optimal.' }}
        </p>
      </x-reveal>

      <x-reveal-group :step="100" class="relative grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-0 items-start">
        {{-- D1 FOR --}}
        <div id="d1-for-box" class="md:pr-8 lg:pr-14">
          <h3 id="d1-for-title" class="text-[18px] font-bold text-white mb-4 pb-3 border-b border-white/10">
            <span>Model 1</span>
          </h3>
          <div id="d1-for-list" class="grid grid-cols-1 gap-3.5"></div>
        </div>

        {{-- D2 FOR --}}
        <div id="d2-for-box" class="md:pl-8 lg:pl-14">
          <h3 id="d2-for-title" class="text-[18px] font-bold text-white mb-4 pb-3 border-b border-white/10">
            <span>Model 2</span>
          </h3>
          <div id="d2-for-list" class="grid grid-cols-1 gap-3.5"></div>
        </div>

        {{-- CENTER VERTICAL DIVIDER --}}
        <div class="hidden md:block absolute inset-y-0 left-1/2 w-px bg-white/[0.08] -translate-x-1/2 pointer-events-none"></div>
      </x-reveal-group>

    </div>
  </section>

  {{-- ── 6. SECTION STATS BAR (WHITE) ──────────────────────────── --}}
  <section id="section-stats" class="bg-white py-16 sm:py-20">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      <x-reveal-group :step="100" class="relative grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-0 items-start">
        
        {{-- D1 STATS --}}
        <div class="md:pr-10 lg:pr-16">
          <p id="d1-stats-header" class="text-[13px] font-semibold text-[#86868b] mb-6 text-center">
            {{ $bk['stats_empty_model1'] ?? 'Keunggulan Model 1' }}
          </p>
          <div id="d1-stats-row" class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center"></div>
        </div>

        {{-- D2 STATS --}}
        <div class="md:pl-10 lg:pl-16">
          <p id="d2-stats-header" class="text-[13px] font-semibold text-[#86868b] mb-6 text-center">
            {{ $bk['stats_empty_model2'] ?? 'Keunggulan Model 2' }}
          </p>
          <div id="d2-stats-row" class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center"></div>
        </div>

        {{-- CENTER VERTICAL DIVIDER --}}
        <div class="hidden md:block absolute inset-y-0 left-1/2 w-px bg-black/[0.08] -translate-x-1/2 pointer-events-none"></div>

      </x-reveal-group>
    </div>
  </section>

  {{-- ── 6B. PANDUAN MEMILIH ARMADA DRONE & PENGADAAN B2B (SSR) ── --}}
  <section class="bg-[#f5f5f7] py-16 sm:py-24 border-t border-black/[0.06]">
    <div class="max-w-[1100px] mx-auto px-6 lg:px-12 fds-drone-guide">
      @if(!empty($bk['guide_badge']))
        <p class="text-[13px] font-semibold text-[#0f808a] tracking-wide mb-3">{{ $bk['guide_badge'] }}</p>
      @endif
      <h2>{{ $bk['guide_title'] ?? 'Panduan Memilih Armada Drone & Pengadaan B2B (TKDN & SNI)' }}</h2>
      @if(!empty($bk['guide_lead']))
        <p class="lead">{{ $bk['guide_lead'] }}</p>
      @endif
      {!! wp_kses_post((string) ($bk['guide_html'] ?? '')) !!}
    </div>
  </section>

  {{-- ── 7. CLOSING CTA (VIBRANT BRAND TEAL GRADIENT BG) ── --}}
  <section class="relative bg-gradient-to-br from-[#13abb9] via-[#0f808a] to-[#0a555c] py-24 sm:py-32 lg:py-36 overflow-hidden text-center">
    
    {{-- Vector Background: Pure Organic Wave Fills --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
      <svg class="w-full h-full" viewBox="0 0 1000 400" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
        <defs>
          <linearGradient id="bandingkan-wave-fill1" x1="0%" y1="100%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="#46deec" stop-opacity="0.48" />
            <stop offset="60%" stop-color="#18d6e7" stop-opacity="0.22" />
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0" />
          </linearGradient>
          <linearGradient id="bandingkan-wave-fill2" x1="0%" y1="100%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="#75e6f0" stop-opacity="0.38" />
            <stop offset="70%" stop-color="#13abb9" stop-opacity="0.16" />
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0" />
          </linearGradient>
          <linearGradient id="bandingkan-wave-fill3" x1="100%" y1="100%" x2="0%" y2="0%">
            <stop offset="0%" stop-color="#a3eef5" stop-opacity="0.42" />
            <stop offset="50%" stop-color="#18d6e7" stop-opacity="0.22" />
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0" />
          </linearGradient>
        </defs>

        <!-- Pure Fluid Wave Fills: Sedikit di kiri bawah, mengalir elegan naik ke kanan -->
        <path d="M0,340 C280,320 470,220 780,150 C900,120 960,100 1000,80 L1000,400 L0,400 Z" fill="url(#bandingkan-wave-fill1)" />
        <path d="M0,380 C360,370 580,280 860,190 C930,160 970,140 1000,120 L1000,400 L0,400 Z" fill="url(#bandingkan-wave-fill2)" />
        <path d="M440,400 C590,340 780,300 1000,220 L1000,400 Z" fill="url(#bandingkan-wave-fill3)" />
      </svg>
    </div>

    <x-reveal-group :step="90" class="max-w-[1400px] mx-auto px-6 lg:px-12 text-center relative z-10">
      <h2 class="text-[36px] sm:text-[48px] lg:text-[54px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5 drop-shadow-sm">
        {{ $bk['cta_title'] ?? 'Siap menentukan pilihan armada Anda?' }}
      </h2>
      <p class="text-[16px] sm:text-[18px] lg:text-[19px] text-white/80 max-w-[540px] mx-auto mb-9 leading-relaxed">
        {{ $bk['cta_desc'] ?? 'Konsultasikan kebutuhan operasional dan uji terbang (demo unit) bersama tim teknis PT Karya Solusi Angkasa (FDS) di Yogyakarta.' }}
      </p>
      <div class="flex flex-wrap gap-4 justify-center">
        <a href="{!! esc_url($href_bk($bk['cta_primary_url'] ?? '/kontak/#hubungi-form')) !!}"
           class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.98] text-[#0f808a] text-[16px] font-semibold px-8 py-4 rounded-full transition-all duration-150 shadow-lg shadow-black/20">
          {{ $bk['cta_primary_text'] ?? 'Hubungi Tim Sales FDS' }}
        </a>
        <a href="{!! esc_url($href_bk($bk['cta_secondary_url'] ?? '/blog/')) !!}"
           class="inline-flex items-center text-white/80 hover:text-white text-[16px] font-medium transition-colors gap-2 px-4 py-3 leading-none group">
          <span class="leading-none">{{ $bk['cta_secondary_text'] ?? 'Baca studi kasus operasional' }}</span>
          <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
      </div>
    </x-reveal-group>
  </section>

  {{-- ── 8. CUSTOM APPLE-STYLE DRONE PICKER MODAL ── --}}
  <div id="drone-picker-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-md hidden items-center justify-center p-4 sm:p-6 transition-opacity duration-200">
    <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-hidden shadow-2xl border border-black/10 flex flex-col transform transition-all duration-300 scale-95 opacity-0" id="drone-picker-dialog">
      
      {{-- Modal Header --}}
      <div class="p-6 pb-4 border-b border-black/[0.08] flex items-center justify-between bg-[#fbfbfd]">
        <div>
          <div class="flex items-center gap-2 mb-0.5">
            <p class="text-[12px] font-semibold text-[#0f808a]">{{ $bk['modal_eyebrow'] ?? 'Katalog Produk' }}</p>
            <span id="modal-category-badge" class="hidden text-[11px] font-medium text-[#0f808a] bg-[#0f808a]/10 px-2.5 py-0.5 rounded-full"></span>
          </div>
          <h3 id="modal-heading-text" class="text-[20px] sm:text-[24px] font-bold text-[#1d1d1f] tracking-tight">
            {{ $bk['modal_title'] ?? 'Pilih Model Drone' }}
          </h3>
          <p id="modal-subheading-text" class="text-[13px] text-[#86868b] mt-0.5">
            {{ $bk['modal_sub'] ?? 'Pilih produk drone yang ingin dibandingkan.' }}
          </p>
        </div>
        <button type="button" onclick="closePickerModal()" class="w-9 h-9 rounded-full bg-black/5 hover:bg-black/10 flex items-center justify-center text-[#515154] hover:text-[#1d1d1f] transition-colors cursor-pointer" aria-label="Tutup">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      {{-- Modal Filter Tabs (Dinamis dari Taksonomi kategori_drone WP Admin) --}}
      <div id="modal-cat-tabs-container" class="px-6 py-3 border-b border-black/[0.06] bg-white flex flex-wrap gap-1.5">
        <button type="button" onclick="filterModalCategory('all', this)" class="modal-cat-tab active px-3.5 py-1.5 rounded-full text-[12px] font-semibold bg-[#0f808a] text-white shadow-sm transition-colors cursor-pointer" data-tab-cat="all">{{ $bk['modal_all_tab'] ?? 'Semua' }}</button>
        @foreach($modal_categories as $mSlug => $mName)
        <button type="button" onclick="filterModalCategory('{{ $mSlug }}', this)" class="modal-cat-tab px-3.5 py-1.5 rounded-full text-[12px] font-medium bg-[#f5f5f7] text-[#515154] hover:bg-[#ebebed] hover:text-[#1d1d1f] transition-colors cursor-pointer" data-tab-cat="{{ $mSlug }}">
          {{ function_exists('fds_clean_string') ? fds_clean_string((string) $mName) : $mName }}
        </button>
        @endforeach
      </div>

      {{-- Modal Body: Drone Cards Grid --}}
      <div id="modal-drones-grid" class="p-6 overflow-y-auto max-h-[60vh] grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($catalog as $slugKey => $dItem)
        <div class="modal-drone-card group border border-black/[0.08] hover:border-[#0f808a] rounded-2xl p-4 bg-white hover:bg-[#fbfbfd] transition-all cursor-pointer flex items-center gap-4 relative"
             data-slug="{{ $slugKey }}"
             data-cat-slug="{{ $dItem['cat_slug'] ?? sanitize_title($dItem['kategori']) }}"
             onclick="selectDroneForActiveSlot('{{ $slugKey }}')">
          <div class="w-20 h-20 bg-[#f5f5f7] rounded-xl flex items-center justify-center p-2 flex-shrink-0">
            <img src="{!! esc_url($dItem['specs_img'] ?: $dItem['hero_img']) !!}" alt="{{ function_exists('fds_clean_string') ? fds_clean_string((string) $dItem['name']) : $dItem['name'] }}" class="max-w-full max-h-full object-contain">
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-1.5 mb-1">
              @if(!empty($dItem['badge']))
              <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#0f808a]/10 text-[#0f808a]">
                {{ function_exists('fds_clean_string') ? fds_clean_string((string) $dItem['badge']) : $dItem['badge'] }}
              </span>
              @endif
            </div>
            <h4 class="text-[16px] font-bold text-[#1d1d1f] group-hover:text-[#0f808a] transition-colors leading-tight mb-1 truncate">
              {{ function_exists('fds_clean_string') ? fds_clean_string((string) $dItem['name']) : $dItem['name'] }}
            </h4>
            <p class="text-[12px] text-[#86868b] truncate">
              {{ function_exists('fds_clean_string') ? fds_clean_string((string) $dItem['kategori']) : $dItem['kategori'] }}
            </p>
          </div>
          <div class="w-8 h-8 rounded-full bg-[#f5f5f7] group-hover:bg-[#0f808a] group-hover:text-white flex items-center justify-center text-[#86868b] transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
          </div>
        </div>
        @endforeach
      </div>

    </div>
  </div>

</div>

{{-- ── 9. JAVASCRIPT COMPARISON ENGINE WITH SAME-CATEGORY RESTRICTION ── --}}
<script>
(function() {
  const CATALOG = {!! $json_catalog !!};
  const BK_UI = {!! wp_json_encode($bk_ui) !!};
  const KONTAK_URL_BASE = {!! wp_json_encode(home_url('/kontak/')) !!};
  let activePickingSlot = 1;
  let activeCatFilter = 'all';
  let d1Key = {!! wp_json_encode($req_d1) !!};
  let d2Key = {!! wp_json_encode($req_d2) !!};

  function normalizeCategory(slugOrName) {
    if (!slugOrName) return '';
    const s = String(slugOrName).toLowerCase().trim();
    if (s.includes('agrikultur') || s.includes('pertanian') || s.includes('ferto')) return 'agrikultur';
    if (s.includes('pemetaan') || s.includes('gis') || s.includes('inspeksi') || s.includes('survey')) return 'pemetaan-gis';
    if (s.includes('kargo') || s.includes('cargo') || s.includes('logistik')) return 'kargo';
    return s.replace(/[^a-z0-9]/g, '-');
  }

  window.openPickerModal = function(slot) {
    activePickingSlot = slot;
    const otherSlug = (slot === 1) ? d2Key : d1Key;
    const otherDrone = CATALOG[otherSlug] || null;

    const catBadge = document.getElementById('modal-category-badge');
    const subHeading = document.getElementById('modal-subheading-text');
    const tabsContainer = document.getElementById('modal-cat-tabs-container');

    if (otherDrone) {
      // Kategori dikunci ke kategori drone pembanding
      const lockedCat = normalizeCategory(otherDrone.cat_slug || otherDrone.kategori);
      const catName = otherDrone.kategori || 'Kategori yang Sama';
      
      if (catBadge) {
        catBadge.textContent = `Kategori: ${catName}`;
        catBadge.classList.remove('hidden');
      }
      if (subHeading) {
        subHeading.textContent = `Menampilkan model dalam kategori ${catName} untuk perbandingan yang relevan.`;
      }
      if (tabsContainer) {
        tabsContainer.classList.add('hidden'); // Sembunyikan tab filter karena kategori sudah dikunci
      }
      activeCatFilter = lockedCat;
      applyDroneModalFiltering(otherSlug, lockedCat);
    } else {
      // Bebas memilih kategori apa saja untuk model pertama
      if (catBadge) {
        catBadge.classList.add('hidden');
      }
      if (subHeading) {
        subHeading.textContent = BK_UI.modal_sub || 'Pilih produk drone yang ingin dibandingkan.';
      }
      if (tabsContainer) {
        tabsContainer.classList.remove('hidden');
      }
      activeCatFilter = 'all';
      // Reset tab button states
      document.querySelectorAll('.modal-cat-tab').forEach(b => {
        if (b.getAttribute('data-tab-cat') === 'all') {
          b.classList.remove('bg-[#f5f5f7]', 'text-[#515154]', 'font-medium');
          b.classList.add('bg-[#0f808a]', 'text-white', 'font-semibold', 'shadow-sm');
        } else {
          b.classList.remove('bg-[#0f808a]', 'text-white', 'font-semibold', 'shadow-sm');
          b.classList.add('bg-[#f5f5f7]', 'text-[#515154]', 'font-medium');
        }
      });
      applyDroneModalFiltering(null, null);
    }

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

  function applyDroneModalFiltering(otherSlug, lockedCategory) {
    let visibleCount = 0;
    const grid = document.getElementById('modal-drones-grid');
    const existingEmptyNotice = document.getElementById('modal-empty-drones-notice');
    if (existingEmptyNotice) existingEmptyNotice.remove();

    document.querySelectorAll('.modal-drone-card').forEach(card => {
      const cardSlug = card.getAttribute('data-slug');
      const cardCatSlug = normalizeCategory(card.getAttribute('data-cat-slug') || '');
      
      // 1. Sembunyikan drone yang sudah dipilih di slot lain
      if (otherSlug && cardSlug === otherSlug) {
        card.classList.add('hidden');
        return;
      }

      // 2. Jika kategori terkunci (slot lain sudah terisi), WAJIB kategori yang sama
      if (lockedCategory) {
        if (cardCatSlug === lockedCategory) {
          card.classList.remove('hidden');
          visibleCount++;
        } else {
          card.classList.add('hidden');
        }
        return;
      }

      // 3. Jika belum terkunci, filter berdasarkan tab kategori aktif
      if (activeCatFilter === 'all' || cardCatSlug === activeCatFilter) {
        card.classList.remove('hidden');
        visibleCount++;
      } else {
        card.classList.add('hidden');
      }
    });

    if (visibleCount === 0 && grid) {
      const emptyDiv = document.createElement('div');
      emptyDiv.id = 'modal-empty-drones-notice';
      emptyDiv.className = 'col-span-1 sm:col-span-2 py-12 text-center text-[#86868b]';
      emptyDiv.innerHTML = `
        <div class="w-12 h-12 rounded-full bg-[#f5f5f7] flex items-center justify-center mx-auto mb-3 text-[#86868b]">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-[15px] font-semibold text-[#1d1d1f] mb-1">${escapeHtml(BK_UI.modal_empty_title)}</p>
        <p class="text-[13px] text-[#86868b] max-w-sm mx-auto">${escapeHtml(BK_UI.modal_empty_desc)}</p>
      `;
      grid.appendChild(emptyDiv);
    }
  }

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

  window.filterModalCategory = function(catSlug, btn) {
    activeCatFilter = catSlug;
    document.querySelectorAll('.modal-cat-tab').forEach(b => {
      b.classList.remove('bg-[#0f808a]', 'text-white', 'font-semibold', 'shadow-sm');
      b.classList.add('bg-[#f5f5f7]', 'text-[#515154]', 'font-medium');
    });
    if (btn) {
      btn.classList.remove('bg-[#f5f5f7]', 'text-[#515154]', 'font-medium');
      btn.classList.add('bg-[#0f808a]', 'text-white', 'font-semibold', 'shadow-sm');
    }

    const otherSlug = (activePickingSlot === 1) ? d2Key : d1Key;
    const otherDrone = CATALOG[otherSlug] || null;
    applyDroneModalFiltering(otherSlug, otherDrone ? normalizeCategory(otherDrone.cat_slug || otherDrone.kategori) : null);
  };

  window.selectDroneForActiveSlot = function(slug) {
    const selectedDrone = CATALOG[slug];
    if (!selectedDrone) return;

    const otherSlug = (activePickingSlot === 1) ? d2Key : d1Key;
    const otherDrone = CATALOG[otherSlug] || null;

    if (otherDrone) {
      const cat1 = normalizeCategory(selectedDrone.cat_slug || selectedDrone.kategori);
      const cat2 = normalizeCategory(otherDrone.cat_slug || otherDrone.kategori);
      if (cat1 !== cat2) {
        alert(`Perbandingan hanya dapat dilakukan untuk model drone dalam kategori yang sama (${otherDrone.kategori}).`);
        return;
      }
      if (slug === otherSlug) {
        alert('Model ini sudah dipilih. Silakan pilih model drone yang berbeda.');
        return;
      }
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

    // 1. RENDER MAIN PRODUCT CARDS / PLUS PLACEHOLDERS
    renderSlotCard('slot-1-card-container', 1, d1);
    renderSlotCard('slot-2-card-container', 2, d2);

    // 2. RENDER SPECS TABLE
    renderSpecsTable(d1, d2);

    // 3. RENDER USE-CASES
    renderUseCases(d1, d2);

    // 4. RENDER STATS BARS
    renderStatsQuad(d1, d2);

    // 5. UPDATE STICKY BAR
    updateStickyBar();
  }

  function renderSlotCard(containerId, slotNum, drone) {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (!drone) {
      // NATURAL SEAMLESS APPLE-STYLE PLACEHOLDER (FLAT, CLEAN, NO GLOW, NO CARD BOX)
      container.innerHTML = `
        <div class="w-full flex flex-col items-center text-center">
          <div class="w-full h-56 sm:h-76 flex flex-col items-center justify-center mb-6">
            <button type="button" onclick="openPickerModal(${slotNum})"
                    class="group flex flex-col items-center justify-center cursor-pointer transition-colors duration-150">
              <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-black/[0.05] group-hover:bg-black/[0.1] text-[#1d1d1f] flex items-center justify-center transition-colors mb-4">
                <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              </div>
              <span class="text-[18px] sm:text-[20px] font-semibold text-[#1d1d1f] group-hover:text-[#0f808a] transition-colors mb-1">
                ${escapeHtml(BK_UI.slot_pick_title)}
              </span>
              <span class="text-[13px] text-[#86868b] max-w-xs leading-relaxed">
                ${escapeHtml(BK_UI.slot_pick_sub)}
              </span>
            </button>
          </div>

          <div class="min-h-[140px] flex items-center justify-center">
            <button type="button" onclick="openPickerModal(${slotNum})"
                    class="inline-flex items-center gap-1.5 bg-black/[0.06] hover:bg-black/[0.1] text-[#1d1d1f] font-semibold text-[13px] sm:text-[14px] px-5 py-2.5 rounded-full transition-colors cursor-pointer">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              <span>${escapeHtml(BK_UI.slot_pick_btn)}</span>
            </button>
          </div>
        </div>
      `;
    } else {
      // POPULATED APPLE PRODUCT CARD (LIGHT GRAY THEME)
      container.innerHTML = `
        <div class="w-full flex flex-col items-center text-center">
          <div class="w-full h-56 sm:h-76 flex items-center justify-center mb-6 relative">
            <img src="${escapeHtml(drone.specs_img || drone.hero_img || '')}" 
                 alt="${escapeHtml(drone.name)}" 
                 class="max-w-full max-h-full object-contain select-none drop-shadow-[0_12px_24px_rgba(0,0,0,0.1)] transition-all duration-300">
          </div>
          
          <p class="text-[13px] font-semibold text-[#0f808a] tracking-wide mb-1.5">
            ${escapeHtml(drone.badge || '')}
          </p>

          <h2 class="text-[32px] sm:text-[44px] font-bold tracking-[-0.03em] text-[#1d1d1f] mb-1 leading-tight">
            ${escapeHtml(drone.name)}
          </h2>

          <div class="mb-4 flex items-center gap-3">
            <button type="button" onclick="openPickerModal(${slotNum})" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-[#0f808a] hover:underline cursor-pointer">
              <span>${escapeHtml(BK_UI.slot_change_btn)}</span>
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <span class="text-black/20">|</span>
            <button type="button" onclick="clearSlot(${slotNum})" class="text-[13px] text-[#86868b] hover:text-red-600 transition-colors cursor-pointer">
              ${escapeHtml(BK_UI.slot_remove_btn)}
            </button>
          </div>

          <p class="text-[14px] sm:text-[15px] text-[#515154] max-w-md leading-relaxed mb-6 min-h-[44px]">
            ${escapeHtml(drone.tagline || '')}
          </p>

          <div class="flex flex-wrap gap-2.5 justify-center">
            <a href="${escapeHtml(KONTAK_URL_BASE)}?produk=${encodeURIComponent(drone.slug || '')}#hubungi-form" class="inline-flex items-center bg-[#0f808a] hover:bg-[#0a555c] text-white text-[13px] sm:text-[14px] font-semibold px-5 py-2.5 rounded-full transition-all shadow-sm">
              ${escapeHtml(BK_UI.slot_offer_btn)}
            </a>
            <a href="${escapeHtml(drone.url || '#')}" class="inline-flex items-center bg-white hover:bg-black/5 text-[#1d1d1f] text-[13px] sm:text-[14px] font-semibold px-4 py-2.5 rounded-full transition-all border border-black/10 shadow-sm">
              ${escapeHtml(BK_UI.slot_detail_btn)}
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
          <p class="text-[16px] font-semibold text-[#1d1d1f] mb-1">${escapeHtml(BK_UI.table_empty_title)}</p>
          <p class="text-[13px]">${escapeHtml(BK_UI.table_empty_desc)}</p>
        </div>
      `;
      return;
    }

    function normalizeSpecLabel(key) {
      const k = (key || '').trim();
      if (k === 'Kapasitas Tangki' || k === 'Kapasitas Tangki / Payload' || k === 'Kapasitas Payload' || k === 'Kapasitas Payload Biji' || k.toLowerCase().startsWith('kapasitas tangki') || k.toLowerCase().startsWith('kapasitas payload')) {
        return 'Payload';
      }
      return k;
    }

    const specsMap1 = {};
    if (d1 && d1.specs) {
      d1.specs.forEach(item => {
        if (Array.isArray(item) && item.length === 2) {
          const normKey = normalizeSpecLabel(item[0]);
          specsMap1[normKey] = item[1];
        }
      });
    }

    const specsMap2 = {};
    if (d2 && d2.specs) {
      d2.specs.forEach(item => {
        if (Array.isArray(item) && item.length === 2) {
          const normKey = normalizeSpecLabel(item[0]);
          specsMap2[normKey] = item[1];
        }
      });
    }

    const standardKeys = [
      'Payload',
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
      'Mode Pengoperasian',
      'Sistem Otonomi & Navigasi',
      'Sistem Kendali & Misi',
      'Ground Control Station',
      'Ground Control Software',
      'Software Pengendali',
      'Software Perencanaan Misi',
      'Sertifikasi & Standar',
      'Material Rangka',
      'Rangka & Proteksi',
      'Sistem Proteksi',
      'Ukuran Propeller',
      'Dimensi Kotak Payload',
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
      const rawVal1 = d1 ? (specsMap1[specTitle] !== undefined ? specsMap1[specTitle] : '—') : null;
      const rawVal2 = d2 ? (specsMap2[specTitle] !== undefined ? specsMap2[specTitle] : '—') : null;

      const val1 = rawVal1 !== null 
        ? escapeHtml(rawVal1) 
        : `<button type="button" onclick="openPickerModal(1)" class="text-[#0f808a] text-[13px] font-semibold hover:underline cursor-pointer">+ Pilih Drone</button>`;

      const val2 = rawVal2 !== null 
        ? escapeHtml(rawVal2) 
        : `<button type="button" onclick="openPickerModal(2)" class="text-[#0f808a] text-[13px] font-semibold hover:underline cursor-pointer">+ Pilih Drone</button>`;

      html += `
        <div class="py-7 transition-colors hover:bg-black/[0.01]">
          <div class="text-center mb-3.5">
            <span class="inline-block text-[13px] font-medium text-[#515154] bg-[#f5f5f7] px-4 py-1 rounded-full border border-black/[0.04]">
              ${escapeHtml(specTitle)}
            </span>
          </div>
          <div class="relative grid grid-cols-2 items-center">
            <div class="text-center text-[15px] sm:text-[16px] font-medium text-[#1d1d1f] leading-relaxed px-4 sm:px-8">
              ${val1}
            </div>
            <div class="text-center text-[15px] sm:text-[16px] font-medium text-[#1d1d1f] leading-relaxed px-4 sm:px-8">
              ${val2}
            </div>
            <div class="absolute inset-y-0 left-1/2 w-px bg-black/[0.08] -translate-x-1/2 pointer-events-none"></div>
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
      if (d1Title) d1Title.innerHTML = `<span>${escapeHtml(d1.name)}</span>`;
      if (d1List) {
        d1List.innerHTML = (d1.for || []).map(uc => `
          <div class="group relative bg-white/[0.06] hover:bg-white/[0.11] backdrop-blur-xl backdrop-saturate-150 border border-white/15 hover:border-white/30 rounded-2xl p-5 flex items-start gap-3.5 transition-all duration-200 shadow-[0_10px_24px_-6px_rgba(0,0,0,0.35),inset_0_1px_1px_0_rgba(255,255,255,0.3)] hover:shadow-[0_16px_32px_-8px_rgba(0,0,0,0.45),inset_0_1px_1.2px_0_rgba(255,255,255,0.45)] hover:-translate-y-0.5 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/50 to-transparent pointer-events-none opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-5 h-5 bg-white/10 text-white rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 border border-white/20">
              <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-[14px] font-medium text-white/90 leading-snug">${escapeHtml(uc)}</p>
          </div>
        `).join('') || '<p class="text-[14px] text-white/50">Lembar spesifikasi use-case lengkap tersedia di rincian produk.</p>';
      }
    } else {
      if (d1Title) d1Title.innerHTML = `<span>${escapeHtml(BK_UI.uc_empty_model1)}</span>`;
      if (d1List) d1List.innerHTML = `<div class="bg-white/[0.03] border border-dashed border-white/10 rounded-2xl p-6 text-center text-[13px] text-white/40"><button type="button" onclick="openPickerModal(1)" class="text-white hover:underline font-semibold cursor-pointer">+ ${escapeHtml(BK_UI.sticky_slot1)}</button></div>`;
    }

    const d2Title = document.getElementById('d2-for-title');
    const d2List = document.getElementById('d2-for-list');
    if (d2) {
      if (d2Title) d2Title.innerHTML = `<span>${escapeHtml(d2.name)}</span>`;
      if (d2List) {
        d2List.innerHTML = (d2.for || []).map(uc => `
          <div class="group relative bg-white/[0.06] hover:bg-white/[0.11] backdrop-blur-xl backdrop-saturate-150 border border-white/15 hover:border-white/30 rounded-2xl p-5 flex items-start gap-3.5 transition-all duration-200 shadow-[0_10px_24px_-6px_rgba(0,0,0,0.35),inset_0_1px_1px_0_rgba(255,255,255,0.3)] hover:shadow-[0_16px_32px_-8px_rgba(0,0,0,0.45),inset_0_1px_1.2px_0_rgba(255,255,255,0.45)] hover:-translate-y-0.5 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/50 to-transparent pointer-events-none opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-5 h-5 bg-white/10 text-white rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 border border-white/20">
              <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-[14px] font-medium text-white/90 leading-snug">${escapeHtml(uc)}</p>
          </div>
        `).join('') || '<p class="text-[14px] text-white/50">Lembar spesifikasi use-case lengkap tersedia di rincian produk.</p>';
      }
    } else {
      if (d2Title) d2Title.innerHTML = `<span>${escapeHtml(BK_UI.uc_empty_model2)}</span>`;
      if (d2List) d2List.innerHTML = `<div class="bg-white/[0.03] border border-dashed border-white/10 rounded-2xl p-6 text-center text-[13px] text-white/40"><button type="button" onclick="openPickerModal(2)" class="text-white hover:underline font-semibold cursor-pointer">+ ${escapeHtml(BK_UI.sticky_slot2)}</button></div>`;
    }
  }

  function renderStatsQuad(d1, d2) {
    const d1Header = document.getElementById('d1-stats-header');
    const d1Row = document.getElementById('d1-stats-row');
    if (d1) {
      if (d1Header) d1Header.textContent = `Keunggulan: ${cleanText(d1.name)}`;
      if (d1Row) {
        d1Row.innerHTML = `
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d1.stat1_num || 'UAV')}</p>
            <p class="text-[12px] font-medium text-[#86868b] tracking-normal mt-1">${escapeHtml(d1.stat1_lbl || 'Platform Industri')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d1.stat2_num || '100%')}</p>
            <p class="text-[12px] font-medium text-[#86868b] tracking-normal mt-1">${escapeHtml(d1.stat2_lbl || 'FDS Station GCS')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d1.stat3_num || 'Nasional')}</p>
            <p class="text-[12px] font-medium text-[#86868b] tracking-normal mt-1">${escapeHtml(d1.stat3_lbl || 'Dukungan Teknis')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d1.stat4_num || 'Garansi')}</p>
            <p class="text-[12px] font-medium text-[#86868b] tracking-normal mt-1">${escapeHtml(d1.stat4_lbl || 'Purna Jual Resmi')}</p>
          </div>
        `;
      }
    } else {
      if (d1Header) d1Header.textContent = BK_UI.stats_empty_model1 || 'Keunggulan Model 1';
      if (d1Row) d1Row.innerHTML = `<div class="col-span-4 text-center text-[13px] text-[#86868b] py-4">${escapeHtml(BK_UI.stats_empty_text)}</div>`;
    }

    const d2Header = document.getElementById('d2-stats-header');
    const d2Row = document.getElementById('d2-stats-row');
    if (d2) {
      if (d2Header) d2Header.textContent = `Keunggulan: ${cleanText(d2.name)}`;
      if (d2Row) {
        d2Row.innerHTML = `
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d2.stat1_num || 'UAV')}</p>
            <p class="text-[12px] font-medium text-[#86868b] tracking-normal mt-1">${escapeHtml(d2.stat1_lbl || 'Platform Industri')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d2.stat2_num || '100%')}</p>
            <p class="text-[12px] font-medium text-[#86868b] tracking-normal mt-1">${escapeHtml(d2.stat2_lbl || 'FDS Station GCS')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d2.stat3_num || 'Nasional')}</p>
            <p class="text-[12px] font-medium text-[#86868b] tracking-normal mt-1">${escapeHtml(d2.stat3_lbl || 'Dukungan Teknis')}</p>
          </div>
          <div>
            <p class="text-[32px] sm:text-[36px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">${escapeHtml(d2.stat4_num || 'Garansi')}</p>
            <p class="text-[12px] font-medium text-[#86868b] tracking-normal mt-1">${escapeHtml(d2.stat4_lbl || 'Purna Jual Resmi')}</p>
          </div>
        `;
      }
    } else {
      if (d2Header) d2Header.textContent = BK_UI.stats_empty_model2 || 'Keunggulan Model 2';
      if (d2Row) d2Row.innerHTML = `<div class="col-span-4 text-center text-[13px] text-[#86868b] py-4">${escapeHtml(BK_UI.stats_empty_text)}</div>`;
    }
  }

  function cleanText(str) {
    if (str === null || str === undefined) return '';
    let txt = String(str);
    if (!txt.includes('&')) return txt;
    try {
      const doc = new DOMParser().parseFromString(txt, 'text/html');
      return doc.body.textContent || txt;
    } catch(e) {
      return txt.replace(/&amp;/g, '&')
                .replace(/&#038;/g, '&')
                .replace(/&#8211;/g, '–')
                .replace(/&#8212;/g, '—')
                .replace(/&mdash;/g, '—')
                .replace(/&ndash;/g, '–')
                .replace(/&#039;/g, "'")
                .replace(/&quot;/g, '"');
    }
  }

  function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    const clean = cleanText(str);
    return clean
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  // Modal Close on click outside & Escape key
  document.addEventListener('click', function(e) {
    const modal = document.getElementById('drone-picker-modal');
    if (e.target === modal) {
      closePickerModal();
    }
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closePickerModal();
    }
  });

  // Sticky Comparison Bar on Scroll
  function updateStickyBar() {
    const bar = document.getElementById('sticky-compare-bar');
    const cardsSection = document.getElementById('product-cards-section');
    const slot1El = document.getElementById('sticky-slot-1');
    const slot2El = document.getElementById('sticky-slot-2');
    if (!bar || !cardsSection) return;

    const d1 = d1Key ? CATALOG[d1Key] : null;
    const d2 = d2Key ? CATALOG[d2Key] : null;

    if (slot1El) {
      slot1El.innerHTML = d1 
        ? `<button type="button" onclick="openPickerModal(1)" class="text-[12px] sm:text-[13px] font-semibold text-[#0f808a] hover:underline truncate max-w-[160px] sm:max-w-xs cursor-pointer">${escapeHtml(d1.name)}</button>` 
        : `<button type="button" onclick="openPickerModal(1)" class="text-[12px] text-[#86868b] hover:text-[#0f808a] cursor-pointer">+ ${escapeHtml(BK_UI.sticky_slot1)}</button>`;
    }

    if (slot2El) {
      slot2El.innerHTML = d2 
        ? `<button type="button" onclick="openPickerModal(2)" class="text-[12px] sm:text-[13px] font-semibold text-[#0f808a] hover:underline truncate max-w-[160px] sm:max-w-xs cursor-pointer">${escapeHtml(d2.name)}</button>` 
        : `<button type="button" onclick="openPickerModal(2)" class="text-[12px] text-[#86868b] hover:text-[#0f808a] cursor-pointer">+ ${escapeHtml(BK_UI.sticky_slot2)}</button>`;
    }

    const rect = cardsSection.getBoundingClientRect();
    if (rect.bottom < 60) {
      bar.classList.remove('-translate-y-full', 'pointer-events-none');
      bar.classList.add('translate-y-0', 'pointer-events-auto');
    } else {
      bar.classList.remove('translate-y-0', 'pointer-events-auto');
      bar.classList.add('-translate-y-full', 'pointer-events-none');
    }
  }

  window.addEventListener('scroll', updateStickyBar, { passive: true });

  document.addEventListener('DOMContentLoaded', function() {
    renderAll();
    updateStickyBar();
  });
})();
</script>
@endsection
