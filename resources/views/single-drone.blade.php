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

  $cln = static function ($v): string {
      $s = (string) $v;
      $s = function_exists('fds_clean_string') ? fds_clean_string($s) : html_entity_decode(wp_specialchars_decode($s, ENT_QUOTES), ENT_QUOTES | ENT_HTML5, 'UTF-8');
      return function_exists('fds_scrub_legacy_text') ? fds_scrub_legacy_text($s) : $s;
  };
  $arrow_svg = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>';
@endphp

@if(empty($drone))
  <div class="pt-[52px] bg-white min-h-[70vh]">
    <div class="fds-container" style="padding-block:80px;">
      <h1 class="fds-h1" style="margin-bottom:24px;">{{ $cln(get_the_title()) }}</h1>
      <div class="fds-prose fds-body">{!! get_the_content() !!}</div>
    </div>
  </div>
@else
@php
  $slug           = (string) ($drone['slug'] ?? get_post_field('post_name', $post_id));
  $modelKey       = (string) ($drone['model_key'] ?? strtolower($slug));
  $isFerto        = !empty($drone['is_ferto']);
  $hasSni         = !empty($drone['has_sni']);
  $cat_url        = (string) ($drone['cat_url'] ?? home_url('/produk/'));
  $heroMedia      = is_array($drone['hero_media'] ?? null) ? $drone['hero_media'] : [];
  $heroSrc        = (string) ($heroMedia['url'] ?? ($drone['hero_url'] ?? ''));
  $heroSrcset     = (string) ($heroMedia['srcset'] ?? ($drone['hero_srcset'] ?? ''));
  $specs_img      = (string) ($drone['specs_img'] ?? '');
  $cleanDroneName = $cln($drone['name'] ?? get_the_title($preview_id ?: $post_id));

  $dui = function_exists('\App\fds_get_drone_detail_ui') ? \App\fds_get_drone_detail_ui() : [];
  $href_detail = static function ($u): string {
      return function_exists('\App\fds_produk_normalize_href')
          ? \App\fds_produk_normalize_href((string) $u)
          : home_url((string) $u);
  };

  $gd = function_exists('\App\fds_get_drone_gallery_and_faqs')
      ? \App\fds_get_drone_gallery_and_faqs($post_id, $preview_id, $drone)
      : [];
  $stats4      = $gd['stats4'] ?? [];
  $specs_rest  = $gd['specs_rest'] ?? [];
  $gallery     = $gd['gallery'] ?? [];
  $faqs        = $gd['faqs'] ?? [];
  $model_base  = $gd['model_base'] ?? $cleanDroneName;

  $heroAltCategory = match (true) {
      $modelKey === 'deltav' => 'Drone Pemetaan Fixed-Wing VTOL',
      $modelKey === 'multipurpose' => 'Platform UAV Inspeksi Modular',
      $modelKey === 'delfro' || stripos((string) ($drone['kategori'] ?? ''), 'kargo') !== false => 'Drone Kargo Logistik Otonom',
      $isFerto || stripos((string) ($drone['kategori'] ?? ''), 'agrikultur') !== false => 'Drone Pertanian Presisi',
      default => $cln($drone['kategori'] ?? 'UAV Industri'),
  };

  $usecases_list = !empty($drone['for']) && is_array($drone['for']) ? $drone['for'] : ($dui['uc_fallback_items'] ?? [
      'Kelompok tani & Gapoktan — Titik temu antara kapasitas operasional dan efisiensi investasi.',
      'Operasional kebun komersial — Aplikasi semprot atau akuisisi data udara lebih terukur sesuai SOP.',
      'Korporasi & pengadaan instansi — Dukungan pelatihan operator, garansi resmi, dan demonstrasi unit.',
      'Suku cadang asli terjamin — Ketersediaan komponen cepat dari workshop Sleman, Yogyakarta.',
  ]);
@endphp

    {{-- 1. HERO — FULL-BLEED CINEMA STAGE WITH DEEP-TEAL SCRIM & SIGNATURE WAVE --}}
    <section class="fds-cinema-hero fds-drone-hero">
      <img src="{!! esc_url($heroSrc) !!}"
           @if($heroSrcset !== '') srcset="{{ $heroSrcset }}" sizes="100vw" @endif
           alt="{{ $cleanDroneName }} — {{ $heroAltCategory }} PT Karya Solusi Angkasa FDS"
           width="1920"
           height="1080"
           loading="eager"
           fetchpriority="high"
           decoding="sync"
           data-no-lazy="1"
           class="fds-cinema-bg fds-drone-hero-bg">
      <div class="fds-drone-hero-scrim" aria-hidden="true"></div>
      <x-fds-wave type="quiet" :uid="'drone-hero-' . $slug" />

      <div class="fds-container">
        <div class="fds-drone-hero-content fds-hero-entrance fds-hero-delay-1">
          <h1 class="fds-h1 fds-h1--soft fds-drone-hero-title">
            {{ $cleanDroneName }}
          </h1>

          @if(!empty($drone['tagline']))
            <p class="fds-lead fds-drone-hero-lead">{{ $cln($drone['tagline']) }}</p>
          @endif
        </div>
      </div>
    </section>

    <div class="bg-white">

    {{-- 2. IKHTISAR HARDWARE — CENTERED APPLE / DJI KEYNOTE STAGE (DESC + GIANT CUTOUT + KPI ANCHORS) --}}
    @if(!empty($stats4) || $specs_img !== '' || !empty($drone['desc']))
    <section id="ikhtisar" class="fds-sec fds-bg-white fds-drone-keynote">
      <div class="fds-container">
        <div class="fds-drone-keynote-head" data-aos="fade-up">
          <h2 class="fds-h2">
            {!! wp_kses((string) ($dui['specs_heading'] ?? 'Direkayasa untuk <br>performa nyata.'), ['br' => []]) !!}
          </h2>
          <p class="fds-lead fds-drone-keynote-lead">
            {{ $cln($drone['desc'] ?: str_replace('{drone_name}', $cleanDroneName, (string) ($dui['specs_desc_fallback'] ?? "Setiap spesifikasi {$cleanDroneName} divalidasi melalui ratusan jam uji lapangan di berbagai kondisi cuaca dan jenis lahan di Indonesia."))) }}
          </p>
        </div>

        @if($specs_img !== '')
          <div class="fds-drone-keynote-stage" data-aos="zoom-in" data-aos-delay="80">
            <img src="{!! esc_url($specs_img) !!}"
                 alt="Tampilan teknis {{ $cleanDroneName }} — Full Drone Solutions"
                 loading="lazy"
                 decoding="async"
                 width="960"
                 height="640">
          </div>
        @endif

        @if(!empty($stats4))
          <div class="fds-specs-kpi-band fds-specs-kpi-band--center is-{{ min(4, max(1, count($stats4))) }}" data-reveal-group data-reveal-step="80" data-reveal-modulo="4">
            @foreach($stats4 as $st)
              <div class="fds-specs-kpi-item">
                @if(!empty($st['num']))
                  <span class="fds-stat-balanced-num">{{ $st['num'] }}</span>
                @endif
                @if(!empty($st['lbl']))
                  <span class="fds-stat-balanced-lbl">{{ $st['lbl'] }}</span>
                @endif
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </section>
    @endif

    {{-- 3. SPESIFIKASI TEKNIS — APPLE / DJI STICKY 5/7 DATASHEET --}}
    @if(!empty($specs_rest))
    <section id="spesifikasi" class="fds-sec fds-bg-light scroll-mt-24">
      <div class="fds-container fds-sticky-split">
        <div class="fds-sticky-col">
          <div data-aos="fade-up">
            <h2 class="fds-h2">{{ $cln($dui['specs_table_heading'] ?? 'Spesifikasi Teknis') ?: 'Spesifikasi Teknis' }}</h2>
            <p class="fds-lead" style="margin-top:12px;">{{ $cleanDroneName }}</p>
            <div style="margin-top:20px;">
              <a href="{!! esc_url(home_url('/bandingkan/?d1=' . urlencode($slug))) !!}" class="fds-text-link">
                {{ $cln($dui['specs_compare_link'] ?? 'Bandingkan spesifikasi') }} {!! $arrow_svg !!}
              </a>
            </div>
          </div>
        </div>

        <div class="fds-specs-balanced-content" data-aos="fade-up" data-aos-delay="80">
          <dl class="fds-specs-balanced-list">
            @foreach($specs_rest as [$rLbl, $rVal])
              <div class="fds-specs-balanced-item">
                <dt class="fds-specs-balanced-label">{{ $rLbl }}</dt>
                <dd class="fds-specs-balanced-value">{{ $rVal }}</dd>
              </div>
            @endforeach
          </dl>
        </div>
      </div>
    </section>
    @endif

    {{-- 4. UNDUH BROSUR + APLIKASI & USE CASES + GALERI UJI LAPANGAN (WHITE SURFACE) --}}
    @php
      $brochure_enable        = get_option('fds_brochure_enable', '1');
      $raw_title              = $cln(get_option('fds_brochure_title', 'Brosur Spesifikasi {drone_name}'));
      $brochure_title         = str_replace('{drone_name}', $cleanDroneName, $raw_title);
      $brochure_desc_initial  = $cln(get_option('fds_brochure_desc', 'Masukkan email Anda untuk mengunduh brosur spesifikasi teknis resmi (PDF).'));
      $brochure_desc_ready    = $cln(get_option('fds_brochure_desc_ready', 'Dokumen spesifikasi teknis resmi (PDF) telah siap untuk diunduh.'));
      $brochure_ph            = $cln(get_option('fds_brochure_placeholder', 'Masukkan email bisnis Anda...'));
      $brochure_submit        = $cln(get_option('fds_brochure_submit_text', 'Kirim Email'));
      $brochure_btn           = $cln(get_option('fds_brochure_button_text', 'Download Brosur (PDF)'));
      $brochure_file_url      = get_post_meta($post_id, 'drone_brosur_url', true) ?: get_option('fds_brochure_default_pdf', '');
    @endphp
    <section id="aplikasi-usecases" class="fds-sec fds-bg-white">
      <div class="fds-container">
        @if($brochure_enable === '1')
          <div id="unduh-brosur" class="relative z-0" style="margin-bottom:72px;">
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
          </div>
        @endif

        @if(!empty($usecases_list))
        <div class="fds-story-split fds-story-split--media-left" @if(!empty($gallery)) style="margin-bottom:72px;" @endif>
          <figure class="fds-media-stage" data-aos="fade-up">
            <img src="{!! esc_url($heroSrc) !!}"
                 @if($heroSrcset !== '') srcset="{{ $heroSrcset }}" sizes="(max-width: 1024px) 100vw, 640px" @endif
                 alt="Operasi lapangan {{ $cleanDroneName }} — Full Drone Solutions"
                 loading="lazy"
                 decoding="async"
                 width="1000"
                 height="625">
          </figure>
          <div data-aos="fade-up" data-aos-delay="90">
            <h2 class="fds-h2" style="margin-bottom:24px;">
              {{ $cln(str_replace('{drone_name}', $cleanDroneName, (string) ($dui['uc_heading_tpl'] ?? '{drone_name} cocok untuk Anda.'))) }}
            </h2>
            <div class="fds-hairline-list">
              @foreach($usecases_list as $usecase)
                <div class="fds-hairline-row">
                  <p class="fds-body" style="margin:0;color:var(--fds-ink-primary);">{{ $cln($usecase) }}</p>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif

        @if(!empty($gallery))
          @php
            $gallery_hd_tpl = (string) ($dui['gallery_heading_tpl'] ?? 'Dokumentasi Uji Lapangan {drone_name}');
            $gallery_hd     = $cln(str_replace(['{drone_name}', '{model_base}'], [$cleanDroneName, $model_base], $gallery_hd_tpl));
          @endphp
          <div>
            @if($gallery_hd !== '')
              <h3 data-aos="fade-up" class="fds-h3" style="margin:0 0 20px;color:var(--fds-ink-primary);">{{ $gallery_hd }}</h3>
            @endif
            <x-fds-gallery :items="$gallery" variant="three" />
          </div>
        @endif
      </div>
    </section>

    {{-- 5. FAQ MODEL — INTERACTIVE <details> DROPDOWN ACCORDION --}}
    @if(!empty($faqs))
    @php
      $faq_hd_tpl   = (string) ($dui['faq_heading_tpl'] ?? 'Pertanyaan Teknis Seputar {model_base}');
      $faq_hd       = $cln(str_replace(['{drone_name}', '{model_base}'], [$cleanDroneName, $model_base], $faq_hd_tpl));
      $faq_sub_text = $cln($dui['faq_subtitle'] ?? 'Jawaban ringkas mengenai spesifikasi teknis, sertifikasi, pengoperasian, dan layanan purna jual resmi FDS.');
    @endphp
    <section id="faq" class="fds-sec fds-bg-light scroll-mt-24">
      <div class="fds-container fds-sticky-split">
        <div class="fds-sticky-col">
          <div data-aos="fade-up">
            @if($faq_hd !== '')
              <h2 class="fds-h2">{{ $faq_hd }}</h2>
            @endif
            @if($faq_sub_text !== '')
              <p class="fds-lead" style="margin-top:14px;">{{ $faq_sub_text }}</p>
            @endif
          </div>
        </div>
        <div class="fds-faq-list" data-reveal-group data-reveal-step="60">
          @foreach($faqs as $faq)
            <details class="fds-faq-row">
              <summary class="fds-faq-summary">
                <span>{{ $cln($faq['q'] ?? '') }}</span>
                <svg class="fds-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M19 9l-7 7-7-7"/>
                </svg>
              </summary>
              <div class="fds-faq-body fds-prose">
                <p class="fds-body" style="margin:0;">{{ $cln($faq['a'] ?? '') }}</p>
              </div>
            </details>
          @endforeach
        </div>
      </div>
    </section>
    @endif

    {{-- 5. CTA AKHIR (EKOSISTEM WAVE BAND) --}}
    <section class="fds-sec fds-bg-teal">
      <x-fds-wave type="eko" :uid="'drone-cta-' . $slug" />
      <div data-aos="fade-up" class="fds-container" style="text-align:center;max-width:740px;">
        <h2 class="fds-h2" style="margin-bottom:16px;">
          {{ $cln(str_replace('{drone_name}', $cleanDroneName, (string) ($dui['cta_heading_tpl'] ?? 'Siap mengoperasikan {drone_name}?'))) }}
        </h2>
        <p class="fds-lead" style="margin:0 auto 32px;">
          {{ $cln($dui['cta_desc'] ?? 'Konsultasikan kebutuhan misi Anda dengan tim teknis PT Karya Solusi Angkasa (FDS). Demo unit dan konsultasi teknis tersedia di Yogyakarta.') }}
        </p>
        <div class="fds-btn-group" style="justify-content:center;">
          <a href="{!! esc_url(home_url('/kontak/?produk=' . urlencode($slug) . '#hubungi-form')) !!}" class="fds-btn fds-btn--white-teal">
            <span>{{ $cln($dui['cta_primary_text'] ?? 'Hubungi Tim Sales') }}</span>
            {!! $arrow_svg !!}
          </a>
          <a href="{!! esc_url($href_detail($dui['cta_secondary_url'] ?? '/blog/')) !!}" class="fds-text-link">
            <span>{{ $cln($dui['cta_secondary_text'] ?? 'Baca studi kasus') }}</span>
            {!! $arrow_svg !!}
          </a>
        </div>
      </div>
    </section>

  </div>

@endif
@endsection
