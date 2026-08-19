@extends('layouts.app')

@section('content')
<div class="bg-white pt-[52px] pb-24 min-h-screen">
  @while(have_posts()) @php(the_post())
    <article>

      {{-- Header --}}
      <header class="max-w-[1100px] mx-auto px-6 lg:px-12 pt-14 pb-10 text-center">
        <p class="text-[13px] font-medium text-[#86868b] mb-4">{{ get_the_date('j F Y') }}</p>
        <h1 class="text-[38px] sm:text-[54px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">{!! get_the_title() !!}</h1>
      </header>

      {{-- Featured image --}}
      @if(has_post_thumbnail())
        <div class="max-w-[1100px] mx-auto px-6 lg:px-12 mb-14">
          <div class="rounded-[1.5rem] overflow-hidden aspect-video bg-[#f5f5f7]">
            {!! get_the_post_thumbnail(null, 'full', ['class' => 'w-full h-full object-cover']) !!}
          </div>
        </div>
      @endif

      {{-- Article body --}}
      <div class="max-w-[1100px] mx-auto px-6 lg:px-12">
        <div class="prose max-w-none"
             style="font-family: inherit; font-size: 18px; line-height: 1.8; color: #1d1d1f;">
          @php(the_content())
        </div>

        {{-- Share --}}
        <div class="mt-16 pt-8 border-t border-black/[0.06] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
          <a href="{{ home_url('/blog') }}" class="inline-flex items-center gap-1.5 text-[14px] font-medium text-[#0066cc] hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Newsroom
          </a>
          <div class="flex items-center gap-3">
            <a href="https://api.whatsapp.com/send?text={{ urlencode(get_the_title() . ' ' . get_permalink()) }}" target="_blank" rel="noopener"
               class="px-4 py-1.5 rounded-full bg-[#f5f5f7] text-[13px] font-semibold text-[#1d1d1f] hover:bg-[#e8e8ed] transition-colors">
              WhatsApp
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(get_permalink()) }}" target="_blank" rel="noopener"
               class="px-4 py-1.5 rounded-full bg-[#f5f5f7] text-[13px] font-semibold text-[#1d1d1f] hover:bg-[#e8e8ed] transition-colors">
              LinkedIn
            </a>
          </div>
        </div>
      </div>

    </article>
  @endwhile
</div>

<style>
  .prose p { margin-bottom: 1.5em; }
  .prose h2 { font-size: 1.5rem; font-weight: 600; letter-spacing: -0.02em; margin: 2em 0 0.75em; color: #1d1d1f; }
  .prose h3 { font-size: 1.25rem; font-weight: 600; margin: 1.5em 0 0.75em; color: #1d1d1f; }
  .prose a { color: #0066cc; text-decoration: none; }
  .prose a:hover { text-decoration: underline; }
  .prose blockquote { border-left: 3px solid #d2d2d7; padding-left: 1.25rem; color: #515154; font-style: normal; margin: 2em 0; font-size: 1.1em; line-height: 1.6; }
  .prose ul { list-style: disc; padding-left: 1.5em; margin-bottom: 1.5em; }
  .prose ol { list-style: decimal; padding-left: 1.5em; margin-bottom: 1.5em; }
  .prose li { margin-bottom: 0.5em; }
  .prose strong { font-weight: 600; color: #1d1d1f; }
  .prose img { border-radius: 1rem; margin: 2em 0; }
</style>
@endsection
