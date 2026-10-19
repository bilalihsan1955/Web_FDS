{{--
  Template Name: Kontak Kami / Contact FDS
--}}

@extends('layouts.app')

@section('content')
@php
  // Ambil data kontak terpusat dari Theme Settings
  $global_c  = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_phone   = $global_c['phone'] ?? '+62 8112 748 882';
  $c_email   = $global_c['email'] ?? 'marketing@fulldronesolutions.com';
  $c_address = $global_c['address'] ?? 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281';
  $c_wa_link = $global_c['wa_link'] ?? 'https://wa.me/628112748882';
  $c_maps    = $global_c['maps_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid';

  // Deep decode string untuk kebersihan output
  $clean_str = function($val) {
    if (!is_string($val)) return $val;
    $prev = '';
    while ($prev !== $val) {
      $prev = $val;
      $val = wp_specialchars_decode($val, ENT_QUOTES);
    }
    return $val;
  };

  $c_phone   = $clean_str($c_phone);
  $c_email   = $clean_str($c_email);
  $c_address = $clean_str($c_address);
  $c_company = $clean_str($global_c['company_name'] ?? 'PT Karya Solusi Angkasa');

  // Kustomisasi Narasi Halaman Kontak Dinamis
  $p_badge       = $clean_str($global_c['page_kontak_badge'] ?? 'Pusat Kontak');
  $p_title       = $clean_str($global_c['page_kontak_title'] ?? 'Hubungi Kami.');
  $p_desc        = $clean_str($global_c['page_kontak_desc'] ?? 'Diskusikan pengadaan armada drone, jadwal demo terbang, atau kebutuhan teknis operasional Anda bersama tim Full Drone Solutions.');
  $p_panel_label = $clean_str($global_c['page_kontak_panel_label'] ?? 'Kantor & Workshop Resmi');
  $p_panel_desc  = $clean_str($global_c['page_kontak_panel_desc'] ?? 'Produsen UAV nasional bersertifikasi TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015 berlokasi di Sleman, Yogyakarta.');
  $p_hours_wd    = $clean_str($global_c['page_kontak_hours_wd'] ?? 'Senin – Jumat: 08:30 – 17:00 WIB');
  $p_hours_sat   = $clean_str($global_c['page_kontak_hours_sat'] ?? 'Sabtu: 08:30 – 13:00 WIB');
  $p_wa_btn      = $clean_str($global_c['page_kontak_wa_btn'] ?? 'Chat WhatsApp Kami');
  $p_form_title  = $clean_str($global_c['page_kontak_form_title'] ?? 'Kirim Pesan atau Permintaan Penawaran');
  $p_form_desc   = $clean_str($global_c['page_kontak_form_desc'] ?? 'Lengkapi formulir di bawah ini, tim kami akan merespons dalam 1×24 jam kerja:');
  $p_form_btn    = $clean_str($global_c['page_kontak_form_btn'] ?? 'Kirim Pesan');
  $p_form_note   = $clean_str($global_c['page_kontak_form_note'] ?? 'Respons 1×24 jam kerja. Data terjamin aman.');
  $p_map_title   = $clean_str($global_c['page_kontak_map_title'] ?? 'Lokasi Workshop');
  $p_map_btn     = $clean_str($global_c['page_kontak_map_btn'] ?? 'Buka di Google Maps');
  $p_map_link    = $global_c['page_kontak_map_link'] ?? 'https://maps.google.com/?q=Full+Drone+Solutions+Condongcatur+Sleman';
@endphp

<div id="contact-page" class="w-full bg-[#f5f5f7] font-sans">

  {{-- ============================================================ --}}
  {{-- 1. HERO SECTION — Apple Minimalist & Ringkas                --}}
  {{-- ============================================================ --}}
  <section class="pt-[52px] bg-[#f5f5f7] overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-14 sm:pt-20 pb-8 sm:pb-12 text-center">

      <p class="inline-block text-[13px] font-semibold text-[#0f808a] mb-3 tracking-wide">
        {!! esc_html($p_badge) !!}
      </p>

      <h1 class="text-[38px] sm:text-[50px] lg:text-[58px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.08] max-w-[800px] mx-auto">
        {!! esc_html($p_title) !!}
      </h1>

      <p class="mt-3.5 text-[16px] sm:text-[18px] text-[#515154] font-normal max-w-[620px] mx-auto leading-relaxed">
        {!! nl2br(esc_html($p_desc)) !!}
      </p>

    </div>
  </section>


  {{-- ============================================================ --}}
  {{-- 2. MASTER CONTACT & INQUIRY HUB (Dual-Surface Card)          --}}
  {{-- ============================================================ --}}
  <section id="hubungi-form" class="bg-[#f5f5f7] pb-12 sm:pb-16 overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 w-full">

      {{-- Unified Master Card: Seamless Dual-Surface --}}
      <div class="bg-white rounded-[2rem] border border-black/[0.08] shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 items-stretch">

        {{-- Left Side: Dark Teal Architectural Panel (5 cols) matching Homepage Contact Section --}}
        <div class="lg:col-span-5 bg-gradient-to-br from-[#0a555c] via-[#052b2e] to-[#031e20] py-9 px-8 sm:px-10 lg:px-12 text-white flex flex-col justify-between relative overflow-hidden border-b lg:border-b-0 lg:border-r border-white/[0.08] group">
          
          {{-- Ambient Dual-Orb Gradient Glow (Teal Palette matching Homepage) --}}
          <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
            <svg class="absolute inset-0 w-full h-full transition-transform duration-700 group-hover:scale-105" viewBox="0 0 335 160" preserveAspectRatio="xMaxYMin slice" fill="none" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <linearGradient id="orb1-kontak-page" x1="323.781" y1="-61" x2="286" y2="80" gradientUnits="userSpaceOnUse">
                  <stop offset="0.15" stop-color="#0f808a" stop-opacity="0.15"/>
                  <stop offset="1" stop-color="#18d6e7" stop-opacity="0.8"/>
                </linearGradient>
                <linearGradient id="orb2-kontak-page" x1="239.637" y1="64.8558" x2="201.856" y2="205.856" gradientUnits="userSpaceOnUse">
                  <stop offset="0.15" stop-color="#0f808a" stop-opacity="0.15"/>
                  <stop offset="1" stop-color="#18d6e7" stop-opacity="0.8"/>
                </linearGradient>
              </defs>
              <g opacity="0.45">
                <circle cx="286" cy="5" r="75" fill="url(#orb1-kontak-page)"/>
                <circle cx="201.856" cy="130.856" r="75" transform="rotate(-165 201.856 130.856)" fill="url(#orb2-kontak-page)"/>
              </g>
            </svg>
          </div>

          {{-- Top Header Section --}}
          <div class="relative z-10">
            <span class="text-[12px] font-semibold text-white/80 tracking-wide block mb-1.5">
              {!! esc_html($p_panel_label) !!}
            </span>
            <h2 class="text-[23px] sm:text-[26px] font-semibold tracking-tight text-white leading-tight mb-2">
              {!! esc_html($c_company) !!}
            </h2>
            <p class="text-[13px] text-white/65 leading-relaxed max-w-[380px]">
              {!! nl2br(esc_html($p_panel_desc)) !!}
            </p>
          </div>

          {{-- Direct Contact Details --}}
          <div class="my-8 space-y-4 relative z-10">
            <div>
              <p class="text-[11px] font-medium text-white/40 mb-1">Telepon / WhatsApp</p>
              <a href="{{ esc_url($c_wa_link) }}" target="_blank" rel="noopener"
                 class="text-[15.5px] sm:text-[16px] font-semibold text-white hover:text-[#75e6f0] transition-colors">
                {!! esc_html($c_phone) !!}
              </a>
            </div>

            <div>
              <p class="text-[11px] font-medium text-white/40 mb-1">Email Resmi</p>
              <a href="mailto:{{ esc_attr($c_email) }}"
                 class="text-[14px] sm:text-[14.5px] font-semibold text-white hover:text-[#75e6f0] transition-colors break-all">
                {!! esc_html($c_email) !!}
              </a>
            </div>

            <div>
              <p class="text-[11px] font-medium text-white/40 mb-1">Alamat Fasilitas</p>
              <p class="text-[12.5px] sm:text-[13px] text-white/70 leading-relaxed max-w-[380px]">
                {!! esc_html($c_address) !!}
              </p>
            </div>

            <div class="pt-2 border-t border-white/10">
              <p class="text-[11px] font-medium text-white/40 mb-1">Jam Operasional</p>
              <div class="text-[12px] text-white/70 space-y-0.5">
                @if(!empty($p_hours_wd))
                  <p>{!! esc_html($p_hours_wd) !!}</p>
                @endif
                @if(!empty($p_hours_sat))
                  <p>{!! esc_html($p_hours_sat) !!}</p>
                @endif
              </div>
            </div>
          </div>

          {{-- WhatsApp Action Button --}}
          <div class="relative z-10">
            <a href="{{ esc_url($c_wa_link) }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] active:scale-[0.97] text-white font-semibold text-[13px] px-5 py-2.5 rounded-full transition-all duration-150 shadow-sm">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
              <span>{!! esc_html($p_wa_btn) !!}</span>
            </a>
          </div>
        </div>

        {{-- Right Side: Clean Inquiry Form (7 cols) --}}
        <div class="lg:col-span-7 py-9 px-8 sm:px-10 lg:px-12 bg-white flex flex-col justify-between">
          <div class="mb-5">
            <h3 class="text-[19px] sm:text-[21px] font-semibold text-[#1d1d1f] tracking-tight mb-1">
              {!! esc_html($p_form_title) !!}
            </h3>
            <p class="text-[13px] text-[#64748b]">{!! nl2br(esc_html($p_form_desc)) !!}</p>
          </div>

          {{-- Form Body --}}
          <form id="fds-inquiry-form" class="flex-1 flex flex-col justify-between space-y-4" onsubmit="fdsSubmitInquiry(event)">
            @php wp_nonce_field('fds_inquiry_nonce', 'fds_inquiry_nonce_val'); @endphp

            {{-- Anti-Bot Honeypot Trap --}}
            <div style="display:none !important; position:absolute !important; left:-9999px !important;" aria-hidden="true">
              <label for="fds_website_url_hp">Do not fill this field</label>
              <input type="text" id="fds_website_url_hp" name="website_url_hp" value="" tabindex="-1" autocomplete="off">
            </div>

            <div class="space-y-3.5">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                  <label class="block text-[11px] font-medium text-[#475569] mb-1">Nama Depan <span class="text-red-500">*</span></label>
                  <input type="text" name="first_name" required maxlength="60" placeholder="Ahmad"
                    class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-[#475569] mb-1">Nama Belakang <span class="text-red-500">*</span></label>
                  <input type="text" name="last_name" required maxlength="60" placeholder="Fauzi"
                    class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                  <label class="block text-[11px] font-medium text-[#475569] mb-1">Perusahaan / Instansi <span class="text-red-500">*</span></label>
                  <input type="text" name="company" required maxlength="100" placeholder="PT. Contoh Indonesia"
                    class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-[#475569] mb-1">Email Bisnis <span class="text-red-500">*</span></label>
                  <input type="email" name="email" required maxlength="100" placeholder="nama@perusahaan.co.id"
                    class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
                </div>
              </div>

              <div>
                <label class="block text-[11px] font-medium text-[#475569] mb-1">Nomor Telepon / WhatsApp <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" required maxlength="30" placeholder="+62 812-XXXX-XXXX"
                  class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150">
              </div>

              <div>
                <label class="block text-[11px] font-medium text-[#475569] mb-1">Kebutuhan Drone / Pertanyaan Anda <span class="text-red-500">*</span></label>
                <textarea name="message" rows="3" required maxlength="3000" placeholder="Jelaskan kebutuhan pengadaan armada, demo uji terbang, atau pertanyaan teknis..."
                  class="w-full bg-[#f8fafc] border border-[#e2e8f0] focus:border-[#0f808a] focus:bg-white rounded-xl px-3.5 py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all duration-150 resize-none"></textarea>
              </div>
            </div>

            {{-- Submit Row --}}
            <div class="pt-3 mt-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
              <button type="submit" id="fds-inquiry-btn"
                class="bg-[#0f808a] hover:bg-[#0a555c] active:scale-[0.97] text-white font-semibold text-[13.5px] px-7 py-2.5 rounded-full transition-all duration-200 whitespace-nowrap shadow-md shadow-[#0f808a]/25 inline-flex items-center gap-1.5 cursor-pointer">
                <span id="fds-inquiry-btn-text">{!! esc_html($p_form_btn) !!}</span>
                <span id="fds-inquiry-btn-arrow" class="text-[15px]">&rsaquo;</span>
              </button>
              <div class="flex items-center gap-1.5 text-[11.5px] text-[#86868b] whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-[#86868b] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>{!! esc_html($p_form_note) !!}</span>
              </div>
            </div>

          </form>
        </div>

      </div>
    </div>
  </section>


  {{-- ============================================================ --}}
  {{-- 3. INTERACTIVE GOOGLE MAPS SECTION (Clean & Focused)        --}}
  {{-- ============================================================ --}}
  <section id="lokasi-workshop" class="bg-[#f5f5f7] pb-20 sm:pb-28">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 w-full">

      {{-- Map Card Container --}}
      <div class="bg-white rounded-[2rem] border border-black/[0.08] shadow-lg overflow-hidden">
        
        {{-- Map Top Bar Header --}}
        <div class="px-6 sm:px-8 py-5 border-b border-black/[0.08] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
          <div>
            <h3 class="text-[16px] sm:text-[17px] font-semibold text-[#1d1d1f] leading-snug">
              {!! esc_html($p_map_title) !!}
            </h3>
            <p class="text-[12.5px] text-[#515154] mt-0.5">
              {!! esc_html($c_address) !!}
            </p>
          </div>
          <div class="flex-shrink-0">
            <a href="{{ esc_url($p_map_link) }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-[#0f808a] hover:text-[#0a555c] bg-[#0f808a]/10 hover:bg-[#0f808a]/15 px-3.5 py-1.5 rounded-full transition-colors">
              <span>{!! esc_html($p_map_btn) !!}</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
          </div>
        </div>

        {{-- Google Maps Iframe --}}
        <div class="w-full relative overflow-hidden bg-[#e5e3df]" style="height: 380px;">
          <iframe 
            src="{{ esc_url($c_maps) }}" 
            width="100%" 
            height="100%" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="strict-origin-when-cross-origin"
            title="Peta Lokasi Workshop Full Drone Solutions Sleman Yogyakarta">
          </iframe>
        </div>

      </div>

    </div>
  </section>

</div>

{{-- Floating Bottom-Right Toast / Snackbar Notification --}}
<div id="fds-snackbar" class="fixed bottom-6 right-6 z-[99999] max-w-[400px] w-[calc(100%-3rem)] transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none">
  <div id="fds-snackbar-card" class="rounded-2xl p-4 shadow-2xl shadow-gray-900/30 flex items-start gap-3.5">
    <div id="fds-snackbar-icon" class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"></div>
    <div class="flex-1 min-w-0">
      <p id="fds-snackbar-title" class="text-[13.5px] font-bold text-white leading-tight mb-0.5"></p>
      <p id="fds-snackbar-desc" class="text-[12.5px] text-white/90 leading-snug"></p>
    </div>
    <button type="button" onclick="fdsCloseSnackbar()" class="text-white/70 hover:text-white hover:bg-white/15 p-1 rounded-lg transition-colors flex-shrink-0 cursor-pointer" aria-label="Tutup Notifikasi">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>
</div>

<script>
var fdsSnackbarTimer = null;

function fdsShowSnackbar(isSuccess, title, message) {
  var bar = document.getElementById('fds-snackbar');
  var card = document.getElementById('fds-snackbar-card');
  var icon = document.getElementById('fds-snackbar-icon');
  var titleEl = document.getElementById('fds-snackbar-title');
  var descEl = document.getElementById('fds-snackbar-desc');
  if (!bar) return;

  if (isSuccess) {
    card.className = 'bg-[#059669] border border-[#047857] rounded-2xl p-4 shadow-2xl shadow-gray-900/30 flex items-start gap-3.5 text-white';
    icon.className = 'w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center flex-shrink-0 mt-0.5';
    icon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
  } else {
    card.className = 'bg-[#dc2626] border border-[#b91c1c] rounded-2xl p-4 shadow-2xl shadow-gray-900/30 flex items-start gap-3.5 text-white';
    icon.className = 'w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center flex-shrink-0 mt-0.5';
    icon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
  }

  titleEl.textContent = title;
  descEl.textContent = message;

  bar.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
  bar.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');

  clearTimeout(fdsSnackbarTimer);
  fdsSnackbarTimer = setTimeout(function() {
    fdsCloseSnackbar();
  }, 5000);
}

function fdsCloseSnackbar() {
  var bar = document.getElementById('fds-snackbar');
  if (!bar) return;
  bar.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
  bar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
}

function fdsSubmitInquiry(e) {
  e.preventDefault();
  var form = document.getElementById('fds-inquiry-form');
  var btn = document.getElementById('fds-inquiry-btn');
  var btnText = document.getElementById('fds-inquiry-btn-text');
  
  if (!form || !btn) return;

  // Validasi Semua Field Wajib di Frontend
  var firstName = (form.querySelector('[name="first_name"]') || {}).value || '';
  var lastName  = (form.querySelector('[name="last_name"]') || {}).value || '';
  var company   = (form.querySelector('[name="company"]') || {}).value || '';
  var email     = (form.querySelector('[name="email"]') || {}).value || '';
  var phone     = (form.querySelector('[name="phone"]') || {}).value || '';
  var message   = (form.querySelector('[name="message"]') || {}).value || '';

  if (!firstName.trim() || !lastName.trim() || !company.trim() || !email.trim() || !phone.trim() || !message.trim()) {
    fdsShowSnackbar(false, 'Formulir Belum Lengkap', 'Semua kolom formulir wajib diisi sebelum mengirim pesan.');
    return;
  }

  // Validasi format email dasar
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.trim())) {
    fdsShowSnackbar(false, 'Format Email Tidak Valid', 'Silakan masukkan alamat email yang benar (contoh: nama@perusahaan.com).');
    return;
  }
  
  var formData = new FormData(form);
  formData.append('action', 'fds_submit_inquiry');
  formData.append('nonce', document.getElementById('fds_inquiry_nonce_val').value);

  btn.disabled = true;
  btn.classList.add('opacity-70', 'cursor-not-allowed');
  btnText.textContent = 'Mengirim Pesan...';

  fetch('{{ admin_url("admin-ajax.php") }}', {
    method: 'POST',
    body: formData
  })
  .then(function(res) { return res.json(); })
  .then(function(data) {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = {!! json_encode($p_form_btn) !!};

    if (data.success) {
      fdsShowSnackbar(true, 'Pesan Berhasil Terkirim', data.data.message || 'Pesan Anda telah berhasil disimpan di sistem kami.');
      form.reset();
    } else {
      fdsShowSnackbar(false, 'Gagal Mengirim Pesan', data.data.message || 'Terjadi kesalahan. Silakan periksa kembali formulir Anda.');
    }
  })
  .catch(function(err) {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = {!! json_encode($p_form_btn) !!};
    fdsShowSnackbar(false, 'Gangguan Jaringan', 'Gagal terhubung ke server. Silakan coba lagi atau hubungi kami via WhatsApp.');
  });
}
</script>
@endsection
