@props([
  'label'       => '',
  'title'       => '',
  'description' => '',
  'align'       => 'center',
  'bg'          => 'gray',
  'aos'         => true,
])

@php
  $bgClass = $bg === 'white' ? 'bg-white' : 'bg-[#f5f5f7]';
  $alignClass = $align === 'left' ? 'text-left' : 'text-center';
  $mxClass = $align === 'left' ? '' : 'mx-auto';
@endphp

<section class="pt-[52px] {{ $bgClass }} overflow-hidden">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-16 sm:pt-20 pb-12 sm:pb-16 {{ $alignClass }}">
    
    @if(!empty($label))
      <p class="inline-block text-[13px] font-semibold text-[#0f808a] mb-4 tracking-wide fds-hero-entrance fds-hero-delay-1">
        {!! esc_html($label) !!}
      </p>
    @endif

    <h1 class="text-[44px] sm:text-[58px] lg:text-[72px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.05] max-w-[820px] {{ $mxClass }} fds-hero-entrance fds-hero-delay-2">
      {!! esc_html($title) !!}
    </h1>

    @if(!empty($description))
      <p class="mt-5 text-[18px] sm:text-[20px] text-[#515154] font-normal max-w-[640px] {{ $mxClass }} leading-[1.55] fds-hero-entrance fds-hero-delay-3">
        {!! nl2br(esc_html($description)) !!}
      </p>
    @endif

  </div>
</section>
