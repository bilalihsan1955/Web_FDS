@props([
  'label'       => '',
  'title'       => '',
  'description' => '',
  'align'       => 'center',
  'theme'       => 'light',
  'headingTag'  => 'h2',
  'maxWidth'    => 'max-w-[780px]',
])

@php
  $clean_hdr   = static function ($v): string {
      $str = (string) $v;
      return function_exists('fds_clean_string') ? fds_clean_string($str) : html_entity_decode(wp_specialchars_decode($str, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
  };
  $label       = $clean_hdr($label);
  $title       = $clean_hdr($title);
  $description = $clean_hdr($description);
  $isDark      = ($theme === 'dark');
  $isCenter    = ($align === 'center');
  $tag         = in_array($headingTag, ['h1', 'h2', 'h3'], true) ? $headingTag : 'h2';
  $alignCls    = $isCenter ? 'text-center mx-auto' : 'text-left';
  $labelCls    = $isDark ? 'text-[#75e6f0]' : 'text-[#0f808a]';
  $titleCls    = $isDark ? 'text-white' : 'text-[#1d1d1f]';
  $descCls     = $isDark ? 'text-white/80' : 'text-[#515154]';
@endphp

<div {{ $attributes->merge(['class' => "{$maxWidth} {$alignCls}"]) }}>
  @if($label !== '')
    <p class="text-[13.5px] font-semibold {{ $labelCls }} mb-3 tracking-[-0.01em]">
      {{ $label }}
    </p>
  @endif

  @if($title !== '')
    <{{ $tag }} class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] leading-[1.1] {{ $titleCls }}">
      {{ $title }}
    </{{ $tag }}>
  @endif

  @if($description !== '')
    <p class="mt-4 text-[17px] sm:text-[18.5px] leading-[1.6] {{ $descCls }} {{ $isCenter ? 'max-w-[660px] mx-auto' : '' }}">
      {{ $description }}
    </p>
  @endif
</div>
