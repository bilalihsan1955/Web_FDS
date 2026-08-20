@extends('layouts.app')

@section('content')
@php
  // Ambil data customizer dengan fallback text default
  $hero_sub   = get_theme_mod('fds_about_hero_sub', 'PT Karya Solusi Angkasa (Full Drone Solutions) &middot; Pengalaman UAV Sejak 2012 &middot; Yogyakarta');
  $hero_title = get_theme_mod('fds_about_hero_title', 'Advanced UAV Engineering, Manufacturing & AI Technology.');
  
  // Baca content editor dari admin page Tentang Kami
  $page_content = '';
  if (have_posts()) {
      while (have_posts()) {
          the_post();
          $page_content = get_the_content();
      }
  }
  
  $story_title = get_theme_mod('fds_about_story_title', 'Rekayasa UAV mandiri untuk masa depan industri Indonesia.');
  $values_title = get_theme_mod('fds_about_values_title', '"Powerful Service. Giving Value" — Prinsip Kami.');
  $certs_title = get_theme_mod('fds_about_certs_title', 'Standar mutu global, sertifikasi resmi nasional.');
@endphp

{{-- ========================================================== --}}
{{-- HERO — Dark full-bleed                                     --}}
{{-- ========================================================== --}}
<section class="pt-[52px] bg-[#1d1d1f] overflow-hidden">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-24 pb-0">

    <div class="max-w-[800px]">
      <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-6">
        {!! $hero_sub !!}
      </p>
      <h1 class="text-[44px] sm:text-[60px] lg:text-[76px] font-semibold tracking-[-0.04em] text-white leading-[1.02]">
        {!! $hero_title !!}
      </h1>
      <p class="mt-7 text-[18px] sm:text-[20px] text-white/60 max-w-[620px] leading-[1.6]">
        Berpengalaman di industri UAV sejak 2012 dan resmi berbadan hukum PT pada 2019. Kami merancang desain aerodinamis, struktur avionik in-house, rangka karbon lokal, serta analitik AI untuk kemandirian teknologi udara Indonesia.
      </p>
    </div>

    {{-- Hero image --}}
    <div class="mt-16 rounded-t-[2rem] overflow-hidden" style="box-shadow: 0 -8px 48px rgba(0,0,0,0.3);">
      <img
        src="{{ fds_img('tk_hero', 'https://picsum.photos/seed/fds-team-workshop-2026/1920/800') }}"
        alt="Tim & Workshop PT Karya Solusi Angkasa (FDS)"
        class="w-full h-[320px] sm:h-[480px] lg:h-[560px] object-cover"
      >
    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- STATS — Dark continuation                                 --}}
{{-- ========================================================== --}}
<section class="bg-[#1d1d1f] border-b border-white/[0.08] py-16">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-10 text-center">
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">2012</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">Pengalaman UAV (PT Sejak 2019)</p>
      </div>
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">60,74%</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">Nilai TKDN + BMP Kemenperin</p>
      </div>
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">ISO &amp; SNI</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">ISO 9001:2015 &amp; SNI 9199:2023</p>
      </div>
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">100%</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">Rekayasa &amp; Software Lokal</p>
      </div>
    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- STORY — White section, editorial two-column               --}}
{{-- ========================================================== --}}
<section class="bg-white py-24 sm:py-32">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-start">

      {{-- Left: headline sticky --}}
      <div class="lg:sticky lg:top-28">
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-5">Cerita Kami</p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
          {!! $story_title !!}
        </h2>
        <div class="mt-8 rounded-2xl overflow-hidden">
          <img src="{{ fds_img('tk_story', 'https://picsum.photos/seed/fds-origin-story/800/600') }}"
               alt="Workshop PT Karya Solusi Angkasa"
               class="w-full h-auto object-contain">
        </div>
      </div>

      {{-- Right: story text --}}
      <div class="space-y-8 text-[18px] text-[#515154] leading-[1.7]">
        @if (!empty($page_content))
          {!! apply_filters('the_content', $page_content) !!}
        @else
          <p>
            <strong class="text-[#1d1d1f] font-semibold">PT Karya Solusi Angkasa</strong> (dikenal sebagai <strong class="text-[#1d1d1f] font-semibold">Full Drone Solutions / FDS</strong>) mengawali perjalanannya dari dedikasi mendalam terhadap rekayasa sistem pesawat tanpa awak (*Unmanned Aerial Vehicle*) sejak tahun 2012 di Yogyakarta, hingga resmi berbadan hukum perseroan terbatas pada tahun 2019.
          </p>
          <p>
            Dengan fokus pada <strong class="text-[#1d1d1f] font-semibold">Advanced UAV Engineering, Manufacturing, &amp; AI Technology</strong>, FDS tidak sekadar merakit atau mengimpor komponen jadi. Kami melakukan riset desain aerodinamis, pengembangan struktur mekanis, perancangan avionik *in-house*, pencetakan komposit karbon lokal, dan integrasi *payload* kustom untuk menghasilkan drone yang tangguh di iklim tropis Indonesia.
          </p>
          <p>
            Komitmen mutu kami dibuktikan melalui kepemilikan sertifikasi manajemen mutu internasional <strong class="text-[#1d1d1f] font-semibold">ISO 9001:2015</strong>, sertifikasi produk <strong class="text-[#1d1d1f] font-semibold">SNI 9199:2023</strong>, serta pencapaian <strong class="text-[#1d1d1f] font-semibold">Nilai TKDN + Bobot Manfaat Perusahaan (BMP) mencapai 60,74%</strong> dari Kementerian Perindustrian Republik Indonesia.
          </p>
          <p>
            Dengan moto <em class="text-[#1d1d1f] font-semibold">"Powerful Service. Giving Value"</em>, kami mengoperasikan alur kerja layanan end-to-end yang terstruktur: mulai dari <strong>1. Consultation</strong>, <strong>2. Requirement &amp; Spec Formulation</strong>, <strong>3. In-House Development</strong>, hingga <strong>4. Delivery &amp; Certified Pilot Training</strong>.
          </p>
        @endif
        
        <div class="pt-4 border-t border-black/[0.06]">
          <a href="#mitra" class="inline-flex items-center gap-1.5 text-[16px] font-semibold text-[#0066cc] hover:underline">
            Lihat kemitraan strategis &amp; portofolio klien
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- EKOSISTEM TEKNOLOGI — Spektrum UAV & AI                    --}}
{{-- ========================================================== --}}
<section class="bg-[#1d1d1f] py-24 sm:py-32">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-16">
      <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-4">Spektrum Teknologi UAV</p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-white leading-[1.1] max-w-[620px]">
        Tiga arsitektur wahana udara untuk segala medan.
      </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
      {{-- Rotary Wing --}}
      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 hover:bg-white/[0.09] transition-colors">
        <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center mb-6">
          <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
        </div>
        <h3 class="text-[20px] font-semibold text-white mb-3">Rotary Wing (Multirotor)</h3>
        <p class="text-[15px] text-white/60 leading-relaxed">
          Kemampuan Vertical Takeoff and Landing (VTOL), kontrol posisi presisi tinggi, dan hovering super stabil. Digunakan pada seri <strong>FERTO (5–50L)</strong>, <strong>DELFRO</strong> kargo, dan <strong>REBO</strong> reboisasi.
        </p>
      </div>

      {{-- Fixed Wing --}}
      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 hover:bg-white/[0.09] transition-colors">
        <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center mb-6">
          <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-[20px] font-semibold text-white mb-3">Fixed Wing (Sayap Tetap)</h3>
        <p class="text-[15px] text-white/60 leading-relaxed">
          Dirancang untuk misi jarak jauh, daya tahan terbang tinggi (endurance), dan cakupan area pemetaan luas yang efisien dalam satu sorti penerbangan.
        </p>
      </div>

      {{-- Hybrid VTOL --}}
      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 hover:bg-white/[0.09] transition-colors">
        <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center mb-6">
          <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
        </div>
        <h3 class="text-[20px] font-semibold text-white mb-3">Hybrid VTOL (DELTAV)</h3>
        <p class="text-[15px] text-white/60 leading-relaxed">
          Menggabungkan fleksibilitas peluncuran vertikal tanpa landasan dengan kecepatan jelajah 15–22 m/s dan jangkauan 60 km untuk akuisisi geospasial presisi.
        </p>
      </div>
    </div>

  </div>
</section>


{{-- ========================================================== --}}
{{-- PARTNERSHIPS — White, editorial list                      --}}
{{-- ========================================================== --}}
<section id="mitra" class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

      <div>
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-5">Kemitraan &amp; Klien Strategis</p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-6">
          Dipercaya oleh institusi negara, BUMN, dan korporasi terkemuka.
        </h2>
        <p class="text-[18px] text-[#515154] leading-relaxed max-w-[460px]">
          FDS secara konsisten menjadi mitra strategis dalam program ketahanan pangan nasional, riset geospasial, dan otomatisasi industri skala besar.
        </p>
      </div>

      <div class="divide-y divide-black/[0.06]">
        @foreach([
          ['Program Riset & Pemerintah', 'Bappenas & Australia DFAT', 'Kolaborasi teknologi pertanian presisi dan ketahanan pangan nasional melalui Program PRISMA.'],
          ['Moneter & Pangan', 'Bank Indonesia', 'Penyediaan ekosistem drone agrikultur terpadu untuk penguatan klaster ketahanan pangan daerah.'],
          ['Riset Akademis & Konservasi', 'UGM & Mitra Riset Swiss', 'Riset bersama teknologi reboisasi benih udara (seedball) dan pemetaan geospasial berkelanjutan.'],
          ['Agroindustri & Pupuk', 'Pupuk Indonesia, Petrokimia Kayaku & Petrosida', 'Uji efektivitas penyemprotan pupuk cair dan pestisida presisi di berbagai sentra pertanian.'],
          ['Pertambangan & Energi', 'Pertamina, PLN, PAMA & MHU Coal', 'Inspeksi termal jaringan transmisi 150 kV, solar farm, cerobong migas, dan volumetri stockpile tambang.'],
          ['BUMN & Keuangan', 'SUCOFINDO, Bank BRI, BNI & Perhutani', 'Verifikasi data geospasial, pemetaan tutupan hutan, dan kemitraan pembiayaan modernisasi agritech.'],
          ['Logistik & Kehutanan', 'Sinarmas Forestry, RAPP, KAI, J&T & BRIN', 'Survei kanopi hutan, inspeksi jalur rel kereta api, riset UAV BRIN, dan pengujian logistik otonom.'],
        ] as [$cat, $name, $desc])
        <div class="py-7 grid grid-cols-12 gap-4 items-center">
          <div class="col-span-12 sm:col-span-4">
            <p class="text-[11px] font-semibold text-[#86868b] uppercase tracking-wider">{{ $cat }}</p>
            <h3 class="text-[17px] font-semibold text-[#1d1d1f] mt-0.5">{{ $name }}</h3>
          </div>
          <div class="col-span-12 sm:col-span-8">
            <p class="text-[14px] text-[#515154] leading-relaxed">{{ $desc }}</p>
          </div>
        </div>
        @endforeach
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- CERTIFICATIONS — Light gray bento                         --}}
{{-- ========================================================== --}}
<section class="bg-[#f5f5f7] py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-14">
      <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">Sertifikasi &amp; Standar Mutu</p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] max-w-[600px]">
        {!! $certs_title !!}
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">

      {{-- Card 1: TKDN 60,74% --}}
      <div class="bg-[#0066cc] rounded-[2rem] p-8 lg:p-9 flex flex-col justify-between min-h-[250px] transition-transform duration-200 hover:-translate-y-1"
           style="box-shadow: 0 4px 32px rgba(0,102,204,0.2);">
        <p class="text-[13px] font-semibold text-white/70 tracking-wide mb-6">Kemenperin RI</p>
        <div>
          <p class="text-[44px] sm:text-[48px] font-bold text-white tracking-[-0.03em] leading-tight">60,74%</p>
          <p class="text-[14px] text-white/80 mt-3 leading-relaxed">Nilai TKDN + Bobot Manfaat Perusahaan (BMP) tertinggi di segmen drone industri buatan lokal.</p>
        </div>
      </div>

      {{-- Card 2: ISO & SNI --}}
      <div class="bg-white rounded-[2rem] p-8 lg:p-9 flex flex-col justify-between min-h-[250px] border border-black/[0.04] transition-transform duration-200 hover:-translate-y-1"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <p class="text-[13px] font-semibold text-[#86868b] tracking-wide mb-6">Standar Produk &amp; Manajemen</p>
        <div>
          <p class="text-[44px] sm:text-[48px] font-bold text-[#1d1d1f] tracking-[-0.03em] leading-tight">ISO &amp; SNI</p>
          <p class="text-[14px] text-[#515154] mt-3 leading-relaxed">Sertifikasi ISO 9001:2015 (Manajemen Mutu) dan SNI 9199:2023 (Standar Nasional Drone Pertanian).</p>
        </div>
      </div>

      {{-- Card 3: 24/7 Service --}}
      <div class="bg-[#1d1d1f] rounded-[2rem] p-8 lg:p-9 flex flex-col justify-between min-h-[250px] transition-transform duration-200 hover:-translate-y-1"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.08);">
        <p class="text-[13px] font-semibold text-white/60 tracking-wide mb-6">Jaminan Layanan</p>
        <div>
          <p class="text-[44px] sm:text-[48px] font-bold text-white tracking-[-0.03em] leading-tight">24/7</p>
          <p class="text-[14px] text-white/70 mt-3 leading-relaxed">Dukungan servis, suku cadang asli, dan sertifikasi pilot resmi di seluruh Indonesia.</p>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- CTA — Dark full-width                                      --}}
{{-- ========================================================== --}}
<section class="bg-[#1d1d1f] py-24 sm:py-32">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

      <div>
        <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5">
          Siap bermitra dengan PT Karya Solusi Angkasa?
        </h2>
        <p class="text-[18px] text-white/60 leading-relaxed max-w-[480px]">
          Baik instansi pemerintah, BUMN, perkebunan agrikultur besar, atau mitra industri &mdash; tim engineering kami siap memberikan solusi terbaik.
        </p>
        <div class="mt-8 flex flex-wrap gap-4">
          <a href="{{ home_url('/#kontak') }}"
             class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150">
            Mulai Konsultasi
          </a>
          <a href="{{ home_url('/blog') }}"
             class="inline-flex items-center text-white/60 text-[15px] font-medium hover:text-white transition-colors gap-1">
            Baca Studi Kasus &rsaquo;
          </a>
        </div>
      </div>

      <div class="lg:justify-self-end">
        <div class="bg-white/[0.04] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 max-w-[480px]">
          <h3 class="text-[20px] font-semibold text-white mb-6">Kantor Pusat &amp; Workshop</h3>
          <div class="space-y-4 text-[15px] text-white/60">
            <p>
              <strong class="text-white block mb-0.5">Entitas Perusahaan</strong>
              PT Karya Solusi Angkasa (Full Drone Solutions)
            </p>
            <p>
              <strong class="text-white block mb-0.5">Alamat Workshop</strong>
              DI Yogyakarta, Indonesia
            </p>
            <p>
              <strong class="text-white block mb-0.5">Email Resmi</strong>
              info@fulldronesolutions.com
            </p>
            <p>
              <strong class="text-white block mb-0.5">Layanan Cepat</strong>
              Konsultasi Proyek &amp; Pengadaan Korporasi
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
