{{--
  Template Name: Terima Kasih Inquiry
--}}

@extends('layouts.app')

@section('content')

@php
  $ref_param   = isset($_GET['ref']) ? sanitize_text_field(wp_unslash((string) $_GET['ref'])) : '';
  $ui          = function_exists('App\fds_get_layanan_ui_texts') ? \App\fds_get_layanan_ui_texts() : [];
  $gc          = function_exists('App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_wa_link   = $gc['wa_link'] ?? 'https://wa.me/628112748882';
  $c_email     = $gc['email'] ?? 'marketing@fulldronesolutions.com';
  $c_phone     = $gc['phone'] ?? '+62 8112 748 882';
  $wa_text     = 'Halo tim Full Drone Solutions, kami baru saja mengirimkan formulir permintaan penawaran melalui website'
               . ($ref_param !== '' ? ' dengan nomor referensi ' . $ref_param : '')
               . '. Mohon dibantu koordinasi lanjutan.';
  $wa_sep      = strpos($c_wa_link, '?') === false ? '?' : '&';
  $wa_full_url = $c_wa_link . $wa_sep . 'text=' . rawurlencode($wa_text);
@endphp

<section class="pt-32 sm:pt-40 pb-16 sm:pb-20 bg-gradient-to-br from-[#0f808a] via-[#0a555c] to-[#052b2e] text-white">
  <div class="max-w-[1000px] mx-auto px-6 lg:px-12">
    <div class="mb-6">
      <x-breadcrumbs
        theme="dark"
        :items="[
          ['name' => 'Beranda', 'url' => home_url('/')],
          ['name' => 'Layanan', 'url' => home_url('/layanan/')],
          ['name' => 'Konfirmasi Permintaan', 'url' => ''],
        ]"
      />
    </div>

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

    <h1 class="text-[32px] sm:text-[44px] lg:text-[50px] font-semibold tracking-[-0.03em] leading-[1.1] text-white">
      {{ $ui['ty_h1'] ?? 'Terima Kasih, Permintaan Konsultasi Anda Telah Kami Terima' }}
    </h1>

    <p class="mt-4 text-[16px] sm:text-[17.5px] text-white/85 leading-relaxed max-w-[760px]">
      {{ $ui['ty_subtitle'] ?? 'Tim Full Drone Solutions (PT Karya Solusi Angkasa) akan meninjau kebutuhan sektor, detail lokasi, serta lampiran poligon AOI yang Anda kirimkan untuk menyiapkan diskusi teknis dan estimasi penawaran yang relevan.' }}
    </p>

    <div id="fds-ty-ref-wrap" class="mt-7 {{ $ref_param === '' ? 'hidden' : '' }}">
      <p class="text-[13px] text-white/75 mb-1">{{ $ui['ty_ref_label'] ?? 'Nomor Referensi Inquiry' }}</p>
      <p id="fds-ty-ref-value" class="text-[20px] sm:text-[24px] font-semibold font-mono text-[#a3eef5] tracking-tight">
        {{ $ref_param }}
      </p>
    </div>
  </div>
</section>

<section class="bg-[#f5f5f7] py-16 sm:py-24 border-t border-black/[0.06]">
  <div class="max-w-[1000px] mx-auto px-6 lg:px-12 space-y-10">

    {{-- Ringkasan Non-PII dari SessionStorage (hanya tampil jika tersedia) --}}
    <div id="fds-ty-summary-card" class="hidden bg-white rounded-3xl p-7 sm:p-9 border border-black/[0.08]">
      <h2 class="text-[22px] sm:text-[24px] font-semibold text-[#1d1d1f] tracking-[-0.02em] mb-5">
        Ringkasan Pengajuan Proyek
      </h2>
      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-[14.5px]">
        <div>
          <dt class="text-[#6e6e73]">Fokus Layanan / Sektor</dt>
          <dd id="fds-ty-sum-service" class="font-semibold text-[#1d1d1f] mt-0.5">-</dd>
        </div>
        <div>
          <dt class="text-[#6e6e73]">Perusahaan / Instansi</dt>
          <dd id="fds-ty-sum-company" class="font-semibold text-[#1d1d1f] mt-0.5">-</dd>
        </div>
        <div>
          <dt class="text-[#6e6e73]">Lokasi Proyek</dt>
          <dd id="fds-ty-sum-location" class="font-semibold text-[#1d1d1f] mt-0.5">-</dd>
        </div>
        <div>
          <dt class="text-[#6e6e73]">Perkiraan Luas / Target Jadwal</dt>
          <dd id="fds-ty-sum-area" class="font-semibold text-[#1d1d1f] mt-0.5">-</dd>
        </div>
        <div>
          <dt class="text-[#6e6e73]">Lampiran File AOI / KAK</dt>
          <dd id="fds-ty-sum-aoi" class="font-semibold text-[#0a555c] mt-0.5">-</dd>
        </div>
      </dl>
    </div>

    {{-- 3 Tahap Tindak Lanjut Tanpa Klaim SLA Fiktif --}}
    <div class="bg-white rounded-3xl p-7 sm:p-9 border border-black/[0.08]">
      <h2 class="text-[22px] sm:text-[26px] font-semibold text-[#1d1d1f] tracking-[-0.02em] mb-6">
        {{ $ui['ty_steps_heading'] ?? 'Tahapan Peninjauan Berikutnya oleh Tim FDS' }}
      </h2>
      <ol class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <li class="pt-4 border-t border-black/[0.08]">
          <p class="text-[13px] font-semibold text-[#0a555c] mb-1.5">Tahap 01</p>
          <h3 class="text-[17px] font-semibold text-[#1d1d1f] mb-1.5">{{ $ui['ty_step1_title'] ?? 'Verifikasi Data & Batas AOI' }}</h3>
          <p class="text-[14.5px] text-[#515154] leading-relaxed">
            {{ $ui['ty_step1_desc'] ?? 'Tim kami memastikan kelengkapan koordinat atau poligon wilayah kerja serta memeriksa kejelasan format output data yang Anda butuhkan.' }}
          </p>
        </li>
        <li class="pt-4 border-t border-black/[0.08]">
          <p class="text-[13px] font-semibold text-[#0a555c] mb-1.5">Tahap 02</p>
          <h3 class="text-[17px] font-semibold text-[#1d1d1f] mb-1.5">{{ $ui['ty_step2_title'] ?? 'Evaluasi Metode & Kesiapan Lapangan' }}</h3>
          <p class="text-[14.5px] text-[#515154] leading-relaxed">
            {{ $ui['ty_step2_desc'] ?? 'Tim geospasial mengevaluasi karakteristik tutupan lahan, pilihan metode akuisisi (fotogrametri, LiDAR, atau multispectral), kebutuhan titik kontrol, dan izin ruang udara.' }}
          </p>
        </li>
        <li class="pt-4 border-t border-black/[0.08]">
          <p class="text-[13px] font-semibold text-[#0a555c] mb-1.5">Tahap 03</p>
          <h3 class="text-[17px] font-semibold text-[#1d1d1f] mb-1.5">{{ $ui['ty_step3_title'] ?? 'Koordinasi Diskusi Teknis & Proposal' }}</h3>
          <p class="text-[14.5px] text-[#515154] leading-relaxed">
            {{ $ui['ty_step3_desc'] ?? 'Tim enterprise akan menghubungi email atau WhatsApp bisnis Anda untuk klarifikasi ruang lingkup akhir sebelum penerbitan dokumen proposal resmi.' }}
          </p>
        </li>
      </ol>
    </div>

    {{-- Kanal Koordinasi Langsung --}}
    <div class="bg-white rounded-3xl p-7 sm:p-9 border border-black/[0.08] flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div class="max-w-[560px]">
        <h2 class="text-[20px] sm:text-[22px] font-semibold text-[#1d1d1f] mb-2">
          {{ $ui['ty_extra_heading'] ?? 'Perlu Mengirimkan Dokumen Tambahan atau Koordinasi Mendesak?' }}
        </h2>
        <p class="text-[14.5px] text-[#515154] leading-relaxed">
          Anda dapat langsung melampirkan file SHP/KML tambahan melalui email <a href="mailto:{{ esc_attr($c_email) }}" class="font-semibold text-[#0a555c] hover:underline">{{ $c_email }}</a> atau terhubung ke tim kami melalui WhatsApp ({{ $c_phone }}).
        </p>
      </div>
      <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
        <a href="{!! esc_url($wa_full_url) !!}"
           target="_blank"
           rel="noopener noreferrer"
           class="inline-flex items-center justify-center min-h-[44px] bg-[#25D366] hover:bg-[#1fb857] text-white text-[14px] font-semibold px-6 py-2.5 rounded-full transition-colors">
          {{ $ui['ty_btn_wa'] ?? 'Lanjut via WhatsApp' }}
        </a>
        <a href="{{ home_url('/layanan/') }}"
           class="inline-flex items-center justify-center min-h-[44px] bg-[#0f808a] hover:bg-[#0a555c] text-white text-[14px] font-semibold px-6 py-2.5 rounded-full transition-colors">
          {{ $ui['ty_btn_back'] ?? 'Kembali ke Layanan' }}
        </a>
        <a href="{{ home_url('/bandingkan/') }}"
           class="inline-flex items-center justify-center min-h-[44px] bg-[#f5f5f7] hover:bg-black/[0.06] text-[#1d1d1f] text-[14px] font-medium px-5 py-2.5 rounded-full transition-colors">
          {{ $ui['ty_btn_compare'] ?? 'Bandingkan Armada UAV' }}
        </a>
      </div>
    </div>

  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var refParam = '{{ esc_js($ref_param) }}';
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
      var srvEl = document.getElementById('fds-ty-sum-service');
      var compEl = document.getElementById('fds-ty-sum-company');
      var locEl = document.getElementById('fds-ty-sum-location');
      var areaEl = document.getElementById('fds-ty-sum-area');
      var aoiEl = document.getElementById('fds-ty-sum-aoi');

      if (srvEl) srvEl.textContent = summary.service_label || '-';
      if (compEl) compEl.textContent = summary.company || '-';
      if (locEl) locEl.textContent = summary.project_location || '-';

      var areaParts = [];
      if (summary.project_area) areaParts.push(summary.project_area);
      if (summary.project_timeline) areaParts.push(summary.project_timeline);
      if (areaEl) areaEl.textContent = areaParts.length ? areaParts.join(' · ') : '-';
      if (aoiEl) aoiEl.textContent = summary.has_aoi ? 'Terlampir (.kml/.kmz/.geojson/.pdf)' : 'Tidak dilampirkan';

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

