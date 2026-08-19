@extends('layouts.app')

@section('content')
<div class="pt-[52px] bg-[#f5f5f7] min-h-[70vh]">
  @while(have_posts()) @php(the_post())
    <article>
      {{-- Page Hero --}}
      <header class="bg-white border-b border-black/[0.06]">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-16 sm:py-20">
          <h1 class="text-[40px] sm:text-[56px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
            {!! get_the_title() !!}
          </h1>
        </div>
      </header>

      {{-- Page Content --}}
      <div class="max-w-[1100px] mx-auto px-6 lg:px-12 py-16 sm:py-24">
        <div class="max-w-[1100px]">
          <div class="prose text-[18px] text-[#515154] leading-[1.7]" style="max-width:none;">
            @php(the_content())
          </div>
        </div>
      </div>
    </article>
  @endwhile
</div>

<style>
  .prose p { margin-bottom: 1.5em; color: #515154; }
  .prose h2 { font-size: 1.75rem; font-weight: 600; letter-spacing: -0.02em; margin: 2.5em 0 0.75em; color: #1d1d1f; }
  .prose h3 { font-size: 1.375rem; font-weight: 600; margin: 2em 0 0.75em; color: #1d1d1f; }
  .prose a { color: #0066cc; text-decoration: none; }
  .prose a:hover { text-decoration: underline; }
  .prose ul { list-style: disc; padding-left: 1.5em; margin-bottom: 1.5em; }
  .prose ol { list-style: decimal; padding-left: 1.5em; margin-bottom: 1.5em; }
  .prose li { margin-bottom: 0.5em; }
  .prose strong { font-weight: 600; color: #1d1d1f; }
  .prose img { border-radius: 1rem; margin: 2em 0; max-width: 100%; }
</style>
@endsection
