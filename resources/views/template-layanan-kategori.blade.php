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
  $arrow_svg = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>';
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
    <div class="fds-cinema-content">
      <h1 class="fds-h1" style="margin-bottom:20px;">
        {{ $cln($cat['h1'] ?? ($cat['short_title'] ?? '')) }}
      </h1>
      @if(!empty($cat['hero_summary']))
        <p class="fds-lead" style="margin-bottom:32px;">{{ $cln($cat['hero_summary']) }}</p>
      @endif
      <div class="fds-btn-group">
        <a href="{!! esc_url($kontak_form_url) !!}" class="fds-btn fds-btn--white-teal">
          {{ $cln($p_join((string) ($ui['kat_cta_primary_prefix'] ?? 'Minta Penawaran'), (string) ($cat['short_title'] ?? ''))) }}
        </a>
        <a href="{!! esc_url($wa_cta_url) !!}" target="_blank" rel="noopener noreferrer" class="fds-btn fds-btn--whatsapp">
          {{ $cln($ui['cta_wa_text'] ?? 'Konsultasi via WhatsApp') }}
        </a>
      </div>
    </div>
  </div>
</section>

{{-- 2. STORY SPLIT --}}
<section class="fds-sec fds-bg-white">
  <div class="fds-container">
    <div class="fds-story-split">
      <div>
        <h2 class="fds-h2" style="margin-bottom:18px;">
          {{ $cln($p_join((string) ($ui['kat_intro_heading_prefix'] ?? 'Tentang'), (string) ($cat['short_title'] ?? ''))) }}
        </h2>
        <p class="fds-lead">{{ $lead_intro }}</p>
      </div>
      <figure class="fds-media-stage">
        <img src="{!! esc_url($vis['story_img'] ?? '') !!}" alt="{{ $cln($cat['short_title'] ?? 'Layanan') }} — Operasi Lapangan FDS" loading="lazy" decoding="async" width="1200" height="750">
      </figure>
    </div>
  </div>
</section>

{{-- 3. KAPABILITAS UTAMA --}}
@if(!empty($cat['capabilities']))
<section id="kapabilitas" class="fds-sec fds-bg-light scroll-mt-24">
  <div class="fds-container">
    <div class="fds-sec-head">
      <h2 class="fds-h2">{{ $cln($ui['kat_cap_heading'] ?? 'Kapabilitas & Ruang Lingkup Layanan') }}</h2>
    </div>
    <div class="fds-scope-grid">
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

{{-- 4. OUTPUT & DELIVERABLES (OCEAN-TEAL WAVE BAND) --}}
@if(!empty($cat['outputs']) || !empty($cat['workflow_steps']))
<section class="fds-sec fds-bg-teal">
  <x-fds-wave type="fluid" :uid="'kat-wf-' . $cat_slug" />
  <div class="fds-container fds-sticky-split">
    <div class="fds-sticky-col">
      <h2 class="fds-h2" style="margin-bottom:14px;">{{ $cln($ui['kat_outputs_heading'] ?? 'Output & Deliverables Utama') }}</h2>
      @if(!empty($cat['outputs']))
        <ul style="margin:16px 0 0;padding-left:20px;display:grid;gap:8px;">
          @foreach($cat['outputs'] as $out)
            <li class="fds-body">{{ $cln($out) }}</li>
          @endforeach
        </ul>
      @endif
      @if(!empty($vis['cutout']))
        <div class="fds-hardware-cutout">
          <img src="{!! esc_url($vis['cutout']) !!}" alt="Armada UAV {{ $cln($cat['short_title'] ?? 'FDS') }}" loading="lazy" decoding="async" width="1148" height="559">
        </div>
      @endif
    </div>
    @if(!empty($cat['workflow_steps']))
      <div class="fds-faq-list">
        @foreach($cat['workflow_steps'] as $ws)
          <details class="fds-faq-row">
            <summary class="fds-faq-summary">
              <span>
                <strong class="fds-num" style="margin-right:12px;">Tahap {{ $cln($ws['step'] ?? '') }}</strong>
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
    @endif
  </div>
</section>
@endif

{{-- 5. METODE & BIAYA --}}
@if(!empty($cat['method_comparison']['columns']) || !empty($cat['cost_framework']))
<section class="fds-sec fds-bg-white">
  <div class="fds-container fds-sticky-split">
    <div class="fds-sticky-col">
      @if(!empty($cat['cost_framework']))
        @php $cf = $cat['cost_framework']; @endphp
        <h2 class="fds-h2" style="margin-bottom:14px;">{{ $cln($cf['title'] ?? 'Faktor Penentu Biaya Proyek') }}</h2>
        @if(!empty($cf['intro']))
          <p class="fds-lead" style="margin-bottom:24px;">{{ $cln($cf['intro']) }}</p>
        @endif
        <a href="{!! esc_url($kontak_form_url) !!}" class="fds-btn fds-btn--primary">
          {{ $cln($ui['kat_cost_cta_text'] ?? 'Kirim rincian kebutuhan untuk estimasi penawaran') }}
        </a>
      @elseif(!empty($cat['method_comparison']['title']))
        <h2 class="fds-h2">{{ $cln($cat['method_comparison']['title']) }}</h2>
      @endif
    </div>
    <div>
      @if(!empty($cat['cost_framework']['factors']))
        <div class="fds-faq-list">
          @foreach($cat['cost_framework']['factors'] as $fac)
            <details class="fds-faq-row">
              <summary class="fds-faq-summary"><span>{{ $cln($fac['name'] ?? '') }}</span> {!! $chev_svg !!}</summary>
              <div class="fds-faq-body fds-prose"><p class="fds-body" style="margin:0;">{{ $cln($fac['desc'] ?? '') }}</p></div>
            </details>
          @endforeach
        </div>
      @endif

      @if(!empty($cat['method_comparison']['columns']) && !empty($cat['method_comparison']['rows']))
        @php $mc = $cat['method_comparison']; @endphp
        <details class="fds-quiet-details">
          <summary class="fds-quiet-summary">Lihat tabel perbandingan metode ({!! $cln($mc['title'] ?? 'Perbandingan Metode') !!}) {!! $chev_svg !!}</summary>
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
      @endif
    </div>
  </div>
</section>
@endif

{{-- 6. PENERAPAN DI SEKTOR INDUSTRI (SEQUENTIAL DYNAMIC NUMBERING PRESERVED) --}}
@if(!empty($cat['sector_links']))
<section class="fds-sec fds-bg-light">
  <div class="fds-container">
    <div class="fds-sec-head fds-sec-head--split">
      <div><h2 class="fds-h2">{{ $cln($ui['kat_sectors_heading'] ?? 'Penerapan di Sektor Industri') }}</h2></div>
      <div>
        <a href="{!! esc_url(home_url('/layanan/')) !!}" class="fds-text-link">Semua Layanan FDS {!! $arrow_svg !!}</a>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-9">
      @php $relSecCounter = 0; @endphp
      @foreach($cat['sector_links'] as $sec_slug)
        @if(!empty($sectors[$sec_slug]))
          @php
            $rel_sec = $sectors[$sec_slug];
            $relSecCounter++;
            $rel_sec_num = str_pad((string) $relSecCounter, 2, '0', STR_PAD_LEFT);
          @endphp
          <article class="pt-5 border-t-2 border-[#0f808a] relative group">
            <p class="fds-caption fds-num" style="margin:0 0 6px;color:var(--color-teal-800);font-weight:600;">{{ $rel_sec_num }}</p>
            <div style="display:flex;align-items:baseline;justify-content:space-between;gap:10px;margin-bottom:6px;">
              <h3 class="fds-h3">
                <a href="{!! esc_url($rel_sec['url']) !!}" class="fds-stretched-link" style="color:var(--fds-ink-primary);">{{ $cln($rel_sec['card_title']) }}</a>
              </h3>
              <span class="fds-icon-cue" aria-hidden="true">{!! $arrow_svg !!}</span>
            </div>
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
<section id="galeri" class="fds-sec fds-bg-white scroll-mt-24">
  <div class="fds-container">
    <div class="fds-sec-head">
      <h2 class="fds-h2">Dokumentasi Lapangan &amp; Operasi Teknis.</h2>
    </div>
    <x-fds-gallery :items="$gallery_items" variant="bento" />
  </div>
</section>
@endif

{{-- 8. FAQ KATEGORI --}}
<x-service-faq
  :title="$p_join((string) ($ui['kat_faq_prefix'] ?? 'Pertanyaan Umum:'), (string) ($cat['short_title'] ?? 'Layanan'))"
  :subtitle="$ui['kat_faq_subtitle'] ?? 'Penjelasan teknis, format file keluaran, dan informasi yang dapat Anda konsultasikan bersama tim FDS.'"
  :faqs="$cat['faqs'] ?? []"
  sectionBg="gray"
/>

{{-- 9. QUIET FOOTNOTE REFERENCE BAND --}}
@if(!empty($extra_intros) || !empty($cat['challenge_section']) || !empty($cat['software_integrations']) || !empty($cat['eeat_proof']))
<section class="fds-footnote-band" aria-label="Referensi Teknis {{ $cln($cat['short_title'] ?? '') }}">
  <div class="fds-container">
    <details class="fds-quiet-details" style="margin-top:0;">
      <summary class="fds-quiet-summary">Referensi Teknis &amp; Standar Operasional {!! $chev_svg !!}</summary>
      <div class="fds-quiet-body fds-prose">
        @foreach($extra_intros as $ep)
          <p class="fds-body" style="margin-bottom:10px;">{{ $cln($ep) }}</p>
        @endforeach
        @if(!empty($cat['challenge_section']))
          @php $ch = $cat['challenge_section']; @endphp
          <h2 class="fds-h3" style="margin:18px 0 6px;">{{ $cln($ch['title'] ?? '') }}</h2>
          @foreach($ch['paragraphs'] ?? [] as $cp)
            <p class="fds-body" style="margin-bottom:8px;">{{ $cln($cp) }}</p>
          @endforeach
        @endif
        @if(!empty($cat['software_integrations']))
          @php $si = $cat['software_integrations']; @endphp
          <h2 class="fds-h3" style="margin:18px 0 6px;">{{ $cln($si['title'] ?? 'Format File & Kompatibilitas') }}</h2>
          @if(!empty($si['formats']))
            <ul style="margin:0 0 10px;padding-left:20px;display:grid;gap:4px;">
              @foreach($si['formats'] as $fmt)<li class="fds-body">{{ $cln($fmt) }}</li>@endforeach
            </ul>
          @endif
        @endif
        @if(!empty($cat['eeat_proof']))
          @php $ep = $cat['eeat_proof']; @endphp
          <h2 class="fds-h3" style="margin:18px 0 6px;">{{ $cln($ep['title'] ?? 'Standar Kualitas & Manufaktur FDS') }}</h2>
          @if(!empty($ep['intro']))<p class="fds-body" style="margin-bottom:8px;">{{ $cln($ep['intro']) }}</p>@endif
          @if(!empty($ep['bullets']))
            <ul style="margin:0 0 10px;padding-left:20px;display:grid;gap:4px;">
              @foreach($ep['bullets'] as $eb)<li class="fds-body">{{ $cln($eb) }}</li>@endforeach
            </ul>
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
