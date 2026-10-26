@extends('layouts.app')

@section('content')

@php
  $paged = max(1, get_query_var('paged'), get_query_var('page'));
  $current_cat_id = is_category() ? get_queried_object_id() : 0;
  $blog_url = get_permalink(get_option('page_for_posts')) ?: home_url('/blog');
@endphp

{{-- ========================================================== --}}
{{-- ========================================================== --}}
{{-- BLOG HEADER: Brand Teal with Solusi Industri Wave Vector   --}}
{{-- ========================================================== --}}
<section class="relative bg-gradient-to-br from-[#13abb9] via-[#0f808a] to-[#0a555c] pt-[52px] overflow-hidden text-white">
  {{-- Vector Background: Lekukan Gelombang Organik Fluida (Sesuai Section Solusi Industri FDS Halaman Home) --}}
  <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
    <svg class="absolute inset-0 w-full h-full object-cover" viewBox="0 0 1440 700" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        {{-- Ambient Bottom-Left Glow --}}
        <radialGradient id="newsroom-ambient-bl" cx="10%" cy="90%" r="75%" fx="0%" fy="100%">
          <stop offset="0%" stop-color="#ffffff" stop-opacity="0.22" />
          <stop offset="45%" stop-color="#46deec" stop-opacity="0.12" />
          <stop offset="100%" stop-color="#0f808a" stop-opacity="0.0" />
        </radialGradient>

        {{-- Lapisan 1 (Terluar - Lekukan Halus Menukik Anggun) --}}
        <linearGradient id="newsroom-wave-fill1" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#ffffff" stop-opacity="0.14" />
          <stop offset="45%" stop-color="#75e6f0" stop-opacity="0.12" />
          <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0" />
        </linearGradient>

        {{-- Lapisan 2 (Tengah - Aksen Biru Hidup dengan Gradasi Lembut) --}}
        <linearGradient id="newsroom-wave-fill2" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#a3eef5" stop-opacity="0.18" />
          <stop offset="45%" stop-color="#46deec" stop-opacity="0.14" />
          <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0" />
        </linearGradient>

        {{-- Lapisan 3 (Inti Luminous Wave di Sudut Kiri Bawah) --}}
        <linearGradient id="newsroom-wave-fill3" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#ffffff" stop-opacity="0.20" />
          <stop offset="45%" stop-color="#46deec" stop-opacity="0.15" />
          <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0" />
        </linearGradient>

        {{-- Lapisan 4 (Aksen Halus di Ujung Gelombang) --}}
        <linearGradient id="newsroom-wave-fill4" x1="0%" y1="0%" x2="0%" y2="100%">
          <stop offset="0%" stop-color="#d1f7fa" stop-opacity="0.22" />
          <stop offset="45%" stop-color="#18d6e7" stop-opacity="0.14" />
          <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0" />
        </linearGradient>
      </defs>

      <!-- Ambient Light Kiri Bawah -->
      <rect width="1440" height="700" fill="url(#newsroom-ambient-bl)" />

      <!-- 4 Lapisan Vektor Organik: Mengalir Luwes, Elegan & Mengangkat di Sisi Kanan -->
      <path d="M0,110 C280,130 580,270 860,420 C1120,550 1300,570 1440,520 L1440,700 L0,700 Z" fill="url(#newsroom-wave-fill1)" />
      <path d="M0,250 C280,290 560,410 820,530 C1060,610 1260,620 1440,580 L1440,700 L0,700 Z" fill="url(#newsroom-wave-fill2)" />
      <path d="M0,390 C250,420 500,500 740,580 C980,640 1200,645 1440,620 L1440,700 L0,700 Z" fill="url(#newsroom-wave-fill3)" />
      <path d="M0,510 C180,520 380,580 600,630 C840,670 1100,675 1440,655 L1440,700 L0,700 Z" fill="url(#newsroom-wave-fill4)" />
    </svg>
  </div>

  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-14 sm:py-16 lg:py-20 relative z-10">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 lg:gap-12">
      <div class="max-w-[680px]">
        <p class="text-[13px] font-semibold text-white/85 tracking-wide mb-2.5 fds-hero-entrance fds-hero-delay-1">
          @if(is_category())
            Kategori Artikel
          @elseif(is_tag())
            Tag Artikel
          @else
            Newsroom
          @endif
        </p>
        <h1 class="text-[36px] sm:text-[48px] lg:text-[54px] font-semibold tracking-[-0.03em] text-white leading-[1.08] mb-3 fds-hero-entrance fds-hero-delay-2">
          @if(is_category())
            {{ single_cat_title('', false) }}
          @elseif(is_tag())
            Tag: {{ single_tag_title('', false) }}
          @else
            Berita &amp; Artikel Terkini.
          @endif
        </h1>
        <p class="text-[15px] sm:text-[17px] text-white/85 max-w-[580px] leading-relaxed fds-hero-entrance fds-hero-delay-3">
          @if(is_category() && category_description())
            {!! category_description() !!}
          @else
            Perkembangan produk drone, studi kasus operasional lapangan, dan wawasan teknologi industri dari tim FDS.
          @endif
        </p>
      </div>

      {{-- Search Form: Centered Vertically --}}
      <div class="relative w-full sm:w-[380px] lg:w-[420px] flex-shrink-0 fds-hero-entrance fds-hero-delay-4">
        <form action="{{ home_url('/') }}" method="get" class="relative w-full">
          <div class="relative flex items-center">
            <div class="absolute left-4 pointer-events-none text-[#86868b]">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </div>
            <input type="text" name="s" value="{{ get_search_query() }}" placeholder="Cari drone, artikel, topik..." required autocomplete="off"
                   class="w-full pl-11 pr-24 py-3 sm:py-3.5 bg-white border border-white/20 focus:border-white rounded-full text-[13.5px] text-[#1d1d1f] placeholder-[#86868b] outline-none transition-colors duration-150 shadow-sm">
            <button type="submit"
                    class="absolute right-1.5 bg-[#0f808a] hover:bg-[#0a555c] active:scale-95 text-white text-[12.5px] font-semibold px-4.5 py-2.5 rounded-full transition-colors duration-150 cursor-pointer">
              Cari
            </button>
          </div>
        </form>
      </div>
    </div>

    @if(is_category() || is_tag())
    <div class="pt-4">
      <a href="{{ esc_url($blog_url) }}"
         class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-white/90 hover:text-white hover:underline transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        <span>Lihat semua berita</span>
      </a>
    </div>
    @endif
  </div>
</section>


{{-- ========================================================== --}}
{{-- ARTICLES LIST & PAGINATION                                 --}}
{{-- ========================================================== --}}
<section class="bg-white pt-12 sm:pt-14 pb-16 sm:pb-20 min-h-[50vh]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    @php
      $all_posts = [];
      if (have_posts()) {
        while (have_posts()) {
          the_post();
          $cats = get_the_category(get_the_ID());
          $cat_label = !empty($cats) ? $cats[0]->name : 'Berita';
          $all_posts[] = [
            'id'        => get_the_ID(),
            'title'     => get_the_title(),
            'permalink' => get_permalink(),
            'excerpt'   => get_the_excerpt(),
            'date'      => get_the_date('d M Y'),
            'cat'       => $cat_label,
            'thumb'     => has_post_thumbnail() ? get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']) : null,
            'thumb_med' => has_post_thumbnail() ? get_the_post_thumbnail(null, 'medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']) : null,
          ];
        }
      }
    @endphp

    @if(empty($all_posts))
      {{-- Empty state (Containerless / Seamless) --}}
      <div class="py-16 sm:py-24 text-center max-w-[560px] mx-auto">
        <svg class="w-14 h-14 sm:w-16 sm:h-16 text-[#94a3b8] mx-auto mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h2 class="text-[22px] sm:text-[24px] font-semibold text-[#1d1d1f] tracking-tight mb-2">Belum ada artikel</h2>
        <p class="text-[14.5px] sm:text-[15.5px] text-[#64748b] leading-relaxed mb-6">Pantau terus untuk pembaruan dan berita terbaru dari tim FDS.</p>
        @if(is_category() || is_tag())
          <a href="{{ esc_url($blog_url) }}"
             class="inline-flex items-center gap-2 bg-[#1d1d1f] hover:bg-[#0f808a] text-white text-[13px] font-semibold px-5.5 py-2.5 rounded-full transition-colors shadow-sm leading-none group">
            <span class="leading-none">Lihat Semua Topik</span>
            <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 6h12v12"/>
              <path d="M6 18 18 6"/>
            </svg>
          </a>
        @endif
      </div>
    @else

      {{-- Page 1 Hero Feature (1st post is always a large featured card on Page 1) --}}
      @if($paged === 1 && !empty($all_posts))
        @php
          $featured = $all_posts[0];
          $grid_posts = array_slice($all_posts, 1);
        @endphp

        <x-reveal :delay="100" as="article" class="bg-[#f5f5f7] rounded-[2rem] overflow-hidden mb-8 group hover:-translate-y-0.5 transition-all duration-300 border border-black/[0.05] hover:bg-[#ededf0]">
          <div class="grid grid-cols-1 lg:grid-cols-12 items-stretch">

            <div class="lg:col-span-7 aspect-[16/10] lg:aspect-auto lg:min-h-[380px] bg-[#e8e8ed] overflow-hidden relative">
              @if($featured['thumb'])
                {!! $featured['thumb'] !!}
              @else
                <div class="w-full h-full min-h-[320px] flex items-center justify-center bg-gradient-to-br from-[#e8f0fe] to-[#dbeafe]">
                  <svg class="w-14 h-14 text-[#0f808a]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
              @endif
            </div>

            <div class="lg:col-span-5 p-8 sm:p-12 lg:p-14 flex flex-col justify-between">
              <div>
                <div class="flex items-center gap-2 text-[12px] mb-3">
                  <span class="bg-[#0f808a] text-white text-[11px] font-bold px-3 py-0.5 rounded-full tracking-wide">Terbaru</span>
                  <span class="font-semibold text-[#0f808a] ml-1">{!! esc_html($featured['cat']) !!}</span>
                  <span class="text-[#cbd5e1]">&bull;</span>
                  <span class="text-[#86868b]">{{ $featured['date'] }}</span>
                </div>
                <h2 class="text-[24px] sm:text-[30px] font-semibold text-[#1d1d1f] tracking-[-0.02em] leading-[1.25] mb-3.5 group-hover:text-[#0f808a] transition-colors">
                  <a href="{{ $featured['permalink'] }}">{!! esc_html(wp_specialchars_decode($featured['title'], ENT_QUOTES)) !!}</a>
                </h2>
                <p class="text-[14px] sm:text-[15px] text-[#515154] leading-relaxed line-clamp-3 mb-6">
                  {!! esc_html(wp_specialchars_decode($featured['excerpt'], ENT_QUOTES)) !!}
                </p>
              </div>

              <div>
                <a href="{{ $featured['permalink'] }}"
                   class="inline-flex items-center gap-2 text-[14px] font-semibold text-[#0f808a] group-hover:text-[#0a555c] hover:underline leading-none group">
                  <span class="leading-none">Baca artikel lengkap</span>
                  <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 6h12v12"/>
                    <path d="M6 18 18 6"/>
                  </svg>
                </a>
              </div>
            </div>

          </div>
        </x-reveal>
      @else
        @php $grid_posts = $all_posts; @endphp
      @endif

      {{-- 3-Column Articles Grid --}}
      @if(!empty($grid_posts))
        <x-reveal-group :step="100" :modulo="3" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($grid_posts as $post)
            <article class="bg-[#f5f5f7] rounded-[1.5rem] overflow-hidden group hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between border border-black/[0.05] hover:bg-[#ededf0]">
              <div>
                <div class="aspect-[16/9] overflow-hidden bg-[#e8e8ed] relative">
                  @if($post['thumb_med'])
                    {!! $post['thumb_med'] !!}
                  @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#e8f0fe] to-[#dbeafe]">
                      <svg class="w-10 h-10 text-[#0f808a]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                  @endif
                </div>

                <div class="p-6 sm:p-7 pb-4">
                  <div class="flex items-center gap-2 text-[12px] mb-2.5">
                    <span class="font-semibold text-[#0f808a]">{!! esc_html($post['cat']) !!}</span>
                    <span class="text-[#cbd5e1]">&bull;</span>
                    <span class="text-[#86868b]">{{ $post['date'] }}</span>
                  </div>
                  <h2 class="text-[17px] sm:text-[18.5px] font-semibold text-[#1d1d1f] leading-snug mb-2.5 group-hover:text-[#0f808a] transition-colors line-clamp-2 min-h-[46px]">
                    <a href="{{ $post['permalink'] }}">{!! esc_html(wp_specialchars_decode($post['title'], ENT_QUOTES)) !!}</a>
                  </h2>
                  <p class="text-[13px] sm:text-[13.5px] text-[#64748b] leading-relaxed line-clamp-3">
                    {!! esc_html(wp_specialchars_decode($post['excerpt'], ENT_QUOTES)) !!}
                  </p>
                </div>
              </div>

              <div class="px-6 sm:px-7 pb-6 pt-0">
                <a href="{{ $post['permalink'] }}"
                   class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-[#0f808a] group-hover:text-[#0a555c] transition-colors leading-none group">
                  <span class="leading-none">Baca selengkapnya</span>
                  <svg class="w-[18px] h-[18px] transition-transform duration-150 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 6h12v12"/>
                    <path d="M6 18 18 6"/>
                  </svg>
                </a>
              </div>
            </article>
          @endforeach
        </x-reveal-group>
      @endif

      {{-- Numbered Pagination (No divider, tight spacing) --}}
      <x-reveal :delay="120" class="mt-10 sm:mt-12 flex justify-center">
        {!! function_exists('fds_posts_pagination') ? fds_posts_pagination() : get_the_posts_pagination() !!}
      </x-reveal>

    @endif

  </div>
</section>

@endsection
