@extends('layouts.app')

@section('content')

{{-- ========================================================== --}}
{{-- 1. HERO &mdash; Drone Solutions Company, bukan pertanian saja   --}}
{{-- ========================================================== --}}
<section id="overview" class="pt-[52px] bg-[#f5f5f7] overflow-hidden">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-20 pb-0 text-center">

    <p class="inline-block text-[13px] font-semibold text-[#0066cc] mb-5 tracking-wide">
      TKDN 44,85% &middot; Produksi Indonesia
    </p>

    <h1 class="text-[44px] sm:text-[58px] lg:text-[72px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.05] max-w-[820px] mx-auto">
      Solusi Drone Industrial<br>untuk Setiap Sektor.
    </h1>

    <p class="mt-6 text-[18px] sm:text-[20px] text-[#515154] font-normal max-w-[580px] mx-auto leading-[1.55]">
      Dari pemetaan topografi hingga inspeksi infrastruktur&mdash;Full Drone Solutions menghadirkan teknologi udara berstandar industri, diproduksi lokal.
    </p>

    <div class="mt-8 flex items-center justify-center gap-4 flex-wrap">
      <a href="#solusi" class="inline-flex items-center bg-[#0066cc] hover:bg-[#0055b0] active:scale-[0.97] text-white text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150 shadow-md shadow-[#0066cc]/20">
        Jelajahi Solusi Kami
      </a>
      <a href="#kontak" class="inline-flex items-center text-[#0066cc] text-[15px] font-medium hover:underline gap-1 group">
        Konsultasi Enterprise
        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="mt-14 rounded-t-[2rem] overflow-hidden shadow-2xl shadow-black/10">
      <img
        src="{{ fds_img('hero', 'https://picsum.photos/seed/fds-drone-industrial-hero/1920/900') }}"
        alt="Full Drone Solutions &mdash; Drone Industrial untuk berbagai sektor"
        class="w-full h-[360px] sm:h-[520px] lg:h-[620px] object-cover"
      >
    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- 2. MITRA — Infinite marquee scroll                        --}}
{{-- ========================================================== --}}
@php
  $mitra_posts = get_posts(['post_type'=>'mitra','numberposts'=>-1,'orderby'=>'menu_order','order'=>'ASC']);
  $mitra_with_logo = array_filter($mitra_posts, fn($m) => get_the_post_thumbnail_url($m->ID, 'medium'));
@endphp

@if(!empty($mitra_with_logo))
<section class="bg-white border-b border-black/[0.06] py-14 overflow-hidden">
  <div class="text-center mb-10">
    <p class="text-[12px] font-semibold text-[#86868b] tracking-widest">
      DIPERCAYA OLEH LEMBAGA NASIONAL &amp; INTERNASIONAL
    </p>
  </div>

  {{-- Marquee track --}}
  <div class="relative">
    {{-- Fade mask kiri & kanan --}}
    <div class="absolute left-0 top-0 bottom-0 w-32 z-10 pointer-events-none"
         style="background: linear-gradient(to right, white 0%, transparent 100%);"></div>
    <div class="absolute right-0 top-0 bottom-0 w-32 z-10 pointer-events-none"
         style="background: linear-gradient(to left, white 0%, transparent 100%);"></div>

    {{-- Scroll track --}}
    <div class="flex" style="animation: fds-marquee 35s linear infinite;">
      @foreach($mitra_with_logo as $mitra)
        @php $logo = get_the_post_thumbnail_url($mitra->ID, 'medium'); @endphp
        <div class="flex-shrink-0 flex items-center justify-center px-10">
          <img src="{{ $logo }}"
               alt="{{ esc_attr($mitra->post_title) }}"
               class="h-24 w-auto object-contain"
               title="{{ $mitra->post_title }}">
        </div>
      @endforeach
      @foreach($mitra_with_logo as $mitra)
        @php $logo = get_the_post_thumbnail_url($mitra->ID, 'medium'); @endphp
        <div class="flex-shrink-0 flex items-center justify-center px-10" aria-hidden="true">
          <img src="{{ $logo }}" alt="" class="h-24 w-auto object-contain">
        </div>
      @endforeach
    </div>
  </div>

  <style>
    @keyframes fds-marquee {
      0%   { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    @media (prefers-reduced-motion: reduce) {
      [style*="fds-marquee"] { animation: none; }
    }
  </style>
</section>
@endif



{{-- ========================================================== --}}
{{-- 3. SOLUSI / USE CASES &mdash; drone company bukan hanya tani   --}}
{{-- ========================================================== --}}
<section id="solusi" class="bg-[#1d1d1f] py-24 sm:py-32">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-16">
      <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-4">Solusi Kami</p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-white leading-[1.1] max-w-[600px]">
        Satu platform. Berbagai industri.
      </h2>
      <p class="mt-4 text-[18px] text-white/50 max-w-[520px] leading-relaxed">
        FDS menyediakan ekosistem hardware, software, dan layanan operasional untuk berbagai kebutuhan industri.
      </p>
    </div>

    {{-- Solution grid: 3 cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

      {{-- Spraying / Agrikultur --}}
      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] overflow-hidden group hover:bg-white/[0.09] transition-all duration-300">
        <div class="h-[200px] overflow-hidden">
        <img src="{{ fds_img('solusi_agri', 'https://picsum.photos/seed/fds-spraying/800/400') }}" alt="Aerial Spraying" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        </div>
        <div class="p-7">
          <p class="text-[11px] font-semibold text-[#6e9fd4] tracking-wide mb-3">Agrikultur</p>
          <h3 class="text-[20px] font-semibold text-white mb-2">Aerial Spraying</h3>
          <p class="text-[14px] text-white/50 leading-relaxed mb-5">Penyemprotan pestisida dan pupuk cair presisi tinggi. Seri FERTO 5L&ndash;22L untuk semua skala lahan.</p>
          <a href="#produk" class="text-[13px] font-semibold text-[#6e9fd4] hover:underline inline-flex items-center gap-1">
            Lihat FERTO Series <span>&rsaquo;</span>
          </a>
        </div>
      </div>

      {{-- Pemetaan --}}
      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] overflow-hidden group hover:bg-white/[0.09] transition-all duration-300">
        <div class="h-[200px] overflow-hidden">
          <img src="{{ fds_img('solusi_map', 'https://picsum.photos/seed/fds-mapping-gis/800/400') }}" alt="Aerial Mapping" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        </div>
        <div class="p-7">
          <p class="text-[11px] font-semibold text-[#6e9fd4] tracking-wide mb-3">Pemetaan & GIS</p>
          <h3 class="text-[20px] font-semibold text-white mb-2">Aerial Mapping</h3>
          <p class="text-[14px] text-white/50 leading-relaxed mb-5">Peta topografi akurasi sub-sentimeter untuk infrastruktur, planologi, kehutanan, dan pertambangan.</p>
          <a href="#kontak" class="text-[13px] font-semibold text-[#6e9fd4] hover:underline inline-flex items-center gap-1">
            Konsultasi Pemetaan <span>&rsaquo;</span>
          </a>
        </div>
      </div>

      {{-- Inspeksi --}}
      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] overflow-hidden group hover:bg-white/[0.09] transition-all duration-300">
        <div class="h-[200px] overflow-hidden">
          <img src="{{ fds_img('solusi_ins', 'https://picsum.photos/seed/fds-inspection-thermal/800/400') }}" alt="Inspeksi Termal" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        </div>
        <div class="p-7">
          <p class="text-[11px] font-semibold text-[#6e9fd4] tracking-wide mb-3">Inspeksi Industri</p>
          <h3 class="text-[20px] font-semibold text-white mb-2">Thermal Inspection</h3>
          <p class="text-[14px] text-white/50 leading-relaxed mb-5">Deteksi anomali pada jaringan listrik, panel surya, tangki industri, dan struktur jembatan dengan kamera IR.</p>
          <a href="#kontak" class="text-[13px] font-semibold text-[#6e9fd4] hover:underline inline-flex items-center gap-1">
            Konsultasi Inspeksi <span>&rsaquo;</span>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- 4. KEUNGGULAN &mdash; Bento Grid FDS Company Strengths         --}}
{{-- ========================================================== --}}
<section id="keunggulan" class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-16">
      <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">Mengapa FDS</p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] max-w-[620px]">
        Keunggulan yang tidak bisa dikompromikan.
      </h2>
    </div>

    {{-- Bento Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">

      {{-- Large hero card &mdash; local manufacturing --}}
      <div class="lg:col-span-8 bg-[#f5f5f7] rounded-[2rem] overflow-hidden relative min-h-[340px] group"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <div class="absolute inset-0 z-0">
          <img src="https://picsum.photos/seed/fds-workshop-factory/1200/600"
               alt="Pabrik & Workshop FDS"
               class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700">
          <div class="absolute inset-0 bg-gradient-to-t from-[#1d1d1f]/80 via-[#1d1d1f]/20 to-transparent"></div>
        </div>
        <div class="relative z-10 h-full flex flex-col justify-end p-8 sm:p-10">
          <p class="text-[12px] font-bold text-white/50 tracking-wide mb-3">Produksi Lokal</p>
          <h3 class="text-[28px] sm:text-[34px] font-semibold text-white tracking-[-0.02em] leading-[1.1] mb-2">
            Dirancang & Diproduksi<br>di Indonesia.
          </h3>
          <p class="text-[15px] text-white/70 max-w-[380px] leading-relaxed">
            Seluruh lini hardware FERTO dirancang, dirakit, dan diuji di workshop FDS &mdash; bukan sekadar impor yang diberi label lokal.
          </p>
        </div>
      </div>

      {{-- TKDN Card --}}
      <div class="lg:col-span-4 bg-[#0066cc] rounded-[2rem] p-8 flex flex-col justify-between min-h-[200px]"
           style="box-shadow: 0 2px 24px rgba(0,102,204,0.2);">
        <p class="text-[12px] font-bold text-white/60 tracking-wide">Sertifikasi TKDN</p>
        <div>
          <p class="text-[54px] sm:text-[64px] font-semibold text-white tracking-[-0.04em] leading-none">44,85%</p>
          <p class="text-[14px] text-white/70 mt-2">Nilai TKDN tertinggi di segmen drone agrikultur nasional.</p>
        </div>
      </div>

      {{-- Software &mdash; GCS App --}}
      <div class="lg:col-span-4 bg-[#1d1d1f] rounded-[2rem] p-8 min-h-[200px] flex flex-col justify-between"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.08);">
        <div>
          <p class="text-[12px] font-semibold text-[#6e9fd4] tracking-wide mb-4">Software</p>
          <h3 class="text-[22px] font-semibold text-white tracking-[-0.02em] mb-2">Ground Control<br>Bahasa Indonesia</h3>
          <p class="text-[14px] text-white/50 leading-relaxed">Antarmuka misi penuh berbahasa Indonesia &mdash; tidak ada hambatan bahasa di lapangan.</p>
        </div>
      </div>

      {{-- IP67 --}}
      <div class="lg:col-span-4 bg-[#f5f5f7] rounded-[2rem] p-8 min-h-[200px] flex flex-col justify-between"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <div>
          <p class="text-[12px] font-bold text-[#86868b] tracking-wide mb-4">Ketahanan</p>
          <p class="text-[54px] font-semibold text-[#1d1d1f] tracking-[-0.04em] leading-none">IP67</p>
          <p class="text-[14px] text-[#515154] mt-2 leading-relaxed">Anti debu, tahan air bertekanan tinggi &mdash; siap beroperasi di semua kondisi cuaca.</p>
        </div>
      </div>

      {{-- After-Sales --}}
      <div class="lg:col-span-4 bg-[#f5f5f7] rounded-[2rem] p-8 min-h-[200px] flex flex-col justify-between"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <div>
          <p class="text-[12px] font-bold text-[#86868b] tracking-wide mb-4">After-Sales</p>
          <h3 class="text-[22px] font-semibold text-[#1d1d1f] tracking-[-0.02em] mb-2">Layanan Purna Jual Lokal</h3>
          <p class="text-[14px] text-[#515154] leading-relaxed">Teknisi, suku cadang, dan garansi resmi tersedia langsung di Indonesia tanpa menunggu impor.</p>
        </div>
      </div>

      {{-- 10+ Years --}}
      <div class="lg:col-span-4 sm:col-span-2 bg-[#e8f0fe] rounded-[2rem] p-8 min-h-[200px] flex flex-col justify-between"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.04);">
        <div>
          <p class="text-[12px] font-bold text-[#0066cc] tracking-wide mb-4">Pengalaman</p>
          <p class="text-[54px] font-semibold text-[#1d1d1f] tracking-[-0.04em] leading-none">10+</p>
          <p class="text-[14px] text-[#515154] mt-2 leading-relaxed">Tahun mengembangkan teknologi drone industri untuk berbagai sektor di Indonesia.</p>
        </div>
      </div>

      {{-- Multi-Sector --}}
      <div class="lg:col-span-8 bg-[#f5f5f7] rounded-[2rem] p-8 sm:p-10 min-h-[160px] flex flex-col sm:flex-row items-center gap-8"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <div class="flex-1">
          <p class="text-[12px] font-bold text-[#86868b] tracking-wide mb-3">Cakupan Industri</p>
          <h3 class="text-[24px] font-semibold text-[#1d1d1f] tracking-[-0.02em]">Satu ekosistem. Banyak solusi.</h3>
          <p class="text-[15px] text-[#515154] mt-2 leading-relaxed max-w-[420px]">Agrikultur, pemetaan topografi, inspeksi infrastruktur, kehutanan, dan pertambangan.</p>
        </div>
        <div class="grid grid-cols-3 gap-3 flex-shrink-0">
          @foreach(['Agri', 'Mapping', 'Inspeksi', 'Tambang', 'Hutan', 'BUMN'] as $sector)
            <div class="bg-white rounded-xl px-3 py-2 text-[12px] font-semibold text-[#515154] text-center"
                 style="box-shadow: 0 1px 8px rgba(0,0,0,0.06);">{{ $sector }}</div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- 5. PRODUCT LINEUP &mdash; FERTO Series                         --}}
{{-- ========================================================== --}}
<section id="produk" class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-16 flex flex-wrap items-end justify-between gap-6">
      <div>
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">Hardware</p>
        <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
          Seri FERTO. Dibuat di Indonesia.
        </h2>
        <p class="mt-4 text-[18px] text-[#515154] max-w-[500px] leading-relaxed">
          Empat varian kapasitas untuk seluruh skala operasional. TKDN 44,85%, perangkat lunak penuh Bahasa Indonesia.
        </p>
      </div>
    </div>

    {{-- FERTO lineup table-style --}}
    <div class="space-y-0 border-t border-black/[0.06]">

      <div class="grid grid-cols-12 gap-6 py-7 border-b border-black/[0.06] items-center group hover:bg-[#f5f5f7] rounded-2xl px-4 -mx-4 transition-colors duration-150">
        <div class="col-span-2 sm:col-span-1">
          <div class="w-10 h-10 bg-[#f5f5f7] rounded-xl flex items-center justify-center group-hover:bg-white transition-colors">
            <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
          </div>
        </div>
        <div class="col-span-5 sm:col-span-3">
          <p class="text-[11px] font-bold text-[#86868b] tracking-wide mb-0.5">Agrikultur</p>
          <h3 class="text-[20px] font-semibold text-[#1d1d1f] tracking-tight">FERTO 5L</h3>
        </div>
        <div class="hidden sm:block col-span-5">
          <p class="text-[15px] text-[#515154]">Entry-level sprayer untuk lahan kecil. Ringan, mudah dioperasikan.</p>
        </div>
        <div class="col-span-5 sm:col-span-3 text-right">
          <a href="#kontak" class="text-[13px] font-semibold text-[#0066cc] hover:underline">Hubungi Sales &rsaquo;</a>
        </div>
      </div>

      <div class="grid grid-cols-12 gap-6 py-7 border-b border-black/[0.06] items-center group hover:bg-[#f5f5f7] rounded-2xl px-4 -mx-4 transition-colors duration-150">
        <div class="col-span-2 sm:col-span-1">
          <div class="w-10 h-10 bg-[#f5f5f7] rounded-xl flex items-center justify-center group-hover:bg-white transition-colors">
            <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
          </div>
        </div>
        <div class="col-span-5 sm:col-span-3">
          <p class="text-[11px] font-bold text-[#86868b] tracking-wide mb-0.5">Agrikultur</p>
          <h3 class="text-[20px] font-semibold text-[#1d1d1f] tracking-tight">FERTO 10L</h3>
        </div>
        <div class="hidden sm:block col-span-5">
          <p class="text-[15px] text-[#515154]">Standar operasi lapangan harian. TKDN bersertifikat, andal dan efisien.</p>
        </div>
        <div class="col-span-5 sm:col-span-3 text-right">
          <a href="#kontak" class="text-[13px] font-semibold text-[#0066cc] hover:underline">Hubungi Sales &rsaquo;</a>
        </div>
      </div>

      <div class="grid grid-cols-12 gap-6 py-7 border-b border-black/[0.06] items-center group hover:bg-[#f5f5f7] rounded-2xl px-4 -mx-4 transition-colors duration-150">
        <div class="col-span-2 sm:col-span-1">
          <div class="w-10 h-10 bg-[#f5f5f7] rounded-xl flex items-center justify-center group-hover:bg-white transition-colors">
            <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
          </div>
        </div>
        <div class="col-span-5 sm:col-span-3">
          <p class="text-[11px] font-bold text-[#86868b] tracking-wide mb-0.5">Agrikultur</p>
          <h3 class="text-[20px] font-semibold text-[#1d1d1f] tracking-tight">FERTO 15L</h3>
        </div>
        <div class="hidden sm:block col-span-5">
          <p class="text-[15px] text-[#515154]">Varian menengah untuk operasi semi-komersial dengan payload lebih besar.</p>
        </div>
        <div class="col-span-5 sm:col-span-3 text-right">
          <a href="#kontak" class="text-[13px] font-semibold text-[#0066cc] hover:underline">Hubungi Sales &rsaquo;</a>
        </div>
      </div>

      <div class="grid grid-cols-12 gap-6 py-7 border-b border-black/[0.06] items-center group hover:bg-[#f5f5f7] rounded-2xl px-4 -mx-4 transition-colors duration-150">
        <div class="col-span-2 sm:col-span-1">
          <div class="w-10 h-10 bg-[#0066cc]/10 rounded-xl flex items-center justify-center group-hover:bg-[#0066cc]/20 transition-colors">
            <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
          </div>
        </div>
        <div class="col-span-5 sm:col-span-3">
          <p class="text-[11px] font-bold text-[#0066cc] tracking-wide mb-0.5">Enterprise &middot; Unggulan</p>
          <h3 class="text-[20px] font-semibold text-[#1d1d1f] tracking-tight">FERTO 22L</h3>
        </div>
        <div class="hidden sm:block col-span-5">
          <p class="text-[15px] text-[#515154]">Kapasitas terbesar. Hingga 10 Ha/jam. Juga tersedia varian Granule Spreader 25 Kg.</p>
        </div>
        <div class="col-span-5 sm:col-span-3 text-right">
          <a href="#kontak" class="text-[13px] font-semibold text-[#0066cc] hover:underline">Hubungi Sales &rsaquo;</a>
        </div>
      </div>

    </div>

    {{-- USP strip --}}
    <div class="mt-14 grid grid-cols-2 md:grid-cols-4 gap-6">
      <div class="text-center">
        <p class="text-[32px] font-semibold text-[#1d1d1f] tracking-[-0.03em]">44,85%</p>
        <p class="text-[13px] text-[#86868b] mt-1 font-medium">Nilai TKDN Resmi</p>
      </div>
      <div class="text-center">
        <p class="text-[32px] font-semibold text-[#1d1d1f] tracking-[-0.03em]">IP67</p>
        <p class="text-[13px] text-[#86868b] mt-1 font-medium">Proteksi Cuaca</p>
      </div>
      <div class="text-center">
        <p class="text-[32px] font-semibold text-[#1d1d1f] tracking-[-0.03em]">100%</p>
        <p class="text-[13px] text-[#86868b] mt-1 font-medium">Software Bahasa Indonesia</p>
      </div>
      <div class="text-center">
        <p class="text-[32px] font-semibold text-[#1d1d1f] tracking-[-0.03em]">10+</p>
        <p class="text-[13px] text-[#86868b] mt-1 font-medium">Tahun Pengalaman</p>
      </div>
    </div>

  </div>
</section>


{{-- ========================================================== --}}
{{-- 5. LAYANAN ENTERPRISE                                     --}}
{{-- ========================================================== --}}
<section id="layanan" class="bg-[#1d1d1f] py-24 sm:py-32 border-t border-white/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

      <div class="lg:sticky lg:top-24">
        <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-4">Layanan</p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5">
          Lebih dari sekadar hardware.
        </h2>
        <p class="text-[18px] text-white/50 leading-relaxed max-w-[380px] mb-8">
          Kami menyediakan layanan operasional lengkap untuk memastikan investasi drone Anda memberikan hasil maksimal.
        </p>
        <a href="#kontak"
           class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[14px] font-semibold px-6 py-3 rounded-full transition-all duration-150">
          Diskusi Kebutuhan Anda
        </a>
      </div>

      <div class="divide-y divide-white/[0.08]">
        <div class="py-7">
          <h3 class="text-[17px] font-semibold text-white mb-1.5">Pemetaan Aerial & GIS</h3>
          <p class="text-[15px] text-white/50 leading-relaxed">Peta topografi resolusi tinggi dengan akurasi sub-sentimeter untuk perencanaan lahan, kehutanan, dan infrastruktur.</p>
        </div>
        <div class="py-7">
          <h3 class="text-[17px] font-semibold text-white mb-1.5">Inspeksi Termal & Industri</h3>
          <p class="text-[15px] text-white/50 leading-relaxed">Deteksi dini kerusakan pada jaringan listrik, panel surya, kilang, dan jembatan menggunakan kamera IR beresolusi tinggi.</p>
        </div>
        <div class="py-7">
          <h3 class="text-[17px] font-semibold text-white mb-1.5">Sewa Armada Drone</h3>
          <p class="text-[15px] text-white/50 leading-relaxed">Armada FERTO siap pakai untuk proyek jangka pendek, pilot project, atau kebutuhan peak season tanpa investasi unit penuh.</p>
        </div>
        <div class="py-7">
          <h3 class="text-[17px] font-semibold text-white mb-1.5">Pelatihan & Sertifikasi Pilot</h3>
          <p class="text-[15px] text-white/50 leading-relaxed">Program pelatihan pilot drone bersertifikat resmi untuk tim lapangan Anda. Kurikulum mencakup misi agrikultur, pemetaan, dan inspeksi.</p>
        </div>
        <div class="py-7">
          <h3 class="text-[17px] font-semibold text-white mb-1.5">After-Sales & Maintenance</h3>
          <p class="text-[15px] text-white/50 leading-relaxed">Layanan purna jual lokal dengan stok suku cadang, teknisi bersertifikat, dan garansi resmi di seluruh Indonesia.</p>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- 6. BLOG / NEWSROOM                                        --}}
{{-- ========================================================== --}}
<section id="blog" class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="flex items-end justify-between mb-14 flex-wrap gap-6">
      <div>
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-3">Newsroom</p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
          Berita & Pembaruan Terkini.
        </h2>
      </div>
      <a href="{{ home_url('/blog') }}"
         class="inline-flex items-center gap-1.5 text-[14px] font-semibold text-[#0066cc] hover:underline flex-shrink-0">
        Lihat semua artikel
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    @php
      $recent_posts = get_posts([
        'numberposts' => 3,
        'post_status'  => 'publish',
        'orderby'      => 'date',
        'order'        => 'DESC',
      ]);
    @endphp

    @if($recent_posts)
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($recent_posts as $post)
          @php setup_postdata($post); @endphp
          <article class="bg-[#f5f5f7] rounded-[1.5rem] overflow-hidden group hover:-translate-y-1 transition-all duration-300">

            <div class="aspect-[16/9] overflow-hidden bg-[#e8e8ed]">
              @if(has_post_thumbnail($post->ID))
                {!! get_the_post_thumbnail($post->ID, 'medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700']) !!}
              @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#e8f0fe] to-[#dbeafe]">
                  <svg class="w-10 h-10 text-[#0066cc]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
              @endif
            </div>

            <div class="p-6">
              <p class="text-[11px] font-bold tracking-wide text-[#86868b] mb-3">
                {{ get_the_date('d M Y', $post->ID) }}
              </p>
              <h3 class="text-[16px] font-semibold text-[#1d1d1f] leading-[1.4] mb-3 group-hover:text-[#0066cc] transition-colors line-clamp-2">
                <a href="{{ get_permalink($post->ID) }}">{{ get_the_title($post->ID) }}</a>
              </h3>
              <p class="text-[14px] text-[#515154] leading-relaxed line-clamp-2 mb-5">
                {{ get_the_excerpt($post->ID) }}
              </p>
              <a href="{{ get_permalink($post->ID) }}"
                 class="inline-flex items-center gap-1 text-[13px] font-semibold text-[#0066cc] hover:underline">
                Baca selengkapnya <span>&rsaquo;</span>
              </a>
            </div>

          </article>
        @endforeach
      </div>
      @php wp_reset_postdata(); @endphp
    @else
      <div class="bg-[#f5f5f7] rounded-[1.5rem] p-12 text-center">
        <p class="text-[17px] text-[#515154]">Belum ada artikel yang diterbitkan.</p>
      </div>
    @endif

  </div>
</section>


{{-- ========================================================== --}}
{{-- 7. CONTACT &mdash; Premium split layout                        --}}
{{-- ========================================================== --}}
<section id="kontak" class="bg-[#f5f5f7] py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      {{-- Left panel: dark, info --}}
      <div class="bg-[#1d1d1f] rounded-[2rem] p-10 sm:p-14 flex flex-col justify-between min-h-[520px]">
        <div>
          <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-6">Enterprise Sales</p>
          <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5">
            Hubungi tim<br>Enterprise FDS.
          </h2>
          <p class="text-[17px] text-white/60 leading-relaxed max-w-[360px]">
            Dari konsultasi teknis, fleet management, hingga program sertifikasi &mdash; kami siap mendampingi operasional drone Anda.
          </p>
        </div>

        <div class="mt-12 space-y-6 border-t border-white/[0.08] pt-10">
          <div>
            <p class="text-[11px] font-semibold tracking-wide text-white/40 mb-1.5">Telepon / WhatsApp</p>
            <a href="tel:+6281234567890" class="text-[17px] font-semibold text-white hover:text-[#6e9fd4] transition-colors">+62 812-3456-7890</a>
          </div>
          <div>
            <p class="text-[11px] font-semibold tracking-wide text-white/40 mb-1.5">Email</p>
            <a href="mailto:sales@fulldronesolutions.co.id" class="text-[17px] font-semibold text-white hover:text-[#6e9fd4] transition-colors break-all">sales@fulldronesolutions.co.id</a>
          </div>
          <div>
            <p class="text-[11px] font-semibold tracking-wide text-white/40 mb-1.5">Lokasi Workshop</p>
            <p class="text-[17px] font-semibold text-white">Sleman, D.I. Yogyakarta</p>
          </div>
          <a href="https://wa.me/6281234567890" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2.5 bg-[#25D366] hover:bg-[#1db954] active:scale-[0.97] text-white font-semibold text-[14px] px-5 py-3 rounded-full transition-all duration-150">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            Chat via WhatsApp
          </a>
        </div>
      </div>

      {{-- Right panel: white, form --}}
      <div class="bg-white rounded-[2rem] p-10 sm:p-14" style="box-shadow: 0 4px 40px rgba(0,0,0,0.06);">
        <h3 class="text-[24px] font-semibold text-[#1d1d1f] tracking-tight mb-8">Kirim pesan inquiry</h3>

        <form class="space-y-7" onsubmit="event.preventDefault();">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Nama Depan</label>
              <input type="text" placeholder="Ahmad"
                class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200">
            </div>
            <div>
              <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Nama Belakang</label>
              <input type="text" placeholder="Fauzi"
                class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Perusahaan / Instansi</label>
              <input type="text" placeholder="PT. Contoh Indonesia"
                class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200">
            </div>
            <div>
              <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Email Bisnis</label>
              <input type="email" placeholder="nama@perusahaan.co.id"
                class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200">
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Nomor Telepon / WhatsApp</label>
            <input type="tel" placeholder="+62 812-XXXX-XXXX"
              class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200">
          </div>

          <div>
            <label class="block text-[11px] font-bold text-[#86868b] tracking-wide mb-2">Kebutuhan Anda</label>
            <textarea rows="3" placeholder="Jelaskan kebutuhan drone, layanan, atau pertanyaan teknis Anda..."
              class="w-full border-0 border-b-2 border-[#e8e8ed] focus:border-[#0066cc] bg-transparent py-2.5 text-[16px] text-[#1d1d1f] placeholder-[#c7c7cc] outline-none transition-colors duration-200 resize-none"></textarea>
          </div>

          <div class="pt-2 flex flex-col sm:flex-row items-start sm:items-center gap-5">
            <button type="submit"
              class="bg-[#1d1d1f] hover:bg-black active:scale-[0.97] text-white font-semibold text-[15px] px-8 py-4 rounded-full transition-all duration-150 whitespace-nowrap">
              Kirim Pesan
            </button>
            <p class="text-[12px] text-[#86868b] leading-relaxed">
              Kami merespons dalam 1×24 jam kerja.<br>Data Anda tidak akan dibagikan ke pihak ketiga.
            </p>
          </div>

        </form>
      </div>

    </div>
  </div>
</section>

@endsection

