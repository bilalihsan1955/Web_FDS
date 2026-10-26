@props([
  'title'   => 'Lokasi Workshop & Fasilitas Produksi',
  'address' => 'Jl. Magelang KM 14, Murangan VII, Triharjo, Kec. Sleman, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55514',
  'mapUrl'  => 'https://maps.google.com/maps?q=-7.697669,110.355157&hl=id&z=16&output=embed',
  'btnText' => 'Buka di Google Maps',
  'btnUrl'  => 'https://maps.google.com/?q=-7.697669,110.355157',
])

<div class="bg-white rounded-[2rem] border border-black/[0.08] shadow-lg overflow-hidden">
  {{-- Map Top Bar Header --}}
  <div class="px-6 sm:px-8 py-5 border-b border-black/[0.08] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#f5f5f7]">
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
         class="inline-flex items-center gap-2 text-[13px] font-semibold text-[#0f808a] hover:text-[#0a555c] bg-[#0f808a]/10 hover:bg-[#0f808a]/15 px-4.5 py-2 rounded-full transition-colors min-h-[38px] leading-none group">
        <span class="leading-none">{!! esc_html($btnText) !!}</span>
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
