@props([
  'type'      => 'fade-up',
  'step'      => 100,
  'base'      => 0,
  'modulo'    => 0,
  'selector'  => '',
  'as'        => 'div',
  'class'     => '',
])

<{{ $as }}
  {{ $attributes->merge(['class' => $class]) }}
  data-reveal-group
  data-reveal-type="{{ $type }}"
  data-reveal-step="{{ $step }}"
  @if($base > 0) data-reveal-base="{{ $base }}" @endif
  @if($modulo > 0) data-reveal-modulo="{{ $modulo }}" @endif
  @if(!empty($selector)) data-reveal-selector="{{ $selector }}" @endif
>
  {{ $slot }}
</{{ $as }}>
