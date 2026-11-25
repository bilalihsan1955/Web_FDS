{{--
  Template Name: Kontak Kami / Contact FDS
--}}

@extends('layouts.app')

@section('content')
@php
  $global_c  = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_phone   = $global_c['phone'] ?? '+62 8112 748 882';
  $c_email   = $global_c['email'] ?? 'marketing@fulldronesolutions.com';
  $c_address = $global_c['address'] ?? 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285';
  $c_wa_link = $global_c['wa_link'] ?? 'https://wa.me/628112748882';
  $c_maps    = $global_c['maps_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid';

  $cln = static function ($v): string {
      $s = (string) $v;
      $s = function_exists('fds_clean_string') ? fds_clean_string($s) : html_entity_decode(wp_specialchars_decode($s, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
      return function_exists('fds_scrub_legacy_text') ? fds_scrub_legacy_text($s) : $s;
  };
  $arrow_svg = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>';

  $c_phone   = $cln($c_phone);
  $c_email   = $cln($c_email);
  $c_address = $cln($c_address);

  $p_title       = $cln($global_c['page_kontak_title'] ?? 'Hubungi Kami.');
  $p_desc        = $cln($global_c['page_kontak_desc'] ?? 'Diskusikan pengadaan armada drone, jadwal demo terbang, atau kebutuhan teknis operasional Anda bersama tim Full Drone Solutions.');
  $p_lbl_phone   = $cln($global_c['page_kontak_lbl_phone'] ?? 'Telepon & WhatsApp');
  $p_lbl_email   = $cln($global_c['page_kontak_lbl_email'] ?? 'Email Resmi');
  $p_lbl_hours   = $cln($global_c['page_kontak_lbl_hours'] ?? 'Jam Operasional');
  $p_map_jump    = $cln($global_c['page_kontak_map_jump_text'] ?? 'Lihat titik lokasi & workshop resmi ↓');
  $p_hours_wd    = $cln($global_c['page_kontak_hours_wd'] ?? 'Senin – Jumat: 08:30 – 17:00 WIB');
  $p_hours_sat   = $cln($global_c['page_kontak_hours_sat'] ?? 'Sabtu: 08:30 – 13:00 WIB');
  $p_wa_btn      = $cln($global_c['page_kontak_wa_btn'] ?? 'Chat WhatsApp Kami');
  $p_form_title  = $cln($global_c['page_kontak_form_title'] ?? 'Kirim Pesan atau Permintaan Penawaran');
  $p_form_desc   = $cln($global_c['page_kontak_form_desc'] ?? 'Silakan lengkapi formulir di bawah ini, tim representatif kami siap membantu kebutuhan Anda:');
  $p_form_btn    = $cln($global_c['page_kontak_form_btn'] ?? 'Kirim Pesan');
  $p_form_loading = $cln($global_c['page_kontak_form_loading'] ?? 'Mengirim Pesan...');
  $p_form_note   = $cln($global_c['page_kontak_form_note'] ?? 'Tim kami merespons pada hari kerja Senin–Sabtu. Data perusahaan Anda terlindungi.');
  if (preg_match('/(?:1\s*[×xX]\s*24|24\s*jam|48\s*jam)/i', $p_form_note)) {
      $p_form_note = 'Tim kami merespons pada hari kerja Senin–Sabtu. Data perusahaan Anda terlindungi.';
  }
  $p_opt_default        = $cln($global_c['page_kontak_opt_default'] ?? 'Konsultasi Umum, Demo Unit & Kebutuhan Korporasi');
  $p_field_fullname_lbl = $cln($global_c['page_kontak_field_fullname_lbl'] ?? 'Nama Lengkap');
  $p_field_fullname_ph  = $cln($global_c['page_kontak_field_fullname_ph'] ?? 'Nama lengkap Anda');
  $p_field_comp_lbl     = $cln($global_c['page_kontak_field_comp_lbl'] ?? 'Perusahaan / Instansi');
  $p_field_comp_ph   = $cln($global_c['page_kontak_field_comp_ph'] ?? 'Nama perusahaan / instansi');
  $p_field_email_lbl = $cln($global_c['page_kontak_field_email_lbl'] ?? 'Email Bisnis');
  $p_field_email_ph  = $cln($global_c['page_kontak_field_email_ph'] ?? 'nama@perusahaan.com');
  $p_field_phone_lbl = $cln($global_c['page_kontak_field_phone_lbl'] ?? 'Nomor Telepon / WhatsApp');
  $p_field_phone_ph  = $cln($global_c['page_kontak_field_phone_ph'] ?? 'Nomor telepon / WhatsApp');
  $p_field_msg_lbl   = $cln($global_c['page_kontak_field_msg_lbl'] ?? 'Kebutuhan Operasional & Detail Pengadaan');
  $p_field_msg_ph    = $cln($global_c['page_kontak_field_msg_ph'] ?? 'Jelaskan kebutuhan pengadaan armada drone, jadwal demo terbang, atau spesifikasi teknis operasional Anda...');
  $p_map_title       = $cln($global_c['page_kontak_map_title'] ?? 'Lokasi Workshop Sleman');
  $p_map_btn         = $cln($global_c['page_kontak_map_btn'] ?? 'Buka di Google Maps');
  $p_map_link        = $global_c['page_kontak_map_link'] ?? 'https://maps.google.com/?q=Full+Drone+Solutions+Sleman+Yogyakarta';

  $fds_service_options = ['kontak-inquiry' => $p_opt_default];
  if (function_exists('App\fds_get_layanan_service_options')) {
      foreach (\App\fds_get_layanan_service_options() as $opt_slug => $opt_lbl) {
          $fds_service_options[(string) $opt_slug] = $cln($opt_lbl);
      }
  }
  $drone_kat_terms = get_terms(['taxonomy' => 'kategori_drone', 'hide_empty' => false, 'orderby' => 'term_id', 'order' => 'ASC']);
  if (!empty($drone_kat_terms) && !is_wp_error($drone_kat_terms)) {
      foreach ($drone_kat_terms as $dkt) {
          if (stripos((string) $dkt->slug, 'rebo') !== false) continue;
          $fds_service_options['kategori-' . $dkt->slug] = 'Lini Produk Kategori: ' . $cln($dkt->name);
      }
  }
  $kontak_drone_posts = get_posts(['post_type' => 'drone', 'posts_per_page' => -1, 'post_status' => 'publish']);
  if (!empty($kontak_drone_posts)) {
      if (function_exists('fds_sort_drone_posts')) {
          $kontak_drone_posts = fds_sort_drone_posts($kontak_drone_posts);
      }
      foreach ($kontak_drone_posts as $kd) {
          if (stripos((string) $kd->post_name, 'rebo') !== false || stripos((string) $kd->post_name, 'agrea') !== false || stripos((string) $kd->post_name, 'ferto-20') !== false) continue;
          $fds_service_options[(string) $kd->post_name] = 'Pengadaan Unit: ' . $cln($kd->post_title);
      }
  }
  $raw_prefill_param = sanitize_key(wp_unslash((string) ($_GET['layanan'] ?? $_GET['sektor'] ?? $_GET['produk'] ?? $_GET['model'] ?? '')));
  $selected_service_slug = ($raw_prefill_param !== '' && isset($fds_service_options[$raw_prefill_param])) ? $raw_prefill_param : 'kontak-inquiry';
  $selected_service_label = $cln($fds_service_options[$selected_service_slug] ?? $p_opt_default);
@endphp

<div id="contact-page" class="w-full bg-white font-sans">

  {{-- 1. EDITORIAL TEAL WAVE HEADER --}}
  <section class="fds-stage-hero fds-kontak-hero">
    <x-fds-wave type="kontak" uid="kontak-hero" />
    <div class="fds-container">
      <div class="fds-kontak-hero-inner fds-hero-entrance fds-hero-delay-1">
        <h1 class="fds-h1 fds-h1--soft fds-kontak-hero-title">{{ $p_title }}</h1>
        @if($p_desc !== '')
          <p class="fds-lead fds-kontak-hero-lead">
            {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $p_desc))) !!}
          </p>
        @endif
      </div>
    </div>
  </section>

  {{-- 2. ARCHITECTURAL CONTACT STUDIO (DIRECT CHANNELS + WHITE CARD ON GRAY) --}}
  <section class="fds-sec {{ !empty($c_maps) ? 'fds-bg-light' : 'fds-bg-white' }} fds-kontak-studio-sec">
    <div class="fds-container fds-kontak-studio">

      {{-- Left: Clean Direct Contact Directory --}}
      <aside class="fds-kontak-channels" aria-label="Saluran Kontak Langsung">
        <div data-aos="fade-up" class="fds-hairline-list">
          <div class="fds-kontak-channel-row">
            <span class="fds-kontak-channel-label">{{ $p_lbl_phone }}</span>
            <div class="fds-kontak-channel-body">
              <a href="{!! esc_url($c_wa_link) !!}" target="_blank" rel="noopener noreferrer" class="fds-kontak-primary-tel">
                {{ $c_phone }}
              </a>
              <div>
                <a href="{!! esc_url($c_wa_link) !!}" target="_blank" rel="noopener noreferrer" class="fds-text-link fds-kontak-wa-link">
                  {{ $p_wa_btn }} {!! $arrow_svg !!}
                </a>
              </div>
            </div>
          </div>

          <div class="fds-kontak-channel-row">
            <span class="fds-kontak-channel-label">{{ $p_lbl_email }}</span>
            <div class="fds-kontak-channel-body">
              <a href="mailto:{{ $c_email }}" class="fds-kontak-channel-email">
                {{ $c_email }}
              </a>
            </div>
          </div>

          @if($p_hours_wd !== '' || $p_hours_sat !== '')
            <div class="fds-kontak-channel-row">
              <span class="fds-kontak-channel-label">{{ $p_lbl_hours }}</span>
              <div class="fds-kontak-channel-body fds-kontak-hours">
                @if($p_hours_wd !== '')<p>{{ $p_hours_wd }}</p>@endif
                @if($p_hours_sat !== '')<p>{{ $p_hours_sat }}</p>@endif
              </div>
            </div>
          @endif

          @if(!empty($c_maps))
            <div class="fds-kontak-channel-row">
              <span class="fds-kontak-channel-label">{{ $p_map_title }}</span>
              <div class="fds-kontak-channel-body">
                <a href="#lokasi-workshop" class="fds-text-link fds-kontak-jump">
                  {{ $p_map_jump }}
                </a>
              </div>
            </div>
          @endif
        </div>
      </aside>

      {{-- Right: Soft-Surface Studio Inquiry Form --}}
      <div id="hubungi-form" data-aos="fade-up" data-aos-delay="90" class="scroll-mt-24 fds-kontak-form-panel">
        <div class="fds-kontak-form-head">
          <h2 class="fds-h3 fds-kontak-form-title">{{ $p_form_title }}</h2>
          @if($p_form_desc !== '')
            <p class="fds-kontak-form-sub">{{ $p_form_desc }}</p>
          @endif
        </div>

        <form id="fds-inquiry-form" class="space-y-4" onsubmit="fdsSubmitInquiry(event)">
          @php
            wp_nonce_field('fds_inquiry_nonce', 'fds_inquiry_nonce_val');
            $_fds_kontak_track = function_exists('App\fds_get_tracking_config') ? \App\fds_get_tracking_config() : [];
            $_fds_kontak_ts_key = $_fds_kontak_track['turnstile_site_key'] ?? '';
            $_fds_kontak_ts_sec = $_fds_kontak_track['turnstile_secret_key'] ?? '';
            $_fds_kontak_use_ts = ($_fds_kontak_ts_key !== '' && $_fds_kontak_ts_sec !== '');
          @endphp
          <input type="hidden" name="service_slug" id="fds-service-slug" value="{{ $selected_service_slug }}">
          <input type="hidden" name="service_label" id="fds-service-label" value="{{ $selected_service_label }}">

          <div style="display:none !important; position:absolute !important; left:-9999px !important;" aria-hidden="true">
            <label for="fds_website_url_hp">Do not fill this field</label>
            <input type="text" id="fds_website_url_hp" name="website_url_hp" value="" tabindex="-1" autocomplete="off">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="kontak-fullname" class="fds-kontak-field-label">{{ $p_field_fullname_lbl }}</label>
              <input id="kontak-fullname" type="text" name="full_name" required maxlength="120" autocomplete="name" placeholder="{{ $p_field_fullname_ph }}" class="fds-kontak-field">
            </div>
            <div>
              <label for="kontak-comp" class="fds-kontak-field-label">{{ $p_field_comp_lbl }}</label>
              <input id="kontak-comp" type="text" name="company" required maxlength="100" autocomplete="organization" placeholder="{{ $p_field_comp_ph }}" class="fds-kontak-field">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="kontak-email" class="fds-kontak-field-label">{{ $p_field_email_lbl }}</label>
              <input id="kontak-email" type="email" name="email" required maxlength="100" autocomplete="email" placeholder="{{ $p_field_email_ph }}" class="fds-kontak-field">
            </div>
            <div>
              <label for="kontak-phone" class="fds-kontak-field-label">{{ $p_field_phone_lbl }}</label>
              <input id="kontak-phone" type="tel" name="phone" required maxlength="30" autocomplete="tel" inputmode="tel" placeholder="{{ $p_field_phone_ph }}" class="fds-kontak-field">
            </div>
          </div>

          <div>
            <label for="kontak-msg" class="fds-kontak-field-label">{{ $p_field_msg_lbl }}</label>
            <textarea id="kontak-msg" name="message" rows="3" required maxlength="3000" placeholder="{{ $p_field_msg_ph }}" class="fds-kontak-field fds-kontak-textarea"></textarea>
          </div>

          @if($_fds_kontak_use_ts)
            <div>
              <div class="cf-turnstile" data-sitekey="{{ $_fds_kontak_ts_key }}" data-theme="light"></div>
            </div>
            @once
              <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
            @endonce
          @endif

          <div class="fds-kontak-submit-row">
            <button type="submit" id="fds-inquiry-btn" class="fds-btn fds-btn--primary fds-kontak-submit group">
              <span id="fds-inquiry-btn-text">{{ $p_form_btn }}</span>
              <span id="fds-inquiry-btn-arrow" class="inline-flex items-center justify-center flex-shrink-0">{!! $arrow_svg !!}</span>
            </button>
            @if(!empty($p_form_note))
              <p class="fds-kontak-note">{{ $p_form_note }}</p>
            @endif
          </div>
        </form>
      </div>

    </div>
  </section>

  {{-- 3. ARCHITECTURAL WORKSHOP LOCATION + WIDE MAP --}}
  @if(!empty($c_maps))
  <section id="lokasi-workshop" class="fds-sec fds-bg-white fds-kontak-map-sec scroll-mt-20">
    <div class="fds-container">
      <div data-aos="fade-up" class="fds-kontak-map-head">
        <div>
          <h2 class="fds-h2 fds-kontak-map-title">{{ $p_map_title }}</h2>
          <p class="fds-kontak-address">{{ $c_address }}</p>
        </div>
        @if(!empty($p_map_link))
          <a href="{!! esc_url($p_map_link) !!}" target="_blank" rel="noopener noreferrer" class="fds-text-link shrink-0">
            {{ $p_map_btn }} {!! $arrow_svg !!}
          </a>
        @endif
      </div>

      <div data-aos="fade-up" data-aos-delay="90" class="fds-kontak-map-frame">
        <div id="map-loader-kontak" class="fds-kontak-map-loader"></div>
        <iframe src="{!! esc_url($c_maps) !!}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="{{ $p_map_title }}"
          onload="var l = document.getElementById('map-loader-kontak'); if(l){ l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(function(){ l.remove(); }, 500); }">
        </iframe>
      </div>
    </div>
  </section>
  @endif

</div>

<x-snackbar />

<script>
var fdsServiceOptionMap = {!! wp_json_encode($fds_service_options) !!};
document.addEventListener('DOMContentLoaded', function() {
  var slugInput = document.getElementById('fds-service-slug');
  var lblInput = document.getElementById('fds-service-label');
  if (!slugInput || !lblInput) return;
  try {
    var params = new URLSearchParams(window.location.search);
    var requested = (params.get('layanan') || params.get('sektor') || params.get('produk') || params.get('model') || '').trim().toLowerCase();
    if (requested && fdsServiceOptionMap) {
      Object.keys(fdsServiceOptionMap).some(function(k) {
        if (k.toLowerCase() === requested) {
          slugInput.value = k;
          lblInput.value = String(fdsServiceOptionMap[k] || '').trim();
          return true;
        }
        return false;
      });
    }
  } catch (err) {}
});

function fdsSubmitInquiry(e) {
  e.preventDefault();
  var form = document.getElementById('fds-inquiry-form');
  var btn = document.getElementById('fds-inquiry-btn');
  var btnText = document.getElementById('fds-inquiry-btn-text');
  if (!form || !btn) return;

  var slugInput = document.getElementById('fds-service-slug');
  var lblInput = document.getElementById('fds-service-label');
  var srvSlug = (slugInput && slugInput.value) ? slugInput.value : 'kontak-inquiry';
  var srvLabel = (lblInput && lblInput.value) ? lblInput.value.trim() : {!! wp_json_encode($p_opt_default) !!};

  var fullName = (form.querySelector('[name="full_name"]') || {}).value || '';
  var company = (form.querySelector('[name="company"]') || {}).value || '';
  var email = (form.querySelector('[name="email"]') || {}).value || '';
  var phone = (form.querySelector('[name="phone"]') || {}).value || '';
  var message = (form.querySelector('[name="message"]') || {}).value || '';

  if (!fullName.trim() || !company.trim() || !email.trim() || !phone.trim() || !message.trim()) {
    if (typeof fdsShowSnackbar === 'function') fdsShowSnackbar(false, 'Formulir Belum Lengkap', 'Semua kolom formulir wajib diisi sebelum mengirim pesan.');
    return;
  }
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.trim())) {
    if (typeof fdsShowSnackbar === 'function') fdsShowSnackbar(false, 'Format Email Tidak Valid', 'Silakan masukkan alamat email yang benar.');
    return;
  }
  var tsInput = form.querySelector('[name="cf-turnstile-response"]');
  if (form.querySelector('.cf-turnstile') && (!tsInput || !tsInput.value)) {
    if (typeof fdsShowSnackbar === 'function') fdsShowSnackbar(false, 'Verifikasi Keamanan', 'Silakan selesaikan verifikasi keamanan Cloudflare Turnstile.');
    return;
  }
  if (typeof window.fdsPopulateAttribution === 'function') window.fdsPopulateAttribution(form);

  var formData = new FormData(form);
  formData.append('action', 'fds_submit_inquiry');
  formData.append('nonce', document.getElementById('fds_inquiry_nonce_val').value);

  btn.disabled = true;
  btn.classList.add('opacity-70', 'cursor-not-allowed');
  btnText.textContent = {!! wp_json_encode($p_form_loading) !!};

  fetch({!! wp_json_encode(admin_url('admin-ajax.php')) !!}, { method: 'POST', body: formData })
  .then(function(res) { return res.json(); })
  .then(function(data) {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = {!! wp_json_encode($p_form_btn) !!};
    if (data.success) {
      var leadRef = (data.data && data.data.lead_ref) ? data.data.lead_ref : '';
      var respLabel = (data.data && data.data.service_label) ? data.data.service_label : srvLabel;
      try { sessionStorage.setItem('fds_last_lead_summary', JSON.stringify({ lead_ref: leadRef, service_label: respLabel, company: company.trim() })); } catch (err) {}
      if (typeof window.fdsTrackEvent === 'function') window.fdsTrackEvent('generate_lead', { service_slug: srvSlug, service_label: respLabel, lead_ref: leadRef });
      if (typeof fdsShowSnackbar === 'function') fdsShowSnackbar(true, 'Pesan Berhasil Terkirim', (data.data && data.data.message) ? data.data.message : 'Pesan Anda telah berhasil disimpan di sistem kami.');
      form.reset();
      if (window.turnstile && typeof window.turnstile.reset === 'function') { try { window.turnstile.reset(); } catch (err) {} }
      if (data.data && data.data.redirect_url) { setTimeout(function() { window.location.href = data.data.redirect_url; }, 450); }
    } else {
      if (typeof fdsShowSnackbar === 'function') fdsShowSnackbar(false, 'Gagal Mengirim Pesan', (data.data && data.data.message) ? data.data.message : 'Terjadi kesalahan. Silakan periksa kembali formulir Anda.');
    }
  })
  .catch(function() {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = {!! wp_json_encode($p_form_btn) !!};
    if (typeof fdsShowSnackbar === 'function') fdsShowSnackbar(false, 'Gangguan Jaringan', 'Gagal terhubung ke server. Silakan coba lagi atau hubungi kami via WhatsApp.');
  });
}
</script>
@endsection
