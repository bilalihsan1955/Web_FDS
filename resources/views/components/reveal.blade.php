@props([
  'type'      => 'fade-up',
  'delay'     => 0,
  'offset'    => null,
  'duration'  => null,
  'as'        => 'div',
  'class'     => '',
])

<{{ $as }}
  {{ $attributes->merge(['class' => $class]) }}
  data-aos="{{ $type }}"
  @if($delay > 0) data-aos-delay="{{ $delay }}" @endif
  @if($offset !== null) data-aos-offset="{{ $offset }}" @endif
  @if($duration !== null) data-aos-duration="{{ $duration }}" @endif
>
  {{ $slot }}
</{{ $as }}>
