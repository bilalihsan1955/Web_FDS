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
            alt="{!! esc_attr(wp_specialchars_decode($slide['alt'] ?: 'Full Drone Solutions', ENT_QUOTES)) !!}"
            class="w-full h-full object-cover"
            loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
          >
          @if(!empty($slide['title']))
          <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent p-6 sm:p-10 text-left">
            <p class="text-white text-[16px] sm:text-[20px] font-semibold tracking-[-0.01em] drop-shadow-md">
              {!! esc_html(wp_specialchars_decode($slide['title'], ENT_QUOTES)) !!}
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
               alt="{!! esc_attr(wp_specialchars_decode($mitra->post_title, ENT_QUOTES)) !!}"
               class="h-24 w-auto object-contain"
               title="{!! esc_attr(wp_specialchars_decode($mitra->post_title, ENT_QUOTES)) !!}">
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
<section id="solusi" class="bg-[#1d1d1f] py-24 sm:py-32 overflow-hidden">
  {{-- Header inside 1400px Container --}}
  <div id="solusi-header-container" class="max-w-[1400px] mx-auto px-6 lg:px-12 mb-14">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
      <div>
        <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-3">{!! esc_html($solusi_data['badge']) !!}</p>
        <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-white leading-[1.1] max-w-[640px]">
          {!! esc_html($solusi_data['title']) !!}
        </h2>
      </div>
      <p class="text-[16px] text-white/50 max-w-[460px] leading-relaxed">
        {!! nl2br(esc_html($solusi_data['desc'])) !!}
      </p>
    </div>
  </div>

  {{-- Full Width Carousel Track (Apple Style: Bleeds to Screen Edge, No Padding Cut-off) --}}
  @if(!empty($solusi_data['cards']))
  <div class="w-full">
    <div id="solusi-carousel-track"
         class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-6 pt-2 hide-scrollbar w-full solusi-track-padding">

      @foreach($solusi_data['cards'] as $card)
      <div class="solusi-carousel-card w-[280px] sm:w-[320px] md:w-[340px] lg:w-[360px] min-h-[420px] sm:min-h-[440px] flex-shrink-0 snap-start bg-white/[0.06] rounded-[1.5rem] overflow-hidden group hover:bg-white/[0.09] transition-all duration-300 flex flex-col justify-between select-none">
        <div>
          {{-- Image Box --}}
          <div class="h-[170px] sm:h-[190px] overflow-hidden relative bg-[#1e293b]">
            <img src="{{ esc_url($card['image'] ?: 'https://images.unsplash.com/photo-1527011046414-4781f1f94f8c?auto=format&fit=crop&w=800&q=80') }}" 
                 alt="{!! esc_attr(wp_specialchars_decode($card['title'], ENT_QUOTES)) !!}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-[#1d1d1f]/70 via-transparent to-transparent pointer-events-none"></div>
            @if(!empty($card['tag']))
              <div class="absolute top-3.5 left-3.5 z-10">
                <span class="text-[10px] font-bold text-white bg-black/50 backdrop-blur-md px-3 py-1 rounded-full border border-white/15 uppercase tracking-wider">
                  {!! esc_html($card['tag']) !!}
                </span>
              </div>
            @endif
          </div>

          {{-- Body --}}
          <div class="p-5 sm:p-6 pb-3">
            <h3 class="text-[17px] sm:text-[19px] font-semibold text-white mb-2 leading-snug group-hover:text-[#6e9fd4] transition-colors">
              {!! esc_html($card['title']) !!}
            </h3>
            <p class="text-[13px] sm:text-[13.5px] text-white/70 leading-relaxed">
              {!! nl2br(esc_html($card['desc'])) !!}
            </p>
          </div>
        </div>

        {{-- Footer Link --}}
        <div class="px-5 sm:px-6 pb-5 sm:pb-6 pt-0">
          <div class="pt-3.5 border-t border-white/[0.08] flex items-center justify-between">
            <span class="text-[11px] font-bold text-[#6e9fd4] tracking-wide uppercase">{!! esc_html($card['tag'] ?? 'FDS DRONE') !!}</span>
            <a href="{{ esc_url($card['link_url'] ?: '#kontak') }}" class="text-[12.5px] font-semibold text-white hover:text-[#6e9fd4] inline-flex items-center gap-1.5 transition-colors group-hover:translate-x-0.5 duration-200">
              <span>{!! esc_html($card['link_text'] ?: 'Pelajari') !!}</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>

  {{-- Bottom Right Circular Arrow Buttons (Flush to 1400px Right Padding) --}}
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="flex items-center justify-end gap-3 mt-6">
      <button id="solusi-prev-btn" onclick="scrollSolusiCarousel(-1)" aria-label="Sebelumnya"
              class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 active:scale-95 flex items-center justify-center text-white transition-all disabled:opacity-20 disabled:cursor-not-allowed border border-white/10">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <button id="solusi-next-btn" onclick="scrollSolusiCarousel(1)" aria-label="Berikutnya"
              class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 active:scale-95 flex items-center justify-center text-white transition-all disabled:opacity-20 disabled:cursor-not-allowed border border-white/10">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </button>
    </div>
  </div>
  @endif
</section>

<style>
  .solusi-track-padding {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    scroll-padding-left: 1.5rem;
    scroll-padding-right: 1.5rem;
  }
  @media (min-width: 1024px) {
    .solusi-track-padding {
      padding-left: max(3rem, calc((100% - 1400px) / 2 + 3rem));
      padding-right: max(3rem, calc((100% - 1400px) / 2 + 3rem));
      scroll-padding-left: max(3rem, calc((100% - 1400px) / 2 + 3rem));
      scroll-padding-right: max(3rem, calc((100% - 1400px) / 2 + 3rem));
    }
  }
  .hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  .hide-scrollbar::-webkit-scrollbar {
    display: none;
  }
</style>

<script>
function alignSolusiTrack() {
  const headerContainer = document.getElementById('solusi-header-container');
  const track = document.getElementById('solusi-carousel-track');
  if (!headerContainer || !track) return;

  const rect = headerContainer.getBoundingClientRect();
  const cs = window.getComputedStyle(headerContainer);
  const padLeft = parseFloat(cs.paddingLeft) || 24;
  const padRight = parseFloat(cs.paddingRight) || 24;

  const exactLeft = Math.max(24, Math.round(rect.left + padLeft));
  const exactRight = Math.max(24, Math.round(window.innerWidth - rect.right + padRight));

  track.style.paddingLeft = exactLeft + 'px';
  track.style.paddingRight = exactRight + 'px';
  track.style.scrollPaddingLeft = exactLeft + 'px';
  track.style.scrollPaddingRight = exactRight + 'px';
}

function scrollSolusiCarousel(direction) {
  const track = document.getElementById('solusi-carousel-track');
  if (!track) return;
  const card = track.querySelector('.solusi-carousel-card');
  const scrollAmount = card ? (card.offsetWidth + 24) : 440;
  track.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
}

function updateSolusiCarouselControls() {
  const track = document.getElementById('solusi-carousel-track');
  const prevBtn = document.getElementById('solusi-prev-btn');
  const nextBtn = document.getElementById('solusi-next-btn');
  if (!track) return;

  const canScroll = track.scrollWidth > (track.clientWidth + 5);
  if (!canScroll) {
    if (prevBtn) prevBtn.disabled = true;
    if (nextBtn) nextBtn.disabled = true;
    return;
  }

  const isAtStart = track.scrollLeft <= 8;
  const isAtEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 12;

  if (prevBtn) prevBtn.disabled = isAtStart;
  if (nextBtn) nextBtn.disabled = isAtEnd;
}

window.addEventListener('resize', function() {
  alignSolusiTrack();
  updateSolusiCarouselControls();
});

document.addEventListener('DOMContentLoaded', function() {
  alignSolusiTrack();
  const track = document.getElementById('solusi-carousel-track');
  if (track) {
    track.addEventListener('scroll', updateSolusiCarouselControls, { passive: true });
    setTimeout(function() {
      alignSolusiTrack();
      updateSolusiCarouselControls();
    }, 100);
  }
});
</script>


{{-- ========================================================== --}}
{{-- 4. KEUNGGULAN — Bento grid light                           --}}
{{-- ========================================================== --}}
<section class="bg-white py-24 sm:py-32">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    {{-- Section header --}}
    <div class="mb-14">
      <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">
        {!! esc_html($hp['keunggulan_badge'] ?? 'Mengapa Memilih FDS') !!}
      </p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] max-w-[680px]">
        {!! $hp['keunggulan_title'] ?? 'Keunggulan teknologi UAV buatan dalam negeri.' !!}
      </h2>
    </div>

    {{-- Bento Grid (7 Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">

      {{-- Large hero card — local manufacturing --}}
      <div class="lg:col-span-8 bg-[#f5f5f7] rounded-[2rem] overflow-hidden relative min-h-[340px] group"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <div class="absolute inset-0 z-0">
          <img src="{{ !empty($hp['keunggulan_card1_img']) ? $hp['keunggulan_card1_img'] : fds_img('keunggulan', 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=1200&q=80') }}"
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
                  'id'    => $d->ID,
                  'url'   => get_permalink($d->ID),
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
      @php
        $global_drone_icon = function_exists('App\fds_get_drone_icon') ? \App\fds_get_drone_icon() : (function_exists('App\fds_get_navbar_drone_icon') ? \App\fds_get_navbar_drone_icon() : '');
      @endphp

      @foreach($products as $p)
      <div class="drone-row border-b border-black/[0.06]" data-cat="{!! esc_attr($p['cat']) !!}">
        <div class="grid grid-cols-12 gap-4 py-6 items-center group hover:bg-[#f5f5f7] rounded-2xl px-4 -mx-4 transition-colors duration-150 cursor-pointer"
             onclick="location.href='{{ $p['url'] }}'">

          {{-- Icon --}}
          <div class="col-span-1 hidden sm:flex">
            <div class="w-11 h-11 bg-[#f5f5f7] rounded-xl flex items-center justify-center group-hover:bg-white transition-colors flex-shrink-0">
              @if(!empty($global_drone_icon))
                <img src="{{ esc_url($global_drone_icon) }}" alt="Drone Icon" style="width: 26px !important; height: 26px !important; min-width: 26px !important; max-width: 26px !important; max-height: 26px !important; object-fit: contain !important;" class="block">
              @else
                <svg class="w-6 h-6 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
              @endif
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
            <a href="{{ $p['url'] }}"
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
@php
  $layanan_items = function_exists('App\fds_get_layanan_items') ? \App\fds_get_layanan_items() : [];
@endphp
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
           class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[14px] font-semibold px-6 py-3 rounded-full transition-all duration-150 shadow-md">
          {!! esc_html($hp['layanan_cta_text'] ?? 'Diskusi Kebutuhan Anda') !!}
        </a>
      </div>

      <div class="divide-y divide-white/[0.08]">
        @foreach($layanan_items as $lItem)
        <div class="py-7 group">
          <h3 class="text-[17px] font-semibold text-white mb-1.5 flex items-center justify-between">
            <span>{!! esc_html(wp_specialchars_decode($lItem['title'])) !!}</span>
            @if(!empty($lItem['url']) && $lItem['url'] !== '#')
            <a href="{{ esc_url($lItem['url']) }}" class="text-[12px] font-medium text-[#6e9fd4] group-hover:text-white transition-colors inline-flex items-center gap-0.5 opacity-0 group-hover:opacity-100">
              Lihat Detail &rsaquo;
            </a>
            @endif
          </h3>
          <p class="text-[15px] text-white/50 leading-relaxed">
            {!! nl2br(esc_html(wp_specialchars_decode($lItem['desc']))) !!}
          </p>
        </div>
        @endforeach
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- 6. BLOG / NEWSROOM — Carousel 7 Berita Terbaru             --}}
{{-- ========================================================== --}}
@php
  $recent_posts = get_posts([
    'numberposts' => 7,
    'post_status'  => 'publish',
    'orderby'      => 'date',
    'order'        => 'DESC',
  ]);
@endphp
<section id="blog" class="bg-white py-24 sm:py-32 border-t border-black/[0.06] overflow-hidden">
  {{-- Header inside 1400px Container --}}
  <div id="news-header-container" class="max-w-[1400px] mx-auto px-6 lg:px-12 mb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
      <div>
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-3">{!! esc_html($hp['blog_badge'] ?? 'Newsroom') !!}</p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] max-w-[640px]">
          {!! esc_html($hp['blog_title'] ?? 'Berita & Pembaruan Terkini.') !!}
        </h2>
      </div>
      <a href="{{ home_url('/blog') }}"
         class="inline-flex items-center gap-1.5 text-[14px] font-semibold text-[#0066cc] hover:underline flex-shrink-0">
        {!! esc_html($hp['blog_cta_text'] ?? 'Lihat semua artikel') !!}
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>

  {{-- Full Width Carousel Track (Apple Style: Bleeds to Screen Edge, Aligns to 1400px Left Padding) --}}
  @if(!empty($recent_posts))
  <div class="w-full">
    <div id="news-carousel-track"
         class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-6 pt-2 hide-scrollbar w-full news-track-padding">

      @foreach($recent_posts as $post)
        @php
          setup_postdata($post);
          $categories = get_the_category($post->ID);
          $cat_name = !empty($categories) ? $categories[0]->name : 'Berita';
        @endphp
        <div class="news-carousel-card w-[280px] sm:w-[320px] md:w-[340px] lg:w-[360px] min-h-[420px] sm:min-h-[440px] flex-shrink-0 snap-start bg-[#f5f5f7] rounded-[1.5rem] overflow-hidden group hover:bg-[#ececee] transition-all duration-300 flex flex-col justify-between select-none">
          <div>
            {{-- Image Box (Clean without covering tags) --}}
            <div class="h-[180px] sm:h-[195px] overflow-hidden relative bg-[#e8e8ed]">
              @if(has_post_thumbnail($post->ID))
                {!! get_the_post_thumbnail($post->ID, 'medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']) !!}
              @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#e8f0fe] to-[#dbeafe]">
                  <svg class="w-10 h-10 text-[#0066cc]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
              @endif
            </div>

            {{-- Editorial Body --}}
            <div class="p-6 sm:p-7 pb-4">
              {{-- Category & Date Inline Meta --}}
              <div class="flex items-center gap-2 text-[12px] mb-2.5">
                <span class="font-semibold text-[#0066cc]">{!! esc_html($cat_name) !!}</span>
                <span class="text-[#cbd5e1]">&bull;</span>
                <span class="text-[#86868b]">{{ get_the_date('d M Y', $post->ID) }}</span>
              </div>

              {{-- Article Title --}}
              <h3 class="text-[17px] sm:text-[18.5px] font-semibold text-[#1d1d1f] mb-2.5 leading-snug group-hover:text-[#0066cc] transition-colors line-clamp-2 min-h-[46px]">
                <a href="{{ get_permalink($post->ID) }}">{!! esc_html(wp_specialchars_decode(get_the_title($post->ID), ENT_QUOTES)) !!}</a>
              </h3>

              {{-- Article Excerpt --}}
              <p class="text-[13px] sm:text-[13.5px] text-[#64748b] leading-relaxed line-clamp-3">
                {!! esc_html(wp_specialchars_decode(get_the_excerpt($post->ID), ENT_QUOTES)) !!}
              </p>
            </div>
          </div>

          {{-- Clean Editorial Read More Action (No Divider Bar) --}}
          <div class="px-6 sm:px-7 pb-6 pt-0">
            <a href="{{ get_permalink($post->ID) }}" class="inline-flex items-center gap-1 text-[13px] font-semibold text-[#0066cc] group-hover:text-[#0055b3] transition-colors">
              <span>Baca selengkapnya</span>
              <span class="text-[15px] group-hover:translate-x-1 transition-transform">&rsaquo;</span>
            </a>
          </div>
        </div>
      @endforeach
      @php wp_reset_postdata(); @endphp

    </div>
  </div>

  {{-- Bottom Controls (Arrow Buttons Flush to 1400px Right Padding) --}}
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="flex items-center justify-end gap-3 mt-6">
      <button id="news-prev-btn" onclick="scrollNewsCarousel(-1)" aria-label="Sebelumnya"
              class="w-10 h-10 rounded-full bg-black/[0.05] hover:bg-black/10 active:scale-95 flex items-center justify-center text-[#1d1d1f] transition-all disabled:opacity-20 disabled:cursor-not-allowed border border-black/[0.08]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <button id="news-next-btn" onclick="scrollNewsCarousel(1)" aria-label="Berikutnya"
              class="w-10 h-10 rounded-full bg-black/[0.05] hover:bg-black/10 active:scale-95 flex items-center justify-center text-[#1d1d1f] transition-all disabled:opacity-20 disabled:cursor-not-allowed border border-black/[0.08]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </button>
    </div>
  </div>
  @else
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="bg-[#f5f5f7] rounded-[1.5rem] p-12 text-center">
      <p class="text-[17px] text-[#515154]">Belum ada artikel yang diterbitkan.</p>
    </div>
  </div>
  @endif
</section>

<style>
  .news-track-padding {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    scroll-padding-left: 1.5rem;
    scroll-padding-right: 1.5rem;
  }
  @media (min-width: 1024px) {
    .news-track-padding {
      padding-left: max(3rem, calc((100% - 1400px) / 2 + 3rem));
      padding-right: max(3rem, calc((100% - 1400px) / 2 + 3rem));
      scroll-padding-left: max(3rem, calc((100% - 1400px) / 2 + 3rem));
      scroll-padding-right: max(3rem, calc((100% - 1400px) / 2 + 3rem));
    }
  }
</style>

<script>
function alignNewsTrack() {
  const headerContainer = document.getElementById('news-header-container');
  const track = document.getElementById('news-carousel-track');
  if (!headerContainer || !track) return;

  const rect = headerContainer.getBoundingClientRect();
  const cs = window.getComputedStyle(headerContainer);
  const padLeft = parseFloat(cs.paddingLeft) || 24;
  const padRight = parseFloat(cs.paddingRight) || 24;

  const exactLeft = Math.max(24, Math.round(rect.left + padLeft));
  const exactRight = Math.max(24, Math.round(window.innerWidth - rect.right + padRight));

  track.style.paddingLeft = exactLeft + 'px';
  track.style.paddingRight = exactRight + 'px';
  track.style.scrollPaddingLeft = exactLeft + 'px';
  track.style.scrollPaddingRight = exactRight + 'px';
}

function scrollNewsCarousel(direction) {
  const track = document.getElementById('news-carousel-track');
  if (!track) return;
  const card = track.querySelector('.news-carousel-card');
  const scrollAmount = card ? (card.offsetWidth + 24) : 360;
  track.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
}

function updateNewsCarouselControls() {
  const track = document.getElementById('news-carousel-track');
  const prevBtn = document.getElementById('news-prev-btn');
  const nextBtn = document.getElementById('news-next-btn');
  if (!track) return;

  const canScroll = track.scrollWidth > (track.clientWidth + 5);
  if (!canScroll) {
    if (prevBtn) prevBtn.disabled = true;
    if (nextBtn) nextBtn.disabled = true;
    return;
  }

  const isAtStart = track.scrollLeft <= 8;
  const isAtEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 12;

  if (prevBtn) prevBtn.disabled = isAtStart;
  if (nextBtn) nextBtn.disabled = isAtEnd;
}

window.addEventListener('resize', function() {
  alignNewsTrack();
  updateNewsCarouselControls();
});

document.addEventListener('DOMContentLoaded', function() {
  alignNewsTrack();
  const track = document.getElementById('news-carousel-track');
  if (track) {
    track.addEventListener('scroll', updateNewsCarouselControls, { passive: true });
    setTimeout(function() {
      alignNewsTrack();
      updateNewsCarouselControls();
    }, 100);
  }
});
</script>


{{-- ========================================================== --}}
{{-- 7. CONTACT — Premium split layout & Full-width Map        --}}
{{-- ========================================================== --}}
@php
  $global_c  = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_phone       = $global_c['phone'] ?? ($hp['kontak_phone'] ?? '+62 8112 748 882');
  $c_email       = $global_c['email'] ?? ($hp['kontak_email'] ?? 'marketing@fulldronesolutions.com');
  $c_address     = $global_c['address'] ?? ($hp['kontak_address'] ?? 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281');
  $c_wa_link     = $global_c['wa_link'] ?? ($hp['kontak_wa_link'] ?? 'https://wa.me/628112748882');
  $c_maps        = $global_c['maps_url'] ?? ($hp['kontak_maps'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid');
  $show_map_home = isset($global_c['show_map_home']) ? (bool) $global_c['show_map_home'] : (isset($hp['show_map_home']) ? (bool) $hp['show_map_home'] : (bool) get_option('fds_show_map_home', 1));
@endphp
<section id="kontak" class="bg-[#f5f5f7] py-12 sm:py-16 border-t border-black/[0.06] overflow-hidden">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 w-full">

    {{-- Unified Master Card: Seamless Dual-Surface (Apple/Linear Enterprise Hub) --}}
    <div class="bg-white rounded-[2rem] border border-black/[0.08] shadow-2xl shadow-black/[0.06] overflow-hidden grid grid-cols-1 lg:grid-cols-12 items-stretch">

      {{-- Left Side: Dark Architectural Panel (5 cols) --}}
      <div class="lg:col-span-5 bg-[#161618] py-8 sm:py-9 px-8 sm:px-10 lg:px-12 text-white flex flex-col justify-between relative overflow-hidden border-b lg:border-b-0 lg:border-r border-white/[0.06]">
        {{-- Top Header Section --}}
        <div>
          <div class="mb-2">
            <span class="text-[12px] font-semibold text-[#6e9fd4] tracking-wide">
              {!! esc_html($hp['kontak_badge'] ?? 'Enterprise Sales') !!}
            </span>
          </div>

          <h2 class="text-[24px] sm:text-[28px] lg:text-[30px] font-semibold tracking-tight text-white leading-tight mb-2">
            {!! nl2br(esc_html($hp['kontak_title'] ?? "Hubungi tim Enterprise FDS.")) !!}
          </h2>
          <p class="text-[13px] sm:text-[13.5px] text-white/60 leading-relaxed max-w-[420px]">
            {!! nl2br(esc_html($hp['kontak_desc'] ?? 'Konsultasi teknis armada UAV, integrasi sensor AI khusus, hingga program sertifikasi operasional pilot.')) !!}
          </p>
        </div>

        {{-- Direct Contact Information (Pinned to Bottom with Adaptive Space) --}}
        <div class="mt-8 pt-2 space-y-4">
          {{-- WhatsApp Channel --}}
          <div>
            <p class="text-[11px] font-medium text-white/40 mb-1">Telepon / WhatsApp</p>
            <a href="{{ esc_url($c_wa_link) }}" target="_blank" rel="noopener"
               class="text-[15.5px] sm:text-[16.5px] font-semibold text-white hover:text-[#6e9fd4] transition-colors">
              {!! esc_html($c_phone) !!}
            </a>
          </div>

          {{-- Email Channel --}}
          <div>
            <p class="text-[11px] font-medium text-white/40 mb-1">Email Bisnis</p>
            <a href="mailto:{{ esc_attr($c_email) }}"
               class="text-[14px] sm:text-[15px] font-semibold text-white hover:text-[#6e9fd4] transition-colors break-all">
              {!! esc_html($c_email) !!}
            </a>
          </div>

          {{-- Workshop Location --}}
          <div>
            <p class="text-[11px] font-medium text-white/40 mb-1">Lokasi Workshop &amp; Testing Center</p>
            <p class="text-[12.5px] sm:text-[13px] text-white/70 leading-relaxed max-w-[400px]">
              {!! esc_html($c_address) !!}
            </p>
          </div>

          {{-- WhatsApp Action Button --}}
          <div class="pt-2">
            <a href="{{ esc_url($c_wa_link) }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] active:scale-[0.97] text-white font-semibold text-[13.5px] px-5 py-2.5 rounded-full transition-all duration-150 shadow-sm">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
              <span>{!! esc_html($hp['kontak_wa_text'] ?? 'Chat via WhatsApp') !!}</span>
            </a>
          </div>
        </div>
      </div>

      {{-- Right Side: Streamlined Inquiry Form (7 cols) --}}
      <div class="lg:col-span-7 py-8 sm:py-9 px-8 sm:px-10 lg:px-12 bg-white flex flex-col justify-between">
        {{-- Form Header --}}
        <div class="mb-4 sm:mb-5">
          <h3 class="text-[19px] sm:text-[21px] font-semibold text-[#1d1d1f] tracking-tight mb-1">
            {!! esc_html($hp['kontak_form_title'] ?? 'Kirim pesan inquiry') !!}
          </h3>
          <p class="text-[12.5px] text-[#64748b]">Silakan lengkapi detail kontak dan kebutuhan operasional drone Anda:</p>
        </div>

        {{-- Form Body (Flex-1 to fill space evenly and balance with left panel) --}}
        <form id="fds-inquiry-form" class="flex-1 flex flex-col justify-between space-y-3.5" onsubmit="fdsSubmitInquiry(event)">
          @php wp_nonce_field('fds_inquiry_nonce', 'fds_inquiry_nonce_val'); @endphp

          <div class="space-y-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
              <div>
                <label class="block text-[11px] font-medium text-[#475569] mb-1">Nama Depan <span class="text-red-500">*</span></label>
                <input type="text" name="first_name" required placeholder="Ahmad"
                  class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0066cc] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
              </div>
              <div>
                <label class="block text-[11px] font-medium text-[#475569] mb-1">Nama Belakang <span class="text-red-500">*</span></label>
                <input type="text" name="last_name" required placeholder="Fauzi"
                  class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0066cc] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
              <div>
                <label class="block text-[11px] font-medium text-[#475569] mb-1">Perusahaan / Instansi <span class="text-red-500">*</span></label>
                <input type="text" name="company" required placeholder="PT. Contoh Indonesia"
                  class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0066cc] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
              </div>
              <div>
                <label class="block text-[11px] font-medium text-[#475569] mb-1">Email Bisnis <span class="text-red-500">*</span></label>
                <input type="email" name="email" required placeholder="nama@perusahaan.co.id"
                  class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0066cc] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
              </div>
            </div>

            <div>
              <label class="block text-[11px] font-medium text-[#475569] mb-1">Nomor Telepon / WhatsApp <span class="text-red-500">*</span></label>
              <input type="tel" name="phone" required placeholder="+62 812-XXXX-XXXX"
                class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0066cc] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
            </div>

            <div>
              <label class="block text-[11px] font-medium text-[#475569] mb-1">Kebutuhan Anda <span class="text-red-500">*</span></label>
              <textarea name="message" rows="3" required placeholder="Jelaskan kebutuhan drone, layanan, atau pertanyaan teknis Anda..."
                class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0066cc] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150 resize-none"></textarea>
            </div>
          </div>

          {{-- Submit Row (Docked to bottom, without divider line) --}}
          <div class="pt-2 mt-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <button type="submit" id="fds-inquiry-btn"
              class="bg-[#1d1d1f] hover:bg-[#0066cc] active:scale-[0.97] text-white font-medium text-[13.5px] px-6 py-2.5 rounded-full transition-all duration-200 whitespace-nowrap shadow-sm inline-flex items-center gap-1.5 cursor-pointer">
              <span id="fds-inquiry-btn-text">{!! esc_html($hp['kontak_form_btn_text'] ?? 'Kirim Pesan') !!}</span>
              <span id="fds-inquiry-btn-arrow" class="text-[15px]">&rsaquo;</span>
            </button>
            <div class="flex items-center gap-1.5 text-[11.5px] text-[#86868b] whitespace-nowrap">
              <svg class="w-3.5 h-3.5 text-[#86868b] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <span>{!! esc_html(trim(preg_replace('/\s+/', ' ', str_replace(['Data Anda tidak akan dibagikan ke pihak ketiga.', 'Data Anda tidak akan dibagikan ke pihak ketiga'], '', $hp['kontak_form_note'] ?? 'Kami merespons dalam 1×24 jam kerja. Data terjamin aman.')))) !!}</span>
            </div>
          </div>

        </form>
      </div>

    </div>
  </div>

  @if($show_map_home && !empty($c_maps))
  <div class="w-full overflow-hidden border-t border-black/[0.08] relative mt-12" style="height: 320px;">
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
  </div>
  @endif
</section>

{{-- Floating Bottom-Right Toast / Snackbar Notification --}}
<div id="fds-snackbar" class="fixed bottom-6 right-6 z-[99999] max-w-[400px] w-[calc(100%-3rem)] transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none">
  <div id="fds-snackbar-card" class="rounded-2xl p-4 shadow-2xl shadow-gray-900/30 flex items-start gap-3.5">
    <div id="fds-snackbar-icon" class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"></div>
    <div class="flex-1 min-w-0">
      <p id="fds-snackbar-title" class="text-[13.5px] font-bold text-white leading-tight mb-0.5"></p>
      <p id="fds-snackbar-desc" class="text-[12.5px] text-white/90 leading-snug"></p>
    </div>
    <button type="button" onclick="fdsCloseSnackbar()" class="text-white/70 hover:text-white hover:bg-white/15 p-1 rounded-lg transition-colors flex-shrink-0 cursor-pointer" aria-label="Tutup Notifikasi">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>
</div>

<script>
var fdsSnackbarTimer = null;

function fdsShowSnackbar(isSuccess, title, message) {
  var bar = document.getElementById('fds-snackbar');
  var card = document.getElementById('fds-snackbar-card');
  var icon = document.getElementById('fds-snackbar-icon');
  var titleEl = document.getElementById('fds-snackbar-title');
  var descEl = document.getElementById('fds-snackbar-desc');
  if (!bar) return;

  if (isSuccess) {
    card.className = 'bg-[#059669] border border-[#047857] rounded-2xl p-4 shadow-2xl shadow-gray-900/30 flex items-start gap-3.5 text-white';
    icon.className = 'w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center flex-shrink-0 mt-0.5';
    icon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
  } else {
    card.className = 'bg-[#dc2626] border border-[#b91c1c] rounded-2xl p-4 shadow-2xl shadow-gray-900/30 flex items-start gap-3.5 text-white';
    icon.className = 'w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center flex-shrink-0 mt-0.5';
    icon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
  }

  titleEl.textContent = title;
  descEl.textContent = message;

  bar.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
  bar.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');

  clearTimeout(fdsSnackbarTimer);
  fdsSnackbarTimer = setTimeout(function() {
    fdsCloseSnackbar();
  }, 5000);
}

function fdsCloseSnackbar() {
  var bar = document.getElementById('fds-snackbar');
  if (!bar) return;
  bar.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
  bar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
}

function fdsSubmitInquiry(e) {
  e.preventDefault();
  var form = document.getElementById('fds-inquiry-form');
  var btn = document.getElementById('fds-inquiry-btn');
  var btnText = document.getElementById('fds-inquiry-btn-text');
  
  if (!form || !btn) return;

  // Validasi Semua Field Wajib di Frontend
  var firstName = (form.querySelector('[name="first_name"]') || {}).value || '';
  var lastName  = (form.querySelector('[name="last_name"]') || {}).value || '';
  var company   = (form.querySelector('[name="company"]') || {}).value || '';
  var email     = (form.querySelector('[name="email"]') || {}).value || '';
  var phone     = (form.querySelector('[name="phone"]') || {}).value || '';
  var message   = (form.querySelector('[name="message"]') || {}).value || '';

  if (!firstName.trim() || !lastName.trim() || !company.trim() || !email.trim() || !phone.trim() || !message.trim()) {
    fdsShowSnackbar(false, 'Formulir Belum Lengkap', 'Semua kolom formulir wajib diisi sebelum mengirim pesan.');
    return;
  }

  // Validasi format email dasar
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.trim())) {
    fdsShowSnackbar(false, 'Format Email Tidak Valid', 'Silakan masukkan alamat email yang benar (contoh: nama@perusahaan.com).');
    return;
  }
  
  var formData = new FormData(form);
  formData.append('action', 'fds_submit_inquiry');
  formData.append('nonce', document.getElementById('fds_inquiry_nonce_val').value);

  btn.disabled = true;
  btn.classList.add('opacity-70', 'cursor-not-allowed');
  btnText.textContent = 'Mengirim Pesan...';

  fetch('{{ admin_url("admin-ajax.php") }}', {
    method: 'POST',
    body: formData
  })
  .then(function(res) { return res.json(); })
  .then(function(data) {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = '{!! esc_js($hp["kontak_form_btn_text"] ?? "Kirim Pesan") !!}';

    if (data.success) {
      fdsShowSnackbar(true, 'Pesan Berhasil Terkirim', data.data.message || 'Pesan Anda telah berhasil disimpan di sistem kami.');
      form.reset();
    } else {
      fdsShowSnackbar(false, 'Gagal Mengirim Pesan', data.data.message || 'Terjadi kesalahan. Silakan periksa kembali formulir Anda.');
    }
  })
  .catch(function(err) {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = '{!! esc_js($hp["kontak_form_btn_text"] ?? "Kirim Pesan") !!}';
    fdsShowSnackbar(false, 'Gangguan Jaringan', 'Gagal terhubung ke server. Silakan coba lagi atau hubungi kami via WhatsApp.');
  });
}
</script>

@endsection

