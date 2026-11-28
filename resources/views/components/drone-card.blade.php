@props([
  'drone' => null,
])

@php
  if (!$drone) {
      return;
  }

  $droneId = is_object($drone) ? (int) $drone->ID : (int) $drone;
  if ($droneId <= 0) {
      return;
  }

  $detail = function_exists('\App\fds_get_drone_detail_data')
      ? \App\fds_get_drone_detail_data($droneId)
      : [];

  if (empty($detail)) {
      return;
  }

  $decode = static function ($val): string {
      if (function_exists('\App\fds_decode_text')) {
          return \App\fds_decode_text((string) $val);
      }
      $s = trim(wp_strip_all_tags((string) $val));
      return function_exists('fds_clean_string') ? fds_clean_string($s) : $s;
  };

  $title          = $decode($detail['name'] ?? get_the_title($droneId));
  $slug           = (string) ($detail['slug'] ?? strtolower((string) get_post_field('post_name', $droneId)));
  $permalink      = (string) ($detail['permalink'] ?? get_permalink($droneId));
  $kategori       = $decode($detail['kategori'] ?? 'UAV Industri');
  $tagline        = $decode($detail['card_tagline'] ?? ($detail['tagline'] ?? ''));
  $isPayload      = !empty($detail['is_payload']);
  $hideBandingkan = !empty($detail['hide_bandingkan']);
  $payloadLabel   = $decode($detail['card_payload_label'] ?? ($isPayload ? '' : 'Kapasitas / Muatan'));
  $kapasitas      = $decode($detail['card_payload'] ?? ($isPayload ? '' : 'Modular'));
  $metricLabel    = $decode($detail['card_metric_label'] ?? ($isPayload ? '' : 'Durasi Terbang'));
  $metricValue    = $decode($detail['card_metric_value'] ?? ($isPayload ? '' : 'Otonom GCS'));
  $isFerto        = !empty($detail['is_ferto']);
  $hasSni         = !empty($detail['has_sni']);

  // Single Canonical Source: 1:1 Hero Image parity with single-drone.blade.php
  $heroMedia   = is_array($detail['hero_media'] ?? null) ? $detail['hero_media'] : [];
  $featImg     = (string) ($heroMedia['url'] ?? ($detail['hero_url'] ?? ''));
  $featSrcset  = (string) ($heroMedia['srcset'] ?? ($detail['hero_srcset'] ?? ''));
@endphp

<article class="fds-service-card fds-service-card--light fds-service-card--interactive bg-white border border-black/[0.08] rounded-3xl overflow-hidden flex flex-col justify-between group h-full shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
  <div>
    <div class="aspect-[16/10] bg-[#f5f5f7] overflow-hidden relative">
      <img src="{!! esc_url($featImg) !!}"
           @if($featSrcset !== '') srcset="{{ $featSrcset }}" sizes="(max-width: 767px) 100vw, (max-width: 1023px) 50vw, 33vw" @endif
           alt="{{ $title }}"
           width="720"
           height="450"
           loading="lazy"
           decoding="async"
           class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-[1.03]">
    </div>

    <div class="p-6 sm:p-7 pb-4">
      <div class="flex flex-wrap items-center gap-x-1.5 gap-y-0.5 text-[12.5px] font-semibold leading-snug min-h-[22px] mb-2">
        <span class="text-[#0f808a]">{{ $kategori }}</span>
        @if($isFerto)
          <span class="text-[#d2d2d7]" aria-hidden="true">/</span>
          <span class="text-[#0a555c]">TKDN 60,74%</span>
        @endif
        @if($hasSni)
          <span class="text-[#d2d2d7]" aria-hidden="true">/</span>
          <span class="text-[#0a555c]">SNI 9199:2023</span>
        @endif
      </div>

      <h3 class="text-[19px] sm:text-[20px] font-semibold tracking-[-0.02em] text-[#1d1d1f] group-hover:text-[#0f808a] transition-colors leading-snug line-clamp-2 min-h-[54px]">
        <a href="{!! esc_url($permalink) !!}" class="fds-stretched-link">
          {{ $title }}
        </a>
      </h3>

      <p class="mt-2 text-[14.5px] text-[#424245] leading-[1.6] line-clamp-2 min-h-[48px]">
        {{ $tagline !== '' ? $tagline : ($isPayload ? 'Perangkat sensor & kamera pabrikan mitra yang terintegrasi untuk ekosistem UAV FDS.' : 'Platform UAV manufaktur PT Karya Solusi Angkasa (FDS) Sleman, Yogyakarta.') }}
      </p>
    </div>
  </div>

  <div class="px-6 sm:px-7 pb-6 pt-3 mt-auto">
    @if(($payloadLabel !== '' && $kapasitas !== '') || ($metricLabel !== '' && $metricValue !== ''))
    <dl class="pt-3.5 mb-4 border-t border-black/[0.07] grid grid-cols-2 gap-3 text-[13px]">
      @if($payloadLabel !== '' && $kapasitas !== '')
      <div>
        <dt class="text-[#5c5e63] font-medium truncate" title="{{ $payloadLabel }}">{{ $payloadLabel }}</dt>
        <dd class="text-[#1d1d1f] font-semibold mt-0.5 truncate" title="{{ $kapasitas }}">{{ $kapasitas }}</dd>
      </div>
      @endif
      @if($metricLabel !== '' && $metricValue !== '')
      <div>
        <dt class="text-[#5c5e63] font-medium truncate" title="{{ $metricLabel }}">{{ $metricLabel }}</dt>
        <dd class="text-[#1d1d1f] font-semibold mt-0.5 truncate" title="{{ $metricValue }}">{{ $metricValue }}</dd>
      </div>
      @endif
    </dl>
    @endif

    <div class="flex items-center justify-between gap-3 pt-0.5">
      <span class="inline-flex items-center gap-1.5 text-[14px] font-semibold text-[#0f808a] group-hover:text-[#0a555c] transition-colors">
        <span>Lihat Spesifikasi</span>
        <span class="fds-card-cta-cue inline-flex items-center" aria-hidden="true">
          <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </span>
      </span>
      @if(!$hideBandingkan)
      <a href="{!! esc_url(home_url('/bandingkan/?d1=' . $slug)) !!}"
         class="fds-card-tag-link inline-flex items-center min-h-[34px] text-[12.5px] font-semibold text-[#1d1d1f] bg-[#f5f5f7] hover:bg-[#0f808a] hover:text-white border border-black/[0.08] hover:border-[#0f808a] rounded-full px-3.5 py-1.5 transition-all duration-150 leading-none"
         aria-label="Bandingkan {{ $title }}">
        Bandingkan
      </a>
      @endif
    </div>
  </div>
</article>
