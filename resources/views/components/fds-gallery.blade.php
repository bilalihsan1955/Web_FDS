@props([
  'items'   => [],
  'variant' => 'bento',
])

@if(!empty($items) && is_array($items))
  @php
    $clean_gal = static function ($v): string {
        $s = (string) $v;
        return function_exists('fds_clean_string')
            ? fds_clean_string($s)
            : html_entity_decode(wp_specialchars_decode($s, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    };
    $is_three = ($variant === 'three');
  @endphp

  <div class="{{ $is_three ? 'fds-gallery-grid--3' : 'fds-gallery-grid' }}">
    @foreach($items as $idx => $item)
      @php
        $img_url = trim((string) ($item['url'] ?? ($item['src'] ?? '')));
        $caption = $clean_gal($item['caption'] ?? ($item['alt'] ?? 'Dokumentasi Lapangan Full Drone Solutions'));
        $tile_cls = $is_three ? '' : ($idx < 2 ? 'fds-gallery-tile--wide' : 'fds-gallery-tile--std');
      @endphp
      @if($img_url !== '')
        <button type="button"
                class="fds-gallery-tile {{ $tile_cls }}"
                data-gallery-item
                data-src="{!! esc_url($img_url) !!}"
                data-alt="{{ $caption }}"
                data-caption="{{ $caption }}"
                aria-label="Perbesar foto: {{ $caption }}">
          <img src="{!! esc_url($img_url) !!}"
               alt="{{ $caption }}"
               loading="lazy"
               decoding="async"
               width="1200"
               height="750">
          <span class="fds-gallery-cap">{{ $caption }}</span>
        </button>
      @endif
    @endforeach
  </div>
@endif
