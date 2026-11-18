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
  $arrow_svg = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>';
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
    <div class="fds-cinema-content">
      <h1 class="fds-h1" style="margin-bottom:20px;">
        {{ $cln($sector['h1'] ?? ($sector['card_title'] ?? '')) }}
      </h1>
      @if(!empty($sector['hero_summary']))
        <p class="fds-lead" style="margin-bottom:32px;">
          {{ $cln($sector['hero_summary']) }}
        </p>
      @endif
      <div class="fds-btn-group">
        <a href="{!! esc_url($kontak_form_url) !!}" class="fds-btn fds-btn--white-teal">
          {{ $cln($p_join((string) ($ui['sektor_cta_primary_prefix'] ?? 'Minta Penawaran'), (string) ($sector['card_title'] ?? ''))) }}
        </a>
        <a href="{!! esc_url($wa_cta_url) !!}" target="_blank" rel="noopener noreferrer" class="fds-btn fds-btn--whatsapp">
          {{ $cln($ui['cta_wa_text'] ?? 'Konsultasi via WhatsApp') }}
        </a>
      </div>
    </div>
  </div>
</section>

{{-- STICKY SUB-NAV --}}
<nav class="fds-subnav" aria-label="Navigasi Bagian Layanan {{ $cln($sector['card_title'] ?? '') }}">
  <div class="fds-container fds-subnav-inner">
    <span style="font-weight:600;font-size:0.9375rem;white-space:nowrap;">{{ $cln($sector['card_title'] ?? 'Layanan') }}</span>
    <ul class="fds-subnav-links">
      <li><a class="fds-subnav-link is-active" href="#solusi-sektor">Ruang Lingkup</a></li>
      <li><a class="fds-subnav-link" href="#deliverables">Output</a></li>
      <li><a class="fds-subnav-link" href="#alur-kerja">Alur Kerja</a></li>
      <li><a class="fds-subnav-link" href="#biaya">Biaya</a></li>
      @if(!empty($gallery_items))
        <li><a class="fds-subnav-link" href="#galeri">Galeri</a></li>
      @endif
      <li><a class="fds-subnav-link" href="#faq">FAQ</a></li>
    </ul>
  </div>
</nav>

{{-- 2. PROOF METRICS + STORY SPLIT WITH FIELD PHOTOGRAPHY --}}
<section class="fds-sec fds-bg-white">
  <div class="fds-container">
    @if(!empty($vis['kpis']))
      <div class="fds-stats-band" style="margin-bottom:64px;">
        @foreach($vis['kpis'] as $kpi)
          <div class="fds-stat-cell">
            <span class="fds-stat-num">{{ $cln($kpi['num'] ?? '') }}</span>
            <span class="fds-stat-label">{{ $cln($kpi['lbl'] ?? '') }}</span>
          </div>
        @endforeach
      </div>
    @endif

    <div class="fds-story-split">
      <div>
        <h2 class="fds-h2" style="margin-bottom:18px;">
          {{ $cln($sector['subtitle'] ?? ($sector['card_title'] ?? '')) }}
        </h2>
        <p class="fds-lead">
          {{ $lead_intro }}
        </p>
      </div>
      <figure class="fds-media-stage">
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
    <div class="fds-sec-head">
      <h2 class="fds-h2">{{ $cln($ui['sektor_solutions_heading'] ?? 'Solusi dan Layanan') }}</h2>
    </div>

    @if(!empty($featured_sols))
      <div class="fds-photo-cards-3" @if(!empty($remaining_sols)) style="margin-bottom:52px;" @endif>
        @foreach($featured_sols as $fIdx => $fSol)
          @php
            $card_photo = $sol_photo_map[$fSol['name']] ?? ($gallery_items[$fIdx]['url'] ?? ($vis['story_img'] ?? ''));
          @endphp
          <article class="fds-photo-card">
            <div class="fds-photo-card-frame">
              <img src="{!! esc_url($card_photo) !!}" alt="{{ $fSol['name'] }} — Full Drone Solutions" loading="lazy" decoding="async" width="800" height="500">
            </div>
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
      <div class="fds-scope-grid">
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

{{-- 4. MATRIKS PERBANDINGAN METODE (PHOTO SPLIT + NATIVE <DETAILS>) --}}
@if(!empty($sector['method_comparison']['columns']) && !empty($sector['method_comparison']['rows']))
@php $mc = $sector['method_comparison']; @endphp
<section id="matriks-metode" class="fds-sec fds-bg-white">
  <div class="fds-container">
    <div class="fds-story-split fds-story-split--media-left">
      <figure class="fds-media-stage">
        <img src="{!! esc_url($vis['matrix_img'] ?? '') !!}"
             alt="{{ $cln($vis['matrix_alt'] ?? 'Teknologi sensor UAV Full Drone Solutions') }}"
             loading="lazy"
             decoding="async"
             width="1000"
             height="625">
      </figure>
      <div>
        <h2 class="fds-h2" style="margin-bottom:16px;">{{ $cln($mc['title'] ?? 'Perbandingan Metode Akuisisi') }}</h2>
        @if(!empty($mc['subtitle']))
          <p class="fds-lead">{{ $cln($mc['subtitle']) }}</p>
        @endif
        <details class="fds-quiet-details">
          <summary class="fds-quiet-summary">Lihat matriks perbandingan metode survei {!! $chev_svg !!}</summary>
          <div class="fds-quiet-body">
            <div class="fds-table-wrap">
              <table class="fds-table">
                <thead>
                  <tr>
                    @foreach($mc['columns'] as $colTitle)
                      <th scope="col">{{ $cln($colTitle) }}</th>
                    @endforeach
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
      </div>
    </div>
  </div>
</section>
@endif

{{-- 5. CAKUPAN TEKNIS & DELIVERABLES (BRIGHT TEAL WAVE + HARDWARE CUTOUT) --}}
@if(!empty($sector['deliverable_groups']))
<section id="deliverables" class="fds-sec fds-bg-teal scroll-mt-24">
  <x-fds-wave type="fluid" :uid="'sec-deliv-' . $sec_slug" />
  <div class="fds-container fds-sticky-split">
    <div class="fds-sticky-col">
      <h2 class="fds-h2" style="margin-bottom:14px;">
        {{ $cln($ui['sektor_deliverables_heading'] ?? 'Cakupan Konsultasi, Analisis & Output Data') }}
      </h2>
      <p class="fds-lead">
        {{ $cln($p_join((string) ($ui['sektor_deliverables_sub_prefix'] ?? 'Rincian aktivitas konsultasi, metode akuisisi, pengolahan spasial, hingga dashboard pemantauan pada sektor'), (string) ($sector['card_title'] ?? ''))) }}.
      </p>
      @if(!empty($vis['hardware_cutout']))
        <div class="fds-hardware-cutout">
          <img src="{!! esc_url($vis['hardware_cutout']) !!}"
               alt="{{ $cln($vis['hardware_alt'] ?? 'Armada UAV Full Drone Solutions') }}"
               loading="lazy"
               decoding="async"
               width="1148"
               height="559">
        </div>
      @endif
    </div>
    <div class="fds-faq-list">
      @foreach($sector['deliverable_groups'] as $gIdx => $group)
        @php $gnum = str_pad((string) ($gIdx + 1), 2, '0', STR_PAD_LEFT); @endphp
        <details class="fds-faq-row">
          <summary class="fds-faq-summary">
            <span>Bagian {{ $gnum }} — {{ $cln($group['title'] ?? '') }}</span>
            {!! $chev_svg !!}
          </summary>
          <div class="fds-faq-body fds-prose">
            <ul style="margin:0;padding-left:20px;display:grid;gap:6px;">
              @foreach($group['items'] ?? [] as $item)
                <li class="fds-body">{{ $cln($item) }}</li>
              @endforeach
            </ul>
          </div>
        </details>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 6. ALUR KERJA LAPANGAN (WORKSHOP PHOTO + ACCORDION STEPS) --}}
@if(!empty($sector['workflow_steps']))
<section id="alur-kerja" class="fds-sec fds-bg-light scroll-mt-24">
  <div class="fds-container fds-sticky-split">
    <div class="fds-sticky-col">
      <h2 class="fds-h2" style="margin-bottom:24px;">
        {{ $cln($p_join((string) ($ui['sektor_workflow_heading_prefix'] ?? 'Alur Kerja Survei & Pengendalian Mutu Sektor'), (string) ($sector['card_title'] ?? ''))) }}
      </h2>
      <figure class="fds-media-stage">
        <img src="{!! esc_url($vis['workflow_img'] ?? '') !!}"
             alt="{{ $cln($vis['workflow_alt'] ?? 'Alur kerja pengendalian mutu Full Drone Solutions') }}"
             loading="lazy"
             decoding="async"
             width="1200"
             height="750">
      </figure>
    </div>
    <div class="fds-faq-list">
      @foreach($sector['workflow_steps'] as $ws)
        <details class="fds-faq-row">
          <summary class="fds-faq-summary">
            <span>
              <strong class="fds-num" style="color:var(--color-teal-800);margin-right:12px;">Tahap {{ $cln($ws['step'] ?? '') }}</strong>
              {{ $cln($ws['title'] ?? '') }}
            </span>
            {!! $chev_svg !!}
          </summary>
          <div class="fds-faq-body fds-prose">
            <p class="fds-body" style="margin:0;">{{ $cln($ws['desc'] ?? '') }}</p>
          </div>
        </details>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 7. VARIABEL PEMBENTUK BIAYA --}}
@if(!empty($sector['cost_framework']))
@php $cf = $sector['cost_framework']; @endphp
<section id="biaya" class="fds-sec fds-bg-white scroll-mt-24">
  <div class="fds-container fds-sticky-split">
    <div class="fds-sticky-col">
      <h2 class="fds-h2" style="margin-bottom:24px;">{{ $cln($cf['title'] ?? 'Faktor Penentu Biaya Proyek') }}</h2>
      <a href="{!! esc_url($kontak_form_url) !!}" class="fds-btn fds-btn--primary">
        {{ $cln($ui['sektor_cost_cta_text'] ?? 'Kirim lokasi & batas AOI untuk estimasi resmi') }}
      </a>
    </div>
    <div class="fds-faq-list">
      @foreach($cf['factors'] ?? [] as $fac)
        <details class="fds-faq-row">
          <summary class="fds-faq-summary">
            <span>{{ $cln($fac['name'] ?? '') }}</span>
            {!! $chev_svg !!}
          </summary>
          <div class="fds-faq-body fds-prose">
            <p class="fds-body" style="margin:0;">{{ $cln($fac['desc'] ?? '') }}</p>
          </div>
        </details>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- 8. GALERI DOKUMENTASI LAPANGAN & LIGHTBOX --}}
@if(!empty($gallery_items))
<section id="galeri" class="fds-sec fds-bg-light scroll-mt-24">
  <div class="fds-container">
    <div class="fds-sec-head">
      <h2 class="fds-h2">Dokumentasi Lapangan &amp; Armada Survei.</h2>
      <p class="fds-lead">Akuisisi data lapangan, operasi stasiun kendali, serta kesiapan armada Full Drone Solutions.</p>
    </div>
    <x-fds-gallery :items="$gallery_items" variant="bento" />
  </div>
</section>
@endif

{{-- 9. LAYANAN & SEKTOR TERKAIT --}}
@if(!empty($sector['related_capabilities']))
<section class="fds-sec fds-bg-white">
  <div class="fds-container">
    <div class="fds-sec-head fds-sec-head--split">
      <div><h2 class="fds-h2">{{ $cln($ui['sektor_related_heading'] ?? 'Layanan & Sektor Terkait') }}</h2></div>
      <div>
        <a href="{!! esc_url(home_url('/layanan/')) !!}" class="fds-text-link">
          {{ $cln($ui['sektor_related_all_link'] ?? 'Semua Layanan FDS') }} {!! $arrow_svg !!}
        </a>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-9">
      @foreach($sector['related_capabilities'] as $rel)
        <article class="pt-5 border-t-2 border-[#0f808a] relative group">
          <div style="display:flex;align-items:baseline;justify-content:space-between;gap:10px;margin-bottom:6px;">
            <h3 class="fds-h3">
              <a href="{!! esc_url($rel['url']) !!}" class="fds-stretched-link" style="color:var(--fds-ink-primary);">{{ $cln($rel['title']) }}</a>
            </h3>
            <span class="fds-icon-cue" aria-hidden="true">{!! $arrow_svg !!}</span>
          </div>
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
    sectionBg="gray"
  />
</div>

{{-- 11. APPLE-STYLE QUIET FOOTNOTE BAND (SELURUH DETAIL SEO TERJAGA DI DOM SSR) --}}
@if(!empty($extra_intros) || !empty($sector['challenge_section']) || !empty($sector['products_tech']) || !empty($sector['closing_paragraphs']) || !empty($sector['software_integrations']) || !empty($sector['eeat_proof']))
<section class="fds-footnote-band" aria-label="Referensi Teknis &amp; Dokumentasi {{ $cln($sector['card_title'] ?? '') }}">
  <div class="fds-container">
    <details class="fds-quiet-details" style="margin-top:0;">
      <summary class="fds-quiet-summary">Referensi Teknis, Tantangan Lapangan &amp; Kompatibilitas Integrasi {!! $chev_svg !!}</summary>
      <div class="fds-quiet-body fds-prose">
        @foreach($extra_intros as $ep)
          <p class="fds-body" style="margin-bottom:12px;">{{ $cln($ep) }}</p>
        @endforeach

        @if(!empty($sector['challenge_section']))
          @php $ch = $sector['challenge_section']; @endphp
          <h2 class="fds-h3" style="margin:20px 0 8px;">{{ $cln($ch['title'] ?? '') }}</h2>
          @foreach($ch['paragraphs'] ?? [] as $cp)
            <p class="fds-body" style="margin-bottom:8px;">{{ $cln($cp) }}</p>
          @endforeach
          @if(!empty($ch['pain_points']))
            <ul style="margin:8px 0 16px;padding-left:20px;display:grid;gap:6px;">
              @foreach($ch['pain_points'] as $pp)
                <li class="fds-body"><strong>{{ $cln($pp['title'] ?? '') }}:</strong> {{ $cln($pp['desc'] ?? '') }}</li>
              @endforeach
            </ul>
          @endif
        @endif

        @if(!empty($sector['work_outputs']))
          <h3 class="fds-h3" style="margin:18px 0 6px;">{{ $cln($ui['sektor_outputs_heading'] ?? 'Output Pekerjaan') }}</h3>
          <p class="fds-body" style="margin-bottom:12px;">{{ $cln($sector['work_outputs']) }}</p>
        @endif

        @if(!empty($sector['products_tech']))
          <h2 class="fds-h3" style="margin:18px 0 8px;">{{ $cln($ui['sektor_products_heading'] ?? 'Produk dan Teknologi Pendukung') }}</h2>
          <ul style="margin:0 0 14px;padding-left:20px;display:grid;gap:6px;">
            @foreach($sector['products_tech'] as $pt)
              <li class="fds-body">
                @if(!empty($pt['url']))
                  <a href="{!! esc_url($pt['url']) !!}" style="color:var(--color-teal-800);font-weight:600;">{{ $cln($pt['name']) }}</a>
                @else
                  <strong>{{ $cln($pt['name']) }}</strong>
                @endif
                @if(!empty($pt['note'])) — {{ $cln($pt['note']) }}@endif
                @foreach($pt['sublinks'] ?? [] as $sub)
                  · <a href="{!! esc_url($sub['url']) !!}" style="color:var(--color-teal-800);">{{ $cln($sub['label']) }}</a>
                @endforeach
              </li>
            @endforeach
          </ul>
        @endif

        @if(!empty($sector['closing_paragraphs']))
          <h2 class="fds-h3" style="margin:18px 0 8px;">{{ $cln($ui['sektor_closing_heading'] ?? 'Dukungan Lintas Tim Operasional') }}</h2>
          @foreach($sector['closing_paragraphs'] as $clp)
            <p class="fds-body" style="margin-bottom:8px;">{{ $cln($clp) }}</p>
          @endforeach
        @endif

        @if(!empty($sector['software_integrations']))
          @php $si = $sector['software_integrations']; @endphp
          <h2 class="fds-h3" style="margin:18px 0 6px;">{{ $cln($si['title'] ?? 'Format Output & Kompatibilitas Software') }}</h2>
          @if(!empty($si['subtitle']))
            <p class="fds-body" style="margin-bottom:8px;">{{ $cln($si['subtitle']) }}</p>
          @endif
          @if(!empty($si['formats']))
            <ul style="margin:0 0 10px;padding-left:20px;display:grid;gap:4px;">
              @foreach($si['formats'] as $fmt)
                <li class="fds-body">{{ $cln($fmt) }}</li>
              @endforeach
            </ul>
          @endif
          @if(!empty($si['tools']))
            <p class="fds-caption" style="color:var(--color-teal-800);font-weight:600;">{{ implode(' · ', array_map($cln, $si['tools'])) }}</p>
          @endif
        @endif

        @if(!empty($sector['eeat_proof']))
          @php $ep = $sector['eeat_proof']; @endphp
          <h2 class="fds-h3" style="margin:18px 0 6px;">{{ $cln($ep['title'] ?? 'Standar Kualitas & Manufaktur FDS') }}</h2>
          @if(!empty($ep['intro']))
            <p class="fds-body" style="margin-bottom:8px;">{{ $cln($ep['intro']) }}</p>
          @endif
          @if(!empty($ep['bullets']))
            <ul style="margin:0 0 12px;padding-left:20px;display:grid;gap:4px;">
              @foreach($ep['bullets'] as $eb)
                <li class="fds-body">{{ $cln($eb) }}</li>
              @endforeach
            </ul>
          @endif
          @if(!empty($ep['portfolio_note']))
            <p class="fds-caption" style="margin-bottom:10px;">{{ $cln($ep['portfolio_note']) }}</p>
          @endif
        @endif

        @if(!empty($resolved_reading_links))
          <ul style="margin:8px 0 0;padding-left:20px;display:grid;gap:4px;">
            @foreach($resolved_reading_links as $rl)
              <li><a href="{!! esc_url($rl['url']) !!}" style="color:var(--color-teal-800);">{{ $rl['label'] }}</a></li>
            @endforeach
          </ul>
        @endif
      </div>
    </details>
  </div>
</section>
@endif

@endif

@endsection
