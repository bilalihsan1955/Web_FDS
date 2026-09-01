@extends('layouts.app')

@section('content')

@php
  global $wp_query;
  $found_count = isset($wp_query->found_posts) ? (int) $wp_query->found_posts : 0;
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
        'thumb_med' => has_post_thumbnail() ? get_the_post_thumbnail(null, 'medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']) : null,
      ];
    }
  }
  $blog_url = get_permalink(get_option('page_for_posts')) ?: home_url('/blog');
@endphp

{{-- ========================================================== --}}
{{-- SEARCH RESULTS HEADER                                      --}}
{{-- ========================================================== --}}
<section class="pt-[52px] bg-white border-b border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-14 sm:pt-16 pb-10 sm:pb-12">
    <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-3">Hasil Pencarian</p>
    
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-6">
      <div>
        <h1 class="text-[32px] sm:text-[44px] lg:text-[50px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
          &ldquo;{{ get_search_query() }}&rdquo;
        </h1>
        <p class="text-[15px] sm:text-[16px] text-[#515154] leading-relaxed mt-2.5">
          @if($found_count > 0)
            Ditemukan <strong>{{ $found_count }}</strong> artikel yang relevan dengan kata kunci Anda.
          @else
            Tidak ditemukan artikel yang sesuai dengan kata kunci tersebut.
          @endif
        </p>
      </div>

      {{-- Search Bar to Refine with Instant Pretty & Secure URL --}}
      <form action="{{ home_url('/') }}" method="get" onsubmit="if(this.s.value.trim()){ window.location.href='{{ home_url('/search') }}/'+encodeURIComponent(this.s.value.trim().replace(/\//g, ''))+'/'; return false; }" class="relative w-full sm:w-[380px] lg:w-[420px] flex-shrink-0">
        <div class="relative flex items-center">
          <div class="absolute left-4 pointer-events-none text-[#86868b]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          <input type="text" name="s" value="{{ get_search_query() }}" placeholder="Cari kata kunci lain..." required autocomplete="off"
                 class="w-full pl-11 pr-20 py-2.5 sm:py-3 bg-[#f5f5f7] focus:bg-white border border-black/[0.08] focus:border-[#0066cc] rounded-full text-[13.5px] text-[#1d1d1f] placeholder-[#86868b] outline-none transition-colors duration-150">
          <button type="submit"
                  class="absolute right-1.5 bg-[#1d1d1f] hover:bg-[#0066cc] active:scale-95 text-white text-[12px] font-semibold px-4 py-2 rounded-full transition-colors duration-150 cursor-pointer">
            Cari
          </button>
        </div>
      </form>
    </div>

    <div>
      <a href="{{ esc_url($blog_url) }}"
         class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-[#0066cc] hover:underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        <span>Kembali ke semua artikel</span>
      </a>
    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- SEARCH RESULTS LIST                                       --}}
{{-- ========================================================== --}}
<section class="bg-[#f5f5f7] pt-12 sm:pt-14 pb-8 sm:pb-10 min-h-[50vh]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    @if(empty($all_posts))
      {{-- Empty state (Containerless / Seamless) --}}
      <div class="py-16 sm:py-24 text-center max-w-[560px] mx-auto">
        <svg class="w-14 h-14 sm:w-16 sm:h-16 text-[#94a3b8] mx-auto mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <h2 class="text-[22px] sm:text-[24px] font-semibold text-[#1d1d1f] tracking-tight mb-2">Tidak ada artikel yang cocok</h2>
        <p class="text-[14.5px] sm:text-[15.5px] text-[#64748b] leading-relaxed mb-8">
          Silakan coba kata kunci lain yang lebih umum atau jelajahi seluruh topik di ruang berita kami.
        </p>
        <a href="{{ esc_url($blog_url) }}"
           class="inline-flex items-center gap-2 bg-[#1d1d1f] hover:bg-[#0066cc] active:scale-95 text-white text-[13.5px] font-semibold px-6 py-3 rounded-full transition-all duration-200 shadow-sm">
          <span>Lihat Semua Berita</span>
          <span>&rsaquo;</span>
        </a>
      </div>
    @else
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($all_posts as $post)
          <article class="bg-white rounded-[1.5rem] overflow-hidden group hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shadow-sm hover:shadow-md">
            <div>
              <div class="aspect-[16/9] overflow-hidden bg-[#e8e8ed] relative">
                @if($post['thumb_med'])
                  {!! $post['thumb_med'] !!}
                @else
                  <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#e8f0fe] to-[#dbeafe]">
                    <svg class="w-10 h-10 text-[#0066cc]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  </div>
                @endif
              </div>

              <div class="p-6 sm:p-7 pb-4">
                <div class="flex items-center gap-2 text-[12px] mb-2.5">
                  <span class="font-semibold text-[#0066cc]">{!! esc_html($post['cat']) !!}</span>
                  <span class="text-[#cbd5e1]">&bull;</span>
                  <span class="text-[#86868b]">{{ $post['date'] }}</span>
                </div>
                <h2 class="text-[17px] sm:text-[18.5px] font-semibold text-[#1d1d1f] leading-snug mb-2.5 group-hover:text-[#0066cc] transition-colors line-clamp-2 min-h-[46px]">
                  <a href="{{ $post['permalink'] }}">{!! esc_html(wp_specialchars_decode($post['title'], ENT_QUOTES)) !!}</a>
                </h2>
                <p class="text-[13px] sm:text-[13.5px] text-[#64748b] leading-relaxed line-clamp-3">
                  {!! esc_html(wp_specialchars_decode($post['excerpt'], ENT_QUOTES)) !!}
                </p>
              </div>
            </div>

            <div class="px-6 sm:px-7 pb-6 pt-0">
              <a href="{{ $post['permalink'] }}"
                 class="inline-flex items-center gap-1 text-[13px] font-semibold text-[#0066cc] group-hover:text-[#0055b3] transition-colors">
                <span>Baca selengkapnya</span>
                <span class="text-[15px] group-hover:translate-x-1 transition-transform">&rsaquo;</span>
              </a>
            </div>
          </article>
        @endforeach
      </div>

      {{-- Numbered Pagination (No divider, tight spacing) --}}
      <div class="mt-10 sm:mt-12 flex justify-center">
        {!! function_exists('fds_posts_pagination') ? fds_posts_pagination() : get_the_posts_pagination() !!}
      </div>
    @endif

  </div>
</section>

@endsection
