{{--
  Template Name: Terima Kasih Inquiry
--}}

@extends('layouts.app')

@section('content')

@php
  $clean_ty    = static function ($v): string {
      $str = (string) $v;
      return function_exists('fds_clean_string') ? fds_clean_string($str) : html_entity_decode(wp_specialchars_decode($str, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
  };
  $ref_param   = isset($_GET['ref']) ? $clean_ty(sanitize_text_field(wp_unslash((string) $_GET['ref']))) : '';
  $ui          = function_exists('App\fds_get_layanan_ui_texts') ? \App\fds_get_layanan_ui_texts() : [];
  if (function_exists('fds_deep_decode') && is_array($ui)) {
      $ui = fds_deep_decode($ui);
  }
  $gc          = function_exists('App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_wa_link   = $gc['wa_link'] ?? 'https://wa.me/628112748882';
  $c_email     = $clean_ty($gc['email'] ?? 'marketing@fulldronesolutions.com');
  $c_phone     = $clean_ty($gc['phone'] ?? '+62 8112 748 882');
  $wa_text     = 'Halo tim Full Drone Solutions, kami baru saja mengirimkan formulir permintaan penawaran melalui website'
               . ($ref_param !== '' ? ' dengan nomor referensi ' . $ref_param : '')
               . '. Mohon dibantu koordinasi lanjutan.';
  $wa_sep      = strpos($c_wa_link, '?') === false ? '?' : '&';
  $wa_full_url = $c_wa_link . $wa_sep . 'text=' . rawurlencode($wa_text);
@endphp

<section class="relative pt-32 sm:pt-40 pb-16 sm:pb-20 bg-gradient-to-br from-[#0f808a] via-[#0a555c] to-[#052b2e] text-white overflow-hidden">
  {{-- Brand Teal Fill-Only Aerodynamic Wave Vector Background (Zero Stroke — Rule 7) --}}
  <div class="absolute inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
    <svg class="w-full h-full" viewBox="0 0 1440 420" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
      <defs>
        <linearGradient id="ty_wave_g1" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="#13abb9" stop-opacity="0.28"/>
          <stop offset="55%" stop-color="#0f808a" stop-opacity="0.14"/>
          <stop offset="100%" stop-color="#052b2e" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="ty_wave_g2" x1="15%" y1="0%" x2="90%" y2="100%">
          <stop offset="0%" stop-color="#46deec" stop-opacity="0.24"/>
          <stop offset="55%" stop-color="#0f808a" stop-opacity="0.12"/>
          <stop offset="100%" stop-color="#052b2e" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="ty_wave_g3" x1="0%" y1="100%" x2="100%" y2="0%">
          <stop offset="0%" stop-color="#18d6e7" stop-opacity="0.22"/>
          <stop offset="50%" stop-color="#0f808a" stop-opacity="0.10"/>
          <stop offset="100%" stop-color="#052b2e" stop-opacity="0"/>
        </linearGradient>
      </defs>
      <path d="M-60,-20 C260,70 520,240 760,220 C1020,198 1240,40 1500,-10 L1500,460 L-60,460 Z" fill="url(#ty_wave_g1)"/>
      <path d="M-60,110 C280,175 540,305 780,285 C1030,262 1240,120 1500,85 L1500,460 L-60,460 Z" fill="url(#ty_wave_g2)"/>
      <path d="M-60,235 C300,275 560,365 810,348 C1050,332 1260,225 1500,205 L1500,460 L-60,460 Z" fill="url(#ty_wave_g3)"/>
    </svg>
  </div>

  <div class="max-w-[1000px] mx-auto px-6 lg:px-12 relative z-10 fds-hero-entrance fds-hero-delay-1">
    <div class="inline-flex items-center gap-3 mb-5">
      <span class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center flex-shrink-0" aria-hidden="true">
        <svg class="w-5 h-5 text-[#75e6f0]" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
      </span>
      <p class="text-[14px] font-semibold text-[#a3eef5]">
        {{ $ui['ty_badge'] ?? 'Permintaan Penawaran Tercatat di Sistem FDS' }}
      </p>
    </div>

    <h1 class="text-[38px] sm:text-[50px] lg:text-[58px] font-bold tracking-[-0.03em] leading-[1.06] text-white">
      {{ $ui['ty_h1'] ?? 'Terima Kasih, Permintaan Konsultasi Anda Telah Kami Terima' }}
    </h1>

    <p class="mt-4 text-[16px] sm:text-[17.5px] text-white/85 leading-relaxed max-w-[760px]">
      @php
        $ty_subtitle_raw = (string) ($ui['ty_subtitle'] ?? 'Tim Full Drone Solutions (PT Karya Solusi Angkasa) akan meninjau kebutuhan sektor, detail lokasi, serta rincian kebutuhan yang Anda kirimkan untuk menyiapkan diskusi teknis dan estimasi penawaran yang relevan.');
        $ty_subtitle_clean = preg_replace('/serta\s+lampiran\s+poligon\s+AOI\s+yang\s+Anda\s+kirimkan/i', 'serta rincian kebutuhan yang Anda kirimkan', $ty_subtitle_raw);
      @endphp
      {{ $ty_subtitle_clean }}
    </p>

    <div id="fds-ty-ref-wrap" class="mt-7 {{ $ref_param === '' ? 'hidden' : '' }}">
      <p class="text-[13px] text-white/75 mb-1">{{ $ui['ty_ref_label'] ?? 'Nomor Referensi Inquiry' }}</p>
      <p id="fds-ty-ref-value" class="text-[20px] sm:text-[24px] font-semibold font-mono text-[#a3eef5] tracking-tight">
        {{ $ref_param }}
      </p>
    </div>
  </div>
</section>

<section class="bg-white py-16 sm:py-24">
  <div class="max-w-[1000px] mx-auto px-6 lg:px-12 space-y-10">

    {{-- Ringkasan Non-PII dari SessionStorage (hanya tampil jika tersedia) --}}
    <div id="fds-ty-summary-card" class="hidden bg-[#f5f5f7] rounded-3xl p-7 sm:p-9 border border-black/[0.06]">
      <h2 class="text-[22px] sm:text-[24px] font-semibold text-[#1d1d1f] tracking-[-0.02em] mb-5">
        {{ $ui['ty_summary_heading'] ?? 'Ringkasan Pengajuan Proyek' }}
      </h2>
      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-[14.5px]">
        <div>
          <dt class="text-[#6e6e73]">{{ $ui['ty_lbl_service'] ?? 'Fokus Layanan / Sektor' }}</dt>
          <dd id="fds-ty-sum-service" class="font-semibold text-[#1d1d1f] mt-0.5">-</dd>
        </div>
        <div>
          <dt class="text-[#6e6e73]">{{ $ui['ty_lbl_company'] ?? 'Perusahaan / Instansi' }}</dt>
          <dd id="fds-ty-sum-company" class="font-semibold text-[#1d1d1f] mt-0.5">-</dd>
        </div>
        <div id="fds-ty-sum-location-wrap">
          <dt class="text-[#6e6e73]">{{ $ui['ty_lbl_location'] ?? 'Lokasi Proyek' }}</dt>
          <dd id="fds-ty-sum-location" class="font-semibold text-[#1d1d1f] mt-0.5">-</dd>
        </div>
        <div id="fds-ty-sum-area-wrap">
          <dt class="text-[#6e6e73]">{{ $ui['ty_lbl_area'] ?? 'Perkiraan Luas / Target Jadwal' }}</dt>
          <dd id="fds-ty-sum-area" class="font-semibold text-[#1d1d1f] mt-0.5">-</dd>
        </div>
      </dl>
    </div>

    {{-- 3 Tahap Tindak Lanjut Tanpa Klaim SLA Fiktif --}}
    <div data-aos="fade-up" class="bg-[#f5f5f7] rounded-3xl p-7 sm:p-9 border border-black/[0.06]">
      @php
        $ty_step_prefix = trim((string) ($ui['ty_step_prefix'] ?? 'Tahap')) ?: 'Tahap';
      @endphp
      <h2 class="text-[22px] sm:text-[26px] font-semibold text-[#1d1d1f] tracking-[-0.02em] mb-6">
        {{ $ui['ty_steps_heading'] ?? 'Tahapan Peninjauan Berikutnya oleh Tim FDS' }}
      </h2>
      <ol class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <li class="pt-4 border-t border-black/[0.08]">
          <p class="text-[13px] font-semibold text-[#0a555c] mb-1.5">{{ $ty_step_prefix }} 01</p>
          @php
            $ty_step1_title = (string) ($ui['ty_step1_title'] ?? 'Verifikasi Data & Lokasi Proyek');
            if (stripos($ty_step1_title, 'Batas AOI') !== false) {
              $ty_step1_title = 'Verifikasi Data & Lokasi Proyek';
            }
          @endphp
          <h3 class="text-[17px] font-semibold text-[#1d1d1f] mb-1.5">{{ $ty_step1_title }}</h3>
          <p class="text-[14.5px] text-[#515154] leading-relaxed">
            {{ $ui['ty_step1_desc'] ?? 'Tim kami memastikan kelengkapan koordinat atau lokasi wilayah kerja serta memeriksa kejelasan format output data yang Anda butuhkan.' }}
          </p>
        </li>
        <li class="pt-4 border-t border-black/[0.08]">
          <p class="text-[13px] font-semibold text-[#0a555c] mb-1.5">{{ $ty_step_prefix }} 02</p>
          <h3 class="text-[17px] font-semibold text-[#1d1d1f] mb-1.5">{{ $ui['ty_step2_title'] ?? 'Evaluasi Metode & Kesiapan Lapangan' }}</h3>
          <p class="text-[14.5px] text-[#515154] leading-relaxed">
            {{ $ui['ty_step2_desc'] ?? 'Tim geospasial mengevaluasi karakteristik tutupan lahan, pilihan metode akuisisi (fotogrametri, LiDAR, atau multispectral), kebutuhan titik kontrol, dan izin ruang udara.' }}
          </p>
        </li>
        <li class="pt-4 border-t border-black/[0.08]">
          <p class="text-[13px] font-semibold text-[#0a555c] mb-1.5">{{ $ty_step_prefix }} 03</p>
          <h3 class="text-[17px] font-semibold text-[#1d1d1f] mb-1.5">{{ $ui['ty_step3_title'] ?? 'Koordinasi Diskusi Teknis & Proposal' }}</h3>
          <p class="text-[14.5px] text-[#515154] leading-relaxed">
            {{ $ui['ty_step3_desc'] ?? 'Tim enterprise akan menghubungi email atau WhatsApp bisnis Anda untuk klarifikasi ruang lingkup akhir sebelum penerbitan dokumen proposal resmi.' }}
          </p>
        </li>
      </ol>
    </div>

    {{-- Kanal Koordinasi Langsung --}}
    <div data-aos="fade-up" data-aos-delay="80" class="bg-[#f5f5f7] rounded-3xl p-7 sm:p-9 border border-black/[0.06] flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div class="max-w-[560px]">
        <h2 class="text-[20px] sm:text-[22px] font-semibold text-[#1d1d1f] mb-2">
          {{ $ui['ty_extra_heading'] ?? 'Perlu Mengirimkan Dokumen Tambahan atau Koordinasi Mendesak?' }}
        </h2>
        @php
          $ty_extra_tpl = $clean_ty($ui['ty_extra_desc'] ?? 'Anda dapat langsung melampirkan dokumen KAK, poligon lokasi (.SHP/.GeoJSON), atau spesifikasi teknis tambahan melalui email {email} atau terhubung ke tim kami melalui WhatsApp ({phone}).');
          $ty_extra_safe = e($ty_extra_tpl);
          $ty_email_html = '<a href="mailto:' . esc_attr($c_email) . '" class="font-semibold text-[#0a555c] hover:underline">' . e($c_email) . '</a>';
          $ty_extra_html = str_replace(['{email}', '{phone}'], [$ty_email_html, e($c_phone)], $ty_extra_safe);
        @endphp
        <p class="text-[14.5px] text-[#515154] leading-relaxed">
          {!! $ty_extra_html !!}
        </p>
      </div>
      <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
        <a href="{!! esc_url($wa_full_url) !!}"
           target="_blank"
           rel="noopener noreferrer"
           class="inline-flex items-center justify-center gap-2 min-h-[44px] bg-[#25D366] hover:bg-[#1fb857] text-[#1d1d1f] text-[14px] font-semibold px-6 py-2.5 rounded-full transition-colors leading-none shadow-sm">
          <svg class="w-4 h-4 fill-current flex-shrink-0" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
          </svg>
          <span>{{ $ui['ty_btn_wa'] ?? 'Lanjut via WhatsApp' }}</span>
        </a>
        <a href="{!! esc_url(home_url('/layanan/')) !!}"
           class="group inline-flex items-center justify-center gap-2 min-h-[44px] bg-[#0f808a] hover:bg-[#0a555c] text-white text-[14px] font-semibold px-6 py-2.5 rounded-full transition-colors leading-none shadow-sm">
          <span>{{ $ui['ty_btn_back'] ?? 'Kembali ke Layanan' }}</span>
          <svg class="w-[17px] h-[17px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
        <a href="{!! esc_url(home_url('/bandingkan/')) !!}"
           class="group inline-flex items-center justify-center gap-2 min-h-[44px] bg-white hover:bg-[#ededf0] text-[#1d1d1f] text-[14px] font-semibold px-6 py-2.5 rounded-full border border-black/[0.08] transition-colors leading-none">
          <span>{{ $ui['ty_btn_compare'] ?? 'Bandingkan Armada UAV' }}</span>
          <svg class="w-[17px] h-[17px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 6h12v12"/>
            <path d="M6 18 18 6"/>
          </svg>
        </a>
      </div>
    </div>

  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var refParam = {!! wp_json_encode($ref_param) !!};
  var summary = null;

  try {
    var raw = sessionStorage.getItem('fds_last_lead_summary');
    if (raw) summary = JSON.parse(raw);
  } catch (err) {}

  if (summary && (!refParam || !summary.lead_ref || summary.lead_ref === refParam)) {
    if (!refParam && summary.lead_ref) {
      var refWrap = document.getElementById('fds-ty-ref-wrap');
      var refEl = document.getElementById('fds-ty-ref-value');
      if (refWrap && refEl) {
        refEl.textContent = summary.lead_ref;
        refWrap.classList.remove('hidden');
      }
      refParam = summary.lead_ref;
    }

    var card = document.getElementById('fds-ty-summary-card');
    if (card) {
      var srvEl     = document.getElementById('fds-ty-sum-service');
      var compEl    = document.getElementById('fds-ty-sum-company');
      var locWrap   = document.getElementById('fds-ty-sum-location-wrap');
      var locEl     = document.getElementById('fds-ty-sum-location');
      var areaWrap  = document.getElementById('fds-ty-sum-area-wrap');
      var areaEl    = document.getElementById('fds-ty-sum-area');

      if (srvEl) srvEl.textContent = summary.service_label || '-';
      if (compEl) compEl.textContent = summary.company || '-';

      if (locEl && locWrap) {
        if (summary.project_location) {
          locEl.textContent = summary.project_location;
          locWrap.classList.remove('hidden');
        } else {
          locWrap.classList.add('hidden');
        }
      }

      var areaParts = [];
      if (summary.project_area) areaParts.push(summary.project_area);
      if (summary.project_timeline) areaParts.push(summary.project_timeline);
      if (areaEl && areaWrap) {
        if (areaParts.length) {
          areaEl.textContent = areaParts.join(' · ');
          areaWrap.classList.remove('hidden');
        } else {
          areaWrap.classList.add('hidden');
        }
      }

      card.classList.remove('hidden');
    }
  }

  if (typeof window.fdsTrackEvent === 'function') {
    window.fdsTrackEvent('view_thank_you', {
      lead_ref: refParam || ''
    });
  }
});
</script>

@endsection

