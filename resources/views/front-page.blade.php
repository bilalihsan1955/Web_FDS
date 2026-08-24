@extends('layouts.app')

@section('content')

@php
  $hp = function_exists('App\fds_get_homepage_content') ? \App\fds_get_homepage_content() : [];
@endphp

{{-- ========================================================== --}}
{{-- 1. HERO — Dinamis dari WP Admin Konten Beranda            --}}
{{-- ========================================================== --}}
<section id="overview" class="pt-[52px] bg-[#f5f5f7] overflow-hidden">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-20 pb-0 text-center">

    @if(!empty($hp['hero_badge']))
    <p class="inline-block text-[13px] font-semibold text-[#0066cc] mb-5 tracking-wide">
      {!! esc_html($hp['hero_badge']) !!}
    </p>
    @endif

    <h1 class="text-[44px] sm:text-[58px] lg:text-[72px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.05] max-w-[820px] mx-auto">
      {!! nl2br(esc_html($hp['hero_title'] ?? "Solusi Drone Industrial\nuntuk Setiap Sektor.")) !!}
    </h1>

    <p class="mt-6 text-[18px] sm:text-[20px] text-[#515154] font-normal max-w-[580px] mx-auto leading-[1.55]">
      {!! nl2br(esc_html($hp['hero_desc'] ?? 'Teknologi udara berstandar industri, diproduksi lokal.')) !!}
    </p>

    <div class="mt-8 flex items-center justify-center gap-4 flex-wrap">
      <a href="{{ esc_url($hp['hero_cta1_url'] ?? '#solusi') }}" class="inline-flex items-center bg-[#0066cc] hover:bg-[#0055b0] active:scale-[0.97] text-white text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150 shadow-md shadow-[#0066cc]/20">
        {!! esc_html($hp['hero_cta1_text'] ?? 'Jelajahi Solusi Kami') !!}
      </a>
      <a href="{{ esc_url($hp['hero_cta2_url'] ?? '#kontak') }}" class="inline-flex items-center text-[#0066cc] text-[15px] font-medium hover:underline gap-1 group">
        {!! esc_html($hp['hero_cta2_text'] ?? 'Konsultasi Enterprise') !!}
        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    @php
      $hero_slides = \App\fds_get_hero_slides();
    @endphp

    {{-- ── HERO SLIDER CAROUSEL ──────────────────────────────── --}}
    <div id="fds-hero-slider" class="mt-14 rounded-t-[2rem] overflow-hidden shadow-2xl shadow-black/10 relative group bg-[#000] select-none" data-slide-count="{{ count($hero_slides) }}">
      
      {{-- Slides Wrapper --}}
      <div class="relative w-full h-[360px] sm:h-[520px] lg:h-[620px] overflow-hidden">
        @foreach($hero_slides as $i => $slide)
        <div class="fds-hero-slide absolute inset-0 w-full h-full transition-all duration-1000 ease-out {{ $i === 0 ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-105 pointer-events-none z-0' }}" data-index="{{ $i }}">
          <img
            src="{{ $slide['url'] }}"
            alt="{{ $slide['alt'] ?: 'Full Drone Solutions' }}"
            class="w-full h-full object-cover"
            loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
          >
          @if(!empty($slide['title']))
          <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent p-6 sm:p-10 text-left">
            <p class="text-white text-[16px] sm:text-[20px] font-semibold tracking-[-0.01em] drop-shadow-md">
              {{ $slide['title'] }}
            </p>
          </div>
          @endif
        </div>
        @endforeach
      </div>

      {{-- Prev & Next Navigation Buttons (Visible on hover on desktop) --}}
      @if(count($hero_slides) > 1)
      <button type="button" id="fds-slider-prev" class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/80 hover:bg-white text-[#1d1d1f] flex items-center justify-center backdrop-blur-md shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 hover:scale-105 active:scale-95 focus:outline-none" aria-label="Previous Slide">
        <svg class="w-5 h-5 -translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <button type="button" id="fds-slider-next" class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/80 hover:bg-white text-[#1d1d1f] flex items-center justify-center backdrop-blur-md shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 hover:scale-105 active:scale-95 focus:outline-none" aria-label="Next Slide">
        <svg class="w-5 h-5 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
      </button>

      {{-- Dot Indicators --}}
      <div class="absolute bottom-5 inset-x-0 flex items-center justify-center gap-2 z-20 pointer-events-auto">
        @foreach($hero_slides as $i => $slide)
        <button type="button" class="fds-slider-dot h-2 rounded-full transition-all duration-300 {{ $i === 0 ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/80' }}" data-dot-index="{{ $i }}" aria-label="Go to slide {{ $i + 1 }}"></button>
        @endforeach
      </div>
      @endif

    </div>

    @if(count($hero_slides) > 1)
    <script>
      (function() {
        const slider = document.getElementById('fds-hero-slider');
        if (!slider) return;
        
        const slides = slider.querySelectorAll('.fds-hero-slide');
        const dots = slider.querySelectorAll('.fds-slider-dot');
        const prevBtn = document.getElementById('fds-slider-prev');
        const nextBtn = document.getElementById('fds-slider-next');
        const total = slides.length;
        let current = 0;
        let timer = null;
        const interval = 5000; // 5 detik per slide

        function showSlide(index) {
          if (index < 0) index = total - 1;
          if (index >= total) index = 0;
          current = index;

          slides.forEach((slide, i) => {
            if (i === current) {
              slide.classList.remove('opacity-0', 'scale-105', 'pointer-events-none', 'z-0');
              slide.classList.add('opacity-100', 'scale-100', 'z-10');
            } else {
              slide.classList.remove('opacity-100', 'scale-100', 'z-10');
              slide.classList.add('opacity-0', 'scale-105', 'pointer-events-none', 'z-0');
            }
          });

          dots.forEach((dot, i) => {
            if (i === current) {
              dot.classList.remove('w-2', 'bg-white/50');
              dot.classList.add('w-8', 'bg-white');
            } else {
              dot.classList.remove('w-8', 'bg-white');
              dot.classList.add('w-2', 'bg-white/50');
            }
          });
        }

        function nextSlide() {
          showSlide(current + 1);
        }

        function prevSlide() {
          showSlide(current - 1);
        }

        function startAutoPlay() {
          stopAutoPlay();
          timer = setInterval(nextSlide, interval);
        }

        function stopAutoPlay() {
          if (timer) clearInterval(timer);
        }

        if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); startAutoPlay(); });
        if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); startAutoPlay(); });

        dots.forEach(dot => {
          dot.addEventListener('click', (e) => {
            const idx = parseInt(e.currentTarget.getAttribute('data-dot-index'), 10);
            showSlide(idx);
            startAutoPlay();
          });
        });

        // Pause on mouse hover
        slider.addEventListener('mouseenter', stopAutoPlay);
        slider.addEventListener('mouseleave', startAutoPlay);

        // Touch swipe support on mobile
        let touchStartX = 0;
        let touchEndX = 0;
        slider.addEventListener('touchstart', (e) => {
          touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        slider.addEventListener('touchend', (e) => {
          touchEndX = e.changedTouches[0].screenX;
          if (touchStartX - touchEndX > 50) {
            nextSlide();
            startAutoPlay();
          } else if (touchEndX - touchStartX > 50) {
            prevSlide();
            startAutoPlay();
          }
        }, { passive: true });

        // Start autoplay
        startAutoPlay();
      })();
    </script>
    @endif
  </div>
</section>


{{-- ========================================================== --}}
{{-- 2. MITRA — Infinite marquee scroll                        --}}
{{-- ========================================================== --}}
@php
  $mitra_posts = get_posts(['post_type'=>'mitra','numberposts'=>-1,'orderby'=>'menu_order','order'=>'ASC']);
  $mitra_with_logo = array_filter($mitra_posts, fn($m) => get_the_post_thumbnail_url($m->ID, 'medium'));
@endphp

@if(!empty($mitra_with_logo))
<section class="bg-white border-b border-black/[0.06] py-14 overflow-hidden">
  <div class="text-center mb-10">
    @php
      $mitra_title = $hp['mitra_heading'] ?? 'Dipercaya oleh Lembaga Nasional & Internasional';
      if (mb_strtoupper($mitra_title) === $mitra_title && mb_strlen($mitra_title) > 5) {
          $mitra_title = 'Dipercaya oleh Lembaga Nasional & Internasional';
      }
    @endphp
    <p class="text-[13px] font-semibold text-[#86868b] tracking-wide">
      {!! esc_html($mitra_title) !!}
    </p>
  </div>

  {{-- Marquee track --}}
  <div class="relative">
    {{-- Fade mask kiri & kanan --}}
    <div class="absolute left-0 top-0 bottom-0 w-32 z-10 pointer-events-none"
         style="background: linear-gradient(to right, white 0%, transparent 100%);"></div>
    <div class="absolute right-0 top-0 bottom-0 w-32 z-10 pointer-events-none"
         style="background: linear-gradient(to left, white 0%, transparent 100%);"></div>

    {{-- Scroll track --}}
    <div class="flex" style="animation: fds-marquee 35s linear infinite;">
      @foreach($mitra_with_logo as $mitra)
        @php $logo = get_the_post_thumbnail_url($mitra->ID, 'medium'); @endphp
        <div class="flex-shrink-0 flex items-center justify-center px-10">
          <img src="{{ $logo }}"
               alt="{{ esc_attr($mitra->post_title) }}"
               class="h-24 w-auto object-contain"
               title="{{ $mitra->post_title }}">
        </div>
      @endforeach
      @foreach($mitra_with_logo as $mitra)
        @php $logo = get_the_post_thumbnail_url($mitra->ID, 'medium'); @endphp
        <div class="flex-shrink-0 flex items-center justify-center px-10" aria-hidden="true">
          <img src="{{ $logo }}" alt="" class="h-24 w-auto object-contain">
        </div>
      @endforeach
    </div>
  </div>

  <style>
    @keyframes fds-marquee {
      0%   { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    @media (prefers-reduced-motion: reduce) {
      [style*="fds-marquee"] { animation: none; }
    }
  </style>
</section>
@endif



{{-- ========================================================== --}}
{{-- 3. SOLUSI INDUSTRI — 100% Dinamis dari WP Admin            --}}
{{-- ========================================================== --}}
@php
  $solusi_data = function_exists('App\fds_get_solusi_data') ? \App\fds_get_solusi_data() : [
    'badge' => 'Solusi Industri FDS',
    'title' => 'Satu platform. Berbagai industri strategis.',
    'desc'  => 'Solusi rekayasa UAV terintegrasi hardware, software FDS STATION, sensor AI, dan layanan operasional bersertifikasi untuk efisiensi maksimal di lapangan.',
    'cards' => [],
  ];
@endphp
<section id="solusi" class="bg-[#1d1d1f] py-24 sm:py-32">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-6">
      <div>
        <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-4">{!! esc_html($solusi_data['badge']) !!}</p>
        <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-white leading-[1.1] max-w-[640px]">
          {!! esc_html($solusi_data['title']) !!}
        </h2>
      </div>
      <p class="text-[16px] text-white/50 max-w-[460px] leading-relaxed">
        {!! nl2br(esc_html($solusi_data['desc'])) !!}
      </p>
    </div>

    {{-- Dynamic Solution grid from WP Admin (Fixed 4 Columns on Desktop) --}}
    @if(!empty($solusi_data['cards']))
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

      @foreach($solusi_data['cards'] as $card)
      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] overflow-hidden group hover:bg-white/[0.09] transition-all duration-300 flex flex-col justify-between">
        <div>
          <div class="h-[210px] overflow-hidden relative bg-[#1e293b]">
            <img src="{{ esc_url($card['image'] ?: 'https://picsum.photos/seed/fds-solution-' . $loop->index . '/800/500') }}" 
                 alt="{{ esc_attr($card['title']) }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
          </div>
          <div class="p-7 pb-4">
            <h3 class="text-[19px] font-semibold text-white mb-2 leading-snug">{!! esc_html($card['title']) !!}</h3>
            <p class="text-[13px] text-white/60 leading-relaxed mb-4">
              {!! nl2br(esc_html($card['desc'])) !!}
            </p>
          </div>
        </div>
        <div class="p-7 pt-0">
          <div class="pt-4 border-t border-white/[0.08] flex items-center justify-between">
            @if(!empty($card['tag']))
            <span class="text-[11px] font-medium text-white/40 font-mono">{!! esc_html($card['tag']) !!}</span>
            @else
            <span></span>
            @endif
            <a href="{{ esc_url($card['link_url'] ?: '#kontak') }}" class="text-[13px] font-semibold text-[#6e9fd4] hover:underline inline-flex items-center gap-1">
              {!! esc_html($card['link_text'] ?: 'Pelajari Selengkapnya') !!} <span>&rsaquo;</span>
            </a>
          </div>
        </div>
      </div>
      @endforeach

    </div>
    @endif

  </div>
</section>


{{-- ========================================================== --}}
{{-- 4. KEUNGGULAN &mdash; Bento Grid FDS Company Strengths         --}}
{{-- ========================================================== --}}
<section id="keunggulan" class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-16">
      <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">{!! esc_html($hp['keunggulan_badge'] ?? 'Mengapa FDS') !!}</p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] max-w-[620px]">
        {!! esc_html($hp['keunggulan_title'] ?? 'Keunggulan yang tidak bisa dikompromikan.') !!}
      </h2>
    </div>

    {{-- Bento Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">

      {{-- Large hero card — local manufacturing --}}
      <div class="lg:col-span-8 bg-[#f5f5f7] rounded-[2rem] overflow-hidden relative min-h-[340px] group"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <div class="absolute inset-0 z-0">
          <img src="{{ fds_img('keunggulan', 'https://picsum.photos/seed/fds-workshop-factory/1200/600') }}"
               alt="Pabrik &amp; Workshop FDS"
               class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700">
          <div class="absolute inset-0 bg-gradient-to-t from-[#1d1d1f]/80 via-[#1d1d1f]/20 to-transparent"></div>
        </div>
        <div class="relative z-10 h-full flex flex-col justify-end p-8 sm:p-10">
          <p class="text-[12px] font-bold text-white/50 tracking-wide mb-3">{!! esc_html($hp['keunggulan_card1_badge'] ?? 'Rekayasa & Manufaktur') !!}</p>
          <h3 class="text-[28px] sm:text-[34px] font-semibold text-white tracking-[-0.02em] leading-[1.1] mb-2">
            {!! nl2br(esc_html($hp['keunggulan_card1_title'] ?? "Desain Aerodinamis &\nAvionik In-House.")) !!}
          </h3>
          <p class="text-[15px] text-white/70 max-w-[420px] leading-relaxed">
            {!! nl2br(esc_html($hp['keunggulan_card1_desc'] ?? 'Rangka karbon komposit lokal, avionik in-house, dan integrasi payload kustom di workshop PT Karya Solusi Angkasa (FDS).')) !!}
          </p>
        </div>
      </div>

      {{-- TKDN Card --}}
      <div class="lg:col-span-4 bg-[#0066cc] rounded-[2rem] p-8 flex flex-col justify-between min-h-[200px]"
           style="box-shadow: 0 2px 24px rgba(0,102,204,0.2);">
        <p class="text-[12px] font-bold text-white/60 tracking-wide">{!! esc_html($hp['keunggulan_card2_badge'] ?? 'Sertifikasi TKDN + BMP') !!}</p>
        <div>
          <p class="text-[54px] sm:text-[64px] font-semibold text-white tracking-[-0.04em] leading-none">{!! esc_html($hp['keunggulan_card2_stat'] ?? '60,74%') !!}</p>
          <p class="text-[14px] text-white/70 mt-2">{!! esc_html($hp['keunggulan_card2_desc'] ?? 'Nilai TKDN + Bobot Manfaat Perusahaan resmi Kementerian Perindustrian RI.') !!}</p>
        </div>
      </div>

      {{-- Software — GCS App --}}
      <div class="lg:col-span-4 bg-[#1d1d1f] rounded-[2rem] p-8 min-h-[200px] flex flex-col justify-between"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.08);">
        <div>
          <p class="text-[12px] font-semibold text-[#6e9fd4] tracking-wide mb-4">{!! esc_html($hp['keunggulan_card3_badge'] ?? 'Software') !!}</p>
          <h3 class="text-[22px] font-semibold text-white tracking-[-0.02em] mb-2">{!! nl2br(esc_html($hp['keunggulan_card3_title'] ?? "FDS STATION\nGround Control GCS")) !!}</h3>
          <p class="text-[14px] text-white/50 leading-relaxed">{!! esc_html($hp['keunggulan_card3_desc'] ?? 'Perencanaan misi otomatis dan pemantauan real-time berbahasa Indonesia.') !!}</p>
        </div>
      </div>

      {{-- Standar Mutu ISO & SNI --}}
      <div class="lg:col-span-4 bg-[#f5f5f7] rounded-[2rem] p-8 min-h-[200px] flex flex-col justify-between"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <div>
          <p class="text-[12px] font-bold text-[#86868b] tracking-wide mb-4">{!! esc_html($hp['keunggulan_card4_badge'] ?? 'Standar & Mutu') !!}</p>
          <p class="text-[44px] font-semibold text-[#1d1d1f] tracking-[-0.04em] leading-none">{!! esc_html($hp['keunggulan_card4_stat'] ?? 'ISO & SNI') !!}</p>
          <p class="text-[14px] text-[#515154] mt-2 leading-relaxed">{!! esc_html($hp['keunggulan_card4_desc'] ?? 'Tersertifikasi ISO 9001:2015 dan Standar Nasional Indonesia SNI 9199:2023.') !!}</p>
        </div>
      </div>

      {{-- After-Sales --}}
      <div class="lg:col-span-4 bg-[#f5f5f7] rounded-[2rem] p-8 min-h-[200px] flex flex-col justify-between"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <div>
          <p class="text-[12px] font-bold text-[#86868b] tracking-wide mb-4">{!! esc_html($hp['keunggulan_card5_badge'] ?? 'After-Sales') !!}</p>
          <h3 class="text-[22px] font-semibold text-[#1d1d1f] tracking-[-0.02em] mb-2">{!! esc_html($hp['keunggulan_card5_title'] ?? 'Purna Jual & Suku Cadang') !!}</h3>
          <p class="text-[14px] text-[#515154] leading-relaxed">{!! esc_html($hp['keunggulan_card5_desc'] ?? 'Pelatihan pilot berlisensi, servis berkala, dan spare parts siap kirim dari Yogyakarta.') !!}</p>
        </div>
      </div>

      {{-- 2012 Experience --}}
      <div class="lg:col-span-4 sm:col-span-2 bg-[#e8f0fe] rounded-[2rem] p-8 min-h-[200px] flex flex-col justify-between"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.04);">
        <div>
          <p class="text-[12px] font-bold text-[#0066cc] tracking-wide mb-4">{!! esc_html($hp['keunggulan_card6_badge'] ?? 'Pengalaman Industri') !!}</p>
          <p class="text-[54px] font-semibold text-[#1d1d1f] tracking-[-0.04em] leading-none">{!! esc_html($hp['keunggulan_card6_stat'] ?? '2012') !!}</p>
          <p class="text-[14px] text-[#515154] mt-2 leading-relaxed">{!! esc_html($hp['keunggulan_card6_desc'] ?? 'Berpengalaman di industri UAV sejak 2012, resmi berbadan hukum PT sejak 2019.') !!}</p>
        </div>
      </div>

      {{-- Multi-Sector --}}
      <div class="lg:col-span-8 bg-[#f5f5f7] rounded-[2rem] p-8 sm:p-10 min-h-[160px] flex flex-col sm:flex-row items-center gap-8"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <div class="flex-1">
          <p class="text-[12px] font-bold text-[#86868b] tracking-wide mb-3">{!! esc_html($hp['keunggulan_card7_badge'] ?? 'Cakupan Industri') !!}</p>
          <h3 class="text-[24px] font-semibold text-[#1d1d1f] tracking-[-0.02em]">{!! esc_html($hp['keunggulan_card7_title'] ?? 'Satu ekosistem. Banyak solusi.') !!}</h3>
          <p class="text-[15px] text-[#515154] mt-2 leading-relaxed max-w-[420px]">{!! esc_html($hp['keunggulan_card7_desc'] ?? 'Agrikultur, pemetaan topografi, inspeksi infrastruktur, kehutanan, dan pertambangan.') !!}</p>
        </div>
        <div class="grid grid-cols-3 gap-3 flex-shrink-0">
          @foreach(['Agri', 'Mapping', 'Inspeksi', 'Tambang', 'Hutan', 'BUMN'] as $sector)
            <div class="bg-white rounded-xl px-3 py-2 text-[12px] font-semibold text-[#515154] text-center"
                 style="box-shadow: 0 1px 8px rgba(0,0,0,0.06);">{{ $sector }}</div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- 5. PRODUCT LINEUP — Header Dinamis, List dari CPT Drone   --}}
{{-- ========================================================== --}}
<section id="produk" class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-12 flex flex-wrap items-end justify-between gap-6">
      <div>
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">{!! esc_html($hp['produk_badge'] ?? 'Lini Produk Drone') !!}</p>
        <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
          {!! esc_html($hp['produk_title'] ?? 'Teknologi UAV Rekayasa Indonesia.') !!}
        </h2>
        <p class="mt-4 text-[18px] text-[#515154] max-w-[540px] leading-relaxed">
          {!! nl2br(esc_html($hp['produk_desc'] ?? 'TKDN + BMP hingga 60,74%, SNI 9199:2023, software FDS STATION Bahasa Indonesia, dan garansi purna jual resmi.')) !!}
        </p>
      </div>
    </div>

    @php
      // 100% Dynamic WP Query from 'kategori_drone' Taxonomy & 'drone' CPT
      $tax_terms = get_terms([
          'taxonomy'   => 'kategori_drone',
          'hide_empty' => false,
          'orderby'    => 'term_id',
          'order'      => 'ASC',
      ]);

      $categories = ['Semua'];
      if (!empty($tax_terms) && !is_wp_error($tax_terms)) {
          foreach ($tax_terms as $t) {
              $categories[] = $t->name;
          }
      } else {
          $categories = ['Semua', 'Agrikultur', 'Pemetaan & GIS', 'Kargo', 'Reboisasi'];
      }

      $wp_drones = get_posts([
          'post_type'      => 'drone',
          'posts_per_page' => -1,
          'post_status'    => 'publish',
          'orderby'        => 'ID',
          'order'          => 'ASC',
      ]);

      $products = [];
      if (!empty($wp_drones)) {
          foreach ($wp_drones as $d) {
              $terms    = get_the_terms($d->ID, 'kategori_drone');
              $d_cat    = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : (get_post_meta($d->ID, 'drone_kategori', true) ?: 'Agrikultur');
              $d_badge  = get_post_meta($d->ID, 'drone_badge', true) ?: 'Unggulan';
              $d_tagline= get_post_meta($d->ID, 'drone_tagline', true);
              $d_desc   = get_post_meta($d->ID, 'drone_desc', true) ?: $d->post_content;
              $d_specs  = get_post_meta($d->ID, 'drone_specs_raw', true);
              $d_thumb  = get_the_post_thumbnail_url($d->ID, 'thumbnail');

              // Extract first 2-3 lines of specs for row preview
              $spec_summary = '';
              if ($d_specs) {
                  $lines = array_slice(array_filter(array_map('trim', explode("\n", $d_specs))), 0, 3);
                  $parts = [];
                  foreach ($lines as $l) {
                      $sp = explode(':', $l, 2);
                      if (count($sp) === 2) {
                          $parts[] = trim($sp[1]);
                      }
                  }
                  $spec_summary = implode(' &middot; ', $parts);
              }

              $products[] = [
                  'slug'  => $d->post_name,
                  'name'  => html_entity_decode($d->post_title, ENT_QUOTES, 'UTF-8'),
                  'cat'   => html_entity_decode($d_cat, ENT_QUOTES, 'UTF-8'),
                  'badge' => html_entity_decode($d_badge, ENT_QUOTES, 'UTF-8'),
                  'desc'  => html_entity_decode($d_tagline ?: wp_trim_words($d_desc, 18), ENT_QUOTES, 'UTF-8'),
                  'specs' => html_entity_decode($spec_summary ?: 'Spesifikasi Lengkap · SNI 9199:2023', ENT_QUOTES, 'UTF-8'),
                  'thumb' => $d_thumb,
              ];

              if (!in_array($d_cat, $categories)) {
                  $categories[] = $d_cat;
              }
          }
      }
    @endphp

    {{-- Category filter tabs (Dinamis dari WordPress) --}}
    <div class="flex flex-wrap gap-2 mb-10" id="drone-cat-tabs">
      @foreach($categories as $cat)
      <button
        data-cat="{!! esc_attr(strip_tags($cat)) !!}"
        onclick="filterDrones(this)"
        class="drone-tab {{ $loop->first ? 'active' : '' }}">
        {!! $cat !!}
      </button>
      @endforeach
    </div>

    <style>
      .drone-tab {
        padding: 8px 20px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.15s ease;
        cursor: pointer;
        border: 1px solid rgba(0, 0, 0, 0.12);
        background-color: #ffffff;
        color: #515154;
      }
      .drone-tab:hover {
        border-color: #1d1d1f;
        color: #1d1d1f;
      }
      .drone-tab.active,
      .drone-tab.active:hover,
      .drone-tab.active:focus {
        background-color: #1d1d1f !important;
        color: #ffffff !important;
        border-color: #1d1d1f !important;
      }
    </style>

    {{-- Product rows (Dinamis dari WordPress) --}}
    <div class="space-y-0 border-t border-black/[0.06]" id="drone-list">

      @foreach($products as $p)
      <div class="drone-row border-b border-black/[0.06]" data-cat="{!! esc_attr($p['cat']) !!}">
        <div class="grid grid-cols-12 gap-4 py-6 items-center group hover:bg-[#f5f5f7] rounded-2xl px-4 -mx-4 transition-colors duration-150 cursor-pointer"
             onclick="location.href='{{ home_url('/' . $p['slug']) }}'">

          {{-- Icon --}}
          <div class="col-span-1 hidden sm:flex">
            <div class="w-10 h-10 bg-[#f5f5f7] rounded-xl flex items-center justify-center group-hover:bg-white transition-colors flex-shrink-0">
              <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </div>
          </div>

          {{-- Name + category --}}
          <div class="col-span-7 sm:col-span-3">
            <div class="flex items-center gap-2 mb-0.5">
              <p class="text-[11px] font-semibold text-[#86868b] tracking-wide">{!! $p['cat'] !!}</p>
              <span class="text-[10px] font-semibold text-[#0066cc] bg-[#0066cc]/10 px-2 py-0.5 rounded-full">{!! $p['badge'] !!}</span>
            </div>
            <h3 class="text-[20px] font-semibold text-[#1d1d1f] tracking-tight">{!! $p['name'] !!}</h3>
          </div>

          {{-- Description --}}
          <div class="hidden md:block col-span-4">
            <p class="text-[15px] text-[#515154]">{!! $p['desc'] !!}</p>
          </div>

          {{-- Specs + CTA --}}
          <div class="col-span-5 sm:col-span-4 flex flex-col sm:flex-row items-end sm:items-center justify-end gap-3 sm:gap-6">
            <p class="hidden lg:block text-[13px] text-[#86868b]">{!! $p['specs'] !!}</p>
            <a href="{{ home_url('/' . $p['slug']) }}"
               class="text-[13px] font-semibold text-[#0066cc] hover:underline whitespace-nowrap"
               onclick="event.stopPropagation()">
              Detail &rsaquo;
            </a>
          </div>

        </div>
      </div>
      @endforeach

      {{-- Empty state untuk kategori yang belum ada produk --}}
      <div id="drone-empty" class="hidden py-16 text-center border-b border-black/[0.06]">
        <p class="text-[15px] text-[#86868b]">Produk kategori ini akan segera hadir.</p>
        <a href="{{ home_url('/#kontak') }}" class="mt-4 inline-block text-[14px] font-semibold text-[#0066cc] hover:underline">Daftar notifikasi &rsaquo;</a>
      </div>

    </div>

    {{-- USP strip --}}
    <div class="mt-14 grid grid-cols-2 md:grid-cols-4 gap-6">
      <div class="text-center">
        <p class="text-[32px] font-semibold text-[#1d1d1f] tracking-[-0.03em]">{!! esc_html($hp['produk_stat1_num'] ?? '60,74%') !!}</p>
        <p class="text-[13px] text-[#86868b] mt-1 font-medium">{!! esc_html($hp['produk_stat1_lbl'] ?? 'Nilai TKDN + BMP') !!}</p>
      </div>
      <div class="text-center">
        <p class="text-[32px] font-semibold text-[#1d1d1f] tracking-[-0.03em]">{!! esc_html($hp['produk_stat2_num'] ?? 'ISO & SNI') !!}</p>
        <p class="text-[13px] text-[#86868b] mt-1 font-medium">{!! esc_html($hp['produk_stat2_lbl'] ?? 'ISO 9001 & SNI 9199:2023') !!}</p>
      </div>
      <div class="text-center">
        <p class="text-[32px] font-semibold text-[#1d1d1f] tracking-[-0.03em]">{!! esc_html($hp['produk_stat3_num'] ?? '100%') !!}</p>
        <p class="text-[13px] text-[#86868b] mt-1 font-medium">{!! esc_html($hp['produk_stat3_lbl'] ?? 'FDS STATION GCS') !!}</p>
      </div>
      <div class="text-center">
        <p class="text-[32px] font-semibold text-[#1d1d1f] tracking-[-0.03em]">{!! esc_html($hp['produk_stat4_num'] ?? '2012') !!}</p>
        <p class="text-[13px] text-[#86868b] mt-1 font-medium">{!! esc_html($hp['produk_stat4_lbl'] ?? 'Pengalaman Industri UAV') !!}</p>
      </div>
    </div>

  </div>
</section>

<script>
function filterDrones(btn) {
  const cat = (btn.dataset.cat || '').trim();
  
  // Update active class
  document.querySelectorAll('.drone-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');

  // Filter rows
  const rows = document.querySelectorAll('.drone-row');
  let visible = 0;
  rows.forEach(row => {
    const rcat = (row.dataset.cat || '').trim();
    const match = (cat.toLowerCase() === 'semua') || (rcat.toLowerCase() === cat.toLowerCase());
    row.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  const emptyEl = document.getElementById('drone-empty');
  if (emptyEl) {
    emptyEl.style.display = (visible === 0) ? '' : 'none';
  }
}
</script>



{{-- ========================================================== --}}
{{-- 5. LAYANAN ENTERPRISE                                     --}}
{{-- ========================================================== --}}
<section id="layanan" class="bg-[#1d1d1f] py-24 sm:py-32 border-t border-white/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

      <div class="lg:sticky lg:top-24">
        <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-4">{!! esc_html($hp['layanan_badge'] ?? 'Layanan') !!}</p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5">
          {!! esc_html($hp['layanan_title'] ?? 'Lebih dari sekadar hardware.') !!}
        </h2>
        <p class="text-[18px] text-white/50 leading-relaxed max-w-[380px] mb-8">
          {!! nl2br(esc_html($hp['layanan_desc'] ?? 'Kami menyediakan layanan operasional lengkap untuk memastikan investasi drone Anda memberikan hasil maksimal.')) !!}
        </p>
        <a href="{{ esc_url($hp['layanan_cta_url'] ?? '#kontak') }}"
           class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[14px] font-semibold px-6 py-3 rounded-full transition-all duration-150">
          {!! esc_html($hp['layanan_cta_text'] ?? 'Diskusi Kebutuhan Anda') !!}
        </a>
      </div>

      <div class="divide-y divide-white/[0.08]">
        <div class="py-7">
          <h3 class="text-[17px] font-semibold text-white mb-1.5">{!! esc_html($hp['layanan_item1_title'] ?? 'Pemetaan Aerial & GIS') !!}</h3>
          <p class="text-[15px] text-white/50 leading-relaxed">{!! nl2br(esc_html($hp['layanan_item1_desc'] ?? 'Peta topografi resolusi tinggi dengan akurasi sub-sentimeter untuk perencanaan lahan, kehutanan, dan infrastruktur.')) !!}</p>
        </div>
        <div class="py-7">
          <h3 class="text-[17px] font-semibold text-white mb-1.5">{!! esc_html($hp['layanan_item2_title'] ?? 'Inspeksi Industri & Infrastruktur') !!}</h3>
          <p class="text-[15px] text-white/50 leading-relaxed">{!! nl2br(esc_html($hp['layanan_item2_desc'] ?? 'Pemeriksaan visual dan termal berbasis UAV untuk pemantauan fasilitas energi, kelistrikan, migas, dan infrastruktur kritis secara cepat dan aman tanpa menghentikan operasional.')) !!}</p>
        </div>
        <div class="py-7">
          <h3 class="text-[17px] font-semibold text-white mb-1.5">{!! esc_html($hp['layanan_item3_title'] ?? 'Sewa Armada Drone') !!}</h3>
          <p class="text-[15px] text-white/50 leading-relaxed">{!! nl2br(esc_html($hp['layanan_item3_desc'] ?? 'Armada FERTO siap pakai untuk proyek jangka pendek, pilot project, atau kebutuhan peak season tanpa investasi unit penuh.')) !!}</p>
        </div>
        <div class="py-7">
          <h3 class="text-[17px] font-semibold text-white mb-1.5">{!! esc_html($hp['layanan_item4_title'] ?? 'Pelatihan & Sertifikasi Pilot') !!}</h3>
          <p class="text-[15px] text-white/50 leading-relaxed">{!! nl2br(esc_html($hp['layanan_item4_desc'] ?? 'Program pelatihan pilot drone bersertifikat resmi untuk tim lapangan Anda. Kurikulum mencakup misi agrikultur, pemetaan, dan inspeksi.')) !!}</p>
        </div>
        <div class="py-7">
          <h3 class="text-[17px] font-semibold text-white mb-1.5">{!! esc_html($hp['layanan_item5_title'] ?? 'After-Sales & Maintenance') !!}</h3>
          <p class="text-[15px] text-white/50 leading-relaxed">{!! nl2br(esc_html($hp['layanan_item5_desc'] ?? 'Layanan purna jual lokal dengan stok suku cadang, teknisi bersertifikat, dan garansi resmi di seluruh Indonesia.')) !!}</p>
        </div>
      </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- 6. BLOG / NEWSROOM                                        --}}
{{-- ========================================================== --}}
<section id="blog" class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="flex items-end justify-between mb-14 flex-wrap gap-6">
      <div>
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-3">{!! esc_html($hp['blog_badge'] ?? 'Newsroom') !!}</p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
          {!! esc_html($hp['blog_title'] ?? 'Berita & Pembaruan Terkini.') !!}
        </h2>
      </div>
      <a href="{{ home_url('/blog') }}"
         class="inline-flex items-center gap-1.5 text-[14px] font-semibold text-[#0066cc] hover:underline flex-shrink-0">
        {!! esc_html($hp['blog_cta_text'] ?? 'Lihat semua artikel') !!}
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    @php
      $recent_posts = get_posts([
        'numberposts' => 3,
        'post_status'  => 'publish',
        'orderby'      => 'date',
        'order'        => 'DESC',
      ]);
    @endphp

    @if($recent_posts)
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($recent_posts as $post)
          @php setup_postdata($post); @endphp
          <article class="bg-[#f5f5f7] rounded-[1.5rem] overflow-hidden group hover:-translate-y-1 transition-all duration-300">

            <div class="aspect-[16/9] overflow-hidden bg-[#e8e8ed]">
              @if(has_post_thumbnail($post->ID))
                {!! get_the_post_thumbnail($post->ID, 'medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']) !!}
              @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#e8f0fe] to-[#dbeafe]">
                  <svg class="w-10 h-10 text-[#0066cc]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
              @endif
            </div>

            <div class="p-6">
              <p class="text-[11px] font-bold tracking-wide text-[#86868b] mb-3">
                {{ get_the_date('d M Y', $post->ID) }}
              </p>
              <h3 class="text-[16px] font-semibold text-[#1d1d1f] leading-[1.4] mb-3 group-hover:text-[#0066cc] transition-colors line-clamp-2">
                <a href="{{ get_permalink($post->ID) }}">{{ get_the_title($post->ID) }}</a>
              </h3>
              <p class="text-[14px] text-[#515154] leading-relaxed line-clamp-2 mb-5">
                {{ get_the_excerpt($post->ID) }}
              </p>
              <a href="{{ get_permalink($post->ID) }}"
                 class="inline-flex items-center gap-1 text-[13px] font-semibold text-[#0066cc] hover:underline">
                Baca selengkapnya <span>&rsaquo;</span>
              </a>
            </div>

          </article>
        @endforeach
      </div>
      @php wp_reset_postdata(); @endphp
    @else
      <div class="bg-[#f5f5f7] rounded-[1.5rem] p-12 text-center">
        <p class="text-[17px] text-[#515154]">Belum ada artikel yang diterbitkan.</p>
      </div>
    @endif

  </div>
</section>


{{-- ========================================================== --}}
{{-- 7. CONTACT — Premium split layout                         --}}
{{-- ========================================================== --}}
<section id="kontak" class="bg-[#f5f5f7] py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      {{-- Left panel: dark, info --}}
      <div class="bg-[#1d1d1f] rounded-[2rem] p-10 sm:p-14 flex flex-col justify-between min-h-[520px]">
        <div>
          <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-6">{!! esc_html($hp['kontak_badge'] ?? 'Enterprise Sales') !!}</p>
          <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5">
            {!! nl2br(esc_html($hp['kontak_title'] ?? "Hubungi tim\nEnterprise FDS.")) !!}
          </h2>
          <p class="text-[17px] text-white/60 leading-relaxed max-w-[360px]">
            {!! nl2br(esc_html($hp['kontak_desc'] ?? 'Dari konsultasi teknis, fleet management, hingga program sertifikasi — kami siap mendampingi operasional drone Anda.')) !!}
          </p>
        </div>

        <div class="mt-12 space-y-6 border-t border-white/[0.08] pt-10">
          <div>
            <p class="text-[11px] font-semibold tracking-wide text-white/40 mb-1.5">Telepon / WhatsApp</p>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $hp['kontak_phone'] ?? '+6281234567890') }}" class="text-[17px] font-semibold text-white hover:text-[#6e9fd4] transition-colors">{!! esc_html($hp['kontak_phone'] ?? '+62 812-3456-7890') !!}</a>
          </div>
          <div>
            <p class="text-[11px] font-semibold tracking-wide text-white/40 mb-1.5">Email</p>
            <a href="mailto:{{ esc_attr($hp['kontak_email'] ?? 'sales@fulldronesolutions.co.id') }}" class="text-[17px] font-semibold text-white hover:text-[#6e9fd4] transition-colors break-all">{!! esc_html($hp['kontak_email'] ?? 'sales@fulldronesolutions.co.id') !!}</a>
          </div>
          <div>
            <p class="text-[11px] font-semibold tracking-wide text-white/40 mb-1.5">Lokasi Workshop</p>
            <p class="text-[17px] font-semibold text-white">{!! esc_html($hp['kontak_address'] ?? 'Sleman, D.I. Yogyakarta') !!}</p>
          </div>
          <a href="{{ esc_url($hp['kontak_wa_link'] ?? 'https://wa.me/6281234567890') }}" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2.5 bg-[#25D366] hover:bg-[#1db954] active:scale-[0.97] text-white font-semibold text-[14px] px-5 py-3 rounded-full transition-all duration-150">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            {!! esc_html($hp['kontak_wa_text'] ?? 'Chat via WhatsApp') !!}
          </a>
        </div>
      </div>

      {{-- Right panel: white, form --}}
      <div class="bg-white rounded-[2rem] p-10 sm:p-14" style="box-shadow: 0 4px 40px rgba(0,0,0,0.06);">
        <h3 class="text-[24px] font-semibold text-[#1d1d1f] tracking-tight mb-8">{!! esc_html($hp['kontak_form_title'] ?? 'Kirim pesan inquiry') !!}</h3>

        <form class="space-y-7" onsubmit="event.preventDefault();">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Nama Depan</label>
              <input type="text" placeholder="Ahmad"
                class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200">
            </div>
            <div>
              <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Nama Belakang</label>
              <input type="text" placeholder="Fauzi"
                class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Perusahaan / Instansi</label>
              <input type="text" placeholder="PT. Contoh Indonesia"
                class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200">
            </div>
            <div>
              <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Email Bisnis</label>
              <input type="email" placeholder="nama@perusahaan.co.id"
                class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200">
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Nomor Telepon / WhatsApp</label>
            <input type="tel" placeholder="+62 812-XXXX-XXXX"
              class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200">
          </div>

          <div>
            <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Kebutuhan Anda</label>
            <textarea rows="3" placeholder="Jelaskan kebutuhan drone, layanan, atau pertanyaan teknis Anda..."
              class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200 resize-none"></textarea>
          </div>

          <div class="pt-2 flex flex-col sm:flex-row items-start sm:items-center gap-5">
            <button type="submit"
              class="bg-[#1d1d1f] hover:bg-black active:scale-[0.97] text-white font-semibold text-[15px] px-8 py-4 rounded-full transition-all duration-150 whitespace-nowrap">
              {!! esc_html($hp['kontak_form_btn_text'] ?? 'Kirim Pesan') !!}
            </button>
            <p class="text-[12px] text-[#86868b] leading-relaxed">
              {!! nl2br(esc_html($hp['kontak_form_note'] ?? "Kami merespons dalam 1×24 jam kerja.\nData Anda tidak akan dibagikan ke pihak ketiga.")) !!}
            </p>
          </div>

        </form>
      </div>

    </div>
  </div>
</section>

@endsection

