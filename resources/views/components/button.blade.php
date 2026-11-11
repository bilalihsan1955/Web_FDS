@props([
  'href'    => null,
  'variant' => 'primary',
  'size'    => 'md',
  'type'    => 'button',
])

@php
  $variants = [
    'primary' => 'bg-[#0f808a] hover:bg-[#0a555c] text-white border border-transparent shadow-sm',
    'secondary' => 'bg-[#1d1d1f] hover:bg-[#052b2e] text-white border border-transparent',
    'outline' => 'bg-transparent hover:bg-[#e8fbfd] text-[#0f808a] border border-[#0f808a]/35 hover:border-[#0f808a]',
    'outline-white' => 'bg-transparent hover:bg-white/10 text-white border border-white/30 hover:border-white/60',
    'white' => 'bg-white hover:bg-[#e8fbfd] text-[#052b2e] border border-transparent shadow-sm',
    'ghost' => 'bg-transparent hover:bg-black/[0.04] text-[#0f808a]',
  ];
  $sizes = [
    'sm' => 'px-4 py-2 text-[13.5px] min-h-[38px]',
    'md' => 'px-6 py-3 text-[15px] min-h-[44px]',
    'lg' => 'px-7 py-3.5 text-[16px] min-h-[48px]',
  ];
  $vCls = $variants[$variant] ?? $variants['primary'];
  $sCls = $sizes[$size] ?? $sizes['md'];
  $base = "inline-flex items-center justify-center gap-2 rounded-full font-semibold tracking-[-0.01em] transition-colors cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#0f808a] {$vCls} {$sCls}";
@endphp

@if($href)
  <a href="{!! esc_url($href) !!}" {{ $attributes->merge(['class' => $base]) }}>
    {{ $slot }}
  </a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(['class' => $base]) }}>
    {{ $slot }}
  </button>
@endif
