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

  // Deep decode string helper
  $c_phone   = fds_clean_string($c_phone);
  $c_email   = fds_clean_string($c_email);
  $c_address = fds_clean_string($c_address);
  $c_company = fds_clean_string($global_c['company_name'] ?? 'PT Karya Solusi Angkasa');

  // Kustomisasi Narasi Halaman Kontak Dinamis
  $p_badge       = fds_clean_string($global_c['page_kontak_badge'] ?? 'Pusat Kontak');
  $p_title       = fds_clean_string($global_c['page_kontak_title'] ?? 'Hubungi Kami.');
  $p_desc        = fds_clean_string($global_c['page_kontak_desc'] ?? 'Diskusikan pengadaan armada drone, jadwal demo terbang, atau kebutuhan teknis operasional Anda bersama tim Full Drone Solutions.');
  $p_panel_label = fds_clean_string($global_c['page_kontak_panel_label'] ?? 'Kantor & Workshop Resmi');
  $p_panel_desc  = fds_clean_string($global_c['page_kontak_panel_desc'] ?? 'Produsen UAV nasional bersertifikasi TKDN 60,74%, SNI 9199:2023, dan ISO 9001:2015 berlokasi di Sleman, Yogyakarta.');
  $p_hours_wd    = fds_clean_string($global_c['page_kontak_hours_wd'] ?? 'Senin – Jumat: 08:30 – 17:00 WIB');
  $p_hours_sat   = fds_clean_string($global_c['page_kontak_hours_sat'] ?? 'Sabtu: 08:30 – 13:00 WIB');
  $p_wa_btn      = fds_clean_string($global_c['page_kontak_wa_btn'] ?? 'Chat WhatsApp Kami');
  $p_form_title  = fds_clean_string($global_c['page_kontak_form_title'] ?? 'Kirim Pesan atau Permintaan Penawaran');
  $p_form_desc   = fds_clean_string($global_c['page_kontak_form_desc'] ?? 'Silakan lengkapi formulir di bawah ini, tim representatif kami siap membantu kebutuhan Anda:');
  $p_form_btn    = fds_clean_string($global_c['page_kontak_form_btn'] ?? 'Kirim Pesan');
  $p_form_note   = fds_clean_string($global_c['page_kontak_form_note'] ?? 'Respons 1×24 jam kerja. Data terjamin aman.');
  $p_map_title   = fds_clean_string($global_c['page_kontak_map_title'] ?? 'Lokasi Workshop Sleman');
  $p_map_btn     = fds_clean_string($global_c['page_kontak_map_btn'] ?? 'Buka di Google Maps');
  $p_map_link    = $global_c['page_kontak_map_link'] ?? 'https://maps.google.com/?q=Full+Drone+Solutions+Condongcatur+Sleman';
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
            {!! esc_html($p_badge) !!}
          </p>
          @endif

          {{-- Title --}}
          <h1 class="text-[36px] sm:text-[44px] lg:text-[48px] font-semibold tracking-[-0.035em] text-white leading-[1.08] mb-3 sm:mb-4 fds-hero-entrance fds-hero-delay-2">
            {!! esc_html($p_title) !!}
          </h1>

          {{-- Description --}}
          <p class="text-[15px] sm:text-[16px] text-white/85 leading-relaxed mb-6 sm:mb-8 max-w-[440px] fds-hero-entrance fds-hero-delay-3">
            {!! nl2br(esc_html($p_desc)) !!}
          </p>

          {{-- Direct Contact Information (Clean, zero boxes) --}}
          <div class="space-y-4 pt-1">
            
            {{-- WhatsApp & Phone --}}
            <div>
              <p class="text-[11.5px] font-semibold text-white/70 mb-1">Telepon &amp; WhatsApp</p>
              <div class="flex flex-wrap items-center gap-3">
                <a href="{{ esc_url($c_wa_link) }}" target="_blank" rel="noopener noreferrer"
                   class="text-[20px] sm:text-[22px] font-semibold text-white hover:text-white/80 transition-colors tracking-tight">
                  {!! esc_html($c_phone) !!}
                </a>
                <a href="{{ esc_url($c_wa_link) }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 bg-[#25D366] hover:bg-[#20bd5a] active:scale-[0.98] text-white text-[12px] font-semibold px-3 py-1 rounded-full shadow-xs transition-all duration-150">
                  <svg class="w-4 h-4 fill-current flex-shrink-0" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                  <span>{!! esc_html($p_wa_btn) !!}</span>
                </a>
              </div>
            </div>

            {{-- Email --}}
            <div>
              <p class="text-[11.5px] font-semibold text-white/70 mb-0.5">Email Resmi</p>
              <a href="mailto:{{ esc_attr($c_email) }}" 
                 class="text-[15px] sm:text-[16px] font-medium text-white hover:text-white/80 transition-colors hover:underline break-all">
                {!! esc_html($c_email) !!}
              </a>
            </div>

            {{-- Jam Operasional --}}
            @if(!empty($p_hours_wd) || !empty($p_hours_sat))
            <div>
              <p class="text-[11.5px] font-semibold text-white/70 mb-0.5">Jam Operasional</p>
              @if(!empty($p_hours_wd))
              <p class="text-[14px] text-white/90 leading-tight">{!! esc_html($p_hours_wd) !!}</p>
              @endif
              @if(!empty($p_hours_sat))
              <p class="text-[13px] text-white/75 leading-tight mt-1">{!! esc_html($p_hours_sat) !!}</p>
              @endif
            </div>
            @endif

            {{-- Workshop Link --}}
            @if(!empty($c_maps))
            <div class="pt-1">
              <a href="#lokasi-workshop" 
                 class="inline-block text-[13.5px] font-medium text-white/90 hover:text-white hover:underline transition-colors">
                Lihat titik lokasi &amp; workshop resmi &darr;
              </a>
            </div>
            @endif

          </div>

        </div>

        {{-- RIGHT COLUMN: Compact Enterprise Form Card (Col 7) --}}
        <div class="lg:col-span-7 fds-hero-entrance fds-hero-delay-3">
          <div class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-7 lg:p-8 border border-black/[0.06] shadow-[0_4px_20px_rgba(0,0,0,0.06)] text-[#1d1d1f]">
            
            <div class="mb-5">
              <h2 class="text-[20px] sm:text-[22px] font-semibold text-[#1d1d1f] tracking-tight mb-1">
                {!! esc_html($p_form_title) !!}
              </h2>
              <p class="text-[13px] text-[#6e6e73] leading-relaxed">
                {!! esc_html($p_form_desc) !!}
              </p>
            </div>

            <form id="fds-inquiry-form" class="space-y-3" onsubmit="fdsSubmitInquiry(event)">
              @php wp_nonce_field('fds_inquiry_nonce', 'fds_inquiry_nonce_val'); @endphp

              {{-- Anti-Bot Honeypot Trap --}}
              <div style="display:none !important; position:absolute !important; left:-9999px !important;" aria-hidden="true">
                <label for="fds_website_url_hp">Do not fill this field</label>
                <input type="text" id="fds_website_url_hp" name="website_url_hp" value="" tabindex="-1" autocomplete="off">
              </div>

              {{-- Names --}}
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block text-[11.5px] font-medium text-[#475569] mb-1">Nama Depan <span class="text-red-500">*</span></label>
                  <input type="text" name="first_name" required maxlength="60" placeholder="Nama depan"
                    class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all">
                </div>
                <div>
                  <label class="block text-[11.5px] font-medium text-[#475569] mb-1">Nama Belakang <span class="text-red-500">*</span></label>
                  <input type="text" name="last_name" required maxlength="60" placeholder="Nama belakang"
                    class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all">
                </div>
              </div>

              {{-- Company & Email --}}
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block text-[11.5px] font-medium text-[#475569] mb-1">Perusahaan / Instansi <span class="text-red-500">*</span></label>
                  <input type="text" name="company" required maxlength="100" placeholder="Nama perusahaan / instansi"
                    class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all">
                </div>
                <div>
                  <label class="block text-[11.5px] font-medium text-[#475569] mb-1">Email Bisnis <span class="text-red-500">*</span></label>
                  <input type="email" name="email" required maxlength="100" placeholder="nama@perusahaan.com"
                    class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all">
                </div>
              </div>

              {{-- Phone --}}
              <div>
                <label class="block text-[11.5px] font-medium text-[#475569] mb-1">Nomor Telepon / WhatsApp <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" required maxlength="30" placeholder="Nomor telepon / WhatsApp"
                  class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all">
              </div>

              {{-- Message --}}
              <div>
                <label class="block text-[11.5px] font-medium text-[#475569] mb-1">Kebutuhan Operasional &amp; Detail Pengadaan <span class="text-red-500">*</span></label>
                <textarea name="message" rows="3" required maxlength="3000" placeholder="Jelaskan kebutuhan pengadaan armada drone, jadwal demo terbang, atau spesifikasi teknis operasional Anda..."
                  class="w-full bg-[#f8fafc] border border-black/[0.08] focus:border-[#0f808a] focus:bg-white focus:ring-2 focus:ring-[#0f808a]/10 rounded-lg px-3.5 py-2 sm:py-2.5 text-[13.5px] text-[#1d1d1f] placeholder-[#94a3b8] outline-none transition-all resize-none"></textarea>
              </div>

              {{-- Action row --}}
              <div class="pt-2">
                <button type="submit" id="fds-inquiry-btn"
                  class="bg-[#0f808a] hover:bg-[#0a555c] active:scale-[0.98] text-white font-semibold text-[13.5px] px-6 py-2.5 rounded-full transition-all duration-200 shadow-sm cursor-pointer inline-flex items-center justify-center gap-2 leading-none min-h-[42px] group">
                  <span id="fds-inquiry-btn-text">{!! esc_html($p_form_btn) !!}</span>
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
                  <span>{!! esc_html($p_form_note) !!}</span>
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
            {!! esc_html(!empty($p_panel_label) ? $p_panel_label : 'Fasilitas Produksi & Workshop Resmi') !!}
          </p>
          <h2 class="text-[28px] sm:text-[36px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-snug">
            {!! esc_html($p_map_title) !!}
          </h2>
          <p class="text-[14px] sm:text-[14.5px] text-[#515154] mt-1.5 leading-relaxed">
            {!! esc_html($c_address) !!}
          </p>
          @if(!empty($p_panel_desc))
          <p class="text-[13px] text-[#64748b] mt-2 leading-relaxed max-w-[720px]">
            {!! esc_html($p_panel_desc) !!}
          </p>
          @endif
        </div>

        @if(!empty($p_map_link))
        <div class="flex-shrink-0">
          <a href="{{ esc_url($p_map_link) }}" target="_blank" rel="noopener noreferrer"
             class="inline-flex items-center gap-2 text-[13.5px] font-semibold text-[#0f808a] hover:text-white hover:bg-[#0f808a] bg-[#0f808a]/10 border border-[#0f808a]/20 px-5.5 py-2.5 rounded-full transition-all shadow-xs min-h-[42px] whitespace-nowrap leading-none group">
            <span>{!! esc_html($p_map_btn) !!}</span>
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
          src="{{ esc_url($c_maps) }}" 
          width="100%" 
          height="100%" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="strict-origin-when-cross-origin"
          title="{{ esc_attr($p_map_title) }}"
          onload="var l = document.getElementById('map-loader-kontak'); if(l){ l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(function(){ l.remove(); }, 500); }">
        </iframe>
      </x-reveal>

    </div>
  </section>
  @endif

</div>

{{-- Global Floating Toast Notification --}}
<x-snackbar />

{{-- Form Submission AJAX Handler --}}
<script>
function fdsSubmitInquiry(e) {
  e.preventDefault();
  var form = document.getElementById('fds-inquiry-form');
  var btn = document.getElementById('fds-inquiry-btn');
  var btnText = document.getElementById('fds-inquiry-btn-text');

  if (!form || !btn) return;

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
    btnText.textContent = '{!! esc_js($p_form_btn) !!}';

    if (data.success) {
      if (typeof fdsShowSnackbar === 'function') {
        fdsShowSnackbar(true, 'Pesan Berhasil Terkirim', data.data.message || 'Pesan Anda telah berhasil disimpan di sistem kami.');
      } else {
        alert('Pesan berhasil terkirim!');
      }
      form.reset();
    } else {
      if (typeof fdsShowSnackbar === 'function') {
        fdsShowSnackbar(false, 'Gagal Mengirim Pesan', data.data.message || 'Terjadi kesalahan. Silakan periksa kembali formulir Anda.');
      } else {
        alert('Gagal mengirim pesan: ' + (data.data.message || 'Terjadi kesalahan.'));
      }
    }
  })
  .catch(function(err) {
    btn.disabled = false;
    btn.classList.remove('opacity-70', 'cursor-not-allowed');
    btnText.textContent = '{!! esc_js($p_form_btn) !!}';
    if (typeof fdsShowSnackbar === 'function') {
      fdsShowSnackbar(false, 'Gangguan Jaringan', 'Gagal terhubung ke server. Silakan coba lagi atau hubungi kami via WhatsApp.');
    } else {
      alert('Gangguan jaringan. Silakan hubungi kami via WhatsApp.');
    }
  });
}
</script>

@endsection
