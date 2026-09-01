@extends('layouts.app')

@section('content')

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
          @global $wp_query;
          @if($wp_query->found_posts > 0)
            Ditemukan <strong>{{ $wp_query->found_posts }}</strong> artikel yang relevan dengan kata kunci Anda.
          @else
            Tidak ditemukan artikel yang sesuai dengan kata kunci tersebut.
          @endif
        </p>
      </div>

      {{-- Search Bar to Refine --}}
      <form action="{{ home_url('/') }}" method="get" class="relative w-full sm:w-[380px] lg:w-[420px] flex-shrink-0">
        <div class="relative flex items-center">
          <div class="absolute left-4 pointer-events-none text-[#86868b]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          <input type="search" name="s" value="{{ get_search_query() }}" placeholder="Cari kata kunci lain..." required
                 class="w-full pl-11 pr-24 py-3 bg-[#f5f5f7] focus:bg-white border border-black/[0.08] focus:border-[#0066cc] rounded-full text-[13.5px] text-[#1d1d1f] placeholder-[#86868b] outline-none transition-all duration-200 shadow-sm focus:shadow-md">
          <button type="submit"
                  class="absolute right-1.5 bg-[#1d1d1f] hover:bg-[#0066cc] active:scale-95 text-white text-[12px] font-semibold px-4 py-2 rounded-full transition-all duration-150 cursor-pointer">
            Cari
          </button>
        </div>
      </form>
    </div>

    <div>
      <a href="{{ get_permalink(get_option('page_for_posts')) ?: home_url('/blog') }}"
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

    @if(!have_posts())
      <div class="bg-white rounded-[2rem] p-12 sm:p-20 text-center max-w-[600px] mx-auto shadow-sm">
        <div class="w-16 h-16 bg-[#f5f5f7] rounded-2xl flex items-center justify-center mx-auto mb-5">
          <svg class="w-8 h-8 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
        <h2 class="text-[22px] font-semibold text-[#1d1d1f] mb-2">Tidak ada artikel yang cocok</h2>
        <p class="text-[15px] text-[#64748b] leading-relaxed mb-8">
          Silakan coba kata kunci lain yang lebih umum atau jelajahi seluruh topik di ruang berita kami.
        </p>
        <a href="{{ get_permalink(get_option('page_for_posts')) ?: home_url('/blog') }}"
           class="inline-flex items-center gap-2 bg-[#1d1d1f] hover:bg-[#0066cc] active:scale-95 text-white text-[13.5px] font-semibold px-6 py-3 rounded-full transition-all duration-200 shadow-md">
          <span>Lihat Semua Berita</span>
          <span>&rsaquo;</span>
        </a>
      </div>
    @else
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @while(have_posts()) @php(the_post())
          @php
            $categories = get_the_category(get_the_ID());
            $cat_name = !empty($categories) ? $categories[0]->name : 'Berita';
          @endphp
          <article class="bg-white rounded-[1.5rem] overflow-hidden group hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between shadow-sm hover:shadow-md">
            <div>
              <div class="aspect-[16/9] overflow-hidden bg-[#e8e8ed] relative">
                @if(has_post_thumbnail())
                  {!! get_the_post_thumbnail(get_the_ID(), 'medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']) !!}
                @else
                  <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#e8f0fe] to-[#dbeafe]">
                    <svg class="w-10 h-10 text-[#0066cc]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  </div>
                @endif
              </div>

              <div class="p-6 sm:p-7 pb-4">
                <div class="flex items-center gap-2 text-[12px] mb-2.5">
                  <span class="font-semibold text-[#0066cc]">{!! esc_html($cat_name) !!}</span>
                  <span class="text-[#cbd5e1]">&bull;</span>
                  <span class="text-[#86868b]">{{ get_the_date('d M Y') }}</span>
                </div>
                <h2 class="text-[17px] sm:text-[18.5px] font-semibold text-[#1d1d1f] leading-snug mb-2.5 group-hover:text-[#0066cc] transition-colors line-clamp-2 min-h-[46px]">
                  <a href="{{ get_permalink() }}">{!! get_the_title() !!}</a>
                </h2>
                <p class="text-[13px] sm:text-[13.5px] text-[#64748b] leading-relaxed line-clamp-3">
                  {!! get_the_excerpt() !!}
                </p>
              </div>
            </div>

            <div class="px-6 sm:px-7 pb-6 pt-0">
              <a href="{{ get_permalink() }}"
                 class="inline-flex items-center gap-1 text-[13px] font-semibold text-[#0066cc] group-hover:text-[#0055b3] transition-colors">
                <span>Baca selengkapnya</span>
                <span class="text-[15px] group-hover:translate-x-1 transition-transform">&rsaquo;</span>
              </a>
            </div>
          </article>
        @endwhile
      </div>

      {{-- Numbered Pagination (No divider, tight spacing) --}}
      <div class="mt-10 sm:mt-12 flex justify-center">
        {!! function_exists('fds_posts_pagination') ? fds_posts_pagination() : get_the_posts_pagination() !!}
      </div>
    @endif

  </div>
</section>

@endsection
