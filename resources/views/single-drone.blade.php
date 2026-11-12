@extends('layouts.app')

@section('content')
@php
  $post_id    = (int) get_the_ID();
  $preview_id = $post_id;
  if (is_preview()) {
      $autosave = wp_get_post_autosave($post_id);
      if ($autosave) {
          $preview_id = (int) $autosave->ID;
      }
  }

  $drone = function_exists('\App\fds_get_drone_detail_data')
      ? \App\fds_get_drone_detail_data($post_id, $preview_id)
      : [];

  $slug           = (string) ($drone['slug'] ?? get_post_field('post_name', $post_id));
  $modelKey       = (string) ($drone['model_key'] ?? strtolower($slug));
  $isFerto        = !empty($drone['is_ferto']);
  $hasSni         = !empty($drone['has_sni']);
  $cat_url        = (string) ($drone['cat_url'] ?? home_url('/produk/'));
  $heroMedia      = is_array($drone['hero_media'] ?? null) ? $drone['hero_media'] : [];
  $heroSrc        = (string) ($heroMedia['url'] ?? ($drone['hero_url'] ?? ''));
  $heroSrcset     = (string) ($heroMedia['srcset'] ?? ($drone['hero_srcset'] ?? ''));
  $specs_img      = (string) ($drone['specs_img'] ?? '');
  $cleanDroneName = function_exists('fds_clean_string')
      ? fds_clean_string((string) ($drone['name'] ?? get_the_title($preview_id ?: $post_id)))
      : (string) ($drone['name'] ?? get_the_title($preview_id ?: $post_id));

  $dui = function_exists('\App\fds_get_drone_detail_ui')
      ? \App\fds_get_drone_detail_ui()
      : [];
  $href_detail = static function ($u): string {
      return function_exists('\App\fds_produk_normalize_href')
          ? \App\fds_produk_normalize_href((string) $u)
          : home_url((string) $u);
  };
@endphp

@if(empty($drone))
  <div class="pt-[52px] bg-[#f5f5f7] min-h-[70vh]">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-20">
      <h1 class="text-[40px] font-semibold text-[#1d1d1f] mb-8">{{ fds_clean_string(get_the_title()) }}</h1>
      <div class="prose text-[18px] text-[#515154] leading-[1.7]">{!! get_the_content() !!}</div>
    </div>
  </div>

@else

  <div class="bg-white pt-[52px]">

    {{-- ── HERO — Dark split layout (ORIGINAL HERO STRUCTURE WITH ENHANCED BACKGROUND VECTOR) ─── --}}
    <section class="relative bg-gradient-to-br from-[#13abb9] via-[#0f808a] to-[#0a555c] flex flex-col justify-between overflow-hidden">

      {{-- Vector Background: Lekukan Gelombang Organik Fluida dengan Gradasi ke Gelap di Sisi Kanan --}}
      <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <svg class="absolute inset-0 w-full h-full object-cover" viewBox="0 0 1440 700" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
            {{-- Lapisan 1 (Terluar) — Biru di sisi kurva, bergradasi ke gelap di sisi kanan --}}
            <linearGradient id="hero-curve-fill1" x1="0%" y1="20%" x2="100%" y2="80%">
              <stop offset="0%" stop-color="#18d6e7" stop-opacity="0.16" />
              <stop offset="45%" stop-color="#0a555c" stop-opacity="0.08" />
              <stop offset="100%" stop-color="#0a555c" stop-opacity="0.95" />
            </linearGradient>

            {{-- Lapisan 2 (Sedang-Luar) — Bergradasi ke gelap di sisi kanan --}}
            <linearGradient id="hero-curve-fill2" x1="0%" y1="20%" x2="100%" y2="80%">
              <stop offset="0%" stop-color="#46deec" stop-opacity="0.25" />
              <stop offset="50%" stop-color="#0a555c" stop-opacity="0.12" />
              <stop offset="100%" stop-color="#0a555c" stop-opacity="0.85" />
            </linearGradient>

            {{-- Lapisan 3 (Tengah) — Bergradasi ke gelap di sisi kanan --}}
            <linearGradient id="hero-curve-fill3" x1="0%" y1="20%" x2="100%" y2="80%">
              <stop offset="0%" stop-color="#13abb9" stop-opacity="0.36" />
              <stop offset="55%" stop-color="#0f808a" stop-opacity="0.18" />
              <stop offset="100%" stop-color="#0a555c" stop-opacity="0.75" />
            </linearGradient>

            {{-- Lapisan 4 (Inti) — Biru Vibrant di awal, bergradasi ke gelap di sisi kanan --}}
            <linearGradient id="hero-curve-fill4" x1="0%" y1="20%" x2="100%" y2="80%">
              <stop offset="0%" stop-color="#18d6e7" stop-opacity="0.52" />
              <stop offset="55%" stop-color="#18d6e7" stop-opacity="0.26" />
              <stop offset="100%" stop-color="#0a555c" stop-opacity="0.65" />
            </linearGradient>
          </defs>

          <!-- Lapisan Lekukan Vektor Organik: Digeser Lebih ke Kanan, Kiri Biru Cerah, Kanan Bergradasi ke Gelap -->
          <path d="M380,0 C560,110 1160,160 1060,360 C980,520 900,620 800,700 L1440,700 L1440,0 Z" fill="url(#hero-curve-fill1)" />
          <path d="M600,0 C760,100 1260,150 1180,330 C1100,490 1020,600 940,700 L1440,700 L1440,0 Z" fill="url(#hero-curve-fill2)" />
          <path d="M820,0 C960,90 1340,140 1280,300 C1220,450 1160,570 1080,700 L1440,700 L1440,0 Z" fill="url(#hero-curve-fill3)" />
          <path d="M1040,0 C1160,80 1420,130 1360,270 C1300,410 1240,540 1200,700 L1440,700 L1440,0 Z" fill="url(#hero-curve-fill4)" />
        </svg>
      </div>

      {{-- Top: text block (STRUKTUR ASLI TIDAK DIUBAH) --}}
      <div class="relative z-10 max-w-[1400px] mx-auto px-6 lg:px-12 pt-14 sm:pt-16 w-full">
        {{-- Badge chips --}}
        <div class="flex flex-wrap items-start gap-3 mb-8 fds-hero-entrance fds-hero-delay-1">
          <a href="{!! esc_url($cat_url) !!}" class="inline-flex items-center text-[12px] font-semibold text-white/80 hover:text-white tracking-wide border border-white/[0.15] hover:border-white/35 bg-white/[0.04] rounded-full px-3.5 py-1 backdrop-blur-sm transition-colors">
            {{ fds_clean_string((string) ($drone['kategori'] ?? 'Agrikultur')) }}
          </a>
          @if(!empty($drone['badge']))
          <span class="inline-flex items-center text-[12px] font-semibold text-[#18d6e7] tracking-wide bg-[#0f808a]/20 border border-[#46deec]/30 rounded-full px-3.5 py-1 backdrop-blur-sm">
            {{ fds_clean_string((string) $drone['badge']) }}
          </span>
          @endif
        </div>

        {{-- Nama produk --}}
        <h1 class="text-[72px] sm:text-[100px] lg:text-[128px] font-semibold tracking-[-0.05em] text-white leading-[0.9] mb-8 drop-shadow-sm fds-hero-entrance fds-hero-delay-2">
          {{ fds_clean_string((string) $drone['name']) }}
        </h1>

        @if(!empty($drone['tagline']))
        <p class="text-[18px] sm:text-[20px] text-white/75 max-w-[580px] leading-[1.6] mb-10 fds-hero-entrance fds-hero-delay-3">
          {{ fds_clean_string((string) $drone['tagline']) }}
        </p>
        @endif

        {{-- CTAs --}}
        <div class="flex flex-wrap items-center gap-4 pb-8 sm:pb-10 fds-hero-entrance fds-hero-delay-4">
          <a href="{!! esc_url(home_url('/kontak/?produk=' . urlencode($slug) . '#hubungi-form')) !!}"
             class="inline-flex items-center bg-[#0f808a] hover:bg-[#0a555c] active:scale-[0.97] text-white text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150 shadow-md shadow-black/15">
            {{ $dui['hero_cta1_text'] ?? 'Minta Penawaran' }}
          </a>
          <a href="{!! esc_url(home_url('/bandingkan/?d1=' . $slug)) !!}"
             class="inline-flex items-center text-white/80 hover:text-white text-[15px] font-medium transition-colors gap-2 px-3 py-3 leading-none group">
            <span class="leading-none">{{ $dui['hero_cta2_text'] ?? 'Bandingkan model' }}</span>
            <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 6h12v12"/>
              <path d="M6 18 18 6"/>
            </svg>
          </a>
        </div>
      </div>

      {{-- Hero image (Single Canonical Media Resolver — Identik 1:1 dengan Kartu Katalog) --}}
      <div class="relative z-10 w-full mt-auto overflow-hidden flex items-end justify-center leading-none max-h-[240px] sm:max-h-[260px] lg:max-h-[260px] xl:max-h-[400px] 2xl:max-h-[680px] fds-hero-entrance fds-hero-delay-5">
        <img src="{!! esc_url($heroSrc) !!}"
             @if($heroSrcset !== '') srcset="{{ $heroSrcset }}" sizes="(max-width: 768px) 100vw, 1200px" @endif
             alt="{{ $cleanDroneName }} — Drone Pertanian & Industri PT Karya Solusi Angkasa FDS"
             width="1200"
             height="720"
             loading="eager"
             fetchpriority="high"
             data-no-lazy="1"
             decoding="sync"
             class="w-full h-auto object-cover object-center block max-h-[240px] sm:max-h-[260px] lg:max-h-[260px] xl:max-h-[400px] 2xl:max-h-[680px] no-lazy">
      </div>

    </section>

    {{-- ── SPECS ─────────────────────────────────────────────────── --}}
    @if(!empty($drone['specs']) || !empty($drone['desc']))
    <section class="bg-white pt-16 sm:pt-20 pb-10 sm:pb-12 relative z-20 overflow-visible">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 relative overflow-visible">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start relative overflow-visible">
          
          {{-- Left Column: Header, Desc, & Big Transparent Product Image (Col 6) --}}
          <x-reveal type="fade-right" class="lg:col-span-6 flex flex-col relative overflow-visible z-20">
            <div>
              <div class="flex items-center justify-between mb-3">
                <p class="text-[13px] font-semibold text-[#0f808a] tracking-wide">{{ $dui['specs_eyebrow'] ?? 'Spesifikasi' }}</p>
                <a href="{!! esc_url(home_url('/bandingkan/?d1=' . $slug)) !!}" class="text-[13px] font-semibold text-[#0f808a] hover:underline flex items-center gap-1.5 leading-none group">
                  <span class="leading-none">{{ $dui['specs_compare_link'] ?? 'Bandingkan spesifikasi' }}</span>
                  <svg class="w-[18px] h-[18px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 6h12v12"/>
                    <path d="M6 18 18 6"/>
                  </svg>
                </a>
              </div>
              <h2 class="text-[32px] sm:text-[40px] lg:text-[44px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-5">
                {!! wp_kses((string) ($dui['specs_heading'] ?? 'Direkayasa untuk <br>performa nyata.'), ['br' => []]) !!}
              </h2>
              <p class="text-[15px] sm:text-[16px] text-[#515154] leading-relaxed max-w-xl mb-4">
                {{ fds_clean_string((string) ($drone['desc'] ?: str_replace('{drone_name}', $cleanDroneName, (string) ($dui['specs_desc_fallback'] ?? "Setiap spesifikasi {$cleanDroneName} divalidasi melalui ratusan jam uji lapangan di berbagai kondisi cuaca dan jenis lahan di Indonesia.")))) }}
              </p>
            </div>

            {{-- Big Transparent Specs Drone Image: Offset melayang presisi, 100% utuh tanpa terpotong --}}
            @if($specs_img)
            <div class="mt-4 sm:mt-6 w-full select-none lg:h-0 lg:relative flex justify-center lg:block overflow-visible">
              <div class="relative lg:absolute lg:top-0 lg:left-0 lg:-ml-2 xl:-ml-4 w-full max-w-[540px] lg:max-w-[620px] pointer-events-none z-30 flex justify-center lg:block mx-auto lg:mx-0 overflow-visible">
                <img src="{!! esc_url($specs_img) !!}" 
                     alt="Spesifikasi Teknis {{ $cleanDroneName }} — Full Drone Solutions" 
                     loading="lazy"
                     decoding="async"
                     class="w-full h-auto object-contain object-center lg:object-left select-none drop-shadow-[0_20px_45px_rgba(255,255,255,0.35)] lg:drop-shadow-[0_30px_60px_rgba(255,255,255,0.42)]">
              </div>
            </div>
            @endif
          </x-reveal>

          {{-- Right Column: Specifications Table (Col 6) --}}
          <x-reveal-group :step="50" class="lg:col-span-6 divide-y divide-black/[0.06] pt-2 lg:pt-8 relative z-10">
            @if(!empty($drone['specs']))
              @foreach($drone['specs'] as [$label, $value])
              @php
                $lblClean = trim(fds_clean_string((string) $label));
                if (
                  $lblClean === 'Kapasitas Tangki' || 
                  $lblClean === 'Kapasitas Tangki / Payload' || 
                  $lblClean === 'Kapasitas Payload' || 
                  $lblClean === 'Kapasitas Payload Biji' || 
                  stripos($lblClean, 'kapasitas tangki') === 0 || 
                  stripos($lblClean, 'kapasitas payload') === 0
                ) {
                  $lblClean = 'Payload';
                }
              @endphp
              <div class="py-4 sm:py-5 first:pt-0 grid grid-cols-2 gap-4 sm:gap-6 items-baseline">
                <p class="text-[13px] sm:text-[14px] font-medium text-[#86868b] leading-tight">{{ $lblClean }}</p>
                <p class="text-[15px] sm:text-[16px] font-semibold text-[#1d1d1f] leading-tight">{{ fds_clean_string((string) $value) }}</p>
              </div>
              @endforeach
            @else
              <div class="py-4 text-[#86868b] text-[14px]">{{ $dui['specs_empty_text'] ?? 'Spesifikasi detail akan segera diperbarui.' }}</div>
            @endif
          </x-reveal-group>

        </div>
      </div>
    </section>
    @endif

    {{-- ── DOWNLOAD BROCHURE SECTION (CLEAN & MINIMALIST) ─────────────── --}}
    @php
      $brochure_enable        = get_option('fds_brochure_enable', '1');
      $brochure_layout        = get_option('fds_brochure_layout', 'horizontal');
      $raw_title              = fds_clean_string((string) get_option('fds_brochure_title', 'Brosur Spesifikasi {drone_name}'));
      $brochure_title         = str_replace('{drone_name}', $cleanDroneName, $raw_title);
      $brochure_desc_initial  = fds_clean_string((string) get_option('fds_brochure_desc', 'Masukkan email Anda untuk mengunduh brosur spesifikasi teknis resmi (PDF).'));
      $brochure_desc_ready    = fds_clean_string((string) get_option('fds_brochure_desc_ready', 'Dokumen spesifikasi teknis resmi (PDF) telah siap untuk diunduh.'));
      $brochure_ph            = fds_clean_string((string) get_option('fds_brochure_placeholder', 'Masukkan email Anda...'));
      $brochure_submit        = fds_clean_string((string) get_option('fds_brochure_submit_text', 'Kirim Email'));
      $brochure_btn           = fds_clean_string((string) get_option('fds_brochure_button_text', 'Download Brosur (PDF)'));
      $brochure_file_url      = get_post_meta($post_id, 'drone_brosur_url', true) ?: get_option('fds_brochure_default_pdf', '');
    @endphp

    @if($brochure_enable === '1')
    <section id="unduh-brosur" class="bg-white py-16 sm:py-20 lg:py-24 relative z-0">
      <x-reveal class="max-w-[1400px] mx-auto px-6 lg:px-12 relative z-0">
        <x-modal-brochure
          :drone-id="$post_id"
          :drone-name="$cleanDroneName"
          :title="$brochure_title"
          :desc-initial="$brochure_desc_initial"
          :desc-ready="$brochure_desc_ready"
          :placeholder="$brochure_ph"
          :submit-text="$brochure_submit"
          :btn-text="$brochure_btn"
          :file-url="$brochure_file_url"
        />
      </x-reveal>
    </section>
    @endif

    {{-- ── APLIKASI & USE-CASES (AERODYNAMIC FLOW STRATA VECTOR BACKGROUND) ─────── --}}
    @php
      $usecases_list = !empty($drone['for']) && is_array($drone['for']) ? $drone['for'] : ($dui['uc_fallback_items'] ?? [
        'Kelompok tani & Gapoktan — Titik temu antara kapasitas operasional dan efisiensi investasi.',
        'Operasional kebun komersial — Aplikasi semprot atau akuisisi data udara lebih terukur sesuai SOP.',
        'Korporasi & pengadaan instansi — Dukungan pelatihan operator, garansi resmi, dan demonstrasi unit.',
        'Suku cadang asli terjamin — Ketersediaan komponen cepat dari workshop Sleman, Yogyakarta.',
      ]);
    @endphp

    <section id="aplikasi-usecases" class="relative bg-gradient-to-br from-[#13abb9] via-[#0f808a] to-[#0a555c] pt-24 sm:pt-32 pb-24 sm:pb-32 overflow-hidden z-10">
      
      {{-- Vector Background: Aerodynamic Flow Strata Lines (Identik dengan Hero Section Halaman Tentang Kami) --}}
      <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <svg class="w-full h-full" viewBox="0 0 1440 600" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
          <defs>
            {{-- Base Canvas Gradient --}}
            <linearGradient id="sd_flow_base" x1="720" y1="0" x2="720" y2="600" gradientUnits="userSpaceOnUse">
              <stop stop-color="#13abb9"/>
              <stop offset="0.65" stop-color="#0f808a"/>
              <stop offset="1" stop-color="#0a555c"/>
            </linearGradient>

            {{-- Wave Gradients --}}
            <linearGradient id="sd_flow_grad1" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#0a555c" stop-opacity="0.22"/>
              <stop offset="50%" stop-color="#0a555c" stop-opacity="0.10"/>
              <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
            </linearGradient>

            <linearGradient id="sd_flow_grad2" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#0f808a" stop-opacity="0.28"/>
              <stop offset="50%" stop-color="#0a555c" stop-opacity="0.14"/>
              <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
            </linearGradient>

            <linearGradient id="sd_flow_grad3" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#46deec" stop-opacity="0.34"/>
              <stop offset="50%" stop-color="#0a555c" stop-opacity="0.16"/>
              <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
            </linearGradient>

            <linearGradient id="sd_flow_grad4" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#13abb9" stop-opacity="0.40"/>
              <stop offset="50%" stop-color="#0f808a" stop-opacity="0.18"/>
              <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
            </linearGradient>

            <linearGradient id="sd_flow_grad5" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#18d6e7" stop-opacity="0.46"/>
              <stop offset="50%" stop-color="#46deec" stop-opacity="0.22"/>
              <stop offset="100%" stop-color="#0a555c" stop-opacity="0.0"/>
            </linearGradient>

            {{-- Bottom Dark Fade to seamlessly blend into section below --}}
            <linearGradient id="sd_bottom_fade" x1="0" y1="320" x2="0" y2="600" gradientUnits="userSpaceOnUse">
              <stop offset="0%" stop-color="#0a555c" stop-opacity="0"/>
              <stop offset="50%" stop-color="#0a555c" stop-opacity="0.6"/>
              <stop offset="100%" stop-color="#0a555c" stop-opacity="1"/>
            </linearGradient>
          </defs>

          <g>
            <!-- Base Canvas -->
            <rect width="1440" height="600" fill="url(#sd_flow_base)"/>

            <!-- 1. Wave Layer 1 (U-Cradle: Kiri -50px, Tengah 360px, Kanan -50px) -->
            <path d="M-50,-50 C250,40 500,360 720,360 C940,360 1190,40 1490,-50 L1490,700 L-50,700 Z" fill="url(#sd_flow_grad1)"/>

            <!-- 2. Wave Layer 2 (Samping +135px, Tengah +50px) -->
            <path d="M-50,85 C250,160 500,410 720,410 C940,410 1190,160 1490,85 L1490,700 L-50,700 Z" fill="url(#sd_flow_grad2)"/>

            <!-- 3. Wave Layer 3 (Samping +135px, Tengah +50px) -->
            <path d="M-50,220 C250,280 500,460 720,460 C940,460 1190,280 1490,220 L1490,700 L-50,700 Z" fill="url(#sd_flow_grad3)"/>

            <!-- 4. Wave Layer 4 (Samping +135px, Tengah +55px) -->
            <path d="M-50,355 C250,400 500,515 720,515 C940,515 1190,400 1490,355 L1490,700 L-50,700 Z" fill="url(#sd_flow_grad4)"/>

            <!-- 5. Wave Layer 5 (Samping +135px, Tengah +55px, Inti Depan) -->
            <path d="M-50,490 C250,510 500,570 720,570 C940,570 1190,510 1490,490 L1490,700 L-50,700 Z" fill="url(#sd_flow_grad5)"/>

            <!-- Bottom Blend Gradient Overlay -->
            <rect y="320" width="1440" height="280" fill="url(#sd_bottom_fade)"/>
          </g>
        </svg>
      </div>

      <div class="max-w-[1400px] mx-auto px-6 lg:px-12 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
          <x-reveal type="fade-right">
            <span class="text-[12px] font-semibold text-white/80 tracking-wide mb-3 block">{{ $dui['uc_eyebrow'] ?? 'Aplikasi & Use-Cases' }}</span>
            <h2 class="text-[36px] sm:text-[46px] lg:text-[48px] font-semibold tracking-[-0.03em] text-white leading-[1.12]">
              {{ str_replace('{drone_name}', $cleanDroneName, (string) ($dui['uc_heading_tpl'] ?? '{drone_name} cocok untuk Anda.')) }}
            </h2>
          </x-reveal>
          <div class="relative">
            <x-reveal-group :step="70" class="grid grid-cols-1 sm:grid-cols-2 gap-4 relative z-10">
              @foreach($usecases_list as $usecase)
              <div class="group relative bg-white/[0.06] hover:bg-white/[0.11] backdrop-blur-xl backdrop-saturate-150 border border-white/15 hover:border-white/30 rounded-2xl p-5 flex items-start gap-3.5 transition-all duration-200 shadow-[0_10px_24px_-6px_rgba(0,0,0,0.35),inset_0_1px_1px_0_rgba(255,255,255,0.3)] hover:shadow-[0_16px_32px_-8px_rgba(0,0,0,0.45),inset_0_1px_1.2px_0_rgba(255,255,255,0.45)] hover:-translate-y-0.5 overflow-hidden">
                {{-- Top Specular Edge Sheen --}}
                <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/50 to-transparent pointer-events-none opacity-60 group-hover:opacity-100 transition-opacity"></div>
                <div class="w-5 h-5 bg-white/10 text-white rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 border border-white/20">
                  <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-[14px] font-medium text-white/90 leading-snug">{{ fds_clean_string((string) $usecase) }}</p>
              </div>
              @endforeach
            </x-reveal-group>
          </div>
        </div>
      </div>
    </section>

    {{-- ── STATS BAR ────────────────────────────────────────────── --}}
    @php
      $stats_list = [];
      for ($si = 1; $si <= 4; $si++) {
          $sNum = trim((string)($drone["stat{$si}_num"] ?? ''));
          $sLbl = trim((string)($drone["stat{$si}_lbl"] ?? ''));
          if ($sNum !== '' || $sLbl !== '') {
              $stats_list[] = [
                  'num' => $sNum,
                  'lbl' => $sLbl,
              ];
          }
      }
      $total_stats = count($stats_list);
    @endphp

    @if($total_stats > 0)
    <section class="bg-white py-16">
      <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
        <x-reveal-group :step="100" class="grid grid-cols-2 {{ $total_stats === 1 ? 'md:grid-cols-1 max-w-sm mx-auto' : ($total_stats === 2 ? 'md:grid-cols-2 max-w-2xl mx-auto' : ($total_stats === 3 ? 'md:grid-cols-3 max-w-4xl mx-auto' : 'md:grid-cols-4')) }} gap-10 text-center">
          @foreach($stats_list as $st)
          <div>
            @if(!empty($st['num']))
            <p class="text-[40px] font-semibold tracking-[-0.04em] text-[#1d1d1f]">{{ fds_clean_string((string) $st['num']) }}</p>
            @endif
            @if(!empty($st['lbl']))
            <p class="text-[12px] font-semibold text-[#86868b] tracking-wide mt-1">{{ fds_clean_string((string) $st['lbl']) }}</p>
            @endif
          </div>
          @endforeach
        </x-reveal-group>
      </div>
    </section>
    @endif

    {{-- ── PANDUAN TEKNIS & FAQ RESMI (VISIBLE SSR CONTENT) ─────── --}}
    @php
      $raw_guide_body = trim((string) get_post_field('post_content', $preview_id ?: $post_id));
      $has_legacy_guide_marker = ($raw_guide_body !== '') && (
          stripos($raw_guide_body, 'fds-seo-spec-table') !== false ||
          preg_match('/(Best UAV|sejak\s*(?:tahun\s*)?2012|IP\s?(?:54|67|68)|\bFLIR\b|FDS STATION|>\s*50%|Bappenas)/i', $raw_guide_body) ||
          (!$isFerto && preg_match('/TKDN|SNI\s*9199:2023/i', $raw_guide_body)) ||
          ($isFerto && !$hasSni && stripos($raw_guide_body, 'SNI 9199:2023') !== false)
      );
      if ($raw_guide_body !== '' && !$has_legacy_guide_marker) {
          $drone_guide_html = apply_filters('the_content', $raw_guide_body);
      } else {
          $drone_guide_html = function_exists('App\fds_generate_drone_seo_content')
              ? \App\fds_generate_drone_seo_content($post_id)
              : (function_exists('fds_generate_drone_seo_content') ? fds_generate_drone_seo_content($post_id) : '');
      }
      if (is_string($drone_guide_html) && $drone_guide_html !== '') {
          $drone_guide_html = preg_replace('/<(\/?)h1\b/i', '<$1h2', $drone_guide_html);
      }
    @endphp
    @if(!empty($drone_guide_html))
    <section id="panduan-teknis" class="bg-[#f5f5f7] py-16 sm:py-24 border-t border-black/[0.06]">
      <div class="max-w-[1100px] mx-auto px-6 lg:px-12">
        <p class="text-[13px] font-semibold text-[#0f808a] tracking-wide mb-3">{{ $dui['guide_eyebrow'] ?? 'Panduan Teknis & Tanya Jawab' }}</p>
        <div class="fds-drone-guide">
          {!! $drone_guide_html !!}
        </div>
      </div>
    </section>
    @endif

    {{-- ── CTA (EKOSISTEM VECTOR BACKGROUND) ─────────────────────── --}}
    <section class="relative bg-gradient-to-br from-[#13abb9] via-[#0f808a] to-[#0a555c] py-24 sm:py-32 lg:py-36 overflow-hidden">
      
      {{-- Organic Wave Geometry Vector Background (Matching Ekosistem Bento Card) --}}
      <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <svg class="w-full h-full" viewBox="0 0 398 96" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
          <g>
            <rect width="398" height="96" fill="url(#paint0_drone_cta_eko)"/>
            <path d="M-18.2893 -6.32697C-136.964 10.9814 -24.6036 35.4983 -46.9639 64.4209C-70.1751 94.444 4.76287 155.377 4.76287 155.377L406.77 160.338L455.686 19.5816C455.686 19.5816 486.61 10.9501 346.048 50.0886C205.485 89.2271 100.385 -23.6353 -18.2893 -6.32697Z" fill="url(#paint1_drone_cta_eko)"/>
            <path d="M-87.4837 43.4736C-65.1233 14.551 -71.703 -9.63438 27.815 2.49305C127.333 14.6205 180.184 94.5513 338.738 73.6039C402.333 65.2021 433.365 58.4269 447.725 53.3251L453.437 41.8199C453.437 41.8199 469.167 45.7069 447.725 53.3251L389.303 171L-53.1488 161.803C-53.1488 161.803 -110.695 73.4967 -87.4837 43.4736Z" fill="url(#paint2_drone_cta_eko)"/>
            <path d="M52.5537 76.3607C17.9455 66.8521 -56.751 53.9736 -56.751 53.9736L-46.402 162.355L187.493 166.765C187.493 166.765 114.012 93.2462 52.5537 76.3607Z" fill="url(#paint3_drone_cta_eko)"/>
          </g>
          <defs>
            <linearGradient id="paint0_drone_cta_eko" x1="199" y1="0" x2="199" y2="96" gradientUnits="userSpaceOnUse">
              <stop stop-color="#0a555c"/>
              <stop offset="0.915" stop-color="#0a555c"/>
            </linearGradient>
            <linearGradient id="paint1_drone_cta_eko" x1="192.438" y1="-8.14648" x2="192.438" y2="160.338" gradientUnits="userSpaceOnUse">
              <stop stop-color="#18d6e7" stop-opacity="0.40"/>
              <stop offset="0.915" stop-color="#0f808a" stop-opacity="0.15"/>
            </linearGradient>
            <linearGradient id="paint2_drone_cta_eko" x1="183" y1="-0.710449" x2="183" y2="171" gradientUnits="userSpaceOnUse">
              <stop stop-color="#46deec" stop-opacity="0.35"/>
              <stop offset="0.49" stop-color="#0f808a" stop-opacity="0.22"/>
              <stop offset="0.9999" stop-color="#0a555c"/>
            </linearGradient>
            <linearGradient id="paint3_drone_cta_eko" x1="65.3711" y1="53.9736" x2="65.3711" y2="166.765" gradientUnits="userSpaceOnUse">
              <stop stop-color="#75e6f0" stop-opacity="0.40"/>
              <stop offset="0.49" stop-color="#18d6e7" stop-opacity="0.25"/>
              <stop offset="0.9999" stop-color="#0a555c"/>
            </linearGradient>
          </defs>
        </svg>
      </div>

      <x-reveal-group :step="90" class="max-w-[1400px] mx-auto px-6 lg:px-12 text-center relative z-10">
        <h2 class="text-[36px] sm:text-[48px] lg:text-[54px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5 drop-shadow-sm">
          {{ str_replace('{drone_name}', $cleanDroneName, (string) ($dui['cta_heading_tpl'] ?? 'Siap mengoperasikan {drone_name}?')) }}
        </h2>
        <p class="text-[16px] sm:text-[18px] lg:text-[19px] text-white/80 max-w-[540px] mx-auto mb-9 leading-relaxed">
          {{ $dui['cta_desc'] ?? 'Konsultasikan kebutuhan misi Anda dengan tim teknis PT Karya Solusi Angkasa (FDS). Demo unit dan konsultasi teknis tersedia di Yogyakarta.' }}
        </p>
        <div class="flex flex-wrap gap-4 items-center justify-center">
          <a href="{!! esc_url(home_url('/kontak/?produk=' . urlencode($slug) . '#hubungi-form')) !!}"
             class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.98] text-[#0f808a] text-[16px] font-semibold px-8 py-4 rounded-full transition-all duration-150 shadow-lg shadow-black/20">
            {{ $dui['cta_primary_text'] ?? 'Hubungi Tim Sales' }}
          </a>
          <a href="{!! esc_url($href_detail($dui['cta_secondary_url'] ?? '/blog/')) !!}"
             class="inline-flex items-center text-white/80 hover:text-white text-[16px] font-medium transition-colors gap-2 px-4 py-3 leading-none group">
            <span class="leading-none">{{ $dui['cta_secondary_text'] ?? 'Baca studi kasus' }}</span>
            <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 6h12v12"/>
              <path d="M6 18 18 6"/>
            </svg>
          </a>
        </div>
      </x-reveal-group>
    </section>

  </div>

@endif
@endsection
