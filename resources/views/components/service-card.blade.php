@props([
  'variant'   => 'light',
  'num'       => '',
  'title'     => '',
  'desc'      => '',
  'url'       => '',
  'tags'      => [],
  'ctaLabel'  => 'Pelajari selengkapnya',
  'image'     => '',
  'imageAlt'  => '',
  'showImage' => true,
  'headingTag'=> 'h3',
])

@php
  $is_dark        = ($variant === 'dark');
  $has_link       = is_string($url) && $url !== '';
  $card_base      = $is_dark
    ? 'fds-service-card--dark bg-[#052b2e] hover:bg-[#0a555c] border border-white/15 shadow-xl'
    : 'fds-service-card--light bg-white border border-black/[0.08] shadow-[0_4px_20px_rgba(0,0,0,0.03)]';
  $interactive    = $has_link ? 'fds-service-card--interactive' : '';
  $num_cls        = $is_dark ? 'text-[#75e6f0]' : 'text-[#0a555c]';
  $title_cls      = $is_dark ? 'text-white group-hover:text-[#75e6f0]' : 'text-[#1d1d1f] group-hover:text-[#0a555c]';
  $desc_cls       = $is_dark ? 'text-white/85' : 'text-[#515154]';
  $divider_cls    = $is_dark ? 'border-white/[0.12]' : 'border-black/[0.08]';
  $plain_tag_cls  = $is_dark ? 'text-[#a3eef5]' : 'text-[#515154]';
  $link_tag_cls   = $is_dark
    ? 'fds-card-tag-link text-[#75e6f0] hover:text-white underline decoration-[#75e6f0]/60 underline-offset-4'
    : 'fds-card-tag-link text-[#0a555c] hover:text-[#1d1d1f] underline decoration-[#0f808a]/50 underline-offset-4';
  $cta_cls        = $is_dark ? 'text-white' : 'text-[#0a555c]';
  $sep_cls        = $is_dark ? 'text-white/35' : 'text-black/25';

  // Normalisasi tags: menerima array of ['label'=>..., 'url'=>...] atau array of string
  $norm_tags = [];
  if (is_array($tags)) {
      foreach ($tags as $t) {
          if (is_array($t) && !empty($t['label'])) {
              $norm_tags[] = [
                  'label' => (string) $t['label'],
                  'url'   => (string) ($t['url'] ?? ''),
              ];
          } elseif (is_string($t) && trim($t) !== '') {
              $norm_tags[] = [
                  'label' => trim($t),
                  'url'   => '',
              ];
          }
      }
  }
  $safe_heading = in_array($headingTag, ['h2', 'h3', 'h4'], true) ? $headingTag : 'h3';
@endphp

<article class="fds-service-card {{ $interactive }} {{ $card_base }} rounded-3xl overflow-hidden flex flex-col justify-between group h-full">
  <div>
    @if($showImage && !empty($image))
      <div class="h-[185px] sm:h-[200px] overflow-hidden relative {{ $is_dark ? 'bg-[#031e20]' : 'bg-[#f5f5f7]' }}">
        <img src="{!! esc_url($image) !!}"
             alt="{{ esc_attr($imageAlt !== '' ? $imageAlt : $title) }}"
             width="720"
             height="400"
             loading="lazy"
             decoding="async"
             class="w-full h-full object-cover transition-transform duration-500 {{ $has_link ? 'group-hover:scale-[1.03]' : '' }}">
        @if($is_dark)
          <div class="absolute inset-0 bg-gradient-to-t from-[#052b2e]/60 via-transparent to-transparent pointer-events-none"></div>
        @endif
      </div>
    @endif

    <div class="p-6 sm:p-7 pb-4">
      @if($num !== '')
        <p class="text-[13px] font-semibold {{ $num_cls }} mb-2 tracking-wide">
          {{ $num }}
        </p>
      @endif

      <{{ $safe_heading }} class="text-[20px] sm:text-[21px] font-semibold tracking-[-0.02em] leading-snug mb-2.5 transition-colors {{ $title_cls }}">
        @if($has_link)
          <a href="{!! esc_url($url) !!}" class="fds-stretched-link">
            {{ $title }}
          </a>
        @else
          <span>{{ $title }}</span>
        @endif
      </{{ $safe_heading }}>

      @if($desc !== '')
        <p class="text-[14.5px] {{ $desc_cls }} leading-relaxed">
          {{ $desc }}
        </p>
      @endif
    </div>
  </div>

  <div class="px-6 sm:px-7 pb-6 pt-2 mt-auto">
    @if(!empty($norm_tags))
      <div class="pt-3.5 mb-3.5 border-t {{ $divider_cls }} flex flex-wrap items-center gap-x-2 gap-y-1 text-[12.5px]">
        @foreach($norm_tags as $tIdx => $tagItem)
          @if($tIdx > 0)
            <span aria-hidden="true" class="{{ $sep_cls }}">&middot;</span>
          @endif
          @if($tagItem['url'] !== '')
            <a href="{!! esc_url($tagItem['url']) !!}"
               class="{{ $link_tag_cls }} font-semibold inline-flex items-center min-h-[44px] sm:min-h-[28px] px-1 -mx-1 transition-colors">
              {{ $tagItem['label'] }}
            </a>
          @else
            <span class="{{ $plain_tag_cls }} font-medium">{{ $tagItem['label'] }}</span>
          @endif
        @endforeach
      </div>
    @else
      <div class="pt-3.5 border-t {{ $divider_cls }}"></div>
    @endif

    @if($has_link)
      <div class="flex items-center justify-between">
        <span class="fds-card-cta-cue inline-flex items-center gap-1.5 text-[14px] font-semibold {{ $cta_cls }} leading-none" aria-hidden="true">
          <span>{{ $ctaLabel }}</span>
          <svg class="w-[18px] h-[18px] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </span>
      </div>
    @endif
  </div>
</article>

