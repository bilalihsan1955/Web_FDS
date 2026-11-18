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
  $arrow_svg = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>';

  $c_phone   = $cln($c_phone);
  $c_email   = $cln($c_email);
  $c_address = $cln($c_address);

  $p_title       = $cln($global_c['page_kontak_title'] ?? 'Hubungi Kami.');
  $p_desc        = $cln($global_c['page_kontak_desc'] ?? 'Diskusikan pengadaan armada drone, jadwal demo terbang, atau kebutuhan teknis operasional Anda bersama tim Full Drone Solutions.');
  $p_lbl_phone   = $cln($global_c['page_kontak_lbl_phone'] ?? 'Telepon & WhatsApp');
  $p_lbl_email   = $cln($global_c['page_kontak_lbl_email'] ?? 'Email Resmi');
  $p_lbl_hours   = $cln($global_c['page_kontak_lbl_hours'] ?? 'Jam Operasional');
  $p_map_jump    = $cln($global_c['page_kontak_map_jump_text'] ?? 'Lihat titik lokasi & workshop resmi ↓');
  $p_panel_desc  = $cln($global_c['page_kontak_panel_desc'] ?? 'Produsen UAV nasional dengan manajemen mutu ISO 9001:2015, TKDN + BMP 60,74% (seri FERTO), serta SNI 9199:2023 pada FERTO 15 dan FERTO 22, berlokasi di Sleman, Yogyakarta.');
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
  $p_opt_default     = $cln($global_c['page_kontak_opt_default'] ?? 'Konsultasi Umum, Demo Unit & Kebutuhan Korporasi');
  $p_field_srv_lbl   = $cln($global_c['page_kontak_field_service_lbl'] ?? 'Fokus Layanan / Model UAV');
  $p_field_fname_lbl = $cln($global_c['page_kontak_field_fname_lbl'] ?? 'Nama Depan');
  $p_field_fname_ph  = $cln($global_c['page_kontak_field_fname_ph'] ?? 'Nama depan');
  $p_field_lname_lbl = $cln($global_c['page_kontak_field_lname_lbl'] ?? 'Nama Belakang');
  $p_field_lname_ph  = $cln($global_c['page_kontak_field_lname_ph'] ?? 'Nama belakang');
  $p_field_comp_lbl  = $cln($global_c['page_kontak_field_comp_lbl'] ?? 'Perusahaan / Instansi');
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
  $p_guide_title     = $cln($global_c['page_kontak_guide_title'] ?? 'Layanan Konsultasi & Pengadaan Korporasi FDS');
  $p_guide_body      = (string) ($global_c['page_kontak_guide_body'] ?? '');
  if ($p_guide_body === '' && function_exists('\App\fds_get_kontak_default_guide_html')) {
      $p_guide_body = \App\fds_get_kontak_default_guide_html();
  }

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

<div id="contact-page" class="w-full bg-white font-sans pt-[52px]">

  {{-- 1. CONTACT HUB (BRIGHT OCEAN-TEAL WAVE + COMPACT FORM) --}}
  <section class="fds-sec fds-bg-teal" style="padding-block:72px 88px;">
    <x-fds-wave type="kontak" uid="kontak-hero" />
    <div class="fds-container fds-sticky-split" style="align-items:start;">
      <div>
        <h1 class="fds-h1" style="margin-bottom:20px;">{{ $p_title }}</h1>
        <p class="fds-lead" style="margin-bottom:36px;">
          {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", $p_desc))) !!}
        </p>

        <div class="fds-hairline-list" style="margin-bottom:24px;">
          <div class="fds-hairline-row" style="padding-block:18px;">
            <span class="fds-caption">{{ $p_lbl_phone }}</span>
            <div>
              <a href="{!! esc_url($c_wa_link) !!}" target="_blank" rel="noopener noreferrer"
                 style="color:var(--fds-white);font-size:1.25rem;font-weight:600;text-decoration:none;display:inline-block;margin-bottom:10px;">
                {{ $c_phone }}
              </a>
              <div>
                <a href="{!! esc_url($c_wa_link) !!}" target="_blank" rel="noopener noreferrer" class="fds-btn fds-btn--whatsapp">
                  {{ $p_wa_btn }}
                </a>
              </div>
            </div>
          </div>

          <div class="fds-hairline-row" style="padding-block:18px;">
            <span class="fds-caption">{{ $p_lbl_email }}</span>
            <a href="mailto:{{ $c_email }}" style="color:var(--fds-white);font-size:1.0625rem;font-weight:500;text-decoration:none;word-break:break-all;">
              {{ $c_email }}
            </a>
          </div>

          @if($p_hours_wd !== '' || $p_hours_sat !== '')
          <div class="fds-hairline-row" style="padding-block:18px;">
            <span class="fds-caption">{{ $p_lbl_hours }}</span>
            <div>
              @if($p_hours_wd !== '')<p class="fds-body" style="margin:0;">{{ $p_hours_wd }}</p>@endif
              @if($p_hours_sat !== '')<p class="fds-body" style="margin:2px 0 0;">{{ $p_hours_sat }}</p>@endif
            </div>
          </div>
          @endif
        </div>

        @if(!empty($c_maps))
          <a href="#lokasi-workshop" class="fds-text-link">{{ $p_map_jump }}</a>
        @endif
      </div>

      <div id="hubungi-form" class="scroll-mt-24 bg-white text-[#1d1d1f] rounded-[20px] p-7 sm:p-9 shadow-[0_24px_64px_-20px_rgba(3,30,32,0.35)]">
        <div style="margin-bottom:22px;">
          <h2 class="fds-h3" style="margin-bottom:6px;">{{ $p_form_title }}</h2>
          <p class="fds-caption" style="margin:0;color:var(--fds-ink-muted-aa);">{{ $p_form_desc }}</p>
        </div>

        <form id="fds-inquiry-form" class="space-y-4" onsubmit="fdsSubmitInquiry(event)">
          @php
            wp_nonce_field('fds_inquiry_nonce', 'fds_inquiry_nonce_val');
            $_fds_kontak_track = function_exists('App\fds_get_tracking_config') ? \App\fds_get_tracking_config() : [];
            $_fds_kontak_ts_key = $_fds_kontak_track['turnstile_site_key'] ?? '';
            $_fds_kontak_ts_sec = $_fds_kontak_track['turnstile_secret_key'] ?? '';
            $_fds_kontak_use_ts = ($_fds_kontak_ts_key !== '' && $_fds_kontak_ts_sec !== '');
          @endphp
          <input type="hidden" name="service_label" id="fds-service-label" value="{{ $selected_service_label }}">

          <div style="display:none !important; position:absolute !important; left:-9999px !important;" aria-hidden="true">
            <label for="fds_website_url_hp">Do not fill this field</label>
            <input type="text" id="fds_website_url_hp" name="website_url_hp" value="" tabindex="-1" autocomplete="off">
          </div>

          <div>
            <label for="fds-service-select" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $p_field_srv_lbl }} <span class="text-red-600">*</span></label>
            <select id="fds-service-select" name="service_slug" required
              class="w-full min-h-[48px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] outline-none">
              @foreach($fds_service_options as $opt_val => $opt_text)
                <option value="{{ $opt_val }}" @selected($selected_service_slug === $opt_val)>{{ $opt_text }}</option>
              @endforeach
            </select>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
              <label for="kontak-fname" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $p_field_fname_lbl }} <span class="text-red-600">*</span></label>
              <input id="kontak-fname" type="text" name="first_name" required maxlength="60" autocomplete="given-name" placeholder="{{ $p_field_fname_ph }}"
                class="w-full min-h-[48px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] outline-none">
            </div>
            <div>
              <label for="kontak-lname" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $p_field_lname_lbl }} <span class="text-red-600">*</span></label>
              <input id="kontak-lname" type="text" name="last_name" required maxlength="60" autocomplete="family-name" placeholder="{{ $p_field_lname_ph }}"
                class="w-full min-h-[48px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] outline-none">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
              <label for="kontak-comp" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $p_field_comp_lbl }} <span class="text-red-600">*</span></label>
              <input id="kontak-comp" type="text" name="company" required maxlength="100" autocomplete="organization" placeholder="{{ $p_field_comp_ph }}"
                class="w-full min-h-[48px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] outline-none">
            </div>
            <div>
              <label for="kontak-email" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $p_field_email_lbl }} <span class="text-red-600">*</span></label>
              <input id="kontak-email" type="email" name="email" required maxlength="100" autocomplete="email" placeholder="{{ $p_field_email_ph }}"
                class="w-full min-h-[48px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] outline-none">
            </div>
          </div>

          <div>
            <label for="kontak-phone" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $p_field_phone_lbl }} <span class="text-red-600">*</span></label>
            <input id="kontak-phone" type="tel" name="phone" required maxlength="30" autocomplete="tel" inputmode="tel" placeholder="{{ $p_field_phone_ph }}"
              class="w-full min-h-[48px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] outline-none">
          </div>

          <div>
            <label for="kontak-msg" class="block text-[13.5px] font-semibold text-[#1d1d1f] mb-1.5">{{ $p_field_msg_lbl }} <span class="text-red-600">*</span></label>
            <textarea id="kontak-msg" name="message" rows="3" required maxlength="3000" placeholder="{{ $p_field_msg_ph }}"
              class="w-full min-h-[96px] bg-[#fbfbfd] border border-[#d2d2d7] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[15px] text-[#1d1d1f] outline-none resize-y"></textarea>
          </div>

          @if($_fds_kontak_use_ts)
            <div>
              <div class="cf-turnstile" data-sitekey="{{ $_fds_kontak_ts_key }}" data-theme="light"></div>
            </div>
            @once
              <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
            @endonce
          @endif

          <div style="padding-top:4px;">
            <button type="submit" id="fds-inquiry-btn" class="fds-btn fds-btn--primary w-full">
              <span id="fds-inquiry-btn-text">{{ $p_form_btn }}</span>
            </button>
            @if(!empty($p_form_note))
              <p class="fds-caption" style="margin:10px 0 0;color:var(--fds-ink-muted-aa);">{{ $p_form_note }}</p>
            @endif
          </div>
        </form>
      </div>
    </div>
  </section>

  {{-- 2. WORKSHOP LOCATION SPLIT + GOOGLE MAPS --}}
  @if(!empty($c_maps))
  <section id="lokasi-workshop" class="fds-sec fds-bg-light scroll-mt-20">
    <div class="fds-container">
      <div class="fds-story-split" style="align-items:center;">
        <div>
          <h2 class="fds-h2" style="margin-bottom:16px;">{{ $p_map_title }}</h2>
          <p class="fds-lead" style="margin-bottom:14px;">{{ $c_address }}</p>
          @if($p_panel_desc !== '')
            <p class="fds-body" style="margin-bottom:24px;">{{ $p_panel_desc }}</p>
          @endif
          @if(!empty($p_map_link))
            <a href="{!! esc_url($p_map_link) !!}" target="_blank" rel="noopener noreferrer" class="fds-text-link">
              {{ $p_map_btn }} {!! $arrow_svg !!}
            </a>
          @endif
        </div>
        <div class="w-full rounded-[20px] overflow-hidden border border-black/[0.08] relative bg-[#eef2f6]" style="height:400px;max-height:460px;">
          <div id="map-loader-kontak" class="absolute inset-0 bg-slate-200 animate-pulse z-10 transition-opacity duration-500 pointer-events-none"></div>
          <iframe src="{!! esc_url($c_maps) !!}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="{{ $p_map_title }}"
            onload="var l = document.getElementById('map-loader-kontak'); if(l){ l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(function(){ l.remove(); }, 500); }">
          </iframe>
        </div>
      </div>
    </div>
  </section>
  @endif

  {{-- 3. PANDUAN KONSULTASI & PENGADAAN B2B --}}
  @if(!empty($p_guide_body) || !empty($p_guide_title))
  <section class="fds-sec fds-bg-white">
    <div class="fds-container fds-sticky-split">
      <div class="fds-sticky-col">
        <h2 class="fds-h2">{{ $p_guide_title }}</h2>
      </div>
      <div class="fds-drone-guide fds-prose">
        {!! wp_kses_post($p_guide_body) !!}
      </div>
    </div>
  </section>
  @endif

</div>

<x-snackbar />

<script>
document.addEventListener('DOMContentLoaded', function() {
  var sel = document.getElementById('fds-service-select');
  var lbl = document.getElementById('fds-service-label');
  if (!sel || !lbl) return;
  function syncServiceLabel() {
    var opt = sel.options[sel.selectedIndex];
    lbl.value = opt ? opt.text.trim() : '';
  }
  try {
    var params = new URLSearchParams(window.location.search);
    var requested = (params.get('layanan') || params.get('sektor') || params.get('produk') || params.get('model') || '').trim().toLowerCase();
    if (requested) {
      var matched = Array.prototype.find.call(sel.options, function(o) { return o.value.toLowerCase() === requested; });
      if (matched) sel.value = matched.value;
    }
  } catch (err) {}
  syncServiceLabel();
  sel.addEventListener('change', syncServiceLabel);
});

function fdsSubmitInquiry(e) {
  e.preventDefault();
  var form = document.getElementById('fds-inquiry-form');
  var btn = document.getElementById('fds-inquiry-btn');
  var btnText = document.getElementById('fds-inquiry-btn-text');
  if (!form || !btn) return;

  var sel = document.getElementById('fds-service-select');
  var lblInput = document.getElementById('fds-service-label');
  var srvSlug = sel ? sel.value : 'kontak-inquiry';
  var srvLabel = (sel && sel.selectedIndex >= 0 && sel.options[sel.selectedIndex]) ? sel.options[sel.selectedIndex].text.trim() : {!! wp_json_encode($p_opt_default) !!};
  if (lblInput) lblInput.value = srvLabel;

  var firstName = (form.querySelector('[name="first_name"]') || {}).value || '';
  var lastName = (form.querySelector('[name="last_name"]') || {}).value || '';
  var company = (form.querySelector('[name="company"]') || {}).value || '';
  var email = (form.querySelector('[name="email"]') || {}).value || '';
  var phone = (form.querySelector('[name="phone"]') || {}).value || '';
  var message = (form.querySelector('[name="message"]') || {}).value || '';

  if (!firstName.trim() || !lastName.trim() || !company.trim() || !email.trim() || !phone.trim() || !message.trim()) {
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
