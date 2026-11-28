@extends('layouts.app')

@section('content')

@php
  $img_base = get_template_directory_uri() . '/public/images/';

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
  $cat_rules = ($term && !empty($term->term_id) && function_exists('\App\fds_get_kategori_product_rules'))
    ? \App\fds_get_kategori_product_rules((int) $term->term_id)
    : ['kind' => 'uav', 'is_payload' => false, 'hide_bandingkan' => false, 'hide_home' => false];
  if (function_exists('fds_scrub_legacy_text')) {
    $is_ferto_cat = ($term_slug === 'agrikultur');
    $term_name = fds_scrub_legacy_text($term_name, $is_ferto_cat, $is_ferto_cat);
  }

  $active_copy = function_exists('\App\fds_get_kategori_drone_catalog_content')
    ? \App\fds_get_kategori_drone_catalog_content($term_slug, $term)
    : [
        'headline'         => 'Drone ' . $term_name . '.',
        'subtitle'         => 'Katalog resmi pesawat nirawak industrial produksi PT Karya Solusi Angkasa (sistem manajemen mutu perusahaan ISO 9001:2015).',
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
        'sidebar_desc'       => 'Seluruh unit dirakit di workshop Sleman, D.I. Yogyakarta oleh PT Karya Solusi Angkasa yang menerapkan sistem manajemen mutu perusahaan ISO 9001:2015, dengan sertifikasi produk TKDN + BMP 60,74% untuk lini pertanian FERTO serta SNI 9199:2023 pada FERTO 15 dan FERTO 22.',
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
  if (!empty($all_cats) && !is_wp_error($all_cats) && function_exists('App\fds_sort_kategori_terms')) {
    $all_cats = \App\fds_sort_kategori_terms($all_cats);
  }

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
  $empty_prefix     = !empty($cat_rules['is_payload']) && (($ui['empty_text_prefix'] ?? '') === '' || ($ui['empty_text_prefix'] ?? '') === 'Belum ada unit drone terdaftar dalam kategori')
      ? 'Belum ada produk terdaftar dalam kategori'
      : ($ui['empty_text_prefix'] ?? 'Belum ada unit drone terdaftar dalam kategori');
  $faq_prefix       = !empty($cat_rules['is_payload']) && (($ui['faq_title_prefix'] ?? '') === '' || ($ui['faq_title_prefix'] ?? '') === 'Pertanyaan Umum: Drone')
      ? 'Pertanyaan Umum:'
      : ($ui['faq_title_prefix'] ?? 'Pertanyaan Umum: Drone');
  $cta_kontak_label = $active_copy['cta_kontak_label'] ?: (($term_slug === 'agrikultur') ? 'Konsultasi Pengadaan & TKDN' : 'Konsultasi Pengadaan & Demo Unit');
  $cta_kontak_url   = $href((string) ($active_copy['cta_kontak_url'] ?: ('/kontak/?layanan=' . urlencode('kategori-' . $term_slug) . '#hubungi-form')));
  $hero_compare_url = $href((string) ($ui['hero_compare_url'] ?? '/bandingkan/'));
  $hero_compare_lbl = (string) ($ui['hero_compare_text'] ?? 'Bandingkan Spesifikasi Drone');
  if (!empty($cat_rules['is_payload']) && empty($cat_rules['hide_bandingkan'])) {
    if (!empty($cat_drones[0]->post_name)) {
      $hero_compare_url = home_url('/bandingkan/?d1=' . urlencode((string) $cat_drones[0]->post_name));
    }
    $hero_compare_lbl = 'Bandingkan ' . $term_name;
  }
  $hero_img = [
    'agrikultur'   => 'sectors/perkebunan-pertanian.webp',
    'pemetaan-gis' => 'sectors/perencanaan.webp',
    'kargo'        => 'site/hero-3.webp',
  ][$term_slug] ?? 'site/hero-1.webp';
@endphp

{{-- 1. HERO KATEGORI DRONE (Foto + Brand Teal) --}}
<section class="fds-cinema-hero">
  <img src="{!! esc_url($img_base . $hero_img) !!}"
       alt="{{ $term_name }} — {{ !empty($cat_rules['is_payload']) ? 'Sensor & Payload Pabrikan Mitra Terintegrasi FDS' : 'drone UAV Full Drone Solutions' }}"
       class="fds-cinema-bg"
       width="1200"
       height="750"
       loading="eager"
       fetchpriority="high"
       decoding="sync"
       data-no-lazy="1">
  <div class="fds-cinema-scrim"></div>
  <div class="fds-container">
    <div class="fds-cinema-content fds-hero-entrance fds-hero-delay-1">
    <h1 class="text-[42px] sm:text-[56px] lg:text-[66px] font-bold tracking-[-0.03em] text-white leading-[1.06] max-w-[820px]">
      {{ $active_copy['headline'] }}
    </h1>

    @if(!empty($active_copy['subtitle']))
      <p class="mt-5 text-[17px] sm:text-[19px] text-white/85 leading-relaxed max-w-[740px]">
        {{ $active_copy['subtitle'] }}
      </p>
    @endif

    <div class="mt-8 flex flex-wrap items-center gap-4">
      @if(empty($cat_rules['hide_bandingkan']) && $hero_compare_lbl !== '')
        <a href="{!! esc_url($hero_compare_url) !!}"
           class="group inline-flex items-center justify-center gap-2 min-h-[44px] bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#052b2e] hover:text-[#0a555c] text-[14px] sm:text-[15px] font-semibold px-6 py-3 rounded-full transition-all duration-150 shadow-lg shadow-black/15 leading-none">
          <span>{{ $hero_compare_lbl }}</span>
          <svg class="w-[17px] h-[17px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
      @endif
      @if($cta_kontak_label !== '')
        <a href="{!! esc_url($cta_kontak_url) !!}"
           class="group inline-flex items-center justify-center gap-2 min-h-[44px] {{ !empty($cat_rules['hide_bandingkan']) ? 'bg-white hover:bg-[#f5f5f7] text-[#052b2e] hover:text-[#0a555c] font-semibold shadow-lg shadow-black/15' : 'bg-white/15 hover:bg-white/25 text-white backdrop-blur-md border border-white/25 font-medium' }} active:scale-[0.97] text-[14px] sm:text-[15px] px-6 py-3 rounded-full transition-all duration-150 leading-none">
          <span>{{ $cta_kontak_label }}</span>
          <svg class="w-[17px] h-[17px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
      @endif
    </div>
    </div>
  </div>
</section>

{{-- 2. NAVIGASI KATEGORI & KATALOG UNIT --}}
@php
  $has_cat_faqs = !empty($cat_faqs);
  $cat_sec2_bg  = $has_cat_faqs ? 'bg-white' : 'bg-[#f5f5f7]';
  $cat_sec3_bg  = $has_cat_faqs ? 'bg-[#f5f5f7]' : 'bg-white';
  $cat_tab_idle = $has_cat_faqs
      ? 'bg-[#f5f5f7] text-[#1d1d1f] border-black/10 hover:border-[#0f808a] hover:text-[#0f808a]'
      : 'bg-white text-[#1d1d1f] border-black/10 hover:border-[#0f808a] hover:text-[#0f808a]';
@endphp
<section class="{{ $cat_sec2_bg }} py-16 sm:py-24">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    @if(!empty($all_cats) && !is_wp_error($all_cats))
    <div class="flex flex-wrap items-center gap-2.5 mb-12">
      <a href="{!! esc_url(home_url('/produk/')) !!}"
         class="inline-flex items-center min-h-[44px] px-5 py-2.5 rounded-full text-[13.5px] font-semibold transition-all border {{ $cat_tab_idle }}">
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
             class="inline-flex items-center min-h-[44px] px-5 py-2.5 rounded-full text-[13.5px] font-semibold transition-all border {{ $is_curr ? 'bg-[#0f808a] text-white border-[#0f808a]' : $cat_tab_idle }}">
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
      <div class="{{ $has_cat_faqs ? 'bg-[#f5f5f7]' : 'bg-white' }} rounded-3xl p-12 text-center border border-black/[0.06]">
        <p class="text-[17px] text-[#515154] mb-4">{{ $empty_prefix }} {{ $term_name }}.</p>
        <a href="{!! esc_url(home_url('/produk/')) !!}" class="inline-flex items-center justify-center gap-2 min-h-[44px] bg-[#0f808a] hover:bg-[#0a555c] active:scale-[0.97] text-white text-[14px] font-semibold px-6 py-2.5 rounded-full transition-all duration-150 shadow-sm group leading-none">
          <span>{{ $ui['empty_link_text'] ?? 'Lihat seluruh katalog armada FDS' }}</span>
          <svg class="w-[17px] h-[17px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
      </div>
    @endif

  </div>
</section>

{{-- 3. PANDUAN TEKNIS & INTERNAL LINKS KATEGORI --}}
<section class="{{ $cat_sec3_bg }} py-16 sm:py-24">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
    <div data-aos="fade-up" class="lg:col-span-8 fds-drone-guide">
      <h2>{{ $active_copy['guide_title'] }}</h2>
      @foreach((array) ($active_copy['paragraphs'] ?? []) as $para)
        <p>{{ $para }}</p>
      @endforeach
    </div>

    @php
      $sb_title = (string) ($ui['sidebar_title'] ?? 'Dukungan Manufaktur Lokal');
      $sb_desc  = (string) ($ui['sidebar_desc'] ?? '');
      $sb_links = (array) ($ui['sidebar_links'] ?? []);
      if (!empty($cat_rules['is_payload'])) {
        if ($sb_title === '' || stripos($sb_title, 'Manufaktur') !== false) {
          $sb_title = 'Dukungan Integrasi & Pengadaan Resmi';
        }
        if ($sb_desc === '' || stripos($sb_desc, 'dirakit di workshop') !== false) {
          $sb_desc = 'Perangkat sensor dan kamera berasal dari pabrikan spesialis mitra resmi serta didukung penuh oleh tim engineering PT Karya Solusi Angkasa untuk integrasi wahana FDS, uji kalibrasi, dan dukungan purna jual.';
        }
      }
    @endphp
    <div data-aos="fade-up" data-aos-delay="100" class="lg:col-span-4 space-y-6 border-t lg:border-t-0 lg:border-l border-black/[0.08] pt-8 lg:pt-0 lg:pl-10">
      <div>
        <h3 class="text-[18px] font-semibold text-[#1d1d1f] mb-2">{{ $sb_title }}</h3>
        @if($sb_desc !== '')
          <p class="text-[14px] text-[#515154] leading-relaxed mb-4">
            {{ $sb_desc }}
          </p>
        @endif
        @if(!empty($sb_links))
          <ul class="space-y-2.5 text-[14px] font-semibold text-[#0f808a]">
            @foreach($sb_links as $sl)
              @php
                $raw_sl_url = trim((string) ($sl['url'] ?? ''));
                $resolved_sl_url = ($raw_sl_url === '{cta_kontak_url}') ? $cta_kontak_url : $href($raw_sl_url);
                $sl_lbl = (string) ($sl['label'] ?? '');
                if (!empty($cat_rules['is_payload'])) {
                  if ($sl_lbl === 'Lihat Katalog Lengkap UAV FDS') {
                    $sl_lbl = 'Lihat Seluruh Katalog Produk';
                  } elseif ($sl_lbl === 'Profil Pabrik & Sertifikasi FDS') {
                    $sl_lbl = 'Profil Integrator & Kemampuan FDS';
                  }
                }
              @endphp
              <li>
                <a href="{!! esc_url($resolved_sl_url) !!}" class="inline-flex items-center gap-1.5 hover:underline group leading-none">
                  <span>{{ $sl_lbl }}</span>
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
    :title="trim($faq_prefix . ' ' . $term_name)"
    :subtitle="$ui['faq_subtitle'] ?? 'Informasi sertifikasi, pilihan kapasitas muatan, pelatihan operator, dan kesiapan pengadaan B2B maupun instansi.'"
    :faqs="$cat_faqs"
    section-bg="white"
  />
@endif

@endsection
