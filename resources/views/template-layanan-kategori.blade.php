{{--
  Template Name: Layanan Kategori
--}}

@extends('layouts.app')

@section('content')

@php
  $ctx        = function_exists('App\fds_get_current_layanan_context') ? \App\fds_get_current_layanan_context() : null;
  $categories = function_exists('App\fds_get_layanan_categories') ? \App\fds_get_layanan_categories() : [];
  $sectors    = function_exists('App\fds_get_layanan_sectors') ? \App\fds_get_layanan_sectors() : [];
  $cat        = ($ctx && ($ctx['kind'] ?? '') === 'kategori' && !empty($ctx['data']))
    ? $ctx['data']
    : ($categories['jasa-foto-udara-lidar'] ?? reset($categories) ?: []);
  $ui         = function_exists('App\fds_get_layanan_ui_texts') ? \App\fds_get_layanan_ui_texts() : [];
  $gc         = function_exists('App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $wa_link    = $gc['wa_link'] ?? 'https://wa.me/628112748882';
  $p_join     = static function (string $prefix, string $suffix): string {
      $p = trim($prefix);
      return $p !== '' ? ($p . ' ' . $suffix) : $suffix;
  };
  $cln = static function ($v): string {
      $s = (string) $v;
      $s = function_exists('fds_clean_string') ? fds_clean_string($s) : html_entity_decode(wp_specialchars_decode($s, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
      return function_exists('fds_scrub_legacy_text') ? fds_scrub_legacy_text($s) : $s;
  };
  $arrow_svg = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>';
  $chev_svg  = '<svg class="fds-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 9l-7 7-7-7"/></svg>';
@endphp

@if(!empty($cat))
@php
  $cat_slug        = (string) ($cat['slug'] ?? 'jasa-foto-udara-lidar');
  $q_def           = $cat['quote_defaults'] ?? [];
  $wa_prefill      = $q_def['wa_prefill'] ?? ('Halo tim FDS, kami ingin berkonsultasi mengenai layanan ' . ($cat['short_title'] ?? 'Layanan FDS') . '.');
  $wa_sep          = strpos($wa_link, '?') === false ? '?' : '&';
  $wa_cta_url      = $wa_link . $wa_sep . 'text=' . rawurlencode($wa_prefill);
  $kontak_form_url = home_url('/kontak/?layanan=' . rawurlencode($cat_slug) . '#hubungi-form');
  $vis             = function_exists('App\fds_get_category_visuals') ? \App\fds_get_category_visuals($cat_slug, $cat) : [];
  $gallery_items   = $vis['gallery'] ?? [];

  $resolved_reading_links = [];
  foreach (($cat['reading_links'] ?? []) as $rl) {
      $url = !empty($rl['url'])
          ? (string) $rl['url']
          : (!empty($rl['slug']) && function_exists('App\fds_resolve_optional_post_url')
              ? \App\fds_resolve_optional_post_url((string) $rl['slug'])
              : '');
      if ($url !== '' && !empty($rl['label'])) {
          $resolved_reading_links[] = ['label' => $cln($rl['label']), 'url' => $url];
      }
  }
  $intro_paras  = $cat['intro_paragraphs'] ?? [];
  $lead_intro   = !empty($intro_paras[0]) ? $cln($intro_paras[0]) : $cln($cat['hero_summary'] ?? '');
  $extra_intros = array_slice($intro_paras, 1);
@endphp

{{-- 1. HERO KATEGORI --}}
<section class="fds-cinema-hero">
  <img src="{!! esc_url($vis['hero_img'] ?? '') !!}"
       alt="{{ $cln($cat['short_title'] ?? 'Layanan FDS') }} — Full Drone Solutions"
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
        {{ $cln($cat['h1'] ?? ($cat['short_title'] ?? '')) }}
      </h1>
      @if(!empty($cat['hero_summary']))
        <p class="fds-lead">{{ $cln($cat['hero_summary']) }}</p>
      @endif
    </div>
  </div>
</section>

{{-- 2. STORY SPLIT --}}
<section class="fds-sec fds-bg-white">
  <div class="fds-container">
    <div class="fds-story-split">
      <div data-aos="fade-up">
        <h2 class="fds-h2" style="margin-bottom:18px;">
          {{ $cln($p_join((string) ($ui['kat_intro_heading_prefix'] ?? 'Tentang'), (string) ($cat['short_title'] ?? ''))) }}
        </h2>
        <p class="fds-lead">{{ $lead_intro }}</p>
      </div>
      <figure class="fds-media-stage" data-aos="fade-up" data-aos-delay="90">
        <img src="{!! esc_url($vis['story_img'] ?? '') !!}" alt="{{ $cln($cat['short_title'] ?? 'Layanan') }} — Operasi Lapangan FDS" loading="lazy" decoding="async" width="1200" height="750">
      </figure>
    </div>
  </div>
</section>

{{-- 3. KAPABILITAS UTAMA --}}
@if(!empty($cat['capabilities']))
<section id="kapabilitas" class="fds-sec fds-bg-light scroll-mt-24">
  <div class="fds-container">
    <div class="fds-sec-intro" data-aos="fade-up">
      <div>
        <p class="fds-intro-label">{{ $cln($ui['kat_cap_eyebrow'] ?? 'Ruang Lingkup') }}</p>
        <h2 class="fds-h2">{{ $cln($ui['kat_cap_heading'] ?? 'Kapabilitas & Ruang Lingkup Layanan') }}</h2>
      </div>
    </div>
    <div class="fds-scope-grid" data-reveal-group data-reveal-step="70" data-reveal-modulo="2">
      @foreach($cat['capabilities'] as $cap)
        <article class="fds-hairline-row">
          <h3 class="fds-h3">{{ $cln($cap['name'] ?? '') }}</h3>
          <p class="fds-caption" style="margin:6px 0 0;">{{ $cln($cap['desc'] ?? '') }}</p>
        </article>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 4. OUTPUT & ALUR KERJA (QUIET TEAL WAVE + PROCESS RAIL) --}}
@if(!empty($cat['outputs']) || !empty($cat['workflow_steps']))
<section class="fds-sec fds-bg-teal">
  <x-fds-wave type="quiet" :uid="'kat-wf-' . $cat_slug" />
  <div class="fds-container">
    <div class="fds-sec-intro" data-aos="fade-up">
      <div>
        <p class="fds-intro-label">{{ $cln($ui['kat_workflow_eyebrow'] ?? 'Standar Pelaksanaan') }}</p>
        <h2 class="fds-h2">{{ $cln($ui['kat_outputs_heading'] ?? 'Output & Deliverables Utama') }}</h2>
      </div>
    </div>

    @if(!empty($cat['outputs']))
      <ul data-aos="fade-up" data-aos-delay="70" style="margin:0;padding-left:20px;display:grid;gap:8px;">
        @foreach($cat['outputs'] as $out)
          <li class="fds-body">{{ $cln($out) }}</li>
        @endforeach
      </ul>
    @endif

    @if(!empty($cat['workflow_steps']))
      <h3 data-aos="fade-up" class="fds-h3" style="margin:40px 0 20px;">
        {{ $cln($p_join((string) ($ui['kat_workflow_heading_prefix'] ?? 'Alur Kerja & Kontrol Kualitas'), (string) ($cat['short_title'] ?? ''))) }}
      </h3>
      <div class="fds-process-block">
        <div class="fds-process-rail" data-reveal-group data-reveal-step="80">
          @foreach($cat['workflow_steps'] as $ws)
            <div class="fds-process-step">
              <p class="fds-process-step-label">Tahap {{ $cln($ws['step'] ?? '') }}</p>
              <p class="fds-process-step-title">{{ $cln($ws['title'] ?? '') }}</p>
              <p class="fds-body">{{ $cln($ws['desc'] ?? '') }}</p>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    @if(!empty($vis['cutout']))
      <div class="fds-hardware-cutout" data-aos="zoom-in" style="margin-top:40px;">
        <img src="{!! esc_url($vis['cutout']) !!}" alt="Armada UAV {{ $cln($cat['short_title'] ?? 'FDS') }}" loading="lazy" decoding="async" width="1148" height="559">
      </div>
    @endif
  </div>
</section>
@endif

{{-- 5. METODE & BIAYA --}}
@if(!empty($cat['method_comparison']['columns']) || !empty($cat['cost_framework']))
@php
  $cf = $cat['cost_framework'] ?? [];
  $mc = $cat['method_comparison'] ?? [];
@endphp
<section class="fds-sec fds-bg-light">
  <div class="fds-container">
    <div class="fds-sec-intro" data-aos="fade-up">
      <div>
        @if(!empty($cf))
          <p class="fds-intro-label">{{ $cln($cf['eyebrow'] ?? 'Perencanaan Anggaran') }}</p>
          <h2 class="fds-h2">{{ $cln($cf['title'] ?? 'Faktor Penentu Biaya Proyek') }}</h2>
        @elseif(!empty($mc['title']))
          <h2 class="fds-h2">{{ $cln($mc['title']) }}</h2>
        @endif
      </div>
      @if(!empty($cf))
        <div>
          @if(!empty($cf['intro']))
            <p class="fds-body" style="margin:0 0 16px;">{{ $cln($cf['intro']) }}</p>
          @endif
          <a href="{!! esc_url($kontak_form_url) !!}" class="fds-btn fds-btn--primary">
            <span>{{ $cln($ui['kat_cost_cta_text'] ?? 'Kirim rincian kebutuhan untuk estimasi penawaran') }}</span>
            {!! $arrow_svg !!}
          </a>
        </div>
      @endif
    </div>

    @if(!empty($cf['factors']))
      <div class="fds-definition-list" data-reveal-group data-reveal-step="70">
        @foreach($cf['factors'] as $fac)
          <div class="fds-definition-row">
            <h3>{{ $cln($fac['name'] ?? '') }}</h3>
            <p class="fds-body">{{ $cln($fac['desc'] ?? '') }}</p>
          </div>
        @endforeach
      </div>
    @endif

  </div>
</section>
@endif

{{-- 6. PENERAPAN DI SEKTOR INDUSTRI (SEQUENTIAL DYNAMIC NUMBERING PRESERVED) --}}
@if(!empty($cat['sector_links']))
<section class="fds-sec fds-bg-white">
  <div class="fds-container">
    <div class="fds-sec-head fds-sec-head--split" data-aos="fade-up">
      <div><h2 class="fds-h2">{{ $cln($ui['kat_sectors_heading'] ?? 'Penerapan di Sektor Industri') }}</h2></div>
      <div>
        <a href="{!! esc_url(home_url('/layanan/')) !!}" class="fds-btn fds-btn--primary">
          <span>Semua Layanan FDS</span>
          {!! $arrow_svg !!}
        </a>
      </div>
    </div>
    <div class="fds-hairline-list" data-reveal-group data-reveal-step="70">
      @php $relSecCounter = 0; @endphp
      @foreach($cat['sector_links'] as $sec_slug)
        @if(!empty($sectors[$sec_slug]))
          @php
            $rel_sec = $sectors[$sec_slug];
            $relSecCounter++;
            $rel_sec_num = str_pad((string) $relSecCounter, 2, '0', STR_PAD_LEFT);
          @endphp
          <article class="fds-hairline-row fds-hairline-row--split fds-hairline-row--interactive">
            <div style="display:flex;align-items:baseline;gap:14px;">
              <span class="fds-num" style="color:var(--color-teal-800);font-weight:600;">{{ $rel_sec_num }}</span>
              <h3 class="fds-h3" style="margin:0;">
                <a href="{!! esc_url($rel_sec['url']) !!}" class="fds-stretched-link" style="color:var(--fds-ink-primary);">{{ $cln($rel_sec['card_title']) }}</a>
              </h3>
            </div>
            <span class="fds-icon-cue" aria-hidden="true">{!! $arrow_svg !!}</span>
            <p class="fds-caption" style="margin:0;">{{ $cln($rel_sec['card_desc']) }}</p>
          </article>
        @endif
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 7. GALERI DOKUMENTASI LAPANGAN --}}
@if(!empty($gallery_items))
@php
  $kat_galeri_cls = !empty($cat['faqs']) ? 'fds-bg-light' : 'fds-bg-white';
@endphp
<section id="galeri" class="fds-sec {{ $kat_galeri_cls }} scroll-mt-24">
  <div class="fds-container">
    <h2 data-aos="fade-up" class="fds-h2" style="margin-bottom:28px;">Dokumentasi Lapangan &amp; Operasi Teknis</h2>
    <x-fds-gallery :items="$gallery_items" variant="bento" />
  </div>
</section>
@endif

{{-- 8. FAQ KATEGORI --}}
<x-service-faq
  :title="$p_join((string) ($ui['kat_faq_prefix'] ?? 'Pertanyaan Umum:'), (string) ($cat['short_title'] ?? 'Layanan'))"
  :subtitle="$ui['kat_faq_subtitle'] ?? 'Penjelasan teknis, format file keluaran, dan informasi yang dapat Anda konsultasikan bersama tim FDS.'"
  :faqs="$cat['faqs'] ?? []"
  sectionBg="white"
/>

@endif

@endsection
