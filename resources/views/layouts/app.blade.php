<!doctype html>
<html @php(language_attributes()) class="scroll-smooth">
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

      /* WP Admin Bar compensation &mdash; push our fixed navbar below the admin bar */
      .admin-bar #site-header { top: 32px; }
      @media screen and (max-width: 782px) {
        .admin-bar #site-header { top: 46px; }
        #wpadminbar { position: fixed !important; }
      }
    </style>

    @php(do_action('get_header'))
    @php(wp_head())
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body @php(body_class('bg-[#f5f5f7] text-[#1d1d1f] antialiased'))>
    @php(wp_body_open())

    <div id="app">
      <a class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-white focus:text-black focus:rounded-lg focus:shadow-lg" href="#main">
        Lewati ke konten utama
      </a>

      <!-- ============================================================ -->
      <!-- NAVBAR &mdash; Apple-style glassmorphism, light, refined           -->
      <!-- ============================================================ -->
      <header id="site-header" class="fixed top-0 inset-x-0 z-[9999] bg-white border-b border-black/[0.08]" style="transition: background 0.2s ease;">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
          <div class="flex items-center justify-between h-[52px]">

            <!-- Logo: wordmark only, clean -->
            <a href="{{ home_url('/') }}" class="flex items-center gap-2.5 group">
              <svg class="w-5 h-5 text-[#1d1d1f]" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 2L3 6l7 4 7-4-7-4zM3 14l7 4 7-4M3 10l7 4 7-4"/>
              </svg>
              <span class="text-[15px] font-semibold text-[#1d1d1f] tracking-tight">Full Drone Solutions</span>
            </a>

            <!-- Nav links -->
            <nav class="hidden lg:flex items-center gap-7 text-[13px] font-medium text-[#515154]">
              <a href="{{ home_url('/#produk') }}" class="hover:text-[#1d1d1f] transition-colors duration-150">Produk</a>

              <!-- Layanan dropdown trigger -->
              <div id="layanan-trigger" class="relative flex items-center gap-1 cursor-pointer group select-none">
                <span class="hover:text-[#1d1d1f] transition-colors duration-150">Layanan</span>
                <svg id="layanan-chevron" class="w-3 h-3 text-[#86868b] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>

              <a href="{{ home_url('/tentang-kami') }}" class="hover:text-[#1d1d1f] transition-colors duration-150">Tentang Kami</a>
              <a href="{{ home_url('/blog') }}" class="hover:text-[#1d1d1f] transition-colors duration-150">Blog</a>
            </nav>

            <!-- CTA -->
            <div class="flex items-center gap-4">
              <a href="{{ home_url('/#kontak') }}" class="hidden sm:inline-flex items-center bg-[#0066cc] hover:bg-[#0055b0] active:scale-[0.97] text-white text-[13px] font-semibold px-4 py-2 rounded-full transition-all duration-150">
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
        <!-- LAYANAN MEGA DROPDOWN &mdash; Apple style                    -->
        <!-- ===================================================== -->
        <div id="layanan-dropdown"
             class="absolute top-full inset-x-0 bg-white border-b border-black/[0.08] overflow-hidden"
             style="opacity:0; transform:translateY(-6px); pointer-events:none; transition: opacity 0.2s ease, transform 0.2s ease;">
          <div class="max-w-[1400px] mx-auto px-6 lg:px-8 py-8">
            <div class="grid grid-cols-[240px_1fr] gap-0">

              <!-- Left: Category list -->
              <div class="border-r border-black/[0.06] pr-6 space-y-1">

                <button data-cat="sprayer"
                        class="layanan-cat-btn w-full flex items-center justify-between text-left px-3 py-2.5 rounded-xl transition-all duration-150 bg-[#f5f5f7] text-[#1d1d1f]">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center flex-shrink-0" style="box-shadow:0 1px 6px rgba(0,0,0,0.08)">
                      <svg class="w-4 h-4 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <span class="text-[13px] font-semibold">Drone Sprayer</span>
                  </div>
                  <svg class="w-3.5 h-3.5 text-[#86868b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                <button data-cat="pemetaan"
                        class="layanan-cat-btn w-full flex items-center justify-between text-left px-3 py-2.5 rounded-xl transition-all duration-150 text-[#515154] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#f5f5f7] flex items-center justify-center flex-shrink-0">
                      <svg class="w-4 h-4 text-[#515154]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
                    <span class="text-[13px] font-semibold">Drone Pemetaan</span>
                  </div>
                  <svg class="w-3.5 h-3.5 text-[#86868b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                <button data-cat="inspeksi"
                        class="layanan-cat-btn w-full flex items-center justify-between text-left px-3 py-2.5 rounded-xl transition-all duration-150 text-[#515154] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#f5f5f7] flex items-center justify-center flex-shrink-0">
                      <svg class="w-4 h-4 text-[#515154]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <span class="text-[13px] font-semibold">Drone Inspeksi</span>
                  </div>
                  <svg class="w-3.5 h-3.5 text-[#86868b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                <button data-cat="pesewaan"
                        class="layanan-cat-btn w-full flex items-center justify-between text-left px-3 py-2.5 rounded-xl transition-all duration-150 text-[#515154] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#f5f5f7] flex items-center justify-center flex-shrink-0">
                      <svg class="w-4 h-4 text-[#515154]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <span class="text-[13px] font-semibold">Pesewaan Drone</span>
                  </div>
                  <svg class="w-3.5 h-3.5 text-[#86868b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                <button data-cat="kursus"
                        class="layanan-cat-btn w-full flex items-center justify-between text-left px-3 py-2.5 rounded-xl transition-all duration-150 text-[#515154] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#f5f5f7] flex items-center justify-center flex-shrink-0">
                      <svg class="w-4 h-4 text-[#515154]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <span class="text-[13px] font-semibold">Kursus & Sertifikasi</span>
                  </div>
                  <svg class="w-3.5 h-3.5 text-[#86868b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

              </div>

              <!-- Right: Sub-items per category -->
              <div class="pl-8">

                <!-- Drone Sprayer -->
                <div id="cat-sprayer" class="layanan-panel">
                  <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mb-5">Seri FERTO &mdash; Pilih kapasitas yang sesuai</p>
                  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach([
                      ['FERTO 22L', 'Kapasitas terbesar. Untuk lahan skala enterprise.', '/ferto-22l'],
                      ['FERTO 15L', 'Keseimbangan kapasitas dan portabilitas optimal.', '/ferto-15l'],
                      ['FERTO 10L', 'Pilihan populer untuk lahan medium.', '/ferto-10l'],
                      ['FERTO 5L', 'Ringan dan lincah untuk lahan berbukit.', '/ferto-5l'],
                    ] as [$name, $desc, $link])
                    <a href="{{ home_url($link) }}"
                       class="group flex flex-col gap-2 p-4 rounded-2xl bg-[#f5f5f7] hover:bg-white transition-all duration-150" style="box-shadow:0 0 0 0 transparent; transition: box-shadow 0.15s, background 0.15s;" onmouseover="this.style.boxShadow='0 2px 16px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                      <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center" style="box-shadow:0 1px 6px rgba(0,0,0,0.08)">
                        <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                      </div>
                      <div>
                        <p class="text-[13px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors">{{ $name }}</p>
                        <p class="text-[12px] text-[#86868b] leading-snug mt-0.5">{{ $desc }}</p>
                      </div>
                    </a>
                    @endforeach
                  </div>
                </div>

                <!-- Drone Pemetaan -->
                <div id="cat-pemetaan" class="layanan-panel hidden">
                  <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mb-5">Layanan pemetaan udara presisi tinggi</p>
                  <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach([
                      ['Pemetaan Topografi', 'Model elevasi digital dan kontur lahan akurat.', '/#layanan'],
                      ['Ortofoto Lahan', 'Foto udara terkoreksi geometri untuk GIS.', '/#layanan'],
                      ['Pemetaan Perkebunan', 'Indeks NDVI dan analisis kesehatan tanaman.', '/#layanan'],
                    ] as [$name, $desc, $link])
                    <a href="{{ home_url($link) }}"
                       class="group flex flex-col gap-2 p-4 rounded-2xl bg-[#f5f5f7] hover:bg-white transition-all duration-150" onmouseover="this.style.boxShadow='0 2px 16px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                      <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center" style="box-shadow:0 1px 6px rgba(0,0,0,0.08)">
                        <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                      </div>
                      <div>
                        <p class="text-[13px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors">{{ $name }}</p>
                        <p class="text-[12px] text-[#86868b] leading-snug mt-0.5">{{ $desc }}</p>
                      </div>
                    </a>
                    @endforeach
                  </div>
                </div>

                <!-- Drone Inspeksi -->
                <div id="cat-inspeksi" class="layanan-panel hidden">
                  <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mb-5">Inspeksi infrastruktur tanpa risiko manusia</p>
                  <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach([
                      ['Inspeksi Termal', 'Deteksi anomali panas pada kabel, panel, dan SUTT.', '/#layanan'],
                      ['Inspeksi Visual', 'Pemeriksaan detail struktur gedung dan jembatan.', '/#layanan'],
                      ['Inspeksi Tambang', 'Pemantauan progress galian dan stockpile material.', '/#layanan'],
                    ] as [$name, $desc, $link])
                    <a href="{{ home_url($link) }}"
                       class="group flex flex-col gap-2 p-4 rounded-2xl bg-[#f5f5f7] hover:bg-white transition-all duration-150" onmouseover="this.style.boxShadow='0 2px 16px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                      <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center" style="box-shadow:0 1px 6px rgba(0,0,0,0.08)">
                        <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                      </div>
                      <div>
                        <p class="text-[13px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors">{{ $name }}</p>
                        <p class="text-[12px] text-[#86868b] leading-snug mt-0.5">{{ $desc }}</p>
                      </div>
                    </a>
                    @endforeach
                  </div>
                </div>

                <!-- Pesewaan -->
                <div id="cat-pesewaan" class="layanan-panel hidden">
                  <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mb-5">Sewa unit per hari atau paket proyek</p>
                  <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach([
                      ['Sewa Harian', 'Unit lengkap + baterai cadangan, tanpa pilot.', '/#kontak'],
                      ['Sewa + Pilot', 'Operator bersertifikat FDS siap bertugas.', '/#kontak'],
                      ['Paket Proyek', 'Solusi end-to-end untuk proyek skala besar.', '/#kontak'],
                    ] as [$name, $desc, $link])
                    <a href="{{ home_url($link) }}"
                       class="group flex flex-col gap-2 p-4 rounded-2xl bg-[#f5f5f7] hover:bg-white transition-all duration-150" onmouseover="this.style.boxShadow='0 2px 16px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                      <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center" style="box-shadow:0 1px 6px rgba(0,0,0,0.08)">
                        <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                      </div>
                      <div>
                        <p class="text-[13px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors">{{ $name }}</p>
                        <p class="text-[12px] text-[#86868b] leading-snug mt-0.5">{{ $desc }}</p>
                      </div>
                    </a>
                    @endforeach
                  </div>
                </div>

                <!-- Kursus -->
                <div id="cat-kursus" class="layanan-panel hidden">
                  <p class="text-[11px] font-semibold text-[#86868b] tracking-wide mb-5">Program pelatihan bersertifikat resmi FDS</p>
                  <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach([
                      ['Pilot Pemula', 'Dasar penerbangan dan regulasi CASR Part 107.', '/#layanan'],
                      ['Pilot Korporasi', 'Program intensif 5 hari untuk tenaga perusahaan.', '/#layanan'],
                      ['Pilot Misi Lanjut', 'Pemetaan, inspeksi termal, dan misi kompleks.', '/#layanan'],
                    ] as [$name, $desc, $link])
                    <a href="{{ home_url($link) }}"
                       class="group flex flex-col gap-2 p-4 rounded-2xl bg-[#f5f5f7] hover:bg-white transition-all duration-150" onmouseover="this.style.boxShadow='0 2px 16px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">
                      <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center" style="box-shadow:0 1px 6px rgba(0,0,0,0.08)">
                        <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6l6.16-3.422"/></svg>
                      </div>
                      <div>
                        <p class="text-[13px] font-semibold text-[#1d1d1f] group-hover:text-[#0066cc] transition-colors">{{ $name }}</p>
                        <p class="text-[12px] text-[#86868b] leading-snug mt-0.5">{{ $desc }}</p>
                      </div>
                    </a>
                    @endforeach
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

        <!-- Mobile nav -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white/95 backdrop-blur-2xl border-t border-black/[0.06] py-6 px-6">
          <nav class="flex flex-col gap-5 text-[17px] font-medium text-[#1d1d1f]">
            <a href="{{ home_url('/#produk') }}" class="mobile-nav-link py-1 border-b border-[#f5f5f7]">Produk</a>
            <details class="border-b border-[#f5f5f7]">
              <summary class="py-1 cursor-pointer list-none flex items-center justify-between">Layanan
                <svg class="w-4 h-4 text-[#86868b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </summary>
              <div class="pl-3 pb-3 mt-2 flex flex-col gap-2 text-[15px] text-[#515154]">
                <a href="{{ home_url('/#produk') }}" class="py-1">Drone Sprayer (FERTO Series)</a>
                <a href="{{ home_url('/#layanan') }}" class="py-1">Drone Pemetaan</a>
                <a href="{{ home_url('/#layanan') }}" class="py-1">Drone Inspeksi</a>
                <a href="{{ home_url('/#kontak') }}" class="py-1">Pesewaan Drone</a>
                <a href="{{ home_url('/#layanan') }}" class="py-1">Kursus & Sertifikasi</a>
              </div>
            </details>
            <a href="{{ home_url('/tentang-kami') }}" class="mobile-nav-link py-1 border-b border-[#f5f5f7]">Tentang Kami</a>
            <a href="{{ home_url('/blog') }}" class="mobile-nav-link py-1 border-b border-[#f5f5f7]">Blog</a>
            <a href="{{ home_url('/#kontak') }}" class="mobile-nav-link py-1 text-[#0066cc]">Hubungi Kami</a>
          </nav>
        </div>
      </header>

      <!-- Dropdown overlay &mdash; blur page content behind mega menu -->
      <div id="layanan-overlay"
           style="position:fixed; top:52px; left:0; right:0; bottom:0; z-index:9998;
                  background:rgba(0,0,0,0.25); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
                  opacity:0; pointer-events:none; transition:opacity 0.2s ease;"
           aria-hidden="true">
      </div>

      <!-- ============================================================ -->
      <!-- MAIN CONTENT                                                  -->
      <!-- ============================================================ -->
      <main id="main">
        @yield('content')
      </main>

      <!-- ============================================================ -->
      <!-- FOOTER &mdash; Apple-style directory, light                        -->
      <!-- ============================================================ -->
      <footer class="bg-[#f5f5f7] border-t border-black/[0.08] pt-14 pb-8">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">

          <!-- Disclaimer -->
          <div class="border-b border-black/[0.08] pb-8 mb-10">
            <p class="text-[11px] text-[#86868b] leading-relaxed max-w-3xl">
              Sertifikasi TKDN 44,85% diterbitkan oleh Kementerian Perindustrian Republik Indonesia untuk seri FERTO 10L dan 22L. Kapasitas penyemprotan 10 Ha/jam merupakan estimasi pada kondisi lapangan ideal. Spesifikasi dapat berubah tanpa pemberitahuan sebelumnya.
            </p>
          </div>

          <!-- Link columns -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-10 pb-10 border-b border-black/[0.08]">
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Produk</h4>
              <ul class="space-y-2.5 text-[12px] text-[#515154]">
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">FERTO 22L</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">FERTO 15L</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">FERTO 10L</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">FERTO 5L</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Granule Spreader</a></li>
              </ul>
            </div>
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Layanan</h4>
              <ul class="space-y-2.5 text-[12px] text-[#515154]">
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Pemetaan Aerial</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Inspeksi Termal</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Sewa Drone</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Pelatihan Pilot</a></li>
              </ul>
            </div>
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Perusahaan</h4>
              <ul class="space-y-2.5 text-[12px] text-[#515154]">
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Tentang FDS</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Newsroom</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Pemerintah &amp; BUMN</a></li>
              </ul>
            </div>
            <div>
              <h4 class="text-[12px] font-semibold text-[#1d1d1f] mb-4 tracking-wide">Dukungan</h4>
              <ul class="space-y-2.5 text-[12px] text-[#515154]">
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Hubungi Tim Sales</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Pusat Layanan</a></li>
                <li><a href="#" class="hover:text-[#1d1d1f] hover:underline">Garansi &amp; Suku Cadang</a></li>
              </ul>
            </div>
          </div>

          <!-- Bottom bar -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 text-[11px] text-[#86868b]">
            <span>Copyright &copy; {{ date('Y') }} Full Drone Solutions. Hak cipta dilindungi.</span>
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
        // ── Layanan Mega Dropdown ────────────────────────────────
        const trigger    = document.getElementById('layanan-trigger');
        const dropdown   = document.getElementById('layanan-dropdown');
        const chevron    = document.getElementById('layanan-chevron');
        let dropdownOpen = false;

        const siteHeader = document.getElementById('site-header');
        const overlay    = document.getElementById('layanan-overlay');

        function openDropdown() {
          dropdownOpen = true;
          dropdown.style.opacity = '1';
          dropdown.style.transform = 'translateY(0)';
          dropdown.style.pointerEvents = 'auto';
          chevron.style.transform = 'rotate(180deg)';
          if (siteHeader) siteHeader.style.borderBottomColor = 'transparent';
          // Tampilkan overlay blur
          if (overlay) { overlay.style.opacity = '1'; overlay.style.pointerEvents = 'auto'; }
        }

        function closeDropdown() {
          dropdownOpen = false;
          dropdown.style.opacity = '0';
          dropdown.style.transform = 'translateY(-6px)';
          dropdown.style.pointerEvents = 'none';
          chevron.style.transform = 'rotate(0deg)';
          if (siteHeader) siteHeader.style.borderBottomColor = '';
          // Sembunyikan overlay
          if (overlay) { overlay.style.opacity = '0'; overlay.style.pointerEvents = 'none'; }
        }

        if (trigger && dropdown && siteHeader) {
          // Buka saat hover pada trigger
          trigger.addEventListener('mouseenter', () => openDropdown());

          // Tutup saat mouse keluar dari seluruh header (navbar + dropdown)
          siteHeader.addEventListener('mouseleave', () => closeDropdown());

          // Tutup juga saat Escape
          document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDropdown();
          });

          // Category switching
          document.querySelectorAll('.layanan-cat-btn').forEach(btn => {
            btn.addEventListener('mouseenter', () => {
              const cat = btn.dataset.cat;

              // Update button styles
              document.querySelectorAll('.layanan-cat-btn').forEach(b => {
                b.classList.remove('bg-[#f5f5f7]', 'text-[#1d1d1f]');
                b.classList.add('text-[#515154]');
                const icon = b.querySelector('.w-8');
                if (icon) { icon.style.background = '#f5f5f7'; icon.style.boxShadow = 'none'; }
              });
              btn.classList.add('bg-[#f5f5f7]', 'text-[#1d1d1f]');
              btn.classList.remove('text-[#515154]');
              const activeIcon = btn.querySelector('.w-8');
              if (activeIcon) { activeIcon.style.background = 'white'; activeIcon.style.boxShadow = '0 1px 6px rgba(0,0,0,0.08)'; }

              // Show correct panel
              document.querySelectorAll('.layanan-panel').forEach(p => p.classList.add('hidden'));
              const panel = document.getElementById('cat-' + cat);
              if (panel) panel.classList.remove('hidden');
            });
          });
        }
      }
    });
    </script>

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
