{{--
  Template Name: Katalog Produk
--}}

@extends('layouts.app')

@section('content')

@php
  $img_base = get_template_directory_uri() . '/public/images/';

  $pk = function_exists('\App\fds_get_produk_catalog_content')
      ? \App\fds_get_produk_catalog_content()
      : [];

  $href = static function ($url): string {
      return function_exists('\App\fds_produk_normalize_href')
          ? \App\fds_produk_normalize_href((string) $url)
          : home_url((string) $url);
  };

  $clean_cat_txt = static function ($val): string {
      $str = (string) $val;
      if (function_exists('fds_clean_string')) {
          $str = fds_clean_string($str);
      } else {
          $str = html_entity_decode(wp_specialchars_decode($str, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
      }
      return function_exists('fds_scrub_legacy_text') ? fds_scrub_legacy_text($str) : $str;
  };

  $tax_terms = get_terms([
      'taxonomy'   => 'kategori_drone',
      'hide_empty' => true,
      'orderby'    => 'term_id',
      'order'      => 'ASC',
  ]);
  if (is_wp_error($tax_terms) || empty($tax_terms)) {
      $tax_terms = [];
  } else {
      $tax_terms = array_values(array_filter($tax_terms, static function ($t) {
          return stripos((string) ($t->slug ?? ''), 'rebo') === false;
      }));
  }

  $catalog_map = [];
  foreach ($tax_terms as $term_item) {
      $tid   = (int) $term_item->term_id;
      $tslug = sanitize_title((string) $term_item->slug);
      $catalog_map[$tid] = [
          'term_id'     => $tid,
          'slug'        => $tslug,
          'anchor_slug' => $tslug !== '' ? $tslug : 'kategori-' . $tid,
          'name'        => $clean_cat_txt($term_item->name),
          'term_desc'   => $clean_cat_txt($term_item->description ?? ''),
          'drones'      => [],
          'is_ferto'    => false,
      ];
  }

  $all_drones = get_posts([
      'post_type'      => 'drone',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
      'orderby'        => 'menu_order title',
      'order'          => 'ASC',
  ]);

  $ferto_order = ['ferto-5l' => 1, 'ferto-10l' => 2, 'ferto-15l' => 3, 'ferto-22l' => 4, 'ferto-30l' => 5, 'ferto-50l' => 6];

  foreach ($all_drones as $d_post) {
      $d_slug = strtolower((string) $d_post->post_name);
      if (strpos($d_slug, 'rebo') !== false || strpos($d_slug, 'agrea') !== false || strpos($d_slug, 'ferto-20') !== false) {
          continue;
      }

      $terms   = get_the_terms($d_post->ID, 'kategori_drone');
      $gid     = null;
      $gname   = '';
      $gslug   = '';
      $gdesc   = '';

      if (!empty($terms) && !is_wp_error($terms)) {
          foreach ($terms as $t_item) {
              if (stripos((string) ($t_item->slug ?? ''), 'rebo') === false) {
                  $gid   = (int) $t_item->term_id;
                  $gname = $clean_cat_txt($t_item->name);
                  $gslug = sanitize_title((string) $t_item->slug);
                  $gdesc = $clean_cat_txt($t_item->description ?? '');
                  break;
              }
          }
      }

      if ($gid === null) {
          $meta_cat  = $clean_cat_txt(get_post_meta($d_post->ID, 'drone_kategori', true) ?: 'Varian Wahana Tambahan');
          $meta_slug = sanitize_title($meta_cat) ?: 'lainnya';
          foreach ($tax_terms as $term_item) {
              if (sanitize_title((string) $term_item->slug) === $meta_slug || strcasecmp($clean_cat_txt($term_item->name), $meta_cat) === 0) {
                  $gid   = (int) $term_item->term_id;
                  $gname = $clean_cat_txt($term_item->name);
                  $gslug = sanitize_title((string) $term_item->slug);
                  $gdesc = $clean_cat_txt($term_item->description ?? '');
                  break;
              }
          }
          if ($gid === null) {
              $gid   = 'meta_' . $meta_slug;
              $gname = $meta_cat;
              $gslug = $meta_slug;
          }
      }

      if (!isset($catalog_map[$gid])) {
          $catalog_map[$gid] = [
              'term_id'     => is_int($gid) ? $gid : 0,
              'slug'        => $gslug,
              'anchor_slug' => $gslug !== '' ? $gslug : 'lainnya',
              'name'        => $gname,
              'term_desc'   => $gdesc,
              'drones'      => [],
              'is_ferto'    => false,
          ];
      }

      $catalog_map[$gid]['drones'][] = $d_post;
      if (strpos($d_slug, 'ferto') === 0 || $gslug === 'agrikultur') {
          $catalog_map[$gid]['is_ferto'] = true;
      }
  }

  // Susun grup kategori non-kosong mengikuti urutan kanonik yang sama 1:1 dengan Mega Dropdown.
  $catalog_groups = array_values(array_filter($catalog_map, static fn($g) => !empty($g['drones'])));

  if (function_exists('\App\fds_sort_drone_category_groups')) {
      $catalog_groups = \App\fds_sort_drone_category_groups($catalog_groups);
  } else {
      $core_order = ['agrikultur' => 1, 'pemetaan-gis' => 2, 'pemetaan' => 2, 'kargo' => 3];
      usort($catalog_groups, static function ($a, $b) use ($core_order) {
          $ca = count($a['drones']);
          $cb = count($b['drones']);
          if ($ca !== $cb) {
              return $cb <=> $ca;
          }
          $oa = $core_order[$a['slug']] ?? 100;
          $ob = $core_order[$b['slug']] ?? 100;
          return $oa !== $ob ? ($oa <=> $ob) : strcasecmp((string) $a['name'], (string) $b['name']);
      });
  }

  foreach ($catalog_groups as $gi => $gdata) {
      $posts = $gdata['drones'];
      if (function_exists('fds_sort_drone_posts')) {
          $posts = fds_sort_drone_posts($posts);
      }

      $tid    = (int) ($gdata['term_id'] ?? 0);
      $slug_k = (string) ($gdata['slug'] ?? '');
      $sec_ov = ($tid > 0 && isset($pk['cat_sections'][$tid]) && is_array($pk['cat_sections'][$tid]))
          ? $pk['cat_sections'][$tid]
          : null;
      $legacy_map = [
          'agrikultur'   => ['title' => 'cat_agri_title', 'desc' => 'cat_agri_desc'],
          'pemetaan-gis' => ['title' => 'cat_pemetaan_title', 'desc' => 'cat_pemetaan_desc'],
          'pemetaan'     => ['title' => 'cat_pemetaan_title', 'desc' => 'cat_pemetaan_desc'],
          'kargo'        => ['title' => 'cat_kargo_title', 'desc' => 'cat_kargo_desc'],
      ];

      if ($sec_ov !== null) {
          $title = trim((string) ($sec_ov['title'] ?? '')) !== '' ? (string) $sec_ov['title'] : (string) $gdata['name'];
          $desc  = (string) ($sec_ov['desc'] ?? '');
      } elseif (isset($legacy_map[$slug_k])) {
          $lk    = $legacy_map[$slug_k];
          $title = trim((string) ($pk[$lk['title']] ?? '')) !== '' ? (string) $pk[$lk['title']] : (string) $gdata['name'];
          $desc  = array_key_exists($lk['desc'], $pk) ? (string) $pk[$lk['desc']] : (string) $gdata['term_desc'];
      } else {
          $title = (string) $gdata['name'];
          $desc  = (string) $gdata['term_desc'];
      }

      $catalog_groups[$gi]['drones'] = $posts;
      $catalog_groups[$gi]['title']  = $clean_cat_txt($title ?: $gdata['name']);
      $catalog_groups[$gi]['desc']   = $clean_cat_txt($desc);
  }

  $produk_faqs = function_exists('\App\fds_get_produk_faqs') ? \App\fds_get_produk_faqs() : (array) ($pk['faqs'] ?? []);
  $faq_bg      = (count($catalog_groups) % 2 === 0) ? 'gray' : 'white';
@endphp

{{-- 1. HERO KATALOG PRODUK --}}
<section class="fds-cinema-hero">
  <img src="{!! esc_url($img_base . 'site/hero-1.webp') !!}"
       alt="Armada Drone UAV Full Drone Solutions untuk agrikultur, pemetaan, dan kargo"
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
      <h1 class="fds-katalog-hero-title text-[42px] sm:text-[56px] lg:text-[66px] font-bold tracking-[-0.03em] text-white leading-[1.06]">
        {{ $pk['hero_title'] ?? 'Armada Drone UAV Indonesia untuk Agrikultur, Pemetaan & Kargo.' }}
      </h1>

      @if(!empty($pk['hero_desc']))
        <p class="mt-5 text-[16px] sm:text-[18.5px] text-white/85 leading-relaxed max-w-[680px]">
          {{ $pk['hero_desc'] }}
        </p>
      @endif

    </div>
  </div>
</section>

{{-- 2. SEKSI KATALOG DINAMIS PER KATEGORI TAKSONOMI DRONE --}}
@foreach($catalog_groups as $grp_idx => $grp)
  @php
    $sec_bg = ($grp_idx % 2 === 0) ? 'bg-[#f5f5f7]' : 'bg-white';
  @endphp
  <section id="kategori-{{ $grp['anchor_slug'] }}" class="{{ $sec_bg }} py-16 sm:py-20 scroll-mt-20">
    @if($grp['anchor_slug'] === 'pemetaan-gis')
      <span id="kategori-pemetaan" class="block h-0 scroll-mt-20" aria-hidden="true"></span>
    @endif
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      <div data-aos="fade-up" class="max-w-[780px] mb-10">
        <h2 class="text-[32px] sm:text-[42px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
          {{ $grp['title'] }}
        </h2>
        @if($grp['desc'] !== '')
          <p class="mt-3 text-[15.5px] sm:text-[16.5px] text-[#515154] leading-relaxed">
            {{ $grp['desc'] }}
          </p>
        @endif
      </div>

      <x-reveal-group :step="85" :modulo="3" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
        @foreach($grp['drones'] as $drone)
          <x-drone-card :drone="$drone" />
        @endforeach
      </x-reveal-group>
    </div>
  </section>
@endforeach

{{-- 3. FAQ KATALOG PRODUK (SSR NATIVE DETAILS) --}}
@if(!empty($produk_faqs))
  <x-service-faq
    :title="$pk['faq_title'] ?? 'Pertanyaan Umum Katalog Drone'"
    :subtitle="$pk['faq_subtitle'] ?? 'Ringkasan pertanyaan teknis dan kepatuhan pengadaan lini wahana PT Karya Solusi Angkasa.'"
    :faqs="$produk_faqs"
    :section-bg="$faq_bg"
  />
@endif

{{-- 7. CTA BAND B2B --}}
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
