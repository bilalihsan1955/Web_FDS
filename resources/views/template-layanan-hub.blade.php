{{--
  Template Name: Layanan Hub
--}}

@extends('layouts.app')

@section('content')

@php
  $hub        = function_exists('App\fds_get_layanan_hub_data') ? \App\fds_get_layanan_hub_data() : [];
  $sectors    = function_exists('App\fds_get_layanan_sectors') ? \App\fds_get_layanan_sectors() : [];
  $categories = function_exists('App\fds_get_layanan_categories') ? \App\fds_get_layanan_categories() : [];
  $ui         = function_exists('App\fds_get_layanan_ui_texts') ? \App\fds_get_layanan_ui_texts() : [];
  $gc         = function_exists('App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $wa_link    = $gc['wa_link'] ?? 'https://wa.me/628112748882';
  $q_def      = $hub['quote_defaults'] ?? [];
  $wa_prefill = $q_def['wa_prefill'] ?? 'Halo tim Enterprise FDS, kami ingin berkonsultasi mengenai layanan UAV dan pemetaan udara.';
  $wa_sep     = strpos($wa_link, '?') === false ? '?' : '&';
  $wa_cta_url = $wa_link . $wa_sep . 'text=' . rawurlencode($wa_prefill);
  $kontak_form_url     = home_url('/kontak/?layanan=layanan#hubungi-form');
  $raw_hub_cta_url     = trim((string) ($hub['cta_primary_url'] ?? ''));
  $hub_primary_cta_url = ($raw_hub_cta_url !== '' && $raw_hub_cta_url !== '#form-penawaran')
      ? $raw_hub_cta_url
      : $kontak_form_url;

  $cln = static function ($v): string {
      $s = (string) $v;
      $s = function_exists('fds_clean_string') ? fds_clean_string($s) : html_entity_decode(wp_specialchars_decode($s, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
      return function_exists('fds_scrub_legacy_text') ? fds_scrub_legacy_text($s) : $s;
  };
  $arrow_svg = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>';
  $chev_svg  = '<svg class="fds-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 9l-7 7-7-7"/></svg>';
  $img_base    = get_template_directory_uri() . '/public/images/';
  $intro_paras = $hub['intro_paragraphs'] ?? [];
  $lead_intro  = !empty($intro_paras[0]) ? $cln($intro_paras[0]) : $cln($hub['hero_summary'] ?? '');

  $hub_gallery = [
      ['url' => $img_base . 'sectors/pertambangan.webp', 'caption' => 'Akuisisi Drone LiDAR Sektor Pertambangan'],
      ['url' => $img_base . 'site/hero-2.webp', 'caption' => 'Pemetaan Koridor Fixed-Wing VTOL DELTAV'],
      ['url' => $img_base . 'sectors/perkebunan-pertanian.webp', 'caption' => 'Aplikasi & Pencitraan Perkebunan Presisi'],
      ['url' => $img_base . 'sectors/energi-infrastruktur.webp', 'caption' => 'Inspeksi Infrastruktur Energi & Utilitas'],
      ['url' => $img_base . 'site/workshop-deltav.webp', 'caption' => 'Perakitan & Kalibrasi Workshop Sleman'],
      ['url' => $img_base . 'site/about-team.webp', 'caption' => 'Tim Teknis & Pilot Bersertifikat FDS'],
  ];
@endphp

{{-- 1. HERO HUB LAYANAN --}}
<section class="fds-cinema-hero">
  <img src="{!! esc_url($img_base . 'site/hero-2.webp') !!}"
       alt="Solusi Layanan UAV & Pemetaan Geospasial Full Drone Solutions"
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
      <h1 class="fds-h1 fds-h1--soft" style="margin-bottom:20px;">
        {{ $cln($hub['h1'] ?? 'UAV Technology. Intelligent Data. Real Industrial Solutions.') }}
      </h1>
      <p class="fds-lead">
        {{ $cln($hub['hero_summary'] ?? ($hub['subtitle'] ?? '')) }}
      </p>
    </div>
  </div>
</section>

{{-- 2. EDITORIAL STORY SPLIT --}}
<section class="fds-sec fds-bg-white">
  <div class="fds-container">
    <div class="fds-story-split">
      <div data-aos="fade-up">
        <h2 class="fds-h2" style="margin-bottom:18px;">
          {{ $cln($ui['hub_intro_heading'] ?? 'Ekosistem Layanan Pemetaan, Sensor & UAV Nasional') }}
        </h2>
        <p class="fds-lead">{{ $lead_intro }}</p>
      </div>
      <figure class="fds-media-stage" data-aos="fade-up" data-aos-delay="90">
        <img src="{!! esc_url($img_base . 'site/workshop-deltav.webp') !!}" alt="Ekosistem Manufaktur dan Layanan UAV Full Drone Solutions" loading="lazy" decoding="async" width="1200" height="750">
      </figure>
    </div>
  </div>
</section>

{{-- 3. ENAM SEKTOR INDUSTRI STRATEGIS (OCEAN TEAL WAVE BAND) --}}
<section id="solusi-industri" class="fds-sec fds-bg-teal scroll-mt-24">
  <x-fds-wave type="fluid" uid="hub-sectors" />
  <div class="fds-container">
    <div class="fds-sec-head fds-sec-head--split" data-aos="fade-up">
      <div>
        <h2 class="fds-h2">{{ $cln($ui['hub_sectors_heading'] ?? 'Enam Sektor Industri Strategis') }}</h2>
        <p class="fds-lead" style="margin-top:14px;">{{ $cln($ui['hub_sectors_desc'] ?? 'Pilih sektor operasional Anda untuk mempelajari cakupan survei udara dan output teknis lengkap.') }}</p>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" data-reveal-group data-reveal-step="80" data-reveal-modulo="3">
      @foreach($sectors as $sector)
        <article class="relative rounded-2xl overflow-hidden aspect-[16/10] flex flex-col justify-end p-6 sm:p-7 bg-[#052b2e] group">
          <img src="{!! esc_url($sector['image']) !!}" alt="{{ $cln($sector['image_alt']) }}" loading="lazy" decoding="async" width="800" height="500"
               class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 z-0">
          <div class="absolute inset-0 pointer-events-none z-10" style="background:linear-gradient(180deg, rgba(5,43,46,0.06) 0%, rgba(5,43,46,0.65) 54%, rgba(3,30,32,0.96) 100%);"></div>
          <div class="relative z-20 text-white">
            <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;margin-bottom:6px;">
              <h3 class="fds-h3">
                <a href="{!! esc_url($sector['url']) !!}" class="fds-stretched-link text-white">{{ $cln($sector['card_title']) }}</a>
              </h3>
              <span class="fds-icon-cue" aria-hidden="true">{!! $arrow_svg !!}</span>
            </div>
            <p class="fds-caption" style="margin:0;color:rgba(255,255,255,0.88);">{{ $cln($sector['card_desc']) }}</p>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>

{{-- 4. KATEGORI LAYANAN SPESIALIS & PELATIHAN --}}
<section id="kategori-layanan" class="fds-sec fds-bg-light scroll-mt-24">
  <div class="fds-container">
    <div class="fds-sec-head fds-sec-head--split" data-aos="fade-up">
      <div><h2 class="fds-h2">{{ $cln($ui['hub_categories_heading'] ?? 'Foto Udara, LiDAR, Sensor Spektral & Pelatihan Tim') }}</h2></div>
      <div><p class="fds-lead">{{ $cln($ui['hub_categories_desc'] ?? 'Kapabilitas akuisisi data geospasial dan peningkatan kapasitas operator lintas sektor.') }}</p></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-9" data-reveal-group data-reveal-step="85" data-reveal-modulo="3">
      @foreach($categories as $cat)
        <article class="pt-5 border-t-2 border-[#0f808a] relative group">
          <div style="display:flex;align-items:baseline;justify-content:space-between;gap:10px;margin-bottom:8px;">
            <h3 class="fds-h3">
              <a href="{!! esc_url($cat['url']) !!}" class="fds-stretched-link" style="color:var(--fds-ink-primary);">{{ $cln($cat['short_title']) }}</a>
            </h3>
            <span class="fds-icon-cue" aria-hidden="true">{!! $arrow_svg !!}</span>
          </div>
          <p class="fds-caption" style="margin:0;">{{ $cln($cat['hero_summary']) }}</p>
        </article>
      @endforeach
    </div>
  </div>
</section>

{{-- 5. ALUR KERJA & MATRIKS SENSOR --}}
<section class="fds-sec fds-bg-white">
  <div class="fds-container fds-sticky-split">
    <div class="fds-sticky-col">
      <div data-aos="fade-up">
        <h2 class="fds-h2" style="margin-bottom:14px;">{{ $cln($hub['workflow']['title'] ?? 'Alur Kerja Layanan Lapangan hingga Dashboard Operasional') }}</h2>
        <p class="fds-lead" style="margin-bottom:24px;">{{ $cln($hub['workflow']['desc'] ?? '') }}</p>
        @if(!empty($hub['method_comparison']['columns']) && !empty($hub['method_comparison']['rows']))
          @php $mc = $hub['method_comparison']; @endphp
          <details class="fds-quiet-details" style="margin-top:0;">
            <summary class="fds-quiet-summary">Lihat matriks perbandingan metode sensor {!! $chev_svg !!}</summary>
            <div class="fds-quiet-body">
              <div class="fds-table-wrap">
                <table class="fds-table">
                  <thead>
                    <tr>
                      @foreach($mc['columns'] as $colTitle)<th scope="col">{{ $cln($colTitle) }}</th>@endforeach
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($mc['rows'] as $row)
                      <tr>
                        @foreach($row as $cIdx => $cell)
                          <td @if($cIdx === 0) style="font-weight:600;color:var(--fds-ink-primary);" @endif>{{ $cln($cell) }}</td>
                        @endforeach
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </details>
        @endif
      </div>
    </div>
    <div class="fds-faq-list" data-reveal-group data-reveal-step="60">
      @foreach($hub['workflow']['steps'] ?? [] as $step)
        <details class="fds-faq-row">
          <summary class="fds-faq-summary">
            <span>
              <strong class="fds-num" style="color:var(--color-teal-800);margin-right:12px;">Tahap {{ $cln($step['step'] ?? '') }}</strong>
              {{ $cln($step['title'] ?? '') }}
            </span>
            {!! $chev_svg !!}
          </summary>
          <div class="fds-faq-body fds-prose">
            <p class="fds-body" style="margin:0;">{{ $cln($step['desc'] ?? '') }}</p>
          </div>
        </details>
      @endforeach
    </div>
  </div>
</section>

@php
  $hub_galeri_cls = !empty($hub['faqs']) ? 'fds-bg-light' : 'fds-bg-white';
@endphp

{{-- 6. GALERI DOKUMENTASI LAPANGAN --}}
<section id="galeri" class="fds-sec {{ $hub_galeri_cls }} scroll-mt-24">
  <div class="fds-container">
    <div class="fds-sec-head" data-aos="fade-up">
      <h2 class="fds-h2">Dokumentasi Operasi Lintas Sektor.</h2>
    </div>
    <x-fds-gallery :items="$hub_gallery" variant="bento" />
  </div>
</section>

{{-- 7. FAQ HUB LAYANAN --}}
<x-service-faq
  :title="$ui['hub_faq_title'] ?? 'Pertanyaan Umum Layanan FDS'"
  :subtitle="$ui['hub_faq_subtitle'] ?? 'Informasi awal terkait ruang lingkup proyek, pemilihan metode survei udara, integrasi data spasial GIS, dan prosedur konsultasi.'"
  :faqs="$hub['faqs'] ?? []"
  sectionBg="white"
/>

@endsection
