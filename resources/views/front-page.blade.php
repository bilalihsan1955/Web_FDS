@extends('layouts.app')

@section('content')

@php
  $hp = function_exists('App\fds_get_homepage_content') ? \App\fds_get_homepage_content() : [];
  $fds_clean = static function ($text): string {
      $val = (string) $text;
      return function_exists('fds_clean_string')
          ? fds_clean_string($val)
          : html_entity_decode(wp_specialchars_decode($val, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
  };
  $fds_hp_scrub = static function ($text, bool $allowTkdn = true, bool $allowSni = true, bool $allowIso = true) use ($fds_clean): string {
      $val = $fds_clean($text);
      return function_exists('fds_scrub_legacy_text') ? fds_scrub_legacy_text($val, $allowTkdn, $allowSni, $allowIso) : $val;
  };
@endphp

{{-- ========================================================== --}}
{{-- 1. HERO — Full-Bleed Background Hero Section (Dinamis)    --}}
{{-- ========================================================== --}}
@php
  $hero_slides = \App\fds_get_hero_slides();
@endphp
<section id="overview" class="relative w-full h-screen min-h-[100vh] min-h-[100dvh] flex flex-col justify-between overflow-hidden bg-[#070b14] pt-[52px] select-none">
  
  {{-- ── FULL-BLEED BACKGROUND SLIDER ──────────────────────── --}}
  <div id="fds-hero-slider" class="absolute inset-0 w-full h-full z-0 overflow-hidden" data-slide-count="{{ count($hero_slides) }}">
    @foreach($hero_slides as $i => $slide)
    <div class="fds-hero-slide absolute inset-0 w-full h-full transition-all duration-1000 ease-out {{ $i === 0 ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-105 pointer-events-none z-0' }}" data-index="{{ $i }}">
      <img
        @if($i === 0)
        src="{!! esc_url(fds_optimized_img_url($slide['url'], 'full')) !!}"
        fetchpriority="high"
        loading="eager"
        decoding="sync"
        data-no-lazy="1"
        class="w-full h-full object-cover object-center no-lazy"
        @else
        src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
        data-slide-src="{!! esc_url(fds_optimized_img_url($slide['url'], 'full')) !!}"
        loading="lazy"
        decoding="async"
        class="w-full h-full object-cover object-center"
        @endif
        width="1920"
        height="1080"
        alt="{{ $fds_clean($slide['alt'] ?: 'Full Drone Solutions') }}"
      >
      {{-- Balanced Ocean-Teal Contrast Scrim --}}
      <div class="absolute inset-0 bg-gradient-to-b from-[#031e20]/65 via-[#052b2e]/35 to-[#052b2e]/85 z-10 pointer-events-none"></div>
    </div>
    @endforeach
  </div>

  {{-- ── FOREGROUND CONTENT (CENTER STAGE) ─────────────────── --}}
  <div class="relative z-20 max-w-[1240px] w-full mx-auto px-6 md:px-8 xl:px-12 flex-1 flex flex-col justify-center items-center text-center py-8 sm:py-12 my-auto">

    @if(!empty($hp['hero_badge']))
    <p class="text-[14px] sm:text-[15px] font-medium text-[#a3eef5] tracking-[0.01em] mb-4 fds-hero-entrance fds-hero-delay-1">
      {{ $fds_hp_scrub($hp['hero_badge'] ?? 'Produsen Drone Pertanian & UAV Indonesia') }}
    </p>
    @endif

    <h1 class="text-[42px] sm:text-[62px] lg:text-[76px] xl:text-[84px] font-semibold tracking-[-0.03em] text-white leading-[1.04] max-w-[920px] mx-auto fds-hero-entrance fds-hero-delay-2" style="text-wrap:balance;">
      {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_hp_scrub($hp['hero_title'] ?? "Solusi Drone Pertanian &\nIndustrial Indonesia.")))) !!}
    </h1>

    <p class="mt-6 text-[17px] sm:text-[20px] lg:text-[21px] text-white/90 font-normal max-w-[660px] mx-auto leading-[1.55] fds-hero-entrance fds-hero-delay-3" style="text-wrap:pretty;">
      {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_hp_scrub($hp['hero_desc'] ?? 'Produsen UAV dengan manajemen mutu ISO 9001:2015, serta sertifikasi produk TKDN 60,74% (seri FERTO) dan SNI 9199:2023 (FERTO 15 & FERTO 22). Solusi drone pertanian sprayer, pemetaan LiDAR, dan inspeksi industri.')))) !!}
    </p>

    <div class="mt-9 flex items-center justify-center gap-4 flex-wrap fds-hero-entrance fds-hero-delay-4">
      <a href="{!! esc_url($hp['hero_cta1_url'] ?? '#solusi') !!}"
         class="fds-btn fds-btn--primary shadow-lg shadow-black/25">
        {{ $fds_hp_scrub($hp['hero_cta1_text'] ?? 'Jelajahi Solusi Kami') }}
      </a>
      <a href="{!! esc_url($hp['hero_cta2_url'] ?? '#kontak') !!}"
         class="group inline-flex items-center justify-center min-h-[44px] bg-white/10 hover:bg-white/20 text-white backdrop-blur-md border border-white/35 text-[15px] font-medium px-6 py-2.5 rounded-full transition-colors gap-2 leading-none">
        <span>{{ $fds_hp_scrub($hp['hero_cta2_text'] ?? 'Konsultasi Enterprise') }}</span>
        <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M6 6h12v12"/>
          <path d="M6 18 18 6"/>
        </svg>
      </a>
    </div>

  </div>

  {{-- ── BOTTOM SLIDER NAVIGATION BAR ──────────────────────── --}}
  <div class="relative z-20 w-full border-t border-white/10 bg-black/25 backdrop-blur-md py-3 sm:py-3.5">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 flex items-center justify-end">
      
      {{-- Hidden Caption Anchor (Preserves JS Compatibility Without Displaying Text) --}}
      <div id="fds-hero-slide-caption" class="hidden" aria-hidden="true"></div>

      {{-- High-Noticeability UX Controls (Right-Corner Aligned Like Before) --}}
      @if(count($hero_slides) > 1)
      <div class="flex items-center gap-4 sm:gap-5">
        
        {{-- Dot Indicators --}}
        <div class="flex items-center gap-1">
          @foreach($hero_slides as $i => $slide)
          <button type="button" class="fds-slider-dot min-w-[24px] min-h-[24px] px-1 flex items-center justify-center cursor-pointer" data-dot-index="{{ $i }}" aria-label="Slide {{ $i + 1 }}">
            <span class="fds-slider-dot-pill block h-2 sm:h-2.5 rounded-full transition-all duration-300 {{ $i === 0 ? 'w-8 sm:w-10 bg-[#0f808a] shadow-[0_0_12px_rgba(15,128,138,0.85)]' : 'w-2 sm:w-2.5 bg-white/45 hover:bg-white/80' }}"></span>
          </button>
          @endforeach
        </div>

        {{-- Arrows --}}
        <div class="flex items-center gap-2">
          <button type="button" id="fds-slider-prev" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/15 hover:bg-[#0f808a] active:scale-95 text-white flex items-center justify-center border border-white/25 hover:border-[#0f808a] backdrop-blur-md transition-all duration-200 cursor-pointer shadow-md group" aria-label="Slide Sebelumnya">
            <svg class="w-4.5 h-4.5 transition-transform group-hover:-translate-x-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
          </button>
          <button type="button" id="fds-slider-next" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/15 hover:bg-[#0f808a] active:scale-95 text-white flex items-center justify-center border border-white/25 hover:border-[#0f808a] backdrop-blur-md transition-all duration-200 cursor-pointer shadow-md group" aria-label="Slide Selanjutnya">
            <svg class="w-4.5 h-4.5 transition-transform group-hover:translate-x-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>

      </div>
      @endif

    </div>
  </div>

  @if(count($hero_slides) > 1)
  <script>
    (function() {
      const slider = document.getElementById('fds-hero-slider');
      if (!slider) return;
      
      const slides = slider.querySelectorAll('.fds-hero-slide');
      const dots = document.querySelectorAll('.fds-slider-dot');
      const prevBtn = document.getElementById('fds-slider-prev');
      const nextBtn = document.getElementById('fds-slider-next');
      const captionEl = document.getElementById('fds-hero-slide-caption');
      
      const slideTitles = @json(array_column($hero_slides, 'title'));
      const total = slides.length;
      let current = 0;
      let timer = null;
      const interval = 6000; // 6 detik per slide

      function hydrateSlideImage(idx) {
        const s = slides[idx];
        if (!s) return;
        const img = s.querySelector('img[data-slide-src]');
        if (img) {
          const url = img.getAttribute('data-slide-src');
          if (url) {
            img.src = url;
            img.removeAttribute('data-slide-src');
          }
        }
      }

      function showSlide(index) {
        if (index < 0) index = total - 1;
        if (index >= total) index = 0;
        current = index;

        hydrateSlideImage(current);
        hydrateSlideImage((current + 1) % total);

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
          const pill = dot.querySelector('.fds-slider-dot-pill') || dot;
          if (i === current) {
            pill.classList.remove('w-2', 'sm:w-2.5', 'bg-white/45');
            pill.classList.add('w-8', 'sm:w-10', 'bg-[#0f808a]', 'shadow-[0_0_12px_rgba(15,128,138,0.85)]');
          } else {
            pill.classList.remove('w-8', 'sm:w-10', 'bg-[#0f808a]', 'shadow-[0_0_12px_rgba(15,128,138,0.85)]');
            pill.classList.add('w-2', 'sm:w-2.5', 'bg-white/45');
          }
        });

        if (captionEl && slideTitles[current]) {
          captionEl.textContent = slideTitles[current] || '';
        }
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

      // Pause on mouse hover over overview section
      const overview = document.getElementById('overview');
      if (overview) {
        overview.addEventListener('mouseenter', stopAutoPlay);
        overview.addEventListener('mouseleave', startAutoPlay);
      }

      // Touch swipe support on mobile
      let touchStartX = 0;
      let touchEndX = 0;
      if (overview) {
        overview.addEventListener('touchstart', (e) => {
          touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        overview.addEventListener('touchend', (e) => {
          touchEndX = e.changedTouches[0].screenX;
          if (touchStartX - touchEndX > 50) {
            nextSlide();
            startAutoPlay();
          } else if (touchEndX - touchStartX > 50) {
            prevSlide();
            startAutoPlay();
          }
        }, { passive: true });
      }

      // Start autoplay if user has not requested reduced motion
      const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
      if (!prefersReducedMotion || !prefersReducedMotion.matches) {
        startAutoPlay();
      }
    })();
  </script>
  @endif
</section>


{{-- ========================================================== --}}
{{-- 2. MITRA — Infinite marquee scroll                        --}}
{{-- ========================================================== --}}
@php
  $mitra_posts = get_posts(['post_type'=>'mitra','numberposts'=>-1,'orderby'=>'menu_order','order'=>'ASC']);
  $mitra_with_logo = array_values(array_filter($mitra_posts, fn($m) => get_the_post_thumbnail_url($m->ID, 'medium')));
@endphp

@if(!empty($mitra_with_logo))
<section class="fds-sec fds-sec--tight fds-bg-white">
  <div class="fds-container">
    @php
      $mitra_title = $hp['mitra_heading'] ?? 'Dipercaya oleh Lembaga Nasional & Internasional';
      if (mb_strtoupper($mitra_title) === $mitra_title && mb_strlen($mitra_title) > 5) {
          $mitra_title = 'Dipercaya oleh Lembaga Nasional & Internasional';
      }
      $mitra_primary = array_slice($mitra_with_logo, 0, 8);
      $mitra_extra   = array_slice($mitra_with_logo, 8);
    @endphp
    <p class="fds-caption text-center mb-9" data-aos="fade-up">
      {{ $fds_clean($mitra_title) }}
    </p>

    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 items-center justify-items-center gap-x-7 gap-y-6">
      @foreach($mitra_primary as $mitra)
        @php
          $logo = get_the_post_thumbnail_url($mitra->ID, 'medium');
          $m_inst = function_exists('fds_resolve_mitra_alt')
              ? fds_resolve_mitra_alt((int) $mitra->ID, (string) $logo)
              : (trim($fds_clean($mitra->post_title)) ?: 'Mitra Full Drone Solutions');
          $m_alt = $m_inst . ' — Mitra Full Drone Solutions';
        @endphp
        <div class="flex items-center justify-center w-full h-16">
          <img src="{!! esc_url($logo) !!}"
               alt="{{ $m_alt }}"
               width="150"
               height="64"
               loading="lazy"
               decoding="async"
               class="max-h-14 w-auto max-w-[130px] object-contain"
               style="mix-blend-mode: multiply !important; background-color: transparent !important;"
               title="{{ $m_inst }}">
        </div>
      @endforeach
    </div>

    @if(!empty($mitra_extra))
    <div class="text-center">
      <details class="fds-quiet-details inline-block text-left">
        <summary class="fds-quiet-summary">Lihat seluruh {{ count($mitra_with_logo) }} mitra institusi</summary>
        <div class="fds-quiet-body grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 items-center justify-items-center gap-x-7 gap-y-6">
          @foreach($mitra_extra as $mitra)
            @php
              $logo = get_the_post_thumbnail_url($mitra->ID, 'medium');
              $m_inst = function_exists('fds_resolve_mitra_alt')
                  ? fds_resolve_mitra_alt((int) $mitra->ID, (string) $logo)
                  : (trim($fds_clean($mitra->post_title)) ?: 'Mitra Full Drone Solutions');
              $m_alt = $m_inst . ' — Mitra Full Drone Solutions';
            @endphp
            <div class="flex items-center justify-center w-full h-14">
              <img src="{!! esc_url($logo) !!}"
                   alt="{{ $m_alt }}"
                   width="140"
                   height="56"
                   loading="lazy"
                   decoding="async"
                   class="max-h-12 w-auto max-w-[120px] object-contain"
                   style="mix-blend-mode: multiply !important; background-color: transparent !important;"
                   title="{{ $m_inst }}">
            </div>
          @endforeach
        </div>
      </details>
    </div>
    @endif
  </div>
</section>
@endif



{{-- ========================================================== --}}
{{-- 3. SOLUSI INDUSTRI — Ocean-Teal Wave + Grid 6 Sektor       --}}
{{-- ========================================================== --}}
@php
  $solusi_data = function_exists('App\fds_get_solusi_data') ? \App\fds_get_solusi_data() : [
    'badge'    => 'Solusi Industri FDS',
    'title'    => 'Satu Platform UAV. Enam Sektor Kerja Strategis.',
    'desc'     => 'Integrasi platform drone, sensor pemetaan dan inspeksi, serta pengolahan data geospasial untuk mendukung keputusan operasional lintas sektor.',
    'cta_text' => 'Lihat semua layanan',
    'cta_url'  => home_url('/layanan/'),
    'cards'    => [],
  ];
@endphp
<section id="solusi" class="fds-sec fds-bg-teal">
  <x-fds-wave type="fluid" uid="home-solusi" />

  <div class="fds-container">
    <x-reveal id="solusi-header-container" class="fds-sec-head fds-sec-head--split">
      <div>
        @if(!empty($solusi_data['badge']))
          <p class="fds-caption" style="color:var(--color-teal-200);font-weight:600;margin-bottom:10px;">{{ $fds_clean($solusi_data['badge']) }}</p>
        @endif
        <h2 class="fds-h2" style="color:var(--fds-white);max-width:620px;">
          {{ $fds_clean($solusi_data['title']) }}
        </h2>
      </div>
      <div class="flex flex-col items-start md:items-end gap-4 max-w-[440px]">
        <p class="fds-body" style="color:rgba(255,255,255,0.88);">
          {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_clean($solusi_data['desc'])))) !!}
        </p>
        @if(!empty($solusi_data['cta_text']))
        <a href="{!! esc_url(!empty($solusi_data['cta_url']) ? $solusi_data['cta_url'] : home_url('/layanan/')) !!}"
           class="fds-btn fds-btn--white-teal">
          {{ $fds_clean($solusi_data['cta_text']) }}
        </a>
        @endif
      </div>
    </x-reveal>

    @if(!empty($solusi_data['cards']))
    <x-reveal-group :step="60" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-7 gap-y-10">
      @foreach($solusi_data['cards'] as $card)
      @php
        $card_href      = !empty($card['link_url']) ? $card['link_url'] : home_url('/layanan/');
        $slug_match     = trim((string) basename(parse_url((string) $card_href, PHP_URL_PATH) ?: ''), '/');
        $sector_img_map = [
            'pertambangan'           => 'pertambangan.webp',
            'perkebunan-pertanian'   => 'perkebunan-pertanian.webp',
            'kehutanan-lingkungan'   => 'kehutanan-lingkungan.webp',
            'energi-infrastruktur'   => 'energi-infrastruktur.webp',
            'perencanaan'            => 'perencanaan.webp',
            'bencana-lingkungan'     => 'bencana-lingkungan.webp',
        ];
        $local_sector_img = get_template_directory_uri() . '/public/images/sectors/' . ($sector_img_map[$slug_match] ?? 'perencanaan.webp');
        $raw_card_img     = trim((string) ($card['image'] ?? ''));
        $card_img_raw     = ($raw_card_img !== '' && stripos($raw_card_img, 'unsplash.com') === false)
            ? $raw_card_img
            : $local_sector_img;
        $card_img_url = function_exists('App\fds_optimized_img_url')
          ? \App\fds_optimized_img_url($card_img_raw, 'large')
          : (function_exists('fds_optimized_img_url') ? fds_optimized_img_url($card_img_raw, 'large') : $card_img_raw);
        $card_alt_raw = !empty($card['image_alt']) ? $card['image_alt'] : ($card['title'] ?? '');
      @endphp
      <article class="fds-photo-card group">
        <div class="fds-photo-card-frame" style="background-color:#031e20;">
          <img src="{!! esc_url($card_img_url) !!}"
               alt="{{ $fds_clean($card_alt_raw) }}"
               width="720"
               height="450"
               loading="lazy"
               decoding="async">
        </div>
        <h3 class="fds-h3 flex items-center justify-between gap-3" style="color:var(--fds-white);margin-bottom:8px;">
          <a href="{!! esc_url($card_href) !!}" class="fds-stretched-link text-white group-hover:text-[#a3eef5] transition-colors" style="text-decoration:none;">
            {{ $fds_clean($card['title'] ?? '') }}
          </a>
          <span class="fds-icon-cue" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>
          </span>
        </h3>
        @if(!empty($card['desc']))
        <p class="fds-body" style="color:rgba(255,255,255,0.86);font-size:0.9375rem;">
          {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_clean($card['desc'])))) !!}
        </p>
        @endif
      </article>
      @endforeach
    </x-reveal-group>
    @endif
  </div>
</section>


{{-- ========================================================== --}}
{{-- 4. KEUNGGULAN — Bento grid (Masonry Reference Architecture) --}}
{{-- ========================================================== --}}
<section id="keunggulan" class="fds-sec fds-bg-light">
  <div class="fds-container">

    {{-- Section header --}}
    <x-reveal class="fds-sec-head">
      <p class="fds-caption" style="color:var(--color-teal-800);font-weight:600;">
        {{ $fds_hp_scrub($hp['keunggulan_badge'] ?? 'Keunggulan Manufaktur FDS') }}
      </p>
      <h2 class="fds-h2" style="color:var(--fds-ink-primary);max-width:700px;">
        {{ $fds_hp_scrub($hp['keunggulan_title'] ?? 'Mengapa Memilih Produsen Drone Lokal?') }}
      </h2>
    </x-reveal>

    {{-- Bento Grid --}}
    <x-reveal-group :step="90" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

      {{-- Card 1: Kiri Atas Lebar — Manufaktur Sleman --}}
      @php
        $card1_default_img = get_template_directory_uri() . '/public/images/site/workshop-deltav.webp';
        $card1_raw_img     = trim((string) ($hp['keunggulan_card1_img'] ?? fds_img('keunggulan', '')));
        $card1_final_img   = ($card1_raw_img !== '' && stripos($card1_raw_img, 'unsplash.com') === false)
            ? $card1_raw_img
            : $card1_default_img;
      @endphp
      <div class="md:col-span-2 lg:col-span-2 bg-[#052b2e] rounded-3xl overflow-hidden relative min-h-[320px] sm:min-h-[280px] group flex flex-col justify-end p-6 sm:p-8 shadow-[0_8px_30px_rgba(0,0,0,0.05)]">
        <div class="absolute inset-0 z-0">
          <img src="{!! esc_url(fds_optimized_img_url($card1_final_img, 'large')) !!}"
               alt="Pabrik &amp; Workshop FDS"
               width="1200"
               height="675"
               loading="lazy"
               decoding="async"
               class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700">
          <div class="absolute inset-0 bg-gradient-to-t from-[#031e20]/95 via-[#031e20]/60 via-48% to-transparent"></div>
        </div>
        <div class="relative z-10">
          <h3 class="text-[20px] sm:text-[24px] font-semibold text-white tracking-[-0.02em] leading-snug mb-2 max-w-[600px]">
            {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_hp_scrub($hp['keunggulan_card1_title'] ?? "Desain Aerodinamis & Avionik In-House.")))) !!}
          </h3>
          <p class="text-[14px] text-white/90 max-w-[560px] leading-relaxed">
            {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_hp_scrub($hp['keunggulan_card1_desc'] ?? 'Rangka karbon komposit lokal, avionik in-house, dan integrasi payload kustom di workshop PT Karya Solusi Angkasa (FDS).')))) !!}
          </p>
        </div>
      </div>

      {{-- Card 2: Kolom Kanan Tinggi 2 Baris — TKDN & Bangga Buatan Indonesia --}}
      @php
        $card2_default_img = get_template_directory_uri() . '/public/images/bangga-buatan-indonesia.svg';
        $card2_raw_img     = trim((string) ($hp['keunggulan_card2_img'] ?? ''));
        $card2_img         = ($card2_raw_img !== '' && stripos($card2_raw_img, 'unsplash.com') === false) ? $card2_raw_img : $card2_default_img;
        $card2_desc_text   = $fds_hp_scrub($hp['keunggulan_card2_desc'] ?? 'Sertifikat resmi TKDN + BMP untuk seluruh model drone pertanian seri FERTO. Memenuhi prioritas belanja pemerintah di e-Katalog LKPP.');
      @endphp
      <div class="md:col-span-2 lg:col-span-1 lg:row-span-2 bg-gradient-to-b md:bg-gradient-to-r lg:bg-gradient-to-b from-[#0f808a] via-[#0a555c] to-[#052b2e] rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row lg:flex-col justify-between md:items-center lg:items-stretch gap-6 min-h-[300px] md:min-h-[220px] lg:min-h-full relative overflow-hidden group shadow-[0_8px_30px_rgba(15,128,138,0.12)]">
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
          <svg class="absolute -bottom-10 -left-10 w-[550px] h-[550px]" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="40" cy="460" r="440" fill="#ffffff" fill-opacity="0.04" />
            <circle cx="40" cy="460" r="330" fill="#ffffff" fill-opacity="0.06" />
            <circle cx="40" cy="460" r="230" fill="#ffffff" fill-opacity="0.09" />
            <circle cx="40" cy="460" r="140" fill="#ffffff" fill-opacity="0.14" />
            <circle cx="40" cy="460" r="70" fill="#ffffff" fill-opacity="0.20" />
          </svg>
        </div>
        <div class="absolute inset-x-0 bottom-0 h-56 bg-gradient-to-t from-[#031e20]/90 via-[#052b2e]/45 to-transparent pointer-events-none z-0"></div>

        <div class="relative z-10 flex-1 md:max-w-[58%] lg:max-w-full">
          <div class="my-1">
            <p class="fds-num text-[48px] sm:text-[56px] font-semibold text-white tracking-[-0.04em] leading-none">{{ $fds_hp_scrub($hp['keunggulan_card2_stat'] ?? '60,74%') }}</p>
            <p class="text-[13.5px] font-medium text-white/95 mt-1.5">{{ $fds_hp_scrub($hp['keunggulan_card2_substat'] ?? 'TKDN + BMP Seri FERTO Kemenperin RI') }}</p>
          </div>
          <p class="block lg:hidden text-[14px] text-white/90 leading-relaxed mt-3">
            {{ $card2_desc_text }}
          </p>
        </div>

        <div class="relative z-10 my-auto py-2 flex justify-center items-center flex-shrink-0 md:max-w-[38%] lg:max-w-full">
          <img src="{!! esc_url(fds_optimized_img_url($card2_img, 'medium')) !!}"
               alt="Bangga Buatan Indonesia"
               width="320"
               height="180"
               loading="lazy"
               decoding="async"
               class="w-auto max-w-[95%] sm:max-w-full max-h-[140px] md:max-h-[160px] lg:max-h-[205px] object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-500">
        </div>

        <div class="hidden lg:block relative z-10 pt-4 border-t border-white/15">
          <p class="text-[13.5px] text-white/90 leading-relaxed">
            {{ $card2_desc_text }}
          </p>
        </div>
      </div>

      {{-- Card 3: Tengah Kiri — Software Kendali FDS Station --}}
      <div class="md:col-span-1 lg:col-span-1 bg-gradient-to-br from-[#0f808a] via-[#0a555c] to-[#052b2e] rounded-3xl p-6 sm:p-8 flex flex-col justify-end min-h-[195px] sm:min-h-[210px] relative overflow-hidden group shadow-[0_8px_30px_rgba(15,128,138,0.12)]">
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
          <svg class="w-full h-full" viewBox="0 0 320 200" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
            <defs>
              <linearGradient id="gcs-wave-fill1" x1="0%" y1="100%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#ffffff" stop-opacity="0.25" />
                <stop offset="60%" stop-color="#75e6f0" stop-opacity="0.16" />
                <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0" />
              </linearGradient>
              <linearGradient id="gcs-wave-fill2" x1="0%" y1="100%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#a3eef5" stop-opacity="0.22" />
                <stop offset="70%" stop-color="#46deec" stop-opacity="0.12" />
                <stop offset="100%" stop-color="#052b2e" stop-opacity="0.0" />
              </linearGradient>
              <linearGradient id="gcs-wave-fill3" x1="100%" y1="100%" x2="0%" y2="0%">
                <stop offset="0%" stop-color="#ffffff" stop-opacity="0.20" />
                <stop offset="50%" stop-color="#18d6e7" stop-opacity="0.15" />
                <stop offset="100%" stop-color="#052b2e" stop-opacity="0.0" />
              </linearGradient>
            </defs>
            <path d="M0,170 C90,160 150,110 250,75 C290,60 310,50 320,40 L320,200 L0,200 Z" fill="url(#gcs-wave-fill1)" />
            <path d="M0,195 C120,190 190,140 280,95 C305,80 315,70 320,60 L320,200 L0,200 Z" fill="url(#gcs-wave-fill2)" />
            <path d="M140,200 C190,170 250,150 320,110 L320,200 Z" fill="url(#gcs-wave-fill3)" />
          </svg>
        </div>
        <div class="relative z-10">
          <h3 class="text-[22px] sm:text-[26px] font-semibold text-white tracking-[-0.03em] leading-snug mb-2">
            {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_hp_scrub($hp['keunggulan_card3_title'] ?? "FDS Station GCS\nBahasa Indonesia.")))) !!}
          </h3>
          <p class="text-[14px] text-white/90 leading-relaxed">
            {{ $fds_hp_scrub($hp['keunggulan_card3_desc'] ?? 'Misi otomatis & telemetri Bahasa Indonesia.') }}
          </p>
        </div>
      </div>

      {{-- Card 4: Tengah Kanan — ISO & SNI --}}
      <div class="md:col-span-1 lg:col-span-1 bg-gradient-to-br from-[#0a555c] via-[#052b2e] to-[#031e20] rounded-3xl p-6 sm:p-8 flex flex-col justify-end min-h-[195px] sm:min-h-[210px] relative overflow-hidden group shadow-[0_8px_30px_rgba(0,0,0,0.06)]">
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
          <svg class="absolute -top-6 -right-6 w-[400px] sm:w-[440px] h-[400px] sm:h-[440px]" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="370" cy="30" r="320" fill="#18d6e7" fill-opacity="0.06" />
            <circle cx="370" cy="30" r="220" fill="#18d6e7" fill-opacity="0.11" />
            <circle cx="370" cy="30" r="130" fill="#18d6e7" fill-opacity="0.18" />
            <circle cx="370" cy="30" r="60" fill="#18d6e7" fill-opacity="0.28" />
          </svg>
        </div>
        <div class="relative z-10">
          <p class="text-[46px] sm:text-[56px] lg:text-[62px] font-semibold text-white tracking-[-0.03em] leading-[0.95] mb-2.5">{{ $fds_hp_scrub($hp['keunggulan_card4_stat'] ?? 'ISO & SNI') }}</p>
          <p class="text-[14px] text-white/90 leading-relaxed max-w-[380px]">
            {{ $fds_hp_scrub($hp['keunggulan_card4_desc'] ?? 'Sistem manajemen mutu perusahaan ISO 9001:2015 serta standar keselamatan produk SNI 9199:2023 pada FERTO 15 dan FERTO 22.') }}
          </p>
        </div>
      </div>

      {{-- Card 6: Bawah Kiri — Akademi Pilot & Regulasi DKPPU --}}
      @php
        $card6_default_img = get_template_directory_uri() . '/public/images/site/about-team.webp';
        $card6_raw_img     = trim((string) ($hp['keunggulan_card6_img'] ?? ''));
        $card6_img         = ($card6_raw_img !== '' && stripos($card6_raw_img, 'unsplash.com') === false) ? $card6_raw_img : $card6_default_img;
        $card6_stat_raw    = trim((string) ($hp['keunggulan_card6_stat'] ?? ''));
        $card6_stat        = ($card6_stat_raw !== '' && $card6_stat_raw !== '2012') ? $fds_hp_scrub($card6_stat_raw) : 'DKPPU';
        $card6_desc_raw    = trim((string) ($hp['keunggulan_card6_desc'] ?? ''));
        if ($card6_desc_raw === '' || preg_match('/sejak\s*(?:tahun\s*)?2012|\b2012\b/i', $card6_desc_raw)) {
            $card6_desc = 'Pelatihan operator drone industri dan pendampingan persiapan sertifikasi pilot sesuai regulasi CASR Part 107.';
        } else {
            $card6_desc = $fds_hp_scrub($card6_desc_raw);
        }
      @endphp
      <div class="md:col-span-1 lg:col-span-1 bg-[#031e20] rounded-3xl overflow-hidden relative min-h-[200px] group flex flex-col justify-end p-6 sm:p-8 shadow-[0_8px_30px_rgba(0,0,0,0.06)]">
        <div class="absolute inset-0 z-0">
          <img src="{!! esc_url(fds_optimized_img_url($card6_img, 'large')) !!}"
               alt="Akademi Pilot &amp; Riset FDS"
               width="800"
               height="500"
               loading="lazy"
               decoding="async"
               class="w-full h-full object-cover brightness-[0.95] group-hover:scale-105 transition-all duration-700">
          <div class="absolute inset-0 bg-gradient-to-t from-[#031e20]/90 via-[#031e20]/45 to-transparent"></div>
        </div>
        <div class="relative z-10">
          <p class="text-[54px] sm:text-[66px] lg:text-[74px] font-semibold text-white tracking-[-0.04em] leading-[0.9] mb-2.5">{{ $card6_stat }}</p>
          <p class="text-[14px] text-white/95 leading-relaxed max-w-[380px]">
            {{ $card6_desc }}
          </p>
        </div>
      </div>

      {{-- Card 5: Bawah Kanan — Satu ekosistem (Clean Typographic List, Zero Pill Slop) --}}
      <div class="md:col-span-1 lg:col-span-2 bg-[#052b2e] rounded-3xl p-6 sm:p-8 min-h-[200px] flex flex-col justify-between gap-4 relative overflow-hidden shadow-[0_8px_30px_rgba(0,0,0,0.10)]">
        <x-fds-wave type="eko" uid="home-keunggulan-eko" />
        <div class="relative z-10 max-w-[600px]">
          <h3 class="text-[22px] sm:text-[26px] font-semibold text-white tracking-[-0.02em] leading-snug mb-2">
            {{ $fds_hp_scrub($hp['keunggulan_card7_title'] ?? 'Satu ekosistem. Banyak solusi.') }}
          </h3>
          <p class="text-[14px] text-white/90 leading-relaxed">
            {{ $fds_hp_scrub($hp['keunggulan_card7_desc'] ?? 'Agrikultur, pemetaan topografi, inspeksi infrastruktur, kehutanan, dan pertambangan.') }}
          </p>
        </div>
        @php
          $chips_raw = (string) ($hp['keunggulan_card7_chips'] ?? 'Agri, Mapping, Inspeksi, Tambang, Hutan, BUMN');
          $chips_arr = array_values(array_filter(array_map('trim', explode(',', $chips_raw)), fn($s) => $s !== ''));
          if (empty($chips_arr)) {
              $chips_arr = ['Agri', 'Mapping', 'Inspeksi', 'Tambang', 'Hutan', 'BUMN'];
          }
          $chips_clean = array_map(fn($s) => $fds_hp_scrub($s), $chips_arr);
        @endphp
        <p class="relative z-10 text-[14px] font-medium text-white/95 tracking-[0.01em] pt-3 border-t border-white/15">
          {{ implode(' · ', $chips_clean) }}
        </p>
      </div>

    </x-reveal-group>
  </div>
</section>


{{-- ========================================================== --}}
{{-- 5. PRODUCT LINEUP — Header Dinamis, List dari CPT Drone   --}}
{{-- ========================================================== --}}
@php
  $hp_compare_text    = trim($fds_hp_scrub($hp['produk_compare_text'] ?? '')) ?: 'Bandingkan Model';
  $hp_catalog_text    = trim($fds_hp_scrub($hp['produk_catalog_text'] ?? '')) ?: 'Lihat Semua Katalog';
  $hp_filter_all      = trim($fds_hp_scrub($hp['produk_filter_all'] ?? '')) ?: 'Semua';
  $hp_row_detail_text = trim($fds_hp_scrub($hp['produk_row_detail_text'] ?? '')) ?: 'Detail';
  $hp_prod_empty_text = trim($fds_hp_scrub($hp['produk_empty_text'] ?? '')) ?: 'Produk kategori ini akan segera hadir.';
  $hp_prod_empty_cta  = trim($fds_hp_scrub($hp['produk_empty_cta_text'] ?? '')) ?: 'Hubungi Tim Sales FDS';
@endphp
<section id="produk" class="fds-sec fds-bg-white">
  <div class="fds-container">

    <x-reveal class="fds-sec-head fds-sec-head--split" style="margin-bottom:40px;">
      <div>
        <p class="fds-caption" style="color:var(--color-teal-800);font-weight:600;margin-bottom:10px;">{{ $fds_hp_scrub($hp['produk_badge'] ?? 'Lini Produk Drone') }}</p>
        <h2 class="fds-h2" style="color:var(--fds-ink-primary);">
          {{ $fds_hp_scrub($hp['produk_title'] ?? 'Teknologi UAV Rekayasa Indonesia.') }}
        </h2>
        <p class="fds-lead" style="margin-top:16px;">
          {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_hp_scrub($hp['produk_desc'] ?? 'TKDN + BMP 60,74% pada seri FERTO, SNI 9199:2023 untuk FERTO 15 & 22, software FDS Station Bahasa Indonesia, dan garansi purna jual resmi.')))) !!}
        </p>
      </div>
      <div class="fds-btn-group flex-shrink-0">
        <a href="{!! esc_url(home_url('/bandingkan/')) !!}"
           class="fds-btn" style="background-color:#f5f5f7;color:#1d1d1f;border-color:#d2d2d7;">
          {{ $hp_compare_text }}
        </a>
        <a href="{!! esc_url(home_url('/produk/')) !!}"
           class="fds-btn fds-btn--primary group">
          <span>{{ $hp_catalog_text }}</span>
          <svg class="w-4 h-4 transition-transform duration-150 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
      </div>
    </x-reveal>

    @php
      // 100% Dynamic WP Query from 'kategori_drone' Taxonomy & 'drone' CPT
      $tax_terms = get_terms([
          'taxonomy'   => 'kategori_drone',
          'hide_empty' => true,
          'orderby'    => 'term_id',
          'order'      => 'ASC',
      ]);

      $categories = [$hp_filter_all];
      if (!empty($tax_terms) && !is_wp_error($tax_terms)) {
          foreach ($tax_terms as $t) {
              if (strtolower((string) $t->slug) === 'reboisasi') {
                  continue;
              }
              $t_clean = $fds_clean($t->name);
              if ($t_clean !== '' && !in_array($t_clean, $categories, true)) {
                  $categories[] = $t_clean;
              }
          }
      } else {
          $categories = [$hp_filter_all, 'Agrikultur', 'Pemetaan & GIS', 'Kargo'];
      }

      $wp_drones = get_posts([
          'post_type'      => 'drone',
          'posts_per_page' => -1,
          'post_status'    => 'publish',
          'orderby'        => 'ID',
          'order'          => 'ASC',
      ]);
      if (function_exists('fds_sort_drone_posts') && !empty($wp_drones)) {
          $wp_drones = fds_sort_drone_posts($wp_drones);
      }

      $products = [];
      if (!empty($wp_drones)) {
          foreach ($wp_drones as $d) {
              $d_slug_chk = strtolower((string) $d->post_name);
              if (strpos($d_slug_chk, 'rebo') !== false || strpos($d_slug_chk, 'agrea') !== false || strpos($d_slug_chk, 'ferto-20') !== false) {
                  continue;
              }
              $terms    = get_the_terms($d->ID, 'kategori_drone');
              $d_cat    = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : (get_post_meta($d->ID, 'drone_kategori', true) ?: 'Agrikultur');
              if (stripos((string) $d_cat, 'rebo') !== false) {
                  continue;
              }
              $d_cat_clean = $fds_clean($d_cat);

              $s_lower   = $d_slug_chk;
              $is_ferto  = (strpos($s_lower, 'ferto') === 0);
              $has_sni   = in_array($s_lower, ['ferto-15l', 'ferto-22l', 'ferto-15', 'ferto-22'], true);

              $d_badge   = get_post_meta($d->ID, 'drone_badge', true) ?: 'Unggulan';
              $d_tagline = get_post_meta($d->ID, 'drone_tagline', true);
              $d_desc    = get_post_meta($d->ID, 'drone_desc', true) ?: $d->post_content;
              $d_specs   = get_post_meta($d->ID, 'drone_specs_raw', true);
              $d_thumb   = get_the_post_thumbnail_url($d->ID, 'thumbnail');

              $d_name_clean  = $fds_hp_scrub(get_the_title($d->ID), $is_ferto, $has_sni);
              $d_badge_clean = $fds_hp_scrub($d_badge, $is_ferto, $has_sni);
              $d_desc_plain  = $fds_hp_scrub(wp_strip_all_tags((string) ($d_tagline ?: wp_trim_words((string) $d_desc, 18))), $is_ferto, $has_sni);

              // Extract first 2-3 lines of specs for row preview (guardrail-safe)
              $spec_summary = '';
              if ($d_specs) {
                  $raw_lines = array_filter(array_map('trim', explode("\n", (string) $d_specs)));
                  $parts = [];
                  foreach ($raw_lines as $l) {
                      $sp = explode(':', $l, 2);
                      if (count($sp) === 2) {
                          $sp_lbl = $fds_hp_scrub(trim($sp[0]), $is_ferto, $has_sni);
                          $sp_is_cert = stripos($sp_lbl, 'sertifikasi') !== false;
                          $sp_val = $fds_hp_scrub(trim($sp[1]), $is_ferto, $has_sni, !$sp_is_cert);
                          if (in_array($s_lower, ['ferto-15l', 'ferto-15'], true) && (stripos($sp_lbl, 'kapasitas') !== false || stripos($sp_lbl, 'payload') !== false || stripos($sp_lbl, 'tangki') !== false)) {
                              if (stripos($sp_val, '17') === false) {
                                  $sp_val = '17 Liter';
                              }
                          }
                          if ($sp_val !== '') {
                              $parts[] = $sp_val;
                          }
                      }
                      if (count($parts) >= 3) break;
                  }
                  $spec_summary = implode(' · ', $parts);
              }
              $spec_fallback = $has_sni
                  ? 'TKDN 60,74% · SNI 9199:2023 · FDS Station'
                  : ($is_ferto ? 'TKDN 60,74% · FDS Station' : 'FDS Station GCS · Manufaktur Lokal');

              $products[] = [
                  'id'    => $d->ID,
                  'url'   => get_permalink($d->ID),
                  'slug'  => $d->post_name,
                  'name'  => $d_name_clean,
                  'cat'   => $d_cat_clean,
                  'badge' => $d_badge_clean !== '' ? $d_badge_clean : 'Unggulan',
                  'desc'  => $d_desc_plain,
                  'specs' => $spec_summary !== '' ? $spec_summary : $spec_fallback,
                  'thumb' => $d_thumb,
              ];

              if ($d_cat_clean !== '' && !in_array($d_cat_clean, $categories, true)) {
                  $categories[] = $d_cat_clean;
              }
          }
      }
    @endphp

    {{-- Category filter tabs (Dinamis dari WordPress) --}}
    <div class="flex flex-wrap gap-2 mb-10" id="drone-cat-tabs" data-aos="fade-up" data-aos-delay="100">
      @foreach($categories as $cat)
      <button
        type="button"
        data-cat="{{ strip_tags($cat) }}"
        data-all="{{ $loop->first ? '1' : '0' }}"
        onclick="filterDrones(this)"
        class="drone-tab {{ $loop->first ? 'active' : '' }}">
        {{ $cat }}
      </button>
      @endforeach
    </div>

    {{-- Product rows (Horizontal list layout original di beranda — Rule 15) --}}
    <x-reveal-group :step="50" class="space-y-0 border-t border-[#e5e5ea]" id="drone-list">
      @php
        $global_drone_icon = function_exists('App\fds_get_drone_icon') ? \App\fds_get_drone_icon() : (function_exists('App\fds_get_navbar_drone_icon') ? \App\fds_get_navbar_drone_icon() : '');
      @endphp

      @foreach($products as $p)
      <article class="drone-row relative border-b border-[#e5e5ea]" data-cat="{{ $p['cat'] }}">
        <div class="grid grid-cols-12 gap-4 py-6 items-center group hover:bg-[#fbfbfd] rounded-2xl px-4 -mx-4 transition-colors duration-150">

          {{-- Icon --}}
          <div class="col-span-1 hidden sm:flex">
            <div class="w-11 h-11 bg-[#f5f5f7] rounded-xl flex items-center justify-center group-hover:bg-white transition-colors flex-shrink-0">
              @if(!empty($global_drone_icon))
                <div class="w-[26px] h-[26px] bg-[#0a555c] transition-colors flex-shrink-0"
                     style="-webkit-mask-image: url('{!! esc_url($global_drone_icon) !!}'); mask-image: url('{!! esc_url($global_drone_icon) !!}'); -webkit-mask-size: contain; mask-size: contain; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center;"
                     role="img" aria-label="Drone Icon"></div>
              @else
                <svg class="w-6 h-6 text-[#0a555c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
              @endif
            </div>
          </div>

          {{-- Name + category --}}
          <div class="col-span-7 sm:col-span-3">
            <p class="text-[13px] font-medium text-[#0a555c] mb-0.5">{{ $p['cat'] }}</p>
            <h3 class="text-[20px] font-semibold text-[#1d1d1f] tracking-tight leading-snug">
              <a href="{!! esc_url($p['url']) !!}" class="fds-stretched-link text-[#1d1d1f] group-hover:text-[#0a555c] transition-colors" style="text-decoration:none;">
                {{ $p['name'] }}
              </a>
            </h3>
          </div>

          {{-- Description --}}
          <div class="hidden md:block col-span-4">
            <p class="text-[15px] text-[#424245] leading-relaxed">{{ $p['desc'] }}</p>
          </div>

          {{-- Specs + Detail Cue --}}
          <div class="col-span-5 sm:col-span-4 flex flex-col sm:flex-row items-end sm:items-center justify-end gap-2 sm:gap-5">
            <p class="hidden lg:block text-[13.5px] text-[#5c5e63]">{{ $p['specs'] }}</p>
            <span class="fds-icon-cue text-[14px] font-semibold text-[#0a555c] whitespace-nowrap inline-flex items-center gap-1.5 leading-none flex-shrink-0" aria-hidden="true">
              <span>{{ $hp_row_detail_text }}</span>
              <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 6h12v12"/>
                <path d="M6 18 18 6"/>
              </svg>
            </span>
          </div>

        </div>
      </article>
      @endforeach

      {{-- Empty state untuk kategori yang belum ada produk --}}
      <div id="drone-empty" class="hidden py-16 text-center border-b border-[#e5e5ea]">
        <p class="fds-body" style="margin-inline:auto;">{{ $hp_prod_empty_text }}</p>
        <a href="{!! esc_url(home_url('/kontak/#hubungi-form')) !!}" class="fds-text-link mt-3 group">
          <span>{{ $hp_prod_empty_cta }}</span>
          <svg class="w-4 h-4 transition-transform duration-150 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
      </div>

    </x-reveal-group>

    {{-- USP strip (Hairline Stats Band — Dinamis & Adaptif) --}}
    @php
      $usp_stats_list = [];
      for ($ui = 1; $ui <= 4; $ui++) {
          $uNum = trim((string)($hp["produk_stat{$ui}_num"] ?? ''));
          $uLbl = trim((string)($hp["produk_stat{$ui}_lbl"] ?? ''));
          if ($uNum !== '' || $uLbl !== '') {
              $usp_stats_list[] = [
                  'num' => $fds_hp_scrub($uNum),
                  'lbl' => $fds_hp_scrub($uLbl),
              ];
          }
      }
      $total_usp_stats = count($usp_stats_list);
    @endphp

    @if($total_usp_stats > 0)
    <div class="fds-stats-band {{ $total_usp_stats >= 4 ? 'fds-stats-band--4' : '' }} mt-12">
      @foreach($usp_stats_list as $ust)
      <div class="fds-stat-cell">
        @if(!empty($ust['num']))
        <span class="fds-stat-num">{{ $ust['num'] }}</span>
        @endif
        @if(!empty($ust['lbl']))
        <span class="fds-stat-label">{{ $ust['lbl'] }}</span>
        @endif
      </div>
      @endforeach
    </div>
    @endif

  </div>
</section>

<script>
function filterDrones(btn) {
  const cat = (btn.dataset.cat || '').trim();
  const isAll = btn.dataset.all === '1' || cat.toLowerCase() === 'semua';
  
  // Update active class
  document.querySelectorAll('.drone-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');

  // Filter grid cards
  const rows = document.querySelectorAll('.drone-row');
  let visible = 0;
  rows.forEach(row => {
    const rcat = (row.dataset.cat || '').trim();
    const match = isAll || (rcat.toLowerCase() === cat.toLowerCase());
    row.style.display = match ? '' : 'none';
    if (match) {
      row.classList.add('aos-init', 'aos-animate');
      visible++;
    }
  });
  const emptyEl = document.getElementById('drone-empty');
  if (emptyEl) {
    emptyEl.style.display = (visible === 0) ? '' : 'none';
  }
}
</script>



{{-- ========================================================== --}}
{{-- ========================================================== --}}
{{-- 5. LAYANAN ENTERPRISE                                     --}}
{{-- ========================================================== --}}
@php
  $layanan_items = function_exists('App\fds_get_layanan_items') ? \App\fds_get_layanan_items() : [];
@endphp
@if(!empty($layanan_items) || !empty($hp['layanan_title']))
<section id="layanan" class="fds-sec fds-bg-dark">
  <x-fds-wave type="sektor" uid="home-layanan" />

  <div class="fds-container">
    <div class="fds-sticky-split">

      <x-reveal-group :step="100" class="fds-sticky-col">
        @if(!empty($hp['layanan_badge']))
        <p class="fds-caption" style="color:var(--color-teal-200);font-weight:600;margin-bottom:10px;">{{ $fds_hp_scrub($hp['layanan_badge']) }}</p>
        @endif
        @if(!empty($hp['layanan_title']))
        <h2 class="fds-h2" style="color:var(--fds-white);margin-bottom:18px;">
          {{ $fds_hp_scrub($hp['layanan_title']) }}
        </h2>
        @endif
        @if(!empty($hp['layanan_desc']))
        <p class="fds-lead" style="color:rgba(255,255,255,0.88);margin-bottom:28px;">
          {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_hp_scrub($hp['layanan_desc'])))) !!}
        </p>
        @endif
        @if(!empty($hp['layanan_cta_text']))
        @php
          $layanan_cta_href = trim((string) ($hp['layanan_cta_url'] ?? '')) ?: home_url('/layanan/');
        @endphp
        <a href="{!! esc_url($layanan_cta_href) !!}" class="fds-btn fds-btn--white-teal">
          {{ $fds_hp_scrub($hp['layanan_cta_text']) }}
        </a>
        @endif
      </x-reveal-group>

      @if(!empty($layanan_items))
      @php
        $hp_layanan_detail_text = trim($fds_hp_scrub($hp['layanan_detail_text'] ?? '')) ?: 'Lihat Detail';
      @endphp
      <x-reveal-group :step="80" class="fds-hairline-list">
        @foreach($layanan_items as $lItem)
        @php
          $item_href = (!empty($lItem['url']) && $lItem['url'] !== '#') ? $lItem['url'] : home_url('/layanan/');
        @endphp
        <article class="fds-hairline-row fds-hairline-row--split fds-hairline-row--interactive group">
          <div>
            <h3 class="fds-h3" style="color:var(--fds-white);margin-bottom:6px;">
              <a href="{!! esc_url($item_href) !!}" class="fds-stretched-link text-white group-hover:text-[#a3eef5] transition-colors" style="text-decoration:none;">
                {{ $fds_clean($lItem['title']) }}
              </a>
            </h3>
            @if(!empty($lItem['desc']))
            <p class="fds-body" style="color:rgba(255,255,255,0.84);font-size:0.9375rem;">
              {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_clean($lItem['desc'])))) !!}
            </p>
            @endif
          </div>
          <span class="fds-icon-cue justify-self-end" aria-hidden="true">
            <span class="sr-only">{{ $hp_layanan_detail_text }}</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>
          </span>
        </article>
        @endforeach
      </x-reveal-group>
      @endif

    </div>
  </div>
</section>
@endif


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
  $news_primary = !empty($recent_posts) ? array_slice($recent_posts, 0, 3) : [];
  $news_more    = !empty($recent_posts) ? array_slice($recent_posts, 3) : [];
@endphp
<section id="blog" class="fds-sec fds-bg-light">
  <div class="fds-container">
    <x-reveal id="news-header-container" class="fds-sec-head fds-sec-head--split">
      <div>
        <p class="fds-caption" style="color:var(--color-teal-800);font-weight:600;margin-bottom:10px;">{{ $fds_hp_scrub($hp['blog_badge'] ?? 'Newsroom') }}</p>
        <h2 class="fds-h2" style="color:var(--fds-ink-primary);max-width:620px;">
          {{ $fds_hp_scrub($hp['blog_title'] ?? 'Berita & Pembaruan Terkini.') }}
        </h2>
      </div>
      <a href="{!! esc_url(home_url('/blog/')) !!}" class="fds-text-link group flex-shrink-0">
        <span>{{ $fds_hp_scrub($hp['blog_cta_text'] ?? 'Lihat semua artikel') }}</span>
        <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M6 6h12v12"/>
          <path d="M6 18 18 6"/>
        </svg>
      </a>
    </x-reveal>

    @if(!empty($news_primary))
    <x-reveal-group :step="70" class="fds-photo-cards-3">
      @foreach($news_primary as $post)
        @php
          setup_postdata($post);
          $categories = get_the_category($post->ID);
          $cat_name = !empty($categories) ? $fds_clean($categories[0]->name) : 'Berita';
          $n_thumb_id = get_post_thumbnail_id($post->ID);
          $n_alt = $n_thumb_id ? trim($fds_clean(get_post_meta($n_thumb_id, '_wp_attachment_image_alt', true))) : '';
          if ($n_alt === '') {
              $n_alt = trim($fds_clean(get_the_title($post->ID))) . ' — Newsroom Full Drone Solutions';
          }
        @endphp
        <article class="fds-photo-card group">
          <div class="fds-photo-card-frame" style="background-color:#e5e5ea;">
            @if(has_post_thumbnail($post->ID))
              {!! get_the_post_thumbnail($post->ID, 'medium_large', [
                'class'    => 'w-full h-full object-cover',
                'sizes'    => '(max-width: 768px) 100vw, 380px',
                'alt'      => $n_alt,
                'loading'  => 'lazy',
                'decoding' => 'async',
              ]) !!}
            @else
              <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#e8fbfd] to-[#d1f7fa]">
                <svg class="w-10 h-10 text-[#0f808a]/35" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
            @endif
          </div>
          <p class="fds-caption mb-2" style="color:var(--fds-ink-muted-aa);">
            <span style="color:var(--color-teal-800);font-weight:600;">{{ $cat_name }}</span> &middot; {{ get_the_date('d M Y', $post->ID) }}
          </p>
          <h3 class="fds-h3 flex items-start justify-between gap-3" style="color:var(--fds-ink-primary);">
            <a href="{!! esc_url(get_permalink($post->ID)) !!}" class="fds-stretched-link text-[#1d1d1f] group-hover:text-[#0a555c] transition-colors" style="text-decoration:none;">
              {{ $fds_clean(get_the_title($post->ID)) }}
            </a>
            <span class="fds-icon-cue mt-1" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>
            </span>
          </h3>
        </article>
      @endforeach
    </x-reveal-group>

    @if(!empty($news_more))
    <details class="fds-quiet-details mt-10">
      <summary class="fds-quiet-summary">Lihat artikel terbaru lainnya ({{ count($news_more) }})</summary>
      <div class="fds-quiet-body fds-hairline-list" style="margin-top:16px;">
        @foreach($news_more as $post)
          @php
            setup_postdata($post);
            $categories = get_the_category($post->ID);
            $cat_name = !empty($categories) ? $fds_clean($categories[0]->name) : 'Berita';
          @endphp
          <article class="fds-hairline-row fds-hairline-row--split fds-hairline-row--interactive group">
            <div>
              <p class="fds-caption mb-1">{{ $cat_name }} &middot; {{ get_the_date('d M Y', $post->ID) }}</p>
              <h3 class="fds-h3" style="font-size:1.0625rem;">
                <a href="{!! esc_url(get_permalink($post->ID)) !!}" class="fds-stretched-link text-[#1d1d1f] group-hover:text-[#0a555c] transition-colors" style="text-decoration:none;">
                  {{ $fds_clean(get_the_title($post->ID)) }}
                </a>
              </h3>
            </div>
            <span class="fds-icon-cue justify-self-end" aria-hidden="true">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>
            </span>
          </article>
        @endforeach
      </div>
    </details>
    @endif
    @php wp_reset_postdata(); @endphp

    @else
    <div class="bg-white rounded-3xl p-12 text-center border border-[#e5e5ea]">
      <p class="fds-lead" style="margin-inline:auto;">{{ trim($fds_hp_scrub($hp['newsroom_empty_text'] ?? '')) ?: 'Belum ada artikel yang diterbitkan.' }}</p>
    </div>
    @endif
  </div>
</section>


{{-- ========================================================== --}}
{{-- 7. CONTACT — Ocean-Teal Wave Split & Elevated Inquiry Card --}}
{{-- ========================================================== --}}
@php
  $global_c  = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_phone       = $global_c['phone'] ?? ($hp['kontak_phone'] ?? '+62 8112 748 882');
  $c_email       = $global_c['email'] ?? ($hp['kontak_email'] ?? 'marketing@fulldronesolutions.com');
  $c_address     = $global_c['address'] ?? ($hp['kontak_address'] ?? 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285');
  $c_wa_link     = $global_c['wa_link'] ?? ($hp['kontak_wa_link'] ?? 'https://wa.me/628112748882');
  $c_maps        = $global_c['maps_url'] ?? ($hp['kontak_maps'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid');
  $show_map_home = isset($global_c['show_map_home']) ? (bool) $global_c['show_map_home'] : (isset($hp['show_map_home']) ? (bool) $hp['show_map_home'] : (bool) get_option('fds_show_map_home', 1));
@endphp
<section id="kontak" class="fds-sec fds-bg-teal">
  <x-fds-wave type="kontak" uid="home-kontak" />

  <div class="fds-container">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">

      {{-- Left: Clean Contact Info Directly on Ocean-Teal Wave --}}
      <div class="lg:col-span-5">
        @if(!empty($hp['kontak_badge']))
        <p class="fds-caption" style="color:var(--color-teal-200);font-weight:600;margin-bottom:10px;">
          {{ $fds_hp_scrub($hp['kontak_badge'] ?? 'Enterprise Sales') }}
        </p>
        @endif
        <h2 class="fds-h2" style="color:var(--fds-white);margin-bottom:16px;">
          {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_hp_scrub($hp['kontak_title'] ?? "Hubungi tim Enterprise FDS.")))) !!}
        </h2>
        <p class="fds-lead" style="color:rgba(255,255,255,0.9);margin-bottom:32px;">
          {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $fds_hp_scrub($hp['kontak_desc'] ?? 'Konsultasi teknis armada UAV, integrasi sensor khusus, hingga program pelatihan operasional pilot.')))) !!}
        </p>

        <div class="fds-hairline-list" style="margin-bottom:28px;">
          <div class="fds-hairline-row" style="padding-block:16px;">
            <span class="fds-caption">{{ trim($fds_hp_scrub($hp['kontak_phone_label'] ?? '')) ?: 'Telepon / WhatsApp' }}</span>
            <a href="{!! esc_url($c_wa_link) !!}" target="_blank" rel="noopener noreferrer"
               class="text-[19px] font-semibold text-white hover:text-white/95 transition-colors" style="text-decoration:none;">
              {{ $fds_clean($c_phone) }}
            </a>
          </div>
          <div class="fds-hairline-row" style="padding-block:16px;">
            <span class="fds-caption">{{ trim($fds_hp_scrub($hp['kontak_email_label'] ?? '')) ?: 'Email Bisnis' }}</span>
            <a href="mailto:{{ $fds_clean($c_email) }}"
               class="text-[16px] font-medium text-white hover:text-white/95 transition-colors break-all" style="text-decoration:none;">
              {{ $fds_clean($c_email) }}
            </a>
          </div>
          <div class="fds-hairline-row" style="padding-block:16px;">
            <span class="fds-caption">{{ trim($fds_hp_scrub($hp['kontak_address_label'] ?? '')) ?: 'Lokasi Workshop & Testing Center' }}</span>
            <p class="fds-body" style="color:rgba(255,255,255,0.92);font-size:0.9375rem;">
              {{ $fds_clean($c_address) }}
            </p>
          </div>
        </div>

        <a href="{!! esc_url($c_wa_link) !!}" target="_blank" rel="noopener noreferrer" class="fds-btn fds-btn--whatsapp">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
          <span>{{ $fds_hp_scrub($hp['kontak_wa_text'] ?? 'Chat via WhatsApp') }}</span>
        </a>
      </div>

      {{-- Right: Elevated White Form Card --}}
      <div class="lg:col-span-7 bg-white text-[#1d1d1f] rounded-[20px] p-7 sm:p-9 shadow-[0_24px_64px_-20px_rgba(3,30,32,0.35)]">
        <div class="mb-6">
          <h3 class="fds-h3 mb-1.5" style="color:var(--fds-ink-primary);">
            {{ $fds_hp_scrub($hp['kontak_form_title'] ?? 'Kirim pesan inquiry') }}
          </h3>
          <p class="fds-caption" style="margin:0;color:var(--fds-ink-muted-aa);">{{ $fds_hp_scrub($hp['kontak_form_desc'] ?? 'Silakan lengkapi formulir di bawah ini, tim representatif kami siap membantu kebutuhan Anda:') }}</p>
        </div>

        <form id="fds-inquiry-form" class="space-y-4" onsubmit="fdsSubmitInquiry(event)">
          @php
            wp_nonce_field('fds_inquiry_nonce', 'fds_inquiry_nonce_val');
            $_fds_home_track = function_exists('App\fds_get_tracking_config') ? \App\fds_get_tracking_config() : [];
            $_fds_home_ts_key = $_fds_home_track['turnstile_site_key'] ?? '';
            $_fds_home_ts_sec = $_fds_home_track['turnstile_secret_key'] ?? '';
            $_fds_home_use_ts = ($_fds_home_ts_key !== '' && $_fds_home_ts_sec !== '');
          @endphp
          <input type="hidden" name="service_slug" value="beranda-inquiry">

          {{-- Anti-Bot Honeypot Trap --}}
          <div style="display:none !important; position:absolute !important; left:-9999px !important;" aria-hidden="true">
            <label for="fds_website_url_hp">Do not fill this field</label>
            <input type="text" id="fds_website_url_hp" name="website_url_hp" value="" tabindex="-1" autocomplete="off">
          </div>

          @php
            $hp_fname_lbl   = trim($fds_hp_scrub($hp['kontak_form_fname_lbl'] ?? '')) ?: 'Nama Depan';
            $hp_fname_ph    = trim($fds_hp_scrub($hp['kontak_form_fname_ph'] ?? '')) ?: 'Nama depan';
            $hp_lname_lbl   = trim($fds_hp_scrub($hp['kontak_form_lname_lbl'] ?? '')) ?: 'Nama Belakang';
            $hp_lname_ph    = trim($fds_hp_scrub($hp['kontak_form_lname_ph'] ?? '')) ?: 'Nama belakang';
            $hp_company_lbl = trim($fds_hp_scrub($hp['kontak_form_company_lbl'] ?? '')) ?: 'Perusahaan / Instansi';
            $hp_company_ph  = trim($fds_hp_scrub($hp['kontak_form_company_ph'] ?? '')) ?: 'Nama perusahaan / instansi';
            $hp_email_lbl   = trim($fds_hp_scrub($hp['kontak_form_email_lbl'] ?? '')) ?: 'Email Bisnis';
            $hp_email_ph    = trim($fds_hp_scrub($hp['kontak_form_email_ph'] ?? '')) ?: 'nama@perusahaan.com';
            $hp_phone_lbl   = trim($fds_hp_scrub($hp['kontak_form_phone_lbl'] ?? '')) ?: 'Nomor Telepon / WhatsApp';
            $hp_phone_ph    = trim($fds_hp_scrub($hp['kontak_form_phone_ph'] ?? '')) ?: 'Nomor telepon / WhatsApp';
            $hp_msg_lbl     = trim($fds_hp_scrub($hp['kontak_form_msg_lbl'] ?? '')) ?: 'Kebutuhan Anda';
            $hp_msg_ph      = trim($fds_hp_scrub($hp['kontak_form_msg_ph'] ?? '')) ?: 'Jelaskan kebutuhan armada drone, layanan, atau pertanyaan teknis Anda...';
            $hp_map_title   = trim($fds_hp_scrub($hp['kontak_map_title'] ?? '')) ?: 'Lokasi Full Drone Solutions Sleman Yogyakarta';
            $hp_loading_txt = trim($fds_hp_scrub($hp['kontak_form_loading'] ?? '')) ?: 'Mengirim Pesan...';
          @endphp

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="home-fname" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $hp_fname_lbl }} <span class="text-red-600">*</span></label>
              <input id="home-fname" type="text" name="first_name" required maxlength="60" autocomplete="given-name" placeholder="{{ $hp_fname_ph }}"
                class="w-full min-h-[46px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] placeholder-[#73767d] outline-none">
            </div>
            <div>
              <label for="home-lname" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $hp_lname_lbl }} <span class="text-red-600">*</span></label>
              <input id="home-lname" type="text" name="last_name" required maxlength="60" autocomplete="family-name" placeholder="{{ $hp_lname_ph }}"
                class="w-full min-h-[46px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] placeholder-[#73767d] outline-none">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="home-company" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $hp_company_lbl }} <span class="text-red-600">*</span></label>
              <input id="home-company" type="text" name="company" required maxlength="100" autocomplete="organization" placeholder="{{ $hp_company_ph }}"
                class="w-full min-h-[46px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] placeholder-[#73767d] outline-none">
            </div>
            <div>
              <label for="home-email" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $hp_email_lbl }} <span class="text-red-600">*</span></label>
              <input id="home-email" type="email" name="email" required maxlength="100" autocomplete="email" placeholder="{{ $hp_email_ph }}"
                class="w-full min-h-[46px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] placeholder-[#73767d] outline-none">
            </div>
          </div>

          <div>
            <label for="home-phone" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $hp_phone_lbl }} <span class="text-red-600">*</span></label>
            <input id="home-phone" type="tel" name="phone" required maxlength="30" autocomplete="tel" placeholder="{{ $hp_phone_ph }}"
              class="w-full min-h-[46px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] placeholder-[#73767d] outline-none">
          </div>

          <div>
            <label for="home-msg" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $hp_msg_lbl }} <span class="text-red-600">*</span></label>
            <textarea id="home-msg" name="message" rows="3" required maxlength="3000" placeholder="{{ $hp_msg_ph }}"
              class="w-full bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] placeholder-[#73767d] outline-none resize-none"></textarea>
          </div>

          @if($_fds_home_use_ts)
            <div class="pt-1">
              <div class="cf-turnstile" data-sitekey="{{ $_fds_home_ts_key }}" data-theme="light"></div>
            </div>
            @once
              <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
            @endonce
          @endif

          <div class="pt-2">
            <button type="submit" id="fds-inquiry-btn" class="fds-btn fds-btn--primary group">
              <span id="fds-inquiry-btn-text">{{ $fds_hp_scrub($hp['kontak_form_btn_text'] ?? 'Kirim Permintaan Konsultasi') }}</span>
              <span id="fds-inquiry-btn-arrow" class="inline-flex items-center justify-center transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" aria-hidden="true">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M6 6h12v12"/>
                  <path d="M6 18 18 6"/>
                </svg>
              </span>
            </button>
            @php
              $home_form_note_raw = trim($fds_hp_scrub($hp['kontak_form_note'] ?? ''));
              if ($home_form_note_raw !== '' && preg_match('/1[×x]24|48\s*jam|24\s*jam/i', $home_form_note_raw)) {
                  $home_form_note_raw = 'Tim kami merespons pada hari kerja Senin–Sabtu. Data perusahaan Anda terlindungi.';
              }
            @endphp
            @if($home_form_note_raw !== '')
            <p class="fds-caption mt-3 flex items-center gap-1.5" style="color:var(--fds-ink-muted-aa);">
              <svg class="w-4 h-4 text-[#0a555c] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <span>{{ $home_form_note_raw }}</span>
            </p>
            @endif
          </div>
        </form>
      </div>

    </div>

    @if($show_map_home && !empty($c_maps))
    <div class="mt-12 rounded-[18px] overflow-hidden shadow-[0_18px_48px_-12px_rgba(3,30,32,0.35)] border border-white/20" style="height: 300px;">
      <iframe
        src="{!! esc_url($c_maps) !!}"
        width="100%"
        height="100%"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="strict-origin-when-cross-origin"
        title="{{ $hp_map_title }}">
      </iframe>
    </div>
    @endif
  </div>
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

  var tsInput = form.querySelector('[name="cf-turnstile-response"]');
  if (form.querySelector('.cf-turnstile') && (!tsInput || !tsInput.value)) {
    fdsShowSnackbar(false, 'Verifikasi Keamanan', 'Silakan selesaikan verifikasi keamanan Cloudflare Turnstile sebelum mengirim formulir.');
    return;
  }

  if (typeof window.fdsPopulateAttribution === 'function') {
    window.fdsPopulateAttribution(form);
  }

  var formData = new FormData(form);
  formData.append('action', 'fds_submit_inquiry');
  formData.append('nonce', document.getElementById('fds_inquiry_nonce_val').value);

  btn.disabled = true;
  btn.classList.add('opacity-70', 'cursor-not-allowed');
  btnText.textContent = {!! wp_json_encode($hp_loading_txt) !!};

  fetch({!! wp_json_encode(admin_url("admin-ajax.php")) !!}, {
    method: 'POST',
    body: formData
  })
  .then(function(res) { return res.json(); })
  .then(function(data) {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = {!! wp_json_encode($fds_hp_scrub($hp["kontak_form_btn_text"] ?? "Kirim Permintaan Konsultasi")) !!};

    if (data.success) {
      if (typeof window.fdsTrackEvent === 'function') {
        window.fdsTrackEvent('generate_lead', {
          service_slug: 'beranda-inquiry',
          service_label: 'Inquiry Beranda',
          lead_ref: (data.data && data.data.lead_ref) ? data.data.lead_ref : ''
        });
      }
      try {
        sessionStorage.setItem('fds_last_lead_summary', JSON.stringify({
          lead_ref: (data.data && data.data.lead_ref) ? data.data.lead_ref : '',
          service_label: (data.data && data.data.service_label) ? data.data.service_label : 'Inquiry Beranda',
          company: company.trim()
        }));
      } catch (storageErr) {}
      fdsShowSnackbar(true, 'Pesan Berhasil Terkirim', data.data.message || 'Pesan Anda telah berhasil disimpan di sistem kami.');
      form.reset();
      if (window.turnstile && typeof window.turnstile.reset === 'function') {
        try { window.turnstile.reset(); } catch (err) {}
      }
    } else {
      fdsShowSnackbar(false, 'Gagal Mengirim Pesan', data.data.message || 'Terjadi kesalahan. Silakan periksa kembali formulir Anda.');
    }
  })
  .catch(function(err) {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = {!! wp_json_encode($fds_hp_scrub($hp["kontak_form_btn_text"] ?? "Kirim Permintaan Konsultasi")) !!};
    fdsShowSnackbar(false, 'Gangguan Jaringan', 'Gagal terhubung ke server. Silakan coba lagi atau hubungi kami via WhatsApp.');
  });
}
</script>


<x-snackbar />

@endsection
