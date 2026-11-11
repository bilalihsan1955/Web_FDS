@props([
  'title'     => 'Pertanyaan Umum',
  'subtitle'  => '',
  'faqs'      => [],
  'sectionBg' => 'white',
])

@if(!empty($faqs))
  @php
    $clean_sfaq = static function ($v): string {
        $str = (string) $v;
        return function_exists('fds_clean_string') ? fds_clean_string($str) : html_entity_decode(wp_specialchars_decode($str, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    };
    $title    = $clean_sfaq($title);
    $subtitle = $clean_sfaq($subtitle);
    $bg_cls   = ($sectionBg === 'gray') ? 'bg-[#f5f5f7]' : 'bg-white';
  @endphp
  <section class="{{ $bg_cls }} py-16 sm:py-24 border-t border-black/[0.06]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
      <div class="lg:col-span-4 lg:sticky lg:top-24">
        <h2 class="text-[32px] sm:text-[40px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.12]">
          {{ $title }}
        </h2>
        @if($subtitle !== '')
          <p class="mt-4 text-[15.5px] text-[#515154] leading-relaxed">
            {{ $subtitle }}
          </p>
        @endif
      </div>

      <div class="lg:col-span-8 fds-service-faq border-t border-black/[0.08]">
        @foreach($faqs as $idx => $faq)
          <details class="border-b border-black/[0.08]" @if($idx === 0) open @endif>
            <summary class="cursor-pointer py-5 min-h-[52px] flex items-center justify-between gap-5 text-left text-[16.5px] sm:text-[17.5px] font-semibold text-[#1d1d1f] hover:text-[#0f808a] transition-colors">
              <span>{{ $clean_sfaq($faq['q'] ?? '') }}</span>
              <svg class="w-5 h-5 text-[#515154] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
              </svg>
            </summary>
            <div class="pb-6 pr-6 text-[15px] sm:text-[15.5px] text-[#424245] leading-relaxed">
              <p>{{ $clean_sfaq($faq['a'] ?? '') }}</p>
            </div>
          </details>
        @endforeach
      </div>
    </div>
  </section>
@endif

