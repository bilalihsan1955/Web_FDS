@extends('layouts.app')

@section('content')

@php
  $decode_fn = function ($val) {
    $v = function_exists('fds_clean_string')
      ? fds_clean_string((string) $val)
      : trim((string) $val);
    if (!function_exists('fds_clean_string')) {
      $prev = '';
      $max = 5;
      while ($prev !== $v && $max-- > 0) {
        $prev = $v;
        $v = html_entity_decode(wp_specialchars_decode($v, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
      }
    }
    return trim($v);
  };

  $href = static function ($url): string {
    return function_exists('\App\fds_produk_normalize_href')
      ? \App\fds_produk_normalize_href((string) $url)
      : home_url((string) $url);
  };

  $term      = get_queried_object();
  $term_name = ($term && !empty($term->name)) ? $decode_fn($term->name) : 'Katalog Drone';
  $term_slug = ($term && !empty($term->slug)) ? (string) $term->slug : '';
  if (function_exists('fds_scrub_legacy_text')) {
    $is_ferto_cat = ($term_slug === 'agrikultur');
    $term_name = fds_scrub_legacy_text($term_name, $is_ferto_cat, $is_ferto_cat);
  }

  $active_copy = function_exists('\App\fds_get_kategori_drone_catalog_content')
    ? \App\fds_get_kategori_drone_catalog_content($term_slug, $term)
    : [
        'headline'         => 'Drone ' . $term_name . '.',
        'subtitle'         => 'Katalog resmi pesawat nirawak industrial buatan PT Karya Solusi Angkasa dengan standar manajemen mutu ISO 9001:2015.',
        'cta_kontak_label' => ($term_slug === 'agrikultur') ? 'Konsultasi Pengadaan & TKDN' : 'Konsultasi Pengadaan & Demo Unit',
        'cta_kontak_url'   => '/kontak/?layanan=' . urlencode('kategori-' . $term_slug) . '#hubungi-form',
        'guide_title'      => 'Keunggulan Wahana UAV Rekayasa PT Karya Solusi Angkasa',
        'paragraphs'       => [],
        'faqs'             => [],
      ];

  $ui = function_exists('\App\fds_get_kategori_drone_ui_content')
    ? \App\fds_get_kategori_drone_ui_content()
    : [
        'all_products_label' => 'Semua Produk',
        'hero_compare_text'  => 'Bandingkan Spesifikasi Drone',
        'hero_compare_url'   => '/bandingkan/',
        'empty_text_prefix'  => 'Belum ada unit drone terdaftar dalam kategori',
        'empty_link_text'    => 'Lihat seluruh katalog armada FDS',
        'sidebar_title'      => 'Dukungan Manufaktur Lokal',
        'sidebar_desc'       => 'Seluruh unit dirakit di workshop Sleman, D.I. Yogyakarta oleh PT Karya Solusi Angkasa dengan sistem manajemen mutu ISO 9001:2015, sertifikasi TKDN + BMP 60,74% untuk lini pertanian FERTO, serta SNI 9199:2023 pada FERTO 15 dan FERTO 22.',
        'sidebar_links'      => [
            ['label' => 'Lihat Katalog Lengkap UAV FDS', 'url' => '/produk/'],
            ['label' => 'Bandingkan Spesifikasi Antar Model', 'url' => '/bandingkan/'],
            ['label' => 'Profil Pabrik & Sertifikasi FDS', 'url' => '/tentang-kami/'],
            ['label' => 'Ajukan Penawaran & Jadwal Demo', 'url' => '{cta_kontak_url}'],
        ],
        'faq_title_prefix'   => 'Pertanyaan Umum: Drone',
        'faq_subtitle'       => 'Informasi sertifikasi, pilihan kapasitas muatan, pelatihan operator, dan kesiapan pengadaan B2B maupun instansi.',
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
  if (function_exists('fds_sort_drone_posts')) {
    $cat_drones = fds_sort_drone_posts($cat_drones);
  }
  $cat_faqs         = function_exists('App\fds_get_kategori_drone_faqs') ? \App\fds_get_kategori_drone_faqs($term_slug) : (array) ($active_copy['faqs'] ?? []);
  $cta_kontak_label = $active_copy['cta_kontak_label'] ?: (($term_slug === 'agrikultur') ? 'Konsultasi Pengadaan & TKDN' : 'Konsultasi Pengadaan & Demo Unit');
  $cta_kontak_url   = $href((string) ($active_copy['cta_kontak_url'] ?: ('/kontak/?layanan=' . urlencode('kategori-' . $term_slug) . '#hubungi-form')));
  $hero_compare_url = $href((string) ($ui['hero_compare_url'] ?? '/bandingkan/'));
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
    <h1 class="text-[36px] sm:text-[48px] lg:text-[56px] font-semibold tracking-[-0.03em] text-white leading-[1.08] max-w-[820px]">
      {{ $active_copy['headline'] }}
    </h1>

    @if(!empty($active_copy['subtitle']))
      <p class="mt-5 text-[17px] sm:text-[19px] text-white/85 leading-relaxed max-w-[740px]">
        {{ $active_copy['subtitle'] }}
      </p>
    @endif

    <div class="mt-8 flex flex-wrap items-center gap-4">
      @if(!empty($ui['hero_compare_text']))
        <a href="{!! esc_url($hero_compare_url) !!}"
           class="inline-flex items-center gap-2 bg-white text-[#052b2e] hover:bg-[#f5f5f7] text-[14px] sm:text-[15px] font-semibold px-6 py-3 rounded-full transition-colors shadow-lg shadow-black/15">
          {{ $ui['hero_compare_text'] }}
        </a>
      @endif
      @if($cta_kontak_label !== '')
        <a href="{!! esc_url($cta_kontak_url) !!}"
           class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white border border-white/25 text-[14px] sm:text-[15px] font-medium px-6 py-3 rounded-full transition-colors">
          {{ $cta_kontak_label }}
        </a>
      @endif
    </div>
  </div>
</section>

{{-- 2. NAVIGASI KATEGORI & KATALOG UNIT --}}
<section class="bg-[#f5f5f7] py-16 sm:py-24">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    @if(!empty($all_cats) && !is_wp_error($all_cats))
    <div class="flex flex-wrap items-center gap-2.5 mb-12">
      <a href="{!! esc_url(home_url('/produk/')) !!}"
         class="px-5 py-2.5 rounded-full text-[13.5px] font-semibold transition-all border bg-white text-[#1d1d1f] border-black/10 hover:border-[#0f808a] hover:text-[#0f808a]">
        {{ $ui['all_products_label'] ?? 'Semua Produk' }}
      </a>
      @foreach($all_cats as $cat)
        @php
          if (stripos((string) $cat->slug, 'rebo') !== false) continue;
          $c_link = get_term_link($cat);
          $is_curr = ($term && isset($term->term_id) && (int) $term->term_id === (int) $cat->term_id);
        @endphp
        @if(!is_wp_error($c_link))
          <a href="{!! esc_url($c_link) !!}"
             class="px-5 py-2.5 rounded-full text-[13.5px] font-semibold transition-all border {{ $is_curr ? 'bg-[#0f808a] text-white border-[#0f808a]' : 'bg-white text-[#1d1d1f] border-black/10 hover:border-[#0f808a] hover:text-[#0f808a]' }}">
            {{ $decode_fn($cat->name) }}
          </a>
        @endif
      @endforeach
    </div>
    @endif

    @if(!empty($cat_drones))
      <x-reveal-group :step="90" :modulo="3" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7 lg:gap-8">
        @foreach($cat_drones as $drone)
          <x-drone-card :drone="$drone" />
        @endforeach
      </x-reveal-group>
    @else
      <div class="bg-white rounded-3xl p-12 text-center border border-black/[0.06]">
        <p class="text-[17px] text-[#515154] mb-4">{{ $ui['empty_text_prefix'] ?? 'Belum ada unit drone terdaftar dalam kategori' }} {{ $term_name }}.</p>
        <a href="{!! esc_url(home_url('/produk/')) !!}" class="inline-flex items-center gap-1.5 text-[14px] font-semibold text-[#0f808a] hover:underline group leading-none">
          <span>{{ $ui['empty_link_text'] ?? 'Lihat seluruh katalog armada FDS' }}</span>
          <svg class="w-[16px] h-[16px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
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
      @foreach((array) ($active_copy['paragraphs'] ?? []) as $para)
        <p>{{ $para }}</p>
      @endforeach
    </div>

    <div class="lg:col-span-4 space-y-6 border-t lg:border-t-0 lg:border-l border-black/[0.08] pt-8 lg:pt-0 lg:pl-10">
      <div>
        <h3 class="text-[18px] font-semibold text-[#1d1d1f] mb-2">{{ $ui['sidebar_title'] ?? 'Dukungan Manufaktur Lokal' }}</h3>
        @if(!empty($ui['sidebar_desc']))
          <p class="text-[14px] text-[#515154] leading-relaxed mb-4">
            {{ $ui['sidebar_desc'] }}
          </p>
        @endif
        @if(!empty($ui['sidebar_links']))
          <ul class="space-y-2.5 text-[14px] font-semibold text-[#0f808a]">
            @foreach((array) $ui['sidebar_links'] as $sl)
              @php
                $raw_sl_url = trim((string) ($sl['url'] ?? ''));
                $resolved_sl_url = ($raw_sl_url === '{cta_kontak_url}') ? $cta_kontak_url : $href($raw_sl_url);
              @endphp
              <li>
                <a href="{!! esc_url($resolved_sl_url) !!}" class="inline-flex items-center gap-1.5 hover:underline group leading-none">
                  <span>{{ $sl['label'] ?? '' }}</span>
                  <svg class="w-[15px] h-[15px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 6h12v12"/>
                    <path d="M6 18 18 6"/>
                  </svg>
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </div>
</section>

{{-- 4. FAQ KATEGORI (SSR & SYNCED DENGAN FAQPAGE JSON-LD) --}}
@if(!empty($cat_faqs))
  <x-service-faq
    :title="trim(($ui['faq_title_prefix'] ?? 'Pertanyaan Umum: Drone') . ' ' . $term_name)"
    :subtitle="$ui['faq_subtitle'] ?? 'Informasi sertifikasi, pilihan kapasitas muatan, pelatihan operator, dan kesiapan pengadaan B2B maupun instansi.'"
    :faqs="$cat_faqs"
    section-bg="gray"
  />
@endif

@endsection
