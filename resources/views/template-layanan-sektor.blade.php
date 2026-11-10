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
@endphp

@if(!empty($sector))
@php
  $sec_slug   = $sector['slug'] ?? 'layanan';
  $q_def      = $sector['quote_defaults'] ?? [];
  $wa_prefill = $q_def['wa_prefill'] ?? ('Halo tim FDS, kami ingin berkonsultasi mengenai layanan sektor ' . ($sector['card_title'] ?? 'Industri') . '.');
  $wa_sep     = strpos($wa_link, '?') === false ? '?' : '&';
  $wa_cta_url = $wa_link . $wa_sep . 'text=' . rawurlencode($wa_prefill);

  $resolved_reading_links = [];
  foreach (($sector['reading_links'] ?? []) as $rl) {
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

{{-- 1. HERO SEKTOR --}}
<section class="relative pt-32 sm:pt-40 pb-20 sm:pb-24 bg-gradient-to-br from-[#0f808a] via-[#0a555c] to-[#052b2e] overflow-hidden">
  <div class="absolute inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
    <svg class="absolute inset-0 w-full h-full object-cover" viewBox="0 0 1440 500" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="sec-wave1" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#ffffff" stop-opacity="0.12" />
          <stop offset="100%" stop-color="#052b2e" stop-opacity="0.55" />
        </linearGradient>
        <linearGradient id="sec-wave2" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#75e6f0" stop-opacity="0.18" />
          <stop offset="100%" stop-color="#031e20" stop-opacity="0.75" />
        </linearGradient>
      </defs>
      <path d="M0,180 C340,220 720,340 1080,380 C1260,400 1380,390 1440,365 L1440,500 L0,500 Z" fill="url(#sec-wave1)" />
      <path d="M0,310 C320,330 680,410 1040,440 C1240,455 1360,450 1440,435 L1440,500 L0,500 Z" fill="url(#sec-wave2)" />
    </svg>
  </div>

  <div class="relative z-10 max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="mb-6">
      <x-breadcrumbs
        theme="dark"
        :items="[
          ['name' => 'Beranda', 'url' => home_url('/')],
          ['name' => 'Layanan', 'url' => home_url('/layanan/')],
          ['name' => $sector['card_title'] ?? 'Sektor Industri', 'url' => ''],
        ]"
      />
    </div>

    <p class="text-[14px] sm:text-[15px] font-semibold text-[#a3eef5] mb-3 tracking-wide">
      {{ $sector['num'] ?? '' }} &middot; {{ $sector['subtitle'] ?? '' }}
    </p>

    <h1 class="text-[36px] sm:text-[48px] lg:text-[56px] font-semibold tracking-[-0.03em] text-white leading-[1.08] max-w-[920px]">
      {{ $sector['h1'] ?? $sector['card_title'] }}
    </h1>

    @if(!empty($sector['hero_summary']))
      <p class="mt-5 text-[17px] sm:text-[19px] text-white/90 leading-relaxed max-w-[780px]">
        {{ $sector['hero_summary'] }}
      </p>
    @endif

    <div class="mt-8 flex flex-wrap items-center gap-4">
      <a href="#form-penawaran"
         class="inline-flex items-center justify-center min-h-[44px] bg-white text-[#052b2e] hover:bg-[#f5f5f7] text-[14px] sm:text-[15px] font-semibold px-6 py-3 rounded-full transition-colors shadow-lg shadow-black/15">
        {{ $p_join((string) ($ui['sektor_cta_primary_prefix'] ?? 'Minta Penawaran'), (string) ($sector['card_title'] ?? '')) }}
      </a>
      <a href="{!! esc_url($wa_cta_url) !!}"
         target="_blank"
         rel="noopener noreferrer"
         class="inline-flex items-center justify-center min-h-[44px] bg-[#25D366] hover:bg-[#1fb857] text-white text-[14px] sm:text-[15px] font-semibold px-6 py-3 rounded-full transition-colors shadow-lg shadow-black/15">
        {{ $ui['cta_wa_text'] ?? 'Konsultasi via WhatsApp' }}
      </a>
      <a href="#solusi-sektor"
         class="inline-flex items-center justify-center min-h-[44px] bg-white/15 hover:bg-white/25 text-white border border-white/25 text-[14px] sm:text-[15px] font-medium px-6 py-3 rounded-full transition-colors">
        {{ $ui['sektor_cta_secondary_text'] ?? 'Lihat Solusi & Output Data' }}
      </a>
    </div>

    @if(!empty($ui['trust_bar'] ?? 'Manufaktur UAV sejak 2012 · TKDN 60,74% · SNI 9199:2023 · ISO 9001:2015'))
    <p class="mt-6 text-[13px] sm:text-[13.5px] text-white/75">
      {{ $ui['trust_bar'] ?? 'Manufaktur UAV sejak 2012 · TKDN 60,74% · SNI 9199:2023 · ISO 9001:2015' }}
    </p>
    @endif
  </div>
</section>

{{-- 2. PENGANTAR SEKTOR & TANTANGAN OPERASIONAL --}}
@if(!empty($sector['intro_paragraphs']) || !empty($sector['challenge_section']))
<section class="bg-white py-16 sm:py-24 border-b border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 space-y-14">
    @if(!empty($sector['intro_paragraphs']))
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        <div class="lg:col-span-4">
          <h2 class="text-[28px] sm:text-[34px] font-semibold tracking-[-0.025em] text-[#1d1d1f] leading-tight">
            {{ $sector['subtitle'] ?? $sector['card_title'] }}
          </h2>
        </div>
        <div class="lg:col-span-8 space-y-4 text-[16px] sm:text-[17px] text-[#424245] leading-relaxed">
          @foreach($sector['intro_paragraphs'] as $para)
            <p>{{ $para }}</p>
          @endforeach
        </div>
      </div>
    @endif

    @if(!empty($sector['challenge_section']))
      @php $ch = $sector['challenge_section']; @endphp
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

{{-- 3. SOLUSI DAN LAYANAN (DIPERTAHANKAN 100%) --}}
@if(!empty($sector['solutions']))
<section id="solusi-sektor" class="bg-[#f5f5f7] py-20 sm:py-24 scroll-mt-16">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="mb-12">
      <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $p_join((string) ($ui['sektor_solutions_eyebrow_prefix'] ?? 'Ruang Lingkup Sektor'), (string) ($sector['card_title'] ?? '')) }}</p>
      <h2 class="text-[32px] sm:text-[42px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
        {{ $ui['sektor_solutions_heading'] ?? 'Solusi dan Layanan' }}
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($sector['solutions'] as $sol)
        @php $has_sol_link = !empty($sol['link_url']); @endphp
        <article class="fds-service-card {{ $has_sol_link ? 'fds-service-card--interactive fds-service-card--light' : '' }} bg-white rounded-3xl p-6 sm:p-7 border border-black/[0.08] flex flex-col justify-between group">
          <div>
            <h3 class="text-[19px] sm:text-[20px] font-semibold tracking-[-0.015em] text-[#1d1d1f] leading-snug mb-2.5 {{ $has_sol_link ? 'group-hover:text-[#0a555c]' : '' }} transition-colors">
              @if($has_sol_link)
                <a href="{{ esc_url($sol['link_url']) }}" class="fds-stretched-link">
                  {{ $sol['name'] }}
                </a>
              @else
                <span>{{ $sol['name'] }}</span>
              @endif
            </h3>
            <p class="text-[14.5px] text-[#515154] leading-relaxed">
              {{ $sol['desc'] }}
            </p>
          </div>

          @if($has_sol_link)
            <div class="pt-5 mt-5 border-t border-black/[0.06]">
              <span class="fds-card-cta-cue inline-flex items-center gap-1.5 text-[13.5px] font-semibold text-[#0a555c] leading-none" aria-hidden="true">
                <span>{{ $sol['link_label'] ?: 'Pelajari selengkapnya' }}</span>
                <svg class="w-[18px] h-[18px] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M6 6h12v12"/>
                  <path d="M6 18 18 6"/>
                </svg>
              </span>
            </div>
          @endif
        </article>
      @endforeach
    </div>
  </div>
</section>
@endif

@php $use_white = true; @endphp

{{-- 4. BLOK NARASI PENUTUP & KONDISIONAL PRODUK/TEKNOLOGI + OUTPUT PEKERJAAN (DIPERTAHANKAN 100%) --}}
@if(!empty($sector['products_tech']) || !empty($sector['work_outputs']) || !empty($sector['closing_paragraphs']))
@php $bg_4 = $use_white ? 'bg-white' : 'bg-[#f5f5f7]'; $card_4 = $use_white ? 'bg-[#f5f5f7]' : 'bg-white'; @endphp
<section class="{{ $bg_4 }} py-16 sm:py-20 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 space-y-12">
    @if(!empty($sector['products_tech']) || !empty($sector['work_outputs']))
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        @if(!empty($sector['products_tech']))
          <div class="lg:col-span-7">
            <h2 class="text-[28px] sm:text-[34px] font-semibold tracking-[-0.025em] text-[#1d1d1f] mb-5">
              {{ $ui['sektor_products_heading'] ?? 'Produk dan Teknologi Pendukung' }}
            </h2>
            <ul class="divide-y divide-black/[0.08] border-t border-b border-black/[0.08]">
              @foreach($sector['products_tech'] as $pt)
                <li class="py-4 text-[15.5px] text-[#1d1d1f]">
                  @if(!empty($pt['url']))
                    <a href="{{ esc_url($pt['url']) }}" class="inline-flex items-center min-h-[44px] sm:min-h-0 font-semibold text-[#0a555c] hover:underline underline-offset-4">
                      {{ $pt['name'] }}
                    </a>
                  @else
                    <span class="font-semibold">{{ $pt['name'] }}</span>
                  @endif

                  @if(!empty($pt['sublinks']))
                    <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-[14px]">
                      @foreach($pt['sublinks'] as $sub)
                        <a href="{{ esc_url($sub['url']) }}" class="inline-flex items-center min-h-[44px] sm:min-h-0 font-semibold text-[#0f808a] hover:underline underline-offset-4">
                          Detail spesifikasi {{ $sub['label'] }} &rarr;
                        </a>
                      @endforeach
                    </div>
                  @endif

                  @if(!empty($pt['note']))
                    <p class="mt-1.5 text-[13.5px] text-[#6e6e73]">{{ $pt['note'] }}</p>
                  @endif
                </li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(!empty($sector['work_outputs']))
          <div class="lg:col-span-5 {{ $card_4 }} rounded-3xl p-7 sm:p-8 border border-black/[0.06]">
            <h3 class="text-[22px] font-semibold text-[#1d1d1f] mb-3">{{ $ui['sektor_outputs_heading'] ?? 'Output Pekerjaan' }}</h3>
            <p class="text-[15.5px] text-[#424245] leading-relaxed">
              {{ $sector['work_outputs'] }}
            </p>
          </div>
        @endif
      </div>
    @endif

    @if(!empty($sector['closing_paragraphs']))
      <div class="{{ $card_4 }} rounded-3xl p-7 sm:p-10 border border-black/[0.06] grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-4">
          <h2 class="text-[26px] sm:text-[30px] font-semibold tracking-[-0.025em] text-[#1d1d1f] leading-tight">
            {{ $ui['sektor_closing_heading'] ?? 'Dukungan Lintas Tim Operasional' }}
          </h2>
        </div>
        <div class="lg:col-span-8 space-y-3.5 text-[16px] text-[#424245] leading-relaxed">
          @foreach($sector['closing_paragraphs'] as $cp)
            <p>{{ $cp }}</p>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</section>
@php $use_white = !$use_white; @endphp
@endif

{{-- 5. MATRIKS PERBANDINGAN METODE --}}
@if(!empty($sector['method_comparison']['columns']) && !empty($sector['method_comparison']['rows']))
@php $mc = $sector['method_comparison']; $bg_5 = $use_white ? 'bg-white' : 'bg-[#f5f5f7]'; @endphp
<section class="{{ $bg_5 }} py-20 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="max-w-[820px] mb-10">
      @if(!empty($mc['eyebrow']))
        <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $mc['eyebrow'] }}</p>
      @endif
      <h2 class="text-[32px] sm:text-[42px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
        {{ $mc['title'] ?? 'Perbandingan Metode Akuisisi' }}
      </h2>
      @if(!empty($mc['subtitle']))
        <p class="mt-3.5 text-[15.5px] sm:text-[16px] text-[#515154] leading-relaxed">
          {{ $mc['subtitle'] }}
        </p>
      @endif
    </div>

    <div class="overflow-x-auto">
      <table class="fds-seo-spec-table fds-comparison-table min-w-[720px]">
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

{{-- 6. CAKUPAN TEKNIS, KONSULTASI & DELIVERABLES (SELURUH BUTIR DIPERTAHANKAN) --}}
@if(!empty($sector['deliverable_groups']))
@php $bg_6 = $use_white ? 'bg-white' : 'bg-[#f5f5f7]'; $card_6 = $use_white ? 'bg-[#f5f5f7]' : 'bg-white'; @endphp
<section id="deliverables" class="{{ $bg_6 }} py-20 sm:py-24 border-t border-black/[0.06] scroll-mt-16">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="max-w-[780px] mb-12 sm:mb-14">
      <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $ui['sektor_deliverables_eyebrow'] ?? 'Detail Teknis & Deliverables' }}</p>
      <h2 class="text-[32px] sm:text-[42px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
        {{ $ui['sektor_deliverables_heading'] ?? 'Cakupan Konsultasi, Analisis & Output Data' }}
      </h2>
      <p class="mt-3.5 text-[16px] text-[#515154] leading-relaxed">
        {{ $p_join((string) ($ui['sektor_deliverables_sub_prefix'] ?? 'Rincian aktivitas konsultasi, metode akuisisi, pengolahan spasial, hingga dashboard pemantauan pada sektor'), (string) ($sector['card_title'] ?? '')) }}.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
      @foreach($sector['deliverable_groups'] as $gIdx => $group)
        <div class="{{ $card_6 }} rounded-3xl p-7 sm:p-8 border border-black/[0.08]">
          <p class="text-[13px] font-semibold text-[#0a555c] mb-1.5">
            Bagian {{ str_pad((string) ($gIdx + 1), 2, '0', STR_PAD_LEFT) }}
          </p>
          <h3 class="text-[20px] sm:text-[22px] font-semibold tracking-[-0.02em] text-[#1d1d1f] leading-snug mb-5">
            {{ $group['title'] ?? '' }}
          </h3>
          <ul class="space-y-2.5 text-[14.5px] sm:text-[15px] text-[#424245] leading-relaxed list-disc pl-5">
            @foreach($group['items'] ?? [] as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        </div>
      @endforeach
    </div>
  </div>
</section>
@php $use_white = !$use_white; @endphp
@endif

{{-- 7. ALUR KERJA LAPANGAN & INTEGRASI SOFTWARE ENGINEERING --}}
@if(!empty($sector['workflow_steps']) || !empty($sector['software_integrations']))
@php $bg_7 = $use_white ? 'bg-white' : 'bg-[#f5f5f7]'; $card_7 = $use_white ? 'bg-[#f5f5f7]' : 'bg-white'; @endphp
<section class="{{ $bg_7 }} py-20 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 items-start">
    @if(!empty($sector['workflow_steps']))
      <div class="lg:col-span-7">
        <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $ui['sektor_workflow_eyebrow'] ?? 'Standar Pelaksanaan' }}</p>
        <h2 class="text-[30px] sm:text-[38px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12] mb-8">
          {{ $p_join((string) ($ui['sektor_workflow_heading_prefix'] ?? 'Alur Kerja Survei & Pengendalian Mutu Sektor'), (string) ($sector['card_title'] ?? '')) }}
        </h2>
        <ol class="space-y-4">
          @foreach($sector['workflow_steps'] as $ws)
            <li class="{{ $card_7 }} rounded-2xl p-6 border border-black/[0.08]">
              <p class="text-[13px] font-semibold text-[#0a555c] mb-1">Tahap {{ $ws['step'] ?? '' }}</p>
              <h3 class="text-[18px] font-semibold text-[#1d1d1f] mb-1.5">{{ $ws['title'] ?? '' }}</h3>
              <p class="text-[14.5px] text-[#515154] leading-relaxed">{{ $ws['desc'] ?? '' }}</p>
            </li>
          @endforeach
        </ol>
      </div>
    @endif

    @if(!empty($sector['software_integrations']))
      @php $si = $sector['software_integrations']; @endphp
      <div class="lg:col-span-5 {{ $card_7 }} rounded-3xl p-7 sm:p-9 border border-black/[0.08] space-y-6">
        <div>
          @if(!empty($si['eyebrow']))
            <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $si['eyebrow'] }}</p>
          @endif
          <h2 class="text-[24px] sm:text-[28px] font-semibold tracking-[-0.025em] text-[#1d1d1f] leading-tight">
            {{ $si['title'] ?? 'Format Output & Kompatibilitas Software' }}
          </h2>
          @if(!empty($si['subtitle']))
            <p class="mt-2.5 text-[14.5px] text-[#515154] leading-relaxed">
              {{ $si['subtitle'] }}
            </p>
          @endif
        </div>

        @if(!empty($si['formats']))
          <div class="pt-4 border-t border-black/[0.08]">
            <h3 class="text-[15px] font-semibold text-[#1d1d1f] mb-3">{{ $ui['sektor_formats_heading'] ?? 'Format File Penyerahan' }}</h3>
            <ul class="space-y-2.5 text-[14.5px] text-[#424245] leading-relaxed list-disc pl-5">
              @foreach($si['formats'] as $fmt)
                <li>{{ $fmt }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(!empty($si['tools']))
          <div class="pt-4 border-t border-black/[0.08]">
            <h3 class="text-[15px] font-semibold text-[#1d1d1f] mb-2">{{ $ui['sektor_tools_heading'] ?? 'Perangkat Lunak Penerima' }}</h3>
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

{{-- 8. FAKTOR BIAYA, OTORITAS MANUFAKTUR FDS & TAUTAN TERKAIT --}}
@if(!empty($sector['cost_framework']) || !empty($sector['eeat_proof']))
@php $bg_8 = $use_white ? 'bg-white' : 'bg-[#f5f5f7]'; $card_8 = $use_white ? 'bg-[#f5f5f7]' : 'bg-white'; @endphp
<section class="{{ $bg_8 }} py-20 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
    @if(!empty($sector['cost_framework']))
      @php $cf = $sector['cost_framework']; @endphp
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
          <a href="#form-penawaran" class="inline-flex items-center min-h-[44px] text-[14.5px] font-semibold text-[#0a555c] hover:underline underline-offset-4">
            {{ $ui['sektor_cost_cta_text'] ?? 'Kirim lokasi & batas AOI untuk estimasi resmi →' }}
          </a>
        </div>
      </div>
    @endif

    @if(!empty($sector['eeat_proof']))
      @php $ep = $sector['eeat_proof']; @endphp
      <div class="lg:col-span-6 {{ $card_8 }} rounded-3xl p-7 sm:p-9 border border-black/[0.08] space-y-6">
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
            <p class="text-[13px] font-semibold text-[#1d1d1f] mb-2.5">{{ $ui['sektor_reading_heading'] ?? 'Referensi Armada & Artikel Teknis' }}</p>
            <ul class="space-y-1.5">
              @foreach($resolved_reading_links as $rl)
                <li>
                  <a href="{{ esc_url($rl['url']) }}" class="inline-flex items-center min-h-[44px] sm:min-h-[32px] text-[14px] font-semibold text-[#0a555c] hover:underline underline-offset-4">
                    {{ $rl['label'] }} &rarr;
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

{{-- 9. INTERNAL LINKING: KEMAMPUAN TERKAIT LINTAS SEKTOR --}}
@if(!empty($sector['related_capabilities']))
@php $bg_9 = $use_white ? 'bg-white' : 'bg-[#f5f5f7]'; @endphp
<section class="{{ $bg_9 }} py-16 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
      <div>
        <h2 class="text-[28px] sm:text-[36px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-tight">
          {{ $ui['sektor_related_heading'] ?? 'Layanan & Sektor Terkait' }}
        </h2>
        <p class="mt-2 text-[15.5px] text-[#515154]">
          {{ $ui['sektor_related_subtitle'] ?? 'Telusuri metode sensor dan solusi lintas industri lainnya dari Full Drone Solutions.' }}
        </p>
      </div>
      <a href="{{ home_url('/layanan/') }}"
         class="inline-flex items-center min-h-[44px] text-[14px] font-semibold text-[#0a555c] hover:underline underline-offset-4 flex-shrink-0">
        {{ $ui['sektor_related_all_link'] ?? 'Semua Layanan FDS →' }}
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach($sector['related_capabilities'] as $rel)
        <x-service-card
          variant="light"
          headingTag="h3"
          :showImage="false"
          :title="$rel['title']"
          :desc="$rel['desc']"
          :url="$rel['url']"
          :ctaLabel="$ui['sektor_related_card_cta'] ?? 'Jelajahi layanan terkait'"
        />
      @endforeach
    </div>
  </div>
</section>
@php $use_white = !$use_white; @endphp
@endif

{{-- 10. FAQ SEKTOR --}}
@php $faq_bg = $use_white ? 'white' : 'gray'; $use_white = !$use_white; @endphp
<x-service-faq
  :title="$p_join((string) ($ui['sektor_faq_prefix'] ?? 'Pertanyaan Umum:'), (string) ($sector['card_title'] ?? 'Layanan'))"
  :subtitle="$ui['sektor_faq_subtitle'] ?? 'Jawaban ringkas terkait metodologi, deliverables, integrasi software, dan tahapan kerja lapangan.'"
  :faqs="$sector['faqs'] ?? []"
  :sectionBg="$faq_bg"
/>

{{-- 11. FORM PENAWARAN & REQUEST FOR PROPOSAL (RFP) --}}
<x-service-quotation-section
  :serviceSlug="$sec_slug"
  :eyebrow="$q_def['eyebrow'] ?? ('Konsultasi Sektor ' . ($sector['card_title'] ?? 'Industri'))"
  :title="$q_def['title'] ?? ('Ajukan Kebutuhan Sektor ' . ($sector['card_title'] ?? 'Industri') . ' Anda')"
  :subtitle="$q_def['subtitle'] ?? ''"
  :kakChecklist="$sector['cost_framework']['kak_checklist'] ?? []"
  :waPrefill="$wa_prefill"
  :messagePlaceholder="$q_def['placeholder'] ?? ''"
  :sectionBg="$use_white ? 'white' : 'gray'"
/>

{{-- 12. MOBILE STICKY CTA BAR --}}
<x-service-sticky-bar
  :ctaLabel="$ui['sticky_cta_label'] ?? 'Minta Penawaran'"
  :waPrefill="$wa_prefill"
/>

@endif

@endsection
