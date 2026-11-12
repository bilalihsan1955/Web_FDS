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
  $c_address = $global_c['address'] ?? 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285';
  $c_wa_link = $global_c['wa_link'] ?? 'https://wa.me/628112748882';
  $c_maps    = $global_c['maps_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid';

  // Deep decode string helper
  $c_phone   = fds_clean_string($c_phone);
  $c_email   = fds_clean_string($c_email);
  $c_address = fds_clean_string($c_address);
  $c_company = fds_clean_string($global_c['company_name'] ?? 'PT Karya Solusi Angkasa');

  // Kustomisasi Narasi & Label Formulir Halaman Kontak Dinamis
  $p_badge       = fds_clean_string($global_c['page_kontak_badge'] ?? 'Pusat Kontak');
  $p_title       = fds_clean_string($global_c['page_kontak_title'] ?? 'Hubungi Kami.');
  $p_desc        = fds_clean_string($global_c['page_kontak_desc'] ?? 'Diskusikan pengadaan armada drone, jadwal demo terbang, atau kebutuhan teknis operasional Anda bersama tim Full Drone Solutions.');
  $p_lbl_phone   = fds_clean_string($global_c['page_kontak_lbl_phone'] ?? 'Telepon & WhatsApp');
  $p_lbl_email   = fds_clean_string($global_c['page_kontak_lbl_email'] ?? 'Email Resmi');
  $p_lbl_hours   = fds_clean_string($global_c['page_kontak_lbl_hours'] ?? 'Jam Operasional');
  $p_map_jump    = fds_clean_string($global_c['page_kontak_map_jump_text'] ?? 'Lihat titik lokasi & workshop resmi ↓');
  $p_panel_label = fds_clean_string($global_c['page_kontak_panel_label'] ?? 'Kantor & Workshop Resmi');
  $p_panel_desc_raw = fds_clean_string($global_c['page_kontak_panel_desc'] ?? 'Produsen UAV nasional dengan manajemen mutu ISO 9001:2015, TKDN + BMP 60,74% (seri FERTO), serta SNI 9199:2023 pada FERTO 15 dan FERTO 22, berlokasi di Sleman, Yogyakarta.');
  $p_panel_desc  = function_exists('fds_scrub_legacy_text') ? fds_scrub_legacy_text($p_panel_desc_raw) : $p_panel_desc_raw;
  $p_hours_wd    = fds_clean_string($global_c['page_kontak_hours_wd'] ?? 'Senin – Jumat: 08:30 – 17:00 WIB');
  $p_hours_sat   = fds_clean_string($global_c['page_kontak_hours_sat'] ?? 'Sabtu: 08:30 – 13:00 WIB');
  $p_wa_btn      = fds_clean_string($global_c['page_kontak_wa_btn'] ?? 'Chat WhatsApp Kami');
  $p_form_title  = fds_clean_string($global_c['page_kontak_form_title'] ?? 'Kirim Pesan atau Permintaan Penawaran');
  $p_form_desc   = fds_clean_string($global_c['page_kontak_form_desc'] ?? 'Silakan lengkapi formulir di bawah ini, tim representatif kami siap membantu kebutuhan Anda:');
  $p_form_btn    = fds_clean_string($global_c['page_kontak_form_btn'] ?? 'Kirim Pesan');
  $p_form_loading = fds_clean_string($global_c['page_kontak_form_loading'] ?? 'Mengirim Pesan...');
  $p_form_note   = fds_clean_string($global_c['page_kontak_form_note'] ?? 'Tim kami merespons pada hari kerja Senin–Sabtu. Data perusahaan Anda terlindungi.');
  if (preg_match('/(?:1\s*[×xX]\s*24|24\s*jam|48\s*jam)/i', $p_form_note)) {
    $p_form_note = 'Tim kami merespons pada hari kerja Senin–Sabtu. Data perusahaan Anda terlindungi.';
  }
  $p_opt_default     = fds_clean_string($global_c['page_kontak_opt_default'] ?? 'Konsultasi Umum, Demo Unit & Kebutuhan Korporasi');
  $p_field_srv_lbl   = fds_clean_string($global_c['page_kontak_field_service_lbl'] ?? 'Fokus Layanan / Model UAV');
  $p_field_fname_lbl = fds_clean_string($global_c['page_kontak_field_fname_lbl'] ?? 'Nama Depan');
  $p_field_fname_ph  = fds_clean_string($global_c['page_kontak_field_fname_ph'] ?? 'Nama depan');
  $p_field_lname_lbl = fds_clean_string($global_c['page_kontak_field_lname_lbl'] ?? 'Nama Belakang');
  $p_field_lname_ph  = fds_clean_string($global_c['page_kontak_field_lname_ph'] ?? 'Nama belakang');
  $p_field_comp_lbl  = fds_clean_string($global_c['page_kontak_field_comp_lbl'] ?? 'Perusahaan / Instansi');
  $p_field_comp_ph   = fds_clean_string($global_c['page_kontak_field_comp_ph'] ?? 'Nama perusahaan / instansi');
  $p_field_email_lbl = fds_clean_string($global_c['page_kontak_field_email_lbl'] ?? 'Email Bisnis');
  $p_field_email_ph  = fds_clean_string($global_c['page_kontak_field_email_ph'] ?? 'nama@perusahaan.com');
  $p_field_phone_lbl = fds_clean_string($global_c['page_kontak_field_phone_lbl'] ?? 'Nomor Telepon / WhatsApp');
  $p_field_phone_ph  = fds_clean_string($global_c['page_kontak_field_phone_ph'] ?? 'Nomor telepon / WhatsApp');
  $p_field_msg_lbl   = fds_clean_string($global_c['page_kontak_field_msg_lbl'] ?? 'Kebutuhan Operasional & Detail Pengadaan');
  $p_field_msg_ph    = fds_clean_string($global_c['page_kontak_field_msg_ph'] ?? 'Jelaskan kebutuhan pengadaan armada drone, jadwal demo terbang, atau spesifikasi teknis operasional Anda...');
  $p_map_title       = fds_clean_string($global_c['page_kontak_map_title'] ?? 'Lokasi Workshop Sleman');
  $p_map_btn         = fds_clean_string($global_c['page_kontak_map_btn'] ?? 'Buka di Google Maps');
  $p_map_link        = $global_c['page_kontak_map_link'] ?? 'https://maps.google.com/?q=Full+Drone+Solutions+Sleman+Yogyakarta';
  $p_guide_eyebrow   = fds_clean_string($global_c['page_kontak_guide_eyebrow'] ?? 'Dukungan Pengadaan Enterprise');
  $p_guide_title     = fds_clean_string($global_c['page_kontak_guide_title'] ?? 'Layanan Konsultasi & Pengadaan Korporasi FDS');
  $p_guide_body      = (string) ($global_c['page_kontak_guide_body'] ?? '');
  if ($p_guide_body === '' && function_exists('\App\fds_get_kontak_default_guide_html')) {
    $p_guide_body = \App\fds_get_kontak_default_guide_html();
  }

  // Opsi Layanan, Kategori & Armada Drone untuk Prefill Form Lead Engine
  $fds_clean_kontak = static function ($val) {
    $s = (string) $val;
    if (function_exists('fds_clean_string')) {
      return fds_clean_string($s);
    }
    $prev = '';
    while ($prev !== $s) {
      $prev = $s;
      $s = wp_specialchars_decode($s, ENT_QUOTES);
      $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    return $s;
  };
  $fds_service_options = [
    'kontak-inquiry' => $p_opt_default,
  ];
  if (function_exists('App\fds_get_layanan_service_options')) {
    foreach (\App\fds_get_layanan_service_options() as $opt_slug => $opt_lbl) {
      $fds_service_options[(string) $opt_slug] = $fds_clean_kontak($opt_lbl);
    }
  }
  $drone_kat_terms = get_terms([
    'taxonomy'   => 'kategori_drone',
    'hide_empty' => false,
    'orderby'    => 'term_id',
    'order'      => 'ASC',
  ]);
  if (!empty($drone_kat_terms) && !is_wp_error($drone_kat_terms)) {
    foreach ($drone_kat_terms as $dkt) {
      if (stripos((string) $dkt->slug, 'rebo') !== false) continue;
      $fds_service_options['kategori-' . $dkt->slug] = 'Lini Produk Kategori: ' . $fds_clean_kontak($dkt->name);
    }
  }
  $kontak_drone_posts = get_posts([
    'post_type'      => 'drone',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
  ]);
  if (!empty($kontak_drone_posts)) {
    if (function_exists('fds_sort_drone_posts')) {
      $kontak_drone_posts = fds_sort_drone_posts($kontak_drone_posts);
    }
    foreach ($kontak_drone_posts as $kd) {
      if (stripos((string) $kd->post_name, 'rebo') !== false || stripos((string) $kd->post_name, 'agrea') !== false || stripos((string) $kd->post_name, 'ferto-20') !== false) continue;
      $fds_service_options[(string) $kd->post_name] = 'Pengadaan Unit: ' . $fds_clean_kontak($kd->post_title);
    }
  }
  $raw_prefill_param = sanitize_key(wp_unslash((string) ($_GET['layanan'] ?? $_GET['sektor'] ?? $_GET['produk'] ?? $_GET['model'] ?? '')));
  $selected_service_slug = ($raw_prefill_param !== '' && isset($fds_service_options[$raw_prefill_param]))
    ? $raw_prefill_param
    : 'kontak-inquiry';
  $selected_service_label = $fds_clean_kontak($fds_service_options[$selected_service_slug] ?? $p_opt_default);
@endphp

<div id="contact-page" class="w-full bg-white font-sans">
  
  {{-- ========================================================== --}}
  {{-- 1. UNIFIED CONTACT HUB (BRAND TEAL WITH AERODYNAMIC VECTOR) --}}
  {{-- ========================================================== --}}
  {{-- 1. UNIFIED CONTACT HUB (BRAND TEAL WITH AERODYNAMIC VECTOR) --}}
  {{-- ========================================================== --}}
  <section class="relative bg-gradient-to-br from-[#13abb9] via-[#0f808a] to-[#0a555c] pt-24 pb-16 md:py-0 md:min-h-screen md:flex md:items-center overflow-hidden">
    
    {{-- Vector Background: Aerodynamic Flow Strata (Desain Resmi Sesuai Halaman Tentang Kami) --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
      <svg class="w-full h-full" viewBox="0 0 1440 800" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
        <defs>
          <linearGradient id="kontak_hero_base" x1="720" y1="0" x2="720" y2="800" gradientUnits="userSpaceOnUse">
            <stop stop-color="#13abb9"/>
            <stop offset="0.65" stop-color="#0f808a"/>
            <stop offset="1" stop-color="#0a555c"/>
          </linearGradient>

          <linearGradient id="kontak_flow_grad1" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#0a555c" stop-opacity="0.22"/>
            <stop offset="50%" stop-color="#0a555c" stop-opacity="0.10"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
          </linearGradient>

          <linearGradient id="kontak_flow_grad2" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#0f808a" stop-opacity="0.28"/>
            <stop offset="50%" stop-color="#0a555c" stop-opacity="0.14"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
          </linearGradient>

          <linearGradient id="kontak_flow_grad3" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#46deec" stop-opacity="0.34"/>
            <stop offset="50%" stop-color="#0a555c" stop-opacity="0.16"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
          </linearGradient>

          <linearGradient id="kontak_flow_grad4" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#13abb9" stop-opacity="0.40"/>
            <stop offset="50%" stop-color="#0f808a" stop-opacity="0.18"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
          </linearGradient>

          <linearGradient id="kontak_flow_grad5" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#18d6e7" stop-opacity="0.46"/>
            <stop offset="50%" stop-color="#46deec" stop-opacity="0.22"/>
            <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
          </linearGradient>
        </defs>

        <g>
          <rect width="1440" height="800" fill="url(#kontak_hero_base)"/>
          <path d="M-50,-50 C250,60 500,480 720,480 C940,480 1190,60 1490,-50 L1490,900 L-50,900 Z" fill="url(#kontak_flow_grad1)"/>
          <path d="M-50,110 C250,210 500,550 720,550 C940,550 1190,210 1490,110 L1490,900 L-50,900 Z" fill="url(#kontak_flow_grad2)"/>
          <path d="M-50,290 C250,370 500,610 720,610 C940,610 1190,370 1490,290 L1490,900 L-50,900 Z" fill="url(#kontak_flow_grad3)"/>
          <path d="M-50,470 C250,530 500,680 720,680 C940,680 1190,530 1490,470 L1490,900 L-50,900 Z" fill="url(#kontak_flow_grad4)"/>
          <path d="M-50,650 C250,680 500,760 720,760 C940,760 1190,680 1490,650 L1490,900 L-50,900 Z" fill="url(#kontak_flow_grad5)"/>
        </g>
      </svg>
    </div>

    <div class="max-w-[1400px] w-full mx-auto px-6 lg:px-12 relative z-10 py-6 md:py-10">
      {{-- Main Architectural 2-Column Split --}}
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-center">

        {{-- LEFT COLUMN: Direct Typography & Touchpoints (Zero Card Containers) --}}
        <div class="lg:col-span-5 text-white fds-hero-entrance">
          
          {{-- Eyebrow --}}
          @if(!empty($p_badge))
          <p class="text-[12.5px] font-semibold text-white/80 tracking-tight mb-2 fds-hero-entrance fds-hero-delay-1">
            {{ $p_badge }}
          </p>
          @endif

          {{-- Title --}}
          <h1 class="text-[36px] sm:text-[44px] lg:text-[48px] font-semibold tracking-[-0.035em] text-white leading-[1.08] mb-3 sm:mb-4 fds-hero-entrance fds-hero-delay-2">
            {{ $p_title }}
          </h1>

          {{-- Description --}}
          <p class="text-[15px] sm:text-[16px] text-white/85 leading-relaxed mb-6 sm:mb-8 max-w-[440px] fds-hero-entrance fds-hero-delay-3">
            {!! nl2br(e(preg_replace('/(?<!\s)\R/u', " \n", (string) $p_desc))) !!}
          </p>

          {{-- Direct Contact Information (Clean, zero boxes) --}}
          <div class="space-y-4 pt-1">
            
            {{-- WhatsApp & Phone --}}
            <div>
              <p class="text-[11.5px] font-semibold text-white/70 mb-1">{{ $p_lbl_phone }}</p>
              <div class="flex flex-wrap items-center gap-3">
                <a href="{!! esc_url($c_wa_link) !!}" target="_blank" rel="noopener noreferrer"
                   class="text-[20px] sm:text-[22px] font-semibold text-white hover:text-white/80 transition-colors tracking-tight">
                  {{ $c_phone }}
                </a>
                <a href="{!! esc_url($c_wa_link) !!}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 bg-[#25D366] hover:bg-[#20bd5a] active:scale-[0.98] text-white text-[12px] font-semibold px-3 py-1 rounded-full shadow-xs transition-all duration-150">
                  <svg class="w-4 h-4 fill-current flex-shrink-0" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                  <span>{{ $p_wa_btn }}</span>
                </a>
              </div>
            </div>

            {{-- Email --}}
            <div>
              <p class="text-[11.5px] font-semibold text-white/70 mb-0.5">{{ $p_lbl_email }}</p>
              <a href="mailto:{{ $c_email }}" 
                 class="text-[15px] sm:text-[16px] font-medium text-white hover:text-white/80 transition-colors hover:underline break-all">
                {{ $c_email }}
              </a>
            </div>

            {{-- Jam Operasional --}}
            @if(!empty($p_hours_wd) || !empty($p_hours_sat))
            <div>
              <p class="text-[11.5px] font-semibold text-white/70 mb-0.5">{{ $p_lbl_hours }}</p>
              @if(!empty($p_hours_wd))
              <p class="text-[14px] text-white/90 leading-tight">{{ $p_hours_wd }}</p>
              @endif
              @if(!empty($p_hours_sat))
              <p class="text-[13px] text-white/75 leading-tight mt-1">{{ $p_hours_sat }}</p>
              @endif
            </div>
            @endif

            {{-- Workshop Link --}}
            @if(!empty($c_maps))
            <div class="pt-1">
              <a href="#lokasi-workshop" 
                 class="inline-block text-[13.5px] font-medium text-white/90 hover:text-white hover:underline transition-colors">
                {{ $p_map_jump }}
              </a>
            </div>
            @endif

          </div>

        </div>

        {{-- RIGHT COLUMN: Compact Enterprise Form Card (Col 7) --}}
        <div id="hubungi-form" class="lg:col-span-7 scroll-mt-24 fds-hero-entrance fds-hero-delay-3">
          <div class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-7 lg:p-8 border border-black/[0.06] shadow-[0_4px_20px_rgba(0,0,0,0.06)] text-[#1d1d1f]">
            
            <div class="mb-5">
              <h2 class="text-[20px] sm:text-[22px] font-semibold text-[#1d1d1f] tracking-tight mb-1">
                {{ $p_form_title }}
              </h2>
              <p class="text-[13px] text-[#6e6e73] leading-relaxed">
                {{ $p_form_desc }}
              </p>
            </div>

            <form id="fds-inquiry-form" class="space-y-3" onsubmit="fdsSubmitInquiry(event)">
              @php
                wp_nonce_field('fds_inquiry_nonce', 'fds_inquiry_nonce_val');
                $_fds_kontak_track = function_exists('App\fds_get_tracking_config') ? \App\fds_get_tracking_config() : [];
                $_fds_kontak_ts_key = $_fds_kontak_track['turnstile_site_key'] ?? '';
                $_fds_kontak_ts_sec = $_fds_kontak_track['turnstile_secret_key'] ?? '';
                $_fds_kontak_use_ts = ($_fds_kontak_ts_key !== '' && $_fds_kontak_ts_sec !== '');
              @endphp
              <input type="hidden" name="service_label" id="fds-service-label" value="{{ $selected_service_label }}">

              {{-- Service / Product Focus (Auto-Prefill via ?layanan= / ?sektor= / ?produk=) --}}
              <div>
                <label for="fds-service-select" class="block text-[11.5px] font-medium text-[#475569] mb-1">{{ $p_field_srv_lbl }} <span class="text-red-500">*</span></label>
                <select id="fds-service-select" name="service_slug" required
                  class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] outline-none transition-all cursor-pointer">
                  @foreach($fds_service_options as $opt_val => $opt_text)
                    <option value="{{ $opt_val }}" @selected($selected_service_slug === $opt_val)>{{ $opt_text }}</option>
                  @endforeach
                </select>
              </div>

              {{-- Anti-Bot Honeypot Trap --}}
              <div style="display:none !important; position:absolute !important; left:-9999px !important;" aria-hidden="true">
                <label for="fds_website_url_hp">Do not fill this field</label>
                <input type="text" id="fds_website_url_hp" name="website_url_hp" value="" tabindex="-1" autocomplete="off">
              </div>

              {{-- Names --}}
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block text-[11.5px] font-medium text-[#475569] mb-1">{{ $p_field_fname_lbl }} <span class="text-red-500">*</span></label>
                  <input type="text" name="first_name" required maxlength="60" placeholder="{{ $p_field_fname_ph }}"
                    class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all">
                </div>
                <div>
                  <label class="block text-[11.5px] font-medium text-[#475569] mb-1">{{ $p_field_lname_lbl }} <span class="text-red-500">*</span></label>
                  <input type="text" name="last_name" required maxlength="60" placeholder="{{ $p_field_lname_ph }}"
                    class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all">
                </div>
              </div>

              {{-- Company & Email --}}
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block text-[11.5px] font-medium text-[#475569] mb-1">{{ $p_field_comp_lbl }} <span class="text-red-500">*</span></label>
                  <input type="text" name="company" required maxlength="100" placeholder="{{ $p_field_comp_ph }}"
                    class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all">
                </div>
                <div>
                  <label class="block text-[11.5px] font-medium text-[#475569] mb-1">{{ $p_field_email_lbl }} <span class="text-red-500">*</span></label>
                  <input type="email" name="email" required maxlength="100" placeholder="{{ $p_field_email_ph }}"
                    class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all">
                </div>
              </div>

              {{-- Phone --}}
              <div>
                <label class="block text-[11.5px] font-medium text-[#475569] mb-1">{{ $p_field_phone_lbl }} <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" required maxlength="30" placeholder="{{ $p_field_phone_ph }}"
                  class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all">
              </div>

              {{-- Message --}}
              <div>
                <label class="block text-[11.5px] font-medium text-[#475569] mb-1">{{ $p_field_msg_lbl }} <span class="text-red-500">*</span></label>
                <textarea name="message" rows="3" required maxlength="3000" placeholder="{{ $p_field_msg_ph }}"
                  class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all resize-none"></textarea>
              </div>

              @if($_fds_kontak_use_ts)
                <div class="pt-1">
                  <div class="cf-turnstile" data-sitekey="{{ $_fds_kontak_ts_key }}" data-theme="light"></div>
                </div>
                @once
                  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                @endonce
              @endif

              {{-- Action row --}}
              <div class="pt-2">
                <button type="submit" id="fds-inquiry-btn"
                  class="bg-[#0f808a] hover:bg-[#0a555c] active:scale-[0.98] text-white font-semibold text-[13.5px] px-6 py-2.5 rounded-full transition-all duration-200 shadow-sm cursor-pointer inline-flex items-center justify-center gap-2 leading-none min-h-[42px] group">
                  <span id="fds-inquiry-btn-text">{{ $p_form_btn }}</span>
                  <span id="fds-inquiry-btn-arrow" class="inline-flex items-center justify-center transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M6 6h12v12"/>
                      <path d="M6 18 18 6"/>
                    </svg>
                  </span>
                </button>
                @if(!empty($p_form_note))
                <div class="flex items-center gap-1.5 text-[11.5px] text-[#64748b] mt-3">
                  <svg class="w-3.5 h-3.5 text-[#0f808a] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                  <span>{{ $p_form_note }}</span>
                </div>
                @endif
              </div>

            </form>

          </div>
        </div>

      </div>

    </div>
  </section>


  {{-- ========================================================== --}}
  {{-- 2. WORKSHOP & TESTING CENTER MAP (WHITE BACKGROUND)        --}}
  {{-- ========================================================== --}}
  @if(!empty($c_maps))
  <section id="lokasi-workshop" class="scroll-mt-20 bg-white py-16 sm:py-24">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      
      {{-- Minimalist Header Bar (No nested card header) --}}
      <x-reveal class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
        <div class="flex-1 min-w-0 pr-0 sm:pr-6 lg:pr-8">
          <p class="text-[12px] font-semibold text-[#0f808a] tracking-wide mb-1">
            {{ !empty($p_panel_label) ? $p_panel_label : 'Fasilitas Produksi & Workshop Resmi' }}
          </p>
          <h2 class="text-[28px] sm:text-[36px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-snug">
            {{ $p_map_title }}
          </h2>
          <p class="text-[14px] sm:text-[14.5px] text-[#515154] mt-1.5 leading-relaxed">
            {{ $c_address }}
          </p>
          @if(!empty($p_panel_desc))
          <p class="text-[13px] text-[#64748b] mt-2 leading-relaxed max-w-[720px]">
            {{ $p_panel_desc }}
          </p>
          @endif
        </div>

        @if(!empty($p_map_link))
        <div class="flex-shrink-0">
          <a href="{!! esc_url($p_map_link) !!}" target="_blank" rel="noopener noreferrer"
             class="inline-flex items-center gap-2 text-[13.5px] font-semibold text-[#0f808a] hover:text-white hover:bg-[#0f808a] bg-[#0f808a]/10 border border-[#0f808a]/20 px-5.5 py-2.5 rounded-full transition-all shadow-xs min-h-[42px] whitespace-nowrap leading-none group">
            <span>{{ $p_map_btn }}</span>
            <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 6h12v12"/>
              <path d="M6 18 18 6"/>
            </svg>
          </a>
        </div>
        @endif
      </x-reveal>

      {{-- Large Rounded Map Viewport with Smooth Skeleton Loader --}}
      <x-reveal :delay="120" class="w-full rounded-3xl overflow-hidden border border-black/[0.08] shadow-sm relative bg-[#eef2f6]" style="height: 440px; max-height: 520px;">
        
        {{-- Map Skeleton Loader (Clean Grayscale Pulse) --}}
        <div id="map-loader-kontak" class="absolute inset-0 bg-slate-200 animate-pulse z-10 transition-opacity duration-500 pointer-events-none"></div>

        <iframe 
          src="{!! esc_url($c_maps) !!}" 
          width="100%" 
          height="100%" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="strict-origin-when-cross-origin"
          title="{{ $p_map_title }}"
          onload="var l = document.getElementById('map-loader-kontak'); if(l){ l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(function(){ l.remove(); }, 500); }">
        </iframe>
      </x-reveal>

    </div>
  </section>
  @endif

  {{-- ========================================================== --}}
  {{-- 3. PANDUAN KONSULTASI & PENGADAAN B2B (EDITORIAL SSR)      --}}
  {{-- ========================================================== --}}
  <section class="bg-[#f5f5f7] py-16 sm:py-24 border-t border-black/[0.06]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      <div class="max-w-4xl">
        @if(!empty($p_guide_eyebrow))
        <p class="text-[12.5px] font-semibold text-[#0f808a] tracking-wide mb-2">
          {{ $p_guide_eyebrow }}
        </p>
        @endif
        @if(!empty($p_guide_title))
        <h2 class="text-[28px] sm:text-[36px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.15] mb-6">
          {{ $p_guide_title }}
        </h2>
        @endif

        <div class="fds-drone-guide">
          {!! wp_kses_post($p_guide_body) !!}
        </div>
      </div>
    </div>
  </section>

</div>

{{-- Global Floating Toast Notification --}}
<x-snackbar />

{{-- Form Submission & URL Prefill Handler --}}
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
      var matched = Array.prototype.find.call(sel.options, function(o) {
        return o.value.toLowerCase() === requested;
      });
      if (matched) {
        sel.value = matched.value;
      }
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

  var sel       = document.getElementById('fds-service-select');
  var lblInput  = document.getElementById('fds-service-label');
  var srvSlug   = sel ? sel.value : 'kontak-inquiry';
  var srvLabel  = (sel && sel.selectedIndex >= 0 && sel.options[sel.selectedIndex])
    ? sel.options[sel.selectedIndex].text.trim()
    : {!! wp_json_encode($p_opt_default) !!};
  if (lblInput) lblInput.value = srvLabel;

  var firstName = (form.querySelector('[name="first_name"]') || {}).value || '';
  var lastName  = (form.querySelector('[name="last_name"]') || {}).value || '';
  var company   = (form.querySelector('[name="company"]') || {}).value || '';
  var email     = (form.querySelector('[name="email"]') || {}).value || '';
  var phone     = (form.querySelector('[name="phone"]') || {}).value || '';
  var message   = (form.querySelector('[name="message"]') || {}).value || '';

  if (!firstName.trim() || !lastName.trim() || !company.trim() || !email.trim() || !phone.trim() || !message.trim()) {
    if (typeof fdsShowSnackbar === 'function') {
      fdsShowSnackbar(false, 'Formulir Belum Lengkap', 'Semua kolom formulir wajib diisi sebelum mengirim pesan.');
    } else {
      alert('Semua kolom formulir wajib diisi.');
    }
    return;
  }

  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.trim())) {
    if (typeof fdsShowSnackbar === 'function') {
      fdsShowSnackbar(false, 'Format Email Tidak Valid', 'Silakan masukkan alamat email yang benar (contoh: nama@perusahaan.com).');
    } else {
      alert('Format email tidak valid.');
    }
    return;
  }

  var tsInput = form.querySelector('[name="cf-turnstile-response"]');
  if (form.querySelector('.cf-turnstile') && (!tsInput || !tsInput.value)) {
    if (typeof fdsShowSnackbar === 'function') {
      fdsShowSnackbar(false, 'Verifikasi Keamanan', 'Silakan selesaikan verifikasi keamanan Cloudflare Turnstile sebelum mengirim formulir.');
    } else {
      alert('Silakan selesaikan verifikasi keamanan Cloudflare Turnstile terlebih dahulu.');
    }
    return;
  }

  if (typeof window.fdsPopulateAttribution === 'function') {
    window.fdsPopulateAttribution(form);
  }

  var formData = new FormData(form);
  formData.append('action', 'fds_submit_inquiry');
  formData.append('nonce', document.getElementById('fds_inquiry_nonce_val').value);

  btn.disabled = true;
  btn.classList.add('opacity-70', 'cursor-not-allowed');
  btnText.textContent = {!! wp_json_encode($p_form_loading) !!};

  fetch({!! wp_json_encode(admin_url('admin-ajax.php')) !!}, {
    method: 'POST',
    body: formData
  })
  .then(function(res) { return res.json(); })
  .then(function(data) {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = {!! wp_json_encode($p_form_btn) !!};

    if (data.success) {
      var leadRef = (data.data && data.data.lead_ref) ? data.data.lead_ref : '';
      var respLabel = (data.data && data.data.service_label) ? data.data.service_label : srvLabel;
      try {
        sessionStorage.setItem('fds_last_lead_summary', JSON.stringify({
          lead_ref: leadRef,
          service_label: respLabel,
          company: company.trim()
        }));
      } catch (err) {}

      if (typeof window.fdsTrackEvent === 'function') {
        window.fdsTrackEvent('generate_lead', {
          service_slug: srvSlug,
          service_label: respLabel,
          lead_ref: leadRef
        });
      }
      if (typeof fdsShowSnackbar === 'function') {
        fdsShowSnackbar(true, 'Pesan Berhasil Terkirim', (data.data && data.data.message) ? data.data.message : 'Pesan Anda telah berhasil disimpan di sistem kami.');
      }
      form.reset();
      if (window.turnstile && typeof window.turnstile.reset === 'function') {
        try { window.turnstile.reset(); } catch (err) {}
      }
      if (data.data && data.data.redirect_url) {
        setTimeout(function() {
          window.location.href = data.data.redirect_url;
        }, 450);
      }
    } else {
      if (typeof fdsShowSnackbar === 'function') {
        fdsShowSnackbar(false, 'Gagal Mengirim Pesan', (data.data && data.data.message) ? data.data.message : 'Terjadi kesalahan. Silakan periksa kembali formulir Anda.');
      } else {
        alert('Gagal mengirim pesan: ' + ((data.data && data.data.message) || 'Terjadi kesalahan.'));
      }
    }
  })
  .catch(function(err) {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = {!! wp_json_encode($p_form_btn) !!};
    if (typeof fdsShowSnackbar === 'function') {
      fdsShowSnackbar(false, 'Gangguan Jaringan', 'Gagal terhubung ke server. Silakan coba lagi atau hubungi kami via WhatsApp.');
    } else {
      alert('Gangguan jaringan. Silakan hubungi kami via WhatsApp.');
    }
  });
}
</script>

@endsection
