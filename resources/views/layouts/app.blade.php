<!doctype html>
<html <?php language_attributes(); ?> class="scroll-smooth">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"></noscript>

    {{-- Prioritas utama: Muat stylesheet aplikasi seawal mungkin sebelum wp_head (dikunci dengan data-no-optimize agar tidak di-404-kan oleh LiteSpeed Combine) --}}
    {!! str_replace(['<link ', '<script '], ['<link data-no-optimize="1" data-no-minify="1" ', '<script data-no-optimize="1" data-no-minify="1" '], \Illuminate\Support\Facades\Vite::withEntryPoints(['resources/css/app.css', 'resources/js/app.js'])->toHtml()) !!}

    {{-- Universal LCP Image Preload across Homepage, Detail Drone, and Detail Blog --}}
    @if(is_front_page())
      @php
        $hp_slides = function_exists('App\fds_get_hero_slides') ? \App\fds_get_hero_slides() : [];
        $lcp_img = !empty($hp_slides[0]['url']) ? $hp_slides[0]['url'] : '';
      @endphp
      @if(!empty($lcp_img))
      <link rel="preload" as="image" href="{{ esc_url($lcp_img) }}" fetchpriority="high">
      @endif
    @elseif(is_singular())
      @php
        $sing_thumb = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: get_the_post_thumbnail_url(get_the_ID(), 'full');
      @endphp
      @if(!empty($sing_thumb))
      <link rel="preload" as="image" href="{{ esc_url($sing_thumb) }}" fetchpriority="high">
      @endif
    @endif

    <style>
      *, *::before, *::after { box-sizing: border-box; }
      html { scroll-behavior: smooth; }
      body {
        font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Inter', 'Segoe UI', sans-serif;
        -webkit-font-smoothing: antialiased;
        background-color: #f5f5f7;
        color: #1d1d1f;
        margin: 0;
        padding: 0;
      }
      ::-webkit-scrollbar { width: 6px; }
      ::-webkit-scrollbar-track { background: #f5f5f7; }
      ::-webkit-scrollbar-thumb { background: #c7c7cc; border-radius: 3px; }

      /* Anti-FOUC & Anti-SVG Blowout: Mencegah icon membesar raksasa sebelum CSS selesai di-parse */
      svg {
        display: inline-block;
        vertical-align: middle;
        overflow: hidden;
      }
      header svg, nav svg, .site-header svg, button svg, a svg {
        max-width: 24px;
        max-height: 24px;
      }
      svg:not([class*="w-full"]):not([class*="w-["]):not([width]) {
        max-width: 28px;
        max-height: 28px;
      }

      /* WP Admin Bar compensation */
      .admin-bar #site-header { top: 32px; }
      @media screen and (max-width: 782px) {
        .admin-bar #site-header { top: 46px; }
        #wpadminbar { position: fixed !important; }
      }
    </style>

    @php
      $nb_brand_head = function_exists('App\fds_get_navbar_brand') ? \App\fds_get_navbar_brand() : [];
    @endphp
    @if(!empty($nb_brand_head['favicon_url']))
    <link rel="icon" href="{{ esc_url($nb_brand_head['favicon_url']) }}">
    <link rel="apple-touch-icon" href="{{ esc_url($nb_brand_head['favicon_url']) }}">
    @endif

    @php
      if (function_exists('App\fds_render_seo_meta_tags')) {
        \App\fds_render_seo_meta_tags();
      }
      if (function_exists('App\fds_render_schema_jsonld')) {
        \App\fds_render_schema_jsonld();
      }
      $fds_track_cfg = function_exists('App\fds_get_tracking_config') ? \App\fds_get_tracking_config() : [];
      $fds_ga4_id    = $fds_track_cfg['ga4_id'] ?? '';
      $fds_gtm_id    = $fds_track_cfg['gtm_id'] ?? '';
      $fds_gads_id   = $fds_track_cfg['gads_id'] ?? '';
      $fds_gads_lbl  = $fds_track_cfg['gads_label'] ?? '';
      $fds_meta_px   = $fds_track_cfg['meta_pixel_id'] ?? '';
      $fds_tracking_js_payload = [
        'ga4_id'        => $fds_ga4_id,
        'gtm_id'        => $fds_gtm_id,
        'gads_id'       => $fds_gads_id,
        'gads_label'    => $fds_gads_lbl,
        'meta_pixel_id' => $fds_meta_px,
      ];
    @endphp

    <script>
      window.dataLayer = window.dataLayer || [];
      window.fdsTrackingConfig = {!! wp_json_encode($fds_tracking_js_payload) !!};
    </script>
    @if($fds_gtm_id !== '')
      <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','{{ esc_js($fds_gtm_id) }}');</script>
    @endif
    @if($fds_ga4_id !== '' || $fds_gads_id !== '')
      @php $gtag_primary = $fds_ga4_id !== '' ? $fds_ga4_id : $fds_gads_id; @endphp
      <script async src="https://www.googletagmanager.com/gtag/js?id={{ esc_attr($gtag_primary) }}"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        @if($fds_ga4_id !== '')
        gtag('config', '{{ esc_js($fds_ga4_id) }}');
        @endif
        @if($fds_gads_id !== '')
        gtag('config', '{{ esc_js($fds_gads_id) }}');
        @endif
      </script>
    @endif
    @if($fds_meta_px !== '')
      <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ esc_js($fds_meta_px) }}');
        fbq('track', 'PageView');
      </script>
    @endif

    <?php do_action('get_header'); ?>
    <?php wp_head(); ?>
  </head>

  <body <?php body_class('bg-[#f5f5f7] text-[#1d1d1f] antialiased'); ?>>
    <?php wp_body_open(); ?>
    @if(!empty($fds_gtm_id))
      <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ esc_attr($fds_gtm_id) }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    @php
      // 100% Dynamic WP Query from 'kategori_drone' Taxonomy & 'drone' CPT (Cached via Transient)
      $drones_by_cat_slug = get_transient('fds_mega_menu_data_v2');

      if ($drones_by_cat_slug === false) {
          $tax_terms = get_terms([
              'taxonomy'   => 'kategori_drone',
              'hide_empty' => true,
              'orderby'    => 'term_id',
              'order'      => 'ASC',
          ]);

          if (empty($tax_terms) || is_wp_error($tax_terms)) {
              $tax_terms = [
                  (object)['name' => 'Agrikultur', 'slug' => 'agrikultur', 'description' => 'Pertanian Presisi FERTO 5L – 50L'],
                  (object)['name' => 'Pemetaan & GIS', 'slug' => 'pemetaan-gis', 'description' => 'Survei Geospasial & Inspeksi Aset'],
                  (object)['name' => 'Kargo', 'slug' => 'kargo', 'description' => 'Distribusi Logistik Cepat 10 kg'],
              ];
          } else {
              $tax_terms = array_values(array_filter($tax_terms, function ($t) {
                  return stripos((string) $t->slug, 'rebo') === false;
              }));
          }

          $nav_drones = get_posts([
              'post_type'      => 'drone',
              'posts_per_page' => -1,
              'post_status'    => 'publish',
              'orderby'        => 'ID',
              'order'          => 'ASC',
          ]);

          $drones_by_cat_slug = [];
          foreach ($tax_terms as $t) {
              $t_link = (isset($t->term_id) && taxonomy_exists('kategori_drone')) ? get_term_link($t) : home_url('/kategori-drone/' . $t->slug . '/');
              $drones_by_cat_slug[$t->slug] = [
                  'term'     => $t,
                  'term_url' => (!is_wp_error($t_link) && is_string($t_link)) ? $t_link : home_url('/#produk'),
                  'drones'   => [],
              ];
          }

          foreach ($nav_drones as $nd) {
              $nd_slug_chk = strtolower((string) $nd->post_name);
              if (strpos($nd_slug_chk, 'rebo') !== false || strpos($nd_slug_chk, 'agrea') !== false) {
                  continue;
              }

              $terms = get_the_terms($nd->ID, 'kategori_drone');
              $cat_slug = '';
              if (!empty($terms) && !is_wp_error($terms)) {
                  $cat_slug = $terms[0]->slug;
              } else {
                  $meta_cat = get_post_meta($nd->ID, 'drone_kategori', true) ?: 'Agrikultur';
                  $cat_slug = sanitize_title($meta_cat);
              }
              if (stripos((string) $cat_slug, 'rebo') !== false) {
                  continue;
              }

              if (!isset($drones_by_cat_slug[$cat_slug])) {
                  $t_obj = (object)['name' => (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : ($meta_cat ?? 'Agrikultur'), 'slug' => $cat_slug, 'description' => ''];
                  $t_link = (!empty($terms) && !is_wp_error($terms)) ? get_term_link($terms[0]) : home_url('/kategori-drone/' . $cat_slug . '/');
                  $drones_by_cat_slug[$cat_slug] = [
                      'term'     => $t_obj,
                      'term_url' => (!is_wp_error($t_link) && is_string($t_link)) ? $t_link : home_url('/#produk'),
                      'drones'   => [],
                  ];
              }

              $d_specs = get_post_meta($nd->ID, 'drone_specs_raw', true);
              $spec_preview = '';
              if ($d_specs) {
                  $lines = array_slice(array_filter(array_map('trim', explode("\n", $d_specs))), 0, 2);
                  $parts = [];
                  foreach ($lines as $l) {
                      $sp = explode(':', $l, 2);
                      if (count($sp) === 2) {
                          $valClean = trim($sp[1]);
                          $valClean = preg_replace('/^(Kapasitas\s+Tangki|Kapasitas\s+Payload\s+Biji|Kapasitas\s+Payload|Payload\s+Kapasitas|Payload):\s*/i', '', $valClean);
                          $parts[] = $valClean;
                      }
                  }
                  $spec_preview = implode(' · ', $parts);
              }

              $drones_by_cat_slug[$cat_slug]['drones'][] = [
                  'id'       => $nd->ID,
                  'url'      => get_permalink($nd->ID),
                  'name'     => html_entity_decode($nd->post_title, ENT_QUOTES, 'UTF-8'),
                  'slug'     => $nd->post_name,
                  'badge'    => html_entity_decode(get_post_meta($nd->ID, 'drone_badge', true) ?: 'Unggulan', ENT_QUOTES, 'UTF-8'),
                  'desc'     => html_entity_decode(get_post_meta($nd->ID, 'drone_tagline', true) ?: wp_trim_words(get_post_meta($nd->ID, 'drone_desc', true) ?: $nd->post_content, 12), ENT_QUOTES, 'UTF-8'),
                  'spec'     => html_entity_decode($spec_preview ?: 'Spesifikasi Lengkap', ENT_QUOTES, 'UTF-8'),
              ];
          }

          set_transient('fds_mega_menu_data_v2', $drones_by_cat_slug, 12 * HOUR_IN_SECONDS);
      }

      // Build ordered flat list for footer without extra DB queries on cache hit
      $footer_drones = [];
      if (is_array($drones_by_cat_slug)) {
          foreach ($drones_by_cat_slug as $cslug => $cdata) {
              if (stripos((string) $cslug, 'rebo') !== false || empty($cdata['drones'])) {
                  continue;
              }
              foreach ($cdata['drones'] as $cd_item) {
                  $footer_drones[$cd_item['id']] = $cd_item;
              }
          }
          ksort($footer_drones);
      }
    @endphp

    <div id="app">
      <a class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-white focus:text-black focus:rounded-lg focus:shadow-lg" href="#main">
        Lewati ke konten utama
      </a>

      <!-- ============================================================ -->
      <!-- NAVBAR — Apple-style glassmorphism, light, refined           -->
      <!-- ============================================================ -->
      <header id="site-header" class="fixed top-0 inset-x-0 z-[9999] bg-white border-b border-black/[0.08]" style="transition: background 0.2s ease;">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
          <div class="flex items-center justify-between h-[52px]">

            <!-- Dynamic Logo & Brand -->
            <!-- Dynamic Logo & Brand -->
            @php
              $nb_brand = function_exists('App\fds_get_navbar_brand') ? \App\fds_get_navbar_brand() : [
                'has_logo'     => false,
                'logo_url'     => '',
                'brand_text'   => 'Full Drone Solutions',
                'display_mode' => 'both',
                'logo_height'  => 28,
              ];
            @endphp
            <a href="{{ home_url('/') }}" class="nav-direct-link flex items-center gap-2.5 group">
              @if($nb_brand['display_mode'] !== 'text_only')
                @if($nb_brand['has_logo'])
                  <img src="{{ esc_url($nb_brand['logo_url']) }}"
                       alt="{{ esc_attr($nb_brand['brand_text']) }}"
                       width="195"
                       height="{{ (int) $nb_brand['logo_height'] }}"
                       decoding="async"
                       style="height: {{ (int) $nb_brand['logo_height'] }}px; width: auto; max-width: 220px; object-fit: contain;"
                       class="transition-transform duration-200 group-hover:scale-[1.03]">
                @else
                  <svg class="w-5 h-5 text-[#1d1d1f] group-hover:text-[#0f808a] transition-colors flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2L3 6l7 4 7-4-7-4zM3 14l7 4 7-4M3 10l7 4 7-4"/>
                  </svg>
                @endif
              @endif

              @if($nb_brand['display_mode'] !== 'logo_only' && !empty($nb_brand['brand_text']))
                <span class="text-[15px] font-semibold text-[#1d1d1f] group-hover:text-[#0f808a] transition-colors tracking-tight">
                  {!! esc_html($nb_brand['brand_text']) !!}
                </span>
              @endif
            </a>

            <!-- Nav links with Dynamic Active State -->
            @php
              $is_active_home    = is_front_page() && !is_home();
              $is_active_produk  = is_singular('drone') || is_page('bandingkan') || is_tax('kategori_drone');
              $is_active_layanan = is_page('layanan') || is_singular('layanan') || (function_exists('App\fds_get_current_layanan_context') && \App\fds_get_current_layanan_context() !== null);
              $is_active_about   = is_page('tentang-kami') || is_page('tentang-kami-2') || is_page('about') || is_page('about-2') || is_page('about-us');
              $is_active_blog    = is_home() || is_page('blog') || (is_single() && get_post_type() === 'post') || is_category() || is_tag();
              $is_active_kontak  = is_page('kontak') || is_page('contact') || is_page('hubungi-kami');
            @endphp
            <nav aria-label="Navigasi utama" class="hidden lg:flex items-center gap-7 text-[13px] font-medium text-[#515154]">
              <a href="{{ home_url('/') }}" 
                 class="nav-direct-link transition-colors duration-150 py-2 relative {{ $is_active_home ? 'text-[#0f808a] font-semibold after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-[#0f808a] after:rounded-full' : 'hover:text-[#1d1d1f]' }}">
                Beranda
              </a>

              <!-- Produk dropdown trigger -->
              <div id="produk-trigger" class="relative flex items-center py-2 {{ $is_active_produk ? 'text-[#0f808a] font-semibold after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-[#0f808a] after:rounded-full' : '' }}">
                <button type="button"
                        id="produk-toggle-btn"
                        aria-expanded="false"
                        aria-controls="mega-pane-produk"
                        class="flex items-center gap-1 cursor-pointer group select-none bg-transparent border-0 p-0 text-[13px] font-medium focus-visible:outline-2 focus-visible:outline-[#0f808a] focus-visible:outline-offset-4 rounded {{ $is_active_produk ? 'text-[#0f808a] font-semibold' : 'text-[#515154] hover:text-[#1d1d1f] transition-colors duration-150' }}">
                  <span>Produk</span>
                  <svg id="produk-chevron" class="w-3.5 h-3.5 flex-shrink-0 {{ $is_active_produk ? 'text-[#0f808a]' : 'text-[#6e6e73]' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
              </div>

              <!-- Layanan link + accessible submenu trigger -->
              <div id="layanan-trigger" class="relative flex items-center gap-1 group select-none py-2 {{ $is_active_layanan ? 'text-[#0f808a] font-semibold after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-[#0f808a] after:rounded-full' : '' }}">
                <a href="{{ home_url('/layanan/') }}"
                   class="py-0.5 focus-visible:outline-2 focus-visible:outline-[#0f808a] focus-visible:outline-offset-4 rounded {{ $is_active_layanan ? 'text-[#0f808a] font-semibold' : 'hover:text-[#1d1d1f] transition-colors duration-150' }}">
                  Layanan
                </a>
                <button type="button"
                        id="layanan-toggle-btn"
                        aria-expanded="false"
                        aria-controls="mega-pane-layanan"
                        aria-label="Buka submenu Layanan"
                        class="p-1 -my-1 -mr-1 rounded inline-flex items-center justify-center bg-transparent border-0 cursor-pointer focus-visible:outline-2 focus-visible:outline-[#0f808a] focus-visible:outline-offset-2">
                  <svg id="layanan-chevron" class="w-3.5 h-3.5 flex-shrink-0 {{ $is_active_layanan ? 'text-[#0f808a]' : 'text-[#6e6e73]' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
              </div>

              <a href="{{ home_url('/tentang-kami/') }}" 
                 class="nav-direct-link transition-colors duration-150 py-2 relative {{ $is_active_about ? 'text-[#0f808a] font-semibold after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-[#0f808a] after:rounded-full' : 'hover:text-[#1d1d1f]' }}">
                Tentang Kami
              </a>
              <a href="{{ home_url('/blog/') }}" 
                 class="nav-direct-link transition-colors duration-150 py-2 relative {{ $is_active_blog ? 'text-[#0f808a] font-semibold after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-[#0f808a] after:rounded-full' : 'hover:text-[#1d1d1f]' }}">
                Blog
              </a>
              <a href="{{ home_url('/kontak/') }}" 
                 class="nav-direct-link transition-colors duration-150 py-2 relative {{ $is_active_kontak ? 'text-[#0f808a] font-semibold after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-[#0f808a] after:rounded-full' : 'hover:text-[#1d1d1f]' }}">
                Kontak
              </a>
            </nav>

            <!-- CTA -->
            <div class="flex items-center gap-4">
              <a href="{{ esc_url($nb_brand['cta_url'] ?? home_url('/#kontak')) }}" class="nav-direct-link hidden sm:inline-flex items-center bg-[#0f808a] hover:bg-[#0a555c] active:scale-[0.97] text-white text-[13px] font-semibold px-4 py-2 rounded-full transition-all duration-150">
                {!! esc_html($nb_brand['cta_text'] ?? 'Konsultasi Drone') !!}
              </a>
              <button id="mobile-menu-toggle" type="button" class="lg:hidden w-10 h-10 flex flex-col gap-1.5 items-center justify-center" aria-label="Menu">
                <span id="bar1" class="block w-5 h-[1.5px] bg-[#1d1d1f] transition-all duration-300 origin-center"></span>
                <span id="bar2" class="block w-5 h-[1.5px] bg-[#1d1d1f] transition-all duration-300"></span>
                <span id="bar3" class="block w-5 h-[1.5px] bg-[#1d1d1f] transition-all duration-300 origin-center"></span>
              </button>
            </div>

          </div>
        </div>

        <!-- ===================================================== -->
        <!-- UNIFIED MEGA MENU DRAWER — Apple-Style Editorial Layout  -->
        <!-- ===================================================== -->
        <div id="mega-menu-drawer"
             class="absolute top-full inset-x-0 bg-white/95 backdrop-blur-2xl border-b border-black/[0.08] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.08)] overflow-hidden"
             style="opacity:0; transform:translateY(-6px); pointer-events:none; transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1), transform 0.22s cubic-bezier(0.16, 1, 0.3, 1); will-change: opacity, transform;">
          <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-10">

            <!-- 1. PANE PRODUK (100% Dinamis dari Taksonomi WordPress) -->
            <div id="mega-pane-produk" class="mega-pane transition-opacity duration-150 opacity-100">
              @php
                $agri_cat = $drones_by_cat_slug['agrikultur'] ?? null;
                $pemetaan_cat = $drones_by_cat_slug['pemetaan-gis'] ?? ($drones_by_cat_slug['pemetaan'] ?? null);
                $kargo_cat = $drones_by_cat_slug['kargo'] ?? ($drones_by_cat_slug['cargo'] ?? null);

                $agri_url = $agri_cat['term_url'] ?? home_url('/kategori-drone/agrikultur/');
                $pemetaan_url = $pemetaan_cat['term_url'] ?? home_url('/kategori-drone/pemetaan-gis/');
                $kargo_url = $kargo_cat['term_url'] ?? home_url('/kategori-drone/kargo/');

                $agri_limit = 6;
                $agri_drones = $agri_cat ? array_slice($agri_cat['drones'], 0, $agri_limit) : [];
                $agri_remaining = $agri_cat ? max(0, count($agri_cat['drones']) - $agri_limit) : 0;

                $pemetaan_limit = 2;
                $pemetaan_drones = $pemetaan_cat ? array_slice($pemetaan_cat['drones'], 0, $pemetaan_limit) : [];
                $pemetaan_remaining = $pemetaan_cat ? max(0, count($pemetaan_cat['drones']) - $pemetaan_limit) : 0;

                $kargo_limit = 2;
                $kargo_drones = $kargo_cat ? array_slice($kargo_cat['drones'], 0, $kargo_limit) : [];
                $kargo_remaining = $kargo_cat ? max(0, count($kargo_cat['drones']) - $kargo_limit) : 0;

                $raw_compare_url = trim((string) ($nb_brand['menu_prod_compare_url'] ?? ''));
                if ($raw_compare_url === '' || preg_match('#/bandingkan$#i', $raw_compare_url)) {
                    $compare_href = home_url('/bandingkan/');
                } else {
                    $compare_href = $raw_compare_url;
                }
              @endphp

              <div class="grid grid-cols-12 gap-10">
                
                <!-- Column 1 & 2: Agrikultur (Atas) + Pemetaan & GIS (Bawah) (Col 6) -->
                <div class="col-span-6 border-r border-black/[0.06] pr-10">
                  
                  <!-- Agrikultur (Atas) -->
                  <div>
                    <div class="mb-3 flex items-center justify-between">
                      <a href="{{ esc_url($agri_url) }}" class="text-[12px] font-semibold text-[#86868b] hover:text-[#0f808a] transition-colors">
                        {!! !empty($agri_cat['term']->name) ? wp_specialchars_decode($agri_cat['term']->name) : 'UAV Agrikultur' !!} (Seri FERTO)
                      </a>
                      @if($agri_remaining > 0)
                      <a href="{{ esc_url($agri_url) }}" class="text-[11px] font-semibold text-[#0f808a] hover:underline inline-flex items-center gap-0.5 group">
                        <span>+ Lihat {{ $agri_remaining }} lainnya</span>
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </a>
                      @endif
                    </div>

                    @if(!empty($agri_drones))
                    <div class="grid grid-cols-2 gap-x-8 gap-y-3">
                      @foreach($agri_drones as $item)
                      <a href="{{ esc_url($item['url']) }}" class="group py-0.5 block transition-colors">
                        <div class="text-[15px] font-semibold text-[#1d1d1f] group-hover:text-[#0f808a] transition-colors leading-tight">
                          {!! $item['name'] !!}
                        </div>
                        <div class="text-[11px] text-[#86868b] mt-0.5 font-normal leading-snug">
                          {!! $item['spec'] ?: $item['desc'] !!}
                        </div>
                      </a>
                      @endforeach
                    </div>
                    @else
                    <p class="text-[13px] text-[#86868b]">Belum ada drone pada kategori ini.</p>
                    @endif
                  </div>

                  <!-- Pemetaan & GIS (Bawah Kategori Agrikultur) -->
                  @if(!empty($pemetaan_cat) && !empty($pemetaan_drones))
                  <div class="pt-4 mt-4 border-t border-black/[0.06]">
                    <div class="mb-3 flex items-center justify-between">
                      <a href="{{ esc_url($pemetaan_url) }}" class="text-[12px] font-semibold text-[#86868b] hover:text-[#0f808a] transition-colors">
                        {!! wp_specialchars_decode($pemetaan_cat['term']->name) !!}
                      </a>
                      @if($pemetaan_remaining > 0)
                      <a href="{{ esc_url($pemetaan_url) }}" class="text-[11px] font-semibold text-[#0f808a] hover:underline inline-flex items-center gap-0.5 group">
                        <span>+ Lihat {{ $pemetaan_remaining }} lainnya</span>
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </a>
                      @endif
                    </div>

                    <div class="grid grid-cols-2 gap-x-8 gap-y-3">
                      @foreach($pemetaan_drones as $item)
                      <a href="{{ esc_url($item['url']) }}" class="group py-0.5 block transition-colors">
                        <div class="text-[15px] font-semibold text-[#1d1d1f] group-hover:text-[#0f808a] transition-colors leading-tight">
                          {!! $item['name'] !!}
                        </div>
                        <div class="text-[11px] text-[#86868b] mt-0.5 font-normal leading-snug">
                          {!! $item['spec'] ?: $item['desc'] !!}
                        </div>
                      </a>
                      @endforeach
                    </div>
                  </div>
                  @endif

                </div>

                <!-- Column 3: Kargo & Logistik (Col 3) -->
                <div class="col-span-3 border-r border-black/[0.06] pr-8 space-y-5">
                  
                  <!-- Kargo -->
                  @if(!empty($kargo_cat) && !empty($kargo_drones))
                  <div>
                    <a href="{{ esc_url($kargo_url) }}" class="text-[12px] font-semibold text-[#86868b] hover:text-[#0f808a] transition-colors mb-2.5 inline-block">
                      {!! wp_specialchars_decode($kargo_cat['term']->name) !!}
                    </a>
                    <div class="space-y-2.5">
                      @foreach($kargo_drones as $item)
                      <a href="{{ esc_url($item['url']) }}" class="group py-0.5 block transition-colors">
                        <div class="text-[14px] font-semibold text-[#1d1d1f] group-hover:text-[#0f808a] transition-colors leading-tight">
                          {!! $item['name'] !!}
                        </div>
                        <div class="text-[11px] text-[#86868b] mt-0.5 font-normal leading-snug">
                          {!! $item['spec'] ?: $item['desc'] !!}
                        </div>
                      </a>
                      @endforeach
                      @if($kargo_remaining > 0)
                      <a href="{{ esc_url($kargo_url) }}" class="inline-flex items-center gap-0.5 text-[11px] font-semibold text-[#0f808a] hover:underline pt-0.5 group">
                        <span>+ Lihat {{ $kargo_remaining }} lainnya</span>
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </a>
                      @endif
                    </div>
                  </div>
                  @endif

                </div>

                <!-- Column 4: Ekosistem & Standar (Col 3) -->
                <div class="col-span-3 flex flex-col justify-between">
                  <div>
                    <p class="text-[12px] font-semibold text-[#86868b] mb-4">{!! esc_html($nb_brand['menu_prod_heading'] ?? 'Ekosistem & Standar Mutu') !!}</p>
                    
                    <div class="space-y-3.5">
                      <div>
                        <div class="text-[14px] font-semibold text-[#1d1d1f] leading-tight">{!! esc_html($nb_brand['menu_prod_item1_title'] ?? 'FDS STATION GCS') !!}</div>
                        <div class="text-[11px] text-[#86868b] mt-0.5 font-normal leading-snug">{!! esc_html($nb_brand['menu_prod_item1_desc'] ?? 'Software Ground Control Bahasa Indonesia') !!}</div>
                      </div>

                      <div>
                        <div class="text-[14px] font-semibold text-[#1d1d1f] leading-tight">{!! esc_html($nb_brand['menu_prod_item2_title'] ?? 'Sertifikasi TKDN 60,74%') !!}</div>
                        <div class="text-[11px] text-[#86868b] mt-0.5 font-normal leading-snug">{!! esc_html($nb_brand['menu_prod_item2_desc'] ?? 'Prioritas Pengadaan Pemerintah & LPSE') !!}</div>
                      </div>

                      <div>
                        <div class="text-[14px] font-semibold text-[#1d1d1f] leading-tight">{!! esc_html($nb_brand['menu_prod_item3_title'] ?? 'Standar SNI 9199:2023') !!}</div>
                        <div class="text-[11px] text-[#86868b] mt-0.5 font-normal leading-snug">{!! esc_html($nb_brand['menu_prod_item3_desc'] ?? 'Teruji Resmi Mutu Pertanian Nasional') !!}</div>
                      </div>
                    </div>
                  </div>

                  <div class="pt-5 border-t border-black/[0.06]">
                    <a href="{{ esc_url($compare_href) }}" class="inline-flex items-center text-[#0f808a] text-[13px] font-semibold hover:underline gap-1.5 leading-none group">
                      <span class="leading-none">{!! esc_html($nb_brand['menu_prod_compare_text'] ?? 'Bandingkan Semua Model Drone') !!}</span>
                      <svg class="w-[18px] h-[18px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6h12v12"/>
                        <path d="M6 18 18 6"/>
                      </svg>
                    </a>
                  </div>
                </div>

              </div>
            </div>

            <!-- 2. PANE LAYANAN (Single Source of Truth: Hub, 6 Sektor Industri & 3 Kategori Layanan) -->
            @php
              $menu_sectors    = function_exists('App\fds_get_layanan_sectors') ? \App\fds_get_layanan_sectors() : [];
              $menu_categories = function_exists('App\fds_get_layanan_categories') ? \App\fds_get_layanan_categories() : [];
              $menu_ui         = function_exists('App\fds_get_layanan_ui_texts') ? \App\fds_get_layanan_ui_texts() : [];
              $menu_layanan_cta_href = trim((string) ($nb_brand['menu_layanan_cta_url'] ?? '')) ?: home_url('/kontak/#hubungi-form');
            @endphp
            <div id="mega-pane-layanan" class="mega-pane transition-opacity duration-150 opacity-0 hidden">
              <div class="grid grid-cols-12 gap-10">

                <!-- Column 1: Solusi Industri (6 Sektor) (Col 5) -->
                <div class="col-span-5 border-r border-black/[0.06] pr-8">
                  <div class="mb-4 flex items-center justify-between">
                    <a href="{{ home_url('/layanan/#solusi-industri') }}" class="text-[12px] font-semibold text-[#515154] hover:text-[#0f808a] transition-colors">
                      {{ $menu_ui['menu_industries_heading'] ?? 'Solusi Industri (6 Sektor)' }}
                    </a>
                    <a href="{{ home_url('/layanan/') }}" class="text-[11.5px] font-semibold text-[#0a555c] hover:underline">
                      {{ $menu_ui['menu_hub_link_text'] ?? 'Hub Layanan' }} &rarr;
                    </a>
                  </div>

                  <div class="grid grid-cols-2 gap-x-6 gap-y-3.5">
                    @foreach($menu_sectors as $sec)
                      <a href="{{ esc_url($sec['url']) }}" class="group py-0.5 block transition-colors">
                        <div class="text-[14.5px] font-semibold text-[#1d1d1f] group-hover:text-[#0f808a] transition-colors leading-tight">
                          {{ $sec['card_title'] }}
                        </div>
                        <div class="text-[11.5px] text-[#6e6e73] mt-0.5 font-normal leading-snug line-clamp-2">
                          {{ $sec['subtitle'] }}
                        </div>
                      </a>
                    @endforeach
                  </div>
                </div>

                <!-- Column 2: Layanan Spesialis & Pelatihan (3 Kategori) (Col 4) -->
                <div class="col-span-4 border-r border-black/[0.06] pr-8 flex flex-col justify-between">
                  <div>
                    <p class="text-[12px] font-semibold text-[#515154] mb-4">{{ $menu_ui['menu_categories_heading'] ?? 'Layanan Spesialis & Pelatihan' }}</p>
                    <div class="space-y-4">
                      @foreach($menu_categories as $cat)
                        <a href="{{ esc_url($cat['url']) }}" class="group py-0.5 block transition-colors">
                          <div class="text-[15px] font-semibold text-[#1d1d1f] group-hover:text-[#0f808a] transition-colors leading-tight">
                            {{ $cat['short_title'] }}
                          </div>
                          <div class="text-[11.5px] text-[#6e6e73] mt-0.5 font-normal leading-snug line-clamp-2">
                            {{ $cat['hero_summary'] }}
                          </div>
                        </a>
                      @endforeach
                    </div>
                  </div>

                  <div class="pt-5 mt-5 border-t border-black/[0.06]">
                    <a href="{{ home_url('/layanan/') }}" class="inline-flex items-center text-[#0a555c] text-[13px] font-semibold hover:underline gap-1.5 leading-none group">
                      <span class="leading-none">{{ $menu_ui['menu_all_services_text'] ?? 'Lihat Semua Layanan' }}</span>
                      <svg class="w-[18px] h-[18px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6h12v12"/>
                        <path d="M6 18 18 6"/>
                      </svg>
                    </a>
                  </div>
                </div>

                <!-- Column 3: Hubungi Spesialis Layanan (Col 3) -->
                <div class="col-span-3 flex flex-col justify-between">
                  <div>
                    <p class="text-[12px] font-semibold text-[#515154] mb-4">{!! esc_html($nb_brand['menu_layanan_heading'] ?? 'Dukungan Teknis Langsung') !!}</p>

                    <div class="space-y-4">
                      <div>
                        <div class="text-[14px] font-semibold text-[#1d1d1f] leading-tight">{!! esc_html($nb_brand['menu_layanan_item1_title'] ?? 'Pilot Bersertifikat Resmi') !!}</div>
                        <div class="text-[12px] text-[#6e6e73] mt-0.5 font-normal leading-snug">{!! esc_html($nb_brand['menu_layanan_item1_desc'] ?? 'Tim Operasional & Pemetaan Berpengalaman') !!}</div>
                      </div>

                      <div>
                        <div class="text-[14px] font-semibold text-[#1d1d1f] leading-tight">{!! esc_html($nb_brand['menu_layanan_item2_title'] ?? 'Workshop Sleman Yogyakarta') !!}</div>
                        <div class="text-[12px] text-[#6e6e73] mt-0.5 font-normal leading-snug">{!! esc_html($nb_brand['menu_layanan_item2_desc'] ?? 'Pusat Perakitan, Riset, & Suku Cadang Asli') !!}</div>
                      </div>
                    </div>
                  </div>

                  <div class="pt-6 border-t border-black/[0.06]">
                    <a href="{{ esc_url($menu_layanan_cta_href) }}" class="inline-flex items-center text-[#0a555c] text-[13px] font-semibold hover:underline gap-1.5 leading-none group">
                      <span class="leading-none">{!! esc_html($nb_brand['menu_layanan_cta_text'] ?? 'Jadwalkan Demo & Konsultasi') !!}</span>
                      <svg class="w-[18px] h-[18px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6h12v12"/>
                        <path d="M6 18 18 6"/>
                      </svg>
                    </a>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>

        <!-- Mobile nav — 100% Dinamis dari WordPress -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white/95 backdrop-blur-2xl border-t border-black/[0.06] py-6 px-6 max-h-[85vh] overflow-y-auto">
          <nav aria-label="Navigasi seluler" class="flex flex-col gap-3 text-[16px] font-medium text-[#1d1d1f]">
            <a href="{{ home_url('/') }}" class="mobile-nav-link min-h-[44px] flex items-center border-b border-[#f5f5f7] {{ $is_active_home ? 'text-[#0f808a] font-semibold' : '' }}">Beranda</a>

            <details class="border-b border-[#f5f5f7]" {{ $is_active_produk ? 'open' : '' }}>
              <summary class="min-h-[44px] py-2 cursor-pointer list-none flex items-center justify-between font-semibold {{ $is_active_produk ? 'text-[#0f808a]' : '' }}">Produk Drone
                <svg class="w-4 h-4 {{ $is_active_produk ? 'text-[#0f808a]' : 'text-[#6e6e73]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </summary>
              <div class="pl-3 pb-3 mt-1 flex flex-col gap-1 text-[14px] text-[#515154]">
                @foreach($drones_by_cat_slug as $cslug => $cdata)
                  @if(!empty($cdata['drones']))
                    <p class="text-[12px] font-semibold text-[#6e6e73] mt-2">{!! wp_specialchars_decode($cdata['term']->name) !!}</p>
                    @foreach($cdata['drones'] as $d)
                      @php
                        $is_this_drone = is_single($d['id']) || (is_singular('drone') && get_the_ID() === $d['id']);
                      @endphp
                      <a href="{{ $d['url'] }}" class="mobile-nav-link min-h-[44px] flex items-center justify-between {{ $is_this_drone ? 'text-[#0f808a] font-semibold' : '' }}">
                        <span>{!! wp_specialchars_decode($d['name']) !!}</span>
                        @if($d['badge'])
                        <span class="text-[11px] font-semibold text-[#0a555c]">{{ $d['badge'] }}</span>
                        @endif
                      </a>
                    @endforeach
                  @endif
                @endforeach
                <a href="{{ home_url('/bandingkan/') }}" class="mobile-nav-link min-h-[44px] mt-2 border-t border-black/[0.06] text-[#0a555c] font-semibold flex items-center justify-between {{ is_page('bandingkan') ? 'bg-[#0f808a]/10 px-2.5 rounded-lg' : '' }}">
                  <span>Bandingkan Semua Model</span>
                  <svg class="w-4 h-4 text-[#0a555c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
              </div>
            </details>

            <details class="border-b border-[#f5f5f7]" {{ $is_active_layanan ? 'open' : '' }}>
              <summary class="min-h-[44px] py-2 cursor-pointer list-none flex items-center justify-between font-semibold {{ $is_active_layanan ? 'text-[#0f808a]' : '' }}">Layanan
                <svg class="w-4 h-4 {{ $is_active_layanan ? 'text-[#0f808a]' : 'text-[#6e6e73]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </summary>
              <div class="pl-3 pb-3 mt-1 flex flex-col gap-1 text-[14px] text-[#515154]">
                <a href="{{ home_url('/layanan/') }}" class="mobile-nav-link min-h-[44px] flex items-center font-semibold text-[#0a555c]">
                  {{ $menu_ui['footer_hub_link_text'] ?? 'Semua Layanan UAV (Hub)' }}
                </a>
                <p class="text-[12px] font-semibold text-[#6e6e73] mt-1">{{ $menu_ui['menu_industries_heading'] ?? 'Solusi Industri (6 Sektor)' }}</p>
                @foreach($menu_sectors as $sec)
                  <a href="{{ esc_url($sec['url']) }}" class="mobile-nav-link min-h-[44px] flex items-center">
                    {{ $sec['card_title'] }}
                  </a>
                @endforeach
                <p class="text-[12px] font-semibold text-[#6e6e73] mt-2">{{ $menu_ui['menu_categories_heading'] ?? 'Layanan Spesialis & Pelatihan' }}</p>
                @foreach($menu_categories as $cat)
                  <a href="{{ esc_url($cat['url']) }}" class="mobile-nav-link min-h-[44px] flex items-center">
                    {{ $cat['short_title'] }}
                  </a>
                @endforeach
              </div>
            </details>
            <a href="{{ home_url('/tentang-kami/') }}" class="mobile-nav-link min-h-[44px] flex items-center border-b border-[#f5f5f7] {{ $is_active_about ? 'text-[#0f808a] font-semibold' : '' }}">Tentang Kami</a>
            <a href="{{ home_url('/blog/') }}" class="mobile-nav-link min-h-[44px] flex items-center border-b border-[#f5f5f7] {{ $is_active_blog ? 'text-[#0f808a] font-semibold' : '' }}">Blog</a>
            <a href="{{ home_url('/kontak/') }}" class="mobile-nav-link min-h-[44px] flex items-center {{ $is_active_kontak ? 'text-[#0f808a] font-semibold' : 'text-[#1d1d1f]' }}">Kontak</a>
          </nav>
        </div>
      </header>

      <!-- Dropdown overlay -->
      <div id="layanan-overlay"
           style="position:fixed; top:52px; left:0; right:0; bottom:0; z-index:9998;
                  background:rgba(0,0,0,0.25); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
                  opacity:0; pointer-events:none; transition:opacity 0.22s ease;"
           aria-hidden="true">
      </div>

      <!-- MAIN CONTENT -->
      <main id="main">
        @yield('content')
      </main>

      @php
        $footer_data        = function_exists('\App\fds_get_footer_data') ? \App\fds_get_footer_data() : [];
        $footer_logos       = function_exists('\App\fds_get_footer_logos') ? \App\fds_get_footer_logos() : [];
        $footer_logo_height = intval(get_option('fds_footer_logo_height', 28)) ?: 28;
      @endphp

      <!-- FOOTER -->
      <footer class="bg-[#f5f5f7] border-t border-black/[0.08] pt-14 pb-8">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">

          <!-- Link columns -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-10 pb-10 border-b border-black/[0.08]">
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Produk Drone</h4>
              <ul class="space-y-1 text-[12px] text-[#515154]">
                @foreach($footer_drones as $d)
                <li><a href="{{ esc_url($d['url']) }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">{!! esc_html($d['name']) !!}</a></li>
                @endforeach
              </ul>
            </div>
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">{{ $menu_ui['footer_layanan_heading'] ?? 'Layanan' }}</h4>
              <ul class="space-y-1 text-[12px] text-[#515154]">
                <li><a href="{{ home_url('/layanan/') }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">{{ $menu_ui['footer_hub_link_text'] ?? 'Semua Layanan UAV (Hub)' }}</a></li>
                @foreach($menu_categories as $cat)
                  <li><a href="{{ esc_url($cat['url']) }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">{{ $cat['short_title'] }}</a></li>
                @endforeach
                @if(!empty($menu_sectors['pertambangan']))
                  <li><a href="{{ esc_url($menu_sectors['pertambangan']['url']) }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">Solusi {{ $menu_sectors['pertambangan']['card_title'] }}</a></li>
                @endif
              </ul>
            </div>
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Perusahaan</h4>
              <ul class="space-y-1 text-[12px] text-[#515154]">
                <li><a href="{{ home_url('/tentang-kami/') }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">Tentang PT Karya Solusi Angkasa</a></li>
                @if(!empty($menu_sectors['perkebunan-pertanian']))
                  <li><a href="{{ esc_url($menu_sectors['perkebunan-pertanian']['url']) }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">Solusi {{ $menu_sectors['perkebunan-pertanian']['card_title'] }}</a></li>
                @endif
                @if(!empty($menu_sectors['energi-infrastruktur']))
                  <li><a href="{{ esc_url($menu_sectors['energi-infrastruktur']['url']) }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">Solusi {{ $menu_sectors['energi-infrastruktur']['card_title'] }}</a></li>
                @endif
                <li><a href="{{ home_url('/blog/') }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">Newsroom &amp; Riset</a></li>
                <li><a href="{{ home_url('/kontak/') }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">Pemerintah, BUMN &amp; Korporasi</a></li>
              </ul>
            </div>
            <div class="relative flex flex-col justify-between h-full min-h-[160px] pb-10 md:pb-12">
              <div>
                <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Dukungan</h4>
                <ul class="space-y-1 text-[12px] text-[#515154]">
                  <li><a href="{{ home_url('/kontak/') }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">Hubungi Tim Sales &amp; Engineering</a></li>
                  <li><a href="{{ home_url('/kontak/#lokasi-workshop') }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">Workshop &amp; Demo Unit Yogyakarta</a></li>
                  <li><a href="{{ home_url('/kontak/#hubungi-form') }}" class="inline-block py-1 hover:text-[#1d1d1f] hover:underline">Permintaan Penawaran &amp; RFP</a></li>
                </ul>
              </div>

              @if(!empty($footer_logos))
              <!-- Tablet & Desktop: Pinned absolute right-0 bottom-0 (dimulai dari KANAN, memanjang ke KIRI tanpa offside ke kanan) -->
              <div class="hidden md:flex flex-nowrap items-center gap-4 sm:gap-5 absolute right-0 bottom-0 w-max max-w-none">
                @foreach($footer_logos as $logo)
                  @if(!empty($logo['img']))
                    @php
                      $mw = !empty($logo['max_width']) ? intval($logo['max_width']) : 120;
                      $style_attr = "height: {$footer_logo_height}px; max-width: {$mw}px; mix-blend-mode: multiply !important; background-color: transparent !important;";
                    @endphp
                    @if(!empty($logo['url']) && $logo['url'] !== '#')
                      <a href="{{ esc_url($logo['url']) }}" target="_blank" rel="noopener" class="inline-flex items-center flex-shrink-0" title="{{ esc_attr($logo['name'] ?? 'Logo Partner') }}">
                        <img src="{{ esc_url($logo['img']) }}" alt="{{ esc_attr($logo['name'] ?? 'Logo Partner') }}" width="{{ $mw }}" height="{{ $footer_logo_height }}" class="w-auto object-contain mix-blend-multiply flex-shrink-0" style="{{ $style_attr }}">
                      </a>
                    @else
                      <div class="inline-flex items-center flex-shrink-0" title="{{ esc_attr($logo['name'] ?? 'Logo Partner') }}">
                        <img src="{{ esc_url($logo['img']) }}" alt="{{ esc_attr($logo['name'] ?? 'Logo Partner') }}" width="{{ $mw }}" height="{{ $footer_logo_height }}" class="w-auto object-contain mix-blend-multiply flex-shrink-0" style="{{ $style_attr }}">
                      </div>
                    @endif
                  @endif
                @endforeach
              </div>
              @endif
            </div>

            @if(!empty($footer_logos))
            <!-- Mobile: Dimulai dari KIRI (col-span-2 md:hidden), 1 baris utuh tidak terpotong -->
            <div class="col-span-2 md:hidden pt-2">
              <div class="flex flex-nowrap items-center gap-4 justify-start overflow-x-auto no-scrollbar py-1" style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($footer_logos as $logo)
                  @if(!empty($logo['img']))
                    @php
                      $mw = !empty($logo['max_width']) ? intval($logo['max_width']) : 120;
                      $style_attr = "height: {$footer_logo_height}px; max-width: {$mw}px; mix-blend-mode: multiply !important; background-color: transparent !important;";
                    @endphp
                    @if(!empty($logo['url']) && $logo['url'] !== '#')
                      <a href="{{ esc_url($logo['url']) }}" target="_blank" rel="noopener" class="inline-flex items-center flex-shrink-0" title="{{ esc_attr($logo['name'] ?? 'Logo Partner') }}">
                        <img src="{{ esc_url($logo['img']) }}" alt="{{ esc_attr($logo['name'] ?? 'Logo Partner') }}" width="{{ $mw }}" height="{{ $footer_logo_height }}" class="w-auto object-contain mix-blend-multiply flex-shrink-0" style="{{ $style_attr }}">
                      </a>
                    @else
                      <div class="inline-flex items-center flex-shrink-0" title="{{ esc_attr($logo['name'] ?? 'Logo Partner') }}">
                        <img src="{{ esc_url($logo['img']) }}" alt="{{ esc_attr($logo['name'] ?? 'Logo Partner') }}" width="{{ $mw }}" height="{{ $footer_logo_height }}" class="w-auto object-contain mix-blend-multiply flex-shrink-0" style="{{ $style_attr }}">
                      </div>
                    @endif
                  @endif
                @endforeach
              </div>
            </div>
            @endif
          </div>

          <!-- Middle Row: Company Info (Left) & Social Media (Right) -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 py-8 border-b border-black/[0.08] items-center">
            <!-- Left Side: Company Information -->
            <div class="lg:col-span-8 flex flex-col justify-center">
              <div>
                <p class="text-[13px] font-semibold text-[#1d1d1f]">{!! esc_html($footer_data['company_name'] ?? 'PT Karya Solusi Angkasa (Full Drone Solutions)') !!}</p>
                <p class="text-[12px] text-[#515154] mt-1.5 max-w-2xl leading-relaxed">
                  {!! nl2br(esc_html(preg_replace('/(?<!\s)\R/u', " \n", (string) ($footer_data['address'] ?? 'Jl. Lukito Harjo No.10, Mulungan Kulon, Sendangadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55285')))) !!}
                </p>
              </div>
              <div class="flex flex-wrap items-center gap-4 mt-3 text-[12px] text-[#515154]">
                @if(!empty($footer_data['phone']))
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $footer_data['phone']) }}" class="hover:text-[#0f808a] font-medium transition-colors">Tel: {!! esc_html($footer_data['phone']) !!}</a>
                @endif
                @if(!empty($footer_data['phone']) && !empty($footer_data['email']))
                <span class="text-black/20">&middot;</span>
                @endif
                @if(!empty($footer_data['email']))
                <a href="mailto:{{ esc_attr($footer_data['email']) }}" class="hover:text-[#0f808a] font-medium transition-colors">Email: {!! esc_html($footer_data['email']) !!}</a>
                @endif
              </div>
            </div>

            <!-- Right Side: Social Media Icons -->
            <div class="lg:col-span-4 flex items-center justify-start lg:justify-end">
              <div class="flex items-center gap-2.5 flex-wrap">
                @if(!empty($footer_data['instagram']) && !empty($footer_data['instagram_active']))
                <!-- Instagram -->
                <a href="{{ esc_url($footer_data['instagram']) }}" target="_blank" rel="noopener" aria-label="Instagram FDS"
                   class="w-11 h-11 rounded-full bg-white hover:bg-[#0f808a] text-[#515154] hover:text-white border border-black/[0.08] hover:border-[#0f808a] flex items-center justify-center transition-all duration-200 shadow-sm hover:scale-105 group">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </a>
                @endif

                @if(!empty($footer_data['youtube']) && !empty($footer_data['youtube_active']))
                <!-- YouTube -->
                <a href="{{ esc_url($footer_data['youtube']) }}" target="_blank" rel="noopener" aria-label="YouTube FDS"
                   class="w-11 h-11 rounded-full bg-white hover:bg-[#FF0000] text-[#515154] hover:text-white border border-black/[0.08] hover:border-[#FF0000] flex items-center justify-center transition-all duration-200 shadow-sm hover:scale-105 group">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                @endif

                @if(!empty($footer_data['linkedin']) && !empty($footer_data['linkedin_active']))
                <!-- LinkedIn -->
                <a href="{{ esc_url($footer_data['linkedin']) }}" target="_blank" rel="noopener" aria-label="LinkedIn FDS"
                   class="w-11 h-11 rounded-full bg-white hover:bg-[#0077B5] text-[#515154] hover:text-white border border-black/[0.08] hover:border-[#0077B5] flex items-center justify-center transition-all duration-200 shadow-sm hover:scale-105 group">
                  <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
                @endif

                @if(!empty($footer_data['tiktok']) && !empty($footer_data['tiktok_active']))
                <!-- TikTok -->
                <a href="{{ esc_url($footer_data['tiktok']) }}" target="_blank" rel="noopener" aria-label="TikTok FDS"
                   class="w-11 h-11 rounded-full bg-white hover:bg-[#000000] text-[#515154] hover:text-white border border-black/[0.08] hover:border-black flex items-center justify-center transition-all duration-200 shadow-sm hover:scale-105 group">
                  <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                </a>
                @endif

                @if(!empty($footer_data['twitter']) && !empty($footer_data['twitter_active']))
                <!-- Twitter / X -->
                <a href="{{ esc_url($footer_data['twitter']) }}" target="_blank" rel="noopener" aria-label="Twitter / X FDS"
                   class="w-11 h-11 rounded-full bg-white hover:bg-black text-[#515154] hover:text-white border border-black/[0.08] hover:border-black flex items-center justify-center transition-all duration-200 shadow-sm hover:scale-105 group">
                  <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                @endif

                @if(!empty($footer_data['whatsapp']) && !empty($footer_data['whatsapp_active']))
                <!-- WhatsApp Direct -->
                <a href="{{ esc_url($footer_data['whatsapp']) }}" target="_blank" rel="noopener" aria-label="WhatsApp FDS"
                   class="w-11 h-11 rounded-full bg-white hover:bg-[#25D366] text-[#515154] hover:text-white border border-black/[0.08] hover:border-[#25D366] flex items-center justify-center transition-all duration-200 shadow-sm hover:scale-105 group">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </a>
                @endif
              </div>
            </div>
          </div>

        </div>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
          <!-- Bottom bar (Copyright & Links) -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 text-[11px] text-[#86868b]">
            <span>{!! esc_html($footer_data['copyright'] ?? ('Copyright © ' . date('Y') . ' PT Karya Solusi Angkasa (Full Drone Solutions). Hak cipta dilindungi.')) !!}</span>
            <div class="flex items-center gap-4">
              <a href="{{ esc_url(!empty($footer_data['privacy_url']) && $footer_data['privacy_url'] !== '#' ? $footer_data['privacy_url'] : home_url('/privacy-policy/')) }}" class="hover:text-[#1d1d1f] hover:underline">Kebijakan Privasi</a>
              <span class="h-3 w-px bg-black/10"></span>
              <a href="{{ esc_url(!empty($footer_data['terms_url']) && $footer_data['terms_url'] !== '#' ? $footer_data['terms_url'] : home_url('/disclaimer/')) }}" class="hover:text-[#1d1d1f] hover:underline">Ketentuan Layanan</a>
            </div>
          </div>
        </div>
      </footer>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
      // ── Mobile Menu Toggle ──────────────────────────────────
      const btn = document.getElementById('mobile-menu-toggle');
      const menu = document.getElementById('mobile-menu');
      const bar1 = document.getElementById('bar1');
      const bar2 = document.getElementById('bar2');
      const bar3 = document.getElementById('bar3');
      let open = false;

      if (btn && menu) {
        btn.setAttribute('aria-expanded', 'false');
        btn.addEventListener('click', () => {
          open = !open;
          menu.classList.toggle('hidden', !open);
          btn.setAttribute('aria-expanded', open ? 'true' : 'false');
          document.body.style.overflow = open ? 'hidden' : '';
          if (open) {
            bar1.style.transform = 'translateY(6px) rotate(45deg)';
            bar2.style.opacity = '0';
            bar3.style.transform = 'translateY(-6px) rotate(-45deg)';
          } else {
            bar1.style.transform = '';
            bar2.style.opacity = '';
            bar3.style.transform = '';
          }
        });

        document.querySelectorAll('.mobile-nav-link').forEach(link => {
          link.addEventListener('click', () => {
            open = false;
            menu.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
            bar1.style.transform = '';
            bar2.style.opacity = '';
            bar3.style.transform = '';
          });
        });
      }

      // ── Unified Flicker-Free & Keyboard-Accessible Mega Menu Controller ─
      const siteHeader = document.getElementById('site-header');
      const drawer     = document.getElementById('mega-menu-drawer');
      const overlay    = document.getElementById('layanan-overlay');

      const produkTrigger   = document.getElementById('produk-trigger');
      const produkToggleBtn = document.getElementById('produk-toggle-btn');
      const produkChevron   = document.getElementById('produk-chevron');
      const paneProduk      = document.getElementById('mega-pane-produk');

      const layananTrigger   = document.getElementById('layanan-trigger');
      const layananToggleBtn = document.getElementById('layanan-toggle-btn');
      const layananChevron   = document.getElementById('layanan-chevron');
      const paneLayanan      = document.getElementById('mega-pane-layanan');

      let currentMenu = null;
      let closeTimer  = null;

      function syncAriaExpanded(activeType) {
        if (produkToggleBtn) {
          produkToggleBtn.setAttribute('aria-expanded', activeType === 'produk' ? 'true' : 'false');
        }
        if (layananToggleBtn) {
          layananToggleBtn.setAttribute('aria-expanded', activeType === 'layanan' ? 'true' : 'false');
        }
      }

      function openMegaMenu(type) {
        cancelClose();

        const isAlreadyOpen = (currentMenu !== null);

        if (type === 'produk') {
          if (produkChevron) produkChevron.style.transform = 'rotate(180deg)';
          if (layananChevron) layananChevron.style.transform = 'rotate(0deg)';

          if (paneLayanan) {
            paneLayanan.classList.add('hidden');
            paneLayanan.style.opacity = '0';
          }
          if (paneProduk) {
            paneProduk.classList.remove('hidden');
            requestAnimationFrame(() => {
              paneProduk.style.opacity = '1';
            });
          }
        } else if (type === 'layanan') {
          if (layananChevron) layananChevron.style.transform = 'rotate(180deg)';
          if (produkChevron) produkChevron.style.transform = 'rotate(0deg)';

          if (paneProduk) {
            paneProduk.classList.add('hidden');
            paneProduk.style.opacity = '0';
          }
          if (paneLayanan) {
            paneLayanan.classList.remove('hidden');
            requestAnimationFrame(() => {
              paneLayanan.style.opacity = '1';
            });
          }
        }

        currentMenu = type;
        syncAriaExpanded(type);

        if (!isAlreadyOpen && drawer) {
          drawer.style.opacity = '1';
          drawer.style.transform = 'translateY(0)';
          drawer.style.pointerEvents = 'auto';
          if (siteHeader) siteHeader.style.borderBottomColor = 'transparent';
          if (overlay) {
            overlay.style.opacity = '1';
            overlay.style.pointerEvents = 'auto';
          }
        }
      }

      function toggleMegaMenu(type) {
        if (currentMenu === type) {
          closeMegaMenu();
        } else {
          openMegaMenu(type);
        }
      }

      function scheduleClose(delay = 200) {
        if (closeTimer) clearTimeout(closeTimer);
        closeTimer = setTimeout(() => {
          closeMegaMenu();
        }, delay);
      }

      function cancelClose() {
        if (closeTimer) {
          clearTimeout(closeTimer);
          closeTimer = null;
        }
      }

      function closeMegaMenu() {
        cancelClose();
        if (drawer) {
          drawer.style.opacity = '0';
          drawer.style.transform = 'translateY(-6px)';
          drawer.style.pointerEvents = 'none';
        }
        if (produkChevron) produkChevron.style.transform = 'rotate(0deg)';
        if (layananChevron) layananChevron.style.transform = 'rotate(0deg)';
        if (siteHeader) siteHeader.style.borderBottomColor = '';
        if (overlay) {
          overlay.style.opacity = '0';
          overlay.style.pointerEvents = 'none';
        }
        currentMenu = null;
        syncAriaExpanded(null);
      }

      if (produkTrigger) {
        produkTrigger.addEventListener('mouseenter', () => openMegaMenu('produk'));
      }
      if (produkToggleBtn) {
        produkToggleBtn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          toggleMegaMenu('produk');
        });
      }
      if (layananTrigger) {
        layananTrigger.addEventListener('mouseenter', () => openMegaMenu('layanan'));
      }
      if (layananToggleBtn) {
        layananToggleBtn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          toggleMegaMenu('layanan');
        });
      }

      if (drawer) {
        drawer.addEventListener('mouseenter', cancelClose);
        drawer.addEventListener('mouseleave', () => scheduleClose(200));
      }

      if (siteHeader) {
        siteHeader.addEventListener('mouseenter', cancelClose);
        siteHeader.addEventListener('mouseleave', () => scheduleClose(200));
      }

      if (overlay) {
        overlay.addEventListener('mouseenter', () => closeMegaMenu());
        overlay.addEventListener('click', () => closeMegaMenu());
      }

      document.querySelectorAll('.nav-direct-link').forEach(link => {
        link.addEventListener('mouseenter', () => closeMegaMenu());
        link.addEventListener('focus', () => closeMegaMenu());
      });

      document.addEventListener('click', (e) => {
        if (currentMenu && siteHeader && !siteHeader.contains(e.target)) {
          closeMegaMenu();
        }
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && currentMenu) {
          const prev = currentMenu;
          closeMegaMenu();
          if (prev === 'produk' && produkToggleBtn) produkToggleBtn.focus();
          if (prev === 'layanan' && layananToggleBtn) layananToggleBtn.focus();
        }
      });
    });
    </script>

    <?php do_action('get_footer'); ?>
    <?php wp_footer(); ?>
  </body>
</html>
