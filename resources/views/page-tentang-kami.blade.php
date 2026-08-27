@extends('layouts.app')

@section('content')
@php
  // Ambil data lengkap halaman Tentang Kami dari Admin WP
  $about = function_exists('\App\fds_get_about_content') ? \App\fds_get_about_content() : [];

  // Baca content editor bawaan jika ada konten khusus di editor halaman
  $page_content = '';
  if (have_posts()) {
      while (have_posts()) {
          the_post();
          $page_content = get_the_content();
      }
  }
@endphp

{{-- ========================================================== --}}
{{-- HERO — Dark full-bleed                                     --}}
{{-- ========================================================== --}}
<section class="pt-[52px] bg-[#1d1d1f] overflow-hidden">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-24 pb-0">

    <div class="max-w-[840px]">
      <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-6">
        {!! esc_html(wp_specialchars_decode($about['hero_sub'] ?? 'PT Karya Solusi Angkasa (Full Drone Solutions) · Pengalaman UAV Sejak 2012 · Yogyakarta')) !!}
      </p>
      <h1 class="text-[44px] sm:text-[60px] lg:text-[76px] font-semibold tracking-[-0.04em] text-white leading-[1.02]">
        {!! nl2br(esc_html(wp_specialchars_decode($about['hero_title'] ?? "Advanced UAV Engineering,\nManufacturing & AI Technology."))) !!}
      </h1>
      <p class="mt-7 text-[18px] sm:text-[20px] text-white/60 max-w-[640px] leading-[1.6]">
        {!! nl2br(esc_html(wp_specialchars_decode($about['hero_desc'] ?? 'Berpengalaman di industri UAV sejak 2012 dan resmi berbadan hukum PT pada 2019. Kami merancang desain aerodinamis, struktur avionik in-house, rangka karbon lokal, serta analitik AI untuk kemandirian teknologi udara Indonesia.'))) !!}
      </p>
    </div>

    {{-- Hero image --}}
    <div class="mt-16 rounded-t-[2rem] overflow-hidden" style="box-shadow: 0 -8px 48px rgba(0,0,0,0.3);">
      <img
        src="{{ !empty($about['hero_img']) ? $about['hero_img'] : fds_img('tk_hero', 'https://picsum.photos/seed/fds-team-workshop-2026/1920/800') }}"
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
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">{!! esc_html($about['stat1_num'] ?? '2012') !!}</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">{!! esc_html($about['stat1_lbl'] ?? 'Pengalaman UAV (PT Sejak 2019)') !!}</p>
      </div>
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">{!! esc_html($about['stat2_num'] ?? '60,74%') !!}</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">{!! esc_html($about['stat2_lbl'] ?? 'Nilai TKDN + BMP Kemenperin') !!}</p>
      </div>
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">{!! esc_html($about['stat3_num'] ?? 'ISO & SNI') !!}</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">{!! esc_html($about['stat3_lbl'] ?? 'ISO 9001:2015 & SNI 9199:2023') !!}</p>
      </div>
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">{!! esc_html($about['stat4_num'] ?? '100%') !!}</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">{!! esc_html($about['stat4_lbl'] ?? 'Rekayasa & Software Lokal') !!}</p>
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
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-5">
          {!! esc_html($about['story_badge'] ?? 'Cerita Kami') !!}
        </p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
          {!! $about['story_title'] ?? 'Rekayasa UAV mandiri untuk masa depan industri Indonesia.' !!}
        </h2>
        <div class="mt-8">
          <img src="{{ !empty($about['story_img']) ? $about['story_img'] : fds_img('tk_story', 'https://picsum.photos/seed/fds-origin-story/800/600') }}"
               alt="Perjalanan dan Sejarah PT Karya Solusi Angkasa (FDS)"
               class="w-full h-auto block">
        </div>
      </div>

      {{-- Right: story text --}}
      <div class="space-y-8 text-[18px] text-[#515154] leading-[1.7]">
        @if (!empty($page_content))
          {!! apply_filters('the_content', $page_content) !!}
        @else
          <p>{!! $about['story_p1'] ?? '' !!}</p>
          <p>{!! $about['story_p2'] ?? '' !!}</p>
          <p>{!! $about['story_p3'] ?? '' !!}</p>
          <p>{!! $about['story_p4'] ?? '' !!}</p>
        @endif
        
        <div class="pt-4 border-t border-black/[0.06]">
          <a href="{{ $about['story_cta_url'] ?? '#mitra' }}" class="inline-flex items-center gap-1.5 text-[16px] font-semibold text-[#0066cc] hover:underline">
            {!! esc_html($about['story_cta_text'] ?? 'Lihat kemitraan strategis & portofolio klien') !!}
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
      <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-4">
        {!! esc_html($about['spektrum_badge'] ?? 'Spektrum Teknologi UAV') !!}
      </p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-white leading-[1.1] max-w-[620px]">
        {!! esc_html($about['spektrum_title'] ?? 'Tiga arsitektur wahana udara untuk segala medan.') !!}
      </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
      {{-- Rotary Wing --}}
      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 hover:bg-white/[0.09] transition-colors">
        <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center mb-6">
          <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
        </div>
        <h3 class="text-[20px] font-semibold text-white mb-3">{!! esc_html($about['spektrum1_title'] ?? 'Rotary Wing (Multirotor)') !!}</h3>
        <p class="text-[15px] text-white/60 leading-relaxed">
          {!! $about['spektrum1_desc'] ?? 'Kemampuan Vertical Takeoff and Landing (VTOL), kontrol posisi presisi tinggi, dan hovering super stabil.' !!}
        </p>
      </div>

      {{-- Fixed Wing --}}
      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 hover:bg-white/[0.09] transition-colors">
        <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center mb-6">
          <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-[20px] font-semibold text-white mb-3">{!! esc_html($about['spektrum2_title'] ?? 'Fixed Wing (Sayap Tetap)') !!}</h3>
        <p class="text-[15px] text-white/60 leading-relaxed">
          {!! $about['spektrum2_desc'] ?? 'Dirancang untuk misi jarak jauh, daya tahan terbang tinggi (endurance), dan cakupan area pemetaan luas.' !!}
        </p>
      </div>

      {{-- Hybrid VTOL --}}
      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 hover:bg-white/[0.09] transition-colors">
        <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center mb-6">
          <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
        </div>
        <h3 class="text-[20px] font-semibold text-white mb-3">{!! esc_html($about['spektrum3_title'] ?? 'Hybrid VTOL (DELTAV)') !!}</h3>
        <p class="text-[15px] text-white/60 leading-relaxed">
          {!! $about['spektrum3_desc'] ?? 'Menggabungkan fleksibilitas peluncuran vertikal tanpa landasan dengan kecepatan jelajah 15–22 m/s dan jangkauan 60 km.' !!}
        </p>
      </div>
    </div>

  </div>
</section>


{{-- ========================================================== --}}
{{-- OUR ACTIVITY & KEMITRAAN — White, editorial list           --}}
{{-- ========================================================== --}}
<section id="aktivitas" class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start">

      <div class="lg:col-span-4 lg:sticky lg:top-28">
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">
          {!! esc_html($about['mitra_badge'] ?? 'Aktivitas Kami') !!}
        </p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-6">
          {!! esc_html($about['mitra_title'] ?? 'Aktivitas Kami') !!}
        </h2>
        <p class="text-[17px] text-[#515154] leading-relaxed">
          {!! esc_html($about['mitra_desc'] ?? 'Riset mandiri, inovasi manufaktur lokal, serta kolaborasi strategis bersama institusi nasional dan mitra internasional.') !!}
        </p>
      </div>

      <div class="lg:col-span-8 divide-y divide-black/[0.06]">
        @php
          $about_activities = function_exists('App\fds_get_about_activities') ? \App\fds_get_about_activities() : [];
        @endphp
        @foreach($about_activities as $act)
          @if(!empty($act['name']))
          <div class="py-8 grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-6 items-start">
            <div class="sm:col-span-5">
              @if(!empty($act['cat']))
              <p class="text-[12px] font-medium text-[#86868b] tracking-normal mb-1">{!! esc_html($act['cat']) !!}</p>
              @endif
              <h3 class="text-[18px] font-semibold text-[#1d1d1f] leading-snug">{!! esc_html($act['name']) !!}</h3>
            </div>
            <div class="sm:col-span-7">
              <p class="text-[15px] text-[#515154] leading-relaxed">{!! nl2br(esc_html($act['desc'])) !!}</p>
            </div>
          </div>
          @endif
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
      <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">
        {!! esc_html($about['certs_badge'] ?? 'Sertifikasi & Standar Mutu') !!}
      </p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] max-w-[600px]">
        {!! $about['certs_title'] ?? 'Standar mutu global, sertifikasi resmi nasional.' !!}
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">

      {{-- Card 1: TKDN 60,74% --}}
      <div class="bg-[#0066cc] rounded-[2rem] p-8 lg:p-9 flex flex-col justify-between min-h-[250px] transition-transform duration-200 hover:-translate-y-1"
           style="box-shadow: 0 4px 32px rgba(0,102,204,0.2);">
        <p class="text-[13px] font-semibold text-white/70 tracking-wide mb-6">{!! esc_html($about['cert1_badge'] ?? 'Kemenperin RI') !!}</p>
        <div>
          <p class="text-[44px] sm:text-[48px] font-bold text-white tracking-[-0.03em] leading-tight">{!! esc_html($about['cert1_val'] ?? '60,74%') !!}</p>
          <p class="text-[14px] text-white/80 mt-3 leading-relaxed">{!! esc_html($about['cert1_desc'] ?? 'Nilai TKDN + Bobot Manfaat Perusahaan (BMP) tertinggi di segmen drone industri buatan lokal.') !!}</p>
        </div>
      </div>

      {{-- Card 2: ISO & SNI --}}
      <div class="bg-white rounded-[2rem] p-8 lg:p-9 flex flex-col justify-between min-h-[250px] border border-black/[0.04] transition-transform duration-200 hover:-translate-y-1"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <p class="text-[13px] font-semibold text-[#86868b] tracking-wide mb-6">{!! esc_html($about['cert2_badge'] ?? 'Standar Produk & Manajemen') !!}</p>
        <div>
          <p class="text-[44px] sm:text-[48px] font-bold text-[#1d1d1f] tracking-[-0.03em] leading-tight">{!! esc_html($about['cert2_val'] ?? 'ISO & SNI') !!}</p>
          <p class="text-[14px] text-[#515154] mt-3 leading-relaxed">{!! esc_html($about['cert2_desc'] ?? 'Sertifikasi ISO 9001:2015 (Manajemen Mutu) dan SNI 9199:2023 (Standar Nasional Drone Pertanian).') !!}</p>
        </div>
      </div>

      {{-- Card 3: 24/7 Service --}}
      <div class="bg-[#1d1d1f] rounded-[2rem] p-8 lg:p-9 flex flex-col justify-between min-h-[250px] transition-transform duration-200 hover:-translate-y-1"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.08);">
        <p class="text-[13px] font-semibold text-white/60 tracking-wide mb-6">{!! esc_html($about['cert3_badge'] ?? 'Jaminan Layanan') !!}</p>
        <div>
          <p class="text-[44px] sm:text-[48px] font-bold text-white tracking-[-0.03em] leading-tight">{!! esc_html($about['cert3_val'] ?? '24/7') !!}</p>
          <p class="text-[14px] text-white/70 mt-3 leading-relaxed">{!! esc_html($about['cert3_desc'] ?? 'Dukungan servis, suku cadang asli, dan sertifikasi pilot resmi di seluruh Indonesia.') !!}</p>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- CTA & WORKSHOP — Clean Editorial Dark Split & Full Map     --}}
{{-- ========================================================== --}}
@php
  $global_c  = function_exists('\App\fds_get_global_contact') ? \App\fds_get_global_contact() : [];
  $c_entitas = $global_c['company_name'] ?? ($about['info_entitas'] ?? 'PT Karya Solusi Angkasa (Full Drone Solutions)');
  $c_alamat  = $global_c['address'] ?? ($about['info_alamat'] ?? 'Jl. Griya Perwita Asri No.15, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281');
  $c_email   = $global_c['email'] ?? ($about['info_email'] ?? 'marketing@fulldronesolutions.com');
  $c_phone   = $global_c['phone'] ?? ($about['info_phone'] ?? '+62 8112 748 882');
  $c_wa_link = $global_c['wa_link'] ?? 'https://wa.me/628112748882';
  $c_maps    = $global_c['maps_url'] ?? ($about['info_maps'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4859.550770370755!2d110.35575187584948!3d-7.733164692285225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59ea1c47127b%3A0xd9a7f206f6f28d07!2sFull%20Drone%20Solutions!5e1!3m2!1sid!2sid!4v1787546079011!5m2!1sid!2sid');
@endphp
<section class="bg-[#1d1d1f] pt-28 sm:pt-36 pb-0 border-t border-white/[0.08] overflow-hidden flex flex-col justify-between">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 w-full pb-20 sm:pb-28">
    
    {{-- 2-Column Split: Headline & Directory List --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 items-start">

      {{-- Left: Massive Editorial Headline & Action Buttons (Col 7) --}}
      <div class="lg:col-span-7">
        <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-6">
          Kemitraan &amp; Pengadaan Korporasi
        </p>
        <h2 class="text-[38px] sm:text-[52px] lg:text-[60px] font-semibold tracking-[-0.035em] text-white leading-[1.06] mb-8">
          {!! esc_html($about['cta_title'] ?? 'Siap bermitra dengan PT Karya Solusi Angkasa?') !!}
        </h2>
        <p class="text-[18px] sm:text-[20px] text-white/60 leading-[1.65] max-w-[580px] mb-12">
          {!! esc_html($about['cta_desc'] ?? 'Baik instansi pemerintah, BUMN, perkebunan agrikultur besar, atau mitra industri — tim engineering kami siap memberikan solusi terbaik.') !!}
        </p>
        <div class="flex flex-wrap items-center gap-6">
          <a href="{{ $about['cta_btn1_url'] ?? home_url('/#kontak') }}"
             class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.98] text-[#1d1d1f] text-[16px] font-semibold px-8 py-4 rounded-full transition-all duration-150 shadow-md">
            {!! esc_html($about['cta_btn1_text'] ?? 'Mulai Konsultasi') !!}
          </a>
          @php
            $btn2_label = $about['cta_btn2_text'] ?? 'Baca Studi Kasus';
            $btn2_label = preg_replace('/(&rsaquo;|&gt;|&raquo;|›|>|»|\s)+$/u', '', html_entity_decode($btn2_label, ENT_QUOTES, 'UTF-8'));
          @endphp
          <a href="{{ $about['cta_btn2_url'] ?? home_url('/blog') }}"
             class="inline-flex items-center text-white/70 text-[16px] font-medium hover:text-white transition-colors gap-2 group">
            <span>{{ $btn2_label }}</span>
            <span class="group-hover:translate-x-1 transition-transform duration-150">&rsaquo;</span>
          </a>
        </div>
      </div>

      {{-- Right: Editorial Directory & Workshop Details (Col 5) --}}
      <div class="lg:col-span-5 border-t lg:border-t-0 lg:border-l border-white/[0.1] pt-12 lg:pt-0 lg:pl-16">
        <h3 class="text-[14px] font-semibold text-white/60 tracking-normal mb-8">
          {!! esc_html($about['info_title'] ?? 'Kantor Pusat & Workshop') !!}
        </h3>

        <div class="divide-y divide-white/[0.08]">
          
          <div class="pb-7">
            <p class="text-[13px] font-semibold text-[#6e9fd4] mb-1">Entitas Resmi</p>
            <p class="text-[17px] font-medium text-white leading-snug">
              {!! esc_html($c_entitas) !!}
            </p>
          </div>

          <div class="py-7">
            <p class="text-[13px] font-semibold text-white/40 mb-1">Alamat Workshop</p>
            <p class="text-[16px] font-medium text-white/90 leading-relaxed">
              {!! esc_html($c_alamat) !!}
            </p>
            <p class="text-[13px] text-white/50 mt-1">Fasilitas Riset, Desain Aerodinamis &amp; Manufaktur UAV</p>
          </div>

          <div class="py-7">
            <p class="text-[13px] font-semibold text-white/40 mb-1">Email Resmi</p>
            <a href="mailto:{{ esc_attr($c_email) }}" 
               class="text-[16px] font-medium text-white hover:text-[#6e9fd4] transition-colors block">
              {!! esc_html($c_email) !!}
            </a>
          </div>

          <div class="pt-7">
            <p class="text-[13px] font-semibold text-white/40 mb-1">Kontak &amp; Layanan Cepat</p>
            <div class="flex items-center gap-3 mt-1">
              <a href="tel:{{ preg_replace('/[^0-9+]/', '', $c_phone) }}" class="text-[16px] font-medium text-white hover:text-[#6e9fd4] transition-colors">
                {!! esc_html($c_phone) !!}
              </a>
              <span class="text-white/20">&middot;</span>
              <a href="{{ esc_url($c_wa_link) }}" target="_blank" rel="noopener" class="text-[13px] font-medium text-[#25D366] hover:underline inline-flex items-center gap-1">
                WhatsApp <span>&rsaquo;</span>
              </a>
            </div>
          </div>

        </div>
      </div>

    </div>

  </div>

  {{-- Full-width Map Media Block — exactly like drone detail hero image --}}
  <div class="w-full overflow-hidden border-t border-white/[0.08] relative" style="height: 520px; max-height: 600px;">
    <iframe 
      src="{{ esc_url($c_maps) }}" 
      width="100%" 
      height="100%" 
      style="border:0;" 
      allowfullscreen="" 
      loading="lazy" 
      referrerpolicy="strict-origin-when-cross-origin"
      title="Lokasi Full Drone Solutions Sleman Yogyakarta">
    </iframe>
  </div>
</section>

@endsection
