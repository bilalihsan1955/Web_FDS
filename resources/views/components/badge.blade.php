@props([
  'variant' => 'subtle',
  'class'   => '',
])

@php
  $styles = [
    'subtle' => 'text-[#0f808a] bg-[#e8fbfd]',
    'outline' => 'text-[#0a555c] border border-[#0f808a]/25 bg-transparent',
    'solid' => 'text-white bg-[#0f808a]',
    'dark' => 'text-[#75e6f0] bg-white/10',
    'neutral' => 'text-[#515154] bg-[#f5f5f7]',
  ];
  $variantCls = $styles[$variant] ?? $styles['subtle'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-3 py-1 rounded-full text-[12.5px] font-semibold tracking-[-0.01em] {$variantCls} {$class}"]) }}>
  {{ $slot }}
</span>
