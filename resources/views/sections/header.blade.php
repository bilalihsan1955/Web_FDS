<header class="sticky top-0 z-50 bg-[#0b0f17]/95 backdrop-blur-xl border-b border-white/[0.08] text-slate-100 transition-all duration-200">
  <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-12">
    <div class="flex items-center justify-between h-16 sm:h-20">
      
      <!-- Brand Logo -->
      <a href="{{ home_url('/') }}" class="flex items-center gap-3 group">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400 group-hover:border-blue-400 group-hover:bg-blue-500/20 transition-all">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
          </svg>
        </div>
        <div class="flex flex-col">
          <span class="font-bold text-base sm:text-lg tracking-wider text-slate-100 uppercase group-hover:text-blue-400 transition-colors">
            FULL DRONE <span class="text-blue-500">SOLUTIONS</span>
          </span>
          <span class="text-[10px] tracking-widest text-slate-400 uppercase font-mono -mt-1">
            TKDN + BMP Hingga 60,74%
          </span>
        </div>
      </a>

      <!-- Desktop Navigation Links -->
      <nav class="hidden lg:flex items-center gap-7 text-xs font-semibold tracking-wider uppercase text-slate-300">
        <a href="{{ home_url('/') }}" class="hover:text-blue-400 transition-colors py-2">
          Beranda
        </a>

        <!-- ── MEGA DROPDOWN: PRODUK ──────────────────────────── -->
        <div class="group py-6">
          <div class="hover:text-blue-400 transition-colors flex items-center gap-1.5 cursor-pointer py-1">
            <span>Produk</span>
            <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-180 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
          </div>

          <!-- Mega Dropdown Panel -->
          <div class="absolute left-1/2 -translate-x-1/2 top-full w-[94vw] max-w-[1180px] bg-[#0c1017]/95 backdrop-blur-2xl border border-white/[0.08] rounded-2xl shadow-[0_30px_70px_rgba(0,0,0,0.7),inset_0_1px_0_rgba(255,255,255,0.08)] opacity-0 -translate-y-2 pointer-events-none group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-200 ease-out z-50 overflow-hidden">
            
            <div class="grid grid-cols-12 divide-x divide-white/[0.06] p-6 lg:p-8">
              
              <!-- Column 1: Seri FERTO Agrikultur (Col 6) -->
              <div class="col-span-6 pr-6">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-white/[0.06]">
                  <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <span class="text-[11px] font-bold text-slate-200 tracking-wider uppercase">UAV Agrikultur &middot; Seri FERTO</span>
                  </div>
                  <span class="text-[10px] font-mono font-medium text-blue-400 bg-blue-500/10 px-2 py-0.5 rounded border border-blue-500/20">TKDN &middot; SNI</span>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                  @php
                    $ferto_list = [
                      ['slug' => 'ferto-5l', 'name' => 'FERTO 5L', 'cap' => '5 Liter', 'desc' => 'Lahan berbukit & kebun'],
                      ['slug' => 'ferto-10l', 'name' => 'FERTO 10L', 'cap' => '10 Liter', 'desc' => 'Pilihan terlaris kelompok tani'],
                      ['slug' => 'ferto-15l', 'name' => 'FERTO 15L', 'cap' => '17 Liter', 'desc' => 'Komersial & produktivitas 8 Ha/j'],
                      ['slug' => 'ferto-22l', 'name' => 'FERTO 22L', 'cap' => '22 Liter', 'desc' => 'Enterprise perkebunan sawit'],
                      ['slug' => 'ferto-30l', 'name' => 'FERTO 30L', 'cap' => '30 Liter', 'desc' => 'Heavy duty 15 Ha/jam'],
                      ['slug' => 'ferto-50l', 'name' => 'FERTO 50L', 'cap' => '50 Liter', 'desc' => 'Muatan puncak agroindustri'],
                    ];
                  @endphp
                  @foreach($ferto_list as $f)
                  <a href="{{ home_url('/' . $f['slug'] . '/') }}" class="group/item flex items-start gap-3 p-3 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center flex-shrink-0 text-blue-400 font-mono text-[10px] font-bold group-hover/item:bg-blue-600 group-hover/item:text-white transition-colors">
                      {{ str_replace(' Liter', 'L', $f['cap']) }}
                    </div>
                    <div class="min-w-0">
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-blue-400 transition-colors flex items-center gap-1.5">
                        {{ $f['name'] }}
                        <svg class="w-3 h-3 opacity-0 -translate-x-1 group-hover/item:opacity-100 group-hover/item:translate-x-0 transition-all text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                      </div>
                      <p class="text-[11px] text-slate-400 font-normal truncate mt-0.5">{{ $f['desc'] }}</p>
                    </div>
                  </a>
                  @endforeach
                </div>
              </div>

              <!-- Column 2: Enterprise & GIS (Col 4) -->
              <div class="col-span-3 px-6">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-white/[0.06]">
                  <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-[11px] font-bold text-slate-200 tracking-wider uppercase">Spesialis &amp; GIS</span>
                  </div>
                </div>

                <div class="flex flex-col gap-2">
                  <a href="{{ home_url('/deltav/') }}" class="group/item flex items-start gap-3 p-2.5 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <span class="w-7 h-7 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-mono text-[9px] font-bold flex items-center justify-center flex-shrink-0">VTOL</span>
                    <div class="min-w-0">
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-emerald-400 transition-colors">DELTAV</div>
                      <p class="text-[11px] text-slate-400 font-normal leading-tight mt-0.5">Fixed-Wing VTOL &middot; Range 60 km</p>
                    </div>
                  </a>

                  <a href="{{ home_url('/multipurpose/') }}" class="group/item flex items-start gap-3 p-2.5 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <span class="w-7 h-7 rounded-md bg-amber-500/10 border border-amber-500/20 text-amber-400 font-mono text-[9px] font-bold flex items-center justify-center flex-shrink-0">MOD</span>
                    <div class="min-w-0">
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-amber-400 transition-colors">MULTIPURPOSE</div>
                      <p class="text-[11px] text-slate-400 font-normal leading-tight mt-0.5">Inspeksi 150kV &amp; Sensor Termal</p>
                    </div>
                  </a>

                  <a href="{{ home_url('/delfro/') }}" class="group/item flex items-start gap-3 p-2.5 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <span class="w-7 h-7 rounded-md bg-purple-500/10 border border-purple-500/20 text-purple-400 font-mono text-[9px] font-bold flex items-center justify-center flex-shrink-0">LOG</span>
                    <div class="min-w-0">
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-purple-400 transition-colors">DELFRO</div>
                      <p class="text-[11px] text-slate-400 font-normal leading-tight mt-0.5">Kargo Logistik Ringan 10 kg</p>
                    </div>
                  </a>

                  <a href="{{ home_url('/rebo/') }}" class="group/item flex items-start gap-3 p-2.5 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <span class="w-7 h-7 rounded-md bg-teal-500/10 border border-teal-500/20 text-teal-400 font-mono text-[9px] font-bold flex items-center justify-center flex-shrink-0">SEEDB</span>
                    <div class="min-w-0">
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-teal-400 transition-colors">REBO</div>
                      <p class="text-[11px] text-slate-400 font-normal leading-tight mt-0.5">Restorasi Hutan &middot; 20 kg Seedball</p>
                    </div>
                  </a>
                </div>
              </div>

              <!-- Column 3: Platform Highlight & Standar (Col 3) -->
              <div class="col-span-3 pl-6 flex flex-col justify-between">
                <div>
                  <div class="flex items-center gap-2 mb-4 pb-2 border-b border-white/[0.06]">
                    <span class="text-[11px] font-bold text-slate-200 tracking-wider uppercase">FDS Station &amp; Sertifikasi</span>
                  </div>

                  <div class="p-4 rounded-xl bg-gradient-to-br from-blue-900/30 via-slate-900/40 to-slate-950/80 border border-blue-500/20">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="text-[11px] font-bold text-blue-400 uppercase tracking-wider font-mono">FDS STATION GCS</span>
                    </div>
                    <p class="text-[12px] text-slate-300 font-normal leading-relaxed normal-case">
                      Ground Control Station berbahasa Indonesia untuk perencanaan misi otomatis &amp; telemetri *real-time*.
                    </p>
                    <div class="mt-3 pt-3 border-t border-white/[0.08] flex items-center justify-between text-[11px] font-mono text-slate-400">
                      <span>SNI 9199:2023</span>
                      <span>ISO 9001:2015</span>
                    </div>
                  </div>
                </div>

                <div class="pt-4">
                  <a href="#kontak" class="block w-full text-center bg-blue-600 hover:bg-blue-500 text-white text-[12px] font-semibold py-2.5 px-4 rounded-xl shadow-lg shadow-blue-600/20 transition-all normal-case">
                    Jadwalkan Demo Produk &rarr;
                  </a>
                </div>

              </div>

            </div>

            <!-- Bottom Sub-Bar -->
            <div class="bg-white/[0.02] border-t border-white/[0.06] px-8 py-3.5 flex items-center justify-between text-[12px] normal-case text-slate-400">
              <span>Semua produk drone FDS dirakit di Indonesia dengan dukungan servis &amp; suku cadang resmi.</span>
              <a href="{{ home_url('/#solusi') }}" class="text-blue-400 hover:text-blue-300 font-medium flex items-center gap-1">
                Bandingkan Semua Spesifikasi
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </a>
            </div>

          </div>
        </div>

        <!-- ── MEGA DROPDOWN: LAYANAN ─────────────────────────── -->
        <div class="group py-6">
          <div class="hover:text-blue-400 transition-colors flex items-center gap-1.5 cursor-pointer py-1">
            <span>Layanan</span>
            <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-180 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
          </div>

          <!-- Dropdown Panel -->
          <div class="absolute left-1/2 -translate-x-1/2 top-full w-[94vw] max-w-[860px] bg-[#0c1017]/95 backdrop-blur-2xl border border-white/[0.08] rounded-2xl shadow-[0_30px_70px_rgba(0,0,0,0.7),inset_0_1px_0_rgba(255,255,255,0.08)] opacity-0 -translate-y-2 pointer-events-none group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-200 ease-out z-50 overflow-hidden">
            
            <div class="grid grid-cols-2 divide-x divide-white/[0.06] p-6 lg:p-8">
              
              <!-- Column 1: Operasional & Pelatihan -->
              <div class="pr-6">
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-white/[0.06]">
                  <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                  <span class="text-[11px] font-bold text-slate-200 tracking-wider uppercase">Operasional &amp; Pelatihan</span>
                </div>

                <div class="space-y-2">
                  <a href="{{ home_url('/#layanan') }}" class="group/item flex items-start gap-3 p-3 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center flex-shrink-0 group-hover/item:bg-blue-600 group-hover/item:text-white transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-blue-400 transition-colors">Pelatihan &amp; Sertifikasi Pilot</div>
                      <p class="text-[11px] text-slate-400 font-normal mt-0.5">Sertifikasi pilot drone resmi &amp; training lapangan intensif.</p>
                    </div>
                  </a>

                  <a href="{{ home_url('/#layanan') }}" class="group/item flex items-start gap-3 p-3 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center flex-shrink-0 group-hover/item:bg-blue-600 group-hover/item:text-white transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-blue-400 transition-colors">Sewa Armada Drone Agrikultur</div>
                      <p class="text-[11px] text-slate-400 font-normal mt-0.5">Sewa drone sprayer &amp; spreader lengkap pilot berpengalaman.</p>
                    </div>
                  </a>

                  <a href="{{ home_url('/#layanan') }}" class="group/item flex items-start gap-3 p-3 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center flex-shrink-0 group-hover/item:bg-blue-600 group-hover/item:text-white transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-blue-400 transition-colors">After-Sales &amp; Maintenance</div>
                      <p class="text-[11px] text-slate-400 font-normal mt-0.5">Garansi resmi, servis berkala &amp; ketersediaan suku cadang lokal.</p>
                    </div>
                  </a>
                </div>
              </div>

              <!-- Column 2: Survei & Analisis Geospasial -->
              <div class="pl-6">
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-white/[0.06]">
                  <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                  <span class="text-[11px] font-bold text-slate-200 tracking-wider uppercase">Survei &amp; Inspeksi Teknis</span>
                </div>

                <div class="space-y-2">
                  <a href="{{ home_url('/#layanan') }}" class="group/item flex items-start gap-3 p-3 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0 group-hover/item:bg-emerald-600 group-hover/item:text-white transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
                    <div>
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-emerald-400 transition-colors">Pemetaan Aerial &amp; GIS Topografi</div>
                      <p class="text-[11px] text-slate-400 font-normal mt-0.5">Pemodelan 3D, ortofoto beresolusi tinggi, &amp; data CAD/BIM.</p>
                    </div>
                  </a>

                  <a href="{{ home_url('/#layanan') }}" class="group/item flex items-start gap-3 p-3 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0 group-hover/item:bg-emerald-600 group-hover/item:text-white transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-emerald-400 transition-colors">Inspeksi Termal Transmisi &amp; Solar PV</div>
                      <p class="text-[11px] text-slate-400 font-normal mt-0.5">Pemeriksaan sensor IR untuk transmisi 150kV &amp; pipa migas.</p>
                    </div>
                  </a>

                  <a href="{{ home_url('/#layanan') }}" class="group/item flex items-start gap-3 p-3 rounded-xl hover:bg-white/[0.04] border border-transparent hover:border-white/[0.06] transition-all normal-case">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0 group-hover/item:bg-emerald-600 group-hover/item:text-white transition-colors">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                      <div class="text-[13px] font-bold text-slate-100 group-hover/item:text-emerald-400 transition-colors">Analisis NDVI &amp; Kesehatan Tanaman</div>
                      <p class="text-[11px] text-slate-400 font-normal mt-0.5">Monitoring presisi berbasis multispektral untuk perkebunan.</p>
                    </div>
                  </a>
                </div>
              </div>

            </div>

            <!-- Bottom Sub-Bar -->
            <div class="bg-white/[0.02] border-t border-white/[0.06] px-8 py-3.5 flex items-center justify-between text-[12px] normal-case text-slate-400">
              <span>Konsultasi kebutuhan survei &amp; demo armada gratis untuk korporasi &amp; instansi.</span>
              <a href="#kontak" class="text-blue-400 hover:text-blue-300 font-medium flex items-center gap-1">
                Hubungi Spesialis Layanan &rarr;
              </a>
            </div>

          </div>
        </div>

        <a href="{{ home_url('/tentang-kami/') }}" class="hover:text-blue-400 transition-colors py-2">
          Tentang Kami
        </a>
        <a href="#kontak" class="hover:text-blue-400 transition-colors py-2">
          Kontak
        </a>
      </nav>

      <!-- CTA Button & Mobile Menu Button -->
      <div class="flex items-center gap-3 sm:gap-4">
        <a href="#kontak" class="bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white font-semibold text-xs sm:text-sm px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl shadow-lg shadow-blue-600/20 transition-all flex items-center gap-2">
          <span>Jadwalkan Demo</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </a>

        <!-- Mobile Menu Trigger -->
        <button id="mobile-menu-toggle" type="button" class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-slate-100 hover:bg-white/[0.06] transition-colors" aria-label="Toggle navigation">
          <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
          <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

    </div>
  </div>

  <!-- Mobile Menu Dropdown -->
  <div id="mobile-menu" class="hidden lg:hidden border-t border-white/[0.08] bg-[#0b0f17] px-4 py-6 space-y-4 max-h-[85vh] overflow-y-auto">
    <nav class="flex flex-col space-y-3 text-xs uppercase tracking-wider font-semibold text-slate-300">
      <a href="{{ home_url('/') }}" class="mobile-nav-link hover:text-blue-400 py-2 border-b border-white/[0.06]">Beranda</a>
      
      <!-- Mobile Accordion: Produk -->
      <div class="border-b border-white/[0.06] py-2">
        <button onclick="toggleMobileSub(this)" class="w-full flex items-center justify-between hover:text-blue-400 uppercase text-slate-300 text-xs font-semibold tracking-wider">
          <span>Semua Produk Drone (10 Model)</span>
          <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div class="hidden flex-col pl-3 mt-3 space-y-2 text-xs normal-case text-slate-400 transition-all duration-150">
          <div class="text-[10px] font-bold tracking-widest text-slate-500 uppercase font-mono mt-1">UAV Agrikultur</div>
          <a href="{{ home_url('/ferto-5l/') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 flex items-center justify-between text-slate-200">
            <span>FERTO 5L</span>
            <span class="text-[10px] font-mono text-blue-400">5L</span>
          </a>
          <a href="{{ home_url('/ferto-10l/') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 flex items-center justify-between text-slate-200">
            <span>FERTO 10L</span>
            <span class="text-[10px] font-mono text-blue-400">10L &middot; Terlaris</span>
          </a>
          <a href="{{ home_url('/ferto-15l/') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 flex items-center justify-between text-slate-200">
            <span>FERTO 15L</span>
            <span class="text-[10px] font-mono text-blue-400">17L</span>
          </a>
          <a href="{{ home_url('/ferto-22l/') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 flex items-center justify-between text-slate-200">
            <span>FERTO 22L</span>
            <span class="text-[10px] font-mono text-blue-400">22L</span>
          </a>
          <a href="{{ home_url('/ferto-30l/') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 flex items-center justify-between text-slate-200">
            <span>FERTO 30L</span>
            <span class="text-[10px] font-mono text-blue-400">30L</span>
          </a>
          <a href="{{ home_url('/ferto-50l/') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 flex items-center justify-between text-slate-200">
            <span>FERTO 50L</span>
            <span class="text-[10px] font-mono text-blue-400">50L</span>
          </a>

          <div class="text-[10px] font-bold tracking-widest text-slate-500 uppercase font-mono pt-2 border-t border-white/[0.04]">Enterprise &amp; GIS</div>
          <a href="{{ home_url('/deltav/') }}" class="mobile-nav-link hover:text-emerald-400 py-1.5 flex items-center justify-between text-slate-200">
            <span>DELTAV</span>
            <span class="text-[10px] font-mono text-emerald-400">Hybrid VTOL</span>
          </a>
          <a href="{{ home_url('/multipurpose/') }}" class="mobile-nav-link hover:text-amber-400 py-1.5 flex items-center justify-between text-slate-200">
            <span>MULTIPURPOSE</span>
            <span class="text-[10px] font-mono text-amber-400">Inspeksi 150kV</span>
          </a>
          <a href="{{ home_url('/delfro/') }}" class="mobile-nav-link hover:text-purple-400 py-1.5 flex items-center justify-between text-slate-200">
            <span>DELFRO</span>
            <span class="text-[10px] font-mono text-purple-400">Kargo 10kg</span>
          </a>
          <a href="{{ home_url('/rebo/') }}" class="mobile-nav-link hover:text-teal-400 py-1.5 flex items-center justify-between text-slate-200">
            <span>REBO</span>
            <span class="text-[10px] font-mono text-teal-400">Seedball 20kg</span>
          </a>
        </div>
      </div>

      <!-- Mobile Accordion: Layanan -->
      <div class="border-b border-white/[0.06] py-2">
        <button onclick="toggleMobileSub(this)" class="w-full flex items-center justify-between hover:text-blue-400 uppercase text-slate-300 text-xs font-semibold tracking-wider">
          <span>Layanan FDS</span>
          <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div class="hidden flex-col pl-3 mt-3 space-y-2 text-xs normal-case text-slate-400 transition-all duration-150">
          <a href="{{ home_url('/#layanan') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 block text-slate-200">Pelatihan &amp; Sertifikasi Pilot</a>
          <a href="{{ home_url('/#layanan') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 block text-slate-200">Sewa Armada Drone Sprayer</a>
          <a href="{{ home_url('/#layanan') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 block text-slate-200">Pemetaan Aerial &amp; GIS Topografi</a>
          <a href="{{ home_url('/#layanan') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 block text-slate-200">Inspeksi Termal &amp; Infrastruktur</a>
          <a href="{{ home_url('/#layanan') }}" class="mobile-nav-link hover:text-blue-400 py-1.5 block text-slate-200">Purna Jual &amp; Suku Cadang Resmi</a>
        </div>
      </div>

      <a href="{{ home_url('/tentang-kami/') }}" class="mobile-nav-link hover:text-blue-400 py-2 border-b border-white/[0.06]">Tentang FDS &amp; TKDN</a>
      <a href="#kontak" class="mobile-nav-link hover:text-blue-400 py-2">Hubungi Sales &amp; Demo</a>
    </nav>
  </div>
</header>

<script>
  function toggleMobileSub(btn) {
    const content = btn.nextElementSibling;
    const arrow = btn.querySelector('svg');
    const isHidden = content.classList.toggle('hidden');
    content.classList.toggle('flex', !isHidden);
    if (arrow) {
      arrow.classList.toggle('rotate-180', !isHidden);
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('menu-icon-open');
    const iconClose = document.getElementById('menu-icon-close');
    const mobileLinks = document.querySelectorAll('.mobile-nav-link');

    if (toggleBtn && mobileMenu) {
      toggleBtn.addEventListener('click', () => {
        const isHidden = mobileMenu.classList.toggle('hidden');
        if (iconOpen && iconClose) {
          iconOpen.classList.toggle('hidden', !isHidden);
          iconClose.classList.toggle('hidden', isHidden);
        }
      });

      mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
          mobileMenu.classList.add('hidden');
          if (iconOpen && iconClose) {
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
          }
        });
      });
    }
  });
</script>

