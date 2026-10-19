@props([
  'title'   => 'Lokasi Workshop & Fasilitas Produksi',
  'address' => 'Jl. Magelang KM 14, Murangan VII, Triharjo, Kec. Sleman, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55514',
  'mapUrl'  => 'https://maps.google.com/maps?q=-7.697669,110.355157&hl=id&z=16&output=embed',
  'btnText' => 'Buka di Google Maps',
  'btnUrl'  => 'https://maps.google.com/?q=-7.697669,110.355157',
])

<div class="bg-white rounded-[2rem] border border-black/[0.08] shadow-lg overflow-hidden">
  {{-- Map Top Bar Header --}}
  <div class="px-6 sm:px-8 py-5 border-b border-black/[0.08] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
    <div>
      <h3 class="text-[16px] sm:text-[17px] font-semibold text-[#1d1d1f] leading-snug">
        {!! esc_html($title) !!}
      </h3>
      <p class="text-[12.5px] text-[#515154] mt-0.5">
        {!! esc_html($address) !!}
      </p>
    </div>
    <div class="flex-shrink-0">
      <a href="{{ esc_url($btnUrl) }}" target="_blank" rel="noopener noreferrer"
         class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#0f808a] hover:text-[#0a555c] bg-[#0f808a]/10 hover:bg-[#0f808a]/15 px-4 py-2 rounded-full transition-colors min-h-[38px]">
        <span>{!! esc_html($btnText) !!}</span>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
      </a>
    </div>
  </div>

  {{-- Google Maps Iframe --}}
  <div class="w-full relative overflow-hidden bg-[#e5e3df]" style="height: 380px;">
    <iframe 
      src="{{ esc_url($mapUrl) }}" 
      width="100%" 
      height="100%" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="strict-origin-when-cross-origin"
      title="{{ esc_attr($title) }}">
    </iframe>
  </div>
</div>
