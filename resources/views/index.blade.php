@extends('layouts.app')

@section('content')

{{-- ========================================================== --}}
{{-- BLOG HEADER                                               --}}
{{-- ========================================================== --}}
<section class="pt-[52px] bg-white border-b border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-16 pb-12">
    <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">Newsroom</p>
    <div class="flex flex-wrap items-end justify-between gap-6">
      <h1 class="text-[44px] sm:text-[56px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.05]">
        Berita & Artikel Terkini.
      </h1>
      <p class="text-[17px] text-[#515154] max-w-[420px] leading-relaxed">
        Perkembangan produk, studi kasus operasional, dan wawasan industri drone dari tim FDS.
      </p>
    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- POSTS                                                     --}}
{{-- ========================================================== --}}
<section class="bg-[#f5f5f7] py-16 sm:py-24 min-h-[60vh]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    @php
      // Collect all posts first so we can handle featured vs grid separately
      $all_posts = [];
      if (have_posts()) {
        while (have_posts()) {
          the_post();
          $all_posts[] = [
            'id'        => get_the_ID(),
            'title'     => get_the_title(),
            'permalink' => get_permalink(),
            'excerpt'   => get_the_excerpt(),
            'date'      => get_the_date('j F Y'),
            'date_grid' => get_the_date('j F Y'),
            'thumb'     => has_post_thumbnail() ? get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']) : null,
            'thumb_med' => has_post_thumbnail() ? get_the_post_thumbnail(null, 'medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']) : null,
          ];
        }
      }
    @endphp

    @if(empty($all_posts))

      {{-- Empty state --}}
      <div class="bg-white rounded-[2rem] p-16 sm:p-24 text-center" style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <div class="w-16 h-16 bg-[#f5f5f7] rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-[#c7c7cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h2 class="text-[24px] font-semibold text-[#1d1d1f] mb-2">Belum ada artikel</h2>
        <p class="text-[16px] text-[#515154]">Pantau terus untuk pembaruan dan berita terbaru dari tim FDS.</p>
      </div>

    @else

      {{-- Featured post (first) --}}
      @php $featured = $all_posts[0]; @endphp
      <article class="bg-white rounded-[2rem] overflow-hidden mb-6 group hover:-translate-y-0.5 transition-all duration-300"
               style="box-shadow: 0 4px 32px rgba(0,0,0,0.07);">
        <div class="grid grid-cols-1 lg:grid-cols-2">

          <div class="aspect-[16/10] lg:aspect-auto lg:min-h-[380px] bg-[#f5f5f7] overflow-hidden">
            @if($featured['thumb'])
              {!! $featured['thumb'] !!}
            @else
              <div class="w-full h-full min-h-[380px] flex items-center justify-center bg-gradient-to-br from-[#e8f0fe] to-[#dbeafe]">
                <svg class="w-14 h-14 text-[#0066cc]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
            @endif
          </div>

          <div class="p-10 sm:p-14 flex flex-col justify-center">
            <div class="flex items-center gap-3 mb-5">
              <span class="bg-[#0066cc] text-white text-[11px] font-bold px-3 py-1 rounded-full tracking-wide">Terbaru</span>
              <span class="text-[12px] font-medium text-[#86868b]">{{ $featured['date'] }}</span>
            </div>
            <h2 class="text-[26px] sm:text-[32px] font-semibold text-[#1d1d1f] tracking-[-0.02em] leading-[1.2] mb-4 group-hover:text-[#0066cc] transition-colors">
              <a href="{{ $featured['permalink'] }}">{!! $featured['title'] !!}</a>
            </h2>
            <p class="text-[16px] text-[#515154] leading-relaxed line-clamp-3 mb-8">
              {!! $featured['excerpt'] !!}
            </p>
            <a href="{{ $featured['permalink'] }}"
               class="inline-flex items-center gap-2 text-[14px] font-semibold text-[#0066cc] hover:underline">
              Baca artikel lengkap
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>

        </div>
      </article>

      {{-- Remaining posts &mdash; 3 column grid --}}
      @php $rest = array_slice($all_posts, 1); @endphp
      @if(!empty($rest))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
          @foreach($rest as $post)
            <article class="bg-white rounded-[1.5rem] overflow-hidden group hover:-translate-y-1 transition-all duration-300"
                     style="box-shadow: 0 2px 20px rgba(0,0,0,0.06);">

              <div class="aspect-[16/9] overflow-hidden bg-[#f5f5f7]">
                @if($post['thumb_med'])
                  {!! $post['thumb_med'] !!}
                @else
                  <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#f5f5f7] to-[#e8e8ed]">
                    <svg class="w-10 h-10 text-[#c7c7cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  </div>
                @endif
              </div>

              <div class="p-6 sm:p-7">
                <p class="text-[12px] font-medium text-[#86868b] mb-3">{{ $post['date_grid'] }}</p>
                <h2 class="text-[17px] font-semibold text-[#1d1d1f] leading-[1.35] mb-3 group-hover:text-[#0066cc] transition-colors line-clamp-2">
                  <a href="{{ $post['permalink'] }}">{!! $post['title'] !!}</a>
                </h2>
                <p class="text-[14px] text-[#515154] leading-relaxed line-clamp-2 mb-5">
                  {!! $post['excerpt'] !!}
                </p>
                <a href="{{ $post['permalink'] }}"
                   class="inline-flex items-center gap-1 text-[13px] font-semibold text-[#0066cc] hover:underline">
                  Baca selengkapnya <span>&rsaquo;</span>
                </a>
              </div>

            </article>
          @endforeach
        </div>
      @endif

      {{-- Pagination --}}
      <div class="mt-14 pt-8 border-t border-black/[0.06]">
        {!! get_the_posts_navigation([
          'prev_text' => '← Artikel Terbaru',
          'next_text' => 'Artikel Lama →',
          'class'     => 'text-[14px] font-semibold text-[#0066cc]',
        ]) !!}
      </div>

    @endif

  </div>
</section>

@endsection
