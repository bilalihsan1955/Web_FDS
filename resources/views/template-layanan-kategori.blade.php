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
@endphp

@if(!empty($cat))
@php
  $cat_slug   = $cat['slug'] ?? 'jasa-foto-udara-lidar';
  $q_def      = $cat['quote_defaults'] ?? [];
  $wa_prefill = $q_def['wa_prefill'] ?? ('Halo tim FDS, kami ingin berkonsultasi mengenai layanan ' . ($cat['short_title'] ?? 'Layanan FDS') . '.');
  $wa_sep     = strpos($wa_link, '?') === false ? '?' : '&';
  $wa_cta_url = $wa_link . $wa_sep . 'text=' . rawurlencode($wa_prefill);
  $kontak_form_url = home_url('/kontak/?layanan=' . rawurlencode((string) $cat_slug) . '#hubungi-form');
  $kat_trust_bar   = function_exists('fds_scrub_legacy_text')
      ? fds_scrub_legacy_text((string) ($ui['trust_bar'] ?? 'Manufaktur UAV Nasional · ISO 9001:2015 · TKDN 60,74% (Seri FERTO) · SNI 9199:2023 (FERTO 15 & 22)'))
      : (string) ($ui['trust_bar'] ?? 'Manufaktur UAV Nasional · ISO 9001:2015 · TKDN 60,74% (Seri FERTO) · SNI 9199:2023 (FERTO 15 & 22)');

  $resolved_reading_links = [];
  foreach (($cat['reading_links'] ?? []) as $rl) {
      $url = !empty($rl['url'])
          ? (string) $rl['url']
          : (!empty($rl['slug']) && function_exists('App\fds_resolve_optional_post_url')
              ? \App\fds_resolve_optional_post_url((string) $rl['slug'])
              : '');
      if ($url !== '' && !empty($rl['label'])) {
          $resolved_reading_links[] = ['label' => (string) $rl['label'], 'url' => $url];
      }
  }
@endphp

{{-- 1. HERO KATEGORI LAYANAN --}}
<section class="relative pt-32 sm:pt-40 pb-20 sm:pb-24 bg-gradient-to-br from-[#0f808a] via-[#0a555c] to-[#052b2e] overflow-hidden">
  <div class="absolute inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
    <svg class="absolute inset-0 w-full h-full object-cover" viewBox="0 0 1440 500" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="kat-wave1" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#ffffff" stop-opacity="0.12" />
          <stop offset="100%" stop-color="#052b2e" stop-opacity="0.55" />
        </linearGradient>
        <linearGradient id="kat-wave2" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#75e6f0" stop-opacity="0.18" />
          <stop offset="100%" stop-color="#031e20" stop-opacity="0.75" />
        </linearGradient>
      </defs>
      <path d="M0,180 C340,220 720,340 1080,380 C1260,400 1380,390 1440,365 L1440,500 L0,500 Z" fill="url(#kat-wave1)" />
      <path d="M0,310 C320,330 680,410 1040,440 C1240,455 1360,450 1440,435 L1440,500 L0,500 Z" fill="url(#kat-wave2)" />
    </svg>
  </div>

  <div class="relative z-10 max-w-[1400px] mx-auto px-6 lg:px-12">
    <h1 class="text-[36px] sm:text-[48px] lg:text-[56px] font-semibold tracking-[-0.03em] text-white leading-[1.08] max-w-[920px]">
      {{ $cat['h1'] ?? $cat['short_title'] }}
    </h1>

    @if(!empty($cat['subtitle']))
      <p class="mt-4 text-[17px] sm:text-[19px] text-[#d1f7fa] font-medium max-w-[780px]">
        {{ $cat['subtitle'] }}
      </p>
    @endif

    @if(!empty($cat['hero_summary']))
      <p class="mt-3 text-[15.5px] sm:text-[16.5px] text-white/85 leading-relaxed max-w-[780px]">
        {{ $cat['hero_summary'] }}
      </p>
    @endif

    <div class="mt-8 flex flex-wrap items-center gap-4">
      <a href="{!! esc_url($kontak_form_url) !!}"
         class="inline-flex items-center justify-center min-h-[44px] bg-white text-[#052b2e] hover:bg-[#f5f5f7] text-[14px] sm:text-[15px] font-semibold px-6 py-3 rounded-full transition-colors shadow-lg shadow-black/15">
        {{ $p_join((string) ($ui['kat_cta_primary_prefix'] ?? 'Minta Penawaran'), (string) ($cat['short_title'] ?? '')) }}
      </a>
      <a href="{!! esc_url($wa_cta_url) !!}"
         target="_blank"
         rel="noopener noreferrer"
         class="inline-flex items-center justify-center min-h-[44px] bg-[#25D366] hover:bg-[#1fb857] text-white text-[14px] sm:text-[15px] font-semibold px-6 py-3 rounded-full transition-colors shadow-lg shadow-black/15">
        {{ $ui['cta_wa_text'] ?? 'Konsultasi via WhatsApp' }}
      </a>
      <a href="#kapabilitas"
         class="inline-flex items-center justify-center min-h-[44px] bg-white/15 hover:bg-white/25 text-white border border-white/25 text-[14px] sm:text-[15px] font-medium px-6 py-3 rounded-full transition-colors">
        {{ $ui['kat_cta_secondary_text'] ?? 'Pelajari Ruang Lingkup' }}
      </a>
    </div>

    @if(!empty($kat_trust_bar))
    <p class="mt-6 text-[13px] sm:text-[13.5px] text-white/75">
      {{ $kat_trust_bar }}
    </p>
    @endif
  </div>
</section>

{{-- 2. PENGANTAR & PRINSIP TEKNIS --}}
@if(!empty($cat['intro_paragraphs']) || !empty($cat['challenge_section']))
<section class="bg-white py-16 sm:py-24 border-b border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 space-y-14">
    @if(!empty($cat['intro_paragraphs']))
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        <div class="lg:col-span-4">
          <h2 class="text-[28px] sm:text-[34px] font-semibold tracking-[-0.025em] text-[#1d1d1f] leading-tight">
            {{ $p_join((string) ($ui['kat_intro_heading_prefix'] ?? 'Tentang'), (string) ($cat['short_title'] ?? '')) }}
          </h2>
        </div>
        <div class="lg:col-span-8 space-y-4 text-[16px] sm:text-[17px] text-[#424245] leading-relaxed">
          @foreach($cat['intro_paragraphs'] as $para)
            <p>{{ $para }}</p>
          @endforeach
        </div>
      </div>
    @endif

    @if(!empty($cat['challenge_section']))
      @php $ch = $cat['challenge_section']; @endphp
      <div class="pt-12 border-t border-black/[0.08] space-y-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
          <div class="lg:col-span-5">
            @if(!empty($ch['eyebrow']))
              <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $ch['eyebrow'] }}</p>
            @endif
            <h2 class="text-[28px] sm:text-[36px] font-semibold tracking-[-0.025em] text-[#1d1d1f] leading-tight">
              {{ $ch['title'] ?? '' }}
            </h2>
          </div>
          <div class="lg:col-span-7 space-y-4 text-[15.5px] sm:text-[16.5px] text-[#424245] leading-relaxed">
            @foreach($ch['paragraphs'] ?? [] as $cp)
              <p>{{ $cp }}</p>
            @endforeach
          </div>
        </div>

        @if(!empty($ch['pain_points']))
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($ch['pain_points'] as $pp)
              <div class="pt-5 border-t border-black/[0.12]">
                <h3 class="text-[18px] font-semibold text-[#1d1d1f] leading-snug mb-2">
                  {{ $pp['title'] ?? '' }}
                </h3>
                <p class="text-[14.5px] text-[#515154] leading-relaxed">
                  {{ $pp['desc'] ?? '' }}
                </p>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    @endif
  </div>
</section>
@endif

{{-- 3. KAPABILITAS UTAMA --}}
@if(!empty($cat['capabilities']))
<section id="kapabilitas" class="bg-[#f5f5f7] py-20 sm:py-24 scroll-mt-16">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="mb-12">
      <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $ui['kat_cap_eyebrow'] ?? 'Cakupan Teknis' }}</p>
      <h2 class="text-[32px] sm:text-[42px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
        {{ $ui['kat_cap_heading'] ?? 'Kapabilitas & Ruang Lingkup Layanan' }}
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
      @foreach($cat['capabilities'] as $cap)
        <div class="bg-white rounded-3xl p-7 sm:p-8 border border-black/[0.08]">
          <h3 class="text-[20px] font-semibold text-[#1d1d1f] tracking-[-0.015em] mb-2.5">
            {{ $cap['name'] ?? '' }}
          </h3>
          <p class="text-[15px] text-[#515154] leading-relaxed">
            {{ $cap['desc'] ?? '' }}
          </p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@php $use_white = true; @endphp

{{-- 4. TABEL PERBANDINGAN METODE --}}
@if(!empty($cat['method_comparison']['columns']) && !empty($cat['method_comparison']['rows']))
@php $mc = $cat['method_comparison']; $bg_4 = $use_white ? 'bg-white' : 'bg-[#f5f5f7]'; @endphp
<section class="{{ $bg_4 }} py-20 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="max-w-[820px] mb-10">
      @if(!empty($mc['eyebrow']))
        <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $mc['eyebrow'] }}</p>
      @endif
      <h2 class="text-[30px] sm:text-[38px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-tight">
        {{ $mc['title'] ?? 'Perbandingan Metode' }}
      </h2>
      @if(!empty($mc['subtitle']))
        <p class="mt-3 text-[15.5px] text-[#515154] leading-relaxed">
          {{ $mc['subtitle'] }}
        </p>
      @endif
    </div>

    <div class="overflow-x-auto">
      <table class="fds-seo-spec-table fds-comparison-table min-w-[760px]">
        <thead>
          <tr>
            @foreach($mc['columns'] as $colTitle)
              <th>{{ $colTitle }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($mc['rows'] as $row)
            <tr>
              @foreach($row as $cIdx => $cell)
                <td class="{{ $cIdx === 0 ? 'font-semibold text-[#1d1d1f]' : '' }}">{{ $cell }}</td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@php $use_white = !$use_white; @endphp
@endif

{{-- 5. ALUR KERJA & KOMPATIBILITAS SOFTWARE/EKOSISTEM --}}
@if(!empty($cat['workflow_steps']) || !empty($cat['software_integrations']))
@php $bg_5 = $use_white ? 'bg-white' : 'bg-[#f5f5f7]'; $card_5 = $use_white ? 'bg-[#f5f5f7]' : 'bg-white'; @endphp
<section class="{{ $bg_5 }} py-20 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 items-start">
    @if(!empty($cat['workflow_steps']))
      <div class="lg:col-span-7">
        <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $ui['kat_workflow_eyebrow'] ?? 'Standar Pelaksanaan' }}</p>
        <h2 class="text-[30px] sm:text-[38px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12] mb-8">
          {{ $p_join((string) ($ui['kat_workflow_heading_prefix'] ?? 'Alur Kerja & Kontrol Kualitas'), (string) ($cat['short_title'] ?? '')) }}
        </h2>
        <ol class="space-y-4">
          @foreach($cat['workflow_steps'] as $ws)
            <li class="{{ $card_5 }} rounded-2xl p-6 border border-black/[0.08]">
              <p class="text-[13px] font-semibold text-[#0a555c] mb-1">Tahap {{ $ws['step'] ?? '' }}</p>
              <h3 class="text-[18px] font-semibold text-[#1d1d1f] mb-1.5">{{ $ws['title'] ?? '' }}</h3>
              <p class="text-[14.5px] text-[#515154] leading-relaxed">{{ $ws['desc'] ?? '' }}</p>
            </li>
          @endforeach
        </ol>
      </div>
    @endif

    @if(!empty($cat['software_integrations']))
      @php $si = $cat['software_integrations']; @endphp
      <div class="lg:col-span-5 {{ $card_5 }} rounded-3xl p-7 sm:p-9 border border-black/[0.08] space-y-6">
        <div>
          @if(!empty($si['eyebrow']))
            <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $si['eyebrow'] }}</p>
          @endif
          <h2 class="text-[24px] sm:text-[28px] font-semibold tracking-[-0.025em] text-[#1d1d1f] leading-tight">
            {{ $si['title'] ?? 'Format File & Kompatibilitas' }}
          </h2>
          @if(!empty($si['subtitle']))
            <p class="mt-2.5 text-[14.5px] text-[#515154] leading-relaxed">
              {{ $si['subtitle'] }}
            </p>
          @endif
        </div>

        @if(!empty($si['formats']))
          <div class="pt-4 border-t border-black/[0.08]">
            <h3 class="text-[15px] font-semibold text-[#1d1d1f] mb-3">{{ $ui['kat_formats_heading'] ?? 'Format Output & Modul' }}</h3>
            <ul class="space-y-2.5 text-[14.5px] text-[#424245] leading-relaxed list-disc pl-5">
              @foreach($si['formats'] as $fmt)
                <li>{{ $fmt }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(!empty($si['tools']))
          <div class="pt-4 border-t border-black/[0.08]">
            <h3 class="text-[15px] font-semibold text-[#1d1d1f] mb-2">{{ $ui['kat_tools_heading'] ?? 'Perangkat Lunak & Platform Pendukung' }}</h3>
            <p class="text-[14.5px] text-[#0a555c] font-semibold leading-relaxed">
              {{ implode(' · ', $si['tools']) }}
            </p>
          </div>
        @endif
      </div>
    @endif
  </div>
</section>
@php $use_white = !$use_white; @endphp
@endif

{{-- 6. DELIVERABLES & SEKTOR INDUSTRI YANG TERKAIT --}}
@php $bg_6 = $use_white ? 'bg-white' : 'bg-[#f5f5f7]'; $use_white = !$use_white; @endphp
<section class="{{ $bg_6 }} py-16 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
    <div class="lg:col-span-6">
      <h2 class="text-[28px] sm:text-[34px] font-semibold tracking-[-0.025em] text-[#1d1d1f] mb-5">
        {{ $ui['kat_outputs_heading'] ?? 'Output & Deliverables Utama' }}
      </h2>
      <ul class="space-y-3 text-[15.5px] text-[#424245] leading-relaxed list-disc pl-5">
        @foreach($cat['outputs'] ?? [] as $out)
          <li>{{ $out }}</li>
        @endforeach
      </ul>
    </div>

    <div class="lg:col-span-6">
      <h2 class="text-[28px] sm:text-[34px] font-semibold tracking-[-0.025em] text-[#1d1d1f] mb-5">
        {{ $ui['kat_sectors_heading'] ?? 'Penerapan di Sektor Industri' }}
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($cat['sector_links'] ?? [] as $sec_slug)
          @if(!empty($sectors[$sec_slug]))
            @php $rel_sec = $sectors[$sec_slug]; @endphp
            <x-service-card
              variant="light"
              headingTag="h3"
              :showImage="false"
              :num="$rel_sec['num']"
              :title="$rel_sec['card_title']"
              :desc="$rel_sec['card_desc']"
              :url="$rel_sec['url']"
              :ctaLabel="$ui['kat_sector_card_cta'] ?? 'Lihat sektor'"
            />
          @endif
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- 7. FAKTOR BIAYA, OTORITAS MANUFAKTUR FDS & TAUTAN TERKAIT --}}
@if(!empty($cat['cost_framework']) || !empty($cat['eeat_proof']))
@php $bg_7 = $use_white ? 'bg-white' : 'bg-[#f5f5f7]'; $card_7 = $use_white ? 'bg-[#f5f5f7]' : 'bg-white'; @endphp
<section class="{{ $bg_7 }} py-20 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
    @if(!empty($cat['cost_framework']))
      @php $cf = $cat['cost_framework']; @endphp
      <div class="lg:col-span-6">
        @if(!empty($cf['eyebrow']))
          <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $cf['eyebrow'] }}</p>
        @endif
        <h2 class="text-[28px] sm:text-[36px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.15] mb-4">
          {{ $cf['title'] ?? 'Faktor Penentu Biaya Proyek' }}
        </h2>
        @if(!empty($cf['intro']))
          <p class="text-[15.5px] text-[#515154] leading-relaxed mb-6">{{ $cf['intro'] }}</p>
        @endif

        @if(!empty($cf['factors']))
          <div class="divide-y divide-black/[0.08] border-t border-b border-black/[0.08]">
            @foreach($cf['factors'] as $fac)
              <div class="py-4">
                <h3 class="text-[16.5px] font-semibold text-[#1d1d1f] mb-1">{{ $fac['name'] ?? '' }}</h3>
                <p class="text-[14.5px] text-[#515154] leading-relaxed">{{ $fac['desc'] ?? '' }}</p>
              </div>
            @endforeach
          </div>
        @endif

        <div class="mt-6">
          <a href="{!! esc_url($kontak_form_url) !!}" class="inline-flex items-center gap-1.5 min-h-[44px] text-[14.5px] font-semibold text-[#0a555c] hover:underline underline-offset-4 group leading-none">
            <span>{{ $ui['kat_cost_cta_text'] ?? 'Kirim rincian kebutuhan untuk estimasi penawaran' }}</span>
            <svg class="w-[17px] h-[17px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M6 6h12v12"/>
              <path d="M6 18 18 6"/>
            </svg>
          </a>
        </div>
      </div>
    @endif

    @if(!empty($cat['eeat_proof']))
      @php $ep = $cat['eeat_proof']; @endphp
      <div class="lg:col-span-6 {{ $card_7 }} rounded-3xl p-7 sm:p-9 border border-black/[0.08] space-y-6">
        <div>
          @if(!empty($ep['eyebrow']))
            <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $ep['eyebrow'] }}</p>
          @endif
          <h2 class="text-[26px] sm:text-[32px] font-semibold tracking-[-0.025em] text-[#1d1d1f] leading-tight">
            {{ $ep['title'] ?? 'Standar Kualitas & Manufaktur FDS' }}
          </h2>
          @if(!empty($ep['intro']))
            <p class="mt-3 text-[15px] text-[#424245] leading-relaxed">{{ $ep['intro'] }}</p>
          @endif
        </div>

        @if(!empty($ep['bullets']))
          <ul class="space-y-3 text-[14.5px] text-[#424245] leading-relaxed list-disc pl-5">
            @foreach($ep['bullets'] as $eb)
              <li>{{ $eb }}</li>
            @endforeach
          </ul>
        @endif

        @if(!empty($ep['portfolio_note']))
          <p class="pt-4 border-t border-black/[0.08] text-[13.5px] text-[#6e6e73] leading-relaxed">
            {{ $ep['portfolio_note'] }}
          </p>
        @endif

        @if(!empty($resolved_reading_links))
          <div class="pt-5 border-t border-black/[0.08]">
            <p class="text-[13px] font-semibold text-[#1d1d1f] mb-2.5">{{ $ui['kat_reading_heading'] ?? 'Referensi Teknis & Armada Terkait' }}</p>
            <ul class="space-y-1.5">
              @foreach($resolved_reading_links as $rl)
                <li>
                  <a href="{!! esc_url($rl['url']) !!}" class="inline-flex items-center gap-1.5 min-h-[44px] sm:min-h-[32px] text-[14px] font-semibold text-[#0a555c] hover:underline underline-offset-4 group leading-none">
                    <span>{{ $rl['label'] }}</span>
                    <svg class="w-[15px] h-[15px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                      <path d="M6 6h12v12"/>
                      <path d="M6 18 18 6"/>
                    </svg>
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    @endif
  </div>
</section>
@php $use_white = !$use_white; @endphp
@endif

{{-- 8. FAQ KATEGORI --}}
@php $faq_bg = $use_white ? 'white' : 'gray'; @endphp
<x-service-faq
  :title="$p_join((string) ($ui['kat_faq_prefix'] ?? 'Pertanyaan Umum:'), (string) ($cat['short_title'] ?? 'Layanan'))"
  :subtitle="$ui['kat_faq_subtitle'] ?? 'Penjelasan teknis, format file keluaran, dan informasi yang dapat Anda konsultasikan bersama tim FDS.'"
  :faqs="$cat['faqs'] ?? []"
  :sectionBg="$faq_bg"
/>

{{-- Mobile sticky CTA bar dirender terpusat oleh layouts/app.blade.php --}}

@endif

@endsection
