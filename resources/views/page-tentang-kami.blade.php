@extends('layouts.app')

@section('content')

{{-- ========================================================== --}}
{{-- HERO &mdash; Dark full-bleed, berbeda dari homepage             --}}
{{-- ========================================================== --}}
<section class="pt-[52px] bg-[#1d1d1f] overflow-hidden">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pt-24 pb-0">

    <div class="max-w-[720px]">
      <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-6">
        Full Drone Solutions &middot; Berdiri 2016 &middot; Yogyakarta, Indonesia
      </p>
      <h1 class="text-[48px] sm:text-[64px] lg:text-[80px] font-semibold tracking-[-0.04em] text-white leading-[1.02]">
        Kami membangun teknologi udara yang benar-benar Indonesia.
      </h1>
      <p class="mt-7 text-[18px] sm:text-[20px] text-white/60 max-w-[540px] leading-[1.6]">
        Bukan sekadar mengimpor dan memberi nama lokal. Setiap unit FDS dirancang, diproduksi, dan disertifikasi di workshop kami sendiri.
      </p>
    </div>

    {{-- Hero image &mdash; flush bottom, no top rounding on dark bg --}}
    <div class="mt-16 rounded-t-[2rem] overflow-hidden" style="box-shadow: 0 -8px 48px rgba(0,0,0,0.3);">
      <img
        src="https://picsum.photos/seed/fds-team-workshop-2026/1920/800"
        alt="Tim Full Drone Solutions di workshop Yogyakarta"
        class="w-full h-[320px] sm:h-[480px] lg:h-[560px] object-cover"
      >
    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- STATS &mdash; Dark continuation                                 --}}
{{-- ========================================================== --}}
<section class="bg-[#1d1d1f] border-b border-white/[0.08] py-16">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-10 text-center">
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">2016</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">Tahun Berdiri</p>
      </div>
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">44,85%</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">Nilai TKDN</p>
      </div>
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">4 Seri</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">Lini Produk FERTO</p>
      </div>
      <div>
        <p class="text-[44px] font-semibold tracking-[-0.04em] text-white">100%</p>
        <p class="text-[13px] font-medium text-white/40 mt-1">Software Lokal</p>
      </div>
    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- STORY &mdash; White section, editorial two-column               --}}
{{-- ========================================================== --}}
<section class="bg-white py-24 sm:py-32">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-start">

      {{-- Left: headline sticky --}}
      <div class="lg:sticky lg:top-28">
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-5">Cerita Kami</p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1]">
          Dari workshop kecil, menuju standar industri nasional.
        </h2>
        <div class="mt-8 rounded-2xl overflow-hidden">
          <img src="https://picsum.photos/seed/fds-origin-story/800/600"
               alt="Awal mula FDS di Yogyakarta"
               class="w-full aspect-[4/3] object-cover">
        </div>
      </div>

      {{-- Right: story --}}
      <div class="space-y-8 text-[18px] text-[#515154] leading-[1.7]">
        <p>
          Full Drone Solutions (FDS) lahir dari keyakinan sederhana: bahwa Indonesia mampu memiliki industri drone yang tidak bergantung sepenuhnya pada teknologi asing. Berdiri sejak 2016 di Yogyakarta, FDS memulai perjalanannya dengan merancang prototipe drone pertanian pertama yang sepenuhnya menggunakan komponen dan rekayasa lokal.
        </p>
        <p>
          Setelah bertahun-tahun riset dan pengujian lapangan intensif bersama komunitas petani, insinyur, dan mitra pemerintah, FDS berhasil menghadirkan seri <strong class="text-[#1d1d1f] font-semibold">FERTO</strong> &mdash; lini drone agrikultur dengan nilai TKDN sebesar <strong class="text-[#1d1d1f] font-semibold">44,85%</strong> yang merupakan salah satu tertinggi di segmennya.
        </p>
        <p>
          Namun FDS tidak berhenti di pertanian. Hari ini, ekosistem FDS melayani berbagai sektor industri: pemetaan topografi, inspeksi infrastruktur termal, kehutanan, hingga layanan enterprise untuk instansi pemerintah dan korporasi besar.
        </p>
        <p>
          Visi kami tetap sama: memastikan bahwa solusi drone terbaik untuk kondisi Indonesia dikembangkan oleh orang Indonesia, untuk Indonesia.
        </p>
        <div class="pt-4 border-t border-black/[0.06]">
          <a href="#mitra" class="inline-flex items-center gap-1.5 text-[16px] font-semibold text-[#0066cc] hover:underline">
            Lihat kemitraan strategis kami
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- VALUES &mdash; Dark background, berbeda dari homepage           --}}
{{-- ========================================================== --}}
<section class="bg-[#1d1d1f] py-24 sm:py-32">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-16">
      <p class="text-[13px] font-semibold text-[#6e9fd4] tracking-wide mb-4">Nilai Perusahaan</p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-white leading-[1.1] max-w-[580px]">
        Prinsip yang mendasari setiap produk kami.
      </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 hover:bg-white/[0.09] transition-colors">
        <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center mb-6">
          <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <h3 class="text-[20px] font-semibold text-white mb-3">Integritas Rekayasa</h3>
        <p class="text-[15px] text-white/50 leading-relaxed">Setiap unit melewati pengujian lapangan ketat sebelum dikirimkan. Tidak ada kompromi pada standar kualitas.</p>
      </div>

      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 hover:bg-white/[0.09] transition-colors">
        <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center mb-6">
          <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-[20px] font-semibold text-white mb-3">Kemandirian Teknologi</h3>
        <p class="text-[15px] text-white/50 leading-relaxed">Indonesia memiliki kapasitas untuk menjadi pemain utama industri drone global. FDS adalah bukti nyata keyakinan itu.</p>
      </div>

      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 hover:bg-white/[0.09] transition-colors">
        <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center mb-6">
          <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <h3 class="text-[20px] font-semibold text-white mb-3">Kemitraan Jangka Panjang</h3>
        <p class="text-[15px] text-white/50 leading-relaxed">Kami tidak hanya menjual unit. Kami membangun ekosistem: pelatihan, after-sales, dan pendampingan operasional penuh.</p>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- PARTNERSHIPS &mdash; White, editorial list                      --}}
{{-- ========================================================== --}}
<section id="mitra" class="bg-white py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

      <div>
        <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-5">Kemitraan Strategis</p>
        <h2 class="text-[36px] sm:text-[46px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] mb-6">
          Didukung oleh institusi terbaik.
        </h2>
        <p class="text-[18px] text-[#515154] leading-relaxed max-w-[420px]">
          Kolaborasi kami dengan lembaga nasional dan internasional membuktikan bahwa inovasi drone Indonesia mampu bersaing di panggung global.
        </p>
      </div>

      <div class="divide-y divide-black/[0.06]">

        @foreach([
          ['Pemerintah', 'Bappenas', 'Badan Perencanaan Pembangunan Nasional &mdash; Kolaborasi inovasi teknologi pertanian presisi nasional.'],
          ['Keuangan', 'Bank Indonesia', 'Program pengembangan ekosistem agritech dan pembiayaan inovasi dalam negeri.'],
          ['Akademis', 'Universitas Gadjah Mada', 'Riset bersama pengembangan teknologi presisi dan uji lapangan multi-sektor.'],
          ['Internasional', 'Pemerintah Australia', 'Program bilateral teknologi pertanian dan ketahanan pangan di kawasan Asia Pasifik.'],
          ['Internasional', 'Pemerintah Swiss', 'Inisiatif riset bersama pengembangan drone presisi untuk pertanian berkelanjutan.'],
        ] as [$cat, $name, $desc])
        <div class="py-8 grid grid-cols-12 gap-4 items-center">
          <div class="col-span-3">
            <p class="text-[11px] font-semibold text-[#86868b] tracking-wide">{{ $cat }}</p>
          </div>
          <div class="col-span-9">
            <h3 class="text-[20px] font-semibold text-[#1d1d1f]">{{ $name }}</h3>
            <p class="text-[14px] text-[#515154] mt-1">{{ $desc }}</p>
          </div>
        </div>
        @endforeach

      </div>
    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- CERTIFICATIONS &mdash; Light gray bento                         --}}
{{-- ========================================================== --}}
<section class="bg-[#f5f5f7] py-24 sm:py-32 border-t border-black/[0.06]">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">

    <div class="mb-14">
      <p class="text-[13px] font-semibold text-[#0066cc] tracking-wide mb-4">Sertifikasi & Standar</p>
      <h2 class="text-[36px] sm:text-[48px] font-semibold tracking-[-0.03em] text-[#1d1d1f] leading-[1.1] max-w-[560px]">
        Diakui secara resmi, terverifikasi secara independen.
      </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">

      <div class="lg:col-span-5 bg-[#0066cc] rounded-[2rem] p-10 flex flex-col justify-between min-h-[220px]"
           style="box-shadow: 0 4px 32px rgba(0,102,204,0.2);">
        <p class="text-[12px] font-semibold text-white/60 tracking-wide">Kemenperin RI</p>
        <div>
          <p class="text-[56px] font-semibold text-white tracking-[-0.04em] leading-none">44,85%</p>
          <p class="text-[15px] text-white/80 mt-3 leading-relaxed">TKDN &mdash; Tingkat Komponen Dalam Negeri, diterbitkan resmi oleh Kementerian Perindustrian Republik Indonesia.</p>
        </div>
      </div>

      <div class="lg:col-span-4 bg-white rounded-[2rem] p-8 flex flex-col justify-between min-h-[220px]"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.05);">
        <p class="text-[12px] font-semibold text-[#86868b] tracking-wide">Proteksi Perangkat</p>
        <div>
          <p class="text-[48px] font-semibold text-[#1d1d1f] tracking-[-0.04em] leading-none">IP67</p>
          <p class="text-[14px] text-[#515154] mt-2 leading-relaxed">Anti-debu total dan tahan air hingga kedalaman 1 meter selama 30 menit.</p>
        </div>
      </div>

      <div class="lg:col-span-3 bg-[#1d1d1f] rounded-[2rem] p-8 flex flex-col justify-between min-h-[220px]"
           style="box-shadow: 0 2px 24px rgba(0,0,0,0.08);">
        <p class="text-[12px] font-semibold text-white/40 tracking-wide">Dukungan</p>
        <div>
          <p class="text-[48px] font-semibold text-white tracking-[-0.04em] leading-none">24/7</p>
          <p class="text-[14px] text-white/60 mt-2 leading-relaxed">Dukungan teknis dan spare parts di jaringan kami seluruh Indonesia.</p>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ========================================================== --}}
{{-- CTA &mdash; Dark full-width                                      --}}
{{-- ========================================================== --}}
<section class="bg-[#1d1d1f] py-24 sm:py-32">
  <div class="max-w-[1400px] mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

      <div>
        <h2 class="text-[36px] sm:text-[52px] font-semibold tracking-[-0.03em] text-white leading-[1.1] mb-5">
          Siap bermitra dengan FDS?
        </h2>
        <p class="text-[18px] text-white/60 leading-relaxed max-w-[440px]">
          Baik instansi pemerintah, perusahaan agrikultur besar, atau startup &mdash; tim kami siap berdiskusi.
        </p>
        <div class="mt-8 flex flex-wrap gap-4">
          <a href="{{ home_url('/#kontak') }}"
             class="inline-flex items-center bg-white hover:bg-[#f5f5f7] active:scale-[0.97] text-[#1d1d1f] text-[15px] font-semibold px-7 py-3.5 rounded-full transition-all duration-150">
            Hubungi Tim Sales
          </a>
          <a href="{{ home_url('/blog') }}"
             class="inline-flex items-center text-white/60 text-[15px] font-medium hover:text-white transition-colors gap-1">
            Baca Blog &rsaquo;
          </a>
        </div>
      </div>

      <div class="bg-white/[0.06] border border-white/[0.08] rounded-[2rem] p-8 sm:p-10 space-y-6">
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
          </div>
          <div>
            <p class="text-[11px] font-semibold text-white/40 tracking-wide mb-0.5">Telepon / WA</p>
            <a href="tel:+6281234567890" class="text-[16px] font-semibold text-white hover:text-[#6e9fd4] transition-colors">+62 812-3456-7890</a>
          </div>
        </div>
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          </div>
          <div>
            <p class="text-[11px] font-semibold text-white/40 tracking-wide mb-0.5">Email</p>
            <a href="mailto:sales@fulldronesolutions.co.id" class="text-[16px] font-semibold text-[#6e9fd4] hover:underline">sales@fulldronesolutions.co.id</a>
          </div>
        </div>
        <div class="flex items-start gap-4">
          <div class="w-10 h-10 bg-[#0066cc]/20 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-[#6e9fd4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <div>
            <p class="text-[11px] font-semibold text-white/40 tracking-wide mb-0.5">Lokasi</p>
            <p class="text-[16px] font-semibold text-white">Sleman, D.I. Yogyakarta, Indonesia</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
