<!doctype html>
<html <?php language_attributes(); ?> class="scroll-smooth">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

      /* WP Admin Bar compensation */
      .admin-bar #site-header { top: 32px; }
      @media screen and (max-width: 782px) {
        .admin-bar #site-header { top: 46px; }
        #wpadminbar { position: fixed !important; }
      }
    </style>

    <?php do_action('get_header'); ?>
    <?php wp_head(); ?>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body <?php body_class('bg-[#f5f5f7] text-[#1d1d1f] antialiased'); ?>>
    <?php wp_body_open(); ?>

    @php
      // 100% Dynamic WP Query from 'kategori_drone' Taxonomy & 'drone' CPT
      $tax_terms = get_terms([
          'taxonomy'   => 'kategori_drone',
          'hide_empty' => false,
          'orderby'    => 'term_id',
          'order'      => 'ASC',
      ]);

      if (empty($tax_terms) || is_wp_error($tax_terms)) {
          $tax_terms = [
              (object)['name' => 'Agrikultur', 'slug' => 'agrikultur', 'description' => 'Pertanian Presisi FERTO 5L – 50L'],
              (object)['name' => 'Pemetaan & GIS', 'slug' => 'pemetaan-gis', 'description' => 'Survei Geospasial & Inspeksi Aset'],
              (object)['name' => 'Kargo', 'slug' => 'kargo', 'description' => 'Distribusi Logistik Cepat 10 kg'],
              (object)['name' => 'Reboisasi', 'slug' => 'reboisasi', 'description' => 'Restorasi Hutan & Penabur Biji'],
          ];
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
          $drones_by_cat_slug[$t->slug] = [
              'term'   => $t,
              'drones' => [],
          ];
      }

      foreach ($nav_drones as $nd) {
          $terms = get_the_terms($nd->ID, 'kategori_drone');
          $cat_slug = '';
          if (!empty($terms) && !is_wp_error($terms)) {
              $cat_slug = $terms[0]->slug;
          } else {
              $meta_cat = get_post_meta($nd->ID, 'drone_kategori', true) ?: 'Agrikultur';
              $cat_slug = sanitize_title($meta_cat);
          }

          if (!isset($drones_by_cat_slug[$cat_slug])) {
              $drones_by_cat_slug[$cat_slug] = [
                  'term'   => (object)['name' => (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : $meta_cat, 'slug' => $cat_slug, 'description' => ''],
                  'drones' => [],
              ];
          }

          $d_specs = get_post_meta($nd->ID, 'drone_specs_raw', true);
          $spec_preview = '';
          if ($d_specs) {
              $lines = array_slice(array_filter(array_map('trim', explode("\n", $d_specs))), 0, 2);
              $parts = [];
              foreach ($lines as $l) {
                  $sp = explode(':', $l, 2);
                  if (count($sp) === 2) $parts[] = trim($sp[1]);
              }
              $spec_preview = implode(' · ', $parts);
          }

          $drones_by_cat_slug[$cat_slug]['drones'][] = [
              'id'       => $nd->ID,
              'name'     => html_entity_decode($nd->post_title, ENT_QUOTES, 'UTF-8'),
              'slug'     => $nd->post_name,
              'badge'    => html_entity_decode(get_post_meta($nd->ID, 'drone_badge', true) ?: 'Unggulan', ENT_QUOTES, 'UTF-8'),
              'desc'     => html_entity_decode(get_post_meta($nd->ID, 'drone_tagline', true) ?: wp_trim_words(get_post_meta($nd->ID, 'drone_desc', true) ?: $nd->post_content, 12), ENT_QUOTES, 'UTF-8'),
              'spec'     => html_entity_decode($spec_preview ?: 'Spesifikasi Lengkap', ENT_QUOTES, 'UTF-8'),
          ];
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

            <!-- Logo -->
            <a href="{{ home_url('/') }}" class="nav-direct-link flex items-center gap-2.5 group">
              <svg class="w-5 h-5 text-[#1d1d1f]" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 2L3 6l7 4 7-4-7-4zM3 14l7 4 7-4M3 10l7 4 7-4"/>
              </svg>
              <span class="text-[15px] font-semibold text-[#1d1d1f] tracking-tight">Full Drone Solutions</span>
            </a>

            <!-- Nav links -->
            <nav class="hidden lg:flex items-center gap-7 text-[13px] font-medium text-[#515154]">
              <a href="{{ home_url('/') }}" class="nav-direct-link hover:text-[#1d1d1f] transition-colors duration-150 py-2">Beranda</a>

              <!-- Produk dropdown trigger -->
              <div id="produk-trigger" class="relative flex items-center gap-1 cursor-pointer group select-none py-2">
                <span class="hover:text-[#1d1d1f] transition-colors duration-150">Produk</span>
                <svg id="produk-chevron" class="w-3 h-3 text-[#86868b] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>

              <!-- Layanan dropdown trigger -->
              <div id="layanan-trigger" class="relative flex items-center gap-1 cursor-pointer group select-none py-2">
                <span class="hover:text-[#1d1d1f] transition-colors duration-150">Layanan</span>
                <svg id="layanan-chevron" class="w-3 h-3 text-[#86868b] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>

              <a href="{{ home_url('/tentang-kami') }}" class="nav-direct-link hover:text-[#1d1d1f] transition-colors duration-150 py-2">Tentang Kami</a>
              <a href="{{ home_url('/blog') }}" class="nav-direct-link hover:text-[#1d1d1f] transition-colors duration-150 py-2">Blog</a>
            </nav>

            <!-- CTA -->
            <div class="flex items-center gap-4">
              <a href="{{ home_url('/#kontak') }}" class="nav-direct-link hidden sm:inline-flex items-center bg-[#0066cc] hover:bg-[#0055b0] active:scale-[0.97] text-white text-[13px] font-semibold px-4 py-2 rounded-full transition-all duration-150">
                Hubungi Kami
              </a>
              <button id="mobile-menu-toggle" type="button" class="lg:hidden w-8 h-8 flex flex-col gap-1.5 items-center justify-center" aria-label="Menu">
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
                $other_cats = [];
                foreach ($drones_by_cat_slug as $k => $v) {
                    if ($k !== 'agrikultur') {
                        $other_cats[$k] = $v;
                    }
                }
                if (!$agri_cat && !empty($drones_by_cat_slug)) {
                    $first_key = array_key_first($drones_by_cat_slug);
                    $agri_cat = $drones_by_cat_slug[$first_key];
                    unset($other_cats[$first_key]);
                }

                $agri_limit = 6;
                $agri_drones = $agri_cat ? array_slice($agri_cat['drones'], 0, $agri_limit) : [];
                $agri_remaining = $agri_cat ? max(0, count($agri_cat['drones']) - $agri_limit) : 0;
              @endphp

              <div class="grid grid-cols-12 gap-10">
                
                <!-- Column 1 & 2: Kategori Agrikultur (Col 6) -->
                <div class="col-span-6 border-r border-black/[0.06] pr-10">
                  <div class="mb-5">
                    <p class="text-[12px] font-semibold text-[#86868b]">
                      {!! !empty($agri_cat['term']->name) ? wp_specialchars_decode($agri_cat['term']->name) : 'UAV Agrikultur' !!} (Seri FERTO)
                    </p>
                  </div>

                  @if(!empty($agri_drones))
                  <div class="grid grid-cols-2 gap-x-8 gap-y-4">
                    @foreach($agri_drones as $item)
                    <a href="{{ home_url('/' . $item['slug']) }}" class="group py-1 block transition-colors">
                      <div class="text-[16px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors leading-tight">
                        {!! $item['name'] !!}
                      </div>
                      <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">
                        {!! $item['spec'] ?: $item['desc'] !!}
                      </div>
                    </a>
                    @endforeach

                    @if($agri_remaining > 0)
                    <div class="col-span-2 pt-2 border-t border-black/[0.04]">
                      <a href="{{ home_url('/#solusi') }}" class="inline-flex items-center text-[12px] font-semibold text-[#0066cc] hover:underline gap-1">
                        + Lihat {{ $agri_remaining }} Drone Agrikultur lainnya di Beranda &rsaquo;
                      </a>
                    </div>
                    @endif
                  </div>
                  @else
                  <p class="text-[13px] text-[#86868b]">Belum ada drone pada kategori ini.</p>
                  @endif
                </div>

                <!-- Column 3: Kategori Lainnya Dinamis dari WP (Col 3) -->
                <div class="col-span-3 border-r border-black/[0.06] pr-8 space-y-6">
                  @forelse($other_cats as $cslug => $cdata)
                    @if(!empty($cdata['drones']))
                      @php
                        $sub_limit = 3;
                        $sub_drones = array_slice($cdata['drones'], 0, $sub_limit);
                        $sub_remaining = max(0, count($cdata['drones']) - $sub_limit);
                      @endphp
                      <div class="{{ !$loop->first ? 'pt-4 border-t border-black/[0.06]' : '' }}">
                        <p class="text-[12px] font-semibold text-[#86868b] mb-3">
                          {!! wp_specialchars_decode($cdata['term']->name) !!}
                        </p>
                        
                        <div class="space-y-3">
                          @foreach($sub_drones as $item)
                          <a href="{{ home_url('/' . $item['slug']) }}" class="group py-0.5 block transition-colors">
                            <div class="text-[15px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors leading-tight">
                              {!! $item['name'] !!}
                            </div>
                            <div class="text-[11px] text-[#86868b] mt-0.5 font-normal leading-snug">
                              {!! $item['spec'] ?: $item['desc'] !!}
                            </div>
                          </a>
                          @endforeach

                          @if($sub_remaining > 0)
                          <a href="{{ home_url('/#solusi') }}" class="inline-flex items-center text-[11px] font-semibold text-[#0066cc] hover:underline pt-1">
                            + Lihat {{ $sub_remaining }} Drone lainnya &rsaquo;
                          </a>
                          @endif
                        </div>
                      </div>
                    @endif
                  @empty
                    <p class="text-[13px] text-[#86868b]">Belum ada kategori lainnya.</p>
                  @endforelse
                </div>

                <!-- Column 4: Ekosistem & Standar (Col 3) -->
                <div class="col-span-3 flex flex-col justify-between">
                  <div>
                    <p class="text-[12px] font-semibold text-[#86868b] mb-5">Ekosistem &amp; Standar</p>
                    
                    <div class="space-y-4">
                      <div>
                        <div class="text-[14px] font-semibold text-[#1d1d1f] leading-tight">FDS Station GCS</div>
                        <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Software Ground Control Bahasa Indonesia</div>
                      </div>

                      <div>
                        <div class="text-[14px] font-semibold text-[#1d1d1f] leading-tight">Sertifikasi TKDN + BMP</div>
                        <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Nilai kandungan lokal mencapai 60,74%</div>
                      </div>

                      <div>
                        <div class="text-[14px] font-semibold text-[#1d1d1f] leading-tight">Standar SNI 9199:2023</div>
                        <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Teruji standar mutu pertanian nasional</div>
                      </div>
                    </div>
                  </div>

                  <div class="pt-6 border-t border-black/[0.06]">
                    <a href="{{ home_url('/#solusi') }}" class="inline-flex items-center text-[#0066cc] text-[13px] font-semibold hover:underline gap-1 group">
                      Bandingkan Semua Spesifikasi
                      <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                  </div>
                </div>

              </div>
            </div>

            <!-- 2. PANE LAYANAN (Editorial Multi-Column) -->
            <div id="mega-pane-layanan" class="mega-pane transition-opacity duration-150 opacity-0 hidden">
              <div class="grid grid-cols-12 gap-10">
                
                <!-- Column 1: Pelatihan & Operasional (Col 4) -->
                <div class="col-span-4 border-r border-black/[0.06] pr-8">
                  <p class="text-[12px] font-semibold text-[#86868b] mb-5">Pelatihan &amp; Operasional</p>
                  
                  <div class="space-y-4">
                    <a href="{{ home_url('/#layanan') }}" class="group py-1 block transition-colors">
                      <div class="text-[16px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors leading-tight">Kursus &amp; Sertifikasi Pilot</div>
                      <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Sertifikasi resmi CASR Part 107 &amp; training lapangan intensif</div>
                    </a>

                    <a href="{{ home_url('/#kontak') }}" class="group py-1 block transition-colors">
                      <div class="text-[16px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors leading-tight">Sewa Armada Drone</div>
                      <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Unit sprayer &amp; spreader komersial siap pakai lengkap pilot</div>
                    </a>

                    <a href="{{ home_url('/#kontak') }}" class="group py-1 block transition-colors">
                      <div class="text-[16px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors leading-tight">After-Sales &amp; Servis</div>
                      <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Garansi resmi &amp; ketersediaan suku cadang asli lokal Yogyakarta</div>
                    </a>
                  </div>
                </div>

                <!-- Column 2: Survei Geospasial & Analitik (Col 5) -->
                <div class="col-span-5 border-r border-black/[0.06] pr-8">
                  <p class="text-[12px] font-semibold text-[#86868b] mb-5">Survei &amp; Inspeksi Teknis</p>
                  
                  <div class="space-y-4">
                    <a href="{{ home_url('/#layanan') }}" class="group py-1 block transition-colors">
                      <div class="text-[16px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors leading-tight">Pemetaan Aerial &amp; GIS Topografi</div>
                      <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Model 3D elevasi, ortofoto sub-sentimeter, &amp; data siap CAD/BIM</div>
                    </a>

                    <a href="{{ home_url('/#layanan') }}" class="group py-1 block transition-colors">
                      <div class="text-[16px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors leading-tight">Inspeksi Termal Transmisi &amp; Solar PV</div>
                      <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Pemeriksaan sensor inframerah untuk transmisi 150kV &amp; pipa migas</div>
                    </a>

                    <a href="{{ home_url('/#layanan') }}" class="group py-1 block transition-colors">
                      <div class="text-[16px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors leading-tight">Analisis Vegetasi &amp; NDVI</div>
                      <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Deteksi dini kesehatan tanaman &amp; rekomendasi pemupukan presisi</div>
                    </a>
                  </div>
                </div>

                <!-- Column 3: Hubungi Spesialis Layanan (Col 3) -->
                <div class="col-span-3 flex flex-col justify-between">
                  <div>
                    <p class="text-[12px] font-semibold text-[#86868b] mb-5">Dukungan Teknis</p>
                    
                    <div class="space-y-4">
                      <div>
                        <div class="text-[14px] font-semibold text-[#1d1d1f] leading-tight">Pilot Bersertifikat Resmi</div>
                        <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Instruktur berpengalaman di ratusan misi lapangan</div>
                      </div>

                      <div>
                        <div class="text-[14px] font-semibold text-[#1d1d1f] leading-tight">Workshop Yogyakarta</div>
                        <div class="text-[12px] text-[#86868b] mt-0.5 font-normal leading-snug">Pusat perakitan, riset terpadu, dan kalibrasi UAV</div>
                      </div>
                    </div>
                  </div>

                  <div class="pt-6 border-t border-black/[0.06]">
                    <a href="{{ home_url('/#kontak') }}" class="inline-flex items-center text-[#0066cc] text-[13px] font-semibold hover:underline gap-1 group">
                      Jadwalkan Demo &amp; Konsultasi
                      <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>

        <!-- Mobile nav — 100% Dinamis dari WordPress -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white/95 backdrop-blur-2xl border-t border-black/[0.06] py-6 px-6 max-h-[85vh] overflow-y-auto">
          <nav class="flex flex-col gap-4 text-[16px] font-medium text-[#1d1d1f]">
            <a href="{{ home_url('/') }}" class="mobile-nav-link py-1.5 border-b border-[#f5f5f7]">Beranda</a>
            
            <details class="border-b border-[#f5f5f7]">
              <summary class="py-1.5 cursor-pointer list-none flex items-center justify-between font-semibold">Produk Drone
                <svg class="w-4 h-4 text-[#86868b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </summary>
              <div class="pl-3 pb-3 mt-2 flex flex-col gap-1.5 text-[14px] text-[#515154]">
                @foreach($drones_by_cat_slug as $cslug => $cdata)
                  @if(!empty($cdata['drones']))
                    <p class="text-[12px] font-semibold text-[#86868b] mt-2">{!! wp_specialchars_decode($cdata['term']->name) !!}</p>
                    @foreach($cdata['drones'] as $d)
                      <a href="{{ home_url('/' . $d['slug']) }}" class="mobile-nav-link py-1 flex items-center justify-between">
                        <span>{!! wp_specialchars_decode($d['name']) !!}</span>
                        @if($d['badge'])
                        <span class="text-[10px] text-[#0066cc] bg-[#0066cc]/10 px-2 py-0.5 rounded-full">{{ $d['badge'] }}</span>
                        @endif
                      </a>
                    @endforeach
                  @endif
                @endforeach
              </div>
            </details>

            <details class="border-b border-[#f5f5f7]">
              <summary class="py-1.5 cursor-pointer list-none flex items-center justify-between font-semibold">Layanan
                <svg class="w-4 h-4 text-[#86868b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </summary>
              <div class="pl-3 pb-3 mt-2 flex flex-col gap-1.5 text-[14px] text-[#515154]">
                <a href="{{ home_url('/#layanan') }}" class="mobile-nav-link py-1">Pelatihan &amp; Sertifikasi Pilot</a>
                <a href="{{ home_url('/#kontak') }}" class="mobile-nav-link py-1">Pesewaan Drone</a>
                <a href="{{ home_url('/#layanan') }}" class="mobile-nav-link py-1">Pemetaan Aerial &amp; GIS</a>
                <a href="{{ home_url('/#layanan') }}" class="mobile-nav-link py-1">Inspeksi Termal &amp; Industri</a>
                <a href="{{ home_url('/#kontak') }}" class="mobile-nav-link py-1">After-Sales &amp; Maintenance</a>
              </div>
            </details>
            <a href="{{ home_url('/tentang-kami') }}" class="mobile-nav-link py-1.5 border-b border-[#f5f5f7]">Tentang Kami</a>
            <a href="{{ home_url('/blog') }}" class="mobile-nav-link py-1.5 border-b border-[#f5f5f7]">Blog</a>
            <a href="{{ home_url('/#kontak') }}" class="mobile-nav-link py-1.5 text-[#0066cc] font-semibold">Hubungi Kami</a>
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

      <!-- FOOTER -->
      <footer class="bg-[#f5f5f7] border-t border-black/[0.08] pt-14 pb-8">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">

          <!-- Disclaimer -->
          <div class="border-b border-black/[0.08] pb-8 mb-10">
            <p class="text-[11px] text-[#86868b] leading-relaxed max-w-3xl">
              PT Karya Solusi Angkasa (Full Drone Solutions) &mdash; Advanced UAV Engineering, Manufacturing & AI Technology. Sertifikasi ISO 9001:2015, SNI 9199:2023, serta Sertifikasi Nilai TKDN + BMP mencapai 60,74% diterbitkan resmi oleh Kementerian Perindustrian Republik Indonesia. Spesifikasi dapat disesuaikan dengan kebutuhan misi kustom.
            </p>
          </div>

          <!-- Link columns -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-10 pb-10 border-b border-black/[0.08]">
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Produk Drone</h4>
              <ul class="space-y-2 text-[12px] text-[#515154]">
                @foreach($nav_drones as $d)
                <li><a href="{{ home_url('/' . $d->post_name) }}" class="hover:text-[#1d1d1f] hover:underline">{!! wp_specialchars_decode($d->post_title) !!}</a></li>
                @endforeach
              </ul>
            </div>
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Layanan</h4>
              <ul class="space-y-2.5 text-[12px] text-[#515154]">
                <li><a href="{{ home_url('/#layanan') }}" class="hover:text-[#1d1d1f] hover:underline">Kursus &amp; Sertifikasi Pilot</a></li>
                <li><a href="{{ home_url('/#kontak') }}" class="hover:text-[#1d1d1f] hover:underline">Pesewaan Drone</a></li>
                <li><a href="{{ home_url('/#layanan') }}" class="hover:text-[#1d1d1f] hover:underline">Pemetaan Aerial &amp; GIS</a></li>
                <li><a href="{{ home_url('/#layanan') }}" class="hover:text-[#1d1d1f] hover:underline">Inspeksi Termal &amp; Industri</a></li>
                <li><a href="{{ home_url('/#kontak') }}" class="hover:text-[#1d1d1f] hover:underline">After-Sales &amp; Servis</a></li>
              </ul>
            </div>
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Perusahaan</h4>
              <ul class="space-y-2.5 text-[12px] text-[#515154]">
                <li><a href="{{ home_url('/tentang-kami') }}" class="hover:text-[#1d1d1f] hover:underline">Tentang PT Karya Solusi Angkasa</a></li>
                <li><a href="{{ home_url('/blog') }}" class="hover:text-[#1d1d1f] hover:underline">Newsroom &amp; Riset</a></li>
                <li><a href="{{ home_url('/#kontak') }}" class="hover:text-[#1d1d1f] hover:underline">Pemerintah, BUMN &amp; Korporasi</a></li>
                <li><a href="{{ home_url('/#solusi') }}" class="hover:text-[#1d1d1f] hover:underline">Solusi Multi-Industri &amp; AI</a></li>
              </ul>
            </div>
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Dukungan</h4>
              <ul class="space-y-2.5 text-[12px] text-[#515154]">
                <li><a href="{{ home_url('/#kontak') }}" class="hover:text-[#1d1d1f] hover:underline">Hubungi Tim Sales &amp; Engineering</a></li>
                <li><a href="{{ home_url('/#kontak') }}" class="hover:text-[#1d1d1f] hover:underline">Workshop &amp; Demo Unit Yogyakarta</a></li>
                <li><a href="{{ home_url('/#kontak') }}" class="hover:text-[#1d1d1f] hover:underline">Garansi &amp; Suku Cadang Asli</a></li>
              </ul>
            </div>
          </div>

          <!-- Bottom bar -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 text-[11px] text-[#86868b]">
            <span>Copyright &copy; {{ date('Y') }} PT Karya Solusi Angkasa (Full Drone Solutions). Hak cipta dilindungi.</span>
            <div class="flex items-center gap-4">
              <a href="#" class="hover:text-[#1d1d1f] hover:underline">Kebijakan Privasi</a>
              <span class="h-3 w-px bg-black/10"></span>
              <a href="#" class="hover:text-[#1d1d1f] hover:underline">Ketentuan Layanan</a>
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
        btn.addEventListener('click', () => {
          open = !open;
          menu.classList.toggle('hidden', !open);
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
            document.body.style.overflow = '';
            bar1.style.transform = '';
            bar2.style.opacity = '';
            bar3.style.transform = '';
          });
        });
      }

      // ── Unified Flicker-Free Mega Menu Controller ───────────
      const siteHeader = document.getElementById('site-header');
      const drawer     = document.getElementById('mega-menu-drawer');
      const overlay    = document.getElementById('layanan-overlay');

      const produkTrigger = document.getElementById('produk-trigger');
      const produkChevron = document.getElementById('produk-chevron');
      const paneProduk    = document.getElementById('mega-pane-produk');

      const layananTrigger = document.getElementById('layanan-trigger');
      const layananChevron = document.getElementById('layanan-chevron');
      const paneLayanan    = document.getElementById('mega-pane-layanan');

      let currentMenu = null;
      let closeTimer  = null;

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
      }

      if (produkTrigger) {
        produkTrigger.addEventListener('mouseenter', () => openMegaMenu('produk'));
      }
      if (layananTrigger) {
        layananTrigger.addEventListener('mouseenter', () => openMegaMenu('layanan'));
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
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMegaMenu();
      });
    });
    </script>

    <?php do_action('get_footer'); ?>
    <?php wp_footer(); ?>
  </body>
</html>
