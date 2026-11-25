{{--
  Template Name: Layanan Sektor
--}}

@extends('layouts.app')

@section('content')

@php
  $ctx     = function_exists('App\fds_get_current_layanan_context') ? \App\fds_get_current_layanan_context() : null;
  $sectors = function_exists('App\fds_get_layanan_sectors') ? \App\fds_get_layanan_sectors() : [];
  $sector  = ($ctx && ($ctx['kind'] ?? '') === 'sektor' && !empty($ctx['data']))
    ? $ctx['data']
    : ($sectors['pertambangan'] ?? reset($sectors) ?: []);
  $ui      = function_exists('App\fds_get_layanan_ui_texts') ? \App\fds_get_layanan_ui_texts() : [];
  $gc      = function_exists('App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $wa_link = $gc['wa_link'] ?? 'https://wa.me/628112748882';
  $p_join  = static function (string $prefix, string $suffix): string {
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

@if(!empty($sector))
@php
  $sec_slug        = (string) ($sector['slug'] ?? 'pertambangan');
  $q_def           = $sector['quote_defaults'] ?? [];
  $wa_prefill      = $q_def['wa_prefill'] ?? ('Halo tim FDS, kami ingin berkonsultasi mengenai layanan sektor ' . ($sector['card_title'] ?? 'Industri') . '.');
  $wa_sep          = strpos($wa_link, '?') === false ? '?' : '&';
  $wa_cta_url      = $wa_link . $wa_sep . 'text=' . rawurlencode($wa_prefill);
  $kontak_form_url = home_url('/kontak/?layanan=' . rawurlencode($sec_slug) . '#hubungi-form');
  $vis             = function_exists('App\fds_get_sector_visuals') ? \App\fds_get_sector_visuals($sec_slug, $sector) : [];
  $gallery_items   = $vis['gallery'] ?? [];

  $resolved_reading_links = [];
  foreach (($sector['reading_links'] ?? []) as $rl) {
      $url = !empty($rl['url'])
          ? (string) $rl['url']
          : (!empty($rl['slug']) && function_exists('App\fds_resolve_optional_post_url')
              ? \App\fds_resolve_optional_post_url((string) $rl['slug'])
              : '');
      if ($url !== '' && !empty($rl['label'])) {
          $resolved_reading_links[] = ['label' => $cln($rl['label']), 'url' => $url];
      }
  }

  $intro_paras     = $sector['intro_paragraphs'] ?? [];
  $lead_intro      = !empty($intro_paras[0]) ? $cln($intro_paras[0]) : $cln($sector['hero_summary'] ?? '');
  $extra_intros    = array_slice($intro_paras, 1);

  $solutions       = $sector['solutions'] ?? [];
  $featured_sols   = [];
  $remaining_sols  = [];
  foreach ($solutions as $sol) {
      $raw_sol_url  = trim((string) ($sol['link_url'] ?? ''));
      $sol_url      = ($raw_sol_url === '#form-penawaran') ? $kontak_form_url : $raw_sol_url;
      $sol_name     = $cln($sol['name'] ?? '');
      $sol_desc     = $cln($sol['desc'] ?? '');
      $entry        = ['name' => $sol_name, 'desc' => $sol_desc, 'url' => $sol_url];
      if ($sol_url !== '' && count($featured_sols) < 3) {
          $featured_sols[] = $entry;
      } else {
          $remaining_sols[] = $entry;
      }
  }
  if (count($featured_sols) === 0 && count($remaining_sols) >= 3) {
      $featured_sols  = array_slice($remaining_sols, 0, 3);
      $remaining_sols = array_slice($remaining_sols, 3);
  }
  $sol_photo_map = $vis['solution_photos'] ?? [];
@endphp

{{-- 1. HERO CINEMA SEKTOR --}}
<section class="fds-cinema-hero">
  <img src="{!! esc_url($vis['hero_img'] ?? ($sector['image'] ?? '')) !!}"
       alt="{{ $cln($vis['hero_alt'] ?? ($sector['image_alt'] ?? ($sector['card_title'] ?? 'Layanan FDS'))) }}"
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
        {{ $cln($sector['h1'] ?? ($sector['card_title'] ?? '')) }}
      </h1>
      @if(!empty($sector['hero_summary']))
        <p class="fds-lead">
          {{ $cln($sector['hero_summary']) }}
        </p>
      @endif
    </div>
  </div>
</section>

{{-- 2. PROOF METRICS + STORY SPLIT WITH FIELD PHOTOGRAPHY --}}
<section class="fds-sec fds-bg-white">
  <div class="fds-container">
    <div class="fds-story-split">
      <div data-aos="fade-up">
        <h2 class="fds-h2" style="margin-bottom:18px;">
          {{ $cln($sector['subtitle'] ?? ($sector['card_title'] ?? '')) }}
        </h2>
        <p class="fds-lead">
          {{ $lead_intro }}
        </p>
      </div>
      <figure class="fds-media-stage" data-aos="fade-up" data-aos-delay="90">
        <img src="{!! esc_url($vis['story_img'] ?? '') !!}"
             alt="{{ $cln($vis['story_alt'] ?? ('Operasi udara ' . ($sector['card_title'] ?? 'FDS'))) }}"
             loading="lazy"
             decoding="async"
             width="1200"
             height="750">
      </figure>
    </div>
  </div>
</section>

{{-- 3. SOLUSI DAN LAYANAN (3 PHOTO FEATURE CARDS + CAPABILITY HAIRLINE GRID) --}}
@if(!empty($solutions))
<section id="solusi-sektor" class="fds-sec fds-bg-light scroll-mt-24">
  <div class="fds-container">
    <div class="fds-sec-head" data-aos="fade-up">
      <h2 class="fds-h2">{{ $cln($ui['sektor_solutions_heading'] ?? 'Solusi dan Layanan') }}</h2>
    </div>

    @if(!empty($featured_sols))
      <div class="fds-photo-cards-3" data-reveal-group data-reveal-step="85" data-reveal-modulo="3">
        @foreach($featured_sols as $fIdx => $fSol)
          @php
            $card_photo = $sol_photo_map[$fSol['name']] ?? ($gallery_items[$fIdx]['url'] ?? ($vis['story_img'] ?? ''));
            $card_num  = str_pad((string) ($fIdx + 1), 2, '0', STR_PAD_LEFT);
          @endphp
          <article class="fds-photo-card">
            <div class="fds-photo-card-frame">
              <img src="{!! esc_url($card_photo) !!}" alt="{{ $fSol['name'] }} — Full Drone Solutions" loading="lazy" decoding="async" width="800" height="500">
            </div>
            <p class="fds-card-num">{{ $card_num }}</p>
            <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;">
              <h3 class="fds-h3">
                @if($fSol['url'] !== '')
                  <a href="{!! esc_url($fSol['url']) !!}" class="fds-stretched-link" style="color:var(--fds-ink-primary);">{{ $fSol['name'] }}</a>
                @else
                  <span>{{ $fSol['name'] }}</span>
                @endif
              </h3>
              @if($fSol['url'] !== '')
                <span class="fds-icon-cue" aria-hidden="true">{!! $arrow_svg !!}</span>
              @endif
            </div>
            <p class="fds-caption" style="margin:6px 0 0;">{{ $fSol['desc'] }}</p>
          </article>
        @endforeach
      </div>
    @endif

    @if(!empty($remaining_sols))
      <div class="fds-scope-grid" data-reveal-group data-reveal-step="70" data-reveal-modulo="2">
        @foreach($remaining_sols as $rSol)
          <article class="fds-hairline-row {{ $rSol['url'] !== '' ? 'fds-hairline-row--interactive' : '' }}">
            <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;">
              <h3 class="fds-h3">
                @if($rSol['url'] !== '')
                  <a href="{!! esc_url($rSol['url']) !!}" class="fds-stretched-link" style="color:var(--fds-ink-primary);">{{ $rSol['name'] }}</a>
                @else
                  <span>{{ $rSol['name'] }}</span>
                @endif
              </h3>
              @if($rSol['url'] !== '')
                <span class="fds-icon-cue" aria-hidden="true">{!! $arrow_svg !!}</span>
              @endif
            </div>
            <p class="fds-caption" style="margin:6px 0 0;">{{ $rSol['desc'] }}</p>
          </article>
        @endforeach
      </div>
    @endif
  </div>
</section>
@endif

@php
  $has_sec_faqs = !empty($sector['faqs']);
  $sec_s9_cls   = $has_sec_faqs ? 'fds-bg-light' : 'fds-bg-white';
@endphp

{{-- 8. GALERI DOKUMENTASI LAPANGAN & LIGHTBOX --}}
@if(!empty($gallery_items))
<section id="galeri" class="fds-sec fds-bg-white scroll-mt-24">
  <div class="fds-container">
    <h2 data-aos="fade-up" class="fds-h2" style="margin-bottom:28px;">Dokumentasi Lapangan &amp; Armada Survei</h2>
    <x-fds-gallery :items="$gallery_items" variant="bento" />
  </div>
</section>
@endif

{{-- 9. LAYANAN & SEKTOR TERKAIT --}}
@if(!empty($sector['related_capabilities']))
<section class="fds-sec {{ $sec_s9_cls }}">
  <div class="fds-container">
    <div class="fds-sec-head fds-sec-head--split" data-aos="fade-up">
      <div><h2 class="fds-h2">{{ $cln($ui['sektor_related_heading'] ?? 'Layanan & Sektor Terkait') }}</h2></div>
      <div>
        <a href="{!! esc_url(home_url('/layanan/')) !!}" class="fds-btn fds-btn--primary">
          <span>{{ $cln($ui['sektor_related_all_link'] ?? 'Semua Layanan FDS') }}</span>
          {!! $arrow_svg !!}
        </a>
      </div>
    </div>
    <div class="fds-hairline-list" data-reveal-group data-reveal-step="70">
      @foreach($sector['related_capabilities'] as $rel)
        <article class="fds-hairline-row fds-hairline-row--split fds-hairline-row--interactive">
          <h3 class="fds-h3">
            <a href="{!! esc_url($rel['url']) !!}" class="fds-stretched-link" style="color:var(--fds-ink-primary);">{{ $cln($rel['title']) }}</a>
          </h3>
          <span class="fds-icon-cue" aria-hidden="true">{!! $arrow_svg !!}</span>
          <p class="fds-caption" style="margin:0;">{{ $cln($rel['desc']) }}</p>
        </article>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 10. FAQ SEKTOR --}}
<div id="faq" class="scroll-mt-24">
  <x-service-faq
    :title="$p_join((string) ($ui['sektor_faq_prefix'] ?? 'Pertanyaan Umum:'), (string) ($sector['card_title'] ?? 'Layanan'))"
    :subtitle="$ui['sektor_faq_subtitle'] ?? 'Jawaban ringkas terkait metodologi, deliverables, integrasi software, dan tahapan kerja lapangan.'"
    :faqs="$sector['faqs'] ?? []"
    sectionBg="white"
  />
</div>

@endif

@endsection
