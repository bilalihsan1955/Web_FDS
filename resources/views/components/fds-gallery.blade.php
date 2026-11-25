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

    /* Bento: 1 large tile + N stacked tiles on the right + 3 tiles in the
       full bottom row. The large tile spans the right-hand stack's rows. */
    $gal_items = [];
    foreach ((array) $items as $g_item) {
        $g_url = trim((string) ($g_item['url'] ?? ($g_item['src'] ?? '')));
        if ($g_url === '') {
            continue;
        }
        $gal_items[] = [
            'url'     => $g_url,
            'caption' => $clean_gal($g_item['caption'] ?? ($g_item['alt'] ?? 'Dokumentasi Lapangan Full Drone Solutions')),
        ];
    }
    $bento_rows       = max(1, count($gal_items) - 4);
    $bento_bottom_row = $bento_rows + 1;
  @endphp

  <div class="{{ $is_three ? 'fds-gallery-grid--3' : 'fds-gallery-grid' }}"
       data-reveal-group data-reveal-step="70" data-reveal-modulo="3"
       @if(!$is_three) style="--fds-bento-rows: {{ $bento_rows }}; --fds-bento-bottom-row: {{ $bento_bottom_row }};" @endif>
    @foreach($gal_items as $idx => $item)
      @php
        $img_url = $item['url'];
        $caption = $item['caption'];
        $tile_cls = $is_three ? '' : ($idx < 2 ? 'fds-gallery-tile--wide' : 'fds-gallery-tile--std');
      @endphp
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
    @endforeach
  </div>
@endif
