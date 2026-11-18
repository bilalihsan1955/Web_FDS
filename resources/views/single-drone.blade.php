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
  $arrow_svg = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h12v12"/><path d="M6 18 18 6"/></svg>';
  $chev_svg  = '<svg class="fds-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 9l-7 7-7-7"/></svg>';
@endphp

@if(empty($drone))
  <div class="pt-[52px] bg-[#f5f5f7] min-h-[70vh]">
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
  $stageSrc       = $specs_img !== '' ? $specs_img : $heroSrc;
  $isCutoutStage  = ($specs_img !== '');
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
  $guide_lead  = $gd['guide_lead'] ?? '';
  $guide_secs  = $gd['sections'] ?? [];
  $guide_html  = $gd['guide_html'] ?? '';
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

  <div class="bg-white pt-[52px]">

    {{-- 1. HERO STAGE (BRIGHT OCEAN-TEAL WAVE + STUDIO CUTOUT STAGE) --}}
    <section class="fds-stage-hero">
      <x-fds-wave type="fluid" :uid="'drone-hero-' . $slug" />
      <div class="fds-container fds-stage-grid">
        <div>
          <p class="fds-caption" style="margin:0 0 12px;">
            <a href="{!! esc_url($cat_url) !!}" style="color:var(--fds-white);text-decoration:underline;text-underline-offset:4px;">
              {{ $cln($drone['kategori'] ?? 'Agrikultur') }}
            </a>
          </p>
          <h1 class="fds-h1" style="margin-bottom:20px;">
            {{ $cleanDroneName }}
          </h1>
          @if(!empty($drone['tagline']))
            <p class="fds-lead" style="margin-bottom:32px;">{{ $cln($drone['tagline']) }}</p>
          @endif
          <div class="fds-btn-group">
            <a href="{!! esc_url(home_url('/kontak/?produk=' . urlencode($slug) . '#hubungi-form')) !!}" class="fds-btn fds-btn--white-teal">
              {{ $cln($dui['hero_cta1_text'] ?? 'Minta Penawaran') }}
            </a>
            <a href="{!! esc_url(home_url('/bandingkan/?d1=' . urlencode($slug))) !!}" class="fds-text-link">
              {{ $cln($dui['hero_cta2_text'] ?? 'Bandingkan model') }} {!! $arrow_svg !!}
            </a>
          </div>
        </div>
        <div class="fds-cutout-stage {{ $isCutoutStage ? '' : 'fds-cutout-stage--cover' }}">
          <img src="{!! esc_url($stageSrc) !!}"
               @if(!$isCutoutStage && $heroSrcset !== '') srcset="{{ $heroSrcset }}" sizes="(max-width: 1024px) 100vw, 640px" @endif
               alt="{{ $cleanDroneName }} — {{ $heroAltCategory }} PT Karya Solusi Angkasa FDS"
               width="1148"
               height="559"
               loading="eager"
               fetchpriority="high"
               decoding="sync"
               data-no-lazy="1">
        </div>
      </div>
    </section>

    {{-- 2. SPESIFIKASI KUNCI (4-KPI BAND + COLLAPSIBLE DETAIL PARAMETERS, TANPA TABEL DUPLIKAT) --}}
    @if(!empty($stats4) || !empty($drone['desc']))
    <section id="spesifikasi" class="fds-sec fds-bg-white relative z-20">
      <div class="fds-container">
        <div class="fds-sec-head fds-sec-head--split">
          <div>
            <h2 class="fds-h2">
              {!! wp_kses((string) ($dui['specs_heading'] ?? 'Direkayasa untuk <br>performa nyata.'), ['br' => []]) !!}
            </h2>
            <p class="fds-lead" style="margin-top:14px;">
              {{ $cln($drone['desc'] ?: str_replace('{drone_name}', $cleanDroneName, (string) ($dui['specs_desc_fallback'] ?? "Setiap spesifikasi {$cleanDroneName} divalidasi melalui ratusan jam uji lapangan di berbagai kondisi cuaca dan jenis lahan di Indonesia."))) }}
            </p>
          </div>
          <div>
            <a href="{!! esc_url(home_url('/bandingkan/?d1=' . urlencode($slug))) !!}" class="fds-text-link">
              {{ $cln($dui['specs_compare_link'] ?? 'Bandingkan spesifikasi') }} {!! $arrow_svg !!}
            </a>
          </div>
        </div>

        @if(!empty($stats4))
          <div class="fds-stats-band {{ count($stats4) >= 4 ? 'fds-stats-band--4' : '' }}">
            @foreach($stats4 as $st)
              <div class="fds-stat-cell">
                <span class="fds-stat-num">{{ $st['num'] }}</span>
                <span class="fds-stat-label">{{ $st['lbl'] }}</span>
              </div>
            @endforeach
          </div>
        @endif

        @if(!empty($specs_rest))
          <details class="fds-quiet-details">
            <summary class="fds-quiet-summary">Lihat parameter operasional lanjutan &amp; standar unit {{ $cleanDroneName }} {!! $chev_svg !!}</summary>
            <div class="fds-quiet-body fds-prose">
              <dl style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin:0;">
                @foreach($specs_rest as [$rLbl, $rVal])
                  <div>
                    <dt class="fds-caption">{{ $rLbl }}</dt>
                    <dd style="margin:4px 0 0;font-weight:600;color:var(--fds-ink-primary);">{{ $rVal }}</dd>
                  </div>
                @endforeach
              </dl>
            </div>
          </details>
        @endif
      </div>
    </section>
    @endif

    {{-- 3. UNDUH BROSUR + APLIKASI & USE CASES + GALERI UJI LAPANGAN --}}
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
    <section id="aplikasi-usecases" class="fds-sec fds-bg-light">
      <div class="fds-container">
        @if($brochure_enable === '1')
          <div id="unduh-brosur" style="padding-bottom:64px;border-bottom:1px solid var(--fds-border-subtle);margin-bottom:64px;">
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

        <div class="fds-story-split fds-story-split--media-left" @if(!empty($gallery)) style="margin-bottom:64px;" @endif>
          <figure class="fds-media-stage">
            <img src="{!! esc_url($heroSrc) !!}"
                 @if($heroSrcset !== '') srcset="{{ $heroSrcset }}" sizes="(max-width: 1024px) 100vw, 640px" @endif
                 alt="Operasi lapangan {{ $cleanDroneName }} — Full Drone Solutions"
                 loading="lazy"
                 decoding="async"
                 width="1000"
                 height="625">
          </figure>
          <div>
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

        @if(!empty($gallery))
          <div>
            <p class="fds-caption" style="margin:0 0 18px;font-weight:600;color:var(--fds-ink-primary);">Dokumentasi Uji Lapangan {{ $cleanDroneName }}</p>
            <x-fds-gallery :items="$gallery" variant="three" />
          </div>
        @endif
      </div>
    </section>

    {{-- 4. FAQ MODEL (ACCORDION RAPI TANPA ESSAY MENUMPUK) --}}
    @if(!empty($faqs))
    <section class="fds-sec fds-bg-white">
      <div class="fds-container fds-sticky-split">
        <div class="fds-sticky-col">
          <h2 class="fds-h2">Pertanyaan Teknis Seputar {{ $model_base }}</h2>
        </div>
        <div class="fds-faq-list">
          @foreach($faqs as $idx => $faq)
            <details class="fds-faq-row" @if($idx === 0) open @endif>
              <summary class="fds-faq-summary">
                <span>{{ $cln($faq['q'] ?? '') }}</span>
                {!! $chev_svg !!}
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
      <div class="fds-container" style="text-align:center;max-width:740px;">
        <h2 class="fds-h2" style="margin-bottom:16px;">
          {{ $cln(str_replace('{drone_name}', $cleanDroneName, (string) ($dui['cta_heading_tpl'] ?? 'Siap mengoperasikan {drone_name}?'))) }}
        </h2>
        <p class="fds-lead" style="margin:0 auto 32px;">
          {{ $cln($dui['cta_desc'] ?? 'Konsultasikan kebutuhan misi Anda dengan tim teknis PT Karya Solusi Angkasa (FDS). Demo unit dan konsultasi teknis tersedia di Yogyakarta.') }}
        </p>
        <div class="fds-btn-group" style="justify-content:center;">
          <a href="{!! esc_url(home_url('/kontak/?produk=' . urlencode($slug) . '#hubungi-form')) !!}" class="fds-btn fds-btn--white-teal">
            {{ $cln($dui['cta_primary_text'] ?? 'Hubungi Tim Sales') }}
          </a>
          <a href="{!! esc_url($href_detail($dui['cta_secondary_url'] ?? '/blog/')) !!}" class="fds-text-link">
            {{ $cln($dui['cta_secondary_text'] ?? 'Baca studi kasus') }} {!! $arrow_svg !!}
          </a>
        </div>
      </div>
    </section>

    {{-- 6. QUIET FOOTNOTE BAND (NARASI PANDUAN 250-400w TETAP SSR DI DOM) --}}
    @if($guide_html !== '' || $guide_lead !== '' || !empty($guide_secs))
    <section id="panduan-teknis" class="fds-footnote-band" aria-label="Referensi Operasional {{ $cleanDroneName }}">
      <div class="fds-container">
        <details class="fds-quiet-details" style="margin-top:0;">
          <summary class="fds-quiet-summary">Referensi Operasional &amp; Panduan Lapangan {{ $cleanDroneName }} {!! $chev_svg !!}</summary>
          <div class="fds-quiet-body fds-prose">
            @if($guide_html !== '')
              <div class="fds-drone-guide">{!! $guide_html !!}</div>
            @else
              @if($guide_lead !== '')
                <p class="fds-body" style="margin-bottom:14px;">{!! wp_kses_post($guide_lead) !!}</p>
              @endif
              @foreach($guide_secs as $gs)
                <h2 class="fds-h3" style="margin:18px 0 6px;">{{ $cln($gs['h2'] ?? '') }}</h2>
                <p class="fds-body" style="margin-bottom:8px;">{!! wp_kses_post((string) ($gs['p'] ?? '')) !!}</p>
              @endforeach
              <p class="fds-body" style="margin-top:16px;">
                Tinjau perbandingan spesifikasi lintas lini di <a href="{!! esc_url(home_url('/bandingkan/')) !!}" style="color:var(--color-teal-800);font-weight:600;">Bandingkan Spesifikasi Drone</a>, telusuri <a href="{!! esc_url(home_url('/produk/')) !!}" style="color:var(--color-teal-800);font-weight:600;">Katalog Lengkap UAV FDS</a>, atau jadwalkan demo teknis di halaman <a href="{!! esc_url(home_url('/kontak/#hubungi-form')) !!}" style="color:var(--color-teal-800);font-weight:600;">Hubungi Tim Engineering</a>.
              </p>
            @endif
          </div>
        </details>
      </div>
    </section>
    @endif

  </div>

@endif
@endsection
