@extends('layouts.app')

@section('content')
@php
  // Ambil data lengkap halaman Tentang Kami dari Admin WP
  $about = function_exists('\App\fds_get_about_content') ? \App\fds_get_about_content() : [];

  // Baca content editor bawaan jika ada konten khusus di editor halaman
  $page_content = '';
  if (have_posts()) {
      while (have_posts()) {
          the_post();
          $page_content = get_the_content();
      }
  }
@endphp

{{-- ========================================================== --}}
{{-- HERO — Dark full-bleed with Vector Background              --}}
{{-- ========================================================== --}}
<section class="relative bg-gradient-to-br from-[#1c1f26] via-[#13151b] to-[#0a0c10] pt-[52px] overflow-hidden">
  
  {{-- Vector Background: Redesigned Pure Aerodynamic Flow Strata (Bersih, Luwes & Mewah) --}}
  <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
    <svg class="w-full h-full" viewBox="0 0 1440 600" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
      <defs>
        {{-- Base Canvas Gradient --}}
        <linearGradient id="tk_hero_base" x1="720" y1="0" x2="720" y2="600" gradientUnits="userSpaceOnUse">
          <stop stop-color="#141720"/>
          <stop offset="0.65" stop-color="#0e1118"/>
          <stop offset="1" stop-color="#080a0e"/>
        </linearGradient>

        {{-- Wave Gradients --}}
        <linearGradient id="tk_flow_grad1" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#0052a3" stop-opacity="0.22"/>
          <stop offset="50%" stop-color="#003366" stop-opacity="0.10"/>
          <stop offset="100%" stop-color="#080a0e" stop-opacity="0.0"/>
        </linearGradient>

        <linearGradient id="tk_flow_grad2" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#0066cc" stop-opacity="0.28"/>
          <stop offset="50%" stop-color="#004080" stop-opacity="0.14"/>
          <stop offset="100%" stop-color="#080a0e" stop-opacity="0.0"/>
        </linearGradient>

        <linearGradient id="tk_flow_grad3" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#0077ed" stop-opacity="0.34"/>
          <stop offset="50%" stop-color="#0052a3" stop-opacity="0.16"/>
          <stop offset="100%" stop-color="#080a0e" stop-opacity="0.0"/>
        </linearGradient>

        <linearGradient id="tk_flow_grad4" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#1a85ff" stop-opacity="0.40"/>
          <stop offset="50%" stop-color="#0066cc" stop-opacity="0.18"/>
          <stop offset="100%" stop-color="#080a0e" stop-opacity="0.0"/>
        </linearGradient>

        <linearGradient id="tk_flow_grad5" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#2997ff" stop-opacity="0.46"/>
          <stop offset="50%" stop-color="#0077ed" stop-opacity="0.22"/>
          <stop offset="100%" stop-color="#080a0e" stop-opacity="0.0"/>
        </linearGradient>

        {{-- Bottom Dark Fade to seamlessly blend into section below --}}
        <linearGradient id="tk_bottom_fade" x1="0" y1="320" x2="0" y2="600" gradientUnits="userSpaceOnUse">
          <stop offset="0%" stop-color="#080a0e" stop-opacity="0"/>
          <stop offset="50%" stop-color="#0e1118" stop-opacity="0.6"/>
          <stop offset="100%" stop-color="#141720" stop-opacity="1"/>
        </linearGradient>
      </defs>

      <g>
        <!-- Base Canvas -->
        <rect width="1440" height="600" fill="url(#tk_hero_base)"/>

        <!-- 1. Wave Layer 1 (U-Cradle: Kiri -50px, Tengah 360px, Kanan -50px) -->
        <path d="M-50,-50 C250,40 500,360 720,360 C940,360 1190,40 1490,-50 L1490,700 L-50,700 Z" fill="url(#tk_flow_grad1)"/>

        <!-- 2. Wave Layer 2 (Samping +135px, Tengah +50px) -->
        <path d="M-50,85 C250,160 500,410 720,410 C940,410 1190,160 1490,85 L1490,700 L-50,700 Z" fill="url(#tk_flow_grad2)"/>

        <!-- 3. Wave Layer 3 (Samping +135px, Tengah +50px) -->
        <path d="M-50,220 C250,280 500,460 720,460 C940,460 1190,280 1490,220 L1490,700 L-50,700 Z" fill="url(#tk_flow_grad3)"/>

        <!-- 4. Wave Layer 4 (Samping +135px, Tengah +55px) -->
        <path d="M-50,355 C250,400 500,515 720,515 C940,515 1190,400 1490,355 L1490,700 L-50,700 Z" fill="url(#tk_flow_grad4)"/>

        <!-- 5. Wave Layer 5 (Samping +135px, Tengah +55px, Inti Depan) -->
        <path d="M-50,490 C250,510 500,570 720,570 C940,570 1190,510 1490,490 L1490,700 L-50,700 Z" fill="url(#tk_flow_grad5)"/>

        <!-- Bottom Blend Gradient Overlay -->
        <rect y="320" width="1440" height="280" fill="url(#tk_bottom_fade)"/>
      </g>
    </svg>
  </div>

  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-24 pb-0 relative z-10">

    <div class="max-w-[1140px] mx-auto text-center flex flex-col items-center">
      <p class="text-[13px] sm:text-[14px] font-semibold text-white/80 tracking-wide mb-6">
        {!! esc_html(wp_specialchars_decode($about['hero_sub'] ?? 'PT Karya Solusi Angkasa (Full Drone Solutions) · Pengalaman UAV Sejak 2012 · Yogyakarta')) !!}
      </p>
      <h1 class="text-[44px] sm:text-[60px] lg:text-[76px] font-semibold tracking-[-0.04em] text-white leading-[1.04] max-w-[1080px]">
        {!! nl2br(esc_html(wp_specialchars_decode($about['hero_title'] ?? "Advanced UAV Engineering,\nManufacturing & AI Technology."))) !!}
      </h1>
      <p class="mt-7 text-[18px] sm:text-[20px] lg:text-[21px] text-white/65 max-w-[880px] mx-auto leading-[1.65]">
        {!! nl2br(esc_html(wp_specialchars_decode($about['hero_desc'] ?? 'Berpengalaman di industri UAV sejak 2012 dan resmi berbadan hukum PT pada 2019. Kami merancang desain aerodinamis, struktur avionik in-house, rangka karbon lokal, serta analitik AI untuk kemandirian teknologi udara Indonesia.'))) !!}
      </p>
    </div>

    {{-- Hero image --}}
    <div class="mt-16 rounded-t-[2rem] overflow-hidden relative z-10" style="box-shadow: 0 -8px 48px rgba(0,0,0,0.3);">
      <img
        src="{{ !empty($about['hero_img']) ? $about['hero_img'] : fds_img('tk_hero', 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=1920&q=80') }}"
        alt="Tim & Workshop PT Karya Solusi Angkasa (FDS)"
        class="w-full h-[320px] sm:h-[480px] lg:h-[560px] object-cover"
      >
    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- STATS — Dark continuation                                 --}}
{{-- ========================================================== --}}
{{-- STATS BAR — Dark bar below hero                           --}}
{{-- ========================================================== --}}
@php
  $about_stats_list = [];
  for ($ai = 1; $ai <= 4; $ai++) {
      $aNum = trim((string)($about["stat{$ai}_num"] ?? ''));
      $aLbl = trim((string)($about["stat{$ai}_lbl"] ?? ''));
      if ($aNum !== '' || $aLbl !== '') {
          $about_stats_list[] = [
              'num' => $aNum,
              'lbl' => $aLbl,
          ];
      }
  }
  $total_about_stats = count($about_stats_list);
@endphp

@if($total_about_stats > 0)
<section class="bg-[#1d1d1f] border-b border-white/[0.08] py-16">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-2 {{ $total_about_stats === 1 ? 'md:grid-cols-1 max-w-sm mx-auto' : ($total_about_stats === 2 ? 'md:grid-cols-2 max-w-2xl mx-auto' : ($total_about_stats === 3 ? 'md:grid-cols-3 max-w-4xl mx-auto' : 'md:grid-cols-4')) }} gap-10 text-center">
      @foreach($about_stats_list as $ast)
      <div>
        @if(!empty($ast['num']))
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">{!! esc_html($ast['num']) !!}</p>
        @endif
        @if(!empty($ast['lbl']))
        <p class="text-[13px] font-medium text-white/40 mt-1">{!! esc_html($ast['lbl']) !!}</p>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif


{{-- ========================================================== --}}
{{-- STORY — White section, editorial two-column               --}}
{{-- ========================================================== --}}
<section class="bg-white py-24 sm:py-32">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-start">

      {{-- Left: headline sticky --}}
      <div class="lg:sticky lg:top-28">
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-5">
          {!! esc_html($about['story_badge'] ?? 'Cerita Kami') !!}
        </p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
          {!! $about['story_title'] ?? 'Rekayasa UAV mandiri untuk masa depan industri Indonesia.' !!}
        </h2>
        <div class="mt-8">
          <img src="{{ !empty($about['story_img']) ? $about['story_img'] : fds_img('tk_story', 'https://images.unsplash.com/photo-1527011046414-4781f1f94f8c?auto=format&fit=crop&w=800&q=80') }}"
               alt="Perjalanan dan Sejarah PT Karya Solusi Angkasa (FDS)"
               class="w-full h-auto block">
        </div>
      </div>

      {{-- Right: story text --}}
      <div class="space-y-8 text-[18px] text-[#515154] leading-[1.7]">
        @if (!empty($page_content))
          {!! apply_filters('the_content', $page_content) !!}
        @else
          <p>{!! $about['story_p1'] ?? '' !!}</p>
          <p>{!! $about['story_p2'] ?? '' !!}</p>
          <p>{!! $about['story_p3'] ?? '' !!}</p>
          <p>{!! $about['story_p4'] ?? '' !!}</p>
        @endif
        
        <div class="pt-4 border-t border-black/[0.06]">
          <a href="{{ $about['story_cta_url'] ?? '#mitra' }}" class="inline-flex items-center gap-1.5 text-[16px] font-semibold text-[#0066cc] hover:underline">
            {!! esc_html($about['story_cta_text'] ?? 'Lihat kemitraan strategis & portofolio klien') !!}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- EKOSISTEM TEKNOLOGI — Spektrum UAV & AI                    --}}
{{-- ========================================================== --}}
<section class="relative bg-gradient-to-br from-[#181a20] via-[#101216] to-[#0a0c10] py-24 sm:py-32 overflow-hidden border-b border-white/[0.06]">
  
  {{-- Vector Background: Diagonal Sweeping Fluid Horizon with Linear Bottom Gradient (Tanpa Lingkaran) --}}
  <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
    <div class="absolute inset-0 bg-gradient-to-t from-[#0066cc]/20 via-[#004080]/05 to-transparent pointer-events-none"></div>

    <svg class="absolute inset-0 w-full h-full object-cover opacity-80" viewBox="0 0 1440 600" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        {{-- Ambient Linear Gradient dari Bawah ke Atas (Bukan Lingkaran) --}}
        <linearGradient id="spektrum-ambient-bottom" x1="0%" y1="100%" x2="0%" y2="0%">
          <stop offset="0%" stop-color="#0066cc" stop-opacity="0.18" />
          <stop offset="50%" stop-color="#004080" stop-opacity="0.06" />
          <stop offset="100%" stop-color="#0a0c10" stop-opacity="0.0" />
        </linearGradient>

        {{-- Diagonal Wave Layers (Desain Asli Spektrum) --}}
        <linearGradient id="spektrum-wave-diag1" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#0066cc" stop-opacity="0.30" />
          <stop offset="50%" stop-color="#003388" stop-opacity="0.12" />
          <stop offset="100%" stop-color="#0a0c10" stop-opacity="0.0" />
        </linearGradient>

        <linearGradient id="spektrum-wave-diag2" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#1a85ff" stop-opacity="0.42" />
          <stop offset="60%" stop-color="#0066cc" stop-opacity="0.16" />
          <stop offset="100%" stop-color="#0a0c10" stop-opacity="0.0" />
        </linearGradient>

        <linearGradient id="spektrum-wave-diag3" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#2997ff" stop-opacity="0.52" />
          <stop offset="45%" stop-color="#0071e3" stop-opacity="0.22" />
          <stop offset="100%" stop-color="#0a0c10" stop-opacity="0.0" />
        </linearGradient>
      </defs>

      <!-- Ambient Light: Gradasi Linear Biasa dari Bawah ke Atas (Tanpa Lingkaran) -->
      <rect width="1440" height="600" fill="url(#spektrum-ambient-bottom)" />

      <!-- Diagonal Sweeping Fluid Fills (Desain Asli Spektrum dari Top-Left ke Bottom-Right) -->
      <path d="M0,0 C420,40 760,220 1020,420 C1200,540 1340,580 1440,600 L0,600 Z" fill="url(#spektrum-wave-diag1)" />
      <path d="M0,80 C360,120 680,300 940,470 C1140,570 1300,590 1440,600 L0,600 Z" fill="url(#spektrum-wave-diag2)" />
      <path d="M0,220 C300,240 580,380 820,510 C1040,600 1260,600 1440,600 L0,600 Z" fill="url(#spektrum-wave-diag3)" />
    </svg>
  </div>

  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 relative z-10">

    <div class="mb-16">
      <p class="text-[13px] font-semibold text-white/80 tracking-wide mb-4">
        {!! esc_html($about['spektrum_badge'] ?? 'Spektrum Teknologi UAV') !!}
      </p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-white leading-[1.1] max-w-[620px]">
        {!! esc_html($about['spektrum_title'] ?? 'Tiga arsitektur wahana udara untuk segala medan.') !!}
      </h2>
    </div>

    <div class="relative">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 relative z-10">
        {{-- Rotary Wing --}}
        <div class="group relative bg-white/[0.06] hover:bg-white/[0.11] backdrop-blur-xl backdrop-saturate-150 border border-white/15 hover:border-white/30 rounded-[2rem] p-8 sm:p-10 transition-all duration-300 shadow-[0_16px_36px_-10px_rgba(0,0,0,0.5),inset_0_1px_1px_0_rgba(255,255,255,0.35)] hover:shadow-[0_24px_48px_-12px_rgba(0,0,0,0.6),inset_0_1px_1.5px_0_rgba(255,255,255,0.5)] hover:-translate-y-1 overflow-hidden">
          {{-- Top Specular Edge Sheen (Liquid Glass Rim Light) --}}
          <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/60 to-transparent pointer-events-none opacity-70 group-hover:opacity-100 transition-opacity"></div>
          <h3 class="text-[20px] font-semibold text-white mb-3">{!! esc_html($about['spektrum1_title'] ?? 'Rotary Wing (Multirotor)') !!}</h3>
          <p class="text-[15px] text-white/75 leading-relaxed">
            {!! $about['spektrum1_desc'] ?? 'Kemampuan Vertical Takeoff and Landing (VTOL), kontrol posisi presisi tinggi, dan hovering super stabil.' !!}
          </p>
        </div>

        {{-- Fixed Wing --}}
        <div class="group relative bg-white/[0.06] hover:bg-white/[0.11] backdrop-blur-xl backdrop-saturate-150 border border-white/15 hover:border-white/30 rounded-[2rem] p-8 sm:p-10 transition-all duration-300 shadow-[0_16px_36px_-10px_rgba(0,0,0,0.5),inset_0_1px_1px_0_rgba(255,255,255,0.35)] hover:shadow-[0_24px_48px_-12px_rgba(0,0,0,0.6),inset_0_1px_1.5px_0_rgba(255,255,255,0.5)] hover:-translate-y-1 overflow-hidden">
          {{-- Top Specular Edge Sheen (Liquid Glass Rim Light) --}}
          <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/60 to-transparent pointer-events-none opacity-70 group-hover:opacity-100 transition-opacity"></div>
          <h3 class="text-[20px] font-semibold text-white mb-3">{!! esc_html($about['spektrum2_title'] ?? 'Fixed Wing (Sayap Tetap)') !!}</h3>
          <p class="text-[15px] text-white/75 leading-relaxed">
            {!! $about['spektrum2_desc'] ?? 'Dirancang untuk misi jarak jauh, daya tahan terbang tinggi (endurance), dan cakupan area pemetaan luas.' !!}
          </p>
        </div>

        {{-- Hybrid VTOL --}}
        <div class="group relative bg-white/[0.06] hover:bg-white/[0.11] backdrop-blur-xl backdrop-saturate-150 border border-white/15 hover:border-white/30 rounded-[2rem] p-8 sm:p-10 transition-all duration-300 shadow-[0_16px_36px_-10px_rgba(0,0,0,0.5),inset_0_1px_1px_0_rgba(255,255,255,0.35)] hover:shadow-[0_24px_48px_-12px_rgba(0,0,0,0.6),inset_0_1px_1.5px_0_rgba(255,255,255,0.5)] hover:-translate-y-1 overflow-hidden">
          {{-- Top Specular Edge Sheen (Liquid Glass Rim Light) --}}
          <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/60 to-transparent pointer-events-none opacity-70 group-hover:opacity-100 transition-opacity"></div>
          <h3 class="text-[20px] font-semibold text-white mb-3">{!! esc_html($about['spektrum3_title'] ?? 'Hybrid VTOL (DELTAV)') !!}</h3>
          <p class="text-[15px] text-white/75 leading-relaxed">
            {!! $about['spektrum3_desc'] ?? 'Menggabungkan fleksibilitas peluncuran vertikal tanpa landasan dengan kecepatan jelajah 15–22 m/s dan jangkauan 60 km.' !!}
          </p>
        </div>
      </div>
    </div>

  </div>
</section>


{{-- ========================================================== --}}
{{-- OUR ACTIVITY & KEMITRAAN — White, editorial list           --}}
{{-- ========================================================== --}}
<section id="aktivitas" class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start">

      <div class="lg:col-span-4 lg:sticky lg:top-28">
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">
          {!! esc_html($about['mitra_badge'] ?? 'Aktivitas Kami') !!}
        </p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-6">
          {!! esc_html($about['mitra_title'] ?? 'Aktivitas Kami') !!}
        </h2>
        <p class="text-[17px] text-[#515154] leading-relaxed">
          {!! esc_html($about['mitra_desc'] ?? 'Riset mandiri, inovasi manufaktur lokal, serta kolaborasi strategis bersama institusi nasional dan mitra internasional.') !!}
        </p>
      </div>

      <div class="lg:col-span-8 divide-y divide-black/[0.06]">
        @php
          $about_activities = function_exists('App\fds_get_about_activities') ? \App\fds_get_about_activities() : [];
        @endphp
        @foreach($about_activities as $act)
          @if(!empty($act['name']))
          <div class="py-8 grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-6 items-start">
            <div class="sm:col-span-5">
              @if(!empty($act['cat']))
              <p class="text-[12px] font-medium text-[#86868b] tracking-normal mb-1">{!! esc_html($act['cat']) !!}</p>
              @endif
              <h3 class="text-[18px] font-semibold text-[#1d1d1f] leading-snug">{!! esc_html($act['name']) !!}</h3>
            </div>
            <div class="sm:col-span-7">
              <p class="text-[15px] text-[#515154] leading-relaxed">{!! nl2br(esc_html($act['desc'])) !!}</p>
            </div>
          </div>
          @endif
        @endforeach
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- CERTIFICATIONS — Light gray bento                         --}}
{{-- ========================================================== --}}
<section class="bg-[#f5f5f7] py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-14">
      <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">
        {!! esc_html($about['certs_badge'] ?? 'Sertifikasi & Standar Mutu') !!}
      </p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] max-w-[600px]">
        {!! $about['certs_title'] ?? 'Standar mutu global, sertifikasi resmi nasional.' !!}
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">

      {{-- Card 1: TKDN 60,74% (Ambient Dual-Orb Gradient - Ref: Frame 3716.svg) --}}
      <div class="relative overflow-hidden bg-[#0066cc] rounded-[2rem] p-8 lg:p-9 flex flex-col justify-between min-h-[260px] transition-all duration-300 hover:-translate-y-1 group shadow-[0_4px_32px_rgba(0,102,204,0.25)]">
        
        {{-- Vector Background: Ambient Dual-Orb Gradient Glow (Ref: Frame 3716.svg) --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
          <svg class="absolute inset-0 w-full h-full transition-transform duration-700 group-hover:scale-105" viewBox="0 0 335 160" preserveAspectRatio="xMaxYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <linearGradient id="orb1-tkdn" x1="323.781" y1="-61" x2="286" y2="80" gradientUnits="userSpaceOnUse">
                <stop offset="0.28" stop-color="white" stop-opacity="0"/>
                <stop offset="1" stop-color="white" stop-opacity="1"/>
              </linearGradient>
              <linearGradient id="orb2-tkdn" x1="239.637" y1="64.8558" x2="201.856" y2="205.856" gradientUnits="userSpaceOnUse">
                <stop offset="0.28" stop-color="white" stop-opacity="0"/>
                <stop offset="1" stop-color="white" stop-opacity="1"/>
              </linearGradient>
            </defs>
            <g opacity="0.22">
              <circle cx="286" cy="5" r="75" fill="url(#orb1-tkdn)"/>
              <circle cx="201.856" cy="130.856" r="75" transform="rotate(-165 201.856 130.856)" fill="url(#orb2-tkdn)"/>
            </g>
          </svg>
        </div>

        <div class="relative z-10">
          <p class="text-[13px] font-semibold text-white/80 tracking-wide mb-6">{!! esc_html($about['cert1_badge'] ?? 'Kemenperin RI') !!}</p>
        </div>
        <div class="relative z-10">
          <p class="text-[44px] sm:text-[48px] font-bold text-white tracking-[-0.03em] leading-tight">{!! esc_html($about['cert1_val'] ?? '60,74%') !!}</p>
          <p class="text-[14px] text-white/85 mt-3 leading-relaxed">{!! esc_html($about['cert1_desc'] ?? 'Nilai TKDN + Bobot Manfaat Perusahaan (BMP) tertinggi di segmen drone industri buatan lokal.') !!}</p>
        </div>
      </div>

      {{-- Card 2: ISO & SNI (Ambient Dual-Orb Gradient - Ref: Frame 3716.svg) --}}
      <div class="relative overflow-hidden bg-white rounded-[2rem] p-8 lg:p-9 flex flex-col justify-between min-h-[260px] border border-black/[0.06] transition-all duration-300 hover:-translate-y-1 group shadow-[0_2px_24px_rgba(0,0,0,0.06)]">
        
        {{-- Vector Background: Ambient Dual-Orb Gradient Glow (Ref: Frame 3716.svg) --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
          <svg class="absolute inset-0 w-full h-full transition-transform duration-700 group-hover:scale-105" viewBox="0 0 335 160" preserveAspectRatio="xMaxYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <linearGradient id="orb1-iso" x1="323.781" y1="-61" x2="286" y2="80" gradientUnits="userSpaceOnUse">
                <stop offset="0.28" stop-color="#0066cc" stop-opacity="0"/>
                <stop offset="1" stop-color="#0066cc" stop-opacity="1"/>
              </linearGradient>
              <linearGradient id="orb2-iso" x1="239.637" y1="64.8558" x2="201.856" y2="205.856" gradientUnits="userSpaceOnUse">
                <stop offset="0.28" stop-color="#0066cc" stop-opacity="0"/>
                <stop offset="1" stop-color="#0066cc" stop-opacity="1"/>
              </linearGradient>
            </defs>
            <g opacity="0.14">
              <circle cx="286" cy="5" r="75" fill="url(#orb1-iso)"/>
              <circle cx="201.856" cy="130.856" r="75" transform="rotate(-165 201.856 130.856)" fill="url(#orb2-iso)"/>
            </g>
          </svg>
        </div>

        <div class="relative z-10">
          <p class="text-[13px] font-semibold text-[#86868b] tracking-wide mb-6">{!! esc_html($about['cert2_badge'] ?? 'Standar Produk & Manajemen') !!}</p>
        </div>
        <div class="relative z-10">
          <p class="text-[44px] sm:text-[48px] font-bold text-[#1d1d1f] tracking-[-0.03em] leading-tight">{!! esc_html($about['cert2_val'] ?? 'ISO & SNI') !!}</p>
          <p class="text-[14px] text-[#515154] mt-3 leading-relaxed">{!! esc_html($about['cert2_desc'] ?? 'Sertifikasi ISO 9001:2015 (Manajemen Mutu) dan SNI 9199:2023 (Standar Nasional Drone Pertanian).') !!}</p>
        </div>
      </div>

      {{-- Card 3: 24/7 Service (Ambient Dual-Orb Gradient - Ref: Frame 3716.svg) --}}
      <div class="relative overflow-hidden bg-gradient-to-br from-[#0d2342] via-[#09182d] to-[#050f1d] rounded-[2rem] p-8 lg:p-9 flex flex-col justify-between min-h-[260px] border border-white/[0.08] transition-all duration-300 hover:-translate-y-1 group shadow-[0_4px_30px_rgba(0,0,0,0.18)]">
        
        {{-- Vector Background: Ambient Dual-Orb Gradient Glow (Ref: Frame 3716.svg) --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
          <svg class="absolute inset-0 w-full h-full transition-transform duration-700 group-hover:scale-105" viewBox="0 0 335 160" preserveAspectRatio="xMaxYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <linearGradient id="orb1-srv" x1="323.781" y1="-61" x2="286" y2="80" gradientUnits="userSpaceOnUse">
                <stop offset="0.15" stop-color="#0066cc" stop-opacity="0.1"/>
                <stop offset="1" stop-color="#0077ed" stop-opacity="1"/>
              </linearGradient>
              <linearGradient id="orb2-srv" x1="239.637" y1="64.8558" x2="201.856" y2="205.856" gradientUnits="userSpaceOnUse">
                <stop offset="0.15" stop-color="#0066cc" stop-opacity="0.1"/>
                <stop offset="1" stop-color="#0077ed" stop-opacity="1"/>
              </linearGradient>
            </defs>
            <g opacity="0.65">
              <circle cx="286" cy="5" r="75" fill="url(#orb1-srv)"/>
              <circle cx="201.856" cy="130.856" r="75" transform="rotate(-165 201.856 130.856)" fill="url(#orb2-srv)"/>
            </g>
          </svg>
        </div>

        <div class="relative z-10">
          <p class="text-[13px] font-semibold text-white/70 tracking-wide mb-6">{!! esc_html($about['cert3_badge'] ?? 'Jaminan Layanan') !!}</p>
        </div>
        <div class="relative z-10">
          <p class="text-[44px] sm:text-[48px] font-bold text-white tracking-[-0.03em] leading-tight">{!! esc_html($about['cert3_val'] ?? '24/7') !!}</p>
          <p class="text-[14px] text-white/80 mt-3 leading-relaxed">{!! esc_html($about['cert3_desc'] ?? 'Dukungan servis, suku cadang asli, dan sertifikasi pilot resmi di seluruh Indonesia.') !!}</p>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- CTA & WORKSHOP — Ekosistem Bento Card Vector & Dark Theme  --}}
{{-- ========================================================== --}}
@php
  $global_c  = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_entitas = $global_c['company_name'] ?? ($about['info_entitas'] ?? 'PT Karya Solusi Angkasa (Full Drone Solutions)');
  $c_alamat  = $global_c['address'] ?? ($about['info_alamat'] ?? 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281');
  $c_email   = $global_c['email'] ?? ($about['info_email'] ?? 'marketing@fulldronesolutions.com');
  $c_phone   = $global_c['phone'] ?? '+62 8112 748 882';
  $c_wa_link      = $global_c['wa_link'] ?? 'https://wa.me/628112748882';
  $c_maps         = $global_c['maps_url'] ?? ($about['info_maps'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid');
  $show_map_about = isset($global_c['show_map_about']) ? (bool) $global_c['show_map_about'] : (isset($about['show_map_about']) ? (bool) $about['show_map_about'] : (bool) get_option('fds_show_map_about', 1));
@endphp
<section class="relative bg-gradient-to-br from-[#1c1f26] via-[#12141a] to-[#0a0c10] py-24 sm:py-32 overflow-hidden border-t border-white/[0.08]">
  
  {{-- Vector Background: Pure Organic Wave Fills (Matching Section Untuk Siapa) --}}
  <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
    <svg class="w-full h-full" viewBox="0 0 1000 400" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
      <defs>
        <linearGradient id="about-wave-fill1" x1="0%" y1="100%" x2="100%" y2="0%">
          <stop offset="0%" stop-color="#2563eb" stop-opacity="0.48" />
          <stop offset="60%" stop-color="#1d4ed8" stop-opacity="0.22" />
          <stop offset="100%" stop-color="#0f172a" stop-opacity="0.0" />
        </linearGradient>
        <linearGradient id="about-wave-fill2" x1="0%" y1="100%" x2="100%" y2="0%">
          <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.38" />
          <stop offset="70%" stop-color="#2563eb" stop-opacity="0.16" />
          <stop offset="100%" stop-color="#0f172a" stop-opacity="0.0" />
        </linearGradient>
        <linearGradient id="about-wave-fill3" x1="100%" y1="100%" x2="0%" y2="0%">
          <stop offset="0%" stop-color="#60a5fa" stop-opacity="0.42" />
          <stop offset="50%" stop-color="#2563eb" stop-opacity="0.22" />
          <stop offset="100%" stop-color="#0f172a" stop-opacity="0.0" />
        </linearGradient>
      </defs>

      <!-- Pure Fluid Wave Fills: Sedikit di kiri bawah, mengalir elegan naik ke kanan -->
      <path d="M0,340 C280,320 470,220 780,150 C900,120 960,100 1000,80 L1000,400 L0,400 Z" fill="url(#about-wave-fill1)" />
      <path d="M0,380 C360,370 580,280 860,190 C930,160 970,140 1000,120 L1000,400 L0,400 Z" fill="url(#about-wave-fill2)" />
      <path d="M440,400 C590,340 780,300 1000,220 L1000,400 Z" fill="url(#about-wave-fill3)" />
    </svg>
  </div>

  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 w-full relative z-10">
    
    {{-- 2-Column Split: Headline & Directory List --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 items-start">

      {{-- Left: Massive Editorial Headline & Action Buttons (Col 7) --}}
      <div class="lg:col-span-7">
        <p class="text-[13px] font-semibold text-white/80 tracking-wide mb-6">
          Kemitraan &amp; Pengadaan Korporasi
        </p>
        <h2 class="text-[38px] sm:text-[52px] lg:text-[60px] font-semibold tracking-[-0.035em] text-white leading-[1.06] mb-8">
          {!! esc_html($about['cta_title'] ?? 'Siap bermitra dengan PT Karya Solusi Angkasa?') !!}
        </h2>
        <p class="text-[18px] sm:text-[20px] text-white/60 leading-[1.65] max-w-[580px] mb-12">
          {!! esc_html($about['cta_desc'] ?? 'Baik instansi pemerintah, BUMN, perkebunan agrikultur besar, atau mitra industri — tim engineering kami siap memberikan solusi terbaik.') !!}
        </p>
        <div class="flex flex-wrap items-center gap-6">
          <a href="{{ $about['cta_btn1_url'] ?? home_url('/#kontak') }}"
             class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.98] text-[#1d1d1f] text-[16px] font-semibold px-8 py-4 rounded-full transition-all duration-150 shadow-md">
            {!! esc_html($about['cta_btn1_text'] ?? 'Mulai Konsultasi') !!}
          </a>
          @php
            $btn2_label = $about['cta_btn2_text'] ?? 'Baca Studi Kasus';
            $btn2_label = preg_replace('/(&rsaquo;|&gt;|&raquo;|›|>|»|\s)+$/u', '', html_entity_decode($btn2_label, ENT_QUOTES, 'UTF-8'));
          @endphp
          <a href="{{ $about['cta_btn2_url'] ?? home_url('/blog') }}"
             class="inline-flex items-center text-white/70 text-[16px] font-medium hover:text-white transition-colors gap-2 group">
            <span>{{ $btn2_label }}</span>
            <span class="group-hover:translate-x-1 transition-transform duration-150">&rsaquo;</span>
          </a>
        </div>
      </div>

      {{-- Right: Editorial Directory & Workshop Details (Col 5) --}}
      <div class="lg:col-span-5 border-t lg:border-t-0 lg:border-l border-white/[0.1] pt-12 lg:pt-0 lg:pl-16">
        <h3 class="text-[14px] font-semibold text-white/60 tracking-normal mb-8">
          {!! esc_html($about['info_title'] ?? 'Kantor Pusat & Workshop') !!}
        </h3>

        <div class="divide-y divide-white/[0.08]">
          
          <div class="pb-7">
            <p class="text-[13px] font-semibold text-white/60 mb-1">Entitas Resmi</p>
            <p class="text-[17px] font-medium text-white leading-snug">
              {!! esc_html($c_entitas) !!}
            </p>
          </div>

          <div class="py-7">
            <p class="text-[13px] font-semibold text-white/40 mb-1">Alamat Workshop</p>
            <p class="text-[16px] font-medium text-white/90 leading-relaxed">
              {!! esc_html($c_alamat) !!}
            </p>
            <p class="text-[13px] text-white/50 mt-1">Fasilitas Riset, Desain Aerodinamis &amp; Manufaktur UAV</p>
          </div>

          <div class="py-7">
            <p class="text-[13px] font-semibold text-white/40 mb-1">Email Resmi</p>
            <a href="mailto:{{ esc_attr($c_email) }}" 
               class="text-[16px] font-medium text-white hover:text-[#6e9fd4] transition-colors block">
              {!! esc_html($c_email) !!}
            </a>
          </div>

          <div class="pt-7">
            <p class="text-[13px] font-semibold text-white/40 mb-1">Kontak &amp; Layanan Cepat</p>
            <div class="flex items-center gap-3 mt-1">
              <a href="tel:{{ preg_replace('/[^0-9+]/', '', $c_phone) }}" class="text-[16px] font-medium text-white hover:text-[#6e9fd4] transition-colors">
                {!! esc_html($c_phone) !!}
              </a>
              <span class="text-white/20">&middot;</span>
              <a href="{{ esc_url($c_wa_link) }}" target="_blank" rel="noopener" class="text-[13px] font-medium text-[#25D366] hover:underline inline-flex items-center gap-1">
                WhatsApp <span>&rsaquo;</span>
              </a>
            </div>
          </div>

        </div>
      </div>

    </div>

  </div>
</section>

@if($show_map_about && !empty($c_maps))
{{-- Full-width Map Media Block — Terpisah Namun Mepet --}}
<section class="w-full overflow-hidden border-t border-white/[0.08] relative bg-[#0c1018]" style="height: 520px; max-height: 600px;">
  <iframe 
    src="{{ esc_url($c_maps) }}" 
    width="100%" 
    height="100%" 
    style="border:0;" 
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="strict-origin-when-cross-origin"
    title="Lokasi Full Drone Solutions Sleman Yogyakarta">
  </iframe>
</section>
@endif

@endsection
