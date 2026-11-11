@props([
  'label'        => 'Konsultasi Teknis B2B',
  'title'        => 'Diskusikan Kebutuhan Armada & Pemetaan Anda.',
  'description'  => 'Hubungi tim engineering PT Karya Solusi Angkasa untuk spesifikasi teknis, penjadwalan demo unit, dan penawaran resmi instansi maupun korporasi.',
  'primaryText'  => 'Minta Penawaran Resmi',
  'primaryUrl'   => '/kontak/#hubungi-form',
  'secondaryText'=> 'Hubungi via WhatsApp',
  'secondaryUrl' => '',
  'variant'      => 'dark',
])

@php
  $clean_cb = static function ($v): string {
      $str = (string) $v;
      return function_exists('fds_clean_string') ? fds_clean_string($str) : html_entity_decode(wp_specialchars_decode($str, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
  };
  $label         = $clean_cb($label);
  $title         = $clean_cb($title);
  $description   = $clean_cb($description);
  $primaryText   = $clean_cb($primaryText);
  $secondaryText = $clean_cb($secondaryText);
  $isDark        = ($variant === 'dark');
  $gc            = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $waHref        = $secondaryUrl !== '' ? $secondaryUrl : ($gc['wa_link'] ?? 'https://wa.me/628112748882');
  $priHref       = str_starts_with($primaryUrl, 'http') ? $primaryUrl : home_url($primaryUrl);
  $waveUid       = function_exists('wp_unique_id') ? wp_unique_id('cta_wave_') : 'cta_wave_' . wp_rand(100, 9999);
@endphp

<section class="relative py-16 sm:py-20 overflow-hidden {{ $isDark ? 'bg-gradient-to-br from-[#0f808a] via-[#0a555c] to-[#052b2e] text-white' : 'bg-[#f5f5f7] text-[#1d1d1f]' }}">
  @if($isDark)
    {{-- Brand Teal Fill-Only Aerodynamic Wave Background (Zero Stroke — Rule 7) --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
      <svg class="w-full h-full" viewBox="0 0 1440 420" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
        <defs>
          <linearGradient id="{{ $waveUid }}_g1" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#13abb9" stop-opacity="0.28"/>
            <stop offset="55%" stop-color="#0f808a" stop-opacity="0.14"/>
            <stop offset="100%" stop-color="#052b2e" stop-opacity="0"/>
          </linearGradient>
          <linearGradient id="{{ $waveUid }}_g2" x1="15%" y1="0%" x2="90%" y2="100%">
            <stop offset="0%" stop-color="#46deec" stop-opacity="0.24"/>
            <stop offset="55%" stop-color="#0f808a" stop-opacity="0.12"/>
            <stop offset="100%" stop-color="#052b2e" stop-opacity="0"/>
          </linearGradient>
          <linearGradient id="{{ $waveUid }}_g3" x1="0%" y1="100%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="#18d6e7" stop-opacity="0.22"/>
            <stop offset="50%" stop-color="#0f808a" stop-opacity="0.10"/>
            <stop offset="100%" stop-color="#052b2e" stop-opacity="0"/>
          </linearGradient>
        </defs>
        <path d="M-60,-20 C260,70 520,240 760,220 C1020,198 1240,40 1500,-10 L1500,460 L-60,460 Z" fill="url(#{{ $waveUid }}_g1)"/>
        <path d="M-60,110 C280,175 540,305 780,285 C1030,262 1240,120 1500,85 L1500,460 L-60,460 Z" fill="url(#{{ $waveUid }}_g2)"/>
        <path d="M-60,235 C300,275 560,365 810,348 C1050,332 1260,225 1500,205 L1500,460 L-60,460 Z" fill="url(#{{ $waveUid }}_g3)"/>
      </svg>
    </div>
  @endif

  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 relative z-10">
    <div class="max-w-[820px] mx-auto text-center">
      @if($label !== '')
        <p class="text-[13.5px] font-semibold {{ $isDark ? 'text-[#75e6f0]' : 'text-[#0f808a]' }} mb-3">
          {{ $label }}
        </p>
      @endif

      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] leading-[1.1] {{ $isDark ? 'text-white' : 'text-[#1d1d1f]' }}">
        {{ $title }}
      </h2>

      @if($description !== '')
        <p class="mt-4 text-[17px] sm:text-[18.5px] leading-[1.6] {{ $isDark ? 'text-white/85' : 'text-[#515154]' }}">
          {{ $description }}
        </p>
      @endif

      <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3.5">
        <x-button :href="$priHref" :variant="$isDark ? 'white' : 'primary'" size="lg">
          {{ $primaryText }}
        </x-button>
        @if($secondaryText !== '' && $waHref !== '')
          <x-button :href="$waHref" :variant="$isDark ? 'outline-white' : 'outline'" size="lg" target="_blank" rel="noopener noreferrer">
            {{ $secondaryText }}
          </x-button>
        @endif
      </div>
    </div>
  </div>
</section>
