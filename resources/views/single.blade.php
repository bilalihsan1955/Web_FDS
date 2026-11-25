@extends('layouts.app')

@section('content')
<div class="bg-white pt-[52px] pb-24 min-h-screen">
  @while(have_posts())
    @php
      the_post();
      $fds_post_clean = static function ($val) {
        $s = (string) $val;
        if (function_exists('fds_clean_string')) {
          return fds_clean_string($s);
        }
        $prev = '';
        while ($prev !== $s) {
          $prev = $s;
          $s = wp_specialchars_decode($s, ENT_QUOTES);
          $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $s;
      };
      $single_title_clean = $fds_post_clean(get_the_title());
      $nr = function_exists('App\fds_get_newsroom_ui_content') ? \App\fds_get_newsroom_ui_content() : [];
    @endphp
    <article>

      {{-- Header --}}
      <header class="max-w-[960px] mx-auto px-6 lg:px-12 pt-14 pb-10 text-center">
        <p class="text-[13.5px] font-medium text-[#5c5e63] mb-4 fds-hero-entrance fds-hero-delay-1">{{ get_the_date('j F Y') }}</p>
        <h1 class="text-[36px] sm:text-[50px] font-semibold tracking-[-0.025em] text-[#1d1d1f] leading-[1.12] fds-hero-entrance fds-hero-delay-2">{{ $single_title_clean }}</h1>
      </header>

      {{-- Featured image --}}
      @if(has_post_thumbnail())
        @php
          $s_thumb_id  = get_post_thumbnail_id(get_the_ID());
          $s_thumb_alt = $s_thumb_id ? trim($fds_post_clean((string) get_post_meta($s_thumb_id, '_wp_attachment_image_alt', true))) : '';
          if ($s_thumb_alt === '') {
              $s_thumb_alt = trim($single_title_clean) . ' — Full Drone Solutions';
          }
        @endphp
        <div class="max-w-[1100px] mx-auto px-6 lg:px-12 mb-14 fds-hero-entrance fds-hero-delay-3">
          <div class="rounded-[1.5rem] overflow-hidden aspect-video bg-[#f5f5f7]">
            {!! get_the_post_thumbnail(null, 'large', [
              'class'         => 'w-full h-full object-cover no-lazy',
              'alt'           => $s_thumb_alt,
              'loading'       => 'eager',
              'fetchpriority' => 'high',
              'data-no-lazy'  => '1',
              'decoding'      => 'sync',
            ]) !!}
          </div>
        </div>
      @endif

      {{-- Article body (Optimal 65ch reading measure) --}}
      <x-reveal :delay="200" class="max-w-[760px] mx-auto px-6 lg:px-8">
        <div class="prose max-w-none"
             style="font-family: inherit; font-size: 17.5px; line-height: 1.76; color: #374151;">
          @php
            $post_body_html = apply_filters('the_content', get_the_content());
            if (is_string($post_body_html) && $post_body_html !== '') {
                $post_body_html = preg_replace('/<(\/?)h1\b/i', '<$1h2', $post_body_html);
            }
          @endphp
          {!! $post_body_html !!}
        </div>

        {{-- Share --}}
        <div class="mt-16 pt-8 border-t border-black/[0.06] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
          <a href="{!! esc_url(home_url('/blog/')) !!}" class="inline-flex items-center gap-1.5 text-[14px] font-medium text-[#0f808a] hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ $nr['single_back_text'] ?? 'Kembali ke Newsroom' }}
          </a>
          <div class="flex items-center gap-3">
            <a href="{!! esc_url('https://api.whatsapp.com/send?text=' . rawurlencode($single_title_clean . ' ' . get_permalink())) !!}" target="_blank" rel="noopener"
               class="px-4 py-1.5 rounded-full bg-[#f5f5f7] text-[13px] font-semibold text-[#1d1d1f] hover:bg-[#e8e8ed] transition-colors">
              {{ $nr['single_share_wa'] ?? 'WhatsApp' }}
            </a>
            <a href="{!! esc_url('https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode(get_permalink())) !!}" target="_blank" rel="noopener"
               class="px-4 py-1.5 rounded-full bg-[#f5f5f7] text-[13px] font-semibold text-[#1d1d1f] hover:bg-[#e8e8ed] transition-colors">
              {{ $nr['single_share_li'] ?? 'LinkedIn' }}
            </a>
          </div>
        </div>

        {{-- Komentar Artikel --}}
        @include('partials.comments')
      </x-reveal>

    </article>
  @endwhile
</div>

<style>
  .prose p { margin-bottom: 1.5em; color: #374151; }
  .prose h2 { font-family: var(--fds-font-display); font-size: 1.625rem; line-height: 1.24; font-weight: 600; letter-spacing: -0.02em; margin: 2.1em 0 0.75em; color: #1d1d1f; }
  .prose h3 { font-family: var(--fds-font-display); font-size: 1.3125rem; line-height: 1.3; font-weight: 600; letter-spacing: -0.01em; margin: 1.65em 0 0.7em; color: #1d1d1f; }
  .prose a { color: #0f808a; font-weight: 500; text-decoration: none; }
  .prose a:hover { text-decoration: underline; }
  .prose blockquote { border-left: 3px solid #0f808a; padding-left: 1.25rem; color: #424245; font-style: normal; margin: 2em 0; font-size: 1.06em; line-height: 1.68; }
  .prose ul { list-style: disc; padding-left: 1.5em; margin-bottom: 1.5em; color: #374151; }
  .prose ol { list-style: decimal; padding-left: 1.5em; margin-bottom: 1.5em; color: #374151; }
  .prose li { margin-bottom: 0.55em; line-height: 1.72; }
  .prose strong { font-weight: 600; color: #1d1d1f; }
  .prose img { border-radius: 1rem; margin: 2em 0; }
</style>
@endsection
