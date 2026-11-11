@props([
  'ctaLabel'  => 'Minta Penawaran',
  'ctaUrl'    => '',
  'waPrefill' => 'Halo tim Enterprise FDS, kami ingin berkonsultasi mengenai layanan UAV dan pemetaan udara.',
])

@php
  $clean_sb       = static function ($v): string {
      $str = (string) $v;
      return function_exists('fds_clean_string') ? fds_clean_string($str) : html_entity_decode(wp_specialchars_decode($str, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
  };
  $ctaLabel       = $clean_sb($ctaLabel);
  $waPrefill      = $clean_sb($waPrefill);
  $gc             = function_exists('App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_wa_link      = $gc['wa_link'] ?? 'https://wa.me/628112748882';
  $wa_sep         = strpos($c_wa_link, '?') === false ? '?' : '&';
  $wa_full_url    = $c_wa_link . $wa_sep . 'text=' . rawurlencode($waPrefill);
  $raw_cta_url    = trim((string) $ctaUrl);
  $target_cta_url = ($raw_cta_url !== '' && $raw_cta_url !== '#form-penawaran') ? $raw_cta_url : home_url('/kontak/#hubungi-form');
@endphp

<div id="fds-mobile-sticky-cta"
     class="lg:hidden fixed inset-x-0 bottom-0 z-[9990] bg-white/95 backdrop-blur-lg border-t border-black/[0.1] shadow-[0_-8px_24px_rgba(0,0,0,0.08)] px-4 py-3 transition-all duration-300 translate-y-0 opacity-100">
  <div class="flex items-center gap-3 max-w-[600px] mx-auto">
    <a href="{!! esc_url($target_cta_url) !!}"
       class="flex-1 inline-flex items-center justify-center min-h-[44px] bg-[#0f808a] hover:bg-[#0a555c] text-white text-[13.5px] font-semibold px-4 py-2.5 rounded-full transition-colors">
      {{ $ctaLabel }}
    </a>
    <a href="{!! esc_url($wa_full_url) !!}"
       target="_blank"
       rel="noopener noreferrer"
       class="inline-flex items-center justify-center gap-1.5 min-h-[44px] bg-[#25D366] hover:bg-[#1fb857] text-white text-[13.5px] font-semibold px-4 py-2.5 rounded-full transition-colors flex-shrink-0">
      <svg class="w-4 h-4 fill-current flex-shrink-0" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
      </svg>
      <span>WhatsApp</span>
    </a>
  </div>
</div>

@once
<script>
document.addEventListener('DOMContentLoaded', function() {
  var bar = document.getElementById('fds-mobile-sticky-cta');
  if (!bar || typeof IntersectionObserver === 'undefined') return;

  var footerTarget = document.querySelector('footer');
  if (!footerTarget) return;

  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        bar.classList.add('translate-y-full', 'opacity-0', 'pointer-events-none');
        bar.classList.remove('translate-y-0', 'opacity-100');
      } else {
        bar.classList.remove('translate-y-full', 'opacity-0', 'pointer-events-none');
        bar.classList.add('translate-y-0', 'opacity-100');
      }
    });
  }, { threshold: 0.05 });

  observer.observe(footerTarget);
});
</script>
@endonce

