@props([
  'title'   => 'Kantor & Workshop Resmi',
  'address' => 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285',
  'mapUrl'  => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid',
  'btnText' => 'Buka di Google Maps',
  'btnUrl'  => 'https://maps.google.com/?q=Full+Drone+Solutions+Sleman+Yogyakarta',
])

@php
  $clean_map = static function ($v): string {
      $str = (string) $v;
      return function_exists('fds_clean_string') ? fds_clean_string($str) : html_entity_decode(wp_specialchars_decode($str, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
  };
  $title   = $clean_map($title);
  $address = $clean_map($address);
  $btnText = $clean_map($btnText);
@endphp

<div class="bg-white rounded-[2rem] border border-black/[0.08] shadow-lg overflow-hidden">
  {{-- Map Top Bar Header --}}
  <div class="px-6 sm:px-8 py-5 border-b border-black/[0.08] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#f5f5f7]">
    <div>
      <h3 class="text-[16px] sm:text-[17px] font-semibold text-[#1d1d1f] leading-snug">
        {{ $title }}
      </h3>
      <p class="text-[12.5px] text-[#515154] mt-0.5">
        {{ $address }}
      </p>
    </div>
    <div class="flex-shrink-0">
      <a href="{!! esc_url($btnUrl) !!}" target="_blank" rel="noopener noreferrer"
         class="inline-flex items-center gap-2 text-[13px] font-semibold text-[#0f808a] hover:text-[#0a555c] bg-[#0f808a]/10 hover:bg-[#0f808a]/15 px-4.5 py-2 rounded-full transition-colors min-h-[38px] leading-none group">
        <span class="leading-none">{{ $btnText }}</span>
        <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 6h12v12"/>
          <path d="M6 18 18 6"/>
        </svg>
      </a>
    </div>
  </div>

  {{-- Google Maps Iframe --}}
  <div class="w-full relative overflow-hidden bg-[#e5e3df]" style="height: 380px;">
    <iframe 
      src="{!! esc_url($mapUrl) !!}" 
      width="100%" 
      height="100%" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="strict-origin-when-cross-origin"
      title="{{ $title }}">
    </iframe>
  </div>
</div>
