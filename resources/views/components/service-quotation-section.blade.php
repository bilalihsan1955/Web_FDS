@props([
  'serviceSlug'        => 'layanan',
  'eyebrow'            => 'Konsultasi & Request for Proposal (RFP)',
  'title'              => 'Ajukan Kebutuhan Pemetaan, Survei LiDAR, atau Solusi UAV',
  'subtitle'           => 'Lengkapi detail proyek atau lampirkan file batas area (AOI) agar tim geospasial dan engineering Full Drone Solutions dapat mengevaluasi metode serta menyusun penawaran yang tepat.',
  'kakChecklist'       => [],
  'waPrefill'          => 'Halo tim Enterprise FDS, kami ingin berkonsultasi mengenai layanan UAV dan pemetaan udara.',
  'messagePlaceholder' => 'Jelaskan lokasi proyek (kota/provinsi), perkiraan luas area atau panjang koridor, kondisi vegetasi, serta format output data yang dibutuhkan...',
  'sectionBg'          => 'gray',
])

@php
  $ui           = function_exists('App\fds_get_layanan_ui_texts') ? \App\fds_get_layanan_ui_texts() : [];
  $gc           = function_exists('App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_phone      = $gc['phone'] ?? '+62 8112 748 882';
  $c_email      = $gc['email'] ?? 'marketing@fulldronesolutions.com';
  $c_wa_link    = $gc['wa_link'] ?? 'https://wa.me/628112748882';
  $privacy_url  = $gc['privacy_url'] ?? home_url('/privacy-policy/');
  $wa_sep       = strpos($c_wa_link, '?') === false ? '?' : '&';
  $wa_full_url  = $c_wa_link . $wa_sep . 'text=' . rawurlencode((string) $waPrefill);

  $service_opts = function_exists('App\fds_get_layanan_service_options') ? \App\fds_get_layanan_service_options() : [
    'layanan' => 'Konsultasi Umum Solusi UAV & Pemetaan',
  ];
  $selected_label = $service_opts[$serviceSlug] ?? reset($service_opts) ?: 'Solusi UAV & Pemetaan FDS';

  $tracking_cfg  = function_exists('App\fds_get_tracking_config') ? \App\fds_get_tracking_config() : [];
  $ts_site_key   = $tracking_cfg['turnstile_site_key'] ?? '';
  $ts_secret     = $tracking_cfg['turnstile_secret_key'] ?? '';
  $use_turnstile = ($ts_site_key !== '' && $ts_secret !== '');

  $band_bg = ($sectionBg === 'white') ? 'bg-white' : 'bg-[#f5f5f7]';
  $uid     = function_exists('wp_unique_id') ? wp_unique_id('fds-q-') : 'fds-q-' . wp_rand(100, 999);
@endphp

<section id="form-penawaran" class="{{ $band_bg }} py-20 sm:py-24 border-t border-black/[0.06] scroll-mt-20">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="rounded-[2rem] border border-black/[0.08] shadow-[0_18px_45px_-15px_rgba(0,0,0,0.08)] overflow-hidden grid grid-cols-1 lg:grid-cols-12 items-stretch bg-white">

      {{-- PANEL KIRI: KONTEKS TEKNIS & KANAL KONTAK LANGSUNG --}}
      <div class="lg:col-span-5 bg-gradient-to-br from-[#0f808a] via-[#0a555c] to-[#052b2e] text-white p-8 sm:p-10 lg:p-12 flex flex-col justify-between relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
          <svg class="absolute inset-0 w-full h-full object-cover" viewBox="0 0 600 720" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <linearGradient id="{{ $uid }}-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#75e6f0" stop-opacity="0.14"/>
                <stop offset="100%" stop-color="#031e20" stop-opacity="0.55"/>
              </linearGradient>
            </defs>
            <path d="M0,420 C160,380 340,460 600,360 L600,720 L0,720 Z" fill="url(#{{ $uid }}-grad)"/>
          </svg>
        </div>

        <div class="relative z-10 space-y-6">
          <div>
            @if(!empty($eyebrow))
              <p class="text-[13px] font-semibold text-[#a3eef5] mb-2.5">
                {{ $eyebrow }}
              </p>
            @endif
            <h2 class="text-[28px] sm:text-[34px] font-semibold tracking-[-0.025em] text-white leading-[1.15]">
              {{ $title }}
            </h2>
            @if(!empty($subtitle))
              <p class="mt-3.5 text-[15px] text-white/85 leading-relaxed">
                {{ $subtitle }}
              </p>
            @endif
          </div>

          @if(!empty($kakChecklist))
            <div class="pt-5 border-t border-white/15">
              <p class="text-[14px] font-semibold text-white mb-3">
                {{ $ui['quote_kak_heading'] ?? 'Informasi yang Membantu Evaluasi Awal:' }}
              </p>
              <ul class="space-y-2.5 text-[14px] text-white/85 leading-relaxed">
                @foreach($kakChecklist as $item)
                  <li class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-[#75e6f0] flex-shrink-0 mt-1" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ $item }}</span>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>

        <div class="relative z-10 mt-10 pt-7 border-t border-white/15 space-y-5">
          <div class="flex flex-wrap gap-3">
            <a href="{!! esc_url($wa_full_url) !!}"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center justify-center gap-2 min-h-[44px] bg-[#25D366] hover:bg-[#1fb857] text-white text-[14px] font-semibold px-5 py-2.5 rounded-full transition-colors shadow-md">
              <svg class="w-4 h-4 fill-current flex-shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
              </svg>
              <span>{{ $ui['quote_wa_btn'] ?? 'Diskusi via WhatsApp' }}</span>
            </a>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-[13.5px]">
            <div>
              <p class="text-white/70 mb-0.5">{{ $ui['quote_phone_label'] ?? 'Telepon Langsung' }}</p>
              <a href="tel:{{ preg_replace('/[^0-9+]/', '', $c_phone) }}"
                 class="inline-flex items-center min-h-[44px] sm:min-h-0 font-semibold text-white hover:text-[#a3eef5] transition-colors">
                {{ $c_phone }}
              </a>
            </div>
            <div>
              <p class="text-white/70 mb-0.5">{{ $ui['quote_email_label'] ?? 'Email Resmi' }}</p>
              <a href="mailto:{{ esc_attr($c_email) }}"
                 class="inline-flex items-center min-h-[44px] sm:min-h-0 font-semibold text-white hover:text-[#a3eef5] transition-colors break-all">
                {{ $c_email }}
              </a>
            </div>
          </div>

          @if(!empty($ui['quote_trust_footer'] ?? 'PT Karya Solusi Angkasa (FDS) · Manufaktur UAV sejak 2012 · TKDN 60,74% · SNI 9199:2023 · ISO 9001:2015'))
          <p class="text-[12.5px] text-white/75 leading-relaxed">
            {{ $ui['quote_trust_footer'] ?? 'PT Karya Solusi Angkasa (FDS) · Manufaktur UAV sejak 2012 · TKDN 60,74% · SNI 9199:2023 · ISO 9001:2015' }}
          </p>
          @endif
        </div>
      </div>

      {{-- PANEL KANAN: FORM PENAWARAN & LAMPIRAN AOI B2B --}}
      <div class="lg:col-span-7 bg-white p-8 sm:p-10 lg:p-12">
        <div class="mb-6">
          <h3 class="text-[22px] sm:text-[26px] font-semibold tracking-[-0.02em] text-[#1d1d1f] leading-snug">
            {{ $ui['quote_form_heading'] ?? 'Formulir Permintaan Penawaran & Konsultasi Proyek' }}
          </h3>
          @php
            $q_form_sub = $ui['quote_form_subtitle'] ?? 'Lengkapi rincian berikut agar proposal teknis disusun sesuai kondisi lapangan Anda. Bidang bertanda * wajib diisi.';
          @endphp
          @if($q_form_sub !== '')
            <p class="mt-1.5 text-[14.5px] text-[#515154]">
              @if(str_contains($q_form_sub, '*'))
                {!! str_replace('*', '<span class="text-[#dc2626]">*</span>', e($q_form_sub)) !!}
              @else
                {{ $q_form_sub }}
              @endif
            </p>
          @endif
        </div>

        <form class="fds-quotation-form space-y-4" novalidate enctype="multipart/form-data">
          <input type="hidden" name="action" value="fds_submit_inquiry">
          <input type="hidden" name="nonce" value="{{ wp_create_nonce('fds_inquiry_nonce') }}">
          <input type="hidden" name="pdp_consent_required" value="1">
          <input type="hidden" name="redirect_on_success" value="1">
          <input type="hidden" name="service_label" value="{{ esc_attr($selected_label) }}">

          {{-- Honeypot anti-bot --}}
          <div style="display:none !important; position:absolute !important; left:-9999px !important;" aria-hidden="true">
            <label for="{{ $uid }}-hp">Biarkan kolom ini kosong</label>
            <input type="text" id="{{ $uid }}-hp" name="website_url_hp" value="" tabindex="-1" autocomplete="off">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="{{ $uid }}-name" class="block text-[13px] font-semibold text-[#1d1d1f] mb-1.5">
                Nama Lengkap <span class="text-[#dc2626]">*</span>
              </label>
              <input type="text"
                     id="{{ $uid }}-name"
                     name="full_name"
                     required
                     maxlength="120"
                     autocomplete="name"
                     placeholder="Nama lengkap penanggung jawab"
                     class="w-full min-h-[44px] bg-[#f5f5f7] border border-black/[0.1] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/15 rounded-xl px-4 py-2.5 text-[14.5px] text-[#1d1d1f] placeholder:text-[#86868b] outline-none transition-all">
            </div>

            <div>
              <label for="{{ $uid }}-company" class="block text-[13px] font-semibold text-[#1d1d1f] mb-1.5">
                Perusahaan / Instansi <span class="text-[#dc2626]">*</span>
              </label>
              <input type="text"
                     id="{{ $uid }}-company"
                     name="company"
                     required
                     maxlength="120"
                     autocomplete="organization"
                     placeholder="Nama PT, konsultan, atau instansi"
                     class="w-full min-h-[44px] bg-[#f5f5f7] border border-black/[0.1] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/15 rounded-xl px-4 py-2.5 text-[14.5px] text-[#1d1d1f] placeholder:text-[#86868b] outline-none transition-all">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="{{ $uid }}-email" class="block text-[13px] font-semibold text-[#1d1d1f] mb-1.5">
                Email Bisnis <span class="text-[#dc2626]">*</span>
              </label>
              <input type="email"
                     id="{{ $uid }}-email"
                     name="email"
                     required
                     maxlength="100"
                     autocomplete="email"
                     placeholder="nama@perusahaan.co.id"
                     class="w-full min-h-[44px] bg-[#f5f5f7] border border-black/[0.1] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/15 rounded-xl px-4 py-2.5 text-[14.5px] text-[#1d1d1f] placeholder:text-[#86868b] outline-none transition-all">
            </div>

            <div>
              <label for="{{ $uid }}-phone" class="block text-[13px] font-semibold text-[#1d1d1f] mb-1.5">
                Nomor WhatsApp / Telepon <span class="text-[#dc2626]">*</span>
              </label>
              <input type="tel"
                     id="{{ $uid }}-phone"
                     name="phone"
                     required
                     maxlength="30"
                     autocomplete="tel"
                     placeholder="+62 812 xxxx xxxx"
                     class="w-full min-h-[44px] bg-[#f5f5f7] border border-black/[0.1] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/15 rounded-xl px-4 py-2.5 text-[14.5px] text-[#1d1d1f] placeholder:text-[#86868b] outline-none transition-all">
            </div>
          </div>

          <div>
            <label for="{{ $uid }}-service" class="block text-[13px] font-semibold text-[#1d1d1f] mb-1.5">
              Fokus Layanan / Sektor Proyek <span class="text-[#dc2626]">*</span>
            </label>
            <select id="{{ $uid }}-service"
                    name="service_slug"
                    required
                    class="w-full min-h-[44px] bg-[#f5f5f7] border border-black/[0.1] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/15 rounded-xl px-4 py-2.5 text-[14.5px] text-[#1d1d1f] outline-none transition-all">
              @foreach($service_opts as $optSlug => $optLabel)
                <option value="{{ esc_attr($optSlug) }}" @selected($optSlug === $serviceSlug)>
                  {{ $optLabel }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label for="{{ $uid }}-location" class="block text-[13px] font-semibold text-[#1d1d1f] mb-1.5">
                Lokasi Proyek (Kota/Provinsi)
              </label>
              <input type="text"
                     id="{{ $uid }}-location"
                     name="project_location"
                     maxlength="140"
                     placeholder="Mis. Kutai Timur, Kaltim"
                     class="w-full min-h-[44px] bg-[#f5f5f7] border border-black/[0.1] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/15 rounded-xl px-4 py-2.5 text-[14.5px] text-[#1d1d1f] placeholder:text-[#86868b] outline-none transition-all">
            </div>

            <div>
              <label for="{{ $uid }}-area" class="block text-[13px] font-semibold text-[#1d1d1f] mb-1.5">
                Perkiraan Luas / Panjang
              </label>
              <input type="text"
                     id="{{ $uid }}-area"
                     name="project_area"
                     maxlength="100"
                     placeholder="Mis. 500 Ha atau 25 Km"
                     class="w-full min-h-[44px] bg-[#f5f5f7] border border-black/[0.1] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/15 rounded-xl px-4 py-2.5 text-[14.5px] text-[#1d1d1f] placeholder:text-[#86868b] outline-none transition-all">
            </div>

            <div>
              <label for="{{ $uid }}-timeline" class="block text-[13px] font-semibold text-[#1d1d1f] mb-1.5">
                Target Jadwal Kerja
              </label>
              <select id="{{ $uid }}-timeline"
                      name="project_timeline"
                      class="w-full min-h-[44px] bg-[#f5f5f7] border border-black/[0.1] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/15 rounded-xl px-4 py-2.5 text-[14.5px] text-[#1d1d1f] outline-none transition-all">
                <option value="">Pilih perkiraan jadwal</option>
                <option value="Segera / Tanggap Darurat">Segera / Tanggap Darurat</option>
                <option value="Dalam 1 Bulan ke Depan">Dalam 1 Bulan ke Depan</option>
                <option value="1–3 Bulan / Kuartal Berjalan">1–3 Bulan / Kuartal Berjalan</option>
                <option value="Perencanaan / Penyusunan RAB">Perencanaan / Penyusunan RAB</option>
              </select>
            </div>
          </div>

          <div>
            <label for="{{ $uid }}-message" class="block text-[13px] font-semibold text-[#1d1d1f] mb-1.5">
              Rincian Kebutuhan, Tutupan Lahan &amp; Output Data <span class="text-[#dc2626]">*</span>
            </label>
            <textarea id="{{ $uid }}-message"
                      name="message"
                      rows="4"
                      required
                      maxlength="3000"
                      placeholder="{{ esc_attr($messagePlaceholder) }}"
                      class="w-full bg-[#f5f5f7] border border-black/[0.1] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/15 rounded-xl px-4 py-3 text-[14.5px] text-[#1d1d1f] placeholder:text-[#86868b] outline-none transition-all resize-y"></textarea>
          </div>

          <div>
            <label for="{{ $uid }}-aoi" class="block text-[13px] font-semibold text-[#1d1d1f] mb-1.5">
              Unggah Batas Poligon AOI atau Dokumen KAK (Opsional)
            </label>
            <input type="file"
                   id="{{ $uid }}-aoi"
                   name="aoi_file"
                   accept=".kml,.kmz,.geojson,.pdf"
                   class="block w-full text-[13.5px] text-[#424245] file:mr-4 file:min-h-[44px] file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-[13px] file:font-semibold file:bg-[#0f808a]/10 file:text-[#0a555c] hover:file:bg-[#0f808a]/20 bg-[#f5f5f7] border border-dashed border-black/[0.18] rounded-xl p-2 cursor-pointer">
            <p class="mt-1.5 text-[12.5px] text-[#6e6e73]">
              Format yang didukung: <code class="text-[#0a555c]">.kml</code>, <code class="text-[#0a555c]">.kmz</code>, <code class="text-[#0a555c]">.geojson</code>, atau <code class="text-[#0a555c]">.pdf</code> (maksimal 5 MB). Disimpan terproteksi khusus untuk evaluasi teknis internal.
            </p>
          </div>

          @if($use_turnstile)
            <div class="pt-1">
              <div class="cf-turnstile" data-sitekey="{{ esc_attr($ts_site_key) }}" data-theme="light"></div>
            </div>
            @once
              <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
            @endonce
          @endif

          <div class="pt-1">
            <label for="{{ $uid }}-pdp" class="flex items-start gap-3 min-h-[44px] py-1.5 cursor-pointer select-none">
              <input type="checkbox"
                     id="{{ $uid }}-pdp"
                     name="pdp_consent"
                     value="1"
                     required
                     class="mt-1 w-4 h-4 rounded border-black/25 text-[#0f808a] focus:ring-[#0f808a] flex-shrink-0 cursor-pointer">
              <span class="text-[13px] text-[#424245] leading-relaxed">
                Saya menyetujui <strong class="text-[#1d1d1f]">PT Karya Solusi Angkasa (Full Drone Solutions)</strong> memproses data proyek, lampiran poligon AOI, dan kontak bisnis di atas guna keperluan evaluasi teknis dan penyusunan penawaran sesuai
                <a href="{{ esc_url($privacy_url) }}" target="_blank" rel="noopener" class="text-[#0a555c] font-semibold underline underline-offset-4 hover:text-[#0f808a]">Kebijakan Privasi</a>
                dan prinsip pelindungan data pribadi. <span class="text-[#dc2626]">*</span>
              </span>
            </label>
          </div>

          {{-- Inline Alert Status Box --}}
          <div class="fds-quote-alert hidden rounded-xl px-4 py-3 text-[13.5px] font-medium" role="alert" aria-live="polite"></div>

          <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <button type="submit"
                    class="fds-quote-submit inline-flex items-center justify-center gap-2.5 min-h-[48px] bg-[#0f808a] hover:bg-[#0a555c] active:scale-[0.98] text-white text-[15px] font-semibold px-7 py-3 rounded-full transition-all shadow-md shadow-black/10 cursor-pointer">
              <span class="fds-quote-submit-label">{{ $ui['quote_form_submit_text'] ?? 'Kirim Permintaan Penawaran' }}</span>
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7 7 7-7 7"/>
              </svg>
            </button>

            @if(!empty($ui['quote_form_privacy_note'] ?? 'Seluruh dokumen proyek dijaga kerahasiaannya.'))
            <p class="text-[12.5px] text-[#6e6e73]">
              {{ $ui['quote_form_privacy_note'] ?? 'Seluruh dokumen proyek dijaga kerahasiaannya.' }}
            </p>
            @endif
          </div>
        </form>
      </div>

    </div>
  </div>
</section>

@once
<script>
document.addEventListener('DOMContentLoaded', function() {
  var forms = document.querySelectorAll('.fds-quotation-form');
  if (!forms.length) return;

  var ajaxUrl = '{{ esc_js(admin_url("admin-ajax.php")) }}';
  var fallbackTyUrl = '{{ esc_js(home_url("/terima-kasih/")) }}';
  var allowedExts = ['kml', 'kmz', 'geojson', 'pdf'];
  var maxFileBytes = 5 * 1024 * 1024;

  forms.forEach(function(form) {
    var selectEl = form.querySelector('select[name="service_slug"]');
    var labelHidden = form.querySelector('input[name="service_label"]');
    var fileInput = form.querySelector('input[name="aoi_file"]');
    var alertBox = form.querySelector('.fds-quote-alert');
    var submitBtn = form.querySelector('.fds-quote-submit');
    var submitLabel = form.querySelector('.fds-quote-submit-label');

    function syncServiceLabel() {
      if (selectEl && labelHidden && selectEl.selectedIndex >= 0) {
        labelHidden.value = (selectEl.options[selectEl.selectedIndex].text || '').trim();
      }
    }

    function showAlert(isError, msg) {
      if (!alertBox) return;
      alertBox.textContent = msg;
      alertBox.classList.remove('hidden', 'bg-rose-50', 'text-rose-700', 'border', 'border-rose-200', 'bg-teal-50', 'text-[#0a555c]', 'border-teal-200');
      if (isError) {
        alertBox.classList.add('bg-rose-50', 'text-rose-700', 'border', 'border-rose-200');
      } else {
        alertBox.classList.add('bg-teal-50', 'text-[#0a555c]', 'border', 'border-teal-200');
      }
      alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideAlert() {
      if (alertBox) alertBox.classList.add('hidden');
    }

    if (selectEl) {
      selectEl.addEventListener('change', syncServiceLabel);
      syncServiceLabel();
    }

    if (fileInput) {
      fileInput.addEventListener('change', function() {
        hideAlert();
        var file = fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
        if (!file) return;
        var ext = (file.name.split('.').pop() || '').toLowerCase();
        if (allowedExts.indexOf(ext) === -1) {
          showAlert(true, 'Format file AOI harus berupa .kml, .kmz, .geojson, atau .pdf.');
          fileInput.value = '';
          return;
        }
        if (file.size > maxFileBytes) {
          showAlert(true, 'Ukuran file AOI maksimal 5 MB. Kompres poligon ke format KMZ atau kirim berkas ukuran lebih kecil via WhatsApp.');
          fileInput.value = '';
        }
      });
    }

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      hideAlert();
      syncServiceLabel();

      var fullName = ((form.querySelector('[name="full_name"]') || {}).value || '').trim();
      var company  = ((form.querySelector('[name="company"]') || {}).value || '').trim();
      var email    = ((form.querySelector('[name="email"]') || {}).value || '').trim();
      var phone    = ((form.querySelector('[name="phone"]') || {}).value || '').trim();
      var message  = ((form.querySelector('[name="message"]') || {}).value || '').trim();
      var pdpCheck = form.querySelector('[name="pdp_consent"]');

      if (!fullName || !company || !email || !phone || !message) {
        showAlert(true, 'Silakan lengkapi seluruh bidang bertanda wajib (*) sebelum mengirim permintaan penawaran.');
        return;
      }

      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        showAlert(true, 'Format alamat email bisnis tidak valid.');
        return;
      }

      if (phone.replace(/\D/g, '').length < 6) {
        showAlert(true, 'Silakan masukkan nomor WhatsApp atau telepon yang valid minimal 6 digit angka.');
        return;
      }

      if (pdpCheck && !pdpCheck.checked) {
        showAlert(true, 'Silakan centang persetujuan pemrosesan data sesuai Kebijakan Privasi sebelum melanjutkan.');
        return;
      }

      var file = fileInput && fileInput.files && fileInput.files[0] ? fileInput.files[0] : null;
      if (file) {
        var ext = (file.name.split('.').pop() || '').toLowerCase();
        if (allowedExts.indexOf(ext) === -1 || file.size > maxFileBytes) {
          showAlert(true, 'Berkas AOI harus berformat .kml, .kmz, .geojson, atau .pdf dengan ukuran maksimal 5 MB.');
          return;
        }
      }

      var tsInput = form.querySelector('[name="cf-turnstile-response"]');
      if (form.querySelector('.cf-turnstile') && (!tsInput || !tsInput.value)) {
        showAlert(true, 'Silakan selesaikan verifikasi keamanan Cloudflare Turnstile sebelum mengirim formulir.');
        return;
      }

      if (typeof window.fdsPopulateAttribution === 'function') {
        window.fdsPopulateAttribution(form);
      }

      var formData = new FormData(form);
      var origLabel = submitLabel ? submitLabel.textContent : '{{ esc_js($ui['quote_form_submit_text'] ?? 'Kirim Permintaan Penawaran') }}';

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
      }
      if (submitLabel) submitLabel.textContent = 'Mengirim Permintaan...';

      fetch(ajaxUrl, {
        method: 'POST',
        body: formData
      })
      .then(function(res) { return res.json(); })
      .then(function(data) {
        if (data && data.success) {
          var leadRef = (data.data && data.data.lead_ref) ? data.data.lead_ref : '';
          var srvLabel = (data.data && data.data.service_label) ? data.data.service_label : (labelHidden ? labelHidden.value : '');
          var srvSlug = selectEl ? selectEl.value : '';

          try {
            sessionStorage.setItem('fds_last_lead_summary', JSON.stringify({
              lead_ref: leadRef,
              service_label: srvLabel,
              company: company,
              project_location: ((form.querySelector('[name="project_location"]') || {}).value || '').trim(),
              project_area: ((form.querySelector('[name="project_area"]') || {}).value || '').trim(),
              project_timeline: ((form.querySelector('[name="project_timeline"]') || {}).value || '').trim(),
              has_aoi: !!file
            }));
          } catch (err) {}

          if (typeof window.fdsTrackEvent === 'function') {
            window.fdsTrackEvent('generate_lead', {
              service_slug: srvSlug,
              service_label: srvLabel,
              lead_ref: leadRef
            });
          }

          showAlert(false, (data.data && data.data.message) ? data.data.message : 'Permintaan penawaran berhasil dikirim. Mengalihkan ke halaman konfirmasi...');

          var targetUrl = (data.data && data.data.redirect_url)
            ? data.data.redirect_url
            : (fallbackTyUrl + (leadRef ? '?ref=' + encodeURIComponent(leadRef) : ''));

          setTimeout(function() {
            window.location.href = targetUrl;
          }, 250);
          return;
        }

        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
        if (submitLabel) submitLabel.textContent = origLabel;
        showAlert(true, (data && data.data && data.data.message) ? data.data.message : 'Terjadi kesalahan validasi. Silakan periksa kembali isian formulir.');
      })
      .catch(function() {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
        if (submitLabel) submitLabel.textContent = origLabel;
        showAlert(true, 'Gagal terhubung ke server. Silakan coba kembali atau hubungi tim kami melalui tombol WhatsApp.');
      });
    });
  });
});
</script>
@endonce

