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
  $kontak_form_url     = home_url('/kontak/#hubungi-form');
  $raw_hub_cta_url     = trim((string) ($hub['cta_primary_url'] ?? ''));
  $hub_primary_cta_url = ($raw_hub_cta_url !== '' && $raw_hub_cta_url !== '#form-penawaran')
      ? $raw_hub_cta_url
      : $kontak_form_url;

  $resolved_reading_links = [];
  foreach (($hub['reading_links'] ?? []) as $rl) {
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

{{-- 1. HERO HUB LAYANAN --}}
<section class="relative pt-32 sm:pt-40 pb-20 sm:pb-24 bg-gradient-to-br from-[#0f808a] via-[#0a555c] to-[#052b2e] overflow-hidden">
  <div class="absolute inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
    <svg class="absolute inset-0 w-full h-full object-cover" viewBox="0 0 1440 520" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="hub-wave1" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#ffffff" stop-opacity="0.12" />
          <stop offset="100%" stop-color="#052b2e" stop-opacity="0.55" />
        </linearGradient>
        <linearGradient id="hub-wave2" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#75e6f0" stop-opacity="0.18" />
          <stop offset="100%" stop-color="#031e20" stop-opacity="0.75" />
        </linearGradient>
      </defs>
      <path d="M0,190 C360,230 740,350 1080,390 C1260,410 1380,400 1440,375 L1440,520 L0,520 Z" fill="url(#hub-wave1)" />
      <path d="M0,330 C320,350 680,430 1040,460 C1240,475 1360,470 1440,450 L1440,520 L0,520 Z" fill="url(#hub-wave2)" />
    </svg>
  </div>

  <div class="relative z-10 max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="mb-6">
      <x-breadcrumbs
        theme="dark"
        :items="[
          ['name' => 'Beranda', 'url' => home_url('/')],
          ['name' => 'Layanan', 'url' => ''],
        ]"
      />
    </div>

    @if(!empty($hub['eyebrow']))
      <p class="text-[14px] sm:text-[15px] font-semibold text-[#a3eef5] mb-3 tracking-wide">
        {{ $hub['eyebrow'] }}
      </p>
    @endif

    <h1 class="text-[36px] sm:text-[48px] lg:text-[58px] font-semibold tracking-[-0.03em] text-white leading-[1.08] max-w-[960px]">
      {{ $hub['h1'] ?? 'UAV Technology. Intelligent Data. Real Industrial Solutions.' }}
    </h1>

    @if(!empty($hub['subtitle']))
      <p class="mt-4 text-[17px] sm:text-[19px] text-[#d1f7fa] font-medium leading-relaxed max-w-[820px]">
        {{ $hub['subtitle'] }}
      </p>
    @endif

    @if(!empty($hub['hero_summary']))
      <p class="mt-3 text-[15.5px] sm:text-[17px] text-white/85 leading-relaxed max-w-[800px]">
        {{ $hub['hero_summary'] }}
      </p>
    @endif

    <div class="mt-8 flex flex-wrap items-center gap-4">
      <a href="{{ esc_url($hub_primary_cta_url) }}"
         class="inline-flex items-center justify-center min-h-[44px] bg-white text-[#052b2e] hover:bg-[#f5f5f7] text-[14px] sm:text-[15px] font-semibold px-6 py-3 rounded-full transition-colors shadow-lg shadow-black/15">
        {{ $hub['cta_primary_text'] ?? 'Minta Penawaran Proyek' }}
      </a>
      <a href="{!! esc_url($wa_cta_url) !!}"
         target="_blank"
         rel="noopener noreferrer"
         class="inline-flex items-center justify-center min-h-[44px] bg-[#25D366] hover:bg-[#1fb857] text-white text-[14px] sm:text-[15px] font-semibold px-6 py-3 rounded-full transition-colors shadow-lg shadow-black/15">
        {{ $ui['cta_wa_text'] ?? 'Konsultasi via WhatsApp' }}
      </a>
      <a href="{{ esc_attr($hub['cta_secondary_url'] ?? '#solusi-industri') }}"
         class="inline-flex items-center justify-center min-h-[44px] bg-white/15 hover:bg-white/25 text-white border border-white/25 text-[14px] sm:text-[15px] font-medium px-6 py-3 rounded-full transition-colors">
        {{ $hub['cta_secondary_text'] ?? 'Jelajahi 6 Sektor Industri' }}
      </a>
    </div>

    @if(!empty($ui['trust_bar'] ?? 'Manufaktur UAV sejak 2012 · TKDN 60,74% · SNI 9199:2023 · ISO 9001:2015'))
    <p class="mt-6 text-[13px] sm:text-[13.5px] text-white/75">
      {{ $ui['trust_bar'] ?? 'Manufaktur UAV sejak 2012 · TKDN 60,74% · SNI 9199:2023 · ISO 9001:2015' }}
    </p>
    @endif
  </div>
</section>

{{-- 2. PENGANTAR & TANTANGAN DATA GEOSPASIAL INDUSTRI --}}
@if(!empty($hub['intro_paragraphs']) || !empty($hub['challenge_section']))
<section class="bg-white py-16 sm:py-24 border-b border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 space-y-14">
    @if(!empty($hub['intro_paragraphs']))
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        <div class="lg:col-span-4">
          <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $ui['hub_intro_eyebrow'] ?? 'Tentang Layanan Terpadu FDS' }}</p>
          <h2 class="text-[28px] sm:text-[36px] font-semibold tracking-[-0.025em] text-[#1d1d1f] leading-tight">
            {{ $ui['hub_intro_heading'] ?? 'Ekosistem Layanan Pemetaan, Sensor & UAV Nasional' }}
          </h2>
        </div>
        <div class="lg:col-span-8 space-y-4 text-[16px] sm:text-[17px] text-[#424245] leading-relaxed">
          @foreach($hub['intro_paragraphs'] as $para)
            <p>{{ $para }}</p>
          @endforeach
        </div>
      </div>
    @endif

    @if(!empty($hub['challenge_section']))
      @php $ch = $hub['challenge_section']; @endphp
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

{{-- 3. SOLUSI INDUSTRI (6 KARTU SEKTOR UTAMA DENGAN STRETCHED-LINK UX) --}}
<section id="solusi-industri" class="bg-[#f5f5f7] py-20 sm:py-24 scroll-mt-16">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 sm:mb-14">
      <div>
        <p class="text-[13px] font-semibold text-[#0a555c] tracking-wide mb-3">{{ $ui['hub_sectors_eyebrow'] ?? 'Solusi Industri FDS' }}</p>
        <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] max-w-[680px]">
          {{ $ui['hub_sectors_heading'] ?? 'Enam Sektor Industri Strategis' }}
        </h2>
      </div>
      <p class="text-[15.5px] sm:text-[16px] text-[#515154] max-w-[460px] leading-relaxed">
        {{ $ui['hub_sectors_desc'] ?? 'Pilih sektor operasional Anda untuk mempelajari cakupan survei udara, pemodelan geospasial, inspeksi aset, dan output teknis lengkap.' }}
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
      @foreach($sectors as $sector)
        <x-service-card
          variant="light"
          headingTag="h3"
          :num="$sector['num']"
          :title="$sector['card_title']"
          :desc="$sector['card_desc']"
          :url="$sector['url']"
          :tags="$sector['card_tags']"
          :image="$sector['image']"
          :imageAlt="$sector['image_alt']"
          :ctaLabel="$ui['hub_sector_card_cta'] ?? 'Pelajari solusi sektor'"
        />
      @endforeach
    </div>
  </div>
</section>

{{-- 4. KATEGORI LAYANAN SPESIALIS & PELATIHAN --}}
<section id="kategori-layanan" class="bg-white py-20 sm:py-24 border-t border-black/[0.06] scroll-mt-16">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 sm:mb-14">
      <div>
        <p class="text-[13px] font-semibold text-[#0a555c] tracking-wide mb-3">{{ $ui['hub_categories_eyebrow'] ?? 'Layanan Lintas Industri' }}</p>
        <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] max-w-[740px]">
          {{ $ui['hub_categories_heading'] ?? 'Foto Udara, LiDAR, Sensor Spektral & Pelatihan Tim' }}
        </h2>
      </div>
      <p class="text-[15.5px] sm:text-[16px] text-[#515154] max-w-[440px] leading-relaxed">
        {{ $ui['hub_categories_desc'] ?? 'Kapabilitas akuisisi data geospasial dan peningkatan kapasitas operator yang dapat disesuaikan lintas sektor.' }}
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
      @foreach($categories as $cat)
        <x-service-card
          variant="light"
          headingTag="h3"
          :showImage="false"
          :title="$cat['short_title']"
          :desc="$cat['hero_summary']"
          :url="$cat['url']"
          :ctaLabel="$ui['hub_category_card_cta'] ?? 'Lihat detail layanan'"
        />
      @endforeach
    </div>
  </div>
</section>

{{-- 5. MATRIKS TEKNOLOGI SENSOR UAV --}}
@if(!empty($hub['method_comparison']['columns']) && !empty($hub['method_comparison']['rows']))
@php $mc = $hub['method_comparison']; @endphp
<section class="bg-[#f5f5f7] py-20 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="max-w-[820px] mb-10">
      @if(!empty($mc['eyebrow']))
        <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $mc['eyebrow'] }}</p>
      @endif
      <h2 class="text-[32px] sm:text-[42px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
        {{ $mc['title'] ?? 'Pemilihan Teknologi Akuisisi' }}
      </h2>
      @if(!empty($mc['subtitle']))
        <p class="mt-3.5 text-[15.5px] sm:text-[16px] text-[#515154] leading-relaxed">
          {{ $mc['subtitle'] }}
        </p>
      @endif
    </div>

    <div class="overflow-x-auto">
      <table class="fds-seo-spec-table fds-comparison-table min-w-[780px]">
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
@endif

{{-- 6. MENGAPA FDS & ALUR KERJA 5 TAHAP --}}
<section class="bg-white py-20 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-14 items-start">
    <div class="lg:col-span-5">
      <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $ui['hub_why_eyebrow'] ?? 'Keunggulan Terpadu' }}</p>
      <h2 class="text-[32px] sm:text-[40px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12] mb-4">
        {{ $hub['why_fds']['title'] ?? 'Mengapa Memilih Layanan UAV FDS' }}
      </h2>
      <p class="text-[16px] text-[#515154] leading-relaxed mb-8">
        {{ $hub['why_fds']['desc'] ?? '' }}
      </p>

      <div class="divide-y divide-black/[0.08] border-t border-b border-black/[0.08]">
        @foreach($hub['why_fds']['items'] ?? [] as $why)
          <div class="py-5">
            <h3 class="text-[18px] font-semibold text-[#1d1d1f] mb-1.5">{{ $why['title'] ?? '' }}</h3>
            <p class="text-[14.5px] text-[#515154] leading-relaxed">{{ $why['desc'] ?? '' }}</p>
          </div>
        @endforeach
      </div>
    </div>

    <div class="lg:col-span-7">
      <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $ui['hub_workflow_eyebrow'] ?? 'Metodologi Kerja' }}</p>
      <h2 class="text-[32px] sm:text-[40px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12] mb-4">
        {{ $hub['workflow']['title'] ?? 'Alur Kerja Layanan' }}
      </h2>
      <p class="text-[16px] text-[#515154] leading-relaxed mb-8">
        {{ $hub['workflow']['desc'] ?? '' }}
      </p>

      <ol class="space-y-4">
        @foreach($hub['workflow']['steps'] ?? [] as $step)
          <li class="bg-[#f5f5f7] rounded-2xl p-6 border border-black/[0.06]">
            <p class="text-[13px] font-semibold text-[#0a555c] mb-1">Tahap {{ $step['step'] ?? '' }}</p>
            <h3 class="text-[18px] font-semibold text-[#1d1d1f] mb-1.5">{{ $step['title'] ?? '' }}</h3>
            <p class="text-[14.5px] text-[#515154] leading-relaxed">{{ $step['desc'] ?? '' }}</p>
          </li>
        @endforeach
      </ol>
    </div>
  </div>
</section>

{{-- 7. PANDUAN ESTIMASI BIAYA B2B, KEANDALAN PRODUSEN & TAUTAN TERKAIT --}}
@if(!empty($hub['cost_framework']) || !empty($hub['eeat_proof']))
<section class="bg-[#f5f5f7] py-20 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
    @if(!empty($hub['cost_framework']))
      @php $cf = $hub['cost_framework']; @endphp
      <div class="lg:col-span-6">
        @if(!empty($cf['eyebrow']))
          <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $cf['eyebrow'] }}</p>
        @endif
        <h2 class="text-[28px] sm:text-[36px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.15] mb-4">
          {{ $cf['title'] ?? 'Faktor Penyusunan Penawaran Biaya' }}
        </h2>
        @if(!empty($cf['intro']))
          <p class="text-[15.5px] text-[#515154] leading-relaxed mb-6">
            {{ $cf['intro'] }}
          </p>
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
          <a href="{{ esc_url($kontak_form_url) }}" class="inline-flex items-center min-h-[44px] text-[14.5px] font-semibold text-[#0a555c] hover:underline underline-offset-4">
            {{ $ui['hub_cost_cta_text'] ?? 'Kirim batas poligon AOI & KAK untuk estimasi resmi →' }}
          </a>
        </div>
      </div>
    @endif

    @if(!empty($hub['eeat_proof']))
      @php $ep = $hub['eeat_proof']; @endphp
      <div class="lg:col-span-6 bg-white rounded-3xl p-7 sm:p-9 border border-black/[0.08] space-y-6">
        <div>
          @if(!empty($ep['eyebrow']))
            <p class="text-[13px] font-semibold text-[#0a555c] mb-2">{{ $ep['eyebrow'] }}</p>
          @endif
          <h2 class="text-[26px] sm:text-[32px] font-semibold tracking-[-0.025em] text-[#1d1d1f] leading-tight">
            {{ $ep['title'] ?? 'Keandalan Produsen UAV Nasional' }}
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
            <p class="text-[13px] font-semibold text-[#1d1d1f] mb-2.5">{{ $ui['hub_reading_heading'] ?? 'Tautan Teknis & Armada Terkait' }}</p>
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
@endif

{{-- 8. FAQ HUB LAYANAN --}}
<x-service-faq
  :title="$ui['hub_faq_title'] ?? 'Pertanyaan Umum Layanan FDS'"
  :subtitle="$ui['hub_faq_subtitle'] ?? 'Informasi awal terkait ruang lingkup proyek, pemilihan metode survei udara, integrasi data spasial GIS, dan prosedur konsultasi.'"
  :faqs="$hub['faqs'] ?? []"
  sectionBg="white"
/>

{{-- 9. MOBILE STICKY CTA BAR --}}
<x-service-sticky-bar
  :ctaLabel="$ui['sticky_cta_label'] ?? 'Minta Penawaran'"
  :ctaUrl="$hub_primary_cta_url"
  :waPrefill="$wa_prefill"
/>

@endsection
