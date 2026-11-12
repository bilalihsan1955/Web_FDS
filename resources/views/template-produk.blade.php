{{--
  Template Name: Katalog Produk
--}}

@extends('layouts.app')

@section('content')

@php
  $pk = function_exists('\App\fds_get_produk_catalog_content')
      ? \App\fds_get_produk_catalog_content()
      : [];

  $href = static function ($url): string {
      return function_exists('\App\fds_produk_normalize_href')
          ? \App\fds_produk_normalize_href((string) $url)
          : home_url((string) $url);
  };

  $all_drones = get_posts([
      'post_type'      => 'drone',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'orderby'        => 'menu_order title',
      'order'          => 'ASC',
  ]);

  $agrikultur_drones = [];
  $pemetaan_drones   = [];
  $kargo_drones      = [];
  $other_drones      = [];

  $ferto_order = ['ferto-5l' => 1, 'ferto-10l' => 2, 'ferto-15l' => 3, 'ferto-22l' => 4, 'ferto-30l' => 5, 'ferto-50l' => 6];

  foreach ($all_drones as $d_post) {
      $d_slug = strtolower((string) $d_post->post_name);
      if (strpos($d_slug, 'rebo') !== false || strpos($d_slug, 'agrea') !== false || strpos($d_slug, 'ferto-20') !== false) {
          continue;
      }

      $terms     = get_the_terms($d_post->ID, 'kategori_drone');
      $cat_slug  = (!empty($terms) && !is_wp_error($terms))
          ? strtolower((string) $terms[0]->slug)
          : sanitize_title((string) (get_post_meta($d_post->ID, 'drone_kategori', true) ?: 'agrikultur'));
      $model_key = function_exists('\App\fds_get_drone_model_key')
          ? \App\fds_get_drone_model_key($d_post->ID)
          : $d_slug;

      if ($cat_slug === 'kargo' || $model_key === 'delfro') {
          $kargo_drones[] = $d_post;
      } elseif (strpos($cat_slug, 'peta') !== false || strpos($cat_slug, 'gis') !== false || in_array($model_key, ['deltav', 'multipurpose'], true)) {
          $pemetaan_drones[] = $d_post;
      } elseif (in_array($cat_slug, ['agrikultur', 'sprayer', 'pertanian', ''], true) || strpos($model_key, 'ferto') === 0) {
          $agrikultur_drones[] = $d_post;
      } else {
          $other_drones[] = $d_post;
      }
  }

  usort($agrikultur_drones, function ($a, $b) use ($ferto_order) {
      $ka = function_exists('\App\fds_get_drone_model_key') ? \App\fds_get_drone_model_key($a->ID) : strtolower((string) $a->post_name);
      $kb = function_exists('\App\fds_get_drone_model_key') ? \App\fds_get_drone_model_key($b->ID) : strtolower((string) $b->post_name);
      $oa = $ferto_order[$ka] ?? 99;
      $ob = $ferto_order[$kb] ?? 99;
      return $oa === $ob ? strcmp($a->post_title, $b->post_title) : ($oa <=> $ob);
  });

  if (function_exists('fds_sort_drone_posts')) {
      $agrikultur_drones = fds_sort_drone_posts($agrikultur_drones);
      $pemetaan_drones   = fds_sort_drone_posts($pemetaan_drones);
      $kargo_drones      = fds_sort_drone_posts($kargo_drones);
      $other_drones      = fds_sort_drone_posts($other_drones);
  }

  $cat_agri_url      = get_term_link('agrikultur', 'kategori_drone');
  $cat_pemetaan_url  = get_term_link('pemetaan-gis', 'kategori_drone');
  $cat_kargo_url     = get_term_link('kargo', 'kategori_drone');
  $cat_agri_href     = (!is_wp_error($cat_agri_url) && is_string($cat_agri_url)) ? $cat_agri_url : home_url('/kategori-drone/agrikultur/');
  $cat_pemetaan_href = (!is_wp_error($cat_pemetaan_url) && is_string($cat_pemetaan_url)) ? $cat_pemetaan_url : home_url('/kategori-drone/pemetaan-gis/');
  $cat_kargo_href    = (!is_wp_error($cat_kargo_url) && is_string($cat_kargo_url)) ? $cat_kargo_url : home_url('/kategori-drone/kargo/');

  $produk_faqs = function_exists('\App\fds_get_produk_faqs') ? \App\fds_get_produk_faqs() : (array) ($pk['faqs'] ?? []);
  $highlights  = (array) ($pk['highlights'] ?? []);
  $matrix_cols = (array) ($pk['matrix_columns'] ?? []);

  $build_matrix_rows = static function (array $posts): array {
      $rows = [];
      foreach ($posts as $mpost) {
          if (function_exists('\App\fds_get_drone_detail_data')) {
              $mdetail = \App\fds_get_drone_detail_data((int) $mpost->ID);
              if (!empty($mdetail)) {
                  $rows[] = [
                      'name'      => (string) ($mdetail['name'] ?? get_the_title($mpost->ID)),
                      'permalink' => (string) ($mdetail['permalink'] ?? get_permalink($mpost->ID)),
                      'function'  => (string) ($mdetail['matrix_function'] ?? ($mdetail['card_tagline'] ?? '')),
                      'payload'   => (string) ($mdetail['matrix_payload'] ?? ($mdetail['card_payload'] ?? '')),
                      'duration'  => (string) ($mdetail['matrix_duration'] ?? ($mdetail['card_metric_value'] ?? '')),
                      'cert'      => (string) ($mdetail['matrix_cert'] ?? ''),
                  ];
              }
          }
      }
      return $rows;
  };

  $has_row_certs = static fn(array $rows): bool => count(array_filter($rows, static fn($r) => trim((string) ($r['cert'] ?? '')) !== '')) > 0;

  $cat_term_label = static function (string $slug, string $fallback): string {
      $term = get_term_by('slug', $slug, 'kategori_drone');
      if ($term && !is_wp_error($term) && !empty($term->name)) {
          return function_exists('fds_clean_string') ? fds_clean_string((string) $term->name) : (string) $term->name;
      }
      return $fallback;
  };

  $matrix_categories = [];
  $agri_rows = $build_matrix_rows($agrikultur_drones);
  if (!empty($agri_rows)) {
      $matrix_categories[] = [
          'key'       => 'agrikultur',
          'label'     => $cat_term_label('agrikultur', 'Agrikultur'),
          'rows'      => $agri_rows,
          'has_certs' => $has_row_certs($agri_rows),
      ];
  }
  $pemetaan_rows = $build_matrix_rows($pemetaan_drones);
  if (!empty($pemetaan_rows)) {
      $matrix_categories[] = [
          'key'       => 'pemetaan',
          'label'     => $cat_term_label('pemetaan-gis', 'Pemetaan & GIS'),
          'rows'      => $pemetaan_rows,
          'has_certs' => $has_row_certs($pemetaan_rows),
      ];
  }
  $kargo_rows = $build_matrix_rows($kargo_drones);
  if (!empty($kargo_rows)) {
      $matrix_categories[] = [
          'key'       => 'kargo',
          'label'     => $cat_term_label('kargo', 'Kargo'),
          'rows'      => $kargo_rows,
          'has_certs' => $has_row_certs($kargo_rows),
      ];
  }
  $other_rows = $build_matrix_rows($other_drones);
  if (!empty($other_rows)) {
      $matrix_categories[] = [
          'key'       => 'lainnya',
          'label'     => 'Varian Tambahan',
          'rows'      => $other_rows,
          'has_certs' => $has_row_certs($other_rows),
      ];
  }
@endphp

{{-- 1. HERO KATALOG PRODUK --}}
<section class="relative pt-32 sm:pt-40 pb-20 sm:pb-24 bg-gradient-to-br from-[#0f808a] via-[#0a555c] to-[#052b2e] overflow-hidden">
  <div class="absolute inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
    <svg class="absolute inset-0 w-full h-full object-cover" viewBox="0 0 1440 520" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="produk-wave1" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#ffffff" stop-opacity="0.12" />
          <stop offset="100%" stop-color="#052b2e" stop-opacity="0.55" />
        </linearGradient>
        <linearGradient id="produk-wave2" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#75e6f0" stop-opacity="0.18" />
          <stop offset="100%" stop-color="#031e20" stop-opacity="0.75" />
        </linearGradient>
      </defs>
      <path d="M0,190 C360,230 740,350 1080,390 C1260,410 1380,400 1440,375 L1440,520 L0,520 Z" fill="url(#produk-wave1)" />
      <path d="M0,330 C320,350 680,430 1040,460 C1240,475 1360,470 1440,450 L1440,520 L0,520 Z" fill="url(#produk-wave2)" />
    </svg>
  </div>

  <div class="relative z-10 max-w-[1400px] mx-auto px-6 lg:px-12">
    @if(!empty($pk['hero_badge']))
      <p class="text-[14px] sm:text-[15px] font-semibold text-[#a3eef5] mb-3 tracking-wide">
        {{ $pk['hero_badge'] }}
      </p>
    @endif

    <h1 class="text-[36px] sm:text-[48px] lg:text-[58px] font-semibold tracking-[-0.03em] text-white leading-[1.08] max-w-[920px]">
      {{ $pk['hero_title'] ?? 'Armada Drone UAV Indonesia untuk Agrikultur, Pemetaan & Kargo.' }}
    </h1>

    @if(!empty($pk['hero_desc']))
      <p class="mt-4 text-[16px] sm:text-[18.5px] text-white/85 leading-relaxed max-w-[820px]">
        {{ $pk['hero_desc'] }}
      </p>
    @endif

    <div class="mt-8 flex flex-wrap items-center gap-3.5">
      @if(!empty($pk['hero_cta1_text']))
        <a href="{!! esc_url($href($pk['hero_cta1_url'] ?? '/kontak/?produk=armada-uav-fds#hubungi-form')) !!}"
           class="inline-flex items-center justify-center min-h-[44px] bg-white text-[#052b2e] hover:bg-[#f5f5f7] text-[14.5px] font-semibold px-6 py-3 rounded-full transition-colors shadow-lg shadow-black/15">
          {{ $pk['hero_cta1_text'] }}
        </a>
      @endif
      @if(!empty($pk['hero_cta2_text']))
        <a href="{!! esc_url($href($pk['hero_cta2_url'] ?? '/bandingkan/')) !!}"
           class="inline-flex items-center justify-center min-h-[44px] bg-white/15 hover:bg-white/25 text-white border border-white/25 text-[14.5px] font-semibold px-6 py-3 rounded-full transition-colors">
          {{ $pk['hero_cta2_text'] }}
        </a>
      @endif
    </div>

    <nav aria-label="Navigasi kategori produk" class="mt-8 pt-6 border-t border-white/15 flex flex-wrap items-center gap-3">
      <a href="#kategori-agrikultur" class="inline-flex items-center min-h-[40px] px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-[13.5px] font-semibold text-white transition-colors">
        {{ $pk['nav_agri_label'] ?? 'Agrikultur — Seri FERTO' }} ({{ count($agrikultur_drones) }})
      </a>
      <a href="#kategori-pemetaan" class="inline-flex items-center min-h-[40px] px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-[13.5px] font-semibold text-white transition-colors">
        {{ $pk['nav_pemetaan_label'] ?? 'Pemetaan & Inspeksi' }} ({{ count($pemetaan_drones) }})
      </a>
      <a href="#kategori-kargo" class="inline-flex items-center min-h-[40px] px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-[13.5px] font-semibold text-white transition-colors">
        {{ $pk['nav_kargo_label'] ?? 'Kargo Logistik' }} ({{ count($kargo_drones) }})
      </a>
    </nav>
  </div>
</section>

{{-- 2. STANDAR MUTU & LINGKUP SERTIFIKASI RESMI --}}
@if(!empty($highlights))
<section class="bg-white py-10 sm:py-12 border-b border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 md:grid-cols-3 gap-8">
    @foreach($highlights as $hl)
      <div class="pt-4 border-t border-[#0f808a]/25">
        @if(!empty($hl['title']))
          <p class="text-[22px] font-semibold tracking-[-0.02em] text-[#0f808a]">{{ $hl['title'] }}</p>
        @endif
        @if(!empty($hl['desc']))
          <p class="mt-1.5 text-[14.5px] text-[#515154] leading-relaxed">
            {!! wp_kses((string) $hl['desc'], ['strong' => []]) !!}
          </p>
        @endif
      </div>
    @endforeach
  </div>
</section>
@endif

{{-- 3. KATEGORI 1: AGRIKULTUR (SERI FERTO) --}}
<section id="kategori-agrikultur" class="bg-[#f5f5f7] py-16 sm:py-20">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
      <div class="max-w-[780px]">
        @if(!empty($pk['cat_agri_badge']))
          <p class="text-[13.5px] font-semibold text-[#0f808a] mb-2">{{ $pk['cat_agri_badge'] }}</p>
        @endif
        <h2 class="text-[32px] sm:text-[42px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
          {{ $pk['cat_agri_title'] ?? 'Drone Pertanian Presisi Seri FERTO (5L – 50L)' }}
        </h2>
        @if(!empty($pk['cat_agri_desc']))
          <p class="mt-3 text-[15.5px] sm:text-[16.5px] text-[#515154] leading-relaxed">
            {{ $pk['cat_agri_desc'] }}
          </p>
        @endif
      </div>
      @if(!empty($pk['cat_agri_link_text']))
        <a href="{!! esc_url($cat_agri_href) !!}"
           class="inline-flex items-center gap-1.5 text-[14.5px] font-semibold text-[#0f808a] hover:text-[#0a555c] hover:underline flex-shrink-0 group leading-none">
          <span>{{ $pk['cat_agri_link_text'] }}</span>
          <svg class="w-[17px] h-[17px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
      @endif
    </div>

    @if(!empty($agrikultur_drones))
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
        @foreach($agrikultur_drones as $drone)
          <x-drone-card :drone="$drone" />
        @endforeach
      </div>
    @endif
  </div>
</section>

{{-- 4. KATEGORI 2: PEMETAAN & INSPEKSI --}}
<section id="kategori-pemetaan" class="bg-white py-16 sm:py-20 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
      <div class="max-w-[780px]">
        @if(!empty($pk['cat_pemetaan_badge']))
          <p class="text-[13.5px] font-semibold text-[#0f808a] mb-2">{{ $pk['cat_pemetaan_badge'] }}</p>
        @endif
        <h2 class="text-[32px] sm:text-[42px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
          {{ $pk['cat_pemetaan_title'] ?? 'Drone Pemetaan Fixed-Wing VTOL & Inspeksi Modular' }}
        </h2>
        @if(!empty($pk['cat_pemetaan_desc']))
          <p class="mt-3 text-[15.5px] sm:text-[16.5px] text-[#515154] leading-relaxed">
            {{ $pk['cat_pemetaan_desc'] }}
          </p>
        @endif
      </div>
      @if(!empty($pk['cat_pemetaan_link_text']))
        <a href="{!! esc_url($cat_pemetaan_href) !!}"
           class="inline-flex items-center gap-1.5 text-[14.5px] font-semibold text-[#0f808a] hover:text-[#0a555c] hover:underline flex-shrink-0 group leading-none">
          <span>{{ $pk['cat_pemetaan_link_text'] }}</span>
          <svg class="w-[17px] h-[17px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
      @endif
    </div>

    @if(!empty($pemetaan_drones))
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
        @foreach($pemetaan_drones as $drone)
          <x-drone-card :drone="$drone" />
        @endforeach
      </div>
    @endif
  </div>
</section>

{{-- 5. KATEGORI 3: KARGO LOGISTIK --}}
<section id="kategori-kargo" class="bg-[#f5f5f7] py-16 sm:py-20 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
      <div class="max-w-[780px]">
        @if(!empty($pk['cat_kargo_badge']))
          <p class="text-[13.5px] font-semibold text-[#0f808a] mb-2">{{ $pk['cat_kargo_badge'] }}</p>
        @endif
        <h2 class="text-[32px] sm:text-[42px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
          {{ $pk['cat_kargo_title'] ?? 'Drone Kargo Otonom DELFRO' }}
        </h2>
        @if(!empty($pk['cat_kargo_desc']))
          <p class="mt-3 text-[15.5px] sm:text-[16.5px] text-[#515154] leading-relaxed">
            {{ $pk['cat_kargo_desc'] }}
          </p>
        @endif
      </div>
      @if(!empty($pk['cat_kargo_link_text']))
        <a href="{!! esc_url($cat_kargo_href) !!}"
           class="inline-flex items-center gap-1.5 text-[14.5px] font-semibold text-[#0f808a] hover:text-[#0a555c] hover:underline flex-shrink-0 group leading-none">
          <span>{{ $pk['cat_kargo_link_text'] }}</span>
          <svg class="w-[17px] h-[17px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
      @endif
    </div>

    @if(!empty($kargo_drones))
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
        @foreach($kargo_drones as $drone)
          <x-drone-card :drone="$drone" />
        @endforeach
      </div>
    @endif
  </div>
</section>

@if(!empty($other_drones))
<section id="kategori-lainnya" class="bg-white py-16 sm:py-20 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="max-w-[780px] mb-10">
      <p class="text-[13.5px] font-semibold text-[#0f808a] mb-2">Platform Khusus Lainnya</p>
      <h2 class="text-[32px] sm:text-[42px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
        Varian Wahana Tambahan
      </h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
      @foreach($other_drones as $drone)
        <x-drone-card :drone="$drone" />
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 6. MATRIKS RINGKAS PEMILIHAN ARMADA PER KATEGORI (LIVE 1:1 CPT DRONE + FULL ROW CLICK) --}}
@if(!empty($matrix_cols) && !empty($matrix_categories))
<section id="ringkasan-spesifikasi" class="bg-white py-16 sm:py-20 border-t border-black/[0.06]" data-fds-matrix-root>
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-7">
      <div>
        <h2 class="text-[28px] sm:text-[36px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-tight">
          {{ $pk['matrix_title'] ?? 'Ringkasan Spesifikasi Lini Wahana FDS' }}
        </h2>
        @if(!empty($pk['matrix_desc']))
          <p class="mt-2 text-[15.5px] text-[#515154]">
            {{ $pk['matrix_desc'] }}
          </p>
        @endif
      </div>
      @if(!empty($pk['matrix_cta_text']))
        <a href="{!! esc_url($href($pk['matrix_cta_url'] ?? '/bandingkan/')) !!}"
           class="inline-flex items-center justify-center min-h-[44px] bg-[#0f808a] hover:bg-[#0a555c] text-white text-[14px] font-semibold px-6 py-2.5 rounded-full transition-colors flex-shrink-0">
          {{ $pk['matrix_cta_text'] }}
        </a>
      @endif
    </div>

    {{-- Pilihan Kategori Ringkasan Spesifikasi (Identik tab filter Beranda, tanpa angka) --}}
    <div class="flex flex-wrap gap-2 mb-8"
         role="tablist"
         aria-label="Pilih kategori ringkasan spesifikasi drone">
      @foreach($matrix_categories as $mcat)
        @php $is_active_mcat = $loop->first; @endphp
        <button type="button"
                role="tab"
                id="fds-matrix-tab-{{ $mcat['key'] }}"
                aria-controls="fds-matrix-panel-{{ $mcat['key'] }}"
                aria-selected="{{ $is_active_mcat ? 'true' : 'false' }}"
                tabindex="{{ $is_active_mcat ? '0' : '-1' }}"
                data-matrix-tab="{{ $mcat['key'] }}"
                class="drone-tab {{ $is_active_mcat ? 'active' : '' }}">
          {{ $mcat['label'] }}
        </button>
      @endforeach
    </div>

    @foreach($matrix_categories as $mcat)
      @php
        $is_active_mcat = $loop->first;
        $cat_has_certs  = !empty($mcat['has_certs']);
        $cat_cols       = $cat_has_certs ? $matrix_cols : array_slice($matrix_cols, 0, 4);
      @endphp
      <div id="fds-matrix-panel-{{ $mcat['key'] }}"
           data-matrix-panel="{{ $mcat['key'] }}"
           class="overflow-x-auto {{ $is_active_mcat ? '' : 'hidden' }}">
        <table class="fds-seo-spec-table fds-produk-matrix-table !my-0 min-w-[780px]">
          <thead>
            <tr>
              @foreach($cat_cols as $mcol)
                <th scope="col">{{ $mcol }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($mcat['rows'] as $mrow)
              <tr class="fds-matrix-row group"
                  data-href="{!! esc_url($mrow['permalink']) !!}"
                  onclick="if(!event.target.closest('a'))window.location.href=this.dataset.href;">
                <td>
                  <a href="{!! esc_url($mrow['permalink']) !!}"
                     class="inline-flex items-center gap-1.5 font-semibold text-[#1d1d1f] group-hover:text-[#0f808a] hover:text-[#0f808a] hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0f808a] rounded-sm">
                    <span>{{ $mrow['name'] }}</span>
                    <svg class="w-3.5 h-3.5 text-[#0f808a] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                      <path d="M6 6h12v12"/>
                      <path d="M6 18 18 6"/>
                    </svg>
                  </a>
                </td>
                <td>{{ $mrow['function'] }}</td>
                <td>{{ $mrow['payload'] }}</td>
                <td>{{ $mrow['duration'] }}</td>
                @if($cat_has_certs)
                  <td>{{ $mrow['cert'] !== '' ? $mrow['cert'] : '—' }}</td>
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endforeach
  </div>
</section>
<script>
(function () {
  var root = document.querySelector('[data-fds-matrix-root]');
  if (!root) return;
  var tabs = Array.prototype.slice.call(root.querySelectorAll('[data-matrix-tab]'));
  var panels = Array.prototype.slice.call(root.querySelectorAll('[data-matrix-panel]'));
  if (!tabs.length || !panels.length) return;

  function activateTab(key, focusTab) {
    tabs.forEach(function (tab) {
      var isMatch = tab.getAttribute('data-matrix-tab') === key;
      tab.setAttribute('aria-selected', isMatch ? 'true' : 'false');
      tab.setAttribute('tabindex', isMatch ? '0' : '-1');
      tab.classList.toggle('active', isMatch);
      if (isMatch && focusTab) tab.focus();
    });
    panels.forEach(function (panel) {
      var isMatch = panel.getAttribute('data-matrix-panel') === key;
      panel.classList.toggle('hidden', !isMatch);
    });
  }

  tabs.forEach(function (tab, idx) {
    tab.addEventListener('click', function () {
      activateTab(tab.getAttribute('data-matrix-tab'), false);
    });
    tab.addEventListener('keydown', function (e) {
      var nextIdx = -1;
      if (e.key === 'ArrowRight') nextIdx = (idx + 1) % tabs.length;
      else if (e.key === 'ArrowLeft') nextIdx = (idx - 1 + tabs.length) % tabs.length;
      else if (e.key === 'Home') nextIdx = 0;
      else if (e.key === 'End') nextIdx = tabs.length - 1;
      if (nextIdx !== -1) {
        e.preventDefault();
        activateTab(tabs[nextIdx].getAttribute('data-matrix-tab'), true);
      }
    });
  });
})();
</script>
@endif

{{-- 7. FAQ KATALOG PRODUK (SSR NATIVE DETAILS) --}}
@if(!empty($produk_faqs))
  <x-service-faq
    :title="$pk['faq_title'] ?? 'Pertanyaan Umum Katalog Drone'"
    :subtitle="$pk['faq_subtitle'] ?? 'Ringkasan pertanyaan teknis dan kepatuhan pengadaan lini wahana PT Karya Solusi Angkasa.'"
    :faqs="$produk_faqs"
    section-bg="gray"
  />
@endif

{{-- 8. CTA BAND B2B --}}
<x-cta-band
  :label="$pk['cta_label'] ?? 'Konsultasi Pengadaan & Uji Terbang'"
  :title="$pk['cta_title'] ?? 'Butuh Rekomendasi Spesifikasi Unit atau Dokumen e-Katalog?'"
  :description="$pk['cta_desc'] ?? 'Tim engineering PT Karya Solusi Angkasa siap membantu menyusun kajian teknis armada, jadwal demo unit di workshop Sleman, maupun permintaan penawaran resmi.'"
  :primary-text="$pk['cta_primary_text'] ?? 'Minta Penawaran Unit'"
  :primary-url="$href($pk['cta_primary_url'] ?? '/kontak/?produk=armada-uav-fds#hubungi-form')"
  :secondary-text="$pk['cta_secondary_text'] ?? 'Chat WhatsApp Tim Sales'"
  variant="dark"
/>

@endsection
