@extends('layouts.app')

@section('content')
@php
  // Baca dari CPT "drone" post meta
  $post_id  = get_the_ID();
  $name     = get_the_title();
  $tagline  = get_post_meta($post_id, 'drone_tagline',  true);
  $badge    = get_post_meta($post_id, 'drone_badge',    true) ?: 'Produk';
  $kategori = get_post_meta($post_id, 'drone_kategori', true) ?: 'Drone';
  $desc     = get_post_meta($post_id, 'drone_desc',     true);
  $forRaw   = get_post_meta($post_id, 'drone_for',      true);
  $forList  = array_filter(array_map('trim', explode("\n", $forRaw)));

  // Spesifikasi dari meta
  $specsMap = [
    'Kapasitas tangki'    => get_post_meta($post_id, 'drone_kapasitas', true),
    'Cakupan per jam'     => get_post_meta($post_id, 'drone_cakupan',  true),
    'Lebar semprot'       => get_post_meta($post_id, 'drone_lebar',    true),
    'Daya tahan baterai'  => get_post_meta($post_id, 'drone_baterai',  true),
    'Ketahanan'           => 'IP65',
    'TKDN'                => '44,85%',
    'Nozzle'              => get_post_meta($post_id, 'drone_nozzle',   true),
    'Software'            => 'Ground Control App',
  ];
  $specs = array_filter($specsMap);

  // Hero image: Featured Image utama
  $heroImg = get_the_post_thumbnail_url($post_id, 'full');
@endphp

<div class="bg-white pt-[52px]">

  {{-- ── HERO — Dark, tanpa grid kotak ────────────────────────── --}}
  <section class="bg-[#1d1d1f] pt-16 pb-0 overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

      {{-- Badge chips --}}
      <div class="flex flex-wrap items-center gap-3 mb-8">
        <span class="text-[12px] font-semibold text-white/40 tracking-wide border border-white/[0.12] rounded-full px-3.5 py-1">
          {{ $kategori }}
        </span>
        @if($badge)
        <span class="text-[12px] font-semibold text-[#6e9fd4] bg-[#0066cc]/15 rounded-full px-3.5 py-1">
          {{ $badge }}
        </span>
        @endif
      </div>

      {{-- Nama produk --}}
      <h1 class="text-[72px] sm:text-[100px] lg:text-[128px] font-semibold tracking-[-0.05em] text-white leading-[0.9] mb-8">
        {{ $name }}
      </h1>

      @if($tagline)
      <p class="text-[18px] sm:text-[20px] text-white/55 max-w-[520px] leading-[1.6] mb-10">
        {{ $tagline }}
      </p>
      @endif

      {{-- CTAs --}}
      <div class="flex flex-wrap gap-4 pb-16">
        <a href="{{ home_url('/#kontak') }}"
           class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150">
          Minta Penawaran
        </a>
        <a href="{{ home_url('/#kontak') }}"
           class="inline-flex items-center text-white/60 text-[15px] font-medium hover:text-white transition-colors gap-1">
          Jadwalkan Demo &rsaquo;
        </a>
      </div>
    </div>

    {{-- Hero image — full bleed --}}
    @if($heroImg)
    <div class="w-full overflow-hidden" style="max-height:620px;">
      <img src="{{ $heroImg }}"
           alt="{{ $name }} &mdash; FDS"
           class="w-full object-cover object-center"
           style="max-height:620px;">
    </div>
    @endif
  </section>

  {{-- ── SPECS ─────────────────────────────────────────────────── --}}
  @if(!empty($specs))
  @php
    $specs_img_meta = get_post_meta($post_id, 'drone_specs_img', true);
    $slug = get_post_field('post_name', $post_id);
    $specs_img = $specs_img_meta ?: ($imgSrc ?: "https://picsum.photos/seed/{$slug}-specs/1400/1000");
  @endphp
  <section class="bg-white pt-16 sm:pt-20 pb-16 sm:pb-20 border-t border-black/[0.06] relative z-10 overflow-visible">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 relative overflow-visible">

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-stretch relative overflow-visible">
        
        {{-- Left Column: Relative container where image is positioned absolute overlapping into black section below --}}
        <div class="lg:col-span-6 relative flex flex-col min-h-[320px]">
          <div>
            <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-3">Spesifikasi</p>
            <h2 class="text-[32px] sm:text-[40px] lg:text-[44px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-5">
              Direkayasa untuk<br>performa nyata.
            </h2>
            <p class="text-[15px] sm:text-[16px] text-[#515154] leading-relaxed max-w-xl lg:max-w-[480px] mb-6">
              {{ $desc ?: "Setiap spesifikasi $name divalidasi melalui ratusan jam uji lapangan di berbagai kondisi cuaca dan jenis lahan di Indonesia." }}
            </p>
          </div>

          {{-- Giant Transparent PNG Drone: Anchored cleanly right below description text --}}
          @if($specs_img)
          <div class="relative w-full h-0 select-none">
            <div class="mt-4 lg:mt-0 lg:absolute lg:top-2 xl:top-3 lg:-left-3 xl:-left-6 w-full lg:w-[125%] xl:w-[130%] max-w-[560px] lg:max-w-[660px] pointer-events-none z-20">
              <img src="{{ $specs_img }}" 
                   alt="{{ $name }} Spesifikasi" 
                   class="w-full h-auto object-contain object-left select-none drop-shadow-none lg:drop-shadow-[0_20px_35px_rgba(255,255,255,0.22)]">
            </div>
          </div>
          @endif
        </div>

        {{-- Right Column: Specifications Table (More compact width) --}}
        <div class="lg:col-span-6 lg:pt-[44px] divide-y divide-black/[0.06] relative z-10">
          @foreach($specs as $label => $value)
          <div class="py-4 sm:py-5 first:pt-0 first:pb-5 grid grid-cols-2 gap-4 sm:gap-6 items-baseline">
            <p class="text-[13px] sm:text-[14px] font-medium text-[#86868b] leading-tight">{!! wp_specialchars_decode($label) !!}</p>
            <p class="text-[15px] sm:text-[16px] font-semibold text-[#1d1d1f] leading-tight">{!! wp_specialchars_decode($value) !!}</p>
          </div>
          @endforeach
        </div>

      </div>
    </div>
  </section>
  @endif

  {{-- ── FOR WHOM ─────────────────────────────────────────────── --}}
  @if(!empty($forList))
  <section class="bg-[#1d1d1f] pt-24 sm:pt-32 pb-24 sm:pb-32 relative z-0">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div>
          <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-4">Untuk Siapa</p>
          <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-white leading-[1.1]">
            {{ $name }} cocok untuk Anda.
          </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach($forList as $usecase)
          <div class="bg-white/[0.06] border border-white/[0.08] rounded-2xl p-5 flex items-start gap-3">
            <div class="w-5 h-5 bg-[#0066cc]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
              <svg class="w-3 h-3 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-[14px] font-medium text-white/80 leading-snug">{{ $usecase }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  @endif

  {{-- ── STATS BAR ────────────────────────────────────────────── --}}
  <section class="bg-white py-16 border-t border-black/[0.06]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-10 text-center">
        <div>
          <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">IP65</p>
          <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">Proteksi Debu &amp; Air</p>
        </div>
        <div>
          <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">44,85%</p>
          <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">Nilai TKDN</p>
        </div>
        <div>
          <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">100%</p>
          <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">Software Bahasa Indonesia</p>
        </div>
        <div>
          <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">Garansi</p>
          <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">Purna Jual Resmi</p>
        </div>
      </div>
    </div>
  </section>

  {{-- ── CTA ─────────────────────────────────────────────────── --}}
  <section class="bg-[#1d1d1f] py-24">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 text-center">
      <h2 class="text-[36px] sm:text-[52px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5">
        Siap mengoperasikan {{ $name }}?
      </h2>
      <p class="text-[18px] text-white/60 max-w-[480px] mx-auto mb-8 leading-relaxed">
        Konsultasikan kebutuhan Anda dengan tim teknis FDS.
      </p>
      <div class="flex flex-wrap gap-4 justify-center">
        <a href="{{ home_url('/#kontak') }}"
           class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150">
          Hubungi Tim Sales
        </a>
        <a href="{{ home_url('/blog') }}"
           class="inline-flex items-center text-white/70 text-[15px] font-medium hover:text-white transition-colors gap-1">
          Baca studi kasus &rsaquo;
        </a>
      </div>
    </div>
  </section>

</div>
@endsection
